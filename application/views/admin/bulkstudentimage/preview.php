<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-file-image-o"></i> Bulk Student Image Upload - Preview
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Step 2: Preview & Confirm</h3>
                    </div>
                    
                    <div class="box-body">
                        <?php if (count($matched) > 0) { ?>
                            <h4>Matched Students (<?php echo count($matched); ?>)</h4>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Admission No</th>
                                            <th>Student Name</th>
                                            <th>Old Photo</th>
                                            <th>New Photo File</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($matched as $match) { 
                                            $old_image = $match['old_image'];
                                            if (empty($old_image)) {
                                                $old_image = 'uploads/student_images/no_image.png';
                                            } else {
                                                if (strpos($old_image, 'uploads/student_images/') === false) {
                                                    $old_image = 'uploads/student_images/' . $old_image;
                                                }
                                            }
                                        ?>
                                            <tr>
                                                <td><?php echo $match['admission_no']; ?></td>
                                                <td><?php echo $match['name']; ?></td>
                                                <td>
                                                    <img src="<?php echo base_url($old_image); ?>" alt="Old Photo" style="width: 50px; height: 50px; object-fit: cover;" class="img-thumbnail" />
                                                </td>
                                                <td><?php echo $match['new_file']; ?></td>
                                                <td><span class="label label-success"><i class="fa fa-check"></i> Matched</span></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } else { ?>
                            <div class="alert alert-danger">
                                No images matched any student admission numbers. Please check your filenames.
                            </div>
                        <?php } ?>

                        <?php if (count($unmatched) > 0) { ?>
                            <hr />
                            <h4>Unmatched Files (<?php echo count($unmatched); ?>)</h4>
                            <div class="alert alert-warning">
                                The following files did not match any active student's admission number in the system. They will be ignored.
                            </div>
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Filename</th>
                                            <th>Extracted Admission No</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($unmatched as $unmatch) { ?>
                                            <tr>
                                                <td><?php echo $unmatch['file']; ?></td>
                                                <td><?php echo $unmatch['admission_no']; ?></td>
                                                <td><span class="label label-danger"><i class="fa fa-times"></i> Unmatched</span></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php } ?>
                    </div>
                    
                    <div class="box-footer">
                        <form action="<?php echo site_url('admin/bulkstudentimage/confirm') ?>" method="post" id="confirm-form">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <button type="submit" name="cancel" value="1" class="btn btn-default"><i class="fa fa-times"></i> Cancel & Start Over</button>
                            
                            <?php if (count($matched) > 0) { ?>
                                <button type="submit" class="btn btn-success pull-right"><i class="fa fa-upload"></i> Confirm & Upload <?php echo count($matched); ?> Images</button>
                            <?php } ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
