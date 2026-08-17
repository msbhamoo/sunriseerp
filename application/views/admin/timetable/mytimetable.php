<style type="text/css">
    .dashboard2-wrapper {
        background-color: #f8fafc;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }
    
    /* Single Row Horizontal Scroll Container */
    .tt-row-container {
        display: flex;
        flex-direction: row;
        gap: 16px;
        overflow-x: auto;
        padding-bottom: 16px;
        scroll-behavior: smooth;
    }
    .tt-row-container::-webkit-scrollbar {
        height: 8px;
    }
    .tt-row-container::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }
    .tt-row-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    .tt-row-container::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    .tt-day-col {
        flex: 0 0 280px;
        min-width: 280px;
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 18px rgba(0,0,0,0.03);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .tt-day-col:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.06);
    }
    
    /* Sticky First Column */
    .tt-row-container > .tt-day-col:first-child {
        position: sticky;
        left: 0;
        z-index: 10;
        box-shadow: 4px 0 15px rgba(0,0,0,0.08);
    }

    /* Pastel Theme Variations per Day */
    .pastel-monday { --day-bg: #eef2ff; --day-header-bg: #e0e7ff; --day-accent: #4f46e5; --badge-bg: #e0e7ff; --badge-text: #3730a3; }
    .pastel-tuesday { --day-bg: #ecfdf5; --day-header-bg: #d1fae5; --day-accent: #059669; --badge-bg: #d1fae5; --badge-text: #065f46; }
    .pastel-wednesday { --day-bg: #fdf4ff; --day-header-bg: #fae8ff; --day-accent: #c026d3; --badge-bg: #fae8ff; --badge-text: #86198f; }
    .pastel-thursday { --day-bg: #fff7ed; --day-header-bg: #ffedd5; --day-accent: #ea580c; --badge-bg: #ffedd5; --badge-text: #9a3412; }
    .pastel-friday { --day-bg: #f0f9ff; --day-header-bg: #e0f2fe; --day-accent: #0284c7; --badge-bg: #e0f2fe; --badge-text: #075985; }
    .pastel-saturday { --day-bg: #fef2f2; --day-header-bg: #fee2e2; --day-accent: #dc2626; --badge-bg: #fee2e2; --badge-text: #991b1b; }
    .pastel-sunday { --day-bg: #f8fafc; --day-header-bg: #f1f5f9; --day-accent: #64748b; --badge-bg: #f1f5f9; --badge-text: #334155; }

    .tt-day-header {
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
    .tt-day-header.today {
        box-shadow: inset 0 -3px 0 0 var(--day-accent);
    }
    .tt-day-body {
        padding: 14px;
        background: #ffffff;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .tt-slot-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-left: 5px solid var(--day-accent);
        border-radius: 10px;
        padding: 12px 14px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    .tt-slot-subject {
        font-size: 13px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 6px;
        line-height: 1.3;
    }
    .tt-slot-class {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: var(--badge-bg);
        color: var(--badge-text);
        font-size: 11px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 6px;
        margin-bottom: 8px;
    }
    .tt-slot-meta {
        font-size: 11px;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-weight: 600;
    }
    .tt-slot-meta i { color: var(--day-accent); margin-right: 4px; }

    .tt-break-card {
        background: var(--day-bg);
        border: 1px dashed var(--day-accent);
        border-radius: 10px;
        padding: 10px;
        text-align: center;
        color: var(--day-accent);
        font-size: 12px;
        font-weight: 600;
    }

    .tt-empty-day {
        text-align: center;
        padding: 32px 12px;
        color: #94a3b8;
        font-size: 13px;
        font-weight: 500;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-calendar-check-o"></i> <?php echo $this->lang->line('teacher_time_table'); ?></h1>
    </section>

    <section class="content">
        <?php
        $total_periods = 0;
        $active_days = 0;
        $current_day = date('l');

        if (!empty($timetable)) {
            foreach ($timetable as $day_k => $day_v) {
                if (!empty($day_v)) {
                    $active_days++;
                    foreach ($day_v as $period) {
                        if (!isset($period->period_type) || $period->period_type != 'break') {
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

        <!-- Header Card Box -->
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-clock-o text-muted" style="margin-right: 6px;"></i> My Timetable Schedule</h3>
                <div class="box-tools pull-right">
                    <?php if (!empty($timetable)) { ?>
                        <button type="button" title="<?php echo $this->lang->line('print'); ?>" class="btn btn-default btn-sm print_timetable" data-staff_id="<?php echo $staff_id;?>" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>">
                            <i class="fa fa-print text-primary"></i> <?php echo $this->lang->line('print'); ?>
                        </button>
                    <?php } ?>
                </div>
            </div>
            <div class="box-body" style="padding-bottom: 0;">
                <!-- Modern Summary Metrics -->
                <div class="modern-stat-grid" style="margin-bottom: 16px;">
                    <div class="modern-stat-card">
                        <div class="modern-stat-info">
                            <div class="stat-label">Total Assigned Periods</div>
                            <div class="stat-value"><?php echo $total_periods; ?></div>
                        </div>
                        <div class="modern-stat-icon" style="background: rgba(99, 102, 241, 0.12); color: #6366f1;">
                            <i class="fa fa-book"></i>
                        </div>
                    </div>
                    
                    <div class="modern-stat-card">
                        <div class="modern-stat-info">
                            <div class="stat-label">Today's Load (<?php echo substr($current_day, 0, 3); ?>)</div>
                            <div class="stat-value text-success" style="color: #059669;"><?php echo isset($timetable[$current_day]) ? count($timetable[$current_day]) : 0; ?> <span style="font-size:12px; font-weight:600; color:#64748b;">periods</span></div>
                        </div>
                        <div class="modern-stat-icon" style="background: rgba(16, 185, 129, 0.12); color: #059669;">
                            <i class="fa fa-clock-o"></i>
                        </div>
                    </div>
                    
                    <div class="modern-stat-card">
                        <div class="modern-stat-info">
                            <div class="stat-label">Active Working Days</div>
                            <div class="stat-value" style="color: #0284c7;"><?php echo $active_days; ?> <span style="font-size:12px; font-weight:600; color:#64748b;">days/week</span></div>
                        </div>
                        <div class="modern-stat-icon" style="background: rgba(2, 132, 199, 0.12); color: #0284c7;">
                            <i class="fa fa-calendar-check-o"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Single Row Horizontal Scroll Timetable -->
        <?php if (!empty($timetable)) { ?>
            <div class="tt-row-container">
                <?php foreach ($timetable as $day_name => $periods) { 
                    $is_today = (strtolower($day_name) == strtolower($current_day));
                    $p_class = isset($pastel_classes[$day_name]) ? $pastel_classes[$day_name] : 'pastel-monday';
                ?>
                    <div class="tt-day-col <?php echo $p_class; ?>">
                        <div class="tt-day-header <?php echo $is_today ? 'today' : ''; ?>">
                            <span><i class="fa fa-clock-o"></i> <?php echo $day_name; ?></span>
                            <?php if ($is_today) { ?>
                                <span class="label label-warning" style="font-size: 10px; border-radius: 4px; padding: 3px 6px;">Today</span>
                            <?php } ?>
                        </div>
                        <div class="tt-day-body">
                            <?php if (empty($periods)) { ?>
                                <div class="tt-empty-day">
                                    <i class="fa fa-coffee" style="font-size: 28px; color: #cbd5e1; margin-bottom: 8px; display: block;"></i>
                                    <?php echo $this->lang->line('not_scheduled'); ?>
                                </div>
                            <?php } else { 
                                foreach ($periods as $p) {
                                    if (isset($p->period_type) && $p->period_type == 'break') { ?>
                                        <div class="tt-break-card">
                                            <i class="fa fa-cutlery"></i> <strong><?php echo $p->break_label; ?></strong><br/>
                                            <small><i class="fa fa-clock-o"></i> <?php echo $p->time_from; ?> - <?php echo $p->time_to; ?></small>
                                        </div>
                                    <?php } else { ?>
                                        <div class="tt-slot-card">
                                            <div class="tt-slot-subject">
                                                <?php echo $p->subject_name; ?>
                                                <?php if (!empty($p->subject_code)) { ?>
                                                    <span style="font-size:10px; color:#64748b; font-weight:600;">(<?php echo $p->subject_code; ?>)</span>
                                                <?php } ?>
                                            </div>
                                            <div>
                                                <span class="tt-slot-class"><i class="fa fa-graduation-cap"></i> Class <?php echo $p->class . " (" . $p->section . ")"; ?></span>
                                            </div>
                                            <div class="tt-slot-meta">
                                                <span><i class="fa fa-clock-o"></i> <?php echo $p->time_from . " - " . $p->time_to; ?></span>
                                                <?php if (!empty($p->room_no)) { ?>
                                                    <span><i class="fa fa-building"></i> Rm: <?php echo $p->room_no; ?></span>
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
    </section>
</div>

<script>
    
    $(document).on('click', '.print_timetable', function (e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        var $this = $(this);
        var id = $this.data('staff_id');
        $.ajax(
                {
                    url: "<?php echo site_url('admin/timetable/printteachertimetable') ?>",
                    type: "POST",
                    data: {'staff_id': id},
                    dataType: 'Json',
                    beforeSend: function () {
                $this.button('loading');
            },
                    success: function (data, textStatus, jqXHR)
                    {
                        console.log(data.page);
                         Popup(data.page);
                        $this.button('reset');
                    },
                    error: function (jqXHR, textStatus, errorThrown)
                    {
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