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
        $this->load->model("StaffBiometricSetting_model");
        $this->load->library("biometric_lib");
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
        $this->load->model('staffattendancecorrection_model');
        $data['correction_pending'] = $this->staffattendancecorrection_model->pendingCount();

        if (!(isset($user_type_id))) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/staffattendance/staffattendancelist', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $user_type            = $this->input->post('user_id');
            $date                 = $this->input->post('date');
            $data['userlist']     = [];
            $data['class_id']     = "";
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

                // Cache column existence once before loop to avoid repetitive queries per staff
                $available_compliance_cols = [];
                $compliance_items = [
                    'uniform_status'        => 'uniform_status_',
                    'id_card_status'        => 'id_card_status_',
                    'lesson_plan_status'    => 'lesson_plan_status_',
                    'phone_handover_status' => 'phone_handover_status_',
                ];
                foreach ($compliance_items as $col_name => $post_prefix) {
                    if ($this->db->field_exists($col_name, 'staff_attendance')) {
                        $available_compliance_cols[$col_name] = $post_prefix;
                    }
                }

                foreach ($user_type_ary as $key => $value) {

                  $attendencetype = $this->input->post('attendencetype' . $value);

                  // Skip staff with no attendance status selected. Prevents inserting a NULL
                  // attendance type (rejected under strict SQL mode) and avoids overwriting an
                  // existing record with a null type. Only marked staff are saved.
                  if ($attendencetype === null || $attendencetype === '' || $attendencetype === false) {
                      continue;
                  }

                  $raw_in   = trim((string)$this->input->post("in_time_" . $value));
                  $raw_out  = trim((string)$this->input->post("out_time_" . $value));
                  $in_time  = (!empty($raw_in) && $raw_in !== '--:--' && $raw_in !== '00:00:00') ? date('H:i:s', strtotime($raw_in)) : null;
                  $out_time = (!empty($raw_out) && $raw_out !== '--:--' && $raw_out !== '00:00:00') ? date('H:i:s', strtotime($raw_out)) : null;

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

                    foreach ($available_compliance_cols as $col_name => $post_prefix) {
                        if ($this->input->post($post_prefix . $value) !== null) {
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
            $filter_date_str = date('Y-m-d', $this->customlib->datetostrtotime($date));
            $data['role_required_hours'] = $this->staffAttendaceSetting_model->getAllRoleRequiredHours($filter_date_str);
            $data['role_schedules_map']  = $this->staffAttendaceSetting_model->getAllRoleSchedulesMap($filter_date_str);
            // Holiday / working-day status of the chosen date, shown as a banner above the list.
            $this->load->model('staffdayoverride_model');
            $base_off = $this->staffattendancemodel->getOffDays($filter_date_str, $filter_date_str);
            $data['day_is_off_default'] = isset($base_off[$filter_date_str]);
            $data['day_overrides']      = $this->staffdayoverride_model->describeDate($filter_date_str);
            $data['day_date']           = $filter_date_str;

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

        $data = $this->_monthSheetData($this->input->post('role'), (int) $this->input->post('month'), (int) $this->input->post('year'));
        $this->load->view('admin/staffattendance/_monthly_sheet', $data);
    }

    /**
     * Printable monthly staff attendance register (for inspection / records), or the same as an
     * Excel file with ?format=excel. GET: role, month, year.
     */
    public function register()
    {
        if (!($this->rbac->hasPrivilege('staff_attendance', 'can_view'))) {
            access_denied();
        }
        $data = $this->_monthSheetData($this->input->get('role'), (int) $this->input->get('month'), (int) $this->input->get('year'));
        $data['school']       = $this->setting_model->getSetting();
        $data['role_label']   = (empty($data['role']) || $data['role'] === 'select') ? 'All Staff' : $data['role'];
        $data['generated_by'] = $this->customlib->getAdminSessionUserName();
        $data['format']       = $this->input->get('format') === 'excel' ? 'excel' : 'print';

        if ($data['format'] === 'excel') {
            $file = 'Staff_Attendance_Register_' . date('M_Y', mktime(0, 0, 0, $data['month'], 1, $data['year']))
                . ($data['role_label'] !== 'All Staff' ? '_' . preg_replace('/[^A-Za-z0-9]+/', '_', $data['role_label']) : '') . '.xls';
            header('Content-Type: application/vnd.ms-excel; charset=utf-8');
            header('Content-Disposition: attachment; filename="' . $file . '"');
            header('Cache-Control: max-age=0');
        }
        $this->load->view('admin/staffattendance/register', $data);
    }

    /** Data shared by the monthly sheet and the printable register. Empty / "select" role = all staff. */
    private function _monthSheetData($role, $month, $year)
    {
        if ($month < 1 || $month > 12) { $month = (int) date('m'); }
        if ($year < 2000) { $year = (int) date('Y'); }
        $data = array('role' => $role);

        $sheet    = $this->staffattendancemodel->getMonthlySheet($role, $year, $month);

        $days_in_month = (int) date('t', mktime(0, 0, 0, $month, 1, $year));

        // Off days = Sundays + calendar holidays, minus days the calendar declares working days.
        $m_start = sprintf('%04d-%02d-01', $year, $month);
        $m_end   = sprintf('%04d-%02d-%02d', $year, $month, $days_in_month);
        $holiday_dates = $this->staffattendancemodel->getOffDays($m_start, $m_end);
        // Per-staff off days: the school-wide days above, adjusted by Holidays & Working Days entries
        // (a Sunday can be a working day for teachers only, a date a holiday for one role, etc.).
        $this->load->model('staffdayoverride_model');
        $staff_roles = array();
        foreach ($sheet['staff'] as $st) { $staff_roles[$st['staff_id']] = $st['role_id']; }
        $data['staff_off_map'] = $this->staffdayoverride_model->getOffDayMap($m_start, $m_end, $staff_roles);

        $data['sheet']         = $sheet;
        $data['year']          = $year;
        $data['month']         = $month;
        $data['days_in_month'] = $days_in_month;
        $data['holiday_dates'] = $holiday_dates;
        $data['month_days']    = $days_in_month;
        $data['holidays']      = count($holiday_dates);
        $data['working_days']  = $days_in_month - count($holiday_dates);
        $data['type_list']     = $this->attendencetype_model->getStaffAttendanceType();
        $data['role_required_hours'] = $this->staffAttendaceSetting_model->getAllRoleRequiredHours();
        $data['role_schedules_map']  = $this->staffAttendaceSetting_model->getAllRoleSchedulesMap();
        // Per-day schedule from each row's snapshot shift, so a shift change never rewrites past months.
        $used_shifts = array();
        foreach ($sheet['map_shift'] as $days) {
            foreach ($days as $sid) { $used_shifts[$sid] = $sid; }
        }
        $data['shift_schedules_map'] = $this->staffAttendaceSetting_model->getSchedulesMapByShifts(array_values($used_shifts));

        // Late-coming policy: days deducted for a month's late count (daily records stay "Late").
        $policy   = $this->staffAttendaceSetting_model->getLatePolicy();
        $settings = $this->staffAttendaceSetting_model;
        $data['late_policy_on']   = !empty($policy['late_enabled']);
        $data['late_policy_text'] = $settings->latePolicyText($policy);
        $data['late_deduct']      = function ($lates) use ($settings, $policy) { return $settings->lateDeduction($lates, $policy); };

        return $data;
    }

    /**
     * Holidays & working days: turn dates into a working day or a holiday for all staff,
     * selected roles, or selected staff (e.g. a working Sunday for teachers only).
     */
    public function dayoverrides()
    {
        if (!($this->rbac->hasPrivilege('staff_attendance', 'can_view'))) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/staffattendance');
        $this->load->model('staffdayoverride_model');

        $data['overrides']  = $this->staffdayoverride_model->listRecent(date('Y-m-d', strtotime('-60 days')));
        $data['roles']      = $this->db->select('id, name')->order_by('name')->get('roles')->result_array();
        $data['staff_list'] = $this->db->select('staff.id, staff.name, staff.surname, staff.employee_id, roles.name AS role_name')
            ->join('staff_roles', 'staff_roles.staff_id = staff.id', 'left')->join('roles', 'roles.id = staff_roles.role_id', 'left')
            ->where('staff.is_active', 1)->order_by('staff.name')->get('staff')->result_array();
        $data['can_edit']   = $this->rbac->hasPrivilege('staff_attendance', 'can_edit') || $this->rbac->hasPrivilege('staff_attendance', 'can_add');

        $this->load->view('layout/header', $data);
        $this->load->view('admin/staffattendance/dayoverrides', $data);
        $this->load->view('layout/footer', $data);
    }

    /** AJAX: add a working-day / holiday override. */
    public function savedayoverride()
    {
        if (!($this->rbac->hasPrivilege('staff_attendance', 'can_edit') || $this->rbac->hasPrivilege('staff_attendance', 'can_add'))) {
            echo json_encode(array('status' => 0, 'message' => 'Access denied.'));
            return;
        }
        $this->load->model(array('staffdayoverride_model', 'staffattendanceaudit_model'));
        $to_ymd = function ($v) {
            if (empty($v)) { return null; }
            $d = date('Y-m-d', $this->customlib->datetostrtotime($v));
            return ($d && $d !== '1970-01-01') ? $d : null;
        };
        $from       = $to_ymd($this->input->post('from_date'));
        $to         = $to_ymd($this->input->post('to_date')) ?: $from;
        $day_type   = $this->input->post('day_type') === 'holiday' ? 'holiday' : 'working';
        $applies_to = in_array($this->input->post('applies_to'), array('all', 'roles', 'staff'), true) ? $this->input->post('applies_to') : 'all';
        $targets    = array_values(array_unique(array_filter(array_map('intval', (array) $this->input->post($applies_to === 'roles' ? 'role_ids' : 'staff_ids')))));
        $reason     = trim((string) $this->input->post('reason'));

        if (!$from) {
            echo json_encode(array('status' => 0, 'message' => 'Please choose a From date.'));
            return;
        }
        if ($to < $from) {
            echo json_encode(array('status' => 0, 'message' => 'To date cannot be before From date.'));
            return;
        }
        if ($applies_to !== 'all' && empty($targets)) {
            echo json_encode(array('status' => 0, 'message' => 'Select at least one ' . ($applies_to === 'roles' ? 'role' : 'staff member') . '.'));
            return;
        }
        $row = array(
            'from_date'  => $from,
            'to_date'    => $to,
            'day_type'   => $day_type,
            'applies_to' => $applies_to,
            'target_ids' => $applies_to === 'all' ? null : implode(',', $targets),
            'reason'     => $reason !== '' ? substr($reason, 0, 255) : null,
            'created_by' => $this->customlib->getStaffID(),
            'created_at' => date('Y-m-d H:i:s'),
        );
        list($id, $fixed) = $this->staffdayoverride_model->add($row);
        $this->staffattendanceaudit_model->logEvent('DAY_OVERRIDE_ADD', 'override #' . $id, null, $row + array('records_updated' => $fixed));
        echo json_encode(array('status' => 1, 'message' => 'Saved.' . ($fixed ? " {$fixed} existing attendance record(s) updated (Absent/Holiday)." : '')));
    }

    /** AJAX: remove an override. */
    public function deletedayoverride()
    {
        if (!($this->rbac->hasPrivilege('staff_attendance', 'can_edit') || $this->rbac->hasPrivilege('staff_attendance', 'can_delete'))) {
            echo json_encode(array('status' => 0, 'message' => 'Access denied.'));
            return;
        }
        $this->load->model(array('staffdayoverride_model', 'staffattendanceaudit_model'));
        list($row, $fixed) = $this->staffdayoverride_model->delete((int) $this->input->post('id'));
        if (!$row) {
            echo json_encode(array('status' => 0, 'message' => 'Not found.'));
            return;
        }
        $this->staffattendanceaudit_model->logEvent('DAY_OVERRIDE_DELETE', 'override #' . $row['id'], $row, array('records_updated' => $fixed));
        echo json_encode(array('status' => 1, 'message' => 'Removed.' . ($fixed ? " {$fixed} attendance record(s) updated back." : '')));
    }

    private function _isSuperAdmin()
    {
        $r = json_decode($this->customlib->getStaffRole());
        return $r && $r->name === 'Super Admin';
    }

    /**
     * Super Admin: status of the staff attendance database update (backup, changes, verification),
     * with Run again / Verify / Restore actions (POST action=run|verify|restore).
     */
    public function dbupdate()
    {
        if (!$this->_isSuperAdmin()) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/staffattendance');
        $this->load->model('staffattendancedbupdate_model');
        $m = $this->staffattendancedbupdate_model;

        $result = null;
        $action = $this->input->post('action');
        $record = $m->getRecord();
        if ($action === 'run') {
            $result = $m->run($this->customlib->getStaffID());
            $record = $m->getRecord();
        } elseif ($action === 'restore' && !empty($record['backup_prefix'])) {
            $restored = $m->restoreFromBackup($record['backup_prefix']);
            $lines = array('Restored original values from ' . $record['backup_prefix'] . '*:');
            foreach ($restored as $t => $n) { $lines[] = "  - $t: $n row(s) put back"; }
            $result = array('status' => 'restored', 'log' => $lines);
        }

        $data['record']     = $record;
        $data['result']     = $result;
        $data['code']       = Staffattendancedbupdate_model::CODE;
        $data['title_text'] = Staffattendancedbupdate_model::TITLE;
        $data['compare']    = !empty($record['backup_prefix']) ? $m->compareWithBackup($record['backup_prefix']) : array();
        $data['backups']    = $m->listBackups();
        $this->load->model('staffattendanceaudit_model');
        $data['triggers_ok'] = $this->db->table_exists('staff_attendance_audit_log') && $this->staffattendanceaudit_model->triggersInstalled();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/staffattendance/dbupdate', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * Change log of every insert / update / delete on staff attendance plus shift settings changes.
     */
    public function auditlog()
    {
        if (!($this->rbac->hasPrivilege('staff_attendance', 'can_view'))) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/staffattendance');
        $this->load->model('staffattendanceaudit_model');

        $to_ymd = function ($v) {
            if (empty($v)) { return null; }
            $ts = $this->customlib->datetostrtotime($v);
            return $ts ? date('Y-m-d', $ts) : null;
        };
        $filters = array(
            'from'     => $to_ymd($this->input->get('from')) ?: date('Y-m-d', strtotime('-7 days')),
            'to'       => $to_ymd($this->input->get('to')) ?: date('Y-m-d'),
            'att_date' => $to_ymd($this->input->get('att_date')),
            'staff_id' => (int) $this->input->get('staff_id'),
            'action'   => $this->input->get('action'),
            'source'   => $this->input->get('source'),
        );
        $page     = max(1, (int) $this->input->get('page'));
        $per_page = 100;
        $res      = $this->staffattendanceaudit_model->search($filters, $per_page, ($page - 1) * $per_page);

        $type_names = array();
        foreach ($this->attendencetype_model->getStaffAttendanceType() as $t) {
            $t = (array) $t;
            $type_names[$t['id']] = !empty($t['type']) ? $t['type'] : $t['key_value'];
        }
        $shift_names = array();
        foreach ($this->staffAttendaceSetting_model->getShifts() as $s) {
            $shift_names[$s['id']] = $s['name'];
        }

        $data['filters']       = $filters;
        $data['rows']          = $res['rows'];
        $data['total']         = $res['total'];
        $data['page']          = $page;
        $data['per_page']      = $per_page;
        $data['type_names']    = $type_names;
        $data['shift_names']   = $shift_names;
        $data['sources']       = $this->staffattendanceaudit_model->getSources();
        $data['staff_list']    = $this->db->select('id, name, surname, employee_id')->where('is_active', 1)->order_by('name')->get('staff')->result_array();
        $data['triggers_ok']   = $this->staffattendanceaudit_model->triggersInstalled();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/staffattendance/auditlog', $data);
        $this->load->view('layout/footer', $data);
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
        $period = $this->input->get('period');
        if (!in_array($period, ['7days', 'this_month', 'prev_month'])) {
            $period = '7days';
        }
        $data['title']             = 'Mark My Attendance';
        $data['setting']           = $setting;
        $data['period']            = $period;
        $data['recent_attendance'] = $this->staffattendancemodel->getStaffRecentAttendance($admin['id'], $period === '7days' ? 7 : $period);
        $this->load->model('staffattendancecorrection_model');
        $data['my_corrections']    = $this->staffattendancecorrection_model->myRequests($admin['id']);
        $data['correction_types']  = Staffattendancecorrection_model::$types;
        $data['correction_min']    = Staffattendancecorrection_model::minDate();
        $this->load->view('layout/header', $data);
        $this->load->view('admin/staffattendance/scan', $data);
        $this->load->view('layout/footer', $data);
    }

    /** Staff self-service AJAX: request a correction for one of their own days. */
    public function requestcorrection()
    {
        $admin = $this->session->userdata('admin');
        if (empty($admin['id'])) {
            echo json_encode(array('status' => 0, 'message' => 'Your session has expired. Please log in again.'));
            return;
        }
        $this->load->model('staffattendancecorrection_model');
        $raw  = $this->input->post('date');
        $date = $raw ? date('Y-m-d', strtotime($raw) ?: $this->customlib->datetostrtotime($raw)) : null;
        list($ok, $msg) = $this->staffattendancecorrection_model->create(
            $admin['id'], $date, $this->input->post('request_type'),
            $this->input->post('in_time'), $this->input->post('out_time'), $this->input->post('reason')
        );
        echo json_encode(array('status' => $ok ? 1 : 0, 'message' => $msg));
    }

    /** Staff self-service AJAX: withdraw own pending correction request. */
    public function cancelcorrection()
    {
        $admin = $this->session->userdata('admin');
        if (empty($admin['id'])) {
            echo json_encode(array('status' => 0, 'message' => 'Your session has expired. Please log in again.'));
            return;
        }
        $this->load->model('staffattendancecorrection_model');
        $ok = $this->staffattendancecorrection_model->cancel((int) $this->input->post('id'), $admin['id']);
        echo json_encode(array('status' => $ok ? 1 : 0, 'message' => $ok ? 'Request withdrawn.' : 'Only pending requests can be withdrawn.'));
    }

    /** Admin: review attendance correction requests. */
    public function corrections()
    {
        if (!($this->rbac->hasPrivilege('staff_attendance', 'can_edit') || $this->rbac->hasPrivilege('staff_attendance', 'can_add'))) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/staffattendance');
        $this->load->model('staffattendancecorrection_model');
        $status = $this->input->get('status');
        if (!in_array($status, array('pending', 'approved', 'rejected', 'cancelled', 'all'), true)) {
            $status = 'pending';
        }
        $data['status']        = $status;
        $data['requests']      = $this->staffattendancecorrection_model->listForReview($status);
        $data['pending_count'] = $this->staffattendancecorrection_model->pendingCount();
        $data['types']         = Staffattendancecorrection_model::$types;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/staffattendance/corrections', $data);
        $this->load->view('layout/footer', $data);
    }

    /** Admin AJAX: approve (optionally adjusting times) or reject a correction request. */
    public function reviewcorrection()
    {
        if (!($this->rbac->hasPrivilege('staff_attendance', 'can_edit') || $this->rbac->hasPrivilege('staff_attendance', 'can_add'))) {
            echo json_encode(array('status' => 0, 'message' => 'Access denied.'));
            return;
        }
        $this->load->model('staffattendancecorrection_model');
        $id       = (int) $this->input->post('id');
        $reviewer = $this->customlib->getStaffID();
        $req      = $this->db->where('id', $id)->get('staff_attendance_corrections')->row_array();
        $my_role = json_decode($this->customlib->getStaffRole());
        if ($req && (int) $req['staff_id'] === (int) $reviewer && !($my_role && $my_role->name === 'Super Admin')) {
            echo json_encode(array('status' => 0, 'message' => 'You cannot review your own request.'));
            return;
        }
        if ($this->input->post('decision') === 'approve') {
            list($ok, $msg) = $this->staffattendancecorrection_model->approve($id, $reviewer, $this->input->post('in_time'), $this->input->post('out_time'), $this->input->post('remark'));
        } else {
            list($ok, $msg) = $this->staffattendancecorrection_model->reject($id, $reviewer, $this->input->post('remark'));
        }
        echo json_encode(array('status' => $ok ? 1 : 0, 'message' => $msg));
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

        // Ensure school timezone is always active
        if (isset($this->customlib) && method_exists($this->customlib, 'getTimeZone')) {
            $tz = $this->customlib->getTimeZone();
            if (!empty($tz)) {
                date_default_timezone_set($tz);
            } else {
                date_default_timezone_set('Asia/Kolkata');
            }
        } else {
            date_default_timezone_set('Asia/Kolkata');
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

        // Actions driven by user interaction
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
        if ($action === 'direct_out') {
            json_output(200, $this->staffattendancemodel->qrDirectOut($admin['id'], $role->id, $opts));
            return;
        }
        if ($action === 'mark_in') {
            json_output(200, $this->staffattendancemodel->qrMark($admin['id'], $role->id, $opts));
            return;
        }

        // Evaluate QR state
        $state = $this->staffattendancemodel->getQrState($admin['id']);
        if ($state['state'] === 'not_in') {
            // Check current time. If it is afternoon / past 11:30 AM, offer choice (Check In vs Direct Check Out)
            $now_time = date('H:i:s');
            if ($now_time >= '11:30:00') {
                json_output(200, array(
                    'status'          => 'choose',
                    'actions'         => array('mark_in', 'direct_out'),
                    'message'         => 'You do not have a morning check-in for today. What would you like to record at ' . date('h:i A', strtotime($now_time)) . '?',
                    'in_time'         => '--',
                    'out_time'        => 'Not Checked Out Yet',
                    'attendance_type' => 'Pending',
                    'date'            => date('d M Y')
                ));
                return;
            } else {
                json_output(200, $this->staffattendancemodel->qrMark($admin['id'], $role->id, $opts));
                return;
            }
        }
        if ($state['state'] === 'on_break') {
            json_output(200, array(
                'status'          => 'choose',
                'actions'         => array('break_in'),
                'message'         => 'You stepped out at ' . $state['since'] . '. Scan confirmed — step back in?',
                'in_time'         => isset($state['in']) ? $state['in'] : 'N/A',
                'out_time'        => isset($state['out']) ? $state['out'] : 'Stepped Out',
                'attendance_type' => isset($state['attendance_type']) ? $state['attendance_type'] : 'Present',
                'date'            => isset($state['date']) ? $state['date'] : date('d M Y')
            ));
            return;
        }
        if ($state['state'] === 'complete') {
            json_output(200, array(
                'status'          => 'already_complete',
                'message'         => 'Your attendance for today is already complete.',
                'in_time'         => $state['in'],
                'out_time'        => $state['out'],
                'attendance_type' => isset($state['attendance_type']) ? $state['attendance_type'] : 'Present',
                'date'            => isset($state['date']) ? $state['date'] : date('d M Y')
            ));
            return;
        }
        // state === 'in'
        json_output(200, array(
            'status'          => 'choose',
            'actions'         => array('break_out', 'final_out'),
            'message'         => 'You are checked in at ' . $state['in'] . ' (Marked as ' . (isset($state['attendance_type']) ? $state['attendance_type'] : 'Present') . '). What would you like to do?',
            'in_time'         => $state['in'],
            'out_time'        => isset($state['out']) ? $state['out'] : 'Not Checked Out Yet',
            'attendance_type' => isset($state['attendance_type']) ? $state['attendance_type'] : 'Present',
            'date'            => isset($state['date']) ? $state['date'] : date('d M Y')
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

    // =================================================================
    //  BIOMETRIC (E-TIMEOFFICE) INTEGRATION
    // =================================================================

    /**
     * Biometric Settings & Staff Mapping Diagnostic Dashboard.
     */
    public function biometricsettings()
    {
        if (!($this->rbac->hasPrivilege('staff_attendance', 'can_view'))) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/staffattendance/biometricsettings');

        if ($this->input->post('save_biometric_setting')) {
            if (!($this->rbac->hasPrivilege('staff_attendance', 'can_edit'))) {
                access_denied();
            }

            $save = array(
                'is_enabled'        => $this->input->post('is_enabled') ? 1 : 0,
                'auto_sync_enabled' => $this->input->post('auto_sync_enabled') ? 1 : 0,
                'api_url'           => trim($this->input->post('api_url')),
                'corporate_id'      => trim($this->input->post('corporate_id')),
                'username'          => trim($this->input->post('username')),
            );
            $new_pass = trim($this->input->post('password'));
            if ($new_pass !== '') {
                $save['password'] = $new_pass;
            }

            $this->StaffBiometricSetting_model->save($save);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/staffattendance/biometricsettings');
        }

        $data['title']       = 'Staff Biometric Settings & Mapping';
        $data['setting']     = $this->StaffBiometricSetting_model->get();
        $data['staff_list']  = $this->StaffBiometricSetting_model->getStaffBiometricMappingList();
        $data['logs']        = $this->StaffBiometricSetting_model->getLogs(50);

        $this->load->view('layout/header', $data);
        $this->load->view('admin/staffattendance/biometricsettings', $data);
        $this->load->view('layout/footer', $data);
    }

    /**
     * AJAX: Test connection to e-TimeOffice.
     */
    public function test_biometric_connection_ajax()
    {
        if (!($this->rbac->hasPrivilege('staff_attendance', 'can_view'))) {
            json_output(403, array('status' => 'error', 'message' => 'Access Denied'));
            return;
        }
        $this->load->helper('json_output');

        $corporate_id = trim($this->input->post('corporate_id'));
        $username     = trim($this->input->post('username'));
        $password     = trim($this->input->post('password'));
        $api_url      = trim($this->input->post('api_url'));

        $setting = $this->StaffBiometricSetting_model->get();
        if ($corporate_id !== '') { $setting['corporate_id'] = $corporate_id; }
        if ($username !== '') { $setting['username'] = $username; }
        if ($password !== '') { $setting['password'] = $password; }
        if ($api_url !== '') { $setting['api_url'] = $api_url; }

        $res = $this->biometric_lib->testConnection($setting);
        if ($res['success']) {
            json_output(200, array('status' => 'success', 'message' => $res['message']));
        } else {
            json_output(200, array('status' => 'error', 'message' => $res['message']));
        }
    }

    /**
     * AJAX: Sync Biometric Attendance for a selected date or custom range.
     */
    public function sync_biometric_ajax()
    {
        if (!($this->rbac->hasPrivilege('staff_attendance', 'can_add') || $this->rbac->hasPrivilege('staff_attendance', 'can_edit'))) {
            json_output(403, array('status' => 'error', 'message' => 'Access Denied'));
            return;
        }
        $this->load->helper('json_output');

        $raw_date = $this->input->post('date');
        $raw_from = $this->input->post('from_date');
        $raw_to   = $this->input->post('to_date');

        if (!empty($raw_from) && !empty($raw_to)) {
            $fromDate = date('Y-m-d', $this->customlib->datetostrtotime($raw_from));
            $toDate   = date('Y-m-d', $this->customlib->datetostrtotime($raw_to));
            $mode     = 'range';
        } elseif (!empty($raw_date)) {
            $fromDate = date('Y-m-d', $this->customlib->datetostrtotime($raw_date));
            $toDate   = $fromDate;
            $mode     = 'ajax';
        } else {
            $fromDate = date('Y-m-d');
            $toDate   = $fromDate;
            $mode     = 'ajax';
        }

        $res = $this->biometric_lib->syncAttendance($fromDate, $toDate, $mode);
        json_output(200, $res);
    }

    /**
     * AJAX: Inline update of custom biometric machine ID for a staff member.
     */
    public function save_staff_biometric_id_ajax()
    {
        if (!($this->rbac->hasPrivilege('staff_attendance', 'can_edit'))) {
            json_output(403, array('status' => 'error', 'message' => 'Access Denied'));
            return;
        }
        $this->load->helper('json_output');

        $staff_id         = (int) $this->input->post('staff_id');
        $biometric_emp_id = trim($this->input->post('biometric_emp_id'));

        if (empty($staff_id)) {
            json_output(400, array('status' => 'error', 'message' => 'Invalid staff ID'));
            return;
        }

        $this->StaffBiometricSetting_model->updateStaffBiometricId($staff_id, $biometric_emp_id);
        json_output(200, array('status' => 'success', 'message' => 'Biometric ID updated successfully'));
    }

    /**
     * CRON: Automated sync endpoint (e.g. called periodically via Windows Task Scheduler or cURL).
     * URL: http://localhost/lms/admin/staffattendance/sync_biometric_cron?token=7b91d2e854f9a3c10b78e2d45c61a
     */
    public function sync_biometric_cron()
    {
        $token = $this->input->get_post('token');
        $setting = $this->StaffBiometricSetting_model->get();

        if (empty($setting['cron_token']) || $token !== $setting['cron_token']) {
            show_error('Invalid or missing security token.', 403);
            return;
        }

        $today = date('Y-m-d');
        $res = $this->biometric_lib->syncAttendance($today, $today, 'cron');

        header('Content-Type: application/json');
        echo json_encode($res);
    }

    /**
     * Download Excel/CSV formatted export of active staff for e-TimeOffice / Biometric software bulk import.
     * Format matches exactly:
     * Empcode, Enroll No, Name, Father, Address, CountryCode, Mobile, Email, CompID, DeptID, DesgID, CatID, ActiveStatus(Active/De Active), Shift, Auto Shift(A/B/C), WO1, WO2, Sat Off, Sat Half Day, Status(T/F), DOJ, ENT(1/2), CountryCode1, Mobile1, DOB, DOR
     */
    public function export_biometric_template()
    {
        if (!($this->rbac->hasPrivilege('staff_attendance', 'can_view'))) {
            access_denied();
        }

        $staffList = $this->StaffBiometricSetting_model->getStaffExportListForBiometric();

        $filename = 'etimeoffice_staff_import_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');

        // Header matching exact e-TimeOffice bulk upload specification
        fputcsv($output, array(
            'Empcode',
            'Enroll No',
            'Name',
            'Father',
            'Address',
            'CountryCode',
            'Mobile',
            'Email',
            'CompID',
            'DeptID',
            'DesgID',
            'CatID',
            'ActiveStatus(Active/De Active)',
            'Shift',
            'Auto Shift(A/B/C)',
            'WO1',
            'WO2',
            'Sat Off',
            'Sat Half Day',
            'Status(T/F)',
            'DOJ',
            'ENT(1/2)',
            'CountryCode1',
            'Mobile1',
            'DOB',
            'DOR'
        ));

        foreach ($staffList as $st) {
            $emp_code = !empty($st['biometric_emp_id']) ? trim($st['biometric_emp_id']) : trim((string)$st['employee_id']);
            if (empty($emp_code)) {
                $emp_code = sprintf('%04d', $st['id']);
            }

            // Enroll No: padded to 8 digits if numeric or matched
            $enroll_no = is_numeric($emp_code) ? sprintf('%08d', (int)$emp_code) : $emp_code;

            $full_name = trim($st['name'] . ' ' . $st['surname']);
            $father    = trim((string)$st['father_name']);
            $address   = trim((string)(!empty($st['local_address']) ? $st['local_address'] : $st['permanent_address']));
            $mobile    = trim((string)$st['contact_no']);
            $email     = trim((string)$st['email']);
            $dept_id   = !empty($st['dept_id']) ? (int)$st['dept_id'] : 1;
            $desg_id   = !empty($st['desg_id']) ? (int)$st['desg_id'] : 1;
            $cat_id    = !empty($st['role_id']) ? (int)$st['role_id'] : 1;

            $doj = !empty($st['date_of_joining']) && $st['date_of_joining'] !== '0000-00-00' ? date('d/m/Y', strtotime($st['date_of_joining'])) : '';
            $dob = !empty($st['dob']) && $st['dob'] !== '0000-00-00' ? date('d/m/Y', strtotime($st['dob'])) : '';
            $dor = !empty($st['date_of_leaving']) && $st['date_of_leaving'] !== '0000-00-00' ? date('d/m/Y', strtotime($st['date_of_leaving'])) : '';

            $row = array(
                $emp_code,                       // Empcode
                $enroll_no,                      // Enroll No
                $full_name,                      // Name
                $father,                         // Father
                $address,                        // Address
                '',                              // CountryCode
                $mobile,                         // Mobile
                $email,                          // Email
                '1',                             // CompID
                $dept_id,                        // DeptID
                $desg_id,                        // DesgID
                $cat_id,                         // CatID
                'Active',                        // ActiveStatus(Active/De Active)
                '',                              // Shift
                'G',                             // Auto Shift(A/B/C)
                '',                              // WO1
                '',                              // WO2
                '',                              // Sat Off
                '',                              // Sat Half Day
                'T',                             // Status(T/F)
                $doj,                            // DOJ
                '1',                             // ENT(1/2)
                '',                              // CountryCode1
                '',                              // Mobile1
                $dob,                            // DOB
                $dor                             // DOR
            );

            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }

}

