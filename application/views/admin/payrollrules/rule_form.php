<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-sitemap"></i> <?php echo $this->lang->line('payroll_rules'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $rule ? $this->lang->line('edit_rule') : $this->lang->line('add_rule'); ?></h3>
                    </div>
                    <form id="rule_form" method="post" action="<?php echo site_url('admin/payrollrules/saverule'); ?>">
                        <input type="hidden" name="rule_group_id" value="<?php echo $group_id; ?>" />
                        <input type="hidden" name="id" value="<?php echo $rule ? $rule['id'] : ''; ?>" />
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('rule_name'); ?> <small class="req"> *</small></label>
                                        <input type="text" name="name" class="form-control" value="<?php echo $rule ? $rule['name'] : ''; ?>" required />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('code'); ?> <small class="req"> *</small></label>
                                        <input type="text" name="code" class="form-control" value="<?php echo $rule ? $rule['code'] : ''; ?>" required />
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('rule_priority'); ?></label>
                                        <input type="number" name="priority" class="form-control" value="<?php echo $rule ? $rule['priority'] : 100; ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('description'); ?></label>
                                        <input type="text" name="description" class="form-control" value="<?php echo $rule ? $rule['description'] : ''; ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Applies To (Roles)</label>
                                        <select name="applies_to[]" class="form-control select2" multiple="multiple">
                                            <?php 
                                            $applies = $rule && $rule['applies_to'] ? json_decode($rule['applies_to'], true) : array();
                                            foreach ($roles as $role) { 
                                                $selected = in_array($role['id'], $applies) ? 'selected' : '';
                                            ?>
                                                <option value="<?php echo $role['id']; ?>" <?php echo $selected; ?>><?php echo $role['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                        <small class="text-muted">Leave blank to apply to all roles.</small>
                                    </div>
                                </div>
                            </div>

                            <hr/>
                            <h4><?php echo $this->lang->line('rule_conditions'); ?> (IF)</h4>
                            <table class="table table-bordered" id="conditions_table">
                                <thead>
                                    <tr>
                                        <th>Field (e.g. self_holidays, basic_salary)</th>
                                        <th>Operator</th>
                                        <th>Value</th>
                                        <th>Group (AND/OR)</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($rule && !empty($rule['conditions'])) { 
                                        foreach ($rule['conditions'] as $cond) { ?>
                                        <tr>
                                            <td><input type="text" name="condition_field[]" class="form-control" value="<?php echo $cond['field']; ?>" /></td>
                                            <td>
                                                <select name="condition_operator[]" class="form-control">
                                                    <option value="eq" <?php echo $cond['operator']=='eq'?'selected':''; ?>>Equals (=)</option>
                                                    <option value="gt" <?php echo $cond['operator']=='gt'?'selected':''; ?>>Greater Than (>)</option>
                                                    <option value="gte" <?php echo $cond['operator']=='gte'?'selected':''; ?>>Greater Than or Equal (>=)</option>
                                                    <option value="lt" <?php echo $cond['operator']=='lt'?'selected':''; ?>>Less Than (<)</option>
                                                    <option value="lte" <?php echo $cond['operator']=='lte'?'selected':''; ?>>Less Than or Equal (<=)</option>
                                                </select>
                                            </td>
                                            <td><input type="text" name="condition_value[]" class="form-control" value="<?php echo $cond['value']; ?>" /></td>
                                            <td><input type="number" name="condition_group[]" class="form-control" value="<?php echo $cond['condition_group']; ?>" /></td>
                                            <td><button type="button" class="btn btn-danger btn-xs remove_row"><i class="fa fa-trash"></i></button></td>
                                        </tr>
                                    <?php } } else { ?>
                                        <tr>
                                            <td><input type="text" name="condition_field[]" class="form-control" placeholder="e.g. self_holidays" /></td>
                                            <td>
                                                <select name="condition_operator[]" class="form-control">
                                                    <option value="eq">Equals (=)</option>
                                                    <option value="gt">Greater Than (>)</option>
                                                    <option value="gte">Greater Than or Equal (>=)</option>
                                                    <option value="lt">Less Than (<)</option>
                                                    <option value="lte">Less Than or Equal (<=)</option>
                                                </select>
                                            </td>
                                            <td><input type="text" name="condition_value[]" class="form-control" placeholder="Value" /></td>
                                            <td><input type="number" name="condition_group[]" class="form-control" value="1" /></td>
                                            <td><button type="button" class="btn btn-danger btn-xs remove_row"><i class="fa fa-trash"></i></button></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-success btn-sm" id="add_condition"><i class="fa fa-plus"></i> Add Condition</button>

                            <hr/>
                            <h4><?php echo $this->lang->line('rule_actions'); ?> (THEN)</h4>
                            <table class="table table-bordered" id="actions_table">
                                <thead>
                                    <tr>
                                        <th>Action Type</th>
                                        <th>Target Field</th>
                                        <th>Value</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($rule && !empty($rule['actions'])) { 
                                        foreach ($rule['actions'] as $act) { ?>
                                        <tr>
                                            <td>
                                                <select name="action_type[]" class="form-control">
                                                    <option value="add_paid_days" <?php echo $act['action_type']=='add_paid_days'?'selected':''; ?>>Add Paid Days</option>
                                                    <option value="deduct_paid_days" <?php echo $act['action_type']=='deduct_paid_days'?'selected':''; ?>>Deduct Paid Days</option>
                                                    <option value="fixed_deduction" <?php echo $act['action_type']=='fixed_deduction'?'selected':''; ?>>Fixed Deduction</option>
                                                    <option value="percentage_deduction" <?php echo $act['action_type']=='percentage_deduction'?'selected':''; ?>>Percentage Deduction (of basic)</option>
                                                    <option value="add_allowance" <?php echo $act['action_type']=='add_allowance'?'selected':''; ?>>Add Allowance (Fixed)</option>
                                                    <option value="percentage_addition" <?php echo $act['action_type']=='percentage_addition'?'selected':''; ?>>Add Allowance (% of basic)</option>
                                                    <option value="statutory_pf" <?php echo $act['action_type']=='statutory_pf'?'selected':''; ?>>Statutory PF (%)</option>
                                                    <option value="statutory_esi" <?php echo $act['action_type']=='statutory_esi'?'selected':''; ?>>Statutory ESI (%)</option>
                                                    <option value="statutory_tds" <?php echo $act['action_type']=='statutory_tds'?'selected':''; ?>>Statutory TDS (%)</option>
                                                    <option value="multiply_value" <?php echo $act['action_type']=='multiply_value'?'selected':''; ?>>Multiply Field By Value</option>
                                                </select>
                                            </td>
                                            <td><input type="text" name="action_target[]" class="form-control" value="<?php echo $act['target_field']; ?>" /></td>
                                            <td><input type="text" name="action_value[]" class="form-control" value="<?php echo $act['value']; ?>" /></td>
                                            <td><button type="button" class="btn btn-danger btn-xs remove_row"><i class="fa fa-trash"></i></button></td>
                                        </tr>
                                    <?php } } else { ?>
                                        <tr>
                                            <td>
                                                <select name="action_type[]" class="form-control">
                                                    <option value="add_paid_days">Add Paid Days</option>
                                                    <option value="deduct_paid_days">Deduct Paid Days</option>
                                                    <option value="fixed_deduction">Fixed Deduction</option>
                                                    <option value="percentage_deduction">Percentage Deduction (of basic)</option>
                                                    <option value="add_allowance">Add Allowance (Fixed)</option>
                                                    <option value="percentage_addition">Add Allowance (% of basic)</option>
                                                    <option value="statutory_pf">Statutory PF (%)</option>
                                                    <option value="statutory_esi">Statutory ESI (%)</option>
                                                    <option value="statutory_tds">Statutory TDS (%)</option>
                                                    <option value="multiply_value">Multiply Field By Value</option>
                                                </select>
                                            </td>
                                            <td><input type="text" name="action_target[]" class="form-control" placeholder="e.g. Uniform" /></td>
                                            <td><input type="text" name="action_value[]" class="form-control" placeholder="Value" /></td>
                                            <td><button type="button" class="btn btn-danger btn-xs remove_row"><i class="fa fa-trash"></i></button></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <button type="button" class="btn btn-success btn-sm" id="add_action"><i class="fa fa-plus"></i> Add Action</button>

                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-save"></i> <?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        if ($('.select2').length > 0) {
            $('.select2').select2();
        }

        $('#add_condition').click(function() {
            var row = '<tr>' +
                '<td><input type="text" name="condition_field[]" class="form-control" /></td>' +
                '<td><select name="condition_operator[]" class="form-control"><option value="eq">Equals (=)</option><option value="gt">Greater Than (>)</option><option value="gte">Greater Than or Equal (>=)</option><option value="lt">Less Than (<)</option><option value="lte">Less Than or Equal (<=)</option></select></td>' +
                '<td><input type="text" name="condition_value[]" class="form-control" /></td>' +
                '<td><input type="number" name="condition_group[]" class="form-control" value="1" /></td>' +
                '<td><button type="button" class="btn btn-danger btn-xs remove_row"><i class="fa fa-trash"></i></button></td>' +
                '</tr>';
            $('#conditions_table tbody').append(row);
        });

        $('#add_action').click(function() {
            var row = '<tr>' +
                '<td><select name="action_type[]" class="form-control"><option value="add_paid_days">Add Paid Days</option><option value="deduct_paid_days">Deduct Paid Days</option><option value="fixed_deduction">Fixed Deduction</option><option value="percentage_deduction">Percentage Deduction (of basic)</option><option value="add_allowance">Add Allowance (Fixed)</option><option value="percentage_addition">Add Allowance (% of basic)</option><option value="statutory_pf">Statutory PF (%)</option><option value="statutory_esi">Statutory ESI (%)</option><option value="statutory_tds">Statutory TDS (%)</option><option value="multiply_value">Multiply Field By Value</option></select></td>' +
                '<td><input type="text" name="action_target[]" class="form-control" /></td>' +
                '<td><input type="text" name="action_value[]" class="form-control" /></td>' +
                '<td><button type="button" class="btn btn-danger btn-xs remove_row"><i class="fa fa-trash"></i></button></td>' +
                '</tr>';
            $('#actions_table tbody').append(row);
        });

        $(document).on('click', '.remove_row', function() {
            $(this).closest('tr').remove();
        });

        $('#rule_form').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(res) {
                    if (res.status == 'success') {
                        successMsg(res.message);
                        window.location.href = res.redirect;
                    } else {
                        errorMsg(res.message);
                    }
                }
            });
        });
    });
</script>
