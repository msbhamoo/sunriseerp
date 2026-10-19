<?php

namespace App\Controllers\Cbseexam;

use App\Controllers\BaseController;

/**
 * ScheduleController
 *
 * Auto-generated CI4 controller migrating CI3 Schedule.
 */
class ScheduleController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Schedule', 'index', func_get_args());
    }

    public function add()
    {
        return $this->dispatchLegacy('Schedule', 'add', func_get_args());
    }

    public function get_editdetails()
    {
        return $this->dispatchLegacy('Schedule', 'get_editdetails', func_get_args());
    }

    public function remove($id)
    {
        return $this->dispatchLegacy('Schedule', 'remove', func_get_args());
    }

    public function add_type()
    {
        return $this->dispatchLegacy('Schedule', 'add_type', func_get_args());
    }

    protected function dispatchLegacy(string $controller, string $method, array $params)
    {
        // Set up compatibility environment
        $_SERVER['CI4_LEGACY_CONTROLLER'] = $controller;
        $_SERVER['CI4_LEGACY_METHOD'] = $method;
        $_SERVER['CI4_LEGACY_PARAMS'] = $params;

        require_once APPPATH . '../application/core/MY_Controller.php';
        $target = APPPATH . '../application/controllers/cbseexam/' . $controller . '.php';
        if (file_exists($target)) {
            require_once $target;
            $instance = new $controller();
            return call_user_func_array([$instance, $method], $params);
        }

        return redirect()->to(base_url('admin/unauthorized'));
    }
}