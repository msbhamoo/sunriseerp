<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Studentcall_model extends MY_Model
{
    protected $current_session;

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function get_purposes()
    {
        $this->db->select('*');
        $this->db->from('student_call_purpose');
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function add_purpose($data)
    {
        $this->db->insert('student_call_purpose', $data);
        return $this->db->insert_id();
    }

    public function update_purpose($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('student_call_purpose', $data);
    }

    public function delete_purpose($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('student_call_purpose');
    }

    public function get_purpose($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('student_call_purpose');
        return $query->row_array();
    }

    public function add_call($data)
    {
        $this->db->insert('student_calls', $data);
        return $this->db->insert_id();
    }

    public function add_followup($data)
    {
        $this->db->insert('student_call_followups', $data);
        return $this->db->insert_id();
    }

    public function update_followup($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('student_call_followups', $data);
    }

    public function search_student($query)
    {
        $this->db->select('students.id as student_id, student_session.id as student_session_id, students.admission_no, students.roll_no, students.firstname, students.middlename, students.lastname, students.mobileno, students.father_phone, students.mother_phone, students.guardian_phone, classes.class, sections.section');
        $this->db->from('student_session');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'student_session.section_id = sections.id');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        
        $this->db->group_start();
        $this->db->like('students.firstname', $query);
        $this->db->or_like('students.lastname', $query);
        $this->db->or_like('students.admission_no', $query);
        $this->db->or_like('students.roll_no', $query);
        $this->db->group_end();
        
        $this->db->limit(20);
        $q = $this->db->get();
        return $q->result_array();
    }

    public function get_calls($class_id = null, $section_id = null, $date_from = null, $date_to = null, $purpose_id = null, $status = null, $follow_up_date_from = null, $follow_up_date_to = null, $assigned_to = null)
    {
        $this->db->select('student_calls.*, students.firstname, students.lastname, students.admission_no, classes.class, sections.section, student_call_purpose.purpose as purpose_name, staff.name as staff_name, staff.surname as staff_surname, (SELECT due_date FROM student_call_followups WHERE student_call_id = student_calls.id AND status = "Pending" ORDER BY id ASC LIMIT 1) as next_follow_up_date, (SELECT count(id) FROM student_call_followups WHERE student_call_id = student_calls.id AND status = "Pending") as pending_count, (SELECT count(id) FROM student_call_followups WHERE student_call_id = student_calls.id) as total_followups, (SELECT CONCAT(staff.name, " ", staff.surname) FROM student_call_followups JOIN staff ON staff.id = student_call_followups.assigned_to WHERE student_call_id = student_calls.id AND student_call_followups.status = "Pending" ORDER BY student_call_followups.id ASC LIMIT 1) as assigned_to_name');
        $this->db->from('student_calls');
        $this->db->join('student_session', 'student_session.id = student_calls.student_session_id');
        $this->db->join('students', 'students.id = student_calls.student_id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'student_session.section_id = sections.id');
        $this->db->join('student_call_purpose', 'student_call_purpose.id = student_calls.call_purpose_id', 'left');
        $this->db->join('staff', 'staff.id = student_calls.created_by', 'left');

        if (!empty($class_id)) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if (!empty($section_id)) {
            $this->db->where('student_session.section_id', $section_id);
        }
        if (!empty($purpose_id)) {
            $this->db->where('student_calls.call_purpose_id', $purpose_id);
        }
        if (!empty($status)) {
            $this->db->where('student_calls.call_status', $status);
        }
        if (!empty($date_from) && !empty($date_to)) {
            $this->db->where('DATE(student_calls.date) >=', $date_from);
            $this->db->where('DATE(student_calls.date) <=', $date_to);
        }
        
        if (!empty($assigned_to)) {
            $this->db->where('EXISTS (SELECT 1 FROM student_call_followups WHERE student_call_followups.student_call_id = student_calls.id AND student_call_followups.status = \'Pending\' AND student_call_followups.assigned_to = '.(int)$assigned_to.')', NULL, FALSE);
        }
        if (!empty($follow_up_date_from) && !empty($follow_up_date_to)) {
            $this->db->where('EXISTS (SELECT 1 FROM student_call_followups WHERE student_call_followups.student_call_id = student_calls.id AND student_call_followups.status = \'Pending\' AND DATE(student_call_followups.due_date) >= '.$this->db->escape($follow_up_date_from).' AND DATE(student_call_followups.due_date) <= '.$this->db->escape($follow_up_date_to).')', NULL, FALSE);
        } elseif (!empty($follow_up_date_from)) {
            $this->db->where('EXISTS (SELECT 1 FROM student_call_followups WHERE student_call_followups.student_call_id = student_calls.id AND student_call_followups.status = \'Pending\' AND DATE(student_call_followups.due_date) >= '.$this->db->escape($follow_up_date_from).')', NULL, FALSE);
        } elseif (!empty($follow_up_date_to)) {
            $this->db->where('EXISTS (SELECT 1 FROM student_call_followups WHERE student_call_followups.student_call_id = student_calls.id AND student_call_followups.status = \'Pending\' AND DATE(student_call_followups.due_date) <= '.$this->db->escape($follow_up_date_to).')', NULL, FALSE);
        }

        $this->db->order_by('student_calls.id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_call($id)
    {
        $this->db->select('student_calls.*, students.firstname, students.lastname, students.admission_no, classes.class, sections.section, student_call_purpose.purpose as purpose_name');
        $this->db->from('student_calls');
        $this->db->join('student_session', 'student_session.id = student_calls.student_session_id');
        $this->db->join('students', 'students.id = student_calls.student_id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'student_session.section_id = sections.id');
        $this->db->join('student_call_purpose', 'student_call_purpose.id = student_calls.call_purpose_id', 'left');
        $this->db->where('student_calls.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_followups_by_call($call_id)
    {
        $this->db->select('student_call_followups.*, staff.name as assigned_name, staff.surname as assigned_surname');
        $this->db->from('student_call_followups');
        $this->db->join('staff', 'staff.id = student_call_followups.assigned_to', 'left');
        $this->db->where('student_call_followups.student_call_id', $call_id);
        $this->db->order_by('student_call_followups.id', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_followups_by_student($student_id)
    {
        $this->db->select('student_call_followups.*, student_calls.date as call_date, student_calls.notes as call_notes, student_call_purpose.purpose as purpose_name, staff.name as assigned_name, staff.surname as assigned_surname');
        $this->db->from('student_call_followups');
        $this->db->join('student_calls', 'student_calls.id = student_call_followups.student_call_id');
        $this->db->join('student_call_purpose', 'student_call_purpose.id = student_calls.call_purpose_id', 'left');
        $this->db->join('staff', 'staff.id = student_call_followups.assigned_to', 'left');
        $this->db->where('student_call_followups.student_id', $student_id);
        $this->db->order_by('student_call_followups.id', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_pending_followups_by_staff($staff_id)
    {
        $this->db->select('student_call_followups.*, student_calls.phone_number, student_calls.contact_person, students.firstname, students.lastname, students.admission_no');
        $this->db->from('student_call_followups');
        $this->db->join('student_calls', 'student_calls.id = student_call_followups.student_call_id');
        $this->db->join('students', 'students.id = student_calls.student_id');
        $this->db->where('student_call_followups.assigned_to', $staff_id);
        $this->db->where('student_call_followups.status', 'Pending');
        $this->db->order_by('student_call_followups.due_date', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_call_statistics($staff_id = null)
    {
        $this->db->select('call_status, count(*) as count');
        $this->db->from('student_calls');
        if ($staff_id) {
            $this->db->where('created_by', $staff_id);
        }
        $this->db->group_by('call_status');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_followup($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('student_call_followups');
        return $query->row_array();
    }

    public function get_recent_history($student_id, $limit = 3)
    {
        $this->db->select('student_calls.date, student_calls.call_status, student_calls.notes, student_call_purpose.purpose as purpose_name');
        $this->db->from('student_calls');
        $this->db->join('student_call_purpose', 'student_call_purpose.id = student_calls.call_purpose_id', 'left');
        $this->db->where('student_calls.student_id', $student_id);
        $this->db->order_by('student_calls.date', 'desc');
        $this->db->limit($limit);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_calls_by_student($student_session_id)
    {
        $this->db->select('student_calls.*, student_call_purpose.purpose as purpose_name, staff.name as staff_name, staff.surname as staff_surname');
        $this->db->from('student_calls');
        $this->db->join('student_call_purpose', 'student_call_purpose.id = student_calls.call_purpose_id', 'left');
        $this->db->join('staff', 'staff.id = student_calls.created_by', 'left');
        $this->db->where('student_calls.student_session_id', $student_session_id);
        $this->db->order_by('student_calls.date', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_today_call_statistics($staff_id = null)
    {
        $this->db->select('call_status, count(*) as count');
        $this->db->from('student_calls');
        $this->db->where('DATE(date)', date('Y-m-d'));
        if ($staff_id) {
            $this->db->where('created_by', $staff_id);
        }
        $this->db->group_by('call_status');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_students_call_status($class_id = null, $section_id = null, $start = 0, $length = 10, $search_value = '')
    {
        $this->db->select('students.id as student_id, student_session.id as student_session_id, students.firstname, students.lastname, students.admission_no, students.mobileno, students.father_phone, students.mother_phone, students.guardian_phone, classes.class, sections.section, sc.date as last_call_date, sc.call_status as last_call_status');
        $this->db->from('student_session');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'student_session.section_id = sections.id');
        
        // Join with subquery to get the most recent call status
        $this->db->join('(SELECT student_session_id, MAX(date) as max_date FROM student_calls GROUP BY student_session_id) as latest_call', 'latest_call.student_session_id = student_session.id', 'left', false);
        $this->db->join('student_calls sc', 'sc.student_session_id = student_session.id AND sc.date = latest_call.max_date', 'left', false);

        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');

        if (!empty($class_id)) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if (!empty($section_id)) {
            $this->db->where('student_session.section_id', $section_id);
        }

        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('students.firstname', $search_value);
            $this->db->or_like('students.lastname', $search_value);
            $this->db->or_like('students.admission_no', $search_value);
            $this->db->group_end();
        }

        $this->db->order_by('students.firstname', 'asc');
        
        if ($length != -1) {
            $this->db->limit($length, $start);
        }
        
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_students_call_status_count($class_id = null, $section_id = null, $search_value = '')
    {
        $this->db->select('students.id');
        $this->db->from('student_session');
        $this->db->join('students', 'students.id = student_session.student_id');
        
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');

        if (!empty($class_id)) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if (!empty($section_id)) {
            $this->db->where('student_session.section_id', $section_id);
        }

        if (!empty($search_value)) {
            $this->db->group_start();
            $this->db->like('students.firstname', $search_value);
            $this->db->or_like('students.lastname', $search_value);
            $this->db->or_like('students.admission_no', $search_value);
            $this->db->group_end();
        }
        
        $query = $this->db->get();
        return $query->num_rows();
    }
}
