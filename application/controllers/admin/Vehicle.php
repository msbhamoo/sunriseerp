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
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('vehicle', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Transport');
        $this->session->set_userdata('sub_menu', 'vehicle/index');
        $data['title']       = 'Add Vehicle';
        $listVehicle         = $this->vehicle_model->get();
        $data['listVehicle'] = $listVehicle;
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
        $vehicleid           = $this->input->post('vehicleid');
        $data['editvehicle'] = $this->vehicle_model->get($vehicleid);
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
        $vehicleid           = $this->input->post('vehicleid');
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
        echo json_encode(array('status' => 'success', 'message' => 'Updated successfully!'));
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

}
