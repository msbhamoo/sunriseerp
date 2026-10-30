<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * ExpenseController
 *
 * Auto-generated CI4 controller migrating CI3 Expense.
 */
class ExpenseController extends BaseController
{
    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Expense', 'validateCanUploadFile', func_get_args());
    }

    public function index()
    {
        return $this->dispatchLegacy('Expense', 'index', func_get_args());
    }

    public function download($id)
    {
        return $this->dispatchLegacy('Expense', 'download', func_get_args());
    }

    public function handle_upload()
    {
        return $this->dispatchLegacy('Expense', 'handle_upload', func_get_args());
    }

    public function view($id)
    {
        return $this->dispatchLegacy('Expense', 'view', func_get_args());
    }

    public function getByFeecategory()
    {
        return $this->dispatchLegacy('Expense', 'getByFeecategory', func_get_args());
    }

    public function getStudentCategoryFee()
    {
        return $this->dispatchLegacy('Expense', 'getStudentCategoryFee', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Expense', 'delete', func_get_args());
    }

    public function create()
    {
        return $this->dispatchLegacy('Expense', 'create', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Expense', 'edit', func_get_args());
    }

    public function expenseSearch()
    {
        return $this->dispatchLegacy('Expense', 'expenseSearch', func_get_args());
    }

    public function getexpenselist()
    {
        return $this->dispatchLegacy('Expense', 'getexpenselist', func_get_args());
    }

    public function search()
    {
        return $this->dispatchLegacy('Expense', 'search', func_get_args());
    }

    public function getsearchexpenselist()
    {
        return $this->dispatchLegacy('Expense', 'getsearchexpenselist', func_get_args());
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