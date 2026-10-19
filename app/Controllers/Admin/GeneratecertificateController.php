<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * GeneratecertificateController
 *
 * Auto-generated CI4 controller migrating CI3 Generatecertificate.
 */
class GeneratecertificateController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Generatecertificate', 'index', func_get_args());
    }

    public function search()
    {
        return $this->dispatchLegacy('Generatecertificate', 'search', func_get_args());
    }

    public function generate($student, $class, $certificate)
    {
        return $this->dispatchLegacy('Generatecertificate', 'generate', func_get_args());
    }

    public function generatemultiple()
    {
        return $this->dispatchLegacy('Generatecertificate', 'generatemultiple', func_get_args());
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