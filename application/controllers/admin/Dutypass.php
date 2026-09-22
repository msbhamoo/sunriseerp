<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Dutypass extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Customlib');
        $this->load->library('media_storage');
        $this->load->model('dutypass_model');
        $this->load->model('staff_model');
        $this->load->model('setting_model');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('staff_duty_pass', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/dutypass');

        $data['title']       = 'Staff Duty Pass';
        $data['duty_passes'] = $this->dutypass_model->get();
        $data['sch_setting'] = $this->setting_model->getSetting();
        $data['staff_list']  = $this->staff_model->get();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/dutypass/index', $data);
        $this->load->view('layout/footer', $data);
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('staff_duty_pass', 'can_add')) {
            access_denied();
        }

        $this->form_validation->set_rules('title', 'Duty Title / Event Name', 'required|trim');
        $this->form_validation->set_rules('category', 'Duty Category', 'required|trim');
        $this->form_validation->set_rules('venue', 'Venue / Destination', 'required|trim');
        $this->form_validation->set_rules('from_date', 'From Date', 'required|trim');
        $this->form_validation->set_rules('to_date', 'To Date', 'required|trim');
        
        $staff_ids = $this->input->post('staff_ids');
        if (empty($staff_ids)) {
            $this->form_validation->set_rules('staff_ids[]', 'Staff Member(s)', 'required');
        }

        if ($this->form_validation->run() == false) {
            $errors = array(
                'title'       => form_error('title'),
                'category'    => form_error('category'),
                'venue'       => form_error('venue'),
                'from_date'   => form_error('from_date'),
                'to_date'     => form_error('to_date'),
                'staff_ids'   => empty($staff_ids) ? 'Please select at least one staff member.' : ''
            );
            echo json_encode(array('status' => 'fail', 'error' => $errors, 'message' => ''));
            return;
        }

        $from_date = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('from_date')));
        $to_date   = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('to_date')));

        if (strtotime($to_date) < strtotime($from_date)) {
            echo json_encode(array(
                'status' => 'fail',
                'error'  => array('to_date' => 'To Date cannot be earlier than From Date.'),
                'message' => ''
            ));
            return;
        }

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

        $status = $this->input->post('status') ? $this->input->post('status') : 'Approved';

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
            'status'            => $status,
            'approved_by'       => ($status == 'Approved') ? $this->customlib->getStaffID() : null,
            'created_by'        => $this->customlib->getStaffID()
        );

        $insert_id = $this->dutypass_model->add($data, $staff_ids);

        // System notification to assigned staff
        $this->load->model('SystemNotification_model');
        if (class_exists('SystemNotification_model')) {
            foreach ($staff_ids as $st_id) {
                if (!empty($st_id)) {
                    $this->SystemNotification_model->notifyUser(
                        $st_id,
                        'Field Duty Pass Issued',
                        "You have been assigned to Field Duty: {$data['title']} at {$data['venue']} ({$from_date} to {$to_date}). Pass No: {$duty_pass_no}",
                        'admin/mydutypass'
                    );
                }
            }
        }

        echo json_encode(array(
            'status'  => 'success',
            'message' => $this->lang->line('success_message'),
            'id'      => $insert_id
        ));
    }

    public function get_details($id)
    {
        if (!$this->rbac->hasPrivilege('staff_duty_pass', 'can_view')) {
            access_denied();
        }

        $duty_pass = $this->dutypass_model->get($id);
        if ($duty_pass) {
            $duty_pass['from_date_formatted'] = date($this->customlib->getSchoolDateFormat(), strtotime($duty_pass['from_date']));
            $duty_pass['to_date_formatted']   = date($this->customlib->getSchoolDateFormat(), strtotime($duty_pass['to_date']));
            echo json_encode(array('status' => 'success', 'data' => $duty_pass));
        } else {
            echo json_encode(array('status' => 'fail', 'message' => 'Duty Pass not found.'));
        }
    }

    public function update()
    {
        if (!$this->rbac->hasPrivilege('staff_duty_pass', 'can_edit')) {
            access_denied();
        }

        $id = $this->input->post('id');
        $this->form_validation->set_rules('title', 'Duty Title / Event Name', 'required|trim');
        $this->form_validation->set_rules('category', 'Duty Category', 'required|trim');
        $this->form_validation->set_rules('venue', 'Venue / Destination', 'required|trim');
        $this->form_validation->set_rules('from_date', 'From Date', 'required|trim');
        $this->form_validation->set_rules('to_date', 'To Date', 'required|trim');

        $staff_ids = $this->input->post('staff_ids');
        if (empty($staff_ids)) {
            $this->form_validation->set_rules('staff_ids[]', 'Staff Member(s)', 'required');
        }

        if ($this->form_validation->run() == false) {
            $errors = array(
                'title'       => form_error('title'),
                'category'    => form_error('category'),
                'venue'       => form_error('venue'),
                'from_date'   => form_error('from_date'),
                'to_date'     => form_error('to_date'),
                'staff_ids'   => empty($staff_ids) ? 'Please select at least one staff member.' : ''
            );
            echo json_encode(array('status' => 'fail', 'error' => $errors, 'message' => ''));
            return;
        }

        $from_date = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('from_date')));
        $to_date   = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('to_date')));

        $status = $this->input->post('status') ? $this->input->post('status') : 'Approved';

        $data = array(
            'id'                => $id,
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
            'status'            => $status,
            'approved_by'       => ($status == 'Approved') ? $this->customlib->getStaffID() : null
        );

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
                $upload_data      = $this->upload->data();
                $data['document'] = 'uploads/staff_documents/duty_pass/' . $upload_data['file_name'];
            }
        }

        $this->dutypass_model->add($data, $staff_ids);

        echo json_encode(array(
            'status'  => 'success',
            'message' => $this->lang->line('update_message')
        ));
    }

    public function update_status()
    {
        if (!$this->rbac->hasPrivilege('staff_duty_pass', 'can_edit')) {
            access_denied();
        }

        $id     = $this->input->post('id');
        $status = $this->input->post('status');

        $data = array(
            'id'          => $id,
            'status'      => $status,
            'approved_by' => $this->customlib->getStaffID()
        );

        $this->dutypass_model->add($data);

        echo json_encode(array(
            'status'  => 'success',
            'message' => $this->lang->line('update_message')
        ));
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('staff_duty_pass', 'can_delete')) {
            access_denied();
        }

        $this->dutypass_model->delete($id);
        echo json_encode(array('status' => 'success', 'message' => $this->lang->line('delete_message')));
    }

    public function print_dutypass($id)
    {
        if (!$this->rbac->hasPrivilege('staff_duty_pass', 'can_view') && !$this->rbac->hasPrivilege('my_duty_pass', 'can_view')) {
            access_denied();
        }

        $data['duty_pass']   = $this->dutypass_model->get($id);
        $data['sch_setting'] = $this->setting_model->getSetting();

        if (empty($data['duty_pass'])) {
            show_404();
        }

        $this->load->view('admin/dutypass/_print_dutypass', $data);
    }

    public function search_staff()
    {
        $keyword = $this->input->post('keyword') ? $this->input->post('keyword') : $this->input->get('keyword');

        $this->db->select('staff.id, staff.name, staff.surname, staff.employee_id, roles.name as role_name, staff_designation.designation');
        $this->db->from('staff');
        $this->db->join('staff_roles', 'staff_roles.staff_id = staff.id', 'left');
        $this->db->join('roles', 'staff_roles.role_id = roles.id', 'left');
        $this->db->join('staff_designation', 'staff_designation.id = staff.designation', 'left');
        $this->db->where('staff.is_active', 1);

        if (!empty($keyword)) {
            $this->db->group_start();
            $this->db->like('staff.name', $keyword);
            $this->db->or_like('staff.surname', $keyword);
            $this->db->or_like('staff.employee_id', $keyword);
            $this->db->or_like("CONCAT(staff.name, ' ', staff.surname)", $keyword);
            $this->db->group_end();
        }

        $this->db->group_by('staff.id');
        $this->db->limit(30);
        $staffs = $this->db->get()->result_array();

        $result = array();
        foreach ($staffs as $st) {
            $role_str = !empty($st['role_name']) ? ' (' . $st['role_name'] . ')' : '';
            $desig_str = !empty($st['designation']) ? ' - ' . $st['designation'] : '';
            $result[] = array(
                'id'   => $st['id'],
                'text' => $st['name'] . ' ' . $st['surname'] . ' [' . $st['employee_id'] . ']' . $role_str . $desig_str
            );
        }

        echo json_encode($result);
    }
}
