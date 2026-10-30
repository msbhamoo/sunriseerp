<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * AccexpensetypeModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Accexpensetype_model.
 */
class AccexpensetypeModel extends BaseModel
{
    protected $table = 'accexpensetype';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add($data)
    {
        return $this->callLegacyModel('Accexpensetype_model', 'add', func_get_args());
    }

    public function get($id = null)
    {
        return $this->callLegacyModel('Accexpensetype_model', 'get', func_get_args());
    }

    public function getByType($type)
    {
        return $this->callLegacyModel('Accexpensetype_model', 'getByType', func_get_args());
    }

    public function delete($id = null, bool $purge = false)
    {
        return $this->callLegacyModel('Accexpensetype_model', 'delete', func_get_args());
    }

    public function getDatatableExpenseTypes()
    {
        return $this->callLegacyModel('Accexpensetype_model', 'getDatatableExpenseTypes', func_get_args());
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