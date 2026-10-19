<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * StaffcomplianceModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Staffcompliance_model.
 */
class StaffcomplianceModel extends BaseModel
{
    protected $table = 'staffcompliance';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function getStaffComplianceList($user_type, $date)
    {
        return $this->callLegacyModel('Staffcompliance_model', 'getStaffComplianceList', func_get_args());
    }

    public function saveCompliance($compliance_data)
    {
        return $this->callLegacyModel('Staffcompliance_model', 'saveCompliance', func_get_args());
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