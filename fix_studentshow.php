<?php
$path = 'c:\wamp64\www\lms\application\views\student\studentShow.php';
$content = file_get_contents($path);

// Remove the incorrect tab injection
$content = str_replace("                            <li class=\"\"><a href=\"#absentee_followup\" data-toggle=\"tab\" aria-expanded=\"true\"><?php echo (\->lang->line('absentee_followup') ? \->lang->line('absentee_followup') : 'Absentee Follow Up'); ?></a></li>\n", "", $content);

// Inject correctly after the call_log if block ends
$content = preg_replace('/(<\?php if \(\->rbac->hasPrivilege\(\'student_call_log\', \'can_view\'\)\) \{ \?>.*?<\?php \} \?>)/is', "\n                        <?php if (\->rbac->hasPrivilege('absentee_followup', 'can_view')) { ?>\n                            <li class=\"\"><a href=\"#absentee_followup\" data-toggle=\"tab\" aria-expanded=\"true\"><?php echo (\->lang->line('absentee_followup') ? \->lang->line('absentee_followup') : 'Absentee Follow Up'); ?></a></li>\n                        <?php } ?>", $content);

file_put_contents($path, $content);
