<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Cron extends MY_Controller
{

    protected $cron_key;

    /**
     * This is default constructor of the class
     */
    public function __construct($key = "")
    {
        parent::__construct();
        $setting_result = $this->setting_model->getSetting();
        $this->cron_key = $setting_result->cron_secret_key;
        $this->load->model('feereminder_model');
        $this->load->model('feegrouptype_model');
        $this->load->model('studentfeemaster_model');
        $this->load->model('calendar_model');
        $this->load->model('customfield_model');
        $this->load->model('class_section_time_model');
        $this->load->model('stuattendence_model');
        $this->load->model('student_model');
        $this->load->model('staff_model');
        $this->load->library('customlib');
        $this->load->helper('custom');
        if ($this->customlib->getTimeZone()) {
            date_default_timezone_set($this->customlib->getTimeZone());
        } else {
            return date_default_timezone_set('UTC');
        }
    }

    public function index($key = '')
    {
        if ($key == 'test_digest') {
            $this->test_digest();
            return;
        }

        if ($key == 'test_autobackup') {
            $this->autobackup($this->cron_key);
            echo "autobackup completed successfully";
            return;
        }

        if ($key == 'test_feereminder') {
            $this->feereminder($this->cron_key);
            echo "feereminder completed successfully";
            return;
        }

        if ($key == 'test_eventreminder') {
            $this->eventreminder($this->cron_key);
            echo "eventreminder completed successfully";
            return;
        }

        if ($key == 'test_schedulesmsemails') {
            $this->schedulesmsemails($this->cron_key);
            echo "schedulesmsemails completed successfully";
            return;
        }

        if ($key == 'test_send_email_digests') {
            $this->send_email_digests($this->cron_key);
            echo "send_email_digests completed successfully";
            return;
        }

        if ($key == 'test_studentcall') {
            $this->studentcall_followup_reminder($this->cron_key);
            echo "studentcall completed successfully";
            return;
        }

        if ($key == 'test_vehicle') {
            $this->vehicle_expiration_reminder($this->cron_key);
            echo "vehicle completed successfully";
            return;
        }

        if ($key != "" && $this->cron_key == $key) {
            try { $this->autobackup($key); } catch (Throwable $e) { log_message('error', 'Cron autobackup error: ' . $e->getMessage()); }
            try { $this->feereminder($key); } catch (Throwable $e) { log_message('error', 'Cron feereminder error: ' . $e->getMessage()); }
            try { $this->eventreminder($key); } catch (Throwable $e) { log_message('error', 'Cron eventreminder error: ' . $e->getMessage()); }
            try { $this->schedulesmsemails($key); } catch (Throwable $e) { log_message('error', 'Cron schedulesmsemails error: ' . $e->getMessage()); }
            try { $this->send_email_digests($key); } catch (Throwable $e) { log_message('error', 'Cron send_email_digests error: ' . $e->getMessage()); }
            try { $this->studentcall_followup_reminder($key); } catch (Throwable $e) { log_message('error', 'Cron studentcall_followup_reminder error: ' . $e->getMessage()); }
            try { $this->vehicle_expiration_reminder($key); } catch (Throwable $e) { log_message('error', 'Cron vehicle_expiration_reminder error: ' . $e->getMessage()); }
            echo "Cron executed successfully.";
        } else {
            echo "Invalid Key or Direct access is not allowed";
            return;
        }
    }

    public function studentcall_followup_reminder($key = "")
    {
        if ($key != "") {
            if ($key != "" && $this->cron_key != $key) {
                echo "Invalid Key or Direct access is not allowed";
                return;
            }

            $this->load->model('SystemNotificationSetting_model');
            if ($this->SystemNotificationSetting_model->check_setting('student_call_log')) {
                $this->load->model('studentcall_model');
                $this->load->model('SystemNotification_model');

                $pending_followups = $this->studentcall_model->get_all_pending_followups_for_reminder();

                if (!empty($pending_followups)) {
                    foreach ($pending_followups as $followup) {
                        $message = "Reminder: You have a pending follow-up due for student {$followup['firstname']} {$followup['lastname']} ({$followup['admission_no']}). Phone: {$followup['phone_number']}";
                        $this->SystemNotification_model->notifyUser($followup['assigned_to'], 'Follow-up Reminder', $message, 'admin/studentcall');
                    }
                }
            }
        }
    }

    public function vehicle_expiration_reminder($key = "")
    {
        if ($key != "") {
            if ($key != "" && $this->cron_key != $key) {
                echo "Invalid Key or Direct access is not allowed";
                return;
            }

            $this->load->model('SystemNotificationSetting_model');
            if ($this->SystemNotificationSetting_model->check_setting('vehicle_alerts')) {
                $this->load->model('vehicle_model');
                $this->load->model('staff_model');
                $this->load->model('SystemNotification_model');

                $vehicles = $this->vehicle_model->get();
                $notify_days = [15, 7, 5, 3, 2, 1];
                $today = new DateTime(date('Y-m-d'));

                $check_fields = [
                    'insurance_expiry' => 'Insurance',
                    'permit_expiry' => 'Permit',
                    'fitness_expiry' => 'Fitness',
                    'puc_expiry' => 'PUC',
                    'license_expiry' => 'License',
                    'fire_extinguisher_expiry' => 'Fire Extinguisher',
                    'next_maintenance_due' => 'Maintenance'
                ];

                if (!empty($vehicles)) {
                    foreach ($vehicles as $v) {
                        foreach ($check_fields as $field => $label) {
                            if (!empty($v[$field]) && $v[$field] != '0000-00-00') {
                                $exp_date = new DateTime($v[$field]);
                                $diff = $today->diff($exp_date);
                                $days = (int)$diff->format('%R%a'); // positive if in future, negative if past

                                $trigger = false;
                                if (in_array($days, $notify_days)) {
                                    $trigger = true;
                                    $msg_text = "expires in {$days} days";
                                } else if ($days <= 0) {
                                    $trigger = true;
                                    $msg_text = ($days == 0) ? "expires TODAY" : "is OVERDUE";
                                }

                                if ($trigger) {
                                    $message = "Vehicle {$v['vehicle_no']} {$label} {$msg_text} ({$v[$field]}).";
                                    
                                    // Notify Super Admin
                                    $this->SystemNotification_model->notifyRole(7, 'Vehicle Expiration Alert', $message, 'admin/vehicle');
                                    
                                    // Notify Driver
                                    if (!empty($v['driver_name'])) {
                                        $driver_id = $this->staff_model->getStaffByName($v['driver_name']);
                                        if ($driver_id) $this->SystemNotification_model->notifyUser($driver_id, 'Vehicle Expiration Alert', $message, 'admin/vehicle');
                                    }

                                    // Notify Attendant(s)
                                    if (!empty($v['attendant_name'])) {
                                        $att_list = array_filter(array_map('trim', explode(',', $v['attendant_name'])));
                                        foreach ($att_list as $single_att_name) {
                                            $attendant_id = $this->staff_model->getStaffByName($single_att_name);
                                            if ($attendant_id) {
                                                $this->SystemNotification_model->notifyUser($attendant_id, 'Vehicle Expiration Alert', $message, 'admin/vehicle');
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function student_attendance($key=""){

           if ($key != "" && $this->cron_key != $key) {
                echo "Invalid Key or Direct access is not allowed";
                return;
            }

          $time=date('H:i:s');
          $date=date('Y-m-d');
          $class_sections=$this->class_section_time_model->getAttendanceNotSubmittedByTime($date,$time);
         
          if(!empty($class_sections)){
            $attendance_data=array();
                foreach ($class_sections as $class_key => $class_value) {
                    $attendance_data[]=[
                                        'student_session_id'=>$class_value->student_session_id,
                                        'date'=>date('Y-m-d'),
                                        'attendence_type_id'=>4,
                                      ];
             
                }
            $this->stuattendence_model->batch_insert($attendance_data);
          }

    }

    public function autobackup($key = '')
    {
        if ($key != "") {
            if ($key != "" && $this->cron_key != $key) {
                echo "Invalid Key or Direct access is not allowed";
                return;
            }

            $dir = './backup/database_backup/';
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }

            $this->load->dbutil();
            $version  = $this->customlib->getAppVersion();
            $filename = "db_ver_" . $version . '_' . date("Y-m-d_H-i-s") . ".sql";
            $prefs    = array(
                'ignore'     => array(),
                'format'     => 'txt',
                'filename'   => 'mybackup.sql',
                'add_drop'   => true,
                'add_insert' => true,
                'newline'    => "\n",
            );
            $backup = $this->dbutil->backup($prefs);
            $this->load->helper('file');
            write_file($dir . $filename, $backup);
        }
    }

    public function feereminder($key = "")
    {
        $setting_result = $this->setting_model->getSetting();
        if ($key != "") {
            if ($key != "" && $this->cron_key != $key) {
                echo "Invalid Key or Direct access is not allowed";
                return;
            }
       
            $this->load->library('mailsmsconf');
            $this->load->model('feegrouptype_model');
            $this->load->model('studentfeemaster_model');
            $feereminder   = $this->feereminder_model->get(null, 1);

            $reminter_type = array();
            $studentList   = array();

            if (!empty($feereminder)) {
                foreach ($feereminder as $feereminder_key => $feereminder_value) {
                    if ($feereminder_value->reminder_type == "before") {

                        $date               = date('Y-m-d', strtotime('+' . $feereminder_value->day . ' days'));
                        $fees_type_reminder = $this->feegrouptype_model->getFeeTypeDueDateReminder($date);


                        if (!empty($fees_type_reminder)) {

                            foreach ($fees_type_reminder as $reminder_key => $reminder_value) {

                                $students = $this->feegrouptype_model->getFeeTypeStudents($reminder_value->fee_session_group_id, $reminder_value->id);

                                foreach ($students as $student_key => $student_value) {
                                    $students[$student_key]->{'fee_category'}       = "fees";       
                                    $students[$student_key]->{'fee_group_name'}       = $reminder_value->fee_group_name;
                                    $students[$student_key]->{'due_date'}       = $date;
                                    $students[$student_key]->{'fee_type'}       = $reminder_value->type;
                                    $students[$student_key]->{'fee_code'}       = $reminder_value->code;
                                    $students[$student_key]->{'fee_amount'}     = $reminder_value->amount;
                                    $students[$student_key]->{'due_amount'}     = $reminder_value->amount;
                                    $students[$student_key]->{'deposit_amount'} = number_format((float) 0, 2, '.', '');
                                    $fees_array                                 = json_decode($student_value->amount_detail);
                                    if (json_last_error() == JSON_ERROR_NONE && (is_array($fees_array) || is_object($fees_array))) {
                                        $deposit_amount = 0;
                                        foreach ($fees_array as $fee_collected_key => $fee_collected_value) {
                                            $deposit_amount = $deposit_amount + ($fee_collected_value->amount + $fee_collected_value->amount_discount);
                                        };
                                        $students[$student_key]->{'deposit_amount'} = number_format((float) ($deposit_amount), 2, '.', '');
                                        $students[$student_key]->{'due_amount'}     = number_format((float) ($reminder_value->amount - $deposit_amount), 2, '.', '');
                                    };
                                    $students[$student_key]->{'student_name'} = $this->customlib->getFullName($student_value->firstname, $student_value->middlename, $student_value->lastname, $setting_result->middlename, $setting_result->lastname);
                                    $studentList[]                            = $student_value;
                                }
                            }
                        }
                        $transport_fees= $this->studentfeemaster_model->getTransportFeesByDueDate($date, $date);               


                           if (!empty($transport_fees)) {

                            foreach ($transport_fees as $reminder_key => $reminder_value) {

                                    $transport_fees[$reminder_key]->{'fee_category'}       ="transport";
                                    $transport_fees[$reminder_key]->{'fee_group_name'}   = "Transport";
                                    $transport_fees[$reminder_key]->{'due_date'}       = $date;
                                    $transport_fees[$reminder_key]->{'fee_type'}       = $reminder_value->month;
                                    $transport_fees[$reminder_key]->{'fee_code'}       = "-";
                                    $transport_fees[$reminder_key]->{'fee_amount'}     = $reminder_value->fees;
                                    $transport_fees[$reminder_key]->{'due_amount'}     = $reminder_value->fees;
                                    $transport_fees[$reminder_key]->{'deposit_amount'} = number_format((float) 0, 2, '.', '');
                                    $fees_array                                 = json_decode($reminder_value->amount_detail);
                                    if (isJSON($reminder_value->amount_detail) && (is_array($fees_array) || is_object($fees_array))) {
                                        $deposit_amount = 0;
                                        foreach ($fees_array as $fee_collected_key => $fee_collected_value) {
                                            $deposit_amount = $deposit_amount + ($fee_collected_value->amount + $fee_collected_value->amount_discount);
                                        };
                                        $transport_fees[$reminder_key]->{'deposit_amount'} = number_format((float) ($deposit_amount), 2, '.', '');
                                        $transport_fees[$reminder_key]->{'due_amount'}     = number_format((float) ($reminder_value->fees - $deposit_amount), 2, '.', '');
                                    };
                                    $transport_fees[$reminder_key]->{'student_name'} = $this->customlib->getFullName($reminder_value->firstname, $reminder_value->middlename, $reminder_value->lastname, $setting_result->middlename, $setting_result->lastname);
                                    $studentList[]                            = $reminder_value;
                               
                            }
                        }


                    } else if ($feereminder_value->reminder_type == "after") {

                        $date               = date('Y-m-d', strtotime('-' . $feereminder_value->day . ' days'));
                        $fees_type_reminder = $this->feegrouptype_model->getFeeTypeDueDateReminder($date);

                        if (!empty($fees_type_reminder)) {
                            foreach ($fees_type_reminder as $reminder_key => $reminder_value) {

                                $students = $this->feegrouptype_model->getFeeTypeStudents($reminder_value->fee_session_group_id, $reminder_value->id);

                                foreach ($students as $student_key => $student_value) {
                                    $students[$student_key]->{'fee_category'}       = "fees";
                                    $students[$student_key]->{'due_date'}       = $date;
                                    $students[$student_key]->{'fee_group_name'}       = $reminder_value->fee_group_name;
                                    $students[$student_key]->{'fee_type'}       = $reminder_value->type;
                                    $students[$student_key]->{'fee_code'}       = $reminder_value->code;
                                    $students[$student_key]->{'fee_amount'}     = $reminder_value->amount;
                                    $students[$student_key]->{'due_amount'}     = $reminder_value->amount;
                                    $students[$student_key]->{'deposit_amount'} = number_format((float) 0, 2, '.', '');
                                    $fees_array                                 = json_decode($student_value->amount_detail);
                                    if (json_last_error() == JSON_ERROR_NONE && (is_array($fees_array) || is_object($fees_array))) {
                                        $deposit_amount = 0;
                                        foreach ($fees_array as $fee_collected_key => $fee_collected_value) {

                                            $deposit_amount = $deposit_amount + ($fee_collected_value->amount + $fee_collected_value->amount_discount);
                                        };
                                        $students[$student_key]->{'deposit_amount'} = number_format((float) ($deposit_amount), 2, '.', '');
                                        $students[$student_key]->{'due_amount'}     = number_format((float) ($reminder_value->amount - $deposit_amount), 2, '.', '');
                                    };

                                    $students[$student_key]->{'student_name'} = $this->customlib->getFullName($student_value->firstname, $student_value->middlename, $student_value->lastname, $setting_result->middlename, $setting_result->lastname);
                                    $students[$student_key]->{'school_name'}  = $this->customlib->getSchoolName();
                                    $studentList[]                            = $student_value;
                                }
                            }
                        }

                        $transport_fees= $this->studentfeemaster_model->getTransportFeesByDueDate($date, $date);
					   
                            if (!empty($transport_fees)) {

                             foreach ($transport_fees as $reminder_key => $reminder_value) {

                                     $transport_fees[$reminder_key]->{'fee_category'}       ="transport";
                                     $transport_fees[$reminder_key]->{'fee_group_name'}   = "Transport";
                                     $transport_fees[$reminder_key]->{'due_date'}       = $date;
                                     $transport_fees[$reminder_key]->{'fee_type'}       = $reminder_value->month;
                                     $transport_fees[$reminder_key]->{'fee_code'}       = "-";
                                     $transport_fees[$reminder_key]->{'fee_amount'}     = $reminder_value->fees;
                                     $transport_fees[$reminder_key]->{'due_amount'}     = $reminder_value->fees;
                                     $transport_fees[$reminder_key]->{'deposit_amount'} = number_format((float) 0, 2, '.', '');
                                     $fees_array                                 = json_decode($reminder_value->amount_detail);
                                     if (isJSON($reminder_value->amount_detail) && (is_array($fees_array) || is_object($fees_array))) {
                                         $deposit_amount = 0;
                                         foreach ($fees_array as $fee_collected_key => $fee_collected_value) {
                                             $deposit_amount = $deposit_amount + ($fee_collected_value->amount + $fee_collected_value->amount_discount);
                                         };
                                         $transport_fees[$reminder_key]->{'deposit_amount'} = number_format((float) ($deposit_amount), 2, '.', '');
                                         $transport_fees[$reminder_key]->{'due_amount'}     = number_format((float) ($reminder_value->fees - $deposit_amount), 2, '.', '');
                                     };
                                     $transport_fees[$reminder_key]->{'student_name'} = $this->customlib->getFullName($reminder_value->firstname, $reminder_value->middlename, $reminder_value->lastname, $setting_result->middlename, $setting_result->lastname);
                                     $studentList[]                            = $reminder_value;
                                
                             }
                         }
                    }
                }

                if (!empty($studentList)) {
                    foreach ($studentList as $eachStudent_key => $eachStudent_value) {
                        if ($eachStudent_value->due_amount <= 0) {
                            unset($studentList[$eachStudent_key]);
                        }
                    }
                }

                if (!empty($studentList)) {
                    foreach ($studentList as $eachStudent_key => $eachStudent_value) {

                        $this->mailsmsconf->mailsms('fees_reminder', $eachStudent_value);
                    
                    }
                }
            }
        }
    }

    public function eventreminder($key = "")
    {
        $setting_result = $this->setting_model->getSetting();

        if ($key != "") {
            if ($key != "" && $this->cron_key != $key) {
                echo "Invalid Key or Direct access is not allowed";
                return;
            }
            $this->load->library('mailsmsconf');

            $event_reminder = array();

            if ($setting_result->event_reminder == "enabled") {

                $date = date('Y-m-d', strtotime('+' . $setting_result->calendar_event_reminder . ' days'));

                $event_reminder = $this->calendar_model->geteventreminder($date);

                if (!empty($event_reminder)) {
                    foreach ($event_reminder as $event_reminder_key => $event_reminder_value) {

                        if ($event_reminder_value['event_type'] == 'private') {
                            $event_email = $this->staff_model->getstaffemail($event_reminder_value['event_for']);
                            if (!empty($event_email)) {
                                foreach ($event_email as $event_email_key => $event_email_value) {
                                    $event_reminder[$event_reminder_key]['event_email_list'][] = $event_email_value['email'];
                                }
                            }
                        } else if ($event_reminder_value['event_type'] == 'sameforall') {
                            $event_email = $this->staff_model->getEmployee($event_reminder_value['event_for'], 1);
                            if (!empty($event_email)) {
                                foreach ($event_email as $event_email_key => $event_email_value) {
                                    $event_reminder[$event_reminder_key]['event_email_list'][] = $event_email_value['email'];
                                }
                            }
                        } else if ($event_reminder_value['event_type'] == 'public') {
                            $event_email = $this->calendar_model->getstaffandstudentemail();
                            if (!empty($event_email)) {
                                foreach ($event_email as $event_email_key => $event_email_value) {
                                    $event_reminder[$event_reminder_key]['event_email_list'][] = $event_email_value['email'];
                                }
                            }
                        } else if ($event_reminder_value['event_type'] == 'protected') {
                            $event_email = $this->staff_model->searchFullText("", 1);
                            if (!empty($event_email)) {
                                foreach ($event_email as $event_email_key => $event_email_value) {
                                    $event_reminder[$event_reminder_key]['event_email_list'][] = $event_email_value['email'];
                                }
                            }
                        }

                        if (!empty($event_reminder)) {
                            foreach ($event_reminder as $event_reminder_value) {
                                $this->mailsmsconf->sendEailEventReminder($event_reminder_value);
                            }
                        }
                    }
                }
            }
        }
    }

    public function schedulesmsemails($key = "")
    {
        $this->load->library('mailer');
        $this->load->model('messages_model');
        $this->load->library('smsgateway');
        $userdata     = $this->messages_model->get_scheduledata(date('Y-m-d H:i'));
        
        $current_date = date('Y-m-d H:i:s');
        foreach ($userdata as $key => $value) {
               
            $user_list = json_decode($value['user_list'], true);

            if ($value['schedule_date_time'] <= $current_date) {
                $attachments = $this->messages_model->get_message_attachment($value['id']);                
 
                foreach ($user_list as $user_listkey => $user_listvalue) {
                    
                    if($user_listvalue['role']=='student'){
                        
                        $user_student   =   $this->student_model->getstudentdetailbyid($user_listvalue['user_id']);
                        $email  =   $user_student['email'];
                        $phone  =   $user_student['mobileno'];
                        
                    }elseif($user_listvalue['role']=='parent'){
                        
                        $user_parent   =   $this->student_model->getstudentdetailbyid($user_listvalue['user_id']);
                        $email  =   $user_parent['guardian_email'];
                        $phone  =   $user_parent['guardian_phone'];
                        
                    }elseif($user_listvalue['role']=='staff'){
                        
                        $user_staff   =   $this->staff_model->getProfile($user_listvalue['user_id']);
                        $email  =   $user_staff['email'];
                        $phone  =   $user_staff['contact_no'];
                        
                    }                 
                    
                    if (!empty($email) && $value['send_mail'] == 1) {
                        $this->mailer->compose_mail($email, $value['title'], $value['message'], $attachments);
                    }

                    if (!empty($phone) && $value['send_sms'] == 1) {
                        $this->smsgateway->sendSMS($phone, $value['message'], $value['title']);
                    }

                }
                
                $insert['id']   = $value['id'];
                $insert['sent'] = 1;
                $this->messages_model->add($insert);

            }
        }
    }

    public function send_email_digests($key = "")
    {
        if ($key != "" && $this->cron_key != $key) {
            echo "Invalid Key or Direct access is not allowed";
            return;
        }

        $setting_result = $this->setting_model->getSetting();
        $recipients = $setting_result->email_digest_recipients;

        if (empty($recipients)) {
            return;
        }

        $current_time = date('H:i');
        $is_daily = (!empty($setting_result->daily_digest_time) && $current_time == date('H:i', strtotime($setting_result->daily_digest_time)));
        $is_weekly = (!empty($setting_result->weekly_digest_time) && date('w') == 0 && $current_time == date('H:i', strtotime($setting_result->weekly_digest_time)));
        $is_monthly = (!empty($setting_result->monthly_digest_time) && date('d') == 1 && $current_time == date('H:i', strtotime($setting_result->monthly_digest_time)));

        if ($is_daily || $is_weekly || $is_monthly) {
            $this->load->library('brevogateway');
            $this->load->model('digest_model');
            
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d');
            $period_name = 'Daily';
            
            if ($is_monthly) {
                $start_date = date('Y-m-01');
                $end_date = date('Y-m-t');
                $period_name = 'Monthly';
            } elseif ($is_weekly) {
                $start_date = date('Y-m-d', strtotime('-6 days'));
                $end_date = date('Y-m-d');
                $period_name = 'Weekly';
            }
            
            $stats = $this->digest_model->get_stats($start_date, $end_date);
            
            $recipient_list = array_map('trim', explode(',', $recipients));
            
            foreach ($recipient_list as $email) {
                if (empty($email)) continue;
                
                // Lookup name
                $staff = $this->db->select('name, surname')->where('email', $email)->get('staff')->row();
                $recipient_name = $staff ? trim($staff->name . ' ' . $staff->surname) : 'Administrator';
                
                $data = [
                    'is_daily' => $is_daily,
                    'is_weekly' => $is_weekly,
                    'is_monthly' => $is_monthly,
                    'period_name' => $period_name,
                    'stats' => $stats,
                    'recipient_name' => $recipient_name
                ];
                
                $html_content = $this->load->view('emailconfig/digest_report', $data, true);
                $subject = "Sunrise ERP " . $period_name . " Digest Report";
                
                $this->brevogateway->send_digest($email, $subject, $html_content);
            }
        }
    }
    public function test_digest()
    {
        try {
            echo "Testing Digest...\n";
            $this->load->library('brevogateway');
            $this->load->model('digest_model');
            
            $setting_result = $this->setting_model->getSetting();
            $recipients = isset($setting_result->email_digest_recipients) ? $setting_result->email_digest_recipients : '';

            if (empty($recipients)) {
                echo "Error: No recipients configured in General Settings.\n";
                return;
            }

            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d');
            $period_name = 'Test Daily';

            $stats = $this->digest_model->get_stats($start_date, $end_date);
            
            $recipient_list = array_map('trim', explode(',', $recipients));
            $success_count = 0;
            
            foreach ($recipient_list as $email) {
                if (empty($email)) continue;
                
                // Lookup name
                $staff = $this->db->select('name, surname')->where('email', $email)->get('staff')->row();
                $recipient_name = $staff ? trim(($staff->name ?? '') . ' ' . ($staff->surname ?? '')) : 'Administrator';
                if (empty(trim($recipient_name))) {
                    $recipient_name = 'Administrator';
                }
                
                $data = [
                    'is_daily' => true,
                    'is_weekly' => false,
                    'is_monthly' => false,
                    'period_name' => $period_name,
                    'stats' => $stats,
                    'recipient_name' => $recipient_name
                ];
                
                $html_content = $this->load->view('emailconfig/digest_report', $data, true);
                $subject = "Sunrise ERP " . $period_name . " Digest Report (TEST)";
                
                if ($this->brevogateway->send_digest($email, $subject, $html_content)) {
                    $success_count++;
                    echo "Digest sent successfully to: " . $email . "\n";
                } else {
                    echo "Failed to send digest to: " . $email . ". Check application logs for Brevo API error.\n";
                }
            }
            
            if ($success_count == 0) {
                echo "Failed to send any test digests.\n";
            }
        } catch (Throwable $e) {
            log_message('error', 'Error in test_digest: ' . $e->getMessage());
            echo "Error in test_digest: " . $e->getMessage() . "\n";
        }
    }

}
