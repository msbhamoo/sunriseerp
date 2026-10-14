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
            if (!$this->db->field_exists('effective_from', 'staff_attendence_schedules')) {
                $this->db->query("ALTER TABLE `staff_attendence_schedules` ADD COLUMN `effective_from` DATE NOT NULL DEFAULT '2000-01-01' AFTER `is_active`");
            }
            if (!$this->db->field_exists('effective_to', 'staff_attendence_schedules')) {
                $this->db->query("ALTER TABLE `staff_attendence_schedules` ADD COLUMN `effective_to` DATE NULL DEFAULT NULL AFTER `effective_from`");
            }
            if (!$this->db->field_exists('season_tag', 'staff_attendence_schedules')) {
                $this->db->query("ALTER TABLE `staff_attendence_schedules` ADD COLUMN `season_tag` VARCHAR(100) NULL DEFAULT 'General Timings' AFTER `effective_to`");
            }
        }
    }

    /**
     * Get distinct seasonal schedule profiles configured in the system.
     */
    public function getSeasonalProfiles()
    {
        if (!$this->db->field_exists('season_tag', 'staff_attendence_schedules')) {
            return array();
        }
        $sql = "SELECT DISTINCT season_tag, effective_from, effective_to, COUNT(DISTINCT role_id) as total_roles
                FROM staff_attendence_schedules
                GROUP BY season_tag, effective_from, effective_to
                ORDER BY effective_from DESC";
        return $this->db->query($sql)->result_array();
    }

    /**
     * Check if a proposed seasonal date range collides with existing seasonal records for the specified roles.
     * Note: A prior schedule that is open-ended (effective_to IS NULL) is automatically capped up to (effective_from - 1 day)
     * by the system when the new season begins. It is therefore NOT a collision.
     * A collision only occurs if another distinct season has an explicit bounded range that overlaps, or if effective_to is defined
     * and collides with another season that starts during or covers that bounded window.
     */
    public function checkSeasonalCollision($roles, $from_date, $to_date = null, $season_tag = '')
    {
        if (empty($roles) || empty($from_date)) {
            return array('collision' => false);
        }

        $roles_str = implode(',', array_map('intval', (array)$roles));

        // Build overlap check query:
        // We only check against other seasons that have an effective_to specified (bounded)
        // OR that start AFTER our $from_date (which would conflict if we are active or open-ended).
        $sql = "SELECT DISTINCT sas.season_tag, sas.effective_from, sas.effective_to, r.name as role_name
                FROM staff_attendence_schedules sas
                JOIN roles r ON r.id = sas.role_id
                WHERE sas.role_id IN ($roles_str)";

        // Ignore the exact current record if re-saving the exact same season & start date
        if (!empty($season_tag)) {
            $sql .= " AND NOT (sas.season_tag = " . $this->db->escape($season_tag) . " AND sas.effective_from = " . $this->db->escape($from_date) . ")";
        }

        if (!empty($to_date)) {
            // New season is bounded [from_date, to_date]:
            // Conflict if an existing schedule starts inside our window: (sas.effective_from >= from_date AND sas.effective_from <= to_date)
            // OR if an existing bounded schedule overlaps: (sas.effective_from <= to_date AND sas.effective_to IS NOT NULL AND sas.effective_to >= from_date)
            $sql .= " AND (
                        (sas.effective_from >= " . $this->db->escape($from_date) . " AND sas.effective_from <= " . $this->db->escape($to_date) . ")
                        OR 
                        (sas.effective_from <= " . $this->db->escape($to_date) . " AND sas.effective_to IS NOT NULL AND sas.effective_to >= " . $this->db->escape($from_date) . ")
                      )";
        } else {
            // New season is open-ended from $from_date onwards:
            // Conflict only if an existing future schedule already exists that starts on or after $from_date
            $sql .= " AND sas.effective_from > " . $this->db->escape($from_date);
        }

        $query = $this->db->query($sql);
        if ($query && $query->num_rows() > 0) {
            return array(
                'collision' => true,
                'conflicts' => $query->result_array()
            );
        }

        return array('collision' => false);
    }

    public function getRoleAttendanceSetting($date = null, $season_tag = null, $effective_from = null)
    {
        $date = !empty($date) ? $date : date('Y-m-d');
        $has_eff = $this->db->field_exists('effective_from', 'staff_attendence_schedules');

        if ($has_eff) {
            $where_clause = "";
            if (!empty($season_tag) && !empty($effective_from)) {
                $where_clause = " AND sas.season_tag = " . $this->db->escape($season_tag) . " AND sas.effective_from = " . $this->db->escape($effective_from);
            } else {
                $where_clause = " AND sas.effective_from <= " . $this->db->escape($date) . " AND (sas.effective_to IS NULL OR sas.effective_to >= " . $this->db->escape($date) . ")";
            }

            $sql = "SELECT roles.*, sas.role_id, sas.staff_attendence_type_id, sas.id as `staff_attendence_schedules`,
                           sas.entry_time_from, sas.entry_time_to, sas.total_institute_hour, sas.early_half_time, sas.early_grace_time,
                           sas.effective_from, sas.effective_to, sas.season_tag,
                           roles.name as `role_name`
                    FROM `roles`
                    LEFT JOIN staff_attendence_schedules sas ON sas.role_id = roles.id $where_clause";
        } else {
            $sql = "SELECT roles.*,staff_attendence_schedules.role_id,staff_attendence_schedules.staff_attendence_type_id,staff_attendence_schedules.id as `staff_attendence_schedules`,entry_time_from,entry_time_to,staff_attendence_schedules.total_institute_hour,staff_attendence_schedules.early_half_time,staff_attendence_schedules.early_grace_time,roles.name as `role_name` FROM `roles` LEFT JOIN staff_attendence_schedules on staff_attendence_schedules.role_id=roles.id";
        }
        $query = $this->db->query($sql);
        return $query->result();
    }

    public function add($insert_array = [], $role_array = [], $effective_from = null, $effective_to = null, $season_tag = 'General Timings')
    {
        $has_eff = $this->db->field_exists('effective_from', 'staff_attendence_schedules');
        $has_season = $this->db->field_exists('season_tag', 'staff_attendence_schedules');

        if (!empty($role_array)) {
            $unique_roles = array_values(array_unique($role_array));

            if ($has_eff && !empty($effective_from)) {
                // 1. If an existing version starts exactly on $effective_from for these roles, replace it
                $this->db->where_in('role_id', $unique_roles);
                $this->db->where('effective_from', $effective_from);
                $this->db->delete('staff_attendence_schedules');

                // 2. Safely cap any previous open-ended version that started prior to $effective_from
                $prev_day = date('Y-m-d', strtotime($effective_from . ' -1 day'));
                $this->db->where_in('role_id', $unique_roles);
                $this->db->where('effective_from <', $effective_from);
                $this->db->group_start();
                $this->db->where('effective_to IS NULL', null, false);
                $this->db->or_where('effective_to >=', $effective_from);
                $this->db->group_end();
                $this->db->update('staff_attendence_schedules', array('effective_to' => $prev_day));
            } else {
                // Fallback / legacy mode: delete all
                $this->db->where_in('role_id', $unique_roles);
                $this->db->delete('staff_attendence_schedules');
            }
        }

        if (!empty($insert_array)) {
            if ($has_eff && !empty($effective_from)) {
                foreach ($insert_array as &$item) {
                    $item['effective_from'] = $effective_from;
                    $item['effective_to']   = !empty($effective_to) ? $effective_to : null;
                    if ($has_season) {
                        $item['season_tag'] = !empty($season_tag) ? $season_tag : 'General Timings';
                    }
                }
                unset($item);
            }
            $this->db->insert_batch('staff_attendence_schedules', $insert_array);
        }
    }

    public function getRoleWiseAttendanceSetting($role_id, $date = null)
    {
        $date = !empty($date) ? $date : date('Y-m-d');
        $has_eff = $this->db->field_exists('effective_from', 'staff_attendence_schedules');
        $date_clause = $has_eff ? " AND staff_attendence_schedules.effective_from <= " . $this->db->escape($date) . " AND (staff_attendence_schedules.effective_to IS NULL OR staff_attendence_schedules.effective_to >= " . $this->db->escape($date) . ")" : "";

        if ($role_id == null ||  $role_id == 'select') {
            $sql = "SELECT staff_attendence_schedules.*,roles.name as `role_name` FROM `staff_attendence_schedules` INNER JOIN roles on roles.id=staff_attendence_schedules.role_id WHERE 1=1 $date_clause order by roles.id";
        } else {
            $sql = "SELECT staff_attendence_schedules.*,roles.name as `role_name` FROM `staff_attendence_schedules` INNER JOIN roles on roles.id=staff_attendence_schedules.role_id WHERE roles.name=" . $this->db->escape($role_id) . " $date_clause order by roles.id";
        }
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getAttendanceTypeByRole($role_id, $time, $date = null)
    {
        $date = !empty($date) ? $date : date('Y-m-d');
        $has_eff = $this->db->field_exists('effective_from', 'staff_attendence_schedules');
        $date_clause = $has_eff ? " and effective_from <= " . $this->db->escape($date) . " and (effective_to IS NULL or effective_to >= " . $this->db->escape($date) . ")" : "";

        $sql = "SELECT * FROM `staff_attendence_schedules` WHERE role_id=" . $this->db->escape($role_id) . " and " . $this->db->escape($time) . " BETWEEN entry_time_from and entry_time_to $date_clause";

        $q = $this->db->query($sql);

        if ($q->num_rows() == 0) {
            return false;
        } else {
            $return_result = $q->row();
            return $return_result;
        }
    }

    public function getAllRoleRequiredHours($date = null)
    {
        $date = !empty($date) ? $date : date('Y-m-d');
        $has_eff = $this->db->field_exists('effective_from', 'staff_attendence_schedules');
        $date_clause = $has_eff ? " AND effective_from <= " . $this->db->escape($date) . " AND (effective_to IS NULL OR effective_to >= " . $this->db->escape($date) . ")" : "";

        $sql = "SELECT role_id, total_institute_hour FROM staff_attendence_schedules WHERE total_institute_hour IS NOT NULL AND total_institute_hour != '' $date_clause GROUP BY role_id";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        $map = array();
        foreach ($result as $row) {
            $map[$row['role_id']] = $row['total_institute_hour'];
        }
        return $map;
    }

    public function getAllRoleSchedulesMap($date = null)
    {
        $date = !empty($date) ? $date : date('Y-m-d');
        $has_eff = $this->db->field_exists('effective_from', 'staff_attendence_schedules');
        $date_clause = $has_eff ? " AND sas.effective_from <= " . $this->db->escape($date) . " AND (sas.effective_to IS NULL OR sas.effective_to >= " . $this->db->escape($date) . ")" : "";

        $sql = "SELECT sas.role_id, sas.staff_attendence_type_id, sas.entry_time_from, sas.entry_time_to, sas.total_institute_hour, sas.early_half_time, sas.early_grace_time, sat.key_value, sat.type 
                FROM staff_attendence_schedules sas
                JOIN staff_attendance_type sat ON sat.id = sas.staff_attendence_type_id
                WHERE 1=1 $date_clause";
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



