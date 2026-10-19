<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

/**
 * NotificationController
 *
 * Auto-generated CI4 controller migrating CI3 Notification.
 */
class NotificationController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Notification', 'index', func_get_args());
    }

    public function updatestatus()
    {
        return $this->dispatchLegacy('Notification', 'updatestatus', func_get_args());
    }

    public function read()
    {
        return $this->dispatchLegacy('Notification', 'read', func_get_args());
    }

    public function download($id)
    {
        return $this->dispatchLegacy('Notification', 'download', func_get_args());
    }

    public function notification()
    {
        return $this->dispatchLegacy('Notification', 'notification', func_get_args());
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