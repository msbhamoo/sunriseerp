<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * InstallmentplanModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Installmentplan_model.
 */
class InstallmentplanModel extends BaseModel
{
    protected $table = 'installmentplan';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($id = null)
    {
        return $this->callLegacyModel('Installmentplan_model', 'get', func_get_args());
    }

    public function getPlanClasses($plan_id)
    {
        return $this->callLegacyModel('Installmentplan_model', 'getPlanClasses', func_get_args());
    }

    public function getPlanItems($plan_id)
    {
        return $this->callLegacyModel('Installmentplan_model', 'getPlanItems', func_get_args());
    }

    public function getPlanDetails($plan_id)
    {
        return $this->callLegacyModel('Installmentplan_model', 'getPlanDetails', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Installmentplan_model', 'add', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Installmentplan_model', 'remove', func_get_args());
    }

    public function saveClasses($plan_id, $classes)
    {
        return $this->callLegacyModel('Installmentplan_model', 'saveClasses', func_get_args());
    }

    public function saveItems($plan_id, $items)
    {
        return $this->callLegacyModel('Installmentplan_model', 'saveItems', func_get_args());
    }

    public function saveDetails($plan_id, $details)
    {
        return $this->callLegacyModel('Installmentplan_model', 'saveDetails', func_get_args());
    }

    public function calculate_student_installments($student_session_id, $plan_id = null)
    {
        return $this->callLegacyModel('Installmentplan_model', 'calculate_student_installments', func_get_args());
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