<style type="text/css">
    .radio {
        padding-left: 20px;
    }

    .radio label {
        display: inline-block;
        vertical-align: middle;
        position: relative;
        padding-left: 5px;
    }

    .radio label::before {
        content: "";
        display: inline-block;
        position: absolute;
        width: 17px;
        height: 17px;
        left: 0;
        margin-left: -20px;
        border: 1px solid var(--bs-primary);
        border-radius: 50%;
        background-color: var(--bs-input-bg);
        -webkit-transition: border 0.15s ease-in-out;
        -o-transition: border 0.15s ease-in-out;
        transition: border 0.15s ease-in-out;
    }

    .radio label::after {
        display: inline-block;
        position: absolute;
        content: " ";
        width: 11px;
        height: 11px;
        left: 3px;
        top: 3px;
        margin-left: -20px;
        border-radius: 50%;
        background-color: #555555;
        -webkit-transform: scale(0, 0);
        -ms-transform: scale(0, 0);
        -o-transform: scale(0, 0);
        transform: scale(0, 0);
        -webkit-transition: -webkit-transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);
        -moz-transition: -moz-transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);
        -o-transition: -o-transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);
        transition: transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);
    }

    .radio input[type="radio"] {
        opacity: 0;
        z-index: 1;
    }

    .radio input[type="radio"]:focus+label::before {
        outline: thin dotted;
        outline: 5px auto -webkit-focus-ring-color;
        outline-offset: -2px;
    }

    .radio input[type="radio"]:checked+label::after {
        -webkit-transform: scale(1, 1);
        -ms-transform: scale(1, 1);
        -o-transform: scale(1, 1);
        transform: scale(1, 1);
    }

    .radio input[type="radio"]:disabled+label {
        opacity: 0.65;
    }

    .radio input[type="radio"]:disabled+label::before {
        cursor: not-allowed;
    }

    .radio.radio-inline {
        margin-top: 0;
        margin-right: 10px;
        margin-left: 0;
    }

    .radio-primary input[type="radio"]+label::after {
        background-color: var(--bs-primary);
    }

    .radio-primary input[type="radio"]:checked+label::before {
        border-color: var(--bs-primary);
    }

    .radio-primary input[type="radio"]:checked+label::after {
        background-color: var(--bs-primary);
    }

    .radio-danger input[type="radio"]+label::after {
        background-color: #d9534f;
    }

    .radio-danger input[type="radio"]:checked+label::before {
        border-color: #d9534f;
    }

    .radio-danger input[type="radio"]:checked+label::after {
        background-color: #d9534f;
    }

    .radio-info input[type="radio"]+label::after {
        background-color: var(--bs-primary);
    }

    .radio-info input[type="radio"]:checked+label::before {
        border-color: var(--bs-primary);
    }

    .radio-info input[type="radio"]:checked+label::after {
        background-color: var(--bs-primary);
    }

    @media (max-width:767px) {
        .radio.radio-inline {
            display: block;
            margin-left: 0;
        }
    }

    /* ===== Compliance Compact Badge Checkboxes ===== */
    .cmp-group { display: flex; flex-wrap: wrap; gap: 4px 6px; align-items: center; }
    .cmp-pill { display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; cursor: pointer; user-select: none; border: 1px solid #d1d5db; background: #f8fafc; color: #64748b; transition: all 0.15s ease; margin: 0; }
    .cmp-pill input { display: none; }
    .cmp-pill.active { background: #e6f4ea; color: #1e7e34; border-color: #a8dab5; }
    .cmp-pill:not(.active) { background: #fef2f2; color: #dc3545; border-color: #f5c6cb; }
    .cmp-pill i { font-size: 10px; }

    /* ===== Page polish ===== */
    #staffatt-legend { display:flex; flex-wrap:wrap; gap:14px; align-items:center; font-size:12px; color:#555; padding:8px 4px 0; }
    #staffatt-legend .lg-item { display:inline-flex; align-items:center; gap:6px; }
    #staffatt-legend .lg-dot { width:10px; height:10px; border-radius:50%; display:inline-block; }
    .staffatt-table thead th { background:#f7f9fb; color:#41505f; font-size:12px; text-transform:uppercase; letter-spacing:.3px; border-bottom:2px solid #e4e9ef !important; vertical-align:middle; white-space:nowrap; }
    .staffatt-table tbody td { vertical-align:middle !important; }
    .staffatt-table tbody tr:hover td { background:#f3f8ff !important; }
    .staffatt-table .radio-inline { margin-right:8px; }
    .staffatt-hint { color:#8a93a2; font-size:12px; margin-top:4px; }
    @media (max-width:767px){ .uniform-switch { justify-content:flex-start; } }

    /* ===== Attendance status as compact colour pills ===== */
    .att-cell { min-width: 300px; }
    .att-cell .radio.radio-inline { margin: 0 5px 5px 0 !important; padding: 0 !important; display: inline-block; }
    .att-cell .radio.radio-inline input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; }
    .att-cell .radio.radio-inline label { display: inline-block; padding: 4px 11px; margin: 0; border: 1.5px solid #d7dde5; border-radius: 14px; font-size: 11.5px; font-weight: 600; color: #6b7684; background: #f7f9fb; cursor: pointer; line-height: 1.45; white-space: nowrap; transition: all .12s ease; }
    .att-cell .radio.radio-inline label::before, .att-cell .radio.radio-inline label::after { display: none !important; content: none !important; }
    .att-cell .radio.radio-inline label:hover { border-color: #9aa6b4; color: #445; }
    /* selected — default, then per-status colours */
    .att-cell input:checked + label { color: #fff; background: #334155; border-color: #334155; box-shadow: 0 1px 3px rgba(0,0,0,.12); }
    .att-cell input.att-present:checked + label { background: #16a34a; border-color: #16a34a; }
    .att-cell input.att-late:checked + label { background: #f59e0b; border-color: #f59e0b; }
    .att-cell input.att-absent:checked + label { background: #dc2626; border-color: #dc2626; }
    .att-cell input.att-half_day:checked + label { background: #2563eb; border-color: #2563eb; }
    .att-cell input.att-holiday:checked + label { background: #7c3aed; border-color: #7c3aed; }
    .att-cell input.att-unplanned_leave:checked + label { background: #db2777; border-color: #db2777; }
    .att-cell input[type="radio"]:disabled + label { opacity: .5; cursor: not-allowed; }

    /* ===== Fixed-size scroll box: table scrolls inside, page does not ===== */
    .staffatt-scroll { width: 100%; max-width: 100%; max-height: 66vh; overflow: auto; }
    .staffatt-scroll .staffatt-table { margin-bottom: 0; }

    /* ===== Sticky header + tighter rows so more staff fit ===== */
    .staffatt-table thead th { position: sticky; top: 0; z-index: 5; }
    .staffatt-table tbody td { padding: 7px 8px !important; }
    .staffatt-table .in_time, .staffatt-table .out_time { min-width: 84px; padding: 4px 6px; }
    .staffatt-table td .form-control[name^="remark"] { min-width: 120px; }

    /* ===== Row tint (left accent) by selected status ===== */
    .staffatt-table tbody tr td:first-child { position: relative; }
    .staffatt-table tbody tr.rt-present td:first-child { box-shadow: inset 4px 0 0 #16a34a; }
    .staffatt-table tbody tr.rt-late td:first-child { box-shadow: inset 4px 0 0 #f59e0b; }
    .staffatt-table tbody tr.rt-absent td:first-child { box-shadow: inset 4px 0 0 #dc2626; }
    .staffatt-table tbody tr.rt-half_day td:first-child { box-shadow: inset 4px 0 0 #2563eb; }
    .staffatt-table tbody tr.rt-holiday td:first-child { box-shadow: inset 4px 0 0 #7c3aed; }
    .staffatt-table tbody tr.rt-unplanned_leave td:first-child { box-shadow: inset 4px 0 0 #db2777; }

    /* ===== Cleaner criteria bar ===== */
    #form1 .form-group label { font-weight: 600; color: #55606d; font-size: 13px; }
    #form1 .btn-block { margin-top: 2px; }

    /* ===== Redesigned header / single-panel look ===== */
    .staffatt-topbar { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; margin-bottom:12px; }
    .att-qractions { display:flex; gap:6px; flex-wrap:wrap; }
    .staffatt-card { border-top:3px solid #2b3a4a !important; box-shadow:0 1px 3px rgba(0,0,0,.06); border-radius:8px; margin-bottom:0; }
    .staffatt-criteria { padding:14px 16px 4px !important; }
    .staffatt-card .box-body { padding-left:16px; padding-right:16px; }
    .staffatt-actionbar { display:flex; align-items:center; justify-content:space-between; gap:12px; padding:6px 0 12px; flex-wrap:wrap; border-top:1px solid #eef2f6; margin-top:4px; }
    .staffatt-actionbar .sa-left { display:flex; align-items:center; gap:8px; }
    .staffatt-actionbar .sa-title { font-size:15px; font-weight:600; color:#2b3a4a; }
    .staffatt-actionbar .sa-count { display:inline-block; background:#eef2f7; color:#55606d; border-radius:10px; padding:1px 9px; font-size:12px; font-weight:600; }
    .staffatt-info { color:#94a3b8; font-size:14px; }
    .staffatt-info:hover { color:#64748b; }

    /* ===== "Set all" controls inside column headers ===== */
    .setall-wrap { display: flex; flex-wrap: wrap; gap: 4px; align-items: center; margin-top: 6px; text-transform: none; font-weight: 400; }
    .setall-label { font-size: 10.5px; color: #97a3b2; text-transform: uppercase; letter-spacing: .3px; margin-right: 2px; }
    .setall-pill { margin: 0; font-weight: 500; cursor: pointer; }
    .setall-pill input { position: absolute; opacity: 0; width: 0; height: 0; }
    .setall-pill span { display: inline-block; padding: 2px 8px; border: 1px solid #d7dde5; border-radius: 11px; font-size: 10.5px; color: #6b7684; background: #fff; transition: all .12s ease; }
    .setall-pill:hover span { border-color: #9aa6b4; }
    .setall-pill input:checked + span { color: #fff; background: #334155; border-color: #334155; }
    .setall-pill.att-present input:checked + span { background: #16a34a; border-color: #16a34a; }
    .setall-pill.att-late input:checked + span { background: #f59e0b; border-color: #f59e0b; }
    .setall-pill.att-absent input:checked + span { background: #dc2626; border-color: #dc2626; }
    .setall-pill.att-half_day input:checked + span { background: #2563eb; border-color: #2563eb; }
    .setall-pill.att-holiday input:checked + span { background: #7c3aed; border-color: #7c3aed; }
    /* ===== Quick Stats Summary Bar ===== */
    .att-stats-bar { display:flex; flex-wrap:wrap; gap:8px; align-items:center; padding:10px 14px; background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; margin-bottom:12px; }
    .att-stat-chip { display:inline-flex; align-items:center; gap:6px; padding:4px 10px; border-radius:20px; font-size:12px; font-weight:600; background:#fff; border:1px solid #cbd5e1; color:#475569; }
    .att-stat-chip .stat-count { display:inline-block; padding:1px 6px; border-radius:10px; font-size:11px; color:#fff; font-weight:700; }
    .chip-present .stat-count { background:#16a34a; }
    .chip-late .stat-count { background:#f59e0b; }
    .chip-absent .stat-count { background:#dc2626; }
    .chip-half_day .stat-count { background:#2563eb; }
    .chip-holiday .stat-count { background:#7c3aed; }
    .chip-unplanned_leave .stat-count { background:#db2777; }
    .chip-qr .stat-count { background:#0891b2; }
    .chip-manual .stat-count { background:#64748b; }

    /* ===== Live Search & Filter Controls ===== */
    .att-filter-bar { display:flex; flex-wrap:wrap; gap:10px; align-items:center; justify-content:space-between; margin-bottom:10px; }
    .att-search-input { min-width:220px; max-width:320px; border-radius:20px; padding:6px 14px; border:1px solid #cbd5e1; font-size:12.5px; }
    .att-filter-btn-group .btn { border-radius:15px; font-size:11.5px; padding:3px 10px; margin-right:4px; font-weight:500; }

    /* ===== Total Working Duration Badges ===== */
    .duration-badge { display:inline-block; font-size:11px; font-weight:600; padding:2px 7px; border-radius:10px; margin-top:3px; }
    .dur-green { background:#dcfce7; color:#15803d; border:1px solid #bbf7d0; }
    .dur-amber { background:#fef3c7; color:#b45309; border:1px solid #fde68a; }
    .dur-gray { background:#f1f5f9; color:#64748b; border:1px solid #e2e8f0; }

    /* ===== Quick Time Helper Icons ===== */
    .time-btn-wrap { display:flex; align-items:center; gap:2px; margin-top:3px; }
    .time-btn-wrap .btn-t-action { padding:1px 5px; font-size:10px; color:#64748b; background:#f8fafc; border:1px solid #cbd5e1; border-radius:4px; cursor:pointer; }
    .time-btn-wrap .btn-t-action:hover { background:#e2e8f0; color:#1e293b; }

</style>

<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="staffatt-topbar">
                    <div class="btn-group att-tabs">
                        <button type="button" class="btn btn-primary att-tab-btn" data-tab="daily"><i class="fa fa-calendar-check-o"></i> <?php echo $this->lang->line('staff_attendance'); ?></button>
                        <button type="button" class="btn btn-default att-tab-btn" data-tab="monthly"><i class="fa fa-table"></i> Monthly Sheet</button>
                    </div>
                    <div class="att-qractions">
                        <button type="button" id="btn-quick-sync-biometric" class="btn btn-sm btn-info" title="Sync punches from e-TimeOffice Biometric Machine"><i class="fa fa-fingerprint"></i> Sync Biometric</button>
                        <a href="<?php echo site_url('admin/staffattendance/biometricsettings'); ?>" class="btn btn-sm btn-default" title="Biometric Settings & Mapping"><i class="fa fa-sliders"></i></a>
                        <a href="<?php echo site_url('admin/staffattendance/scan'); ?>" class="btn btn-sm btn-success"><i class="fa fa-qrcode"></i> Mark My Attendance</a>
                        <a href="<?php echo site_url('admin/staffattendance/qrdisplay'); ?>" target="_blank" class="btn btn-sm btn-default"><i class="fa fa-desktop"></i> Display QR</a>
                        <a href="<?php echo site_url('admin/staffattendance/qrsettings'); ?>" class="btn btn-sm btn-default" title="QR Settings"><i class="fa fa-cog"></i></a>
                    </div>
                </div>
                <div id="tab-daily">
                <div class="box box-primary staffatt-card">
                    <form id='form1' action="<?php echo site_url('admin/staffattendance/index') ?>" method="post" accept-charset="utf-8">
                        <div class="box-body staffatt-criteria">
                            <?php
                            if ($this->session->flashdata('msg')) {
                                echo $this->session->flashdata('msg');
                                $this->session->unset_userdata('msg');
                            }
                            ?>
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-5 col-sm-6">
                                    <div class="form-group">
                                        <label for="class_id"><?php echo $this->lang->line('role'); ?></label>

                                        <select autofocus="" id="class_id" name="user_id" class="form-control">
                                            <option value="select"><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($classlist as $key => $class) {
                                            ?>
                                                <option value="<?php echo $class["type"] ?>" <?php
                                                                                                if ($class["type"] == $user_type_id) {
                                                                                                    echo "selected =selected";
                                                                                                }
                                                                                                ?>><?php print_r($class["type"]) ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label for="date_field">
                                            <?php echo $this->lang->line('attendance_date'); ?></label>
                                        <input id="date_field" name="date" placeholder="" type="text" class="form-control date" value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat())); ?>" readonly="readonly" />
                                        <span class="text-danger"><?php echo form_error('date'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <div class="form-group">
                                        <label class="hidden-xs" style="display:block;">&nbsp;</label>
                                        <button type="submit" name="search" value="search" class="btn btn-primary btn-block checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <?php
                    if (isset($resultlist)) {
                    ?>
                        <div class="box-body" style="padding-top:0;">
                            <?php
                            if (!empty($resultlist)) {
                                $checked = "";

                                // Attendance types that should NOT have entry/exit time (leave-like).
                                // Kept data-driven so the new "Unplanned Leave" type is handled without
                                // hardcoding its id, while Absent/Holiday behave exactly as before.
                                $leave_like_ids = [];
                                foreach ($attendencetypeslist as $__t) {
                                    if (in_array($__t['long_lang_name'], ['absent', 'holiday', 'unplanned_leave'])) {
                                        $leave_like_ids[] = (int)$__t['id'];
                                    }
                                }
                                ?>
                                <form action="<?php echo site_url('admin/staffattendance/index') ?>" id="save_attendance" method="post">
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <div class="staffatt-actionbar">
                                        <div class="sa-left">
                                            <span class="sa-title"><i class="fa fa-users"></i> <?php echo $this->lang->line('staff_list'); ?> <span class="sa-count"><?php echo count($resultlist); ?></span></span>
                                            <a href="#" class="staffatt-info" data-toggle="tooltip" data-html="true" title="<span style='color:#00a65a;'>&#9679;</span> <?php echo $this->lang->line('yes'); ?> / Compliant&nbsp;&nbsp;<span style='color:#d9534f;'>&#9679;</span> <?php echo $this->lang->line('no'); ?> / Non-Compliant<br>Compliance items are for administrative tracking.&nbsp; Use <b>Set all</b> in the column headers to fill everyone at once."><i class="fa fa-info-circle"></i></a>
                                        </div>
                                        <?php if (($this->rbac->hasPrivilege('staff_attendance', 'can_add')) || ($this->rbac->hasPrivilege('staff_attendance', 'can_edit'))) { ?>
                                            <button type="submit" name="search" value="saveattendence" id="saveattendence" class="btn btn-primary checkbox-toggle"><i class="fa fa-save"></i> <?php echo $this->lang->line('save_attendance'); ?></button>
                                        <?php } ?>
                                    </div>

                                    <!-- Quick Stats Live Summary Bar -->
                                    <div class="att-stats-bar" id="live-att-stats">
                                        <span class="att-stat-chip chip-present"><i class="fa fa-check-circle text-success"></i> Present <span class="stat-count" id="cnt-present">0</span></span>
                                        <span class="att-stat-chip chip-late"><i class="fa fa-clock-o text-warning"></i> Late <span class="stat-count" id="cnt-late">0</span></span>
                                        <span class="att-stat-chip chip-absent"><i class="fa fa-times-circle text-danger"></i> Absent <span class="stat-count" id="cnt-absent">0</span></span>
                                        <span class="att-stat-chip chip-half_day"><i class="fa fa-adjust text-info"></i> Half Day <span class="stat-count" id="cnt-half_day">0</span></span>
                                        <span class="att-stat-chip chip-holiday"><i class="fa fa-tree" style="color:#7c3aed;"></i> Holiday <span class="stat-count" id="cnt-holiday">0</span></span>
                                        <span class="att-stat-chip chip-qr"><i class="fa fa-qrcode text-info"></i> QR / Biometric <span class="stat-count" id="cnt-qr">0</span></span>
                                        <span class="att-stat-chip chip-manual"><i class="fa fa-edit text-muted"></i> Manual <span class="stat-count" id="cnt-manual">0</span></span>
                                    </div>

                                    <!-- Live Search & Filter Bar -->
                                    <div class="att-filter-bar">
                                        <input type="text" id="att-table-search" class="form-control att-search-input" placeholder="🔍 Quick search staff name, ID...">
                                        <div class="att-filter-btn-group">
                                            <button type="button" class="btn btn-xs btn-default att-filter-btn active" data-filter="all">All (<span class="cnt-total"><?php echo count($resultlist); ?></span>)</button>
                                            <button type="button" class="btn btn-xs btn-default att-filter-btn" data-filter="present">Present</button>
                                            <button type="button" class="btn btn-xs btn-default att-filter-btn" data-filter="late">Late</button>
                                            <button type="button" class="btn btn-xs btn-default att-filter-btn" data-filter="absent">Absent</button>
                                            <button type="button" class="btn btn-xs btn-default att-filter-btn" data-filter="half_day">Half Day</button>
                                            <button type="button" class="btn btn-xs btn-default att-filter-btn" data-filter="missing-out"><i class="fa fa-exclamation-triangle text-warning"></i> Missing Exit Time</button>
                                        </div>
                                    </div>

                                    <input type="hidden" name="is_first_time_attendance" value="<?php echo $is_first_time_attendance;?>">
                                    <input type="hidden" name="user_id" value="<?php echo $user_type_id; ?>">
                                    <input type="hidden" name="section_id" value="">
                                    <input type="hidden" name="date" value="<?php echo $date; ?>">
                                    <div class="table-responsive staffatt-scroll">
                                        <table class="table table-hover table-striped example staffatt-table" id="staff-attendance-table">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th><?php echo $this->lang->line('staff_id'); ?></th>
                                                    <th><?php echo $this->lang->line('name'); ?></th>
                                                    <th><?php echo $this->lang->line('role'); ?></th>
                                                    <th style="min-width:300px;">
                                                        <?php echo $this->lang->line('attendance'); ?>
                                                        <div class="setall-wrap">
                                                            <span class="setall-label">Set all:</span>
                                                            <?php foreach ($attendencetypeslist as $key => $type) { $att_type = str_replace(" ", "_", strtolower($type['type'])); ?>
                                                                <label class="setall-pill att-<?php echo $att_type; ?>">
                                                                    <input type="radio" data-record_id="<?php echo $type['id'] ?>" name="attendencetype" class="default_radio" value="radio_<?php echo $type['id'] ?>" id="attendencetype<?php echo $type['id'] ?>" onclick="getatten(<?php echo $type['id'] ?>)">
                                                                    <span><?php echo $this->lang->line($att_type); ?></span>
                                                                </label>
                                                            <?php } ?>
                                                        </div>
                                                    </th>
                                                    <th class="text-center white-space-nowrap" style="min-width:280px;">
                                                        <i class="fa fa-check-square-o"></i> <?php echo $this->lang->line('compliance_checklist'); ?>
                                                        <div class="setall-wrap setall-cmp-wrap">
                                                            <span class="setall-label">Set all:</span>
                                                            <?php
                                                            $cmp_items = array(
                                                                'uniform-check'    => $this->lang->line('in_uniform'),
                                                                'idcard-check'     => $this->lang->line('id_card'),
                                                                'lessonplan-check' => $this->lang->line('lesson_plan'),
                                                                'phone-check'      => $this->lang->line('phone_handover'),
                                                            );
                                                            foreach ($cmp_items as $cmp_target => $cmp_label) { ?>
                                                                <span class="setall-cmp-item"><?php echo $cmp_label; ?>
                                                                    <button type="button" class="btn btn-xs btn-success set-all-cmp" data-target="<?php echo $cmp_target; ?>" data-val="true"><i class="fa fa-check"></i></button>
                                                                    <button type="button" class="btn btn-xs btn-danger set-all-cmp" data-target="<?php echo $cmp_target; ?>" data-val="false"><i class="fa fa-times"></i></button>
                                                                </span>
                                                            <?php } ?>
                                                        </div>
                                                    </th>
                                                    <?php  if ($sch_setting->biometric) {  ?>
                                                        <th width="10%"><?php echo $this->lang->line('date'); ?></th>
                                                    <?php  }  ?>
                                                    <th width="8%" ><?php echo $this->lang->line('source'); ?></th>
                                                    <th class="white-space-nowrap"><?php echo $this->lang->line('entry_time'); ?></th>
                                                    <th class="white-space-nowrap"><?php echo $this->lang->line('exit_time'); ?></th>
                                                    <th class="text-right"><?php echo $this->lang->line('note'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $row_count = 1;
                                                foreach ($resultlist as $key => $value) {

                                                    $attendendence_id = $value["id"];
                                                ?>
                                                    <tr>
                                                        <td>
                                                            <input type="hidden" name="staff_role[]" id="staff_role_<?php echo $value['role_id']; ?>" value="<?php echo $value['role_id']; ?>">
                                                            <input type="hidden" name="student_session[]" value="<?php echo $value['staff_id']; ?>">
                                                            <input type="hidden" value="<?php echo $attendendence_id ?>" name="attendendence_id<?php echo $value["staff_id"]; ?>">
                                                            <?php echo $row_count; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $value['employee_id']; ?>
                                                        </td>                                                      
                                                        <td>
                                                            <?php echo $value['name'] . " " . $value['surname']; ?>
                                                        </td>
                                                        <td><?php echo $value['user_type']; ?></td>
                                                        <td class="att-cell">
                                                            <?php
                                                            $c     = 1;
                                                            $count = 0;
                                                            foreach ($attendencetypeslist as $key => $type) {

                                                                    $att_type = str_replace(" ", "_", strtolower($type['type']));
                                                                    if ($value["date"] != "xxx") {
                                                            ?>
                                                                        <div class="radio radio-info radio-inline">
                                                                            <input onclick="disable_enable(this.value,<?php echo $value["staff_id"] ?>)"  <?php if ($value['staff_attendance_type_id'] == $type['id']) {
                                                                                        echo "checked";
                                                                                    }
                                                                                    ?> type="radio" id="attendencetype<?php echo $value['staff_id'] . "-" . $count; ?>" value="<?php echo $type['id'] ?>" name="attendencetype<?php echo $value['staff_id']; ?>" class="radio_<?php echo $type['id'] ?> att-<?php echo $att_type; ?>">
                                                                            <label for="attendencetype<?php echo $value['staff_id'] . "-" . $count; ?>">
                                                                                <?php echo $this->lang->line(($type['long_lang_name'])); ?>
                                                                            </label>
                                                                        </div>
                                                                    <?php
                                                                    } else {
                                                                    ?>

                                                                        <?php
                                                                        if ($sch_setting->biometric) {
                                                                        ?>
                                                                            <div class="radio radio-info radio-inline">
                                                                                <input <?php if ($att_type == "absent") {
                                                                                            echo "checked";
                                                                                        }
                                                                                        ?> type="radio" id="attendencetype<?php echo $value['staff_id'] . "-" . $count; ?>" value="<?php echo $type['id'] ?>" name="attendencetype<?php echo $value['staff_id']; ?>" class="radio_<?php echo $type['id'] ?> att-<?php echo $att_type; ?>">
                                                                                <label for="attendencetype<?php echo $value['staff_id'] . "-" . $count; ?>">
                                                                                    <?php echo $this->lang->line(($type['long_lang_name'])); ?>
                                                                                </label>
                                                                            </div>
                                                                        <?php
                                                                        } else {
                                                                        ?>
                                                                            <div class="radio radio-info radio-inline">
                                                                                <input  onclick="disable_enable(this.value,<?php echo $value["staff_id"] ?>)"  <?php if (($c == 1) && ($resultlist[0]['staff_attendance_type_id'] != 5)) {
                                                                                            echo "checked";
                                                                                        }
                                                                                        ?> type="radio" id="attendencetype<?php echo $value['staff_id'] . "-" . $count; ?>" value="<?php echo $type['id'] ?>" name="attendencetype<?php echo $value['staff_id']; ?>" class="radio_<?php echo $type['id'] ?> att-<?php echo $att_type; ?>">
                                                                                <label for="attendencetype<?php echo $value['staff_id'] . "-" . $count; ?>">
                                                                                    <?php
                                                                                     echo $this->lang->line(($type['long_lang_name'])); ?>
                                                                                </label>
                                                                            </div>
                                                                        <?php
                                                                        }
                                                                        ?> <?php
                                                                        }
                                                                        $c++;
                                                                        $count++;
                                                                    
                                                                }
                                                                            ?>
                                                        </td>
                                                        <?php
                                                            // Compliance toggles — default 'yes' (checked) unless explicit 'no'
                                                            $u_val  = isset($value['uniform_status']) ? $value['uniform_status'] : null;
                                                            $id_val = isset($value['id_card_status']) ? $value['id_card_status'] : null;
                                                            $lp_val = isset($value['lesson_plan_status']) ? $value['lesson_plan_status'] : null;
                                                            $ph_val = isset($value['phone_handover_status']) ? $value['phone_handover_status'] : null;
                                                        ?>
                                                        <td>
                                                            <div class="cmp-group">
                                                                <!-- Uniform -->
                                                                <label class="cmp-pill <?php echo ($u_val === 'no') ? '' : 'active'; ?>" title="<?php echo $this->lang->line('in_uniform'); ?>">
                                                                    <input type="checkbox" class="cmp-check uniform-check" name="uniform_status_<?php echo $value['staff_id']; ?>" value="yes" <?php echo ($u_val === 'no') ? '' : 'checked'; ?>>
                                                                    <i class="fa <?php echo ($u_val === 'no') ? 'fa-times' : 'fa-check'; ?>"></i>
                                                                    <span><?php echo $this->lang->line('in_uniform'); ?></span>
                                                                </label>

                                                                <!-- ID Card -->
                                                                <label class="cmp-pill <?php echo ($id_val === 'no') ? '' : 'active'; ?>" title="<?php echo $this->lang->line('id_card'); ?>">
                                                                    <input type="checkbox" class="cmp-check idcard-check" name="id_card_status_<?php echo $value['staff_id']; ?>" value="yes" <?php echo ($id_val === 'no') ? '' : 'checked'; ?>>
                                                                    <i class="fa <?php echo ($id_val === 'no') ? 'fa-times' : 'fa-check'; ?>"></i>
                                                                    <span><?php echo $this->lang->line('id_card'); ?></span>
                                                                </label>

                                                                <!-- Lesson Plan / Diary -->
                                                                <label class="cmp-pill <?php echo ($lp_val === 'no') ? '' : 'active'; ?>" title="<?php echo $this->lang->line('lesson_plan'); ?>">
                                                                    <input type="checkbox" class="cmp-check lessonplan-check" name="lesson_plan_status_<?php echo $value['staff_id']; ?>" value="yes" <?php echo ($lp_val === 'no') ? '' : 'checked'; ?>>
                                                                    <i class="fa <?php echo ($lp_val === 'no') ? 'fa-times' : 'fa-check'; ?>"></i>
                                                                    <span><?php echo $this->lang->line('lesson_plan'); ?></span>
                                                                </label>

                                                                <!-- Phone Handover -->
                                                                <label class="cmp-pill <?php echo ($ph_val === 'no') ? '' : 'active'; ?>" title="<?php echo $this->lang->line('phone_handover'); ?>">
                                                                    <input type="checkbox" class="cmp-check phone-check" name="phone_handover_status_<?php echo $value['staff_id']; ?>" value="yes" <?php echo ($ph_val === 'no') ? '' : 'checked'; ?>>
                                                                    <i class="fa <?php echo ($ph_val === 'no') ? 'fa-times' : 'fa-check'; ?>"></i>
                                                                    <span><?php echo $this->lang->line('phone_handover'); ?></span>
                                                                </label>
                                                            </div>
                                                        </td>
                                                        <?php
                                                        if ($sch_setting->biometric) {
                                                        ?>
                                                            <td>
                                                            <?php
                                                                if ($value['biometric_attendence'] || $value['qrcode_attendance']) {

                                                                    echo $this->customlib->dateyyyymmddToDateTimeformat($value['attendence_dt']);
                                                                }
                                                                ?>
                                                            </td>
                                                        <?php
                                                        }
                                                        ?>
                                                        <td>
                                                            <?php

                                                            if (IsNullOrEmptyString($value['biometric_attendence']) && IsNullOrEmptyString($value['qrcode_attendance'])) {
                                                                echo $this->lang->line('n_a');
                                                            } elseif (($value['biometric_attendence'] == 0) && ($value['qrcode_attendance']  == 0)) {
                                                                echo $this->lang->line('manual');
                                                            } elseif ($value['biometric_attendence']) {
                                                                echo $this->lang->line('biometric');
                                                            } elseif ($value['qrcode_attendance']) {
                                                                echo $this->lang->line('qrcode')." / ".$this->lang->line('barcode');
                                                            }

                                                            ?>
                                                        </td>

                                                        <?php
                                                        if(in_array((int)$value['staff_attendance_type_id'], $leave_like_ids)){
                                                            $disable_input_attr="disabled";
                                                        }else{
                                                            $disable_input_attr="";
                                                        }  ?>

                                                    <td class="relative">
                                                        <input <?php echo $disable_input_attr;?> type="text" value="<?php if($value["in_time"]!="00:00:00"){ echo $value["in_time"]; }else{ echo "";} ?>"  name="in_time_<?php echo $value["staff_id"] ?>" id="in_time_<?php echo $value["staff_id"] ?>" class="form-control datetime in_time time in_time_<?php echo $value['role_id']; ?>" data-staff_id="<?php echo $value['staff_id']; ?>" data-role_id="<?php echo $value['role_id']; ?>">
                                                        <div class="time-btn-wrap">
                                                            <button type="button" class="btn-t-action fill-sched-in" title="Fill Role Default In-Time" data-target="in_time_<?php echo $value['staff_id']; ?>" data-role="<?php echo $value['role_id']; ?>"><i class="fa fa-clock-o"></i></button>
                                                            <button type="button" class="btn-t-action clear-single-time" title="Clear Time" data-target="in_time_<?php echo $value['staff_id']; ?>"><i class="fa fa-times"></i></button>
                                                        </div>
                                                    </td>                                                        
                                                    <td class="relative">
                                                        <input  <?php echo $disable_input_attr;?>  type="text" value="<?php if($value["out_time"]!="00:00:00"){ echo $value["out_time"]; }else{ echo "";} ?>"  name="out_time_<?php echo $value["staff_id"] ?>"  id="out_time_<?php echo $value["staff_id"] ?>" class="form-control datetime out_time time out_time_<?php echo $value['role_id']; ?>" data-staff_id="<?php echo $value['staff_id']; ?>" data-role_id="<?php echo $value['role_id']; ?>">
                                                        <div class="time-btn-wrap">
                                                            <button type="button" class="btn-t-action clear-single-time" title="Clear Time" data-target="out_time_<?php echo $value['staff_id']; ?>"><i class="fa fa-times"></i></button>
                                                            <span class="staff-work-dur duration-badge dur-gray" id="dur_<?php echo $value['staff_id']; ?>" style="display:none;"></span>
                                                        </div>
                                                    </td>  
                                                        <?php if ($value["date"] == 'xxx') { ?>
                                                            <td class="text-right"><input type="text"  class="form-control"  name="remark<?php echo $value["staff_id"] ?>"></td>
                                                        <?php } else { ?>
                                                            <td class="text-right"><input type="text"  class="form-control" name="remark<?php echo $value["staff_id"] ?>" value="<?php echo $value["remark"]; ?>"></td>
                                                        <?php } ?>
                                                    </tr>
                                                <?php
                                                    $row_count++;
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </form>
                            <?php
                            } else {
                            ?>
                                <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
                            <?php
                            }
                            ?>
                        </div>
                </div>
            <?php
                    }
            ?>
                </div><!-- /#tab-daily -->

                <div id="tab-monthly" style="display:none;">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-table"></i> Monthly Attendance Sheet</h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-4 col-sm-6">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('role'); ?></label>
                                        <select id="ms-role" class="form-control">
                                            <option value="select">All Staff</option>
                                            <?php foreach ($classlist as $c) { ?>
                                                <option value="<?php echo $c['type']; ?>"><?php echo $c['type']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-4">
                                    <div class="form-group">
                                        <label>Month</label>
                                        <select id="ms-month" class="form-control">
                                            <?php for ($mn = 1; $mn <= 12; $mn++) { ?>
                                                <option value="<?php echo $mn; ?>" <?php echo ($mn == date('n')) ? 'selected' : ''; ?>><?php echo date('F', mktime(0, 0, 0, $mn, 1)); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-4">
                                    <div class="form-group">
                                        <label>Year</label>
                                        <select id="ms-year" class="form-control">
                                            <?php $cy = (int) date('Y'); for ($yy = $cy - 3; $yy <= $cy + 1; $yy++) { ?>
                                                <option value="<?php echo $yy; ?>" <?php echo ($yy == $cy) ? 'selected' : ''; ?>><?php echo $yy; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-4">
                                    <div class="form-group">
                                        <label class="hidden-xs" style="display:block;">&nbsp;</label>
                                        <button type="button" id="ms-show" class="btn btn-primary btn-block"><i class="fa fa-search"></i> Show</button>
                                    </div>
                                </div>
                            </div>
                            <div id="ms-container"><div class="text-muted" style="padding:16px;">Pick a role and month, then click <strong>Show</strong>.</div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    (function () {
        var MS_URL = "<?php echo site_url('admin/staffattendance/monthsheet'); ?>";
        $(document).on('click', '.att-tab-btn', function () {
            var t = $(this).data('tab');
            $('.att-tab-btn').removeClass('btn-primary').addClass('btn-default');
            $(this).removeClass('btn-default').addClass('btn-primary');
            $('#tab-daily').toggle(t === 'daily');
            $('#tab-monthly').toggle(t === 'monthly');
        });
        $(document).on('click', '#ms-show', function () {
            var role = $('#ms-role').val(), month = $('#ms-month').val(), year = $('#ms-year').val();
            $('#ms-container').html('<div style="padding:20px;"><i class="fa fa-spinner fa-spin"></i> Loading monthly sheet...</div>');
            $.post(MS_URL, { role: role, month: month, year: year }, function (html) {
                $('#ms-container').html(html);
            }).fail(function () {
                $('#ms-container').html('<div class="alert alert-danger">Could not load the sheet. Please try again.</div>');
            });
        });
    })();
</script>

<script type="text/javascript">
    $(document).on('submit', '#save_attendance', function(e) {
        $('#load').button('loading');
    });

    // Left-accent row tint reflecting the selected attendance status.
    (function () {
        var STATUSES = ['present','late','absent','half_day','holiday','unplanned_leave'];
        function tintRow(radio) {
            var tr = $(radio).closest('tr');
            if (!tr.length) { return; }
            for (var i = 0; i < STATUSES.length; i++) { tr.removeClass('rt-' + STATUSES[i]); }
            var m = (radio.className || '').match(/att-([a-z_]+)/);
            if (m) { tr.addClass('rt-' + m[1]); }
        }
        $(function () {
            $('.att-cell input[type="radio"]:checked').each(function () { tintRow(this); });
            if ($.fn.tooltip) { $('.staffatt-info').tooltip({ container: 'body' }); }
        });
        $(document).on('change', '.att-cell input[type="radio"]', function () { tintRow(this); });
        // Refresh all row tints after a header "Set all" bulk action.
        $(document).on('click', '.setall-pill input.default_radio', function () {
            setTimeout(function () {
                $('.att-cell input[type="radio"]:checked').each(function () { tintRow(this); });
            }, 60);
        });
    })();

    $(document).ready(function() {
        $.extend($.fn.dataTable.defaults, {
            searching: false,
            ordering: true,
            paging: false,
            retrieve: true,
            destroy: true,
            info: false
        });
        var table = $('.example').DataTable();
        table.buttons('.export').remove();
    });
</script>
<script type="text/javascript">
  
    $(document).ready(function() {
        $('.default_radio').click(function() {
            let radio_default=($(this).val());
            var returnVal = confirm("<?php echo $this->lang->line('are_you_sure'); ?>");
            if(returnVal){
                
                $("input[type=radio]." + radio_default).prop("checked", returnVal).trigger('change');
                
                let attendance_type_id = ($(this).data('record_id'));
                if(window.leaveTypeIds && window.leaveTypeIds.indexOf(parseInt(attendance_type_id))!==-1){
                    //absent, holiday or unplanned leave -> no entry/exit time
                    $('.in_time').attr("disabled",true);
                    $('.out_time').attr("disabled",true);
                }else{
                    $('.in_time').attr("disabled",false);
                    $('.out_time').attr("disabled",false);
                }

            }else{
                return false;
            }
    });
    });
</script>

<script type="text/javascript">
    $(function() {
        $('.button-checkbox').each(function() {
            var $widget = $(this),
                $button = $widget.find('button'),
                $checkbox = $widget.find('input:checkbox'),
                color = $button.data('color'),
                settings = {
                    on: {
                        icon: 'glyphicon glyphicon-check'
                    },
                    off: {
                        icon: 'glyphicon glyphicon-unchecked'
                    }
                };
            $button.on('click', function() {
                $checkbox.prop('checked', !$checkbox.is(':checked'));
                $checkbox.triggerHandler('change');
                updateDisplay();
            });
            $checkbox.on('change', function() {
                updateDisplay();
            });

            function updateDisplay() {
                var isChecked = $checkbox.is(':checked');
                $button.data('state', (isChecked) ? "on" : "off");
                $button.find('.state-icon')
                    .removeClass()
                    .addClass('state-icon ' + settings[$button.data('state')].icon);
                if (isChecked) {
                    $button
                        .removeClass('btn-success')
                        .addClass('btn-' + color + ' active');
                } else {
                    $button
                        .removeClass('btn-' + color + ' active')
                        .addClass('btn-primary');
                }
            }

            function init() {
                updateDisplay();
                if ($button.find('.state-icon').length == 0) {
                    $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i> ');
                }
            }
            init();
        });
    });
</script>

<script type="text/javascript">
//**** staff attendance ****//
 $(function() {
     $('.time').datetimepicker({
            format: 'LT'
     });
 });
 
  $(function()
        {
            $('.time').datetimepicker().on('dp.show',function()
            {
                $(this).closest('.table-responsive').removeClass('table-responsive').addClass('temp');
            }).on('dp.hide',function()
            {
                $(this).closest('.temp').addClass('table-responsive').removeClass('temp')
            });
        });

function tConvert(time) {
if (time.toString().match(/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/)) {
    const [timeWithoutPeriod, period] = time.split(" ");
    let [hours, minutes, seconds] = timeWithoutPeriod.split(":");
    let AM_PM = null;
    AM_PM = hours < 12 ? 'AM' : 'PM'; // Set AM/PM
    hours = hours % 12 || 12; // Adjust hours
    return `${hours}:${minutes} ${AM_PM}`;
} else {
    return time;
}
}

var attendance_setting = <?php echo json_encode($staff_settings) ?>;

var leaveTypeIds = <?php echo json_encode(isset($leave_like_ids) ? $leave_like_ids : [3,5]); ?>;
window.leaveTypeIds = leaveTypeIds;

function getatten(atten_type){
    // Leave-like types (absent / holiday / unplanned leave) have no entry/exit time.
    if(leaveTypeIds.indexOf(parseInt(atten_type))!==-1){
      $('.in_time').val('');
      $('.out_time').val('');
      return false;
    }else{
        var role_id = $("input[name='staff_role[]']").map(function(){return $(this).val();}).get();
        let nm = (attendance_setting);     
        for(var i=0;i<role_id.length;i++){
            $.each(nm, function(key, value) {
                if (value.staff_attendence_type_id == atten_type && value.role_id == role_id[i]) {                
                    var defaultIn = tConvert(value.entry_time_from);
                    
                    // Only populate IN-TIME if field is currently empty.
                    // DO NOT populate value.entry_time_to into OUT-TIME because entry_time_to 
                    // is the morning entry-window cutoff (e.g. 8:05 AM), NOT the exit time!
                    $('.in_time_' + role_id[i]).each(function() {
                        if ($(this).val() === '' || $(this).val() === '00:00:00') {
                            $(this).val(defaultIn);
                        }
                    });
                }
            }); 
        }
    }
}

let disable_enable=(type,staff_id)=>{
    if(leaveTypeIds.indexOf(parseInt(type))!==-1){
        $("#in_time_"+staff_id).val("");
        $("#out_time_"+staff_id).val("");
        $("#in_time_"+staff_id).attr("disabled",true);
        $("#out_time_"+staff_id).attr("disabled",true);
    }else{
        $("#in_time_"+staff_id).attr("disabled",false);
        $("#out_time_"+staff_id).attr("disabled",false);
    }
}

// ===== Compliance Checklist Pill handlers =====
$(document).ready(function() {
    function syncCmpPill($chk) {
        var isChecked = $chk.is(':checked');
        var $pill = $chk.closest('.cmp-pill');
        var $icon = $pill.find('i');
        if (isChecked) {
            $pill.addClass('active');
            $icon.removeClass('fa-times').addClass('fa-check');
        } else {
            $pill.removeClass('active');
            $icon.removeClass('fa-check').addClass('fa-times');
        }
    }

    $(document).on('change', '.cmp-check', function() {
        syncCmpPill($(this));
    });

    $('.set-all-cmp').on('click', function() {
        var targetClass = $(this).data('target');
        var state = $(this).data('val') === true || $(this).data('val') === 'true';
        $('.' + targetClass).prop('checked', state).each(function() {
            syncCmpPill($(this));
        });
    });
});

// ===== Live Stats Counters, Working Duration, Search & Filter =====
$(document).ready(function() {
    // 1. Calculate and update live stats counters
    function updateLiveStats() {
        var counts = {
            present: 0,
            late: 0,
            absent: 0,
            half_day: 0,
            holiday: 0,
            qr: 0,
            manual: 0
        };

        $('.staffatt-table tbody tr').each(function() {
            var $tr = $(this);
            var $checked = $tr.find('.att-cell input[type="radio"]:checked');
            if ($checked.length) {
                var cls = $checked.attr('class') || '';
                if (cls.indexOf('att-present') !== -1) counts.present++;
                else if (cls.indexOf('att-late') !== -1) counts.late++;
                else if (cls.indexOf('att-absent') !== -1) counts.absent++;
                else if (cls.indexOf('att-half_day') !== -1) counts.half_day++;
                else if (cls.indexOf('att-holiday') !== -1) counts.holiday++;
            }

            var srcText = $tr.find('td:nth-child(6), td:nth-child(7)').text().toLowerCase();
            if (srcText.indexOf('qr') !== -1 || srcText.indexOf('biometric') !== -1) {
                counts.qr++;
            } else if (srcText.indexOf('manual') !== -1) {
                counts.manual++;
            }
        });

        $('#cnt-present').text(counts.present);
        $('#cnt-late').text(counts.late);
        $('#cnt-absent').text(counts.absent);
        $('#cnt-half_day').text(counts.half_day);
        $('#cnt-holiday').text(counts.holiday);
        $('#cnt-qr').text(counts.qr);
        $('#cnt-manual').text(counts.manual);
    }

    // 2. Working Duration Calculation per row
    function parseTimeString(tStr) {
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

    function calculateDurations() {
        $('.staffatt-table tbody tr').each(function() {
            var $tr = $(this);
            var inVal = $tr.find('.in_time').val();
            var outVal = $tr.find('.out_time').val();
            var $badge = $tr.find('.staff-work-dur');

            var inMins = parseTimeString(inVal);
            var outMins = parseTimeString(outVal);

            if (inMins !== null && outMins !== null && outMins > inMins) {
                var diff = outMins - inMins;
                var hours = Math.floor(diff / 60);
                var mins = diff % 60;
                var durText = (hours > 0 ? hours + 'h ' : '') + mins + 'm';

                $badge.removeClass('dur-green dur-amber dur-gray').show();
                if (diff >= 480) { // >= 8 hours
                    $badge.addClass('dur-green').html('<i class="fa fa-check"></i> ' + durText);
                } else if (diff >= 240) { // >= 4 hours
                    $badge.addClass('dur-amber').html('<i class="fa fa-clock-o"></i> ' + durText);
                } else {
                    $badge.addClass('dur-gray').html(durText);
                }
            } else {
                $badge.hide();
            }
        });
    }

    // 3. Live Search & Status Filters
    function applyTableFilter() {
        var query = ($('#att-table-search').val() || '').toLowerCase().trim();
        var activeFilter = $('.att-filter-btn.active').data('filter') || 'all';

        $('.staffatt-table tbody tr').each(function() {
            var $tr = $(this);
            var rowText = $tr.text().toLowerCase();
            var matchesSearch = !query || rowText.indexOf(query) !== -1;

            var matchesFilter = true;
            if (activeFilter === 'present') {
                matchesFilter = $tr.find('input.att-present:checked').length > 0;
            } else if (activeFilter === 'late') {
                matchesFilter = $tr.find('input.att-late:checked').length > 0;
            } else if (activeFilter === 'absent') {
                matchesFilter = $tr.find('input.att-absent:checked').length > 0;
            } else if (activeFilter === 'half_day') {
                matchesFilter = $tr.find('input.att-half_day:checked').length > 0;
            } else if (activeFilter === 'missing-out') {
                var inV = $tr.find('.in_time').val();
                var outV = $tr.find('.out_time').val();
                matchesFilter = inV && inV !== '' && inV !== '00:00:00' && (!outV || outV === '' || outV === '00:00:00');
            }

            if (matchesSearch && matchesFilter) {
                $tr.show();
            } else {
                $tr.hide();
            }
        });
    }

    $('#att-table-search').on('keyup input', applyTableFilter);

    $('.att-filter-btn').on('click', function() {
        $('.att-filter-btn').removeClass('active btn-primary').addClass('btn-default');
        $(this).removeClass('btn-default').addClass('active btn-primary');
        applyTableFilter();
    });

    // 4. Time Helper Buttons (Fill Role Default Schedule & Clear)
    $(document).on('click', '.fill-sched-in', function() {
        var targetId = $(this).data('target');
        var roleId = $(this).data('role');
        var defaultIn = '';
        if (attendance_setting) {
            $.each(attendance_setting, function(k, v) {
                if (v.role_id == roleId && v.staff_attendence_type_id == 1) {
                    defaultIn = tConvert(v.entry_time_from);
                }
            });
        }
        if (defaultIn) {
            $('#' + targetId).val(defaultIn).trigger('change');
            calculateDurations();
        }
    });

    $(document).on('click', '.clear-single-time', function() {
        var targetId = $(this).data('target');
        $('#' + targetId).val('').trigger('change');
        calculateDurations();
    });

    // Initial triggers
    updateLiveStats();
    calculateDurations();

    $(document).on('change', '.att-cell input[type="radio"], .in_time, .out_time', function() {
        updateLiveStats();
        calculateDurations();
    });

    $(document).on('click', '.default_radio', function() {
        setTimeout(function() {
            updateLiveStats();
            calculateDurations();
        }, 100);
    });

    // Quick Biometric Sync from Staff Attendance list
    $('#btn-quick-sync-biometric').on('click', function() {
        var $btn = $(this);
        var originalHtml = $btn.html();
        var selectedDate = $('#date_field').val() || '';

        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Syncing Biometric...');

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
});

</script>