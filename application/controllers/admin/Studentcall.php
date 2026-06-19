<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Studentcall extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array('studentcall_model', 'staff_model', 'class_model', 'notification_model'));
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('student_call_log', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'admin/studentcall');
        
        $data['class_list'] = $this->class_model->get();
        $data['purposes'] = $this->studentcall_model->get_purposes();
        $data['staff_list'] = $this->staff_model->get();

        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        $date_from = $this->input->post('date_from') ? date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date_from'))) : null;
        $date_to = $this->input->post('date_to') ? date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date_to'))) : null;
        $purpose_id = $this->input->post('purpose_id');
        $status = $this->input->post('status');

        $data['calls'] = $this->studentcall_model->get_calls($class_id, $section_id, $date_from, $date_to, $purpose_id, $status);

        $this->load->view('layout/header');
        $this->load->view('admin/studentcall/index', $data);
        $this->load->view('layout/footer');
    }

    public function search_student()
    {
        $keyword = $this->input->post('keyword');
        if (!empty($keyword)) {
            $students = $this->studentcall_model->search_student($keyword);
            echo json_encode($students);
        } else {
            echo json_encode([]);
        }
    }

    public function add_call()
    {
        if (!$this->rbac->hasPrivilege('student_call_log', 'can_add')) {
            access_denied();
        }

        $this->form_validation->set_rules('student_id', $this->lang->line('student'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('call_type', $this->lang->line('call_type'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('contact_person', $this->lang->line('contact_person'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('phone_number', $this->lang->line('phone'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'student_id'     => form_error('student_id'),
                'call_type'      => form_error('call_type'),
                'contact_person' => form_error('contact_person'),
                'phone_number'   => form_error('phone_number'),
                'date'           => form_error('date')
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $userdata = $this->customlib->getUserData();
            
            $data = array(
                'student_session_id' => $this->input->post('student_session_id'),
                'student_id'         => $this->input->post('student_id'),
                'call_type'          => $this->input->post('call_type'),
                'contact_person'     => $this->input->post('contact_person'),
                'phone_number'       => $this->input->post('phone_number'),
                'call_purpose_id'    => empty2null($this->input->post('purpose_id')),
                'call_status'        => $this->input->post('call_status'),
                'date'               => $this->customlib->dateFormatToYYYYMMDD($this->input->post('date')),
                'duration'           => $this->input->post('duration'),
                'notes'              => $this->input->post('notes'),
                'created_by'         => $userdata["id"]
            );
            $call_id = $this->studentcall_model->add_call($data);

            if ($this->input->post('follow_up_date')) {
                $followup = array(
                    'student_call_id' => $call_id,
                    'student_id'      => $this->input->post('student_id'),
                    'due_date'        => $this->customlib->dateFormatToYYYYMMDD($this->input->post('follow_up_date')),
                    'priority'        => $this->input->post('priority') ? $this->input->post('priority') : 'Medium',
                    'assigned_to'     => empty2null($this->input->post('assigned_to')),
                    'status'          => 'Pending',
                    'created_by'      => $userdata["id"]
                );
                $this->studentcall_model->add_followup($followup);
                
                // Add notification
                if (!empty($followup['assigned_to'])) {
                    $this->add_notification($followup['assigned_to'], "New Follow-up assigned to you for student ID: " . $followup['student_id']);
                }
            }

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function get_recent_history($student_id)
    {
        if (!$this->rbac->hasPrivilege('student_call_log', 'can_add')) {
            access_denied();
        }
        $history = $this->studentcall_model->get_recent_history($student_id, 3);
        $html = '';
        if (!empty($history)) {
            foreach ($history as $h) {
                $html .= '<div style="margin-bottom:10px; padding-bottom:10px; border-bottom:1px solid #eee;">';
                $html .= '<div style="font-size:12px; color:#666;">' . date($this->customlib->getSchoolDateFormat(), strtotime($h['date'])) . ' - <strong>' . $h['call_status'] . '</strong></div>';
                if(!empty($h['purpose_name'])) $html .= '<div style="font-size:12px; color:#333;"><strong>Purpose:</strong> ' . $h['purpose_name'] . '</div>';
                if(!empty($h['notes'])) $html .= '<div style="font-size:11px; color:#888; font-style:italic;">' . $h['notes'] . '</div>';
                $html .= '</div>';
            }
        } else {
            $html = '<div style="font-size:12px; color:#888;">No recent calls.</div>';
        }
        echo json_encode(['status' => 'success', 'html' => $html]);
    }

    public function follow_up($call_id)
    {
        if (!$this->rbac->hasPrivilege('student_call_log', 'can_view')) {
            access_denied();
        }
        $data['call'] = $this->studentcall_model->get_call($call_id);
        $data['followups'] = $this->studentcall_model->get_followups_by_call($call_id);
        $data['staff_list'] = $this->staff_model->get();
        $this->load->view('admin/studentcall/follow_up_modal', $data);
    }

    public function add_follow_up_task()
    {
        if (!$this->rbac->hasPrivilege('student_call_log', 'can_add')) {
            access_denied();
        }

        $this->form_validation->set_rules('due_date', $this->lang->line('date'), 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $msg = array('due_date' => form_error('due_date'));
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $userdata = $this->customlib->getUserData();
            $data = array(
                'student_call_id' => $this->input->post('student_call_id'),
                'student_id'      => $this->input->post('student_id'),
                'due_date'        => $this->customlib->dateFormatToYYYYMMDD($this->input->post('due_date')),
                'priority'        => $this->input->post('priority'),
                'assigned_to'     => empty2null($this->input->post('assigned_to')),
                'status'          => 'Pending',
                'created_by'      => $userdata["id"]
            );
            $this->studentcall_model->add_followup($data);
            
            if (!empty($data['assigned_to'])) {
                $this->add_notification($data['assigned_to'], "New Follow-up assigned to you for student ID: " . $data['student_id']);
            }
            
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function edit_follow_up_task()
    {
        if (!$this->rbac->hasPrivilege('student_call_log', 'can_edit')) {
            access_denied();
        }
        
        $userdata = $this->customlib->getUserData();
        $id = $this->input->post('followup_id');
        
        $data = array(
            'status'      => $this->input->post('status'),
            'call_status' => $this->input->post('call_status'),
            'remarks'     => $this->input->post('remarks')
        );
        $this->studentcall_model->update_followup($id, $data);

        // Check if next follow up date is set
        if ($this->input->post('next_follow_up_date')) {
            $followup_db = $this->studentcall_model->get_followup($id);
            $next = array(
                'student_call_id' => $followup_db['student_call_id'],
                'student_id'      => $followup_db['student_id'],
                'due_date'        => $this->customlib->dateFormatToYYYYMMDD($this->input->post('next_follow_up_date')),
                'priority'        => $this->input->post('next_priority') ? $this->input->post('next_priority') : $followup_db['priority'],
                'assigned_to'     => $this->input->post('next_assigned_to') ? $this->input->post('next_assigned_to') : $followup_db['assigned_to'],
                'status'          => 'Pending',
                'created_by'      => $userdata["id"]
            );
            $this->studentcall_model->add_followup($next);
            
            if (!empty($next['assigned_to']) && $next['assigned_to'] != $userdata['id']) {
                $this->add_notification($next['assigned_to'], "New Follow-up reassigned to you for student ID: " . $next['student_id']);
            }
        }

        $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('update_message'));
        echo json_encode($array);
    }

    private function add_notification($staff_id, $message)
    {
        $staff = $this->staff_model->get($staff_id);
        if(!empty($staff)){
            $notification = array(
                'title' => 'Follow-up Task',
                'message' => $message,
                'publish_date' => date('Y-m-d'),
                'date' => date('Y-m-d'),
                'visible_student' => 'No',
                'visible_staff' => 'Yes',
                'visible_parent' => 'No',
                'created_id' => $this->customlib->getUserData()["id"],
            );
            $staff_roles = array(array('role_id' => $staff['role_id']));
            $this->notification_model->insertBatch($notification, $staff_roles);
        }
    }
}
