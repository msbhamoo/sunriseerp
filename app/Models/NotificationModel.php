<?php

namespace App\Models;

/**
 * NotificationModel
 *
 * Model for school notifications (`send_notification`, `notification_roles`, `read_notification`).
 */
class NotificationModel extends BaseModel
{
    protected $table = 'send_notification';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'title', 'message', 'attachment', 'publish_date', 'date',
        'visible_student', 'visible_staff', 'visible_parent', 'created_id', 'created_by'
    ];

    public function insertBatch(array $data, array $staffRoles = [], array $deleteArray = []): ?int
    {
        $this->db->transStart();

        if (isset($data['id'])) {
            $insertId = (int)$data['id'];
            $this->db->table('send_notification')->where('id', $insertId)->update($data);
        } else {
            $this->db->table('send_notification')->insert($data);
            $insertId = (int)$this->db->insertID();
        }

        if (!empty($staffRoles)) {
            foreach ($staffRoles as $k => $v) {
                $staffRoles[$k]['send_notification_id'] = $insertId;
            }
            $this->db->table('notification_roles')->insertBatch($staffRoles);
        }

        if (!empty($deleteArray)) {
            $this->db->table('notification_roles')
                ->where('send_notification_id', $insertId)
                ->whereIn('role_id', $deleteArray)
                ->delete();
        }

        $this->db->transComplete();

        if ($this->db->transStatus() === false) {
            $this->db->transRollback();
            return null;
        }

        return $insertId;
    }
}
