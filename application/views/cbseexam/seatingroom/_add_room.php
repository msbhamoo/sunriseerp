<div class="modal fade" id="addRoomModal" tabindex="-1" role="dialog" aria-labelledby="addRoomModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="addRoomModalLabel">Add Room</h4>
            </div>
            <form id="formAddRoom" action="<?php echo site_url('cbseexam/seatingroom/add_room') ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id" id="room_id" value="0">
                    <div class="form-group">
                        <label>Building / Block <small class="req"> *</small></label>
                        <select class="form-control" name="building_id" id="room_building_id">
                            <option value="">Select</option>
                            <?php foreach ($buildings as $building) { ?>
                                <option value="<?php echo $building['id']; ?>"><?php echo $building['name']; ?></option>
                            <?php } ?>
                        </select>
                        <span class="text-danger" id="error_room_building_id"></span>
                    </div>
                    <div class="form-group">
                        <label>Room Number <small class="req"> *</small></label>
                        <input type="text" class="form-control" name="room_number" id="room_number">
                        <span class="text-danger" id="error_room_room_number"></span>
                    </div>
                    <div class="form-group">
                        <label>Floor</label>
                        <input type="text" class="form-control" name="floor" id="room_floor">
                    </div>
                    <div class="form-group">
                        <label>Capacity <small class="req"> *</small></label>
                        <input type="number" class="form-control" name="seating_capacity" id="room_seating_capacity" value="30">
                        <span class="text-danger" id="error_room_seating_capacity"></span>
                    </div>
                    <div class="form-group">
                        <label>Room Type</label>
                        <select class="form-control" name="room_type" id="room_type">
                            <option value="classroom">Classroom</option>
                            <option value="lab">Lab</option>
                            <option value="hall">Hall</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><input type="checkbox" name="is_active" id="room_is_active" value="1" checked> Active</label>
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
    function addRoomModal() {
        $('#formAddRoom')[0].reset();
        $('#room_id').val(0);
        $('#addRoomModalLabel').text('Add Room');
        $('#addRoomModal').modal('show');
    }

    function editRoom(data) {
        $('#formAddRoom')[0].reset();
        $('#room_id').val(data.id);
        $('#room_building_id').val(data.building_id);
        $('#room_number').val(data.room_number);
        $('#room_floor').val(data.floor);
        $('#room_seating_capacity').val(data.seating_capacity);
        $('#room_type').val(data.room_type);
        $('#room_is_active').prop('checked', data.is_active == 1);
        $('#addRoomModalLabel').text('Edit Room');
        $('#addRoomModal').modal('show');
    }

    $(document).ready(function (e) {
        $("#formAddRoom").on('submit', (function (e) {
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
                            var errorDiv = '#error_room_' + index;
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
