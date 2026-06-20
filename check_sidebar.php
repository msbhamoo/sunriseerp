<?php
$mysqli = new mysqli('localhost', 'root', '', 'smserp');
$res = $mysqli->query('SELECT * FROM sidebar_sub_menus WHERE `key` LIKE "%substitut%"');
while($row = $res->fetch_assoc()) {
    print_r($row);
}
?>
