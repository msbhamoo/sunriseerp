<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-bus"></i> <?php echo $this->lang->line('transport'); ?></h1>
    </section>
    
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Bulk Assign Pickup Point</h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo base_url(); ?>admin/transportyearlyfee" class="btn btn-sm btn-primary"><i class="fa fa-arrow-left"></i> Back to Yearly Fees</a>
                        </div>
                    </div>
                    
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <p><i class="fa fa-info-circle"></i> Upload a CSV file to bulk assign routes and pickup points to students.</p>
                                    <p>The CSV should contain the Admission Number and Pickup Point Name.</p>
                                </div>
                            </div>
                        </div>
                        
                        <form id="bulkAssignForm" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="csv_file">Select CSV File <small class="req">*</small></label>
                                        <input type="file" name="csv_file" id="csv_file" class="form-control filestyle" required accept=".csv">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="adm_col">Admission No Column Number <small class="req">*</small></label>
                                        <input type="number" name="adm_col" id="adm_col" class="form-control" value="1" min="1" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="pickup_col">Pickup Point Column Number <small class="req">*</small></label>
                                        <input type="number" name="pickup_col" id="pickup_col" class="form-control" value="4" min="1" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <div class="checkbox">
                                            <label><input type="checkbox" name="has_header" value="1" checked> Has Header Row</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12 text-center">
                                    <button type="submit" class="btn btn-info" id="btnPreview" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Processing...">Preview Assignments</button>
                                </div>
                            </div>
                        </form>
                        
                        <hr>
                        
                        <div id="preview_container" style="display:none; margin-top:20px;">
                            <h4>Preview Results</h4>
                            <div id="preview_html"></div>
                            
                            <div class="text-center mt10" id="save_btn_container" style="display:none; margin-top: 20px;">
                                <button type="button" class="btn btn-success" id="btnSave" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving...">Save Assignments</button>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
var assign_data_json = '';

$(document).ready(function() {
    $('#bulkAssignForm').on('submit', function(e) {
        e.preventDefault();
        
        var $btn = $('#btnPreview');
        $btn.button('loading');
        
        var formData = new FormData(this);
        
        $.ajax({
            url: '<?php echo base_url(); ?>admin/transportyearlyfee/preview_bulk_assign',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                $btn.button('reset');
                $('#preview_container').show();
                
                if (response.status === 1) {
                    $('#preview_html').html(response.html);
                    assign_data_json = JSON.stringify(response.data);
                    
                    var validRecords = response.data.filter(function(r) { return r.valid === 1; });
                    if (validRecords.length > 0) {
                        $('#save_btn_container').show();
                    } else {
                        $('#save_btn_container').hide();
                    }
                } else {
                    $('#preview_html').html(response.html);
                    $('#save_btn_container').hide();
                }
            },
            error: function() {
                $btn.button('reset');
                $('#preview_container').show();
                $('#preview_html').html('<div class="alert alert-danger">An error occurred while uploading. Please try again.</div>');
                $('#save_btn_container').hide();
            }
        });
    });
    
    $('#btnSave').on('click', function() {
        if (assign_data_json === '') return;
        
        var $btn = $(this);
        $btn.button('loading');
        
        $.ajax({
            url: '<?php echo base_url(); ?>admin/transportyearlyfee/save_bulk_assign',
            type: 'POST',
            data: { assign_data: assign_data_json },
            dataType: 'json',
            success: function(response) {
                $btn.button('reset');
                if (response.status === 1) {
                    $('#preview_html').prepend('<div class="alert alert-success">' + response.msg + '</div>');
                    $('#save_btn_container').hide();
                    assign_data_json = '';
                } else {
                    alert('Error saving data.');
                }
            },
            error: function() {
                $btn.button('reset');
                alert('An error occurred. Please try again.');
            }
        });
    });
});
</script>
