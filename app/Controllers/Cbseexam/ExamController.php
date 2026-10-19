<?php

namespace App\Controllers\Cbseexam;

use App\Controllers\BaseController;

/**
 * ExamController
 *
 * Auto-generated CI4 controller migrating CI3 Exam.
 */
class ExamController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Exam', 'index', func_get_args());
    }

    public function wizard($id = null)
    {
        return $this->dispatchLegacy('Exam', 'wizard', func_get_args());
    }

    public function wizard_load_timetable()
    {
        return $this->dispatchLegacy('Exam', 'wizard_load_timetable', func_get_args());
    }

    public function save_wizard()
    {
        return $this->dispatchLegacy('Exam', 'save_wizard', func_get_args());
    }

    public function toggle_publish()
    {
        return $this->dispatchLegacy('Exam', 'toggle_publish', func_get_args());
    }

    public function read($id)
    {
        return $this->dispatchLegacy('Exam', 'read', func_get_args());
    }

    public function getByClasssection()
    {
        return $this->dispatchLegacy('Exam', 'getByClasssection', func_get_args());
    }

    public function examstudent()
    {
        return $this->dispatchLegacy('Exam', 'examstudent', func_get_args());
    }

    public function getexamSubjects()
    {
        return $this->dispatchLegacy('Exam', 'getexamSubjects', func_get_args());
    }

    public function exam_rank()
    {
        return $this->dispatchLegacy('Exam', 'exam_rank', func_get_args());
    }

    public function exam_ajax_rank()
    {
        return $this->dispatchLegacy('Exam', 'exam_ajax_rank', func_get_args());
    }

    public function examrankgenerate()
    {
        return $this->dispatchLegacy('Exam', 'examrankgenerate', func_get_args());
    }

    public function updateExamRank($exam_id)
    {
        return $this->dispatchLegacy('Exam', 'updateExamRank', func_get_args());
    }

    public function examwiserank($exam_id)
    {
        return $this->dispatchLegacy('Exam', 'examwiserank', func_get_args());
    }

    public function rank()
    {
        return $this->dispatchLegacy('Exam', 'rank', func_get_args());
    }

    public function term_wise($cbse_template_id, $students)
    {
        return $this->dispatchLegacy('Exam', 'term_wise', func_get_args());
    }

    public function all_term($cbse_template_id, $students)
    {
        return $this->dispatchLegacy('Exam', 'all_term', func_get_args());
    }

    public function getExamAssesment($array, $find_cbse_term_id)
    {
        return $this->dispatchLegacy('Exam', 'getExamAssesment', func_get_args());
    }

    public function multi_exam_without_term($cbse_template_id, $students)
    {
        return $this->dispatchLegacy('Exam', 'multi_exam_without_term', func_get_args());
    }

    public function exam_wise_rank($cbse_template_id, $cbse_exam_id, $students)
    {
        return $this->dispatchLegacy('Exam', 'exam_wise_rank', func_get_args());
    }

    public function rank_ajax()
    {
        return $this->dispatchLegacy('Exam', 'rank_ajax', func_get_args());
    }

    public function rankgenerate()
    {
        return $this->dispatchLegacy('Exam', 'rankgenerate', func_get_args());
    }

    public function add()
    {
        return $this->dispatchLegacy('Exam', 'add', func_get_args());
    }

    public function get_editdetails()
    {
        return $this->dispatchLegacy('Exam', 'get_editdetails', func_get_args());
    }

    public function remove($id)
    {
        return $this->dispatchLegacy('Exam', 'remove', func_get_args());
    }

    public function add_exam()
    {
        return $this->dispatchLegacy('Exam', 'add_exam', func_get_args());
    }

    public function entrystudents()
    {
        return $this->dispatchLegacy('Exam', 'entrystudents', func_get_args());
    }

    public function addexamsubject()
    {
        return $this->dispatchLegacy('Exam', 'addexamsubject', func_get_args());
    }

    public function getSubjectByExam()
    {
        return $this->dispatchLegacy('Exam', 'getSubjectByExam', func_get_args());
    }

    public function subjectstudent()
    {
        return $this->dispatchLegacy('Exam', 'subjectstudent', func_get_args());
    }

    public function teacherRemark()
    {
        return $this->dispatchLegacy('Exam', 'teacherRemark', func_get_args());
    }

    public function entrymarks()
    {
        return $this->dispatchLegacy('Exam', 'entrymarks', func_get_args());
    }

    public function exam_attendance()
    {
        return $this->dispatchLegacy('Exam', 'exam_attendance', func_get_args());
    }

    public function exam_attendance_search()
    {
        return $this->dispatchLegacy('Exam', 'exam_attendance_search', func_get_args());
    }

    public function get_observation_parameter()
    {
        return $this->dispatchLegacy('Exam', 'get_observation_parameter', func_get_args());
    }

    public function addattendance()
    {
        return $this->dispatchLegacy('Exam', 'addattendance', func_get_args());
    }

    public function check_teacher_remark($field, $marks)
    {
        return $this->dispatchLegacy('Exam', 'check_teacher_remark', func_get_args());
    }

    public function addteacherremark()
    {
        return $this->dispatchLegacy('Exam', 'addteacherremark', func_get_args());
    }

    public function get_exam()
    {
        return $this->dispatchLegacy('Exam', 'get_exam', func_get_args());
    }

    public function edit()
    {
        return $this->dispatchLegacy('Exam', 'edit', func_get_args());
    }

    public function deleteexam()
    {
        return $this->dispatchLegacy('Exam', 'deleteexam', func_get_args());
    }

    public function generate_rank()
    {
        return $this->dispatchLegacy('Exam', 'generate_rank', func_get_args());
    }

    public function examtimetable()
    {
        return $this->dispatchLegacy('Exam', 'examtimetable', func_get_args());
    }

    public function get_examtimetable_matrix()
    {
        return $this->dispatchLegacy('Exam', 'get_examtimetable_matrix', func_get_args());
    }

    public function uploadfile()
    {
        return $this->dispatchLegacy('Exam', 'uploadfile', func_get_args());
    }

    public function handle_upload()
    {
        return $this->dispatchLegacy('Exam', 'handle_upload', func_get_args());
    }

    public function exportformat()
    {
        return $this->dispatchLegacy('Exam', 'exportformat', func_get_args());
    }

    public function marks($exam_id)
    {
        return $this->dispatchLegacy('Exam', 'marks', func_get_args());
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