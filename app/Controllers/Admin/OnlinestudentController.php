<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * OnlinestudentController
 *
 * Auto-generated CI4 controller migrating CI3 Onlinestudent.
 */
class OnlinestudentController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Onlinestudent', 'index', func_get_args());
    }

    public function download($doc)
    {
        return $this->dispatchLegacy('Onlinestudent', 'download', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Onlinestudent', 'delete', func_get_args());
    }

    public function onlineadmission_download($id)
    {
        return $this->dispatchLegacy('Onlinestudent', 'onlineadmission_download', func_get_args());
    }

    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Onlinestudent', 'validateCanUploadFile', func_get_args());
    }

    public function validateCanAddNewResource($input, $resource_name)
    {
        return $this->dispatchLegacy('Onlinestudent', 'validateCanAddNewResource', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Onlinestudent', 'edit', func_get_args());
    }

    public function getByClass()
    {
        return $this->dispatchLegacy('Onlinestudent', 'getByClass', func_get_args());
    }

    public function getstudentlist()
    {
        return $this->dispatchLegacy('Onlinestudent', 'getstudentlist', func_get_args());
    }

    public function checkpaymentstatus()
    {
        return $this->dispatchLegacy('Onlinestudent', 'checkpaymentstatus', func_get_args());
    }

    public function handle_upload($str, $var)
    {
        return $this->dispatchLegacy('Onlinestudent', 'handle_upload', func_get_args());
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