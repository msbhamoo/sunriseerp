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
        
        $this->load->view('layout/header', $data);
        $this->load->view('cbseexam/dashboard/index', $data);
        $this->load->view('layout/footer', $data);
    }
}
