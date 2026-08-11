<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Dashboard extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('cbseexam/cbseexam_exam_model');
        $this->load->model('cbseexam/cbseexam_template_model');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('cbse_exam', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'cbse_exam');
        $this->session->set_userdata('sub_menu', 'cbseexam/dashboard');

        // Fetch metrics
        $data['total_exams'] = $this->db->count_all('cbse_exams');
        $data['total_templates'] = $this->db->count_all('cbse_template');
        
        $session_id = $this->setting_model->getCurrentSession();
        $today = date('Y-m-d');
        
        // Upcoming exams
        $cbse_query = $this->db->query("
            SELECT e.name as exam_title, e.description as exam_description,
                   MIN(t.date) as start_date, MAX(t.date) as end_date
            FROM cbse_exams e
            JOIN cbse_exam_timetable t ON t.cbse_exam_id = e.id
            WHERE e.session_id = $session_id
            GROUP BY e.id
            HAVING MAX(t.date) >= '$today'
            ORDER BY MIN(t.date) ASC
            LIMIT 5
        ");
        $data['upcoming_cbse_exams'] = $cbse_query->result_array();
        
        // Recent templates
        $template_query = $this->db->query("
            SELECT name, date, marksheet_type
            FROM cbse_template
            ORDER BY id DESC
            LIMIT 5
        ");
        $data['recent_templates'] = $template_query->result_array();

        // ---- Guided setup steps (completion-aware) ----
        $grade_count      = $this->db->count_all('cbse_exam_grades_range');
        $assessment_count = $this->db->count_all('cbse_exam_assessment_types');
        $term_count       = $this->db->count_all('cbse_terms');
        $exam_count       = $data['total_exams'];
        $timetable_count  = $this->db->count_all('cbse_exam_timetable');
        $marks_count      = $this->db->count_all('cbse_student_subject_marks');

        $data['setup_steps'] = array(
            array('n' => 1, 'title' => 'Set up grades', 'desc' => 'Define the grade bands (A1–E) used on report cards.', 'url' => site_url('cbseexam/grade/gradelist'), 'done' => $grade_count > 0, 'count' => $grade_count),
            array('n' => 2, 'title' => 'Define the marks pattern', 'desc' => 'Create an Assessment with its max marks & pass %.', 'url' => site_url('cbseexam/assessment'), 'done' => $assessment_count > 0, 'count' => $assessment_count),
            array('n' => 3, 'title' => 'Create terms', 'desc' => 'Set up terms (e.g. Term I, Term II) for the session.', 'url' => site_url('cbseexam/term/index'), 'done' => $term_count > 0, 'count' => $term_count),
            array('n' => 4, 'title' => 'Create an exam & subjects', 'desc' => 'Add the exam, its classes, subjects and schedule.', 'url' => site_url('cbseexam/exam'), 'done' => ($exam_count > 0 && $timetable_count > 0), 'count' => $exam_count),
            array('n' => 5, 'title' => 'Enter marks', 'desc' => 'Type marks or bulk-upload from the Marks Register.', 'url' => site_url('cbseexam/marks'), 'done' => $marks_count > 0, 'count' => $marks_count),
            array('n' => 6, 'title' => 'Generate report cards', 'desc' => 'Print the CBSE report card from the Marks Register.', 'url' => site_url('cbseexam/marks'), 'done' => false, 'count' => null, 'optional' => true),
        );
        $done = 0;
        foreach ($data['setup_steps'] as $st) { if ($st['done']) $done++; }
        $data['setup_done'] = $done;
        $data['setup_total'] = count($data['setup_steps']);

        $this->load->view('layout/header', $data);
        $this->load->view('cbseexam/dashboard/index', $data);
        $this->load->view('layout/footer', $data);
    }
}
