<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * HostelattendanceModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Hostelattendance_model.
 */
class HostelattendanceModel extends BaseModel
{
    protected $table = 'hostelattendance';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get_hostel_students($hostel_id)
    {
        return $this->callLegacyModel('Hostelattendance_model', 'get_hostel_students', func_get_args());
    }

    public function get_attendance($hostel_id, $date, $roll_call_type = 'morning')
    {
        return $this->callLegacyModel('Hostelattendance_model', 'get_attendance', func_get_args());
    }

    public function save($data)
    {
        return $this->callLegacyModel('Hostelattendance_model', 'save', func_get_args());
    }

    public function get_monthly_attendance($hostel_id, $month, $year, $roll_call_type = 'morning')
    {
        return $this->callLegacyModel('Hostelattendance_model', 'get_monthly_attendance', func_get_args());
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