<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * ExpenseheadModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Expensehead_model.
 */
class ExpenseheadModel extends BaseModel
{
    protected $table = 'expensehead';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function getDatatableExpenseHead()
    {
        return $this->callLegacyModel('Expensehead_model', 'getDatatableExpenseHead', func_get_args());
    }

    public function get($id = null)
    {
        return $this->callLegacyModel('Expensehead_model', 'get', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Expensehead_model', 'remove', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Expensehead_model', 'add', func_get_args());
    }

    public function searchexpensegroup($start_date, $end_date, $head_id = null)
    {
        return $this->callLegacyModel('Expensehead_model', 'searchexpensegroup', func_get_args());
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