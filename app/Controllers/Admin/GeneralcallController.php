<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * GeneralcallController
 *
 * Auto-generated CI4 controller migrating CI3 Generalcall.
 */
class GeneralcallController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Generalcall', 'index', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Generalcall', 'edit', func_get_args());
    }

    public function details($id)
    {
        return $this->dispatchLegacy('Generalcall', 'details', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Generalcall', 'delete', func_get_args());
    }

    public function check_default($post_string)
    {
        return $this->dispatchLegacy('Generalcall', 'check_default', func_get_args());
    }

    public function test()
    {
        return $this->dispatchLegacy('Generalcall', 'test', func_get_args());
    }

    public function getcalllist()
    {
        return $this->dispatchLegacy('Generalcall', 'getcalllist', func_get_args());
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