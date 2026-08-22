<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Aiexamsyllabus extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Ai_exam_generator');
        $this->load->model(['class_model', 'subject_model']);
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    /**
     * Curriculum & Syllabus Catalog Manager View
     */
    public function index()
    {
        if (!$this->rbac->hasPrivilege('examination', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'aiexam');
        $this->session->set_userdata('sub_menu', 'admin/aiexamsyllabus');

        $data['classlist']       = $this->class_model->get();
        $data['subjectlist']     = $this->subject_model->get();
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['current_session'] = $this->setting_model->getCurrentSessionName();

        // Fetch all cached syllabus records from database
        $this->db->select('*');
        $this->db->from('cbse_syllabus_chapters');
        $this->db->order_by('class_name', 'ASC');
        $this->db->order_by('subject_name', 'ASC');
        $data['syllabus_list']   = $this->db->get()->result_array();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/aiexam/syllabus_catalog', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * AJAX Endpoint: Save or Edit Chapters for a specific Class + Subject
     */
    public function save_chapters_ajax()
    {
        if (!$this->rbac->hasPrivilege('examination', 'can_edit')) {
            echo json_encode(['status' => 'error', 'message' => 'Access Denied']);
            return;
        }

        $class_name   = trim($this->input->post('class_name'));
        $subject_name = trim($this->input->post('subject_name'));
        $chapters_raw = trim($this->input->post('chapters_raw'));

        if (empty($class_name) || empty($subject_name)) {
            echo json_encode(['status' => 'error', 'message' => 'Class and Subject are required.']);
            return;
        }

        $lines = explode("\n", str_replace("\r", "", $chapters_raw));
        $chapters = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line)) {
                $chapters[] = $line;
            }
        }

        if (empty($chapters)) {
            echo json_encode(['status' => 'error', 'message' => 'Please provide at least 1 chapter.']);
            return;
        }

        $this->db->where('class_name', $class_name);
        $this->db->where('subject_name', $subject_name);
        $this->db->delete('cbse_syllabus_chapters');

        $this->db->insert('cbse_syllabus_chapters', [
            'class_name'    => $class_name,
            'subject_name'  => $subject_name,
            'chapters_json' => json_encode($chapters),
            'updated_at'    => date('Y-m-d H:i:s')
        ]);

        echo json_encode([
            'status'  => 'success',
            'message' => 'Curriculum syllabus chapters saved successfully!'
        ]);
    }

    /**
     * AJAX Endpoint: Delete specific syllabus cache entry
     */
    public function delete_syllabus_ajax()
    {
        if (!$this->rbac->hasPrivilege('examination', 'can_delete')) {
            echo json_encode(['status' => 'error', 'message' => 'Access Denied']);
            return;
        }

        $id = $this->input->post('id');
        if (!empty($id)) {
            $this->db->where('id', $id);
            $this->db->delete('cbse_syllabus_chapters');
            echo json_encode(['status' => 'success', 'message' => 'Syllabus entry removed.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid ID.']);
        }
    }
}
