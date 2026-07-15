<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-eye"></i> Preview Fee Import
        </h1>
    </section>
    
    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-blue"><i class="fa fa-list"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Rows</span>
                        <span class="info-box-number"><?php echo $total; ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-check"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Ready to Import</span>
                        <span class="info-box-number"><?php echo $ready; ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-red"><i class="fa fa-times"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Errors</span>
                        <span class="info-box-number"><?php echo $errors; ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-money"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Total Amount</span>
                        <span class="info-box-number"><?php echo $this->customlib->getSchoolCurrencyFormat() . number_format($total_amount, 2); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Batch: <?php echo $batch->batch_code; ?> (<?php echo $batch->description; ?>)</h3>
                        <div class="box-tools pull-right">
                            <?php if ($errors > 0) { ?>
                                <span class="text-danger" style="margin-right: 15px;"><i class="fa fa-warning"></i> Fix errors in CSV and re-upload.</span>
                                <a href="<?php echo site_url('feeimport/index') ?>" class="btn btn-default btn-sm">Cancel</a>
                            <?php } else { ?>
                                <a href="<?php echo site_url('feeimport/index') ?>" class="btn btn-default btn-sm">Cancel</a>
                                <a href="<?php echo site_url('feeimport/execute/' . $batch->id) ?>" class="btn btn-success btn-sm" onclick="return confirm('Are you sure you want to execute this import? This will create fee receipts and accounting vouchers.');"><i class="fa fa-play"></i> Execute Import</a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php echo $this->session->flashdata('msg'); ?>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Row</th>
                                        <th>Admission No</th>
                                        <th>Fee Category</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Message</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rows as $row) { 
                                        $csv = json_decode($row->csv_data, true);
                                        $row_class = ($row->status == 'matched') ? 'success' : 'danger';
                                    ?>
                                        <tr class="<?php echo $row_class; ?>">
                                            <td><?php echo $row->row_number; ?></td>
                                            <td><?php echo isset($csv[0]) ? $csv[0] : ''; ?></td>
                                            <td><?php echo isset($csv[1]) ? $csv[1] : ''; ?></td>
                                            <td><?php echo isset($csv[2]) ? $csv[2] : ''; ?></td>
                                            <td><?php echo isset($csv[3]) ? $csv[3] : ''; ?></td>
                                            <td>
                                                <?php if ($row->status == 'matched') { ?>
                                                    <span class="label label-success">Ready</span>
                                                <?php } else { ?>
                                                    <span class="label label-danger">Error</span>
                                                <?php } ?>
                                            </td>
                                            <td class="text-danger"><?php echo $row->error_message; ?></td>
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
