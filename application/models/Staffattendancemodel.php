<?php
class Staffattendancemodel extends MY_Model {

    protected $current_session;
    protected $current_date;
    public function __construct() {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->current_date = $this->setting_model->getDateYmd();
    }
    
    public function addorUpdate($attendances)
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);
      
        if(!empty($attendances)){
            foreach ($attendances as $attendance_key => $attendance_value) {
                            
                $this->db->where('staff_id',  $attendance_value['staff_id']);
                $this->db->where('date', $attendance_value['date']);
                $query = $this->db->get('staff_attendance');
                
                if ($query->num_rows() > 0) {
                    // Record exists, update it
                    $this->db->where('id', $query->row()->id);
                    $this->db->update('staff_attendance', $attendance_value);
                } else {
                    // Record does not exist, insert a new one.
                    // Populate NOT NULL columns that have no usable default so the insert
                    // succeeds under strict SQL mode. Without this, back-dated / first-time
                    // attendance (e.g. previous months with no existing row) silently failed
                    // and rolled back, while current-month saves worked only because their
                    // rows already existed (UPDATE path).
                    if (!isset($attendance_value['is_active'])) {
                        $attendance_value['is_active'] = 1;
                    }
                    if (empty($attendance_value['created_at'])) {
                        $attendance_value['created_at'] = date('Y-m-d H:i:s');
                    }
                    if (!isset($attendance_value['remark']) || $attendance_value['remark'] === null) {
                        $attendance_value['remark'] = '';
                    }
                    $this->db->insert('staff_attendance', $attendance_value);
                }

                }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }    
    }

    public function get($id = null) {
        $this->db->select()->join("staff", "staff.id = staff_attendance.staff_id")->from('staff_attendance');
        $this->db->where("staff.is_active", 1);
        if ($id != null) {
            $this->db->where('staff_attendance.id', $id);
        } else {
            $this->db->order_by('staff_attendance.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getUserType() {
        $query = $this->db->query("select distinct user_type from staff where is_active = 1");
        return $query->result_array();
    }

    public function searchAttendenceUserTypeWithMode($user_type, $date,$mode) {
        $condition = '';

        if ($mode == 1) {
            $condition = " and staff_attendance.biometric_attendence= 0 and staff_attendance.qrcode_attendance=0";
        } elseif ($mode == 2) {
            $condition = " and staff_attendance.biometric_attendence= 0 and staff_attendance.qrcode_attendance=1";
        } elseif ($mode == 3) {
            $condition = " and staff_attendance.biometric_attendence= 1 and staff_attendance.qrcode_attendance=0";
        }

        if ($this->session->has_userdata('admin')) {
            $getStaffRole     = $this->customlib->getStaffRole();
            $staffrole   =   json_decode($getStaffRole);       
            $superadmin_visible = $this->customlib->superadmin_visible(); 
            if ($superadmin_visible == 'disabled' && $staffrole->id != 7) {                 
                $condition = " and roles.id != 7";
            } 
        }
        
        if ($user_type == "select") { 
            $query = $this->db->query("select staff_attendance.id,staff_attendance.created_at as attendence_dt, staff_attendance.staff_attendance_type_id,staff_attendance.biometric_attendence,staff_attendance.qrcode_attendance,staff_attendance.user_agent,staff_attendance.biometric_device_data,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date,staff.id as staff_id, staff_attendance_type.type as `att_type`,staff_attendance_type.key_value as `key`,staff_attendance_type.long_lang_name,staff_attendance_type.long_name_style  from staff left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " left join staff_attendance_type on staff_attendance_type.id = staff_attendance.staff_attendance_type_id where staff.is_active = 1 $condition order by staff_attendance.created_at asc");
        } else {
            $query = $this->db->query("select staff_attendance.staff_attendance_type_id,staff_attendance.created_at as attendence_dt,staff_attendance.biometric_attendence,staff_attendance.qrcode_attendance,staff_attendance.user_agent,staff_attendance.biometric_device_data,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date, IFNULL(staff_attendance.id, 0) as id, staff.id as staff_id ,staff_attendance_type.type as `att_type`,staff_attendance_type.key_value as `key`,staff_attendance_type.long_lang_name,staff_attendance_type.long_name_style from staff left join staff_roles on (staff.id = staff_roles.staff_id) left join roles on (roles.id = staff_roles.role_id) left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " left join staff_attendance_type on staff_attendance_type.id = staff_attendance.staff_attendance_type_id where roles.name = " . $this->db->escape($user_type) . " and staff.is_active = 1 $condition order by staff_attendance.created_at asc");
            
        }
        return $query->result_array();
    }

    public function searchAttendenceUserType($user_type, $date) {
        $condition = '';
        // Deploy-safe: dynamically select compliance columns if present
        $compliance_cols = '';
        foreach (['uniform_status', 'id_card_status', 'lesson_plan_status', 'phone_handover_status'] as $ccol) {
            if ($this->db->field_exists($ccol, 'staff_attendance')) {
                $compliance_cols .= ", staff_attendance.$ccol";
            } else {
                $compliance_cols .= ", NULL as $ccol";
            }
        }

        if ($this->session->has_userdata('admin')) {
            $getStaffRole     = $this->customlib->getStaffRole();
            $staffrole   =   json_decode($getStaffRole);       
            $superadmin_visible = $this->customlib->superadmin_visible(); 
            if ($superadmin_visible == 'disabled' && $staffrole->id != 7) {                 
                $condition = " and roles.id != 7";
            } 
        }
        
        if ($user_type == "select") { 

            $query = $this->db->query("select staff_attendance.out_time,staff_attendance.in_time,staff_attendance.id,staff_attendance.created_at as attendence_dt, staff_attendance.staff_attendance_type_id,staff_attendance.biometric_attendence,staff_attendance.qrcode_attendance,staff_attendance.user_agent,staff_attendance.biometric_device_data,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.contact_no,staff.email,roles.name as user_type,roles.id as role_id,IFNULL(staff_attendance.date, 'xxx') as date,staff.id as staff_id, staff_attendance_type.type as `att_type`,staff_attendance_type.key_value as `key`,staff_attendance_type.long_lang_name,staff_attendance_type.long_name_style $compliance_cols  from staff left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " left join staff_attendance_type on staff_attendance_type.id = staff_attendance.staff_attendance_type_id where staff.is_active = 1 $condition");
        } else {
            $query = $this->db->query("select  staff_attendance.out_time,staff_attendance.in_time,staff_attendance.staff_attendance_type_id,staff_attendance.created_at as attendence_dt,staff_attendance.biometric_attendence,staff_attendance.qrcode_attendance,staff_attendance.user_agent,staff_attendance.biometric_device_data,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.contact_no,staff.email,roles.name as user_type,roles.id as role_id,IFNULL(staff_attendance.date, 'xxx') as date, IFNULL(staff_attendance.id, 0) as id, staff.id as staff_id ,staff_attendance_type.type as `att_type`,staff_attendance_type.key_value as `key`,staff_attendance_type.long_lang_name,staff_attendance_type.long_name_style $compliance_cols from staff left join staff_roles on (staff.id = staff_roles.staff_id) left join roles on (roles.id = staff_roles.role_id) left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " left join staff_attendance_type on staff_attendance_type.id = staff_attendance.staff_attendance_type_id where roles.name = " . $this->db->escape($user_type) . " and staff.is_active = 1 $condition");            
        }
        return $query->result_array();
    }

    public function add($data) {
        $this->db->trans_start(); # Starting Transaction
        $this->db->trans_strict(false); # See Note 01. If you wish can remove as well
        //=======================Code Start===========================
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('staff_attendance', $data);
            $message = UPDATE_RECORD_CONSTANT . " On staff attendance id " . $data['id'];
            $action = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('staff_attendance', $data);
            $id = $this->db->insert_id();
            $message = INSERT_RECORD_CONSTANT . " On staff attendance id " . $id;
            $action = "Insert";
            $record_id = $id;
            $this->log($message, $record_id, $action);
        }
        //======================Code End==============================
        $this->db->trans_complete(); # Completing transaction
        /* Optional */
        if ($this->db->trans_status() === false) {
            # Something went wrong.
            $this->db->trans_rollback();
            return false;
        } else {
            //return $return_value;
        }
    }

    public function getStaffAttendanceType() {
        $query = $this->db->select('*')->where("is_active", 'yes')->get("staff_attendance_type");
        return $query->result_array();
    }

    public function searchAttendanceReport($user_type, $date) {

        if ($this->session->has_userdata('admin')) {
            $getStaffRole     = $this->customlib->getStaffRole();
            $staffrole   =   json_decode($getStaffRole);       
             
            $superadmin_visible = $this->customlib->superadmin_visible(); 
            $condition = '';
            if ($superadmin_visible == 'disabled' && $staffrole->id != 7) {
                $condition = "and staff_roles.role_id != 7";       
            } 
        }
        
        if ($user_type == "select") {
            $query = $this->db->query("select staff_attendance.staff_attendance_type_id,staff_attendance_type.type as `att_type`,staff_attendance_type.key_value as `key`,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date, IFNULL(staff_attendance.id, 0) as attendence_id, staff.id as id from staff left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " left join staff_attendance_type on staff_attendance_type.id = staff_attendance.staff_attendance_type_id left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id where staff.is_active = 1 $condition");
        } else {
            $query = $this->db->query("select staff_attendance.staff_attendance_type_id,staff_attendance_type.type as `att_type`,staff_attendance_type.key_value as `key`,staff_attendance.remark,staff.name,staff.surname,staff.employee_id,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date, IFNULL(staff_attendance.id, 0) as attendence_id, staff.id as id from staff  left join staff_roles on (staff.id = staff_roles.staff_id) left join roles on (roles.id = staff_roles.role_id) left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " left join staff_attendance_type on staff_attendance_type.id = staff_attendance.staff_attendance_type_id  where roles.name = '" . $user_type . "' and staff.is_active = 1 $condition");
        }

        return $query->result_array();
    }

    public function attendanceYearCount() {
        $query = $this->db->select("distinct year(date) as year")->get("staff_attendance");
        return $query->result_array();
    }

    public function searchStaffattendance($date, $staff_id, $active_staff = true) {

        $sql = "select staff_attendance.staff_attendance_type_id,staff_attendance_type.type as `att_type`,staff_attendance_type.key_value as `key`,staff_attendance.remark,staff.name,staff.surname,staff.contact_no,staff.email,roles.name as user_type,IFNULL(staff_attendance.date, 'xxx') as date, IFNULL(staff_attendance.id, 0) as attendence_id, staff.id as id from staff left join staff_attendance on (staff.id = staff_attendance.staff_id) and staff_attendance.date = " . $this->db->escape($date) . " left join staff_roles on staff_roles.staff_id = staff.id left join roles on staff_roles.role_id = roles.id left join staff_attendance_type on staff_attendance_type.id = staff_attendance.staff_attendance_type_id where staff.id = " . $this->db->escape($staff_id);
        if ($active_staff || !isset($active_staff)) {
            $sql .= " and staff.is_active = 1";
        }
        $query = $this->db->query($sql);
        return $query->row_array();
    }

    public function onlineattendence($data, $role_id)
    {
        $status = false;
        $this->db->where('staff_id', $data['staff_id']);
        $this->db->where('date', $data['date']);
        $q = $this->db->get('staff_attendance');
        $time = date('H:i:s');
        if ($q->num_rows() == 0) {
            $attendance_range = $this->staffAttendaceSetting_model->getAttendanceTypeByRole($role_id, $time);
            if ($attendance_range) {
                $data['staff_attendance_type_id'] = $attendance_range->staff_attendence_type_id;
                $data['in_time'] = $time;

                $this->db->insert('staff_attendance', $data);
                $status = 1; //for successfully saving

            } else {
                $status = 2; //for range not exist to save
            }
        } else {

            $return_result = $q->row();
            if (!IsNullOrEmptyString($return_result->in_time)  && !IsNullOrEmptyString($return_result->out_time)) {

                $status = 0;
            } else {
                $updateArr = ['out_time' => $time];
                $return_attendance_type =  $this->staff_schedule_hours($return_result->staff_id, $return_result->in_time);
                if ($return_attendance_type) {
                    $updateArr['staff_attendance_type_id'] = $return_attendance_type;
                }


                $this->db->where('id', $return_result->id);
                $this->db->update('staff_attendance', $updateArr);
                $status = 1;
            }
        }
        return $status;
    }

    public function staff_schedule_hours($staff_id, $in_time)
    {
        $date = date('Y-m-d');
        $sql    = "SELECT staff_roles.role_id,staff_attendence_schedules.staff_attendence_type_id as staff_attendence_schedule_staff_attendence_type_id,staff_attendence_schedules.entry_time_from,staff_attendence_schedules.entry_time_to,staff_attendence_schedules.total_institute_hour FROM `staff` INNER JOIN staff_roles on staff_roles.staff_id=staff.id INNER join staff_attendence_schedules on staff_attendence_schedules.role_id = staff_roles.role_id WHERE staff.id=" . $this->db->escape($staff_id);     

        $current_time = date('H:i:s');
        $query  = $this->db->query($sql);
        if ($query->num_rows() > 0) {

            $return_attedance_type = false;
            $time_entry_seconds = strtotime("1970-01-01 $in_time UTC");
            $time_current_seconds = strtotime("1970-01-01 $current_time UTC");
            $total_spend_time = $time_current_seconds - $time_entry_seconds;

            $result = $query->result();
            $find_array = array();           

            foreach ($result as $result_key => $result_value) {

                $entry_time_from_seconds = strtotime("1970-01-01 $result_value->entry_time_from UTC");
                $entry_time_to_seconds = strtotime("1970-01-01 $result_value->entry_time_to UTC");

                if ($entry_time_from_seconds  <= $time_entry_seconds && $entry_time_to_seconds >= $time_entry_seconds) {

                    $find_array[] = array(
                        'staff_attendence_type_id' => $result_value->staff_attendence_schedule_staff_attendence_type_id,
                        'time_schedule_seconds' => strtotime("1970-01-01 $result_value->total_institute_hour UTC")
                    );
                }
            }
            if (count($find_array) > 1) {

                if ($total_spend_time < $find_array[0]['time_schedule_seconds'] && $total_spend_time > $find_array[1]['time_schedule_seconds']) {
                    $return_attedance_type = $find_array[1]['staff_attendence_type_id'];
                }
            }

            return $return_attedance_type;
        } else {
            return false;
        }
    }

    /**
     * Compute the schedule-based earliest allowed out-time for a staff on a
     * given day: the entry time of the band the in_time fell into, plus that
     * band's total_institute_hour. Returns "H:i:s" or null if undeterminable.
     */
    public function scheduleEarliestOut($role_id, $in_time)
    {
        if (IsNullOrEmptyString($in_time)) {
            return null;
        }
        $this->db->where('role_id', $role_id);
        $rows = $this->db->get('staff_attendence_schedules')->result();
        if (empty($rows)) {
            return null;
        }
        $in_sec = strtotime("1970-01-01 $in_time UTC");
        foreach ($rows as $row) {
            $from = strtotime("1970-01-01 {$row->entry_time_from} UTC");
            $to   = strtotime("1970-01-01 {$row->entry_time_to} UTC");
            if ($in_sec >= $from && $in_sec <= $to) {
                $hours = $row->total_institute_hour;
                if (IsNullOrEmptyString($hours)) {
                    return null;
                }
                $dur = strtotime("1970-01-01 $hours UTC") - strtotime("1970-01-01 00:00:00 UTC");
                return date('H:i:s', strtotime($in_time) + $dur);
            }
        }
        return null;
    }

    /**
     * QR-driven attendance marking with cooldown + early-exit guards.
     *
     * $opts keys: cooldown_minutes, earliest_out_source ('schedule'|'manual'),
     *             manual_earliest_out_time, confirm_early (bool), reason,
     *             source (string, e.g. 'qr').
     *
     * Returns array('status' => code, 'message' => text, 'time' => H:i).
     * Codes: no_schedule, marked_in, cooldown, confirm_early, marked_out,
     *        already_complete, error.
     */
    public function qrMark($staff_id, $role_id, $opts = array())
    {
        $date   = date('Y-m-d');
        $now    = date('H:i:s');
        $source = isset($opts['source']) ? $opts['source'] : 'qr';
        $has_source_col = $this->db->field_exists('attendance_source', 'staff_attendance');

        $this->db->where('staff_id', $staff_id)->where('date', $date);
        $row = $this->db->get('staff_attendance')->row();

        // ---- IN scan: no row yet, or row exists without an in_time ----
        if (empty($row) || IsNullOrEmptyString($row->in_time)) {
            $range = $this->staffAttendaceSetting_model->getAttendanceTypeByRole($role_id, $now);
            if (!$range) {
                return array('status' => 'no_schedule', 'message' => 'No attendance schedule is configured for your role at this time. Please contact admin.');
            }
            $data = array(
                'staff_id'                 => $staff_id,
                'date'                     => $date,
                'in_time'                  => $now,
                'staff_attendance_type_id' => $range->staff_attendence_type_id,
                'remark'                   => '',
                'is_active'                => 1,
            );
            if ($has_source_col) {
                $data['attendance_source'] = $source;
            }
            // Flag as QR so the attendance list shows the "QR / Barcode" source
            // (the list view keys off qrcode_attendance, default 0 = Manual).
            if ($this->db->field_exists('qrcode_attendance', 'staff_attendance')) {
                $data['qrcode_attendance'] = 1;
            }
            if (empty($row)) {
                $data['created_at'] = date('Y-m-d H:i:s');
                $this->db->insert('staff_attendance', $data);
            } else {
                unset($data['created_at']);
                $data['updated_at'] = date('Y-m-d H:i:s');
                $this->db->where('id', $row->id)->update('staff_attendance', $data);
            }
            return array('status' => 'marked_in', 'message' => 'Attendance marked. Welcome!', 'time' => date('h:i A', strtotime($now)));
        }

        // ---- Row already complete ----
        if (!IsNullOrEmptyString($row->out_time)) {
            return array(
                'status'  => 'already_complete',
                'message' => 'Your attendance for today is already complete (in ' . date('h:i A', strtotime($row->in_time)) . ', out ' . date('h:i A', strtotime($row->out_time)) . ').',
            );
        }

        // ---- OUT scan candidate: in_time set, out_time empty ----
        // Cooldown: ignore accidental rapid re-scans after the in-scan.
        $cooldown = isset($opts['cooldown_minutes']) ? (int) $opts['cooldown_minutes'] : 5;
        if ($cooldown > 0) {
            $secs_since_in = strtotime("1970-01-01 $now UTC") - strtotime("1970-01-01 {$row->in_time} UTC");
            if ($secs_since_in >= 0 && $secs_since_in < ($cooldown * 60)) {
                return array(
                    'status'  => 'cooldown',
                    'message' => 'You already marked in at ' . date('h:i A', strtotime($row->in_time)) . '. Scan again after your shift to mark out.',
                );
            }
        }

        // Early-exit guard: only accept out-scan past the earliest exit time,
        // unless the staff explicitly confirms leaving early.
        $confirm_early = !empty($opts['confirm_early']);
        $earliest_out  = null;
        if (isset($opts['earliest_out_source']) && $opts['earliest_out_source'] === 'manual') {
            if (!IsNullOrEmptyString($opts['manual_earliest_out_time'])) {
                $earliest_out = date('H:i:s', strtotime($opts['manual_earliest_out_time']));
            }
        } else {
            $earliest_out = $this->scheduleEarliestOut($role_id, $row->in_time);
        }

        if (!$confirm_early && $earliest_out !== null && $now < $earliest_out) {
            return array(
                'status'       => 'confirm_early',
                'message'      => 'It is earlier than your exit time (' . date('h:i A', strtotime($earliest_out)) . '). Are you leaving early?',
                'earliest_out' => date('h:i A', strtotime($earliest_out)),
            );
        }

        // Record the out-scan.
        $update = array('out_time' => $now, 'updated_at' => date('Y-m-d H:i:s'));
        $type   = $this->staff_schedule_hours($row->staff_id, $row->in_time);
        if ($type) {
            $update['staff_attendance_type_id'] = $type;
        }
        $early_note = '';
        if ($earliest_out !== null && $now < $earliest_out) {
            $reason     = isset($opts['reason']) ? trim($opts['reason']) : '';
            $early_note = 'early exit (QR)' . ($reason !== '' ? ': ' . $reason : '');
        }
        if ($early_note !== '') {
            $existing = IsNullOrEmptyString($row->remark) ? '' : $row->remark . ' | ';
            $update['remark'] = $existing . $early_note;
        }
        $this->db->where('id', $row->id)->update('staff_attendance', $update);

        return array('status' => 'marked_out', 'message' => 'Marked out. Have a good day!', 'time' => date('h:i A', strtotime($now)));
    }

    /**
     * Current QR state for a staff today, used by the scan page to decide which
     * action buttons to show.
     * Returns one of: not_in, on_break, complete, in  (+ context fields).
     */
    public function getQrState($staff_id)
    {
        $date = date('Y-m-d');
        $this->db->where('staff_id', $staff_id)->where('date', $date);
        $att = $this->db->get('staff_attendance')->row();

        if (empty($att) || IsNullOrEmptyString($att->in_time)) {
            return array('state' => 'not_in');
        }

        // Open break? (out_time set, in_time null)
        if ($this->db->table_exists('staff_attendance_break')) {
            $this->db->where('staff_id', $staff_id)->where('date', $date)
                     ->where('in_time IS NULL')->order_by('id', 'desc')->limit(1);
            $open = $this->db->get('staff_attendance_break')->row();
            if (!empty($open)) {
                return array('state' => 'on_break', 'since' => date('h:i A', strtotime($open->out_time)));
            }
        }

        if (!IsNullOrEmptyString($att->out_time)) {
            return array('state' => 'complete', 'in' => date('h:i A', strtotime($att->in_time)), 'out' => date('h:i A', strtotime($att->out_time)));
        }

        return array('state' => 'in', 'in' => date('h:i A', strtotime($att->in_time)));
    }

    /**
     * Log the start of a mid-day outing. Requires the staff to be checked in
     * and not already on an open break.
     */
    public function qrBreakOut($staff_id, $reason = '')
    {
        $date = date('Y-m-d');
        $now  = date('H:i:s');

        $st = $this->getQrState($staff_id);
        if ($st['state'] === 'not_in') {
            return array('status' => 'error', 'message' => 'Please check in first before stepping out.');
        }
        if ($st['state'] === 'on_break') {
            return array('status' => 'error', 'message' => 'You are already marked as stepped out. Scan again to step back in.');
        }
        if ($st['state'] === 'complete') {
            return array('status' => 'error', 'message' => 'Your attendance for today is already complete.');
        }

        $this->db->insert('staff_attendance_break', array(
            'staff_id'   => $staff_id,
            'date'       => $date,
            'out_time'   => $now,
            'reason'     => ($reason === null) ? '' : trim($reason),
            'is_active'  => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ));
        return array('status' => 'break_out', 'message' => 'Stepped out. Scan again when you return.', 'time' => date('h:i A', strtotime($now)));
    }

    /**
     * Close the open outing for the staff, recording return time and duration.
     */
    public function qrBreakIn($staff_id)
    {
        $date = date('Y-m-d');
        $now  = date('H:i:s');

        $this->db->where('staff_id', $staff_id)->where('date', $date)
                 ->where('in_time IS NULL')->order_by('id', 'desc')->limit(1);
        $open = $this->db->get('staff_attendance_break')->row();
        if (empty($open)) {
            return array('status' => 'error', 'message' => 'No open outing found to step back in.');
        }

        $mins = (int) round((strtotime("1970-01-01 $now UTC") - strtotime("1970-01-01 {$open->out_time} UTC")) / 60);
        if ($mins < 0) { $mins = 0; }

        $this->db->where('id', $open->id)->update('staff_attendance_break', array(
            'in_time'          => $now,
            'duration_minutes' => $mins,
            'updated_at'       => date('Y-m-d H:i:s'),
        ));

        $h = intdiv($mins, 60); $mm = $mins % 60;
        $dur = ($h > 0 ? $h . 'h ' : '') . $mm . 'm';
        return array('status' => 'break_in', 'message' => 'Welcome back. You were out for ' . $dur . '.', 'time' => date('h:i A', strtotime($now)));
    }

    // =================================================================
    //  MONTHLY ATTENDANCE SHEET
    // =================================================================

    /**
     * Per-staff daily attendance for a role across a month.
     * Returns ['staff' => [ {staff_id,name,employee_id} ], 'map' => [staff_id => ['Y-m-d' => long_lang_name]]].
     */
    public function getMonthlySheet($role_name, $year, $month)
    {
        $start = sprintf('%04d-%02d-01', $year, $month);
        $end   = date('Y-m-t', strtotime($start));

        // Optional role filter — empty/select means "all staff".
        $role_cond = '';
        if (!empty($role_name) && $role_name !== 'select') {
            $role_cond = " AND roles.name = " . $this->db->escape($role_name);
        }

        $sql = "SELECT staff.id AS staff_id, staff.name, staff.surname, staff.employee_id,
                       roles.name AS role_name, sa.date, sat.long_lang_name
                FROM staff
                LEFT JOIN staff_roles ON staff_roles.staff_id = staff.id
                LEFT JOIN roles ON roles.id = staff_roles.role_id
                LEFT JOIN staff_attendance sa ON sa.staff_id = staff.id AND sa.date BETWEEN " . $this->db->escape($start) . " AND " . $this->db->escape($end) . "
                LEFT JOIN staff_attendance_type sat ON sat.id = sa.staff_attendance_type_id
                WHERE staff.is_active = 1" . $role_cond . "
                ORDER BY staff.name, staff.surname";
        $rows = $this->db->query($sql)->result();

        $staff = array();
        $seen  = array();
        $map   = array();
        foreach ($rows as $r) {
            if (!isset($seen[$r->staff_id])) {
                $seen[$r->staff_id] = true;
                $staff[] = array(
                    'staff_id'    => $r->staff_id,
                    'name'        => trim($r->name . ' ' . $r->surname),
                    'employee_id' => $r->employee_id,
                    'role_name'   => $r->role_name,
                );
                $map[$r->staff_id] = array();
            }
            if (!empty($r->date) && !empty($r->long_lang_name)) {
                $map[$r->staff_id][$r->date] = $r->long_lang_name;
            }
        }
        return array('staff' => $staff, 'map' => $map);
    }

    /**
     * Distinct calendar-holiday dates (Y-m-d) within a month, taken from the
     * annual_calendar (non-working days flagged for attendance).
     */
    public function getMonthHolidayDates($year, $month)
    {
        $start = sprintf('%04d-%02d-01', $year, $month);
        $end   = date('Y-m-t', strtotime($start));
        $dates = array();

        if (!$this->db->table_exists('annual_calendar')) {
            return $dates;
        }
        $sql = "SELECT from_date, to_date FROM annual_calendar
                WHERE is_active = 1 AND is_working_day = 0
                AND (module_impact IS NULL OR module_impact LIKE '%attendance%')
                AND DATE(from_date) <= " . $this->db->escape($end) . "
                AND DATE(to_date) >= " . $this->db->escape($start) . "";
        $rows = $this->db->query($sql)->result();
        $ms = strtotime($start);
        $me = strtotime($end);
        foreach ($rows as $r) {
            $d = max($ms, strtotime(date('Y-m-d', strtotime($r->from_date))));
            $to = min($me, strtotime(date('Y-m-d', strtotime($r->to_date))));
            for ($t = $d; $t <= $to; $t += 86400) {
                $dates[date('Y-m-d', $t)] = true;
            }
        }
        return array_keys($dates);
    }

}
