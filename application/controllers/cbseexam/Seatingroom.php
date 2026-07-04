<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Seatingroom extends MY_Addon_CBSEController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('cbseexam/cbse_seating_room_model');
        $this->auth->addonchk('sscbse', site_url('cbseexam/setting/index'));
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('cbse_exam_seating_rooms', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'cbse_exam');
        $this->session->set_userdata('sub_menu', 'cbseexam/setting/index'); // Main sidebar setting tab
        $this->session->set_userdata('subsub_menu', 'cbseexam/seatingroom'); // Settings inner tab
        
        $data['title'] = 'Seating Rooms Master';
        $data['buildings'] = $this->cbse_seating_room_model->get_buildings();
        $data['rooms'] = $this->cbse_seating_room_model->get_rooms();

        $this->load->view('layout/header', $data);
        $this->load->view('cbseexam/seatingroom/index', $data);
        $this->load->view('layout/footer', $data);
    }

    public function add_building()
    {
        if (!$this->rbac->hasPrivilege('cbse_exam_seating_rooms', 'can_add')) {
            access_denied();
        }

        $this->form_validation->set_rules('name', 'Building Name', 'required|trim|xss_clean');
        if ($this->form_validation->run() == false) {
            $msg = array(
                'name' => form_error('name'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $data = array(
                'name' => $this->input->post('name'),
                'code' => $this->input->post('code'),
                'description' => $this->input->post('description'),
                'is_active' => $this->input->post('is_active') ? 1 : 0
            );
            if ($this->input->post('id')) {
                $data['id'] = $this->input->post('id');
            }
            $this->cbse_seating_room_model->add_building($data);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function delete_building($id)
    {
        if (!$this->rbac->hasPrivilege('cbse_exam_seating_rooms', 'can_delete')) {
            access_denied();
        }
        $this->cbse_seating_room_model->delete_building($id);
        redirect('cbseexam/seatingroom');
    }

    public function add_room()
    {
        if (!$this->rbac->hasPrivilege('cbse_exam_seating_rooms', 'can_add')) {
            access_denied();
        }

        $this->form_validation->set_rules('building_id', 'Building', 'required');
        $this->form_validation->set_rules('room_number', 'Room Number', 'required|trim|xss_clean');
        $this->form_validation->set_rules('seating_capacity', 'Capacity', 'required|numeric');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'building_id' => form_error('building_id'),
                'room_number' => form_error('room_number'),
                'seating_capacity' => form_error('seating_capacity'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $data = array(
                'building_id' => $this->input->post('building_id'),
                'room_number' => $this->input->post('room_number'),
                'floor' => $this->input->post('floor'),
                'seating_capacity' => $this->input->post('seating_capacity'),
                'room_type' => $this->input->post('room_type'),
                'is_active' => $this->input->post('is_active') ? 1 : 0
            );
            if ($this->input->post('id')) {
                $data['id'] = $this->input->post('id');
            }
            
            // Fetch session first to prevent Active Record query bleeding
            $session_id = $this->setting_model->getCurrentSession();
            
            // Basic unique check
            $check = $this->db->where('building_id', $data['building_id'])->where('room_number', $data['room_number'])->where('session_id', $session_id);
            if (isset($data['id'])) $check->where('id !=', $data['id']);
            
            if ($check->get('cbse_seating_rooms')->num_rows() > 0) {
                $array = array('status' => 'fail', 'error' => array('room_number' => 'Room number already exists in this building.'), 'message' => '');
            } else {
                $this->cbse_seating_room_model->add_room($data);
                $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            }
        }
        echo json_encode($array);
    }
    
    public function delete_room($id)
    {
        if (!$this->rbac->hasPrivilege('cbse_exam_seating_rooms', 'can_delete')) {
            access_denied();
        }
        $this->cbse_seating_room_model->delete_room($id);
        redirect('cbseexam/seatingroom');
    }

    public function bulk_generate_rooms()
    {
        if (!$this->rbac->hasPrivilege('cbse_exam_seating_rooms', 'can_add')) {
            access_denied();
        }

        $this->form_validation->set_rules('bulk_building_id', 'Building', 'required');
        $this->form_validation->set_rules('prefix', 'Prefix', 'required|trim|xss_clean');
        $this->form_validation->set_rules('start', 'Start Number', 'required|numeric');
        $this->form_validation->set_rules('count', 'Count', 'required|numeric|greater_than[0]|less_than[101]');
        $this->form_validation->set_rules('capacity', 'Capacity', 'required|numeric|greater_than[0]');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'bulk_building_id' => form_error('bulk_building_id'),
                'prefix' => form_error('prefix'),
                'start' => form_error('start'),
                'count' => form_error('count'),
                'capacity' => form_error('capacity'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $building_id = $this->input->post('bulk_building_id');
            $prefix = $this->input->post('prefix');
            $start = $this->input->post('start');
            $count = $this->input->post('count');
            $capacity = $this->input->post('capacity');
            $type = $this->input->post('room_type');
            
            $generated = $this->cbse_seating_room_model->bulk_generate_rooms($building_id, $prefix, $start, $count, $capacity, $type);
            
            $array = array('status' => 'success', 'error' => '', 'message' => "$generated rooms successfully generated.");
        }
        echo json_encode($array);
    }
}
