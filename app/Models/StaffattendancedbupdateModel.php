<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * StaffattendancedbupdateModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Staffattendancedbupdate_model.
 */
class StaffattendancedbupdateModel extends BaseModel
{
    protected $table = 'staffattendancedbupdate';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function ensure()
    {
        return $this->callLegacyModel('Staffattendancedbupdate_model', 'ensure', func_get_args());
    }

    public function isCurrent()
    {
        return $this->callLegacyModel('Staffattendancedbupdate_model', 'isCurrent', func_get_args());
    }

    public function getRecord()
    {
        return $this->callLegacyModel('Staffattendancedbupdate_model', 'getRecord', func_get_args());
    }

    public function run($by = 'auto')
    {
        return $this->callLegacyModel('Staffattendancedbupdate_model', 'run', func_get_args());
    }

    public function compareWithBackup($prefix)
    {
        return $this->callLegacyModel('Staffattendancedbupdate_model', 'compareWithBackup', func_get_args());
    }

    public function restoreFromBackup($prefix)
    {
        return $this->callLegacyModel('Staffattendancedbupdate_model', 'restoreFromBackup', func_get_args());
    }

    public function listBackups()
    {
        return $this->callLegacyModel('Staffattendancedbupdate_model', 'listBackups', func_get_args());
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