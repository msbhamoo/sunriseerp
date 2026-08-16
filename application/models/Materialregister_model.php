<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Materialregister_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function add($data)
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);
        //=======================Code Start===========================
        $this->db->insert('material_register', $data);
        $id        = $this->db->insert_id();
        $message   = INSERT_RECORD_CONSTANT . " On material register id " . $id;
        $action    = "Insert";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        }
        return $id;
    }

    public function get($id = null, $direction = null)
    {
        $this->db->select('material_register.*,staff.name as staff_name,staff.surname as staff_surname,staff.employee_id as staff_employee_id')
            ->from('material_register');
        $this->db->join('staff', 'staff.id=material_register.staff_id', 'left');
        if ($id != null) {
            $this->db->where('material_register.id', $id);
        } else {
            if ($direction != null) {
                $this->db->where('material_register.direction', $direction);
            }
            $this->db->order_by('material_register.id', 'desc');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        }
        return $query->result_array();
    }

    // ---- Master lists (item / quantity / unit / party) for the dropdowns ----

    public function get_masters($type = null)
    {
        $this->db->from('material_masters');
        if ($type != null) {
            $this->db->where('type', $type);
        }
        $this->db->order_by('name', 'asc');
        return $this->db->get()->result_array();
    }

    // Insert a new master value if it does not already exist; returns the name.
    public function add_master($type, $name)
    {
        $name  = trim($name);
        $types = array('item', 'quantity', 'unit', 'party', 'department', 'vehicle', 'driver');
        if ($name === '' || !in_array($type, $types)) {
            return false;
        }
        $exists = $this->db->get_where('material_masters', array('type' => $type, 'name' => $name))->num_rows();
        if (!$exists) {
            $this->db->insert('material_masters', array('type' => $type, 'name' => $name));
        }
        return $name;
    }

    public function generate_gate_pass_no()
    {
        $this->db->select('gate_pass_no');
        $this->db->from('material_register');
        $this->db->where('gate_pass_no IS NOT NULL');
        $this->db->where('gate_pass_no !=', '');
        $this->db->order_by('id', 'desc');
        $this->db->limit(1);
        $query  = $this->db->get();
        $result = $query->row_array();

        if ($result && !empty($result['gate_pass_no'])) {
            $last_no = $result['gate_pass_no'];
            if (preg_match('/^([A-Za-z\-]+)?(\d+)$/', $last_no, $matches)) {
                $prefix = !empty($matches[1]) ? $matches[1] : 'MGP-';
                $num    = (int) $matches[2] + 1;
                $len    = max(4, strlen($matches[2]));
                return $prefix . sprintf('%0' . $len . 'd', $num);
            }
        }
        return 'MGP-0001';
    }

    public function ensure_sidebar_title()
    {
        $this->db->where('url', 'admin/materialregister');
        $this->db->update('sidebar_sub_menus', array(
            'menu'     => 'Material Register',
            'lang_key' => 'material_register'
        ));
    }

    public function update($id, $data)
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->update('material_register', $data);
        $message   = UPDATE_RECORD_CONSTANT . " On material register id " . $id;
        $action    = "Update";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        }
        return true;
    }

    public function delete($id)
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);
        //=======================Code Start===========================
        $this->db->where('id', $id);
        $this->db->delete('material_register');
        $message   = DELETE_RECORD_CONSTANT . " On material register id " . $id;
        $action    = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);
        //======================Code End==============================
        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        }
        return true;
    }
}
