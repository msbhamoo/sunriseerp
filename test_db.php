<?php
$mysqli = new mysqli('localhost', 'root', '', 'smserp');
$res = $mysqli->query('SELECT date_format FROM sch_settings');
$row = $res->fetch_assoc();
echo $row['date_format'];
?>
