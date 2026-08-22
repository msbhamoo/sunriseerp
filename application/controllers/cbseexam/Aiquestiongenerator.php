<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Aiquestiongenerator extends MY_Addon_CBSEController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
        $this->load->library('Ai_exam_generator');
        $this->load->model(['class_model', 'subject_model', 'question_model', 'cbseexam/cbseexam_exam_model', 'cbseexam/cbseexam_term_model']);
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    /**
     * Generator Main Studio View
     */
    public function index()
    {
        if (!$this->rbac->hasPrivilege('cbse_exam', 'can_view') || !$this->auth->addonchk('sscbse', false)) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'cbse_exam');
        $this->session->set_userdata('sub_menu', 'cbseexam/aiquestiongenerator');

        $data['classlist']       = $this->class_model->get();
        $data['subjectlist']     = $this->subject_model->get();
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['current_session'] = $this->setting_model->getCurrentSessionName();

        // Fetch recent papers history
        $data['recent_papers']   = $this->get_saved_papers_list();

        $this->load->view('layout/header', $data);
        $this->load->view('cbseexam/ai_question_generator/index', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * AJAX Endpoint: Generate CBSE Question Paper using AI & Auto-Save to History
     */
    public function generate_paper_ajax()
    {
        if (!$this->rbac->hasPrivilege('cbse_exam', 'can_view') || !$this->auth->addonchk('sscbse', false)) {
            echo json_encode(['status' => 'error', 'message' => 'Access Denied']);
            return;
        }

        $class_id     = $this->input->post('class_id');
        $subject_id   = $this->input->post('subject_id');
        $class_name   = $this->input->post('class_name');
        $subject_name = $this->input->post('subject_name');
        $chapter      = $this->input->post('chapter');
        $total_marks  = $this->input->post('total_marks');
        $difficulty   = $this->input->post('difficulty');
        $language     = $this->input->post('language');
        $api_engine   = $this->input->post('api_engine');
        $api_key      = $this->input->post('api_key');

        if (empty($class_name) || empty($subject_name)) {
            echo json_encode(['status' => 'error', 'message' => 'Class and Subject are required.']);
            return;
        }

        $blooms_taxonomy       = $this->input->post('blooms_taxonomy');
        $generate_multi_sets   = $this->input->post('generate_multi_sets');
        $question_distribution = $this->input->post('question_distribution');

        $current_session = $this->setting_model->getCurrentSessionName();

        $params = [
            'class_name'            => $class_name,
            'subject_name'          => $subject_name,
            'chapter'               => !empty($chapter) ? $chapter : 'Complete Syllabus',
            'total_marks'           => !empty($total_marks) ? $total_marks : 80,
            'difficulty'            => !empty($difficulty) ? $difficulty : 'Medium',
            'language'              => !empty($language) ? $language : 'English',
            'academic_session'      => !empty($current_session) ? $current_session : date('Y') . '-' . (date('y') + 1),
            'blooms_taxonomy'       => is_array($blooms_taxonomy) ? $blooms_taxonomy : null,
            'generate_multi_sets'   => $generate_multi_sets,
            'question_distribution' => is_array($question_distribution) ? $question_distribution : null,
            'api_engine'            => !empty($api_engine) ? $api_engine : 'gemini',
            'api_key'               => !empty($api_key) ? trim($api_key) : ''
        ];

        $result = $this->ai_exam_generator->generate_paper($params);

        // If generated successfully, automatically save into cbse_ai_generated_papers table
        if ($result['status'] === 'success' && !empty($result['data'])) {
            $paper_data = $result['data'];
            $paper_title = isset($paper_data['paper_title']) ? $paper_data['paper_title'] : "CBSE {$class_name} {$subject_name} Exam";
            
            $history_data = [
                'paper_title'  => $paper_title,
                'class_id'     => !empty($class_id) ? $class_id : null,
                'class_name'   => $class_name,
                'subject_id'   => !empty($subject_id) ? $subject_id : null,
                'subject_name' => $subject_name,
                'chapter'      => !empty($chapter) ? $chapter : 'Complete Syllabus',
                'total_marks'  => !empty($total_marks) ? intval($total_marks) : 80,
                'difficulty'   => !empty($difficulty) ? $difficulty : 'Medium',
                'language'     => !empty($language) ? $language : 'English',
                'paper_json'   => json_encode($paper_data),
                'created_by'   => $this->customlib->getStaffID(),
                'created_at'   => date('Y-m-d H:i:s')
            ];

            $this->db->insert('cbse_ai_generated_papers', $history_data);
            $result['saved_paper_id'] = $this->db->insert_id();
        }

        echo json_encode($result);
    }

    /**
     * AJAX Endpoint: Regenerate a Single Question in-place
     */
    public function regenerate_single_question_ajax()
    {
        if (!$this->rbac->hasPrivilege('cbse_exam', 'can_view') || !$this->auth->addonchk('sscbse', false)) {
            echo json_encode(['status' => 'error', 'message' => 'Access Denied']);
            return;
        }

        $params = [
            'class_name'    => $this->input->post('class_name'),
            'subject_name'  => $this->input->post('subject_name'),
            'chapter'       => $this->input->post('chapter'),
            'section_name'  => $this->input->post('section_name'),
            'question_type' => $this->input->post('question_type'),
            'marks'         => $this->input->post('marks'),
            'difficulty'    => $this->input->post('difficulty'),
            'language'      => $this->input->post('language'),
            'api_engine'    => $this->input->post('api_engine'),
            'api_key'       => $this->input->post('api_key')
        ];

        $result = $this->ai_exam_generator->regenerate_single_question($params);
        echo json_encode($result);
    }

    /**
     * AJAX Endpoint: Save User Edits to Archived Paper
     */
    public function save_edited_paper_ajax()
    {
        if (!$this->rbac->hasPrivilege('cbse_exam', 'can_view') || !$this->auth->addonchk('sscbse', false)) {
            echo json_encode(['status' => 'error', 'message' => 'Access Denied']);
            return;
        }

        $paper_id   = $this->input->post('paper_id');
        $paper_json = $this->input->post('paper_json');

        if (empty($paper_id) || empty($paper_json)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid parameters']);
            return;
        }

        $this->db->where('id', $paper_id);
        $this->db->update('cbse_ai_generated_papers', [
            'paper_json' => $paper_json,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Paper changes saved successfully!']);
    }

    /**
     * AJAX Endpoint: Get a previously saved paper by ID
     */
    public function get_saved_paper_ajax()
    {
        if (!$this->rbac->hasPrivilege('cbse_exam', 'can_view') || !$this->auth->addonchk('sscbse', false)) {
            echo json_encode(['status' => 'error', 'message' => 'Access Denied']);
            return;
        }

        $paper_id = $this->input->post('paper_id');
        if (empty($paper_id)) {
            echo json_encode(['status' => 'error', 'message' => 'Paper ID is required']);
            return;
        }

        $this->db->where('id', $paper_id);
        $row = $this->db->get('cbse_ai_generated_papers')->row_array();

        if ($row) {
            $paper_data = json_decode($row['paper_json'], true);
            echo json_encode([
                'status'     => 'success',
                'paper_info' => $row,
                'data'       => $paper_data
            ]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Paper not found.']);
        }
    }

    /**
     * AJAX Endpoint: Delete a saved paper from history
     */
    public function delete_saved_paper_ajax()
    {
        if (!$this->rbac->hasPrivilege('cbse_exam', 'can_view') || !$this->auth->addonchk('sscbse', false)) {
            echo json_encode(['status' => 'error', 'message' => 'Access Denied']);
            return;
        }

        $paper_id = $this->input->post('paper_id');
        if (!empty($paper_id)) {
            $this->db->where('id', $paper_id);
            $this->db->delete('cbse_ai_generated_papers');
            echo json_encode(['status' => 'success', 'message' => 'Paper deleted successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid Paper ID']);
        }
    }

    /**
     * AJAX Endpoint: Reload saved papers history list
     */
    public function get_saved_papers_list_ajax()
    {
        $list = $this->get_saved_papers_list();
        echo json_encode(['status' => 'success', 'list' => $list]);
    }

    /**
     * Helper: Fetch all saved papers
     */
    private function get_saved_papers_list()
    {
        $this->db->select('id, paper_title, class_name, subject_name, chapter, total_marks, difficulty, language, created_at');
        $this->db->from('cbse_ai_generated_papers');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(50);
        return $this->db->get()->result_array();
    }

    /**
     * AJAX Endpoint: Batch Save Generated Questions to LMS Question Bank
     */
    public function save_to_question_bank_ajax()
    {
        if (!$this->rbac->hasPrivilege('cbse_exam', 'can_view') || !$this->auth->addonchk('sscbse', false)) {
            echo json_encode(['status' => 'error', 'message' => 'Access Denied']);
            return;
        }

        $questions_json = $this->input->post('questions_payload');
        $class_id       = $this->input->post('class_id');
        $subject_id     = $this->input->post('subject_id');

        if (empty($questions_json) || empty($subject_id)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid parameters. Subject and questions are required.']);
            return;
        }

        $questions_data = json_decode($questions_json, true);
        if (!is_array($questions_data)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid question payload.']);
            return;
        }

        $staff_id = $this->customlib->getStaffID();
        $saved_count = 0;

        foreach ($questions_data as $q) {
            $q_type = isset($q['question_type']) ? $q['question_type'] : 'descriptive';
            $level  = isset($q['level']) ? $q['level'] : 'medium';

            $insert_data = [
                'subject_id'    => $subject_id,
                'class_id'      => !empty($class_id) ? $class_id : null,
                'question_type' => $q_type,
                'level'         => $level,
                'question'      => isset($q['question_text']) ? $q['question_text'] : '',
                'staff_id'      => $staff_id,
                'created_at'    => date('Y-m-d H:i:s')
            ];

            if ($q_type === 'singlechoice' || $q_type === 'multichoice') {
                $opts = isset($q['options']) ? $q['options'] : [];
                $insert_data['opt_a']   = isset($opts['A']) ? $opts['A'] : (isset($opts[0]) ? $opts[0] : '');
                $insert_data['opt_b']   = isset($opts['B']) ? $opts['B'] : (isset($opts[1]) ? $opts[1] : '');
                $insert_data['opt_c']   = isset($opts['C']) ? $opts['C'] : (isset($opts[2]) ? $opts[2] : '');
                $insert_data['opt_d']   = isset($opts['D']) ? $opts['D'] : (isset($opts[3]) ? $opts[3] : '');
                $insert_data['opt_e']   = isset($opts['E']) ? $opts['E'] : (isset($opts[4]) ? $opts[4] : '');
                $insert_data['correct'] = isset($q['correct_option']) ? strtolower($q['correct_option']) : 'opt_a';
                
                // Map 'A' -> 'opt_a'
                if (strlen($insert_data['correct']) === 1) {
                    $insert_data['correct'] = 'opt_' . strtolower($insert_data['correct']);
                }
            }

            $question_id = $this->question_model->add($insert_data);
            if ($question_id) {
                $saved_count++;
            }
        }

        echo json_encode([
            'status'      => 'success',
            'message'     => "$saved_count questions successfully saved to LMS Question Bank!",
            'saved_count' => $saved_count
        ]);
    }
}
