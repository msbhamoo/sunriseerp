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

    /**
     * Fallback for any method defined in CI3 legacy model but not yet implemented natively in CI4.
     */
    public function __call(string $name, array $params)
    {
        // Infer legacy model name from CI4 model class
        $shortName = (new \ReflectionClass($this))->getShortName();
        // E.g., SettingModel -> Setting_model
        $legacyName = preg_replace('/Model$/', '_model', $shortName);

        static $legacyInstances = [];

        if (!isset($legacyInstances[$legacyName])) {
            require_once APPPATH . '../application/core/MY_Model.php';
            $paths = [
                APPPATH . '../application/models/' . $legacyName . '.php',
                APPPATH . '../application/models/' . strtolower($legacyName) . '.php',
                APPPATH . '../application/models/' . ucfirst($legacyName) . '.php',
            ];

            foreach ($paths as $path) {
                if (file_exists($path)) {
                    require_once $path;
                    $class = basename($path, '.php');
                    if (class_exists($class)) {
                        $legacyInstances[$legacyName] = new $class();
                        break;
                    }
                }
            }
        }

        if (isset($legacyInstances[$legacyName]) && method_exists($legacyInstances[$legacyName], $name)) {
            return call_user_func_array([$legacyInstances[$legacyName], $name], $params);
        }

        return parent::__call($name, $params);
    }
}
