<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Hostelattendance_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_hostel_students($hostel_id)
    {
        $this->db->select('students.id as student_id, student_session.id as student_session_id, students.firstname, students.lastname, students.admission_no, hostel_rooms.room_no, hostel_rooms.no_of_bed, students.hostel_bed_no');
        $this->db->from('students');
        $this->db->join('student_session', 'student_session.student_id = students.id');
        $this->db->join('hostel_rooms', 'hostel_rooms.id = students.hostel_room_id');
        $this->db->where('students.is_active', 'yes');
        $this->db->where('hostel_rooms.hostel_id', $hostel_id);
        $this->db->order_by('hostel_rooms.room_no', 'ASC');
        $this->db->order_by('students.hostel_bed_no', 'asc');
        return $this->db->get()->result_array();
    }

    public function get_attendance($hostel_id, $date, $roll_call_type = 'morning')
    {
        $this->db->select('hostel_attendance.*');
        $this->db->from('hostel_attendance');
        $this->db->where('hostel_id', $hostel_id);
        $this->db->where('date', $date);
        $this->db->where('roll_call_type', $roll_call_type);
        $result = $this->db->get()->result_array();
        
        $attendance = [];
        foreach ($result as $row) {
            $attendance[$row['student_session_id']] = $row;
        }
        return $attendance;
    }

    public function save($data)
    {
        foreach ($data as $row) {
            $this->db->where('student_session_id', $row['student_session_id']);
            $this->db->where('date', $row['date']);
            $this->db->where('roll_call_type', $row['roll_call_type']);
            $q = $this->db->get('hostel_attendance');
            
            if ($q->num_rows() > 0) {
                $this->db->where('id', $q->row()->id);
                $this->db->update('hostel_attendance', $row);
            } else {
                $this->db->insert('hostel_attendance', $row);
            }
        }
    }
    public function get_monthly_attendance($hostel_id, $month, $year, $roll_call_type = 'morning')
    {
        $this->db->select('hostel_attendance.*');
        $this->db->from('hostel_attendance');
        $this->db->where('hostel_id', $hostel_id);
        if ($month != 'all') {
            $this->db->where('MONTH(date)', $month);
        }
        $this->db->where('YEAR(date)', $year);
        if ($roll_call_type != 'all') {
            $this->db->where('roll_call_type', $roll_call_type);
        }
        $result = $this->db->get()->result_array();
        
        $attendance = [];
        foreach ($result as $row) {
            $m = date('m', strtotime($row['date']));
            $day = date('j', strtotime($row['date']));
            $attendance[$row['student_session_id']][$m][$day][$row['roll_call_type']] = $row['attendence_type_id'];
        }
        return $attendance;
    }
}
