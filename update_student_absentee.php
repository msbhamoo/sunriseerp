<?php
$path = 'c:\wamp64\www\lms\application\controllers\Student.php';
$content = file_get_contents($path);
$content = preg_replace('/(\\["timeline_list"\] = \->timeline_model->getStudentTimeline\(\, \ = \'\'\);)/', "\n\n        \->load->model('absenteefollowup_model');\n        \['absentee_followups'] = \->absenteefollowup_model->getAllLogsByStudent(\['id']);", $content);
file_put_contents($path, $content);
