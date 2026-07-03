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

    public function custom_report()
    {
        if (!$this->rbac->hasPrivilege('student_dashboard', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Student Information');
        $this->session->set_userdata('sub_menu', 'studentreport/custom_report');
        $data['title'] = 'Custom Report';

        $data['classlist'] = $this->class_model->get();
        // Since Admission Type is a custom field, we'll fetch its values or let it be empty initially
        $this->load->model('customfield_model');
        $custom_fields = $this->customfield_model->getByBelong('students');
        $data['custom_fields'] = $custom_fields;

        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $class_ids = $this->input->post('class_id') ? $this->input->post('class_id') : [];
            $section_ids = $this->input->post('section_id') ? $this->input->post('section_id') : [];
            $admission_types = $this->input->post('admission_type') ? $this->input->post('admission_type') : [];
            $selected_columns = $this->input->post('columns') ? $this->input->post('columns') : [];

            // Get students
            $students = $this->student_model->get();
            $results = [];

            foreach ($students as $student) {
                // Filter by Class
                if (!empty($class_ids) && !in_array('all', $class_ids) && !in_array($student['class_id'], $class_ids)) {
                    continue;
                }
                // Filter by Section
                if (!empty($section_ids) && !in_array('all', $section_ids) && !in_array($student['section_id'], $section_ids)) {
                    continue;
                }
                
                // Fetch custom fields for student
                $student_custom_fields = $this->customfield_model->get_custom_fields('students', $student['id']);
                
                // Filter by Admission Type
                if (!empty($admission_types) && !in_array('all', $admission_types)) {
                    $has_admission_type = false;
                    if (isset($student['admission_type']) && in_array($student['admission_type'], $admission_types)) {
                        $has_admission_type = true;
                    } else if (!empty($student_custom_fields)) {
                        foreach ($student_custom_fields as $scf) {
                            // Match if any custom field value is in the selected admission types
                            if (in_array($scf->field_value, $admission_types)) {
                                $has_admission_type = true;
                                break;
                            }
                        }
                    }
                    if (!$has_admission_type) {
                        continue;
                    }
                }
                
                $student['custom_fields'] = $student_custom_fields;
                
                // Add to results
                $results[] = $student;
            }

            // Custom fields and fees
            foreach ($results as &$res) {
                // Fetch fee info
                $this->load->model('studentfeemaster_model');
                $student_due_fee = $this->studentfeemaster_model->getStudentFees($res['student_session_id']);
                $total_fee = 0;
                $total_paid = 0;
                if (!empty($student_due_fee)) {
                    foreach ($student_due_fee as $fee_key => $fee_value) {
                        $total_fee += $fee_value->amount;
                        if (is_string($fee_value->amount_detail)) {
                            $amount_detail = json_decode($fee_value->amount_detail);
                            if (is_array($amount_detail)) {
                                foreach ($amount_detail as $amount_detail_val) {
                                    $total_paid += $amount_detail_val->amount;
                                }
                            }
                        }
                    }
                }
                $res['total_fee'] = $total_fee;
                $res['total_paid'] = $total_paid;
                $res['total_balance'] = $total_fee - $total_paid;
            }

            $data['results'] = $results;
            $data['selected_columns'] = $selected_columns;
            $this->load->view('layout/header', $data);
            $this->load->view('student/reports/custom_report_view', $data);
            $this->load->view('layout/footer', $data);
            return;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('student/reports/custom_report', $data);
        $this->load->view('layout/footer', $data);
    }
}
