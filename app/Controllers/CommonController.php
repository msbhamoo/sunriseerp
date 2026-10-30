<?php

namespace App\Controllers;

use App\Controllers\BaseController;

/**
 * CommonController
 *
 * Auto-generated CI4 controller migrating CI3 Common.
 */
class CommonController extends BaseController
{
    public function parents()
    {
        return $this->dispatchLegacy('Common', 'parents', func_get_args());
    }

    public function getSudentSessions()
    {
        return $this->dispatchLegacy('Common', 'getSudentSessions', func_get_args());
    }

    public function getStudentSessionClasses()
    {
        return $this->dispatchLegacy('Common', 'getStudentSessionClasses', func_get_args());
    }

    public function getStudentClass()
    {
        return $this->dispatchLegacy('Common', 'getStudentClass', func_get_args());
    }

    public function getAllSession()
    {
        return $this->dispatchLegacy('Common', 'getAllSession', func_get_args());
    }

    public function updateSession()
    {
        return $this->dispatchLegacy('Common', 'updateSession', func_get_args());
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