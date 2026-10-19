<?php

namespace App\Controllers\Cbseexam;

use App\Controllers\BaseController;

/**
 * SeatingreportController
 *
 * Auto-generated CI4 controller migrating CI3 Seatingreport.
 */
class SeatingreportController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Seatingreport', 'index', func_get_args());
    }

    public function roomwise($allocation_id)
    {
        return $this->dispatchLegacy('Seatingreport', 'roomwise', func_get_args());
    }

    public function studentwise($allocation_id)
    {
        return $this->dispatchLegacy('Seatingreport', 'studentwise', func_get_args());
    }

    public function invigilator_duty($allocation_id)
    {
        return $this->dispatchLegacy('Seatingreport', 'invigilator_duty', func_get_args());
    }

    public function attendance_sheet($allocation_id)
    {
        return $this->dispatchLegacy('Seatingreport', 'attendance_sheet', func_get_args());
    }

    public function summary_report($allocation_id)
    {
        return $this->dispatchLegacy('Seatingreport', 'summary_report', func_get_args());
    }

    public function ajax_swap_seats()
    {
        return $this->dispatchLegacy('Seatingreport', 'ajax_swap_seats', func_get_args());
    }

    public function ajax_move_students()
    {
        return $this->dispatchLegacy('Seatingreport', 'ajax_move_students', func_get_args());
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