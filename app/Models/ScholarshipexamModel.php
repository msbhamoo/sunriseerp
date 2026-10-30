<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * ScholarshipexamModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Scholarshipexam_model.
 */
class ScholarshipexamModel extends BaseModel
{
    protected $table = 'scholarshipexam';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function checkAndSetupTables()
    {
        return $this->callLegacyModel('Scholarshipexam_model', 'checkAndSetupTables', func_get_args());
    }

    public function getExams($id = null)
    {
        return $this->callLegacyModel('Scholarshipexam_model', 'getExams', func_get_args());
    }

    public function getExamByCodeOrId($code_or_id)
    {
        return $this->callLegacyModel('Scholarshipexam_model', 'getExamByCodeOrId', func_get_args());
    }

    public function getSchedulesByExam($exam_id)
    {
        return $this->callLegacyModel('Scholarshipexam_model', 'getSchedulesByExam', func_get_args());
    }

    public function getCandidateCount($exam_id)
    {
        return $this->callLegacyModel('Scholarshipexam_model', 'getCandidateCount', func_get_args());
    }

    public function getQuestionCount($exam_id)
    {
        return $this->callLegacyModel('Scholarshipexam_model', 'getQuestionCount', func_get_args());
    }

    public function addExam($data, $schedules = array())
    {
        return $this->callLegacyModel('Scholarshipexam_model', 'addExam', func_get_args());
    }

    public function deleteExam($id)
    {
        return $this->callLegacyModel('Scholarshipexam_model', 'deleteExam', func_get_args());
    }

    public function getEligibleStudentsForExam($exam_id, $class_id, $section_id = null)
    {
        return $this->callLegacyModel('Scholarshipexam_model', 'getEligibleStudentsForExam', func_get_args());
    }

    public function registerCandidatesBatch($exam_id, $schedule_id, $student_session_ids, $prefix = 'OLY-')
    {
        return $this->callLegacyModel('Scholarshipexam_model', 'registerCandidatesBatch', func_get_args());
    }

    public function getCandidatesList($exam_id, $class_id = null, $section_id = null, $candidate_type = null)
    {
        return $this->callLegacyModel('Scholarshipexam_model', 'getCandidatesList', func_get_args());
    }

    public function getQuestionsForExam($exam_id)
    {
        return $this->callLegacyModel('Scholarshipexam_model', 'getQuestionsForExam', func_get_args());
    }

    public function getAvailableQuestions($subject_id = null, $class_ids = null, $keyword = null)
    {
        return $this->callLegacyModel('Scholarshipexam_model', 'getAvailableQuestions', func_get_args());
    }

    public function addQuestionToExam($exam_id, $question_id, $marks = 1.00, $neg_marks = 0.00)
    {
        return $this->callLegacyModel('Scholarshipexam_model', 'addQuestionToExam', func_get_args());
    }

    public function removeQuestionFromExam($exam_id, $question_id)
    {
        return $this->callLegacyModel('Scholarshipexam_model', 'removeQuestionFromExam', func_get_args());
    }

    public function saveMarksBatch($exam_id, $marks_data)
    {
        return $this->callLegacyModel('Scholarshipexam_model', 'saveMarksBatch', func_get_args());
    }

    public function calculateRanks($exam_id)
    {
        return $this->callLegacyModel('Scholarshipexam_model', 'calculateRanks', func_get_args());
    }

    public function getCandidateAdmitCardData($candidate_id)
    {
        return $this->callLegacyModel('Scholarshipexam_model', 'getCandidateAdmitCardData', func_get_args());
    }

    public function getDashboardStats()
    {
        return $this->callLegacyModel('Scholarshipexam_model', 'getDashboardStats', func_get_args());
    }

    public function getAllClasses()
    {
        return $this->callLegacyModel('Scholarshipexam_model', 'getAllClasses', func_get_args());
    }

    public function getSchoolSetting()
    {
        return $this->callLegacyModel('Scholarshipexam_model', 'getSchoolSetting', func_get_args());
    }

    public function getActiveExam()
    {
        return $this->callLegacyModel('Scholarshipexam_model', 'getActiveExam', func_get_args());
    }

    public function toggleExamStatus($id)
    {
        return $this->callLegacyModel('Scholarshipexam_model', 'toggleExamStatus', func_get_args());
    }

    public function toggleRegistrationStatus($id)
    {
        return $this->callLegacyModel('Scholarshipexam_model', 'toggleRegistrationStatus', func_get_args());
    }

    public function toggleAdmitCardStatus($id)
    {
        return $this->callLegacyModel('Scholarshipexam_model', 'toggleAdmitCardStatus', func_get_args());
    }

    public function toggleResultStatus($id)
    {
        return $this->callLegacyModel('Scholarshipexam_model', 'toggleResultStatus', func_get_args());
    }

    public function getFieldSettings()
    {
        return $this->callLegacyModel('Scholarshipexam_model', 'getFieldSettings', func_get_args());
    }

    public function saveFieldSettings($post_data)
    {
        return $this->callLegacyModel('Scholarshipexam_model', 'saveFieldSettings', func_get_args());
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