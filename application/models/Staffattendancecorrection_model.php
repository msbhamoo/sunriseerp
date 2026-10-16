<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Attendance correction requests: a staff member asks for a day to be fixed (missed punch,
 * wrong time, on official duty) and an admin approves or rejects it.
 *
 * Approving writes the corrected times / status to staff_attendance with attendance_source = 'manual'
 * (so biometric sync will not undo it). The status is worked out from the corrected times with the
 * shift rules, except On Duty which is set as On Duty. The row's previous values are kept on the
 * request so the change is traceable; the attendance audit triggers also log it.
 */
class Staffattendancecorrection_model extends CI_Model
{
    const TABLE = 'staff_attendance_corrections';
    /** Earliest date a staff member may ask to correct: the 1st of the current month. */
    public static function minDate()
    {
        return date('Y-m-01');
    }

    public static $types = array(
        'missed_in'   => 'Missed check-in',
        'missed_out'  => 'Missed check-out',
        'missed_both' => 'Missed both punches',
        'wrong_time'  => 'Wrong time recorded',
        'on_duty'     => 'On official duty (outside school)',
    );

    private static $schema_checked = false;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('staffAttendaceSetting_model', 'staffattendancemodel'));
        if (!self::$schema_checked) {
            self::$schema_checked = true;
            // Table created by the versioned database update (backs up first, runs once).
            $this->load->model('staffattendancedbupdate_model');
            $this->staffattendancedbupdate_model->ensure();
        }
    }

    private function _time($v)
    {
        $v = trim((string) $v);
        if ($v === '') {
            return null;
        }
        $ts = strtotime($v);
        return $ts ? date('H:i:s', $ts) : false;
    }

    /** Staff submits a request. Returns array(ok bool, message). */
    public function create($staff_id, $date, $type, $in, $out, $reason)
    {
        if (!isset(self::$types[$type])) {
            return array(false, 'Please choose what needs correcting.');
        }
        if (!$date || $date > date('Y-m-d')) {
            return array(false, 'You can only request corrections for today or earlier.');
        }
        if ($date < self::minDate()) {
            return array(false, 'Corrections can be requested for the current month only (from ' . date('d M Y', strtotime(self::minDate())) . '). Please contact the office for earlier dates.');
        }
        $in  = $this->_time($in);
        $out = $this->_time($out);
        if ($in === false || $out === false) {
            return array(false, 'Please enter valid times.');
        }
        $need_in  = in_array($type, array('missed_in', 'missed_both'), true);
        $need_out = in_array($type, array('missed_out', 'missed_both'), true);
        if (($need_in && !$in) || ($need_out && !$out) || ($type === 'wrong_time' && !$in && !$out)) {
            return array(false, 'Please enter the correct ' . ($need_in && $need_out ? 'in and out times' : ($need_in ? 'in time' : ($need_out ? 'out time' : 'time'))) . '.');
        }
        if ($in && $out && $out <= $in) {
            return array(false, 'Out time must be after in time.');
        }
        $reason = trim((string) $reason);
        if (mb_strlen($reason) < 5) {
            return array(false, 'Please give a short reason.');
        }
        $pending = $this->db->where('staff_id', (int) $staff_id)->where('date', $date)->where('status', 'pending')->count_all_results(self::TABLE);
        if ($pending) {
            return array(false, 'You already have a pending request for this date.');
        }
        $this->db->insert(self::TABLE, array(
            'staff_id'      => (int) $staff_id,
            'date'          => $date,
            'request_type'  => $type,
            'requested_in'  => $in,
            'requested_out' => $out,
            'reason'        => mb_substr($reason, 0, 500),
            'status'        => 'pending',
            'created_at'    => date('Y-m-d H:i:s'),
        ));
        return array(true, 'Request sent. You will see the result here once the office reviews it.');
    }

    /** Staff withdraws their own pending request. */
    public function cancel($id, $staff_id)
    {
        $this->db->where('id', (int) $id)->where('staff_id', (int) $staff_id)->where('status', 'pending')
            ->update(self::TABLE, array('status' => 'cancelled', 'reviewed_at' => date('Y-m-d H:i:s')));
        return $this->db->affected_rows() > 0;
    }

    public function myRequests($staff_id, $limit = 20)
    {
        return $this->db->where('staff_id', (int) $staff_id)->order_by('id', 'DESC')->limit($limit)->get(self::TABLE)->result_array();
    }

    public function pendingCount()
    {
        return $this->db->where('status', 'pending')->count_all_results(self::TABLE);
    }

    /** Requests for the admin page with the staff member and the current attendance of that day. */
    public function listForReview($status = 'pending', $limit = 200)
    {
        $where = $status && $status !== 'all' ? " WHERE c.status = " . $this->db->escape($status) : '';
        return $this->db->query("SELECT c.*, s.name, s.surname, s.employee_id, r.name AS role_name,
                    a.in_time AS cur_in, a.out_time AS cur_out, t.type AS cur_status,
                    rv.name AS rv_name, rv.surname AS rv_surname
                FROM " . self::TABLE . " c
                JOIN staff s ON s.id = c.staff_id
                LEFT JOIN staff_roles sr ON sr.staff_id = c.staff_id
                LEFT JOIN roles r ON r.id = sr.role_id
                LEFT JOIN staff_attendance a ON a.staff_id = c.staff_id AND a.date = c.date
                LEFT JOIN staff_attendance_type t ON t.id = a.staff_attendance_type_id
                LEFT JOIN staff rv ON rv.id = c.reviewed_by
                $where
                ORDER BY (c.status = 'pending') DESC, c.date DESC, c.id DESC
                LIMIT " . (int) $limit)->result_array();
    }

    public function reject($id, $reviewer_id, $remark)
    {
        $this->db->where('id', (int) $id)->where('status', 'pending')->update(self::TABLE, array(
            'status'       => 'rejected',
            'admin_remark' => mb_substr(trim((string) $remark), 0, 500),
            'reviewed_by'  => $reviewer_id,
            'reviewed_at'  => date('Y-m-d H:i:s'),
        ));
        return $this->db->affected_rows() > 0 ? array(true, 'Request rejected.') : array(false, 'This request was already reviewed.');
    }

    /**
     * Approve and apply. The admin may adjust the times ($in / $out, blank = keep what the day has).
     * Returns array(ok bool, message).
     */
    public function approve($id, $reviewer_id, $in, $out, $remark)
    {
        $req = $this->db->where('id', (int) $id)->get(self::TABLE)->row_array();
        if (!$req || $req['status'] !== 'pending') {
            return array(false, 'This request was already reviewed.');
        }
        $in  = $this->_time($in);
        $out = $this->_time($out);
        if ($in === false || $out === false) {
            return array(false, 'Please enter valid times.');
        }

        $row  = $this->db->where('staff_id', $req['staff_id'])->where('date', $req['date'])->get('staff_attendance')->row_array();
        $role = $this->db->select('role_id')->where('staff_id', $req['staff_id'])->get('staff_roles')->row();
        $role_id = $role ? (int) $role->role_id : null;

        // Admin's times win; blank = the time the staff member asked for; otherwise what the day already has.
        $new_in  = $in ?: ($req['requested_in'] ?: ($row && !empty($row['in_time']) && $row['in_time'] !== '00:00:00' ? $row['in_time'] : null));
        $new_out = $out ?: ($req['requested_out'] ?: ($row && !empty($row['out_time']) && $row['out_time'] !== '00:00:00' ? $row['out_time'] : null));
        if ($new_in && $new_out && $new_out <= $new_in) {
            return array(false, 'Out time must be after in time.');
        }
        $shift_id = ($row && !empty($row['shift_id'])) ? $row['shift_id'] : $this->staffattendancemodel->shiftIdForStaff($req['staff_id'], $req['date']);

        if ($req['request_type'] === 'on_duty') {
            $status = $this->staffAttendaceSetting_model->getAttendanceTypeIdByKey('on_duty');
        } else {
            $status = $this->staffAttendaceSetting_model->statusForTimes($role_id, $req['date'], $shift_id, $new_in, $new_out);
        }
        if (!$status) {
            return array(false, 'Could not work out the status for this day. Check the shift settings.');
        }

        $note = 'Correction #' . (int) $req['id'] . ': ' . self::$types[$req['request_type']];
        $this->db->trans_start();
        if ($row) {
            $this->db->where('id', $row['id'])->update('staff_attendance', array(
                'in_time'                  => $new_in,
                'out_time'                 => $new_out,
                'staff_attendance_type_id' => $status,
                'attendance_source'        => 'manual',
                'remark'                   => IsNullOrEmptyString($row['remark']) ? $note : $row['remark'] . ' | ' . $note,
                'updated_at'               => date('Y-m-d H:i:s'),
            ));
        } else {
            $this->db->insert('staff_attendance', array(
                'staff_id'                 => $req['staff_id'],
                'date'                     => $req['date'],
                'in_time'                  => $new_in,
                'out_time'                 => $new_out,
                'staff_attendance_type_id' => $status,
                'attendance_source'        => 'manual',
                'shift_id'                 => $shift_id,
                'remark'                   => $note,
                'is_active'                => 1,
                'created_at'               => date('Y-m-d H:i:s'),
            ));
        }
        $this->db->where('id', $req['id'])->update(self::TABLE, array(
            'status'          => 'approved',
            'approved_in'     => $new_in,
            'approved_out'    => $new_out,
            'admin_remark'    => mb_substr(trim((string) $remark), 0, 500),
            'previous_values' => $row ? json_encode(array('in' => $row['in_time'], 'out' => $row['out_time'], 'type' => $row['staff_attendance_type_id'], 'source' => $row['attendance_source'])) : null,
            'reviewed_by'     => $reviewer_id,
            'reviewed_at'     => date('Y-m-d H:i:s'),
        ));
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            return array(false, 'Could not save. Please try again.');
        }
        $tname = $this->db->select('type')->where('id', $status)->get('staff_attendance_type')->row();
        return array(true, 'Approved. Attendance for ' . date('d M Y', strtotime($req['date'])) . ' is now ' . ($tname ? $tname->type : 'updated') . '.');
    }
}
