<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Ledgermaster extends MY_Addon_AccountsController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('acc_ledger_master', 'can_view')) {
            access_denied();
        }

        // Auto-seed default school data and upgrade schema if empty/missing
        $this->accledger_model->seedDefaultData();
        
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'accounts/ledgermaster');
        
        $data['title'] = $this->lang->line('ledger_list');
        $data['ledger_groups'] = $this->accledger_model->getLedgerGroups();
        $data['banks'] = $this->accledger_model->getBanks();
        
        $this->form_validation->set_rules('group_id', $this->lang->line('ledger_group'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('name', $this->lang->line('ledger_name'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('accounts/ledger_master/index', $data);
            $this->load->view('layout/footer', $data);
        } else {
            if (!$this->rbac->hasPrivilege('acc_ledger_master', 'can_add')) {
                access_denied();
            }
            $opening_date = $this->input->post('opening_date');
            $data_insert = array(
                'group_id' => $this->input->post('group_id'),
                'name' => $this->input->post('name'),
                'mobile' => $this->input->post('mobile'),
                'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
                'state' => $this->input->post('state'),
                'gst_no' => $this->input->post('gst_no'),
                'pan_no' => $this->input->post('pan_no'),
                'aadhar_no' => $this->input->post('aadhar_no'),
                'bank_id' => $this->input->post('bank_id') ? $this->input->post('bank_id') : null,
                'account_no' => $this->input->post('account_no'),
                'ifsc_code' => $this->input->post('ifsc_code'),
                'branch' => $this->input->post('branch'),
                'opening_type' => $this->input->post('opening_type'),
                'opening_balance' => $this->input->post('opening_balance') ? $this->input->post('opening_balance') : 0.00,
                'opening_date' => $opening_date ? date('Y-m-d', $this->customlib->datetostrtotime($opening_date)) : null
            );
            $this->accledger_model->addLedger($data_insert);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('accounts/ledgermaster/index' . ($this->input->get('layout') ? '?layout='.$this->input->get('layout') : ''));
        }
    }

    public function getlist()
    {
        if (!$this->rbac->hasPrivilege('acc_ledger_master', 'can_view')) {
            access_denied();
        }
        $result = $this->accledger_model->getDatatableLedgers();
        $result = json_decode($result);
        $dt_data = array();
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();

        if (!empty($result->data)) {
            foreach ($result->data as $value) {
                $row = array();
                $action = "<div class='d-flex gap-0-5 text-right float-right'>";
                
                if ($this->rbac->hasPrivilege('acc_ledger_master', 'can_edit')) {
                    $action .= "<a href='" . site_url('accounts/ledgermaster/edit/' . $value->id) . "' class='btn btn-primary btn-xs' data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
                }
                if ($this->rbac->hasPrivilege('acc_ledger_master', 'can_delete')) {
                    $action .= "<a href='" . site_url('accounts/ledgermaster/delete/' . $value->id) . "' class='btn btn-primary btn-xs' data-toggle='tooltip' title='" . $this->lang->line('delete') . "' onclick='return confirm(\"" . $this->lang->line('delete_confirm') . "\");'><i class='fa fa-remove'></i></a>";
                }
                $action .= "</div>";

                $row[] = $value->name;
                $row[] = $value->group_name;
                $row[] = $value->mobile;
                $row[] = $currency_symbol . amountFormat($value->opening_balance) . " " . $value->opening_type;
                $row[] = $action;
                $dt_data[] = $row;
            }
        }

        $json_data = array(
            "draw" => intval($result->draw),
            "recordsTotal" => intval($result->recordsTotal),
            "recordsFiltered" => intval($result->recordsFiltered),
            "data" => $dt_data,
        );
        echo json_encode($json_data);
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('acc_ledger_master', 'can_edit')) {
            access_denied();
        }

        $data['title'] = $this->lang->line('edit_ledger');
        $data['id'] = $id;
        $data['ledger'] = $this->accledger_model->getLedgers($id);
        $data['ledger_groups'] = $this->accledger_model->getLedgerGroups();
        $data['banks'] = $this->accledger_model->getBanks();

        $this->form_validation->set_rules('group_id', $this->lang->line('ledger_group'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('name', $this->lang->line('ledger_name'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('accounts/ledger_master/edit', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $opening_date = $this->input->post('opening_date');
            $data_update = array(
                'id' => $id,
                'group_id' => $this->input->post('group_id'),
                'name' => $this->input->post('name'),
                'mobile' => $this->input->post('mobile'),
                'email' => $this->input->post('email'),
                'address' => $this->input->post('address'),
                'state' => $this->input->post('state'),
                'gst_no' => $this->input->post('gst_no'),
                'pan_no' => $this->input->post('pan_no'),
                'aadhar_no' => $this->input->post('aadhar_no'),
                'bank_id' => $this->input->post('bank_id') ? $this->input->post('bank_id') : null,
                'account_no' => $this->input->post('account_no'),
                'ifsc_code' => $this->input->post('ifsc_code'),
                'branch' => $this->input->post('branch'),
                'opening_type' => $this->input->post('opening_type'),
                'opening_balance' => $this->input->post('opening_balance') ? $this->input->post('opening_balance') : 0.00,
                'opening_date' => $opening_date ? date('Y-m-d', $this->customlib->datetostrtotime($opening_date)) : null
            );
            $this->accledger_model->addLedger($data_update);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('accounts/ledgermaster/index' . ($this->input->get('layout') ? '?layout='.$this->input->get('layout') : ''));
        }
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('acc_ledger_master', 'can_delete')) {
            access_denied();
        }
        $this->accledger_model->deleteLedger($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('delete_message') . '</div>');
        redirect('accounts/ledgermaster');
    }

    public function get_balance()
    {
        $id = $this->input->post('id');
        $date = date('Y-m-d');
        
        $this->load->model('accreport_model');
        $trial = $this->accreport_model->getTrialBalance($date);
        
        $balance = 0;
        $type = 'Dr';
        
        foreach ($trial as $row) {
            if ($row['id'] == $id) {
                // Trial balance returns closing_balance
                // For Dr groups (assets, expenses), positive is Dr, negative is Cr
                // For Cr groups (liabilities, income), positive is Cr, negative is Dr
                $bal = $row['closing_balance'];
                if (in_array($row['system_name'], ['bank', 'cash', 'sundry_debtors', 'current_assets', 'fixed_assets', 'direct_expense', 'indirect_expense', 'purchase'])) {
                    $type = ($bal >= 0) ? 'Dr' : 'Cr';
                } else {
                    $type = ($bal >= 0) ? 'Cr' : 'Dr';
                }
                $balance = abs($bal);
                break;
            }
        }
        
        echo json_encode([
            'status' => 'success',
            'balance' => $balance,
            'type' => $type
        ]);
    }

    public function addbank()
    {
        if (!$this->rbac->hasPrivilege('acc_ledger_master', 'can_add')) {
            echo json_encode(array('status' => 'fail', 'error' => 'Access Denied'));
            return;
        }
        $name = $this->input->post('name');
        if (!empty($name)) {
            $this->db->insert('acc_banks', array('name' => $name, 'is_active' => 1));
            echo json_encode(array('status' => 'success', 'id' => $this->db->insert_id(), 'name' => $name));
        } else {
            echo json_encode(array('status' => 'fail', 'error' => 'Bank name is required'));
        }
    }
}
