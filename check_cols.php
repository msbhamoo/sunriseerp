<?php
$mysqli = new mysqli('localhost', 'root', '', 'smserp');
$res = $mysqli->query('SELECT * FROM sidebar_sub_menus WHERE `menu` = "class_timetable" OR `key` = "substitution_planning"');
while($row = $res->fetch_assoc()) {
    print_r($row);
}
?>
