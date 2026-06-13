<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Accounts Integration Library
 * 
 * Synchronizes Smart School ERP native financial events with the double-entry
 * accounting addon. All sync methods are atomic (wrapped in DB transactions),
 * idempotent (safe to call multiple times), and fault-tolerant (failures are
 * queued for retry).
 * 
 * Handles: Fee Collection, Payroll, Native Expenses, Native Income,
 *          Fee Discounts (JV), Payment Gateway Clearing
 */
class Accounts_integration
{
    private $CI;
    private $settings_cache = null;

    public function __construct()
    {
        $this->CI = &get_instance();
    }

    // =========================================================================
    // CORE HELPERS
    // =========================================================================

    /**
     * Get addon settings with per-request cache to avoid repeated queries.
     */
    private function getSettings()
    {
        if ($this->settings_cache !== null) {
            return $this->settings_cache;
        }
        if (!$this->CI->db->table_exists('acc_settings')) {
            return false;
        }
        $this->settings_cache = $this->CI->db->get('acc_settings')->row_array();
        return $this->settings_cache;
    }

    /**
     * Atomic voucher number generation using sequence table with row-level lock.
     * MUST be called within an active transaction.
     */
    private function generateVoucherNo($type)
    {
        // FOR UPDATE locks the row to prevent concurrent duplicate numbers
        $this->CI->db->query(
            "SELECT last_number, prefix FROM acc_voucher_sequences WHERE voucher_type = "
            . $this->CI->db->escape($type) . " FOR UPDATE"
        );
        $seq = $this->CI->db->where('voucher_type', $type)->get('acc_voucher_sequences')->row();

        if (!$seq) {
            log_message('error', 'Voucher sequence not found for type: ' . $type);
            return false;
        }

        $next = $seq->last_number + 1;
        $this->CI->db->where('voucher_type', $type)
                     ->update('acc_voucher_sequences', ['last_number' => $next]);

        return $seq->prefix . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Queue a failed sync operation for later retry.
     * Uses REPLACE to handle duplicate source entries gracefully.
     */
    private function queueFailedSync($module, $source_id, $action = 'create', $error = '')
    {
        // Use a separate connection to ensure queue write succeeds even if main txn rolled back
        $this->CI->db->replace('acc_sync_queue', [
            'source_module' => $module,
            'source_id' => (string)$source_id,
            'action' => $action,
            'status' => 'pending',
            'error_message' => substr($error, 0, 1000),
            'attempts' => 0
        ]);
        log_message('error', "Accounts sync QUEUED for {$module}:{$source_id} - {$error}");
    }

    private function getSessionId()
    {
        // First try to get it from the settings model directly (safest and most accurate in Admin panel)
        if (isset($this->CI->setting_model) && method_exists($this->CI->setting_model, 'getCurrentSession')) {
            $sess = $this->CI->setting_model->getCurrentSession();
            if ($sess) return (int)$sess;
        }
        
        // Then try admin session data
        $admin = $this->CI->session->userdata('admin');
        if (isset($admin['current_session']) && $admin['current_session']) {
            return (int)$admin['current_session'];
        }
        
        // Stateless fallback — works with both 'active' and 'is_active' column names
        $row = null;
        if ($this->CI->db->field_exists('is_active', 'sessions')) {
            $row = $this->CI->db->select('id')->where('is_active', 'yes')->or_where('is_active', '1')->get('sessions')->row();
        } elseif ($this->CI->db->field_exists('active', 'sessions')) {
            $row = $this->CI->db->select('id')->where('active', 'yes')->or_where('active', '1')->get('sessions')->row();
        }
        
        // Final fallback to the highest session ID
        if (!$row) {
            $row = $this->CI->db->select('id')->order_by('id', 'desc')->get('sessions')->row();
        }
        
        return $row ? (int)$row->id : 1;
    }

    private function getStaffId()
    {
        if (isset($this->CI->customlib) && method_exists($this->CI->customlib, 'getStaffID')) {
            return $this->CI->customlib->getStaffID() ?? 1;
        }
        return 1;
    }

    /**
     * Check if a voucher already exists for a given reference (idempotency guard).
     */
    private function voucherExists($module, $ref_id)
    {
        return $this->CI->db->where('reference_module', $module)
                            ->where('reference_id', (string)$ref_id)
                            ->where('status', 'posted')
                            ->get('acc_vouchers')->row();
    }

    /**
     * Determine which debit ledger to use based on payment mode.
     * - Online gateway payments → Gateway Clearing Account
     * - Cash → Cash Account (fee_receipt_ledger_id)
     * - UPI/Cheque/DD/Card/Bank Transfer → Bank Account (fee_bank_receipt_ledger_id)
     * Falls back to fee_receipt_ledger_id if bank ledger is not configured.
     */
    private function resolveDebitLedger($payment_mode, $settings)
    {
        $pm = strtolower($payment_mode ?? '');

        // Online payment gateways → Gateway Clearing Account
        $online_modes = ['online', 'razorpay', 'stripe', 'paytm', 'paypal', 'ccavenue', 'instamojo', 'paystack', 'flutterwave'];
        if (!empty($settings['gateway_clearing_ledger_id']) && in_array($pm, $online_modes)) {
            return $settings['gateway_clearing_ledger_id'];
        }

        // Non-cash modes → Bank Account (if configured)
        $bank_modes = ['upi', 'cheque', 'dd', 'card', 'bank_transfer', 'bank transfer', 'net banking', 'net_banking', 'neft', 'rtgs', 'imps'];
        if (!empty($settings['fee_bank_receipt_ledger_id']) && in_array($pm, $bank_modes)) {
            return $settings['fee_bank_receipt_ledger_id'];
        }

        // Cash and fallback → Cash Account
        return $settings['fee_receipt_ledger_id'];
    }

    // =========================================================================
    // FEE COLLECTION SYNC (P1 atomicity, P4 discounts, P5 gateway clearing)
    // =========================================================================

    /**
     * Sync a fee deposit to the double-entry books.
     * 
     * Creates a Receipt Voucher (Dr: Cash/Bank/Gateway | Cr: Fee Income).
     * If discounts exist, also creates a Journal Voucher (Dr: Discount Expense | Cr: Fee Income).
     * 
     * @param int $fee_deposit_id The student_fees_deposite.id
     * @return bool
     */
    public function sync_fee($fee_deposit_id)
    {
        $settings = $this->getSettings();
        if (!$settings || $settings['enable_fee_sync'] != 1
            || empty($settings['fee_receipt_ledger_id'])
            || empty($settings['fee_income_ledger_id'])) {
            return false;
        }

        // Fetch fee deposit with student details, handling normal, transport, and yearly transport fees
        $this->CI->db->select('student_fees_deposite.*, COALESCE(student_fees_master.student_session_id, student_transport_fees.student_session_id, student_transport_yearly_fees.student_session_id) as fee_session_id');
        $this->CI->db->join('student_fees_master', 'student_fees_master.id = student_fees_deposite.student_fees_master_id', 'left');
        $this->CI->db->join('student_transport_fees', 'student_transport_fees.id = student_fees_deposite.student_transport_fee_id', 'left');
        if ($this->CI->db->field_exists('student_transport_yearly_fee_id', 'student_fees_deposite')) {
            $this->CI->db->join('student_transport_yearly_fees', 'student_transport_yearly_fees.id = student_fees_deposite.student_transport_yearly_fee_id', 'left');
        } else {
            $this->CI->db->join('student_transport_fees as student_transport_yearly_fees', '1=0', 'left'); // Dummy join if column doesn't exist
        }
        $this->CI->db->where('student_fees_deposite.id', $fee_deposit_id);
        $fee_deposit = $this->CI->db->get('student_fees_deposite')->row_array();

        if (empty($fee_deposit)) {
            return true; // Source deleted, clear from queue
        }

        if (!empty($fee_deposit['fee_session_id'])) {
            $this->CI->db->select('students.firstname, students.lastname, students.admission_no, student_session.session_id as academic_session_id');
            $this->CI->db->join('students', 'students.id = student_session.student_id', 'left');
            $this->CI->db->where('student_session.id', $fee_deposit['fee_session_id']);
            $student = $this->CI->db->get('student_session')->row_array();
            if ($student) {
                $fee_deposit = array_merge($fee_deposit, $student);
            }
        }

        $amount_detail = json_decode($fee_deposit['amount_detail'], true);
        if (empty($amount_detail)) {
            return false;
        }

        $student_name = trim(($fee_deposit['firstname'] ?? '') . ' ' . ($fee_deposit['lastname'] ?? ''))
                      . ' (' . ($fee_deposit['admission_no'] ?? '') . ')';

        // Process each sub-invoice in the deposit
        foreach ($amount_detail as $inv_no => $detail) {
            $ref_id = $fee_deposit_id . '_' . $inv_no;

            // Idempotency: skip if voucher already exists
            if ($this->voucherExists('fee_collection', $ref_id)) {
                continue;
            }

            $amount = floatval($detail['amount'] ?? 0);
            $fine = floatval($detail['amount_fine'] ?? 0);
            $discount = floatval($detail['amount_discount'] ?? 0);
            $total_collected = $amount + $fine;
            $payment_mode = $detail['payment_mode'] ?? 'Cash';
            $sync_session_id = !empty($fee_deposit['academic_session_id']) ? (int)$fee_deposit['academic_session_id'] : $this->getSessionId();

            if ($total_collected <= 0 && $discount <= 0) continue;

            $date = date('Y-m-d', strtotime($detail['date'] ?? 'now'));

            // === START ATOMIC TRANSACTION ===
            $this->CI->db->trans_start();
            $this->CI->db->trans_strict(TRUE);

            // --- Receipt Voucher for collected amount ---
            if ($total_collected > 0) {
                $voucher_no = $this->generateVoucherNo('receipt');
                if (!$voucher_no) {
                    $this->CI->db->trans_rollback();
                    $this->queueFailedSync('fee_collection', $ref_id, 'create', 'Voucher number generation failed');
                    continue;
                }

                // Determine debit ledger (Cash/Bank vs Gateway Clearing)
                $debit_ledger_id = $this->resolveDebitLedger($payment_mode, $settings);
                
                $fee_type_str = "Fee";
                $expense_type_name_search = "Tuition"; // default category mapping
                if (!empty($fee_deposit['student_transport_fee_id'])) {
                    $fee_type_str = "Transport Fee";
                    $expense_type_name_search = "Transport";
                } elseif (!empty($fee_deposit['student_transport_yearly_fee_id'])) {
                    $fee_type_str = "Yearly Transport Fee";
                    $expense_type_name_search = "Transport";
                }
                
                // Fetch dynamic expense_type_id for Category/Head
                $expense_type_id = null;
                if ($this->CI->db->table_exists('acc_expense_types')) {
                    $this->CI->db->like('name', $expense_type_name_search, 'both');
                    $this->CI->db->where('type', 'income');
                    $exp_type = $this->CI->db->get('acc_expense_types')->row();
                    if ($exp_type) {
                        $expense_type_id = $exp_type->id;
                    }
                }

                
                $narration = "{$fee_type_str} collected from {$student_name}. Inv: {$fee_deposit_id}/{$inv_no}";
                if ($debit_ledger_id == $settings['gateway_clearing_ledger_id']) {
                    $narration .= " [Online: {$payment_mode} — Pending Settlement]";
                }

                $voucher_data = [
                    'voucher_no'       => $voucher_no,
                    'voucher_date'     => $date,
                    'voucher_type'     => 'receipt',
                    'status'           => 'posted',
                    'narration'        => $narration,
                    'reference_module' => 'fee_collection',
                    'reference_id'     => $ref_id,
                    'payment_method'   => $payment_mode,
                    'session_id'       => $sync_session_id,
                    'created_by'       => $this->getStaffId(),
                ];

                $desc = trim($detail['description'] ?? '');
                $ref_no = trim($detail['reference_no'] ?? '');
                $c_date = trim($detail['cheque_date'] ?? '');
                $pm_lower = strtolower($payment_mode);
                $is_bank_mode = in_array($pm_lower, ['cheque', 'dd', 'upi', 'bank_transfer', 'bank transfer', 'net banking', 'net_banking']);

                if ($is_bank_mode) {
                    if (!empty($c_date)) {
                        $voucher_data['cheque_date'] = $c_date;
                    }
                    
                    if (empty($ref_no) && !empty($desc)) {
                        $ref_no = $desc;
                        $desc = '';
                    }
                    if (!empty($ref_no)) {
                        if ($pm_lower == 'cheque' || $pm_lower == 'dd') {
                            $voucher_data['cheque_no'] = substr($ref_no, 0, 50);
                        } elseif ($pm_lower == 'upi') {
                            $voucher_data['upi_transaction_id'] = substr($ref_no, 0, 100);
                        } else {
                            $voucher_data['net_banking_ref'] = substr($ref_no, 0, 100);
                        }
                    }
                }
                
                if (!empty($desc)) {
                    $voucher_data['narration'] .= " | Note: " . $desc;
                }

                $this->CI->db->insert('acc_vouchers', $voucher_data);
                $voucher_id = $this->CI->db->insert_id();

                if (!$voucher_id) {
                    $this->CI->db->trans_rollback();
                    $this->queueFailedSync('fee_collection', $ref_id, 'create', 'Voucher insert returned no ID');
                    continue;
                }

                $items = [
                    [
                        'voucher_id'    => $voucher_id,
                        'ledger_id'     => $debit_ledger_id,
                        'expense_type_id' => null,
                        'debit_amount'  => $total_collected,
                        'credit_amount' => 0,
                        'narration'     => ''
                    ],
                    [
                        'voucher_id'    => $voucher_id,
                        'ledger_id'     => $settings['fee_income_ledger_id'],
                        'expense_type_id' => $expense_type_id,
                        'debit_amount'  => 0,
                        'credit_amount' => $total_collected,
                        'narration'     => ''
                    ]
                ];
                $this->CI->db->insert_batch('acc_voucher_items', $items);
            }

            // --- Journal Voucher for discount (P4 fix) ---
            if ($discount > 0 && !empty($settings['fee_discount_expense_ledger_id'])) {
                $disc_ref_id = $ref_id . '_disc';
                if (!$this->voucherExists('fee_discount', $disc_ref_id)) {
                    $jv_no = $this->generateVoucherNo('journal');
                    if ($jv_no) {
                        $jv_data = [
                            'voucher_no'       => $jv_no,
                            'voucher_date'     => $date,
                            'voucher_type'     => 'journal',
                            'status'           => 'posted',
                            'narration'        => "Fee discount/concession for {$student_name}. Inv: {$fee_deposit_id}/{$inv_no}. Discount: {$discount}",
                            'reference_module' => 'fee_discount',
                            'reference_id'     => $disc_ref_id,
                            'session_id'       => $sync_session_id,
                            'created_by'       => $this->getStaffId(),
                        ];
                        $this->CI->db->insert('acc_vouchers', $jv_data);
                        $jv_id = $this->CI->db->insert_id();

                        if ($jv_id) {
                            $jv_items = [
                                [
                                    'voucher_id'    => $jv_id,
                                    'ledger_id'     => $settings['fee_discount_expense_ledger_id'],
                                    'debit_amount'  => $discount,
                                    'credit_amount' => 0,
                                    'narration'     => 'Fee concession expense'
                                ],
                                [
                                    'voucher_id'    => $jv_id,
                                    'ledger_id'     => $settings['fee_income_ledger_id'],
                                    'debit_amount'  => 0,
                                    'credit_amount' => $discount,
                                    'narration'     => 'Discount adjustment against fees'
                                ]
                            ];
                            $this->CI->db->insert_batch('acc_voucher_items', $jv_items);
                        }
                    }
                }
            }

            $this->CI->db->trans_complete();
            if ($this->CI->db->trans_status() === FALSE) {
                $this->queueFailedSync('fee_collection', $ref_id, 'create', 'Transaction commit failed');
            }
        }

        return true;
    }

    // =========================================================================
    // PAYROLL SYNC (P1 atomicity, P6 reversal support)
    // =========================================================================

    /**
     * Sync a paid payslip to the double-entry books.
     * Creates a Payment Voucher (Dr: Salary Expense | Cr: Cash/Bank).
     */
    public function sync_payroll($staff_payslip_id)
    {
        $settings = $this->getSettings();
        if (!$settings || $settings['enable_payroll_sync'] != 1
            || empty($settings['payroll_payment_ledger_id'])
            || empty($settings['payroll_expense_ledger_id'])) {
            return false;
        }

        $this->CI->db->select('staff_payslip.*, staff.name, staff.surname, staff.employee_id');
        $this->CI->db->join('staff', 'staff.id = staff_payslip.staff_id', 'left');
        $this->CI->db->where('staff_payslip.id', $staff_payslip_id);
        $payslip = $this->CI->db->get('staff_payslip')->row_array();

        if (empty($payslip) || $payslip['status'] != 'paid') {
            return false;
        }

        // Idempotency
        if ($this->voucherExists('payroll', (string)$staff_payslip_id)) {
            return true;
        }

        $net_salary = floatval($payslip['net_salary']);
        if ($net_salary <= 0) return false;

        $this->CI->db->trans_start();
        $this->CI->db->trans_strict(TRUE);

        $voucher_no = $this->generateVoucherNo('payment');
        if (!$voucher_no) {
            $this->CI->db->trans_rollback();
            $this->queueFailedSync('payroll', $staff_payslip_id, 'create', 'Voucher number generation failed');
            return false;
        }

        $date = date('Y-m-d', strtotime($payslip['payment_date']));
        $staff_name = ($payslip['name'] ?? '') . ' ' . ($payslip['surname'] ?? '') . ' (' . ($payslip['employee_id'] ?? '') . ')';
        $narration = "Salary paid to {$staff_name} for {$payslip['month']} {$payslip['year']}";

        $voucher_data = [
            'voucher_no'       => $voucher_no,
            'voucher_date'     => $date,
            'voucher_type'     => 'payment',
            'status'           => 'posted',
            'narration'        => $narration,
            'reference_module' => 'payroll',
            'reference_id'     => (string)$staff_payslip_id,
            'session_id'       => $this->getSessionId(),
            'created_by'       => $this->getStaffId(),
        ];

        $this->CI->db->insert('acc_vouchers', $voucher_data);
        $voucher_id = $this->CI->db->insert_id();

        if (!$voucher_id) {
            $this->CI->db->trans_rollback();
            $this->queueFailedSync('payroll', $staff_payslip_id, 'create', 'Voucher insert failed');
            return false;
        }

        $items = [
            [
                'voucher_id'    => $voucher_id,
                'ledger_id'     => $settings['payroll_expense_ledger_id'],
                'debit_amount'  => $net_salary,
                'credit_amount' => 0,
                'narration'     => ''
            ],
            [
                'voucher_id'    => $voucher_id,
                'ledger_id'     => $settings['payroll_payment_ledger_id'],
                'debit_amount'  => 0,
                'credit_amount' => $net_salary,
                'narration'     => ''
            ]
        ];
        $this->CI->db->insert_batch('acc_voucher_items', $items);

        $this->CI->db->trans_complete();
        if ($this->CI->db->trans_status() === FALSE) {
            $this->queueFailedSync('payroll', $staff_payslip_id, 'create', 'Transaction failed');
            return false;
        }
        return true;
    }

    // =========================================================================
    // NATIVE EXPENSE SYNC (P2 — intercepts Smart School expenses table)
    // =========================================================================

    /**
     * Sync a native Smart School expense entry to the double-entry books.
     * Creates a Payment Voucher (Dr: Mapped Expense Ledger | Cr: Cash/Bank).
     */
    public function sync_expense($expense_id)
    {
        $settings = $this->getSettings();
        if (!$settings || empty($settings['enable_expense_sync']) || $settings['enable_expense_sync'] != 1
            || empty($settings['expense_payment_ledger_id'])) {
            return false;
        }

        $expense = $this->CI->db->select('expenses.*, expense_head.exp_category')
                                ->join('expense_head', 'expense_head.id = expenses.exp_head_id', 'left')
                                ->where('expenses.id', $expense_id)
                                ->where('expenses.is_active', 'yes')
                                ->get('expenses')->row_array();

        if (empty($expense) || floatval($expense['amount']) <= 0) {
            return false;
        }

        $ref_id = (string)$expense_id;
        if ($this->voucherExists('ss_expense', $ref_id)) {
            return true;
        }

        $this->CI->db->trans_start();
        $this->CI->db->trans_strict(TRUE);

        $voucher_no = $this->generateVoucherNo('payment');
        if (!$voucher_no) {
            $this->CI->db->trans_rollback();
            $this->queueFailedSync('ss_expense', $ref_id, 'create', 'Voucher number generation failed');
            return false;
        }

        $expense_ledger_id = $this->findOrCreateExpenseLedger($expense['exp_category'] ?? 'General Expense');
        $amount = floatval($expense['amount']);

        $voucher_data = [
            'voucher_no'       => $voucher_no,
            'voucher_date'     => $expense['date'],
            'voucher_type'     => 'payment',
            'status'           => 'posted',
            'narration'        => 'Expense: ' . ($expense['name'] ?? '') . ' [' . ($expense['exp_category'] ?? '') . '] Inv:' . ($expense['invoice_no'] ?? ''),
            'reference_module' => 'ss_expense',
            'reference_id'     => $ref_id,
            'session_id'       => $this->getSessionId(),
            'created_by'       => $this->getStaffId(),
        ];

        $this->CI->db->insert('acc_vouchers', $voucher_data);
        $voucher_id = $this->CI->db->insert_id();

        if (!$voucher_id) {
            $this->CI->db->trans_rollback();
            $this->queueFailedSync('ss_expense', $ref_id, 'create', 'Voucher insert failed');
            return false;
        }

        $items = [
            ['voucher_id' => $voucher_id, 'ledger_id' => $expense_ledger_id, 'debit_amount' => $amount, 'credit_amount' => 0, 'narration' => ''],
            ['voucher_id' => $voucher_id, 'ledger_id' => $settings['expense_payment_ledger_id'], 'debit_amount' => 0, 'credit_amount' => $amount, 'narration' => '']
        ];
        $this->CI->db->insert_batch('acc_voucher_items', $items);

        $this->CI->db->trans_complete();
        if ($this->CI->db->trans_status() === FALSE) {
            $this->queueFailedSync('ss_expense', $ref_id, 'create', 'Transaction failed');
            return false;
        }
        return true;
    }

    // =========================================================================
    // NATIVE INCOME SYNC (P2 — intercepts Smart School income table)
    // =========================================================================

    /**
     * Sync a native Smart School income entry to the double-entry books.
     * Creates a Receipt Voucher (Dr: Cash/Bank | Cr: Mapped Income Ledger).
     */
    public function sync_income($income_id)
    {
        $settings = $this->getSettings();
        if (!$settings || empty($settings['enable_income_sync']) || $settings['enable_income_sync'] != 1
            || empty($settings['income_receipt_ledger_id'])) {
            return false;
        }

        $income = $this->CI->db->select('income.*, income_head.income_category')
                               ->join('income_head', 'income_head.id = income.income_head_id', 'left')
                               ->where('income.id', $income_id)
                               ->where('income.is_active', 'yes')
                               ->get('income')->row_array();

        if (empty($income) || floatval($income['amount']) <= 0) {
            return false;
        }

        $ref_id = (string)$income_id;
        if ($this->voucherExists('ss_income', $ref_id)) {
            return true;
        }

        $this->CI->db->trans_start();
        $this->CI->db->trans_strict(TRUE);

        $voucher_no = $this->generateVoucherNo('receipt');
        if (!$voucher_no) {
            $this->CI->db->trans_rollback();
            $this->queueFailedSync('ss_income', $ref_id, 'create', 'Voucher number generation failed');
            return false;
        }

        $income_ledger_id = $this->findOrCreateIncomeLedger($income['income_category'] ?? 'General Income');
        $amount = floatval($income['amount']);

        $voucher_data = [
            'voucher_no'       => $voucher_no,
            'voucher_date'     => $income['date'],
            'voucher_type'     => 'receipt',
            'status'           => 'posted',
            'narration'        => 'Income: ' . ($income['name'] ?? '') . ' [' . ($income['income_category'] ?? '') . '] Inv:' . ($income['invoice_no'] ?? ''),
            'reference_module' => 'ss_income',
            'reference_id'     => $ref_id,
            'session_id'       => $this->getSessionId(),
            'created_by'       => $this->getStaffId(),
        ];

        $this->CI->db->insert('acc_vouchers', $voucher_data);
        $voucher_id = $this->CI->db->insert_id();

        if (!$voucher_id) {
            $this->CI->db->trans_rollback();
            $this->queueFailedSync('ss_income', $ref_id, 'create', 'Voucher insert failed');
            return false;
        }

        $items = [
            ['voucher_id' => $voucher_id, 'ledger_id' => $settings['income_receipt_ledger_id'], 'debit_amount' => $amount, 'credit_amount' => 0, 'narration' => ''],
            ['voucher_id' => $voucher_id, 'ledger_id' => $income_ledger_id, 'debit_amount' => 0, 'credit_amount' => $amount, 'narration' => '']
        ];
        $this->CI->db->insert_batch('acc_voucher_items', $items);

        $this->CI->db->trans_complete();
        if ($this->CI->db->trans_status() === FALSE) {
            $this->queueFailedSync('ss_income', $ref_id, 'create', 'Transaction failed');
            return false;
        }
        return true;
    }

    // =========================================================================
    // REVERSAL SYNC (P6/P7 — handles payroll revert, fee delete, etc.)
    // =========================================================================

    /**
     * Reverse all posted vouchers linked to a source module + ID.
     * Called when a Smart School core record is deleted or reverted.
     */
    public function reverse_sync($module, $source_id)
    {
        $vouchers = $this->CI->db->where('reference_module', $module)
                                  ->where('reference_id', (string)$source_id)
                                  ->where('status', 'posted')
                                  ->get('acc_vouchers')->result_array();

        if (empty($vouchers)) return true;

        $this->CI->load->model('accvoucher_model');

        foreach ($vouchers as $voucher) {
            $result = $this->CI->accvoucher_model->reverseAutoSyncVoucher($voucher['id']);
            if (!$result) {
                $this->queueFailedSync($module, $source_id, 'reverse', 'Reversal failed for voucher ID: ' . $voucher['id']);
                return false;
            }
        }
        return true;
    }

    // =========================================================================
    // LEDGER AUTO-CREATION HELPERS
    // =========================================================================

    /**
     * Find an existing expense ledger by name, or create one under Indirect Expenses.
     */
    private function findOrCreateExpenseLedger($head_name)
    {
        $ledger = $this->CI->db->select('acc_ledgers.id')
                               ->join('acc_ledger_groups', 'acc_ledger_groups.id = acc_ledgers.group_id')
                               ->where('acc_ledgers.name', $head_name)
                               ->where_in('acc_ledger_groups.system_name', ['direct_expense', 'indirect_expense'])
                               ->get('acc_ledgers')->row();
        if ($ledger) return $ledger->id;

        // Auto-create under Indirect Expenses group
        $group = $this->CI->db->where('system_name', 'indirect_expense')->get('acc_ledger_groups')->row();
        $group_id = $group ? $group->id : 8;
        $this->CI->db->insert('acc_ledgers', [
            'group_id' => $group_id,
            'name' => $head_name,
            'opening_type' => 'Dr',
            'opening_balance' => 0,
            'current_balance' => 0
        ]);
        return $this->CI->db->insert_id();
    }

    /**
     * Find an existing income ledger by name, or create one under Direct Income.
     */
    private function findOrCreateIncomeLedger($head_name)
    {
        $ledger = $this->CI->db->select('acc_ledgers.id')
                               ->join('acc_ledger_groups', 'acc_ledger_groups.id = acc_ledgers.group_id')
                               ->where('acc_ledgers.name', $head_name)
                               ->where_in('acc_ledger_groups.system_name', ['direct_income', 'indirect_income'])
                               ->get('acc_ledgers')->row();
        if ($ledger) return $ledger->id;

        $group = $this->CI->db->where('system_name', 'direct_income')->get('acc_ledger_groups')->row();
        $group_id = $group ? $group->id : 9;
        $this->CI->db->insert('acc_ledgers', [
            'group_id' => $group_id,
            'name' => $head_name,
            'opening_type' => 'Cr',
            'opening_balance' => 0,
            'current_balance' => 0
        ]);
        return $this->CI->db->insert_id();
    }

    // =========================================================================
    // RECONCILIATION — Orphan Detection & Sync Queue Processing
    // =========================================================================

    /**
     * Detect orphaned vouchers whose source records no longer exist.
     * Returns array of voucher IDs that have no matching source record.
     */
    public function detectOrphanedVouchers()
    {
        $orphans = [];

        // Check fee_collection vouchers
        $fee_vouchers = $this->CI->db->where('reference_module', 'fee_collection')
                                      ->where('status', 'posted')
                                      ->get('acc_vouchers')->result_array();
        foreach ($fee_vouchers as $v) {
            $parts = explode('_', $v['reference_id']);
            $deposit_id = $parts[0] ?? 0;
            $exists = $this->CI->db->where('id', $deposit_id)->get('student_fees_deposite')->row();
            $sub_invoice_exists = false;
            if ($exists) {
                $amount_detail = json_decode($exists->amount_detail, true);
                $sub_invoice_id = $parts[1] ?? 0;
                if (isset($amount_detail[$sub_invoice_id])) {
                    $sub_invoice_exists = true;
                }
            }
            if (!$sub_invoice_exists) {
                $orphans[] = ['voucher_id' => $v['id'], 'module' => 'fee_collection', 'ref_id' => $v['reference_id']];
                $this->reverse_sync('fee_collection', $v['reference_id']);
                $this->reverse_sync('fee_discount', $v['reference_id'] . '_disc');
            }
        }

        // Check payroll vouchers
        $payroll_vouchers = $this->CI->db->where('reference_module', 'payroll')
                                          ->where('status', 'posted')
                                          ->get('acc_vouchers')->result_array();
        foreach ($payroll_vouchers as $v) {
            $exists = $this->CI->db->where('id', $v['reference_id'])->where('status', 'paid')->get('staff_payslip')->row();
            if (!$exists) {
                $orphans[] = ['voucher_id' => $v['id'], 'module' => 'payroll', 'ref_id' => $v['reference_id']];
                $this->reverse_sync('payroll', $v['reference_id']);
            }
        }

        return $orphans;
    }

    /**
     * Process pending items in the sync failure queue.
     * Should be called periodically (e.g., via cron or admin action).
     */
    public function processSyncQueue($limit = 20)
    {
        $pending = $this->CI->db->where('status', 'pending')
                                 ->where('attempts <', 5)
                                 ->order_by('created_at', 'asc')
                                 ->limit($limit)
                                 ->get('acc_sync_queue')->result_array();

        $processed = 0;
        foreach ($pending as $item) {
            // Mark as processing
            $this->CI->db->where('id', $item['id'])
                         ->update('acc_sync_queue', ['status' => 'processing', 'attempts' => $item['attempts'] + 1]);

            $result = false;
            if ($item['action'] === 'create') {
                switch ($item['source_module']) {
                    case 'fee_collection':
                        $parts = explode('_', $item['source_id']);
                        $result = $this->sync_fee($parts[0] ?? 0);
                        break;
                    case 'payroll':
                        $result = $this->sync_payroll($item['source_id']);
                        break;
                    case 'ss_expense':
                        $result = $this->sync_expense($item['source_id']);
                        break;
                    case 'ss_income':
                        $result = $this->sync_income($item['source_id']);
                        break;
                }
            } elseif ($item['action'] === 'reverse') {
                $result = $this->reverse_sync($item['source_module'], $item['source_id']);
            }

            $new_status = $result ? 'completed' : ($item['attempts'] >= 4 ? 'failed' : 'pending');
            $this->CI->db->where('id', $item['id'])
                         ->update('acc_sync_queue', [
                             'status' => $new_status,
                             'processed_at' => date('Y-m-d H:i:s')
                         ]);
            if ($result) $processed++;
        }
        return $processed;
    }

    /**
     * Run non-destructive reconciliation checks and return operational metrics.
     */
    public function run_reconciliation_checks()
    {
        $orphans = $this->detectOrphanedVouchers();
        $orphan_by_module = [];
        foreach ($orphans as $orphan) {
            $module = $orphan['module'] ?? 'unknown';
            if (!isset($orphan_by_module[$module])) {
                $orphan_by_module[$module] = 0;
            }
            $orphan_by_module[$module]++;
        }

        $queue_pending = (int)$this->CI->db->where('status', 'pending')->count_all_results('acc_sync_queue');
        $queue_failed = (int)$this->CI->db->where('status', 'failed')->count_all_results('acc_sync_queue');

        $unsynced_fees = 0;
        if ($this->CI->db->table_exists('student_fees_deposite')) {
            $fee_sql = "SELECT COUNT(*) AS total FROM student_fees_deposite d
                        WHERE NOT EXISTS (
                            SELECT 1 FROM acc_vouchers v
                            WHERE v.reference_module = 'fee_collection'
                              AND v.status = 'posted'
                              AND v.reference_id LIKE CONCAT(d.id, '_%')
                        )";
            $fee_row = $this->CI->db->query($fee_sql)->row_array();
            $unsynced_fees = (int)($fee_row['total'] ?? 0);
        }

        $unsynced_payroll = 0;
        if ($this->CI->db->table_exists('staff_payslip')) {
            $payroll_sql = "SELECT COUNT(*) AS total FROM staff_payslip p
                            WHERE LOWER(p.status) = 'paid'
                              AND NOT EXISTS (
                                  SELECT 1 FROM acc_vouchers v
                                  WHERE v.reference_module = 'payroll'
                                    AND v.reference_id = CAST(p.id AS CHAR)
                                    AND v.status = 'posted'
                              )";
            $payroll_row = $this->CI->db->query($payroll_sql)->row_array();
            $unsynced_payroll = (int)($payroll_row['total'] ?? 0);
        }

        $message = 'Reconciliation checks completed. '
            . 'Orphans: ' . count($orphans)
            . ', Queue Pending: ' . $queue_pending
            . ', Queue Failed: ' . $queue_failed
            . ', Un-synced Fees: ' . $unsynced_fees
            . ', Un-synced Payroll: ' . $unsynced_payroll . '.';

        return [
            'status' => true,
            'message' => $message,
            'data' => [
                'orphans_total' => count($orphans),
                'orphans_by_module' => $orphan_by_module,
                'queue_pending' => $queue_pending,
                'queue_failed' => $queue_failed,
                'unsynced_fees' => $unsynced_fees,
                'unsynced_payroll' => $unsynced_payroll,
            ],
        ];
    }

    /**
     * Retry pending/failed queued sync operations.
     */
    public function retry_sync_queue($limit = 50)
    {
        $processed = (int)$this->processSyncQueue($limit);
        return [
            'status' => true,
            'count' => $processed,
            'message' => 'Queue retry completed. Successfully processed ' . $processed . ' queued sync item(s).',
        ];
    }

    // =========================================================================
    // BULK SYNC (HISTORICAL DATA)
    // =========================================================================

    public function sync_all_fees()
    {
        if (function_exists('set_time_limit')) { @set_time_limit(0); }
        if (function_exists('ini_set')) { @ini_set('memory_limit', '-1'); }
        $settings = $this->getSettings();
        if (!$settings || empty($settings['enable_fee_sync'])) {
            return ['status' => false, 'message' => 'Fee sync is disabled in settings.'];
        }

        // Guard: verify required ledger IDs actually exist before starting
        $required = [
            'fee_receipt_ledger_id' => $settings['fee_receipt_ledger_id'] ?? 0,
            'fee_income_ledger_id'  => $settings['fee_income_ledger_id'] ?? 0,
        ];
        foreach ($required as $key => $lid) {
            if (!$lid) return ['status' => false, 'message' => "Sync aborted: $key is not configured. Go to Accounts > Settings and verify Ledger mappings."];
            if (!$this->CI->db->where('id', $lid)->get('acc_ledgers')->row()) {
                return ['status' => false, 'message' => "Sync aborted: Ledger ID $lid for '$key' does not exist in acc_ledgers. Please re-run the DB installer or update script."];
            }
        }

        $deposits = $this->CI->db->select('id')->get('student_fees_deposite')->result();
        $count = 0;
        $errors = [];
        foreach ($deposits as $dep) {
            $exists = $this->CI->db->where('reference_module', 'fee_collection')
                                   ->like('reference_id', $dep->id . '_', 'after')
                                   ->where('status', 'posted')
                                   ->get('acc_vouchers')->row();
            if (!$exists) {
                try {
                    if ($this->sync_fee($dep->id)) {
                        $count++;
                    }
                } catch (\Throwable $e) {
                    $errors[] = "Deposit #{$dep->id}: " . $e->getMessage();
                }
            }
        }
        $msg = "Successfully synced {$count} historical fee deposits.";
        if (!empty($errors)) $msg .= " Errors: " . implode('; ', array_slice($errors, 0, 3));
        return ['status' => true, 'count' => $count, 'message' => $msg];
    }

    public function sync_all_payroll()
    {
        if (function_exists('set_time_limit')) { @set_time_limit(0); }
        if (function_exists('ini_set')) { @ini_set('memory_limit', '-1'); }
        $settings = $this->getSettings();
        if (!$settings || empty($settings['enable_payroll_sync'])) {
            return ['status' => false, 'message' => 'Payroll sync is disabled in settings.'];
        }

        // Guard: verify required ledger IDs exist
        $required = [
            'payroll_payment_ledger_id' => $settings['payroll_payment_ledger_id'] ?? 0,
            'payroll_expense_ledger_id' => $settings['payroll_expense_ledger_id'] ?? 0,
        ];
        foreach ($required as $key => $lid) {
            if (!$lid) return ['status' => false, 'message' => "Sync aborted: $key is not configured in settings."];
            $exists = $this->CI->db->where('id', $lid)->get('acc_ledgers')->row();
            if (!$exists) return ['status' => false, 'message' => "Sync aborted: Ledger ID $lid ($key) not found. Please run the database update script."];
        }

        $payslips = $this->CI->db->select('id')->where('status', 'paid')->get('staff_payslip')->result();
        if (empty($payslips)) {
            // Try case-insensitive match
            $payslips = $this->CI->db->select('id')->where('LOWER(status)', 'paid', false)->get('staff_payslip')->result();
        }
        $count = 0;
        $errors = [];
        foreach ($payslips as $ps) {
            if (!$this->voucherExists('payroll', $ps->id)) {
                try {
                    if ($this->sync_payroll($ps->id)) {
                        $count++;
                    }
                } catch (\Throwable $e) {
                    $errors[] = "Payslip #{$ps->id}: " . $e->getMessage();
                }
            }
        }
        $msg = "Successfully synced {$count} historical payrolls.";
        if (!empty($errors)) $msg .= " Errors: " . implode('; ', array_slice($errors, 0, 3));
        return ['status' => true, 'count' => $count, 'message' => $msg];
    }


    public function sync_all_expenses()
    {
        if (function_exists('set_time_limit')) { @set_time_limit(0); }
        if (function_exists('ini_set')) { @ini_set('memory_limit', '-1'); }
        $settings = $this->getSettings();
        if (!$settings || empty($settings['enable_expense_sync'])) {
            return ['status' => false, 'message' => 'Expense sync is disabled in settings.'];
        }
        if (empty($settings['expense_payment_ledger_id'])) {
            return ['status' => false, 'message' => 'Sync aborted: expense_payment_ledger_id is not configured.'];
        }
        if (!$this->CI->db->where('id', $settings['expense_payment_ledger_id'])->get('acc_ledgers')->row()) {
            return ['status' => false, 'message' => "Sync aborted: Ledger ID {$settings['expense_payment_ledger_id']} not found in acc_ledgers."];
        }

        $expenses = $this->CI->db->select('id')->where('is_active', 'yes')->get('expenses')->result();
        $count = 0;
        $errors = [];
        foreach ($expenses as $ex) {
            if (!$this->voucherExists('ss_expense', $ex->id)) {
                try {
                    if ($this->sync_expense($ex->id)) {
                        $count++;
                    }
                } catch (\Throwable $e) {
                    $errors[] = "Expense #{$ex->id}: " . $e->getMessage();
                }
            }
        }
        $msg = "Successfully synced {$count} historical expenses.";
        if (!empty($errors)) $msg .= " Errors: " . implode('; ', array_slice($errors, 0, 3));
        return ['status' => true, 'count' => $count, 'message' => $msg];
    }

    public function sync_all_income()
    {
        if (function_exists('set_time_limit')) { @set_time_limit(0); }
        if (function_exists('ini_set')) { @ini_set('memory_limit', '-1'); }
        $settings = $this->getSettings();
        if (!$settings || empty($settings['enable_income_sync'])) {
            return ['status' => false, 'message' => 'Income sync is disabled in settings.'];
        }
        if (empty($settings['income_receipt_ledger_id'])) {
            return ['status' => false, 'message' => 'Sync aborted: income_receipt_ledger_id is not configured.'];
        }
        if (!$this->CI->db->where('id', $settings['income_receipt_ledger_id'])->get('acc_ledgers')->row()) {
            return ['status' => false, 'message' => "Sync aborted: Ledger ID {$settings['income_receipt_ledger_id']} not found in acc_ledgers."];
        }

        $incomes = $this->CI->db->select('id')->where('is_active', 'yes')->get('income')->result();
        $count = 0;
        $errors = [];
        foreach ($incomes as $inc) {
            if (!$this->voucherExists('ss_income', $inc->id)) {
                try {
                    if ($this->sync_income($inc->id)) {
                        $count++;
                    }
                } catch (\Throwable $e) {
                    $errors[] = "Income #{$inc->id}: " . $e->getMessage();
                }
            }
        }
        $msg = "Successfully synced {$count} historical incomes.";
        if (!empty($errors)) $msg .= " Errors: " . implode('; ', array_slice($errors, 0, 3));
        return ['status' => true, 'count' => $count, 'message' => $msg];
    }
}
