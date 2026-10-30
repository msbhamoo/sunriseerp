<?php

namespace App\Controllers;

use App\Controllers\BaseController;

/**
 * AttendencereportsController
 *
 * Auto-generated CI4 controller migrating CI3 Attendencereports.
 */
class AttendencereportsController extends BaseController
{
    public function attendance()
    {
        return $this->dispatchLegacy('Attendencereports', 'attendance', func_get_args());
    }

    public function staffdaywiseattendancereport()
    {
        return $this->dispatchLegacy('Attendencereports', 'staffdaywiseattendancereport', func_get_args());
    }

    public function daywiseattendancereport()
    {
        return $this->dispatchLegacy('Attendencereports', 'daywiseattendancereport', func_get_args());
    }

    public function classattendencereport()
    {
        return $this->dispatchLegacy('Attendencereports', 'classattendencereport', func_get_args());
    }

    public function stuMonthAttendance($st_month, $no_of_months, $student_id)
    {
        return $this->dispatchLegacy('Attendencereports', 'stuMonthAttendance', func_get_args());
    }

    public function attendancereport()
    {
        return $this->dispatchLegacy('Attendencereports', 'attendancereport', func_get_args());
    }

    public function daily_attendance_report()
    {
        return $this->dispatchLegacy('Attendencereports', 'daily_attendance_report', func_get_args());
    }

    public function staffattendancereport()
    {
        return $this->dispatchLegacy('Attendencereports', 'staffattendancereport', func_get_args());
    }

    public function monthAttendance($st_month, $no_of_months, $emp)
    {
        return $this->dispatchLegacy('Attendencereports', 'monthAttendance', func_get_args());
    }

    public function biometric_attlog($offset = 0)
    {
        return $this->dispatchLegacy('Attendencereports', 'biometric_attlog', func_get_args());
    }

    public function reportbymonthstudent()
    {
        return $this->dispatchLegacy('Attendencereports', 'reportbymonthstudent', func_get_args());
    }

    public function reportbymonth()
    {
        return $this->dispatchLegacy('Attendencereports', 'reportbymonth', func_get_args());
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