<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * RolesController
 *
 * Auto-generated CI4 controller migrating CI3 Roles.
 */
class RolesController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Roles', 'index', func_get_args());
    }

    public function permission($id)
    {
        return $this->dispatchLegacy('Roles', 'permission', func_get_args());
    }

    public function savecheck()
    {
        return $this->dispatchLegacy('Roles', 'savecheck', func_get_args());
    }

    public function savebulk()
    {
        return $this->dispatchLegacy('Roles', 'savebulk', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Roles', 'edit', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Roles', 'delete', func_get_args());
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