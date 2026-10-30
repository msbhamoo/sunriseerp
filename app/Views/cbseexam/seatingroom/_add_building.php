<div class="modal fade" id="addBuildingModal" tabindex="-1" role="dialog" aria-labelledby="addBuildingModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addBuildingModalLabel">Add Building</h4>
            </div>
            <form id="formAddBuilding" action="<?php echo site_url('cbseexam/seatingroom/add_building') ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id" id="building_id" value="0">
                    <div class="form-group">
                        <label>Name <small class="req"> *</small></label>
                        <input type="text" class="form-control" name="name" id="building_name">
                        <span class="text-danger" id="error_building_name"></span>
                    </div>
                    <div class="form-group">
                        <label>Code</label>
                        <input type="text" class="form-control" name="code" id="building_code">
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea class="form-control" name="description" id="building_description"></textarea>
                    </div>
                    <div class="form-group">
                        <label><input type="checkbox" name="is_active" id="building_is_active" value="1" checked> Active</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" data-loading-text="<i class='fa fa-spinner fa-spin '></i> Saving...">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function addBuildingModal() {
        $('#formAddBuilding')[0].reset();
        $('#building_id').val(0);
        $('#addBuildingModalLabel').text('Add Building');
        $('#addBuildingModal').modal('show');
    }

    function editBuilding(data) {
        $('#formAddBuilding')[0].reset();
        $('#building_id').val(data.id);
        $('#building_name').val(data.name);
        $('#building_code').val(data.code);
        $('#building_description').val(data.description);
        $('#building_is_active').prop('checked', data.is_active == 1);
        $('#addBuildingModalLabel').text('Edit Building');
        $('#addBuildingModal').modal('show');
    }

    $(document).ready(function (e) {
        $("#formAddBuilding").on('submit', (function (e) {
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
                            var errorDiv = '#error_building_' + index;
                            $(errorDiv).html(value);
                        });
                    } else {
                        successMsg(res.message);
                        window.location.reload();
                    }
                },
                error: function (xhr) { // if error occured
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
