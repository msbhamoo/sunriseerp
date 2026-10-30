<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * VisitorsController
 *
 * Auto-generated CI4 controller migrating CI3 Visitors.
 */
class VisitorsController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Visitors', 'index', func_get_args());
    }

    public function delete()
    {
        return $this->dispatchLegacy('Visitors', 'delete', func_get_args());
    }

    public function details($id)
    {
        return $this->dispatchLegacy('Visitors', 'details', func_get_args());
    }

    public function download($id)
    {
        return $this->dispatchLegacy('Visitors', 'download', func_get_args());
    }

    public function check_default($post_string)
    {
        return $this->dispatchLegacy('Visitors', 'check_default', func_get_args());
    }

    public function handle_upload($str, $var)
    {
        return $this->dispatchLegacy('Visitors', 'handle_upload', func_get_args());
    }

    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Visitors', 'validateCanUploadFile', func_get_args());
    }

    public function add()
    {
        return $this->dispatchLegacy('Visitors', 'add', func_get_args());
    }

    public function editvisitor()
    {
        return $this->dispatchLegacy('Visitors', 'editvisitor', func_get_args());
    }

    public function edit_old()
    {
        return $this->dispatchLegacy('Visitors', 'edit_old', func_get_args());
    }

    public function edit()
    {
        return $this->dispatchLegacy('Visitors', 'edit', func_get_args());
    }

    public function getstudent()
    {
        return $this->dispatchLegacy('Visitors', 'getstudent', func_get_args());
    }

    public function staffvisitor()
    {
        return $this->dispatchLegacy('Visitors', 'staffvisitor', func_get_args());
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