<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Customstudentimport extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('SaasValidation');
        $this->load->library('media_storage');
        $this->config->load('app-config');
        $this->config->load("payroll");
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->load->library('encoding_lib');
        $this->load->library('role');
        $this->load->model(array("student_model", "class_model", "section_model", "category_model", "customfield_model", "studentfeemaster_model", "user_model", "setting_model"));
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->staff_role = $this->customlib->getStaffRole();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('import_student', 'can_view')) {
            access_denied();
        }
        $data['title'] = 'Custom Bulk Student Import';
        $this->load->view('layout/header', $data);
        $this->load->view('admin/customstudentimport/upload', $data);
        $this->load->view('layout/footer', $data);
    }

    public function handle_csv_upload()
    {
        $error = "";
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
            $allowedExts = array('csv');
            $mimes       = array('text/csv', 'text/plain', 'application/csv', 'text/comma-separated-values', 'application/excel', 'application/vnd.ms-excel', 'application/vnd.msexcel', 'text/anytext', 'application/octet-stream', 'application/txt');
            $temp        = explode(".", $_FILES["file"]["name"]);
            $extension   = end($temp);
            if ($_FILES["file"]["error"] > 0) {
                $error .= "Error opening the file<br />";
            }
            if (!in_array($extension, $allowedExts)) {
                $error .= "Error uploading file, please ensure you are uploading a CSV file.<br />";
            }
        } else {
            $error .= "Please select file<br />";
        }

        if ($error == "") {
            return true;
        }
        $this->form_validation->set_message('handle_csv_upload', $error);
        return false;
    }

    public function mapping()
    {
        if (!$this->rbac->hasPrivilege('import_student', 'can_view')) {
            access_denied();
        }

        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_csv_upload');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Custom Bulk Student Import';
            $this->load->view('layout/header', $data);
            $this->load->view('admin/customstudentimport/upload', $data);
            $this->load->view('layout/footer', $data);
        } else {
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                if ($ext == 'csv') {
                    // Create temp folder if not exists
                    if (!is_dir('./uploads/temp/')) {
                        mkdir('./uploads/temp/', 0777, true);
                    }
                    
                    $file_name = "import_" . time() . ".csv";
                    $destination = './uploads/temp/' . $file_name;
                    move_uploaded_file($_FILES['file']['tmp_name'], $destination);

                    $this->load->library('CSVReader');
                    $result = $this->csvreader->parse_file($destination);
                    
                    if (empty($result)) {
                        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Failed to read the CSV file. Please make sure it has headers and is comma separated.</div>');
                        redirect('admin/customstudentimport/index');
                    }

                    // The CSVReader returns an array where the keys of the inner arrays are the headers
                    $first_row = current($result);
                    $data['csv_headers'] = array_keys($first_row);
                    $data['file_name'] = $file_name;
                    
                    // Standard Fields
                    $data['standard_fields'] = array(
                        '' => 'Ignore this column',
                        'firstname' => 'First Name',
                        'lastname' => 'Last Name',
                        'student_name' => 'Full Student Name (Will auto split)',
                        'admission_no' => 'Admission Number (SR No)',
                        'roll_no' => 'Roll Number',
                        'class_name' => 'Class Name (e.g. Class 1)',
                        'stream_name' => 'Stream Name (Auto-combines with Class)',
                        'section_name' => 'Section Name (e.g. A)',
                        'gender' => 'Gender',
                        'dob' => 'Date of Birth',
                        'category' => 'Category (Text)',
                        'religion' => 'Religion',
                        'cast' => 'Cast',
                        'mobileno' => 'Mobile Number',
                        'email' => 'Email',
                        'admission_date' => 'Admission Date',
                        'blood_group' => 'Blood Group',
                        'school_house' => 'House (Text)',
                        'height' => 'Height',
                        'weight' => 'Weight',
                        'measurement_date' => 'Measurement Date',
                        'father_name' => 'Father Name',
                        'father_phone' => 'Father Phone',
                        'father_occupation' => 'Father Occupation',
                        'mother_name' => 'Mother Name',
                        'mother_phone' => 'Mother Phone',
                        'mother_occupation' => 'Mother Occupation',
                        'guardian_is' => 'Guardian Is',
                        'guardian_name' => 'Guardian Name',
                        'guardian_relation' => 'Guardian Relation',
                        'guardian_email' => 'Guardian Email',
                        'guardian_phone' => 'Guardian Phone',
                        'guardian_occupation' => 'Guardian Occupation',
                        'guardian_address' => 'Guardian Address',
                        'current_address' => 'Current Address',
                        'permanent_address' => 'Permanent Address',
                        'bank_account_no' => 'Bank Account No',
                        'bank_name' => 'Bank Name',
                        'ifsc_code' => 'IFSC Code',
                        'adhar_no' => 'Adhar No',
                        'samagra_id' => 'Samagra ID',
                        'rte' => 'RTE (Yes/No)',
                        'previous_school' => 'Previous School',
                        'note' => 'Note'
                    );

                    // Custom Fields
                    $custom_fields = $this->customfield_model->getByBelong('students');
                    $data['custom_fields'] = $custom_fields;

                    // Auto-mapping logic
                    $auto_map = array();
                    $alias_map = array(
                        'studentname' => 'student_name',
                        'name' => 'student_name',
                        'firstname' => 'student_name',
                        'admissionno' => 'admission_no',
                        'srno' => 'admission_no',
                        'class' => 'class_name',
                        'stream' => 'stream_name',
                        'section' => 'section_name',
                        'dob' => 'dob',
                        'dateofbirth' => 'dob',
                        'gender' => 'gender',
                        'mobileno' => 'mobileno',
                        'studentcontact' => 'mobileno',
                        'contact' => 'mobileno',
                        'email' => 'email',
                        'studentemail' => 'email',
                        'fathername' => 'father_name',
                        'mothername' => 'mother_name',
                        'fathercontact' => 'father_phone',
                        'mothercontact' => 'mother_phone',
                        'category' => 'category',
                        'religion' => 'religion',
                        'bloodgroup' => 'blood_group',
                        'address' => 'current_address',
                        'guardianname' => 'guardian_name',
                        'guardiancontact' => 'guardian_phone',
                        'admissiondate' => 'admission_date',
                        'house' => 'school_house',
                        'aadharno' => 'adhar_no',
                        'aadhar' => 'adhar_no',
                        'adhar' => 'adhar_no',
                    );

                    foreach ($data['csv_headers'] as $header) {
                        $clean_header = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $header));
                        $matched_val = '';
                        
                        if (isset($alias_map[$clean_header])) {
                            $matched_val = $alias_map[$clean_header];
                        } else {
                            // Try custom fields
                            foreach ($custom_fields as $cf) {
                                $clean_cf = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $cf['name']));
                                if ($clean_header == $clean_cf) {
                                    $matched_val = 'custom_' . $cf['id'];
                                    break;
                                }
                            }
                        }
                        $auto_map[$header] = $matched_val;
                    }
                    $data['auto_map'] = $auto_map;

                    $data['title'] = 'Map Columns';
                    $this->load->view('layout/header', $data);
                    $this->load->view('admin/customstudentimport/mapping', $data);
                    $this->load->view('layout/footer', $data);
                }
            }
        }
    }

    public function preview()
    {
        if (!$this->rbac->hasPrivilege('import_student', 'can_view')) {
            access_denied();
        }

        $file_name = $this->input->post('file_name');
        
        $mapping_raw = $this->input->post('mapping');
        $mapping = array();
        if ($mapping_raw) {
            foreach($mapping_raw as $hex => $db_field) {
                $mapping[hex2bin($hex)] = $db_field;
            }
        }
        $custom_mapping = $this->input->post('custom_mapping');

        $destination = './uploads/temp/' . $file_name;

        if (!file_exists($destination)) {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Temporary file not found. Please upload again.</div>');
            redirect('admin/customstudentimport/index');
        }

        $this->load->library('CSVReader');
        // Standard parse without true returns an associative array based on headers
        $result = $this->csvreader->parse_file($destination);

        $parsed_data = array();
        $errors = array();
        $perfect_matches = 0;
        $error_matches = 0;

        $classes = $this->class_model->get();
        $class_map = array();
        foreach($classes as $c) {
            $class_map[strtolower(trim($c['class']))] = $c['id'];
        }

        $categories = $this->category_model->get();
        $category_map = array();
        foreach($categories as $c) {
            $category_map[strtolower(trim($c['category']))] = $c['id'];
        }

        $houses = $this->student_model->gethouselist();
        $house_map = array();
        foreach($houses as $h) {
            $house_map[strtolower(trim($h['house_name']))] = $h['id'];
        }

        $row_num = 1;
        foreach ($result as $row) {
            $row_num++;
            
            // Normalize row keys (trim them so they match the trimmed headers from mapping)
            $normalized_row = array();
            foreach($row as $k => $v) {
                $normalized_row[trim($k)] = $v;
            }
            $row = $normalized_row;

            $student_data = array();
            $custom_value_array = array();
            $row_errors = array();

            // Apply mapping
            foreach ($mapping as $csv_col => $db_field) {
                if ($db_field != '') {
                    $val = isset($row[$csv_col]) ? trim($this->encoding_lib->toUTF8($row[$csv_col])) : '';
                    if (strpos($db_field, 'custom_') === 0) {
                        $custom_field_id = str_replace('custom_', '', $db_field);
                        $custom_value_array[] = array(
                            'belong_table_id' => 0,
                            'custom_field_id' => $custom_field_id,
                            'field_value'     => $val,
                        );
                    } else if ($db_field == 'student_name') {
                        $parts = explode(" ", $val, 2);
                        $student_data['firstname'] = $parts[0];
                        $student_data['lastname'] = isset($parts[1]) ? $parts[1] : '';
                    } else {
                        $student_data[$db_field] = $val;
                    }
                }
            }

            // Combine Class and Stream if Stream is provided
            if (isset($student_data['stream_name']) && $student_data['stream_name'] != '') {
                $stream = trim($student_data['stream_name']);
                $stream_lower = strtolower($stream);
                
                // Intelligent fallbacks based on user's setup
                if ($stream_lower == 'common' || $stream_lower == 'n/a' || $stream_lower == 'na' || $stream_lower == 'none') {
                    $stream = ''; // Ignore these
                } else if ($stream_lower == 'science maths' || $stream_lower == 'pcm' || $stream_lower == 'science pcm') {
                    $stream = 'Science(PCM)';
                } else if ($stream_lower == 'science bio' || $stream_lower == 'pcb' || $stream_lower == 'science pcb') {
                    $stream = 'Science(PCB)';
                } else if ($stream_lower == 'arts' || $stream_lower == 'art') {
                    $stream = 'Arts';
                } else if ($stream_lower == 'commerce') {
                    $stream = 'Commerce';
                }

                if ($stream != '') {
                    $student_data['class_name'] = trim($student_data['class_name']) . ' - ' . $stream;
                }
            }

            // Validate Class
            $class_id = 0;
            if (isset($student_data['class_name']) && $student_data['class_name'] != '') {
                $c_name = strtolower(trim($student_data['class_name']));
                if (isset($class_map[$c_name])) {
                    $class_id = $class_map[$c_name];
                    $student_data['class_id'] = $class_id;
                } else {
                    $stream_mapped = isset($student_data['stream_name']) ? ($student_data['stream_name'] == '' ? 'mapped but empty in CSV' : 'mapped with value: '.$student_data['stream_name']) : 'NOT mapped in Step 2';
                    $row_errors[] = "Class '".$student_data['class_name']."' not found. (Debug: Stream was $stream_mapped)";
                }
            } else {
                $row_errors[] = "Class is required.";
            }

            // Validate Section
            $section_id = 0;
            if ($class_id > 0 && isset($student_data['section_name']) && $student_data['section_name'] != '') {
                $sections = $this->section_model->getClassBySection($class_id);
                $sec_found = false;
                foreach($sections as $sec) {
                    if (strtolower(trim($sec['section'])) == strtolower(trim($student_data['section_name']))) {
                        $section_id = $sec['section_id'];
                        $sec_found = true;
                        break;
                    }
                }
                if (!$sec_found) {
                    $row_errors[] = "Section '".$student_data['section_name']."' not found in Class '".$student_data['class_name']."'.";
                }
            } else if ($class_id > 0) {
                 $row_errors[] = "Section is required.";
            }

            // Map Category
            if (isset($student_data['category']) && $student_data['category'] != '') {
                $cat_name = strtolower(trim($student_data['category']));
                if (isset($category_map[$cat_name])) {
                    $student_data['category_id'] = $category_map[$cat_name];
                } else {
                    $student_data['category_id'] = 0;
                }
            } else {
                $student_data['category_id'] = 0;
            }

            // Map House
            if (isset($student_data['school_house']) && $student_data['school_house'] != '') {
                $h_name = strtolower(trim($student_data['school_house']));
                if (isset($house_map[$h_name])) {
                    $student_data['school_house_id'] = $house_map[$h_name];
                } else {
                    $student_data['school_house_id'] = 0;
                }
            } else {
                $student_data['school_house_id'] = 0;
            }

            if (!isset($student_data['firstname']) || $student_data['firstname'] == '') {
                $row_errors[] = "First Name (or Student Name) is required.";
            }

            if (!isset($student_data['admission_no']) || $student_data['admission_no'] == '') {
                $row_errors[] = "Admission No (SR No) is required.";
            } else {
                if ($this->student_model->check_adm_exists($student_data['admission_no'])) {
                    $row_errors[] = "Admission No '".$student_data['admission_no']."' already exists.";
                }
            }

            if (empty($row_errors)) {
                $perfect_matches++;
                $status = 'valid';
            } else {
                $error_matches++;
                $status = 'error';
            }

            $parsed_data[] = array(
                'row_num' => $row_num,
                'raw_row' => $row,
                'student_data' => $student_data,
                'class_id' => $class_id,
                'section_id' => $section_id,
                'custom_value_array' => $custom_value_array,
                'status' => $status,
                'errors' => $row_errors
            );
        }

        $data['parsed_data'] = $parsed_data;
        $data['perfect_matches'] = $perfect_matches;
        $data['error_matches'] = $error_matches;
        $data['file_name'] = $file_name;
        $data['mapping'] = $mapping;
        
        $data['title'] = 'Preview Data';
        $this->load->view('layout/header', $data);
        $this->load->view('admin/customstudentimport/preview', $data);
        $this->load->view('layout/footer', $data);
    }

    public function confirm()
    {
        if (!$this->rbac->hasPrivilege('import_student', 'can_view')) {
            access_denied();
        }

        // Increase time and memory limit for large imports
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $parsed_data_json = $this->input->post('parsed_data');
        $file_name = $this->input->post('file_name');
        
        $parsed_data = json_decode($parsed_data_json, true);

        if (empty($parsed_data)) {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">No valid data to import.</div>');
            redirect('admin/customstudentimport/index');
        }

        $session = $this->setting_model->getCurrentSession();
        $imported_count = 0;

        foreach ($parsed_data as $row) {
            if ($row['status'] == 'valid') {
                $student_data = $row['student_data'];
                $student_data['is_active'] = 'yes';
                
                // Formatting dates
                if (isset($student_data['dob']) && $student_data['dob'] != '') {
                    $student_data['dob'] = date('Y-m-d', strtotime($student_data['dob']));
                }
                if (isset($student_data['admission_date']) && $student_data['admission_date'] != '') {
                    $student_data['admission_date'] = date('Y-m-d', strtotime($student_data['admission_date']));
                }
                if (isset($student_data['measurement_date']) && $student_data['measurement_date'] != '') {
                    $student_data['measurement_date'] = date('Y-m-d', strtotime($student_data['measurement_date']));
                }

                // Add default values for NOT NULL columns in MySQL strict mode
                $student_data['parent_id'] = 0;
                if (!isset($student_data['blood_group'])) $student_data['blood_group'] = '';
                if (!isset($student_data['guardian_is'])) $student_data['guardian_is'] = '';
                if (!isset($student_data['guardian_occupation'])) $student_data['guardian_occupation'] = '';
                if (!isset($student_data['father_pic'])) $student_data['father_pic'] = '';
                if (!isset($student_data['mother_pic'])) $student_data['mother_pic'] = '';
                if (!isset($student_data['guardian_pic'])) $student_data['guardian_pic'] = '';
                if (!isset($student_data['height'])) $student_data['height'] = '';
                if (!isset($student_data['weight'])) $student_data['weight'] = '';
                if (!isset($student_data['dis_reason'])) $student_data['dis_reason'] = 0;
                if (!isset($student_data['dis_note'])) $student_data['dis_note'] = '';

                // Add to students
                $data_setting = array();
                $data_setting['id'] = $this->sch_setting_detail->id;
                // Force NO auto insert since user is providing Admission No
                $data_setting['adm_auto_insert'] = 0;
                $data_setting['adm_update_status'] = $this->sch_setting_detail->adm_update_status;

                // Remove fields that do not exist in the 'students' table to prevent MySQL strict mode errors
                unset($student_data['class_id']);
                unset($student_data['class_name']);
                unset($student_data['section_name']);
                unset($student_data['stream_name']);
                unset($student_data['category']);
                unset($student_data['school_house']);

                $insert_id = $this->student_model->add($student_data, $data_setting);
                
                // --- DEBUGGING ---
                $db_err = $this->db->error();
                if (!empty($db_err) && !empty($db_err['code'])) {
                    log_message('error', 'CUSTOM IMPORT DB ERROR: ' . json_encode($db_err) . ' | DATA: ' . json_encode($student_data));
                }
                // -----------------

                if (!empty($insert_id)) {
                    // Insert Custom Fields manually
                    if (!empty($row['custom_value_array'])) {
                        $this->customfield_model->insertRecord($row['custom_value_array'], $insert_id);
                    }

                    $this->saasvalidation->updateResouceQuota('no_of_student', 1);

                    // Student Session
                    $data_new = array(
                        'student_id' => $insert_id,
                        'class_id'   => $row['class_id'],
                        'section_id' => $row['section_id'],
                        'session_id' => $session,
                    );
                    $this->student_model->add_student_session($data_new);

                    // User Creation (Student)
                    $user_password = $this->role->get_random_password(6, 6, false, true, false);
                    $data_student_login = array(
                        'username' => 'std' . $insert_id,
                        'password' => $user_password,
                        'user_id'  => $insert_id,
                        'role'     => 'student',
                    );
                    $this->user_model->add($data_student_login);

                    // User Creation (Parent)
                    $parent_password = $this->role->get_random_password(6, 6, false, true, false);
                    $data_parent_login = array(
                        'username' => 'prt' . $insert_id,
                        'password' => $parent_password,
                        'user_id'  => $insert_id,
                        'role'     => 'parent',
                        'childs'   => $insert_id,
                    );
                    $ins_id = $this->user_model->add($data_parent_login);

                    $update_student = array(
                        'id'        => $insert_id,
                        'parent_id' => $ins_id,
                    );
                    $this->student_model->add($update_student);
                    
                    $imported_count++;
                }
            }
        }

        // Clean up temp file
        if (file_exists('./uploads/temp/' . $file_name)) {
            unlink('./uploads/temp/' . $file_name);
        }

        $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">' . $imported_count . ' ' . $this->lang->line('records_imported_successfully') . '</div>');
        redirect('admin/customstudentimport/index');
    }
}
?>
