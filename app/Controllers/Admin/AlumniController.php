<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * AlumniController
 *
 * Auto-generated CI4 controller migrating CI3 Alumni.
 */
class AlumniController extends BaseController
{
    public function alumnilist()
    {
        return $this->dispatchLegacy('Alumni', 'alumnilist', func_get_args());
    }

    public function get_alumnidetails()
    {
        return $this->dispatchLegacy('Alumni', 'get_alumnidetails', func_get_args());
    }

    public function change_show_on_website()
    {
        return $this->dispatchLegacy('Alumni', 'change_show_on_website', func_get_args());
    }

    public function story($student_id = null)
    {
        return $this->dispatchLegacy('Alumni', 'story', func_get_args());
    }

    public function get_story_details()
    {
        return $this->dispatchLegacy('Alumni', 'get_story_details', func_get_args());
    }

    public function save_story()
    {
        return $this->dispatchLegacy('Alumni', 'save_story', func_get_args());
    }

    public function create_new_alumni()
    {
        return $this->dispatchLegacy('Alumni', 'create_new_alumni', func_get_args());
    }

    public function import()
    {
        return $this->dispatchLegacy('Alumni', 'import', func_get_args());
    }

    public function exportformat()
    {
        return $this->dispatchLegacy('Alumni', 'exportformat', func_get_args());
    }

    public function handle_csv_upload()
    {
        return $this->dispatchLegacy('Alumni', 'handle_csv_upload', func_get_args());
    }

    public function add()
    {
        return $this->dispatchLegacy('Alumni', 'add', func_get_args());
    }

    public function events()
    {
        return $this->dispatchLegacy('Alumni', 'events', func_get_args());
    }

    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Alumni', 'validateCanUploadFile', func_get_args());
    }

    public function add_event()
    {
        return $this->dispatchLegacy('Alumni', 'add_event', func_get_args());
    }

    public function get_event($id)
    {
        return $this->dispatchLegacy('Alumni', 'get_event', func_get_args());
    }

    public function delete_event($id)
    {
        return $this->dispatchLegacy('Alumni', 'delete_event', func_get_args());
    }

    public function getevent()
    {
        return $this->dispatchLegacy('Alumni', 'getevent', func_get_args());
    }

    public function deletestudent($id)
    {
        return $this->dispatchLegacy('Alumni', 'deletestudent', func_get_args());
    }

    public function handle_upload($str, $var)
    {
        return $this->dispatchLegacy('Alumni', 'handle_upload', func_get_args());
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