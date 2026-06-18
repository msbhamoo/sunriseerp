<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Whatsappgateway
{
    private $CI;
    private $api_url = "http://202.143.97.65/smppv/production/api_page/wa/send_message.php";
    private $auth_key = "";

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('whatsappconfig_model');
        $this->CI->load->library('smsgateway'); // We will use smsgateway to get replaced content
        
        $whatsapp_detail = $this->CI->whatsappconfig_model->getActiveWhatsApp();
        if ($whatsapp_detail) {
            // Using api_key from DB as auth_key
            $this->auth_key = $whatsapp_detail->api_key;
        }
    }

    private function _sendWhatsapp($mobile, $message_text)
    {
        if (empty($this->auth_key) || empty($mobile) || empty($message_text)) {
            return false;
        }

        // Format mobile: ensuring 10 digits or 12 digits (91XXXXXXXXXX)
        $mobile = preg_replace('/[^0-9]/', '', $mobile);
        if (strlen($mobile) === 10) {
            $mobile = '91' . $mobile;
        }

        $data = array(
            'auth_key'      => $this->auth_key,
            'campaign_name' => 'Scholarship_OTP',
            'msg_type'      => 'text',
            'mobile'        => $mobile,
            'caption'       => $message_text,
            'priority'      => 0
        );
        file_put_contents('whatsapp_debug.log', "Sending payload: " . json_encode($data) . "\n", FILE_APPEND);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->api_url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        
        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        file_put_contents('whatsapp_debug.log', "Response: " . $response . " Error: " . $err . "\n", FILE_APPEND);

        if ($err) {
            return false;
        } else {
            return $response;
        }
    }

    public function testMessage($mobile, $message_text)
    {
        return $this->_sendWhatsapp($mobile, $message_text);
    }

    public function sentRegisterWhatsapp($id, $send_to, $template, $template_id)
    {
        $content = $this->CI->smsgateway->getStudentRegistrationContent($id, $template, null);
        return $this->_sendWhatsapp($send_to, $content);
    }

    public function sentAddGroupFeeWhatsapp($detail, $send_to, $template, $template_id)
    {
        $content = $this->CI->smsgateway->getGroupAddFeeContent($detail, $template, null);
        return $this->_sendWhatsapp($send_to, $content);
    }

    public function sentAddFeeWhatsapp($detail, $send_to, $template, $template_id)
    {
        $content = $this->CI->smsgateway->getAddFeeContent($detail, $template, null);
        return $this->_sendWhatsapp($send_to, $content);
    }

    public function sentFeeProcessingNotification($detail, $send_to, $template, $template_id)
    {
        $content = $this->CI->smsgateway->getFeeProcessingContent($detail, $template, null);
        return $this->_sendWhatsapp($send_to, $content);
    }

    public function sentfeesreminderNotification($detail, $send_to, $template, $template_id)
    {
        $content = $this->CI->smsgateway->getContent($detail, $template, null);
        return $this->_sendWhatsapp($send_to, $content);
    }

    public function sendStudentLoginCredential($chk_mail_sms, $sender_details, $template, $template_id)
    {
        $content = $this->CI->smsgateway->getLoginCredentialContent($chk_mail_sms['student_recipient'], $sender_details, $template, null);
        return $this->_sendWhatsapp($sender_details['mobileno'], $content);
    }

    public function sendStaffLoginCredential($chk_mail_sms, $sender_details, $template, $template_id)
    {
        $content = $this->CI->smsgateway->getLoginCredentialContent($chk_mail_sms['staff_recipient'], $sender_details, $template, null);
        return $this->_sendWhatsapp($sender_details['contact_no'], $content);
    }

    public function student_apply_leave($sender_details, $template, $template_id)
    {
        $content = $this->CI->smsgateway->getstudent_apply_leaveContent($sender_details, $template, null);
        return $this->_sendWhatsapp($sender_details['mobileno'], $content); // Assuming mobileno
    }

    public function sentExamResultWhatsapp($detail, $template, $template_id)
    {
        $content = $this->CI->smsgateway->getStudentResultContent($detail, $template, null);
        return $this->_sendWhatsapp($detail['mobileno'], $content);
    }

    public function sendPresentAttendancenotification($detail, $template, $template_id, $send_to)
    {
        $content = $this->CI->smsgateway->getPresentStudentContent($detail, $template, null);
        return $this->_sendWhatsapp($send_to, $content);
    }

    public function sentPresentStaffWhatsapp($detail, $template, $template_id)
    {
        $content = $this->CI->smsgateway->getPresentStaffContent($detail, $template, null);
        return $this->_sendWhatsapp($detail['contact_no'], $content);
    }

    public function sentAbsentStaffWhatsapp($detail, $template, $template_id)
    {
        $content = $this->CI->smsgateway->getAbsentStaffContent($detail, $template, null);
        return $this->_sendWhatsapp($detail['contact_no'], $content);
    }

    public function sendAbsentAttendancenotification($detail, $template, $template_id, $send_to)
    {
        $content = $this->CI->smsgateway->getAbsentStudentContent($detail, $template, null);
        return $this->_sendWhatsapp($send_to, $content);
    }

    public function sendstudentlhomework($student_sms_list, $template, $template_id)
    {
        if(!empty($student_sms_list)){
            foreach($student_sms_list as $student){
                $content = $this->CI->smsgateway->getHomeworkStudentContent($student, $template, null);
                $this->_sendWhatsapp($student['mobileno'], $content);
            }
        }
        return true;
    }

    public function sentOnlineexamStudentWhatsapp($student_sms_list, $template, $template_id)
    {
        if(!empty($student_sms_list)){
            foreach($student_sms_list as $student){
                $content = $this->CI->smsgateway->getOnlineexamStudentContent($student, $template, null);
                $this->_sendWhatsapp($student['mobileno'], $content);
            }
        }
        return true;
    }

    public function sendOnlineadmissionformsubmit($student_details, $template, $send_to, $template_id)
    {
        $content = $this->CI->smsgateway->getOnlineadmissionStudentContent($student_details, $template);
        return $this->_sendWhatsapp($send_to, $content);
    }

    public function sentstudentOnlineadmissionFeessubmissionWhatsapp($student_details, $template, $send_to, $template_id)
    {
        $content = $this->CI->smsgateway->getOnlineadmissionFeesContent($student_details, $template, null);
        return $this->_sendWhatsapp($send_to, $content);
    }

    public function sendDynamicWhatsapp($mobile, $message_text)
    {
        return $this->_sendWhatsapp($mobile, $message_text);
    }
}
