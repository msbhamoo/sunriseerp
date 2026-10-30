<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * RoleModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Role_model.
 */
class RoleModel extends BaseModel
{
    protected $table = 'role';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($id = null)
    {
        return $this->callLegacyModel('Role_model', 'get', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Role_model', 'remove', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Role_model', 'add', func_get_args());
    }

    public function valid_check_exists($str)
    {
        return $this->callLegacyModel('Role_model', 'valid_check_exists', func_get_args());
    }

    public function check_data_exists($name, $id)
    {
        return $this->callLegacyModel('Role_model', 'check_data_exists', func_get_args());
    }

    public function find($id = null)
    {
        return $this->callLegacyModel('Role_model', 'find', func_get_args());
    }

    public function getPermissions($group_id, $role_id)
    {
        return $this->callLegacyModel('Role_model', 'getPermissions', func_get_args());
    }

    public function updatePermission($upate_array)
    {
        return $this->callLegacyModel('Role_model', 'updatePermission', func_get_args());
    }

    public function getInsertBatch($role_id, $to_be_insert = array(), $to_be_update = array(), $to_be_delete = array())
    {
        return $this->callLegacyModel('Role_model', 'getInsertBatch', func_get_args());
    }

    public function count_roles($id)
    {
        return $this->callLegacyModel('Role_model', 'count_roles', func_get_args());
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