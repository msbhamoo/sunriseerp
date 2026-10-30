<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * SubjectController
 *
 * Auto-generated CI4 controller migrating CI3 Subject.
 */
class SubjectController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Subject', 'index', func_get_args());
    }

    public function view($id)
    {
        return $this->dispatchLegacy('Subject', 'view', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Subject', 'delete', func_get_args());
    }

    public function _check_name_exists()
    {
        return $this->dispatchLegacy('Subject', '_check_name_exists', func_get_args());
    }

    public function _check_code_exists()
    {
        return $this->dispatchLegacy('Subject', '_check_code_exists', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Subject', 'edit', func_get_args());
    }

    public function getSubjctByClassandSection()
    {
        return $this->dispatchLegacy('Subject', 'getSubjctByClassandSection', func_get_args());
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