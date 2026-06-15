<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Attendanceanalytics extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("attendanceanalytics_model");
        $this->load->model("teachercompliance_model");
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('attendance_analytics', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Attendance');
        $this->session->set_userdata('sub_menu', 'attendanceanalytics/index');

        $data['sch_setting'] = $this->sch_setting_detail;
        $session_id = $this->setting_model->getCurrentSession();

        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $date = date('Y-m-d');
        } else {
            $date = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date')));
        }
        
        $data['date'] = date($this->customlib->getSchoolDateFormat(), strtotime($date));

        $data['overview'] = $this->attendanceanalytics_model->getTodayOverview($date);
        $data['class_absenteeism'] = $this->attendanceanalytics_model->getClassWiseAbsenteeism($date, $session_id);
        
        $start_date = date('Y-m-d', strtotime('-6 days', strtotime($date)));
        $data['trend'] = $this->attendanceanalytics_model->getTrend($start_date, $date);

        $data['absent_students'] = $this->attendanceanalytics_model->getAbsentStudentsWithFollowup($date, $session_id);

        $compliance_list = $this->teachercompliance_model->getComplianceByDate($date, $session_id);
        $non_compliant = array();
        if(!empty($compliance_list)){
            foreach($compliance_list as $row){
                if($row['attendance_count'] == 0){
                    $non_compliant[] = $row;
                }
            }
        }
        $data['non_compliant_teachers'] = $non_compliant;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/attendanceanalytics/index', $data);
        $this->load->view('layout/footer', $data);
    }
}
