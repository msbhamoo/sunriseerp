<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * TeacherModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Teacher_model.
 */
class TeacherModel extends BaseModel
{
    protected $table = 'teacher';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($id = null)
    {
        return $this->callLegacyModel('Teacher_model', 'get', func_get_args());
    }

    public function getTeacher($id = null)
    {
        return $this->callLegacyModel('Teacher_model', 'getTeacher', func_get_args());
    }

    public function getTeacherByEmail($email = null)
    {
        return $this->callLegacyModel('Teacher_model', 'getTeacherByEmail', func_get_args());
    }

    public function getLibraryTeacher()
    {
        return $this->callLegacyModel('Teacher_model', 'getLibraryTeacher', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Teacher_model', 'remove', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Teacher_model', 'add', func_get_args());
    }

    public function getTotalTeacher()
    {
        return $this->callLegacyModel('Teacher_model', 'getTotalTeacher', func_get_args());
    }

    public function searchNameLike($searchterm)
    {
        return $this->callLegacyModel('Teacher_model', 'searchNameLike', func_get_args());
    }

    public function rating($data)
    {
        return $this->callLegacyModel('Teacher_model', 'rating', func_get_args());
    }

    public function my_classes($staff_id)
    {
        return $this->callLegacyModel('Teacher_model', 'my_classes', func_get_args());
    }

    public function get_classbysubject_group_id($subject_group_id)
    {
        return $this->callLegacyModel('Teacher_model', 'get_classbysubject_group_id', func_get_args());
    }

    public function get_subjectby_staffid($staff_id)
    {
        return $this->callLegacyModel('Teacher_model', 'get_subjectby_staffid', func_get_args());
    }

    public function get_examsubjects($staff_id)
    {
        return $this->callLegacyModel('Teacher_model', 'get_examsubjects', func_get_args());
    }

    public function get_subjectby_classid($class_id, $section_id, $staff_id)
    {
        return $this->callLegacyModel('Teacher_model', 'get_subjectby_classid', func_get_args());
    }

    public function get_teacherrestricted_mode($staff_id)
    {
        return $this->callLegacyModel('Teacher_model', 'get_teacherrestricted_mode', func_get_args());
    }

    public function get_daywiseattendanceclass($staff_id)
    {
        return $this->callLegacyModel('Teacher_model', 'get_daywiseattendanceclass', func_get_args());
    }

    public function get_teacherrestricted_modesections($staff_id, $classid)
    {
        return $this->callLegacyModel('Teacher_model', 'get_teacherrestricted_modesections', func_get_args());
    }

    public function get_teacherrestricted_modeallsections($staff_id)
    {
        return $this->callLegacyModel('Teacher_model', 'get_teacherrestricted_modeallsections', func_get_args());
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