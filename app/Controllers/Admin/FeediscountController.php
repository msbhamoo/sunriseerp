<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * FeediscountController
 *
 * Auto-generated CI4 controller migrating CI3 Feediscount.
 */
class FeediscountController extends BaseController
{
    public function delete($id)
    {
        return $this->dispatchLegacy('Feediscount', 'delete', func_get_args());
    }

    public function index()
    {
        return $this->dispatchLegacy('Feediscount', 'index', func_get_args());
    }

    public function check_number($userName)
    {
        return $this->dispatchLegacy('Feediscount', 'check_number', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Feediscount', 'edit', func_get_args());
    }

    public function assign($id)
    {
        return $this->dispatchLegacy('Feediscount', 'assign', func_get_args());
    }

    public function studentdiscount()
    {
        return $this->dispatchLegacy('Feediscount', 'studentdiscount', func_get_args());
    }

    public function applydiscount()
    {
        return $this->dispatchLegacy('Feediscount', 'applydiscount', func_get_args());
    }

    public function approvalQueue()
    {
        return $this->dispatchLegacy('Feediscount', 'approvalQueue', func_get_args());
    }

    public function approveRequest($id)
    {
        return $this->dispatchLegacy('Feediscount', 'approveRequest', func_get_args());
    }

    public function rejectRequest()
    {
        return $this->dispatchLegacy('Feediscount', 'rejectRequest', func_get_args());
    }

    public function ajax_search_students()
    {
        return $this->dispatchLegacy('Feediscount', 'ajax_search_students', func_get_args());
    }

    public function get_student_fee_summary()
    {
        return $this->dispatchLegacy('Feediscount', 'get_student_fee_summary', func_get_args());
    }

    public function apply_direct_discount()
    {
        return $this->dispatchLegacy('Feediscount', 'apply_direct_discount', func_get_args());
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