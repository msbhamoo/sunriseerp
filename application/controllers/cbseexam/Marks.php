<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Marks extends MY_Addon_CBSEController
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->load->model('cbseexam/cbseexam_admitcard_model');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('cbse_exam_marks', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'cbse_exam');
        $this->session->set_userdata('sub_menu', 'cbse_exam/marks');
        
        $data['title'] = 'Marks Register';
        $data['classes'] = $this->class_model->get();

        $this->load->view('layout/header', $data);
        $this->load->view('cbseexam/marks/index', $data);
        $this->load->view('layout/footer', $data);
    }

    public function get_sections_multi()
    {
        $class_ids = $this->input->post('class_ids'); // array
        $sections = array();
        if (!empty($class_ids) && is_array($class_ids)) {
            foreach ($class_ids as $class_id) {
                $data = $this->section_model->getClassBySection($class_id);
                foreach ($data as $sec) {
                    $sections[$sec['section_id']] = $sec; // Keep unique by section_id
                }
            }
        }
        echo json_encode(array_values($sections));
    }

    public function get_exams_multi()
    {
        $class_ids = $this->input->post('class_ids');
        $section_ids = $this->input->post('section_ids');
        $exams = array();
        
        if (!empty($class_ids) && is_array($class_ids) && !empty($section_ids) && is_array($section_ids)) {
            foreach ($class_ids as $class_id) {
                foreach ($section_ids as $section_id) {
                    $data = $this->cbseexam_admitcard_model->get_cbse_exam_list($class_id, $section_id);
                    foreach ($data as $ex) {
                        $exams[$ex->id] = $ex; // Keep unique by exam id
                    }
                }
            }
        }
        echo json_encode(array_values($exams));
    }

    public function subjectstudent_multi()
    {
        $data['timetable_id'] = $this->input->post('timetable_id');
        $data['subject_id'] = $this->input->post('subject_id');
        $data['exam_id'] = $this->input->post('exam_id');
        $class_ids = $this->input->post('class_ids');
        $section_ids = $this->input->post('section_ids');
        
        if (!is_array($class_ids)) $class_ids = array();
        if (!is_array($section_ids)) $section_ids = array();
        
        $examdetails = $this->cbseexam_exam_model->get_exambyId($data['exam_id']);
        $data['exam'] = $examdetails;
        
        $resultlist = $this->cbseexam_exam_model->get_markexamstudents_multi($data['timetable_id'], $class_ids, $section_ids);
        $data['resultlist'] = $resultlist;
        
        $data['exam_assessment_types'] = $this->cbseexam_exam_model->get_exam_subject_assessment_types($examdetails['cbse_exam_assessment_id'], $data['timetable_id']);
        $subject_detail = $this->batchsubject_model->getExamSubject($data['subject_id']);
        $data['subject_detail'] = $subject_detail;
        $data['sch_setting'] = $this->sch_setting_detail;
        
        $student_exam_page = $this->load->view('cbseexam/exam/_partialstudentmarkEntry', $data, true);
        $array = array('status' => '1', 'error' => '', 'page' => $student_exam_page);
        echo json_encode($array);
    }
}
