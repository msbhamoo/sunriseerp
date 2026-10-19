<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * SubjectgroupModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Subjectgroup_model.
 */
class SubjectgroupModel extends BaseModel
{
    protected $table = 'subjectgroup';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($classid = null)
    {
        return $this->callLegacyModel('Subjectgroup_model', 'get', func_get_args());
    }

    public function update($id = null, $row = null): bool
    {
        return $this->callLegacyModel('Subjectgroup_model', 'update', func_get_args());
    }

    public function check_data_exists($data)
    {
        return $this->callLegacyModel('Subjectgroup_model', 'check_data_exists', func_get_args());
    }

    public function class_exists($str)
    {
        return $this->callLegacyModel('Subjectgroup_model', 'class_exists', func_get_args());
    }

    public function edit($data, $delete_sections, $add_sections, $delete_subjects, $add_subjects)
    {
        return $this->callLegacyModel('Subjectgroup_model', 'edit', func_get_args());
    }

    public function add($data, $subject_group, $section_group)
    {
        return $this->callLegacyModel('Subjectgroup_model', 'add', func_get_args());
    }

    public function getDetailbyClassSection($class_id, $section_id)
    {
        return $this->callLegacyModel('Subjectgroup_model', 'getDetailbyClassSection', func_get_args());
    }

    public function getByID($id = null)
    {
        return $this->callLegacyModel('Subjectgroup_model', 'getByID', func_get_args());
    }

    public function getClassSectionByGroup($subject_group_id)
    {
        return $this->callLegacyModel('Subjectgroup_model', 'getClassSectionByGroup', func_get_args());
    }

    public function getGroupsubjects($subject_group_id ,$session_id=NULL)
    {
        return $this->callLegacyModel('Subjectgroup_model', 'getGroupsubjects', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Subjectgroup_model', 'remove', func_get_args());
    }

    public function getSubjectgroupbyTeacherid($staff_id)
    {
        return $this->callLegacyModel('Subjectgroup_model', 'getSubjectgroupbyTeacherid', func_get_args());
    }

    public function getGroupByClassandSection($class_id, $section_id,$session_id=NULL)
    {
        return $this->callLegacyModel('Subjectgroup_model', 'getGroupByClassandSection', func_get_args());
    }

    public function getClassandSectionTimetable($class_id, $section_id)
    {
        return $this->callLegacyModel('Subjectgroup_model', 'getClassandSectionTimetable', func_get_args());
    }

    public function check_section_exists($str)
    {
        return $this->callLegacyModel('Subjectgroup_model', 'check_section_exists', func_get_args());
    }

    public function check_section_data_exists($sections, $id)
    {
        return $this->callLegacyModel('Subjectgroup_model', 'check_section_data_exists', func_get_args());
    }

    public function getsubject($class_id,$section_id)
    {
        return $this->callLegacyModel('Subjectgroup_model', 'getsubject', func_get_args());
    }

    public function getAllsubjectByClassSection($class_id,$section_id)
    {
        return $this->callLegacyModel('Subjectgroup_model', 'getAllsubjectByClassSection', func_get_args());
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