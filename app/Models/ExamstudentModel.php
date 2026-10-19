<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * ExamstudentModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Examstudent_model.
 */
class ExamstudentModel extends BaseModel
{
    protected $table = 'examstudent';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function searchExamStudents($class_id, $section_id, $exam_id)
    {
        return $this->callLegacyModel('Examstudent_model', 'searchExamStudents', func_get_args());
    }

    public function add_exam_result($data)
    {
        return $this->callLegacyModel('Examstudent_model', 'add_exam_result', func_get_args());
    }

    public function add_student($insert_array, $exam_group_class_batch_exam_id, $all_students)
    {
        return $this->callLegacyModel('Examstudent_model', 'add_student', func_get_args());
    }

    public function checkStudentExists($check_alreay_inserted_students, $exam_group_class_batch_exam_id)
    {
        return $this->callLegacyModel('Examstudent_model', 'checkStudentExists', func_get_args());
    }

    public function insert($data = null, bool $returnID = true)
    {
        return $this->callLegacyModel('Examstudent_model', 'insert', func_get_args());
    }

    public function getBatchStudentDetail($exam_group_class_batch_exam_student_id)
    {
        return $this->callLegacyModel('Examstudent_model', 'getBatchStudentDetail', func_get_args());
    }

    public function getStudentByExamAndStudentID($student_id, $exam_group_class_batch_exam_id)
    {
        return $this->callLegacyModel('Examstudent_model', 'getStudentByExamAndStudentID', func_get_args());
    }

    public function getStudentsAdmitCardByExamAndStudentID($students_array, $exam_group_class_batch_exam_id)
    {
        return $this->callLegacyModel('Examstudent_model', 'getStudentsAdmitCardByExamAndStudentID', func_get_args());
    }

    public function getStudentDetailsByExamAndStudentID($student_id, $exam_group_class_batch_exam_id)
    {
        return $this->callLegacyModel('Examstudent_model', 'getStudentDetailsByExamAndStudentID', func_get_args());
    }

    public function getStudentdetailByExam($student_id, $exam_group_class_batch_exam_id)
    {
        return $this->callLegacyModel('Examstudent_model', 'getStudentdetailByExam', func_get_args());
    }

    public function getExamStudentByID($exam_group_class_batch_exam_id)
    {
        return $this->callLegacyModel('Examstudent_model', 'getExamStudentByID', func_get_args());
    }

    public function getstudentexam($admission_no)
    {
        return $this->callLegacyModel('Examstudent_model', 'getstudentexam', func_get_args());
    }

    public function getstudentsessionidbyadmissionno($admission_no)
    {
        return $this->callLegacyModel('Examstudent_model', 'getstudentsessionidbyadmissionno', func_get_args());
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