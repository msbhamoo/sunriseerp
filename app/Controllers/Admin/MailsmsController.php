<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

/**
 * MailsmsController
 *
 * Auto-generated CI4 controller migrating CI3 Mailsms.
 */
class MailsmsController extends BaseController
{
    public function index()
    {
        return $this->dispatchLegacy('Mailsms', 'index', func_get_args());
    }

    public function delete_email_sms_log()
    {
        return $this->dispatchLegacy('Mailsms', 'delete_email_sms_log', func_get_args());
    }

    public function schedule()
    {
        return $this->dispatchLegacy('Mailsms', 'schedule', func_get_args());
    }

    public function search()
    {
        return $this->dispatchLegacy('Mailsms', 'search', func_get_args());
    }

    public function compose()
    {
        return $this->dispatchLegacy('Mailsms', 'compose', func_get_args());
    }

    public function compose_sms()
    {
        return $this->dispatchLegacy('Mailsms', 'compose_sms', func_get_args());
    }

    public function edit($id)
    {
        return $this->dispatchLegacy('Mailsms', 'edit', func_get_args());
    }

    public function delete($id)
    {
        return $this->dispatchLegacy('Mailsms', 'delete', func_get_args());
    }

    public function send_individual()
    {
        return $this->dispatchLegacy('Mailsms', 'send_individual', func_get_args());
    }

    public function send_birthday()
    {
        return $this->dispatchLegacy('Mailsms', 'send_birthday', func_get_args());
    }

    public function send_group()
    {
        return $this->dispatchLegacy('Mailsms', 'send_group', func_get_args());
    }

    public function send_group_sms()
    {
        return $this->dispatchLegacy('Mailsms', 'send_group_sms', func_get_args());
    }

    public function send_birthday_sms()
    {
        return $this->dispatchLegacy('Mailsms', 'send_birthday_sms', func_get_args());
    }

    public function send_individual_sms()
    {
        return $this->dispatchLegacy('Mailsms', 'send_individual_sms', func_get_args());
    }

    public function send_class_sms()
    {
        return $this->dispatchLegacy('Mailsms', 'send_class_sms', func_get_args());
    }

    public function send_class()
    {
        return $this->dispatchLegacy('Mailsms', 'send_class', func_get_args());
    }

    public function test_sms()
    {
        return $this->dispatchLegacy('Mailsms', 'test_sms', func_get_args());
    }

    public function email_template()
    {
        return $this->dispatchLegacy('Mailsms', 'email_template', func_get_args());
    }

    public function viewdocuments()
    {
        return $this->dispatchLegacy('Mailsms', 'viewdocuments', func_get_args());
    }

    public function add_email_template()
    {
        return $this->dispatchLegacy('Mailsms', 'add_email_template', func_get_args());
    }

    public function edit_email_template()
    {
        return $this->dispatchLegacy('Mailsms', 'edit_email_template', func_get_args());
    }

    public function multihandle_upload($str, $var)
    {
        return $this->dispatchLegacy('Mailsms', 'multihandle_upload', func_get_args());
    }

    public function update_email_template_old()
    {
        return $this->dispatchLegacy('Mailsms', 'update_email_template_old', func_get_args());
    }

    public function update_email_template()
    {
        return $this->dispatchLegacy('Mailsms', 'update_email_template', func_get_args());
    }

    public function handle_upload($str, $var)
    {
        return $this->dispatchLegacy('Mailsms', 'handle_upload', func_get_args());
    }

    public function delete_email_template($id)
    {
        return $this->dispatchLegacy('Mailsms', 'delete_email_template', func_get_args());
    }

    public function email_template_download($doc)
    {
        return $this->dispatchLegacy('Mailsms', 'email_template_download', func_get_args());
    }

    public function sms_template()
    {
        return $this->dispatchLegacy('Mailsms', 'sms_template', func_get_args());
    }

    public function add_sms_template()
    {
        return $this->dispatchLegacy('Mailsms', 'add_sms_template', func_get_args());
    }

    public function edit_sms_template()
    {
        return $this->dispatchLegacy('Mailsms', 'edit_sms_template', func_get_args());
    }

    public function update_sms_template()
    {
        return $this->dispatchLegacy('Mailsms', 'update_sms_template', func_get_args());
    }

    public function delete_sms_template($id)
    {
        return $this->dispatchLegacy('Mailsms', 'delete_sms_template', func_get_args());
    }

    public function templatedata()
    {
        return $this->dispatchLegacy('Mailsms', 'templatedata', func_get_args());
    }

    public function smstemplatedata()
    {
        return $this->dispatchLegacy('Mailsms', 'smstemplatedata', func_get_args());
    }

    public function get_birthday_preview()
    {
        return $this->dispatchLegacy('Mailsms', 'get_birthday_preview', func_get_args());
    }

    public function get_class_preview()
    {
        return $this->dispatchLegacy('Mailsms', 'get_class_preview', func_get_args());
    }

    public function get_individual_preview()
    {
        return $this->dispatchLegacy('Mailsms', 'get_individual_preview', func_get_args());
    }

    public function get_preview()
    {
        return $this->dispatchLegacy('Mailsms', 'get_preview', func_get_args());
    }

    public function genrateDiv($image_name, $attachments, $template_attachment_id)
    {
        return $this->dispatchLegacy('Mailsms', 'genrateDiv', func_get_args());
    }

    public function edit_schedule($id, $type)
    {
        return $this->dispatchLegacy('Mailsms', 'edit_schedule', func_get_args());
    }

    public function update_group_schedule_old()
    {
        return $this->dispatchLegacy('Mailsms', 'update_group_schedule_old', func_get_args());
    }

    public function update_group_schedule()
    {
        return $this->dispatchLegacy('Mailsms', 'update_group_schedule', func_get_args());
    }

    public function update_individual_schedule_old()
    {
        return $this->dispatchLegacy('Mailsms', 'update_individual_schedule_old', func_get_args());
    }

    public function update_individual_schedule()
    {
        return $this->dispatchLegacy('Mailsms', 'update_individual_schedule', func_get_args());
    }

    public function update_class_schedule()
    {
        return $this->dispatchLegacy('Mailsms', 'update_class_schedule', func_get_args());
    }

    public function update_group_sms_schedule()
    {
        return $this->dispatchLegacy('Mailsms', 'update_group_sms_schedule', func_get_args());
    }

    public function update_individual_sms_schedule()
    {
        return $this->dispatchLegacy('Mailsms', 'update_individual_sms_schedule', func_get_args());
    }

    public function update_class_sms_schedule()
    {
        return $this->dispatchLegacy('Mailsms', 'update_class_sms_schedule', func_get_args());
    }

    public function schedule_templatedata()
    {
        return $this->dispatchLegacy('Mailsms', 'schedule_templatedata', func_get_args());
    }

    public function delete_schedule()
    {
        return $this->dispatchLegacy('Mailsms', 'delete_schedule', func_get_args());
    }

    public function validateCanUploadFile($str, $params_string)
    {
        return $this->dispatchLegacy('Mailsms', 'validateCanUploadFile', func_get_args());
    }

    public function whatsapp_template()
    {
        return $this->dispatchLegacy('Mailsms', 'whatsapp_template', func_get_args());
    }

    public function add_whatsapp_template()
    {
        return $this->dispatchLegacy('Mailsms', 'add_whatsapp_template', func_get_args());
    }

    public function edit_whatsapp_template()
    {
        return $this->dispatchLegacy('Mailsms', 'edit_whatsapp_template', func_get_args());
    }

    public function update_whatsapp_template()
    {
        return $this->dispatchLegacy('Mailsms', 'update_whatsapp_template', func_get_args());
    }

    public function delete_whatsapp_template($id)
    {
        return $this->dispatchLegacy('Mailsms', 'delete_whatsapp_template', func_get_args());
    }

    public function whatsapptemplatedata()
    {
        return $this->dispatchLegacy('Mailsms', 'whatsapptemplatedata', func_get_args());
    }

    public function compose_whatsapp()
    {
        return $this->dispatchLegacy('Mailsms', 'compose_whatsapp', func_get_args());
    }

    public function whatsapp_log()
    {
        return $this->dispatchLegacy('Mailsms', 'whatsapp_log', func_get_args());
    }

    public function get_whatsapp_individual_logs()
    {
        return $this->dispatchLegacy('Mailsms', 'get_whatsapp_individual_logs', func_get_args());
    }

    public function send_group_whatsapp()
    {
        return $this->dispatchLegacy('Mailsms', 'send_group_whatsapp', func_get_args());
    }

    public function send_individual_whatsapp()
    {
        return $this->dispatchLegacy('Mailsms', 'send_individual_whatsapp', func_get_args());
    }

    public function send_class_whatsapp()
    {
        return $this->dispatchLegacy('Mailsms', 'send_class_whatsapp', func_get_args());
    }

    public function send_birthday_whatsapp()
    {
        return $this->dispatchLegacy('Mailsms', 'send_birthday_whatsapp', func_get_args());
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