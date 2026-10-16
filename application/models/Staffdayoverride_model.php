<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Per-date working-day / holiday overrides for staff attendance.
 *
 * Default off days are Sundays + annual-calendar holidays (Staffattendancemodel::getOffDays).
 * An override turns a date range into a Working day or a Holiday for:
 *   - all staff, or
 *   - selected roles, or
 *   - selected staff members.
 * When several overrides cover the same staff and date, the most specific wins
 * (staff > roles > all); among equals, the newest wins.
 */
class Staffdayoverride_model extends CI_Model
{
    const TABLE = 'staff_day_overrides';

    private static $schema_checked = false;
    private $range_cache = array(); // "start|end" => array(base off days, overrides)

    public function __construct()
    {
        parent::__construct();
        if (!self::$schema_checked) {
            self::$schema_checked = true;
            // Table created by the versioned database update (backs up first, runs once).
            $this->load->model('staffattendancedbupdate_model');
            $this->staffattendancedbupdate_model->ensure();
        }
        $this->load->model('staffattendancemodel');
    }

    private static $level = array('all' => 1, 'roles' => 2, 'staff' => 3);

    public function getOverrides($start, $end)
    {
        $rows = $this->db->query("SELECT * FROM " . self::TABLE . "
                                  WHERE from_date <= " . $this->db->escape($end) . " AND to_date >= " . $this->db->escape($start) . "
                                  ORDER BY id ASC")->result_array();
        foreach ($rows as &$r) {
            $r['targets'] = array_filter(array_map('intval', explode(',', (string) $r['target_ids'])));
        }
        unset($r);
        usort($rows, function ($a, $b) {
            $d = self::$level[$a['applies_to']] - self::$level[$b['applies_to']];
            return $d !== 0 ? $d : $a['id'] - $b['id'];
        });
        return $rows;
    }

    private function _range($start, $end)
    {
        $k = $start . '|' . $end;
        if (!isset($this->range_cache[$k])) {
            $this->range_cache[$k] = array(
                $this->staffattendancemodel->getOffDays($start, $end),
                $this->getOverrides($start, $end),
            );
        }
        return $this->range_cache[$k];
    }

    /** Is $date an off day for this staff member? Returns array(bool off, override row|null). */
    public function dayStatus($staff_id, $role_id, $date, $start = null, $end = null)
    {
        list($base, $overrides) = $this->_range($start ?: $date, $end ?: $date);
        $off = isset($base[$date]);
        $hit = null;
        foreach ($overrides as $o) { // least specific first, so the most specific applies last
            if ($date < $o['from_date'] || $date > $o['to_date']) {
                continue;
            }
            if ($o['applies_to'] === 'roles' && !in_array((int) $role_id, $o['targets'], true)) {
                continue;
            }
            if ($o['applies_to'] === 'staff' && !in_array((int) $staff_id, $o['targets'], true)) {
                continue;
            }
            $off = ($o['day_type'] === 'holiday');
            $hit = $o;
        }
        return array($off, $hit);
    }

    public function isOff($staff_id, $role_id, $date, $start = null, $end = null)
    {
        $s = $this->dayStatus($staff_id, $role_id, $date, $start, $end);
        return $s[0];
    }

    /** staff_id => [date => true] for every off day of each staff member in the range. $staff: [staff_id => role_id]. */
    public function getOffDayMap($start, $end, $staff)
    {
        $map = array();
        for ($t = strtotime($start); $t <= strtotime($end); $t += 86400) {
            $d = date('Y-m-d', $t);
            foreach ($staff as $sid => $rid) {
                if ($this->isOff($sid, $rid, $d, $start, $end)) {
                    $map[$sid][$d] = true;
                }
            }
        }
        return $map;
    }

    /** Overrides covering a single date, with a readable "applies to" label. */
    public function describeDate($date)
    {
        $out = array();
        foreach ($this->getOverrides($date, $date) as $o) {
            $o['label'] = $this->targetLabel($o);
            $out[] = $o;
        }
        return $out;
    }

    public function targetLabel($o)
    {
        if ($o['applies_to'] === 'all') {
            return 'All staff';
        }
        if (empty($o['targets'])) {
            return 'Nobody';
        }
        $ids = implode(',', array_map('intval', $o['targets']));
        if ($o['applies_to'] === 'roles') {
            $names = array_column($this->db->query("SELECT name FROM roles WHERE id IN ($ids) ORDER BY name")->result_array(), 'name');
            return 'Roles: ' . implode(', ', $names);
        }
        $names = array();
        foreach ($this->db->query("SELECT name, surname, employee_id FROM staff WHERE id IN ($ids) ORDER BY name")->result_array() as $s) {
            $names[] = trim($s['name'] . ' ' . $s['surname']);
        }
        return 'Staff: ' . (count($names) > 4 ? implode(', ', array_slice($names, 0, 4)) . ' +' . (count($names) - 4) . ' more' : implode(', ', $names));
    }

    public function listRecent($from)
    {
        $rows = $this->db->query("SELECT o.*, s.name AS by_name, s.surname AS by_surname FROM " . self::TABLE . " o
                                  LEFT JOIN staff s ON s.id = o.created_by
                                  WHERE o.to_date >= " . $this->db->escape($from) . " ORDER BY o.from_date DESC, o.id DESC")->result_array();
        foreach ($rows as &$r) {
            $r['targets'] = array_filter(array_map('intval', explode(',', (string) $r['target_ids'])));
            $r['label']   = $this->targetLabel($r);
        }
        unset($r);
        return $rows;
    }

    /**
     * Save an override, then fix existing no-punch rows it affects so the records match:
     * Absent -> Holiday on new holidays, Holiday -> Absent on new working days.
     * Rows set by hand (attendance_source = 'manual') are left alone.
     * Returns array(id, updated row count).
     */
    public function add($data)
    {
        $this->range_cache = array();
        $this->db->insert(self::TABLE, $data);
        $id = $this->db->insert_id();
        return array($id, $this->_reconcile($data['from_date'], $data['to_date']));
    }

    public function delete($id)
    {
        $row = $this->db->where('id', (int) $id)->get(self::TABLE)->row_array();
        if (!$row) {
            return array(false, 0);
        }
        $this->db->where('id', (int) $id)->delete(self::TABLE);
        $this->range_cache = array();
        return array($row, $this->_reconcile($row['from_date'], $row['to_date']));
    }

    private function _reconcile($from, $to)
    {
        $this->load->model('staffAttendaceSetting_model');
        $absent  = $this->staffAttendaceSetting_model->getAttendanceTypeIdByKey('a');
        $holiday = $this->staffAttendaceSetting_model->getAttendanceTypeIdByKey('h');
        if (!$absent || !$holiday) {
            return 0;
        }
        $rows = $this->db->query("SELECT a.id, a.staff_id, a.date, a.staff_attendance_type_id t, sr.role_id
                                  FROM staff_attendance a LEFT JOIN staff_roles sr ON sr.staff_id = a.staff_id
                                  WHERE a.date BETWEEN " . $this->db->escape($from) . " AND " . $this->db->escape($to) . "
                                    AND a.staff_attendance_type_id IN (" . (int) $absent . ", " . (int) $holiday . ")
                                    AND (a.in_time IS NULL OR a.in_time = '00:00:00') AND (a.out_time IS NULL OR a.out_time = '00:00:00')
                                    AND (a.attendance_source IS NULL OR a.attendance_source <> 'manual')")->result_array();
        $n = 0;
        foreach ($rows as $r) {
            $want = $this->isOff($r['staff_id'], $r['role_id'], $r['date'], $from, $to) ? $holiday : $absent;
            if ((int) $r['t'] !== (int) $want) {
                $this->db->where('id', $r['id'])->update('staff_attendance', array('staff_attendance_type_id' => $want));
                $n++;
            }
        }
        return $n;
    }
}
