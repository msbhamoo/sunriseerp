<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * LeavetypesModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Leavetypes_model.
 */
class LeavetypesModel extends BaseModel
{
    protected $table = 'leavetypes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function addLeaveType($data)
    {
        return $this->callLegacyModel('Leavetypes_model', 'addLeaveType', func_get_args());
    }

    public function getLeaveType()
    {
        return $this->callLegacyModel('Leavetypes_model', 'getLeaveType', func_get_args());
    }

    public function deleteLeaveType($id)
    {
        return $this->callLegacyModel('Leavetypes_model', 'deleteLeaveType', func_get_args());
    }

    public function valid_leave_type($str)
    {
        return $this->callLegacyModel('Leavetypes_model', 'valid_leave_type', func_get_args());
    }

    public function check_data_exists($name, $id)
    {
        return $this->callLegacyModel('Leavetypes_model', 'check_data_exists', func_get_args());
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