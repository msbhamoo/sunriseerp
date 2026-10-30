<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * GatepassController
 *
 * Auto-generated CI4 controller migrating CI3 Gatepass.
 */
class GatepassController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Gatepass', 'index', func_get_args());
    }

    public function create()
    {
        return $this->dispatchLegacy('Gatepass', 'create', func_get_args());
    }

    public function search_user()
    {
        return $this->dispatchLegacy('Gatepass', 'search_user', func_get_args());
    }

    public function update_status()
    {
        return $this->dispatchLegacy('Gatepass', 'update_status', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Gatepass', 'delete', func_get_args());
    }

    public function print_gatepass($id)
    {
        return $this->dispatchLegacy('Gatepass', 'print_gatepass', func_get_args());
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