<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Absenteefollowup extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array("absenteefollowup_model", "teacher_model", "class_model"));
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->config->load("mailsms");
        $this->load->library('mailsmsconf');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('absentee_followup', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Attendance');
        $this->session->set_userdata('sub_menu', 'admin/absenteefollowup');

        $data['sch_setting'] = $this->sch_setting_detail;
        
        $userdata = $this->customlib->getUserData();
        $role_id = $userdata["role_id"];

        if (isset($role_id) && ($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
            $class = $this->teacher_model->get_daywiseattendanceclass($userdata["id"]);
        } else {
            $class = $this->class_model->get();
        }

        $data['classlist'] = $class;
        $data['class_id'] = "";
        $data['section_id'] = "";
        $data['date'] = date($this->customlib->getSchoolDateFormat());

        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');
        // Class and section are optional filters. By default we show all absentees for the day.

        if ($this->form_validation->run() == false) {
            $date = date('Y-m-d');
            $class_id = null;
            $section_id = null;
        } else {
            $data['class_id'] = $this->input->post('class_id');
            $data['section_id'] = $this->input->post('section_id');
            $data['date'] = $this->input->post('date');

            $date = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date')));
            $class_id = $this->input->post('class_id') != "" ? $this->input->post('class_id') : null;
            $section_id = $this->input->post('section_id') != "" ? $this->input->post('section_id') : null;
        }

        $resultlist = $this->absenteefollowup_model->getAbsentLateStudents($date, $class_id, $section_id);
        
        // Calculate consecutive absence counts dynamically
        foreach ($resultlist as &$student) {
            $student['consecutive_days'] = $this->absenteefollowup_model->getConsecutiveAbsenceCount($student['student_session_id'], $date);
        }
        
        $data['resultlist'] = $resultlist;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/absenteefollowup/index', $data);
        $this->load->view('layout/footer', $data);
    }

    public function save_followup()
    {
        if (!$this->rbac->hasPrivilege('absentee_followup', 'can_add')) {
            access_denied();
        }

        $this->form_validation->set_rules('student_session_id', 'Student', 'required|trim|xss_clean');
        $this->form_validation->set_rules('date', 'Date', 'required|trim|xss_clean');
        $this->form_validation->set_rules('action', 'Action', 'required|trim|xss_clean');
        $this->form_validation->set_rules('followup_status', 'Status', 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'status' => 'fail',
                'error' => validation_errors()
            );
        } else {
            $userdata = $this->customlib->getUserData();
            $data = array(
                'student_session_id' => $this->input->post('student_session_id'),
                'date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date'))),
                'action' => $this->input->post('action'),
                'followup_status' => $this->input->post('followup_status'),
                'remark' => $this->input->post('remark'),
                'created_by' => $userdata['id']
            );

            $this->absenteefollowup_model->saveLog($data);
            
            // If action is SMS or Email, we would call the mailsmsconf here
            // Currently using hardcoded dummy templates/actions as per prompt
            if ($this->input->post('action') == 'SMS') {
                 // Trigger SMS
            } elseif ($this->input->post('action') == 'Email') {
                 // Trigger Email
            } elseif ($this->input->post('action') == 'WhatsApp') {
                $this->load->library('smsgateway');
                $this->load->library('whatsappgateway');
                
                $student_session_id = $this->input->post('student_session_id');
                $date_post = $this->input->post('date');
                $date_formatted = date('Y-m-d', $this->customlib->datetostrtotime($date_post));
                
                $chk_mail_sms = $this->customlib->sendMailSMS('student_absent_attendence');
                if ($chk_mail_sms['is_whatsapp'] && $chk_mail_sms['template'] != "") {
                    // Fetch comprehensive student details including class, section, and attendance type
                    $student_result_v = $this->db->select('students.*, classes.class, sections.section, student_attendences.attendence_type_id, students.parent_app_key, students.app_key')
                        ->from('student_session')
                        ->join('students', 'students.id = student_session.student_id')
                        ->join('classes', 'classes.id = student_session.class_id')
                        ->join('sections', 'sections.id = student_session.section_id')
                        ->join('student_attendences', 'student_attendences.student_session_id = student_session.id AND student_attendences.date = ' . $this->db->escape($date_formatted))
                        ->where('student_session.id', $student_session_id)
                        ->get()
                        ->row_array();
                        
                    if (!empty($student_result_v) && $student_result_v['attendence_type_id'] == 4) { // Only send if Absent (4)
                        $detail = array(
                            'date'                => $date_formatted,
                            'parent_app_key'      => $student_result_v['parent_app_key'],
                            'app_key'             => $student_result_v['app_key'],
                            'mobileno'            => $student_result_v['mobileno'],
                            'email'               => $student_result_v['email'],
                            'firstname'           => $student_result_v['firstname'],
                            'middlename'          => $student_result_v['middlename'],
                            'lastname'            => $student_result_v['lastname'],
                            'father_name'         => $student_result_v['father_name'],
                            'father_phone'        => $student_result_v['father_phone'],
                            'father_occupation'   => $student_result_v['father_occupation'],
                            'mother_name'         => $student_result_v['mother_name'],
                            'mother_phone'        => $student_result_v['mother_phone'],
                            'guardian_name'       => $student_result_v['guardian_name'],
                            'guardian_phone'      => $student_result_v['guardian_phone'],
                            'guardian_occupation' => $student_result_v['guardian_occupation'],
                            'guardian_email'      => $student_result_v['guardian_email'],
                            'student_name'        => $student_result_v['firstname'] . ' ' . $student_result_v['lastname'],
                            'class'               => $student_result_v['class'],
                            'section'             => $student_result_v['section'],
                            'current_session_name'=> $this->setting_model->getCurrentSessionName()
                        );
                        
                        if ($chk_mail_sms['is_student_recipient']) { 
                            $this->whatsappgateway->sendAbsentAttendancenotification($detail, $chk_mail_sms['template'], $chk_mail_sms['whatsapp_template_id'], $detail['mobileno']);
                        }
                    }
                }
            }

            $data = array('status' => 'success', 'message' => $this->lang->line('success_message'));
        }

        echo json_encode($data);
    }

    public function get_history()
    {
        if (!$this->rbac->hasPrivilege('absentee_followup', 'can_view')) {
            access_denied();
        }

        $student_session_id = $this->input->post('student_session_id');
        $date = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date')));

        $logs = $this->absenteefollowup_model->getLogs($student_session_id, $date);

        $data['logs'] = $logs;
        $html = $this->load->view('admin/absenteefollowup/_history', $data, true);
        
        echo json_encode(['status' => 'success', 'html' => $html]);
    }
}
