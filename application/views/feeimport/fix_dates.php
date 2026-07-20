<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-calendar"></i> Fix Date Format: <?php echo $batch->batch_code; ?>
        </h1>
    </section>
    
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">Compare Dates Before Execution</h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo site_url('feeimport/batch_detail/' . $batch->id) ?>" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Back to Batch Detail</a>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php echo $this->session->flashdata('msg'); ?>
                        
                        <div class="alert alert-warning text-left">
                            <h4><i class="icon fa fa-warning"></i> Date Swapping Warning</h4>
                            This utility will analyze all rows in this batch. If the system parsed a date like <strong>12-04-2026</strong> (12th April) incorrectly as <strong>04 Dec 2026</strong>, this tool will swap it back to the correct format. 
                            It will safely update the imported fee records, the custom receipt logs, and the general ledger (acc_vouchers) in the accounting addon under a single database transaction.
                        </div>

                        <?php 
                        $has_changes = false;
                        $preview_rows = [];
                        foreach ($rows as $row) {
                            $csv = json_decode($row->csv_data, true);
                            if (empty($csv)) continue;

                            $raw_date = isset($csv[2]) ? trim($csv[2]) : '';
                            if (empty($raw_date)) continue;

                            // Parse date properly
                            $payment_date_clean = str_replace('/', '-', $raw_date);
                            $date_obj = DateTime::createFromFormat('d-m-Y', $payment_date_clean);
                            if (!$date_obj) {
                                $date_obj = DateTime::createFromFormat('Y-m-d', $payment_date_clean);
                            }

                            if ($date_obj) {
                                $correct_date = $date_obj->format('Y-m-d');
                                $stored_date = $row->original_date;

                                if ($correct_date !== $stored_date) {
                                    $has_changes = true;
                                }

                                $preview_rows[] = [
                                    'row_number' => $row->row_number,
                                    'admission_no' => $csv[0],
                                    'fee_category' => $csv[1],
                                    'raw_date' => $raw_date,
                                    'stored_date' => $stored_date,
                                    'correct_date' => $correct_date,
                                    'needs_update' => ($correct_date !== $stored_date)
                                ];
                            }
                        }
                        ?>

                        <?php if ($has_changes) { ?>
                            <form action="<?php echo site_url('feeimport/fix_dates/' . $batch->id); ?>" method="POST" style="margin-bottom: 20px;">
                                <input type="hidden" name="execute" value="1">
                                <button type="submit" class="btn btn-warning btn-lg" onclick="return confirm('Are you sure you want to swap the dates for all mismatched rows in this batch? This will update fee deposits, receipt logs, and accounting journals.');">
                                    <i class="fa fa-play"></i> Execute Date Correction
                                </button>
                            </form>
                        <?php } else { ?>
                            <div class="alert alert-success text-left">
                                <i class="icon fa fa-check"></i> All dates in this batch are already correctly formatted! No action needed.
                            </div>
                        <?php } ?>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped example">
                                <thead>
                                    <tr>
                                        <th>Row</th>
                                        <th>Admission No</th>
                                        <th>Fee Category</th>
                                        <th>CSV Date (Raw)</th>
                                        <th>Stored Date (DB)</th>
                                        <th>Corrected Date (Target)</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($preview_rows as $p_row) { ?>
                                        <tr class="<?php echo $p_row['needs_update'] ? 'warning' : ''; ?>">
                                            <td><?php echo $p_row['row_number']; ?></td>
                                            <td><?php echo $p_row['admission_no']; ?></td>
                                            <td><?php echo $p_row['fee_category']; ?></td>
                                            <td><code><?php echo $p_row['raw_date']; ?></code></td>
                                            <td><span class="text-danger"><strong><?php echo date('d-M-Y', strtotime($p_row['stored_date'])); ?></strong></span></td>
                                            <td><span class="text-success"><strong><?php echo date('d-M-Y', strtotime($p_row['correct_date'])); ?></strong></span></td>
                                            <td>
                                                <?php if ($p_row['needs_update']) { ?>
                                                    <span class="label label-warning"><i class="fa fa-refresh"></i> Will Change</span>
                                                <?php } else { ?>
                                                    <span class="label label-success"><i class="fa fa-check"></i> Correct</span>
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
