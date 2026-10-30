<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * UserlogModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Userlog_model.
 */
class UserlogModel extends BaseModel
{
    protected $table = 'userlog';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($id = null)
    {
        return $this->callLegacyModel('Userlog_model', 'get', func_get_args());
    }

    public function getByRole($role)
    {
        return $this->callLegacyModel('Userlog_model', 'getByRole', func_get_args());
    }

    public function getByRoleStaff()
    {
        return $this->callLegacyModel('Userlog_model', 'getByRoleStaff', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Userlog_model', 'add', func_get_args());
    }

    public function getAllRecord()
    {
        return $this->callLegacyModel('Userlog_model', 'getAllRecord', func_get_args());
    }

    public function getAllRecordByRole($role)
    {
        return $this->callLegacyModel('Userlog_model', 'getAllRecordByRole', func_get_args());
    }

    public function getAllRecordByStaff()
    {
        return $this->callLegacyModel('Userlog_model', 'getAllRecordByStaff', func_get_args());
    }

    public function userlog_delete()
    {
        return $this->callLegacyModel('Userlog_model', 'userlog_delete', func_get_args());
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