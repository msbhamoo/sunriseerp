<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * StudentidcardController
 *
 * Auto-generated CI4 controller migrating CI3 Studentidcard.
 */
class StudentidcardController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Studentidcard', 'index', func_get_args());
    }

    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Studentidcard', 'validateCanUploadFile', func_get_args());
    }

    public function create()
    {
        return $this->dispatchLegacy('Studentidcard', 'create', func_get_args());
    }

    public function handle_upload($str, $var)
    {
        return $this->dispatchLegacy('Studentidcard', 'handle_upload', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Studentidcard', 'edit', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Studentidcard', 'delete', func_get_args());
    }

    public function view()
    {
        return $this->dispatchLegacy('Studentidcard', 'view', func_get_args());
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