<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * ExpenseModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Expense_model.
 */
class ExpenseModel extends BaseModel
{
    protected $table = 'expense';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function search($text = null, $start_date = null, $end_date = null)
    {
        return $this->callLegacyModel('Expense_model', 'search', func_get_args());
    }

    public function get($id = null)
    {
        return $this->callLegacyModel('Expense_model', 'get', func_get_args());
    }

    public function getexpenselist($id = null)
    {
        return $this->callLegacyModel('Expense_model', 'getexpenselist', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Expense_model', 'remove', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Expense_model', 'add', func_get_args());
    }

    public function check_Exits_group($data)
    {
        return $this->callLegacyModel('Expense_model', 'check_Exits_group', func_get_args());
    }

    public function getTypeByFeecategory($type, $class_id)
    {
        return $this->callLegacyModel('Expense_model', 'getTypeByFeecategory', func_get_args());
    }

    public function getTotalExpenseBydate($date)
    {
        return $this->callLegacyModel('Expense_model', 'getTotalExpenseBydate', func_get_args());
    }

    public function getTotalExpenseBwdate($date_from, $date_to)
    {
        return $this->callLegacyModel('Expense_model', 'getTotalExpenseBwdate', func_get_args());
    }

    public function getExpenseHeadData($start_date, $end_date)
    {
        return $this->callLegacyModel('Expense_model', 'getExpenseHeadData', func_get_args());
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