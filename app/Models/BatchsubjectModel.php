<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * BatchsubjectModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Batchsubject_model.
 */
class BatchsubjectModel extends BaseModel
{
    protected $table = 'batchsubject';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function getExamSubjects($id = null)
    {
        return $this->callLegacyModel('Batchsubject_model', 'getExamSubjects', func_get_args());
    }

    public function getExamstudentSubjects($id = null)
    {
        return $this->callLegacyModel('Batchsubject_model', 'getExamstudentSubjects', func_get_args());
    }

    public function examGroupExamResult($class_id, $batch_id, $exam_group_class_batch_exams_id)
    {
        return $this->callLegacyModel('Batchsubject_model', 'examGroupExamResult', func_get_args());
    }

    public function attempt_exam_students($class_id, $batch_id, $exam_group_class_batch_exams_id)
    {
        return $this->callLegacyModel('Batchsubject_model', 'attempt_exam_students', func_get_args());
    }

    public function get($id = null)
    {
        return $this->callLegacyModel('Batchsubject_model', 'get', func_get_args());
    }

    public function getClassBatchSubjects($id = null)
    {
        return $this->callLegacyModel('Batchsubject_model', 'getClassBatchSubjects', func_get_args());
    }

    public function getByID($id = null)
    {
        return $this->callLegacyModel('Batchsubject_model', 'getByID', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Batchsubject_model', 'remove', func_get_args());
    }

    public function removeGroup($id)
    {
        return $this->callLegacyModel('Batchsubject_model', 'removeGroup', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Batchsubject_model', 'add', func_get_args());
    }

    public function batchsubject_exists($class_section_id, $batch_id)
    {
        return $this->callLegacyModel('Batchsubject_model', 'batchsubject_exists', func_get_args());
    }

    public function check_data_exists($data)
    {
        return $this->callLegacyModel('Batchsubject_model', 'check_data_exists', func_get_args());
    }

    public function class_exists($str)
    {
        return $this->callLegacyModel('Batchsubject_model', 'class_exists', func_get_args());
    }

    public function getBatchClass($id = null)
    {
        return $this->callLegacyModel('Batchsubject_model', 'getBatchClass', func_get_args());
    }

    public function getBatchSectionByClass($classid)
    {
        return $this->callLegacyModel('Batchsubject_model', 'getBatchSectionByClass', func_get_args());
    }

    public function getBatchByClassSection($class_section_id)
    {
        return $this->callLegacyModel('Batchsubject_model', 'getBatchByClassSection', func_get_args());
    }

    public function getBatchByClass($class_id)
    {
        return $this->callLegacyModel('Batchsubject_model', 'getBatchByClass', func_get_args());
    }

    public function getExamSubject($exam_subject_id)
    {
        return $this->callLegacyModel('Batchsubject_model', 'getExamSubject', func_get_args());
    }

    public function valid_batchsubject()
    {
        return $this->callLegacyModel('Batchsubject_model', 'valid_batchsubject', func_get_args());
    }

    public function check_batch_subject_exists($class_section_id, $subject_id, $batch_id, $id)
    {
        return $this->callLegacyModel('Batchsubject_model', 'check_batch_subject_exists', func_get_args());
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