<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Cast extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('student_casts', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'cast/index');
        $data['title']        = $this->lang->line('cast_list');
        $cast_result      = $this->cast_model->get();
        $data['castlist'] = $cast_result;
        $this->load->view('layout/header', $data);
        $this->load->view('cast/castList', $data);
        $this->load->view('layout/footer', $data);
    }

    public function view($id)
    {
        if (!$this->rbac->hasPrivilege('student_casts', 'can_view')) {
            access_denied();
        }
        $data['title']    = $this->lang->line('cast_list');
        $cast         = $this->cast_model->get($id);
        $data['cast'] = $cast;
        $this->load->view('layout/header', $data);
        $this->load->view('cast/castShow', $data);
        $this->load->view('layout/footer', $data);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('student_casts', 'can_delete')) {
            access_denied();
        }
        $data['title'] = $this->lang->line('cast_list');
        $this->cast_model->remove($id);
        $this->session->set_flashdata('msgdelete', '<div class="alert alert-success text-left">' . $this->lang->line('delete_message') . '</div>');
        redirect('cast/index');
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('student_casts', 'can_add')) {
            access_denied();
        }
        $data['title']        = $this->lang->line('add_cast');
        $cast_result      = $this->cast_model->get();
        $data['castlist'] = $cast_result;
        $this->form_validation->set_rules('cast', $this->lang->line('cast'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('cast/castList', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'cast' => $this->input->post('cast'),
            );
            $this->cast_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('cast/index');
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('student_casts', 'can_edit')) {
            access_denied();
        }
        $data['title']        = $this->lang->line('edit_cast');
        $cast_result      = $this->cast_model->get();
        $data['castlist'] = $cast_result;
        $data['id']           = $id;
        $cast             = $this->cast_model->get($id);
        $data['cast']     = $cast;
        $this->form_validation->set_rules('cast', $this->lang->line('cast'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('cast/castEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $data = array(
                'id'       => $id,
                'cast' => $this->input->post('cast'),
            );
            $this->cast_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('update_message') . '</div>');
            redirect('cast/index');
        }
    }

}

