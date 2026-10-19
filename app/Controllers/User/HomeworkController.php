<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

/**
 * HomeworkController
 *
 * Auto-generated CI4 controller migrating CI3 Homework.
 */
class HomeworkController extends BaseController
{
    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Homework', 'validateCanUploadFile', func_get_args());
    }

    public function index()
    {
        return $this->dispatchLegacy('Homework', 'index', func_get_args());
    }

    public function upload_docs()
    {
        return $this->dispatchLegacy('Homework', 'upload_docs', func_get_args());
    }

    public function homework_detail($id, $status)
    {
        return $this->dispatchLegacy('Homework', 'homework_detail', func_get_args());
    }

    public function download($id)
    {
        return $this->dispatchLegacy('Homework', 'download', func_get_args());
    }

    public function assigmnetDownload($id)
    {
        return $this->dispatchLegacy('Homework', 'assigmnetDownload', func_get_args());
    }

    public function handle_upload($str, $is_required)
    {
        return $this->dispatchLegacy('Homework', 'handle_upload', func_get_args());
    }

    public function assignment_handle_upload($str, $is_required)
    {
        return $this->dispatchLegacy('Homework', 'assignment_handle_upload', func_get_args());
    }

    public function dailyassignment()
    {
        return $this->dispatchLegacy('Homework', 'dailyassignment', func_get_args());
    }

    public function createdailyassignment()
    {
        return $this->dispatchLegacy('Homework', 'createdailyassignment', func_get_args());
    }

    public function editdailyassignment()
    {
        return $this->dispatchLegacy('Homework', 'editdailyassignment', func_get_args());
    }

    public function updatedailyassignment()
    {
        return $this->dispatchLegacy('Homework', 'updatedailyassignment', func_get_args());
    }

    public function deletedailyassignment($id)
    {
        return $this->dispatchLegacy('Homework', 'deletedailyassignment', func_get_args());
    }

    public function dailyassigmnetdownload($id)
    {
        return $this->dispatchLegacy('Homework', 'dailyassigmnetdownload', func_get_args());
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