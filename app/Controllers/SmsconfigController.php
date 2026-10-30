<?php

namespace App\Controllers;

use App\Controllers\BaseController;

/**
 * SmsconfigController
 *
 * Auto-generated CI4 controller migrating CI3 Smsconfig.
 */
class SmsconfigController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Smsconfig', 'index', func_get_args());
    }

    public function clickatell()
    {
        return $this->dispatchLegacy('Smsconfig', 'clickatell', func_get_args());
    }

    public function twilio()
    {
        return $this->dispatchLegacy('Smsconfig', 'twilio', func_get_args());
    }

    public function custom()
    {
        return $this->dispatchLegacy('Smsconfig', 'custom', func_get_args());
    }

    public function msgnineone()
    {
        return $this->dispatchLegacy('Smsconfig', 'msgnineone', func_get_args());
    }

    public function smscountry()
    {
        return $this->dispatchLegacy('Smsconfig', 'smscountry', func_get_args());
    }

    public function textlocal()
    {
        return $this->dispatchLegacy('Smsconfig', 'textlocal', func_get_args());
    }

    public function bulk_sms()
    {
        return $this->dispatchLegacy('Smsconfig', 'bulk_sms', func_get_args());
    }

    public function smsgatewayhub()
    {
        return $this->dispatchLegacy('Smsconfig', 'smsgatewayhub', func_get_args());
    }

    public function mobireach()
    {
        return $this->dispatchLegacy('Smsconfig', 'mobireach', func_get_args());
    }

    public function nexmo()
    {
        return $this->dispatchLegacy('Smsconfig', 'nexmo', func_get_args());
    }

    public function africastalking()
    {
        return $this->dispatchLegacy('Smsconfig', 'africastalking', func_get_args());
    }

    public function smseg()
    {
        return $this->dispatchLegacy('Smsconfig', 'smseg', func_get_args());
    }

    protected function dispatchLegacy(string $controller, string $method, array $params)
    {
        // Set up compatibility environment
        $_SERVER['CI4_LEGACY_CONTROLLER'] = $controller;
        $_SERVER['CI4_LEGACY_METHOD'] = $method;
        $_SERVER['CI4_LEGACY_PARAMS'] = $params;

        require_once APPPATH . '../application/core/MY_Controller.php';
        $target = APPPATH . '../application/controllers/' . $controller . '.php';
        if (file_exists($target)) {
            require_once $target;
            $instance = new $controller();
            return call_user_func_array([$instance, $method], $params);
        }

        return redirect()->to(base_url('admin/unauthorized'));
    }
}