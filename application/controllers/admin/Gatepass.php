<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Gatepass extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('Customlib');
        $this->load->model('gatepass_model');
        $this->load->model('student_model');
        $this->load->model('staff_model');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('front_office_gate_pass', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'front_office');
        $this->session->set_userdata('sub_menu', 'gate_pass');

        $data['title'] = 'Gate Pass';
        $data['gate_passes'] = $this->gatepass_model->get();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/gatepass/index', $data);
        $this->load->view('layout/footer', $data);
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('front_office_gate_pass', 'can_add')) {
            access_denied();
        }

        $user_type = $this->input->post('user_type');
        $user_id = $this->input->post('user_id');
        $date = $this->input->post('date');
        $out_time = $this->input->post('out_time');
        $reason = $this->input->post('reason');

        $this->form_validation->set_rules('user_type', 'User Type', 'required');
        $this->form_validation->set_rules('user_id', 'User', 'required');
        $this->form_validation->set_rules('date', 'Date', 'required');
        $this->form_validation->set_rules('out_time', 'Out Time', 'required');
        
        $pass_type = $this->input->post('pass_type');
        if ($pass_type != 'Full Day') {
            $this->form_validation->set_rules('in_time', 'In Time (Expected)', 'required');
        }

        if ($this->form_validation->run() == FALSE) {
            $msg = array(
                'user_type' => form_error('user_type'),
                'user_id' => form_error('user_id'),
                'date' => form_error('date'),
                'out_time' => form_error('out_time'),
                'in_time' => form_error('in_time')
            );
            echo json_encode(array('status' => 'fail', 'error' => $msg, 'message' => ''));
        } else {
            $gate_pass_no = $this->gatepass_model->generate_gate_pass_no();

            if ($pass_type == 'Full Day') {
                $in_time = null;
            } else {
                $in_time = $this->input->post('in_time');
            }

            $data = array(
                'gate_pass_no' => $gate_pass_no,
                'user_type' => $user_type,
                'user_id' => $user_id,
                'date' => date('Y-m-d', $this->customlib->datetostrtotime($date)),
                'out_time' => $out_time,
                'in_time' => $in_time,
                'reason' => $reason,
                'status' => $this->input->post('status') ? $this->input->post('status') : 'Approved', // Auto-approve if created by Admin
                'approved_by' => $this->customlib->getStaffID()
            );

            $this->gatepass_model->add($data);

            echo json_encode(array('status' => 'success', 'message' => $this->lang->line('success_message')));
        }
    }

    public function search_user()
    {
        $user_type = $this->input->get('user_type');
        $keyword = $this->input->get('keyword');

        $result = array();
        if ($user_type == 'student') {
            $current_session = $this->setting_model->getCurrentSession();
            $this->db->select('students.id, students.firstname, students.lastname, students.admission_no, classes.class, sections.section');
            $this->db->join('student_session', 'student_session.student_id = students.id');
            $this->db->join('classes', 'classes.id = student_session.class_id');
            $this->db->join('sections', 'sections.id = student_session.section_id');
            $this->db->where('student_session.session_id', $current_session);
            $this->db->where('students.is_active', 'yes');
            if(!empty($keyword)){
                $this->db->group_start();
                $this->db->like('students.firstname', $keyword);
                $this->db->or_like('students.lastname', $keyword);
                $this->db->or_like('students.admission_no', $keyword);
                $this->db->group_end();
            }
            $this->db->limit(20);
            $query = $this->db->get('students');
            $students = $query->result_array();
            foreach ($students as $student) {
                $result[] = array(
                    'id' => $student['id'],
                    'text' => $student['firstname'] . ' ' . $student['lastname'] . ' (' . $student['admission_no'] . ') - ' . $student['class'] . ' (' . $student['section'] . ')'
                );
            }
        } elseif ($user_type == 'staff') {
            $this->db->select('staff.id, staff.name, staff.surname, staff.employee_id, roles.name as role_name');
            $this->db->join('staff_roles', 'staff_roles.staff_id = staff.id');
            $this->db->join('roles', 'staff_roles.role_id = roles.id');
            $this->db->where('staff.is_active', 1);
            if(!empty($keyword)){
                $this->db->group_start();
                $this->db->like('staff.name', $keyword);
                $this->db->or_like('staff.surname', $keyword);
                $this->db->or_like('staff.employee_id', $keyword);
                $this->db->group_end();
            }
            $this->db->limit(20);
            $query = $this->db->get('staff');
            $staffs = $query->result_array();
            foreach ($staffs as $st) {
                $result[] = array(
                    'id' => $st['id'],
                    'text' => $st['name'] . ' ' . $st['surname'] . ' (' . $st['employee_id'] . ') - ' . $st['role_name']
                );
            }
        }

        echo json_encode($result);
    }

    public function update_status()
    {
        if (!$this->rbac->hasPrivilege('front_office_gate_pass', 'can_edit')) {
            access_denied();
        }

        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $in_time = $this->input->post('in_time');

        $data = array(
            'id' => $id,
            'status' => $status,
            'approved_by' => $this->customlib->getStaffID()
        );

        if ($status == 'Completed' && !empty($in_time)) {
            $data['actual_in_time'] = $in_time;
        }

        $this->gatepass_model->add($data);

        // Notify Class Teacher if student
        $gatepass = $this->gatepass_model->get($id);
        if ($gatepass['user_type'] == 'student' && $status == 'Approved') {
            $this->load->model('classteacher_model');
            $this->load->model('notification_model');
            
            // Get student session and class
            $this->db->select('student_session.class_id, student_session.section_id');
            $this->db->from('student_session');
            $this->db->where('student_id', $gatepass['user_id']);
            $this->db->where('session_id', $this->setting_model->getCurrentSession());
            $student_session = $this->db->get()->row_array();
            
            if ($student_session) {
                $class_teachers = $this->classteacher_model->class_teacher($student_session['class_id'], $student_session['section_id']);
                if (!empty($class_teachers)) {
                    // Send notification to class teachers
                    $notification_data = array(
                        'title' => 'Gate Pass Approved',
                        'message' => 'Gate Pass ' . $gatepass['gate_pass_no'] . ' has been approved for a student in your class.',
                        'date' => date('Y-m-d'),
                        'publish_date' => date('Y-m-d'),
                        'created_id' => $this->customlib->getStaffID(),
                        'visible_student' => 'No',
                        'visible_staff' => 'Yes',
                        'visible_parent' => 'No'
                    );
                    
                    // In a real scenario, we might want to restrict this to just the class teacher role or specific staff.
                    // For now, we broadcast to staff role and rely on staff dashboard to filter if needed,
                    // or ideally just send a direct email via mailsmsconf.
                    $staff_roles = array();
                    foreach ($class_teachers as $teacher) {
                        // Assuming role_id is needed, standard staff role is usually 2 or fetched dynamically.
                        // However, we just insert into send_notification
                    }
                    $this->notification_model->insertBatch($notification_data, array(array('role_id' => 2))); // Broadcasting to Staff role as fallback
                }
            }
        }

        echo json_encode(array('status' => 'success', 'message' => $this->lang->line('update_message')));
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('front_office_gate_pass', 'can_delete')) {
            access_denied();
        }
        $this->gatepass_model->delete($id);
        redirect('admin/gatepass');
    }

    public function print_gatepass($id)
    {
        if (!$this->rbac->hasPrivilege('front_office_gate_pass', 'can_view')) {
            access_denied();
        }
        
        $data['gatepass'] = $this->gatepass_model->get($id);
        $data['sch_setting'] = $this->setting_model->getSetting();
        $this->load->view('admin/gatepass/_print_gatepass', $data);
    }
}
