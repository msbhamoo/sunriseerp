<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Accexpensetype_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function add($data)
    {
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);

        if (isset($data['id']) && $data['id'] > 0) {
            $this->db->where('id', $data['id']);
            $this->db->update('acc_expense_types', $data);
            $insert_id = $data['id'];
        } else {
            $this->db->insert('acc_expense_types', $data);
            $insert_id = $this->db->insert_id();
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        }
        return $insert_id;
    }

    public function get($id = null)
    {
        $this->db->select('*');
        $this->db->from('acc_expense_types');
        if ($id != null) {
            $this->db->where('id', $id);
            $query = $this->db->get();
            return $query->row_array();
        } else {
            $this->db->order_by('name', 'asc');
            $query = $this->db->get();
            return $query->result_array();
        }
    }

    public function getByType($type)
    {
        $this->db->select('*');
        $this->db->from('acc_expense_types');
        $this->db->where('type', $type);
        $this->db->where('is_active', 1);
        $this->db->order_by('name', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('acc_expense_types');
    }

    public function getDatatableExpenseTypes()
    {
        $this->datatables
            ->select('id, name, type, is_active')
            ->from('acc_expense_types')
            ->searchable('name, type')
            ->orderable('name, type')
            ->sort('id', 'desc');

        return $this->datatables->generate('json');
    }
}
