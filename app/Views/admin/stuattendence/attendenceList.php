<style type="text/css">
    .att-seg { display: inline-flex; flex-wrap: nowrap; gap: 4px; vertical-align: middle; }
    .att-seg input[type="radio"] { position: absolute; opacity: 0; width: 0; height: 0; pointer-events: none; }
    .att-pill {
        margin: 0; padding: 5px 11px; font-size: 12px; font-weight: 600; line-height: 1.2;
        border: 1px solid #d5dbe3; border-radius: 999px; background: #fff; color: #5b6573;
        cursor: pointer; user-select: none; white-space: nowrap; transition: all .12s ease;
    }
    .att-pill:hover { border-color: #9aa5b4; background: #f5f7fa; }
    .att-seg input:focus-visible + .att-pill { outline: 2px solid #3b82f6; outline-offset: 1px; }
    .att-seg input:disabled + .att-pill { opacity: .5; cursor: not-allowed; }
    .att-seg input:checked + .att-pill.t-present  { background: #16a34a; border-color: #16a34a; color: #fff; }
    .att-seg input:checked + .att-pill.t-late     { background: #f59e0b; border-color: #f59e0b; color: #fff; }
    .att-seg input:checked + .att-pill.t-absent   { background: #dc2626; border-color: #dc2626; color: #fff; }
    .att-seg input:checked + .att-pill.t-holiday  { background: #7c3aed; border-color: #7c3aed; color: #fff; }
    .att-seg input:checked + .att-pill.t-half_day { background: #0891b2; border-color: #0891b2; color: #fff; }
    .att-bulk { display: flex; align-items: center; flex-wrap: wrap; gap: 10px; }
    .att-bulk .att-bulk-label { font-weight: 600; color: #334155; margin: 0; }
    .att-bulk .att-pill { padding: 6px 14px; }
    .att-summary { display: flex; flex-wrap: wrap; gap: 8px; margin: 0 0 14px; }
    .att-chip { padding: 4px 12px; border-radius: 999px; font-size: 12px; font-weight: 600; background: #eef2f7; color: #334155; }
    .att-chip b { margin-left: 4px; }
    .att-chip.c-present { background: #dcfce7; color: #166534; }
    .att-chip.c-late { background: #fef3c7; color: #92400e; }
    .att-chip.c-absent { background: #fee2e2; color: #991b1b; }
    .att-chip.c-holiday { background: #ede9fe; color: #5b21b6; }
    .att-chip.c-half_day { background: #cffafe; color: #155e75; }
    .att-table > thead > tr > th { white-space: nowrap; font-size: 12px; text-transform: uppercase; letter-spacing: .03em; color: #64748b; }
    .att-table > tbody > tr > td { vertical-align: middle !important; }
    .att-table td .form-control { min-width: 90px; }
    .att-table td.att-name { font-weight: 600; min-width: 170px; }
    .att-table td.att-cell { min-width: 380px; }
    .att-table tr.row-present  { box-shadow: inset 3px 0 0 #16a34a; }
    .att-table tr.row-late     { box-shadow: inset 3px 0 0 #f59e0b; }
    .att-table tr.row-absent   { box-shadow: inset 3px 0 0 #dc2626; background: #fff7f7; }
    .att-table tr.row-holiday  { box-shadow: inset 3px 0 0 #7c3aed; }
    .att-table tr.row-half_day { box-shadow: inset 3px 0 0 #0891b2; }
    .att-save-bar { position: sticky; bottom: 0; z-index: 5; background: #fff; border-top: 1px solid #e5e9ef; padding: 10px 0; margin-top: 8px; text-align: right; }
    @media (max-width: 767px) { .att-table td.att-cell { min-width: 330px; } }
    /* Compact status badge: only the selected status shows; click opens a menu */
    .att-table .att-seg { gap: 0; cursor: pointer; }
    .att-table .att-seg label.att-pill { display: none; }
    .att-table .att-seg input:checked + label.att-pill { display: inline-block; padding-right: 26px; position: relative; }
    .att-table .att-seg input:checked + label.att-pill::after { content: ""; position: absolute; right: 10px; top: 50%; margin-top: -2px; border-left: 4px solid transparent; border-right: 4px solid transparent; border-top: 5px solid currentColor; }
    .att-table .att-seg.att-none::before { content: "Select"; padding: 5px 12px; font-size: 12px; font-weight: 600; border: 1px dashed #9aa5b4; border-radius: 999px; color: #64748b; }
    .att-table td.att-cell { min-width: 140px !important; }
    .att-menu { position: fixed; z-index: 99999; background: #fff; border: 1px solid #dfe5ec; border-radius: 10px; box-shadow: 0 8px 24px rgba(15,23,42,.18); padding: 6px; display: flex; flex-direction: column; gap: 4px; min-width: 150px; }
    .att-menu .att-pill { display: block; text-align: left; }
    .att-menu .att-pill.is-current { box-shadow: 0 0 0 2px rgba(15,23,42,.25); }
    .att-table tbody tr > td:first-child { border-left: 4px solid transparent; }
    .att-table tr.row-present  > td:first-child { border-left-color: #16a34a; }
    .att-table tr.row-late     > td:first-child { border-left-color: #f59e0b; }
    .att-table tr.row-absent   > td:first-child { border-left-color: #dc2626; }
    .att-table tr.row-holiday  > td:first-child { border-left-color: #7c3aed; }
    .att-table tr.row-half_day > td:first-child { border-left-color: #0891b2; }
    .att-note { min-width: 240px; resize: vertical; overflow: hidden; line-height: 1.35; white-space: pre-wrap; }
    .att-table th.noteinput { min-width: 240px; }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-calendar-check-o"></i> <?php echo $this->lang->line('attendance'); ?> <small><?php echo $this->lang->line('by_date1'); ?></small>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                        <?php if ($this->rbac->hasPrivilege('student_attendance', 'can_add')) { ?>
                        <div class="box-tools pull-right">
                            <a href="<?php echo site_url('admin/stuattendence/bulk'); ?>" class="btn btn-sm btn-primary"><i class="fa fa-users"></i> Mark Entire School Attendance (Bulk)</a>
                        </div>
                        <?php } ?>
                    </div>
                    <form id='form1' action="<?php echo site_url('admin/stuattendence/index') ?>" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <?php if ($this->session->flashdata('msg')) {
                                ?>
                                    <?php echo $this->session->flashdata('msg');
                                    $this->session->unset_userdata('msg'); ?>
                                <?php } ?>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>

                                        <select autofocus="" id="class_id" name="class_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($classlist as $class) {
                                            ?>
                                                <option value="<?php echo $class['id'] ?>" <?php
                                                                                            if ($class_id == $class['id']) {
                                                                                                echo "selected =selected";
                                                                                            }
                                                                                            ?>>
                                                    <?php echo $class['class'] ?>
                                                </option>
                                            <?php
                                                $count++;
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                        <select id="section_id" name="section_id" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1">
                                            <?php echo $this->lang->line('attendance_date'); ?>
                                        </label><small class="req"> *</small>
                                        <input id="date" name="date" placeholder="" type="text" class="form-control date" value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat())); ?>" readonly="readonly" />
                                        <div id="sunday_message" class="text-danger" style="display:none; font-weight:bold;">Today is Sunday, so mark it a holiday</div>
                                        <span class="text-danger"><?php echo form_error('date'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button type="submit" name="search" value="search" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>



                <?php
                if (isset($resultlist)) {
                ?>
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-users"></i> <?php echo $this->lang->line('student_list'); ?></h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <div class="box-body">
                                <?php
                                if (!empty($resultlist)) {
                                    $can_edit = 1;
                                    $checked  = "";
                                
                                    ?>
                                    <form action="<?php echo site_url('admin/stuattendence/index') ?>" method="post" class="form_attendence">
                                        <?php echo $this->customlib->getCSRF(); ?>
                                        <input type="hidden" name="is_first_time_attendance" value="<?php echo $is_first_time_attendance;?>"/>

                                        <div class="mailbox-controls">
                                            <div class="row">
                                                <div class="col-md-8">                            
                                                <?php
                                                if ($this->rbac->hasPrivilege('student_attendance', 'can_add')) { ?>
                                                    <div class="att-bulk">
                                                        <label class="att-bulk-label"><?php echo $this->lang->line('set_attendance_for_all_students_as'); ?></label>
                                                        <div class="att-seg">
                                                        <?php
                                                        foreach ($attendencetypeslist as $key => $type) {
                                                            $att_type = str_replace(" ", "_", strtolower($type['type']));
                                                        ?>
                                                            <input type="radio" data-record_id="<?php echo $type['id'] ?>" name="attendencetype" class="default_radio" value="radio_<?php echo $type['id'] ?>" id="attendencetype<?php echo $type['id'] ?>">
                                                            <label class="att-pill t-<?php echo $att_type; ?>" for="attendencetype<?php echo $type['id'] ?>"><?php echo $this->lang->line($att_type); ?></label>
                                                        <?php } ?>
                                                        </div>
                                                    </div>
                                                <?php
                                                }
                                                ?>
                                           
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="pull-right">
                                                        <?php

                                                        if ($can_edit == 1) {
                                                            if ($this->rbac->hasPrivilege('student_attendance', 'can_add')) {
                                                        ?>
                                                                <button type="submit" name="search" value="saveattendence" id="saveattendence" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-save"></i> <?php echo $this->lang->line('save_attendance'); ?> </button>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                                        <input type="hidden" name="section_id" value="<?php echo $section_id; ?>">
                                        <input type="hidden" name="date" value="<?php echo $date; ?>">
                                        <div class="att-summary" id="att_summary">
                                            <span class="att-chip">Total <b id="sum_total">0</b></span>
                                            <?php foreach ($attendencetypeslist as $type) { $k = str_replace(" ", "_", strtolower($type['type'])); ?>
                                                <span class="att-chip c-<?php echo $k; ?>"><?php echo $this->lang->line($k); ?> <b data-sum="<?php echo $type['id']; ?>">0</b></span>
                                            <?php } ?>
                                        </div>
                                        <div class="table-responsive ptt10">
                                            <table class="table table-hover att-table example">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th class="dt-body-left dt-head-left"><?php echo $this->lang->line('admission_no'); ?></th>   
                                                        <?php if($sch_setting->roll_no){?>                                                  
                                                        <th class="dt-body-left dt-head-left"><?php echo $this->lang->line('roll_number'); ?></th>
                                                        <?php } ?>
                                                        <th><?php echo $this->lang->line('name'); ?></th>                                                      
                                                        <th ><?php echo $this->lang->line('attendance'); ?></th>
                                                        <?php
                                                        if ($sch_setting->biometric) {
                                                        ?>
                                                            <th ><?php echo $this->lang->line('date'); ?></th>
                                                        <?php
                                                        }
                                                        ?>
                                                        <th ><?php echo $this->lang->line('source'); ?></th>
                                                        <th width="13%"><?php echo $this->lang->line('entry_time'); ?></th>
                                                        <th width="13%"><?php echo $this->lang->line('exit_time'); ?></th>
                                                        <th width="13%"class="noteinput"><?php echo $this->lang->line('note'); ?></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    $row_count = 1;
                                                    foreach ($resultlist as $key => $value) {
                                                    ?>
                                                        <tr>
                                                            <td>
                                                                <input type="hidden" name="student_session[]" value="<?php echo $value['student_session_id']; ?>">
                                                                <input type="hidden" value="<?php echo $value['attendence_id']; ?>" name="attendendence_id<?php echo $value['student_session_id']; ?>">
                                                                <?php echo $row_count; ?>
                                                            </td>
                                                            <td class="dt-body-left dt-head-left">
                                                                <?php echo $value['admission_no']; ?>
                                                            </td>
                                                            
                                                            <?php if($sch_setting->roll_no){?>  
                                                            <td class="dt-body-left dt-head-left">
                                                                <?php echo $value['roll_no']; ?>
                                                            </td>
                                                            <?php } ?>

                                                            <td class="att-name">
                                                                <?php
                                                                echo $this->customlib->getFullName($value['firstname'], $value['middlename'], $value['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?>
                                                                <?php if (isset($value['bus_status']) && !empty($value['bus_status'])) { 
                                                                    if ($value['bus_status'] == 'Present' || $value['bus_status'] == 'Switched Bus') {
                                                                        echo '<span class="label label-success pull-right" style="font-size:10px;"><i class="fa fa-bus"></i> Bus: Present</span>';
                                                                    } elseif ($value['bus_status'] == 'Absent') {
                                                                        echo '<span class="label label-danger pull-right" style="font-size:10px;"><i class="fa fa-bus"></i> Bus: Absent</span>';
                                                                    }
                                                                } ?>
                                                            </td>
                                                            <td class="att-cell">
                                                                <div class="att-seg">
                                                                <?php
                                                                $c     = 1;
                                                                $count = 0;
                                                                foreach ($attendencetypeslist as $key => $type) {
                                                                    $att_type = str_replace(" ", "_", strtolower($type['type']));
                                                                    $ssid     = $value['student_session_id'];
                                                                    $rid      = "attendencetype" . $ssid . "-" . $count;
                                                                    $onclick  = 'onclick="disable_enable(this.value,' . $ssid . ')"';
                                                                    if ($value['date'] != "xxx") {
                                                                        $is_checked = ($value['attendence_type_id'] == $type['id']);
                                                                    } elseif ($sch_setting->biometric) {
                                                                        $is_checked = ($att_type == "absent");
                                                                        $onclick    = '';
                                                                    } else {
                                                                        $is_checked = ($c == 1);
                                                                    }
                                                                ?>
                                                                    <input <?php echo $onclick; ?> <?php echo $is_checked ? "checked" : ""; ?> type="radio" id="<?php echo $rid; ?>" value="<?php echo $type['id'] ?>" class="radio_<?php echo $type['id'] ?>" data-type="<?php echo $att_type; ?>" name="attendencetype<?php echo $ssid; ?>">
                                                                    <label class="att-pill t-<?php echo $att_type; ?>" for="<?php echo $rid; ?>"><?php echo $this->lang->line($att_type); ?></label>
                                                                <?php
                                                                    $c++;
                                                                    $count++;
                                                                }
                                                                ?>
                                                                </div>
                                                            </td>
                                                            <?php
                                                            if ($sch_setting->biometric) {
                                                            ?>
                                                                <td>
                                                                <?php
                                                                if ($value['biometric_attendence'] || $value['qrcode_attendance']) {
                                                                    echo $this->customlib->dateyyyymmddToDateTimeformat($value['attendence_dt']);
                                                                }
                                                                ?>
                                                                </td>
                                                            <?php
                                                            }
                                                            ?>
                                                            <td>
                                                            <?php
                                                            if (IsNullOrEmptyString($value['biometric_attendence']) && IsNullOrEmptyString($value['qrcode_attendance'])) {
                                                                echo $this->lang->line('n_a');
                                                            } elseif (($value['biometric_attendence'] == 0) && ($value['qrcode_attendance']  == 0)) {
                                                                echo $this->lang->line('manual');
                                                            } elseif ($value['biometric_attendence']) {
                                                                echo $this->lang->line('biometric');
                                                            } elseif ($value['qrcode_attendance']) {
                                                                echo $this->lang->line('qrcode')." / ".$this->lang->line('barcode');
                                                            }
                                                            ?>
                                                        </td>

                                                        <?php
                                                        if($value['attendence_type_id']==4 || $value['attendence_type_id']==5){
                                                            $disable_input_attr="disabled";
                                                        }else{
                                                            $disable_input_attr="";
                                                        }  ?>

                                                        <td class="relative"><input type="text" value="<?php echo $this->customlib->timeFormat($value["in_time"]); ?>" name="in_time_<?php echo $value["student_session_id"] ?>" id="in_time_<?php echo $value["student_session_id"] ?>" class="form-control in_time time" <?php echo $disable_input_attr;?>></td>
                                                        <td class="relative"><input type="text" value="<?php echo $this->customlib->timeFormat($value["out_time"]); ?>" name="out_time_<?php echo $value["student_session_id"] ?>"  id="out_time_<?php echo $value["student_session_id"] ?>" class="form-control out_time time" <?php echo $disable_input_attr;?>></td>
                                                            <?php if ($date == 'xxx') { ?>
                                                                <td><textarea rows="1" class="noteinput form-control att-note" name="remark<?php echo $value["student_session_id"] ?>" id="remark<?php echo $value["student_session_id"] ?>"></textarea></td>
                                                            <?php } else { ?>
                                                                <td><textarea rows="1" class="noteinput form-control att-note" name="remark<?php echo $value["student_session_id"] ?>" id="remark<?php echo $value["student_session_id"] ?>"><?php echo htmlspecialchars((string) $value["remark"]); ?></textarea></td>
                                                            <?php } ?>
                                                        </tr>
                                                    <?php
                                                        $row_count++;
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </form>
                                <?php
                                } else {
                                ?>
                                    <div class="alert alert-info"><?php echo $this->lang->line('admited_alert'); ?></div>
                                <?php
                                }
                                ?>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $.extend($.fn.dataTable.defaults, {
            searching: false,
            ordering: true,
            paging: false,
            retrieve: true,
            destroy: true,
            info: false
        });
        var table = $('.example').DataTable();
        table.buttons('.export').remove();
        var section_id_post = '<?php echo $section_id; ?>';
        var class_id_post = '<?php echo $class_id; ?>';
        populateSection(section_id_post, class_id_post);

        function populateSection(section_id_post, class_id_post) {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {
                    'class_id': class_id_post,
                    'day_wise': 'yes'
                },
                dataType: "json",
                success: function(data) {
                    $.each(data, function(i, obj) {
                        var select = "";
                        if (section_id_post == obj.section_id) {
                            var select = "selected=selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + select + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        }

        $(document).on('change', '#class_id', function(e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            var url = "<?php
                        $userdata = $this->customlib->getUserData();
                        if (($userdata["role_id"] == 2)) {
                            echo "getClassTeacherSection";
                        } else {
                            echo "getByClass";
                        }
                        ?>";
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {
                    'class_id': class_id,
                    'day_wise': 'yes'
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

    });
</script>

<script type="text/javascript">
    $(function() {
        $('.button-checkbox').each(function() {
            var $widget = $(this),
                $button = $widget.find('button'),
                $checkbox = $widget.find('input:checkbox'),
                color = $button.data('color'),
                settings = {
                    on: {
                        icon: 'glyphicon glyphicon-check'
                    },
                    off: {
                        icon: 'glyphicon glyphicon-unchecked'
                    }
                };
            $button.on('click', function() {

                $checkbox.prop('checked', !$checkbox.is(':checked'));
                $checkbox.triggerHandler('change');
                updateDisplay();
            });
            $checkbox.on('change', function() {
                updateDisplay();
            });

            function updateDisplay() {
                var isChecked = $checkbox.is(':checked');
                $button.data('state', (isChecked) ? "on" : "off");
                $button.find('.state-icon')
                    .removeClass()
                    .addClass('state-icon ' + settings[$button.data('state')].icon);
                if (isChecked) {
                    $button
                        .removeClass('btn-success')
                        .addClass('btn-' + color + ' active');
                } else {
                    $button
                        .removeClass('btn-' + color + ' active')
                        .addClass('btn-primary');
                }
            }

            function init() {
                updateDisplay();
                if ($button.find('.state-icon').length == 0) {
                    $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i> ');
                }
            }
            init();
        });
    });

    $('form.form_attendence').on('submit', function(e) {
        $('input[type="submit"]').attr('disabled', true);
        $(this).submit(function() {
            return false;
        });
        return true;
    });
</script>



<script type="text/javascript">
    //****intime out time class***//
    var attendance_setting = <?php echo json_encode($student_class_section_setting ?? false) ?>;
        $(function() {
        $('.time').datetimepicker({
            format: 'LT'
        });
    });

          $(function()
        {
            $('.time').datetimepicker().on('dp.show',function()
            {
                $(this).closest('.table-responsive').removeClass('table-responsive').addClass('temp');
            }).on('dp.hide',function()
            {
                $(this).closest('.temp').addClass('table-responsive').removeClass('temp')
            });
        });

    function tConvert(time) {
        if (time.toString().match(/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/)) {
            const [timeWithoutPeriod, period] = time.split(" ");
            let [hours, minutes, seconds] = timeWithoutPeriod.split(":");
            let AM_PM = null;
            AM_PM = hours < 12 ? 'AM' : 'PM'; // Set AM/PM
            hours = hours % 12 || 12; // Adjust hours
            return `${hours}:${minutes} ${AM_PM}`;
        } else {
            return time;
        }
    }


    $(document).ready(function() {
       $('.default_radio').click(function() {
       let radio_default=($(this).val());
            var returnVal = confirm("<?php echo $this->lang->line('are_you_sure'); ?>");
            var attendance_type_id = ($(this).data('record_id'));
            
            if(returnVal){
                let return_time = getAttendanceTime(attendance_type_id);
                    $('.in_time').val("");
                    $('.out_time').val("");
                if (return_time) {
                    $('.in_time').val(return_time[0]);
                    $('.out_time').val(return_time[1]);
                }
               
                $("input[type=radio][class='"+radio_default+"']").prop("checked", returnVal);

              
                if(attendance_type_id==4 || attendance_type_id==5){
                    //absent or holiday
                    $('.in_time').attr("disabled",true);
                    $('.out_time').attr("disabled",true);
                }else{
                    $('.in_time').attr("disabled",false);
                    $('.out_time').attr("disabled",false);
                }

            }else{               
                return false;
            }
        });
    });

    let getAttendanceTime = (attendance_type_id) => {
        let nm = (attendance_setting);
        var returnValue = false;
        $.each(nm, function(key, value) {
            if (value.attendence_type_id == attendance_type_id) {
                returnValue = [tConvert(value.entry_time_from), tConvert(value.entry_time_to)];
                return false; // this breaks out of the each
            }
        });
        return returnValue;
    }


let disable_enable=(type,student_session_id)=>{
    if(type==4 || type==5){
        $("#in_time_"+student_session_id).val("");
        $("#out_time_"+student_session_id).val("");
        $("#in_time_"+student_session_id).attr("disabled",true);
        $("#out_time_"+student_session_id).attr("disabled",true);
    }else{
        $("#in_time_"+student_session_id).val("");
        $("#out_time_"+student_session_id).val("");
        $("#in_time_"+student_session_id).attr("disabled",false);
        $("#out_time_"+student_session_id).attr("disabled",false);
    }
}

// Convert a PHP date() format string (e.g. "d M Y") to the equivalent
// moment.js format (e.g. "DD MMM YYYY"). Uppercasing the PHP format is wrong:
// PHP "M" (short month name, "Aug") is not moment "M" (numeric month), which
// caused strict parsing to fail and a lenient fallback to report the wrong
// weekday (e.g. flagging Wednesday as Sunday).
function phpToMomentFormat(phpFormat) {
    var map = {
        d: 'DD', j: 'D', D: 'ddd', l: 'dddd', N: 'E', w: 'e',
        m: 'MM', n: 'M', M: 'MMM', F: 'MMMM',
        Y: 'YYYY', y: 'YY',
        H: 'HH', G: 'H', h: 'hh', g: 'h', i: 'mm', s: 'ss', A: 'A', a: 'a'
    };
    var out = '';
    for (var i = 0; i < phpFormat.length; i++) {
        var ch = phpFormat[i];
        out += map.hasOwnProperty(ch) ? map[ch] : ch;
    }
    return out;
}

function parseDateValAndCheckSunday(dateVal) {
    if (!dateVal) return false;

    var format = '<?php echo $this->customlib->getSchoolDateFormat(); ?>';

    if (typeof moment !== 'undefined') {
        var momentFormat = phpToMomentFormat(format);
        var m = moment(dateVal, momentFormat, true); // strict parse with correct tokens
        if (m.isValid()) {
            return m.day() === 0;
        }
        // Fallback: try a few common explicit formats (strict).
        var m2 = moment(dateVal, ["DD MMM YYYY", "DD-MM-YYYY", "YYYY-MM-DD", "DD.MM.YYYY", "DD/MM/YYYY", "MM/DD/YYYY"], true);
        if (m2.isValid()) {
            return m2.day() === 0;
        }
    }

    // Last-resort native fallback.
    var d = new Date(dateVal);
    if (!isNaN(d.getTime())) {
        return d.getDay() === 0;
    }
    return false;
}

function checkSundayAndShowMessage(dateInputId, messageContainerId) {
    var dateVal = $('#' + dateInputId).val();
    if (dateVal) {
        var isSunday = parseDateValAndCheckSunday(dateVal);
        
        if (isSunday) {
            $('#' + messageContainerId).show();
            $('.default_radio').each(function() {
                var label = $(this).next('label').text().toLowerCase();
                if (label.indexOf('holiday') !== -1) {
                    $(this).prop('checked', true);
                }
            });
        } else {
            $('#' + messageContainerId).hide();
        }
    }
}

$(document).on('click', '.att-table .att-seg', function(e) {
    e.preventDefault(); e.stopPropagation();
    $('.att-menu').remove();
    var $seg = $(this), $menu = $('<div class="att-menu"></div>');
    $seg.find('input[type=radio]').each(function() {
        var $in = $(this), $lb = $seg.find('label[for="' + this.id + '"]');
        if ($in.is(':disabled')) { return; }
        var $it = $lb.clone().removeAttr('for').toggleClass('is-current', $in.is(':checked'));
        $it.on('click', function(ev) { ev.stopPropagation(); $in.prop('checked', true).trigger('click').trigger('change'); $('.att-menu').remove(); attRefresh(); });
        $menu.append($it);
    });
    var r = this.getBoundingClientRect();
    $('body').append($menu);
    var top = r.bottom + 4;
    if (top + $menu.outerHeight() > window.innerHeight) { top = Math.max(4, r.top - $menu.outerHeight() - 4); }
    $menu.css({ top: top, left: r.left });
});
$(document).on('click', function() { $('.att-menu').remove(); });
$(window).on('scroll resize', function() { $('.att-menu').remove(); });
function attGrow(el) { el.style.height = 'auto'; el.style.height = el.scrollHeight + 'px'; }
$(document).on('input', '.att-note', function() { attGrow(this); });
$(function() { $('.att-note').each(function() { attGrow(this); }); });
function attRefresh() {
    var counts = {}, total = 0;
    $('.att-table tbody tr').each(function() {
        var $r = $(this).find('.att-cell input:checked');
        $(this).removeClass('row-present row-late row-absent row-holiday row-half_day');
        if ($r.length) {
            $(this).addClass('row-' + $r.data('type'));
            counts[$r.val()] = (counts[$r.val()] || 0) + 1;
        }
        total++;
    });
    $('.att-table .att-seg').each(function() { $(this).toggleClass('att-none', !$(this).find('input:checked').length); });
    $('#sum_total').text(total);
    $('[data-sum]').each(function() { $(this).text(counts[$(this).data('sum')] || 0); });
}
$(document).on('change click', '.att-cell input, .default_radio', function() { setTimeout(attRefresh, 0); });
$(document).ready(function() {
    attRefresh();
    // Check initially
    checkSundayAndShowMessage('date', 'sunday_message');

    // Bootstrap datepicker dp.change or change
    $('.date').on('change dp.change', function(e) {
        if ($(this).attr('id') == 'date') {
            checkSundayAndShowMessage('date', 'sunday_message');
        }
    });
});

</script>