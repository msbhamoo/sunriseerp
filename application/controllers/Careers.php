<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Careers extends Front_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('job_posting_model');
        $this->load->model('job_application_model');
        $this->load->model('designation_model');
        $this->load->model('setting_model');
        $this->load->library('upload');
    }

    public function index()
    {
        $search          = $this->input->get('search');
        $designation_id  = $this->input->get('designation_id');

        $data['jobs']           = $this->job_posting_model->getActiveForWebsite(null, $search, $designation_id);
        $data['designations']   = $this->designation_model->get();
        $data['search']         = $search;
        $data['selected_desg']  = $designation_id;
        $data['school_setting'] = $this->setting_model->getSetting();

        $this->load->view('front/careers/index', $data);
    }

    public function detail($id)
    {
        $job = $this->job_posting_model->get($id);
        if (empty($job) || $job['is_active'] != 1) {
            echo json_encode(array('status' => 'fail', 'message' => 'Job posting not found or inactive'));
            return;
        }

        // Increment views counter atomically
        $this->job_posting_model->incrementViews($id);

        if (!empty($job['last_date'])) {
            $job['formatted_last_date'] = date($this->customlib->getSchoolDateFormat(), strtotime($job['last_date']));
        }
        echo json_encode($job);
    }

    public function apply()
    {
        $this->form_validation->set_rules('job_id', 'Job Position', 'required|numeric');
        $this->form_validation->set_rules('name', 'Full Name', 'trim|required|xss_clean');
        $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|xss_clean');
        $this->form_validation->set_rules('phone', 'Phone Number', 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'job_id' => form_error('job_id'),
                'name'   => form_error('name'),
                'email'  => form_error('email'),
                'phone'  => form_error('phone'),
            );
            echo json_encode(array('status' => 'fail', 'error' => $msg));
            return;
        }

        $job_id = $this->input->post('job_id');
        $job    = $this->job_posting_model->get($job_id);

        if (empty($job) || $job['is_active'] != 1) {
            echo json_encode(array('status' => 'fail', 'message' => 'This job posting is no longer active for applications.'));
            return;
        }

        if (isset($job['is_closed']) && $job['is_closed'] == 1) {
            echo json_encode(array('status' => 'fail', 'message' => 'Applications for this job position have been CLOSED.'));
            return;
        }

        $resume_file_path = null;

        // File upload handling for resume/CV
        if (isset($_FILES['resume_file']) && !empty($_FILES['resume_file']['name'])) {
            $upload_path = FCPATH . 'uploads/job_applications/resumes/';
            if (!file_exists($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            $config['upload_path']   = $upload_path;
            $config['allowed_types'] = 'pdf|doc|docx';
            $config['max_size']      = 5120; // 5MB
            $config['file_name']     = 'resume_' . time() . '_' . rand(1000, 9999);

            $this->upload->initialize($config);

            if (!$this->upload->do_upload('resume_file')) {
                $error = array('resume_file' => $this->upload->display_errors('', ''));
                echo json_encode(array('status' => 'fail', 'error' => $error));
                return;
            } else {
                $file_data        = $this->upload->data();
                $resume_file_path = 'uploads/job_applications/resumes/' . $file_data['file_name'];
            }
        }

        $app_no = 'APP-' . date('Y') . '-' . sprintf('%04d', rand(1000, 9999));

        $data = array(
            'job_id'           => $job_id,
            'application_no'   => $app_no,
            'name'             => $this->input->post('name'),
            'email'            => $this->input->post('email'),
            'phone'            => $this->input->post('phone'),
            'experience_years' => $this->input->post('experience_years'),
            'qualification'    => $this->input->post('qualification'),
            'cover_letter'     => $this->input->post('cover_letter'),
            'resume_file'      => $resume_file_path,
            'stage'            => 'Submitted',
        );

        $insert_id = $this->job_application_model->add($data);

        if ($insert_id) {
            echo json_encode(array(
                'status'         => 'success',
                'application_no' => $app_no,
                'message'        => 'Application submitted successfully! Your Reference ID is ' . $app_no
            ));
        } else {
            echo json_encode(array('status' => 'fail', 'message' => 'Failed to record application. Please try again.'));
        }
    }
}
