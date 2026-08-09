<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Workexperience extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('workexperience_model');
    }

    public function workexperience()
    {
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/workexperience/workexperience');

        $workexperienceid = $this->input->post("workexperienceid");
        $data["workexperience_list"] = $this->workexperience_model->get();

        $this->form_validation->set_rules('type', 'Work Experience Title', 'trim|required|xss_clean');
        $data["title"] = 'Add Work Experience';

        if ($this->form_validation->run()) {
            $type = $this->input->post("type");
            $workexperienceid = $this->input->post("workexperienceid");

            if (!empty($workexperienceid)) {
                $data = array('work_experience' => $type, 'is_active' => 'yes', 'id' => $workexperienceid);
            } else {
                $data = array('work_experience' => $type, 'is_active' => 'yes');
            }
            $this->workexperience_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Work Experience saved successfully</div>');
            redirect("admin/workexperience/workexperience");
        } else {
            $this->load->view("layout/header");
            $this->load->view("admin/staff/workexperience", $data);
            $this->load->view("layout/footer");
        }
    }

    public function workexperienceedit($id)
    {
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/workexperience/workexperience');

        $data["id"] = $id;
        $workexperience = $this->workexperience_model->get($id);
        $data["workexperience"] = $workexperience;

        $data["workexperience_list"] = $this->workexperience_model->get();
        $this->load->view("layout/header");
        $this->load->view("admin/staff/workexperienceedit", $data);
        $this->load->view("layout/footer");
    }

    public function workexperiencedelete($id)
    {
        $this->workexperience_model->deleteWorkExperience($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-success">Work Experience deleted successfully</div>');
        redirect("admin/workexperience/workexperience");
    }
}
