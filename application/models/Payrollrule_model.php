<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Payrollrule_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    // =========================================================
    // 1. RULE GROUPS
    // =========================================================

    public function getRuleGroups($active_only = false)
    {
        $this->db->select('*');
        $this->db->from('pre_rule_groups');
        if ($active_only) {
            $this->db->where('is_active', 1);
        }
        $this->db->order_by('execution_order', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function saveRuleGroup($data)
    {
        if (isset($data['id']) && $data['id'] != '') {
            $this->db->where('id', $data['id']);
            $this->db->update('pre_rule_groups', $data);
            $action = 'update';
        } else {
            $this->db->insert('pre_rule_groups', $data);
            $data['id'] = $this->db->insert_id();
            $action = 'insert';
        }
        $this->log($action, 'pre_rule_groups', $data['id'], $data);
        return $data['id'];
    }

    public function deleteRuleGroup($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('pre_rule_groups');
        $this->log('delete', 'pre_rule_groups', $id, array('id' => $id));
    }

    // =========================================================
    // 2. RULES
    // =========================================================

    public function getRules($group_id = null, $active_only = false)
    {
        $this->db->select('pre_rules.*, pre_rule_groups.name as group_name');
        $this->db->from('pre_rules');
        $this->db->join('pre_rule_groups', 'pre_rules.rule_group_id = pre_rule_groups.id');
        if ($group_id !== null) {
            $this->db->where('pre_rules.rule_group_id', $group_id);
        }
        if ($active_only) {
            $this->db->where('pre_rules.is_active', 1);
        }
        $this->db->order_by('pre_rules.rule_group_id', 'asc');
        $this->db->order_by('pre_rules.priority', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getRule($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('pre_rules');
        $rule = $query->row_array();
        if ($rule) {
            $rule['conditions'] = $this->getConditions($id);
            $rule['actions'] = $this->getActions($id);
        }
        return $rule;
    }

    public function saveRule($data, $conditions = array(), $actions = array())
    {
        $this->db->trans_start();

        if (isset($data['id']) && $data['id'] != '') {
            // Update
            $this->db->where('id', $data['id']);
            $this->db->update('pre_rules', $data);
            $rule_id = $data['id'];
            $action_log = 'update';
        } else {
            // Insert
            $data['created_by'] = $this->customlib->getStaffID();
            $this->db->insert('pre_rules', $data);
            $rule_id = $this->db->insert_id();
            $action_log = 'insert';
        }

        // Save conditions
        $this->db->where('rule_id', $rule_id);
        $this->db->delete('pre_rule_conditions');
        if (!empty($conditions)) {
            foreach ($conditions as &$cond) {
                $cond['rule_id'] = $rule_id;
            }
            $this->db->insert_batch('pre_rule_conditions', $conditions);
        }

        // Save actions
        $this->db->where('rule_id', $rule_id);
        $this->db->delete('pre_rule_actions');
        if (!empty($actions)) {
            foreach ($actions as &$act) {
                $act['rule_id'] = $rule_id;
            }
            $this->db->insert_batch('pre_rule_actions', $actions);
        }

        // Create version snapshot
        $this->createVersion($rule_id, "Saved rule");

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            return false;
        }

        $this->log($action_log, 'pre_rules', $rule_id, $data);
        return $rule_id;
    }

    public function toggleRule($id, $status)
    {
        $this->db->where('id', $id);
        $this->db->update('pre_rules', array('is_active' => $status));
        $this->log('update', 'pre_rules', $id, array('is_active' => $status));
    }

    public function deleteRule($id)
    {
        // Soft delete
        $this->db->where('id', $id);
        $this->db->update('pre_rules', array('is_active' => 0));
        $this->log('delete', 'pre_rules', $id, array('is_active' => 0));
    }

    // =========================================================
    // 3. CONDITIONS & ACTIONS
    // =========================================================

    public function getConditions($rule_id)
    {
        $this->db->where('rule_id', $rule_id);
        $this->db->order_by('sort_order', 'asc');
        return $this->db->get('pre_rule_conditions')->result_array();
    }

    public function getActions($rule_id)
    {
        $this->db->where('rule_id', $rule_id);
        $this->db->order_by('sort_order', 'asc');
        return $this->db->get('pre_rule_actions')->result_array();
    }

    // =========================================================
    // 4. VERSIONING
    // =========================================================

    public function createVersion($rule_id, $reason)
    {
        $rule = $this->getRule($rule_id);
        if (!$rule) return false;

        $this->db->where('rule_id', $rule_id);
        $this->db->select_max('version');
        $query = $this->db->get('pre_rule_versions')->row_array();
        $next_version = ($query['version'] ?? 0) + 1;

        $snapshot = json_encode($rule);

        $data = array(
            'rule_id' => $rule_id,
            'version' => $next_version,
            'rule_snapshot' => $snapshot,
            'change_reason' => $reason,
            'created_by' => $this->customlib->getStaffID(),
            'created_at' => date('Y-m-d H:i:s')
        );

        $this->db->insert('pre_rule_versions', $data);

        // Update the current version in the main table
        $this->db->where('id', $rule_id);
        $this->db->update('pre_rules', array('version' => $next_version));
        
        return $next_version;
    }

    public function getVersionHistory($rule_id)
    {
        $this->db->where('rule_id', $rule_id);
        $this->db->order_by('version', 'desc');
        return $this->db->get('pre_rule_versions')->result_array();
    }

    // =========================================================
    // 5. ENGINE RUNS & TRACES
    // =========================================================

    public function createEngineRun($data)
    {
        $this->db->insert('pre_engine_runs', $data);
        return $this->db->insert_id();
    }

    public function updateEngineRun($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('pre_engine_runs', $data);
    }

    public function getEngineRuns()
    {
        $this->db->select('pre_engine_runs.*, staff.name as staff_name, staff.surname as staff_surname');
        $this->db->from('pre_engine_runs');
        $this->db->join('staff', 'staff.id = pre_engine_runs.run_by', 'left');
        $this->db->order_by('pre_engine_runs.id', 'desc');
        return $this->db->get()->result_array();
    }

    public function getRunTraces($run_id)
    {
        $this->db->where('engine_run_id', $run_id);
        return $this->db->get('pre_calculation_traces')->result_array();
    }

    public function getSimulationResults($run_id)
    {
        $this->db->where('engine_run_id', $run_id);
        return $this->db->get('pre_simulation_results')->result_array();
    }

    public function clearSimulation($run_id)
    {
        $this->db->where('engine_run_id', $run_id);
        $this->db->delete('pre_simulation_results');
        
        $this->db->where('engine_run_id', $run_id);
        $this->db->delete('pre_calculation_traces');
        
        $this->db->where('id', $run_id);
        $this->db->delete('pre_engine_runs');
    }

    // =========================================================
    // 6. DATA COLLECTION (For Engine)
    // =========================================================

    public function getStaffList($role_id = null)
    {
        $this->db->select('staff.*, roles.name as role_name, roles.id as role_id, department.department_name, staff_designation.designation as designation_name');
        $this->db->from('staff');
        $this->db->join('staff_roles', 'staff_roles.staff_id = staff.id', 'left');
        $this->db->join('roles', 'roles.id = staff_roles.role_id', 'left');
        $this->db->join('department', 'department.id = staff.department', 'left');
        $this->db->join('staff_designation', 'staff_designation.id = staff.designation', 'left');
        $this->db->where('staff.is_active', 1);
        if ($role_id) {
            $this->db->where('roles.id', $role_id);
        }
        return $this->db->get()->result_array();
    }

    public function getStaffAttendanceSummary($month, $year)
    {
        $month = str_pad($month, 2, '0', STR_PAD_LEFT);
        $query = $this->db->query("
            SELECT 
                staff_id,
                staff_attendance_type.type,
                COUNT(*) as days
            FROM staff_attendance
            JOIN staff_attendance_type ON staff_attendance_type.id = staff_attendance.staff_attendance_type_id
            WHERE DATE_FORMAT(date, '%Y-%m') = ?
            GROUP BY staff_id, staff_attendance_type.type
        ", array("$year-$month"));
        
        $result = array();
        foreach ($query->result_array() as $row) {
            if (!isset($result[$row['staff_id']])) {
                $result[$row['staff_id']] = array(
                    'Present' => 0, 'Absent' => 0, 'Late' => 0, 'Half Day' => 0, 'Holiday' => 0
                );
            }
            $result[$row['staff_id']][$row['type']] = $row['days'];
        }
        return $result;
    }

    public function getStaffLeaveSummary($month, $year)
    {
        $month = str_pad($month, 2, '0', STR_PAD_LEFT);
        $query = $this->db->query("
            SELECT 
                staff_id,
                SUM(leave_days) as leave_days
            FROM staff_leave_request
            WHERE status = 'approve' 
              AND DATE_FORMAT(leave_from, '%Y-%m') = ?
            GROUP BY staff_id
        ", array("$year-$month"));
        
        $result = array();
        foreach ($query->result_array() as $row) {
            $result[$row['staff_id']] = $row['leave_days'];
        }
        return $result;
    }

    public function getHolidayCount($month, $year)
    {
        $month = str_pad($month, 2, '0', STR_PAD_LEFT);
        $start_of_month = "$year-$month-01";
        $end_of_month = date("Y-m-t", strtotime($start_of_month));

        $query = $this->db->query("
            SELECT from_date, to_date
            FROM annual_calendar
            WHERE from_date <= ? AND to_date >= ? AND is_active = 1
        ", array($end_of_month . ' 23:59:59', $start_of_month . ' 00:00:00'));
        
        $holidays = 0;
        foreach ($query->result_array() as $row) {
            $h_start = strtotime(date('Y-m-d', strtotime($row['from_date'])));
            $h_end = strtotime(date('Y-m-d', strtotime($row['to_date'])));
            $m_start = strtotime($start_of_month);
            $m_end = strtotime($end_of_month);

            $actual_start = max($h_start, $m_start);
            $actual_end = min($h_end, $m_end);

            if ($actual_start <= $actual_end) {
                $days = round(($actual_end - $actual_start) / 86400) + 1;
                $holidays += $days;
            }
        }
        return $holidays;
    }
}
