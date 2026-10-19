<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * PaymentsettingsController
 *
 * Auto-generated CI4 controller migrating CI3 Paymentsettings.
 */
class PaymentsettingsController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Paymentsettings', 'index', func_get_args());
    }

    public function paypal()
    {
        return $this->dispatchLegacy('Paymentsettings', 'paypal', func_get_args());
    }

    public function stripe()
    {
        return $this->dispatchLegacy('Paymentsettings', 'stripe', func_get_args());
    }

    public function payu()
    {
        return $this->dispatchLegacy('Paymentsettings', 'payu', func_get_args());
    }

    public function twocheckout()
    {
        return $this->dispatchLegacy('Paymentsettings', 'twocheckout', func_get_args());
    }

    public function ccavenue()
    {
        return $this->dispatchLegacy('Paymentsettings', 'ccavenue', func_get_args());
    }

    public function paystack()
    {
        return $this->dispatchLegacy('Paymentsettings', 'paystack', func_get_args());
    }

    public function instamojo()
    {
        return $this->dispatchLegacy('Paymentsettings', 'instamojo', func_get_args());
    }

    public function razorpay()
    {
        return $this->dispatchLegacy('Paymentsettings', 'razorpay', func_get_args());
    }

    public function paytm()
    {
        return $this->dispatchLegacy('Paymentsettings', 'paytm', func_get_args());
    }

    public function midtrans()
    {
        return $this->dispatchLegacy('Paymentsettings', 'midtrans', func_get_args());
    }

    public function pesapal()
    {
        return $this->dispatchLegacy('Paymentsettings', 'pesapal', func_get_args());
    }

    public function ipayafrica()
    {
        return $this->dispatchLegacy('Paymentsettings', 'ipayafrica', func_get_args());
    }

    public function flutterwave()
    {
        return $this->dispatchLegacy('Paymentsettings', 'flutterwave', func_get_args());
    }

    public function jazzcash()
    {
        return $this->dispatchLegacy('Paymentsettings', 'jazzcash', func_get_args());
    }

    public function billplz()
    {
        return $this->dispatchLegacy('Paymentsettings', 'billplz', func_get_args());
    }

    public function sslcommerz()
    {
        return $this->dispatchLegacy('Paymentsettings', 'sslcommerz', func_get_args());
    }

    public function walkingm()
    {
        return $this->dispatchLegacy('Paymentsettings', 'walkingm', func_get_args());
    }

    public function mollie()
    {
        return $this->dispatchLegacy('Paymentsettings', 'mollie', func_get_args());
    }

    public function cashfree()
    {
        return $this->dispatchLegacy('Paymentsettings', 'cashfree', func_get_args());
    }

    public function payfast()
    {
        return $this->dispatchLegacy('Paymentsettings', 'payfast', func_get_args());
    }

    public function toyyibPay()
    {
        return $this->dispatchLegacy('Paymentsettings', 'toyyibPay', func_get_args());
    }

    public function skrill()
    {
        return $this->dispatchLegacy('Paymentsettings', 'skrill', func_get_args());
    }

    public function payhere()
    {
        return $this->dispatchLegacy('Paymentsettings', 'payhere', func_get_args());
    }

    public function onepay()
    {
        return $this->dispatchLegacy('Paymentsettings', 'onepay', func_get_args());
    }

    public function dpopay()
    {
        return $this->dispatchLegacy('Paymentsettings', 'dpopay', func_get_args());
    }

    public function momopay()
    {
        return $this->dispatchLegacy('Paymentsettings', 'momopay', func_get_args());
    }

    public function kowri()
    {
        return $this->dispatchLegacy('Paymentsettings', 'kowri', func_get_args());
    }

    public function payment_gateway_config()
    {
        return $this->dispatchLegacy('Paymentsettings', 'payment_gateway_config', func_get_args());
    }

    public function setting()
    {
        return $this->dispatchLegacy('Paymentsettings', 'setting', func_get_args());
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