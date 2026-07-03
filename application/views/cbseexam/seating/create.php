<?php $this->load->view('layout/cbseexam_css.php'); ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-users"></i> Create New Allocation</h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Allocation Configuration Wizard</h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo site_url('cbseexam/seatingarrangement') ?>" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Back</a>
                        </div>
                    </div>
                    <form action="<?php echo site_url('cbseexam/seatingarrangement/create') ?>" method="post">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('msg')) {
                                echo $this->session->flashdata('msg');
                            } ?>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Select Exam <small class="req"> *</small></label>
                                        <select class="form-control" name="exam_id" required>
                                            <option value="">Select</option>
                                            <?php foreach ($exams as $exam) { ?>
                                                <option value="<?php echo $exam['id']; ?>"><?php echo $exam['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('exam_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Exam Date <small class="req"> *</small></label>
                                        <input type="text" class="form-control date" name="exam_date" required readonly="readonly" style="background-color: #fff;" />
                                        <span class="text-danger"><?php echo form_error('exam_date'); ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Allocation Strategy <small class="req"> *</small></label>
                                        <select class="form-control" name="allocation_strategy" required>
                                            <option value="interleaved">Interleaved (Mixed Classes - Prevents Cheating)</option>
                                            <option value="grouped">Grouped (Class by Class)</option>
                                        </select>
                                        <small class="text-muted">Interleaved allocates one student from each class sequentially.</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Seat Number Format <small class="req"> *</small></label>
                                        <select class="form-control" name="seat_number_format" required>
                                            <option value="sequential">Sequential (1, 2, 3...)</option>
                                            <option value="room_prefixed">Room Prefixed (Room101-1, Room101-2...)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <hr>
                            <h4>Select Rooms <small class="req"> *</small></h4>
                            <span class="text-danger"><?php echo form_error('rooms[]'); ?></span>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover">
                                            <thead>
                                                <tr>
                                                    <th width="50"><input type="checkbox" id="checkAll"></th>
                                                    <th>Building</th>
                                                    <th>Room No</th>
                                                    <th>Capacity</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($rooms as $room) { ?>
                                                    <tr>
                                                        <td><input type="checkbox" name="rooms[]" class="room-checkbox" value="<?php echo $room['id']; ?>" data-capacity="<?php echo $room['seating_capacity']; ?>"></td>
                                                        <td><?php echo $room['building_name']; ?></td>
                                                        <td><?php echo $room['room_number']; ?></td>
                                                        <td><?php echo $room['seating_capacity']; ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3" class="text-right">Total Selected Capacity:</th>
                                                    <th id="totalCapacity">0</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary pull-right"><i class="fa fa-cogs"></i> Generate Allocation</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        $('.date').datepicker({
            format: "dd-mm-yyyy",
            autoclose: true,
            todayHighlight: true
        });
        
        function updateTotalCapacity() {
            var total = 0;
            $('.room-checkbox:checked').each(function() {
                total += parseInt($(this).data('capacity'));
            });
            $('#totalCapacity').text(total);
        }
        
        $('#checkAll').change(function() {
            $('.room-checkbox').prop('checked', $(this).prop('checked'));
            updateTotalCapacity();
        });
        
        $('.room-checkbox').change(function() {
            updateTotalCapacity();
            if(!$(this).prop('checked')){
                $('#checkAll').prop('checked', false);
            }
        });
    });
</script>
