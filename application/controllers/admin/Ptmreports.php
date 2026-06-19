<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Ptmreports extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ptm_model');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('ptm_parent_teacher_meeting', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/ptm');
        $this->session->set_userdata('sub_menu_ptm', 'admin/ptmreports/index');
        
        $data['title'] = 'PTM Report';
        $data['ptm_list'] = $this->ptm_model->get();

        if ($this->input->post('search')) {
            $ptm_id = $this->input->post('ptm_id');
            $data['selected_ptm'] = $ptm_id;
            
            $data['ptm'] = $this->ptm_model->get($ptm_id);
            $data['targets'] = $this->ptm_model->get_targets($ptm_id);
            
            $students = [];
            if ($data['ptm']['target_type'] == 'whole_school') {
                $students = $this->student_model->get();
            } else {
                foreach ($data['targets'] as $target) {
                    $class_students = $this->student_model->searchByClassSection($target['class_id'], $target['section_id']);
                    $students = array_merge($students, $class_students);
                }
            }
            $data['students'] = $students;
            $data['attendances'] = $this->ptm_model->get_student_attendances($ptm_id);
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/ptm/report', $data);
        $this->load->view('layout/footer', $data);
    }

    public function get_student_profile_data()
    {
        $student_id = $this->input->post('student_id');
        $student_session_id = $this->input->post('student_session_id');
        $ptm_id = $this->input->post('ptm_id');
        
        // Student Info
        $student = $this->student_model->get($student_id);
        
        // Fee Info
        $this->load->model('studentfeemaster_model');
        $fees = $this->studentfeemaster_model->getStudentFees($student_session_id);
        
        // CBSE Exams
        $this->load->model('cbseexam/cbseexam_result_model');
        // Find exams based on student session
        $cbse_exams = [];
        if (method_exists($this->cbseexam_result_model, 'getStudentResult')) {
            $cbse_exams = $this->cbseexam_result_model->getStudentResult($student_session_id);
        } else {
            // fallback generic query if getStudentResult doesn't exist
            $cbse_exams = $this->db->where('student_session_id', $student_session_id)->get('cbse_exam_results')->result_array();
        }
        
        // PTM Remarks (current)
        $current_ptm = $this->ptm_model->get_student_attendances($ptm_id);
        $ptm_remarks = isset($current_ptm[$student_session_id]) ? $current_ptm[$student_session_id] : null;

        // Previous PTM Remarks
        $this->db->select('ptm_attendances.*, ptms.title, ptms.ptm_date');
        $this->db->from('ptm_attendances');
        $this->db->join('ptms', 'ptms.id = ptm_attendances.ptm_id');
        $this->db->where('ptm_attendances.student_session_id', $student_session_id);
        $this->db->where('ptm_attendances.ptm_id !=', $ptm_id);
        $this->db->order_by('ptms.ptm_date', 'desc');
        $previous_ptm_remarks = $this->db->get()->result_array();
        
        $data = [
            'student' => $student,
            'fees' => $fees,
            'cbse_exams' => $cbse_exams,
            'ptm_remarks' => $ptm_remarks,
            'previous_ptm_remarks' => $previous_ptm_remarks,
            'settinglist' => $this->setting_model->get()
        ];
        
        $html = $this->load->view('admin/ptm/_student_profile', $data, true);
        echo json_encode(['status' => 1, 'page' => $html]);
    }
}
