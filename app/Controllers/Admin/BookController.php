<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * BookController
 *
 * Auto-generated CI4 controller migrating CI3 Book.
 */
class BookController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Book', 'index', func_get_args());
    }

    public function getall()
    {
        return $this->dispatchLegacy('Book', 'getall', func_get_args());
    }

    public function create()
    {
        return $this->dispatchLegacy('Book', 'create', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Book', 'edit', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Book', 'delete', func_get_args());
    }

    public function getAvailQuantity()
    {
        return $this->dispatchLegacy('Book', 'getAvailQuantity', func_get_args());
    }

    public function import()
    {
        return $this->dispatchLegacy('Book', 'import', func_get_args());
    }

    public function import_new()
    {
        return $this->dispatchLegacy('Book', 'import_new', func_get_args());
    }

    public function handle_csv_upload()
    {
        return $this->dispatchLegacy('Book', 'handle_csv_upload', func_get_args());
    }

    public function exportformat()
    {
        return $this->dispatchLegacy('Book', 'exportformat', func_get_args());
    }

    public function issue_report()
    {
        return $this->dispatchLegacy('Book', 'issue_report', func_get_args());
    }

    public function issue_returnreport()
    {
        return $this->dispatchLegacy('Book', 'issue_returnreport', func_get_args());
    }

    public function getbooklist()
    {
        return $this->dispatchLegacy('Book', 'getbooklist', func_get_args());
    }

    public function dtbookissuereturnreportlist()
    {
        return $this->dispatchLegacy('Book', 'dtbookissuereturnreportlist', func_get_args());
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