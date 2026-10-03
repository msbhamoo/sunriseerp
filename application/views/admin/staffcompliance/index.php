<style type="text/css">
    :root {
        --cmp-primary: #0f766e;
        --cmp-primary-dark: #115e59;
        --cmp-primary-light: #f0fdfa;
        --cmp-border: #e2e8f0;
        --cmp-text: #0f172a;
        --cmp-muted: #64748b;
        --cmp-green-bg: #dcfce7;
        --cmp-green-text: #15803d;
        --cmp-green-border: #bbf7d0;
        --cmp-red-bg: #fee2e2;
        --cmp-red-text: #b91c1c;
        --cmp-red-border: #fecaca;
    }

    .compliance-page {
        font-family: inherit;
        color: var(--cmp-text);
    }

    /* Top Action Bar */
    .cmp-top-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #ffffff;
        border: 1px solid var(--cmp-border);
        border-radius: 12px;
        padding: 12px 18px;
        margin-bottom: 14px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        flex-wrap: wrap;
        gap: 12px;
    }

    .cmp-page-title {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .cmp-page-title h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 800;
        color: var(--cmp-text);
    }

    .cmp-page-title small {
        color: var(--cmp-muted);
        font-size: 12px;
    }

    /* KPI Summary Bar */
    .cmp-stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 12px;
        margin-bottom: 14px;
    }

    .cmp-stat-card {
        background: #ffffff;
        border: 1px solid var(--cmp-border);
        border-radius: 10px;
        padding: 12px 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 1px 2px rgba(0,0,0,0.03);
    }

    .cmp-stat-val {
        font-size: 22px;
        font-weight: 800;
        color: var(--cmp-text);
        line-height: 1.1;
    }

    .cmp-stat-lbl {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: var(--cmp-muted);
        letter-spacing: 0.5px;
        margin-top: 3px;
    }

    .cmp-stat-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    /* Search & Filter Card */
    .cmp-filter-card {
        background: #ffffff;
        border: 1px solid var(--cmp-border);
        border-radius: 12px;
        padding: 14px 18px;
        margin-bottom: 14px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
    }

    .cmp-filter-form {
        display: flex;
        align-items: flex-end;
        gap: 12px;
        flex-wrap: wrap;
    }

    .cmp-form-group {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .cmp-form-group label {
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        margin-bottom: 0;
    }

    .cmp-date-wrap {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .cmp-date-nav-btn {
        height: 36px;
        width: 34px;
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        color: #475569;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.15s ease;
    }

    .cmp-date-nav-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    /* Buttons */
    .cmp-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        height: 36px;
        padding: 0 16px;
        font-size: 12.5px;
        font-weight: 600;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        background: #ffffff;
        color: #334155;
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none !important;
    }

    .cmp-btn:hover {
        background: #f8fafc;
        color: #0f172a;
        border-color: #94a3b8;
    }

    .cmp-btn-primary {
        background: var(--cmp-primary);
        border-color: var(--cmp-primary);
        color: #ffffff !important;
    }

    .cmp-btn-primary:hover {
        background: var(--cmp-primary-dark);
        border-color: var(--cmp-primary-dark);
        color: #ffffff !important;
    }

    /* Table Toolbar */
    .cmp-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 12px;
    }

    .cmp-search-box {
        position: relative;
        min-width: 260px;
        flex: 1;
        max-width: 380px;
    }

    .cmp-search-box i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .cmp-search-input {
        width: 100%;
        height: 36px;
        padding-left: 34px;
        padding-right: 12px;
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        font-size: 12.5px;
        outline: none;
        background: #ffffff;
        transition: all 0.15s ease;
    }

    .cmp-search-input:focus {
        border-color: var(--cmp-primary);
        box-shadow: 0 0 0 2px rgba(15, 118, 110, 0.12);
    }

    /* Table Card */
    .cmp-table-card {
        background: #ffffff;
        border: 1px solid var(--cmp-border);
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        overflow: hidden;
    }

    .cmp-table-container {
        overflow-x: auto;
    }

    .cmp-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12.5px;
    }

    .cmp-table thead th {
        background: #f8fafc;
        color: #475569;
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 10px 12px;
        border-bottom: 2px solid #e2e8f0;
        vertical-align: middle;
    }

    .cmp-table tbody td {
        padding: 8px 12px;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
        color: #334155;
    }

    .cmp-table tbody tr:hover {
        background-color: #f8fafc;
    }

    /* Compliance Chips / Badges */
    .cmp-chip-wrap {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .cmp-toggle-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 700;
        cursor: pointer;
        user-select: none;
        margin: 0;
        border: 1px solid transparent;
        transition: all 0.15s ease;
    }

    .cmp-toggle-chip input[type="checkbox"] {
        display: none;
    }

    .cmp-toggle-chip.is-yes {
        background: var(--cmp-green-bg);
        color: var(--cmp-green-text);
        border-color: var(--cmp-green-border);
    }

    .cmp-toggle-chip.is-yes:hover {
        background: #bbf7d0;
    }

    .cmp-toggle-chip.is-no {
        background: var(--cmp-red-bg);
        color: var(--cmp-red-text);
        border-color: var(--cmp-red-border);
    }

    .cmp-toggle-chip.is-no:hover {
        background: #fecaca;
    }

    /* Header Bulk Pills */
    .cmp-setall-pill {
        padding: 2px 7px;
        border-radius: 4px;
        font-size: 10.5px;
        font-weight: 700;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        cursor: pointer;
        transition: all 0.1s ease;
        display: inline-flex;
        align-items: center;
        gap: 3px;
    }

    .cmp-setall-pill:hover {
        background: #0f172a;
        color: #ffffff !important;
        border-color: #0f172a;
    }
</style>

<div class="content-wrapper compliance-page">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Top Navigation & Action Bar -->
                <div class="cmp-top-header">
                    <div class="cmp-page-title">
                        <div style="background: rgba(15, 118, 110, 0.12); color: var(--cmp-primary); width: 42px; height: 42px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                            <i class="fa fa-check-square-o"></i>
                        </div>
                        <div>
                            <h3><?php echo $this->lang->line('compliance_checklist'); ?></h3>
                            <small>Daily verification of Uniform, ID Card, Lesson Plan & Phone Handover</small>
                        </div>
                    </div>

                    <div style="display:flex; align-items:center; gap:8px;">
                        <a href="<?php echo site_url('admin/staffattendance'); ?>" class="cmp-btn" title="Go to Staff Attendance">
                            <i class="fa fa-calendar-check-o text-teal"></i> Staff Attendance
                        </a>
                        <a href="<?php echo site_url('admin/dutypass'); ?>" class="cmp-btn" title="View Duty Passes">
                            <i class="fa fa-id-badge text-muted"></i> Field Duty Pass
                        </a>
                    </div>
                </div>

                <!-- Criteria Filter Card -->
                <div class="cmp-filter-card">
                    <form id="filter_form" action="<?php echo site_url('admin/staffcompliance/index') ?>" method="post" accept-charset="utf-8">
                        <?php
                        if ($this->session->flashdata('msg')) {
                            echo $this->session->flashdata('msg');
                            $this->session->unset_userdata('msg');
                        }
                        ?>
                        <?php echo $this->customlib->getCSRF(); ?>
                        <div class="cmp-filter-form">
                            <div class="cmp-form-group" style="flex: 1.5; min-width: 200px;">
                                <label for="class_id"><?php echo $this->lang->line('role'); ?></label>
                                <select autofocus="" id="class_id" name="user_id" class="form-control" style="height:36px; border-radius:8px; border-color:#cbd5e1; font-weight:600;">
                                    <option value="select"><?php echo $this->lang->line('select'); ?> All Roles</option>
                                    <?php
                                    foreach ($classlist as $key => $class) {
                                    ?>
                                        <option value="<?php echo $class["type"] ?>" <?php
                                            if (isset($user_type_id) && $class["type"] == $user_type_id) {
                                                echo "selected =selected";
                                            }
                                            ?>><?php echo html_escape($class["type"]); ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="cmp-form-group" style="flex: 1.5; min-width: 220px;">
                                <label for="date_field"><?php echo $this->lang->line('date'); ?></label>
                                <div class="cmp-date-wrap">
                                    <button type="button" class="cmp-date-nav-btn" id="btn-prev-day" title="Previous Day"><i class="fa fa-chevron-left"></i></button>
                                    <input id="date_field" name="date" type="text" class="form-control date" value="<?php echo set_value('date', $date); ?>" readonly="readonly" style="height:36px; border-radius:8px; border-color:#cbd5e1; background:#ffffff; font-weight:600; cursor:pointer;" />
                                    <button type="button" class="cmp-date-nav-btn" id="btn-next-day" title="Next Day"><i class="fa fa-chevron-right"></i></button>
                                </div>
                            </div>

                            <div style="display:flex; gap:8px;">
                                <button type="submit" name="search" value="search" class="cmp-btn cmp-btn-primary" style="height:36px; padding:0 22px;">
                                    <i class="fa fa-search"></i> Search Staff
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <?php if (isset($resultlist)) { 
                    $total_staff = count($resultlist);
                    $full_compliant_count = 0;
                    $uniform_count = 0;
                    $id_card_count = 0;
                    $lesson_plan_count = 0;
                    $phone_count = 0;

                    foreach ($resultlist as $r) {
                        $u = ($r['uniform_status'] === 'yes');
                        $i = ($r['id_card_status'] === 'yes');
                        $l = ($r['lesson_plan_status'] === 'yes');
                        $p = ($r['phone_handover_status'] === 'yes');

                        if ($u) $uniform_count++;
                        if ($i) $id_card_count++;
                        if ($l) $lesson_plan_count++;
                        if ($p) $phone_count++;
                        if ($u && $i && $l && $p) $full_compliant_count++;
                    }
                ?>
                    <!-- KPI Summary Cards -->
                    <div class="cmp-stat-grid">
                        <div class="cmp-stat-card">
                            <div>
                                <div class="cmp-stat-val"><?php echo $total_staff; ?></div>
                                <div class="cmp-stat-lbl">Total Staff</div>
                            </div>
                            <div class="cmp-stat-icon" style="background:#f1f5f9; color:#475569;">
                                <i class="fa fa-users"></i>
                            </div>
                        </div>

                        <div class="cmp-stat-card">
                            <div>
                                <div class="cmp-stat-val" style="color:#15803d;"><?php echo $full_compliant_count; ?></div>
                                <div class="cmp-stat-lbl">100% Compliant</div>
                            </div>
                            <div class="cmp-stat-icon" style="background:#dcfce7; color:#15803d;">
                                <i class="fa fa-check-circle"></i>
                            </div>
                        </div>

                        <div class="cmp-stat-card">
                            <div>
                                <div class="cmp-stat-val" style="color:#0284c7;"><?php echo $uniform_count; ?></div>
                                <div class="cmp-stat-lbl">Uniform (<?php echo $total_staff > 0 ? round(($uniform_count/$total_staff)*100) : 0; ?>%)</div>
                            </div>
                            <div class="cmp-stat-icon" style="background:#e0f2fe; color:#0284c7;">
                                <i class="fa fa-user"></i>
                            </div>
                        </div>

                        <div class="cmp-stat-card">
                            <div>
                                <div class="cmp-stat-val" style="color:#7c3aed;"><?php echo $id_card_count; ?></div>
                                <div class="cmp-stat-lbl">ID Card (<?php echo $total_staff > 0 ? round(($id_card_count/$total_staff)*100) : 0; ?>%)</div>
                            </div>
                            <div class="cmp-stat-icon" style="background:#ede9fe; color:#7c3aed;">
                                <i class="fa fa-id-card-o"></i>
                            </div>
                        </div>

                        <div class="cmp-stat-card">
                            <div>
                                <div class="cmp-stat-val" style="color:#d97706;"><?php echo $lesson_plan_count; ?></div>
                                <div class="cmp-stat-lbl">Lesson Plan (<?php echo $total_staff > 0 ? round(($lesson_plan_count/$total_staff)*100) : 0; ?>%)</div>
                            </div>
                            <div class="cmp-stat-icon" style="background:#fef3c7; color:#d97706;">
                                <i class="fa fa-book"></i>
                            </div>
                        </div>

                        <div class="cmp-stat-card">
                            <div>
                                <div class="cmp-stat-val" style="color:#0f766e;"><?php echo $phone_count; ?></div>
                                <div class="cmp-stat-lbl">Phone Handover (<?php echo $total_staff > 0 ? round(($phone_count/$total_staff)*100) : 0; ?>%)</div>
                            </div>
                            <div class="cmp-stat-icon" style="background:#ccfbf1; color:#0f766e;">
                                <i class="fa fa-mobile-phone" style="font-size:24px;"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Main Form & Table -->
                    <form action="<?php echo site_url('admin/staffcompliance/index'); ?>" id="save_compliance_form" method="post">
                        <?php echo $this->customlib->getCSRF(); ?>
                        <input type="hidden" name="user_id" value="<?php echo html_escape($user_type_id); ?>">
                        <input type="hidden" name="date" value="<?php echo html_escape($date); ?>">

                        <!-- Action Toolbar -->
                        <div class="cmp-toolbar">
                            <div class="cmp-search-box">
                                <i class="fa fa-search"></i>
                                <input type="text" id="compliance-search" class="cmp-search-input" placeholder="Search staff name, ID, role...">
                            </div>

                            <div style="display:flex; align-items:center; gap:8px;">
                                <span style="font-size:12px; font-weight:700; color:#64748b; margin-right:4px;">Bulk Set:</span>
                                <button type="button" class="cmp-setall-pill bulk-set-global" data-val="true" style="color:#16a34a;" title="Mark all items Yes">
                                    <i class="fa fa-check"></i> All Yes
                                </button>
                                <button type="button" class="cmp-setall-pill bulk-set-global" data-val="false" style="color:#dc2626;" title="Mark all items No">
                                    <i class="fa fa-times"></i> All No
                                </button>

                                <?php if (($this->rbac->hasPrivilege('staff_compliance', 'can_add')) || ($this->rbac->hasPrivilege('staff_compliance', 'can_edit'))) { ?>
                                    <button type="submit" name="search" value="savecompliance" class="cmp-btn cmp-btn-primary" style="margin-left:8px; font-weight:700;">
                                        <i class="fa fa-save"></i> Save All Changes
                                    </button>
                                <?php } ?>
                            </div>
                        </div>

                        <!-- Data Table Card -->
                        <div class="cmp-table-card">
                            <div class="cmp-table-container">
                                <table class="cmp-table" id="compliance-table">
                                    <thead>
                                        <tr>
                                            <th width="40">#</th>
                                            <th width="100"><?php echo $this->lang->line('staff_id'); ?></th>
                                            <th><?php echo $this->lang->line('name'); ?></th>
                                            <th><?php echo $this->lang->line('role'); ?></th>
                                            <th width="130">Attendance</th>

                                            <!-- Uniform Column -->
                                            <th style="min-width: 130px;">
                                                <div style="display:flex; justify-content:space-between; align-items:center;">
                                                    <span><i class="fa fa-user"></i> Uniform</span>
                                                    <div>
                                                        <button type="button" class="cmp-setall-pill bulk-col-btn" data-target="uniform-check" data-val="true" title="All Uniform Yes" style="color:#16a34a; padding:1px 4px; font-size:9.5px;">✓</button>
                                                        <button type="button" class="cmp-setall-pill bulk-col-btn" data-target="uniform-check" data-val="false" title="All Uniform No" style="color:#dc2626; padding:1px 4px; font-size:9.5px;">✗</button>
                                                    </div>
                                                </div>
                                            </th>

                                            <!-- ID Card Column -->
                                            <th style="min-width: 130px;">
                                                <div style="display:flex; justify-content:space-between; align-items:center;">
                                                    <span><i class="fa fa-id-card-o"></i> ID Card</span>
                                                    <div>
                                                        <button type="button" class="cmp-setall-pill bulk-col-btn" data-target="idcard-check" data-val="true" title="All ID Card Yes" style="color:#16a34a; padding:1px 4px; font-size:9.5px;">✓</button>
                                                        <button type="button" class="cmp-setall-pill bulk-col-btn" data-target="idcard-check" data-val="false" title="All ID Card No" style="color:#dc2626; padding:1px 4px; font-size:9.5px;">✗</button>
                                                    </div>
                                                </div>
                                            </th>

                                            <!-- Lesson Plan Column -->
                                            <th style="min-width: 140px;">
                                                <div style="display:flex; justify-content:space-between; align-items:center;">
                                                    <span><i class="fa fa-book"></i> Lesson Plan</span>
                                                    <div>
                                                        <button type="button" class="cmp-setall-pill bulk-col-btn" data-target="lessonplan-check" data-val="true" title="All Lesson Plan Yes" style="color:#16a34a; padding:1px 4px; font-size:9.5px;">✓</button>
                                                        <button type="button" class="cmp-setall-pill bulk-col-btn" data-target="lessonplan-check" data-val="false" title="All Lesson Plan No" style="color:#dc2626; padding:1px 4px; font-size:9.5px;">✗</button>
                                                    </div>
                                                </div>
                                            </th>

                                            <!-- Phone Handover Column -->
                                            <th style="min-width: 140px;">
                                                <div style="display:flex; justify-content:space-between; align-items:center;">
                                                    <span><i class="fa fa-mobile-phone" style="font-size:16px;"></i> Phone Handover</span>
                                                    <div>
                                                        <button type="button" class="cmp-setall-pill bulk-col-btn" data-target="phone-check" data-val="true" title="All Phone Handover Yes" style="color:#16a34a; padding:1px 4px; font-size:9.5px;">✓</button>
                                                        <button type="button" class="cmp-setall-pill bulk-col-btn" data-target="phone-check" data-val="false" title="All Phone Handover No" style="color:#dc2626; padding:1px 4px; font-size:9.5px;">✗</button>
                                                    </div>
                                                </div>
                                            </th>

                                            <th style="min-width: 180px;">Remarks / Note</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if (empty($resultlist)) {
                                        ?>
                                            <tr>
                                                <td colspan="10" class="text-center text-muted" style="padding: 30px;">
                                                    <i class="fa fa-info-circle"></i> No staff found for the selected criteria.
                                                </td>
                                            </tr>
                                        <?php
                                        } else {
                                            $row_i = 1;
                                            foreach ($resultlist as $val) {
                                                $sid    = $val['staff_id'];
                                                $u_val  = isset($val['uniform_status']) ? $val['uniform_status'] : null;
                                                $id_val = isset($val['id_card_status']) ? $val['id_card_status'] : null;
                                                $lp_val = isset($val['lesson_plan_status']) ? $val['lesson_plan_status'] : null;
                                                $ph_val = isset($val['phone_handover_status']) ? $val['phone_handover_status'] : null;

                                                // Attendance status badge
                                                $att_text = !empty($val['att_type']) ? $val['att_type'] : 'Unmarked';
                                                $att_bg   = '#f1f5f9';
                                                $att_fg   = '#475569';
                                                if ($val['att_key'] === 'present') { $att_bg = '#dcfce7'; $att_fg = '#15803d'; }
                                                elseif ($val['att_key'] === 'late') { $att_bg = '#fef3c7'; $att_fg = '#b45309'; }
                                                elseif ($val['att_key'] === 'absent') { $att_bg = '#fee2e2'; $att_fg = '#b91c1c'; }
                                                elseif ($val['att_key'] === 'half_day') { $att_bg = '#dbeafe'; $att_fg = '#1d4ed8'; }
                                        ?>
                                            <tr class="cmp-row" data-staff-id="<?php echo $sid; ?>">
                                                <td style="font-weight:700; color:#64748b;"><?php echo $row_i++; ?></td>
                                                <td>
                                                    <code style="background:#f1f5f9; color:#0f172a; padding:2px 6px; border-radius:4px; font-weight:700;">
                                                        <?php echo html_escape($val['employee_id']); ?>
                                                    </code>
                                                    <input type="hidden" name="staff_session[]" value="<?php echo $sid; ?>">
                                                </td>
                                                <td>
                                                    <div style="font-weight:700; color:#0f172a;">
                                                        <?php echo html_escape($val['name'] . ' ' . $val['surname']); ?>
                                                    </div>
                                                    <?php if (!empty($val['contact_no'])) { ?>
                                                        <div style="font-size:11px; color:#64748b;">
                                                            <i class="fa fa-phone text-muted"></i> <?php echo html_escape($val['contact_no']); ?>
                                                        </div>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <span style="background:#e2e8f0; color:#334155; padding:2px 7px; border-radius:4px; font-size:11px; font-weight:600;">
                                                        <?php echo html_escape($val['role_name']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span style="background:<?php echo $att_bg; ?>; color:<?php echo $att_fg; ?>; padding:2px 8px; border-radius:12px; font-size:11px; font-weight:700; display:inline-block;">
                                                        <?php echo html_escape($att_text); ?>
                                                    </span>
                                                    <?php if (!empty($val['in_time']) && $val['in_time'] !== '00:00:00') { ?>
                                                        <div style="font-size:10.5px; color:#64748b; margin-top:2px;">
                                                            <i class="fa fa-clock-o"></i> <?php echo date('h:i A', strtotime($val['in_time'])); ?>
                                                        </div>
                                                    <?php } ?>
                                                </td>

                                                <!-- Uniform Toggle -->
                                                <td>
                                                    <label class="cmp-toggle-chip uniform-chip <?php echo ($u_val === 'no') ? 'is-no' : 'is-yes'; ?>" title="Toggle Uniform">
                                                        <input type="checkbox" class="cmp-checkbox uniform-check" data-field="uniform_status" data-staff-id="<?php echo $sid; ?>" name="uniform_status_<?php echo $sid; ?>" value="yes" <?php echo ($u_val === 'no') ? '' : 'checked'; ?>>
                                                        <i class="fa <?php echo ($u_val === 'no') ? 'fa-times' : 'fa-check'; ?>"></i>
                                                        <span>Uniform</span>
                                                    </label>
                                                </td>

                                                <!-- ID Card Toggle -->
                                                <td>
                                                    <label class="cmp-toggle-chip idcard-chip <?php echo ($id_val === 'no') ? 'is-no' : 'is-yes'; ?>" title="Toggle ID Card">
                                                        <input type="checkbox" class="cmp-checkbox idcard-check" data-field="id_card_status" data-staff-id="<?php echo $sid; ?>" name="id_card_status_<?php echo $sid; ?>" value="yes" <?php echo ($id_val === 'no') ? '' : 'checked'; ?>>
                                                        <i class="fa <?php echo ($id_val === 'no') ? 'fa-times' : 'fa-check'; ?>"></i>
                                                        <span>ID Card</span>
                                                    </label>
                                                </td>

                                                <!-- Lesson Plan Toggle -->
                                                <td>
                                                    <label class="cmp-toggle-chip lessonplan-chip <?php echo ($lp_val === 'no') ? 'is-no' : 'is-yes'; ?>" title="Toggle Lesson Plan">
                                                        <input type="checkbox" class="cmp-checkbox lessonplan-check" data-field="lesson_plan_status" data-staff-id="<?php echo $sid; ?>" name="lesson_plan_status_<?php echo $sid; ?>" value="yes" <?php echo ($lp_val === 'no') ? '' : 'checked'; ?>>
                                                        <i class="fa <?php echo ($lp_val === 'no') ? 'fa-times' : 'fa-check'; ?>"></i>
                                                        <span>Lesson Plan</span>
                                                    </label>
                                                </td>

                                                <!-- Phone Handover Toggle -->
                                                <td>
                                                    <label class="cmp-toggle-chip phone-chip <?php echo ($ph_val === 'no') ? 'is-no' : 'is-yes'; ?>" title="Toggle Phone Handover">
                                                        <input type="checkbox" class="cmp-checkbox phone-check" data-field="phone_handover_status" data-staff-id="<?php echo $sid; ?>" name="phone_handover_status_<?php echo $sid; ?>" value="yes" <?php echo ($ph_val === 'no') ? '' : 'checked'; ?>>
                                                        <i class="fa <?php echo ($ph_val === 'no') ? 'fa-times' : 'fa-check'; ?>"></i>
                                                        <span>Phone</span>
                                                    </label>
                                                </td>

                                                <!-- Remarks Input -->
                                                <td>
                                                    <input type="text" class="form-control input-sm" name="remark_<?php echo $sid; ?>" value="<?php echo html_escape($val['remark']); ?>" placeholder="Add note..." style="height:28px; font-size:12px; border-radius:6px; border-color:#cbd5e1;">
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
                    </form>
                <?php } ?>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
(function($) {
    'use strict';

    // 1. Sync Chip UI State on Checkbox Change
    function syncChip($chk) {
        var isChecked = $chk.is(':checked');
        var $chip = $chk.closest('.cmp-toggle-chip');
        var $icon = $chip.find('i');
        if (isChecked) {
            $chip.removeClass('is-no').addClass('is-yes');
            $icon.removeClass('fa-times').addClass('fa-check');
        } else {
            $chip.removeClass('is-yes').addClass('is-no');
            $icon.removeClass('fa-check').addClass('fa-times');
        }
    }

    $(document).on('change', '.cmp-checkbox', function() {
        syncChip($(this));
    });

    // 2. Column-Level Bulk Toggles
    $(document).on('click', '.bulk-col-btn', function() {
        var targetClass = $(this).data('target');
        var state = $(this).data('val') === true || $(this).data('val') === 'true';
        $('.' + targetClass).prop('checked', state).each(function() {
            syncChip($(this));
        });
    });

    // 3. Global Bulk Toggles (All Yes / All No)
    $(document).on('click', '.bulk-set-global', function() {
        var state = $(this).data('val') === true || $(this).data('val') === 'true';
        $('.cmp-checkbox').prop('checked', state).each(function() {
            syncChip($(this));
        });
    });

    // 4. Quick Live Search Filter
    $('#compliance-search').on('keyup', function() {
        var val = $(this).val().toLowerCase().trim();
        $('#compliance-table tbody tr.cmp-row').each(function() {
            var rowText = $(this).text().toLowerCase();
            if (rowText.indexOf(val) > -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // 5. Prev / Next Day Navigation
    $('#btn-prev-day, #btn-next-day').on('click', function() {
        var isNext = $(this).attr('id') === 'btn-next-day';
        var $dateField = $('#date_field');
        var curVal = $dateField.val();
        if (!curVal) return;

        var parts = curVal.split('/');
        var curDate;
        if (parts.length === 3) {
            curDate = new Date(parseInt(parts[2], 10), parseInt(parts[0], 10) - 1, parseInt(parts[1], 10));
            if (isNaN(curDate.getTime())) {
                curDate = new Date(parseInt(parts[2], 10), parseInt(parts[1], 10) - 1, parseInt(parts[0], 10));
            }
        } else {
            curDate = new Date(curVal);
        }

        if (isNaN(curDate.getTime())) return;
        curDate.setDate(curDate.getDate() + (isNext ? 1 : -1));

        var yyyy = curDate.getFullYear();
        var mm = String(curDate.getMonth() + 1).padStart(2, '0');
        var dd = String(curDate.getDate()).padStart(2, '0');

        if (parts.length === 3) {
            $dateField.val(dd + '/' + mm + '/' + yyyy);
        } else {
            $dateField.val(yyyy + '-' + mm + '-' + dd);
        }
        $('#filter_form').submit();
    });

})(jQuery);
</script>
