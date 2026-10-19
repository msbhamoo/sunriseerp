<?php

namespace App\Models;

/**
 * FeesessiongroupModel
 *
 * Model for fee session groups and feetype assignments (`fee_session_groups`, `fee_groups_feetype`).
 */
class FeesessiongroupModel extends BaseModel
{
    protected $table = 'fee_session_groups';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['fee_groups_id', 'session_id'];

    public function getFeesByGroup(?int $id = null, ?int $displaySystem = null, ?int $classId = null): array
    {
        $sessionModel = new SessionModel();
        $currentSession = (int)$sessionModel->getCurrentSession();

        $builder = $this->db->table('fee_session_groups')
            ->select('fee_session_groups.*, fee_groups.name as group_name, fee_groups.is_system')
            ->join('fee_groups', 'fee_groups.id = fee_session_groups.fee_groups_id')
            ->where('fee_session_groups.session_id', $currentSession)
            ->where('fee_groups.nature !=', 'custom');

        if ($classId !== null) {
            $builder->join('fee_groups_classes', 'fee_groups_classes.fee_groups_id = fee_groups.id')
                ->where('fee_groups_classes.class_id', $classId);
        }

        if ($displaySystem !== null) {
            $builder->where('fee_groups.is_system', $displaySystem);
        }

        if ($id !== null) {
            $builder->where('fee_session_groups.id', $id);
        }

        $result = $builder->orderBy('fee_groups.id', 'ASC')->get()->getResult();

        foreach ($result as $value) {
            $value->feetypes = $this->getfeeTypeByGroup((int)$value->id, (int)$value->fee_groups_id);
        }

        return $result;
    }

    public function getfeeTypeByGroup(int $feeSessionGroupId, int $feeGroupId): array
    {
        $rows = $this->db->table('fee_groups_feetype')
            ->select('fee_groups_feetype.*, feetype.type, feetype.code')
            ->join('feetype', 'feetype.id = fee_groups_feetype.feetype_id')
            ->where('fee_groups_feetype.fee_groups_id', $feeGroupId)
            ->where('fee_groups_feetype.fee_session_group_id', $feeSessionGroupId)
            ->orderBy('fee_groups_feetype.id', 'ASC')
            ->get()
            ->getResult();

        foreach ($rows as $row) {
            $row->cumulative_fine_data = $this->getCumulativeFine((int)$row->id);
        }

        return $rows;
    }

    public function getCumulativeFine(int $feeGroupsFeetypeId): array
    {
        return $this->db->table('cumulative_fine')
            ->where('fee_groups_feetype_id', $feeGroupsFeetypeId)
            ->orderBy('id', 'ASC')
            ->get()
            ->getResult();
    }

    public function group_exists(int $feeGroupId): int
    {
        $sessionModel = new SessionModel();
        $currentSession = (int)$sessionModel->getCurrentSession();

        $existing = $this->where('fee_groups_id', $feeGroupId)
            ->where('session_id', $currentSession)
            ->first();

        if ($existing) {
            return (int)$existing['id'];
        }

        $insertId = $this->insert([
            'fee_groups_id' => $feeGroupId,
            'session_id'    => $currentSession,
        ]);

        return (int)$insertId;
    }

    public function remove(int $id): bool
    {
        $this->db->transStart();
        $this->db->table('fee_groups_feetype')->where('fee_session_group_id', $id)->delete();
        $this->where('id', $id)->delete();
        $this->logAudit("Delete On fee session groups id {$id}", $id, 'Delete');
        $this->db->transComplete();

        return $this->db->transStatus();
    }

    public function removeGroupFeetype(int $id): bool
    {
        $this->db->transStart();
        $this->db->table('cumulative_fine')->where('fee_groups_feetype_id', $id)->delete();
        $this->db->table('fee_groups_feetype')->where('id', $id)->delete();
        $this->logAudit("Delete On fee groups feetype id {$id}", $id, 'Delete');
        $this->db->transComplete();

        return $this->db->transStatus();
    }
}
