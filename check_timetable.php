<?php
$mysqli = new mysqli('localhost', 'root', '', 'smserp');
$res = $mysqli->query('SELECT period_type, break_label FROM subject_timetable LIMIT 20');
while($row = $res->fetch_assoc()) {
    print_r($row);
}
?>
