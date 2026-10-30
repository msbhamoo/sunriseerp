<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * ExamscheduleController
 *
 * Auto-generated CI4 controller migrating CI3 Examschedule.
 */
class ExamscheduleController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Examschedule', 'index', func_get_args());
    }

    public function view($id)
    {
        return $this->dispatchLegacy('Examschedule', 'view', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Examschedule', 'delete', func_get_args());
    }

    public function create()
    {
        return $this->dispatchLegacy('Examschedule', 'create', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Examschedule', 'edit', func_get_args());
    }

    public function getexamscheduledetail()
    {
        return $this->dispatchLegacy('Examschedule', 'getexamscheduledetail', func_get_args());
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