<?php

namespace App\Controllers\Accounts;

use App\Controllers\BaseController;

/**
 * RepairController
 *
 * Auto-generated CI4 controller migrating CI3 Repair.
 */
class RepairController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Repair', 'index', func_get_args());
    }

    public function fix_truncated_references()
    {
        return $this->dispatchLegacy('Repair', 'fix_truncated_references', func_get_args());
    }

    public function rebuild_balances()
    {
        return $this->dispatchLegacy('Repair', 'rebuild_balances', func_get_args());
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