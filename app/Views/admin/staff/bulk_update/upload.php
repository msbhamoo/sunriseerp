<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-sitemap"></i> <?php echo $this->lang->line('human_resource'); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-upload"></i> Bulk Staff Detail Update (CSV Import)</h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo site_url('admin/staff/export_staff_id_name'); ?>" class="btn btn-primary btn-sm">
                                <i class="fa fa-download"></i> Download Staff ID & Name CSV
                            </a>
                            <a href="<?php echo site_url('admin/staff/export_staff_sample'); ?>" class="btn btn-info btn-sm">
                                <i class="fa fa-file-excel-o"></i> Download Full Staff CSV Template
                            </a>
                        </div>
                    </div>
                    <form action="<?php echo site_url('admin/staff/bulk_update'); ?>" method="post" enctype="multipart/form-data">
                        <?php echo $this->customlib->getCSRF(); ?>
                        <div class="box-body">
                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php echo $this->session->flashdata('msg'); $this->session->unset_userdata('msg'); ?>
                            <?php } ?>

                            <div class="alert alert-info">
                                <h4><i class="icon fa fa-info"></i> Instructions</h4>
                                <ul>
                                    <li>Download either the <strong>Staff ID & Name CSV</strong> (for quick reference) or the <strong>Full Staff CSV Template</strong> (pre-filled with current staff details).</li>
                                    <li>Edit fields such as <strong>Email, Password, Basic Salary, Bank Account No, Bank Name, IFSC Code, Department, Designation</strong> in Excel/Google Sheets.</li>
                                    <li>Keep <strong>employee_id</strong> unchanged as it is used to match existing staff records.</li>
                                    <li>Click anywhere in the box below to select your updated CSV file.</li>
                                </ul>
                            </div>

                            <div class="form-group">
                                <label>Select CSV File</label><small class="req"> *</small>
                                <label for="file" style="display: block; cursor: pointer; border: 2px dashed #3b82f6; border-radius: 8px; padding: 30px; text-align: center; background: #eff6ff; margin-top: 5px; transition: background 0.2s ease;">
                                    <i class="fa fa-cloud-upload" style="font-size: 42px; color: #2563eb; margin-bottom: 10px;"></i>
                                    <h4 style="font-weight: 600; margin: 5px 0; color: #1e3a8a;" id="upload_title_text">Click Here to Select CSV File</h4>
                                    <p style="color: #64748b; margin-bottom: 0;" id="upload_sub_text">Supports .csv files</p>
                                    <input type="file" name="file" id="file" accept=".csv" required style="display: none;">
                                </label>
                                <span class="text-danger"><?php echo form_error('file'); ?></span>
                            </div>
                        </div>

                        <div class="box-footer">
                            <button type="submit" class="btn btn-success pull-right" style="font-weight: 600; padding: 8px 22px;">
                                <i class="fa fa-arrow-right"></i> Upload & Preview Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('#file').on('change', function () {
            var fileName = $(this).val().split('\\').pop();
            if (fileName) {
                $('#upload_title_text').html('<i class="fa fa-file-excel-o text-success"></i> Selected File: ' + fileName);
                $('#upload_sub_text').text('Click again to change file');
            }
        });
    });
</script>
