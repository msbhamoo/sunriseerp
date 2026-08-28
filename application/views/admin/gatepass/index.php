<?php
$total_passes = count($gate_passes);
$pending_count = 0;
$approved_count = 0;
$completed_count = 0;
$overdue_count = 0;

$now_timestamp = time();

foreach ($gate_passes as &$gp_item) {
    $is_overdue = false;
    $overdue_text = '';

    if (isset($gp_item['status'])) {
        if ($gp_item['status'] == 'Pending') { 
            $pending_count++; 
        } elseif ($gp_item['status'] == 'Approved') { 
            $approved_count++; 

            // Check if partial pass is overdue
            $is_partial = (isset($gp_item['pass_type']) && $gp_item['pass_type'] !== '')
                ? ($gp_item['pass_type'] !== 'Full Day')
                : (!empty($gp_item['in_time']) && $gp_item['in_time'] != '00:00:00');

            if ($is_partial && empty($gp_item['actual_in_time'])) {
                if (!empty($gp_item['date']) && !empty($gp_item['in_time'])) {
                    $exp_datetime_str = $gp_item['date'] . ' ' . $gp_item['in_time'];
                    $exp_timestamp = strtotime($exp_datetime_str);
                    if ($exp_timestamp && $now_timestamp > $exp_timestamp) {
                        $is_overdue = true;
                        $overdue_count++;
                        $diff_seconds = $now_timestamp - $exp_timestamp;
                        $diff_hours = floor($diff_seconds / 3600);
                        $diff_days = floor($diff_seconds / 86400);

                        if ($diff_days >= 1) {
                            $overdue_text = $diff_days . 'd ' . ($diff_hours % 24) . 'h late';
                        } elseif ($diff_hours >= 1) {
                            $overdue_text = $diff_hours . 'h late';
                        } else {
                            $diff_mins = max(1, floor($diff_seconds / 60));
                            $overdue_text = $diff_mins . 'm late';
                        }
                    }
                }
            }
        } elseif ($gp_item['status'] == 'Completed') { 
            $completed_count++; 
        }
    }
    
    $gp_item['is_overdue'] = $is_overdue;
    $gp_item['overdue_text'] = $overdue_text;
}
unset($gp_item);
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-id-card-o"></i> <?php echo $this->lang->line('front_office'); ?></h1>
    </section>
    
    <section class="content">
        <!-- Modern KPI Stat Grid -->
        <div class="modern-stat-grid">
            <div class="modern-stat-card gp-stat-clickable" onclick="filterGatePasses('all')">
                <div class="modern-stat-info">
                    <div class="stat-label">Total Passes</div>
                    <div class="stat-value"><?php echo $total_passes; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(99, 102, 241, 0.12); color: #6366f1;">
                    <i class="fa fa-id-badge"></i>
                </div>
            </div>
            
            <div class="modern-stat-card gp-stat-clickable" onclick="filterGatePasses('approved')">
                <div class="modern-stat-info">
                    <div class="stat-label">Approved Passes</div>
                    <div class="stat-value text-success" style="color: #059669;"><?php echo $approved_count; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
                    <i class="fa fa-check-circle"></i>
                </div>
            </div>

            <div class="modern-stat-card gp-stat-clickable <?php echo ($overdue_count > 0) ? 'gp-card-overdue-alert' : ''; ?>" onclick="filterGatePasses('overdue')">
                <div class="modern-stat-info">
                    <div class="stat-label" style="color: #dc2626; font-weight: 700;">Overdue Return</div>
                    <div class="stat-value" style="color: #dc2626;"><?php echo $overdue_count; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(239, 68, 68, 0.15); color: #ef4444;">
                    <i class="fa fa-exclamation-triangle"></i>
                </div>
            </div>
            
            <div class="modern-stat-card gp-stat-clickable" onclick="filterGatePasses('completed')">
                <div class="modern-stat-info">
                    <div class="stat-label">Completed / Returned</div>
                    <div class="stat-value" style="color: #0284c7;"><?php echo $completed_count; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(14, 165, 233, 0.12); color: #0284c7;">
                    <i class="fa fa-flag-checkered"></i>
                </div>
            </div>

            <div class="modern-stat-card gp-stat-clickable" onclick="filterGatePasses('pending')">
                <div class="modern-stat-info">
                    <div class="stat-label">Pending Approval</div>
                    <div class="stat-value text-warning" style="color: #d97706;"><?php echo $pending_count; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b;">
                    <i class="fa fa-hourglass-half"></i>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary" id="route">
                    <div class="box-header ptbnull" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; padding: 12px 16px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <h3 class="box-title titlefix" style="margin: 0;"><i class="fa fa-list text-muted" style="margin-right: 6px;"></i> <?php echo $this->lang->line('gate_pass_list'); ?></h3>
                            
                            <!-- Quick Filter Pills -->
                            <div class="gp-filter-pill-group">
                                <button type="button" class="gp-filter-pill active" data-filter="all" onclick="filterGatePasses('all')">All (<?php echo $total_passes; ?>)</button>
                                <button type="button" class="gp-filter-pill" data-filter="approved" onclick="filterGatePasses('approved')">Approved (<?php echo $approved_count; ?>)</button>
                                <button type="button" class="gp-filter-pill pill-overdue <?php echo ($overdue_count > 0) ? 'has-overdue' : ''; ?>" data-filter="overdue" onclick="filterGatePasses('overdue')">⚠️ Overdue (<?php echo $overdue_count; ?>)</button>
                                <button type="button" class="gp-filter-pill" data-filter="completed" onclick="filterGatePasses('completed')">Completed (<?php echo $completed_count; ?>)</button>
                            </div>
                        </div>

                        <?php if ($this->rbac->hasPrivilege('front_office_gate_pass', 'can_add')) { ?>
                            <div class="box-tools">
                                <button type="button" id="btn-open-gatepass-drawer" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_gate_pass'); ?></button>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-messages table-responsive">
                            <div class="download_label"><?php echo $this->lang->line('gate_pass_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover example" id="frontoffice_gatepass_table">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('gate_pass_no'); ?></th>
                                        <th>Person Details</th>
                                        <th>Contact</th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th>Duration</th>
                                        <th><?php echo $this->lang->line('out_time'); ?></th>
                                        <th>In Time (Exp.)</th>
                                        <th>Actual In-Time</th>
                                        <th>Reason</th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($gate_passes)) {
                                        foreach ($gate_passes as $gatepass) {
                                            $row_class = '';
                                            $filter_types = 'all';
                                            if ($gatepass['status'] == 'Approved') {
                                                $filter_types .= ' approved';
                                                if ($gatepass['is_overdue']) {
                                                    $filter_types .= ' overdue';
                                                    $row_class = 'gp-row-overdue';
                                                }
                                            } elseif ($gatepass['status'] == 'Pending') {
                                                $filter_types .= ' pending';
                                            } elseif ($gatepass['status'] == 'Completed') {
                                                $filter_types .= ' completed';
                                            }

                                            $u_info = $gatepass['user_info'] ?? [];
                                            $user_name = !empty($u_info['name']) ? $u_info['name'] : $gatepass['user_details'];
                                            $user_adm = $u_info['admission_no'] ?? '';
                                            $user_class = $u_info['class_name'] ?? '';
                                            
                                            // Avatar
                                            if ($gatepass['user_type'] == 'staff') {
                                                $user_img = !empty($u_info['image']) ? base_url('uploads/staff_images/' . $u_info['image']) : base_url('uploads/staff_images/default_male.jpg');
                                            } else {
                                                $user_img = !empty($u_info['image']) ? base_url($u_info['image']) : base_url('uploads/student_images/no_image.png');
                                            }

                                            // Phone contact
                                            $phone_contact = !empty($u_info['father_phone']) ? $u_info['father_phone'] : (!empty($u_info['guardian_phone']) ? $u_info['guardian_phone'] : (!empty($u_info['mobileno']) ? $u_info['mobileno'] : ''));

                                            $is_partial = (isset($gatepass['pass_type']) && $gatepass['pass_type'] !== '')
                                                ? ($gatepass['pass_type'] !== 'Full Day')
                                                : (!empty($gatepass['in_time']) && $gatepass['in_time'] != '00:00:00');
                                            ?>
                                            <tr class="<?php echo $row_class; ?>" data-filter-type="<?php echo $filter_types; ?>">
                                                <td class="mailbox-name">
                                                    <code style="background: #f1f5f9; color: #4f46e5; padding: 2px 6px; border-radius: 4px; font-weight: 600;"><?php echo html_escape($gatepass['gate_pass_no']); ?></code>
                                                    <div style="margin-top: 3px;">
                                                        <span class="badge" style="background: #f8fafc; color: #475569; border: 1px solid #cbd5e1; font-size: 10px;"><?php echo ucfirst($gatepass['user_type']); ?></span>
                                                    </div>
                                                </td>
                                                <td class="mailbox-name">
                                                    <div style="display: flex; align-items: center; gap: 10px;">
                                                        <img src="<?php echo $user_img; ?>" alt="Photo" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover; border: 1.5px solid #e2e8f0; box-shadow: 0 1px 2px rgba(0,0,0,0.06); flex-shrink: 0;" onerror="this.src='<?php echo ($gatepass['user_type'] == 'staff') ? base_url('uploads/staff_images/default_male.jpg') : base_url('uploads/student_images/no_image.png'); ?>'">
                                                        <div>
                                                            <div style="font-weight: 700; color: #0f172a; font-size: 13.5px; line-height: 1.2;">
                                                                <?php echo html_escape($user_name); ?>
                                                            </div>
                                                            <div style="margin-top: 3px; display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                                                <?php if (!empty($user_adm)) { ?>
                                                                    <code style="background: #eef2ff; color: #4338ca; padding: 1px 5px; border-radius: 4px; font-weight: 600; font-size: 11px;"><?php echo html_escape($user_adm); ?></code>
                                                                <?php } ?>
                                                                <?php if (!empty($user_class)) { ?>
                                                                    <span style="font-size: 11px; color: #64748b; font-weight: 600;"><?php echo html_escape($user_class); ?></span>
                                                                <?php } ?>
                                                            </div>
                                                            <?php if ($gatepass['is_overdue']) { ?>
                                                                <span class="label label-danger gp-overdue-tag" title="User has not returned by expected time"><i class="fa fa-clock-o"></i> OVERDUE (<?php echo $gatepass['overdue_text']; ?>)</span>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="mailbox-name white-space-nowrap">
                                                    <?php if (!empty($phone_contact)) { ?>
                                                        <div style="display: inline-flex; align-items: center; gap: 4px;">
                                                            <a href="tel:<?php echo html_escape($phone_contact); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="Call: <?php echo html_escape($phone_contact); ?>" style="color: #0284c7; border-radius: 4px;">
                                                                <i class="fa fa-phone"></i> <?php echo html_escape($phone_contact); ?>
                                                            </a>
                                                            <a href="https://wa.me/91<?php echo preg_replace('/[^0-9]/', '', $phone_contact); ?>" target="_blank" class="btn btn-xs" data-toggle="tooltip" title="WhatsApp" style="background: #25d366; color: #fff; border-radius: 4px; padding: 2px 6px;">
                                                                <i class="fa fa-whatsapp"></i>
                                                            </a>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class="text-muted">-</span>
                                                    <?php } ?>
                                                </td>
                                                <td class="mailbox-name white-space-nowrap">
                                                    <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($gatepass['date'])); ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php 
                                                    if ($is_partial) {
                                                        echo '<span class="badge" style="background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd;">Partial Time</span>';
                                                    } else {
                                                        echo '<span class="badge" style="background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;">Full Day</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td class="mailbox-name"><?php echo html_escape($gatepass['out_time']); ?></td>
                                                <td class="mailbox-name">
                                                    <?php 
                                                    if (!empty($gatepass['in_time']) && $gatepass['in_time'] != '00:00:00') {
                                                        if ($gatepass['is_overdue']) {
                                                            echo '<span style="color: #dc2626; font-weight: 700;"><i class="fa fa-exclamation-circle"></i> ' . html_escape($gatepass['in_time']) . '</span>';
                                                        } else {
                                                            echo html_escape($gatepass['in_time']);
                                                        }
                                                    } else {
                                                        echo '-';
                                                    }
                                                    ?>
                                                </td>
                                                <td class="mailbox-name white-space-nowrap">
                                                    <?php if (!empty($gatepass['actual_in_time']) && $gatepass['actual_in_time'] != '00:00:00') { ?>
                                                        <span style="font-weight: 600; color: #059669;"><?php echo html_escape($gatepass['actual_in_time']); ?></span>
                                                    <?php } elseif ($is_partial && $gatepass['status'] != 'Rejected') { ?>
                                                        <?php if ($gatepass['is_overdue']) { ?>
                                                            <span class="text-danger" style="font-weight: 700; font-size: 11px; margin-right: 6px;"><i class="fa fa-warning"></i> Not Returned (Overdue)</span>
                                                        <?php } else { ?>
                                                            <span class="text-muted" style="font-style: italic; font-size: 11px; margin-right: 6px;">Not Returned</span>
                                                        <?php } ?>
                                                        <?php if ($this->rbac->hasPrivilege('front_office_gate_pass', 'can_edit')) { ?>
                                                            <button type="button" class="btn btn-xs btn-quick-mark-in" onclick="quickMarkInTime('<?php echo $gatepass['id']; ?>', '<?php echo html_escape($gatepass['gate_pass_no']); ?>')" data-toggle="tooltip" title="Mark In-Time & Complete" style="display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 50%; background: #10b981; color: #fff; border: none; padding: 0; cursor: pointer; transition: all 0.2s; box-shadow: 0 1px 3px rgba(16,185,129,0.4);">
                                                                <i class="fa fa-plus" style="font-size: 11px; font-weight: bold;"></i>
                                                            </button>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <span class="text-muted">-</span>
                                                    <?php } ?>
                                                </td>
                                                <td class="mailbox-name"><?php echo html_escape($gatepass['reason']); ?></td>
                                                <td class="mailbox-name">
                                                    <?php
                                                    if ($gatepass['status'] == 'Pending') {
                                                        echo "<span class='label label-warning'>Pending</span>";
                                                    } elseif ($gatepass['status'] == 'Approved') {
                                                        if ($gatepass['is_overdue']) {
                                                            echo "<span class='label label-danger' style='animation: pulseWarning 1.5s infinite;'><i class='fa fa-exclamation-triangle'></i> OVERDUE</span>";
                                                        } else {
                                                            echo "<span class='label label-success'>Approved</span>";
                                                        }
                                                    } elseif ($gatepass['status'] == 'Rejected') {
                                                        echo "<span class='label label-danger'>Rejected</span>";
                                                    } elseif ($gatepass['status'] == 'Completed') {
                                                        echo "<span class='label label-info'>Completed</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td class="mailbox-date text-right white-space-nowrap">
                                                    <div class="dropdown" style="display: inline-block;">
                                                        <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius: 6px; padding: 4px 8px; color: #475569; background: #f8fafc; border-color: #e2e8f0;">
                                                            <i class="fa fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-right" style="border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; min-width: 150px; padding: 4px 0; font-size: 13px; z-index: 1050;">
                                                            <?php if ($this->rbac->hasPrivilege('front_office_gate_pass', 'can_edit')) { ?>
                                                                <li>
                                                                    <a href="javascript:void(0);" onclick="updateStatus('<?php echo $gatepass['id']; ?>', '<?php echo $gatepass['status']; ?>', '<?php echo $gatepass['actual_in_time']; ?>')">
                                                                        <i class="fa fa-pencil text-warning" style="width: 18px;"></i> <?php echo $this->lang->line('edit') ? $this->lang->line('edit') : 'Edit'; ?> / Status
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                            
                                                            <?php if ($gatepass['status'] == 'Approved' || $gatepass['status'] == 'Completed') { ?>
                                                                <li>
                                                                    <a href="javascript:void(0);" onclick="printGatePass('<?php echo $gatepass['id']; ?>')">
                                                                        <i class="fa fa-print text-info" style="width: 18px;"></i> <?php echo $this->lang->line('print'); ?>
                                                                    </a>
                                                                </li>
                                                            <?php } ?>

                                                            <?php if ($this->rbac->hasPrivilege('front_office_gate_pass', 'can_delete')) { ?>
                                                                <li role="separator" class="divider" style="margin: 4px 0;"></li>
                                                                <li>
                                                                    <a href="<?php echo base_url(); ?>admin/gatepass/delete/<?php echo $gatepass['id'] ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');" style="color: #ef4444;">
                                                                        <i class="fa fa-trash text-danger" style="width: 18px;"></i> <?php echo $this->lang->line('delete'); ?>
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Slide-in Right Drawer for Adding Gate Pass -->
<div id="gatepass-drawer-overlay" class="modern-drawer-overlay"></div>
<div id="gatepass-drawer-panel" class="modern-drawer-panel">
    <form id="formadd" action="<?php echo site_url('admin/gatepass/create') ?>" method="post" accept-charset="utf-8">
        <div class="modern-drawer-header">
            <h4 class="modern-drawer-title"><i class="fa fa-id-badge" style="color: var(--primary-theme-color, #4f46e5);"></i> <?php echo $this->lang->line('add_gate_pass'); ?></h4>
            <button type="button" class="modern-drawer-close" id="btn-close-gatepass-drawer">&times;</button>
        </div>
        <div class="modern-drawer-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('user_type'); ?></label><small class="req"> *</small>
                        <select class="form-control" name="user_type" id="user_type" onchange="resetUserSelect()">
                            <option value="student"><?php echo $this->lang->line('student'); ?></option>
                            <option value="staff"><?php echo $this->lang->line('staff'); ?></option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                        <select class="form-control select2" style="width:100%" name="user_id" id="user_id">
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('date'); ?></label><small class="req"> *</small>
                        <input type="text" name="date" class="form-control date" id="date" readonly value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Duration</label><small class="req"> *</small>
                        <select class="form-control" name="pass_type" id="pass_type" onchange="toggleOutTime()">
                            <option value="Partial">Partial Time</option>
                            <option value="Full Day">Full Day</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6" id="out_time_container">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('out_time'); ?></label><small class="req"> *</small>
                        <div class="input-group">
                            <input type="text" name="out_time" class="form-control timepicker" id="out_time">
                            <div class="input-group-addon" style="cursor: pointer;" onclick="$('#out_time').focus();">
                                <i class="fa fa-clock-o"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6" id="in_time_container_add">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('in_time'); ?> (Expected)</label>
                        <div class="input-group">
                            <input type="text" name="in_time" class="form-control timepicker" id="in_time_add">
                            <div class="input-group-addon" style="cursor: pointer;" onclick="$('#in_time_add').focus();">
                                <i class="fa fa-clock-o"></i>
                            </div>
                        </div>
                        <span id="in_time_error"></span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label><?php echo $this->lang->line('reason'); ?> <small class="req"> *</small></label>
                <textarea name="reason" class="form-control" id="reason" rows="3" placeholder="State purpose for leaving campus..." required></textarea>
            </div>
        </div>
        <div class="modern-drawer-footer" style="display: flex; justify-content: flex-end; gap: 8px;">
            <button type="button" class="btn btn-default" id="btn-cancel-gatepass-drawer"><?php echo $this->lang->line('cancel'); ?></button>
            <button type="submit" class="btn btn-primary" id="btn-save-gatepass" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><i class="fa fa-check"></i> <?php echo $this->lang->line('save') ?></button>
            <button type="submit" class="btn btn-info" id="btn-save-print-gatepass" name="save_and_print" value="1" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><i class="fa fa-print"></i> Save & Print</button>
        </div>
    </form>
</div>

<!-- Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-pencil text-primary" style="margin-right: 6px;"></i> <?php echo $this->lang->line('update_status'); ?></h4>
            </div>
            <form id="statusform" action="<?php echo site_url('admin/gatepass/update_status') ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id" id="update_id">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('status'); ?></label>
                        <select name="status" id="update_status" class="form-control" onchange="checkCompletedStatus()">
                            <option value="Pending">Pending</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                    <div class="form-group" id="in_time_div" style="display:none;">
                        <label>Actual In-Time</label>
                        <div class="input-group">
                            <input type="text" name="in_time" class="form-control timepicker" id="in_time">
                            <div class="input-group-addon" style="cursor: pointer;" onclick="$('#in_time').focus();">
                                <i class="fa fa-clock-o"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                    <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Custom Quick Gatepass In-Time Confirmation Modal -->
<div class="modal fade" id="quickGatepassTimeModal" tabindex="-1" role="dialog" aria-labelledby="quickGatepassTimeModalLabel" style="z-index: 1060;">
    <div class="modal-dialog modal-sm" role="document" style="max-width: 380px; margin-top: 15vh;">
        <div class="modal-content" style="border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); border: none;">
            <div class="modal-body" style="padding: 24px; text-align: center;">
                <div style="width: 52px; height: 52px; border-radius: 50%; background: #ecfdf5; color: #10b981; display: inline-flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 16px;">
                    <i class="fa fa-check-circle"></i>
                </div>
                <h4 style="font-weight: 700; color: #0f172a; margin: 0 0 8px 0;">Mark Return In-Time</h4>
                <p style="color: #64748b; font-size: 13px; margin: 0 0 16px 0;" id="qgp-modal-desc"></p>
                <div style="margin-bottom: 20px; text-align: left;">
                    <label style="font-size: 12px; color: #475569; font-weight: 600; margin-bottom: 4px; display: block;">Return In-Time:</label>
                    <div class="input-group">
                        <input type="text" name="quick_in_time" class="form-control timepicker" id="qgp-edit-time" style="height: 38px; font-size: 15px; font-weight: 600; color: #0f172a; border-radius: 6px 0 0 6px; text-align: center;">
                        <div class="input-group-addon" style="background: #f8fafc; border-radius: 0 6px 6px 0; color: #64748b; cursor: pointer;" onclick="$('#qgp-edit-time').focus();">
                            <i class="fa fa-clock-o"></i>
                        </div>
                    </div>
                    <small class="text-muted" style="font-size: 11px; margin-top: 3px; display: block; text-align: center;">Auto-filled with current time. You can edit if needed.</small>
                </div>
                <input type="hidden" id="qgp-id" value="">
                <div style="display: flex; gap: 10px; justify-content: center;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 6px; padding: 8px 18px; font-weight: 600; color: #475569; min-width: 90px;"><?php echo $this->lang->line('cancel'); ?></button>
                    <button type="button" class="btn btn-primary" id="btn-confirm-quick-gp-time" style="border-radius: 6px; padding: 8px 20px; font-weight: 600; background: #10b981; border-color: #10b981; min-width: 100px;">Confirm</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Select2 CSS/JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
/* Modern Compact KPI Stats Styles */
.modern-stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
    gap: 10px;
    margin-bottom: 15px;
}

.modern-stat-card {
    background: #ffffff;
    border-radius: 8px;
    padding: 10px 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    border: 1px solid #e2e8f0;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.gp-stat-clickable {
    cursor: pointer;
}

.gp-stat-clickable:hover {
    transform: translateY(-1px);
    box-shadow: 0 3px 6px -1px rgba(0, 0, 0, 0.08);
    border-color: #cbd5e1;
}

.gp-card-overdue-alert {
    border: 1.5px solid #fca5a5 !important;
    background: #fef2f2 !important;
}

.pulse-dot {
    display: inline-block;
    width: 7px;
    height: 7px;
    background-color: #ef4444;
    border-radius: 50%;
    margin-left: 4px;
    animation: pulseDot 1.5s infinite;
}

@keyframes pulseDot {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
    70% { transform: scale(1.1); box-shadow: 0 0 0 5px rgba(239, 68, 68, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
}

@keyframes pulseWarning {
    0% { opacity: 1; }
    50% { opacity: 0.6; }
    100% { opacity: 1; }
}

/* Quick Filter Pills */
.gp-filter-pill-group {
    display: inline-flex;
    align-items: center;
    background: #f1f5f9;
    padding: 2px;
    border-radius: 16px;
    gap: 3px;
}

.gp-filter-pill {
    background: transparent;
    border: none;
    padding: 3px 10px;
    font-size: 11.5px;
    font-weight: 600;
    color: #475569;
    border-radius: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
    outline: none !important;
}

.gp-filter-pill:hover {
    color: #0f172a;
    background: #e2e8f0;
}

.gp-filter-pill.active {
    background: #ffffff;
    color: #0284c7;
    box-shadow: 0 1px 2px rgba(0,0,0,0.08);
}

.gp-filter-pill.pill-overdue.has-overdue {
    color: #dc2626;
    font-weight: 700;
}

.gp-filter-pill.pill-overdue.active {
    background: #ef4444 !important;
    color: #ffffff !important;
}

/* Row Overdue Highlight */
.gp-row-overdue {
    background-color: #fff1f2 !important;
    border-left: 4px solid #ef4444 !important;
}

.gp-row-overdue:hover {
    background-color: #ffe4e6 !important;
}

.gp-overdue-tag {
    display: block;
    margin-top: 3px;
    font-size: 9.5px;
    font-weight: 700;
    letter-spacing: 0.3px;
    border-radius: 4px;
    padding: 1px 5px;
    width: fit-content;
}

.stat-label {
    font-size: 11.5px;
    font-weight: 600;
    color: #64748b;
    margin-bottom: 2px;
    text-transform: capitalize;
    letter-spacing: 0.2px;
}

.stat-value {
    font-size: 18px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.1;
}

.modern-stat-icon {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
}

/* Modern Drawer Styles */
.modern-drawer-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(15, 23, 42, 0.4);
    backdrop-filter: blur(2px);
    z-index: 1040;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s cubic-bezier(0.16, 1, 0.3, 1), visibility 0.3s;
}

.modern-drawer-overlay.is-active {
    opacity: 1;
    visibility: visible;
}

.modern-drawer-panel {
    position: fixed;
    top: 0;
    right: -520px;
    width: 500px;
    max-width: 90vw;
    height: 100%;
    background: #ffffff;
    z-index: 1050;
    box-shadow: -10px 0 25px -5px rgba(0, 0, 0, 0.1), -8px 0 10px -6px rgba(0, 0, 0, 0.1);
    transition: right 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    display: flex;
    flex-direction: column;
}

.modern-drawer-panel.is-open {
    right: 0;
}

.modern-drawer-header {
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #fafafa;
}

.modern-drawer-title {
    font-size: 16px;
    font-weight: 600;
    color: #1e293b;
    margin: 0;
}

.modern-drawer-close {
    background: none;
    border: none;
    font-size: 24px;
    line-height: 1;
    color: #94a3b8;
    cursor: pointer;
    padding: 0;
    margin: 0;
    transition: color 0.2s;
}

.modern-drawer-close:hover {
    color: #0f172a;
}

.modern-drawer-body {
    padding: 20px;
    overflow-y: auto;
    flex-grow: 1;
}

.modern-drawer-footer {
    padding: 14px 20px;
    border-top: 1px solid #f1f5f9;
    background: #fafafa;
}

/* Select2 in Drawer */
.select2-container--open {
    z-index: 99999999 !important;
}

.select2-dropdown {
    z-index: 99999999 !important;
}

.bootstrap-timepicker-widget.dropdown-menu,
.bootstrap-timepicker-widget {
    z-index: 100000 !important;
}

/* Fix Select2 Clear 'x' icon positioning & UI */
.select2-container--default .select2-selection--single .select2-selection__clear {
    position: absolute !important;
    right: 26px !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
    margin: 0 !important;
    padding: 0 !important;
    font-size: 14px !important;
    font-weight: bold !important;
    color: #94a3b8 !important;
    cursor: pointer !important;
    z-index: 5 !important;
    line-height: 1 !important;
    transition: color 0.15s ease !important;
}

.select2-container--default .select2-selection--single .select2-selection__clear:hover {
    color: #ef4444 !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    padding-right: 44px !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    right: 6px !important;
    top: 50% !important;
    transform: translateY(-50%) !important;
}

/* Keep table horizontally scrollable while allowing space for dropdown menus */
.table-responsive {
    overflow-x: auto !important;
    overflow-y: visible !important;
    padding-bottom: 60px;
    margin-bottom: -60px;
}
</style>

<script>
    var lastClickedSubmitBtn = null;

    function filterGatePasses(type) {
        $('.gp-filter-pill').removeClass('active');
        $('.gp-filter-pill[data-filter="' + type + '"]').addClass('active');

        var table = $('#frontoffice_gatepass_table');
        if (type === 'all') {
            table.find('tbody tr').show();
        } else {
            table.find('tbody tr').each(function() {
                var rowFilters = $(this).attr('data-filter-type') || '';
                if (rowFilters.indexOf(type) !== -1) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        }
    }

    $(document).ready(function () {
        $('#user_id').select2({
            dropdownParent: $('#gatepass-drawer-panel'),
            width: '100%',
            placeholder: 'Search name / roll no / ID',
            allowClear: true,
            minimumInputLength: 1,
            ajax: {
                url: '<?php echo site_url('admin/gatepass/search_user'); ?>',
                type: 'post',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        keyword: params.term,
                        user_type: $('#user_type').val()
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            }
        });

        $('#btn-open-gatepass-drawer').on('click', function() {
            $('#formadd').trigger("reset");
            $('#user_id').val(null).trigger('change');
            toggleOutTime();
            openGatepassDrawer();
        });

        $('#btn-close-gatepass-drawer, #btn-cancel-gatepass-drawer, #gatepass-drawer-overlay').on('click', function() {
            closeGatepassDrawer();
        });
        
        $('.timepicker').timepicker({
            showInputs: false,
            showMeridian: false
        });

        $('#formadd button[type=submit]').on('click', function() {
            lastClickedSubmitBtn = $(this);
        });

        $('#formadd').on('submit', (function (e) {
            e.preventDefault();
            var $this = lastClickedSubmitBtn || $(this).find("button[type=submit]:first");
            var isSaveAndPrint = ($this.attr('id') === 'btn-save-print-gatepass');

            $('#formadd').find('.text-danger').remove();

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
                                var $elem = $('#' + index);
                                if ($elem.length === 0) {
                                    $elem = $('[name="' + index + '"]');
                                }
                                if ($elem.length) {
                                    $elem.closest('.form-group').append('<span class="text-danger">' + value + '</span>');
                                }
                            }
                        });
                    } else {
                        successMsg(res.message);
                        closeGatepassDrawer();
                        if (isSaveAndPrint && res.id) {
                            printGatePass(res.id);
                            setTimeout(function() {
                                window.location.reload(true);
                            }, 1000);
                        } else {
                            setTimeout(function() {
                                window.location.reload(true);
                            }, 500);
                        }
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                },
                complete: function (data) {
                    $this.button('reset');
                }
            });
        }));

        $('#statusform').on('submit', (function (e) {
            e.preventDefault();
            var $this = $(this).find("button[type=submit]");
            $this.button('loading');
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: $(this).serialize(),
                dataType: 'json',
                success: function (res) {
                    $this.button('reset');
                    if (res.status == "fail") {
                        errorMsg(res.message);
                    } else {
                        $('#statusModal').modal('hide');
                        successMsg(res.message);
                        window.location.reload(true);
                    }
                }
            });
        }));
    });

    function resetUserSelect() {
        $('#user_id').val(null).trigger('change');
    }

    function toggleOutTime() {
        if ($('#pass_type').val() == 'Full Day') {
            $('#in_time_container_add').hide();
            $('#in_time_add').val('');
        } else {
            $('#in_time_container_add').show();
        }
    }

    function updateStatus(id, current_status, actual_in_time) {
        $('#update_id').val(id);
        $('#update_status').val(current_status);
        if (actual_in_time) {
            $('#in_time').val(actual_in_time);
        } else {
            $('#in_time').val(getGatepassCurrentTimeString());
        }
        checkCompletedStatus();
        $('#statusModal').modal('show');
    }

    function checkCompletedStatus() {
        if ($('#update_status').val() == 'Completed') {
            $('#in_time_div').show();
            if (!$('#in_time').val()) {
                $('#in_time').val(getGatepassCurrentTimeString());
            }
        } else {
            $('#in_time_div').hide();
        }
    }
    
    function quickMarkInTime(id, passNo) {
        var currentTime = getGatepassCurrentTimeString();
        $('#qgp-id').val(id);
        $('#qgp-edit-time').val(currentTime);
        $('#qgp-modal-desc').text('Mark return in-time and complete Gate Pass ' + passNo + '.');
        $('#quickGatepassTimeModal').modal('show');
        $('#qgp-edit-time').timepicker({
            showInputs: false,
            showMeridian: false,
            defaultTime: currentTime
        });
    }

    $(document).on('click', '#btn-confirm-quick-gp-time', function () {
        var $btn = $(this);
        var id = $('#qgp-id').val();
        var currentTime = $('#qgp-edit-time').val() || getGatepassCurrentTimeString();

        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

        $.ajax({
            url: '<?php echo site_url('admin/gatepass/update_status'); ?>',
            type: 'POST',
            data: {
                id: id,
                status: 'Completed',
                in_time: currentTime
            },
            dataType: 'json',
            success: function (res) {
                if (res.status === 'success') {
                    $('#quickGatepassTimeModal').modal('hide');
                    successMsg(res.message);
                    window.location.reload(true);
                } else {
                    errorMsg(res.message || 'Failed to update');
                    $btn.prop('disabled', false).text('Confirm');
                }
            },
            error: function () {
                errorMsg('Failed to update');
                $btn.prop('disabled', false).text('Confirm');
            }
        });
    });

    function printGatePass(id) {
        var url = '<?php echo base_url() ?>admin/gatepass/print_gatepass/' + id;
        $.ajax({
            url: url,
            type: "POST",
            success: function (data) {
                var popupWin = window.open('', '_blank', 'width=800,height=600');
                popupWin.document.open();
                popupWin.document.write('<html><head><title>Gate Pass</title></head><body onload="window.print()">' + data + '</body></html>');
                popupWin.document.close();
            }
        });
    }

    function getGatepassCurrentTimeString() {
        var now = new Date();
        var hours = now.getHours();
        var minutes = now.getMinutes();
        var formattedHours = hours < 10 ? '0' + hours : hours;
        var formattedMinutes = minutes < 10 ? '0' + minutes : minutes;
        return formattedHours + ':' + formattedMinutes;
    }

    function openGatepassDrawer() {
        var currentTime = getGatepassCurrentTimeString();
        if (!$('#out_time').val()) {
            $('#out_time').val(currentTime);
        }

        $('#gatepass-drawer-overlay').addClass('is-active');
        $('#gatepass-drawer-panel').addClass('is-open');
        $('body').addClass('drawer-open').css('overflow', 'hidden');
    }

    function closeGatepassDrawer() {
        $('#gatepass-drawer-panel').removeClass('is-open');
        $('#gatepass-drawer-overlay').removeClass('is-active');
        $('body').removeClass('drawer-open').css('overflow', '');
    }
</script>
