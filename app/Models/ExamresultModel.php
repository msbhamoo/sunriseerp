<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * ExamresultModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Examresult_model.
 */
class ExamresultModel extends BaseModel
{
    protected $table = 'examresult';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($id = null)
    {
        return $this->callLegacyModel('Examresult_model', 'get', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Examresult_model', 'remove', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Examresult_model', 'add', func_get_args());
    }

    public function add_exam_result($data)
    {
        return $this->callLegacyModel('Examresult_model', 'add_exam_result', func_get_args());
    }

    public function get_exam_result($exam_schedule_id = null, $student_id = null)
    {
        return $this->callLegacyModel('Examresult_model', 'get_exam_result', func_get_args());
    }

    public function get_result($exam_schedule_id = null, $student_id = null)
    {
        return $this->callLegacyModel('Examresult_model', 'get_result', func_get_args());
    }

    public function checkexamresultpreparebyexam($exam_id, $class_id, $section_id)
    {
        return $this->callLegacyModel('Examresult_model', 'checkexamresultpreparebyexam', func_get_args());
    }

    public function getStudentExamResultByStudent($exam_id, $student_id, $exam_schedule)
    {
        return $this->callLegacyModel('Examresult_model', 'getStudentExamResultByStudent', func_get_args());
    }

    public function getExamResults($exam_id, $post_exam_group_id, $students)
    {
        return $this->callLegacyModel('Examresult_model', 'getExamResults', func_get_args());
    }

    public function updaterank($update_array,$exam_group_class_batch_exam_id)
    {
        return $this->callLegacyModel('Examresult_model', 'updaterank', func_get_args());
    }

    public function getStudentResultByExam($exam_id, $student_id)
    {
        return $this->callLegacyModel('Examresult_model', 'getStudentResultByExam', func_get_args());
    }

    public function getStudentExamResults($exam_id, $post_exam_group_id, $exam_group_class_batch_exam_student_id, $student_id)
    {
        return $this->callLegacyModel('Examresult_model', 'getStudentExamResults', func_get_args());
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