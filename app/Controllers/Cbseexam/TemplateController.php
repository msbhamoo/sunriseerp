<?php

namespace App\Controllers\Cbseexam;

use App\Controllers\BaseController;

/**
 * TemplateController
 *
 * Auto-generated CI4 controller migrating CI3 Template.
 */
class TemplateController extends BaseController
{
    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Template', 'validateCanUploadFile', func_get_args());
    }

    public function index()
    {
        return $this->dispatchLegacy('Template', 'index', func_get_args());
    }

    public function create()
    {
        return $this->dispatchLegacy('Template', 'create', func_get_args());
    }

    public function edit_page($template_id)
    {
        return $this->dispatchLegacy('Template', 'edit_page', func_get_args());
    }

    public function add()
    {
        return $this->dispatchLegacy('Template', 'add', func_get_args());
    }

    public function edit()
    {
        return $this->dispatchLegacy('Template', 'edit', func_get_args());
    }

    public function getdata()
    {
        return $this->dispatchLegacy('Template', 'getdata', func_get_args());
    }

    public function viewtemplate()
    {
        return $this->dispatchLegacy('Template', 'viewtemplate', func_get_args());
    }

    public function get_ClassSectionByTermId($termid)
    {
        return $this->dispatchLegacy('Template', 'get_ClassSectionByTermId', func_get_args());
    }

    public function remove()
    {
        return $this->dispatchLegacy('Template', 'remove', func_get_args());
    }

    public function get_examdata()
    {
        return $this->dispatchLegacy('Template', 'get_examdata', func_get_args());
    }

    public function deleteimg()
    {
        return $this->dispatchLegacy('Template', 'deleteimg', func_get_args());
    }

    public function linkexams()
    {
        return $this->dispatchLegacy('Template', 'linkexams', func_get_args());
    }

    public function get()
    {
        return $this->dispatchLegacy('Template', 'get', func_get_args());
    }

    public function check_exams($field, $exams)
    {
        return $this->dispatchLegacy('Template', 'check_exams', func_get_args());
    }

    public function check_exam_subject_note($field, $exams)
    {
        return $this->dispatchLegacy('Template', 'check_exam_subject_note', func_get_args());
    }

    public function check_exam_grading($field, $exams)
    {
        return $this->dispatchLegacy('Template', 'check_exam_grading', func_get_args());
    }

    public function check_teacher_remark($field, $exams)
    {
        return $this->dispatchLegacy('Template', 'check_teacher_remark', func_get_args());
    }

    public function check_weightage($field, $weightage)
    {
        return $this->dispatchLegacy('Template', 'check_weightage', func_get_args());
    }

    public function handle_upload($str, $var)
    {
        return $this->dispatchLegacy('Template', 'handle_upload', func_get_args());
    }

    public function templatewiserank($template_id)
    {
        return $this->dispatchLegacy('Template', 'templatewiserank', func_get_args());
    }

    public function get_sections_by_multiple_classes()
    {
        return $this->dispatchLegacy('Template', 'get_sections_by_multiple_classes', func_get_args());
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