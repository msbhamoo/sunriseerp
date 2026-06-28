<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Gatepass extends Student_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('gatepass_model');
    }

    public function index()
    {
        $this->session->set_userdata('top_menu', 'gatepass');
        $student_id = $this->customlib->getStudentSessionUserID();
        $student_current_class = $this->customlib->getStudentCurrentClsSection();
        
        $data['title'] = 'My Gate Pass';
        // The student ID in session is actually the student_session ID, wait... 
        // customlib->getStudentSessionUserID() usually returns students.id. Let's make sure.
        
        $data['gate_passes'] = $this->gatepass_model->getByUser('student', $student_id);

        $this->load->view('layout/student/header', $data);
        $this->load->view('user/gatepass/index', $data);
        $this->load->view('layout/student/footer', $data);
    }

    public function apply()
    {
        $this->form_validation->set_rules('date', 'Date', 'required');
        $this->form_validation->set_rules('out_time', 'Out Time', 'required');
        $this->form_validation->set_rules('reason', 'Reason', 'required');

        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'date' => form_error('date'),
                'out_time' => form_error('out_time'),
                'reason' => form_error('reason')
            );
            echo json_encode(array('status' => 'fail', 'error' => $msg, 'message' => ''));
        } else {
            $student_id = $this->customlib->getStudentSessionUserID();
            $gate_pass_no = $this->gatepass_model->generate_gate_pass_no();

            $data = array(
                'gate_pass_no' => $gate_pass_no,
                'user_type' => 'student',
                'user_id' => $student_id,
                'date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'out_time' => $this->input->post('out_time'),
                'reason' => $this->input->post('reason'),
                'status' => 'Pending',
            );

            $this->gatepass_model->add($data);

            echo json_encode(array('status' => 'success', 'message' => $this->lang->line('success_message')));
        }
    }
}
