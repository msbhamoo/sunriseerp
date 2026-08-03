<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Ptm_model extends MY_Model
{
    public $current_session;


    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function get($id = null)
    {
        $this->db->select('ptms.*, staff.name as staff_name, staff.surname as staff_surname');
        $this->db->from('ptms');
        $this->db->join('staff', 'staff.id = ptms.created_by', 'left');
        
        if ($id != null) {
            $this->db->where('ptms.id', $id);
            $query = $this->db->get();
            return $query->row_array();
        } else {
            $this->db->order_by('ptms.ptm_date', 'desc');
            $query = $this->db->get();
            return $query->result_array();
        }
    }
    
    public function get_targets($ptm_id)
    {
        $this->db->select('ptm_targets.*, classes.class, sections.section');
        $this->db->from('ptm_targets');
        $this->db->join('classes', 'classes.id = ptm_targets.class_id', 'left');
        $this->db->join('sections', 'sections.id = ptm_targets.section_id', 'left');
        $this->db->where('ptm_targets.ptm_id', $ptm_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function add($data)
    {
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);

        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('ptms', $data);
            $insert_id = $data['id'];
            $message = UPDATE_RECORD_CONSTANT . " On ptms id " . $insert_id;
            $action = "Update";
        } else {
            $this->db->insert('ptms', $data);
            $insert_id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On ptms id " . $insert_id;
            $action = "Insert";
        }
        $this->log($message, $insert_id, $action);
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            return $insert_id;
        }
    }
    
    public function add_targets($ptm_id, $targets)
    {
        $this->db->where('ptm_id', $ptm_id);
        $this->db->delete('ptm_targets');
        if (!empty($targets)) {
            $insert_data = [];
            foreach ($targets as $target) {
                // target usually comes as class_id-section_id
                $parts = explode('-', $target);
                if (count($parts) == 2) {
                    $insert_data[] = [
                        'ptm_id' => $ptm_id,
                        'class_id' => $parts[0],
                        'section_id' => $parts[1]
                    ];
                }
            }
            if (!empty($insert_data)) {
                $this->db->insert_batch('ptm_targets', $insert_data);
            }
        }
    }

    public function remove($id)
    {
        $this->db->trans_start();
        $this->db->where('id', $id);
        $this->db->delete('ptms');
        
        $this->db->where('ptm_id', $id);
        $this->db->delete('ptm_targets');
        
        $this->db->where('ptm_id', $id);
        $this->db->delete('ptm_attendances');
        
        $message = DELETE_RECORD_CONSTANT . " On ptms id " . $id;
        $this->log($message, $id, "Delete");
        
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            return false;
        }
        return true;
    }

    public function get_student_attendances($ptm_id)
    {
        $this->db->select('ptm_attendances.*, staff.name as assigned_staff_name, staff.surname as assigned_staff_surname, staff.employee_id');
        $this->db->from('ptm_attendances');
        $this->db->join('staff', 'staff.id = ptm_attendances.followup_assigned_to', 'left');
        $this->db->where('ptm_id', $ptm_id);
        $query = $this->db->get();
        $result = $query->result_array();
        $return_array = [];
        foreach ($result as $row) {
            $return_array[$row['student_session_id']] = $row;
        }
        return $return_array;
    }

    public function get_assigned_followups($staff_id = null)
    {
        $this->db->select('ptm_attendances.*, ptms.title as ptm_title, ptms.ptm_date, students.firstname, students.lastname, students.admission_no, classes.class, sections.section, staff.name as assigned_staff_name, staff.surname as assigned_staff_surname, staff.employee_id');
        $this->db->from('ptm_attendances');
        $this->db->join('ptms', 'ptms.id = ptm_attendances.ptm_id');
        $this->db->join('student_session', 'student_session.id = ptm_attendances.student_session_id');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->join('classes', 'classes.id = student_session.class_id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('staff', 'staff.id = ptm_attendances.followup_assigned_to', 'left');
        $this->db->where('ptm_attendances.followup_required', 1);
        if ($staff_id != null) {
            $this->db->where('ptm_attendances.followup_assigned_to', $staff_id);
        }
        $this->db->order_by('ptm_attendances.followup_date', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function save_attendance($data)
    {
        $this->db->where('ptm_id', $data['ptm_id']);
        $this->db->where('student_session_id', $data['student_session_id']);
        $q = $this->db->get('ptm_attendances');
        if ($q->num_rows() > 0) {
            $result = $q->row_array();
            $this->db->where('id', $result['id']);
            $this->db->update('ptm_attendances', $data);
            return $result['id'];
        } else {
            $this->db->insert('ptm_attendances', $data);
            return $this->db->insert_id();
        }
    }
}
