<?php

namespace App\Controllers\Cbseexam;

use App\Controllers\BaseController;

/**
 * MarksController
 *
 * Auto-generated CI4 controller migrating CI3 Marks.
 */
class MarksController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Marks', 'index', func_get_args());
    }

    public function get_sections_multi()
    {
        return $this->dispatchLegacy('Marks', 'get_sections_multi', func_get_args());
    }

    public function get_exams_multi()
    {
        return $this->dispatchLegacy('Marks', 'get_exams_multi', func_get_args());
    }

    public function subjectstudent_multi()
    {
        return $this->dispatchLegacy('Marks', 'subjectstudent_multi', func_get_args());
    }

    public function cbse_reportcard($exam_id = null, $class_id = null, $section_id = null)
    {
        return $this->dispatchLegacy('Marks', 'cbse_reportcard', func_get_args());
    }

    public function subject_max_marks()
    {
        return $this->dispatchLegacy('Marks', 'subject_max_marks', func_get_args());
    }

    public function save_subject_max_marks()
    {
        return $this->dispatchLegacy('Marks', 'save_subject_max_marks', func_get_args());
    }

    public function get_bulk_exam_classes()
    {
        return $this->dispatchLegacy('Marks', 'get_bulk_exam_classes', func_get_args());
    }

    public function export_bulk_template($exam_id = null, $class_id = null)
    {
        return $this->dispatchLegacy('Marks', 'export_bulk_template', func_get_args());
    }

    public function parse_bulk_marks()
    {
        return $this->dispatchLegacy('Marks', 'parse_bulk_marks', func_get_args());
    }

    public function import_bulk_marks()
    {
        return $this->dispatchLegacy('Marks', 'import_bulk_marks', func_get_args());
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