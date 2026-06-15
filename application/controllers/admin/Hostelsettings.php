<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Hostelsettings extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Customlib');
        $this->load->model('feegroup_model');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('hostel_settings', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Hostel');
        $this->session->set_userdata('sub_menu', 'hostelsettings/index');

        $data['title'] = 'Hostel Settings';

        // Get all fee groups
        $fee_groups = $this->feegroup_model->get();
        
        // Get mapped hostel fee groups
        $hostel_fee_groups = $this->db->get('hostel_fee_groups')->result_array();
        $mapped_fee_group_ids = array_column($hostel_fee_groups, 'fee_groups_id');

        // Mark fee groups with 'is_hostel_fee' flag for the view
        foreach ($fee_groups as &$fg) {
            $fg['is_hostel_fee'] = in_array($fg['id'], $mapped_fee_group_ids);
        }
        $data['fee_groups'] = $fee_groups;

        // Get hostel asset items
        $data['asset_items'] = $this->db->get('hostel_asset_items')->result_array();

        // Get hostels and staff for warden assignment
        $this->load->model('hostel_model');
        $this->load->model('staff_model');
        $data['hostellist'] = $this->hostel_model->get();
        $data['staff_list'] = $this->staff_model->get();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/hostel/settings', $data);
        $this->load->view('layout/footer', $data);
    }

    public function save_fee_map()
    {
        if (!$this->rbac->hasPrivilege('hostel_settings', 'can_edit')) {
            access_denied();
        }

        $fee_group_ids = $this->input->post('fee_groups');
        
        // Clear all existing mapping
        $this->db->empty_table('hostel_fee_groups');

        if (!empty($fee_group_ids)) {
            $insert_data = [];
            foreach ($fee_group_ids as $fg_id) {
                $insert_data[] = [
                    'fee_groups_id' => $fg_id
                ];
            }
            $this->db->insert_batch('hostel_fee_groups', $insert_data);
        }
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
        redirect('admin/hostelsettings');
    }

    public function save_asset_item()
    {
        if (!$this->rbac->hasPrivilege('hostel_settings', 'can_edit')) {
            access_denied();
        }

        $item_name = $this->input->post('item_name');
        if (!empty($item_name)) {
            $this->db->insert('hostel_asset_items', ['item_name' => $item_name]);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Asset item added successfully</div>');
        } else {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Item name is required</div>');
        }
        redirect('admin/hostelsettings');
    }

    public function delete_asset_item($id)
    {
        if (!$this->rbac->hasPrivilege('hostel_settings', 'can_edit')) {
            access_denied();
        }

        $this->db->where('id', $id)->delete('hostel_asset_items');
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Asset item deleted successfully</div>');
        redirect('admin/hostelsettings');
    }

    public function save_warden()
    {
        if (!$this->rbac->hasPrivilege('hostel_settings', 'can_edit')) {
            access_denied();
        }

        $wardens = $this->input->post('warden_id');
        if (!empty($wardens)) {
            foreach ($wardens as $hostel_id => $warden_id) {
                if (empty($warden_id)) {
                    $warden_id = NULL; // allow un-assigning
                }
                $this->db->where('id', $hostel_id)->update('hostel', ['warden_id' => $warden_id]);
            }
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Warden assignments updated successfully</div>');
        }
        redirect('admin/hostelsettings');
    }
}
