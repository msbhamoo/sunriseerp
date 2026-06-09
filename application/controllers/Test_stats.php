<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Test_stats extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('studentfeemaster_model');
    }

    public function index()
    {
        try {
            $stats = $this->studentfeemaster_model->getFeesDashboardStats();
            echo "Success!";
            echo "<pre>";
            print_r($stats);
            echo "</pre>";
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
