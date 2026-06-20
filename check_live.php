<?php
$mysqli = new mysqli('localhost', 'root', '', 'smserp');
$res = $mysqli->query('SELECT * FROM sidebar_sub_menus WHERE `key` = "substitution_planning" OR `key` = "substitution_history"');
while($row = $res->fetch_assoc()) {
    print_r($row);
}
?>
