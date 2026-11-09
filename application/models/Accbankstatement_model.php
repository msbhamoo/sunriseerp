<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Accbankstatement_model
 * Handles parsing, storing, and auto-matching bank statement rows with LMS vouchers.
 * Zero-impact on existing vouchers, tracks reconciliation state cleanly.
 */
class Accbankstatement_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get statements for a bank ledger within a date range
     */
    public function getStatements($bank_ledger_id, $date_from = null, $date_to = null, $match_status = null)
    {
        $this->db->select('s.*, v.voucher_no, v.voucher_type, staff.name as imported_by_name')
                 ->from('acc_bank_statements s')
                 ->join('acc_vouchers v', 'v.id = s.matched_voucher_id', 'left')
                 ->join('staff', 'staff.id = s.imported_by', 'left')
                 ->where('s.bank_ledger_id', $bank_ledger_id);

        if ($date_from && $date_to) {
            $this->db->where('s.transaction_date >=', $date_from)
                     ->where('s.transaction_date <=', $date_to);
        }

        if ($match_status && in_array($match_status, ['MATCHED', 'UNMATCHED', 'EXCLUDED'])) {
            $this->db->where('s.match_status', $match_status);
        }

        return $this->db->order_by('s.transaction_date', 'DESC')
                        ->order_by('s.id', 'DESC')
                        ->get()
                        ->result_array();
    }

    /**
     * Batch insert imported rows
     */
    public function insertBatch($rows)
    {
        if (empty($rows)) {
            return 0;
        }
        return $this->db->insert_batch('acc_bank_statements', $rows);
    }

    /**
     * Auto-Match algorithm:
     * Attempts to match UNMATCHED bank statement entries with bank vouchers
     * matching on (bank_ledger_id, amount, date window +- 3 days, or cheque/ref number).
     */
    public function autoMatch($bank_ledger_id)
    {
        $unmatched = $this->db->get_where('acc_bank_statements', [
            'bank_ledger_id' => $bank_ledger_id,
            'match_status' => 'UNMATCHED'
        ])->result_array();

        $matched_count = 0;

        foreach ($unmatched as $row) {
            $tDate = $row['transaction_date'];
            $dateStart = date('Y-m-d', strtotime($tDate . ' -3 days'));
            $dateEnd = date('Y-m-d', strtotime($tDate . ' +3 days'));

            // Look for matching voucher
            $this->db->select('v.id, v.voucher_no')
                     ->from('acc_vouchers v')
                     ->join('acc_voucher_items vi', 'vi.voucher_id = v.id')
                     ->where('vi.ledger_id', $bank_ledger_id)
                     ->where('v.voucher_date >=', $dateStart)
                     ->where('v.voucher_date <=', $dateEnd)
                     ->where('v.status !=', 'reversed');

            if ((float)$row['credit'] > 0) {
                // Bank received money (Debit to Bank in books)
                $this->db->where('vi.debit', (float)$row['credit']);
            } elseif ((float)$row['debit'] > 0) {
                // Bank deducted money (Credit to Bank in books)
                $this->db->where('vi.credit', (float)$row['debit']);
            }

            // Reference match if available
            if (!empty($row['cheque_no'])) {
                $this->db->group_start()
                         ->where('v.cheque_no', $row['cheque_no'])
                         ->or_like('v.narration', $row['cheque_no'])
                         ->group_end();
            }

            $cand = $this->db->limit(1)->get()->row_array();

            if ($cand) {
                $this->db->where('id', $row['id'])->update('acc_bank_statements', [
                    'matched_voucher_id' => $cand['id'],
                    'match_status' => 'MATCHED'
                ]);
                $matched_count++;
            }
        }

        return $matched_count;
    }

    /**
     * Manual match link
     */
    public function matchManual($statement_id, $voucher_id)
    {
        return $this->db->where('id', $statement_id)->update('acc_bank_statements', [
            'matched_voucher_id' => $voucher_id,
            'match_status' => 'MATCHED'
        ]);
    }

    /**
     * Unmatch / reset
     */
    public function unmatch($statement_id)
    {
        return $this->db->where('id', $statement_id)->update('acc_bank_statements', [
            'matched_voucher_id' => null,
            'match_status' => 'UNMATCHED'
        ]);
    }
}
