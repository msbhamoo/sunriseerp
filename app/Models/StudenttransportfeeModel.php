<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * StudenttransportfeeModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Studenttransportfee_model.
 */
class StudenttransportfeeModel extends BaseModel
{
    protected $table = 'studenttransportfee';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add($data_insert, $student_session_id, $remove_ids, $route_pickup_point_id)
    {
        return $this->callLegacyModel('Studenttransportfee_model', 'add', func_get_args());
    }

    public function update($id = null, $row = null): bool
    {
        return $this->callLegacyModel('Studenttransportfee_model', 'update', func_get_args());
    }

    public function getTransportFeeByStudentSession($student_session_id, $route_pickup_point_id)
    {
        return $this->callLegacyModel('Studenttransportfee_model', 'getTransportFeeByStudentSession', func_get_args());
    }

    public function getTransportFeeByMonthStudentSession($student_session_id, $route_pickup_point_id,$month)
    {
        return $this->callLegacyModel('Studenttransportfee_model', 'getTransportFeeByMonthStudentSession', func_get_args());
    }

    public function getTransportFeeMasterByStudentTransportID($student_transport_fee_id)
    {
        return $this->callLegacyModel('Studenttransportfee_model', 'getTransportFeeMasterByStudentTransportID', func_get_args());
    }

    public function getTransportDepositAmountBetweenDate($start_date, $end_date)
    {
        return $this->callLegacyModel('Studenttransportfee_model', 'getTransportDepositAmountBetweenDate', func_get_args());
    }

    public function findObjectById($array, $st_date, $ed_date)
    {
        return $this->callLegacyModel('Studenttransportfee_model', 'findObjectById', func_get_args());
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