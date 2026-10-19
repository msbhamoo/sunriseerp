<?php

namespace App\Controllers;

use App\Controllers\BaseController;

/**
 * FeeimportController
 *
 * Auto-generated CI4 controller migrating CI3 Feeimport.
 */
class FeeimportController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Feeimport', 'index', func_get_args());
    }

    public function download_template()
    {
        return $this->dispatchLegacy('Feeimport', 'download_template', func_get_args());
    }

    public function upload()
    {
        return $this->dispatchLegacy('Feeimport', 'upload', func_get_args());
    }

    public function preview($batch_id)
    {
        return $this->dispatchLegacy('Feeimport', 'preview', func_get_args());
    }

    public function execute($batch_id)
    {
        return $this->dispatchLegacy('Feeimport', 'execute', func_get_args());
    }

    public function batch_detail($batch_id)
    {
        return $this->dispatchLegacy('Feeimport', 'batch_detail', func_get_args());
    }

    public function revert($batch_id)
    {
        return $this->dispatchLegacy('Feeimport', 'revert', func_get_args());
    }

    public function fix_dates($batch_id)
    {
        return $this->dispatchLegacy('Feeimport', 'fix_dates', func_get_args());
    }

    protected function dispatchLegacy(string $controller, string $method, array $params)
    {
        // Set up compatibility environment
        $_SERVER['CI4_LEGACY_CONTROLLER'] = $controller;
        $_SERVER['CI4_LEGACY_METHOD'] = $method;
        $_SERVER['CI4_LEGACY_PARAMS'] = $params;

        require_once APPPATH . '../application/core/MY_Controller.php';
        $target = APPPATH . '../application/controllers/' . $controller . '.php';
        if (file_exists($target)) {
            require_once $target;
            $instance = new $controller();
            return call_user_func_array([$instance, $method], $params);
        }

        return redirect()->to(base_url('admin/unauthorized'));
    }
}