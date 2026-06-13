<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Settings extends MY_Addon_AccountsController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('accledger_model');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('acc_settings', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'accounts/settings');

        $data['title'] = 'Accounts Settings';

        // Get current settings
        $settings = $this->db->get('acc_settings')->row_array();
        if (empty($settings)) {
            // Insert default if missing
            $this->db->insert('acc_settings', ['enable_fee_sync' => 0, 'enable_payroll_sync' => 0]);
            $settings = $this->db->get('acc_settings')->row_array();
        }

        // Ensure signature columns exist in acc_settings to prevent db errors
        if ($settings) {
            $alter = false;
            if (!array_key_exists('signature_1_label', $settings)) {
                $this->db->query("ALTER TABLE `acc_settings` ADD COLUMN `signature_1_label` varchar(255) DEFAULT 'Prepared By'");
                $this->db->query("ALTER TABLE `acc_settings` ADD COLUMN `signature_2_label` varchar(255) DEFAULT 'Checked By'");
                $this->db->query("ALTER TABLE `acc_settings` ADD COLUMN `signature_3_label` varchar(255) DEFAULT 'Authorized Signatory'");
                $alter = true;
            }
            if (!array_key_exists('signature_1_photo', $settings)) {
                $this->db->query("ALTER TABLE `acc_settings` ADD COLUMN `signature_1_photo` varchar(255) DEFAULT NULL");
                $this->db->query("ALTER TABLE `acc_settings` ADD COLUMN `signature_2_photo` varchar(255) DEFAULT NULL");
                $this->db->query("ALTER TABLE `acc_settings` ADD COLUMN `signature_3_photo` varchar(255) DEFAULT NULL");
                $this->db->query("ALTER TABLE `acc_settings` ADD COLUMN `signature_1_name` varchar(255) DEFAULT NULL");
                $this->db->query("ALTER TABLE `acc_settings` ADD COLUMN `signature_2_name` varchar(255) DEFAULT NULL");
                $this->db->query("ALTER TABLE `acc_settings` ADD COLUMN `signature_3_name` varchar(255) DEFAULT NULL");
                $alter = true;
            }
            if ($alter) {
                $settings = $this->db->get('acc_settings')->row_array();
            }
        }
        $data['settings'] = $settings;

        // Fetch sequences for prefix and series customization
        $sequences_raw = $this->db->get('acc_voucher_sequences')->result_array();
        $sequences = [];
        foreach ($sequences_raw as $seq) {
            $sequences[$seq['voucher_type'] . '_prefix'] = $seq['prefix'];
            $sequences[$seq['voucher_type'] . '_series'] = $seq['last_number'];
        }
        $data['sequences'] = $sequences;

        // Get ledgers for dropdowns
        $data['bank_cash_ledgers'] = $this->accledger_model->getLedgersByGroupIds([1, 2]); // Bank and Cash
        $data['income_ledgers'] = $this->accledger_model->getLedgersByGroupIds([6, 9, 10]); // Sales, Direct/Indirect Income
        $data['expense_ledgers'] = $this->accledger_model->getLedgersByGroupIds([5, 7, 8]); // Purchase, Direct/Indirect Expense

        $this->form_validation->set_rules('enable_fee_sync', 'Fee Sync', 'trim|xss_clean');
        
        if ($this->input->post('enable_fee_sync') == 1) {
            $this->form_validation->set_rules('fee_receipt_ledger_id', 'Fee Receipt Ledger (Debit)', 'trim|required|xss_clean');
            $this->form_validation->set_rules('fee_income_ledger_id', 'Fee Income Ledger (Credit)', 'trim|required|xss_clean');
            // Optional but recommended for advanced sync
            $this->form_validation->set_rules('gateway_clearing_ledger_id', 'Gateway Clearing Ledger', 'trim|xss_clean');
            $this->form_validation->set_rules('fee_discount_expense_ledger_id', 'Fee Discount Ledger', 'trim|xss_clean');
        }

        if ($this->input->post('enable_payroll_sync') == 1) {
            $this->form_validation->set_rules('payroll_payment_ledger_id', 'Payroll Payment Ledger (Credit)', 'trim|required|xss_clean');
            $this->form_validation->set_rules('payroll_expense_ledger_id', 'Payroll Expense Ledger (Debit)', 'trim|required|xss_clean');
        }

        $this->form_validation->set_rules('enable_expense_sync', 'Native Expense Sync', 'trim|xss_clean');
        if ($this->input->post('enable_expense_sync') == 1) {
            $this->form_validation->set_rules('expense_payment_ledger_id', 'Expense Payment Ledger (Credit)', 'trim|required|xss_clean');
        }

        $this->form_validation->set_rules('enable_income_sync', 'Native Income Sync', 'trim|xss_clean');
        if ($this->input->post('enable_income_sync') == 1) {
            $this->form_validation->set_rules('income_receipt_ledger_id', 'Income Receipt Ledger (Debit)', 'trim|required|xss_clean');
        }

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('accounts/settings/index', $data);
            $this->load->view('layout/footer', $data);
        } else {
            if (!$this->rbac->hasPrivilege('acc_settings', 'can_edit')) {
                access_denied();
            }

            $update_data = array(
                'enable_fee_sync' => $this->input->post('enable_fee_sync') ? 1 : 0,
                'fee_receipt_ledger_id' => $this->input->post('fee_receipt_ledger_id') ?: NULL,
                'fee_bank_receipt_ledger_id' => $this->input->post('fee_bank_receipt_ledger_id') ?: NULL,
                'fee_income_ledger_id' => $this->input->post('fee_income_ledger_id') ?: NULL,
                'gateway_clearing_ledger_id' => $this->input->post('gateway_clearing_ledger_id') ?: NULL,
                'fee_discount_expense_ledger_id' => $this->input->post('fee_discount_expense_ledger_id') ?: NULL,
                
                'enable_payroll_sync' => $this->input->post('enable_payroll_sync') ? 1 : 0,
                'payroll_payment_ledger_id' => $this->input->post('payroll_payment_ledger_id') ?: NULL,
                'payroll_expense_ledger_id' => $this->input->post('payroll_expense_ledger_id') ?: NULL,
                
                'enable_expense_sync' => $this->input->post('enable_expense_sync') ? 1 : 0,
                'expense_payment_ledger_id' => $this->input->post('expense_payment_ledger_id') ?: NULL,
                
                'enable_income_sync' => $this->input->post('enable_income_sync') ? 1 : 0,
                'income_receipt_ledger_id' => $this->input->post('income_receipt_ledger_id') ?: NULL,

                'signature_1_label' => $this->input->post('signature_1_label') ?: 'Prepared By',
                'signature_2_label' => $this->input->post('signature_2_label') ?: 'Checked By',
                'signature_3_label' => $this->input->post('signature_3_label') ?: 'Authorized Signatory',
                'signature_1_name' => $this->input->post('signature_1_name') ?: NULL,
                'signature_3_name' => $this->input->post('signature_3_name') ?: NULL
            );

            // Update sequences
            $voucher_types = ['payment', 'receipt', 'general_receipt', 'contra', 'journal', 'purchase'];
            foreach ($voucher_types as $type) {
                if ($this->input->post($type . '_prefix') !== null) {
                    $prefix = $this->input->post($type . '_prefix');
                    $series = $this->input->post($type . '_series');
                    
                    // Check if sequence row exists
                    $seq_row = $this->db->where('voucher_type', $type)->get('acc_voucher_sequences')->row();
                    if ($seq_row) {
                        $this->db->where('voucher_type', $type)->update('acc_voucher_sequences', [
                            'prefix' => $prefix,
                            'last_number' => $series
                        ]);
                    } else {
                        $this->db->insert('acc_voucher_sequences', [
                            'voucher_type' => $type,
                            'prefix' => $prefix,
                            'last_number' => $series,
                            'padding_length' => 5
                        ]);
                    }
                }
            }

            // Handle file uploads for signature photos
            $upload_path = './uploads/accounts/signatures/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            $this->load->library('upload');

            $photos = array('signature_1_photo', 'signature_2_photo', 'signature_3_photo');
            foreach ($photos as $photo_field) {
                if (!empty($_FILES[$photo_field]['name'])) {
                    $config = array(
                        'upload_path' => $upload_path,
                        'allowed_types' => 'gif|jpg|jpeg|png|PNG|JPG|JPEG|GIF',
                        'max_size' => '2048',
                        'file_name' => uniqid() . '_' . $_FILES[$photo_field]['name'],
                        'overwrite' => TRUE
                    );

                    $this->upload->initialize($config);
                    if ($this->upload->do_upload($photo_field)) {
                        $upload_data = $this->upload->data();
                        // Delete old file if exists
                        if (!empty($settings[$photo_field]) && file_exists($upload_path . $settings[$photo_field])) {
                            @unlink($upload_path . $settings[$photo_field]);
                        }
                        $update_data[$photo_field] = $upload_data['file_name'];
                    }
                } else {
                    // Check if user requested to delete existing photo
                    if ($this->input->post('delete_' . $photo_field) == 1) {
                        if (!empty($settings[$photo_field]) && file_exists($upload_path . $settings[$photo_field])) {
                            @unlink($upload_path . $settings[$photo_field]);
                        }
                        $update_data[$photo_field] = NULL;
                    }
                }
            }

            $this->db->where('id', $settings['id']);
            $this->db->update('acc_settings', $update_data);

            $sync_msg = '';
            
            // Run bulk syncs if requested
            if ($this->input->post('run_bulk_fee_sync') == 1) {
                $this->load->library('accounts_integration');
                $res = $this->accounts_integration->sync_all_fees();
                $sync_msg .= '<br>' . $res['message'];
            }
            if ($this->input->post('run_bulk_payroll_sync') == 1) {
                $this->load->library('accounts_integration');
                $res = $this->accounts_integration->sync_all_payroll();
                $sync_msg .= '<br>' . $res['message'];
            }

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Settings updated successfully' . $sync_msg . '</div>');
            redirect('accounts/settings');
        }
    }

    public function bulksync()
    {
        if (!$this->rbac->hasPrivilege('acc_settings', 'can_edit')) {
            echo json_encode(['status' => false, 'message' => 'Access denied']);
            return;
        }

        // CRITICAL: Disable CI db_debug so DB errors become catchable
        // instead of triggering show_error() which generates HTML + 500 status
        $this->db->db_debug = FALSE;

        $type = $this->input->post('type');
        $this->load->library('accounts_integration');
        
        $result = ['status' => false, 'message' => 'Invalid sync type'];

        try {
            if ($type == 'fees') {
                $result = $this->accounts_integration->sync_all_fees();
            } elseif ($type == 'payroll') {
                $result = $this->accounts_integration->sync_all_payroll();
            } elseif ($type == 'expenses') {
                $result = $this->accounts_integration->sync_all_expenses();
            } elseif ($type == 'income') {
                $result = $this->accounts_integration->sync_all_income();
            } elseif ($type == 'queue_retry') {
                $result = $this->accounts_integration->retry_sync_queue(50);
            } elseif ($type == 'reconciliation') {
                $result = $this->accounts_integration->run_reconciliation_checks();
            }
        } catch (\Throwable $e) {
            log_message('error', 'BulkSync Exception: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
            $result = ['status' => false, 'message' => 'Server error: ' . $e->getMessage()];
        }

        // Restore db_debug
        $this->db->db_debug = (ENVIRONMENT !== 'production');

        echo json_encode($result);
    }
}
