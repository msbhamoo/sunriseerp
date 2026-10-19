<?php

namespace App\Models;

/**
 * StuattendenceModel
 *
 * Model for managing student daily attendance (`student_attendences`).
 */
class StuattendenceModel extends BaseModel
{
    protected $table = 'student_attendences';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'student_session_id', 'attendence_type_id', 'date', 'remark',
        'in_time', 'out_time', 'biometric_attendence', 'qrcode_attendance',
        'biometric_device_data', 'user_agent'
    ];

    /**
     * Get attendance record by ID or all records
     */
    public function get(?int $id = null): array
    {
        if ($id !== null) {
            $row = $this->find($id);
            return $row ?: [];
        }

        return $this->orderBy('id', 'ASC')->findAll();
    }

    /**
     * Search attendance for a class and section on a specific date.
     */
    public function searchAttendenceClassSection(int $classId, int $sectionId, string $date): array
    {
        $sessionModel = new SessionModel();
        $currentSession = (int)$sessionModel->getCurrentSession();

        $sql = "SELECT student_sessions.in_time, student_sessions.out_time, student_sessions.attendence_id,
                       student_sessions.attendence_dt, students.firstname, students.middlename, students.lastname,
                       student_sessions.date, student_sessions.remark, student_sessions.biometric_attendence,
                       student_sessions.qrcode_attendance, student_sessions.biometric_device_data, student_sessions.user_agent,
                       students.roll_no, students.admission_no, students.id AS std_id, student_sessions.attendence_type_id,
                       student_sessions.id AS student_session_id, attendence_type.type AS att_type,
                       attendence_type.key_value AS `key`, attendence_type.long_lang_name, attendence_type.long_name_style
                FROM students,
                     (SELECT student_attendences.in_time, student_attendences.out_time, student_session.id,
                             student_session.student_id, IFNULL(student_attendences.date, 'xxx') AS date,
                             IFNULL(student_attendences.created_at, 'xxx') AS attendence_dt,
                             student_attendences.remark, student_attendences.biometric_attendence,
                             student_attendences.user_agent, student_attendences.biometric_device_data,
                             student_attendences.qrcode_attendance, IFNULL(student_attendences.id, 0) AS attendence_id,
                             student_attendences.attendence_type_id
                      FROM student_session
                      LEFT JOIN student_attendences ON student_attendences.student_session_id = student_session.id
                           AND student_attendences.date = ?
                      WHERE student_session.session_id = ?
                        AND student_session.class_id = ?
                        AND student_session.section_id = ?
                     ) AS student_sessions
                LEFT JOIN attendence_type ON attendence_type.id = student_sessions.attendence_type_id
                WHERE student_sessions.student_id = students.id
                  AND students.is_active = 'yes'
                ORDER BY students.admission_no ASC";

        return $this->db->query($sql, [$date, $currentSession, $classId, $sectionId])->getResultArray();
    }

    /**
     * Save attendance records (bulk insert & update)
     */
    public function add(array $insertArray = [], array $updateArray = []): bool
    {
        $this->db->transStart();

        if (!empty($insertArray)) {
            $this->db->table('student_attendences')->insertBatch($insertArray);
        }

        if (!empty($updateArray)) {
            $this->db->table('student_attendences')->updateBatch($updateArray, 'id');
        }

        $this->db->transComplete();

        return $this->db->transStatus();
    }

    /**
     * Search attendance report for class and section on date
     */
    public function searchAttendenceReport(int $classId, int $sectionId, string $date): array
    {
        $sessionModel = new SessionModel();
        $currentSession = (int)$sessionModel->getCurrentSession();

        $sql = "SELECT student_sessions.attendence_id, students.firstname, students.middlename, student_sessions.date,
                       student_sessions.remark, students.roll_no, students.admission_no, students.lastname,
                       student_sessions.attendence_type_id, student_sessions.id AS student_session_id,
                       attendence_type.type AS att_type, attendence_type.key_value AS `key`
                FROM students,
                     (SELECT student_session.id, student_session.student_id, IFNULL(student_attendences.date, 'xxx') AS date,
                             student_attendences.remark, IFNULL(student_attendences.id, 0) AS attendence_id,
                             student_attendences.attendence_type_id
                      FROM student_session
                      LEFT JOIN student_attendences ON student_attendences.student_session_id = student_session.id
                           AND student_attendences.date = ?
                      WHERE student_session.session_id = ?
                        AND student_session.class_id = ?
                        AND student_session.section_id = ?
                     ) AS student_sessions
                LEFT JOIN attendence_type ON attendence_type.id = student_sessions.attendence_type_id
                WHERE student_sessions.student_id = students.id
                  AND students.is_active = 'yes'";

        return $this->db->query($sql, [$date, $currentSession, $classId, $sectionId])->getResultArray();
    }
}
