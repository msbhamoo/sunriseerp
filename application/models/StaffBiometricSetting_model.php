<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class StaffBiometricSetting_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get biometric integration configuration row.
     */
    public function get()
    {
        $query = $this->db->get('staff_biometric_setting');
        if ($query->num_rows() > 0) {
            return $query->row_array();
        }
        return array(
            'id'                => 1,
            'is_enabled'        => 1,
            'auto_sync_enabled' => 0,
            'api_url'           => 'https://api.etimeoffice.com/api/',
            'corporate_id'      => 'SUNRISEEDUGROUP',
            'username'          => 'msbhamu',
            'password'          => 'Sunirse@2026',
            'last_record'       => null,
            'last_sync_time'    => null,
            'last_sync_status'  => 'idle',
            'last_sync_message' => null,
            'cron_token'        => '7b91d2e854f9a3c10b78e2d45c61a',
        );
    }

    /**
     * Update settings.
     */
    public function save($data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $query = $this->db->get('staff_biometric_setting');
        if ($query->num_rows() > 0) {
            $this->db->where('id', 1)->update('staff_biometric_setting', $data);
        } else {
            $data['id'] = 1;
            $data['created_at'] = date('Y-m-d H:i:s');
            $this->db->insert('staff_biometric_setting', $data);
        }
    }

    /**
     * Log biometric sync run.
     */
    public function logSync($log_data)
    {
        $log_data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('staff_biometric_log', $log_data);
        return $this->db->insert_id();
    }

    /**
     * Get recent sync audit logs.
     */
    public function getLogs($limit = 50)
    {
        $this->db->order_by('id', 'DESC');
        $this->db->limit($limit);
        return $this->db->get('staff_biometric_log')->result_array();
    }

    /**
     * Get all active staff with biometric configuration & mapping status.
     */
    public function getStaffBiometricMappingList()
    {
        $sql = "SELECT staff.id, staff.employee_id, staff.biometric_emp_id, staff.name, staff.surname, staff.contact_no, staff.email, roles.name as role_name, roles.id as role_id,
                (SELECT MAX(sa.date) FROM staff_attendance sa WHERE sa.staff_id = staff.id AND sa.biometric_attendence = 1) as last_biometric_date,
                (SELECT MAX(sa.created_at) FROM staff_attendance sa WHERE sa.staff_id = staff.id AND sa.biometric_attendence = 1) as last_biometric_time
                FROM staff
                LEFT JOIN staff_roles ON staff_roles.staff_id = staff.id
                LEFT JOIN roles ON roles.id = staff_roles.role_id
                WHERE staff.is_active = 1
                ORDER BY roles.name ASC, staff.name ASC";
        return $this->db->query($sql)->result_array();
    }

    /**
     * Update custom biometric machine code for a staff member.
     */
    public function updateStaffBiometricId($staff_id, $biometric_emp_id)
    {
        $biometric_emp_id = trim($biometric_emp_id);
        $val = ($biometric_emp_id === '') ? null : $biometric_emp_id;
        $this->db->where('id', $staff_id)->update('staff', array('biometric_emp_id' => $val));
        return true;
    }

    /**
     * Get staff list with complete fields for e-TimeOffice / Biometric Machine CSV/Excel Export.
     */
    public function getStaffExportListForBiometric()
    {
        $sql = "SELECT staff.id, staff.employee_id, staff.biometric_emp_id, staff.name, staff.surname,
                staff.father_name, staff.local_address, staff.permanent_address, staff.contact_no, staff.email,
                staff.emergency_contact_no, staff.dob, staff.date_of_joining, staff.date_of_leaving, staff.is_active,
                staff.department as dept_id, staff.designation as desg_id,
                roles.name as role_name, roles.id as role_id
                FROM staff
                LEFT JOIN staff_roles ON staff_roles.staff_id = staff.id
                LEFT JOIN roles ON roles.id = staff_roles.role_id
                WHERE staff.is_active = 1
                ORDER BY staff.id ASC";
        return $this->db->query($sql)->result_array();
    }
}

