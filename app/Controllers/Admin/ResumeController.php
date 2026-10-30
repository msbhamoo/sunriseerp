<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * ResumeController
 *
 * Auto-generated CI4 controller migrating CI3 Resume.
 */
class ResumeController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Resume', 'index', func_get_args());
    }

    public function student_resume_details($id)
    {
        return $this->dispatchLegacy('Resume', 'student_resume_details', func_get_args());
    }

    public function add_work()
    {
        return $this->dispatchLegacy('Resume', 'add_work', func_get_args());
    }

    public function add_education()
    {
        return $this->dispatchLegacy('Resume', 'add_education', func_get_args());
    }

    public function add_skill()
    {
        return $this->dispatchLegacy('Resume', 'add_skill', func_get_args());
    }

    public function add_referensh()
    {
        return $this->dispatchLegacy('Resume', 'add_referensh', func_get_args());
    }

    public function resume_setting()
    {
        return $this->dispatchLegacy('Resume', 'resume_setting', func_get_args());
    }

    public function changeresumefieldsetting()
    {
        return $this->dispatchLegacy('Resume', 'changeresumefieldsetting', func_get_args());
    }

    public function change_additional_fields_status()
    {
        return $this->dispatchLegacy('Resume', 'change_additional_fields_status', func_get_args());
    }

    public function printpdfresume()
    {
        return $this->dispatchLegacy('Resume', 'printpdfresume', func_get_args());
    }

    public function download()
    {
        return $this->dispatchLegacy('Resume', 'download', func_get_args());
    }

    public function add_other_details()
    {
        return $this->dispatchLegacy('Resume', 'add_other_details', func_get_args());
    }

    public function enable_download_setting()
    {
        return $this->dispatchLegacy('Resume', 'enable_download_setting', func_get_args());
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