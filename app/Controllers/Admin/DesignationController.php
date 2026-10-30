<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * DesignationController
 *
 * Auto-generated CI4 controller migrating CI3 Designation.
 */
class DesignationController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Designation', 'designation', func_get_args());
    }

    public function designation()
    {
        return $this->dispatchLegacy('Designation', 'designation', func_get_args());
    }

    public function designationedit($id = null)
    {
        return $this->dispatchLegacy('Designation', 'designationedit', func_get_args());
    }

    public function designationdelete($id = null)
    {
        return $this->dispatchLegacy('Designation', 'designationdelete', func_get_args());
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
