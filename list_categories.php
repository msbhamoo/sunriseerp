<?php
$mysqli = new mysqli('localhost', 'root', '', 'smserp');
$res = $mysqli->query("SELECT id, perm_group_id, name, short_code FROM permission_category ORDER BY id ASC");
while($row = $res->fetch_assoc()) {
    if(stripos($row['name'], 'hostel') !== false || stripos($row['name'], 'cbse') !== false || stripos($row['name'], 'absentee') !== false || stripos($row['name'], 'teacher') !== false || stripos($row['name'], 'analytics') !== false) {
        echo $row['id'] . " | group: " . $row['perm_group_id'] . " | " . $row['short_code'] . " | " . $row['name'] . "\n";
    }
}
