<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * ModuleModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Module_model.
 */
class ModuleModel extends BaseModel
{
    protected $table = 'module';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function getPermission()
    {
        return $this->callLegacyModel('Module_model', 'getPermission', func_get_args());
    }

    public function getParentPermission()
    {
        return $this->callLegacyModel('Module_model', 'getParentPermission', func_get_args());
    }

    public function getStudentPermission()
    {
        return $this->callLegacyModel('Module_model', 'getStudentPermission', func_get_args());
    }

    public function changeStatus($data)
    {
        return $this->callLegacyModel('Module_model', 'changeStatus', func_get_args());
    }

    public function getPermissionByModulename($module_name)
    {
        return $this->callLegacyModel('Module_model', 'getPermissionByModulename', func_get_args());
    }

    public function get($id = null)
    {
        return $this->callLegacyModel('Module_model', 'get', func_get_args());
    }

    public function get_parent($id = null)
    {
        return $this->callLegacyModel('Module_model', 'get_parent', func_get_args());
    }

    public function get_student($id = null)
    {
        return $this->callLegacyModel('Module_model', 'get_student', func_get_args());
    }

    public function get_userpermission($role)
    {
        return $this->callLegacyModel('Module_model', 'get_userpermission', func_get_args());
    }

    public function changeParentStatus($data)
    {
        return $this->callLegacyModel('Module_model', 'changeParentStatus', func_get_args());
    }

    public function changeStudentStatus($data)
    {
        return $this->callLegacyModel('Module_model', 'changeStudentStatus', func_get_args());
    }

    public function hasModule($module_shortcode)
    {
        return $this->callLegacyModel('Module_model', 'hasModule', func_get_args());
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