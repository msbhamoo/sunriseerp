<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-users"></i> Collection by Collector Report
            <small>Daily fee breakdown per cashier / staff member</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo site_url('accounts/reports/cashbook'); ?>">Accounts</a></li>
            <li class="active">Collector Summary</li>
        </ul>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-filter"></i> Date Filter</h3>
                <div class="box-tools pull-right">
                    <a href="<?php echo site_url('accounts/reports/dayclose?date=' . $date_to); ?>" class="btn btn-default btn-sm">
                        <i class="fa fa-lock"></i> Go to Day Close
                    </a>
                </div>
            </div>
            <div class="box-body">
                <form method="get" action="<?php echo site_url('accounts/reports/collector_summary'); ?>" class="form-inline">
                    <div class="form-group">
                        <label>From Date: &nbsp;</label>
                        <input type="date" name="date_from" class="form-control input-sm" value="<?php echo html_escape($date_from); ?>">
                    </div>
                    &nbsp;&nbsp;
                    <div class="form-group">
                        <label>To Date: &nbsp;</label>
                        <input type="date" name="date_to" class="form-control input-sm" value="<?php echo html_escape($date_to); ?>">
                    </div>
                    &nbsp;&nbsp;
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-search"></i> Generate Summary</button>
                </form>
            </div>
        </div>

        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Staff Handover & Mode-wise Collections</h3>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr class="active">
                            <th>Collector / Cashier</th>
                            <th>Staff ID</th>
                            <th class="text-center">Receipts</th>
                            <th class="text-right text-success">Cash Handover (₹)</th>
                            <th class="text-right text-primary">Cheque / DD (₹)</th>
                            <th class="text-right text-info">Digital / UPI (₹)</th>
                            <th class="text-right font-16">Total Collection (₹)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total_cash = 0;
                        $total_cheque = 0;
                        $total_digital = 0;
                        $grand_total = 0;
                        $total_receipts = 0;

                        if (!empty($collectors)): 
                            foreach ($collectors as $c):
                                $total_cash += (float)$c['cash_amount'];
                                $total_cheque += (float)$c['cheque_amount'];
                                $total_digital += (float)$c['digital_amount'];
                                $grand_total += (float)$c['total_amount'];
                                $total_receipts += (int)$c['receipt_count'];
                        ?>
                        <tr>
                            <td>
                                <strong><?php echo html_escape($c['collector_name'] ?: 'Online / Unassigned'); ?></strong>
                            </td>
                            <td><?php echo html_escape($c['employee_id'] ?: '-'); ?></td>
                            <td class="text-center"><span class="badge bg-gray"><?php echo (int)$c['receipt_count']; ?></span></td>
                            <td class="text-right"><strong>₹<?php echo number_format($c['cash_amount'], 2); ?></strong></td>
                            <td class="text-right">₹<?php echo number_format($c['cheque_amount'], 2); ?></td>
                            <td class="text-right">₹<?php echo number_format($c['digital_amount'], 2); ?></td>
                            <td class="text-right"><strong class="text-primary font-15">₹<?php echo number_format($c['total_amount'], 2); ?></strong></td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">No collections recorded for this period.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                    <?php if (!empty($collectors)): ?>
                    <tfoot>
                        <tr style="background:#f9f9f9; font-weight:bold;">
                            <th colspan="2">Total Handover:</th>
                            <th class="text-center"><?php echo $total_receipts; ?></th>
                            <th class="text-right text-success">₹<?php echo number_format($total_cash, 2); ?></th>
                            <th class="text-right text-primary">₹<?php echo number_format($total_cheque, 2); ?></th>
                            <th class="text-right text-info">₹<?php echo number_format($total_digital, 2); ?></th>
                            <th class="text-right text-primary font-16">₹<?php echo number_format($grand_total, 2); ?></th>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </div>
        </div>
    </section>
</div>
