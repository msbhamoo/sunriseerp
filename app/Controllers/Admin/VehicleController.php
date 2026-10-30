<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * VehicleController
 *
 * Auto-generated CI4 controller migrating CI3 Vehicle.
 */
class VehicleController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Vehicle', 'index', func_get_args());
    }

    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Vehicle', 'validateCanUploadFile', func_get_args());
    }

    public function add()
    {
        return $this->dispatchLegacy('Vehicle', 'add', func_get_args());
    }

    public function getsinglevehicledata()
    {
        return $this->dispatchLegacy('Vehicle', 'getsinglevehicledata', func_get_args());
    }

    public function edit()
    {
        return $this->dispatchLegacy('Vehicle', 'edit', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Vehicle', 'delete', func_get_args());
    }

    public function vehicledetails()
    {
        return $this->dispatchLegacy('Vehicle', 'vehicledetails', func_get_args());
    }

    public function update_alert()
    {
        return $this->dispatchLegacy('Vehicle', 'update_alert', func_get_args());
    }

    public function assign_student_transport()
    {
        return $this->dispatchLegacy('Vehicle', 'assign_student_transport', func_get_args());
    }

    public function get_vehicle_students()
    {
        return $this->dispatchLegacy('Vehicle', 'get_vehicle_students', func_get_args());
    }

    public function handle_upload()
    {
        return $this->dispatchLegacy('Vehicle', 'handle_upload', func_get_args());
    }

    public function export_sample_csv()
    {
        return $this->dispatchLegacy('Vehicle', 'export_sample_csv', func_get_args());
    }

    public function preview_bulk_csv()
    {
        return $this->dispatchLegacy('Vehicle', 'preview_bulk_csv', func_get_args());
    }

    public function confirm_bulk_csv()
    {
        return $this->dispatchLegacy('Vehicle', 'confirm_bulk_csv', func_get_args());
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