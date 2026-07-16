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

    public function index()
    {
        if (!$this->rbac->hasPrivilege('transport_attendance', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Transport');
        $this->session->set_userdata('sub_menu', 'transportattendance/index');
        
        $data['title'] = 'Bus Attendance';
        $data['vehiclelist'] = $this->vehicle_model->get();
        
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
            $attendance_type = $this->input->post('attendance_type');
            
            $data['date'] = $date;
            $data['vehicle_id'] = $vehicle_id;
            $data['attendance_type'] = $attendance_type;
            
            $students = $this->transportattendance_model->get_bus_students($vehicle_id);
            $saved_attendance = $this->transportattendance_model->get_attendance($vehicle_id, $date, $attendance_type);
            $custom_riders = $this->transportattendance_model->get_custom_riders($vehicle_id, $date, $attendance_type);
            
            // Merge custom riders into the main list so they can be managed
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

    public function save()
    {
        if (!$this->rbac->hasPrivilege('transport_attendance', 'can_add')) {
            access_denied();
        }
        $date = $this->input->post('date');
        $vehicle_id = $this->input->post('vehicle_id');
        $attendance_type = $this->input->post('attendance_type');
        $student_session = $this->input->post('student_session');
        
        $insert_data = [];
        if (!empty($student_session)) {
            foreach ($student_session as $session_id) {
                $status = $this->input->post('attendencetype' . $session_id);
                $remark = $this->input->post('remark' . $session_id);
                
                // A hidden field indicates if this is a custom rider, to keep their status as 'Switched Bus' if they were added.
                // We'll trust the form submission for standard statuses.
                if ($status == 'Switched Bus' || $this->input->post('is_custom_rider' . $session_id) == 'yes') {
                    // Force their status to Switched Bus so we know they don't belong to this bus permanently
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
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Attendance saved successfully</div>');
        }
        
        redirect('admin/transportattendance');
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
        if (!$this->rbac->hasPrivilege('transport_attendance', 'can_view')) {
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
        $data['summary'] = $this->transportattendance_model->get_daily_summary($search_date);
        
        $this->load->view('layout/header');
        $this->load->view('admin/transport/daily_summary', $data);
        $this->load->view('layout/footer');
    }

    public function monthly_summary()
    {
        if (!$this->rbac->hasPrivilege('transport_attendance', 'can_view')) {
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
        $data['summary'] = $this->transportattendance_model->get_monthly_summary($month, $year);
        
        $this->load->view('layout/header');
        $this->load->view('admin/transport/monthly_summary', $data);
        $this->load->view('layout/footer');
    }
    
    public function get_summary_detail()
    {
        if (!$this->rbac->hasPrivilege('transport_attendance', 'can_view')) {
            echo json_encode(['status' => 0, 'msg' => 'Access Denied']);
            return;
        }

        $vehicle_id = $this->input->post('vehicle_id');
        $date_str = $this->input->post('date');
        $date = $this->customlib->dateFormatToYYYYMMDD($date_str);
        
        $details = $this->transportattendance_model->get_attendance_detail($vehicle_id, $date);
        
        $html = '<table class="table table-striped table-bordered table-hover">';
        $html .= '<thead><tr><th>Student</th><th>Class (Section)</th><th>Shift</th><th>Status</th><th>Remark</th></tr></thead><tbody>';
        
        if (!empty($details)) {
            foreach ($details as $row) {
                $status_label = $row['status'] == 'Switched Bus' ? '<span class="label label-info">Custom Rider</span>' : '<span class="label label-success">Present</span>';
                $name = $row['firstname'] . ' ' . $row['lastname'] . ' (' . $row['admission_no'] . ')';
                $class_sec = $row['class'] . ' (' . $row['section'] . ')';
                
                $html .= '<tr>';
                $html .= '<td>' . $name . '</td>';
                $html .= '<td>' . $class_sec . '</td>';
                $html .= '<td>' . $row['attendance_type'] . '</td>';
                $html .= '<td>' . $status_label . '</td>';
                $html .= '<td>' . $row['remark'] . '</td>';
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr><td colspan="5" class="text-center text-danger">No attendance marked for this date.</td></tr>';
        }
        $html .= '</tbody></table>';
        
        echo json_encode(['status' => 1, 'html' => $html]);
    }
    
    public function get_monthly_summary_detail()
    {
        if (!$this->rbac->hasPrivilege('transport_attendance', 'can_view')) {
            echo json_encode(['status' => 0, 'msg' => 'Access Denied']);
            return;
        }

        $vehicle_id = $this->input->post('vehicle_id');
        $month = $this->input->post('month');
        $year = $this->input->post('year');
        
        $details = $this->transportattendance_model->get_monthly_attendance_detail($vehicle_id, $month, $year);
        
        $html = '<table class="table table-striped table-bordered table-hover">';
        $html .= '<thead><tr><th>Date</th><th>Student</th><th>Class (Section)</th><th>Shift</th><th>Status</th><th>Remark</th></tr></thead><tbody>';
        
        if (!empty($details)) {
            foreach ($details as $row) {
                $status_label = $row['status'] == 'Switched Bus' ? '<span class="label label-info">Custom Rider</span>' : '<span class="label label-success">Present</span>';
                $name = $row['firstname'] . ' ' . $row['lastname'] . ' (' . $row['admission_no'] . ')';
                $class_sec = $row['class'] . ' (' . $row['section'] . ')';
                $formatted_date = date($this->customlib->getSchoolDateFormat(), strtotime($row['date']));
                
                $html .= '<tr>';
                $html .= '<td>' . $formatted_date . '</td>';
                $html .= '<td>' . $name . '</td>';
                $html .= '<td>' . $class_sec . '</td>';
                $html .= '<td>' . $row['attendance_type'] . '</td>';
                $html .= '<td>' . $status_label . '</td>';
                $html .= '<td>' . $row['remark'] . '</td>';
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr><td colspan="6" class="text-center text-danger">No attendance marked for this month.</td></tr>';
        }
        $html .= '</tbody></table>';
        
        echo json_encode(['status' => 1, 'html' => $html]);
    }
}
