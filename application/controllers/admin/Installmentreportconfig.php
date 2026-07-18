<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Installmentreportconfig extends Admin_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model("Installmentreport_model");
        $this->load->model("class_model");
        // Ensure proper permissions
        if (!$this->rbac->hasPrivilege('fees_master', 'can_view')) {
            access_denied();
        }
    }

    public function index() {
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'installment_report_config');
        
        $data['title'] = 'Installment Report Configuration';
        $data['plans'] = $this->Installmentreport_model->get_plans();
        $data['classes'] = $this->class_model->get();
        
        $data['fee_groups'] = $this->Installmentreport_model->get_available_fee_groups();
        $data['hostel_groups'] = $this->Installmentreport_model->get_available_hostel_groups();
        $data['transport_yearly'] = $this->Installmentreport_model->get_available_transport_yearly();
        
        $this->load->view('layout/header', $data);
        $this->load->view('admin/fees/installmentreportconfig_index', $data);
        $this->load->view('layout/footer', $data);
    }

    public function save() {
        $this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean');
        $this->form_validation->set_rules('total_installments', 'Total Installments', 'required|integer');
        
        if ($this->form_validation->run() == false) {
            $array = array('status' => 'fail', 'error' => validation_errors());
            echo json_encode($array);
            return;
        }

        $total_installments = $this->input->post('total_installments');
        $due_dates = $this->input->post('due_date'); // array of dates
        
        $fee_groups = $this->input->post('fee_groups');
        $hostel_groups = $this->input->post('hostel_groups');
        $transport_yearly = $this->input->post('transport_yearly');
        
        $split_percentages = $this->input->post('split_percentage'); // Array: type => id => inst_num => percentage
        
        // Validate that selected categories sum to exactly 100%
        $validation_errors = [];
        if (!empty($split_percentages)) {
            foreach ($split_percentages as $type => $ids) {
                foreach ($ids as $id => $installments) {
                    $sum = 0;
                    foreach ($installments as $inst_num => $percent) {
                        $sum += (float)$percent;
                    }
                    if (abs($sum - 100.0) > 0.01) {
                        $validation_errors[] = "Total percentage for selected fee (Type: {$type}, ID: {$id}) must be exactly 100%. Currently it is {$sum}%.";
                    }
                }
            }
        }
        
        if (!empty($validation_errors)) {
            $array = array('status' => 'fail', 'error' => implode("<br>", $validation_errors));
            echo json_encode($array);
            return;
        }
        
        $data = array(
            'id' => $this->input->post('id'),
            'name' => $this->input->post('name'),
            'class_id' => $this->input->post('class_id') ? $this->input->post('class_id') : null,
            'session_id' => $this->setting_model->getCurrentSession(),
            'total_installments' => $total_installments
        );

        // Prepare dates
        $dates = array();
        for ($i = 0; $i < $total_installments; $i++) {
            $dates[] = array(
                'installment_number' => $i + 1,
                'due_date' => $this->customlib->dateFormatToYYYYMMDD($due_dates[$i])
            );
        }

        // Prepare splits
        $splits = array();
        if (!empty($split_percentages)) {
            foreach ($split_percentages as $type => $ids) {
                foreach ($ids as $id => $installments) {
                    foreach ($installments as $inst_num => $percent) {
                        $splits[] = array(
                            'fee_source_type' => $type,
                            'fee_source_id' => $id,
                            'installment_number' => $inst_num,
                            'percentage' => $percent
                        );
                    }
                }
            }
        }

        $result = $this->Installmentreport_model->add_plan($data, $dates, $splits);
        if ($result) {
            $array = array('status' => 'success', 'error' => '', 'message' => 'Record Saved Successfully');
        } else {
            $array = array('status' => 'fail', 'error' => 'Failed to save record.');
        }
        echo json_encode($array);
    }

    public function get_plan() {
        $id = $this->input->post('id');
        $plan = $this->Installmentreport_model->get_plan($id);
        
        if (!empty($plan['dates'])) {
            foreach ($plan['dates'] as &$date) {
                $date['due_date'] = date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($date['due_date']));
            }
        }
        
        echo json_encode($plan);
    }

    public function delete($id) {
        $this->Installmentreport_model->remove_plan($id);
        redirect('admin/installmentreportconfig/index');
    }
}
