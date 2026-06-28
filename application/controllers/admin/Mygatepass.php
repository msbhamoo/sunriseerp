<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Mygatepass extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('gatepass_model');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('my_gate_pass', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/mygatepass');

        $staff_id = $this->customlib->getStaffID();
        
        $data['title'] = 'My Gate Pass';
        $data['gate_passes'] = $this->gatepass_model->getByUser('staff', $staff_id);

        $this->load->view('layout/header', $data);
        $this->load->view('admin/gatepass/mygatepass', $data);
        $this->load->view('layout/footer', $data);
    }

    public function apply()
    {
        if (!$this->rbac->hasPrivilege('my_gate_pass', 'can_add')) {
            access_denied();
        }

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
            $staff_id = $this->customlib->getStaffID();
            $gate_pass_no = $this->gatepass_model->generate_gate_pass_no();

            $data = array(
                'gate_pass_no' => $gate_pass_no,
                'user_type' => 'staff',
                'user_id' => $staff_id,
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
