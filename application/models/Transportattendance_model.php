<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Transportattendance_model extends MY_Model
{
    public $current_session;
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
        $this->db->select('transport_attendance.*, students.id as student_id, students.firstname, students.lastname, students.admission_no, classes.class, sections.section, students.hostel_room_id, vehicles.vehicle_no as original_vehicle_no');
        $this->db->from('transport_attendance');
        $this->db->join('student_session', 'student_session.id = transport_attendance.student_session_id');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->join('classes', 'classes.id = student_session.class_id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        $this->db->join('vehicle_routes', 'vehicle_routes.id = student_session.vehroute_id', 'left');
        $this->db->join('vehicles', 'vehicles.id = vehicle_routes.vehicle_id', 'left');
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
                
                if ($row['status'] == 'Remove') {
                    $this->db->where('id', $existing->id);
                    $this->db->delete('transport_attendance');
                    continue;
                }
                
                $this->db->where('id', $existing->id);
                $this->db->update('transport_attendance', $row);
            } else {
                if ($row['status'] != 'Remove') {
                    $this->db->insert('transport_attendance', $row);
                }
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
    public function get_daily_summary($date)
    {
        $session_id = $this->current_session;
        
        $sql = "
            SELECT 
                v.id as vehicle_id,
                v.vehicle_no,
                v.driver_name,
                v.attendant_name,
                (SELECT count(ss.id) FROM student_session ss JOIN vehicle_routes vr ON vr.id = ss.vehroute_id JOIN students s ON s.id = ss.student_id WHERE vr.vehicle_id = v.id AND ss.session_id = ? AND s.is_active = 'yes') as total_assigned,
                (SELECT count(ta.id) FROM transport_attendance ta WHERE ta.vehicle_id = v.id AND ta.date = ? AND ta.attendance_type = 'Morning' AND ta.status = 'Present') as morning_present,
                (SELECT count(ta.id) FROM transport_attendance ta WHERE ta.vehicle_id = v.id AND ta.date = ? AND ta.attendance_type = 'Morning' AND ta.status = 'Switched Bus') as morning_custom,
                (SELECT count(ta.id) FROM transport_attendance ta WHERE ta.vehicle_id = v.id AND ta.date = ? AND ta.attendance_type = 'Evening' AND ta.status = 'Present') as evening_present,
                (SELECT count(ta.id) FROM transport_attendance ta WHERE ta.vehicle_id = v.id AND ta.date = ? AND ta.attendance_type = 'Evening' AND ta.status = 'Switched Bus') as evening_custom
            FROM vehicles v
            ORDER BY v.vehicle_no ASC
        ";
        
        $query = $this->db->query($sql, array($session_id, $date, $date, $date, $date));
        return $query->result_array();
    }

    public function get_monthly_summary($month, $year)
    {
        $session_id = $this->current_session;
        
        $sql = "
            SELECT 
                v.id as vehicle_id,
                v.vehicle_no,
                v.driver_name,
                v.attendant_name,
                (SELECT count(ta.id) FROM transport_attendance ta WHERE ta.vehicle_id = v.id AND MONTH(ta.date) = ? AND YEAR(ta.date) = ? AND ta.attendance_type = 'Morning' AND ta.status = 'Present') as morning_present_month,
                (SELECT count(ta.id) FROM transport_attendance ta WHERE ta.vehicle_id = v.id AND MONTH(ta.date) = ? AND YEAR(ta.date) = ? AND ta.attendance_type = 'Morning' AND ta.status = 'Switched Bus') as morning_custom_month,
                (SELECT count(ta.id) FROM transport_attendance ta WHERE ta.vehicle_id = v.id AND MONTH(ta.date) = ? AND YEAR(ta.date) = ? AND ta.attendance_type = 'Evening' AND ta.status = 'Present') as evening_present_month,
                (SELECT count(ta.id) FROM transport_attendance ta WHERE ta.vehicle_id = v.id AND MONTH(ta.date) = ? AND YEAR(ta.date) = ? AND ta.attendance_type = 'Evening' AND ta.status = 'Switched Bus') as evening_custom_month,
                (SELECT count(ta.id) FROM transport_attendance ta WHERE ta.vehicle_id = v.id AND MONTH(ta.date) = ? AND YEAR(ta.date) = ? AND ta.status IN ('Present', 'Switched Bus')) as total_present_month
            FROM vehicles v
            ORDER BY v.vehicle_no ASC
        ";
        
        $query = $this->db->query($sql, array($month, $year, $month, $year, $month, $year, $month, $year, $month, $year));
        return $query->result_array();
    }
    
    public function get_attendance_detail($vehicle_id, $date)
    {
        $session_id = $this->current_session;
        
        $sql = "
            SELECT 
                s.firstname, s.lastname, s.admission_no, 
                c.class, sec.section,
                ta.attendance_type, ta.status, ta.remark
            FROM transport_attendance ta
            JOIN student_session ss ON ss.id = ta.student_session_id
            JOIN students s ON s.id = ss.student_id
            JOIN classes c ON c.id = ss.class_id
            JOIN sections sec ON sec.id = ss.section_id
            WHERE ta.vehicle_id = ? AND ta.date = ? AND ta.status IN ('Present', 'Switched Bus')
            ORDER BY s.firstname ASC
        ";
        
        $query = $this->db->query($sql, array($vehicle_id, $date));
        return $query->result_array();
    }
    
    public function get_monthly_attendance_detail($vehicle_id, $month, $year)
    {
        $sql = "
            SELECT 
                ta.date, ta.attendance_type, ta.status, ta.remark,
                s.firstname, s.lastname, s.admission_no, 
                c.class, sec.section
            FROM transport_attendance ta
            JOIN student_session ss ON ss.id = ta.student_session_id
            JOIN students s ON s.id = ss.student_id
            JOIN classes c ON c.id = ss.class_id
            JOIN sections sec ON sec.id = ss.section_id
            WHERE ta.vehicle_id = ? AND MONTH(ta.date) = ? AND YEAR(ta.date) = ? AND ta.status IN ('Present', 'Switched Bus')
            ORDER BY ta.date ASC, ta.attendance_type DESC, s.firstname ASC
        ";
        
        $query = $this->db->query($sql, array($vehicle_id, $month, $year));
        return $query->result_array();
    }
}
