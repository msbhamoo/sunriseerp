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

        $this->db->select('v.voucher_date, v.voucher_no, v.voucher_type, v.narration as voucher_narration, vi.debit_amount, vi.credit_amount, vi.narration, ' . $opposite_ledger_sql . ' as opposite_ledger_name', FALSE);
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
    // =====================================================================
    // RECONCILIATION  (read-only; never changes data)
    // =====================================================================

    /**
     * Run all reconciliation checks for a date range (Y-m-d).
     * Returns ['checks' => [key => ['title','rows'=>[]]], 'balances' => [...], 'daily' => [...]]
     * Every check is a list of offending rows; an empty list means OK.
     */
    public function getReconciliation($from, $to)
    {
        $e_from = $this->db->escape($from);
        $e_to = $this->db->escape($to);
        $sess = $this->db->escape($this->current_session);
        $checks = [];
        $daily = [];
        $neg = [];

        // ---------- Fee deposits vs vouchers ----------
        $vmap = [];
        $q = $this->db->query("SELECT v.id, v.voucher_no, v.voucher_date, v.status, v.reference_id, v.payment_method,
                    (SELECT COALESCE(SUM(vi.debit_amount),0) FROM acc_voucher_items vi WHERE vi.voucher_id = v.id) AS dr,
                    (SELECT GROUP_CONCAT(DISTINCT g.system_name) FROM acc_voucher_items vi JOIN acc_ledgers l ON l.id = vi.ledger_id
                        LEFT JOIN acc_ledger_groups g ON g.id = l.group_id WHERE vi.voucher_id = v.id AND vi.debit_amount > 0) AS dr_groups
                 FROM acc_vouchers v WHERE v.reference_module = 'fee_collection'");
        foreach ($q->result_array() as $v) {
            $vmap[$v['reference_id']][] = $v;
        }

        $fee_missing = $fee_amount = $fee_mode = $fee_orphan = [];
        $seen_refs = [];
        $bank_modes = ['cheque', 'dd', 'upi', 'bank_transfer', 'bank transfer', 'net banking', 'net_banking'];
        if ($this->db->table_exists('student_fees_deposite')) {
            $deps = $this->db->query("SELECT id, amount_detail FROM student_fees_deposite")->result_array();
            foreach ($deps as $d) {
                $detail = json_decode($d['amount_detail'], true);
                if (!is_array($detail)) {
                    continue;
                }
                foreach ($detail as $inv => $row) {
                    $ref = $d['id'] . '_' . $inv;
                    $seen_refs[$ref] = true;
                    $date = date('Y-m-d', strtotime($row['date'] ?? 'now'));
                    $collected = floatval($row['amount'] ?? 0) + floatval($row['amount_fine'] ?? 0);
                    if ($collected <= 0 || $date < $from || $date > $to) {
                        continue;
                    }
                    $posted = null;
                    foreach (($vmap[$ref] ?? []) as $v) {
                        if ($v['status'] === 'posted') {
                            $posted = $v;
                            break;
                        }
                    }
                    $label = 'Fee deposit #' . $d['id'] . ' / inv ' . $inv;
                    if (!$posted) {
                        $fee_missing[] = ['date' => $date, 'ref' => $label, 'amount' => $collected,
                            'detail' => 'Fee collected (' . ($row['payment_mode'] ?? 'Cash') . ') but no posted voucher',
                            'fix' => 'sync_fee', 'fix_id' => $d['id']];
                        continue;
                    }
                    if (abs($posted['dr'] - $collected) > 0.01) {
                        $fee_amount[] = ['date' => $date, 'ref' => $label, 'amount' => $collected,
                            'detail' => 'Fee = ' . number_format($collected, 2) . ' but voucher ' . $posted['voucher_no'] . ' = ' . number_format($posted['dr'], 2),
                            'fix' => '', 'fix_id' => ''];
                    }
                    $mode = strtolower($row['payment_mode'] ?? 'cash');
                    $groups = $posted['dr_groups'] ?? '';
                    if ($mode === 'cash' && strpos($groups, 'cash') === false) {
                        $fee_mode[] = ['date' => $date, 'ref' => $label, 'amount' => $collected,
                            'detail' => 'Paid by Cash but voucher ' . $posted['voucher_no'] . ' debits: ' . ($groups ?: 'unknown'), 'fix' => '', 'fix_id' => ''];
                    } elseif (in_array($mode, $bank_modes, true) && strpos($groups, 'bank') === false) {
                        $fee_mode[] = ['date' => $date, 'ref' => $label, 'amount' => $collected,
                            'detail' => 'Paid by ' . $mode . ' but voucher ' . $posted['voucher_no'] . ' debits: ' . ($groups ?: 'unknown'), 'fix' => '', 'fix_id' => ''];
                    }
                }
            }
        }
        foreach ($vmap as $ref => $list) {
            foreach ($list as $v) {
                if ($v['status'] !== 'posted' || strpos($ref, '_rev') !== false) {
                    continue;
                }
                if ($v['voucher_date'] < $from || $v['voucher_date'] > $to) {
                    continue;
                }
                if (!isset($seen_refs[$ref])) {
                    $fee_orphan[] = ['date' => $v['voucher_date'], 'ref' => $v['voucher_no'], 'amount' => $v['dr'],
                        'detail' => 'Voucher for fee ' . $ref . ' but the fee record no longer exists', 'fix' => '', 'fix_id' => ''];
                }
            }
        }
        $checks['fee_missing'] = ['title' => 'Fee collected but no voucher (missing from books)', 'rows' => $fee_missing];
        $checks['fee_orphan'] = ['title' => 'Fee voucher exists but fee record was deleted', 'rows' => $fee_orphan];
        $checks['fee_amount'] = ['title' => 'Fee amount differs from voucher amount', 'rows' => $fee_amount];
        $checks['fee_mode'] = ['title' => 'Payment mode differs from ledger (cash vs bank)', 'rows' => $fee_mode];

        // ---------- Expense / income / payroll without voucher ----------
        $st = $this->db->where('id', 1)->get('acc_settings')->row_array();
        $exp = $inc = $pay = [];
        if (!empty($st['enable_expense_sync']) && $this->db->table_exists('expenses')) {
            $r = $this->db->query("SELECT e.id, e.date, e.amount, e.name FROM expenses e
                WHERE e.is_active = 'yes' AND e.amount > 0 AND e.date BETWEEN $e_from AND $e_to
                AND NOT EXISTS (SELECT 1 FROM acc_vouchers v WHERE v.reference_module = 'ss_expense' AND v.reference_id = CAST(e.id AS CHAR) AND v.status = 'posted')")->result_array();
            foreach ($r as $x) {
                $exp[] = ['date' => $x['date'], 'ref' => 'Expense #' . $x['id'], 'amount' => $x['amount'], 'detail' => $x['name'] . ' - no payment voucher', 'fix' => 'sync_expense', 'fix_id' => $x['id']];
            }
        }
        if (!empty($st['enable_income_sync']) && $this->db->table_exists('income')) {
            $r = $this->db->query("SELECT i.id, i.date, i.amount, i.name FROM income i
                WHERE i.is_active = 'yes' AND i.amount > 0 AND i.date BETWEEN $e_from AND $e_to
                AND NOT EXISTS (SELECT 1 FROM acc_vouchers v WHERE v.reference_module = 'ss_income' AND v.reference_id = CAST(i.id AS CHAR) AND v.status = 'posted')")->result_array();
            foreach ($r as $x) {
                $inc[] = ['date' => $x['date'], 'ref' => 'Income #' . $x['id'], 'amount' => $x['amount'], 'detail' => $x['name'] . ' - no receipt voucher', 'fix' => 'sync_income', 'fix_id' => $x['id']];
            }
        }
        if ($this->db->table_exists('staff_payslip')) {
            $r = $this->db->query("SELECT p.id, p.net_salary, p.month, p.year FROM staff_payslip p
                WHERE LOWER(p.status) = 'paid'
                AND NOT EXISTS (SELECT 1 FROM acc_vouchers v WHERE v.reference_module = 'payroll' AND v.reference_id = CAST(p.id AS CHAR) AND v.status = 'posted')")->result_array();
            foreach ($r as $x) {
                $pay[] = ['date' => '', 'ref' => 'Payslip #' . $x['id'], 'amount' => $x['net_salary'], 'detail' => $x['month'] . ' ' . $x['year'] . ' - paid but no voucher', 'fix' => 'sync_payroll', 'fix_id' => $x['id']];
            }
        }
        $checks['expense_missing'] = ['title' => 'Expenses without payment voucher', 'rows' => $exp];
        $checks['income_missing'] = ['title' => 'Other income without receipt voucher', 'rows' => $inc];
        $checks['payroll_missing'] = ['title' => 'Paid payslips without voucher', 'rows' => $pay];

        // ---------- Sync queue ----------
        $qrows = [];
        if ($this->db->table_exists('acc_sync_queue')) {
            foreach ($this->db->where_in('status', ['pending', 'failed'])->get('acc_sync_queue')->result_array() as $x) {
                $qrows[] = ['date' => substr($x['created_at'], 0, 10), 'ref' => $x['source_module'] . ':' . $x['source_id'], 'amount' => '',
                    'detail' => ucfirst($x['status']) . ' ' . $x['action'] . ' (attempts ' . $x['attempts'] . ')',
                    'fix' => 'retry_queue', 'fix_id' => $x['id']];
            }
        }
        $checks['queue'] = ['title' => 'Failed / pending sync queue', 'rows' => $qrows];

        // ---------- Unbalanced / empty vouchers ----------
        $unb = [];
        $r = $this->db->query("SELECT v.id, v.voucher_no, v.voucher_date, COUNT(vi.id) n,
                COALESCE(SUM(vi.debit_amount),0) dr, COALESCE(SUM(vi.credit_amount),0) cr
            FROM acc_vouchers v LEFT JOIN acc_voucher_items vi ON vi.voucher_id = v.id
            WHERE v.voucher_date BETWEEN $e_from AND $e_to AND v.status IN ('posted','reversed')
            GROUP BY v.id, v.voucher_no, v.voucher_date HAVING n = 0 OR ABS(dr - cr) > 0.01")->result_array();
        foreach ($r as $x) {
            $unb[] = ['date' => $x['voucher_date'], 'ref' => $x['voucher_no'], 'amount' => abs($x['dr'] - $x['cr']),
                'detail' => $x['n'] == 0 ? 'Voucher has no entries' : 'Debit ' . number_format($x['dr'], 2) . ' vs Credit ' . number_format($x['cr'], 2), 'fix' => '', 'fix_id' => ''];
        }
        $checks['unbalanced'] = ['title' => 'Vouchers where debit does not equal credit', 'rows' => $unb];

        // ---------- Duplicates / broken reversal pairs ----------
        $dup = [];
        $r = $this->db->query("SELECT reference_module, reference_id, COUNT(*) n, GROUP_CONCAT(voucher_no) nos, MIN(voucher_date) d
            FROM acc_vouchers WHERE status = 'posted' AND reference_module IS NOT NULL AND reference_module <> ''
            GROUP BY reference_module, reference_id HAVING n > 1 AND d BETWEEN $e_from AND $e_to")->result_array();
        foreach ($r as $x) {
            $dup[] = ['date' => $x['d'], 'ref' => $x['reference_module'] . ':' . $x['reference_id'], 'amount' => '',
                'detail' => $x['n'] . ' posted vouchers for one source: ' . $x['nos'], 'fix' => '', 'fix_id' => ''];
        }
        $r = $this->db->query("SELECT v.id, v.voucher_no, v.voucher_date FROM acc_vouchers v
            WHERE v.voucher_date BETWEEN $e_from AND $e_to AND (
              (v.status = 'reversed' AND (v.reversed_by_voucher_id IS NULL OR NOT EXISTS (SELECT 1 FROM acc_vouchers r WHERE r.id = v.reversed_by_voucher_id)))
              OR (v.reversal_of_voucher_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM acc_vouchers o WHERE o.id = v.reversal_of_voucher_id)))")->result_array();
        foreach ($r as $x) {
            $dup[] = ['date' => $x['voucher_date'], 'ref' => $x['voucher_no'], 'amount' => '', 'detail' => 'Reversal pair is incomplete (reversed with no reversing voucher, or reversal of a missing voucher)', 'fix' => '', 'fix_id' => ''];
        }
        $checks['duplicates'] = ['title' => 'Duplicate vouchers / broken reversal pairs', 'rows' => $dup];

        // ---------- Cash/bank vouchers excluded from the book totals ----------
        $other = $this->db->query("SELECT v.id, v.voucher_no, v.voucher_date, v.session_id, v.status,
                SUM(vi.debit_amount) dr, SUM(vi.credit_amount) cr
            FROM acc_vouchers v JOIN acc_voucher_items vi ON vi.voucher_id = v.id
            JOIN acc_ledgers l ON l.id = vi.ledger_id JOIN acc_ledger_groups g ON g.id = l.group_id
            WHERE g.system_name IN ('cash','bank') AND v.voucher_date BETWEEN $e_from AND $e_to
              AND (v.session_id <> $sess OR v.status NOT IN ('posted','reversed'))
            GROUP BY v.id, v.voucher_no, v.voucher_date, v.session_id, v.status")->result_array();
        $oth = [];
        foreach ($other as $x) {
            $oth[] = ['date' => $x['voucher_date'], 'ref' => $x['voucher_no'], 'amount' => max($x['dr'], $x['cr']),
                'detail' => ($x['status'] !== 'posted' && $x['status'] !== 'reversed')
                    ? 'Status is "' . $x['status'] . '", so it is left out of the books'
                    : 'Belongs to another session (#' . $x['session_id'] . '), so it is left out of this session\'s books', 'fix' => '', 'fix_id' => ''];
        }
        $checks['excluded'] = ['title' => 'Cash/bank vouchers left out of the book totals', 'rows' => $oth];

        // ---------- Opening + receipts - payments = closing, per cash/bank ledger ----------
        $balances = [];
        $ledgers = $this->db->query("SELECT l.id, l.name, l.opening_balance, l.opening_type, l.current_balance, g.system_name
            FROM acc_ledgers l JOIN acc_ledger_groups g ON g.id = l.group_id WHERE g.system_name IN ('cash','bank') ORDER BY g.system_name, l.name")->result_array();
        foreach ($ledgers as $l) {
            $ob = ($l['opening_type'] == 'Dr') ? $l['opening_balance'] : -$l['opening_balance'];
            $lid = (int)$l['id'];
            $sum = function ($cond) use ($lid, $sess) {
                return $this->db->query("SELECT COALESCE(SUM(vi.debit_amount),0) dr, COALESCE(SUM(vi.credit_amount),0) cr
                    FROM acc_voucher_items vi JOIN acc_vouchers v ON v.id = vi.voucher_id
                    WHERE vi.ledger_id = $lid AND v.session_id = $sess AND v.status IN ('posted','reversed') AND $cond")->row_array();
            };
            $before = $sum("v.voucher_date < $e_from");
            $range = $sum("v.voucher_date BETWEEN $e_from AND $e_to");
            $all = $sum("v.voucher_date <= $e_to");
            $opening = $ob + $before['dr'] - $before['cr'];
            $expected = $opening + $range['dr'] - $range['cr'];
            $closing = $ob + $all['dr'] - $all['cr'];
            $balances[] = ['type' => $l['system_name'], 'name' => $l['name'], 'opening' => $opening, 'receipts' => $range['dr'], 'payments' => $range['cr'],
                'expected' => $expected, 'closing' => $closing, 'diff' => round($expected - $closing, 2)];

            $days = $this->db->query("SELECT v.voucher_date d, SUM(vi.debit_amount) dr, SUM(vi.credit_amount) cr FROM acc_voucher_items vi
                JOIN acc_vouchers v ON v.id = vi.voucher_id WHERE vi.ledger_id = $lid AND v.session_id = $sess AND v.status IN ('posted','reversed')
                AND v.voucher_date BETWEEN $e_from AND $e_to GROUP BY v.voucher_date ORDER BY v.voucher_date")->result_array();
            $run = $opening;
            foreach ($days as $d) {
                $o = $run;
                $run = $o + $d['dr'] - $d['cr'];
                $daily[$l['system_name']][$l['name']][] = ['date' => $d['d'], 'opening' => $o, 'receipts' => $d['dr'], 'payments' => $d['cr'], 'closing' => $run];
                if ($run < -0.01) {
                    $neg[] = ['date' => $d['d'], 'ref' => $l['name'], 'amount' => abs($run),
                        'detail' => ucfirst($l['system_name']) . ' balance went negative (' . number_format($run, 2) . '); a receipt is probably missing or dated wrongly', 'fix' => '', 'fix_id' => ''];
                }
            }
        }
        $bal_rows = [];
        foreach ($balances as $b) {
            if (abs($b['diff']) > 0.01) {
                $bal_rows[] = ['date' => '', 'ref' => $b['name'], 'amount' => abs($b['diff']),
                    'detail' => 'Expected closing ' . number_format($b['expected'], 2) . ' vs actual ' . number_format($b['closing'], 2), 'fix' => '', 'fix_id' => ''];
            }
        }
        $checks['balance'] = ['title' => 'Opening + receipts - payments does not equal closing', 'rows' => $bal_rows];
        $checks['negative'] = ['title' => 'Cash/bank balance went negative on a day', 'rows' => $neg];

        return ['checks' => $checks, 'balances' => $balances, 'daily' => $daily];
    }

    /** Number of issues across all checks (used for the warning banner on the books). */
    public function getReconciliationIssueCount($from, $to)
    {
        $res = $this->getReconciliation($from, $to);
        $n = 0;
        foreach ($res['checks'] as $c) {
            $n += count($c['rows']);
        }
        return $n;
    }
}
