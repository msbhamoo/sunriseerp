<?php

namespace App\Controllers\Onlineadmission;

use App\Controllers\BaseController;

/**
 * StripeController
 *
 * Auto-generated CI4 controller migrating CI3 Stripe.
 */
class StripeController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Stripe', 'index', func_get_args());
    }

    public function create_payment_intent()
    {
        return $this->dispatchLegacy('Stripe', 'create_payment_intent', func_get_args());
    }

    public function create_customer()
    {
        return $this->dispatchLegacy('Stripe', 'create_customer', func_get_args());
    }

    public function insert_payment()
    {
        return $this->dispatchLegacy('Stripe', 'insert_payment', func_get_args());
    }

    public function complete()
    {
        return $this->dispatchLegacy('Stripe', 'complete', func_get_args());
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