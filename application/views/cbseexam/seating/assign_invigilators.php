<?php $this->load->view('layout/cbseexam_css.php'); ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-user-plus"></i> Assign Invigilators</h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Exam: <?php echo $allocation['exam_name']; ?> (<?php echo $this->customlib->dateformat($allocation['exam_date']); ?>)</h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo site_url('cbseexam/seatingarrangement') ?>" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                    <form action="<?php echo site_url('cbseexam/seatingarrangement/assign_invigilators/'.$allocation['id']) ?>" method="post">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('msg')) {
                                echo $this->session->flashdata('msg');
                            } ?>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Building</th>
                                            <th>Room No</th>
                                            <th>Seats Used / Capacity</th>
                                            <th>Assign Staff (Invigilators)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($rooms as $room) { 
                                            // Extract existing invigilator IDs to pre-select
                                            $selected_staff = [];
                                            if (!empty($room['invigilators'])) {
                                                foreach ($room['invigilators'] as $inv) {
                                                    $selected_staff[] = $inv['staff_id'];
                                                }
                                            }
                                        ?>
                                            <tr>
                                                <td><?php echo $room['building_name']; ?></td>
                                                <td><?php echo $room['room_number']; ?></td>
                                                <td><?php echo $room['seats_used']; ?> / <?php echo $room['seating_capacity']; ?></td>
                                                <td>
                                                    <select class="form-control select2" name="staff_id[<?php echo $room['id']; ?>][]" multiple="multiple" data-placeholder="Select Staff">
                                                        <?php foreach ($staffs as $staff) { ?>
                                                            <option value="<?php echo $staff['id']; ?>" <?php if(in_array($staff['id'], $selected_staff)) echo "selected"; ?>>
                                                                <?php echo $staff['name']." ".$staff['surname']." (".$staff['employee_id'].")"; ?>
                                                            </option>
                                                        <?php } ?>
                                                    </select>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-save"></i> Save Assignments</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
