<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * InstallmentreportModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Installmentreport_model.
 */
class InstallmentreportModel extends BaseModel
{
    protected $table = 'installmentreport';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get_plans($session_id = null)
    {
        return $this->callLegacyModel('Installmentreport_model', 'get_plans', func_get_args());
    }

    public function get_plan($id)
    {
        return $this->callLegacyModel('Installmentreport_model', 'get_plan', func_get_args());
    }

    public function get_plan_dates($plan_id)
    {
        return $this->callLegacyModel('Installmentreport_model', 'get_plan_dates', func_get_args());
    }

    public function get_plan_splits($plan_id)
    {
        return $this->callLegacyModel('Installmentreport_model', 'get_plan_splits', func_get_args());
    }

    public function add_plan($data, $dates, $splits)
    {
        return $this->callLegacyModel('Installmentreport_model', 'add_plan', func_get_args());
    }

    public function remove_plan($id)
    {
        return $this->callLegacyModel('Installmentreport_model', 'remove_plan', func_get_args());
    }

    public function get_available_fee_groups()
    {
        return $this->callLegacyModel('Installmentreport_model', 'get_available_fee_groups', func_get_args());
    }

    public function get_available_hostel_groups()
    {
        return $this->callLegacyModel('Installmentreport_model', 'get_available_hostel_groups', func_get_args());
    }

    public function get_available_transport_yearly()
    {
        return $this->callLegacyModel('Installmentreport_model', 'get_available_transport_yearly', func_get_args());
    }

    public function get_plan_by_class($class_id, $session_id = null)
    {
        return $this->callLegacyModel('Installmentreport_model', 'get_plan_by_class', func_get_args());
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