<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Staffattendance extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('file');
        $this->config->load("mailsms");
        $this->config->load("payroll");
        $this->load->library('mailsmsconf');
        $this->config_attendance = $this->config->item('attendence');
        $this->staff_attendance  = $this->config->item('staffattendance');
        $this->load->model("staffattendancemodel");
        $this->load->model("staff_model");
        $this->load->model("payroll_model"); 
        $this->load->model("staffAttendaceSetting_model");
        $this->load->model("staffQrSetting_model");
    }

    public function index_old(){
        if (!($this->rbac->hasPrivilege('staff_attendance', 'can_view'))) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/staffattendance');
        $data['title']        = 'Staff Attendance List';
        $data['title_list']   = 'Staff Attendance List';
        $user_type            = $this->staff_model->getStaffRole();
        $data['sch_setting']  = $this->setting_model->getSetting();
        $data['classlist']    = $user_type;
        $data['class_id']     = "";
        $data['section_id']   = "";
        $data['date']         = "";
        $user_type_id         = $this->input->post('user_id');
        $data["user_type_id"] = $user_type_id;
        $staff_settings           = $this->staffAttendaceSetting_model->getRoleWiseAttendanceSetting($user_type_id);
        $data['staff_settings']   = $staff_settings;   

        if (!(isset($user_type_id))) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/staffattendance/staffattendancelist', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $user_type            = $this->input->post('user_id');
            $date                 = $this->input->post('date');
            $user_list            = $this->staffattendancemodel->get();
            $data['userlist']     = $user_list;
            $data['class_id']     = $user_list;
            $data['user_type_id'] = $user_type_id;
            $data['section_id']   = "";
            $data['date']         = $date;
            $is_first_time_attendance      = true;
            $search               = $this->input->post('search');
            $holiday              = $this->input->post('holiday');
            $this->session->set_flashdata('msg', '');
            if ($search == "saveattendence") {
            
                $user_type_ary       = $this->input->post('student_session');
                $attendance_array=[];
                $absent_staff_list=[];
                foreach ($user_type_ary as $key => $value) {
                    
                    $attendencetype = $this->input->post('attendencetype' . $value);
                  $in_time    =   $this->input->post("in_time_" . $value);
                  $out_time   =   $this->input->post("out_time_" . $value);

                    if((!isset($in_time) || $in_time=="") && (!isset($out_time) || $out_time=="")){
                        $in_time  = null;
                        $out_time = null;
                    }else{
                        $in_time=date('H:i:s', strtotime($this->input->post("in_time_" . $value)));
                        $out_time=date('H:i:s', strtotime($this->input->post("out_time_" . $value)));
                    }

                    $absent_config = $this->staff_attendance['absent'];
                
                    if ($attendencetype == $absent_config) {
                        $absent_staff_list[] = $value;
                    }

                    $attendance_array[] = array(                       
                        'staff_id'                 => $value,
                        'staff_attendance_type_id' => $this->input->post('attendencetype' . $value),
                        'remark'                   => $this->input->post("remark" . $value),
                        'in_time'                  => $in_time,
                        'out_time'                 => $out_time, 
                        'date'                     => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                        'updated_at'               => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                    );
                }
               
                $this->staffattendancemodel->addorUpdate($attendance_array);
                //added mail sms code //
                if (!empty($absent_staff_list)) {
                    $this->mailsmsconf->mailsms('staff_absent_attendence', $absent_staff_list, $date);
                }
                if (!empty($present_staff_list)) {
                    $this->mailsmsconf->mailsms('staff_present_attendence', $present_staff_list, $date);
                }
                // added mail sms code //

                $absent_config = $this->config_attendance['absent'];
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
                redirect('admin/staffattendance/index');
            }

            $attendencetypes             = $this->attendencetype_model->getStaffAttendanceType();
            $data['attendencetypeslist'] = $attendencetypes;        
            $resultlist                  = $this->staffattendancemodel->searchAttendenceUserType($user_type, date('Y-m-d', $this->customlib->datetostrtotime($date)));
            if (!empty($resultlist)) {
                foreach ($resultlist as $key => $value) {
                    if (!IsNullOrEmptyString($value['staff_attendance_type_id'])) {
                        $is_first_time_attendance = false;
                    }
                }
            }
            $data['is_first_time_attendance']  = $is_first_time_attendance;
            $data['resultlist']  = $resultlist;

            $this->load->view('layout/header', $data);
            $this->load->view('admin/staffattendance/staffattendancelist', $data);
            $this->load->view('layout/footer', $data);
        }
    }    

      public function index(){
        if (!($this->rbac->hasPrivilege('staff_attendance', 'can_view'))) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/staffattendance');
        $data['title']        = 'Staff Attendance List';
        $data['title_list']   = 'Staff Attendance List';
        $user_type            = $this->staff_model->getStaffRole();
        $data['sch_setting']  = $this->setting_model->getSetting();
        $data['classlist']    = $user_type;
        $data['class_id']     = "";
        $data['section_id']   = "";
        $data['date']         = "";
        $user_type_id         = $this->input->post('user_id');
        $data["user_type_id"] = $user_type_id;
        $staff_settings           = $this->staffAttendaceSetting_model->getRoleWiseAttendanceSetting($user_type_id);
        $data['staff_settings']   = $staff_settings;   

        if (!(isset($user_type_id))) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/staffattendance/staffattendancelist', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $user_type            = $this->input->post('user_id');
            $date                 = $this->input->post('date');
            $user_list            = $this->staffattendancemodel->get();
            $data['userlist']     = $user_list;
            $data['class_id']     = $user_list;
            $data['user_type_id'] = $user_type_id;
            $data['section_id']   = "";
            $data['date']         = $date;
            $is_first_time_attendance      = true;
            $search               = $this->input->post('search');
            $holiday              = $this->input->post('holiday');
            $this->session->set_flashdata('msg', '');
            if ($search == "saveattendence") {
            
                $user_type_ary       = $this->input->post('student_session');
                $attendance_array=[];
                $absent_staff_list=[];
                $present_staff_list=[];

                // Deploy-safe: only persist uniform status if the column exists (migration run).
                $has_uniform_col = $this->db->field_exists('uniform_status', 'staff_attendance');

                foreach ($user_type_ary as $key => $value) {

                  $attendencetype = $this->input->post('attendencetype' . $value);

                  // Skip staff with no attendance status selected. Prevents inserting a NULL
                  // attendance type (rejected under strict SQL mode) and avoids overwriting an
                  // existing record with a null type. Only marked staff are saved.
                  if ($attendencetype === null || $attendencetype === '' || $attendencetype === false) {
                      continue;
                  }

                  $in_time    =   $this->input->post("in_time_" . $value);
                  $out_time   =   $this->input->post("out_time_" . $value);

                    if((!isset($in_time) || $in_time=="") && (!isset($out_time) || $out_time=="")){
                        $in_time  = null;
                        $out_time = null;
                    }else{
                        $in_time=date('H:i:s', strtotime($this->input->post("in_time_" . $value)));
                        $out_time=date('H:i:s', strtotime($this->input->post("out_time_" . $value)));
                    }

                    $absent_config = $this->staff_attendance['absent'];

                    if ($attendencetype == $absent_config) {
                        $absent_staff_list[] = $value;
                    }else if(
                            ($attendencetype == $this->staff_attendance["present"]
                            || $attendencetype == $this->staff_attendance["late"]
                            || $attendencetype == $this->staff_attendance["half_day"]
                            || $attendencetype == $this->staff_attendance["half_day_second_shift"] ) && $this->input->post('is_first_time_attendance')
                        ){

                        $present_staff_list['staff_id'][$value] = ($value);
                        $present_staff_list['in_time'][$value] =$this->input->post("in_time_" . $value);
                    }

                    $single_attendance = array(
                        'staff_id'                 => $value,
                        'staff_attendance_type_id' => $this->input->post('attendencetype' . $value),
                        'remark'                   => $this->input->post("remark" . $value),
                        'in_time'                  => $in_time,
                        'out_time'                 => $out_time,
                        'date'                     => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                        'updated_at'               => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                    );

                    $compliance_items = [
                        'uniform_status'       => 'uniform_status_',
                        'id_card_status'       => 'id_card_status_',
                        'lesson_plan_status'   => 'lesson_plan_status_',
                        'phone_handover_status' => 'phone_handover_status_',
                    ];

                    foreach ($compliance_items as $col_name => $post_prefix) {
                        if ($this->db->field_exists($col_name, 'staff_attendance')) {
                            $val = $this->input->post($post_prefix . $value);
                            $single_attendance[$col_name] = ($val === 'yes') ? 'yes' : 'no';
                        }
                    }

                    $attendance_array[] = $single_attendance;
                }

                $this->staffattendancemodel->addorUpdate($attendance_array);
                //added mail sms code //
                if (!empty($absent_staff_list)) {
                    $this->mailsmsconf->mailsms('staff_absent_attendence', $absent_staff_list, $date);
                }

                if (!empty($present_staff_list)) {
                    $this->mailsmsconf->mailsms('staff_present_attendence', $present_staff_list, $date);
                }
                // added mail sms code //

                $absent_config = $this->config_attendance['absent'];
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
                redirect('admin/staffattendance/index');
            }

            $attendencetypes             = $this->attendencetype_model->getStaffAttendanceType();
            $data['attendencetypeslist'] = $attendencetypes;        
            $resultlist                  = $this->staffattendancemodel->searchAttendenceUserType($user_type, date('Y-m-d', $this->customlib->datetostrtotime($date)));
            if (!empty($resultlist)) {
                foreach ($resultlist as $key => $value) {
                    if (!IsNullOrEmptyString($value['staff_attendance_type_id'])) {
                        $is_first_time_attendance = false;
                    }
                }
            }
            $data['is_first_time_attendance']  = $is_first_time_attendance;
            $data['resultlist']  = $resultlist;

            $this->load->view('layout/header', $data);
            $this->load->view('admin/staffattendance/staffattendancelist', $data);
            $this->load->view('layout/footer', $data);
        }
    }  

    public function monthAttendance($st_month, $no_of_months, $emp)
    {
        $this->load->model("payroll_model");
        $record = array();
        $r     = array();
        $month = date('m', strtotime($st_month));
        $year  = date('Y', strtotime($st_month));
        foreach ($this->staff_attendance as $att_key => $att_value) {
            $s = $this->payroll_model->count_attendance_obj($month, $year, $emp, $att_value);
            $r[$att_key] = $s;
        }

        $record[$emp] = $r;
        return $record;
    }

    public function profileattendance()
    {
        $monthlist             = $this->customlib->getMonthDropdown();
        $startMonth            = $this->setting_model->getStartMonth();
        $data["monthlist"]     = $monthlist;
        $data['yearlist']      = $this->staffattendancemodel->attendanceYearCount();
        $staffRole             = $this->staff_model->getStaffRole();
        $data["role"]          = $staffRole;
        $data["role_selected"] = "";
        $j                     = 0;
        for ($i = 1; $i <= 31; $i++) {
            $att_date = sprintf("%02d", $i);
            $attendence_array[] = $att_date;
            foreach ($monthlist as $key => $value) {
                $datemonth       = date("m", strtotime($value));
                $att_dates       = date("Y") . "-" . $datemonth . "-" . sprintf("%02d", $i);
                $date_array[]    = $att_dates;
                $res[$att_dates] = $this->staffattendancemodel->searchStaffattendance($att_dates, $staff_id = 8);
            }

            $j++;
        }

        $data["resultlist"]       = $res;
        $data["attendence_array"] = $attendence_array;
        $data["date_array"]       = $date_array;
        $this->load->view("layout/header");
        $this->load->view("admin/staff/staffattendance", $data);
        $this->load->view("layout/footer");
    }

    /**
     * AJAX: render the monthly attendance sheet (grid + summary) for a role and
     * month. Returns an HTML partial loaded into the Monthly tab.
     */
    public function monthsheet()
    {
        if (!($this->rbac->hasPrivilege('staff_attendance', 'can_view'))) {
            access_denied();
        }

        $role  = $this->input->post('role');
        $month = (int) $this->input->post('month');
        $year  = (int) $this->input->post('year');
        if ($month < 1 || $month > 12) { $month = (int) date('m'); }
        if ($year < 2000) { $year = (int) date('Y'); }

        // Empty/"select" role = all staff.
        $sheet    = $this->staffattendancemodel->getMonthlySheet($role, $year, $month);
        $cal_hols = $this->staffattendancemodel->getMonthHolidayDates($year, $month);

        $days_in_month = (int) date('t', mktime(0, 0, 0, $month, 1, $year));

        // Holidays = Sundays (weekly off) + calendar holidays, de-duplicated.
        $holiday_dates = array();
        foreach ($cal_hols as $d) { $holiday_dates[$d] = true; }
        for ($d = 1; $d <= $days_in_month; $d++) {
            $ds = sprintf('%04d-%02d-%02d', $year, $month, $d);
            if ((int) date('w', strtotime($ds)) === 0) { $holiday_dates[$ds] = true; } // Sunday
        }

        $data['sheet']         = $sheet;
        $data['year']          = $year;
        $data['month']         = $month;
        $data['days_in_month'] = $days_in_month;
        $data['holiday_dates'] = $holiday_dates;
        $data['month_days']    = $days_in_month;
        $data['holidays']      = count($holiday_dates);
        $data['working_days']  = $days_in_month - count($holiday_dates);
        $data['type_list']     = $this->attendencetype_model->getStaffAttendanceType();

        $this->load->view('admin/staffattendance/_monthly_sheet', $data);
    }

    // =================================================================
    //  QR-BASED ATTENDANCE
    // =================================================================

    /**
     * Admin: configure QR attendance (mode, cooldown, IP/GPS restriction).
     */
    public function qrsettings()
    {
        if (!($this->rbac->hasPrivilege('staff_attendance', 'can_view'))) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/staffattendance/qrsettings');

        if ($this->input->post('save_qr_setting')) {
            if (!($this->rbac->hasPrivilege('staff_attendance', 'can_edit'))) {
                access_denied();
            }
            $posted_mode = $this->input->post('qr_mode');
            $mode = in_array($posted_mode, array('static', 'dynamic'), true) ? $posted_mode : 'daily';
            $interval = (int) $this->input->post('dynamic_interval_seconds');
            if ($interval < 5) { $interval = 30; }
            $save = array(
                'is_enabled'               => $this->input->post('is_enabled') ? 1 : 0,
                'qr_mode'                  => $mode,
                'dynamic_interval_seconds' => $interval,
                'rescan_cooldown_minutes'  => (int) $this->input->post('rescan_cooldown_minutes'),
                'earliest_out_source'      => ($this->input->post('earliest_out_source') === 'manual') ? 'manual' : 'schedule',
                'manual_earliest_out_time' => $this->input->post('manual_earliest_out_time') ?: null,
                'ip_allowlist'             => trim($this->input->post('ip_allowlist')),
                'gps_enabled'              => $this->input->post('gps_enabled') ? 1 : 0,
                'gps_lat'                  => ($this->input->post('gps_lat') === '') ? null : $this->input->post('gps_lat'),
                'gps_lng'                  => ($this->input->post('gps_lng') === '') ? null : $this->input->post('gps_lng'),
                'gps_radius_m'             => (int) ($this->input->post('gps_radius_m') ?: 200),
            );
            // Regenerate the static token on demand.
            if ($this->input->post('regenerate_static')) {
                $save['static_token'] = $this->staffQrSetting_model->generateToken();
            }
            $this->staffQrSetting_model->save($save);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/staffattendance/qrsettings');
        }

        $data['title']    = 'QR Attendance Settings';
        $data['setting']  = $this->staffQrSetting_model->get();
        $this->load->view('layout/header', $data);
        $this->load->view('admin/staffattendance/qrsettings', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Admin: full-screen QR to display at the entrance. In daily mode the
     * token auto-rotates at date change; the page also refreshes periodically.
     */
    public function qrdisplay()
    {
        if (!($this->rbac->hasPrivilege('staff_attendance', 'can_view'))) {
            access_denied();
        }

        $setting = $this->staffQrSetting_model->get();
        $token   = $this->staffQrSetting_model->getValidToken();
        $this->load->library('QR_Code');

        $data['title']       = 'Attendance QR';
        $data['setting']     = $setting;
        $data['qr_base64']   = $this->qr_code->generateBase64($token);
        $data['generated_at'] = date('d M Y, h:i A');
        $this->load->view('layout/header', $data);
        $this->load->view('admin/staffattendance/qrdisplay', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Live QR image endpoint the Display page polls in dynamic mode. Returns
     * the current token rendered as a base64 PNG plus the rotation interval.
     */
    public function qrimage()
    {
        if (!($this->rbac->hasPrivilege('staff_attendance', 'can_view'))) {
            access_denied();
        }
        $this->load->helper('json_output');
        $setting = $this->staffQrSetting_model->get();
        $token   = $this->staffQrSetting_model->getValidToken();
        $this->load->library('QR_Code');
        json_output(200, array(
            'img'      => $this->qr_code->generateBase64($token),
            'mode'     => $setting['qr_mode'],
            'interval' => max(5, (int) $setting['dynamic_interval_seconds']),
        ));
    }

    /**
     * Staff self-service: camera page to scan the displayed QR.
     */
    public function scan()
    {
        $admin = $this->session->userdata('admin');
        if (empty($admin['id'])) {
            access_denied();
        }
        $setting = $this->staffQrSetting_model->get();

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/staffattendance/scan');
        $data['title']       = 'Mark My Attendance';
        $data['setting']     = $setting;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/staffattendance/scan', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Staff self-service AJAX endpoint: validate the scanned token +
     * location, then mark in/out via the guarded model method.
     */
    public function markqr()
    {
        $this->load->helper('json_output');
        $admin = $this->session->userdata('admin');
        if (empty($admin['id'])) {
            json_output(401, array('status' => 'error', 'message' => 'Your session has expired. Please log in again.'));
            return;
        }

        $setting = $this->staffQrSetting_model->get();
        if (empty($setting['is_enabled'])) {
            json_output(200, array('status' => 'error', 'message' => 'QR attendance is currently disabled.'));
            return;
        }

        $token = $this->input->post('token');
        if (!$this->staffQrSetting_model->isTokenValid($token)) {
            json_output(200, array('status' => 'error', 'message' => 'Invalid or expired QR code. Please scan the current code on screen.'));
            return;
        }

        // IP allowlist restriction.
        if (!IsNullOrEmptyString($setting['ip_allowlist'])) {
            $allow = array_filter(array_map('trim', explode(',', $setting['ip_allowlist'])));
            $ip    = $this->input->ip_address();
            if (!empty($allow) && !$this->ipAllowed($ip, $allow)) {
                json_output(200, array('status' => 'error', 'message' => 'You must be on the school network to mark attendance.'));
                return;
            }
        }

        // GPS proximity restriction.
        if (!empty($setting['gps_enabled'])) {
            $lat = $this->input->post('lat');
            $lng = $this->input->post('lng');
            if ($lat === null || $lat === '' || $lng === null || $lng === '') {
                json_output(200, array('status' => 'error', 'message' => 'Location access is required. Please enable location and try again.'));
                return;
            }
            $dist = $this->haversineMeters((float) $setting['gps_lat'], (float) $setting['gps_lng'], (float) $lat, (float) $lng);
            if ($dist > (float) $setting['gps_radius_m']) {
                json_output(200, array('status' => 'error', 'message' => 'You are too far from the school to mark attendance.'));
                return;
            }
        }

        $role   = json_decode($this->customlib->getStaffRole());
        $opts   = array(
            'cooldown_minutes'         => $setting['rescan_cooldown_minutes'],
            'earliest_out_source'      => $setting['earliest_out_source'],
            'manual_earliest_out_time' => $setting['manual_earliest_out_time'],
            'confirm_early'            => (bool) $this->input->post('confirm_early'),
            'reason'                   => $this->input->post('reason'),
            'source'                   => 'qr',
        );

        // Mid-day outing actions (step out / step in) and the explicit
        // end-of-day check-out are driven by an "action" the scan page sends
        // after showing the teacher their options.
        $action = $this->input->post('action');
        if ($action === 'break_out') {
            json_output(200, $this->staffattendancemodel->qrBreakOut($admin['id'], $this->input->post('reason')));
            return;
        }
        if ($action === 'break_in') {
            json_output(200, $this->staffattendancemodel->qrBreakIn($admin['id']));
            return;
        }
        if ($action === 'final_out') {
            json_output(200, $this->staffattendancemodel->qrMark($admin['id'], $role->id, $opts));
            return;
        }

        // No explicit action: mark in on the first scan; otherwise return the
        // available choices so the teacher can pick step-out vs leave-for-day.
        $state = $this->staffattendancemodel->getQrState($admin['id']);
        if ($state['state'] === 'not_in') {
            json_output(200, $this->staffattendancemodel->qrMark($admin['id'], $role->id, $opts));
            return;
        }
        if ($state['state'] === 'on_break') {
            json_output(200, array(
                'status'  => 'choose',
                'actions' => array('break_in'),
                'message' => 'You stepped out at ' . $state['since'] . '. Scan confirmed — step back in?',
            ));
            return;
        }
        if ($state['state'] === 'complete') {
            json_output(200, array('status' => 'already_complete', 'message' => 'Your attendance for today is already complete (in ' . $state['in'] . ', out ' . $state['out'] . ').'));
            return;
        }
        // state === 'in'
        json_output(200, array(
            'status'  => 'choose',
            'actions' => array('break_out', 'final_out'),
            'message' => 'You are checked in (' . $state['in'] . '). What would you like to do?',
        ));
    }

    /**
     * Match an IP against the allowlist. Supports exact IPs and simple
     * prefix entries (e.g. "203.0.113." matches 203.0.113.x).
     */
    private function ipAllowed($ip, $allow)
    {
        foreach ($allow as $entry) {
            if ($entry === $ip) {
                return true;
            }
            if (substr($entry, -1) === '.' && strpos($ip, $entry) === 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Great-circle distance between two lat/lng points, in metres.
     */
    private function haversineMeters($lat1, $lng1, $lat2, $lng2)
    {
        $r = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);
        return $r * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }

}
