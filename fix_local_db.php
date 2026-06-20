<?php
$mysqli = new mysqli('localhost', 'root', '', 'smserp');
$mysqli->query("UPDATE `sidebar_sub_menus` SET `access_permissions` = '(\'class_timetable\', \'can_view\')', `activate_controller` = 'substitution', `activate_methods` = 'index', `permission_group_id` = NULL WHERE `key` = 'substitution_planning'");
$mysqli->query("UPDATE `sidebar_sub_menus` SET `access_permissions` = '(\'class_timetable\', \'can_view\')', `activate_controller` = 'substitution', `activate_methods` = 'history', `permission_group_id` = NULL WHERE `key` = 'substitution_history'");
echo "Done";
?>
