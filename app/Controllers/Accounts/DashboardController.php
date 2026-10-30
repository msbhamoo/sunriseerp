<?php

namespace App\Controllers\Accounts;

use App\Controllers\BaseController;

/**
 * DashboardController
 *
 * Auto-generated CI4 controller migrating CI3 Dashboard.
 */
class DashboardController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Dashboard', 'index', func_get_args());
    }

    public function get_ledger_balance()
    {
        return $this->dispatchLegacy('Dashboard', 'get_ledger_balance', func_get_args());
    }

    public function quick_entry()
    {
        return $this->dispatchLegacy('Dashboard', 'quick_entry', func_get_args());
    }

    public function generate_demo_data()
    {
        return $this->dispatchLegacy('Dashboard', 'generate_demo_data', func_get_args());
    }

    public function reset_demo_data()
    {
        return $this->dispatchLegacy('Dashboard', 'reset_demo_data', func_get_args());
    }

    public function search_vouchers_ajax()
    {
        return $this->dispatchLegacy('Dashboard', 'search_vouchers_ajax', func_get_args());
    }


    public function _remap($method, ...$params)
    {
        if (method_exists($this, $method)) {
            return $this->$method(...$params);
        }
        return $this->dispatchLegacy('Dashboard', $method, $params);
    }

    protected function dispatchLegacy(string $controller, string $method, array $params)
    {
        // Set up compatibility environment
        $_SERVER['CI4_LEGACY_CONTROLLER'] = $controller;
        $_SERVER['CI4_LEGACY_METHOD'] = $method;
        $_SERVER['CI4_LEGACY_PARAMS'] = $params;

        require_once APPPATH . '../application/core/MY_Controller.php';
        $target = APPPATH . '../application/controllers/accounts/' . $controller . '.php';
        if (file_exists($target)) {
            require_once $target;
            $instance = new $controller();
            return call_user_func_array([$instance, $method], $params);
        }

        return redirect()->to(base_url('admin/unauthorized'));
    }
}