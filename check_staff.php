<?php
$_SERVER['DOCUMENT_ROOT'] = 'c:/wamp64/www/lms';
// Load CI manually to simulate the controller, or just run the exact PHP that gets JSON encoded
$mysqli = new mysqli('localhost', 'root', '', 'smserp');

$date = '20 Jun 2026';
$timestamp = strtotime(str_replace('.', '-', $date)); // The customlib does this? Wait, customlib has datetostrtotime!

// Let's copy datetostrtotime logic:
$format = 'd M Y'; // e.g. "20 Jun 2026"
list($day, $month_str, $year) = explode(' ', $date);
$month = date('m', strtotime($month_str . " 1 2015"));
$formatted_date = $year . '-' . $month . '-' . $day;
$timestamp = strtotime($formatted_date);
$day_of_week = date('l', $timestamp);
echo "Date: $formatted_date, Day: $day_of_week\n";

$staff_id = 4;
$current_session = 21;

$sql = "SELECT `classes`.`class`,`sections`.`section`,`subject_group_subjects`.`subject_id`,`sub`.`name` as `subject_name`,`sub`.`code` as `subject_code`,`subject_timetable`.* FROM `subject_timetable` INNER JOIN `classes` on classes.id = `subject_timetable`.`class_id` INNER JOIN sections on `sections`.`id`=`subject_timetable`.`section_id` INNER JOIN `subject_group_subjects` on `subject_group_subjects`.`id`=`subject_timetable`.`subject_group_subject_id` INNER JOIN `subjects` as `sub` on `sub`.`id`=`subject_group_subjects`.`subject_id`  WHERE subject_timetable.staff_id=" . $staff_id . " and subject_timetable.session_id =" . $current_session . " and subject_timetable.day='" . $day_of_week . "' order by subject_timetable.start_time";

$res = $mysqli->query($sql);
$timetable = [];
while($row = $res->fetch_object()){
    $timetable[] = $row;
}

echo "Timetable rows: " . count($timetable) . "\n";
if (count($timetable) > 0) {
    echo $timetable[0]->subject_name . "\n";
} else {
    echo "No rows found.\n";
}
?>
