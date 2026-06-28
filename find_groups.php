<?php
$mysqli = new mysqli('localhost', 'root', '', 'smserp');
$res = $mysqli->query("SELECT id, name, short_code FROM permission_group WHERE id >= 1000");
while($row = $res->fetch_assoc()) {
    echo $row['id'] . " | " . $row['short_code'] . " | " . $row['name'] . "\n";
}
