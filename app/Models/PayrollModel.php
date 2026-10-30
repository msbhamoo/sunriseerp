<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * PayrollModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Payroll_model.
 */
class PayrollModel extends BaseModel
{
    protected $table = 'payroll';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function searchEmployee($month, $year, $emp_name, $role)
    {
        return $this->callLegacyModel('Payroll_model', 'searchEmployee', func_get_args());
    }

    public function update_allowance($insert_data, $update_data, $delete_data,$payslipid,$type)
    {
        return $this->callLegacyModel('Payroll_model', 'update_allowance', func_get_args());
    }

    public function createPayslip($data)
    {
        return $this->callLegacyModel('Payroll_model', 'createPayslip', func_get_args());
    }

    public function checkPayslip($month, $year, $staff_id)
    {
        return $this->callLegacyModel('Payroll_model', 'checkPayslip', func_get_args());
    }

    public function add_allowance($data)
    {
        return $this->callLegacyModel('Payroll_model', 'add_allowance', func_get_args());
    }

    public function searchPaylist($name, $month, $year)
    {
        return $this->callLegacyModel('Payroll_model', 'searchPaylist', func_get_args());
    }

    public function count_attendance($month, $year, $staff_id, $attendance_type = 1)
    {
        return $this->callLegacyModel('Payroll_model', 'count_attendance', func_get_args());
    }

    public function count_attendance_obj($month, $year, $staff_id, $attendance_type = 1)
    {
        return $this->callLegacyModel('Payroll_model', 'count_attendance_obj', func_get_args());
    }

    public function updatePaymentStatus($status, $id)
    {
        return $this->callLegacyModel('Payroll_model', 'updatePaymentStatus', func_get_args());
    }

    public function searchEmployeeById($id)
    {
        return $this->callLegacyModel('Payroll_model', 'searchEmployeeById', func_get_args());
    }

    public function searchPayment($id, $month, $year)
    {
        return $this->callLegacyModel('Payroll_model', 'searchPayment', func_get_args());
    }

    public function paymentSuccess($data, $payslipid)
    {
        return $this->callLegacyModel('Payroll_model', 'paymentSuccess', func_get_args());
    }

    public function getPayslip($id)
    {
        return $this->callLegacyModel('Payroll_model', 'getPayslip', func_get_args());
    }

    public function getAllowance($id, $type = null)
    {
        return $this->callLegacyModel('Payroll_model', 'getAllowance', func_get_args());
    }

    public function getSalaryDetails($id)
    {
        return $this->callLegacyModel('Payroll_model', 'getSalaryDetails', func_get_args());
    }

    public function getpayrollReport($month, $year, $role)
    {
        return $this->callLegacyModel('Payroll_model', 'getpayrollReport', func_get_args());
    }

    public function deletePayslip($payslipid)
    {
        return $this->callLegacyModel('Payroll_model', 'deletePayslip', func_get_args());
    }

    public function revertPayslipStatus($payslipid)
    {
        return $this->callLegacyModel('Payroll_model', 'revertPayslipStatus', func_get_args());
    }

    public function payrollYearCount()
    {
        return $this->callLegacyModel('Payroll_model', 'payrollYearCount', func_get_args());
    }

    public function getbetweenpayrollReport($start_date, $end_date)
    {
        return $this->callLegacyModel('Payroll_model', 'getbetweenpayrollReport', func_get_args());
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