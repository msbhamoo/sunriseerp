<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * WorkexperienceController
 *
 * Auto-generated CI4 controller migrating CI3 Workexperience.
 */
class WorkexperienceController extends BaseController
{
    public function workexperience()
    {
        return $this->dispatchLegacy('Workexperience', 'workexperience', func_get_args());
    }

    public function workexperienceedit($id)
    {
        return $this->dispatchLegacy('Workexperience', 'workexperienceedit', func_get_args());
    }

    public function workexperiencedelete($id)
    {
        return $this->dispatchLegacy('Workexperience', 'workexperiencedelete', func_get_args());
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