<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * VisitorsPurposeModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Visitors_purpose_model.
 */
class VisitorsPurposeModel extends BaseModel
{
    protected $table = 'visitors_purpose';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add($visitors_purpose)
    {
        return $this->callLegacyModel('Visitors_purpose_model', 'add', func_get_args());
    }

    public function visitors_purpose_list($id = null)
    {
        return $this->callLegacyModel('Visitors_purpose_model', 'visitors_purpose_list', func_get_args());
    }

    public function delete($id)
    {
        return $this->callLegacyModel('Visitors_purpose_model', 'delete', func_get_args());
    }

    public function update($id, $data)
    {
        return $this->callLegacyModel('Visitors_purpose_model', 'update', func_get_args());
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