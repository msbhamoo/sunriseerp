<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Setting extends MY_Addon_CBSEController {

    function __construct() {
        parent::__construct();
    }

    public function index() 
    {
        if ($this->auth->addonchk('sscbse', false)) {
            if ($this->rbac->hasPrivilege('cbse_exam_category', 'can_view')) {
                redirect('cbseexam/cbsecategory/index');
            } elseif ($this->rbac->hasPrivilege('cbse_exam_grade', 'can_view')) {
                redirect('cbseexam/grade/gradelist');
            } elseif ($this->rbac->hasPrivilege('cbse_exam_assessment', 'can_view')) {
                redirect('cbseexam/assessment');
            } elseif ($this->rbac->hasPrivilege('cbse_exam_term', 'can_view')) {
                redirect('cbseexam/term/index');
            }
        }
        
        $data['version'] = $this->config->item('version');
        $this->load->view('layout/header');
        $this->load->view('cbseexam/setting',$data);
        $this->load->view('layout/footer');
    }   

    
}