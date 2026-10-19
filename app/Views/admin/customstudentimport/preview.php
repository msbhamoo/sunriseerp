<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i> Custom Bulk Student Import
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Step 3: Preview Data</h3>
                    </div>
                    
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="alert alert-success">
                                    <h4><i class="fa fa-check"></i> Perfect Matches (Ready to Import)</h4>
                                    <h2><?php echo isset($perfect_matches) ? $perfect_matches : '0'; ?></h2>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-danger">
                                    <h4><i class="fa fa-exclamation-triangle"></i> Rows with Errors</h4>
                                    <h2><?php echo isset($error_matches) ? $error_matches : '0'; ?></h2>
                                </div>
                            </div>
                        </div>

                        <!-- DEBUG INFORMATION -->
                        <div style="display:block; max-height: 200px; overflow-y: auto; background: #f9f9f9; padding: 10px; border: 1px solid #ccc; margin-bottom: 20px;">
                            <h4>Debug Data (Please share this if you still have issues)</h4>
                            <pre>RAW POST MAPPING: <?php print_r(isset($_POST['mapping']) ? $_POST['mapping'] : 'No POST mapping'); ?></pre>
                            <pre>Mapping: <?php print_r(isset($mapping) ? $mapping : 'No mapping'); ?></pre>
                            <pre>First Row: <?php print_r(isset($parsed_data) ? array_slice($parsed_data, 0, 1) : 'No parsed data'); ?></pre>
                        </div>

                        <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Row</th>
                                        <th>Status</th>
                                        <th>Details</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($parsed_data as $row) { ?>
                                        <tr class="<?php echo $row['status'] == 'error' ? 'danger' : 'success'; ?>">
                                            <td><strong><?php echo $row['row_num']; ?></strong></td>
                                            <td>
                                                <?php if($row['status'] == 'error') { ?>
                                                    <span class="label label-danger">Error</span>
                                                <?php } else { ?>
                                                    <span class="label label-success">Valid</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php if($row['status'] == 'error') { ?>
                                                    <ul class="text-danger" style="padding-left: 15px; margin-bottom: 10px;">
                                                        <?php foreach($row['errors'] as $err) { ?>
                                                            <li><?php echo $err; ?></li>
                                                        <?php } ?>
                                                    </ul>
                                                <?php } ?>
                                                
                                                <div style="font-size: 12px; color: #666;">
                                                    <strong>Name:</strong> <?php echo isset($row['student_data']['firstname']) ? $row['student_data']['firstname'] : ''; ?> <?php echo isset($row['student_data']['lastname']) ? $row['student_data']['lastname'] : ''; ?> | 
                                                    <strong>Adm No:</strong> <?php echo isset($row['student_data']['admission_no']) ? $row['student_data']['admission_no'] : ''; ?> | 
                                                    <strong>Class ID:</strong> <?php echo $row['class_id']; ?> | 
                                                    <strong>Section ID:</strong> <?php echo $row['section_id']; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="box-footer">
                        <form action="<?php echo site_url('admin/customstudentimport/confirm') ?>" method="post" id="confirm-form">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <input type="hidden" name="file_name" value="<?php echo $file_name; ?>">
                            <input type="hidden" name="parsed_data" value='<?php echo htmlentities(json_encode($parsed_data), ENT_QUOTES, 'UTF-8'); ?>'>
                            
                            <a href="<?php echo site_url('admin/customstudentimport/index'); ?>" class="btn btn-default"><i class="fa fa-times"></i> Cancel & Start Over</a>
                            
                            <?php if ($perfect_matches > 0) { ?>
                                <button type="submit" class="btn btn-success pull-right" onclick="return confirm('Are you sure you want to import <?php echo $perfect_matches; ?> valid rows? Errors will be ignored.');"><i class="fa fa-upload"></i> Confirm & Import Valid Rows</button>
                            <?php } else { ?>
                                <button type="button" class="btn btn-success pull-right" disabled><i class="fa fa-upload"></i> Confirm & Import Valid Rows</button>
                            <?php } ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
