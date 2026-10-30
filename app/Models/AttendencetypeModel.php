<?php

namespace App\Models;

/**
 * AttendencetypeModel
 *
 * Model for student and staff attendance types (`attendence_type`, `staff_attendance_type`).
 */
class AttendencetypeModel extends BaseModel
{
    protected $table = 'attendence_type';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['type', 'key_value', 'is_active', 'for_schedule'];

    public function get(?int $id = null): array
    {
        if ($id !== null) {
            $row = $this->find($id);
            return $row ?: [];
        }

        return $this->where('is_active', 'yes')
            ->orderBy('id', 'ASC')
            ->findAll();
    }

    public function getAttType(?int $id = null): array
    {
        if ($id !== null) {
            $row = $this->find($id);
            return $row ?: [];
        }

        return $this->orderBy('id', 'ASC')->findAll();
    }

    public function getStaffAttendanceType(?int $id = null): array
    {
        $builder = $this->db->table('staff_attendance_type');
        if ($id !== null) {
            $builder->where('id', $id);
            $row = $builder->get()->getRowArray();
            return $row ?: [];
        }

        return $builder->where('is_active', 'yes')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getStudentAttendence(string $date, int $studentSessionId): ?object
    {
        return $this->db->table('student_attendences')
            ->select('attendence_type.type')
            ->join('attendence_type', 'attendence_type.id = student_attendences.attendence_type_id')
            ->where('student_attendences.student_session_id', $studentSessionId)
            ->where('student_attendences.date', $date)
            ->get()
            ->getRow();
    }
}
