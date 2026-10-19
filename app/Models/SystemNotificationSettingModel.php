<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * SystemNotificationSettingModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy SystemNotificationSetting_model.
 */
class SystemNotificationSettingModel extends BaseModel
{
    protected $table = 'systemnotificationsetting';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($type = null)
    {
        return $this->callLegacyModel('SystemNotificationSetting_model', 'get', func_get_args());
    }

    public function update($id = null, $row = null): bool
    {
        return $this->callLegacyModel('SystemNotificationSetting_model', 'update', func_get_args());
    }

    public function check_setting($type)
    {
        return $this->callLegacyModel('SystemNotificationSetting_model', 'check_setting', func_get_args());
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