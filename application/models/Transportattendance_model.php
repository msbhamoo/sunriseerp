<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Transportattendance_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function search_student_for_transport($search)
    {
        $this->db->select('students.id, student_session.id as student_session_id, students.firstname, students.lastname, students.admission_no, classes.class, sections.section, classes.id as class_id');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('classes', 'classes.id = student_session.class_id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where('students.is_active', 'yes');
        $this->db->group_start();
        $this->db->like('students.firstname', $search);
        $this->db->or_like('students.lastname', $search);
        $this->db->or_like('students.admission_no', $search);
        $this->db->group_end();
        $this->db->order_by('students.firstname', 'ASC');
        $this->db->limit(15);
        return $this->db->get()->result_array();
    }

    public function get_bus_students($vehicle_id, $session_id = null)
    {
        if ($session_id == null) {
            $session_id = $this->current_session;
        }
        $this->db->select('students.id as student_id, student_session.id as student_session_id, students.firstname, students.lastname, students.admission_no, classes.class, sections.section, transport_route.route_title, pickup_point.name as pickup_point_name');
        $this->db->from('student_session');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->join('classes', 'classes.id = student_session.class_id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = student_session.vehroute_id');
        $this->db->join('transport_route', 'transport_route.id = vehicle_routes.route_id', 'left');
        $this->db->join('route_pickup_point', 'route_pickup_point.id = student_session.route_pickup_point_id', 'left');
        $this->db->join('pickup_point', 'pickup_point.id = route_pickup_point.pickup_point_id', 'left');
        $this->db->where('vehicle_routes.vehicle_id', $vehicle_id);
        $this->db->where('student_session.session_id', $session_id);
        $this->db->where('students.is_active', 'yes');
        $this->db->order_by('transport_route.route_title', 'ASC');
        $this->db->order_by('students.firstname', 'ASC');
        return $this->db->get()->result_array();
    }

    public function get_attendance($vehicle_id, $date, $attendance_type)
    {
        $this->db->select('transport_attendance.*');
        $this->db->from('transport_attendance');
        $this->db->where('vehicle_id', $vehicle_id);
        $this->db->where('date', $date);
        $this->db->where('attendance_type', $attendance_type);
        $result = $this->db->get()->result_array();
        
        $attendance = [];
        foreach ($result as $row) {
            $attendance[$row['student_session_id']] = $row;
        }
        return $attendance;
    }

    public function get_custom_riders($vehicle_id, $date, $attendance_type)
    {
        $this->db->select('transport_attendance.*, students.id as student_id, students.firstname, students.lastname, students.admission_no, classes.class, sections.section');
        $this->db->from('transport_attendance');
        $this->db->join('student_session', 'student_session.id = transport_attendance.student_session_id');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->join('classes', 'classes.id = student_session.class_id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->where('transport_attendance.vehicle_id', $vehicle_id);
        $this->db->where('transport_attendance.date', $date);
        $this->db->where('transport_attendance.attendance_type', $attendance_type);
        $this->db->where('transport_attendance.status', 'Switched Bus');
        return $this->db->get()->result_array();
    }

    public function save_attendance($data)
    {
        foreach ($data as $row) {
            $this->db->where('student_session_id', $row['student_session_id']);
            $this->db->where('date', $row['date']);
            $this->db->where('attendance_type', $row['attendance_type']);
            $q = $this->db->get('transport_attendance');
            
            if ($q->num_rows() > 0) {
                $existing = $q->row();
                // If it's a Switched Bus record, we don't change vehicle_id unless specified
                if ($existing->status == 'Switched Bus' && $row['status'] != 'Switched Bus') {
                    // Changing status of custom rider? usually they are just Present.
                }
                $this->db->where('id', $existing->id);
                $this->db->update('transport_attendance', $row);
            } else {
                $this->db->insert('transport_attendance', $row);
            }
        }
    }

    public function check_transport_presence($date, $attendance_type)
    {
        $this->db->select('student_session_id, vehicle_id');
        $this->db->from('transport_attendance');
        $this->db->where('date', $date);
        $this->db->where('attendance_type', $attendance_type);
        $this->db->where_in('status', ['Present', 'Switched Bus']);
        
        $result = $this->db->get()->result_array();
        
        $present_students = [];
        foreach ($result as $row) {
            $present_students[$row['student_session_id']] = $row['vehicle_id'];
        }
        return $present_students;
    }
}
