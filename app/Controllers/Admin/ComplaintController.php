<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * ComplaintController
 *
 * Auto-generated CI4 controller migrating CI3 Complaint.
 */
class ComplaintController extends BaseController
{
    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Complaint', 'validateCanUploadFile', func_get_args());
    }

    public function index()
    {
        return $this->dispatchLegacy('Complaint', 'index', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Complaint', 'edit', func_get_args());
    }

    public function details($id)
    {
        return $this->dispatchLegacy('Complaint', 'details', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Complaint', 'delete', func_get_args());
    }

    public function download($id)
    {
        return $this->dispatchLegacy('Complaint', 'download', func_get_args());
    }

    public function check_default($post_string)
    {
        return $this->dispatchLegacy('Complaint', 'check_default', func_get_args());
    }

    public function handle_upload($str, $var)
    {
        return $this->dispatchLegacy('Complaint', 'handle_upload', func_get_args());
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