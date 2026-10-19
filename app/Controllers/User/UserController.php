<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

/**
 * UserController
 *
 * Auto-generated CI4 controller migrating CI3 User.
 */
class UserController extends BaseController
{
    public function unauthorized()
    {
        return $this->dispatchLegacy('User', 'unauthorized', func_get_args());
    }

    public function choose()
    {
        return $this->dispatchLegacy('User', 'choose', func_get_args());
    }

    public function fees()
    {
        return $this->dispatchLegacy('User', 'fees', func_get_args());
    }

    public function profile()
    {
        return $this->dispatchLegacy('User', 'profile', func_get_args());
    }

    public function dashboard()
    {
        return $this->dispatchLegacy('User', 'dashboard', func_get_args());
    }

    public function changepass()
    {
        return $this->dispatchLegacy('User', 'changepass', func_get_args());
    }

    public function changeusername()
    {
        return $this->dispatchLegacy('User', 'changeusername', func_get_args());
    }

    public function download($student_id, $id)
    {
        return $this->dispatchLegacy('User', 'download', func_get_args());
    }

    public function user_language($lang_id)
    {
        return $this->dispatchLegacy('User', 'user_language', func_get_args());
    }

    public function change_currency()
    {
        return $this->dispatchLegacy('User', 'change_currency', func_get_args());
    }

    public function timeline_download($timeline_id, $doc)
    {
        return $this->dispatchLegacy('User', 'timeline_download', func_get_args());
    }

    public function view($id)
    {
        return $this->dispatchLegacy('User', 'view', func_get_args());
    }

    public function getfees()
    {
        return $this->dispatchLegacy('User', 'getfees', func_get_args());
    }

    public function getAppliedDiscounts()
    {
        return $this->dispatchLegacy('User', 'getAppliedDiscounts', func_get_args());
    }

    public function geBalanceFee()
    {
        return $this->dispatchLegacy('User', 'geBalanceFee', func_get_args());
    }

    public function getBalanceFee_()
    {
        return $this->dispatchLegacy('User', 'getBalanceFee_', func_get_args());
    }

    public function getStudentTransportFeetypeBalance($trans_fee_id)
    {
        return $this->dispatchLegacy('User', 'getStudentTransportFeetypeBalance', func_get_args());
    }

    public function getStuFeetypeBalance($fee_groups_feetype_id, $student_fees_master_id)
    {
        return $this->dispatchLegacy('User', 'getStuFeetypeBalance', func_get_args());
    }

    public function getNotAppliedDiscount($student_session_id)
    {
        return $this->dispatchLegacy('User', 'getNotAppliedDiscount', func_get_args());
    }

    public function getProcessingfees()
    {
        return $this->dispatchLegacy('User', 'getProcessingfees', func_get_args());
    }

    public function printFeesByGroupArray()
    {
        return $this->dispatchLegacy('User', 'printFeesByGroupArray', func_get_args());
    }

    public function getcollectfee()
    {
        return $this->dispatchLegacy('User', 'getcollectfee', func_get_args());
    }

    public function create_doc()
    {
        return $this->dispatchLegacy('User', 'create_doc', func_get_args());
    }

    public function multihandle_upload($str, $var)
    {
        return $this->dispatchLegacy('User', 'multihandle_upload', func_get_args());
    }

    public function handle_upload()
    {
        return $this->dispatchLegacy('User', 'handle_upload', func_get_args());
    }

    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('User', 'validateCanUploadFile', func_get_args());
    }

    public function edit()
    {
        return $this->dispatchLegacy('User', 'edit', func_get_args());
    }

    public function findSelected($inserted_fields, $find)
    {
        return $this->dispatchLegacy('User', 'findSelected', func_get_args());
    }

    public function edit_handle_upload($value, $field_name)
    {
        return $this->dispatchLegacy('User', 'edit_handle_upload', func_get_args());
    }

    public function printFeesByName()
    {
        return $this->dispatchLegacy('User', 'printFeesByName', func_get_args());
    }

    public function countAttendance($session_year_start, $student_session_id)
    {
        return $this->dispatchLegacy('User', 'countAttendance', func_get_args());
    }

    public function startmonthandend()
    {
        return $this->dispatchLegacy('User', 'startmonthandend', func_get_args());
    }

    public function printpdfresume()
    {
        return $this->dispatchLegacy('User', 'printpdfresume', func_get_args());
    }

    public function printStudentDetails()
    {
        return $this->dispatchLegacy('User', 'printStudentDetails', func_get_args());
    }

    public function addstudentfee()
    {
        return $this->dispatchLegacy('User', 'addstudentfee', func_get_args());
    }

    public function check_deposit()
    {
        return $this->dispatchLegacy('User', 'check_deposit', func_get_args());
    }

    public function searchvalidationtitle()
    {
        return $this->dispatchLegacy('User', 'searchvalidationtitle', func_get_args());
    }

    public function save_image()
    {
        return $this->dispatchLegacy('User', 'save_image', func_get_args());
    }

    public function validateCanAddNewResource($input, $resource_name)
    {
        return $this->dispatchLegacy('User', 'validateCanAddNewResource', func_get_args());
    }

    protected function dispatchLegacy(string $controller, string $method, array $params)
    {
        // Set up compatibility environment
        $_SERVER['CI4_LEGACY_CONTROLLER'] = $controller;
        $_SERVER['CI4_LEGACY_METHOD'] = $method;
        $_SERVER['CI4_LEGACY_PARAMS'] = $params;

        require_once APPPATH . '../application/core/MY_Controller.php';
        $target = APPPATH . '../application/controllers/user/' . $controller . '.php';
        if (file_exists($target)) {
            require_once $target;
            $instance = new $controller();
            return call_user_func_array([$instance, $method], $params);
        }

        return redirect()->to(base_url('admin/unauthorized'));
    }
}