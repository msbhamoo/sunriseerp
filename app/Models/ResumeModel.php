<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * ResumeModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Resume_model.
 */
class ResumeModel extends BaseModel
{
    protected $table = 'resume';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($id = null)
    {
        return $this->callLegacyModel('Resume_model', 'get', func_get_args());
    }

    public function get_student_work_experience($student_id)
    {
        return $this->callLegacyModel('Resume_model', 'get_student_work_experience', func_get_args());
    }

    public function get_student_education($student_id)
    {
        return $this->callLegacyModel('Resume_model', 'get_student_education', func_get_args());
    }

    public function get_student_skills($student_id)
    {
        return $this->callLegacyModel('Resume_model', 'get_student_skills', func_get_args());
    }

    public function get_student_reference($student_id)
    {
        return $this->callLegacyModel('Resume_model', 'get_student_reference', func_get_args());
    }

    public function add_work_experience($data)
    {
        return $this->callLegacyModel('Resume_model', 'add_work_experience', func_get_args());
    }

    public function add_education_details($data)
    {
        return $this->callLegacyModel('Resume_model', 'add_education_details', func_get_args());
    }

    public function add_skills_detail($data)
    {
        return $this->callLegacyModel('Resume_model', 'add_skills_detail', func_get_args());
    }

    public function add_reference_detail($data)
    {
        return $this->callLegacyModel('Resume_model', 'add_reference_detail', func_get_args());
    }

    public function delete_work_experience($student_id)
    {
        return $this->callLegacyModel('Resume_model', 'delete_work_experience', func_get_args());
    }

    public function delete_student_educational($student_id)
    {
        return $this->callLegacyModel('Resume_model', 'delete_student_educational', func_get_args());
    }

    public function delete_referensh($student_id)
    {
        return $this->callLegacyModel('Resume_model', 'delete_referensh', func_get_args());
    }

    public function delete_student_skills($student_id)
    {
        return $this->callLegacyModel('Resume_model', 'delete_student_skills', func_get_args());
    }

    public function getformfields()
    {
        return $this->callLegacyModel('Resume_model', 'getformfields', func_get_args());
    }

    public function addformfields($record)
    {
        return $this->callLegacyModel('Resume_model', 'addformfields', func_get_args());
    }

    public function editguardianfield($status)
    {
        return $this->callLegacyModel('Resume_model', 'editguardianfield', func_get_args());
    }

    public function getfieldstatus($fieldname)
    {
        return $this->callLegacyModel('Resume_model', 'getfieldstatus', func_get_args());
    }

    public function get_additional_field_status($fieldname)
    {
        return $this->callLegacyModel('Resume_model', 'get_additional_field_status', func_get_args());
    }

    public function add_additional_fields_setting($record)
    {
        return $this->callLegacyModel('Resume_model', 'add_additional_fields_setting', func_get_args());
    }

    public function get_additional_fields()
    {
        return $this->callLegacyModel('Resume_model', 'get_additional_fields', func_get_args());
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