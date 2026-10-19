<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * AuditController
 *
 * Auto-generated CI4 controller migrating CI3 Audit.
 */
class AuditController extends BaseController
{
    public function unauthorized()
    {
        return $this->dispatchLegacy('Audit', 'unauthorized', func_get_args());
    }

    public function index($offset = 0)
    {
        return $this->dispatchLegacy('Audit', 'index', func_get_args());
    }

    public function getDatatable()
    {
        return $this->dispatchLegacy('Audit', 'getDatatable', func_get_args());
    }

    public function delete()
    {
        return $this->dispatchLegacy('Audit', 'delete', func_get_args());
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