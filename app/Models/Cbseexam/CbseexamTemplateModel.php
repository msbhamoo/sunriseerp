<?php

namespace App\Models\Cbseexam;

use App\Models\BaseModel;

/**
 * CbseexamTemplateModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Cbseexam_template_model.
 */
class CbseexamTemplateModel extends BaseModel
{
    protected $table = 'cbseexam_template';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function delete_img($data)
    {
        return $this->callLegacyModel('Cbseexam_template_model', 'delete_img', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Cbseexam_template_model', 'add', func_get_args());
    }

    public function add_class_section($data)
    {
        return $this->callLegacyModel('Cbseexam_template_model', 'add_class_section', func_get_args());
    }

    public function deleteclasssectionbytemplateid($template_id)
    {
        return $this->callLegacyModel('Cbseexam_template_model', 'deleteclasssectionbytemplateid', func_get_args());
    }

    public function delete_class_section($id)
    {
        return $this->callLegacyModel('Cbseexam_template_model', 'delete_class_section', func_get_args());
    }

    public function gettemplatelist()
    {
        return $this->callLegacyModel('Cbseexam_template_model', 'gettemplatelist', func_get_args());
    }

    public function gettermbyid($id)
    {
        return $this->callLegacyModel('Cbseexam_template_model', 'gettermbyid', func_get_args());
    }

    public function getClassSectionByTermId($term_id)
    {
        return $this->callLegacyModel('Cbseexam_template_model', 'getClassSectionByTermId', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Cbseexam_template_model', 'remove', func_get_args());
    }

    public function all_term_old($template_id)
    {
        return $this->callLegacyModel('Cbseexam_template_model', 'all_term_old', func_get_args());
    }

    public function all_term($template_id)
    {
        return $this->callLegacyModel('Cbseexam_template_model', 'all_term', func_get_args());
    }

    public function getTermByTemplateId($template_id)
    {
        return $this->callLegacyModel('Cbseexam_template_model', 'getTermByTemplateId', func_get_args());
    }

    public function getTemplateTermExamWithAssessment($cbse_template_id)
    {
        return $this->callLegacyModel('Cbseexam_template_model', 'getTemplateTermExamWithAssessment', func_get_args());
    }

    public function getTermExamsWithAssessmentAndAssmentTypes($cbse_template_id, $cbse_term_id)
    {
        return $this->callLegacyModel('Cbseexam_template_model', 'getTermExamsWithAssessmentAndAssmentTypes', func_get_args());
    }

    public function get_templatedata($template_id)
    {
        return $this->callLegacyModel('Cbseexam_template_model', 'get_templatedata', func_get_args());
    }

    public function getTemplateTermsOrExam($template_id)
    {
        return $this->callLegacyModel('Cbseexam_template_model', 'getTemplateTermsOrExam', func_get_args());
    }

    public function getTemplateWithTermWithExams($template_id)
    {
        return $this->callLegacyModel('Cbseexam_template_model', 'getTemplateWithTermWithExams', func_get_args());
    }

    public function delete_template_record($template_id)
    {
        return $this->callLegacyModel('Cbseexam_template_model', 'delete_template_record', func_get_args());
    }

    public function cbse_template_terms($cbse_template_terms)
    {
        return $this->callLegacyModel('Cbseexam_template_model', 'cbse_template_terms', func_get_args());
    }

    public function cbse_template_term_exams($cbse_template_term_exams)
    {
        return $this->callLegacyModel('Cbseexam_template_model', 'cbse_template_term_exams', func_get_args());
    }

    public function getTemplateListbyclasssectionid($class_section_id)
    {
        return $this->callLegacyModel('Cbseexam_template_model', 'getTemplateListbyclasssectionid', func_get_args());
    }

    public function get($template_id)
    {
        return $this->callLegacyModel('Cbseexam_template_model', 'get', func_get_args());
    }

    public function getclasssection($template_id)
    {
        return $this->callLegacyModel('Cbseexam_template_model', 'getclasssection', func_get_args());
    }

    public function getStudentExamsResult($cbse_template_id)
    {
        return $this->callLegacyModel('Cbseexam_template_model', 'getStudentExamsResult', func_get_args());
    }

    public function get_single_exam_template_by_sectionid($class_section_id)
    {
        return $this->callLegacyModel('Cbseexam_template_model', 'get_single_exam_template_by_sectionid', func_get_args());
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