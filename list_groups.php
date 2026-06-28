<?php
$mysqli = new mysqli('localhost', 'root', '', 'smserp');
$res = $mysqli->query("SELECT id, name, short_code FROM permission_group ORDER BY id ASC");
while($row = $res->fetch_assoc()) {
    echo $row['id'] . " | " . $row['short_code'] . " | " . $row['name'] . "\n";
}
