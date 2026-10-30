<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

/**
 * TeacherController
 *
 * Auto-generated CI4 controller migrating CI3 Teacher.
 */
class TeacherController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Teacher', 'index', func_get_args());
    }

    public function getSubjctByClassandSection()
    {
        return $this->dispatchLegacy('Teacher', 'getSubjctByClassandSection', func_get_args());
    }

    public function assignTeacher()
    {
        return $this->dispatchLegacy('Teacher', 'assignTeacher', func_get_args());
    }

    public function getSubjectTeachers()
    {
        return $this->dispatchLegacy('Teacher', 'getSubjectTeachers', func_get_args());
    }

    public function view($id)
    {
        return $this->dispatchLegacy('Teacher', 'view', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Teacher', 'delete', func_get_args());
    }

    public function create()
    {
        return $this->dispatchLegacy('Teacher', 'create', func_get_args());
    }

    public function handle_upload()
    {
        return $this->dispatchLegacy('Teacher', 'handle_upload', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Teacher', 'edit', func_get_args());
    }

    public function rating()
    {
        return $this->dispatchLegacy('Teacher', 'rating', func_get_args());
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