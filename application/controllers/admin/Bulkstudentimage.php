<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Bulkstudentimage extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('SaasValidation');
        $this->load->library('media_storage');
        $this->config->load('app-config');
        $this->load->model(array("student_model", "setting_model"));
        $this->sch_setting_detail = $this->setting_model->getSetting();
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('import_student', 'can_view')) {
            access_denied();
        }
        $data['title'] = 'Bulk Student Image Upload';
        $this->load->view('layout/header', $data);
        $this->load->view('admin/bulkstudentimage/upload', $data);
        $this->load->view('layout/footer', $data);
    }

    public function handle_zip_upload()
    {
        $error = "";
        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
            $allowedExts = array('zip');
            $temp        = explode(".", $_FILES["file"]["name"]);
            $extension   = end($temp);
            if ($_FILES["file"]["error"] > 0) {
                $error .= "Error opening the file<br />";
            }
            if (!in_array(strtolower($extension), $allowedExts)) {
                $error .= "Error uploading file, please ensure you are uploading a ZIP file.<br />";
            }
        } else {
            $error .= "Please select a ZIP file to upload.<br />";
        }

        if ($error == "") {
            return true;
        }
        $this->form_validation->set_message('handle_zip_upload', $error);
        return false;
    }

    public function upload()
    {
        if (!$this->rbac->hasPrivilege('import_student', 'can_view')) {
            access_denied();
        }

        $this->form_validation->set_rules('file', 'ZIP File', 'callback_handle_zip_upload');

        if ($this->form_validation->run() == false) {
            $data['title'] = 'Bulk Student Image Upload';
            $this->load->view('layout/header', $data);
            $this->load->view('admin/bulkstudentimage/upload', $data);
            $this->load->view('layout/footer', $data);
        } else {
            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                
                // Create temp folder if not exists
                $temp_base = './uploads/temp/';
                if (!is_dir($temp_base)) {
                    mkdir($temp_base, 0777, true);
                }
                
                $folder_name = "bulk_images_" . time();
                $extract_path = $temp_base . $folder_name . '/';
                
                if (!is_dir($extract_path)) {
                    mkdir($extract_path, 0777, true);
                }
                
                $destination = $temp_base . "upload_" . time() . ".zip";
                if (!move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Failed to upload the ZIP file. Please try again.</div>');
                    redirect('admin/bulkstudentimage/index');
                }

                $zip = new ZipArchive;
                $res = $zip->open($destination);
                if ($res === TRUE) {
                    $zip->extractTo($extract_path);
                    $zip->close();
                    
                    // delete zip file
                    @unlink($destination);
                } else {
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Failed to open the ZIP file. Ensure it is a valid zip archive.</div>');
                    redirect('admin/bulkstudentimage/index');
                }

                $valid_extensions = array('jpg', 'jpeg', 'png', 'gif', 'webp');
                
                $admission_nos = array();
                $file_map = array(); // filename => absolute path
                
                try {
                    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($extract_path, RecursiveDirectoryIterator::SKIP_DOTS));
                    foreach ($iterator as $file) {
                        if ($file->isDir()) continue;
                        
                        $ext = strtolower($file->getExtension());
                        if (in_array($ext, $valid_extensions)) {
                            $admission_no = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                            $admission_nos[] = $admission_no;
                            $file_map[$admission_no] = array(
                                'file' => $file->getFilename(),
                                'path' => $file->getPathname(),
                                'ext'  => $ext,
                                'size' => $file->getSize()
                            );
                        }
                    }
                } catch (Exception $e) {
                    $this->_delete_dir($extract_path);
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Error reading extracted files.</div>');
                    redirect('admin/bulkstudentimage/index');
                }
                
                if (empty($admission_nos)) {
                    $this->_delete_dir($extract_path);
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">No valid images found in the ZIP file. Images must be at the root of the ZIP.</div>');
                    redirect('admin/bulkstudentimage/index');
                }
                
                $students = $this->student_model->getStudentsByAdmissionNos($admission_nos);
                
                $matched = array();
                $unmatched = array();
                
                $found_admissions = array();
                foreach ($students as $student) {
                    $found_admissions[] = $student['admission_no'];
                    $file_info = $file_map[$student['admission_no']];
                    
                    $matched[] = array(
                        'student_id' => $student['id'],
                        'admission_no' => $student['admission_no'],
                        'name' => $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $this->sch_setting_detail->middlename, $this->sch_setting_detail->lastname),
                        'old_image' => $student['image'],
                        'new_file' => $file_info['file'],
                        'new_file_path' => $file_info['path'],
                        'new_file_ext' => $file_info['ext']
                    );
                }
                
                foreach ($file_map as $adm_no => $file_info) {
                    if (!in_array($adm_no, $found_admissions)) {
                        $unmatched[] = array(
                            'file' => $file_info['file'],
                            'admission_no' => $adm_no
                        );
                    }
                }
                
                $session_data = array(
                    'bulk_image_temp_folder' => $extract_path,
                    'bulk_image_matched' => $matched,
                    'bulk_image_unmatched' => $unmatched
                );
                
                $this->session->set_userdata($session_data);
                redirect('admin/bulkstudentimage/preview');
            }
        }
    }

    public function preview()
    {
        if (!$this->rbac->hasPrivilege('import_student', 'can_view')) {
            access_denied();
        }

        $matched = $this->session->userdata('bulk_image_matched');
        $unmatched = $this->session->userdata('bulk_image_unmatched');
        
        if ($matched === NULL) {
            redirect('admin/bulkstudentimage/index');
        }
        
        $data['title'] = 'Preview Bulk Image Upload';
        $data['matched'] = $matched;
        $data['unmatched'] = $unmatched;
        
        $this->load->view('layout/header', $data);
        $this->load->view('admin/bulkstudentimage/preview', $data);
        $this->load->view('layout/footer', $data);
    }

    public function confirm()
    {
        if (!$this->rbac->hasPrivilege('import_student', 'can_add')) {
            access_denied();
        }

        $matched = $this->session->userdata('bulk_image_matched');
        $temp_folder = $this->session->userdata('bulk_image_temp_folder');
        
        if ($matched === NULL) {
            redirect('admin/bulkstudentimage/index');
        }

        if ($this->input->post('cancel')) {
            $this->_delete_dir($temp_folder);
            $this->session->unset_userdata(array('bulk_image_matched', 'bulk_image_unmatched', 'bulk_image_temp_folder'));
            redirect('admin/bulkstudentimage/index');
        }

        $success_count = 0;
        $failed_count = 0;
        $upload_dir = $this->customlib->getFolderPath() . './uploads/student_images/';
        
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        foreach ($matched as $match) {
            $source_file = $match['new_file_path'];
            $ext = $match['new_file_ext'];
            
            if (file_exists($source_file)) {
                // Mimic Media_storage filename logic
                $saved_name = time() . "-" . uniqid(rand()) . "!" . $match['new_file'];
                $destination = rtrim($upload_dir, '/') . '/' . $saved_name;
                
                if (copy($source_file, $destination)) {
                    $update_data = array(
                        'image' => 'uploads/student_images/' . $saved_name
                    );
                    
                    // We don't delete the old file to mimic existing individual upload behavior
                    $this->student_model->studentUpdateByID($match['student_id'], $update_data);
                    $success_count++;
                } else {
                    $failed_count++;
                }
            } else {
                $failed_count++;
            }
        }
        
        // Cleanup temp files
        $this->_delete_dir($temp_folder);
        $this->session->unset_userdata(array('bulk_image_matched', 'bulk_image_unmatched', 'bulk_image_temp_folder'));
        
        $this->session->set_flashdata('bulk_image_result', array(
            'success' => $success_count,
            'failed'  => $failed_count
        ));
        
        redirect('admin/bulkstudentimage/result');
    }

    public function result()
    {
        if (!$this->rbac->hasPrivilege('import_student', 'can_view')) {
            access_denied();
        }
        
        $result = $this->session->flashdata('bulk_image_result');
        if (!$result) {
            redirect('admin/bulkstudentimage/index');
        }
        
        $data['title'] = 'Bulk Image Upload Result';
        $data['result'] = $result;
        
        $this->load->view('layout/header', $data);
        $this->load->view('admin/bulkstudentimage/result', $data);
        $this->load->view('layout/footer', $data);
    }
    
    private function _delete_dir($dirPath) {
        if (!is_dir($dirPath)) {
            return;
        }
        $files = scandir($dirPath);
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') continue;
            $filePath = rtrim($dirPath, '/') . '/' . $file;
            if (is_dir($filePath)) {
                $this->_delete_dir($filePath);
            } else {
                @unlink($filePath);
            }
        }
        @rmdir($dirPath);
    }
}
