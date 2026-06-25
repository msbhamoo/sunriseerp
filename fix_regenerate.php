<?php
$path = 'c:\wamp64\www\lms\application\controllers\cbseexam\Cbseadmitcardbulk.php';
$content = file_get_contents($path);

$content = preg_replace('/foreach \(\ as \\) \{\s*if \(empty\(\->roll_no\).*?\{\s*\->db->update\(\'cbse_exam_students\', \[\'roll_no\' => \\], \[\'id\' => \->id\]\);\s*\\+\+;\s*\}\s*\}/is', "foreach (\ as \) {\n            \->db->update('cbse_exam_students', ['roll_no' => \], ['id' => \->id]);\n            \++;\n        }", $content);

file_put_contents($path, $content);
