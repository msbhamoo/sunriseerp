<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * StaffcomplianceController
 *
 * Auto-generated CI4 controller migrating CI3 Staffcompliance.
 */
class StaffcomplianceController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Staffcompliance', 'index', func_get_args());
    }

    public function ajax_save_single()
    {
        return $this->dispatchLegacy('Staffcompliance', 'ajax_save_single', func_get_args());
    }

    public function monthsheet()
    {
        return $this->dispatchLegacy('Staffcompliance', 'monthsheet', func_get_args());
    }

    public function _remap($method, ...$params)
    {
        if (method_exists($this, $method)) {
            return $this->$method(...$params);
        }
        return $this->dispatchLegacy('Staffcompliance', $method, $params);
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