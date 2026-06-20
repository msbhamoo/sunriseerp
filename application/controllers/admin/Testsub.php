<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Testsub extends Admin_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('subjecttimetable_model');
    }
    public function index() {
        $staff_id = 4;
        $day_of_week = 'Saturday';
        $timetable = $this->subjecttimetable_model->getByStaffandDay($staff_id, $day_of_week);
        echo "Timetable size: " . count($timetable) . "\n";
        print_r($timetable);
    }
}
