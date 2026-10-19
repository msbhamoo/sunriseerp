<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * OnlineexamController
 *
 * Auto-generated CI4 controller migrating CI3 Onlineexam.
 */
class OnlineexamController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Onlineexam', 'index', func_get_args());
    }

    public function getexamlist()
    {
        return $this->dispatchLegacy('Onlineexam', 'getexamlist', func_get_args());
    }

    public function getclosedexamlist()
    {
        return $this->dispatchLegacy('Onlineexam', 'getclosedexamlist', func_get_args());
    }

    public function evalution($id)
    {
        return $this->dispatchLegacy('Onlineexam', 'evalution', func_get_args());
    }

    public function getDescQues()
    {
        return $this->dispatchLegacy('Onlineexam', 'getDescQues', func_get_args());
    }

    public function assign($id)
    {
        return $this->dispatchLegacy('Onlineexam', 'assign', func_get_args());
    }

    public function addstudent()
    {
        return $this->dispatchLegacy('Onlineexam', 'addstudent', func_get_args());
    }

    public function getOnlineExamByID()
    {
        return $this->dispatchLegacy('Onlineexam', 'getOnlineExamByID', func_get_args());
    }

    public function searchQuestionByExamID()
    {
        return $this->dispatchLegacy('Onlineexam', 'searchQuestionByExamID', func_get_args());
    }

    public function rankgenerate()
    {
        return $this->dispatchLegacy('Onlineexam', 'rankgenerate', func_get_args());
    }

    public function add()
    {
        return $this->dispatchLegacy('Onlineexam', 'add', func_get_args());
    }

    public function getRecord($id)
    {
        return $this->dispatchLegacy('Onlineexam', 'getRecord', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Onlineexam', 'delete', func_get_args());
    }

    public function saverank()
    {
        return $this->dispatchLegacy('Onlineexam', 'saverank', func_get_args());
    }

    public function fillmarks()
    {
        return $this->dispatchLegacy('Onlineexam', 'fillmarks', func_get_args());
    }

    public function validate_duration($str)
    {
        return $this->dispatchLegacy('Onlineexam', 'validate_duration', func_get_args());
    }

    public function validate_word_limit($str)
    {
        return $this->dispatchLegacy('Onlineexam', 'validate_word_limit', func_get_args());
    }

    public function validate_marks($str)
    {
        return $this->dispatchLegacy('Onlineexam', 'validate_marks', func_get_args());
    }

    public function questionAdd()
    {
        return $this->dispatchLegacy('Onlineexam', 'questionAdd', func_get_args());
    }

    public function deleteExamQuestions()
    {
        return $this->dispatchLegacy('Onlineexam', 'deleteExamQuestions', func_get_args());
    }

    public function report()
    {
        return $this->dispatchLegacy('Onlineexam', 'report', func_get_args());
    }

    public function getstudentresult()
    {
        return $this->dispatchLegacy('Onlineexam', 'getstudentresult', func_get_args());
    }

    public function download_exam()
    {
        return $this->dispatchLegacy('Onlineexam', 'download_exam', func_get_args());
    }

    public function getExamQuestions()
    {
        return $this->dispatchLegacy('Onlineexam', 'getExamQuestions', func_get_args());
    }

    public function downloadattachment($doc)
    {
        return $this->dispatchLegacy('Onlineexam', 'downloadattachment', func_get_args());
    }

    public function searchloginvalidation()
    {
        return $this->dispatchLegacy('Onlineexam', 'searchloginvalidation', func_get_args());
    }

    public function dtreportlist()
    {
        return $this->dispatchLegacy('Onlineexam', 'dtreportlist', func_get_args());
    }

    public function ajax_delete()
    {
        return $this->dispatchLegacy('Onlineexam', 'ajax_delete', func_get_args());
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