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

    public function genderwise()
    {
        if (!$this->rbac->hasPrivilege('student_dashboard', 'can_view')) {
            access_denied();
        }

        $data['title'] = 'Gender Wise Student';
        
        $gender = $this->input->post('gender') ? $this->input->post('gender') : '';
        $as_on_date = $this->input->post('as_on_date') ? $this->input->post('as_on_date') : date('Y-m-d');
        
        $data['gender'] = $gender;
        $data['as_on_date'] = $as_on_date;
        $results = array();
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $all_students = $this->student_model->get(); 
            $grouped = array();
            
            foreach ($all_students as $student) {
                if ($gender !== '' && strtolower((string)$student['gender']) !== strtolower((string)$gender)) continue;
                
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
                
                $student['age_string'] = $student['gender']; // repurposing age_string field for display
                $grouped[$key]['total']++;
                $grouped[$key]['students'][] = $student;
            }
            ksort($grouped);
            $results = array_values($grouped);
        }
        $data['results'] = $results;

        $this->load->view('layout/header', $data);
        $this->load->view('student/reports/genderwise_student', $data);
        $this->load->view('layout/footer', $data);
    }

    public function categorywise()
    {
        if (!$this->rbac->hasPrivilege('student_dashboard', 'can_view')) {
            access_denied();
        }

        $data['title'] = 'Category Wise Student';
        
        $category_id = $this->input->post('category_id') ? $this->input->post('category_id') : '';
        $as_on_date = $this->input->post('as_on_date') ? $this->input->post('as_on_date') : date('Y-m-d');
        
        $data['category_id'] = $category_id;
        $data['as_on_date'] = $as_on_date;
        $data['category_list'] = $this->category_model->get();

        $results = array();
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $all_students = $this->student_model->get(); 
            $grouped = array();
            
            foreach ($all_students as $student) {
                if ($category_id !== '' && $student['category_id'] != $category_id) continue;
                
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
                
                $student['age_string'] = $student['category']; 
                $grouped[$key]['total']++;
                $grouped[$key]['students'][] = $student;
            }
            ksort($grouped);
            $results = array_values($grouped);
        }
        $data['results'] = $results;

        $this->load->view('layout/header', $data);
        $this->load->view('student/reports/categorywise_student', $data);
        $this->load->view('layout/footer', $data);
    }

    public function religionwise()
    {
        if (!$this->rbac->hasPrivilege('student_dashboard', 'can_view')) {
            access_denied();
        }

        $data['title'] = 'Religion Wise Student';
        
        $religion = $this->input->post('religion') ? $this->input->post('religion') : '';
        $as_on_date = $this->input->post('as_on_date') ? $this->input->post('as_on_date') : date('Y-m-d');
        
        $data['religion'] = $religion;
        $data['as_on_date'] = $as_on_date;

        $results = array();
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $all_students = $this->student_model->get(); 
            $grouped = array();
            
            foreach ($all_students as $student) {
                if ($religion !== '' && strtolower((string)$student['religion']) !== strtolower((string)$religion)) continue;
                
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
                
                $student['age_string'] = $student['religion']; 
                $grouped[$key]['total']++;
                $grouped[$key]['students'][] = $student;
            }
            ksort($grouped);
            $results = array_values($grouped);
        }
        $data['results'] = $results;

        $this->load->view('layout/header', $data);
        $this->load->view('student/reports/religionwise_student', $data);
        $this->load->view('layout/footer', $data);
    }

    public function classwise()
    {
        if (!$this->rbac->hasPrivilege('student_dashboard', 'can_view')) {
            access_denied();
        }

        $data['title'] = 'Class Wise Students';
        
        $class_id = $this->input->post('class_id') ? $this->input->post('class_id') : '';
        $data['class_id'] = $class_id;
        $data['classlist'] = $this->class_model->get();

        $results = array();
        
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $all_students = $this->student_model->get(); 
            $grouped = array();
            
            foreach ($all_students as $student) {
                if ($class_id !== '' && $student['class_id'] != $class_id) continue;
                
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
                
                $student['age_string'] = ''; 
                $grouped[$key]['total']++;
                $grouped[$key]['students'][] = $student;
            }
            ksort($grouped);
            $results = array_values($grouped);
        }
        $data['results'] = $results;

        $this->load->view('layout/header', $data);
        $this->load->view('student/reports/classwise_student', $data);
        $this->load->view('layout/footer', $data);
    }

    public function rtewise()
    {
        if (!$this->rbac->hasPrivilege('student_dashboard', 'can_view')) {
            access_denied();
        }

        $data['title'] = 'RTE Students';
        
        $results = array();
        $all_students = $this->student_model->get(); 
        $grouped = array();
        
        foreach ($all_students as $student) {
            if (strtolower((string)$student['rte']) !== 'yes') continue;
            
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
            
            $student['age_string'] = 'RTE'; 
            $grouped[$key]['total']++;
            $grouped[$key]['students'][] = $student;
        }
        ksort($grouped);
        $results = array_values($grouped);
        
        $data['results'] = $results;

        $this->load->view('layout/header', $data);
        $this->load->view('student/reports/rtewise_student', $data);
        $this->load->view('layout/footer', $data);
    }

    public function birthdays()
    {
        if (!$this->rbac->hasPrivilege('student_dashboard', 'can_view')) {
            access_denied();
        }

        $data['title'] = 'Birthdays Today';
        
        $date = $this->input->post('date') ? $this->input->post('date') : date('Y-m-d');
        $data['date'] = $date;

        $results = array();
        
        if ($this->input->server('REQUEST_METHOD') === 'POST' || true) {
            $all_students = $this->student_model->get(); 
            
            $target_m = date('m', strtotime($date));
            $target_d = date('d', strtotime($date));
            
            foreach ($all_students as $student) {
                if(empty($student['dob']) || $student['dob'] == '0000-00-00') continue;
                $sm = date('m', strtotime($student['dob']));
                $sd = date('d', strtotime($student['dob']));
                
                if ($sm != $target_m || $sd != $target_d) continue;
                
                $student['age_string'] = date($this->customlib->getSchoolDateFormat(), strtotime($student['dob'])); 
                $results[] = $student;
            }
        }
        $data['results'] = $results;
        
        $data['sch_setting'] = $this->setting_model->getSetting();

        $this->load->view('layout/header', $data);
        $this->load->view('student/reports/birthdays_today', $data);
        $this->load->view('layout/footer', $data);
    }

    public function newadmissions()
    {
        if (!$this->rbac->hasPrivilege('student_dashboard', 'can_view')) {
            access_denied();
        }

        $data['title'] = 'New Admissions';
        
        $from_date = $this->input->post('from_date') ? $this->input->post('from_date') : date('Y-m-01');
        $to_date = $this->input->post('to_date') ? $this->input->post('to_date') : date('Y-m-t');
        
        $data['from_date'] = $from_date;
        $data['to_date'] = $to_date;

        $results = array();
        
        if ($this->input->server('REQUEST_METHOD') === 'POST' || true) {
            $all_students = $this->student_model->get(); 
            $grouped = array();
            
            $t_from = strtotime($from_date);
            $t_to = strtotime($to_date);
            
            foreach ($all_students as $student) {
                if(empty($student['admission_date']) || $student['admission_date'] == '0000-00-00') continue;
                $t_adm = strtotime($student['admission_date']);
                
                if ($t_adm < $t_from || $t_adm > $t_to) continue;
                
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
                
                $student['age_string'] = date($this->customlib->getSchoolDateFormat(), strtotime($student['admission_date'])); 
                $grouped[$key]['total']++;
                $grouped[$key]['students'][] = $student;
            }
            ksort($grouped);
            $results = array_values($grouped);
        }
        $data['results'] = $results;

        $this->load->view('layout/header', $data);
        $this->load->view('student/reports/new_admissions', $data);
        $this->load->view('layout/footer', $data);
    }
    
    public function totalstudents()
    {
        if (!$this->rbac->hasPrivilege('student_dashboard', 'can_view')) {
            access_denied();
        }

        $data['title'] = 'Total Students Summary';
        
        $results = array();
        $all_students = $this->student_model->get(); 
        $grouped = array();
        
        foreach ($all_students as $student) {
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
            
            $student['age_string'] = ''; 
            $grouped[$key]['total']++;
            $grouped[$key]['students'][] = $student;
        }
        ksort($grouped);
        $results = array_values($grouped);
        
        $data['results'] = $results;

        $this->load->view('layout/header', $data);
        $this->load->view('student/reports/total_students', $data);
        $this->load->view('layout/footer', $data);
    }
}
