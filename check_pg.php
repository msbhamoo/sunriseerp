<?php
$mysqli = new mysqli('localhost', 'root', '', 'smserp');
$res = $mysqli->query('SELECT * FROM permission_group WHERE id = 1');
while($row = $res->fetch_assoc()) {
    print_r($row);
}
?>
