<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Scholarshipregister extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('scholarshipexam_model');
    }

    public function index($code_or_id = null)
    {
        $data['exams'] = $this->scholarshipexam_model->getExams();
        $data['classList'] = $this->scholarshipexam_model->getAllClasses();
        $data['sch_setting'] = $this->scholarshipexam_model->getSchoolSetting();
        $data['field_settings'] = $this->scholarshipexam_model->getFieldSettings();

        if ($code_or_id) {
            $exam = $this->scholarshipexam_model->getExamByCodeOrId($code_or_id);
            if ($exam) {
                $data['selected_exam'] = $exam;
                $data['selected_exam_id'] = $exam['id'];
                $data['is_direct_link'] = true;
            } else {
                $data['selected_exam_id'] = $data['exams'] ? $data['exams'][0]['id'] : null;
                $data['is_direct_link'] = false;
            }
        } else {
            $data['selected_exam_id'] = $data['exams'] ? $data['exams'][0]['id'] : null;
            $data['is_direct_link'] = false;
        }

        if (empty($data['selected_exam']) && $data['selected_exam_id']) {
            $data['selected_exam'] = $this->scholarshipexam_model->getExams($data['selected_exam_id']);
        }

        if (!empty($data['selected_exam'])) {
            $is_active = isset($data['selected_exam']['status']) ? $data['selected_exam']['status'] : 1;
            $reg_active = isset($data['selected_exam']['registration_status']) ? $data['selected_exam']['registration_status'] : 1;
            if ($is_active == 0 || $reg_active == 0) {
                $data['registration_closed'] = true;
            } else {
                $data['registration_closed'] = false;
            }
        }

        $this->load->view('scholarshipregister/index', $data);
    }

    public function apply($code_or_id = null)
    {
        $this->index($code_or_id);
    }

    public function submit()
    {
        $exam_id = $this->input->post('exam_id');
        $exam = $this->scholarshipexam_model->getExams($exam_id);

        if (!$exam || (isset($exam['status']) && $exam['status'] == 0) || (isset($exam['registration_status']) && $exam['registration_status'] == 0)) {
            redirect('scholarshipregister/apply/' . ($exam ? $exam['exam_code'] : ''));
            return;
        }

        $candidate_name = $this->input->post('candidate_name');
        $school_type = $this->input->post('school_type');
        $sch_setting = $this->scholarshipexam_model->getSchoolSetting();
        $default_school_name = isset($sch_setting[0]['name']) ? $sch_setting[0]['name'] : 'Sunrise International Public School';
        $school_name = ($school_type == 'external') ? ($this->input->post('other_school_name') ?: 'Other School') : $default_school_name;

        $mobile = $this->input->post('mobile');
        $email = $this->input->post('email');
        $class_id = $this->input->post('class_id');

        if ($exam_id && $candidate_name && $mobile) {
            $exam = $this->scholarshipexam_model->getExams($exam_id);
            $prefix = $exam['roll_no_prefix'] ?: 'EXT-';

            // Auto generate roll no for external candidate
            $this->db->select('roll_no');
            $this->db->from('scholarship_exam_candidates');
            $this->db->where('scholarship_exam_id', $exam_id);
            $this->db->like('roll_no', $prefix, 'after');
            $this->db->order_by('id', 'DESC');
            $this->db->limit(1);
            $last_cand = $this->db->get()->row();

            $start_counter = 1001;
            if ($last_cand && !empty($last_cand->roll_no)) {
                $num_str = preg_replace('/[^0-9]/', '', str_replace($prefix, '', $last_cand->roll_no));
                if (is_numeric($num_str) && intval($num_str) >= 1000) {
                    $start_counter = intval($num_str) + 1;
                }
            }

            $roll_no = $prefix . $start_counter;
            $admit_no = 'ADM-EXT-' . sprintf('%04d', rand(1000, 9999));
            $payment_status = ($exam['is_paid'] == 1) ? 'unpaid' : 'free';

            $insert_data = array(
                'scholarship_exam_id' => $exam_id,
                'student_id' => 0,
                'student_session_id' => 0,
                'roll_no' => $roll_no,
                'admit_card_no' => $admit_no,
                'payment_status' => $payment_status,
                'attendance_status' => 'pending',
                'result_status' => 'pending',
                'is_external' => 1,
                'external_candidate_name' => $candidate_name,
                'external_school_name' => $school_name,
                'external_mobile' => $mobile,
                'external_email' => $email
            );

            $this->db->insert('scholarship_exam_candidates', $insert_data);
            $cand_id = $this->db->insert_id();

            $data['candidate'] = array(
                'id' => $cand_id,
                'roll_no' => $roll_no,
                'admit_card_no' => $admit_no,
                'firstname' => $candidate_name,
                'lastname' => '(External Candidate)',
                'school_name' => $school_name,
                'mobile' => $mobile,
                'exam_title' => $exam['title'],
                'exam_code' => $exam['exam_code'],
                'exam_mode' => $exam['exam_mode'],
                'exam_center' => $exam['exam_center'],
                'instructions' => $exam['instructions'],
                'class' => 'External (' . $school_name . ')',
                'section' => 'A',
                'admission_no' => 'EXT-' . $cand_id,
                'father_name' => 'Guardian',
                'image' => '',
                'duration_minutes' => 60,
                'exam_date' => date('Y-m-d H:i:s', strtotime('+7 days'))
            );
            $data['sch_setting'] = $sch_setting;

            if (isset($exam['admit_card_status']) && $exam['admit_card_status'] == 1) {
                // Admit Cards ARE released -> Render Admit Card!
                $this->load->view('admin/scholarshipexam/admitcard', $data);
            } else {
                // Admit Cards NOT released yet -> Render Registration Success confirmation slip!
                $this->load->view('scholarshipregister/success', $data);
            }
        } else {
            redirect('scholarshipregister');
        }
    }

    public function check_status()
    {
        $search_term = trim($this->input->post('search_term') ?: $this->input->get('roll_no'));
        $data['sch_setting'] = $this->scholarshipexam_model->getSchoolSetting();

        if ($search_term) {
            $this->db->select('scholarship_exam_candidates.*, scholarship_exams.admit_card_status');
            $this->db->from('scholarship_exam_candidates');
            $this->db->join('scholarship_exams', 'scholarship_exams.id = scholarship_exam_candidates.scholarship_exam_id', 'inner');
            $this->db->group_start();
            $this->db->where('scholarship_exam_candidates.roll_no', $search_term);
            $this->db->or_where('scholarship_exam_candidates.external_mobile', $search_term);
            $this->db->group_end();
            $cand = $this->db->get()->row_array();

            if ($cand) {
                $cand_data = $this->scholarshipexam_model->getCandidateAdmitCardData($cand['id']);
                $data['candidate'] = $cand_data;

                if ($cand['admit_card_status'] == 1) {
                    $this->load->view('admin/scholarshipexam/admitcard', $data);
                    return;
                } else {
                    $data['error'] = 'Admit Cards for this exam have not been released yet by the administration. Please check back later.';
                }
            } else {
                $data['error'] = 'No candidate registration found with Roll Number or Mobile: ' . htmlspecialchars($search_term);
            }
        }

        $this->load->view('scholarshipregister/lookup', $data);
    }
}
