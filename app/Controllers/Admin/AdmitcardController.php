<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * AdmitcardController
 *
 * Auto-generated CI4 controller migrating CI3 Admitcard.
 */
class AdmitcardController extends BaseController
{
    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Admitcard', 'validateCanUploadFile', func_get_args());
    }

    public function index()
    {
        return $this->dispatchLegacy('Admitcard', 'index', func_get_args());
    }

    public function handle_upload($str, $var)
    {
        return $this->dispatchLegacy('Admitcard', 'handle_upload', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Admitcard', 'edit', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Admitcard', 'delete', func_get_args());
    }

    public function view()
    {
        return $this->dispatchLegacy('Admitcard', 'view', func_get_args());
    }

    public function save_active_status()
    {
        return $this->dispatchLegacy('Admitcard', 'save_active_status', func_get_args());
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