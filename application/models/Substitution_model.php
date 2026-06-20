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
        $this->db->select('*');
        $this->db->from('staff_leave_request');
        $this->db->where('staff_id', $staff_id);
        $this->db->where('status', 'approve');
        $this->db->where("date(leave_from) <= ", $date);
        $this->db->where("date(leave_to) >= ", $date);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function insert_substitutions($data)
    {
        $this->db->insert_batch('staff_substitutions', $data);
    }

    public function check_conflict($substitute_staff_id, $day, $time_from, $time_to, $date)
    {
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
                    (subject_timetable.time_from <= " . $this->db->escape($time_from) . " AND subject_timetable.time_to > " . $this->db->escape($time_from) . ") 
                    OR 
                    (subject_timetable.time_from < " . $this->db->escape($time_to) . " AND subject_timetable.time_to >= " . $this->db->escape($time_to) . ") 
                    OR 
                    (subject_timetable.time_from >= " . $this->db->escape($time_from) . " AND subject_timetable.time_to <= " . $this->db->escape($time_to) . ") 
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
                    (subject_timetable.time_from <= " . $this->db->escape($time_from) . " AND subject_timetable.time_to > " . $this->db->escape($time_from) . ") 
                    OR 
                    (subject_timetable.time_from < " . $this->db->escape($time_to) . " AND subject_timetable.time_to >= " . $this->db->escape($time_to) . ") 
                    OR 
                    (subject_timetable.time_from >= " . $this->db->escape($time_from) . " AND subject_timetable.time_to <= " . $this->db->escape($time_to) . ") 
                 )";
        $query2 = $this->db->query($sql2);
        return $query2->row_array();
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
                subject_timetable.time_from, subject_timetable.time_to
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
