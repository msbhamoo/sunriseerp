<?php
$total_passes = count($duty_passes);
$today_on_duty = 0;
$pending_count = 0;
$approved_count = 0;
$completed_count = 0;
$multiday_count = 0;

$today_date = date('Y-m-d');

foreach ($duty_passes as $dp) {
    if ($dp['status'] == 'Pending') {
        $pending_count++;
    } elseif ($dp['status'] == 'Approved') {
        $approved_count++;
        if ($dp['from_date'] <= $today_date && $dp['to_date'] >= $today_date) {
            $today_on_duty += count($dp['staff_members']);
        }
    } elseif ($dp['status'] == 'Completed') {
        $completed_count++;
    }

    if ($dp['from_date'] !== $dp['to_date']) {
        $multiday_count++;
    }
}
?>

<!-- Select2 CSS & JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style type="text/css">
    :root {
        --dp-primary: #0d9488;
        --dp-primary-dark: #0f766e;
        --dp-primary-light: #f0fdfa;
        --dp-border: #e2e8f0;
        --dp-text-main: #0f172a;
        --dp-text-muted: #64748b;
    }

    .dutypass-page {
        font-family: inherit;
        color: var(--dp-text-main);
    }

    /* KPI Bar */
    .dp-stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
        margin-bottom: 16px;
    }

    .dp-stat-card {
        background: #ffffff;
        border: 1px solid var(--dp-border);
        border-radius: 12px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .dp-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.07);
        border-color: #cbd5e1;
    }

    .dp-stat-card.active {
        border-color: var(--dp-primary);
        background: #f0fdfa;
    }

    .dp-stat-val {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.1;
    }

    .dp-stat-lbl {
        font-size: 12px;
        font-weight: 600;
        color: var(--dp-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 3px;
    }

    .dp-stat-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .icon-today { background: #ccfbf1; color: #0d9488; }
    .icon-pending { background: #fef3c7; color: #d97706; }
    .icon-multiday { background: #e0e7ff; color: #4338ca; }
    .icon-total { background: #f1f5f9; color: #475569; }

    /* Action bar */
    .dp-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 14px;
        background: #ffffff;
        border: 1px solid var(--dp-border);
        border-radius: 12px;
        padding: 10px 14px;
    }

    .dp-search-wrap {
        position: relative;
        min-width: 260px;
        flex: 1;
    }

    .dp-search-wrap i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .dp-search-input {
        width: 100%;
        height: 36px;
        padding-left: 34px;
        padding-right: 12px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        font-size: 13px;
        outline: none;
        transition: all 0.15s ease;
    }

    .dp-search-input:focus {
        border-color: var(--dp-primary);
        box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15);
    }

    .dp-filter-select {
        height: 36px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        font-size: 12.5px;
        font-weight: 600;
        padding: 0 10px;
        background: #ffffff;
        color: #334155;
        cursor: pointer;
    }

    .dp-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 36px;
        padding: 0 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none !important;
        white-space: nowrap;
    }

    .dp-btn-primary {
        background: var(--dp-primary);
        color: #ffffff;
    }

    .dp-btn-primary:hover {
        background: var(--dp-primary-dark);
        color: #ffffff;
    }

    /* Table styling */
    .dp-card {
        background: #ffffff;
        border: 1px solid var(--dp-border);
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        overflow: hidden;
    }

    .dp-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .dp-table th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 11px 14px;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
    }

    .dp-table td {
        padding: 10px 14px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .dp-table tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Status Pills */
    .dp-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 9px;
        border-radius: 20px;
        font-size: 11.5px;
        font-weight: 700;
    }

    .pill-approved { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .pill-pending { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    .pill-completed { background: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe; }
    .pill-rejected { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }

    /* Category Badges */
    .cat-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 4px;
        display: inline-block;
    }

    .cat-sports { background: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
    .cat-competition { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    .cat-exam { background: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    .cat-workshop { background: #f3e8ff; color: #6b21a8; border: 1px solid #e9d5ff; }
    .cat-tour { background: #fdf2f8; color: #9d174d; border: 1px solid #fbcfe8; }
    .cat-other { background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; }

    .staff-avatar-stack {
        display: flex;
        align-items: center;
        gap: 4px;
        flex-wrap: wrap;
    }

    .staff-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        padding: 2px 7px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 600;
        color: #334155;
    }

    /* ==========================================================================
       MODERN SLIDE-IN RIGHT DRAWER STYLES
       ========================================================================== */
    .modern-drawer-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.5);
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
        right: -580px;
        width: 550px;
        max-width: 92vw;
        height: 100%;
        background: #ffffff;
        z-index: 1050;
        box-shadow: -10px 0 30px -5px rgba(0, 0, 0, 0.18), -8px 0 10px -6px rgba(0, 0, 0, 0.1);
        transition: right 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        display: flex;
        flex-direction: column;
    }

    .modern-drawer-panel.is-open {
        right: 0;
    }

    .modern-drawer-header {
        padding: 16px 22px;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
        color: #ffffff;
    }

    .modern-drawer-title {
        font-size: 16px;
        font-weight: 700;
        color: #ffffff;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .modern-drawer-close {
        background: rgba(255, 255, 255, 0.15);
        border: none;
        font-size: 20px;
        line-height: 1;
        color: #ffffff;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 6px;
        transition: background 0.2s;
    }

    .modern-drawer-close:hover {
        background: rgba(255, 255, 255, 0.3);
    }

    .modern-drawer-body {
        padding: 22px;
        overflow-y: auto;
        flex-grow: 1;
        background: #ffffff;
    }

    .modern-drawer-footer {
        padding: 14px 22px;
        border-top: 1px solid #e2e8f0;
        background: #f8fafc;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 10px;
    }

    /* Select2 Drawer Dropdown Styling */
    .select2-container {
        width: 100% !important;
    }
    .select2-container--open {
        z-index: 99999999 !important;
    }
    .select2-dropdown {
        z-index: 99999999 !important;
        border: 1.5px solid #0d9488 !important;
        border-radius: 8px !important;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2) !important;
        background: #ffffff !important;
    }
    .select2-results__option {
        padding: 8px 12px !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        color: #1e293b !important;
    }
    .select2-results__option--highlighted[aria-selected] {
        background-color: #0d9488 !important;
        color: #ffffff !important;
    }
    .select2-container--default .select2-selection--multiple {
        border-radius: 8px !important;
        border: 1.5px solid #cbd5e1 !important;
        min-height: 40px !important;
        padding: 4px 6px !important;
        background: #ffffff !important;
    }
    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border-color: #0d9488 !important;
        box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.15) !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background: #ccfbf1 !important;
        border: 1px solid #99f6e4 !important;
        color: #0f766e !important;
        border-radius: 6px !important;
        font-weight: 700 !important;
        font-size: 12px !important;
        padding: 3px 8px !important;
        margin: 2px 4px !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: #0f766e !important;
        margin-right: 5px !important;
        font-weight: bold !important;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #dc2626 !important;
    }
    .select2-search--inline .select2-search__field {
        margin-top: 4px !important;
        padding-left: 6px !important;
        font-size: 13px !important;
    }

    .drawer-form-label {
        font-size: 12.5px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 5px;
        display: block;
    }
</style>

<div class="content-wrapper dutypass-page">
    <section class="content">
        <!-- KPI Metrics Grid -->
        <div class="dp-stat-grid">
            <div class="dp-stat-card active" data-kpi="all" onclick="filterByKpi('all')">
                <div>
                    <div class="dp-stat-val" id="kpi-total-val"><?php echo $total_passes; ?></div>
                    <div class="dp-stat-lbl">Total Passes</div>
                </div>
                <div class="dp-stat-icon icon-total"><i class="fa fa-file-text-o"></i></div>
            </div>
            <div class="dp-stat-card" data-kpi="today" onclick="filterByKpi('today')">
                <div>
                    <div class="dp-stat-val" style="color:#0d9488;"><?php echo $today_on_duty; ?></div>
                    <div class="dp-stat-lbl">Staff On Duty Today</div>
                </div>
                <div class="dp-stat-icon icon-today"><i class="fa fa-users"></i></div>
            </div>
            <div class="dp-stat-card" data-kpi="pending" onclick="filterByKpi('pending')">
                <div>
                    <div class="dp-stat-val" style="color:#d97706;"><?php echo $pending_count; ?></div>
                    <div class="dp-stat-lbl">Pending Approval</div>
                </div>
                <div class="dp-stat-icon icon-pending"><i class="fa fa-hourglass-half"></i></div>
            </div>
            <div class="dp-stat-card" data-kpi="multiday" onclick="filterByKpi('multiday')">
                <div>
                    <div class="dp-stat-val" style="color:#4338ca;"><?php echo $multiday_count; ?></div>
                    <div class="dp-stat-lbl">Multi-Day Trips</div>
                </div>
                <div class="dp-stat-icon icon-multiday"><i class="fa fa-road"></i></div>
            </div>
        </div>

        <!-- Filter & Search Toolbar with Prominent Add Pass CTA -->
        <div class="dp-toolbar">
            <div class="dp-search-wrap">
                <i class="fa fa-search"></i>
                <input type="text" id="dp-search" class="dp-search-input" placeholder="Search pass no, event name, venue, staff name...">
            </div>
            <div style="display:inline-flex; align-items:center; gap:8px; flex-wrap:wrap;">
                <select id="filter-category" class="dp-filter-select">
                    <option value="">All Categories</option>
                    <option value="Sports">Sports Tournament</option>
                    <option value="Competition">Inter-School Competition</option>
                    <option value="Exam Duty">Board / Exam Duty</option>
                    <option value="Workshop">Workshop / Training</option>
                    <option value="Educational Tour">Educational Tour</option>
                    <option value="Official Duty">Official / Admin Duty</option>
                </select>
                <select id="filter-status" class="dp-filter-select">
                    <option value="">All Statuses</option>
                    <option value="Approved">Approved</option>
                    <option value="Pending">Pending</option>
                    <option value="Completed">Completed</option>
                    <option value="Rejected">Rejected</option>
                </select>
                <a href="<?php echo site_url('admin/staffattendance'); ?>" class="dp-btn" style="background:#f1f5f9; color:#334155; border:1px solid #cbd5e1;" title="View Staff Attendance">
                    <i class="fa fa-calendar-check-o text-success"></i> Attendance
                </a>
                <?php if ($this->rbac->hasPrivilege('staff_duty_pass', 'can_add')) { ?>
                    <button type="button" id="btn-open-dutypass-drawer" class="dp-btn dp-btn-primary" onclick="openDutyPassDrawer()">
                        <i class="fa fa-plus-circle"></i> Issue Duty Pass
                    </button>
                <?php } ?>
            </div>
        </div>

        <!-- Main Data Table -->
        <div class="dp-card">
            <div class="table-responsive">
                <table class="dp-table" id="dutypass-table">
                    <thead>
                        <tr>
                            <th width="40">#</th>
                            <th width="120">Pass No</th>
                            <th>Duty / Event Name</th>
                            <th>Category</th>
                            <th>Venue / Destination</th>
                            <th width="160">Duty Period</th>
                            <th>Deputed Staff Member(s)</th>
                            <th width="95">Status</th>
                            <th width="110" class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (!empty($duty_passes)) {
                            $cnt = 1;
                            foreach ($duty_passes as $pass) {
                                $from_ts = strtotime($pass['from_date']);
                                $to_ts = strtotime($pass['to_date']);
                                $days_diff = round(($to_ts - $from_ts) / 86400) + 1;
                                $is_multiday = ($days_diff > 1);
                                $is_today_active = ($pass['status'] == 'Approved' && $pass['from_date'] <= $today_date && $pass['to_date'] >= $today_date);
                                
                                $cat_class = 'cat-other';
                                if (stripos($pass['category'], 'sport') !== false) $cat_class = 'cat-sports';
                                elseif (stripos($pass['category'], 'compet') !== false) $cat_class = 'cat-competition';
                                elseif (stripos($pass['category'], 'exam') !== false) $cat_class = 'cat-exam';
                                elseif (stripos($pass['category'], 'work') !== false) $cat_class = 'cat-workshop';
                                elseif (stripos($pass['category'], 'tour') !== false) $cat_class = 'cat-tour';

                                $status_class = 'pill-' . strtolower($pass['status']);
                        ?>
                            <tr data-pass_id="<?php echo $pass['id']; ?>"
                                data-category="<?php echo html_escape($pass['category']); ?>"
                                data-status="<?php echo html_escape($pass['status']); ?>"
                                data-is_today="<?php echo $is_today_active ? '1' : '0'; ?>"
                                data-is_multiday="<?php echo $is_multiday ? '1' : '0'; ?>">
                                <td><?php echo $cnt++; ?></td>
                                <td>
                                    <code style="background:#f0fdfa; color:#0f766e; padding:3px 6px; border-radius:5px; font-weight:700; font-size:12px; border:1px solid #99f6e4;">
                                        <?php echo html_escape($pass['duty_pass_no']); ?>
                                    </code>
                                </td>
                                <td>
                                    <div style="font-weight:700; color:#0f172a;"><?php echo html_escape($pass['title']); ?></div>
                                    <?php if (!empty($pass['transport_details'])) { ?>
                                        <div style="font-size:11px; color:#64748b; margin-top:2px;">
                                            <i class="fa fa-bus text-info"></i> <?php echo html_escape($pass['transport_details']); ?>
                                        </div>
                                    <?php } ?>
                                </td>
                                <td>
                                    <span class="cat-badge <?php echo $cat_class; ?>"><?php echo html_escape($pass['category']); ?></span>
                                </td>
                                <td>
                                    <div style="font-weight:600; color:#334155;"><i class="fa fa-map-marker text-danger"></i> <?php echo html_escape($pass['venue']); ?></div>
                                </td>
                                <td>
                                    <div style="font-weight:700; color:#0f172a; font-size:12px;">
                                        <?php echo date($this->customlib->getSchoolDateFormat(), $from_ts); ?>
                                        <?php if ($is_multiday) { ?>
                                            → <?php echo date($this->customlib->getSchoolDateFormat(), $to_ts); ?>
                                        <?php } ?>
                                    </div>
                                    <div style="margin-top:2px;">
                                        <?php if ($is_multiday) { ?>
                                            <span style="background:#e0e7ff; color:#4338ca; font-size:10px; font-weight:700; padding:1px 6px; border-radius:4px;"><?php echo $days_diff; ?> Days Trip</span>
                                        <?php } else { ?>
                                            <span style="background:#f1f5f9; color:#475569; font-size:10px; font-weight:600; padding:1px 6px; border-radius:4px;">1 Day Duty</span>
                                        <?php } ?>
                                        <?php if ($is_today_active) { ?>
                                            <span style="background:#ccfbf1; color:#0f766e; font-size:10px; font-weight:800; padding:1px 6px; border-radius:4px;">Active Today</span>
                                        <?php } ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="staff-avatar-stack">
                                        <?php foreach ($pass['staff_members'] as $m) { ?>
                                            <span class="staff-chip" title="<?php echo html_escape($m['name'] . ' ' . $m['surname'] . ' (' . $m['role_name'] . ')'); ?>">
                                                <i class="fa fa-user text-teal"></i> <?php echo html_escape($m['name'] . ' ' . $m['surname']); ?>
                                            </span>
                                        <?php } ?>
                                    </div>
                                </td>
                                <td>
                                    <?php if ($pass['status'] == 'Pending' && $this->rbac->hasPrivilege('staff_duty_pass', 'can_edit')) { ?>
                                        <div class="dropdown" style="display:inline-block;">
                                            <button class="dp-status-pill pill-pending dropdown-toggle" type="button" data-toggle="dropdown" style="cursor:pointer; border:1px dashed #d97706;" title="Click to Approve or Reject">
                                                <i class="fa fa-clock-o"></i> Pending <i class="fa fa-caret-down" style="font-size:10px; margin-left:2px;"></i>
                                            </button>
                                            <ul class="dropdown-menu" style="border-radius:8px; box-shadow:0 10px 25px -5px rgba(0,0,0,0.15); min-width:140px;">
                                                <li>
                                                    <a href="javascript:void(0);" onclick="updateDutyPassStatus(<?php echo $pass['id']; ?>, 'Approved')">
                                                        <i class="fa fa-check text-success"></i> <strong>Approve Pass</strong>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0);" onclick="updateDutyPassStatus(<?php echo $pass['id']; ?>, 'Rejected')">
                                                        <i class="fa fa-times text-danger"></i> Reject Pass
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    <?php } else { ?>
                                        <span class="dp-status-pill <?php echo $status_class; ?>">
                                            <?php echo html_escape($pass['status']); ?>
                                        </span>
                                    <?php } ?>
                                </td>
                                <td class="text-right" style="white-space:nowrap;">
                                    <?php if ($pass['status'] == 'Pending' && $this->rbac->hasPrivilege('staff_duty_pass', 'can_edit')) { ?>
                                        <button type="button" class="btn btn-success btn-xs" onclick="updateDutyPassStatus(<?php echo $pass['id']; ?>, 'Approved')" style="border-radius:6px; font-weight:700; padding:4px 9px; margin-right:4px;" title="Approve Field Duty Pass">
                                            <i class="fa fa-check"></i> Approve
                                        </button>
                                    <?php } ?>
                                    <a href="<?php echo base_url('admin/dutypass/print_dutypass/' . $pass['id']); ?>" target="_blank" class="btn btn-default btn-xs" style="border-radius:6px; padding:4px 8px; margin-right:4px;" title="Print Duty Slip">
                                        <i class="fa fa-print text-primary"></i>
                                    </a>
                                    <div class="dropdown" style="display:inline-block;">
                                        <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" style="border-radius:6px; padding:4px 8px;">
                                            <i class="fa fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-right" style="border-radius:8px; box-shadow:0 10px 25px -5px rgba(0,0,0,0.15);">
                                            <li>
                                                <a href="<?php echo base_url('admin/dutypass/print_dutypass/' . $pass['id']); ?>" target="_blank">
                                                    <i class="fa fa-print text-primary"></i> Print Duty Slip
                                                </a>
                                            </li>
                                            <?php if ($this->rbac->hasPrivilege('staff_duty_pass', 'can_edit')) { ?>
                                                <li>
                                                    <a href="javascript:void(0);" onclick="openEditDutyPassDrawer(<?php echo $pass['id']; ?>)">
                                                        <i class="fa fa-pencil text-warning"></i> Edit Pass
                                                    </a>
                                                </li>
                                                <?php if ($pass['status'] == 'Pending') { ?>
                                                    <li>
                                                        <a href="javascript:void(0);" onclick="updateDutyPassStatus(<?php echo $pass['id']; ?>, 'Approved')">
                                                            <i class="fa fa-check text-success"></i> Approve Pass
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0);" onclick="updateDutyPassStatus(<?php echo $pass['id']; ?>, 'Rejected')">
                                                            <i class="fa fa-times text-danger"></i> Reject Pass
                                                        </a>
                                                    </li>
                                                <?php } elseif ($pass['status'] == 'Approved') { ?>
                                                    <li>
                                                        <a href="javascript:void(0);" onclick="updateDutyPassStatus(<?php echo $pass['id']; ?>, 'Completed')">
                                                            <i class="fa fa-flag-checkered text-info"></i> Mark Completed
                                                        </a>
                                                    </li>
                                                <?php } ?>
                                            <?php } ?>
                                            <?php if ($this->rbac->hasPrivilege('staff_duty_pass', 'can_delete')) { ?>
                                                <li class="divider"></li>
                                                <li>
                                                    <a href="javascript:void(0);" onclick="deleteDutyPass(<?php echo $pass['id']; ?>)" style="color:#dc2626;">
                                                        <i class="fa fa-trash"></i> Delete
                                                    </a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php 
                            }
                        } else {
                        ?>
                            <tr>
                                <td colspan="9" class="text-center" style="padding:40px; color:#94a3b8;">
                                    <i class="fa fa-id-badge" style="font-size:42px; margin-bottom:12px; display:block; color:#cbd5e1;"></i>
                                    <strong style="font-size:15px; color:#475569;">No Field Duty Passes Found</strong>
                                    <div style="font-size:12.5px; margin-top:6px; color:#64748b;">Issue a pass to staff when they are deputed for sports, competitions, or official duties.</div>
                                    <?php if ($this->rbac->hasPrivilege('staff_duty_pass', 'can_add')) { ?>
                                        <div style="margin-top:14px;">
                                            <button type="button" class="dp-btn dp-btn-primary" onclick="openDutyPassDrawer()">
                                                <i class="fa fa-plus"></i> Issue Duty Pass
                                            </button>
                                        </div>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!-- ==========================================================================
     SLIDE-IN RIGHT DRAWER (OFFCANVAS) FOR ISSUING / EDITING DUTY PASS
     ========================================================================== -->
<div id="dutypass-drawer-overlay" class="modern-drawer-overlay"></div>
<div id="dutypass-drawer-panel" class="modern-drawer-panel">
    <form id="dutyPassForm" method="post" enctype="multipart/form-data" style="display:flex; flex-direction:column; height:100%; margin:0;">
        <input type="hidden" name="id" id="dp_id" value="">
        <input type="hidden" name="save_and_print" id="dp_save_and_print" value="0">

        <!-- Drawer Header -->
        <div class="modern-drawer-header">
            <h4 class="modern-drawer-title" id="dutyPassDrawerTitle">
                <i class="fa fa-id-badge"></i> Issue Staff Field Duty Pass
            </h4>
            <button type="button" class="modern-drawer-close" id="btn-close-dutypass-drawer" onclick="closeDutyPassDrawer()">&times;</button>
        </div>

        <!-- Drawer Body -->
        <div class="modern-drawer-body">
            <div class="form-group">
                <label class="drawer-form-label">Duty Title / Event Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="title" id="dp_title" placeholder="e.g. State Athletic Meet 2026 / Board Exam Invigilation" required style="border-radius:8px;">
                <span class="text-danger error-title"></span>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="drawer-form-label">Category / Purpose <span class="text-danger">*</span></label>
                        <select class="form-control" name="category" id="dp_category" style="border-radius:8px;" required>
                            <option value="Sports">Sports Tournament / Meet</option>
                            <option value="Competition">Inter-School Competition</option>
                            <option value="Exam Duty">Board / External Exam Duty</option>
                            <option value="Workshop">Seminar / Workshop / Training</option>
                            <option value="Educational Tour">Educational Tour / Excursion</option>
                            <option value="Official Duty">Official / Administrative Duty</option>
                        </select>
                        <span class="text-danger error-category"></span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="drawer-form-label">Status</label>
                        <select class="form-control" name="status" id="dp_status" style="border-radius:8px;">
                            <option value="Approved">Approved (Auto-mark OD)</option>
                            <option value="Pending">Pending Approval</option>
                            <option value="Completed">Completed</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="drawer-form-label">Deputed Staff Member(s) <span class="text-danger">*</span></label>
                <select class="form-control select2" name="staff_ids[]" id="dp_staff_ids" multiple="multiple" style="width:100%;" required>
                    <?php foreach ($staff_list as $st) { ?>
                        <option value="<?php echo $st['id']; ?>">
                            <?php echo html_escape($st['name'] . ' ' . $st['surname'] . ' [' . $st['employee_id'] . ']'); ?>
                        </option>
                    <?php } ?>
                </select>
                <span class="text-danger error-staff_ids"></span>
                <small class="text-muted" style="font-size:11px;">You can select one or multiple teachers/staff members assigned to this duty.</small>
            </div>

            <div class="form-group">
                <label class="drawer-form-label">Venue / Destination Location <span class="text-danger">*</span></label>
                <input type="text" class="form-control" name="venue" id="dp_venue" placeholder="e.g. SMS Stadium, Jaipur / DPS Ground, Jodhpur" required style="border-radius:8px;">
                <span class="text-danger error-venue"></span>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="drawer-form-label">From Date <span class="text-danger">*</span></label>
                        <input type="text" class="form-control date" name="from_date" id="dp_from_date" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" readonly style="background:#fff; cursor:pointer; border-radius:8px;" required>
                        <span class="text-danger error-from_date"></span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="drawer-form-label">To Date <span class="text-danger">*</span></label>
                        <input type="text" class="form-control date" name="to_date" id="dp_to_date" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" readonly style="background:#fff; cursor:pointer; border-radius:8px;" required>
                        <span class="text-danger error-to_date"></span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="drawer-form-label">Departure Time (Optional)</label>
                        <input type="text" class="form-control time" name="departure_time" id="dp_departure_time" placeholder="HH:MM AM/PM" style="border-radius:8px;">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="drawer-form-label">Expected Return Time (Optional)</label>
                        <input type="text" class="form-control time" name="return_time" id="dp_return_time" placeholder="HH:MM AM/PM" style="border-radius:8px;">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="drawer-form-label">Transport Details (Optional)</label>
                <input type="text" class="form-control" name="transport_details" id="dp_transport_details" placeholder="e.g. School Bus No. 4 / Train No 12987 / Self" style="border-radius:8px;">
            </div>

            <div class="form-group">
                <label class="drawer-form-label">Accompanying Students / Team Info (Optional)</label>
                <textarea class="form-control" name="student_details" id="dp_student_details" rows="2" placeholder="e.g. 15 Students from Under-17 Football Team" style="border-radius:8px;"></textarea>
            </div>

            <div class="form-group">
                <label class="drawer-form-label">Special Instructions / Remarks (Optional)</label>
                <textarea class="form-control" name="description" id="dp_description" rows="2" placeholder="Any special guidelines, reporting rules, or allowances..." style="border-radius:8px;"></textarea>
            </div>

            <div class="form-group">
                <label class="drawer-form-label">Approval Status</label>
                <select name="status" id="dp_status" class="form-control" style="border-radius:8px; font-weight:600;">
                    <option value="Approved" selected>Approved (Active immediately)</option>
                    <option value="Pending">Pending Approval</option>
                    <option value="Completed">Completed</option>
                    <option value="Rejected">Rejected</option>
                </select>
                <small class="text-muted" style="font-size:11px;">Passes marked as "Approved" automatically link to staff attendance records.</small>
            </div>

            <div class="form-group">
                <label class="drawer-form-label">Duty Circular / Order Attachment (Optional)</label>
                <input type="file" name="document" id="dp_document" class="form-control" style="border-radius:8px;">
            </div>
        </div>

        <!-- Drawer Footer -->
        <div class="modern-drawer-footer">
            <button type="button" class="btn btn-default" onclick="closeDutyPassDrawer()" style="border-radius:8px; font-weight:600;">Cancel</button>
            <button type="submit" id="dp_submit_btn" class="dp-btn dp-btn-primary" onclick="$('#dp_save_and_print').val('0');">
                <i class="fa fa-save"></i> Save Pass
            </button>
            <button type="submit" id="dp_save_print_btn" class="dp-btn" style="background:#0f766e; color:#fff;" onclick="$('#dp_save_and_print').val('1');">
                <i class="fa fa-print"></i> Save & Print
            </button>
        </div>
    </form>
</div>

<script type="text/javascript">
function initStaffSelect2() {
    if ($.fn.select2) {
        try {
            if ($('#dp_staff_ids').hasClass('select2-hidden-accessible')) {
                $('#dp_staff_ids').select2('destroy');
            }
        } catch(e) {}
        
        $('#dp_staff_ids').select2({
            dropdownParent: $('#dutypass-drawer-panel'),
            width: '100%',
            placeholder: "Search and select staff member(s)...",
            allowClear: true,
            closeOnSelect: false
        });
    }
}

$(document).ready(function() {
    initStaffSelect2();

    // Close on overlay click or Esc
    $('#dutypass-drawer-overlay').on('click', function() {
        closeDutyPassDrawer();
    });

    $(document).keyup(function(e) {
        if (e.key === "Escape") {
            closeDutyPassDrawer();
        }
    });

    // Search and Filters
    $('#dp-search, #filter-category, #filter-status').on('input change', function() {
        applyDutyPassFilters();
    });

    // Form submit AJAX
    $('#dutyPassForm').on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        var id = $('#dp_id').val();
        var url = id ? '<?php echo site_url("admin/dutypass/update"); ?>' : '<?php echo site_url("admin/dutypass/create"); ?>';
        var saveAndPrint = $('#dp_save_and_print').val() == '1';

        $('#dp_submit_btn, #dp_save_print_btn').prop('disabled', true);
        $('.text-danger[class*="error-"]').text('');

        $.ajax({
            url: url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                $('#dp_submit_btn, #dp_save_print_btn').prop('disabled', false);
                if (response.status === 'success') {
                    closeDutyPassDrawer();
                    successMsg(response.message);
                    
                    if (saveAndPrint && response.id) {
                        window.open('<?php echo base_url("admin/dutypass/print_dutypass/"); ?>' + response.id, '_blank');
                    }

                    setTimeout(function() {
                        location.reload();
                    }, 800);
                } else {
                    if (response.error) {
                        $.each(response.error, function(key, val) {
                            $('.error-' + key).text(val);
                        });
                    }
                    if (response.message) {
                        errorMsg(response.message);
                    }
                }
            },
            error: function() {
                $('#dp_submit_btn, #dp_save_print_btn').prop('disabled', false);
                errorMsg('Something went wrong. Please try again.');
            }
        });
    });
});

function openDutyPassDrawer() {
    $('#dutyPassForm')[0].reset();
    $('#dp_id').val('');
    $('#dp_save_and_print').val('0');
    $('#dp_staff_ids').val(null).trigger('change');
    $('#dp_status').val('Approved');
    $('#dutyPassDrawerTitle').html('<i class="fa fa-id-badge"></i> Issue Staff Field Duty Pass');
    $('.text-danger[class*="error-"]').text('');

    $('#dutypass-drawer-overlay').addClass('is-active');
    $('#dutypass-drawer-panel').addClass('is-open');
    $('body').css('overflow', 'hidden');

    setTimeout(function() {
        initStaffSelect2();
        $('#dp_title').focus();
    }, 150);
}

function closeDutyPassDrawer() {
    $('#dutypass-drawer-panel').removeClass('is-open');
    $('#dutypass-drawer-overlay').removeClass('is-active');
    $('body').css('overflow', '');
}

function openEditDutyPassDrawer(id) {
    $('.text-danger[class*="error-"]').text('');
    $.ajax({
        url: '<?php echo site_url("admin/dutypass/get_details/"); ?>' + id,
        type: 'GET',
        dataType: 'json',
        success: function(res) {
            if (res.status === 'success') {
                var d = res.data;
                $('#dp_id').val(d.id);
                $('#dp_save_and_print').val('0');
                $('#dp_title').val(d.title);
                $('#dp_category').val(d.category);
                $('#dp_venue').val(d.venue);
                $('#dp_from_date').val(d.from_date_formatted);
                $('#dp_to_date').val(d.to_date_formatted);
                $('#dp_departure_time').val(d.departure_time);
                $('#dp_return_time').val(d.return_time);
                $('#dp_transport_details').val(d.transport_details);
                $('#dp_student_details').val(d.student_details);
                $('#dp_description').val(d.description);
                $('#dp_status').val(d.status);

                var staff_ids = [];
                if (d.staff_members) {
                    $.each(d.staff_members, function(i, st) {
                        staff_ids.push(st.staff_id);
                    });
                }

                $('#dutyPassDrawerTitle').html('<i class="fa fa-pencil"></i> Edit Duty Pass: ' + d.duty_pass_no);
                
                $('#dutypass-drawer-overlay').addClass('is-active');
                $('#dutypass-drawer-panel').addClass('is-open');
                $('body').css('overflow', 'hidden');

                setTimeout(function() {
                    initStaffSelect2();
                    $('#dp_staff_ids').val(staff_ids).trigger('change');
                }, 150);
            } else {
                errorMsg(res.message);
            }
        }
    });
}

function updateDutyPassStatus(id, status) {
    if (confirm('Are you sure you want to change pass status to ' + status + '?')) {
        $.ajax({
            url: '<?php echo site_url("admin/dutypass/update_status"); ?>',
            type: 'POST',
            data: { id: id, status: status },
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    successMsg(res.message);
                    setTimeout(function() {
                        location.reload();
                    }, 600);
                } else {
                    errorMsg(res.message);
                }
            }
        });
    }
}

function deleteDutyPass(id) {
    if (confirm('Are you sure you want to delete this duty pass?')) {
        $.ajax({
            url: '<?php echo site_url("admin/dutypass/delete/"); ?>' + id,
            type: 'POST',
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    successMsg(res.message);
                    setTimeout(function() {
                        location.reload();
                    }, 600);
                } else {
                    errorMsg(res.message);
                }
            }
        });
    }
}

function filterByKpi(kpi) {
    $('.dp-stat-card').removeClass('active');
    $('.dp-stat-card[data-kpi="' + kpi + '"]').addClass('active');

    if (kpi === 'all') {
        $('#filter-status').val('');
    } else if (kpi === 'pending') {
        $('#filter-status').val('Pending');
    }
    applyDutyPassFilters(kpi);
}

function applyDutyPassFilters(forceKpi) {
    var query = ($('#dp-search').val() || '').toLowerCase().trim();
    var category = $('#filter-category').val();
    var status = $('#filter-status').val();
    var activeKpi = forceKpi || $('.dp-stat-card.active').data('kpi') || 'all';

    $('#dutypass-table tbody tr').each(function() {
        var $tr = $(this);
        var text = $tr.text().toLowerCase();
        var rowCat = $tr.data('category');
        var rowStatus = $tr.data('status');
        var isToday = $tr.data('is_today') == 1;
        var isMultiday = $tr.data('is_multiday') == 1;

        var matchesSearch = !query || text.indexOf(query) !== -1;
        var matchesCat = !category || rowCat === category;
        var matchesStatus = !status || rowStatus === status;

        var matchesKpi = true;
        if (activeKpi === 'today') {
            matchesKpi = isToday;
        } else if (activeKpi === 'pending') {
            matchesKpi = (rowStatus === 'Pending');
        } else if (activeKpi === 'multiday') {
            matchesKpi = isMultiday;
        }

        if (matchesSearch && matchesCat && matchesStatus && matchesKpi) {
            $tr.show();
        } else {
            $tr.hide();
        }
    });
}
</script>
