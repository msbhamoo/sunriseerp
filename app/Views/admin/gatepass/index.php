<?php
$total_passes = count($gate_passes);
$pending_count = 0;
$approved_count = 0;
$completed_count = 0;
$overdue_count = 0;
$today_count = 0;

$today_bus_groups = array();
$today_non_bus_students = array();
$today_bus_student_count = 0;

$now_timestamp = time();
$today_date = date('Y-m-d');

foreach ($gate_passes as &$gp_item) {
    $is_overdue = false;
    $overdue_text = '';

    $gp_date = !empty($gp_item['date']) ? date('Y-m-d', strtotime($gp_item['date'])) : '';
    $is_today = ($gp_date === $today_date);
    if ($is_today) {
        $today_count++;

        if ($gp_item['user_type'] === 'student') {
            $u_inf = $gp_item['user_info'] ?? array();
            $veh = !empty($u_inf['vehicle_no']) ? trim($u_inf['vehicle_no']) : '';
            if (!empty($veh)) {
                $today_bus_student_count++;
                if (!isset($today_bus_groups[$veh])) {
                    $today_bus_groups[$veh] = array(
                        'vehicle_no' => $veh,
                        'route_title' => $u_inf['route_title'] ?? '',
                        'driver_name' => $u_inf['driver_name'] ?? '',
                        'driver_contact' => $u_inf['driver_contact'] ?? '',
                        'students' => array()
                    );
                }
                $today_bus_groups[$veh]['students'][] = $gp_item;
            } else {
                $today_non_bus_students[] = $gp_item;
            }
        }
    }

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

$sch_name = !empty($sch_setting->name) ? $sch_setting->name : 'School Gate Pass System';
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-id-card-o"></i> <?php echo $this->lang->line('front_office'); ?></h1>
    </section>
    
    <section class="content">
        <!-- Modern KPI Stat Grid -->
        <div class="modern-stat-grid">
            <div class="modern-stat-card gp-stat-clickable" onclick="filterGatePasses('today')">
                <div class="modern-stat-info">
                    <div class="stat-label" style="color: #4f46e5; font-weight: 700;">Today's Passes</div>
                    <div class="stat-value" style="color: #4f46e5;"><?php echo $today_count; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(79, 70, 229, 0.12); color: #4f46e5;">
                    <i class="fa fa-calendar-check-o"></i>
                </div>
            </div>

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
                        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                            <h3 class="box-title titlefix" style="margin: 0; margin-right: 4px;"><i class="fa fa-list text-muted" style="margin-right: 6px;"></i> <?php echo $this->lang->line('gate_pass_list'); ?></h3>
                            
                            <!-- Quick Filter Pills -->
                            <div class="gp-filter-pill-group" style="display: inline-flex; align-items: center; gap: 3px; flex-wrap: wrap;">
                                <button type="button" class="gp-filter-pill active" data-filter="all" onclick="filterGatePasses('all')">All (<?php echo $total_passes; ?>)</button>
                                <button type="button" class="gp-filter-pill pill-today" data-filter="today" onclick="filterGatePasses('today')">📅 Today (<?php echo $today_count; ?>)</button>
                                
                                <!-- Small CTA button: ONLY visible when Today filter is active -->
                                <button type="button" id="btn-bus-share-cta" class="gp-btn-bus-share-micro" onclick="openBusShareModal('all')" data-toggle="tooltip" title="Share Today's Gate Passes by Bus with Drivers on WhatsApp">
                                    <i class="fa fa-whatsapp"></i> <span>Share Bus</span>
                                    <?php if ($today_bus_student_count > 0) { ?>
                                        <span class="gp-micro-badge"><?php echo $today_bus_student_count; ?></span>
                                    <?php } ?>
                                </button>

                                <button type="button" class="gp-filter-pill" data-filter="approved" onclick="filterGatePasses('approved')">Approved (<?php echo $approved_count; ?>)</button>
                                <button type="button" class="gp-filter-pill pill-overdue <?php echo ($overdue_count > 0) ? 'has-overdue' : ''; ?>" data-filter="overdue" onclick="filterGatePasses('overdue')">⚠️ Overdue (<?php echo $overdue_count; ?>)</button>
                                <button type="button" class="gp-filter-pill" data-filter="completed" onclick="filterGatePasses('completed')">Completed (<?php echo $completed_count; ?>)</button>
                            </div>
                        </div>

                        <div class="box-tools" style="display: flex; align-items: center; gap: 6px;">
                            <?php if ($this->rbac->hasPrivilege('front_office_gate_pass', 'can_add')) { ?>
                                <button type="button" id="btn-open-gatepass-drawer" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_gate_pass'); ?></button>
                            <?php } ?>
                        </div>
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
                                            $gp_row_date = !empty($gatepass['date']) ? date('Y-m-d', strtotime($gatepass['date'])) : '';
                                            $is_row_today = ($gp_row_date === $today_date);

                                            $filter_types = 'all';
                                            if ($is_row_today) {
                                                $filter_types .= ' today';
                                            }
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
                                                    <div style="display: flex; align-items: flex-start; gap: 10px;">
                                                        <img src="<?php echo $user_img; ?>" alt="Photo" style="width: 38px; height: 38px; border-radius: 50%; object-fit: cover; border: 1.5px solid #e2e8f0; box-shadow: 0 1px 2px rgba(0,0,0,0.06); flex-shrink: 0; margin-top: 2px;" onerror="this.src='<?php echo ($gatepass['user_type'] == 'staff') ? base_url('uploads/staff_images/default_male.jpg') : base_url('uploads/student_images/no_image.png'); ?>'">
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
                                                            <?php if (!empty($u_info['vehicle_no']) || !empty($u_info['route_title'])) { ?>
                                                                <div class="gp-bus-share-clickable-badge" onclick="openBusShareModal('<?php echo html_escape(!empty($u_info['vehicle_no']) ? $u_info['vehicle_no'] : 'all'); ?>')" data-toggle="tooltip" title="Click to preview & share this bus gatepass list with driver on WhatsApp" style="margin-top: 4px; display: inline-flex; align-items: center; gap: 4px; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 2px 8px; border-radius: 4px; font-size: 11px; color: #166534; line-height: 1.4; white-space: nowrap; max-width: none; cursor: pointer; transition: all 0.2s ease;">
                                                                    <i class="fa fa-bus text-success" style="font-size: 10.5px; flex-shrink: 0;"></i>
                                                                    <?php if (!empty($u_info['vehicle_no'])) { ?>
                                                                        <strong style="color: #14532d; white-space: nowrap;"><?php echo html_escape($u_info['vehicle_no']); ?></strong>
                                                                    <?php } ?>
                                                                    <?php if (!empty($u_info['route_title'])) { ?>
                                                                        <span style="white-space: nowrap;">• <?php echo html_escape($u_info['route_title']); ?></span>
                                                                    <?php } ?>
                                                                    <?php if (!empty($u_info['pickup_point_name'])) { ?>
                                                                        <span style="color: #15803d; font-size: 10.5px; white-space: nowrap;">(<?php echo html_escape($u_info['pickup_point_name']); ?>)</span>
                                                                    <?php } ?>
                                                                    <i class="fa fa-share-alt" style="margin-left: 3px; font-size: 9.5px; color: #15803d; opacity: 0.8;"></i>
                                                                </div>
                                                            <?php } ?>
                                                            <?php if ($gatepass['is_overdue']) { ?>
                                                                <div style="margin-top: 3px;">
                                                                    <span class="label label-danger gp-overdue-tag" title="User has not returned by expected time"><i class="fa fa-clock-o"></i> OVERDUE (<?php echo $gatepass['overdue_text']; ?>)</span>
                                                                </div>
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
                                                    <?php if ($is_row_today) { ?>
                                                        <span class="badge" style="background: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe; font-size: 10px; margin-left: 2px;">Today</span>
                                                    <?php } ?>
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

<!-- Bus-wise WhatsApp Share & Screenshot Hub Modal -->
<div class="modal fade" id="busShareModal" tabindex="-1" role="dialog" aria-labelledby="busShareModalLabel" style="z-index: 1060;">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 920px; margin-top: 30px; margin-bottom: 30px;">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #15803d 0%, #16a34a 100%); color: #ffffff; padding: 16px 20px;">
                <button type="button" class="close" data-dismiss="modal" style="color: #ffffff; opacity: 0.9; font-size: 24px; text-shadow: none;">&times;</button>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <div style="width: 38px; height: 38px; border-radius: 8px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 18px;">
                        <i class="fa fa-whatsapp"></i>
                    </div>
                    <div>
                        <h4 class="modal-title" style="font-weight: 700; margin: 0; font-size: 16px; color: #ffffff;">
                            🚌 Today's Bus Gate Pass Share & Screenshot Hub
                        </h4>
                        <div style="font-size: 12px; opacity: 0.9; margin-top: 2px;">
                            Date: <strong><?php echo date('d M Y'); ?></strong> • Total <strong><?php echo $today_bus_student_count; ?></strong> student(s) on transport early leave
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-body" style="padding: 16px 20px; background: #f8fafc;">
                <!-- Vehicle Selector Tabs -->
                <div class="gp-bus-tabs-wrapper" style="margin-bottom: 15px; overflow-x: auto; padding-bottom: 4px;">
                    <div class="gp-bus-tabs-list" id="gp-bus-tabs-container" style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <!-- Rendered by JS -->
                    </div>
                </div>

                <!-- Action Toolbar -->
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; background: #ffffff; padding: 12px 16px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 16px; box-shadow: 0 1px 2px rgba(0,0,0,0.04);">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Quick Actions:</span>
                        <span id="gp-share-selected-desc" style="font-size: 12.5px; font-weight: 600; color: #0f172a;"></span>
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                        <button type="button" class="btn btn-sm btn-primary" onclick="copyBusCardScreenshot()" id="btn-copy-screenshot" style="background: #4f46e5; border-color: #4338ca; border-radius: 6px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px;">
                            <i class="fa fa-camera"></i> <span>📸 Copy Screenshot (Ctrl+V)</span>
                        </button>

                        <button type="button" class="btn btn-sm btn-default" onclick="downloadBusCardScreenshot()" id="btn-download-screenshot" style="border-radius: 6px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; color: #334155;">
                            <i class="fa fa-download"></i> <span>💾 Save Image</span>
                        </button>

                        <a href="javascript:void(0);" onclick="shareBusOnWhatsApp()" id="btn-whatsapp-direct" class="btn btn-sm btn-success" target="_blank" style="background: #16a34a; border-color: #15803d; border-radius: 6px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px;">
                            <i class="fa fa-whatsapp"></i> <span>📱 WhatsApp Driver</span>
                        </a>

                        <button type="button" class="btn btn-sm btn-default" onclick="copyBusShareText()" id="btn-copy-text" style="border-radius: 6px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; color: #475569;">
                            <i class="fa fa-copy"></i> <span>📋 Copy Text</span>
                        </button>
                    </div>
                </div>

                <!-- Feedback Toast -->
                <div id="gp-share-feedback-alert" style="display: none; padding: 10px 16px; border-radius: 6px; margin-bottom: 12px; font-size: 13px; font-weight: 600; animation: fadeIn 0.3s ease;"></div>

                <!-- Screenshot Preview Container (Clean, Retina-Ready Card for html2canvas) -->
                <div style="background: #e2e8f0; padding: 16px; border-radius: 10px; display: flex; justify-content: center; overflow-x: auto;">
                    <div id="bus-screenshot-card" style="width: 680px; min-width: 680px; background: #ffffff; border-radius: 12px; padding: 24px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1); font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #0f172a; position: relative;">
                        <!-- Dynamic Content Rendered by JS -->
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="padding: 12px 20px; background: #fafafa; display: flex; justify-content: space-between; align-items: center;">
                <small class="text-muted"><i class="fa fa-info-circle"></i> <strong>Tip:</strong> Click "Copy Screenshot", then switch to WhatsApp Web/Desktop and press <code>Ctrl + V</code> to instantly paste and send image.</small>
                <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 6px; font-weight: 600;">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- html2canvas Library for Instant HD Screenshot Capture -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

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

.gp-filter-pill.pill-today.active {
    background: #4f46e5 !important;
    color: #ffffff !important;
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
    var currentGatepassFilter = 'all';

    function registerGatepassDataTableFilter() {
        if (typeof $.fn.dataTable !== 'undefined' && $.fn.dataTable.ext && $.fn.dataTable.ext.search) {
            // Check if already registered to avoid duplicates
            if (!window._gatepassFilterRegistered) {
                window._gatepassFilterRegistered = true;
                $.fn.dataTable.ext.search.push(
                    function(settings, data, dataIndex, rowData, counter) {
                        var tableId = settings.nTable ? settings.nTable.id : (settings.sTableId || '');
                        if (tableId !== 'frontoffice_gatepass_table') {
                            return true;
                        }
                        if (!currentGatepassFilter || currentGatepassFilter === 'all') {
                            return true;
                        }
                        var rowNode = null;
                        if (settings.aoData && settings.aoData[dataIndex]) {
                            rowNode = settings.aoData[dataIndex].nTr;
                        }
                        if (!rowNode && settings.oInstance && typeof settings.oInstance.fnGetNodes === 'function') {
                            rowNode = settings.oInstance.fnGetNodes(dataIndex);
                        }
                        if (!rowNode && settings.nTable) {
                            rowNode = $(settings.nTable).find('tbody tr')[dataIndex];
                        }
                        if (!rowNode) {
                            return true;
                        }
                        var filterTypes = $(rowNode).attr('data-filter-type') || '';
                        var filterArr = filterTypes.trim().split(/\s+/);
                        return filterArr.indexOf(currentGatepassFilter) !== -1;
                    }
                );
            }
        }
    }

    function filterGatePasses(type) {
        currentGatepassFilter = type;
        $('.gp-filter-pill').removeClass('active');
        $('.gp-filter-pill[data-filter="' + type + '"]').addClass('active');

        // Show small bus share CTA strictly ONLY when Today filter is active
        if (type === 'today') {
            $('#btn-bus-share-cta').addClass('is-visible');
        } else {
            $('#btn-bus-share-cta').removeClass('is-visible');
        }

        registerGatepassDataTableFilter();

        if (typeof $.fn.DataTable !== 'undefined' && $.fn.DataTable.isDataTable('#frontoffice_gatepass_table')) {
            $('#frontoffice_gatepass_table').DataTable().draw();
        } else {
            var table = $('#frontoffice_gatepass_table');
            if (type === 'all') {
                table.find('tbody tr').show();
            } else {
                table.find('tbody tr').each(function() {
                    var rowFilters = ($(this).attr('data-filter-type') || '').trim().split(/\s+/);
                    if (rowFilters.indexOf(type) !== -1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            }
        }
    }

    $(document).ready(function () {
        registerGatepassDataTableFilter();

        var urlParams = new URLSearchParams(window.location.search);
        var initialFilter = urlParams.get('filter');
        if (initialFilter) {
            setTimeout(function() {
                filterGatePasses(initialFilter);
            }, 200);
        }

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

    /* ==========================================================================
       BUS-WISE WHATSAPP SHARE & AUTO-SCREENSHOT HUB LOGIC
       ========================================================================== */
    var TODAY_BUS_DATA = <?php echo json_encode($today_bus_groups); ?>;
    var TODAY_NON_BUS_DATA = <?php echo json_encode($today_non_bus_students); ?>;
    var SCHOOL_NAME = <?php echo json_encode($sch_name); ?>;
    var TODAY_DATE_STR = <?php echo json_encode(date('d M Y')); ?>;
    var currentActiveBusKey = 'all';

    function openBusShareModal(initialVehKey) {
        renderBusShareTabs();
        var targetKey = initialVehKey && (TODAY_BUS_DATA[initialVehKey] || initialVehKey === 'non_bus') ? initialVehKey : 'all';
        switchBusShareTab(targetKey);
        $('#busShareModal').modal('show');
    }

    function renderBusShareTabs() {
        var container = $('#gp-bus-tabs-container');
        container.empty();

        var totalBusStudents = <?php echo (int)$today_bus_student_count; ?>;
        var nonBusCount = TODAY_NON_BUS_DATA ? TODAY_NON_BUS_DATA.length : 0;

        // "All Buses" tab
        var allActive = (currentActiveBusKey === 'all') ? 'active' : '';
        var allBtn = $('<button type="button" class="gp-bus-tab-pill ' + allActive + '" onclick="switchBusShareTab(\'all\')">' +
            '<i class="fa fa-th-large"></i> <span>All Transport Buses</span>' +
            '<span class="gp-tab-badge">' + totalBusStudents + '</span>' +
        '</button>');
        container.append(allBtn);

        // Individual Bus Tabs
        $.each(TODAY_BUS_DATA, function(vehKey, vehData) {
            var isActive = (currentActiveBusKey === vehKey) ? 'active' : '';
            var count = vehData.students ? vehData.students.length : 0;
            var routeShort = vehData.route_title ? ' • ' + vehData.route_title : '';
            var vehBtn = $('<button type="button" class="gp-bus-tab-pill ' + isActive + '" onclick="switchBusShareTab(\'' + vehKey.replace(/'/g, "\\'") + '\')">' +
                '<i class="fa fa-bus"></i> <span><strong>' + escapeHtml(vehData.vehicle_no) + '</strong>' + escapeHtml(routeShort) + '</span>' +
                '<span class="gp-tab-badge">' + count + '</span>' +
            '</button>');
            container.append(vehBtn);
        });

        // Non-Bus / Self tab (if any)
        if (nonBusCount > 0) {
            var nonActive = (currentActiveBusKey === 'non_bus') ? 'active' : '';
            var nonBtn = $('<button type="button" class="gp-bus-tab-pill ' + nonActive + '" onclick="switchBusShareTab(\'non_bus\')">' +
                '<i class="fa fa-user"></i> <span>Self / Non-Transport</span>' +
                '<span class="gp-tab-badge" style="background:#e2e8f0; color:#475569;">' + nonBusCount + '</span>' +
            '</button>');
            container.append(nonBtn);
        }
    }

    function switchBusShareTab(vehKey) {
        currentActiveBusKey = vehKey;
        $('#gp-bus-tabs-container .gp-bus-tab-pill').removeClass('active');
        $('#gp-bus-tabs-container button').each(function() {
            var onclickAttr = $(this).attr('onclick') || '';
            if (onclickAttr.indexOf("'" + vehKey + "'") !== -1) {
                $(this).addClass('active');
            }
        });

        renderBusShareCard(vehKey);
        updateWhatsAppDirectBtn(vehKey);
    }

    function renderBusShareCard(vehKey) {
        var card = $('#bus-screenshot-card');
        card.empty();
        $('#gp-share-feedback-alert').hide();

        var students = [];
        var titleText = '';
        var subText = '';
        var driverName = '';
        var driverPhone = '';
        var routeTitle = '';

        if (vehKey === 'all') {
            titleText = 'All Transport Vehicles / Combined Report';
            subText = 'Summary of all students leaving early / on gate pass today across all bus routes';
            $('#gp-share-selected-desc').text('All Buses (' + <?php echo (int)$today_bus_student_count; ?> + ' students)');
            $.each(TODAY_BUS_DATA, function(k, v) {
                if (v.students) {
                    students = students.concat(v.students);
                }
            });
        } else if (vehKey === 'non_bus') {
            titleText = 'Non-Transport / Self Departures';
            subText = 'Students not assigned to school transport who took a Gate Pass today';
            students = TODAY_NON_BUS_DATA || [];
            $('#gp-share-selected-desc').text('Non-Transport (' + students.length + ' students)');
        } else if (TODAY_BUS_DATA[vehKey]) {
            var vData = TODAY_BUS_DATA[vehKey];
            titleText = 'Bus: ' + vData.vehicle_no + (vData.route_title ? ' (' + vData.route_title + ')' : '');
            routeTitle = vData.route_title || '';
            driverName = vData.driver_name || '';
            driverPhone = vData.driver_contact || '';
            students = vData.students || [];
            $('#gp-share-selected-desc').text(vData.vehicle_no + ' (' + students.length + ' students)');
        }

        if (students.length === 0) {
            card.html('<div style="text-align:center; padding: 40px 20px; color:#64748b;">' +
                '<i class="fa fa-check-circle text-success" style="font-size: 40px; margin-bottom: 12px; display:block;"></i>' +
                '<h4 style="font-weight:700; color:#0f172a; margin:0 0 6px 0;">No Gate Passes For This Vehicle Today</h4>' +
                '<p style="font-size:13px; margin:0;">All students are scheduled to travel as normal.</p>' +
            '</div>');
            return;
        }

        // Build Card HTML
        var html = '';
        
        // Header Section
        html += '<div style="display:flex; justify-content:space-between; align-items:flex-start; border-bottom:2px solid #e2e8f0; padding-bottom:14px; margin-bottom:16px;">';
        html += '  <div>';
        html += '    <h3 style="margin:0 0 4px 0; font-size:18px; font-weight:800; color:#0f172a; letter-spacing:-0.3px;">' + escapeHtml(SCHOOL_NAME) + '</h3>';
        html += '    <div style="font-size:13px; font-weight:700; color:#16a34a; display:flex; align-items:center; gap:6px;">';
        html += '      <span style="background:#dcfce7; color:#15803d; padding:2px 8px; border-radius:4px; font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:0.5px;">Gate Pass Alert</span>';
        html += '      <span>' + escapeHtml(titleText) + '</span>';
        html += '    </div>';
        html += '  </div>';
        html += '  <div style="text-align:right;">';
        html += '    <div style="font-size:11px; color:#64748b; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Date of Issue</div>';
        html += '    <div style="font-size:14px; font-weight:700; color:#0f172a; margin-top:2px;"><i class="fa fa-calendar-o text-muted"></i> ' + escapeHtml(TODAY_DATE_STR) + '</div>';
        html += '  </div>';
        html += '</div>';

        // Vehicle & Driver Info Banner (if single vehicle)
        if (vehKey !== 'all' && vehKey !== 'non_bus') {
            html += '<div style="background:#f0fdf4; border:1.5px solid #86efac; border-radius:8px; padding:10px 14px; margin-bottom:16px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">';
            html += '  <div style="display:flex; align-items:center; gap:12px;">';
            html += '    <div style="width:36px; height:36px; border-radius:8px; background:#16a34a; color:#fff; display:flex; align-items:center; justify-content:center; font-size:17px;"><i class="fa fa-bus"></i></div>';
            html += '    <div>';
            html += '      <div style="font-size:14px; font-weight:800; color:#14532d;">Vehicle: ' + escapeHtml(vehKey) + '</div>';
            if (routeTitle) {
                html += '      <div style="font-size:12px; color:#166534; font-weight:600;">Route: ' + escapeHtml(routeTitle) + '</div>';
            }
            html += '    </div>';
            html += '  </div>';
            if (driverName || driverPhone) {
                html += '  <div style="text-align:right; font-size:12px; color:#14532d;">';
                if (driverName) html += '<div><strong>Driver:</strong> ' + escapeHtml(driverName) + '</div>';
                if (driverPhone) html += '<div><strong>Contact:</strong> ' + escapeHtml(driverPhone) + '</div>';
                html += '  </div>';
            }
            html += '</div>';
        }

        // Notice Header
        html += '<div style="background:#fef2f2; border-left:4px solid #ef4444; padding:8px 12px; border-radius:4px; font-size:12.5px; color:#991b1b; font-weight:700; margin-bottom:14px; display:flex; align-items:center; justify-content:space-between;">';
        html += '  <div><i class="fa fa-exclamation-triangle" style="margin-right:6px;"></i> The following <strong>' + students.length + ' student(s)</strong> have Gate Passes and will NOT board the bus:</div>';
        html += '</div>';

        // Student Table
        html += '<table style="width:100%; border-collapse:collapse; font-size:12.5px;">';
        html += '  <thead>';
        html += '    <tr style="background:#f8fafc; border-bottom:2px solid #cbd5e1; text-align:left; color:#475569; font-size:11.5px; text-transform:uppercase; letter-spacing:0.5px;">';
        html += '      <th style="padding:8px 10px; width:30px;">#</th>';
        html += '      <th style="padding:8px 10px;">Student Details</th>';
        html += '      <th style="padding:8px 10px;">Bus Stop / Pickup</th>';
        html += '      <th style="padding:8px 10px;">Gate Pass Details</th>';
        html += '      <th style="padding:8px 10px;">Parent Contact</th>';
        html += '    </tr>';
        html += '  </thead>';
        html += '  <tbody>';

        $.each(students, function(idx, stItem) {
            var uInfo = stItem.user_info || {};
            var stName = uInfo.name || stItem.user_details || 'Student';
            var stAdm = uInfo.admission_no || '';
            var stClass = uInfo.class_name || '';
            var stStop = uInfo.pickup_point_name || uInfo.route_title || '-';
            var stVeh = uInfo.vehicle_no || '';
            var stPhone = uInfo.father_phone || uInfo.guardian_phone || uInfo.mobileno || '-';
            var passNo = stItem.gate_pass_no || '';
            var outTime = stItem.out_time || '';
            var passType = stItem.pass_type || 'Full Day';
            var reason = stItem.reason || '';

            var bgRow = (idx % 2 === 0) ? '#ffffff' : '#f8fafc';

            html += '    <tr style="background:' + bgRow + '; border-bottom:1px solid #e2e8f0;">';
            html += '      <td style="padding:10px 10px; font-weight:700; color:#64748b; vertical-align:top;">' + (idx + 1) + '</td>';
            html += '      <td style="padding:10px 10px; vertical-align:top;">';
            html += '        <div style="font-weight:800; color:#0f172a; font-size:13.5px;">' + escapeHtml(stName) + '</div>';
            html += '        <div style="margin-top:2px; display:flex; align-items:center; gap:6px; flex-wrap:wrap;">';
            if (stAdm) html += '<span style="background:#eef2ff; color:#4338ca; padding:1px 5px; border-radius:4px; font-weight:700; font-size:11px;">Adm: ' + escapeHtml(stAdm) + '</span>';
            if (stClass) html += '<span style="color:#475569; font-weight:600; font-size:11.5px;">' + escapeHtml(stClass) + '</span>';
            html += '        </div>';
            if (vehKey === 'all' && stVeh) {
                html += '        <div style="margin-top:3px; font-size:11px; color:#15803d; font-weight:700;"><i class="fa fa-bus"></i> ' + escapeHtml(stVeh) + '</div>';
            }
            html += '      </td>';
            html += '      <td style="padding:10px 10px; vertical-align:top;">';
            html += '        <div style="font-weight:700; color:#0f172a;"><i class="fa fa-map-marker text-danger" style="margin-right:4px;"></i> ' + escapeHtml(stStop) + '</div>';
            html += '      </td>';
            html += '      <td style="padding:10px 10px; vertical-align:top;">';
            html += '        <div><strong style="color:#4f46e5;">' + escapeHtml(passNo) + '</strong> <span style="background:#e0f2fe; color:#0369a1; padding:1px 5px; border-radius:4px; font-size:10.5px; font-weight:700;">' + escapeHtml(passType) + '</span></div>';
            if (outTime) html += '        <div style="color:#475569; font-size:11.5px; margin-top:2px;"><i class="fa fa-clock-o"></i> Out: <strong>' + escapeHtml(outTime) + '</strong></div>';
            if (reason) html += '        <div style="color:#64748b; font-size:11px; margin-top:2px; font-style:italic;">Reason: ' + escapeHtml(reason) + '</div>';
            html += '      </td>';
            html += '      <td style="padding:10px 10px; vertical-align:top; font-weight:700; color:#0f172a; white-space:nowrap;">';
            html += '        <div><i class="fa fa-phone text-muted" style="margin-right:4px;"></i> ' + escapeHtml(stPhone) + '</div>';
            html += '      </td>';
            html += '    </tr>';
        });

        html += '  </tbody>';
        html += '</table>';

        // Footer Section
        html += '<div style="margin-top:18px; padding-top:12px; border-top:2px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#64748b;">';
        html += '  <div><strong>Important:</strong> Driver/Conductor please do NOT wait at the above stop(s) for these students.</div>';
        html += '  <div style="text-align:right;">Total: <strong>' + students.length + ' Student(s)</strong> | LMS Gate Pass System</div>';
        html += '</div>';

        card.html(html);
    }

    function getWhatsAppShareText(vehKey) {
        var students = [];
        var headerTitle = '';
        var routeTitle = '';
        var driverName = '';

        if (vehKey === 'all') {
            headerTitle = 'ALL BUSES (COMBINED)';
            $.each(TODAY_BUS_DATA, function(k, v) {
                if (v.students) students = students.concat(v.students);
            });
        } else if (vehKey === 'non_bus') {
            headerTitle = 'NON-TRANSPORT STUDENTS';
            students = TODAY_NON_BUS_DATA || [];
        } else if (TODAY_BUS_DATA[vehKey]) {
            var vData = TODAY_BUS_DATA[vehKey];
            headerTitle = 'BUS: ' + vData.vehicle_no;
            routeTitle = vData.route_title || '';
            driverName = vData.driver_name || '';
            students = vData.students || [];
        }

        if (students.length === 0) {
            return "🚌 *GATE PASS ALERT*\n📅 Date: " + TODAY_DATE_STR + "\n✅ All students traveling as normal.";
        }

        var text = "🚌 *GATE PASS ALERT - " + headerTitle + "*\n";
        text += "📅 *Date:* " + TODAY_DATE_STR + "\n";
        if (routeTitle) text += "🛣️ *Route:* " + routeTitle + "\n";
        if (driverName) text += "👤 *Driver:* " + driverName + "\n";
        text += "🏫 *School:* " + SCHOOL_NAME + "\n\n";
        text += "⚠️ *The following " + students.length + " student(s) will NOT be going on the bus today:*\n\n";

        $.each(students, function(idx, stItem) {
            var uInfo = stItem.user_info || {};
            var stName = uInfo.name || stItem.user_details || 'Student';
            var stAdm = uInfo.admission_no ? " (Adm: " + uInfo.admission_no + ")" : "";
            var stClass = uInfo.class_name || "";
            var stStop = uInfo.pickup_point_name || uInfo.route_title || "";
            var stVeh = uInfo.vehicle_no || "";
            var stPhone = uInfo.father_phone || uInfo.guardian_phone || uInfo.mobileno || "";
            var passNo = stItem.gate_pass_no || "";
            var outTime = stItem.out_time || "";

            text += (idx + 1) + ". *" + stName + "*" + stAdm + "\n";
            if (stClass) text += "   • *Class:* " + stClass + "\n";
            if (vehKey === 'all' && stVeh) text += "   • *Bus:* " + stVeh + "\n";
            if (stStop) text += "   • *Stop:* " + stStop + "\n";
            if (passNo || outTime) text += "   • *Gate Pass:* " + passNo + (outTime ? " (Out: " + outTime + ")" : "") + "\n";
            if (stPhone) text += "   • *Parent:* " + stPhone + "\n";
            text += "\n";
        });

        text += "_Total: " + students.length + " student(s) on Gate Pass._\n";
        text += "_Driver/Conductor please do not wait for these students._";

        return text;
    }

    function updateWhatsAppDirectBtn(vehKey) {
        var driverPhone = '';
        if (TODAY_BUS_DATA[vehKey] && TODAY_BUS_DATA[vehKey].driver_contact) {
            driverPhone = TODAY_BUS_DATA[vehKey].driver_contact.replace(/[^0-9]/g, '');
        }

        var btn = $('#btn-whatsapp-direct');
        if (driverPhone) {
            btn.find('span').text('📱 WhatsApp Driver (' + driverPhone + ')');
        } else {
            btn.find('span').text('📱 WhatsApp Share');
        }
    }

    function shareBusOnWhatsApp() {
        var text = getWhatsAppShareText(currentActiveBusKey);
        var driverPhone = '';
        if (TODAY_BUS_DATA[currentActiveBusKey] && TODAY_BUS_DATA[currentActiveBusKey].driver_contact) {
            driverPhone = TODAY_BUS_DATA[currentActiveBusKey].driver_contact.replace(/[^0-9]/g, '');
        }

        var waUrl = '';
        if (driverPhone) {
            waUrl = 'https://wa.me/91' + driverPhone + '?text=' + encodeURIComponent(text);
        } else {
            waUrl = 'https://api.whatsapp.com/send?text=' + encodeURIComponent(text);
        }

        window.open(waUrl, '_blank');
    }

    function copyBusShareText() {
        var text = getWhatsAppShareText(currentActiveBusKey);
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(text).then(function() {
                showShareFeedback('✅ Formatted WhatsApp text copied to clipboard! Paste directly into WhatsApp.');
            }).catch(function() {
                fallbackCopyText(text);
            });
        } else {
            fallbackCopyText(text);
        }
    }

    function fallbackCopyText(text) {
        var tempInput = $('<textarea>');
        $('body').append(tempInput);
        tempInput.val(text).select();
        document.execCommand('copy');
        tempInput.remove();
        showShareFeedback('✅ Formatted WhatsApp text copied to clipboard! Paste directly into WhatsApp.');
    }

    function copyBusCardScreenshot() {
        var cardElem = document.getElementById('bus-screenshot-card');
        var btn = $('#btn-copy-screenshot');
        var originalBtnHtml = btn.html();

        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Generating HD Screenshot...');

        html2canvas(cardElem, {
            scale: 2,
            useCORS: true,
            backgroundColor: '#ffffff',
            logging: false
        }).then(function(canvas) {
            canvas.toBlob(function(blob) {
                btn.prop('disabled', false).html(originalBtnHtml);

                if (navigator.clipboard && window.ClipboardItem) {
                    var item = new ClipboardItem({ 'image/png': blob });
                    navigator.clipboard.write([item]).then(function() {
                        showShareFeedback('🎉 <strong>HD Screenshot Copied to Clipboard!</strong> Simply open WhatsApp (Web or App) and press <code>Ctrl + V</code> to send.');
                    }).catch(function(err) {
                        // Fallback to download if direct clipboard write fails
                        downloadCanvasAsPng(canvas);
                        showShareFeedback('💾 Screenshot downloaded as image! You can drag & drop it into WhatsApp.');
                    });
                } else {
                    downloadCanvasAsPng(canvas);
                    showShareFeedback('💾 Screenshot downloaded as image! You can drag & drop it into WhatsApp.');
                }
            }, 'image/png');
        }).catch(function(err) {
            btn.prop('disabled', false).html(originalBtnHtml);
            showShareFeedback('❌ Could not generate screenshot: ' + err.message, true);
        });
    }

    function downloadBusCardScreenshot() {
        var cardElem = document.getElementById('bus-screenshot-card');
        var btn = $('#btn-download-screenshot');
        var originalBtnHtml = btn.html();

        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

        html2canvas(cardElem, {
            scale: 2,
            useCORS: true,
            backgroundColor: '#ffffff',
            logging: false
        }).then(function(canvas) {
            btn.prop('disabled', false).html(originalBtnHtml);
            downloadCanvasAsPng(canvas);
            showShareFeedback('💾 Image saved successfully!');
        }).catch(function(err) {
            btn.prop('disabled', false).html(originalBtnHtml);
            showShareFeedback('❌ Could not download screenshot: ' + err.message, true);
        });
    }

    function downloadCanvasAsPng(canvas) {
        var safeVeh = currentActiveBusKey.replace(/[^a-zA-Z0-9_-]/g, '_');
        var filename = 'GatePass_Bus_' + safeVeh + '_' + TODAY_DATE_STR.replace(/\s+/g, '_') + '.png';
        var link = document.createElement('a');
        link.download = filename;
        link.href = canvas.toDataURL('image/png');
        link.click();
    }

    function showShareFeedback(msg, isError) {
        var alertBox = $('#gp-share-feedback-alert');
        var bg = isError ? '#fef2f2' : '#f0fdf4';
        var color = isError ? '#991b1b' : '#166534';
        var border = isError ? '#f87171' : '#86efac';

        alertBox.css({
            'background': bg,
            'color': color,
            'border': '1px solid ' + border
        }).html(msg).slideDown(200);

        setTimeout(function() {
            alertBox.slideUp(300);
        }, 5000);
    }

    function escapeHtml(text) {
        if (!text) return '';
        return String(text)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
</script>

<style>
/* Bus Share Tab Pills */
.gp-bus-tab-pill {
    background: #ffffff;
    border: 1.5px solid #e2e8f0;
    border-radius: 20px;
    padding: 6px 14px;
    font-size: 12.5px;
    font-weight: 600;
    color: #475569;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
    outline: none !important;
    white-space: nowrap;
}

.gp-bus-tab-pill:hover {
    border-color: #94a3b8;
    color: #0f172a;
    background: #f1f5f9;
}

.gp-bus-tab-pill.active {
    background: #16a34a;
    border-color: #15803d;
    color: #ffffff;
    box-shadow: 0 2px 4px rgba(22, 163, 74, 0.25);
}

.gp-tab-badge {
    background: #e2e8f0;
    color: #334155;
    border-radius: 10px;
    padding: 1px 7px;
    font-size: 11px;
    font-weight: 700;
}

.gp-bus-tab-pill.active .gp-tab-badge {
    background: #ffffff;
    color: #15803d;
}

.gp-bus-share-clickable-badge:hover {
    background: #dcfce7 !important;
    border-color: #86efac !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 5px rgba(22, 163, 74, 0.2);
}

/* Micro CTA for Bus Share - Hidden by default, visible only on Today */
.gp-btn-bus-share-micro {
    display: none;
    background: #16a34a;
    color: #ffffff;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    font-size: 10.5px;
    padding: 2px 8px;
    align-items: center;
    gap: 4px;
    box-shadow: 0 1px 2px rgba(22,163,74,0.3);
    transition: all 0.2s;
    margin-left: 2px;
    cursor: pointer;
    line-height: 1.4;
    outline: none !important;
}

.gp-btn-bus-share-micro:hover {
    background: #15803d;
    color: #ffffff;
    box-shadow: 0 2px 4px rgba(22,163,74,0.4);
}

.gp-btn-bus-share-micro.is-visible {
    display: inline-flex !important;
}

.gp-micro-badge {
    background: #ffffff;
    color: #15803d;
    font-weight: 700;
    padding: 0 4px;
    font-size: 9px;
    border-radius: 6px;
    margin-left: 2px;
}
</style>

