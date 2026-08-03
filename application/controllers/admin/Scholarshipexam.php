<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Scholarshipexam extends Admin_Controller
{
    public $sch_setting_detail = array();

    public function __construct()
    {
        parent::__construct();
        $this->config->load('app-config');
        $this->load->model('scholarshipexam_model');
        $this->load->model('class_model');
        $this->load->model('section_model');
        $this->load->model('classsection_model');
        $this->load->model('subject_model');
        $this->load->model('setting_model');

        $this->sch_setting_detail = $this->setting_model->getSetting();

        if (!$this->rbac->hasPrivilege('scholarship_exam', 'can_view')) {
            access_denied();
        }
    }

    public function index()
    {
        $this->session->set_userdata('top_menu', 'scholarship_exam');
        $this->session->set_userdata('sub_menu', 'scholarshipexam_index');

        $data['stats'] = $this->scholarshipexam_model->getDashboardStats();
        $data['exams'] = $this->scholarshipexam_model->getExams();
        $data['sch_setting'] = $this->sch_setting_detail;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/scholarshipexam/index', $data);
        $this->load->view('layout/footer', $data);
    }

    public function exams()
    {
        $this->session->set_userdata('top_menu', 'scholarship_exam');
        $this->session->set_userdata('sub_menu', 'scholarshipexam_exams');

        $data['exams'] = $this->scholarshipexam_model->getExams();
        $data['classList'] = $this->class_model->get();
        $data['sch_setting'] = $this->sch_setting_detail;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/scholarshipexam/exams', $data);
        $this->load->view('layout/footer', $data);
    }

    public function save_exam()
    {
        if (!$this->rbac->hasPrivilege('scholarship_exam', 'can_add') && !$this->rbac->hasPrivilege('scholarship_exam', 'can_edit')) {
            access_denied();
        }

        $this->form_validation->set_rules('title', $this->lang->line('title') ?: 'Exam Title', 'trim|required|xss_clean');
        $this->form_validation->set_rules('exam_code', 'Exam Code', 'trim|required|xss_clean');
        $this->form_validation->set_rules('roll_no_prefix', 'Roll No Prefix', 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'title' => form_error('title'),
                'exam_code' => form_error('exam_code'),
                'roll_no_prefix' => form_error('roll_no_prefix')
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $id = $this->input->post('id');
            $data = array(
                'id' => $id ? $id : null,
                'title' => $this->input->post('title'),
                'exam_code' => $this->input->post('exam_code'),
                'exam_category' => $this->input->post('exam_category') ?: 'Scholarship/Olympiad',
                'exam_mode' => $this->input->post('exam_mode') ?: 'offline',
                'roll_no_prefix' => $this->input->post('roll_no_prefix') ?: 'OLY-',
                'is_paid' => $this->input->post('is_paid') ? 1 : 0,
                'registration_fee' => $this->input->post('registration_fee') ? floatval($this->input->post('registration_fee')) : 0.00,
                'description' => $this->input->post('description'),
                'exam_center' => $this->input->post('exam_center') ?: 'Main School Campus',
                'instructions' => $this->input->post('instructions'),
                'status' => $this->input->post('status') ? 1 : 0
            );

            $phase_names = $this->input->post('phase_name');
            $class_selection = $this->input->post('class_ids');
            $schedules = array();

            if (!empty($phase_names)) {
                foreach ($phase_names as $idx => $phase_name) {
                    $selected_classes = isset($class_selection[$idx]) ? $class_selection[$idx] : array();
                    if (is_array($selected_classes)) {
                        $class_ids_str = implode(',', array_filter($selected_classes));
                        $primary_class_id = !empty($selected_classes) ? $selected_classes[0] : 0;
                    } else {
                        $class_ids_str = $selected_classes;
                        $primary_class_id = $selected_classes ?: 0;
                    }

                    if (!empty($class_ids_str) || !empty($phase_name)) {
                        $schedules[] = array(
                            'phase_name' => $phase_name ?: 'Phase 1',
                            'class_id' => $primary_class_id,
                            'class_ids' => $class_ids_str,
                            'section_id' => null,
                            'registration_start_date' => isset($_POST['registration_start_date'][$idx]) && $_POST['registration_start_date'][$idx] ? date('Y-m-d', strtotime($_POST['registration_start_date'][$idx])) : null,
                            'registration_close_date' => isset($_POST['registration_close_date'][$idx]) && $_POST['registration_close_date'][$idx] ? date('Y-m-d', strtotime($_POST['registration_close_date'][$idx])) : null,
                            'admit_card_release_date' => isset($_POST['admit_card_release_date'][$idx]) && $_POST['admit_card_release_date'][$idx] ? date('Y-m-d', strtotime($_POST['admit_card_release_date'][$idx])) : null,
                            'exam_date' => isset($_POST['exam_date'][$idx]) && $_POST['exam_date'][$idx] ? date('Y-m-d H:i:s', strtotime($_POST['exam_date'][$idx])) : null,
                            'duration_minutes' => isset($_POST['duration_minutes'][$idx]) ? intval($_POST['duration_minutes'][$idx]) : 60,
                            'total_marks' => isset($_POST['total_marks'][$idx]) ? floatval($_POST['total_marks'][$idx]) : 100.00,
                            'passing_marks' => isset($_POST['passing_marks'][$idx]) ? floatval($_POST['passing_marks'][$idx]) : 40.00,
                            'result_date' => isset($_POST['result_date'][$idx]) && $_POST['result_date'][$idx] ? date('Y-m-d', strtotime($_POST['result_date'][$idx])) : null,
                            'award_ceremony_date' => isset($_POST['award_ceremony_date'][$idx]) && $_POST['award_ceremony_date'][$idx] ? date('Y-m-d', strtotime($_POST['award_ceremony_date'][$idx])) : null
                        );
                    }
                }
            }

            $res = $this->scholarshipexam_model->addExam($data, $schedules);
            if ($res) {
                $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message') ?: 'Exam saved successfully.');
            } else {
                $array = array('status' => 'fail', 'error' => array('db' => 'Database error'), 'message' => 'Failed to save exam.');
            }
        }
        echo json_encode($array);
    }

    public function delete_exam($id)
    {
        if (!$this->rbac->hasPrivilege('scholarship_exam', 'can_delete')) {
            access_denied();
        }
        $this->scholarshipexam_model->deleteExam($id);
        redirect('admin/scholarshipexam/exams');
    }

    public function get_exam_details($id)
    {
        $exam = $this->scholarshipexam_model->getExams($id);
        echo json_encode($exam);
    }

    public function toggle_exam_status($id)
    {
        if (!$this->rbac->hasPrivilege('scholarship_exam', 'can_edit')) {
            access_denied();
        }
        $this->scholarshipexam_model->toggleExamStatus($id);
        redirect('admin/scholarshipexam/exams');
    }

    public function toggle_registration_status($id)
    {
        if (!$this->rbac->hasPrivilege('scholarship_exam', 'can_edit')) {
            access_denied();
        }
        $this->scholarshipexam_model->toggleRegistrationStatus($id);
        redirect('admin/scholarshipexam/exams');
    }

    public function toggle_admit_card_status($id)
    {
        if (!$this->rbac->hasPrivilege('scholarship_exam', 'can_edit')) {
            access_denied();
        }
        $this->scholarshipexam_model->toggleAdmitCardStatus($id);
        redirect('admin/scholarshipexam/exams');
    }

    public function toggle_result_status($id)
    {
        if (!$this->rbac->hasPrivilege('scholarship_exam', 'can_edit')) {
            access_denied();
        }
        $this->scholarshipexam_model->toggleResultStatus($id);
        redirect('admin/scholarshipexam/exams');
    }

    /* ================= Question Bank & Paper Creation ================= */

    public function questions()
    {
        $this->session->set_userdata('top_menu', 'scholarship_exam');
        $this->session->set_userdata('sub_menu', 'scholarshipexam_questions');

        $data['exams'] = $this->scholarshipexam_model->getExams();
        $exam_id = $this->input->get('exam_id') ?: ($data['exams'] ? $data['exams'][0]['id'] : null);
        $subject_id = $this->input->get('subject_id');
        $class_ids = $this->input->get('class_id');

        $data['selected_exam_id'] = $exam_id;
        $data['selected_subject_id'] = $subject_id;
        $data['selected_class_ids'] = $class_ids;
        $data['subjects'] = $this->subject_model->get();
        $data['classList'] = $this->class_model->get();

        if ($exam_id) {
            $data['selected_exam'] = $this->scholarshipexam_model->getExams($exam_id);
            $data['exam_questions'] = $this->scholarshipexam_model->getQuestionsForExam($exam_id);
            $data['available_questions'] = $this->scholarshipexam_model->getAvailableQuestions($subject_id, $class_ids, $this->input->get('keyword'));
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/scholarshipexam/questions', $data);
        $this->load->view('layout/footer', $data);
    }

    public function add_question()
    {
        if (!$this->rbac->hasPrivilege('scholarship_exam', 'can_add')) {
            access_denied();
        }

        $exam_id = $this->input->post('exam_id');
        $question_id = $this->input->post('question_id');
        $marks = $this->input->post('marks') ? floatval($this->input->post('marks')) : 1.00;
        $neg_marks = $this->input->post('neg_marks') ? floatval($this->input->post('neg_marks')) : 0.00;

        if ($exam_id && $question_id) {
            $this->scholarshipexam_model->addQuestionToExam($exam_id, $question_id, $marks, $neg_marks);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Question added to paper successfully.</div>');
        }
        redirect('admin/scholarshipexam/questions?exam_id=' . $exam_id);
    }

    public function remove_question()
    {
        if (!$this->rbac->hasPrivilege('scholarship_exam', 'can_delete')) {
            access_denied();
        }

        $exam_id = $this->input->get('exam_id');
        $question_id = $this->input->get('question_id');

        if ($exam_id && $question_id) {
            $this->scholarshipexam_model->removeQuestionFromExam($exam_id, $question_id);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Question removed from paper.</div>');
        }
        redirect('admin/scholarshipexam/questions?exam_id=' . $exam_id);
    }

    public function print_paper($exam_id)
    {
        $data['exam'] = $this->scholarshipexam_model->getExams($exam_id);
        $data['questions'] = $this->scholarshipexam_model->getQuestionsForExam($exam_id);
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('admin/scholarshipexam/paper_print', $data);
    }

    public function candidates()
    {
        $this->session->set_userdata('top_menu', 'scholarship_exam');
        $this->session->set_userdata('sub_menu', 'scholarshipexam_candidates');

        $data['exams'] = $this->scholarshipexam_model->getExams();
        $data['classList'] = $this->class_model->get();

        $exam_id = $this->input->get('exam_id') ?: ($data['exams'] ? $data['exams'][0]['id'] : null);
        $class_id = $this->input->get('class_id');
        $section_id = $this->input->get('section_id');

        $data['selected_exam_id'] = $exam_id;
        $data['selected_class_id'] = $class_id;
        $data['selected_section_id'] = $section_id;

        $candidate_type = $this->input->get('candidate_type');
        $data['selected_candidate_type'] = $candidate_type;

        if ($exam_id) {
            $data['schedules'] = $this->scholarshipexam_model->getSchedulesByExam($exam_id);
            $data['registered_candidates'] = $this->scholarshipexam_model->getCandidatesList($exam_id, $class_id, $section_id, $candidate_type);
            if ($class_id) {
                $data['eligible_students'] = $this->scholarshipexam_model->getEligibleStudentsForExam($exam_id, $class_id, $section_id);
            }
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/scholarshipexam/candidates', $data);
        $this->load->view('layout/footer', $data);
    }

    public function register_candidates()
    {
        if (!$this->rbac->hasPrivilege('scholarship_exam', 'can_add')) {
            access_denied();
        }

        $exam_id = $this->input->post('exam_id');
        $schedule_id = $this->input->post('schedule_id');
        $student_session_ids = $this->input->post('student_session_ids');
        $prefix = $this->input->post('roll_prefix') ?: 'OLY-';

        if ($exam_id && !empty($student_session_ids)) {
            $res = $this->scholarshipexam_model->registerCandidatesBatch($exam_id, $schedule_id, $student_session_ids, $prefix);
            if ($res) {
                $this->session->set_flashdata('msg', '<div class="alert alert-success">Candidates registered successfully with auto-generated Roll Numbers.</div>');
            } else {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger">Failed to register candidates.</div>');
            }
        }
        redirect('admin/scholarshipexam/candidates?exam_id=' . $exam_id);
    }

    public function admitcard($candidate_id)
    {
        $data['candidate'] = $this->scholarshipexam_model->getCandidateAdmitCardData($candidate_id);
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('admin/scholarshipexam/admitcard', $data);
    }

    public function marks()
    {
        $this->session->set_userdata('top_menu', 'scholarship_exam');
        $this->session->set_userdata('sub_menu', 'scholarshipexam_marks');

        $data['exams'] = $this->scholarshipexam_model->getExams();
        $data['classList'] = $this->class_model->get();

        $exam_id = $this->input->get('exam_id') ?: ($data['exams'] ? $data['exams'][0]['id'] : null);
        $class_id = $this->input->get('class_id');

        $data['selected_exam_id'] = $exam_id;
        $data['selected_class_id'] = $class_id;

        if ($exam_id) {
            $data['candidates'] = $this->scholarshipexam_model->getCandidatesList($exam_id, $class_id);
            $data['selected_exam'] = $this->scholarshipexam_model->getExams($exam_id);
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/scholarshipexam/marks', $data);
        $this->load->view('layout/footer', $data);
    }

    public function save_marks()
    {
        if (!$this->rbac->hasPrivilege('scholarship_exam', 'can_edit')) {
            access_denied();
        }

        $exam_id = $this->input->post('exam_id');
        $marks_data = $this->input->post('marks');

        if ($exam_id && !empty($marks_data)) {
            $res = $this->scholarshipexam_model->saveMarksBatch($exam_id, $marks_data);
            if ($res) {
                $this->session->set_flashdata('msg', '<div class="alert alert-success">Marks saved and Ranks updated successfully!</div>');
            } else {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger">Failed to update marks.</div>');
            }
        }
        redirect('admin/scholarshipexam/marks?exam_id=' . $exam_id);
    }

    public function certificates()
    {
        $this->session->set_userdata('top_menu', 'scholarship_exam');
        $this->session->set_userdata('sub_menu', 'scholarshipexam_certificates');

        $data['exams'] = $this->scholarshipexam_model->getExams();
        $exam_id = $this->input->get('exam_id') ?: ($data['exams'] ? $data['exams'][0]['id'] : null);

        $data['selected_exam_id'] = $exam_id;
        if ($exam_id) {
            $data['candidates'] = $this->scholarshipexam_model->getCandidatesList($exam_id);
            $data['selected_exam'] = $this->scholarshipexam_model->getExams($exam_id);
        }

        $data['sch_setting'] = $this->sch_setting_detail;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/scholarshipexam/certificates', $data);
        $this->load->view('layout/footer', $data);
    }

    public function report()
    {
        $this->session->set_userdata('top_menu', 'scholarship_exam');
        $this->session->set_userdata('sub_menu', 'scholarshipexam_report');

        $data['exams'] = $this->scholarshipexam_model->getExams();
        $exam_id = $this->input->get('exam_id') ?: ($data['exams'] ? $data['exams'][0]['id'] : null);
        $class_id = $this->input->get('class_id');

        $data['classList'] = $this->class_model->get();
        $data['selected_exam_id'] = $exam_id;
        $data['selected_class_id'] = $class_id;

        if ($exam_id) {
            $data['candidates'] = $this->scholarshipexam_model->getCandidatesList($exam_id, $class_id);
            $data['selected_exam'] = $this->scholarshipexam_model->getExams($exam_id);
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/scholarshipexam/report', $data);
        $this->load->view('layout/footer', $data);
    }

    public function print_certificate($candidate_id)
    {
        $data['candidate'] = $this->scholarshipexam_model->getCandidateAdmitCardData($candidate_id);
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('admin/scholarshipexam/print_certificate', $data);
    }

    public function setting()
    {
        if (!$this->rbac->hasPrivilege('scholarship_exam', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'scholarship_exam');
        $this->session->set_userdata('sub_menu', 'scholarshipexam_setting');

        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            if (!$this->rbac->hasPrivilege('scholarship_exam', 'can_edit')) {
                access_denied();
            }
            $fields = $this->input->post('fields');
            $this->scholarshipexam_model->saveFieldSettings($fields);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Registration Form Field Settings updated successfully!</div>');
            redirect('admin/scholarshipexam/setting');
        }

        $data['field_settings'] = $this->scholarshipexam_model->getFieldSettings();
        $this->load->view('layout/header', $data);
        $this->load->view('admin/scholarshipexam/setting', $data);
        $this->load->view('layout/footer', $data);
    }
}
