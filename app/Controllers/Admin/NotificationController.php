<?php

namespace App\Controllers\Admin;

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

    public function delete($id)
    {
        return $this->dispatchLegacy('Notification', 'delete', func_get_args());
    }

    public function setting()
    {
        return $this->dispatchLegacy('Notification', 'setting', func_get_args());
    }

    public function read()
    {
        return $this->dispatchLegacy('Notification', 'read', func_get_args());
    }

    public function gettemplate()
    {
        return $this->dispatchLegacy('Notification', 'gettemplate', func_get_args());
    }

    public function savetemplate()
    {
        return $this->dispatchLegacy('Notification', 'savetemplate', func_get_args());
    }

    public function handle_upload()
    {
        return $this->dispatchLegacy('Notification', 'handle_upload', func_get_args());
    }

    public function download($id)
    {
        return $this->dispatchLegacy('Notification', 'download', func_get_args());
    }

    public function notification()
    {
        return $this->dispatchLegacy('Notification', 'notification', func_get_args());
    }

    public function multihandle_upload($str, $var)
    {
        return $this->dispatchLegacy('Notification', 'multihandle_upload', func_get_args());
    }

    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Notification', 'validateCanUploadFile', func_get_args());
    }

    public function add()
    {
        return $this->dispatchLegacy('Notification', 'add', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Notification', 'edit', func_get_args());
    }

    public function view_template()
    {
        return $this->dispatchLegacy('Notification', 'view_template', func_get_args());
    }

    public function delete_notice_board_log()
    {
        return $this->dispatchLegacy('Notification', 'delete_notice_board_log', func_get_args());
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