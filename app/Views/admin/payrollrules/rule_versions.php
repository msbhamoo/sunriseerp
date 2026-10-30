<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-sitemap"></i> <?php echo $this->lang->line('payroll_rules'); ?> - Version History</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Version History: <?php echo $rule['name']; ?> (<?php echo $rule['code']; ?>)</h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo site_url('admin/payrollrules/rules/'.$rule['rule_group_id']); ?>" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Back to Rules</a>
                        </div>
                    </div>
                    <div class="box-body">
                        <ul class="timeline">
                            <?php foreach ($versions as $version) { ?>
                                <li>
                                    <i class="fa fa-file-text-o bg-blue"></i>
                                    <div class="timeline-item">
                                        <span class="time"><i class="fa fa-clock-o"></i> <?php echo date('d-m-Y h:i A', strtotime($version['created_at'])); ?></span>
                                        <h3 class="timeline-header">Version <?php echo $version['version']; ?></h3>
                                        <div class="timeline-body">
                                            <p><b>Change Reason:</b> <?php echo $version['change_reason'] ?: 'Rule updated'; ?></p>
                                            <button class="btn btn-primary btn-xs" type="button" data-toggle="collapse" data-target="#snapshot_<?php echo $version['id']; ?>" aria-expanded="false">
                                                View Snapshot Details
                                            </button>
                                            <div class="collapse" id="snapshot_<?php echo $version['id']; ?>">
                                                <div class="well mt-3">
                                                    <?php
                                                    $snapshot = json_decode($version['rule_snapshot'], true);
                                                    if ($snapshot) {
                                                        echo "<h4>Conditions</h4><ul>";
                                                        if (!empty($snapshot['conditions'])) {
                                                            foreach ($snapshot['conditions'] as $cond) {
                                                                echo "<li>IF {$cond['field']} {$cond['operator']} {$cond['value']}</li>";
                                                            }
                                                        } else {
                                                            echo "<li>None</li>";
                                                        }
                                                        echo "</ul><h4>Actions</h4><ul>";
                                                        if (!empty($snapshot['actions'])) {
                                                            foreach ($snapshot['actions'] as $act) {
                                                                echo "<li>THEN {$act['action_type']} on {$act['target_field']} with value {$act['value']}</li>";
                                                            }
                                                        } else {
                                                            echo "<li>None</li>";
                                                        }
                                                        echo "</ul>";
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php } ?>
                            <li>
                                <i class="fa fa-clock-o bg-gray"></i>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
