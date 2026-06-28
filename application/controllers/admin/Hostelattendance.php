<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Hostelattendance extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Customlib');
        $this->load->model('hostel_model');
        $this->load->model('hostelattendance_model');
        $this->load->model('attendencetype_model');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('hostel_attendance', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Hostel');
        $this->session->set_userdata('sub_menu', 'hostelattendance/index');

        $data['title'] = 'Hostel Attendance';
        $data['hostellist'] = $this->hostel_model->get();
        
        $attendencetypes = $this->attendencetype_model->get();
        $data['attendencetypeslist'] = $attendencetypes;

        $hostel_id = $this->input->post('hostel_id');
        $date = $this->input->post('date');
        $roll_call_type = $this->input->post('roll_call_type');
        if (empty($roll_call_type)) {
            $roll_call_type = 'morning';
        }
        $data['roll_call_type'] = $roll_call_type;

        if (isset($_POST['search'])) {
            $this->form_validation->set_rules('hostel_id', 'Hostel', 'required');
            $this->form_validation->set_rules('date', 'Date', 'required');

            if ($this->form_validation->run() == TRUE) {
                $date_format = $this->customlib->dateFormatToYYYYMMDD($date);
                $data['date'] = $date;
                $data['hostel_id'] = $hostel_id;
                
                $data['students'] = $this->hostelattendance_model->get_hostel_students($hostel_id);
                $data['attendance'] = $this->hostelattendance_model->get_attendance($hostel_id, $date_format, $roll_call_type);
            }
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/hostel/attendance', $data);
        $this->load->view('layout/footer', $data);
    }

    public function save()
    {
        if (!$this->rbac->hasPrivilege('hostel_attendance', 'can_add') && !$this->rbac->hasPrivilege('hostel_attendance', 'can_edit')) {
            access_denied();
        }

        $hostel_id = $this->input->post('hostel_id');
        $date = $this->input->post('date');
        $roll_call_type = $this->input->post('roll_call_type');
        if (empty($roll_call_type)) {
            $roll_call_type = 'morning';
        }
        $date_format = $this->customlib->dateFormatToYYYYMMDD($date);
        $student_sessions = $this->input->post('student_session');

        $attendance_data = [];
        if (!empty($student_sessions)) {
            foreach ($student_sessions as $student_session_id) {
                $attendance_type_id = $this->input->post('attendencetype' . $student_session_id);
                $remark = $this->input->post('remark' . $student_session_id);

                if (!empty($attendance_type_id)) {
                    $attendance_data[] = [
                        'hostel_id' => $hostel_id,
                        'student_session_id' => $student_session_id,
                        'date' => $date_format,
                        'roll_call_type' => $roll_call_type,
                        'attendence_type_id' => $attendance_type_id,
                        'remark' => $remark
                    ];
                }
            }
        }

        if (!empty($attendance_data)) {
            $this->hostelattendance_model->save($attendance_data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Attendance Saved Successfully</div>');
        }

        redirect('admin/hostelattendance');
    }
    public function monthly_report()
    {
        if (!$this->rbac->hasPrivilege('hostel_attendance', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Hostel');
        $this->session->set_userdata('sub_menu', 'hostelattendance/index');

        $data['title'] = 'Hostel Monthly Attendance';
        $data['hostellist'] = $this->hostel_model->get();
        
        $attendencetypes = $this->attendencetype_model->get();
        $data['attendencetypeslist'] = $attendencetypes;

        $hostel_id = $this->input->post('hostel_id');
        $month = $this->input->post('month');
        $year = $this->input->post('year');
        $roll_call_type = $this->input->post('roll_call_type');
        if (empty($roll_call_type)) {
            $roll_call_type = 'morning';
        }

        if (empty($month)) {
            $month = date('m');
        }
        if (empty($year)) {
            $year = date('Y');
        }

        $data['month'] = $month;
        $data['year'] = $year;
        $data['hostel_id'] = $hostel_id;
        $data['roll_call_type'] = $roll_call_type;
        
        if ($month != 'all') {
            $data['days_in_month'] = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        } else {
            $data['days_in_month'] = 0;
        }

        if (isset($_POST['search'])) {
            $this->form_validation->set_rules('hostel_id', 'Hostel', 'required');

            if ($this->form_validation->run() == TRUE) {
                $data['students'] = $this->hostelattendance_model->get_hostel_students($hostel_id);
                $data['attendance'] = $this->hostelattendance_model->get_monthly_attendance($hostel_id, $month, $year, $roll_call_type);
            }
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/hostel/monthly_attendance', $data);
        $this->load->view('layout/footer', $data);
    }
}
