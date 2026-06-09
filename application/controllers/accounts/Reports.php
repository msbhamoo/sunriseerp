<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Reports extends MY_Addon_AccountsController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function daybook()
    {
        if (!$this->rbac->hasPrivilege('acc_daybook_report', 'can_view')) {
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'accounts/reports/daybook');

        $data['title'] = $this->lang->line('day_book_report');
        $this->processReportFilters($data, 'daybook');

        $this->load->view('layout/header', $data);
        $this->load->view('accounts/reports/daybook', $data);
        $this->load->view('layout/footer', $data);
    }

    public function cashbook()
    {
        if (!$this->rbac->hasPrivilege('acc_cashbook_report', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'accounts/reports/cashbook');

        $data['title'] = $this->lang->line('cash_book_report');
        $this->processReportFilters($data, 'cashbook');

        $this->load->view('layout/header', $data);
        $this->load->view('accounts/reports/cashbook', $data);
        $this->load->view('layout/footer', $data);
    }

    public function bankbook()
    {
        if (!$this->rbac->hasPrivilege('acc_bankbook_report', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'accounts/reports/bankbook');

        $data['title'] = $this->lang->line('bank_book_report');
        $data['bank_ledgers'] = $this->accledger_model->getLedgersBySystemGroup('bank');
        $data['ledger_id'] = $this->input->post('ledger_id');
        
        $this->processReportFilters($data, 'bankbook');

        $this->load->view('layout/header', $data);
        $this->load->view('accounts/reports/bankbook', $data);
        $this->load->view('layout/footer', $data);
    }

    public function statement()
    {
        if (!$this->rbac->hasPrivilege('acc_statement', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'accounts/reports/statement');

        $data['title'] = $this->lang->line('statement');
        $data['ledgers'] = $this->accledger_model->getLedgers();
        
        $ledger_id = $this->input->post('ledger_id');
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        
        if (empty($date_from)) {
            $date_from = date($this->customlib->getSchoolDateFormat(), strtotime('-30 days'));
        }
        if (empty($date_to)) {
            $date_to = date($this->customlib->getSchoolDateFormat());
        }
        
        if (empty($ledger_id) && !empty($data['ledgers'])) {
            // Default to first ledger if not searched
            $ledger_id = $data['ledgers'][0]['id'];
        }
        
        $data['ledger_id'] = $ledger_id;
        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        
        if ($ledger_id) {
            $db_date_from = date('Y-m-d', $this->customlib->datetostrtotime($date_from));
            $db_date_to = date('Y-m-d', $this->customlib->datetostrtotime($date_to));
            $data['result'] = $this->accreport_model->getLedgerStatement($ledger_id, $db_date_from, $db_date_to);
        }

        $this->load->view('layout/header', $data);
        $this->load->view('accounts/reports/statement', $data);
        $this->load->view('layout/footer', $data);
    }
    
    public function outstanding()
    {
        if (!$this->rbac->hasPrivilege('acc_outstanding', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'accounts/reports/outstanding');

        $data['title'] = $this->lang->line('outstanding');
        $data['type'] = $this->input->post('type') ? $this->input->post('type') : 'payable';
        
        $data['result'] = $this->accreport_model->getOutstandingReport($data['type']);

        $this->load->view('layout/header', $data);
        $this->load->view('accounts/reports/outstanding', $data);
        $this->load->view('layout/footer', $data);
    }

    public function expincome_type()
    {
        if (!$this->rbac->hasPrivilege('acc_expincome_report', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'accounts/reports/expincome_type');

        $data['title'] = $this->lang->line('exp_income_type_report');
        $data['type'] = $this->input->post('type') ? $this->input->post('type') : 'expense';
        
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        
        if (empty($date_from)) {
            $date_from = date($this->customlib->getSchoolDateFormat(), strtotime('-30 days'));
        }
        if (empty($date_to)) {
            $date_to = date($this->customlib->getSchoolDateFormat());
        }
        
        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        
        $db_date_from = date('Y-m-d', $this->customlib->datetostrtotime($date_from));
        $db_date_to = date('Y-m-d', $this->customlib->datetostrtotime($date_to));
        
        $data['result'] = $this->accreport_model->getExpIncomeTypeReport($db_date_from, $db_date_to, $data['type']);

        $this->load->view('layout/header', $data);
        $this->load->view('accounts/reports/expincome_type', $data);
        $this->load->view('layout/footer', $data);
    }

    private function processReportFilters(&$data, $report_type)
    {
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        
        if (empty($date_from)) {
            $date_from = date($this->customlib->getSchoolDateFormat());
        }
        if (empty($date_to)) {
            $date_to = date($this->customlib->getSchoolDateFormat());
        }
        
        $data['date_from'] = $date_from;
        $data['date_to'] = $date_to;
        
        $db_date_from = date('Y-m-d', $this->customlib->datetostrtotime($date_from));
        $db_date_to = date('Y-m-d', $this->customlib->datetostrtotime($date_to));

        if ($report_type == 'daybook') {
            $data['result'] = $this->accreport_model->getDaybookData($db_date_from, $db_date_to);
        } elseif ($report_type == 'cashbook') {
            $data['cash_result'] = $this->accreport_model->getCashbookData($db_date_from, $db_date_to);
            $data['bank_result'] = $this->accreport_model->getBankbookData($db_date_from, $db_date_to, null);
        } elseif ($report_type == 'bankbook') {
            $ledger_id = $this->input->post('ledger_id');
            if (empty($ledger_id) && !empty($data['bank_ledgers'])) {
                $ledger_id = $data['bank_ledgers'][0]['id'];
                $data['ledger_id'] = $ledger_id; // Pass back to view
            }
            if ($ledger_id) {
                $data['result'] = $this->accreport_model->getBankbookData($db_date_from, $db_date_to, $ledger_id);
                $stmt = $this->accreport_model->getLedgerStatement($ledger_id, $db_date_from, $db_date_to);
                $data['opening_balances'] = ['bank' => $stmt['opening_balance']];
                
                $dr_sum = 0;
                $cr_sum = 0;
                foreach ($data['result'] as $r) {
                    $dr_sum += $r['debit_amount'];
                    $cr_sum += $r['credit_amount'];
                }
                $data['closing_balances'] = ['bank' => $stmt['opening_balance'] + $dr_sum - $cr_sum];
            } else {
                $data['result'] = [];
            }
        }
        
        // Get Opening and Closing Balances for Cash and Bank
        if ($report_type == 'daybook' || $report_type == 'cashbook') {
            $prev_date = date('Y-m-d', strtotime('-1 day', strtotime($db_date_from)));
            $trial_before = $this->accreport_model->getTrialBalance($prev_date);
            $trial_after = $this->accreport_model->getTrialBalance($db_date_to);
            
            $data['opening_balances'] = ['cash' => 0, 'bank' => 0];
            $data['closing_balances'] = ['cash' => 0, 'bank' => 0];
            
            foreach ($trial_before as $row) {
                if ($row['system_name'] == 'cash') {
                    $data['opening_balances']['cash'] += $row['closing_balance'];
                } elseif ($row['system_name'] == 'bank') {
                    $data['opening_balances']['bank'] += $row['closing_balance'];
                }
            }
            
            foreach ($trial_after as $row) {
                if ($row['system_name'] == 'cash') {
                    $data['closing_balances']['cash'] += $row['closing_balance'];
                } elseif ($row['system_name'] == 'bank') {
                    $data['closing_balances']['bank'] += $row['closing_balance'];
                }
            }
        }
    }

    public function trialbalance()
    {
        if (!$this->rbac->hasPrivilege('acc_statement', 'can_view')) { // Using statement privilege for now
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'accounts/reports/trialbalance');

        $data['title'] = 'Trial Balance';
        
        if ($this->input->post('search')) {
            $date_to = $this->input->post('date_to');
            $data['date_to'] = $date_to;
            $db_date_to = date('Y-m-d', $this->customlib->datetostrtotime($date_to));
            $data['result'] = $this->accreport_model->getTrialBalance($db_date_to);
        } else {
            // Default load
            $db_date_to = date('Y-m-d');
            $data['date_to'] = date($this->customlib->getSchoolDateFormat(), strtotime($db_date_to));
            $data['result'] = $this->accreport_model->getTrialBalance($db_date_to);
        }

        $this->load->view('layout/header', $data);
        $this->load->view('accounts/reports/trialbalance', $data);
        $this->load->view('layout/footer', $data);
    }

    public function profitloss()
    {
        if (!$this->rbac->hasPrivilege('acc_statement', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'accounts/reports/profitloss');

        $data['title'] = 'Profit & Loss Account';
        
        if ($this->input->post('search')) {
            $date_from = $this->input->post('date_from');
            $date_to = $this->input->post('date_to');
            $data['date_from'] = $date_from;
            $data['date_to'] = $date_to;
            $db_date_from = date('Y-m-d', $this->customlib->datetostrtotime($date_from));
            $db_date_to = date('Y-m-d', $this->customlib->datetostrtotime($date_to));
            $data['result'] = $this->accreport_model->getProfitLoss($db_date_from, $db_date_to);
        } else {
            // Default load
            $db_date_to = date('Y-m-d');
            $data['date_to'] = date($this->customlib->getSchoolDateFormat(), strtotime($db_date_to));
            $data['result'] = $this->accreport_model->getProfitLoss(null, $db_date_to);
        }

        $this->load->view('layout/header', $data);
        $this->load->view('accounts/reports/profitloss', $data);
        $this->load->view('layout/footer', $data);
    }

    public function balancesheet()
    {
        if (!$this->rbac->hasPrivilege('acc_statement', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'accounts/reports/balancesheet');

        $data['title'] = 'Balance Sheet';
        
        if ($this->input->post('search')) {
            $date_to = $this->input->post('date_to');
            $data['date_to'] = $date_to;
            $db_date_to = date('Y-m-d', $this->customlib->datetostrtotime($date_to));
            $data['result'] = $this->accreport_model->getBalanceSheet($db_date_to);
        } else {
            // Default load
            $db_date_to = date('Y-m-d');
            $data['date_to'] = date($this->customlib->getSchoolDateFormat(), strtotime($db_date_to));
            $data['result'] = $this->accreport_model->getBalanceSheet($db_date_to);
        }

        $this->load->view('layout/header', $data);
        $this->load->view('accounts/reports/balancesheet', $data);
        $this->load->view('layout/footer', $data);
    }
}
