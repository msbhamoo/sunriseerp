<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Absenteefollowup_model extends MY_Model
{
    protected $current_session;

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function getAbsentLateStudents($date, $class_id = null, $section_id = null)
    {
        // 3 = Late, 4 = Absent, 6 = Half Day (typically considered late/absent, but we'll stick to 3, 4 based on typical usage)
        $this->db->select("
            student_attendences.id as attendence_id, 
            student_attendences.date, 
            student_attendences.attendence_type_id, 
            attendence_type.type as att_type,
            students.id as student_id,
            students.firstname, 
            students.middlename, 
            students.lastname, 
            students.roll_no, 
            students.admission_no, 
            students.guardian_phone, 
            students.father_name,
            students.mobileno,
            student_session.id as student_session_id,
            classes.class,
            sections.section,
            attendance_followup_log.followup_status,
            attendance_followup_log.remark
        ");
        $this->db->from('student_attendences');
        $this->db->join('attendence_type', 'attendence_type.id = student_attendences.attendence_type_id');
        $this->db->join('student_session', 'student_session.id = student_attendences.student_session_id');
        $this->db->join('students', 'students.id = student_session.student_id');
        $this->db->join('classes', 'classes.id = student_session.class_id');
        $this->db->join('sections', 'sections.id = student_session.section_id');
        
        // Join with the latest follow-up log for this student and date
        $this->db->join(
            '(SELECT student_session_id, date, followup_status, remark FROM attendance_followup_log WHERE date = ' . $this->db->escape($date) . ' AND id IN (SELECT MAX(id) FROM attendance_followup_log WHERE date = ' . $this->db->escape($date) . ' GROUP BY student_session_id)) as attendance_followup_log',
            'attendance_followup_log.student_session_id = student_attendences.student_session_id AND attendance_followup_log.date = student_attendences.date',
            'left'
        );

        $this->db->where('student_attendences.date', $date);
        $this->db->where('student_session.session_id', $this->current_session);
        $this->db->where_in('student_attendences.attendence_type_id', [3, 4, 6]); // 3=Late, 4=Absent, 6=Half Day
        $this->db->where('students.is_active', 'yes');

        if ($class_id != null) {
            $this->db->where('student_session.class_id', $class_id);
        }
        if ($section_id != null) {
            $this->db->where('student_session.section_id', $section_id);
        }

        $this->db->order_by('classes.id, sections.id, students.firstname');
        
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getConsecutiveAbsenceCount($student_session_id, $date)
    {
        // Fetch all attendance for this student up to the selected date, ordered by date descending
        $this->db->select('date, attendence_type_id');
        $this->db->from('student_attendences');
        $this->db->where('student_session_id', $student_session_id);
        $this->db->where('date <=', $date);
        $this->db->order_by('date', 'DESC');
        
        $query = $this->db->get();
        $records = $query->result_array();

        $consecutive_days = 0;
        foreach ($records as $row) {
            // If attendence type is 4 (Absent), we increment.
            // If it's a holiday (5) or excuse (2), we might skip or reset depending on school rules.
            // Let's assume consecutive absent days are interrupted by present (1), late (3), or half day (6)
            if ($row['attendence_type_id'] == 4) {
                $consecutive_days++;
            } elseif ($row['attendence_type_id'] == 5) {
                // Ignore holidays for consecutive working days count
                continue;
            } else {
                // Found a working day where the student was NOT absent. Break consecutive count.
                break;
            }
        }
        return $consecutive_days;
    }

    public function saveLog($data)
    {
        $this->db->insert('attendance_followup_log', $data);
        return $this->db->insert_id();
    }

    public function getLogs($student_session_id, $date)
    {
        $this->db->select('attendance_followup_log.*, staff.name, staff.surname, staff.employee_id');
        $this->db->from('attendance_followup_log');
        $this->db->join('staff', 'staff.id = attendance_followup_log.created_by', 'left');
        $this->db->where('student_session_id', $student_session_id);
        $this->db->where('date', $date);
        $this->db->order_by('attendance_followup_log.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getAllLogsByStudent($student_session_id)
    {
        $this->db->select('attendance_followup_log.*, staff.name, staff.surname, staff.employee_id');
        $this->db->from('attendance_followup_log');
        $this->db->join('staff', 'staff.id = attendance_followup_log.created_by', 'left');
        $this->db->where('student_session_id', $student_session_id);
        $this->db->order_by('attendance_followup_log.date', 'DESC');
        $this->db->order_by('attendance_followup_log.created_at', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }
}
