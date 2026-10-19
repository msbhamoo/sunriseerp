<?php

namespace App\Controllers\.;

use App\Controllers\BaseController;

/**
 * SiteController
 *
 * Auto-generated CI4 controller migrating CI3 Site.
 */
class SiteController extends BaseController
{
    public function login()
    {
        return $this->dispatchLegacy('Site', 'login', func_get_args());
    }

    public function logout()
    {
        return $this->dispatchLegacy('Site', 'logout', func_get_args());
    }

    public function download_content($share_id, $content_id)
    {
        return $this->dispatchLegacy('Site', 'download_content', func_get_args());
    }

    public function forgotpassword()
    {
        return $this->dispatchLegacy('Site', 'forgotpassword', func_get_args());
    }

    public function admin_resetpassword($verification_code = null)
    {
        return $this->dispatchLegacy('Site', 'admin_resetpassword', func_get_args());
    }

    public function share($key)
    {
        return $this->dispatchLegacy('Site', 'share', func_get_args());
    }

    public function resetpassword($role = null, $verification_code = null)
    {
        return $this->dispatchLegacy('Site', 'resetpassword', func_get_args());
    }

    public function ufpassword()
    {
        return $this->dispatchLegacy('Site', 'ufpassword', func_get_args());
    }

    public function userlogin()
    {
        return $this->dispatchLegacy('Site', 'userlogin', func_get_args());
    }

    public function savemulticlass()
    {
        return $this->dispatchLegacy('Site', 'savemulticlass', func_get_args());
    }

    public function check_captcha($captcha)
    {
        return $this->dispatchLegacy('Site', 'check_captcha', func_get_args());
    }

    public function refreshCaptcha()
    {
        return $this->dispatchLegacy('Site', 'refreshCaptcha', func_get_args());
    }

    public function download_fee_receipt_token($token)
    {
        return $this->dispatchLegacy('Site', 'download_fee_receipt_token', func_get_args());
    }

    public function download_cbseexam_marksheet_token($token)
    {
        return $this->dispatchLegacy('Site', 'download_cbseexam_marksheet_token', func_get_args());
    }

    public function download_cbseexam_marksheet_pdf($student_session_id,$cbse_template_id,$cbse_exam_id)
    {
        return $this->dispatchLegacy('Site', 'download_cbseexam_marksheet_pdf', func_get_args());
    }

    public function test($cbse_template_id, $cbse_exam_id, $student_session_id)
    {
        return $this->dispatchLegacy('Site', 'test', func_get_args());
    }

    public function download_marksheet_token($token)
    {
        return $this->dispatchLegacy('Site', 'download_marksheet_token', func_get_args());
    }

    public function download_marksheet_pdf($template,$student_id,$post_exam_id,$post_exam_group_id,$exam_group_class_batch_exam_student_id)
    {
        return $this->dispatchLegacy('Site', 'download_marksheet_pdf', func_get_args());
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