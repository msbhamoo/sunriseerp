<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Transportyearlyfee extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model(array("pickuppoint_model", "routepickuppoint_model", "transportyearlyfee_model", "class_model", "feetype_model", "student_model", "vehroute_model"));
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function index()
    {
        if (!($this->rbac->hasPrivilege('transport_fees_master', 'can_view'))) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Transport');
        $this->session->set_userdata('sub_menu', 'transportyearlyfee/index');

        $data['title'] = 'Yearly Transport Fees';
        $data['vehroutelist'] = $this->vehroute_model->get();
        $data['classlist'] = $this->class_model->get();
        $data['feetypelist'] = $this->feetype_model->get();
        $data['yearlyfeelist'] = $this->transportyearlyfee_model->get();

        $this->form_validation->set_rules('route_id', $this->lang->line('route'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('route_pickup_point_id', $this->lang->line('pickup_point'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('class_id[]', $this->lang->line('class'), 'required');
        $this->form_validation->set_rules('feetype_id', $this->lang->line('fees_type'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('amount', $this->lang->line('amount'), 'trim|required|xss_clean|numeric');
        $this->form_validation->set_rules('due_date', $this->lang->line('due_date'), 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/transport/yearlyfeemaster', $data);
            $this->load->view('layout/footer', $data);
        } else {
            if (!($this->rbac->hasPrivilege('transport_fees_master', 'can_add'))) {
                access_denied();
            }

            $fine_type = $this->input->post('account_type');
            $fine_amount = 0;
            $fine_percentage = 0;
            if ($fine_type == 'fix') {
                $fine_amount = $this->input->post('fine_amount');
            } elseif ($fine_type == 'percentage') {
                $fine_percentage = $this->input->post('fine_percentage');
            } else {
                $fine_type = 'none';
            }

            $class_ids = $this->input->post('class_id');
            if (is_array($class_ids)) {
                foreach ($class_ids as $c_id) {
                    $data_insert = array(
                        'session_id' => $this->setting_model->getCurrentSession(),
                        'route_pickup_point_id' => $this->input->post('route_pickup_point_id'),
                        'class_id' => $c_id,
                        'feetype_id' => $this->input->post('feetype_id'),
                        'amount' => convertCurrencyFormatToBaseAmount($this->input->post('amount')),
                        'due_date' => $this->customlib->dateFormatToYYYYMMDD($this->input->post('due_date')),
                        'fine_type' => $fine_type,
                        'fine_amount' => convertCurrencyFormatToBaseAmount($fine_amount),
                        'fine_percentage' => $fine_percentage
                    );

                    $insert_id = $this->transportyearlyfee_model->add($data_insert);
                    
                    // Handle bulk assign if checked
                    $assign_to = $this->input->post('assign_to');
                    if ($assign_to == 'all') {
                        // Assign to all students in this class
                        $students = $this->student_model->searchByClassSection($c_id);
                        if(!empty($students)) {
                            foreach($students as $student) {
                                // Check if student route matches
                                if ($student['route_pickup_point_id'] == $data_insert['route_pickup_point_id']) {
                                    $assign_data = array(
                                        'student_session_id' => $student['student_session_id'],
                                        'transport_yearly_feemaster_id' => $insert_id,
                                        'is_active' => 'yes'
                                    );
                                    $this->transportyearlyfee_model->assignStudent($assign_data);
                                }
                            }
                        }
                    }
                }
            }

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
            redirect('admin/transportyearlyfee');
        }
    }

    public function delete($id)
    {
        if (!($this->rbac->hasPrivilege('transport_fees_master', 'can_delete'))) {
            access_denied();
        }
        $this->transportyearlyfee_model->remove($id);
        redirect('admin/transportyearlyfee');
    }
    public function search_yearly_fees()
    {
        $route_id = $this->input->post('route_id');
        $pickup_point_id = $this->input->post('pickup_point_id');
        
        if (!empty($route_id)) {
            $vec_route = $this->vehroute_model->getVechileDetailByVecRouteID($route_id);
            if ($vec_route) {
                $route_id = $vec_route->route_id;
            }
        }
        
        $data['yearlyfeelist'] = $this->transportyearlyfee_model->get(null, $route_id, $pickup_point_id);
        $data['currency_symbol'] = $this->customlib->getSchoolCurrencyFormat();
        
        $html = $this->load->view('admin/transport/_yearlyfeelist', $data, true);
        echo json_encode(array('status' => 1, 'page' => $html));
    }
}
