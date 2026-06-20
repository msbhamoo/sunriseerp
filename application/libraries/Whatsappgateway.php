<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Whatsappgateway
{
    private $CI;
    private $api_url = "http://202.143.97.65/smppv/production/api_page/wa/send_message.php";
    private $auth_key = "";
    private $sent_messages = array();

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

    private function _sendWhatsapp($mobile, $message_text, $detail = null)
    {
        if (empty($mobile) && !empty($detail)) {
            if (is_array($detail)) {
                $mobile = !empty($detail['mobileno']) ? $detail['mobileno'] : (!empty($detail['guardian_phone']) ? $detail['guardian_phone'] : (!empty($detail['father_phone']) ? $detail['father_phone'] : (!empty($detail['mother_phone']) ? $detail['mother_phone'] : '')));
            } else if (is_object($detail)) {
                $mobile = !empty($detail->mobileno) ? $detail->mobileno : (!empty($detail->guardian_phone) ? $detail->guardian_phone : (!empty($detail->father_phone) ? $detail->father_phone : (!empty($detail->mother_phone) ? $detail->mother_phone : '')));
            }
        }

        if (empty($this->auth_key) || empty($mobile) || empty($message_text)) {
            file_put_contents('whatsapp_debug.log', "Early exit in _sendWhatsapp. auth_key_empty=" . empty($this->auth_key) . ", mobile=" . $mobile . ", message_text length=" . strlen($message_text) . "\n", FILE_APPEND);
            return false;
        }

        $msg_hash = md5($mobile . $message_text);
        if (isset($this->sent_messages[$msg_hash])) {
            return true;
        }
        $this->sent_messages[$msg_hash] = true;

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
            // Log to Central WhatsApp Log
            $title_map = array(
                'sentRegisterWhatsapp' => 'New Admission',
                'sentAddGroupFeeWhatsapp' => 'Group Fee Submission',
                'sentAddFeeWhatsapp' => 'Fee Submission',
                'sentFeeProcessingNotification' => 'Fee Processing',
                'sentfeesreminderNotification' => 'Fees Reminder',
                'sendStudentLoginCredential' => 'Student Login Credential',
                'sendStaffLoginCredential' => 'Staff Login Credential',
                'student_apply_leave' => 'Leave Application',
                'sentExamResultWhatsapp' => 'Exam Result',
                'sendPresentAttendancenotification' => 'Present Attendance',
                'sentPresentStaffWhatsapp' => 'Staff Present',
                'sentAbsentStaffWhatsapp' => 'Staff Absent',
                'sendAbsentAttendancenotification' => 'Absent Attendance',
                'sendstudentlhomework' => 'Homework Notification',
                'sentOnlineexamStudentWhatsapp' => 'Online Exam Notification',
                'sendOnlineadmissionformsubmit' => 'Online Admission Form',
                'sentstudentOnlineadmissionFeessubmissionWhatsapp' => 'Online Admission Fee Submission'
            );

            $trace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
            $caller_function = isset($trace[1]['function']) ? $trace[1]['function'] : 'Automated WhatsApp Message';

            // Only log if the caller function is one of the automated ones.
            // Manual broadcasts (via sendDynamicWhatsapp) are already logged by the Mailsms controller.
            if (isset($title_map[$caller_function])) {
                $title = $title_map[$caller_function];

                if (!empty($detail)) {
                    $name = '';
                    if (is_array($detail) && !empty($detail['student_name'])) {
                        $name = $detail['student_name'];
                    } elseif (is_object($detail) && !empty($detail->firstname)) {
                        $name = $detail->firstname . ' ' . $detail->lastname;
                    } elseif (is_array($detail) && !empty($detail['firstname'])) {
                        $name = $detail['firstname'] . ' ' . $detail['lastname'];
                    }
                    if ($name) {
                        $title .= " - " . $name;
                    }
                }

                $this->CI->load->model('messages_model');
                $user_list = array(
                    array(
                        'mobileno' => $mobile,
                        'app_key'  => ''
                    )
                );
                
                $message_data = array(
                    'title'              => $title,
                    'message'            => $message_text,
                    'append_roles'       => '',
                    'user_list'          => json_encode($user_list),
                    'is_group'           => 0,
                    'is_individual'      => 1,
                    'is_class'           => 0,
                    'send_mail'          => 0,
                    'send_sms'           => 0,
                    'send_whatsapp'      => 1,
                    'created_at'         => date('Y-m-d H:i:s'),
                );
                $message_id = $this->CI->messages_model->add($message_data);
                
                // Insert into whatsapp_message_logs
                $this->CI->db->insert('whatsapp_message_logs', array(
                    'message_id' => $message_id,
                    'mobileno'   => $mobile,
                    'status'     => 'Sent',
                    'created_at' => date('Y-m-d H:i:s')
                ));
            }

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
        return $this->_sendWhatsapp($send_to, $content, $detail);
    }

    public function sentAddFeeWhatsapp($detail, $send_to, $template, $template_id)
    {
        $content = $this->CI->smsgateway->getAddFeeContent($detail, $template, null);
        return $this->_sendWhatsapp($send_to, $content, $detail);
    }

    public function sentFeeProcessingNotification($detail, $send_to, $template, $template_id)
    {
        $content = $this->CI->smsgateway->getFeeProcessingContent($detail, $template, null);
        return $this->_sendWhatsapp($send_to, $content, $detail);
    }

    public function sentfeesreminderNotification($detail, $send_to, $template, $template_id)
    {
        $content = $this->CI->smsgateway->getContent($detail, $template, null);
        return $this->_sendWhatsapp($send_to, $content, $detail);
    }

    public function sendStudentLoginCredential($chk_mail_sms, $sender_details, $template, $template_id)
    {
        $content = $this->CI->smsgateway->getLoginCredentialContent($chk_mail_sms['student_recipient'], $sender_details, $template, null);
        return $this->_sendWhatsapp($sender_details['mobileno'], $content, $sender_details);
    }

    public function sendStaffLoginCredential($chk_mail_sms, $sender_details, $template, $template_id)
    {
        $content = $this->CI->smsgateway->getLoginCredentialContent($chk_mail_sms['staff_recipient'], $sender_details, $template, null);
        return $this->_sendWhatsapp($sender_details['contact_no'], $content, $sender_details);
    }

    public function student_apply_leave($sender_details, $template, $template_id)
    {
        $content = $this->CI->smsgateway->getstudent_apply_leaveContent($sender_details, $template, null);
        return $this->_sendWhatsapp($sender_details['mobileno'], $content, $sender_details); // Assuming mobileno
    }

    public function sentExamResultWhatsapp($detail, $template, $template_id)
    {
        $content = $this->CI->smsgateway->getStudentResultContent($detail, $template, null);
        return $this->_sendWhatsapp($detail['mobileno'], $content, $detail);
    }

    public function sendPresentAttendancenotification($detail, $template, $template_id, $send_to)
    {
        $content = $this->CI->smsgateway->getPresentStudentContent($detail, $template, null);
        return $this->_sendWhatsapp($send_to, $content, $detail);
    }

    public function sentPresentStaffWhatsapp($detail, $template, $template_id)
    {
        $content = $this->CI->smsgateway->getPresentStaffContent($detail, $template, null);
        return $this->_sendWhatsapp($detail['contact_no'], $content, $detail);
    }

    public function sentAbsentStaffWhatsapp($detail, $template, $template_id)
    {
        $content = $this->CI->smsgateway->getAbsentStaffContent($detail, $template, null);
        return $this->_sendWhatsapp($detail['contact_no'], $content, $detail);
    }

    public function sendAbsentAttendancenotification($detail, $template, $template_id, $send_to)
    {
        $content = $this->CI->smsgateway->getAbsentStudentContent($detail, $template, null);
        return $this->_sendWhatsapp($send_to, $content, $detail);
    }

    public function sendstudentlhomework($student_sms_list, $template, $template_id)
    {
        if(!empty($student_sms_list)){
            foreach($student_sms_list as $student){
                $content = $this->CI->smsgateway->getHomeworkStudentContent($student, $template, null);
                $this->_sendWhatsapp($student['mobileno'], $content, $student);
            }
        }
        return true;
    }

    public function sentOnlineexamStudentWhatsapp($student_sms_list, $template, $template_id)
    {
        if(!empty($student_sms_list)){
            foreach($student_sms_list as $student){
                $content = $this->CI->smsgateway->getOnlineexamStudentContent($student, $template, null);
                $this->_sendWhatsapp($student['mobileno'], $content, $student);
            }
        }
        return true;
    }

    public function sendOnlineadmissionformsubmit($student_details, $template, $send_to, $template_id)
    {
        $content = $this->CI->smsgateway->getOnlineadmissionStudentContent($student_details, $template);
        return $this->_sendWhatsapp($send_to, $content, $student_details);
    }

    public function sentstudentOnlineadmissionFeessubmissionWhatsapp($student_details, $template, $send_to, $template_id)
    {
        $content = $this->CI->smsgateway->getOnlineadmissionFeesContent($student_details, $template, null);
        return $this->_sendWhatsapp($send_to, $content, $student_details);
    }

    public function sendDynamicWhatsapp($mobile, $message_text)
    {
        return $this->_sendWhatsapp($mobile, $message_text);
    }
}
