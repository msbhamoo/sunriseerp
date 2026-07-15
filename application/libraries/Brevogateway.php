<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Brevogateway
{
    private $CI;
    private $api_key = '';
    private $sender_email = 'info@sunriseschool.in';
    private $sender_name = 'Sunrise ERP';

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->model('emailconfig_model');
        $this->CI->load->model('setting_model');
        $emailconfig = $this->CI->emailconfig_model->get();
        if ($emailconfig && $emailconfig->email_type == 'brevo_api') {
            $this->api_key = $emailconfig->api_key;
        }
        
        $setting = $this->CI->setting_model->getSetting();
        if ($setting && !empty($setting->name)) {
            $this->sender_name = $setting->name;
        }
    }

    public function send_digest($to_emails_csv, $subject, $html_body)
    {
        if (empty($this->api_key)) {
            log_message('error', 'Brevo API Key is missing or not configured as active email engine.');
            return false;
        }

        $emails = array_map('trim', explode(',', $to_emails_csv));
        $to_array = [];
        foreach ($emails as $email) {
            if (!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $to_array[] = ['email' => $email];
            }
        }

        if (empty($to_array)) {
            log_message('error', 'No valid recipient emails found for Brevo Digest.');
            return false;
        }

        $post_data = [
            'sender' => ['name' => $this->sender_name, 'email' => $this->sender_email],
            'to' => $to_array,
            'subject' => $subject,
            'htmlContent' => $html_body
        ];

        $ch = curl_init('https://api.brevo.com/v3/smtp/email');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Fix for WAMP localhost SSL issues
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            'api-key: ' . $this->api_key,
            'content-type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
        
        $response = curl_exec($ch);
        $curl_err = curl_error($ch);
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_status >= 200 && $http_status < 300) {
            return true;
        } else {
            $err_msg = 'Brevo API error (' . $http_status . '): ' . $response . ($curl_err ? ' cURL Error: ' . $curl_err : '');
            log_message('error', $err_msg);
            // Quick debug for testing
            if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], 'test_digest') !== false) {
                echo "<br><br><strong>Brevo API Error Details:</strong><br>";
                echo $err_msg . "<br>";
            }
            return false;
        }
    }
}
