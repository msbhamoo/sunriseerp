<?php
$mysqli = new mysqli('localhost', 'root', '', 'smserp');
$res = $mysqli->query("ALTER TABLE staff_substitutions ADD COLUMN substitute_subject_id INT NULL DEFAULT NULL AFTER substitute_staff_id");
if ($res) echo "Column added successfully";
else echo "Error: " . $mysqli->error;
?>
