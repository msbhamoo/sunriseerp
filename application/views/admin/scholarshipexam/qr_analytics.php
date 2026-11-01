<?php
$t = $stats['totals'];
$scans = (int) $t['scans'];
$visitors = (int) $t['visitors'];
$regs = (int) $t['registrations'];
$conv = $visitors ? round($regs * 100 / $visitors, 1) : 0;
$max_day = 1;
foreach ($stats['daily'] as $d) {
    $max_day = max($max_day, (int) $d['scans']);
}
$icons = array('Mobile' => 'fa-mobile', 'Tablet' => 'fa-tablet', 'Desktop' => 'fa-desktop');
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-qrcode"></i> QR Scan Analytics</h1>
    </section>

    <section class="content">
        <style>
            .sx-top { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 18px; }
            .sx-top p { margin: 0; color: #64748b; }
            .sx-btn { border: 0; border-radius: 8px; padding: 9px 16px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 7px; text-decoration: none !important; font-size: 13.5px; }
            .sx-btn-primary { background: #1b4f9c; color: #fff; } .sx-btn-primary:hover { background: #12356f; color: #fff; }
            .sx-btn-light { background: #eef3fa; color: #12356f; } .sx-btn-light:hover { background: #dfe9f6; color: #12356f; }
            .sx-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(15,23,42,.05); overflow: hidden; }
            .sx-head { padding: 16px 22px; display: flex; justify-content: space-between; gap: 14px; flex-wrap: wrap; align-items: center; border-bottom: 1px solid #eef2f7; }
            .sx-title { margin: 0; font-size: 16px; font-weight: 700; color: #0f172a; }
            .sx-pad { padding: 18px 22px; }
            .sx-chip { display: inline-block; padding: 3px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; background: #eef3fa; color: #12356f; }
            .sx-chip.ok { background: #e2f7e9; color: #12713a; } .sx-chip.off { background: #eceff3; color: #5b6676; }
            .sx-h { font-size: 12px; text-transform: uppercase; letter-spacing: .7px; color: #64748b; font-weight: 700; margin: 0 0 10px; }
            .sx-filter { display: flex; gap: 10px; flex-wrap: wrap; align-items: flex-end; }
            .sx-filter label { display: block; font-size: 11px; text-transform: uppercase; letter-spacing: .5px; color: #64748b; font-weight: 700; margin-bottom: 4px; }
            .sx-filter input { border: 1px solid #d5deea; border-radius: 8px; padding: 7px 12px; background: #f6f9fd; font-size: 13px; color: #12356f; }
            .sx-kpis { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 20px; }
            .sx-kpi { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; padding: 16px 20px; box-shadow: 0 2px 10px rgba(15,23,42,.05); }
            .sx-kpi b { display: block; font-size: 28px; color: #12356f; line-height: 1.1; }
            .sx-kpi span { font-size: 11px; text-transform: uppercase; letter-spacing: .5px; color: #64748b; font-weight: 700; }
            .sx-kpi small { display: block; color: #94a3b8; font-size: 12px; margin-top: 4px; }
            .sx-kpi.hl b { color: #16a34a; }
            .sx-grid { display: grid; grid-template-columns: 1.55fr 1fr; gap: 20px; }
            table.sx-t { width: 100%; border-collapse: collapse; font-size: 13px; }
            table.sx-t th { text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: .5px; color: #64748b; padding: 6px 8px; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
            table.sx-t td { padding: 9px 8px; border-bottom: 1px solid #f0f3f8; vertical-align: middle; }
            table.sx-t tr:last-child td { border-bottom: 0; }
            table.sx-t .r { text-align: right; }
            .sx-bar { display: flex; align-items: center; gap: 10px; margin-bottom: 8px; font-size: 12.5px; }
            .sx-bar .d { width: 56px; color: #475569; font-weight: 600; }
            .sx-bar .tr { flex: 1; background: #f1f5f9; border-radius: 6px; height: 20px; position: relative; overflow: hidden; }
            .sx-bar .s { position: absolute; left: 0; top: 0; height: 100%; background: #bcd3f2; border-radius: 6px; }
            .sx-bar .g { position: absolute; left: 0; top: 0; height: 100%; background: #1b4f9c; border-radius: 6px; }
            .sx-bar .n { width: 70px; text-align: right; color: #334155; }
            .sx-legend { font-size: 12px; color: #64748b; }
            .sx-legend i { display: inline-block; width: 10px; height: 10px; border-radius: 3px; margin: 0 4px 0 12px; vertical-align: -1px; }
            .sx-share { height: 6px; background: #eef3fa; border-radius: 999px; overflow: hidden; margin-top: 4px; }
            .sx-share div { height: 100%; background: #1b4f9c; }
            .sx-empty { text-align: center; padding: 40px 20px; color: #64748b; }
            @media (max-width: 991px) { .sx-grid { grid-template-columns: 1fr; } .sx-kpis { grid-template-columns: repeat(2, 1fr); } }
        </style>

        <div class="sx-top">
            <form method="GET" action="<?php echo site_url('admin/scholarshipexam/qr_analytics'); ?>" class="sx-filter">
                <div><label>From</label><input type="date" name="from" value="<?php echo htmlspecialchars($from); ?>"></div>
                <div><label>To</label><input type="date" name="to" value="<?php echo htmlspecialchars($to); ?>"></div>
                <button class="sx-btn sx-btn-primary"><i class="fa fa-filter"></i> Apply</button>
            </form>
            <a href="<?php echo site_url('admin/scholarshipexam/setting'); ?>" class="sx-btn sx-btn-light"><i class="fa fa-arrow-left"></i> Back to Settings</a>
        </div>

        <div class="sx-kpis">
            <div class="sx-kpi"><span>Total scans</span><b><?php echo $scans; ?></b><small>Every time the QR was opened</small></div>
            <div class="sx-kpi"><span>Unique scanners</span><b><?php echo $visitors; ?></b><small>Different phones / devices</small></div>
            <div class="sx-kpi"><span>Registrations from QR</span><b><?php echo $regs; ?></b><small>Scanned, then registered</small></div>
            <div class="sx-kpi hl"><span>Scan &rarr; registration</span><b><?php echo $conv; ?>%</b><small>Registrations ÷ unique scanners</small></div>
        </div>

        <div class="sx-grid">
            <div class="sx-card">
                <div class="sx-head">
                    <h3 class="sx-title">Scans vs registrations by date</h3>
                    <span class="sx-legend"><i style="background:#bcd3f2"></i>Scans<i style="background:#1b4f9c"></i>Registrations</span>
                </div>
                <div class="sx-pad">
                    <?php if (empty($stats['daily'])) { ?>
                        <div class="sx-empty"><i class="fa fa-qrcode fa-2x"></i><br>No scans in this date range.</div>
                    <?php } foreach ($stats['daily'] as $d) { ?>
                        <div class="sx-bar">
                            <div class="d"><?php echo date('d M', strtotime($d['day'])); ?></div>
                            <div class="tr">
                                <div class="s" style="width: <?php echo round($d['scans'] * 100 / $max_day, 1); ?>%"></div>
                                <div class="g" style="width: <?php echo round($d['registrations'] * 100 / $max_day, 1); ?>%"></div>
                            </div>
                            <div class="n"><?php echo (int) $d['scans']; ?> / <strong><?php echo (int) $d['registrations']; ?></strong></div>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <div class="sx-card">
                <div class="sx-head"><h3 class="sx-title">Who is scanning</h3></div>
                <div class="sx-pad">
                    <?php foreach (array('device' => 'Device', 'os' => 'Operating system', 'browser' => 'Browser') as $key => $title) { ?>
                        <p class="sx-h"><?php echo $title; ?></p>
                        <table class="sx-t" style="margin-bottom: 18px;">
                            <tr><th><?php echo $title; ?></th><th class="r">Scans</th><th class="r">Registered</th></tr>
                            <?php if (empty($stats['breakdown'][$key])) { ?>
                                <tr><td colspan="3" style="color:#94a3b8;">—</td></tr>
                            <?php } foreach ($stats['breakdown'][$key] as $b) {
                                $label = $b['label'] ?: 'Unknown'; ?>
                                <tr>
                                    <td>
                                        <?php if ($key == 'device' && isset($icons[$label])) { ?><i class="fa <?php echo $icons[$label]; ?>" style="color:#1b4f9c; width:14px;"></i> <?php } ?>
                                        <?php echo htmlspecialchars($label); ?>
                                        <div class="sx-share"><div style="width: <?php echo $scans ? round($b['scans'] * 100 / $scans) : 0; ?>%"></div></div>
                                    </td>
                                    <td class="r"><?php echo (int) $b['scans']; ?></td>
                                    <td class="r"><?php echo (int) $b['registrations']; ?></td>
                                </tr>
                            <?php } ?>
                        </table>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="sx-card">
            <div class="sx-head">
                <h3 class="sx-title">Latest scans</h3>
                <span class="sx-chip">Last 100</span>
            </div>
            <div class="sx-pad table-responsive">
                <table class="sx-t">
                    <tr><th>Date &amp; time</th><th>Device</th><th>OS</th><th>Browser</th><th>IP</th><th>Status</th></tr>
                    <?php if (empty($stats['recent'])) { ?>
                        <tr><td colspan="6" class="sx-empty">No scans yet.</td></tr>
                    <?php } foreach ($stats['recent'] as $r) { ?>
                        <tr>
                            <td><?php echo date('d M Y, h:i A', strtotime($r['scanned_at'])); ?></td>
                            <td><?php echo htmlspecialchars($r['device']); ?></td>
                            <td><?php echo htmlspecialchars($r['os']); ?></td>
                            <td><?php echo htmlspecialchars($r['browser']); ?></td>
                            <td style="color:#64748b;"><?php echo htmlspecialchars($r['ip']); ?></td>
                            <td><?php echo $r['registered'] ? '<span class="sx-chip ok"><i class="fa fa-check"></i> Registered</span> ' . htmlspecialchars((string) $r['external_candidate_name']) : '<span class="sx-chip off">Scanned only</span>'; ?></td>
                        </tr>
                    <?php } ?>
                </table>
            </div>
        </div>

        <?php if ($stats['bots']) { ?>
            <p style="color:#64748b;"><i class="fa fa-info-circle"></i> <?php echo $stats['bots']; ?> link-preview / bot hits (e.g. WhatsApp previews) were excluded from these numbers.</p>
        <?php } ?>
    </section>
</div>
