<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * DispatchModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Dispatch_model.
 */
class DispatchModel extends BaseModel
{
    protected $table = 'dispatch';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function insert($table, $data)
    {
        return $this->callLegacyModel('Dispatch_model', 'insert', func_get_args());
    }

    public function image_add($type, $dispatch_id, $image)
    {
        return $this->callLegacyModel('Dispatch_model', 'image_add', func_get_args());
    }

    public function dispatch_list()
    {
        return $this->callLegacyModel('Dispatch_model', 'dispatch_list', func_get_args());
    }

    public function receive_list()
    {
        return $this->callLegacyModel('Dispatch_model', 'receive_list', func_get_args());
    }

    public function dis_rec_data($id, $type)
    {
        return $this->callLegacyModel('Dispatch_model', 'dis_rec_data', func_get_args());
    }

    public function update_dispatch($table, $id, $type, $data)
    {
        return $this->callLegacyModel('Dispatch_model', 'update_dispatch', func_get_args());
    }

    public function image_update($type, $id, $img_name)
    {
        return $this->callLegacyModel('Dispatch_model', 'image_update', func_get_args());
    }

    public function image_delete($id, $img_name)
    {
        return $this->callLegacyModel('Dispatch_model', 'image_delete', func_get_args());
    }

    public function delete($id)
    {
        return $this->callLegacyModel('Dispatch_model', 'delete', func_get_args());
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