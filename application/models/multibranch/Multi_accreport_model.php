<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Multi_accreport_model extends CI_Model
{
    protected $db_default;

    public function __construct()
    {
        parent::__construct();
        $this->db_default = $this->load->database('default', true);
        $this->load->model('setting_model');
    }

    private function getConsolidatedTrialBalanceQuery($end_date = null)
    {
        $default_db = $this->db_default->database;
        $this->load->model("multibranch_model");

        // 1. Get Main Session Name & Currency Base Price
        $current_session_id = $this->setting_model->getCurrentSession();
        $main_session = $this->db_default->select('session')->from('sessions')->where('id', $current_session_id)->get()->row_array();
        $session_name = $main_session['session'];

        $main_setting = $this->db_default->select('currencies.base_price')
            ->from('sch_settings')
            ->join('currencies', 'currencies.id = sch_settings.currency', 'left')
            ->get()->row_array();
        $main_base_price = isset($main_setting['base_price']) && is_numeric($main_setting['base_price']) && $main_setting['base_price'] > 0 ? $main_setting['base_price'] : 1;

        $branches = $this->multibranch_model->get();
        $condition = array();

        // Query the main DB as well (it's in the branches list if it is added as a branch, but usually the home branch is handled explicitly or it's in the branches list).
        // Wait, `multibranch_model->get()` returns all branches except the home branch?
        // Ah, `multibranch_model->get()` returns the added branches. We need to include the home branch (default_db).
        
        $all_dbs = [];
        // Add Main DB
        $all_dbs[] = [
            'db_name' => $default_db,
            'db_conn' => $this->db_default
        ];

        // Add sub-branches
        if (!empty($branches)) {
            foreach ($branches as $branch) {
                $db_conn = $this->load->database('branch_' . $branch->id, true);
                $all_dbs[] = [
                    'db_name' => $db_conn->database,
                    'db_conn' => $db_conn
                ];
            }
        }

        $date_cond = $end_date ? " AND v.voucher_date <= " . $this->db_default->escape($end_date) : "";

        foreach ($all_dbs as $db_info) {
            $db_dynamic = $db_info['db_name'];
            $db_conn = $db_info['db_conn'];

            // Find session_id for this branch matching the session name
            $b_session = $db_conn->select('id')->from('sessions')->where('session', $session_name)->get()->row_array();
            if (!$b_session) continue; // Skip if session doesn't exist in this branch
            $b_session_id = $b_session['id'];

            // Find base_price for this branch
            $b_setting = $db_conn->select('currencies.base_price')
                ->from('sch_settings')
                ->join('currencies', 'currencies.id = sch_settings.currency', 'left')
                ->get()->row_array();
            
            $b_base_price = isset($b_setting['base_price']) && is_numeric($b_setting['base_price']) && $b_setting['base_price'] > 0 ? $b_setting['base_price'] : 1;
            
            // Fix: To convert branch amount to main branch currency
            $ratio = $main_base_price / $b_base_price;

            $condition[] = "
                SELECT 
                    l.name as ledger_name, 
                    (l.opening_balance * $ratio) as opening_balance, 
                    l.opening_type, 
                    g.name as group_name, 
                    g.system_name,
                    SUM(CASE WHEN v.session_id = $b_session_id $date_cond THEN (vi.debit_amount * $ratio) ELSE 0 END) as total_debit,
                    SUM(CASE WHEN v.session_id = $b_session_id $date_cond THEN (vi.credit_amount * $ratio) ELSE 0 END) as total_credit
                FROM `$db_dynamic`.`acc_ledgers` l
                JOIN `$db_dynamic`.`acc_ledger_groups` g ON g.id = l.group_id
                LEFT JOIN `$db_dynamic`.`acc_voucher_items` vi ON vi.ledger_id = l.id
                LEFT JOIN `$db_dynamic`.`acc_vouchers` v ON v.id = vi.voucher_id AND v.status IN ('posted', 'reversed')
                GROUP BY l.id
            ";
        }

        if (empty($condition)) {
            return [];
        }

        $sql = implode(" UNION ALL ", $condition);

        // Now aggregate the UNION ALL by Group Name and Ledger Name
        $final_sql = "
            SELECT 
                ledger_name as name, 
                group_name, 
                system_name,
                SUM(CASE WHEN opening_type = 'Dr' THEN opening_balance ELSE -opening_balance END) as net_opening_balance,
                SUM(total_debit) as total_debit, 
                SUM(total_credit) as total_credit
            FROM ($sql) as tempTable
            GROUP BY group_name, ledger_name, system_name
            ORDER BY group_name ASC, ledger_name ASC
        ";

        return $this->db_default->query($final_sql)->result_array();
    }

    public function getConsolidatedTrialBalance($to_date = null)
    {
        $ledgers = $this->getConsolidatedTrialBalanceQuery($to_date);
        $trial_balance = array();
        
        foreach ($ledgers as $l) {
            $bal = $l['net_opening_balance']; // Positive = Dr, Negative = Cr
            $bal += ($l['total_debit'] - $l['total_credit']);
            
            if (abs($bal) > 0.001) {
                $l['closing_balance'] = $bal; 
                $trial_balance[] = $l;
            }
        }
        return $trial_balance;
    }

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

    public function getConsolidatedProfitLoss($from_date, $to_date)
    {
        // Currently PL covers up to to_date. For exact period PL, it might require closing stock adjustments, 
        // but for standard tracking we use trial balance up to date.
        $trial = $this->getConsolidatedTrialBalance($to_date);
        return $this->calculateProfitLossFromTrial($trial);
    }

    public function getConsolidatedBalanceSheet($to_date)
    {
        $trial = $this->getConsolidatedTrialBalance($to_date);
        
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

        // Add Net Profit to Liabilities (Capital) side
        $pl = $this->calculateProfitLossFromTrial($trial);
        if ($pl['net_profit'] != 0) {
            $data['liabilities'][] = ['name' => 'Net Profit (Current Year)', 'group' => 'P&L Account', 'amount' => $pl['net_profit']];
            $data['total_liabilities'] += $pl['net_profit'];
        }

        return $data;
    }
}
