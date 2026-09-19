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

/* Stats Cards Grid */
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
.status-stat-icon.icon-teal { background: #f0fdfa; color: #114B5F; border: 1px solid #99f6e4; }
.status-stat-icon.icon-green { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
.status-stat-icon.icon-amber { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
.status-stat-icon.icon-purple { background: #fdf4ff; color: #9333ea; border: 1px solid #f0abfc; }
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

.report-quick-search {
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 6px 12px;
    font-size: 12.5px;
    width: 220px;
    outline: none;
    transition: all 0.15s ease;
}
.report-quick-search:focus {
    border-color: #114B5F;
    box-shadow: 0 0 0 3px rgba(17, 75, 95, 0.1);
}

/* Modern Tables */
.custom-table {
    width: 100%;
    border-collapse: collapse;
}
.custom-table thead th {
    background: #f8fafc;
    color: #475569;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #e2e8f0;
    padding: 10px 14px;
}
.custom-table tbody td {
    padding: 11px 14px;
    font-size: 12.5px;
    border-bottom: 1px solid #f1f5f9;
    color: #1e293b;
    vertical-align: middle;
}
.custom-table tbody tr:hover td {
    background: #f8fafc;
}

/* Badges & Tags */
.badge-roll {
    font-family: 'Courier New', monospace;
    font-size: 13px;
    font-weight: 700;
    background: #f0fdfa;
    color: #114B5F;
    border: 1px solid #99f6e4;
    padding: 3px 8px;
    border-radius: 6px;
    display: inline-block;
}
.badge-missing {
    font-size: 11px;
    font-weight: 600;
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
    padding: 3px 8px;
    border-radius: 6px;
    display: inline-block;
}
.badge-seat {
    font-size: 11px;
    font-weight: 600;
    background: #fdf4ff;
    color: #9333ea;
    border: 1px solid #f0abfc;
    padding: 2px 7px;
    border-radius: 5px;
    display: inline-block;
}

/* Progress Bar */
.custom-progress {
    height: 8px;
    border-radius: 4px;
    background: #f1f5f9;
    overflow: hidden;
    margin-top: 4px;
}
.custom-progress-bar {
    height: 100%;
    border-radius: 4px;
    transition: width 0.3s ease;
}
.bg-progress-green { background: #10b981; }
.bg-progress-amber { background: #f59e0b; }
.bg-progress-red { background: #ef4444; }

.action-link-btn {
    padding: 4px 10px;
    font-size: 11.5px;
    font-weight: 600;
    color: #114B5F;
    background: #f0fdfa;
    border: 1px solid #99f6e4;
    border-radius: 6px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.action-link-btn:hover {
    background: #114B5F;
    color: #ffffff;
    text-decoration: none;
}
</style>

<div class="content-wrapper" style="padding: 15px 20px;">
    <div class="report-page-header">
        <h1 class="report-page-title">
            <i class="fa fa-id-card-o"></i> Exam Roll Number & Admit Card Report
        </h1>
        <?php if (!empty($roll_report)) { ?>
            <div class="report-header-actions">
                <a href="<?php echo site_url('cbseexam/cbseadmitcardbulk/generate'); ?>" class="report-btn report-btn-primary">
                    <i class="fa fa-cogs"></i> Manage / Generate Roll Numbers
                </a>
            </div>
        <?php } ?>
    </div>

    <section class="content" style="padding: 0;">
        <?php $this->load->view('cbseexam/report/_cbsereport'); ?>

        <div class="row">
            <div class="col-md-12">
                <!-- Search & Filter Card -->
                <div class="report-card">
                    <div class="report-card-header">
                        <h3 class="report-card-title">
                            <i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?>
                        </h3>
                    </div>
                    <div class="report-card-body">
                        <form role="form" action="<?php echo site_url('cbseexam/report/rollnumber'); ?>" method="post" class="row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            
                            <div class="col-md-4 col-sm-6">
                                <div class="form-group mb0">
                                    <label class="report-form-label"><?php echo $this->lang->line('exam'); ?> <small class="text-danger">*</small></label>
                                    <select id="exam_id" name="exam_id" class="form-control report-select select2">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php if (!empty($exams)) {
                                            foreach ($exams as $exam) { ?>
                                                <option value="<?php echo $exam['id']; ?>" <?php echo (set_value('exam_id', isset($exam_id) ? $exam_id : '') == $exam['id']) ? 'selected="selected"' : ''; ?>>
                                                    <?php echo $exam['name']; ?>
                                                </option>
                                        <?php } } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('exam_id'); ?></span>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6">
                                <div class="form-group mb0">
                                    <label class="report-form-label"><?php echo $this->lang->line('class'); ?></label>
                                    <select id="class_id" name="class_id" class="form-control report-select">
                                        <option value=""><?php echo $this->lang->line('all'); ?></option>
                                        <?php if (!empty($classlist)) {
                                            foreach ($classlist as $class) { ?>
                                                <option value="<?php echo $class['id']; ?>" <?php echo (set_value('class_id', isset($class_id) ? $class_id : '') == $class['id']) ? 'selected="selected"' : ''; ?>>
                                                    <?php echo $class['class']; ?>
                                                </option>
                                        <?php } } ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-6">
                                <div class="form-group mb0">
                                    <label class="report-form-label"><?php echo $this->lang->line('section'); ?></label>
                                    <select id="section_id" name="section_id" class="form-control report-select">
                                        <option value=""><?php echo $this->lang->line('all'); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2 col-sm-6">
                                <div class="form-group mb0">
                                    <label class="report-form-label">&nbsp;</label>
                                    <button type="submit" name="search" value="search_filter" class="report-btn report-btn-primary" style="width: 100%; justify-content: center; height: 38px;">
                                        <i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <?php if (isset($roll_report) && !empty($roll_report)) { 
                    $summary = $roll_report['summary'];
                    $class_sections = $roll_report['class_sections'];
                    $students = $roll_report['students'];
                    $exam_detail = $roll_report['exam'];
                ?>
                    <!-- Summary Stats Metric Cards -->
                    <div class="status-stats-grid">
                        <div class="status-stat-card">
                            <div class="status-stat-icon icon-teal">
                                <i class="fa fa-users"></i>
                            </div>
                            <div>
                                <div class="status-stat-label">Total Enrolled Students</div>
                                <div class="status-stat-value"><?php echo $summary['total_students']; ?></div>
                                <div class="status-stat-subtext">Across <?php echo $summary['total_classes']; ?> Class Section(s)</div>
                            </div>
                        </div>

                        <div class="status-stat-card">
                            <div class="status-stat-icon icon-green">
                                <i class="fa fa-check-circle"></i>
                            </div>
                            <div>
                                <div class="status-stat-label">Roll Numbers Generated</div>
                                <div class="status-stat-value text-success"><?php echo $summary['generated_count']; ?></div>
                                <div class="status-stat-subtext"><?php echo $summary['generation_percent']; ?>% Generation Coverage</div>
                            </div>
                        </div>

                        <div class="status-stat-card">
                            <div class="status-stat-icon icon-amber">
                                <i class="fa fa-exclamation-triangle"></i>
                            </div>
                            <div>
                                <div class="status-stat-label">Missing / Pending</div>
                                <div class="status-stat-value text-warning"><?php echo $summary['missing_count']; ?></div>
                                <div class="status-stat-subtext">Students without Exam Roll No</div>
                            </div>
                        </div>

                        <div class="status-stat-card">
                            <div class="status-stat-icon icon-purple">
                                <i class="fa fa-pie-chart"></i>
                            </div>
                            <div>
                                <div class="status-stat-label">Overall Readiness</div>
                                <div class="status-stat-value"><?php echo $summary['generation_percent']; ?>%</div>
                                <div class="status-stat-subtext">Ready for Admit Cards & Seating</div>
                            </div>
                        </div>
                    </div>

                    <!-- Class-Wise Aggregated Summary Table -->
                    <div class="report-card">
                        <div class="report-card-header">
                            <div class="report-card-title">
                                <i class="fa fa-th-list"></i> Class & Section Breakdown - <?php echo $exam_detail['name']; ?>
                            </div>
                        </div>
                        <div class="report-card-body" style="padding: 0;">
                            <div class="table-responsive">
                                <table class="custom-table">
                                    <thead>
                                        <tr>
                                            <th>Class & Section</th>
                                            <th class="text-center">Total Students</th>
                                            <th class="text-center">Roll No Generated</th>
                                            <th class="text-center">Pending / Missing</th>
                                            <th style="width: 25%;">Generation Progress</th>
                                            <th class="text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($class_sections)) { 
                                            foreach ($class_sections as $cs) { 
                                                $pct = $cs['generation_percent'];
                                                $pcolor = ($pct >= 100) ? 'bg-progress-green' : (($pct > 0) ? 'bg-progress-amber' : 'bg-progress-red');
                                        ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo $cs['class_name'] . ' (' . $cs['section_name'] . ')'; ?></strong>
                                                </td>
                                                <td class="text-center font-weight-bold"><?php echo $cs['total_students']; ?></td>
                                                <td class="text-center">
                                                    <span class="text-success font-weight-bold"><?php echo $cs['generated_count']; ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($cs['missing_count'] > 0) { ?>
                                                        <span class="badge-missing"><?php echo $cs['missing_count']; ?> Missing</span>
                                                    <?php } else { ?>
                                                        <span class="text-muted"><i class="fa fa-check text-success"></i> All Done</span>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <div style="display: flex; justify-content: space-between; font-size: 11.5px; font-weight: 700; color: #475569; margin-bottom: 3px;">
                                                        <span><?php echo $pct; ?>%</span>
                                                        <span><?php echo $cs['generated_count']; ?> / <?php echo $cs['total_students']; ?></span>
                                                    </div>
                                                    <div class="custom-progress">
                                                        <div class="custom-progress-bar <?php echo $pcolor; ?>" style="width: <?php echo $pct; ?>%;"></div>
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <a href="<?php echo site_url('cbseexam/cbseadmitcardbulk/generate'); ?>" class="action-link-btn" title="Manage Roll Numbers">
                                                        <i class="fa fa-pencil-square-o"></i> Generate
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php } } else { ?>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted" style="padding: 24px;">No class records found for this exam.</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Student-Level Roll Number Directory -->
                    <div class="report-card">
                        <div class="report-card-header">
                            <div class="report-card-title">
                                <i class="fa fa-id-badge"></i> Student Roll Number Directory (<?php echo count($students); ?>)
                            </div>
                            <div class="report-header-actions">
                                <!-- Status Filter Pills -->
                                <div class="status-filter-pills">
                                    <button type="button" class="status-pill-btn active" data-filter="all">All (<?php echo count($students); ?>)</button>
                                    <button type="button" class="status-pill-btn" data-filter="generated"><i class="fa fa-check-circle text-success"></i> Generated (<?php echo $summary['generated_count']; ?>)</button>
                                    <button type="button" class="status-pill-btn" data-filter="missing"><i class="fa fa-exclamation-circle text-danger"></i> Missing (<?php echo $summary['missing_count']; ?>)</button>
                                </div>

                                <!-- Quick Search Input -->
                                <input type="text" id="student_roll_search" class="report-quick-search" placeholder="Search student, adm no, roll no...">

                                <button type="button" class="report-btn report-btn-print" onclick="printDiv('div_print_rollnumber')">
                                    <i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?>
                                </button>
                                <button type="button" class="report-btn report-btn-excel" onclick="fnExcelReportRollNumber()">
                                    <i class="fa fa-file-excel-o"></i> <?php echo $this->lang->line('download_excel'); ?>
                                </button>
                            </div>
                        </div>

                        <div class="report-card-body" id="div_print_rollnumber" style="padding: 0;">
                            <div class="table-responsive">
                                <table class="custom-table" id="student_roll_table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Admission No</th>
                                            <th>Student Name</th>
                                            <th>Class (Section)</th>
                                            <th>Father's Name</th>
                                            <th>Profile Roll No</th>
                                            <th>Exam Roll No</th>
                                            <th>Allocated Room & Seat</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($students)) {
                                            $counter = 1;
                                            foreach ($students as $stu) {
                                                $has_roll = (!empty($stu['exam_roll_no']) && $stu['exam_roll_no'] != '0');
                                                $row_filter_class = $has_roll ? 'filter-generated' : 'filter-missing';
                                                $student_full_name = trim($stu['firstname'] . ' ' . $stu['middlename'] . ' ' . $stu['lastname']);
                                        ?>
                                            <tr class="student-roll-row <?php echo $row_filter_class; ?>" 
                                                data-name="<?php echo strtolower($student_full_name); ?>"
                                                data-admno="<?php echo strtolower($stu['admission_no']); ?>"
                                                data-rollno="<?php echo strtolower($stu['exam_roll_no']); ?>"
                                                data-class="<?php echo strtolower($stu['class_name'] . ' ' . $stu['section_name']); ?>">
                                                <td><?php echo $counter++; ?></td>
                                                <td><strong><?php echo $stu['admission_no']; ?></strong></td>
                                                <td>
                                                    <strong><?php echo $student_full_name; ?></strong>
                                                    <?php if (!empty($stu['mobileno'])) { ?>
                                                        <div style="font-size: 11px; color: #64748b;"><i class="fa fa-phone"></i> <?php echo $stu['mobileno']; ?></div>
                                                    <?php } ?>
                                                </td>
                                                <td><?php echo $stu['class_name'] . ' (' . $stu['section_name'] . ')'; ?></td>
                                                <td><?php echo $stu['father_name']; ?></td>
                                                <td><?php echo !empty($stu['profile_roll_no']) ? $stu['profile_roll_no'] : '-'; ?></td>
                                                <td>
                                                    <?php if ($has_roll) { ?>
                                                        <span class="badge-roll"><?php echo $stu['exam_roll_no']; ?></span>
                                                    <?php } else { ?>
                                                        <span class="badge-missing">Not Generated</span>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($stu['allocated_room'])) { ?>
                                                        <span><i class="fa fa-building-o text-muted"></i> Room <?php echo $stu['allocated_room']; ?></span>
                                                        <?php if (!empty($stu['allocated_seat'])) { ?>
                                                            <span class="badge-seat"><?php echo $stu['allocated_seat']; ?></span>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <span class="text-muted" style="font-size: 11.5px;">-</span>
                                                    <?php } ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($has_roll) { ?>
                                                        <span class="text-success"><i class="fa fa-check-circle"></i> Ready</span>
                                                    <?php } else { ?>
                                                        <span class="text-danger"><i class="fa fa-times-circle"></i> Missing</span>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } } else { ?>
                                            <tr>
                                                <td colspan="9" class="text-center text-muted" style="padding: 24px;">No students found for the selected filter.</td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Hidden Table for Clean Excel Export -->
                        <div style="display:none;">
                            <table id="roll_number_excel_table">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th>Admission No</th>
                                        <th>Student Name</th>
                                        <th>Class</th>
                                        <th>Section</th>
                                        <th>Father Name</th>
                                        <th>Profile Roll No</th>
                                        <th>Exam Roll No</th>
                                        <th>Allocated Room</th>
                                        <th>Allocated Seat</th>
                                        <th>Roll No Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($students)) {
                                        $cnt = 1;
                                        foreach ($students as $stu) {
                                            $has_roll = (!empty($stu['exam_roll_no']) && $stu['exam_roll_no'] != '0');
                                            $student_full_name = trim($stu['firstname'] . ' ' . $stu['middlename'] . ' ' . $stu['lastname']);
                                    ?>
                                        <tr>
                                            <td><?php echo $cnt++; ?></td>
                                            <td><?php echo $stu['admission_no']; ?></td>
                                            <td><?php echo $student_full_name; ?></td>
                                            <td><?php echo $stu['class_name']; ?></td>
                                            <td><?php echo $stu['section_name']; ?></td>
                                            <td><?php echo $stu['father_name']; ?></td>
                                            <td><?php echo $stu['profile_roll_no']; ?></td>
                                            <td><?php echo $stu['exam_roll_no']; ?></td>
                                            <td><?php echo $stu['allocated_room']; ?></td>
                                            <td><?php echo $stu['allocated_seat']; ?></td>
                                            <td><?php echo $has_roll ? 'Generated' : 'Missing'; ?></td>
                                        </tr>
                                    <?php } } ?>
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
    $(document).ready(function() {
        // Section dropdown dynamic loader
        var class_id_post = '<?php echo set_value("class_id", isset($class_id) ? $class_id : 0); ?>';
        var section_id_post = '<?php echo set_value("section_id", isset($section_id) ? $section_id : 0); ?>';

        function getSectionByClass(class_id, section_id) {
            if (class_id !== "") {
                $('#section_id').html("");
                var base_url = '<?php echo base_url() ?>';
                var div_data = '<option value=""><?php echo $this->lang->line("all"); ?></option>';
                $.ajax({
                    type: "GET",
                    url: base_url + "sections/getByClass",
                    data: { 'class_id': class_id },
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

        if (class_id_post !== "" && class_id_post !== "0") {
            getSectionByClass(class_id_post, section_id_post);
        }

        $(document).on('change', '#class_id', function() {
            var class_id = $(this).val();
            $('#section_id').html("");
            getSectionByClass(class_id, 0);
        });

        // Instant filter & search for student table
        $('.status-pill-btn').on('click', function() {
            $('.status-pill-btn').removeClass('active');
            $(this).addClass('active');
            applyStudentRollFilter();
        });

        $('#student_roll_search').on('keyup', function() {
            applyStudentRollFilter();
        });

        function applyStudentRollFilter() {
            var activeFilter = $('.status-pill-btn.active').data('filter');
            var search = $('#student_roll_search').val().toLowerCase().trim();

            $('.student-roll-row').each(function() {
                var row = $(this);
                var isGenerated = row.hasClass('filter-generated');
                var isMissing = row.hasClass('filter-missing');

                var name = row.data('name') || '';
                var admno = row.data('admno') || '';
                var rollno = row.data('rollno') || '';
                var cname = row.data('class') || '';

                var statusMatch = true;
                if (activeFilter === 'generated' && !isGenerated) statusMatch = false;
                if (activeFilter === 'missing' && !isMissing) statusMatch = false;

                var textMatch = false;
                if (search === '' || name.indexOf(search) > -1 || admno.indexOf(search) > -1 || rollno.indexOf(search) > -1 || cname.indexOf(search) > -1) {
                    textMatch = true;
                }

                if (statusMatch && textMatch) {
                    row.show();
                } else {
                    row.hide();
                }
            });
        }
    });

    function printDiv(tagid) {
        let hashid = "#" + tagid;
        var divToPrint = $(hashid).html();
        var head = "<html><head>" + $("head").html() + "<style>.action-link-btn, .status-filter-pills, .report-quick-search { display: none !important; }</style></head>";
        var allcontent = head + "<body onload='window.print()'>" + divToPrint + "</body></html>";
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

    function fnExcelReportRollNumber() {
        var tab_text = "<table border='1px'><tr bgcolor='#f8fafc'>";
        var tab = document.getElementById('roll_number_excel_table');
        for (var j = 0; j < tab.rows.length; j++) {
            tab_text += tab.rows[j].innerHTML + "</tr>";
        }
        tab_text += "</table>";
        var sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
        return sa;
    }
</script>
