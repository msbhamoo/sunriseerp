<?php
$m = new mysqli('127.0.0.1', 'root', '', 'schoolsms', 3307);
if ($m->connect_errno) {
    echo "Connect failed: " . $m->connect_error . "\n";
    exit;
}
$res = $m->query("SHOW COLUMNS FROM questions");
while ($row = $res->fetch_assoc()) {
    echo $row['Field'] . " (" . $row['Type'] . ")\n";
}
