<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * StaffController
 *
 * Auto-generated CI4 controller migrating CI3 Staff.
 */
class StaffController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Staff', 'index', func_get_args());
    }

    public function ajaxsearch()
    {
        return $this->dispatchLegacy('Staff', 'ajaxsearch', func_get_args());
    }

    public function disablestafflist()
    {
        return $this->dispatchLegacy('Staff', 'disablestafflist', func_get_args());
    }

    public function profile($id)
    {
        return $this->dispatchLegacy('Staff', 'profile', func_get_args());
    }

    public function countAttendance($year, $emp)
    {
        return $this->dispatchLegacy('Staff', 'countAttendance', func_get_args());
    }

    public function getSession()
    {
        return $this->dispatchLegacy('Staff', 'getSession', func_get_args());
    }

    public function getSessionMonthDropdown()
    {
        return $this->dispatchLegacy('Staff', 'getSessionMonthDropdown', func_get_args());
    }

    public function download($staff_id, $doc)
    {
        return $this->dispatchLegacy('Staff', 'download', func_get_args());
    }

    public function doc_delete($id, $doc)
    {
        return $this->dispatchLegacy('Staff', 'doc_delete', func_get_args());
    }

    public function ajax_attendance()
    {
        return $this->dispatchLegacy('Staff', 'ajax_attendance', func_get_args());
    }

    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Staff', 'validateCanUploadFile', func_get_args());
    }

    public function create()
    {
        return $this->dispatchLegacy('Staff', 'create', func_get_args());
    }

    public function validateStorage($input, $storage_params_json)
    {
        return $this->dispatchLegacy('Staff', 'validateStorage', func_get_args());
    }

    public function validateResource($input, $resource_params_json)
    {
        return $this->dispatchLegacy('Staff', 'validateResource', func_get_args());
    }

    public function handle_upload()
    {
        return $this->dispatchLegacy('Staff', 'handle_upload', func_get_args());
    }

    public function handle_upload_old()
    {
        return $this->dispatchLegacy('Staff', 'handle_upload_old', func_get_args());
    }

    public function handle_first_upload()
    {
        return $this->dispatchLegacy('Staff', 'handle_first_upload', func_get_args());
    }

    public function handle_second_upload()
    {
        return $this->dispatchLegacy('Staff', 'handle_second_upload', func_get_args());
    }

    public function handle_third_upload()
    {
        return $this->dispatchLegacy('Staff', 'handle_third_upload', func_get_args());
    }

    public function handle_fourth_upload()
    {
        return $this->dispatchLegacy('Staff', 'handle_fourth_upload', func_get_args());
    }

    public function username_check($str)
    {
        return $this->dispatchLegacy('Staff', 'username_check', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Staff', 'edit', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Staff', 'delete', func_get_args());
    }

    public function disablestaff($id)
    {
        return $this->dispatchLegacy('Staff', 'disablestaff', func_get_args());
    }

    public function enablestaff($id)
    {
        return $this->dispatchLegacy('Staff', 'enablestaff', func_get_args());
    }

    public function staffLeaveSummary()
    {
        return $this->dispatchLegacy('Staff', 'staffLeaveSummary', func_get_args());
    }

    public function getEmployeeByRole()
    {
        return $this->dispatchLegacy('Staff', 'getEmployeeByRole', func_get_args());
    }

    public function dateDifference($date_1, $date_2, $differenceFormat = '%a')
    {
        return $this->dispatchLegacy('Staff', 'dateDifference', func_get_args());
    }

    public function permission($id)
    {
        return $this->dispatchLegacy('Staff', 'permission', func_get_args());
    }

    public function leaverequest()
    {
        return $this->dispatchLegacy('Staff', 'leaverequest', func_get_args());
    }

    public function change_password($id)
    {
        return $this->dispatchLegacy('Staff', 'change_password', func_get_args());
    }

    public function import()
    {
        return $this->dispatchLegacy('Staff', 'import', func_get_args());
    }

    public function handle_csv_upload()
    {
        return $this->dispatchLegacy('Staff', 'handle_csv_upload', func_get_args());
    }

    public function exportformat()
    {
        return $this->dispatchLegacy('Staff', 'exportformat', func_get_args());
    }

    public function rating()
    {
        return $this->dispatchLegacy('Staff', 'rating', func_get_args());
    }

    public function ratingapr($id)
    {
        return $this->dispatchLegacy('Staff', 'ratingapr', func_get_args());
    }

    public function delete_rateing($id)
    {
        return $this->dispatchLegacy('Staff', 'delete_rateing', func_get_args());
    }

    public function validateCanAddNewResource($input, $resource_name)
    {
        return $this->dispatchLegacy('Staff', 'validateCanAddNewResource', func_get_args());
    }

    public function bulk_update()
    {
        return $this->dispatchLegacy('Staff', 'bulk_update', func_get_args());
    }

    public function export_staff_id_name()
    {
        return $this->dispatchLegacy('Staff', 'export_staff_id_name', func_get_args());
    }

    public function export_staff_sample()
    {
        return $this->dispatchLegacy('Staff', 'export_staff_sample', func_get_args());
    }

    public function process_bulk_update()
    {
        return $this->dispatchLegacy('Staff', 'process_bulk_update', func_get_args());
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