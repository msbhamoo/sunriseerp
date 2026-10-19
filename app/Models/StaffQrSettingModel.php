<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * StaffQrSettingModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy StaffQrSetting_model.
 */
class StaffQrSettingModel extends BaseModel
{
    protected $table = 'staffqrsetting';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get()
    {
        return $this->callLegacyModel('StaffQrSetting_model', 'get', func_get_args());
    }

    public function save($data)
    {
        return $this->callLegacyModel('StaffQrSetting_model', 'save', func_get_args());
    }

    public function generateToken()
    {
        return $this->callLegacyModel('StaffQrSetting_model', 'generateToken', func_get_args());
    }

    public function getValidToken()
    {
        return $this->callLegacyModel('StaffQrSetting_model', 'getValidToken', func_get_args());
    }

    public function isTokenValid($token)
    {
        return $this->callLegacyModel('StaffQrSetting_model', 'isTokenValid', func_get_args());
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