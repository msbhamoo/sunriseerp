<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * HostelregistrationController
 *
 * Auto-generated CI4 controller migrating CI3 Hostelregistration.
 */
class HostelregistrationController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Hostelregistration', 'index', func_get_args());
    }

    public function search_student()
    {
        return $this->dispatchLegacy('Hostelregistration', 'search_student', func_get_args());
    }

    public function get_applicable_fees()
    {
        return $this->dispatchLegacy('Hostelregistration', 'get_applicable_fees', func_get_args());
    }

    public function assign_bed()
    {
        return $this->dispatchLegacy('Hostelregistration', 'assign_bed', func_get_args());
    }

    public function unassign_bed()
    {
        return $this->dispatchLegacy('Hostelregistration', 'unassign_bed', func_get_args());
    }

    public function transfer_bed()
    {
        return $this->dispatchLegacy('Hostelregistration', 'transfer_bed', func_get_args());
    }

    public function get_bed_history()
    {
        return $this->dispatchLegacy('Hostelregistration', 'get_bed_history', func_get_args());
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