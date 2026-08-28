<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Hostelgatepass_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($id = null)
    {
        $this->db->select('hostel_gate_pass.*, students.firstname, students.lastname, students.admission_no, students.father_name, students.father_phone, students.guardian_name, students.guardian_phone, students.mobileno, students.mother_name, students.mother_phone, classes.class as class_name, sections.section as section_name');
        $this->db->from('hostel_gate_pass');
        $this->db->join('student_session', 'student_session.id = hostel_gate_pass.student_session_id');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->join('classes', 'classes.id = student_session.class_id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        
        if ($id != null) {
            $this->db->where('hostel_gate_pass.id', $id);
            return $this->db->get()->row_array();
        } else {
            $this->db->order_by('hostel_gate_pass.id', 'desc');
            return $this->db->get()->result_array();
        }
    }

    public function add($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('hostel_gate_pass', $data);
            return $data['id'];
        } else {
            $this->db->insert('hostel_gate_pass', $data);
            return $this->db->insert_id();
        }
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('hostel_gate_pass');
    }

    public function mark_returned($id)
    {
        $data = array(
            'id' => $id,
            'status' => 'Returned',
            'actual_in_date' => date('Y-m-d'),
            'actual_in_time' => date('H:i:s')
        );
        $this->db->where('id', $id);
        $this->db->update('hostel_gate_pass', $data);
    }
}
