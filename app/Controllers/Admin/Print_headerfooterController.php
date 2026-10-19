<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * Print_headerfooterController
 *
 * Auto-generated CI4 controller migrating CI3 Print_headerfooter.
 */
class Print_headerfooterController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Print_headerfooter', 'index', func_get_args());
    }

    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Print_headerfooter', 'validateCanUploadFile', func_get_args());
    }

    public function edit()
    {
        return $this->dispatchLegacy('Print_headerfooter', 'edit', func_get_args());
    }

    public function handle_upload($str, $is_required)
    {
        return $this->dispatchLegacy('Print_headerfooter', 'handle_upload', func_get_args());
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