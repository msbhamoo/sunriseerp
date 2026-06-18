<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Whatsappconfig extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('whatsappconfig_model');
        $this->load->library('whatsappgateway');
    }

    public function index()
    {
        // We use sms_setting privilege as a fallback if whatsapp_setting isn't defined
        if (!$this->rbac->hasPrivilege('sms_setting', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'admin/whatsappconfig');
        $data['title']      = 'WhatsApp Config';
        
        $whatsapp_result = $this->whatsappconfig_model->get();
        if (empty($whatsapp_result)) {
            // Create a default one if it doesn't exist
            $data_insert = array('api_engine' => 'easyapp', 'is_active' => 'no', 'api_key' => '');
            $this->whatsappconfig_model->add($data_insert);
            $whatsapp_result = $this->whatsappconfig_model->get();
        }

        $data['statuslist'] = $this->customlib->getStatus();
        $data['whatsapplist'] = $whatsapp_result;
        $this->load->view('layout/header', $data);
        $this->load->view('whatsappconfig/waIndex', $data); 
        $this->load->view('layout/footer', $data);
    }

    public function easyapp()
    {
        $this->form_validation->set_error_delimiters('', '');
        $this->form_validation->set_rules('easyapp_auth_key', 'Auth Key', 'required');
        $this->form_validation->set_rules('easyapp_status', $this->lang->line('status'), 'required');

        if ($this->form_validation->run()) {
            // Find existing row
            $existing = $this->db->get_where('whatsapp_config', array('api_engine' => 'easyapp'))->row();
            
            $data = array(
                'api_engine'=> 'easyapp',
                'api_key'   => $this->input->post('easyapp_auth_key'),
                'is_active' => $this->input->post('easyapp_status'),
            );

            if ($existing) {
                $data['id'] = $existing->id;
            }

            $this->whatsappconfig_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));
        } else {
            $data = array(
                'easyapp_auth_key' => form_error('easyapp_auth_key'),
                'easyapp_status'   => form_error('easyapp_status'),
            );
            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

    public function test_message()
    {
        $mobile = $this->input->post('mobile');
        $message = "Test message from System API: " . date('Y-m-d H:i:s');

        $result = $this->whatsappgateway->testMessage($mobile, $message);
        
        if ($result) {
            echo json_encode(array('status' => 'success', 'msg' => 'Message sent. API Response: ' . $result));
        } else {
            echo json_encode(array('status' => 'fail', 'msg' => 'Failed to connect or invalid response.'));
        }
    }

    public function check_status()
    {
        $dummy = "1234567890";
        $result = $this->whatsappgateway->testMessage($dummy, 'Test API Status');
        
        if ($result) {
            echo json_encode(array('status' => 'success', 'msg' => 'API is reachable. Response: ' . $result));
        } else {
            echo json_encode(array('status' => 'fail', 'msg' => 'API Unreachable. Please check your credentials or network.'));
        }
    }
}
