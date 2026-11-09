<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-lock"></i> Day-Close & Cash Denomination Count
            <small>Freeze daily entries and reconcile physical cash drawer</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo site_url('accounts/reports/daybook'); ?>">Accounts</a></li>
            <li class="active">Day Close</li>
        </ul>
    </section>

    <section class="content">
        <?php if ($this->session->flashdata('msg')) { echo $this->session->flashdata('msg'); } ?>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-calendar"></i> Select Date to Close / Review</h3>
                <div class="box-tools pull-right">
                    <a href="<?php echo site_url('accounts/reports/collector_summary?date_from=' . $selected_date . '&date_to=' . $selected_date); ?>" class="btn btn-default btn-sm">
                        <i class="fa fa-users"></i> Cashier Breakdown
                    </a>
                    <a href="<?php echo site_url('accounts/reports/reconciliation?date_from=' . $selected_date . '&date_to=' . $selected_date); ?>" class="btn btn-default btn-sm">
                        <i class="fa fa-balance-scale"></i> Full Reconciliation
                    </a>
                </div>
            </div>
            <div class="box-body">
                <form method="get" action="<?php echo site_url('accounts/reports/dayclose'); ?>" class="form-inline">
                    <div class="form-group">
                        <label for="date">Closing Date: &nbsp;</label>
                        <input type="date" name="date" id="date" class="form-control" value="<?php echo html_escape($selected_date); ?>" max="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Load Balances</button>
                </form>
            </div>
        </div>

        <div class="row">
            <!-- Left col: Status & Closing Balances -->
            <div class="col-md-5">
                <div class="box <?php echo (!empty($day_close_record) && $day_close_record['status'] === 'LOCKED') ? 'box-success' : 'box-warning'; ?>">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <?php if (!empty($day_close_record) && $day_close_record['status'] === 'LOCKED'): ?>
                                <i class="fa fa-check-circle text-green"></i> Day Status: <strong>LOCKED</strong>
                            <?php else: ?>
                                <i class="fa fa-unlock text-yellow"></i> Day Status: <strong>OPEN / UNLOCKED</strong>
                            <?php endif; ?>
                        </h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered">
                            <tr>
                                <th>Date</th>
                                <td><strong><?php echo date('d M Y', strtotime($selected_date)); ?></strong></td>
                            </tr>
                            <tr>
                                <th>Cash Book Closing</th>
                                <td class="text-right"><strong class="text-primary font-16">₹<?php echo number_format($cash_closing, 2); ?></strong></td>
                            </tr>
                            <tr>
                                <th>Bank Book Closing</th>
                                <td class="text-right"><strong class="text-primary font-16">₹<?php echo number_format($bank_closing, 2); ?></strong></td>
                            </tr>
                            <?php if (!empty($day_close_record) && $day_close_record['status'] === 'LOCKED'): ?>
                                <tr>
                                    <th>Locked At</th>
                                    <td><?php echo date('d M Y H:i', strtotime($day_close_record['closed_at'])); ?></td>
                                </tr>
                                <tr>
                                    <th>Physical Cash Counted</th>
                                    <td class="text-right">₹<?php echo number_format($day_close_record['physical_cash'], 2); ?></td>
                                </tr>
                                <tr>
                                    <th>Discrepancy (Short/Excess)</th>
                                    <td class="text-right">
                                        <?php if ((float)$day_close_record['discrepancy_amount'] == 0): ?>
                                            <span class="label label-success">Exact Match</span>
                                        <?php elseif ((float)$day_close_record['discrepancy_amount'] < 0): ?>
                                            <span class="label label-danger">Short: ₹<?php echo number_format(abs($day_close_record['discrepancy_amount']), 2); ?></span>
                                        <?php else: ?>
                                            <span class="label label-info">Excess: ₹<?php echo number_format($day_close_record['discrepancy_amount'], 2); ?></span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php if (!empty($day_close_record['discrepancy_reason'])): ?>
                                    <tr>
                                        <th>Reason Note</th>
                                        <td><?php echo nl2br(html_escape($day_close_record['discrepancy_reason'])); ?></td>
                                    </tr>
                                <?php endif; ?>
                            <?php endif; ?>
                        </table>

                        <?php if (!empty($day_close_record) && $day_close_record['status'] === 'LOCKED'): ?>
                            <div class="alert alert-info mt-15">
                                <i class="fa fa-info-circle"></i> Entries for this date are frozen. To modify historical records, authorized personnel can reopen this day.
                            </div>
                            <form method="post" action="<?php echo site_url('accounts/reports/dayclose?date=' . $selected_date); ?>" onsubmit="return confirm('Are you sure you want to reopen this day for editing?');">
                                <input type="hidden" name="action" value="reopen_day">
                                <div class="form-group">
                                    <label>Reopening Reason <span class="text-danger">*</span></label>
                                    <input type="text" name="reopen_reason" class="form-control input-sm" placeholder="e.g. Correcting misplaced voucher #104" required>
                                </div>
                                <button type="submit" class="btn btn-warning btn-block btn-sm"><i class="fa fa-unlock"></i> Reopen Day for Edits</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Right col: Cash Denomination Count -->
            <div class="col-md-7">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-money"></i> Cash Drawer Denomination Count</h3>
                    </div>
                    <form method="post" action="<?php echo site_url('accounts/reports/dayclose?date=' . $selected_date); ?>" id="denomForm">
                        <input type="hidden" name="action" value="lock_day">
                        <div class="box-body">
                            <table class="table table-condensed table-striped">
                                <thead>
                                    <tr>
                                        <th style="width: 140px;">Denomination</th>
                                        <th style="width: 120px;">Count</th>
                                        <th class="text-right">Total Amount (₹)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $notes = [
                                        ['key' => 'n_2000', 'val' => 2000, 'label' => '₹ 2,000 Note'],
                                        ['key' => 'n_500',  'val' => 500,  'label' => '₹ 500 Note'],
                                        ['key' => 'n_200',  'val' => 200,  'label' => '₹ 200 Note'],
                                        ['key' => 'n_100',  'val' => 100,  'label' => '₹ 100 Note'],
                                        ['key' => 'n_50',   'val' => 50,   'label' => '₹ 50 Note'],
                                        ['key' => 'n_20',   'val' => 20,   'label' => '₹ 20 Note'],
                                        ['key' => 'n_10',   'val' => 10,   'label' => '₹ 10 Note'],
                                    ];
                                    foreach ($notes as $n):
                                        $cnt = isset($denomination[$n['key']]) ? (int)$denomination[$n['key']] : 0;
                                    ?>
                                    <tr>
                                        <td><strong><?php echo $n['label']; ?></strong></td>
                                        <td>
                                            <input type="number" min="0" name="<?php echo $n['key']; ?>" class="form-control input-sm denom-input" data-multiplier="<?php echo $n['val']; ?>" value="<?php echo $cnt; ?>">
                                        </td>
                                        <td class="text-right"><span class="denom-subtotal" id="sub_<?php echo $n['key']; ?>">0.00</span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <tr>
                                        <td><strong>Coins (Lump Sum)</strong></td>
                                        <td>
                                            <input type="number" step="0.01" min="0" name="coins" id="coins_input" class="form-control input-sm" value="<?php echo isset($denomination['coins']) ? (float)$denomination['coins'] : 0; ?>">
                                        </td>
                                        <td class="text-right"><span id="sub_coins">0.00</span></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr class="active">
                                        <th colspan="2">Total Physical Cash:</th>
                                        <th class="text-right">
                                            <input type="hidden" name="total_physical" id="total_physical_val" value="0">
                                            <strong class="text-success font-18" id="total_physical_display">₹0.00</strong>
                                        </th>
                                    </tr>
                                    <tr class="active">
                                        <th colspan="2">System Cash Book Balance:</th>
                                        <th class="text-right">
                                            <strong class="text-muted font-16">₹<?php echo number_format($cash_closing, 2); ?></strong>
                                        </th>
                                    </tr>
                                    <tr id="diff_row" style="background:#fcf8e3;">
                                        <th colspan="2">Difference (Shortage / Excess):</th>
                                        <th class="text-right">
                                            <strong id="diff_display" class="font-18">₹0.00</strong>
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>

                            <div class="form-group mt-15" id="reason_group" style="display:none;">
                                <label class="text-danger"><i class="fa fa-exclamation-triangle"></i> Discrepancy Reason / Note <span class="text-danger">*</span></label>
                                <textarea name="discrepancy_reason" id="discrepancy_reason" class="form-control" rows="2" placeholder="Explain the cash shortage or excess..."><?php echo html_escape($denomination['remarks'] ?? ''); ?></textarea>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary pull-right" id="closeDayBtn">
                                <i class="fa fa-lock"></i> <?php echo (!empty($day_close_record) && $day_close_record['status'] === 'LOCKED') ? 'Update Denominations & Lock' : 'Freeze & Close Day'; ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Recent Day Close History -->
        <?php if (!empty($history)): ?>
        <div class="box box-default">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-history"></i> Day-Close History (Last 30 days)</h3>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th class="text-right">Cash Closing</th>
                            <th class="text-right">Bank Closing</th>
                            <th class="text-right">Physical Cash</th>
                            <th class="text-right">Discrepancy</th>
                            <th>Status</th>
                            <th>Closed At</th>
                            <th>Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($history as $h): ?>
                        <tr>
                            <td><a href="<?php echo site_url('accounts/reports/dayclose?date=' . $h['close_date']); ?>"><?php echo date('d M Y', strtotime($h['close_date'])); ?></a></td>
                            <td class="text-right">₹<?php echo number_format($h['cash_balance'], 2); ?></td>
                            <td class="text-right">₹<?php echo number_format($h['bank_balance'], 2); ?></td>
                            <td class="text-right"><?php echo $h['physical_cash'] !== null ? '₹' . number_format($h['physical_cash'], 2) : '-'; ?></td>
                            <td class="text-right">
                                <?php
                                $d = (float)$h['discrepancy_amount'];
                                if ($d == 0) {
                                    echo '<span class="text-success"><i class="fa fa-check"></i> 0.00</span>';
                                } elseif ($d < 0) {
                                    echo '<span class="text-danger"><i class="fa fa-arrow-down"></i> -' . number_format(abs($d), 2) . '</span>';
                                } else {
                                    echo '<span class="text-info"><i class="fa fa-arrow-up"></i> +' . number_format($d, 2) . '</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <?php if ($h['status'] === 'LOCKED'): ?>
                                    <span class="label label-success"><i class="fa fa-lock"></i> Locked</span>
                                <?php else: ?>
                                    <span class="label label-warning"><i class="fa fa-unlock"></i> Open</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('d M Y H:i', strtotime($h['closed_at'])); ?></td>
                            <td><small class="text-muted"><?php echo html_escape($h['discrepancy_reason'] ?: ($h['reopen_reason'] ? 'Reopen: ' . $h['reopen_reason'] : '-')); ?></small></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </section>
</div>

<script>
$(document).ready(function() {
    var cashBookVal = <?php echo (float)$cash_closing; ?>;

    function recalc() {
        var total = 0;
        $('.denom-input').each(function() {
            var count = parseInt($(this).val()) || 0;
            var mult = parseFloat($(this).data('multiplier')) || 0;
            var sub = count * mult;
            $(this).closest('tr').find('.denom-subtotal').text(sub.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
            total += sub;
        });

        var coins = parseFloat($('#coins_input').val()) || 0;
        $('#sub_coins').text(coins.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}));
        total += coins;

        $('#total_physical_val').val(total.toFixed(2));
        $('#total_physical_display').text('₹' + total.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}));

        var diff = total - cashBookVal;
        var diffDisplay = $('#diff_display');
        var reasonGroup = $('#reason_group');
        var reasonInput = $('#discrepancy_reason');

        if (Math.abs(diff) < 0.01) {
            diffDisplay.text('₹0.00 (Exact Match)').removeClass('text-danger text-info').addClass('text-success');
            reasonGroup.hide();
            reasonInput.prop('required', false);
        } else if (diff < 0) {
            diffDisplay.text('-₹' + Math.abs(diff).toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' (Shortage)').removeClass('text-success text-info').addClass('text-danger');
            reasonGroup.show();
            reasonInput.prop('required', true);
        } else {
            diffDisplay.text('+₹' + diff.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}) + ' (Excess)').removeClass('text-success text-danger').addClass('text-info');
            reasonGroup.show();
            reasonInput.prop('required', true);
        }
    }

    $('.denom-input, #coins_input').on('input change', recalc);
    recalc();
});
</script>
