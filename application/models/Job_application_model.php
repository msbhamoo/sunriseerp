<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Job_application_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Add new job application
     * @param array $data
     * @return int|bool
     */
    public function add($data)
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);

        if (empty($data['application_no'])) {
            $data['application_no'] = 'APP-' . date('Y') . '-' . sprintf('%04d', rand(1, 9999));
        }

        $this->db->insert('job_applications', $data);
        $insert_id = $this->db->insert_id();

        $message   = INSERT_RECORD_CONSTANT . " On job application id " . $insert_id;
        $action    = "Insert";
        $record_id = $insert_id;
        $this->log($message, $record_id, $action);

        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            return $insert_id;
        }
    }

    /**
     * Fetch job applications by job_id or stage filter
     * @param int|null $job_id
     * @param string|null $stage
     * @return array
     */
    public function getByJobId($job_id = null, $stage = null)
    {
        $this->db->select('job_applications.*, job_postings.title as job_title, job_postings.department, staff_designation.designation as designation_title');
        $this->db->from('job_applications');
        $this->db->join('job_postings', 'job_postings.id = job_applications.job_id', 'left');
        $this->db->join('staff_designation', 'staff_designation.id = job_postings.designation_id', 'left');

        if (!empty($job_id)) {
            $this->db->where('job_applications.job_id', $job_id);
        }

        if (!empty($stage)) {
            $this->db->where('job_applications.stage', $stage);
        }

        $this->db->order_by('job_applications.id', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Fetch single application details
     * @param int $id
     * @return array
     */
    public function get($id)
    {
        $this->db->select('job_applications.*, job_postings.title as job_title, job_postings.department, staff_designation.designation as designation_title');
        $this->db->from('job_applications');
        $this->db->join('job_postings', 'job_postings.id = job_applications.job_id', 'left');
        $this->db->join('staff_designation', 'staff_designation.id = job_postings.designation_id', 'left');
        $this->db->where('job_applications.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * Update application pipeline stage
     * @param int $id
     * @param string $stage
     * @return bool
     */
    public function updateStage($id, $stage)
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);

        $this->db->where('id', $id);
        $this->db->update('job_applications', array('stage' => $stage));

        $message   = UPDATE_RECORD_CONSTANT . " On job application stage id " . $id . " set to " . $stage;
        $action    = "Update";
        $record_id = $id;
        $this->log($message, $record_id, $action);

        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            return true;
        }
    }

    /**
     * Update HR admin notes
     * @param int $id
     * @param string $notes
     * @return bool
     */
    public function updateNotes($id, $notes)
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);

        $this->db->where('id', $id);
        $this->db->update('job_applications', array('admin_notes' => $notes));

        $message   = UPDATE_RECORD_CONSTANT . " On job application notes id " . $id;
        $action    = "Update";
        $record_id = $id;
        $this->log($message, $record_id, $action);

        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            return true;
        }
    }

    /**
     * Delete job application and attached resume file
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $app = $this->get($id);
        if ($app && !empty($app['resume_file'])) {
            $file_path = FCPATH . $app['resume_file'];
            if (file_exists($file_path)) {
                @unlink($file_path);
            }
        }

        $this->db->trans_start();
        $this->db->trans_strict(false);

        $this->db->where('id', $id);
        $this->db->delete('job_applications');

        $message   = DELETE_RECORD_CONSTANT . " On job application id " . $id;
        $action    = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);

        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            return true;
        }
    }

    /**
     * Get summary counts of applications per stage
     * @param int|null $job_id
     * @return array
     */
    public function getSummaryCounts($job_id = null)
    {
        $this->db->select('stage, COUNT(*) as count');
        $this->db->from('job_applications');
        if (!empty($job_id)) {
            $this->db->where('job_id', $job_id);
        }
        $this->db->group_by('stage');
        $query = $this->db->get();
        $result = $query->result_array();

        $summary = array(
            'total'               => 0,
            'Submitted'           => 0,
            'Screening'           => 0,
            'Shortlisted'         => 0,
            'Interview Scheduled' => 0,
            'Offered'             => 0,
            'Hired'               => 0,
            'Rejected'            => 0,
        );

        foreach ($result as $row) {
            $summary[$row['stage']] = intval($row['count']);
            $summary['total'] += intval($row['count']);
        }

        return $summary;
    }
}
