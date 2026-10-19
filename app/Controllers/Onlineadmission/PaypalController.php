<?php

namespace App\Controllers\Onlineadmission;

use App\Controllers\BaseController;

/**
 * PaypalController
 *
 * Auto-generated CI4 controller migrating CI3 Paypal.
 */
class PaypalController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Paypal', 'index', func_get_args());
    }

    public function checkout()
    {
        return $this->dispatchLegacy('Paypal', 'checkout', func_get_args());
    }

    public function getsuccesspayment()
    {
        return $this->dispatchLegacy('Paypal', 'getsuccesspayment', func_get_args());
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