<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * StaffattendanceController
 *
 * Auto-generated CI4 controller migrating CI3 Staffattendance.
 */
class StaffattendanceController extends BaseController
{
    public function index_old()
    {
        return $this->dispatchLegacy('Staffattendance', 'index_old', func_get_args());
    }

    public function index()
    {
        return $this->dispatchLegacy('Staffattendance', 'index', func_get_args());
    }

    public function monthAttendance($st_month, $no_of_months, $emp)
    {
        return $this->dispatchLegacy('Staffattendance', 'monthAttendance', func_get_args());
    }

    public function profileattendance()
    {
        return $this->dispatchLegacy('Staffattendance', 'profileattendance', func_get_args());
    }

    public function monthsheet()
    {
        return $this->dispatchLegacy('Staffattendance', 'monthsheet', func_get_args());
    }

    public function register()
    {
        return $this->dispatchLegacy('Staffattendance', 'register', func_get_args());
    }

    public function dayoverrides()
    {
        return $this->dispatchLegacy('Staffattendance', 'dayoverrides', func_get_args());
    }

    public function savedayoverride()
    {
        return $this->dispatchLegacy('Staffattendance', 'savedayoverride', func_get_args());
    }

    public function deletedayoverride()
    {
        return $this->dispatchLegacy('Staffattendance', 'deletedayoverride', func_get_args());
    }

    public function dbupdate()
    {
        return $this->dispatchLegacy('Staffattendance', 'dbupdate', func_get_args());
    }

    public function auditlog()
    {
        return $this->dispatchLegacy('Staffattendance', 'auditlog', func_get_args());
    }

    public function qrsettings()
    {
        return $this->dispatchLegacy('Staffattendance', 'qrsettings', func_get_args());
    }

    public function qrdisplay()
    {
        return $this->dispatchLegacy('Staffattendance', 'qrdisplay', func_get_args());
    }

    public function qrimage()
    {
        return $this->dispatchLegacy('Staffattendance', 'qrimage', func_get_args());
    }

    public function scan()
    {
        return $this->dispatchLegacy('Staffattendance', 'scan', func_get_args());
    }

    public function requestcorrection()
    {
        return $this->dispatchLegacy('Staffattendance', 'requestcorrection', func_get_args());
    }

    public function cancelcorrection()
    {
        return $this->dispatchLegacy('Staffattendance', 'cancelcorrection', func_get_args());
    }

    public function corrections()
    {
        return $this->dispatchLegacy('Staffattendance', 'corrections', func_get_args());
    }

    public function reviewcorrection()
    {
        return $this->dispatchLegacy('Staffattendance', 'reviewcorrection', func_get_args());
    }

    public function markqr()
    {
        return $this->dispatchLegacy('Staffattendance', 'markqr', func_get_args());
    }

    public function biometricsettings()
    {
        return $this->dispatchLegacy('Staffattendance', 'biometricsettings', func_get_args());
    }

    public function test_biometric_connection_ajax()
    {
        return $this->dispatchLegacy('Staffattendance', 'test_biometric_connection_ajax', func_get_args());
    }

    public function sync_biometric_ajax()
    {
        return $this->dispatchLegacy('Staffattendance', 'sync_biometric_ajax', func_get_args());
    }

    public function save_staff_biometric_id_ajax()
    {
        return $this->dispatchLegacy('Staffattendance', 'save_staff_biometric_id_ajax', func_get_args());
    }

    public function sync_biometric_cron()
    {
        return $this->dispatchLegacy('Staffattendance', 'sync_biometric_cron', func_get_args());
    }

    public function export_biometric_template()
    {
        return $this->dispatchLegacy('Staffattendance', 'export_biometric_template', func_get_args());
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