<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Aisetting extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('setting_model');
    }

    /**
     * Dedicated AI Integration & API Keys Settings Page
     */
    public function index()
    {
        if (!$this->rbac->hasPrivilege('general_setting', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'admin/aisetting');
        $this->session->set_userdata('inner_menu', 'admin/aisetting');

        $setting = $this->setting_model->getSetting();
        $data['result'] = $setting;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/setting/ai_setting', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * AJAX Endpoint: Save AI Integration Settings
     */
    public function save_ajax()
    {
        if (!$this->rbac->hasPrivilege('general_setting', 'can_edit')) {
            echo json_encode(['status' => 'fail', 'message' => 'Access Denied']);
            return;
        }

        $setting = $this->setting_model->getSetting();
        $data = [
            'id'                     => $setting->id,
            'ai_gemini_api_key'      => trim($this->input->post('ai_gemini_api_key')),
            'ai_groq_api_key'        => trim($this->input->post('ai_groq_api_key')),
            'ai_openai_api_key'      => trim($this->input->post('ai_openai_api_key')),
            'ai_openrouter_api_key'  => trim($this->input->post('ai_openrouter_api_key')),
            'ai_default_model'       => trim($this->input->post('ai_default_model'))
        ];

        $this->setting_model->add($data);

        echo json_encode([
            'status'  => 'success',
            'message' => 'AI Integration & API Keys updated and saved permanently!'
        ]);
    }
}
