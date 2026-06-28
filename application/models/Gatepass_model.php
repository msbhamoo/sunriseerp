<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Gatepass_model extends MY_Model
{
    public $current_session;

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function add($data)
    {
        if (isset($data['id']) && $data['id'] != '') {
            $this->db->where('id', $data['id']);
            $this->db->update('front_office_gate_pass', $data);
            return $data['id'];
        } else {
            $this->db->insert('front_office_gate_pass', $data);
            return $this->db->insert_id();
        }
    }

    public function get($id = null)
    {
        $this->db->select('front_office_gate_pass.*');
        $this->db->from('front_office_gate_pass');
        
        if ($id != null) {
            $this->db->where('front_office_gate_pass.id', $id);
        } else {
            $this->db->order_by('front_office_gate_pass.id', 'desc');
        }

        $query = $this->db->get();

        if ($id != null) {
            $result = $query->row_array();
            if ($result) {
                $result['user_details'] = $this->get_user_details($result['user_type'], $result['user_id']);
            }
            return $result;
        } else {
            $results = $query->result_array();
            foreach ($results as $key => $value) {
                $results[$key]['user_details'] = $this->get_user_details($value['user_type'], $value['user_id']);
            }
            return $results;
        }
    }

    public function getByUser($user_type, $user_id)
    {
        $this->db->select('front_office_gate_pass.*');
        $this->db->from('front_office_gate_pass');
        $this->db->where('user_type', $user_type);
        $this->db->where('user_id', $user_id);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }

    private function get_user_details($user_type, $user_id)
    {
        if ($user_type == 'student') {
            $this->db->select('students.firstname, students.lastname, students.admission_no, classes.class, sections.section');
            $this->db->from('students');
            $this->db->join('student_session', 'student_session.student_id = students.id');
            $this->db->join('classes', 'classes.id = student_session.class_id');
            $this->db->join('sections', 'sections.id = student_session.section_id');
            $this->db->where('students.id', $user_id);
            $this->db->where('student_session.session_id', $this->current_session);
            $query = $this->db->get();
            $student = $query->row_array();
            if ($student) {
                return $student['firstname'] . ' ' . $student['lastname'] . ' (' . $student['admission_no'] . ') - ' . $student['class'] . ' (' . $student['section'] . ')';
            }
        } elseif ($user_type == 'staff') {
            $this->db->select('staff.name, staff.surname, staff.employee_id, roles.name as role_name');
            $this->db->from('staff');
            $this->db->join('staff_roles', 'staff_roles.staff_id = staff.id');
            $this->db->join('roles', 'roles.id = staff_roles.role_id');
            $this->db->where('staff.id', $user_id);
            $query = $this->db->get();
            $staff = $query->row_array();
            if ($staff) {
                return $staff['name'] . ' ' . $staff['surname'] . ' (' . $staff['employee_id'] . ') - ' . $staff['role_name'];
            }
        }
        return 'Unknown User';
    }

    public function generate_gate_pass_no()
    {
        $this->db->select('gate_pass_no');
        $this->db->from('front_office_gate_pass');
        $this->db->order_by('id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get();
        $result = $query->row_array();

        if ($result) {
            $last_no = $result['gate_pass_no'];
            // assuming format GP-0001
            $parts = explode('-', $last_no);
            if(count($parts) == 2){
                $number = (int)$parts[1];
                $number++;
                return 'GP-' . sprintf('%04d', $number);
            }
        }
        
        return 'GP-0001';
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('front_office_gate_pass');
    }
}
