<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Job_posting_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get single or all job postings joined with Designation master and Application Counts
     * @param int|null $id
     * @return array
     */
    public function get($id = null)
    {
        $this->db->select('job_postings.*, staff_designation.designation as designation_title, 
            (SELECT COUNT(*) FROM job_applications WHERE job_applications.job_id = job_postings.id) as applications_count');
        $this->db->from('job_postings');
        $this->db->join('staff_designation', 'staff_designation.id = job_postings.designation_id', 'left');

        if ($id != null) {
            $this->db->where('job_postings.id', $id);
            $query = $this->db->get();
            return $query->row_array();
        } else {
            $this->db->order_by('job_postings.id', 'DESC');
            $query = $this->db->get();
            return $query->result_array();
        }
    }

    /**
     * Increment job view count by 1
     * @param int $id
     */
    public function incrementViews($id)
    {
        $this->db->set('views_count', 'views_count+1', FALSE);
        $this->db->where('id', $id);
        $this->db->update('job_postings');
    }

    /**
     * Get active job listings for public website portal
     * @param int|null $limit
     * @param string|null $search
     * @param int|null $designation_id
     * @return array
     */
    public function getActiveForWebsite($limit = null, $search = null, $designation_id = null)
    {
        $this->db->select('job_postings.*, staff_designation.designation as designation_title');
        $this->db->from('job_postings');
        $this->db->join('staff_designation', 'staff_designation.id = job_postings.designation_id', 'left');
        $this->db->where('job_postings.is_active', 1);

        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('job_postings.title', $search);
            $this->db->or_like('job_postings.job_description', $search);
            $this->db->or_like('job_postings.department', $search);
            $this->db->or_like('staff_designation.designation', $search);
            $this->db->group_end();
        }

        if (!empty($designation_id)) {
            $this->db->where('job_postings.designation_id', $designation_id);
        }

        $this->db->order_by('job_postings.id', 'DESC');

        if ($limit) {
            $this->db->limit($limit);
        }

        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Add or update job posting
     * @param array $data
     * @return int|bool
     */
    public function add($data)
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);

        if (isset($data['id']) && $data['id'] > 0) {
            $this->db->where('id', $data['id']);
            $this->db->update('job_postings', $data);
            $message   = UPDATE_RECORD_CONSTANT . " On job posting id " . $data['id'];
            $action    = "Update";
            $record_id = $data['id'];
            $this->log($message, $record_id, $action);

            $this->db->trans_complete();
            if ($this->db->trans_status() === false) {
                $this->db->trans_rollback();
                return false;
            } else {
                return $data['id'];
            }
        } else {
            $this->db->insert('job_postings', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On job posting id " . $insert_id;
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
    }

    /**
     * Delete job posting
     * @param int $id
     * @return bool
     */
    public function delete($id)
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);

        $this->db->where('id', $id);
        $this->db->delete('job_postings');
        $message   = DELETE_RECORD_CONSTANT . " On job posting id " . $id;
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
     * Update website listing active status (1 = Turned ON, 0 = Turned OFF)
     * @param int $id
     * @param int $status
     * @return bool
     */
    public function changeStatus($id, $status)
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);

        $this->db->where('id', $id);
        $this->db->update('job_postings', array('is_active' => $status));

        $message   = UPDATE_RECORD_CONSTANT . " On job posting status id " . $id . " set to " . $status;
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
     * Toggle job posting open/closed status (0 = Open, 1 = Closed)
     * @param int $id
     * @param int $is_closed
     * @return bool
     */
    public function toggleCloseStatus($id, $is_closed)
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);

        $this->db->where('id', $id);
        $this->db->update('job_postings', array('is_closed' => $is_closed));

        $message   = UPDATE_RECORD_CONSTANT . " On job posting is_closed id " . $id . " set to " . $is_closed;
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
}
