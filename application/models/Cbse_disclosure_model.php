<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Cbse_disclosure_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->ensure_table_exists();
    }

    public function ensure_table_exists()
    {
        if (!$this->db->table_exists('cbse_mandatory_disclosures')) {
            $query = "CREATE TABLE IF NOT EXISTS `cbse_mandatory_disclosures` (
              `id` INT(11) NOT NULL AUTO_INCREMENT,
              `section` VARCHAR(50) NOT NULL,
              `field_key` VARCHAR(100) NOT NULL,
              `field_value` TEXT NULL,
              `file_path` VARCHAR(255) NULL,
              `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
              PRIMARY KEY (`id`),
              UNIQUE KEY `unique_section_key` (`section`, `field_key`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            $this->db->query($query);
        }
    }

    public function get_all_disclosures()
    {
        $this->ensure_table_exists();
        $query = $this->db->get('cbse_mandatory_disclosures');
        $results = $query->result_array();
        
        $data = array(
            'general_info' => array(),
            'documents' => array(),
            'results_x' => array(),
            'results_xii' => array(),
            'staff' => array(),
            'infrastructure' => array()
        );

        foreach ($results as $row) {
            $section = $row['section'];
            $key = $row['field_key'];
            if (!isset($data[$section])) {
                $data[$section] = array();
            }
            $data[$section][$key] = array(
                'value' => $row['field_value'],
                'file_path' => $row['file_path']
            );
        }

        return $data;
    }

    public function save_field($section, $field_key, $field_value, $file_path = null)
    {
        $this->ensure_table_exists();
        $existing = $this->db->get_where('cbse_mandatory_disclosures', array(
            'section' => $section,
            'field_key' => $field_key
        ))->row_array();

        $data = array(
            'section' => $section,
            'field_key' => $field_key,
            'field_value' => $field_value
        );

        if ($file_path !== null) {
            $data['file_path'] = $file_path;
        }

        if ($existing) {
            $this->db->where('id', $existing['id']);
            $this->db->update('cbse_mandatory_disclosures', $data);
        } else {
            $this->db->insert('cbse_mandatory_disclosures', $data);
        }
    }

    public function get_section_fields($section)
    {
        $this->ensure_table_exists();
        $query = $this->db->get_where('cbse_mandatory_disclosures', array('section' => $section));
        return $query->result_array();
    }
}
