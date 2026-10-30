<style type="text/css">
    .tt-part-row-container {
        display: flex;
        flex-direction: row;
        gap: 16px;
        overflow-x: auto;
        padding-bottom: 16px;
        scroll-behavior: smooth;
        margin-top: 15px;
    }
    .tt-part-row-container::-webkit-scrollbar {
        height: 8px;
    }
    .tt-part-row-container::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }
    .tt-part-row-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .tt-part-day-col {
        flex: 0 0 280px;
        min-width: 280px;
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 18px rgba(0,0,0,0.03);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    /* Pastel Theme Variations per Day */
    .pastel-monday { --day-bg: #eef2ff; --day-header-bg: #e0e7ff; --day-accent: #4f46e5; --badge-bg: #e0e7ff; --badge-text: #3730a3; }
    .pastel-tuesday { --day-bg: #ecfdf5; --day-header-bg: #d1fae5; --day-accent: #059669; --badge-bg: #d1fae5; --badge-text: #065f46; }
    .pastel-wednesday { --day-bg: #fdf4ff; --day-header-bg: #fae8ff; --day-accent: #c026d3; --badge-bg: #fae8ff; --badge-text: #86198f; }
    .pastel-thursday { --day-bg: #fff7ed; --day-header-bg: #ffedd5; --day-accent: #ea580c; --badge-bg: #ffedd5; --badge-text: #9a3412; }
    .pastel-friday { --day-bg: #f0f9ff; --day-header-bg: #e0f2fe; --day-accent: #0284c7; --badge-bg: #e0f2fe; --badge-text: #075985; }
    .pastel-saturday { --day-bg: #fef2f2; --day-header-bg: #fee2e2; --day-accent: #dc2626; --badge-bg: #fee2e2; --badge-text: #991b1b; }
    .pastel-sunday { --day-bg: #f8fafc; --day-header-bg: #f1f5f9; --day-accent: #64748b; --badge-bg: #f1f5f9; --badge-text: #334155; }

    .tt-part-day-header {
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
    .tt-part-day-header.today {
        box-shadow: inset 0 -3px 0 0 var(--day-accent);
    }
    .tt-part-day-body {
        padding: 14px;
        background: #ffffff;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .tt-part-slot-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-left: 5px solid var(--day-accent);
        border-radius: 12px;
        padding: 12px 14px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    .tt-part-slot-subject {
        font-size: 13px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 6px;
    }
    .tt-part-slot-class {
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
    .tt-part-slot-meta {
        font-size: 11px;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-weight: 600;
    }
    .tt-part-slot-meta i { color: var(--day-accent); margin-right: 4px; }
</style>

<?php if (!empty($timetable)) { 
    $current_day = date('l');
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
    <div class="row" style="margin-bottom: 10px;">
        <div class="col-md-12">
            <button type="submit" title="<?php echo $this->lang->line('print'); ?>" class="btn btn-primary btn-sm pull-right print_timetable" data-staff_id="<?php echo $staff_id;?>" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>">
                <i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?>
            </button>
        </div>
    </div>

    <div class="tt-part-row-container">
        <?php foreach ($timetable as $tm_key => $tm_value) { 
            $is_today = (strtolower($tm_key) == strtolower($current_day));
            $p_class = isset($pastel_classes[$tm_key]) ? $pastel_classes[$tm_key] : 'pastel-monday';
        ?>
            <div class="tt-part-day-col <?php echo $p_class; ?>">
                <div class="tt-part-day-header <?php echo $is_today ? 'today' : ''; ?>">
                    <span><i class="fa fa-clock-o"></i> <?php echo $tm_key; ?></span>
                    <?php if ($is_today) { ?>
                        <span class="label label-warning" style="font-size: 10px; border-radius: 4px; padding: 3px 6px;">Today</span>
                    <?php } ?>
                </div>
                <div class="tt-part-day-body">
                    <?php if (!$timetable[$tm_key]) { ?>
                        <div class="text-center" style="padding: 28px 12px; color: #94a3b8; font-size: 12px;">
                            <i class="fa fa-coffee" style="font-size: 24px; color: #cbd5e1; margin-bottom: 6px; display: block;"></i>
                            <?php echo $this->lang->line('not_scheduled'); ?>
                        </div>
                    <?php } else {
                        foreach ($timetable[$tm_key] as $tm_k => $tm_kue) {
                            if (isset($tm_kue->period_type) && $tm_kue->period_type == 'break') { ?>
                                <div style="background: var(--day-bg); border: 1px dashed var(--day-accent); border-radius: 8px; padding: 8px; text-align: center; font-size: 11px; color: var(--day-accent); font-weight: 600;">
                                    <i class="fa fa-cutlery"></i> <strong><?php echo $tm_kue->break_label; ?></strong> (<?php echo $tm_kue->time_from; ?> - <?php echo $tm_kue->time_to; ?>)
                                </div>
                            <?php } else { ?>
                                <div class="tt-part-slot-card">
                                    <div class="tt-part-slot-subject">
                                        <?php echo $tm_kue->subject_name; ?><?php echo !empty($tm_kue->subject_code) ? " (" . $tm_kue->subject_code . ")" : ""; ?>
                                    </div>
                                    <div>
                                        <span class="tt-part-slot-class"><i class="fa fa-graduation-cap"></i> <?php echo $tm_kue->class . "(" . $tm_kue->section . ")"; ?></span>
                                    </div>
                                    <div class="tt-part-slot-meta">
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
    <div class="alert alert-info">
        <?php echo $this->lang->line('no_record_found'); ?>
    </div>
<?php } ?>