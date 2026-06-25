<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Religion extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('student_religions', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'religion/index');
        $data['title']        = $this->lang->line('religion_list');
        $religion_result      = $this->religion_model->get();
        $data['religionlist'] = $religion_result;
        $this->load->view('layout/header', $data);
        $this->load->view('religion/religionList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function view($id)
    {
        if (!$this->rbac->hasPrivilege('student_religions', 'can_view')) {
            access_denied();
        }
        $data['title']    = $this->lang->line('religion_list');
        $religion         = $this->religion_model->get($id);
        $data['religion'] = $religion;
        $this->load->view('layout/header', $data);
        $this->load->view('religion/religionShow', $data);
        $this->load->view('layout/footer', $data);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('student_religions', 'can_delete')) {
            access_denied();
        }
        $data['title'] = $this->lang->line('religion_list');
        $this->religion_model->remove($id);
        $this->session->set_flashdata('msgdelete', '<div class="alert alert-success text-left">' . $this->lang->line('delete_message') . '</div>');
        redirect('religion/index');
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('student_religions', 'can_add')) {
            access_denied();
        }
        $data['title']        = $this->lang->line('add_religion');
        $religion_result      = $this->religion_model->get();
        $data['religionlist'] = $religion_result;
        $this->form_validation->set_rules('religion', $this->lang->line('religion'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('religion/religionList', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'religion' => $this->input->post('religion'),
            );
            $this->religion_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('religion/index');
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('student_religions', 'can_edit')) {
            access_denied();
        }
        $data['title']        = $this->lang->line('edit_religion');
        $religion_result      = $this->religion_model->get();
        $data['religionlist'] = $religion_result;
        $data['id']           = $id;
        $religion             = $this->religion_model->get($id);
        $data['religion']     = $religion;
        $this->form_validation->set_rules('religion', $this->lang->line('religion'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('religion/religionEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'       => $id,
                'religion' => $this->input->post('religion'),
            );
            $this->religion_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('religion/index');
        }
    }

}

