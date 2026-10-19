<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * LeaverequestController
 *
 * Auto-generated CI4 controller migrating CI3 Leaverequest.
 */
class LeaverequestController extends BaseController
{
    public function leaverequest()
    {
        return $this->dispatchLegacy('Leaverequest', 'leaverequest', func_get_args());
    }

    public function countLeave($id)
    {
        return $this->dispatchLegacy('Leaverequest', 'countLeave', func_get_args());
    }

    public function leaveStatus()
    {
        return $this->dispatchLegacy('Leaverequest', 'leaveStatus', func_get_args());
    }

    public function remove($id, $staff_id)
    {
        return $this->dispatchLegacy('Leaverequest', 'remove', func_get_args());
    }

    public function leaveRecord()
    {
        return $this->dispatchLegacy('Leaverequest', 'leaveRecord', func_get_args());
    }

    public function dateDifference($date_1, $date_2, $differenceFormat = '%a')
    {
        return $this->dispatchLegacy('Leaverequest', 'dateDifference', func_get_args());
    }

    public function getDateDifference()
    {
        return $this->dispatchLegacy('Leaverequest', 'getDateDifference', func_get_args());
    }

    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Leaverequest', 'validateCanUploadFile', func_get_args());
    }

    public function addLeave()
    {
        return $this->dispatchLegacy('Leaverequest', 'addLeave', func_get_args());
    }

    public function add_staff_leave()
    {
        return $this->dispatchLegacy('Leaverequest', 'add_staff_leave', func_get_args());
    }

    public function handle_upload($str, $var)
    {
        return $this->dispatchLegacy('Leaverequest', 'handle_upload', func_get_args());
    }

    public function downloadleaverequestdoc($staff_id, $id)
    {
        return $this->dispatchLegacy('Leaverequest', 'downloadleaverequestdoc', func_get_args());
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