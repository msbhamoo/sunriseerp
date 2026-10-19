<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * ExamgroupModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Examgroup_model.
 */
class ExamgroupModel extends BaseModel
{
    protected $table = 'examgroup';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($id = null)
    {
        return $this->callLegacyModel('Examgroup_model', 'get', func_get_args());
    }

    public function getExamByID($id = null)
    {
        return $this->callLegacyModel('Examgroup_model', 'getExamByID', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Examgroup_model', 'remove', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Examgroup_model', 'add', func_get_args());
    }

    public function delete_exam($id)
    {
        return $this->callLegacyModel('Examgroup_model', 'delete_exam', func_get_args());
    }

    public function add_exam($data)
    {
        return $this->callLegacyModel('Examgroup_model', 'add_exam', func_get_args());
    }

    public function getExamByExamGroup($id, $is_active = false)
    {
        return $this->callLegacyModel('Examgroup_model', 'getExamByExamGroup', func_get_args());
    }

    public function getBacklogExam($parent_exam_id)
    {
        return $this->callLegacyModel('Examgroup_model', 'getBacklogExam', func_get_args());
    }

    public function getExamGroupDetailByID($id)
    {
        return $this->callLegacyModel('Examgroup_model', 'getExamGroupDetailByID', func_get_args());
    }

    public function verifyExamConnection($exam_array)
    {
        return $this->callLegacyModel('Examgroup_model', 'verifyExamConnection', func_get_args());
    }

    public function getExamByExamGroupConnection($id = null)
    {
        return $this->callLegacyModel('Examgroup_model', 'getExamByExamGroupConnection', func_get_args());
    }

    public function getExamGroupConnectionList($exam_group_id = null)
    {
        return $this->callLegacyModel('Examgroup_model', 'getExamGroupConnectionList', func_get_args());
    }

    public function connectExam($insert_array, $exam_group_id)
    {
        return $this->callLegacyModel('Examgroup_model', 'connectExam', func_get_args());
    }

    public function deleteExamGroupConnection($exam_group_id)
    {
        return $this->callLegacyModel('Examgroup_model', 'deleteExamGroupConnection', func_get_args());
    }

    public function getExamGroupByStudent($student_id, $active = 1)
    {
        return $this->callLegacyModel('Examgroup_model', 'getExamGroupByStudent', func_get_args());
    }

    public function getExamGroupByStudentSession($student_session_id, $active = 1)
    {
        return $this->callLegacyModel('Examgroup_model', 'getExamGroupByStudentSession', func_get_args());
    }

    public function getExamResultStudent($exam_group_exam_id, $exam_group_id, $student_id)
    {
        return $this->callLegacyModel('Examgroup_model', 'getExamResultStudent', func_get_args());
    }

    public function getExamResultDetailStudent($exam_group_exam_id, $exam_group_id, $student_id)
    {
        return $this->callLegacyModel('Examgroup_model', 'getExamResultDetailStudent', func_get_args());
    }

    public function getExamGroupExamsResultByStudentID($exam_group_id, $student_id)
    {
        return $this->callLegacyModel('Examgroup_model', 'getExamGroupExamsResultByStudentID', func_get_args());
    }

    public function getExamGroupConnection($exam_group_id)
    {
        return $this->callLegacyModel('Examgroup_model', 'getExamGroupConnection', func_get_args());
    }

    public function getExamGroupByClassSection($class_id, $section_id, $session_id)
    {
        return $this->callLegacyModel('Examgroup_model', 'getExamGroupByClassSection', func_get_args());
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