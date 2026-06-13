<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Purchaseentry extends MY_Addon_AccountsController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('acc_purchase_entry', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Accounts');
        $this->session->set_userdata('sub_menu', 'accounts/purchaseentry');

        $data['title'] = $this->lang->line('purchase_entry');
        $data['suppliers'] = $this->accledger_model->getLedgersBySystemGroup('sundry_creditors');
        $data['expense_types'] = $this->accexpensetype_model->get();
        
        // Generate auto invoice number for new entries
        $data['next_invoice_no'] = $this->accvoucher_model->peekVoucherNo('purchase');

        $this->form_validation->set_rules('supplier_ledger_id', $this->lang->line('supplier'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('purchase_date', $this->lang->line('voucher_date'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('net_amount', $this->lang->line('net_amount'), 'trim|required|numeric|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('accounts/purchase_entry/index', $data);
            $this->load->view('layout/footer', $data);
        } else {
            if (!$this->rbac->hasPrivilege('acc_purchase_entry', 'can_add')) {
                access_denied();
            }

            $purchase_date = $this->input->post('purchase_date');
            
            $invoice_no = $this->input->post('invoice_no');
            if (empty($invoice_no)) {
                $invoice_no = $this->accvoucher_model->generateVoucherNo('purchase');
            }
            
            $has_approve_perm = $this->rbac->hasPrivilege('acc_approve_voucher', 'can_edit');

            $data_insert = array(
                'supplier_ledger_id' => $this->input->post('supplier_ledger_id'),
                'purchase_date' => date('Y-m-d', $this->customlib->datetostrtotime($purchase_date)),
                'invoice_no' => $invoice_no,
                'status' => $has_approve_perm ? 'posted' : 'draft',
                'total_amount' => $this->input->post('total_amount') ?: 0,
                'gst_amount' => $this->input->post('gst_amount') ?: 0,
                'discount' => $this->input->post('discount') ?: 0,
                'net_amount' => $this->input->post('net_amount') ?: 0,
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
            $item_desc = $this->input->post('item_description');
            $expense_type_ids = $this->input->post('expense_type_id');
            $qty = $this->input->post('qty');
            $rate = $this->input->post('rate');
            $amounts = $this->input->post('amount');

            if (!empty($item_desc)) {
                foreach ($item_desc as $key => $val) {
                    if ($val != '') {
                        $items[] = array(
                            'item_description' => $val,
                            'expense_type_id' => empty($expense_type_ids[$key]) ? null : $expense_type_ids[$key],
                            'qty' => empty($qty[$key]) ? 1 : $qty[$key],
                            'rate' => empty($rate[$key]) ? 0 : $rate[$key],
                            'amount' => empty($amounts[$key]) ? 0 : $amounts[$key]
                        );
                    }
                }
            }

            $insert_id = $this->accpurchase_model->addPurchase($data_insert, $items);
            if ($insert_id) {
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            } else {
                $db_error = $this->db->error();
                $err_msg = isset($db_error['message']) ? $db_error['message'] : 'Unknown DB error';
                $post_dump = json_encode($_POST);
                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left" style="word-wrap:break-word;">DB Error: ' . htmlspecialchars($err_msg) . '<br><br>POST Data: ' . htmlspecialchars($post_dump) . '</div>');
            }
            redirect('accounts/purchaseentry' . ($this->input->get('layout') ? '?layout='.$this->input->get('layout') : ''));
        }
    }

    public function getlist()
    {
        if (!$this->rbac->hasPrivilege('acc_purchase_entry', 'can_view')) {
            access_denied();
        }
        $result = $this->accpurchase_model->getDatatablePurchases();
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
                
                $action .= "<a href='" . site_url('accounts/purchaseentry/print_voucher/' . $value->id . '/1') . "' class='btn btn-acc-print btn-xs' target='_blank' data-toggle='tooltip' title='Print'><i class='fa fa-print'></i></a>";
                
                if ($value->status === 'draft' && $this->rbac->hasPrivilege('acc_approve_voucher', 'can_edit')) {
                    $action .= "<button type='button' class='btn btn-success btn-xs approve-purchase-btn' data-id='" . $value->id . "' data-toggle='tooltip' title='Approve'><i class='fa fa-check'></i></button>";
                    $action .= "<button type='button' class='btn btn-danger btn-xs reject-purchase-btn' data-id='" . $value->id . "' data-toggle='tooltip' title='Reject'><i class='fa fa-times'></i></button>";
                }
                
                if ($this->rbac->hasPrivilege('acc_purchase_entry', 'can_edit')) {
                    $action .= "<a href='" . site_url('accounts/purchaseentry/edit/' . $value->id) . "' class='btn btn-primary btn-xs' data-toggle='tooltip' title='" . $this->lang->line('edit') . "'><i class='fa fa-pencil'></i></a>";
                }
                if ($this->rbac->hasPrivilege('acc_purchase_entry', 'can_delete')) {
                    $action .= "<a href='" . site_url('accounts/purchaseentry/delete/' . $value->id) . "' class='btn btn-primary btn-xs' data-toggle='tooltip' title='" . $this->lang->line('delete') . "' onclick='return confirm(\"" . $this->lang->line('delete_confirm') . "\");'><i class='fa fa-remove'></i></a>";
                }
                $action .= "</div>";

                $row[] = date($this->customlib->getSchoolDateFormat(), strtotime($value->purchase_date));
                $row[] = $value->invoice_no;
                $row[] = $value->supplier_name;
                
                $status_html = "";
                if (isset($value->status)) {
                    if ($value->status === 'draft') {
                        $status_html = " <span class='label label-warning' style='font-size:9px;'>DRAFT</span>";
                    } elseif ($value->status === 'rejected') {
                        $rejected_reason = isset($value->rejected_reason) ? $value->rejected_reason : '';
                        $status_html = " <span class='label label-danger' style='font-size:9px;' title='" . htmlspecialchars($rejected_reason) . "'>REJECTED</span>";
                    } elseif ($value->status === 'reversed') {
                        $status_html = " <span class='label label-danger' style='font-size:9px;'>REVERSED</span>";
                    }
                }
                
                $row[] = $currency_symbol . amountFormat($value->net_amount) . $status_html;
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
        if (!$this->rbac->hasPrivilege('acc_purchase_entry', 'can_edit')) {
            access_denied();
        }

        $data['title'] = $this->lang->line('edit');
        $data['id'] = $id;
        $data['purchase'] = $this->accpurchase_model->getPurchase($id);
        
        if ($data['purchase']['status'] === 'reversed') {
            $this->session->set_flashdata('msg_toast_error', 'Reversed purchase entries cannot be edited manually.');
            redirect('accounts/purchaseentry');
        }
        
        $data['suppliers'] = $this->accledger_model->getLedgersBySystemGroup('sundry_creditors');
        $data['expense_types'] = $this->accexpensetype_model->get();

        $this->form_validation->set_rules('supplier_ledger_id', $this->lang->line('supplier'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('purchase_date', $this->lang->line('voucher_date'), 'trim|required|xss_clean');
        
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('accounts/purchase_entry/index', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $purchase_date = $this->input->post('purchase_date');
            
            $data_update = array(
                'id' => $id,
                'supplier_ledger_id' => $this->input->post('supplier_ledger_id'),
                'purchase_date' => date('Y-m-d', $this->customlib->datetostrtotime($purchase_date)),
                'invoice_no' => $this->input->post('invoice_no'),
                'total_amount' => $this->input->post('total_amount') ?: 0,
                'gst_amount' => $this->input->post('gst_amount') ?: 0,
                'discount' => $this->input->post('discount') ?: 0,
                'net_amount' => $this->input->post('net_amount') ?: 0,
                'narration' => $this->input->post('narration')
            );

            // Maker/checker reset
            $old_purchase = $this->accpurchase_model->getPurchase($id);
            if ($old_purchase['status'] === 'rejected' || $old_purchase['status'] === 'draft') {
                $has_approve_perm = $this->rbac->hasPrivilege('acc_approve_voucher', 'can_edit');
                $data_update['status'] = $has_approve_perm ? 'posted' : 'draft';
                $data_update['rejected_reason'] = null;
            }

            // Attachment upload
            $attachment = $this->handleVoucherUpload('attachment');
            if ($attachment) {
                if (!empty($old_purchase['attachment'])) {
                    @unlink('./uploads/accounts/vouchers/' . $old_purchase['attachment']);
                }
                $data_update['attachment'] = $attachment;
            } elseif ($this->input->post('delete_attachment') == 1) {
                if (!empty($old_purchase['attachment'])) {
                    @unlink('./uploads/accounts/vouchers/' . $old_purchase['attachment']);
                }
                $data_update['attachment'] = null;
            }

            // Item processing
            $items = array();
            $item_desc = $this->input->post('item_description');
            $expense_type_ids = $this->input->post('expense_type_id');
            $qty = $this->input->post('qty');
            $rate = $this->input->post('rate');
            $amounts = $this->input->post('amount');

            if (!empty($item_desc)) {
                foreach ($item_desc as $key => $val) {
                    if ($val != '') {
                        $items[] = array(
                            'item_description' => $val,
                            'expense_type_id' => empty($expense_type_ids[$key]) ? null : $expense_type_ids[$key],
                            'qty' => empty($qty[$key]) ? 1 : $qty[$key],
                            'rate' => empty($rate[$key]) ? 0 : $rate[$key],
                            'amount' => empty($amounts[$key]) ? 0 : $amounts[$key]
                        );
                    }
                }
            }

            $update_res = $this->accpurchase_model->addPurchase($data_update, $items);
            if ($update_res !== false) {
                $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            } else {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger">Error: Failed to update the purchase entry.</div>');
            }
            redirect('accounts/purchaseentry' . ($this->input->get('layout') ? '?layout='.$this->input->get('layout') : ''));
        }
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('acc_purchase_entry', 'can_delete')) {
            access_denied();
        }
        $this->accpurchase_model->deletePurchase($id);
        redirect('accounts/purchaseentry');
    }

    public function addsupplier()
    {
        if (!$this->rbac->hasPrivilege('acc_purchase_entry', 'can_add')) {
            echo json_encode(array('status' => 'fail', 'error' => 'Access Denied'));
            return;
        }
        $name = $this->input->post('name');
        $mobile = $this->input->post('mobile');
        if (!empty($name)) {
            $data_insert = array(
                'group_id' => 4, // Sundry Creditors (Supplier)
                'name' => $name,
                'mobile' => $mobile,
                'opening_balance' => 0.00,
                'opening_type' => 'Cr'
            );
            $insert_id = $this->accledger_model->addLedger($data_insert);
            echo json_encode(array('status' => 'success', 'id' => $insert_id, 'name' => $name));
        } else {
            echo json_encode(array('status' => 'fail', 'error' => 'Supplier name is required'));
        }
    }

    public function approve_purchase()
    {
        if (!$this->rbac->hasPrivilege('acc_approve_voucher', 'can_edit')) {
            echo json_encode(['status' => false, 'message' => 'No permission']);
            return;
        }
        
        $id = $this->input->post('purchase_id');
        $action = $this->input->post('action'); // 'approve' or 'reject'
        $reason = $this->input->post('reason');
        
        $purchase = $this->accpurchase_model->getPurchase($id);
        if (!$purchase || $purchase['status'] !== 'draft') {
            echo json_encode(['status' => false, 'message' => 'Invalid purchase entry']);
            return;
        }
        
        if ($action === 'approve') {
            $this->db->where('id', $id)->update('acc_purchase_entries', [
                'status' => 'posted',
                'approved_by' => $this->customlib->getStaffID(),
                'approved_at' => date('Y-m-d H:i:s')
            ]);
            
            echo json_encode(['status' => true, 'message' => 'Purchase entry approved']);
        } else {
            $this->db->where('id', $id)->update('acc_purchase_entries', [
                'status' => 'rejected',
                'rejected_reason' => $reason
            ]);
            echo json_encode(['status' => true, 'message' => 'Purchase entry rejected']);
        }
    }
}
