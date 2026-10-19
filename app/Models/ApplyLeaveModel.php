<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * ApplyLeaveModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Apply_leave_model.
 */
class ApplyLeaveModel extends BaseModel
{
    protected $table = 'apply_leave';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($id = null, $carray = null, $section_array = null)
    {
        return $this->callLegacyModel('Apply_leave_model', 'get', func_get_args());
    }

    public function get_student($student_session_id = null)
    {
        return $this->callLegacyModel('Apply_leave_model', 'get_student', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Apply_leave_model', 'add', func_get_args());
    }

    public function get_studentsessionId($class_id, $section_id, $student_id)
    {
        return $this->callLegacyModel('Apply_leave_model', 'get_studentsessionId', func_get_args());
    }

    public function remove_leave($id)
    {
        return $this->callLegacyModel('Apply_leave_model', 'remove_leave', func_get_args());
    }

    public function canApproveLeave($staff_id, $class_id, $section_id)
    {
        return $this->callLegacyModel('Apply_leave_model', 'canApproveLeave', func_get_args());
    }

    public function getclassteacherbyclasssection($class_id, $section_id)
    {
        return $this->callLegacyModel('Apply_leave_model', 'getclassteacherbyclasssection', func_get_args());
    }

    public function getstudentleave($id = null, $carray = null, $section_array = null)
    {
        return $this->callLegacyModel('Apply_leave_model', 'getstudentleave', func_get_args());
    }

    public function getStudentMonthlyLeave($start_date, $end_date)
    {
        return $this->callLegacyModel('Apply_leave_model', 'getStudentMonthlyLeave', func_get_args());
    }

    public function getStudentApproveMonthlyLeave($start_date, $end_date)
    {
        return $this->callLegacyModel('Apply_leave_model', 'getStudentApproveMonthlyLeave', func_get_args());
    }

    public function getStaffMonthlyLeave($start_date, $end_date)
    {
        return $this->callLegacyModel('Apply_leave_model', 'getStaffMonthlyLeave', func_get_args());
    }

    public function getStaffApproveMonthlyLeave($start_date, $end_date)
    {
        return $this->callLegacyModel('Apply_leave_model', 'getStaffApproveMonthlyLeave', func_get_args());
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