<?php

namespace App\Controllers;

use App\Controllers\BaseController;

/**
 * WhatsappconfigController
 *
 * Auto-generated CI4 controller migrating CI3 Whatsappconfig.
 */
class WhatsappconfigController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Whatsappconfig', 'index', func_get_args());
    }

    public function twilio()
    {
        return $this->dispatchLegacy('Whatsappconfig', 'twilio', func_get_args());
    }

    public function metawhatsapp()
    {
        return $this->dispatchLegacy('Whatsappconfig', 'metawhatsapp', func_get_args());
    }

    public function richautomate()
    {
        return $this->dispatchLegacy('Whatsappconfig', 'richautomate', func_get_args());
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