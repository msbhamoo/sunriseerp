<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * TimelineController
 *
 * Auto-generated CI4 controller migrating CI3 Timeline.
 */
class TimelineController extends BaseController
{
    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Timeline', 'validateCanUploadFile', func_get_args());
    }

    public function add()
    {
        return $this->dispatchLegacy('Timeline', 'add', func_get_args());
    }

    public function add_staff_timeline()
    {
        return $this->dispatchLegacy('Timeline', 'add_staff_timeline', func_get_args());
    }

    public function download($timeline_id)
    {
        return $this->dispatchLegacy('Timeline', 'download', func_get_args());
    }

    public function download_staff_timeline($timeline_id)
    {
        return $this->dispatchLegacy('Timeline', 'download_staff_timeline', func_get_args());
    }

    public function delete_timeline()
    {
        return $this->dispatchLegacy('Timeline', 'delete_timeline', func_get_args());
    }

    public function delete_staff_timeline($id)
    {
        return $this->dispatchLegacy('Timeline', 'delete_staff_timeline', func_get_args());
    }

    public function staff_timeline($id = 77)
    {
        return $this->dispatchLegacy('Timeline', 'staff_timeline', func_get_args());
    }

    public function handle_upload($str, $var)
    {
        return $this->dispatchLegacy('Timeline', 'handle_upload', func_get_args());
    }

    public function getstudentsingletimeline()
    {
        return $this->dispatchLegacy('Timeline', 'getstudentsingletimeline', func_get_args());
    }

    public function editstudenttimeline()
    {
        return $this->dispatchLegacy('Timeline', 'editstudenttimeline', func_get_args());
    }

    public function getstaffsingletimeline()
    {
        return $this->dispatchLegacy('Timeline', 'getstaffsingletimeline', func_get_args());
    }

    public function editstafftimeline()
    {
        return $this->dispatchLegacy('Timeline', 'editstafftimeline', func_get_args());
    }

    public function doc_handle_upload()
    {
        return $this->dispatchLegacy('Timeline', 'doc_handle_upload', func_get_args());
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