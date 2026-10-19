<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * StaffBiometricSettingModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy StaffBiometricSetting_model.
 */
class StaffBiometricSettingModel extends BaseModel
{
    protected $table = 'staffbiometricsetting';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get()
    {
        return $this->callLegacyModel('StaffBiometricSetting_model', 'get', func_get_args());
    }

    public function save($data)
    {
        return $this->callLegacyModel('StaffBiometricSetting_model', 'save', func_get_args());
    }

    public function logSync($log_data)
    {
        return $this->callLegacyModel('StaffBiometricSetting_model', 'logSync', func_get_args());
    }

    public function getLogs($limit = 50)
    {
        return $this->callLegacyModel('StaffBiometricSetting_model', 'getLogs', func_get_args());
    }

    public function getStaffBiometricMappingList()
    {
        return $this->callLegacyModel('StaffBiometricSetting_model', 'getStaffBiometricMappingList', func_get_args());
    }

    public function updateStaffBiometricId($staff_id, $biometric_emp_id)
    {
        return $this->callLegacyModel('StaffBiometricSetting_model', 'updateStaffBiometricId', func_get_args());
    }

    public function getStaffExportListForBiometric()
    {
        return $this->callLegacyModel('StaffBiometricSetting_model', 'getStaffExportListForBiometric', func_get_args());
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