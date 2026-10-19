<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * BatchsubjectController
 *
 * Auto-generated CI4 controller migrating CI3 Batchsubject.
 */
class BatchsubjectController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Batchsubject', 'index', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Batchsubject', 'delete', func_get_args());
    }

    public function deletegrp($id)
    {
        return $this->dispatchLegacy('Batchsubject', 'deletegrp', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Batchsubject', 'edit', func_get_args());
    }

    public function getSectionByClass()
    {
        return $this->dispatchLegacy('Batchsubject', 'getSectionByClass', func_get_args());
    }

    public function getBatchByClassSection()
    {
        return $this->dispatchLegacy('Batchsubject', 'getBatchByClassSection', func_get_args());
    }

    public function getBatchByClass()
    {
        return $this->dispatchLegacy('Batchsubject', 'getBatchByClass', func_get_args());
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