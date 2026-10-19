<style>
    /* Premium Attendance Scan & History Aesthetics */
    .sa-scan-wrapper {
        max-width: 960px;
        margin: 0 auto;
    }
    .sa-history-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        box-shadow: 0 4px 16px -2px rgba(15, 23, 42, 0.05), 0 2px 6px -1px rgba(15, 23, 42, 0.03);
        margin-top: 24px;
        overflow: hidden;
    }
    .sa-history-header {
        background: #ffffff;
        padding: 16px 20px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
    }
    .sa-history-title {
        font-size: 15px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    /* Sleek Segmented Pill Control */
    .sa-pill-group {
        display: inline-flex;
        background: #f1f5f9;
        padding: 3.5px;
        border-radius: 10px;
        border: 1px solid #e2e8f0;
        gap: 2px;
    }
    .sa-pill-item {
        color: #475569;
        font-size: 12px;
        font-weight: 600;
        padding: 6px 14px;
        border-radius: 7px;
        text-decoration: none !important;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }
    .sa-pill-item:hover {
        color: #0f172a;
        background: rgba(255, 255, 255, 0.6);
    }
    .sa-pill-item.active {
        background: #ffffff;
        color: #0f766e;
        font-weight: 700;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08), 0 1px 2px rgba(0, 0, 0, 0.04);
    }
    /* Modern Table Rows */
    .sa-history-table {
        margin: 0;
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    .sa-history-table th {
        background: #f8fafc;
        color: #64748b;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        font-weight: 700;
        padding: 11px 16px;
        border-bottom: 1px solid #e2e8f0;
    }
    .sa-history-table td {
        padding: 12px 16px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
        font-size: 13px;
        color: #1e293b;
    }
    .sa-history-table tbody tr:hover {
        background-color: #f8fafc;
    }
    .sa-history-table tbody tr:last-child td {
        border-bottom: none;
    }
    .sa-badge-time {
        font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
        font-size: 12.5px;
        font-weight: 600;
    }
    .sa-dur-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: #f1f5f9;
        color: #334155;
        font-size: 11.5px;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
    }
    .sa-status-tag {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.2px;
    }
    .sa-tag-present { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .sa-tag-late { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    .sa-tag-half { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
    .sa-tag-absent { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
    .sa-source-tag {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11.5px;
        font-weight: 600;
        color: #64748b;
    }
    /* Scanner viewfinder */
    #scanner-wrap { position: relative; width: 100%; max-width: 360px; margin: 0 auto; aspect-ratio: 1 / 1; border-radius: 18px; overflow: hidden; background: #0f172a; box-shadow: 0 8px 24px rgba(15,23,42,0.18); }
    #qr-video { width: 100%; height: 100%; object-fit: cover; display: block; background: #0f172a; }
    .sa-vf { position: absolute; inset: 14%; pointer-events: none; }
    .sa-vf i { position: absolute; width: 34px; height: 34px; border: 4px solid #2dd4bf; }
    .sa-vf i:nth-child(1) { top: 0; left: 0; border-right: 0; border-bottom: 0; border-top-left-radius: 12px; }
    .sa-vf i:nth-child(2) { top: 0; right: 0; border-left: 0; border-bottom: 0; border-top-right-radius: 12px; }
    .sa-vf i:nth-child(3) { bottom: 0; left: 0; border-right: 0; border-top: 0; border-bottom-left-radius: 12px; }
    .sa-vf i:nth-child(4) { bottom: 0; right: 0; border-left: 0; border-top: 0; border-bottom-right-radius: 12px; }
    .sa-vf-line { position: absolute; left: 6%; right: 6%; height: 2px; background: linear-gradient(90deg, transparent, #2dd4bf, transparent); box-shadow: 0 0 10px #2dd4bf; animation: saScan 2.2s ease-in-out infinite; }
    @keyframes saScan { 0%, 100% { top: 4%; } 50% { top: 94%; } }
    .sa-vf-hint { position: absolute; left: 0; right: 0; bottom: 10px; text-align: center; color: #e2e8f0; font-size: 12px; font-weight: 600; text-shadow: 0 1px 3px rgba(0,0,0,0.6); pointer-events: none; }
    #scanner-wrap.sa-paused .sa-vf-line { display: none; }
    #scanner-wrap.sa-paused::after { content: ""; position: absolute; inset: 0; background: rgba(15,23,42,0.55); }
    .sa-head-status { display: none !important; }
    .sa-big-btn { min-height: 48px; font-size: 15px !important; font-weight: 700 !important; border-radius: 12px !important; }

    @media (max-width: 767px) {
        .content-wrapper > .content-header { padding: 12px 12px 0 12px !important; }
        .content-wrapper > .content-header h1 { font-size: 18px !important; }
        .content-wrapper > .content { padding: 12px !important; }
        .sa-scan-box .box-body { padding: 16px 12px !important; }
        .sa-scan-intro { font-size: 13px !important; margin-bottom: 12px !important; }
        #scanner-wrap { max-width: none; border-radius: 16px; }
        #btn-rescan { width: 100%; }
        #early-box .btn, #choose-box .btn { width: 100%; min-height: 48px; font-size: 15px; margin: 0 0 8px 0; }
        #early-box input, #choose-box input { height: 44px; font-size: 16px; }

        .sa-history-card { margin-top: 16px; border-radius: 14px; }
        .sa-history-header { flex-direction: column; align-items: stretch; padding: 14px 12px; gap: 10px; }
        .sa-pill-group { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
        .sa-pill-item { flex: 1 0 auto; justify-content: center; padding: 9px 10px; font-size: 12.5px; }

        .sa-corr-bar { flex-direction: column; align-items: stretch; padding: 12px; }
        .sa-corr-bar .sa-corr-btn-main { width: 100%; padding: 11px 12px; font-size: 14px; border-radius: 10px; }

        /* Tables -> stacked cards */
        .sa-scan-wrapper .table-responsive { border: 0; margin: 0; overflow: visible; }
        .sa-history-table, .sa-history-table tbody, .sa-history-table tr, .sa-history-table td { display: block; width: 100%; }
        .sa-history-table thead { display: none; }
        .sa-history-table tbody { padding: 10px; background: #f8fafc; }
        .sa-history-table tbody tr { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; margin-bottom: 10px; padding: 4px 12px; box-shadow: 0 1px 2px rgba(15,23,42,0.04); }
        .sa-history-table tbody tr:last-child { margin-bottom: 0; }
        .sa-history-table tbody tr:hover { background: #fff; }
        .sa-history-table td { display: flex; justify-content: space-between; align-items: center; gap: 12px; padding: 9px 0; border-bottom: 1px dashed #eef2f7 !important; text-align: right; max-width: none !important; white-space: normal !important; }
        .sa-history-table td::before { content: attr(data-label); font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; color: #94a3b8; text-align: left; flex-shrink: 0; }
        .sa-history-table td:last-child { border-bottom: 0 !important; }
        .sa-history-table td.sa-td-head { display: flex; justify-content: space-between; text-align: left; padding: 10px 0; border-bottom: 1px solid #f1f5f9 !important; }
        .sa-history-table td.sa-td-head::before { display: none; }
        .sa-history-table .sa-head-status { display: inline-flex !important; }
        .sa-history-table td.sa-td-status { display: none; }
        .sa-history-table td.sa-td-action { justify-content: stretch; padding: 10px 0 8px; }
        .sa-history-table td.sa-td-action::before { display: none; }
        .sa-history-table td.sa-td-action:empty { display: none; }
        .sa-history-table td.sa-td-action .sa-corr-btn, .sa-history-table td.sa-td-action .sa-corr-cancel { width: 100%; padding: 9px; font-size: 13px; border-radius: 9px; }
        .sa-history-table td.sa-td-action .sa-corr-pending { width: 100%; text-align: center; background: #fffbeb; border-radius: 9px; padding: 8px; }

        /* Result modal as bottom sheet */
        #scanResultModal .modal-dialog, #corrModal .modal-dialog { position: fixed; left: 0; right: 0; bottom: 0; margin: 0 !important; max-width: none !important; width: 100%; }
        #scanResultModal.fade .modal-dialog, #corrModal.fade .modal-dialog { transform: translateY(100%); transition: transform .25s ease-out; }
        #scanResultModal.in .modal-dialog, #corrModal.in .modal-dialog { transform: translateY(0); }
        #scanResultModal .modal-content, #corrModal .modal-content { border-radius: 20px 20px 0 0 !important; max-height: 92vh; overflow-y: auto; padding-bottom: env(safe-area-inset-bottom); }
        #scanResultModal .modal-body { padding: 18px 16px !important; }
        #scanResultModal .modal-footer { padding: 12px 16px !important; }
        #modal-choice-box .btn { min-height: 52px; font-size: 16px !important; }
        #modal-breakout-reason, #corrModal .form-control { height: 44px; font-size: 16px; }
        #corrModal textarea.form-control { height: auto; }
        #corrModal .modal-footer { display: flex; gap: 8px; }
        #corrModal .modal-footer .btn { flex: 1; min-height: 46px; margin: 0 !important; font-weight: 700; border-radius: 10px; }
    }
</style>

<div class="content-wrapper">
    <section class="content-header" style="padding: 16px 20px 0 20px;">
        <h1 style="font-size: 20px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
            <i class="fa fa-qrcode text-teal" style="color: #0d9488;"></i> Mark My Attendance
        </h1>
    </section>
    <section class="content" style="padding: 16px 20px;">
        <div class="sa-scan-wrapper">
            <!-- Camera QR Scanner Card -->
            <div class="box box-solid sa-scan-box" style="border-radius: 14px; border: 1px solid #e2e8f0; box-shadow: 0 4px 16px -2px rgba(15,23,42,0.05); overflow: hidden;">
                <div class="box-body" style="text-align:center; padding: 24px;">

                    <?php if (empty($setting['is_enabled'])) { ?>
                        <div class="alert alert-warning" style="border-radius: 8px;">QR attendance is currently disabled. Please contact admin.</div>
                    <?php } else { ?>

                    <p class="sa-scan-intro" style="color: #64748b; font-size: 13.5px; margin-bottom: 18px; font-weight: 500;">Point your mobile or laptop camera at the attendance QR code displayed at school.</p>

                    <div id="qr-status" class="alert alert-info" style="display:none; border-radius: 8px;"></div>

                    <div id="scanner-wrap">
                        <video id="qr-video" playsinline muted></video>
                        <canvas id="qr-canvas" style="display:none;"></canvas>
                        <div class="sa-vf"><i></i><i></i><i></i><i></i><div class="sa-vf-line"></div></div>
                        <div class="sa-vf-hint">Align the QR code inside the frame</div>
                    </div>

                    <!-- Early-exit confirmation -->
                    <div id="early-box" style="display:none; margin-top:18px; text-align:left; background: #fffbeb; border: 1px solid #fef3c7; border-radius: 10px; padding: 16px;">
                        <div class="alert alert-warning" id="early-msg" style="margin-bottom: 12px;"></div>
                        <div class="form-group">
                            <label style="font-weight: 700; font-size: 12.5px;">Reason for leaving early (optional)</label>
                            <input type="text" id="early-reason" class="form-control" placeholder="e.g. medical, personal" style="border-radius: 6px;">
                        </div>
                        <button class="btn btn-danger" id="btn-confirm-early" style="font-weight: 700; border-radius: 6px;">Yes, I'm leaving early</button>
                        <button class="btn btn-default" id="btn-cancel-early" style="font-weight: 600; border-radius: 6px;">No, stay marked in</button>
                    </div>

                    <!-- Action choices when already checked in or when no morning check-in exists -->
                    <div id="choose-box" style="display:none; margin-top:18px; text-align:left; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px;">
                        <div class="alert alert-info" id="choose-msg" style="margin-bottom: 12px;"></div>
                        <div id="breakout-reason-wrap" style="display:none; margin-bottom:12px;">
                            <label style="font-weight: 700; font-size: 12.5px;">Reason (optional)</label>
                            <input type="text" id="breakout-reason" class="form-control" placeholder="e.g. bank, medical, departure" style="border-radius: 6px;">
                        </div>
                        <div style="display:flex; flex-wrap:wrap; gap:8px; align-items:center;">
                            <button class="btn btn-success" id="btn-mark-in" style="display:none; font-weight: 700; border-radius: 6px;"><i class="fa fa-sign-in"></i> Check In</button>
                            <button class="btn btn-danger" id="btn-direct-out" style="display:none; font-weight: 700; border-radius: 6px;"><i class="fa fa-sign-out"></i> Direct Check Out</button>
                            <button class="btn btn-warning" id="btn-break-out" style="display:none; font-weight: 700; border-radius: 6px;"><i class="fa fa-sign-out"></i> Step Out (will return)</button>
                            <button class="btn btn-success" id="btn-break-in" style="display:none; font-weight: 700; border-radius: 6px;"><i class="fa fa-sign-in"></i> Step In (returned)</button>
                            <button class="btn btn-danger" id="btn-final-out" style="display:none; font-weight: 700; border-radius: 6px;"><i class="fa fa-power-off"></i> Mark Out (end of day)</button>
                            <button class="btn btn-default" id="btn-choose-cancel" style="font-weight: 600; border-radius: 6px;"><i class="fa fa-times"></i> Cancel</button>
                        </div>
                    </div>

                    <div style="margin-top:18px;">
                        <button class="btn btn-primary sa-big-btn" id="btn-rescan" style="display:none; padding: 10px 28px;"><i class="fa fa-refresh"></i> Scan again</button>
                    </div>

                    <?php } ?>
                </div>
            </div>

            <!-- Attendance History Card -->
            <div class="sa-history-card">
                <div class="sa-history-header">
                    <h4 class="sa-history-title">
                        <i class="fa fa-calendar-check-o text-teal" style="color:#0d9488;"></i> My Attendance History
                    </h4>
                    <!-- Segmented Pill Selector -->
                    <?php $curr_period = isset($period) ? $period : '7days'; ?>
                    <div class="sa-pill-group">
                        <a href="<?php echo site_url('admin/staffattendance/scan?period=7days'); ?>" 
                           class="sa-pill-item <?php echo ($curr_period === '7days') ? 'active' : ''; ?>">
                            <i class="fa fa-clock-o"></i> Last 7 Days
                        </a>
                        <a href="<?php echo site_url('admin/staffattendance/scan?period=this_month'); ?>" 
                           class="sa-pill-item <?php echo ($curr_period === 'this_month') ? 'active' : ''; ?>">
                            <i class="fa fa-calendar"></i> <?php echo date('M Y'); ?>
                        </a>
                        <a href="<?php echo site_url('admin/staffattendance/scan?period=prev_month'); ?>" 
                           class="sa-pill-item <?php echo ($curr_period === 'prev_month') ? 'active' : ''; ?>">
                            <i class="fa fa-history"></i> <?php echo date('M Y', strtotime('last month')); ?>
                        </a>
                    </div>
                </div>

                <?php
                $pending_dates = array();
                foreach ((array) $my_corrections as $c) { if ($c['status'] === 'pending') { $pending_dates[$c['date']] = true; } }
                $corr_min_date = $correction_min;
                // Per-row "Request fix" only on the last 7 days (and never before this month);
                // older days this month can still be requested with the main button.
                $corr_row_min  = max($corr_min_date, date('Y-m-d', strtotime('-6 days')));
                ?>
                <div class="sa-corr-bar">
                    <span><i class="fa fa-info-circle"></i> Missed a punch, wrong time, or were you on official duty? Ask the office to correct it (<?php echo date('F'); ?> only).</span>
                    <button type="button" class="sa-corr-btn sa-corr-btn-main" data-date="" data-in="" data-out=""><i class="fa fa-plus"></i> Request a correction</button>
                </div>
                <div style="padding: 0;">
                    <?php if (!empty($recent_attendance)) { ?>
                    <div class="table-responsive">
                        <table class="sa-history-table">
                            <thead>
                                <tr>
                                    <th width="140">Date</th>
                                    <th>In Time</th>
                                    <th>Out Time</th>
                                    <th>Working Hours</th>
                                    <th>Status</th>
                                    <th>Source</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_attendance as $rec) { 
                                    $tagClass = 'sa-tag-present';
                                    $st = strtolower($rec['status']);
                                    if (strpos($st, 'late') !== false) $tagClass = 'sa-tag-late';
                                    elseif (strpos($st, 'half') !== false) $tagClass = 'sa-tag-half';
                                    elseif (strpos($st, 'absent') !== false) $tagClass = 'sa-tag-absent';
                                ?>
                                    <tr>
                                        <td class="sa-td-head">
                                            <div>
                                                <div style="font-weight: 700; color: #0f172a; line-height: 1.2;"><?php echo $rec['date']; ?></div>
                                                <span style="font-size: 11px; color: #64748b; font-weight: 600; text-transform: uppercase;"><?php echo $rec['day']; ?></span>
                                            </div>
                                            <span class="sa-status-tag <?php echo $tagClass; ?> sa-head-status"><?php echo html_escape($rec['status']); ?></span>
                                        </td>
                                        <td data-label="In">
                                            <?php if ($rec['in_time'] !== '--') { ?>
                                                <span class="sa-badge-time" style="color: #16a34a;"><i class="fa fa-sign-in" style="font-size: 11px; opacity:0.8;"></i> <?php echo $rec['in_time']; ?></span>
                                            <?php } else { ?>
                                                <span class="text-muted">--</span>
                                            <?php } ?>
                                        </td>
                                        <td data-label="Out">
                                            <?php if ($rec['out_time'] !== '--') { ?>
                                                <span class="sa-badge-time" style="color: #ea580c;"><i class="fa fa-sign-out" style="font-size: 11px; opacity:0.8;"></i> <?php echo $rec['out_time']; ?></span>
                                            <?php } else { ?>
                                                <span class="text-muted">--</span>
                                            <?php } ?>
                                        </td>
                                        <td data-label="Hours">
                                            <?php if ($rec['duration'] !== '--') { ?>
                                                <span class="sa-dur-pill"><i class="fa fa-clock-o text-muted"></i> <?php echo $rec['duration']; ?></span>
                                            <?php } else { ?>
                                                <span class="text-muted">--</span>
                                            <?php } ?>
                                        </td>
                                        <td class="sa-td-status" data-label="Status">
                                            <span class="sa-status-tag <?php echo $tagClass; ?>">
                                                <span style="width:6px; height:6px; border-radius:50%; background:currentColor; display:inline-block;"></span>
                                                <?php echo html_escape($rec['status']); ?>
                                            </span>
                                        </td>
                                        <td data-label="Source">
                                            <span class="sa-source-tag">
                                                <i class="fa fa-<?php echo ($rec['source'] === 'QR Code' ? 'qrcode text-success' : ($rec['source'] === 'Biometric' ? 'fingerprint text-info' : 'pencil-square-o text-muted')); ?>"></i>
                                                <?php echo html_escape($rec['source']); ?>
                                            </span>
                                        </td>
                                        <?php $can_fix = isset($pending_dates[$rec['date_ymd']]) || $rec['date_ymd'] >= $corr_row_min; ?>
                                        <td class="text-right<?php echo $can_fix ? ' sa-td-action' : ' hidden-xs'; ?>">
                                            <?php if (isset($pending_dates[$rec['date_ymd']])) { ?>
                                                <span class="sa-corr-pending" title="Correction request pending"><i class="fa fa-hourglass-half"></i> Pending</span>
                                            <?php } elseif ($rec['date_ymd'] >= $corr_row_min) { ?>
                                                <button type="button" class="sa-corr-btn" data-date="<?php echo $rec['date_ymd']; ?>"
                                                        data-in="<?php echo html_escape($rec['in_raw']); ?>" data-out="<?php echo html_escape($rec['out_raw']); ?>"
                                                        title="Ask the office to correct this day">
                                                    <i class="fa fa-pencil"></i> Request fix
                                                </button>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php } else { ?>
                        <div style="padding: 40px 20px; text-align: center; color: #64748b;">
                            <div style="width: 52px; height: 52px; border-radius: 50%; background: #f1f5f9; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px; color: #94a3b8; font-size: 22px;">
                                <i class="fa fa-calendar-o"></i>
                            </div>
                            <div style="font-weight: 700; font-size: 14px; color: #1e293b; margin-bottom: 4px;">No Attendance Records</div>
                            <div style="font-size: 12.5px; color: #64748b;">There are no punch records found for the selected timeframe.</div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php if (!empty($my_corrections)) {
            $badge = array('pending' => '#d97706', 'approved' => '#16a34a', 'rejected' => '#dc2626', 'cancelled' => '#94a3b8'); ?>
        <div class="sa-scan-wrapper" style="margin-top:0;">
            <div class="sa-history-card">
                <div class="sa-history-header">
                    <h4 class="sa-history-title"><i class="fa fa-pencil-square-o" style="color:#7c3aed;"></i> My Correction Requests</h4>
                </div>
                <div class="table-responsive">
                    <table class="sa-history-table">
                        <thead><tr><th>Date</th><th>Request</th><th>Times asked</th><th>Reason</th><th>Status</th><th></th></tr></thead>
                        <tbody>
                        <?php foreach ($my_corrections as $c) { ?>
                            <tr>
                                <td class="sa-td-head"><div><b><?php echo date('d M Y', strtotime($c['date'])); ?></b><br><span style="font-size:11px;color:#64748b;"><?php echo date('D', strtotime($c['date'])); ?></span></div><span class="sa-corr-status sa-head-status" style="background:<?php echo $badge[$c['status']] ?? '#64748b'; ?>;"><?php echo ucfirst(html_escape($c['status'])); ?></span></td>
                                <td data-label="Request"><?php echo html_escape(isset($correction_types[$c['request_type']]) ? $correction_types[$c['request_type']] : $c['request_type']); ?></td>
                                <td data-label="Times asked" style="white-space:nowrap;">
                                    <?php echo $c['requested_in'] ? 'In ' . date('h:i A', strtotime($c['requested_in'])) : ''; ?>
                                    <?php echo $c['requested_out'] ? '<br>Out ' . date('h:i A', strtotime($c['requested_out'])) : ''; ?>
                                </td>
                                <td data-label="Reason" style="max-width:260px; white-space:normal;"><?php echo html_escape($c['reason']); ?>
                                    <?php if (!empty($c['admin_remark'])) { ?><div style="font-size:11.5px;color:#475569;margin-top:3px;"><b>Office:</b> <?php echo html_escape($c['admin_remark']); ?></div><?php } ?>
                                </td>
                                <td class="sa-td-status" data-label="Status"><span class="sa-corr-status" style="background:<?php echo $badge[$c['status']] ?? '#64748b'; ?>;"><?php echo ucfirst(html_escape($c['status'])); ?></span></td>
                                <td class="text-right<?php echo $c['status'] === 'pending' ? ' sa-td-action' : ' hidden-xs'; ?>"><?php if ($c['status'] === 'pending') { ?><button type="button" class="btn btn-xs btn-default sa-corr-cancel" data-id="<?php echo $c['id']; ?>">Withdraw</button><?php } ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php } ?>
    </section>
</div>

<!-- Correction request form -->
<div class="modal fade" id="corrModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="max-width:460px;">
        <div class="modal-content" style="border-radius:12px;">
            <form id="corrForm" autocomplete="off">
                <div class="modal-header" style="border-bottom:1px solid #e2e8f0;">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" style="font-weight:700;">Request attendance correction</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date" id="corrDate" class="form-control" required
                               max="<?php echo date('Y-m-d'); ?>" min="<?php echo $corr_min_date; ?>">
                    </div>
                    <div class="form-group">
                        <label>What needs correcting?</label>
                        <select name="request_type" id="corrType" class="form-control" required>
                            <?php foreach ($correction_types as $k => $v) { ?><option value="<?php echo $k; ?>"><?php echo html_escape($v); ?></option><?php } ?>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-xs-6 form-group corr-in"><label>Correct in time</label><input type="time" name="in_time" id="corrIn" class="form-control"></div>
                        <div class="col-xs-6 form-group corr-out"><label>Correct out time</label><input type="time" name="out_time" id="corrOut" class="form-control"></div>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label>Reason</label>
                        <textarea name="reason" class="form-control" rows="3" maxlength="500" required placeholder="e.g. Biometric machine was not working; Went to CBSE board office for exam duty"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-paper-plane"></i> Send request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .sa-corr-bar { display:flex; justify-content:space-between; align-items:center; gap:10px; flex-wrap:wrap; padding:10px 16px; background:#faf5ff; border-bottom:1px solid #ede9fe; font-size:12.5px; color:#5b21b6; }
    .sa-corr-btn { border:1px solid #c4b5fd; background:#fff; color:#6d28d9; font-weight:600; font-size:12px; border-radius:6px; padding:4px 10px; cursor:pointer; white-space:nowrap; }
    .sa-corr-btn:hover { background:#f5f3ff; }
    .sa-corr-btn-main { background:#7c3aed; color:#fff; border-color:#7c3aed; padding:6px 12px; }
    .sa-corr-btn-main:hover { background:#6d28d9; }
    .sa-corr-pending { font-size:11.5px; color:#b45309; font-weight:700; white-space:nowrap; }
    .sa-corr-status { display:inline-block; color:#fff; font-size:11px; font-weight:700; padding:2px 8px; border-radius:10px; }
</style>
<script>
$(function () {
    function toggleTimes() {
        var t = $('#corrType').val();
        $('.corr-in').toggle(t === 'missed_in' || t === 'missed_both' || t === 'wrong_time' || t === 'on_duty');
        $('.corr-out').toggle(t === 'missed_out' || t === 'missed_both' || t === 'wrong_time' || t === 'on_duty');
    }
    $('#corrType').on('change', toggleTimes);
    $(document).on('click', '.sa-corr-btn', function () {
        var $b = $(this), inT = $b.data('in'), outT = $b.data('out');
        $('#corrForm')[0].reset();
        $('#corrDate').val($b.data('date') || '<?php echo date('Y-m-d'); ?>');
        // Guess the most likely problem from what is missing
        $('#corrType').val(!$b.data('date') ? 'missed_both' : (!inT && !outT ? 'missed_both' : (!inT ? 'missed_in' : (!outT ? 'missed_out' : 'wrong_time'))));
        $('#corrIn').val(inT || ''); $('#corrOut').val(outT || '');
        toggleTimes();
        $('#corrModal').modal('show');
    });
    $('#corrForm').on('submit', function (e) {
        e.preventDefault();
        var $b = $(this).find('button[type=submit]').prop('disabled', true);
        $.post('<?php echo site_url('admin/staffattendance/requestcorrection'); ?>', $(this).serialize(), function (res) {
            if (res.status == 1) { $('#corrModal').modal('hide'); successMsg(res.message); setTimeout(function () { location.reload(); }, 900); }
            else { errorMsg(res.message); }
        }, 'json').fail(function () { errorMsg('Could not send. Please try again.'); }).always(function () { $b.prop('disabled', false); });
    });
    $(document).on('click', '.sa-corr-cancel', function () {
        if (!confirm('Withdraw this request?')) return;
        $.post('<?php echo site_url('admin/staffattendance/cancelcorrection'); ?>', { id: $(this).data('id') }, function (res) {
            if (res.status == 1) { successMsg(res.message); setTimeout(function () { location.reload(); }, 700); } else { errorMsg(res.message); }
        }, 'json');
    });
});
</script>

<!-- Attendance Scan Pop-Up Modal -->
<div class="modal fade" id="scanResultModal" tabindex="-1" role="dialog" aria-labelledby="scanResultModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width:400px; margin: 40px auto;">
        <div class="modal-content" style="border-radius:12px; border:none; box-shadow:0 12px 35px rgba(0,0,0,0.25); overflow:hidden;">
            <div class="modal-header" id="modal-header-bg" style="background:#28a745; color:#fff; padding:22px 15px; text-align:center; position:relative;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#fff; opacity:0.8; position:absolute; right:15px; top:12px; font-size:24px;"><span aria-hidden="true">&times;</span></button>
                <div id="modal-icon" style="font-size:42px; margin-bottom:6px; line-height:1;">
                    <i class="fa fa-check-circle"></i>
                </div>
                <h4 class="modal-title" id="modal-status-title" style="font-weight:700; margin:0; font-size:18px; text-transform:uppercase; letter-spacing:0.5px;">Check-In Successful</h4>
            </div>
            <div class="modal-body" style="padding:22px 25px;">
                <p id="modal-message-text" class="text-center text-muted" style="font-size:14px; margin-bottom:18px; font-weight:500;"></p>
                
                <!-- Action Buttons container inside modal for direct/afternoon choices -->
                <div id="modal-choice-box" style="display:none; margin-bottom:18px;">
                    <div id="modal-breakout-reason-wrap" style="display:none; margin-bottom:12px;">
                        <label style="font-size:12px; color:#555;">Reason (optional):</label>
                        <input type="text" id="modal-breakout-reason" class="form-control input-sm" placeholder="e.g. personal, medical, departure">
                    </div>
                    <div style="display:flex; flex-direction:column; gap:10px;">
                        <button type="button" class="btn btn-success btn-block" id="modal-btn-mark-in" style="display:none; padding:10px; font-size:15px; font-weight:600; border-radius:8px;">
                            <i class="fa fa-sign-in"></i> Check In Now
                        </button>
                        <button type="button" class="btn btn-danger btn-block" id="modal-btn-direct-out" style="display:none; padding:10px; font-size:15px; font-weight:600; border-radius:8px;">
                            <i class="fa fa-sign-out"></i> Direct Check Out
                        </button>
                        <button type="button" class="btn btn-warning btn-block" id="modal-btn-break-out" style="display:none; padding:10px; font-size:15px; font-weight:600; border-radius:8px;">
                            <i class="fa fa-sign-out"></i> Step Out (will return)
                        </button>
                        <button type="button" class="btn btn-success btn-block" id="modal-btn-break-in" style="display:none; padding:10px; font-size:15px; font-weight:600; border-radius:8px;">
                            <i class="fa fa-sign-in"></i> Step In (returned)
                        </button>
                        <button type="button" class="btn btn-danger btn-block" id="modal-btn-final-out" style="display:none; padding:10px; font-size:15px; font-weight:600; border-radius:8px;">
                            <i class="fa fa-power-off"></i> Mark Out (End of day)
                        </button>
                    </div>
                </div>

                <div id="modal-details-card" style="background:#f8f9fa; border-radius:10px; padding:16px; border:1px solid #e9ecef;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; border-bottom:1px dashed #dee2e6; padding-bottom:8px;">
                        <span style="color:#6c757d; font-size:13px; font-weight:600;"><i class="fa fa-calendar" style="width:16px; text-align:center;"></i> Date:</span>
                        <span id="modal-date" style="font-weight:600; color:#333; font-size:13px;">-</span>
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; border-bottom:1px dashed #dee2e6; padding-bottom:8px;">
                        <span style="color:#6c757d; font-size:13px; font-weight:600;"><i class="fa fa-sign-in" style="width:16px; text-align:center;"></i> Check-In Time:</span>
                        <span id="modal-in-time" style="font-weight:700; color:#28a745; font-size:14px;">-</span>
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px; border-bottom:1px dashed #dee2e6; padding-bottom:8px;">
                        <span style="color:#6c757d; font-size:13px; font-weight:600;"><i class="fa fa-sign-out" style="width:16px; text-align:center;"></i> Check-Out Time:</span>
                        <span id="modal-out-time" style="font-weight:700; color:#dc3545; font-size:14px;">-</span>
                    </div>

                    <div style="display:flex; justify-content:space-between; align-items:center;">
                        <span style="color:#6c757d; font-size:13px; font-weight:600;"><i class="fa fa-info-circle" style="width:16px; text-align:center;"></i> Attendance Status:</span>
                        <span id="modal-att-status" class="label label-success" style="font-size:12px; padding:5px 12px; border-radius:12px; text-transform:capitalize;">Present</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="text-align:center; background:#f8f9fa; border-top:1px solid #e9ecef; padding:14px 20px;">
                <button type="button" class="btn btn-primary btn-block btn-lg" id="modal-done-btn" data-dismiss="modal" style="border-radius:8px; font-weight:600; font-size:15px; padding:10px 16px; box-shadow:0 4px 10px rgba(0,0,0,0.1);">OK / Done</button>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($setting['is_enabled'])) { ?>
<script src="<?php echo base_url('backend/plugins/jsqr/jsQR.js'); ?>"></script>
<script type="text/javascript">
(function () {
    var MARK_URL   = "<?php echo site_url('admin/staffattendance/markqr'); ?>";
    var GPS_NEEDED = <?php echo !empty($setting['gps_enabled']) ? 'true' : 'false'; ?>;

    var video   = document.getElementById('qr-video');
    var canvas  = document.getElementById('qr-canvas');
    var ctx     = canvas.getContext('2d');
    var statusEl = document.getElementById('qr-status');
    var earlyBox = document.getElementById('early-box');
    var earlyMsg = document.getElementById('early-msg');
    var rescanBtn = document.getElementById('btn-rescan');

    var chooseBox = document.getElementById('choose-box');
    var chooseMsg = document.getElementById('choose-msg');

    var stream = null;
    var scanning = false;
    var lastToken = null;   // token being processed (kept for re-submits)
    var curLat = null;      // location captured on the scan, reused for actions
    var curLng = null;

    function showStatus(msg, cls) {
        statusEl.style.display = 'block';
        statusEl.className = 'alert ' + (cls || 'alert-info');
        statusEl.innerHTML = msg;
        if (window.innerWidth < 768 && statusEl.scrollIntoView) { statusEl.scrollIntoView({ behavior: 'smooth', block: 'center' }); }
    }

    var wrapEl = document.getElementById('scanner-wrap');

    function stopCamera() {
        scanning = false;
        wrapEl.classList.add('sa-paused');
        if (stream) {
            stream.getTracks().forEach(function (t) { t.stop(); });
            stream = null;
        }
    }

    function startCamera() {
        earlyBox.style.display = 'none';
        chooseBox.style.display = 'none';
        rescanBtn.style.display = 'none';
        statusEl.style.display = 'none';
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            showStatus('Camera not supported on this browser. Please use a modern mobile browser over HTTPS.', 'alert-danger');
            return;
        }
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
            .then(function (s) {
                stream = s;
                video.srcObject = s;
                video.setAttribute('playsinline', true);
                video.setAttribute('webkit-playsinline', true);
                video.muted = true;
                video.play();
                scanning = true;
                wrapEl.classList.remove('sa-paused');
                requestAnimationFrame(tick);
            })
            .catch(function () {
                wrapEl.classList.add('sa-paused');
                rescanBtn.style.display = 'inline-block';
                showStatus('Unable to access the camera. Please allow camera permission.', 'alert-danger');
            });
    }

    function tick() {
        if (!scanning) { return; }
        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            var img = ctx.getImageData(0, 0, canvas.width, canvas.height);
            var code = jsQR(img.data, img.width, img.height, { inversionAttempts: 'dontInvert' });
            if (code && code.data && code.data.trim() !== '') {
                scanning = false;
                stopCamera();
                onDecoded(code.data.trim());
                return;
            }
        }
        requestAnimationFrame(tick);
    }

    function onDecoded(token) {
        lastToken = token;
        showStatus('<i class="fa fa-spinner fa-spin"></i> QR scanned! Submitting attendance...', 'alert-info');
        if (GPS_NEEDED) {
            if (!navigator.geolocation) {
                showStatus('Location is required but not supported by this browser.', 'alert-danger');
                rescanBtn.style.display = 'inline-block';
                return;
            }
            navigator.geolocation.getCurrentPosition(
                function (pos) { curLat = pos.coords.latitude; curLng = pos.coords.longitude; submit({}); },
                function (err) {
                    showStatus('Location permission denied or unavailable. Please allow location access and scan again.', 'alert-danger');
                    rescanBtn.style.display = 'inline-block';
                },
                { enableHighAccuracy: true, timeout: 7000 }
            );
        } else {
            curLat = null; curLng = null;
            submit({});
        }
    }

    // extra: { action, reason, confirm_early }
    function submit(extra) {
        extra = extra || {};
        var data = { token: lastToken };
        if (curLat !== null) { data.lat = curLat; data.lng = curLng; }
        if (extra.action) { data.action = extra.action; }
        if (extra.reason) { data.reason = extra.reason; }
        if (extra.confirm_early) { data.confirm_early = 1; }

        $.ajax({
            url: MARK_URL,
            type: 'POST',
            data: data,
            dataType: 'json'
        }).done(function (res) {
            if (typeof res === 'object' && res !== null) {
                handleResult(res);
            } else {
                showStatus('Unexpected response format from server. Please try again.', 'alert-danger');
                rescanBtn.style.display = 'inline-block';
            }
        }).fail(function (xhr, textStatus, errorThrown) {
            var msg = 'Network or server error (' + xhr.status + ').';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                msg = xhr.responseJSON.message;
            } else if (xhr.responseText && xhr.responseText.length < 150) {
                msg = xhr.responseText;
            }
            showStatus('<i class="fa fa-exclamation-circle"></i> ' + msg + ' Please scan again.', 'alert-danger');
            rescanBtn.style.display = 'inline-block';
        });
    }

    function playScanChime(isSuccess) {
        try {
            var AudioCtx = window.AudioContext || window.webkitAudioContext;
            if (AudioCtx) {
                var ctx = new AudioCtx();
                var osc = ctx.createOscillator();
                var gain = ctx.createGain();
                osc.connect(gain);
                gain.connect(ctx.destination);
                if (isSuccess !== false) {
                    // Sweet double-tone success chime (880Hz -> 1320Hz)
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(880, ctx.currentTime);
                    osc.frequency.exponentialRampToValueAtTime(1320, ctx.currentTime + 0.12);
                    gain.gain.setValueAtTime(0.3, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.35);
                    osc.start(ctx.currentTime);
                    osc.stop(ctx.currentTime + 0.35);
                } else {
                    // Warning/error tone
                    osc.type = 'triangle';
                    osc.frequency.setValueAtTime(320, ctx.currentTime);
                    gain.gain.setValueAtTime(0.3, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + 0.3);
                    osc.start(ctx.currentTime);
                    osc.stop(ctx.currentTime + 0.3);
                }
            }
        } catch (e) { }

        // Trigger mobile haptic feedback if supported
        if (navigator.vibrate) {
            navigator.vibrate(isSuccess !== false ? [80, 40, 80] : [200]);
        }
    }

    function showAttendanceModal(res) {
        playScanChime(true);
        var s = res.status;
        var inTime = res.in_time || '--';
        var outTime = res.out_time || '--';
        var attType = res.attendance_type || 'Present';
        var attDate = res.date || '<?php echo date("d M Y"); ?>';

        var title = 'Attendance Status';
        var iconHtml = '<i class="fa fa-check-circle"></i>';
        var headerBg = '#28a745'; // default green
        var badgeClass = 'label-success';

        var lowerType = attType.toLowerCase();
        if (lowerType.indexOf('late') !== -1) {
            badgeClass = 'label-warning';
        } else if (lowerType.indexOf('half') !== -1) {
            badgeClass = 'label-info';
        } else if (lowerType.indexOf('absent') !== -1) {
            badgeClass = 'label-danger';
        }

        if (s === 'marked_in') {
            title = 'Check-In Successful';
            headerBg = '#28a745';
            iconHtml = '<i class="fa fa-sign-in"></i>';
        } else if (s === 'marked_out') {
            title = 'Check-Out Successful';
            headerBg = '#007bff';
            iconHtml = '<i class="fa fa-sign-out"></i>';
        } else if (s === 'already_complete') {
            title = 'Attendance Completed';
            headerBg = '#17a2b8';
            iconHtml = '<i class="fa fa-check-square-o"></i>';
        } else if (s === 'cooldown') {
            title = 'Already Checked In';
            headerBg = '#f39c12';
            iconHtml = '<i class="fa fa-clock-o"></i>';
        } else if (s === 'break_out') {
            title = 'Stepped Out';
            headerBg = '#e67e22';
            iconHtml = '<i class="fa fa-coffee"></i>';
        } else if (s === 'break_in') {
            title = 'Welcome Back';
            headerBg = '#27ae60';
            iconHtml = '<i class="fa fa-level-up"></i>';
        } else if (s === 'choose') {
            title = 'Choose Action';
            headerBg = '#3c8dbc';
            iconHtml = '<i class="fa fa-question-circle"></i>';
        }

        $('#modal-header-bg').css('background-color', headerBg);
        $('#modal-icon').html(iconHtml);
        $('#modal-status-title').text(title);
        $('#modal-message-text').text(res.message || '');
        $('#modal-date').text(attDate);
        $('#modal-in-time').text(inTime);
        $('#modal-out-time').text(outTime);
        $('#modal-att-status').text(attType).attr('class', 'label ' + badgeClass);

        if (s === 'choose') {
            var acts = res.actions || [];
            $('#modal-details-card').hide();
            $('#modal-done-btn').hide();
            $('#modal-choice-box').show();

            $('#modal-breakout-reason-wrap').toggle(acts.indexOf('break_out') !== -1 || acts.indexOf('direct_out') !== -1);
            $('#modal-btn-mark-in').toggle(acts.indexOf('mark_in') !== -1);
            $('#modal-btn-direct-out').toggle(acts.indexOf('direct_out') !== -1);
            $('#modal-btn-break-out').toggle(acts.indexOf('break_out') !== -1);
            $('#modal-btn-break-in').toggle(acts.indexOf('break_in') !== -1);
            $('#modal-btn-final-out').toggle(acts.indexOf('final_out') !== -1);
        } else {
            $('#modal-choice-box').hide();
            $('#modal-details-card').show();
            $('#modal-done-btn').show();
        }

        if (statusEl) {
            statusEl.style.display = 'none';
        }

        $('#scanResultModal').modal('show');
    }

    // When modal is dismissed, ensure status is hidden and rescan button is available
    $('#scanResultModal').on('hidden.bs.modal', function () {
        if (statusEl) {
            statusEl.style.display = 'none';
        }
        if (rescanBtn) {
            rescanBtn.style.display = 'inline-block';
        }
    });

    function hideAllPanels() {
        if (earlyBox) earlyBox.style.display = 'none';
        if (chooseBox) chooseBox.style.display = 'none';
    }

    function handleResult(res) {
        var s = res.status;
        hideAllPanels();
        if (s === 'marked_in' || s === 'marked_out' || s === 'break_out' || s === 'break_in' || s === 'already_complete' || s === 'cooldown' || s === 'choose') {
            showAttendanceModal(res);
            rescanBtn.style.display = 'inline-block';
        } else if (s === 'confirm_early') {
            statusEl.style.display = 'none';
            earlyMsg.innerHTML = res.message;
            earlyBox.style.display = 'block';
        } else {
            // no_schedule or error
            showStatus('<i class="fa fa-exclamation-triangle"></i> ' + res.message, 'alert-danger');
            rescanBtn.style.display = 'inline-block';
        }
    }

    document.getElementById('btn-confirm-early').addEventListener('click', function () {
        var reason = document.getElementById('early-reason').value;
        earlyBox.style.display = 'none';
        showStatus('<i class="fa fa-spinner fa-spin"></i> Marking out...', 'alert-info');
        submit({ action: 'final_out', confirm_early: true, reason: reason });
    });

    document.getElementById('btn-cancel-early').addEventListener('click', function () {
        earlyBox.style.display = 'none';
        showStatus('<i class="fa fa-info-circle"></i> No changes made. You are still marked in.', 'alert-info');
        rescanBtn.style.display = 'inline-block';
    });

    // Handle clicks from both inline and modal action buttons
    function triggerAction(actionName) {
        var reason = $('#modal-breakout-reason').val() || $('#breakout-reason').val() || '';
        $('#scanResultModal').modal('hide');
        chooseBox.style.display = 'none';
        showStatus('<i class="fa fa-spinner fa-spin"></i> Recording attendance...', 'alert-info');
        submit({ action: actionName, reason: reason });
    }

    $('#modal-btn-mark-in, #btn-mark-in').on('click', function () { triggerAction('mark_in'); });
    $('#modal-btn-direct-out, #btn-direct-out').on('click', function () { triggerAction('direct_out'); });
    $('#modal-btn-break-out, #btn-break-out').on('click', function () { triggerAction('break_out'); });
    $('#modal-btn-break-in, #btn-break-in').on('click', function () { triggerAction('break_in'); });
    $('#modal-btn-final-out, #btn-final-out').on('click', function () { triggerAction('final_out'); });

    document.getElementById('btn-choose-cancel').addEventListener('click', function () {
        chooseBox.style.display = 'none';
        showStatus('<i class="fa fa-info-circle"></i> No changes made.', 'alert-info');
        rescanBtn.style.display = 'inline-block';
    });

    rescanBtn.addEventListener('click', startCamera);

    startCamera();
})();
</script>
<?php } ?>
