<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class FeeCategory_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * This funtion takes id as a parameter and will fetch the record.
     * If id is not provided, then it will fetch all the records form the table.
     * @param int $id
     * @return mixed
     */
    public function get($id = null, $order = "desc")
    {
        if (!$this->db->table_exists('feecategory')) {
            if ($this->db->table_exists('fee_groups')) {
                $this->db->select('id, name as category')->from('fee_groups');
                if ($id != null) {
                    $this->db->where('id', $id);
                    $row = $this->db->get()->row_array();
                    return $row ?: [];
                }
                $res = $this->db->get()->result_array();
                return $res ?: [];
            }
            return $id != null ? [] : [];
        }

        $this->db->select()->from('feecategory');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id ' . $order);
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getbyasc($id = null)
    {
        if (!$this->db->table_exists('feecategory')) {
            return $this->get($id, 'asc');
        }

        $this->db->select()->from('feecategory');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    /**
     * This function will delete the record based on the id
     * @param $id
     */
    public function remove($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('feecategory');
    }

    /**
     * This function will take the post data passed from the controller
     * If id is present, then it will do an update
     * else an insert. One function doing both add and edit.
     * @param $data
     */
    public function add($data)
    {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('feecategory', $data);
        } else {
            $this->db->insert('feecategory', $data);
            return $this->db->insert_id();
        }
    }

}
