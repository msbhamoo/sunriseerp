<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * TransportyearlyfeeModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Transportyearlyfee_model.
 */
class TransportyearlyfeeModel extends BaseModel
{
    protected $table = 'transportyearlyfee';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add($data)
    {
        return $this->callLegacyModel('Transportyearlyfee_model', 'add', func_get_args());
    }

    public function get($id = null, $route_id = null, $pickup_point_id = null)
    {
        return $this->callLegacyModel('Transportyearlyfee_model', 'get', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Transportyearlyfee_model', 'remove', func_get_args());
    }

    public function check_payment_exists($transport_yearly_feemaster_id)
    {
        return $this->callLegacyModel('Transportyearlyfee_model', 'check_payment_exists', func_get_args());
    }

    public function getApplicableYearlyFees($class_id, $route_pickup_point_id)
    {
        return $this->callLegacyModel('Transportyearlyfee_model', 'getApplicableYearlyFees', func_get_args());
    }

    public function getStudentYearlyFees($student_session_id)
    {
        return $this->callLegacyModel('Transportyearlyfee_model', 'getStudentYearlyFees', func_get_args());
    }

    public function assignStudent($data)
    {
        return $this->callLegacyModel('Transportyearlyfee_model', 'assignStudent', func_get_args());
    }

    public function unassignStudent($student_session_id, $transport_yearly_feemaster_id)
    {
        return $this->callLegacyModel('Transportyearlyfee_model', 'unassignStudent', func_get_args());
    }

    public function getStudentTransportYearlyFees($student_session_id, $route_pickup_point_id, $class_id)
    {
        return $this->callLegacyModel('Transportyearlyfee_model', 'getStudentTransportYearlyFees', func_get_args());
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