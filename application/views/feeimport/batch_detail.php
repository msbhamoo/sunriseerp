<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-list"></i> Batch Detail: <?php echo $batch->batch_code; ?>
        </h1>
    </section>
    
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Description: <?php echo $batch->description; ?></h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo site_url('feeimport/index') ?>" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Back to History</a>
                            <?php if ($batch->status == 'imported') { ?>
                                <a href="<?php echo site_url('feeimport/fix_dates/' . $batch->id) ?>" class="btn btn-warning btn-sm" style="margin-right: 5px;"><i class="fa fa-calendar"></i> Fix Date Format</a>
                                <a href="<?php echo site_url('feeimport/revert/' . $batch->id) ?>" class="btn btn-danger btn-sm" onclick="return confirm('CRITICAL: Are you sure you want to REVERT this entire batch? This will delete all fee deposits and reverse all accounting vouchers for this batch. This action cannot be undone.');"><i class="fa fa-undo"></i> Revert Entire Batch</a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php echo $this->session->flashdata('msg'); ?>
                        
                        <div class="row" style="margin-bottom: 20px;">
                            <div class="col-md-3"><strong>Status:</strong> <span class="label label-<?php echo ($batch->status == 'imported') ? 'success' : 'danger'; ?>"><?php echo strtoupper($batch->status); ?></span></div>
                            <div class="col-md-3"><strong>Success Rows:</strong> <?php echo $batch->success_rows; ?> / <?php echo $batch->total_rows; ?></div>
                            <div class="col-md-3"><strong>Imported At:</strong> <?php echo $batch->imported_at ? date('d-m-Y H:i', strtotime($batch->imported_at)) : '-'; ?></div>
                            <div class="col-md-3"><strong>Reverted At:</strong> <?php echo $batch->reverted_at ? date('d-m-Y H:i', strtotime($batch->reverted_at)) : '-'; ?></div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped example">
                                <thead>
                                    <tr>
                                        <th>Row</th>
                                        <th>Admission No</th>
                                        <th>Fee Category</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Receipt No</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rows as $row) { 
                                        $csv = json_decode($row->csv_data, true);
                                    ?>
                                        <tr>
                                            <td><?php echo $row->row_number; ?></td>
                                            <td><?php echo isset($csv[0]) ? $csv[0] : ''; ?></td>
                                            <td><?php echo isset($csv[1]) ? $csv[1] : ''; ?></td>
                                            <td><?php echo isset($csv[2]) ? $csv[2] : ''; ?></td>
                                            <td><?php echo $this->customlib->getSchoolCurrencyFormat() . number_format($row->net_amount, 2); ?></td>
                                            <td>
                                                <?php 
                                                if ($row->custom_receipt_log_id) {
                                                    // Fetch receipt number dynamically or we could have stored it. 
                                                    // Since we didn't store the exact IMP- number in fee_import_rows directly (we could have), 
                                                    // we'll just say Generated
                                                    echo "Generated";
                                                } else {
                                                    echo "-";
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <?php if ($row->status == 'imported') { ?>
                                                    <span class="label label-success">Imported</span>
                                                <?php } else if ($row->status == 'reverted') { ?>
                                                    <span class="label label-danger">Reverted</span>
                                                <?php } else { ?>
                                                    <span class="label label-default"><?php echo ucfirst($row->status); ?></span>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
