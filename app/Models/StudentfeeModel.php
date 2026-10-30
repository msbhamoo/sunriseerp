<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * StudentfeeModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Studentfee_model.
 */
class StudentfeeModel extends BaseModel
{
    protected $table = 'studentfee';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function getStudentFeesArray($student_session_id, $ids = null)
    {
        return $this->callLegacyModel('Studentfee_model', 'getStudentFeesArray', func_get_args());
    }

    public function getCollectBy()
    {
        return $this->callLegacyModel('Studentfee_model', 'getCollectBy', func_get_args());
    }

    public function getTotalCollectionBydate($date)
    {
        return $this->callLegacyModel('Studentfee_model', 'getTotalCollectionBydate', func_get_args());
    }

    public function getStudentFees($id = null)
    {
        return $this->callLegacyModel('Studentfee_model', 'getStudentFees', func_get_args());
    }

    public function getFeeByInvoice($id = null)
    {
        return $this->callLegacyModel('Studentfee_model', 'getFeeByInvoice', func_get_args());
    }

    public function getTodayStudentFees()
    {
        return $this->callLegacyModel('Studentfee_model', 'getTodayStudentFees', func_get_args());
    }

    public function remove($id, $sub_invoice)
    {
        return $this->callLegacyModel('Studentfee_model', 'remove', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Studentfee_model', 'add', func_get_args());
    }

    public function getMultipleDueFees($feegroups = array(), $fee_groups_feetypes = array(),$transport_groups_feetype_array=array(), $class_id = NULL, $section_id = NULL)
    {
        return $this->callLegacyModel('Studentfee_model', 'getMultipleDueFees', func_get_args());
    }

    public function getDueStudentFeesByDateClassSection($class_id = NULL, $section_id = NULL , $date=NULL)
    {
        return $this->callLegacyModel('Studentfee_model', 'getDueStudentFeesByDateClassSection', func_get_args());
    }

    public function getDueStudentFees($feegroup_id = null, $fee_groups_feetype_id = null, $class_id = NULL, $section_id = NULL)
    {
        return $this->callLegacyModel('Studentfee_model', 'getDueStudentFees', func_get_args());
    }

    public function getDueFeeBystudent($class_id = null, $section_id = null, $student_id = null)
    {
        return $this->callLegacyModel('Studentfee_model', 'getDueFeeBystudent', func_get_args());
    }

    public function getDueFeeBystudentSection($class_id = null, $section_id = null, $student_session_id = null)
    {
        return $this->callLegacyModel('Studentfee_model', 'getDueFeeBystudentSection', func_get_args());
    }

    public function getFeesByClass($class_id = null, $section_id = null, $student_id = null)
    {
        return $this->callLegacyModel('Studentfee_model', 'getFeesByClass', func_get_args());
    }

    public function getFeeBetweenDate($start_date, $end_date)
    {
        return $this->callLegacyModel('Studentfee_model', 'getFeeBetweenDate', func_get_args());
    }

    public function getStudentTotalFee($class_id, $student_session_id)
    {
        return $this->callLegacyModel('Studentfee_model', 'getStudentTotalFee', func_get_args());
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