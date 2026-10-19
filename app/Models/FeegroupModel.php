<?php

namespace App\Models;

/**
 * FeegroupModel
 *
 * Model for fee groups (`fee_groups`, `fee_groups_classes`).
 */
class FeegroupModel extends BaseModel
{
    protected $table = 'fee_groups';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['name', 'is_system', 'nature', 'description'];

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

        $classIds = [];
        if (isset($data['class_id'])) {
            $classIds = (array)$data['class_id'];
            unset($data['class_id']);
        }

        if (isset($data['id'])) {
            $id = (int)$data['id'];
            $this->update($id, $data);
            $this->logAudit("Update On fee group id {$id}", $id, 'Update');
        } else {
            $id = (int)$this->insert($data);
            $this->logAudit("Insert On fee group id {$id}", $id, 'Insert');
        }

        if (!empty($id)) {
            $this->db->table('fee_groups_classes')->where('fee_groups_id', $id)->delete();
            if (!empty($classIds)) {
                $insertClasses = [];
                foreach ($classIds as $cId) {
                    $insertClasses[] = [
                        'fee_groups_id' => $id,
                        'class_id'      => (int)$cId,
                    ];
                }
                $this->db->table('fee_groups_classes')->insertBatch($insertClasses);
            }
        }

        $this->db->transComplete();

        return $this->db->transStatus() !== false ? $id : false;
    }

    public function remove(int $id): bool
    {
        $this->db->transStart();
        $this->where('id', $id)->where('is_system', 0)->delete();
        $this->db->table('fee_groups_classes')->where('fee_groups_id', $id)->delete();
        $this->logAudit("Delete On fee group id {$id}", $id, 'Delete');
        $this->db->transComplete();

        return $this->db->transStatus();
    }

    public function check_data_exists(string $name, int $id = 0): bool
    {
        $builder = $this->where('name', $name);
        if ($id > 0) {
            $builder->where('id !=', $id);
        }
        return $builder->countAllResults() > 0;
    }

    public function get_mapped_classes(int $feeGroupId): array
    {
        $rows = $this->db->table('fee_groups_classes')
            ->select('class_id')
            ->where('fee_groups_id', $feeGroupId)
            ->get()
            ->getResultArray();

        return array_column($rows, 'class_id');
    }

    public function get_mapped_class_names(int $feeGroupId): array
    {
        $rows = $this->db->table('fee_groups_classes')
            ->select('classes.class')
            ->join('classes', 'classes.id = fee_groups_classes.class_id')
            ->where('fee_groups_classes.fee_groups_id', $feeGroupId)
            ->get()
            ->getResultArray();

        return array_column($rows, 'class');
    }
}
