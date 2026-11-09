<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-university"></i> Bank Statement Import & Matching
            <small>Reconcile bank entries with fee collections & vouchers</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="<?php echo site_url('accounts/reports/bankbook'); ?>">Accounts</a></li>
            <li class="active">Bank Reconcile</li>
        </ul>
    </section>

    <section class="content">
        <?php if ($this->session->flashdata('msg')) { echo $this->session->flashdata('msg'); } ?>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-filter"></i> Select Bank Account & Upload Statement</h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-md-5">
                        <form method="get" action="<?php echo site_url('accounts/reports/bank_reconcile'); ?>">
                            <div class="form-group">
                                <label>Bank Ledger: <small class="text-danger">*</small></label>
                                <select name="bank_ledger_id" class="form-control" onchange="this.form.submit()">
                                    <?php foreach ($bank_ledgers as $b): ?>
                                        <option value="<?php echo $b['id']; ?>" <?php echo ($selected_bank == $b['id']) ? 'selected' : ''; ?>>
                                            <?php echo html_escape($b['name']); ?> (Bal: ₹<?php echo number_format($b['current_balance'], 2); ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-7">
                        <form method="post" enctype="multipart/form-data" action="<?php echo site_url('accounts/reports/bank_reconcile?bank_ledger_id=' . $selected_bank); ?>">
                            <input type="hidden" name="action" value="import_csv">
                            <label>Upload Bank CSV / Excel Statement (Columns: Date, Description, Ref/Cheque, Withdrawal, Deposit, Balance):</label>
                            <div class="input-group">
                                <input type="file" name="statement_file" class="form-control input-sm" accept=".csv" required>
                                <span class="input-group-btn">
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-upload"></i> Upload & Auto-Match</button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Matched & Unmatched Statement Lines -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-list"></i> Statement Lines & Reconciliation To-Do List</h3>
                <div class="box-tools pull-right">
                    <form method="post" action="<?php echo site_url('accounts/reports/bank_reconcile?bank_ledger_id=' . $selected_bank); ?>" style="display:inline;">
                        <input type="hidden" name="action" value="automatch_now">
                        <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-magic"></i> Run Auto-Match</button>
                    </form>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr class="active">
                            <th>Date</th>
                            <th>Description</th>
                            <th>Ref / UTR / Cheque</th>
                            <th class="text-right text-danger">Withdrawal / Dr (₹)</th>
                            <th class="text-right text-success">Deposit / Cr (₹)</th>
                            <th class="text-right">Balance (₹)</th>
                            <th class="text-center">Status</th>
                            <th>Matched Voucher</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($statements)): foreach ($statements as $st): ?>
                        <tr>
                            <td><?php echo date('d M Y', strtotime($st['transaction_date'])); ?></td>
                            <td><?php echo html_escape($st['description']); ?></td>
                            <td><code><?php echo html_escape($st['reference_no'] ?: '-'); ?></code></td>
                            <td class="text-right text-danger"><?php echo (float)$st['debit'] > 0 ? '₹' . number_format($st['debit'], 2) : '-'; ?></td>
                            <td class="text-right text-success"><?php echo (float)$st['credit'] > 0 ? '₹' . number_format($st['credit'], 2) : '-'; ?></td>
                            <td class="text-right">₹<?php echo number_format($st['closing_balance'], 2); ?></td>
                            <td class="text-center">
                                <?php if ($st['match_status'] === 'MATCHED'): ?>
                                    <span class="label label-success"><i class="fa fa-check"></i> Matched</span>
                                <?php else: ?>
                                    <span class="label label-warning"><i class="fa fa-clock-o"></i> To-Do</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($st['voucher_no'])): ?>
                                    <span class="badge bg-green"><?php echo html_escape($st['voucher_no']); ?> (<?php echo ucfirst($st['voucher_type']); ?>)</span>
                                <?php else: ?>
                                    <span class="text-muted"><small>Unmatched</small></span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($st['match_status'] === 'MATCHED'): ?>
                                    <form method="post" action="<?php echo site_url('accounts/reports/bank_reconcile?bank_ledger_id=' . $selected_bank); ?>" style="display:inline;">
                                        <input type="hidden" name="action" value="unmatch">
                                        <input type="hidden" name="statement_id" value="<?php echo $st['id']; ?>">
                                        <button type="submit" class="btn btn-default btn-xs" title="Unmatch"><i class="fa fa-times text-danger"></i> Unlink</button>
                                    </form>
                                <?php else: ?>
                                    <small class="text-muted">Awaiting match</small>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; else: ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted">No bank statement lines uploaded for this bank ledger yet. Upload a CSV statement above to begin.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
