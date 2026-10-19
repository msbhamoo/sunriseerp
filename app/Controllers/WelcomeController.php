<?php

namespace App\Controllers\.;

use App\Controllers\BaseController;

/**
 * WelcomeController
 *
 * Auto-generated CI4 controller migrating CI3 Welcome.
 */
class WelcomeController extends BaseController
{
    public function show_404()
    {
        return $this->dispatchLegacy('Welcome', 'show_404', func_get_args());
    }

    public function index()
    {
        return $this->dispatchLegacy('Welcome', 'index', func_get_args());
    }

    public function page($slug)
    {
        return $this->dispatchLegacy('Welcome', 'page', func_get_args());
    }

    public function ajaxPaginationData()
    {
        return $this->dispatchLegacy('Welcome', 'ajaxPaginationData', func_get_args());
    }

    public function read($slug)
    {
        return $this->dispatchLegacy('Welcome', 'read', func_get_args());
    }

    public function getSections()
    {
        return $this->dispatchLegacy('Welcome', 'getSections', func_get_args());
    }

    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Welcome', 'validateCanUploadFile', func_get_args());
    }

    public function admission()
    {
        return $this->dispatchLegacy('Welcome', 'admission', func_get_args());
    }

    public function online_admission_review($reference_no=null)
    {
        return $this->dispatchLegacy('Welcome', 'online_admission_review', func_get_args());
    }

    public function editonlineadmission($reference_no)
    {
        return $this->dispatchLegacy('Welcome', 'editonlineadmission', func_get_args());
    }

    public function image_handle_upload($str, $var)
    {
        return $this->dispatchLegacy('Welcome', 'image_handle_upload', func_get_args());
    }

    public function document_handle_upload($str, $var)
    {
        return $this->dispatchLegacy('Welcome', 'document_handle_upload', func_get_args());
    }

    public function check_captcha($captcha)
    {
        return $this->dispatchLegacy('Welcome', 'check_captcha', func_get_args());
    }

    public function setsitecookies()
    {
        return $this->dispatchLegacy('Welcome', 'setsitecookies', func_get_args());
    }

    public function checkadmissionstatus()
    {
        return $this->dispatchLegacy('Welcome', 'checkadmissionstatus', func_get_args());
    }

    public function submitadmission()
    {
        return $this->dispatchLegacy('Welcome', 'submitadmission', func_get_args());
    }

    public function checktermcondition()
    {
        return $this->dispatchLegacy('Welcome', 'checktermcondition', func_get_args());
    }

    public function examresult()
    {
        return $this->dispatchLegacy('Welcome', 'examresult', func_get_args());
    }

    public function getstudentexam()
    {
        return $this->dispatchLegacy('Welcome', 'getstudentexam', func_get_args());
    }

    public function download($id)
    {
        return $this->dispatchLegacy('Welcome', 'download', func_get_args());
    }

    public function loadCaptcha()
    {
        return $this->dispatchLegacy('Welcome', 'loadCaptcha', func_get_args());
    }

    public function changeCurrencyFormat()
    {
        return $this->dispatchLegacy('Welcome', 'changeCurrencyFormat', func_get_args());
    }

    public function cbseexam()
    {
        return $this->dispatchLegacy('Welcome', 'cbseexam', func_get_args());
    }

    public function annual_calendar()
    {
        return $this->dispatchLegacy('Welcome', 'annual_calendar', func_get_args());
    }

    public function download_receipt($invoice_id, $sub_invoice_id, $hash)
    {
        return $this->dispatchLegacy('Welcome', 'download_receipt', func_get_args());
    }

    public function cbse_disclosure()
    {
        return $this->dispatchLegacy('Welcome', 'cbse_disclosure', func_get_args());
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