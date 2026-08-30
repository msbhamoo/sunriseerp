<style>
.btn-touch-status {
    font-weight: 600 !important;
    padding: 5px 9px !important;
    font-size: 12px !important;
    border-radius: 4px !important;
    margin-right: 2px !important;
    margin-bottom: 2px !important;
    border: 1px solid #ccc !important;
    background-color: #f8f9fa !important;
    color: #495057 !important;
    transition: all 0.15s ease-in-out !important;
}
.btn-touch-status:hover {
    opacity: 0.9;
}
.btn-present-active {
    background-color: #27ae60 !important;
    color: #ffffff !important;
    border-color: #27ae60 !important;
    box-shadow: 0 1px 3px rgba(39, 174, 96, 0.3) !important;
}
.btn-absent-active {
    background-color: #e74c3c !important;
    color: #ffffff !important;
    border-color: #e74c3c !important;
    box-shadow: 0 1px 3px rgba(231, 76, 60, 0.3) !important;
}
.btn-hostel-active {
    background-color: #8e44ad !important;
    color: #ffffff !important;
    border-color: #8e44ad !important;
    box-shadow: 0 1px 3px rgba(142, 68, 173, 0.3) !important;
}
.btn-gatepass-active {
    background-color: #f39c12 !important;
    color: #ffffff !important;
    border-color: #f39c12 !important;
    box-shadow: 0 1px 3px rgba(243, 156, 18, 0.3) !important;
}
.sticky-save-bar {
    position: sticky;
    bottom: 0;
    background: #ffffff;
    padding: 8px 15px;
    border-top: 2px solid #3c8dbc;
    box-shadow: 0 -3px 10px rgba(0,0,0,0.12);
    z-index: 1020;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 0 0 4px 4px;
}
@media (max-width: 768px) {
    .sticky-save-bar {
        flex-direction: column;
        gap: 8px;
        text-align: center;
    }
    .btn-touch-status {
        padding: 6px 6px !important;
        font-size: 11px !important;
        flex: 1;
    }
    .status-toggle-group {
        display: flex !important;
        flex-wrap: wrap;
        width: 100%;
    }
}
</style>

<div class="content-wrapper">
    <section class="content-header" style="display:flex; justify-content:space-between; align-items:center;">
        <h1 style="margin:0;">
            <i class="fa fa-bus"></i> <?php echo $this->lang->line('transport'); ?>
        </h1>
        <div>
            <a href="<?php echo site_url('admin/transportattendance/mobile'); ?>" class="btn btn-sm btn-primary" style="font-weight:600; border-radius:6px; box-shadow:0 2px 4px rgba(0,0,0,0.15);">
                <i class="fa fa-mobile-phone fa-lg"></i> Open Mobile Quick Mode
            </a>
        </div>
    </section>
    
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Select Criteria</h3>
                    </div>
                    <form id="attendance_form" action="<?php echo site_url('admin/transportattendance/index'); ?>" method="post">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="date"><?php echo $this->lang->line('date'); ?></label> <small class="req"> *</small>
                                        <input type="text" id="date" name="date" class="form-control date" value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat())); ?>" autocomplete="off" />
                                        <span class="text-danger"><?php echo form_error('date'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Vehicle / Bus</label> <small class="req"> *</small>
                                        <select class="form-control select2" name="vehicle_id" id="vehicle_id" style="width:100%;">
                                            <option value="">Select Bus</option>
                                            <?php 
                                            $auto_select_single = (isset($vehiclelist) && count($vehiclelist) == 1);
                                            foreach ($vehiclelist as $vehicle) { 
                                                $selected = set_select('vehicle_id', $vehicle['id'], (isset($vehicle_id) && $vehicle_id == $vehicle['id']));
                                                if (empty($selected) && $auto_select_single && !isset($_POST['vehicle_id'])) {
                                                    $selected = 'selected="selected"';
                                                }
                                            ?>
                                                <option value="<?php echo $vehicle['id'] ?>" <?php echo $selected; ?>><?php echo $vehicle['vehicle_no'] ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('vehicle_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Route (Optional Filter)</label>
                                        <select class="form-control select2" name="route_id" id="route_id" style="width:100%;">
                                            <option value="">All Routes (Entire Bus)</option>
                                            <?php 
                                            if (isset($vehicle_routes) && !empty($vehicle_routes)) {
                                                $seen_routes = array();
                                                foreach ($vehicle_routes as $vr) {
                                                    // Extract route_id or fallback
                                                    $curr_r_id = isset($vr['route_id']) ? $vr['route_id'] : $vr['vehroute_id'];
                                                    if (!isset($seen_routes[$curr_r_id])) {
                                                        $seen_routes[$curr_r_id] = true;
                                                        $r_selected = (isset($route_id) && $route_id == $curr_r_id) ? 'selected="selected"' : '';
                                                        echo '<option value="' . $curr_r_id . '" ' . $r_selected . '>' . $vr['route_title'] . '</option>';
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('route_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Attendance Type</label> <small class="req"> *</small>
                                        <select class="form-control select2" name="attendance_type" style="width:100%;">
                                            <option value="">Select Shift</option>
                                            <option value="morning" <?php echo set_select('attendance_type', 'morning', (isset($attendance_type) && $attendance_type == 'morning')); ?>>Morning</option>
                                            <option value="evening" <?php echo set_select('attendance_type', 'evening', (isset($attendance_type) && $attendance_type == 'evening')); ?>>Evening</option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('attendance_type'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" name="search" value="search" class="btn btn-primary pull-right btn-sm"><i class="fa fa-search"></i> Search Students</button>
                        </div>
                    </form>
                </div>
                
                <?php if (isset($resultlist)) { ?>
                    <div class="box box-info" id="attendance">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-users"></i> Student List</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#customRiderModal">
                                    <i class="fa fa-plus"></i> Add Custom Rider
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php echo $this->session->flashdata('msg') ?>
                            <?php } ?>
                            
                            <?php if (empty($resultlist)) { ?>
                                <div class="alert alert-info">No students found.</div>
                            <?php } else { ?>

                                <!-- Live Counter Summary Cards -->
                                <div class="row" style="margin-bottom:12px;">
                                    <div class="col-xs-6 col-sm-3">
                                        <div class="small-box bg-aqua" style="border-radius:6px; margin-bottom:8px;">
                                            <div class="inner" style="padding:8px 12px;">
                                                <h3 id="count_total" style="font-size:18px; margin:0 0 2px 0; font-weight:700;">0</h3>
                                                <p style="margin:0; font-weight:600; text-transform:uppercase; font-size:11px;">Total Students</p>
                                            </div>
                                            <div class="icon" style="top:5px; font-size:30px;"><i class="fa fa-users"></i></div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-3">
                                        <div class="small-box bg-green" style="border-radius:6px; margin-bottom:8px;">
                                            <div class="inner" style="padding:8px 12px;">
                                                <h3 id="count_present" style="font-size:18px; margin:0 0 2px 0; font-weight:700;">0</h3>
                                                <p style="margin:0; font-weight:600; text-transform:uppercase; font-size:11px;">Present</p>
                                            </div>
                                            <div class="icon" style="top:5px; font-size:30px;"><i class="fa fa-check-circle"></i></div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-3">
                                        <div class="small-box bg-red" style="border-radius:6px; margin-bottom:8px;">
                                            <div class="inner" style="padding:8px 12px;">
                                                <h3 id="count_absent" style="font-size:18px; margin:0 0 2px 0; font-weight:700;">0</h3>
                                                <p style="margin:0; font-weight:600; text-transform:uppercase; font-size:11px;">Absent</p>
                                            </div>
                                            <div class="icon" style="top:5px; font-size:30px;"><i class="fa fa-times-circle"></i></div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-3">
                                        <div class="small-box bg-yellow" style="border-radius:6px; margin-bottom:8px;">
                                            <div class="inner" style="padding:8px 12px;">
                                                <h3 id="count_other" style="font-size:18px; margin:0 0 2px 0; font-weight:700;">0</h3>
                                                <p style="margin:0; font-weight:600; text-transform:uppercase; font-size:11px;">Gatepass / Other</p>
                                            </div>
                                            <div class="icon" style="top:5px; font-size:30px;"><i class="fa fa-info-circle"></i></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quick Actions & Touch Search Filter Bar -->
                                <div class="row" style="margin-bottom: 12px;">
                                    <div class="col-md-6 col-sm-12" style="margin-bottom:8px;">
                                        <div class="btn-group btn-group-justified" role="group">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-success btn-sm" onclick="markAllStatus('Present')" style="font-weight:600; font-size:12px; padding:6px 10px; border-radius:4px 0 0 4px;">
                                                    <i class="fa fa-check-square-o"></i> Mark All Present
                                                </button>
                                            </div>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-danger btn-sm" onclick="markAllStatus('Absent')" style="font-weight:600; font-size:12px; padding:6px 10px; border-radius:0 4px 4px 0;">
                                                    <i class="fa fa-minus-square-o"></i> Mark All Absent
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12" style="margin-bottom:8px;">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-addon" style="background:#fff;"><i class="fa fa-search"></i></span>
                                            <input type="text" id="touch_student_search" class="form-control" placeholder="Search student name, adm no, or bus stop..." style="font-size:13px;">
                                        </div>
                                    </div>
                                </div>

                                <form id="save_bus_attendance_form" action="<?php echo site_url('admin/transportattendance/save') ?>" method="post">
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <input type="hidden" name="date" value="<?php echo date('Y-m-d', strtotime($date)); ?>">
                                    <input type="hidden" name="vehicle_id" value="<?php echo $vehicle_id; ?>">
                                    <input type="hidden" name="attendance_type" value="<?php echo $attendance_type; ?>">
                                    
                                    <div class="table-responsive p-b-20">
                                        <table class="table table-hover table-striped table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width:10%;">Adm No</th>
                                                    <th style="width:32%;">Student & Stop Info</th>
                                                    <th style="width:12%;">Class (Section)</th>
                                                    <th style="width:15%;"><?php echo isset($opposite_shift) ? $opposite_shift . ' Shift Status' : 'Morning Status'; ?></th>
                                                    <th style="width:21%;">Attendance Action</th>
                                                    <th style="width:10%;">Remark</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($resultlist as $student) { ?>
                                                    <tr class="student-row">
                                                        <td style="vertical-align:middle;">
                                                            <strong><?php echo $student['admission_no']; ?></strong>
                                                            <input type="hidden" name="student_session[]" value="<?php echo $student['student_session_id']; ?>">
                                                            <?php if(isset($student['status']) && $student['status'] == 'Switched Bus') { ?>
                                                                <input type="hidden" name="is_custom_rider<?php echo $student['student_session_id']; ?>" value="yes">
                                                            <?php } ?>
                                                        </td>
                                                        <td style="vertical-align:middle;">
                                                            <div style="display:flex; align-items:center; gap:8px;">
                                                                <?php 
                                                                $img_src = !empty($student['image']) ? base_url($student['image']) : base_url('uploads/student_images/no_image.png');
                                                                ?>
                                                                <img src="<?php echo $img_src; ?>" class="img-circle" style="width:34px; height:34px; object-fit:cover; border:1px solid #ccc;" onerror="this.src='<?php echo base_url('uploads/student_images/no_image.png'); ?>';">
                                                                <div>
                                                                    <strong style="font-size:13px; color:#2c3e50;"><?php echo $student['firstname'] . ' ' . $student['lastname']; ?></strong>
                                                                    <?php if (!empty($student['route_title']) || !empty($student['pickup_point_name'])) { ?>
                                                                        <div style="font-size:11px; color:#555; margin-top:1px;">
                                                                            <?php if (!empty($student['route_title'])) { ?>
                                                                                <span class="text-primary" style="margin-right:6px;"><i class="fa fa-road"></i> <strong><?php echo $student['route_title']; ?></strong></span>
                                                                            <?php } ?>
                                                                            <?php if (!empty($student['pickup_point_name'])) { ?>
                                                                                <span><i class="fa fa-map-marker text-danger"></i> Stop: <strong><?php echo $student['pickup_point_name']; ?></strong></span>
                                                                            <?php } ?>
                                                                        </div>
                                                                    <?php } ?>
                                                                    <?php if (!empty($student['has_gatepass'])) { ?>
                                                                        <span class="label label-warning" style="margin-top:2px; font-size:10px; display:inline-block;"><i class="fa fa-ticket"></i> Gatepass Issued Today</span>
                                                                    <?php } ?>
                                                                    <?php if (isset($student['status']) && $student['status'] == 'Switched Bus') { 
                                                                        if (!empty($student['original_vehicle_no'])) {
                                                                            $rider_type = 'Custom Rider (' . $student['original_vehicle_no'] . ')';
                                                                        } elseif (!empty($student['hostel_room_id']) && $student['hostel_room_id'] > 0) {
                                                                            $rider_type = 'Custom Rider (Hosteler)';
                                                                        } else {
                                                                            $rider_type = 'Custom Rider (Day Scholar)';
                                                                        }
                                                                    ?>
                                                                        <span class="label label-info" style="margin-top:2px; font-size:10px; display:inline-block;"><?php echo $rider_type; ?></span>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td style="vertical-align:middle; font-size:12px;"><?php echo $student['class'] . ' (' . $student['section'] . ')'; ?></td>
                                                        <td style="vertical-align:middle;">
                                                            <?php
                                                            $opp_status = isset($student['opposite_shift_status']) ? $student['opposite_shift_status'] : 'Not Marked';
                                                            $opp_title = isset($opposite_shift) ? $opposite_shift : 'Morning';
                                                            if ($opp_status == 'Present') {
                                                                echo '<span class="label label-success" style="font-size:11px; padding:4px 6px;"><i class="fa fa-sun-o"></i> ' . $opp_title . ': Present</span>';
                                                            } elseif ($opp_status == 'Absent') {
                                                                echo '<span class="label label-danger" style="font-size:11px; padding:4px 6px;"><i class="fa fa-sun-o"></i> ' . $opp_title . ': Absent</span>';
                                                            } elseif ($opp_status == 'Switched Bus') {
                                                                echo '<span class="label label-info" style="font-size:11px; padding:4px 6px;"><i class="fa fa-exchange"></i> ' . $opp_title . ': Switched Bus</span>';
                                                            } elseif ($opp_status == 'Gatepass') {
                                                                echo '<span class="label label-warning" style="font-size:11px; padding:4px 6px;"><i class="fa fa-ticket"></i> ' . $opp_title . ': Gatepass</span>';
                                                            } elseif ($opp_status == 'Hostel') {
                                                                echo '<span class="label" style="background-color:#8e44ad; color:#fff; font-size:11px; padding:4px 6px;"><i class="fa fa-building"></i> ' . $opp_title . ': Hostel</span>';
                                                            } elseif (strpos($opp_status, 'Present') !== false) {
                                                                echo '<span class="label label-success" style="font-size:11px; padding:4px 6px;"><i class="fa fa-bus"></i> ' . $opp_title . ': ' . $opp_status . '</span>';
                                                            } else {
                                                                echo '<span class="label label-default" style="background-color:#95a5a6; color:#fff; font-size:11px; padding:4px 6px;">' . $opp_title . ': Not Marked</span>';
                                                            }
                                                            ?>
                                                        </td>
                                                        <td style="vertical-align:middle;">
                                                            <?php if (isset($student['status']) && $student['status'] == 'Switched Bus') { ?>
                                                                <input type="hidden" name="attendencetype<?php echo $student['student_session_id']; ?>" value="Switched Bus">
                                                                <input type="hidden" name="is_custom_rider<?php echo $student['student_session_id']; ?>" value="yes">
                                                                <span class="label label-info" style="font-size:12px; padding:5px 10px; display:inline-block;">Custom Rider (Present)</span>
                                                            <?php } else { 
                                                                $st_val = $student['attendance_status'];
                                                            ?>
                                                                <input type="hidden" name="attendencetype<?php echo $student['student_session_id']; ?>" id="attendencetype_<?php echo $student['student_session_id']; ?>" class="attendencetype-field" value="<?php echo $st_val; ?>">
                                                                <div class="status-toggle-group" data-session-id="<?php echo $student['student_session_id']; ?>">
                                                                    <button type="button" class="btn btn-touch-status <?php echo ($st_val == 'Present') ? 'btn-present-active' : ''; ?>" data-value="Present">
                                                                        <i class="fa fa-check"></i> Present
                                                                    </button>
                                                                    <button type="button" class="btn btn-touch-status <?php echo ($st_val == 'Absent') ? 'btn-absent-active' : ''; ?>" data-value="Absent">
                                                                        <i class="fa fa-times"></i> Absent
                                                                    </button>
                                                                    <button type="button" class="btn btn-touch-status <?php echo ($st_val == 'Hostel') ? 'btn-hostel-active' : ''; ?>" data-value="Hostel">
                                                                        Hostel
                                                                    </button>
                                                                    <button type="button" class="btn btn-touch-status <?php echo ($st_val == 'Gatepass') ? 'btn-gatepass-active' : ''; ?>" data-value="Gatepass">
                                                                        Gatepass
                                                                    </button>
                                                                </div>
                                                            <?php } ?>
                                                        </td>
                                                        <td style="vertical-align:middle;">
                                                            <div style="display: flex; align-items: center; gap: 4px;">
                                                                <input type="text" name="remark<?php echo $student['student_session_id']; ?>" class="form-control input-sm" value="<?php echo $student['remark']; ?>" placeholder="Remark..." style="flex-grow:1; font-size:12px;">
                                                                <?php if (isset($student['status']) && $student['status'] == 'Switched Bus') { ?>
                                                                    <button type="button" class="btn btn-danger btn-xs" title="Remove custom rider" onclick="removeCustomRider(<?php echo $student['student_session_id']; ?>, this)">
                                                                        <i class="fa fa-times"></i>
                                                                    </button>
                                                                <?php } ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Sticky Bottom Save Action Bar -->
                                    <div class="sticky-save-bar">
                                        <div>
                                            <span style="font-size:13px; font-weight:700; color:#2c3e50;"><i class="fa fa-list-check text-primary"></i> Summary: </span>
                                            <span id="sticky_summary" style="font-size:13px; font-weight:700; color:#27ae60;">0 Present, 0 Absent</span>
                                        </div>
                                        <button type="submit" class="btn btn-success btn-md" style="font-weight:700; padding:6px 20px; font-size:13px; border-radius:4px;">
                                            <i class="fa fa-save"></i> Save Attendance
                                        </button>
                                    </div>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
function updateCounters() {
    var total = $('.student-row').length;
    var present = 0;
    var absent = 0;
    var other = 0;

    $('.attendencetype-field').each(function() {
        var val = $(this).val();
        if (val === 'Present') present++;
        else if (val === 'Absent') absent++;
        else other++;
    });

    $('input[value="Switched Bus"]').each(function() {
        if ($(this).attr('name') && $(this).attr('name').indexOf('attendencetype') === 0) {
            present++;
        }
    });

    $('#count_total').text(total);
    $('#count_present').text(present);
    $('#count_absent').text(absent);
    $('#count_other').text(other);
    $('#sticky_summary').text(present + ' Present, ' + absent + ' Absent');
}

function markAllStatus(status) {
    $('.status-toggle-group').each(function() {
        var sessionId = $(this).data('session-id');
        $('#attendencetype_' + sessionId).val(status);
        $(this).find('.btn-touch-status').removeClass('btn-present-active btn-absent-active btn-hostel-active btn-gatepass-active');
        if (status === 'Present') {
            $(this).find('[data-value="Present"]').addClass('btn-present-active');
        } else if (status === 'Absent') {
            $(this).find('[data-value="Absent"]').addClass('btn-absent-active');
        }
    });
    updateCounters();
}

function removeCustomRider(student_session_id, btn) {
    if (confirm('Are you sure you want to remove this custom rider?')) {
        var vehicle_id = $('select[name="vehicle_id"]').val();
        var date = $('#date').val();
        var attendance_type = $('select[name="attendance_type"]').val();
        
        $.ajax({
            url: "<?php echo site_url('admin/transportattendance/remove_custom_rider') ?>",
            type: "POST",
            data: {
                student_session_id: student_session_id,
                vehicle_id: vehicle_id,
                date: date,
                attendance_type: attendance_type
            },
            dataType: 'json',
            success: function(res) {
                if (res.status == 1) {
                    successMsg(res.msg);
                    $(btn).closest('tr').fadeOut(400, function() {
                        $(this).remove();
                        updateCounters();
                    });
                } else {
                    errorMsg(res.msg);
                }
            }
        });
    }
}

$(document).ready(function() {
    $('.select2').select2();
    updateCounters();

    var initial_route_id = '<?php echo isset($route_id) ? $route_id : ""; ?>';
    
    $('#vehicle_id').on('change', function(e, triggeredByLoad) {
        var vehicle_id = $(this).val();
        var route_select = $('#route_id');
        
        if (triggeredByLoad) {
            return;
        }
        
        route_select.html('<option value="">All Routes (Entire Bus)</option>');
        
        if (vehicle_id) {
            $.ajax({
                url: '<?php echo site_url("admin/transportattendance/get_vehicle_routes"); ?>',
                type: 'POST',
                data: { vehicle_id: vehicle_id },
                dataType: 'json',
                success: function(routes) {
                    if (routes && routes.length > 0) {
                        $.each(routes, function(idx, r) {
                            var sel = (initial_route_id && initial_route_id == r.route_id) ? ' selected="selected"' : '';
                            route_select.append('<option value="' + r.route_id + '"' + sel + '>' + r.route_title + '</option>');
                        });
                    }
                    route_select.trigger('change.select2');
                }
            });
        } else {
            route_select.trigger('change.select2');
        }
    });

    $(document).on('click', '.btn-touch-status', function(e) {
        e.preventDefault();
        var val = $(this).data('value');
        var group = $(this).closest('.status-toggle-group');
        var sessionId = group.data('session-id');
        
        $('#attendencetype_' + sessionId).val(val);
        group.find('.btn-touch-status').removeClass('btn-present-active btn-absent-active btn-hostel-active btn-gatepass-active');
        
        if (val === 'Present') $(this).addClass('btn-present-active');
        else if (val === 'Absent') $(this).addClass('btn-absent-active');
        else if (val === 'Hostel') $(this).addClass('btn-hostel-active');
        else if (val === 'Gatepass') $(this).addClass('btn-gatepass-active');
        
        updateCounters();
    });

    $('#touch_student_search').on('keyup search', function() {
        var query = $(this).val().toLowerCase();
        $('.student-row').each(function() {
            var text = $(this).text().toLowerCase();
            if (text.indexOf(query) !== -1) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    $('#search_student_text').on('keyup', function() {
        var search = $(this).val();
        if (search.length >= 3) {
            $.ajax({
                url: '<?php echo site_url("admin/transportattendance/search_student") ?>',
                type: 'POST',
                data: {search: search},
                dataType: 'json',
                success: function(data) {
                    var html = '<ul class="list-group">';
                    $.each(data, function(index, student) {
                        html += '<li class="list-group-item d-flex justify-content-between align-items-center">';
                        html += student.firstname + ' ' + student.lastname + ' (' + student.admission_no + ') - ' + student.class + ' (' + student.section + ')';
                        html += '<button type="button" class="btn btn-xs btn-success pull-right add_custom_btn" data-studentid="'+student.id+'">Add</button>';
                        html += '</li>';
                    });
                    html += '</ul>';
                    $('#search_results').html(html);
                }
            });
        }
    });

    $(document).on('click', '.add_custom_btn', function() {
        var student_id = $(this).data('studentid');
        var vehicle_id = $('select[name="vehicle_id"]').val();
        var date = $('#date').val();
        var attendance_type = $('select[name="attendance_type"]').val();
        
        if(!vehicle_id || !date || !attendance_type) {
            errorMsg('Please ensure you have selected a date, vehicle, and attendance type on the main page first.');
            return;
        }
        
        $.ajax({
            url: '<?php echo site_url("admin/transportattendance/add_custom_rider") ?>',
            type: 'POST',
            data: {
                student_id: student_id,
                vehicle_id: vehicle_id,
                date: date,
                attendance_type: attendance_type
            },
            dataType: 'json',
            success: function(res) {
                if (res.status == 1) {
                    successMsg(res.msg);
                    setTimeout(function(){ location.reload(); }, 1000);
                } else {
                    errorMsg(res.msg);
                }
            },
            error: function(xhr) {
                errorMsg('An error occurred. Check console for details.');
                console.error(xhr.responseText);
            }
        });
    });
});
</script>

<!-- Custom Rider Modal -->
<div id="customRiderModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Custom Rider</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Search Student (Name or Admission No)</label>
                    <input type="text" id="search_student_text" class="form-control" placeholder="Type to search...">
                </div>
                <div id="search_results" style="max-height: 200px; overflow-y: auto;">
                    
                </div>
            </div>
        </div>
    </div>
</div>
