<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Installmentplan_model extends MY_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function get($id = null)
    {
        $this->db->select('fee_installment_plans.*');
        $this->db->from('fee_installment_plans');
        $this->db->where('fee_installment_plans.session_id', $this->current_session);
        if ($id != null) {
            $this->db->where('fee_installment_plans.id', $id);
        } else {
            $this->db->order_by('fee_installment_plans.id', 'desc');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getPlanClasses($plan_id)
    {
        $this->db->select('class_id');
        $this->db->from('fee_installment_plan_classes');
        $this->db->where('installment_plan_id', $plan_id);
        $query = $this->db->get();
        $result = $query->result_array();
        $classes = array();
        foreach ($result as $row) {
            $classes[] = $row['class_id'];
        }
        return $classes;
    }

    public function getPlanItems($plan_id)
    {
        $this->db->select('*');
        $this->db->from('fee_installment_plan_items');
        $this->db->where('installment_plan_id', $plan_id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getPlanDetails($plan_id)
    {
        $this->db->select('*');
        $this->db->from('fee_installment_details');
        $this->db->where('installment_plan_id', $plan_id);
        $this->db->order_by('installment_number', 'asc');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function add($data)
    {
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);
        if (isset($data['id']) && $data['id'] != '') {
            $this->db->where('id', $data['id']);
            $this->db->update('fee_installment_plans', $data);
            $insert_id = $data['id'];
            $message   = UPDATE_RECORD_CONSTANT . " On fee installment plan id " . $insert_id;
            $action    = "Update";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);
        } else {
            $this->db->insert('fee_installment_plans', $data);
            $insert_id = $this->db->insert_id();
            $message   = INSERT_RECORD_CONSTANT . " On fee installment plan id " . $insert_id;
            $action    = "Insert";
            $record_id = $insert_id;
            $this->log($message, $record_id, $action);
        }
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            return $insert_id;
        }
    }

    public function remove($id)
    {
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);

        $this->db->where('id', $id);
        $this->db->delete('fee_installment_plans');

        $this->db->where('installment_plan_id', $id);
        $this->db->delete('fee_installment_plan_classes');

        $this->db->where('installment_plan_id', $id);
        $this->db->delete('fee_installment_plan_items');

        $this->db->where('installment_plan_id', $id);
        $this->db->delete('fee_installment_details');

        $message   = DELETE_RECORD_CONSTANT . " On fee installment plan id " . $id;
        $action    = "Delete";
        $record_id = $id;
        $this->log($message, $record_id, $action);

        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function saveClasses($plan_id, $classes)
    {
        $this->db->where('installment_plan_id', $plan_id);
        $this->db->delete('fee_installment_plan_classes');

        if (!empty($classes)) {
            $insert_data = array();
            foreach ($classes as $class_id) {
                $insert_data[] = array(
                    'installment_plan_id' => $plan_id,
                    'class_id' => $class_id
                );
            }
            $this->db->insert_batch('fee_installment_plan_classes', $insert_data);
        }
    }

    public function saveItems($plan_id, $items)
    {
        $this->db->where('installment_plan_id', $plan_id);
        $this->db->delete('fee_installment_plan_items');

        if (!empty($items)) {
            $insert_data = array();
            foreach ($items as $item) {
                $parts = explode('-', $item); // Format: type-id (e.g., fee_group-3, transport_yearly-5)
                if (count($parts) == 2) {
                    $insert_data[] = array(
                        'installment_plan_id' => $plan_id,
                        'fee_source_type' => $parts[0],
                        'fee_source_id' => $parts[1]
                    );
                }
            }
            if(!empty($insert_data)) {
                $this->db->insert_batch('fee_installment_plan_items', $insert_data);
            }
        }
    }

    public function saveDetails($plan_id, $details)
    {
        $this->db->where('installment_plan_id', $plan_id);
        $this->db->delete('fee_installment_details');

        if (!empty($details)) {
            $this->db->insert_batch('fee_installment_details', $details);
        }
    }

    public function calculate_student_installments($student_session_id)
    {
        // 1. Get student class
        $this->db->select('class_id');
        $this->db->where('id', $student_session_id);
        $student_session = $this->db->get('student_session')->row();
        if (!$student_session) return false;
        $class_id = $student_session->class_id;

        // 2. Find applicable plan
        $this->db->select('p.*');
        $this->db->from('fee_installment_plans p');
        $this->db->join('fee_installment_plan_classes c', 'c.installment_plan_id = p.id', 'left');
        $this->db->where('p.session_id', $this->current_session);
        $this->db->where('p.is_active', 'yes');
        $this->db->group_start();
        $this->db->where('p.is_global', 1);
        $this->db->or_where('c.class_id', $class_id);
        $this->db->group_end();
        $this->db->order_by('p.id', 'desc');
        $this->db->limit(1);
        $plan = $this->db->get()->row_array();
        
        if (!$plan) return false;

        $plan_id = $plan['id'];
        $details = $this->getPlanDetails($plan_id);
        $items = $this->getPlanItems($plan_id);

        $included_fee_groups = array();
        $included_transport = array();
        foreach ($items as $item) {
            if ($item['fee_source_type'] == 'fee_group') {
                $included_fee_groups[] = $item['fee_source_id'];
            } else if ($item['fee_source_type'] == 'transport_yearly') {
                $included_transport[] = $item['fee_source_id'];
            }
        }

        // Identify Hostel Fee Groups
        $hostel_fee_groups = array();
        $this->db->select('fee_groups_id');
        $hfg_result = $this->db->get('hostel_fee_groups')->result_array();
        foreach ($hfg_result as $row) {
            $hostel_fee_groups[] = $row['fee_groups_id'];
        }

        // 3. Fetch Assigned Fees and Totals
        $base_academic = 0;
        $base_transport = 0;
        $base_hostel = 0;
        $paid_academic = 0;
        $paid_transport = 0;
        $paid_hostel = 0;

        // Get regular fees (Academic & Hostel)
        $this->load->model('studentfeemaster_model');
        $fees = $this->studentfeemaster_model->getStudentFees($student_session_id);
        
        foreach ($fees as $fee) {
            if (in_array($fee->fee_groups_id, $included_fee_groups)) {
                $is_hostel = in_array($fee->fee_groups_id, $hostel_fee_groups);
                
                foreach ($fee->fees as $f) {
                    $amount = $f->amount;
                    if ($fee->is_system) { $amount = $fee->amount; }
                    
                    if ($is_hostel) {
                        $base_hostel += $amount;
                    } else {
                        $base_academic += $amount;
                    }

                    // Check payments
                    if (isset($f->amount_detail) && !empty($f->amount_detail)) {
                        $payments = json_decode($f->amount_detail, true);
                        foreach ($payments as $p) {
                            $paid_amount = $p['amount'] + $p['amount_discount'];
                            if ($is_hostel) {
                                $paid_hostel += $paid_amount;
                            } else {
                                $paid_academic += $paid_amount;
                            }
                        }
                    }
                }
            }
        }

        // Apply global student discounts (only to academic)
        $this->load->model('feediscount_model');
        $discounts = $this->feediscount_model->getStudentFeesDiscount($student_session_id);
        $total_discount_assigned = 0;
        foreach ($discounts as $discount) {
            if ($discount['status'] == 'assigned') {
                $total_discount_assigned += $discount['amount'];
            }
        }
        $base_academic = max(0, $base_academic - $total_discount_assigned);

        // Get Transport Yearly Fees
        $student = $this->db->get_where('student_session', array('id' => $student_session_id))->row();
        $route_pickup_point_id = $student->route_pickup_point_id;
        
        $this->db->select('student_transport_yearly_fees.*, transport_yearly_feemaster.amount, transport_yearly_feemaster.id as tyf_id, student_fees_deposite.amount_detail');
        $this->db->from('student_transport_yearly_fees');
        $this->db->join('transport_yearly_feemaster', 'transport_yearly_feemaster.id = student_transport_yearly_fees.transport_yearly_feemaster_id');
        $this->db->join('student_fees_deposite', 'student_fees_deposite.student_transport_yearly_fee_id = student_transport_yearly_fees.id', 'left');
        $this->db->where('student_transport_yearly_fees.student_session_id', $student_session_id);
        $transport_fees = $this->db->get()->result();

        foreach ($transport_fees as $tfee) {
            if (in_array($tfee->tyf_id, $included_transport)) {
                $base_transport += $tfee->amount;
                
                if (isset($tfee->amount_detail) && !empty($tfee->amount_detail)) {
                    $payments = json_decode($tfee->amount_detail, true);
                    foreach ($payments as $p) {
                        $paid_transport += ($p['amount'] + $p['amount_discount']);
                    }
                }
            }
        }

        // 4. Distribute into Installments
        $result = array();
        $total_overdue = 0;
        $current_date = date('Y-m-d');

        foreach ($details as $d) {
            $ac_due = $base_academic * ($d['academic_percentage'] / 100);
            $tr_due = $base_transport * ($d['transport_percentage'] / 100);
            $ho_due = $base_hostel * ($d['hostel_percentage'] / 100);

            // Pour payments sequentially
            $ac_paid = min($ac_due, $paid_academic);
            $paid_academic -= $ac_paid;
            $ac_bal = $ac_due - $ac_paid;

            $tr_paid = min($tr_due, $paid_transport);
            $paid_transport -= $tr_paid;
            $tr_bal = $tr_due - $tr_paid;

            $ho_paid = min($ho_due, $paid_hostel);
            $paid_hostel -= $ho_paid;
            $ho_bal = $ho_due - $ho_paid;

            $total_inst_bal = $ac_bal + $tr_bal + $ho_bal;
            
            $is_overdue = ($current_date > $d['due_date'] && $total_inst_bal > 0);
            if ($is_overdue) {
                $total_overdue += $total_inst_bal;
            }

            $result[] = array(
                'installment_number' => $d['installment_number'],
                'due_date' => $d['due_date'],
                'academic_due' => $ac_due,
                'academic_paid' => $ac_paid,
                'academic_balance' => $ac_bal,
                'transport_due' => $tr_due,
                'transport_paid' => $tr_paid,
                'transport_balance' => $tr_bal,
                'hostel_due' => $ho_due,
                'hostel_paid' => $ho_paid,
                'hostel_balance' => $ho_bal,
                'total_balance' => $total_inst_bal,
                'is_overdue' => $is_overdue
            );
        }

        return array(
            'plan_id' => $plan['id'],
            'is_global' => $plan['is_global'],
            'plan_name' => $plan['name'],
            'installments' => $result,
            'total_overdue' => $total_overdue
        );
    }
}
