<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * RouteController
 *
 * Auto-generated CI4 controller migrating CI3 Route.
 */
class RouteController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Route', 'index', func_get_args());
    }

    public function create()
    {
        return $this->dispatchLegacy('Route', 'create', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Route', 'edit', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Route', 'delete', func_get_args());
    }

    public function studenttransportdetails()
    {
        return $this->dispatchLegacy('Route', 'studenttransportdetails', func_get_args());
    }

    public function pickup_point()
    {
        return $this->dispatchLegacy('Route', 'pickup_point', func_get_args());
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