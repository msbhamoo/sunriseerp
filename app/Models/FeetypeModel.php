<?php

namespace App\Models;

/**
 * FeetypeModel
 *
 * Model for fee types (`feetype`).
 */
class FeetypeModel extends BaseModel
{
    protected $table = 'feetype';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['type', 'code', 'description', 'is_system', 'nature', 'feecategory_id', 'student_session_id'];

    public function get(?int $id = null): array
    {
        $builder = $this->where('is_system', 0)
            ->where('nature !=', 'custom');

        if ($id !== null) {
            $row = $builder->find($id);
            return $row ?: [];
        }

        return $builder->orderBy('id', 'ASC')->findAll();
    }

    public function add(array $data): int|bool
    {
        $this->db->transStart();

        if (isset($data['id'])) {
            $id = (int)$data['id'];
            $this->update($id, $data);
            $this->logAudit("Update On fee type id {$id}", $id, 'Update');
        } else {
            $id = (int)$this->insert($data);
            $this->logAudit("Insert On fee type id {$id}", $id, 'Insert');
        }

        $this->db->transComplete();

        return $this->db->transStatus() !== false ? $id : false;
    }

    public function remove(int $id): bool
    {
        $this->db->transStart();
        $this->where('id', $id)->where('is_system', 0)->delete();
        $this->logAudit("Delete On fee type id {$id}", $id, 'Delete');
        $this->db->transComplete();

        return $this->db->transStatus();
    }

    public function check_data_exists(string $name, int $id = 0): bool
    {
        $builder = $this->where('type', $name);
        if ($id > 0) {
            $builder->where('id !=', $id);
        }
        return $builder->countAllResults() > 0;
    }
}
