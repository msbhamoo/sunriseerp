<?php

namespace App\Models;

/**
 * SessionModel
 *
 * Model for academic sessions (table `sessions`).
 */
class SessionModel extends BaseModel
{
    protected $table = 'sessions';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['session', 'is_active'];

    /**
     * Get the active/current academic session ID from school settings.
     */
    public function getCurrentSessionId(): int
    {
        $setting = $this->db->table('sch_settings')->select('session_id')->get()->getRowArray();
        return !empty($setting['session_id']) ? (int)$setting['session_id'] : 1;
    }

    /**
     * Get the active academic session record.
     */
    public function getCurrentSession(): ?array
    {
        $currentId = $this->getCurrentSessionId();
        return $this->find($currentId);
    }

    /**
     * Get all academic sessions.
     */
    public function getAllSessions(): array
    {
        return $this->orderBy('id', 'ASC')->findAll();
    }
}
