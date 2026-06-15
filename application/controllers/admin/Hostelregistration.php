<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Hostelregistration extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('hostelroom_model');
        $this->load->model('student_model');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('student_registration', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Hostel');
        $this->session->set_userdata('sub_menu', 'admin/hostelregistration');
        $data['title'] = 'Student Registration';
        
        // Fetch all rooms with their bed statuses
        $data['hostelroomlist'] = $this->hostelroom_model->getRoomsWithBedStatus();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/hostel/student_registration', $data);
        $this->load->view('layout/footer', $data);
    }

    public function search_student()
    {
        $search_text = $this->input->post('search_text');
        $current_session = $this->setting_model->getCurrentSession();

        $sql = "SELECT students.id, students.firstname, students.lastname, students.admission_no, students.image, students.mobileno, students.guardian_name, students.hostel_room_id, classes.class as class_name, sections.section as section_name, hostel_rooms.room_no, hostel.hostel_name
                FROM students
                LEFT JOIN student_session ON students.id = student_session.student_id
                LEFT JOIN classes ON student_session.class_id = classes.id
                LEFT JOIN sections ON student_session.section_id = sections.id
                LEFT JOIN hostel_rooms ON students.hostel_room_id = hostel_rooms.id
                LEFT JOIN hostel ON hostel_rooms.hostel_id = hostel.id
                WHERE students.is_active = 'yes'
                AND student_session.session_id = ?";

        $binds = array($current_session);

        if (!empty($search_text)) {
            $sql .= " AND (students.firstname LIKE ? OR students.lastname LIKE ? OR students.admission_no LIKE ?)";
            $like_text = '%' . $search_text . '%';
            $binds[] = $like_text;
            $binds[] = $like_text;
            $binds[] = $like_text;
        }

        $sql .= " LIMIT 10";

        $query = $this->db->query($sql, $binds);

        if (!$query) {
            echo json_encode(['status' => 0, 'msg' => $this->db->error()['message']]);
            return;
        }
        $result = $query->result_array();

        echo json_encode(['status' => 1, 'data' => $result]);
    }

    public function get_applicable_fees()
    {
        $student_id = $this->input->post('student_id');
        $this->load->model('setting_model');
        $current_session = $this->setting_model->getCurrentSession();

        $this->db->where('student_id', $student_id);
        $this->db->where('session_id', $current_session);
        $student_session = $this->db->get('student_session')->row();

        if (empty($student_session)) {
            echo json_encode(['status' => 1, 'data' => []]);
            return;
        }

        $hostel_fee_groups = $this->db->get('hostel_fee_groups')->result_array();
        if (empty($hostel_fee_groups)) {
            echo json_encode(['status' => 1, 'data' => []]);
            return;
        }

        $hostel_fee_group_ids = array_column($hostel_fee_groups, 'fee_groups_id');

        $this->db->where('class_id', $student_session->class_id);
        $this->db->where_in('fee_groups_id', $hostel_fee_group_ids);
        $class_fee_groups = $this->db->get('fee_groups_classes')->result_array();

        if (empty($class_fee_groups)) {
            echo json_encode(['status' => 1, 'data' => []]);
            return;
        }

        $applicable_fee_group_ids = array_column($class_fee_groups, 'fee_groups_id');

        $this->db->where_in('id', $applicable_fee_group_ids);
        $fee_groups = $this->db->get('fee_groups')->result_array();

        foreach ($fee_groups as &$fg) {
            $this->db->select_sum('amount');
            $this->db->where('fee_groups_id', $fg['id']);
            $this->db->where('session_id', $current_session);
            $sum_res = $this->db->get('fee_groups_feetype')->row();
            $fg['total_amount'] = $sum_res->amount ? $sum_res->amount : '0.00';
        }

        echo json_encode(['status' => 1, 'data' => $fee_groups]);
    }

    public function assign_bed()
    {
        if (!$this->rbac->hasPrivilege('student_registration', 'can_add')) {
            echo json_encode(['status' => 0, 'msg' => 'Access Denied']);
            return;
        }

        $student_id = $this->input->post('student_id');
        $hostel_room_id = $this->input->post('hostel_room_id');
        $hostel_bed_no = $this->input->post('hostel_bed_no');
        $assign_date = $this->customlib->dateFormatToYYYYMMDD($this->input->post('assign_date'));

        if (empty($student_id) || empty($hostel_room_id) || empty($hostel_bed_no)) {
            echo json_encode(['status' => 0, 'msg' => 'Missing required fields.']);
            return;
        }

        // Check if the bed is already occupied by someone else
        $this->db->where('hostel_room_id', $hostel_room_id);
        $this->db->where('hostel_bed_no', $hostel_bed_no);
        $this->db->where('is_active', 'yes');
        $existing = $this->db->get('students')->row_array();

        if (!empty($existing) && $existing['id'] != $student_id) {
            echo json_encode(['status' => 0, 'msg' => 'Bed is already occupied by ' . $existing['firstname'] . ' ' . $existing['lastname']]);
            return;
        }

        $update_data = [
            'hostel_room_id' => $hostel_room_id,
            'hostel_bed_no' => $hostel_bed_no,
            'hostel_assign_date' => $assign_date
        ];

        $this->db->where('id', $student_id);
        $this->db->update('students', $update_data);

        // Get student_session_id
        $this->load->model('setting_model');
        $current_session = $this->setting_model->getCurrentSession();
        $this->db->where('student_id', $student_id)->where('session_id', $current_session);
        $ss = $this->db->get('student_session')->row();
        
        if($ss) {
            $this->db->insert('hostel_room_transfers', [
                'student_session_id' => $ss->id,
                'from_hostel_id' => NULL,
                'from_room_id' => NULL,
                'from_bed_no' => NULL,
                'to_hostel_id' => $this->get_hostel_id_by_room($hostel_room_id),
                'to_room_id' => $hostel_room_id,
                'to_bed_no' => $hostel_bed_no,
                'transfer_date' => $assign_date,
                'reason' => 'Assigned Room'
            ]);
        }

        $this->auto_assign_hostel_fee($student_id);

        echo json_encode(['status' => 1, 'msg' => 'Bed assigned successfully']);
    }

    private function get_hostel_id_by_room($room_id) {
        $this->db->where('id', $room_id);
        $room = $this->db->get('hostel_rooms')->row();
        return $room ? $room->hostel_id : NULL;
    }

    public function unassign_bed()
    {
        if (!$this->rbac->hasPrivilege('student_registration', 'can_delete') && !$this->rbac->hasPrivilege('student_registration', 'can_edit')) {
            echo json_encode(['status' => 0, 'msg' => 'Access Denied']);
            return;
        }

        $student_id = $this->input->post('student_id');

        if (empty($student_id)) {
            echo json_encode(['status' => 0, 'msg' => 'Invalid Request']);
            return;
        }

        $from_room_id = $this->input->post('from_room_id');
        $from_bed_no = $this->input->post('from_bed_no');
        
        $this->auto_remove_hostel_fee($student_id);

        $update_data = [
            'hostel_room_id' => 0,
            'hostel_bed_no' => null,
            'hostel_assign_date' => null
        ];

        $this->db->where('id', $student_id);
        $this->db->update('students', $update_data);

        // Get student_session_id
        $this->load->model('setting_model');
        $current_session = $this->setting_model->getCurrentSession();
        $this->db->where('student_id', $student_id)->where('session_id', $current_session);
        $ss = $this->db->get('student_session')->row();
        
        if($ss) {
            $this->db->insert('hostel_room_transfers', [
                'student_session_id' => $ss->id,
                'from_hostel_id' => $this->get_hostel_id_by_room($from_room_id),
                'from_room_id' => $from_room_id,
                'from_bed_no' => $from_bed_no,
                'to_hostel_id' => NULL,
                'to_room_id' => NULL,
                'to_bed_no' => NULL,
                'transfer_date' => date('Y-m-d'),
                'reason' => 'Unassigned Room'
            ]);
        }

        echo json_encode(['status' => 1, 'msg' => 'Student removed from hostel bed successfully']);
    }

    public function transfer_bed()
    {
        if (!$this->rbac->hasPrivilege('student_registration', 'can_edit')) {
            echo json_encode(['status' => 0, 'msg' => 'Access Denied']);
            return;
        }

        $student_id = $this->input->post('student_id');
        $from_room_id = $this->input->post('from_room_id');
        $from_bed_no = $this->input->post('from_bed_no');
        $to_room_id = $this->input->post('to_room_id');
        $to_bed_no = $this->input->post('to_bed_no');
        $transfer_date = $this->customlib->dateFormatToYYYYMMDD($this->input->post('transfer_date'));
        $reason = $this->input->post('reason');

        if (empty($student_id) || empty($to_room_id) || empty($to_bed_no) || empty($transfer_date)) {
            echo json_encode(['status' => 0, 'msg' => 'Please fill all required fields.']);
            return;
        }

        $update_data = [
            'hostel_room_id' => $to_room_id,
            'hostel_bed_no' => $to_bed_no,
            'hostel_assign_date' => $transfer_date
        ];

        $this->db->where('id', $student_id);
        $this->db->update('students', $update_data);

        // Get student_session_id
        $this->load->model('setting_model');
        $current_session = $this->setting_model->getCurrentSession();
        $this->db->where('student_id', $student_id)->where('session_id', $current_session);
        $ss = $this->db->get('student_session')->row();
        
        if($ss) {
            $this->db->insert('hostel_room_transfers', [
                'student_session_id' => $ss->id,
                'from_hostel_id' => $this->get_hostel_id_by_room($from_room_id),
                'from_room_id' => $from_room_id,
                'from_bed_no' => $from_bed_no,
                'to_hostel_id' => $this->get_hostel_id_by_room($to_room_id),
                'to_room_id' => $to_room_id,
                'to_bed_no' => $to_bed_no,
                'transfer_date' => $transfer_date,
                'reason' => $reason
            ]);
        }

        echo json_encode(['status' => 1, 'msg' => 'Student transferred successfully']);
    }

    public function get_bed_history()
    {
        $room_id = $this->input->post('room_id');
        $bed_no = $this->input->post('bed_no');

        if (empty($room_id) || empty($bed_no)) {
            echo json_encode([]);
            return;
        }

        $this->db->select('hrt.*, s.firstname, s.lastname');
        $this->db->from('hostel_room_transfers hrt');
        $this->db->join('student_session ss', 'ss.id = hrt.student_session_id');
        $this->db->join('students s', 's.id = ss.student_id');
        $this->db->where("(hrt.from_room_id = $room_id AND hrt.from_bed_no = '$bed_no') OR (hrt.to_room_id = $room_id AND hrt.to_bed_no = '$bed_no')");
        $this->db->order_by('hrt.transfer_date', 'desc');
        $this->db->order_by('hrt.created_at', 'desc');
        
        $history = $this->db->get()->result_array();
        
        $data = [];
        foreach ($history as $log) {
            $student_name = $log['firstname'] . ' ' . $log['lastname'];
            
            if ($log['to_room_id'] == $room_id && $log['to_bed_no'] == $bed_no) {
                if(empty($log['from_room_id'])) {
                    $action = '<span class="label label-success">Assigned</span>';
                } else {
                    $action = '<span class="label label-primary">Transferred In</span>';
                }
            } else if ($log['from_room_id'] == $room_id && $log['from_bed_no'] == $bed_no) {
                if(empty($log['to_room_id'])) {
                    $action = '<span class="label label-danger">Unassigned</span>';
                } else {
                    $action = '<span class="label label-warning">Transferred Out</span>';
                }
            } else {
                $action = 'Unknown';
            }

            $data[] = [
                'student_name' => $student_name,
                'date' => date($this->customlib->getSchoolDateFormat(), strtotime($log['transfer_date'])),
                'action' => $action,
                'details' => $log['reason'] ? $log['reason'] : '-'
            ];
        }

        echo json_encode($data);
    }

    private function auto_assign_hostel_fee($student_id)
    {
        $this->load->model('studentfeemaster_model');
        $this->load->model('setting_model');
        $current_session = $this->setting_model->getCurrentSession();

        $this->db->where('student_id', $student_id);
        $this->db->where('session_id', $current_session);
        $student_session = $this->db->get('student_session')->row();

        if (empty($student_session)) return;

        $hostel_fee_groups = $this->db->get('hostel_fee_groups')->result_array();
        if (empty($hostel_fee_groups)) return;

        $hostel_fee_group_ids = array_column($hostel_fee_groups, 'fee_groups_id');

        $this->db->where('class_id', $student_session->class_id);
        $this->db->where_in('fee_groups_id', $hostel_fee_group_ids);
        $class_fee_groups = $this->db->get('fee_groups_classes')->result_array();

        if (empty($class_fee_groups)) return;

        $applicable_fee_group_ids = array_column($class_fee_groups, 'fee_groups_id');
        
        $assign_fee_groups = $this->input->post('assign_fee_groups');
        $fees_loaded = $this->input->post('fees_loaded');

        if ($fees_loaded == '1') {
            if (!is_array($assign_fee_groups)) {
                $assign_fee_groups = [];
            }
            $applicable_fee_group_ids = array_intersect($applicable_fee_group_ids, $assign_fee_groups);
        }

        if (empty($applicable_fee_group_ids)) return;

        $this->db->where('session_id', $current_session);
        $this->db->where_in('fee_groups_id', $applicable_fee_group_ids);
        $fee_session_groups = $this->db->get('fee_session_groups')->result_array();

        foreach ($fee_session_groups as $fsg) {
            $insert_data = [
                'student_session_id' => $student_session->id,
                'fee_session_group_id' => $fsg['id'],
                'is_system' => 0
            ];
            $this->studentfeemaster_model->add($insert_data);
        }
    }

    private function auto_remove_hostel_fee($student_id)
    {
        $this->load->model('setting_model');
        $current_session = $this->setting_model->getCurrentSession();

        $this->db->where('student_id', $student_id);
        $this->db->where('session_id', $current_session);
        $student_session = $this->db->get('student_session')->row();

        if (empty($student_session)) return;

        $hostel_fee_groups = $this->db->get('hostel_fee_groups')->result_array();
        if (empty($hostel_fee_groups)) return;

        $hostel_fee_group_ids = array_column($hostel_fee_groups, 'fee_groups_id');

        $this->db->where('session_id', $current_session);
        $this->db->where_in('fee_groups_id', $hostel_fee_group_ids);
        $fee_session_groups = $this->db->get('fee_session_groups')->result_array();

        if (empty($fee_session_groups)) return;
        $fsg_ids = array_column($fee_session_groups, 'id');

        $this->db->where('student_session_id', $student_session->id);
        $this->db->where_in('fee_session_group_id', $fsg_ids);
        $assigned_fees = $this->db->get('student_fees_master')->result_array();

        foreach ($assigned_fees as $assigned_fee) {
            $this->db->where('student_fees_master_id', $assigned_fee['id']);
            $payments = $this->db->get('student_fees_deposite')->num_rows();

            if ($payments == 0) {
                $this->db->where('id', $assigned_fee['id']);
                $this->db->delete('student_fees_master');
            }
        }
    }
}
