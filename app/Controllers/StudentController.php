<?php

namespace App\Controllers\.;

use App\Controllers\BaseController;

/**
 * StudentController
 *
 * Auto-generated CI4 controller migrating CI3 Student.
 */
class StudentController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Student', 'index', func_get_args());
    }

    public function generaterollno()
    {
        return $this->dispatchLegacy('Student', 'generaterollno', func_get_args());
    }

    public function multiclass()
    {
        return $this->dispatchLegacy('Student', 'multiclass', func_get_args());
    }

    public function download($student_id, $doc_id)
    {
        return $this->dispatchLegacy('Student', 'download', func_get_args());
    }

    public function view($id)
    {
        return $this->dispatchLegacy('Student', 'view', func_get_args());
    }

    public function exportformat()
    {
        return $this->dispatchLegacy('Student', 'exportformat', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Student', 'delete', func_get_args());
    }

    public function doc_delete($id, $student_id)
    {
        return $this->dispatchLegacy('Student', 'doc_delete', func_get_args());
    }

    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Student', 'validateCanUploadFile', func_get_args());
    }

    public function create()
    {
        return $this->dispatchLegacy('Student', 'create', func_get_args());
    }

    public function duplicate_class_section($value)
    {
        return $this->dispatchLegacy('Student', 'duplicate_class_section', func_get_args());
    }

    public function ____________________________________create()
    {
        return $this->dispatchLegacy('Student', '____________________________________create', func_get_args());
    }

    public function multihandle_upload($str, $var)
    {
        return $this->dispatchLegacy('Student', 'multihandle_upload', func_get_args());
    }

    public function create_doc()
    {
        return $this->dispatchLegacy('Student', 'create_doc', func_get_args());
    }

    public function handle_uploadcreate_doc()
    {
        return $this->dispatchLegacy('Student', 'handle_uploadcreate_doc', func_get_args());
    }

    public function handle_father_upload()
    {
        return $this->dispatchLegacy('Student', 'handle_father_upload', func_get_args());
    }

    public function handle_mother_upload()
    {
        return $this->dispatchLegacy('Student', 'handle_mother_upload', func_get_args());
    }

    public function handle_guardian_upload()
    {
        return $this->dispatchLegacy('Student', 'handle_guardian_upload', func_get_args());
    }

    public function sendpassword()
    {
        return $this->dispatchLegacy('Student', 'sendpassword', func_get_args());
    }

    public function send_parent_password()
    {
        return $this->dispatchLegacy('Student', 'send_parent_password', func_get_args());
    }

    public function import()
    {
        return $this->dispatchLegacy('Student', 'import', func_get_args());
    }

    public function handle_csv_upload()
    {
        return $this->dispatchLegacy('Student', 'handle_csv_upload', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Student', 'edit', func_get_args());
    }

    public function bulkdelete()
    {
        return $this->dispatchLegacy('Student', 'bulkdelete', func_get_args());
    }

    public function search()
    {
        return $this->dispatchLegacy('Student', 'search', func_get_args());
    }

    public function ajaxsearch()
    {
        return $this->dispatchLegacy('Student', 'ajaxsearch', func_get_args());
    }

    public function getFeeGroupsByClass_ajax()
    {
        return $this->dispatchLegacy('Student', 'getFeeGroupsByClass_ajax', func_get_args());
    }

    public function getByClassAndSection()
    {
        return $this->dispatchLegacy('Student', 'getByClassAndSection', func_get_args());
    }

    public function getByClassAndSectionExcludeMe()
    {
        return $this->dispatchLegacy('Student', 'getByClassAndSectionExcludeMe', func_get_args());
    }

    public function getStudentRecordByID()
    {
        return $this->dispatchLegacy('Student', 'getStudentRecordByID', func_get_args());
    }

    public function uploadimage($id)
    {
        return $this->dispatchLegacy('Student', 'uploadimage', func_get_args());
    }

    public function doupload($id)
    {
        return $this->dispatchLegacy('Student', 'doupload', func_get_args());
    }

    public function getlogindetail()
    {
        return $this->dispatchLegacy('Student', 'getlogindetail', func_get_args());
    }

    public function getUserLoginDetails()
    {
        return $this->dispatchLegacy('Student', 'getUserLoginDetails', func_get_args());
    }

    public function disablestudentslist()
    {
        return $this->dispatchLegacy('Student', 'disablestudentslist', func_get_args());
    }

    public function disablestudent($id)
    {
        return $this->dispatchLegacy('Student', 'disablestudent', func_get_args());
    }

    public function enablestudent($id)
    {
        return $this->dispatchLegacy('Student', 'enablestudent', func_get_args());
    }

    public function savemulticlass()
    {
        return $this->dispatchLegacy('Student', 'savemulticlass', func_get_args());
    }

    public function disable_reason()
    {
        return $this->dispatchLegacy('Student', 'disable_reason', func_get_args());
    }

    public function ajax_delete()
    {
        return $this->dispatchLegacy('Student', 'ajax_delete', func_get_args());
    }

    public function profilesetting()
    {
        return $this->dispatchLegacy('Student', 'profilesetting', func_get_args());
    }

    public function save_student_dashboard_settings()
    {
        return $this->dispatchLegacy('Student', 'save_student_dashboard_settings', func_get_args());
    }

    public function changeprofilesetting()
    {
        return $this->dispatchLegacy('Student', 'changeprofilesetting', func_get_args());
    }

    public function bulkmail()
    {
        return $this->dispatchLegacy('Student', 'bulkmail', func_get_args());
    }

    public function sendbulkmail()
    {
        return $this->dispatchLegacy('Student', 'sendbulkmail', func_get_args());
    }

    public function studentList()
    {
        return $this->dispatchLegacy('Student', 'studentList', func_get_args());
    }

    public function preview_custom_export()
    {
        return $this->dispatchLegacy('Student', 'preview_custom_export', func_get_args());
    }

    public function export_custom_data()
    {
        return $this->dispatchLegacy('Student', 'export_custom_data', func_get_args());
    }

    public function ajax_student_list_custom()
    {
        return $this->dispatchLegacy('Student', 'ajax_student_list_custom', func_get_args());
    }

    public function dtstudentlist()
    {
        return $this->dispatchLegacy('Student', 'dtstudentlist', func_get_args());
    }

    public function searchvalidation()
    {
        return $this->dispatchLegacy('Student', 'searchvalidation', func_get_args());
    }

    public function getStudentByClassSection()
    {
        return $this->dispatchLegacy('Student', 'getStudentByClassSection', func_get_args());
    }

    public function handle_upload($str, $var)
    {
        return $this->dispatchLegacy('Student', 'handle_upload', func_get_args());
    }

    public function handle_uploadfordoc($str, $var)
    {
        return $this->dispatchLegacy('Student', 'handle_uploadfordoc', func_get_args());
    }

    public function countAttendance($session_year_start, $student_session_id)
    {
        return $this->dispatchLegacy('Student', 'countAttendance', func_get_args());
    }

    public function startmonthandend()
    {
        return $this->dispatchLegacy('Student', 'startmonthandend', func_get_args());
    }

    public function getAdmissionNoByGuardianEmail()
    {
        return $this->dispatchLegacy('Student', 'getAdmissionNoByGuardianEmail', func_get_args());
    }

    public function getAdmissionNoByGuardianPhone()
    {
        return $this->dispatchLegacy('Student', 'getAdmissionNoByGuardianPhone', func_get_args());
    }

    public function printStudentDetails()
    {
        return $this->dispatchLegacy('Student', 'printStudentDetails', func_get_args());
    }

    public function searchvalidationtitle()
    {
        return $this->dispatchLegacy('Student', 'searchvalidationtitle', func_get_args());
    }

    public function save_image()
    {
        return $this->dispatchLegacy('Student', 'save_image', func_get_args());
    }

    public function validateCanAddNewResource($input, $resource_name)
    {
        return $this->dispatchLegacy('Student', 'validateCanAddNewResource', func_get_args());
    }

    public function dashboard()
    {
        return $this->dispatchLegacy('Student', 'dashboard', func_get_args());
    }

    public function get_dashboard_stats()
    {
        return $this->dispatchLegacy('Student', 'get_dashboard_stats', func_get_args());
    }

    public function get_transport_fee()
    {
        return $this->dispatchLegacy('Student', 'get_transport_fee', func_get_args());
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