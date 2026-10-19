<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * SyllabusController
 *
 * Auto-generated CI4 controller migrating CI3 Syllabus.
 */
class SyllabusController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Syllabus', 'index', func_get_args());
    }

    public function get_weekdates()
    {
        return $this->dispatchLegacy('Syllabus', 'get_weekdates', func_get_args());
    }

    public function get_subject_syllabus()
    {
        return $this->dispatchLegacy('Syllabus', 'get_subject_syllabus', func_get_args());
    }

    public function delete_subject_syllabus()
    {
        return $this->dispatchLegacy('Syllabus', 'delete_subject_syllabus', func_get_args());
    }

    public function get_subject_syllabusdata()
    {
        return $this->dispatchLegacy('Syllabus', 'get_subject_syllabusdata', func_get_args());
    }

    public function getsubject_syllabus($id)
    {
        return $this->dispatchLegacy('Syllabus', 'getsubject_syllabus', func_get_args());
    }

    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Syllabus', 'validateCanUploadFile', func_get_args());
    }

    public function add_syllabus()
    {
        return $this->dispatchLegacy('Syllabus', 'add_syllabus', func_get_args());
    }

    public function download($id)
    {
        return $this->dispatchLegacy('Syllabus', 'download', func_get_args());
    }

    public function lacture_video_download($id)
    {
        return $this->dispatchLegacy('Syllabus', 'lacture_video_download', func_get_args());
    }

    public function handle_uploadlacturevideo()
    {
        return $this->dispatchLegacy('Syllabus', 'handle_uploadlacturevideo', func_get_args());
    }

    public function handle_upload()
    {
        return $this->dispatchLegacy('Syllabus', 'handle_upload', func_get_args());
    }

    public function status()
    {
        return $this->dispatchLegacy('Syllabus', 'status', func_get_args());
    }

    public function addmessage()
    {
        return $this->dispatchLegacy('Syllabus', 'addmessage', func_get_args());
    }

    public function getmessage()
    {
        return $this->dispatchLegacy('Syllabus', 'getmessage', func_get_args());
    }

    public function deletemessage()
    {
        return $this->dispatchLegacy('Syllabus', 'deletemessage', func_get_args());
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