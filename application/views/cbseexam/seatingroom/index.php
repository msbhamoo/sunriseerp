<?php $this->load->view('layout/cbseexam_css.php'); ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-building"></i> CBSE Examination Seating Rooms</h1>
    </section>

    <section class="content">
        <div class="row">
            <!-- Buildings List -->
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Buildings / Blocks</h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('cbse_exam_seating_rooms', 'can_add')) { ?>
                                <button type="button" class="btn btn-primary btn-sm" onclick="addBuildingModal()"><i class="fa fa-plus"></i> Add</button>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($buildings as $building) { ?>
                                        <tr>
                                            <td><?php echo $building['name']; ?></td>
                                            <td><?php echo $building['code']; ?></td>
                                            <td class="text-right">
                                                <?php if ($this->rbac->hasPrivilege('cbse_exam_seating_rooms', 'can_edit')) { ?>
                                                    <a href="#" class="btn btn-default btn-xs" onclick="editBuilding(<?php echo htmlspecialchars(json_encode($building)); ?>)" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil"></i></a>
                                                <?php } ?>
                                                <?php if ($this->rbac->hasPrivilege('cbse_exam_seating_rooms', 'can_delete')) { ?>
                                                    <a href="<?php echo site_url('cbseexam/seatingroom/delete_building/'.$building['id']); ?>" class="btn btn-default btn-xs" onclick="return confirm('Delete this building?');" data-toggle="tooltip" title="Delete"><i class="fa fa-remove"></i></a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Rooms List -->
            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Rooms</h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('cbse_exam_seating_rooms', 'can_add')) { ?>
                                <button type="button" class="btn btn-success btn-sm" onclick="bulkGenerateModal()"><i class="fa fa-magic"></i> Bulk Generate</button>
                                <button type="button" class="btn btn-primary btn-sm" onclick="addRoomModal()"><i class="fa fa-plus"></i> Add Room</button>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped example">
                                <thead>
                                    <tr>
                                        <th>Building</th>
                                        <th>Room No</th>
                                        <th>Floor</th>
                                        <th>Capacity</th>
                                        <th>Type</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($rooms as $room) { ?>
                                        <tr>
                                            <td><?php echo $room['building_name']; ?></td>
                                            <td><?php echo $room['room_number']; ?></td>
                                            <td><?php echo $room['floor']; ?></td>
                                            <td><?php echo $room['seating_capacity']; ?></td>
                                            <td><?php echo ucfirst($room['room_type']); ?></td>
                                            <td class="text-right">
                                                <?php if ($this->rbac->hasPrivilege('cbse_exam_seating_rooms', 'can_edit')) { ?>
                                                    <a href="#" class="btn btn-default btn-xs" onclick="editRoom(<?php echo htmlspecialchars(json_encode($room)); ?>)" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil"></i></a>
                                                <?php } ?>
                                                <?php if ($this->rbac->hasPrivilege('cbse_exam_seating_rooms', 'can_delete')) { ?>
                                                    <a href="<?php echo site_url('cbseexam/seatingroom/delete_room/'.$room['id']); ?>" class="btn btn-default btn-xs" onclick="return confirm('Delete this room?');" data-toggle="tooltip" title="Delete"><i class="fa fa-remove"></i></a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modals -->
<?php 
$this->load->view('cbseexam/seatingroom/_add_building'); 
$this->load->view('cbseexam/seatingroom/_add_room');
$this->load->view('cbseexam/seatingroom/_bulk_generate');
?>

<script>
    // Include DataTables
    $(document).ready(function() {
        $('.example').DataTable({
            "aaSorting": [],
            "bSort": true,
            "bPaginate": true,
            "bInfo": true,
            "bFilter": true
        });
    });
</script>
