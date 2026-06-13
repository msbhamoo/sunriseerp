<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Accreport_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getDaybookData($from_date, $to_date)
    {
        $this->db->select('v.*, vi.debit_amount, vi.credit_amount, vi.narration as item_narration, l.name as ledger_name');
        $this->db->from('acc_vouchers v');
        $this->db->join('acc_voucher_items vi', 'vi.voucher_id = v.id');
        $this->db->join('acc_ledgers l', 'l.id = vi.ledger_id', 'left');
        $this->db->where('v.voucher_date >=', $from_date);
        $this->db->where('v.voucher_date <=', $to_date);
        $this->db->where('v.session_id', $this->current_session);
        $this->db->where_in('v.status', ['posted', 'reversed']); // Fix: Include reversed vouchers to balance with their reversal entries
        $this->db->order_by('v.voucher_date', 'asc');
        $this->db->order_by('v.id', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getCashbookData($from_date, $to_date)
    {
        $opposite_ledger_sql = "(SELECT GROUP_CONCAT(l2.name SEPARATOR ', ') FROM acc_voucher_items vi2 JOIN acc_ledgers l2 ON l2.id = vi2.ledger_id WHERE vi2.voucher_id = v.id AND ( (vi.debit_amount > 0 AND vi2.credit_amount > 0) OR (vi.credit_amount > 0 AND vi2.debit_amount > 0) ))";
        
        $this->db->select('v.*, vi.debit_amount, vi.credit_amount, vi.narration as item_narration, ' . $opposite_ledger_sql . ' as ledger_name', FALSE);
        $this->db->from('acc_vouchers v');
        $this->db->join('acc_voucher_items vi', 'vi.voucher_id = v.id');
        $this->db->join('acc_ledgers l', 'l.id = vi.ledger_id', 'left');
        $this->db->join('acc_ledger_groups g', 'g.id = l.group_id', 'left');
        $this->db->where('g.system_name', 'cash');
        $this->db->where('v.voucher_date >=', $from_date);
        $this->db->where('v.voucher_date <=', $to_date);
        $this->db->where('v.session_id', $this->current_session);
        $this->db->where_in('v.status', ['posted', 'reversed']); // Fix
        $this->db->order_by('v.voucher_date', 'asc');
        $this->db->order_by('v.id', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getBankbookData($from_date, $to_date, $ledger_id = null)
    {
        $opposite_ledger_sql = "(SELECT GROUP_CONCAT(l2.name SEPARATOR ', ') FROM acc_voucher_items vi2 JOIN acc_ledgers l2 ON l2.id = vi2.ledger_id WHERE vi2.voucher_id = v.id AND ( (vi.debit_amount > 0 AND vi2.credit_amount > 0) OR (vi.credit_amount > 0 AND vi2.debit_amount > 0) ))";
        
        $this->db->select('v.*, vi.debit_amount, vi.credit_amount, vi.narration as item_narration, ' . $opposite_ledger_sql . ' as ledger_name', FALSE);
        $this->db->from('acc_vouchers v');
        $this->db->join('acc_voucher_items vi', 'vi.voucher_id = v.id');
        $this->db->join('acc_ledgers l', 'l.id = vi.ledger_id', 'left');
        $this->db->join('acc_ledger_groups g', 'g.id = l.group_id', 'left');
        $this->db->where('g.system_name', 'bank');
        if ($ledger_id != null && $ledger_id != '') {
            $this->db->where('vi.ledger_id', $ledger_id);
        }
        $this->db->where('v.voucher_date >=', $from_date);
        $this->db->where('v.voucher_date <=', $to_date);
        $this->db->where('v.session_id', $this->current_session);
        $this->db->where_in('v.status', ['posted', 'reversed']); // Fix
        $this->db->order_by('v.voucher_date', 'asc');
        $this->db->order_by('v.id', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getLedgerStatement($ledger_id, $from_date, $to_date)
    {
        // 1. Get Opening Balance up to $from_date
        $this->db->select('opening_balance, opening_type');
        $this->db->from('acc_ledgers');
        $this->db->where('id', $ledger_id);
        $ledger = $this->db->get()->row();
        
        $opening_bal = 0;
        if ($ledger) {
            $opening_bal = ($ledger->opening_type == 'Dr') ? $ledger->opening_balance : -$ledger->opening_balance;
        }

        $this->db->select('SUM(vi.debit_amount) as total_debit, SUM(vi.credit_amount) as total_credit');
        $this->db->from('acc_vouchers v');
        $this->db->join('acc_voucher_items vi', 'vi.voucher_id = v.id');
        $this->db->where('vi.ledger_id', $ledger_id);
        $this->db->where('v.voucher_date <', $from_date);
        $this->db->where('v.session_id', $this->current_session);
        $this->db->where_in('v.status', ['posted', 'reversed']); // Fix
        $this->db->order_by('v.voucher_date', 'asc');
        $prev_txn = $this->db->get()->row();
        
        if ($prev_txn) {
            $opening_bal += ($prev_txn->total_debit - $prev_txn->total_credit);
        }

        // 2. Get Transactions for the period
        $opposite_ledger_sql = "(SELECT GROUP_CONCAT(l2.name SEPARATOR ', ') FROM acc_voucher_items vi2 JOIN acc_ledgers l2 ON l2.id = vi2.ledger_id WHERE vi2.voucher_id = v.id AND vi2.ledger_id != vi.ledger_id)";

        $this->db->select('v.voucher_date, v.voucher_no, v.voucher_type, vi.debit_amount, vi.credit_amount, vi.narration, ' . $opposite_ledger_sql . ' as opposite_ledger_name', FALSE);
        $this->db->from('acc_vouchers v');
        $this->db->join('acc_voucher_items vi', 'vi.voucher_id = v.id');
        $this->db->where('vi.ledger_id', $ledger_id);
        $this->db->where('v.voucher_date >=', $from_date);
        $this->db->where('v.voucher_date <=', $to_date);
        $this->db->where('v.session_id', $this->current_session);
        $this->db->where_in('v.status', ['posted', 'reversed']); // Fix
        $this->db->order_by('v.voucher_date', 'asc');
        $this->db->order_by('v.id', 'asc');
        $transactions = $this->db->get()->result_array();

        return array(
            'opening_balance' => $opening_bal,
            'transactions' => $transactions
        );
    }
    
    public function getOutstandingReport($type)
    {
        $group_sys = ($type == 'payable') ? 'sundry_creditors' : 'sundry_debtors';
        
        // Use materialized current_balance for much faster query (B4 Fix)
        $this->db->select('l.id, l.name, l.mobile, l.opening_balance, l.opening_type, l.current_balance');
        $this->db->from('acc_ledgers l');
        $this->db->join('acc_ledger_groups g', 'g.id = l.group_id');
        $this->db->where('g.system_name', $group_sys);
        $this->db->order_by('l.name', 'asc');
        
        $ledgers = $this->db->get()->result_array();
        
        $outstanding = array();
        foreach ($ledgers as $l) {
            $bal = ($l['opening_type'] == 'Dr') ? $l['opening_balance'] : -$l['opening_balance'];
            $bal += $l['current_balance'];
            
            if (abs($bal) > 0.001) {
                $l['current_balance'] = $bal;
                $outstanding[] = $l;
            }
        }
        return $outstanding;
    }

    public function getExpIncomeTypeReport($from_date, $to_date, $type)
    {
        $this->db->select('et.name as type_name, SUM(vi.debit_amount) as total_debit, SUM(vi.credit_amount) as total_credit');
        $this->db->from('acc_expense_types et');
        $this->db->join('acc_voucher_items vi', 'vi.expense_type_id = et.id');
        $this->db->join('acc_vouchers v', 'v.id = vi.voucher_id');
        $this->db->where('et.type', $type);
        $this->db->where('v.voucher_date >=', $from_date);
        $this->db->where('v.voucher_date <=', $to_date);
        $this->db->where('v.session_id', $this->current_session);
        $this->db->where_in('v.status', ['posted', 'reversed']); // Fix
        $this->db->group_by('et.id');
        $this->db->order_by('et.name', 'asc');
        
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getTrialBalance($to_date = null)
    {
        $date_cond = $to_date ? ' AND v.voucher_date <= ' . $this->db->escape($to_date) : '';
        
        $this->db->select('l.id, l.name, l.opening_balance, l.opening_type, g.name as group_name, g.system_name,
                          SUM(CASE WHEN v.session_id = '.$this->db->escape($this->current_session).$date_cond.' THEN vi.debit_amount ELSE 0 END) as total_debit, 
                          SUM(CASE WHEN v.session_id = '.$this->db->escape($this->current_session).$date_cond.' THEN vi.credit_amount ELSE 0 END) as total_credit', FALSE);
        $this->db->from('acc_ledgers l');
        $this->db->join('acc_ledger_groups g', 'g.id = l.group_id');
        $this->db->join('acc_voucher_items vi', 'vi.ledger_id = l.id', 'left');
        $this->db->join('acc_vouchers v', 'v.id = vi.voucher_id AND v.status IN (\'posted\', \'reversed\')', 'left'); // Fix
        $this->db->group_by('l.id');
        $this->db->order_by('g.name', 'asc');
        $this->db->order_by('l.name', 'asc');
        
        $query = $this->db->get();
        $ledgers = $query->result_array();
        
        $trial_balance = array();
        foreach ($ledgers as $l) {
            $bal = ($l['opening_type'] == 'Dr') ? $l['opening_balance'] : -$l['opening_balance'];
            $bal += ($l['total_debit'] - $l['total_credit']);
            
            if (abs($bal) > 0.001) {
                $l['closing_balance'] = $bal; // Positive = Dr, Negative = Cr
                $trial_balance[] = $l;
            }
        }
        return $trial_balance;
    }

    /**
     * Reusable P&L logic decoupled from DB fetching (B2 Fix)
     */
    private function calculateProfitLossFromTrial($trial)
    {
        $pl_groups = ['direct_expense', 'indirect_expense', 'purchase', 'direct_income', 'indirect_income', 'sales'];
        $data = [
            'expenses' => [],
            'incomes' => [],
            'total_expense' => 0,
            'total_income' => 0,
            'net_profit' => 0
        ];

        foreach ($trial as $row) {
            if (in_array($row['system_name'], $pl_groups)) {
                if (in_array($row['system_name'], ['direct_expense', 'indirect_expense', 'purchase'])) {
                    // Normal balance is Dr (Positive)
                    $bal = $row['closing_balance']; 
                    if (abs($bal) > 0.001) {
                        $data['expenses'][] = ['name' => $row['name'], 'group' => $row['group_name'], 'amount' => $bal];
                        $data['total_expense'] += $bal;
                    }
                } else {
                    // Normal balance is Cr (Negative)
                    $bal = -$row['closing_balance'];
                    if (abs($bal) > 0.001) {
                        $data['incomes'][] = ['name' => $row['name'], 'group' => $row['group_name'], 'amount' => $bal];
                        $data['total_income'] += $bal;
                    }
                }
            }
        }

        $data['net_profit'] = $data['total_income'] - $data['total_expense'];
        return $data;
    }

    public function getProfitLoss($from_date, $to_date)
    {
        $trial = $this->getTrialBalance($to_date);
        return $this->calculateProfitLossFromTrial($trial);
    }

    public function getBalanceSheet($to_date)
    {
        $trial = $this->getTrialBalance($to_date);
        
        // Everything not in P&L is in Balance Sheet
        $bs_groups = ['bank', 'cash', 'sundry_debtors', 'current_assets', 'fixed_assets', 'sundry_creditors', 'current_liabilities', 'capital', 'loans_liability', 'duties_taxes'];
        $data = [
            'assets' => [],
            'liabilities' => [],
            'total_assets' => 0,
            'total_liabilities' => 0
        ];

        foreach ($trial as $row) {
            if (in_array($row['system_name'], $bs_groups)) {
                if (in_array($row['system_name'], ['bank', 'cash', 'sundry_debtors', 'current_assets', 'fixed_assets'])) {
                    // Assets - Normal balance Dr (Positive)
                    $bal = $row['closing_balance'];
                    if (abs($bal) > 0.001) {
                        $data['assets'][] = ['name' => $row['name'], 'group' => $row['group_name'], 'amount' => $bal];
                        $data['total_assets'] += $bal;
                    }
                } else {
                    // Liabilities/Capital - Normal balance Cr (Negative)
                    $bal = -$row['closing_balance'];
                    if (abs($bal) > 0.001) {
                        $data['liabilities'][] = ['name' => $row['name'], 'group' => $row['group_name'], 'amount' => $bal];
                        $data['total_liabilities'] += $bal;
                    }
                }
            }
        }

        // B2 Fix: Add Net Profit to Liabilities (Capital) side using pre-fetched trial
        $pl = $this->calculateProfitLossFromTrial($trial);
        if ($pl['net_profit'] != 0) {
            $data['liabilities'][] = ['name' => 'Net Profit (Current Year)', 'group' => 'P&L Account', 'amount' => $pl['net_profit']];
            $data['total_liabilities'] += $pl['net_profit'];
        }

        return $data;
    }
}
