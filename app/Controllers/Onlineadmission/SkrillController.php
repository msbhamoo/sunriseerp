<?php

namespace App\Controllers\Onlineadmission;

use App\Controllers\BaseController;

/**
 * SkrillController
 *
 * Auto-generated CI4 controller migrating CI3 Skrill.
 */
class SkrillController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Skrill', 'index', func_get_args());
    }

    public function success()
    {
        return $this->dispatchLegacy('Skrill', 'success', func_get_args());
    }

    public function cancel()
    {
        return $this->dispatchLegacy('Skrill', 'cancel', func_get_args());
    }

    protected function dispatchLegacy(string $controller, string $method, array $params)
    {
        // Set up compatibility environment
        $_SERVER['CI4_LEGACY_CONTROLLER'] = $controller;
        $_SERVER['CI4_LEGACY_METHOD'] = $method;
        $_SERVER['CI4_LEGACY_PARAMS'] = $params;

        require_once APPPATH . '../application/core/MY_Controller.php';
        $target = APPPATH . '../application/controllers/onlineadmission/' . $controller . '.php';
        if (file_exists($target)) {
            require_once $target;
            $instance = new $controller();
            return call_user_func_array([$instance, $method], $params);
        }

        return redirect()->to(base_url('admin/unauthorized'));
    }
}