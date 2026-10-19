<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * JobpostingController
 *
 * Auto-generated CI4 controller migrating CI3 Jobposting.
 */
class JobpostingController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Jobposting', 'index', func_get_args());
    }

    public function save()
    {
        return $this->dispatchLegacy('Jobposting', 'save', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Jobposting', 'delete', func_get_args());
    }

    public function change_status()
    {
        return $this->dispatchLegacy('Jobposting', 'change_status', func_get_args());
    }

    public function toggle_close()
    {
        return $this->dispatchLegacy('Jobposting', 'toggle_close', func_get_args());
    }

    public function get_details($id)
    {
        return $this->dispatchLegacy('Jobposting', 'get_details', func_get_args());
    }

    public function applicants($job_id = null)
    {
        return $this->dispatchLegacy('Jobposting', 'applicants', func_get_args());
    }

    public function update_applicant_stage()
    {
        return $this->dispatchLegacy('Jobposting', 'update_applicant_stage', func_get_args());
    }

    public function update_applicant_notes()
    {
        return $this->dispatchLegacy('Jobposting', 'update_applicant_notes', func_get_args());
    }

    public function download_resume($id)
    {
        return $this->dispatchLegacy('Jobposting', 'download_resume', func_get_args());
    }

    public function delete_applicant($id)
    {
        return $this->dispatchLegacy('Jobposting', 'delete_applicant', func_get_args());
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