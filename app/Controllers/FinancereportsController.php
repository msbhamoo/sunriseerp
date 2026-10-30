<?php

namespace App\Controllers;

use App\Controllers\BaseController;

/**
 * FinancereportsController
 *
 * Auto-generated CI4 controller migrating CI3 Financereports.
 */
class FinancereportsController extends BaseController
{
    public function finance()
    {
        return $this->dispatchLegacy('Financereports', 'finance', func_get_args());
    }

    public function reportduefees()
    {
        return $this->dispatchLegacy('Financereports', 'reportduefees', func_get_args());
    }

    public function printreportduefees()
    {
        return $this->dispatchLegacy('Financereports', 'printreportduefees', func_get_args());
    }

    public function reportdailycollection()
    {
        return $this->dispatchLegacy('Financereports', 'reportdailycollection', func_get_args());
    }

    public function feeCollectionStudentDeposit()
    {
        return $this->dispatchLegacy('Financereports', 'feeCollectionStudentDeposit', func_get_args());
    }

    public function reportbyname()
    {
        return $this->dispatchLegacy('Financereports', 'reportbyname', func_get_args());
    }

    public function studentacademicreport()
    {
        return $this->dispatchLegacy('Financereports', 'studentacademicreport', func_get_args());
    }

    public function collection_report()
    {
        return $this->dispatchLegacy('Financereports', 'collection_report', func_get_args());
    }

    public function collection_list()
    {
        return $this->dispatchLegacy('Financereports', 'collection_list', func_get_args());
    }

    public function class_summary_report()
    {
        return $this->dispatchLegacy('Financereports', 'class_summary_report', func_get_args());
    }

    public function onlinefees_report()
    {
        return $this->dispatchLegacy('Financereports', 'onlinefees_report', func_get_args());
    }

    public function duefeesremark()
    {
        return $this->dispatchLegacy('Financereports', 'duefeesremark', func_get_args());
    }

    public function add_new_student($student)
    {
        return $this->dispatchLegacy('Financereports', 'add_new_student', func_get_args());
    }

    public function printduefeesremark()
    {
        return $this->dispatchLegacy('Financereports', 'printduefeesremark', func_get_args());
    }

    public function income()
    {
        return $this->dispatchLegacy('Financereports', 'income', func_get_args());
    }

    public function searchreportvalidation()
    {
        return $this->dispatchLegacy('Financereports', 'searchreportvalidation', func_get_args());
    }

    public function getincomelistbydt()
    {
        return $this->dispatchLegacy('Financereports', 'getincomelistbydt', func_get_args());
    }

    public function expense()
    {
        return $this->dispatchLegacy('Financereports', 'expense', func_get_args());
    }

    public function getexpenselistbydt()
    {
        return $this->dispatchLegacy('Financereports', 'getexpenselistbydt', func_get_args());
    }

    public function payroll()
    {
        return $this->dispatchLegacy('Financereports', 'payroll', func_get_args());
    }

    public function incomegroup()
    {
        return $this->dispatchLegacy('Financereports', 'incomegroup', func_get_args());
    }

    public function dtincomegroupreport()
    {
        return $this->dispatchLegacy('Financereports', 'dtincomegroupreport', func_get_args());
    }

    public function getgroupreportparam()
    {
        return $this->dispatchLegacy('Financereports', 'getgroupreportparam', func_get_args());
    }

    public function expensegroup()
    {
        return $this->dispatchLegacy('Financereports', 'expensegroup', func_get_args());
    }

    public function dtexpensegroupreport()
    {
        return $this->dispatchLegacy('Financereports', 'dtexpensegroupreport', func_get_args());
    }

    public function onlineadmission()
    {
        return $this->dispatchLegacy('Financereports', 'onlineadmission', func_get_args());
    }

    public function incomeexpensebalancereport()
    {
        return $this->dispatchLegacy('Financereports', 'incomeexpensebalancereport', func_get_args());
    }

    protected function dispatchLegacy(string $controller, string $method, array $params)
    {
        // Set up compatibility environment
        $_SERVER['CI4_LEGACY_CONTROLLER'] = $controller;
        $_SERVER['CI4_LEGACY_METHOD'] = $method;
        $_SERVER['CI4_LEGACY_PARAMS'] = $params;

        require_once APPPATH . '../application/core/MY_Controller.php';
        $target = APPPATH . '../application/controllers/' . $controller . '.php';
        if (file_exists($target)) {
            require_once $target;
            $instance = new $controller();
            return call_user_func_array([$instance, $method], $params);
        }

        return redirect()->to(base_url('admin/unauthorized'));
    }
}