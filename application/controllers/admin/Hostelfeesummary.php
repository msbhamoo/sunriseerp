<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Hostelfeesummary extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('hostelroom_model');
        $this->load->model('studentfeemaster_model');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('hostel_fee_summary', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Hostel');
        $this->session->set_userdata('sub_menu', 'admin/hostelfeesummary');
        $data['title'] = 'Hostel Fee Summary';
        
        $current_session = $this->setting_model->getCurrentSession();

        // 1. Get all hostel fee groups
        $hostel_fee_groups = $this->db->get('hostel_fee_groups')->result_array();
        $hostel_fee_group_ids = array_column($hostel_fee_groups, 'fee_groups_id');
        
        // 2. Get students in hostel
        $rooms = $this->hostelroom_model->getRoomsWithBedStatus();
        $enrolled_students = [];
        foreach ($rooms as $room) {
            if (!empty($room['students'])) {
                foreach ($room['students'] as $student) {
                    $student['hostel_name'] = $room['hostel_name'];
                    $student['room_no'] = $room['room_no'];
                    $student['room_type'] = $room['room_type'];
                    $enrolled_students[] = $student;
                }
            }
        }

        $total_hostel_fee = 0;
        $total_collected = 0;
        $total_pending = 0;

        foreach ($enrolled_students as &$student) {
            $student_session_id = $this->getStudentSessionId($student['student_id'], $current_session);
            $student['student_session_id'] = $student_session_id;
            
            $student_total_fee = 0;
            $student_collected = 0;
            
            if ($student_session_id && !empty($hostel_fee_group_ids)) {
                // Get fee master records for this student that belong to hostel fee groups
                $this->db->select('student_fees_master.*, fee_session_groups.fee_groups_id');
                $this->db->from('student_fees_master');
                $this->db->join('fee_session_groups', 'student_fees_master.fee_session_group_id = fee_session_groups.id');
                $this->db->where('student_fees_master.student_session_id', $student_session_id);
                $this->db->where_in('fee_session_groups.fee_groups_id', $hostel_fee_group_ids);
                $fee_masters = $this->db->get()->result_array();
                
                foreach ($fee_masters as $master) {
                    // Get total fee amount from fee_groups_feetype for this fee_groups_id
                    $this->db->select_sum('amount');
                    $this->db->where('fee_groups_id', $master['fee_groups_id']);
                    $this->db->where('session_id', $current_session);
                    $sum_res = $this->db->get('fee_groups_feetype')->row();
                    $amount = $sum_res->amount ? $sum_res->amount : 0;
                    $student_total_fee += $amount;
                    
                    // Get collected amount from student_fees_deposite
                    $this->db->select('amount_detail');
                    $this->db->where('student_fees_master_id', $master['id']);
                    $deposits = $this->db->get('student_fees_deposite')->result_array();
                    
                    foreach ($deposits as $dep) {
                        $amount_detail = json_decode($dep['amount_detail'], true);
                        if (is_array($amount_detail)) {
                            foreach ($amount_detail as $ad) {
                                $student_collected += $ad['amount'] + $ad['amount_discount'];
                            }
                        }
                    }
                }
            }
            
            $student['total_fee'] = $student_total_fee;
            $student['collected'] = $student_collected;
            $student['pending'] = $student_total_fee - $student_collected;
            if ($student['pending'] < 0) $student['pending'] = 0;
            
            $total_hostel_fee += $student_total_fee;
            $total_collected += $student_collected;
            $total_pending += $student['pending'];
        }

        $data['students'] = $enrolled_students;
        $data['total_hostel_fee'] = $total_hostel_fee;
        $data['total_collected'] = $total_collected;
        $data['total_pending'] = $total_pending;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/hostel/feesummary', $data);
        $this->load->view('layout/footer', $data);
    }
    
    private function getStudentSessionId($student_id, $session_id) {
        $this->db->select('id');
        $this->db->where('student_id', $student_id);
        $this->db->where('session_id', $session_id);
        $res = $this->db->get('student_session')->row();
        return $res ? $res->id : 0;
    }

    public function print_summary($student_session_id)
    {
        if (!$this->rbac->hasPrivilege('hostel_fee_summary', 'can_view')) {
            access_denied();
        }
        
        $current_session = $this->setting_model->getCurrentSession();
        $student = $this->student_model->getByStudentSession($student_session_id);
        $data['student'] = $student;
        $data['settinglist'] = $this->setting_model->get();
        $data['sch_setting'] = $this->setting_model->getSetting();

        // 1. Get all hostel fee groups
        $hostel_fee_groups = $this->db->get('hostel_fee_groups')->result_array();
        $hostel_fee_group_ids = array_column($hostel_fee_groups, 'fee_groups_id');
        
        $feeList = [];
        if (!empty($hostel_fee_group_ids)) {
            $this->db->select('student_fees_master.*, fee_session_groups.fee_groups_id, fee_groups.name as fee_group_name');
            $this->db->from('student_fees_master');
            $this->db->join('fee_session_groups', 'student_fees_master.fee_session_group_id = fee_session_groups.id');
            $this->db->join('fee_groups', 'fee_session_groups.fee_groups_id = fee_groups.id');
            $this->db->where('student_fees_master.student_session_id', $student_session_id);
            $this->db->where_in('fee_session_groups.fee_groups_id', $hostel_fee_group_ids);
            $fee_masters = $this->db->get()->result_array();
            
            foreach ($fee_masters as $master) {
                $this->db->select_sum('amount');
                $this->db->where('fee_groups_id', $master['fee_groups_id']);
                $this->db->where('session_id', $current_session);
                $sum_res = $this->db->get('fee_groups_feetype')->row();
                $master['total_amount'] = $sum_res->amount ? $sum_res->amount : 0;
                
                $this->db->select('amount_detail');
                $this->db->where('student_fees_master_id', $master['id']);
                $deposits = $this->db->get('student_fees_deposite')->result_array();
                
                $master['deposits'] = [];
                foreach ($deposits as $dep) {
                    $amount_detail = json_decode($dep['amount_detail'], true);
                    if (is_array($amount_detail)) {
                        foreach ($amount_detail as $ad) {
                            $master['deposits'][] = $ad;
                        }
                    }
                }
                
                $feeList[] = $master;
            }
        }
        
        $data['feeList'] = $feeList;

        $page = $this->load->view('admin/hostel/print_feesummary', $data, true);
        echo json_encode(array('status' => 1, 'page' => $page));
    }
}
