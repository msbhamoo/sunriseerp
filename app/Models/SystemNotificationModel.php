<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * SystemNotificationModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy SystemNotification_model.
 */
class SystemNotificationModel extends BaseModel
{
    protected $table = 'systemnotification';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function addNotification($data)
    {
        return $this->callLegacyModel('SystemNotification_model', 'addNotification', func_get_args());
    }

    public function notifyRole($role_id, $title, $message, $action_url = '')
    {
        return $this->callLegacyModel('SystemNotification_model', 'notifyRole', func_get_args());
    }

    public function notifyUser($user_id, $title, $message, $action_url = '')
    {
        return $this->callLegacyModel('SystemNotification_model', 'notifyUser', func_get_args());
    }

    public function getAllNotifications($staff_id, $role_id)
    {
        return $this->callLegacyModel('SystemNotification_model', 'getAllNotifications', func_get_args());
    }

    public function getUnreadCount($staff_id, $role_id)
    {
        return $this->callLegacyModel('SystemNotification_model', 'getUnreadCount', func_get_args());
    }

    public function markAsRead($notification_id)
    {
        return $this->callLegacyModel('SystemNotification_model', 'markAsRead', func_get_args());
    }

    public function markAllAsRead($staff_id, $role_id)
    {
        return $this->callLegacyModel('SystemNotification_model', 'markAllAsRead', func_get_args());
    }

    /**
     * Compatibility Bridge: Delegates execution to the legacy model instance
     * maintaining full compatibility with query builder and CI3 environment.
     */
    protected function callLegacyModel(string $modelClass, string $method, array $args)
    {
        static $instances = [];

        if (!isset($instances[$modelClass])) {
            require_once APPPATH . '../application/core/MY_Model.php';
            $file = APPPATH . '../application/models/' . ($this->subPath() ? $this->subPath() . '/' : '') . $modelClass . '.php';
            if (file_exists($file)) {
                require_once $file;
                $instances[$modelClass] = new $modelClass();
            } else {
                return null;
            }
        }

        return call_user_func_array([$instances[$modelClass], $method], $args);
    }

    protected function subPath(): string
    {
        return '';
    }
}