<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * ContentController
 *
 * Auto-generated CI4 controller migrating CI3 Content.
 */
class ContentController extends BaseController
{
    public function upload()
    {
        return $this->dispatchLegacy('Content', 'upload', func_get_args());
    }

    public function download_content($id)
    {
        return $this->dispatchLegacy('Content', 'download_content', func_get_args());
    }

    public function getuploaddata()
    {
        return $this->dispatchLegacy('Content', 'getuploaddata', func_get_args());
    }

    public function ajaxupload()
    {
        return $this->dispatchLegacy('Content', 'ajaxupload', func_get_args());
    }

    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Content', 'validateCanUploadFile', func_get_args());
    }

    public function validateCanAddNewResource($input, $resource_name)
    {
        return $this->dispatchLegacy('Content', 'validateCanAddNewResource', func_get_args());
    }

    public function handle_upload_file($field, $var)
    {
        return $this->dispatchLegacy('Content', 'handle_upload_file', func_get_args());
    }

    public function share()
    {
        return $this->dispatchLegacy('Content', 'share', func_get_args());
    }

    public function generate_url()
    {
        return $this->dispatchLegacy('Content', 'generate_url', func_get_args());
    }

    public function delete_content($id)
    {
        return $this->dispatchLegacy('Content', 'delete_content', func_get_args());
    }

    public function delete()
    {
        return $this->dispatchLegacy('Content', 'delete', func_get_args());
    }

    public function delete_array()
    {
        return $this->dispatchLegacy('Content', 'delete_array', func_get_args());
    }

    public function ajaxupdate()
    {
        return $this->dispatchLegacy('Content', 'ajaxupdate', func_get_args());
    }

    public function getsharelist()
    {
        return $this->dispatchLegacy('Content', 'getsharelist', func_get_args());
    }

    public function index()
    {
        return $this->dispatchLegacy('Content', 'index', func_get_args());
    }

    public function getsharedcontents()
    {
        return $this->dispatchLegacy('Content', 'getsharedcontents', func_get_args());
    }

    public function index1()
    {
        return $this->dispatchLegacy('Content', 'index1', func_get_args());
    }

    public function handle_upload()
    {
        return $this->dispatchLegacy('Content', 'handle_upload', func_get_args());
    }

    public function download($file)
    {
        return $this->dispatchLegacy('Content', 'download', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Content', 'edit', func_get_args());
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