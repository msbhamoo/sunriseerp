<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Keeps staff attendance in step with staff leave requests.
 *
 * Approved leave marks each working day of the leave as "Leave" (half-day leave as "Half Day"),
 * with attendance_source = 'leave' so biometric sync does not overwrite it. Sundays, calendar
 * holidays and Holidays & Working Days entries are skipped (they are not leave days).
 *
 * Every row touched remembers what it was before (pre_leave_*), so rejecting, editing or deleting
 * the leave restores it exactly. Days where the person punched in on a full-day leave are not
 * overridden; they are reported back as conflicts instead.
 */
class Staffleaveattendance_model extends CI_Model
{
    const NEW_ROW = '__created_by_leave__';
    private static $schema_checked = false;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('staffAttendaceSetting_model', 'staffattendancemodel', 'staffdayoverride_model'));
        if (!self::$schema_checked) {
            self::$schema_checked = true;
            $this->_ensureSchema();
        }
    }

    private function _ensureSchema()
    {
        // Leave status and columns are added by the versioned database update (backs up first, runs once).
        $this->load->model('staffattendancedbupdate_model');
        $this->staffattendancedbupdate_model->ensure();
    }

    private function typeId($key)
    {
        $id = $this->staffAttendaceSetting_model->getAttendanceTypeIdByKey($key);
        if (!$id && $key === 'lv') { // created in this request, after the key cache was built
            $r = $this->db->query("SELECT id FROM staff_attendance_type WHERE long_lang_name = 'leave' LIMIT 1")->row();
            $id = $r ? (int) $r->id : null;
        }
        return $id;
    }

    private function hasPunch($row)
    {
        return (!empty($row['in_time']) && $row['in_time'] !== '00:00:00') || (!empty($row['out_time']) && $row['out_time'] !== '00:00:00');
    }

    private function roleOf($staff_id)
    {
        $r = $this->db->select('role_id')->where('staff_id', $staff_id)->get('staff_roles')->row();
        return $r ? (int) $r->role_id : null;
    }

    /** Status the punches give for a row (same rules as biometric sync). */
    private function statusFromPunches($row, $role_id)
    {
        $s = $this->staffAttendaceSetting_model;
        if (!empty($row['in_time']) && $row['in_time'] !== '00:00:00') {
            $band = $s->getAttendanceTypeByRole($role_id, $row['in_time'], $row['date'], $row['shift_id']);
            $t = $band ? (int) $band->staff_attendence_type_id : $this->typeId('p');
        } else {
            $t = $this->typeId('p');
        }
        return $s->applyEarlyHalfRule($t, $row['out_time'], $role_id, $row['date'], $row['shift_id']);
    }

    /**
     * Bring attendance in line with one leave request (call after it is saved or its status changes).
     * Returns array('applied' => n, 'reverted' => n, 'conflicts' => [dates with punches on full-day leave]).
     */
    public function syncRequest($request_id)
    {
        $res = array('applied' => 0, 'reverted' => $this->revertRequest($request_id), 'conflicts' => array());
        $req = $this->db->where('id', (int) $request_id)->get('staff_leave_request')->row_array();
        if (!$req || $req['status'] !== 'approved') {
            return $res;
        }

        $leave_id = $this->typeId('lv');
        $half_id  = $this->typeId('f');
        if (!$leave_id) {
            return $res;
        }
        $type_row  = $this->db->select('type')->where('id', $req['leave_type_id'])->get('leave_types')->row();
        $type_name = $type_row ? $type_row->type : 'Leave';
        $role_id   = $this->roleOf($req['staff_id']);
        $single    = $req['leave_from'] === $req['leave_to'];

        for ($t = strtotime($req['leave_from']); $t <= strtotime($req['leave_to']); $t += 86400) {
            $date = date('Y-m-d', $t);
            if ($this->staffdayoverride_model->isOff($req['staff_id'], $role_id, $date, $req['leave_from'], $req['leave_to'])) {
                continue; // Sunday / holiday: not a leave day
            }
            // Half-day leave: the single day, or the last day of a longer leave.
            $is_half = !empty($req['half_day_leave']) && ($single || $date === $req['leave_to']);
            $status  = $is_half && $half_id ? $half_id : $leave_id;
            $note    = ($is_half ? 'Half-day ' : '') . $type_name . ' (approved leave #' . (int) $req['id'] . ')';

            $row = $this->db->where('staff_id', $req['staff_id'])->where('date', $date)->get('staff_attendance')->row_array();
            if ($row) {
                if (!$is_half && $this->hasPunch($row)) {
                    $res['conflicts'][] = $date; // came in on a full-day leave: keep the punches' status
                    continue;
                }
                $this->db->where('id', $row['id'])->update('staff_attendance', array(
                    'pre_leave_type_id'        => $row['staff_attendance_type_id'],
                    'pre_leave_source'         => $row['attendance_source'],
                    'pre_leave_remark'         => $row['remark'],
                    'leave_request_id'         => (int) $req['id'],
                    'staff_attendance_type_id' => $status,
                    'attendance_source'        => 'leave',
                    'remark'                   => IsNullOrEmptyString($row['remark']) ? $note : $row['remark'] . ' | ' . $note,
                ));
            } else {
                $this->db->insert('staff_attendance', array(
                    'staff_id'                 => $req['staff_id'],
                    'date'                     => $date,
                    'staff_attendance_type_id' => $status,
                    'attendance_source'        => 'leave',
                    'remark'                   => $note,
                    'is_active'                => 1,
                    'created_at'               => date('Y-m-d H:i:s'),
                    'shift_id'                 => $this->staffattendancemodel->shiftIdForStaff($req['staff_id'], $date),
                    'leave_request_id'         => (int) $req['id'],
                    'pre_leave_source'         => self::NEW_ROW,
                ));
            }
            $res['applied']++;
        }
        return $res;
    }

    /** Undo everything a leave request did to attendance. Returns the number of rows restored or removed. */
    public function revertRequest($request_id)
    {
        $rows = $this->db->where('leave_request_id', (int) $request_id)->get('staff_attendance')->result_array();
        $n = 0;
        foreach ($rows as $row) {
            $role_id = $this->roleOf($row['staff_id']);
            if ($row['pre_leave_source'] === self::NEW_ROW) {
                if ($this->hasPunch($row)) {
                    // Punches arrived after the leave was approved: keep the day, status from punches.
                    $this->db->where('id', $row['id'])->update('staff_attendance', array(
                        'staff_attendance_type_id' => $this->statusFromPunches($row, $role_id),
                        'attendance_source'        => 'biometric',
                        'remark'                   => '',
                        'leave_request_id'         => null,
                        'pre_leave_source'         => null,
                    ));
                } else {
                    $this->db->where('id', $row['id'])->delete('staff_attendance');
                }
            } else {
                $restore = array(
                    'staff_attendance_type_id' => $row['pre_leave_type_id'],
                    'attendance_source'        => $row['pre_leave_source'],
                    'remark'                   => (string) $row['pre_leave_remark'],
                    'leave_request_id'         => null,
                    'pre_leave_type_id'        => null,
                    'pre_leave_source'         => null,
                    'pre_leave_remark'         => null,
                );
                // Was Absent/blank before but has punches now (half-day leave): status from punches.
                if ($this->hasPunch($row) && in_array((int) $row['pre_leave_type_id'], array(0, (int) $this->typeId('a')), true)) {
                    $restore['staff_attendance_type_id'] = $this->statusFromPunches($row, $role_id);
                    $restore['attendance_source']        = 'biometric';
                }
                $this->db->where('id', $row['id'])->update('staff_attendance', $restore);
            }
            $n++;
        }
        return $n;
    }

    /** Approved leave requests overlapping a date range (for the backfill). */
    public function approvedRequestIds($from, $to)
    {
        $rows = $this->db->select('id')->where('status', 'approved')
            ->where('leave_from <=', $to)->where('leave_to >=', $from)->get('staff_leave_request')->result_array();
        return array_map('intval', array_column($rows, 'id'));
    }
}
