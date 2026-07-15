<?php
$mysqli = new mysqli('localhost', 'root', '', 'smserpnew');
$res = $mysqli->query("SHOW TABLES LIKE 'student_%transport_fees'");
while($row = $res->fetch_array()) {
    echo $row[0] . "\n";
}
?>
