<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-file-image-o"></i> Bulk Student Image Upload - Result
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Step 3: Upload Complete</h3>
                    </div>
                    
                    <div class="box-body">
                        <?php if ($result['success'] > 0) { ?>
                            <div class="alert alert-success">
                                <h4><i class="icon fa fa-check"></i> Success!</h4>
                                Successfully updated images for <strong><?php echo $result['success']; ?></strong> students.
                            </div>
                        <?php } ?>
                        
                        <?php if ($result['failed'] > 0) { ?>
                            <div class="alert alert-danger">
                                <h4><i class="icon fa fa-ban"></i> Error!</h4>
                                Failed to update images for <strong><?php echo $result['failed']; ?></strong> students.
                            </div>
                        <?php } ?>
                        
                        <?php if ($result['success'] == 0 && $result['failed'] == 0) { ?>
                            <div class="alert alert-warning">
                                <h4><i class="icon fa fa-warning"></i> Warning!</h4>
                                No images were updated.
                            </div>
                        <?php } ?>
                    </div>
                    
                    <div class="box-footer">
                        <a href="<?php echo site_url('admin/bulkstudentimage'); ?>" class="btn btn-primary"><i class="fa fa-upload"></i> Upload More Images</a>
                        <a href="<?php echo site_url('student/search'); ?>" class="btn btn-default"><i class="fa fa-search"></i> Go to Student Search</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
