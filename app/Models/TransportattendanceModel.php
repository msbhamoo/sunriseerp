<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * TransportattendanceModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Transportattendance_model.
 */
class TransportattendanceModel extends BaseModel
{
    protected $table = 'transportattendance';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function search_student_for_transport($search)
    {
        return $this->callLegacyModel('Transportattendance_model', 'search_student_for_transport', func_get_args());
    }

    public function get_bus_students($vehicle_id, $session_id = null, $route_id = null)
    {
        return $this->callLegacyModel('Transportattendance_model', 'get_bus_students', func_get_args());
    }

    public function get_attendance($vehicle_id, $date, $attendance_type)
    {
        return $this->callLegacyModel('Transportattendance_model', 'get_attendance', func_get_args());
    }

    public function get_custom_riders($vehicle_id, $date, $attendance_type)
    {
        return $this->callLegacyModel('Transportattendance_model', 'get_custom_riders', func_get_args());
    }

    public function get_switched_out_students_map($current_vehicle_id, $date, $attendance_type)
    {
        return $this->callLegacyModel('Transportattendance_model', 'get_switched_out_students_map', func_get_args());
    }

    public function save_attendance($data)
    {
        return $this->callLegacyModel('Transportattendance_model', 'save_attendance', func_get_args());
    }

    public function check_transport_presence($date, $attendance_type)
    {
        return $this->callLegacyModel('Transportattendance_model', 'check_transport_presence', func_get_args());
    }

    public function get_daily_summary($date)
    {
        return $this->callLegacyModel('Transportattendance_model', 'get_daily_summary', func_get_args());
    }

    public function get_monthly_summary($month, $year)
    {
        return $this->callLegacyModel('Transportattendance_model', 'get_monthly_summary', func_get_args());
    }

    public function get_attendance_detail($vehicle_id, $date, $route_id = null)
    {
        return $this->callLegacyModel('Transportattendance_model', 'get_attendance_detail', func_get_args());
    }

    public function get_monthly_attendance_detail($vehicle_id, $month, $year, $route_id = null)
    {
        return $this->callLegacyModel('Transportattendance_model', 'get_monthly_attendance_detail', func_get_args());
    }

    /**
     * Compatibility Bridge: Delegates execution to the legacy model instance
     * maintaining full compatibility with query builder and CI3 environment.
     */
    protected function callLegacyModel(string $modelClass, string $method, array $args)
    {
        static $instances = [];

        if (!isset($instances[$modelClass])) {
            require_once APPPATH . '../application/core/MY_Model.php';
            $file = APPPATH . '../application/models/' . ($this->subPath() ? $this->subPath() . '/' : '') . $modelClass . '.php';
            if (file_exists($file)) {
                require_once $file;
                $instances[$modelClass] = new $modelClass();
            } else {
                return null;
            }
        }

        return call_user_func_array([$instances[$modelClass], $method], $args);
    }

    protected function subPath(): string
    {
        return '';
    }
}