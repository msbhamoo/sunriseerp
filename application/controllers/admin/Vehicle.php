<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Vehicle extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('media_storage');
        $this->load->library('SaasValidation');
        $this->load->model('vehroute_model');
        $this->load->model('student_model');
        $this->load->model('studenttransportfee_model');
        $this->load->model('transportyearlyfee_model');
    }

    private function isSuperAdmin()
    {
        $getStaffRole = $this->customlib->getStaffRole();
        $staffrole = json_decode($getStaffRole);
        if (!empty($staffrole) && (strtolower($staffrole->name) == 'super admin' || $staffrole->id == 7)) {
            return true;
        }
        return false;
    }

    private function getStaffAssignedVehicles($staff_id = null)
    {
        if (empty($staff_id)) {
            $staff_id = $this->customlib->getStaffID();
        }
        
        $all_vehicles = $this->vehicle_model->get();
        if (empty($all_vehicles)) {
            return array();
        }

        $this->load->model('staff_model');
        $staff = $this->staff_model->get($staff_id);
        if (empty($staff)) {
            return array();
        }

        $staff_name = strtolower(trim((string)$staff['name']));
        $staff_surname = strtolower(trim((string)$staff['surname']));
        $staff_fullname = trim($staff_name . ' ' . $staff_surname);
        $staff_emp_id = strtolower(trim((string)$staff['employee_id']));
        $staff_phone = preg_replace('/[^0-9]/', '', (string)$staff['contact_no']);

        $assigned_vehicles = array();
        foreach ($all_vehicles as $v) {
            $driver_name = strtolower(trim((string)$v['driver_name']));
            $attendant_name = strtolower(trim((string)$v['attendant_name']));
            $driver_phone = preg_replace('/[^0-9]/', '', (string)$v['driver_contact']);
            $attendant_phone = preg_replace('/[^0-9]/', '', (string)$v['attendant_contact']);

            $match = false;
            
            if (!empty($driver_name) && strlen($driver_name) > 1) {
                if ($driver_name == $staff_fullname || 
                    $driver_name == $staff_name || 
                    (!empty($staff_emp_id) && $driver_name == $staff_emp_id) ||
                    (!empty($staff_name) && strlen($staff_name) > 2 && strpos($driver_name, $staff_name) !== false) || 
                    (!empty($staff_fullname) && strlen($staff_fullname) > 2 && strpos($staff_fullname, $driver_name) !== false)) {
                    $match = true;
                }
            }

            if (!empty($attendant_name) && strlen($attendant_name) > 1) {
                if ($attendant_name == $staff_fullname || 
                    $attendant_name == $staff_name || 
                    (!empty($staff_emp_id) && $attendant_name == $staff_emp_id) ||
                    (!empty($staff_name) && strlen($staff_name) > 2 && strpos($attendant_name, $staff_name) !== false) || 
                    (!empty($staff_fullname) && strlen($staff_fullname) > 2 && strpos($staff_fullname, $attendant_name) !== false)) {
                    $match = true;
                }
            }
            
            if (!empty($staff_phone) && strlen($staff_phone) >= 7) {
                if ((!empty($driver_phone) && (strpos($driver_phone, $staff_phone) !== false || strpos($staff_phone, $driver_phone) !== false)) || 
                    (!empty($attendant_phone) && (strpos($attendant_phone, $staff_phone) !== false || strpos($staff_phone, $attendant_phone) !== false))) {
                    $match = true;
                }
            }

            if ($match) {
                $assigned_vehicles[] = $v;
            }
        }

        if (empty($assigned_vehicles)) {
            // If the staff member is NOT assigned as a driver or helper on any specific vehicle,
            // but HAS explicit RBAC permissions (e.g. Transport Manager/Incharge/Staff), allow access to ALL vehicles.
            if ($this->rbac->hasPrivilege('vehicle', 'can_view') || 
                $this->rbac->hasPrivilege('transport_attendance', 'can_view')) {
                return $all_vehicles;
            }
        }

        return $assigned_vehicles;
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('vehicle', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Transport');
        $this->session->set_userdata('sub_menu', 'vehicle/index');
        $data['title']       = 'Add Vehicle';
        
        if ($this->isSuperAdmin()) {
            $listVehicle = $this->vehicle_model->get();
        } else {
            $listVehicle = $this->getStaffAssignedVehicles();
        }

        $data['occupancy']   = $this->vehicle_model->getVehicleOccupancy();
        $data['listVehicle'] = $listVehicle;
        $data['vehroutelist'] = $this->vehroute_model->get();
        
        $this->load->model('staff_model');
        $data['stafflist'] = $this->staff_model->get();
        
        $this->load->view('layout/header');
        $this->load->view('admin/vehicle/index', $data);
        $this->load->view('layout/footer');
    }

    public function validateCanUploadFile($str, $params_string)
    {
        $params_array = array_map('trim', explode(',', $params_string));
        return $this->saasvalidation->validateCanUploadFile($str, $params_array);
    }

    public function add()
    {
        if (!$this->rbac->hasPrivilege('vehicle', 'can_add')) {
            access_denied();
        }
        $this->form_validation->set_rules('vehicle_no', $this->lang->line('vehicle_number'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('vehicle_photo', $this->lang->line('vehicle_photo'), 'callback_handle_upload');

        $storage_array = "vehicle_photo"; // use comma for multiple files       
        $this->form_validation->set_rules('vehicle_photo', $this->lang->line('storage'), "callback_validateCanUploadFile[$storage_array]");      
    
        if ($this->form_validation->run() == false) {
            $msg = array(
                'vehicle_no' => form_error('vehicle_no'),
                'vehicle_photo' => form_error('vehicle_photo'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {   

         try {
                $total_documents_failed_size = 0;
                $storage_array = ['vehicle_photo'];
                $this->saasvalidation->updateStorageLimit('storage', $storage_array); // update resource quota initially 
                $vehicle_photo = $this->media_storage->fileupload("vehicle_photo", "./uploads/vehicle_photo/");
                if (IsNullOrEmptyString($vehicle_photo)) {  // check upload image has not uploaded successfully
                    $total_documents_failed_size += $this->media_storage->getTmpFileSize('vehicle_photo');  // get temp size of image because of image not uploaded 
                }

                if ($total_documents_failed_size > 0) {
                    $this->saasvalidation->deleteResouceQuota('storage', $total_documents_failed_size);
                }         
            
            $data = array(
                'vehicle_no'           => $this->input->post('vehicle_no'),
                'vehicle_model'        => $this->input->post('vehicle_model'),
                'driver_name'          => $this->input->post('driver_name'),
                'driver_licence'       => $this->input->post('driver_licence'),
                'driver_contact'       => $this->input->post('driver_contact'),
                'note'                 => $this->input->post('note'),
                'registration_number'  => $this->input->post('registration_number'),
                'chasis_number'        => $this->input->post('chasis_number'),
                'max_seating_capacity' => $this->input->post('max_seating_capacity'),
                'manufacture_year'      => $this->input->post('manufacture_year'),
                'vehicle_photo'        => $vehicle_photo,
                
                // Insurance
                'insurance_provider' => $this->input->post('insurance_provider'),
                'policy_number' => $this->input->post('policy_number'),
                'insurance_type' => $this->input->post('insurance_type'),
                'insurance_start' => ($this->input->post('insurance_start')) ? date('Y-m-d', strtotime($this->input->post('insurance_start'))) : null,
                'insurance_expiry' => ($this->input->post('insurance_expiry')) ? date('Y-m-d', strtotime($this->input->post('insurance_expiry'))) : null,
                
                // Permits & Certs
                'permit_number' => $this->input->post('permit_number'),
                'permit_expiry' => ($this->input->post('permit_expiry')) ? date('Y-m-d', strtotime($this->input->post('permit_expiry'))) : null,
                'fitness_number' => $this->input->post('fitness_number'),
                'fitness_expiry' => ($this->input->post('fitness_expiry')) ? date('Y-m-d', strtotime($this->input->post('fitness_expiry'))) : null,
                'puc_number' => $this->input->post('puc_number'),
                'puc_expiry' => ($this->input->post('puc_expiry')) ? date('Y-m-d', strtotime($this->input->post('puc_expiry'))) : null,
                'road_tax_valid' => ($this->input->post('road_tax_valid')) ? date('Y-m-d', strtotime($this->input->post('road_tax_valid'))) : null,
                
                // Driver Details
                'license_number' => $this->input->post('license_number'),
                'license_category' => $this->input->post('license_category'),
                'license_expiry' => ($this->input->post('license_expiry')) ? date('Y-m-d', strtotime($this->input->post('license_expiry'))) : null,
                'background_verification' => $this->input->post('background_verification'),
                'attendant_name' => $this->input->post('attendant_name'),
                'attendant_contact' => $this->input->post('attendant_contact'),
                
                // Safety & Compliance
                'has_gps' => $this->input->post('has_gps') ? $this->input->post('has_gps') : 'No',
                'gps_device_id' => $this->input->post('gps_device_id'),
                'has_cctv' => $this->input->post('has_cctv') ? $this->input->post('has_cctv') : 'No',
                'has_fire_extinguisher' => $this->input->post('has_fire_extinguisher') ? $this->input->post('has_fire_extinguisher') : 'No',
                'fire_extinguisher_expiry' => ($this->input->post('fire_extinguisher_expiry')) ? date('Y-m-d', strtotime($this->input->post('fire_extinguisher_expiry'))) : null,
                'has_first_aid' => $this->input->post('has_first_aid') ? $this->input->post('has_first_aid') : 'No',
                'has_speed_governor' => $this->input->post('has_speed_governor') ? $this->input->post('has_speed_governor') : 'No',
                
                // Maintenance & Status
                'vehicle_status' => $this->input->post('vehicle_status') ? $this->input->post('vehicle_status') : 'Active',
                'last_maintenance_date' => ($this->input->post('last_maintenance_date')) ? date('Y-m-d', strtotime($this->input->post('last_maintenance_date'))) : null,
                'next_maintenance_due' => ($this->input->post('next_maintenance_due')) ? date('Y-m-d', strtotime($this->input->post('next_maintenance_due'))) : null,
            );
            
            // Handle Document Uploads
            $doc_fields = ['doc_rc', 'doc_insurance', 'doc_fitness', 'doc_puc', 'doc_permit', 'doc_license'];
            foreach ($doc_fields as $doc_field) {
                if (isset($_FILES[$doc_field]) && $_FILES[$doc_field]['name'] != '') {
                    $doc_name = $this->media_storage->fileupload($doc_field, "./uploads/vehicle_documents/");
                    if (!IsNullOrEmptyString($doc_name)) {
                        $data[$doc_field] = $doc_name;
                    }
                }
            }

           
            $this->vehicle_model->add($data);

            // System Notification (Bell Icon)
            $this->load->model('SystemNotificationSetting_model');
            if ($this->SystemNotificationSetting_model->check_setting('vehicle_alerts')) {
                $this->load->model('SystemNotification_model');
                $message = "New Vehicle Added: " . $data['vehicle_no'];
                // Notify Super Admin (Role 7)
                $this->SystemNotification_model->notifyRole(7, 'Vehicle Added', $message, 'admin/vehicle');
                
                // Notify Driver
                if (!empty($data['driver_name'])) {
                    $this->load->model('staff_model');
                    $driver_id = $this->staff_model->getStaffByName($data['driver_name']);
                    if ($driver_id) {
                        $this->SystemNotification_model->notifyUser($driver_id, 'Vehicle Assigned', "You have been assigned as driver for vehicle: " . $data['vehicle_no'], 'admin/vehicle');
                    }
                }
            }

            $msg   = $this->lang->line('success_message');
            $array = array('status' => 'success', 'error' => '', 'message' => $msg);

        } catch (Exception $e) {
                // Print the exception message for debugging or logging purposes
                $array = array('status' => 'fail', 'error' => $e->getMessage(), 'message' => '');
        }  
        }
        echo json_encode($array);
    }

    public function getsinglevehicledata()
    {
        $vehicleid = $this->input->post('vehicleid');
        if (!$this->isSuperAdmin()) {
            $assigned = $this->getStaffAssignedVehicles();
            $allowed_ids = array_column($assigned, 'id');
            if (!in_array($vehicleid, $allowed_ids)) {
                echo json_encode(array('page' => '<div class="alert alert-danger">Access Denied</div>'));
                return;
            }
        }
        $data['editvehicle'] = $this->vehicle_model->get($vehicleid);
        
        $this->load->model('staff_model');
        $data['stafflist'] = $this->staff_model->get();

        $page                = $this->load->view('admin/vehicle/edit', $data, true);
        echo json_encode(array('page' => $page));
    }

    public function edit()
    {
        if (!$this->rbac->hasPrivilege('vehicle', 'can_edit')) {
            access_denied();
        }
        
        $this->form_validation->set_rules('vehicle_no', $this->lang->line('vehicle_number'), 'trim|required|xss_clean');
        $this->form_validation->set_rules('vehicle_photo', $this->lang->line('vehicle_photo'), 'callback_handle_upload');
        $storage_array = "vehicle_photo";
        $this->form_validation->set_rules('vehicle_photo', $this->lang->line('storage'), "callback_validateCanUploadFile[$storage_array]");

        $id        =    $this->input->post('id');
        $vehicle   =    $this->vehicle_model->get($id);       
        
        if ($this->form_validation->run() == false) {
            $msg = array(
                'vehicle_no' => form_error('vehicle_no'),
                'vehicle_photo' => form_error('vehicle_photo'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {    

        try {
            $prev_file_size = 0;
            $total_image_upload_size = 0;       

            $data = array(
                'id'                   => $this->input->post('id'),
                'vehicle_no'           => $this->input->post('vehicle_no'),
                'vehicle_model'        => $this->input->post('vehicle_model'),
                'driver_name'          => $this->input->post('driver_name'),
                'driver_licence'       => $this->input->post('driver_licence'),
                'driver_contact'       => $this->input->post('driver_contact'),
                'note'                 => $this->input->post('note'),
                'registration_number'  => $this->input->post('registration_number'),
                'chasis_number'        => $this->input->post('chasis_number'),
                'max_seating_capacity' => $this->input->post('max_seating_capacity'),
                'manufacture_year' => $this->input->post('manufacture_year'),
                
                // Insurance
                'insurance_provider' => $this->input->post('insurance_provider'),
                'policy_number' => $this->input->post('policy_number'),
                'insurance_type' => $this->input->post('insurance_type'),
                'insurance_start' => ($this->input->post('insurance_start')) ? date('Y-m-d', strtotime($this->input->post('insurance_start'))) : null,
                'insurance_expiry' => ($this->input->post('insurance_expiry')) ? date('Y-m-d', strtotime($this->input->post('insurance_expiry'))) : null,
                
                // Permits & Certs
                'permit_number' => $this->input->post('permit_number'),
                'permit_expiry' => ($this->input->post('permit_expiry')) ? date('Y-m-d', strtotime($this->input->post('permit_expiry'))) : null,
                'fitness_number' => $this->input->post('fitness_number'),
                'fitness_expiry' => ($this->input->post('fitness_expiry')) ? date('Y-m-d', strtotime($this->input->post('fitness_expiry'))) : null,
                'puc_number' => $this->input->post('puc_number'),
                'puc_expiry' => ($this->input->post('puc_expiry')) ? date('Y-m-d', strtotime($this->input->post('puc_expiry'))) : null,
                'road_tax_valid' => ($this->input->post('road_tax_valid')) ? date('Y-m-d', strtotime($this->input->post('road_tax_valid'))) : null,
                
                // Driver Details
                'license_number' => $this->input->post('license_number'),
                'license_category' => $this->input->post('license_category'),
                'license_expiry' => ($this->input->post('license_expiry')) ? date('Y-m-d', strtotime($this->input->post('license_expiry'))) : null,
                'background_verification' => $this->input->post('background_verification'),
                'attendant_name' => $this->input->post('attendant_name'),
                'attendant_contact' => $this->input->post('attendant_contact'),
                
                // Safety & Compliance
                'has_gps' => $this->input->post('has_gps') ? $this->input->post('has_gps') : 'No',
                'gps_device_id' => $this->input->post('gps_device_id'),
                'has_cctv' => $this->input->post('has_cctv') ? $this->input->post('has_cctv') : 'No',
                'has_fire_extinguisher' => $this->input->post('has_fire_extinguisher') ? $this->input->post('has_fire_extinguisher') : 'No',
                'fire_extinguisher_expiry' => ($this->input->post('fire_extinguisher_expiry')) ? date('Y-m-d', strtotime($this->input->post('fire_extinguisher_expiry'))) : null,
                'has_first_aid' => $this->input->post('has_first_aid') ? $this->input->post('has_first_aid') : 'No',
                'has_speed_governor' => $this->input->post('has_speed_governor') ? $this->input->post('has_speed_governor') : 'No',
                
                // Maintenance & Status
                'vehicle_status' => $this->input->post('vehicle_status') ? $this->input->post('vehicle_status') : 'Active',
                'last_maintenance_date' => ($this->input->post('last_maintenance_date')) ? date('Y-m-d', strtotime($this->input->post('last_maintenance_date'))) : null,
                'next_maintenance_due' => ($this->input->post('next_maintenance_due')) ? date('Y-m-d', strtotime($this->input->post('next_maintenance_due'))) : null,
            );
            
            // Handle Document Uploads
            $doc_fields = ['doc_rc', 'doc_insurance', 'doc_fitness', 'doc_puc', 'doc_permit', 'doc_license'];
            foreach ($doc_fields as $doc_field) {
                if (isset($_FILES[$doc_field]) && $_FILES[$doc_field]['name'] != '') {
                    $doc_name = $this->media_storage->fileupload($doc_field, "./uploads/vehicle_documents/");
                    if (!IsNullOrEmptyString($doc_name)) {
                        $data[$doc_field] = $doc_name;
                    }
                }
            }            
            
            if (isset($_FILES["vehicle_photo"]) && $_FILES['vehicle_photo']['name'] != '' && (!empty($_FILES['vehicle_photo']['name']))) {
                $prev_file_size = $this->media_storage->getUploadedFileSize($vehicle->vehicle_photo, 'uploads/vehicle_photo');
                $img_name       = $this->media_storage->fileupload("vehicle_photo", "./uploads/vehicle_photo/");
                 if (!IsNullOrEmptyString($img_name)) {
                    $total_image_upload_size += $this->media_storage->getTmpFileSize('vehicle_photo');
                }

            } else {
                $img_name = $vehicle->vehicle_photo;
            }

            $data['vehicle_photo'] = $img_name;
            if (isset($_FILES["vehicle_photo"]) && $_FILES['vehicle_photo']['name'] != '' && (!empty($_FILES['vehicle_photo']['name']))) {
                if ($vehicle->vehicle_photo != '') {
                    $this->media_storage->filedelete($vehicle->vehicle_photo, "uploads/vehicle_photo");
                }
            }

            if ($prev_file_size > $total_image_upload_size) {
                // Previous file was larger 
                $size_difference = $prev_file_size - $total_image_upload_size;
                $this->saasvalidation->deleteResouceQuota('storage', $size_difference);
            } elseif ($prev_file_size < $total_image_upload_size) {
                // New file is larger 
                $size_difference = $total_image_upload_size - $prev_file_size;
                $this->saasvalidation->updateResouceQuota('storage', $size_difference);
            } else {
                // File size unchanged → no quota adjustment needed 
            }

            $this->vehicle_model->add($data);
            $msg   = $this->lang->line('success_message');
            $array = array('status' => 'success', 'error' => '', 'message' => $msg);
             } catch (Exception $e) {
                $array = array('status' => 'fail', 'error' => $e->getMessage() , 'message' => '');
            } 
        }
        echo json_encode($array);
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('vehicle', 'can_delete')) {
            access_denied();
        }
        $row = $this->vehicle_model->get($id);       

        if ($row->vehicle_photo != '') {
            $delete_file_size = $this->media_storage->getUploadedFileSize($row->vehicle_photo, 'uploads/vehicle_photo');
            $this->saasvalidation->deleteResouceQuota('storage', $delete_file_size);                
            $this->media_storage->filedelete($row->vehicle_photo, "uploads/vehicle_photo/");
        }
        $this->vehicle_model->remove($id);
        redirect('admin/vehicle/index');
    }
    
    public function vehicledetails()
    {
        $vehicleid = $this->input->post('vehicleid');
        if (!$this->isSuperAdmin()) {
            $assigned = $this->getStaffAssignedVehicles();
            $allowed_ids = array_column($assigned, 'id');
            if (!in_array($vehicleid, $allowed_ids)) {
                echo json_encode(array('page' => '<div class="alert alert-danger">Access Denied</div>'));
                return;
            }
        }
        $data['editvehicle'] = $this->vehicle_model->get($vehicleid);
        $data['vehicle_routes'] = $this->vehicle_model->getVehicleRoutes($vehicleid);
        $page                = $this->load->view('admin/vehicle/_vehicledetails', $data, true);
        echo json_encode(array('page' => $page));
    }
    
    public function update_alert()
    {
        if (!$this->rbac->hasPrivilege('vehicle', 'can_edit')) {
            echo json_encode(array('status' => 'fail', 'message' => 'Access Denied'));
            return;
        }
        
        $id = $this->input->post('vehicle_id');
        $field_name = $this->input->post('field_name'); 
        $new_date = $this->input->post('new_date');
        $doc_field_name = $this->input->post('doc_field_name'); 
        
        if (empty($id) || empty($field_name) || empty($new_date)) {
            echo json_encode(array('status' => 'fail', 'message' => 'Missing required fields'));
            return;
        }

        $data = array(
            'id' => $id,
            $field_name => date('Y-m-d', $this->customlib->datetostrtotime($new_date))
        );

        if (isset($_FILES['new_document']) && !empty($_FILES['new_document']['name']) && !empty($doc_field_name)) {
            $file_name = $_FILES["new_document"]["name"];
            $time      = md5($_FILES["new_document"]['name'] . microtime());
            $ext       = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $img_name  = $time . "." . $ext;
            
            $this->media_storage->fileupload("new_document", "./uploads/vehicle_documents/");
            $data[$doc_field_name] = $img_name;
            
            $vehicle = $this->vehicle_model->get($id);
            if (!empty($vehicle->$doc_field_name)) {
                $this->media_storage->filedelete($vehicle->$doc_field_name, "uploads/vehicle_documents/");
            }
        }

        $this->vehicle_model->add($data); 
        echo json_encode(array('status' => 1, 'msg' => $this->lang->line('update_message')));
    }

    public function assign_student_transport()
    {
        if (!$this->rbac->hasPrivilege('vehicle', 'can_add') && !$this->rbac->hasPrivilege('vehicle', 'can_edit')) {
            echo json_encode(['status' => 0, 'error' => ['Access Denied']]);
            return;
        }

        $student_id = $this->input->post('student_id');
        $student_session_id = $this->input->post('student_session_id');
        $class_id = $this->input->post('class_id');
        $vehroute_id = $this->input->post('vehroute_id');
        $route_pickup_point_id = $this->input->post('route_pickup_point_id');

        if(empty($student_session_id) || empty($vehroute_id) || empty($route_pickup_point_id) || empty($class_id)) {
            echo json_encode(['status' => 0, 'error' => ['Please fill all required fields.']]);
            return;
        }

        // 1. Check if the student has paid Yearly fees
        $paid_yearly_fees = $this->db->query("
            SELECT syf.id, syf.transport_yearly_feemaster_id 
            FROM student_transport_yearly_fees syf 
            JOIN student_fees_deposite sfd ON sfd.student_transport_yearly_fee_id = syf.id 
            WHERE syf.student_session_id = ?
        ", [$student_session_id])->result_array();

        $paid_master_ids = [];
        foreach($paid_yearly_fees as $pyf) {
            $paid_master_ids[] = $pyf['transport_yearly_feemaster_id'];
        }

        $this->db->trans_begin();

        // 2. Update Student Session Profile
        $this->db->where('id', $student_session_id);
        $this->db->update('student_session', [
            'vehroute_id' => $vehroute_id,
            'route_pickup_point_id' => $route_pickup_point_id
        ]);

        // 3. Protect Paid Fees: We do not update them because they are tied to the old fee master which acts as receipt for what they paid.

        // 4. Safely Delete ALL UNPAID yearly fees for this student
        $this->db->where('student_session_id', $student_session_id);
        if (count($paid_yearly_fees) > 0) {
            $paid_fee_ids = array_column($paid_yearly_fees, 'id');
            $this->db->where_not_in('id', $paid_fee_ids);
        }
        $this->db->delete('student_transport_yearly_fees');

        // 5. Automatically fetch and assign new Yearly Fees based on Class and Pickup Point
        $yearly_fees = $this->transportyearlyfee_model->getApplicableYearlyFees($class_id, $route_pickup_point_id);
        
        if (!empty($yearly_fees)) {
            $used_paid_fee = false;
            foreach($yearly_fees as $yf) {
                if (count($paid_yearly_fees) > 0 && !$used_paid_fee) {
                    // Update the existing partially/fully paid fee to point to the new fee master. 
                    // This naturally transfers any already paid amount towards the new fee!
                    $this->db->where('id', $paid_yearly_fees[0]['id']);
                    $this->db->update('student_transport_yearly_fees', ['transport_yearly_feemaster_id' => $yf['id']]);
                    $used_paid_fee = true;
                } else {
                    // No paid fees exist, so we do a fresh insert
                    $insert_val = array(
                        'student_session_id' => $student_session_id,
                        'transport_yearly_feemaster_id' => $yf['id'],
                    );
                    $this->db->insert('student_transport_yearly_fees', $insert_val);
                }
            }
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(['status' => 0, 'error' => ['Database error occurred.']]);
        } else {
            $this->db->trans_commit();
            echo json_encode(['status' => 1, 'message' => 'Student successfully assigned to transport!']);
        }
    }

    public function get_vehicle_students()
    {
        if (!$this->rbac->hasPrivilege('vehicle', 'can_view')) {
            access_denied();
        }
        $vehicle_id = $this->input->post('vehicle_id');
        if (!$this->isSuperAdmin()) {
            $assigned = $this->getStaffAssignedVehicles();
            $allowed_ids = array_column($assigned, 'id');
            if (!in_array($vehicle_id, $allowed_ids)) {
                echo json_encode(array('status' => 0, 'html' => '<div class="alert alert-danger">Access Denied</div>'));
                return;
            }
        }
        $students = $this->vehicle_model->getVehicleStudents($vehicle_id);
        $data['students'] = $students;
        $data['vehicle_id'] = $vehicle_id;
        
        $html = $this->load->view('admin/vehicle/_students_list', $data, true);
        echo json_encode(array('status' => 1, 'html' => $html));
    }

    public function handle_upload()
    {
        $image_validate = $this->config->item('file_validate');
        $result         = $this->filetype_model->get();
        if (isset($_FILES["vehicle_photo"]) && !empty($_FILES['vehicle_photo']['name'])) {

            $file_type = $_FILES["vehicle_photo"]['type'];
            $file_size = $_FILES["vehicle_photo"]["size"];
            $file_name = $_FILES["vehicle_photo"]["name"];

            $allowed_extension = array_map('trim', array_map('strtolower', explode(',', $result->image_extension)));
            $allowed_mime_type = array_map('trim', array_map('strtolower', explode(',', $result->image_mime)));
            $ext               = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            if ($files = filesize($_FILES['vehicle_photo']['tmp_name'])) {

                if (!in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_not_allowed'));
                    return false;
                }

                if (!in_array($ext, $allowed_extension) || !in_array($file_type, $allowed_mime_type)) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('extension_not_allowed'));
                    return false;
                }
                
                if ($file_size > $result->image_size) {
                    $this->form_validation->set_message('handle_upload', $this->lang->line('file_size_shoud_be_less_than') . number_format($result->image_size / 1048576, 2) . " MB");
                    return false;
                }
            } else {
                $this->form_validation->set_message('handle_upload', $this->lang->line('file_type_extension_error_uploading_image'));
                return false;
            }

            return true;
        }
        return true;
    }

    private function clean_csv_string($str)
    {
        if (empty($str)) {
            return '';
        }
        
        if (!mb_check_encoding($str, 'UTF-8')) {
            $str = mb_convert_encoding($str, 'UTF-8', 'Windows-1252, ISO-8859-1, ASCII');
        }

        $search = [
            "\x80", "\x82", "\x83", "\x84", "\x85", "\x86", "\x87", "\x88", "\x89", "\x8a", "\x8b", "\x8c", "\x8e",
            "\x91", "\x92", "\x93", "\x94", "\x95", "\x96", "\x97", "\x98", "\x99", "\x9a", "\x9b", "\x9c", "\x9e", "\x9f",
            "“", "”", "‘", "’", "–", "—", "…"
        ];
        $replace = [
            "EUR", ",", "f", ",,", "...", "+", "++", "^", "%", "S", "<", "OE", "Z",
            "'", "'", '"', '"', "*", "-", "--", "~", "TM", "s", ">", "oe", "z", "Y",
            '"', '"', "'", "'", "-", "-", "..."
        ];
        $str = str_replace($search, $replace, $str);
        $str = preg_replace('/[\x00-\x1F\x7F-\x9F]/u', '', $str);

        return trim($str);
    }

    public function export_sample_csv()
    {
        if (!$this->rbac->hasPrivilege('vehicle', 'can_view')) {
            access_denied();
        }
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=sample_transport_assignment.csv');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, array('admission_no', 'vehicle_no', 'pickup_point_name'));
        fputcsv($output, array('4333', 'RJ14 PA 1234', 'Main Gate'));
        fputcsv($output, array('4859', 'RJ14 PA 5678', 'Kailash Nagar'));
        fclose($output);
        exit();
    }

    public function preview_bulk_csv()
    {
        if (!$this->rbac->hasPrivilege('vehicle', 'can_edit') && !$this->rbac->hasPrivilege('vehicle', 'can_add')) {
            echo json_encode(['status' => 0, 'message' => 'Access Denied']);
            return;
        }

        if (!isset($_FILES['csv_file']['tmp_name']) || empty($_FILES['csv_file']['tmp_name'])) {
            echo json_encode(['status' => 0, 'message' => 'Please select a valid CSV file to upload.']);
            return;
        }

        $file = $_FILES['csv_file']['tmp_name'];
        $handle = fopen($file, 'r');
        if ($handle === false) {
            echo json_encode(['status' => 0, 'message' => 'Failed to open CSV file.']);
            return;
        }

        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            echo json_encode(['status' => 0, 'message' => 'CSV file is empty.']);
            return;
        }

        $header_clean = array_map(function($h) {
            return strtolower(trim(str_replace("\xEF\xBB\xBF", '', $h)));
        }, $header);

        $adm_idx = array_search('admission_no', $header_clean);
        $veh_idx = array_search('vehicle_no', $header_clean);
        if ($veh_idx === false) {
            $veh_idx = array_search('route_name', $header_clean);
        }
        $pickup_idx = array_search('pickup_point_name', $header_clean);

        if ($adm_idx === false || $veh_idx === false || $pickup_idx === false) {
            fclose($handle);
            echo json_encode(['status' => 0, 'message' => 'Invalid CSV headers. CSV must contain admission_no, vehicle_no (or route_name), and pickup_point_name columns.']);
            return;
        }

        $current_session = $this->setting_model->getCurrentSession();
        $preview_rows = [];
        $line_num = 1;
        $valid_count = 0;
        $invalid_count = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $line_num++;
            if (empty(array_filter($row))) {
                continue;
            }

            $admission_no  = isset($row[$adm_idx]) ? $this->clean_csv_string($row[$adm_idx]) : '';
            $vehicle_input = isset($row[$veh_idx]) ? $this->clean_csv_string($row[$veh_idx]) : '';
            $pickup_input  = isset($row[$pickup_idx]) ? $this->clean_csv_string($row[$pickup_idx]) : '';

            $item = [
                'line' => $line_num,
                'admission_no' => $admission_no,
                'vehicle_input' => $vehicle_input,
                'pickup_input' => $pickup_input,
                'student_name' => '-',
                'class_section' => '-',
                'student_id' => null,
                'student_session_id' => null,
                'class_id' => null,
                'vehroute_id' => null,
                'route_pickup_point_id' => null,
                'vehicle_no' => '-',
                'pickup_point_name' => '-',
                'is_valid' => false,
                'error_msg' => ''
            ];

            if (empty($admission_no)) {
                $item['error_msg'] = 'Missing admission number';
                $invalid_count++;
                $preview_rows[] = $item;
                continue;
            }

            $student = $this->db->query("
                SELECT s.id as student_id, s.firstname, s.lastname, ss.id as student_session_id, ss.class_id, c.class, sec.section 
                FROM students s 
                JOIN student_session ss ON ss.student_id = s.id 
                JOIN classes c ON c.id = ss.class_id 
                JOIN sections sec ON sec.id = ss.section_id 
                WHERE s.admission_no = ? AND ss.session_id = ? AND s.is_active = 'yes'
                LIMIT 1
            ", [$admission_no, $current_session])->row_array();

            if (empty($student)) {
                $item['error_msg'] = "Admission No '{$admission_no}' not found in active session";
                $invalid_count++;
                $preview_rows[] = $item;
                continue;
            }

            $item['student_id'] = $student['student_id'];
            $item['student_session_id'] = $student['student_session_id'];
            $item['class_id'] = $student['class_id'];
            $item['student_name'] = trim($student['firstname'] . ' ' . $student['lastname']);
            $item['class_section'] = $student['class'] . ' (' . $student['section'] . ')';

            if (empty($vehicle_input)) {
                $item['error_msg'] = "Missing vehicle number or route name";
                $invalid_count++;
                $preview_rows[] = $item;
                continue;
            }

            $veh_route_search = preg_replace('/[^a-zA-Z0-9\s\-\_\.\(\)\/]/', ' ', $vehicle_input);
            $veh_route_search = trim(preg_replace('/\s+/', ' ', $veh_route_search));

            $veh_route = $this->db->query("
                SELECT vr.id as vehroute_id, v.vehicle_no, r.route_title 
                FROM vehicle_routes vr 
                JOIN vehicles v ON v.id = vr.vehicle_id 
                JOIN transport_route r ON r.id = vr.route_id 
                WHERE v.vehicle_no = ? OR r.route_title = ? OR v.vehicle_no LIKE ? OR r.route_title LIKE ?
                LIMIT 1
            ", [$vehicle_input, $vehicle_input, '%'.$veh_route_search.'%', '%'.$veh_route_search.'%'])->row_array();

            if (empty($veh_route)) {
                $item['error_msg'] = "Vehicle/Route '{$vehicle_input}' not found";
                $invalid_count++;
                $preview_rows[] = $item;
                continue;
            }

            $item['vehroute_id'] = $veh_route['vehroute_id'];
            $item['vehicle_no'] = $veh_route['vehicle_no'] . ' (' . $veh_route['route_title'] . ')';

            if (empty($pickup_input)) {
                $item['error_msg'] = "Missing pickup point name";
                $invalid_count++;
                $preview_rows[] = $item;
                continue;
            }

            $pickup_search = preg_replace('/[^a-zA-Z0-9\s\-\_\.\(\)\/]/', ' ', $pickup_input);
            $pickup_search = trim(preg_replace('/\s+/', ' ', $pickup_search));

            $pickup = $this->db->query("
                SELECT rpp.id as route_pickup_point_id, pp.name as pickup_point 
                FROM route_pickup_point rpp 
                JOIN pickup_point pp ON pp.id = rpp.pickup_point_id 
                JOIN vehicle_routes vr ON vr.route_id = rpp.transport_route_id 
                WHERE vr.id = ? AND (pp.name = ? OR pp.name LIKE ?)
                LIMIT 1
            ", [$veh_route['vehroute_id'], $pickup_input, '%'.$pickup_search.'%'])->row_array();

            if (empty($pickup)) {
                $item['error_msg'] = "Pickup point '{$pickup_input}' not found for route";
                $invalid_count++;
                $preview_rows[] = $item;
                continue;
            }

            $item['route_pickup_point_id'] = $pickup['route_pickup_point_id'];
            $item['pickup_point_name'] = $pickup['pickup_point'];
            $item['is_valid'] = true;
            $valid_count++;

            $preview_rows[] = $item;
        }

        fclose($handle);

        echo json_encode([
            'status' => 1,
            'rows' => $preview_rows,
            'valid_count' => $valid_count,
            'invalid_count' => $invalid_count,
            'total_count' => count($preview_rows)
        ]);
    }

    public function confirm_bulk_csv()
    {
        if (!$this->rbac->hasPrivilege('vehicle', 'can_edit') && !$this->rbac->hasPrivilege('vehicle', 'can_add')) {
            echo json_encode(['status' => 0, 'message' => 'Access Denied']);
            return;
        }

        $raw_rows = $this->input->post('valid_rows');
        if (empty($raw_rows)) {
            echo json_encode(['status' => 0, 'message' => 'No valid rows to process.']);
            return;
        }

        $rows = is_string($raw_rows) ? json_decode($raw_rows, true) : $raw_rows;
        if (empty($rows) || !is_array($rows)) {
            echo json_encode(['status' => 0, 'message' => 'Invalid data payload.']);
            return;
        }

        $this->db->trans_begin();
        $processed_count = 0;

        foreach ($rows as $item) {
            if (empty($item['is_valid']) || empty($item['student_session_id']) || empty($item['vehroute_id']) || empty($item['route_pickup_point_id']) || empty($item['class_id'])) {
                continue;
            }

            $student_session_id = $item['student_session_id'];
            $vehroute_id = $item['vehroute_id'];
            $route_pickup_point_id = $item['route_pickup_point_id'];
            $class_id = $item['class_id'];

            // 1. Check if student has paid Yearly fees
            $paid_yearly_fees = $this->db->query("
                SELECT syf.id, syf.transport_yearly_feemaster_id 
                FROM student_transport_yearly_fees syf 
                JOIN student_fees_deposite sfd ON sfd.student_transport_yearly_fee_id = syf.id 
                WHERE syf.student_session_id = ?
            ", [$student_session_id])->result_array();

            // 2. Update Student Session Profile
            $this->db->where('id', $student_session_id);
            $this->db->update('student_session', [
                'vehroute_id' => $vehroute_id,
                'route_pickup_point_id' => $route_pickup_point_id
            ]);

            // 3. Safely Delete ALL UNPAID yearly fees for this student
            $this->db->where('student_session_id', $student_session_id);
            if (count($paid_yearly_fees) > 0) {
                $paid_fee_ids = array_column($paid_yearly_fees, 'id');
                $this->db->where_not_in('id', $paid_fee_ids);
            }
            $this->db->delete('student_transport_yearly_fees');

            // 4. Automatically fetch and assign new Yearly Fees based on Class and Pickup Point
            $yearly_fees = $this->transportyearlyfee_model->getApplicableYearlyFees($class_id, $route_pickup_point_id);
            
            if (!empty($yearly_fees)) {
                $used_paid_fee = false;
                foreach ($yearly_fees as $yf) {
                    if (count($paid_yearly_fees) > 0 && !$used_paid_fee) {
                        $this->db->where('id', $paid_yearly_fees[0]['id']);
                        $this->db->update('student_transport_yearly_fees', ['transport_yearly_feemaster_id' => $yf['id']]);
                        $used_paid_fee = true;
                    } else {
                        $insert_val = array(
                            'student_session_id' => $student_session_id,
                            'transport_yearly_feemaster_id' => $yf['id'],
                        );
                        $this->db->insert('student_transport_yearly_fees', $insert_val);
                    }
                }
            }

            $processed_count++;
        }

        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            echo json_encode(['status' => 0, 'message' => 'Database transaction error occurred.']);
        } else {
            $this->db->trans_commit();
            echo json_encode(['status' => 1, 'message' => "Successfully assigned {$processed_count} students to transport and updated fee structures!"]);
        }
    }

}
