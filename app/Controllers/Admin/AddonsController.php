<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * AddonsController
 *
 * Auto-generated CI4 controller migrating CI3 Addons.
 */
class AddonsController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Addons', 'index', func_get_args());
    }

    public function install()
    {
        return $this->dispatchLegacy('Addons', 'install', func_get_args());
    }

    public function uninstall()
    {
        return $this->dispatchLegacy('Addons', 'uninstall', func_get_args());
    }

    public function handle_upload()
    {
        return $this->dispatchLegacy('Addons', 'handle_upload', func_get_args());
    }

    public function getuploaddata()
    {
        return $this->dispatchLegacy('Addons', 'getuploaddata', func_get_args());
    }

    public function register_custom()
    {
        return $this->dispatchLegacy('Addons', 'register_custom', func_get_args());
    }

    public function delete_custom()
    {
        return $this->dispatchLegacy('Addons', 'delete_custom', func_get_args());
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