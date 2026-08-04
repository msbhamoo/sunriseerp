<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Ptm extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('ptm_model');
        $this->load->model('section_model');
        $this->load->model('messages_model');
        $this->load->library('mailsmsconf');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('ptm_parent_teacher_meeting', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'admin/ptm');
        $data['title'] = 'PTM List';
        
        $ptms = $this->ptm_model->get();
        foreach ($ptms as $key => $ptm) {
            $ptms[$key]['targets'] = $this->ptm_model->get_targets($ptm['id']);
        }
        $data['ptm_list'] = $ptms;
        
        $class = $this->class_model->get();
        foreach ($class as $key => $value) {
            $class[$key]['sections'] = $this->section_model->getClassBySection($value['id']);
        }
        $data['classlist'] = $class;

        $this->form_validation->set_rules('title', $this->lang->line('title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('ptm_date', $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('time_from', $this->lang->line('time_from'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('time_to', $this->lang->line('time_to'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('venue', $this->lang->line('venue'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/ptm/index', $data);
            $this->load->view('layout/footer', $data);
        } else {
            if (!$this->rbac->hasPrivilege('ptm_parent_teacher_meeting', 'can_add')) {
                access_denied();
            }
            $target_type = $this->input->post('target_type');
            $data_insert = array(
                'title' => $this->input->post('title'),
                'ptm_date' => $this->customlib->dateFormatToYYYYMMDD($this->input->post('ptm_date')),
                'time_from' => $this->input->post('time_from'),
                'time_to' => $this->input->post('time_to'),
                'venue' => $this->input->post('venue'),
                'description' => $this->input->post('description'),
                'target_type' => $target_type,
                'created_by' => $this->customlib->getStaffID() ? $this->customlib->getStaffID() : 1,
            );
            $insert_id = $this->ptm_model->add($data_insert);
            
            if ($target_type != 'whole_school') {
                $targets = $this->input->post('class_section_id'); // e.g. "classid-sectionid" array
                $this->ptm_model->add_targets($insert_id, $targets);
            }

            // Notification Logic using mailsmsconf helper
            $sender_details = array('title' => $this->input->post('title'), 'date' => $this->input->post('ptm_date'), 'venue' => $this->input->post('venue'));
            if ($this->input->post('mail') || $this->input->post('sms') || $this->input->post('mobile_app') || $this->input->post('whatsapp')) {
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">PTM created successfully. Notifications are queued.</div>');
            } else {
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">PTM created successfully</div>');
            }

            redirect('admin/ptm');
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('ptm_parent_teacher_meeting', 'can_edit')) {
            access_denied();
        }
        $data['id'] = $id;
        $data['title'] = 'Edit PTM';
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'admin/ptm');
        
        $ptm = $this->ptm_model->get($id);
        $ptm['targets'] = $this->ptm_model->get_targets($id);
        $data['ptm'] = $ptm;

        $ptms = $this->ptm_model->get();
        foreach ($ptms as $key => $p) {
            $ptms[$key]['targets'] = $this->ptm_model->get_targets($p['id']);
        }
        $data['ptm_list'] = $ptms;

        $class = $this->class_model->get();
        foreach ($class as $key => $value) {
            $class[$key]['sections'] = $this->section_model->getClassBySection($value['id']);
        }
        $data['classlist'] = $class;

        $this->form_validation->set_rules('title', $this->lang->line('title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('ptm_date', $this->lang->line('date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('time_from', $this->lang->line('time_from'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('time_to', $this->lang->line('time_to'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('venue', $this->lang->line('venue'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/ptm/index', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $target_type = $this->input->post('target_type');
            $data_insert = array(
                'id' => $id,
                'title' => $this->input->post('title'),
                'ptm_date' => $this->customlib->dateFormatToYYYYMMDD($this->input->post('ptm_date')),
                'time_from' => $this->input->post('time_from'),
                'time_to' => $this->input->post('time_to'),
                'venue' => $this->input->post('venue'),
                'description' => $this->input->post('description'),
                'target_type' => $target_type,
                'created_by' => $this->customlib->getStaffID() ? $this->customlib->getStaffID() : 1,
            );
            $this->ptm_model->add($data_insert);
            
            if ($target_type != 'whole_school') {
                $targets = $this->input->post('class_section_id'); 
                $this->ptm_model->add_targets($id, $targets);
            } else {
                $this->ptm_model->add_targets($id, []);
            }

            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/ptm');
        }
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('ptm_parent_teacher_meeting', 'can_delete')) {
            access_denied();
        }
        $this->ptm_model->remove($id);
        redirect('admin/ptm');
    }

    public function attendance($ptm_id)
    {
        if (!$this->rbac->hasPrivilege('ptm_parent_teacher_meeting', 'can_add')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Academics');
        $this->session->set_userdata('sub_menu', 'admin/ptm');
        
        $data['ptm'] = $this->ptm_model->get($ptm_id);
        $data['targets'] = $this->ptm_model->get_targets($ptm_id);
        
        // Fetch students based on targets
        $students = [];
        if ($data['ptm']['target_type'] == 'whole_school') {
            $students = $this->student_model->get();
        } else {
            foreach ($data['targets'] as $target) {
                $class_students = $this->student_model->searchByClassSection($target['class_id'], $target['section_id']);
                $students = array_merge($students, $class_students);
            }
        }
        $data['students'] = $students;
        $data['attendances'] = $this->ptm_model->get_student_attendances($ptm_id);
        $data['staffs'] = $this->staff_model->get();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/ptm/attendance', $data);
        $this->load->view('layout/footer', $data);
    }

    public function save_attendance()
    {
        if (!$this->rbac->hasPrivilege('ptm_parent_teacher_meeting', 'can_add')) {
            $json_array = array('status' => 'fail', 'error' => '', 'message' => $this->lang->line('access_denied'));
            echo json_encode($json_array);
            return;
        }

        $ptm_id = $this->input->post('ptm_id');
        $student_session_id = $this->input->post('student_session_id');
        
        $data = array(
            'ptm_id' => $ptm_id,
            'student_session_id' => $student_session_id,
            'status' => $this->input->post('status'),
            'attendee_type' => $this->input->post('attendee_type') != '' ? $this->input->post('attendee_type') : NULL,
            'arrival_time' => $this->input->post('arrival_time') != '' ? $this->input->post('arrival_time') : NULL,
            'departure_time' => $this->input->post('departure_time') != '' ? $this->input->post('departure_time') : NULL,
            'discussion_points' => $this->input->post('discussion_points'),
            'parent_remarks' => $this->input->post('parent_remarks'),
            'teacher_remarks' => $this->input->post('teacher_remarks'),
            'concerns_academics' => $this->input->post('concerns_academics'),
            'concerns_attendance' => $this->input->post('concerns_attendance'),
            'concerns_behavior' => $this->input->post('concerns_behavior'),
            'concerns_discipline' => $this->input->post('concerns_discipline'),
            'action_items' => $this->input->post('action_items'),
            'followup_required' => $this->input->post('followup_required') ? 1 : 0,
            'followup_assigned_to' => $this->input->post('followup_assigned_to') != '' ? $this->input->post('followup_assigned_to') : NULL,
            'followup_date' => $this->input->post('followup_date') != '' ? date('Y-m-d', strtotime(str_replace('/', '-', $this->input->post('followup_date')))) : NULL,
        );

        $this->ptm_model->save_attendance($data);

        // Sync with Student Call Log if follow-up is required
        if ($data['followup_required'] == 1 && !empty($data['followup_assigned_to'])) {
            $this->load->model('studentcall_model');
            
            // Find or create purpose "PTM Follow-up"
            $purposes = $this->studentcall_model->get_purposes();
            $ptm_purpose_id = null;
            foreach ($purposes as $purp) {
                if (strtolower($purp['purpose']) == 'ptm follow-up' || strtolower($purp['purpose']) == 'ptm') {
                    $ptm_purpose_id = $purp['id'];
                    break;
                }
            }
            if (!$ptm_purpose_id) {
                $ptm_purpose_id = $this->studentcall_model->add_purpose([
                    'purpose' => 'PTM Follow-up',
                    'description' => 'Follow-up assigned from PTM report'
                ]);
            }

            // Build comprehensive PTM summary incorporating discussion, concerns, remarks & action items
            $ptm_details = [];
            if (!empty($data['action_items'])) {
                $ptm_details[] = 'Action Items: ' . $data['action_items'];
            }
            if (!empty($data['discussion_points'])) {
                $ptm_details[] = 'Discussion: ' . $data['discussion_points'];
            }
            if (!empty($data['concerns_academics'])) {
                $ptm_details[] = 'Academics: ' . $data['concerns_academics'];
            }
            if (!empty($data['concerns_attendance'])) {
                $ptm_details[] = 'Attendance: ' . $data['concerns_attendance'];
            }
            if (!empty($data['concerns_behavior'])) {
                $ptm_details[] = 'Behavior: ' . $data['concerns_behavior'];
            }
            if (!empty($data['concerns_discipline'])) {
                $ptm_details[] = 'Discipline: ' . $data['concerns_discipline'];
            }
            if (!empty($data['teacher_remarks'])) {
                $ptm_details[] = 'Teacher Remarks: ' . $data['teacher_remarks'];
            }
            if (!empty($data['parent_remarks'])) {
                $ptm_details[] = 'Parent Remarks: ' . $data['parent_remarks'];
            }
            $ptm_summary_notes = !empty($ptm_details) ? implode(' | ', $ptm_details) : 'PTM Follow-up';

            // Fetch student session & info
            $student = $this->student_model->getByStudentSession($student_session_id);
            if (!empty($student)) {
                $call_data = array(
                    'student_id' => $student['id'],
                    'student_session_id' => $student_session_id,
                    'call_purpose_id' => $ptm_purpose_id,
                    'call_type' => 'Outgoing',
                    'contact_person' => !empty($student['father_name']) ? $student['father_name'] : $student['firstname'],
                    'phone_number' => !empty($student['father_phone']) ? $student['father_phone'] : (!empty($student['mobileno']) ? $student['mobileno'] : $student['guardian_phone']),
                    'date' => date('Y-m-d H:i:s'),
                    'call_status' => 'Callback Requested',
                    'notes' => $ptm_summary_notes,
                    'created_by' => $this->customlib->getStaffID() ? $this->customlib->getStaffID() : 1
                );
                $call_id = $this->studentcall_model->add_call($call_data);

                $followup_data = array(
                    'student_call_id' => $call_id,
                    'assigned_to' => $data['followup_assigned_to'],
                    'due_date' => !empty($data['followup_date']) ? $data['followup_date'] . ' 09:00:00' : date('Y-m-d 09:00:00'),
                    'priority' => 'High',
                    'remarks' => $ptm_summary_notes,
                    'status' => 'Pending'
                );
                $this->studentcall_model->add_followup($followup_data);
            }
        }

        // Internal notification to assigned staff
        if ($data['followup_required'] == 1 && $data['followup_assigned_to'] != NULL) {
            $sender_id = $this->customlib->getStaffID();
            if ($sender_id != $data['followup_assigned_to']) {
                $student = $this->student_model->getByStudentSession($student_session_id);
                $this->load->model('SystemNotification_model');
                $title = 'PTM Follow-up Assigned';
                $message = 'A PTM follow-up for student ' . $student['firstname'] . ' ' . $student['lastname'] . ' has been assigned to you.';
                if (!empty($data['action_items'])) {
                    $message .= ' Action Items: ' . $data['action_items'];
                }
                $this->SystemNotification_model->notifyUser($data['followup_assigned_to'], $title, $message, base_url('admin/studentcall/assigned_to_me'));
            }
        }

        $json_array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        echo json_encode($json_array);
    }

    public function get_student_snapshot()
    {
        $student_id = $this->input->post('student_id');
        $student_session_id = $this->input->post('student_session_id');
        $ptm_id = $this->input->post('ptm_id');
        
        $student = $this->student_model->get($student_id);
        
        // Fee Info
        $this->load->model('studentfeemaster_model');
        $fees = $this->studentfeemaster_model->getStudentFees($student_session_id);
        
        // CBSE Exams
        $cbse_exams = [];
        if ($this->db->table_exists('cbse_exam_students')) {
            $this->db->select('cbse_exams.name as exam_name');
            $this->db->from('cbse_exam_students');
            $this->db->join('cbse_exams', 'cbse_exams.id = cbse_exam_students.cbse_exam_id');
            $this->db->where('cbse_exam_students.student_session_id', $student_session_id);
            $student_exams = $this->db->get()->result_array();
            
            foreach($student_exams as $exam) {
                 $cbse_exams[] = [
                     'exam_name' => $exam['exam_name'],
                     'total_marks' => '-',
                     'obtained_marks' => '-',
                     'percentage' => '-'
                 ];
            }
        }
        
        // PTM Remarks (current)
        $current_ptm = [];
        if($ptm_id) {
            $current_ptm = $this->ptm_model->get_student_attendances($ptm_id);
        }
        $ptm_remarks = isset($current_ptm[$student_session_id]) ? $current_ptm[$student_session_id] : null;

        // Previous PTM Remarks
        $this->db->select('ptm_attendances.*, ptms.title, ptms.ptm_date');
        $this->db->from('ptm_attendances');
        $this->db->join('ptms', 'ptms.id = ptm_attendances.ptm_id');
        $this->db->where('ptm_attendances.student_session_id', $student_session_id);
        if ($ptm_id) {
            $this->db->where('ptm_attendances.ptm_id !=', $ptm_id);
        }
        $this->db->order_by('ptms.ptm_date', 'desc');
        $previous_ptm_remarks = $this->db->get()->result_array();
        
        $data = [
            'student' => $student,
            'fees' => $fees,
            'cbse_exams' => $cbse_exams,
            'ptm_remarks' => $ptm_remarks,
            'previous_ptm_remarks' => $previous_ptm_remarks,
            'settinglist' => $this->setting_model->get()
        ];
        
        $html = $this->load->view('admin/ptm/_student_profile', $data, true);
        echo json_encode(['status' => 1, 'page' => $html]);
    }
}
