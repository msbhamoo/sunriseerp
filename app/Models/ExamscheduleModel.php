<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * ExamscheduleModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Examschedule_model.
 */
class ExamscheduleModel extends BaseModel
{
    protected $table = 'examschedule';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function getDetailbyClsandSection($class_id, $section_id, $exam_id)
    {
        return $this->callLegacyModel('Examschedule_model', 'getDetailbyClsandSection', func_get_args());
    }

    public function getTeacherSubjects($class_id, $section_id, $id)
    {
        return $this->callLegacyModel('Examschedule_model', 'getTeacherSubjects', func_get_args());
    }

    public function getExamByClassandSection($class_id, $section_id)
    {
        return $this->callLegacyModel('Examschedule_model', 'getExamByClassandSection', func_get_args());
    }

    public function getresultByStudentandExam($exam_id, $student_id)
    {
        return $this->callLegacyModel('Examschedule_model', 'getresultByStudentandExam', func_get_args());
    }

    public function getclassandsectionbyexam($exam_id)
    {
        return $this->callLegacyModel('Examschedule_model', 'getclassandsectionbyexam', func_get_args());
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