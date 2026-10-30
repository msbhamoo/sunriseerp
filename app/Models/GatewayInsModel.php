<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * GatewayInsModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Gateway_ins_model.
 */
class GatewayInsModel extends BaseModel
{
    protected $table = 'gateway_ins';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add_gateway_ins_response($gateway_ins_response)
    {
        return $this->callLegacyModel('Gateway_ins_model', 'add_gateway_ins_response', func_get_args());
    }

    public function get($unique_id, $gateway_name)
    {
        return $this->callLegacyModel('Gateway_ins_model', 'get', func_get_args());
    }

    public function get_fees_processing($gateway_ins_id)
    {
        return $this->callLegacyModel('Gateway_ins_model', 'get_fees_processing', func_get_args());
    }

    public function get_gateway_ins($unique_id, $gateway_name)
    {
        return $this->callLegacyModel('Gateway_ins_model', 'get_gateway_ins', func_get_args());
    }

    public function get_gateway_ins_by_name($gateway_name)
    {
        return $this->callLegacyModel('Gateway_ins_model', 'get_gateway_ins_by_name', func_get_args());
    }

    public function add_gateway_ins($gateway_ins)
    {
        return $this->callLegacyModel('Gateway_ins_model', 'add_gateway_ins', func_get_args());
    }

    public function update_gateway_ins($gateway_ins)
    {
        return $this->callLegacyModel('Gateway_ins_model', 'update_gateway_ins', func_get_args());
    }

    public function get_gateway_details($type)
    {
        return $this->callLegacyModel('Gateway_ins_model', 'get_gateway_details', func_get_args());
    }

    public function get_statusByUnique_id($unique_id, $gateway_name)
    {
        return $this->callLegacyModel('Gateway_ins_model', 'get_statusByUnique_id', func_get_args());
    }

    public function deleteBygateway_ins_id($id)
    {
        return $this->callLegacyModel('Gateway_ins_model', 'deleteBygateway_ins_id', func_get_args());
    }

    public function onlineAdmissionStatus($admission_id, $paid_status)
    {
        return $this->callLegacyModel('Gateway_ins_model', 'onlineAdmissionStatus', func_get_args());
    }

    public function fee_processing($bulk_data)
    {
        return $this->callLegacyModel('Gateway_ins_model', 'fee_processing', func_get_args());
    }

    public function fee_deposit_bulk($bulk_data)
    {
        return $this->callLegacyModel('Gateway_ins_model', 'fee_deposit_bulk', func_get_args());
    }

    public function add_course_payment($data,$delete_id)
    {
        return $this->callLegacyModel('Gateway_ins_model', 'add_course_payment', func_get_args());
    }

    public function get_processing_payment($gateway_ins_id)
    {
        return $this->callLegacyModel('Gateway_ins_model', 'get_processing_payment', func_get_args());
    }

    public function deleteprocessingpaymentByid($id)
    {
        return $this->callLegacyModel('Gateway_ins_model', 'deleteprocessingpaymentByid', func_get_args());
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