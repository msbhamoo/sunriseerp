<?php $this->load->view('layout/cbseexam_css.php'); ?>

<style type="text/css">
.report-page-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-bottom: 18px;
}
.report-page-title {
    font-size: 20px;
    font-weight: 800;
    color: #0f172a;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
    letter-spacing: -0.3px;
}
.report-page-title i {
    color: #114B5F;
}
.report-header-actions {
    display: flex;
    gap: 8px;
    align-items: center;
    flex-wrap: wrap;
}
.report-btn {
    padding: 7px 14px;
    border-radius: 8px;
    font-size: 12.5px;
    font-weight: 600;
    border: 1px solid #cbd5e1;
    background: #ffffff;
    color: #334155;
    cursor: pointer;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.15s ease;
    white-space: nowrap;
}
.report-btn:hover {
    background: #f8fafc;
    color: #0f172a;
    border-color: #94a3b8;
    text-decoration: none;
}
.report-btn-primary {
    background: #114B5F !important;
    border-color: #114B5F !important;
    color: #ffffff !important;
    box-shadow: 0 2px 6px rgba(17, 75, 95, 0.2) !important;
}
.report-btn-primary:hover {
    background: #0c3847 !important;
    border-color: #0c3847 !important;
    color: #ffffff !important;
}
.report-btn-excel {
    background: #ecfdf5 !important;
    border-color: #a7f3d0 !important;
    color: #059669 !important;
}
.report-btn-excel:hover {
    background: #d1fae5 !important;
    border-color: #6ee7b7 !important;
    color: #047857 !important;
}
.report-btn-print {
    background: #eff6ff !important;
    border-color: #bfdbfe !important;
    color: #1d4ed8 !important;
}
.report-btn-print:hover {
    background: #dbeafe !important;
    border-color: #93c5fd !important;
    color: #1e40af !important;
}

/* Summary Stats Grid */
.status-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 12px;
    margin-bottom: 20px;
}
.status-stat-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
    transition: all 0.15s ease;
}
.status-stat-card:hover {
    border-color: #cbd5e1;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
}
.status-stat-icon {
    width: 46px;
    height: 46px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}
.status-stat-icon.icon-teal {
    background: #f0fdfa;
    color: #114B5F;
    border: 1px solid #99f6e4;
}
.status-stat-icon.icon-green {
    background: #ecfdf5;
    color: #059669;
    border: 1px solid #a7f3d0;
}
.status-stat-icon.icon-amber {
    background: #fffbeb;
    color: #d97706;
    border: 1px solid #fde68a;
}
.status-stat-icon.icon-purple {
    background: #fdf4ff;
    color: #9333ea;
    border: 1px solid #f0abfc;
}
.status-stat-label {
    font-size: 11px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 3px;
}
.status-stat-value {
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.1;
}
.status-stat-subtext {
    font-size: 11.5px;
    color: #64748b;
    margin-top: 2px;
    font-weight: 500;
}

/* Card */
.report-card {
    background: #ffffff;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
    margin-bottom: 20px;
    overflow: hidden;
}
.report-card-header {
    padding: 14px 18px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #ffffff;
    flex-wrap: wrap;
    gap: 10px;
}
.report-card-title {
    font-size: 13px;
    font-weight: 700;
    color: #1e293b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.report-card-title i {
    color: #114B5F;
}
.report-card-body {
    padding: 18px 20px;
}
.report-form-label {
    font-size: 11px;
    font-weight: 700;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 6px;
    display: block;
}
.report-input, .report-select {
    border: 1px solid #cbd5e1 !important;
    border-radius: 8px !important;
    padding: 8px 12px !important;
    height: 38px !important;
    font-size: 13px !important;
    color: #0f172a !important;
    box-shadow: none !important;
    transition: all 0.15s ease !important;
    width: 100%;
}
.report-input:focus, .report-select:focus {
    border-color: #114B5F !important;
    box-shadow: 0 0 0 3px rgba(17, 75, 95, 0.12) !important;
}

/* Filter Buttons Group */
.status-filter-pills {
    display: inline-flex;
    background: #f1f5f9;
    padding: 3px;
    border-radius: 8px;
    gap: 3px;
}
.status-pill-btn {
    border: none;
    background: transparent;
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    cursor: pointer;
    transition: all 0.15s ease;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.status-pill-btn:hover {
    color: #0f172a;
}
.status-pill-btn.active {
    background: #ffffff;
    color: #114B5F;
    font-weight: 700;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.08);
}

/* Quick Search Input */
.report-quick-search {
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 6px 12px;
    font-size: 12.5px;
    height: 34px;
    width: 220px;
    color: #0f172a;
    transition: all 0.15s ease;
}
.report-quick-search:focus {
    border-color: #114B5F;
    outline: none;
    box-shadow: 0 0 0 3px rgba(17, 75, 95, 0.12);
}

/* Class Section Card */
.class-status-card {
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    background: #ffffff;
    margin-bottom: 16px;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
    overflow: hidden;
}
.class-status-header {
    padding: 12px 18px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
}
.class-status-name {
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 8px;
}
.class-progress-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 200px;
}
.custom-progress {
    height: 8px;
    border-radius: 4px;
    background-color: #e2e8f0;
    overflow: hidden;
    flex-grow: 1;
    margin-bottom: 0;
}
.custom-progress-bar {
    height: 100%;
    transition: width 0.3s ease;
}
.bg-progress-green {
    background-color: #10b981;
}
.bg-progress-amber {
    background-color: #f59e0b;
}
.bg-progress-red {
    background-color: #ef4444;
}

/* Modern Status Badges */
.badge-status {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11.5px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.badge-status-completed {
    background: #ecfdf5;
    color: #059669;
    border: 1px solid #a7f3d0;
}
.badge-status-partial {
    background: #fffbeb;
    color: #d97706;
    border: 1px solid #fde68a;
}
.badge-status-pending {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

/* Tables inside class card */
.class-subjects-table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 0;
}
.class-subjects-table thead th {
    background: #ffffff;
    color: #64748b;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #f1f5f9;
    padding: 8px 14px;
}
.class-subjects-table tbody td {
    padding: 10px 14px;
    font-size: 12.5px;
    border-bottom: 1px solid #f8fafc;
    color: #1e293b;
    vertical-align: middle;
}
.class-subjects-table tbody tr:hover td {
    background: #f8fafc;
}
.class-subjects-table tbody tr:last-child td {
    border-bottom: none;
}

.action-link-btn {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: all 0.15s ease;
}
.action-link-btn-enter {
    background: #f0fdfa;
    color: #0f766e;
    border: 1px solid #99f6e4;
}
.action-link-btn-enter:hover {
    background: #ccfbf1;
    color: #115e59;
}
.action-link-btn-edit {
    background: #f8fafc;
    color: #475569;
    border: 1px solid #cbd5e1;
}
.action-link-btn-edit:hover {
    background: #f1f5f9;
    color: #0f172a;
}
</style>

<div class="content-wrapper" style="padding: 15px 20px;">
    <!-- Page Header -->
    <div class="report-page-header">
        <h1 class="report-page-title">
            <i class="fa fa-check-square-o"></i> CBSE Exam Marks Update Status
        </h1>
    </div>

    <!-- Main content -->
    <section class="content" style="padding: 0;">
        <?php $this->load->view('cbseexam/report/_cbsereport'); ?>
        <div class="row">
            <div class="col-md-12">
                <!-- Search & Filter Card -->
                <div class="report-card">
                    <div class="report-card-header">
                        <h3 class="report-card-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="report-card-body">
                        <form role="form" action="<?php echo site_url('cbseexam/report/marksstatus') ?>" method="post" class="row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group mb0">
                                    <label class="report-form-label"><?php echo $this->lang->line('exam'); ?> <small class="text-danger">*</small></label>
                                    <select id="exam_id" name="exam_id" class="form-control report-select select2">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($exams as $exam_key => $exam_value) {
                                        ?>
                                            <option value="<?php echo $exam_value['id'] ?>" <?php
                                                if (set_value('exam_id', isset($exam_id) ? $exam_id : '') == $exam_value['id']) {
                                                    echo "selected=selected";
                                                }
                                            ?>><?php echo $exam_value['name'] ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('exam_id'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group mb0">
                                    <label class="report-form-label"><?php echo $this->lang->line('class'); ?></label>
                                    <select id="class_id" name="class_id" class="form-control report-select">
                                        <option value=""><?php echo $this->lang->line('all'); ?></option>
                                        <?php
                                        if (isset($classlist) && !empty($classlist)) {
                                            foreach ($classlist as $class) {
                                        ?>
                                                <option value="<?php echo $class['id'] ?>" <?php
                                                    if (set_value('class_id', isset($class_id) ? $class_id : '') == $class['id']) {
                                                        echo "selected=selected";
                                                    }
                                                ?>><?php echo $class['class'] ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group mb0">
                                    <label class="report-form-label"><?php echo $this->lang->line('section'); ?></label>
                                    <select id="section_id" name="section_id" class="form-control report-select">
                                        <option value=""><?php echo $this->lang->line('all'); ?></option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                </div>
                            </div>
                            <div class="col-sm-12" style="margin-top: 16px;">
                                <div class="form-group mb0">
                                    <button type="submit" name="search" value="search_filter" class="report-btn report-btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <?php
                if (isset($status_report) && !empty($status_report['exam'])) {
                    $summary = $status_report['summary'];
                    $class_sections = $status_report['class_sections'];
                    $exam_detail = $status_report['exam'];
                ?>
                    <!-- Summary Stats Tiles -->
                    <div class="status-stats-grid">
                        <div class="status-stat-card">
                            <div class="status-stat-icon icon-teal">
                                <i class="fa fa-graduation-cap"></i>
                            </div>
                            <div>
                                <div class="status-stat-label">Assigned Classes</div>
                                <div class="status-stat-value"><?php echo $summary['total_classes']; ?></div>
                                <div class="status-stat-subtext"><?php echo $summary['total_students']; ?> Total Students</div>
                            </div>
                        </div>

                        <div class="status-stat-card">
                            <div class="status-stat-icon icon-green">
                                <i class="fa fa-check-circle"></i>
                            </div>
                            <div>
                                <div class="status-stat-label">Fully Updated</div>
                                <div class="status-stat-value text-success"><?php echo $summary['fully_updated']; ?> / <?php echo $summary['total_classes']; ?></div>
                                <div class="status-stat-subtext">All subjects 100% done</div>
                            </div>
                        </div>

                        <div class="status-stat-card">
                            <div class="status-stat-icon icon-amber">
                                <i class="fa fa-hourglass-half"></i>
                            </div>
                            <div>
                                <div class="status-stat-label">Pending / In Progress</div>
                                <div class="status-stat-value text-warning"><?php echo ($summary['partially_updated'] + $summary['not_started']); ?></div>
                                <div class="status-stat-subtext"><?php echo $summary['not_started']; ?> classes not started</div>
                            </div>
                        </div>

                        <div class="status-stat-card">
                            <div class="status-stat-icon icon-purple">
                                <i class="fa fa-pie-chart"></i>
                            </div>
                            <div>
                                <div class="status-stat-label">Overall Completion</div>
                                <div class="status-stat-value"><?php echo $summary['overall_completion_percent']; ?>%</div>
                                <div class="status-stat-subtext"><?php echo $summary['total_entered_entries']; ?> / <?php echo $summary['total_expected_entries']; ?> marks entered</div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Report Card -->
                    <div class="report-card">
                        <div class="report-card-header">
                            <div class="report-card-title">
                                <i class="fa fa-list-alt"></i> <?php echo $exam_detail['name']; ?> - Marks Status
                            </div>
                            <div class="report-header-actions">
                                <!-- Status Filter Tabs -->
                                <div class="status-filter-pills">
                                    <button type="button" class="status-pill-btn active" data-filter="all">All Classes (<?php echo count($class_sections); ?>)</button>
                                    <button type="button" class="status-pill-btn" data-filter="pending"><i class="fa fa-exclamation-circle text-warning"></i> Pending (<?php echo ($summary['partially_updated'] + $summary['not_started']); ?>)</button>
                                    <button type="button" class="status-pill-btn" data-filter="completed"><i class="fa fa-check-circle text-success"></i> Fully Updated (<?php echo $summary['fully_updated']; ?>)</button>
                                </div>

                                <!-- Instant Search Box -->
                                <input type="text" id="status_class_search" class="report-quick-search" placeholder="Search class or subject...">

                                <button type="button" class="report-btn report-btn-print" onclick="printDiv('div_print_status')">
                                    <i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?>
                                </button>
                                <button type="button" class="report-btn report-btn-excel" onclick="fnExcelReportStatus()">
                                    <i class="fa fa-file-excel-o"></i> <?php echo $this->lang->line('download_excel'); ?>
                                </button>
                            </div>
                        </div>

                        <div class="report-card-body" id="div_print_status" style="padding: 20px;">
                            <div id="class_status_list_container">
                                <?php
                                if (!empty($class_sections)) {
                                    foreach ($class_sections as $cs) {
                                        $status_filter_class = ($cs['status'] == 'completed') ? 'filter-completed' : 'filter-pending';
                                        $progress_color = ($cs['status'] == 'completed') ? 'bg-progress-green' : (($cs['completion_percent'] > 0) ? 'bg-progress-amber' : 'bg-progress-red');
                                ?>
                                        <div class="class-status-card <?php echo $status_filter_class; ?>" data-classname="<?php echo strtolower($cs['class_section_name'] . ' ' . $cs['class_teacher']); ?>">
                                            <div class="class-status-header">
                                                <div>
                                                    <div class="class-status-name">
                                                        <i class="fa fa-bookmark text-primary"></i>
                                                        <span><?php echo $cs['class_section_name']; ?></span>
                                                        <span style="font-size: 12px; font-weight: 600; color: #64748b; margin-left: 6px;">
                                                            (<?php echo $cs['students_count']; ?> Students | <?php echo $cs['total_subjects']; ?> Subjects)
                                                        </span>
                                                    </div>
                                                    <?php if (!empty($cs['class_teacher'])) { ?>
                                                        <div style="font-size: 11.5px; color: #64748b; margin-top: 3px; font-weight: 500;">
                                                            <i class="fa fa-user-circle text-muted"></i> <strong>Class Teacher:</strong> <?php echo $cs['class_teacher']; ?>
                                                        </div>
                                                    <?php } ?>
                                                </div>

                                                <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                                                    <!-- Progress Bar -->
                                                    <div class="class-progress-wrapper">
                                                        <div class="custom-progress">
                                                            <div class="custom-progress-bar <?php echo $progress_color; ?>" style="width: <?php echo $cs['completion_percent']; ?>%;"></div>
                                                        </div>
                                                        <span style="font-size: 12px; font-weight: 800; color: #0f172a; min-width: 40px;">
                                                            <?php echo $cs['completion_percent']; ?>%
                                                        </span>
                                                    </div>

                                                    <!-- Class Status Pill -->
                                                    <?php if ($cs['status'] == 'completed') { ?>
                                                        <span class="badge-status badge-status-completed"><i class="fa fa-check"></i> All Subjects Updated</span>
                                                    <?php } elseif ($cs['status'] == 'partial') { ?>
                                                        <span class="badge-status badge-status-partial"><i class="fa fa-hourglass-half"></i> <?php echo $cs['pending_subjects_count']; ?> Subject(s) Pending</span>
                                                    <?php } else { ?>
                                                        <span class="badge-status badge-status-pending"><i class="fa fa-times-circle"></i> Marks Not Entered</span>
                                                    <?php } ?>
                                                </div>
                                            </div>

                                            <!-- Subjects Table -->
                                            <div class="table-responsive">
                                                <table class="table class-subjects-table">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 22%;"><?php echo $this->lang->line('subject'); ?></th>
                                                            <th style="width: 18%;"><?php echo $this->lang->line('teacher'); ?></th>
                                                            <th style="width: 20%;"><?php echo $this->lang->line('assessment'); ?></th>
                                                            <th style="width: 14%;" class="text-center">Entered / Total Marks</th>
                                                            <th style="width: 12%;" class="text-center">Progress</th>
                                                            <th style="width: 14%;" class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        if (!empty($cs['subjects'])) {
                                                            foreach ($cs['subjects'] as $sub) {
                                                                $sub_progress_color = ($sub['status'] == 'completed') ? 'bg-progress-green' : (($sub['completion_percent'] > 0) ? 'bg-progress-amber' : 'bg-progress-red');
                                                        ?>
                                                                <tr class="subject-row" data-subname="<?php echo strtolower($sub['subject_name'] . ' ' . $sub['subject_code'] . ' ' . $sub['subject_teacher']); ?>">
                                                                    <td>
                                                                        <strong style="color: #0f172a;"><?php echo $sub['subject_name']; ?></strong>
                                                                        <?php if (!empty($sub['subject_code'])) { ?>
                                                                            <span style="color: #64748b; font-size: 11.5px;">(<?php echo $sub['subject_code']; ?>)</span>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php if (!empty($sub['subject_teacher'])) { ?>
                                                                            <span style="font-weight: 600; color: #334155; font-size: 12px; display: inline-flex; align-items: center; gap: 4px;">
                                                                                <i class="fa fa-user-o text-muted"></i> <?php echo $sub['subject_teacher']; ?>
                                                                            </span>
                                                                        <?php } else { ?>
                                                                            <span style="color: #94a3b8; font-style: italic; font-size: 11.5px;">Not Assigned</span>
                                                                        <?php } ?>
                                                                    </td>
                                                                    <td>
                                                                        <?php
                                                                        if (!empty($sub['assessments'])) {
                                                                            $assess_names = [];
                                                                            foreach ($sub['assessments'] as $as) {
                                                                                $code = !empty($as['assessment_code']) ? " (" . $as['assessment_code'] . ")" : "";
                                                                                $assess_names[] = $as['assessment_name'] . $code;
                                                                            }
                                                                            echo "<span style='font-size: 11.5px; color: #475569;'>" . implode(", ", $assess_names) . "</span>";
                                                                        } else {
                                                                            echo "<span style='color:#94a3b8;'>-</span>";
                                                                        }
                                                                        ?>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <span style="font-weight: 700; color: #0f172a;"><?php echo $sub['entered_entries']; ?></span>
                                                                        <span style="color: #64748b;"> / <?php echo $sub['expected_entries']; ?></span>
                                                                    </td>
                                                                    <td class="text-center">
                                                                        <div style="display: flex; align-items: center; gap: 6px; justify-content: center;">
                                                                            <div class="custom-progress" style="width: 60px;">
                                                                                <div class="custom-progress-bar <?php echo $sub_progress_color; ?>" style="width: <?php echo $sub['completion_percent']; ?>%;"></div>
                                                                            </div>
                                                                            <span style="font-size: 11.5px; font-weight: 700; color: #334155; min-width: 32px;"><?php echo $sub['completion_percent']; ?>%</span>
                                                                        </div>
                                                                    </td>
                                                                    <td class="text-right">
                                                                        <div style="display: inline-flex; align-items: center; gap: 6px; justify-content: flex-end;">
                                                                            <?php if ($sub['status'] == 'completed') { ?>
                                                                                <span class="badge-status badge-status-completed"><i class="fa fa-check"></i> Done</span>
                                                                            <?php } elseif ($sub['status'] == 'partial') { ?>
                                                                                <span class="badge-status badge-status-partial"><i class="fa fa-hourglass-half"></i> <?php echo $sub['missing_entries']; ?> Left</span>
                                                                            <?php } else { ?>
                                                                                <span class="badge-status badge-status-pending"><i class="fa fa-times"></i> Not Entered</span>
                                                                            <?php } ?>

                                                                            <?php if ($this->rbac->hasPrivilege('cbse_exam_marks', 'can_edit') || $this->rbac->hasPrivilege('cbse_exam_marks', 'can_view')) { ?>
                                                                                <a href="<?php echo site_url('cbseexam/marks/index/' . $exam_detail['id'] . '/' . $sub['timetable_id']); ?>" class="action-link-btn <?php echo ($sub['status'] == 'completed') ? 'action-link-btn-edit' : 'action-link-btn-enter'; ?>" title="Go to Marks Entry">
                                                                                    <i class="fa fa-pencil-square-o"></i> <?php echo ($sub['status'] == 'completed') ? 'Edit' : 'Enter'; ?>
                                                                                </a>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                        <?php
                                                            }
                                                        } else {
                                                        ?>
                                                            <tr>
                                                                <td colspan="6" class="text-center text-muted" style="padding: 15px;">No subjects scheduled for this class.</td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                <?php
                                    }
                                } else {
                                ?>
                                    <div class="alert alert-info mb0" style="background: #f0fdfa; border-color: #99f6e4; color: #0f766e; border-radius: 8px;">
                                        <i class="fa fa-info-circle"></i> <?php echo $this->lang->line('no_record_found'); ?>
                                    </div>
                                <?php } ?>
                            </div>

                            <!-- Hidden Export Table for clean Excel Download -->
                            <table id="status_excel_table" style="display: none;">
                                <thead>
                                    <tr>
                                        <th>Class (Section)</th>
                                        <th>Class Teacher</th>
                                        <th>Subject</th>
                                        <th>Subject Code</th>
                                        <th>Subject Teacher</th>
                                        <th>Students</th>
                                        <th>Expected Entries</th>
                                        <th>Entered Entries</th>
                                        <th>Missing Entries</th>
                                        <th>Completion %</th>
                                        <th>Subject Status</th>
                                        <th>Class Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($class_sections)) {
                                        foreach ($class_sections as $cs) {
                                            if (!empty($cs['subjects'])) {
                                                foreach ($cs['subjects'] as $sub) {
                                    ?>
                                                    <tr>
                                                        <td><?php echo $cs['class_section_name']; ?></td>
                                                        <td><?php echo $cs['class_teacher']; ?></td>
                                                        <td><?php echo $sub['subject_name']; ?></td>
                                                        <td><?php echo $sub['subject_code']; ?></td>
                                                        <td><?php echo $sub['subject_teacher']; ?></td>
                                                        <td><?php echo $sub['students_count']; ?></td>
                                                        <td><?php echo $sub['expected_entries']; ?></td>
                                                        <td><?php echo $sub['entered_entries']; ?></td>
                                                        <td><?php echo $sub['missing_entries']; ?></td>
                                                        <td><?php echo $sub['completion_percent']; ?>%</td>
                                                        <td><?php echo strtoupper($sub['status']); ?></td>
                                                        <td><?php echo strtoupper($cs['status']); ?></td>
                                                    </tr>
                                    <?php
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id', isset($section_id) ? $section_id : ''); ?>';
        getSectionByClass(class_id, section_id);

        $(document).on('change', '#class_id', function (e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            getSectionByClass(class_id, 0);
        });

        // Filter pills click
        $('.status-pill-btn').on('click', function () {
            $('.status-pill-btn').removeClass('active');
            $(this).addClass('active');
            applyFilters();
        });

        // Instant search
        $('#status_class_search').on('keyup', function () {
            applyFilters();
        });

        function applyFilters() {
            var activeFilter = $('.status-pill-btn.active').data('filter');
            var searchTerm = $('#status_class_search').val().toLowerCase().trim();

            $('.class-status-card').each(function () {
                var card = $(this);
                var className = card.data('classname') || '';
                var isCompleted = card.hasClass('filter-completed');
                var isPending = card.hasClass('filter-pending');

                var statusMatch = true;
                if (activeFilter === 'completed' && !isCompleted) {
                    statusMatch = false;
                } else if (activeFilter === 'pending' && !isPending) {
                    statusMatch = false;
                }

                var textMatch = false;
                if (searchTerm === '' || className.indexOf(searchTerm) > -1) {
                    textMatch = true;
                } else {
                    card.find('.subject-row').each(function () {
                        var subName = $(this).data('subname') || '';
                        if (subName.indexOf(searchTerm) > -1) {
                            textMatch = true;
                        }
                    });
                }

                if (statusMatch && textMatch) {
                    card.show();
                } else {
                    card.hide();
                }
            });
        }
    });

    function getSectionByClass(class_id, section_id) {
        if (class_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('all'); ?></option>';

            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                beforeSend: function () {
                    $('#section_id').addClass('dropdownloading');
                },
                success: function (data) {
                    $.each(data, function (i, obj) {
                        var sel = "";
                        if (section_id == obj.section_id || section_id == obj.id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                },
                complete: function () {
                    $('#section_id').removeClass('dropdownloading');
                }
            });
        } else {
            $('#section_id').html('<option value=""><?php echo $this->lang->line('all'); ?></option>');
        }
    }

    function printDiv(tagid) {
        let hashid = "#" + tagid;
        var tagname = $(hashid).prop("tagName").toLowerCase();
        var attributes = "";
        var attrs = document.getElementById(tagid).attributes;
        $.each(attrs, function(i, elem) {
            attributes += " " + elem.name + " ='" + elem.value + "' ";
        });
        var divToPrint = $(hashid).html();
        var head = "<html><head>" + $("head").html() + "<style>.action-link-btn, .status-filter-pills, .report-quick-search { display: none !important; }</style></head>";
        var allcontent = head + "<body onload='window.print()'>" + "<" + tagname + attributes + ">" + divToPrint + "</" + tagname + ">" + "</body></html>";

        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({ "position": "absolute", "top": "-1000000px" });
        $("body").append(frame1);
        var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
        frameDoc.document.open();
        frameDoc.document.write(allcontent);
        frameDoc.document.close();
        setTimeout(function() {
            window.frames["frame1"].focus();
            window.frames["frame1"].print();
            frame1.remove();
        }, 500);
    }

    function fnExcelReportStatus() {
        var tab_text = "<table border='1px'><tr bgcolor='#f8fafc'>";
        var textRange;
        var j = 0;
        var tab = document.getElementById('status_excel_table');

        for (j = 0; j < tab.rows.length; j++) {
            tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
        }

        tab_text = tab_text + "</table>";
        tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, "");
        tab_text = tab_text.replace(/<img[^>]*>/gi, "");
        tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, "");

        var ua = window.navigator.userAgent;
        var msie = ua.indexOf("MSIE ");

        if (msie > 0 || !navigator.userAgent.match(/Trident.*rv\:11\./)) {
            var sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
        } else {
            txtArea1.document.open("txt/html", "replace");
            txtArea1.document.write(tab_text);
            txtArea1.document.close();
            txtArea1.focus();
            sa = txtArea1.document.execCommand("SaveAs", true, "CBSE_Exam_Marks_Status_Report.xls");
        }

        return (sa);
    }
</script>
