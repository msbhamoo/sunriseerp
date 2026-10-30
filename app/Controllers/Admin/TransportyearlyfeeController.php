<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * TransportyearlyfeeController
 *
 * Auto-generated CI4 controller migrating CI3 Transportyearlyfee.
 */
class TransportyearlyfeeController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Transportyearlyfee', 'index', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Transportyearlyfee', 'delete', func_get_args());
    }

    public function delete_bulk($ids_string)
    {
        return $this->dispatchLegacy('Transportyearlyfee', 'delete_bulk', func_get_args());
    }

    public function search_yearly_fees()
    {
        return $this->dispatchLegacy('Transportyearlyfee', 'search_yearly_fees', func_get_args());
    }

    public function bulk_assign()
    {
        return $this->dispatchLegacy('Transportyearlyfee', 'bulk_assign', func_get_args());
    }

    public function preview_bulk_assign()
    {
        return $this->dispatchLegacy('Transportyearlyfee', 'preview_bulk_assign', func_get_args());
    }

    public function save_bulk_assign()
    {
        return $this->dispatchLegacy('Transportyearlyfee', 'save_bulk_assign', func_get_args());
    }

    public function migrate_legacy_fees()
    {
        return $this->dispatchLegacy('Transportyearlyfee', 'migrate_legacy_fees', func_get_args());
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