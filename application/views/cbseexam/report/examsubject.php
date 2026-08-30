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

/* Stats Grid */
.report-stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 12px;
    margin-bottom: 20px;
}
.report-stat-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 14px 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
    transition: all 0.15s ease;
}
.report-stat-card:hover {
    border-color: #cbd5e1;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
}
.report-stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}
.report-stat-icon.icon-teal {
    background: #f0fdfa;
    color: #114B5F;
    border: 1px solid #99f6e4;
}
.report-stat-icon.icon-blue {
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
}
.report-stat-icon.icon-amber {
    background: #fffbeb;
    color: #d97706;
    border: 1px solid #fde68a;
}
.report-stat-icon.icon-purple {
    background: #fdf4ff;
    color: #9333ea;
    border: 1px solid #f0abfc;
}
.report-stat-label {
    font-size: 10.5px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 2px;
}
.report-stat-value {
    font-size: 19px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.1;
}
.report-stat-subtext {
    font-size: 11px;
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

/* Modern Table */
.report-modern-table {
    border-collapse: separate !important;
    border-spacing: 0 !important;
    border: 1px solid #e2e8f0 !important;
    border-radius: 8px !important;
    overflow: hidden !important;
    width: 100% !important;
    margin-bottom: 0 !important;
}
.report-modern-table thead th {
    background: #f8fafc !important;
    color: #475569 !important;
    font-size: 11px !important;
    font-weight: 700 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
    border: 1px solid #e2e8f0 !important;
    padding: 10px 8px !important;
    vertical-align: middle !important;
}
.report-modern-table tbody td {
    padding: 10px 10px !important;
    font-size: 12.5px !important;
    border-color: #f1f5f9 !important;
    vertical-align: middle !important;
    color: #1e293b;
}
.report-modern-table tbody tr:hover td {
    background: #f8fafc !important;
}
.report-modern-table tfoot td {
    background: #f8fafc !important;
    border-top: 2px solid #cbd5e1 !important;
    padding: 9px 8px !important;
    vertical-align: middle !important;
}
.report-student-name {
    font-weight: 700;
    color: #0f172a;
}
.report-badge {
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 11.5px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.report-badge-total {
    background: #f1f5f9;
    color: #334155;
    border: 1px solid #e2e8f0;
}
.report-badge-percent {
    background: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
}
.report-badge-grade {
    border: 1px solid transparent;
}
.report-badge-grade-a {
    background: #ecfdf5 !important;
    color: #059669 !important;
    border-color: #a7f3d0 !important;
}
.report-badge-grade-b {
    background: #eff6ff !important;
    color: #1d4ed8 !important;
    border-color: #bfdbfe !important;
}
.report-badge-grade-c {
    background: #fffbeb !important;
    color: #d97706 !important;
    border-color: #fde68a !important;
}
.report-badge-grade-d {
    background: #fef2f2 !important;
    color: #dc2626 !important;
    border-color: #fecaca !important;
}
.report-badge-grade-default {
    background: #f1f5f9 !important;
    color: #475569 !important;
    border-color: #e2e8f0 !important;
}
.report-badge-rank {
    background: #fdf4ff;
    color: #9333ea;
    border: 1px solid #f0abfc;
}
.report-count-badge {
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 700;
    background: #ecfdf5;
    color: #059669;
    border: 1px solid #a7f3d0;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

/* Person Details Cell Matching System Style */
.person-details-cell {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 180px;
    text-align: left;
}
.person-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    object-fit: cover;
    border: 1.5px solid #e2e8f0;
    box-shadow: 0 1px 2px rgba(0,0,0,0.06);
    flex-shrink: 0;
    background: #f8fafc;
}
.person-name-title {
    font-weight: 700;
    color: #0f172a;
    font-size: 13px;
    line-height: 1.2;
    text-transform: uppercase;
}
.person-sub-row {
    margin-top: 3px;
    display: flex;
    align-items: center;
    gap: 6px;
    flex-wrap: wrap;
}
.person-adm-badge {
    background: #eef2ff;
    color: #4338ca;
    padding: 1px 6px;
    border-radius: 4px;
    font-weight: 700;
    font-size: 11px;
    font-family: inherit;
}
.person-class-text {
    font-size: 11px;
    color: #64748b;
    font-weight: 600;
}
</style>

<div class="content-wrapper" style="padding: 15px 20px;">
    <!-- Page Header -->
    <div class="report-page-header">
        <h1 class="report-page-title">
            <i class="fa fa-file-text-o"></i> <?php echo $this->lang->line('subject_wise_marks_report'); ?>
        </h1>
    </div>

    <!-- Main content -->
    <section class="content" style="padding: 0;">
        <?php $this->load->view('cbseexam/report/_cbsereport'); ?>
        <div class="row">
            <div class="col-md-12">
                <!-- Filter Card -->
                <div class="report-card">
                    <div class="report-card-header">
                        <h3 class="report-card-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="report-card-body">
                        <form role="form" action="<?php echo site_url('cbseexam/report/examsubject') ?>" method="post" class="row">
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
                                                                                            if (set_value('exam_id') == $exam_value['id']) {
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
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
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
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
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
                if (isset($subjects)) {
                    if (!empty($subjects) && !empty($students)) {
                        // Precompute class statistics
                        $total_students_count = count($students);
                        $sum_percentages = 0;
                        $top_percentage = -1;
                        $top_student_name = "-";
                        $top_student_rank = 1;

                        // Subject & Assessment column aggregates: [subject_id][assessment_id] => ['sum' => 0, 'count' => 0, 'max' => 0]
                        $col_stats = [];

                        foreach ($students as $stu_key => $stu_val) {
                            $stu_tot_marks = 0;
                            $stu_tot_max = 0;

                            foreach ($subjects as $sb_k => $sb_v) {
                                foreach ($exam_assessments as $ea_k => $ea_v) {
                                    $as_exists = find_subject_assessment_exists($subject_assessments, $sb_v->id, $ea_v->id);
                                    if ($as_exists) {
                                        $as_arr = findAssessmentValue($sb_v->subject_id, $ea_v->id, $stu_val);
                                        $m_val = ($as_arr['marks'] === "N/A" || $as_arr['is_absent']) ? 0 : (float)$as_arr['marks'];
                                        $stu_tot_max += (float)$as_arr['maximum_marks'];
                                        $stu_tot_marks += $m_val;

                                        if (!isset($col_stats[$sb_v->id][$ea_v->id])) {
                                            $col_stats[$sb_v->id][$ea_v->id] = ['sum' => 0, 'count' => 0, 'highest' => 0, 'max_marks' => (float)$as_arr['maximum_marks']];
                                        }
                                        if (!$as_arr['is_absent'] && $as_arr['marks'] !== "N/A") {
                                            $col_stats[$sb_v->id][$ea_v->id]['sum'] += $m_val;
                                            $col_stats[$sb_v->id][$ea_v->id]['count']++;
                                            if ($m_val > $col_stats[$sb_v->id][$ea_v->id]['highest']) {
                                                $col_stats[$sb_v->id][$ea_v->id]['highest'] = $m_val;
                                            }
                                        }
                                    }
                                }
                            }

                            $stu_percent = ($stu_tot_max > 0) ? getPercent($stu_tot_max, $stu_tot_marks) : 0;
                            $sum_percentages += (float)$stu_percent;

                            if ((float)$stu_percent > $top_percentage) {
                                $top_percentage = (float)$stu_percent;
                                $top_student_name = trim($stu_val['firstname'] . " " . $stu_val['lastname']);
                                $top_student_rank = $stu_val['rank'];
                            }
                        }

                        $avg_class_percentage = ($total_students_count > 0) ? round($sum_percentages / $total_students_count, 2) : 0;
                ?>
                        <!-- Summary Stats Tiles -->
                        <div class="report-stats-grid">
                            <div class="report-stat-card">
                                <div class="report-stat-icon icon-teal">
                                    <i class="fa fa-users"></i>
                                </div>
                                <div>
                                    <div class="report-stat-label"><?php echo $this->lang->line('students'); ?></div>
                                    <div class="report-stat-value"><?php echo $total_students_count; ?></div>
                                    <div class="report-stat-subtext">Total Enrolled</div>
                                </div>
                            </div>
                            <div class="report-stat-card">
                                <div class="report-stat-icon icon-blue">
                                    <i class="fa fa-line-chart"></i>
                                </div>
                                <div>
                                    <div class="report-stat-label">Class Average</div>
                                    <div class="report-stat-value"><?php echo $avg_class_percentage; ?>%</div>
                                    <div class="report-stat-subtext">Overall Score</div>
                                </div>
                            </div>
                            <div class="report-stat-card">
                                <div class="report-stat-icon icon-amber">
                                    <i class="fa fa-trophy"></i>
                                </div>
                                <div>
                                    <div class="report-stat-label">Top Scorer</div>
                                    <div class="report-stat-value"><?php echo $top_percentage >= 0 ? $top_percentage . '%' : '-'; ?></div>
                                    <div class="report-stat-subtext"><?php echo htmlspecialchars($top_student_name); ?></div>
                                </div>
                            </div>
                            <div class="report-stat-card">
                                <div class="report-stat-icon icon-purple">
                                    <i class="fa fa-book"></i>
                                </div>
                                <div>
                                    <div class="report-stat-label">Subjects</div>
                                    <div class="report-stat-value"><?php echo count($subjects); ?></div>
                                    <div class="report-stat-subtext"><?php echo count($exam_assessments); ?> Assessment(s)</div>
                                </div>
                            </div>
                        </div>

                        <!-- Results Card -->
                        <div class="report-card">
                            <div class="report-card-header">
                                <div class="report-card-title">
                                    <i class="fa fa-list-alt"></i> <?php echo $exam->name; ?>
                                </div>
                                <div class="report-header-actions">
                                    <!-- Instant Search Input -->
                                    <div class="input-group" style="width: auto;">
                                        <input type="text" id="report_student_search" class="report-quick-search" placeholder="Search student / adm no...">
                                    </div>
                                    <span id="filtered_students_count_badge" class="report-count-badge">
                                        <i class="fa fa-users"></i> <?php echo count($students); ?> <?php echo $this->lang->line('students'); ?>
                                    </span>
                                    <button type="button" class="report-btn report-btn-print" title="<?php echo $this->lang->line('print'); ?>" onclick="printDiv('div_print')">
                                        <i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?>
                                    </button>
                                    <button type="button" class="report-btn report-btn-excel" title="<?php echo $this->lang->line('download_excel'); ?>" onclick="fnExcelReport()">
                                        <i class="fa fa-file-excel-o"></i> <?php echo $this->lang->line('download_excel'); ?>
                                    </button>
                                </div>
                            </div>

                            <div class="report-card-body" style="padding: 0;">
                                <div class="table-responsive" id="div_print" style="padding: 18px 20px;">
                                    <h4 id="print_title" style="font-weight: 800; color: #0f172a; margin-bottom: 15px;">
                                        <?php echo $exam->name; ?>
                                        <?php 
                                        if (!empty($students)) {
                                            $first_student = reset($students);
                                            if (!empty($class_id) && !empty($first_student['class'])) {
                                                echo " - " . $this->lang->line('class') . ": " . $first_student['class'];
                                                if (!empty($section_id) && !empty($first_student['section'])) {
                                                    echo " (" . $this->lang->line('section') . ": " . $first_student['section'] . ")";
                                                }
                                            }
                                        }
                                        ?>
                                    </h4>

                                    <table class="table table-bordered report-modern-table vertical-middle" id="headerTable">
                                        <thead>
                                            <tr>
                                                <th rowspan="2" class="white-space-nowrap" style="min-width: 220px; text-align: left; padding-left: 14px;"><?php echo $this->lang->line('person_details') ? $this->lang->line('person_details') : 'PERSON DETAILS'; ?></th>
                                                <?php
                                                foreach ($subjects as $subject_key => $subject_value) {
                                                ?>
                                                    <th colspan="<?php echo count($exam_assessments); ?>" class="text-center" style="background: #f1f5f9 !important; color: #0f172a !important;">
                                                        <?php $subject_code = ($subject_value->subject_code == "") ? "" : " (" . $subject_value->subject_code . ")"; ?>
                                                        <?php echo $subject_value->subject_name . $subject_code; ?>
                                                    </th>
                                                <?php
                                                }
                                                ?>
                                                <th rowspan="2" class="white-space-nowrap text-center" style="min-width: 90px;"><?php echo $this->lang->line('total_marks'); ?></th>
                                                <th rowspan="2" class="white-space-nowrap text-center" style="min-width: 80px;"><?php echo $this->lang->line('percentage'); ?> (%)</th>
                                                <th rowspan="2" class="white-space-nowrap text-center" style="min-width: 70px;"><?php echo $this->lang->line('grade'); ?></th>
                                                <th rowspan="2" class="white-space-nowrap text-center" style="min-width: 70px;"><?php echo $this->lang->line('rank'); ?></th>
                                            </tr>
                                            <tr>
                                                <?php
                                                foreach ($subjects as $subject_key => $subject_value) {
                                                    foreach ($exam_assessments as $exam_assessment_key => $exam_assessment_value) {
                                                ?>
                                                        <th class="vertical-middle text-center" style="font-size: 10.5px !important; padding: 6px 4px !important;">
                                                            <?php $assessment_code = ($exam_assessment_value->code == "") ? "" : " (" . $exam_assessment_value->code . ")"; ?>
                                                            <?php echo $exam_assessment_value->name . $assessment_code; ?>
                                                            <br />
                                                            <span style="color: #64748b; font-size: 10px;">(<?php echo $this->lang->line('max'); ?>: <?php echo $exam_assessment_value->maximum_marks; ?>)</span>
                                                        </th>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </tr>
                                        </thead>
                                        <tbody id="report_table_tbody">
                                            <?php
                                            if (!empty($students)) {
                                                foreach ($students as $student_key => $student_value) {
                                                    $total_marks = 0;
                                                    $total_max_marks = 0;
                                                    $exam_percentage = 0;

                                                    $student_full_name = trim($student_value['firstname'] . " " . $student_value['middlename'] . " " . $student_value['lastname']);
                                                    $student_img_src = !empty($student_value['image']) ? base_url($student_value['image']) : base_url('uploads/student_images/no_image.png');
                                                    $class_section_str = (!empty($student_value['class'])) ? $student_value['class'] . (!empty($student_value['section']) ? ' (' . $student_value['section'] . ')' : '') : '';
                                            ?>
                                                    <tr class="report-student-row">
                                                        <td>
                                                            <div class="person-details-cell">
                                                                <img class="person-avatar" src="<?php echo $student_img_src; ?>" alt="Photo" onerror="this.src='<?php echo base_url('uploads/student_images/no_image.png'); ?>';">
                                                                <div>
                                                                    <div class="person-name-title" title="<?php echo html_escape($student_full_name); ?>">
                                                                        <?php echo html_escape($student_full_name); ?>
                                                                    </div>
                                                                    <div class="person-sub-row">
                                                                        <span class="person-adm-badge"><?php echo html_escape($student_value['admission_no']); ?></span>
                                                                        <?php if (!empty($class_section_str)) { ?>
                                                                            <span class="person-class-text"><?php echo html_escape($class_section_str); ?></span>
                                                                        <?php } ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <?php
                                                        foreach ($subjects as $subject_key => $subject_value) {
                                                            foreach ($exam_assessments as $exam_assessment_key => $exam_assessment_value) {
                                                        ?>
                                                                <td class="text-center">
                                                                    <?php
                                                                    $assessment_exists = find_subject_assessment_exists($subject_assessments, $subject_value->id, $exam_assessment_value->id);
                                                                    if ($assessment_exists) {
                                                                        $assessment_array = findAssessmentValue($subject_value->subject_id, $exam_assessment_value->id, $student_value);
                                                                        if ($assessment_array['is_absent']) {
                                                                            echo "<span class='text-danger' style='font-weight: 700;'>" . $this->lang->line('abs') . "</span>";
                                                                        } else {
                                                                            echo "<span style='font-weight: 600; color: #0f172a;'>" . $assessment_array['marks'] . "</span>";
                                                                        }
                                                                        if ($assessment_array['marks'] == "N/A") {
                                                                            $assessment_array['marks'] = 0;
                                                                        }
                                                                        $total_max_marks += $assessment_array['maximum_marks'];
                                                                        $total_marks += $assessment_array['marks'];
                                                                    } else {
                                                                        echo "<span style='color: #94a3b8;'>-</span>";
                                                                    }
                                                                    ?>
                                                                </td>
                                                        <?php
                                                            }
                                                        }

                                                        if ($total_max_marks > 0) {
                                                            $exam_percentage = getPercent($total_max_marks, $total_marks);
                                                        }
                                                        $grade_name = getGrade($exam->grades, $exam_percentage);
                                                        ?>
                                                        <td class="text-center"><span class="report-badge report-badge-total"><?php echo $total_marks . " / " . $total_max_marks; ?></span></td>
                                                        <td class="text-center"><span class="report-badge report-badge-percent"><?php echo $exam_percentage; ?>%</span></td>
                                                        <td class="text-center"><span class="report-badge report-badge-grade <?php echo getGradeBadgeClass($grade_name); ?>"><?php echo $grade_name; ?></span></td>
                                                        <td class="text-center"><span class="report-badge report-badge-rank">#<?php echo $student_value['rank']; ?></span></td>
                                                    </tr>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr style="font-weight: 700; background: #f8fafc; color: #1e293b;">
                                                <td class="text-right" style="font-weight: 800; font-size: 11.5px; text-transform: uppercase; letter-spacing: 0.5px;">
                                                    <i class="fa fa-calculator text-muted"></i> Class Average:
                                                </td>
                                                <?php
                                                foreach ($subjects as $subject_key => $subject_value) {
                                                    foreach ($exam_assessments as $exam_assessment_key => $exam_assessment_value) {
                                                        $as_stat = isset($col_stats[$subject_value->id][$exam_assessment_value->id]) ? $col_stats[$subject_value->id][$exam_assessment_value->id] : null;
                                                        if ($as_stat && $as_stat['count'] > 0) {
                                                            $col_avg = round($as_stat['sum'] / $as_stat['count'], 1);
                                                            echo "<td class='text-center' style='font-size: 11.5px; font-weight: 700; color: #114B5F;'>" . $col_avg . "<br><span style='font-size: 9.5px; color: #64748b;'>Top: " . $as_stat['highest'] . "</span></td>";
                                                        } else {
                                                            echo "<td class='text-center' style='color: #94a3b8;'>-</td>";
                                                        }
                                                    }
                                                }
                                                ?>
                                                <td class="text-center" colspan="4" style="font-weight: 800; color: #114B5F; font-size: 12px;">
                                                    Overall Avg: <?php echo $avg_class_percentage; ?>%
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                <?php
                    } else {
                ?>
                        <div class="report-card">
                            <div class="report-card-body">
                                <div class="alert alert-info mb0" style="background: #f0fdfa; border-color: #99f6e4; color: #0f766e; border-radius: 8px;">
                                    <i class="fa fa-info-circle"></i> <?php echo $this->lang->line('no_record_found'); ?>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </section>
</div>

<?php

function find_subject_assessment_exists($subject_assessments, $cbse_exam_timetable_id, $cbse_exam_assessment_type_id)
{

    if (!empty($subject_assessments)) {
        foreach ($subject_assessments as $key => $value) {
            if ($value->id == $cbse_exam_timetable_id) {
                if (!empty($value->subject_assessments)) {
                    foreach ($value->subject_assessments as $askey => $asvalue) {
                        if ($asvalue->cbse_exam_timetable_id == $cbse_exam_timetable_id  && $asvalue->cbse_exam_assessment_type_id == $cbse_exam_assessment_type_id) {
                            return true;
                            break;
                        }
                    }
                }
            }
        }
    }
    return false;
}

function findAssessmentValue($find_subject_id, $find_cbse_exam_assessment_type_id, $student_value)
{

    $return_array = [
        'maximum_marks' => "",
        'marks' => "",
        'note' => "",
        'is_absent' => "",
    ];

    if (array_key_exists('subjects', $student_value)) {

        if (array_key_exists($find_subject_id, $student_value['subjects'])) {
            $result_array = ($student_value['subjects'][$find_subject_id]['exam_assessments'][$find_cbse_exam_assessment_type_id]);

            $return_array = [
                'maximum_marks' => $result_array['maximum_marks'],
                'marks' => is_null($result_array['marks']) ? "N/A" : $result_array['marks'],
                'note' => $result_array['note'],
                'is_absent' => $result_array['is_absent'],
            ];
        }
    }

    return $return_array;
}

function getGrade($grade_array, $Percentage)
{

    if (!empty($grade_array)) {
        foreach ($grade_array as $grade_key => $grade_value) {

            if ($grade_value->minimum_percentage <= $Percentage) {
                return $grade_value->name;
                break;
            } elseif (($grade_value->minimum_percentage >= $Percentage && $grade_value->maximum_percentage <= $Percentage)) {

                return $grade_value->name;
                break;
            }
        }
    }
    return "-";
}

function getGradeBadgeClass($grade_name)
{
    $g = strtoupper(trim((string)$grade_name));
    if (strpos($g, 'A') !== false || strpos($g, 'O') !== false || strpos($g, 'DIST') !== false) {
        return 'report-badge-grade-a';
    } elseif (strpos($g, 'B') !== false || strpos($g, 'FIRST') !== false) {
        return 'report-badge-grade-b';
    } elseif (strpos($g, 'C') !== false) {
        return 'report-badge-grade-c';
    } elseif (strpos($g, 'D') !== false || strpos($g, 'E') !== false || strpos($g, 'FAIL') !== false || strpos($g, 'NEEDS') !== false) {
        return 'report-badge-grade-d';
    }
    return 'report-badge-grade-default';
}

?>

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

        // Client-side Instant Student Search
        $('#report_student_search').on('keyup', function () {
            var searchTerm = $(this).val().toLowerCase().trim();
            var visibleCount = 0;

            $('#report_table_tbody tr.report-student-row').each(function () {
                var rowText = $(this).text().toLowerCase();
                if (rowText.indexOf(searchTerm) > -1) {
                    $(this).show();
                    visibleCount++;
                } else {
                    $(this).hide();
                }
            });

            $('#filtered_students_count_badge').html('<i class="fa fa-users"></i> ' + visibleCount + ' <?php echo $this->lang->line("students"); ?>');
        });
    });

    function getSectionByClass(class_id, section_id) {
        if (class_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                beforeSend: function () {
                    $('#section_id').addClass('dropdownloading');
                },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
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
            $('#section_id').html('<option value=""><?php echo $this->lang->line('select'); ?></option>');
        }
    }

    function printDiv(tagid) {
        let hashid = "#" + tagid;
        var tagname = $(hashid).prop("tagName").toLowerCase();
        var attributes = "";
        var attrs = document.getElementById(tagid).attributes;
        $.each(attrs, function(i, elem) {
            attributes += " " + elem.name + " ='" + elem.value + "' ";
        })
        var divToPrint = $(hashid).html();
        var head = "<html><head>" + $("head").html() + "</head>";
        var allcontent = head + "<body  onload='window.print()' >" + "<" + tagname + attributes + ">" + divToPrint + "</" + tagname + ">" + "</body></html>";


        var allcontent = head + "<body>" + "<" + tagname + attributes + ">" + divToPrint + "</" + tagname + ">" + "</body></html>";
        var frame1 = $('<iframe />');
        frame1[0].name = "frame1";
        frame1.css({
            "position": "absolute",
            "top": "-1000000px"
        });
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
</script>