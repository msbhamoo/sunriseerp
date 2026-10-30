<?php

namespace App\Controllers\Cbseexam;

use App\Controllers\BaseController;

/**
 * ReportController
 *
 * Auto-generated CI4 controller migrating CI3 Report.
 */
class ReportController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Report', 'index', func_get_args());
    }

    public function rollnumber()
    {
        return $this->dispatchLegacy('Report', 'rollnumber', func_get_args());
    }

    public function marksstatus()
    {
        return $this->dispatchLegacy('Report', 'marksstatus', func_get_args());
    }

    public function teachercompliance()
    {
        return $this->dispatchLegacy('Report', 'teachercompliance', func_get_args());
    }

    public function gradedistribution()
    {
        return $this->dispatchLegacy('Report', 'gradedistribution', func_get_args());
    }

    public function toprankers()
    {
        return $this->dispatchLegacy('Report', 'toprankers', func_get_args());
    }

    public function examsubject()
    {
        return $this->dispatchLegacy('Report', 'examsubject', func_get_args());
    }

    public function templatewise()
    {
        return $this->dispatchLegacy('Report', 'templatewise', func_get_args());
    }

    public function getTemplatewiseExams()
    {
        return $this->dispatchLegacy('Report', 'getTemplatewiseExams', func_get_args());
    }

    public function getTermTemplateWise()
    {
        return $this->dispatchLegacy('Report', 'getTermTemplateWise', func_get_args());
    }

    public function getTemplateWiseResult()
    {
        return $this->dispatchLegacy('Report', 'getTemplateWiseResult', func_get_args());
    }

    public function getSinglTerm($cbse_template_id,$class_section_id)
    {
        return $this->dispatchLegacy('Report', 'getSinglTerm', func_get_args());
    }

    public function getMultiTerm($cbse_template_id,$class_section_id)
    {
        return $this->dispatchLegacy('Report', 'getMultiTerm', func_get_args());
    }

    public function getSingleExam($exam_id,$class_section_id)
    {
        return $this->dispatchLegacy('Report', 'getSingleExam', func_get_args());
    }

    public function getMultiexam($template_id,$class_section_id)
    {
        return $this->dispatchLegacy('Report', 'getMultiexam', func_get_args());
    }

    public function getExamAssesmentByTerm($array, $find_cbse_term_id)
    {
        return $this->dispatchLegacy('Report', 'getExamAssesmentByTerm', func_get_args());
    }

    public function getExamAssesment($array, $find_cbse_term_id)
    {
        return $this->dispatchLegacy('Report', 'getExamAssesment', func_get_args());
    }

    protected function dispatchLegacy(string $controller, string $method, array $params)
    {
        // Set up compatibility environment
        $_SERVER['CI4_LEGACY_CONTROLLER'] = $controller;
        $_SERVER['CI4_LEGACY_METHOD'] = $method;
        $_SERVER['CI4_LEGACY_PARAMS'] = $params;

        require_once APPPATH . '../application/core/MY_Controller.php';
        $target = APPPATH . '../application/controllers/cbseexam/' . $controller . '.php';
        if (file_exists($target)) {
            require_once $target;
            $instance = new $controller();
            return call_user_func_array([$instance, $method], $params);
        }

        return redirect()->to(base_url('admin/unauthorized'));
    }
}