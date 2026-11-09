<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Accdayclose_model
 * Handles Day-Close locking, verification, and cash denomination counting.
 * Zero-impact on existing vouchers, provides non-blocking / warning status.
 */
class Accdayclose_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('accreport_model');
    }

    /**
     * Check if a specific date is closed/locked.
     */
    public function isDateLocked($date)
    {
        $res = $this->db->select('id, status, close_date')
                        ->from('acc_day_close')
                        ->where('close_date', $date)
                        ->where('status', 'LOCKED')
                        ->get()
                        ->row_array();
        return !empty($res);
    }

    /**
     * Get day close record for a date (or latest).
     */
    public function getDayClose($date = null)
    {
        if ($date) {
            return $this->db->get_where('acc_day_close', ['close_date' => $date])->row_array();
        }
        return $this->db->order_by('close_date', 'DESC')->get('acc_day_close', 30)->result_array();
    }

    /**
     * Get latest locked date.
     */
    public function getLatestLockedDate()
    {
        $row = $this->db->select('close_date')
                        ->from('acc_day_close')
                        ->where('status', 'LOCKED')
                        ->order_by('close_date', 'DESC')
                        ->limit(1)
                        ->get()
                        ->row_array();
        return $row ? $row['close_date'] : null;
    }

    /**
     * Close a day: freeze closing cash and bank balance, store physical cash & denominations.
     */
    public function closeDay($data, $denominations = [])
    {
        $close_date = $data['close_date'];

        // Compute current system balances on that date if not provided
        if (!isset($data['cash_balance']) || !isset($data['bank_balance'])) {
            $cashBook = $this->accreport_model->getCashBook($close_date, $close_date);
            $bankBook = $this->accreport_model->getBankBook($close_date, $close_date);
            $data['cash_balance'] = $cashBook['closing_balance'] ?? 0.00;
            $data['bank_balance'] = $bankBook['closing_balance'] ?? 0.00;
        }

        $existing = $this->db->get_where('acc_day_close', ['close_date' => $close_date])->row_array();
        if ($existing) {
            $this->db->where('id', $existing['id'])->update('acc_day_close', $data);
            $day_close_id = $existing['id'];
        } else {
            $this->db->insert('acc_day_close', $data);
            $day_close_id = $this->db->insert_id();
        }

        if (!empty($denominations)) {
            $denominations['day_close_id'] = $day_close_id;
            $denominations['entry_date'] = $close_date;
            $denominations['book_balance'] = $data['cash_balance'];
            $denominations['difference'] = ($denominations['total_physical'] ?? 0.00) - $data['cash_balance'];

            $existDenom = $this->db->get_where('acc_cash_denominations', ['entry_date' => $close_date])->row_array();
            if ($existDenom) {
                $this->db->where('id', $existDenom['id'])->update('acc_cash_denominations', $denominations);
            } else {
                $this->db->insert('acc_cash_denominations', $denominations);
            }
        }

        return $day_close_id;
    }

    /**
     * Reopen a closed day (with audit reason).
     */
    public function reopenDay($date, $staff_id, $reason)
    {
        $update = [
            'status' => 'UNLOCKED',
            'reopened_by' => $staff_id,
            'reopened_at' => date('Y-m-d H:i:s'),
            'reopen_reason' => $reason
        ];
        return $this->db->where('close_date', $date)->update('acc_day_close', $update);
    }

    /**
     * Get denomination entry for a date.
     */
    public function getDenomination($date)
    {
        return $this->db->get_where('acc_cash_denominations', ['entry_date' => $date])->row_array();
    }
}
