<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * SubjecttimetableModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Subjecttimetable_model.
 */
class SubjecttimetableModel extends BaseModel
{
    protected $table = 'subjecttimetable';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add($delete_array, $insert_array, $update_array)
    {
        return $this->callLegacyModel('Subjecttimetable_model', 'add', func_get_args());
    }

    public function get($id)
    {
        return $this->callLegacyModel('Subjecttimetable_model', 'get', func_get_args());
    }

    public function getBySubjectGroupDayClassSection($subject_group_id, $day, $class_id, $section_id)
    {
        return $this->callLegacyModel('Subjecttimetable_model', 'getBySubjectGroupDayClassSection', func_get_args());
    }

    public function getSubjectByClassandSectionDay($class_id, $section_id, $day)
    {
        return $this->callLegacyModel('Subjecttimetable_model', 'getSubjectByClassandSectionDay', func_get_args());
    }

    public function getparentSubjectByClassandSectionDay($class_id, $section_id, $day)
    {
        return $this->callLegacyModel('Subjecttimetable_model', 'getparentSubjectByClassandSectionDay', func_get_args());
    }

    public function getTeacherByClassandSection($class_id, $section_id)
    {
        return $this->callLegacyModel('Subjecttimetable_model', 'getTeacherByClassandSection', func_get_args());
    }

    public function getSubjectByClassandSection($class_id, $section_id)
    {
        return $this->callLegacyModel('Subjecttimetable_model', 'getSubjectByClassandSection', func_get_args());
    }

    public function getSyllabussubject($staff_id, $day_value, $class_section_array)
    {
        return $this->callLegacyModel('Subjecttimetable_model', 'getSyllabussubject', func_get_args());
    }

    public function getByStaffandDay($staff_id, $day_value)
    {
        return $this->callLegacyModel('Subjecttimetable_model', 'getByStaffandDay', func_get_args());
    }

    public function getByStaffClassTeachersubjects($staff_id)
    {
        return $this->callLegacyModel('Subjecttimetable_model', 'getByStaffClassTeachersubjects', func_get_args());
    }

    public function getByTeacherSubjects($staff_id)
    {
        return $this->callLegacyModel('Subjecttimetable_model', 'getByTeacherSubjects', func_get_args());
    }

    public function canAddExamMarks($staff_id, $class_id, $section_id, $subject_id)
    {
        return $this->callLegacyModel('Subjecttimetable_model', 'canAddExamMarks', func_get_args());
    }

    public function getByTeacherSubjectandDay($staff_id, $day_value)
    {
        return $this->callLegacyModel('Subjecttimetable_model', 'getByTeacherSubjectandDay', func_get_args());
    }

    public function getByStaffClassTeacherandDay($staff_id, $day_value)
    {
        return $this->callLegacyModel('Subjecttimetable_model', 'getByStaffClassTeacherandDay', func_get_args());
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