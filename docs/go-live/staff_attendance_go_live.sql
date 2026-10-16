-- =====================================================================================
--  STAFF ATTENDANCE GO-LIVE: SQL companion to STAFF_ATTENDANCE_GO_LIVE.md
--  Target: production database (e.g. u774654038_sunrise) via phpMyAdmin > SQL tab.
--  Run section by section, in order. Read each section's note before running it.
--  Every structural statement is safe to run twice (it checks before it changes).
-- =====================================================================================


-- =====================================================================================
--  SECTION 1. PRE-CHECKS  (read-only, run BEFORE anything else)
-- =====================================================================================

-- 1.1 Server version. Needs MySQL >= 5.7.8 or MariaDB >= 10.2.3 (JSON_OBJECT is used by the audit triggers).
SELECT VERSION() AS server_version;

-- 1.2 Can this DB user create triggers? Look for TRIGGER (or ALL PRIVILEGES) on this database.
SHOW GRANTS FOR CURRENT_USER();

-- 1.3 Binary logging. If log_bin = ON and log_bin_trust_function_creators = OFF and the user is not SUPER,
--     trigger creation can fail. The app still works; only the Change Log stays empty (it shows a red warning).
SHOW VARIABLES LIKE 'log_bin';
SHOW VARIABLES LIKE 'log_bin_trust_function_creators';

-- 1.4 Existing triggers on staff_attendance (expect none before go-live, or old trg_sa_audit_* from a test).
SELECT TRIGGER_NAME, ACTION_TIMING, EVENT_MANIPULATION
FROM information_schema.TRIGGERS
WHERE TRIGGER_SCHEMA = DATABASE() AND EVENT_OBJECT_TABLE = 'staff_attendance';

-- 1.5 Baseline counts. Copy these numbers into the go-live sheet; compare after go-live.
SELECT 'staff_attendance' t, COUNT(*) n FROM staff_attendance
UNION ALL SELECT 'staff_attendence_schedules', COUNT(*) FROM staff_attendence_schedules
UNION ALL SELECT 'staff_attendance_type', COUNT(*) FROM staff_attendance_type
UNION ALL SELECT 'staff_leave_request', COUNT(*) FROM staff_leave_request
UNION ALL SELECT 'active staff', COUNT(*) FROM staff WHERE is_active = 1;

-- 1.6 Current timing setup (one row per role + band). Screenshot this for reference.
SELECT r.name AS role, t.type AS band, s.entry_time_from, s.entry_time_to, s.total_institute_hour,
       s.early_half_time, s.early_grace_time
FROM staff_attendence_schedules s
JOIN roles r ON r.id = s.role_id
JOIN staff_attendance_type t ON t.id = s.staff_attendence_type_id
ORDER BY r.name, s.entry_time_from;

-- 1.7 Columns that already exist (the earlier seasonal-timings release may have added some).
SELECT TABLE_NAME, COLUMN_NAME FROM information_schema.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
  AND ((TABLE_NAME = 'staff_attendence_schedules' AND COLUMN_NAME IN ('early_half_time','early_grace_time','effective_from','effective_to','season_tag','is_override','shift_id'))
    OR (TABLE_NAME = 'staff_attendance' AND COLUMN_NAME IN ('attendance_source','shift_id','leave_request_id','pre_leave_type_id','pre_leave_source','pre_leave_remark')))
ORDER BY TABLE_NAME, COLUMN_NAME;


-- =====================================================================================
--  SECTION 2. IN-DATABASE BACKUP  (OPTIONAL extra copy: the app's automatic database update already
--  backs up these four tables as bak_<yymmdd>_<hhmm>_* before changing anything)
--  Also take a full phpMyAdmin Export (SQL, all tables) and download it. These copies are a
--  fast rollback for the tables this release touches. Change the date suffix if you re-run.
-- =====================================================================================

CREATE TABLE bak_golive_staff_attendance            AS SELECT * FROM staff_attendance;
CREATE TABLE bak_golive_staff_attendence_schedules  AS SELECT * FROM staff_attendence_schedules;
CREATE TABLE bak_golive_staff_attendance_type       AS SELECT * FROM staff_attendance_type;
CREATE TABLE bak_golive_staff_leave_request         AS SELECT * FROM staff_leave_request;

-- Check the copies have the same counts as 1.5
SELECT 'bak staff_attendance' t, COUNT(*) n FROM bak_golive_staff_attendance
UNION ALL SELECT 'bak schedules', COUNT(*) FROM bak_golive_staff_attendence_schedules;


-- =====================================================================================
--  SECTION 3. STRUCTURE MIGRATION  (OPTIONAL: the app does all of this by itself)
--
--  Recommended: skip this section. After the new files are uploaded, opening
--  Settings > Attendance Type (as Super Admin) runs every change below automatically,
--  converts the old seasonal timings into shifts and creates the audit triggers.
--
--  Use this section only if you prefer to apply the structure by hand first (e.g. to see
--  errors in phpMyAdmin). The shift conversion and triggers still happen on that first
--  page load either way.
-- =====================================================================================

-- 3.1 Timing table columns (some may exist from the earlier seasonal release)
SET @t := 'staff_attendence_schedules';
SET @s := IF((SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=@t AND COLUMN_NAME='early_half_time')=0,
  'ALTER TABLE `staff_attendence_schedules` ADD COLUMN `early_half_time` TIME NULL AFTER `total_institute_hour`', 'SELECT 1');
PREPARE x FROM @s; EXECUTE x; DEALLOCATE PREPARE x;
SET @s := IF((SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=@t AND COLUMN_NAME='early_grace_time')=0,
  'ALTER TABLE `staff_attendence_schedules` ADD COLUMN `early_grace_time` TIME NULL AFTER `early_half_time`', 'SELECT 1');
PREPARE x FROM @s; EXECUTE x; DEALLOCATE PREPARE x;
SET @s := IF((SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=@t AND COLUMN_NAME='effective_from')=0,
  'ALTER TABLE `staff_attendence_schedules` ADD COLUMN `effective_from` DATE NOT NULL DEFAULT ''2000-01-01'' AFTER `is_active`', 'SELECT 1');
PREPARE x FROM @s; EXECUTE x; DEALLOCATE PREPARE x;
SET @s := IF((SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=@t AND COLUMN_NAME='effective_to')=0,
  'ALTER TABLE `staff_attendence_schedules` ADD COLUMN `effective_to` DATE NULL DEFAULT NULL AFTER `effective_from`', 'SELECT 1');
PREPARE x FROM @s; EXECUTE x; DEALLOCATE PREPARE x;
SET @s := IF((SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=@t AND COLUMN_NAME='season_tag')=0,
  'ALTER TABLE `staff_attendence_schedules` ADD COLUMN `season_tag` VARCHAR(100) NULL DEFAULT ''General Timings'' AFTER `effective_to`', 'SELECT 1');
PREPARE x FROM @s; EXECUTE x; DEALLOCATE PREPARE x;
SET @s := IF((SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=@t AND COLUMN_NAME='is_override')=0,
  'ALTER TABLE `staff_attendence_schedules` ADD COLUMN `is_override` TINYINT(1) NOT NULL DEFAULT 0 AFTER `season_tag`', 'SELECT 1');
PREPARE x FROM @s; EXECUTE x; DEALLOCATE PREPARE x;
SET @s := IF((SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=@t AND COLUMN_NAME='shift_id')=0,
  'ALTER TABLE `staff_attendence_schedules` ADD COLUMN `shift_id` INT(11) NULL DEFAULT NULL, ADD KEY `idx_shift_role` (`shift_id`, `role_id`)', 'SELECT 1');
PREPARE x FROM @s; EXECUTE x; DEALLOCATE PREPARE x;

-- 3.2 Shift master
CREATE TABLE IF NOT EXISTS `staff_shifts` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `shift_type` ENUM('regular','temporary') NOT NULL DEFAULT 'regular',
  `is_active` TINYINT(1) NOT NULL DEFAULT 0,
  `effective_from` DATE NULL DEFAULT NULL,
  `effective_to` DATE NULL DEFAULT NULL,
  `created_at` DATETIME NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_type_active` (`shift_type`, `is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 3.3 Attendance columns: shift snapshot + approved-leave bookkeeping
SET @t := 'staff_attendance';
SET @s := IF((SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=@t AND COLUMN_NAME='shift_id')=0,
  'ALTER TABLE `staff_attendance` ADD COLUMN `shift_id` INT(11) NULL DEFAULT NULL, ADD KEY `idx_att_shift` (`shift_id`)', 'SELECT 1');
PREPARE x FROM @s; EXECUTE x; DEALLOCATE PREPARE x;
SET @s := IF((SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=@t AND COLUMN_NAME='leave_request_id')=0,
  'ALTER TABLE `staff_attendance` ADD COLUMN `leave_request_id` INT(11) NULL DEFAULT NULL', 'SELECT 1');
PREPARE x FROM @s; EXECUTE x; DEALLOCATE PREPARE x;
SET @s := IF((SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=@t AND COLUMN_NAME='pre_leave_type_id')=0,
  'ALTER TABLE `staff_attendance` ADD COLUMN `pre_leave_type_id` INT(11) NULL DEFAULT NULL', 'SELECT 1');
PREPARE x FROM @s; EXECUTE x; DEALLOCATE PREPARE x;
SET @s := IF((SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=@t AND COLUMN_NAME='pre_leave_source')=0,
  'ALTER TABLE `staff_attendance` ADD COLUMN `pre_leave_source` VARCHAR(30) NULL DEFAULT NULL', 'SELECT 1');
PREPARE x FROM @s; EXECUTE x; DEALLOCATE PREPARE x;
SET @s := IF((SELECT COUNT(*) FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=@t AND COLUMN_NAME='pre_leave_remark')=0,
  'ALTER TABLE `staff_attendance` ADD COLUMN `pre_leave_remark` TEXT NULL', 'SELECT 1');
PREPARE x FROM @s; EXECUTE x; DEALLOCATE PREPARE x;

-- 3.4 Change log table (the triggers that fill it are created by the app, see section 5.3)
CREATE TABLE IF NOT EXISTS `staff_attendance_audit_log` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `attendance_id` INT(11) NULL DEFAULT NULL,
  `staff_id` INT(11) NULL DEFAULT NULL,
  `att_date` DATE NULL DEFAULT NULL,
  `action` VARCHAR(30) NOT NULL,
  `changed_fields` VARCHAR(500) NULL DEFAULT NULL,
  `old_values` TEXT NULL,
  `new_values` TEXT NULL,
  `changed_by` INT(11) NULL DEFAULT NULL,
  `changed_by_name` VARCHAR(150) NULL DEFAULT NULL,
  `source` VARCHAR(30) NULL DEFAULT NULL,
  `ip_address` VARCHAR(45) NULL DEFAULT NULL,
  `url` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_staff_date` (`staff_id`, `att_date`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 3.5 Holidays & Working Days (per staff / role / everyone)
CREATE TABLE IF NOT EXISTS `staff_day_overrides` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `from_date` DATE NOT NULL,
  `to_date` DATE NOT NULL,
  `day_type` ENUM('working','holiday') NOT NULL,
  `applies_to` ENUM('all','roles','staff') NOT NULL DEFAULT 'all',
  `target_ids` VARCHAR(2000) NULL DEFAULT NULL,
  `reason` VARCHAR(255) NULL DEFAULT NULL,
  `created_by` INT(11) NULL DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_dates` (`from_date`, `to_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 3.6 Correction requests
CREATE TABLE IF NOT EXISTS `staff_attendance_corrections` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `staff_id` INT(11) NOT NULL,
  `date` DATE NOT NULL,
  `request_type` VARCHAR(30) NOT NULL,
  `requested_in` TIME NULL DEFAULT NULL,
  `requested_out` TIME NULL DEFAULT NULL,
  `reason` VARCHAR(500) NOT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'pending',
  `admin_remark` VARCHAR(500) NULL DEFAULT NULL,
  `approved_in` TIME NULL DEFAULT NULL,
  `approved_out` TIME NULL DEFAULT NULL,
  `previous_values` TEXT NULL,
  `reviewed_by` INT(11) NULL DEFAULT NULL,
  `reviewed_at` DATETIME NULL DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_staff_date` (`staff_id`, `date`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- 3.7 Late-coming policy (single row, starts OFF)
CREATE TABLE IF NOT EXISTS `staff_attendance_policy` (
  `id` INT(11) NOT NULL,
  `late_enabled` TINYINT(1) NOT NULL DEFAULT 0,
  `late_free` INT(11) NOT NULL DEFAULT 0,
  `late_every` INT(11) NOT NULL DEFAULT 3,
  `late_penalty` DECIMAL(3,1) NOT NULL DEFAULT 0.5,
  `updated_by` INT(11) NULL DEFAULT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT IGNORE INTO `staff_attendance_policy` (`id`, `late_enabled`, `late_free`, `late_every`, `late_penalty`) VALUES (1, 0, 0, 3, 0.5);

-- 3.8 "Leave" attendance status (approved leave days)
INSERT INTO `staff_attendance_type` (`type`, `key_value`, `is_active`, `for_qr_attendance`, `long_lang_name`, `long_name_style`, `for_schedule`)
SELECT 'Leave', '<b class="text text-primary">LV</b>', 'yes', 0, 'leave', 'label label-primary', 0
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `staff_attendance_type` WHERE long_lang_name = 'leave' OR LOWER(TRIM(key_value)) = 'lv');


-- =====================================================================================
--  SECTION 4. (NO SQL) Upload files, then open Settings > Attendance Type as Super Admin.
--  That page load converts old seasonal timings into shifts, tags old attendance rows with
--  their shift, and creates the three audit triggers. See the plan document, step 4.
-- =====================================================================================


-- =====================================================================================
--  SECTION 5. VERIFY  (read-only, run right after step 4)
-- =====================================================================================

-- 5.1 All new tables exist (expect 6 rows)
SELECT TABLE_NAME FROM information_schema.TABLES
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME IN ('staff_shifts','staff_attendance_audit_log','staff_day_overrides','staff_attendance_corrections','staff_attendance_policy','staff_attendance');

-- 5.2 Shifts created from the old timings. Expect exactly one regular shift "In force now".
SELECT s.id, s.name, s.shift_type, s.is_active, s.effective_from, s.effective_to,
       (SELECT COUNT(DISTINCT b.role_id) FROM staff_attendence_schedules b WHERE b.shift_id = s.id) AS roles,
       (SELECT COUNT(*) FROM staff_attendance a WHERE a.shift_id = s.id) AS attendance_rows
FROM staff_shifts s ORDER BY s.is_active DESC, s.id;

-- 5.3 Audit triggers (expect 3 rows: trg_sa_audit_ins_*, _upd_*, _del_*)
SELECT TRIGGER_NAME FROM information_schema.TRIGGERS
WHERE TRIGGER_SCHEMA = DATABASE() AND EVENT_OBJECT_TABLE = 'staff_attendance' AND TRIGGER_NAME LIKE 'trg_sa_audit%';

-- 5.4 Every timing row belongs to a shift (expect 0)
SELECT COUNT(*) AS timing_rows_without_shift FROM staff_attendence_schedules WHERE shift_id IS NULL;

-- 5.5 Roles with no regular shift in force today (expect 0 rows; any row = that role gets "no schedule")
SELECT r.id, r.name FROM roles r
WHERE EXISTS (SELECT 1 FROM staff_roles sr JOIN staff st ON st.id = sr.staff_id AND st.is_active = 1 WHERE sr.role_id = r.id)
  AND NOT EXISTS (
    SELECT 1 FROM staff_shifts s JOIN staff_attendence_schedules b ON b.shift_id = s.id AND b.role_id = r.id
    WHERE s.is_active = 1 AND s.shift_type = 'regular'
      AND IFNULL(s.effective_from, '0000-01-01') <= CURDATE() AND IFNULL(s.effective_to, '9999-12-31') >= CURDATE());

-- 5.6 Roles with MORE than one regular shift in force today (expect 0 rows)
SELECT b.role_id, COUNT(DISTINCT s.id) AS shifts_in_force
FROM staff_shifts s JOIN staff_attendence_schedules b ON b.shift_id = s.id
WHERE s.is_active = 1 AND s.shift_type = 'regular'
  AND IFNULL(s.effective_from, '0000-01-01') <= CURDATE() AND IFNULL(s.effective_to, '9999-12-31') >= CURDATE()
GROUP BY b.role_id HAVING COUNT(DISTINCT s.id) > 1;

-- 5.7 Leave status and policy row exist (expect 1 row each)
SELECT id, type, long_lang_name FROM staff_attendance_type WHERE long_lang_name = 'leave';
SELECT * FROM staff_attendance_policy;

-- 5.8 Nothing lost: attendance row count must equal the 1.5 baseline
SELECT COUNT(*) AS staff_attendance_rows_now FROM staff_attendance;

-- 5.9 Change log is recording: make one small attendance edit in the app, then run this
SELECT id, action, staff_id, att_date, changed_fields, changed_by_name, source, created_at
FROM staff_attendance_audit_log ORDER BY id DESC LIMIT 10;


-- =====================================================================================
--  SECTION 6. DATA HEALTH CHECKS  (read-only; decide on fixes with the plan, step 6)
--  Change @from / @to to the month you want to check.
-- =====================================================================================

SET @from := CAST(DATE_FORMAT(CURDATE(), '%Y-%m-01') AS DATE), @to := LAST_DAY(CURDATE());
-- For another month, e.g. October 2026:  SET @from := DATE '2026-10-01', @to := DATE '2026-10-31';

-- 6.1 Status counts for the month
SELECT t.type, COUNT(a.id) AS days
FROM staff_attendance_type t
LEFT JOIN staff_attendance a ON a.staff_attendance_type_id = t.id AND a.date BETWEEN @from AND @to
GROUP BY t.id, t.type ORDER BY t.id;

-- 6.2 Remarks repeated by old syncs ("Late: .. | Late: ..")
SELECT COUNT(*) AS repeated_remarks FROM staff_attendance
WHERE date BETWEEN @from AND @to AND (remark LIKE '%Late:%Late:%' OR remark LIKE '%Early Out:%Early Out:%');

-- 6.3 Absent on a Sunday with no punch (should usually be Holiday)
SELECT a.date, COUNT(*) AS rows_absent_on_sunday FROM staff_attendance a
WHERE a.date BETWEEN @from AND @to AND DAYOFWEEK(a.date) = 1
  AND a.staff_attendance_type_id = (SELECT id FROM staff_attendance_type WHERE long_lang_name = 'absent' LIMIT 1)
  AND (a.in_time IS NULL OR a.in_time = '00:00:00') AND (a.out_time IS NULL OR a.out_time = '00:00:00')
GROUP BY a.date;

-- 6.4 Approved leave on a working day that is not shown as Leave (fix: open the leave and save it again,
--     or set it back to pending and approve again; attendance then updates itself).
--     Sundays are left out: leave is not counted on off days (a Sunday Absent is fixed by 7.1).
SELECT r.id AS leave_request_id, CONCAT(s.name, ' ', s.surname) AS staff, r.leave_from, r.leave_to, a.date, t.type AS shown_as
FROM staff_leave_request r
JOIN staff s ON s.id = r.staff_id
JOIN staff_attendance a ON a.staff_id = r.staff_id AND a.date BETWEEN r.leave_from AND r.leave_to
JOIN staff_attendance_type t ON t.id = a.staff_attendance_type_id
WHERE r.status = 'approved' AND DAYOFWEEK(a.date) <> 1
  AND (a.leave_request_id IS NULL OR a.leave_request_id <> r.id) AND t.long_lang_name NOT IN ('leave', 'holiday');

-- 6.5 Present with no punch at all (marked by hand; confirm with HR they are genuine)
SELECT a.date, COUNT(*) AS present_without_punch FROM staff_attendance a
WHERE a.date BETWEEN @from AND @to
  AND a.staff_attendance_type_id = (SELECT id FROM staff_attendance_type WHERE long_lang_name = 'present' LIMIT 1)
  AND (a.in_time IS NULL OR a.in_time = '00:00:00') AND (a.out_time IS NULL OR a.out_time = '00:00:00')
GROUP BY a.date ORDER BY a.date;

-- 6.6 Settings mistakes: "Early End Half" at or after the day end (half-day rule is skipped for these roles)
SELECT sh.name AS shift, r.name AS role, MAX(b.early_half_time) AS early_end_half, MAX(b.entry_time_to) AS day_end
FROM staff_attendence_schedules b
JOIN staff_shifts sh ON sh.id = b.shift_id AND sh.is_active = 1
JOIN roles r ON r.id = b.role_id
GROUP BY sh.name, r.name
HAVING MAX(b.early_half_time) IS NOT NULL AND MAX(b.early_half_time) >= MAX(b.entry_time_to);

-- 6.7 Active staff with no biometric punch in the last 30 days. The sync matches the machine code to the
--     Biometric ID or the Employee ID; anyone listed here is probably not mapped (or not using the machine).
SELECT s.id, CONCAT(s.name, ' ', s.surname) AS staff, s.employee_id, s.biometric_emp_id
FROM staff s
WHERE s.is_active = 1
  AND NOT EXISTS (SELECT 1 FROM staff_attendance a WHERE a.staff_id = s.id AND a.biometric_attendence = 1
                  AND a.date >= CURDATE() - INTERVAL 30 DAY AND (a.in_time > '00:00:00' OR a.out_time > '00:00:00'))
ORDER BY s.name;


-- =====================================================================================
--  SECTION 7. OPTIONAL DATA FIXES  (WRITE: only after section 2 backup, one at a time)
-- =====================================================================================

-- 7.1 Sunday, no punch, marked Absent by the device sync  ->  Holiday.
--     Rows set by hand, leave and duty-pass rows are not touched. Preview first:
SELECT a.id, a.staff_id, a.date FROM staff_attendance a
WHERE a.date BETWEEN @from AND @to AND DAYOFWEEK(a.date) = 1
  AND a.staff_attendance_type_id = (SELECT id FROM staff_attendance_type WHERE long_lang_name = 'absent' LIMIT 1)
  AND (a.in_time IS NULL OR a.in_time = '00:00:00') AND (a.out_time IS NULL OR a.out_time = '00:00:00')
  AND IFNULL(a.attendance_source, '') NOT IN ('manual', 'leave', 'duty_pass');
--     If a Sunday was a real working day (e.g. 4 Oct), add it on the Holidays & Working Days page instead and
--     exclude it here with  AND a.date <> 'YYYY-MM-DD'.  Then apply:
SET @att_audit_user_name := 'Go-live fix 7.1', @att_audit_source := 'cleanup', @att_audit_url := 'go-live sql 7.1';
UPDATE staff_attendance a
SET a.staff_attendance_type_id = (SELECT id FROM (SELECT id FROM staff_attendance_type WHERE long_lang_name = 'holiday' LIMIT 1) h)
WHERE a.date BETWEEN @from AND @to AND DAYOFWEEK(a.date) = 1
  AND a.staff_attendance_type_id = (SELECT id FROM (SELECT id FROM staff_attendance_type WHERE long_lang_name = 'absent' LIMIT 1) ab)
  AND (a.in_time IS NULL OR a.in_time = '00:00:00') AND (a.out_time IS NULL OR a.out_time = '00:00:00')
  AND IFNULL(a.attendance_source, '') NOT IN ('manual', 'leave', 'duty_pass');

-- 7.2 Repeated remarks (6.2) and statuses set by the old noon rule: these need the PHP clean-up script
--     (same one used on the local copy for October). Ask for it to be packaged for production; do not
--     hand-edit remarks with SQL.


-- =====================================================================================
--  SECTION 8. ROLLBACK  (only if go-live has to be undone)
-- =====================================================================================

-- 8.1 Stop the audit triggers (generates the DROP statements; copy and run the output)
SELECT CONCAT('DROP TRIGGER IF EXISTS `', TRIGGER_NAME, '`;') AS run_these
FROM information_schema.TRIGGERS
WHERE TRIGGER_SCHEMA = DATABASE() AND EVENT_OBJECT_TABLE = 'staff_attendance' AND TRIGGER_NAME LIKE 'trg_sa_audit%';

-- 8.2 Put attendance values back exactly as they were at the backup (rows created after the backup are kept).
UPDATE staff_attendance a JOIN bak_golive_staff_attendance b ON b.id = a.id
SET a.staff_attendance_type_id = b.staff_attendance_type_id, a.in_time = b.in_time, a.out_time = b.out_time,
    a.remark = b.remark, a.attendance_source = b.attendance_source;

-- 8.3 Timing rows back to the backup (only needed if shift settings were edited after go-live)
-- DELETE FROM staff_attendence_schedules;
-- INSERT INTO staff_attendence_schedules SELECT * FROM bak_golive_staff_attendence_schedules;
--   (if 8.3 fails on column count because shift_id was added, list the columns explicitly)

-- 8.4 Then re-upload the previous version of the files (from the file backup / git).
--     New tables and columns can stay: the old code ignores them.


-- =====================================================================================
--  SECTION 9. CLEAN-UP  (2-4 weeks after a stable go-live)
-- =====================================================================================
-- DROP TABLE bak_golive_staff_attendance;
-- DROP TABLE bak_golive_staff_attendence_schedules;
-- DROP TABLE bak_golive_staff_attendance_type;
-- DROP TABLE bak_golive_staff_leave_request;
