<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * StaffAttendaceSettingModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy StaffAttendaceSetting_model.
 */
class StaffAttendaceSettingModel extends BaseModel
{
    protected $table = 'staffattendacesetting';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function getShifts()
    {
        return $this->callLegacyModel('StaffAttendaceSetting_model', 'getShifts', func_get_args());
    }

    public function getShift($shift_id)
    {
        return $this->callLegacyModel('StaffAttendaceSetting_model', 'getShift', func_get_args());
    }

    public function getShiftRoles($shift_id)
    {
        return $this->callLegacyModel('StaffAttendaceSetting_model', 'getShiftRoles', func_get_args());
    }

    public function getActiveRegularShiftId()
    {
        return $this->callLegacyModel('StaffAttendaceSetting_model', 'getActiveRegularShiftId', func_get_args());
    }

    public function getShiftEditorRows($shift_id = null)
    {
        return $this->callLegacyModel('StaffAttendaceSetting_model', 'getShiftEditorRows', func_get_args());
    }

    public function checkActivation($roles, $shift_type, $from = null, $to = null, $exclude_id = null)
    {
        return $this->callLegacyModel('StaffAttendaceSetting_model', 'checkActivation', func_get_args());
    }

    public function saveShift($shift_id, $meta, $bands)
    {
        return $this->callLegacyModel('StaffAttendaceSetting_model', 'saveShift', func_get_args());
    }

    public function isDayLocked($date)
    {
        return $this->callLegacyModel('StaffAttendaceSetting_model', 'isDayLocked', func_get_args());
    }

    public function shiftCoversDate($shift, $date)
    {
        return $this->callLegacyModel('StaffAttendaceSetting_model', 'shiftCoversDate', func_get_args());
    }

    public function setShiftActive($shift_id, $active)
    {
        return $this->callLegacyModel('StaffAttendaceSetting_model', 'setShiftActive', func_get_args());
    }

    public function deleteShift($shift_id)
    {
        return $this->callLegacyModel('StaffAttendaceSetting_model', 'deleteShift', func_get_args());
    }

    public function resolveShiftMap($date = null)
    {
        return $this->callLegacyModel('StaffAttendaceSetting_model', 'resolveShiftMap', func_get_args());
    }

    public function resolveShiftId($role_id, $date = null)
    {
        return $this->callLegacyModel('StaffAttendaceSetting_model', 'resolveShiftId', func_get_args());
    }

    public function getRoleShiftBands($role_id, $date = null, $shift_id = null)
    {
        return $this->callLegacyModel('StaffAttendaceSetting_model', 'getRoleShiftBands', func_get_args());
    }

    public function getRoleWiseAttendanceSetting($role_id, $date = null)
    {
        return $this->callLegacyModel('StaffAttendaceSetting_model', 'getRoleWiseAttendanceSetting', func_get_args());
    }

    public function getAttendanceTypeByRole($role_id, $time, $date = null, $shift_id = null)
    {
        return $this->callLegacyModel('StaffAttendaceSetting_model', 'getAttendanceTypeByRole', func_get_args());
    }

    public function getLatePolicy()
    {
        return $this->callLegacyModel('StaffAttendaceSetting_model', 'getLatePolicy', func_get_args());
    }

    public function saveLatePolicy($enabled, $free, $every, $penalty, $user_id)
    {
        return $this->callLegacyModel('StaffAttendaceSetting_model', 'saveLatePolicy', func_get_args());
    }

    public function lateDeduction($lates, $policy = null)
    {
        return $this->callLegacyModel('StaffAttendaceSetting_model', 'lateDeduction', func_get_args());
    }

    public function latePolicyText($policy = null)
    {
        return $this->callLegacyModel('StaffAttendaceSetting_model', 'latePolicyText', func_get_args());
    }

    public function getAttendanceTypeIdByKey($key)
    {
        return $this->callLegacyModel('StaffAttendaceSetting_model', 'getAttendanceTypeIdByKey', func_get_args());
    }

    public function applyEarlyHalfRule($type_id, $out_time, $role_id, $date = null, $shift_id = null)
    {
        return $this->callLegacyModel('StaffAttendaceSetting_model', 'applyEarlyHalfRule', func_get_args());
    }

    public function statusForTimes($role_id, $date, $shift_id, $in_time, $out_time)
    {
        return $this->callLegacyModel('StaffAttendaceSetting_model', 'statusForTimes', func_get_args());
    }

    public function recalculateRange($roles, $from, $to)
    {
        return $this->callLegacyModel('StaffAttendaceSetting_model', 'recalculateRange', func_get_args());
    }

    public function getAllRoleRequiredHours($date = null)
    {
        return $this->callLegacyModel('StaffAttendaceSetting_model', 'getAllRoleRequiredHours', func_get_args());
    }

    public function getAllRoleSchedulesMap($date = null)
    {
        return $this->callLegacyModel('StaffAttendaceSetting_model', 'getAllRoleSchedulesMap', func_get_args());
    }

    public function getSchedulesMapByShifts($shift_ids)
    {
        return $this->callLegacyModel('StaffAttendaceSetting_model', 'getSchedulesMapByShifts', func_get_args());
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