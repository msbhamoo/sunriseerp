<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-exclamation-triangle"></i> False-Positive Auto-Reversals <small>Review only</small></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="callout callout-info" style="margin-bottom:15px;">
                    <p style="margin:0;">
                        This is a <strong>read-only diagnostic</strong>. It lists receipt/journal vouchers that were
                        <strong>auto-reversed</strong> even though the underlying fee sub-invoice still exists in the
                        student's fee record &mdash; i.e. reversals that most likely should not have happened.
                        <strong>Nothing is changed by opening this page.</strong> Use it to review before deciding on any restore.
                    </p>
                </div>

                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-list"></i> Candidates
                            <span class="label label-warning" style="margin-left:6px;"><?php echo count($rows); ?></span>
                        </h3>
                    </div>
                    <div class="box-body table-responsive">
                        <?php if (empty($rows)) { ?>
                            <div class="text-center text-muted" style="padding:24px;">
                                <i class="fa fa-check-circle text-success" style="font-size:26px; display:block; margin-bottom:8px;"></i>
                                No false-positive auto-reversals detected. Every auto-reversal on record corresponds to a fee sub-invoice that no longer exists.
                            </div>
                        <?php } else { ?>
                            <table class="table table-striped table-bordered table-hover" id="false_reversals_table" cellspacing="0" width="100%">
                                <thead>
                                    <tr style="background-color:#f9fafb;">
                                        <th>#</th>
                                        <th>Original Voucher</th>
                                        <th>Orig. Date</th>
                                        <th>Status</th>
                                        <th>Reversal Voucher</th>
                                        <th>Rev. Date</th>
                                        <th>Module</th>
                                        <th>Reference</th>
                                        <th>Deposit / Sub-Inv</th>
                                        <th class="text-right">Amount</th>
                                        <th>Narration</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $sr = 1; foreach ($rows as $r) {
                                        $cur = $this->customlib->getSchoolCurrencyFormat();
                                    ?>
                                        <tr>
                                            <td><?php echo $sr++; ?></td>
                                            <td><strong><?php echo htmlspecialchars($r['original_voucher_no']); ?></strong></td>
                                            <td style="white-space:nowrap;"><?php echo !empty($r['original_voucher_date']) ? date($this->customlib->getSchoolDateFormat(), strtotime($r['original_voucher_date'])) : '-'; ?></td>
                                            <td><span class="label label-danger"><?php echo htmlspecialchars($r['original_status']); ?></span></td>
                                            <td><?php echo htmlspecialchars($r['reversal_voucher_no']); ?></td>
                                            <td style="white-space:nowrap;"><?php echo !empty($r['reversal_voucher_date']) ? date($this->customlib->getSchoolDateFormat(), strtotime($r['reversal_voucher_date'])) : '-'; ?></td>
                                            <td><?php echo htmlspecialchars($r['reference_module']); ?></td>
                                            <td><?php echo htmlspecialchars($r['reference_id']); ?></td>
                                            <td><?php echo htmlspecialchars($r['deposit_id'] . ' / ' . $r['sub_invoice_id']); ?></td>
                                            <td class="text-right" style="white-space:nowrap; font-weight:600;"><?php echo $cur . ' ' . number_format((float)$r['amount'], 2); ?></td>
                                            <td><?php echo htmlspecialchars($r['narration']); ?></td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php if (!empty($rows)) { ?>
<script type="text/javascript">
    $(document).ready(function () {
        if ($.fn.DataTable) {
            $('#false_reversals_table').DataTable({
                "pageLength": 50,
                "order": [[0, 'asc']]
            });
        }
    });
</script>
<?php } ?>
