<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * ScholarshipexamController
 *
 * Auto-generated CI4 controller migrating CI3 Scholarshipexam.
 */
class ScholarshipexamController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Scholarshipexam', 'index', func_get_args());
    }

    public function exams()
    {
        return $this->dispatchLegacy('Scholarshipexam', 'exams', func_get_args());
    }

    public function save_exam()
    {
        return $this->dispatchLegacy('Scholarshipexam', 'save_exam', func_get_args());
    }

    public function delete_exam($id)
    {
        return $this->dispatchLegacy('Scholarshipexam', 'delete_exam', func_get_args());
    }

    public function get_exam_details($id)
    {
        return $this->dispatchLegacy('Scholarshipexam', 'get_exam_details', func_get_args());
    }

    public function toggle_exam_status($id)
    {
        return $this->dispatchLegacy('Scholarshipexam', 'toggle_exam_status', func_get_args());
    }

    public function toggle_registration_status($id)
    {
        return $this->dispatchLegacy('Scholarshipexam', 'toggle_registration_status', func_get_args());
    }

    public function toggle_admit_card_status($id)
    {
        return $this->dispatchLegacy('Scholarshipexam', 'toggle_admit_card_status', func_get_args());
    }

    public function toggle_result_status($id)
    {
        return $this->dispatchLegacy('Scholarshipexam', 'toggle_result_status', func_get_args());
    }

    public function questions()
    {
        return $this->dispatchLegacy('Scholarshipexam', 'questions', func_get_args());
    }

    public function add_question()
    {
        return $this->dispatchLegacy('Scholarshipexam', 'add_question', func_get_args());
    }

    public function remove_question()
    {
        return $this->dispatchLegacy('Scholarshipexam', 'remove_question', func_get_args());
    }

    public function print_paper($exam_id)
    {
        return $this->dispatchLegacy('Scholarshipexam', 'print_paper', func_get_args());
    }

    public function brochure($exam_id)
    {
        return $this->dispatchLegacy('Scholarshipexam', 'brochure', func_get_args());
    }

    public function candidates()
    {
        return $this->dispatchLegacy('Scholarshipexam', 'candidates', func_get_args());
    }

    public function register_candidates()
    {
        return $this->dispatchLegacy('Scholarshipexam', 'register_candidates', func_get_args());
    }

    public function admitcard($candidate_id = null)
    {
        return $this->dispatchLegacy('Scholarshipexam', 'admitcard', func_get_args());
    }

    public function marks()
    {
        return $this->dispatchLegacy('Scholarshipexam', 'marks', func_get_args());
    }

    public function save_marks()
    {
        return $this->dispatchLegacy('Scholarshipexam', 'save_marks', func_get_args());
    }

    public function certificates()
    {
        return $this->dispatchLegacy('Scholarshipexam', 'certificates', func_get_args());
    }

    public function report()
    {
        return $this->dispatchLegacy('Scholarshipexam', 'report', func_get_args());
    }

    public function print_certificate($candidate_id)
    {
        return $this->dispatchLegacy('Scholarshipexam', 'print_certificate', func_get_args());
    }

    public function setting()
    {
        return $this->dispatchLegacy('Scholarshipexam', 'setting', func_get_args());
    }

    protected function dispatchLegacy(string $controller, string $method, array $params)
    {
        // Set up compatibility environment
        $_SERVER['CI4_LEGACY_CONTROLLER'] = $controller;
        $_SERVER['CI4_LEGACY_METHOD'] = $method;
        $_SERVER['CI4_LEGACY_PARAMS'] = $params;

        require_once APPPATH . '../application/core/MY_Controller.php';
        $target = APPPATH . '../application/controllers/admin/' . $controller . '.php';
        if (file_exists($target)) {
            require_once $target;
            $instance = new $controller();
            return call_user_func_array([$instance, $method], $params);
        }

        return redirect()->to(base_url('admin/unauthorized'));
    }
}