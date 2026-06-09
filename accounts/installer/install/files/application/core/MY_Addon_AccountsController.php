<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class MY_Addon_AccountsController extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->config('accounts-config');
        
        $this->load->model(array(
            "accledger_model",
            "accvoucher_model",
            "accexpensetype_model",
            "accpurchase_model",
            "accreport_model",
            "setting_model"
        ));

        $this->current_session = $this->setting_model->getCurrentSession();

        // Dynamically sync currency settings from database to prevent stale session values
        $school_setting = $this->setting_model->getSetting();
        if ($school_setting && isset($this->session->userdata['admin'])) {
            $admin_data = $this->session->userdata['admin'];
            $admin_data['currency_symbol'] = $school_setting->currency_symbol;
            $admin_data['currency_base_price'] = $school_setting->base_price;
            $admin_data['currency'] = $school_setting->currency_id;
            $admin_data['currency_name'] = $school_setting->currency;
            $admin_data['currency_place'] = $school_setting->currency_place;
            $this->session->set_userdata('admin', $admin_data);
        }

        // Check if addon is active
        if ($this->uri->segment(1) == "accounts") {
            // "ssacc" is our shortcode logic if using addon verification
            $this->auth->addonchk('ssacc', site_url('admin/dashboard'));
        }
    }

    public function quick_add_ledger_ajax()
    {
        $group_id = $this->input->post('group_id');
        $name = $this->input->post('name');
        
        if (empty($name) || empty($group_id)) {
            echo json_encode(array('status' => 'fail', 'error' => 'Ledger name and group are required.'));
            return;
        }

        $data_insert = array(
            'group_id' => $group_id,
            'name' => $name,
            'mobile' => $this->input->post('mobile') ? $this->input->post('mobile') : '',
            'email' => $this->input->post('email') ? $this->input->post('email') : '',
            'bank_id' => $this->input->post('bank_id') ? $this->input->post('bank_id') : null,
            'account_no' => $this->input->post('account_no') ? $this->input->post('account_no') : '',
            'opening_balance' => $this->input->post('opening_balance') ? floatval($this->input->post('opening_balance')) : 0.00,
            'opening_type' => $this->input->post('opening_type') ? $this->input->post('opening_type') : 'Dr'
        );

        $insert_id = $this->accledger_model->addLedger($data_insert);
        if ($insert_id) {
            echo json_encode(array('status' => 'success', 'id' => $insert_id, 'name' => $name));
        } else {
            echo json_encode(array('status' => 'fail', 'error' => 'Failed to insert ledger.'));
        }
    }

    public function approve_voucher()
    {
        if (!$this->rbac->hasPrivilege('acc_approve_voucher', 'can_edit')) {
            echo json_encode(['status' => false, 'message' => 'No permission']);
            return;
        }

        $id = $this->input->post('voucher_id');
        $action = $this->input->post('action'); // 'approve' or 'reject'
        $reason = $this->input->post('reason');

        $voucher = $this->accvoucher_model->getVoucher($id);
        if (!$voucher || $voucher['status'] !== 'draft') {
            echo json_encode(['status' => false, 'message' => 'Invalid voucher or not in draft state']);
            return;
        }

        if ($action === 'approve') {
            $this->db->where('id', $id)->update('acc_vouchers', [
                'status' => 'posted',
                'approved_by' => $this->customlib->getStaffID(),
                'approved_at' => date('Y-m-d H:i:s')
            ]);
            
            // Triggers automatically handle the balances when status changes to 'posted'
            
            echo json_encode(['status' => true, 'message' => 'Voucher approved successfully']);
        } else {
            $this->db->where('id', $id)->update('acc_vouchers', [
                'status' => 'rejected',
                'rejected_reason' => $reason
            ]);
            echo json_encode(['status' => true, 'message' => 'Voucher rejected']);
        }
    }

    public function print_voucher($id, $is_purchase = 0)
    {
        $print_header = $this->setting_model->get_general_purpose_header();
        $print_footer = $this->setting_model->get_general_purpose_footer();

        $school_setting = $this->setting_model->getSetting();

        if ($is_purchase) {
            $this->load->model('accpurchase_model');
            $data['purchase'] = $this->accpurchase_model->getPurchase($id);
            if (empty($data['purchase'])) {
                show_404();
            }
            // Fetch supplier name
            $supplier = $this->db->select('name')->where('id', $data['purchase']['supplier_ledger_id'])->get('acc_ledgers')->row();
            $data['supplier_name'] = $supplier ? $supplier->name : 'N/A';
            $data['voucher_type'] = 'purchase';
            $data['title'] = 'Purchase Entry';
        } else {
            $data['voucher'] = $this->accvoucher_model->getVoucher($id);
            if (empty($data['voucher'])) {
                show_404();
            }
            $data['voucher_type'] = $data['voucher']['voucher_type'];
            $data['title'] = ucfirst($data['voucher_type']) . ' Voucher';
        }

        $data['print_header'] = $print_header;
        $data['print_footer'] = $print_footer;
        $data['school_setting'] = $school_setting;
        $data['currency_symbol'] = $school_setting->currency_symbol;

        // Fetch accounts settings for signature labels
        $data['settings'] = $this->db->get('acc_settings')->row_array();

        $this->load->view('accounts/print/print_voucher', $data);
    }

    protected function handleVoucherUpload($field_name = 'attachment')
    {
        if (empty($_FILES[$field_name]['name'])) return null;
        
        $upload_path = './uploads/accounts/vouchers/';
        if (!is_dir($upload_path)) mkdir($upload_path, 0777, true);
        
        $config = [
            'upload_path'   => $upload_path,
            'allowed_types' => 'gif|jpg|jpeg|png|pdf|doc|docx|xls|xlsx|csv',
            'max_size'      => 5120, // 5MB
            'file_name'     => uniqid('voucher_') . '_' . $_FILES[$field_name]['name'],
            'overwrite'     => FALSE
        ];
        
        $this->load->library('upload');
        $this->upload->initialize($config);
        if ($this->upload->do_upload($field_name)) {
            return $this->upload->data('file_name');
        }
        log_message('error', 'Voucher upload failed: ' . $this->upload->display_errors('', ''));
        return null;
    }


}
