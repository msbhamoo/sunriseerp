<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Feediscount extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function delete($id)
    {
        $this->feediscount_model->remove($id);
        redirect('admin/feediscount/index');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('fees_discount', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feediscount');
        $feesdiscount_result     = $this->feediscount_model->get();
        $data['feediscountList'] = $feesdiscount_result;
        $this->form_validation->set_rules('code', $this->lang->line('discount_code'), 'trim|required|xss_clean');
        
        $this->form_validation->set_rules('discount_limit', $this->lang->line('number_of_use_count'), 'trim|required|xss_clean|callback_check_number');       
        
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        if ($this->input->post('account_type') == "percentage") {
            $this->form_validation->set_rules('percentage', $this->lang->line('percentage'), 'trim|required|xss_clean');
        } else {
            $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required|xss_clean');
        }

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/feediscount/feediscountList', $data);
            $this->load->view('layout/footer', $data);
        } else {
            
            if ($this->input->post('account_type') == "percentage") {
                $amount =  '0.00' ;
            } else {
                $amount =   convertCurrencyFormatToBaseAmount($this->input->post('amount'));
            }
			
            $data = array(
                'name'        => $this->input->post('name'),
                'code'        => $this->input->post('code'),
                'type'        => $this->input->post('account_type'),
                'amount'      => $amount,
                'percentage'  => empty2null($this->input->post('percentage')),
                'description' => $this->input->post('description'),
                'discount_limit' => $this->input->post('discount_limit'),
                'expire_date' => $this->customlib->dateFormatToYYYYMMDD($this->input->post('expire_date')),
            );

            $this->feediscount_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/feediscount');
        }
    }

    public function check_number($userName) {
        if($userName != "")
        {
            if (preg_match("/^[1-9][0-9]*$/", $userName ) ) 
            {
                return TRUE;
            } else{
                $this->form_validation->set_message('check_number', 'The {field} must be greater than 0');
                return FALSE;    
            }            
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('fees_discount', 'can_edit')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feediscount');
        $feesdiscount_result     = $this->feediscount_model->get();
        $data['feediscountList'] = $feesdiscount_result;    
        $data['id'] = $id;
        $feediscount         = $this->feediscount_model->get($id);
        $data['feediscount'] = $feediscount;
        $this->form_validation->set_rules('code', $this->lang->line('discount_code'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('name', $this->lang->line('name'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('discount_limit', $this->lang->line('number_of_use_count'), 'trim|required|xss_clean|callback_check_number');
        
        if ($this->input->post('account_type') == "percentage") {
            $this->form_validation->set_rules('percentage', $this->lang->line('percentage'), 'trim|required|xss_clean');
        } else {
            $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required|xss_clean');
        }

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/feediscount/feediscountEdit', $data);
            $this->load->view('layout/footer', $data);
        } else {           
           
            if ($this->input->post('account_type') == "percentage") {
                $amount =  '0.00' ;
            } else {
                $amount =   convertCurrencyFormatToBaseAmount($this->input->post('amount'));
            }
     
            $data = array(
                'id'          => $id,
                'name'        => $this->input->post('name'),
                'code'        => $this->input->post('code'),
                'type'        => $this->input->post('account_type'),
                'amount'      => $amount,
                'percentage'  => empty2null($this->input->post('percentage')),
                'description' => $this->input->post('description'),
                'discount_limit' => $this->input->post('discount_limit'),
                'expire_date' => $this->customlib->dateFormatToYYYYMMDD($this->input->post('expire_date')),
            );

            $this->feediscount_model->add($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">' . $this->lang->line('update_message') . '</div>');
            redirect('admin/feediscount/index');
        }
    }

    public function assign($id)
    {
        if (!$this->rbac->hasPrivilege('fees_discount_assign', 'can_view')) {
            access_denied();
        }
        $userdata = $this->customlib->getUserData();
if (($userdata["role_id"] == 2) && ($userdata["class_teacher"] == "yes")) {
    $class_section_array = $this->customlib->get_myClassSection();
    if (empty($class_section_array)) {
        access_denied();
    }
}
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feediscount');
        $data['id'] = $id;

        $class                   = $this->class_model->get();
        $data['classlist']       = $class;
        $feediscount_result      = $this->feediscount_model->get($id);
        $data['feediscountList'] = $feediscount_result;
        $genderList            = $this->customlib->getGender();
        $data['genderList']    = $genderList;
        $RTEstatusList         = $this->customlib->getRteStatus();
        $data['RTEstatusList'] = $RTEstatusList;
        $category              = $this->category_model->get();
        $data['categorylist']  = $category;

        if ($this->input->server('REQUEST_METHOD') == 'POST') {

            $data['category_id'] = $this->input->post('category_id');
            $data['gender']      = $this->input->post('gender');
            $data['rte_status']  = $this->input->post('rte');
            $data['class_id']    = $this->input->post('class_id');
            $data['section_id']  = $this->input->post('section_id');
            $resultlist          = $this->feediscount_model->searchAssignFeeByClassSection($data['class_id'], $data['section_id'], $id, $data['category_id'], $data['gender'], $data['rte_status']);
            $data['resultlist']  = $resultlist;
        }
        $data['sch_setting'] = $this->sch_setting_detail;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/feediscount/assign', $data);
        $this->load->view('layout/footer', $data);
    }

    public function studentdiscount()
    {
        if (!$this->rbac->hasPrivilege('fees_discount_assign', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feediscount');
        $this->form_validation->set_rules('feediscount_id', $this->lang->line('fee_discount'), 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'feediscount_id' => form_error('feediscount_id'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {

            $student_list           = $this->input->post('student_list');
            $feediscount_id         = $this->input->post('feediscount_id');
            $student_sesssion_array = $this->input->post('student_session_id');
            if (!isset($student_sesssion_array)) {
                $student_sesssion_array = array();
            }
            $diff_aray       = array_diff($student_list, $student_sesssion_array);
            $preserve_record = array();
            foreach ($student_sesssion_array as $key => $value) {

                $insert_array = array(
                    'student_session_id' => $value,
                    'fees_discount_id'   => $feediscount_id,
                );
                $inserted_id = $this->feediscount_model->allotdiscount($insert_array);
                $preserve_record[] = $inserted_id;
            }
            if (!empty($diff_aray)) {
                $this->feediscount_model->deletedisstd($feediscount_id, $diff_aray);
            }

            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('success_message'));
            echo json_encode($array);
        }
    }

    public function applydiscount()
    {
        if (!$this->rbac->hasPrivilege('fees_discount_assign', 'can_view')) {
            access_denied();
        }
        $this->form_validation->set_rules('discount_payment_id', $this->lang->line('fees_payment_id'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('student_fees_discount_id', $this->lang->line('fees_discount_id'), 'required|trim|xss_clean');

        if ($this->form_validation->run() == false) {
            $data = array(
                'amount'              => form_error('amount'),
                'discount_payment_id' => form_error('discount_payment_id'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {

            $data = array(
                'id'          => $this->input->post('student_fees_discount_id'),
                'payment_id'  => $this->input->post('discount_payment_id'),
                'description' => $this->input->post('dis_description'),
                'status'      => 'applied',
            );

            $this->feediscount_model->updateStudentDiscount($data);
            $array = array('status' => 'success', 'error' => '');
            echo json_encode($array);
        }
    }

    public function approvalQueue()
    {
        if (!$this->rbac->hasPrivilege('fee_discount_approval', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/feediscount/approvalQueue');
        
        $this->load->model('feediscountrequest_model');
        $data['requests'] = $this->feediscountrequest_model->getAll();
        
        $this->load->view('layout/header', $data);
        $this->load->view('admin/feediscount/approvalQueue', $data);
        $this->load->view('layout/footer', $data);
    }

    public function approveRequest($id)
    {
        if (!$this->rbac->hasPrivilege('fee_discount_approval', 'can_edit')) {
            access_denied();
        }
        
        $this->load->model('feediscountrequest_model');
        $request = $this->feediscountrequest_model->get($id);
        
        if ($request && ($request['status'] == 'pending' || $request['status'] == 'provisional')) {
            $admin_id = $this->customlib->getStaffID();
            $this->feediscountrequest_model->updateStatus($id, 'approved', $admin_id);
            
            // Create the dynamic discount record
            $student_fees_discount_id = $this->feediscount_model->createDynamicDiscount($request);
            
            if ($request['status'] == 'provisional' && $request['student_fees_deposite_id'] && $student_fees_discount_id) {
                // Insert into student_applied_discounts since it was applied during collection
                $applied_data = array(
                    'student_fees_deposite_id' => $request['student_fees_deposite_id'],
                    'student_fees_discount_id' => $student_fees_discount_id,
                    'invoice_id' => $request['student_fees_deposite_id'],
                    'sub_invoice_id' => $request['sub_invoice_id'],
                    'date' => date('Y-m-d')
                );
                $this->db->insert('student_applied_discounts', $applied_data);
            }
            
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Request Approved Successfully</div>');
        }
        redirect('admin/feediscount/approvalQueue');
    }

    public function rejectRequest()
    {
        if (!$this->rbac->hasPrivilege('fee_discount_approval', 'can_edit')) {
            access_denied();
        }
        
        $id = $this->input->post('request_id');
        $remark = $this->input->post('admin_remark');
        
        $this->load->model('feediscountrequest_model');
        $request = $this->feediscountrequest_model->get($id);
        
        if ($request && ($request['status'] == 'pending' || $request['status'] == 'provisional')) {
            $admin_id = $this->customlib->getStaffID();
            $this->feediscountrequest_model->updateStatus($id, 'rejected', $admin_id, $remark);
            
            if ($request['status'] == 'provisional' && $request['student_fees_deposite_id']) {
                $this->studentfeemaster_model->reverseProvisionalDiscount($request['student_fees_deposite_id'], $request['sub_invoice_id']);
            }
            
            $this->session->set_flashdata('msg', '<div class="alert alert-success">Request Rejected Successfully</div>');
        }
        redirect('admin/feediscount/approvalQueue');
    }

    public function ajax_search_students()
    {
        if (!$this->rbac->hasPrivilege('fees_discount', 'can_view') && !$this->rbac->hasPrivilege('fee_discount_approval', 'can_view')) {
            echo json_encode(array('status' => 'fail', 'message' => 'Access Denied', 'data' => array()));
            return;
        }

        $search_text = $this->input->post('search_text');
        if (!isset($search_text) || strlen(trim($search_text)) < 2) {
            echo json_encode(array('status' => 'success', 'data' => array()));
            return;
        }

        $search_text = trim($search_text);
        $this->load->model('student_model');
        $this->load->library('media_storage');
        $resultlist = $this->student_model->searchusersbyFullText($search_text, null, 15);
        
        $data = array();
        $sch_setting = $this->sch_setting_detail;
        
        foreach ($resultlist as $student) {
            $image_path = "uploads/student_images/no_image.png";
            if (!empty($student['image'])) {
                if (strpos($student['image'], 'uploads/student_images/') !== false) {
                    $image_path = $student['image'];
                } else {
                    $image_path = "uploads/student_images/" . $student['image'];
                }
            }
            
            $data[] = array(
                'id' => $student['id'],
                'student_session_id' => $student['student_session_id'],
                'admission_no' => $student['admission_no'],
                'full_name' => $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname),
                'father_name' => $student['father_name'] ? $student['father_name'] : 'N/A',
                'mother_name' => isset($student['mother_name']) && $student['mother_name'] ? $student['mother_name'] : 'N/A',
                'class' => $student['class'],
                'section' => $student['section'],
                'image' => base_url($image_path)
            );
        }

        echo json_encode(array('status' => 'success', 'data' => $data));
    }

    public function get_student_fee_summary()
    {
        if (!$this->rbac->hasPrivilege('fees_discount', 'can_view') && !$this->rbac->hasPrivilege('fee_discount_approval', 'can_view')) {
            echo json_encode(array('status' => 'fail', 'message' => 'Access Denied'));
            return;
        }

        $student_session_id = $this->input->post('student_session_id');
        if (empty($student_session_id)) {
            echo json_encode(array('status' => 'fail', 'message' => 'Invalid Student Session ID'));
            return;
        }

        $this->load->model('student_model');
        $this->load->model('studentfeemaster_model');
        $this->load->model('module_model');
        $this->load->library('media_storage');

        $student = $this->student_model->getByStudentSession($student_session_id);
        if (empty($student)) {
            echo json_encode(array('status' => 'fail', 'message' => 'Student record not found'));
            return;
        }

        $student_due_fee = $this->studentfeemaster_model->getStudentFees($student_session_id);
        
        $transport_fees = array();
        $module = $this->module_model->getPermissionByModulename('transport');
        if ($module['is_active'] && !empty($student['route_pickup_point_id'])) {
            $transport_fees = $this->studentfeemaster_model->getStudentTransportFees($student_session_id, $student['route_pickup_point_id']);
        }

        $student_discount_fee = $this->feediscount_model->getStudentFeesDiscount($student_session_id);

        $hdr_total_fee = 0;
        $hdr_total_paid = 0;
        $hdr_total_discount = 0;
        $hdr_total_fine = 0;
        $hdr_total_balance = 0;
        $fee_breakdown = array();

        if (!empty($student_due_fee)) {
            foreach ($student_due_fee as $fee) {
                if (!empty($fee->fees)) {
                    foreach ($fee->fees as $fee_value) {
                        $fee_paid = 0;
                        $fee_discount = 0;
                        $fee_fine = 0;

                        if (!empty($fee_value->amount_detail)) {
                            $fee_deposits = json_decode($fee_value->amount_detail);
                            if (is_array($fee_deposits) || is_object($fee_deposits)) {
                                foreach ($fee_deposits as $fee_deposits_value) {
                                    $fee_paid += $fee_deposits_value->amount;
                                    $fee_discount += $fee_deposits_value->amount_discount;
                                    $fee_fine += $fee_deposits_value->amount_fine;
                                }
                            }
                        }

                        $feetype_balance = $fee_value->amount - ($fee_paid + $fee_discount);
                        if ($feetype_balance < 0) {
                            $feetype_balance = 0;
                        }

                        $hdr_total_fee += $fee_value->amount;
                        $hdr_total_paid += $fee_paid;
                        $hdr_total_discount += $fee_discount;
                        $hdr_total_fine += $fee_fine;
                        $hdr_total_balance += $feetype_balance;

                        $fee_name = ($fee_value->is_system) ? $this->lang->line($fee_value->name) . " (" . $this->lang->line($fee_value->type) . ")" : $fee_value->name . " (" . $fee_value->type . ")";
                        $fee_breakdown[] = array(
                            'type' => 'tuition',
                            'name' => $fee_name,
                            'due_date' => $fee_value->due_date ? date($this->customlib->getSchoolDateFormat(), strtotime($fee_value->due_date)) : 'N/A',
                            'amount' => $fee_value->amount,
                            'paid' => $fee_paid,
                            'discount' => $fee_discount,
                            'balance' => $feetype_balance,
                        );
                    }
                }
            }
        }

        if (!empty($transport_fees)) {
            foreach ($transport_fees as $transport_fee_value) {
                $fee_paid = 0;
                $fee_discount = 0;
                $fee_fine = 0;

                if (!empty($transport_fee_value->amount_detail)) {
                    $fee_deposits = json_decode($transport_fee_value->amount_detail);
                    if (is_array($fee_deposits) || is_object($fee_deposits)) {
                        foreach ($fee_deposits as $fee_deposits_value) {
                            $fee_paid += $fee_deposits_value->amount;
                            $fee_discount += $fee_deposits_value->amount_discount;
                            $fee_fine += $fee_deposits_value->amount_fine;
                        }
                    }
                }

                $feetype_balance = $transport_fee_value->fees - ($fee_paid + $fee_discount);
                if ($feetype_balance < 0) {
                    $feetype_balance = 0;
                }

                $hdr_total_fee += $transport_fee_value->fees;
                $hdr_total_paid += $fee_paid;
                $hdr_total_discount += $fee_discount;
                $hdr_total_fine += $fee_fine;
                $hdr_total_balance += $feetype_balance;

                $tr_name = isset($transport_fee_value->fee_category) && $transport_fee_value->fee_category == 'transport_yearly' ? $this->lang->line('transport_fees') . " (Yearly)" : $this->lang->line('transport_fees') . " (" . $this->lang->line(strtolower($transport_fee_value->month)) . ")";
                $fee_breakdown[] = array(
                    'type' => 'transport',
                    'name' => $tr_name,
                    'due_date' => $transport_fee_value->due_date ? date($this->customlib->getSchoolDateFormat(), strtotime($transport_fee_value->due_date)) : 'N/A',
                    'amount' => $transport_fee_value->fees,
                    'paid' => $fee_paid,
                    'discount' => $fee_discount,
                    'balance' => $feetype_balance,
                );
            }
        }

        $image_path = "uploads/student_images/no_image.png";
        if (!empty($student['image'])) {
            if (strpos($student['image'], 'uploads/student_images/') !== false) {
                $image_path = $student['image'];
            } else {
                $image_path = "uploads/student_images/" . $student['image'];
            }
        }

        $assigned_discounts = array();
        if (!empty($student_discount_fee)) {
            foreach ($student_discount_fee as $dis) {
                $assigned_discounts[] = array(
                    'id' => $dis['id'],
                    'name' => $dis['name'],
                    'code' => $dis['code'],
                    'type' => $dis['type'],
                    'amount' => $dis['amount'],
                    'percentage' => $dis['percentage'],
                    'status' => $dis['status']
                );
            }
        }

        $sch_setting = $this->sch_setting_detail;
        $currency_symbol = $this->customlib->getSchoolCurrencyFormat();

        $response = array(
            'status' => 'success',
            'currency_symbol' => $currency_symbol,
            'student' => array(
                'id' => $student['id'],
                'student_session_id' => $student['student_session_id'],
                'full_name' => $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname),
                'admission_no' => $student['admission_no'],
                'class' => $student['class'],
                'section' => $student['section'],
                'father_name' => $student['father_name'] ? $student['father_name'] : 'N/A',
                'mobileno' => $student['mobileno'] ? $student['mobileno'] : 'N/A',
                'image' => base_url($image_path)
            ),
            'summary' => array(
                'total_fee' => $hdr_total_fee,
                'total_paid' => $hdr_total_paid,
                'total_discount' => $hdr_total_discount,
                'total_fine' => $hdr_total_fine,
                'total_balance' => $hdr_total_balance
            ),
            'breakdown' => $fee_breakdown,
            'assigned_discounts' => $assigned_discounts
        );

        echo json_encode($response);
    }

    public function apply_direct_discount()
    {
        if (!$this->rbac->hasPrivilege('fee_discount_approval', 'can_edit') && !$this->rbac->hasPrivilege('fees_discount_assign', 'can_view')) {
            echo json_encode(array('status' => 'fail', 'message' => 'Access Denied'));
            return;
        }

        $this->form_validation->set_rules('student_session_id', $this->lang->line('student'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('discount_type', $this->lang->line('discount_type'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('reason', $this->lang->line('reason'), 'required|trim|xss_clean');

        $discount_type = $this->input->post('discount_type');
        if ($discount_type == 'fix') {
            $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'required|numeric|greater_than[0]|trim|xss_clean');
        } else {
            $this->form_validation->set_rules('percentage', $this->lang->line('percentage'), 'required|numeric|greater_than[0]|less_than_equal_to[100]|trim|xss_clean');
        }

        if ($this->form_validation->run() == false) {
            $errors = array();
            if (form_error('student_session_id')) $errors['student_session_id'] = form_error('student_session_id');
            if (form_error('discount_type')) $errors['discount_type'] = form_error('discount_type');
            if (form_error('reason')) $errors['reason'] = form_error('reason');
            if (form_error('amount')) $errors['amount'] = form_error('amount');
            if (form_error('percentage')) $errors['percentage'] = form_error('percentage');

            echo json_encode(array('status' => 'fail', 'error' => $errors));
            return;
        }

        $student_session_id = $this->input->post('student_session_id');
        $reason = trim($this->input->post('reason'));
        $amount = $discount_type == 'fix' ? convertCurrencyFormatToBaseAmount($this->input->post('amount')) : 0.00;
        $percentage = $discount_type == 'percentage' ? $this->input->post('percentage') : null;

        $staff_id = $this->customlib->getStaffID();
        $staff_record = $this->staff_model->get($staff_id);
        $collected_by = $this->customlib->getAdminSessionUserName() . "(" . ($staff_record['employee_id'] ?? '') . ")";

        $this->db->trans_start();

        // 1. Log in fee_discount_requests as approved direct allotment
        $this->load->model('feediscountrequest_model');
        $this->load->model('studentfeemaster_model');
        $request_data = array(
            'student_session_id' => $student_session_id,
            'requested_by' => $staff_id,
            'approved_by' => $staff_id,
            'discount_type' => $discount_type,
            'amount' => $amount,
            'percentage' => $percentage,
            'reason' => $reason,
            'status' => 'approved',
            'approved_at' => date('Y-m-d H:i:s'),
            'admin_remark' => 'Direct discount applied from Approval Queue'
        );
        $request_id = $this->feediscountrequest_model->create($request_data);

        // 2. Create dynamic discount in fees_discounts & assign to student_fees_discounts
        $session_id = $this->setting_model->getCurrentSession();
        $session_data = $this->session_model->get($session_id);
        
        $expire_date = null;
        if (!empty($session_data) && isset($session_data['end_date'])) {
            $expire_date = date('Y-m-d', strtotime($session_data['end_date']));
        }

        $discount_data = array(
            'session_id' => $session_id,
            'name' => 'Dynamic: ' . (strlen($reason) > 20 ? substr($reason, 0, 17) . '...' : $reason),
            'code' => 'DYN-' . $request_id,
            'type' => $discount_type,
            'amount' => $amount,
            'percentage' => $percentage,
            'description' => $reason,
            'discount_limit' => 1,
            'expire_date' => $expire_date,
            'is_active' => 'no'
        );

        $this->db->insert('fees_discounts', $discount_data);
        $fees_discount_id = $this->db->insert_id();

        $student_discount_data = array(
            'student_session_id' => $student_session_id,
            'fees_discount_id' => $fees_discount_id,
            'status' => 'applied',
            'payment_id' => 'DISC-' . $request_id,
            'description' => $reason
        );
        $this->db->insert('student_fees_discounts', $student_discount_data);
        $student_fees_discount_id = $this->db->insert_id();

        // 3. Auto-apply the discount against the student's unpaid fee heads so fee balance is cleared immediately without collecting fee
        $student_due_fee = $this->studentfeemaster_model->getStudentFees($student_session_id);
        
        // Calculate total discount to distribute
        $total_discount_to_apply = 0;
        if ($discount_type == 'fix') {
            $total_discount_to_apply = $amount;
        } else {
            // calculate against total outstanding balance
            $total_due_balance = 0;
            if (!empty($student_due_fee)) {
                foreach ($student_due_fee as $fee) {
                    if (!empty($fee->fees)) {
                        foreach ($fee->fees as $fee_value) {
                            $paid = 0;
                            $disc = 0;
                            if (!empty($fee_value->amount_detail)) {
                                $deps = json_decode($fee_value->amount_detail);
                                if (is_array($deps) || is_object($deps)) {
                                    foreach ($deps as $d) {
                                        $paid += $d->amount;
                                        $disc += $d->amount_discount;
                                    }
                                }
                            }
                            $bal = $fee_value->amount - ($paid + $disc);
                            if ($bal > 0) $total_due_balance += $bal;
                        }
                    }
                }
            }
            $total_discount_to_apply = ($total_due_balance * $percentage) / 100;
        }

        $remaining_discount = $total_discount_to_apply;

        if (!empty($student_due_fee) && $remaining_discount > 0) {
            foreach ($student_due_fee as $fee) {
                if (!empty($fee->fees)) {
                    foreach ($fee->fees as $fee_value) {
                        if ($remaining_discount <= 0) break;

                        $head_paid = 0;
                        $head_disc = 0;
                        if (!empty($fee_value->amount_detail)) {
                            $deps = json_decode($fee_value->amount_detail);
                            if (is_array($deps) || is_object($deps)) {
                                foreach ($deps as $d) {
                                    $head_paid += $d->amount;
                                    $head_disc += $d->amount_discount;
                                }
                            }
                        }

                        $head_balance = $fee_value->amount - ($head_paid + $head_disc);
                        if ($head_balance > 0) {
                            $apply_now = min($remaining_discount, $head_balance);
                            $remaining_discount -= $apply_now;

                            // Check existing student_fees_deposite record
                            $q_dep = $this->db->get_where('student_fees_deposite', array(
                                'student_fees_master_id' => $fee->id,
                                'fee_groups_feetype_id' => $fee_value->fee_groups_feetype_id
                            ));

                            if ($q_dep->num_rows() > 0) {
                                $dep_row = $q_dep->row();
                                $amt_detail = json_decode($dep_row->amount_detail, true);
                                $inv_no = max(array_keys($amt_detail)) + 1;
                                
                                $amt_detail[$inv_no] = array(
                                    'inv_no' => $inv_no,
                                    'amount' => 0,
                                    'date' => date('Y-m-d'),
                                    'description' => 'Direct Discount: ' . $reason,
                                    'amount_discount' => $apply_now,
                                    'amount_fine' => 0,
                                    'payment_mode' => 'Cash',
                                    'received_by' => $staff_id,
                                    'collected_by' => $collected_by
                                );

                                $this->db->where('id', $dep_row->id);
                                $this->db->update('student_fees_deposite', array(
                                    'amount_detail' => json_encode($amt_detail)
                                ));

                                $this->db->insert('student_applied_discounts', array(
                                    'student_fees_deposite_id' => $dep_row->id,
                                    'student_fees_discount_id' => $student_fees_discount_id,
                                    'date' => date('Y-m-d'),
                                    'invoice_id' => $dep_row->id,
                                    'sub_invoice_id' => $inv_no
                                ));
                            } else {
                                $amt_detail = array(
                                    1 => array(
                                        'inv_no' => 1,
                                        'amount' => 0,
                                        'date' => date('Y-m-d'),
                                        'description' => 'Direct Discount: ' . $reason,
                                        'amount_discount' => $apply_now,
                                        'amount_fine' => 0,
                                        'payment_mode' => 'Cash',
                                        'received_by' => $staff_id,
                                        'collected_by' => $collected_by
                                    )
                                );

                                $this->db->insert('student_fees_deposite', array(
                                    'student_fees_master_id' => $fee->id,
                                    'fee_groups_feetype_id' => $fee_value->fee_groups_feetype_id,
                                    'amount_detail' => json_encode($amt_detail)
                                ));
                                $inserted_dep_id = $this->db->insert_id();

                                $this->db->insert('student_applied_discounts', array(
                                    'student_fees_deposite_id' => $inserted_dep_id,
                                    'student_fees_discount_id' => $student_fees_discount_id,
                                    'date' => date('Y-m-d'),
                                    'invoice_id' => $inserted_dep_id,
                                    'sub_invoice_id' => 1
                                ));
                            }
                        }
                    }
                }
            }
        }

        // If transport fee has balance and discount remaining
        if ($remaining_discount > 0) {
            $student = $this->student_model->getByStudentSession($student_session_id);
            if (!empty($student['route_pickup_point_id'])) {
                $transport_fees = $this->studentfeemaster_model->getStudentTransportFees($student_session_id, $student['route_pickup_point_id']);
                if (!empty($transport_fees)) {
                    foreach ($transport_fees as $tr_fee) {
                        if ($remaining_discount <= 0) break;

                        $tr_paid = 0;
                        $tr_disc = 0;
                        if (!empty($tr_fee->amount_detail)) {
                            $deps = json_decode($tr_fee->amount_detail);
                            if (is_array($deps) || is_object($deps)) {
                                foreach ($deps as $d) {
                                    $tr_paid += $d->amount;
                                    $tr_disc += $d->amount_discount;
                                }
                            }
                        }

                        $tr_balance = $tr_fee->fees - ($tr_paid + $tr_disc);
                        if ($tr_balance > 0) {
                            $apply_now = min($remaining_discount, $tr_balance);
                            $remaining_discount -= $apply_now;

                            $is_yearly = (isset($tr_fee->fee_category) && $tr_fee->fee_category == 'transport_yearly');
                            $where_field = $is_yearly ? 'student_transport_yearly_fee_id' : 'student_transport_fee_id';

                            $q_dep = $this->db->get_where('student_fees_deposite', array($where_field => $tr_fee->id));

                            if ($q_dep->num_rows() > 0) {
                                $dep_row = $q_dep->row();
                                $amt_detail = json_decode($dep_row->amount_detail, true);
                                $inv_no = max(array_keys($amt_detail)) + 1;

                                $amt_detail[$inv_no] = array(
                                    'inv_no' => $inv_no,
                                    'amount' => 0,
                                    'date' => date('Y-m-d'),
                                    'description' => 'Direct Discount: ' . $reason,
                                    'amount_discount' => $apply_now,
                                    'amount_fine' => 0,
                                    'payment_mode' => 'Cash',
                                    'received_by' => $staff_id,
                                    'collected_by' => $collected_by
                                );

                                $this->db->where('id', $dep_row->id);
                                $this->db->update('student_fees_deposite', array(
                                    'amount_detail' => json_encode($amt_detail)
                                ));

                                $this->db->insert('student_applied_discounts', array(
                                    'student_fees_deposite_id' => $dep_row->id,
                                    'student_fees_discount_id' => $student_fees_discount_id,
                                    'date' => date('Y-m-d'),
                                    'invoice_id' => $dep_row->id,
                                    'sub_invoice_id' => $inv_no
                                ));
                            } else {
                                $amt_detail = array(
                                    1 => array(
                                        'inv_no' => 1,
                                        'amount' => 0,
                                        'date' => date('Y-m-d'),
                                        'description' => 'Direct Discount: ' . $reason,
                                        'amount_discount' => $apply_now,
                                        'amount_fine' => 0,
                                        'payment_mode' => 'Cash',
                                        'received_by' => $staff_id,
                                        'collected_by' => $collected_by
                                    )
                                );

                                $insert_data = array(
                                    'amount_detail' => json_encode($amt_detail)
                                );
                                $insert_data[$where_field] = $tr_fee->id;

                                $this->db->insert('student_fees_deposite', $insert_data);
                                $inserted_dep_id = $this->db->insert_id();

                                $this->db->insert('student_applied_discounts', array(
                                    'student_fees_deposite_id' => $inserted_dep_id,
                                    'student_fees_discount_id' => $student_fees_discount_id,
                                    'date' => date('Y-m-d'),
                                    'invoice_id' => $inserted_dep_id,
                                    'sub_invoice_id' => 1
                                ));
                            }
                        }
                    }
                }
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            echo json_encode(array('status' => 'fail', 'message' => 'Failed to apply discount. Please try again.'));
            return;
        }

        echo json_encode(array(
            'status' => 'success',
            'message' => 'Discount successfully applied! The student fee balance has been deducted immediately without collecting any fee.',
            'student_fees_discount_id' => $student_fees_discount_id,
            'fees_discount_id' => $fees_discount_id
        ));
    }

}



