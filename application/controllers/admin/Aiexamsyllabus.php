<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Aiexamsyllabus extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Ai_exam_generator');
        $this->load->model(['class_model', 'subject_model']);
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    /**
     * Curriculum & Syllabus Catalog Manager View
     */
    public function index()
    {
        if (!$this->rbac->hasPrivilege('examination', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'aiexam');
        $this->session->set_userdata('sub_menu', 'admin/aiexamsyllabus');

        $data['classlist']       = $this->class_model->get();
        $data['subjectlist']     = $this->subject_model->get();
        $data['sch_setting']     = $this->sch_setting_detail;
        $data['current_session'] = $this->setting_model->getCurrentSessionName();

        // Fetch all cached syllabus records from database
        $this->db->select('*');
        $this->db->from('cbse_syllabus_chapters');
        $this->db->order_by('class_name', 'ASC');
        $this->db->order_by('subject_name', 'ASC');
        $data['syllabus_list']   = $this->db->get()->result_array();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/aiexam/syllabus_catalog', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * AJAX Endpoint: Save or Edit Chapters for a specific Class + Subject
     */
    public function save_chapters_ajax()
    {
        if (!$this->rbac->hasPrivilege('examination', 'can_edit')) {
            echo json_encode(['status' => 'error', 'message' => 'Access Denied']);
            return;
        }

        $class_name   = trim($this->input->post('class_name'));
        $subject_name = trim($this->input->post('subject_name'));
        $chapters_raw = trim($this->input->post('chapters_raw'));

        if (empty($class_name) || empty($subject_name)) {
            echo json_encode(['status' => 'error', 'message' => 'Class and Subject are required.']);
            return;
        }

        $lines = explode("\n", str_replace("\r", "", $chapters_raw));
        $chapters = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line)) {
                $chapters[] = $line;
            }
        }

        if (empty($chapters)) {
            echo json_encode(['status' => 'error', 'message' => 'Please provide at least 1 chapter.']);
            return;
        }

        $this->db->where('class_name', $class_name);
        $this->db->where('subject_name', $subject_name);
        $this->db->delete('cbse_syllabus_chapters');

        $this->db->insert('cbse_syllabus_chapters', [
            'class_name'    => $class_name,
            'subject_name'  => $subject_name,
            'chapters_json' => json_encode($chapters),
            'updated_at'    => date('Y-m-d H:i:s')
        ]);

        // Automatically sync chapters into LMS Lesson Plan & Status (lesson & topic tables)
        $this->sync_single_syllabus_to_lessons($class_name, $subject_name, $chapters);

        echo json_encode([
            'status'  => 'success',
            'message' => 'Curriculum chapters saved and synchronized with Lesson Plan & Syllabus Status!'
        ]);
    }

    /**
     * Helper: Sync chapters array into LMS Lesson Plan tables (`lesson` & `topic`)
     */
    private function sync_single_syllabus_to_lessons($class_name, $subject_name, $chapters)
    {
        $current_session = $this->setting_model->getCurrentSession();
        
        // Find matching class
        $this->db->select('id');
        $this->db->from('classes');
        $this->db->where('class', $class_name);
        $class_row = $this->db->get()->row_array();
        if (empty($class_row)) return 0;
        $class_id = $class_row['id'];

        // Find matching subject
        $this->db->select('id');
        $this->db->from('subjects');
        $this->db->where('name', $subject_name);
        $sub_row = $this->db->get()->row_array();
        if (empty($sub_row)) return 0;
        $subject_id = $sub_row['id'];

        // Find subject group mapping
        $sql = "SELECT sgs.id as subject_group_subject_id, sgcs.id as subject_group_class_sections_id
                FROM subject_group_subjects sgs
                INNER JOIN subject_groups sg ON sg.id = sgs.subject_group_id
                INNER JOIN subject_group_class_sections sgcs ON sgcs.subject_group_id = sg.id
                INNER JOIN class_sections cs ON cs.id = sgcs.class_section_id
                WHERE cs.class_id = ? AND sgs.subject_id = ? AND sg.session_id = ?
                LIMIT 1";
        $map = $this->db->query($sql, [$class_id, $subject_id, $current_session])->row_array();

        if (empty($map)) return 0;

        $sg_sub_id = $map['subject_group_subject_id'];
        $sg_cs_id  = $map['subject_group_class_sections_id'];

        $synced_lessons = 0;
        foreach ($chapters as $ch_name) {
            $ch_clean = trim($ch_name);
            if (empty($ch_clean)) continue;

            // Check if lesson already exists
            $this->db->select('id');
            $this->db->from('lesson');
            $this->db->where('subject_group_subject_id', $sg_sub_id);
            $this->db->where('subject_group_class_sections_id', $sg_cs_id);
            $this->db->where('name', $ch_clean);
            $this->db->where('session_id', $current_session);
            $existing_lesson = $this->db->get()->row_array();

            $lesson_id = null;
            if (!empty($existing_lesson)) {
                $lesson_id = $existing_lesson['id'];
            } else {
                $this->db->insert('lesson', [
                    'name'                            => $ch_clean,
                    'subject_group_subject_id'        => $sg_sub_id,
                    'subject_group_class_sections_id' => $sg_cs_id,
                    'session_id'                      => $current_session
                ]);
                $lesson_id = $this->db->insert_id();
                $synced_lessons++;
            }

            // Ensure at least 1 default core topic is created under this lesson for Syllabus Status tracking
            if ($lesson_id) {
                $this->db->select('id');
                $this->db->from('topic');
                $this->db->where('lesson_id', $lesson_id);
                $this->db->where('session_id', $current_session);
                $existing_topic = $this->db->get()->row_array();

                if (empty($existing_topic)) {
                    $this->db->insert('topic', [
                        'name'       => $ch_clean . ' - Core Concepts & Learning Objectives',
                        'lesson_id'  => $lesson_id,
                        'session_id' => $current_session,
                        'status'     => 0
                    ]);
                }
            }
        }

        return $synced_lessons;
    }

    /**
     * AJAX Endpoint: Bulk 2-Way Sync between AI Syllabus and Lesson Plan / Syllabus Status
     */
    public function sync_all_with_lessonplan_ajax()
    {
        if (!$this->rbac->hasPrivilege('examination', 'can_edit')) {
            echo json_encode(['status' => 'error', 'message' => 'Access Denied']);
            return;
        }

        $all_syllabi = $this->db->get('cbse_syllabus_chapters')->result_array();
        $synced_count = 0;

        foreach ($all_syllabi as $s) {
            $chapters = json_decode($s['chapters_json'], true);
            if (is_array($chapters)) {
                $synced_count += $this->sync_single_syllabus_to_lessons($s['class_name'], $s['subject_name'], $chapters);
            }
        }

        echo json_encode([
            'status'  => 'success',
            'message' => "Successfully synchronized curriculum chapters with LMS Lesson Plan & Syllabus Status! ({$synced_count} lessons updated/created)."
        ]);
    }

    /**
     * AJAX Endpoint: Delete specific syllabus cache entry
     */
    public function delete_syllabus_ajax()
    {
        if (!$this->rbac->hasPrivilege('examination', 'can_delete')) {
            echo json_encode(['status' => 'error', 'message' => 'Access Denied']);
            return;
        }

        $id = $this->input->post('id');
        if (!empty($id)) {
            $this->db->where('id', $id);
            $this->db->delete('cbse_syllabus_chapters');
            echo json_encode(['status' => 'success', 'message' => 'Syllabus entry removed.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Invalid ID.']);
        }
    }
}
