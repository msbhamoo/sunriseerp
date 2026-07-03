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

        // Fetch widget stats
        $staff_id = $this->customlib->getUserData()["id"];
        $today_stats = $this->studentcall_model->get_today_call_statistics();
        $pending_followups = count($this->studentcall_model->get_pending_followups_by_staff($staff_id));
        
        $total_calls_today = 0;
        $connected_today = 0;
        $not_connected_today = 0;
        
        foreach($today_stats as $stat) {
            $total_calls_today += $stat['count'];
            if ($stat['call_status'] == 'Connected') {
                $connected_today += $stat['count'];
            } elseif ($stat['call_status'] != 'Callback Requested') {
                $not_connected_today += $stat['count'];
            }
        }
        
        $data['total_calls_today'] = $total_calls_today;
        $data['connected_today'] = $connected_today;
        $data['not_connected_today'] = $not_connected_today;
        $data['pending_followups'] = $pending_followups;

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

    public function search_ajax()
    {
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        $date_from = $this->input->post('date_from') ? date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date_from'))) : null;
        $date_to = $this->input->post('date_to') ? date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date_to'))) : null;
        $purpose_id = $this->input->post('purpose_id');
        $status = $this->input->post('status');
        
        $follow_up_date_from = $this->input->post('follow_up_date_from') ? date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('follow_up_date_from'))) : null;
        $follow_up_date_to = $this->input->post('follow_up_date_to') ? date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('follow_up_date_to'))) : null;
        $assigned_to = $this->input->post('assigned_to');

        $calls = $this->studentcall_model->get_calls($class_id, $section_id, $date_from, $date_to, $purpose_id, $status, $follow_up_date_from, $follow_up_date_to, $assigned_to);

        $data = [];
        if (!empty($calls)) {
            foreach ($calls as $call) {
                $followup_status = '';
                if ($call['total_followups'] == 0) {
                    $followup_status = "<span class='label label-default'>None</span>";
                } else if ($call['pending_count'] > 0) {
                    $followup_status = "<span class='label label-warning'>Pending (" . date($this->customlib->getSchoolDateFormat(), strtotime($call['next_follow_up_date'])) . ")</span>";
                } else {
                    $followup_status = "<span class='label label-success'>Resolved</span>";
                }

                $action = '';
                if ($this->rbac->hasPrivilege('student_call_log', 'can_view')) {
                    $action = '<a href="#" class="btn btn-default btn-xs" onclick="follow_up(' . $call['id'] . ')" data-toggle="tooltip" title="' . ($this->lang->line('follow_up') ? $this->lang->line('follow_up') : 'Follow Up') . '"><i class="fa fa-phone"></i></a>';
                }

                $row = [
                    $call['firstname'] . ' ' . $call['lastname'] . ' (' . $call['admission_no'] . ')',
                    $call['class'] . ' (' . $call['section'] . ')',
                    $call['phone_number'],
                    $call['purpose_name'],
                    $call['call_status'],
                    date($this->customlib->getSchoolDateFormat(true, true), strtotime($call['date'])),
                    $followup_status,
                    $call['assigned_to_name'],
                    $call['staff_name'] . ' ' . $call['staff_surname'],
                    '<div class="pull-right">' . $action . '</div>'
                ];
                $data[] = $row;
            }
        }

        echo json_encode(['status' => 'success', 'data' => $data]);
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

            $copy_to_siblings = $this->input->post('copy_to_siblings');
            if (!empty($copy_to_siblings)) {
                foreach ($copy_to_siblings as $sib_str) {
                    $sib_parts = explode('|', $sib_str);
                    if (count($sib_parts) == 2) {
                        $sib_data = $data;
                        $sib_data['student_session_id'] = $sib_parts[0];
                        $sib_data['student_id'] = $sib_parts[1];
                        
                        $sib_call_id = $this->studentcall_model->add_call($sib_data);
                        
                        if ($this->input->post('follow_up_date')) {
                            $sib_followup = $followup;
                            $sib_followup['student_call_id'] = $sib_call_id;
                            $sib_followup['student_id'] = $sib_parts[1];
                            $this->studentcall_model->add_followup($sib_followup);
                            
                            if (!empty($sib_followup['assigned_to'])) {
                                $this->add_notification($sib_followup['assigned_to'], "New Follow-up assigned to you for student ID: " . $sib_followup['student_id']);
                            }
                        }
                    }
                }
            }

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function get_siblings($student_id)
    {
        if (!$this->rbac->hasPrivilege('student_call_log', 'can_add')) {
            access_denied();
        }
        $this->load->model('student_model');
        $student = $this->student_model->get($student_id);
        $siblings_data = [];
        if ($student && !empty($student['parent_id'])) {
            $siblings = $this->student_model->getParentChilds($student['parent_id']);
            foreach ($siblings as $sib) {
                if ($sib->id != $student_id) {
                    $siblings_data[] = [
                        'student_id' => $sib->id,
                        'student_session_id' => $sib->student_session_id,
                        'name' => $sib->firstname . " " . $sib->lastname,
                        'class_section' => $sib->class . " (" . $sib->section . ")"
                    ];
                }
            }
        }
        echo json_encode(['status' => 'success', 'siblings' => $siblings_data]);
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
        
        $this->load->model('student_model');
        $student = $this->student_model->get($data['call']['student_id']);
        $data['siblings'] = [];
        if ($student && !empty($student['parent_id'])) {
            $siblings = $this->student_model->getParentChilds($student['parent_id']);
            foreach ($siblings as $sib) {
                if ($sib->id != $student['id']) {
                    $data['siblings'][] = $sib;
                }
            }
        }
        
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

        $followup_db = $this->studentcall_model->get_followup($id);
        
        // Check if next follow up date is set
        if ($this->input->post('next_follow_up_date')) {
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

        $copy_to_siblings = $this->input->post('copy_to_siblings');
        if (!empty($copy_to_siblings)) {
            $original_call = $this->studentcall_model->get_call($followup_db['student_call_id']);
            foreach ($copy_to_siblings as $sib_str) {
                $sib_parts = explode('|', $sib_str);
                if (count($sib_parts) == 2) {
                    $sib_call = array(
                        'student_session_id' => $sib_parts[0],
                        'student_id'         => $sib_parts[1],
                        'call_type'          => $original_call['call_type'],
                        'contact_person'     => $original_call['contact_person'],
                        'phone_number'       => $original_call['phone_number'],
                        'call_purpose_id'    => $original_call['call_purpose_id'],
                        'call_status'        => $this->input->post('call_status') ? $this->input->post('call_status') : 'Completed',
                        'date'               => date('Y-m-d'),
                        'notes'              => $this->input->post('remarks'),
                        'created_by'         => $userdata["id"]
                    );
                    $sib_call_id = $this->studentcall_model->add_call($sib_call);
                    
                    if ($this->input->post('next_follow_up_date')) {
                        $sib_next = array(
                            'student_call_id' => $sib_call_id,
                            'student_id'      => $sib_parts[1],
                            'due_date'        => $this->customlib->dateFormatToYYYYMMDD($this->input->post('next_follow_up_date')),
                            'priority'        => $this->input->post('next_priority') ? $this->input->post('next_priority') : $followup_db['priority'],
                            'assigned_to'     => $this->input->post('next_assigned_to') ? $this->input->post('next_assigned_to') : $followup_db['assigned_to'],
                            'status'          => 'Pending',
                            'created_by'      => $userdata["id"]
                        );
                        $this->studentcall_model->add_followup($sib_next);
                        if (!empty($sib_next['assigned_to']) && $sib_next['assigned_to'] != $userdata['id']) {
                            $this->add_notification($sib_next['assigned_to'], "New Follow-up assigned to you for student ID: " . $sib_next['student_id']);
                        }
                    }
                }
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

    public function students_status_ajax()
    {
        $class_id = $this->input->post('class_id');
        $section_id = $this->input->post('section_id');
        $start = $this->input->post('start') ? $this->input->post('start') : 0;
        $length = $this->input->post('length') ? $this->input->post('length') : 10;
        
        $search = $this->input->post('search');
        $search_value = isset($search['value']) ? $search['value'] : '';

        $students = $this->studentcall_model->get_students_call_status($class_id, $section_id, $start, $length, $search_value);
        $total_count = $this->studentcall_model->get_students_call_status_count($class_id, $section_id, $search_value);

        $data = [];
        if (!empty($students)) {
            foreach ($students as $student) {
                $status = $student['last_call_status'] ? $student['last_call_status'] : 'Not connected';
                $last_call = $student['last_call_date'] ? date($this->customlib->getSchoolDateFormat(true, true), strtotime($student['last_call_date'])) : '-';
                
                $phone = '';
                if ($student['mobileno']) $phone = $student['mobileno'];
                elseif ($student['father_phone']) $phone = $student['father_phone'];
                elseif ($student['mother_phone']) $phone = $student['mother_phone'];
                elseif ($student['guardian_phone']) $phone = $student['guardian_phone'];

                $action = '';
                if ($this->rbac->hasPrivilege('student_call_log', 'can_add')) {
                    $action = '<button class="btn btn-default btn-xs" onclick="openCallModalFromStatus(' . $student['student_id'] . ', ' . $student['student_session_id'] . ', \'' . addslashes($student['firstname'] . ' ' . $student['lastname'] . ' (' . $student['admission_no'] . ') - ' . $student['class'] . ' (' . $student['section'] . ')') . '\')" data-toggle="tooltip" title="' . ($this->lang->line('add_call') ? $this->lang->line('add_call') : 'Add Call') . '"><i class="fa fa-phone"></i></button>';
                }

                $row = [
                    $student['firstname'] . ' ' . $student['lastname'] . ' (' . $student['admission_no'] . ')',
                    $student['class'] . ' (' . $student['section'] . ')',
                    $phone,
                    $last_call,
                    $status,
                    '<div class="pull-right">' . $action . '</div>'
                ];
                $data[] = $row;
            }
        }

        $json_data = json_encode([
            "draw" => intval($this->input->post('draw')),
            "recordsTotal" => $total_count,
            "recordsFiltered" => $total_count,
            "data" => $data
        ]);
        file_put_contents('ajax_debug.txt', $json_data . "\nJSON ERROR: " . json_last_error_msg());
        echo $json_data;
    }
}
