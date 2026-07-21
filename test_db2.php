<?php
$conn = new mysqli('127.0.0.1', 'root', '', 'sunriseerp');
$res = $conn->query("SHOW COLUMNS FROM students");
$cols = [];
while($row = $res->fetch_assoc()){
    $cols[] = $row['Field'];
}
echo "Columns:\n";
echo implode(", ", $cols);
?>
