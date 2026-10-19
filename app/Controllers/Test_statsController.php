<?php

namespace App\Controllers;

use App\Controllers\BaseController;

/**
 * Test_statsController
 *
 * Auto-generated CI4 controller migrating CI3 Test_stats.
 */
class Test_statsController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Test_stats', 'index', func_get_args());
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