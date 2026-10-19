<?php

namespace App\Models;

/**
 * StudentcallModel
 *
 * Model for student call logs and follow-up activities (`student_calls`, `student_call_followups`, `student_call_purpose`).
 */
class StudentcallModel extends BaseModel
{
    protected $table = 'student_calls';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'student_id', 'student_session_id', 'call_purpose_id', 'call_status',
        'follow_up_date', 'notes', 'created_by', 'created_at', 'updated_at'
    ];

    public function get_purposes(): array
    {
        return $this->db->table('student_call_purpose')
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();
    }

    public function auto_resolve_zero_due_fee_followups(): void
    {
        // Safe no-op / basic implementation
    }

    public function get_pending_followup_calls(string $filter = 'all'): array
    {
        return [];
    }

    public function add_call(array $data): int
    {
        $id = $this->insert($data);
        return (int)$id;
    }

    public function add_followup(array $data): int
    {
        $this->db->table('student_call_followups')->insert($data);
        return (int)$this->db->insertID();
    }
}
