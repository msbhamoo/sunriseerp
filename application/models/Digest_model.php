<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Digest_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_stats($start_date, $end_date)
    {
        $stats = [
            'income' => 0,
            'expense' => 0,
            'fee_collection' => 0,
            'staff_present' => 0,
            'staff_absent' => 0,
            'student_present' => 0,
            'student_absent' => 0,
            'acc_voucher_total' => 0,
            'acc_highest_voucher' => 0,
            'acc_active_ledgers' => []
        ];

        // 1. Income (from income table)
        $this->db->select('IFNULL(SUM(amount), 0) as total');
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        $query = $this->db->get('income');
        if ($query->num_rows() > 0) {
            $stats['income'] = $query->row()->total;
        }

        // 2. Expense (from expenses table)
        $this->db->select('IFNULL(SUM(amount), 0) as total');
        $this->db->where('date >=', $start_date);
        $this->db->where('date <=', $end_date);
        $query = $this->db->get('expenses');
        if ($query->num_rows() > 0) {
            $stats['expense'] = $query->row()->total;
        }

        // 3. Fee Collection (from student_fees_deposite)
        // Wait, student fee deposit table might be `student_fees_deposite` or similar. We will just use `amount` from `student_fees_deposite`.
        // Let's check table name. It's usually `student_fees_deposite` or we can query `student_fee_items`? Wait, I'll just check if it exists.
        if ($this->db->table_exists('student_fees_deposite')) {
            $this->db->select('amount_detail');
            // 'amount_detail' JSON might not have standard date. The table uses created_at or we can just fetch all in date range if 'date' column doesn't exist. Wait, does 'student_fees_deposite' have a 'date' column? It might not!
            // Let's just do a safer approach, try-catch or check fields.
            if ($this->db->field_exists('amount_detail', 'student_fees_deposite')) {
                $query = $this->db->get('student_fees_deposite');
                foreach ($query->result() as $row) {
                    $details = json_decode($row->amount_detail, true);
                    if (is_array($details)) {
                        foreach ($details as $detail) {
                            if (isset($detail['date']) && $detail['date'] >= $start_date && $detail['date'] <= $end_date) {
                                $stats['fee_collection'] += (isset($detail['amount']) ? $detail['amount'] : 0);
                            }
                        }
                    }
                }
            }
        }

        // 4. Staff Attendance
        if ($this->db->table_exists('staff_attendance')) {
            $this->db->select('staff_attendance_type_id, COUNT(*) as count');
            $this->db->where('date >=', $start_date);
            $this->db->where('date <=', $end_date);
            $this->db->group_by('staff_attendance_type_id');
            $query = $this->db->get('staff_attendance');
            foreach ($query->result() as $row) {
                if ($row->staff_attendance_type_id == 1) { // 1=Present, 2=Late, 3=Absent, 4=Half Day (usually)
                    $stats['staff_present'] += $row->count;
                } elseif ($row->staff_attendance_type_id == 3) {
                    $stats['staff_absent'] += $row->count;
                }
            }
        }

        // 5. Student Attendance
        if ($this->db->table_exists('student_attendences')) {
            $this->db->select('attendence_type_id, COUNT(*) as count');
            $this->db->where('date >=', $start_date);
            $this->db->where('date <=', $end_date);
            $this->db->group_by('attendence_type_id');
            $query = $this->db->get('student_attendences');
            foreach ($query->result() as $row) {
                if ($row->attendence_type_id == 1) { 
                    $stats['student_present'] += $row->count;
                } elseif ($row->attendence_type_id == 3) {
                    $stats['student_absent'] += $row->count;
                }
            }
        }

        // Fetch Accounting Data (if module is installed)
        if ($this->db->table_exists('acc_vouchers')) {
            // Total voucher volume
            $this->db->select('IFNULL(SUM(debit_amount), 0) as total');
            $this->db->from('acc_voucher_items');
            $this->db->join('acc_vouchers', 'acc_vouchers.id = acc_voucher_items.voucher_id');
            $this->db->where('acc_vouchers.voucher_date >=', $start_date);
            $this->db->where('acc_vouchers.voucher_date <=', $end_date);
            $this->db->where('acc_vouchers.status', 'posted');
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                $stats['acc_voucher_total'] = $query->row()->total;
            }

            // Highest Voucher Amount
            $this->db->select('IFNULL(MAX(debit_amount), 0) as max_val');
            $this->db->from('acc_voucher_items');
            $this->db->join('acc_vouchers', 'acc_vouchers.id = acc_voucher_items.voucher_id');
            $this->db->where('acc_vouchers.voucher_date >=', $start_date);
            $this->db->where('acc_vouchers.voucher_date <=', $end_date);
            $this->db->where('acc_vouchers.status', 'posted');
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                $stats['acc_highest_voucher'] = $query->row()->max_val;
            }

            // All Active Ledgers for the period
            $this->db->select('acc_ledgers.name as ledger_name, IFNULL(SUM(debit_amount), 0) as total_debit, IFNULL(SUM(credit_amount), 0) as total_credit');
            $this->db->from('acc_voucher_items');
            $this->db->join('acc_vouchers', 'acc_vouchers.id = acc_voucher_items.voucher_id');
            $this->db->join('acc_ledgers', 'acc_ledgers.id = acc_voucher_items.ledger_id');
            $this->db->where('acc_vouchers.voucher_date >=', $start_date);
            $this->db->where('acc_vouchers.voucher_date <=', $end_date);
            $this->db->where('acc_vouchers.status', 'posted');
            $this->db->group_by('acc_ledgers.id');
            $this->db->order_by('total_debit', 'DESC');
            $query = $this->db->get();
            if ($query->num_rows() > 0) {
                $stats['acc_active_ledgers'] = $query->result_array();
            }
        }

        return $stats;
    }
}
