<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Substitution_model extends MY_Model
{
    protected $current_session;

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function get_approved_leave($staff_id, $date)
    {
        $this->db->select('staff_leave_request.*, leave_types.type as leave_type');
        $this->db->from('staff_leave_request');
        $this->db->join('leave_types', 'leave_types.id = staff_leave_request.leave_type_id', 'left');
        $this->db->where('staff_id', $staff_id);
        $this->db->where_in('staff_leave_request.status', ['approve', 'approved', 'pending']);
        $this->db->where("date(leave_from) <= ", $date);
        $this->db->where("date(leave_to) >= ", $date);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_absent_staff_by_date($date)
    {
        $session_id = $this->current_session;

        // 1. Staff with leave requests (approved or pending) covering $date
        $sql_leave = "SELECT staff.id, staff.name, staff.surname, staff.employee_id, 1 as is_leave, staff_leave_request.status as leave_status, staff_leave_request.leave_type_id, leave_types.type as leave_type
                      FROM staff_leave_request
                      INNER JOIN staff ON staff.id = staff_leave_request.staff_id
                      LEFT JOIN leave_types ON leave_types.id = staff_leave_request.leave_type_id
                      WHERE staff_leave_request.status IN ('approve', 'approved', 'pending')
                      AND DATE(staff_leave_request.leave_from) <= " . $this->db->escape($date) . "
                      AND DATE(staff_leave_request.leave_to) >= " . $this->db->escape($date);
        $leave_staff = $this->db->query($sql_leave)->result_array();

        $absent_ids = [];
        foreach ($leave_staff as $ls) {
            $absent_ids[] = $ls['id'];
        }

        // 2. Staff marked absent / on leave in staff_attendance table for $date
        $sql_att = "SELECT staff.id, staff.name, staff.surname, staff.employee_id, 0 as is_leave, staff_attendance.staff_attendance_type_id, staff_attendance_type.type as leave_type
                    FROM staff_attendance
                    INNER JOIN staff ON staff.id = staff_attendance.staff_id
                    LEFT JOIN staff_attendance_type ON staff_attendance_type.id = staff_attendance.staff_attendance_type_id
                    WHERE staff_attendance.date = " . $this->db->escape($date) . "
                    AND staff_attendance.staff_attendance_type_id IN (3, 4, 5)"; // 3=Absent, 4=On Leave, 5=Half Day
        $att_staff = $this->db->query($sql_att)->result_array();

        foreach ($att_staff as $as) {
            if (!in_array($as['id'], $absent_ids)) {
                $leave_staff[] = $as;
                $absent_ids[] = $as['id'];
            }
        }

        // 3. Get remaining active staff
        $this->db->select('id, name, surname, employee_id');
        $this->db->from('staff');
        $this->db->where('is_active', 1);
        if (!empty($absent_ids)) {
            $this->db->where_not_in('id', $absent_ids);
        }
        $other_staff = $this->db->get()->result_array();

        return [
            'absent_staff' => $leave_staff,
            'other_staff'  => $other_staff
        ];
    }

    public function insert_substitutions($data)
    {
        $this->db->insert_batch('staff_substitutions', $data);
    }

    public function check_conflict($substitute_staff_id, $day, $time_from, $time_to, $date)
    {
        // Convert 12-hour time format (e.g. "08:45 AM" or "12:00 PM") to 24-hour H:i:s for accurate MySQL time string comparison
        $tf_24 = date('H:i:s', strtotime($time_from));
        $tt_24 = date('H:i:s', strtotime($time_to));

        // NOTE: compare against the proper 24-hour start_time/end_time TIME
        // columns, NOT TIME(time_from)/TIME(time_to). time_from/time_to are
        // 12-hour VARCHARs ("01:00 PM") and MySQL's TIME() silently drops the
        // AM/PM, turning afternoon periods into early-morning ones (and periods
        // crossing noon into reversed intervals), which produced false
        // conflicts for teachers who were actually free.

        // Check standard timetable for conflict
        $sql = "SELECT subject_timetable.*, classes.class, sections.section, subjects.name as subject_name
                FROM subject_timetable
                INNER JOIN classes ON classes.id = subject_timetable.class_id
                INNER JOIN sections ON sections.id = subject_timetable.section_id
                LEFT JOIN subject_group_subjects ON subject_group_subjects.id = subject_timetable.subject_group_subject_id
                LEFT JOIN subjects ON subjects.id = subject_group_subjects.subject_id
                WHERE subject_timetable.staff_id = " . $this->db->escape($substitute_staff_id) . "
                AND subject_timetable.day = " . $this->db->escape($day) . "
                AND subject_timetable.session_id = " . $this->current_session . "
                AND (
                    (subject_timetable.start_time <= " . $this->db->escape($tf_24) . " AND subject_timetable.end_time > " . $this->db->escape($tf_24) . ")
                    OR
                    (subject_timetable.start_time < " . $this->db->escape($tt_24) . " AND subject_timetable.end_time >= " . $this->db->escape($tt_24) . ")
                    OR
                    (subject_timetable.start_time >= " . $this->db->escape($tf_24) . " AND subject_timetable.end_time <= " . $this->db->escape($tt_24) . ")
                )
                AND subject_timetable.id NOT IN (
                    SELECT subject_timetable_id FROM staff_substitutions 
                    WHERE absent_staff_id = " . $this->db->escape($substitute_staff_id) . " 
                    AND date = " . $this->db->escape($date) . " 
                    AND session_id = " . $this->current_session . "
                )";
        $query = $this->db->query($sql);
        $standard_conflict = $query->row_array();

        if ($standard_conflict) {
            $standard_conflict['conflict_type'] = 'regular';
            return $standard_conflict;
        }

        // Also check if they are already substituting another class at the same time
        $sql2 = "SELECT subject_timetable.*, classes.class, sections.section, subjects.name as subject_name 
                 FROM staff_substitutions
                 INNER JOIN subject_timetable ON subject_timetable.id = staff_substitutions.subject_timetable_id
                 INNER JOIN classes ON classes.id = subject_timetable.class_id 
                 INNER JOIN sections ON sections.id = subject_timetable.section_id 
                 LEFT JOIN subject_group_subjects ON subject_group_subjects.id = subject_timetable.subject_group_subject_id 
                 LEFT JOIN subjects ON subjects.id = subject_group_subjects.subject_id 
                 WHERE staff_substitutions.substitute_staff_id = " . $this->db->escape($substitute_staff_id) . " 
                 AND staff_substitutions.date = " . $this->db->escape($date) . " 
                 AND staff_substitutions.session_id = " . $this->current_session . " 
                 AND (
                    (subject_timetable.start_time <= " . $this->db->escape($tf_24) . " AND subject_timetable.end_time > " . $this->db->escape($tf_24) . ")
                    OR
                    (subject_timetable.start_time < " . $this->db->escape($tt_24) . " AND subject_timetable.end_time >= " . $this->db->escape($tt_24) . ")
                    OR
                    (subject_timetable.start_time >= " . $this->db->escape($tf_24) . " AND subject_timetable.end_time <= " . $this->db->escape($tt_24) . ")
                 )";
        $query2 = $this->db->query($sql2);
        $sub_conflict = $query2->row_array();
        if ($sub_conflict) {
            $sub_conflict['conflict_type'] = 'substitution';
            return $sub_conflict;
        }
        return null;
    }

    public function get_substitution_history($date = null, $staff_id = null)
    {
        $condition = " WHERE ss.session_id = " . $this->current_session;
        if (!empty($date)) {
            $condition .= " AND ss.date = " . $this->db->escape($date);
        }
        if (!empty($staff_id)) {
            $condition .= " AND (ss.absent_staff_id = " . $this->db->escape($staff_id) . " OR ss.substitute_staff_id = " . $this->db->escape($staff_id) . ")";
        }

        $sql = "SELECT ss.*, 
                absent_staff.name as absent_name, absent_staff.surname as absent_surname, absent_staff.employee_id as absent_emp_id,
                sub_staff.name as sub_name, sub_staff.surname as sub_surname, sub_staff.employee_id as sub_emp_id,
                admin_staff.name as admin_name, admin_staff.surname as admin_surname, admin_staff.employee_id as admin_emp_id,
                classes.class, sections.section, 
                IF(sub_subjects.name IS NOT NULL, sub_subjects.name, subjects.name) as subject_name, 
                subject_timetable.time_from, subject_timetable.time_to, subject_timetable.class_id, subject_timetable.section_id, subject_timetable.day
                FROM staff_substitutions ss
                INNER JOIN staff as absent_staff ON absent_staff.id = ss.absent_staff_id
                INNER JOIN staff as sub_staff ON sub_staff.id = ss.substitute_staff_id
                LEFT JOIN staff as admin_staff ON admin_staff.id = ss.created_by
                INNER JOIN subject_timetable ON subject_timetable.id = ss.subject_timetable_id
                INNER JOIN classes ON classes.id = subject_timetable.class_id
                INNER JOIN sections ON sections.id = subject_timetable.section_id
                LEFT JOIN subject_group_subjects ON subject_group_subjects.id = subject_timetable.subject_group_subject_id
                LEFT JOIN subjects ON subjects.id = subject_group_subjects.subject_id
                LEFT JOIN subjects as sub_subjects ON sub_subjects.id = ss.substitute_subject_id
                " . $condition . "
                ORDER BY ss.id DESC";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
}
?>
