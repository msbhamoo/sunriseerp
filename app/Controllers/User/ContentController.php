<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

/**
 * ContentController
 *
 * Auto-generated CI4 controller migrating CI3 Content.
 */
class ContentController extends BaseController
{
    public function getsharelist()
    {
        return $this->dispatchLegacy('Content', 'getsharelist', func_get_args());
    }

    public function view($id)
    {
        return $this->dispatchLegacy('Content', 'view', func_get_args());
    }

    public function download_content($id)
    {
        return $this->dispatchLegacy('Content', 'download_content', func_get_args());
    }

    public function index()
    {
        return $this->dispatchLegacy('Content', 'index', func_get_args());
    }

    public function download($file)
    {
        return $this->dispatchLegacy('Content', 'download', func_get_args());
    }

    public function assignment()
    {
        return $this->dispatchLegacy('Content', 'assignment', func_get_args());
    }

    public function studymaterial()
    {
        return $this->dispatchLegacy('Content', 'studymaterial', func_get_args());
    }

    public function syllabus()
    {
        return $this->dispatchLegacy('Content', 'syllabus', func_get_args());
    }

    public function other()
    {
        return $this->dispatchLegacy('Content', 'other', func_get_args());
    }

    protected function dispatchLegacy(string $controller, string $method, array $params)
    {
        // Set up compatibility environment
        $_SERVER['CI4_LEGACY_CONTROLLER'] = $controller;
        $_SERVER['CI4_LEGACY_METHOD'] = $method;
        $_SERVER['CI4_LEGACY_PARAMS'] = $params;

        require_once APPPATH . '../application/core/MY_Controller.php';
        $target = APPPATH . '../application/controllers/user/' . $controller . '.php';
        if (file_exists($target)) {
            require_once $target;
            $instance = new $controller();
            return call_user_func_array([$instance, $method], $params);
        }

        return redirect()->to(base_url('admin/unauthorized'));
    }
}