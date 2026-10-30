<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * CertificateregisterController
 *
 * Auto-generated CI4 controller migrating CI3 Certificateregister.
 */
class CertificateregisterController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Certificateregister', 'index', func_get_args());
    }

    public function generate($student_id = null)
    {
        return $this->dispatchLegacy('Certificateregister', 'generate', func_get_args());
    }

    public function settings()
    {
        return $this->dispatchLegacy('Certificateregister', 'settings', func_get_args());
    }

    public function download($cert_id)
    {
        return $this->dispatchLegacy('Certificateregister', 'download', func_get_args());
    }

    public function revert($id)
    {
        return $this->dispatchLegacy('Certificateregister', 'revert', func_get_args());
    }

    public function search_student_ajax()
    {
        return $this->dispatchLegacy('Certificateregister', 'search_student_ajax', func_get_args());
    }

    public function get_student_fee_summary_ajax()
    {
        return $this->dispatchLegacy('Certificateregister', 'get_student_fee_summary_ajax', func_get_args());
    }

    public function get_cert_number_ajax()
    {
        return $this->dispatchLegacy('Certificateregister', 'get_cert_number_ajax', func_get_args());
    }

    public function save_scholar_history_ajax()
    {
        return $this->dispatchLegacy('Certificateregister', 'save_scholar_history_ajax', func_get_args());
    }

    public function generate_cert_ajax()
    {
        return $this->dispatchLegacy('Certificateregister', 'generate_cert_ajax', func_get_args());
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