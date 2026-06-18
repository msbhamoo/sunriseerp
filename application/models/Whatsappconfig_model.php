<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Whatsappconfig_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get($id = null)
    {
        $this->db->select()->from('whatsapp_config');
        if ($id != null) {
            $this->db->where('id', $id);
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row();
        } else {
            return $query->result();
        }
    }

    public function getActiveWhatsApp()
    {
        $this->db->select()->from('whatsapp_config');
        $this->db->where('is_active', 'enabled');
        $query = $this->db->get();
        return $query->row();
    }

    public function add($data)
    {
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);

        if (isset($data['id']) && $data['id'] != '') {
            $this->db->where('id', $data['id']);
            $this->db->update('whatsapp_config', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On whatsapp config id " . $data['id'];
            $action    = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('whatsapp_config', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On whatsapp config id " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            $this->db->trans_commit();
            return TRUE;
        }
    }

    public function updateActiveWhatsApp($api_engine)
    {
        // First deactivate all
        $data = array('is_active' => 'disabled');
        $this->db->update('whatsapp_config', $data);

        // Then activate the specific one
        $data_active = array('is_active' => 'enabled');
        $this->db->where('api_engine', $api_engine);
        $this->db->update('whatsapp_config', $data_active);
    }
}
