<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Testdata extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function index() {
        $q = $this->db->query("SELECT * FROM sidebar_sub_menus WHERE lang_key LIKE '%student%' OR lang_key LIKE '%dashboard%' OR lang_key LIKE '%register%' OR lang_key LIKE '%certificate%' OR lang_key LIKE '%scholar%'");
        echo json_encode($q->result_array(), JSON_PRETTY_PRINT);
    }
}
