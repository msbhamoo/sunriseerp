<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * CustomfieldController
 *
 * Auto-generated CI4 controller migrating CI3 Customfield.
 */
class CustomfieldController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Customfield', 'index', func_get_args());
    }

    public function validate_type()
    {
        return $this->dispatchLegacy('Customfield', 'validate_type', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Customfield', 'edit', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Customfield', 'delete', func_get_args());
    }

    public function updateorder()
    {
        return $this->dispatchLegacy('Customfield', 'updateorder', func_get_args());
    }

    public function myCustomFieldBundle($customfield_values)
    {
        return $this->dispatchLegacy('Customfield', 'myCustomFieldBundle', func_get_args());
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