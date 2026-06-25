<?php
$path = 'c:\wamp64\www\lms\application\controllers\Student.php';
$content = file_get_contents($path);
$content = str_replace("getAllLogsByStudent($studentSession['id']);", "getAllLogsByStudent($student['student_session_id']);", $content);
file_put_contents($path, $content);
