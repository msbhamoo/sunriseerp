<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

/**
 * ExamController
 *
 * Auto-generated CI4 controller migrating CI3 Exam.
 */
class ExamController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Exam', 'index', func_get_args());
    }

    public function view($id)
    {
        return $this->dispatchLegacy('Exam', 'view', func_get_args());
    }

    public function getByFeecategory()
    {
        return $this->dispatchLegacy('Exam', 'getByFeecategory', func_get_args());
    }

    public function getStudentCategoryFee()
    {
        return $this->dispatchLegacy('Exam', 'getStudentCategoryFee', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Exam', 'delete', func_get_args());
    }

    public function create()
    {
        return $this->dispatchLegacy('Exam', 'create', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Exam', 'edit', func_get_args());
    }

    public function examSearch()
    {
        return $this->dispatchLegacy('Exam', 'examSearch', func_get_args());
    }

    public function examresult()
    {
        return $this->dispatchLegacy('Exam', 'examresult', func_get_args());
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