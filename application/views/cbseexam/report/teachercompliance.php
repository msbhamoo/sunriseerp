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
}
.report-btn-excel {
    background: #ecfdf5 !important;
    border-color: #a7f3d0 !important;
    color: #059669 !important;
}
.report-btn-excel:hover {
    background: #d1fae5 !important;
    border-color: #6ee7b7 !important;
}
.report-btn-print {
    background: #eff6ff !important;
    border-color: #bfdbfe !important;
    color: #1d4ed8 !important;
}
.report-btn-print:hover {
    background: #dbeafe !important;
    border-color: #93c5fd !important;
}

/* Stats Cards */
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
.report-select, .report-input {
    border: 1px solid #cbd5e1 !important;
    border-radius: 8px !important;
    padding: 8px 12px !important;
    height: 38px !important;
    font-size: 13px !important;
    color: #0f172a !important;
    width: 100%;
}

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
    height: 34px;
    width: 220px;
}

/* Teacher Card */
.teacher-status-card {
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    background: #ffffff;
    margin-bottom: 16px;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
    overflow: hidden;
}
.teacher-status-header {
    padding: 12px 18px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
}
.teacher-name-title {
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 8px;
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
.bg-progress-green { background-color: #10b981; }
.bg-progress-amber { background-color: #f59e0b; }
.bg-progress-red { background-color: #ef4444; }

.badge-status {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11.5px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.badge-status-completed { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
.badge-status-partial { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
.badge-status-pending { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

.class-subjects-table {
    width: 100%;
    border-collapse: collapse;
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

.action-link-btn {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 700;
    text-decoration: none !important;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.action-link-btn-enter { background: #f0fdfa; color: #0f766e; border: 1px solid #99f6e4; }
.action-link-btn-enter:hover { background: #ccfbf1; }
.action-link-btn-edit { background: #f8fafc; color: #475569; border: 1px solid #cbd5e1; }
</style>

<div class="content-wrapper" style="padding: 15px 20px;">
    <div class="report-page-header">
        <h1 class="report-page-title">
            <i class="fa fa-users"></i> Teacher Marks Submission Compliance
        </h1>
    </div>

    <section class="content" style="padding: 0;">
        <?php $this->load->view('cbseexam/report/_cbsereport'); ?>
        <div class="row">
            <div class="col-md-12">
                <!-- Search Box -->
                <div class="report-card">
                    <div class="report-card-header">
                        <h3 class="report-card-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="report-card-body">
                        <form role="form" action="<?php echo site_url('cbseexam/report/teachercompliance') ?>" method="post" class="row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-md-6 col-sm-6">
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
                            <div class="col-md-6 col-sm-6">
                                <div class="form-group mb0">
                                    <label class="report-form-label"><?php echo $this->lang->line('teacher'); ?></label>
                                    <select id="staff_id" name="staff_id" class="form-control report-select select2">
                                        <option value=""><?php echo $this->lang->line('all'); ?></option>
                                        <?php
                                        if (isset($stafflist) && !empty($stafflist)) {
                                            foreach ($stafflist as $stf) {
                                                $code = !empty($stf['employee_id']) ? " (" . $stf['employee_id'] . ")" : "";
                                        ?>
                                                <option value="<?php echo $stf['id'] ?>" <?php
                                                    if (set_value('staff_id', isset($staff_id) ? $staff_id : '') == $stf['id']) {
                                                        echo "selected=selected";
                                                    }
                                                ?>><?php echo $stf['name'] . " " . $stf['surname'] . $code; ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
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
                if (isset($compliance_report) && !empty($compliance_report['exam'])) {
                    $summary = $compliance_report['summary'];
                    $teachers = $compliance_report['teachers'];
                    $exam_detail = $compliance_report['exam'];
                ?>
                    <!-- Summary Stats -->
                    <div class="status-stats-grid">
                        <div class="status-stat-card">
                            <div class="status-stat-icon icon-teal">
                                <i class="fa fa-users"></i>
                            </div>
                            <div>
                                <div class="status-stat-label">Assigned Teachers</div>
                                <div class="status-stat-value"><?php echo $summary['total_teachers']; ?></div>
                                <div class="status-stat-subtext">Teaching exam classes</div>
                            </div>
                        </div>

                        <div class="status-stat-card">
                            <div class="status-stat-icon icon-green">
                                <i class="fa fa-check-circle"></i>
                            </div>
                            <div>
                                <div class="status-stat-label">Fully Submitted</div>
                                <div class="status-stat-value text-success"><?php echo $summary['completed_teachers']; ?> / <?php echo $summary['total_teachers']; ?></div>
                                <div class="status-stat-subtext">100% marks entered</div>
                            </div>
                        </div>

                        <div class="status-stat-card">
                            <div class="status-stat-icon icon-amber">
                                <i class="fa fa-clock-o"></i>
                            </div>
                            <div>
                                <div class="status-stat-label">Pending Teachers</div>
                                <div class="status-stat-value text-warning"><?php echo $summary['pending_teachers']; ?></div>
                                <div class="status-stat-subtext">Marks submission due</div>
                            </div>
                        </div>

                        <div class="status-stat-card">
                            <div class="status-stat-icon icon-purple">
                                <i class="fa fa-percent"></i>
                            </div>
                            <div>
                                <div class="status-stat-label">Overall Compliance</div>
                                <div class="status-stat-value"><?php echo $summary['overall_compliance_percent']; ?>%</div>
                                <div class="status-stat-subtext"><?php echo $summary['grand_entered']; ?> / <?php echo $summary['grand_expected']; ?> entered</div>
                            </div>
                        </div>
                    </div>

                    <!-- Main Report Grid -->
                    <div class="report-card">
                        <div class="report-card-header">
                            <div class="report-card-title">
                                <i class="fa fa-list"></i> <?php echo $exam_detail['name']; ?> - Teacher Compliance
                            </div>
                            <div class="report-header-actions">
                                <div class="status-filter-pills">
                                    <button type="button" class="status-pill-btn active" data-filter="all">All Teachers (<?php echo count($teachers); ?>)</button>
                                    <button type="button" class="status-pill-btn" data-filter="pending"><i class="fa fa-exclamation-circle text-warning"></i> Pending (<?php echo $summary['pending_teachers']; ?>)</button>
                                    <button type="button" class="status-pill-btn" data-filter="completed"><i class="fa fa-check-circle text-success"></i> Done (<?php echo $summary['completed_teachers']; ?>)</button>
                                </div>
                                <input type="text" id="teacher_search" class="report-quick-search" placeholder="Search teacher or subject...">
                                <button type="button" class="report-btn report-btn-print" onclick="printDiv('div_print_teacher')"><i class="fa fa-print"></i> <?php echo $this->lang->line('print'); ?></button>
                                <button type="button" class="report-btn report-btn-excel" onclick="fnExcelReportTeacher()"><i class="fa fa-file-excel-o"></i> <?php echo $this->lang->line('download_excel'); ?></button>
                            </div>
                        </div>

                        <div class="report-card-body" id="div_print_teacher" style="padding: 20px;">
                            <div id="teacher_list_container">
                                <?php
                                if (!empty($teachers)) {
                                    foreach ($teachers as $tch) {
                                        $filter_class = ($tch['status'] == 'completed') ? 'filter-completed' : 'filter-pending';
                                        $p_color = ($tch['status'] == 'completed') ? 'bg-progress-green' : (($tch['completion_percent'] > 0) ? 'bg-progress-amber' : 'bg-progress-red');
                                ?>
                                        <div class="teacher-status-card <?php echo $filter_class; ?>" data-tname="<?php echo strtolower($tch['staff_name'] . ' ' . $tch['employee_id']); ?>">
                                            <div class="teacher-status-header">
                                                <div>
                                                    <div class="teacher-name-title">
                                                        <i class="fa fa-user-circle text-primary"></i>
                                                        <span><?php echo $tch['staff_name']; ?></span>
                                                        <?php if (!empty($tch['employee_id'])) { ?>
                                                            <span style="font-size: 11.5px; color: #64748b;">(Emp ID: <?php echo $tch['employee_id']; ?>)</span>
                                                        <?php } ?>
                                                        <?php if (!empty($tch['contact_no'])) { ?>
                                                            <span style="font-size: 11.5px; color: #64748b; margin-left: 8px;"><i class="fa fa-phone"></i> <?php echo $tch['contact_no']; ?></span>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                                <div style="display: flex; align-items: center; gap: 14px; flex-wrap: wrap;">
                                                    <div style="display: flex; align-items: center; gap: 10px; min-width: 180px;">
                                                        <div class="custom-progress">
                                                            <div class="custom-progress-bar <?php echo $p_color; ?>" style="width: <?php echo $tch['completion_percent']; ?>%;"></div>
                                                        </div>
                                                        <span style="font-size: 12px; font-weight: 800; color: #0f172a; min-width: 40px;"><?php echo $tch['completion_percent']; ?>%</span>
                                                    </div>

                                                    <?php if ($tch['status'] == 'completed') { ?>
                                                        <span class="badge-status badge-status-completed"><i class="fa fa-check"></i> 100% Submitted</span>
                                                    <?php } else { ?>
                                                        <span class="badge-status badge-status-partial"><i class="fa fa-clock-o"></i> <?php echo $tch['missing_entries']; ?> Entries Pending</span>
                                                    <?php } ?>
                                                </div>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table class-subjects-table">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 30%;">Class & Subject</th>
                                                            <th style="width: 20%;" class="text-center">Students</th>
                                                            <th style="width: 20%;" class="text-center">Entered / Total</th>
                                                            <th style="width: 15%;" class="text-center">Progress</th>
                                                            <th style="width: 15%;" class="text-right">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        foreach ($tch['assigned_subjects'] as $asub) {
                                                            $sub_p_color = ($asub['status'] == 'completed') ? 'bg-progress-green' : (($asub['completion_percent'] > 0) ? 'bg-progress-amber' : 'bg-progress-red');
                                                        ?>
                                                            <tr class="subject-row" data-subname="<?php echo strtolower($asub['subject_name'] . ' ' . $asub['class_name']); ?>">
                                                                <td>
                                                                    <strong><?php echo $asub['class_name']; ?></strong> - <?php echo $asub['subject_name']; ?>
                                                                    <?php if (!empty($asub['subject_code'])) { ?><span style="color:#64748b; font-size:11px;">(<?php echo $asub['subject_code']; ?>)</span><?php } ?>
                                                                </td>
                                                                <td class="text-center"><?php echo $asub['students_count']; ?></td>
                                                                <td class="text-center">
                                                                    <strong><?php echo $asub['entered_entries']; ?></strong> / <?php echo $asub['expected_entries']; ?>
                                                                </td>
                                                                <td class="text-center">
                                                                    <div style="display: flex; align-items: center; gap: 6px; justify-content: center;">
                                                                        <div class="custom-progress" style="width: 60px;">
                                                                            <div class="custom-progress-bar <?php echo $sub_p_color; ?>" style="width: <?php echo $asub['completion_percent']; ?>%;"></div>
                                                                        </div>
                                                                        <span style="font-size: 11.5px; font-weight: 700; color: #334155; min-width: 32px;"><?php echo $asub['completion_percent']; ?>%</span>
                                                                    </div>
                                                                </td>
                                                                <td class="text-right">
                                                                    <div style="display: inline-flex; align-items: center; gap: 6px; justify-content: flex-end;">
                                                                        <?php if ($asub['status'] == 'completed') { ?>
                                                                            <span class="badge-status badge-status-completed"><i class="fa fa-check"></i> Done</span>
                                                                        <?php } else { ?>
                                                                            <span class="badge-status badge-status-pending"><?php echo $asub['missing_entries']; ?> Left</span>
                                                                        <?php } ?>
                                                                        <a href="<?php echo site_url('cbseexam/marks/index/' . $exam_detail['id'] . '/' . $asub['timetable_id']); ?>" class="action-link-btn <?php echo ($asub['status'] == 'completed') ? 'action-link-btn-edit' : 'action-link-btn-enter'; ?>">
                                                                            <i class="fa fa-pencil-square-o"></i> <?php echo ($asub['status'] == 'completed') ? 'Edit' : 'Enter'; ?>
                                                                        </a>
                                                                    </div>
                                                                </td>
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
                                    <div class="alert alert-info">No teacher assignments found for this exam.</div>
                                <?php } ?>
                            </div>

                            <!-- Hidden Export Table -->
                            <table id="teacher_excel_table" style="display: none;">
                                <thead>
                                    <tr>
                                        <th>Teacher Name</th>
                                        <th>Employee ID</th>
                                        <th>Contact</th>
                                        <th>Class</th>
                                        <th>Subject</th>
                                        <th>Expected</th>
                                        <th>Entered</th>
                                        <th>Missing</th>
                                        <th>Completion %</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($teachers)) {
                                        foreach ($teachers as $tch) {
                                            foreach ($tch['assigned_subjects'] as $asub) {
                                    ?>
                                                <tr>
                                                    <td><?php echo $tch['staff_name']; ?></td>
                                                    <td><?php echo $tch['employee_id']; ?></td>
                                                    <td><?php echo $tch['contact_no']; ?></td>
                                                    <td><?php echo $asub['class_name']; ?></td>
                                                    <td><?php echo $asub['subject_name']; ?></td>
                                                    <td><?php echo $asub['expected_entries']; ?></td>
                                                    <td><?php echo $asub['entered_entries']; ?></td>
                                                    <td><?php echo $asub['missing_entries']; ?></td>
                                                    <td><?php echo $asub['completion_percent']; ?>%</td>
                                                    <td><?php echo strtoupper($asub['status']); ?></td>
                                                </tr>
                                    <?php
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
    $(document).ready(function() {
        $('.status-pill-btn').on('click', function() {
            $('.status-pill-btn').removeClass('active');
            $(this).addClass('active');
            applyTeacherFilter();
        });

        $('#teacher_search').on('keyup', function() {
            applyTeacherFilter();
        });

        function applyTeacherFilter() {
            var activeFilter = $('.status-pill-btn.active').data('filter');
            var search = $('#teacher_search').val().toLowerCase().trim();

            $('.teacher-status-card').each(function() {
                var card = $(this);
                var tname = card.data('tname') || '';
                var isCompleted = card.hasClass('filter-completed');
                var isPending = card.hasClass('filter-pending');

                var statusMatch = true;
                if (activeFilter === 'completed' && !isCompleted) statusMatch = false;
                if (activeFilter === 'pending' && !isPending) statusMatch = false;

                var textMatch = false;
                if (search === '' || tname.indexOf(search) > -1) {
                    textMatch = true;
                } else {
                    card.find('.subject-row').each(function() {
                        var sname = $(this).data('subname') || '';
                        if (sname.indexOf(search) > -1) textMatch = true;
                    });
                }

                if (statusMatch && textMatch) card.show(); else card.hide();
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
        setTimeout(function() { window.frames["frame1"].focus(); window.frames["frame1"].print(); frame1.remove(); }, 500);
    }

    function fnExcelReportTeacher() {
        var tab_text = "<table border='1px'><tr bgcolor='#f8fafc'>";
        var tab = document.getElementById('teacher_excel_table');
        for (var j = 0; j < tab.rows.length; j++) { tab_text += tab.rows[j].innerHTML + "</tr>"; }
        tab_text += "</table>";
        var sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
        return sa;
    }
</script>
