<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * StudentsessionModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Studentsession_model.
 */
class StudentsessionModel extends BaseModel
{
    protected $table = 'studentsession';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function getMultiClassWithTeacher($student_id)
    {
        return $this->callLegacyModel('Studentsession_model', 'getMultiClassWithTeacher', func_get_args());
    }

    public function searchStudents($class_id = null, $section_id = null, $key = null)
    {
        return $this->callLegacyModel('Studentsession_model', 'searchStudents', func_get_args());
    }

    public function searchStudentsBySession($student_session_id = null)
    {
        return $this->callLegacyModel('Studentsession_model', 'searchStudentsBySession', func_get_args());
    }

    public function getStudentClass($id)
    {
        return $this->callLegacyModel('Studentsession_model', 'getStudentClass', func_get_args());
    }

    public function updateById($update_array)
    {
        return $this->callLegacyModel('Studentsession_model', 'updateById', func_get_args());
    }

    public function updatePromote($update_array)
    {
        return $this->callLegacyModel('Studentsession_model', 'updatePromote', func_get_args());
    }

    public function getSessionById($id)
    {
        return $this->callLegacyModel('Studentsession_model', 'getSessionById', func_get_args());
    }

    public function getTotalStudentBySession()
    {
        return $this->callLegacyModel('Studentsession_model', 'getTotalStudentBySession', func_get_args());
    }

    public function getTotalHeadCountBySession()
    {
        return $this->callLegacyModel('Studentsession_model', 'getTotalHeadCountBySession', func_get_args());
    }

    public function getAllActiveStudentSessions()
    {
        return $this->callLegacyModel('Studentsession_model', 'getAllActiveStudentSessions', func_get_args());
    }

    public function add($insert_array, $student_id)
    {
        return $this->callLegacyModel('Studentsession_model', 'add', func_get_args());
    }

    public function searchMultiStudentByClassSection($class_id = null, $section_id = null)
    {
        return $this->callLegacyModel('Studentsession_model', 'searchMultiStudentByClassSection', func_get_args());
    }

    public function searchMultiClsSectionByStudent($student_id)
    {
        return $this->callLegacyModel('Studentsession_model', 'searchMultiClsSectionByStudent', func_get_args());
    }

    public function getMultiClsSectionByStudentOldSession($student_id)
    {
        return $this->callLegacyModel('Studentsession_model', 'getMultiClsSectionByStudentOldSession', func_get_args());
    }

    public function searchActiveClassSectionStudent($student_id, $enable_session = null)
    {
        return $this->callLegacyModel('Studentsession_model', 'searchActiveClassSectionStudent', func_get_args());
    }

    public function addMultiClassWithTeacher($insert_array, $student_id, $class_id, $section_id)
    {
        return $this->callLegacyModel('Studentsession_model', 'addMultiClassWithTeacher', func_get_args());
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