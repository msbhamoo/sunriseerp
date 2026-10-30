<?php

namespace App\Controllers\Cbseexam;

use App\Controllers\BaseController;

/**
 * GradeController
 *
 * Auto-generated CI4 controller migrating CI3 Grade.
 */
class GradeController extends BaseController
{
    public function gradelist()
    {
        return $this->dispatchLegacy('Grade', 'gradelist', func_get_args());
    }

    public function add()
    {
        return $this->dispatchLegacy('Grade', 'add', func_get_args());
    }

    public function get_editdetails()
    {
        return $this->dispatchLegacy('Grade', 'get_editdetails', func_get_args());
    }

    public function add_graderange()
    {
        return $this->dispatchLegacy('Grade', 'add_graderange', func_get_args());
    }

    public function gradeform()
    {
        return $this->dispatchLegacy('Grade', 'gradeform', func_get_args());
    }

    public function remove()
    {
        return $this->dispatchLegacy('Grade', 'remove', func_get_args());
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