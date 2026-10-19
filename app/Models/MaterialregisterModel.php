<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * MaterialregisterModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Materialregister_model.
 */
class MaterialregisterModel extends BaseModel
{
    protected $table = 'materialregister';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add($data, $items = array())
    {
        return $this->callLegacyModel('Materialregister_model', 'add', func_get_args());
    }

    public function get_items($material_register_id)
    {
        return $this->callLegacyModel('Materialregister_model', 'get_items', func_get_args());
    }

    public function get($id = null, $direction = null)
    {
        return $this->callLegacyModel('Materialregister_model', 'get', func_get_args());
    }

    public function get_masters($type = null)
    {
        return $this->callLegacyModel('Materialregister_model', 'get_masters', func_get_args());
    }

    public function add_master($type, $name)
    {
        return $this->callLegacyModel('Materialregister_model', 'add_master', func_get_args());
    }

    public function generate_gate_pass_no()
    {
        return $this->callLegacyModel('Materialregister_model', 'generate_gate_pass_no', func_get_args());
    }

    public function ensure_sidebar_title()
    {
        return $this->callLegacyModel('Materialregister_model', 'ensure_sidebar_title', func_get_args());
    }

    public function update($id, $data, $items = null)
    {
        return $this->callLegacyModel('Materialregister_model', 'update', func_get_args());
    }

    public function delete($id)
    {
        return $this->callLegacyModel('Materialregister_model', 'delete', func_get_args());
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