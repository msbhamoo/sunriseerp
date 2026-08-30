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
            $attendant_name = strtolower(trim((string)$v['attendant_name']));
            $driver_phone = preg_replace('/[^0-9]/', '', (string)$v['driver_contact']);
            $attendant_phone = preg_replace('/[^0-9]/', '', (string)$v['attendant_contact']);

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

            if (!empty($attendant_name) && strlen($attendant_name) > 1) {
                if ($attendant_name == $staff_fullname || 
                    $attendant_name == $staff_name || 
                    (!empty($staff_emp_id) && $attendant_name == $staff_emp_id) ||
                    (!empty($staff_name) && strlen($staff_name) > 2 && strpos($attendant_name, $staff_name) !== false) || 
                    (!empty($staff_fullname) && strlen($staff_fullname) > 2 && strpos($staff_fullname, $attendant_name) !== false)) {
                    $match = true;
                }
            }
            
            if (!empty($staff_phone) && strlen($staff_phone) >= 7) {
                if ((!empty($driver_phone) && (strpos($driver_phone, $staff_phone) !== false || strpos($staff_phone, $driver_phone) !== false)) || 
                    (!empty($attendant_phone) && (strpos($attendant_phone, $staff_phone) !== false || strpos($staff_phone, $attendant_phone) !== false))) {
                    $match = true;
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
        
        if ($this->isSuperAdmin()) {
            $data['vehiclelist'] = $this->vehicle_model->get();
        } else {
            $data['vehiclelist'] = $this->getStaffAssignedVehicles();
        }
        
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('vehicle_id', 'Vehicle', 'trim|required|xss_clean');
        $this->form_validation->set_rules('attendance_type', 'Attendance Type', 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/transport/attendance', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $date = $this->customlib->dateFormatToYYYYMMDD($this->input->post('date'));
            $vehicle_id = $this->input->post('vehicle_id');
            $route_id = $this->input->post('route_id');
            $attendance_type = $this->input->post('attendance_type');
            
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
                if (isset($saved_attendance[$student['student_session_id']])) {
                    $students[$key]['attendance_status'] = $saved_attendance[$student['student_session_id']]['status'];
                    $students[$key]['remark'] = $saved_attendance[$student['student_session_id']]['remark'];
                } else {
                    $students[$key]['attendance_status'] = 'Present';
                    $students[$key]['remark'] = '';
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
            
            $data['resultlist'] = $students;
            
            $this->load->view('layout/header', $data);
            $this->load->view('admin/transport/attendance', $data);
            $this->load->view('layout/footer', $data);
        }
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
                // Get unique routes
                $this->db->select('route_id')->from('vehicle_routes')->where('id', $r['vehroute_id']);
                $vr = $this->db->get()->row_array();
                $r_id = !empty($vr['route_id']) ? $vr['route_id'] : $r['vehroute_id'];
                
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
        $date = $this->customlib->dateFormatToYYYYMMDD($this->input->post('date'));
        $attendance_type = $this->input->post('attendance_type');

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
            // Check if already assigned to this bus
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
            $insert_data = [[
                'student_session_id' => $student_session['id'],
                'vehicle_id' => $vehicle_id,
                'date' => $date,
                'attendance_type' => $attendance_type,
                'status' => 'Switched Bus',
                'remark' => 'Added Manually'
            ]];
            $this->transportattendance_model->save_attendance($insert_data);
            echo json_encode(['status' => 1, 'msg' => 'Student successfully added to this bus for today.']);
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
        $date = $this->customlib->dateFormatToYYYYMMDD($this->input->post('date'));
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
        
        $search_date = $this->customlib->dateFormatToYYYYMMDD($date);
        
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
        $date = $this->customlib->dateFormatToYYYYMMDD($date_str);

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
}
