<style type="text/css">
    /* ==========================================================================
       MODERN STAFF ATTENDANCE STYLING & COMPACT HIGH-DENSITY DESIGN SYSTEM
       ========================================================================== */
    :root {
        --sa-primary: #0f766e;
        --sa-primary-dark: #115e59;
        --sa-primary-light: #f0fdfa;
        --sa-present: #16a34a;
        --sa-present-bg: #dcfce7;
        --sa-present-text: #15803d;
        --sa-present-border: #bbf7d0;

        --sa-late: #d97706;
        --sa-late-bg: #fef3c7;
        --sa-late-text: #b45309;
        --sa-late-border: #fde68a;

        --sa-absent: #dc2626;
        --sa-absent-bg: #fee2e2;
        --sa-absent-text: #b91c1c;
        --sa-absent-border: #fecaca;

        --sa-halfday: #2563eb;
        --sa-halfday-bg: #dbeafe;
        --sa-halfday-text: #1d4ed8;
        --sa-halfday-border: #bfdbfe;

        --sa-holiday: #7c3aed;
        --sa-holiday-bg: #ede9fe;
        --sa-holiday-text: #6d28d9;
        --sa-holiday-border: #ddd6fe;

        --sa-unplanned: #db2777;
        --sa-unplanned-bg: #fce7f3;
        --sa-unplanned-text: #be185d;
        --sa-unplanned-border: #fbcfe8;

        --sa-onduty: #0d9488;
        --sa-onduty-bg: #ccfbf1;
        --sa-onduty-text: #0f766e;
        --sa-onduty-border: #99f6e4;

        --sa-border: #e2e8f0;
        --sa-card-bg: #ffffff;
        --sa-text-main: #0f172a;
        --sa-text-muted: #64748b;
    }

    .staffatt-page {
        font-family: inherit;
        color: var(--sa-text-main);
    }

    /* ===== Top Header Bar ===== */
    .sa-top-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 14px;
    }

    .sa-nav-tabs {
        display: inline-flex;
        background: #e2e8f0;
        border-radius: 10px;
        padding: 3px;
        gap: 2px;
    }

    .sa-tab-btn {
        border: none;
        background: transparent;
        color: #475569;
        font-size: 13px;
        font-weight: 600;
        padding: 7px 16px;
        border-radius: 8px;
        transition: all 0.2s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .sa-tab-btn.active {
        background: #ffffff;
        color: #0f172a;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .sa-top-actions {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .sa-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12.5px;
        font-weight: 600;
        padding: 6px 13px;
        border-radius: 8px;
        border: 1px solid var(--sa-border);
        background: #ffffff;
        color: #334155;
        transition: all 0.15s ease;
        cursor: pointer;
        text-decoration: none !important;
    }

    .sa-btn:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: #0f172a;
    }

    .sa-btn-primary {
        background: var(--sa-primary);
        border-color: var(--sa-primary-dark);
        color: #ffffff;
    }

    .sa-btn-primary:hover {
        background: var(--sa-primary-dark);
        color: #ffffff;
    }

    .sa-btn-whatsapp {
        background: #16a34a;
        border-color: #15803d;
        color: #ffffff;
    }

    .sa-btn-whatsapp:hover {
        background: #15803d;
        color: #ffffff;
    }

    /* ===== Search Criteria Panel ===== */
    .sa-criteria-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid var(--sa-border);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
        padding: 14px 18px;
        margin-bottom: 14px;
    }

    .sa-criteria-form {
        display: flex;
        align-items: flex-end;
        gap: 14px;
        flex-wrap: wrap;
    }

    .sa-form-group {
        display: flex;
        flex-direction: column;
        gap: 4px;
        flex: 1;
        min-width: 180px;
    }

    .sa-form-group label {
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin: 0;
    }

    .sa-form-group .form-control {
        border-radius: 8px;
        border: 1px solid #cbd5e1;
        height: 38px;
        font-size: 13.5px;
        box-shadow: none;
        transition: border-color 0.15s ease;
    }

    .sa-form-group .form-control:focus {
        border-color: var(--sa-primary);
        outline: none;
    }

    .sa-date-wrap {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .sa-date-nav-btn {
        height: 38px;
        width: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid #cbd5e1;
        background: #f8fafc;
        border-radius: 8px;
        color: #475569;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .sa-date-nav-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
    }

    /* ===== Live Stats KPI Chips Bar ===== */
    .sa-kpi-bar {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
        margin-bottom: 12px;
        background: #ffffff;
        border: 1px solid var(--sa-border);
        border-radius: 12px;
        padding: 8px 12px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
    }

    .sa-kpi-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 11px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        color: #475569;
        cursor: pointer;
        user-select: none;
        transition: all 0.15s ease;
    }

    .sa-kpi-chip:hover {
        border-color: #94a3b8;
        transform: translateY(-1px);
    }

    .sa-kpi-chip.active {
        background: #0f172a !important;
        border-color: #0f172a !important;
        color: #ffffff !important;
    }

    .sa-kpi-chip.active .sa-kpi-count {
        background: #334155 !important;
        color: #ffffff !important;
    }

    .sa-kpi-count {
        display: inline-block;
        padding: 1px 7px;
        border-radius: 10px;
        font-size: 11px;
        font-weight: 700;
        color: #ffffff;
    }

    .kpi-all .sa-kpi-count { background: #64748b; }
    .kpi-present .sa-kpi-count { background: var(--sa-present); }
    .kpi-late .sa-kpi-count { background: var(--sa-late); }
    .kpi-absent .sa-kpi-count { background: var(--sa-absent); }
    .kpi-halfday .sa-kpi-count { background: var(--sa-halfday); }
    .kpi-holiday .sa-kpi-count { background: var(--sa-holiday); }
    .kpi-missing .sa-kpi-count { background: #ea580c; }
    .kpi-qr .sa-kpi-count { background: #0891b2; }
    .kpi-manual .sa-kpi-count { background: #64748b; }

    /* ===== Toolbar with Instant Search & Quick Actions ===== */
    .sa-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        margin-bottom: 12px;
    }

    .sa-search-wrap {
        position: relative;
        min-width: 260px;
        max-width: 380px;
        flex: 1;
    }

    .sa-search-wrap i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
        font-size: 13px;
    }

    .sa-search-input {
        width: 100%;
        padding: 7px 12px 7px 34px;
        border-radius: 20px;
        border: 1px solid #cbd5e1;
        font-size: 13px;
        background: #ffffff;
        transition: all 0.15s ease;
    }

    .sa-search-input:focus {
        border-color: var(--sa-primary);
        outline: none;
        box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.12);
    }

    .sa-actions-right {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    /* ===== High Density Modern Table ===== */
    .sa-table-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid var(--sa-border);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .sa-table-container {
        max-height: 72vh;
        overflow-y: auto;
        position: relative;
    }

    .sa-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 12.5px;
    }

    .sa-table thead th {
        position: sticky;
        top: 0;
        z-index: 10;
        background: #f8fafc;
        color: #475569;
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 9px 10px;
        border-bottom: 2px solid #cbd5e1;
        white-space: nowrap;
        vertical-align: middle;
    }

    .sa-table tbody tr {
        transition: background-color 0.1s ease;
    }

    .sa-table tbody tr:hover td {
        background-color: #f1f5f9 !important;
    }

    .sa-table tbody td {
        padding: 7px 9px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
        background: #ffffff;
    }

    /* Status accent bar on first column */
    .sa-table tbody tr td:first-child { position: relative; }
    .sa-table tbody tr.rt-present td:first-child { box-shadow: inset 4px 0 0 var(--sa-present); }
    .sa-table tbody tr.rt-late td:first-child { box-shadow: inset 4px 0 0 var(--sa-late); }
    .sa-table tbody tr.rt-absent td:first-child { box-shadow: inset 4px 0 0 var(--sa-absent); }
    .sa-table tbody tr.rt-half_day td:first-child { box-shadow: inset 4px 0 0 var(--sa-halfday); }
    .sa-table tbody tr.rt-holiday td:first-child { box-shadow: inset 4px 0 0 var(--sa-holiday); }
    .sa-table tbody tr.rt-half_day_second_shift td:first-child { box-shadow: inset 4px 0 0 var(--sa-halfday); }
    .sa-table tbody tr.rt-unplanned_leave td:first-child { box-shadow: inset 4px 0 0 var(--sa-unplanned); }
    .sa-table tbody tr.rt-on_duty td:first-child { box-shadow: inset 4px 0 0 var(--sa-onduty); }

    /* ===== Single Active Status Badge & Dropdown Selector ===== */
    .sa-status-dropdown {
        position: relative;
        display: inline-block;
    }

    .sa-status-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        border: 1.5px solid transparent;
        cursor: pointer;
        transition: all 0.15s ease;
        outline: none !important;
        background: #f8fafc;
        color: #475569;
    }

    .sa-status-btn:hover {
        filter: brightness(0.96);
        transform: translateY(-1px);
    }

    .sa-status-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        display: inline-block;
    }

    /* Per-status styling for the single badge */
    .btn-status-present { background: var(--sa-present-bg); color: var(--sa-present-text); border-color: var(--sa-present-border); }
    .btn-status-present .sa-status-dot { background: var(--sa-present); }

    .btn-status-late { background: var(--sa-late-bg); color: var(--sa-late-text); border-color: var(--sa-late-border); }
    .btn-status-late .sa-status-dot { background: var(--sa-late); }

    .btn-status-absent { background: var(--sa-absent-bg); color: var(--sa-absent-text); border-color: var(--sa-absent-border); }
    .btn-status-absent .sa-status-dot { background: var(--sa-absent); }

    .btn-status-half_day { background: var(--sa-halfday-bg); color: var(--sa-halfday-text); border-color: var(--sa-halfday-border); }
    .btn-status-half_day .sa-status-dot { background: var(--sa-halfday); }

    .btn-status-half_day_second_shift { background: var(--sa-halfday-bg); color: var(--sa-halfday-text); border-color: var(--sa-halfday-border); }
    .btn-status-half_day_second_shift .sa-status-dot { background: var(--sa-halfday); }

    .btn-status-holiday { background: var(--sa-holiday-bg); color: var(--sa-holiday-text); border-color: var(--sa-holiday-border); }
    .btn-status-holiday .sa-status-dot { background: var(--sa-holiday); }

    .btn-status-unplanned_leave { background: var(--sa-unplanned-bg); color: var(--sa-unplanned-text); border-color: var(--sa-unplanned-border); }
    .btn-status-unplanned_leave .sa-status-dot { background: var(--sa-unplanned); }

    .btn-status-on_duty { background: var(--sa-onduty-bg); color: var(--sa-onduty-text); border-color: var(--sa-onduty-border); }
    .btn-status-on_duty .sa-status-dot { background: var(--sa-onduty); }

    .dot-on_duty { background: var(--sa-onduty); }
    .kpi-onduty .sa-kpi-count { background: var(--sa-onduty); }

    /* Dropdown menu items */
    .sa-status-menu {
        border-radius: 10px;
        border: 1px solid #cbd5e1;
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.15);
        padding: 4px;
        min-width: 190px;
        z-index: 1050;
    }

    .sa-status-menu li a {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        color: #334155;
        text-decoration: none;
        transition: all 0.1s ease;
    }

    .sa-status-menu li a:hover {
        background: #f1f5f9;
        color: #0f172a;
    }

    .sa-status-menu .dot-present { background: var(--sa-present); }
    .sa-status-menu .dot-late { background: var(--sa-late); }
    .sa-status-menu .dot-absent { background: var(--sa-absent); }
    .sa-status-menu .dot-half_day { background: var(--sa-halfday); }
    .sa-status-menu .dot-half_day_second_shift { background: var(--sa-halfday); }
    .sa-status-menu .dot-holiday { background: var(--sa-holiday); }
    .sa-status-menu .dot-unplanned_leave { background: var(--sa-unplanned); }

    /* ===== Clearly Understandable Compliance Badges ===== */
    .sa-cmp-container {
        display: flex;
        align-items: center;
        gap: 5px;
        flex-wrap: wrap;
    }

    .sa-cmp-chip {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        cursor: pointer;
        user-select: none;
        margin: 0;
        border: 1px solid transparent;
        transition: all 0.12s ease;
    }

    .sa-cmp-chip input[type="checkbox"] {
        display: none;
    }

    /* Green Compliant State */
    .sa-cmp-chip.is-yes {
        background: #dcfce7;
        color: #15803d;
        border-color: #bbf7d0;
    }

    .sa-cmp-chip.is-yes:hover {
        background: #bbf7d0;
    }

    /* Red Non-Compliant State */
    .sa-cmp-chip.is-no {
        background: #fee2e2;
        color: #b91c1c;
        border-color: #fecaca;
    }

    .sa-cmp-chip.is-no:hover {
        background: #fecaca;
    }

    .sa-cmp-chip i {
        font-size: 10px;
    }

    /* ===== Modern Time & Duration Controls ===== */
    .sa-time-cell {
        display: flex;
        flex-direction: column;
        gap: 2px;
        min-width: 90px;
    }

    .sa-time-input-wrap {
        position: relative;
        display: flex;
        align-items: center;
    }

    .sa-time-input {
        width: 100%;
        height: 28px;
        padding: 2px 22px 2px 6px;
        font-size: 12px;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        font-weight: 600;
        color: #334155;
        background: #ffffff;
    }

    .sa-time-input:disabled {
        background: #f1f5f9;
        color: #94a3b8;
        cursor: not-allowed;
    }

    .sa-time-clear {
        position: absolute;
        right: 4px;
        background: transparent;
        border: none;
        color: #94a3b8;
        cursor: pointer;
        padding: 2px 4px;
        font-size: 11px;
    }

    .sa-time-clear:hover {
        color: #dc2626;
    }

    .sa-dur-badge {
        font-size: 10px;
        font-weight: 700;
        padding: 1px 6px;
        border-radius: 10px;
        display: inline-block;
        width: fit-content;
    }

    .sa-dur-green { background: #dcfce7; color: #15803d; }
    .sa-dur-amber { background: #fef3c7; color: #b45309; }
    .sa-dur-gray { background: #f1f5f9; color: #64748b; }
    .sa-dur-shortfall { background: #fee2e2; color: #b91c1c; }
    .kpi-shortfall .sa-kpi-count { background: #dc2626; }

    /* ===== Clean Header "Set All" ===== */
    .sa-header-setall {
        display: flex;
        align-items: center;
        gap: 4px;
        margin-top: 4px;
        text-transform: none;
        font-weight: normal;
    }

    .sa-setall-pill {
        padding: 1px 6px;
        border-radius: 4px;
        font-size: 10px;
        font-weight: 600;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        color: #475569;
        cursor: pointer;
        transition: all 0.1s ease;
    }

    .sa-setall-pill:hover {
        background: #0f172a;
        color: #ffffff;
        border-color: #0f172a;
    }

    /* ===== Note / Remark Input Styling ===== */
    .sa-note-input {
        width: 100% !important;
        min-width: 170px;
        height: 28px !important;
        font-size: 12px !important;
        font-weight: 500;
        border-radius: 6px !important;
        border: 1px solid #cbd5e1 !important;
        background: #ffffff !important;
        color: #334155 !important;
        padding: 2px 8px !important;
        box-shadow: none !important;
        transition: all 0.15s ease;
    }

    .sa-note-input:focus {
        border-color: var(--sa-primary) !important;
        background: #ffffff !important;
        box-shadow: 0 0 0 2px rgba(15, 118, 110, 0.15) !important;
        outline: none !important;
    }

    .sa-note-input::placeholder {
        color: #94a3b8;
        font-weight: 400;
    }
</style>

<div class="content-wrapper staffatt-page">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Top Navigation & Action Bar -->
                <div class="sa-top-header">
                    <div class="sa-nav-tabs">
                        <button type="button" class="sa-tab-btn active" data-tab="daily"><i class="fa fa-calendar-check-o"></i> <?php echo $this->lang->line('staff_attendance'); ?></button>
                        <button type="button" class="sa-tab-btn" data-tab="monthly"><i class="fa fa-table"></i> Monthly Sheet</button>
                    </div>

                    <div class="sa-top-actions">
                        <button type="button" id="btn-quick-sync-biometric" class="sa-btn" title="Sync punches from e-TimeOffice Biometric Machine">
                            <i class="fa fa-fingerprint text-info"></i> Sync Biometric
                        </button>
                        <a href="<?php echo site_url('admin/dutypass'); ?>" class="sa-btn" style="background:#f0fdfa; border-color:#99f6e4; color:#0f766e; font-weight:700;" title="Issue & Manage Field Duty Passes">
                            <i class="fa fa-id-badge text-teal" style="color:#0d9488;"></i> Field Duty Pass
                        </a>
                        <a href="<?php echo site_url('admin/staffattendance/qrdisplay'); ?>" target="_blank" class="sa-btn">
                            <i class="fa fa-desktop text-muted"></i> Display QR
                        </a>
                        <div class="dropdown" style="display:inline-block;">
                            <button class="sa-btn dropdown-toggle" type="button" data-toggle="dropdown" title="Settings">
                                <i class="fa fa-cog text-muted"></i> <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right" style="border-radius: 8px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.15);">
                                <li><a href="<?php echo site_url('admin/staffattendance/biometricsettings'); ?>"><i class="fa fa-sliders"></i> Biometric Device Settings</a></li>
                                <li><a href="<?php echo site_url('admin/staffattendance/qrsettings'); ?>"><i class="fa fa-qrcode"></i> QR Attendance Settings</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Daily Tab Content -->
                <div id="tab-daily">
                    <!-- Criteria Filter Card -->
                    <div class="sa-criteria-card">
                        <form id="form1" action="<?php echo site_url('admin/staffattendance/index') ?>" method="post" accept-charset="utf-8">
                            <?php
                            if ($this->session->flashdata('msg')) {
                                echo $this->session->flashdata('msg');
                                $this->session->unset_userdata('msg');
                            }
                            ?>
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="sa-criteria-form">
                                <div class="sa-form-group" style="flex: 1.5;">
                                    <label for="class_id"><?php echo $this->lang->line('role'); ?></label>
                                    <select autofocus="" id="class_id" name="user_id" class="form-control">
                                        <option value="select"><?php echo $this->lang->line('select'); ?> All Roles</option>
                                        <?php
                                        foreach ($classlist as $key => $class) {
                                        ?>
                                            <option value="<?php echo $class["type"] ?>" <?php
                                                if ($class["type"] == $user_type_id) {
                                                    echo "selected =selected";
                                                }
                                                ?>><?php echo html_escape($class["type"]); ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                </div>

                                <div class="sa-form-group" style="flex: 1.5;">
                                    <label for="date_field"><?php echo $this->lang->line('attendance_date'); ?></label>
                                    <div class="sa-date-wrap">
                                        <button type="button" class="sa-date-nav-btn" id="btn-prev-day" title="Previous Day"><i class="fa fa-chevron-left"></i></button>
                                        <input id="date_field" name="date" type="text" class="form-control date" value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat())); ?>" readonly="readonly" style="background:#ffffff; cursor:pointer;" />
                                        <button type="button" class="sa-date-nav-btn" id="btn-next-day" title="Next Day"><i class="fa fa-chevron-right"></i></button>
                                    </div>
                                    <span class="text-danger"><?php echo form_error('date'); ?></span>
                                </div>

                                <div style="display:flex; gap:8px;">
                                    <button type="submit" name="search" value="search" class="sa-btn sa-btn-primary" style="height:38px; padding:0 20px;">
                                        <i class="fa fa-search"></i> Search Staff
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <?php
                    if (isset($resultlist)) {
                        if (!empty($resultlist)) {
                            // Attendance types that should NOT have entry/exit time
                            $leave_like_ids = [];
                            foreach ($attendencetypeslist as $__t) {
                                if (in_array($__t['long_lang_name'], ['absent', 'holiday', 'unplanned_leave'])) {
                                    $leave_like_ids[] = (int)$__t['id'];
                                }
                            }
                    ?>
                        <form action="<?php echo site_url('admin/staffattendance/index') ?>" id="save_attendance" method="post">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <input type="hidden" name="is_first_time_attendance" value="<?php echo $is_first_time_attendance;?>">
                            <input type="hidden" name="user_id" value="<?php echo $user_type_id; ?>">
                            <input type="hidden" name="section_id" value="">
                            <input type="hidden" name="date" value="<?php echo $date; ?>">

                            <!-- Interactive Live Stats KPI Filter Bar -->
                            <div class="sa-kpi-bar" id="live-att-stats">
                                <span class="sa-kpi-chip kpi-all active" data-filter="all">
                                    <i class="fa fa-users"></i> All Staff <span class="sa-kpi-count" id="cnt-all"><?php echo count($resultlist); ?></span>
                                </span>
                                <span class="sa-kpi-chip kpi-present" data-filter="present" title="Filter Present staff">
                                    <i class="fa fa-check-circle text-success"></i> Present <span class="sa-kpi-count" id="cnt-present">0</span>
                                </span>
                                <span class="sa-kpi-chip kpi-late" data-filter="late" title="Filter Late staff">
                                    <i class="fa fa-clock-o text-warning"></i> Late <span class="sa-kpi-count" id="cnt-late">0</span>
                                </span>
                                <span class="sa-kpi-chip kpi-absent" data-filter="absent" title="Filter Absent staff">
                                    <i class="fa fa-times-circle text-danger"></i> Absent <span class="sa-kpi-count" id="cnt-absent">0</span>
                                </span>
                                <span class="sa-kpi-chip kpi-halfday" data-filter="half_day" title="Filter Half Day staff">
                                    <i class="fa fa-adjust text-info"></i> Half Day <span class="sa-kpi-count" id="cnt-half_day">0</span>
                                </span>
                                <span class="sa-kpi-chip kpi-holiday" data-filter="holiday" title="Filter Holiday staff">
                                    <i class="fa fa-tree" style="color:#7c3aed;"></i> Holiday <span class="sa-kpi-count" id="cnt-holiday">0</span>
                                </span>
                                <span class="sa-kpi-chip kpi-onduty" data-filter="on_duty" title="Filter On Duty staff">
                                    <i class="fa fa-id-badge" style="color:var(--sa-onduty);"></i> On Duty <span class="sa-kpi-count" id="cnt-on_duty">0</span>
                                </span>
                                <span class="sa-kpi-chip kpi-missing" data-filter="missing-out" title="Filter staff with Entry punch but Missing Exit punch">
                                    <i class="fa fa-exclamation-triangle text-warning"></i> Missing Exit <span class="sa-kpi-count" id="cnt-missing">0</span>
                                </span>
                                <span class="sa-kpi-chip kpi-shortfall" data-filter="shortfall" title="Filter staff with working hours shortfall">
                                    <i class="fa fa-hourglass-half text-danger"></i> Shortfall <span class="sa-kpi-count" id="cnt-shortfall">0</span>
                                </span>
                                <span class="sa-kpi-chip kpi-qr" data-filter="biometric" title="Filter Biometric or QR punches">
                                    <i class="fa fa-qrcode text-info"></i> Biometric/QR <span class="sa-kpi-count" id="cnt-qr">0</span>
                                </span>
                                <span class="sa-kpi-chip kpi-manual" data-filter="manual" title="Filter Manual entries">
                                    <i class="fa fa-pencil text-muted"></i> Manual <span class="sa-kpi-count" id="cnt-manual">0</span>
                                </span>
                            </div>

                            <!-- Live Search & Action Bar -->
                            <div class="sa-toolbar">
                                <div class="sa-search-wrap">
                                    <i class="fa fa-search"></i>
                                    <input type="text" id="att-table-search" class="sa-search-input" placeholder="Quick search staff name, ID, phone...">
                                </div>
                                <div class="sa-actions-right">
                                    <div class="sa-sort-wrap" style="display:inline-flex; align-items:center; gap:6px;">
                                        <span style="font-size:11.5px; font-weight:700; color:#64748b;"><i class="fa fa-sort"></i> Sort:</span>
                                        <select id="att-sort-select" class="form-control input-sm" style="width:160px; height:32px; font-size:12px; font-weight:600; border-radius:8px; border-color:#cbd5e1; background:#ffffff; cursor:pointer;">
                                            <option value="default">Default (#)</option>
                                            <option value="name_asc">Name (A → Z)</option>
                                            <option value="name_desc">Name (Z → A)</option>
                                            <option value="in_time_asc">Entry Time (Earliest)</option>
                                            <option value="in_time_desc">Entry Time (Latest)</option>
                                            <option value="emp_id">Staff ID</option>
                                            <option value="status">Status</option>
                                            <option value="shortfall">Shortfall First</option>
                                        </select>
                                    </div>
                                    <button type="button" class="sa-btn sa-btn-whatsapp" onclick="openStaffAttendanceShareModal('all')">
                                        <i class="fa fa-whatsapp"></i> WhatsApp Report
                                    </button>
                                    <?php if (($this->rbac->hasPrivilege('staff_attendance', 'can_add')) || ($this->rbac->hasPrivilege('staff_attendance', 'can_edit'))) { ?>
                                        <button type="submit" name="search" value="saveattendence" id="saveattendence" class="sa-btn sa-btn-primary" style="font-weight:700;">
                                            <i class="fa fa-save"></i> <?php echo $this->lang->line('save_attendance'); ?>
                                        </button>
                                    <?php } ?>
                                </div>
                            </div>

                            <!-- Modern High-Density Table Card -->
                            <div class="sa-table-card">
                                <div class="sa-table-container">
                                    <table class="sa-table" id="staff-attendance-table">
                                        <thead>
                                            <tr>
                                                <th width="35">#</th>
                                                <th width="90"><?php echo $this->lang->line('staff_id'); ?></th>
                                                <th><?php echo $this->lang->line('name'); ?></th>
                                                <th><?php echo $this->lang->line('role'); ?></th>
                                                <!-- Compact Attendance Column -->
                                                <th style="min-width: 170px;">
                                                    <div style="display:flex; justify-content:space-between; align-items:center;">
                                                        <span><?php echo $this->lang->line('attendance'); ?></span>
                                                        <div class="dropdown sa-header-setall" style="display:inline-block;">
                                                            <button class="sa-setall-pill dropdown-toggle" type="button" data-toggle="dropdown" style="font-size:10.5px;">
                                                                Set All ▾
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-right sa-status-menu" style="min-width:180px;">
                                                                <?php foreach ($attendencetypeslist as $type) { 
                                                                    $att_type = str_replace(" ", "_", strtolower($type['type']));
                                                                ?>
                                                                    <li>
                                                                        <a href="javascript:void(0);" class="bulk-set-att" data-type-id="<?php echo $type['id']; ?>" data-type-key="<?php echo $att_type; ?>" data-type-name="<?php echo html_escape($type['type']); ?>">
                                                                            <span class="sa-status-dot dot-<?php echo $att_type; ?>"></span> <?php echo html_escape($type['type']); ?>
                                                                        </a>
                                                                    </li>
                                                                <?php } ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </th>
                                                <!-- Clearly Understandable Compliance Column -->
                                                <th style="min-width: 270px;">
                                                    <div style="display:flex; justify-content:space-between; align-items:center;">
                                                        <span><i class="fa fa-check-square-o"></i> Compliance Checklist</span>
                                                        <div class="sa-header-setall">
                                                            <button type="button" class="sa-setall-pill bulk-set-all-cmp" data-val="true" title="Mark all staff compliant" style="color:#16a34a; font-weight:700;">
                                                                <i class="fa fa-check"></i> All Yes
                                                            </button>
                                                            <button type="button" class="sa-setall-pill bulk-set-all-cmp" data-val="false" title="Mark all non-compliant" style="color:#dc2626; font-weight:700;">
                                                                <i class="fa fa-times"></i> All No
                                                            </button>
                                                        </div>
                                                    </div>
                                                </th>
                                                <th width="75"><?php echo $this->lang->line('source'); ?></th>
                                                <th width="105"><?php echo $this->lang->line('entry_time'); ?></th>
                                                <th width="105"><?php echo $this->lang->line('exit_time'); ?></th>
                                                <th style="min-width: 180px; width: 200px;"><?php echo $this->lang->line('note'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $row_count = 1;
                                            foreach ($resultlist as $key => $value) {
                                                $attendendence_id = $value["id"];
                                                $staff_contact = !empty($value['contact_no']) ? $value['contact_no'] : '';
                                                
                                                // Determine initial selected type
                                                $selectedTypeId = $value['staff_attendance_type_id'];
                                                $selectedTypeName = 'Select Status';
                                                $selectedTypeKey = 'unmarked';

                                                if ($value["date"] != "xxx" && !empty($selectedTypeId)) {
                                                    foreach ($attendencetypeslist as $t) {
                                                        if ($t['id'] == $selectedTypeId) {
                                                            $selectedTypeName = $t['type'];
                                                            $selectedTypeKey = str_replace(" ", "_", strtolower($t['type']));
                                                            break;
                                                        }
                                                    }
                                                } else {
                                                    if (!empty($value['duty_pass'])) {
                                                        $selectedTypeKey = "on_duty";
                                                    } elseif (!empty($value['in_time']) && $value['in_time'] !== '00:00:00') {
                                                        $selectedTypeKey = "present";
                                                    } elseif (!empty($sch_setting->biometric)) {
                                                        $selectedTypeKey = "absent";
                                                    } else {
                                                        $selectedTypeKey = "present";
                                                    }
                                                    foreach ($attendencetypeslist as $t) {
                                                        $tk = str_replace(" ", "_", strtolower($t['type']));
                                                        if ($tk === $selectedTypeKey) {
                                                            $selectedTypeId = $t['id'];
                                                            $selectedTypeName = $t['type'];
                                                            break;
                                                        }
                                                    }
                                                }

                                                $isLeaveLike = in_array((int)$selectedTypeId, $leave_like_ids);
                                            ?>
                                                <tr data-staff_id="<?php echo $value['staff_id']; ?>" 
                                                    data-role_id="<?php echo $value['role_id']; ?>"
                                                    data-req_hours="<?php echo isset($role_required_hours[$value['role_id']]) ? $role_required_hours[$value['role_id']] : '08:00:00'; ?>"
                                                    data-source="<?php echo (!empty($value['biometric_attendence']) ? 'biometric' : (!empty($value['qrcode_attendance']) ? 'qr' : 'manual')); ?>"
                                                    data-employee_id="<?php echo html_escape($value['employee_id']); ?>" 
                                                    data-staff_name="<?php echo html_escape($value['name'] . " " . $value['surname']); ?>" 
                                                    data-user_type="<?php echo html_escape($value['user_type']); ?>" 
                                                    data-contact_no="<?php echo html_escape($staff_contact); ?>"
                                                    class="rt-<?php echo $selectedTypeKey; ?>">
                                                    <td>
                                                        <input type="hidden" name="staff_role[]" id="staff_role_<?php echo $value['role_id']; ?>" value="<?php echo $value['role_id']; ?>">
                                                        <input type="hidden" name="student_session[]" value="<?php echo $value['staff_id']; ?>">
                                                        <input type="hidden" value="<?php echo $attendendence_id ?>" name="attendendence_id<?php echo $value["staff_id"]; ?>">
                                                        <span class="text-muted" style="font-weight:600; font-size:11.5px;"><?php echo $row_count; ?></span>
                                                    </td>
                                                    <td>
                                                        <code style="background:#f1f5f9; color:#334155; padding:2px 5px; border-radius:4px; font-weight:700; font-size:11px;"><?php echo html_escape($value['employee_id']); ?></code>
                                                    </td>
                                                    <td>
                                                        <div style="font-weight:700; color:#0f172a; line-height:1.2;"><?php echo html_escape($value['name'] . " " . $value['surname']); ?></div>
                                                        <?php if (!empty($value['duty_pass'])) { 
                                                            $dp = $value['duty_pass'];
                                                        ?>
                                                            <div style="margin-top: 3px;">
                                                                <a href="<?php echo base_url('admin/dutypass/print_dutypass/' . $dp['duty_pass_id']); ?>" target="_blank" class="badge" style="background:#0d9488; color:#ffffff; font-size:10px; font-weight:700; text-decoration:none; padding:3px 7px; border-radius:4px; display:inline-flex; align-items:center; gap:4px;" title="Official Duty: <?php echo html_escape($dp['duty_title']); ?> @ <?php echo html_escape($dp['venue']); ?> (<?php echo $dp['from_date']; ?> to <?php echo $dp['to_date']; ?>)">
                                                                    <i class="fa fa-id-badge"></i> ON DUTY (<?php echo html_escape($dp['duty_pass_no']); ?>) <i class="fa fa-print" style="font-size:9px;"></i>
                                                                </a>
                                                            </div>
                                                        <?php } ?>
                                                        <?php if (!empty($staff_contact)) { ?>
                                                            <div style="font-size:10.5px; color:#64748b; display:inline-flex; align-items:center; gap:4px; margin-top:2px;">
                                                                <a href="tel:<?php echo html_escape($staff_contact); ?>" style="color:#64748b; text-decoration:none;"><i class="fa fa-phone" style="font-size:9px;"></i> <?php echo html_escape($staff_contact); ?></a>
                                                                <a href="https://wa.me/91<?php echo preg_replace('/[^0-9]/', '', $staff_contact); ?>" target="_blank" style="color:#16a34a; font-size:10px;" title="WhatsApp Chat"><i class="fa fa-whatsapp"></i></a>
                                                            </div>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <span style="background:#f1f5f9; color:#475569; font-size:11px; font-weight:600; padding:2px 6px; border-radius:4px;"><?php echo html_escape($value['user_type']); ?></span>
                                                    </td>

                                                    <!-- Compact Single Status Badge & Dropdown Selector -->
                                                    <td>
                                                        <div class="dropdown sa-status-dropdown">
                                                            <button class="sa-status-btn btn-status-<?php echo $selectedTypeKey; ?> dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="status_btn_<?php echo $value['staff_id']; ?>">
                                                                <span class="sa-status-dot"></span>
                                                                <span class="sa-status-text"><?php echo html_escape($selectedTypeName); ?></span>
                                                                <i class="fa fa-caret-down" style="font-size:10px; margin-left:4px; opacity:0.8;"></i>
                                                            </button>
                                                            <ul class="dropdown-menu sa-status-menu" aria-labelledby="status_btn_<?php echo $value['staff_id']; ?>">
                                                                <?php foreach ($attendencetypeslist as $type) { 
                                                                    $att_type = str_replace(" ", "_", strtolower($type['type']));
                                                                ?>
                                                                    <li>
                                                                        <a href="javascript:void(0);" class="sa-status-opt" 
                                                                           data-staff-id="<?php echo $value['staff_id']; ?>" 
                                                                           data-type-id="<?php echo $type['id']; ?>" 
                                                                           data-type-key="<?php echo $att_type; ?>" 
                                                                           data-type-name="<?php echo html_escape($type['type']); ?>">
                                                                            <span class="sa-status-dot dot-<?php echo $att_type; ?>"></span> <?php echo html_escape($type['type']); ?>
                                                                        </a>
                                                                    </li>
                                                                <?php } ?>
                                                            </ul>
                                                            <!-- Preserved exact POST input name -->
                                                            <input type="hidden" 
                                                                   name="attendencetype<?php echo $value['staff_id']; ?>" 
                                                                   id="att_input_<?php echo $value['staff_id']; ?>" 
                                                                   value="<?php echo $selectedTypeId; ?>" 
                                                                   class="att-hidden-input" 
                                                                   data-staff-id="<?php echo $value['staff_id']; ?>" 
                                                                   data-type-key="<?php echo $selectedTypeKey; ?>">
                                                        </div>
                                                    </td>

                                                    <!-- Clearly Understandable Compliance Badges -->
                                                    <?php
                                                        $u_val  = isset($value['uniform_status']) ? $value['uniform_status'] : null;
                                                        $id_val = isset($value['id_card_status']) ? $value['id_card_status'] : null;
                                                        $lp_val = isset($value['lesson_plan_status']) ? $value['lesson_plan_status'] : null;
                                                        $ph_val = isset($value['phone_handover_status']) ? $value['phone_handover_status'] : null;
                                                    ?>
                                                    <td>
                                                        <div class="sa-cmp-container">
                                                            <!-- Uniform -->
                                                            <label class="sa-cmp-chip cmp-uniform <?php echo ($u_val === 'no') ? 'is-no' : 'is-yes'; ?>" title="Uniform Status (Click to toggle)">
                                                                <input type="checkbox" class="cmp-check uniform-check" name="uniform_status_<?php echo $value['staff_id']; ?>" value="yes" <?php echo ($u_val === 'no') ? '' : 'checked'; ?>>
                                                                <i class="fa <?php echo ($u_val === 'no') ? 'fa-times' : 'fa-check'; ?>"></i>
                                                                <span>Uniform</span>
                                                            </label>

                                                            <!-- ID Card -->
                                                            <label class="sa-cmp-chip cmp-idcard <?php echo ($id_val === 'no') ? 'is-no' : 'is-yes'; ?>" title="ID Card Status (Click to toggle)">
                                                                <input type="checkbox" class="cmp-check idcard-check" name="id_card_status_<?php echo $value['staff_id']; ?>" value="yes" <?php echo ($id_val === 'no') ? '' : 'checked'; ?>>
                                                                <i class="fa <?php echo ($id_val === 'no') ? 'fa-times' : 'fa-check'; ?>"></i>
                                                                <span>ID Card</span>
                                                            </label>

                                                            <!-- Lesson Plan -->
                                                            <label class="sa-cmp-chip cmp-lessonplan <?php echo ($lp_val === 'no') ? 'is-no' : 'is-yes'; ?>" title="Lesson Plan / Diary (Click to toggle)">
                                                                <input type="checkbox" class="cmp-check lessonplan-check" name="lesson_plan_status_<?php echo $value['staff_id']; ?>" value="yes" <?php echo ($lp_val === 'no') ? '' : 'checked'; ?>>
                                                                <i class="fa <?php echo ($lp_val === 'no') ? 'fa-times' : 'fa-check'; ?>"></i>
                                                                <span>Lesson Plan</span>
                                                            </label>

                                                            <!-- Phone Handover -->
                                                            <label class="sa-cmp-chip cmp-phone <?php echo ($ph_val === 'no') ? 'is-no' : 'is-yes'; ?>" title="Phone Handover (Click to toggle)">
                                                                <input type="checkbox" class="cmp-check phone-check" name="phone_handover_status_<?php echo $value['staff_id']; ?>" value="yes" <?php echo ($ph_val === 'no') ? '' : 'checked'; ?>>
                                                                <i class="fa <?php echo ($ph_val === 'no') ? 'fa-times' : 'fa-check'; ?>"></i>
                                                                <span>Phone</span>
                                                            </label>
                                                        </div>
                                                    </td>

                                                    <!-- Source -->
                                                    <td>
                                                        <?php
                                                        $hasPunchTime = (!empty($value['in_time']) && $value['in_time'] !== '00:00:00') || (!empty($value['out_time']) && $value['out_time'] !== '00:00:00');
                                                        if (!empty($value['biometric_attendence']) && $hasPunchTime) {
                                                            echo '<span style="font-size:11px; color:#0284c7; font-weight:600;"><i class="fa fa-fingerprint"></i> Bio</span>';
                                                        } elseif (!empty($value['qrcode_attendance']) && $hasPunchTime) {
                                                            echo '<span style="font-size:11px; color:#059669; font-weight:600;"><i class="fa fa-qrcode"></i> QR</span>';
                                                        } elseif (IsNullOrEmptyString($value['biometric_attendence']) && IsNullOrEmptyString($value['qrcode_attendance'])) {
                                                            echo '<span class="text-muted" style="font-size:11px;">-</span>';
                                                        } else {
                                                            echo '<span style="font-size:11px; color:#64748b;"><i class="fa fa-pencil"></i> Manual</span>';
                                                        }
                                                        ?>
                                                    </td>

                                                    <!-- Entry Time -->
                                                    <td>
                                                        <div class="sa-time-cell">
                                                            <div class="sa-time-input-wrap">
                                                                <input type="text" 
                                                                       name="in_time_<?php echo $value['staff_id']; ?>" 
                                                                       id="in_time_<?php echo $value['staff_id']; ?>" 
                                                                       value="<?php echo ($value['in_time'] != '00:00:00') ? html_escape($value['in_time']) : ''; ?>" 
                                                                       class="sa-time-input in_time in_time_<?php echo $value['role_id']; ?>" 
                                                                       data-staff_id="<?php echo $value['staff_id']; ?>" 
                                                                       data-role_id="<?php echo $value['role_id']; ?>"
                                                                       placeholder="--:--"
                                                                       <?php echo $isLeaveLike ? 'disabled' : ''; ?>>
                                                                <button type="button" class="sa-time-clear clear-time-btn" data-target="in_time_<?php echo $value['staff_id']; ?>" title="Clear"><i class="fa fa-times"></i></button>
                                                            </div>
                                                        </div>
                                                    </td>

                                                    <!-- Exit Time -->
                                                    <td>
                                                        <div class="sa-time-cell">
                                                            <div class="sa-time-input-wrap">
                                                                <input type="text" 
                                                                       name="out_time_<?php echo $value['staff_id']; ?>" 
                                                                       id="out_time_<?php echo $value['staff_id']; ?>" 
                                                                       value="<?php echo ($value['out_time'] != '00:00:00') ? html_escape($value['out_time']) : ''; ?>" 
                                                                       class="sa-time-input out_time out_time_<?php echo $value['role_id']; ?>" 
                                                                       data-staff_id="<?php echo $value['staff_id']; ?>" 
                                                                       data-role_id="<?php echo $value['role_id']; ?>"
                                                                       placeholder="--:--"
                                                                       <?php echo $isLeaveLike ? 'disabled' : ''; ?>>
                                                                <button type="button" class="sa-time-clear clear-time-btn" data-target="out_time_<?php echo $value['staff_id']; ?>" title="Clear"><i class="fa fa-times"></i></button>
                                                            </div>
                                                            <span class="sa-dur-badge sa-dur-gray dur-label-<?php echo $value['staff_id']; ?>" style="display:none;"></span>
                                                        </div>
                                                    </td>

                                                    <!-- Note -->
                                                    <td>
                                                        <input type="text" 
                                                               class="form-control input-sm sa-note-input" 
                                                               name="remark<?php echo $value['staff_id']; ?>" 
                                                               value="<?php echo ($value['date'] != 'xxx') ? html_escape($value['remark']) : ''; ?>" 
                                                               title="<?php echo ($value['date'] != 'xxx') ? html_escape($value['remark']) : ''; ?>"
                                                               placeholder="Add note...">
                                                    </td>
                                                </tr>
                                            <?php
                                                $row_count++;
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </form>
                    <?php
                        } else {
                    ?>
                        <div class="alert alert-info" style="border-radius:8px;"><i class="fa fa-info-circle"></i> <?php echo $this->lang->line('no_record_found'); ?></div>
                    <?php
                        }
                    }
                    ?>
                </div><!-- /#tab-daily -->

                <!-- Monthly Tab Content -->
                <div id="tab-monthly" style="display:none;">
                    <div class="sa-criteria-card">
                        <div style="font-size:15px; font-weight:700; color:#0f172a; margin-bottom:12px;">
                            <i class="fa fa-table text-primary"></i> Monthly Attendance Sheet
                        </div>
                        <div class="row">
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('role'); ?></label>
                                    <select id="ms-role" class="form-control" style="border-radius:8px;">
                                        <option value="select">All Staff</option>
                                        <?php foreach ($classlist as $c) { ?>
                                            <option value="<?php echo html_escape($c['type']); ?>"><?php echo html_escape($c['type']); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-4">
                                <div class="form-group">
                                    <label>Month</label>
                                    <select id="ms-month" class="form-control" style="border-radius:8px;">
                                        <?php for ($mn = 1; $mn <= 12; $mn++) { ?>
                                            <option value="<?php echo $mn; ?>" <?php echo ($mn == date('n')) ? 'selected' : ''; ?>><?php echo date('F', mktime(0, 0, 0, $mn, 1)); ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3 col-sm-4">
                                <div class="form-group">
                                    <label>Year</label>
                                    <select id="ms-year" class="form-control" style="border-radius:8px;">
                                        <?php $cy = (int) date('Y'); for ($yy = $cy - 3; $yy <= $cy + 1; $yy++) { ?>
                                            <option value="<?php echo $yy; ?>" <?php echo ($yy == $cy) ? 'selected' : ''; ?>><?php echo $yy; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-4">
                                <div class="form-group">
                                    <label class="hidden-xs" style="display:block;">&nbsp;</label>
                                    <button type="button" id="ms-show" class="sa-btn sa-btn-primary btn-block" style="height:38px; justify-content:center;">
                                        <i class="fa fa-search"></i> Show Sheet
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div id="ms-container" style="margin-top:16px;">
                            <div class="text-muted text-center" style="padding:24px; background:#f8fafc; border-radius:8px;">
                                Select role and month, then click <strong>Show Sheet</strong>.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- ==========================================================================
     WHATSAPP SHARE & SCREENSHOT MODAL
     ========================================================================== -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<div class="modal fade" id="staffAttendanceShareModal" tabindex="-1" role="dialog" aria-labelledby="staffAttendanceShareModalLabel" style="z-index: 1060;">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 960px; margin-top: 25px; margin-bottom: 25px;">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); overflow: hidden;">
            <div class="modal-header" style="background: linear-gradient(135deg, #15803d 0%, #16a34a 100%); color: #ffffff; padding: 16px 20px;">
                <button type="button" class="close" data-dismiss="modal" style="color: #ffffff; opacity: 0.9; font-size: 24px; text-shadow: none;">&times;</button>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="width: 40px; height: 40px; border-radius: 10px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 20px;">
                        <i class="fa fa-whatsapp"></i>
                    </div>
                    <div>
                        <h4 class="modal-title" style="font-weight: 700; margin: 0; font-size: 16.5px; color: #ffffff;">
                            📋 Staff Attendance WhatsApp Share & Screenshot Hub
                        </h4>
                        <div id="staff-share-modal-subtitle" style="font-size: 12px; opacity: 0.92; margin-top: 2px;">
                            Date: <strong><?php echo isset($date) && $date ? html_escape($date) : date('d M Y'); ?></strong> • Ready to share reports & screenshots
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-body" style="padding: 16px 20px; background: #f8fafc;">
                <!-- Status & Filter Tabs -->
                <div class="att-share-tabs-wrapper" style="margin-bottom: 15px; overflow-x: auto; padding-bottom: 4px;">
                    <div class="att-share-tabs-list" id="staff-share-tabs-container" style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <!-- Rendered by JS -->
                    </div>
                </div>

                <!-- Action Toolbar -->
                <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; background: #ffffff; padding: 12px 16px; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 16px; box-shadow: 0 1px 2px rgba(0,0,0,0.04);">
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <span style="font-size: 12px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">Quick Actions:</span>
                        <span id="staff-share-selected-desc" style="font-size: 12.5px; font-weight: 600; color: #0f172a;"></span>
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                        <button type="button" class="btn btn-sm btn-primary" onclick="copyStaffCardScreenshot()" id="btn-copy-staff-screenshot" style="background: #4f46e5; border-color: #4338ca; border-radius: 6px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px;">
                            <i class="fa fa-camera"></i> <span>📸 Copy Screenshot (Ctrl+V)</span>
                        </button>

                        <button type="button" class="btn btn-sm btn-default" onclick="downloadStaffCardScreenshot()" id="btn-download-staff-screenshot" style="border-radius: 6px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; color: #334155;">
                            <i class="fa fa-download"></i> <span>💾 Save Image</span>
                        </button>

                        <a href="javascript:void(0);" onclick="shareStaffOnWhatsApp()" id="btn-staff-whatsapp-direct" class="btn btn-sm btn-success" target="_blank" style="background: #16a34a; border-color: #15803d; border-radius: 6px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px;">
                            <i class="fa fa-whatsapp"></i> <span>📱 WhatsApp Share</span>
                        </a>

                        <button type="button" class="btn btn-sm btn-default" onclick="copyStaffShareText()" id="btn-copy-staff-text" style="border-radius: 6px; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; color: #475569;">
                            <i class="fa fa-copy"></i> <span>📋 Copy Text</span>
                        </button>
                    </div>
                </div>

                <!-- Feedback Toast Alert -->
                <div id="staff-share-feedback-alert" style="display: none; padding: 10px 16px; border-radius: 6px; margin-bottom: 12px; font-size: 13px; font-weight: 600;"></div>

                <!-- Screenshot Preview Container -->
                <div style="background: #cbd5e1; padding: 16px; border-radius: 10px; display: flex; justify-content: center; overflow-x: auto;">
                    <div id="staff-screenshot-card" style="width: 760px; min-width: 760px; background: #ffffff; border-radius: 12px; padding: 24px; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.12); font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #0f172a; position: relative;">
                        <!-- Dynamic Content Rendered by JS -->
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="padding: 12px 20px; background: #fafafa; display: flex; justify-content: space-between; align-items: center;">
                <small class="text-muted"><i class="fa fa-info-circle"></i> <strong>Tip:</strong> Click "Copy Screenshot", then switch to WhatsApp and press <code>Ctrl + V</code> to send image.</small>
                <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 6px; font-weight: 600;">Close</button>
            </div>
        </div>
    </div>
</div>

<style>
/* WhatsApp Share Tab Pills */
.att-share-tab-pill {
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
.att-share-tab-pill:hover { border-color: #94a3b8; color: #0f172a; background: #f1f5f9; }
.att-share-tab-pill.active { background: #16a34a; border-color: #15803d; color: #ffffff; box-shadow: 0 2px 4px rgba(22, 163, 74, 0.25); }
.att-share-tab-pill.tab-absent.active { background: #dc2626 !important; border-color: #b91c1c !important; }
.att-share-tab-pill.tab-late.active { background: #d97706 !important; border-color: #b45309 !important; }
.att-share-tab-pill.tab-half_day.active { background: #2563eb !important; border-color: #1d4ed8 !important; }
.att-tab-badge { background: #e2e8f0; color: #334155; border-radius: 10px; padding: 1px 7px; font-size: 11px; font-weight: 700; }
.att-share-tab-pill.active .att-tab-badge { background: #ffffff; color: #15803d; }
.att-share-tab-pill.tab-absent.active .att-tab-badge { color: #dc2626; }
.att-share-tab-pill.tab-late.active .att-tab-badge { color: #d97706; }
.att-share-tab-pill.tab-half_day.active .att-tab-badge { color: #2563eb; }
</style>

<!-- ==========================================================================
     SCRIPTS & CLIENT-SIDE EVENT HANDLERS
     ========================================================================== -->
<script type="text/javascript">
(function($) {
    'use strict';

    var STAFF_SCHOOL_NAME = <?php echo json_encode(!empty($sch_setting->name) ? $sch_setting->name : 'School Management System'); ?>;
    var STAFF_SELECTED_DATE = <?php echo json_encode(isset($date) && $date ? $date : date($this->customlib->getSchoolDateFormat())); ?>;
    var STAFF_SELECTED_ROLE = <?php echo json_encode(isset($user_type_id) && $user_type_id && $user_type_id !== 'select' ? $user_type_id : 'All Staff'); ?>;
    var attendance_setting = <?php echo json_encode(isset($staff_settings) ? $staff_settings : []); ?>;
    var leaveTypeIds = <?php echo json_encode(isset($leave_like_ids) ? $leave_like_ids : [3, 5]); ?>;
    window.leaveTypeIds = leaveTypeIds;

    // 1. Navigation Tabs (Daily vs Monthly)
    $(document).on('click', '.sa-tab-btn', function() {
        var t = $(this).data('tab');
        $('.sa-tab-btn').removeClass('active');
        $(this).addClass('active');
        $('#tab-daily').toggle(t === 'daily');
        $('#tab-monthly').toggle(t === 'monthly');
    });

    // 2. Monthly Sheet AJAX
    $(document).on('click', '#ms-show', function() {
        var role = $('#ms-role').val(), month = $('#ms-month').val(), year = $('#ms-year').val();
        $('#ms-container').html('<div style="padding:24px; text-align:center;"><i class="fa fa-spinner fa-spin text-primary" style="font-size:24px;"></i><p style="margin-top:8px; font-weight:600;">Loading monthly sheet...</p></div>');
        $.post("<?php echo site_url('admin/staffattendance/monthsheet'); ?>", { role: role, month: month, year: year }, function(html) {
            $('#ms-container').html(html);
        }).fail(function() {
            $('#ms-container').html('<div class="alert alert-danger" style="border-radius:8px;">Could not load the sheet. Please try again.</div>');
        });
    });

    // 3. Date Prev / Next Helper Buttons
    $('#btn-prev-day, #btn-next-day').on('click', function() {
        var isNext = $(this).attr('id') === 'btn-next-day';
        var dateField = $('#date_field');
        var val = dateField.val();
        if (!val) return;
        var d = new Date(val);
        if (isNaN(d.getTime())) {
            var parts = val.split(/[-/]/);
            if (parts.length === 3) {
                d = new Date(parts[2], parts[1] - 1, parts[0]);
            }
        }
        if (!isNaN(d.getTime())) {
            d.setDate(d.getDate() + (isNext ? 1 : -1));
            if (dateField.data('DateTimePicker')) {
                dateField.data('DateTimePicker').date(d);
            } else {
                var dd = String(d.getDate()).padStart(2, '0');
                var mm = String(d.getMonth() + 1).padStart(2, '0');
                var yyyy = d.getFullYear();
                dateField.val(dd + '-' + mm + '-' + yyyy);
            }
            $('#form1').submit();
        }
    });

    // 4. Time conversion helper
    function tConvert(time) {
        if (!time) return '';
        if (time.toString().match(/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/)) {
            var parts = time.split(":");
            var hours = parseInt(parts[0], 10);
            var minutes = parts[1];
            var ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12 || 12;
            return hours + ':' + minutes + ' ' + ampm;
        }
        return time;
    }

    function parseTimeMinutes(tStr) {
        if (!tStr || tStr === '00:00:00') return null;
        var m = tStr.match(/(\d+):(\d+)(?::(\d+))?\s*(AM|PM)?/i);
        if (!m) return null;
        var h = parseInt(m[1], 10);
        var min = parseInt(m[2], 10);
        var period = m[4] ? m[4].toUpperCase() : null;
        if (period === 'PM' && h < 12) h += 12;
        if (period === 'AM' && h === 12) h = 0;
        return (h * 60) + min;
    }

    // 5. Update Single Staff Status
    function setStaffAttendanceStatus(staffId, typeId, typeKey, typeName) {
        var $tr = $('tr[data-staff_id="' + staffId + '"]');
        var $input = $('#att_input_' + staffId);
        var $btn = $('#status_btn_' + staffId);
        var $inInput = $('#in_time_' + staffId);
        var $outInput = $('#out_time_' + staffId);

        // Update hidden input
        $input.val(typeId).data('type-key', typeKey).attr('data-type-key', typeKey);

        // Update button style & label
        $btn.removeClass('btn-status-present btn-status-late btn-status-absent btn-status-half_day btn-status-half_day_second_shift btn-status-holiday btn-status-unplanned_leave btn-status-on_duty btn-status-unmarked');
        $btn.addClass('btn-status-' + typeKey);
        $btn.find('.sa-status-text').text(typeName);

        // Update row tint
        $tr.removeClass('rt-present rt-late rt-absent rt-half_day rt-half_day_second_shift rt-holiday rt-unplanned_leave rt-on_duty');
        $tr.addClass('rt-' + typeKey);

        // Enable / Disable times if leave-like
        var intTypeId = parseInt(typeId, 10);
        if (leaveTypeIds.indexOf(intTypeId) !== -1) {
            $inInput.val('').prop('disabled', true);
            $outInput.val('').prop('disabled', true);
        } else {
            $inInput.prop('disabled', false);
            $outInput.prop('disabled', false);
        }

        updateLiveStats();
    }

    // 6. Calculate and update Row Tints, Stats, Durations & Shortfall
    function updateLiveStats() {
        var counts = {
            total: 0,
            present: 0,
            late: 0,
            absent: 0,
            half_day: 0,
            holiday: 0,
            missing: 0,
            shortfall: 0,
            qr: 0,
            manual: 0
        };

        $('#staff-attendance-table tbody tr').each(function() {
            var $tr = $(this);
            counts.total++;

            var $input = $tr.find('.att-hidden-input');
            var key = $input.data('type-key') || $input.attr('data-type-key') || '';

            if (key === 'present') counts.present++;
            else if (key === 'late') counts.late++;
            else if (key === 'absent' || key === 'unplanned_leave') counts.absent++;
            else if (key === 'half_day' || key === 'half_day_second_shift') counts.half_day++;
            else if (key === 'holiday') counts.holiday++;
            else if (key === 'on_duty') counts.on_duty = (counts.on_duty || 0) + 1;

            var inV = $tr.find('.in_time').val();
            var outV = $tr.find('.out_time').val();
            if (inV && inV !== '' && inV !== '00:00:00' && (!outV || outV === '' || outV === '00:00:00')) {
                counts.missing++;
            }

            var srcText = $tr.find('td:nth-child(7)').text().toLowerCase();
            if (srcText.indexOf('qr') !== -1 || srcText.indexOf('bio') !== -1) {
                counts.qr++;
            } else if (srcText.indexOf('manual') !== -1) {
                counts.manual++;
            }

            // Duration & Shortfall calculation
            var reqStr = $tr.attr('data-req_hours') || $tr.data('req_hours') || '08:00:00';
            var reqM = parseTimeMinutes(reqStr) || 480;
            var inM = parseTimeMinutes(inV);
            var outM = parseTimeMinutes(outV);
            var $durBadge = $tr.find('.sa-dur-badge');
            if (inM !== null && outM !== null && outM > inM) {
                var diff = outM - inM;
                var hrs = Math.floor(diff / 60);
                var mins = diff % 60;
                var text = (hrs > 0 ? hrs + 'h ' : '') + mins + 'm';
                $durBadge.removeClass('sa-dur-green sa-dur-amber sa-dur-gray sa-dur-shortfall').show();

                var shortM = reqM - diff;
                if (shortM > 5) { // more than 5 minutes shortfall
                    counts.shortfall++;
                    var shH = Math.floor(shortM / 60);
                    var shMin = shortM % 60;
                    var shText = '-' + (shH > 0 ? shH + 'h ' : '') + shMin + 'm';
                    $durBadge.addClass('sa-dur-shortfall').html('<i class="fa fa-arrow-down"></i> ' + text + ' <span style="font-weight:800; font-size:9.5px;">(' + shText + ')</span>');
                    $tr.attr('data-is_shortfall', '1');
                } else {
                    $durBadge.addClass('sa-dur-green').html('<i class="fa fa-check"></i> ' + text);
                    $tr.removeAttr('data-is_shortfall');
                }
            } else {
                $durBadge.hide();
                $tr.removeAttr('data-is_shortfall');
            }
        });

        $('#cnt-all').text(counts.total);
        $('#cnt-present').text(counts.present);
        $('#cnt-late').text(counts.late);
        $('#cnt-absent').text(counts.absent);
        $('#cnt-half_day').text(counts.half_day);
        $('#cnt-holiday').text(counts.holiday);
        $('#cnt-on_duty').text(counts.on_duty || 0);
        $('#cnt-missing').text(counts.missing);
        $('#cnt-shortfall').text(counts.shortfall);
        $('#cnt-qr').text(counts.qr);
        $('#cnt-manual').text(counts.manual);
    }

    // 7. Live Filter and Search
    function applyFilters() {
        var query = ($('#att-table-search').val() || '').toLowerCase().trim();
        var activeFilter = $('.sa-kpi-chip.active').data('filter') || 'all';

        $('#staff-attendance-table tbody tr').each(function() {
            var $tr = $(this);
            var text = $tr.text().toLowerCase();
            var matchesSearch = !query || text.indexOf(query) !== -1;

            var matchesFilter = true;
            var $input = $tr.find('.att-hidden-input');
            var key = $input.data('type-key') || $input.attr('data-type-key') || '';

            if (activeFilter === 'present') {
                matchesFilter = (key === 'present');
            } else if (activeFilter === 'late') {
                matchesFilter = (key === 'late');
            } else if (activeFilter === 'absent') {
                matchesFilter = (key === 'absent' || key === 'unplanned_leave');
            } else if (activeFilter === 'half_day') {
                matchesFilter = (key === 'half_day' || key === 'half_day_second_shift');
            } else if (activeFilter === 'holiday') {
                matchesFilter = (key === 'holiday');
            } else if (activeFilter === 'on_duty') {
                matchesFilter = (key === 'on_duty');
            } else if (activeFilter === 'missing-out') {
                var inV = $tr.find('.in_time').val();
                var outV = $tr.find('.out_time').val();
                matchesFilter = inV && inV !== '' && inV !== '00:00:00' && (!outV || outV === '' || outV === '00:00:00');
            } else if (activeFilter === 'shortfall') {
                matchesFilter = ($tr.attr('data-is_shortfall') === '1');
            } else if (activeFilter === 'biometric') {
                var src = ($tr.attr('data-source') || '').toLowerCase();
                var srcText = $tr.find('td:nth-child(7)').text().toLowerCase();
                matchesFilter = (src === 'biometric' || src === 'qr' || srcText.indexOf('bio') !== -1 || srcText.indexOf('qr') !== -1);
            } else if (activeFilter === 'manual') {
                var src = ($tr.attr('data-source') || '').toLowerCase();
                var srcText = $tr.find('td:nth-child(7)').text().toLowerCase();
                matchesFilter = (src === 'manual' || srcText.indexOf('manual') !== -1 || srcText.indexOf('-') !== -1);
            }

            if (matchesSearch && matchesFilter) {
                $tr.show();
            } else {
                $tr.hide();
            }
        });
    }

    // 7b. Table Sorting
    function applySorting() {
        var sortBy = $('#att-sort-select').val() || 'default';
        var $tbody = $('#staff-attendance-table tbody');
        var $rows = $tbody.find('tr').get();

        $rows.sort(function(a, b) {
            var $a = $(a), $b = $(b);

            if (sortBy === 'name_asc') {
                var nameA = ($a.attr('data-staff_name') || '').toLowerCase();
                var nameB = ($b.attr('data-staff_name') || '').toLowerCase();
                return nameA.localeCompare(nameB);
            } else if (sortBy === 'name_desc') {
                var nameA = ($a.attr('data-staff_name') || '').toLowerCase();
                var nameB = ($b.attr('data-staff_name') || '').toLowerCase();
                return nameB.localeCompare(nameA);
            } else if (sortBy === 'in_time_asc') {
                var timeA = $a.find('.in_time').val() || '';
                var timeB = $b.find('.in_time').val() || '';
                var minA = parseTimeMinutes(timeA);
                var minB = parseTimeMinutes(timeB);
                if (minA === null && minB === null) return 0;
                if (minA === null) return 1;
                if (minB === null) return -1;
                return minA - minB;
            } else if (sortBy === 'in_time_desc') {
                var timeA = $a.find('.in_time').val() || '';
                var timeB = $b.find('.in_time').val() || '';
                var minA = parseTimeMinutes(timeA);
                var minB = parseTimeMinutes(timeB);
                if (minA === null && minB === null) return 0;
                if (minA === null) return 1;
                if (minB === null) return -1;
                return minB - minA;
            } else if (sortBy === 'emp_id') {
                var idA = ($a.attr('data-employee_id') || '').toLowerCase();
                var idB = ($b.attr('data-employee_id') || '').toLowerCase();
                return idA.localeCompare(idB, undefined, { numeric: true, sensitivity: 'base' });
            } else if (sortBy === 'status') {
                var statOrder = { 'present': 1, 'late': 2, 'half_day': 3, 'half_day_second_shift': 4, 'absent': 5, 'unplanned_leave': 6, 'holiday': 7, 'unmarked': 8 };
                var keyA = $a.find('.att-hidden-input').data('type-key') || 'unmarked';
                var keyB = $b.find('.att-hidden-input').data('type-key') || 'unmarked';
                var ordA = statOrder[keyA] || 99;
                var ordB = statOrder[keyB] || 99;
                return ordA - ordB;
            } else if (sortBy === 'shortfall') {
                var sfA = $a.attr('data-is_shortfall') === '1' ? 1 : 0;
                var sfB = $b.attr('data-is_shortfall') === '1' ? 1 : 0;
                return sfB - sfA;
            } else {
                // Default index (#)
                var idxA = parseInt($a.find('td:first-child span').text(), 10) || 0;
                var idxB = parseInt($b.find('td:first-child span').text(), 10) || 0;
                return idxA - idxB;
            }
        });

        $.each($rows, function(idx, row) {
            $tbody.append(row);
        });
    }

    $('#att-table-search').on('keyup input', applyFilters);

    $('#att-sort-select').on('change', function() {
        applySorting();
        applyFilters();
    });

    $('.sa-kpi-chip').on('click', function() {
        var filter = $(this).data('filter');
        if (!filter) return;
        $('.sa-kpi-chip').removeClass('active');
        $(this).addClass('active');
        applyFilters();
    });

    // 8. Single Staff Status Dropdown Click Handler
    $(document).on('click', '.sa-status-opt', function() {
        var staffId = $(this).data('staff-id');
        var typeId = $(this).data('type-id');
        var typeKey = $(this).data('type-key');
        var typeName = $(this).data('type-name');

        setStaffAttendanceStatus(staffId, typeId, typeKey, typeName);
    });

    // 9. Bulk "Set All" Attendance
    $(document).on('click', '.bulk-set-att', function() {
        var typeId = $(this).data('type-id');
        var typeKey = $(this).data('type-key');
        var typeName = $(this).data('type-name');

        $('#staff-attendance-table tbody tr').each(function() {
            var staffId = $(this).attr('data-staff_id');
            if (staffId) {
                setStaffAttendanceStatus(staffId, typeId, typeKey, typeName);
            }
        });
    });

    // 10. Compliance Checklist Individual & Bulk Toggles
    function syncCmpChip($chk) {
        var isChecked = $chk.is(':checked');
        var $chip = $chk.closest('.sa-cmp-chip');
        var $icon = $chip.find('i');
        if (isChecked) {
            $chip.removeClass('is-no').addClass('is-yes');
            $icon.removeClass('fa-times').addClass('fa-check');
        } else {
            $chip.removeClass('is-yes').addClass('is-no');
            $icon.removeClass('fa-check').addClass('fa-times');
        }
    }

    $(document).on('change', '.cmp-check', function() {
        syncCmpChip($(this));
    });

    $('.bulk-set-all-cmp').on('click', function() {
        var state = $(this).data('val') === true || $(this).data('val') === 'true';
        $('.cmp-check').prop('checked', state).each(function() {
            syncCmpChip($(this));
        });
    });

    // 11. Clear Time Helper Button
    $(document).on('click', '.clear-time-btn', function() {
        var targetId = $(this).data('target');
        $('#' + targetId).val('').trigger('change');
        updateLiveStats();
    });

    $(document).on('change input', '.in_time, .out_time', function() {
        updateLiveStats();
    });

    $(document).on('input change', '.sa-note-input', function() {
        $(this).attr('title', $(this).val());
    });

    // 12. Lazy Timepicker on Focus
    $(document).on('focus', '.sa-time-input:not([disabled])', function() {
        var $input = $(this);
        if (!$input.data('DateTimePicker') && typeof $.fn.datetimepicker === 'function') {
            $input.datetimepicker({
                format: 'LT',
                keepOpen: false
            });
            $input.data('DateTimePicker').show();
        }
    });

    // 13. Keyboard Shortcut Ctrl+S / Cmd+S for instant save
    $(document).on('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            var $saveBtn = $('#saveattendence');
            if ($saveBtn.length) {
                $('#save_attendance').submit();
            }
        }
    });

    // 14. Quick Biometric Sync AJAX
    $('#btn-quick-sync-biometric').on('click', function() {
        var $btn = $(this);
        var originalHtml = $btn.html();
        var selectedDate = $('#date_field').val() || '';
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Syncing...');
        $.post("<?php echo site_url('admin/staffattendance/sync_biometric_ajax'); ?>", {
            date: selectedDate
        }, function(res) {
            $btn.prop('disabled', false).html(originalHtml);
            if (res.status === 'success') {
                alert(res.message);
                location.reload();
            } else {
                alert(res.message || 'Biometric synchronization failed.');
            }
        }, 'json').fail(function() {
            $btn.prop('disabled', false).html(originalHtml);
            alert('Failed to contact server for biometric synchronization.');
        });
    });

    // Initial load
    updateLiveStats();
    if ($.fn.tooltip) {
        $('[data-toggle="tooltip"]').tooltip({ container: 'body' });
    }

})(jQuery);

/* ==========================================================================
   STAFF ATTENDANCE WHATSAPP SHARE & AUTO-SCREENSHOT HUB
   ========================================================================== */
var currentActiveStaffShareTab = 'all';

function extractLiveStaffAttendanceData() {
    var data = [];
    var $rows = $('#staff-attendance-table tbody tr');
    
    $rows.each(function(idx) {
        var $tr = $(this);
        var staffId = $tr.attr('data-staff_id') || $tr.find('input[name="student_session[]"]').val() || '';
        var employeeId = $tr.attr('data-employee_id') || '';
        var staffName = $tr.attr('data-staff_name') || '';
        var userType = $tr.attr('data-user_type') || '';
        var contactNo = $tr.attr('data-contact_no') || '';

        var $hiddenInput = $tr.find('.att-hidden-input');
        var statusKey = $hiddenInput.data('type-key') || $hiddenInput.attr('data-type-key') || 'unmarked';
        var attTypeName = $tr.find('.sa-status-text').text().trim() || 'Unmarked';

        var inTime = $tr.find('.in_time').val() || '';
        var outTime = $tr.find('.out_time').val() || '';
        var duration = $tr.find('.sa-dur-badge').text().trim() || '';
        var remark = $tr.find('input[name^="remark"]').val() || '';

        var isUniform = $tr.find('.uniform-check').is(':checked');
        var isIdCard = $tr.find('.idcard-check').is(':checked');
        var isLessonPlan = $tr.find('.lessonplan-check').is(':checked');
        var isPhone = $tr.find('.phone-check').is(':checked');

        data.push({
            index: idx + 1,
            staff_id: staffId,
            employee_id: employeeId,
            name: staffName,
            role: userType,
            contact_no: contactNo,
            status_key: statusKey,
            status_name: attTypeName,
            in_time: inTime,
            out_time: outTime,
            duration: duration,
            remark: remark,
            compliance: {
                uniform: isUniform,
                id_card: isIdCard,
                lesson_plan: isLessonPlan,
                phone: isPhone
            }
        });
    });

    return data;
}

function openStaffAttendanceShareModal(initialTabKey) {
    var liveData = extractLiveStaffAttendanceData();
    if (!liveData || liveData.length === 0) {
        alert('Please search and load the staff attendance list first to share.');
        return;
    }

    renderStaffShareTabs(liveData);
    var targetKey = initialTabKey || 'all';
    switchStaffShareTab(targetKey);
    $('#staffAttendanceShareModal').modal('show');
}

function renderStaffShareTabs(liveData) {
    var container = $('#staff-share-tabs-container');
    container.empty();

    var counts = {
        all: liveData.length,
        absent: 0,
        late: 0,
        present: 0,
        half_day: 0,
        missing_out: 0
    };

    $.each(liveData, function(i, item) {
        if (counts[item.status_key] !== undefined) {
            counts[item.status_key]++;
        }
        if (item.in_time && item.in_time !== '' && item.in_time !== '00:00:00' && (!item.out_time || item.out_time === '' || item.out_time === '00:00:00')) {
            counts.missing_out++;
        }
    });

    var allActive = (currentActiveStaffShareTab === 'all') ? 'active' : '';
    container.append(
        '<button type="button" class="att-share-tab-pill tab-all ' + allActive + '" onclick="switchStaffShareTab(\'all\')">' +
            '<i class="fa fa-users"></i> <span>All Staff</span>' +
            '<span class="att-tab-badge">' + counts.all + '</span>' +
        '</button>'
    );

    var absentActive = (currentActiveStaffShareTab === 'absent') ? 'active' : '';
    container.append(
        '<button type="button" class="att-share-tab-pill tab-absent ' + absentActive + '" onclick="switchStaffShareTab(\'absent\')">' +
            '<i class="fa fa-times-circle text-danger"></i> <span>Absent Staff</span>' +
            '<span class="att-tab-badge" style="background:#fee2e2; color:#dc2626;">' + counts.absent + '</span>' +
        '</button>'
    );

    var lateActive = (currentActiveStaffShareTab === 'late') ? 'active' : '';
    container.append(
        '<button type="button" class="att-share-tab-pill tab-late ' + lateActive + '" onclick="switchStaffShareTab(\'late\')">' +
            '<i class="fa fa-clock-o text-warning"></i> <span>Late Arrivals</span>' +
            '<span class="att-tab-badge" style="background:#fef3c7; color:#d97706;">' + counts.late + '</span>' +
        '</button>'
    );

    var presentActive = (currentActiveStaffShareTab === 'present') ? 'active' : '';
    container.append(
        '<button type="button" class="att-share-tab-pill tab-present ' + presentActive + '" onclick="switchStaffShareTab(\'present\')">' +
            '<i class="fa fa-check-circle text-success"></i> <span>Present</span>' +
            '<span class="att-tab-badge" style="background:#dcfce7; color:#15803d;">' + counts.present + '</span>' +
        '</button>'
    );

    if (counts.half_day > 0) {
        var halfActive = (currentActiveStaffShareTab === 'half_day') ? 'active' : '';
        container.append(
            '<button type="button" class="att-share-tab-pill tab-half_day ' + halfActive + '" onclick="switchStaffShareTab(\'half_day\')">' +
                '<i class="fa fa-adjust text-info"></i> <span>Half Day</span>' +
                '<span class="att-tab-badge">' + counts.half_day + '</span>' +
            '</button>'
        );
    }

    if (counts.missing_out > 0) {
        var missActive = (currentActiveStaffShareTab === 'missing_out') ? 'active' : '';
        container.append(
            '<button type="button" class="att-share-tab-pill tab-missing_out ' + missActive + '" onclick="switchStaffShareTab(\'missing_out\')">' +
                '<i class="fa fa-exclamation-triangle text-warning"></i> <span>Missing Exit Time</span>' +
                '<span class="att-tab-badge" style="background:#fffbeb; color:#b45309;">' + counts.missing_out + '</span>' +
            '</button>'
        );
    }
}

function switchStaffShareTab(tabKey) {
    currentActiveStaffShareTab = tabKey;
    $('#staff-share-tabs-container .att-share-tab-pill').removeClass('active');
    $('#staff-share-tabs-container button').each(function() {
        var onclickAttr = $(this).attr('onclick') || '';
        if (onclickAttr.indexOf("'" + tabKey + "'") !== -1) {
            $(this).addClass('active');
        }
    });
    renderStaffScreenshotCard(tabKey);
}

function filterStaffDataByTab(liveData, tabKey) {
    if (tabKey === 'all') return liveData;
    if (tabKey === 'absent') return liveData.filter(function(i) { return i.status_key === 'absent' || i.status_key === 'unplanned_leave'; });
    if (tabKey === 'late') return liveData.filter(function(i) { return i.status_key === 'late'; });
    if (tabKey === 'present') return liveData.filter(function(i) { return i.status_key === 'present'; });
    if (tabKey === 'half_day') return liveData.filter(function(i) { return i.status_key === 'half_day' || i.status_key === 'half_day_second_shift'; });
    if (tabKey === 'holiday') return liveData.filter(function(i) { return i.status_key === 'holiday'; });
    if (tabKey === 'missing_out') return liveData.filter(function(i) {
        return i.in_time && i.in_time !== '' && i.in_time !== '00:00:00' && (!i.out_time || i.out_time === '' || i.out_time === '00:00:00');
    });
    return liveData;
}

function renderStaffScreenshotCard(tabKey) {
    var card = $('#staff-screenshot-card');
    card.empty();
    $('#staff-share-feedback-alert').hide();

    var allData = extractLiveStaffAttendanceData();
    var filteredData = filterStaffDataByTab(allData, tabKey);

    var titleBadge = 'Daily Staff Attendance Report';
    var titleColor = '#15803d';
    var titleBg = '#dcfce7';

    if (tabKey === 'absent') {
        titleBadge = 'Absent Staff Alert';
        titleColor = '#b91c1c';
        titleBg = '#fee2e2';
        $('#staff-share-selected-desc').text('Absent Staff (' + filteredData.length + ' staff)');
    } else if (tabKey === 'late') {
        titleBadge = 'Late Arrivals Report';
        titleColor = '#b45309';
        titleBg = '#fef3c7';
        $('#staff-share-selected-desc').text('Late Staff (' + filteredData.length + ' staff)');
    } else if (tabKey === 'present') {
        titleBadge = 'Present Staff List';
        titleColor = '#15803d';
        titleBg = '#dcfce7';
        $('#staff-share-selected-desc').text('Present Staff (' + filteredData.length + ' staff)');
    } else if (tabKey === 'missing_out') {
        titleBadge = 'Missing Exit Punches';
        titleColor = '#c2410c';
        titleBg = '#ffedd5';
        $('#staff-share-selected-desc').text('Missing Exit Time (' + filteredData.length + ' staff)');
    } else {
        $('#staff-share-selected-desc').text('All Staff (' + filteredData.length + ' staff)');
    }

    if (filteredData.length === 0) {
        card.html('<div style="text-align:center; padding: 40px 20px; color:#64748b;">' +
            '<i class="fa fa-check-circle text-success" style="font-size: 40px; margin-bottom: 12px; display:block;"></i>' +
            '<h4 style="font-weight:700; color:#0f172a; margin:0 0 6px 0;">No Staff in this Category</h4>' +
            '<p style="font-size:13px; margin:0;">Zero staff records found matching the "' + escapeStaffHtml(titleBadge) + '" filter for today.</p>' +
        '</div>');
        return;
    }

    var countPresent = allData.filter(function(x) { return x.status_key === 'present'; }).length;
    var countLate = allData.filter(function(x) { return x.status_key === 'late'; }).length;
    var countAbsent = allData.filter(function(x) { return x.status_key === 'absent' || x.status_key === 'unplanned_leave'; }).length;
    var countHalf = allData.filter(function(x) { return x.status_key === 'half_day' || x.status_key === 'half_day_second_shift'; }).length;

    var html = '';
    html += '<div style="display:flex; justify-content:space-between; align-items:flex-start; border-bottom:2px solid #e2e8f0; padding-bottom:14px; margin-bottom:16px;">';
    html += '  <div>';
    html += '    <h3 style="margin:0 0 4px 0; font-size:18px; font-weight:800; color:#0f172a; letter-spacing:-0.3px;">' + escapeStaffHtml(<?php echo json_encode(!empty($sch_setting->name) ? $sch_setting->name : 'School Management System'); ?>) + '</h3>';
    html += '    <div style="font-size:13px; font-weight:700; display:flex; align-items:center; gap:8px;">';
    html += '      <span style="background:' + titleBg + '; color:' + titleColor + '; padding:2px 8px; border-radius:4px; font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:0.5px;">' + escapeStaffHtml(titleBadge) + '</span>';
    html += '      <span style="color:#475569; font-size:12px;">Role: <strong>' + escapeStaffHtml(<?php echo json_encode(isset($user_type_id) && $user_type_id && $user_type_id !== 'select' ? $user_type_id : 'All Staff'); ?>) + '</strong></span>';
    html += '    </div>';
    html += '  </div>';
    html += '  <div style="text-align:right;">';
    html += '    <div style="font-size:11px; color:#64748b; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Attendance Date</div>';
    html += '    <div style="font-size:14px; font-weight:700; color:#0f172a; margin-top:2px;"><i class="fa fa-calendar-check-o text-success"></i> ' + escapeStaffHtml(<?php echo json_encode(isset($date) && $date ? $date : date($this->customlib->getSchoolDateFormat())); ?>) + '</div>';
    html += '  </div>';
    html += '</div>';

    html += '<div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:10px 14px; margin-bottom:16px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px;">';
    html += '  <div style="display:flex; align-items:center; gap:12px; flex-wrap:wrap;">';
    html += '    <span style="font-size:12px; color:#334155;">Total: <strong>' + allData.length + '</strong></span>';
    html += '    <span style="font-size:12px; color:#15803d; font-weight:700;"><i class="fa fa-check-circle"></i> Present: ' + countPresent + '</span>';
    html += '    <span style="font-size:12px; color:#b45309; font-weight:700;"><i class="fa fa-clock-o"></i> Late: ' + countLate + '</span>';
    html += '    <span style="font-size:12px; color:#b91c1c; font-weight:700;"><i class="fa fa-times-circle"></i> Absent: ' + countAbsent + '</span>';
    if (countHalf > 0) {
        html += '    <span style="font-size:12px; color:#1d4ed8; font-weight:700;"><i class="fa fa-adjust"></i> Half Day: ' + countHalf + '</span>';
    }
    html += '  </div>';
    html += '  <div style="font-size:11.5px; color:#64748b; font-weight:600;">Showing <strong>' + filteredData.length + '</strong> record(s)</div>';
    html += '</div>';

    html += '<table style="width:100%; border-collapse:collapse; font-size:12px;">';
    html += '  <thead>';
    html += '    <tr style="background:#f8fafc; border-bottom:2px solid #cbd5e1; text-align:left; color:#475569; font-size:11px; text-transform:uppercase; letter-spacing:0.5px;">';
    html += '      <th style="padding:8px 8px; width:30px;">#</th>';
    html += '      <th style="padding:8px 8px;">Staff Details</th>';
    html += '      <th style="padding:8px 8px;">Role</th>';
    html += '      <th style="padding:8px 8px;">Status</th>';
    html += '      <th style="padding:8px 8px;">In / Out Time</th>';
    html += '      <th style="padding:8px 8px;">Compliance</th>';
    html += '      <th style="padding:8px 8px;">Contact</th>';
    html += '    </tr>';
    html += '  </thead>';
    html += '  <tbody>';

    $.each(filteredData, function(idx, item) {
        var bgRow = (idx % 2 === 0) ? '#ffffff' : '#f8fafc';
        var badgeStyle = 'background:#f1f5f9; color:#475569; border:1px solid #cbd5e1;';
        if (item.status_key === 'present') badgeStyle = 'background:#dcfce7; color:#15803d; border:1px solid #bbf7d0;';
        else if (item.status_key === 'late') badgeStyle = 'background:#fef3c7; color:#b45309; border:1px solid #fde68a;';
        else if (item.status_key === 'absent' || item.status_key === 'unplanned_leave') badgeStyle = 'background:#fee2e2; color:#dc2626; border:1px solid #fecaca;';
        else if (item.status_key === 'half_day' || item.status_key === 'half_day_second_shift') badgeStyle = 'background:#dbeafe; color:#1e40af; border:1px solid #bfdbfe;';

        var timeDisplay = '-';
        if (item.in_time || item.out_time) {
            timeDisplay = '<strong>In:</strong> ' + (item.in_time || '-') + (item.out_time ? ' | <strong>Out:</strong> ' + item.out_time : '');
            if (item.duration) {
                timeDisplay += '<br><span style="font-size:10.5px; color:#15803d; font-weight:600;">⏱️ ' + escapeStaffHtml(item.duration) + '</span>';
            }
        }

        var cmpHtml = '<div style="display:flex; gap:3px; flex-wrap:wrap;">';
        cmpHtml += item.compliance.uniform ? '<span style="background:#e6f4ea; color:#1e7e34; padding:1px 4px; border-radius:3px; font-size:10px;">✓ Uni</span>' : '<span style="background:#fef2f2; color:#dc3545; padding:1px 4px; border-radius:3px; font-size:10px;">✗ Uni</span>';
        cmpHtml += item.compliance.id_card ? '<span style="background:#e6f4ea; color:#1e7e34; padding:1px 4px; border-radius:3px; font-size:10px;">✓ ID</span>' : '<span style="background:#fef2f2; color:#dc3545; padding:1px 4px; border-radius:3px; font-size:10px;">✗ ID</span>';
        cmpHtml += item.compliance.lesson_plan ? '<span style="background:#e6f4ea; color:#1e7e34; padding:1px 4px; border-radius:3px; font-size:10px;">✓ LP</span>' : '<span style="background:#fef2f2; color:#dc3545; padding:1px 4px; border-radius:3px; font-size:10px;">✗ LP</span>';
        cmpHtml += item.compliance.phone ? '<span style="background:#e6f4ea; color:#1e7e34; padding:1px 4px; border-radius:3px; font-size:10px;">✓ Phone</span>' : '<span style="background:#fef2f2; color:#dc3545; padding:1px 4px; border-radius:3px; font-size:10px;">✗ Phone</span>';
        cmpHtml += '</div>';

        html += '    <tr style="background:' + bgRow + '; border-bottom:1px solid #e2e8f0;">';
        html += '      <td style="padding:8px 8px; font-weight:700; color:#64748b; vertical-align:middle;">' + (idx + 1) + '</td>';
        html += '      <td style="padding:8px 8px; vertical-align:middle;">';
        html += '        <div style="font-weight:700; color:#0f172a; font-size:13px;">' + escapeStaffHtml(item.name) + '</div>';
        if (item.employee_id) {
            html += '        <div style="font-size:10.5px; color:#64748b; margin-top:1px;"><code style="background:#f1f5f9; padding:1px 4px; border-radius:3px;">' + escapeStaffHtml(item.employee_id) + '</code></div>';
        }
        if (item.remark) {
            html += '        <div style="font-size:10.5px; color:#64748b; font-style:italic; margin-top:2px;">Note: ' + escapeStaffHtml(item.remark) + '</div>';
        }
        html += '      </td>';
        html += '      <td style="padding:8px 8px; vertical-align:middle;"><span style="background:#e2e8f0; color:#334155; padding:2px 6px; border-radius:4px; font-size:11px; font-weight:600;">' + escapeStaffHtml(item.role) + '</span></td>';
        html += '      <td style="padding:8px 8px; vertical-align:middle;"><span style="' + badgeStyle + ' padding:2px 8px; border-radius:12px; font-size:11px; font-weight:700;">' + escapeStaffHtml(item.status_name) + '</span></td>';
        html += '      <td style="padding:8px 8px; vertical-align:middle; font-size:11.5px; color:#334155;">' + timeDisplay + '</td>';
        html += '      <td style="padding:8px 8px; vertical-align:middle;">' + cmpHtml + '</td>';
        html += '      <td style="padding:8px 8px; vertical-align:middle; font-weight:600; color:#0f172a; white-space:nowrap; font-size:11.5px;">';
        if (item.contact_no) {
            html += '        <i class="fa fa-phone text-muted" style="margin-right:2px;"></i> ' + escapeStaffHtml(item.contact_no);
        } else {
            html += '        <span style="color:#94a3b8;">-</span>';
        }
        html += '      </td>';
        html += '    </tr>';
    });

    html += '  </tbody>';
    html += '</table>';

    html += '<div style="margin-top:16px; padding-top:10px; border-top:2px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; font-size:11px; color:#64748b;">';
    html += '  <div><strong>Report:</strong> ' + escapeStaffHtml(titleBadge) + ' | ' + escapeStaffHtml(<?php echo json_encode(!empty($sch_setting->name) ? $sch_setting->name : 'School Management System'); ?>) + '</div>';
    html += '  <div style="text-align:right;">Generated on <strong>' + escapeStaffHtml(<?php echo json_encode(isset($date) && $date ? $date : date($this->customlib->getSchoolDateFormat())); ?>) + '</strong> | LMS Staff Attendance</div>';
    html += '</div>';

    card.html(html);
}

function getStaffWhatsAppShareText(tabKey) {
    var allData = extractLiveStaffAttendanceData();
    var filteredData = filterStaffDataByTab(allData, tabKey);
    var schoolName = <?php echo json_encode(!empty($sch_setting->name) ? $sch_setting->name : 'School Management System'); ?>;
    var selectedDate = <?php echo json_encode(isset($date) && $date ? $date : date($this->customlib->getSchoolDateFormat())); ?>;
    var selectedRole = <?php echo json_encode(isset($user_type_id) && $user_type_id && $user_type_id !== 'select' ? $user_type_id : 'All Staff'); ?>;

    var countPresent = allData.filter(function(x) { return x.status_key === 'present'; }).length;
    var countLate = allData.filter(function(x) { return x.status_key === 'late'; }).length;
    var countAbsent = allData.filter(function(x) { return x.status_key === 'absent' || x.status_key === 'unplanned_leave'; }).length;
    var countHalf = allData.filter(function(x) { return x.status_key === 'half_day' || x.status_key === 'half_day_second_shift'; }).length;

    var text = "";
    if (tabKey === 'absent') {
        text += "🚫 *ABSENT STAFF REPORT*\n";
        text += "🏫 *" + schoolName + "*\n";
        text += "📅 *Date:* " + selectedDate + "\n";
        text += "👥 *Role:* " + selectedRole + "\n";
        text += "⚠️ *Total Absent: " + filteredData.length + " staff member(s)*\n\n";

        if (filteredData.length === 0) {
            text += "✅ *Zero Absentees! All staff are present/on duty today.*\n";
        } else {
            $.each(filteredData, function(idx, item) {
                var empStr = item.employee_id ? " (" + item.employee_id + ")" : "";
                text += (idx + 1) + ". *" + item.name + "*" + empStr + "\n";
                text += "   • Role: " + item.role + "\n";
                if (item.contact_no) text += "   • Contact: " + item.contact_no + "\n";
                if (item.remark) text += "   • Note: " + item.remark + "\n";
                text += "\n";
            });
            text += "_Please arrange substitute duties/periods accordingly._\n";
        }
    } else if (tabKey === 'late') {
        text += "⏰ *LATE ARRIVALS REPORT*\n";
        text += "🏫 *" + schoolName + "*\n";
        text += "📅 *Date:* " + selectedDate + "\n";
        text += "👥 *Role:* " + selectedRole + "\n";
        text += "⚠️ *Total Late Arrivals: " + filteredData.length + " staff member(s)*\n\n";

        if (filteredData.length === 0) {
            text += "✅ *No late arrivals today. Everyone arrived on time!*\n";
        } else {
            $.each(filteredData, function(idx, item) {
                var empStr = item.employee_id ? " (" + item.employee_id + ")" : "";
                text += (idx + 1) + ". *" + item.name + "*" + empStr + "\n";
                text += "   • Role: " + item.role + "\n";
                text += "   • In-Time: *" + (item.in_time || "N/A") + "*\n";
                if (item.contact_no) text += "   • Contact: " + item.contact_no + "\n";
                text += "\n";
            });
        }
    } else {
        text += "📋 *DAILY STAFF ATTENDANCE REPORT*\n";
        text += "🏫 *" + schoolName + "*\n";
        text += "📅 *Date:* " + selectedDate + "\n";
        text += "👥 *Role Filter:* " + selectedRole + "\n\n";

        text += "📊 *ATTENDANCE SUMMARY:*\n";
        text += "• Total Staff: *" + allData.length + "*\n";
        text += "• ✅ Present: *" + countPresent + "*\n";
        text += "• ⏰ Late: *" + countLate + "*\n";
        text += "• ❌ Absent: *" + countAbsent + "*\n";
        if (countHalf > 0) text += "• ⏳ Half Day: *" + countHalf + "*\n";
        text += "\n";

        if (countAbsent > 0) {
            var absentList = allData.filter(function(x) { return x.status_key === 'absent' || x.status_key === 'unplanned_leave'; });
            text += "🚫 *Absent Staff (" + countAbsent + "):*\n";
            $.each(absentList, function(idx, item) {
                var empStr = item.employee_id ? " [" + item.employee_id + "]" : "";
                text += (idx + 1) + ". " + item.name + empStr + " (" + item.role + ")" + (item.contact_no ? " 📞 " + item.contact_no : "") + "\n";
            });
            text += "\n";
        }

        if (countLate > 0) {
            var lateList = allData.filter(function(x) { return x.status_key === 'late'; });
            text += "⏰ *Late Arrivals (" + countLate + "):*\n";
            $.each(lateList, function(idx, item) {
                var empStr = item.employee_id ? " [" + item.employee_id + "]" : "";
                text += (idx + 1) + ". " + item.name + empStr + " (In: " + item.in_time + ")\n";
            });
            text += "\n";
        }
        text += "_Generated via LMS Staff Attendance System_";
    }
    return text;
}

function shareStaffOnWhatsApp() {
    var text = getStaffWhatsAppShareText(currentActiveStaffShareTab);
    var waUrl = 'https://api.whatsapp.com/send?text=' + encodeURIComponent(text);
    window.open(waUrl, '_blank');
}

function copyStaffShareText() {
    var text = getStaffWhatsAppShareText(currentActiveStaffShareTab);
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(function() {
            showStaffShareFeedback('✅ Formatted WhatsApp text copied to clipboard! Paste directly into WhatsApp.');
        }).catch(function() {
            fallbackCopyStaffText(text);
        });
    } else {
        fallbackCopyStaffText(text);
    }
}

function fallbackCopyStaffText(text) {
    var tempInput = $('<textarea>');
    $('body').append(tempInput);
    tempInput.val(text).select();
    document.execCommand('copy');
    tempInput.remove();
    showStaffShareFeedback('✅ Formatted WhatsApp text copied to clipboard! Paste directly into WhatsApp.');
}

function copyStaffCardScreenshot() {
    var cardElem = document.getElementById('staff-screenshot-card');
    var btn = $('#btn-copy-staff-screenshot');
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
                    showStaffShareFeedback('🎉 <strong>HD Screenshot Copied!</strong> Simply open WhatsApp and press <code>Ctrl + V</code> to send.');
                }).catch(function() {
                    downloadStaffCanvasAsPng(canvas);
                    showStaffShareFeedback('💾 Screenshot downloaded as image!');
                });
            } else {
                downloadStaffCanvasAsPng(canvas);
                showStaffShareFeedback('💾 Screenshot downloaded as image!');
            }
        }, 'image/png');
    }).catch(function(err) {
        btn.prop('disabled', false).html(originalBtnHtml);
        showStaffShareFeedback('❌ Could not generate screenshot: ' + err.message, true);
    });
}

function downloadStaffCardScreenshot() {
    var cardElem = document.getElementById('staff-screenshot-card');
    var btn = $('#btn-download-staff-screenshot');
    var originalBtnHtml = btn.html();

    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

    html2canvas(cardElem, {
        scale: 2,
        useCORS: true,
        backgroundColor: '#ffffff',
        logging: false
    }).then(function(canvas) {
        btn.prop('disabled', false).html(originalBtnHtml);
        downloadStaffCanvasAsPng(canvas);
        showStaffShareFeedback('💾 Image saved successfully!');
    }).catch(function(err) {
        btn.prop('disabled', false).html(originalBtnHtml);
        showStaffShareFeedback('❌ Could not download screenshot: ' + err.message, true);
    });
}

function downloadStaffCanvasAsPng(canvas) {
    var safeTab = currentActiveStaffShareTab.replace(/[^a-zA-Z0-9_-]/g, '_');
    var safeDate = (<?php echo json_encode(isset($date) && $date ? $date : date('Y-m-d')); ?>).replace(/[^a-zA-Z0-9_-]/g, '_');
    var filename = 'Staff_Attendance_' + safeTab + '_' + safeDate + '.png';
    var link = document.createElement('a');
    link.download = filename;
    link.href = canvas.toDataURL('image/png');
    link.click();
}

function showStaffShareFeedback(msg, isError) {
    var alertBox = $('#staff-share-feedback-alert');
    var bg = isError ? '#fef2f2' : '#f0fdf4';
    var color = isError ? '#991b1b' : '#166534';
    var border = isError ? '#f87171' : '#86efac';

    alertBox.css({
        'background': bg,
        'color': color,
        'border': '1px solid ' + border,
        'display': 'block'
    }).html(msg);

    setTimeout(function() {
        alertBox.slideUp(300);
    }, 5000);
}

function escapeStaffHtml(text) {
    if (!text) return '';
    return String(text)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}
</script>