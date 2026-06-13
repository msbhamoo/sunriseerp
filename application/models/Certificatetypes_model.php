<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Certificatetypes_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get($id = null) {
        $this->db->select('*');
        $this->db->from('student_certificate_types');
        if ($id != null) {
            $this->db->where('id', $id);
            return $this->db->get()->row_array();
        }
        $this->db->where('is_active', 1);
        $this->db->order_by('id', 'asc');
        return $this->db->get()->result_array();
    }

    public function get_next_certificate_number($type_id) {
        $type = $this->get($type_id);
        if (empty($type)) return null;
        
        $next_num = max($type['start_number'], $type['current_number'] + 1);
        return $type['series_prefix'] . sprintf('%03d', $next_num);
    }

    public function update_current_number($type_id, $new_current_number) {
        $this->db->where('id', $type_id);
        $this->db->update('student_certificate_types', ['current_number' => $new_current_number]);
    }

    public function add($data) {
        $this->db->insert('student_certificate_types', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data) {
        $this->db->where('id', $id);
        $this->db->update('student_certificate_types', $data);
    }
}
