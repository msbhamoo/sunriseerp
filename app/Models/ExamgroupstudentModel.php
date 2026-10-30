<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * ExamgroupstudentModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Examgroupstudent_model.
 */
class ExamgroupstudentModel extends BaseModel
{
    protected $table = 'examgroupstudent';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function searchExamGroupStudentAttempted1($exam_group_id, $class_id, $batch_id)
    {
        return $this->callLegacyModel('Examgroupstudent_model', 'searchExamGroupStudentAttempted1', func_get_args());
    }

    public function searchExamGroupStudentAttempted($exam_group_id, $exam_id, $class_id, $section_id, $session_id)
    {
        return $this->callLegacyModel('Examgroupstudent_model', 'searchExamGroupStudentAttempted', func_get_args());
    }

    public function searchExamStudentsByExam($exam_id)
    {
        return $this->callLegacyModel('Examgroupstudent_model', 'searchExamStudentsByExam', func_get_args());
    }

    public function searchExamStudents($exam_group_id, $exam_id, $class_id, $section_id, $session_id)
    {
        return $this->callLegacyModel('Examgroupstudent_model', 'searchExamStudents', func_get_args());
    }

    public function searchExamGroupStudents($exam_group_id, $class_id, $section_id, $session_id)
    {
        return $this->callLegacyModel('Examgroupstudent_model', 'searchExamGroupStudents', func_get_args());
    }

    public function add($data_insert, $data_delete, $exam_group_id)
    {
        return $this->callLegacyModel('Examgroupstudent_model', 'add', func_get_args());
    }

    public function examGroupSubjectResult($exam_subject_id, $class_id, $section_id, $session_id)
    {
        return $this->callLegacyModel('Examgroupstudent_model', 'examGroupSubjectResult', func_get_args());
    }

    public function add_result($insert_array)
    {
        return $this->callLegacyModel('Examgroupstudent_model', 'add_result', func_get_args());
    }

    public function searchStudentByClassSectionSession($class_id, $section_id, $session_id)
    {
        return $this->callLegacyModel('Examgroupstudent_model', 'searchStudentByClassSectionSession', func_get_args());
    }

    public function searchStudentExams($student_session_id, $is_active = false, $is_publish = false)
    {
        return $this->callLegacyModel('Examgroupstudent_model', 'searchStudentExams', func_get_args());
    }

    public function studentExams($student_session_id)
    {
        return $this->callLegacyModel('Examgroupstudent_model', 'studentExams', func_get_args());
    }

    public function updateExamStudent($data)
    {
        return $this->callLegacyModel('Examgroupstudent_model', 'updateExamStudent', func_get_args());
    }

    public function getexamresult($student_session_id, $exam_id, $is_active = false, $is_publish = false)
    {
        return $this->callLegacyModel('Examgroupstudent_model', 'getexamresult', func_get_args());
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