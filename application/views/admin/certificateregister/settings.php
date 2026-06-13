<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-certificate"></i> Certificate Settings</h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add/Edit Certificate Type</h3>
                    </div>
                    <form action="<?php echo base_url('admin/certificateregister/settings'); ?>" method="post">
                        <div class="box-body">
                            <input type="hidden" name="id" id="type_id" value="">
                            <div class="form-group">
                                <label>Certificate Name</label>
                                <input type="text" name="certificate_name" id="certificate_name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Series Prefix</label>
                                <input type="text" name="series_prefix" id="series_prefix" class="form-control" placeholder="e.g. TC-2026-">
                            </div>
                            <div class="form-group">
                                <label>Start Number</label>
                                <input type="number" name="start_number" id="start_number" class="form-control" value="1" required>
                            </div>
                            <hr>
                            <h4>Configure Print Fields</h4>
                            <div class="checkbox">
                                <label><input type="checkbox" name="show_religion" id="show_religion" value="1" checked> Show Religion</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" name="show_category" id="show_category" value="1" checked> Show Category</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" name="show_handicapped" id="show_handicapped" value="1" checked> Show Handicapped Status</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" name="show_father_contact" id="show_father_contact" value="1" checked> Show Father's Contact</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" name="show_reason" id="show_reason" value="1" checked> Show Reason for Leaving</label>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" name="save" value="save" class="btn btn-info pull-right">Save</button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Certificate Types List</h3>
                    </div>
                    <div class="box-body">
                        <?php echo $this->session->flashdata('msg'); ?>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Prefix</th>
                                        <th>Start No</th>
                                        <th>Current No</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($types)) {
                                        foreach($types as $type) { ?>
                                        <tr>
                                            <td><?php echo $type['certificate_name']; ?></td>
                                            <td><?php echo $type['series_prefix']; ?></td>
                                            <td><?php echo $type['start_number']; ?></td>
                                            <td><?php echo $type['current_number']; ?></td>
                                            <td>
                                                <?php 
                                                    $config_json = htmlspecialchars($type['fields_config'] ?? '{}', ENT_QUOTES, 'UTF-8');
                                                ?>
                                                <button class="btn btn-default btn-xs edit-type" 
                                                    data-id="<?php echo $type['id']; ?>" 
                                                    data-name="<?php echo $type['certificate_name']; ?>" 
                                                    data-prefix="<?php echo $type['series_prefix']; ?>" 
                                                    data-start="<?php echo $type['start_number']; ?>"
                                                    data-config='<?php echo $config_json; ?>'>
                                                    <i class="fa fa-pencil"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $('.edit-type').click(function() {
        $('#type_id').val($(this).data('id'));
        $('#certificate_name').val($(this).data('name'));
        $('#series_prefix').val($(this).data('prefix'));
        $('#start_number').val($(this).data('start'));
        
        // Reset checkboxes to default checked
        $('#show_religion, #show_category, #show_handicapped, #show_father_contact, #show_reason').prop('checked', true);
        
        let configStr = $(this).attr('data-config');
        if (configStr) {
            try {
                let config = JSON.parse(configStr);
                if (Object.keys(config).length > 0) {
                    $('#show_religion').prop('checked', config.show_religion == 1);
                    $('#show_category').prop('checked', config.show_category == 1);
                    $('#show_handicapped').prop('checked', config.show_handicapped == 1);
                    $('#show_father_contact').prop('checked', config.show_father_contact == 1);
                    $('#show_reason').prop('checked', config.show_reason == 1);
                }
            } catch(e) {}
        }
    });
</script>
