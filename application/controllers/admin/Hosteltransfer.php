<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Hosteltransfer extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Customlib');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('hostel_room_transfer', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Hostel');
        $this->session->set_userdata('sub_menu', 'hostel_room_transfer_log');

        $data['title'] = 'Room Transfer Logs';

        $this->db->select('hrt.*, s.firstname, s.lastname, s.admission_no, 
                           fh.hostel_name as from_hostel, fr.room_no as from_room, 
                           th.hostel_name as to_hostel, tr.room_no as to_room');
        $this->db->from('hostel_room_transfers hrt');
        $this->db->join('student_session ss', 'ss.id = hrt.student_session_id');
        $this->db->join('students s', 's.id = ss.student_id');
        $this->db->join('hostel_rooms fr', 'fr.id = hrt.from_room_id', 'left');
        $this->db->join('hostel fh', 'fh.id = fr.hostel_id', 'left');
        $this->db->join('hostel_rooms tr', 'tr.id = hrt.to_room_id', 'left');
        $this->db->join('hostel th', 'th.id = tr.hostel_id', 'left');
        $this->db->order_by('hrt.transfer_date', 'desc');
        $this->db->order_by('hrt.created_at', 'desc');
        
        $data['transfer_logs'] = $this->db->get()->result_array();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/hostel/transfer_log', $data);
        $this->load->view('layout/footer', $data);
    }
}
