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

        $data['title'] = 'Transport Fee Master';
        $data['pickuppointlist'] = $this->pickuppoint_model->get();
        $data['classlist'] = $this->class_model->get();
        $data['feetypelist'] = $this->feetype_model->get();
        
        $yearlyfeelist = $this->transportyearlyfee_model->get();
        $grouped = [];
        $total_classes = count($data['classlist']);
        foreach ($yearlyfeelist as $fee) {
            $key = $fee['pickup_point_id'] . '_' . $fee['feetype_id'] . '_' . $fee['amount'] . '_' . $fee['due_date'] . '_' . $fee['fine_type'] . '_' . $fee['fine_percentage'] . '_' . $fee['fine_amount'];
            if (!isset($grouped[$key])) {
                $grouped[$key] = $fee;
                $grouped[$key]['class_names'] = [$fee['class_name']];
                $grouped[$key]['ids'] = [$fee['id']];
            } else {
                if (!in_array($fee['class_name'], $grouped[$key]['class_names'])) {
                    $grouped[$key]['class_names'][] = $fee['class_name'];
                }
                $grouped[$key]['ids'][] = $fee['id'];
            }
        }
        $data['yearlyfeelist'] = array_values($grouped);
        $data['total_classes'] = $total_classes;

        $this->form_validation->set_rules('pickup_point_id', $this->lang->line('pickup_point'), 'trim|required|xss_clean');
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

            $data_insert = array(
                'session_id' => $this->setting_model->getCurrentSession(),
                'pickup_point_id' => $this->input->post('pickup_point_id'),
                'route_pickup_point_id' => 0,
                'class_id' => 0,
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
                // Fetch all students having this pickup point via their route_pickup_point_id
                $route_pickups = $this->pickuppoint_model->getRoutePickupsByPickupId($this->input->post('pickup_point_id'));
                if (!empty($route_pickups)) {
                    foreach ($route_pickups as $rp) {
                        $students = $this->db->get_where('student_session', array('route_pickup_point_id' => $rp['id'], 'session_id' => $this->setting_model->getCurrentSession()))->result_array();
                        if(!empty($students)) {
                            foreach($students as $student) {
                                $assign_data = array(
                                    'student_session_id' => $student['id'],
                                    'transport_yearly_feemaster_id' => $insert_id,
                                    'is_active' => 'yes'
                                );
                                $this->transportyearlyfee_model->assignStudent($assign_data);
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

        if ($this->transportyearlyfee_model->check_payment_exists($id)) {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Cannot delete, payment is already made for this fee.</div>');
            redirect('admin/transportyearlyfee');
        }

        $this->transportyearlyfee_model->remove($id);
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Record deleted successfully</div>');
        redirect('admin/transportyearlyfee');
    }

    public function delete_bulk($ids_string)
    {
        if (!($this->rbac->hasPrivilege('transport_fees_master', 'can_delete'))) {
            access_denied();
        }

        if (empty($ids_string)) {
            redirect('admin/transportyearlyfee');
            return;
        }

        $ids = explode('-', $ids_string);
        
        foreach ($ids as $id) {
            if ($this->transportyearlyfee_model->check_payment_exists($id)) {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Cannot delete, payment is already made for this fee group.</div>');
                redirect('admin/transportyearlyfee');
            }
        }

        foreach ($ids as $id) {
            $this->transportyearlyfee_model->remove($id);
        }
        
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Record deleted successfully</div>');
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
        
        $yearlyfeelist = $this->transportyearlyfee_model->get(null, $route_id, $pickup_point_id);
        $grouped = [];
        $total_classes = count($this->class_model->get());
        foreach ($yearlyfeelist as $fee) {
            $key = $fee['route_pickup_point_id'] . '_' . $fee['feetype_id'] . '_' . $fee['amount'] . '_' . $fee['due_date'];
            if (!isset($grouped[$key])) {
                $grouped[$key] = $fee;
                $grouped[$key]['class_names'] = [$fee['class_name']];
                $grouped[$key]['ids'] = [$fee['id']];
            } else {
                $grouped[$key]['class_names'][] = $fee['class_name'];
                $grouped[$key]['ids'][] = $fee['id'];
            }
        }
        $data['yearlyfeelist'] = array_values($grouped);
        $data['total_classes'] = $total_classes;
        $data['currency_symbol'] = $this->customlib->getSchoolCurrencyFormat();
        
        $html = $this->load->view('admin/transport/_yearlyfeelist', $data, true);
        echo json_encode(array('status' => 1, 'page' => $html));
    }

    public function bulk_assign()
    {
        if (!($this->rbac->hasPrivilege('transport_fees_master', 'can_add'))) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Transport');
        $this->session->set_userdata('sub_menu', 'transportyearlyfee/index');
        $data['title'] = 'Bulk Assign Pickup Point';
        $this->load->view('layout/header', $data);
        $this->load->view('admin/transport/bulk_assign_pickup', $data);
        $this->load->view('layout/footer', $data);
    }

    public function preview_bulk_assign()
    {
        if (!($this->rbac->hasPrivilege('transport_fees_master', 'can_add'))) {
            access_denied();
        }
        $adm_col = (int)$this->input->post('adm_col') - 1;
        $pickup_col = (int)$this->input->post('pickup_col') - 1;
        $has_header = $this->input->post('has_header') ? true : false;
        
        $session_id = $this->setting_model->getCurrentSession();
        $response = array('status' => 0, 'html' => '', 'data' => array());
        
        if (isset($_FILES['csv_file']) && !empty($_FILES['csv_file']['name'])) {
            $file = $_FILES['csv_file']['tmp_name'];
            if (($handle = fopen($file, "r")) !== FALSE) {
                $row_count = 0;
                $preview_data = array();
                
                if ($has_header) {
                    fgetcsv($handle, 1000, ",");
                }
                
                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                    $row_count++;
                    
                    if (!isset($data[$adm_col]) || !isset($data[$pickup_col])) {
                        $preview_data[] = array('row' => $row_count, 'adm' => 'N/A', 'name' => 'N/A', 'pickup' => 'N/A', 'status' => '<span class="label label-danger">Missing Columns</span>', 'valid' => 0);
                        continue;
                    }
                    
                    $admission_no = trim($data[$adm_col]);
                    $pickup_name = trim($data[$pickup_col]);
                    
                    if (empty($admission_no) || empty($pickup_name)) {
                        $preview_data[] = array('row' => $row_count, 'adm' => htmlspecialchars($admission_no), 'name' => 'N/A', 'pickup' => htmlspecialchars($pickup_name), 'status' => '<span class="label label-danger">Empty Fields</span>', 'valid' => 0);
                        continue;
                    }
                    
                    // Find student
                    $this->db->select('id, firstname, lastname');
                    $this->db->where('admission_no', $admission_no);
                    $student = $this->db->get('students')->row();
                    
                    if (!$student) {
                        $preview_data[] = array('row' => $row_count, 'adm' => htmlspecialchars($admission_no), 'name' => 'N/A', 'pickup' => htmlspecialchars($pickup_name), 'status' => '<span class="label label-danger">Student Not Found</span>', 'valid' => 0);
                        continue;
                    }
                    
                    $student_name = trim($student->firstname . ' ' . $student->lastname);
                    
                    // Find pickup point
                    $this->db->select('id, name');
                    $this->db->where('name', $pickup_name);
                    $pickup = $this->db->get('pickup_point')->row();
                    
                    if (!$pickup) {
                        $preview_data[] = array('row' => $row_count, 'adm' => htmlspecialchars($admission_no), 'name' => htmlspecialchars($student_name), 'pickup' => htmlspecialchars($pickup_name), 'status' => '<span class="label label-danger">Pickup Not Found</span>', 'valid' => 0);
                        continue;
                    }
                    
                    // Find route_pickup_point linking
                    $this->db->select('id, transport_route_id');
                    $this->db->where('pickup_point_id', $pickup->id);
                    $this->db->where('session_id', $session_id);
                    $route_mapping = $this->db->get('route_pickup_point')->row();
                    
                    if (!$route_mapping) {
                        $preview_data[] = array('row' => $row_count, 'adm' => htmlspecialchars($admission_no), 'name' => htmlspecialchars($student_name), 'pickup' => htmlspecialchars($pickup->name), 'status' => '<span class="label label-danger">Not mapped to any route</span>', 'valid' => 0);
                        continue;
                    }

                    // Find vehicle_routes id (vehroute_id)
                    $this->db->select('id');
                    $this->db->where('route_id', $route_mapping->transport_route_id);
                    $vehicle_route = $this->db->get('vehicle_routes')->row();

                    if (!$vehicle_route) {
                        $preview_data[] = array('row' => $row_count, 'adm' => htmlspecialchars($admission_no), 'name' => htmlspecialchars($student_name), 'pickup' => htmlspecialchars($pickup->name), 'status' => '<span class="label label-danger">No vehicle assigned to this route</span>', 'valid' => 0);
                        continue;
                    }
                    
                    $preview_data[] = array(
                        'row' => $row_count, 
                        'adm' => htmlspecialchars($admission_no), 
                        'name' => htmlspecialchars($student_name), 
                        'pickup' => htmlspecialchars($pickup->name), 
                        'status' => '<span class="label label-success">Ready to Assign</span>', 
                        'valid' => 1,
                        'student_id' => $student->id,
                        'route_pickup_point_id' => $route_mapping->id,
                        'vehroute_id' => $vehicle_route->id
                    );
                }
                fclose($handle);
                
                $response['status'] = 1;
                $response['data'] = $preview_data;
                
                // Build HTML
                $html = '<table class="table table-striped table-bordered table-hover"><thead><tr><th>Row</th><th>Admission No</th><th>Student Name</th><th>Pickup Point</th><th>Status</th></tr></thead><tbody>';
                $valid_count = 0;
                foreach($preview_data as $row) {
                    $html .= '<tr><td>'.$row['row'].'</td><td>'.$row['adm'].'</td><td>'.$row['name'].'</td><td>'.$row['pickup'].'</td><td>'.$row['status'].'</td></tr>';
                    if($row['valid']) $valid_count++;
                }
                $html .= '</tbody></table>';
                
                if($valid_count > 0) {
                    $html .= '<div class="alert alert-info" style="margin-top:10px;">Found '.$valid_count.' valid records ready to be assigned.</div>';
                }
                
                $response['html'] = $html;
            } else {
                $response['html'] = '<div class="alert alert-danger">Failed to open file.</div>';
            }
        } else {
            $response['html'] = '<div class="alert alert-danger">Please upload a valid CSV file.</div>';
        }
        
        echo json_encode($response);
    }

    public function save_bulk_assign()
    {
        if (!($this->rbac->hasPrivilege('transport_fees_master', 'can_add'))) {
            access_denied();
        }
        
        $session_id = $this->setting_model->getCurrentSession();
        $assign_data = $this->input->post('assign_data', false);
        
        $success_count = 0;
        if (!empty($assign_data)) {
            $records = json_decode($assign_data, true);
            if (is_array($records)) {
                foreach ($records as $record) {
                    if (isset($record['valid']) && $record['valid'] == 1) {
                        $student_id = $record['student_id'];
                        $route_pickup_point_id = $record['route_pickup_point_id'];
                        $vehroute_id = $record['vehroute_id'];
                        
                        $this->db->where('student_id', $student_id);
                        $this->db->where('session_id', $session_id);
                        $this->db->update('student_session', array(
                            'route_pickup_point_id' => $route_pickup_point_id,
                            'vehroute_id' => $vehroute_id
                        ));
                        
                        $success_count++;
                    }
                }
            }
        }
        
        echo json_encode(array('status' => 1, 'msg' => 'Successfully assigned transport to '.$success_count.' students.'));
    }
}
