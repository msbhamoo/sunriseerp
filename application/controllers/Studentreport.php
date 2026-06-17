<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Studentreport extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('student_model');
        $this->load->model('class_model');
        $this->load->model('section_model');
        $this->load->model('classsection_model');
    }

    public function agewise()
    {
        if (!$this->rbac->hasPrivilege('student_dashboard', 'can_view')) {
            access_denied();
        }

        $data['title'] = 'Age Wise Student';
        
        // Handle filter post
        $min_age = $this->input->post('min_age') ? $this->input->post('min_age') : '';
        $max_age = $this->input->post('max_age') ? $this->input->post('max_age') : '';
        $as_on_date = $this->input->post('as_on_date') ? $this->input->post('as_on_date') : date('Y-m-d');
        
        $data['min_age'] = $min_age;
        $data['max_age'] = $max_age;
        $data['as_on_date'] = $as_on_date;

        $results = array();
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            // Complex grouping query
            // We group by class and section, then count where age between min and max
            // Need to get all active students with their DOB
            $all_students = $this->student_model->get(); // Gets all students in current session
            
            $grouped = array();
            
            foreach ($all_students as $student) {
                if(empty($student['dob']) || $student['dob'] == '0000-00-00') continue;
                
                $dob = new DateTime($student['dob']);
                $as_on = new DateTime($as_on_date);
                $diff = $dob->diff($as_on);
                $age_years = $diff->y;
                
                if ($min_age !== '' && $age_years < $min_age) continue;
                if ($max_age !== '' && $age_years > $max_age) continue;
                
                $key = $student['class_id'] . '_' . $student['section_id'];
                if(!isset($grouped[$key])) {
                    $grouped[$key] = array(
                        'class' => $student['class'],
                        'class_id' => $student['class_id'],
                        'section' => $student['section'],
                        'section_id' => $student['section_id'],
                        'total' => 0,
                        'students' => array()
                    );
                }
                
                $student['age_string'] = $diff->y . ' years ' . $diff->m . ' months ' . $diff->d . ' days';
                $grouped[$key]['total']++;
                $grouped[$key]['students'][] = $student;
            }
            
            // Sort by class name / section conceptually
            ksort($grouped);
            $results = array_values($grouped);
        }
        
        $data['results'] = $results;

        $this->load->view('layout/header', $data);
        $this->load->view('student/reports/agewise_student', $data);
        $this->load->view('layout/footer', $data);
    }
}
