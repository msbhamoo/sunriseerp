<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * OnlinestudentModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Onlinestudent_model.
 */
class OnlinestudentModel extends BaseModel
{
    protected $table = 'onlinestudent';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add($data)
    {
        return $this->callLegacyModel('Onlinestudent_model', 'add', func_get_args());
    }

    public function edit($data)
    {
        return $this->callLegacyModel('Onlinestudent_model', 'edit', func_get_args());
    }

    public function get($id = null, $carray = null)
    {
        return $this->callLegacyModel('Onlinestudent_model', 'get', func_get_args());
    }

    public function getstudentlist($carray = null, $id = null)
    {
        return $this->callLegacyModel('Onlinestudent_model', 'getstudentlist', func_get_args());
    }

    public function checkpaymentstatus($id)
    {
        return $this->callLegacyModel('Onlinestudent_model', 'checkpaymentstatus', func_get_args());
    }

    public function update($data,$fee_session_group_id,$transport_feemaster_id,$discount_id,$action = "save")
    {
        return $this->callLegacyModel('Onlinestudent_model', 'update', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Onlinestudent_model', 'remove', func_get_args());
    }

    public function checkadmissionstatus($reference_no, $dob)
    {
        return $this->callLegacyModel('Onlinestudent_model', 'checkadmissionstatus', func_get_args());
    }

    public function getformfields()
    {
        return $this->callLegacyModel('Onlinestudent_model', 'getformfields', func_get_args());
    }

    public function getfieldstatus($fieldname)
    {
        return $this->callLegacyModel('Onlinestudent_model', 'getfieldstatus', func_get_args());
    }

    public function checkfieldexist($fieldname)
    {
        return $this->callLegacyModel('Onlinestudent_model', 'checkfieldexist', func_get_args());
    }

    public function addformfields($record)
    {
        return $this->callLegacyModel('Onlinestudent_model', 'addformfields', func_get_args());
    }

    public function getAdmissionData($admission_no)
    {
        return $this->callLegacyModel('Onlinestudent_model', 'getAdmissionData', func_get_args());
    }

    public function paymentSuccess($payment)
    {
        return $this->callLegacyModel('Onlinestudent_model', 'paymentSuccess', func_get_args());
    }

    public function getclassbyclasssectionid($class_section_id)
    {
        return $this->callLegacyModel('Onlinestudent_model', 'getclassbyclasssectionid', func_get_args());
    }

    public function checkreferenceno($reference_no)
    {
        return $this->callLegacyModel('Onlinestudent_model', 'checkreferenceno', func_get_args());
    }

    public function getcustomfields()
    {
        return $this->callLegacyModel('Onlinestudent_model', 'getcustomfields', func_get_args());
    }

    public function validate_paymentamount()
    {
        return $this->callLegacyModel('Onlinestudent_model', 'validate_paymentamount', func_get_args());
    }

    public function check_student_email_exists($str)
    {
        return $this->callLegacyModel('Onlinestudent_model', 'check_student_email_exists', func_get_args());
    }

    public function check_data_exists($email, $id = null)
    {
        return $this->callLegacyModel('Onlinestudent_model', 'check_data_exists', func_get_args());
    }

    public function editguardianfield($status)
    {
        return $this->callLegacyModel('Onlinestudent_model', 'editguardianfield', func_get_args());
    }

    public function getidbyrefno($reference_no)
    {
        return $this->callLegacyModel('Onlinestudent_model', 'getidbyrefno', func_get_args());
    }

    public function gethousename($id)
    {
        return $this->callLegacyModel('Onlinestudent_model', 'gethousename', func_get_args());
    }

    public function gettransactionid($id)
    {
        return $this->callLegacyModel('Onlinestudent_model', 'gettransactionid', func_get_args());
    }

    public function gettransactionpaidamount($id)
    {
        return $this->callLegacyModel('Onlinestudent_model', 'gettransactionpaidamount', func_get_args());
    }

    public function getOnlineAdmissionFeeCollectionReport($start_date, $end_date)
    {
        return $this->callLegacyModel('Onlinestudent_model', 'getOnlineAdmissionFeeCollectionReport', func_get_args());
    }

    public function checkisenroll($reference_no)
    {
        return $this->callLegacyModel('Onlinestudent_model', 'checkisenroll', func_get_args());
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