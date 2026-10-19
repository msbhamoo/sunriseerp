<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * HostelsettingsController
 *
 * Auto-generated CI4 controller migrating CI3 Hostelsettings.
 */
class HostelsettingsController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Hostelsettings', 'index', func_get_args());
    }

    public function save_fee_map()
    {
        return $this->dispatchLegacy('Hostelsettings', 'save_fee_map', func_get_args());
    }

    public function save_asset_item()
    {
        return $this->dispatchLegacy('Hostelsettings', 'save_asset_item', func_get_args());
    }

    public function delete_asset_item($id)
    {
        return $this->dispatchLegacy('Hostelsettings', 'delete_asset_item', func_get_args());
    }

    public function save_warden()
    {
        return $this->dispatchLegacy('Hostelsettings', 'save_warden', func_get_args());
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