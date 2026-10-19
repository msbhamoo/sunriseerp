<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

/**
 * OfflinepaymentController
 *
 * Auto-generated CI4 controller migrating CI3 Offlinepayment.
 */
class OfflinepaymentController extends BaseController
{
    public function getBankPayments()
    {
        return $this->dispatchLegacy('Offlinepayment', 'getBankPayments', func_get_args());
    }

    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Offlinepayment', 'validateCanUploadFile', func_get_args());
    }

    public function index()
    {
        return $this->dispatchLegacy('Offlinepayment', 'index', func_get_args());
    }

    public function handle_upload($str, $is_required)
    {
        return $this->dispatchLegacy('Offlinepayment', 'handle_upload', func_get_args());
    }

    public function getlistbyuser()
    {
        return $this->dispatchLegacy('Offlinepayment', 'getlistbyuser', func_get_args());
    }

    public function getpayment()
    {
        return $this->dispatchLegacy('Offlinepayment', 'getpayment', func_get_args());
    }

    public function download($id)
    {
        return $this->dispatchLegacy('Offlinepayment', 'download', func_get_args());
    }

    protected function dispatchLegacy(string $controller, string $method, array $params)
    {
        // Set up compatibility environment
        $_SERVER['CI4_LEGACY_CONTROLLER'] = $controller;
        $_SERVER['CI4_LEGACY_METHOD'] = $method;
        $_SERVER['CI4_LEGACY_PARAMS'] = $params;

        require_once APPPATH . '../application/core/MY_Controller.php';
        $target = APPPATH . '../application/controllers/user/' . $controller . '.php';
        if (file_exists($target)) {
            require_once $target;
            $instance = new $controller();
            return call_user_func_array([$instance, $method], $params);
        }

        return redirect()->to(base_url('admin/unauthorized'));
    }
}