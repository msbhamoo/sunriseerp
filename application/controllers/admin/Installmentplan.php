<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Installmentplan extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Installmentplan_model');
        $this->load->model('feegroup_model');
        $this->load->model('class_model');
        $this->load->model('transportyearlyfee_model');
        $this->current_session = $this->setting_model->getCurrentSession();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('installment_plan', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/installmentplan');
        
        $data['title'] = 'Installment Plan List';
        $data['planList'] = $this->Installmentplan_model->get();
        $data['classlist'] = $this->class_model->get();
        
        // Get fee groups
        $data['feegroupList'] = $this->feegroup_model->get();
        
        // Get hostel fee groups
        $this->db->select('fee_groups.id, fee_groups.name');
        $this->db->from('hostel_fee_groups');
        $this->db->join('fee_groups', 'fee_groups.id = hostel_fee_groups.fee_groups_id');
        $data['hostelFeegroupList'] = $this->db->get()->result_array();
        
        // Get transport yearly fees (grouped by name if possible, or just all)
        // Transport yearly doesn't have a direct name, it's linked to route/class. 
        // We will just fetch all distinct route pickup points used in yearly fees.
        $this->db->select('transport_yearly_feemaster.id, route_pickup_point.destination_distance as name');
        $this->db->from('transport_yearly_feemaster');
        $this->db->join('route_pickup_point', 'route_pickup_point.id = transport_yearly_feemaster.route_pickup_point_id');
        $this->db->group_by('transport_yearly_feemaster.id');
        $data['transportYearlyList'] = $this->db->get()->result_array();

        $this->load->view('layout/header', $data);
        $this->load->view('admin/fees/installmentplan', $data);
        $this->load->view('layout/footer', $data);
    }

    public function add()
    {
        if (!$this->rbac->hasPrivilege('installment_plan', 'can_add')) {
            access_denied();
        }

        $this->form_validation->set_rules('name', 'Plan Name', 'required');
        $this->form_validation->set_rules('total_installments', 'Total Installments', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $data = array(
                'name' => form_error('name'),
                'total_installments' => form_error('total_installments'),
            );
            $array = array('status' => 'fail', 'error' => $data);
            echo json_encode($array);
        } else {
            $is_global = $this->input->post('is_global') ? 1 : 0;
            
            $data = array(
                'name' => $this->input->post('name'),
                'session_id' => $this->current_session,
                'is_global' => $is_global,
                'total_installments' => $this->input->post('total_installments'),
                'is_active' => 'yes'
            );
            
            if ($this->input->post('id')) {
                $data['id'] = $this->input->post('id');
            }
            
            $plan_id = $this->Installmentplan_model->add($data);
            
            // Save Classes if not global
            if (!$is_global) {
                $classes = $this->input->post('class_id');
                $this->Installmentplan_model->saveClasses($plan_id, $classes);
            }
            
            // Save Items
            $items = $this->input->post('fee_items');
            $this->Installmentplan_model->saveItems($plan_id, $items);
            
            // Save Details
            $total_installments = $this->input->post('total_installments');
            $details = array();
            for ($i = 1; $i <= $total_installments; $i++) {
                $details[] = array(
                    'installment_plan_id' => $plan_id,
                    'installment_number' => $i,
                    'academic_percentage' => $this->input->post('academic_percentage_' . $i),
                    'transport_percentage' => $this->input->post('transport_percentage_' . $i),
                    'hostel_percentage' => $this->input->post('hostel_percentage_' . $i),
                    'due_date' => $this->customlib->dateFormatToYYYYMMDD($this->input->post('due_date_' . $i))
                );
            }
            $this->Installmentplan_model->saveDetails($plan_id, $details);

            $array = array('status' => 'success', 'error' => '', 'message' => 'Record Saved Successfully');
            echo json_encode($array);
        }
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('installment_plan', 'can_edit')) {
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'admin/installmentplan');
        
        $data['title'] = 'Installment Plan List';
        $data['planList'] = $this->Installmentplan_model->get();
        $data['classlist'] = $this->class_model->get();
        
        $data['feegroupList'] = $this->feegroup_model->get();
        
        $this->db->select('fee_groups.id, fee_groups.name');
        $this->db->from('hostel_fee_groups');
        $this->db->join('fee_groups', 'fee_groups.id = hostel_fee_groups.fee_groups_id');
        $data['hostelFeegroupList'] = $this->db->get()->result_array();
        
        $this->db->select('transport_yearly_feemaster.id, route_pickup_point.destination_distance as name');
        $this->db->from('transport_yearly_feemaster');
        $this->db->join('route_pickup_point', 'route_pickup_point.id = transport_yearly_feemaster.route_pickup_point_id');
        $this->db->group_by('transport_yearly_feemaster.id');
        $data['transportYearlyList'] = $this->db->get()->result_array();

        $data['edit_plan'] = $this->Installmentplan_model->get($id);
        $data['edit_classes'] = $this->Installmentplan_model->getPlanClasses($id);
        $data['edit_items'] = $this->Installmentplan_model->getPlanItems($id);
        $data['edit_details'] = $this->Installmentplan_model->getPlanDetails($id);

        $this->load->view('layout/header', $data);
        $this->load->view('admin/fees/installmentplan', $data);
        $this->load->view('layout/footer', $data);
    }

    public function getPlanDetails()
    {
        $id = $this->input->post('id');
        $plan = $this->Installmentplan_model->get($id);
        $installments = $this->db->where('installment_plan_id', $id)->get('fee_installment_details')->result_array();
        
        foreach ($installments as &$inst) {
            $inst['due_date'] = $this->customlib->dateformat($inst['due_date']);
        }
        
        $data = array(
            'plan' => $plan,
            'installments' => $installments
        );
        echo json_encode($data);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('installment_plan', 'can_delete')) {
            access_denied();
        }
        $this->Installmentplan_model->remove($id);
        redirect('admin/installmentplan');
    }
}
