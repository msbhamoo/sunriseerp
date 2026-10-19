<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * OnlineexamModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Onlineexam_model.
 */
class OnlineexamModel extends BaseModel
{
    protected $table = 'onlineexam';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add($data)
    {
        return $this->callLegacyModel('Onlineexam_model', 'add', func_get_args());
    }

    public function get($id = null, $publish = null)
    {
        return $this->callLegacyModel('Onlineexam_model', 'get', func_get_args());
    }

    public function getexamlist()
    {
        return $this->callLegacyModel('Onlineexam_model', 'getexamlist', func_get_args());
    }

    public function get_myexam($role_id)
    {
        return $this->callLegacyModel('Onlineexam_model', 'get_myexam', func_get_args());
    }

    public function getclosedexamlist()
    {
        return $this->callLegacyModel('Onlineexam_model', 'getclosedexamlist', func_get_args());
    }

    public function insertExamQuestion($insert_data)
    {
        return $this->callLegacyModel('Onlineexam_model', 'insertExamQuestion', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Onlineexam_model', 'remove', func_get_args());
    }

    public function searchOnlineExamStudents($class_id, $section_id, $onlineexam_id)
    {
        return $this->callLegacyModel('Onlineexam_model', 'searchOnlineExamStudents', func_get_args());
    }

    public function searchAllOnlineExamStudents($onlineexam_id, $class_id = null, $section_id = null,$is_attempted=null)
    {
        return $this->callLegacyModel('Onlineexam_model', 'searchAllOnlineExamStudents', func_get_args());
    }

    public function addStudents($data_insert, $data_delete, $onlineexam_id)
    {
        return $this->callLegacyModel('Onlineexam_model', 'addStudents', func_get_args());
    }

    public function updateStudentRank($onlineexam_students, $exam_id)
    {
        return $this->callLegacyModel('Onlineexam_model', 'updateStudentRank', func_get_args());
    }

    public function getStudentAttemts($onlineexam_student_id)
    {
        return $this->callLegacyModel('Onlineexam_model', 'getStudentAttemts', func_get_args());
    }

    public function addStudentAttemts($data)
    {
        return $this->callLegacyModel('Onlineexam_model', 'addStudentAttemts', func_get_args());
    }

    public function examstudentsID($student_session_id, $onlineexam_id)
    {
        return $this->callLegacyModel('Onlineexam_model', 'examstudentsID', func_get_args());
    }

    public function updateExamResult($onlineexam_student_id)
    {
        return $this->callLegacyModel('Onlineexam_model', 'updateExamResult', func_get_args());
    }

    public function getStudentexam($student_session_id)
    {
        return $this->callLegacyModel('Onlineexam_model', 'getStudentexam', func_get_args());
    }

    public function getstudentexamlist($student_session_id)
    {
        return $this->callLegacyModel('Onlineexam_model', 'getstudentexamlist', func_get_args());
    }

    public function getstudentclosedexamlist($student_session_id)
    {
        return $this->callLegacyModel('Onlineexam_model', 'getstudentclosedexamlist', func_get_args());
    }

    public function getExamQuestions($id = null, $random_type = false)
    {
        return $this->callLegacyModel('Onlineexam_model', 'getExamQuestions', func_get_args());
    }

    public function onlineexamReport($condition)
    {
        return $this->callLegacyModel('Onlineexam_model', 'onlineexamReport', func_get_args());
    }

    public function dtonlineexamReport($condition)
    {
        return $this->callLegacyModel('Onlineexam_model', 'dtonlineexamReport', func_get_args());
    }

    public function onlineexamatteptreport($condition)
    {
        return $this->callLegacyModel('Onlineexam_model', 'onlineexamatteptreport', func_get_args());
    }

    public function getstudentByexam_id($id)
    {
        return $this->callLegacyModel('Onlineexam_model', 'getstudentByexam_id', func_get_args());
    }

    public function get_msnstatusByexam_id($id)
    {
        return $this->callLegacyModel('Onlineexam_model', 'get_msnstatusByexam_id', func_get_args());
    }

    public function bulkdelete($exams)
    {
        return $this->callLegacyModel('Onlineexam_model', 'bulkdelete', func_get_args());
    }

    public function getstudentbystudentsessionid($student_session_id)
    {
        return $this->callLegacyModel('Onlineexam_model', 'getstudentbystudentsessionid', func_get_args());
    }

    public function getexamdetails($id = null, $publish = null)
    {
        return $this->callLegacyModel('Onlineexam_model', 'getexamdetails', func_get_args());
    }

    public function printstudentexamdetails($id = null, $publish = null)
    {
        return $this->callLegacyModel('Onlineexam_model', 'printstudentexamdetails', func_get_args());
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