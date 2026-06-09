<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Paymentvoucher extends MY_Addon_AccountsController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('acc_payment_voucher', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'accounts/paymentvoucher');

        $data['title'] = $this->lang->line('payment_voucher');
        $data['next_voucher_no'] = $this->accvoucher_model->peekVoucherNo('payment');
        $data['expense_types'] = $this->accexpensetype_model->get();
        $data['ledgers'] = $this->accledger_model->getLedgers(); // Dr ledgers
        $data['cash_bank_ledgers'] = $this->accledger_model->getLedgersByGroupIds([1, 2]); // Bank and Cash (Cr)
        $data['ledger_groups'] = $this->accledger_model->getLedgerGroups();
        $data['banks'] = $this->db->get_where('acc_banks', array('is_active' => 1))->result_array();

        $this->form_validation->set_rules('voucher_no', $this->lang->line('voucher_no'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('voucher_date', $this->lang->line('voucher_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('cr_ledger_id', $this->lang->line('payment_mode'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('accounts/payment_voucher/index', $data);
            $this->load->view('layout/footer', $data);
        } else {
            if (!$this->rbac->hasPrivilege('acc_payment_voucher', 'can_add')) {
                access_denied();
            }

            $voucher_date = $this->input->post('voucher_date');
            $cr_ledger_id = $this->input->post('cr_ledger_id');
            
            $payment_method = $this->input->post('payment_method');
            $reference_no = $this->input->post('reference_no');
            $payment_date = $this->input->post('payment_date') ? date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('payment_date'))) : null;
            $bank_name = $this->input->post('bank_name');
            
            $cheque_no = null;
            $upi_transaction_id = null;
            $net_banking_ref = null;

            if ($payment_method == 'Cheque') {
                $cheque_no = $reference_no;
            } elseif ($payment_method == 'UPI') {
                $upi_transaction_id = $reference_no;
            } elseif ($payment_method == 'Net Banking') {
                $net_banking_ref = $reference_no;
            } elseif ($payment_method == 'Card') {
                $net_banking_ref = $reference_no;
            }
            
            $has_approve_perm = $this->rbac->hasPrivilege('acc_approve_voucher', 'can_edit');

            $data_insert = array(
                'voucher_no' => $this->accvoucher_model->generateVoucherNo('payment'),
                'voucher_date' => date('Y-m-d', $this->customlib->datetostrtotime($voucher_date)),
                'voucher_type' => 'payment',
                'status' => $has_approve_perm ? 'posted' : 'draft',
                'narration' => $this->input->post('narration'),
                'payment_method' => $payment_method,
                'cheque_no' => $cheque_no,
                'cheque_date' => $payment_date,
                'bank_name' => $bank_name,
                'upi_transaction_id' => $upi_transaction_id,
                'net_banking_ref' => $net_banking_ref,
                'session_id' => $this->current_session,
                'created_by' => $this->customlib->getStaffID()
            );

            $attachment = $this->handleVoucherUpload('attachment');
            if ($attachment) {
                $data_insert['attachment'] = $attachment;
            }

            // Item processing
            $items = array();
            $ledger_ids = $this->input->post('ledger_id');
            $expense_type_ids = $this->input->post('expense_type_id');
            $amounts = $this->input->post('amount');
            $total_amount = 0;

            if (!empty($ledger_ids)) {
                foreach ($ledger_ids as $key => $val) {
                    if ($val != '') {
                        $amt = floatval($amounts[$key]);
                        if ($amt > 0) {
                            $items[] = array(
                                'ledger_id' => $val,
                                'expense_type_id' => $expense_type_ids[$key],
                                'debit_amount' => $amt,
                                'credit_amount' => 0.00
                            );
                            $total_amount += $amt;
                        }
                    }
                }
            }
            
            // Add the credit side (Cash/Bank)
            if ($total_amount > 0) {
                $items[] = array(
                    'ledger_id' => $cr_ledger_id,
                    'expense_type_id' => null,
                    'debit_amount' => 0.00,
                    'credit_amount' => $total_amount
                );
            }

            $this->accvoucher_model->addVoucher($data_insert, $items);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('accounts/paymentvoucher');
        }
    }

    public function getlist()
    {
        if (!$this->rbac->hasPrivilege('acc_payment_voucher', 'can_view')) {
            access_denied();
        }
        $result = $this->accvoucher_model->getDatatableVouchers('payment');
        $result = json_decode($result);
        $dt_data = array();
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();

        if (!empty($result->data)) {
            foreach ($result->data as $value) {
                $row = array();
                $action = "<div class='d-flex gap-0-5 text-right float-right'>";
                
                if (!empty($value->attachment)) {
                    $action .= "<a href='" . base_url('uploads/accounts/vouchers/' . $value->attachment) . "' class='btn btn-default btn-xs' target='_blank' data-toggle='tooltip' title='Attachment'><i class='fa fa-paperclip'></i></a>";
                }
                
                $action .= "<a href='" . site_url('accounts/paymentvoucher/print_voucher/' . $value->id) . "' class='btn btn-acc-print btn-xs' target='_blank' data-toggle='tooltip' title='Print'><i class='fa fa-print'></i></a>";
                
                if ($value->status === 'draft' && $this->rbac->hasPrivilege('acc_approve_voucher', 'can_edit')) {
                    $action .= "<button type='button' class='btn btn-success btn-xs approve-voucher-btn' data-id='" . $value->id . "' data-toggle='tooltip' title='Approve'><i class='fa fa-check'></i></button>";
                    $action .= "<button type='button' class='btn btn-danger btn-xs reject-voucher-btn' data-id='" . $value->id . "' data-toggle='tooltip' title='Reject'><i class='fa fa-times'></i></button>";
                }
                
                if ($this->rbac->hasPrivilege('acc_payment_voucher', 'can_edit')) {
                    $action .= "<a href='" . site_url('accounts/paymentvoucher/edit/' . $value->id) . "' class='btn btn-primary btn-xs' data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
                }
                if ($this->rbac->hasPrivilege('acc_payment_voucher', 'can_delete')) {
                    $action .= "<a href='" . site_url('accounts/paymentvoucher/delete/' . $value->id) . "' class='btn btn-primary btn-xs' data-toggle='tooltip' title='" . $this->lang->line('delete') . "' onclick='return confirm(\"" . $this->lang->line('delete_confirm') . "\");'><i class='fa fa-remove'></i></a>";
                }
                $action .= "</div>";

                // Fetch Payment details
                $this->db->select('vi.debit_amount, vi.credit_amount, l.name as ledger_name');
                $this->db->from('acc_voucher_items vi');
                $this->db->join('acc_ledgers l', 'l.id = vi.ledger_id');
                $this->db->where('vi.voucher_id', $value->id);
                $items = $this->db->get()->result_array();

                $dr_ledgers = array();
                $cr_ledgers = array();
                foreach ($items as $item) {
                    if ($item['debit_amount'] > 0) {
                        $dr_ledgers[] = $item['ledger_name'];
                    }
                    if ($item['credit_amount'] > 0) {
                        $cr_ledgers[] = $item['ledger_name'];
                    }
                }
                $dr_names = implode(', ', $dr_ledgers);
                $cr_names = implode(', ', $cr_ledgers);

                // Determine badge color and label for payment mode
                $pm = !empty($value->payment_method) ? $value->payment_method : 'Cash';
                $badge_class = 'label-danger'; // Cash
                if (strtolower($pm) == 'cheque') {
                    $badge_class = 'label-info';
                } elseif (strtolower($pm) == 'upi') {
                    $badge_class = 'label-warning';
                } elseif (strtolower($pm) == 'net banking') {
                    $badge_class = 'label-primary';
                } elseif (strtolower($pm) == 'card') {
                    $badge_class = 'label-default';
                }

                $details = "<span class='label " . $badge_class . "' style='font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.5px; padding:3px 8px; border-radius:4px; display:inline-block; margin-bottom:4px;'>" . htmlspecialchars(strtoupper($pm) . " PAYMENT") . "</span>";
                
                if (isset($value->status)) {
                    if ($value->status === 'draft') {
                        $details .= " <span class='label label-warning' style='font-size:9px; font-weight:800; padding:2px 5px; border-radius:3px; margin-left:4px;'>DRAFT</span>";
                    } elseif ($value->status === 'rejected') {
                        $rejected_reason = isset($value->rejected_reason) ? $value->rejected_reason : '';
                        $details .= " <span class='label label-danger' style='font-size:9px; font-weight:800; padding:2px 5px; border-radius:3px; margin-left:4px;' title='" . htmlspecialchars($rejected_reason) . "'>REJECTED</span>";
                    } elseif ($value->status === 'reversed') {
                        $details .= " <span class='label label-danger' style='font-size:9px; font-weight:800; padding:2px 5px; border-radius:3px; margin-left:4px;'>REVERSED</span>";
                    }
                }
                
                if (!empty($value->reference_module)) {
                    $details .= " <span class='label label-primary' style='font-size:9px; font-weight:800; padding:2px 5px; border-radius:3px; margin-left:4px; background-color: #3b82f6 !important;'>AUTO SYNCED</span>";
                }
                $details .= "<br><small class='text-muted' style='font-size:11px; font-weight:500;'><strong style='color:#dc2626;'>" . htmlspecialchars($cr_names) . "</strong> &rarr; <strong style='color:#10b981;'>" . htmlspecialchars($dr_names) . "</strong></small>";

                $more_info = "<strong>Mode:</strong> " . htmlspecialchars(strtoupper($pm)) . "<br>";
                if (!empty($value->cheque_no)) {
                    $more_info .= "<strong>Ref:</strong> " . htmlspecialchars($value->cheque_no);
                } elseif (!empty($value->upi_transaction_id)) {
                    $more_info .= "<strong>Ref:</strong> " . htmlspecialchars($value->upi_transaction_id);
                } elseif (!empty($value->net_banking_ref)) {
                    $more_info .= "<strong>Ref:</strong> " . htmlspecialchars($value->net_banking_ref);
                }

                $narration_text = "";
                if (!empty($value->narration)) {
                    $narration_text .= htmlspecialchars($value->narration);
                }

                $row[] = date($this->customlib->getSchoolDateFormat(), strtotime($value->voucher_date));
                $row[] = $value->voucher_no;
                $row[] = $details;
                $row[] = $currency_symbol . amountFormat($value->total_amount);
                $row[] = $more_info;
                $row[] = $narration_text;
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
        if (!$this->rbac->hasPrivilege('acc_payment_voucher', 'can_edit')) {
            access_denied();
        }

        $data['title'] = $this->lang->line('edit');
        $data['id'] = $id;
        $data['voucher'] = $this->accvoucher_model->getVoucher($id);
        
        // Block edit for auto-synced or reversed vouchers
        if (!empty($data['voucher']['reference_module']) || $data['voucher']['status'] === 'reversed') {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Auto-synced or reversed vouchers cannot be edited manually.</div>');
            redirect('accounts/paymentvoucher');
        }
        
        $data['expense_types'] = $this->accexpensetype_model->get();
        $data['ledgers'] = $this->accledger_model->getLedgers(); // Dr ledgers
        $data['cash_bank_ledgers'] = $this->accledger_model->getLedgersByGroupIds([1, 2]); // Bank and Cash (Cr)
        $data['ledger_groups'] = $this->accledger_model->getLedgerGroups();
        $data['banks'] = $this->db->get_where('acc_banks', array('is_active' => 1))->result_array();

        $this->form_validation->set_rules('voucher_no', $this->lang->line('voucher_no'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('voucher_date', $this->lang->line('voucher_date'), 'trim|required|xss_clean');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('accounts/payment_voucher/index', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $voucher_date = $this->input->post('voucher_date');
            $cr_ledger_id = $this->input->post('cr_ledger_id');
            
            $payment_method = $this->input->post('payment_method');
            $reference_no = $this->input->post('reference_no');
            $payment_date = $this->input->post('payment_date') ? date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('payment_date'))) : null;
            $bank_name = $this->input->post('bank_name');
            
            $cheque_no = null;
            $upi_transaction_id = null;
            $net_banking_ref = null;

            if ($payment_method == 'Cheque') {
                $cheque_no = $reference_no;
            } elseif ($payment_method == 'UPI') {
                $upi_transaction_id = $reference_no;
            } elseif ($payment_method == 'Net Banking' || $payment_method == 'Card') {
                $net_banking_ref = $reference_no;
            }
            
            $data_update = array(
                'id' => $id,
                'voucher_no' => $this->input->post('voucher_no'),
                'voucher_date' => date('Y-m-d', $this->customlib->datetostrtotime($voucher_date)),
                'narration' => $this->input->post('narration'),
                'payment_method' => $payment_method,
                'cheque_no' => $cheque_no,
                'cheque_date' => $payment_date,
                'bank_name' => $bank_name,
                'upi_transaction_id' => $upi_transaction_id,
                'net_banking_ref' => $net_banking_ref,
            );

            // Maker/checker reset
            $old_voucher = $this->accvoucher_model->getVoucher($id);
            if ($old_voucher['status'] === 'rejected' || $old_voucher['status'] === 'draft') {
                $has_approve_perm = $this->rbac->hasPrivilege('acc_approve_voucher', 'can_edit');
                $data_update['status'] = $has_approve_perm ? 'posted' : 'draft';
                $data_update['rejected_reason'] = null;
            }

            // Attachment upload
            $attachment = $this->handleVoucherUpload('attachment');
            if ($attachment) {
                if (!empty($old_voucher['attachment'])) {
                    @unlink('./uploads/accounts/vouchers/' . $old_voucher['attachment']);
                }
                $data_update['attachment'] = $attachment;
            } elseif ($this->input->post('delete_attachment') == 1) {
                if (!empty($old_voucher['attachment'])) {
                    @unlink('./uploads/accounts/vouchers/' . $old_voucher['attachment']);
                }
                $data_update['attachment'] = null;
            }

            // Item processing
            $items = array();
            $ledger_ids = $this->input->post('ledger_id');
            $expense_type_ids = $this->input->post('expense_type_id');
            $amounts = $this->input->post('amount');
            $total_amount = 0;

            if (!empty($ledger_ids)) {
                foreach ($ledger_ids as $key => $val) {
                    if ($val != '') {
                        $amt = floatval($amounts[$key]);
                        if ($amt > 0) {
                            $items[] = array(
                                'ledger_id' => $val,
                                'expense_type_id' => $expense_type_ids[$key],
                                'debit_amount' => $amt,
                                'credit_amount' => 0.00
                            );
                            $total_amount += $amt;
                        }
                    }
                }
            }
            
            // Add the credit side (Cash/Bank)
            if ($total_amount > 0) {
                $items[] = array(
                    'ledger_id' => $cr_ledger_id,
                    'expense_type_id' => null,
                    'debit_amount' => 0.00,
                    'credit_amount' => $total_amount
                );
            }

            $this->accvoucher_model->addVoucher($data_update, $items);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('accounts/paymentvoucher');
        }
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('acc_payment_voucher', 'can_delete')) {
            access_denied();
        }
        $voucher = $this->accvoucher_model->getVoucher($id);
        if ($voucher && (!empty($voucher['reference_module']) || $voucher['status'] === 'reversed')) {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Auto-synced or already reversed vouchers cannot be deleted manually.</div>');
            redirect('accounts/paymentvoucher');
        }

        if ($this->accvoucher_model->deleteVoucher($id)) {
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Voucher reversed successfully.</div>');
        } else {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Failed to reverse voucher.</div>');
        }
        redirect('accounts/paymentvoucher');
    }
}
