<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

/**
 * Apply_leaveController
 *
 * Auto-generated CI4 controller migrating CI3 Apply_leave.
 */
class Apply_leaveController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Apply_leave', 'index', func_get_args());
    }

    public function get_details($id)
    {
        return $this->dispatchLegacy('Apply_leave', 'get_details', func_get_args());
    }

    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Apply_leave', 'validateCanUploadFile', func_get_args());
    }

    public function add()
    {
        return $this->dispatchLegacy('Apply_leave', 'add', func_get_args());
    }

    public function remove_leave($id)
    {
        return $this->dispatchLegacy('Apply_leave', 'remove_leave', func_get_args());
    }

    public function download($id)
    {
        return $this->dispatchLegacy('Apply_leave', 'download', func_get_args());
    }

    public function handle_upload($str, $var1)
    {
        return $this->dispatchLegacy('Apply_leave', 'handle_upload', func_get_args());
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