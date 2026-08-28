<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-body">
                <table class="table table-striped">
                    <tr>
                        <th><?php echo $this->lang->line('student'); ?></th>
                        <td><?php echo $call['firstname'] . " " . $call['lastname'] . " (" . $call['admission_no'] . ")"; ?></td>
                        <th><?php echo $this->lang->line('class'); ?></th>
                        <td><?php echo $call['class'] . " (" . $call['section'] . ")"; ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $this->lang->line('phone'); ?></th>
                        <td><?php echo $call['phone_number'] . " (" . $call['contact_person'] . ")"; ?></td>
                        <th><?php echo $this->lang->line('call_type'); ?></th>
                        <td><?php echo $call['call_type']; ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $this->lang->line('purpose'); ?></th>
                        <td><?php echo get_purpose_pill($call['purpose_name']); ?></td>
                        <th><?php echo $this->lang->line('status'); ?></th>
                        <td><?php echo get_call_status_pill($call['call_status']); ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $this->lang->line('date'); ?></th>
                        <td><?php echo date($this->customlib->getSchoolDateFormat(true, true), strtotime($call['date'])); ?></td>
                        <th><?php echo $this->lang->line('note'); ?></th>
                        <td><?php echo $call['notes']; ?></td>
                    </tr>
                    <tr>
                        <th>Siblings</th>
                        <td colspan="3">
                            <?php 
                            $sib_arr = [];
                            // Add original student to the top of the list so we can switch back easily
                            $sib_arr[] = '<a href="javascript:void(0)" class="load-sibling-fee" data-session-id="' . $call['student_session_id'] . '" data-name="' . htmlspecialchars($call['firstname'] . " " . $call['lastname']) . '"><i class="fa fa-user"></i> <strong>[Original]</strong> ' . $call['firstname'] . " " . $call['lastname'] . " (" . $call['admission_no'] . ") - " . $call['class'] . " (" . $call['section'] . ")</a>";
                            
                            if (!empty($siblings)) {
                                foreach ($siblings as $sib) {
                                    $sib_arr[] = '<a href="javascript:void(0)" class="load-sibling-fee" data-session-id="' . $sib->student_session_id . '" data-name="' . htmlspecialchars($sib->firstname . " " . $sib->lastname) . '"><i class="fa fa-user"></i> ' . $sib->firstname . " " . $sib->lastname . " (" . $sib->admission_no . ") - " . $sib->class . " (" . $sib->section . ")</a>";
                                }
                            } 
                            echo implode('<br>', $sib_arr);
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" style="padding: 0;">
                            <div id="modal_recent_payment_warning"></div>
                            <table class="table table-bordered" style="margin-bottom: 0;">
                                <thead>
                                    <tr>
                                        <th colspan="6" class="text-center" style="background-color: #f9f9f9;">Fee Summary <span id="fee_for_label" style="font-weight:normal;color:#666;"></span></th>
                                    </tr>
                                    <tr>
                                        <th>Fee Type</th>
                                        <th>Total</th>
                                        <th>Total Collected</th>
                                        <th>Last Collected</th>
                                        <th>Last Collected Date</th>
                                        <th>Due</th>
                                    </tr>
                                </thead>
                                <tbody id="modal_fee_summary_body">
                                    <tr><td colspan="6" class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</td></tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <th>Attendance</th>
                        <td colspan="3" id="modal_att_summary_body"><i class="fa fa-spinner fa-spin"></i> Loading...</td>
                    </tr>
                </table>
            </div>
        </div>

        <h4>Timeline</h4>
        <ul class="timeline timeline-inverse">
            <?php 
            $has_pending = false;
            if (!empty($followups)) {
                foreach ($followups as $fw) {
                    if ($fw['status'] == 'Pending') $has_pending = true;
                    
                    $status_color = 'bg-aqua';
                    $status_badge_class = 'label-info';
                    if ($fw['status'] == 'Completed' || $fw['status'] == 'Resolved') {
                        $status_color = 'bg-green';
                        $status_badge_class = 'label-success';
                    } else if ($fw['status'] == 'Pending') {
                        $status_color = 'bg-yellow';
                        $status_badge_class = 'label-warning';
                    } else if ($fw['status'] == 'Cancelled') {
                        $status_color = 'bg-red';
                        $status_badge_class = 'label-danger';
                    }
            ?>
                <li class="time-label">
                    <span class="<?php echo $status_color; ?>">
                        Due: <?php 
                            $fw_timestamp = strtotime($fw['due_date']);
                            if (date('H:i:s', $fw_timestamp) !== '00:00:00') {
                                echo date($this->customlib->getSchoolDateFormat(), $fw_timestamp) . ' ' . date('h:i A', $fw_timestamp);
                            } else {
                                echo date($this->customlib->getSchoolDateFormat(), $fw_timestamp);
                            }
                        ?>
                    </span>
                </li>
                <li>
                    <i class="fa fa-phone bg-blue"></i>
                    <div class="timeline-item">
                        <h3 class="timeline-header">
                            <strong>Status:</strong> <span class="label <?php echo $status_badge_class; ?>"><?php echo $fw['status']; ?></span>
                            <?php if(!empty($fw['call_status'])) { echo " | <strong>Outcome:</strong> <span class='label label-info'>" . $fw['call_status'] . "</span>"; } ?>
                            <?php if ($fw['status'] == 'Pending' && $this->rbac->hasPrivilege('student_call_log', 'can_edit')) { ?>
                                <a href="#" class="pull-right btn btn-xs btn-primary edit_fw" data-id="<?php echo $fw['id']; ?>" data-remarks="<?php echo $fw['remarks']; ?>">Edit / Complete</a>
                            <?php } ?>
                        </h3>
                        <div class="timeline-body">
                            <p><strong>Priority:</strong> <?php echo $fw['priority']; ?></p>
                            <p><strong>Assigned To:</strong> <?php echo $fw['assigned_name'] . " " . $fw['assigned_surname']; ?></p>
                            <p><strong>Remarks:</strong> <?php echo !empty($fw['remarks']) ? $fw['remarks'] : (!empty($call['notes']) ? $call['notes'] : ''); ?></p>
                        </div>
                    </div>
                </li>
            <?php } } else { ?>
                <li>
                    <div class="timeline-item">
                        <div class="timeline-body">No follow-ups found.</div>
                    </div>
                </li>
            <?php } ?>
            
            <?php if (!$has_pending && !empty($followups)) { ?>
                <li>
                    <i class="fa fa-check bg-green"></i>
                    <div class="timeline-item" style="background-color: #f4fdf4; border-color: #d6e9c6;">
                        <h3 class="timeline-header no-border text-success" style="color: #3c763d;">
                            <strong><i class="fa fa-check-circle"></i> Resolved:</strong> No further follow-ups are scheduled.
                        </h3>
                    </div>
                </li>
            <?php } ?>

            <li><i class="fa fa-clock-o bg-gray"></i></li>
        </ul>
    </div>
</div>

<div class="row" id="edit_fw_form" style="display:none;">
    <div class="col-md-12">
        <hr style="margin: 10px 0 15px 0;">
        <h4 style="margin-top:0;"><i class="fa fa-pencil-square-o text-primary"></i> Complete / Update Follow Up</h4>
        <form id="update_fw_form_id" action="<?php echo site_url('admin/studentcall/update_followup') ?>" method="post">
            <input type="hidden" name="followup_id" id="fw_edit_id">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" id="fw_status" class="form-control">
                            <option value="Pending">Pending</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Completed">Completed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Outcome (If Completed)</label>
                        <select name="call_status" class="form-control">
                            <option value="">-- Select --</option>
                            <option value="Connected">Connected</option>
                            <option value="Not Answered">Not Answered</option>
                            <option value="Busy">Busy</option>
                            <option value="Switched Off">Switched Off</option>
                            <option value="Wrong Number">Wrong Number</option>
                            <option value="Callback Requested">Callback Requested</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Next Follow-up Date</label>
                        <input type="text" name="next_follow_up_date" id="next_fw_date" class="form-control date" placeholder="Creates new task">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Time (Optional)</label>
                        <input type="time" name="next_follow_up_time" id="next_fw_time" class="form-control" placeholder="e.g. 04:30 PM">
                        <div style="margin-top: 4px; display: flex; gap: 4px; flex-wrap: wrap;">
                            <span class="btn btn-default btn-xs fw-time-chip" onclick="$('#next_fw_time').val('10:00');" style="font-size:10px; padding:1px 5px; border-radius:10px;">10:00 AM</span>
                            <span class="btn btn-default btn-xs fw-time-chip" onclick="$('#next_fw_time').val('14:00');" style="font-size:10px; padding:1px 5px; border-radius:10px;">02:00 PM</span>
                            <span class="btn btn-default btn-xs fw-time-chip" onclick="$('#next_fw_time').val('17:00');" style="font-size:10px; padding:1px 5px; border-radius:10px;">05:00 PM</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row" id="next_fw_details" style="display:none; background:#f9f9f9; padding-top:10px; border-radius:4px; margin: 0 0 15px 0;">
                <div class="col-md-12"><p class="text-muted" style="margin-bottom:5px;"><i class="fa fa-info-circle"></i> A new follow-up will be created for the date selected above.</p></div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Next Priority</label>
                        <select name="next_priority" class="form-control">
                            <option value="Low">Low</option>
                            <option value="Medium" selected>Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Assign Next To</label>
                        <select name="next_assigned_to" class="form-control">
                            <option value="">-- Same Assignee --</option>
                            <?php foreach ($staff_list as $staff) { ?>
                                <option value="<?php echo $staff['id'] ?>"><?php echo $staff['name'] . " " . $staff['surname'] . " (" . $staff['employee_id'] . ")" ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Remarks</label>
                        <textarea name="remarks" id="fw_remarks" class="form-control" rows="2" placeholder="Type remarks or click a quick preset below..."></textarea>
                        <div class="quick-notes-preset-container" style="margin-top: 8px;">
                            <small class="text-muted" style="font-weight:600; display:block; margin-bottom: 5px;"><i class="fa fa-bolt text-yellow"></i> Quick Presets (Click to insert):</small>
                            <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                <span class="btn btn-default btn-xs quick-note-chip" data-target="#fw_remarks" style="border-radius: 12px; font-size: 11px; background: #f0f4f8; border: 1px solid #cbd5e1; color: #334155; font-weight: 500; cursor:pointer;"><i class="fa fa-money text-success"></i> Fee Payment Tomorrow</span>
                                <span class="btn btn-default btn-xs quick-note-chip" data-target="#fw_remarks" style="border-radius: 12px; font-size: 11px; background: #f0f4f8; border: 1px solid #cbd5e1; color: #334155; font-weight: 500; cursor:pointer;"><i class="fa fa-calendar text-info"></i> Payment in 2-4 days</span>
                                <span class="btn btn-default btn-xs quick-note-chip" data-target="#fw_remarks" style="border-radius: 12px; font-size: 11px; background: #f0f4f8; border: 1px solid #cbd5e1; color: #334155; font-weight: 500; cursor:pointer;"><i class="fa fa-file-text-o text-primary"></i> Requested Fee Receipt</span>
                                <span class="btn btn-default btn-xs quick-note-chip" data-target="#fw_remarks" style="border-radius: 12px; font-size: 11px; background: #f0f4f8; border: 1px solid #cbd5e1; color: #334155; font-weight: 500; cursor:pointer;"><i class="fa fa-clock-o text-warning"></i> Call back in Evening</span>
                                <span class="btn btn-default btn-xs quick-note-chip" data-target="#fw_remarks" style="border-radius: 12px; font-size: 11px; background: #f0f4f8; border: 1px solid #cbd5e1; color: #334155; font-weight: 500; cursor:pointer;"><i class="fa fa-question-circle text-purple"></i> Exam / Syllabus Inquiry</span>
                                <span class="btn btn-default btn-xs quick-note-chip" data-target="#fw_remarks" style="border-radius: 12px; font-size: 11px; background: #f0f4f8; border: 1px solid #cbd5e1; color: #334155; font-weight: 500; cursor:pointer;"><i class="fa fa-phone text-danger"></i> Wrong / Unreachable No.</span>
                                <span class="btn btn-default btn-xs quick-note-chip" data-target="#fw_remarks" style="border-radius: 12px; font-size: 11px; background: #f0f4f8; border: 1px solid #cbd5e1; color: #334155; font-weight: 500; cursor:pointer;"><i class="fa fa-check text-success"></i> Issue Resolved</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php if (!empty($siblings)) { ?>
                <div class="col-md-12">
                    <div class="form-group" style="background: #fdfdfd; padding: 10px; border: 1px solid #eee; border-radius: 4px;">
                        <label style="color:#337ab7;"><i class="fa fa-copy"></i> Sync Call to Siblings</label>
                        <p class="text-muted" style="font-size:12px; margin-bottom:5px;">Check the boxes below to create an identical call log entry (and next follow-up, if set) for siblings discussed today.</p>
                        <?php foreach ($siblings as $sib) { ?>
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="copy_to_siblings[]" value="<?php echo $sib->student_session_id . '|' . $sib->id; ?>"> 
                                    <?php echo $sib->firstname . " " . $sib->lastname . " (" . $sib->class . " " . $sib->section . ")"; ?>
                                </label>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
                
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary pull-right">Update</button>
                    <button type="button" class="btn btn-default pull-right" style="margin-right:5px;" onclick="$('#edit_fw_form').hide();">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $('.edit_fw').click(function(e){
        e.preventDefault();
        $('#fw_edit_id').val($(this).data('id'));
        $('#fw_remarks').val($(this).data('remarks'));
        $('#fw_status').val('Completed'); // Default to completed when editing
        $('#edit_fw_form').show();
        // Initialize datepicker if not already
        // Initialize datepicker with minDate constraint
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
        $('.date').datepicker({
            format: date_format,
            autoclose: true,
            todayHighlight: true,
            startDate: new Date()
        }).on('changeDate', function() {
            if($(this).val() != '') {
                $('#next_fw_details').slideDown();
            } else {
                $('#next_fw_details').slideUp();
            }
        });
        
        $('#next_fw_date').on('keyup', function() {
            if($(this).val() != '') {
                $('#next_fw_details').slideDown();
            } else {
                $('#next_fw_details').slideUp();
            }
        });
    });

    $('#updateFwForm').submit(function(e){
        e.preventDefault();
        var $btn = $(this).find('button[type="submit"]');
        $btn.button('loading');
        $.ajax({
            url: '<?php echo site_url("admin/studentcall/edit_follow_up_task") ?>',
            type: "POST",
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                $btn.button('reset');
                if(res.status == 'success'){
                    // refresh modal content
                    follow_up('<?php echo $call['id']; ?>');
                    // Refresh underlying main tables
                    if (typeof fetchCallLogsAjax === 'function') {
                        fetchCallLogsAjax();
                    }
                    if ($('#pending_followup_table').length && $.fn.DataTable.isDataTable('#pending_followup_table')) {
                        // refresh page or reload table if needed
                    }
                }
            },
            error: function() {
                $btn.button('reset');
                alert('Update failed. Please try again.');
            }
        });
    });

    function parseISODateLocal(dateStr) {
        if (!dateStr) return null;
        var parts = dateStr.split('-');
        if (parts.length === 3) {
            return new Date(parseInt(parts[0], 10), parseInt(parts[1], 10) - 1, parseInt(parts[2], 10));
        }
        return new Date(dateStr);
    }

    function loadStudentFeeAttendance(student_session_id, label) {
        $('#modal_fee_summary_body').html('<tr><td colspan="6" class="text-center"><i class="fa fa-spinner fa-spin"></i> Loading...</td></tr>');
        $('#modal_att_summary_body').html('<i class="fa fa-spinner fa-spin"></i> Loading...');
        $('#modal_recent_payment_warning').html('');
        if(label) $('#fee_for_label').text('(for ' + label + ')');

        $.ajax({
            url: "<?php echo site_url('admin/certificateregister/get_student_fee_summary_ajax') ?>",
            type: "POST",
            data: { student_session_id: student_session_id },
            dataType: "json",
            success: function (res) {
                var feeHtml = '';
                var recentPayment = null;
                var today = new Date();
                today.setHours(0,0,0,0);

                var checkFeeRow = function(feeType, title, data) {
                    if (!data) return '';
                    var last_amt = data.last_collected > 0 ? parseFloat(data.last_collected).toFixed(2) : '-';
                    var last_date = data.last_collected_date ? data.last_collected_date : '-';
                    var rowStyle = '';
                    var badge = '';

                    if (data.last_collected_date_raw && data.last_collected > 0) {
                        var pDate = parseISODateLocal(data.last_collected_date_raw);
                        if (pDate) {
                            pDate.setHours(0,0,0,0);
                            var diffDays = Math.round((today - pDate) / (1000 * 60 * 60 * 24));
                            if (diffDays >= 0 && diffDays <= 14) {
                                rowStyle = 'style="background-color: #fef2f2;"';
                                var dayText = diffDays === 0 ? 'Today' : (diffDays === 1 ? 'Yesterday' : diffDays + 'd ago');
                                badge = ' <span class="badge bg-red" style="font-size:10px; background-color:#dc2626 !important;" data-toggle="tooltip" title="Paid within last 2 weeks">Recently Paid (' + dayText + ')</span>';
                                if (!recentPayment || diffDays < recentPayment.daysAgo) {
                                    recentPayment = {
                                        type: title,
                                        amount: last_amt,
                                        date: last_date,
                                        daysAgo: diffDays
                                    };
                                }
                            }
                        }
                    }

                    return '<tr ' + rowStyle + '><td>' + title + '</td><td>' + parseFloat(data.total).toFixed(2) + '</td><td>' + parseFloat(data.collected).toFixed(2) + '</td><td>' + last_amt + '</td><td>' + last_date + badge + '</td><td><b>' + parseFloat(data.due).toFixed(2) + '</b></td></tr>';
                };

                var totalDue = 0;
                var totalFeeSum = 0;
                if (res.academic) {
                    feeHtml += checkFeeRow('academic', 'Academic Fees', res.academic);
                    totalDue += parseFloat(res.academic.due || 0);
                    totalFeeSum += parseFloat(res.academic.total || 0);
                }
                if (res.transport && res.transport.total > 0) {
                    feeHtml += checkFeeRow('transport', 'Transport Fees', res.transport);
                    totalDue += parseFloat(res.transport.due || 0);
                    totalFeeSum += parseFloat(res.transport.total || 0);
                }
                if (res.hostel && res.hostel.total > 0) {
                    feeHtml += checkFeeRow('hostel', 'Hostel Fees', res.hostel);
                    totalDue += parseFloat(res.hostel.due || 0);
                    totalFeeSum += parseFloat(res.hostel.total || 0);
                }

                $('#modal_fee_summary_body').html(feeHtml);

                var warningsHtml = '';
                if (totalFeeSum > 0 && totalDue <= 0) {
                    warningsHtml += '<div class="alert alert-success" style="margin: 10px 0; border-left: 5px solid #16a34a; background: #f0fdf4; color: #166534; padding: 10px 12px; font-size: 13px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">' +
                        '<i class="fa fa-check-circle" style="font-size:16px; margin-right:6px; color:#16a34a;"></i>' +
                        '<strong>ALL FEES FULLY PAID:</strong> Total Due balance is <strong>₹0.00</strong>. This follow-up task is auto-resolved with zero balance.' +
                        '</div>';
                }

                if (recentPayment) {
                    var dayLabel = recentPayment.daysAgo === 0 ? 'Today' : (recentPayment.daysAgo === 1 ? 'Yesterday' : recentPayment.daysAgo + ' days ago');
                    warningsHtml += '<div class="alert alert-danger" style="margin: 10px 0; border-left: 5px solid #dc2626; background: #fef2f2; color: #991b1b; padding: 10px 12px; font-size: 13px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">' +
                        '<i class="fa fa-exclamation-triangle" style="font-size:16px; margin-right:6px; color:#dc2626;"></i>' +
                        '<strong>RECENT FEE PAYMENT DETECTED:</strong> This student/family submitted a fee payment of <strong>₹' + recentPayment.amount + '</strong> (' + recentPayment.type + ') on <strong>' + recentPayment.date + '</strong> (' + dayLabel + '). Please verify payment details before requesting fee submission!' +
                        '</div>';
                }
                $('#modal_recent_payment_warning').html(warningsHtml);

                var attHtml = '';
                if (res.history && (res.history.working_days !== undefined && res.history.working_days !== null)) {
                    var working = res.history.working_days ? res.history.working_days : 0;
                    var present = res.history.present_days ? res.history.present_days : 0;
                    var absent = working - present;
                    var perc = res.history.attendance_percentage ? res.history.attendance_percentage + '%' : ((working > 0) ? ((present/working)*100).toFixed(2) + '%' : 'N/A');
                    attHtml += 'Working Days: <b>' + working + '</b> | Present Days: <b>' + present + '</b> | Absent Days: <b>' + absent + '</b> | Percentage: <b>' + perc + '</b>';
                } else {
                    attHtml = 'No attendance history found.';
                }
                $('#modal_att_summary_body').html(attHtml);
            },
            error: function() {
                $('#modal_fee_summary_body').html('<tr><td colspan="6" class="text-center text-danger">Error loading fee details.</td></tr>');
                $('#modal_att_summary_body').html('<span class="text-danger">Error loading attendance details.</span>');
            }
        });
    }

    $(document).ready(function() {
        var default_session_id = '<?php echo $call['student_session_id']; ?>';
        var default_name = '<?php echo htmlspecialchars($call['firstname'] . ' ' . $call['lastname']); ?>';
        loadStudentFeeAttendance(default_session_id, default_name);

        $(document).on('click', '.load-sibling-fee', function(e) {
            e.preventDefault();
            loadStudentFeeAttendance($(this).data('session-id'), $(this).data('name'));
        });
    });
</script>
