<style type="text/css">

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 22px !important;
        border-radius: 0 !important;
        padding-left: 0 !important;
    }

    .input-group-addon .glyphicon {
        font-size: 12px;
    }

    .show {
        display: block;
        z-index: 100;
        background-image: url('../../backend/images/timeloader.gif');
        opacity: 0.6;
        background-repeat: no-repeat;
        background-position: center;
    }

    .relative {
        position: relative;
    }

    .commentForm .input-group-addon i,
    .commentForm .input-group-addon span {
        /*padding-left: 13px;*/
        padding-top: 0
    }

    .commentForm .relative label.text-danger {
        position: absolute;
        bottom: 5px;
    }


    @media(max-width:767px) {
        .timeresponsive {
            overflow-x: auto;
            overflow-y: hidden;
        }

        .timeresponsive .dropdown-menu {
            z-index: 1060;
            bottom: 0 !important;
            height: 250px;
            padding: 20px;
        }

        .tablewidthRS {
            width: 690px;
        }
    }
</style>
<script src="<?php echo base_url(); ?>backend/custom/jquery.validate.min.js"></script>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <form class="create_time_table" action="<?php echo site_url('admin/timetable/create') ?>" method="post" accept-charset="utf-8">
                        <div class="box-body">

                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('class'); ?><small class="req"> *</small></label>
                                        <select autofocus="" id="class_id" name="class_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($classlist as $class) {
                                            ?>
                                                <option value="<?php echo $class['id'] ?>" <?php
                                                                                            if (set_value('class_id') == $class['id']) {
                                                                                                echo "selected=selected";
                                                                                            }
                                                                                            ?>><?php echo $class['class'] ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('section'); ?><small class="req"> *</small></label>
                                        <select id="section_id" name="section_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('subject_group'); ?><small class="req"> *</small></label>
                                        <select id="subject_group_id" name="subject_group_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('subject_group_id'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" id="insertbtn" class="btn btn-primary pull-right"><?php echo $this->lang->line('search'); ?></button>
                        </div>
                    </form>

                    <?php
                    if (isset($getDaysnameList)) {
                    ?>
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_parameter_to_generate_time_table_quickly'); ?></h3>

                        </div>
                        <div class="box-header ptbnull">
                            <form action="#" method="POST" id="universal_from">
                                <input type="hidden" name="active_tab_day" id="active_tab_day">
                                <div class="row">
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="total_periods">Total Periods<small class="req"> *</small></label>
                                            <input type="number" name="total_periods" class="form-control" id="total_periods" value="" min="1">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="form_name"><?php echo $this->lang->line('period_start_time'); ?><small class="req"> *</small></label>
                                            <div class="input-group">
                                                <input type="text" name="start_time" class="form-control datetimepicker3 " id="start_time" value="">
                                                <div class="input-group-addon"><span class="fa fa-clock-o"></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="form_email"><?php echo $this->lang->line('duration_minute'); ?><small class="req"> *</small></label>
                                            <div class="input-group">
                                                <input type="number" name="duration" class="form-control" id="duration" value="">
                                                <div class="input-group-addon"><span class="fa fa-hourglass-start"></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="form_phone"><?php echo $this->lang->line('interval_minute'); ?><small class="req"> *</small></label>
                                            <div class="input-group">
                                                <input type="number" name="interval" class="form-control" id="interval" value="0">
                                                <div class="input-group-addon"><span class="fa fa-hourglass-start"></span></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="periods_before_break">Periods Before Break</label>
                                            <input type="number" name="periods_before_break" class="form-control" id="periods_before_break" value="" min="1">
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="break_duration">Break Duration (min)</label>
                                            <div class="input-group">
                                                <input type="number" name="break_duration" class="form-control" id="break_duration" value="">
                                                <div class="input-group-addon"><span class="fa fa-hourglass-start"></span></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="break_label">Break Label</label>
                                            <input type="text" name="break_label" class="form-control" id="break_label" value="Lunch Break">
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="form_phone"><?php echo $this->lang->line('room_no'); ?></label>
                                            <input type="text" name="rroom_no" class="form-control" id="froom_no">
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="form-group">
                                            <label for="form_phone" class="displayblock opacity d-sm-none">&nbsp;</label>
                                            <button type="button" class="btn btn-primary btn-send smallbtn28" id="quick_generate_btn"><?php echo $this->lang->line('apply'); ?></button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs" id="myTabs">
                                <?php
                                $count = 1;

                                foreach ($getDaysnameList as $days_key => $days_value) {
                                    $cls = "";
                                    if ($count == 1) {
                                    }
                                ?>
                                    <li <?php echo $cls; ?>><a href="#tab_<?php echo $count; ?>" data-c="<?php echo set_value('class_id'); ?>" data-days="<?php echo $days_value; ?>" data-s="<?php echo set_value('section_id'); ?>" data-group="<?php echo set_value('subject_group_id'); ?>" data-day="<?php echo $days_key; ?>" data-toggle="tab" aria-expanded="true"><?php echo $days_value; ?></a></li>

                                <?php
                                    $count++;
                                }
                                ?>
                            </ul>
                            <div class="tab-content">
                                <?php
                                $count = 1;
                                foreach ($getDaysnameList as $days_key => $days_value) {
                                    $cls = "class='tab-pane'";
                                    if ($count == 1) {
                                    }
                                ?>
                                    <div <?php echo $cls; ?> id="tab_<?php echo $count; ?>">
                                    </div>

                                <?php
                                    $count++;
                                }
                                ?>

                            </div>
                        </div>
                </div>
            <?php
                    }
            ?>
    </section>
</div>

<!-- Copy Timetable Modal -->
<div class="modal fade" id="copyTimetableModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Copy Timetable</h4>
            </div>
            <div class="modal-body">
                <form id="copyTimetableForm">
                    <input type="hidden" name="source_day" id="copy_source_day" value="">
                    <div class="form-group">
                        <label>Copy To</label>
                        <select class="form-control" name="target_day" id="copy_target_day">
                            <option value="all">All Days (except Sunday)</option>
                            <?php foreach ($getDaysnameList as $day_key => $day_value) { 
                                if($day_key != 'Sunday') { ?>
                                <option value="<?php echo $day_key; ?>"><?php echo $day_value; ?></option>
                            <?php } } ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                <button type="button" class="btn btn-primary" id="btnCopyTimetable">Copy</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

    function openCopyModal(day) {
        $('#copy_source_day').val(day);
        $('#copyTimetableModal').modal('show');
    }

    $(document).on('click', '#btnCopyTimetable', function() {
        var class_id = $('#class_id').val();
        var section_id = $('#section_id').val();
        var subject_group_id = $('#subject_group_id').val();
        var source_day = $('#copy_source_day').val();
        var target_day = $('#copy_target_day').val();

        if (!class_id || !section_id || !subject_group_id) {
            errorMsg("Please select Class, Section, and Subject Group first.");
            return;
        }

        $.ajax({
            type: 'POST',
            url: base_url + "admin/timetable/copy_timetable",
            data: {
                class_id: class_id,
                section_id: section_id,
                subject_group_id: subject_group_id,
                source_day: source_day,
                target_day: target_day
            },
            dataType: 'json',
            success: function(res) {
                if (res.status == 1) {
                    successMsg(res.message);
                    $('#copyTimetableModal').modal('hide');
                    // Optionally refresh the current tab or let the user do it
                } else {
                    errorMsg(res.error);
                }
            }
        });
    });

    $(document).on('change', '#class_id', function(e) {
        document.getElementById("insertbtn").disabled = true;
    });
    
    $(document).on('change', '#section_id', function(e) {
        document.getElementById("insertbtn").disabled = true;
    });

    $(document).on('change', '#subject_group_id', function(e) {
        if($(this).val() != '') {
            document.getElementById("insertbtn").disabled = false;
        } else {
            document.getElementById("insertbtn").disabled = true;
        }
    });

    $(document).on('submit', '.create_time_table', function(e) {
        document.getElementById("insertbtn").disabled = true;
    });




    var tot_count = 0;
    var class_id = $('#class_id').val();
    var section_id = '<?php echo set_value('section_id') ?>';
    var subject_group_id = '<?php echo set_value('subject_group_id') ?>';
    $(document).ready(function() {

        $('#myTabs a:first').tab('show') // Select first tab
        getSectionByClass(class_id, section_id);
        getGroupByClassandSection(class_id, section_id, subject_group_id);

        $(document).on('change', '#class_id', function(e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {
                    'class_id': class_id
                },
                dataType: "json",
                success: function(data) {
                    $.each(data, function(i, obj) {
                        div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                    });

                    $('#section_id').append(div_data);
                }
            });
        });

        $(document).on('change', '#section_id', function(e) {
            $('#subject_group_id').html("");
            var section_id = $(this).val();
            var class_id = $('#class_id').val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "POST",
                url: base_url + "admin/subjectgroup/getGroupByClassandSection",
                data: {
                    'class_id': class_id,
                    'section_id': section_id
                },
                dataType: "json",
                success: function(data) {
                    $.each(data, function(i, obj) {
                        div_data += "<option value=" + obj.subject_group_id + ">" + obj.name + "</option>";
                    });

                    $('#subject_group_id').append(div_data);
                }
            });
        });
    });

    function getSectionByClass(class_id, section_id) {
        if (class_id != "" && section_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {
                    'class_id': class_id
                },
                dataType: "json",
                success: function(data) {
                    $.each(data, function(i, obj) {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        }
    }

    function getGroupByClassandSection(class_id, section_id, subject_group_id) {
        if (class_id != "" && section_id != "" && subject_group_id != "") {
            $('#subject_group_id').html("");

            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "POST",
                url: base_url + "admin/subjectgroup/getGroupByClassandSection",
                data: {
                    'class_id': class_id,
                    'section_id': section_id
                },
                dataType: "json",
                success: function(data) {
                    console.log(subject_group_id);
                    $.each(data, function(i, obj) {
                        var sel = "";
                        if (subject_group_id == obj.subject_group_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.subject_group_id + " " + sel + ">" + obj.name + "</option>";
                    });

                    $('#subject_group_id').append(div_data);
                }
            });
        }
    }

    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        var target = $(e.target).attr("href"); // activated tab
        var target_id = $(e.target).attr("id"); // activated tab
        var ajax_data = $(e.target).data(); // activated tab

        $("#active_tab_day").val(ajax_data.day);
        $(target).html("");
        getGroupdata(target, target_id, ajax_data);
    })





    function getGroupdata(target, target_id, ajax_data) {

        $.ajax({
            type: 'POST',
            url: base_url + "admin/timetable/getBydategroupclasssection",
            data: {
                'day': ajax_data.day,
                'class_id': ajax_data.c,
                'section_id': ajax_data.s,
                'subject_group_id': ajax_data.group
            },
            dataType: 'json',
            beforeSend: function() {
                $(target).addClass('show');
            },
            success: function(data) {
                $(target).html(data.html);

             
              
                $('.staff', target).select2({
                    dropdownAutoWidth: true,
                    width: '100%'
                });
                $('.subject', target).select2({
                    dropdownAutoWidth: true,
                    width: '100%'
                });
                tot_count = data.total_count + 1;
            },
            error: function(xhr) { // if error occured

            },
            complete: function() {
                $(target).removeClass('show');
            }
        });
    }


    $(document).ready(function() {
        var counter = 0;
        $(document).on("click", ".addrow", function() {
            var newRow = $("<tr>");
            var cols = "";
            cols += '<td class="relative"><input type="hidden" name="total_row[]" value="' + tot_count + '"><input type="hidden" name="prev_id_' + tot_count + '" value="0"><input type="hidden" class="period_type" name="period_type_' + tot_count + '" value="period"><select class="form-control subject" id="subject_id_' + tot_count + '" name="subject_' + tot_count + '">' + $("#subject_dropdown").text() + '</select></td>';

            cols += '<td>' +
            '<div class="input-group">' +
                '<input type="text" name="time_from_' + tot_count + '" class="form-control datetimepicker3 time_from" id="time_from_' + tot_count + '">' +
                '<span class="input-group-addon">' +
                    '<span class="fa fa-clock-o"></span>' +
                '</span>' +
            '</div>' +
        '</td>';

cols += '<td>' +
            '<div class="input-group">' +
                '<input type="text" name="time_to_' + tot_count + '" class="form-control datetimepicker3 time_to" id="time_to_' + tot_count + '">' +
                '<span class="input-group-addon">' +
                    '<span class="fa fa-clock-o"></span>' +
                '</span>' +
            '</div>' +
        '</td>';


            cols += '<td class="relative"><select class="form-control staff"  onchange="check_class_dublicate_recored(' + tot_count + ', this.value)"   id="staff_id_' + tot_count + '" name="staff_' + tot_count + '">' + $("#staff_dropdown").text() + '</select></span></td>';

            cols += '<td><input type="text" class="form-control room_no" name="room_no_' + tot_count + '" id="room_no_' + tot_count + '"/> </td>';
            cols += '<td class="text-right"><button type="button" class="ibtnDel btn btn-danger"><i class="fa fa-trash"></i></button></td>';
            newRow.append(cols);

            $("table.order-list").append(newRow);


            $('.staff', newRow).select2({
                dropdownAutoWidth: true,
                width: '100%'
            });

            $('.subject', newRow).select2({
                dropdownAutoWidth: true,
                width: '100%'
            });
            tot_count++;
        });

        $(document).on("click", ".addbreakrow", function() {
            var newRow = $("<tr>");
            var cols = "";
            cols += '<td class="relative"><input type="hidden" name="total_row[]" value="' + tot_count + '"><input type="hidden" name="prev_id_' + tot_count + '" value="0"><input type="hidden" class="period_type" name="period_type_' + tot_count + '" value="break"><input type="text" class="form-control break_label" name="break_label_' + tot_count + '" placeholder="Break Label"></td>';

            cols += '<td>' +
            '<div class="input-group">' +
                '<input type="text" name="time_from_' + tot_count + '" class="form-control datetimepicker3 time_from" id="time_from_' + tot_count + '">' +
                '<span class="input-group-addon">' +
                    '<span class="fa fa-clock-o"></span>' +
                '</span>' +
            '</div>' +
        '</td>';

        cols += '<td>' +
            '<div class="input-group">' +
                '<input type="text" name="time_to_' + tot_count + '" class="form-control datetimepicker3 time_to" id="time_to_' + tot_count + '">' +
                '<span class="input-group-addon">' +
                    '<span class="fa fa-clock-o"></span>' +
                '</span>' +
            '</div>' +
        '</td>';

            cols += '<td class="relative"><span class="text-muted">N/A</span></td>';
            cols += '<td><span class="text-muted">N/A</span></td>';
            cols += '<td class="text-right"><button type="button" class="ibtnDel btn btn-danger"><i class="fa fa-trash"></i></button></td>';
            newRow.append(cols);

            $("table.order-list").append(newRow);

            tot_count++;
        });

        $(document).on("click", ".ibtnDel", function(event) {
            if ($(this).closest('tr').prev('input').val()) {
                if (confirm('<?php echo $this->lang->line("are_you_sure_you_want_to_delete"); ?>')) {
                    $(this).closest("tr").remove();
                    counter -= 1
                }
                return false;

            } else {
                $(this).closest("tr").remove();
                counter -= 1
            }
        });

        $(document).on('click', '.submit_subject_group', function() {
            var form_id = $(this).closest("form").attr('id');
            var target = $('.nav-tabs .active a').attr("href"); // activated tab
            var target_id = $('.nav-tabs .active a').attr("id"); // activated tab
            var ajax_data = $('.nav-tabs .active a').data(); // activated tab

        });
    });
</script>

<script type="text/template" id="staff_dropdown">
    <option value=""><?php echo $this->lang->line('select') ?></option>
                <?php
                foreach ($staff as $staff_key => $staff_value) {
                ?>
                    <option value="<?php echo $staff_value['id']; ?>"><?php echo $staff_value['name'] . " " . $staff_value['surname'] . " (" . $staff_value['employee_id'] . ")"; ?></option>
                    <?php
                }
                    ?>
            </script>

<script type="text/template" id="subject_dropdown">
    <option value=""><?php echo $this->lang->line('select') ?></option>
                <?php
                foreach ($subject as $subject_key => $subject_value) {
                    if ($subject_value->code !== '') {
                        $sub_name = $subject_value->name . " (" . $subject_value->code . ")";
                    } else {
                        $sub_name = $subject_value->name;
                    }
                ?>
                    <option value="<?php echo $subject_value->id; ?>" ><?php echo $sub_name; ?></option>
                    <?php
                }
                    ?>
            </script>

<script type="text/javascript">
    $(document).ready(function() {
        $("#universal_from").validate({

            rules: {
                start_time: {
                    required: true

                },
                duration: {
                    required: true

                },
                interval: {
                    required: true

                }
            },
            // Specify validation error messages
            messages: {

                start_time: "<?php echo $this->lang->line('required'); ?>",
                duration: "<?php echo $this->lang->line('required'); ?>",
                interval: "<?php echo $this->lang->line('required'); ?>",
            },
            errorClass: 'text-danger',
            validClass: 'valid',

            errorPlacement: function(error, element) {
                $("#errorText").empty();

                if (error[0].htmlFor == 'start_time') {
                    error.appendTo($(element).parents('div.form-group'));
                }
                if (error[0].htmlFor == 'duration') {
                    error.appendTo($(element).parents('div.form-group'));
                }
                if (error[0].htmlFor == 'interval') {
                    error.appendTo($(element).parents('div.form-group'));
                }

            },
            // Make sure the form is submitted to the destination defined
            // in the "action" attribute of the form when valid
            submitHandler: function(form) {
                // Now handled by quick_generate_btn click
            }
        });

        $(document).on('click', '#quick_generate_btn', function() {
            var class_id = $('#class_id').val();
            var section_id = $('#section_id').val();
            var subject_group_id = $('#subject_group_id').val();

            if (!class_id || !section_id || !subject_group_id) {
                errorMsg("Please select Class, Section, and Subject Group first.");
                return;
            }

            var start_time = $('#start_time').val();
            var duration = $('#duration').val();
            var interval = $('#interval').val();
            var total_periods = $('#total_periods').val();
            
            if (!start_time || !duration || !total_periods) {
                errorMsg("Please fill in Total Periods, Start Time, and Duration.");
                return;
            }

            if (confirm("This will overwrite existing timetable empty rows for all days except Sunday. Do you want to continue?")) {
                $.ajax({
                    type: 'POST',
                    url: base_url + "admin/timetable/quick_generate",
                    data: {
                        class_id: class_id,
                        section_id: section_id,
                        subject_group_id: subject_group_id,
                        start_time: start_time,
                        duration: duration,
                        interval: interval,
                        total_periods: total_periods,
                        periods_before_break: $('#periods_before_break').val(),
                        break_duration: $('#break_duration').val(),
                        break_label: $('#break_label').val(),
                        room_no: $('#froom_no').val()
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status == 1) {
                            successMsg(res.message);
                            // Refresh the current active tab
                            var target = $('.nav-tabs .active a').attr("href");
                            var target_id = $('.nav-tabs .active a').attr("id");
                            var ajax_data = $('.nav-tabs .active a').data();
                            $(target).html("");
                            getGroupdata(target, target_id, ajax_data);
                        } else {
                            errorMsg(res.error);
                        }
                    }
                });
            }
        });
    });
</script>


<script>
$(document).on("focus", ".datetimepicker3", function () {
    if (!$(this).data("DateTimePicker")) {

        $(this).datetimepicker({
            format: 'LT',
       
            widgetPositioning: {
                // horizontal: 'auto',
                // vertical: 'bottom'
            }
        });

    }
});


    

// $(document).on('dp.show', '.datetimepicker3', function () {
//     $(".table-responsive").removeClass("table-responsive").addClass("temp");
// });

// $(document).on('dp.hide', '.datetimepicker3', function () {
//     $(".temp").addClass("table-responsive").removeClass("temp");
// });
</script>


<script>




    $(document).on('change', '.staff', function() {
        var row = $(this).closest('tr'); // Get the closest table row
        var time_from = row.find('.time_from').val(); // Find input with class 'time_from' in the same row
        var time_to = row.find('.time_to').val(); // Find input with class 'time_to' in the same row
        var staff_id = $(this).val(); // Current select value
        var day = $("#active_tab_day").val();

        console.log("Time From:", time_from);
        console.log("Time To:", time_to);
        console.log("Staff ID:", staff_id);
        console.log("day :", day);

        $.ajax({
            type: "POST",
            url: base_url + "admin/timetable/check_class_dublicate_recored",
            data: {
                time_from: time_from,
                time_to: time_to,
                staff_id: staff_id,
                day: day
            },
            dataType: "json",
            success: function(res) {
                if (res.status == 1) {
                    errorMsg(res.error);
                }
            }
        });

        // Now you can pass these values to a function or use in AJAX
    });
</script>