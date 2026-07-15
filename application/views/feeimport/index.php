<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> Historical Fee Import
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-upload"></i> Import Historical Fees</h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo site_url('feeimport/download_template') ?>" class="btn btn-primary btn-sm"><i class="fa fa-download"></i> Download Template</a>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php echo $this->session->flashdata('msg'); ?>
                        
                        <form action="<?php echo site_url('feeimport/upload') ?>" id="import_form" name="import_form" method="post" enctype="multipart/form-data">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="file">Select CSV File <small class="req"> *</small></label>
                                        <input type="file" name="file" id="file" class="form-control filestyle" accept=".csv" required />
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description">Batch Description (Optional)</label>
                                        <input type="text" name="description" id="description" class="form-control" placeholder="E.g., April to June Fees from Old System" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-info pull-right"><i class="fa fa-upload"></i> Upload & Preview</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-history"></i> Import History</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Batch Code</th>
                                        <th>Date</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Success / Total</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($batches)) { ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted">No import history found.</td>
                                        </tr>
                                    <?php } else {
                                        foreach ($batches as $batch) {
                                            $label_class = 'label-default';
                                            if ($batch->status == 'imported') $label_class = 'label-success';
                                            if ($batch->status == 'reverted') $label_class = 'label-danger';
                                            if ($batch->status == 'draft' || $batch->status == 'previewed') $label_class = 'label-warning';
                                    ?>
                                        <tr>
                                            <td><?php echo $batch->batch_code; ?></td>
                                            <td><?php echo date($this->customlib->getSchoolDateFormat() . ' H:i', strtotime($batch->created_at)); ?></td>
                                            <td><?php echo $batch->description; ?></td>
                                            <td><span class="label <?php echo $label_class; ?>"><?php echo strtoupper($batch->status); ?></span></td>
                                            <td><?php echo $batch->success_rows . " / " . $batch->total_rows; ?></td>
                                            <td>
                                                <?php if ($batch->status == 'draft' || $batch->status == 'previewed') { ?>
                                                    <a href="<?php echo site_url('feeimport/preview/' . $batch->id) ?>" class="btn btn-warning btn-xs" data-toggle="tooltip" title="Preview"><i class="fa fa-eye"></i> Preview</a>
                                                <?php } else { ?>
                                                    <a href="<?php echo site_url('feeimport/batch_detail/' . $batch->id) ?>" class="btn btn-info btn-xs" data-toggle="tooltip" title="View Detail"><i class="fa fa-list"></i> Details</a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
