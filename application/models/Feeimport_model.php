<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Feeimport_model extends CI_Model
{
    public $current_session;

    public function __construct()
    {
        parent::__construct();
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function createBatch($data)
    {
        $this->db->insert('fee_import_batches', $data);
        return $this->db->insert_id();
    }

    public function deleteBatch($batch_id)
    {
        $this->db->where('batch_id', $batch_id);
        $this->db->delete('fee_import_rows');
        $this->db->where('id', $batch_id);
        $this->db->delete('fee_import_batches');
    }

    public function getBatchHistory()
    {
        return $this->db->order_by('id', 'desc')->get('fee_import_batches')->result();
    }

    public function getBatch($batch_id)
    {
        return $this->db->where('id', $batch_id)->get('fee_import_batches')->row();
    }

    public function getBatchDetail($batch_id)
    {
        return $this->db->where('batch_id', $batch_id)->get('fee_import_rows')->result();
    }

    public function parseAndValidateCSV($file_path, $batch_id, $fallback_route_id = null)
    {
        if (($handle = fopen($file_path, "r")) !== FALSE) {
            $header = fgetcsv($handle);
            $expected_header = ['AdmissionNo', 'FeeCategory', 'PaymentDate(YYYY-MM-DD)', 'NetAmount', 'DiscountAmount', 'FineAmount', 'PaymentMode', 'ReferenceNo'];
            
            // Validate header
            $header_valid = true;
            foreach ($expected_header as $i => $h) {
                if (!isset($header[$i]) || trim($header[$i]) != $h) {
                    $header_valid = false;
                    break;
                }
            }
            
            if (!$header_valid) {
                fclose($handle);
                return ['status' => 'error', 'message' => 'Invalid CSV format. Please use the downloaded template.'];
            }

            $rows = [];
            $row_num = 1;
            while (($data = fgetcsv($handle)) !== FALSE) {
                $row_num++;
                if (empty($data[0]) && empty($data[1])) continue;

                $admission_no = trim($data[0]);
                $fee_category = trim($data[1]);
                $payment_date = trim($data[2]);
                $net_amount = floatval(trim($data[3]));
                $discount = floatval(trim($data[4]));
                $fine = floatval(trim($data[5]));
                $payment_mode = trim($data[6]);
                $reference_no = isset($data[7]) ? trim($data[7]) : '';
                
                // Parse date carefully (handle both d-m-Y and Y-m-d formats robustly)
                $payment_date_clean = str_replace('/', '-', $payment_date);
                $date_obj = DateTime::createFromFormat('d-m-Y', $payment_date_clean);
                if (!$date_obj) {
                    $date_obj = DateTime::createFromFormat('Y-m-d', $payment_date_clean);
                }
                
                if ($date_obj) {
                    $parsed_date = $date_obj->format('Y-m-d');
                } else {
                    $parsed_date = date('Y-m-d', strtotime($payment_date_clean));
                }
                
                if (!$parsed_date || $parsed_date == '1970-01-01') {
                    $row['status'] = 'failed';
                    $row['error_message'] = 'Invalid date format. Use DD-MM-YYYY or YYYY-MM-DD.';
                }

                $row = [
                    'batch_id' => $batch_id,
                    'row_number' => $row_num,
                    'csv_data' => json_encode($data),
                    'student_id' => null,
                    'student_session_id' => null,
                    'student_fees_master_id' => null,
                    'fee_groups_feetype_id' => null,
                    'student_transport_fee_id' => null,
                    'student_transport_yearly_fee_id' => null,
                    'amount' => $net_amount,
                    'discount' => $discount,
                    'fine' => $fine,
                    'net_amount' => $net_amount,
                    'original_receipt_no' => $reference_no,
                    'original_date' => $parsed_date,
                    'payment_mode' => $payment_mode,
                    'status' => 'pending',
                    'error_message' => ''
                ];

                // Validate Student
                $this->db->select('students.id, student_session.id as student_session_id');
                $this->db->from('students');
                $this->db->join('student_session', 'student_session.student_id = students.id');
                $this->db->where('students.admission_no', $admission_no);
                $this->db->where('student_session.session_id', $this->current_session);
                $student = $this->db->get()->row();

                if (!$student) {
                    $row['status'] = 'failed';
                    $row['error_message'] = "Student with Admission No $admission_no not found in current session.";
                } else {
                    $row['student_id'] = $student->id;
                    $row['student_session_id'] = $student->student_session_id;

                    // Validate and Map Fee Category
                    $is_transport = stripos($fee_category, 'transport') !== false;
                    $is_hostel = stripos($fee_category, 'hostel') !== false;
                    
                    if ($is_transport) {
                        // 1. Try to find existing Monthly Transport
                        $this->db->select('student_transport_fees.id');
                        $this->db->from('student_transport_fees');
                        $this->db->where('student_transport_fees.student_session_id', $student->student_session_id);
                        $t_fee_monthly = $this->db->get()->row();
                        
                        // 2. Try to find existing Yearly Transport
                        $this->db->select('id');
                        $this->db->where('student_session_id', $student->student_session_id);
                        $t_fee_yearly = $this->db->get('student_transport_yearly_fees')->row();

                        // 3. Resolve and Auto-Assign
                        if ($t_fee_monthly && !$t_fee_yearly) {
                            $row['student_transport_fee_id'] = $t_fee_monthly->id;
                            $row['status'] = 'matched';
                        } elseif ($t_fee_yearly) {
                            $row['student_transport_yearly_fee_id'] = $t_fee_yearly->id;
                            $row['status'] = 'matched';
                            
                            // Self-heal: If student_session is missing route_pickup_point_id, fix it now
                            $sess = $this->db->where('id', $student->student_session_id)->get('student_session')->row();
                            if ($sess && empty($sess->route_pickup_point_id)) {
                                $existing_master = $this->db->where('id', $t_fee_yearly->transport_yearly_feemaster_id)->get('transport_yearly_feemaster')->row();
                                if ($existing_master) {
                                    $rpp_query = !empty($existing_master->route_pickup_point_id) 
                                        ? $this->db->where('id', $existing_master->route_pickup_point_id)
                                        : $this->db->where('pickup_point_id', $existing_master->pickup_point_id);
                                    
                                    $route_point = $rpp_query->get('route_pickup_point')->row();
                                    
                                    if ($route_point) {
                                        $veh_route = $this->db->where('route_id', $route_point->transport_route_id)->get('vehicle_routes')->row();
                                        $this->db->where('id', $student->student_session_id);
                                        $this->db->update('student_session', [
                                            'route_pickup_point_id' => $route_point->id,
                                            'vehroute_id' => $veh_route ? $veh_route->id : null
                                        ]);
                                    }
                                }
                            }
                        } else {
                            // Student has NO transport assigned. Force auto-assign to Yearly (most common fallback)
                            if ($fallback_route_id) {
                                $this->db->where('route_pickup_point_id', $fallback_route_id);
                            }
                            $master = $this->db->get('transport_yearly_feemaster')->row();
                            
                            // Bulletproof fallback: if the selected route has no master, just pick ANY master to satisfy DB constraints
                            if (!$master) {
                                $master = $this->db->get('transport_yearly_feemaster')->row();
                            }
                            
                            if ($master) {
                                $this->db->insert('student_transport_yearly_fees', [
                                    'student_session_id' => $student->student_session_id,
                                    'transport_yearly_feemaster_id' => $master->id
                                ]);
                                $new_id = $this->db->insert_id();
                                $row['student_transport_yearly_fee_id'] = $new_id;
                                $row['status'] = 'matched';
                                
                                // CRUCIAL FIX: Update student_session so the fee actually renders in the UI
                                $rpp_query = !empty($master->route_pickup_point_id) 
                                    ? $this->db->where('id', $master->route_pickup_point_id)
                                    : $this->db->where('pickup_point_id', $master->pickup_point_id);
                                    
                                $route_point = $rpp_query->get('route_pickup_point')->row();
                                if ($route_point) {
                                    $veh_route = $this->db->where('route_id', $route_point->transport_route_id)->get('vehicle_routes')->row();
                                    $this->db->where('id', $student->student_session_id);
                                    $this->db->update('student_session', [
                                        'route_pickup_point_id' => $route_point->id,
                                        'vehroute_id' => $veh_route ? $veh_route->id : null
                                    ]);
                                }
                            } else {
                                $row['status'] = 'failed';
                                $row['error_message'] = "Could not auto-assign transport route. No valid transport master found.";
                            }
                        }
                    } else {
                        // Find academic fee by matching the type name
                        $sql = "SELECT sfm.id as student_fees_master_id, fgt.id as fee_groups_feetype_id 
                                FROM student_fees_master sfm 
                                JOIN fee_session_groups fsg ON fsg.id = sfm.fee_session_group_id 
                                JOIN fee_groups_feetype fgt ON fgt.fee_session_group_id = fsg.id 
                                JOIN feetype ft ON ft.id = fgt.feetype_id 
                                WHERE sfm.student_session_id = ? AND ft.type = ?";
                        $fee = $this->db->query($sql, [$student->student_session_id, $fee_category])->row();
                        
                        if ($fee) {
                            $row['student_fees_master_id'] = $fee->student_fees_master_id;
                            $row['fee_groups_feetype_id'] = $fee->fee_groups_feetype_id;
                            $row['status'] = 'matched';
                        } else {
                            $row['status'] = 'failed';
                            $row['error_message'] = "Fee type '$fee_category' not assigned to student.";
                        }
                    }
                }
                
                $rows[] = $row;
            }
            fclose($handle);

            if (!empty($rows)) {
                $this->db->insert_batch('fee_import_rows', $rows);
            }

            return ['status' => 'success'];
        }
        return ['status' => 'error', 'message' => 'Could not read the uploaded file.'];
    }

    public function executeBatch($batch_id)
    {
        $this->db->trans_start();
        $this->db->trans_strict(TRUE);
        
        $rows = $this->db->where('batch_id', $batch_id)
                         ->where('status', 'matched')
                         ->get('fee_import_rows')->result();
        
        $success_count = 0;
        
        // Get custom receipt settings for import prefix if needed, though we will just hardcode "IMP-" logic
        $settings = $this->db->get('custom_receipt_settings')->row();
        
        foreach ($rows as $row) {
            $date = $row->original_date;
            
            $fee_data = array(
                'amount_detail' => array(
                    'amount' => number_format((float)$row->amount, 2, '.', ''),
                    'date' => $date,
                    'description' => $row->original_receipt_no ? "Imported Ref: " . $row->original_receipt_no : "Historical Import",
                    'amount_discount' => number_format((float)$row->discount, 2, '.', ''),
                    'amount_fine' => number_format((float)$row->fine, 2, '.', ''),
                    'payment_mode' => $row->payment_mode,
                    'received_by' => $this->customlib->getStaffID(),
                    'inv_no' => 1
                ),
            );

            if ($row->student_transport_fee_id) {
                $fee_data['student_transport_fee_id'] = $row->student_transport_fee_id;
                $fee_category = 'transport';
                $this->db->where('student_transport_fee_id', $row->student_transport_fee_id);
                $this->db->where('student_transport_yearly_fee_id', null);
            } elseif ($row->student_transport_yearly_fee_id) {
                $fee_data['student_transport_yearly_fee_id'] = $row->student_transport_yearly_fee_id;
                $fee_category = 'transport';
                $this->db->where('student_fees_master_id', null);
                $this->db->where('student_transport_fee_id', null);
                $this->db->where('student_transport_yearly_fee_id', $row->student_transport_yearly_fee_id);
            } else {
                $fee_data['student_fees_master_id'] = $row->student_fees_master_id;
                $fee_data['fee_groups_feetype_id'] = $row->fee_groups_feetype_id;
                $fee_category = 'fees';
                $this->db->where('student_fees_master_id', $row->student_fees_master_id);
                $this->db->where('fee_groups_feetype_id', $row->fee_groups_feetype_id);
            }

            $q = $this->db->get('student_fees_deposite');
            
            $deposite_id = null;
            $sub_invoice_id = 1;
            
            if ($q->num_rows() > 0) {
                $existing = $q->row();
                $deposite_id = $existing->id;
                $a = json_decode($existing->amount_detail, true);
                $sub_invoice_id = max(array_keys($a)) + 1;
                $fee_data['amount_detail']['inv_no'] = $sub_invoice_id;
                $a[$sub_invoice_id] = $fee_data['amount_detail'];
                
                $update_data = ['amount_detail' => json_encode($a)];
                $this->db->where('id', $deposite_id);
                $this->db->update('student_fees_deposite', $update_data);
            } else {
                $fee_data['amount_detail'] = json_encode(array('1' => $fee_data['amount_detail']));
                $this->db->insert('student_fees_deposite', $fee_data);
                $deposite_id = $this->db->insert_id();
            }

            // Create custom receipt log with IMP prefix (does not touch live counter)
            // Use the original receipt number from CSV if provided, else auto-generate
            if (!empty($row->original_receipt_no)) {
                $receipt_no = 'IMP-' . $row->original_receipt_no;
            } else {
                $receipt_no = 'IMP-' . str_pad($deposite_id . $sub_invoice_id, 5, '0', STR_PAD_LEFT);
            }
            
            $log_data = array(
                'student_fees_deposite_id' => $deposite_id,
                'sub_invoice_id' => $sub_invoice_id,
                'receipt_no' => $receipt_no,
                'fee_type' => ($fee_category == 'transport') ? 'transport' : 'common',
                'status' => 'Collected',
                'created_at' => $date . ' 12:00:00'
            );
            $this->db->insert('custom_receipt_logs', $log_data);
            $receipt_log_id = $this->db->insert_id();

            // Accounts Integration - VERY IMPORTANT to pass original date
            // We use the normal sync, but we would need Accounts_integration to use the deposit's amount_detail date.
            // Accounts_integration::sync_fee() reads amount_detail date automatically! (See Accounts_integration.php Line 245)
            if (file_exists(APPPATH . 'libraries/Accounts_integration.php')) {
                $this->load->library('accounts_integration');
                $this->accounts_integration->sync_fee($deposite_id);
            }

            // Update row status
            $this->db->where('id', $row->id);
            $this->db->update('fee_import_rows', [
                'status' => 'imported',
                'student_fees_deposite_id' => $deposite_id,
                'sub_invoice_id' => $sub_invoice_id,
                'custom_receipt_log_id' => $receipt_log_id
            ]);
            
            $success_count++;
        }

        $this->db->where('id', $batch_id);
        $this->db->update('fee_import_batches', [
            'status' => 'imported',
            'success_rows' => $success_count,
            'imported_by' => $this->customlib->getStaffID(),
            'imported_at' => date('Y-m-d H:i:s')
        ]);

        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return ['status' => 'error', 'message' => 'Database transaction failed during execution.'];
        } else {
            return ['status' => 'success', 'count' => $success_count];
        }
    }

    public function revertBatch($batch_id)
    {
        $this->db->trans_start();
        $this->db->trans_strict(TRUE);
        
        // Process in reverse order to unwind correctly
        $rows = $this->db->where('batch_id', $batch_id)
                         ->where('status', 'imported')
                         ->order_by('id', 'desc')
                         ->get('fee_import_rows')->result();
                         
        if (empty($rows)) {
            $this->db->trans_rollback();
            return ['status' => 'error', 'message' => 'No imported rows found to revert.'];
        }
        
        foreach ($rows as $row) {
            $deposite_id = $row->student_fees_deposite_id;
            $sub_invoice_id = $row->sub_invoice_id;
            
            if (!$deposite_id || !$sub_invoice_id) continue;

            // 1. Reverse Accounts Integration - HARD DELETE to leave zero trace in cashbook
            if (file_exists(APPPATH . 'libraries/Accounts_integration.php')) {
                $ref_id = $deposite_id . '_' . $sub_invoice_id;
                $ref_id_disc = $ref_id . '_disc';
                
                $vouchers = $this->db->where_in('reference_module', ['fee_collection', 'fee_discount'])
                                     ->group_start()
                                         ->where('reference_id', $ref_id)
                                         ->or_where('reference_id', $ref_id . '_rev')
                                         ->or_where('reference_id', $ref_id_disc)
                                         ->or_where('reference_id', $ref_id_disc . '_rev')
                                     ->group_end()
                                     ->get('acc_vouchers')->result();
                                     
                foreach ($vouchers as $v) {
                    $this->db->where('voucher_id', $v->id)->delete('acc_voucher_items');
                    $this->db->where('id', $v->id)->delete('acc_vouchers');
                }
            }

            // 2. Remove Receipt Log
            if ($row->custom_receipt_log_id) {
                $this->db->where('id', $row->custom_receipt_log_id);
                $this->db->delete('custom_receipt_logs');
            }

            // 3. Update/Delete Deposit
            $this->db->where('id', $deposite_id);
            $q = $this->db->get('student_fees_deposite');
            if ($q->num_rows() > 0) {
                $result = $q->row();
                $a = json_decode($result->amount_detail, true);
                if (isset($a[$sub_invoice_id])) {
                    unset($a[$sub_invoice_id]);
                    if (!empty($a)) {
                        $this->db->where('id', $deposite_id);
                        $this->db->update('student_fees_deposite', ['amount_detail' => json_encode($a)]);
                    } else {
                        $this->db->where('id', $deposite_id);
                        $this->db->delete('student_fees_deposite');
                    }
                }
            }

            // Update row status
            $this->db->where('id', $row->id);
            $this->db->update('fee_import_rows', ['status' => 'reverted']);
        }

        $this->db->where('id', $batch_id);
        $this->db->update('fee_import_batches', [
            'status' => 'reverted',
            'reverted_by' => $this->customlib->getStaffID(),
            'reverted_at' => date('Y-m-d H:i:s')
        ]);

        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return ['status' => 'error', 'message' => 'Database transaction failed during revert.'];
        } else {
            return ['status' => 'success'];
        }
    }

    public function fixBatchDates($batch_id)
    {
        $this->db->trans_start();
        $this->db->trans_strict(TRUE);

        $rows = $this->db->where('batch_id', $batch_id)->get('fee_import_rows')->result();
        $fix_count = 0;

        foreach ($rows as $row) {
            $csv_data = json_decode($row->csv_data, true);
            if (empty($csv_data)) continue;

            $raw_date = isset($csv_data[2]) ? trim($csv_data[2]) : '';
            if (empty($raw_date)) continue;

            $payment_date_clean = str_replace('/', '-', $raw_date);
            $date_obj = DateTime::createFromFormat('d-m-Y', $payment_date_clean);
            if (!$date_obj) {
                $date_obj = DateTime::createFromFormat('Y-m-d', $payment_date_clean);
            }

            if (!$date_obj) continue;

            $correct_date = $date_obj->format('Y-m-d');
            $wrong_date_stored = $row->original_date;

            if ($correct_date === $wrong_date_stored) continue;

            // 1. Update fee_import_rows
            $this->db->where('id', $row->id)->update('fee_import_rows', ['original_date' => $correct_date]);

            // 2. Update student_fees_deposite
            $deposite_id = $row->student_fees_deposite_id;
            $sub_invoice_id = $row->sub_invoice_id;

            if ($deposite_id && $sub_invoice_id) {
                $deposit = $this->db->where('id', $deposite_id)->get('student_fees_deposite')->row();
                if ($deposit) {
                    $amount_detail = json_decode($deposit->amount_detail, true);
                    if (isset($amount_detail[$sub_invoice_id])) {
                        $amount_detail[$sub_invoice_id]['date'] = $correct_date;
                        if (isset($amount_detail[$sub_invoice_id]['cheque_date'])) {
                            $amount_detail[$sub_invoice_id]['cheque_date'] = $correct_date;
                        }
                        $this->db->where('id', $deposite_id)->update('student_fees_deposite', [
                            'amount_detail' => json_encode($amount_detail)
                        ]);
                    }
                }

                // 3. Update custom_receipt_logs
                if ($row->custom_receipt_log_id) {
                    $this->db->where('id', $row->custom_receipt_log_id)->update('custom_receipt_logs', [
                        'created_at' => $correct_date . ' 12:00:00'
                    ]);
                }

                // 4. Update Accounting Vouchers (acc_vouchers)
                $ref_id = $deposite_id . '_' . $sub_invoice_id;
                
                $vouchers = $this->db->where_in('reference_module', ['fee_collection', 'fee_discount'])
                                     ->group_start()
                                         ->where('reference_id', $ref_id)
                                         ->or_where('reference_id', $ref_id . '_disc')
                                     ->group_end()
                                     ->get('acc_vouchers')
                                     ->result();

                foreach ($vouchers as $voucher) {
                    $this->db->where('id', $voucher->id)->update('acc_vouchers', [
                        'voucher_date' => $correct_date,
                        'cheque_date' => ($voucher->cheque_date) ? $correct_date : null
                    ]);
                }
            }
            $fix_count++;
        }

        $this->db->trans_complete();
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return ['status' => 'error', 'message' => 'Database transaction failed during execution.'];
        } else {
            return ['status' => 'success', 'count' => $fix_count];
        }
    }
}
