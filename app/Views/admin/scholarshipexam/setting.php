<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-cogs"></i> Scholarship Module Settings</h1>
    </section>

    <section class="content">
        <?php echo $this->session->flashdata('msg'); ?>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-success" style="border-radius: 10px;">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-qrcode"></i> Public Registration Link &amp; QR Code</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <img src="<?php echo site_url('admin/scholarshipexam/qr_download/png'); ?>" alt="Registration QR" style="width: 190px; height: 190px; border: 1px solid #e2e8f0; padding: 6px; border-radius: 8px;">
                                <div style="margin-top: 10px;">
                                    <a href="<?php echo site_url('admin/scholarshipexam/qr_download/png'); ?>" class="btn btn-success btn-sm" download><i class="fa fa-download"></i> PNG</a>
                                    <a href="<?php echo site_url('admin/scholarshipexam/qr_download/svg'); ?>" class="btn btn-default btn-sm" download><i class="fa fa-download"></i> SVG (print)</a>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <p><strong>Permanent QR link (never changes):</strong><br>
                                    <code><?php echo htmlspecialchars($permanent_url); ?></code></p>
                                <p class="text-muted">The QR code only contains the permanent link above, so printed posters and brochures keep working. Change the destination below whenever the public form link changes.</p>
                                <form action="<?php echo site_url('admin/scholarshipexam/setting'); ?>" method="POST">
                                    <input type="hidden" name="save_public_link" value="1">
                                    <label>Where should the QR / permanent link open?</label>
                                    <input type="text" name="public_link_target" class="form-control" value="<?php echo htmlspecialchars($public_link_target); ?>" placeholder="Leave empty to open the first exam's form">
                                    <p class="help-block">Full URL (https://...) or a site path (e.g. <code>scholarshipregister/apply/SSAT2026</code>). Currently opens: <code><?php echo htmlspecialchars($public_link_target !== '' ? $public_link_target : $default_target); ?></code></p>
                                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa fa-check"></i> Save Link</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

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
