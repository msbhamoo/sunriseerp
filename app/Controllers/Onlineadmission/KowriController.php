<?php

namespace App\Controllers\Onlineadmission;

use App\Controllers\BaseController;

/**
 * KowriController
 *
 * Auto-generated CI4 controller migrating CI3 Kowri.
 */
class KowriController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Kowri', 'index', func_get_args());
    }

    public function pay()
    {
        return $this->dispatchLegacy('Kowri', 'pay', func_get_args());
    }

    public function success()
    {
        return $this->dispatchLegacy('Kowri', 'success', func_get_args());
    }

    public function cancel()
    {
        return $this->dispatchLegacy('Kowri', 'cancel', func_get_args());
    }

    public function webhook()
    {
        return $this->dispatchLegacy('Kowri', 'webhook', func_get_args());
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