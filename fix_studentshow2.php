<?php
$path = 'c:\wamp64\www\lms\application\views\student\studentShow.php';
$content = file_get_contents($path);

// First remove any existing absentee_followup tabs
$content = preg_replace('/<li class=""><a href="#absentee_followup".*?<\/a><\/li>\s*/is', '', $content);

// Then add it after the call_log if block ends
$content = preg_replace('/(<\?php if \(\$this->rbac->hasPrivilege\(\'student_call_log\', \'can_view\'\)\) \{ \?>.*?<\?php \} \?>)/is', "$1\n                        <?php if (\$this->rbac->hasPrivilege('absentee_followup', 'can_view')) { ?>\n                            <li class=\"\"><a href=\"#absentee_followup\" data-toggle=\"tab\" aria-expanded=\"true\"><?php echo (\$this->lang->line('absentee_followup') ? \$this->lang->line('absentee_followup') : 'Absentee Follow Up'); ?></a></li>\n                        <?php } ?>", $content);

file_put_contents($path, $content);
