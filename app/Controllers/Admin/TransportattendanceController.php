<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * TransportattendanceController
 *
 * Auto-generated CI4 controller migrating CI3 Transportattendance.
 */
class TransportattendanceController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Transportattendance', 'index', func_get_args());
    }

    public function mobile()
    {
        return $this->dispatchLegacy('Transportattendance', 'mobile', func_get_args());
    }

    public function get_vehicle_routes()
    {
        return $this->dispatchLegacy('Transportattendance', 'get_vehicle_routes', func_get_args());
    }

    public function save()
    {
        return $this->dispatchLegacy('Transportattendance', 'save', func_get_args());
    }

    public function search_student()
    {
        return $this->dispatchLegacy('Transportattendance', 'search_student', func_get_args());
    }

    public function add_custom_rider()
    {
        return $this->dispatchLegacy('Transportattendance', 'add_custom_rider', func_get_args());
    }

    public function remove_custom_rider()
    {
        return $this->dispatchLegacy('Transportattendance', 'remove_custom_rider', func_get_args());
    }

    public function daily_summary()
    {
        return $this->dispatchLegacy('Transportattendance', 'daily_summary', func_get_args());
    }

    public function monthly_summary()
    {
        return $this->dispatchLegacy('Transportattendance', 'monthly_summary', func_get_args());
    }

    public function get_summary_detail()
    {
        return $this->dispatchLegacy('Transportattendance', 'get_summary_detail', func_get_args());
    }

    public function get_monthly_summary_detail()
    {
        return $this->dispatchLegacy('Transportattendance', 'get_monthly_summary_detail', func_get_args());
    }

    public function get_route_vehicles()
    {
        return $this->dispatchLegacy('Transportattendance', 'get_route_vehicles', func_get_args());
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