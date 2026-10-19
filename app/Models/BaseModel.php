<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * BaseModel
 *
 * Base model for CI4 migration layer.
 * Provides logging and utility methods shared across models.
 */
class BaseModel extends Model
{
    /**
     * Write an audit log entry to the `logs` table.
     */
    public function logAudit(?string $message = null, ?int $recordId = null, ?string $action = null, ?int $userId = null): bool
    {
        $request = service('request');
        $agent = $request->getUserAgent();

        if ($agent->isBrowser()) {
            $agentString = $agent->getBrowser() . ' ' . $agent->getVersion();
        } elseif ($agent->isRobot()) {
            $agentString = $agent->getRobot();
        } elseif ($agent->isMobile()) {
            $agentString = $agent->getMobile();
        } else {
            $agentString = 'Unidentified User Agent';
        }

        $platform = $agent->getPlatform();

        $data = [
            'message'    => $message,
            'user_id'    => $userId,
            'record_id'  => $recordId,
            'ip_address' => $request->getIPAddress(),
            'platform'   => $platform,
            'agent'      => $agentString,
            'action'     => $action,
            'time'       => date('Y-m-d H:i:s'),
        ];

        return (bool) $this->db->table('logs')->insert($data);
    }
}
