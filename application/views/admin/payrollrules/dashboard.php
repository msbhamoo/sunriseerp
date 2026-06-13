<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-sitemap"></i> <?php echo $this->lang->line('payroll_rules'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="box box-primary">
                    <div class="box-body box-profile">
                        <h3 class="profile-username text-center"><?php echo $this->lang->line('rule_engine'); ?></h3>
                        <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                                <b>Rule Groups</b> <a class="pull-right"><?php echo count($rule_groups); ?></a>
                            </li>
                        </ul>
                        <a href="<?php echo site_url('admin/payrollrules/simulate'); ?>" class="btn btn-primary btn-block"><i class="fa fa-play"></i> <?php echo $this->lang->line('simulate_payroll'); ?></a>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('rule_groups'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('description'); ?></th>
                                        <th>Rules</th>
                                        <th>Active Rules</th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rule_groups as $group) { ?>
                                        <tr>
                                            <td><?php echo $group['name']; ?></td>
                                            <td><?php echo $group['description']; ?></td>
                                            <td><?php echo $group['total_rules']; ?></td>
                                            <td><?php echo $group['active_rules']; ?></td>
                                            <td class="text-right">
                                                <a href="<?php echo site_url('admin/payrollrules/rules/'.$group['id']); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="View Rules">
                                                    <i class="fa fa-list"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
