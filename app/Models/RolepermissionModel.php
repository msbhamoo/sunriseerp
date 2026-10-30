<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * RolepermissionModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Rolepermission_model.
 */
class RolepermissionModel extends BaseModel
{
    protected $table = 'rolepermission';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function getPermissionByRole($role_id)
    {
        return $this->callLegacyModel('Rolepermission_model', 'getPermissionByRole', func_get_args());
    }

    public function getPermissionByRoleandCategory($role_id, $category)
    {
        return $this->callLegacyModel('Rolepermission_model', 'getPermissionByRoleandCategory', func_get_args());
    }

    public function getInsertBatch($insert_array, $role_id, $delete_array)
    {
        return $this->callLegacyModel('Rolepermission_model', 'getInsertBatch', func_get_args());
    }

    public function getPermissionWithSelectedByRole($role_id)
    {
        return $this->callLegacyModel('Rolepermission_model', 'getPermissionWithSelectedByRole', func_get_args());
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