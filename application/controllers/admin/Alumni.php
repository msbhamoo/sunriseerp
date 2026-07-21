<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Alumni extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->sch_setting_detail = $this->setting_model->getSetting();
        $this->load->library('media_storage');
        $this->config->load('app-config');
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->load->library('encoding_lib');
		$this->load->library('SaasValidation');
    }

    public function alumnilist()
    {
        if (!$this->rbac->hasPrivilege('manage_alumni', 'can_view')) {
            access_denied();
        }
        $data                = array();
        $data['sessionlist'] = $this->session_model->get();
        $this->session->set_userdata('top_menu', 'alumni');
        $this->session->set_userdata('sub_menu', 'alumni/alumnilist');
        $class             = $this->class_model->get();
        $data['classlist'] = $class;

        $data['title']  = $this->lang->line('alumni_student');
        $carray         = array();
        $alumni_studets = array();
        $alumni_student = $this->alumni_model->get();

        $education_details = array();
        $work_details      = array();
        foreach ($alumni_student as $key => $value) {
            $alumni_studets[$value['student_id']]    = $value;
            $education_details[$value['student_id']] = $this->alumni_model->get_education($value['student_id']);
            $work_details[$value['student_id']]      = $this->alumni_model->get_work_experience($value['student_id']);
        }
        $data['alumni_studets']    = $alumni_studets;
        $data['education_details'] = $education_details;
        $data['work_details']      = $work_details;

        if (!empty($data["classlist"])) {
            foreach ($data["classlist"] as $ckey => $cvalue) {
                $carray[] = $cvalue["id"];
            }
        }

        $data['sch_setting'] = $this->sch_setting_detail;

        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $data['resultlist'] = $this->student_model->search_alumniStudent();
        } else {
            $class              = $this->input->post('class_id');
            $section            = $this->input->post('section_id');
            $search             = $this->input->post('search');
            $search_text        = $this->input->post('search_text');
            $data['session_id'] = $session_id = $this->input->post('session_id');
            if (isset($search)) {
                if ($search == 'search_filter') {
                    $this->form_validation->set_rules('session_id', $this->lang->line('session'), 'trim|required|xss_clean');
                    $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
                    if ($this->form_validation->run() == false) {
                        $data['resultlist'] = $this->student_model->search_alumniStudent();
                    } else {
                        $data['searchby']    = "filter";
                        $data['class_id']    = $this->input->post('class_id');
                        $data['section_id']  = $this->input->post('section_id');
                        $data['search_text'] = $this->input->post('search_text');
                        $resultlist          = $this->student_model->search_alumniStudent($class, $section, $session_id);
                        $data['resultlist']  = $resultlist;
                    }
                } else if ($search == 'search_full') {
                    $data['searchby'] = "text";

                    $data['search_text'] = trim($this->input->post('search_text'));
                    $resultlist          = $this->student_model->search_alumniStudentbyAdmissionNo($search_text, $carray);
                    $data['resultlist']  = $resultlist;
                }
            } else {
                $data['resultlist'] = $this->student_model->search_alumniStudent();
            }
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/alumni/alumnilist', $data);
        $this->load->view('layout/footer', $data);
    }

    public function get_alumnidetails()
    {
        $student_id = $_POST['student_id'];
        $data       = $this->alumni_model->get_alumnidetail($student_id);

        if (empty($data)) {

            $data = array(
                'id'              => '',
                'current_email'   => '',
                'current_phone'   => '',
                'occupation'      => '',
                'address'         => '',
                'show_on_website' => 0,
                'student_id'      => $student_id);
        } else {
            $data['show_on_website'] = isset($data['show_on_website']) ? $data['show_on_website'] : 0;
        }

        $data['education_list'] = $this->alumni_model->get_education($student_id);
        $data['work_list']      = $this->alumni_model->get_work_experience($student_id);

        echo json_encode($data);
    }

    public function change_show_on_website()
    {
        $student_id      = $this->input->post('student_id');
        $show_on_website = $this->input->post('show_on_website');
        
        $alumni_detail = $this->alumni_model->get_alumnidetail($student_id);
        if (!empty($alumni_detail)) {
            $update_data = array(
                'id'              => $alumni_detail['id'],
                'student_id'      => $student_id,
                'show_on_website' => $show_on_website
            );
            $this->alumni_model->add($update_data);
        } else {
            $insert_data = array(
                'student_id'      => $student_id,
                'current_phone'   => '',
                'current_email'   => '',
                'occupation'      => '',
                'address'         => '',
                'show_on_website' => $show_on_website
            );
            $this->alumni_model->add($insert_data);
        }
        echo json_encode(array('status' => 'success', 'message' => $this->lang->line('success_message')));
    }

    public function story($student_id = null)
    {
        if (!$student_id) {
            show_404();
        }

        $data['student']        = $this->student_model->get($student_id);
        if (empty($data['student'])) {
            show_404();
        }

        $data['alumni_detail']  = $this->alumni_model->get_alumnidetail($student_id);
        $data['story']          = $this->alumni_model->get_story($student_id);
        $data['education_list'] = $this->alumni_model->get_education($student_id);
        $data['work_list']      = $this->alumni_model->get_work_experience($student_id);
        $data['sch_setting']    = $this->sch_setting_detail;
        $data['title']          = 'Alumni Success Story';

        $this->load->view('layout/header', $data);
        $this->load->view('admin/alumni/story_view', $data);
        $this->load->view('layout/footer', $data);
    }

    public function get_story_details()
    {
        $student_id = $this->input->post('student_id');
        $story      = $this->alumni_model->get_story($student_id);

        if (empty($story)) {
            $student  = $this->student_model->get($student_id);
            $fullname = $student ? $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname) : '';
            $story    = array(
                'id'                   => '',
                'student_id'           => $student_id,
                'badge_text'           => 'CLASS OF ' . date('Y'),
                'subtitle'             => '',
                'story_intro'          => '',
                'higher_edu_summary'   => '',
                'location_summary'     => '',
                'section1_title'       => 'The Sunrise Foundation',
                'section1_content'     => '',
                'quote_text'           => '',
                'quote_author'         => '— ' . $fullname,
                'section2_title'       => 'Going Above and Beyond',
                'section2_content'     => '',
                'is_published'         => 1,
            );
        }

        echo json_encode($story);
    }

    public function save_story()
    {
        $this->form_validation->set_rules('student_id', $this->lang->line('student'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $array = array('status' => 'fail', 'error' => array('student_id' => form_error('student_id')));
        } else {
            $data = array(
                'student_id'         => $this->input->post('student_id'),
                'badge_text'         => $this->input->post('badge_text'),
                'subtitle'           => $this->input->post('subtitle'),
                'story_intro'        => $this->input->post('story_intro'),
                'higher_edu_summary' => $this->input->post('higher_edu_summary'),
                'location_summary'   => $this->input->post('location_summary'),
                'section1_title'     => $this->input->post('section1_title'),
                'section1_content'   => $this->input->post('section1_content'),
                'quote_text'         => $this->input->post('quote_text'),
                'quote_author'       => $this->input->post('quote_author'),
                'section2_title'     => $this->input->post('section2_title'),
                'section2_content'   => $this->input->post('section2_content'),
                'is_published'       => $this->input->post('is_published') ? 1 : 0,
            );
            $this->alumni_model->save_story($data);
            $array = array('status' => 'success', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function create_new_alumni()
    {
        $this->form_validation->set_rules('firstname', $this->lang->line('first_name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('session_id', $this->lang->line('pass_out_session'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id', $this->lang->line('class'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('section_id', $this->lang->line('section'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('current_email', $this->lang->line('current_email'), 'trim|required|valid_email|xss_clean');
        
        if ($this->form_validation->run() == false) {
            $msg = array(
                'firstname' => form_error('firstname'),
                'session_id' => form_error('session_id'),
                'class_id' => form_error('class_id'),
                'section_id' => form_error('section_id'),
                'current_email' => form_error('current_email'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            // Auto-generate Admission No if required
            $admission_no = $this->input->post('admission_no');
            if (empty($admission_no)) {
                $last_student = $this->student_model->lastRecord();
                $last_admission_digit = str_replace($this->sch_setting_detail->adm_prefix, "", $last_student->admission_no);
                $admission_no = $this->sch_setting_detail->adm_prefix . sprintf("%0" . $this->sch_setting_detail->adm_no_digit . "d", $last_admission_digit + 1);
            }

            // 1. Insert into students (isolated from active via is_active = 'no')
            $student_data = array(
                'admission_no' => $admission_no,
                'firstname' => $this->input->post('firstname'),
                'lastname' => $this->input->post('lastname'),
                'gender' => $this->input->post('gender'),
                'dob' => $this->customlib->dateFormatToYYYYMMDD($this->input->post('dob')),
                'mobileno' => $this->input->post('current_phone'),
                'email' => $this->input->post('current_email'),
                'is_active' => 'no' // Prevent appearing in active lists
            );
            $data_setting = array();
            $data_setting['id'] = $this->sch_setting_detail->id;
            $data_setting['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
            $data_setting['adm_update_status'] = $this->sch_setting_detail->adm_update_status;

            $insert_id = $this->student_model->add($student_data, $data_setting);

            // 2. Insert into student_session (is_alumni = 1)
            $session_data = array(
                'student_id' => $insert_id,
                'session_id' => $this->input->post('session_id'),
                'class_id' => $this->input->post('class_id'),
                'section_id' => $this->input->post('section_id'),
                'is_alumni' => 1,
                'is_active' => 'no'
            );
            $this->db->insert('student_session', $session_data);

            // 3. Insert into alumni_students
            $alumni_data = array(
                'student_id' => $insert_id,
                'current_email' => $this->input->post('current_email'),
                'current_phone' => $this->input->post('current_phone'),
                'occupation' => $this->input->post('occupation'),
                'address' => $this->input->post('address'),
                'show_on_website' => 0
            );
            $this->alumni_model->add($alumni_data);

            $array = array('status' => 'success', 'message' => $this->lang->line('success_message'));
        }
        echo json_encode($array);
    }

    public function import()
    {
        if (!$this->rbac->hasPrivilege('manage_alumni', 'can_add')) {
            access_denied();
        }
        
        $data['title'] = 'Import Alumni';
        $fields = array('admission_no', 'firstname', 'lastname', 'gender', 'dob', 'passout_session', 'class', 'section', 'current_email', 'current_phone', 'occupation', 'address');
        $data["fields"] = $fields;

        $this->form_validation->set_rules('file', $this->lang->line('file'), 'callback_handle_csv_upload');

        if ($this->form_validation->run() == true) {
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
                if ($ext == 'csv') {
                    $file = $_FILES['file']['tmp_name'];
                    $this->load->library('CSVReader');
                    $result = $this->csvreader->parse_file($file);
                    
                    if (!empty($result)) {
                        $rowcount = 0;
                        for ($i = 1; $i <= count($result); $i++) {
                            
                            $admission_no = trim($this->encoding_lib->toUTF8($result[$i]['admission_no']));
                            if (empty($admission_no)) {
                                $last_student = $this->student_model->lastRecord();
                                $last_admission_digit = str_replace($this->sch_setting_detail->adm_prefix, "", $last_student->admission_no);
                                $admission_no = $this->sch_setting_detail->adm_prefix . sprintf("%0" . $this->sch_setting_detail->adm_no_digit . "d", $last_admission_digit + 1);
                            }
                            
                            $session_name = trim($this->encoding_lib->toUTF8($result[$i]['passout_session']));
                            $class_name = trim($this->encoding_lib->toUTF8($result[$i]['class']));
                            $section_name = trim($this->encoding_lib->toUTF8($result[$i]['section']));
                            
                            // Map Text to IDs
                            $session_obj = $this->db->select('id')->where('session', $session_name)->get('sessions')->row();
                            $class_obj = $this->db->select('id')->where('class', $class_name)->get('classes')->row();
                            $section_obj = $this->db->select('id')->where('section', $section_name)->get('sections')->row();

                            if (empty($session_obj) || empty($class_obj) || empty($section_obj)) {
                                // Skip row if mapping fails to maintain zero-mistake isolation
                                continue; 
                            }

                            $student_data = array(
                                'admission_no' => $admission_no,
                                'firstname' => trim($this->encoding_lib->toUTF8($result[$i]['firstname'])),
                                'lastname' => trim($this->encoding_lib->toUTF8($result[$i]['lastname'])),
                                'gender' => trim($this->encoding_lib->toUTF8($result[$i]['gender'])),
                                'dob' => date('Y-m-d', strtotime($result[$i]['dob'])),
                                'mobileno' => trim($this->encoding_lib->toUTF8($result[$i]['current_phone'])),
                                'email' => trim($this->encoding_lib->toUTF8($result[$i]['current_email'])),
                                'is_active' => 'no' // Isolated
                            );

                            if ($this->student_model->check_adm_exists($admission_no)) {
                                continue;
                            }
                            
                            $data_setting = array();
                            $data_setting['id'] = $this->sch_setting_detail->id;
                            $data_setting['adm_auto_insert'] = $this->sch_setting_detail->adm_auto_insert;
                            $data_setting['adm_update_status'] = $this->sch_setting_detail->adm_update_status;

                            $insert_id = $this->student_model->add($student_data, $data_setting);

                            if (!empty($insert_id)) {
                                $session_data = array(
                                    'student_id' => $insert_id,
                                    'session_id' => $session_obj->id,
                                    'class_id' => $class_obj->id,
                                    'section_id' => $section_obj->id,
                                    'is_alumni' => 1,
                                    'is_active' => 'no'
                                );
                                $this->db->insert('student_session', $session_data);

                                $alumni_data = array(
                                    'student_id' => $insert_id,
                                    'current_email' => trim($this->encoding_lib->toUTF8($result[$i]['current_email'])),
                                    'current_phone' => trim($this->encoding_lib->toUTF8($result[$i]['current_phone'])),
                                    'occupation' => trim($this->encoding_lib->toUTF8($result[$i]['occupation'])),
                                    'address' => trim($this->encoding_lib->toUTF8($result[$i]['address'])),
                                    'show_on_website' => 0
                                );
                                $this->alumni_model->add($alumni_data);
                                $rowcount++;
                            }
                        }
                        $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">' . $this->lang->line('total') . ' ' . count($result) . ' ' . $this->lang->line('records_found_in_csv_file_total') . ' ' . $rowcount . ' ' . $this->lang->line('records_imported_successfully') . '</div>');
                        redirect('admin/alumni/alumnilist');
                    }
                } else {
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">' . $this->lang->line('please_upload_csv_file_only') . '</div>');
                }
            }
        }
        
        $this->load->view('layout/header');
        $this->load->view('admin/alumni/import', $data);
        $this->load->view('layout/footer');
    }

    public function exportformat()
    {
        $this->load->helper('download');
        $filepath = "./uploads/alumni_import_sample.csv";
        $data = "admission_no,firstname,lastname,gender,dob,passout_session,class,section,current_email,current_phone,occupation,address\n";
        $data .= ",Parmod,Kumar,Male,2000-01-01,2012-13,Class 12,A,parmod@gmail.com,9876543210,Founder,London UK";
        
        $name = 'alumni_import_sample.csv';
        force_download($name, $data);
    }

    public function handle_csv_upload()
    {
        $error = "";
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
            $allowedExts = array('csv');
            $mimes = array('text/csv', 'text/plain', 'application/csv', 'text/comma-separated-values', 'application/excel', 'application/vnd.ms-excel', 'application/vnd.msexcel', 'text/anytext', 'application/octet-stream', 'application/txt');
            $temp = explode(".", $_FILES["file"]["name"]);
            $extension = end($temp);
            if ($_FILES["file"]["error"] > 0) {
                $error .= "Error opening the file<br />";
            }
            if (!in_array($_FILES['file']['type'], $mimes)) {
                $error .= "Error opening the file<br />";
                $this->form_validation->set_message('handle_csv_upload', $this->lang->line('file_type_not_allowed'));
                return false;
            }
            if (!in_array($extension, $allowedExts)) {
                $error .= "Error opening the file<br />";
                $this->form_validation->set_message('handle_csv_upload', $this->lang->line('extension_not_allowed'));
                return false;
            }
            if ($error == "") {
                return true;
            }
        } else {
            $this->form_validation->set_message('handle_csv_upload', $this->lang->line('please_select_file'));
            return false;
        }
    }

    public function add()
    {
        $this->form_validation->set_rules('current_phone', $this->lang->line('current_phone'), 'trim|required|xss_clean');
		
		$storage_array = "documents"; // use comma for multiple files	 

        $this->form_validation->set_rules('validate_storage', $this->lang->line('storage'), "callback_validateCanUploadFile[$storage_array]");	
		
        $this->form_validation->set_rules('documents', $this->lang->line('image'), 'callback_handle_upload[documents]');
		
		

        if ($this->form_validation->run() == false) {

            $msg = array(
                'current_phone' => form_error('current_phone'),
                'documents'          => form_error('documents'),
                'validate_storage'          => form_error('validate_storage'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
			try {
				
				$total_documents_failed_size = 0;
                $storage_array = ['documents'];
                $this->saasvalidation->updateStorageLimit('storage', $storage_array); 
				
				
				$img_name = $this->media_storage->fileupload("documents", "./uploads/alumni_student_images/");
				
				if (IsNullOrEmptyString($img_name)) {  // check upload image has not uploaded successfully

                    $total_documents_failed_size += $this->media_storage->getTmpFileSize('documents');  // get temp size of image because of image not uploaded 
                }

                if ($total_documents_failed_size > 0) {
                    $this->saasvalidation->deleteResouceQuota('storage', $total_documents_failed_size);
                }
	
				$data = array(
					'current_email'   => $this->input->post('current_email'),
					'current_phone'   => $this->input->post('current_phone'),
					'occupation'      => $this->input->post('occupation'),
					'address'         => $this->input->post('address'),
					'show_on_website' => $this->input->post('show_on_website') ? 1 : 0,
					'student_id'      => $this->input->post('student_id'),
					'id'              => $this->input->post('id'),
				);
	
				if ($this->input->post('id') == '') {
	
					$data['photo'] = $img_name;
					$this->alumni_model->add($data);
				} else {
	
					$alumni_data = $this->alumni_model->get_alumnidetail($this->input->post('student_id'));
	
					if (isset($_FILES["documents"]) && $_FILES['documents']['name'] != '' && (!empty($_FILES['documents']['name']))) {
	
						$update_img_name = $img_name;
	
					} else {
	
						$update_img_name = $alumni_data['photo'];
					}
	
					$data['photo'] = $update_img_name;
	
					$this->alumni_model->add($data);
				}

                $student_id = $this->input->post('student_id');

                // Save multiple education entries
                $edu_levels      = $this->input->post('edu_level');
                $degree_names    = $this->input->post('degree_name');
                $college_names   = $this->input->post('college_name');
                $college_types   = $this->input->post('college_type');
                $study_locations = $this->input->post('study_location');
                $country_names   = $this->input->post('country_name');
                $passout_years   = $this->input->post('passout_year');

                $education_batch = array();
                if (!empty($edu_levels) && is_array($edu_levels)) {
                    foreach ($edu_levels as $idx => $level) {
                        if (!empty($level) || !empty($degree_names[$idx]) || !empty($college_names[$idx])) {
                            $education_batch[] = array(
                                'student_id'      => $student_id,
                                'education_level' => trim($level),
                                'degree_name'     => isset($degree_names[$idx]) ? trim($degree_names[$idx]) : '',
                                'college_name'    => isset($college_names[$idx]) ? trim($college_names[$idx]) : '',
                                'college_type'    => isset($college_types[$idx]) ? trim($college_types[$idx]) : 'Government',
                                'study_location'  => isset($study_locations[$idx]) ? trim($study_locations[$idx]) : 'National',
                                'country_name'    => isset($country_names[$idx]) ? trim($country_names[$idx]) : '',
                                'passout_year'    => isset($passout_years[$idx]) ? trim($passout_years[$idx]) : '',
                            );
                        }
                    }
                }
                $this->alumni_model->save_education($student_id, $education_batch);

                // Save multiple work/career entries
                $work_types         = $this->input->post('work_type');
                $organization_names = $this->input->post('organization_name');
                $designations       = $this->input->post('designation');
                $joining_dates      = $this->input->post('joining_date');
                $completion_dates   = $this->input->post('completion_date');
                $is_currents        = $this->input->post('is_current');
                $locations          = $this->input->post('work_location');

                $work_batch = array();
                if (!empty($work_types) && is_array($work_types)) {
                    foreach ($work_types as $widx => $wtype) {
                        if (!empty($wtype) || !empty($organization_names[$widx])) {
                            $j_date = (!empty($joining_dates[$widx])) ? date('Y-m-d', $this->customlib->datetostrtotime($joining_dates[$widx])) : null;
                            $c_date = (!empty($completion_dates[$widx])) ? date('Y-m-d', $this->customlib->datetostrtotime($completion_dates[$widx])) : null;
                            $is_curr = isset($is_currents[$widx]) && $is_currents[$widx] == '1' ? 1 : 0;

                            $work_batch[] = array(
                                'student_id'        => $student_id,
                                'work_type'         => trim($wtype),
                                'organization_name' => isset($organization_names[$widx]) ? trim($organization_names[$widx]) : '',
                                'designation'       => isset($designations[$widx]) ? trim($designations[$widx]) : '',
                                'joining_date'      => $j_date,
                                'completion_date'   => $c_date,
                                'is_current'        => $is_curr,
                                'location'          => isset($locations[$widx]) ? trim($locations[$widx]) : '',
                            );
                        }
                    }
                }
                $this->alumni_model->save_work_experience($student_id, $work_batch);

				$array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
			
			 } catch (Exception $e) {
                 
                $array = array('status' => 'fail', 'error' =>  array('exception' => $e->getMessage()), 'message' => '');
				
            }
        }

        echo json_encode($array);
    }

    public function events()
    {
        if (!$this->rbac->hasPrivilege('events', 'can_view')) {
            access_denied();
        }
        $data['title']       = 'Event List';
        $data['sessionlist'] = $this->session_model->get();
        $eventlist           = $this->alumni_model->getevents();

        foreach ($eventlist as $key => $class) {
            $eventclass[$key]   = '';
            $eventsection[$key] = '';
            $eventsession[$key] = '';
            if (!empty($class['class_id'])) {
                $eventclasslist     = $this->class_model->getAll($class['class_id']);
                $eventclass[$key]   = $eventclasslist['class'];
                $eventsection[$key] = $this->class_model->get_section($class['class_id']);
                $sessionlist        = $this->session_model->get($class['session_id']);
                $eventsession[$key] = $sessionlist['session'];
            }
        }

        $data['eventlist'] = $eventlist;
        if (!empty($eventclass)) {
            $data['eventclass'] = $eventclass;
        }
        if (!empty($eventsection)) {
            $data['eventsection'] = $eventsection;
        }
        if (!empty($eventsession)) {
            $data['eventsession'] = $eventsession;
        }

        $data['classlist'] = $this->class_model->get();        
        $language      = $this->customlib->getLanguage();        
        $data['language_name'] = $language["short_code"];
        $this->session->set_userdata('top_menu', 'alumni');
        $this->session->set_userdata('sub_menu', 'alumni/event');
        $this->load->view("layout/header.php");
        $this->load->view("admin/alumni/events", $data); 
        $this->load->view("layout/footer.php");
    }
	
	public function validateCanUploadFile($str, $params_string)
    {
        $params_array = array_map('trim', explode(',', $params_string));
        return $this->saasvalidation->validateCanUploadFile($str, $params_array);
    }
	
    public function add_event()
    {
        $this->form_validation->set_rules('event_title', $this->lang->line('event_title'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('from_date', $this->lang->line("event_from_date"), 'trim|required|xss_clean');
        $this->form_validation->set_rules('to_date', $this->lang->line("event_to_date"), 'trim|required|xss_clean');
        $this->form_validation->set_rules('file', $this->lang->line('image'), 'callback_handle_upload[file]');
		
		$storage_array = "file"; // use comma for multiple files
        $this->form_validation->set_rules('validate_storage', $this->lang->line('storage'), "callback_validateCanUploadFile[$storage_array]");	
		

        $studentclass = $this->input->post('event_for');
        if ($studentclass == 'class') {
            $this->form_validation->set_rules('session_id', $this->lang->line("pass_out_session"), 'trim|required|xss_clean');
            $this->form_validation->set_rules('class_id', $this->lang->line("class"), 'trim|required|xss_clean');
            $this->form_validation->set_rules('user[]', $this->lang->line("section"), 'trim|required|xss_clean');
        }

        if ($this->form_validation->run() == false) {
            $msg = array(
                'event_title' => form_error('event_title'),
                'from_date'   => form_error('from_date'),
                'to_date'     => form_error('to_date'),
                'file'        => form_error('file'),
                'validate_storage'        => form_error('validate_storage'),
            );
            if ($studentclass == 'class') {
                $msg1 = array(
                    'class_id'   => form_error('class_id'),
                    'user'       => form_error('user[]'),
                    'session_id' => form_error('session_id'),
                );
            }
            if (!empty($msg1)) {
                $error_msg = array_merge($msg, $msg1);
            } else {
                $error_msg = $msg;
            }

            $array = array('status' => 'fail', 'error' => $error_msg, 'message' => '');
        } else {
             
				$total_documents_failed_size = 0;
                $storage_array = ['file'];
                $this->saasvalidation->updateStorageLimit('storage', $storage_array);
				

			$event_starting_date = date('Y-m-d H:i:s',$this->customlib->dateTimeformatTwentyfourhour($this->input->post('from_date')." 00:00:00",true));
			$event_end_date = date('Y-m-d H:i:s',$this->customlib->dateTimeformatTwentyfourhour($this->input->post('to_date')." 23:59:00",true));
       
            $event_for  =   $this->input->post('event_for');
            
            if($event_for != 'all'){
                $session_id =   $this->input->post('session_id');
                $class_id =   $this->input->post('class_id');                
            }else{
                $session_id =   NULL;
                $class_id =   NULL;                
            }          
            
            $data = array(                
                'id'                      => $this->input->post('id'),
                'title'                      => $this->input->post('event_title'),
                'event_for'                  => $this->input->post('event_for'),
                'session_id'                 => $session_id,
                'class_id'                   => $class_id,
                'section'                    => json_encode($this->input->post('user')),
                'from_date'                  => $event_starting_date,
                'to_date'                    => $event_end_date,
                'note'                       => $this->input->post('note'),
                'event_notification_message' => $this->input->post('event_notification_message'),
            );

            $img_name="";
            $file_name="";

            if ($this->input->post('id') == '') {
                if (isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {
                    $img_name = $this->media_storage->fileupload("file", "./uploads/alumni_event_images/");				
					 
			
					if (IsNullOrEmptyString($img_name)) {  // check upload image has not uploaded successfully
	
						$total_documents_failed_size += $this->media_storage->getTmpFileSize('file');  // get temp size of image because of image not uploaded 
					}
	
					if ($total_documents_failed_size > 0) {
						$this->saasvalidation->deleteResouceQuota('storage', $total_documents_failed_size);
					}			
						
						
                    $file_name = $_FILES['file']['name'];
                }
              
            } else {
				
				$prev_file_size = 0;
				$total_image_upload_size = 0;
				
                $event_list = $this->alumni_model->get_eventbyid($this->input->post('id'));

                if (isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {
					
					$prev_file_size = $this->media_storage->getUploadedFileSize($event_list['photo'], 'uploads/alumni_event_images');
					
                    $img_name = $this->media_storage->fileupload("file", "./uploads/alumni_event_images/");
					
						if (!IsNullOrEmptyString($img_name)) {
	
							$total_image_upload_size += $this->media_storage->getTmpFileSize('file');
						}
						
                    $file_name = $_FILES['file']['name'];
                } else {
                    $img_name = $event_list['photo'];
                    $file_name = $_FILES['file']['name'];
                    
                }
				
				if ($prev_file_size > $total_image_upload_size) {
						// Previous file was larger 
	
						$size_difference = $prev_file_size - $total_image_upload_size;
						$this->saasvalidation->deleteResouceQuota('storage', $size_difference);
				} elseif ($prev_file_size < $total_image_upload_size) {
						// New file is larger 
	
						$size_difference = $total_image_upload_size - $prev_file_size;
						$this->saasvalidation->updateResouceQuota('storage', $size_difference);
				} else {
						// File size unchanged → no quota adjustment needed	
				}
				
                if (isset($_FILES["file"]) && $_FILES['file']['name'] != '' && (!empty($_FILES['file']['name']))) {
                    if ($event_list['photo'] != '') {
                        $this->media_storage->filedelete($event_list['photo'], "uploads/alumni_event_images");                 
                        
                    }
                }
                
            }
			
            $data['photo'] = $img_name;
            $data['is_active'] = 0;

            $insert_id = $this->alumni_model->add_event($data);
          
            $email     = $this->input->post('email');
            $sms       = $this->input->post('sms');
            $subject   = $this->input->post('event_title');
            $body      = $this->input->post('event_notification_message');

            $email_value = 'no';
            $sms_value   = 'no';

            if ($email != '') {
                $email_value = 'yes';
            }
            if ($sms != '') {
                $sms_value = 'yes';
            }
            $send_new_attachments=[];
            $studentclass = $this->input->post('event_for');
            
            $template_id = $this->input->post('template_id');
            
            if($file_name != "" ){
                $send_new_attachments[] = array('directory' => 'uploads/alumni_event_images/', 'attachment' => $img_name, 'attachment_name' => $file_name);           
            }         
          
            
            if ($studentclass == 'class') {
                $usersection = $this->input->post('user');
                foreach ($usersection as $section) {
                    $alumniStudent = $this->alumni_model->alumniMail($this->input->post('class_id'), $this->input->post('session_id'), $section);
                    
                    foreach ($alumniStudent as $alumniStudent_value) {               
                          $sender_details = array('student_id' => $insert_id, 'contact_no' => $alumniStudent_value['current_phone'], 'email' => $alumniStudent_value['current_email'], 'email_value' => $email_value, 'sms_value' => $sms_value, 'subject' => $subject, 'body' => $body, 'from_date' => $this->input->post('from_date'), 'to_date' => $this->input->post('to_date'));

                        $this->mailsmsconf->mailsmsalumnistudent($sender_details,$template_id,$send_new_attachments);
                    }
                }
            } else {
                $alumniStudent = $this->alumni_model->get();
                foreach ($alumniStudent as $alumniStudent_value) {
        
                    $sender_details = array('student_id' => $insert_id, 'contact_no' => $alumniStudent_value['current_phone'], 'email' => $alumniStudent_value['current_email'], 'email_value' => $email_value, 'sms_value' => $sms_value, 'subject' => $subject, 'body' => $body, 'from_date' => $this->input->post('from_date'), 'to_date' => $this->input->post('to_date'));

                    $this->mailsmsconf->mailsmsalumnistudent($sender_details,$template_id,$send_new_attachments);
                }
            }

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }

        echo json_encode($array);
    }

    public function get_event($id)
    {
        $data              = $this->alumni_model->get_eventbyid($id);
        
        $data['from_date'] = $this->customlib->dateformat($data['from_date']);
        $data['to_date'] = $this->customlib->dateformat($data['to_date']);      
        
        if ($data['photo']) {
            $data['photo'] =   $this->media_storage->getImageURL('./uploads/alumni_event_images/' . $data['photo']);
        } else {
            $data['photo'] =   $this->media_storage->getImageURL('./uploads/no_image.png');
        }

        echo json_encode($data);
    }

    public function delete_event($id)
    {
        $row = $this->alumni_model->get_eventbyid($id);
        if ($row['photo'] != '') {
			$delete_file_size = $this->media_storage->getUploadedFileSize($row['photo'], 'uploads/alumni_event_images');
			$this->saasvalidation->deleteResouceQuota('storage', $delete_file_size);
            $this->media_storage->filedelete($row['photo'], "uploads/alumni_event_images/");
        }
        $this->alumni_model->delete_event($id);
    }

    public function getevent()
    {
        $start      = $this->input->get('start');
        $end        = $this->input->get('end');
        $event_data = $this->alumni_model->get_eventbydaterange($start, $end);
        foreach ($event_data as $key => $value) {

            $eventdata[] = array('title' => $value['title'],
                'start'                      => $value['from_date'],
                'end'                        => $value['to_date'],
                'description'                => $value['note'],
                'id'                         => $value['id'],
                'backgroundColor'            => '#27ab00',
                'borderColor'                => '#27ab00',
                'event_type'                 => 'Present',
            );
        }

        echo json_encode($eventdata);
    }

    public function deletestudent($id)
    {
        $this->alumni_model->deletestudent($id);
    }

    public function handle_upload($str, $var)
    {
        $image_validate = $this->config->item('image_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES[$var]) && !empty($_FILES[$var]['name'])) {
            $file_type         = $_FILES[$var]['type'];
            $file_size         = $_FILES[$var]["size"];
            $file_name         = $_FILES[$var]["name"];
            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->image_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->image_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = @getimagesize($_FILES[$var]['tmp_name'])) {

                if (!in_array($files['mime'], $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                    return false;
                }

                if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('extension_not_allowed'));
                    return false;
                }

                if ($file_size > $result->image_size) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($result->image_size / 1048576, 2) . " MB");
                    return false;
                }
            } else {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed_or_extension_not_allowed'));
                return false;
            }

            return true;
        }
        return true;
    }

}
