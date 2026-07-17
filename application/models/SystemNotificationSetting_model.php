<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class SystemNotificationSetting_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($type = null)
    {
        $this->db->select('*');
        $this->db->from('system_notification_setting');
        if ($type != null) {
            $this->db->where('type', $type);
            $query = $this->db->get();
            return $query->row_array();
        } else {
            $query = $this->db->get();
            return $query->result_array();
        }
    }

    public function update($data)
    {
        $this->db->select('*');
        $this->db->from('system_notification_setting');
        $this->db->where('type', $data['type']);
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            $result = $q->row_array();
            $this->db->where('id', $result['id']);
            $this->db->update('system_notification_setting', array('is_active' => $data['is_active']));
        } else {
            $this->db->insert('system_notification_setting', $data);
        }
    }

    public function check_setting($type)
    {
        $this->db->select('*');
        $this->db->from('system_notification_setting');
        $this->db->where('type', $type);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            if ($result['is_active'] == 'yes') {
                return true;
            }
        }
        return false;
    }
}
