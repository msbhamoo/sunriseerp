<?php
$path = 'c:\wamp64\www\lms\application\controllers\Student.php';
$content = file_get_contents($path);
$content = str_replace("\$data['category_list']  = \$this->category_model->get();", "\$data['category_list']  = \$this->category_model->get();\n        \$data['religion_list'] = \$this->religion_model->get();\n        \$data['cast_list'] = \$this->cast_model->get();", $content);
$content = str_replace("\$data['categorylist'] = \$this->category_model->get();", "\$data['categorylist'] = \$this->category_model->get();\n        \$data['religion_list'] = \$this->religion_model->get();\n        \$data['cast_list'] = \$this->cast_model->get();", $content);

// For absentee followup
$content = preg_replace('/(\$data\["timeline_list"\] = \$this->timeline_model->getStudentTimeline\(\$id, \$status = \'\'\);)/', "$1\n\n        \$this->load->model('absenteefollowup_model');\n        \$data['absentee_followups'] = \$this->absenteefollowup_model->getAllLogsByStudent(\$studentSession['id']);", $content);

file_put_contents($path, $content);
