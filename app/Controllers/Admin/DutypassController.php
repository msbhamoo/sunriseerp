<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * DutypassController
 *
 * Auto-generated CI4 controller migrating CI3 Dutypass.
 */
class DutypassController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Dutypass', 'index', func_get_args());
    }

    public function create()
    {
        return $this->dispatchLegacy('Dutypass', 'create', func_get_args());
    }

    public function get_details($id)
    {
        return $this->dispatchLegacy('Dutypass', 'get_details', func_get_args());
    }

    public function update()
    {
        return $this->dispatchLegacy('Dutypass', 'update', func_get_args());
    }

    public function update_status()
    {
        return $this->dispatchLegacy('Dutypass', 'update_status', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Dutypass', 'delete', func_get_args());
    }

    public function print_dutypass($id)
    {
        return $this->dispatchLegacy('Dutypass', 'print_dutypass', func_get_args());
    }

    public function search_staff()
    {
        return $this->dispatchLegacy('Dutypass', 'search_staff', func_get_args());
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