<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-cogs"></i> Scholarship Module Settings</h1>
    </section>

    <section class="content">
        <?php echo $this->session->flashdata('msg'); ?>

        <div class="row">
            <div class="col-md-12">
                <form action="<?php echo site_url('admin/scholarshipexam/setting'); ?>" method="POST">
                    <div class="box box-primary" style="border-radius: 10px;">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-sliders"></i> Registration Form Field Customization</h3>
                            <div class="box-tools pull-right">
                                <button type="submit" class="btn btn-primary btn-sm" style="border-radius: 6px;"><i class="fa fa-check"></i> Save Field Settings</button>
                            </div>
                        </div>

                        <div class="box-body table-responsive">
                            <p class="text-muted" style="margin-bottom: 15px;">Configure which student fields (from standard admission form) should appear on the public registration page and whether they are required.</p>

                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr style="background: #f8fafc;">
                                        <th style="width: 50px;">#</th>
                                        <th>Field Name (Label)</th>
                                        <th class="text-center" style="width: 200px;">Show in Registration Form</th>
                                        <th class="text-center" style="width: 200px;">Make Field Required</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($field_settings)) {
                                        $cnt = 1;
                                        foreach ($field_settings as $f) {
                                            $fname = $f['field_name'];
                                            ?>
                                            <tr>
                                                <td><?php echo $cnt++; ?></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($f['field_label']); ?></strong>
                                                    <br><small class="text-muted">Field Key: <code><?php echo htmlspecialchars($fname); ?></code></small>
                                                </td>
                                                <td class="text-center">
                                                    <label class="switch" style="cursor: pointer;">
                                                        <input type="checkbox" name="fields[<?php echo $fname; ?>][is_visible]" value="1" <?php echo ($f['is_visible'] == 1) ? 'checked' : ''; ?>>
                                                        <span class="label label-<?php echo ($f['is_visible'] == 1) ? 'success' : 'default'; ?>"><?php echo ($f['is_visible'] == 1) ? 'ENABLED' : 'DISABLED'; ?></span>
                                                    </label>
                                                </td>
                                                <td class="text-center">
                                                    <label class="switch" style="cursor: pointer;">
                                                        <input type="checkbox" name="fields[<?php echo $fname; ?>][is_required]" value="1" <?php echo ($f['is_required'] == 1) ? 'checked' : ''; ?>>
                                                        <span class="label label-<?php echo ($f['is_required'] == 1) ? 'danger' : 'default'; ?>"><?php echo ($f['is_required'] == 1) ? 'REQUIRED' : 'OPTIONAL'; ?></span>
                                                    </label>
                                                </td>
                                            </tr>
                                        <?php }
                                    } ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="box-footer text-right">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> Save Field Settings</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>
