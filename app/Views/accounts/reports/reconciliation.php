<?php
$checks = $recon['checks'];
$failed = [];
$passed = [];
$total_issues = 0;
foreach ($checks as $k => $c) {
    $n = count($c['rows']);
    $total_issues += $n;
    if ($n) { $failed[$k] = $c; } else { $passed[$k] = $c; }
}
$fmt = function ($n) {
    return ($n === '' || $n === null) ? '' : number_format((float)$n, 2);
};
$df = $this->customlib->getSchoolDateFormat();
?>
<style>
    .rc-wrap .rc-filter { background:#fff; border:1px solid #e5e7eb; border-radius:8px; padding:12px 16px; margin-bottom:16px; }
    .rc-hero { border-radius:10px; padding:18px 22px; margin-bottom:16px; display:flex; align-items:center; gap:16px; }
    .rc-hero.ok { background:#ecfdf3; border:1px solid #abefc6; color:#067647; }
    .rc-hero.bad { background:#fef3f2; border:1px solid #fecdca; color:#b42318; }
    .rc-hero .rc-icon { font-size:34px; }
    .rc-hero h3 { margin:0 0 2px; font-size:20px; font-weight:600; }
    .rc-hero p { margin:0; font-size:13px; opacity:.9; }
    .rc-cards { display:flex; flex-wrap:wrap; gap:12px; margin-bottom:16px; }
    .rc-card { flex:1 1 280px; background:#fff; border:1px solid #e5e7eb; border-radius:10px; padding:14px 16px; }
    .rc-card .rc-title { font-weight:600; font-size:14px; margin-bottom:10px; display:flex; justify-content:space-between; align-items:center; }
    .rc-flow { display:flex; align-items:center; justify-content:space-between; font-size:12px; color:#667085; }
    .rc-flow div { text-align:center; }
    .rc-flow b { display:block; font-size:15px; color:#101828; }
    .rc-flow .op { color:#98a2b3; font-size:16px; }
    .rc-chip { font-size:11px; font-weight:600; padding:2px 9px; border-radius:10px; }
    .rc-chip.ok { background:#d1fadf; color:#067647; }
    .rc-chip.bad { background:#fee4e2; color:#b42318; }
    .rc-sec { background:#fff; border:1px solid #e5e7eb; border-left:4px solid #f04438; border-radius:8px; margin-bottom:12px; }
    .rc-sec-h { padding:12px 16px; display:flex; justify-content:space-between; align-items:center; cursor:pointer; }
    .rc-sec-h .t { font-weight:600; font-size:14px; }
    .rc-sec-h .s { font-size:12px; color:#667085; display:block; font-weight:400; }
    .rc-sec table { margin:0; font-size:13px; }
    .rc-sec table th { background:#f9fafb; font-weight:600; color:#475467; font-size:12px; }
    .rc-pass { display:flex; flex-wrap:wrap; gap:8px; margin-bottom:16px; }
    .rc-pass span { background:#ecfdf3; color:#067647; border-radius:14px; padding:4px 12px; font-size:12px; }
    .rc-hint { color:#667085; font-size:12px; }
</style>
<div class="content-wrapper rc-wrap">
    <section class="content-header">
        <h1><i class="fa fa-balance-scale"></i> Reconciliation <small>Do the books match fee collection and vouchers?</small></h1>
    </section>
    <section class="content">
        <?php if ($this->session->flashdata('msg')) { echo $this->session->flashdata('msg'); } ?>

        <form class="rc-filter form-inline" action="<?php echo site_url('accounts/reports/reconciliation'); ?>" method="post">
            <?php echo $this->customlib->getCSRF(); ?>
            <label>From</label>
            <input type="text" name="date_from" class="form-control input-sm date" style="width:130px;" value="<?php echo $date_from; ?>" readonly="readonly">
            <label style="margin-left:10px;">To</label>
            <input type="text" name="date_to" class="form-control input-sm date" style="width:130px;" value="<?php echo $date_to; ?>" readonly="readonly">
            <button type="submit" class="btn btn-primary btn-sm" style="margin-left:10px;"><i class="fa fa-refresh"></i> Check</button>
            <a href="<?php echo site_url('accounts/reports/cashbook'); ?>" class="btn btn-default btn-sm pull-right"><i class="fa fa-arrow-left"></i> Back to Cash Book</a>
        </form>

        <?php if ($total_issues == 0) { ?>
            <div class="rc-hero ok">
                <i class="fa fa-check-circle rc-icon"></i>
                <div><h3>All matched</h3><p>All <?php echo count($checks); ?> checks passed for <?php echo $date_from; ?> &ndash; <?php echo $date_to; ?>. Cash, bank and fee collection agree with the vouchers.</p></div>
            </div>
        <?php } else { ?>
            <div class="rc-hero bad">
                <i class="fa fa-exclamation-circle rc-icon"></i>
                <div><h3><?php echo $total_issues; ?> mismatch<?php echo $total_issues > 1 ? 'es' : ''; ?> to fix</h3>
                <p><?php echo count($failed); ?> of <?php echo count($checks); ?> checks failed. Use <strong>Fix</strong> to re-sync a missing entry; other rows need a correction in the voucher or the original record.</p></div>
            </div>
        <?php } ?>

        <!-- Cash / bank roll-forward cards -->
        <div class="rc-cards">
            <?php foreach ($recon['balances'] as $b) { $bad = abs($b['diff']) > 0.01; ?>
                <div class="rc-card">
                    <div class="rc-title">
                        <span><i class="fa <?php echo $b['type'] == 'cash' ? 'fa-money' : 'fa-university'; ?>"></i> <?php echo html_escape($b['name']); ?></span>
                        <span class="rc-chip <?php echo $bad ? 'bad' : 'ok'; ?>"><?php echo $bad ? 'Diff ' . $fmt($b['diff']) : 'Balanced'; ?></span>
                    </div>
                    <div class="rc-flow">
                        <div>Opening<b><?php echo $fmt($b['opening']); ?></b></div><span class="op">+</span>
                        <div>Receipts<b><?php echo $fmt($b['receipts']); ?></b></div><span class="op">&minus;</span>
                        <div>Payments<b><?php echo $fmt($b['payments']); ?></b></div><span class="op">=</span>
                        <div>Closing<b><?php echo $fmt($b['closing']); ?></b></div>
                    </div>
                </div>
            <?php } ?>
            <?php if (empty($recon['balances'])) { ?><div class="rc-card text-muted">No cash or bank ledgers found.</div><?php } ?>
        </div>

        <!-- Failed checks -->
        <?php foreach ($failed as $key => $c) { $n = count($c['rows']); ?>
            <div class="rc-sec">
                <div class="rc-sec-h" data-toggle="collapse" data-target="#rc-<?php echo $key; ?>">
                    <div class="t"><?php echo html_escape($c['title']); ?></div>
                    <div><span class="rc-chip bad"><?php echo $n; ?> issue<?php echo $n > 1 ? 's' : ''; ?></span> <i class="fa fa-chevron-down text-muted" style="margin-left:8px;"></i></div>
                </div>
                <div id="rc-<?php echo $key; ?>" class="collapse in table-responsive">
                    <table class="table table-hover">
                        <thead><tr><th style="width:105px;">Date</th><th style="width:210px;">Reference</th><th class="text-right" style="width:110px;">Amount</th><th>What is wrong</th><th class="text-center" style="width:90px;">Action</th></tr></thead>
                        <tbody>
                        <?php foreach ($c['rows'] as $r) { ?>
                            <tr>
                                <td><?php echo $r['date'] ? date($df, strtotime($r['date'])) : '&mdash;'; ?></td>
                                <td><?php echo html_escape($r['ref']); ?></td>
                                <td class="text-right"><?php echo $fmt($r['amount']); ?></td>
                                <td><?php echo html_escape($r['detail']); ?></td>
                                <td class="text-center">
                                    <?php if (!empty($r['fix']) && $can_fix) { ?>
                                        <form method="post" action="<?php echo site_url('accounts/reports/reconcile_fix'); ?>" style="display:inline;">
                                            <?php echo $this->customlib->getCSRF(); ?>
                                            <input type="hidden" name="fix" value="<?php echo $r['fix']; ?>">
                                            <input type="hidden" name="fix_id" value="<?php echo html_escape($r['fix_id']); ?>">
                                            <input type="hidden" name="date_from" value="<?php echo $date_from; ?>">
                                            <input type="hidden" name="date_to" value="<?php echo $date_to; ?>">
                                            <button type="submit" class="btn btn-xs btn-warning"><i class="fa fa-wrench"></i> Fix</button>
                                        </form>
                                    <?php } else { echo '<span class="rc-hint">Manual</span>'; } ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } ?>

        <!-- Passed checks -->
        <?php if ($passed) { ?>
            <div class="rc-hint" style="margin:4px 0 6px;">Passed checks</div>
            <div class="rc-pass">
                <?php foreach ($passed as $c) { echo '<span><i class="fa fa-check"></i> ' . html_escape($c['title']) . '</span>'; } ?>
            </div>
        <?php } ?>

        <!-- Day by day -->
        <?php if (!empty($recon['daily'])) { ?>
            <div class="rc-sec" style="border-left-color:#98a2b3;">
                <div class="rc-sec-h" data-toggle="collapse" data-target="#rc-daily">
                    <div class="t">Day-by-day balances <span class="s">Opening and closing for each day with transactions</span></div>
                    <i class="fa fa-chevron-down text-muted"></i>
                </div>
                <div id="rc-daily" class="collapse table-responsive" style="padding:0 16px 12px;">
                    <?php foreach ($recon['daily'] as $type => $accounts) { foreach ($accounts as $name => $days) { ?>
                        <h5 style="margin:12px 0 6px;"><b><?php echo ucfirst($type) . ' &ndash; ' . html_escape($name); ?></b></h5>
                        <table class="table table-condensed table-bordered">
                            <thead><tr><th>Date</th><th class="text-right">Opening</th><th class="text-right">Receipts</th><th class="text-right">Payments</th><th class="text-right">Closing</th></tr></thead>
                            <tbody>
                            <?php foreach ($days as $d) { ?>
                                <tr<?php echo $d['closing'] < -0.01 ? ' class="danger"' : ''; ?>>
                                    <td><?php echo date($df, strtotime($d['date'])); ?></td>
                                    <td class="text-right"><?php echo $fmt($d['opening']); ?></td>
                                    <td class="text-right"><?php echo $fmt($d['receipts']); ?></td>
                                    <td class="text-right"><?php echo $fmt($d['payments']); ?></td>
                                    <td class="text-right"><?php echo $fmt($d['closing']); ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    <?php } } ?>
                </div>
            </div>
        <?php } ?>
    </section>
</div>
