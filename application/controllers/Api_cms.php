<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_cms extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        // Allow CORS for Next.js frontend
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding, X-Requested-With");
        header("Content-Type: application/json");
        
        // Handle OPTIONS request for preflight
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit();
        }
        
        $this->load->model('cms_program_model');
        $this->load->model('cms_page_model');
        $this->load->model('job_posting_model');
        $this->load->model('job_application_model');
        $this->load->model('scholarshipexam_model');
        $this->load->config('ci-blog');
    }

    // Get all Events
    public function events() {
        $event_content = $this->config->item('ci_front_event_content') ? $this->config->item('ci_front_event_content') : 'events';
        // The last parameter '1' is for $is_front to filter past events if needed, but let's just get all for now
        $events = $this->cms_program_model->getByCategory($event_content);
        echo json_encode(['status' => 'success', 'data' => $events]);
    }

    // Get all Notices
    public function notices() {
        $notice_content = $this->config->item('ci_front_notice_content') ? $this->config->item('ci_front_notice_content') : 'notice';
        $notices = $this->cms_program_model->getByCategory($notice_content);
        echo json_encode(['status' => 'success', 'data' => $notices]);
    }
    
    // Get all Pages / Blogs (Old)
    public function pages() {
        $pages = $this->cms_page_model->get();
        echo json_encode(['status' => 'success', 'data' => $pages]);
    }

    // Get all News (New approach for Blogs)
    public function news() {
        $news_content = $this->config->item('ci_front_news_content') ? $this->config->item('ci_front_news_content') : 'news';
        $news = $this->cms_program_model->getByCategory($news_content);
        echo json_encode(['status' => 'success', 'data' => $news]);
    }

    // Get Single News by Slug
    public function news_item($slug) {
        $news_item = $this->cms_program_model->getBySlug(urldecode($slug));
        if ($news_item) {
            echo json_encode(['status' => 'success', 'data' => $news_item]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'News not found']);
        }
    }

    // Get Single Page / Blog by Slug (Old)
    public function page($slug) {
        $page = $this->cms_page_model->getBySlug(urldecode($slug));
        if ($page) {
            echo json_encode(['status' => 'success', 'data' => $page]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Page not found']);
        }
    }
    
    // Get all Gallery Items
    public function gallery() {
        $gallery_content = $this->config->item('ci_front_gallery_content') ? $this->config->item('ci_front_gallery_content') : 'gallery';
        $gallery = $this->cms_program_model->getByCategory($gallery_content);
        echo json_encode(['status' => 'success', 'data' => $gallery]);
    }

    // Get Single Gallery Item by Slug
    public function gallery_item($slug) {
        $gallery_item = $this->cms_program_model->getBySlug(urldecode($slug));
        if ($gallery_item) {
            echo json_encode(['status' => 'success', 'data' => $gallery_item]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gallery not found']);
        }
    }

    // Verify Transfer Certificate
    public function verify_tc() {
        // Read JSON POST payload (useful for Next.js fetch)
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $this->input->post();
        }
        
        $tc_no = isset($input['tcNumber']) ? $input['tcNumber'] : '';
        $name = isset($input['name']) ? trim($input['name']) : '';
        $dob = isset($input['dob']) ? trim($input['dob']) : '';
        $father_name = isset($input['fatherName']) ? trim($input['fatherName']) : '';

        // Start Query
        $this->db->select('students.firstname, students.lastname, students.father_name, students.dob, students.dis_reason, student_certificate_register.created_at as issue_date');
        $this->db->from('student_certificate_register');
        $this->db->join('students', 'students.id = student_certificate_register.student_id', 'left');
        
        $has_filters = false;
        
        if (!empty($tc_no)) {
            $this->db->group_start();
            $this->db->where('student_certificate_register.certificate_number', $tc_no);
            $this->db->or_where('students.admission_no', $tc_no);
            $this->db->group_end();
            $has_filters = true;
        }

        if (!empty($name)) {
            $this->db->group_start();
            $this->db->like('students.firstname', $name);
            $this->db->or_like('students.lastname', $name);
            $this->db->or_like("CONCAT(students.firstname, ' ', students.lastname)", $name);
            $this->db->group_end();
            $has_filters = true;
        }
        
        if (!empty($dob)) {
            $this->db->where('students.dob', $dob);
            $has_filters = true;
        }
        
        if (!empty($father_name)) {
            $this->db->like('students.father_name', $father_name);
            $has_filters = true;
        }

        if ($has_filters) {
            $query = $this->db->get();
            $result = $query->row_array();
            
            if ($result) {
                echo json_encode([
                    'status' => 'valid',
                    'data' => [
                        'tcNumber' => $tc_no ? $tc_no : 'Verified',
                        'studentName' => trim($result['firstname'] . ' ' . $result['lastname']),
                        'fatherName' => $result['father_name'],
                        'dob' => $result['dob'],
                        'issueDate' => date('Y-m-d', strtotime($result['issue_date'])),
                        'institution' => 'Sunrise Edu Group',
                        'leavingReason' => $result['dis_reason'] ? $result['dis_reason'] : 'Course Completed'
                    ]
                ]);
                return;
            }
        }
        
        // Mock fallback if nothing found in DB just for testing during dev
        if ($tc_no === 'TC-2026-101' || (strtolower($name) === 'rahul' && $dob === '2010-05-15')) {
            echo json_encode([
                'status' => 'valid',
                'data' => [
                    'tcNumber' => $tc_no ? $tc_no : 'TC-2026-101',
                    'studentName' => 'Rahul Kumar',
                    'fatherName' => 'Ramesh Kumar',
                    'dob' => '2010-05-15',
                    'issueDate' => '2026-03-31',
                    'institution' => 'Sunrise International Public School, Nechhwa',
                    'leavingReason' => 'Course Completed'
                ]
            ]);
            return;
        }

        echo json_encode(['status' => 'not_found']);
    }

    // Get Alumni Stories
    public function alumni_stories() {
        $this->db->select('alumni_students.*, students.firstname, students.lastname, students.image as student_image, alumni_stories.badge_text, alumni_stories.subtitle, alumni_stories.story_intro, alumni_stories.higher_edu_summary, alumni_stories.location_summary, alumni_stories.section1_title, alumni_stories.section1_content, alumni_stories.quote_text, alumni_stories.quote_author, alumni_stories.section2_title, alumni_stories.section2_content, alumni_stories.is_published');
        $this->db->from('alumni_students');
        $this->db->join('students', 'students.id = alumni_students.student_id', 'left');
        $this->db->join('alumni_stories', 'alumni_stories.student_id = alumni_students.student_id', 'left');
        $this->db->where('alumni_students.show_on_website', 1);
        
        $query = $this->db->get();
        $alumni_list = $query->result_array();
        
        $results = [];
        foreach ($alumni_list as $alumni) {
            $student_id = $alumni['student_id'];
            
            // Fetch education
            $this->db->select('*');
            $this->db->from('alumni_education');
            $this->db->where('student_id', $student_id);
            $this->db->order_by('id', 'asc');
            $edu_query = $this->db->get();
            $education = $edu_query->result_array();
            
            // Fetch work
            $this->db->select('*');
            $this->db->from('alumni_work_experience');
            $this->db->where('student_id', $student_id);
            $this->db->order_by('id', 'asc');
            $work_query = $this->db->get();
            $work = $work_query->result_array();
            
            $alumni['education'] = $education;
            $alumni['work_experience'] = $work;
            
            $results[] = $alumni;
        }

        // Fallback for Parmod Kumar if DB is empty
        if (empty($results)) {
            $results[] = [
                'student_id' => '0',
                'firstname' => 'Parmod',
                'lastname' => 'Kumar',
                'student_image' => null,
                'photo' => null,
                'current_email' => 'parmod@nextgenedulite.com',
                'current_phone' => '',
                'occupation' => 'Founder & CEO',
                'address' => 'London, UK',
                'badge_text' => 'Class of 2012',
                'subtitle' => 'From Nechhwa to London',
                'story_intro' => 'Parmod Kumar went to study MBA in UWTST London and then worked as a VISA & Compliance Officer before starting his own successful startup.',
                'higher_edu_summary' => 'MBA, UWTST London',
                'location_summary' => 'London, UK',
                'section1_title' => 'The Sunrise Foundation',
                'section1_content' => "Long before reaching the pinnacle of his career in the UK, Parmod spent his formative years at Sunrise Edu Group. It was here in the classrooms of Nechhwa that the foundation for global success was laid.\n\nThe faculty at Sunrise recognized his potential early on and encouraged him to dream beyond borders.",
                'quote_text' => '"Sunrise didn\'t just give me an education; they gave me the vision to see myself succeeding anywhere in the world."',
                'quote_author' => '— Parmod Kumar',
                'section2_title' => 'Going Above and Beyond',
                'section2_content' => "Parmod went to study MBA in UWTST London and proved his mettle internationally. He worked as a VISA & Compliance Officer in UWTST London, gaining invaluable global experience.\n\nToday, he runs his own startup named Next Gen Edulite, helping other students achieve their international education dreams.",
                'is_published' => '1',
                'education' => [
                    ['education_level' => 'Post Graduation', 'degree_name' => 'MBA', 'college_name' => 'UWTST London', 'passout_year' => '2016']
                ],
                'work_experience' => [
                    ['work_type' => 'Full Time', 'organization_name' => 'Next Gen Edulite', 'designation' => 'Founder & CEO', 'joining_date' => '2020-01-01', 'completion_date' => '', 'is_current' => '1', 'location' => 'London, UK'],
                    ['work_type' => 'Full Time', 'organization_name' => 'UWTST London', 'designation' => 'VISA & Compliance Officer', 'joining_date' => '2016-01-01', 'completion_date' => '2020-01-01', 'is_current' => '0', 'location' => 'London, UK']
                ]
            ];
        }
        
        echo json_encode(['status' => 'success', 'data' => $results]);
    }

    // Get Active & Open Job Postings for Website
    public function job_postings() {
        $this->db->select('job_postings.*, staff_designation.designation as designation_title');
        $this->db->from('job_postings');
        $this->db->join('staff_designation', 'staff_designation.id = job_postings.designation_id', 'left');
        $this->db->where('job_postings.is_active', 1);
        $this->db->where('job_postings.is_closed', 0);
        $this->db->order_by('job_postings.id', 'DESC');
        
        $query = $this->db->get();
        $jobs = $query->result_array();
        
        echo json_encode(['status' => 'success', 'data' => $jobs]);
    }

    // Get Single Job Posting and Increment View Count
    public function job_item($id) {
        if (empty($id)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid Job ID']);
            return;
        }

        // Increment view counter
        $this->job_posting_model->incrementViews($id);

        $this->db->select('job_postings.*, staff_designation.designation as designation_title');
        $this->db->from('job_postings');
        $this->db->join('staff_designation', 'staff_designation.id = job_postings.designation_id', 'left');
        $this->db->where('job_postings.id', $id);
        $this->db->where('job_postings.is_active', 1);
        $this->db->where('job_postings.is_closed', 0);

        $query = $this->db->get();
        $job = $query->row_array();

        if ($job) {
            echo json_encode(['status' => 'success', 'data' => $job]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Job posting not found or closed']);
        }
    }

    // Explicitly Increment Job View Count
    public function track_job_view() {
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $this->input->post();
        }

        $job_id = isset($input['job_id']) ? intval($input['job_id']) : 0;
        if ($job_id > 0) {
            $this->job_posting_model->incrementViews($job_id);
            echo json_encode(['status' => 'success', 'message' => 'View tracked']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid job_id']);
        }
    }

    // Submit Job Application
    public function submit_job_application() {
        // Read input from POST or JSON
        $job_id = $this->input->post('job_id');
        $name = trim($this->input->post('name'));
        $email = trim($this->input->post('email'));
        $phone = trim($this->input->post('phone'));
        $experience_years = trim($this->input->post('experience_years'));
        $qualification = trim($this->input->post('qualification'));
        $cover_letter = trim($this->input->post('cover_letter'));

        if (!$job_id || !$name || !$email || !$phone) {
            // Check if JSON payload was sent
            $input = json_decode(file_get_contents('php://input'), true);
            if ($input) {
                $job_id = isset($input['job_id']) ? $input['job_id'] : '';
                $name = isset($input['name']) ? trim($input['name']) : '';
                $email = isset($input['email']) ? trim($input['email']) : '';
                $phone = isset($input['phone']) ? trim($input['phone']) : '';
                $experience_years = isset($input['experience_years']) ? trim($input['experience_years']) : '';
                $qualification = isset($input['qualification']) ? trim($input['qualification']) : '';
                $cover_letter = isset($input['cover_letter']) ? trim($input['cover_letter']) : '';
            }
        }

        if (empty($job_id) || empty($name) || empty($email) || empty($phone)) {
            echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields (Name, Email, Phone).']);
            return;
        }

        // Verify Job exists and is open
        $job = $this->db->get_where('job_postings', ['id' => $job_id, 'is_active' => 1, 'is_closed' => 0])->row_array();
        if (!$job) {
            echo json_encode(['status' => 'error', 'message' => 'This job posting is no longer accepting applications.']);
            return;
        }

        // Handle Resume File Upload if present
        $resume_file_path = '';
        if (!empty($_FILES['resume']['name'])) {
            $upload_dir = './uploads/job_applications/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }

            $config['upload_path']   = $upload_dir;
            $config['allowed_types'] = 'pdf|doc|docx|jpg|png';
            $config['max_size']      = 10240; // 10MB
            $config['file_name']     = 'resume_' . time() . '_' . rand(1000, 9999);

            $this->load->library('upload', $config);
            if ($this->upload->do_upload('resume')) {
                $upload_data = $this->upload->data();
                $resume_file_path = 'uploads/job_applications/' . $upload_data['file_name'];
            } else {
                $upload_error = $this->upload->display_errors('', '');
                echo json_encode(['status' => 'error', 'message' => 'Resume upload failed: ' . $upload_error]);
                return;
            }
        }

        // Auto-generate Application Number
        $app_no = 'APP-' . date('Y') . '-' . sprintf('%04d', rand(1000, 9999));

        $data = [
            'job_id' => $job_id,
            'application_no' => $app_no,
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'experience_years' => $experience_years,
            'qualification' => $qualification,
            'cover_letter' => $cover_letter,
            'resume_file' => $resume_file_path,
            'stage' => 'Submitted'
        ];

        $insert_id = $this->job_application_model->add($data);

        if ($insert_id) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Your job application has been submitted successfully!',
                'application_no' => $app_no,
                'application_id' => $insert_id
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save application. Please try again.']);
        }
    }

    /* ================= Scholarship & Olympiad Exam APIs ================= */

    // Get the single active scholarship exam
    public function active_scholarship_exam() {
        $exam = $this->scholarshipexam_model->getActiveExam();
        if ($exam) {
            echo json_encode(['status' => 'success', 'data' => $exam]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No active scholarship exam found']);
        }
    }

    // Get all active scholarship exams
    public function scholarship_exams() {
        $exams = $this->scholarshipexam_model->getExams();
        $active_exams = array();
        if (!empty($exams)) {
            foreach ($exams as $e) {
                if (isset($e['status']) && $e['status'] == 1) {
                    $active_exams[] = $e;
                }
            }
        }
        echo json_encode(['status' => 'success', 'data' => $active_exams]);
    }

    // Get single scholarship exam details
    public function scholarship_exam_item($id = null) {
        if (!$id) {
            $id = $this->input->get('id');
        }
        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'Exam ID or Code required']);
            return;
        }

        $exam = $this->scholarshipexam_model->getExamByCodeOrId($id);
        if ($exam) {
            echo json_encode(['status' => 'success', 'data' => $exam]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Exam not found']);
        }
    }

    // Register external candidate for scholarship exam
    public function register_scholarship_candidate() {
        $raw_input = file_get_contents('php://input');
        $json_data = json_decode($raw_input, true);

        $exam_id = $this->input->post('exam_id') ?: ($json_data['exam_id'] ?? null);
        $name = $this->input->post('name') ?: ($json_data['name'] ?? null);
        $father_name = $this->input->post('father_name') ?: ($json_data['father_name'] ?? '');
        $mobile = $this->input->post('mobile') ?: ($json_data['mobile'] ?? null);
        $email = $this->input->post('email') ?: ($json_data['email'] ?? '');
        $school_name = $this->input->post('school_name') ?: ($json_data['school_name'] ?? '');
        $class_name = $this->input->post('class_name') ?: ($json_data['class_name'] ?? 'General');
        $test_mode = $this->input->post('test_mode') ?: ($json_data['test_mode'] ?? 'offline');

        if (!$name || !$mobile) {
            echo json_encode(['status' => 'error', 'message' => 'Student Name and Mobile Number are required.']);
            return;
        }

        // Default to first active exam if exam_id not supplied
        if (!$exam_id) {
            $all_exams = $this->scholarshipexam_model->getExams();
            if (!empty($all_exams)) {
                $exam_id = $all_exams[0]['id'];
            } else {
                $exam_id = 1;
            }
        }

        $exam = $this->scholarshipexam_model->getExams($exam_id);
        $prefix = ($exam && !empty($exam['roll_no_prefix'])) ? $exam['roll_no_prefix'] : 'OLY-';

        // Find highest existing roll number
        $this->db->select('roll_no');
        $this->db->from('scholarship_exam_candidates');
        $this->db->where('scholarship_exam_id', $exam_id);
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
        $admit_card_no = 'ADM-' . str_replace('-', '', $prefix) . '-' . sprintf('%04d', rand(1000, 9999));
        $schedule_id = (!empty($exam['schedules']) && isset($exam['schedules'][0]['id'])) ? $exam['schedules'][0]['id'] : null;

        $candidate_data = array(
            'scholarship_exam_id' => $exam_id,
            'schedule_id' => $schedule_id,
            'student_id' => 0,
            'student_session_id' => 0,
            'roll_no' => $roll_no,
            'admit_card_no' => $admit_card_no,
            'is_external' => 1,
            'external_candidate_name' => $name,
            'external_school_name' => $school_name . ($class_name ? ' (' . $class_name . ')' : ''),
            'external_mobile' => $mobile,
            'external_email' => $email,
            'payment_status' => ($exam && isset($exam['is_paid']) && $exam['is_paid'] == 1) ? 'unpaid' : 'free',
            'attendance_status' => 'pending',
            'result_status' => 'pending',
            'remarks' => 'Mode: ' . $test_mode . ' | Father: ' . $father_name
        );

        $this->db->insert('scholarship_exam_candidates', $candidate_data);
        $insert_id = $this->db->insert_id();

        if ($insert_id) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Candidate registered successfully for ' . ($exam['title'] ?? 'Scholarship Exam') . '!',
                'registration_no' => $roll_no,
                'admit_card_no' => $admit_card_no,
                'candidate_id' => $insert_id
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to complete registration. Please try again.']);
        }
    }

    // Search scholarship exam results
    public function scholarship_result_search() {
        $query_str = $this->input->get('query') ?: $this->input->post('query');
        if (!$query_str) {
            $raw_input = file_get_contents('php://input');
            $json_data = json_decode($raw_input, true);
            $query_str = $json_data['query'] ?? null;
        }

        if (!$query_str) {
            echo json_encode(['status' => 'error', 'message' => 'Please enter Roll Number, Admit Card No, or Mobile Number.']);
            return;
        }

        $this->db->select('scholarship_exam_candidates.*, scholarship_exams.title as exam_title, scholarship_exams.result_status_published');
        $this->db->from('scholarship_exam_candidates');
        $this->db->join('scholarship_exams', 'scholarship_exams.id = scholarship_exam_candidates.scholarship_exam_id', 'inner');
        $this->db->group_start();
        $this->db->where('scholarship_exam_candidates.roll_no', trim($query_str));
        $this->db->or_where('scholarship_exam_candidates.admit_card_no', trim($query_str));
        $this->db->or_where('scholarship_exam_candidates.external_mobile', trim($query_str));
        $this->db->group_end();
        
        $candidate = $this->db->get()->row_array();

        if ($candidate) {
            if ($candidate['result_status_published'] == 1 || $candidate['result_status'] != 'pending') {
                echo json_encode(['status' => 'success', 'data' => $candidate]);
            } else {
                echo json_encode(['status' => 'pending', 'message' => 'Exam result is under evaluation. Please check back after official announcement.', 'data' => $candidate]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No registration record found for ' . $query_str]);
        }
    }

    // Get CBSE Mandatory Disclosure data
    public function cbse_disclosure() {
        $this->load->model('cbse_disclosure_model');
        $raw_data = $this->cbse_disclosure_model->get_all_disclosures();
        
        $base_url = base_url();

        // Attach full URL for uploaded files
        foreach ($raw_data as $section_key => &$fields) {
            foreach ($fields as $field_key => &$field_val) {
                if (!empty($field_val['file_path'])) {
                    $field_val['full_file_url'] = $base_url . $field_val['file_path'];
                }
            }
        }

        echo json_encode(['status' => 'success', 'data' => $raw_data]);
    }
}
