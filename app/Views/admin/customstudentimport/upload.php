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
                        <h3 class="box-title">Step 1: Upload CSV File</h3>
                    </div>
                    
                    <form action="<?php echo site_url('admin/customstudentimport/mapping') ?>" id="employeeform" name="employeeform" method="post" enctype="multipart/form-data">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('msg')) { ?> 
                                <?php echo $this->session->flashdata('msg'); ?>
                            <?php } ?>
                            <?php echo $this->customlib->getCSRF(); ?>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="file">Select CSV File</label>
                                        <input type="file" name="file" id="file" class="form-control filestyle" accept=".csv" required />
                                        <span class="text-danger"><?php echo form_error('file'); ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-info">
                                        <h4>Instructions:</h4>
                                        <ol>
                                            <li>Save your Excel sheet as a <strong>CSV (Comma delimited) (*.csv)</strong> file.</li>
                                            <li>Ensure the file has a header row (e.g., S.No, Class, Student Name, etc.).</li>
                                            <li>Upload the file here. In the next step, you will be able to map your columns to the system fields.</li>
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary pull-right">Next: Map Columns <i class="fa fa-arrow-right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
