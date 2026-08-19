<?php
$total_entries = count($material_list);
$inward_count  = 0;
$outward_count = 0;
$today_str     = date('Y-m-d');
$today_count   = 0;
foreach ($material_list as $m) {
    if (isset($m['direction']) && $m['direction'] === 'inward') {
        $inward_count++;
    } elseif (isset($m['direction']) && $m['direction'] === 'outward') {
        $outward_count++;
    }
    if (isset($m['date']) && date('Y-m-d', strtotime($m['date'])) === $today_str) {
        $today_count++;
    }
}
?>

<div class="content-wrapper" style="min-height: 348px;">
    <section class="content-header">
        <h1><i class="fa fa-truck"></i> <?php echo $this->lang->line('material_register'); ?></h1>
    </section>
    
    <section class="content">
        <!-- Modern KPI Summary Stat Cards -->
        <div class="modern-stat-grid">
            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label"><?php echo $this->lang->line('total_entries') ? $this->lang->line('total_entries') : 'Total Records'; ?></div>
                    <div class="stat-value"><?php echo $total_entries; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(99, 102, 241, 0.12); color: #6366f1;">
                    <i class="fa fa-cubes"></i>
                </div>
            </div>
            
            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label"><?php echo $this->lang->line('inward'); ?></div>
                    <div class="stat-value text-success" style="color: #059669;"><?php echo $inward_count; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
                    <i class="fa fa-arrow-circle-down"></i>
                </div>
            </div>
            
            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label"><?php echo $this->lang->line('outward'); ?></div>
                    <div class="stat-value text-warning" style="color: #d97706;"><?php echo $outward_count; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b;">
                    <i class="fa fa-arrow-circle-up"></i>
                </div>
            </div>
            
            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label"><?php echo $this->lang->line('today_entries') ? $this->lang->line('today_entries') : "Today's Movement"; ?></div>
                    <div class="stat-value" style="color: #0284c7;"><?php echo $today_count; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(14, 165, 233, 0.12); color: #0284c7;">
                    <i class="fa fa-calendar-check-o"></i>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-list text-muted" style="margin-right: 6px;"></i> <?php echo $this->lang->line('material_register_list'); ?></h3>
                        <?php if ($this->rbac->hasPrivilege('material_register', 'can_add')) { ?>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-primary btn-sm" id="btn-open-material-drawer">
                                    <i class="fa fa-plus"></i> <?php echo $this->lang->line('add_material_entry'); ?>
                                </button>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('material_register_list'); ?></div>
                        <?php echo $this->session->flashdata('msg'); ?>
                        <div class="mailbox-messages table-responsive">
                            <table class="table table-hover table-striped table-bordered example" data-export-title="<?php echo $this->lang->line('material_register_list'); ?>">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('direction'); ?></th>
                                        <th><?php echo $this->lang->line('material_name'); ?></th>
                                        <th><?php echo $this->lang->line('quantity'); ?></th>
                                        <th>Time (In / Out)</th>
                                        <th><?php echo $this->lang->line('carried_by'); ?></th>
                                        <th><?php echo $this->lang->line('party_name'); ?></th>
                                        <th><?php echo $this->lang->line('vehicle_no'); ?></th>
                                        <th><?php echo $this->lang->line('gate_pass_no'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($material_list as $value) { ?>
                                        <tr>
                                            <td class="white-space-nowrap"><i class="fa fa-calendar-o text-muted" style="margin-right: 4px;"></i> <?php echo date($this->customlib->getSchoolDateFormat(), strtotime($value['date'])); ?></td>
                                            <td>
                                                <?php if ($value['direction'] == 'inward') { ?>
                                                    <span class="label label-success"><i class="fa fa-arrow-down" style="margin-right: 3px;"></i> <?php echo $this->lang->line('inward'); ?></span>
                                                <?php } else { ?>
                                                    <span class="label label-warning"><i class="fa fa-arrow-up" style="margin-right: 3px;"></i> <?php echo $this->lang->line('outward'); ?></span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php
                                                if (!empty($value['items']) && count($value['items']) > 0) {
                                                    $total_item_count = count($value['items']);
                                                    $all_item_details = array();
                                                    foreach ($value['items'] as $it) {
                                                        $q_part = trim($it['quantity'] . ' ' . $it['unit']);
                                                        $c_part = !empty($it['total_cost']) ? ' (Cost: ' . $it['total_cost'] . ')' : '';
                                                        $all_item_details[] = html_escape($it['material_name'] . ($q_part !== '' ? ' - ' . $q_part : '') . $c_part);
                                                    }
                                                    $tooltip_text = implode("&#10;• ", $all_item_details);

                                                    if ($total_item_count == 1) {
                                                        echo '<strong style="color: #0f172a;">' . html_escape($value['items'][0]['material_name']) . '</strong>';
                                                    } elseif ($total_item_count == 2) {
                                                        echo '<strong style="color: #0f172a;">' . html_escape($value['items'][0]['material_name'] . ', ' . $value['items'][1]['material_name']) . '</strong>';
                                                        echo ' <span class="badge" style="background: #e0e7ff; color: #4338ca; font-size: 10px; padding: 2px 6px;">2 items</span>';
                                                    } else {
                                                        // 3 or more (e.g., 5-10 items): show first item + badge with tooltip and view slip trigger
                                                        $first_item = html_escape($value['items'][0]['material_name']);
                                                        $remaining = $total_item_count - 1;
                                                        echo '<strong style="color: #0f172a;">' . $first_item . '</strong> ';
                                                        echo '<a href="javascript:void(0);" class="btn-view-slip badge" data-id="' . $value['id'] . '" data-toggle="tooltip" data-html="true" title="<b>Items (' . $total_item_count . '):</b><br>• ' . str_replace("&#10;", "<br>", $tooltip_text) . '" style="background: #e0e7ff; color: #4338ca; font-size: 11px; padding: 2px 7px; text-decoration: none; border: 1px solid #c7d2fe; cursor: pointer;">+' . $remaining . ' more <i class="fa fa-list-ul" style="font-size: 9px; margin-left: 2px;"></i></a>';
                                                    }
                                                } else {
                                                    echo '<strong style="color: #0f172a;">' . html_escape($value['material_name']) . '</strong>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if (!empty($value['items']) && count($value['items']) > 0) {
                                                    $total_item_count = count($value['items']);
                                                    if ($total_item_count == 1) {
                                                        $single_qty = trim($value['items'][0]['quantity'] . ' ' . $value['items'][0]['unit']);
                                                        echo ($single_qty !== '') ? '<span class="badge" style="background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0;">' . html_escape($single_qty) . '</span>' : '-';
                                                    } elseif ($total_item_count <= 2) {
                                                        $qty_badges = array();
                                                        foreach ($value['items'] as $it) {
                                                            $q_str = trim($it['quantity'] . ' ' . $it['unit']);
                                                            if ($q_str !== '') {
                                                                $qty_badges[] = '<span class="badge" style="background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0; margin-right: 3px; display: inline-block;">' . html_escape($q_str) . '</span>';
                                                            }
                                                        }
                                                        echo !empty($qty_badges) ? implode(' ', $qty_badges) : '-';
                                                    } else {
                                                        // For 3+ items, show first item qty + compact summary
                                                        $first_qty = trim($value['items'][0]['quantity'] . ' ' . $value['items'][0]['unit']);
                                                        if ($first_qty !== '') {
                                                            echo '<span class="badge" style="background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0;">' . html_escape($first_qty) . '</span> ';
                                                        }
                                                        echo '<span class="text-muted" style="font-size: 11px;">(' . $total_item_count . ' lines)</span>';
                                                    }
                                                } else {
                                                    $single_qty = trim($value['quantity'] . ' ' . $value['unit']);
                                                    echo ($single_qty !== '') ? '<span class="badge" style="background: #f1f5f9; color: #334155; border: 1px solid #e2e8f0;">' . html_escape($single_qty) . '</span>' : '-';
                                                }
                                                ?>
                                            </td>
                                            <td class="white-space-nowrap" style="font-size: 12px;">
                                                <div>
                                                    <span class="text-muted">In:</span> 
                                                    <?php if (!empty($value['in_time'])) { ?>
                                                        <strong style="color: #0f172a;"><?php echo html_escape($value['in_time']); ?></strong>
                                                    <?php } else { ?>
                                                        <span class="text-muted" style="font-style: italic; font-size: 11px;">-</span>
                                                        <?php if ($this->rbac->hasPrivilege('material_register', 'can_edit')) { ?>
                                                            <button type="button" class="btn btn-xs btn-quick-material-time" onclick="quickMarkMaterialTime('<?php echo $value['id']; ?>', 'in_time', '<?php echo html_escape($value['gate_pass_no']); ?>')" data-toggle="tooltip" title="Mark In-Time" style="display: inline-flex; align-items: center; justify-content: center; width: 20px; height: 20px; border-radius: 50%; background: #10b981; color: #fff; border: none; padding: 0; margin-left: 4px; cursor: pointer; transition: all 0.2s; box-shadow: 0 1px 3px rgba(16,185,129,0.4);">
                                                                <i class="fa fa-plus" style="font-size: 10px; font-weight: bold;"></i>
                                                            </button>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </div>
                                                <div style="margin-top: 3px;">
                                                    <span class="text-muted">Out:</span> 
                                                    <?php if (!empty($value['out_time'])) { ?>
                                                        <strong style="color: #0f172a;"><?php echo html_escape($value['out_time']); ?></strong>
                                                    <?php } else { ?>
                                                        <span class="text-muted" style="font-style: italic; font-size: 11px;">-</span>
                                                        <?php if ($this->rbac->hasPrivilege('material_register', 'can_edit')) { ?>
                                                            <button type="button" class="btn btn-xs btn-quick-material-time" onclick="quickMarkMaterialTime('<?php echo $value['id']; ?>', 'out_time', '<?php echo html_escape($value['gate_pass_no']); ?>')" data-toggle="tooltip" title="Mark Out-Time" style="display: inline-flex; align-items: center; justify-content: center; width: 20px; height: 20px; border-radius: 50%; background: #10b981; color: #fff; border: none; padding: 0; margin-left: 4px; cursor: pointer; transition: all 0.2s; box-shadow: 0 1px 3px rgba(16,185,129,0.4);">
                                                                <i class="fa fa-plus" style="font-size: 10px; font-weight: bold;"></i>
                                                            </button>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </div>
                                            </td>
                                            <td>
                                                <?php echo html_escape($value['carried_by']);
                                                if (!empty($value['contact'])) {
                                                    echo '<br><small class="text-muted"><i class="fa fa-phone" style="margin-right: 2px;"></i> ' . html_escape($value['contact']) . '</small>';
                                                } ?>
                                            </td>
                                            <td><?php echo html_escape($value['party_name']); ?></td>
                                            <td>
                                                <?php if (!empty($value['vehicle_no'])) { ?>
                                                    <span class="badge" style="background: #f8fafc; color: #475569; border: 1px solid #cbd5e1;"><i class="fa fa-truck" style="margin-right: 3px;"></i> <?php echo html_escape($value['vehicle_no']); ?></span>
                                                <?php } else { echo '-'; } ?>
                                            </td>
                                            <td><code style="background: #f1f5f9; color: #4f46e5; padding: 2px 6px; border-radius: 4px; font-weight: 600;"><?php echo html_escape($value['gate_pass_no']); ?></code></td>
                                            <td class="text-right white-space-nowrap">
                                                <button type="button" class="btn btn-default btn-xs btn-view-slip" data-id="<?php echo $value['id']; ?>" data-toggle="tooltip" title="<?php echo $this->lang->line('view'); ?>">
                                                    <i class="fa fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-default btn-xs" onclick="printMaterialSlip('<?php echo $value['id']; ?>')" data-toggle="tooltip" title="<?php echo $this->lang->line('print'); ?>">
                                                    <i class="fa fa-print"></i>
                                                </button>
                                                <?php if (!empty($value['image'])) { ?>
                                                    <a href="<?php echo site_url('admin/materialregister/download/' . $value['id']); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('attachment'); ?>"><i class="fa fa-paperclip"></i></a>
                                                <?php } ?>
                                                <?php if ($this->rbac->hasPrivilege('material_register', 'can_edit')) { ?>
                                                    <a href="<?php echo site_url('admin/materialregister/edit/' . $value['id']); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>"><i class="fa fa-pencil text-primary"></i></a>
                                                <?php } ?>
                                                <?php if ($this->rbac->hasPrivilege('material_register', 'can_delete')) { ?>
                                                    <a href="<?php echo site_url('admin/materialregister/delete/' . $value['id']); ?>" class="btn btn-default btn-xs" onclick="return confirm('<?php echo $this->lang->line('delete_confirm'); ?>');" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>"><i class="fa fa-trash text-danger"></i></a>
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

<!-- Right Side Slide-in Drawer for Adding Material Entry -->
<?php if ($this->rbac->hasPrivilege('material_register', 'can_add')) { ?>
    <div id="material-drawer-overlay" class="modern-drawer-overlay"></div>
    <div id="material-drawer-panel" class="modern-drawer-panel">
        <form id="materialform" action="<?php echo site_url('admin/materialregister'); ?>" method="post" enctype="multipart/form-data">
            <div class="modern-drawer-header">
                <h4 class="modern-drawer-title"><i class="fa fa-plus-circle" style="color: var(--primary-theme-color, #4f46e5);"></i> <?php echo $this->lang->line('add_material_entry'); ?></h4>
                <button type="button" class="modern-drawer-close" id="btn-close-material-drawer">&times;</button>
            </div>
            <?php echo $this->customlib->getCSRF(); ?>
            <div class="modern-drawer-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('direction'); ?></label> <small class="req">*</small>
                            <select class="form-control" name="direction">
                                <option value="inward" <?php echo set_select('direction', 'inward', true); ?>><?php echo $this->lang->line('inward'); ?></option>
                                <option value="outward" <?php echo set_select('direction', 'outward'); ?>><?php echo $this->lang->line('outward'); ?></option>
                            </select>
                            <span class="text-danger"><?php echo form_error('direction'); ?></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('date'); ?></label> <small class="req">*</small>
                            <input type="text" class="form-control date" name="date" readonly value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat())); ?>"/>
                            <span class="text-danger"><?php echo form_error('date'); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="material-items-wrapper" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 14px; margin-bottom: 18px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                        <label style="margin: 0; font-weight: 700; color: #1e293b; font-size: 13px;">
                            <i class="fa fa-cubes text-primary" style="margin-right: 5px;"></i> Materials / Items <small class="req">*</small>
                        </label>
                        <button type="button" class="btn btn-primary btn-xs btn-add-item-row" style="border-radius: 4px; padding: 4px 10px; font-weight: 600;">
                            <i class="fa fa-plus"></i> Add Item
                        </button>
                    </div>
                    <span class="text-danger"><?php echo form_error('material_name[]'); ?></span>
                    
                    <div id="material-items-container">
                        <div class="material-item-row" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px; margin-bottom: 10px; position: relative;">
                            <div class="form-group" style="margin-bottom: 8px;">
                                <label style="font-size: 12px; color: #64748b;"><?php echo $this->lang->line('material_name'); ?> <small class="req">*</small></label>
                                <?php echo material_select_array('material_name[]', 'item', $masters['item'], '', $this->lang->line('select')); ?>
                            </div>
                            <div class="row">
                                <div class="col-xs-4" style="padding-right: 4px;">
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label style="font-size: 12px; color: #64748b;"><?php echo $this->lang->line('quantity'); ?></label>
                                        <?php echo material_select_array('quantity[]', 'quantity', $masters['quantity'], '', $this->lang->line('select')); ?>
                                    </div>
                                </div>
                                <div class="col-xs-3" style="padding-left: 4px; padding-right: 4px;">
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label style="font-size: 12px; color: #64748b;"><?php echo $this->lang->line('unit'); ?></label>
                                        <?php echo material_select_array('unit[]', 'unit', $masters['unit'], '', $this->lang->line('select')); ?>
                                    </div>
                                </div>
                                <div class="col-xs-4" style="padding-left: 4px; padding-right: 4px;">
                                    <div class="form-group" style="margin-bottom: 0;">
                                        <label style="font-size: 12px; color: #64748b;">Total Cost</label>
                                        <input type="number" step="any" min="0" class="form-control" name="total_cost[]" placeholder="0.00" style="height: 34px; font-size: 13px;"/>
                                    </div>
                                </div>
                                <div class="col-xs-1" style="padding-left: 2px; padding-right: 2px; display: flex; align-items: flex-end; justify-content: center; height: 59px;">
                                    <button type="button" class="btn btn-danger btn-xs btn-remove-item-row" title="Remove Item" style="display: none; border-radius: 4px; padding: 5px 8px;">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('carried_by'); ?></label>
                            <input class="form-control" name="carried_by" placeholder="e.g. John Doe" value="<?php echo set_value('carried_by'); ?>"/>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('contact'); ?></label>
                            <input class="form-control" name="contact" placeholder="e.g. +91 9876543210" value="<?php echo set_value('contact'); ?>"/>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><?php echo $this->lang->line('party_name'); ?></label>
                    <?php echo material_select('party_name', 'party', $masters['party'], set_value('party_name'), $this->lang->line('from_to_hint')); ?>
                </div>
                
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('vehicle_no'); ?></label>
                            <?php echo material_select('vehicle_no', 'vehicle', $masters['vehicle'], set_value('vehicle_no'), $this->lang->line('select')); ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('gate_pass_no'); ?></label>
                            <input class="form-control" name="gate_pass_no" value="<?php echo set_value('gate_pass_no', $next_gate_pass_no); ?>" placeholder="Auto-generated"/>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label><?php echo $this->lang->line('driver_name'); ?></label>
                    <?php echo material_select('driver_name', 'driver', $masters['driver'], set_value('driver_name'), $this->lang->line('select')); ?>
                </div>
                
                <div class="form-group">
                    <label><?php echo $this->lang->line('received_issued_by'); ?></label>
                    <select class="form-control staff-select2" name="staff_id">
                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                        <?php foreach ($stafflist as $s) { ?>
                            <option value="<?php echo $s['id']; ?>" <?php echo set_select('staff_id', $s['id']); ?>><?php echo html_escape($s['name'] . ' ' . $s['surname'] . ' (' . $s['employee_id'] . ')'); ?></option>
                        <?php } ?>
                    </select>
                </div>
                
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('department'); ?></label>
                            <?php echo material_select('department', 'department', $masters['department'], set_value('department'), $this->lang->line('select')); ?>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('approved_by'); ?></label>
                            <select class="form-control staff-select2" name="approved_by">
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php foreach ($stafflist as $s) { ?>
                                    <option value="<?php echo html_escape($s['name'] . ' ' . $s['surname']); ?>" <?php echo set_select('approved_by', $s['name'] . ' ' . $s['surname']); ?>><?php echo html_escape($s['name'] . ' ' . $s['surname'] . ' (' . $s['employee_id'] . ')'); ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('in_time'); ?></label>
                            <input class="form-control" name="in_time" id="drawer_in_time" placeholder="HH:MM" value="<?php echo set_value('in_time', date('h:i A')); ?>"/>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('out_time'); ?></label>
                            <input class="form-control" name="out_time" id="drawer_out_time" placeholder="HH:MM" value="<?php echo set_value('out_time', date('h:i A')); ?>"/>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label style="font-weight: 600; color: #1e293b;"><?php echo $this->lang->line('attachment') ? $this->lang->line('attachment') : 'Attachment (Photo / Challan)'; ?></label>
                    <div class="custom-file-upload-box" style="border: 2px dashed #cbd5e1; border-radius: 8px; padding: 16px; text-align: center; background: #f8fafc; cursor: pointer; transition: all 0.2s ease; position: relative;" onclick="$('#material_image_input').trigger('click');">
                        <input type="file" name="image" id="material_image_input" style="display: none;" accept=".jpg,.jpeg,.png,.gif,.pdf" onchange="var name = this.files[0] ? this.files[0].name : ''; $('#upload-file-name-display').text(name || 'Click or drag files here to upload');"/>
                        <i class="fa fa-cloud-upload" style="font-size: 28px; color: #6366f1; margin-bottom: 6px;"></i>
                        <div id="upload-file-name-display" style="font-size: 13px; font-weight: 600; color: #334155;">Click to browse or drop document / photo</div>
                        <small class="text-muted" style="display: block; margin-top: 4px;"><?php echo $this->lang->line('photo_challan_hint') ? $this->lang->line('photo_challan_hint') : 'JPG, PNG, GIF or PDF up to 5 MB'; ?></small>
                    </div>
                    <span class="text-danger"><?php echo form_error('image'); ?></span>
                </div>
                
                <div class="form-group">
                    <label><?php echo $this->lang->line('remarks'); ?></label>
                    <textarea class="form-control" name="remarks" rows="2" placeholder="Enter any extra details or observations..."><?php echo set_value('remarks'); ?></textarea>
                </div>
            </div>
            <div class="modern-drawer-footer" style="display: flex; justify-content: flex-end; gap: 8px;">
                <button type="button" class="btn btn-default" id="btn-cancel-material-drawer"><?php echo $this->lang->line('cancel'); ?></button>
                <button type="submit" class="btn btn-primary" id="btn-save-material" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><i class="fa fa-check"></i> <?php echo $this->lang->line('save'); ?></button>
                <button type="submit" class="btn btn-info" id="btn-save-print-material" name="save_and_print" value="1" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><i class="fa fa-print"></i> Save & Print</button>
            </div>
        </form>
    </div>
<?php } ?>

<!-- View / Print Material Gate Pass Slip Modal -->
<div class="modal fade" id="viewSlipModal" tabindex="-1" role="dialog" aria-labelledby="viewSlipModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="viewSlipModalLabel"><i class="fa fa-file-text-o text-primary"></i> Material Gate Pass Slip</h4>
            </div>
            <div class="modal-body" id="printableSlip">
                <div style="text-align: center; border-bottom: 2px dashed #cbd5e1; padding-bottom: 14px; margin-bottom: 18px;">
                    <h3 style="margin: 0; font-weight: 800; color: #0f172a; letter-spacing: 0.5px;">MATERIAL GATE PASS</h3>
                    <p style="margin: 6px 0 0 0; font-size: 13px;" id="slip-direction-badge"></p>
                </div>
                <table class="table table-bordered" style="margin-bottom: 15px;">
                    <tr>
                        <th style="width: 25%; background: #f8fafc; color: #475569;">Gate Pass / Challan No.</th>
                        <td style="width: 25%; font-weight: 700;" id="slip-gate-pass-no"></td>
                        <th style="width: 25%; background: #f8fafc; color: #475569;">Date</th>
                        <td style="width: 25%;" id="slip-date"></td>
                    </tr>
                    <tr>
                        <th style="background: #f8fafc; color: #475569;">From / To (Party)</th>
                        <td id="slip-party-name"></td>
                        <th style="background: #f8fafc; color: #475569;">Carried By</th>
                        <td id="slip-carried-by"></td>
                    </tr>
                    <tr>
                        <th style="background: #f8fafc; color: #475569;">Vehicle No.</th>
                        <td id="slip-vehicle-no"></td>
                        <th style="background: #f8fafc; color: #475569;">Driver Name</th>
                        <td id="slip-driver-name"></td>
                    </tr>
                    <tr>
                        <th style="background: #f8fafc; color: #475569;">Received / Issued By</th>
                        <td id="slip-staff-name"></td>
                        <th style="background: #f8fafc; color: #475569;">Department</th>
                        <td id="slip-department"></td>
                    </tr>
                    <tr>
                        <th style="background: #f8fafc; color: #475569;">Approved By</th>
                        <td id="slip-approved-by"></td>
                        <th style="background: #f8fafc; color: #475569;">In Time / Out Time</th>
                        <td id="slip-time"></td>
                    </tr>
                    <tr>
                        <th style="background: #f8fafc; color: #475569;">Remarks</th>
                        <td colspan="3" id="slip-remarks"></td>
                    </tr>
                    <tr id="slip-image-row" style="display: none;">
                        <th style="background: #f8fafc; color: #475569;">Attachment / Photo</th>
                        <td colspan="3" id="slip-image-container"></td>
                    </tr>
                </table>

                    <div style="margin-top: 15px;">
                        <h5 style="font-weight: 700; color: #0f172a; margin-bottom: 8px;"><i class="fa fa-cubes text-primary"></i> Material / Item Details</h5>
                        <table class="table table-bordered table-striped" style="margin-bottom: 0;">
                            <thead>
                                <tr style="background: #f8fafc;">
                                    <th style="width: 50px; text-align: center;">#</th>
                                    <th>Item / Material Name</th>
                                    <th style="width: 110px;">Quantity</th>
                                    <th style="width: 100px;">Unit</th>
                                    <th style="width: 120px; text-align: right;">Total Cost</th>
                                </tr>
                            </thead>
                            <tbody id="slip-items-tbody">
                            </tbody>
                            <tfoot id="slip-items-tfoot" style="background: #f8fafc; font-weight: 700;">
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('close'); ?></button>
                    <button type="button" class="btn btn-primary" onclick="window.print();"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?></button>
                </div>
            </div>
        </div>
    </div>

<!-- Custom Quick Time Confirmation Modal -->
<div class="modal fade" id="quickMaterialTimeModal" tabindex="-1" role="dialog" aria-labelledby="quickMaterialTimeModalLabel" style="z-index: 1060;">
    <div class="modal-dialog modal-sm" role="document" style="max-width: 380px; margin-top: 15vh;">
        <div class="modal-content" style="border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); border: none;">
            <div class="modal-body" style="padding: 24px; text-align: center;">
                <div style="width: 52px; height: 52px; border-radius: 50%; background: #ecfdf5; color: #10b981; display: inline-flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 16px;">
                    <i class="fa fa-clock-o"></i>
                </div>
                <h4 style="font-weight: 700; color: #0f172a; margin: 0 0 8px 0;" id="qmt-modal-title">Mark Timestamp</h4>
                <p style="color: #64748b; font-size: 13px; margin: 0 0 16px 0;" id="qmt-modal-desc"></p>
                <div style="margin-bottom: 20px; text-align: left;">
                    <label style="font-size: 12px; color: #475569; font-weight: 600; margin-bottom: 4px; display: block;" id="qmt-badge-label">Time:</label>
                    <div class="input-group">
                        <input type="text" name="quick_material_time" class="form-control timepicker" id="qmt-edit-time" style="height: 38px; font-size: 15px; font-weight: 600; color: #0f172a; border-radius: 6px 0 0 6px; text-align: center;">
                        <div class="input-group-addon" style="background: #f8fafc; border-radius: 0 6px 6px 0; color: #64748b; cursor: pointer;" onclick="$('#qmt-edit-time').focus();">
                            <i class="fa fa-clock-o"></i>
                        </div>
                    </div>
                    <small class="text-muted" style="font-size: 11px; margin-top: 3px; display: block; text-align: center;">Auto-filled with current time. You can edit if needed.</small>
                </div>
                <input type="hidden" id="qmt-id" value="">
                <input type="hidden" id="qmt-type" value="">
                <div style="display: flex; gap: 10px; justify-content: center;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 6px; padding: 8px 18px; font-weight: 600; color: #475569; min-width: 90px;"><?php echo $this->lang->line('cancel'); ?></button>
                    <button type="button" class="btn btn-primary" id="btn-confirm-quick-time" style="border-radius: 6px; padding: 8px 20px; font-weight: 600; background: #4f46e5; border-color: #4f46e5; min-width: 100px;">Confirm</button>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
.bootstrap-timepicker-widget.dropdown-menu,
.bootstrap-timepicker-widget {
    z-index: 100000 !important;
}
</style>

    <template id="material-item-template">
        <div class="material-item-row" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px; margin-bottom: 10px; position: relative;">
            <div class="form-group" style="margin-bottom: 8px;">
                <label style="font-size: 12px; color: #64748b;"><?php echo $this->lang->line('material_name'); ?> <small class="req">*</small></label>
                <?php echo material_select_array('material_name[]', 'item', $masters['item'], '', $this->lang->line('select')); ?>
            </div>
            <div class="row">
                <div class="col-xs-4" style="padding-right: 4px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-size: 12px; color: #64748b;"><?php echo $this->lang->line('quantity'); ?></label>
                        <?php echo material_select_array('quantity[]', 'quantity', $masters['quantity'], '', $this->lang->line('select')); ?>
                    </div>
                </div>
                <div class="col-xs-3" style="padding-left: 4px; padding-right: 4px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-size: 12px; color: #64748b;"><?php echo $this->lang->line('unit'); ?></label>
                        <?php echo material_select_array('unit[]', 'unit', $masters['unit'], '', $this->lang->line('select')); ?>
                    </div>
                </div>
                <div class="col-xs-4" style="padding-left: 4px; padding-right: 4px;">
                    <div class="form-group" style="margin-bottom: 0;">
                        <label style="font-size: 12px; color: #64748b;">Total Cost</label>
                        <input type="number" step="any" min="0" class="form-control" name="total_cost[]" placeholder="0.00" style="height: 34px; font-size: 13px;"/>
                    </div>
                </div>
                <div class="col-xs-1" style="padding-left: 2px; padding-right: 2px; display: flex; align-items: flex-end; justify-content: center; height: 59px;">
                    <button type="button" class="btn btn-danger btn-xs btn-remove-item-row" title="Remove Item" style="border-radius: 4px; padding: 5px 8px;">
                        <i class="fa fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    </template>

    <script>
    var MATERIAL_ADDITEM_URL = "<?php echo site_url('admin/materialregister/additem'); ?>";
    var MATERIAL_DETAILS_URL = "<?php echo site_url('admin/materialregister/get_details_json/'); ?>";
    var HAS_FORM_ERRORS = <?php echo (validation_errors() != '') ? 'true' : 'false'; ?>;
    var lastClickedMaterialBtn = null;

    $(document).ready(function () {
        initMaterialSelect2($('#materialform'));

        // Right side drawer toggle actions
        $('#btn-open-material-drawer').on('click', function () {
            openMaterialDrawer();
        });
        $('#btn-close-material-drawer, #btn-cancel-material-drawer, #material-drawer-overlay').on('click', function () {
            closeMaterialDrawer();
        });

        // Add more item rows
        $(document).on('click', '.btn-add-item-row', function () {
            var template = document.getElementById('material-item-template');
            if (template) {
                var clone = $(template.content.cloneNode(true));
                $('#material-items-container').append(clone);
                initMaterialSelect2($('#material-items-container .material-item-row:last'));
                updateRemoveButtonsVisibility();
            }
        });

        // Remove item row
        $(document).on('click', '.btn-remove-item-row', function () {
            $(this).closest('.material-item-row').remove();
            updateRemoveButtonsVisibility();
        });

        // Track submit button clicked
        $('#materialform button[type=submit]').on('click', function() {
            lastClickedMaterialBtn = $(this);
        });

        // Handle AJAX form submission for Save / Save & Print
        $('#materialform').on('submit', function (e) {
            e.preventDefault();
            var $this = lastClickedMaterialBtn || $(this).find("button[type=submit]:first");
            var isSaveAndPrint = ($this.attr('id') === 'btn-save-print-material');

            $('#materialform').find('.text-danger').remove();

            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    $this.button('loading');
                },
                success: function (res) {
                    if (res.status == "fail") {
                        $.each(res.error, function (index, value) {
                            if (value != '') {
                                $('[name="' + index + '"]').closest('.form-group').append('<span class="text-danger">' + value + '</span>');
                            }
                        });
                    } else {
                        successMsg(res.message);
                        if (isSaveAndPrint && res.id) {
                            printMaterialSlip(res.id);
                            setTimeout(function() {
                                window.location.reload(true);
                            }, 1000);
                        } else {
                            window.location.reload(true);
                        }
                    }
                },
                error: function () {
                    $('#materialform')[0].submit();
                },
                complete: function () {
                    $this.button('reset');
                }
            });
        });

        // Open drawer automatically if form validation failed
        if (HAS_FORM_ERRORS) {
            openMaterialDrawer();
        }

        // View slip details
        $('.btn-view-slip').on('click', function () {
            var id = $(this).data('id');
            $.getJSON(MATERIAL_DETAILS_URL + id, function (res) {
                if (res.status === 'success') {
                    var d = res.data;
                    $('#slip-direction-badge').html(
                        d.direction === 'inward' 
                        ? '<span class="label label-success"><i class="fa fa-arrow-down"></i> INWARD ENTRY</span>' 
                        : '<span class="label label-warning"><i class="fa fa-arrow-up"></i> OUTWARD ENTRY</span>'
                    );
                    $('#slip-gate-pass-no').text(d.gate_pass_no || '-');
                    $('#slip-date').text(d.formatted_date || d.date || '-');
                    $('#slip-party-name').text(d.party_name || '-');
                    $('#slip-carried-by').html((d.carried_by || '-') + (d.contact ? ' (' + d.contact + ')' : ''));
                    $('#slip-vehicle-no').text(d.vehicle_no || '-');
                    $('#slip-driver-name').text(d.driver_name || '-');
                    $('#slip-staff-name').text(d.staff_full_name || '-');
                    $('#slip-department').text(d.department || '-');
                    $('#slip-approved-by').text(d.approved_by || '-');
                    $('#slip-time').text('In: ' + (d.in_time || '-') + ' | Out: ' + (d.out_time || '-'));
                    $('#slip-remarks').text(d.remarks || '-');

                    // Render multiple items in slip table
                    var $itemsTbody = $('#slip-items-tbody');
                    var $itemsTfoot = $('#slip-items-tfoot');
                    $itemsTbody.empty();
                    $itemsTfoot.empty();
                    var grandTotalCost = 0;
                    var hasAnyCost = false;

                    if (d.items && d.items.length > 0) {
                        $.each(d.items, function (idx, itm) {
                            var costVal = itm.total_cost ? parseFloat(itm.total_cost) : null;
                            if (costVal !== null && !isNaN(costVal)) {
                                grandTotalCost += costVal;
                                hasAnyCost = true;
                            }
                            $itemsTbody.append('<tr>' +
                                '<td style="text-align: center; font-weight: 600;">' + (idx + 1) + '</td>' +
                                '<td><strong style="color: #0f172a;">' + $('<div>').text(itm.material_name).html() + '</strong></td>' +
                                '<td>' + $('<div>').text(itm.quantity || '-').html() + '</td>' +
                                '<td>' + $('<div>').text(itm.unit || '-').html() + '</td>' +
                                '<td style="text-align: right;">' + (costVal !== null && !isNaN(costVal) ? costVal.toFixed(2) : (itm.total_cost || '-')) + '</td>' +
                            '</tr>');
                        });
                    } else if (d.material_name) {
                        var costVal = d.total_cost ? parseFloat(d.total_cost) : null;
                        if (costVal !== null && !isNaN(costVal)) {
                            grandTotalCost += costVal;
                            hasAnyCost = true;
                        }
                        $itemsTbody.append('<tr>' +
                            '<td style="text-align: center; font-weight: 600;">1</td>' +
                            '<td><strong style="color: #0f172a;">' + $('<div>').text(d.material_name).html() + '</strong></td>' +
                            '<td>' + $('<div>').text(d.quantity || '-').html() + '</td>' +
                            '<td>' + $('<div>').text(d.unit || '-').html() + '</td>' +
                            '<td style="text-align: right;">' + (costVal !== null && !isNaN(costVal) ? costVal.toFixed(2) : (d.total_cost || '-')) + '</td>' +
                        '</tr>');
                    } else {
                        $itemsTbody.append('<tr><td colspan="5" class="text-center text-muted">No items recorded</td></tr>');
                    }

                    if (hasAnyCost) {
                        $itemsTfoot.append('<tr>' +
                            '<td colspan="4" class="text-right">Grand Total:</td>' +
                            '<td style="text-align: right; color: #4338ca;">' + grandTotalCost.toFixed(2) + '</td>' +
                        '</tr>');
                    }

                    if (d.image) {
                        $('#slip-image-container').html('<a href="<?php echo site_url('admin/materialregister/download/'); ?>' + d.id + '" target="_blank" class="btn btn-xs btn-default"><i class="fa fa-paperclip"></i> View Attachment</a>');
                        $('#slip-image-row').show();
                    } else {
                        $('#slip-image-row').hide();
                    }

                    $('#viewSlipModal').modal('show');
                }
            });
        });
    });

    function printMaterialSlip(id) {
        var url = '<?php echo base_url(); ?>admin/materialregister/print_slip/' + id;
        $.ajax({
            url: url,
            type: "POST",
            success: function (data) {
                var popupWin = window.open('', '_blank', 'width=800,height=600');
                popupWin.document.open();
                popupWin.document.write(data);
                popupWin.document.close();
                setTimeout(function() {
                    popupWin.focus();
                    popupWin.print();
                }, 500);
            }
        });
    }

    function quickMarkMaterialTime(id, timeType, passNo) {
        var currentTime = getCurrentTimeString();
        var label = (timeType === 'in_time') ? 'In-Time' : 'Out-Time';
        
        $('#qmt-id').val(id);
        $('#qmt-type').val(timeType);
        $('#qmt-edit-time').val(currentTime);
        $('#qmt-modal-title').text('Mark ' + label);
        $('#qmt-modal-desc').text('Mark ' + label + ' for Material Gate Pass ' + passNo + '.');
        $('#qmt-badge-label').text(label + ':');

        $('#quickMaterialTimeModal').modal('show');
        setTimeout(function() {
            $('#qmt-edit-time').timepicker({
                showInputs: false,
                showMeridian: false,
                defaultTime: currentTime
            });
        }, 200);
    }

    $(document).on('click', '#btn-confirm-quick-time', function () {
        var $btn = $(this);
        var id = $('#qmt-id').val();
        var timeType = $('#qmt-type').val();
        var timeVal = $('#qmt-edit-time').val() || getCurrentTimeString();

        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

        $.ajax({
            url: '<?php echo site_url('admin/materialregister/quick_update_time'); ?>',
            type: 'POST',
            data: {
                id: id,
                time_type: timeType,
                time_val: timeVal
            },
            dataType: 'json',
            success: function (res) {
                if (res.status === 'success') {
                    $('#quickMaterialTimeModal').modal('hide');
                    successMsg(res.message);
                    window.location.reload(true);
                } else {
                    errorMsg(res.message || 'Failed to update time');
                    $btn.prop('disabled', false).text('Confirm');
                }
            },
            error: function () {
                errorMsg('Failed to update time');
                $btn.prop('disabled', false).text('Confirm');
            }
        });
    });

    function updateRemoveButtonsVisibility() {
        var rows = $('#material-items-container .material-item-row');
        if (rows.length > 1) {
            rows.find('.btn-remove-item-row').show();
        } else {
            rows.find('.btn-remove-item-row').hide();
        }
    }

    function getCurrentTimeString() {
        var now = new Date();
        var hours = now.getHours();
        var minutes = now.getMinutes();
        var ampm = hours >= 12 ? 'PM' : 'AM';
        hours = hours % 12;
        hours = hours ? hours : 12; // hour '0' should be '12'
        minutes = minutes < 10 ? '0' + minutes : minutes;
        var strTime = (hours < 10 ? '0' + hours : hours) + ':' + minutes + ' ' + ampm;
        return strTime;
    }

    function openMaterialDrawer() {
        var $inTime = $('#drawer_in_time');
        var $outTime = $('#drawer_out_time');
        var currentTime = getCurrentTimeString();
        if (!$inTime.val()) {
            $inTime.val(currentTime);
        }
        if (!$outTime.val()) {
            $outTime.val(currentTime);
        }

        $('#material-drawer-overlay').addClass('is-active');
        $('#material-drawer-panel').addClass('is-open');
        $('body').addClass('drawer-open').css('overflow', 'hidden');
    }

    function closeMaterialDrawer() {
        $('#material-drawer-panel').removeClass('is-open');
        $('#material-drawer-overlay').removeClass('is-active');
        $('body').removeClass('drawer-open').css('overflow', '');
    }

    function initMaterialSelect2(context) {
        var $ctx = context ? $(context) : $(document);
        $ctx.find('.staff-select2').select2({ width: '100%' });

        $ctx.find('.material-select2').each(function () {
            var $s = $(this);
            if ($s.hasClass('select2-hidden-accessible')) {
                return;
            }
            $s.data('known', $s.find('option').map(function () { return this.value; }).get());
            $s.select2({
                width: '100%',
                tags: true,
                allowClear: true,
                placeholder: $s.data('placeholder') || '',
                createTag: function (params) {
                    var term = $.trim(params.term);
                    if (term === '') { return null; }
                    return { id: term, text: term, newTag: true };
                }
            });
        });

        $ctx.find('.material-select2').on('select2:select', function (e) {
            var $s    = $(this);
            var data  = e.params.data;
            var val   = data.id;
            var known = $s.data('known') || [];
            if (val && (data.newTag === true || known.indexOf(val) === -1)) {
                known.push(val);
                $s.data('known', known);
                $.post(MATERIAL_ADDITEM_URL, { type: $s.data('type'), name: val }, function () {}, 'json');
            }
        });
    }
</script>
