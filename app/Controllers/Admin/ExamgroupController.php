<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * ExamgroupController
 *
 * Auto-generated CI4 controller migrating CI3 Examgroup.
 */
class ExamgroupController extends BaseController
{
    public function exportformat()
    {
        return $this->dispatchLegacy('Examgroup', 'exportformat', func_get_args());
    }

    public function uploadfile()
    {
        return $this->dispatchLegacy('Examgroup', 'uploadfile', func_get_args());
    }

    public function handle_upload()
    {
        return $this->dispatchLegacy('Examgroup', 'handle_upload', func_get_args());
    }

    public function index()
    {
        return $this->dispatchLegacy('Examgroup', 'index', func_get_args());
    }

    public function getExamByExamgroup()
    {
        return $this->dispatchLegacy('Examgroup', 'getExamByExamgroup', func_get_args());
    }

    public function deleteExam()
    {
        return $this->dispatchLegacy('Examgroup', 'deleteExam', func_get_args());
    }

    public function exam($id)
    {
        return $this->dispatchLegacy('Examgroup', 'exam', func_get_args());
    }

    public function examresult($id)
    {
        return $this->dispatchLegacy('Examgroup', 'examresult', func_get_args());
    }

    public function addmark($id)
    {
        return $this->dispatchLegacy('Examgroup', 'addmark', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Examgroup', 'delete', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Examgroup', 'edit', func_get_args());
    }

    public function getByClassSection()
    {
        return $this->dispatchLegacy('Examgroup', 'getByClassSection', func_get_args());
    }

    public function addexam($id)
    {
        return $this->dispatchLegacy('Examgroup', 'addexam', func_get_args());
    }

    public function getNotAppliedDiscount($student_session_id)
    {
        return $this->dispatchLegacy('Examgroup', 'getNotAppliedDiscount', func_get_args());
    }

    public function subjectstudent()
    {
        return $this->dispatchLegacy('Examgroup', 'subjectstudent', func_get_args());
    }

    public function examstudent()
    {
        return $this->dispatchLegacy('Examgroup', 'examstudent', func_get_args());
    }

    public function ajaxaddexam()
    {
        return $this->dispatchLegacy('Examgroup', 'ajaxaddexam', func_get_args());
    }

    public function getExamsByExamGroup()
    {
        return $this->dispatchLegacy('Examgroup', 'getExamsByExamGroup', func_get_args());
    }

    public function entrymarks()
    {
        return $this->dispatchLegacy('Examgroup', 'entrymarks', func_get_args());
    }

    public function getexam()
    {
        return $this->dispatchLegacy('Examgroup', 'getexam', func_get_args());
    }

    public function connectexams()
    {
        return $this->dispatchLegacy('Examgroup', 'connectexams', func_get_args());
    }

    public function getExamByID()
    {
        return $this->dispatchLegacy('Examgroup', 'getExamByID', func_get_args());
    }

    public function getexamSubjects()
    {
        return $this->dispatchLegacy('Examgroup', 'getexamSubjects', func_get_args());
    }

    public function getSubjectByExam()
    {
        return $this->dispatchLegacy('Examgroup', 'getSubjectByExam', func_get_args());
    }

    public function getTeacherRemarkByExam()
    {
        return $this->dispatchLegacy('Examgroup', 'getTeacherRemarkByExam', func_get_args());
    }

    public function addexamsubject()
    {
        return $this->dispatchLegacy('Examgroup', 'addexamsubject', func_get_args());
    }

    public function assign($id)
    {
        return $this->dispatchLegacy('Examgroup', 'assign', func_get_args());
    }

    public function addstudent()
    {
        return $this->dispatchLegacy('Examgroup', 'addstudent', func_get_args());
    }

    public function ajaxConnectForm()
    {
        return $this->dispatchLegacy('Examgroup', 'ajaxConnectForm', func_get_args());
    }

    public function compare_multi_Arrays($array1, $array2)
    {
        return $this->dispatchLegacy('Examgroup', 'compare_multi_Arrays', func_get_args());
    }

    public function getExamGroupByClassSection()
    {
        return $this->dispatchLegacy('Examgroup', 'getExamGroupByClassSection', func_get_args());
    }

    public function entrystudents()
    {
        return $this->dispatchLegacy('Examgroup', 'entrystudents', func_get_args());
    }

    public function saveexamremark()
    {
        return $this->dispatchLegacy('Examgroup', 'saveexamremark', func_get_args());
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