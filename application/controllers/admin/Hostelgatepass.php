<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Hostelgatepass extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Customlib');
        $this->load->model('hostelgatepass_model');
        $this->load->model('student_model');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('hostel_gate_pass', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Hostel');
        $this->session->set_userdata('sub_menu', 'hostelgatepass/index');

        $data['title'] = 'Hostel Gate Pass';
        $data['gate_passes'] = $this->hostelgatepass_model->get();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/hostel/gatepass', $data);
        $this->load->view('layout/footer', $data);
    }

    public function search_student()
    {
        if (!$this->rbac->hasPrivilege('hostel_gate_pass', 'can_add')) {
            echo json_encode([]);
            return;
        }

        $keyword = $this->input->post('keyword');
        
        $this->db->select('students.id, students.firstname, students.lastname, students.admission_no, student_session.id as student_session_id');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->where('students.is_active', 'yes');
        $this->db->where('students.hostel_room_id IS NOT NULL');
        
        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('students.firstname', $keyword);
            $this->db->or_like('students.lastname', $keyword);
            $this->db->or_like('students.admission_no', $keyword);
            $this->db->group_end();
        }
        
        $this->db->limit(10);
        $students = $this->db->get()->result_array();
        
        $json = [];
        foreach ($students as $student) {
            $json[] = [
                'id' => $student['student_session_id'],
                'text' => $student['firstname'] . ' ' . $student['lastname'] . ' (' . $student['admission_no'] . ')'
            ];
        }
        
        echo json_encode($json);
    }

    public function add()
    {
        if (!$this->rbac->hasPrivilege('hostel_gate_pass', 'can_add')) {
            access_denied();
        }

        $this->form_validation->set_rules('student_session_id', 'Student', 'required');
        $this->form_validation->set_rules('going_to', 'Going To', 'required');
        $this->form_validation->set_rules('out_date', 'Out Date', 'required');
        $this->form_validation->set_rules('out_time', 'Out Time', 'required');

        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'student_session_id' => form_error('student_session_id'),
                'going_to' => form_error('going_to'),
                'out_date' => form_error('out_date'),
                'out_time' => form_error('out_time')
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $expected_in = $this->input->post('expected_in_time');
            
            $data = array(
                'student_session_id' => $this->input->post('student_session_id'),
                'going_to' => $this->input->post('going_to'),
                'reason' => $this->input->post('reason'),
                'out_date' => $this->customlib->dateFormatToYYYYMMDD($this->input->post('out_date')),
                'out_time' => $this->input->post('out_time'),
                'expected_in_time' => empty($expected_in) ? NULL : $expected_in,
                'status' => 'Out'
            );

            $this->hostelgatepass_model->add($data);
            $array = array('status' => 'success', 'error' => '', 'message' => 'Gate pass created successfully.');
        }

        echo json_encode($array);
    }

    public function mark_returned($id)
    {
        if (!$this->rbac->hasPrivilege('hostel_gate_pass', 'can_edit')) {
            access_denied();
        }

        $this->hostelgatepass_model->mark_returned($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Gate pass marked as returned.</div>');
        redirect('admin/hostelgatepass');
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('hostel_gate_pass', 'can_delete')) {
            access_denied();
        }

        $this->hostelgatepass_model->delete($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Gate pass deleted successfully.</div>');
        redirect('admin/hostelgatepass');
    }
    public function print_pass($id)
    {
        if (!$this->rbac->hasPrivilege('hostel_gate_pass', 'can_view')) {
            access_denied();
        }

        $gatepass = $this->hostelgatepass_model->get($id);
        if (!$gatepass) {
            echo "Gate pass not found.";
            return;
        }
        
        $this->load->model('setting_model');
        $settinglist = $this->setting_model->get();

        $data['settinglist'] = $settinglist;
        $data['gatepass'] = $gatepass;
        
        $this->load->view('admin/hostel/print_gatepass', $data);
    }
}
