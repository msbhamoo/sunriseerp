<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * UserlogController
 *
 * Auto-generated CI4 controller migrating CI3 Userlog.
 */
class UserlogController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Userlog', 'index', func_get_args());
    }

    public function getDatatable()
    {
        return $this->dispatchLegacy('Userlog', 'getDatatable', func_get_args());
    }

    public function getStudentDatatable()
    {
        return $this->dispatchLegacy('Userlog', 'getStudentDatatable', func_get_args());
    }

    public function getParentDatatable()
    {
        return $this->dispatchLegacy('Userlog', 'getParentDatatable', func_get_args());
    }

    public function getStaffDatatable()
    {
        return $this->dispatchLegacy('Userlog', 'getStaffDatatable', func_get_args());
    }

    public function delete()
    {
        return $this->dispatchLegacy('Userlog', 'delete', func_get_args());
    }

    public function getguestDatatable()
    {
        return $this->dispatchLegacy('Userlog', 'getguestDatatable', func_get_args());
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