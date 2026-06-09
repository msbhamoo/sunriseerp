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
                        <h3 class="box-title">Step 2: Map Columns</h3>
                    </div>
                    
                    <form action="<?php echo site_url('admin/customstudentimport/preview') ?>" method="post">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <input type="hidden" name="file_name" value="<?php echo $file_name; ?>">
                            
                            <div class="alert alert-info">
                                Match your CSV columns to the appropriate system fields. If a column in your CSV doesn't have a matching system field, you can select "Ignore this column".
                            </div>

                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Your CSV Header</th>
                                        <th>Map to System Field</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($csv_headers as $index => $header) { 
                                        $header = trim($header);
                                        if($header == '') continue;
                                        $auto_select = isset($auto_map[$header]) ? $auto_map[$header] : '';
                                    ?>
                                        <tr>
                                            <td><strong><?php echo $header; ?></strong></td>
                                            <td>
                                                <select name="mapping[<?php echo bin2hex($header); ?>]" class="form-control">
                                                    <option value="" <?php echo $auto_select == '' ? 'selected' : ''; ?>>Ignore this column</option>
                                                    <optgroup label="Standard Fields">
                                                        <?php foreach ($standard_fields as $field_key => $field_label) { 
                                                            if($field_key == '') continue;
                                                        ?>
                                                            <option value="<?php echo $field_key; ?>" <?php echo $auto_select == $field_key ? 'selected' : ''; ?>><?php echo $field_label; ?></option>
                                                        <?php } ?>
                                                    </optgroup>
                                                    
                                                    <?php if(!empty($custom_fields)) { ?>
                                                        <optgroup label="Custom Fields">
                                                            <?php foreach ($custom_fields as $cf) { 
                                                                $cf_val = 'custom_' . $cf['id'];
                                                            ?>
                                                                <option value="<?php echo $cf_val; ?>" <?php echo $auto_select == $cf_val ? 'selected' : ''; ?>>Custom Field: <?php echo $cf['name']; ?></option>
                                                            <?php } ?>
                                                        </optgroup>
                                                    <?php } ?>
                                                </select>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="box-footer">
                            <a href="<?php echo site_url('admin/customstudentimport/index'); ?>" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back to Upload</a>
                            <button type="submit" class="btn btn-primary pull-right">Next: Preview Data <i class="fa fa-arrow-right"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
