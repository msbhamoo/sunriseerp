<?php
$mysqli = new mysqli('localhost', 'root', '', 'smserp');
$res = $mysqli->query("DESCRIBE sidebar_sub_menus");
while($row = $res->fetch_assoc()) {
    print_r($row);
}
