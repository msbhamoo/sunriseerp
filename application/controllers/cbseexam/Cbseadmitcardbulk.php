<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Cbseadmitcardbulk extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
        $this->load->model('cbseexam/cbseexam_admitcard_model');
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->load->library('SaasValidation');
    }

    public function generate()
    {
        if (!$this->rbac->hasPrivilege('cbse_exam_generate_admit_card', 'can_view')) {
            access_denied();
        }

        $data['classlist']             = $this->class_model->get();
        $data['getexamlist']           = $this->cbseexam_admitcard_model->getexamlist();
        $data['get_active_admitcard']  = $this->cbseexam_admitcard_model->get_active_admitcard();

        $exam_id    = $this->input->post('exam_id');
        $class_id   = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');

        $data['exam_id']    = $exam_id;
        $data['class_id']   = $class_id;
        $data['section_id'] = $section_id;
        $data['sch_setting']= $this->sch_setting_detail;

        $data['student_list'] = [];
        $data['summary_data'] = [];

        if ($this->input->server('REQUEST_METHOD') === 'POST' && !empty($exam_id)) {
            if (empty($class_id) && empty($section_id)) {
                // Fetch summary data for the exam
                $data['summary_data'] = $this->cbseexam_admitcard_model->get_admitcard_generation_summary($exam_id);
            } else {
                // Fetch students for the selected exam, filtered by class/section
                $data['student_list'] = $this->get_exam_students_with_roll_no($exam_id, $class_id, $section_id);
            }
        } else {
            // Initial Load: If an exam exists, load summary for the most recent/first exam
            if (!empty($data['getexamlist'])) {
                $first_exam_id = $data['getexamlist'][0]->id;
                $data['exam_id'] = $first_exam_id;
                $data['summary_data'] = $this->cbseexam_admitcard_model->get_admitcard_generation_summary($first_exam_id);
            }
        }

        $this->load->view('layout/header', $data);
        $this->load->view('cbseexam/cbseadmitcard/generate_admitcard', $data);
        $this->load->view('layout/footer', $data);
    }

    private function get_exam_students_with_roll_no($exam_id, $class_id = '', $section_id = '') {
        $this->db->select('students.*, classes.class, sections.section, cbse_exam_students.roll_no as admit_roll_no, cbse_exam_students.id as cbse_exam_student_id, student_session.class_id, student_session.section_id');
        $this->db->from('cbse_exam_students');
        $this->db->join('student_session', 'student_session.id = cbse_exam_students.student_session_id', 'left');
        $this->db->join('students', 'students.id = student_session.student_id', 'left');
        $this->db->join('classes', 'classes.id = student_session.class_id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->where('cbse_exam_students.cbse_exam_id', $exam_id);
        
        if (!empty($class_id)) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if (!empty($section_id)) {
            $this->db->where('student_session.section_id', $section_id);
        }
        
        $this->db->order_by('students.firstname', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function generate_missing() {
        $exam_id = $this->input->post('exam_id');
        $series = (int)$this->input->post('series');
        
        if (empty($exam_id) || empty($series)) {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger">Exam and Series are required.</div>');
            redirect('cbseexam/cbseadmitcardbulk/generate');
        }

        $this->db->select('cbse_exam_students.*');
        $this->db->from('cbse_exam_students');
        $this->db->join('student_session', 'student_session.id = cbse_exam_students.student_session_id', 'left');
        $this->db->join('students', 'students.id = student_session.student_id', 'left');
        $this->db->join('classes', 'classes.id = student_session.class_id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->where('cbse_exam_students.cbse_exam_id', $exam_id);
        $this->db->order_by('classes.class', 'asc');
        $this->db->order_by('sections.section', 'asc');
        $this->db->order_by('students.firstname', 'asc');
        $this->db->order_by('students.lastname', 'asc');
        $students = $this->db->get()->result();
        
        foreach ($students as $student) {
            if (empty($student->roll_no) || $student->roll_no == 0 || $student->roll_no == '') {
                $this->db->update('cbse_exam_students', ['roll_no' => $series], ['id' => $student->id]);
                $series++;
            }
        }

        $this->session->set_flashdata('msg', '<div class="alert alert-success">Successfully generated missing roll numbers for the exam.</div>');
        redirect('cbseexam/cbseadmitcardbulk/generate');
    }

    public function generate_missing_by_section() {
        $exam_id = $this->input->post('exam_id');
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        $series = (int)$this->input->post('series');
        
        if (empty($exam_id) || empty($series) || empty($class_id) || empty($section_id)) {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger">Exam, Class, Section and Series are required.</div>');
            redirect('cbseexam/cbseadmitcardbulk/generate');
        }

        $this->db->select('cbse_exam_students.*');
        $this->db->from('cbse_exam_students');
        $this->db->join('student_session', 'student_session.id = cbse_exam_students.student_session_id', 'left');
        $this->db->join('students', 'students.id = student_session.student_id', 'left');
        $this->db->where('cbse_exam_students.cbse_exam_id', $exam_id);
        $this->db->where('student_session.class_id', $class_id);
        $this->db->where('student_session.section_id', $section_id);
        $this->db->order_by('students.firstname', 'asc');
        $this->db->order_by('students.lastname', 'asc');
        $students = $this->db->get()->result();
        
        foreach ($students as $student) {
            if (empty($student->roll_no) || $student->roll_no == 0 || $student->roll_no == '') {
                $this->db->update('cbse_exam_students', ['roll_no' => $series], ['id' => $student->id]);
                $series++;
            }
        }

        $this->session->set_flashdata('msg', '<div class="alert alert-success">Successfully generated missing roll numbers for the selected section.</div>');
        redirect('cbseexam/cbseadmitcardbulk/generate');
    }

    public function generate_admitcards()
    {
        $series = $this->input->post('series');
        $cbse_exam_student_ids = $this->input->post('cbse_exam_student_id');

        if (empty($cbse_exam_student_ids)) {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger">No students selected!</div>');
            redirect('cbseexam/cbseadmitcardbulk/generate');
        }

        if (empty($series)) {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger">Please enter a Series to generate Admit Cards.</div>');
            redirect('cbseexam/cbseadmitcardbulk/generate');
        }

        $current_series = (int)$series;

        $this->db->select('cbse_exam_students.*');
        $this->db->from('cbse_exam_students');
        $this->db->join('student_session', 'student_session.id = cbse_exam_students.student_session_id', 'left');
        $this->db->join('students', 'students.id = student_session.student_id', 'left');
        $this->db->join('classes', 'classes.id = student_session.class_id', 'left');
        $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
        $this->db->where_in('cbse_exam_students.id', $cbse_exam_student_ids);
        $this->db->order_by('classes.class', 'asc');
        $this->db->order_by('sections.section', 'asc');
        $this->db->order_by('students.firstname', 'asc');
        $this->db->order_by('students.lastname', 'asc');
        $exam_students = $this->db->get()->result();

        // Generate Roll Numbers
        foreach ($exam_students as $exam_student) {
            $this->db->update('cbse_exam_students', ['roll_no' => $current_series], ['id' => $exam_student->id]);
            $current_series++;
        }

        $this->session->set_flashdata('msg', '<div class="alert alert-success">Admit Cards Generated successfully.</div>');
        redirect('cbseexam/cbseadmitcardbulk/generate');
    }

    public function save_and_download()
    {
        $admitcard_template = $this->input->post('admitcard_template');
        $exam_id = $this->input->post('exam_id'); 
        $show_timetable = $this->input->post('show_timetable') ? 1 : 0;
        
        // Use cbse_exam_student_ids instead of raw student_id for precision
        $cbse_exam_student_ids = $this->input->post('cbse_exam_student_id');

        if (empty($cbse_exam_student_ids)) {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger">No students selected!</div>');
            redirect('cbseexam/cbseadmitcardbulk/generate');
        }

        // Get student ids that actually have roll numbers generated
        $student_ids = array();
        foreach ($cbse_exam_student_ids as $cbse_exam_student_id) {
            $exam_student = $this->db->get_where('cbse_exam_students', ['id' => $cbse_exam_student_id])->row();
            if ($exam_student && !empty($exam_student->roll_no)) {
                $student_session = $this->db->get_where('student_session', ['id' => $exam_student->student_session_id])->row();
                $student_ids[] = $student_session->student_id;
            }
        }

        if (empty($student_ids)) {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger">None of the selected students have generated Admit Cards. Please generate them first.</div>');
            redirect('cbseexam/cbseadmitcardbulk/generate');
        }

        // Get admit card template
        $data['admitcard'] = $this->cbseexam_admitcard_model->get($admitcard_template);
        $data['sch_setting'] = $this->sch_setting_detail;
        $data['show_timetable'] = $show_timetable;

        $pdf_html = "";
        
        $data['exam'] = $this->cbseexam_admitcard_model->getexamlist($exam_id);
        $data['exam_subjects'] = $this->cbseexam_admitcard_model->get_cbse_exam_timetable($exam_id);
        $data['student_details'] = $this->cbseexam_admitcard_model->get_cbse_exam_students($student_ids, $exam_id);
        
        if (!empty($data['student_details'])) {
            $pdf_html .= $this->load->view('cbseexam/cbseadmitcard/_printadmitcard_pdf', $data, true);
        }

        if (empty($pdf_html)) {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger">No students registered in the selected exam(s) to generate admit card.</div>');
            redirect('cbseexam/cbseadmitcardbulk/generate');
        }

        $this->load->library('m_pdf');
        $mpdf = $this->m_pdf->load(['mode' => 'utf-8', 'format' => 'A4']);

        if (!empty($data['admitcard']->background_img)) {
            $mpdf->SetDefaultBodyCSS('background', "url('" . base_url("uploads/cbseexam/admitcard/" . $data['admitcard']->background_img) . "')");
            $mpdf->SetDefaultBodyCSS('background-image-resize', 6);
        }

        $mpdf->SetDisplayMode('fullpage');
        $mpdf->autoScriptToLang = true;
        $mpdf->baseScript = 1;
        $mpdf->autoLangToFont = true;

        $mpdf->WriteHTML($pdf_html);
        $mpdf->Output('Admit_Cards_' . time() . '.pdf', 'D'); // Download
    }
    public function view_admitcard_html()
    {
        $admitcard_template = $this->input->post('admitcard_template');
        $exam_id = $this->input->post('exam_id'); 
        $show_timetable = $this->input->post('show_timetable') ? 1 : 0;
        
        $cbse_exam_student_id = $this->input->post('cbse_exam_student_id');

        if (empty($cbse_exam_student_id)) {
            echo '<div class="alert alert-danger">No student selected!</div>';
            return;
        }

        $exam_student = $this->db->get_where('cbse_exam_students', ['id' => $cbse_exam_student_id])->row();
        if ($exam_student) {
            $student_session = $this->db->get_where('student_session', ['id' => $exam_student->student_session_id])->row();
            $student_ids = [$student_session->student_id];
        } else {
            echo '<div class="alert alert-danger">Student not found!</div>';
            return;
        }

        $data['admitcard'] = $this->cbseexam_admitcard_model->get($admitcard_template);
        $data['sch_setting'] = $this->sch_setting_detail;
        $data['show_timetable'] = $show_timetable;

        $pdf_html = "";
        
        $data['exam'] = $this->cbseexam_admitcard_model->getexamlist($exam_id);
        $data['exam_subjects'] = $this->cbseexam_admitcard_model->get_cbse_exam_timetable($exam_id);
        $data['student_details'] = $this->cbseexam_admitcard_model->get_cbse_exam_students($student_ids, $exam_id);
        
        if (!empty($data['student_details'])) {
            $pdf_html .= $this->load->view('cbseexam/cbseadmitcard/_printadmitcard_pdf', $data, true);
        }

        if (empty($pdf_html)) {
            echo '<div class="alert alert-danger">Admit card could not be generated.</div>';
            return;
        }

        echo $pdf_html;
    }
}
?>
