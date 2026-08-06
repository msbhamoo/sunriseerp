<style type="text/css">
    .dashboard2-wrapper {
        background-color: #f8fafc;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }
    .cr-header-card {
        background: #ffffff;
        border-radius: 14px;
        padding: 20px 24px;
        margin-bottom: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        border: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .cr-header-title {
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .cr-header-title i {
        color: #4f46e5;
        font-size: 22px;
    }

    .cr-search-card {
        background: #ffffff;
        border-radius: 14px;
        padding: 20px 24px;
        margin-bottom: 24px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }

    .cr-metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 20px;
    }
    .cr-metric-box {
        background: #ffffff;
        border-radius: 14px;
        padding: 16px 20px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .cr-metric-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .cr-metric-icon.indigo { background: #e0e7ff; color: #4338ca; }
    .cr-metric-icon.emerald { background: #d1fae5; color: #047857; }
    .cr-metric-icon.purple { background: #f3e8ff; color: #7e22ce; }

    .cr-metric-label { font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .cr-metric-val { font-size: 22px; font-weight: 800; color: #0f172a; line-height: 1.2; }

    /* Single Row Horizontal Scroll Container */
    .cr-row-container {
        display: flex;
        flex-direction: row;
        gap: 16px;
        overflow-x: auto;
        padding-bottom: 16px;
        scroll-behavior: smooth;
        position: relative;
    }
    .cr-row-container::-webkit-scrollbar {
        height: 8px;
    }
    .cr-row-container::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }
    .cr-row-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .cr-day-col {
        flex: 0 0 280px;
        min-width: 280px;
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 18px rgba(0,0,0,0.03);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .cr-day-col:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.06);
    }
    
    /* Sticky First Column */
    .cr-row-container > .cr-day-col:first-child {
        position: sticky;
        left: 0;
        z-index: 10;
        box-shadow: 4px 0 15px rgba(0,0,0,0.08);
    }

    .period-badge {
        display: inline-block;
        background: var(--day-accent);
        color: #ffffff;
        font-size: 10px;
        font-weight: 800;
        padding: 2px 6px;
        border-radius: 4px;
        margin-right: 6px;
        text-transform: uppercase;
    }

    /* Pastel Theme Variations per Day */
    .pastel-monday { --day-bg: #eef2ff; --day-header-bg: #e0e7ff; --day-accent: #4f46e5; --badge-bg: #e0e7ff; --badge-text: #3730a3; }
    .pastel-tuesday { --day-bg: #ecfdf5; --day-header-bg: #d1fae5; --day-accent: #059669; --badge-bg: #d1fae5; --badge-text: #065f46; }
    .pastel-wednesday { --day-bg: #fdf4ff; --day-header-bg: #fae8ff; --day-accent: #c026d3; --badge-bg: #fae8ff; --badge-text: #86198f; }
    .pastel-thursday { --day-bg: #fff7ed; --day-header-bg: #ffedd5; --day-accent: #ea580c; --badge-bg: #ffedd5; --badge-text: #9a3412; }
    .pastel-friday { --day-bg: #f0f9ff; --day-header-bg: #e0f2fe; --day-accent: #0284c7; --badge-bg: #e0f2fe; --badge-text: #075985; }
    .pastel-saturday { --day-bg: #fef2f2; --day-header-bg: #fee2e2; --day-accent: #dc2626; --badge-bg: #fee2e2; --badge-text: #991b1b; }
    .pastel-sunday { --day-bg: #f8fafc; --day-header-bg: #f1f5f9; --day-accent: #64748b; --badge-bg: #f1f5f9; --badge-text: #334155; }

    .cr-day-header {
        background: var(--day-header-bg);
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 14px 18px;
        font-size: 15px;
        font-weight: 800;
        color: var(--day-accent);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .cr-day-header.today {
        box-shadow: inset 0 -3px 0 0 var(--day-accent);
    }
    .cr-day-body {
        padding: 14px;
        background: #ffffff;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .cr-slot-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-left: 5px solid var(--day-accent);
        border-radius: 12px;
        padding: 12px 14px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    .cr-slot-subject {
        font-size: 13px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 6px;
    }
    .cr-slot-teacher {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 700;
        color: #334155;
        margin-bottom: 8px;
    }
    .cr-slot-meta {
        font-size: 11px;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-weight: 600;
    }
    .cr-slot-meta i { color: var(--day-accent); margin-right: 4px; }
</style>

<div class="content-wrapper dashboard2-wrapper" style="min-height: 946px;">
    <section class="content">
        <!-- Header Card -->
        <div class="cr-header-card">
            <div class="cr-header-title">
                <i class="fa fa-calendar"></i> <?php echo $this->lang->line('class_timetable'); ?>
            </div>
            <div>
                <?php if($this->rbac->hasPrivilege('class_timetable', 'can_edit')) { ?>
                    <a href="<?php echo site_url('admin/timetable/create') ?>" class="btn btn-sm btn-primary" style="border-radius: 8px; font-weight: 600;"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add'); ?></a>
                <?php } ?>
            </div>
        </div>

        <!-- Search Selection Criteria Card -->
        <div class="cr-search-card">
            <form action="<?php echo site_url('admin/timetable/classreport') ?>" method="post" accept-charset="utf-8">
                <?php echo $this->customlib->getCSRF(); ?>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group mb0">
                            <label for="class_id" style="font-weight: 700; color: #475569;"><?php echo $this->lang->line('class'); ?> <small class="req">*</small></label>
                            <select autofocus="" id="class_id" name="class_id" class="form-control" style="border-radius: 8px;">
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php foreach ($classlist as $class) { ?>
                                    <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) echo "selected=selected"; ?>><?php echo $class['class'] ?></option>
                                <?php } ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group mb0">
                            <label for="section_id" style="font-weight: 700; color: #475569;"><?php echo $this->lang->line('section'); ?> <small class="req">*</small></label>
                            <select id="section_id" name="section_id" class="form-control" style="border-radius: 8px;">
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                            </select>
                            <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                        </div>
                    </div>
                    <div class="col-md-2" style="display: flex; align-items: flex-end;">
                        <button type="submit" class="btn btn-primary btn-block" name="search" style="border-radius: 8px; font-weight: 700; padding: 7px;"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                    </div>
                </div>
            </form>
        </div>

        <?php if (isset($timetable)) { 
            $total_periods = 0;
            $active_days = 0;
            $current_day = date('l');

            if (!empty($timetable)) {
                foreach ($timetable as $d_name => $p_list) {
                    if (!empty($p_list)) {
                        $active_days++;
                        foreach ($p_list as $p_item) {
                            if (!isset($p_item->period_type) || $p_item->period_type != 'break') {
                                $total_periods++;
                            }
                        }
                    }
                }
            }

            $pastel_classes = [
                'Monday'    => 'pastel-monday',
                'Tuesday'   => 'pastel-tuesday',
                'Wednesday' => 'pastel-wednesday',
                'Thursday'  => 'pastel-thursday',
                'Friday'    => 'pastel-friday',
                'Saturday'  => 'pastel-saturday',
                'Sunday'    => 'pastel-sunday'
            ];
        ?>
            <!-- Metrics Overview -->
            <div class="cr-metrics-grid">
                <div class="cr-metric-box">
                    <div class="cr-metric-icon indigo">
                        <i class="fa fa-book"></i>
                    </div>
                    <div>
                        <div class="cr-metric-label">Total Scheduled Periods</div>
                        <div class="cr-metric-val"><?php echo $total_periods; ?></div>
                    </div>
                </div>
                <div class="cr-metric-box">
                    <div class="cr-metric-icon emerald">
                        <div style="font-weight: 800; font-size: 15px; text-transform: uppercase;"><?php echo substr($current_day, 0, 3); ?></div>
                    </div>
                    <div>
                        <div class="cr-metric-label">Today's Schedule</div>
                        <div class="cr-metric-val"><?php echo isset($timetable[$current_day]) ? count($timetable[$current_day]) : 0; ?> <span style="font-size:12px; font-weight:600; color:#64748b;">periods</span></div>
                    </div>
                </div>
                <div class="cr-metric-box">
                    <div class="cr-metric-icon purple">
                        <i class="fa fa-calendar-check-o"></i>
                    </div>
                    <div>
                        <div class="cr-metric-label">Active Days</div>
                        <div class="cr-metric-val"><?php echo $active_days; ?> <span style="font-size:12px; font-weight:600; color:#64748b;">days/week</span></div>
                    </div>
                </div>
            </div>

            <!-- Header Print Action -->
            <?php if (!empty($timetable)) { ?>
                <div class="row" style="margin-bottom: 12px;">
                    <div class="col-md-12">
                        <button type="button" title="<?php echo $this->lang->line('print'); ?>" class="btn btn-primary btn-sm pull-right print_timetable" data-class_id="<?php echo set_value('class_id');?>" data-section_id="<?php echo set_value('section_id');?>" id="load" data-loading-text="<i class='fa fa-spinner fa-spin'></i> <?php echo $this->lang->line('please_wait'); ?>" style="border-radius: 8px;">
                            <i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?>
                        </button>
                    </div>
                </div>
            <?php } ?>

            <!-- Single Row Horizontal Scroll Timetable Grid -->
            <?php if (!empty($timetable)) { 
                // Collect max period count / unique slots to render period timeline column
                $max_slots = 0;
                $sample_slots = [];
                foreach ($timetable as $d_k => $p_v) {
                    if (!empty($p_v) && count($p_v) > $max_slots) {
                        $max_slots = count($p_v);
                        $sample_slots = $p_v;
                    }
                }
            ?>
                <div class="cr-row-container">
                    <!-- Sticky Period Timeline Column -->
                    <div class="cr-day-col cr-period-col" style="flex: 0 0 180px; min-width: 180px; position: sticky; left: 0; z-index: 20; background: #ffffff; box-shadow: 4px 0 15px rgba(0,0,0,0.08); border-right: 2px solid #e2e8f0;">
                        <div class="cr-day-header" style="background: #0f172a; color: #ffffff; font-weight: 800;">
                            <span><i class="fa fa-list-ol"></i> Periods</span>
                        </div>
                        <div class="cr-day-body" style="gap: 12px;">
                            <?php if (!empty($sample_slots)) {
                                $p_counter = 1;
                                foreach ($sample_slots as $s_item) {
                                    if (isset($s_item->period_type) && $s_item->period_type == 'break') { ?>
                                        <div style="background: #f1f5f9; border: 1px dashed #94a3b8; border-radius: 10px; padding: 10px; text-align: center; font-size: 12px; color: #475569; font-weight: 700; height: 42px; display: flex; align-items: center; justify-content: center; gap: 4px;">
                                            <i class="fa fa-cutlery"></i> <strong>Break</strong>
                                        </div>
                                    <?php } else { ?>
                                        <div class="cr-slot-card" style="background: #f8fafc; border-left: 5px solid #4f46e5; height: 82px; display: flex; flex-direction: column; justify-content: center;">
                                            <div style="font-size: 14px; font-weight: 800; color: #1e293b;">Period <?php echo $p_counter++; ?></div>
                                            <div style="font-size: 11px; font-weight: 600; color: #64748b;"><i class="fa fa-clock-o"></i> <?php echo $s_item->time_from . " - " . $s_item->time_to; ?></div>
                                        </div>
                                    <?php }
                                }
                            } ?>
                        </div>
                    </div>

                    <?php foreach ($timetable as $tm_key => $tm_value) { 
                        $day_title = ucfirst($tm_key);
                        $is_today = (strtolower($tm_key) == strtolower($current_day));
                        $p_class = isset($pastel_classes[$day_title]) ? $pastel_classes[$day_title] : 'pastel-monday';
                    ?>
                        <div class="cr-day-col <?php echo $p_class; ?> <?php echo $is_today ? 'today-col' : ''; ?>">
                            <div class="cr-day-header <?php echo $is_today ? 'today' : ''; ?>">
                                <span><i class="fa fa-clock-o"></i> <?php echo $day_title; ?></span>
                                <?php if ($is_today) { ?>
                                    <span class="label label-warning" style="font-size: 10px; border-radius: 4px; padding: 3px 6px;">Today</span>
                                <?php } ?>
                            </div>
                            <div class="cr-day-body">
                                <?php if (!$timetable[$tm_key]) { ?>
                                    <div class="text-center" style="padding: 32px 12px; color: #94a3b8; font-size: 12px;">
                                        <i class="fa fa-coffee" style="font-size: 28px; color: #cbd5e1; margin-bottom: 8px; display: block;"></i>
                                        <?php echo $this->lang->line('not_scheduled'); ?>
                                    </div>
                                <?php } else {
                                    foreach ($timetable[$tm_key] as $tm_k => $tm_kue) {
                                        if (isset($tm_kue->period_type) && $tm_kue->period_type == 'break') { ?>
                                            <div style="background: var(--day-bg); border: 1px dashed var(--day-accent); border-radius: 10px; padding: 10px; text-align: center; font-size: 12px; color: var(--day-accent); font-weight: 600; height: 42px; display: flex; align-items: center; justify-content: center;">
                                                <i class="fa fa-cutlery"></i> &nbsp;<strong><?php echo $tm_kue->break_label; ?></strong>
                                            </div>
                                        <?php } else { ?>
                                            <div class="cr-slot-card" style="height: 82px; display: flex; flex-direction: column; justify-content: space-between;">
                                                <div class="cr-slot-subject" style="margin-bottom: 0;">
                                                    <?php echo $tm_kue->subject_name != '' ? $tm_kue->subject_name : 'Not Scheduled'; ?><?php echo !empty($tm_kue->code) ? " (" . $tm_kue->code . ")" : ""; ?>
                                                </div>
                                                <div class="cr-slot-teacher" style="margin-bottom: 0;">
                                                    <i class="fa fa-user-circle" style="color: var(--day-accent);"></i>
                                                    <span><?php echo $tm_kue->name != '' ? $tm_kue->name . " " . $tm_kue->surname : 'Not Scheduled'; ?></span>
                                                </div>
                                                <div class="cr-slot-meta">
                                                    <span><i class="fa fa-clock-o"></i> <?php echo $tm_kue->time_from . " - " . $tm_kue->time_to; ?></span>
                                                    <?php if (!empty($tm_kue->room_no)) { ?>
                                                        <span><i class="fa fa-building"></i> Rm: <?php echo $tm_kue->room_no; ?></span>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                        <?php }
                                    }
                                } ?>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } else { ?>
                <div class="alert alert-info" style="border-radius: 10px;">
                    <?php echo $this->lang->line('no_record_found'); ?>
                </div>
            <?php } ?>
        <?php } ?>
    </section>
</div>


<script type="text/javascript">
    $(document).on('focus', '.time', function () {
        var $this = $(this);
        $this.datetimepicker({
            format: 'LT'
        });
    });
    var tot_count = 0;
    var class_id = $('#class_id').val();
    var section_id = '<?php echo set_value('section_id') ?>';
    var subject_group_id = '<?php echo set_value('subject_group_id') ?>';
    $(document).ready(function () {
        // Auto-scroll to today's timetable column while keeping sticky Periods column visible
        var todayCol = $('.cr-row-container .today-col');
        if (todayCol.length > 0) {
            var container = $('.cr-row-container');
            var periodColWidth = $('.cr-period-col').outerWidth() || 180;
            var scrollPos = todayCol.position().left - periodColWidth;
            if (scrollPos > 0) {
                container.animate({ scrollLeft: scrollPos }, 400);
            }
        }

        $('#myTabs a:first').tab('show') // Select first tab
        getSectionByClass(class_id, section_id);
        getGroupByClassandSection(class_id, section_id, subject_group_id);

        $(document).on('change', '#class_id', function (e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                    });

                    $('#section_id').append(div_data);
                }
            });
        });

        $(document).on('change', '#section_id', function (e) {
            $('#subject_group_id').html("");
            var section_id = $(this).val();
            var class_id = $('#class_id').val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "POST",
                url: base_url + "admin/subjectgroup/getGroupByClassandSection",
                data: {'class_id': class_id, 'section_id': section_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        div_data += "<option value=" + obj.subject_group_id + ">" + obj.name + "</option>";
                    });

                    $('#subject_group_id').append(div_data);
                }
            });
        });
    });



    function getSectionByClass(class_id, section_id) {
       
        if (class_id != ""  ) {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
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
                data: {'class_id': class_id, 'section_id': section_id},
                dataType: "json",
                success: function (data) {
                    console.log(subject_group_id);
                    $.each(data, function (i, obj)
                    {
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

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

        var target = $(e.target).attr("href"); // activated tab
        var target_id = $(e.target).attr("id"); // activated tab
        var ajax_data = $(e.target).data(); // activated tab
        $(target).html("");
        getGroupdata(target, target_id, ajax_data);
    })

    function getGroupdata(target, target_id, ajax_data) {

        $.ajax({
            type: 'POST',
            url: base_url + "admin/timetable/getBydategroupclasssection",
            data: {'day': ajax_data.day, 'class_id': ajax_data.c, 'section_id': ajax_data.s, 'subject_group_id': ajax_data.group},
            dataType: 'json',
            beforeSend: function () {
                $(target).addClass('show');
            },
            success: function (data) {
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
            error: function (xhr) { // if error occured

            },
            complete: function () {
                $(target).removeClass('show');
            }
        });
    }


    $(document).ready(function () {
        var counter = 0;

        $(document).on("click", ".addrow", function () {

            var newRow = $("<tr>");
            var cols = "";
            cols += '<td><input type="hidden" name="total_row[]" value="' + tot_count + '"><input type="hidden" name="prev_id_' + tot_count + '" value="0"><select class="form-control subject" id="subject_id_' + tot_count + '" name="subject_' + tot_count + '">' + $("#subject_dropdown").text() + '</select></td>';
            cols += '<td><select class="form-control staff" id="staff_id_' + tot_count + '" name="staff_' + tot_count + '">' + $("#staff_dropdown").text() + '</select></td>';

            cols += '<td><div class="input-group"><input type="text" name="time_from_' + tot_count + '" class="form-control time_from time" id="time_from_' + tot_count + '"  aria-invalid="false"><div class="input-group-addon"><span class="glyphicon glyphicon-dashboard"></span></div></div></td>';

            cols += '<td><div class="input-group"><input type="text" name="time_to_' + tot_count + '" class="form-control time_to time" id="time_to_' + tot_count + '"  aria-invalid="false"><div class="input-group-addon"><span class="glyphicon glyphicon-dashboard"></span></div></div></td>';

            cols += '<td><input type="text" class="form-control room_no" name="room_no_' + tot_count + '" id="room_no_' + tot_count + '"/></td>';
            cols += '<td><button type="button" class="ibtnDel btn btn-danger btn-sm btn-danger"><i class="fa fa-trash"></i></button></td>';
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

        $(document).on("click", ".ibtnDel", function (event) {
            $(this).closest("tr").remove();
            counter -= 1
        });

        $(document).on('click', '.submit_subject_group', function () {
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
        ?>
        <option value="<?php echo $subject_value->id; ?>" ><?php echo $subject_value->name . " (" . $subject_value->code . ")"; ?></option>
        <?php
    }
    ?>
    

</script>

<script>
        $(document).on('click', '.print_timetable', function (e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        var $this = $(this);
        var class_id = $this.data('class_id');
        var section_id = $this.data('section_id');
        $.ajax(
                {
                    url: base_url+'admin/timetable/printclasstimetable',
                    type: "POST",
                    data: {'class_id': class_id,'section_id':section_id},
                    dataType: 'Json',
                    beforeSend: function () {
                $this.button('loading');
            },
                    success: function (data, textStatus, jqXHR)
                    {
                    
                         Popup(data.page);
                        $this.button('reset');
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
                        $this.button('reset');
                    }, complete: function () {
                        $this.button('reset');
                    }
                });
    });
    function Popup(data, winload = false)
    {
        var frameDoc=window.open('', 'Print-Window');
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body onload="window.print()">');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function () {
            frameDoc.close();      
            if (winload) {
                window.location.reload(true);
            }
        }, 5000);

        return true;
    }
</script>