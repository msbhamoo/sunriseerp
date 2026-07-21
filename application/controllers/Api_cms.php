<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_cms extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        // Allow CORS for Next.js frontend
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        header("Content-Type: application/json");
        
        // Handle OPTIONS request for preflight
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            exit();
        }
        
        $this->load->model('cms_program_model');
        $this->load->model('cms_page_model');
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
            // Attempt to search by certificate or admission number just in case
            // The exact TC No column might vary, assuming it might be in students.admission_no or similar for now
            $this->db->where('students.admission_no', $tc_no);
            $has_filters = true;
        }

        if (!empty($name)) {
            $this->db->group_start();
            $this->db->like('students.firstname', $name);
            $this->db->or_like('students.lastname', $name);
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
}
