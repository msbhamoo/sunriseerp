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

        // Scan tracking: a long-lived visitor cookie tells repeat scans from new people,
        // and the scan id lets submit() credit the registration to this scan.
        $visitor_id = (isset($_COOKIE['ssat_vid']) && preg_match('/^[a-f0-9]{32}$/', $_COOKIE['ssat_vid'])) ? $_COOKIE['ssat_vid'] : bin2hex(random_bytes(16));
        $scan_id = $this->scholarshipexam_model->logQrScan($visitor_id, $this->input->ip_address(), isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '', $target);
        setcookie('ssat_vid', $visitor_id, time() + 365 * 86400, '/', '', false, true);
        setcookie('ssat_sid', (string) $scan_id, time() + 30 * 86400, '/', '', false, true);

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

    public function check_mobile()
    {
        $mobile = preg_replace('/\D/', '', (string) $this->input->post('mobile'));
        $exam_id = (int) $this->input->post('exam_id');

        if (strlen($mobile) > 10 && substr($mobile, 0, 2) === '91') {
            $mobile = substr($mobile, 2);
        } elseif (strlen($mobile) > 10) {
            $mobile = substr($mobile, -10);
        }

        if (strlen($mobile) != 10) {
            echo json_encode(array('status' => 'error', 'message' => 'Please enter a valid 10-digit mobile number.'));
            exit;
        }

        $candidates = $this->scholarshipexam_model->getCandidatesByMobile($exam_id ?: null, $mobile);

        if (!empty($candidates)) {
            $list = array();
            foreach ($candidates as $c) {
                $list[] = array(
                    'id' => $c['id'],
                    'roll_no' => $c['roll_no'],
                    'name' => !empty($c['external_candidate_name']) ? $c['external_candidate_name'] : $c['roll_no'],
                    'class_name' => !empty($c['class_name']) ? $c['class_name'] : 'Registered Class',
                    'has_pin' => !empty($c['password_pin']) ? true : false,
                );
            }
            echo json_encode(array(
                'status' => 'registered',
                'mobile' => $mobile,
                'count' => count($candidates),
                'candidates' => $list
            ));
        } else {
            echo json_encode(array(
                'status' => 'new',
                'mobile' => $mobile
            ));
        }
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

        $password_pin = trim((string) $this->input->post('password_pin'));
        if ($password_pin === '' || !preg_match('/^\d{4}$/', $password_pin)) {
            // Auto generate 4-digit PIN if not provided or invalid
            $password_pin = sprintf('%04d', rand(1000, 9999));
        }

        if (!$exam_id || $candidate_name === '' || strlen($mobile) != 10 || ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL))) {
            redirect('scholarshipregister/apply/' . $exam['exam_code']);
            return;
        }

        // Allow registering multiple children under the same mobile number (e.g. for parents)
        // Check duplicate by both mobile AND candidate name (same child)
        $existing = $this->db->get_where('scholarship_exam_candidates', array(
            'scholarship_exam_id' => $exam_id,
            'is_external' => 1,
            'external_mobile' => $mobile,
            'external_candidate_name' => $candidate_name
        ))->row_array();

        if ($existing) {
            $created = array('id' => $existing['id'], 'roll_no' => $existing['roll_no'], 'admit_card_no' => $existing['admit_card_no']);
            $school_name = $existing['external_school_name'];
            $candidate_name = $existing['external_candidate_name'];
            $class_id = (int) $existing['external_class_id'];
            $password_pin = !empty($existing['password_pin']) ? $existing['password_pin'] : $password_pin;
            $data['already_registered'] = true;
        } else {
            $insert_data = array(
                'scholarship_exam_id' => $exam_id,
                'student_id' => 0,
                'student_session_id' => 0,
                'password_pin' => $password_pin,
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
            if (!empty($_COOKIE['ssat_vid'])) {
                $this->scholarshipexam_model->markQrScanRegistered(
                    isset($_COOKIE['ssat_sid']) ? (int) $_COOKIE['ssat_sid'] : 0,
                    $_COOKIE['ssat_vid'],
                    $created['id'],
                    $exam_id
                );
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
            'password_pin' => $password_pin,
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

    /* ================= Candidate Portal ================= */

    public function portal()
    {
        $candidate_id = $this->session->userdata('scholarship_candidate_id');
        if ($candidate_id) {
            $this->dashboard();
            return;
        }

        $data['sch_setting'] = $this->scholarshipexam_model->getSchoolSetting();
        $data['exams'] = $this->scholarshipexam_model->getExams();
        $this->load->view('scholarshipregister/portal_login', $data);
    }

    public function portal_login()
    {
        $login_identifier = trim((string) $this->input->post('login_identifier'));
        $pin = trim((string) $this->input->post('password_pin'));
        $candidate_id = $this->input->post('candidate_id');

        if ((empty($login_identifier) && empty($candidate_id)) || empty($pin)) {
            $this->session->set_flashdata('error_msg', 'Please enter your Roll Number / Mobile and 4-digit PIN.');
            redirect('scholarshipregister/portal');
            return;
        }

        $candidate = $this->scholarshipexam_model->authenticateCandidate($login_identifier, $pin, $candidate_id);

        if ($candidate) {
            $this->session->set_userdata('scholarship_candidate_id', $candidate['id']);
            redirect('scholarshipregister/portal');
        } else {
            $this->session->set_flashdata('error_msg', 'Invalid Roll Number / Mobile or 4-digit Password PIN. Please try again.');
            redirect('scholarshipregister/portal');
        }
    }

    public function portal_login_ajax()
    {
        $login_identifier = trim((string) $this->input->post('login_identifier'));
        $pin = trim((string) $this->input->post('password_pin'));
        $candidate_id = $this->input->post('candidate_id');

        if ((empty($login_identifier) && empty($candidate_id)) || empty($pin)) {
            echo json_encode(array('status' => 'error', 'message' => 'Please enter the 4-digit PIN.'));
            return;
        }

        $candidate = $this->scholarshipexam_model->authenticateCandidate($login_identifier, $pin, $candidate_id);

        if ($candidate) {
            $this->session->set_userdata('scholarship_candidate_id', $candidate['id']);
            echo json_encode(array(
                'status' => 'success',
                'message' => 'Login successful! Redirecting...',
                'redirect_url' => site_url('scholarshipregister/portal')
            ));
        } else {
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Incorrect 4-digit PIN. Please try again.'
            ));
        }
    }

    public function portal_logout()
    {
        $this->session->unset_userdata('scholarship_candidate_id');
        redirect('scholarshipregister/portal');
    }

    public function dashboard()
    {
        $candidate_id = $this->session->userdata('scholarship_candidate_id');
        if (!$candidate_id) {
            redirect('scholarshipregister/portal');
            return;
        }

        $candidate = $this->scholarshipexam_model->getCandidateAdmitCardData($candidate_id);
        if (!$candidate) {
            $this->session->unset_userdata('scholarship_candidate_id');
            redirect('scholarshipregister/portal');
            return;
        }

        // Get full candidate row for external_class_id and student_session class_id
        $cand_raw = $this->db->get_where('scholarship_exam_candidates', array('id' => $candidate_id))->row_array();
        $class_id = null;
        if (!empty($cand_raw['external_class_id'])) {
            $class_id = $cand_raw['external_class_id'];
        } elseif (!empty($cand_raw['student_session_id'])) {
            $sess = $this->db->get_where('student_session', array('id' => $cand_raw['student_session_id']))->row_array();
            if ($sess) {
                $class_id = $sess['class_id'];
            }
        }

        $data['candidate'] = $candidate;
        $data['cand_raw'] = $cand_raw;
        $data['sch_setting'] = $this->scholarshipexam_model->getSchoolSetting();

        // Exam schedules & days remaining calculation
        $exam_date = !empty($candidate['exam_date']) ? $candidate['exam_date'] : null;
        $days_remaining = null;
        $hours_remaining = null;
        if ($exam_date) {
            $diff = strtotime($exam_date) - time();
            $days_remaining = max(0, (int) floor($diff / 86400));
            $hours_remaining = max(0, (int) floor(($diff % 86400) / 3600));
        }
        $data['exam_date'] = $exam_date;
        $data['days_remaining'] = $days_remaining;
        $data['hours_remaining'] = $hours_remaining;

        // Fetch study materials & sample papers for candidate's class
        $data['sample_papers'] = $this->scholarshipexam_model->getMaterials($candidate['scholarship_exam_id'], $class_id, 'sample_paper');
        $data['other_materials'] = $this->scholarshipexam_model->getMaterials($candidate['scholarship_exam_id'], $class_id);

        $this->load->view('scholarshipregister/portal_dashboard', $data);
    }
}
