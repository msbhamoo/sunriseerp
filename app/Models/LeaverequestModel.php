<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * LeaverequestModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Leaverequest_model.
 */
class LeaverequestModel extends BaseModel
{
    protected $table = 'leaverequest';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function staff_leave_request($id = null)
    {
        return $this->callLegacyModel('Leaverequest_model', 'staff_leave_request', func_get_args());
    }

    public function user_leave_request($id = null)
    {
        return $this->callLegacyModel('Leaverequest_model', 'user_leave_request', func_get_args());
    }

    public function allotedLeaveType($id)
    {
        return $this->callLegacyModel('Leaverequest_model', 'allotedLeaveType', func_get_args());
    }

    public function myallotedLeaveType($id, $leave_type_id)
    {
        return $this->callLegacyModel('Leaverequest_model', 'myallotedLeaveType', func_get_args());
    }

    public function countLeavesData($staff_id, $leave_type_id)
    {
        return $this->callLegacyModel('Leaverequest_model', 'countLeavesData', func_get_args());
    }

    public function changeLeaveStatus($data, $staff_id)
    {
        return $this->callLegacyModel('Leaverequest_model', 'changeLeaveStatus', func_get_args());
    }

    public function syncLeaveAttendance($request_id)
    {
        return $this->callLegacyModel('Leaverequest_model', 'syncLeaveAttendance', func_get_args());
    }

    public function getLeaveSummary()
    {
        return $this->callLegacyModel('Leaverequest_model', 'getLeaveSummary', func_get_args());
    }

    public function leave_remove($id)
    {
        return $this->callLegacyModel('Leaverequest_model', 'leave_remove', func_get_args());
    }

    public function addLeaveRequest($data)
    {
        return $this->callLegacyModel('Leaverequest_model', 'addLeaveRequest', func_get_args());
    }

    public function get_staff_leave($id)
    {
        return $this->callLegacyModel('Leaverequest_model', 'get_staff_leave', func_get_args());
    }

    public function getleaverequestreport($condition)
    {
        return $this->callLegacyModel('Leaverequest_model', 'getleaverequestreport', func_get_args());
    }

    public function getmyleaverequestreport($condition)
    {
        return $this->callLegacyModel('Leaverequest_model', 'getmyleaverequestreport', func_get_args());
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