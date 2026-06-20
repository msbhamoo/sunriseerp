<?php
$mysqli = new mysqli('localhost', 'root', '', 'smserp');
$res = $mysqli->query('SELECT * FROM sidebar_menus WHERE `lang_key` = "academics"');
while($row = $res->fetch_assoc()) {
    print_r($row);
}
?>
