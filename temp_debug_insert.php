<?php
$code = file_get_contents('c:/wamp64/www/lms/application/controllers/Student.php');
$search = "\$data['staff_id'] = null;\n                    }\n                }";
$replace = $search . "\n                file_put_contents('c:/wamp64/www/lms/temp_debug.txt', print_r(\$_POST, true) . print_r(\$data, true));\n";
$code = str_replace($search, $replace, $code);
file_put_contents('c:/wamp64/www/lms/application/controllers/Student.php', $code);
echo "Replaced in Student.php";
?>
