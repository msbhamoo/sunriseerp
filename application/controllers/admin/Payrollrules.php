<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Payrollrules extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('payrollrule_model');
        $this->load->library('payrollengine');
        $this->load->library('form_validation');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('payroll_rules', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/payrollrules');

        $data['rule_groups'] = $this->payrollrule_model->getRuleGroups();
        $data['recent_runs'] = array_slice($this->payrollrule_model->getEngineRuns(), 0, 5);

        // Add rule counts
        foreach ($data['rule_groups'] as &$group) {
            $rules = $this->payrollrule_model->getRules($group['id']);
            $group['total_rules'] = count($rules);
            $group['active_rules'] = count(array_filter($rules, function($r) { return $r['is_active'] == 1; }));
        }

        $this->load->view('layout/header');
        $this->load->view('admin/payrollrules/dashboard', $data);
        $this->load->view('layout/footer');
    }

    public function rules($group_id)
    {
        if (!$this->rbac->hasPrivilege('payroll_rules', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/payrollrules');

        $groups = $this->payrollrule_model->getRuleGroups();
        $current_group = null;
        foreach ($groups as $g) {
            if ($g['id'] == $group_id) {
                $current_group = $g;
                break;
            }
        }

        if (!$current_group) {
            redirect('admin/payrollrules');
        }

        $data['group'] = $current_group;
        $data['rules'] = $this->payrollrule_model->getRules($group_id);

        $this->load->view('layout/header');
        $this->load->view('admin/payrollrules/rules_list', $data);
        $this->load->view('layout/footer');
    }

    public function addrule($group_id, $id = null)
    {
        if (!$this->rbac->hasPrivilege('payroll_rules', 'can_add')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/payrollrules');

        $data['group_id'] = $group_id;
        $data['rule'] = null;
        
        if ($id) {
            $data['rule'] = $this->payrollrule_model->getRule($id);
        }

        $data['roles'] = $this->role_model->get();

        $this->load->view('layout/header');
        $this->load->view('admin/payrollrules/rule_form', $data);
        $this->load->view('layout/footer');
    }

    public function saverule()
    {
        if (!$this->rbac->hasPrivilege('payroll_rules', 'can_add')) {
            access_denied();
        }

        $this->form_validation->set_rules('name', 'Rule Name', 'required|trim');
        $this->form_validation->set_rules('code', 'Rule Code', 'required|trim');

        if ($this->form_validation->run() == false) {
            echo json_encode(array('status' => 'fail', 'message' => validation_errors()));
            return;
        }

        $id = $this->input->post('id');
        $group_id = $this->input->post('rule_group_id');
        
        $roles = $this->input->post('applies_to');
        $applies_to = !empty($roles) ? json_encode($roles) : null;

        $rule_data = array(
            'id' => $id,
            'rule_group_id' => $group_id,
            'name' => $this->input->post('name'),
            'code' => $this->input->post('code'),
            'description' => $this->input->post('description'),
            'rule_type' => $this->input->post('rule_type') ?: 'calculation',
            'applies_to' => $applies_to,
            'priority' => $this->input->post('priority') ?: 100,
            'is_active' => $this->input->post('is_active') ?: 1,
            'effective_from' => $this->input->post('effective_from') ? date('Y-m-d', strtotime($this->input->post('effective_from'))) : null,
            'effective_to' => $this->input->post('effective_to') ? date('Y-m-d', strtotime($this->input->post('effective_to'))) : null,
        );

        $conditions = array();
        $c_fields = $this->input->post('condition_field');
        if ($c_fields) {
            $c_ops = $this->input->post('condition_operator');
            $c_vals = $this->input->post('condition_value');
            $c_grps = $this->input->post('condition_group');
            for ($i=0; $i<count($c_fields); $i++) {
                if (!empty($c_fields[$i])) {
                    $conditions[] = array(
                        'field' => $c_fields[$i],
                        'operator' => $c_ops[$i],
                        'value' => $c_vals[$i],
                        'condition_group' => $c_grps[$i] ?: 1,
                        'sort_order' => $i
                    );
                }
            }
        }

        $actions = array();
        $a_types = $this->input->post('action_type');
        if ($a_types) {
            $a_targets = $this->input->post('action_target');
            $a_vals = $this->input->post('action_value');
            for ($i=0; $i<count($a_types); $i++) {
                if (!empty($a_types[$i])) {
                    $actions[] = array(
                        'action_type' => $a_types[$i],
                        'target_field' => $a_targets[$i] ?: '',
                        'value' => $a_vals[$i] ?: '',
                        'sort_order' => $i
                    );
                }
            }
        }

        $saved_id = $this->payrollrule_model->saveRule($rule_data, $conditions, $actions);

        if ($saved_id) {
            echo json_encode(array('status' => 'success', 'message' => 'Rule saved successfully', 'redirect' => site_url('admin/payrollrules/rules/'.$group_id)));
        } else {
            echo json_encode(array('status' => 'fail', 'message' => 'Failed to save rule'));
        }
    }

    public function togglerule($id)
    {
        if (!$this->rbac->hasPrivilege('payroll_rules', 'can_edit')) {
            access_denied();
        }
        $status = $this->input->post('status');
        $this->payrollrule_model->toggleRule($id, $status);
        echo json_encode(array('status' => 'success'));
    }

    public function deleterule($id)
    {
        if (!$this->rbac->hasPrivilege('payroll_rules', 'can_delete')) {
            access_denied();
        }
        $this->payrollrule_model->deleteRule($id);
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function versions($rule_id)
    {
        if (!$this->rbac->hasPrivilege('payroll_rules', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/payrollrules');

        $data['rule'] = $this->payrollrule_model->getRule($rule_id);
        $data['versions'] = $this->payrollrule_model->getVersionHistory($rule_id);

        $this->load->view('layout/header');
        $this->load->view('admin/payrollrules/rule_versions', $data);
        $this->load->view('layout/footer');
    }

    public function simulate()
    {
        if (!$this->rbac->hasPrivilege('payroll_rules', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/payrollrules');

        $data['roles'] = $this->role_model->get();

        $this->load->view('layout/header');
        $this->load->view('admin/payrollrules/simulate_form', $data);
        $this->load->view('layout/footer');
    }

    public function runsimulation()
    {
        // bypassed

        $month = $this->input->post('month');
        $year = $this->input->post('year');
        $role_id = $this->input->post('role_id');

        if (empty($month) || empty($year)) {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger">Month and Year are required</div>');
            redirect('admin/payrollrules/simulate');
        }

        $run_id = $this->payrollengine->runSimulation($month, $year, $role_id);

        if ($run_id) {
            redirect('admin/payrollrules/runtrace/'.$run_id);
        } else {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger">Simulation failed or no staff found.</div>');
            redirect('admin/payrollrules/simulate');
        }
    }

    public function runlogs()
    {
        if (!$this->rbac->hasPrivilege('payroll_rules', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/payrollrules');

        $data['runs'] = $this->payrollrule_model->getEngineRuns();

        $this->load->view('layout/header');
        $this->load->view('admin/payrollrules/run_logs', $data);
        $this->load->view('layout/footer');
    }

    public function runtrace($run_id)
    {
        if (!$this->rbac->hasPrivilege('payroll_rules', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/payrollrules');

        $data['run_id'] = $run_id;
        $data['results'] = $this->payrollrule_model->getSimulationResults($run_id);
        
        $runs = $this->payrollrule_model->getEngineRuns();
        foreach($runs as $r) {
            if ($r['id'] == $run_id) {
                $data['run_detail'] = $r;
                break;
            }
        }

        $this->load->view('layout/header');
        $this->load->view('admin/payrollrules/run_trace', $data);
        $this->load->view('layout/footer');
    }

    public function applySimulation($run_id)
    {
        if (!$this->rbac->hasPrivilege('payroll_rules', 'can_add')) {
            access_denied();
        }

        $success = $this->payrollengine->applySimulation($run_id);

        if ($success) {
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Payroll applied successfully. It is now visible in the standard Payroll module.</div>');
        } else {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger">Failed to apply payroll.</div>');
        }

        redirect('admin/payrollrules/runtrace/'.$run_id);
    }
}
