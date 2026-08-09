<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Qualification extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('qualification_model');
    }

    public function qualification()
    {
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/qualification/qualification');

        $qualificationid = $this->input->post("qualificationid");
        $data["qualification_list"] = $this->qualification_model->get();

        $this->form_validation->set_rules('type', 'Qualification Name', 'trim|required|xss_clean');
        $data["title"] = 'Add Qualification';

        if ($this->form_validation->run()) {
            $type = $this->input->post("type");
            $qualificationid = $this->input->post("qualificationid");

            if (!empty($qualificationid)) {
                $data = array('qualification_name' => $type, 'is_active' => 'yes', 'id' => $qualificationid);
            } else {
                $data = array('qualification_name' => $type, 'is_active' => 'yes');
            }
            $this->qualification_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Qualification saved successfully</div>');
            redirect("admin/qualification/qualification");
        } else {
            $this->load->view("layout/header");
            $this->load->view("admin/staff/qualification", $data);
            $this->load->view("layout/footer");
        }
    }

    public function qualificationedit($id)
    {
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/qualification/qualification');

        $data["id"] = $id;
        $qualification = $this->qualification_model->get($id);
        $data["qualification"] = $qualification;

        $data["qualification_list"] = $this->qualification_model->get();
        $this->load->view("layout/header");
        $this->load->view("admin/staff/qualificationedit", $data);
        $this->load->view("layout/footer");
    }

    public function qualificationdelete($id)
    {
        $this->qualification_model->deleteQualification($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-success">Qualification deleted successfully</div>');
        redirect("admin/qualification/qualification");
    }
}
