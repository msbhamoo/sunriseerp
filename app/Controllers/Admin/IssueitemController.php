<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * IssueitemController
 *
 * Auto-generated CI4 controller migrating CI3 Issueitem.
 */
class IssueitemController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Issueitem', 'index', func_get_args());
    }

    public function create()
    {
        return $this->dispatchLegacy('Issueitem', 'create', func_get_args());
    }

    public function add()
    {
        return $this->dispatchLegacy('Issueitem', 'add', func_get_args());
    }

    public function check_available_quantity()
    {
        return $this->dispatchLegacy('Issueitem', 'check_available_quantity', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Issueitem', 'delete', func_get_args());
    }

    public function getUser()
    {
        return $this->dispatchLegacy('Issueitem', 'getUser', func_get_args());
    }

    public function returnItem()
    {
        return $this->dispatchLegacy('Issueitem', 'returnItem', func_get_args());
    }

    public function getitemlist()
    {
        return $this->dispatchLegacy('Issueitem', 'getitemlist', func_get_args());
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