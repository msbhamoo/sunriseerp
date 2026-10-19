<?php

namespace App\Controllers\Cbseexam;

use App\Controllers\BaseController;

/**
 * CbseadmitcardController
 *
 * Auto-generated CI4 controller migrating CI3 Cbseadmitcard.
 */
class CbseadmitcardController extends BaseController
{
    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Cbseadmitcard', 'validateCanUploadFile', func_get_args());
    }

    public function index()
    {
        return $this->dispatchLegacy('Cbseadmitcard', 'index', func_get_args());
    }

    public function handle_upload($str, $var)
    {
        return $this->dispatchLegacy('Cbseadmitcard', 'handle_upload', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Cbseadmitcard', 'edit', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Cbseadmitcard', 'delete', func_get_args());
    }

    public function view()
    {
        return $this->dispatchLegacy('Cbseadmitcard', 'view', func_get_args());
    }

    public function save_active_status()
    {
        return $this->dispatchLegacy('Cbseadmitcard', 'save_active_status', func_get_args());
    }

    public function admitcard()
    {
        return $this->dispatchLegacy('Cbseadmitcard', 'admitcard', func_get_args());
    }

    public function printcard()
    {
        return $this->dispatchLegacy('Cbseadmitcard', 'printcard', func_get_args());
    }

    protected function dispatchLegacy(string $controller, string $method, array $params)
    {
        // Set up compatibility environment
        $_SERVER['CI4_LEGACY_CONTROLLER'] = $controller;
        $_SERVER['CI4_LEGACY_METHOD'] = $method;
        $_SERVER['CI4_LEGACY_PARAMS'] = $params;

        require_once APPPATH . '../application/core/MY_Controller.php';
        $target = APPPATH . '../application/controllers/cbseexam/' . $controller . '.php';
        if (file_exists($target)) {
            require_once $target;
            $instance = new $controller();
            return call_user_func_array([$instance, $method], $params);
        }

        return redirect()->to(base_url('admin/unauthorized'));
    }
}