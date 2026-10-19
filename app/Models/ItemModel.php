<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * ItemModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Item_model.
 */
class ItemModel extends BaseModel
{
    protected $table = 'item';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function getItemByCategory($item_category_id)
    {
        return $this->callLegacyModel('Item_model', 'getItemByCategory', func_get_args());
    }

    public function getItemunit($id)
    {
        return $this->callLegacyModel('Item_model', 'getItemunit', func_get_args());
    }

    public function valid_check_exists($str)
    {
        return $this->callLegacyModel('Item_model', 'valid_check_exists', func_get_args());
    }

    public function check_data_exists($name, $item_category_id, $id)
    {
        return $this->callLegacyModel('Item_model', 'check_data_exists', func_get_args());
    }

    public function get($id = null)
    {
        return $this->callLegacyModel('Item_model', 'get', func_get_args());
    }

    public function getItemAvailable($item_id = null)
    {
        return $this->callLegacyModel('Item_model', 'getItemAvailable', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Item_model', 'remove', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Item_model', 'add', func_get_args());
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