<?php
$path = 'c:\wamp64\www\lms\application\views\student\studentShow.php';
$content = file_get_contents($path);

// Insert the tab
$tabHTML = "                            <li class=\"\"><a href=\"#absentee_followup\" data-toggle=\"tab\" aria-expanded=\"true\"><?php echo (\$this->lang->line('absentee_followup') ? \$this->lang->line('absentee_followup') : 'Absentee Follow Up'); ?></a></li>\n";
$content = preg_replace('/(<li class=""><a href="#call_log".*?<\/a><\/li>)/i', "$1\n" . $tabHTML, $content);

// Insert the pane
$paneHTML = <<<EOD
                        <div class="tab-pane" id="absentee_followup">
                            <div class="sp2-card">
                                <div class="sp2-section-title"><i class="fa fa-phone"></i> <?php echo (\$this->lang->line('absentee_followup') ? \$this->lang->line('absentee_followup') : 'Absentee Follow Up'); ?></div>
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th><?php echo \$this->lang->line('date'); ?></th>
                                                <th><?php echo (\$this->lang->line('followup_status') ? \$this->lang->line('followup_status') : 'Status'); ?></th>
                                                <th><?php echo \$this->lang->line('remark'); ?></th>
                                                <th><?php echo \$this->lang->line('created_by'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (isset(\$absentee_followups) && !empty(\$absentee_followups)) { 
                                                foreach (\$absentee_followups as \$log) { ?>
                                                <tr>
                                                    <td><?php echo date(\$this->customlib->getSchoolDateFormat(), strtotime(\$log['date'])); ?></td>
                                                    <td><?php echo \$log['followup_status']; ?></td>
                                                    <td><?php echo \$log['remark']; ?></td>
                                                    <td><?php echo \$log['name'] . ' ' . \$log['surname'] . ' (' . \$log['employee_id'] . ')'; ?></td>
                                                </tr>
                                            <?php } } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
EOD;

$content = preg_replace('/(<div class="tab-pane" id="call_log">)/i', $paneHTML . "\n\n$1", $content);

file_put_contents($path, $content);
echo "Updated $path\n";
