<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Journalvoucher extends MY_Addon_AccountsController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('acc_journal_voucher', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'accounts/journalvoucher');

        $data['title'] = $this->lang->line('journal_voucher');
        $data['next_voucher_no'] = $this->accvoucher_model->peekVoucherNo('journal');
        $data['ledgers'] = $this->accledger_model->getLedgers(); // All ledgers available for Journal

        $this->form_validation->set_rules('voucher_no', $this->lang->line('voucher_no'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('voucher_date', $this->lang->line('voucher_date'), 'trim|required|xss_clean');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('accounts/journal_voucher/index', $data);
            $this->load->view('layout/footer', $data);
        } else {
            if (!$this->rbac->hasPrivilege('acc_journal_voucher', 'can_add')) {
                access_denied();
            }

            $voucher_date = $this->input->post('voucher_date');
            
            $has_approve_perm = $this->rbac->hasPrivilege('acc_approve_voucher', 'can_edit');

            $data_insert = array(
                'voucher_no' => $this->accvoucher_model->generateVoucherNo('journal'),
                'voucher_date' => date('Y-m-d', $this->customlib->datetostrtotime($voucher_date)),
                'voucher_type' => 'journal',
                'status' => $has_approve_perm ? 'posted' : 'draft',
                'narration' => $this->input->post('narration'),
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
            $types = $this->input->post('type');
            $amounts = $this->input->post('amount');
            
            $total_dr = 0;
            $total_cr = 0;

            if (!empty($ledger_ids)) {
                foreach ($ledger_ids as $key => $val) {
                    if ($val != '') {
                        $amt = floatval($amounts[$key]);
                        if ($amt > 0) {
                            $is_dr = ($types[$key] == 'Dr');
                            $items[] = array(
                                'ledger_id' => $val,
                                'debit_amount' => $is_dr ? $amt : 0.00,
                                'credit_amount' => !$is_dr ? $amt : 0.00
                            );
                            if ($is_dr) $total_dr += $amt;
                            else $total_cr += $amt;
                        }
                    }
                }
            }
            
            // Double entry check: Total Dr must equal Total Cr
            if ($total_dr != $total_cr || $total_dr == 0) {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">' . $this->lang->line('debit_credit_must_be_equal') . '</div>');
                redirect('accounts/journalvoucher');
            }

            $this->accvoucher_model->addVoucher($data_insert, $items);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('accounts/journalvoucher');
        }
    }

    public function getlist()
    {
        if (!$this->rbac->hasPrivilege('acc_journal_voucher', 'can_view')) {
            access_denied();
        }
        $result = $this->accvoucher_model->getDatatableVouchers('journal');
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
                
                $action .= "<a href='" . site_url('accounts/journalvoucher/print_voucher/' . $value->id) . "' class='btn btn-acc-print btn-xs' target='_blank' data-toggle='tooltip' title='Print'><i class='fa fa-print'></i></a>";
                
                if ($value->status === 'draft' && $this->rbac->hasPrivilege('acc_approve_voucher', 'can_edit')) {
                    $action .= "<button type='button' class='btn btn-success btn-xs approve-voucher-btn' data-id='" . $value->id . "' data-toggle='tooltip' title='Approve'><i class='fa fa-check'></i></button>";
                    $action .= "<button type='button' class='btn btn-danger btn-xs reject-voucher-btn' data-id='" . $value->id . "' data-toggle='tooltip' title='Reject'><i class='fa fa-times'></i></button>";
                }
                
                if ($this->rbac->hasPrivilege('acc_journal_voucher', 'can_edit')) {
                    $action .= "<a href='" . site_url('accounts/journalvoucher/edit/' . $value->id) . "' class='btn btn-primary btn-xs' data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
                }
                if ($this->rbac->hasPrivilege('acc_journal_voucher', 'can_delete')) {
                    $action .= "<a href='" . site_url('accounts/journalvoucher/delete/' . $value->id) . "' class='btn btn-primary btn-xs' data-toggle='tooltip' title='" . $this->lang->line('delete') . "' onclick='return confirm(\"" . $this->lang->line('delete_confirm') . "\");'><i class='fa fa-remove'></i></a>";
                }
                $action .= "</div>";

                // Fetch Journal details
                $this->db->select('vi.debit_amount, vi.credit_amount, l.name as ledger_name');
                $this->db->from('acc_voucher_items vi');
                $this->db->join('acc_ledgers l', 'l.id = vi.ledger_id');
                $this->db->where('vi.voucher_id', $value->id);
                $items = $this->db->get()->result_array();

                $dr_ledgers = array();
                $cr_ledgers = array();
                foreach ($items as $item) {
                    if ($item['debit_amount'] > 0) {
                        $dr_ledgers[] = $item['ledger_name'] . " (Dr)";
                    }
                    if ($item['credit_amount'] > 0) {
                        $cr_ledgers[] = $item['ledger_name'] . " (Cr)";
                    }
                }
                $dr_names = implode(', ', $dr_ledgers);
                $cr_names = implode(', ', $cr_ledgers);

                $details = "<span class='label label-warning' style='font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:0.5px; padding:3px 8px; border-radius:4px; display:inline-block; margin-bottom:4px;'>JOURNAL ENTRY</span>";
                
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
                $details .= "<br><small class='text-muted' style='font-size:11px; font-weight:500;'><strong style='color:#dc2626;'>" . htmlspecialchars($cr_names) . "</strong> &rarr; <strong style='color:#10b981;'>" . htmlspecialchars($dr_names) . "</strong></small>";

                $narration_text = "";
                if (!empty($value->narration)) {
                    $narration_text .= htmlspecialchars($value->narration);
                }

                $row[] = date($this->customlib->getSchoolDateFormat(), strtotime($value->voucher_date));
                $row[] = $value->voucher_no;
                $row[] = $details;
                $row[] = $currency_symbol . amountFormat($value->total_amount);
                $row[] = "";
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
        if (!$this->rbac->hasPrivilege('acc_journal_voucher', 'can_edit')) {
            access_denied();
        }

        $data['title'] = $this->lang->line('edit');
        $data['id'] = $id;
        $data['voucher'] = $this->accvoucher_model->getVoucher($id);
        
        if ($data['voucher']['status'] === 'reversed') {
            $this->session->set_flashdata('msg_toast_error', 'Reversed vouchers cannot be edited manually.');
            redirect('accounts/journalvoucher');
        }
        
        $data['ledgers'] = $this->accledger_model->getLedgers(); // All ledgers

        $this->form_validation->set_rules('voucher_no', $this->lang->line('voucher_no'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('voucher_date', $this->lang->line('voucher_date'), 'trim|required|xss_clean');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('accounts/journal_voucher/index', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $voucher_date = $this->input->post('voucher_date');
            
            $data_update = array(
                'id' => $id,
                'voucher_no' => $this->input->post('voucher_no'),
                'voucher_date' => date('Y-m-d', $this->customlib->datetostrtotime($voucher_date)),
                'narration' => $this->input->post('narration'),
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
            $types = $this->input->post('type');
            $amounts = $this->input->post('amount');
            
            $total_dr = 0;
            $total_cr = 0;

            if (!empty($ledger_ids)) {
                foreach ($ledger_ids as $key => $val) {
                    if ($val != '') {
                        $amt = floatval($amounts[$key]);
                        if ($amt > 0) {
                            $is_dr = ($types[$key] == 'Dr');
                            $items[] = array(
                                'ledger_id' => $val,
                                'debit_amount' => $is_dr ? $amt : 0.00,
                                'credit_amount' => !$is_dr ? $amt : 0.00
                            );
                            if ($is_dr) $total_dr += $amt;
                            else $total_cr += $amt;
                        }
                    }
                }
            }
            
            if ($total_dr != $total_cr || $total_dr == 0) {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">' . $this->lang->line('debit_credit_must_be_equal') . '</div>');
                redirect('accounts/journalvoucher/edit/' . $id);
            }

            $this->accvoucher_model->addVoucher($data_update, $items);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('accounts/journalvoucher');
        }
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('acc_journal_voucher', 'can_delete')) {
            access_denied();
        }
        $voucher = $this->accvoucher_model->getVoucher($id);
        if ($voucher && $voucher['status'] === 'reversed') {
            $this->session->set_flashdata('msg_toast_error', 'Voucher is already reversed.');
            redirect('accounts/journalvoucher');
        }

        if ($this->accvoucher_model->deleteVoucher($id)) {
            $this->session->set_flashdata('msg_toast_success', 'Voucher reversed successfully.');
        } else {
            $this->session->set_flashdata('msg_toast_error', 'Failed to reverse voucher.');
        }
        redirect('accounts/journalvoucher');
    }
}
