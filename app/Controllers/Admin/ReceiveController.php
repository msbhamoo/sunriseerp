<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * ReceiveController
 *
 * Auto-generated CI4 controller migrating CI3 Receive.
 */
class ReceiveController extends BaseController
{
    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Receive', 'validateCanUploadFile', func_get_args());
    }

    public function index()
    {
        return $this->dispatchLegacy('Receive', 'index', func_get_args());
    }

    public function editreceive($id)
    {
        return $this->dispatchLegacy('Receive', 'editreceive', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Receive', 'delete', func_get_args());
    }

    public function handle_upload($str, $var)
    {
        return $this->dispatchLegacy('Receive', 'handle_upload', func_get_args());
    }

    public function download($id)
    {
        return $this->dispatchLegacy('Receive', 'download', func_get_args());
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