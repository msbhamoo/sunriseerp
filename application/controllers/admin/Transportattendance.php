<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Transportattendance extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('transportattendance_model');
        $this->load->model('vehicle_model');
        $this->load->model('student_model');
        $this->load->model('gatepass_model');
        $this->config->load('app-config');
    }

    private function parseToYYYYMMDD($date_str)
    {
        if (empty($date_str)) {
            return null;
        }
        $date_str = trim((string)$date_str);
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_str)) {
            return $date_str;
        }
        $converted = $this->customlib->dateFormatToYYYYMMDD($date_str);
        if (!empty($converted) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $converted) && substr($converted, 0, 4) != '0000') {
            return $converted;
        }
        if (preg_match('/^(\d{1,2})[\/\-\.](\d{1,2})[\/\-\.](\d{4})$/', $date_str, $matches)) {
            $d = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
            $m = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
            $y = $matches[3];
            if (checkdate((int)$m, (int)$d, (int)$y)) {
                return "{$y}-{$m}-{$d}";
            }
        }
        $ts = strtotime($date_str);
        if ($ts !== false && $ts > 0) {
            return date('Y-m-d', $ts);
        }
        return date('Y-m-d');
    }

    private function isSuperAdmin()
    {
        $getStaffRole = $this->customlib->getStaffRole();
        $staffrole = json_decode($getStaffRole);
        if (!empty($staffrole) && (strtolower($staffrole->name) == 'super admin' || $staffrole->id == 7)) {
            return true;
        }
        return false;
    }

    private function getStaffAssignedVehicles($staff_id = null)
    {
        if (empty($staff_id)) {
            $staff_id = $this->customlib->getStaffID();
        }
        
        $all_vehicles = $this->vehicle_model->get();
        if (empty($all_vehicles)) {
            return array();
        }

        $this->load->model('staff_model');
        $staff = $this->staff_model->get($staff_id);
        if (empty($staff)) {
            return array();
        }

        $staff_name = strtolower(trim((string)$staff['name']));
        $staff_surname = strtolower(trim((string)$staff['surname']));
        $staff_fullname = trim($staff_name . ' ' . $staff_surname);
        $staff_emp_id = strtolower(trim((string)$staff['employee_id']));
        $staff_phone = preg_replace('/[^0-9]/', '', (string)$staff['contact_no']);

        $assigned_vehicles = array();
        foreach ($all_vehicles as $v) {
            $driver_name = strtolower(trim((string)$v['driver_name']));
            $driver_phone = preg_replace('/[^0-9]/', '', (string)$v['driver_contact']);
            
            $attendant_names_arr = array_filter(array_map('trim', explode(',', (string)$v['attendant_name'])));
            $attendant_phones_arr = array_filter(array_map('trim', explode(',', (string)$v['attendant_contact'])));

            $match = false;
            
            if (!empty($driver_name) && strlen($driver_name) > 1) {
                if ($driver_name == $staff_fullname || 
                    $driver_name == $staff_name || 
                    (!empty($staff_emp_id) && $driver_name == $staff_emp_id) ||
                    (!empty($staff_name) && strlen($staff_name) > 2 && strpos($driver_name, $staff_name) !== false) || 
                    (!empty($staff_fullname) && strlen($staff_fullname) > 2 && strpos($staff_fullname, $driver_name) !== false)) {
                    $match = true;
                }
            }

            if (!$match && !empty($attendant_names_arr)) {
                foreach ($attendant_names_arr as $single_att_name) {
                    $att_clean = strtolower(trim($single_att_name));
                    if (!empty($att_clean) && strlen($att_clean) > 1) {
                        if ($att_clean == $staff_fullname || 
                            $att_clean == $staff_name || 
                            (!empty($staff_emp_id) && $att_clean == $staff_emp_id) ||
                            (!empty($staff_name) && strlen($staff_name) > 2 && strpos($att_clean, $staff_name) !== false) || 
                            (!empty($staff_fullname) && strlen($staff_fullname) > 2 && strpos($staff_fullname, $att_clean) !== false)) {
                            $match = true;
                            break;
                        }
                    }
                }
            }
            
            if (!$match && !empty($staff_phone) && strlen($staff_phone) >= 7) {
                if (!empty($driver_phone) && (strpos($driver_phone, $staff_phone) !== false || strpos($staff_phone, $driver_phone) !== false)) {
                    $match = true;
                } elseif (!empty($attendant_phones_arr)) {
                    foreach ($attendant_phones_arr as $single_att_phone) {
                        $phone_clean = preg_replace('/[^0-9]/', '', $single_att_phone);
                        if (!empty($phone_clean) && (strpos($phone_clean, $staff_phone) !== false || strpos($staff_phone, $phone_clean) !== false)) {
                            $match = true;
                            break;
                        }
                    }
                }
            }

            if ($match) {
                $assigned_vehicles[] = $v;
            }
        }

        if (empty($assigned_vehicles)) {
            // If the staff member is NOT assigned as a driver or helper on any specific vehicle,
            // but HAS explicit RBAC permissions (e.g. Transport Manager/Incharge/Staff), allow access to ALL vehicles.
            if ($this->rbac->hasPrivilege('vehicle', 'can_view') || 
                $this->rbac->hasPrivilege('transport_attendance', 'can_view')) {
                return $all_vehicles;
            }
        }

        return $assigned_vehicles;
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('transport_attendance', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Transport');
        $this->session->set_userdata('sub_menu', 'transportattendance/index');
        
        $data['title'] = 'Bus Attendance';
        
        $this->load->model('route_model');
        $this->load->model('vehroute_model');
        $data['all_routes'] = $this->route_model->get();

        if ($this->isSuperAdmin()) {
            $data['vehiclelist'] = $this->vehicle_model->get();
        } else {
            $data['vehiclelist'] = $this->getStaffAssignedVehicles();
        }
        
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('attendance_type', 'Attendance Type', 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/transport/attendance', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $date = $this->parseToYYYYMMDD($this->input->post('date'));
            $vehicle_id = $this->input->post('vehicle_id');
            $route_id = $this->input->post('route_id');
            $attendance_type = $this->input->post('attendance_type');
            
            // If route selected but vehicle not chosen, resolve vehicle from route
            if (empty($vehicle_id) && !empty($route_id)) {
                $mapped_vehicles = $this->vehroute_model->getVechileByRoute($route_id);
                if (!empty($mapped_vehicles)) {
                    $vehicle_id = $mapped_vehicles[0]->id;
                }
            }

            if (empty($vehicle_id)) {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger">Please select a Vehicle or Route.</div>');
                redirect('admin/transportattendance/index');
            }
            
            if (!$this->isSuperAdmin()) {
                $allowed_ids = array_column($data['vehiclelist'], 'id');
                if (!in_array($vehicle_id, $allowed_ids)) {
                    access_denied();
                }
            }

            $data['date'] = $date;
            $data['vehicle_id'] = $vehicle_id;
            $data['route_id'] = $route_id;
            $data['attendance_type'] = $attendance_type;
            
            // Get available routes for this vehicle for the dropdown
            $data['vehicle_routes'] = $this->vehicle_model->getVehicleRoutes($vehicle_id);
            
            $students = $this->transportattendance_model->get_bus_students($vehicle_id, null, $route_id);
            $saved_attendance = $this->transportattendance_model->get_attendance($vehicle_id, $date, $attendance_type);
            $custom_riders = $this->transportattendance_model->get_custom_riders($vehicle_id, $date, $attendance_type);
            $switched_out = $this->transportattendance_model->get_switched_out_students_map($vehicle_id, $date, $attendance_type);
            
            // Determine opposite shift to display context (Morning vs Evening)
            $opposite_shift = (strtolower($attendance_type) == 'evening') ? 'morning' : 'evening';
            $opposite_attendance = $this->transportattendance_model->get_attendance($vehicle_id, $date, $opposite_shift);
            $opposite_presence = $this->transportattendance_model->check_transport_presence($date, $opposite_shift);
            $data['opposite_shift'] = ucfirst($opposite_shift);

            // Merge custom riders into the main list so they can be managed (if route filter is empty or matches)
            if (!empty($custom_riders)) {
                $students = array_merge($students, $custom_riders);
            }
            
            $gatepasses = $this->gatepass_model->check_student_gatepass($date);
            
            foreach ($students as $key => $student) {
                // Check if this student is switched out to another bus today
                if (isset($switched_out[$student['student_session_id']])) {
                    $students[$key]['switched_out_info'] = $switched_out[$student['student_session_id']];
                } else {
                    $students[$key]['switched_out_info'] = null;
                }

                if (isset($saved_attendance[$student['student_session_id']])) {
                    $students[$key]['attendance_status'] = $saved_attendance[$student['student_session_id']]['status'];
                    $students[$key]['remark'] = $saved_attendance[$student['student_session_id']]['remark'];
                } else {
                    if (!empty($students[$key]['switched_out_info'])) {
                        $students[$key]['attendance_status'] = 'Switched Bus';
                        $students[$key]['remark'] = 'Switched to Bus #' . $students[$key]['switched_out_info']['new_vehicle_no'];
                    } else {
                        $students[$key]['attendance_status'] = 'Present';
                        $students[$key]['remark'] = '';
                    }
                }
                
                // Opposite shift status check
                if (isset($opposite_attendance[$student['student_session_id']])) {
                    $students[$key]['opposite_shift_status'] = $opposite_attendance[$student['student_session_id']]['status'];
                } elseif (isset($opposite_presence[$student['student_session_id']])) {
                    $students[$key]['opposite_shift_status'] = 'Present (Bus #' . $opposite_presence[$student['student_session_id']] . ')';
                } else {
                    $students[$key]['opposite_shift_status'] = 'Not Marked';
                }

                // If they have a gatepass today, mark it
                $students[$key]['has_gatepass'] = in_array($student['student_id'], $gatepasses) ? true : false;
                if ($students[$key]['has_gatepass'] && !isset($saved_attendance[$student['student_session_id']])) {
                    $students[$key]['attendance_status'] = 'Gatepass'; // Default status if not yet saved
                }
            }

            // Default sorting: Alphabetical by student name
            usort($students, function($a, $b) {
                $nameA = trim($a['firstname'] . ' ' . $a['lastname']);
                $nameB = trim($b['firstname'] . ' ' . $b['lastname']);
                return strcasecmp($nameA, $nameB);
            });

            // Calculate comprehensive shift and flow metrics
            $total_strength = count($students);
            $regular_count = 0;
            $custom_count = 0;
            $present_count = 0;
            $absent_count = 0;
            $morning_present_count = 0;
            $gatepass_count = 0;
            $switched_out_count = 0;
            $retained_flow_count = 0;

            foreach ($students as $s) {
                $is_cust = (isset($s['status']) && $s['status'] == 'Switched Bus');
                if ($is_cust) {
                    $custom_count++;
                } else {
                    $regular_count++;
                }

                $cur_status = isset($s['attendance_status']) ? $s['attendance_status'] : 'Present';
                if ($cur_status == 'Present' || ($is_cust && $cur_status == 'Switched Bus')) {
                    $present_count++;
                } elseif ($cur_status == 'Absent') {
                    $absent_count++;
                }

                $opp_st = isset($s['opposite_shift_status']) ? $s['opposite_shift_status'] : '';
                $was_morning_present = (strpos(strtolower($opp_st), 'present') !== false || strpos(strtolower($opp_st), 'switched') !== false);
                if ($was_morning_present) {
                    $morning_present_count++;
                    if ($cur_status == 'Present' || ($is_cust && $cur_status == 'Switched Bus')) {
                        $retained_flow_count++;
                    }
                }

                if (!empty($s['has_gatepass'])) {
                    $gatepass_count++;
                }
                if (!empty($s['switched_out_info'])) {
                    $switched_out_count++;
                }
            }

            $data['metrics'] = array(
                'total_strength' => $total_strength,
                'regular_count' => $regular_count,
                'custom_count' => $custom_count,
                'present_count' => $present_count,
                'absent_count' => $absent_count,
                'morning_present_count' => $morning_present_count,
                'retained_flow_count' => $retained_flow_count,
                'gatepass_count' => $gatepass_count,
                'switched_out_count' => $switched_out_count
            );

            $data['resultlist'] = $students;
            
            $this->load->view('layout/header', $data);
            $this->load->view('admin/transport/attendance', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    public function mobile()
    {
        if (!$this->rbac->hasPrivilege('transport_attendance', 'can_view')) {
            access_denied();
        }
        
        $data['title'] = 'Driver Quick Mode - Bus Attendance';
        
        if ($this->isSuperAdmin()) {
            $data['vehiclelist'] = $this->vehicle_model->get();
        } else {
            $data['vehiclelist'] = $this->getStaffAssignedVehicles();
        }
        
        $date_input = $this->input->get_post('date');
        $date = !empty($date_input) ? $this->parseToYYYYMMDD($date_input) : date('Y-m-d');
        $vehicle_id = $this->input->get_post('vehicle_id');
        $route_id = $this->input->get_post('route_id');
        $attendance_type = $this->input->get_post('attendance_type');
        
        // Auto default attendance type based on current time (Morning before 12 PM, Evening after 12 PM)
        if (empty($attendance_type)) {
            $current_hour = (int)date('H');
            $attendance_type = ($current_hour < 12) ? 'morning' : 'evening';
        }
        
        // If route selected but vehicle not chosen, resolve vehicle from route
        if (empty($vehicle_id) && !empty($route_id)) {
            $this->load->model('vehroute_model');
            $mapped_vehicles = $this->vehroute_model->getVechileByRoute($route_id);
            if (!empty($mapped_vehicles)) {
                $vehicle_id = $mapped_vehicles[0]->id;
            }
        }

        // Auto select single vehicle if only 1 assigned
        if (empty($vehicle_id) && !empty($data['vehiclelist']) && count($data['vehiclelist']) == 1) {
            $vehicle_id = $data['vehiclelist'][0]['id'];
        }

        $data['date'] = $date;
        $data['vehicle_id'] = $vehicle_id;
        $data['route_id'] = $route_id;
        $data['attendance_type'] = $attendance_type;
        $data['vehicle_routes'] = !empty($vehicle_id) ? $this->vehicle_model->getVehicleRoutes($vehicle_id) : array();
        
        if (!empty($vehicle_id)) {
            if (!$this->isSuperAdmin()) {
                $allowed_ids = array_column($data['vehiclelist'], 'id');
                if (!in_array($vehicle_id, $allowed_ids)) {
                    access_denied();
                }
            }

            $students = $this->transportattendance_model->get_bus_students($vehicle_id, null, $route_id);
            $saved_attendance = $this->transportattendance_model->get_attendance($vehicle_id, $date, $attendance_type);
            $custom_riders = $this->transportattendance_model->get_custom_riders($vehicle_id, $date, $attendance_type);
            $switched_out = $this->transportattendance_model->get_switched_out_students_map($vehicle_id, $date, $attendance_type);
            
            $opposite_shift = (strtolower($attendance_type) == 'evening') ? 'morning' : 'evening';
            $opposite_attendance = $this->transportattendance_model->get_attendance($vehicle_id, $date, $opposite_shift);
            $opposite_presence = $this->transportattendance_model->check_transport_presence($date, $opposite_shift);
            $data['opposite_shift'] = ucfirst($opposite_shift);

            if (!empty($custom_riders)) {
                $students = array_merge($students, $custom_riders);
            }
            
            $gatepasses = $this->gatepass_model->check_student_gatepass($date);
            
            // Group students by Stop Name (Pickup Point)
            $grouped_by_stop = array();
            foreach ($students as $key => $student) {
                // Check if switched out to another bus
                if (isset($switched_out[$student['student_session_id']])) {
                    $student['switched_out_info'] = $switched_out[$student['student_session_id']];
                } else {
                    $student['switched_out_info'] = null;
                }

                if (isset($saved_attendance[$student['student_session_id']])) {
                    $student['attendance_status'] = $saved_attendance[$student['student_session_id']]['status'];
                    $student['remark'] = $saved_attendance[$student['student_session_id']]['remark'];
                } else {
                    if (!empty($student['switched_out_info'])) {
                        $student['attendance_status'] = 'Switched Bus';
                        $student['remark'] = 'Switched to Bus #' . $student['switched_out_info']['new_vehicle_no'];
                    } else {
                        $student['attendance_status'] = 'Present';
                        $student['remark'] = '';
                    }
                }
                
                if (isset($opposite_attendance[$student['student_session_id']])) {
                    $student['opposite_shift_status'] = $opposite_attendance[$student['student_session_id']]['status'];
                } elseif (isset($opposite_presence[$student['student_session_id']])) {
                    $student['opposite_shift_status'] = 'Present (Bus #' . $opposite_presence[$student['student_session_id']] . ')';
                } else {
                    $student['opposite_shift_status'] = 'Not Marked';
                }

                $student['has_gatepass'] = in_array($student['student_id'], $gatepasses) ? true : false;
                if ($student['has_gatepass'] && !isset($saved_attendance[$student['student_session_id']])) {
                    $student['attendance_status'] = 'Gatepass';
                }
                
                $stop_key = !empty($student['pickup_point_name']) ? $student['pickup_point_name'] : 'Other / Direct Stop';
                if (!isset($grouped_by_stop[$stop_key])) {
                    $grouped_by_stop[$stop_key] = array();
                }
                $grouped_by_stop[$stop_key][] = $student;
            }
            
            // Calculate comprehensive shift and flow metrics
            $total_strength = count($students);
            $regular_count = 0;
            $custom_count = 0;
            $present_count = 0;
            $absent_count = 0;
            $morning_present_count = 0;
            $gatepass_count = 0;
            $switched_out_count = 0;
            $retained_flow_count = 0;

            foreach ($students as $s) {
                $is_cust = (isset($s['status']) && $s['status'] == 'Switched Bus');
                if ($is_cust) {
                    $custom_count++;
                } else {
                    $regular_count++;
                }

                $cur_status = isset($s['attendance_status']) ? $s['attendance_status'] : 'Present';
                if ($cur_status == 'Present' || ($is_cust && $cur_status == 'Switched Bus')) {
                    $present_count++;
                } elseif ($cur_status == 'Absent') {
                    $absent_count++;
                }

                $opp_st = isset($s['opposite_shift_status']) ? $s['opposite_shift_status'] : '';
                $was_morning_present = (strpos(strtolower($opp_st), 'present') !== false || strpos(strtolower($opp_st), 'switched') !== false);
                if ($was_morning_present) {
                    $morning_present_count++;
                    if ($cur_status == 'Present' || ($is_cust && $cur_status == 'Switched Bus')) {
                        $retained_flow_count++;
                    }
                }

                if (!empty($s['has_gatepass'])) {
                    $gatepass_count++;
                }
                if (!empty($s['switched_out_info'])) {
                    $switched_out_count++;
                }
            }

            $data['metrics'] = array(
                'total_strength' => $total_strength,
                'regular_count' => $regular_count,
                'custom_count' => $custom_count,
                'present_count' => $present_count,
                'absent_count' => $absent_count,
                'morning_present_count' => $morning_present_count,
                'retained_flow_count' => $retained_flow_count,
                'gatepass_count' => $gatepass_count,
                'switched_out_count' => $switched_out_count
            );

            $data['grouped_students'] = $grouped_by_stop;
            $data['students_count'] = count($students);
        } else {
            $data['grouped_students'] = array();
            $data['students_count'] = 0;
            $data['metrics'] = array(
                'total_strength' => 0,
                'regular_count' => 0,
                'custom_count' => 0,
                'present_count' => 0,
                'absent_count' => 0,
                'morning_present_count' => 0,
                'retained_flow_count' => 0,
                'gatepass_count' => 0,
                'switched_out_count' => 0
            );
        }
        
        $this->load->view('admin/transport/mobile_attendance', $data);
    }

    public function get_vehicle_routes()
    {
        $vehicle_id = $this->input->post('vehicle_id');
        if (empty($vehicle_id)) {
            echo json_encode(array());
            return;
        }

        $routes_raw = $this->vehicle_model->getVehicleRoutes($vehicle_id);
        $routes = array();
        $seen = array();
        if (!empty($routes_raw)) {
            foreach ($routes_raw as $r) {
                $r_id = $r['route_id'];
                if (!isset($seen[$r_id])) {
                    $seen[$r_id] = true;
                    $routes[] = array(
                        'route_id' => $r_id,
                        'route_title' => $r['route_title']
                    );
                }
            }
        }
        echo json_encode($routes);
    }

    public function save()
    {
        if (!$this->rbac->hasPrivilege('transport_attendance', 'can_add')) {
            access_denied();
        }
        $date = $this->input->post('date');
        $vehicle_id = $this->input->post('vehicle_id');
        $attendance_type = $this->input->post('attendance_type');
        $student_session = $this->input->post('student_session');

        if (!$this->isSuperAdmin()) {
            $assigned_vehicles = $this->getStaffAssignedVehicles();
            $allowed_ids = array_column($assigned_vehicles, 'id');
            if (!in_array($vehicle_id, $allowed_ids)) {
                access_denied();
            }
        }
        
        $insert_data = [];
        if (!empty($student_session)) {
            foreach ($student_session as $session_id) {
                $status = $this->input->post('attendencetype' . $session_id);
                $remark = $this->input->post('remark' . $session_id);
                
                if ($status == 'Switched Bus' || $this->input->post('is_custom_rider' . $session_id) == 'yes') {
                    $status = 'Switched Bus';
                }

                $insert_data[] = [
                    'student_session_id' => $session_id,
                    'vehicle_id' => $vehicle_id,
                    'date' => $date,
                    'attendance_type' => $attendance_type,
                    'status' => $status,
                    'remark' => $remark
                ];
            }
            
            $this->transportattendance_model->save_attendance($insert_data);
            
            if ($attendance_type == 'morning') {
                $this->syncMorningBusToClassAttendance($insert_data);
            }

            $this->session->set_flashdata('msg', '<div class="alert alert-success">Attendance saved successfully</div>');
        }
        
        redirect('admin/transportattendance');
    }

    private function syncMorningBusToClassAttendance($insert_data)
    {
        if (empty($insert_data)) {
            return;
        }

        foreach ($insert_data as $row) {
            $session_id = $row['student_session_id'];
            $status = $row['status'];
            $date = $row['date'];

            // Only sync if student was Present or Switched Bus on the morning bus
            if ($status == 'Present' || $status == 'Switched Bus') {
                $this->db->where('student_session_id', $session_id);
                $this->db->where('date', $date);
                $query = $this->db->get('student_attendences');

                if ($query->num_rows() == 0) {
                    $sync_data = [
                        'student_session_id' => $session_id,
                        'attendence_type_id' => 1, // 1 = Present
                        'date'               => $date,
                        'remark'             => 'Auto-synced from Morning Bus',
                        'created_at'         => date('Y-m-d H:i:s')
                    ];
                    $this->db->insert('student_attendences', $sync_data);
                }
            }
        }
    }

    public function search_student()
    {
        $search = $this->input->post('search');
        $students = $this->transportattendance_model->search_student_for_transport($search);
        echo json_encode($students);
    }

    public function add_custom_rider()
    {
        if (!$this->rbac->hasPrivilege('transport_attendance', 'can_add')) {
            echo json_encode(['status' => 0, 'msg' => 'Access Denied']);
            return;
        }
        $student_id = $this->input->post('student_id');
        $vehicle_id = $this->input->post('vehicle_id');
        $from_date_input = $this->input->post('from_date');
        $to_date_input = $this->input->post('to_date');
        $date_input = $this->input->post('date');
        $attendance_type = $this->input->post('attendance_type');
        $remark_input = trim((string)$this->input->post('remark'));

        if (empty($from_date_input) && !empty($date_input)) {
            $from_date_input = $date_input;
        }
        if (empty($to_date_input) && !empty($from_date_input)) {
            $to_date_input = $from_date_input;
        }

        $from_date = $this->parseToYYYYMMDD($from_date_input);
        $to_date = $this->parseToYYYYMMDD($to_date_input);

        if (empty($from_date)) {
            $from_date = date('Y-m-d');
        }
        if (empty($to_date)) {
            $to_date = $from_date;
        }

        if (strtotime($to_date) < strtotime($from_date)) {
            $to_date = $from_date;
        }

        if (!$this->isSuperAdmin()) {
            $assigned_vehicles = $this->getStaffAssignedVehicles();
            $allowed_ids = array_column($assigned_vehicles, 'id');
            if (!in_array($vehicle_id, $allowed_ids)) {
                echo json_encode(['status' => 0, 'msg' => 'Access Denied']);
                return;
            }
        }
        
        // Get student session
        $session_id = $this->setting_model->getCurrentSession();
        $this->db->where('student_id', $student_id);
        $this->db->where('session_id', $session_id);
        $student_session = $this->db->get('student_session')->row_array();
        
        if ($student_session) {
            // Check if already permanently assigned to this bus
            $bus_students = $this->transportattendance_model->get_bus_students($vehicle_id);
            $is_already_in_bus = false;
            foreach ($bus_students as $bs) {
                if ($bs['student_session_id'] == $student_session['id']) {
                    $is_already_in_bus = true;
                    break;
                }
            }
            
            if ($is_already_in_bus) {
                echo json_encode(['status' => 0, 'msg' => 'Student is already permanently assigned to this bus.']);
                return;
            }

            // Determine shifts
            $shifts = array();
            if (strtolower($attendance_type) == 'both') {
                $shifts = array('morning', 'evening');
            } elseif (!empty($attendance_type)) {
                $shifts = array(strtolower($attendance_type));
            } else {
                $shifts = array('morning', 'evening');
            }

            // Build list of dates using DateTime
            $period_dates = array();
            $cur_dt = new DateTime($from_date);
            $end_dt = new DateTime($to_date);
            $end_dt->modify('+1 day');
            $period = new DatePeriod($cur_dt, new DateInterval('P1D'), $end_dt);
            foreach ($period as $dt) {
                $period_dates[] = $dt->format('Y-m-d');
            }

            $num_days = count($period_dates);
            $start_ts = strtotime($from_date);
            $end_ts = strtotime($to_date);
            $default_remark = ($num_days > 1) 
                ? 'Custom Rider (' . date('d M', $start_ts) . ' - ' . date('d M', $end_ts) . ')'
                : 'Custom Rider (' . date('d M', $start_ts) . ')';
            
            if (!empty($remark_input)) {
                $final_remark = $remark_input . ' [' . $default_remark . ']';
            } else {
                $final_remark = $default_remark;
            }

            $insert_data = array();
            foreach ($period_dates as $d) {
                foreach ($shifts as $s) {
                    $insert_data[] = array(
                        'student_session_id' => $student_session['id'],
                        'vehicle_id' => $vehicle_id,
                        'date' => $d,
                        'attendance_type' => $s,
                        'status' => 'Switched Bus',
                        'remark' => $final_remark
                    );
                }
            }

            $this->transportattendance_model->save_attendance($insert_data);
            
            $msg = ($num_days > 1) 
                ? "Student successfully added as custom rider for {$num_days} days (" . date('d M Y', $start_ts) . " to " . date('d M Y', $end_ts) . ")."
                : "Student successfully added as custom rider for today.";

            echo json_encode(['status' => 1, 'msg' => $msg]);
        } else {
            echo json_encode(['status' => 0, 'msg' => 'Student session not found.']);
        }
    }

    public function remove_custom_rider()
    {
        if (!$this->rbac->hasPrivilege('transport_attendance', 'can_edit')) {
            echo json_encode(['status' => 0, 'msg' => 'Access Denied']);
            return;
        }

        $student_session_id = $this->input->post('student_session_id');
        $vehicle_id = $this->input->post('vehicle_id');
        $date = $this->parseToYYYYMMDD($this->input->post('date'));
        $attendance_type = $this->input->post('attendance_type');

        if (!$this->isSuperAdmin()) {
            $assigned_vehicles = $this->getStaffAssignedVehicles();
            $allowed_ids = array_column($assigned_vehicles, 'id');
            if (!in_array($vehicle_id, $allowed_ids)) {
                echo json_encode(['status' => 0, 'msg' => 'Access Denied']);
                return;
            }
        }

        if ($student_session_id && $vehicle_id && $date) {
            $this->db->where('student_session_id', $student_session_id);
            $this->db->where('vehicle_id', $vehicle_id);
            $this->db->where('date', $date);
            $this->db->where('attendance_type', $attendance_type);
            $this->db->where('status', 'Switched Bus');
            $this->db->delete('transport_attendance');
            
            echo json_encode(['status' => 1, 'msg' => 'Custom rider removed successfully.']);
        } else {
            echo json_encode(['status' => 0, 'msg' => 'Invalid parameters.']);
        }
    }

    public function daily_summary()
    {
        if (!$this->rbac->hasPrivilege('daily_bus_summary', 'can_view') && !$this->rbac->hasPrivilege('transport_attendance', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Transport');
        $this->session->set_userdata('sub_menu', 'transport_attendance/daily_summary');
        
        $date = $this->input->post('date');
        if (empty($date)) {
            $date = date($this->customlib->getSchoolDateFormat());
        }
        
        $search_date = $this->parseToYYYYMMDD($date);
        
        $data['date'] = $date;
        $summary = $this->transportattendance_model->get_daily_summary($search_date);
        
        if (!$this->isSuperAdmin()) {
            $assigned_vehicles = $this->getStaffAssignedVehicles();
            $allowed_ids = array_column($assigned_vehicles, 'id');
            $filtered = array();
            if (!empty($summary)) {
                foreach ($summary as $row) {
                    if (in_array($row['vehicle_id'], $allowed_ids)) {
                        $filtered[] = $row;
                    }
                }
            }
            $summary = $filtered;
        }
        
        $data['summary'] = $summary;
        
        $this->load->view('layout/header');
        $this->load->view('admin/transport/daily_summary', $data);
        $this->load->view('layout/footer');
    }

    public function monthly_summary()
    {
        if (!$this->rbac->hasPrivilege('monthly_bus_summary', 'can_view') && !$this->rbac->hasPrivilege('transport_attendance', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Transport');
        $this->session->set_userdata('sub_menu', 'transport_attendance/monthly_summary');
        
        $month = $this->input->post('month');
        $year = $this->input->post('year');
        
        if (empty($month) || empty($year)) {
            $month = date('m');
            $year = date('Y');
        }
        
        $data['month'] = $month;
        $data['year'] = $year;
        $summary = $this->transportattendance_model->get_monthly_summary($month, $year);
        
        if (!$this->isSuperAdmin()) {
            $assigned_vehicles = $this->getStaffAssignedVehicles();
            $allowed_ids = array_column($assigned_vehicles, 'id');
            $filtered = array();
            if (!empty($summary)) {
                foreach ($summary as $row) {
                    if (in_array($row['vehicle_id'], $allowed_ids)) {
                        $filtered[] = $row;
                    }
                }
            }
            $summary = $filtered;
        }
        
        $data['summary'] = $summary;
        
        $this->load->view('layout/header');
        $this->load->view('admin/transport/monthly_summary', $data);
        $this->load->view('layout/footer');
    }
    
    public function get_summary_detail()
    {
        if (!$this->rbac->hasPrivilege('daily_bus_summary', 'can_view') && !$this->rbac->hasPrivilege('transport_attendance', 'can_view')) {
            echo json_encode(['status' => 0, 'msg' => 'Access Denied']);
            return;
        }

        $vehicle_id = $this->input->post('vehicle_id');
        $route_id = $this->input->post('route_id');
        $date_str = $this->input->post('date');
        $date = $this->parseToYYYYMMDD($date_str);

        if (!$this->isSuperAdmin()) {
            $assigned_vehicles = $this->getStaffAssignedVehicles();
            $allowed_ids = array_column($assigned_vehicles, 'id');
            if (!in_array($vehicle_id, $allowed_ids)) {
                echo json_encode(['status' => 0, 'msg' => 'Access Denied']);
                return;
            }
        }
        
        $details = $this->transportattendance_model->get_attendance_detail($vehicle_id, $date, $route_id);
        
        $html = '<table class="table table-striped table-bordered table-hover">';
        $html .= '<thead><tr><th>Student</th><th>Class (Section)</th><th>Route & Stop</th><th>Shift</th><th>Status</th><th>Remark</th></tr></thead><tbody>';
        
        if (!empty($details)) {
            foreach ($details as $row) {
                $status_label = $row['status'] == 'Switched Bus' ? '<span class="label label-info">Custom Rider</span>' : '<span class="label label-success">Present</span>';
                $name = $row['firstname'] . ' ' . $row['lastname'] . ' (' . $row['admission_no'] . ')';
                $class_sec = $row['class'] . ' (' . $row['section'] . ')';
                $route_stop = '';
                if (!empty($row['route_title'])) {
                    $route_stop .= '<span class="text-primary"><i class="fa fa-road"></i> ' . $row['route_title'] . '</span>';
                }
                if (!empty($row['pickup_point_name'])) {
                    $route_stop .= (!empty($route_stop) ? '<br>' : '') . '<small class="text-muted"><i class="fa fa-map-marker text-danger"></i> ' . $row['pickup_point_name'] . '</small>';
                }
                if (empty($route_stop)) {
                    $route_stop = '-';
                }
                
                $html .= '<tr>';
                $html .= '<td>' . $name . '</td>';
                $html .= '<td>' . $class_sec . '</td>';
                $html .= '<td>' . $route_stop . '</td>';
                $html .= '<td>' . $row['attendance_type'] . '</td>';
                $html .= '<td>' . $status_label . '</td>';
                $html .= '<td>' . $row['remark'] . '</td>';
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr><td colspan="6" class="text-center text-danger">No attendance marked for this date.</td></tr>';
        }
        $html .= '</tbody></table>';
        
        echo json_encode(['status' => 1, 'html' => $html]);
    }
    
    public function get_monthly_summary_detail()
    {
        if (!$this->rbac->hasPrivilege('monthly_bus_summary', 'can_view') && !$this->rbac->hasPrivilege('transport_attendance', 'can_view')) {
            echo json_encode(['status' => 0, 'msg' => 'Access Denied']);
            return;
        }

        $vehicle_id = $this->input->post('vehicle_id');
        $route_id = $this->input->post('route_id');
        $month = $this->input->post('month');
        $year = $this->input->post('year');

        if (!$this->isSuperAdmin()) {
            $assigned_vehicles = $this->getStaffAssignedVehicles();
            $allowed_ids = array_column($assigned_vehicles, 'id');
            if (!in_array($vehicle_id, $allowed_ids)) {
                echo json_encode(['status' => 0, 'msg' => 'Access Denied']);
                return;
            }
        }
        
        $details = $this->transportattendance_model->get_monthly_attendance_detail($vehicle_id, $month, $year, $route_id);
        
        $html = '<table class="table table-striped table-bordered table-hover">';
        $html .= '<thead><tr><th>Date</th><th>Student</th><th>Class (Section)</th><th>Route & Stop</th><th>Shift</th><th>Status</th><th>Remark</th></tr></thead><tbody>';
        
        if (!empty($details)) {
            foreach ($details as $row) {
                $status_label = $row['status'] == 'Switched Bus' ? '<span class="label label-info">Custom Rider</span>' : '<span class="label label-success">Present</span>';
                $name = $row['firstname'] . ' ' . $row['lastname'] . ' (' . $row['admission_no'] . ')';
                $class_sec = $row['class'] . ' (' . $row['section'] . ')';
                $formatted_date = date($this->customlib->getSchoolDateFormat(), strtotime($row['date']));
                $route_stop = '';
                if (!empty($row['route_title'])) {
                    $route_stop .= '<span class="text-primary"><i class="fa fa-road"></i> ' . $row['route_title'] . '</span>';
                }
                if (!empty($row['pickup_point_name'])) {
                    $route_stop .= (!empty($route_stop) ? '<br>' : '') . '<small class="text-muted"><i class="fa fa-map-marker text-danger"></i> ' . $row['pickup_point_name'] . '</small>';
                }
                if (empty($route_stop)) {
                    $route_stop = '-';
                }
                
                $html .= '<tr>';
                $html .= '<td>' . $formatted_date . '</td>';
                $html .= '<td>' . $name . '</td>';
                $html .= '<td>' . $class_sec . '</td>';
                $html .= '<td>' . $route_stop . '</td>';
                $html .= '<td>' . $row['attendance_type'] . '</td>';
                $html .= '<td>' . $status_label . '</td>';
                $html .= '<td>' . $row['remark'] . '</td>';
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr><td colspan="7" class="text-center text-danger">No attendance marked for this month.</td></tr>';
        }
        $html .= '</tbody></table>';
        
        echo json_encode(['status' => 1, 'html' => $html]);
    }

    public function get_route_vehicles()
    {
        $route_id = $this->input->post('route_id');
        $this->load->model('vehroute_model');
        $vehicles = $this->vehroute_model->getVechileByRoute($route_id);
        echo json_encode($vehicles);
    }
}
