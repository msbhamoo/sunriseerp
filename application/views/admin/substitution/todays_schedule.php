<style type="text/css">
    .dashboard2-wrapper {
        background-color: #f8fafc;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }
    .ts-header-card {
        background: #ffffff;
        border-radius: 14px;
        padding: 18px 24px;
        margin-bottom: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        border: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .ts-header-title {
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .ts-header-title i {
        color: #0284c7;
        font-size: 22px;
    }

    .ts-metrics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 20px;
    }
    .ts-metric-box {
        background: #ffffff;
        border-radius: 14px;
        padding: 16px 20px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .ts-metric-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }
    .ts-metric-icon.blue { background: #e0f2fe; color: #0284c7; }
    .ts-metric-icon.amber { background: #fef3c7; color: #d97706; }
    .ts-metric-icon.rose { background: #ffe4e6; color: #e11d48; }

    .ts-metric-label { font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
    .ts-metric-val { font-size: 22px; font-weight: 800; color: #0f172a; line-height: 1.2; }

    /* Single Row Horizontal Scroll Container */
    .ts-row-container {
        display: flex;
        flex-direction: row;
        gap: 16px;
        overflow-x: auto;
        padding-bottom: 16px;
        scroll-behavior: smooth;
    }
    .ts-row-container::-webkit-scrollbar {
        height: 8px;
    }
    .ts-row-container::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 4px;
    }
    .ts-row-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    .ts-class-col {
        flex: 0 0 300px;
        min-width: 300px;
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 4px 18px rgba(0,0,0,0.03);
        display: flex;
        flex-direction: column;
        overflow: hidden;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .ts-class-col:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.06);
    }

    .ts-class-header {
        background: #f8fafc;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 14px 18px;
        font-size: 15px;
        font-weight: 800;
        color: #0f172a;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .ts-class-body {
        padding: 14px;
        background: #ffffff;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .ts-slot-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-left: 5px solid #0284c7;
        border-radius: 12px;
        padding: 12px 14px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    }
    .ts-slot-card.substituted {
        border-left-color: #f59e0b;
        background: #fffbeb;
    }
    .ts-slot-card.uncovered {
        border-left-color: #ef4444;
        background: #fef2f2;
    }

    .ts-slot-subject {
        font-size: 13px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .ts-slot-meta {
        font-size: 11px;
        color: #64748b;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-weight: 600;
        margin-top: 6px;
    }
    .ts-slot-meta i { margin-right: 4px; }

    .ts-teacher-info {
        font-size: 12px;
        font-weight: 700;
        color: #334155;
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .ts-badge {
        font-size: 10px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 6px;
        text-transform: uppercase;
    }
    .ts-badge.normal { background: #e0f2fe; color: #0369a1; }
    .ts-badge.sub { background: #fef3c7; color: #b45309; }
    .ts-badge.danger { background: #fee2e2; color: #b91c1c; }

    /* Pastel accents for Class Cards */
    .pastel-theme-0 { --accent: #3b82f6; --hdr-bg: #eff6ff; }
    .pastel-theme-1 { --accent: #10b981; --hdr-bg: #ecfdf5; }
    .pastel-theme-2 { --accent: #a855f7; --hdr-bg: #faf5ff; }
    .pastel-theme-3 { --accent: #f97316; --hdr-bg: #fff7ed; }
    .pastel-theme-4 { --accent: #06b6d4; --hdr-bg: #ecfeff; }
    .pastel-theme-5 { --accent: #ec4899; --hdr-bg: #fdf2f8; }

    .ts-class-col {
        border-top: 4px solid var(--accent);
    }
    .ts-class-header {
        background: var(--hdr-bg);
        color: var(--accent);
    }
</style>

<div class="content-wrapper dashboard2-wrapper" style="min-height: 946px;">
    <section class="content">
        <?php
        $total_classes = count($schedule);
        $total_substituted = 0;
        $total_uncovered = 0;

        foreach ($schedule as $cls => $tt_list) {
            if (!empty($tt_list)) {
                foreach ($tt_list as $t_item) {
                    if (!isset($t_item->period_type) || $t_item->period_type != 'break') {
                        if (!empty($t_item->is_substituted)) $total_substituted++;
                        if (!empty($t_item->is_absent_uncovered)) $total_uncovered++;
                    }
                }
            }
        }
        ?>

        <!-- Header Card -->
        <div class="ts-header-card">
            <div class="ts-header-title">
                <i class="fa fa-calendar-check-o"></i> Today's Schedule Overview
                <span style="font-size: 13px; font-weight: 600; color: #64748b;">(<?php echo date($this->customlib->getSchoolDateFormat()) . ' - ' . $day; ?>)</span>
            </div>
        </div>

        <!-- Metrics Row -->
        <div class="ts-metrics-grid">
            <div class="ts-metric-box">
                <div class="ts-metric-icon blue">
                    <i class="fa fa-th"></i>
                </div>
                <div>
                    <div class="ts-metric-label">Total Class Sections</div>
                    <div class="ts-metric-val"><?php echo $total_classes; ?></div>
                </div>
            </div>
            <div class="ts-metric-box">
                <div class="ts-metric-icon amber">
                    <i class="fa fa-refresh"></i>
                </div>
                <div>
                    <div class="ts-metric-label">Substituted Periods</div>
                    <div class="ts-metric-val"><?php echo $total_substituted; ?></div>
                </div>
            </div>
            <div class="ts-metric-box">
                <div class="ts-metric-icon rose">
                    <i class="fa fa-exclamation-triangle"></i>
                </div>
                <div>
                    <div class="ts-metric-label">Uncovered Absences</div>
                    <div class="ts-metric-val"><?php echo $total_uncovered; ?></div>
                </div>
            </div>
        </div>

        <!-- Single Row Horizontal Class Cards -->
        <?php if (!empty($schedule)) { ?>
            <div class="ts-row-container">
                <?php 
                $theme_idx = 0;
                foreach ($schedule as $class_name => $timetable) { 
                    if (!empty($timetable)) {
                        $p_theme = 'pastel-theme-' . ($theme_idx % 6);
                        $theme_idx++;
                ?>
                    <div class="ts-class-col <?php echo $p_theme; ?>">
                        <div class="ts-class-header">
                            <span><i class="fa fa-graduation-cap"></i> <?php echo $class_name; ?></span>
                            <span class="ts-badge normal"><?php echo count($timetable); ?> slots</span>
                        </div>
                        <div class="ts-class-body">
                            <?php foreach ($timetable as $t) { ?>
                                <?php if (isset($t->period_type) && $t->period_type == 'break') { ?>
                                    <div style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; padding: 8px; text-align: center; font-size: 11px; color: #64748b; font-weight: 600;">
                                        <i class="fa fa-cutlery"></i> <?php echo $t->break_label; ?> (<?php echo $t->time_from; ?> - <?php echo $t->time_to; ?>)
                                    </div>
                                <?php } else { 
                                    $card_class = $t->is_absent_uncovered ? 'uncovered' : ($t->is_substituted ? 'substituted' : '');
                                ?>
                                    <div class="ts-slot-card <?php echo $card_class; ?>">
                                        <div class="ts-slot-subject">
                                            <span><?php echo $t->subject_name != '' ? $t->subject_name : 'N/A'; ?><?php echo !empty($t->code) ? " (" . $t->code . ")" : ""; ?></span>
                                            <?php if ($t->is_substituted) { ?>
                                                <span class="ts-badge sub"><i class="fa fa-exchange"></i> Sub</span>
                                            <?php } else if ($t->is_absent_uncovered) { ?>
                                                <span class="ts-badge danger"><i class="fa fa-warning"></i> Uncovered</span>
                                            <?php } ?>
                                        </div>

                                        <div class="ts-teacher-info">
                                            <i class="fa fa-user-circle" style="color: #64748b;"></i>
                                            <?php if ($t->is_substituted) { ?>
                                                <span><?php echo $t->name . " " . $t->surname; ?></span>
                                            <?php } else if ($t->is_absent_uncovered) { ?>
                                                <span style="color: #dc2626; font-weight: 800;"><?php echo $t->name . " " . $t->surname; ?> (Absent)</span>
                                            <?php } else { ?>
                                                <span><?php echo $t->name != '' ? $t->name . " " . $t->surname : 'Not Scheduled'; ?></span>
                                            <?php } ?>
                                        </div>

                                        <div class="ts-slot-meta">
                                            <span><i class="fa fa-clock-o"></i> <?php echo $t->time_from . " - " . $t->time_to; ?></span>
                                            <?php if (!empty($t->room_no)) { ?>
                                                <span><i class="fa fa-building"></i> Rm: <?php echo $t->room_no; ?></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                <?php } 
                } ?>
            </div>
        <?php } else { ?>
            <div class="alert alert-info" style="border-radius: 10px;">
                <?php echo $this->lang->line('no_record_found'); ?>
            </div>
        <?php } ?>
    </section>
</div>
