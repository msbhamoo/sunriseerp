<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-mortar-board"></i> <?php echo $this->lang->line('academics'); ?> 
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Substitute Planning</h3>
                    </div>
                    <form id="form1" action="" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php echo $this->session->flashdata('msg') ?>
                            <?php } ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="date">Date</label>
                                        <input type="text" name="date" id="date" class="form-control date" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="staff_id">Absent Staff</label>
                                        <select autofocus="" id="staff_id" name="staff_id" class="form-control" required>
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($staff_list as $staff) { ?>
                                                <option value="<?php echo $staff['id'] ?>"><?php echo $staff['name'] . " " . $staff['surname'] . " (" . $staff['employee_id'] . ")" ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" id="search_btn" class="btn btn-primary btn-sm pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div id="leave_status_container" class="box-body" style="display:none;">
                        <h4 id="leave_status_heading"></h4>
                        <div id="leave_details"></div>
                    </div>

                    <div id="timetable_container" class="box-body">
                        <!-- Timetable loaded via ajax -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var base_url = '<?php echo base_url(); ?>';
        var date_format = '<?php echo strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy', 'M' => 'M']); ?>';

        $('.date').datepicker({
            format: date_format,
            autoclose: true,
            todayHighlight: true
        }).on('changeDate', function() {
            loadAbsentStaffList();
        });

        function loadAbsentStaffList() {
            var date = $('#date').val();
            if(!date) return;

            $.ajax({
                type: "POST",
                url: base_url + "admin/substitution/get_absent_staff",
                data: {'date': date},
                dataType: "json",
                success: function(data) {
                    var current_selected = $('#staff_id').val();
                    var html = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
                    if(data.absent_staff && data.absent_staff.length > 0) {
                        html += '<optgroup label="Absent / On Leave Staff (' + data.absent_staff.length + ')">';
                        $.each(data.absent_staff, function(idx, staff) {
                            var status_text = staff.leave_status === 'pending' ? ' (Pending Leave)' : (staff.is_leave ? ' (' + (staff.leave_type ? staff.leave_type : 'Leave') + ')' : ' (Unplanned Absence)');
                            html += '<option value="' + staff.id + '">⚠️ ' + staff.name + ' ' + staff.surname + ' (' + staff.employee_id + ')' + status_text + '</option>';
                        });
                        html += '</optgroup>';
                    }
                    if(data.other_staff && data.other_staff.length > 0) {
                        html += '<optgroup label="Other Active Staff">';
                        $.each(data.other_staff, function(idx, staff) {
                            html += '<option value="' + staff.id + '">' + staff.name + ' ' + staff.surname + ' (' + staff.employee_id + ')</option>';
                        });
                        html += '</optgroup>';
                    }
                    $('#staff_id').html(html);

                    if(current_selected && $('#staff_id option[value="' + current_selected + '"]').length > 0) {
                        $('#staff_id').val(current_selected);
                    } else if(data.absent_staff && data.absent_staff.length > 0) {
                        $('#staff_id').val(data.absent_staff[0].id);
                    }
                    
                    if($('#staff_id').val() != '') {
                        $('#search_btn').trigger('click');
                    } else {
                        $('#timetable_container').html('');
                        $('#leave_status_container').hide();
                    }
                }
            });
        }

        // Auto load absent staff on page load
        loadAbsentStaffList();

        $('#staff_id').on('change', function() {
            if($(this).val() != '') {
                $('#search_btn').trigger('click');
            } else {
                $('#timetable_container').html('');
                $('#leave_status_container').hide();
            }
        });

        $('#search_btn').on('click', function() {
            var date = $('#date').val();
            var staff_id = $('#staff_id').val();

            if(date == '' || staff_id == '') {
                alert('Please select date and staff');
                return;
            }

            // Check Leave Status
            $.ajax({
                type: "POST",
                url: base_url + "admin/substitution/check_leave_status",
                data: {'date': date, 'staff_id': staff_id},
                dataType: "json",
                success: function(data) {
                    $('#leave_status_container').show();
                    if(data.status == 1) {
                        var leave_type_name = data.leave.leave_type ? data.leave.leave_type : 'Leave';
                        var is_pending = data.leave.status === 'pending';
                        var badge_class = is_pending ? 'label-warning' : 'label-success';
                        var status_label = is_pending ? 'Pending Leave Approval' : 'Approved Leave';
                        $('#leave_status_heading').html('<span class="label ' + badge_class + '"><i class="fa fa-clock-o"></i> ' + leave_type_name + ' (' + status_label + ')</span>');
                    } else {
                        $('#leave_status_heading').html('<span class="label label-danger"><i class="fa fa-warning"></i> Unplanned Absence</span>');
                    }
                }
            });

            // Load Timetable
            $.ajax({
                type: "POST",
                url: base_url + "admin/substitution/get_staff_timetable",
                data: {'date': date, 'staff_id': staff_id},
                dataType: "json",
                success: function(data) {
                    $('#timetable_container').html(data.page);
                }
            });
        });

        $(document).on('change', '.substitute_select', function() {
            var el = $(this);
            var substitute_staff_id = el.val();
            var day = el.data('day');
            var time_from = el.data('time_from');
            var time_to = el.data('time_to');
            var timetable_id = el.data('timetable_id');
            var date = $('#date').val();

            var conflict_div = $('#conflict_' + timetable_id);
            conflict_div.html('');
            $('#override_' + timetable_id).val('');

            if(substitute_staff_id != '') {
                $.ajax({
                    type: "POST",
                    url: base_url + "admin/substitution/validate_conflict",
                    data: {
                        'substitute_staff_id': substitute_staff_id,
                        'day': day,
                        'time_from': time_from,
                        'time_to': time_to,
                        'date': date
                    },
                    dataType: "json",
                    success: function(data) {
                        if(data.status == 0) {
                            var msg = '<div class="alert alert-danger" style="margin-top:10px; padding: 5px;">';
                            msg += '<strong>Conflict Warning:</strong> Teacher is already assigned to ' + data.conflict.class + ' (' + data.conflict.section + ') for ' + data.conflict.subject_name + ' at this time.<br/>';
                            msg += '<label><input type="checkbox" class="conflict_override" data-timetable_id="'+timetable_id+'" data-conflict_id="'+data.conflict.id+'"> Proceed anyway? (Removes them from their original class for this period)</label>';
                            msg += '</div>';
                            conflict_div.html(msg);
                            
                            // Uncheck and clear value until checked
                            el.data('valid', false);
                        } else {
                            el.data('valid', true);
                        }
                    }
                });
            }
        });

        $(document).on('change', '.conflict_override', function() {
            var timetable_id = $(this).data('timetable_id');
            var conflict_id = $(this).data('conflict_id');
            if($(this).is(':checked')) {
                $('#override_' + timetable_id).val(conflict_id);
                $('#substitute_' + timetable_id).data('valid', true);
            } else {
                $('#override_' + timetable_id).val('');
                $('#substitute_' + timetable_id).data('valid', false);
            }
        });

        $(document).on('click', '#save_substitution_btn', function() {
            var is_valid = true;
            $('.substitute_select').each(function() {
                if($(this).val() != '' && $(this).data('valid') === false) {
                    alert('Please resolve conflicts before saving.');
                    is_valid = false;
                    return false;
                }
            });

            if(is_valid) {
                var is_unplanned = $('#leave_status_heading').text().indexOf('Unplanned') !== -1 ? 1 : 0;
                var data = $('#substitution_form').serialize() + '&date=' + $('#date').val() + '&absent_staff_id=' + $('#staff_id').val() + '&is_unplanned=' + is_unplanned;
                
                $.ajax({
                    type: "POST",
                    url: base_url + "admin/substitution/save_substitution",
                    data: data,
                    dataType: "json",
                    success: function(res) {
                        if(res.status == 1) {
                            successMsg(res.msg);
                            $('#search_btn').trigger('click');
                        }
                    }
                });
            }
        });
    });
</script>
