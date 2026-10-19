<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-sitemap"></i> <?php echo $this->lang->line('payroll_rules'); ?> - <?php echo $group['name']; ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Rules for <?php echo $group['name']; ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('payroll_rules', 'can_add')) { ?>
                                <a href="<?php echo site_url('admin/payrollrules/addrule/'.$group['id']); ?>" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_rule'); ?></a>
                            <?php } ?>
                            <a href="<?php echo site_url('admin/payrollrules'); ?>" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('rule_priority'); ?></th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th><?php echo $this->lang->line('code'); ?></th>
                                        <th><?php echo $this->lang->line('rule_version'); ?></th>
                                        <th>Active</th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rules as $rule) { ?>
                                        <tr>
                                            <td><?php echo $rule['priority']; ?></td>
                                            <td><?php echo $rule['name']; ?></td>
                                            <td><?php echo $rule['code']; ?></td>
                                            <td>V<?php echo $rule['version']; ?></td>
                                            <td>
                                                <div class="material-switch">
                                                    <input id="rule_<?php echo $rule['id']; ?>" name="rule_<?php echo $rule['id']; ?>" type="checkbox" class="chk" data-rowid="<?php echo $rule['id']; ?>" <?php echo $rule['is_active'] ? 'checked' : ''; ?> />
                                                    <label for="rule_<?php echo $rule['id']; ?>" class="label-success"></label>
                                                </div>
                                            </td>
                                            <td class="text-right">
                                                <a href="<?php echo site_url('admin/payrollrules/versions/'.$rule['id']); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="Versions">
                                                    <i class="fa fa-history"></i>
                                                </a>
                                                <?php if ($this->rbac->hasPrivilege('payroll_rules', 'can_edit')) { ?>
                                                    <a href="<?php echo site_url('admin/payrollrules/addrule/'.$group['id'].'/'.$rule['id']); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                <?php } ?>
                                                <?php if ($this->rbac->hasPrivilege('payroll_rules', 'can_delete')) { ?>
                                                    <a href="<?php echo site_url('admin/payrollrules/deleterule/'.$rule['id']); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('Are you sure you want to delete this rule?');">
                                                        <i class="fa fa-trash"></i>
                                                    </a>
                                                <?php } ?>
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

<script type="text/javascript">
    $(document).ready(function() {
        $('.chk').change(function() {
            var status = $(this).is(':checked') ? 1 : 0;
            var id = $(this).data('rowid');
            $.ajax({
                type: 'POST',
                url: base_url + 'admin/payrollrules/togglerule/' + id,
                data: {'status': status},
                dataType: 'json',
                success: function(data) {
                    if (data.status === 'success') {
                        successMsg('Rule status updated successfully');
                    }
                }
            });
        });
    });
</script>
