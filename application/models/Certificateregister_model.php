<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Certificateregister_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get($id = null) {
        $current_session = $this->setting_model->getCurrentSession();
        
        $this->db->select('student_certificate_register.*, student_certificate_types.certificate_name, student_certificate_types.fields_config, students.firstname, students.lastname, students.admission_no, students.image, students.father_name, students.mobileno, classes.class, sections.section, staff.name as generated_by_name');
        $this->db->from('student_certificate_register');
        $this->db->join('student_certificate_types', 'student_certificate_types.id = student_certificate_register.student_certificate_type_id', 'left');
        $this->db->join('students', 'students.id = student_certificate_register.student_id', 'left');
        $this->db->join('student_session', 'student_session.student_id = students.id AND student_session.session_id = '.$current_session, 'left');
        $this->db->join('classes', 'student_session.class_id = classes.id', 'left');
        $this->db->join('sections', 'student_session.section_id = sections.id', 'left');
        $this->db->join('staff', 'staff.id = student_certificate_register.generated_by', 'left');
        
        if ($id != null) {
            $this->db->where('student_certificate_register.id', $id);
            return $this->db->get()->row_array();
        }
        
        $this->db->order_by('student_certificate_register.id', 'desc');
        return $this->db->get()->result_array();
    }

    public function getByStudent($student_id) {
        $this->db->select('student_certificate_register.*, student_certificate_types.certificate_name, staff.name as generated_by_name');
        $this->db->from('student_certificate_register');
        $this->db->join('student_certificate_types', 'student_certificate_types.id = student_certificate_register.student_certificate_type_id', 'left');
        $this->db->join('staff', 'staff.id = student_certificate_register.generated_by', 'left');
        $this->db->where('student_certificate_register.student_id', $student_id);
        $this->db->order_by('student_certificate_register.id', 'desc');
        return $this->db->get()->result_array();
    }

    public function add($data) {
        $this->db->insert('student_certificate_register', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('student_certificate_register', $data);
    }
}
