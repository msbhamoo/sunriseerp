<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * ComplaintTypeModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy ComplaintType_model.
 */
class ComplaintTypeModel extends BaseModel
{
    protected $table = 'complainttype';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add($table, $data)
    {
        return $this->callLegacyModel('ComplaintType_model', 'add', func_get_args());
    }

    public function get($table, $id = null)
    {
        return $this->callLegacyModel('ComplaintType_model', 'get', func_get_args());
    }

    public function update($table, $complaint_type_id, $data)
    {
        return $this->callLegacyModel('ComplaintType_model', 'update', func_get_args());
    }

    public function delete($table, $id)
    {
        return $this->callLegacyModel('ComplaintType_model', 'delete', func_get_args());
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