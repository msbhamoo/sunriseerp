<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Audit trail for staff attendance.
 *
 * Database triggers on staff_attendance write a row to staff_attendance_audit_log for every
 * INSERT / UPDATE / DELETE, whichever code path made it (manual form, biometric sync, QR, cron,
 * or a direct SQL edit). Who made the change comes from MySQL session variables set once per
 * request by setContext(); writes made outside the app show up with an empty user.
 *
 * Shift changes (create / edit / activate / delete) are logged from PHP into the same table.
 */
class Staffattendanceaudit_model extends CI_Model
{
    const TABLE = 'staff_attendance_audit_log';

    private static $schema_checked = false;
    private static $context_set = false;

    /** Columns not worth tracking (timestamps and raw device payloads). */
    private $skip_columns = array('id', 'created_at', 'updated_at', 'biometric_device_data');

    /**
     * Make sure the change log is ready. The table itself is created by the versioned database
     * update (Staffattendancedbupdate_model, which backs up first); this only keeps the triggers in
     * step when staff_attendance columns change.
     */
    public function ensureSchema()
    {
        if (self::$schema_checked) {
            return;
        }
        self::$schema_checked = true;
        $this->load->model('staffattendancedbupdate_model');
        $this->staffattendancedbupdate_model->ensure();
        if ($this->db->table_exists(self::TABLE)) {
            $this->_ensureTriggers();
        }
    }

    /** Install / refresh the triggers (used by the database update). Returns true when all three exist. */
    public function installTriggers()
    {
        if (!$this->db->table_exists(self::TABLE)) {
            return false;
        }
        $this->_ensureTriggers();
        return $this->triggersInstalled();
    }

    /**
     * (Re)create the triggers when missing or when staff_attendance gained/lost columns.
     * The trigger names carry a hash of the tracked column list.
     */
    private function _ensureTriggers()
    {
        if (!$this->db->table_exists('staff_attendance')) {
            return;
        }
        $cols = array_values(array_diff($this->db->list_fields('staff_attendance'), $this->skip_columns));
        $hash = substr(md5(implode(',', $cols)), 0, 8);

        $existing = $this->db->query("SELECT TRIGGER_NAME FROM information_schema.TRIGGERS
                                      WHERE TRIGGER_SCHEMA = DATABASE() AND EVENT_OBJECT_TABLE = 'staff_attendance'
                                        AND TRIGGER_NAME LIKE 'trg_sa_audit\\_%'")->result_array();
        $names = array_column($existing, 'TRIGGER_NAME');
        if (in_array('trg_sa_audit_upd_' . $hash, $names, true)
            && in_array('trg_sa_audit_ins_' . $hash, $names, true)
            && in_array('trg_sa_audit_del_' . $hash, $names, true)) {
            return;
        }

        $old_json = array();
        $new_json = array();
        $diff     = array();
        foreach ($cols as $c) {
            $q = "`" . str_replace('`', '', $c) . "`";
            $old_json[] = "'" . $c . "', OLD." . $q;
            $new_json[] = "'" . $c . "', NEW." . $q;
            $diff[]     = "IF(OLD." . $q . " <=> NEW." . $q . ", NULL, '" . $c . "')";
        }
        $old_obj = "JSON_OBJECT(" . implode(', ', $old_json) . ")";
        $new_obj = "JSON_OBJECT(" . implode(', ', $new_json) . ")";
        $ctx     = "@att_audit_user_id, @att_audit_user_name, IFNULL(@att_audit_source, 'database'), @att_audit_ip, @att_audit_url, NOW()";
        $ins_cols = "(attendance_id, staff_id, att_date, action, changed_fields, old_values, new_values, changed_by, changed_by_name, source, ip_address, url, created_at)";

        $statements = array(
            "CREATE TRIGGER `trg_sa_audit_ins_$hash` AFTER INSERT ON `staff_attendance` FOR EACH ROW
             INSERT INTO `" . self::TABLE . "` $ins_cols
             VALUES (NEW.id, NEW.staff_id, NEW.date, 'INSERT', NULL, NULL, $new_obj, $ctx)",
            "CREATE TRIGGER `trg_sa_audit_upd_$hash` AFTER UPDATE ON `staff_attendance` FOR EACH ROW
             BEGIN
                DECLARE ch VARCHAR(500);
                SET ch = CONCAT_WS(',', " . implode(', ', $diff) . ");
                IF ch IS NOT NULL AND ch <> '' THEN
                    INSERT INTO `" . self::TABLE . "` $ins_cols
                    VALUES (NEW.id, NEW.staff_id, NEW.date, 'UPDATE', ch, $old_obj, $new_obj, $ctx);
                END IF;
             END",
            "CREATE TRIGGER `trg_sa_audit_del_$hash` AFTER DELETE ON `staff_attendance` FOR EACH ROW
             INSERT INTO `" . self::TABLE . "` $ins_cols
             VALUES (OLD.id, OLD.staff_id, OLD.date, 'DELETE', NULL, $old_obj, NULL, $ctx)",
        );

        // Trigger creation needs the TRIGGER privilege; never break the page if it is missing.
        $debug = $this->db->db_debug;
        $this->db->db_debug = false;
        foreach ($names as $n) {
            $this->db->query("DROP TRIGGER IF EXISTS `" . str_replace('`', '', $n) . "`");
        }
        foreach ($statements as $sql) {
            if (!$this->db->query($sql)) {
                $err = $this->db->error();
                log_message('error', 'Staff attendance audit trigger could not be created: ' . $err['message']);
            }
        }
        $this->db->db_debug = $debug;
    }

    /** True when all three triggers are installed (shown as a warning on the log page otherwise). */
    public function triggersInstalled()
    {
        $row = $this->db->query("SELECT COUNT(*) AS c FROM information_schema.TRIGGERS
                                 WHERE TRIGGER_SCHEMA = DATABASE() AND EVENT_OBJECT_TABLE = 'staff_attendance'
                                   AND TRIGGER_NAME LIKE 'trg_sa_audit\\_%'")->row();
        return $row && (int) $row->c >= 3;
    }

    /**
     * Set who/where for this DB connection so the triggers can record it.
     * Called once per request (from MY_Model and the attendance models).
     */
    public function setContext()
    {
        if (self::$context_set) {
            return;
        }
        self::$context_set = true;

        $user_id = null;
        $name    = null;
        $source  = 'web';
        $ip      = null;
        $url     = null;

        if (is_cli()) {
            $source = 'cli';
        } else {
            $ip  = $this->input->ip_address();
            $uri = $this->uri->uri_string();
            $url = substr($uri, 0, 250);
            $low = strtolower($uri);
            if (strpos($low, 'cron') !== false) {
                $source = 'cron';
            } elseif (strpos($low, 'biometric') !== false) {
                $source = 'biometric';
            } elseif (strpos($low, 'qr') !== false) {
                $source = 'qr';
            }
            $CI       =& get_instance();
            $session  = isset($CI->session) ? $CI->session : null;
            $admin    = $session ? $session->userdata('admin') : null;
            if (!empty($admin['id'])) {
                $user_id = (int) $admin['id'];
                $name    = isset($admin['username']) ? $admin['username'] : null;
                if ($source === 'web') {
                    $source = 'admin';
                }
            } elseif ($session && $session->userdata('student')) {
                $source = 'portal';
            }
        }

        $this->db->query("SET @att_audit_user_id = ?, @att_audit_user_name = ?, @att_audit_source = ?, @att_audit_ip = ?, @att_audit_url = ?",
            array($user_id, $name, $source, $ip, $url));
    }

    /** Log a shift settings change (not covered by the triggers). */
    public function logShift($action, $shift_id, $old = null, $new = null)
    {
        $this->logEvent($action, 'shift #' . (int) $shift_id, $old, $new);
    }

    /** Log a settings-level change (shift, holiday / working-day override) into the change log. */
    public function logEvent($action, $label, $old = null, $new = null)
    {
        $this->ensureSchema();
        $CI    =& get_instance();
        $admin = isset($CI->session) ? $CI->session->userdata('admin') : null;
        $this->db->insert(self::TABLE, array(
            'attendance_id'   => null,
            'staff_id'        => null,
            'att_date'        => null,
            'action'          => $action,
            'changed_fields'  => $label,
            'old_values'      => $old !== null ? json_encode($old) : null,
            'new_values'      => $new !== null ? json_encode($new) : null,
            'changed_by'      => !empty($admin['id']) ? (int) $admin['id'] : null,
            'changed_by_name' => isset($admin['username']) ? $admin['username'] : null,
            'source'          => 'admin',
            'ip_address'      => $this->input->ip_address(),
            'url'             => substr($this->uri->uri_string(), 0, 250),
            'created_at'      => date('Y-m-d H:i:s'),
        ));
    }

    /**
     * Search the log. $f keys: from, to (Y-m-d, change time), staff_id, action, source, att_date.
     * Returns array('rows' => [...], 'total' => int).
     */
    public function search($f, $limit = 100, $offset = 0)
    {
        $this->ensureSchema();
        $where = array('1 = 1');
        if (!empty($f['from'])) {
            $where[] = "l.created_at >= " . $this->db->escape($f['from'] . ' 00:00:00');
        }
        if (!empty($f['to'])) {
            $where[] = "l.created_at <= " . $this->db->escape($f['to'] . ' 23:59:59');
        }
        if (!empty($f['att_date'])) {
            $where[] = "l.att_date = " . $this->db->escape($f['att_date']);
        }
        if (!empty($f['staff_id'])) {
            $where[] = "l.staff_id = " . (int) $f['staff_id'];
        }
        if (!empty($f['action'])) {
            if ($f['action'] === 'SHIFT') {
                $where[] = "l.action LIKE 'SHIFT%'";
            } elseif ($f['action'] === 'DAY') {
                $where[] = "l.action LIKE 'DAY\\_%'";
            } elseif ($f['action'] === 'POLICY') {
                $where[] = "l.action LIKE 'POLICY\\_%'";
            } else {
                $where[] = "l.action = " . $this->db->escape($f['action']);
            }
        }
        if (!empty($f['source'])) {
            $where[] = "l.source = " . $this->db->escape($f['source']);
        }
        $w = implode(' AND ', $where);

        $total = (int) $this->db->query("SELECT COUNT(*) AS c FROM " . self::TABLE . " l WHERE $w")->row()->c;
        $rows  = $this->db->query("SELECT l.*, s.name AS staff_name, s.surname AS staff_surname, s.employee_id,
                                          cb.name AS by_name, cb.surname AS by_surname
                                   FROM " . self::TABLE . " l
                                   LEFT JOIN staff s ON s.id = l.staff_id
                                   LEFT JOIN staff cb ON cb.id = l.changed_by
                                   WHERE $w
                                   ORDER BY l.id DESC
                                   LIMIT " . (int) $offset . ", " . (int) $limit)->result_array();
        return array('rows' => $rows, 'total' => $total);
    }

    public function getSources()
    {
        $this->ensureSchema();
        $rows = $this->db->query("SELECT DISTINCT source FROM " . self::TABLE . " WHERE source IS NOT NULL ORDER BY source")->result_array();
        return array_column($rows, 'source');
    }
}
