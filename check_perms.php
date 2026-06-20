<?php
$mysqli = new mysqli('localhost', 'root', '', 'smserp');
$res = $mysqli->query('SELECT * FROM sidebar_sub_menus WHERE `sidebar_menu_id` = 14 LIMIT 2');
while($row = $res->fetch_assoc()) {
    print_r($row);
}
?>
