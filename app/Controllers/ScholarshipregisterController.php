<?php

namespace App\Controllers;

use App\Controllers\BaseController;

/**
 * ScholarshipregisterController
 *
 * Auto-generated CI4 controller migrating CI3 Scholarshipregister.
 */
class ScholarshipregisterController extends BaseController
{
    public function index($code_or_id = null)
    {
        return $this->dispatchLegacy('Scholarshipregister', 'index', func_get_args());
    }

    public function apply($code_or_id = null)
    {
        return $this->dispatchLegacy('Scholarshipregister', 'apply', func_get_args());
    }

    public function go()
    {
        return $this->dispatchLegacy('Scholarshipregister', 'go', func_get_args());
    }

    public function submit()
    {
        return $this->dispatchLegacy('Scholarshipregister', 'submit', func_get_args());
    }

    public function check_status()
    {
        return $this->dispatchLegacy('Scholarshipregister', 'check_status', func_get_args());
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