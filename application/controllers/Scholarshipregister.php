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

        $data['registered_count'] = !empty($data['selected_exam']['id']) ? $this->scholarshipexam_model->getOnlineRegistrationCount($data['selected_exam']['id']) : 0;

        $this->load->view('scholarshipregister/index', $data);
    }

    public function apply($code_or_id = null)
    {
        $this->index($code_or_id);
    }

    /**
     * Permanent short link (/ssat) that the printed QR points to. The QR never changes;
     * admin repoints where it lands from Scholarship Settings.
     */
    public function go()
    {
        $target = $this->scholarshipexam_model->getPublicLinkTarget();
        if ($target === '') {
            $exams = $this->scholarshipexam_model->getExams();
            $target = site_url('scholarshipregister/apply/' . ($exams ? urlencode($exams[0]['exam_code']) : ''));
        } elseif (!preg_match('#^https?://#i', $target)) {
            $target = site_url(ltrim($target, '/'));
        }
        redirect($target);
    }

    /** JSON for the public page: real registration count + recent (name-masked) sign-ups. */
    public function activity($code_or_id = null)
    {
        $exam = $code_or_id ? $this->scholarshipexam_model->getExamByCodeOrId($code_or_id) : null;
        if (!$exam) {
            $exams = $this->scholarshipexam_model->getExams();
            $exam = $exams ? $exams[0] : null;
        }
        $payload = array('count' => 0, 'recent' => array());
        if ($exam) {
            $payload['count'] = (int) $this->scholarshipexam_model->getOnlineRegistrationCount($exam['id']);
            $payload['recent'] = $this->scholarshipexam_model->getRecentOnlineRegistrations($exam['id']);
        }
        header('Content-Type: application/json');
        header('Cache-Control: no-store');
        echo json_encode($payload);
        exit;
    }

    public function submit()
    {
        $exam_id = $this->input->post('exam_id');
        $exam = $this->scholarshipexam_model->getExams($exam_id);

        if (!$exam || (isset($exam['status']) && $exam['status'] == 0) || (isset($exam['registration_status']) && $exam['registration_status'] == 0)) {
            redirect('scholarshipregister/apply/' . ($exam ? $exam['exam_code'] : ''));
            return;
        }

        $candidate_name = trim((string) $this->input->post('candidate_name'));
        $school_type = $this->input->post('school_type');
        $sch_setting = $this->scholarshipexam_model->getSchoolSetting();
        $default_school_name = isset($sch_setting[0]['name']) ? $sch_setting[0]['name'] : 'Sunrise International Public School';
        $school_name = ($school_type == 'external') ? (trim((string) $this->input->post('other_school_name')) ?: 'Other School') : $default_school_name;

        $mobile = preg_replace('/\D/', '', (string) $this->input->post('mobile'));
        if (strlen($mobile) > 10) {
            $mobile = substr($mobile, -10);
        }
        $email = trim((string) $this->input->post('email'));
        $class_id = (int) $this->input->post('class_id');

        $dob = trim((string) $this->input->post('dob'));
        $dob = ($dob !== '' && strtotime($dob)) ? date('Y-m-d', strtotime($dob)) : null;

        if (!$exam_id || $candidate_name === '' || strlen($mobile) != 10 || ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL))) {
            redirect('scholarshipregister/apply/' . $exam['exam_code']);
            return;
        }

        // One registration per mobile per exam: show the existing slip instead of creating a duplicate
        $existing = $this->scholarshipexam_model->findExternalDuplicate($exam_id, $mobile);
        if ($existing) {
            $created = array('id' => $existing['id'], 'roll_no' => $existing['roll_no'], 'admit_card_no' => $existing['admit_card_no']);
            $school_name = $existing['external_school_name'];
            $candidate_name = $existing['external_candidate_name'];
            $class_id = (int) $existing['external_class_id'];
            $data['already_registered'] = true;
        } else {
            $insert_data = array(
                'scholarship_exam_id' => $exam_id,
                'student_id' => 0,
                'student_session_id' => 0,
                'payment_status' => ($exam['is_paid'] == 1) ? 'unpaid' : 'free',
                'attendance_status' => 'pending',
                'result_status' => 'pending',
                'is_external' => 1,
                'external_candidate_name' => $candidate_name,
                'external_school_name' => $school_name,
                'external_mobile' => $mobile,
                'external_email' => $email,
                'external_class_id' => $class_id ?: null,
                'external_father_name' => trim((string) $this->input->post('father_name')),
                'external_mother_name' => trim((string) $this->input->post('mother_name')),
                'external_dob' => $dob,
                'external_gender' => trim((string) $this->input->post('gender')),
                'external_category' => trim((string) $this->input->post('category')),
                'external_village' => trim((string) $this->input->post('village')),
                'external_city' => trim((string) $this->input->post('city')),
                'external_address' => trim((string) $this->input->post('address')),
            );
            $created = $this->scholarshipexam_model->insertExternalCandidate($exam, $insert_data);
            if (!$created) {
                redirect('scholarshipregister/apply/' . $exam['exam_code']);
                return;
            }
        }

        $class_row = $class_id ? $this->db->get_where('classes', array('id' => $class_id))->row_array() : null;
        $class_label = $class_row ? 'Class ' . $class_row['class'] : '';

        $exam_date = null;
        foreach ($this->scholarshipexam_model->getSchedulesByExam($exam['id']) as $sc) {
            if (!empty($sc['exam_date']) && strtotime($sc['exam_date'])) {
                $exam_date = $sc['exam_date'];
                break;
            }
        }

        $data['candidate'] = array(
            'id' => $created['id'],
            'roll_no' => $created['roll_no'],
            'admit_card_no' => $created['admit_card_no'],
            'firstname' => $candidate_name,
            'lastname' => '(External Candidate)',
            'school_name' => $school_name,
            'mobile' => $mobile,
            'exam_title' => $exam['title'],
            'exam_code' => $exam['exam_code'],
            'exam_mode' => $exam['exam_mode'],
            'exam_center' => $exam['exam_center'],
            'instructions' => $exam['instructions'],
            'class' => trim($class_label . ' (' . $school_name . ')'),
            'section' => 'A',
            'admission_no' => 'EXT-' . $created['id'],
            'father_name' => $this->input->post('father_name') ?: 'Guardian',
            'image' => '',
            'duration_minutes' => 60,
            'exam_date' => $exam_date ?: date('Y-m-d H:i:s', strtotime('+7 days')),
            'class_label' => $class_label,
            'father_label' => trim((string) $this->input->post('father_name')),
            'registered_on' => date('Y-m-d H:i:s'),
        );
        $data['sch_setting'] = $sch_setting;

        if (isset($exam['admit_card_status']) && $exam['admit_card_status'] == 1) {
            $this->load->view('admin/scholarshipexam/admitcard', $data);
        } else {
            $this->load->view('scholarshipregister/success', $data);
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
