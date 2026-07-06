<?php
$mysqli = new mysqli('localhost', 'root', '', 'smserp');
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$result = $mysqli->query('SHOW COLUMNS FROM students');
while ($row = $result->fetch_assoc()) {
    if ($row['Field'] == 'is_staff_kid' || $row['Field'] == 'staff_id') {
        print_r($row);
    }
}
?>
