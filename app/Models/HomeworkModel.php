<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * HomeworkModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Homework_model.
 */
class HomeworkModel extends BaseModel
{
    protected $table = 'homework';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add($data)
    {
        return $this->callLegacyModel('Homework_model', 'add', func_get_args());
    }

    public function get($id = null)
    {
        return $this->callLegacyModel('Homework_model', 'get', func_get_args());
    }

    public function get_homeworkDocById($homework_id)
    {
        return $this->callLegacyModel('Homework_model', 'get_homeworkDocById', func_get_args());
    }

    public function get_homeworkDocByIdStdid($homework_id, $student_id)
    {
        return $this->callLegacyModel('Homework_model', 'get_homeworkDocByIdStdid', func_get_args());
    }

    public function search_homework($class_id, $section_id, $subject_group_id, $subject_id)
    {
        return $this->callLegacyModel('Homework_model', 'search_homework', func_get_args());
    }

    public function search_dthomework($class_id, $section_id, $subject_group_id, $subject_id)
    {
        return $this->callLegacyModel('Homework_model', 'search_dthomework', func_get_args());
    }

    public function search_closehomework($class_id, $section_id, $subject_group_id, $subject_id)
    {
        return $this->callLegacyModel('Homework_model', 'search_closehomework', func_get_args());
    }

    public function getRecord($id = null)
    {
        return $this->callLegacyModel('Homework_model', 'getRecord', func_get_args());
    }

    public function getStudents($id)
    {
        return $this->callLegacyModel('Homework_model', 'getStudents', func_get_args());
    }

    public function get_homeworkassignmentById($homework_id, $student_id)
    {
        return $this->callLegacyModel('Homework_model', 'get_homeworkassignmentById', func_get_args());
    }

    public function delete($id)
    {
        return $this->callLegacyModel('Homework_model', 'delete', func_get_args());
    }

    public function addEvaluation($insert_prev, $insert_array, $homework_id, $evaluation_date, $evaluated_by, $update_array)
    {
        return $this->callLegacyModel('Homework_model', 'addEvaluation', func_get_args());
    }

    public function searchHomeworkEvaluation($class_id, $section_id, $subject_id)
    {
        return $this->callLegacyModel('Homework_model', 'searchHomeworkEvaluation', func_get_args());
    }

    public function getEvaluationReport($id)
    {
        return $this->callLegacyModel('Homework_model', 'getEvaluationReport', func_get_args());
    }

    public function getEvaStudents($id, $class_id, $section_id)
    {
        return $this->callLegacyModel('Homework_model', 'getEvaStudents', func_get_args());
    }

    public function delete_evaluation($prev_students)
    {
        return $this->callLegacyModel('Homework_model', 'delete_evaluation', func_get_args());
    }

    public function count_students($class_id, $section_id)
    {
        return $this->callLegacyModel('Homework_model', 'count_students', func_get_args());
    }

    public function count_evalstudents($id, $class_id, $section_id)
    {
        return $this->callLegacyModel('Homework_model', 'count_evalstudents', func_get_args());
    }

    public function get_homeworkDoc($student_id)
    {
        return $this->callLegacyModel('Homework_model', 'get_homeworkDoc', func_get_args());
    }

    public function get_homeworkDocByhomework_id($homework_id)
    {
        return $this->callLegacyModel('Homework_model', 'get_homeworkDocByhomework_id', func_get_args());
    }

    public function getStudentHomeworkWithStatus($class_id, $section_id, $student_session_id)
    {
        return $this->callLegacyModel('Homework_model', 'getStudentHomeworkWithStatus', func_get_args());
    }

    public function getstudentclosedhomeworkwithstatus($class_id, $section_id, $student_session_id)
    {
        return $this->callLegacyModel('Homework_model', 'getstudentclosedhomeworkwithstatus', func_get_args());
    }

    public function getStudentHomework($class_id, $section_id)
    {
        return $this->callLegacyModel('Homework_model', 'getStudentHomework', func_get_args());
    }

    public function get_HomeworkSubject($subjectgroup_id)
    {
        return $this->callLegacyModel('Homework_model', 'get_HomeworkSubject', func_get_args());
    }

    public function upload_docs($data)
    {
        return $this->callLegacyModel('Homework_model', 'upload_docs', func_get_args());
    }

    public function get_upload_docs($array)
    {
        return $this->callLegacyModel('Homework_model', 'get_upload_docs', func_get_args());
    }

    public function getEvaluationReportForStudent($id, $student_id)
    {
        return $this->callLegacyModel('Homework_model', 'getEvaluationReportForStudent', func_get_args());
    }

    public function get_homeworkDocBystudentId($homework_id, $student_id)
    {
        return $this->callLegacyModel('Homework_model', 'get_homeworkDocBystudentId', func_get_args());
    }

    public function check_assignment($homework_id, $student_id)
    {
        return $this->callLegacyModel('Homework_model', 'check_assignment', func_get_args());
    }

    public function adddailyassignment($data)
    {
        return $this->callLegacyModel('Homework_model', 'adddailyassignment', func_get_args());
    }

    public function getdailyassignment($student_id, $student_session_id)
    {
        return $this->callLegacyModel('Homework_model', 'getdailyassignment', func_get_args());
    }

    public function getsingledailyassignment($assignment_id)
    {
        return $this->callLegacyModel('Homework_model', 'getsingledailyassignment', func_get_args());
    }

    public function deletedailyassignment($id)
    {
        return $this->callLegacyModel('Homework_model', 'deletedailyassignment', func_get_args());
    }

    public function searchdailyassignment($class_id, $section_id, $subject_group_id, $subject_group_subject_id, $date)
    {
        return $this->callLegacyModel('Homework_model', 'searchdailyassignment', func_get_args());
    }

    public function checkstatus($homework_id, $student_id)
    {
        return $this->callLegacyModel('Homework_model', 'checkstatus', func_get_args());
    }

    public function getStudentlist($id)
    {
        return $this->callLegacyModel('Homework_model', 'getStudentlist', func_get_args());
    }

    public function assignmentrecord($assigment_id)
    {
        return $this->callLegacyModel('Homework_model', 'assignmentrecord', func_get_args());
    }

    public function dailyassignmentreport($class_id, $section_id, $subject_group_id, $subject_group_subject_id, $condition = null)
    {
        return $this->callLegacyModel('Homework_model', 'dailyassignmentreport', func_get_args());
    }

    public function assignmentdetails($student_id, $condition, $subject_group_id)
    {
        return $this->callLegacyModel('Homework_model', 'assignmentdetails', func_get_args());
    }

    public function check_valid_marks($str)
    {
        return $this->callLegacyModel('Homework_model', 'check_valid_marks', func_get_args());
    }

    public function search_dthomeworkreport($class_id, $section_id, $subject_group_id, $subject_id)
    {
        return $this->callLegacyModel('Homework_model', 'search_dthomeworkreport', func_get_args());
    }

    public function get_submitted_homework($homework_id)
    {
        return $this->callLegacyModel('Homework_model', 'get_submitted_homework', func_get_args());
    }

    public function get_not_submitted_homework($class_id,$section_id,$homework_id)
    {
        return $this->callLegacyModel('Homework_model', 'get_not_submitted_homework', func_get_args());
    }

    public function get_student_homework_evaluation($id)
    {
        return $this->callLegacyModel('Homework_model', 'get_student_homework_evaluation', func_get_args());
    }

    public function search_homework_marks_report($class_id, $section_id, $subject_group_id, $subject_id)
    {
        return $this->callLegacyModel('Homework_model', 'search_homework_marks_report', func_get_args());
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