<?php

namespace App\Models;

/**
 * PtmModel
 *
 * Model for Parent Teacher Meetings (PTM), targets, and attendance.
 */
class PtmModel extends BaseModel
{
    protected $table = 'ptms';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'title', 'ptm_date', 'time_from', 'time_to', 'venue',
        'description', 'target_type', 'created_by'
    ];

    /**
     * Get all PTMs or a single PTM by ID.
     */
    public function get(?int $id = null): array
    {
        $builder = $this->db->table('ptms')
            ->select('ptms.*, staff.name as staff_name, staff.surname as staff_surname')
            ->join('staff', 'staff.id = ptms.created_by', 'left');

        if ($id !== null) {
            $builder->where('ptms.id', $id);
            $result = $builder->get()->getRowArray();
            return $result ?: [];
        }

        $builder->orderBy('ptms.ptm_date', 'DESC');
        return $builder->get()->getResultArray();
    }

    /**
     * Get target classes and sections for a PTM.
     */
    public function getTargets(int $ptmId): array
    {
        return $this->db->table('ptm_targets')
            ->select('ptm_targets.*, classes.class, sections.section')
            ->join('classes', 'classes.id = ptm_targets.class_id', 'left')
            ->join('sections', 'sections.id = ptm_targets.section_id', 'left')
            ->where('ptm_targets.ptm_id', $ptmId)
            ->get()
            ->getResultArray();
    }

    /**
     * Insert or update a PTM record.
     */
    public function savePtm(array $data): int|bool
    {
        $this->db->transStart();

        if (!empty($data['id'])) {
            $id = (int)$data['id'];
            $this->db->table('ptms')->where('id', $id)->update($data);
            $action = 'Update';
            $recordId = $id;
        } else {
            $this->db->table('ptms')->insert($data);
            $recordId = (int)$this->db->insertID();
            $action = 'Insert';
        }

        $this->logAudit("PTM {$action} on ID {$recordId}", $recordId, $action, $data['created_by'] ?? null);

        $this->db->transComplete();

        return $this->db->transStatus() !== false ? $recordId : false;
    }

    /**
     * Delete a PTM and its associated targets and attendances.
     */
    public function deletePtm(int $id): bool
    {
        $this->db->transStart();

        $this->db->table('ptm_attendances')->where('ptm_id', $id)->delete();
        $this->db->table('ptm_targets')->where('ptm_id', $id)->delete();
        $this->db->table('ptms')->where('id', $id)->delete();

        $this->logAudit("PTM Deleted on ID {$id}", $id, 'Delete');

        $this->db->transComplete();

        return $this->db->transStatus() !== false;
    }

    /**
     * Get attendance records for a PTM.
     */
    public function getStudentAttendances(int $ptmId): array
    {
        $results = $this->db->table('ptm_attendances')
            ->select('ptm_attendances.*, staff.name as assigned_staff_name, staff.surname as assigned_staff_surname, staff.employee_id')
            ->join('staff', 'staff.id = ptm_attendances.followup_assigned_to', 'left')
            ->where('ptm_id', $ptmId)
            ->get()
            ->getResultArray();

        $attendances = [];
        foreach ($results as $row) {
            $attendances[$row['student_session_id']] = $row;
        }

        return $attendances;
    }

    /**
     * Save student PTM attendance record.
     */
    public function saveAttendance(array $data): int
    {
        $existing = $this->db->table('ptm_attendances')
            ->where('ptm_id', $data['ptm_id'])
            ->where('student_session_id', $data['student_session_id'])
            ->get()
            ->getRowArray();

        if (!empty($existing)) {
            $this->db->table('ptm_attendances')->where('id', $existing['id'])->update($data);
            return (int)$existing['id'];
        }

        $this->db->table('ptm_attendances')->insert($data);
        return (int)$this->db->insertID();
    }
}
