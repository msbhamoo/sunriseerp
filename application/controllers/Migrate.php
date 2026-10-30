<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Migrate extends CI_Controller
{

    public function index()
    {
        $this->load->library('migration');

        echo "Database updated successfully.";
    }

}
