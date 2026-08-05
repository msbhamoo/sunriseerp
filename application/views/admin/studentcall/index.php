<div class="content-wrapper dashboard2-wrapper" style="min-height: 946px;">
    <section class="content-header" style="padding: 15px 15px 5px 15px;">
        <h1 style="font-size: 20px; font-weight: 800; color: #0f172a; margin: 0;"><i class="fa fa-phone" style="color: #0284c7;"></i> <?php echo ($this->lang->line('student_call_log') ? $this->lang->line('student_call_log') : 'Student Call Log'); ?></h1>
    </section>

    <style type="text/css">
        .dashboard2-wrapper {
            background-color: #f8fafc;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        .d2-metric-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 20px;
        }
        .d2-metric-box {
            background: #ffffff;
            border-radius: 14px;
            padding: 16px 20px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 4px 15px rgba(0,0,0,0.02);
            display: flex;
            align-items: center;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .d2-metric-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.05);
        }
        .d2-metric-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-right: 16px;
        }
        .d2-metric-icon.calls { background: #e0f2fe; color: #0284c7; }
        .d2-metric-icon.connected { background: #d1fae5; color: #059669; }
        .d2-metric-icon.not-connected { background: #ffe4e6; color: #e11d48; }
        .d2-metric-icon.pending { background: #fef3c7; color: #d97706; }
        
        .d2-metric-content { flex: 1; }
        .d2-metric-label { font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px; }
        .d2-metric-value { font-size: 24px; font-weight: 800; color: #0f172a; line-height: 1.2; }

        /* Card Box Enhancements */
        .sc-card {
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 4px 18px rgba(0,0,0,0.02);
            margin-bottom: 20px;
            overflow: hidden;
        }
        .sc-card-header {
            background: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .sc-card-title {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Quick Filter Chips Styling */
        .quick-filter-chips {
            background: #f8fafc;
            padding: 10px 14px;
            border-radius: 10px;
            border: 1px solid #e2e8f0;
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            align-items: center;
        }
        .chip-filter {
            border-radius: 20px !important;
            font-weight: 700 !important;
            padding: 4px 12px !important;
            font-size: 11px !important;
            transition: all 0.2s ease !important;
        }

        @media (max-width: 767px) {
            .mobile-card-table, 
            .mobile-card-table thead, 
            .mobile-card-table tbody, 
            .mobile-card-table th, 
            .mobile-card-table td, 
            .mobile-card-table tr { 
                display: block !important; 
            }
            .mobile-card-table thead tr { 
                position: absolute !important;
                top: -9999px !important;
                left: -9999px !important;
            }
            .mobile-card-table tbody tr {
                background: #ffffff !important;
                border: 1px solid #e2e8f0 !important;
                border-radius: 12px !important;
                margin-bottom: 12px !important;
                padding: 12px 14px !important;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04) !important;
            }
            .mobile-card-table td {
                border: none !important;
                padding: 4px 0 !important;
                position: relative !important;
                display: flex !important;
                justify-content: space-between !important;
                align-items: center !important;
                font-size: 12px !important;
                border-bottom: 1px dashed #f1f5f9 !important;
                text-align: right !important;
            }
            .mobile-card-table td.mobile-hide-empty {
                display: none !important;
            }
            .mobile-card-table td::before {
                content: attr(data-label) !important;
                font-weight: 600 !important;
                color: #64748b !important;
                font-size: 10px !important;
                text-transform: uppercase !important;
                letter-spacing: 0.5px !important;
                margin-right: 12px !important;
                text-align: left !important;
                flex-shrink: 0 !important;
            }
            /* FULL-WIDTH SIDE-BY-SIDE BUTTONS AT BOTTOM */
            .mobile-card-table td[data-label="Action"],
            .mobile-card-table td:last-child {
                display: flex !important;
                width: 100% !important;
                border-bottom: none !important;
                border-top: 1px solid #e2e8f0 !important;
                margin-top: 8px !important;
                padding-top: 10px !important;
                padding-bottom: 0 !important;
            }
            .mobile-card-table td[data-label="Action"]::before,
            .mobile-card-table td:last-child::before {
                display: none !important;
            }
            .mobile-card-table td[data-label="Action"] .pull-right,
            .mobile-card-table td:last-child .pull-right {
                width: 100% !important;
                display: flex !important;
                gap: 8px !important;
                float: none !important;
            }
            .mobile-card-table td[data-label="Action"] .btn,
            .mobile-card-table td:last-child .btn {
                flex: 1 !important;
                width: 50% !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                padding: 8px 10px !important;
                font-size: 12px !important;
                font-weight: 700 !important;
                border-radius: 8px !important;
                text-align: center !important;
            }

            /* HIDE FILTERS BY DEFAULT ON MOBILE (UNLESS FILTER-OPEN IS PRESENT) */
            .collapse-box-mobile:not(.filter-open) form,
            .collapse-box-mobile:not(.filter-open) .box-body.row {
                display: none;
            }
        }
    </style>

    <section class="content">
        <?php if (empty($is_assigned_to_me_view)) { ?>
        <div class="d2-metric-grid">
            <div class="d2-metric-box">
                <div class="d2-metric-icon calls"><i class="fa fa-phone"></i></div>
                <div class="d2-metric-content">
                    <div class="d2-metric-label">Today's Calls</div>
                    <div class="d2-metric-value"><?php echo $total_calls_today; ?></div>
                </div>
            </div>
            <div class="d2-metric-box">
                <div class="d2-metric-icon connected"><i class="fa fa-check"></i></div>
                <div class="d2-metric-content">
                    <div class="d2-metric-label">Connected</div>
                    <div class="d2-metric-value"><?php echo $connected_today; ?></div>
                </div>
            </div>
            <div class="d2-metric-box">
                <div class="d2-metric-icon not-connected"><i class="fa fa-times"></i></div>
                <div class="d2-metric-content">
                    <div class="d2-metric-label">Not Connected</div>
                    <div class="d2-metric-value"><?php echo $not_connected_today; ?></div>
                </div>
            </div>
            <div class="d2-metric-box" id="pending_metric_box" style="cursor:pointer;" title="Click to view all pending follow-ups">
                <div class="d2-metric-icon pending"><i class="fa fa-clock-o"></i></div>
                <div class="d2-metric-content">
                    <div class="d2-metric-label">Pending Follow-ups</div>
                    <div class="d2-metric-value"><?php echo $pending_followups; ?></div>
                </div>
            </div>
        </div>
        <?php } ?>

        <div class="row">
            <div class="col-md-12">
                <?php
                    $has_active_filters = (!empty($_POST['class_id']) || !empty($_POST['section_id']) || !empty($_POST['purpose_id']) || !empty($_POST['status']) || !empty($_POST['date_from']) || !empty($_POST['date_to']) || !empty($_POST['follow_up_date_from']) || !empty($_POST['follow_up_date_to']) || !empty($_POST['assigned_to']));
                ?>
                <div class="box box-primary collapse-box-mobile <?php echo $has_active_filters ? 'filter-open' : ''; ?>">
                    <div class="box-header with-border" style="cursor:pointer;">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool mobile-filter-toggle-btn" style="padding: 4px 8px; font-weight:600; color:#337ab7;" title="Toggle Filters">
                                <i class="fa fa-filter"></i> Filters
                            </button>
                            <?php if ($this->rbac->hasPrivilege('student_call_log', 'can_add')) { ?>
                                <a data-toggle="modal" data-target="#addCallModal" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?php echo ($this->lang->line('add_call') ? $this->lang->line('add_call') : 'Add Call'); ?></a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="box-body" style="padding:10px 15px 0 15px; border-bottom:1px solid #f4f4f4;">
                        <div class="quick-filter-chips" style="display: flex; gap: 8px; flex-wrap: wrap; align-items: center;">
                            <span style="font-size:12px; font-weight:600; color:#666; margin-right:4px;"><i class="fa fa-flash"></i> Quick Views:</span>
                            <button type="button" class="btn btn-default btn-xs chip-filter active" data-filter="all"><i class="fa fa-list"></i> All Calls</button>
                            <button type="button" class="btn btn-danger btn-xs chip-filter" data-filter="overdue"><i class="fa fa-exclamation-triangle"></i> Overdue</button>
                            <button type="button" class="btn btn-warning btn-xs chip-filter" data-filter="due_today"><i class="fa fa-clock-o"></i> Due Today</button>
                            <button type="button" class="btn btn-success btn-xs chip-filter" data-filter="connected"><i class="fa fa-check-circle"></i> Connected Today</button>
                            <button type="button" class="btn btn-info btn-xs chip-filter" data-filter="callback"><i class="fa fa-phone"></i> Callback Requested</button>
                        </div>
                    </div>
                    
                    <form id="searchForm" role="form" action="<?php echo site_url('admin/studentcall' . (!empty($is_assigned_to_me_view) ? '/assigned_to_me' : '')) ?>" method="post" class="">
                        <div class="box-body row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('class'); ?></label>
                                    <select id="class_id" name="class_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach ($class_list as $class) { ?>
                                            <option value="<?php echo $class['id'] ?>" <?php echo set_select('class_id', $class['id']); ?>><?php echo $class['class'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('section'); ?></label>
                                    <select id="section_id" name="section_id" class="form-control" data-saved="<?php echo set_value('section_id'); ?>">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('purpose'); ?></label>
                                    <select id="purpose_id" name="purpose_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php foreach ($purposes as $purpose) { ?>
                                            <option value="<?php echo $purpose['id'] ?>" <?php echo set_select('purpose_id', $purpose['id']); ?>><?php echo $purpose['purpose'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('status'); ?></label>
                                    <select id="status" name="status" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <option value="Connected" <?php echo set_select('status', 'Connected'); ?>>Connected</option>
                                        <option value="Not Connected" <?php echo set_select('status', 'Not Connected'); ?>>Not Connected</option>
                                        <option value="Busy" <?php echo set_select('status', 'Busy'); ?>>Busy</option>
                                        <option value="Switched Off" <?php echo set_select('status', 'Switched Off'); ?>>Switched Off</option>
                                        <option value="Callback Requested" <?php echo set_select('status', 'Callback Requested'); ?>>Callback Requested</option>
                                        <option value="Wrong Number" <?php echo set_select('status', 'Wrong Number'); ?>>Wrong Number</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('date_from'); ?></label>
                                    <input id="date_from" name="date_from" placeholder="" type="text" class="form-control date" value="<?php echo set_value('date_from'); ?>" readonly="readonly" />
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('date_to'); ?></label>
                                    <input id="date_to" name="date_to" placeholder="" type="text" class="form-control date" value="<?php echo set_value('date_to'); ?>" readonly="readonly" />
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label>Follow-up Date From</label>
                                    <input id="follow_up_date_from" name="follow_up_date_from" placeholder="" type="text" class="form-control date" value="<?php echo set_value('follow_up_date_from'); ?>" readonly="readonly" />
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label>Follow-up Date To</label>
                                    <input id="follow_up_date_to" name="follow_up_date_to" placeholder="" type="text" class="form-control date" value="<?php echo set_value('follow_up_date_to'); ?>" readonly="readonly" />
                                </div>
                            </div>
                            <div class="col-sm-2 col-md-2">
                                <div class="form-group">
                                    <label><?php echo ($this->lang->line('assign_to') ? $this->lang->line('assign_to') : 'Assigned To'); ?></label>
                                    <select id="assigned_to" name="assigned_to" class="form-control" >
                                        <?php if (empty($is_assigned_to_me_view)) { ?>
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php } ?>
                                        <?php foreach ($staff_list as $staff) { 
                                            if (!empty($is_assigned_to_me_view) && isset($default_assigned_to) && $default_assigned_to != $staff['id']) {
                                                continue;
                                            }
                                        ?>
                                            <option value="<?php echo $staff['id'] ?>" <?php if ((isset($default_assigned_to) && $default_assigned_to == $staff['id']) || set_value('assigned_to') == $staff['id']) echo "selected=selected" ?>><?php echo $staff['name'] . " " . $staff['surname'] . " (" . $staff['employee_id'] . ")" ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="box box-primary" id="call_list">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-list"></i> <?php echo ($this->lang->line('student_call_log') ? $this->lang->line('student_call_log') : 'Student Call Log'); ?> <?php echo $this->lang->line('list'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover example mobile-card-table" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('student'); ?></th>
                                        <th>Father Name</th>
                                        <th><?php echo $this->lang->line('class'); ?></th>
                                        <th>Pickup Point</th>
                                        <th><?php echo $this->lang->line('phone'); ?></th>
                                        <th><?php echo $this->lang->line('purpose'); ?></th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('note'); ?></th>
                                        <th>Follow-up Status</th>
                                        <th><?php echo ($this->lang->line('assign_to') ? $this->lang->line('assign_to') : 'Assigned To'); ?></th>
                                        <th><?php echo $this->lang->line('created_by'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($calls)) {
                                        foreach ($calls as $call) { 
                                            $normalized_date = !empty($call['date']) ? str_replace('/', '-', $call['date']) : '';
                                            $called_today = (!empty($normalized_date) && date('Y-m-d', strtotime($normalized_date)) == date('Y-m-d')) ? '1' : '0';
                                        ?>
                                            <tr data-called-today="<?php echo $called_today; ?>">
                                                <td data-label="Student">
                                                    <div style="font-weight:600; color:#1e293b;"><?php echo $call['firstname'] . " " . $call['lastname'] . " (" . $call['admission_no'] . ")"; ?></div>
                                                    <div style="margin-top: 4px; display: flex; flex-wrap: wrap; gap: 4px; align-items: center; font-size: 10px;">
                                                        <?php 
                                                            $adm_type = !empty($call['admission_type']) ? $call['admission_type'] : 'New';
                                                            $shrestha = !empty($call['shrestha']) ? $call['shrestha'] : 'No';
                                                            $rte = !empty($call['rte']) ? $call['rte'] : 'No';
                                                            $is_staff_kid = ($call['is_staff_kid'] == 1 || $call['is_staff_kid'] === '1' || strtolower($call['is_staff_kid'] ?? '') === 'yes') ? 'Yes' : 'No';
                                                            $staff_name = $call['student_staff_name'] ?? '';
                                                        ?>
                                                        <span style="background:#e0f2fe; color:#0369a1; padding:1px 6px; border-radius:10px; font-weight:600;" data-toggle="tooltip" title="Admission Type">Adm: <?php echo htmlspecialchars($adm_type); ?></span>
                                                        <span style="background:<?php echo ($shrestha === 'Yes' ? '#dcfce7; color:#15803d;' : '#f1f5f9; color:#64748b;'); ?> padding:1px 6px; border-radius:10px; font-weight:600;" data-toggle="tooltip" title="Shrestha">Shrestha: <?php echo $shrestha; ?></span>
                                                        <span style="background:<?php echo ($rte === 'Yes' ? '#dcfce7; color:#15803d;' : '#f1f5f9; color:#64748b;'); ?> padding:1px 6px; border-radius:10px; font-weight:600;" data-toggle="tooltip" title="RTE">RTE: <?php echo $rte; ?></span>
                                                        <span style="background:<?php echo ($is_staff_kid === 'Yes' ? '#fef3c7; color:#b45309;' : '#f1f5f9; color:#64748b;'); ?> padding:1px 6px; border-radius:10px; font-weight:600;" data-toggle="tooltip" title="Staff Kid">Staff Kid: <?php echo $is_staff_kid . ($is_staff_kid === 'Yes' && !empty($staff_name) ? " (<b>" . htmlspecialchars($staff_name) . "</b>)" : ""); ?></span>
                                                    </div>
                                                </td>
                                                <td data-label="Father Name"><?php echo $call['father_name']; ?></td>
                                                <td data-label="Class"><?php echo $call['class'] . " (" . $call['section'] . ")"; ?></td>
                                                <td data-label="Pickup Point"><?php echo $call['pickup_point_name']; ?></td>
                                                <td data-label="Phone"><?php echo $call['phone_number']; ?></td>
                                                <td data-label="Purpose"><?php echo get_purpose_pill($call['purpose_name']); ?></td>
                                                <td data-label="Status"><?php echo get_call_status_pill($call['call_status']); ?></td>
                                                <td data-label="Date"><?php echo date($this->customlib->getSchoolDateFormat(true, true), strtotime($call['date'])); ?></td>
                                                <td data-label="Note">
                                                    <div style="max-width:160px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; color:#444; font-size:12px;" data-toggle="tooltip" data-placement="top" title="<?php echo htmlspecialchars($call['notes'] ?? ''); ?>">
                                                        <?php 
                                                        if (!empty($call['notes'])) {
                                                            if (strpos($call['notes'], ' | ') !== false) {
                                                                echo '<span class="label label-info" style="font-size:10px; padding:2px 6px;">Structured Notes</span> ' . htmlspecialchars(substr($call['notes'], 0, 30)) . '...';
                                                            } else {
                                                                echo htmlspecialchars($call['notes']);
                                                            }
                                                        } else {
                                                            echo '<span style="color:#ccc;">-</span>';
                                                        }
                                                        ?>
                                                    </div>
                                                </td>
                                                <td data-label="Follow-up Status">
                                                    <?php 
                                                    if ($call['total_followups'] == 0) {
                                                        echo "<span class='label label-default'>None</span>";
                                                    } else if ($call['pending_count'] > 0) {
                                                        $today_date = date('Y-m-d');
                                                        $due_date_str = !empty($call['next_follow_up_date']) ? date('Y-m-d', strtotime($call['next_follow_up_date'])) : '';
                                                        $formatted_due = !empty($call['next_follow_up_date']) ? date($this->customlib->getSchoolDateFormat(), strtotime($call['next_follow_up_date'])) : '';

                                                        if (!empty($due_date_str) && $due_date_str < $today_date) {
                                                            echo "<span class='label label-danger' data-toggle='tooltip' title='Overdue since " . $formatted_due . "'><i class='fa fa-exclamation-triangle'></i> Overdue (" . $formatted_due . ")</span>";
                                                        } else if (!empty($due_date_str) && $due_date_str == $today_date) {
                                                            echo "<span class='label label-warning' data-toggle='tooltip' title='Due Today!'><i class='fa fa-clock-o'></i> Due Today</span>";
                                                        } else {
                                                            echo "<span class='label label-info'><i class='fa fa-calendar'></i> Pending (" . $formatted_due . ")</span>";
                                                        }
                                                    } else {
                                                        echo "<span class='label label-success'><i class='fa fa-check'></i> Resolved</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td data-label="Assigned To"><?php echo $call['assigned_to_name']; ?></td>
                                                <td data-label="Created By"><?php echo $call['staff_name'] . " " . $call['staff_surname']; ?></td>
                                                <td data-label="Action" class="pull-right">
                                                    <?php if ($this->rbac->hasPrivilege('student_call_log', 'can_view')) { 
                                                        $dial_phone = !empty($call['father_phone']) ? $call['father_phone'] : (!empty($call['mother_phone']) ? $call['mother_phone'] : (!empty($call['guardian_phone']) ? $call['guardian_phone'] : (!empty($call['mobileno']) ? $call['mobileno'] : $call['phone_number'])));
                                                        $clean_dial_phone = preg_replace('/[^0-9]/', '', $dial_phone);
                                                        $normalized_date = !empty($call['date']) ? str_replace('/', '-', $call['date']) : '';
                                                        $called_today = (!empty($normalized_date) && date('Y-m-d', strtotime($normalized_date)) == date('Y-m-d')) ? '1' : '0';
                                                        $last_call_date = !empty($call['date']) ? date($this->customlib->getSchoolDateFormat(true, true), strtotime($normalized_date)) : '';
                                                        
                                                        $wa_msg = "Hello, this is regarding student " . $call['firstname'] . " " . $call['lastname'] . " (" . $call['class'] . " - " . $call['section'] . "). Purpose: " . $call['purpose_name'] . ". Please get in touch with us.";
                                                        $wa_link = "https://api.whatsapp.com/send?phone=" . $clean_dial_phone . "&text=" . urlencode($wa_msg);
                                                    ?>
                                                        <div style="display:inline-flex; gap:3px; align-items:center; justify-content:flex-end; white-space:nowrap;">
                                                            <a href="javascript:void(0)" class="btn btn-success btn-xs trigger-direct-call" data-call-id="<?php echo $call['id']; ?>" data-student-name="<?php echo htmlspecialchars($call['firstname'] . ' ' . $call['lastname']); ?>" data-phone="<?php echo $clean_dial_phone; ?>" data-called-today="<?php echo $called_today; ?>" data-last-call-date="<?php echo $last_call_date; ?>" data-toggle="tooltip" title="Dial (<?php echo $dial_phone; ?>)"><i class="fa fa-phone"></i></a>
                                                            <?php if (!empty($clean_dial_phone)) { ?>
                                                                <a href="<?php echo $wa_link; ?>" target="_blank" class="btn btn-xs" style="background-color:#25D366; color:#fff; border-color:#25D366;" data-toggle="tooltip" title="WhatsApp Parent (<?php echo $clean_dial_phone; ?>)"><i class="fa fa-whatsapp"></i></a>
                                                            <?php } ?>
                                                            <a href="javascript:void(0)" class="btn btn-info btn-xs btn-view-call-details" data-id="<?php echo $call['id']; ?>" data-toggle="tooltip" title="View Details"><i class="fa fa-eye"></i></a>
                                                            <?php if ($this->rbac->hasPrivilege('student_call_log', 'can_edit') && $call['pending_count'] > 0) { ?>
                                                                <a href="javascript:void(0)" class="btn btn-primary btn-xs btn-quick-resolve" data-id="<?php echo $call['id']; ?>" data-toggle="tooltip" title="Mark Done"><i class="fa fa-check"></i></a>
                                                                <a href="javascript:void(0)" class="btn btn-warning btn-xs btn-quick-reschedule" data-id="<?php echo $call['id']; ?>" data-toggle="tooltip" title="Reschedule Date"><i class="fa fa-calendar"></i></a>
                                                            <?php } ?>
                                                            <a href="javascript:void(0)" class="btn btn-default btn-xs btn-follow-up-log" data-id="<?php echo $call['id']; ?>" onclick="follow_up('<?php echo $call['id']; ?>'); return false;" data-toggle="tooltip" title="Log Follow-up"><i class="fa fa-pencil"></i></a>
                                                        </div>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
<!-- Add Call Modal -->
<div id="addCallModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo ($this->lang->line('add_call') ? $this->lang->line('add_call') : 'Add Call'); ?></h4>
            </div>
            <div class="modal-body">
                <form id="addCallForm" method="post" accept-charset="utf-8">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo ($this->lang->line('search_student') ? $this->lang->line('search_student') : 'Search Student'); ?></label>
                                <input type="text" id="search_student_keyword" class="form-control" placeholder="Search by name, admission no, roll no..." autocomplete="off">
                                <ul id="student_search_results" class="list-group" style="position: absolute; z-index: 1000; width: 95%; max-height: 200px; overflow-y: auto;"></ul>
                            </div>
                        </div>
                    </div>
                    
                    <div id="call_details_section" style="display:none;">
                        <input type="hidden" name="student_id" id="student_id">
                        <input type="hidden" name="student_session_id" id="student_session_id">
                        
                        <div class="row">
                            <div class="col-md-8">
                                <!-- LEFT COLUMN: FORM -->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('student'); ?> 
                                                <a href="javascript:void(0);" id="toggle_fee_attendance" style="display:none; margin-left: 5px; color: #333;" title="View Fee & Attendance"><i class="fa fa-eye"></i></a>
                                            </label>
                                            <input type="text" id="selected_student_name" class="form-control" readonly>
                                            <div id="selected_student_details" style="margin-top: 5px; font-size: 12px; display: none;"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('call_type'); ?> <small class="req"> *</small></label>
                                            <select name="call_type" class="form-control">
                                                <option value="Incoming">Incoming</option>
                                                <option value="Outgoing" selected>Outgoing</option>
                                            </select>
                                            <span class="text-danger" id="error_call_type"></span>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Fee & Attendance Summary Section (Hidden by default) -->
                                <div id="fee_attendance_section" style="display:none; background: #f9f9f9; padding: 15px; border: 1px solid #ddd; margin-bottom: 15px; border-radius: 4px;">
                                    <div class="row">
                                        <?php if ($this->rbac->hasPrivilege('collect_fees', 'can_view')) { ?>
                                        <div class="col-md-12">
                                            <h5 style="font-weight:bold; margin-top:0;">Fees Summary</h5>
                                            <table class="table table-bordered table-striped" style="font-size:12px; margin-bottom: 10px;">
                                                <thead>
                                                    <tr>
                                                        <th>FEE HEAD</th>
                                                        <th>TOTAL FEES</th>
                                                        <th>COLLECTED</th>
                                                        <th>DUE</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="fee_summary_body">
                                                    <tr><td colspan="4" class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</td></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 style="font-weight:bold; margin-top:0;">Attendance Summary</h5>
                                            <div id="attendance_summary_body" style="font-size:12px;">
                                                <i class="fa fa-spinner fa-spin"></i> Loading...
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo ($this->lang->line('contact_person') ? $this->lang->line('contact_person') : 'Contact Person'); ?> <small class="req"> *</small></label>
                                            <select name="contact_person" id="contact_person" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <option value="Father">Father</option>
                                                <option value="Mother">Mother</option>
                                                <option value="Guardian">Guardian</option>
                                                <option value="Student">Student</option>
                                            </select>
                                            <span class="text-danger" id="error_contact_person"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('phone'); ?> <small class="req"> *</small></label>
                                            <input type="text" name="phone_number" id="phone_number" class="form-control">
                                            <span class="text-danger" id="error_phone_number"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('purpose'); ?></label>
                                            <select name="purpose_id" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php foreach ($purposes as $purpose) { ?>
                                                    <option value="<?php echo $purpose['id'] ?>"><?php echo $purpose['purpose'] ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('status'); ?></label>
                                            <select name="call_status" class="form-control">
                                                <option value="Connected">Connected</option>
                                                <option value="Not Answered">Not Answered</option>
                                                <option value="Busy">Busy</option>
                                                <option value="Switched Off">Switched Off</option>
                                                <option value="Wrong Number">Wrong Number</option>
                                                <option value="Callback Requested">Callback Requested</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('date'); ?> <small class="req"> *</small></label>
                                            <input type="text" name="date" class="form-control datetime" value="<?php echo date($this->customlib->getSchoolDateFormat(true, true)); ?>">
                                            <span class="text-danger" id="error_date"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Duration <small class="text-muted">(Optional)</small></label>
                                            <input type="text" name="duration" class="form-control" placeholder="e.g. 5 mins">
                                        </div>
                                    </div>
                                </div>

                                 <div class="form-group">
                                     <label><?php echo $this->lang->line('note'); ?></label>
                                     <textarea name="notes" class="form-control" rows="2" placeholder="Type call notes or click a quick preset below..."></textarea>
                                     <div class="quick-notes-preset-container" style="margin-top: 8px;">
                                         <small class="text-muted" style="font-weight:600; display:block; margin-bottom: 5px;"><i class="fa fa-bolt text-yellow"></i> Quick Presets (Click to insert):</small>
                                         <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                             <span class="btn btn-default btn-xs quick-note-chip" data-target="textarea[name='notes']" style="border-radius: 12px; font-size: 11px; background: #f0f4f8; border: 1px solid #cbd5e1; color: #334155; font-weight: 500; cursor:pointer;"><i class="fa fa-money text-success"></i> Fee Payment Tomorrow</span>
                                             <span class="btn btn-default btn-xs quick-note-chip" data-target="textarea[name='notes']" style="border-radius: 12px; font-size: 11px; background: #f0f4f8; border: 1px solid #cbd5e1; color: #334155; font-weight: 500; cursor:pointer;"><i class="fa fa-calendar text-info"></i> Payment in 2-4 days</span>
                                             <span class="btn btn-default btn-xs quick-note-chip" data-target="textarea[name='notes']" style="border-radius: 12px; font-size: 11px; background: #f0f4f8; border: 1px solid #cbd5e1; color: #334155; font-weight: 500; cursor:pointer;"><i class="fa fa-file-text-o text-primary"></i> Requested Fee Receipt</span>
                                             <span class="btn btn-default btn-xs quick-note-chip" data-target="textarea[name='notes']" style="border-radius: 12px; font-size: 11px; background: #f0f4f8; border: 1px solid #cbd5e1; color: #334155; font-weight: 500; cursor:pointer;"><i class="fa fa-clock-o text-warning"></i> Call back in Evening</span>
                                             <span class="btn btn-default btn-xs quick-note-chip" data-target="textarea[name='notes']" style="border-radius: 12px; font-size: 11px; background: #f0f4f8; border: 1px solid #cbd5e1; color: #334155; font-weight: 500; cursor:pointer;"><i class="fa fa-question-circle text-purple"></i> Exam / Syllabus Inquiry</span>
                                             <span class="btn btn-default btn-xs quick-note-chip" data-target="textarea[name='notes']" style="border-radius: 12px; font-size: 11px; background: #f0f4f8; border: 1px solid #cbd5e1; color: #334155; font-weight: 500; cursor:pointer;"><i class="fa fa-phone text-danger"></i> Wrong / Unreachable No.</span>
                                             <span class="btn btn-default btn-xs quick-note-chip" data-target="textarea[name='notes']" style="border-radius: 12px; font-size: 11px; background: #f0f4f8; border: 1px solid #cbd5e1; color: #334155; font-weight: 500; cursor:pointer;"><i class="fa fa-check text-success"></i> Issue Resolved</span>
                                         </div>
                                     </div>
                                 </div>
                                
                                <div class="form-group" id="sync_siblings_container" style="display:none; background: #fdfdfd; padding: 10px; border: 1px solid #eee; border-radius: 4px;">
                                    <label style="color:#337ab7;"><i class="fa fa-copy"></i> Sync Call to Siblings</label>
                                    <p class="text-muted" style="font-size:12px; margin-bottom:5px;">Check the boxes below to automatically create identical call log entries for siblings discussed during this call.</p>
                                    <div id="sync_siblings_list"></div>
                                </div>
                                
                                <hr>
                                <h4 class="box-title"><?php echo ($this->lang->line('follow_up') ? $this->lang->line('follow_up') : 'Create Follow Up'); ?> (Optional)</h4>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?php echo ($this->lang->line('next_follow_up_date') ? $this->lang->line('next_follow_up_date') : 'Next Date'); ?></label>
                                            <input type="text" name="follow_up_date" class="form-control fw_date" autocomplete="off" placeholder="Select future date">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?php echo ($this->lang->line('priority') ? $this->lang->line('priority') : 'Priority'); ?></label>
                                            <select name="priority" class="form-control">
                                                <option value="Low">Low</option>
                                                <option value="Medium" selected>Medium</option>
                                                <option value="High">High</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label><?php echo ($this->lang->line('assign_to') ? $this->lang->line('assign_to') : 'Assign To'); ?></label>
                                            <select name="assigned_to" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php foreach ($staff_list as $staff) { ?>
                                                    <option value="<?php echo $staff['id'] ?>"><?php echo $staff['name'] . " " . $staff['surname'] . " (" . $staff['employee_id'] . ")" ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <!-- RIGHT COLUMN: HISTORY -->
                                <div class="box box-primary" style="margin-bottom:0;">
                                    <div class="box-header with-border" style="padding: 10px;">
                                        <h3 class="box-title" style="font-size: 14px;"><i class="fa fa-history"></i> Recent History</h3>
                                    </div>
                                    <div class="box-body" id="recent_history_container" style="max-height: 400px; overflow-y: auto; background: #f9f9f9; padding: 10px;">
                                        <div class="text-center text-muted" style="padding: 20px;">Select a student to view history</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 text-right" style="padding-top: 15px; border-top: 1px solid #e5e5e5; margin-top: 15px;">
                            <button type="button" class="btn btn-default" id="saveAndNextBtn" style="display:none;" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving...">Save & Log Another</button>
                            <button type="submit" class="btn btn-info" id="submitBtn" style="display:none;" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>">Save & Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="repeatCallWarningModal" tabindex="-1" role="dialog" aria-labelledby="repeatCallWarningModalLabel" style="z-index: 1060;">
    <div class="modal-dialog" role="document" style="max-width: 420px; margin: 80px auto;">
        <div class="modal-content" style="border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.25); border: none;">
            <div class="modal-header" style="background: #fff3cd; color: #856404; border-top-left-radius: 12px; border-top-right-radius: 12px; border-bottom: 1px solid #ffeeba;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="opacity: 0.8;"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="repeatCallWarningModalLabel" style="font-weight: 700; font-size: 15px;"><i class="fa fa-exclamation-triangle text-warning"></i> Already Called Today</h4>
            </div>
            <div class="modal-body" style="padding: 20px; font-size: 14px; color: #334155; text-align: center;">
                <p id="repeat_call_warning_text" style="margin-bottom: 0; line-height: 1.5;"></p>
            </div>
            <div class="modal-footer" style="background: #f8fafc; border-bottom-left-radius: 12px; border-bottom-right-radius: 12px; padding: 12px 20px; text-align: center; border-top: 1px solid #e2e8f0; display:flex; justify-content:center; gap:10px;">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" style="border-radius: 6px; font-weight: 600; padding: 6px 16px;">Cancel</button>
                <button type="button" class="btn btn-warning btn-sm" id="confirm_dial_btn" style="border-radius: 6px; font-weight: 700; padding: 6px 16px;"><i class="fa fa-phone"></i> Yes, Call Again</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="followUpModal" tabindex="-1" role="dialog" aria-labelledby="followUpModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="followUpModalLabel"><?php echo ($this->lang->line('follow_up') ? $this->lang->line('follow_up') : 'Follow Up'); ?></h4>
            </div>
            <div class="modal-body" id="followUpModalBody">
                <!-- Content loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<script>
    var student_phones = {};

    $(document).ready(function () {
        var pendingCallIdToOpen = null;
        var pendingPhoneToDial = null;

        $(document).on('click', '.trigger-direct-call', function (e) {
            e.preventDefault();
            var callId = $(this).attr('data-call-id');
            var studentName = $(this).attr('data-student-name');
            var phone = $(this).attr('data-phone');
            var calledToday = $(this).attr('data-called-today');
            var lastCallDate = $(this).attr('data-last-call-date');

            pendingCallIdToOpen = callId;
            pendingPhoneToDial = phone;

            if (calledToday == '1' || calledToday === 1 || calledToday === 'true' || calledToday === true) {
                var msg = "A call was already logged for <strong>" + studentName + "</strong> today (" + (lastCallDate || 'Today') + ").<br><br>Do you want to call again?";
                $('#repeat_call_warning_text').html(msg);
                $('#repeatCallWarningModal').modal('show');
            } else {
                execDialAndTrack(phone, callId);
            }
        });

        $(document).on('click', '#confirm_dial_btn', function () {
            $('#repeatCallWarningModal').modal('hide');
            if (pendingPhoneToDial && pendingCallIdToOpen) {
                execDialAndTrack(pendingPhoneToDial, pendingCallIdToOpen);
            }
        });

        function execDialAndTrack(phone, callId) {
            if (phone) {
                try {
                    window.location.href = 'tel:' + phone;
                } catch(e) {}
            }
            if (callId && typeof follow_up === 'function') {
                follow_up(callId);
            }
        }



        $(document).ajaxComplete(function() {
            applyMobileCardLabels();
        });

        function applyMobileCardLabels() {
            var labels = ['Student', 'Father Name', 'Class', 'Pickup Point', 'Phone', 'Purpose', 'Status', 'Date', 'Note', 'Follow-up Status', 'Assigned To', 'Created By', 'Action'];
            $('.mobile-card-table tbody tr').each(function() {
                $(this).find('td').each(function(i) {
                    if (labels[i] && !$(this).attr('data-label')) {
                        $(this).attr('data-label', labels[i]);
                    }
                    var text = $(this).text().trim();
                    if (text === '' || text === '-' || text === 'None' || text === '<span style="color:#ccc;">-</span>') {
                        $(this).addClass('mobile-hide-empty');
                    } else {
                        $(this).removeClass('mobile-hide-empty');
                    }
                });
            });
        }
        applyMobileCardLabels();

        // Auto-collapse filters on mobile by default
        if ($(window).width() < 768) {
            $('.collapse-box-mobile').addClass('collapsed-box');
            $('.collapse-box-mobile form, .collapse-box-mobile .box-body.row').hide();
        }

        $(document).on('click', '.mobile-filter-toggle-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            var $box = $(this).closest('.box');
            var $target = $box.find('form, .box-body.row').first();
            $box.toggleClass('filter-open');
            if ($box.hasClass('filter-open')) {
                $target.slideDown(200);
            } else {
                $target.slideUp(200);
            }
        });

        $(document).on('click', '.btn-follow-up-log', function(e) {
            e.preventDefault();
            var id = $(this).data('id');
            if (id) {
                follow_up(id);
            }
        });

        $(document).on('click', '#pending_metric_box', function () {
            $('#tab_pending_followup_li a').tab('show');
        });

        $(document).on('click', '.quick-note-chip', function () {
            var textToInsert = $(this).text().trim();
            var targetSelector = $(this).data('target');
            var $textarea = $(targetSelector);
            if ($textarea.length) {
                var currentText = $textarea.val().trim();
                if (currentText.length > 0) {
                    $textarea.val(currentText + ", " + textToInsert);
                } else {
                    $textarea.val(textToInsert);
                }
                $textarea.focus();
            }
        });

        $('#search_student_keyword').on('keyup', function () {
            var keyword = $(this).val();
            if (keyword.length >= 2) {
                $.ajax({
                    url: '<?php echo site_url("admin/studentcall/search_student") ?>',
                    type: 'POST',
                    data: {keyword: keyword},
                    dataType: 'json',
                    success: function (res) {
                        var html = '';
                        student_phones = {};
                        var student_details = {};
                        $.each(res, function (index, value) {
                            student_phones[value.student_id] = {
                                Father: value.father_phone,
                                Mother: value.mother_phone,
                                Guardian: value.guardian_phone,
                                Student: value.mobileno
                            };
                            student_details[value.student_id] = {
                                admission_type: value.admission_type || 'New',
                                shrestha: value.shrestha || 'No',
                                rte: value.rte || 'No',
                                is_staff_kid: (value.is_staff_kid == 1 || value.is_staff_kid === '1') ? 'Yes' : 'No',
                                staff_name: value.staff_name || ''
                            };
                            html += '<li class="list-group-item student-item" style="cursor:pointer;" data-id="' + value.student_id + '" data-session="' + value.student_session_id + '" data-name="' + value.firstname + ' ' + value.lastname + ' (' + value.admission_no + ')' + (value.father_name ? ' - Father: ' + value.father_name : '') + ' - ' + value.class + ' (' + value.section + ')">' + value.firstname + ' ' + value.lastname + ' (' + value.admission_no + ')' + (value.father_name ? ' - Father: ' + value.father_name : '') + ' - ' + value.class + ' (' + value.section + ')</li>';
                        });
                        $('#student_search_results').html(html);
                        window.student_details_map = student_details;
                    }
                });
            } else {
                $('#student_search_results').html('');
            }
        });

        $(document).on('click', '.student-item', function () {
            var selected_student_id = $(this).data('id');
            $('#student_id').val(selected_student_id);
            $('#student_session_id').val($(this).data('session'));
            $('#selected_student_name').val($(this).data('name'));
            $('#search_student_keyword').val('');
            $('#student_search_results').html('');

            if (window.student_details_map && window.student_details_map[selected_student_id]) {
                var sd = window.student_details_map[selected_student_id];
                
                var detailsHtml = '<div style="margin-top: 6px; padding: 8px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; display: flex; flex-wrap: wrap; gap: 6px 12px; align-items: center; font-size: 11px; color: #475569;">' +
                    '<span><strong style="color:#1e293b;">Adm Type:</strong> <span style="background:#e0f2fe; color:#0369a1; padding:2px 7px; border-radius:12px; font-weight:600;">' + sd.admission_type + '</span></span>' +
                    '<span><strong style="color:#1e293b;">Shrestha:</strong> <span style="background:' + (sd.shrestha === 'Yes' ? '#dcfce7; color:#15803d;' : '#f1f5f9; color:#64748b;') + ' padding:2px 7px; border-radius:12px; font-weight:600;">' + sd.shrestha + '</span></span>' +
                    '<span><strong style="color:#1e293b;">RTE:</strong> <span style="background:' + (sd.rte === 'Yes' ? '#dcfce7; color:#15803d;' : '#f1f5f9; color:#64748b;') + ' padding:2px 7px; border-radius:12px; font-weight:600;">' + sd.rte + '</span></span>' +
                    '<span><strong style="color:#1e293b;">Staff Kid:</strong> <span style="background:' + (sd.is_staff_kid === 'Yes' ? '#fef3c7; color:#b45309;' : '#f1f5f9; color:#64748b;') + ' padding:2px 7px; border-radius:12px; font-weight:600;">' + sd.is_staff_kid + '</span>' +
                    (sd.is_staff_kid === 'Yes' && sd.staff_name ? ' <span style="color:#0f172a; font-weight:600; margin-left:2px;">(' + sd.staff_name + ')</span>' : '') + '</span>' +
                    '</div>';
                $('#selected_student_details').html(detailsHtml).show();
            } else {
                $('#selected_student_details').hide().html('');
            }
            
            $('#toggle_fee_attendance').show();
            $('#fee_attendance_section').hide();
            
            $('#call_details_section').show();
            $('#submitBtn, #saveAndNextBtn').show();
            
            // Trigger contact person change to auto-fill phone
            $('#contact_person').val('');
            $('#phone_number').val('');

            // Fetch Recent History
            $('#recent_history_container').html('<div class="text-center" style="padding: 20px;"><i class="fa fa-spinner fa-spin fa-2x text-muted"></i></div>');
            $.ajax({
                url: "<?php echo site_url("admin/studentcall/get_recent_history/") ?>" + selected_student_id,
                type: "GET",
                dataType: "json",
                success: function(res) {
                    if(res.status == 'success') {
                        $('#recent_history_container').html(res.html);
                    }
                }
            });
            
            // Fetch Siblings
            $('#sync_siblings_container').hide();
            $('#sync_siblings_list').html('');
            $.ajax({
                url: "<?php echo site_url("admin/studentcall/get_siblings/") ?>" + selected_student_id,
                type: "GET",
                dataType: "json",
                success: function(res) {
                    if(res.status == 'success' && res.siblings.length > 0) {
                        var sibHtml = '';
                        $.each(res.siblings, function(i, sib) {
                            sibHtml += '<div class="checkbox"><label><input type="checkbox" name="copy_to_siblings[]" value="' + sib.student_session_id + '|' + sib.student_id + '"> ' + sib.name + ' (' + sib.class_section + ')</label></div>';
                        });
                        $('#sync_siblings_list').html(sibHtml);
                        $('#sync_siblings_container').show();
                    }
                }
            });
        });

        $('#contact_person').on('change', function () {
            var person = $(this).val();
            var student_id = $('#student_id').val();
            if (person && student_id && student_phones[student_id]) {
                var phone = student_phones[student_id][person];
                if(phone) {
                    var x = phone.replace(/\D/g, '');
                    $('#phone_number').val(x);
                }
            }
        });

        $('#toggle_fee_attendance').on('click', function() {
            var $section = $('#fee_attendance_section');
            if ($section.is(':visible')) {
                $section.slideUp();
            } else {
                $section.slideDown();
                var student_session_id = $('#student_session_id').val();
                if (student_session_id) {
                    $('#fee_summary_body').html('<tr><td colspan="4" class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</td></tr>');
                    $('#attendance_summary_body').html('<i class="fa fa-spinner fa-spin"></i> Loading...');
                    
                    $.ajax({
                        url: "<?php echo site_url('admin/certificateregister/get_student_fee_summary_ajax') ?>",
                        type: "POST",
                        data: { student_session_id: student_session_id },
                        dataType: "json",
                        success: function (res) {
                            var feeHtml = '';
                            if (res.academic) {
                                feeHtml += '<tr><td>Academic Fees</td><td>' + parseFloat(res.academic.total).toFixed(2) + '</td><td>' + parseFloat(res.academic.collected).toFixed(2) + '</td><td><b>' + parseFloat(res.academic.due).toFixed(2) + '</b></td></tr>';
                            }
                            if (res.transport && res.transport.total > 0) {
                                feeHtml += '<tr><td>Transport Fees</td><td>' + parseFloat(res.transport.total).toFixed(2) + '</td><td>' + parseFloat(res.transport.collected).toFixed(2) + '</td><td><b>' + parseFloat(res.transport.due).toFixed(2) + '</b></td></tr>';
                            }
                            if (res.hostel && res.hostel.total > 0) {
                                feeHtml += '<tr><td>Hostel Fees</td><td>' + parseFloat(res.hostel.total).toFixed(2) + '</td><td>' + parseFloat(res.hostel.collected).toFixed(2) + '</td><td><b>' + parseFloat(res.hostel.due).toFixed(2) + '</b></td></tr>';
                            }
                            $('#fee_summary_body').html(feeHtml);

                            var attHtml = '';
                            if (res.history && (res.history.working_days !== undefined && res.history.working_days !== null)) {
                                var working = res.history.working_days ? res.history.working_days : 0;
                                var present = res.history.present_days ? res.history.present_days : 0;
                                var absent = working - present;
                                var perc = res.history.attendance_percentage ? res.history.attendance_percentage + '%' : ((working > 0) ? ((present/working)*100).toFixed(2) + '%' : 'N/A');
                                attHtml += 'Working Days: <b>' + working + '</b> | Present Days: <b>' + present + '</b> | Absent Days: <b>' + absent + '</b> | Percentage: <b>' + perc + '</b>';
                            } else {
                                attHtml = 'No attendance history found.';
                            }
                            $('#attendance_summary_body').html(attHtml);
                        },
                        error: function() {
                            $('#fee_summary_body').html('<tr><td colspan="4" class="text-center text-danger">Error loading fee details.</td></tr>');
                            $('#attendance_summary_body').html('<span class="text-danger">Error loading attendance details.</span>');
                        }
                    });
                }
            }
        });

        // Professional Phone Number Auto-formatting
        $('#phone_number').on('input', function (e) {
            var x = e.target.value.replace(/\D/g, '');
            e.target.value = x;
        });
        
        // Initialize Datepicker with minDate constraint
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
        $('.fw_date').datepicker({
            format: date_format,
            autoclose: true,
            startDate: new Date()
        });

        $('#addCallForm').on('submit', function (e) {
            e.preventDefault();
            submitCallForm(false);
        });

        $('#saveAndNextBtn').on('click', function () {
            submitCallForm(true);
        });

        function submitCallForm(logAnother) {
            var $btn = logAnother ? $('#saveAndNextBtn') : $('#submitBtn');
            $btn.button('loading');
            $.ajax({
                url: '<?php echo site_url("admin/studentcall/add_call") ?>',
                type: "POST",
                data: new FormData($('#addCallForm')[0]),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (res) {
                    if (res.status == "fail") {
                        $.each(res.error, function(key, val) {
                            $('#error_' + key).html(val);
                        });
                        $btn.button('reset');
                    } else {
                        successMsg(res.message);
                        if (logAnother) {
                            $('#addCallForm')[0].reset();
                            $('#call_details_section').hide();
                            $('#selected_student_details').hide().html('');
                            $('#submitBtn, #saveAndNextBtn').hide();
                            $('#student_search_results').empty();
                            $('#search_student_keyword').focus();
                            $btn.button('reset');
                        } else {
                            window.location.reload(true);
                        }
                    }
                },
                error: function() {
                    $btn.button('reset');
                }
            });
        }

        $('#class_id').change(function(){
            var class_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $('#section_id').empty();
                    $('#section_id').append('<option value=""><?php echo $this->lang->line('select'); ?></option>');
                    $.each(data, function (i, obj)
                    {
                        $('#section_id').append("<option value=" + obj.section_id + ">" + obj.section + "</option>");
                    });
                }
            });
        });

        $('#searchForm').on('submit', function(e) {
            e.preventDefault();
            var $btn = $(this).find('button[type="submit"]');
            $btn.button('loading');
            $.ajax({
                url: '<?php echo site_url("admin/studentcall/search_ajax") ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(res) {
                    if (res.status == 'success') {
                        var table = $('.example').DataTable();
                        table.clear();
                        if (res.data && res.data.length > 0) {
                            table.rows.add(res.data);
                        }
                        table.draw();
                    }
                    $btn.button('reset');
                },
                error: function() {
                    $btn.button('reset');
                }
            });
        });
        $('#status_class_id').change(function(){
            var class_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $('#status_section_id').empty();
                    $('#status_section_id').append('<option value=""><?php echo $this->lang->line('select'); ?></option>');
                    $.each(data, function (i, obj)
                    {
                        $('#status_section_id').append("<option value=" + obj.section_id + ">" + obj.section + "</option>");
                    });
                }
            });
        });

        var student_status_table = $('#student_status_table').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?php echo site_url('admin/studentcall/students_status_ajax') ?>",
                "type": "POST",
                "data": function (d) {
                    d.class_id = $('#status_class_id').val();
                    d.section_id = $('#status_section_id').val();
                    d.admission_type = $('#status_admission_type').val();
                    d.shrestha = $('#status_shrestha').val();
                    d.rte = $('#status_rte').val();
                    d.is_staff_kid = $('#status_is_staff_kid').val();
                    d.<?php echo $this->security->get_csrf_token_name(); ?> = "<?php echo $this->security->get_csrf_hash(); ?>";
                }
            },
            "columns": [
                { "orderable": true },
                { "orderable": true },
                { "orderable": true },
                { "orderable": true },
                { "orderable": true },
                { "orderable": true },
                { "orderable": true },
                { "orderable": true },
                { "orderable": false }
            ]
        });

        $('#btn_status_search').on('click', function() {
            student_status_table.ajax.reload();
        });

        // Trigger section load if class is already selected (from saved session filters)
        if ($('#status_class_id').val() !== '') {
            var class_id = $('#status_class_id').val();
            var saved_section = $('#status_section_id').data('saved');
            var base_url = '<?php echo base_url() ?>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $('#status_section_id').empty();
                    $('#status_section_id').append('<option value=""><?php echo $this->lang->line('select'); ?></option>');
                    $.each(data, function (i, obj)
                    {
                        var sel = (saved_section == obj.section_id) ? 'selected' : '';
                        $('#status_section_id').append("<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>");
                    });
                }
            });
        }
    });

    function follow_up(id) {
        var ajaxUrl = '<?php echo site_url("admin/studentcall/follow_up/") ?>' + id;
        ajaxUrl = ajaxUrl.replace(/^http:/, window.location.protocol);
        $.ajax({
            url: ajaxUrl,
            success: function (res) {
                $('#followUpModalBody').html(res);
                $('#followUpModal').modal('show');
            },
            error: function(xhr, status, err) {
                console.error("Follow up load error:", status, err);
                alert("Could not load follow up form. Please refresh or try again.");
            }
        });
    }

    function openCallModalFromStatus(student_id, session_id, name) {
        $('#addCallModal').modal('show');
        setTimeout(function(){
            $('.student-item').remove(); // clear existing dropdown
            $('#student_id').val(student_id);
            $('#student_session_id').val(session_id);
            $('#selected_student_name').val(name);
            $('#search_student_keyword').val('');
            
            $('#toggle_fee_attendance').show();
            $('#fee_attendance_section').hide();
            
            $('#call_details_section').show();
            $('#submitBtn, #saveAndNextBtn').show();
            
            $('#contact_person').val('');
            $('#phone_number').val('');

            $('#recent_history_container').html('<div class="text-center" style="padding: 20px;"><i class="fa fa-spinner fa-spin fa-2x text-muted"></i></div>');
            $.ajax({
                url: "<?php echo site_url('admin/studentcall/get_recent_history/') ?>" + student_id,
                type: "GET",
                dataType: "json",
                success: function(res) {
                    if(res.status == 'success') {
                        $('#recent_history_container').html(res.html);
                    }
                }
            });
        }, 500);
    }

    // --- QUICK FILTER CHIPS & DETAILS MODAL JS HANDLERS ---
    $(document).ready(function() {
        // Quick Filter Chips Handler using DataTables custom search
        $('.chip-filter').on('click', function() {
            $('.chip-filter').removeClass('active btn-primary').addClass('btn-default');
            $(this).removeClass('btn-default').addClass('active btn-primary');
            
            var filter = $(this).data('filter');
            var table = $('.example').DataTable();

            // Clear previous custom search functions
            $.fn.dataTable.ext.search = [];

            if (filter === 'overdue') {
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    var cellHtml = table.cell(dataIndex, 9).node().innerHTML;
                    return cellHtml.indexOf('Overdue') !== -1 || cellHtml.indexOf('label-danger') !== -1;
                });
            } else if (filter === 'due_today') {
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    var cellHtml = table.cell(dataIndex, 9).node().innerHTML;
                    return cellHtml.indexOf('Due Today') !== -1 || cellHtml.indexOf('label-warning') !== -1;
                });
            } else if (filter === 'connected') {
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    var statusHtml = table.cell(dataIndex, 6).node().innerHTML;
                    var rowNode = table.row(dataIndex).node();
                    var isConnected = statusHtml.indexOf('Connected') !== -1;
                    var calledToday = $(rowNode).attr('data-called-today');
                    if (!calledToday) {
                        calledToday = $(rowNode).find('.trigger-direct-call').attr('data-called-today');
                    }
                    return isConnected && calledToday === '1';
                });
            } else if (filter === 'callback') {
                $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
                    var cellHtml = table.cell(dataIndex, 6).node().innerHTML;
                    return cellHtml.indexOf('Callback Requested') !== -1;
                });
            }

            table.draw();
        });

        // View Details Modal Handler
        $(document).on('click', '.btn-view-call-details', function() {
            var callId = $(this).data('id');
            $('#detailModalBody').html('<div class="text-center" style="padding:30px;"><i class="fa fa-spinner fa-spin fa-2x text-info"></i><p>Loading call details...</p></div>');
            $('#callDetailModal').modal('show');

            $.ajax({
                url: '<?php echo site_url("admin/studentcall/get_call_details_ajax/") ?>' + callId,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    if (res.status === 'success') {
                        var call = res.data;
                        var html = '<div class="row" style="margin-bottom:15px;">';
                        html += '<div class="col-md-6"><strong>Student:</strong> ' + call.firstname + ' ' + call.lastname + ' (' + call.admission_no + ')</div>';
                        html += '<div class="col-md-6"><strong>Class:</strong> ' + call.class + ' (' + call.section + ')</div>';
                        html += '<div class="col-md-6" style="margin-top:6px;"><strong>Contact Person:</strong> ' + (call.contact_person || '-') + '</div>';
                        html += '<div class="col-md-6" style="margin-top:6px;"><strong>Phone:</strong> ' + call.phone_number + '</div>';
                        html += '<div class="col-md-6" style="margin-top:6px;"><strong>Purpose:</strong> <span class="label label-info">' + call.purpose_name + '</span></div>';
                        html += '<div class="col-md-6" style="margin-top:6px;"><strong>Status:</strong> <span class="label label-warning">' + call.call_status + '</span></div>';
                        html += '</div><hr style="margin:10px 0;">';

                        html += '<h5 style="font-weight:700; color:#333;"><i class="fa fa-file-text-o"></i> Notes & Remarks Breakdown:</h5>';
                        if (call.parsed_notes && call.parsed_notes.length > 0) {
                            html += '<div class="row">';
                            $.each(call.parsed_notes, function(i, item) {
                                html += '<div class="col-md-12" style="margin-bottom:8px;">';
                                html += '<div style="background:#f8fafc; border-left:4px solid #0284c7; padding:8px 12px; border-radius:4px;">';
                                html += '<strong style="color:#0369a1;">' + item.label + ':</strong> ' + item.value;
                                html += '</div></div>';
                            });
                            html += '</div>';
                        } else {
                            html += '<p class="text-muted">No notes recorded.</p>';
                        }

                        if (call.followups && call.followups.length > 0) {
                            html += '<hr style="margin:15px 0;"><h5 style="font-weight:700; color:#333;"><i class="fa fa-history"></i> Follow-up History:</h5><ul class="list-group">';
                            $.each(call.followups, function(i, fw) {
                                var badge = fw.status === 'Pending' ? '<span class="label label-warning pull-right">Pending</span>' : '<span class="label label-success pull-right">Resolved</span>';
                                html += '<li class="list-group-item">' + badge + '<strong>Due:</strong> ' + fw.due_date + ' | <strong>Assigned:</strong> ' + (fw.assigned_name || '-') + ' ' + (fw.assigned_surname || '') + '<br><small class="text-muted">' + (fw.remarks || 'No remarks') + '</small></li>';
                            });
                            html += '</ul>';
                        }

                        $('#detailModalBody').html(html);
                    } else {
                        $('#detailModalBody').html('<div class="alert alert-danger">' + res.message + '</div>');
                    }
                },
                error: function() {
                    $('#detailModalBody').html('<div class="alert alert-danger">Error loading call details.</div>');
                }
            });
        });

        // Quick Resolve Handler
        $(document).on('click', '.btn-quick-resolve', function() {
            var callId = $(this).data('id');
            $('#quick_resolve_call_id').val(callId);
            $('#quick_resolve_remarks').val('');
            $('#quickResolveModal').modal('show');
        });

        $('#quickResolveForm').on('submit', function(e) {
            e.preventDefault();
            var $btn = $(this).find('button[type="submit"]');
            $btn.button('loading');
            $.ajax({
                url: '<?php echo site_url("admin/studentcall/quick_resolve_followup") ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(res) {
                    $btn.button('reset');
                    if (res.status === 'success') {
                        $('#quickResolveModal').modal('hide');
                        $('#searchForm').submit();
                    } else {
                        alert(res.message);
                    }
                },
                error: function() {
                    $btn.button('reset');
                    alert('Action failed. Please try again.');
                }
            });
        });

        // Quick Reschedule Handler
        $(document).on('click', '.btn-quick-reschedule', function() {
            var callId = $(this).data('id');
            $('#quick_reschedule_call_id').val(callId);
            $('#quickRescheduleModal').modal('show');
        });

        $('.shortcut-date-btn').on('click', function() {
            var days = parseInt($(this).data('days'));
            var d = new Date();
            d.setDate(d.getDate() + days);
            var dd = String(d.getDate()).padStart(2, '0');
            var mm = String(d.getMonth() + 1).padStart(2, '0');
            var yyyy = d.getFullYear();
            var formatted = mm + '/' + dd + '/' + yyyy;
            $('#quick_reschedule_date').val(formatted);
        });

        $('#quickRescheduleForm').on('submit', function(e) {
            e.preventDefault();
            var $btn = $(this).find('button[type="submit"]');
            $btn.button('loading');
            $.ajax({
                url: '<?php echo site_url("admin/studentcall/quick_reschedule_followup") ?>',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(res) {
                    $btn.button('reset');
                    if (res.status === 'success') {
                        $('#quickRescheduleModal').modal('hide');
                        $('#searchForm').submit();
                    } else {
                        alert(res.message);
                    }
                },
                error: function() {
                    $btn.button('reset');
                    alert('Action failed. Please try again.');
                }
            });
        });
    });
</script>

<!-- DETAILS MODAL -->
<div class="modal fade" id="callDetailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header" style="background:#0284c7; color:#fff;">
                <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
                <h4 class="modal-title"><i class="fa fa-info-circle"></i> Call Log & PTM Details</h4>
            </div>
            <div class="modal-body" id="detailModalBody">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- QUICK RESOLVE MODAL -->
<div class="modal fade" id="quickResolveModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="quickResolveForm">
                <?php echo $this->customlib->getCSRF(); ?>
                <input type="hidden" name="call_id" id="quick_resolve_call_id">
                <div class="modal-header" style="background:#10b981; color:#fff;">
                    <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-check-circle"></i> Complete Follow-up</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Completion Remarks / Outcome</label>
                        <textarea name="remarks" class="form-control" rows="3" placeholder="Enter resolution notes (e.g. Call connected, parent agreed)..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Mark Complete</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- QUICK RESCHEDULE MODAL -->
<div class="modal fade" id="quickRescheduleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="quickRescheduleForm">
                <?php echo $this->customlib->getCSRF(); ?>
                <input type="hidden" name="call_id" id="quick_reschedule_call_id">
                <div class="modal-header" style="background:#f59e0b; color:#fff;">
                    <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
                    <h4 class="modal-title"><i class="fa fa-calendar"></i> Quick Reschedule Follow-up</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Quick Date Shortcuts:</label><br>
                        <button type="button" class="btn btn-default btn-xs shortcut-date-btn" data-days="1">+1 Day (Tomorrow)</button>
                        <button type="button" class="btn btn-default btn-xs shortcut-date-btn" data-days="3">+3 Days</button>
                        <button type="button" class="btn btn-default btn-xs shortcut-date-btn" data-days="7">+1 Week</button>
                    </div>
                    <div class="form-group">
                        <label>New Due Date</label>
                        <input type="text" name="next_follow_up_date" id="quick_reschedule_date" class="form-control date" placeholder="MM/DD/YYYY" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning"><i class="fa fa-calendar"></i> Reschedule</button>
                </div>
            </form>
        </div>
    </div>
</div>
