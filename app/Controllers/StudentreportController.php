<?php

namespace App\Controllers;

use App\Controllers\BaseController;

/**
 * StudentreportController
 *
 * Auto-generated CI4 controller migrating CI3 Studentreport.
 */
class StudentreportController extends BaseController
{
    public function agewise()
    {
        return $this->dispatchLegacy('Studentreport', 'agewise', func_get_args());
    }

    public function genderwise()
    {
        return $this->dispatchLegacy('Studentreport', 'genderwise', func_get_args());
    }

    public function categorywise()
    {
        return $this->dispatchLegacy('Studentreport', 'categorywise', func_get_args());
    }

    public function religionwise()
    {
        return $this->dispatchLegacy('Studentreport', 'religionwise', func_get_args());
    }

    public function classwise()
    {
        return $this->dispatchLegacy('Studentreport', 'classwise', func_get_args());
    }

    public function rtewise()
    {
        return $this->dispatchLegacy('Studentreport', 'rtewise', func_get_args());
    }

    public function birthdays()
    {
        return $this->dispatchLegacy('Studentreport', 'birthdays', func_get_args());
    }

    public function newadmissions()
    {
        return $this->dispatchLegacy('Studentreport', 'newadmissions', func_get_args());
    }

    public function totalstudents()
    {
        return $this->dispatchLegacy('Studentreport', 'totalstudents', func_get_args());
    }

    public function custom_report()
    {
        return $this->dispatchLegacy('Studentreport', 'custom_report', func_get_args());
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