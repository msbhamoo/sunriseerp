<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class SystemAlerts extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('SystemNotification_model');
    }

    public function get_alerts()
    {
        $userdata = $this->customlib->getUserData();
        $staff_id = $userdata["id"];
        
        $getStaffRole = $this->customlib->getStaffRole();
        $staffrole = json_decode($getStaffRole);
        $role_id = $staffrole->id;
        
        $count = $this->SystemNotification_model->getUnreadCount($staff_id, $role_id);
        $alerts = $this->SystemNotification_model->getAllNotifications($staff_id, $role_id);
        
        $grouped_alerts = array(
            'today' => array(),
            'yesterday' => array(),
            'last_week' => array(),
            'last_month' => array()
        );
        
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 days'));
        $last_week = date('Y-m-d', strtotime('-7 days'));
        
        foreach ($alerts as $alert) {
            $alert_date = date('Y-m-d', strtotime($alert['created_at']));
            if ($alert_date == $today) {
                $grouped_alerts['today'][] = $alert;
            } else if ($alert_date == $yesterday) {
                $grouped_alerts['yesterday'][] = $alert;
            } else if ($alert_date >= $last_week) {
                $grouped_alerts['last_week'][] = $alert;
            } else {
                $grouped_alerts['last_month'][] = $alert;
            }
        }
        
        $response = array(
            'count' => $count,
            'grouped_alerts' => $grouped_alerts
        );
        
        echo json_encode($response);
    }

    public function mark_as_read()
    {
        $notification_id = $this->input->post('id');
        if (!empty($notification_id)) {
            $this->SystemNotification_model->markAsRead($notification_id);
            echo json_encode(array('status' => 'success'));
        } else {
            echo json_encode(array('status' => 'fail'));
        }
    }

    public function mark_all_as_read()
    {
        $userdata = $this->customlib->getUserData();
        $staff_id = $userdata["id"];
        
        $getStaffRole = $this->customlib->getStaffRole();
        $staffrole = json_decode($getStaffRole);
        $role_id = $staffrole->id;
        
        $this->SystemNotification_model->markAllAsRead($staff_id, $role_id);
        echo json_encode(array('status' => 'success'));
    }
}
