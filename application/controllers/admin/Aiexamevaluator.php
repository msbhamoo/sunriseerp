<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Aiexamevaluator extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
        $this->load->library('Ai_vision_evaluator');
        $this->load->model(['class_model', 'section_model', 'student_model']);
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    /**
     * Main Teacher Evaluation Studio Dashboard
     */
    public function index()
    {
        if (!$this->rbac->hasPrivilege('examination', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'aiexam');
        $this->session->set_userdata('sub_menu', 'admin/aiexamevaluator');

        $this->load->model('subject_model');
        $data['classlist']       = $this->class_model->get();
        $data['subjectlist']     = $this->subject_model->get();
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['current_session'] = $this->setting_model->getCurrentSessionName();

        // Fetch list of saved/generated papers for selection
        $data['saved_papers']    = $this->get_available_papers_list();

        // Fetch recent evaluations
        $data['recent_evaluations'] = $this->get_recent_evaluations_list();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/aiexam/evaluator', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * AJAX Endpoint: Get sections for a given class
     */
    public function get_sections_ajax()
    {
        if (!$this->rbac->hasPrivilege('examination', 'can_view')) {
            echo json_encode(['status' => 'error', 'message' => 'Access Denied']);
            return;
        }

        $class_id = $this->input->post('class_id');
        if (empty($class_id)) {
            echo json_encode(['status' => 'error', 'message' => 'Class ID required']);
            return;
        }

        $sections = $this->section_model->getClassBySectionAll($class_id);
        echo json_encode(['status' => 'success', 'data' => $sections]);
    }

    /**
     * AJAX Endpoint: Get students by class & section
     */
    public function get_students_ajax()
    {
        if (!$this->rbac->hasPrivilege('examination', 'can_view')) {
            echo json_encode(['status' => 'error', 'message' => 'Access Denied']);
            return;
        }

        $class_id   = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');

        if (empty($class_id)) {
            echo json_encode(['status' => 'error', 'message' => 'Class is required']);
            return;
        }

        $current_session = $this->setting_model->getCurrentSession();
        $this->db->select('students.id, students.admission_no, students.roll_no, students.firstname, students.middlename, students.lastname, classes.class, sections.section');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'student_session.class_id = classes.id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->where('student_session.session_id', $current_session);
        $this->db->where('student_session.class_id', $class_id);
        if (!empty($section_id)) {
            $this->db->where('student_session.section_id', $section_id);
        }
        $this->db->where('students.is_active', 'yes');
        $this->db->order_by('students.firstname', 'ASC');

        $students = $this->db->get()->result_array();

        echo json_encode(['status' => 'success', 'data' => $students]);
    }

    /**
     * AJAX Endpoint: Upload Answer Sheets & Run Multimodal AI Evaluation
     */
    public function evaluate_answer_sheets_ajax()
    {
        if (!$this->rbac->hasPrivilege('examination', 'can_view')) {
            echo json_encode(['status' => 'error', 'message' => 'Access Denied']);
            return;
        }

        $paper_mode      = $this->input->post('paper_mode') ? $this->input->post('paper_mode') : 'saved';
        $paper_id        = $this->input->post('paper_id');
        $student_id      = $this->input->post('student_id');
        $class_id        = $this->input->post('class_id');
        $section_id      = $this->input->post('section_id');
        $custom_solution = $this->input->post('custom_solution');
        $custom_api_key  = $this->input->post('api_key');

        if (empty($student_id)) {
            echo json_encode(['status' => 'error', 'message' => 'Student selection is required.']);
            return;
        }

        $paper_data   = [];
        $paper_title  = 'Custom Physical Exam';
        $class_name   = 'Class';
        $subject_name = 'General';

        if ($paper_mode === 'custom') {
            $paper_title  = $this->input->post('custom_paper_title') ? trim($this->input->post('custom_paper_title')) : 'Custom Physical Exam';
            $max_marks    = $this->input->post('custom_max_marks') ? intval($this->input->post('custom_max_marks')) : 80;
            $subject_name = $this->input->post('custom_subject') ? trim($this->input->post('custom_subject')) : 'General';
            
            // Get class name if class_id provided
            if (!empty($class_id)) {
                $cls_row = $this->db->get_where('classes', ['id' => $class_id])->row_array();
                if ($cls_row) {
                    $class_name = $cls_row['class'];
                }
            }

            $paper_data = [
                'paper_title'     => $paper_title,
                'max_marks'       => $max_marks,
                'subject'         => $subject_name,
                'class_name'      => $class_name,
                'custom_solution' => $custom_solution,
                'sections'        => []
            ];

            // If question paper scan images are uploaded, add note to prompt
            if (!empty($_FILES['custom_paper_files']['name'][0])) {
                $custom_solution .= "\n[NOTE: Question Paper scan pages uploaded alongside answer copy.]";
            }

            // AUTO-SAVE into System Question Papers Register so teachers never have to re-upload for other students!
            $new_paper_record = [
                'session_id'       => $this->setting_model->getCurrentSession(),
                'paper_title'      => $paper_title,
                'class_name'       => $class_name,
                'subject_name'     => $subject_name,
                'chapter_topic'    => 'School Physical Exam',
                'total_marks'      => $max_marks,
                'difficulty_level' => 'Standard',
                'paper_json'       => json_encode($paper_data),
                'created_by'       => $this->customlib->getStaffID(),
                'created_at'       => date('Y-m-d H:i:s')
            ];
            $this->db->insert('cbse_ai_generated_papers', $new_paper_record);
            $paper_id = $this->db->insert_id();
        } else {
            if (empty($paper_id)) {
                echo json_encode(['status' => 'error', 'message' => 'Please select a Question Paper.']);
                return;
            }

            // Fetch Question Paper Details
            $paper_row = $this->db->get_where('cbse_ai_generated_papers', ['id' => $paper_id])->row_array();
            if (!$paper_row || empty($paper_row['paper_json'])) {
                echo json_encode(['status' => 'error', 'message' => 'Selected question paper data not found.']);
                return;
            }
            $paper_data   = json_decode($paper_row['paper_json'], true);
            $paper_title  = $paper_row['paper_title'];
            $class_name   = $paper_row['class_name'];
            $subject_name = $paper_row['subject_name'];
        }

        // Fetch Student Details
        $student_row = $this->db->get_where('students', ['id' => $student_id])->row_array();
        $student_name = $student_row ? trim($student_row['firstname'] . ' ' . $student_row['lastname']) : 'Student';

        // Process Uploaded Answer Sheet Images
        $upload_dir = FCPATH . 'uploads/ai_evaluations/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $uploaded_files = [];
        $uploaded_paths = [];

        // 1. Process Answer Sheets
        if (!empty($_FILES['answer_sheets']['name'][0])) {
            $file_count = count($_FILES['answer_sheets']['name']);
            for ($i = 0; $i < $file_count; $i++) {
                if ($_FILES['answer_sheets']['error'][$i] === UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES['answer_sheets']['tmp_name'][$i];
                    $orig_name = $_FILES['answer_sheets']['name'][$i];
                    $ext = strtolower(pathinfo($orig_name, PATHINFO_EXTENSION));

                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                        $new_filename = 'ans_' . time() . '_' . $student_id . '_' . ($i + 1) . '.' . $ext;
                        $dest_path = $upload_dir . $new_filename;

                        if (move_uploaded_file($tmp_name, $dest_path)) {
                            $uploaded_paths[] = $dest_path;
                            $uploaded_files[] = 'uploads/ai_evaluations/' . $new_filename;
                        }
                    }
                }
            }
        }

        if (empty($uploaded_paths)) {
            echo json_encode(['status' => 'error', 'message' => 'Please upload at least 1 valid answer sheet image (JPG/PNG).']);
            return;
        }

        // Call Vision Evaluator Library
        $eval_params = [
            'paper_data'      => $paper_data,
            'image_paths'     => $uploaded_paths,
            'custom_solution' => $custom_solution,
            'student_name'    => $student_name,
            'class_name'      => $class_name,
            'subject_name'    => $subject_name,
            'api_key'         => $custom_api_key
        ];

        $eval_result = $this->ai_vision_evaluator->evaluate_submission($eval_params);

        if ($eval_result['status'] !== 'success') {
            echo json_encode($eval_result);
            return;
        }

        $eval_data = $eval_result['data'];
        $current_session = $this->setting_model->getCurrentSessionName();

        // Calculate and Save into Database
        $total_max = isset($eval_data['total_max_marks']) ? floatval($eval_data['total_max_marks']) : floatval($paper_row['total_marks']);
        $total_obtained = isset($eval_data['total_obtained_marks']) ? floatval($eval_data['total_obtained_marks']) : 0.0;
        $avg_conf = isset($eval_data['average_confidence']) ? intval($eval_data['average_confidence']) : 90;

        $record = [
            'paper_id'             => $paper_id,
            'student_id'           => $student_id,
            'class_id'             => $class_id,
            'section_id'           => !empty($section_id) ? $section_id : null,
            'exam_session'         => $current_session,
            'uploaded_pages_json'  => json_encode($uploaded_files),
            'custom_solution_url'  => !empty($custom_solution) ? $custom_solution : null,
            'evaluation_json'      => json_encode($eval_data),
            'total_max_marks'      => $total_max,
            'total_obtained_marks' => $total_obtained,
            'average_confidence'   => $avg_conf,
            'status'               => 'pending',
            'evaluated_by'         => $this->customlib->getStaffID(),
            'created_at'           => date('Y-m-d H:i:s')
        ];

        $this->db->insert('cbse_ai_answer_evaluations', $record);
        $insert_id = $this->db->insert_id();

        echo json_encode([
            'status'        => 'success',
            'evaluation_id' => $insert_id,
            'data'          => $eval_data,
            'uploaded_urls' => $uploaded_files,
            'student_info'  => [
                'name'         => $student_name,
                'admission_no' => isset($student_row['admission_no']) ? $student_row['admission_no'] : '',
                'roll_no'      => isset($student_row['roll_no']) ? $student_row['roll_no'] : ''
            ]
        ]);
    }

    /**
     * AJAX Endpoint: Save Teacher's Final Score Adjustments
     */
    public function save_verified_evaluation_ajax()
    {
        if (!$this->rbac->hasPrivilege('examination', 'can_view')) {
            echo json_encode(['status' => 'error', 'message' => 'Access Denied']);
            return;
        }

        $evaluation_id  = $this->input->post('evaluation_id');
        $eval_json      = $this->input->post('evaluation_json');
        $total_obtained = $this->input->post('total_obtained_marks');
        $status         = $this->input->post('status'); // 'reviewed' or 'published'

        if (empty($evaluation_id) || empty($eval_json)) {
            echo json_encode(['status' => 'error', 'message' => 'Missing evaluation ID or data payload.']);
            return;
        }

        $this->db->where('id', $evaluation_id);
        $this->db->update('cbse_ai_answer_evaluations', [
            'evaluation_json'      => $eval_json,
            'total_obtained_marks' => floatval($total_obtained),
            'status'               => !empty($status) ? $status : 'reviewed',
            'updated_at'           => date('Y-m-d H:i:s')
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Evaluation verified and saved successfully!']);
    }

    /**
     * AJAX Endpoint: Get specific saved evaluation by ID
     */
    public function get_evaluation_ajax()
    {
        if (!$this->rbac->hasPrivilege('examination', 'can_view')) {
            echo json_encode(['status' => 'error', 'message' => 'Access Denied']);
            return;
        }

        $evaluation_id = $this->input->post('evaluation_id');
        $row = $this->db->get_where('cbse_ai_answer_evaluations', ['id' => $evaluation_id])->row_array();

        if ($row) {
            $eval_data = json_decode($row['evaluation_json'], true);
            $pages = json_decode($row['uploaded_pages_json'], true);
            
            $student = $this->db->get_where('students', ['id' => $row['student_id']])->row_array();
            $paper   = $this->db->get_where('cbse_ai_generated_papers', ['id' => $row['paper_id']])->row_array();

            echo json_encode([
                'status'         => 'success',
                'evaluation_row' => $row,
                'data'           => $eval_data,
                'uploaded_urls'  => $pages,
                'student_info'   => $student,
                'paper_info'     => $paper
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Evaluation record not found.']);
        }
    }

    /**
     * Helper: List of available question papers
     */
    private function get_available_papers_list()
    {
        $this->db->select('id, paper_title, class_name, subject_name, total_marks, created_at');
        $this->db->from('cbse_ai_generated_papers');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(50);
        return $this->db->get()->result_array();
    }

    /**
     * Helper: List of recent evaluations
     */
    private function get_recent_evaluations_list()
    {
        $this->db->select('cbse_ai_answer_evaluations.*, students.firstname, students.lastname, students.admission_no, students.roll_no, cbse_ai_generated_papers.paper_title');
        $this->db->from('cbse_ai_answer_evaluations');
        $this->db->join('students', 'students.id = cbse_ai_answer_evaluations.student_id', 'left');
        $this->db->join('cbse_ai_generated_papers', 'cbse_ai_generated_papers.id = cbse_ai_answer_evaluations.paper_id', 'left');
        $this->db->order_by('cbse_ai_answer_evaluations.id', 'DESC');
        $this->db->limit(30);
        return $this->db->get()->result_array();
    }
}
