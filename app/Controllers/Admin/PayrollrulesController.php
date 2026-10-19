<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * PayrollrulesController
 *
 * Auto-generated CI4 controller migrating CI3 Payrollrules.
 */
class PayrollrulesController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Payrollrules', 'index', func_get_args());
    }

    public function rules($group_id)
    {
        return $this->dispatchLegacy('Payrollrules', 'rules', func_get_args());
    }

    public function addrule($group_id, $id = null)
    {
        return $this->dispatchLegacy('Payrollrules', 'addrule', func_get_args());
    }

    public function saverule()
    {
        return $this->dispatchLegacy('Payrollrules', 'saverule', func_get_args());
    }

    public function togglerule($id)
    {
        return $this->dispatchLegacy('Payrollrules', 'togglerule', func_get_args());
    }

    public function deleterule($id)
    {
        return $this->dispatchLegacy('Payrollrules', 'deleterule', func_get_args());
    }

    public function versions($rule_id)
    {
        return $this->dispatchLegacy('Payrollrules', 'versions', func_get_args());
    }

    public function simulate()
    {
        return $this->dispatchLegacy('Payrollrules', 'simulate', func_get_args());
    }

    public function runsimulation()
    {
        return $this->dispatchLegacy('Payrollrules', 'runsimulation', func_get_args());
    }

    public function runlogs()
    {
        return $this->dispatchLegacy('Payrollrules', 'runlogs', func_get_args());
    }

    public function runtrace($run_id)
    {
        return $this->dispatchLegacy('Payrollrules', 'runtrace', func_get_args());
    }

    public function applySimulation($run_id)
    {
        return $this->dispatchLegacy('Payrollrules', 'applySimulation', func_get_args());
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