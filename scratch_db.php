<?php
define('ENVIRONMENT', 'development');
$system_path = 'system';
$application_folder = 'application';
if (($_temp = realpath($system_path)) !== FALSE) {
	$system_path = $_temp.DIRECTORY_SEPARATOR;
} else {
	$system_path = strtr(
		rtrim($system_path, '/\\'),
		'/\\',
		DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR
	).DIRECTORY_SEPARATOR;
}
define('BASEPATH', $system_path);
define('APPPATH', $application_folder.DIRECTORY_SEPARATOR);
require_once BASEPATH.'core/Config.php';
require_once APPPATH.'config/database.php';

$mysqli = new mysqli($db['default']['hostname'], $db['default']['username'], $db['default']['password'], $db['default']['database']);

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

$sql = "ALTER TABLE `custom_fields` ADD COLUMN `default_value` VARCHAR(255) NULL AFTER `field_values`";
if ($mysqli->query($sql) === TRUE) {
    echo "Column default_value added successfully\n";
} else {
    echo "Error adding column: " . $mysqli->error . "\n";
}

$mysqli->close();
?>
