<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * TransfercertificateController
 *
 * Auto-generated CI4 controller migrating CI3 Transfercertificate.
 */
class TransfercertificateController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Transfercertificate', 'index', func_get_args());
    }

    public function sortQueue()
    {
        return $this->dispatchLegacy('Transfercertificate', 'sortQueue', func_get_args());
    }

    public function changeformfieldsetting()
    {
        return $this->dispatchLegacy('Transfercertificate', 'changeformfieldsetting', func_get_args());
    }

    public function download()
    {
        return $this->dispatchLegacy('Transfercertificate', 'download', func_get_args());
    }

    public function print_transfer_certificate()
    {
        return $this->dispatchLegacy('Transfercertificate', 'print_transfer_certificate', func_get_args());
    }

    public function edit_custom_field($id)
    {
        return $this->dispatchLegacy('Transfercertificate', 'edit_custom_field', func_get_args());
    }

    public function save_custom_fields()
    {
        return $this->dispatchLegacy('Transfercertificate', 'save_custom_fields', func_get_args());
    }

    public function update_signature()
    {
        return $this->dispatchLegacy('Transfercertificate', 'update_signature', func_get_args());
    }

    public function handle_upload()
    {
        return $this->dispatchLegacy('Transfercertificate', 'handle_upload', func_get_args());
    }

    public function remove_signature()
    {
        return $this->dispatchLegacy('Transfercertificate', 'remove_signature', func_get_args());
    }

    public function save_generation_id()
    {
        return $this->dispatchLegacy('Transfercertificate', 'save_generation_id', func_get_args());
    }

    public function verify_tc()
    {
        return $this->dispatchLegacy('Transfercertificate', 'verify_tc', func_get_args());
    }

    public function prepare_tc()
    {
        return $this->dispatchLegacy('Transfercertificate', 'prepare_tc', func_get_args());
    }

    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Transfercertificate', 'validateCanUploadFile', func_get_args());
    }

    public function edit_header()
    {
        return $this->dispatchLegacy('Transfercertificate', 'edit_header', func_get_args());
    }

    public function handle_header_upload()
    {
        return $this->dispatchLegacy('Transfercertificate', 'handle_header_upload', func_get_args());
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