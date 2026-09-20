<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class StaffAttendaceSetting_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->_ensure_columns();
    }

    private function _ensure_columns()
    {
        if ($this->db->table_exists('staff_attendence_schedules')) {
            if (!$this->db->field_exists('early_half_time', 'staff_attendence_schedules')) {
                $this->db->query("ALTER TABLE `staff_attendence_schedules` ADD COLUMN `early_half_time` TIME NULL AFTER `total_institute_hour`");
            }
            if (!$this->db->field_exists('early_grace_time', 'staff_attendence_schedules')) {
                $this->db->query("ALTER TABLE `staff_attendence_schedules` ADD COLUMN `early_grace_time` TIME NULL AFTER `early_half_time`");
            }
        }
    }

    public function getRoleAttendanceSetting()
    {
        $sql = "SELECT roles.*,staff_attendence_schedules.role_id,staff_attendence_schedules.staff_attendence_type_id,staff_attendence_schedules.id as `staff_attendence_schedules`,entry_time_from,entry_time_to,staff_attendence_schedules.total_institute_hour,staff_attendence_schedules.early_half_time,staff_attendence_schedules.early_grace_time,roles.name as `role_name` FROM `roles` LEFT JOIN staff_attendence_schedules on staff_attendence_schedules.role_id=roles.id";
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function add($insert_array = [], $role_array = [])
    {
        if (!empty($role_array)) {
            $this->db->where_in('role_id', array_unique($role_array));
            $this->db->delete('staff_attendence_schedules');
        }
        if (!empty($insert_array)) {
            $this->db->insert_batch('staff_attendence_schedules', $insert_array);
        }
    }

    public function getRoleWiseAttendanceSetting($role_id)
    {
        if ($role_id == null ||  $role_id == 'select') {
            $sql = "SELECT staff_attendence_schedules.*,roles.name as `role_name` FROM `staff_attendence_schedules` INNER JOIN roles on roles.id=staff_attendence_schedules.role_id order by roles.id";
        } else {
            $sql = "SELECT staff_attendence_schedules.*,roles.name as `role_name` FROM `staff_attendence_schedules` INNER JOIN roles on roles.id=staff_attendence_schedules.role_id WHERE  roles.name=" . $this->db->escape($role_id) . "  order by roles.id";
        }
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getAttendanceTypeByRole($role_id, $time)
    {
        $sql = "SELECT * FROM `staff_attendence_schedules` WHERE role_id=" . $this->db->escape($role_id) . " and " . $this->db->escape($time) . " BETWEEN entry_time_from and entry_time_to";

        $q = $this->db->query($sql);

        if ($q->num_rows() == 0) {
            return false;
        } else {
            $return_result = $q->row();
            return $return_result;
        }
    }

    public function getAllRoleRequiredHours()
    {
        $sql = "SELECT role_id, total_institute_hour FROM staff_attendence_schedules WHERE total_institute_hour IS NOT NULL AND total_institute_hour != '' GROUP BY role_id";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $map = array();
        foreach ($result as $row) {
            $map[$row['role_id']] = $row['total_institute_hour'];
        }
        return $map;
    }

    public function getAllRoleSchedulesMap()
    {
        $sql = "SELECT sas.role_id, sas.staff_attendence_type_id, sas.entry_time_from, sas.entry_time_to, sas.total_institute_hour, sas.early_half_time, sas.early_grace_time, sat.key_value, sat.type 
                FROM staff_attendence_schedules sas
                JOIN staff_attendance_type sat ON sat.id = sas.staff_attendence_type_id";
        $query = $this->db->query($sql);
        $rows = $query->result_array();
        $map = array();
        foreach ($rows as $r) {
            $roleId = $r['role_id'];
            $k = strtolower(strip_tags($r['key_value']));
            if (!isset($map[$roleId])) {
                $map[$roleId] = array(
                    'present_up_to' => null,
                    'late_up_to' => null,
                    'halfday_up_to' => null,
                    'early_half' => null,
                    'early_grace' => null,
                    'dayend_time' => null,
                    'total_hours' => $r['total_institute_hour']
                );
            }
            if ($k === 'p') {
                $map[$roleId]['present_up_to'] = $r['entry_time_to'];
            } elseif ($k === 'l') {
                $map[$roleId]['late_up_to'] = $r['entry_time_to'];
            } elseif ($k === 'f') {
                $map[$roleId]['halfday_up_to'] = $r['entry_time_to'];
            } elseif ($k === 'sh') {
                $map[$roleId]['dayend_time'] = $r['entry_time_to'];
            }
            if (!empty($r['early_half_time'])) {
                $map[$roleId]['early_half'] = $r['early_half_time'];
            }
            if (!empty($r['early_grace_time'])) {
                $map[$roleId]['early_grace'] = $r['early_grace_time'];
            }
            if (!empty($r['total_institute_hour'])) {
                $map[$roleId]['total_hours'] = $r['total_institute_hour'];
            }
        }
        return $map;
    }
}



