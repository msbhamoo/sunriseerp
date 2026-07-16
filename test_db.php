<?php
define('ENVIRONMENT', 'development');
$_SERVER['HTTP_HOST'] = 'localhost';
require_once 'index.php';
$CI =& get_instance();

// Daily Bus Summary fix
$CI->db->query("UPDATE sidebar_sub_menus SET lang_key = 'daily_bus_summary', activate_controller = 'transportattendance', activate_methods = 'daily_summary', menu = 'daily_bus_summary' WHERE `key` = 'daily_bus_summary'");

// Monthly Bus Summary fix
$CI->db->query("UPDATE sidebar_sub_menus SET lang_key = 'monthly_bus_summary', activate_controller = 'transportattendance', activate_methods = 'monthly_summary', menu = 'monthly_bus_summary' WHERE `key` = 'monthly_bus_summary'");

echo "Fixed!";

