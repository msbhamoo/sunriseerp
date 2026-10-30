<?php

namespace App\Controllers\Accounts;

use App\Controllers\BaseController;

/**
 * LedgermasterController
 *
 * Auto-generated CI4 controller migrating CI3 Ledgermaster.
 */
class LedgermasterController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Ledgermaster', 'index', func_get_args());
    }

    public function getlist()
    {
        return $this->dispatchLegacy('Ledgermaster', 'getlist', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Ledgermaster', 'edit', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Ledgermaster', 'delete', func_get_args());
    }

    public function get_balance()
    {
        return $this->dispatchLegacy('Ledgermaster', 'get_balance', func_get_args());
    }

    public function addbank()
    {
        return $this->dispatchLegacy('Ledgermaster', 'addbank', func_get_args());
    }


    public function _remap($method, ...$params)
    {
        if (method_exists($this, $method)) {
            return $this->$method(...$params);
        }
        return $this->dispatchLegacy('Ledgermaster', $method, $params);
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