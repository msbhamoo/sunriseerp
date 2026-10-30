<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * Approve_leaveController
 *
 * Auto-generated CI4 controller migrating CI3 Approve_leave.
 */
class Approve_leaveController extends BaseController
{
    public function unauthorized()
    {
        return $this->dispatchLegacy('Approve_leave', 'unauthorized', func_get_args());
    }

    public function index()
    {
        return $this->dispatchLegacy('Approve_leave', 'index', func_get_args());
    }

    public function get_details()
    {
        return $this->dispatchLegacy('Approve_leave', 'get_details', func_get_args());
    }

    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Approve_leave', 'validateCanUploadFile', func_get_args());
    }

    public function add()
    {
        return $this->dispatchLegacy('Approve_leave', 'add', func_get_args());
    }

    public function searchByClassSection($class_id, $student_id)
    {
        return $this->dispatchLegacy('Approve_leave', 'searchByClassSection', func_get_args());
    }

    public function status()
    {
        return $this->dispatchLegacy('Approve_leave', 'status', func_get_args());
    }

    public function remove_leave()
    {
        return $this->dispatchLegacy('Approve_leave', 'remove_leave', func_get_args());
    }

    public function download($id)
    {
        return $this->dispatchLegacy('Approve_leave', 'download', func_get_args());
    }

    public function handle_upload($str, $var)
    {
        return $this->dispatchLegacy('Approve_leave', 'handle_upload', func_get_args());
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