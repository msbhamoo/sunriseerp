<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * MaterialregisterController
 *
 * Auto-generated CI4 controller migrating CI3 Materialregister.
 */
class MaterialregisterController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Materialregister', 'index', func_get_args());
    }

    public function print_slip($id)
    {
        return $this->dispatchLegacy('Materialregister', 'print_slip', func_get_args());
    }

    public function quick_update_time()
    {
        return $this->dispatchLegacy('Materialregister', 'quick_update_time', func_get_args());
    }

    public function get_details_json($id)
    {
        return $this->dispatchLegacy('Materialregister', 'get_details_json', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Materialregister', 'edit', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Materialregister', 'delete', func_get_args());
    }

    public function download($id)
    {
        return $this->dispatchLegacy('Materialregister', 'download', func_get_args());
    }

    public function additem()
    {
        return $this->dispatchLegacy('Materialregister', 'additem', func_get_args());
    }

    public function handle_upload($str, $field)
    {
        return $this->dispatchLegacy('Materialregister', 'handle_upload', func_get_args());
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