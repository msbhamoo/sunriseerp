<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Scholarregister_model extends MY_Model {

    protected $current_session;

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function get_student_scholar_register($student_id) {
        $this->db->select('*');
        $this->db->from('student_scholar_register');
        $this->db->where('student_id', $student_id);
        return $this->db->get()->row_array();
    }

    public function get_student_scholar_register_history($student_session_id) {
        $this->db->select('student_scholar_register_history.*, sessions.session, classes.class, sections.section');
        $this->db->from('student_scholar_register_history');
        $this->db->join('sessions', 'sessions.id = student_scholar_register_history.session_id', 'left');
        $this->db->join('classes', 'classes.id = student_scholar_register_history.class_id', 'left');
        $this->db->join('sections', 'sections.id = student_scholar_register_history.section_id', 'left');
        $this->db->where('student_scholar_register_history.student_session_id', $student_session_id);
        $this->db->order_by('sessions.id', 'asc');
        return $this->db->get()->result_array();
    }

    public function get_history_by_student($student_id) {
        $this->db->select('student_scholar_register_history.*, sessions.session, classes.class, sections.section');
        $this->db->from('student_scholar_register_history');
        $this->db->join('student_session', 'student_session.id = student_scholar_register_history.student_session_id');
        $this->db->join('sessions', 'sessions.id = student_scholar_register_history.session_id', 'left');
        $this->db->join('classes', 'classes.id = student_scholar_register_history.class_id', 'left');
        $this->db->join('sections', 'sections.id = student_scholar_register_history.section_id', 'left');
        $this->db->where('student_session.student_id', $student_id);
        $this->db->order_by('sessions.id', 'desc');
        return $this->db->get()->result_array();
    }

    public function add_or_update_scholar_register($data) {
        $existing = $this->get_student_scholar_register($data['student_id']);
        if (!empty($existing)) {
            $this->db->where('id', $existing['id']);
            $this->db->update('student_scholar_register', $data);
            return $existing['id'];
        } else {
            $this->db->insert('student_scholar_register', $data);
            return $this->db->insert_id();
        }
    }

    public function add_or_update_scholar_register_history($data) {
        $this->db->where('student_session_id', $data['student_session_id']);
        $existing = $this->db->get('student_scholar_register_history')->row_array();
        if (!empty($existing)) {
            $this->db->where('id', $existing['id']);
            $this->db->update('student_scholar_register_history', $data);
            return $existing['id'];
        } else {
            $this->db->insert('student_scholar_register_history', $data);
            return $this->db->insert_id();
        }
    }
}
