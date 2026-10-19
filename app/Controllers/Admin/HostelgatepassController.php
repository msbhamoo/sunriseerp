<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * HostelgatepassController
 *
 * Auto-generated CI4 controller migrating CI3 Hostelgatepass.
 */
class HostelgatepassController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Hostelgatepass', 'index', func_get_args());
    }

    public function search_student()
    {
        return $this->dispatchLegacy('Hostelgatepass', 'search_student', func_get_args());
    }

    public function add()
    {
        return $this->dispatchLegacy('Hostelgatepass', 'add', func_get_args());
    }

    public function mark_returned($id)
    {
        return $this->dispatchLegacy('Hostelgatepass', 'mark_returned', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Hostelgatepass', 'delete', func_get_args());
    }

    public function print_pass($id)
    {
        return $this->dispatchLegacy('Hostelgatepass', 'print_pass', func_get_args());
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