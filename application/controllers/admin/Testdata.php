<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Testdata extends Admin_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $q = $this->db->query("SELECT * FROM sidebar_sub_menus WHERE lang_key LIKE '%student%' OR lang_key LIKE '%dashboard%' OR lang_key LIKE '%register%' OR lang_key LIKE '%certificate%' OR lang_key LIKE '%scholar%'");
        echo "<pre>";
        print_r($q->result_array());
        echo "</pre>";
    }
}
