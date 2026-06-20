<?php
$mysqli = new mysqli('localhost', 'root', '', 'smserp');
$res = $mysqli->query('SELECT menu, access_permissions FROM sidebar_sub_menus WHERE access_permissions NOT LIKE "(%" LIMIT 10');
while($row = $res->fetch_assoc()) {
    print_r($row);
}
?>
