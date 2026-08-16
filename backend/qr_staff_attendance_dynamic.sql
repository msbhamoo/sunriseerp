-- =====================================================================
-- QR staff attendance: add DYNAMIC (auto-rotating) QR mode.
-- Idempotent. Additive only.
-- =====================================================================

-- 1) Allow 'dynamic' as a qr_mode value.
ALTER TABLE `staff_qr_attendance_setting`
  MODIFY COLUMN `qr_mode` ENUM('daily','static','dynamic') NOT NULL DEFAULT 'daily';

-- 2) Rotation interval (seconds) — how often the code changes in dynamic mode.
SET @c1 := (SELECT COUNT(*) FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='staff_qr_attendance_setting'
              AND COLUMN_NAME='dynamic_interval_seconds');
SET @s1 := IF(@c1=0,
  'ALTER TABLE `staff_qr_attendance_setting` ADD COLUMN `dynamic_interval_seconds` INT(11) NOT NULL DEFAULT 30',
  'SELECT 1');
PREPARE st1 FROM @s1; EXECUTE st1; DEALLOCATE PREPARE st1;

-- 3) Secret used to derive the time-based rotating token (like a TOTP seed).
SET @c2 := (SELECT COUNT(*) FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='staff_qr_attendance_setting'
              AND COLUMN_NAME='dynamic_secret');
SET @s2 := IF(@c2=0,
  'ALTER TABLE `staff_qr_attendance_setting` ADD COLUMN `dynamic_secret` VARCHAR(64) NULL DEFAULT NULL',
  'SELECT 1');
PREPARE st2 FROM @s2; EXECUTE st2; DEALLOCATE PREPARE st2;
