<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Callpurpose extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("studentcall_model");
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('call_purpose_setup', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'admin/callpurpose');
        $data['purpose_list'] = $this->studentcall_model->get_purposes();
        $this->load->view('layout/header');
        $this->load->view('admin/studentcall/purpose', $data);
        $this->load->view('layout/footer');
    }

    public function add()
    {
        if (!$this->rbac->hasPrivilege('call_purpose_setup', 'can_add')) {
            access_denied();
        }
        $this->form_validation->set_rules('purpose', $this->lang->line('purpose'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'purpose' => form_error('purpose'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $data = array(
                'purpose'   => $this->input->post('purpose'),
            );
            $this->studentcall_model->add_purpose($data);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('call_purpose_setup', 'can_edit')) {
            access_denied();
        }
        $data['purpose'] = $this->studentcall_model->get_purpose($id);
        echo json_encode($data['purpose']);
    }

    public function update()
    {
        if (!$this->rbac->hasPrivilege('call_purpose_setup', 'can_edit')) {
            access_denied();
        }
        $this->form_validation->set_rules('purpose', $this->lang->line('purpose'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $data = array(
                'purpose' => form_error('purpose'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $id   = $this->input->post('id');
            $data = array(
                'purpose'   => $this->input->post('purpose'),
            );
            $this->studentcall_model->update_purpose($id, $data);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('update_message'));
            echo json_encode($array);
        }
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('call_purpose_setup', 'can_delete')) {
            access_denied();
        }
        $this->studentcall_model->delete_purpose($id);
        echo json_encode(array('status' => 'success', 'message' => $this->lang->line('delete_message')));
    }
}
