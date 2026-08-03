<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Scholarshipexam extends Student_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('scholarshipexam_model');
        $this->load->model('setting_model');
    }

    public function index()
    {
        $this->session->set_userdata('top_menu', 'scholarship_exam');
        $student_session_id = $this->session->userdata('student')['student_session_id'];
        $class_id = $this->session->userdata('student')['class_id'];
        $student_id = $this->session->userdata('student')['student_id'];

        // Get exams active for student class
        $this->db->select('scholarship_exams.*, scholarship_exam_schedules.id as schedule_id, scholarship_exam_schedules.exam_date, scholarship_exam_schedules.duration_minutes, scholarship_exam_schedules.total_marks, scholarship_exam_schedules.registration_close_date');
        $this->db->from('scholarship_exams');
        $this->db->join('scholarship_exam_schedules', 'scholarship_exam_schedules.scholarship_exam_id = scholarship_exams.id', 'inner');
        $this->db->where('scholarship_exam_schedules.class_id', $class_id);
        $this->db->where('scholarship_exams.status', 1);
        $data['exams'] = $this->db->get()->result_array();

        foreach ($data['exams'] as &$e) {
            $cand = $this->db->get_where('scholarship_exam_candidates', array(
                'scholarship_exam_id' => $e['id'],
                'student_session_id' => $student_session_id
            ))->row_array();
            $e['is_registered'] = $cand ? 1 : 0;
            $e['candidate_details'] = $cand;
        }

        $this->load->view('layout/student/header');
        $this->load->view('user/scholarshipexam/index', $data);
        $this->load->view('layout/student/footer');
    }

    public function apply($exam_id, $schedule_id)
    {
        $student_session_id = $this->session->userdata('student')['student_session_id'];
        $exam = $this->scholarshipexam_model->getExams($exam_id);

        if ($exam) {
            $prefix = $exam['roll_no_prefix'] ?: 'OLY-';
            $this->scholarshipexam_model->registerCandidatesBatch($exam_id, $schedule_id, array($student_session_id), $prefix);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Successfully registered for ' . htmlspecialchars($exam['title']) . '! Roll Number assigned.</div>');
        }
        redirect('user/scholarshipexam');
    }
}
