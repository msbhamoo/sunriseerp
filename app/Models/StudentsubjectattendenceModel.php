<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * StudentsubjectattendenceModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Studentsubjectattendence_model.
 */
class StudentsubjectattendenceModel extends BaseModel
{
    protected $table = 'studentsubjectattendence';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add($insert_array, $update_array)
    {
        return $this->callLegacyModel('Studentsubjectattendence_model', 'add', func_get_args());
    }

    public function addorUpdate($attendances)
    {
        return $this->callLegacyModel('Studentsubjectattendence_model', 'addorUpdate', func_get_args());
    }

    public function searchAttendenceClassSection($class_id, $section_id, $subject_timetable_id, $date)
    {
        return $this->callLegacyModel('Studentsubjectattendence_model', 'searchAttendenceClassSection', func_get_args());
    }

    public function getStudentMontlyAttendence($class_id, $section_id, $from_date, $to_date, $student_id,$subject_id)
    {
        return $this->callLegacyModel('Studentsubjectattendence_model', 'getStudentMontlyAttendence', func_get_args());
    }

    public function searchByStudentAttendanceByDate($class_id, $section_id, $day, $date, $student_id,$subject_id)
    {
        return $this->callLegacyModel('Studentsubjectattendence_model', 'searchByStudentAttendanceByDate', func_get_args());
    }

    public function studentAttendanceByDate($class_id, $section_id, $day, $date, $student_session_id)
    {
        return $this->callLegacyModel('Studentsubjectattendence_model', 'studentAttendanceByDate', func_get_args());
    }

    public function getStudentsMontlyAttendence($class_id, $section_id, $from_date, $to_date,$subject_id)
    {
        return $this->callLegacyModel('Studentsubjectattendence_model', 'getStudentsMontlyAttendence', func_get_args());
    }

    public function searchByStudentsAttendanceByDate($class_id, $section_id, $day, $date,$subject_id)
    {
        return $this->callLegacyModel('Studentsubjectattendence_model', 'searchByStudentsAttendanceByDate', func_get_args());
    }

    public function attendanceYearCount()
    {
        return $this->callLegacyModel('Studentsubjectattendence_model', 'attendanceYearCount', func_get_args());
    }

    public function is_biometricAttendence()
    {
        return $this->callLegacyModel('Studentsubjectattendence_model', 'is_biometricAttendence', func_get_args());
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