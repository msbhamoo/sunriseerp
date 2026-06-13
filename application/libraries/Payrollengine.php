<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Payrollengine
{
    private $CI;

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model('payrollrule_model');
    }

    public function runSimulation($month, $year, $role_id = null)
    {
        $staff_list = $this->CI->payrollrule_model->getStaffList($role_id);
        if (empty($staff_list)) {
            return false;
        }

        $month_num = date('n', strtotime($month . ' 1'));
        $month_padded = str_pad($month_num, 2, '0', STR_PAD_LEFT);

        $attendance_data = $this->CI->payrollrule_model->getStaffAttendanceSummary($month_padded, $year);
        $leave_data = $this->CI->payrollrule_model->getStaffLeaveSummary($month_padded, $year);
        $school_holidays = $this->CI->payrollrule_model->getHolidayCount($month_padded, $year);

        $rules = $this->loadActiveRules();

        $run_id = $this->CI->payrollrule_model->createEngineRun(array(
            'run_type' => 'simulation',
            'month' => $month,
            'year' => $year,
            'role_filter' => $role_id ? $role_id : 'All',
            'total_staff' => count($staff_list),
            'run_by' => $this->CI->customlib->getStaffID(),
        ));

        $total_processed = 0;
        $total_errors = 0;
        
        $total_days_in_month = date('t', strtotime("$year-$month_padded-01"));

        foreach ($staff_list as $staff) {
            $context = $this->buildContext($staff, $attendance_data, $leave_data, $school_holidays, $total_days_in_month);
            
            $trace = array();
            $trace['input'] = $context;
            $trace['rules_applied'] = array();

            foreach ($rules as $group) {
                foreach ($group['rules'] as $rule) {
                    if ($this->ruleAppliesToStaff($rule, $context)) {
                        if ($this->evaluateConditions($rule['conditions'], $context)) {
                            $action_results = $this->executeActions($rule['actions'], $context);
                            $trace['rules_applied'][] = array(
                                'rule_id' => $rule['id'],
                                'rule_name' => $rule['name'],
                                'action_results' => $action_results
                            );
                        }
                    }
                }
            }
            
            $this->finalizeSalary($context);
            $trace['output'] = $context;

            // Save trace
            $this->CI->db->insert('pre_calculation_traces', array(
                'engine_run_id' => $run_id,
                'staff_id' => $staff['id'],
                'input_data' => json_encode($trace['input']),
                'rules_applied' => json_encode($trace['rules_applied']),
                'output_data' => json_encode($trace['output']),
            ));

            // Save simulation result
            $this->CI->db->insert('pre_simulation_results', array(
                'engine_run_id' => $run_id,
                'staff_id' => $staff['id'],
                'staff_name' => $staff['name'] . ' ' . $staff['surname'],
                'role_name' => $staff['role_name'],
                'basic_salary' => $context['basic_salary'],
                'total_days' => $context['total_days'],
                'paid_days' => $context['paid_days'],
                'present_days' => $context['present_days'],
                'absent_days' => $context['absent_days'],
                'late_days' => $context['late_count'],
                'half_days' => $context['half_days'],
                'holidays' => $context['school_holidays'],
                'leaves_taken' => $context['leaves_taken'],
                'total_earnings' => $context['total_earnings'],
                'total_deductions' => $context['total_deductions'],
                'tax_amount' => $context['tax_amount'],
                'pf_amount' => $context['pf_amount'],
                'esi_amount' => $context['esi_amount'],
                'tds_amount' => $context['tds_amount'],
                'net_salary' => $context['net_salary'],
                'earnings_detail' => json_encode($context['earnings_detail']),
                'deductions_detail' => json_encode($context['deductions_detail']),
            ));
            
            $total_processed++;
        }

        $this->CI->payrollrule_model->updateEngineRun($run_id, array(
            'total_processed' => $total_processed,
            'total_errors' => $total_errors,
            'status' => 'completed'
        ));

        return $run_id;
    }

    private function loadActiveRules()
    {
        $groups = $this->CI->payrollrule_model->getRuleGroups(true);
        $rules_tree = array();

        foreach ($groups as $group) {
            $rules = $this->CI->payrollrule_model->getRules($group['id'], true);
            foreach ($rules as &$rule) {
                $rule['conditions'] = $this->CI->payrollrule_model->getConditions($rule['id']);
                $rule['actions'] = $this->CI->payrollrule_model->getActions($rule['id']);
                $rule['applies_to_arr'] = !empty($rule['applies_to']) ? json_decode($rule['applies_to'], true) : array();
            }
            $group['rules'] = $rules;
            $rules_tree[] = $group;
        }

        return $rules_tree;
    }

    private function buildContext($staff, $attendance_data, $leave_data, $school_holidays, $total_days_in_month)
    {
        $att = isset($attendance_data[$staff['id']]) ? $attendance_data[$staff['id']] : array('Present'=>0, 'Absent'=>0, 'Late'=>0, 'Half Day'=>0, 'Holiday'=>0);
        $leaves = isset($leave_data[$staff['id']]) ? $leave_data[$staff['id']] : 0;

        $present = $att['Present'] ?? 0;
        $absent = $att['Absent'] ?? 0;
        $late = $att['Late'] ?? 0;
        $half_day = $att['Half Day'] ?? 0;
        
        $self_holidays = max(0, $total_days_in_month - $school_holidays - $present - $leaves - $half_day);
        
        $basic = floatval($staff['basic_salary']);
        $one_day_salary = $basic > 0 ? round($basic / 30, 2) : 0; // fixed divisor per calculation sheet

        $month_num = str_pad(date('n', strtotime($this->CI->input->post('month') . ' 1')), 2, '0', STR_PAD_LEFT);
        $year_val = $this->CI->input->post('year');

        $joining_day = 0;
        if (!empty($staff['date_of_joining']) && $staff['date_of_joining'] != '0000-00-00') {
            $j_time = strtotime($staff['date_of_joining']);
            if (date('m', $j_time) == $month_num && date('Y', $j_time) == $year_val) {
                $joining_day = (int)date('j', $j_time);
            }
        }

        $leaving_day = 0;
        if (!empty($staff['date_of_leaving']) && $staff['date_of_leaving'] != '0000-00-00') {
            $l_time = strtotime($staff['date_of_leaving']);
            if (date('m', $l_time) == $month_num && date('Y', $l_time) == $year_val) {
                $leaving_day = (int)date('j', $l_time);
            }
        }

        return array(
            'staff_id' => $staff['id'],
            'role_id' => $staff['role_id'],
            'department_id' => $staff['department'],
            'designation_id' => $staff['designation'],
            
            'total_days' => $total_days_in_month,
            'basic_salary' => $basic,
            'one_day_salary' => $one_day_salary,
            
            'present_days' => $present,
            'absent_days' => $absent,
            'late_count' => $late,
            'half_days' => $half_day,
            'school_holidays' => $school_holidays,
            'self_holidays' => $self_holidays,
            'leaves_taken' => $leaves,
            'joining_day' => $joining_day,
            'leaving_day' => $leaving_day,
            
            'paid_days' => $total_days_in_month, // Start assuming full pay
            'less_days' => 0,
            
            'total_earnings' => 0,
            'total_deductions' => 0,
            
            'tax_amount' => 0,
            'pf_amount' => 0,
            'esi_amount' => 0,
            'tds_amount' => 0,
            'net_salary' => 0,
            
            'earnings_detail' => array(),
            'deductions_detail' => array()
        );
    }

    private function ruleAppliesToStaff($rule, $context)
    {
        if (empty($rule['applies_to_arr'])) return true;
        return in_array($context['role_id'], $rule['applies_to_arr']);
    }

    private function evaluateConditions($conditions, $context)
    {
        if (empty($conditions)) return true;

        $group_results = array();
        foreach ($conditions as $cond) {
            $grp = $cond['condition_group'];
            if (!isset($group_results[$grp])) $group_results[$grp] = true;
            
            $field_val = isset($context[$cond['field']]) ? $context[$cond['field']] : null;
            $res = $this->compareValue($field_val, $cond['operator'], $cond['value']);
            
            $group_results[$grp] = $group_results[$grp] && $res;
        }

        foreach ($group_results as $grp_res) {
            if ($grp_res) return true; 
        }
        return false;
    }

    private function compareValue($actual, $op, $expected)
    {
        switch ($op) {
            case 'eq': return $actual == $expected;
            case 'neq': return $actual != $expected;
            case 'gt': return $actual > $expected;
            case 'gte': return $actual >= $expected;
            case 'lt': return $actual < $expected;
            case 'lte': return $actual <= $expected;
            case 'between':
                $range = json_decode($expected, true);
                if(is_array($range) && count($range) == 2){
                    return $actual >= $range[0] && $actual <= $range[1];
                }
                return false;
            case 'in':
                $arr = json_decode($expected, true);
                if(!is_array($arr)) $arr = explode(',', $expected);
                return in_array($actual, $arr);
            case 'not_in':
                $arr = json_decode($expected, true);
                if(!is_array($arr)) $arr = explode(',', $expected);
                return !in_array($actual, $arr);
            case 'is_null': return is_null($actual) || $actual === '';
            case 'is_not_null': return !is_null($actual) && $actual !== '';
            default: return false;
        }
    }

    private function executeActions($actions, &$context)
    {
        $results = array();
        foreach ($actions as $action) {
            $val = floatval($action['value']);
            $target = $action['target_field'];
            
            switch ($action['action_type']) {
                case 'add_paid_days':
                    $context['paid_days'] += $val;
                    $results[] = "+{$val} to paid_days";
                    break;
                case 'deduct_paid_days':
                    $context['paid_days'] -= $val;
                    $context['less_days'] += $val;
                    $results[] = "-{$val} to paid_days";
                    break;
                case 'fixed_deduction':
                    $context['total_deductions'] += $val;
                    $context['deductions_detail'][$target] = $val;
                    $results[] = "Deducted {$val} for {$target}";
                    break;
                case 'percentage_deduction':
                    $amt = round(($val / 100) * $context['basic_salary'], 2);
                    $context['total_deductions'] += $amt;
                    $context['deductions_detail'][$target] = $amt;
                    $results[] = "Deducted {$amt} ({$val}%) for {$target}";
                    break;
                case 'fixed_addition':
                case 'add_allowance':
                    $context['total_earnings'] += $val;
                    $context['earnings_detail'][$target] = $val;
                    $results[] = "Added {$val} for {$target}";
                    break;
                case 'percentage_addition':
                    $amt = round(($val / 100) * $context['basic_salary'], 2);
                    $context['total_earnings'] += $amt;
                    $context['earnings_detail'][$target] = $amt;
                    $results[] = "Added {$amt} ({$val}%) for {$target}";
                    break;
                case 'statutory_pf':
                    $amt = round(($val / 100) * $context['basic_salary'], 2);
                    $context['pf_amount'] = $amt;
                    $context['total_deductions'] += $amt;
                    $context['deductions_detail']['PF'] = $amt;
                    $results[] = "PF {$amt}";
                    break;
                case 'statutory_esi':
                    $amt = round(($val / 100) * $context['basic_salary'], 2);
                    $context['esi_amount'] = $amt;
                    $context['total_deductions'] += $amt;
                    $context['deductions_detail']['ESI'] = $amt;
                    $results[] = "ESI {$amt}";
                    break;
                case 'statutory_tds':
                    $amt = round(($val / 100) * $context['basic_salary'], 2);
                    $context['tds_amount'] = $amt;
                    $context['tax_amount'] = $amt;
                    $context['deductions_detail']['TDS'] = $amt;
                    $results[] = "TDS {$amt}";
                    break;
                case 'set_value':
                    $context[$target] = $val;
                    $results[] = "Set {$target} to {$val}";
                    break;
                case 'multiply_value':
                    if (isset($context[$target])) {
                        $amt = round($context[$target] * $val, 2);
                        if ($action['target_field'] == 'TA' || $action['target_field'] == 'DA') {
                            $context['total_earnings'] += $amt;
                            $context['earnings_detail'][$action['target_field']] = $amt;
                        }
                        $results[] = "Multiplied {$target} by {$val} = {$amt}";
                    }
                    break;
                case 'allowance_per_present_day':
                    $amt = round($context['present_days'] * $val, 2);
                    $context['total_earnings'] += $amt;
                    $context['earnings_detail'][$target] = $amt;
                    $results[] = "Added {$amt} ({$val} x {$context['present_days']} present days) for {$target}";
                    break;
                case 'deduction_per_present_day':
                    $amt = round($context['present_days'] * $val, 2);
                    $context['total_deductions'] += $amt;
                    $context['deductions_detail'][$target] = $amt;
                    $results[] = "Deducted {$amt} ({$val} x {$context['present_days']} present days) for {$target}";
                    break;
                case 'percentage_deduction_one_day':
                    $amt = round(($val / 100) * $context['one_day_salary'], 2);
                    $context['total_deductions'] += $amt;
                    $context['deductions_detail'][$target] = $amt;
                    $results[] = "Deducted {$amt} ({$val}% of one day sal) for {$target}";
                    break;
                case 'deduct_dynamic_paid_days':
                    if (isset($context[$target])) {
                        $amt = floatval($context[$target]) * floatval($val);
                        $context['paid_days'] -= $amt;
                        $results[] = "Deducted {$amt} paid days based on {$target} x {$val}";
                    }
                    break;
                case 'add_dynamic_paid_days':
                    if (isset($context[$target])) {
                        $amt = floatval($context[$target]) * floatval($val);
                        $context['paid_days'] += $amt;
                        $results[] = "Added {$amt} paid days based on {$target} x {$val}";
                    }
                    break;
            }
        }
        return $results;
    }

    private function finalizeSalary(&$context)
    {
        $paid_days = max(0, $context['paid_days']);
        
        $base_earned = 0;
        if ($context['total_days'] > 0) {
             // using fixed divisor strategy from calculation sheet (basic_salary / 30) * paid_days
             $base_earned = round($context['one_day_salary'] * $paid_days, 2);
             // or basic - less_amount
             $less_amount = round($context['less_days'] * $context['one_day_salary'], 2);
             $base_earned = $context['basic_salary'] - $less_amount;
        }

        $gross = $base_earned + $context['total_earnings'];
        $net = $gross - $context['total_deductions'];
        if($context['tax_amount'] > 0 && !isset($context['deductions_detail']['TDS'])) {
            $net -= $context['tax_amount'];
        }

        $context['net_salary'] = max(0, round($net, 2));
    }

    public function applySimulation($run_id)
    {
        $results = $this->CI->payrollrule_model->getSimulationResults($run_id);
        if (empty($results)) return false;

        $run = $this->CI->db->where('id', $run_id)->get('pre_engine_runs')->row_array();
        if (!$run) return false;

        $this->CI->db->trans_start();

        foreach ($results as $res) {
            // Check if payslip already exists
            $check = $this->CI->db->where(array(
                'month' => $run['month'],
                'year' => $run['year'],
                'staff_id' => $res['staff_id']
            ))->get('staff_payslip')->row_array();

            if ($check) continue;

            $data = array(
                'staff_id' => $res['staff_id'],
                'basic' => $res['basic_salary'],
                'total_allowance' => $res['total_earnings'],
                'total_deduction' => $res['total_deductions'] + $res['tax_amount'],
                'net_salary' => $res['net_salary'],
                'payment_date' => date('Y-m-d'),
                'status' => 'generated',
                'month' => $run['month'],
                'year' => $run['year'],
                'tax' => $res['tax_amount'],
                'leave_deduction' => 0,
                'generated_by' => $this->CI->customlib->getStaffID(),
            );

            $this->CI->db->insert('staff_payslip', $data);
            $payslip_id = $this->CI->db->insert_id();

            // Insert earnings
            $earnings = json_decode($res['earnings_detail'], true);
            if (!empty($earnings)) {
                foreach ($earnings as $type => $amount) {
                    $this->CI->db->insert('payslip_allowance', array(
                        'payslip_id' => $payslip_id,
                        'allowance_type' => $type,
                        'amount' => $amount,
                        'staff_id' => $res['staff_id'],
                        'cal_type' => 'positive'
                    ));
                }
            }

            // Insert deductions
            $deductions = json_decode($res['deductions_detail'], true);
            if (!empty($deductions)) {
                foreach ($deductions as $type => $amount) {
                    $this->CI->db->insert('payslip_allowance', array(
                        'payslip_id' => $payslip_id,
                        'allowance_type' => $type,
                        'amount' => $amount,
                        'staff_id' => $res['staff_id'],
                        'cal_type' => 'negative'
                    ));
                }
            }
        }

        $this->CI->db->trans_complete();
        return $this->CI->db->trans_status();
    }
}
