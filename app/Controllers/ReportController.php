<?php

namespace App\Controllers;

use App\Controllers\BaseController;

/**
 * ReportController
 *
 * Auto-generated CI4 controller migrating CI3 Report.
 */
class ReportController extends BaseController
{
    public function pdfStudentFeeRecord()
    {
        return $this->dispatchLegacy('Report', 'pdfStudentFeeRecord', func_get_args());
    }

    public function pdfByInvoiceNo()
    {
        return $this->dispatchLegacy('Report', 'pdfByInvoiceNo', func_get_args());
    }

    public function pdfDepositeFeeByStudent($id)
    {
        return $this->dispatchLegacy('Report', 'pdfDepositeFeeByStudent', func_get_args());
    }

    public function pdfStudentListByText()
    {
        return $this->dispatchLegacy('Report', 'pdfStudentListByText', func_get_args());
    }

    public function marksreport()
    {
        return $this->dispatchLegacy('Report', 'marksreport', func_get_args());
    }

    public function pdfStudentListByClassSection()
    {
        return $this->dispatchLegacy('Report', 'pdfStudentListByClassSection', func_get_args());
    }

    public function pdfStudentListDifferentCriteria()
    {
        return $this->dispatchLegacy('Report', 'pdfStudentListDifferentCriteria', func_get_args());
    }

    public function pdfStudentListByClass()
    {
        return $this->dispatchLegacy('Report', 'pdfStudentListByClass', func_get_args());
    }

    public function transactionSearch()
    {
        return $this->dispatchLegacy('Report', 'transactionSearch', func_get_args());
    }

    public function pdfExamschdule()
    {
        return $this->dispatchLegacy('Report', 'pdfExamschdule', func_get_args());
    }

    public function get_betweendate($type)
    {
        return $this->dispatchLegacy('Report', 'get_betweendate', func_get_args());
    }

    public function class_subject()
    {
        return $this->dispatchLegacy('Report', 'class_subject', func_get_args());
    }

    public function admission_report()
    {
        return $this->dispatchLegacy('Report', 'admission_report', func_get_args());
    }

    public function searchreportvalidation()
    {
        return $this->dispatchLegacy('Report', 'searchreportvalidation', func_get_args());
    }

    public function sibling_report()
    {
        return $this->dispatchLegacy('Report', 'sibling_report', func_get_args());
    }

    public function studentbookissuereport()
    {
        return $this->dispatchLegacy('Report', 'studentbookissuereport', func_get_args());
    }

    public function bookduereport()
    {
        return $this->dispatchLegacy('Report', 'bookduereport', func_get_args());
    }

    public function bookinventory()
    {
        return $this->dispatchLegacy('Report', 'bookinventory', func_get_args());
    }

    public function feescollectionreport()
    {
        return $this->dispatchLegacy('Report', 'feescollectionreport', func_get_args());
    }

    public function gerenalincomereport()
    {
        return $this->dispatchLegacy('Report', 'gerenalincomereport', func_get_args());
    }

    public function studentinformation()
    {
        return $this->dispatchLegacy('Report', 'studentinformation', func_get_args());
    }

    public function human_resource()
    {
        return $this->dispatchLegacy('Report', 'human_resource', func_get_args());
    }

    public function library()
    {
        return $this->dispatchLegacy('Report', 'library', func_get_args());
    }

    public function inventory()
    {
        return $this->dispatchLegacy('Report', 'inventory', func_get_args());
    }

    public function onlineexams()
    {
        return $this->dispatchLegacy('Report', 'onlineexams', func_get_args());
    }

    public function onlineexamsresult()
    {
        return $this->dispatchLegacy('Report', 'onlineexamsresult', func_get_args());
    }

    public function onlineexamattend()
    {
        return $this->dispatchLegacy('Report', 'onlineexamattend', func_get_args());
    }

    public function onlineexamrank()
    {
        return $this->dispatchLegacy('Report', 'onlineexamrank', func_get_args());
    }

    public function inventorystock()
    {
        return $this->dispatchLegacy('Report', 'inventorystock', func_get_args());
    }

    public function additem()
    {
        return $this->dispatchLegacy('Report', 'additem', func_get_args());
    }

    public function getadditemlistbydt()
    {
        return $this->dispatchLegacy('Report', 'getadditemlistbydt', func_get_args());
    }

    public function issueinventory()
    {
        return $this->dispatchLegacy('Report', 'issueinventory', func_get_args());
    }

    public function getissueinventorylistbydt()
    {
        return $this->dispatchLegacy('Report', 'getissueinventorylistbydt', func_get_args());
    }

    public function finance()
    {
        return $this->dispatchLegacy('Report', 'finance', func_get_args());
    }

    public function student_profile()
    {
        return $this->dispatchLegacy('Report', 'student_profile', func_get_args());
    }

    public function staff_report()
    {
        return $this->dispatchLegacy('Report', 'staff_report', func_get_args());
    }

    public function lesson_plan()
    {
        return $this->dispatchLegacy('Report', 'lesson_plan', func_get_args());
    }

    public function teachersyllabusstatus()
    {
        return $this->dispatchLegacy('Report', 'teachersyllabusstatus', func_get_args());
    }

    public function alumnireport()
    {
        return $this->dispatchLegacy('Report', 'alumnireport', func_get_args());
    }

    public function boys_girls_ratio()
    {
        return $this->dispatchLegacy('Report', 'boys_girls_ratio', func_get_args());
    }

    public function student_teacher_ratio()
    {
        return $this->dispatchLegacy('Report', 'student_teacher_ratio', func_get_args());
    }

    public function getRatio($num1, $num2)
    {
        return $this->dispatchLegacy('Report', 'getRatio', func_get_args());
    }

    public function getAvailQuantity($item_id)
    {
        return $this->dispatchLegacy('Report', 'getAvailQuantity', func_get_args());
    }

    public function getinventorylist()
    {
        return $this->dispatchLegacy('Report', 'getinventorylist', func_get_args());
    }

    public function dtadmissionreport()
    {
        return $this->dispatchLegacy('Report', 'dtadmissionreport', func_get_args());
    }

    public function getformparameter()
    {
        return $this->dispatchLegacy('Report', 'getformparameter', func_get_args());
    }

    public function dtexamreportlist()
    {
        return $this->dispatchLegacy('Report', 'dtexamreportlist', func_get_args());
    }

    public function dtexamattemptreport()
    {
        return $this->dispatchLegacy('Report', 'dtexamattemptreport', func_get_args());
    }

    public function getbookissueparameter()
    {
        return $this->dispatchLegacy('Report', 'getbookissueparameter', func_get_args());
    }

    public function dtbookissuereportlist()
    {
        return $this->dispatchLegacy('Report', 'dtbookissuereportlist', func_get_args());
    }

    public function dtbookduereportlist()
    {
        return $this->dispatchLegacy('Report', 'dtbookduereportlist', func_get_args());
    }

    public function dtbookinventoryreportlist()
    {
        return $this->dispatchLegacy('Report', 'dtbookinventoryreportlist', func_get_args());
    }

    public function getsearchtypeparam()
    {
        return $this->dispatchLegacy('Report', 'getsearchtypeparam', func_get_args());
    }

    public function online_admission_report()
    {
        return $this->dispatchLegacy('Report', 'online_admission_report', func_get_args());
    }

    public function checkvalidation()
    {
        return $this->dispatchLegacy('Report', 'checkvalidation', func_get_args());
    }

    public function dtonlineadmissionreportlist()
    {
        return $this->dispatchLegacy('Report', 'dtonlineadmissionreportlist', func_get_args());
    }

    public function studentreport()
    {
        return $this->dispatchLegacy('Report', 'studentreport', func_get_args());
    }

    public function studentreportvalidation()
    {
        return $this->dispatchLegacy('Report', 'studentreportvalidation', func_get_args());
    }

    public function dtstudentreportlist()
    {
        return $this->dispatchLegacy('Report', 'dtstudentreportlist', func_get_args());
    }

    public function classsectionreport()
    {
        return $this->dispatchLegacy('Report', 'classsectionreport', func_get_args());
    }

    public function guardianreport()
    {
        return $this->dispatchLegacy('Report', 'guardianreport', func_get_args());
    }

    public function admissionreport()
    {
        return $this->dispatchLegacy('Report', 'admissionreport', func_get_args());
    }

    public function admissionsearchvalidation()
    {
        return $this->dispatchLegacy('Report', 'admissionsearchvalidation', func_get_args());
    }

    public function dtadmissionreportlist()
    {
        return $this->dispatchLegacy('Report', 'dtadmissionreportlist', func_get_args());
    }

    public function logindetailreport()
    {
        return $this->dispatchLegacy('Report', 'logindetailreport', func_get_args());
    }

    public function searchloginvalidation()
    {
        return $this->dispatchLegacy('Report', 'searchloginvalidation', func_get_args());
    }

    public function dtcredentialreportlist()
    {
        return $this->dispatchLegacy('Report', 'dtcredentialreportlist', func_get_args());
    }

    public function parentlogindetailreport()
    {
        return $this->dispatchLegacy('Report', 'parentlogindetailreport', func_get_args());
    }

    public function dtparentcredentialreportlist()
    {
        return $this->dispatchLegacy('Report', 'dtparentcredentialreportlist', func_get_args());
    }

    public function leaverequestreport()
    {
        return $this->dispatchLegacy('Report', 'leaverequestreport', func_get_args());
    }

    public function myleaverequestreport()
    {
        return $this->dispatchLegacy('Report', 'myleaverequestreport', func_get_args());
    }

    protected function dispatchLegacy(string $controller, string $method, array $params)
    {
        // Set up compatibility environment
        $_SERVER['CI4_LEGACY_CONTROLLER'] = $controller;
        $_SERVER['CI4_LEGACY_METHOD'] = $method;
        $_SERVER['CI4_LEGACY_PARAMS'] = $params;

        require_once APPPATH . '../application/core/MY_Controller.php';
        $target = APPPATH . '../application/controllers/' . $controller . '.php';
        if (file_exists($target)) {
            require_once $target;
            $instance = new $controller();
            return call_user_func_array([$instance, $method], $params);
        }

        return redirect()->to(base_url('admin/unauthorized'));
    }
}