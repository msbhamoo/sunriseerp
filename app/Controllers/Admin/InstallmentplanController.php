<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * InstallmentplanController
 *
 * Auto-generated CI4 controller migrating CI3 Installmentplan.
 */
class InstallmentplanController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Installmentplan', 'index', func_get_args());
    }

    public function add()
    {
        return $this->dispatchLegacy('Installmentplan', 'add', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Installmentplan', 'edit', func_get_args());
    }

    public function getPlanDetails()
    {
        return $this->dispatchLegacy('Installmentplan', 'getPlanDetails', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Installmentplan', 'delete', func_get_args());
    }

    public function debug($student_session_id)
    {
        return $this->dispatchLegacy('Installmentplan', 'debug', func_get_args());
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