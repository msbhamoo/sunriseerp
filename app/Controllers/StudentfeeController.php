<?php

namespace App\Controllers;

use App\Controllers\BaseController;

/**
 * StudentfeeController
 *
 * Auto-generated CI4 controller migrating CI3 Studentfee.
 */
class StudentfeeController extends BaseController
{
    public function dashboard()
    {
        return $this->dispatchLegacy('Studentfee', 'dashboard', func_get_args());
    }

    public function index()
    {
        return $this->dispatchLegacy('Studentfee', 'index', func_get_args());
    }

    public function pdf()
    {
        return $this->dispatchLegacy('Studentfee', 'pdf', func_get_args());
    }

    public function search()
    {
        return $this->dispatchLegacy('Studentfee', 'search', func_get_args());
    }

    public function ajaxSearch()
    {
        return $this->dispatchLegacy('Studentfee', 'ajaxSearch', func_get_args());
    }

    public function feesearch()
    {
        return $this->dispatchLegacy('Studentfee', 'feesearch', func_get_args());
    }

    public function reportbyclass()
    {
        return $this->dispatchLegacy('Studentfee', 'reportbyclass', func_get_args());
    }

    public function view($id)
    {
        return $this->dispatchLegacy('Studentfee', 'view', func_get_args());
    }

    public function deleteFee()
    {
        return $this->dispatchLegacy('Studentfee', 'deleteFee', func_get_args());
    }

    public function deleteStudentDiscount()
    {
        return $this->dispatchLegacy('Studentfee', 'deleteStudentDiscount', func_get_args());
    }

    public function getcollectfee()
    {
        return $this->dispatchLegacy('Studentfee', 'getcollectfee', func_get_args());
    }

    public function addfee($id)
    {
        return $this->dispatchLegacy('Studentfee', 'addfee', func_get_args());
    }

    public function getProcessingfees($id)
    {
        return $this->dispatchLegacy('Studentfee', 'getProcessingfees', func_get_args());
    }

    public function deleteTransportFee()
    {
        return $this->dispatchLegacy('Studentfee', 'deleteTransportFee', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Studentfee', 'delete', func_get_args());
    }

    public function create()
    {
        return $this->dispatchLegacy('Studentfee', 'create', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Studentfee', 'edit', func_get_args());
    }

    public function getAppliedDiscounts()
    {
        return $this->dispatchLegacy('Studentfee', 'getAppliedDiscounts', func_get_args());
    }

    public function addstudentfee()
    {
        return $this->dispatchLegacy('Studentfee', 'addstudentfee', func_get_args());
    }

    public function printFeesByName()
    {
        return $this->dispatchLegacy('Studentfee', 'printFeesByName', func_get_args());
    }

    public function printFeesReceiptPopup()
    {
        return $this->dispatchLegacy('Studentfee', 'printFeesReceiptPopup', func_get_args());
    }

    public function printFeesByGroup()
    {
        return $this->dispatchLegacy('Studentfee', 'printFeesByGroup', func_get_args());
    }

    public function printFeesByGroupArray()
    {
        return $this->dispatchLegacy('Studentfee', 'printFeesByGroupArray', func_get_args());
    }

    public function searchpayment()
    {
        return $this->dispatchLegacy('Studentfee', 'searchpayment', func_get_args());
    }

    public function addfeegroup()
    {
        return $this->dispatchLegacy('Studentfee', 'addfeegroup', func_get_args());
    }

    public function getBalanceFee()
    {
        return $this->dispatchLegacy('Studentfee', 'getBalanceFee', func_get_args());
    }

    public function getStudentTransportFeetypeBalance($trans_fee_id)
    {
        return $this->dispatchLegacy('Studentfee', 'getStudentTransportFeetypeBalance', func_get_args());
    }

    public function getStudentTransportYearlyFeetypeBalance($trans_fee_id)
    {
        return $this->dispatchLegacy('Studentfee', 'getStudentTransportYearlyFeetypeBalance', func_get_args());
    }

    public function getStuFeetypeBalance($fee_groups_feetype_id, $student_fees_master_id)
    {
        return $this->dispatchLegacy('Studentfee', 'getStuFeetypeBalance', func_get_args());
    }

    public function check_deposit($amount)
    {
        return $this->dispatchLegacy('Studentfee', 'check_deposit', func_get_args());
    }

    public function getNotAppliedDiscount($student_session_id)
    {
        return $this->dispatchLegacy('Studentfee', 'getNotAppliedDiscount', func_get_args());
    }

    public function addfeegrp()
    {
        return $this->dispatchLegacy('Studentfee', 'addfeegrp', func_get_args());
    }

    public function add_new_student($student)
    {
        return $this->dispatchLegacy('Studentfee', 'add_new_student', func_get_args());
    }

    public function printFeesCertificate()
    {
        return $this->dispatchLegacy('Studentfee', 'printFeesCertificate', func_get_args());
    }

    public function requestDiscount()
    {
        return $this->dispatchLegacy('Studentfee', 'requestDiscount', func_get_args());
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