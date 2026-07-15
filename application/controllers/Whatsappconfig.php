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
		
		 
    }

    public function index()
    {         
        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'whatsappconfig/index');
        $data['title']      = 'SMS Config List';
        $result         = $this->whatsappconfig_model->get();
        $data['statuslist'] = $this->customlib->getStatus();
        $data['list']    = $result;
        $this->load->view('layout/header', $data);
        $this->load->view('whatsappconfig/index', $data);
        $this->load->view('layout/footer', $data);
    }

    public function twilio()
    {
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('twilio_account_sid', $this->lang->line('twilio_account_sid'), 'required');
        $this->form_validation->set_rules('twilio_auth_token', $this->lang->line('authentication_token'), 'required');
        $this->form_validation->set_rules('twilio_sender_phone_number', $this->lang->line('registered_phone_number'), 'required');
        $this->form_validation->set_rules('twilio_status', $this->lang->line('status'), 'required');

        if ($this->form_validation->run()) {

            $data = array(
                'type'      => 'twilio',
                'username'  => $this->input->post('twilio_account_sid'),
                'password'  => $this->input->post('twilio_auth_token'),
                'contact'   => $this->input->post('twilio_sender_phone_number'),
                'is_active' => $this->input->post('twilio_status'),
            );
            $this->whatsappconfig_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));
        } else {

            $data = array(
                'twilio_account_sid'         => form_error('twilio_account_sid'),
                'twilio_auth_token'          => form_error('twilio_auth_token'),
                'twilio_sender_phone_number' => form_error('twilio_sender_phone_number'),
                'twilio_status'              => form_error('twilio_status'),
            );

            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }


    public function metawhatsapp()
    {
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('meta_access_token', "Access Token", 'required');
		$this->form_validation->set_rules('meta_sender_phone_number', $this->lang->line('registered_phone_number'), 'required');
        $this->form_validation->set_rules('meta_language', $this->lang->line('language'), 'required');        
        $this->form_validation->set_rules('meta_status', $this->lang->line('status'), 'required');

        if ($this->form_validation->run()) {

            $data = array(
                'type'      => 'meta',
                'language'  => $this->input->post('meta_language'),
                'authkey'  => $this->input->post('meta_access_token'),
                'contact'   => $this->input->post('meta_sender_phone_number'),
                'is_active' => $this->input->post('meta_status'),
            );
            $this->whatsappconfig_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));
        } else {

            $data = array(
                'meta_language'         		=> form_error('meta_language'),
                'meta_access_token'          	=> form_error('meta_access_token'),
                'meta_sender_phone_number' 		=> form_error('meta_sender_phone_number'),
                'meta_status'              		=> form_error('meta_status'),
            );

            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

     

    public function richautomate()
    {
        $this->form_validation->set_error_delimiters('', '');

        $this->form_validation->set_rules('richautomate_api_key', "API Key", 'required');
        $this->form_validation->set_rules('richautomate_sender_phone_number', $this->lang->line('registered_phone_number'), 'required');
        $this->form_validation->set_rules('richautomate_language', $this->lang->line('language'), 'required');        
        $this->form_validation->set_rules('richautomate_status', $this->lang->line('status'), 'required');

        if ($this->form_validation->run()) {

            $data = array(
                'type'      => 'richautomate',
                'language'  => $this->input->post('richautomate_language'),
                'authkey'  => $this->input->post('richautomate_api_key'),
                'contact'   => $this->input->post('richautomate_sender_phone_number'),
                'is_active' => $this->input->post('richautomate_status'),
            );
            $this->whatsappconfig_model->add($data);
            echo json_encode(array('st' => 0, 'msg' => $this->lang->line('update_message')));
        } else {

            $data = array(
                'richautomate_language'         		=> form_error('richautomate_language'),
                'richautomate_api_key'          	=> form_error('richautomate_api_key'),
                'richautomate_sender_phone_number' 		=> form_error('richautomate_sender_phone_number'),
                'richautomate_status'              		=> form_error('richautomate_status'),
            );

            echo json_encode(array('st' => 1, 'msg' => $data));
        }
    }

}