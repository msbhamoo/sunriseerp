<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * DepartmentModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Department_model.
 */
class DepartmentModel extends BaseModel
{
    protected $table = 'department';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function valid_department($str)
    {
        return $this->callLegacyModel('Department_model', 'valid_department', func_get_args());
    }

    public function check_department_exists($name, $id)
    {
        return $this->callLegacyModel('Department_model', 'check_department_exists', func_get_args());
    }

    public function deleteDepartment($id)
    {
        return $this->callLegacyModel('Department_model', 'deleteDepartment', func_get_args());
    }

    public function getDepartmentType($id = null)
    {
        return $this->callLegacyModel('Department_model', 'getDepartmentType', func_get_args());
    }

    public function addDepartmentType($data)
    {
        return $this->callLegacyModel('Department_model', 'addDepartmentType', func_get_args());
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