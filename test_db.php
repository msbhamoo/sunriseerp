<?php
define('ENVIRONMENT', 'development');
$_SERVER['HTTP_HOST'] = 'localhost';
require_once 'index.php';
$CI =& get_instance();
$perms = $CI->db->query("SELECT * FROM permission_category WHERE short_code = 'transport_attendance'")->result_array();
print_r($perms);
