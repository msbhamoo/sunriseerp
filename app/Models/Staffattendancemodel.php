<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * Staffattendancemodel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Staffattendancemodel.
 */
class Staffattendancemodel extends BaseModel
{
    protected $table = 'staffattendance';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function shiftIdForStaff($staff_id, $date)
    {
        return $this->callLegacyModel('Staffattendancemodel', 'shiftIdForStaff', func_get_args());
    }

    public function addorUpdate($attendances)
    {
        return $this->callLegacyModel('Staffattendancemodel', 'addorUpdate', func_get_args());
    }

    public function mergeSyncRemark($existing, $incoming)
    {
        return $this->callLegacyModel('Staffattendancemodel', 'mergeSyncRemark', func_get_args());
    }

    public function saveBiometricAttendanceBatch($attendances)
    {
        return $this->callLegacyModel('Staffattendancemodel', 'saveBiometricAttendanceBatch', func_get_args());
    }

    public function get($id = null)
    {
        return $this->callLegacyModel('Staffattendancemodel', 'get', func_get_args());
    }

    public function getUserType()
    {
        return $this->callLegacyModel('Staffattendancemodel', 'getUserType', func_get_args());
    }

    public function searchAttendenceUserTypeWithMode($user_type, $date,$mode)
    {
        return $this->callLegacyModel('Staffattendancemodel', 'searchAttendenceUserTypeWithMode', func_get_args());
    }

    public function searchAttendenceUserType($user_type, $date)
    {
        return $this->callLegacyModel('Staffattendancemodel', 'searchAttendenceUserType', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Staffattendancemodel', 'add', func_get_args());
    }

    public function getStaffAttendanceType()
    {
        return $this->callLegacyModel('Staffattendancemodel', 'getStaffAttendanceType', func_get_args());
    }

    public function searchAttendanceReport($user_type, $date)
    {
        return $this->callLegacyModel('Staffattendancemodel', 'searchAttendanceReport', func_get_args());
    }

    public function attendanceYearCount()
    {
        return $this->callLegacyModel('Staffattendancemodel', 'attendanceYearCount', func_get_args());
    }

    public function searchStaffattendance($date, $staff_id, $active_staff = true)
    {
        return $this->callLegacyModel('Staffattendancemodel', 'searchStaffattendance', func_get_args());
    }

    public function onlineattendence($data, $role_id)
    {
        return $this->callLegacyModel('Staffattendancemodel', 'onlineattendence', func_get_args());
    }

    public function staff_schedule_hours($staff_id, $in_time, $shift_id = null)
    {
        return $this->callLegacyModel('Staffattendancemodel', 'staff_schedule_hours', func_get_args());
    }

    public function scheduleEarliestOut($role_id, $in_time, $shift_id = null)
    {
        return $this->callLegacyModel('Staffattendancemodel', 'scheduleEarliestOut', func_get_args());
    }

    public function getAttendanceTypeName($type_id)
    {
        return $this->callLegacyModel('Staffattendancemodel', 'getAttendanceTypeName', func_get_args());
    }

    public function qrMark($staff_id, $role_id, $opts = array())
    {
        return $this->callLegacyModel('Staffattendancemodel', 'qrMark', func_get_args());
    }

    public function qrDirectOut($staff_id, $role_id, $opts = array())
    {
        return $this->callLegacyModel('Staffattendancemodel', 'qrDirectOut', func_get_args());
    }

    public function getQrState($staff_id)
    {
        return $this->callLegacyModel('Staffattendancemodel', 'getQrState', func_get_args());
    }

    public function qrBreakOut($staff_id, $reason = '')
    {
        return $this->callLegacyModel('Staffattendancemodel', 'qrBreakOut', func_get_args());
    }

    public function qrBreakIn($staff_id)
    {
        return $this->callLegacyModel('Staffattendancemodel', 'qrBreakIn', func_get_args());
    }

    public function getMonthlySheet($role_name, $year, $month)
    {
        return $this->callLegacyModel('Staffattendancemodel', 'getMonthlySheet', func_get_args());
    }

    public function getMonthHolidayDates($year, $month)
    {
        return $this->callLegacyModel('Staffattendancemodel', 'getMonthHolidayDates', func_get_args());
    }

    public function getOffDays($start, $end)
    {
        return $this->callLegacyModel('Staffattendancemodel', 'getOffDays', func_get_args());
    }

    public function getStaffRecentAttendance($staff_id, $period = 7)
    {
        return $this->callLegacyModel('Staffattendancemodel', 'getStaffRecentAttendance', func_get_args());
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