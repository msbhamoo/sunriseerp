<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * MessagesModel
 *
 * Auto-generated CI4 PSR-4 Model migrating legacy Messages_model.
 */
class MessagesModel extends BaseModel
{
    protected $table = 'messages';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';

    public function get($id = null)
    {
        return $this->callLegacyModel('Messages_model', 'get', func_get_args());
    }

    public function remove($id)
    {
        return $this->callLegacyModel('Messages_model', 'remove', func_get_args());
    }

    public function add($data)
    {
        return $this->callLegacyModel('Messages_model', 'add', func_get_args());
    }

    public function get_classname($id)
    {
        return $this->callLegacyModel('Messages_model', 'get_classname', func_get_args());
    }

    public function get_sectionname($id)
    {
        return $this->callLegacyModel('Messages_model', 'get_sectionname', func_get_args());
    }

    public function get_categoryname($id)
    {
        return $this->callLegacyModel('Messages_model', 'get_categoryname', func_get_args());
    }

    public function get_subject_groupname($id)
    {
        return $this->callLegacyModel('Messages_model', 'get_subject_groupname', func_get_args());
    }

    public function get_subject_name($id)
    {
        return $this->callLegacyModel('Messages_model', 'get_subject_name', func_get_args());
    }

    public function get_student_name($id)
    {
        return $this->callLegacyModel('Messages_model', 'get_student_name', func_get_args());
    }

    public function get_staff_name($id)
    {
        return $this->callLegacyModel('Messages_model', 'get_staff_name', func_get_args());
    }

    public function get_exphead_name($id)
    {
        return $this->callLegacyModel('Messages_model', 'get_exphead_name', func_get_args());
    }

    public function get_inchead_name($id)
    {
        return $this->callLegacyModel('Messages_model', 'get_inchead_name', func_get_args());
    }

    public function get_attendance_type($id)
    {
        return $this->callLegacyModel('Messages_model', 'get_attendance_type', func_get_args());
    }

    public function get_exam_group($id)
    {
        return $this->callLegacyModel('Messages_model', 'get_exam_group', func_get_args());
    }

    public function get_examname($id)
    {
        return $this->callLegacyModel('Messages_model', 'get_examname', func_get_args());
    }

    public function get_onlineexamname($id)
    {
        return $this->callLegacyModel('Messages_model', 'get_onlineexamname', func_get_args());
    }

    public function get_sessionname($id)
    {
        return $this->callLegacyModel('Messages_model', 'get_sessionname', func_get_args());
    }

    public function get_rolename($id)
    {
        return $this->callLegacyModel('Messages_model', 'get_rolename', func_get_args());
    }

    public function get_designation($id)
    {
        return $this->callLegacyModel('Messages_model', 'get_designation', func_get_args());
    }

    public function get_route_title($id)
    {
        return $this->callLegacyModel('Messages_model', 'get_route_title', func_get_args());
    }

    public function get_student_full_name($id)
    {
        return $this->callLegacyModel('Messages_model', 'get_student_full_name', func_get_args());
    }

    public function get_email_template($id = null)
    {
        return $this->callLegacyModel('Messages_model', 'get_email_template', func_get_args());
    }

    public function add_email_template($data, $FILES = array())
    {
        return $this->callLegacyModel('Messages_model', 'add_email_template', func_get_args());
    }

    public function delete_email_template($id)
    {
        return $this->callLegacyModel('Messages_model', 'delete_email_template', func_get_args());
    }

    public function get_sms_template($id = null)
    {
        return $this->callLegacyModel('Messages_model', 'get_sms_template', func_get_args());
    }

    public function add_sms_template($data)
    {
        return $this->callLegacyModel('Messages_model', 'add_sms_template', func_get_args());
    }

    public function delete_sms_template($id)
    {
        return $this->callLegacyModel('Messages_model', 'delete_sms_template', func_get_args());
    }

    public function get_whatsapp_template($id = null)
    {
        return $this->callLegacyModel('Messages_model', 'get_whatsapp_template', func_get_args());
    }

    public function add_whatsapp_template($data)
    {
        return $this->callLegacyModel('Messages_model', 'add_whatsapp_template', func_get_args());
    }

    public function delete_whatsapp_template($id)
    {
        return $this->callLegacyModel('Messages_model', 'delete_whatsapp_template', func_get_args());
    }

    public function get_email_template_attachment($email_template_id)
    {
        return $this->callLegacyModel('Messages_model', 'get_email_template_attachment', func_get_args());
    }

    public function get_scheduledata($date)
    {
        return $this->callLegacyModel('Messages_model', 'get_scheduledata', func_get_args());
    }

    public function get_message_attachment($message_id)
    {
        return $this->callLegacyModel('Messages_model', 'get_message_attachment', func_get_args());
    }

    public function schedule($id = null,$type = null)
    {
        return $this->callLegacyModel('Messages_model', 'schedule', func_get_args());
    }

    public function get_single_email_template_attachment($id)
    {
        return $this->callLegacyModel('Messages_model', 'get_single_email_template_attachment', func_get_args());
    }

    public function get_email_attachment($message_id)
    {
        return $this->callLegacyModel('Messages_model', 'get_email_attachment', func_get_args());
    }

    public function check_email_attachment($message_id, $name)
    {
        return $this->callLegacyModel('Messages_model', 'check_email_attachment', func_get_args());
    }

    public function get_email_attachment_list($message_id)
    {
        return $this->callLegacyModel('Messages_model', 'get_email_attachment_list', func_get_args());
    }

    public function add_email_attachment($data)
    {
        return $this->callLegacyModel('Messages_model', 'add_email_attachment', func_get_args());
    }

    public function delete_email_attachment($id)
    {
        return $this->callLegacyModel('Messages_model', 'delete_email_attachment', func_get_args());
    }

    public function check_template_attachment($template_id, $name)
    {
        return $this->callLegacyModel('Messages_model', 'check_template_attachment', func_get_args());
    }

    public function delete_template_attachment($id)
    {
        return $this->callLegacyModel('Messages_model', 'delete_template_attachment', func_get_args());
    }

    public function delete_email_sms_log()
    {
        return $this->callLegacyModel('Messages_model', 'delete_email_sms_log', func_get_args());
    }

    public function getEmailTemplateAttachmentByTemplateID($template_id)
    {
        return $this->callLegacyModel('Messages_model', 'getEmailTemplateAttachmentByTemplateID', func_get_args());
    }

    /**
     * Compatibility Bridge: Delegates execution to the legacy model instance
     * maintaining full compatibility with query builder and CI3 environment.
     */
    protected function callLegacyModel(string $modelClass, string $method, array $args)
    {
        static $instances = [];

        if (!isset($instances[$modelClass])) {
            require_once APPPATH . '../application/core/MY_Model.php';
            $file = APPPATH . '../application/models/' . ($this->subPath() ? $this->subPath() . '/' : '') . $modelClass . '.php';
            if (file_exists($file)) {
                require_once $file;
                $instances[$modelClass] = new $modelClass();
            } else {
                return null;
            }
        }

        return call_user_func_array([$instances[$modelClass], $method], $args);
    }

    protected function subPath(): string
    {
        return '';
    }
}