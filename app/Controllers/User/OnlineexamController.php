<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

/**
 * OnlineexamController
 *
 * Auto-generated CI4 controller migrating CI3 Onlineexam.
 */
class OnlineexamController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Onlineexam', 'index', func_get_args());
    }

    public function view($id)
    {
        return $this->dispatchLegacy('Onlineexam', 'view', func_get_args());
    }

    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Onlineexam', 'validateCanUploadFile', func_get_args());
    }

    public function check_storage()
    {
        return $this->dispatchLegacy('Onlineexam', 'check_storage', func_get_args());
    }

    public function save()
    {
        return $this->dispatchLegacy('Onlineexam', 'save', func_get_args());
    }

    public function getExamForm()
    {
        return $this->dispatchLegacy('Onlineexam', 'getExamForm', func_get_args());
    }

    public function downloadattachment($doc)
    {
        return $this->dispatchLegacy('Onlineexam', 'downloadattachment', func_get_args());
    }

    public function getexamlist()
    {
        return $this->dispatchLegacy('Onlineexam', 'getexamlist', func_get_args());
    }

    public function getclosedexamlist()
    {
        return $this->dispatchLegacy('Onlineexam', 'getclosedexamlist', func_get_args());
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