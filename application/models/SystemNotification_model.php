<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class SystemNotification_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addNotification($data)
    {
        $this->db->insert('system_notifications', $data);
        return $this->db->insert_id();
    }

    public function notifyRole($role_id, $title, $message, $action_url = '')
    {
        $data = array(
            'title' => $title,
            'message' => $message,
            'action_url' => $action_url,
            'target_role' => $role_id,
            'target_user' => null,
            'is_read' => 0
        );
        return $this->addNotification($data);
    }

    public function notifyUser($user_id, $title, $message, $action_url = '')
    {
        $data = array(
            'title' => $title,
            'message' => $message,
            'action_url' => $action_url,
            'target_role' => null,
            'target_user' => $user_id,
            'is_read' => 0
        );
        return $this->addNotification($data);
    }

    public function getAllNotifications($staff_id, $role_id)
    {
        $this->db->select('*');
        $this->db->from('system_notifications');
        
        $this->db->group_start();
        $this->db->where('target_user', $staff_id);
        $this->db->or_where('target_role', $role_id);
        $this->db->group_end();
        
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit(50);
        
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getUnreadCount($staff_id, $role_id)
    {
        $this->db->select('count(id) as total');
        $this->db->from('system_notifications');
        $this->db->where('is_read', 0);
        
        $this->db->group_start();
        $this->db->where('target_user', $staff_id);
        $this->db->or_where('target_role', $role_id);
        $this->db->group_end();
        
        $query = $this->db->get();
        $result = $query->row_array();
        return $result['total'];
    }

    public function markAsRead($notification_id)
    {
        $this->db->where('id', $notification_id);
        $this->db->update('system_notifications', array('is_read' => 1));
    }

    public function markAllAsRead($staff_id, $role_id)
    {
        $this->db->where('is_read', 0);
        $this->db->group_start();
        $this->db->where('target_user', $staff_id);
        $this->db->or_where('target_role', $role_id);
        $this->db->group_end();
        $this->db->update('system_notifications', array('is_read' => 1));
    }
}
