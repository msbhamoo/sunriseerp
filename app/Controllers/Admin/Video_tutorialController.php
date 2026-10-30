<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * Video_tutorialController
 *
 * Auto-generated CI4 controller migrating CI3 Video_tutorial.
 */
class Video_tutorialController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Video_tutorial', 'index', func_get_args());
    }

    public function add()
    {
        return $this->dispatchLegacy('Video_tutorial', 'add', func_get_args());
    }

    public function get()
    {
        return $this->dispatchLegacy('Video_tutorial', 'get', func_get_args());
    }

    public function edit()
    {
        return $this->dispatchLegacy('Video_tutorial', 'edit', func_get_args());
    }

    public function getPage($page)
    {
        return $this->dispatchLegacy('Video_tutorial', 'getPage', func_get_args());
    }

    public function genratediv($result)
    {
        return $this->dispatchLegacy('Video_tutorial', 'genratediv', func_get_args());
    }

    public function delete()
    {
        return $this->dispatchLegacy('Video_tutorial', 'delete', func_get_args());
    }

    public function searchvalidation()
    {
        return $this->dispatchLegacy('Video_tutorial', 'searchvalidation', func_get_args());
    }

    public function getsection()
    {
        return $this->dispatchLegacy('Video_tutorial', 'getsection', func_get_args());
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