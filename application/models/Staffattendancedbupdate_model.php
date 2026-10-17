<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Versioned database update for the staff attendance module.
 *
 * Runs once (automatically on the first page load that needs it, or from
 * Staff Attendance > Database Update). Each run:
 *   1. backs up the tables it touches into bak_<stamp>_<table> and checks the row counts,
 *   2. applies only additive changes: new columns, new tables, one new "Leave" status,
 *      shift links for existing rows, and the change-log triggers,
 *   3. compares every existing row's original columns with the backup; any difference marks
 *      the run as failed (with a one-click restore from the backup),
 *   4. records the result in app_db_updates so it never runs twice.
 * If the backup cannot be made, nothing is changed.
 */
class Staffattendancedbupdate_model extends CI_Model
{
    const CODE  = 'staff_attendance_2026_10';
    const TITLE = 'Staff attendance: shifts, change log, leave, corrections, holidays, late policy';
    const LOG_TABLE = 'app_db_updates';
    const MAX_AUTO_ATTEMPTS = 3;

    private static $current = null;
    private static $running = false;
    private $log = array();

    /** Tables backed up before the update, with the original columns compared afterwards. */
    private function trackedTables()
    {
        return array(
            'staff_attendance'           => array('staff_id', 'date', 'staff_attendance_type_id', 'in_time', 'out_time', 'remark', 'attendance_source', 'is_active', 'biometric_attendence', 'qrcode_attendance'),
            'staff_attendence_schedules' => array('role_id', 'staff_attendence_type_id', 'entry_time_from', 'entry_time_to', 'total_institute_hour', 'early_half_time', 'early_grace_time'),
            'staff_attendance_type'      => array('type', 'key_value', 'is_active', 'long_lang_name'),
            'staff_leave_request'        => array('staff_id', 'leave_type_id', 'leave_from', 'leave_to', 'status'),
        );
    }

    // =================================================================
    //  Entry points
    // =================================================================

    /** Called by the attendance models: runs the update if it has not been applied yet. */
    public function ensure()
    {
        if (self::$running || $this->isCurrent()) {
            return;
        }
        $row = $this->getRecord();
        if ($row && (int) $row['attempts'] >= self::MAX_AUTO_ATTEMPTS) {
            return; // stop retrying automatically; a Super Admin can run it from the Database Update page
        }
        $this->run('auto');
    }

    public function isCurrent()
    {
        if (self::$current === null) {
            self::$current = false;
            if ($this->db->table_exists(self::LOG_TABLE)) {
                $r = $this->db->where('code', self::CODE)->get(self::LOG_TABLE)->row_array();
                self::$current = $r && $r['status'] === 'done';
            }
        }
        return self::$current;
    }

    public function getRecord()
    {
        if (!$this->db->table_exists(self::LOG_TABLE)) {
            return null;
        }
        return $this->db->where('code', self::CODE)->get(self::LOG_TABLE)->row_array();
    }

    /**
     * Run (or re-run) the update. Returns array('status' => done|failed|busy, 'log' => [...]).
     * $by: 'auto' or the staff id of the admin who clicked "Run".
     */
    public function run($by = 'auto')
    {
        if (self::$running) {
            return array('status' => 'busy', 'log' => array('Already running in this request.'));
        }
        self::$running = true;
        $this->log = array();
        $debug = $this->db->db_debug;
        $this->db->db_debug = false; // errors are collected into the log instead of stopping the page

        $lock = $this->db->query("SELECT GET_LOCK('staff_att_db_update', 60) AS l")->row();
        if (!$lock || (int) $lock->l !== 1) {
            self::$running = false;
            $this->db->db_debug = $debug;
            return array('status' => 'busy', 'log' => array('Another update is in progress. Try again in a minute.'));
        }

        try {
            $this->_ensureLogTable();
            self::$current = null;
            if ($this->isCurrent() && $by === 'auto') {
                return array('status' => 'done', 'log' => array('Already applied.'));
            }
            $stamp  = date('ymd_Hi');
            $prefix = 'bak_' . $stamp . '_';
            $this->_record(array('status' => 'running', 'backup_prefix' => $prefix, 'started_at' => date('Y-m-d H:i:s'), 'run_by' => (string) $by), true);

            // 1. Backup
            $this->_log('Step 1/4: backing up tables with prefix ' . $prefix);
            foreach (array_keys($this->trackedTables()) as $t) {
                if (!$this->db->table_exists($t)) {
                    $this->_log("  - $t does not exist, skipped");
                    continue;
                }
                $bak = $prefix . $t;
                if (!$this->db->table_exists($bak)) {
                    $this->_q("CREATE TABLE `$bak` AS SELECT * FROM `$t`", "back up $t");
                }
                $live = (int) $this->db->query("SELECT COUNT(*) c FROM `$t`")->row()->c;
                $copy = $this->db->table_exists($bak) ? (int) $this->db->query("SELECT COUNT(*) c FROM `$bak`")->row()->c : -1;
                if ($live !== $copy) {
                    throw new Exception("Backup of $t is incomplete ($copy of $live rows). Nothing was changed.");
                }
                $this->_log("  - $t: $live rows copied to $bak");
            }

            // 2. Structure (additive only)
            $this->_log('Step 2/4: adding new columns and tables');
            $this->_applyStructure();

            // 3. Data links for existing rows (new columns only) + triggers
            $this->_log('Step 3/4: linking existing timings and attendance to shifts; change-log triggers');
            $this->_migrateSeasonsToShifts();
            $this->load->model('staffattendanceaudit_model');
            $trig = $this->staffattendanceaudit_model->installTriggers();
            $this->_log('  - change-log triggers: ' . ($trig ? 'installed' : 'NOT installed (database user lacks TRIGGER permission; everything else works)'));

            // 4. Prove existing data is unchanged
            $this->_log('Step 4/4: comparing every existing row with the backup');
            $changed = $this->compareWithBackup($prefix);
            $total = 0;
            foreach ($changed as $t => $n) {
                $this->_log("  - $t: " . ($n === null ? 'not compared (no backup)' : "$n row(s) differ"));
                $total += (int) $n;
            }
            if ($total > 0) {
                throw new Exception("$total existing row(s) differ from the backup. Use 'Restore from backup' on the Database Update page.");
            }

            $this->_log('Done. Existing attendance data is unchanged.');
            $this->_record(array('status' => 'done', 'finished_at' => date('Y-m-d H:i:s')));
            self::$current = true;
            return array('status' => 'done', 'log' => $this->log);
        } catch (Throwable $e) {
            $this->_log('FAILED: ' . $e->getMessage());
            $this->_record(array('status' => 'failed', 'finished_at' => date('Y-m-d H:i:s')));
            log_message('error', 'Staff attendance DB update failed: ' . $e->getMessage());
            return array('status' => 'failed', 'log' => $this->log);
        } finally {
            $this->db->query("SELECT RELEASE_LOCK('staff_att_db_update')");
            $this->db->db_debug = $debug;
            self::$running = false;
        }
    }

    /**
     * Rows whose original columns differ from the backup (or are missing), per table.
     * Rows added after the backup are not counted. Returns [table => count|null].
     */
    public function compareWithBackup($prefix)
    {
        $out = array();
        foreach ($this->trackedTables() as $t => $cols) {
            $bak = $prefix . $t;
            if (!$this->db->table_exists($bak) || !$this->db->table_exists($t)) {
                $out[$t] = null;
                continue;
            }
            $cmp = array();
            foreach ($cols as $c) {
                if ($this->db->field_exists($c, $bak) && $this->db->field_exists($c, $t)) {
                    $cmp[] = "a.`$c` <=> b.`$c`";
                }
            }
            $cond = $cmp ? implode(' AND ', $cmp) : '1';
            $out[$t] = (int) $this->db->query("SELECT COUNT(*) c FROM `$bak` b LEFT JOIN `$t` a ON a.id = b.id
                                              WHERE a.id IS NULL OR NOT ($cond)")->row()->c;
        }
        return $out;
    }

    /** Put the original columns of every backed-up row back to the backup values (new columns are kept). */
    public function restoreFromBackup($prefix)
    {
        $debug = $this->db->db_debug;
        $this->db->db_debug = false;
        $done = array();
        foreach ($this->trackedTables() as $t => $cols) {
            $bak = $prefix . $t;
            if (!$this->db->table_exists($bak)) {
                continue;
            }
            $set = array();
            foreach ($cols as $c) {
                if ($this->db->field_exists($c, $bak) && $this->db->field_exists($c, $t)) {
                    $set[] = "a.`$c` = b.`$c`";
                }
            }
            if ($set) {
                $this->db->query("UPDATE `$t` a JOIN `$bak` b ON b.id = a.id SET " . implode(', ', $set));
                $done[$t] = $this->db->affected_rows();
            }
        }
        $this->db->db_debug = $debug;
        return $done;
    }

    /** bak_* tables made by this update. */
    public function listBackups()
    {
        $rows = $this->db->query("SELECT TABLE_NAME, TABLE_ROWS, CREATE_TIME FROM information_schema.TABLES
                                  WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME REGEXP '^bak_[0-9]{6}_[0-9]{4}_'
                                  ORDER BY TABLE_NAME DESC")->result_array();
        return $rows;
    }

    // =================================================================
    //  Internals
    // =================================================================

    private function _log($m)
    {
        $this->log[] = '[' . date('H:i:s') . '] ' . $m;
    }

    private function _q($sql, $what)
    {
        if (!$this->db->query($sql)) {
            $err = $this->db->error();
            throw new Exception("Could not $what: " . $err['message']);
        }
    }

    private function _ensureLogTable()
    {
        if (!$this->db->table_exists(self::LOG_TABLE)) {
            $this->_q("CREATE TABLE `" . self::LOG_TABLE . "` (
                `code` VARCHAR(60) NOT NULL,
                `title` VARCHAR(255) NULL DEFAULT NULL,
                `status` VARCHAR(20) NOT NULL,
                `attempts` INT(11) NOT NULL DEFAULT 0,
                `backup_prefix` VARCHAR(40) NULL DEFAULT NULL,
                `run_by` VARCHAR(30) NULL DEFAULT NULL,
                `log` MEDIUMTEXT NULL,
                `started_at` DATETIME NULL DEFAULT NULL,
                `finished_at` DATETIME NULL DEFAULT NULL,
                PRIMARY KEY (`code`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8", 'create the update log table');
        }
    }

    private function _record($fields, $new_attempt = false)
    {
        $fields['log'] = implode("\n", $this->log);
        $row = $this->getRecord();
        if ($row) {
            if ($new_attempt) {
                $fields['attempts'] = (int) $row['attempts'] + 1;
            }
            $this->db->where('code', self::CODE)->update(self::LOG_TABLE, $fields);
        } else {
            $fields['code']     = self::CODE;
            $fields['title']    = self::TITLE;
            $fields['attempts'] = 1;
            $this->db->insert(self::LOG_TABLE, $fields);
        }
    }

    private function _addColumn($table, $column, $definition)
    {
        if (!$this->db->table_exists($table) || $this->db->field_exists($column, $table)) {
            return;
        }
        $this->_q("ALTER TABLE `$table` ADD COLUMN `$column` $definition", "add $table.$column");
        $this->_log("  - added column $table.$column");
    }

    private function _addIndex($table, $name, $columns)
    {
        $exists = (int) $this->db->query("SELECT COUNT(*) c FROM information_schema.STATISTICS
                                          WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = " . $this->db->escape($table) . "
                                            AND INDEX_NAME = " . $this->db->escape($name))->row()->c;
        if ($exists) {
            return;
        }
        $this->_q("ALTER TABLE `$table` ADD KEY `$name` ($columns)", "add index $table.$name");
        $this->_log("  - added index $table.$name");
    }

    private function _createTable($table, $sql)
    {
        if ($this->db->table_exists($table)) {
            return;
        }
        $this->_q($sql, "create table $table");
        $this->_log("  - created table $table");
    }

    private function _applyStructure()
    {
        // Timing table
        $s = 'staff_attendence_schedules';
        $this->_addColumn($s, 'early_half_time', "TIME NULL AFTER `total_institute_hour`");
        $this->_addColumn($s, 'early_grace_time', "TIME NULL AFTER `early_half_time`");
        $this->_addColumn($s, 'day_start_time', "TIME NULL AFTER `total_institute_hour`");
        $this->_addColumn($s, 'effective_from', "DATE NOT NULL DEFAULT '2000-01-01' AFTER `is_active`");
        $this->_addColumn($s, 'effective_to', "DATE NULL DEFAULT NULL AFTER `effective_from`");
        $this->_addColumn($s, 'season_tag', "VARCHAR(100) NULL DEFAULT 'General Timings' AFTER `effective_to`");
        $this->_addColumn($s, 'is_override', "TINYINT(1) NOT NULL DEFAULT 0 AFTER `season_tag`");
        $this->_addColumn($s, 'shift_id', "INT(11) NULL DEFAULT NULL");
        $this->_addIndex($s, 'idx_shift_role', '`shift_id`, `role_id`');

        $this->_createTable('staff_shifts', "CREATE TABLE `staff_shifts` (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");

        // Attendance: shift snapshot + approved-leave bookkeeping
        $a = 'staff_attendance';
        $this->_addColumn($a, 'shift_id', "INT(11) NULL DEFAULT NULL");
        $this->_addIndex($a, 'idx_att_shift', '`shift_id`');
        $this->_addColumn($a, 'leave_request_id', "INT(11) NULL DEFAULT NULL");
        $this->_addColumn($a, 'pre_leave_type_id', "INT(11) NULL DEFAULT NULL");
        $this->_addColumn($a, 'pre_leave_source', "VARCHAR(30) NULL DEFAULT NULL");
        $this->_addColumn($a, 'pre_leave_remark', "TEXT NULL");

        $this->_createTable('staff_attendance_audit_log', "CREATE TABLE `staff_attendance_audit_log` (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");

        $this->_createTable('staff_day_overrides', "CREATE TABLE `staff_day_overrides` (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");

        $this->_createTable('staff_attendance_corrections', "CREATE TABLE `staff_attendance_corrections` (
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
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");

        $this->_createTable('staff_attendance_policy', "CREATE TABLE `staff_attendance_policy` (
            `id` INT(11) NOT NULL,
            `late_enabled` TINYINT(1) NOT NULL DEFAULT 0,
            `late_free` INT(11) NOT NULL DEFAULT 0,
            `late_every` INT(11) NOT NULL DEFAULT 3,
            `late_penalty` DECIMAL(3,1) NOT NULL DEFAULT 0.5,
            `updated_by` INT(11) NULL DEFAULT NULL,
            `updated_at` DATETIME NULL DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8");
        $this->db->query("INSERT IGNORE INTO `staff_attendance_policy` (`id`, `late_enabled`, `late_free`, `late_every`, `late_penalty`) VALUES (1, 0, 0, 3, 0.5)");

        // New status for approved leave (a new row; existing statuses untouched)
        $has_leave = $this->db->query("SELECT id FROM staff_attendance_type WHERE long_lang_name = 'leave' OR LOWER(TRIM(key_value)) = 'lv' LIMIT 1")->row();
        if (!$has_leave) {
            $this->_q("INSERT INTO staff_attendance_type (`type`, `key_value`, `is_active`, `for_qr_attendance`, `long_lang_name`, `long_name_style`, `for_schedule`)
                       VALUES ('Leave', '<b class=\"text text-primary\">LV</b>', 'yes', 0, 'leave', 'label label-primary', 0)", 'add the Leave status');
            $this->_log('  - added attendance status "Leave"');
        }
    }

    /**
     * Turn the old seasonal timing groups (season_tag + dates + is_override) into shifts and link
     * each timing row and each existing attendance row to its shift. Only the new shift_id columns
     * are written; no existing value changes.
     */
    private function _migrateSeasonsToShifts()
    {
        $groups = $this->db->query("SELECT season_tag, effective_from, effective_to, is_override
                                    FROM staff_attendence_schedules WHERE shift_id IS NULL
                                    GROUP BY season_tag, effective_from, effective_to, is_override
                                    ORDER BY is_override ASC, effective_from ASC")->result_array();
        if (empty($groups)) {
            $this->_log('  - timings already linked to shifts');
            return;
        }
        $today = date('Y-m-d');
        $now   = date('Y-m-d H:i:s');
        $made  = array();
        foreach ($groups as $g) {
            $is_temp = !empty($g['is_override']);
            $from    = $g['effective_from'];
            $to      = $g['effective_to'];
            $active  = $is_temp ? ((!empty($to) && $to >= $today) ? 1 : 0) : (($from <= $today && (empty($to) || $to >= $today)) ? 1 : 0);
            $name    = !empty($g['season_tag']) ? $g['season_tag'] : ($is_temp ? 'Temporary Timings' : 'General Timings');
            $this->db->insert('staff_shifts', array(
                'name'           => $name,
                'shift_type'     => $is_temp ? 'temporary' : 'regular',
                'is_active'      => $active,
                'effective_from' => $is_temp ? $from : ($from > '2000-01-01' ? $from : null),
                'effective_to'   => $is_temp ? ($to ?: $from) : ($to ?: null),
                'created_at'     => $now,
                'updated_at'     => $now,
            ));
            $sid = (int) $this->db->insert_id();
            $this->_q("UPDATE staff_attendence_schedules SET shift_id = $sid
                       WHERE shift_id IS NULL
                         AND season_tag <=> " . $this->db->escape($g['season_tag']) . "
                         AND effective_from <=> " . $this->db->escape($from) . "
                         AND effective_to <=> " . $this->db->escape($to) . "
                         AND is_override = " . ($is_temp ? 1 : 0), 'link timings to shift ' . $name);
            $made[] = array('id' => $sid, 'temp' => $is_temp, 'from' => $from, 'to' => $to);
            $this->_log("  - shift \"$name\" (" . ($is_temp ? 'temporary' : 'regular') . ($active ? ', active' : ', inactive') . ')');
        }
        // Tag existing attendance with the shift its date fell under (regular first, temporary on top).
        foreach (array(false, true) as $temp_pass) {
            foreach ($made as $m) {
                if ($m['temp'] !== $temp_pass) {
                    continue;
                }
                $to = !empty($m['to']) ? $this->db->escape($m['to']) : "'9999-12-31'";
                $this->_q("UPDATE staff_attendance sa
                    JOIN staff_roles sr ON sr.staff_id = sa.staff_id
                    JOIN (SELECT DISTINCT role_id FROM staff_attendence_schedules WHERE shift_id = " . (int) $m['id'] . ") r ON r.role_id = sr.role_id
                    SET sa.shift_id = " . (int) $m['id'] . "
                    WHERE sa.date >= " . $this->db->escape($m['from']) . " AND sa.date <= $to" . ($temp_pass ? "" : " AND sa.shift_id IS NULL"),
                    'tag attendance with shifts');
            }
        }
        $n = (int) $this->db->query("SELECT COUNT(*) c FROM staff_attendance WHERE shift_id IS NOT NULL")->row()->c;
        $this->_log("  - $n attendance rows linked to their shift");
    }
}
