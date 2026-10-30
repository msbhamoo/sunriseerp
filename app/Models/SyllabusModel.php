<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * SyllabusModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Syllabus_model.
 */
class SyllabusModel extends BaseModel
{
    protected $table = 'syllabus';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function getmysubjects($class_id, $section_id)
    {
        return $this->callLegacyModel('Syllabus_model', 'getmysubjects', func_get_args());
    }

    public function get_subjectstatus($id, $subject_group_class_sections_id)
    {
        return $this->callLegacyModel('Syllabus_model', 'get_subjectstatus', func_get_args());
    }

    public function get_studentsyllabus($data)
    {
        return $this->callLegacyModel('Syllabus_model', 'get_studentsyllabus', func_get_args());
    }

    public function get_subject_syllabus($data, $staff_id)
    {
        return $this->callLegacyModel('Syllabus_model', 'get_subject_syllabus', func_get_args());
    }

    public function get_subject_syllabus_student($data)
    {
        return $this->callLegacyModel('Syllabus_model', 'get_subject_syllabus_student', func_get_args());
    }

    public function check_subject_syllabus_student($data)
    {
        return $this->callLegacyModel('Syllabus_model', 'check_subject_syllabus_student', func_get_args());
    }

    public function get_subject_syllabus_student_byDate($data)
    {
        return $this->callLegacyModel('Syllabus_model', 'get_subject_syllabus_student_byDate', func_get_args());
    }

    public function get_subject_syllabusdatabyid($id)
    {
        return $this->callLegacyModel('Syllabus_model', 'get_subject_syllabusdatabyid', func_get_args());
    }

    public function get_subject_syllabusdata($subject_group_subject_id, $date, $role_id, $staff_id, $time_from = null, $time_to = null, $subject_group_class_sections_id = null)
    {
        return $this->callLegacyModel('Syllabus_model', 'get_subject_syllabusdata', func_get_args());
    }

    public function get_subjectteachersreport($subject_group_subject_id, $subject_group_class_sections_id)
    {
        return $this->callLegacyModel('Syllabus_model', 'get_subjectteachersreport', func_get_args());
    }

    public function get_subjectsyllabussreport($subject_group_subject_id, $subject_group_class_sections_id)
    {
        return $this->callLegacyModel('Syllabus_model', 'get_subjectsyllabussreport', func_get_args());
    }

    public function get_topicbylessonid($lesson_id)
    {
        return $this->callLegacyModel('Syllabus_model', 'get_topicbylessonid', func_get_args());
    }

    public function get_subjectsyllabusbyid($id)
    {
        return $this->callLegacyModel('Syllabus_model', 'get_subjectsyllabusbyid', func_get_args());
    }

    public function delete_subject_syllabus($id)
    {
        return $this->callLegacyModel('Syllabus_model', 'delete_subject_syllabus', func_get_args());
    }

    public function addmessage($data)
    {
        return $this->callLegacyModel('Syllabus_model', 'addmessage', func_get_args());
    }

    public function getmessage($subject_syllabus_id = null)
    {
        return $this->callLegacyModel('Syllabus_model', 'getmessage', func_get_args());
    }

    public function getstudentmessage($subject_syllabus_id = null)
    {
        return $this->callLegacyModel('Syllabus_model', 'getstudentmessage', func_get_args());
    }

    public function deletemessage($id)
    {
        return $this->callLegacyModel('Syllabus_model', 'deletemessage', func_get_args());
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