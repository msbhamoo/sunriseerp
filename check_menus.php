<?php
$mysqli = new mysqli("localhost", "root", "", "smserp");
if ($mysqli->connect_errno) {
    die("Connection failed: " . $mysqli->connect_error);
}

$res = $mysqli->query("SHOW COLUMNS FROM sidebar_sub_menus;");
echo "sidebar_sub_menus columns:\n";
while ($row = $res->fetch_assoc()) {
    echo $row['Field'] . " - " . $row['Type'] . "\n";
}

$mysqli->close();
?>
