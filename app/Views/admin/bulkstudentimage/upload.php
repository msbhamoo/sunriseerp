<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-file-image-o"></i> Bulk Student Image Upload
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Step 1: Upload ZIP File</h3>
                    </div>
                    
                    <form action="<?php echo site_url('admin/bulkstudentimage/upload') ?>" id="imageuploadform" name="imageuploadform" method="post" enctype="multipart/form-data">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('msg')) { ?> 
                                <?php echo $this->session->flashdata('msg'); ?>
                            <?php } ?>
                            <?php echo $this->customlib->getCSRF(); ?>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="file">Select ZIP File</label>
                                        <input type="file" name="file" id="file" class="form-control filestyle" accept=".zip" required />
                                        <span class="text-danger"><?php echo form_error('file'); ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <h4>Instructions:</h4>
                                        <ol>
                                            <li>Create a ZIP file containing the student images you want to upload.</li>
                                            <li>The filename of each image <strong>MUST</strong> be the student's Admission Number (e.g. <code>ADM001.jpg</code>, <code>12345.png</code>).</li>
                                            <li>Supported image formats: JPG, JPEG, PNG, GIF, WEBP.</li>
                                            <li>Images should be placed at the root of the ZIP file (do not put them inside a folder within the ZIP).</li>
                                            <li>In the next step, you will see a preview of which images matched which students before confirming.</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary pull-right">Upload & Preview <i class="fa fa-arrow-right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
