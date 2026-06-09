<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Repair extends MY_Addon_AccountsController
{
    public function __construct()
    {
        parent::__construct();
        // Restrict to super admin only
        if (!$this->rbac->hasPrivilege('acc_settings', 'can_edit')) {
            access_denied();
        }
    }

    public function index()
    {
        echo "<h1>Accounts Data Repair Tool</h1>";
        echo "<ul>";
        echo "<li><a href='".site_url('accounts/repair/fix_truncated_references')."'>1. Fix Truncated Fee Reference IDs</a> (Run this only once after upgrading)</li>";
        echo "<li><a href='".site_url('accounts/repair/rebuild_balances')."'>2. Rebuild Materialized Ledgers Balances</a> (Run this if Trial Balance doesn't match ledger balances)</li>";
        echo "</ul>";
    }

    public function fix_truncated_references()
    {
        echo "<h2>Fixing Truncated Reference IDs...</h2>";
        
        // Find all fee_collection vouchers where reference_id doesn't have an underscore
        $vouchers = $this->db->where('reference_module', 'fee_collection')
                             ->not_like('reference_id', '_')
                             ->get('acc_vouchers')->result();
        
        $fixed_count = 0;
        
        foreach ($vouchers as $v) {
            $deposit_id = (int)$v->reference_id;
            
            // Look up the original fee deposit
            $deposit = $this->db->where('id', $deposit_id)->get('student_fees_deposite')->row();
            
            if ($deposit) {
                // Determine the correct sub-invoice number based on matching amounts or just assume '_1' if simple
                $amount_detail = json_decode($deposit->amount_detail, true);
                if (is_array($amount_detail)) {
                    // We look at the voucher items to find the matching amount
                    $v_items = $this->db->where('voucher_id', $v->id)->where('debit_amount >', 0)->get('acc_voucher_items')->result();
                    $v_amount = 0;
                    if (!empty($v_items)) {
                        $v_amount = floatval($v_items[0]->debit_amount);
                    }
                    
                    $matched_inv_no = null;
                    foreach ($amount_detail as $inv_no => $detail) {
                        $total_collected = floatval($detail['amount']) + floatval($detail['amount_fine']);
                        // Rounding to 2 decimals for comparison
                        if (abs($total_collected - $v_amount) < 0.01) {
                            $matched_inv_no = $inv_no;
                            break;
                        }
                    }
                    
                    if ($matched_inv_no === null) {
                        // Fallback: just use the first key
                        $keys = array_keys($amount_detail);
                        $matched_inv_no = $keys[0] ?? 1;
                    }
                    
                    $new_ref_id = $deposit_id . '_' . $matched_inv_no;
                    
                    // Update voucher
                    $this->db->where('id', $v->id)->update('acc_vouchers', ['reference_id' => $new_ref_id]);
                    echo "Fixed Voucher ID {$v->id}: {$v->reference_id} -> {$new_ref_id}<br>";
                    $fixed_count++;
                }
            }
        }
        
        echo "<br><b>Done! Fixed {$fixed_count} truncated reference IDs.</b>";
    }

    public function rebuild_balances()
    {
        echo "<h2>Rebuilding Materialized Ledger Balances...</h2>";
        
        $ledgers = $this->db->get('acc_ledgers')->result();
        $rebuilt = 0;
        
        foreach ($ledgers as $l) {
            $this->db->select('SUM(debit_amount) as total_dr, SUM(credit_amount) as total_cr');
            $this->db->from('acc_voucher_items vi');
            $this->db->join('acc_vouchers v', 'v.id = vi.voucher_id');
            $this->db->where('vi.ledger_id', $l->id);
            $this->db->where('v.status', 'posted');
            $totals = $this->db->get()->row();
            
            $dr = floatval($totals->total_dr ?? 0);
            $cr = floatval($totals->total_cr ?? 0);
            $current_balance = $dr - $cr;
            
            $this->db->where('id', $l->id)->update('acc_ledgers', ['current_balance' => $current_balance]);
            $rebuilt++;
        }
        
        echo "<br><b>Done! Rebuilt balances for {$rebuilt} ledgers.</b>";
    }
}
