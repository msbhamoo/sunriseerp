<?php

namespace App\Models\Cbseexam;

use App\Models\BaseModel;

/**
 * CbseexamResultModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Cbseexam_result_model.
 */
class CbseexamResultModel extends BaseModel
{
    protected $table = 'cbseexam_result';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function marksheet_type()
    {
        return $this->callLegacyModel('Cbseexam_result_model', 'marksheet_type', func_get_args());
    }

    public function searchStudents($class_section_id)
    {
        return $this->callLegacyModel('Cbseexam_result_model', 'searchStudents', func_get_args());
    }

    public function searchTemplateStudents($class_section_id,$cbse_template_id)
    {
        return $this->callLegacyModel('Cbseexam_result_model', 'searchTemplateStudents', func_get_args());
    }

    public function searchTemplateStudentsWithTerm($class_section_id,$cbse_template_id)
    {
        return $this->callLegacyModel('Cbseexam_result_model', 'searchTemplateStudentsWithTerm', func_get_args());
    }

    public function getTemplateStudents($cbse_template_id)
    {
        return $this->callLegacyModel('Cbseexam_result_model', 'getTemplateStudents', func_get_args());
    }

    public function get_grade($mark,$grade_data)
    {
        return $this->callLegacyModel('Cbseexam_result_model', 'get_grade', func_get_args());
    }

    public function templatedata($cbse_template_id)
    {
        return $this->callLegacyModel('Cbseexam_result_model', 'templatedata', func_get_args());
    }

    public function getresult($student_session_id,$cbse_template_id)
    {
        return $this->callLegacyModel('Cbseexam_result_model', 'getresult', func_get_args());
    }

    public function observation_result($student_session_id)
    {
        return $this->callLegacyModel('Cbseexam_result_model', 'observation_result', func_get_args());
    }

    public function get_attendance($student_session_id)
    {
        return $this->callLegacyModel('Cbseexam_result_model', 'get_attendance', func_get_args());
    }

    public function getmarksheettypebyid($id)
    {
        return $this->callLegacyModel('Cbseexam_result_model', 'getmarksheettypebyid', func_get_args());
    }

    public function termwise($class_section_id)
    {
        return $this->callLegacyModel('Cbseexam_result_model', 'termwise', func_get_args());
    }

    public function examwise($class_section_id)
    {
        return $this->callLegacyModel('Cbseexam_result_model', 'examwise', func_get_args());
    }

    public function get_student_detail($student_session_id)
    {
        return $this->callLegacyModel('Cbseexam_result_model', 'get_student_detail', func_get_args());
    }

    public function gettermwiseresult($student_session_id)
    {
        return $this->callLegacyModel('Cbseexam_result_model', 'gettermwiseresult', func_get_args());
    }

    public function getexamwiseresult($student_session_id)
    {
        return $this->callLegacyModel('Cbseexam_result_model', 'getexamwiseresult', func_get_args());
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
        return 'cbseexam';
    }
}