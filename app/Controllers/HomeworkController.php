<?php

namespace App\Controllers\.;

use App\Controllers\BaseController;

/**
 * HomeworkController
 *
 * Auto-generated CI4 controller migrating CI3 Homework.
 */
class HomeworkController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Homework', 'index', func_get_args());
    }

    public function searchvalidation()
    {
        return $this->dispatchLegacy('Homework', 'searchvalidation', func_get_args());
    }

    public function dthomeworklist()
    {
        return $this->dispatchLegacy('Homework', 'dthomeworklist', func_get_args());
    }

    public function closehomeworklist()
    {
        return $this->dispatchLegacy('Homework', 'closehomeworklist', func_get_args());
    }

    public function homework_docs($id)
    {
        return $this->dispatchLegacy('Homework', 'homework_docs', func_get_args());
    }

    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Homework', 'validateCanUploadFile', func_get_args());
    }

    public function create()
    {
        return $this->dispatchLegacy('Homework', 'create', func_get_args());
    }

    public function handle_upload()
    {
        return $this->dispatchLegacy('Homework', 'handle_upload', func_get_args());
    }

    public function getRecord()
    {
        return $this->dispatchLegacy('Homework', 'getRecord', func_get_args());
    }

    public function edit()
    {
        return $this->dispatchLegacy('Homework', 'edit', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Homework', 'delete', func_get_args());
    }

    public function download($id)
    {
        return $this->dispatchLegacy('Homework', 'download', func_get_args());
    }

    public function evaluation($id)
    {
        return $this->dispatchLegacy('Homework', 'evaluation', func_get_args());
    }

    public function add_evaluation()
    {
        return $this->dispatchLegacy('Homework', 'add_evaluation', func_get_args());
    }

    public function evaluation_report()
    {
        return $this->dispatchLegacy('Homework', 'evaluation_report', func_get_args());
    }

    public function getreport($id = 1)
    {
        return $this->dispatchLegacy('Homework', 'getreport', func_get_args());
    }

    public function count_percentage($id, $class_id, $section_id)
    {
        return $this->dispatchLegacy('Homework', 'count_percentage', func_get_args());
    }

    public function getClass()
    {
        return $this->dispatchLegacy('Homework', 'getClass', func_get_args());
    }

    public function assigmnetDownload($id)
    {
        return $this->dispatchLegacy('Homework', 'assigmnetDownload', func_get_args());
    }

    public function deletehomework()
    {
        return $this->dispatchLegacy('Homework', 'deletehomework', func_get_args());
    }

    public function dailyassignment()
    {
        return $this->dispatchLegacy('Homework', 'dailyassignment', func_get_args());
    }

    public function searchdailyassignment()
    {
        return $this->dispatchLegacy('Homework', 'searchdailyassignment', func_get_args());
    }

    public function assignmentvalidation()
    {
        return $this->dispatchLegacy('Homework', 'assignmentvalidation', func_get_args());
    }

    public function getdailyassignmentdetails()
    {
        return $this->dispatchLegacy('Homework', 'getdailyassignmentdetails', func_get_args());
    }

    public function dailyassigmnetdownload($id)
    {
        return $this->dispatchLegacy('Homework', 'dailyassigmnetdownload', func_get_args());
    }

    public function submitassignmentremark()
    {
        return $this->dispatchLegacy('Homework', 'submitassignmentremark', func_get_args());
    }

    public function assignmentdetails()
    {
        return $this->dispatchLegacy('Homework', 'assignmentdetails', func_get_args());
    }

    public function homeworkreport()
    {
        return $this->dispatchLegacy('Homework', 'homeworkreport', func_get_args());
    }

    public function getStudentByClassSection()
    {
        return $this->dispatchLegacy('Homework', 'getStudentByClassSection', func_get_args());
    }

    public function homeworkordailyassignmentreport()
    {
        return $this->dispatchLegacy('Homework', 'homeworkordailyassignmentreport', func_get_args());
    }

    public function dailyassignmentreport()
    {
        return $this->dispatchLegacy('Homework', 'dailyassignmentreport', func_get_args());
    }

    public function searchdailyassignmentreport()
    {
        return $this->dispatchLegacy('Homework', 'searchdailyassignmentreport', func_get_args());
    }

    public function dailyassignmentdetails()
    {
        return $this->dispatchLegacy('Homework', 'dailyassignmentdetails', func_get_args());
    }

    public function dailyassignmentreportvalidation()
    {
        return $this->dispatchLegacy('Homework', 'dailyassignmentreportvalidation', func_get_args());
    }

    public function homework_marksreport()
    {
        return $this->dispatchLegacy('Homework', 'homework_marksreport', func_get_args());
    }

    protected function dispatchLegacy(string $controller, string $method, array $params)
    {
        // Set up compatibility environment
        $_SERVER['CI4_LEGACY_CONTROLLER'] = $controller;
        $_SERVER['CI4_LEGACY_METHOD'] = $method;
        $_SERVER['CI4_LEGACY_PARAMS'] = $params;

        require_once APPPATH . '../application/core/MY_Controller.php';
        $target = APPPATH . '../application/controllers/./' . $controller . '.php';
        if (file_exists($target)) {
            require_once $target;
            $instance = new $controller();
            return call_user_func_array([$instance, $method], $params);
        }

        return redirect()->to(base_url('admin/unauthorized'));
    }
}