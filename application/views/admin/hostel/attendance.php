<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-building-o"></i> <?php echo $this->lang->line('hostel'); ?></h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom theme-shadow">
                    <ul class="nav nav-tabs pull-right">
                        <li><a href="<?php echo site_url('admin/hostelattendance/monthly_report'); ?>">Monthly Report</a></li>
                        <li class="active"><a href="#tab_daily" data-toggle="tab">Daily Attendance</a></li>
                        <li class="pull-left header"><i class="fa fa-calendar-check-o"></i> Select Criteria</li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_daily">
                            <form action="<?php echo site_url('admin/hostelattendance/index') ?>" method="post" accept-charset="utf-8">
                                <div class="box-body">
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('hostel'); ?></label><small class="req"> *</small>
                                                <select autofocus="" id="hostel_id" name="hostel_id" class="form-control" >
                                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                    <?php foreach ($hostellist as $hostel) { ?>
                                                        <option value="<?php echo $hostel['id'] ?>" <?php if (isset($hostel_id) && $hostel_id == $hostel['id']) echo "selected"; ?>><?php echo $hostel['hostel_name'] ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('hostel_id'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('date'); ?></label><small class="req"> *</small>
                                                <input id="date" name="date" placeholder="" type="text" class="form-control date" value="<?php echo isset($date) ? $date : date($this->customlib->getSchoolDateFormat()); ?>" readonly="readonly"/>
                                                <span class="text-danger"><?php echo form_error('date'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Roll Call Type</label><small class="req"> *</small>
                                                <select id="roll_call_type" name="roll_call_type" class="form-control" >
                                                    <option value="morning" <?php if (isset($roll_call_type) && $roll_call_type == 'morning') echo "selected"; ?>>Morning</option>
                                                    <option value="evening" <?php if (isset($roll_call_type) && $roll_call_type == 'evening') echo "selected"; ?>>Evening</option>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('roll_call_type'); ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button type="submit" name="search" value="search" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <?php if (isset($students)) { ?>
                    <div class="box box-info">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-users"></i> Hostel Attendance List (<?php echo isset($roll_call_type) ? ucfirst($roll_call_type) : 'Morning'; ?>)</h3>
                            <div class="box-tools pull-right">
                                <!-- Global Checkboxes for mark all -->
                                <?php foreach ($attendencetypeslist as $key => $value) { ?>
                                    <label class="radio-inline">
                                        <input type="radio" name="mark_all" value="<?php echo $value['id']; ?>" class="mark_all_radio"> <?php echo $value['key_value']; ?>
                                    </label>
                                <?php } ?>
                            </div>
                        </div>
                        <form action="<?php echo site_url('admin/hostelattendance/save') ?>" method="post">
                            <div class="box-body">
                                <input type="hidden" name="hostel_id" value="<?php echo $hostel_id; ?>">
                                <input type="hidden" name="date" value="<?php echo $date; ?>">
                                <input type="hidden" name="roll_call_type" value="<?php echo isset($roll_call_type) ? $roll_call_type : 'morning'; ?>">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover example">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Student Name</th>
                                                <th>Admission No</th>
                                                <th>Room No</th>
                                                <th>Bed No</th>
                                                <th>Attendance</th>
                                                <th>Remark</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($students)) { ?>
                                                <tr>
                                                    <td colspan="7" class="text-danger text-center">No students found in this hostel.</td>
                                                </tr>
                                            <?php } else {
                                                $count = 1;
                                                foreach ($students as $student) {
                                                    $student_session_id = $student['student_session_id'];
                                                    $attend_data = isset($attendance[$student_session_id]) ? $attendance[$student_session_id] : null;
                                                    ?>
                                                    <tr>
                                                        <td>
                                                            <input type="hidden" name="student_session[]" value="<?php echo $student_session_id; ?>">
                                                            <?php echo $count++; ?>
                                                        </td>
                                                        <td>
                                                            <?php echo $student['firstname'] . ' ' . $student['lastname']; ?>
                                                            <?php 
                                                            if (isset($gatepasses) && in_array($student['student_id'], $gatepasses)) {
                                                                echo '<span class="label label-warning pull-right" data-toggle="tooltip" title="Issued from Front Desk">Gatepass Issued</span>';
                                                            }
                                                            if (isset($transport_presence) && array_key_exists($student['student_session_id'], $transport_presence)) {
                                                                echo '<span class="label label-info pull-right" style="margin-right: 5px;">On Bus</span>';
                                                            }
                                                            ?>
                                                        </td>
                                                        <td><?php echo $student['admission_no']; ?></td>
                                                        <td><?php echo $student['room_no']; ?></td>
                                                        <td><?php echo $student['hostel_bed_no']; ?></td>
                                                        <td>
                                                            <?php foreach ($attendencetypeslist as $key => $value) {
                                                                $checked = "";
                                                                if ($attend_data) {
                                                                    if ($attend_data['attendence_type_id'] == $value['id']) {
                                                                        $checked = "checked";
                                                                    }
                                                                } else {
                                                                    // Default to Present (if 1 is Present) or whatever logic
                                                                    if ($value['id'] == 1) { // Assuming 1 is Present
                                                                        $checked = "checked";
                                                                    }
                                                                }
                                                                ?>
                                                                <label class="radio-inline">
                                                                    <input type="radio" name="attendencetype<?php echo $student_session_id; ?>" value="<?php echo $value['id']; ?>" class="attend_radio_<?php echo $value['id']; ?>" <?php echo $checked; ?>> <?php echo $value['key_value']; ?>
                                                                </label>
                                                            <?php } ?>
                                                        </td>
                                                        <td>
                                                            <input type="text" name="remark<?php echo $student_session_id; ?>" value="<?php echo $attend_data ? $attend_data['remark'] : ''; ?>" class="form-control" style="width: 100%;">
                                                        </td>
                                                    </tr>
                                                <?php }
                                            } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <?php if (!empty($students)) { ?>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-info pull-right">Save Attendance</button>
                                </div>
                            <?php } ?>
                        </form>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.mark_all_radio').on('change', function () {
            var val = $(this).val();
            $('.attend_radio_' + val).prop('checked', true);
        });
    });
</script>
