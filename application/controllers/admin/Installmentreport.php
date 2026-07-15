<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Installmentreport extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Installmentplan_model');
        $this->load->model('class_model');
        $this->load->model('student_model');
        $this->current_session = $this->setting_model->getCurrentSession();
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('balance_fees_statement', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'Reports/finance');
        $this->session->set_userdata('subsub_menu', 'Reports/finance/installmentreport');
        
        $data['title'] = 'Installment Due Report';
        $data['classlist'] = $this->class_model->get();
        $data['installment_plans'] = $this->Installmentplan_model->get();
        $data['sch_setting'] = $this->sch_setting_detail;
        
        $data['students_list'] = array();
        
        if ($this->input->server('REQUEST_METHOD') == "POST") {
            $class_id = $this->input->post('class_id');
            $section_id = $this->input->post('section_id');
            
            $data['class_id'] = $class_id;
            $data['section_id'] = $section_id;
            
            // Get students for class/section
            $students = $this->student_model->searchByClassSection($class_id, $section_id);
            
            $students_list = array();
            
            foreach ($students as $student) {
                $student_session_id = $student['student_session_id'];
                
                // Determine if user filtered by plan
                $plan_id = $this->input->post('installment_plan_id');
                if (empty($plan_id)) {
                    $plan_id = null;
                }

                // Use calculation engine
                $installment_data = $this->Installmentplan_model->calculate_student_installments($student_session_id, $plan_id);
                
                if ($installment_data && $installment_data['total_overdue'] > 0) {
                    
                    // This student has at least one overdue installment
                    $student_record = array(
                        'name' => $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname),
                        'admission_no' => $student['admission_no'],
                        'roll_no' => $student['roll_no'],
                        'class_section' => $student['class'] . ' (' . $student['section'] . ')',
                        'father_name' => $student['father_name'],
                        'mobileno' => $student['mobileno'],
                        'plan_name' => $installment_data['plan_name'] . ' (' . ($installment_data['is_global'] ? 'Global' : 'Per Class') . ')',
                        'total_overdue' => $installment_data['total_overdue'],
                        'overdue_details' => array()
                    );
                    
                    foreach ($installment_data['installments'] as $inst) {
                        if ($inst['is_overdue']) {
                            $student_record['overdue_details'][] = $inst;
                        }
                    }
                    
                    $students_list[] = $student_record;
                }
            }
            
            $data['students_list'] = $students_list;
        }
        
        $this->load->view('layout/header', $data);
        $this->load->view('financereports/installment_report', $data);
        $this->load->view('layout/footer', $data);
    }
}
