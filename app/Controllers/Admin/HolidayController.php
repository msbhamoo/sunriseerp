<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * HolidayController
 *
 * Auto-generated CI4 controller migrating CI3 Holiday.
 */
class HolidayController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Holiday', 'index', func_get_args());
    }

    public function add()
    {
        return $this->dispatchLegacy('Holiday', 'add', func_get_args());
    }

    public function delete_holiday()
    {
        return $this->dispatchLegacy('Holiday', 'delete_holiday', func_get_args());
    }

    public function getholiday()
    {
        return $this->dispatchLegacy('Holiday', 'getholiday', func_get_args());
    }

    public function holidaytype()
    {
        return $this->dispatchLegacy('Holiday', 'holidaytype', func_get_args());
    }

    public function add_holiday_type()
    {
        return $this->dispatchLegacy('Holiday', 'add_holiday_type', func_get_args());
    }

    public function editholidaytype($id)
    {
        return $this->dispatchLegacy('Holiday', 'editholidaytype', func_get_args());
    }

    public function delete_holiday_type($id)
    {
        return $this->dispatchLegacy('Holiday', 'delete_holiday_type', func_get_args());
    }

    public function get_calendar_events()
    {
        return $this->dispatchLegacy('Holiday', 'get_calendar_events', func_get_args());
    }

    public function save_calendar_settings()
    {
        return $this->dispatchLegacy('Holiday', 'save_calendar_settings', func_get_args());
    }

    public function get_yearly_summary()
    {
        return $this->dispatchLegacy('Holiday', 'get_yearly_summary', func_get_args());
    }

    public function get_dashboard_widget()
    {
        return $this->dispatchLegacy('Holiday', 'get_dashboard_widget', func_get_args());
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