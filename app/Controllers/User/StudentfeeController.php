<?php

namespace App\Controllers\User;

use App\Controllers\BaseController;

/**
 * StudentfeeController
 *
 * Auto-generated CI4 controller migrating CI3 Studentfee.
 */
class StudentfeeController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Studentfee', 'index', func_get_args());
    }

    public function pdf()
    {
        return $this->dispatchLegacy('Studentfee', 'pdf', func_get_args());
    }

    public function search()
    {
        return $this->dispatchLegacy('Studentfee', 'search', func_get_args());
    }

    public function feesearch()
    {
        return $this->dispatchLegacy('Studentfee', 'feesearch', func_get_args());
    }

    public function reportbyname()
    {
        return $this->dispatchLegacy('Studentfee', 'reportbyname', func_get_args());
    }

    public function reportbyclass()
    {
        return $this->dispatchLegacy('Studentfee', 'reportbyclass', func_get_args());
    }

    public function view($id)
    {
        return $this->dispatchLegacy('Studentfee', 'view', func_get_args());
    }

    public function deleteFee()
    {
        return $this->dispatchLegacy('Studentfee', 'deleteFee', func_get_args());
    }

    public function addfee($id)
    {
        return $this->dispatchLegacy('Studentfee', 'addfee', func_get_args());
    }

    public function deleteTransportFee()
    {
        return $this->dispatchLegacy('Studentfee', 'deleteTransportFee', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Studentfee', 'delete', func_get_args());
    }

    public function create()
    {
        return $this->dispatchLegacy('Studentfee', 'create', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Studentfee', 'edit', func_get_args());
    }

    public function add_Ajaxfee()
    {
        return $this->dispatchLegacy('Studentfee', 'add_Ajaxfee', func_get_args());
    }

    public function add_AjaxTransportfee()
    {
        return $this->dispatchLegacy('Studentfee', 'add_AjaxTransportfee', func_get_args());
    }

    public function printFeesByName()
    {
        return $this->dispatchLegacy('Studentfee', 'printFeesByName', func_get_args());
    }

    public function searchpayment()
    {
        return $this->dispatchLegacy('Studentfee', 'searchpayment', func_get_args());
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