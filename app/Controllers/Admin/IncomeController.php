<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * IncomeController
 *
 * Auto-generated CI4 controller migrating CI3 Income.
 */
class IncomeController extends BaseController
{
    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Income', 'validateCanUploadFile', func_get_args());
    }

    public function index()
    {
        return $this->dispatchLegacy('Income', 'index', func_get_args());
    }

    public function download($id)
    {
        return $this->dispatchLegacy('Income', 'download', func_get_args());
    }

    public function view($id)
    {
        return $this->dispatchLegacy('Income', 'view', func_get_args());
    }

    public function getByFeecategory()
    {
        return $this->dispatchLegacy('Income', 'getByFeecategory', func_get_args());
    }

    public function getStudentCategoryFee()
    {
        return $this->dispatchLegacy('Income', 'getStudentCategoryFee', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Income', 'delete', func_get_args());
    }

    public function create()
    {
        return $this->dispatchLegacy('Income', 'create', func_get_args());
    }

    public function handle_upload()
    {
        return $this->dispatchLegacy('Income', 'handle_upload', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Income', 'edit', func_get_args());
    }

    public function incomeSearch()
    {
        return $this->dispatchLegacy('Income', 'incomeSearch', func_get_args());
    }

    public function getincomelist()
    {
        return $this->dispatchLegacy('Income', 'getincomelist', func_get_args());
    }

    public function checkvalidation()
    {
        return $this->dispatchLegacy('Income', 'checkvalidation', func_get_args());
    }

    public function getincomesearchlist()
    {
        return $this->dispatchLegacy('Income', 'getincomesearchlist', func_get_args());
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