<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Teachercompliance extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("teachercompliance_model");
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('teacher_compliance', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Attendance');
        $this->session->set_userdata('sub_menu', 'teachercompliance/index');

        $data['sch_setting'] = $this->sch_setting_detail;
        $session_id = $this->setting_model->getCurrentSession();

        $this->form_validation->set_rules('date', $this->lang->line('date'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $date = date('Y-m-d');
        } else {
            $date = date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('date')));
        }
        $data['date'] = date($this->customlib->getSchoolDateFormat(), strtotime($date));

        $compliance_list = $this->teachercompliance_model->getComplianceByDate($date, $session_id);

        // Define cutoff time logic
        $is_past_cutoff = false;
        $cutoff_time = '14:00:00'; // 2:00 PM
        
        if ($date < date('Y-m-d')) {
            // Past dates are always past cutoff
            $is_past_cutoff = true;
        } elseif ($date == date('Y-m-d')) {
            // Today, check current time against cutoff
            if (date('H:i:s') > $cutoff_time) {
                $is_past_cutoff = true;
            }
        }

        $data['is_past_cutoff'] = $is_past_cutoff;
        $data['cutoff_time_display'] = '02:00 PM';
        $data['resultlist'] = $compliance_list;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/teachercompliance/index', $data);
        $this->load->view('layout/footer', $data);
    }
}
