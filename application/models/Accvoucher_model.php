<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Accvoucher_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Generate next voucher number atomically using a sequence table.
     * Uses SELECT ... FOR UPDATE to prevent race conditions under concurrency.
     */
    public function generateVoucherNo($type)
    {
        $this->db->trans_start();

        // Lock the sequence row for this voucher type to prevent concurrent duplicates
        $this->db->query(
            "SELECT last_number FROM acc_voucher_sequences WHERE voucher_type = "
            . $this->db->escape($type) . " FOR UPDATE"
        );
        $seq = $this->db->where('voucher_type', $type)->get('acc_voucher_sequences')->row();

        if (!$seq) {
            $this->db->trans_complete();
            // Fallback: read prefix from settings
            $setting = $this->db->where('id', 1)->get('acc_settings')->row();
            $prefix = '';
            if ($type == 'payment') $prefix = $setting->payment_prefix ?? 'PAY-';
            elseif ($type == 'receipt') $prefix = $setting->receipt_prefix ?? 'REC-';
            elseif ($type == 'contra') $prefix = $setting->contra_prefix ?? 'CON-';
            elseif ($type == 'journal') $prefix = $setting->journal_prefix ?? 'JOU-';
            elseif ($type == 'general_receipt') $prefix = $setting->general_receipt_prefix ?? 'GRV-';
            elseif ($type == 'purchase') $prefix = 'PUR-';
            return $prefix . '0001';
        }

        $next_number = $seq->last_number + 1;
        $this->db->where('voucher_type', $type)
                 ->update('acc_voucher_sequences', ['last_number' => $next_number]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            log_message('error', 'Voucher sequence generation failed for type: ' . $type);
            return false;
        }

        return $seq->prefix . str_pad($next_number, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Peek at the next voucher number without incrementing the sequence.
     * Use this for displaying the next expected number on forms.
     */
    public function peekVoucherNo($type)
    {
        $seq = $this->db->where('voucher_type', $type)->get('acc_voucher_sequences')->row();

        if (!$seq) {
            $setting = $this->db->where('id', 1)->get('acc_settings')->row();
            $prefix = '';
            if ($type == 'payment') $prefix = $setting->payment_prefix ?? 'PAY-';
            elseif ($type == 'receipt') $prefix = $setting->receipt_prefix ?? 'REC-';
            elseif ($type == 'contra') $prefix = $setting->contra_prefix ?? 'CON-';
            elseif ($type == 'journal') $prefix = $setting->journal_prefix ?? 'JOU-';
            elseif ($type == 'general_receipt') $prefix = $setting->general_receipt_prefix ?? 'GRV-';
            elseif ($type == 'purchase') $prefix = 'PUR-';
            return $prefix . '0001';
        }

        $next_number = $seq->last_number + 1;
        return $seq->prefix . str_pad($next_number, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Validate that total debits equal total credits before saving.
     * Enforces the fundamental accounting equation at the model level.
     */
    private function validateBalance($items)
    {
        $total_debit = 0;
        $total_credit = 0;
        foreach ($items as $item) {
            $total_debit += floatval($item['debit_amount'] ?? 0);
            $total_credit += floatval($item['credit_amount'] ?? 0);
        }
        // Allow 1 paisa rounding tolerance
        return abs($total_debit - $total_credit) < 0.01;
    }

    /**
     * Add or update a voucher with full audit trail.
     * Auto-synced vouchers (with reference_module) are protected from manual edits.
     */
    public function addVoucher($data, $items)
    {
        // Validate debit/credit balance
        if (!$this->validateBalance($items)) {
            $dr = array_sum(array_column($items, 'debit_amount'));
            $cr = array_sum(array_column($items, 'credit_amount'));
            log_message('error', "Voucher balance mismatch: Dr={$dr} Cr={$cr}");
            return false;
        }

        $this->db->trans_start();
        $this->db->trans_strict(TRUE);

        $is_edit = (isset($data['id']) && $data['id'] > 0);
        $old_data_json = null;

        if ($is_edit) {
            $voucher_id = $data['id'];

            // Capture old data for audit log before modifying
            $old_voucher = $this->getVoucher($voucher_id);
            $old_data_json = json_encode($old_voucher);

            // Prevent editing auto-synced vouchers (fee, payroll, expense, income)
            $existing = $this->db->where('id', $voucher_id)->get('acc_vouchers')->row();
            if ($existing && !empty($existing->reference_module)) {
                $this->db->trans_rollback();
                log_message('error', 'Cannot edit auto-synced voucher ID: ' . $voucher_id);
                return false;
            }

            // Prevent editing reversed vouchers
            if ($existing && $existing->status === 'reversed') {
                $this->db->trans_rollback();
                log_message('error', 'Cannot edit reversed voucher ID: ' . $voucher_id);
                return false;
            }

            $this->db->where('id', $voucher_id);
            $this->db->update('acc_vouchers', $data);

            // Delete old items (triggers will reverse current_balance)
            $this->db->where('voucher_id', $voucher_id);
            $this->db->delete('acc_voucher_items');
        } else {
            // Ensure status is set for new vouchers
            if (!isset($data['status'])) {
                $data['status'] = 'posted';
            }
            $this->db->insert('acc_vouchers', $data);
            $voucher_id = $this->db->insert_id();
        }

        // Insert new items (triggers will update current_balance)
        if (!empty($items)) {
            foreach ($items as &$item) {
                $item['voucher_id'] = $voucher_id;
            }
            $this->db->insert_batch('acc_voucher_items', $items);
        }

        // Write audit log
        $staff_id = 0;
        if (method_exists($this, 'customlib') || isset($this->customlib)) {
            $staff_id = $this->customlib->getStaffID() ?? 0;
        }
        $audit = [
            'voucher_id' => $voucher_id,
            'action' => $is_edit ? 'edit' : 'create',
            'old_data' => $old_data_json,
            'new_data' => json_encode(['voucher' => $data, 'items' => $items]),
            'performed_by' => $staff_id,
            'ip_address' => $this->input->ip_address()
        ];
        $this->db->insert('acc_voucher_audit_log', $audit);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        }
        return $voucher_id;
    }

    public function getVoucher($id)
    {
        $this->db->select('acc_vouchers.*');
        $this->db->from('acc_vouchers');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $voucher = $query->row_array();

        if ($voucher) {
            $this->db->select('acc_voucher_items.*, acc_ledgers.name as ledger_name, acc_expense_types.name as expense_type_name');
            $this->db->from('acc_voucher_items');
            $this->db->join('acc_ledgers', 'acc_ledgers.id = acc_voucher_items.ledger_id', 'left');
            $this->db->join('acc_expense_types', 'acc_expense_types.id = acc_voucher_items.expense_type_id', 'left');
            $this->db->where('voucher_id', $voucher['id']);
            $items_query = $this->db->get();
            $voucher['items'] = $items_query->result_array();
        }
        return $voucher;
    }

    /**
     * Delete a voucher via Reversal Voucher pattern instead of hard-delete.
     * Creates a new voucher with swapped Dr/Cr entries and marks the original as 'reversed'.
     * Auto-synced vouchers cannot be deleted through this method.
     */
    public function deleteVoucher($id)
    {
        $voucher = $this->getVoucher($id);
        if (!$voucher) return false;

        // Prevent deleting auto-synced vouchers via UI
        if (!empty($voucher['reference_module'])) {
            return false;
        }

        // Prevent deleting already-reversed vouchers
        if ($voucher['status'] === 'reversed') {
            return false;
        }

        $this->db->trans_start();
        $this->db->trans_strict(TRUE);

        // Mark original as reversed
        $this->db->where('id', $id);
        $this->db->update('acc_vouchers', ['status' => 'reversed']);

        // Generate reversal voucher number
        $reversal_no = $voucher['voucher_no'] . '-REV';
        if (!$reversal_no) {
            $this->db->trans_rollback();
            return false;
        }

        // Create reversal voucher with opposite entries
        $staff_id = 0;
        if (isset($this->customlib)) {
            $staff_id = $this->customlib->getStaffID() ?? 0;
        }

        $reversal_data = [
            'voucher_no' => $reversal_no,
            'voucher_date' => date('Y-m-d'),
            'voucher_type' => $voucher['voucher_type'],
            'status' => 'posted',
            'narration' => 'REVERSAL of ' . $voucher['voucher_no'] . ': ' . ($voucher['narration'] ?? ''),
            'reversal_of_voucher_id' => $id,
            'session_id' => $this->current_session ?? ($voucher['session_id'] ?? 1),
            'created_by' => $staff_id,
            'payment_method' => $voucher['payment_method'] ?? null,
            'cheque_no' => $voucher['cheque_no'] ?? null,
            'cheque_date' => $voucher['cheque_date'] ?? null,
            'bank_name' => $voucher['bank_name'] ?? null,
            'upi_transaction_id' => $voucher['upi_transaction_id'] ?? null,
            'net_banking_ref' => $voucher['net_banking_ref'] ?? null
        ];
        $this->db->insert('acc_vouchers', $reversal_data);
        $reversal_id = $this->db->insert_id();

        // Update original to point to its reversal
        $this->db->where('id', $id);
        $this->db->update('acc_vouchers', ['reversed_by_voucher_id' => $reversal_id]);

        // Insert reversed items (swap debit/credit amounts)
        $reversed_items = [];
        if (!empty($voucher['items'])) {
            foreach ($voucher['items'] as $item) {
                $reversed_items[] = [
                    'voucher_id' => $reversal_id,
                    'ledger_id' => $item['ledger_id'],
                    'expense_type_id' => $item['expense_type_id'],
                    'debit_amount' => $item['credit_amount'],   // Swapped
                    'credit_amount' => $item['debit_amount'],   // Swapped
                    'narration' => 'Reversal entry'
                ];
            }
            if (!empty($reversed_items)) {
                $this->db->insert_batch('acc_voucher_items', $reversed_items);
            }
        }

        // Audit log
        $this->db->insert('acc_voucher_audit_log', [
            'voucher_id' => $id,
            'action' => 'reverse',
            'old_data' => json_encode($voucher),
            'new_data' => json_encode(['reversal_voucher_id' => $reversal_id, 'reversal_voucher_no' => $reversal_no]),
            'performed_by' => $staff_id,
            'ip_address' => $this->input->ip_address()
        ]);

        $this->db->trans_complete();
        $ok = $this->db->trans_status();

        // Non-critical: notify admins in the bell (only after a successful commit).
        if ($ok) {
            $this->notifyReversal($voucher, $reversal_no, $staff_id, 'manual');
        }

        return $ok;
    }

    /**
     * Reverse a voucher created by auto-sync (fee, payroll, expense, income).
     * This is called internally by the integration library, not by UI controllers.
     */
    public function reverseAutoSyncVoucher($id)
    {
        $voucher = $this->getVoucher($id);
        if (!$voucher || $voucher['status'] === 'reversed') return false;

        $this->db->trans_start();
        $this->db->trans_strict(TRUE);

        $this->db->where('id', $id);
        $this->db->update('acc_vouchers', ['status' => 'reversed']);

        $reversal_no = $voucher['voucher_no'] . '-REV';
        if (!$reversal_no) {
            $this->db->trans_rollback();
            return false;
        }

        $staff_id = 0;
        if (isset($this->customlib)) {
            $staff_id = $this->customlib->getStaffID() ?? 0;
        }

        $reversal_data = [
            'voucher_no' => $reversal_no,
            'voucher_date' => date('Y-m-d'),
            'voucher_type' => $voucher['voucher_type'],
            'status' => 'posted',
            'narration' => 'AUTO-REVERSAL of ' . $voucher['voucher_no'] . ': ' . ($voucher['narration'] ?? ''),
            'reference_module' => $voucher['reference_module'],
            'reference_id' => $voucher['reference_id'] . '_rev',
            'reversal_of_voucher_id' => $id,
            'session_id' => $this->current_session ?? ($voucher['session_id'] ?? 1),
            'created_by' => $staff_id,
            'payment_method' => $voucher['payment_method'] ?? null,
            'cheque_no' => $voucher['cheque_no'] ?? null,
            'cheque_date' => $voucher['cheque_date'] ?? null,
            'bank_name' => $voucher['bank_name'] ?? null,
            'upi_transaction_id' => $voucher['upi_transaction_id'] ?? null,
            'net_banking_ref' => $voucher['net_banking_ref'] ?? null
        ];
        $this->db->insert('acc_vouchers', $reversal_data);
        $reversal_id = $this->db->insert_id();

        $this->db->where('id', $id);
        $this->db->update('acc_vouchers', ['reversed_by_voucher_id' => $reversal_id]);

        if (!empty($voucher['items'])) {
            $reversed_items = [];
            foreach ($voucher['items'] as $item) {
                $reversed_items[] = [
                    'voucher_id' => $reversal_id,
                    'ledger_id' => $item['ledger_id'],
                    'expense_type_id' => $item['expense_type_id'],
                    'debit_amount' => $item['credit_amount'],
                    'credit_amount' => $item['debit_amount'],
                    'narration' => 'Auto-reversal entry'
                ];
            }
            $this->db->insert_batch('acc_voucher_items', $reversed_items);
        }

        $this->db->insert('acc_voucher_audit_log', [
            'voucher_id' => $id,
            'action' => 'reverse',
            'old_data' => json_encode($voucher),
            'new_data' => json_encode(['reversal_voucher_id' => $reversal_id]),
            'performed_by' => $staff_id,
            'ip_address' => $this->input->ip_address() ?? '0.0.0.0'
        ]);

        $this->db->trans_complete();
        $ok = $this->db->trans_status();

        // Non-critical: notify admins in the bell (only after a successful commit).
        if ($ok) {
            $this->notifyReversal($voucher, $reversal_no, $staff_id, 'auto');
        }

        return $ok;
    }

    /**
     * Send a bell notification to admins that a voucher was reversed.
     *
     * This is a NON-CRITICAL side effect: it is fully self-contained and swallows any
     * error, so it can NEVER affect or roll back the reversal that already committed.
     * Call it only AFTER the reversal transaction has succeeded.
     *
     * @param array  $voucher      The original voucher (already reversed).
     * @param string $reversal_no  The reversal voucher number (e.g. RV-00156-REV).
     * @param int    $staff_id     Staff id who performed/triggered the reversal (0 = system).
     * @param string $type         'auto' (triggered by fee delete/sync) or 'manual' (UI action).
     */
    private function notifyReversal($voucher, $reversal_no, $staff_id, $type = 'manual')
    {
        try {
            // Resolve "who" — staff name, or "System" when no staff context.
            $who = 'System';
            if (!empty($staff_id)) {
                $staff = $this->db->select('name, surname')
                                  ->where('id', $staff_id)
                                  ->get('staff')->row();
                if ($staff) {
                    $who = trim(($staff->name ?? '') . ' ' . ($staff->surname ?? ''));
                    if ($who === '') { $who = 'Staff #' . $staff_id; }
                }
            }

            $orig_no = $voucher['voucher_no'] ?? '(unknown)';
            $amount  = 0;
            if (!empty($voucher['items'])) {
                foreach ($voucher['items'] as $it) { $amount += (float)($it['debit_amount'] ?? 0); }
            }
            $amount_str = number_format($amount, 2);

            if ($type === 'auto') {
                $title = 'Voucher Auto-Reversed';
                $how   = 'automatically reversed because its linked fee record was deleted or edited';
                $url   = 'accounts/reports/false_reversals';
            } else {
                $title = 'Voucher Reversed';
                $how   = 'manually reversed';
                $url   = 'accounts/reports/daybook';
            }

            $message = "Voucher {$orig_no} (Amt: {$amount_str}) was {$how}. "
                     . "Reversal entry: {$reversal_no}. By: {$who}.";

            $this->load->model('SystemNotification_model');
            // Role 7 = Admin (same role used by other alerts in this app).
            $this->SystemNotification_model->notifyRole(7, $title, $message, $url);
        } catch (\Throwable $e) {
            // Intentionally ignored — notifications must never break a financial reversal.
            log_message('error', 'notifyReversal failed: ' . $e->getMessage());
        }
    }

    public function getDatatableVouchers($type)
    {
        $this->datatables
            ->select('acc_vouchers.id, acc_vouchers.voucher_no, acc_vouchers.voucher_date, acc_vouchers.narration, acc_vouchers.status, acc_vouchers.attachment, acc_vouchers.reference_module, acc_vouchers.payment_method, acc_vouchers.cheque_no, acc_vouchers.upi_transaction_id, acc_vouchers.net_banking_ref, acc_vouchers.rejected_reason, acc_vouchers.bank_name, SUM(acc_voucher_items.debit_amount) as total_amount')
            ->from('acc_vouchers')
            ->join('acc_voucher_items', 'acc_voucher_items.voucher_id = acc_vouchers.id', 'left')
            ->where('acc_vouchers.voucher_type', $type)
            ->where('acc_vouchers.session_id', $this->current_session)
            ->group_by('acc_vouchers.id')
            ->searchable('acc_vouchers.voucher_no, acc_vouchers.voucher_date')
            ->orderable('acc_vouchers.voucher_no, acc_vouchers.voucher_date, total_amount')
            ->sort('acc_vouchers.id', 'desc');

        return $this->datatables->generate('json');
    }

    /**
     * Get audit trail for a specific voucher.
     */
    public function getVoucherAuditLog($voucher_id)
    {
        $this->db->select('acc_voucher_audit_log.*, staff.name as staff_name, staff.surname as staff_surname');
        $this->db->from('acc_voucher_audit_log');
        $this->db->join('staff', 'staff.id = acc_voucher_audit_log.performed_by', 'left');
        $this->db->where('voucher_id', $voucher_id);
        $this->db->order_by('performed_at', 'desc');
        return $this->db->get()->result_array();
    }

    /**
     * Get pending items from the sync failure queue.
     */
    public function getPendingSyncItems($limit = 50)
    {
        $this->db->where('status', 'pending');
        $this->db->where('attempts <', 5);
        $this->db->order_by('created_at', 'asc');
        $this->db->limit($limit);
        return $this->db->get('acc_sync_queue')->result_array();
    }

    /**
     * READ-ONLY diagnostic: list AUTO-REVERSAL vouchers that were most likely false
     * positives — i.e. the original fee voucher was auto-reversed even though its source
     * fee sub-invoice still exists in student_fees_deposite.amount_detail.
     *
     * This is strictly SELECT-only. It performs NO writes and does not restore anything;
     * it is a review aid so an admin can decide what (if anything) to restore later.
     * Detection mirrors Accounts_integration::detectOrphanedVouchers() but inverted:
     * a still-existing sub-invoice means the reversal should never have happened.
     *
     * @return array Rows describing each false-positive reversal candidate.
     */
    public function findFalsePositiveReversals()
    {
        $results = [];

        // The auto-generated mirror vouchers: narration starts with "AUTO-REVERSAL of",
        // reference_id ends with "_rev", and they point back to the original via
        // reversal_of_voucher_id.
        $this->db->from('acc_vouchers');
        $this->db->like('reference_id', '_rev', 'before'); // reference_id LIKE '%_rev'
        $this->db->where_in('reference_module', ['fee_collection', 'fee_discount']);
        $this->db->like('narration', 'AUTO-REVERSAL of', 'after'); // narration LIKE 'AUTO-REVERSAL of%'
        $mirrors = $this->db->get()->result_array();

        foreach ($mirrors as $mirror) {
            // Resolve the original voucher via the FK (robust, no string parsing).
            $original = null;
            if (!empty($mirror['reversal_of_voucher_id'])) {
                $original = $this->db->where('id', $mirror['reversal_of_voucher_id'])
                                     ->get('acc_vouchers')->row_array();
            }
            if (empty($original)) {
                // Fallback: strip a single trailing "_rev" from the mirror reference_id.
                $orig_ref = preg_replace('/_rev$/', '', $mirror['reference_id']);
                $original = $this->db->where('reference_id', $orig_ref)
                                     ->where('reference_module', $mirror['reference_module'])
                                     ->where('status', 'reversed')
                                     ->get('acc_vouchers')->row_array();
            }
            if (empty($original)) {
                continue; // Cannot resolve original; skip (report only what we can verify).
            }

            // Existence check on the ORIGINAL reference_id, same rule as the orphan sweep.
            // Base ref = deposit_sub (drop a trailing "_disc" for discount journals).
            $base_ref = preg_replace('/_disc$/', '', (string)$original['reference_id']);
            $parts = explode('_', $base_ref);
            $deposit_id = $parts[0] ?? 0;
            $sub_invoice_id = $parts[1] ?? 0;

            $sub_invoice_exists = false;
            $student_name = '';
            if ($deposit_id) {
                $deposit = $this->db->where('id', $deposit_id)
                                    ->get('student_fees_deposite')->row();
                if ($deposit) {
                    $amount_detail = json_decode($deposit->amount_detail, true);
                    if (is_array($amount_detail) && isset($amount_detail[$sub_invoice_id])) {
                        $sub_invoice_exists = true;
                    }
                }
            }

            // False positive = the source sub-invoice still exists, yet it was reversed.
            if ($sub_invoice_exists) {
                $amount = $this->db->select('SUM(debit_amount) as amt')
                                   ->where('voucher_id', $original['id'])
                                   ->get('acc_voucher_items')->row();
                $results[] = [
                    'original_voucher_id'   => $original['id'],
                    'original_voucher_no'   => $original['voucher_no'],
                    'original_voucher_date' => $original['voucher_date'],
                    'original_status'       => $original['status'],
                    'reversal_voucher_id'   => $mirror['id'],
                    'reversal_voucher_no'   => $mirror['voucher_no'],
                    'reversal_voucher_date' => $mirror['voucher_date'],
                    'reference_module'      => $original['reference_module'],
                    'reference_id'          => $original['reference_id'],
                    'deposit_id'            => $deposit_id,
                    'sub_invoice_id'        => $sub_invoice_id,
                    'amount'                => $amount ? (float)$amount->amt : 0,
                    'narration'             => $original['narration'],
                ];
            }
        }

        return $results;
    }
}
