<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * SubstitutionController
 *
 * Auto-generated CI4 controller migrating CI3 Substitution.
 */
class SubstitutionController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Substitution', 'index', func_get_args());
    }

    public function check_leave_status()
    {
        return $this->dispatchLegacy('Substitution', 'check_leave_status', func_get_args());
    }

    public function get_absent_staff()
    {
        return $this->dispatchLegacy('Substitution', 'get_absent_staff', func_get_args());
    }

    public function get_staff_timetable()
    {
        return $this->dispatchLegacy('Substitution', 'get_staff_timetable', func_get_args());
    }

    public function validate_conflict()
    {
        return $this->dispatchLegacy('Substitution', 'validate_conflict', func_get_args());
    }

    public function save_substitution()
    {
        return $this->dispatchLegacy('Substitution', 'save_substitution', func_get_args());
    }

    public function history()
    {
        return $this->dispatchLegacy('Substitution', 'history', func_get_args());
    }

    public function todays_schedule()
    {
        return $this->dispatchLegacy('Substitution', 'todays_schedule', func_get_args());
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