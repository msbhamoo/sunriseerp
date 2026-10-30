<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * TimetableController
 *
 * Auto-generated CI4 controller migrating CI3 Timetable.
 */
class TimetableController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Timetable', 'index', func_get_args());
    }

    public function mytimetable()
    {
        return $this->dispatchLegacy('Timetable', 'mytimetable', func_get_args());
    }

    public function view($id)
    {
        return $this->dispatchLegacy('Timetable', 'view', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Timetable', 'delete', func_get_args());
    }

    public function create()
    {
        return $this->dispatchLegacy('Timetable', 'create', func_get_args());
    }

    public function classreport()
    {
        return $this->dispatchLegacy('Timetable', 'classreport', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Timetable', 'edit', func_get_args());
    }

    public function getBydategroupclasssection()
    {
        return $this->dispatchLegacy('Timetable', 'getBydategroupclasssection', func_get_args());
    }

    public function savegroup()
    {
        return $this->dispatchLegacy('Timetable', 'savegroup', func_get_args());
    }

    public function quick_generate()
    {
        return $this->dispatchLegacy('Timetable', 'quick_generate', func_get_args());
    }

    public function shift_timetable_time()
    {
        return $this->dispatchLegacy('Timetable', 'shift_timetable_time', func_get_args());
    }

    public function copy_timetable()
    {
        return $this->dispatchLegacy('Timetable', 'copy_timetable', func_get_args());
    }

    public function getteachertimetable()
    {
        return $this->dispatchLegacy('Timetable', 'getteachertimetable', func_get_args());
    }

    public function printclasstimetable()
    {
        return $this->dispatchLegacy('Timetable', 'printclasstimetable', func_get_args());
    }

    public function printteachertimetable()
    {
        return $this->dispatchLegacy('Timetable', 'printteachertimetable', func_get_args());
    }

    public function check_class_dublicate_recored()
    {
        return $this->dispatchLegacy('Timetable', 'check_class_dublicate_recored', func_get_args());
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