<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Mydutypass extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Customlib');
        $this->load->model('dutypass_model');
        $this->load->model('setting_model');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('my_duty_pass', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/mydutypass');

        $staff_id = $this->customlib->getStaffID();

        $data['title']       = 'My Duty Pass';
        $data['duty_passes'] = $this->dutypass_model->getByStaff($staff_id);
        $data['sch_setting'] = $this->setting_model->getSetting();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/dutypass/mydutypass', $data);
        $this->load->view('layout/footer', $data);
    }

    public function apply()
    {
        if (!$this->rbac->hasPrivilege('my_duty_pass', 'can_add')) {
            access_denied();
        }

        $this->form_validation->set_rules('title', 'Duty Title / Event Name', 'required|trim');
        $this->form_validation->set_rules('category', 'Duty Category', 'required|trim');
        $this->form_validation->set_rules('venue', 'Venue / Destination', 'required|trim');
        $this->form_validation->set_rules('from_date', 'From Date', 'required|trim');
        $this->form_validation->set_rules('to_date', 'To Date', 'required|trim');

        if ($this->form_validation->run() == false) {
            $errors = array(
                'title'     => form_error('title'),
                'category'  => form_error('category'),
                'venue'     => form_error('venue'),
                'from_date' => form_error('from_date'),
                'to_date'   => form_error('to_date')
            );
            echo json_encode(array('status' => 'fail', 'error' => $errors, 'message' => ''));
            return;
        }

        $from_date = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('from_date')));
        $to_date   = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('to_date')));

        if (strtotime($to_date) < strtotime($from_date)) {
            echo json_encode(array(
                'status'  => 'fail',
                'error'   => array('to_date' => 'To Date cannot be earlier than From Date.'),
                'message' => ''
            ));
            return;
        }

        $staff_id     = $this->customlib->getStaffID();
        $duty_pass_no = $this->dutypass_model->generate_duty_pass_no();

        // Handle document attachment if any
        $document = null;
        if (isset($_FILES['document']) && !empty($_FILES['document']['name'])) {
            $upload_path = './uploads/staff_documents/duty_pass/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }
            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'pdf|jpg|jpeg|png|doc|docx';
            $config['file_name']     = 'DP_' . time() . '_' . rand(100, 999);
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('document')) {
                $upload_data = $this->upload->data();
                $document    = 'uploads/staff_documents/duty_pass/' . $upload_data['file_name'];
            }
        }

        $data = array(
            'duty_pass_no'      => $duty_pass_no,
            'title'             => $this->input->post('title'),
            'category'          => $this->input->post('category'),
            'venue'             => $this->input->post('venue'),
            'from_date'         => $from_date,
            'to_date'           => $to_date,
            'departure_time'    => $this->input->post('departure_time') ? $this->input->post('departure_time') : null,
            'return_time'       => $this->input->post('return_time') ? $this->input->post('return_time') : null,
            'description'       => $this->input->post('description'),
            'transport_details' => $this->input->post('transport_details'),
            'student_details'   => $this->input->post('student_details'),
            'document'          => $document,
            'status'            => 'Pending',
            'created_by'        => $staff_id
        );

        $insert_id = $this->dutypass_model->add($data, array($staff_id));

        // System notification to Superadmin / Admin (role_id: 7)
        $this->load->model('SystemNotification_model');
        if (class_exists('SystemNotification_model')) {
            $this->SystemNotification_model->notifyRole(
                7,
                'New Field Duty Pass Request',
                "Staff member has submitted an On-Duty Pass request: {$data['title']} ({$from_date} to {$to_date}). Pass No: {$duty_pass_no}",
                'admin/dutypass'
            );
        }

        echo json_encode(array(
            'status'  => 'success',
            'message' => $this->lang->line('success_message'),
            'id'      => $insert_id
        ));
    }
}
