<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * ExamresultController
 *
 * Auto-generated CI4 controller migrating CI3 Examresult.
 */
class ExamresultController extends BaseController
{
    public function printCard()
    {
        return $this->dispatchLegacy('Examresult', 'printCard', func_get_args());
    }

    public function admitcard()
    {
        return $this->dispatchLegacy('Examresult', 'admitcard', func_get_args());
    }

    public function marksheet()
    {
        return $this->dispatchLegacy('Examresult', 'marksheet', func_get_args());
    }

    public function pdftmarksheet()
    {
        return $this->dispatchLegacy('Examresult', 'pdftmarksheet', func_get_args());
    }

    public function printmarksheet()
    {
        return $this->dispatchLegacy('Examresult', 'printmarksheet', func_get_args());
    }

    public function index()
    {
        return $this->dispatchLegacy('Examresult', 'index', func_get_args());
    }

    public function updaterank()
    {
        return $this->dispatchLegacy('Examresult', 'updaterank', func_get_args());
    }

    public function examrank()
    {
        return $this->dispatchLegacy('Examresult', 'examrank', func_get_args());
    }

    public function getStudentByClassBatch()
    {
        return $this->dispatchLegacy('Examresult', 'getStudentByClassBatch', func_get_args());
    }

    public function getExamGroupByStudent()
    {
        return $this->dispatchLegacy('Examresult', 'getExamGroupByStudent', func_get_args());
    }

    public function studentresult()
    {
        return $this->dispatchLegacy('Examresult', 'studentresult', func_get_args());
    }

    public function getStudentCurrentResult()
    {
        return $this->dispatchLegacy('Examresult', 'getStudentCurrentResult', func_get_args());
    }

    public function generatemarksheet()
    {
        return $this->dispatchLegacy('Examresult', 'generatemarksheet', func_get_args());
    }

    public function rankreport()
    {
        return $this->dispatchLegacy('Examresult', 'rankreport', func_get_args());
    }

    public function examinations()
    {
        return $this->dispatchLegacy('Examresult', 'examinations', func_get_args());
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