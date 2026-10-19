<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

/**
 * AttendenceController
 *
 * Auto-generated CI4 controller migrating CI3 Attendence.
 */
class AttendenceController extends BaseController
{
    public function getdaysubattendence()
    {
        return $this->dispatchLegacy('Attendence', 'getdaysubattendence', func_get_args());
    }

    public function index()
    {
        return $this->dispatchLegacy('Attendence', 'index', func_get_args());
    }

    public function getAttendence()
    {
        return $this->dispatchLegacy('Attendence', 'getAttendence', func_get_args());
    }

    public function getevents()
    {
        return $this->dispatchLegacy('Attendence', 'getevents', func_get_args());
    }

    protected function dispatchLegacy(string $controller, string $method, array $params)
    {
        // Set up compatibility environment
        $_SERVER['CI4_LEGACY_CONTROLLER'] = $controller;
        $_SERVER['CI4_LEGACY_METHOD'] = $method;
        $_SERVER['CI4_LEGACY_PARAMS'] = $params;

        require_once APPPATH . '../application/core/MY_Controller.php';
        $target = APPPATH . '../application/controllers/user/' . $controller . '.php';
        if (file_exists($target)) {
            require_once $target;
            $instance = new $controller();
            return call_user_func_array([$instance, $method], $params);
        }

        return redirect()->to(base_url('admin/unauthorized'));
    }
}