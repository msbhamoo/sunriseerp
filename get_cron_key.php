<?php
define('ENVIRONMENT', 'development');
$_SERVER['HTTP_HOST'] = 'localhost';
define('BASEPATH', '1');
require_once 'application/config/database.php';

$mysqli = new mysqli($db['default']['hostname'], $db['default']['username'], $db['default']['password'], $db['default']['database']);
$r = $mysqli->query("SELECT cron_secret_key FROM sch_settings LIMIT 1");
$row = $r->fetch_assoc();
echo $row['cron_secret_key'];
$mysqli->close();
?>
