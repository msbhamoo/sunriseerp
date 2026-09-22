<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Dutypass_model extends MY_Model
{
    public $current_session;

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    /**
     * Add or update Duty Pass with assigned staff members
     */
    public function add($data, $staff_ids = array())
    {
        $this->db->trans_start();
        $this->db->trans_strict(false);

        $duty_pass_id = null;

        if (isset($data['id']) && !empty($data['id'])) {
            $duty_pass_id = $data['id'];
            $this->db->where('id', $duty_pass_id);
            $this->db->update('staff_duty_pass', $data);

            if (!empty($staff_ids)) {
                // Delete existing member associations and re-insert
                $this->db->where('duty_pass_id', $duty_pass_id);
                $this->db->delete('staff_duty_pass_members');

                foreach ($staff_ids as $st_id) {
                    if (!empty($st_id)) {
                        $this->db->insert('staff_duty_pass_members', array(
                            'duty_pass_id' => $duty_pass_id,
                            'staff_id'     => (int)$st_id,
                            'role_in_duty' => 'Escort / Incharge'
                        ));
                    }
                }
            }
        } else {
            $this->db->insert('staff_duty_pass', $data);
            $duty_pass_id = $this->db->insert_id();

            if (!empty($staff_ids)) {
                foreach ($staff_ids as $st_id) {
                    if (!empty($st_id)) {
                        $this->db->insert('staff_duty_pass_members', array(
                            'duty_pass_id' => $duty_pass_id,
                            'staff_id'     => (int)$st_id,
                            'role_in_duty' => 'Escort / Incharge'
                        ));
                    }
                }
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();

            // Auto-mark attendance if status is Approved
            if (isset($data['status']) && $data['status'] == 'Approved' && $duty_pass_id) {
                $this->autoMarkAttendanceForDutyPass($duty_pass_id);
            }

            return $duty_pass_id;
        }
    }

    /**
     * Get single duty pass or all duty passes with staff member details
     */
    public function get($id = null)
    {
        $this->db->select('staff_duty_pass.*, approver.name as approver_name, approver.surname as approver_surname');
        $this->db->from('staff_duty_pass');
        $this->db->join('staff as approver', 'approver.id = staff_duty_pass.approved_by', 'left');

        if ($id != null) {
            $this->db->where('staff_duty_pass.id', $id);
            $query = $this->db->get();
            $result = $query->row_array();
            if ($result) {
                $result['staff_members'] = $this->getDutyPassMembers($result['id']);
            }
            return $result;
        } else {
            $this->db->order_by('staff_duty_pass.id', 'desc');
            $query = $this->db->get();
            $results = $query->result_array();
            foreach ($results as $k => $row) {
                $results[$k]['staff_members'] = $this->getDutyPassMembers($row['id']);
            }
            return $results;
        }
    }

    /**
     * Get staff members assigned to a duty pass
     */
    public function getDutyPassMembers($duty_pass_id)
    {
        $this->db->select('staff_duty_pass_members.*, staff.name, staff.surname, staff.employee_id, staff.image, staff.contact_no, staff.email, roles.name as role_name, staff_designation.designation, department.department_name');
        $this->db->from('staff_duty_pass_members');
        $this->db->join('staff', 'staff.id = staff_duty_pass_members.staff_id', 'left');
        $this->db->join('staff_roles', 'staff_roles.staff_id = staff.id', 'left');
        $this->db->join('roles', 'roles.id = staff_roles.role_id', 'left');
        $this->db->join('staff_designation', 'staff_designation.id = staff.designation', 'left');
        $this->db->join('department', 'department.id = staff.department', 'left');
        $this->db->where('staff_duty_pass_members.duty_pass_id', $duty_pass_id);
        $this->db->order_by('staff_duty_pass_members.id', 'asc');
        return $this->db->get()->result_array();
    }

    /**
     * Get all duty passes for a specific staff member
     */
    public function getByStaff($staff_id)
    {
        $this->db->select('staff_duty_pass.*, approver.name as approver_name, approver.surname as approver_surname');
        $this->db->from('staff_duty_pass_members');
        $this->db->join('staff_duty_pass', 'staff_duty_pass.id = staff_duty_pass_members.duty_pass_id');
        $this->db->join('staff as approver', 'approver.id = staff_duty_pass.approved_by', 'left');
        $this->db->where('staff_duty_pass_members.staff_id', $staff_id);
        $this->db->order_by('staff_duty_pass.from_date', 'desc');
        $query = $this->db->get();
        $results = $query->result_array();
        foreach ($results as $k => $row) {
            $results[$k]['staff_members'] = $this->getDutyPassMembers($row['id']);
        }
        return $results;
    }

    /**
     * Get all active approved duty passes for a specific date (keyed by staff_id)
     */
    public function getActiveDutyPassesByDate($date)
    {
        $this->db->select('staff_duty_pass.id as duty_pass_id, staff_duty_pass.duty_pass_no, staff_duty_pass.title as duty_title, staff_duty_pass.category as duty_category, staff_duty_pass.venue, staff_duty_pass.from_date, staff_duty_pass.to_date, staff_duty_pass.departure_time, staff_duty_pass.return_time, staff_duty_pass.description, staff_duty_pass.status, staff_duty_pass_members.staff_id');
        $this->db->from('staff_duty_pass');
        $this->db->join('staff_duty_pass_members', 'staff_duty_pass_members.duty_pass_id = staff_duty_pass.id');
        $this->db->where('staff_duty_pass.status', 'Approved');
        $this->db->where('staff_duty_pass.from_date <=', $date);
        $this->db->where('staff_duty_pass.to_date >=', $date);

        $query = $this->db->get();
        $rows = $query->result_array();

        $active_passes = array();
        foreach ($rows as $row) {
            $active_passes[$row['staff_id']] = $row;
        }
        return $active_passes;
    }

    /**
     * Check if a specific staff member is on approved duty for a given date
     */
    public function getStaffActiveDutyPass($staff_id, $date)
    {
        $this->db->select('staff_duty_pass.*');
        $this->db->from('staff_duty_pass');
        $this->db->join('staff_duty_pass_members', 'staff_duty_pass_members.duty_pass_id = staff_duty_pass.id');
        $this->db->where('staff_duty_pass_members.staff_id', $staff_id);
        $this->db->where('staff_duty_pass.status', 'Approved');
        $this->db->where('staff_duty_pass.from_date <=', $date);
        $this->db->where('staff_duty_pass.to_date >=', $date);
        $this->db->limit(1);
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * Generate unique serial Duty Pass number (e.g. DP-2026-0001)
     */
    public function generate_duty_pass_no()
    {
        $year = date('Y');
        $this->db->select('duty_pass_no');
        $this->db->from('staff_duty_pass');
        $this->db->like('duty_pass_no', 'DP-' . $year, 'after');
        $this->db->order_by('id', 'desc');
        $this->db->limit(1);
        $query = $this->db->get();
        $result = $query->row_array();

        if ($result && !empty($result['duty_pass_no'])) {
            $parts = explode('-', $result['duty_pass_no']);
            if (count($parts) >= 3) {
                $number = (int)$parts[2];
                $number++;
                return 'DP-' . $year . '-' . sprintf('%04d', $number);
            }
        }

        return 'DP-' . $year . '-0001';
    }

    /**
     * Auto-mark attendance as 'On Duty' (id: 8) in staff_attendance for each date in range
     */
    public function autoMarkAttendanceForDutyPass($duty_pass_id)
    {
        $duty_pass = $this->get($duty_pass_id);
        if (!$duty_pass || $duty_pass['status'] !== 'Approved') {
            return false;
        }

        $from = strtotime($duty_pass['from_date']);
        $to = strtotime($duty_pass['to_date']);
        $members = $duty_pass['staff_members'];

        if (empty($members) || !$from || !$to) {
            return false;
        }

        $dates = array();
        for ($current = $from; $current <= $to; $current = strtotime('+1 day', $current)) {
            $dates[] = date('Y-m-d', $current);
        }

        $attendance_batch = array();
        foreach ($members as $m) {
            $staff_id = $m['staff_id'];
            foreach ($dates as $d) {
                $attendance_batch[] = array(
                    'staff_id'                 => $staff_id,
                    'staff_attendance_type_id' => 8, // On Duty
                    'remark'                   => 'Duty Pass: ' . $duty_pass['duty_pass_no'] . ' (' . $duty_pass['title'] . ' @ ' . $duty_pass['venue'] . ')',
                    'date'                     => $d,
                    'updated_at'               => date('Y-m-d H:i:s'),
                    'attendance_source'        => 'duty_pass',
                    'is_active'                => 1
                );
            }
        }

        if (!empty($attendance_batch)) {
            $this->load->model('staffattendancemodel');
            return $this->staffattendancemodel->addorUpdate($attendance_batch);
        }

        return true;
    }

    /**
     * Delete Duty Pass and its member associations
     */
    public function delete($id)
    {
        $this->db->trans_start();
        $this->db->where('duty_pass_id', $id);
        $this->db->delete('staff_duty_pass_members');

        $this->db->where('id', $id);
        $this->db->delete('staff_duty_pass');
        $this->db->trans_complete();

        return $this->db->trans_status();
    }
}
