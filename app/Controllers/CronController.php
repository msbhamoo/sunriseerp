<?php

namespace App\Controllers;

use App\Controllers\BaseController;

/**
 * CronController
 *
 * Auto-generated CI4 controller migrating CI3 Cron.
 */
class CronController extends BaseController
{
    public function index($key = '')
    {
        return $this->dispatchLegacy('Cron', 'index', func_get_args());
    }

    public function studentcall_followup_reminder($key = "")
    {
        return $this->dispatchLegacy('Cron', 'studentcall_followup_reminder', func_get_args());
    }

    public function vehicle_expiration_reminder($key = "")
    {
        return $this->dispatchLegacy('Cron', 'vehicle_expiration_reminder', func_get_args());
    }

    public function student_attendance($key="")
    {
        return $this->dispatchLegacy('Cron', 'student_attendance', func_get_args());
    }

    public function autobackup($key = '')
    {
        return $this->dispatchLegacy('Cron', 'autobackup', func_get_args());
    }

    public function feereminder($key = "")
    {
        return $this->dispatchLegacy('Cron', 'feereminder', func_get_args());
    }

    public function eventreminder($key = "")
    {
        return $this->dispatchLegacy('Cron', 'eventreminder', func_get_args());
    }

    public function schedulesmsemails($key = "")
    {
        return $this->dispatchLegacy('Cron', 'schedulesmsemails', func_get_args());
    }

    public function send_email_digests($key = "")
    {
        return $this->dispatchLegacy('Cron', 'send_email_digests', func_get_args());
    }

    public function test_digest()
    {
        return $this->dispatchLegacy('Cron', 'test_digest', func_get_args());
    }

    public function biometric_sync($key = "")
    {
        return $this->dispatchLegacy('Cron', 'biometric_sync', func_get_args());
    }

    protected function dispatchLegacy(string $controller, string $method, array $params)
    {
        // Set up compatibility environment
        $_SERVER['CI4_LEGACY_CONTROLLER'] = $controller;
        $_SERVER['CI4_LEGACY_METHOD'] = $method;
        $_SERVER['CI4_LEGACY_PARAMS'] = $params;

        require_once APPPATH . '../application/core/MY_Controller.php';
        $target = APPPATH . '../application/controllers/' . $controller . '.php';
        if (file_exists($target)) {
            require_once $target;
            $instance = new $controller();
            return call_user_func_array([$instance, $method], $params);
        }

        return redirect()->to(base_url('admin/unauthorized'));
    }
}