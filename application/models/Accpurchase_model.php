<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Accpurchase_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function addPurchase($data, $items)
    {
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);

        if (isset($data['id']) && $data['id'] > 0) {
            $purchase_id = $data['id'];
            $this->db->where('id', $purchase_id);
            $this->db->update('acc_purchase_entries', $data);

            // Delete old items
            $this->db->where('purchase_id', $purchase_id);
            $this->db->delete('acc_purchase_items');
        } else {
            if (!$this->db->insert('acc_purchase_entries', $data)) {
                $GLOBALS['db_error'] = $this->db->error()['message'];
            }
            $purchase_id = $this->db->insert_id();
        }

        // Insert new items
        if (!empty($items) && $purchase_id > 0) {
            foreach ($items as &$item) {
                $item['purchase_id'] = $purchase_id;
            }
            if (!$this->db->insert_batch('acc_purchase_items', $items)) {
                $GLOBALS['db_error'] = $this->db->error()['message'];
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            log_message('error', 'DB Transaction failed in addPurchase: ' . (isset($GLOBALS['db_error']) ? $GLOBALS['db_error'] : 'Unknown'));
            return false;
        }
        return $purchase_id;
    }

    public function getPurchase($id)
    {
        $this->db->select('acc_purchase_entries.*');
        $this->db->from('acc_purchase_entries');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $purchase = $query->row_array();

        if ($purchase) {
            $this->db->select('acc_purchase_items.*, acc_expense_types.name as expense_type_name');
            $this->db->from('acc_purchase_items');
            $this->db->join('acc_expense_types', 'acc_expense_types.id = acc_purchase_items.expense_type_id', 'left');
            $this->db->where('purchase_id', $purchase['id']);
            $items_query = $this->db->get();
            $purchase['items'] = $items_query->result_array();
        }
        return $purchase;
    }

    public function deletePurchase($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('acc_purchase_entries');
    }

    public function getDatatablePurchases()
    {
        $this->datatables
            ->select('acc_purchase_entries.id, acc_purchase_entries.invoice_no, acc_purchase_entries.purchase_date, acc_purchase_entries.narration, acc_purchase_entries.net_amount, acc_purchase_entries.status, acc_purchase_entries.attachment, acc_ledgers.name as supplier_name')
            ->from('acc_purchase_entries')
            ->join('acc_ledgers', 'acc_ledgers.id = acc_purchase_entries.supplier_ledger_id', 'left')
            ->where('acc_purchase_entries.session_id', $this->current_session)
            ->searchable('acc_purchase_entries.invoice_no, acc_purchase_entries.purchase_date, acc_ledgers.name')
            ->orderable('acc_purchase_entries.invoice_no, acc_purchase_entries.purchase_date, supplier_name, acc_purchase_entries.net_amount')
            ->sort('acc_purchase_entries.id', 'desc');

        return $this->datatables->generate('json');
    }
}
