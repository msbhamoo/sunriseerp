<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Timetable extends MY_Addon_CBSEController
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->load->model("cbseexam/cbseexam_exam_model");
        $this->load->model("class_model");
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('cbse_exam_timetable', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'cbse_exam');
        $this->session->set_userdata('sub_menu', 'cbseexam/timetable/index');
        
        $data['classlist'] = $this->class_model->get();
        
        $this->load->view('layout/header', $data);
        $this->load->view('cbseexam/timetable/index', $data);
        $this->load->view('layout/footer', $data);
    }

    public function get_exams()
    {
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        
        // Find exams linked to this class and section
        $this->db->select('cbse_exams.*');
        $this->db->from('cbse_exams');
        $this->db->join('cbse_exam_class_sections', 'cbse_exam_class_sections.cbse_exam_id = cbse_exams.id');
        $this->db->join('class_sections', 'class_sections.id = cbse_exam_class_sections.class_section_id');
        $this->db->where('class_sections.class_id', $class_id);
        $this->db->where('class_sections.section_id', $section_id);
        $this->db->where('cbse_exams.session_id', $this->current_session);
        $this->db->where('cbse_exams.is_active', 1);
        $this->db->group_by('cbse_exams.id');
        $exams = $this->db->get()->result();
        
        echo json_encode($exams);
    }

    public function get_timetable()
    {
        $exam_ids = $this->input->post('exam_ids');
        $data['exams_data'] = array();
        
        $this->db->select('cbse_exams.*');
        $this->db->from('cbse_exams');
        $this->db->where('cbse_exams.session_id', $this->current_session);
        $this->db->where('cbse_exams.is_active', 1);
        
        if (!empty($exam_ids) && is_array($exam_ids)) {
            $this->db->where_in('cbse_exams.id', $exam_ids);
        }
        $exams = $this->db->get()->result_array();
        
        foreach ($exams as $exam) {
            $timetable = $this->db->select('cbse_exam_timetable.*, subjects.name as subject_name, subjects.code as subject_code')
                ->from('cbse_exam_timetable')
                ->join('subjects', 'subjects.id=cbse_exam_timetable.subject_id')
                ->where('cbse_exam_id', $exam['id'])
                ->order_by('date', 'asc')
                ->order_by('time_from', 'asc')
                ->get()
                ->result();
                
            // Fetch class and section names for this exam
            $class_sections = $this->db->select('classes.class, sections.section')
                ->from('cbse_exam_class_sections')
                ->join('class_sections', 'class_sections.id = cbse_exam_class_sections.class_section_id')
                ->join('classes', 'classes.id = class_sections.class_id')
                ->join('sections', 'sections.id = class_sections.section_id')
                ->where('cbse_exam_class_sections.cbse_exam_id', $exam['id'])
                ->get()
                ->result_array();
                
            $class_section_names = [];
            foreach ($class_sections as $cs) {
                $class_section_names[] = $cs['class'] . ' (' . $cs['section'] . ')';
            }
            $exam['class_sections_str'] = implode(', ', $class_section_names);
                
            $data['exams_data'][] = array(
                'exam' => $exam,
                'timetable' => $timetable
            );
        }
            
        $html = $this->load->view('cbseexam/timetable/_timetable_list', $data, true);
        echo json_encode(['status' => 1, 'page' => $html]);
    }
}
