<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Aiexamgenerator extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
        $this->load->library('Ai_exam_generator');
        $this->load->model(['class_model', 'subject_model', 'question_model']);
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    /**
     * Generator Main Studio View
     */
    public function index()
    {
        if (!$this->rbac->hasPrivilege('examination', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'aiexam');
        $this->session->set_userdata('sub_menu', 'admin/aiexamgenerator');

        $data['classlist']       = $this->class_model->get();
        $data['subjectlist']     = $this->subject_model->get();
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['current_session'] = $this->setting_model->getCurrentSessionName();

        // Fetch recent papers history
        $data['recent_papers']   = $this->get_saved_papers_list();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/aiexam/generator', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * AJAX Endpoint: Generate Question Paper using AI & Auto-Save to History
     */
    public function generate_paper_ajax()
    {
        if (!$this->rbac->hasPrivilege('examination', 'can_view')) {
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
            // Ensure DB connection is active after external AI API call
            if (isset($this->db->conn_id) && $this->db->conn_id instanceof mysqli) {
                if (!@$this->db->conn_id->ping()) {
                    $this->db->reconnect();
                }
            } else {
                $this->db->reconnect();
            }

            $paper_data = $result['data'];
            $paper_title = isset($paper_data['paper_title']) ? $paper_data['paper_title'] : "Exam {$class_name} {$subject_name}";
            
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
                'paper_json'   => json_encode($paper_data, JSON_UNESCAPED_UNICODE),
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
        if (!$this->rbac->hasPrivilege('examination', 'can_view')) {
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
        if (!$this->rbac->hasPrivilege('examination', 'can_view')) {
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
        if (!$this->rbac->hasPrivilege('examination', 'can_view')) {
            echo json_encode(['status' => 'error', 'message' => 'Access Denied']);
            return;
        }

        $paper_id = $this->input->post('paper_id');
        if (empty($paper_id)) {
            echo json_encode(['status' => 'error', 'message' => 'Paper ID is required.']);
            return;
        }

        $paper = $this->db->get_where('cbse_ai_generated_papers', ['id' => $paper_id])->row_array();
        if ($paper && !empty($paper['paper_json'])) {
            $data = json_decode($paper['paper_json'], true);
            echo json_encode(['status' => 'success', 'data' => $data, 'paper_info' => $paper]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Paper not found.']);
        }
    }

    /**
     * AJAX Endpoint: Delete a saved paper
     */
    public function delete_saved_paper_ajax()
    {
        if (!$this->rbac->hasPrivilege('examination', 'can_view')) {
            echo json_encode(['status' => 'error', 'message' => 'Access Denied']);
            return;
        }

        $paper_id = $this->input->post('paper_id');
        if (empty($paper_id)) {
            echo json_encode(['status' => 'error', 'message' => 'Paper ID is required.']);
            return;
        }

        $this->db->where('id', $paper_id);
        $this->db->delete('cbse_ai_generated_papers');

        echo json_encode(['status' => 'success', 'message' => 'Paper deleted successfully.']);
    }

    /**
     * AJAX Endpoint: Fetch Saved Papers List
     */
    public function get_saved_papers_list_ajax()
    {
        if (!$this->rbac->hasPrivilege('examination', 'can_view')) {
            echo json_encode(['status' => 'error', 'message' => 'Access Denied']);
            return;
        }

        $list = $this->get_saved_papers_list();
        echo json_encode(['status' => 'success', 'list' => $list]);
    }

    /**
     * Helper to get list of saved papers
     */
    private function get_saved_papers_list()
    {
        $this->db->select('id, paper_title, class_name, subject_name, total_marks, created_at');
        $this->db->from('cbse_ai_generated_papers');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(30);
        return $this->db->get()->result_array();
    }

    /**
     * AJAX Endpoint: Push generated questions into LMS Question Bank
     */
    public function save_to_question_bank_ajax()
    {
        if (!$this->rbac->hasPrivilege('examination', 'can_view')) {
            echo json_encode(['status' => 'error', 'message' => 'Access Denied']);
            return;
        }

        $class_id          = $this->input->post('class_id');
        $subject_id        = $this->input->post('subject_id');
        $questions_payload = $this->input->post('questions_payload');

        if (empty($questions_payload)) {
            echo json_encode(['status' => 'error', 'message' => 'No questions payload received.']);
            return;
        }

        $questions = json_decode($questions_payload, true);
        if (!is_array($questions) || empty($questions)) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid questions data format.']);
            return;
        }

        $saved_count = 0;
        foreach ($questions as $q) {
            $question_type = isset($q['question_type']) ? $q['question_type'] : 'singlechoice';
            $question_text = isset($q['question_text']) ? $q['question_text'] : '';

            if (empty($question_text)) {
                continue;
            }

            $options = isset($q['options']) && is_array($q['options']) ? $q['options'] : [];
            $correct = isset($q['correct_option']) ? $q['correct_option'] : 'A';
            
            $insert_data = [
                'subject_id'     => !empty($subject_id) ? $subject_id : null,
                'class_id'       => !empty($class_id) ? $class_id : null,
                'question_type'  => $question_type,
                'question'       => $question_text,
                'opt_a'          => isset($options['A']) ? $options['A'] : null,
                'opt_b'          => isset($options['B']) ? $options['B'] : null,
                'opt_c'          => isset($options['C']) ? $options['C'] : null,
                'opt_d'          => isset($options['D']) ? $options['D'] : null,
                'opt_e'          => isset($options['E']) ? $options['E'] : null,
                'correct'        => 'opt_' . strtolower($correct),
                'level'          => isset($q['level']) ? $q['level'] : 'medium',
                'explanation'    => isset($q['explanation']) ? $q['explanation'] : null,
                'created_at'     => date('Y-m-d H:i:s')
            ];

            if ($this->question_model->add($insert_data)) {
                $saved_count++;
            }
        }

        echo json_encode([
            'status'  => 'success',
            'message' => "Successfully saved {$saved_count} questions into Question Bank."
        ]);
    }

    /**
     * AJAX Endpoint: Get Cached NCERT Chapters or Fetch 1-Time via AI Model & Cache
     */
    public function get_or_fetch_chapters_ajax()
    {
        if (!$this->rbac->hasPrivilege('examination', 'can_view')) {
            echo json_encode(['status' => 'error', 'message' => 'Access Denied']);
            return;
        }

        $class_name   = trim($this->input->post('class_name'));
        $subject_name = trim($this->input->post('subject_name'));
        $api_engine   = $this->input->post('api_engine');
        $force_reload = $this->input->post('force_reload') == '1';

        if (empty($class_name) || empty($subject_name)) {
            echo json_encode(['status' => 'error', 'message' => 'Class and Subject are required.']);
            return;
        }

        // 1. Check if chapters are already cached in database
        if (!$force_reload) {
            $cached = $this->db->get_where('cbse_syllabus_chapters', [
                'class_name'   => $class_name,
                'subject_name' => $subject_name
            ])->row_array();

            if ($cached && !empty($cached['chapters_json'])) {
                $chapters = json_decode($cached['chapters_json'], true);
                if (is_array($chapters) && count($chapters) > 0) {
                    echo json_encode([
                        'status'   => 'success',
                        'source'   => 'database_cache',
                        'chapters' => $chapters
                    ]);
                    return;
                }
            }
        }

        try {
            // 2. Fetch 1-time from AI Model (Gemini / OpenRouter ox-alpha / Groq)
            $ai_result = $this->ai_exam_generator->fetch_subject_chapters_ai($class_name, $subject_name, $api_engine);

            if ($ai_result['status'] === 'success' && !empty($ai_result['chapters'])) {
                $chapters = $ai_result['chapters'];

                // 3. Ensure MySQL connection is active after external AI API call (reconnect if timed out)
                $db_alive = false;
                try {
                    if (isset($this->db->conn_id) && $this->db->conn_id instanceof mysqli) {
                        $db_alive = @$this->db->conn_id->ping();
                    }
                } catch (\Throwable $te) {
                    $db_alive = false;
                }

                if (!$db_alive) {
                    try {
                        $this->db->reconnect();
                        if (!$this->db->conn_id) {
                            $this->db->initialize();
                        }
                    } catch (\Throwable $te) {}
                }

                // Cache into Database so it NEVER makes external AI calls for this class+subject again
                $this->db->where('class_name', $class_name);
                $this->db->where('subject_name', $subject_name);
                $this->db->delete('cbse_syllabus_chapters');

                $insert_ok = $this->db->insert('cbse_syllabus_chapters', [
                    'class_name'    => $class_name,
                    'subject_name'  => $subject_name,
                    'chapters_json' => json_encode($chapters, JSON_UNESCAPED_UNICODE),
                    'updated_at'    => date('Y-m-d H:i:s')
                ]);

                $db_error = $this->db->error();

                echo json_encode([
                    'status'     => 'success',
                    'source'     => 'ai_fetched_and_cached',
                    'model_used' => isset($ai_result['model_used']) ? $ai_result['model_used'] : 'AI Model',
                    'chapters'   => $chapters,
                    'saved_in_db'=> $insert_ok ? true : false,
                    'db_error'   => !empty($db_error['message']) ? $db_error['message'] : null
                ]);
            } else {
                echo json_encode([
                    'status'  => 'error',
                    'message' => isset($ai_result['message']) ? $ai_result['message'] : 'Failed to fetch chapters from AI.'
                ]);
            }
        } catch (\Throwable $e) {
            echo json_encode([
                'status'  => 'error',
                'message' => 'Server Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * AJAX Endpoint: Fetch mapped subjects for a specific class based on Subject Group assignments
     */
    public function get_subjects_by_class_ajax()
    {
        if (!$this->rbac->hasPrivilege('examination', 'can_view')) {
            echo json_encode(['status' => 'error', 'message' => 'Access Denied']);
            return;
        }

        $class_id = $this->input->post('class_id');
        if (empty($class_id)) {
            echo json_encode(['status' => 'error', 'message' => 'Class ID is required']);
            return;
        }

        $current_session = $this->setting_model->getCurrentSession();

        // Fetch all distinct subjects mapped to this class via subject_group_class_sections
        $sql = "SELECT DISTINCT s.id, s.name, s.code, s.type 
                FROM subjects s
                INNER JOIN subject_group_subjects sgs ON sgs.subject_id = s.id
                INNER JOIN subject_groups sg ON sg.id = sgs.subject_group_id
                INNER JOIN subject_group_class_sections sgcs ON sgcs.subject_group_id = sg.id
                INNER JOIN class_sections cs ON cs.id = sgcs.class_section_id
                WHERE cs.class_id = " . $this->db->escape($class_id) . "
                  AND sg.session_id = " . $this->db->escape($current_session) . "
                ORDER BY s.name ASC";

        $subjects = $this->db->query($sql)->result_array();

        // Fallback: If no subject group mapped yet for this class, fallback to all active subjects
        if (empty($subjects)) {
            $subjects = $this->subject_model->get();
        }

        echo json_encode([
            'status'   => 'success',
            'subjects' => $subjects
        ]);
    }

    /**
     * AJAX Endpoint: Get list of all Class + Subject pairs mapped via Subject Groups for bulk background sync
     */
    public function get_sync_pairs_ajax()
    {
        if (!$this->rbac->hasPrivilege('examination', 'can_view')) {
            echo json_encode(['status' => 'error', 'message' => 'Access Denied']);
            return;
        }

        $current_session = $this->setting_model->getCurrentSession();

        // Fetch only valid Class + Subject combinations defined in Subject Groups
        $sql = "SELECT DISTINCT c.id AS class_id, c.class AS class_name, s.id AS subject_id, s.name AS subject_name
                FROM classes c
                INNER JOIN class_sections cs ON cs.class_id = c.id
                INNER JOIN subject_group_class_sections sgcs ON sgcs.class_section_id = cs.id
                INNER JOIN subject_groups sg ON sg.id = sgcs.subject_group_id
                INNER JOIN subject_group_subjects sgs ON sgs.subject_group_id = sg.id
                INNER JOIN subjects s ON s.id = sgs.subject_id
                WHERE sg.session_id = " . $this->db->escape($current_session) . "
                ORDER BY c.id ASC, s.name ASC";

        $rows = $this->db->query($sql)->result_array();

        $pairs = [];
        if (!empty($rows)) {
            foreach ($rows as $row) {
                $className   = trim($row['class_name']);
                $subjectName = trim($row['subject_name']);

                // Check if already cached in DB
                $cached = $this->db->get_where('cbse_syllabus_chapters', [
                    'class_name'   => $className,
                    'subject_name' => $subjectName
                ])->row_array();

                $pairs[] = [
                    'class_id'     => $row['class_id'],
                    'class_name'   => $className,
                    'subject_id'   => $row['subject_id'],
                    'subject_name' => $subjectName,
                    'is_cached'    => ($cached && !empty($cached['chapters_json'])) ? 1 : 0
                ];
            }
        } else {
            // Fallback: If no subject groups defined yet, pair classes with all subjects
            $classes  = $this->class_model->get();
            $subjects = $this->subject_model->get();
            if (!empty($classes) && !empty($subjects)) {
                foreach ($classes as $cls) {
                    foreach ($subjects as $sub) {
                        $className   = trim($cls['class']);
                        $subjectName = trim($sub['name']);
                        $cached = $this->db->get_where('cbse_syllabus_chapters', [
                            'class_name'   => $className,
                            'subject_name' => $subjectName
                        ])->row_array();

                        $pairs[] = [
                            'class_id'     => $cls['id'],
                            'class_name'   => $className,
                            'subject_id'   => $sub['id'],
                            'subject_name' => $subjectName,
                            'is_cached'    => ($cached && !empty($cached['chapters_json'])) ? 1 : 0
                        ];
                    }
                }
            }
        }

        echo json_encode([
            'status'      => 'success',
            'total_pairs' => count($pairs),
            'pairs'       => $pairs
        ]);
    }
}
