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
        $this->load->model('staff_model');
        $this->load->model('section_model');
        $data['staff_list'] = $this->staff_model->get();
        $data['classlist'] = $this->class_model->get();
        
        $current_staff_id = $this->customlib->getStaffID();
        $data['current_staff_id'] = $current_staff_id;
        $data['my_followups'] = $this->ptm_model->get_assigned_followups($current_staff_id);

        $this->load->view('layout/header', $data);
        $this->load->view('admin/ptm/report', $data);
        $this->load->view('layout/footer', $data);
    }

    public function get_sections_multi()
    {
        $this->load->model('section_model');
        $class_ids = $this->input->post('class_ids');
        $sections = array();
        if (!empty($class_ids) && is_array($class_ids)) {
            foreach ($class_ids as $class_id) {
                $data = $this->section_model->getClassBySection($class_id);
                if (!empty($data)) {
                    foreach ($data as $sec) {
                        $sections[$sec['section_id']] = $sec;
                    }
                }
            }
        }
        echo json_encode(array_values($sections));
    }

    public function get_report_data()
    {
        if (!$this->rbac->hasPrivilege('ptm_parent_teacher_meeting', 'can_view')) {
            echo json_encode(['status' => 0, 'message' => 'Access Denied']);
            return;
        }

        $report_type = $this->input->post('report_type');
        $ptm_id = $this->input->post('ptm_id');
        $class_ids = (array)$this->input->post('class_ids');
        $section_ids = (array)$this->input->post('section_ids');
        $staff_id = $this->input->post('staff_id');

        $class_ids = array_values(array_filter($class_ids));
        $section_ids = array_values(array_filter($section_ids));

        $ptm = $this->ptm_model->get($ptm_id);
        if (empty($ptm)) {
            echo json_encode(['status' => 0, 'message' => 'Please select a valid PTM meeting.']);
            return;
        }

        $targets = $this->ptm_model->get_targets($ptm_id);
        $attendances = $this->ptm_model->get_student_attendances($ptm_id);

        $students = [];
        if (!empty($class_ids)) {
            if (!empty($section_ids)) {
                foreach ($class_ids as $c_id) {
                    foreach ($section_ids as $s_id) {
                        $class_students = $this->student_model->searchByClassSection($c_id, $s_id);
                        if (!empty($class_students)) {
                            $students = array_merge($students, $class_students);
                        }
                    }
                }
            } else {
                foreach ($class_ids as $c_id) {
                    $class_students = $this->student_model->searchByClassSection($c_id, null);
                    if (!empty($class_students)) {
                        $students = array_merge($students, $class_students);
                    }
                }
            }
        } else {
            if ($ptm['target_type'] == 'whole_school') {
                $students = $this->student_model->get();
            } else {
                foreach ($targets as $target) {
                    $class_students = $this->student_model->searchByClassSection($target['class_id'], $target['section_id']);
                    if (!empty($class_students)) {
                        $students = array_merge($students, $class_students);
                    }
                }
            }
        }

        // Deduplicate students
        $unique_students = [];
        foreach ($students as $stu) {
            $sess_id = isset($stu['student_session_id']) ? $stu['student_session_id'] : (isset($stu['id']) ? $stu['id'] : null);
            if ($sess_id && !isset($unique_students[$sess_id])) {
                $unique_students[$sess_id] = $stu;
            }
        }
        $students = array_values($unique_students);

        $data = [
            'report_type' => $report_type,
            'ptm' => $ptm,
            'students' => $students,
            'attendances' => $attendances
        ];

        $html = $this->load->view('admin/ptm/_report_content', $data, true);
        echo json_encode(['status' => 1, 'html' => $html]);
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
