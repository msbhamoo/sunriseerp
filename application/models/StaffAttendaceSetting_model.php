<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Staff shift settings.
 *
 * A shift (staff_shifts) is a named set of timing bands (staff_attendence_schedules rows,
 * linked by shift_id) for one or more roles.
 *
 *  - Regular shift:   start date (empty = always) and optional end date. On any date only one
 *                     active regular shift applies per role; a new one caps the open-ended one before it.
 *  - Temporary shift: bounded by effective_from / effective_to (PTM, exam day, event).
 *                     While active and inside its dates it overlays the regular shift.
 *
 * Every staff_attendance row stores the shift_id it was evaluated against, so changing or
 * switching shifts later never re-interprets past attendance.
 */
class StaffAttendaceSetting_model extends CI_Model
{
    private static $schema_checked = false;
    private $shift_map_cache = array();

    public function __construct()
    {
        parent::__construct();
        if (!self::$schema_checked) {
            self::$schema_checked = true;
            // Schema changes live in the versioned database update, which backs up the tables first
            // and checks that no existing attendance data changed. It runs once.
            $this->load->model(array('staffattendancedbupdate_model', 'staffattendanceaudit_model'));
            $this->staffattendancedbupdate_model->ensure();
            $this->staffattendanceaudit_model->ensureSchema();
            $this->staffattendanceaudit_model->setContext();
        }
    }

    // =================================================================
    //  Shift CRUD
    // =================================================================

    public function getShifts()
    {
        $sql = "SELECT s.*,
                       (SELECT COUNT(DISTINCT b.role_id) FROM staff_attendence_schedules b WHERE b.shift_id = s.id) AS total_roles,
                       (SELECT COUNT(*) FROM staff_attendance a WHERE a.shift_id = s.id) AS usage_count
                FROM staff_shifts s
                ORDER BY s.is_active DESC, s.shift_type ASC, s.effective_from DESC, s.id DESC";
        return $this->db->query($sql)->result_array();
    }

    public function getShift($shift_id)
    {
        return $this->db->where('id', (int) $shift_id)->get('staff_shifts')->row_array();
    }

    public function getShiftRoles($shift_id)
    {
        $rows = $this->db->query("SELECT DISTINCT role_id FROM staff_attendence_schedules WHERE shift_id = " . (int) $shift_id)->result_array();
        return array_map('intval', array_column($rows, 'role_id'));
    }

    /** The regular shift in force today (default editor template). */
    public function getActiveRegularShiftId()
    {
        $today = $this->db->escape(date('Y-m-d'));
        $row = $this->db->query("SELECT id FROM staff_shifts
                                 WHERE shift_type = 'regular' AND is_active = 1
                                   AND IFNULL(effective_from, '0000-01-01') <= $today AND IFNULL(effective_to, '9999-12-31') >= $today
                                 ORDER BY IFNULL(effective_from, '0000-01-01') DESC, id DESC LIMIT 1")->row();
        return $row ? (int) $row->id : null;
    }

    /**
     * Rows for the settings editor: every role, LEFT JOINed with the shift's bands.
     * Same columns the old getRoleAttendanceSetting() returned.
     */
    public function getShiftEditorRows($shift_id = null)
    {
        $join_cond = $shift_id ? " AND sas.shift_id = " . (int) $shift_id : " AND 1 = 0";
        $sql = "SELECT roles.*, sas.role_id, sas.staff_attendence_type_id, sas.id AS `staff_attendence_schedules`,
                       sas.entry_time_from, sas.entry_time_to, sas.total_institute_hour, sas.early_half_time, sas.early_grace_time,
                       sas.shift_id, roles.name AS `role_name`
                FROM `roles`
                LEFT JOIN staff_attendence_schedules sas ON sas.role_id = roles.id $join_cond
                ORDER BY roles.id";
        return $this->db->query($sql)->result();
    }

    private function _rangeLabel($from, $to)
    {
        return ($from ? date('d M Y', strtotime($from)) : 'beginning') . ' to ' . ($to ? date('d M Y', strtotime($to)) : 'open-ended');
    }

    /**
     * Check what activating a shift with these roles/dates would collide with.
     * Empty from = since always, empty to = open-ended.
     *
     * Returns array(
     *   'error'   => string|null,
     *   'replace' => [shift ids to deactivate]            (unused for now, kept for callers),
     *   'cap'     => [shift id => new effective_to]       (earlier regular shifts ended the day before $from)
     * )
     */
    public function checkActivation($roles, $shift_type, $from = null, $to = null, $exclude_id = null)
    {
        $none  = array('error' => null, 'replace' => array(), 'cap' => array());
        $roles = array_values(array_unique(array_map('intval', (array) $roles)));
        if (empty($roles)) {
            return array('error' => 'This shift has no roles configured.') + $none;
        }
        $roles_str = implode(',', $roles);
        $exclude   = $exclude_id ? " AND s.id <> " . (int) $exclude_id : "";
        $from_sql  = $from ? $this->db->escape($from) : "'0000-01-01'";
        $to_sql    = $to ? $this->db->escape($to) : "'9999-12-31'";

        $sql = "SELECT s.id, s.name, s.effective_from, s.effective_to, GROUP_CONCAT(DISTINCT r.name ORDER BY r.name SEPARATOR ', ') AS role_names
                FROM staff_shifts s
                JOIN staff_attendence_schedules b ON b.shift_id = s.id
                JOIN roles r ON r.id = b.role_id
                WHERE s.is_active = 1 AND s.shift_type = " . $this->db->escape($shift_type) . "
                  AND b.role_id IN ($roles_str) $exclude
                  AND IFNULL(s.effective_from, '0000-01-01') <= $to_sql
                  AND IFNULL(s.effective_to, '9999-12-31') >= $from_sql
                GROUP BY s.id, s.name, s.effective_from, s.effective_to";
        $conflicts = $this->db->query($sql)->result_array();
        if (empty($conflicts)) {
            return $none;
        }

        if ($shift_type === 'temporary') {
            $labels = array();
            foreach ($conflicts as $c) {
                $labels[] = $c['name'] . ' (' . $this->_rangeLabel($c['effective_from'], $c['effective_to']) . ', ' . $c['role_names'] . ')';
            }
            return array('error' => 'Another active temporary shift already covers these dates: ' . implode('; ', $labels) . '. Deactivate it or change the dates.') + $none;
        }

        // Regular: an overlapping shift that started earlier is ended the day before this one starts.
        // It must not cover roles this shift leaves out, or those roles would be left without timings.
        // A shift starting on/after this one's start is a real clash the admin must resolve.
        $cap = array();
        foreach ($conflicts as $c) {
            $starts_earlier = $from && (empty($c['effective_from']) || $c['effective_from'] < $from);
            if (!$starts_earlier) {
                return array('error' => 'Dates overlap with active regular shift "' . $c['name'] . '" (' . $this->_rangeLabel($c['effective_from'], $c['effective_to'])
                    . '). Change the Start date to after its start, or edit / deactivate that shift.') + $none;
            }
            if (!empty($to) && (empty($c['effective_to']) || $c['effective_to'] > $to)) {
                return array('error' => '"' . $c['name'] . '" (' . $this->_rangeLabel($c['effective_from'], $c['effective_to'])
                    . ') continues after this shift\'s End date, so ending it here would leave days without timings. Leave End date empty, or use a temporary shift for a short change.') + $none;
            }
            $other_roles = $this->getShiftRoles($c['id']);
            if (count(array_diff($other_roles, $roles)) > 0) {
                return array('error' => 'Active regular shift "' . $c['name'] . '" covers roles this shift does not. Include those roles or edit that shift\'s dates first.') + $none;
            }
            $cap[(int) $c['id']] = date('Y-m-d', strtotime($from . ' -1 day'));
        }
        return array('error' => null, 'replace' => array(), 'cap' => $cap);
    }

    /** Apply the end-date caps returned by checkActivation(). */
    private function _applyCaps($cap)
    {
        foreach ((array) $cap as $id => $to) {
            $this->db->where('id', (int) $id)->update('staff_shifts', array('effective_to' => $to, 'updated_at' => date('Y-m-d H:i:s')));
            $this->db->where('shift_id', (int) $id)->update('staff_attendence_schedules', array('effective_to' => $to));
        }
    }

    /**
     * Create or update a shift and its bands.
     * $meta: name, shift_type, effective_from, effective_to, activate (bool)
     * Returns array('status' => bool, 'message' => string, 'shift_id' => int)
     */
    public function saveShift($shift_id, $meta, $bands)
    {
        $type  = ($meta['shift_type'] === 'temporary') ? 'temporary' : 'regular';
        $from  = !empty($meta['effective_from']) ? $meta['effective_from'] : null;
        $to    = !empty($meta['effective_to']) ? $meta['effective_to'] : null;
        $roles = array_column($bands, 'role_id');

        $existing   = $shift_id ? $this->getShift($shift_id) : null;
        $will_active = !empty($meta['activate']) || (!empty($existing) && !empty($existing['is_active']));

        $cap = array();
        if ($will_active) {
            $check = $this->checkActivation($roles, $type, $from, $to, $shift_id);
            if (!empty($check['error'])) {
                return array('status' => false, 'message' => $check['error']);
            }
            $cap = $check['cap'];
        }

        $now = date('Y-m-d H:i:s');
        $this->db->trans_start();

        $row = array(
            'name'           => $meta['name'],
            'shift_type'     => $type,
            'effective_from' => $from,
            'effective_to'   => $to,
            'is_active'      => $will_active ? 1 : 0,
            'updated_at'     => $now,
        );
        if ($existing) {
            $this->db->where('id', (int) $shift_id)->update('staff_shifts', $row);
        } else {
            $row['created_at'] = $now;
            $this->db->insert('staff_shifts', $row);
            $shift_id = $this->db->insert_id();
        }

        $this->_applyCaps($cap);

        $this->db->where('shift_id', (int) $shift_id)->delete('staff_attendence_schedules');
        foreach ($bands as &$band) {
            $band['shift_id']       = (int) $shift_id;
            // Legacy columns kept in sync for older reports that still read them.
            $band['season_tag']     = $meta['name'];
            $band['is_override']    = $type === 'temporary' ? 1 : 0;
            $band['effective_from'] = $from ? $from : '2000-01-01';
            $band['effective_to']   = $to;
        }
        unset($band);
        if (!empty($bands)) {
            $this->db->insert_batch('staff_attendence_schedules', $bands);
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return array('status' => false, 'message' => 'Could not save the shift. Please try again.');
        }

        $this->shift_map_cache = array();
        $this->staffattendanceaudit_model->logShift($existing ? 'SHIFT_EDIT' : 'SHIFT_CREATE', $shift_id, $existing, array(
            'shift'       => $this->getShift($shift_id),
            'ended'       => $cap,
            'band_count'  => count($bands),
        ));
        return array('status' => true, 'message' => 'Shift saved.' . $this->_capMessage($cap), 'shift_id' => (int) $shift_id);
    }

    private function _capMessage($cap)
    {
        if (empty($cap)) {
            return '';
        }
        $parts = array();
        foreach ($cap as $id => $to) {
            $s = $this->getShift($id);
            $parts[] = '"' . ($s ? $s['name'] : '#' . $id) . '" now ends on ' . date('d M Y', strtotime($to));
        }
        return ' ' . implode('; ', $parts) . '.';
    }

    /** True when a shift's dates include $date (empty from = always, empty to = open-ended). */
    public function shiftCoversDate($shift, $date)
    {
        return (empty($shift['effective_from']) || $shift['effective_from'] <= $date)
            && (empty($shift['effective_to']) || $shift['effective_to'] >= $date);
    }

    /** Activate / deactivate a shift. Returns array('status' => bool, 'message' => string). */
    public function setShiftActive($shift_id, $active)
    {
        $shift = $this->getShift($shift_id);
        if (empty($shift)) {
            return array('status' => false, 'message' => 'Shift not found.');
        }
        $now = date('Y-m-d H:i:s');

        if (!$active) {
            if ($shift['shift_type'] === 'regular' && !empty($shift['is_active']) && $this->shiftCoversDate($shift, date('Y-m-d'))) {
                return array('status' => false, 'message' => 'This regular shift is in force today, so switching it off would leave staff without timings. Create a new regular shift with a Start date instead; this one will end the day before.');
            }
            $this->db->where('id', (int) $shift_id)->update('staff_shifts', array('is_active' => 0, 'updated_at' => $now));
            $this->shift_map_cache = array();
            $this->staffattendanceaudit_model->logShift('SHIFT_DEACTIVATE', $shift_id, $shift, null);
            return array('status' => true, 'message' => 'Shift deactivated.');
        }

        $check = $this->checkActivation($this->getShiftRoles($shift_id), $shift['shift_type'], $shift['effective_from'], $shift['effective_to'], $shift_id);
        if (!empty($check['error'])) {
            return array('status' => false, 'message' => $check['error']);
        }
        $this->db->trans_start();
        $this->_applyCaps($check['cap']);
        $this->db->where('id', (int) $shift_id)->update('staff_shifts', array('is_active' => 1, 'updated_at' => $now));
        $this->db->trans_complete();
        $this->shift_map_cache = array();
        $this->staffattendanceaudit_model->logShift('SHIFT_ACTIVATE', $shift_id, $shift, array('ended' => $check['cap']));
        return array('status' => true, 'message' => 'Shift activated.' . $this->_capMessage($check['cap']));
    }

    /** Delete an inactive shift that no attendance row refers to. */
    public function deleteShift($shift_id)
    {
        $shift = $this->getShift($shift_id);
        if (empty($shift)) {
            return array('status' => false, 'message' => 'Shift not found.');
        }
        if (!empty($shift['is_active'])) {
            return array('status' => false, 'message' => 'Deactivate the shift before deleting it.');
        }
        $used = $this->db->where('shift_id', (int) $shift_id)->count_all_results('staff_attendance');
        if ($used > 0) {
            return array('status' => false, 'message' => "This shift was used for {$used} attendance record(s) and is kept for history. It can stay inactive.");
        }
        $this->db->trans_start();
        $this->db->where('shift_id', (int) $shift_id)->delete('staff_attendence_schedules');
        $this->db->where('id', (int) $shift_id)->delete('staff_shifts');
        $this->db->trans_complete();
        $this->staffattendanceaudit_model->logShift('SHIFT_DELETE', $shift_id, $shift, null);
        return array('status' => true, 'message' => 'Shift deleted.');
    }

    // =================================================================
    //  Shift resolution
    // =================================================================

    /**
     * role_id => shift_id in force on $date: an active temporary shift covering the date wins,
     * otherwise the active regular shift.
     */
    public function resolveShiftMap($date = null)
    {
        $date = !empty($date) ? $date : date('Y-m-d');
        if (isset($this->shift_map_cache[$date])) {
            return $this->shift_map_cache[$date];
        }
        $sql = "SELECT DISTINCT b.role_id, s.id AS shift_id, s.shift_type
                FROM staff_shifts s
                JOIN staff_attendence_schedules b ON b.shift_id = s.id
                WHERE s.is_active = 1
                  AND IFNULL(s.effective_from, '0000-01-01') <= " . $this->db->escape($date) . "
                  AND IFNULL(s.effective_to, '9999-12-31') >= " . $this->db->escape($date) . "
                ORDER BY (s.shift_type = 'temporary') ASC, IFNULL(s.effective_from, '0000-01-01') ASC, s.id ASC";
        $map = array();
        foreach ($this->db->query($sql)->result_array() as $r) {
            // Later rows (temporary, newest) overwrite earlier ones.
            $map[(int) $r['role_id']] = (int) $r['shift_id'];
        }
        $this->shift_map_cache[$date] = $map;
        return $map;
    }

    public function resolveShiftId($role_id, $date = null)
    {
        $map = $this->resolveShiftMap($date);
        return isset($map[(int) $role_id]) ? $map[(int) $role_id] : null;
    }

    /** Use the snapshot shift when it has bands for the role, else the shift in force on $date. */
    private function _effectiveShiftId($role_id, $date, $shift_id)
    {
        if (!empty($shift_id)) {
            $has = $this->db->where('shift_id', (int) $shift_id)->where('role_id', (int) $role_id)->count_all_results('staff_attendence_schedules');
            if ($has > 0) {
                return (int) $shift_id;
            }
        }
        return $this->resolveShiftId($role_id, $date);
    }

    /** All bands of the role's shift (snapshot or in force on $date). */
    public function getRoleShiftBands($role_id, $date = null, $shift_id = null)
    {
        $sid = $this->_effectiveShiftId($role_id, $date, $shift_id);
        if (!$sid) {
            return array();
        }
        return $this->db->where('shift_id', $sid)->where('role_id', (int) $role_id)
            ->order_by('entry_time_from', 'ASC')->get('staff_attendence_schedules')->result();
    }

    // =================================================================
    //  Lookups used by attendance marking and reports
    // =================================================================

    public function getRoleWiseAttendanceSetting($role_id, $date = null)
    {
        $pairs = $this->_pairsCondition($this->resolveShiftMap($date), 'staff_attendence_schedules');
        $sql = "SELECT staff_attendence_schedules.*, roles.name AS `role_name`
                FROM `staff_attendence_schedules`
                INNER JOIN roles ON roles.id = staff_attendence_schedules.role_id
                WHERE $pairs";
        if (!($role_id == null || $role_id == 'select')) {
            $sql .= " AND roles.name = " . $this->db->escape($role_id);
        }
        $sql .= " ORDER BY roles.id";
        return $this->db->query($sql)->result_array();
    }

    /**
     * Band that $time falls into for the role. Pass $shift_id (the attendance row's snapshot)
     * to evaluate against the shift the day was recorded under. The returned row carries shift_id.
     */
    public function getAttendanceTypeByRole($role_id, $time, $date = null, $shift_id = null)
    {
        $date = !empty($date) ? $date : date('Y-m-d');
        $sid  = $this->_effectiveShiftId($role_id, $date, $shift_id);
        if (!$sid) {
            return false;
        }
        // Latest band that has started by $time, so a punch in a gap between bands (e.g. 07:30:30
        // between "to 07:30:00" and "from 07:31:00") lands in the earlier band instead of matching
        // nothing. Times after the shift's last band end still match nothing.
        $t   = $this->db->escape($time);
        $sql = "SELECT * FROM `staff_attendence_schedules`
                WHERE shift_id = " . (int) $sid . " AND role_id = " . $this->db->escape($role_id) . "
                  AND entry_time_from <= $t
                  AND $t <= (SELECT MAX(x.entry_time_to) FROM staff_attendence_schedules x
                             WHERE x.shift_id = " . (int) $sid . " AND x.role_id = " . $this->db->escape($role_id) . ")
                ORDER BY entry_time_from DESC LIMIT 1";
        $q = $this->db->query($sql);
        return $q->num_rows() == 0 ? false : $q->row();
    }

    // =================================================================
    //  Late-coming policy (e.g. every 3 lates in a month = half a day deducted)
    // =================================================================

    public function getLatePolicy()
    {
        $p = $this->db->table_exists('staff_attendance_policy') ? $this->db->where('id', 1)->get('staff_attendance_policy')->row_array() : null;
        return $p ?: array('late_enabled' => 0, 'late_free' => 0, 'late_every' => 3, 'late_penalty' => 0.5);
    }

    /** Returns array(ok, message). */
    public function saveLatePolicy($enabled, $free, $every, $penalty, $user_id)
    {
        $free    = (int) $free;
        $every   = (int) $every;
        $penalty = (float) $penalty;
        if ($free < 0 || $free > 31) {
            return array(false, 'Free lates must be between 0 and 31.');
        }
        if ($every < 1 || $every > 31) {
            return array(false, '"Every N lates" must be between 1 and 31.');
        }
        if (!in_array($penalty, array(0.5, 1.0), true)) {
            return array(false, 'Penalty must be half a day or a full day.');
        }
        $old = $this->getLatePolicy();
        $new = array('late_enabled' => $enabled ? 1 : 0, 'late_free' => $free, 'late_every' => $every, 'late_penalty' => $penalty,
                     'updated_by' => $user_id, 'updated_at' => date('Y-m-d H:i:s'));
        $this->db->where('id', 1)->update('staff_attendance_policy', $new);
        $this->load->model('staffattendanceaudit_model');
        $this->staffattendanceaudit_model->logEvent('POLICY_LATE_EDIT', 'late-coming policy', $old, $new);
        return array(true, 'Late-coming policy saved.');
    }

    /**
     * Days deducted for $lates late arrivals in a month under the policy.
     * After the free lates, every block of N lates costs the penalty (0.5 or 1 day); a part block costs nothing.
     */
    public function lateDeduction($lates, $policy = null)
    {
        $p = $policy ?: $this->getLatePolicy();
        if (empty($p['late_enabled']) || (int) $p['late_every'] < 1) {
            return 0;
        }
        $counted = max(0, (int) $lates - (int) $p['late_free']);
        return floor($counted / (int) $p['late_every']) * (float) $p['late_penalty'];
    }

    /** One-line description of the policy for screens and reports. */
    public function latePolicyText($policy = null)
    {
        $p = $policy ?: $this->getLatePolicy();
        if (empty($p['late_enabled'])) {
            return 'Late-coming policy is off.';
        }
        return ((int) $p['late_free'] ? (int) $p['late_free'] . ' late arrival(s) per month allowed; after that, ' : '')
            . 'every ' . (int) $p['late_every'] . ' late arrival(s) = ' . ((float) $p['late_penalty'] == 1.0 ? '1 full day' : 'half a day') . ' deducted.';
    }

    /** staff_attendance_type id for a key ('p', 'l', 'f', 'sh', ...); key_value holds HTML, so tags are stripped. */
    public function getAttendanceTypeIdByKey($key)
    {
        static $map = null;
        if ($map === null) {
            $map = array();
            foreach ($this->db->select('id, key_value')->get('staff_attendance_type')->result() as $t) {
                $map[strtolower(trim(strip_tags($t->key_value)))] = (int) $t->id;
            }
        }
        $key = strtolower($key);
        return isset($map[$key]) ? $map[$key] : null;
    }

    /**
     * Early-exit rule: a Present / Late day whose out_time is before the shift's "Early End Half"
     * time becomes Half Day. Any other status (and days without an out_time) is returned unchanged.
     */
    public function applyEarlyHalfRule($type_id, $out_time, $role_id, $date = null, $shift_id = null)
    {
        if (empty($type_id) || empty($out_time) || $out_time === '00:00:00' || empty($role_id)) {
            return $type_id;
        }
        $present = $this->getAttendanceTypeIdByKey('p');
        $late    = $this->getAttendanceTypeIdByKey('l');
        $half    = $this->getAttendanceTypeIdByKey('f');
        if (!$half || !in_array((int) $type_id, array_filter(array($present, $late)), true)) {
            return $type_id;
        }
        $early_half = null;
        $day_end    = null;
        foreach ($this->getRoleShiftBands($role_id, $date, $shift_id) as $b) {
            if (!$early_half && !empty($b->early_half_time) && $b->early_half_time !== '00:00:00') {
                $early_half = $b->early_half_time;
            }
            if (!empty($b->entry_time_to) && ($day_end === null || $b->entry_time_to > $day_end)) {
                $day_end = $b->entry_time_to;
            }
        }
        // An Early End Half time at/after the day end would make every early exit a half day;
        // that is a misconfiguration (it belongs in "Early End Grace"), so the rule is skipped.
        if ($early_half && $day_end && $early_half >= $day_end) {
            return $type_id;
        }
        if ($early_half && $out_time < $early_half) {
            return $half;
        }
        return $type_id;
    }

    /**
     * Status for a day from its punch times, with the same rules as biometric sync:
     * shift band for the in-time (gaps fall into the earlier band, before the first band = first band,
     * after the last = last band), then the Early End Half rule. Only an out-time, or no shift = Present.
     */
    public function statusForTimes($role_id, $date, $shift_id, $in_time, $out_time)
    {
        $present = $this->getAttendanceTypeIdByKey('p');
        $t = $present;
        if (!empty($in_time) && $in_time !== '00:00:00' && $role_id) {
            $band = $this->getAttendanceTypeByRole($role_id, $in_time, $date, $shift_id);
            if ($band) {
                $t = (int) $band->staff_attendence_type_id;
            } else {
                $bands = $this->getRoleShiftBands($role_id, $date, $shift_id);
                if (!empty($bands)) {
                    $first = reset($bands);
                    $last  = end($bands);
                    $t = (int) ($in_time < $first->entry_time_from ? $first->staff_attendence_type_id : $last->staff_attendence_type_id);
                }
            }
        }
        return $this->applyEarlyHalfRule($t, $out_time, $role_id, $date, $shift_id);
    }

    public function getAllRoleRequiredHours($date = null)
    {
        $map = array();
        foreach ($this->getAllRoleSchedulesMap($date) as $role_id => $m) {
            if (!empty($m['required_hours'])) {
                $map[$role_id] = $m['required_hours'];
            }
        }
        return $map;
    }

    /** role_id => schedule summary for the shift in force on $date. */
    public function getAllRoleSchedulesMap($date = null)
    {
        $pairs = $this->_pairsCondition($this->resolveShiftMap($date), 'sas');
        $rows  = $this->_fetchBandsWithType($pairs);
        $by_shift = $this->_buildScheduleMaps($rows);
        $out = array();
        foreach ($by_shift as $roles) {
            foreach ($roles as $role_id => $m) {
                $out[$role_id] = $m;
            }
        }
        return $out;
    }

    /** shift_id => role_id => schedule summary, for reports that read each row's snapshot shift. */
    public function getSchedulesMapByShifts($shift_ids)
    {
        $shift_ids = array_filter(array_map('intval', (array) $shift_ids));
        if (empty($shift_ids)) {
            return array();
        }
        $rows = $this->_fetchBandsWithType("sas.shift_id IN (" . implode(',', array_unique($shift_ids)) . ")");
        return $this->_buildScheduleMaps($rows);
    }

    private function _pairsCondition($role_shift_map, $alias)
    {
        if (empty($role_shift_map)) {
            return "1 = 0";
        }
        $parts = array();
        foreach ($role_shift_map as $role_id => $shift_id) {
            $parts[] = "($alias.role_id = " . (int) $role_id . " AND $alias.shift_id = " . (int) $shift_id . ")";
        }
        return "(" . implode(' OR ', $parts) . ")";
    }

    private function _fetchBandsWithType($where)
    {
        $sql = "SELECT sas.shift_id, sas.role_id, sas.staff_attendence_type_id, sas.entry_time_from, sas.entry_time_to,
                       sas.total_institute_hour, sas.early_half_time, sas.early_grace_time, sat.key_value, sat.type
                FROM staff_attendence_schedules sas
                JOIN staff_attendance_type sat ON sat.id = sas.staff_attendence_type_id
                WHERE $where
                ORDER BY sas.entry_time_from ASC";
        return $this->db->query($sql)->result_array();
    }

    private function _buildScheduleMaps($rows)
    {
        $map = array();
        foreach ($rows as $r) {
            $sid    = (int) $r['shift_id'];
            $roleId = (int) $r['role_id'];
            $k      = strtolower(strip_tags($r['key_value']));
            if (!isset($map[$sid][$roleId])) {
                $map[$sid][$roleId] = array(
                    'shift_id'       => $sid,
                    'present_up_to'  => null,
                    'late_up_to'     => null,
                    'halfday_up_to'  => null,
                    'early_half'     => null,
                    'early_grace'    => null,
                    'dayend_time'    => null,
                    'total_hours'    => $r['total_institute_hour'],
                    'required_hours' => null,
                );
            }
            $m = &$map[$sid][$roleId];
            if ($k === 'p') {
                $m['present_up_to'] = $r['entry_time_to'];
                if (!empty($r['total_institute_hour'])) {
                    $m['required_hours'] = $r['total_institute_hour'];
                }
            } elseif ($k === 'l') {
                $m['late_up_to'] = $r['entry_time_to'];
            } elseif ($k === 'f') {
                $m['halfday_up_to'] = $r['entry_time_to'];
            } elseif ($k === 'sh') {
                $m['dayend_time'] = $r['entry_time_to'];
            }
            if (!empty($r['early_half_time'])) {
                $m['early_half'] = $r['early_half_time'];
            }
            if (!empty($r['early_grace_time'])) {
                $m['early_grace'] = $r['early_grace_time'];
            }
            if (!empty($r['total_institute_hour'])) {
                $m['total_hours'] = $r['total_institute_hour'];
                if (empty($m['required_hours'])) {
                    $m['required_hours'] = $r['total_institute_hour'];
                }
            }
            unset($m);
        }
        return $map;
    }
}
