<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * InstallmentreportconfigController
 *
 * Auto-generated CI4 controller migrating CI3 Installmentreportconfig.
 */
class InstallmentreportconfigController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Installmentreportconfig', 'index', func_get_args());
    }

    public function save()
    {
        return $this->dispatchLegacy('Installmentreportconfig', 'save', func_get_args());
    }

    public function get_plan()
    {
        return $this->dispatchLegacy('Installmentreportconfig', 'get_plan', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Installmentreportconfig', 'delete', func_get_args());
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