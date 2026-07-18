<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Installmentreport_model extends MY_Model {

    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function get_plans($session_id = null) {
        if ($session_id == null) {
            $session_id = $this->current_session;
        }
        $this->db->select('report_installment_plans.*, classes.class as class_name');
        $this->db->from('report_installment_plans');
        $this->db->join('classes', 'classes.id = report_installment_plans.class_id', 'left');
        $this->db->where('report_installment_plans.session_id', $session_id);
        $this->db->order_by('report_installment_plans.id', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_plan($id) {
        $this->db->where('id', $id);
        $plan = $this->db->get('report_installment_plans')->row_array();
        if ($plan) {
            $plan['dates'] = $this->get_plan_dates($id);
            $plan['splits'] = $this->get_plan_splits($id);
        }
        return $plan;
    }

    public function get_plan_dates($plan_id) {
        $this->db->where('plan_id', $plan_id);
        $this->db->order_by('installment_number', 'ASC');
        return $this->db->get('report_installment_dates')->result_array();
    }

    public function get_plan_splits($plan_id) {
        $this->db->where('plan_id', $plan_id);
        return $this->db->get('report_installment_category_splits')->result_array();
    }

    public function add_plan($data, $dates, $splits) {
        $this->db->trans_start();

        if (isset($data['id']) && $data['id'] > 0) {
            $this->db->where('id', $data['id']);
            $this->db->update('report_installment_plans', $data);
            $plan_id = $data['id'];

            // Clear old dates and splits
            $this->db->where('plan_id', $plan_id);
            $this->db->delete('report_installment_dates');

            $this->db->where('plan_id', $plan_id);
            $this->db->delete('report_installment_category_splits');
        } else {
            $this->db->insert('report_installment_plans', $data);
            $plan_id = $this->db->insert_id();
        }

        if (!empty($dates)) {
            $insert_dates = array();
            foreach ($dates as $date) {
                $date['plan_id'] = $plan_id;
                $insert_dates[] = $date;
            }
            $this->db->insert_batch('report_installment_dates', $insert_dates);
        }

        if (!empty($splits)) {
            $insert_splits = array();
            foreach ($splits as $split) {
                $split['plan_id'] = $plan_id;
                $insert_splits[] = $split;
            }
            $this->db->insert_batch('report_installment_category_splits', $insert_splits);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        }
        return $plan_id;
    }

    public function remove_plan($id) {
        $this->db->trans_start();
        $this->db->where('id', $id);
        $this->db->delete('report_installment_plans');
        
        $this->db->where('plan_id', $id);
        $this->db->delete('report_installment_dates');
        
        $this->db->where('plan_id', $id);
        $this->db->delete('report_installment_category_splits');
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    // Get all available fee groups for selection
    public function get_available_fee_groups() {
        $this->db->select('id, name');
        $this->db->where('is_system', 0);
        return $this->db->get('fee_groups')->result_array();
    }

    public function get_available_hostel_groups() {
        $this->db->select('fee_groups.id, fee_groups.name');
        $this->db->from('hostel_fee_groups');
        $this->db->join('fee_groups', 'fee_groups.id = hostel_fee_groups.fee_groups_id');
        return $this->db->get()->result_array();
    }

    public function get_available_transport_yearly() {
        $this->db->select('transport_yearly_feemaster.id, transport_yearly_feemaster.amount, classes.class');
        $this->db->from('transport_yearly_feemaster');
        $this->db->join('classes', 'classes.id = transport_yearly_feemaster.class_id', 'left');
        $this->db->where('transport_yearly_feemaster.session_id', $this->current_session);
        return $this->db->get()->result_array();
    }

    // Find applicable plan for a class
    public function get_plan_by_class($class_id, $session_id = null) {
        if ($session_id == null) {
            $session_id = $this->current_session;
        }
        
        // Try class specific first
        $this->db->where('class_id', $class_id);
        $this->db->where('session_id', $session_id);
        $plan = $this->db->get('report_installment_plans')->row_array();
        
        if ($plan) {
            return $this->get_plan($plan['id']);
        }
        
        // Try global
        $this->db->group_start();
        $this->db->where('class_id', null);
        $this->db->or_where('class_id', 0);
        $this->db->or_where('class_id', '');
        $this->db->group_end();
        $this->db->where('session_id', $session_id);
        $plan = $this->db->get('report_installment_plans')->row_array();
        
        if ($plan) {
            return $this->get_plan($plan['id']);
        }
        
        return false;
    }
}