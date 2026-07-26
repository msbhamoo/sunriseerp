<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Jobposting extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('job_posting_model');
        $this->load->model('job_application_model');
        $this->load->model('designation_model');
        $this->load->model('department_model');
        $this->load->helper('download');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('job_posting', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/jobposting');

        $data['title']        = 'Job Posting Manager';
        $data['job_postings'] = $this->job_posting_model->get();
        $data['designations'] = $this->designation_model->get();
        $data['departments']  = $this->department_model->getDepartmentType();

        $this->load->view('layout/header');
        $this->load->view('admin/jobposting/index', $data);
        $this->load->view('layout/footer');
    }

    public function save()
    {
        $id = $this->input->post('job_id');
        if (empty($id)) {
            if (!$this->rbac->hasPrivilege('job_posting', 'can_add')) {
                access_denied();
            }
        } else {
            if (!$this->rbac->hasPrivilege('job_posting', 'can_edit')) {
                access_denied();
            }
        }

        $this->form_validation->set_rules('title', 'Job Title', 'trim|required|xss_clean');
        $this->form_validation->set_rules('designation_id', 'Designation', 'trim|required|xss_clean');
        $this->form_validation->set_rules('employment_type', 'Employment Type', 'trim|required|xss_clean');
        $this->form_validation->set_rules('vacancies', 'Vacancies', 'trim|required|numeric|xss_clean');
        $this->form_validation->set_rules('last_date', 'Last Date to Apply', 'trim|required|xss_clean');
        $this->form_validation->set_rules('job_description', 'Job Description', 'trim|required');

        if ($this->form_validation->run() == false) {
            $msg = array(
                'title'            => form_error('title'),
                'designation_id'   => form_error('designation_id'),
                'employment_type'  => form_error('employment_type'),
                'vacancies'        => form_error('vacancies'),
                'last_date'        => form_error('last_date'),
                'job_description'  => form_error('job_description'),
            );
            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            $raw_date  = $this->input->post('last_date');
            $last_date = $raw_date ? $this->customlib->dateFormatToYYYYMMDD($raw_date) : null;

            $data = array(
                'title'               => $this->input->post('title'),
                'designation_id'      => $this->input->post('designation_id'),
                'department'          => $this->input->post('department'),
                'employment_type'     => $this->input->post('employment_type'),
                'vacancies'           => $this->input->post('vacancies'),
                'location'            => $this->input->post('location'),
                'experience_required' => $this->input->post('experience_required'),
                'educational_level'   => $this->input->post('educational_level'),
                'role_overview'       => $this->input->post('role_overview'),
                'job_description'     => $this->input->post('job_description'),
                'benefits'            => $this->input->post('benefits'),
                'certificates'        => $this->input->post('certificates'),
                'last_date'           => $last_date,
                'is_active'           => $this->input->post('is_active') ? 1 : 0,
                'is_closed'           => $this->input->post('is_closed') ? 1 : 0,
            );

            if (!empty($id)) {
                $data['id'] = $id;
            }

            $this->job_posting_model->add($data);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
        }

        echo json_encode($array);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('job_posting', 'can_delete')) {
            access_denied();
        }

        $this->job_posting_model->delete($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('delete_message') . '</div>');
        redirect('admin/jobposting');
    }

    public function change_status()
    {
        if (!$this->rbac->hasPrivilege('job_posting', 'can_edit')) {
            echo json_encode(array('status' => 'fail', 'message' => 'Permission Denied'));
            return;
        }

        $id     = $this->input->post('id');
        $status = $this->input->post('status');

        if ($id) {
            $this->job_posting_model->changeStatus($id, $status);
            $msg = ($status == 1) ? 'Job posting enabled on website' : 'Job posting hidden from website';
            echo json_encode(array('status' => 'success', 'message' => $msg));
        } else {
            echo json_encode(array('status' => 'fail', 'message' => 'Invalid ID'));
        }
    }

    public function toggle_close()
    {
        if (!$this->rbac->hasPrivilege('job_posting', 'can_edit')) {
            echo json_encode(array('status' => 'fail', 'message' => 'Permission Denied'));
            return;
        }

        $id        = $this->input->post('id');
        $is_closed = $this->input->post('is_closed');

        if ($id) {
            $this->job_posting_model->toggleCloseStatus($id, $is_closed);
            $msg = ($is_closed == 1) ? 'Job posting marked as CLOSED (Applications Closed)' : 'Job posting REOPENED for applications';
            echo json_encode(array('status' => 'success', 'message' => $msg));
        } else {
            echo json_encode(array('status' => 'fail', 'message' => 'Invalid ID'));
        }
    }

    public function get_details($id)
    {
        if (!$this->rbac->hasPrivilege('job_posting', 'can_view')) {
            echo json_encode(array('status' => 'fail', 'message' => 'Permission Denied'));
            return;
        }

        $result = $this->job_posting_model->get($id);
        if (!empty($result) && !empty($result['last_date'])) {
            $result['formatted_last_date'] = date($this->customlib->getSchoolDateFormat(), strtotime($result['last_date']));
        }
        echo json_encode($result);
    }

    // ================= APPLICANT TRACKING SYSTEM (ATS) ================= //

    public function applicants($job_id = null)
    {
        if (!$this->rbac->hasPrivilege('job_posting', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/jobposting');

        $stage = $this->input->get('stage');

        $data['title']        = 'Applicant Tracking System (ATS)';
        $data['selected_job'] = $job_id;
        $data['selected_stage'] = $stage;

        $data['job_list']     = $this->job_posting_model->get();
        $data['applicants']   = $this->job_application_model->getByJobId($job_id, $stage);
        $data['counts']       = $this->job_application_model->getSummaryCounts($job_id);

        if (!empty($job_id)) {
            $data['current_job'] = $this->job_posting_model->get($job_id);
        }

        $this->load->view('layout/header');
        $this->load->view('admin/jobposting/applicants', $data);
        $this->load->view('layout/footer');
    }

    public function update_applicant_stage()
    {
        if (!$this->rbac->hasPrivilege('job_posting', 'can_edit')) {
            echo json_encode(array('status' => 'fail', 'message' => 'Permission Denied'));
            return;
        }

        $id    = $this->input->post('id');
        $stage = $this->input->post('stage');

        $valid_stages = array('Submitted', 'Screening', 'Shortlisted', 'Interview Scheduled', 'Offered', 'Hired', 'Rejected');

        if ($id && in_array($stage, $valid_stages)) {
            $this->job_application_model->updateStage($id, $stage);
            echo json_encode(array('status' => 'success', 'message' => 'Applicant stage updated to ' . $stage));
        } else {
            echo json_encode(array('status' => 'fail', 'message' => 'Invalid parameters'));
        }
    }

    public function update_applicant_notes()
    {
        if (!$this->rbac->hasPrivilege('job_posting', 'can_edit')) {
            echo json_encode(array('status' => 'fail', 'message' => 'Permission Denied'));
            return;
        }

        $id    = $this->input->post('id');
        $notes = $this->input->post('notes');

        if ($id) {
            $this->job_application_model->updateNotes($id, $notes);
            echo json_encode(array('status' => 'success', 'message' => 'Internal HR notes saved'));
        } else {
            echo json_encode(array('status' => 'fail', 'message' => 'Invalid ID'));
        }
    }

    public function download_resume($id)
    {
        if (!$this->rbac->hasPrivilege('job_posting', 'can_view')) {
            access_denied();
        }

        $applicant = $this->job_application_model->get($id);
        if ($applicant && !empty($applicant['resume_file'])) {
            $file_path = FCPATH . $applicant['resume_file'];
            if (file_exists($file_path)) {
                force_download($file_path, null);
                return;
            }
        }
        $this->session->set_flashdata('msg', '<div class="alert alert-danger">Resume file not found.</div>');
        redirect('admin/jobposting/applicants');
    }

    public function delete_applicant($id)
    {
        if (!$this->rbac->hasPrivilege('job_posting', 'can_delete')) {
            access_denied();
        }

        $this->job_application_model->delete($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-success">Applicant removed successfully.</div>');
        redirect('admin/jobposting/applicants');
    }
}
