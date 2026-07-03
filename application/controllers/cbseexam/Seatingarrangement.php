<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Seatingarrangement extends MY_Addon_CBSEController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('cbseexam/cbse_seating_model', 'cbseexam/cbse_seating_room_model', 'cbseexam/cbseexam_exam_model', 'cbseexam/cbse_seating_invigilator_model', 'staff_model'));
        $this->auth->addonchk('sscbse', site_url('cbseexam/setting/index'));
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('cbse_exam_seating', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'cbse_exam');
        $this->session->set_userdata('sub_menu', 'cbseexam/seatingarrangement');
        
        $data['title'] = 'Seating Allocations';
        $data['allocations'] = $this->cbse_seating_model->get_allocations();
        
        $this->load->view('layout/header', $data);
        $this->load->view('cbseexam/seating/index', $data);
        $this->load->view('layout/footer', $data);
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('cbse_exam_seating', 'can_add')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'cbse_exam');
        $this->session->set_userdata('sub_menu', 'cbseexam/seatingarrangement');
        
        $data['title'] = 'New Allocation';
        $data['exams'] = $this->cbseexam_exam_model->getexamlist();
        $data['rooms'] = $this->cbse_seating_room_model->get_rooms();
        
        $this->form_validation->set_rules('exam_id', 'Exam', 'required');
        $this->form_validation->set_rules('exam_date', 'Exam Date', 'required');
        $this->form_validation->set_rules('rooms[]', 'Rooms', 'required');
        
        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('cbseexam/seating/create', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $exam_id = $this->input->post('exam_id');
            $exam_date = $this->customlib->dateFormatToYYYYMMDD($this->input->post('exam_date'));
            $strategy = $this->input->post('allocation_strategy');
            $seat_format = $this->input->post('seat_number_format');
            $rooms = $this->input->post('rooms');
            
            $res = $this->cbse_seating_model->autoAllocateStudents($exam_id, $exam_date, $strategy, $seat_format, $rooms);
            
            if (is_array($res) && !$res['status']) {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">'.$res['msg'].'</div>');
                redirect('cbseexam/seatingarrangement/create');
            } else if ($res) {
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Allocation generated successfully</div>');
                redirect('cbseexam/seatingarrangement');
            } else {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Failed to generate allocation. Please ensure students are assigned to the exam and selected rooms have sufficient capacity.</div>');
                redirect('cbseexam/seatingarrangement/create');
            }
        }
    }
    
    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('cbse_exam_seating', 'can_delete')) {
            access_denied();
        }
        $this->cbse_seating_model->delete_allocation($id);
        redirect('cbseexam/seatingarrangement');
    }

    public function change_status($id, $status)
    {
        if (!$this->rbac->hasPrivilege('cbse_exam_seating', 'can_edit')) {
            access_denied();
        }
        $this->cbse_seating_model->update_allocation_status($id, $status);
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Status updated to ' . $status . ' successfully</div>');
        redirect('cbseexam/seatingarrangement');
    }

    public function assign_invigilators($allocation_id)
    {
        if (!$this->rbac->hasPrivilege('cbse_exam_seating', 'can_edit')) {
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'cbse_exam');
        $this->session->set_userdata('sub_menu', 'cbseexam/seatingarrangement');
        
        $allocation = $this->cbse_seating_model->get_allocation_by_id($allocation_id);
        if (empty($allocation)) {
            redirect('cbseexam/seatingarrangement');
        }
        
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            $staff_assignments = $this->input->post('staff_id');
            $insert_data = [];
            
            if (!empty($staff_assignments)) {
                foreach ($staff_assignments as $room_assign_id => $staff_ids) {
                    foreach ($staff_ids as $s_id) {
                        if (!empty($s_id)) {
                            $insert_data[] = [
                                'allocation_id' => $allocation_id,
                                'room_assignment_id' => $room_assign_id,
                                'staff_id' => $s_id,
                                'role' => 'invigilator'
                            ];
                        }
                    }
                }
            }
            
            $this->cbse_seating_invigilator_model->assign_invigilators($allocation_id, $insert_data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Invigilators assigned successfully</div>');
            redirect('cbseexam/seatingarrangement/assign_invigilators/'.$allocation_id);
        }

        $data['title'] = 'Assign Invigilators';
        $data['allocation'] = $allocation;
        $data['rooms'] = $this->cbse_seating_invigilator_model->get_allocation_rooms($allocation_id);
        $data['staffs'] = $this->staff_model->get(); // All active staff
        
        $this->load->view('layout/header', $data);
        $this->load->view('cbseexam/seating/assign_invigilators', $data);
        $this->load->view('layout/footer', $data);
    }
}
