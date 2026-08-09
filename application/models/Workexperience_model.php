<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Workexperience_model extends MY_model
{
    public function __construct()
    {
        parent::__construct();
        $this->check_table();
    }

    private function check_table()
    {
        if (!$this->db->table_exists('staff_work_experience')) {
            $this->db->query("CREATE TABLE IF NOT EXISTS `staff_work_experience` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `work_experience` varchar(255) NOT NULL,
              `is_active` varchar(10) NOT NULL DEFAULT 'yes',
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

            $defaults = array(
                'Fresher (0 Years)', '1-3 Years', '3-5 Years', '5-10 Years', '10+ Years'
            );
            foreach ($defaults as $e) {
                $this->db->insert('staff_work_experience', array('work_experience' => $e, 'is_active' => 'yes'));
            }
        }
    }

    public function get($id = null)
    {
        if (!empty($id)) {
            $query = $this->db->where("id", $id)->get("staff_work_experience");
            return $query->row_array();
        } else {
            $query = $this->db->where("is_active", "yes")->get("staff_work_experience");
            return $query->result_array();
        }
    }

    public function add($data)
    {
        if (isset($data['id']) && $data['id'] != 0) {
            $this->db->where('id', $data['id']);
            $this->db->update('staff_work_experience', $data);
        } else {
            $this->db->insert('staff_work_experience', $data);
        }
    }

    public function deleteWorkExperience($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('staff_work_experience');
    }
}
