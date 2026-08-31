<?php
$total_passes = count($gate_passes);
$out_count = 0;
$returned_count = 0;
$day_pass_count = 0;
$holiday_pass_count = 0;
$overdue_count = 0;

$now_timestamp = time();

foreach ($gate_passes as &$gp_item) {
    $is_overdue = false;
    $overdue_text = '';
    $is_returned_late = false;
    $returned_late_text = '';

    if (isset($gp_item['status'])) {
        if ($gp_item['status'] == 'Out') { 
            $out_count++;
            
            // Check if overdue
            if (!empty($gp_item['expected_in_date'])) {
                $exp_datetime_str = $gp_item['expected_in_date'];
                if (!empty($gp_item['expected_in_time'])) {
                    $exp_datetime_str .= ' ' . $gp_item['expected_in_time'];
                } else {
                    $exp_datetime_str .= ' 23:59:59';
                }
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
        } elseif ($gp_item['status'] == 'Returned') { 
            $returned_count++; 

            // Check if returned after due date/time
            if (!empty($gp_item['expected_in_date']) && !empty($gp_item['actual_in_date'])) {
                $exp_datetime_str = $gp_item['expected_in_date'] . ' ' . (!empty($gp_item['expected_in_time']) ? $gp_item['expected_in_time'] : '23:59:59');
                $act_datetime_str = $gp_item['actual_in_date'] . ' ' . (!empty($gp_item['actual_in_time']) ? $gp_item['actual_in_time'] : '00:00:00');
                
                $exp_ts = strtotime($exp_datetime_str);
                $act_ts = strtotime($act_datetime_str);
                
                if ($exp_ts && $act_ts && $act_ts > $exp_ts) {
                    $is_returned_late = true;
                    $diff_seconds = $act_ts - $exp_ts;
                    $diff_hours = floor($diff_seconds / 3600);
                    $diff_days = floor($diff_seconds / 86400);

                    if ($diff_days >= 1) {
                        $returned_late_text = $diff_days . 'd ' . ($diff_hours % 24) . 'h late';
                    } elseif ($diff_hours >= 1) {
                        $returned_late_text = $diff_hours . 'h late';
                    } else {
                        $diff_mins = max(1, floor($diff_seconds / 60));
                        $returned_late_text = $diff_mins . 'm late';
                    }
                }
            }
        }
    }
    
    $gp_item['is_overdue'] = $is_overdue;
    $gp_item['overdue_text'] = $overdue_text;
    $gp_item['is_returned_late'] = $is_returned_late;
    $gp_item['returned_late_text'] = $returned_late_text;

    if (isset($gp_item['pass_type'])) {
        if ($gp_item['pass_type'] == 'Holiday Pass') {
            $holiday_pass_count++;
        } else {
            $day_pass_count++;
        }
    }
}
unset($gp_item);
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-building-o"></i> <?php echo $this->lang->line('hostel'); ?></h1>
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
            
            <div class="modern-stat-card gp-stat-clickable" onclick="filterGatePasses('out')">
                <div class="modern-stat-info">
                    <div class="stat-label">Currently Out</div>
                    <div class="stat-value text-warning" style="color: #d97706;"><?php echo $out_count; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b;">
                    <i class="fa fa-sign-out"></i>
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
            
            <div class="modern-stat-card gp-stat-clickable" onclick="filterGatePasses('returned')">
                <div class="modern-stat-info">
                    <div class="stat-label">Returned / Completed</div>
                    <div class="stat-value text-success" style="color: #059669;"><?php echo $returned_count; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
                    <i class="fa fa-check-circle"></i>
                </div>
            </div>
            
            <div class="modern-stat-card gp-stat-clickable" onclick="filterGatePasses('holiday')">
                <div class="modern-stat-info">
                    <div class="stat-label">Holiday Passes</div>
                    <div class="stat-value" style="color: #0284c7;"><?php echo $holiday_pass_count; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(14, 165, 233, 0.12); color: #0284c7;">
                    <i class="fa fa-plane"></i>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary" id="route">
                    <div class="box-header ptbnull" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; padding: 12px 16px;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <h3 class="box-title titlefix" style="margin: 0;"><i class="fa fa-list text-muted" style="margin-right: 6px;"></i> <?php echo $this->lang->line('hostel_gate_pass'); ?> List</h3>
                            
                            <!-- Quick Filter Pills -->
                            <div class="gp-filter-pill-group">
                                <button type="button" class="gp-filter-pill active" data-filter="all" onclick="filterGatePasses('all')">All (<?php echo $total_passes; ?>)</button>
                                <button type="button" class="gp-filter-pill" data-filter="out" onclick="filterGatePasses('out')">Currently Out (<?php echo $out_count; ?>)</button>
                                <button type="button" class="gp-filter-pill pill-overdue <?php echo ($overdue_count > 0) ? 'has-overdue' : ''; ?>" data-filter="overdue" onclick="filterGatePasses('overdue')">⚠️ Overdue Return (<?php echo $overdue_count; ?>)</button>
                                <button type="button" class="gp-filter-pill" data-filter="returned" onclick="filterGatePasses('returned')">Returned (<?php echo $returned_count; ?>)</button>
                            </div>
                        </div>

                        <?php if ($this->rbac->hasPrivilege('hostel_gate_pass', 'can_add')) { ?>
                            <div class="box-tools">
                                <button type="button" id="btn-open-hostelgatepass-drawer" class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i> Add Gate Pass
                                </button>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) { ?>
                            <?php echo $this->session->flashdata('msg'); ?>
                        <?php } ?>
                        <div class="mailbox-messages table-responsive">
                            <div class="download_label"><?php echo $this->lang->line('hostel_gate_pass'); ?> List</div>
                            <table class="table table-striped table-bordered table-hover example" id="hostel_gatepass_table">
                                <thead>
                                    <tr>
                                        <th>Student Details</th>
                                        <th>Contact / Parent</th>
                                        <th>Going To</th>
                                        <th>Pass Type</th>
                                        <th>Out Date & Time</th>
                                        <th>Expected In</th>
                                        <th>Actual In</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($gate_passes)) {
                                        foreach ($gate_passes as $gp) {
                                            $row_class = '';
                                            $filter_types = 'all';
                                            if ($gp['status'] == 'Out') {
                                                $filter_types .= ' out';
                                                if ($gp['is_overdue']) {
                                                    $filter_types .= ' overdue';
                                                    $row_class = 'gp-row-overdue';
                                                }
                                            } elseif ($gp['status'] == 'Returned') {
                                                $filter_types .= ' returned';
                                            }
                                            if (isset($gp['pass_type']) && $gp['pass_type'] == 'Holiday Pass') {
                                                $filter_types .= ' holiday';
                                            } else {
                                                $filter_types .= ' daypass';
                                            }

                                            $phone_contact = !empty($gp['father_phone']) ? $gp['father_phone'] : (!empty($gp['guardian_phone']) ? $gp['guardian_phone'] : (!empty($gp['mobileno']) ? $gp['mobileno'] : ''));
                                            
                                            // Student photo path
                                            $student_img = !empty($gp['image']) ? base_url($gp['image']) : base_url('uploads/student_images/no_image.png');
                                            ?>
                                            <tr class="<?php echo $row_class; ?>" data-filter-type="<?php echo $filter_types; ?>">
                                                <td class="mailbox-name">
                                                    <div style="display: flex; align-items: center; gap: 10px;">
                                                        <img src="<?php echo $student_img; ?>" alt="Student Photo" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover; border: 1.5px solid #e2e8f0; box-shadow: 0 1px 2px rgba(0,0,0,0.06); flex-shrink: 0;" onerror="this.src='<?php echo base_url('uploads/student_images/no_image.png'); ?>'">
                                                        <div>
                                                            <div style="font-weight: 700; color: #0f172a; font-size: 13.5px; line-height: 1.2;">
                                                                <?php echo html_escape($gp['firstname'] . ' ' . $gp['lastname']); ?>
                                                            </div>
                                                            <div style="margin-top: 3px; display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                                                <code style="background: #eef2ff; color: #4338ca; padding: 1px 5px; border-radius: 4px; font-weight: 600; font-size: 11px;"><?php echo html_escape($gp['admission_no']); ?></code>
                                                                <span style="font-size: 11px; color: #64748b; font-weight: 600;"><?php echo !empty($gp['class_name']) ? html_escape($gp['class_name'] . ' (' . $gp['section_name'] . ')') : '-'; ?></span>
                                                            </div>
                                                            <?php if ($gp['is_overdue']) { ?>
                                                                <span class="label label-danger gp-overdue-tag" title="Student has not returned by expected time"><i class="fa fa-clock-o"></i> OVERDUE (<?php echo $gp['overdue_text']; ?>)</span>
                                                            <?php } elseif (!empty($gp['is_returned_late'])) { ?>
                                                                <span class="label" style="background: #fef3c7; color: #92400e; border: 1px solid #fde68a; font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 4px;" title="Student returned after expected due date/time"><i class="fa fa-clock-o"></i> Returned Late (+<?php echo $gp['returned_late_text']; ?>)</span>
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
                                                            <a href="https://wa.me/91<?php echo preg_replace('/[^0-9]/', '', $phone_contact); ?>" target="_blank" class="btn btn-xs" data-toggle="tooltip" title="WhatsApp Parent" style="background: #25d366; color: #fff; border-radius: 4px; padding: 2px 6px;">
                                                                <i class="fa fa-whatsapp"></i>
                                                            </a>
                                                        </div>
                                                    <?php } else { ?>
                                                        <span class="text-muted">-</span>
                                                    <?php } ?>
                                                </td>
                                                <td class="mailbox-name"><?php echo html_escape($gp['going_to']); ?></td>
                                                <td class="mailbox-name">
                                                    <?php 
                                                    $ptype = isset($gp['pass_type']) ? $gp['pass_type'] : 'Day Pass';
                                                    if ($ptype == 'Holiday Pass') {
                                                        echo '<span class="badge" style="background: #fdf4ff; color: #9333ea; border: 1px solid #f5d0fe;"><i class="fa fa-plane"></i> Holiday Pass</span>';
                                                    } else {
                                                        echo '<span class="badge" style="background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd;"><i class="fa fa-sun-o"></i> Day Pass</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td class="mailbox-name white-space-nowrap">
                                                    <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($gp['out_date'])) . ' ' . (!empty($gp['out_time']) ? date("h:i A", strtotime($gp['out_time'])) : ''); ?>
                                                </td>
                                                <td class="mailbox-name white-space-nowrap">
                                                    <?php 
                                                    $exp_str = '';
                                                    if (!empty($gp['expected_in_date'])) {
                                                        $exp_str .= date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($gp['expected_in_date'])) . ' ';
                                                    }
                                                    if (!empty($gp['expected_in_time'])) {
                                                        $exp_str .= date("h:i A", strtotime($gp['expected_in_time']));
                                                    }
                                                    if (!empty($exp_str)) {
                                                        if ($gp['is_overdue']) {
                                                            echo '<span style="color: #dc2626; font-weight: 700;"><i class="fa fa-exclamation-circle"></i> ' . $exp_str . '</span>';
                                                        } else {
                                                            echo $exp_str;
                                                        }
                                                    } else {
                                                        echo '-';
                                                    }
                                                    ?>
                                                </td>
                                                <td class="mailbox-name white-space-nowrap">
                                                    <?php if ($gp['status'] == 'Returned') { ?>
                                                        <div>
                                                            <span style="font-weight: 600; color: #059669;">
                                                                <?php 
                                                                $act_str = '';
                                                                if (!empty($gp['actual_in_date'])) {
                                                                    $act_str .= date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($gp['actual_in_date'])) . ' ';
                                                                }
                                                                if (!empty($gp['actual_in_time'])) {
                                                                    $act_str .= date("h:i A", strtotime($gp['actual_in_time']));
                                                                }
                                                                echo !empty($act_str) ? $act_str : 'Returned';
                                                                ?>
                                                            </span>
                                                            <?php if (!empty($gp['is_returned_late'])) { ?>
                                                                <div style="margin-top: 2px;">
                                                                    <span class="badge" style="background: #fffbeb; color: #b45309; border: 1px solid #fde68a; font-size: 10px; font-weight: 700;">
                                                                        <i class="fa fa-exclamation-triangle text-warning"></i> +<?php echo $gp['returned_late_text']; ?> late
                                                                    </span>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    <?php } else { ?>
                                                        <?php if ($gp['is_overdue']) { ?>
                                                            <span class="text-danger" style="font-weight: 700; font-size: 11px; margin-right: 6px;"><i class="fa fa-warning"></i> Not Returned (Overdue)</span>
                                                        <?php } else { ?>
                                                            <span class="text-muted" style="font-style: italic; font-size: 11px; margin-right: 6px;">Out Campus</span>
                                                        <?php } ?>
                                                        <?php if ($this->rbac->hasPrivilege('hostel_gate_pass', 'can_edit')) { ?>
                                                            <button type="button" class="btn btn-xs" onclick="quickMarkHostelReturned('<?php echo $gp['id']; ?>', '<?php echo html_escape($gp['firstname'] . ' ' . $gp['lastname']); ?>')" data-toggle="tooltip" title="Mark Returned Now" style="display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 50%; background: #10b981; color: #fff; border: none; padding: 0; cursor: pointer; transition: all 0.2s; box-shadow: 0 1px 3px rgba(16,185,129,0.4);">
                                                                <i class="fa fa-check" style="font-size: 11px; font-weight: bold;"></i>
                                                            </button>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </td>
                                                <td class="mailbox-name"><?php echo !empty($gp['reason']) ? html_escape($gp['reason']) : '-'; ?></td>
                                                <td class="mailbox-name">
                                                    <?php
                                                    if ($gp['status'] == 'Out') {
                                                        if ($gp['is_overdue']) {
                                                            echo "<span class='label label-danger' style='animation: pulseWarning 1.5s infinite;'><i class='fa fa-exclamation-triangle'></i> OVERDUE</span>";
                                                        } else {
                                                            echo "<span class='label label-warning'>Out</span>";
                                                        }
                                                    } else {
                                                        echo "<span class='label label-success'>Returned</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td class="mailbox-date text-right white-space-nowrap">
                                                    <div class="dropdown" style="display: inline-block;">
                                                        <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius: 6px; padding: 4px 8px; color: #475569; background: #f8fafc; border-color: #e2e8f0;">
                                                            <i class="fa fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-right" style="border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; min-width: 150px; padding: 4px 0; font-size: 13px; z-index: 1050;">
                                                            <?php if ($this->rbac->hasPrivilege('hostel_gate_pass', 'can_view')) { ?>
                                                                <li>
                                                                    <a href="javascript:void(0);" onclick="printGatePass('<?php echo $gp['id']; ?>')">
                                                                        <i class="fa fa-print text-info" style="width: 18px;"></i> <?php echo $this->lang->line('print'); ?>
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                            
                                                            <?php if ($gp['status'] == 'Out' && $this->rbac->hasPrivilege('hostel_gate_pass', 'can_edit')) { ?>
                                                                <li>
                                                                    <a href="<?php echo site_url('admin/hostelgatepass/mark_returned/'.$gp['id']); ?>" onclick="return confirm('Are you sure you want to mark as returned?');">
                                                                        <i class="fa fa-check text-success" style="width: 18px;"></i> Mark Returned
                                                                    </a>
                                                                </li>
                                                            <?php } ?>

                                                            <?php if ($this->rbac->hasPrivilege('hostel_gate_pass', 'can_delete')) { ?>
                                                                <li role="separator" class="divider" style="margin: 4px 0;"></li>
                                                                <li>
                                                                    <a href="<?php echo site_url('admin/hostelgatepass/delete/'.$gp['id']); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');" style="color: #ef4444;">
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

<!-- Slide-in Right Drawer for Adding Hostel Gate Pass -->
<div id="hostelgatepass-drawer-overlay" class="modern-drawer-overlay"></div>
<div id="hostelgatepass-drawer-panel" class="modern-drawer-panel">
    <form id="addHostelGatePassForm" action="<?php echo site_url('admin/hostelgatepass/add') ?>" method="post" accept-charset="utf-8">
        <div class="modern-drawer-header">
            <h4 class="modern-drawer-title"><i class="fa fa-id-badge" style="color: var(--primary-theme-color, #4f46e5);"></i> Add Hostel Gate Pass</h4>
            <button type="button" class="modern-drawer-close" id="btn-close-hostelgatepass-drawer">&times;</button>
        </div>
        <div class="modern-drawer-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Select Hostel Student <small class="req"> *</small></label>
                        <select class="form-control" name="student_session_id" id="student_session_id" style="width:100%;" required>
                        </select>
                        <span class="text-danger" id="error_student_session_id"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Pass Type <small class="req"> *</small></label>
                        <select class="form-control" name="pass_type" id="pass_type" required onchange="handlePassTypeChange()">
                            <option value="Day Pass">Day Pass (Same Day Return)</option>
                            <option value="Holiday Pass">Holiday Pass (Multi-Day Return)</option>
                        </select>
                        <span class="text-danger" id="error_pass_type"></span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Going To / Destination <small class="req"> *</small></label>
                        <input type="text" class="form-control" name="going_to" id="going_to" placeholder="e.g. Market / Home" required>
                        <span class="text-danger" id="error_going_to"></span>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Out Date <small class="req"> *</small></label>
                        <input type="text" name="out_date" class="form-control date" id="out_date" readonly value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" required onchange="syncDayPassDates()">
                        <span class="text-danger" id="error_out_date"></span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Out Time <small class="req"> *</small></label>
                        <div class="input-group">
                            <input type="text" name="out_time" class="form-control timepicker" id="out_time" required>
                            <div class="input-group-addon" style="cursor: pointer;" onclick="$('#out_time').focus();">
                                <i class="fa fa-clock-o"></i>
                            </div>
                        </div>
                        <span class="text-danger" id="error_out_time"></span>
                    </div>
                </div>
            </div>

            <div class="row" id="expected_in_row">
                <div class="col-sm-6" id="expected_in_date_col">
                    <div class="form-group">
                        <label id="expected_in_date_label">Exp. Return Date <small id="day_pass_note" class="text-muted" style="font-weight:normal;">(Same Day)</small></label>
                        <input type="text" name="expected_in_date" class="form-control date" id="expected_in_date" readonly value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Exp. Return Time</label>
                        <div class="input-group">
                            <input type="text" name="expected_in_time" class="form-control timepicker" id="expected_in_time">
                            <div class="input-group-addon" style="cursor: pointer;" onclick="$('#expected_in_time').focus();">
                                <i class="fa fa-clock-o"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Reason / Note <small class="req"> *</small></label>
                <textarea name="reason" class="form-control" id="reason" rows="3" placeholder="State purpose for leaving hostel..." required></textarea>
                <span class="text-danger" id="error_reason"></span>
            </div>
        </div>
        <div class="modern-drawer-footer" style="display: flex; justify-content: flex-end; gap: 8px;">
            <button type="button" class="btn btn-default" id="btn-cancel-hostelgatepass-drawer"><?php echo $this->lang->line('cancel'); ?></button>
            <button type="submit" class="btn btn-primary" id="btn-save-hostelgatepass" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><i class="fa fa-check"></i> <?php echo $this->lang->line('save') ?></button>
            <button type="submit" class="btn btn-info" id="btn-save-print-hostelgatepass" name="save_and_print" value="1" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><i class="fa fa-print"></i> Save & Print</button>
        </div>
    </form>
</div>

<!-- Select2 CSS/JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
/* Modern KPI Stats Styles */
.modern-stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 14px;
    margin-bottom: 20px;
}

.modern-stat-card {
    background: #ffffff;
    border-radius: 10px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.07), 0 1px 2px 0 rgba(0, 0, 0, 0.04);
    border: 1px solid #e2e8f0;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.gp-stat-clickable {
    cursor: pointer;
}

.gp-stat-clickable:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.08), 0 2px 4px -1px rgba(0, 0, 0, 0.04);
    border-color: #cbd5e1;
}

.gp-card-overdue-alert {
    border: 1.5px solid #fca5a5 !important;
    background: #fef2f2 !important;
}

.pulse-dot {
    display: inline-block;
    width: 8px;
    height: 8px;
    background-color: #ef4444;
    border-radius: 50%;
    margin-left: 4px;
    animation: pulseDot 1.5s infinite;
}

@keyframes pulseDot {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
    70% { transform: scale(1.1); box-shadow: 0 0 0 6px rgba(239, 68, 68, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
}

@keyframes pulseWarning {
    0% { opacity: 1; }
    50% { opacity: 0.6; }
    100% { opacity: 1; }
}

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

.table-responsive {
    overflow-x: auto !important;
    overflow-y: visible !important;
    padding-bottom: 60px;
    margin-bottom: -60px;
}
</style>

<script>
    var lastClickedHostelSubmitBtn = null;

    function handlePassTypeChange() {
        var ptype = $('#pass_type').val();
        if (ptype === 'Day Pass') {
            // Day pass MUST return on same day
            syncDayPassDates();
            $('#expected_in_date').prop('disabled', true).css('background-color', '#f8fafc');
            $('#day_pass_note').show();
        } else {
            // Holiday Pass can have a different expected return date
            $('#expected_in_date').prop('disabled', false).css('background-color', '#ffffff');
            $('#day_pass_note').hide();
        }
    }

    function syncDayPassDates() {
        var ptype = $('#pass_type').val();
        if (ptype === 'Day Pass') {
            $('#expected_in_date').val($('#out_date').val());
        }
    }

    function filterGatePasses(type) {
        $('.gp-filter-pill').removeClass('active');
        $('.gp-filter-pill[data-filter="' + type + '"]').addClass('active');

        var table = $('#hostel_gatepass_table');
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
        handlePassTypeChange();

        $('#student_session_id').select2({
            dropdownParent: $('#hostelgatepass-drawer-panel'),
            width: '100%',
            ajax: {
                url: '<?php echo site_url('admin/hostelgatepass/search_student'); ?>',
                type: 'post',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        keyword: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            placeholder: 'Search by Student Name or Admission No',
            minimumInputLength: 1
        });

        $('#btn-open-hostelgatepass-drawer').on('click', function() {
            $('#addHostelGatePassForm').trigger("reset");
            $('#student_session_id').val(null).trigger('change');
            $('#out_date').val('<?php echo date($this->customlib->getSchoolDateFormat()); ?>');
            handlePassTypeChange();
            openHostelGatepassDrawer();
        });

        $('#btn-close-hostelgatepass-drawer, #btn-cancel-hostelgatepass-drawer, #hostelgatepass-drawer-overlay').on('click', function() {
            closeHostelGatepassDrawer();
        });
        
        $('.timepicker').timepicker({
            showInputs: false,
            showMeridian: false
        });

        $('#addHostelGatePassForm button[type=submit]').on('click', function() {
            lastClickedHostelSubmitBtn = $(this);
        });

        $('#addHostelGatePassForm').on('submit', (function (e) {
            e.preventDefault();
            var $this = lastClickedHostelSubmitBtn || $(this).find("button[type=submit]:first");
            var isSaveAndPrint = ($this.attr('id') === 'btn-save-print-hostelgatepass');

            $('#addHostelGatePassForm').find('.text-danger').remove();
            $this.button('loading');

            // Temporarily enable expected_in_date so it serializes properly if disabled
            $('#expected_in_date').prop('disabled', false);

            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (res) {
                    $this.button('reset');
                    handlePassTypeChange(); // restore disabled state

                    if (res.status == "fail") {
                        var message = "";
                        $.each(res.error, function (index, value) {
                            message += value;
                            $('#' + index).parents('.form-group').append('<span class="text-danger">' + value + '</span>');
                        });
                        errorMsg(message);
                    } else {
                        successMsg(res.message);
                        closeHostelGatepassDrawer();
                        if (isSaveAndPrint && res.insert_id) {
                            printGatePass(res.insert_id);
                        }
                        setTimeout(function() {
                            window.location.reload(true);
                        }, 500);
                    }
                },
                error: function () {
                    $this.button('reset');
                    handlePassTypeChange();
                    errorMsg('An error occurred during submission.');
                }
            });
        }));
    });

    function quickMarkHostelReturned(id, studentName) {
        if (!confirm('Mark gate pass as returned for ' + studentName + '?')) {
            return;
        }
        $.ajax({
            url: '<?php echo site_url('admin/hostelgatepass/mark_returned/'); ?>' + id,
            type: 'POST',
            dataType: 'json',
            success: function (res) {
                if (res.status == 'success') {
                    successMsg(res.message);
                    window.location.reload(true);
                } else {
                    errorMsg('Failed to update status.');
                }
            },
            error: function() {
                window.location.href = '<?php echo site_url('admin/hostelgatepass/mark_returned/'); ?>' + id;
            }
        });
    }

    function printGatePass(id) {
        $.ajax({
            url: '<?php echo site_url('admin/hostelgatepass/print_pass/'); ?>' + id,
            type: 'POST',
            success: function (data) {
                var newWin = window.open('', 'Print-Window', 'width=800,height=600');
                newWin.document.open();
                newWin.document.write('<html><head><title>Hostel Gate Pass</title>');
                newWin.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">');
                newWin.document.write('</head><body onload="window.print()">');
                newWin.document.write(data);
                newWin.document.write('</body></html>');
                newWin.document.close();
            }
        });
    }

    function getHostelCurrentTimeString() {
        var now = new Date();
        var hours = now.getHours();
        var minutes = now.getMinutes();
        var formattedHours = hours < 10 ? '0' + hours : hours;
        var formattedMinutes = minutes < 10 ? '0' + minutes : minutes;
        return formattedHours + ':' + formattedMinutes;
    }

    function openHostelGatepassDrawer() {
        var currentTime = getHostelCurrentTimeString();
        if (!$('#out_time').val()) {
            $('#out_time').val(currentTime);
        }

        $('#hostelgatepass-drawer-overlay').addClass('is-active');
        $('#hostelgatepass-drawer-panel').addClass('is-open');
        $('body').addClass('drawer-open').css('overflow', 'hidden');
    }

    function closeHostelGatepassDrawer() {
        $('#hostelgatepass-drawer-panel').removeClass('is-open');
        $('#hostelgatepass-drawer-overlay').removeClass('is-active');
        $('body').removeClass('drawer-open').css('overflow', '');
    }
</script>
