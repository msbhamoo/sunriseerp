<?php
$file = 'application/language/English/app_files/system_lang.php';
$content = "\n\$lang['substitution_planning'] = \"Substitute Planning\";\n\$lang['substitution_history'] = \"Substitution History\";\n";
file_put_contents($file, $content, FILE_APPEND);
echo "Done";
?>
