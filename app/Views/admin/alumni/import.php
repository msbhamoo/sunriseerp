<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-mortar-board"></i> <?php echo $this->lang->line('alumni'); ?></h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-upload"></i> <?php echo $title; ?></h3>
                        <div class="pull-right box-tools">
                            <a href="<?php echo site_url('admin/alumni/exportformat') ?>">
                                <button class="btn btn-primary btn-sm"><i class="fa fa-download"></i> <?php echo $this->lang->line('download_sample_import_file'); ?></button>
                            </a>
                        </div>
                    </div>

                    <form action="<?php echo site_url('admin/alumni/import') ?>" id="employeeform" name="employeeform" method="post" enctype="multipart/form-data">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="exampleInputFile"><?php echo $this->lang->line('select_csv_file'); ?></label><small class="req"> *</small>
                                        <div><input class="filestyle form-control" type='file' name='file' id="file" size='20' />
                                            <span class="text-danger"><?php echo form_error('file'); ?></span></div>
                                    </div>
                                </div>
                                <div class="col-md-6 pt20">
                                    <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('import_alumni'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('instructions'); ?></h3>
                    </div>

                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <p>1. Your CSV data should be in the format below. The first line of your CSV file should be the column headers as in the table example. Also make sure that your file is UTF-8 to avoid unnecessary encoding problems.</p>
                                <p>2. If the column <b>admission_no</b> is empty, it will be automatically generated (if enabled in general settings).</p>
                                <p>3. Columns <b>passout_session</b>, <b>class</b>, and <b>section</b> must precisely match the names exactly as they are created in your system.</p>
                                <p>4. Example: "2012-13", "Class 12", "A".</p>
                                <hr />
                            </div>

                            <div class="col-md-12 table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <?php
                                            if (!empty($fields)) {
                                                foreach ($fields as $key => $value) {
                                                    $add = "";
                                                    if ($value == 'admission_no' || $value == 'firstname' || $value == 'passout_session' || $value == 'class' || $value == 'section') {
                                                        $add = "<span class='text-danger'>*</span>";
                                                    }
                                                    ?>
                                                    <th><?php echo $add . " " . $value; ?></th>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <?php
                                            if (!empty($fields)) {
                                                foreach ($fields as $key => $value) {
                                                    ?>
                                                    <td><?php echo "Sample Data" ?></td>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>
