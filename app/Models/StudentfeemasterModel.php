<?php

namespace App\Models;

/**
 * StudentfeemasterModel
 *
 * Model for student fee master records (`student_fees_master`, `student_fees_deposite`).
 */
class StudentfeemasterModel extends BaseModel
{
    protected $table = 'student_fees_master';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'student_session_id', 'fee_session_group_id', 'amount', 'is_system', 'created_at', 'updated_at'
    ];

    public function getFeesDashboardStats(): array
    {
        $sessionModel = new SessionModel();
        $sessionId = $sessionModel->getCurrentSessionId();

        // 1. Total Fees (Assigned)
        $sql_total = "SELECT SUM(fee_groups_feetype.amount) as total_fees 
                      FROM student_fees_master 
                      INNER JOIN fee_session_groups ON fee_session_groups.id = student_fees_master.fee_session_group_id
                      INNER JOIN student_session ON student_session.id = student_fees_master.student_session_id
                      INNER JOIN students ON students.id = student_session.student_id
                      INNER JOIN fee_groups ON fee_groups.id = fee_session_groups.fee_groups_id
                      INNER JOIN fee_groups_feetype ON fee_groups.id = fee_groups_feetype.fee_groups_id
                      WHERE student_session.session_id = ? AND fee_session_groups.session_id = ? AND students.is_active = 'yes'";
        $q_total = $this->db->query($sql_total, [$sessionId, $sessionId]);
        $total_fees = 0;
        if ($q_total) {
            $res_total = $q_total->getRow();
            $total_fees = ($res_total && $res_total->total_fees) ? (float)$res_total->total_fees : 0;
        }

        // Add Transport Fees to Total
        $sql_transport = "SELECT SUM(route_pickup_point.fees) as transport_fees
                          FROM student_transport_fees
                          INNER JOIN student_session ON student_session.id = student_transport_fees.student_session_id
                          INNER JOIN students ON students.id = student_session.student_id
                          INNER JOIN route_pickup_point ON route_pickup_point.id = student_transport_fees.route_pickup_point_id
                          WHERE student_session.session_id = ? AND students.is_active = 'yes'";
        $q_trans = $this->db->query($sql_transport, [$sessionId]);
        if ($q_trans) {
            $res_trans = $q_trans->getRow();
            $total_fees += ($res_trans && $res_trans->transport_fees) ? (float)$res_trans->transport_fees : 0;
        }

        // 2. Fetch all deposits for current session
        $sql_deposits = "SELECT sfd.amount_detail, 
                                COALESCE(ss_sfm.class_id, ss_stf.class_id) as class_id,
                                COALESCE(c.class, '') as class_name,
                                COALESCE(ss_sfm.section_id, ss_stf.section_id) as section_id
                         FROM student_fees_deposite sfd
                         LEFT JOIN student_fees_master sfm ON sfm.id = sfd.student_fees_master_id
                         LEFT JOIN student_transport_fees stf ON stf.id = sfd.student_transport_fee_id
                         LEFT JOIN student_session ss_sfm ON ss_sfm.id = sfm.student_session_id
                         LEFT JOIN student_session ss_stf ON ss_stf.id = stf.student_session_id
                         LEFT JOIN classes c ON c.id = COALESCE(ss_sfm.class_id, ss_stf.class_id)
                         WHERE (ss_sfm.session_id = ? OR ss_stf.session_id = ?)";
        $q_dep = $this->db->query($sql_deposits, [$sessionId, $sessionId]);
        $deposits = $q_dep ? $q_dep->getResult() : [];

        $total_collected = 0.0;
        $total_discount = 0.0;
        $todays_collection = 0.0;
        $gateway_count = 0;

        $monthly_collection = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthly_collection[date('F', mktime(0, 0, 0, $i, 10))] = 0.0;
        }

        $payment_modes = [
            'Cash' => 0.0, 'Cheque' => 0.0, 'NetBanking' => 0.0, 
            'Upi' => 0.0, 'CreditCard' => 0.0, 'DebitCard' => 0.0, 
            'PaymentGateway' => 0.0, 'Other' => 0.0
        ];

        $class_wise = [];
        $today = date('Y-m-d');

        foreach ($deposits as $dep) {
            $class_name = (string)$dep->class_name;
            if (!isset($class_wise[$class_name])) {
                $class_wise[$class_name] = 0.0;
            }

            if (is_string($dep->amount_detail)) {
                $amounts = json_decode($dep->amount_detail);
                if (is_array($amounts)) {
                    foreach ($amounts as $amt) {
                        $total_collected += (float)$amt->amount;
                        $total_discount += (float)$amt->amount_discount;

                        if ($amt->date === $today) {
                            $todays_collection += (float)$amt->amount;
                        }

                        $month_name = date('F', strtotime($amt->date));
                        if (isset($monthly_collection[$month_name])) {
                            $monthly_collection[$month_name] += (float)$amt->amount;
                        } else {
                            $monthly_collection[$month_name] = (float)$amt->amount;
                        }

                        $mode = (string)($amt->payment_mode ?? '');
                        $mode_lower = strtolower($mode);

                        $matched = false;
                        foreach ($payment_modes as $key => $val) {
                            if (strtolower($key) === $mode_lower) {
                                $payment_modes[$key] += (float)$amt->amount;
                                $matched = true;
                                break;
                            }
                        }

                        if (!$matched) {
                            if ($mode_lower === 'online' || $mode_lower === 'paymentgateway') {
                                $payment_modes['PaymentGateway'] += (float)$amt->amount;
                                $gateway_count++;
                            } else {
                                $payment_modes['Other'] += (float)$amt->amount;
                            }
                        }

                        $class_wise[$class_name] += (float)$amt->amount;
                    }
                }
            }
        }

        $due_pending = $total_fees - ($total_collected + $total_discount);
        if ($due_pending < 0) {
            $due_pending = 0.0;
        }

        return [
            'total_fees'                 => $total_fees,
            'total_collected'            => $total_collected,
            'total_discount'             => $total_discount,
            'due_pending'                => $due_pending,
            'todays_collection'          => $todays_collection,
            'gateway_count'              => $gateway_count,
            'awaiting_discount_approval' => 0,
            'discount_pending_requests'  => 0,
            'monthly_collection'         => $monthly_collection,
            'payment_modes'              => $payment_modes,
            'class_wise'                 => $class_wise
        ];
    }
}
