<div class="modal fade" id="bulkGenerateModal" tabindex="-1" role="dialog" aria-labelledby="bulkGenerateModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="bulkGenerateModalLabel"><i class="fa fa-magic"></i> Bulk Generate Rooms</h4>
            </div>
            <form id="formBulkGenerate" action="<?php echo site_url('cbseexam/seatingroom/bulk_generate_rooms') ?>" method="post">
                <div class="modal-body">
                    <p class="text-info">Automatically generate multiple sequential rooms. Existing rooms will be skipped to prevent errors.</p>
                    
                    <div class="form-group">
                        <label>Building / Block <small class="req"> *</small></label>
                        <select class="form-control" name="bulk_building_id" id="bulk_building_id">
                            <option value="">Select</option>
                            <?php foreach ($buildings as $building) { ?>
                                <option value="<?php echo $building['id']; ?>"><?php echo $building['name']; ?></option>
                            <?php } ?>
                        </select>
                        <span class="text-danger" id="error_bulk_bulk_building_id"></span>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Prefix <small class="req"> *</small></label>
                                <input type="text" class="form-control" name="prefix" id="bulk_prefix" value="Room-" placeholder="e.g. Room-">
                                <span class="text-danger" id="error_bulk_prefix"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Start No <small class="req"> *</small></label>
                                <input type="number" class="form-control" name="start" id="bulk_start" value="1">
                                <span class="text-danger" id="error_bulk_start"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Count <small class="req"> *</small></label>
                                <input type="number" class="form-control" name="count" id="bulk_count" value="10" max="100">
                                <span class="text-danger" id="error_bulk_count"></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Default Capacity <small class="req"> *</small></label>
                                <input type="number" class="form-control" name="capacity" id="bulk_capacity" value="30">
                                <span class="text-danger" id="error_bulk_capacity"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Room Type</label>
                                <select class="form-control" name="room_type" id="bulk_room_type">
                                    <option value="classroom">Classroom</option>
                                    <option value="lab">Lab</option>
                                    <option value="hall">Hall</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Generating...">Generate Rooms</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function bulkGenerateModal() {
        $('#formBulkGenerate')[0].reset();
        $('#bulkGenerateModal').modal('show');
    }

    $(document).ready(function (e) {
        $("#formBulkGenerate").on('submit', (function (e) {
            e.preventDefault();
            var $this = $(this).find("button[type=submit]:focus");
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    $this.button('loading');
                },
                success: function (res) {
                    if (res.status == "fail") {
                        $.each(res.error, function (index, value) {
                            var errorDiv = '#error_bulk_' + index;
                            $(errorDiv).html(value);
                        });
                    } else {
                        successMsg(res.message);
                        window.location.reload();
                    }
                },
                error: function (xhr) { 
                    alert("Error occured.please try again");
                    $this.button('reset');
                },
                complete: function () {
                    $this.button('reset');
                }
            });
        }));
    });
</script>
