<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Seatingreport extends MY_Addon_CBSEController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('cbseexam/cbse_seating_model', 'cbseexam/cbse_seating_room_model', 'cbseexam/cbse_seating_invigilator_model'));
        $this->auth->addonchk('sscbse', site_url('cbseexam/setting/index'));
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('cbse_exam_seating_reports', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'cbse_exam');
        $this->session->set_userdata('sub_menu', 'cbseexam/seatingreport');
        
        $data['title'] = 'Seating Reports';
        $data['allocations'] = $this->cbse_seating_model->get_allocations();

        $this->load->view('layout/header', $data);
        $this->load->view('cbseexam/seatingreport/index', $data);
        $this->load->view('layout/footer', $data);
    }
    
    // Private helper to get core data for reports
    private function _get_report_data($allocation_id)
    {
        $data['allocation'] = $this->cbse_seating_model->get_allocation_by_id($allocation_id);
        
        if (empty($data['allocation'])) {
            redirect('cbseexam/seatingreport');
        }
        
        $data['rooms'] = $this->cbse_seating_invigilator_model->get_allocation_rooms($allocation_id);
        
        // Fetch students for each room
        foreach ($data['rooms'] as $k => $room) {
            $data['rooms'][$k]['students'] = $this->db->select('cbse_seating_student_seats.*, students.firstname, students.lastname, students.admission_no, classes.class as class_name, sections.section as section_name, cbse_exam_students.roll_no')
                ->from('cbse_seating_student_seats')
                ->join('student_session', 'student_session.id = cbse_seating_student_seats.student_session_id')
                ->join('students', 'students.id = student_session.student_id')
                ->join('classes', 'classes.id = student_session.class_id')
                ->join('sections', 'sections.id = student_session.section_id')
                ->join('cbse_exam_students', 'cbse_exam_students.student_session_id = student_session.id AND cbse_exam_students.cbse_exam_id = ' . $data['allocation']['cbse_exam_id'])
                ->where('cbse_seating_student_seats.room_assignment_id', $room['id'])
                ->order_by('cbse_seating_student_seats.seat_number', 'ASC')
                ->get()->result_array();
        }
        
        return $data;
    }

    public function roomwise($allocation_id)
    {
        if (!$this->rbac->hasPrivilege('cbse_exam_seating_reports', 'can_view')) {
            access_denied();
        }
        $data = $this->_get_report_data($allocation_id);
        $this->load->view('layout/header', $data);
        $this->load->view('cbseexam/seatingreport/roomwise', $data);
        $this->load->view('layout/footer', $data);
    }

    public function studentwise($allocation_id)
    {
        if (!$this->rbac->hasPrivilege('cbse_exam_seating_reports', 'can_view')) {
            access_denied();
        }
        $data = $this->_get_report_data($allocation_id);
        $this->load->view('layout/header', $data);
        $this->load->view('cbseexam/seatingreport/studentwise', $data);
        $this->load->view('layout/footer', $data);
    }

    public function invigilator_duty($allocation_id)
    {
        if (!$this->rbac->hasPrivilege('cbse_exam_seating_reports', 'can_view')) {
            access_denied();
        }
        $data = $this->_get_report_data($allocation_id);
        $this->load->view('layout/header', $data);
        $this->load->view('cbseexam/seatingreport/invigilator_duty', $data);
        $this->load->view('layout/footer', $data);
    }
    
    public function attendance_sheet($allocation_id)
    {
        if (!$this->rbac->hasPrivilege('cbse_exam_seating_reports', 'can_view')) {
            access_denied();
        }
        $data = $this->_get_report_data($allocation_id);
        $this->load->view('layout/header', $data);
        $this->load->view('cbseexam/seatingreport/attendance_sheet', $data);
        $this->load->view('layout/footer', $data);
    }
    
    public function summary_report($allocation_id)
    {
        if (!$this->rbac->hasPrivilege('cbse_exam_seating_reports', 'can_view')) {
            access_denied();
        }
        $data = $this->_get_report_data($allocation_id);
        
        // Compute summary manually
        $summary = [];
        foreach ($data['rooms'] as $room) {
            foreach ($room['students'] as $student) {
                $class_key = $student['class_name'] . ' (' . $student['section_name'] . ')';
                if (!isset($summary[$class_key])) {
                    $summary[$class_key] = [
                        'count' => 0,
                        'rooms' => []
                    ];
                }
                $summary[$class_key]['count']++;
                if (!in_array($room['room_number'], $summary[$class_key]['rooms'])) {
                    $summary[$class_key]['rooms'][] = $room['room_number'];
                }
            }
        }
        $data['summary'] = $summary;
        
        $this->load->view('layout/header', $data);
        $this->load->view('cbseexam/seatingreport/summary_report', $data);
        $this->load->view('layout/footer', $data);
    }
    
    public function ajax_swap_seats()
    {
        if (!$this->rbac->hasPrivilege('cbse_exam_seating_reports', 'can_view')) {
            echo json_encode(array('status' => 0, 'msg' => 'Access denied'));
            return;
        }
        
        $seat1_id = $this->input->post('seat1_id');
        $seat2_id = $this->input->post('seat2_id');
        
        if ($this->cbse_seating_model->swap_students($seat1_id, $seat2_id)) {
            echo json_encode(array('status' => 1, 'msg' => 'Seats swapped successfully'));
        } else {
            echo json_encode(array('status' => 0, 'msg' => 'Failed to swap seats'));
        }
    }
    
    public function ajax_move_students()
    {
        if (!$this->rbac->hasPrivilege('cbse_exam_seating_reports', 'can_view')) {
            echo json_encode(array('status' => 0, 'msg' => 'Access denied'));
            return;
        }
        
        $seat_ids = $this->input->post('seat_ids');
        $target_room_assignment_id = $this->input->post('target_room_assignment_id');
        
        if (!empty($seat_ids) && $target_room_assignment_id) {
            if ($this->cbse_seating_model->move_students($seat_ids, $target_room_assignment_id)) {
                echo json_encode(array('status' => 1, 'msg' => 'Students moved successfully'));
            } else {
                echo json_encode(array('status' => 0, 'msg' => 'Failed to move students or room full'));
            }
        } else {
            echo json_encode(array('status' => 0, 'msg' => 'Invalid data'));
        }
    }
}
