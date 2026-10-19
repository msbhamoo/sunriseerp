<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * PayrollController
 *
 * Auto-generated CI4 controller migrating CI3 Payroll.
 */
class PayrollController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Payroll', 'index', func_get_args());
    }

    public function create($month, $year, $id)
    {
        return $this->dispatchLegacy('Payroll', 'create', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Payroll', 'edit', func_get_args());
    }

    public function editpayroll()
    {
        return $this->dispatchLegacy('Payroll', 'editpayroll', func_get_args());
    }

    public function monthAttendance($st_month, $no_of_months, $emp)
    {
        return $this->dispatchLegacy('Payroll', 'monthAttendance', func_get_args());
    }

    public function monthLeaves($st_month, $no_of_months, $emp)
    {
        return $this->dispatchLegacy('Payroll', 'monthLeaves', func_get_args());
    }

    public function payslip()
    {
        return $this->dispatchLegacy('Payroll', 'payslip', func_get_args());
    }

    public function search($month, $year, $role = '')
    {
        return $this->dispatchLegacy('Payroll', 'search', func_get_args());
    }

    public function paymentRecord()
    {
        return $this->dispatchLegacy('Payroll', 'paymentRecord', func_get_args());
    }

    public function paymentStatus($status)
    {
        return $this->dispatchLegacy('Payroll', 'paymentStatus', func_get_args());
    }

    public function paymentSuccess()
    {
        return $this->dispatchLegacy('Payroll', 'paymentSuccess', func_get_args());
    }

    public function payslipView()
    {
        return $this->dispatchLegacy('Payroll', 'payslipView', func_get_args());
    }

    public function payslippdf()
    {
        return $this->dispatchLegacy('Payroll', 'payslippdf', func_get_args());
    }

    public function payrollreport()
    {
        return $this->dispatchLegacy('Payroll', 'payrollreport', func_get_args());
    }

    public function deletepayroll($payslipid, $month, $year, $role = '')
    {
        return $this->dispatchLegacy('Payroll', 'deletepayroll', func_get_args());
    }

    public function revertpayroll($payslipid, $month, $year, $role = '')
    {
        return $this->dispatchLegacy('Payroll', 'revertpayroll', func_get_args());
    }

    protected function dispatchLegacy(string $controller, string $method, array $params)
    {
        // Set up compatibility environment
        $_SERVER['CI4_LEGACY_CONTROLLER'] = $controller;
        $_SERVER['CI4_LEGACY_METHOD'] = $method;
        $_SERVER['CI4_LEGACY_PARAMS'] = $params;

        require_once APPPATH . '../application/core/MY_Controller.php';
        $target = APPPATH . '../application/controllers/admin/' . $controller . '.php';
        if (file_exists($target)) {
            require_once $target;
            $instance = new $controller();
            return call_user_func_array([$instance, $method], $params);
        }

        return redirect()->to(base_url('admin/unauthorized'));
    }
}