<?php
$mysqli = new mysqli('localhost', 'root', '', 'smserp');
$res = $mysqli->query("SELECT id, name, short_code FROM permission_group WHERE name = 'Student Information'");
print_r($res->fetch_assoc());
