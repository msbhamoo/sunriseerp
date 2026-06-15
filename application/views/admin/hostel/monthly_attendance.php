<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-building-o"></i> <?php echo $this->lang->line('hostel'); ?></h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom theme-shadow">
                    <ul class="nav nav-tabs pull-right">
                        <li class="active"><a href="#tab_monthly" data-toggle="tab">Monthly Report</a></li>
                        <li><a href="<?php echo site_url('admin/hostelattendance/index'); ?>">Daily Attendance</a></li>
                        <li class="pull-left header"><i class="fa fa-calendar-check-o"></i> Select Criteria</li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_monthly">
                            <form action="<?php echo site_url('admin/hostelattendance/monthly_report') ?>" method="post" accept-charset="utf-8">
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
                                                <label><?php echo $this->lang->line('month'); ?></label><small class="req"> *</small>
                                                <select name="month" class="form-control">
                                                    <?php
                                                    $months = array("01" => "January", "02" => "February", "03" => "March", "04" => "April", "05" => "May", "06" => "June", "07" => "July", "08" => "August", "09" => "September", "10" => "October", "11" => "November", "12" => "December");
                                                    foreach ($months as $key => $value) {
                                                        ?>
                                                        <option value="<?php echo $key; ?>" <?php if ($month == $key) echo "selected"; ?>><?php echo $value; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('month'); ?></span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('year'); ?></label><small class="req"> *</small>
                                                <select name="year" class="form-control">
                                                    <?php
                                                    $current_year = date('Y');
                                                    for ($i = $current_year - 5; $i <= $current_year; $i++) {
                                                        ?>
                                                        <option value="<?php echo $i; ?>" <?php if ($year == $i) echo "selected"; ?>><?php echo $i; ?></option>
                                                    <?php } ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('year'); ?></span>
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
                            <h3 class="box-title"><i class="fa fa-users"></i> Monthly Attendance Report</h3>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover example">
                                    <thead>
                                        <tr>
                                            <th>Student Name</th>
                                            <th>Admission No</th>
                                            <th>Room No</th>
                                            <?php for ($i = 1; $i <= $days_in_month; $i++) { ?>
                                                <th><?php echo $i; ?></th>
                                            <?php } ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($students)) { ?>
                                            <tr>
                                                <td colspan="<?php echo $days_in_month + 3; ?>" class="text-danger text-center">No students found in this hostel.</td>
                                            </tr>
                                        <?php } else {
                                            // Map attendencetypes to key_value (P, A, L etc)
                                            $att_types = array();
                                            foreach($attendencetypeslist as $at) {
                                                $att_types[$at['id']] = $at['key_value'];
                                            }

                                            foreach ($students as $student) {
                                                $student_session_id = $student['student_session_id'];
                                                ?>
                                                <tr>
                                                    <td><?php echo $student['firstname'] . ' ' . $student['lastname']; ?></td>
                                                    <td><?php echo $student['admission_no']; ?></td>
                                                    <td><?php echo $student['room_no']; ?></td>
                                                    <?php for ($i = 1; $i <= $days_in_month; $i++) { 
                                                        $val = "";
                                                        if(isset($attendance[$student_session_id][$i])) {
                                                            $type_id = $attendance[$student_session_id][$i];
                                                            $val = isset($att_types[$type_id]) ? $att_types[$type_id] : '';
                                                        }

                                                        // Add coloring depending on value
                                                        $label_class = "label-default";
                                                        if($val == 'P') $label_class = 'label-success';
                                                        else if($val == 'A') $label_class = 'label-danger';
                                                        else if($val == 'L') $label_class = 'label-warning';
                                                        else if($val == 'H') $label_class = 'label-info';
                                                        else if($val == 'F') $label_class = 'label-primary';
                                                        
                                                        ?>
                                                        <td class="text-center">
                                                            <?php if($val != "") { ?>
                                                                <span class="label <?php echo $label_class; ?>"><?php echo $val; ?></span>
                                                            <?php } else { echo "-"; } ?>
                                                        </td>
                                                    <?php } ?>
                                                </tr>
                                            <?php }
                                        } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</div>
