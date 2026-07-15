<?php
define('ENVIRONMENT', 'development');
require_once 'index.php';
$CI =& get_instance();
$result = $CI->student_model->searchFullText('samar');
print_r($result);
