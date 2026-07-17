<?php
define('ENVIRONMENT', 'development');
$_SERVER['HTTP_HOST'] = 'localhost';
define('BASEPATH', '1');
require_once 'application/config/database.php';

$mysqli = new mysqli($db['default']['hostname'], $db['default']['username'], $db['default']['password'], $db['default']['database']);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$sql = "INSERT IGNORE INTO system_notification_setting (type, is_active) VALUES ('vehicle_alerts', 'yes')";

if ($mysqli->query($sql) === TRUE) {
    echo "Setting 'vehicle_alerts' added successfully\n";
} else {
    echo "Error inserting setting: " . $mysqli->error . "\n";
}

$mysqli->close();
?>
