<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * GeneralCallModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy General_call_model.
 */
class GeneralCallModel extends BaseModel
{
    protected $table = 'general_call';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add($data)
    {
        return $this->callLegacyModel('General_call_model', 'add', func_get_args());
    }

    public function call_list($id = null)
    {
        return $this->callLegacyModel('General_call_model', 'call_list', func_get_args());
    }

    public function getcalllist($id = null)
    {
        return $this->callLegacyModel('General_call_model', 'getcalllist', func_get_args());
    }

    public function delete($id)
    {
        return $this->callLegacyModel('General_call_model', 'delete', func_get_args());
    }

    public function call_update($id, $data)
    {
        return $this->callLegacyModel('General_call_model', 'call_update', func_get_args());
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