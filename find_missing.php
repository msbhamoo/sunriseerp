<?php
$mysqli = new mysqli('localhost', 'root', '', 'smserp');

// Query groups specifically for missing items
$res = $mysqli->query("SELECT * FROM permission_group WHERE name LIKE '%Hostel%' OR short_code LIKE '%hostel%' OR name LIKE '%CBSE%' OR name LIKE '%Student Registration%'");
echo "Groups:\n";
while($row = $res->fetch_assoc()) {
    print_r($row);
}
// Query categories for CBSE and Student Registration
$res = $mysqli->query("SELECT * FROM permission_category WHERE name LIKE '%CBSE%' OR name LIKE '%Student Registration%'");
echo "\nCategories:\n";
while($row = $res->fetch_assoc()) {
    print_r($row);
}
