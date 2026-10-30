<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * StaffidcardController
 *
 * Auto-generated CI4 controller migrating CI3 Staffidcard.
 */
class StaffidcardController extends BaseController
{
    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Staffidcard', 'validateCanUploadFile', func_get_args());
    }

    public function index()
    {
        return $this->dispatchLegacy('Staffidcard', 'index', func_get_args());
    }

    public function create()
    {
        return $this->dispatchLegacy('Staffidcard', 'create', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Staffidcard', 'edit', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Staffidcard', 'delete', func_get_args());
    }

    public function view()
    {
        return $this->dispatchLegacy('Staffidcard', 'view', func_get_args());
    }

    public function background_image_handle_upload()
    {
        return $this->dispatchLegacy('Staffidcard', 'background_image_handle_upload', func_get_args());
    }

    public function logo_img_handle_upload()
    {
        return $this->dispatchLegacy('Staffidcard', 'logo_img_handle_upload', func_get_args());
    }

    public function sign_image_handle_upload()
    {
        return $this->dispatchLegacy('Staffidcard', 'sign_image_handle_upload', func_get_args());
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