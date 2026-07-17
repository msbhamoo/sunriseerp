<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Systemnotificationsetting extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('SystemNotificationSetting_model');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('general_setting', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'System Settings');
        $this->session->set_userdata('sub_menu', 'admin/systemnotificationsetting');
        $this->session->set_userdata('inner_menu', 'admin/systemnotificationsetting');
        
        $data = array();
        
        // Define all system notification settings
        $settings = array(
            'enquiry_added' => 'Enquiry Added',
            'enquiry_converted' => 'Enquiry Converted',
            'gatepass_issued' => 'Gate Pass Issued',
            'student_call_log' => 'Student Call Log / Follow-up',
            'vehicle_alerts' => 'Vehicle Alerts & Reminders'
        );
        
        $current_settings = $this->SystemNotificationSetting_model->get();
        $setting_values = array();
        foreach($current_settings as $s) {
            $setting_values[$s['type']] = $s['is_active'];
        }
        
        $formatted_settings = array();
        foreach($settings as $key => $title) {
            $formatted_settings[] = array(
                'type' => $key,
                'title' => $title,
                'is_active' => isset($setting_values[$key]) ? $setting_values[$key] : 'no'
            );
        }
        
        $data['settings'] = $formatted_settings;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/setting/system_notification_setting', $data);
        $this->load->view('layout/footer', $data);
    }

    public function change_status()
    {
        if (!$this->rbac->hasPrivilege('general_setting', 'can_edit')) {
            access_denied();
        }
        
        $type = $this->input->post('type');
        $is_active = $this->input->post('is_active');
        
        $data = array(
            'type' => $type,
            'is_active' => $is_active == '1' ? 'yes' : 'no'
        );
        
        $this->SystemNotificationSetting_model->update($data);
        
        $response = array('status' => 'success', 'msg' => $this->lang->line('update_message'));
        echo json_encode($response);
    }
}
