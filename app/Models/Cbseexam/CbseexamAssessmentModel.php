<?php

namespace App\Models\Cbseexam;

use App\Models\BaseModel;

/**
 * CbseexamAssessmentModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Cbseexam_assessment_model.
 */
class CbseexamAssessmentModel extends BaseModel
{
    protected $table = 'cbseexam_assessment';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function add($data)
    {
        return $this->callLegacyModel('Cbseexam_assessment_model', 'add', func_get_args());
    }

    public function add_type($data)
    {
        return $this->callLegacyModel('Cbseexam_assessment_model', 'add_type', func_get_args());
    }

    public function getassessmentlist()
    {
        return $this->callLegacyModel('Cbseexam_assessment_model', 'getassessmentlist', func_get_args());
    }

    public function get()
    {
        return $this->callLegacyModel('Cbseexam_assessment_model', 'get', func_get_args());
    }

    public function get_editdetails($id)
    {
        return $this->callLegacyModel('Cbseexam_assessment_model', 'get_editdetails', func_get_args());
    }

    public function get_assessmentTypebyId($id)
    {
        return $this->callLegacyModel('Cbseexam_assessment_model', 'get_assessmentTypebyId', func_get_args());
    }

    public function remove_assessment_type($id)
    {
        return $this->callLegacyModel('Cbseexam_assessment_model', 'remove_assessment_type', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Cbseexam_assessment_model', 'remove', func_get_args());
    }

    public function add_graderange($insert_grade, $cbse_exam_grades_range, $cbse_exam_grades_range_update, $delete_grade_range)
    {
        return $this->callLegacyModel('Cbseexam_assessment_model', 'add_graderange', func_get_args());
    }

    public function getWithAssessmentType($id)
    {
        return $this->callLegacyModel('Cbseexam_assessment_model', 'getWithAssessmentType', func_get_args());
    }

    public function getWithAssessmentTypeByAssessmentID($cbse_exam_assessment_id)
    {
        return $this->callLegacyModel('Cbseexam_assessment_model', 'getWithAssessmentTypeByAssessmentID', func_get_args());
    }

    public function getSubjectAssessmentsByExam($subjects)
    {
        return $this->callLegacyModel('Cbseexam_assessment_model', 'getSubjectAssessmentsByExam', func_get_args());
    }

    public function getSubjectAssessments($cbse_exam_timetable_id)
    {
        return $this->callLegacyModel('Cbseexam_assessment_model', 'getSubjectAssessments', func_get_args());
    }

    public function check_exists($str)
    {
        return $this->callLegacyModel('Cbseexam_assessment_model', 'check_exists', func_get_args());
    }

    public function check_data_exists($data)
    {
        return $this->callLegacyModel('Cbseexam_assessment_model', 'check_data_exists', func_get_args());
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
        return 'Cbseexam';
    }
}