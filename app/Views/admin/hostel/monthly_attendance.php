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
                                        <div class="col-md-3">
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
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label><?php echo $this->lang->line('month'); ?></label><small class="req"> *</small>
                                                <select name="month" class="form-control">
                                                    <option value="all" <?php if ($month == 'all') echo "selected"; ?>>All (Annual)</option>
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
                                        <div class="col-md-3">
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
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Roll Call Type</label><small class="req"> *</small>
                                                <select id="roll_call_type" name="roll_call_type" class="form-control" >
                                                    <option value="morning" <?php if (isset($roll_call_type) && $roll_call_type == 'morning') echo "selected"; ?>>Morning</option>
                                                    <option value="evening" <?php if (isset($roll_call_type) && $roll_call_type == 'evening') echo "selected"; ?>>Evening</option>
                                                    <option value="all" <?php if (isset($roll_call_type) && $roll_call_type == 'all') echo "selected"; ?>>All (Cumulative)</option>
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
                            <h3 class="box-title"><i class="fa fa-users"></i> Monthly Attendance Report (<?php echo isset($roll_call_type) ? ucfirst($roll_call_type) : 'Morning'; ?>)</h3>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover example">
                                    <thead>
                                        <tr>
                                            <th>Student Name</th>
                                            <th>Admission No</th>
                                            <th>Room No</th>
                                            <?php if ($month == 'all') { ?>
                                                <?php 
                                                $months_labels = array("01" => "Jan", "02" => "Feb", "03" => "Mar", "04" => "Apr", "05" => "May", "06" => "Jun", "07" => "Jul", "08" => "Aug", "09" => "Sep", "10" => "Oct", "11" => "Nov", "12" => "Dec");
                                                foreach ($months_labels as $m_num => $m_name) { ?>
                                                    <th><?php echo $m_name; ?></th>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <?php for ($i = 1; $i <= $days_in_month; $i++) { ?>
                                                    <th><?php echo sprintf("%02d", $i); ?></th>
                                                <?php } ?>
                                            <?php } ?>
                                            <?php foreach ($attendencetypeslist as $at) { ?>
                                                <?php if (isset($roll_call_type) && $roll_call_type == 'all') { ?>
                                                    <th class="text-center">Total <?php echo $at['key_value']; ?> (M)</th>
                                                    <th class="text-center">Total <?php echo $at['key_value']; ?> (E)</th>
                                                <?php } else { ?>
                                                    <th class="text-center">Total <?php echo $at['key_value']; ?></th>
                                                <?php } ?>
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
                                                
                                                // Initialize counters for cumulative report
                                                $totals = array();
                                                $totals_m = array();
                                                $totals_e = array();
                                                foreach($attendencetypeslist as $at) {
                                                    $totals[$at['key_value']] = 0;
                                                    $totals_m[$at['key_value']] = 0;
                                                    $totals_e[$at['key_value']] = 0;
                                                }

                                                ?>
                                                <tr>
                                                    <td><?php echo $student['firstname'] . ' ' . $student['lastname']; ?></td>
                                                    <td><?php echo $student['admission_no']; ?></td>
                                                    <td><?php echo $student['room_no']; ?></td>
                                                    
                                                    <?php if ($month == 'all') { 
                                                        $months_labels = array("01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12");
                                                        foreach ($months_labels as $m_num) {
                                                            $m_totals = array();
                                                            foreach($attendencetypeslist as $at) { $m_totals[$at['key_value']] = 0; }
                                                            
                                                            $days_in_this_month = cal_days_in_month(CAL_GREGORIAN, $m_num, $year);
                                                            for ($i = 1; $i <= $days_in_this_month; $i++) {
                                                                if(isset($attendance[$student_session_id][$m_num][$i]['morning'])) {
                                                                    $type_id = $attendance[$student_session_id][$m_num][$i]['morning'];
                                                                    $m_val = isset($att_types[$type_id]) ? $att_types[$type_id] : '';
                                                                    if($m_val != '' && ($roll_call_type == 'morning' || $roll_call_type == 'all')) {
                                                                        $m_totals[$m_val]++;
                                                                        $totals[$m_val]++;
                                                                        $totals_m[$m_val]++;
                                                                    }
                                                                }
                                                                if(isset($attendance[$student_session_id][$m_num][$i]['evening'])) {
                                                                    $type_id = $attendance[$student_session_id][$m_num][$i]['evening'];
                                                                    $e_val = isset($att_types[$type_id]) ? $att_types[$type_id] : '';
                                                                    if($e_val != '' && ($roll_call_type == 'evening' || $roll_call_type == 'all')) {
                                                                        $m_totals[$e_val]++;
                                                                        $totals[$e_val]++;
                                                                        $totals_e[$e_val]++;
                                                                    }
                                                                }
                                                            }
                                                            
                                                            $cell_display = array();
                                                            foreach($m_totals as $k => $v) {
                                                                if ($v > 0) $cell_display[] = "$k:$v";
                                                            }
                                                            $display_str = empty($cell_display) ? "-" : implode(", ", $cell_display);
                                                            ?>
                                                            <td class="text-center" style="font-size: 11px; white-space: nowrap;"><?php echo $display_str; ?></td>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <?php for ($i = 1; $i <= $days_in_month; $i++) { 
                                                            $m_val = "";
                                                            $e_val = "";
                                                            
                                                            if(isset($attendance[$student_session_id][$month][$i]['morning'])) {
                                                                $type_id = $attendance[$student_session_id][$month][$i]['morning'];
                                                                $m_val = isset($att_types[$type_id]) ? $att_types[$type_id] : '';
                                                                if($m_val != '' && ($roll_call_type == 'morning' || $roll_call_type == 'all')) {
                                                                    $totals[$m_val]++;
                                                                    $totals_m[$m_val]++;
                                                                }
                                                            }
                                                            if(isset($attendance[$student_session_id][$month][$i]['evening'])) {
                                                                $type_id = $attendance[$student_session_id][$month][$i]['evening'];
                                                                $e_val = isset($att_types[$type_id]) ? $att_types[$type_id] : '';
                                                                if($e_val != '' && ($roll_call_type == 'evening' || $roll_call_type == 'all')) {
                                                                    $totals[$e_val]++;
                                                                    $totals_e[$e_val]++;
                                                                }
                                                            }

                                                            if ($roll_call_type == 'all') {
                                                                $display_val = "";
                                                                if($m_val != "") $display_val .= $m_val; else $display_val .= "-";
                                                                $display_val .= "/";
                                                                if($e_val != "") $display_val .= $e_val; else $display_val .= "-";
                                                                if ($display_val == "-/-") $display_val = "-";
                                                            } else if ($roll_call_type == 'morning') {
                                                                $display_val = ($m_val != "") ? $m_val : "-";
                                                            } else {
                                                                $display_val = ($e_val != "") ? $e_val : "-";
                                                            }
                                                            ?>
                                                            <td class="text-center" style="font-size: 12px; white-space: nowrap;">
                                                                <?php echo $display_val; ?>
                                                            </td>
                                                        <?php } ?>
                                                    <?php } ?>
                                                    
                                                    <?php foreach ($attendencetypeslist as $at) { 
                                                        $count = $totals[$at['key_value']];
                                                        $count_m = $totals_m[$at['key_value']];
                                                        $count_e = $totals_e[$at['key_value']];
                                                    ?>
                                                        <?php if ($roll_call_type == 'all') { ?>
                                                            <td class="text-center" style="font-weight: bold; background: #f9fafb;"><?php echo $count_m; ?></td>
                                                            <td class="text-center" style="font-weight: bold; background: #f9fafb;"><?php echo $count_e; ?></td>
                                                        <?php } else { ?>
                                                            <td class="text-center" style="font-weight: bold; background: #f9fafb;"><?php echo $count; ?></td>
                                                        <?php } ?>
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
