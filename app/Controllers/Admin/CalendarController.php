<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * CalendarController
 *
 * Auto-generated CI4 controller migrating CI3 Calendar.
 */
class CalendarController extends BaseController
{
    public function events()
    {
        return $this->dispatchLegacy('Calendar', 'events', func_get_args());
    }

    public function addtodo()
    {
        return $this->dispatchLegacy('Calendar', 'addtodo', func_get_args());
    }

    public function saveevent()
    {
        return $this->dispatchLegacy('Calendar', 'saveevent', func_get_args());
    }

    public function updateevent()
    {
        return $this->dispatchLegacy('Calendar', 'updateevent', func_get_args());
    }

    public function getevents()
    {
        return $this->dispatchLegacy('Calendar', 'getevents', func_get_args());
    }

    public function view_event($id)
    {
        return $this->dispatchLegacy('Calendar', 'view_event', func_get_args());
    }

    public function delete_event($id)
    {
        return $this->dispatchLegacy('Calendar', 'delete_event', func_get_args());
    }

    public function gettaskbyid($id)
    {
        return $this->dispatchLegacy('Calendar', 'gettaskbyid', func_get_args());
    }

    public function markcomplete($id)
    {
        return $this->dispatchLegacy('Calendar', 'markcomplete', func_get_args());
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