<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Attendanceanalytics_model extends CI_Model {

    public function getTodayOverview($date) {
        $sql = "SELECT 
                    SUM(CASE WHEN at.type = 'Present' THEN 1 ELSE 0 END) as total_present,
                    SUM(CASE WHEN at.type = 'Absent' THEN 1 ELSE 0 END) as total_absent,
                    SUM(CASE WHEN at.type = 'Late' THEN 1 ELSE 0 END) as total_late,
                    SUM(CASE WHEN at.type = 'Half Day' THEN 1 ELSE 0 END) as total_half_day,
                    SUM(CASE WHEN at.type = 'Holiday' THEN 1 ELSE 0 END) as total_holiday
                FROM student_attendences sa
                JOIN attendence_type at ON sa.attendence_type_id = at.id
                WHERE sa.date = ?";
        $query = $this->db->query($sql, array($date));
        return $query->row_array();
    }

    public function getClassWiseAbsenteeism($date, $session_id) {
        $sql = "SELECT c.class, COUNT(sa.id) as total_absent
                FROM student_attendences sa
                JOIN attendence_type at ON sa.attendence_type_id = at.id
                JOIN student_session ss ON sa.student_session_id = ss.id
                JOIN classes c ON ss.class_id = c.id
                WHERE sa.date = ? AND at.type = 'Absent' AND ss.session_id = ?
                GROUP BY c.id, c.class
                ORDER BY total_absent DESC
                LIMIT 10";
        $query = $this->db->query($sql, array($date, $session_id));
        return $query->result_array();
    }

    public function getTrend($start_date, $end_date) {
        $sql = "SELECT sa.date,
                    SUM(CASE WHEN at.type = 'Present' THEN 1 ELSE 0 END) as total_present,
                    SUM(CASE WHEN at.type = 'Absent' THEN 1 ELSE 0 END) as total_absent
                FROM student_attendences sa
                JOIN attendence_type at ON sa.attendence_type_id = at.id
                WHERE sa.date BETWEEN ? AND ?
                GROUP BY sa.date
                ORDER BY sa.date ASC";
        $query = $this->db->query($sql, array($start_date, $end_date));
        return $query->result_array();
    }

    public function getAbsentStudentsWithFollowup($date, $session_id) {
        $sql = "SELECT 
                    s.id as student_id,
                    s.firstname, s.lastname, s.mobileno, s.guardian_phone,
                    c.class, sec.section,
                    ss.id as student_session_id,
                    (SELECT followup_status FROM attendance_followup_log a2 WHERE a2.student_session_id = ss.id AND a2.date = sa.date ORDER BY a2.id DESC LIMIT 1) as followup_status,
                    (SELECT remark FROM attendance_followup_log a2 WHERE a2.student_session_id = ss.id AND a2.date = sa.date ORDER BY a2.id DESC LIMIT 1) as followup_remark
                FROM student_attendences sa
                JOIN attendence_type at ON sa.attendence_type_id = at.id
                JOIN student_session ss ON sa.student_session_id = ss.id
                JOIN students s ON ss.student_id = s.id
                JOIN classes c ON ss.class_id = c.id
                JOIN sections sec ON ss.section_id = sec.id
                WHERE sa.date = ? AND at.type = 'Absent' AND ss.session_id = ?
                ORDER BY c.class, sec.section, s.firstname";
        $query = $this->db->query($sql, array($date, $session_id));
        return $query->result_array();
    }
}
