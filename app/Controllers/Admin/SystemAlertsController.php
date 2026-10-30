<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * SystemAlertsController
 *
 * Auto-generated CI4 controller migrating CI3 SystemAlerts.
 */
class SystemAlertsController extends BaseController
{
    public function get_alerts()
    {
        return $this->dispatchLegacy('SystemAlerts', 'get_alerts', func_get_args());
    }

    public function mark_as_read()
    {
        return $this->dispatchLegacy('SystemAlerts', 'mark_as_read', func_get_args());
    }

    public function mark_all_as_read()
    {
        return $this->dispatchLegacy('SystemAlerts', 'mark_all_as_read', func_get_args());
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