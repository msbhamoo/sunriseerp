<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Feeimport extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('feeimport_model');
        if (!$this->rbac->hasPrivilege('fee_import', 'can_view')) {
            access_denied();
        }
    }

    public function index()
    {
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'historical_fee_import');
        
        $data['title'] = 'Historical Fee Import';
        $data['batches'] = $this->feeimport_model->getBatchHistory();
        
        $this->load->view('layout/header', $data);
        $this->load->view('feeimport/index', $data);
        $this->load->view('layout/footer', $data);
    }

    public function download_template()
    {
        $this->load->helper('download');
        $csv_data = "AdmissionNo,FeeCategory,PaymentDate(YYYY-MM-DD),NetAmount,DiscountAmount,FineAmount,PaymentMode,ReferenceNo\n";
        $csv_data .= "4587,TUTION FEE,2026-04-15,25000,0,0,Cash,\n";
        $csv_data .= "4587,Transport Fees,2026-04-15,5000,0,0,UPI,TXN123456\n";
        force_download("historical_fee_import_template.csv", $csv_data);
    }

    public function upload()
    {
        if (!$this->rbac->hasPrivilege('fee_import', 'can_add')) {
            access_denied();
        }

        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
            $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            if ($ext == 'csv') {
                $file = $_FILES['file']['tmp_name'];
                $batch_code = 'IMP-' . date('YmdHis');
                
                $data = [
                    'batch_code' => $batch_code,
                    'description' => $this->input->post('description'),
                    'session_id' => $this->setting_model->getCurrentSession(),
                    'imported_by' => $this->customlib->getStaffID(),
                    'status' => 'draft'
                ];
                
                $batch_id = $this->feeimport_model->createBatch($data);
                if ($batch_id) {
                    $result = $this->feeimport_model->parseAndValidateCSV($batch_id, $file);
                    if ($result['status'] == 'success') {
                        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">' . $this->lang->line('success_message') . '</div>');
                        redirect('feeimport/preview/' . $batch_id);
                    } else {
                        $this->feeimport_model->deleteBatch($batch_id); // Cleanup on error
                        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">' . $result['message'] . '</div>');
                        redirect('feeimport/index');
                    }
                }
            } else {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Please upload a valid CSV file.</div>');
                redirect('feeimport/index');
            }
        }
        redirect('feeimport/index');
    }

    public function preview($batch_id)
    {
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'historical_fee_import');
        
        $batch = $this->feeimport_model->getBatch($batch_id);
        if (!$batch || ($batch->status != 'draft' && $batch->status != 'previewed')) {
            redirect('feeimport/index');
        }

        $data['title'] = 'Preview Import Batch';
        $data['batch'] = $batch;
        $data['rows'] = $this->feeimport_model->getBatchDetail($batch_id);
        
        // Count stats
        $data['total'] = count($data['rows']);
        $data['ready'] = 0;
        $data['errors'] = 0;
        $data['total_amount'] = 0;
        foreach ($data['rows'] as $row) {
            if ($row->status == 'matched') {
                $data['ready']++;
                $data['total_amount'] += $row->net_amount;
            } else if ($row->status == 'failed') {
                $data['errors']++;
            }
        }

        $this->load->view('layout/header', $data);
        $this->load->view('feeimport/preview', $data);
        $this->load->view('layout/footer', $data);
    }

    public function execute($batch_id)
    {
        if (!$this->rbac->hasPrivilege('fee_import', 'can_add')) {
            access_denied();
        }

        $batch = $this->feeimport_model->getBatch($batch_id);
        if (!$batch || $batch->status != 'draft') {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Invalid batch or already processed.</div>');
            redirect('feeimport/index');
        }

        $result = $this->feeimport_model->executeBatch($batch_id);
        
        if ($result['status'] == 'success') {
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Import completed successfully. Imported ' . $result['count'] . ' rows.</div>');
            redirect('feeimport/batch_detail/' . $batch_id);
        } else {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Import failed: ' . $result['message'] . '</div>');
            redirect('feeimport/preview/' . $batch_id);
        }
    }

    public function batch_detail($batch_id)
    {
        $this->session->set_userdata('top_menu', 'Fees Collection');
        $this->session->set_userdata('sub_menu', 'historical_fee_import');
        
        $batch = $this->feeimport_model->getBatch($batch_id);
        if (!$batch) {
            redirect('feeimport/index');
        }

        $data['title'] = 'Batch Detail';
        $data['batch'] = $batch;
        $data['rows'] = $this->feeimport_model->getBatchDetail($batch_id);

        $this->load->view('layout/header', $data);
        $this->load->view('feeimport/batch_detail', $data);
        $this->load->view('layout/footer', $data);
    }

    public function revert($batch_id)
    {
        if (!$this->rbac->hasPrivilege('fee_import', 'can_delete')) {
            access_denied();
        }

        $batch = $this->feeimport_model->getBatch($batch_id);
        if (!$batch || $batch->status != 'imported') {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Invalid batch or cannot be reverted.</div>');
            redirect('feeimport/index');
        }

        $result = $this->feeimport_model->revertBatch($batch_id);
        
        if ($result['status'] == 'success') {
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Batch reverted successfully. All records have been removed.</div>');
        } else {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Revert failed: ' . $result['message'] . '</div>');
        }
        redirect('feeimport/batch_detail/' . $batch_id);
    }
}
