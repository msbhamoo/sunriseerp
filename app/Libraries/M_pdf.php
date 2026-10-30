<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

require_once APPPATH . "third_party/omnipay/vendor/autoload.php";

use Mpdf\Mpdf;

class M_pdf
{
    public $pdf;

    public function __construct()
    {
        ini_set("pcre.backtrack_limit", "10000000");
        $CI = &get_instance();
        log_message('debug', 'mPDF class is loaded.');
        $this->pdf = $this->load();
    }

    public function load($param = NULL)
    {
        if($param ==NULL){
            return  new Mpdf([
                'tempDir' => __DIR__ . '/../tmp',
                'mode' => 'utf-8',
                'default_font' => 'roboto',
                'margin_left' => 2,
                'margin_right' => 2,
                'margin_top' => 2,
                'margin_bottom' => 2,
                'format' => 'Legal'
            ]);
        }else{
            return  new Mpdf($param);  
        }
      
    }
}
