<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * MemberController
 *
 * Auto-generated CI4 controller migrating CI3 Member.
 */
class MemberController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Member', 'index', func_get_args());
    }

    public function issue($id)
    {
        return $this->dispatchLegacy('Member', 'issue', func_get_args());
    }

    public function bookreturn()
    {
        return $this->dispatchLegacy('Member', 'bookreturn', func_get_args());
    }

    public function student()
    {
        return $this->dispatchLegacy('Member', 'student', func_get_args());
    }

    public function add()
    {
        return $this->dispatchLegacy('Member', 'add', func_get_args());
    }

    public function check_cardno_exists()
    {
        return $this->dispatchLegacy('Member', 'check_cardno_exists', func_get_args());
    }

    public function teacher()
    {
        return $this->dispatchLegacy('Member', 'teacher', func_get_args());
    }

    public function addteacher()
    {
        return $this->dispatchLegacy('Member', 'addteacher', func_get_args());
    }

    public function surrender()
    {
        return $this->dispatchLegacy('Member', 'surrender', func_get_args());
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