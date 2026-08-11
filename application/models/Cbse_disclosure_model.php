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
                'id' => $row['id'],
                'value' => $row['field_value'],
                'file_path' => $row['file_path'],
                'is_custom' => (strpos($key, 'custom_doc_') === 0) ? 1 : 0
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

    public function add_custom_document($section, $title, $file_path)
    {
        $this->ensure_table_exists();
        $field_key = 'custom_doc_' . uniqid() . '_' . time();
        $data = array(
            'section'     => $section,
            'field_key'   => $field_key,
            'field_value' => $title,
            'file_path'   => $file_path
        );
        $this->db->insert('cbse_mandatory_disclosures', $data);
        return $this->db->insert_id();
    }

    public function get_custom_document($id)
    {
        $this->ensure_table_exists();
        return $this->db->get_where('cbse_mandatory_disclosures', array('id' => $id))->row_array();
    }

    public function delete_custom_document($id)
    {
        $this->ensure_table_exists();
        $doc = $this->get_custom_document($id);
        if ($doc) {
            if (!empty($doc['file_path']) && file_exists(FCPATH . $doc['file_path'])) {
                @unlink(FCPATH . $doc['file_path']);
            }
            $this->db->where('id', $id);
            $this->db->delete('cbse_mandatory_disclosures');
            return true;
        }
        return false;
    }

    public function get_section_fields($section)
    {
        $this->ensure_table_exists();
        $query = $this->db->get_where('cbse_mandatory_disclosures', array('section' => $section));
        return $query->result_array();
    }
}
