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
                        <td><?php echo $call['purpose_name']; ?></td>
                        <th><?php echo $this->lang->line('status'); ?></th>
                        <td><?php echo $call['call_status']; ?></td>
                    </tr>
                    <tr>
                        <th><?php echo $this->lang->line('date'); ?></th>
                        <td><?php echo date($this->customlib->getSchoolDateFormat(true, true), strtotime($call['date'])); ?></td>
                        <th><?php echo $this->lang->line('note'); ?></th>
                        <td><?php echo $call['notes']; ?></td>
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
                    if ($fw['status'] == 'Completed') $status_color = 'bg-green';
                    else if ($fw['status'] == 'Pending') $status_color = 'bg-yellow';
                    else if ($fw['status'] == 'Cancelled') $status_color = 'bg-red';
            ?>
                <li class="time-label">
                    <span class="<?php echo $status_color; ?>">
                        Due: <?php echo date($this->customlib->getSchoolDateFormat(), strtotime($fw['due_date'])); ?>
                    </span>
                </li>
                <li>
                    <i class="fa fa-phone bg-blue"></i>
                    <div class="timeline-item">
                        <h3 class="timeline-header">
                            <strong>Status:</strong> <span class="label label-default"><?php echo $fw['status']; ?></span>
                            <?php if(!empty($fw['call_status'])) { echo " | <strong>Outcome:</strong> <span class='label label-info'>" . $fw['call_status'] . "</span>"; } ?>
                            <?php if ($fw['status'] == 'Pending' && $this->rbac->hasPrivilege('student_call_log', 'can_edit')) { ?>
                                <a href="#" class="pull-right btn btn-xs btn-primary edit_fw" data-id="<?php echo $fw['id']; ?>" data-remarks="<?php echo $fw['remarks']; ?>">Edit / Complete</a>
                            <?php } ?>
                        </h3>
                        <div class="timeline-body">
                            <p><strong>Priority:</strong> <?php echo $fw['priority']; ?></p>
                            <p><strong>Assigned To:</strong> <?php echo $fw['assigned_name'] . " " . $fw['assigned_surname']; ?></p>
                            <p><strong>Remarks:</strong> <?php echo $fw['remarks']; ?></p>
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
    <hr>
    <div class="col-md-12">
        <h4>Complete / Update Follow Up</h4>
        <form id="updateFwForm" method="post">
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
                <div class="col-md-4">
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
                <div class="col-md-5">
                    <div class="form-group">
                        <label>Next Follow-up Date (Optional)</label>
                        <input type="text" name="next_follow_up_date" id="next_fw_date" class="form-control date" placeholder="Creates new task">
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
                        <textarea name="remarks" id="fw_remarks" class="form-control" rows="2"></textarea>
                    </div>
                </div>
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
        $.ajax({
            url: '<?php echo site_url("admin/studentcall/edit_follow_up_task") ?>',
            type: "POST",
            data: $(this).serialize(),
            dataType: 'json',
            success: function (res) {
                if(res.status == 'success'){
                    // refresh modal content
                    follow_up('<?php echo $call['id']; ?>');
                }
            }
        });
    });
</script>
