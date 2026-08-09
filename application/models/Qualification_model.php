<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Qualification_model extends MY_model
{
    public function __construct()
    {
        parent::__construct();
        $this->check_table();
    }

    private function check_table()
    {
        if (!$this->db->table_exists('staff_qualification')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `staff_qualification` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `qualification_name` varchar(255) NOT NULL,
              `is_active` varchar(10) NOT NULL DEFAULT 'yes',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

            $defaults = array(
                'B.Tech', 'M.Tech', 'B.Ed', 'M.Sc', 'B.Sc', 'Ph.D', 'TGT Teacher', 'PGT Teacher', 'Post Graduate', 'Graduate', 'Secondary (10th)', 'Higher Secondary (12th)'
            );
            foreach ($defaults as $q) {
                $this->db->insert('staff_qualification', array('qualification_name' => $q, 'is_active' => 'yes'));
            }
        }
    }

    public function get($id = null)
    {
        if (!empty($id)) {
            $query = $this->db->where("id", $id)->get("staff_qualification");
            return $query->row_array();
        } else {
            $query = $this->db->where("is_active", "yes")->get("staff_qualification");
            return $query->result_array();
        }
    }

    public function add($data)
    {
        if (isset($data['id']) && $data['id'] != 0) {
            $this->db->where('id', $data['id']);
            $this->db->update('staff_qualification', $data);
        } else {
            $this->db->insert('staff_qualification', $data);
        }
    }

    public function deleteQualification($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('staff_qualification');
    }
}
