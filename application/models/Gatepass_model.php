<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Gatepass_model extends MY_Model
{
    public $current_session;

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function add($data)
    {
        if (isset($data['id']) && $data['id'] != '') {
            $this->db->where('id', $data['id']);
            $this->db->update('front_office_gate_pass', $data);
            return $data['id'];
        } else {
            $this->db->insert('front_office_gate_pass', $data);
            return $this->db->insert_id();
        }
    }

    public function get($id = null)
    {
        $this->db->select('front_office_gate_pass.*, students.father_name');
        $this->db->from('front_office_gate_pass');
        $this->db->join('students', 'students.id = front_office_gate_pass.user_id AND front_office_gate_pass.user_type = "student"', 'left');
        
        if ($id != null) {
            $this->db->where('front_office_gate_pass.id', $id);
        } else {
            $this->db->order_by('front_office_gate_pass.id', 'desc');
        }

        $query = $this->db->get();

        if ($id != null) {
            $result = $query->row_array();
            if ($result) {
                $user_info = $this->get_user_details($result['user_type'], $result['user_id']);
                $result['user_details'] = $user_info['text'] ?? 'Unknown User';
                $result['user_info'] = $user_info;
            }
            return $result;
        } else {
            $results = $query->result_array();
            foreach ($results as $key => $value) {
                $user_info = $this->get_user_details($value['user_type'], $value['user_id']);
                $results[$key]['user_details'] = $user_info['text'] ?? 'Unknown User';
                $results[$key]['user_info'] = $user_info;
            }
            return $results;
        }
    }

    public function getByUser($user_type, $user_id)
    {
        $this->db->select('front_office_gate_pass.*');
        $this->db->from('front_office_gate_pass');
        $this->db->where('user_type', $user_type);
        $this->db->where('user_id', $user_id);
        $this->db->order_by('id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }

    private function get_user_details($user_type, $user_id)
    {
        if ($user_type == 'student') {
            $this->db->select('students.id, students.firstname, students.lastname, students.admission_no, students.image, students.father_name, students.father_phone, students.guardian_name, students.guardian_phone, students.mobileno, classes.class, sections.section, transport_route.route_title, vehicles.vehicle_no, pickup_point.name as pickup_point_name');
            $this->db->from('students');
            $this->db->join('student_session', 'student_session.student_id = students.id', 'left');
            $this->db->join('classes', 'classes.id = student_session.class_id', 'left');
            $this->db->join('sections', 'sections.id = student_session.section_id', 'left');
            $this->db->join('vehicle_routes', 'vehicle_routes.id = student_session.vehroute_id', 'left');
            $this->db->join('transport_route', 'transport_route.id = vehicle_routes.route_id', 'left');
            $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
            $this->db->join('route_pickup_point', 'route_pickup_point.id = student_session.route_pickup_point_id', 'left');
            $this->db->join('pickup_point', 'pickup_point.id = route_pickup_point.pickup_point_id', 'left');
            $this->db->where('students.id', $user_id);
            $this->db->order_by('student_session.id', 'desc');
            $query = $this->db->get();
            $student = $query->row_array();
            if ($student) {
                $class_str = !empty($student['class']) ? $student['class'] . ' (' . $student['section'] . ')' : '';
                $student['text'] = $student['firstname'] . ' ' . $student['lastname'] . ' (' . $student['admission_no'] . ')' . (!empty($class_str) ? ' - ' . $class_str : '');
                $student['name'] = $student['firstname'] . ' ' . $student['lastname'];
                $student['class_name'] = $class_str;
                $student['vehicle_no'] = !empty($student['vehicle_no']) ? $student['vehicle_no'] : '';
                $student['route_title'] = !empty($student['route_title']) ? $student['route_title'] : '';
                $student['pickup_point_name'] = !empty($student['pickup_point_name']) ? $student['pickup_point_name'] : '';
                return $student;
            }
        } elseif ($user_type == 'staff') {
            $this->db->select('staff.id, staff.name, staff.surname, staff.employee_id, staff.image, staff.contact_no, roles.name as role_name');
            $this->db->from('staff');
            $this->db->join('staff_roles', 'staff_roles.staff_id = staff.id', 'left');
            $this->db->join('roles', 'roles.id = staff_roles.role_id', 'left');
            $this->db->where('staff.id', $user_id);
            $query = $this->db->get();
            $staff = $query->row_array();
            if ($staff) {
                $role_str = !empty($staff['role_name']) ? ' - ' . $staff['role_name'] : '';
                $staff['text'] = $staff['name'] . ' ' . $staff['surname'] . ' (' . $staff['employee_id'] . ')' . $role_str;
                $staff['firstname'] = $staff['name'];
                $staff['lastname'] = $staff['surname'];
                $staff['admission_no'] = $staff['employee_id'];
                $staff['class_name'] = $staff['role_name'] ?? 'Staff';
                $staff['mobileno'] = $staff['contact_no'] ?? '';
                return $staff;
            }
        }
        return ['text' => 'Unknown User', 'name' => 'Unknown User', 'image' => '', 'admission_no' => '', 'class_name' => ''];
    }

    public function generate_gate_pass_no()
    {
        $this->db->select('gate_pass_no');
        $this->db->from('front_office_gate_pass');
        $this->db->order_by('id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get();
        $result = $query->row_array();

        if ($result) {
            $last_no = $result['gate_pass_no'];
            // assuming format GP-0001
            $parts = explode('-', $last_no);
            if(count($parts) == 2){
                $number = (int)$parts[1];
                $number++;
                return 'GP-' . sprintf('%04d', $number);
            }
        }
        
        return 'GP-0001';
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('front_office_gate_pass');
    }

    public function check_student_gatepass($date)
    {
        $this->db->select('user_id');
        $this->db->from('front_office_gate_pass');
        $this->db->where('user_type', 'student');
        $this->db->where('date', $date);
        
        $result = $this->db->get()->result_array();
        
        $gatepasses = [];
        foreach ($result as $row) {
            $gatepasses[] = $row['user_id'];
        }
        return $gatepasses;
    }
}
