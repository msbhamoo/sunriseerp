<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * FeesforwardController
 *
 * Auto-generated CI4 controller migrating CI3 Feesforward.
 */
class FeesforwardController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Feesforward', 'index', func_get_args());
    }

    public function findPreviousBalanceFees($session_id, $class_id, $section_id, $current_session)
    {
        return $this->dispatchLegacy('Feesforward', 'findPreviousBalanceFees', func_get_args());
    }

    public function findValueExists($array, $find)
    {
        return $this->dispatchLegacy('Feesforward', 'findValueExists', func_get_args());
    }

    public function deletefeesforward()
    {
        return $this->dispatchLegacy('Feesforward', 'deletefeesforward', func_get_args());
    }

    public function findPreviousBalanceFeesNew($session_id, $class_id, $section_id, $current_session)
    {
        return $this->dispatchLegacy('Feesforward', 'findPreviousBalanceFeesNew', func_get_args());
    }

    public function bulk_delete()
    {
        return $this->dispatchLegacy('Feesforward', 'bulk_delete', func_get_args());
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