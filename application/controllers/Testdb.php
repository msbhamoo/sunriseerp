<?php
class Testdb extends CI_Controller {
    public function index() {
        $this->load->database();
        if ($this->db->table_exists('acc_expense_types')) {
            $data = $this->db->get('acc_expense_types')->result_array();
            echo "acc_expense_types:<br>";
            foreach($data as $d) echo $d['id'] . " - " . $d['name'] . " (" . $d['type'] . ")<br>";
        } else {
            echo "acc_expense_types does not exist<br>";
        }
    }
}
