<?php

namespace App\Controllers\Cbseexam;

use App\Controllers\BaseController;

/**
 * CbsecategoryController
 *
 * Auto-generated CI4 controller migrating CI3 Cbsecategory.
 */
class CbsecategoryController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Cbsecategory', 'index', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Cbsecategory', 'delete', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Cbsecategory', 'edit', func_get_args());
    }

    protected function dispatchLegacy(string $controller, string $method, array $params)
    {
        // Set up compatibility environment
        $_SERVER['CI4_LEGACY_CONTROLLER'] = $controller;
        $_SERVER['CI4_LEGACY_METHOD'] = $method;
        $_SERVER['CI4_LEGACY_PARAMS'] = $params;

        require_once APPPATH . '../application/core/MY_Controller.php';
        $target = APPPATH . '../application/controllers/cbseexam/' . $controller . '.php';
        if (file_exists($target)) {
            require_once $target;
            $instance = new $controller();
            return call_user_func_array([$instance, $method], $params);
        }

        return redirect()->to(base_url('admin/unauthorized'));
    }
}