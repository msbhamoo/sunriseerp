<?php
$total_evaluations = count($recent_evaluations);
$verified_count = 0;
$pending_count = 0;
$today_str = date('Y-m-d');
$today_count = 0;

foreach ($recent_evaluations as $rev) {
    if (isset($rev['status']) && $rev['status'] === 'verified') {
        $verified_count++;
    } else {
        $pending_count++;
    }
    if (isset($rev['created_at']) && date('Y-m-d', strtotime($rev['created_at'])) === $today_str) {
        $today_count++;
    }
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.css">
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/contrib/auto-render.min.js"></script>
<!-- Fabric.js for Interactive Red-Ink Handwriting Annotation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.eval-studio-wrapper {
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}

/* Annotation Toolbar Styles */
.annotation-toolbar {
    background: #1e293b;
    padding: 8px 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 6px;
    border-bottom: 1px solid #334155;
    flex-wrap: wrap;
}
.annotation-btn-group {
    display: inline-flex;
    gap: 4px;
    align-items: center;
}
.annot-btn {
    background: #334155;
    color: #f1f5f9;
    border: 1px solid #475569;
    border-radius: 6px;
    padding: 5px 10px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.15s ease;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}
.annot-btn:hover {
    background: #475569;
    color: #ffffff;
}
.annot-btn.active {
    background: #dc2626;
    border-color: #ef4444;
    color: #ffffff;
    box-shadow: 0 0 10px rgba(220, 38, 38, 0.5);
}

.canvas-annotation-wrapper {
    position: relative;
    overflow: auto;
    max-height: 68vh;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    background: #0f172a;
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 10px;
}
.canvas-container {
    margin: 0 auto !important;
    box-shadow: 0 10px 30px rgba(0,0,0,0.6);
    border-radius: 4px;
}

/* Modern KPI Summary Stat Cards */
.modern-stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
    margin-bottom: 20px;
}
.modern-stat-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    transition: all 0.2s ease;
}
.modern-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.08);
}
.modern-stat-info .stat-label {
    font-size: 12px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}
.modern-stat-info .stat-value {
    font-size: 24px;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.1;
}
.modern-stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}

/* Slide-in Right Side Drawer */
.modern-drawer-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(4px);
    z-index: 1050;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.modern-drawer-overlay.is-active {
    opacity: 1;
    visibility: visible;
}
.modern-drawer-panel {
    position: fixed;
    top: 0;
    right: -650px;
    width: 620px;
    max-width: 95vw;
    height: 100vh;
    max-height: 100vh;
    background: #ffffff;
    z-index: 1055;
    box-shadow: -10px 0 25px -5px rgba(0, 0, 0, 0.15);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transition: right 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}
.modern-drawer-panel.is-open {
    right: 0;
}
.modern-drawer-header {
    background: #ffffff;
    color: #0f172a;
    padding: 16px 20px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-shrink: 0;
}
.modern-drawer-title {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 8px;
}
.modern-drawer-close {
    background: transparent;
    border: none;
    color: #64748b;
    font-size: 24px;
    cursor: pointer;
    line-height: 1;
    opacity: 0.8;
    transition: all 0.15s ease;
}
.modern-drawer-close:hover {
    color: #0f172a;
    opacity: 1;
}
.modern-drawer-body {
    padding: 22px 24px 40px 24px;
    overflow-y: auto !important;
    overflow-x: hidden;
    flex: 1 1 auto;
    background: #ffffff;
    -webkit-overflow-scrolling: touch;
}
.modern-drawer-footer {
    padding: 14px 24px;
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    flex-shrink: 0;
    z-index: 10;
}

/* Evaluation Specific Badges & Cards */
.eval-badge-confidence {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.conf-green { background: #dcfce7; color: #15803d; border: 1px solid #86efac; }
.conf-yellow { background: #fef9c3; color: #a16207; border: 1px solid #fde047; }
.conf-red { background: #fee2e2; color: #b91c1c; border: 1px solid #fca5a5; }

.page-thumb-preview {
    width: 85px;
    height: 115px;
    object-fit: cover;
    border-radius: 10px;
    border: 2px solid #e2e8f0;
    cursor: pointer;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
}
.page-thumb-preview:hover, .page-thumb-preview.active {
    border-color: #8b5cf6;
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 8px 20px -4px rgba(139, 92, 246, 0.35);
}

.zoom-image-container {
    position: relative;
    overflow: auto;
    max-height: 72vh;
    border: 1px solid #cbd5e1;
    border-radius: 12px;
    background: #0f172a;
    text-align: center;
    padding: 12px;
    box-shadow: inset 0 2px 8px rgba(0,0,0,0.4);
}
.zoom-image-container img {
    max-width: 100%;
    transition: transform 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 6px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.5);
}

.step-row {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 10px 14px;
    margin-bottom: 8px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 13px;
    transition: all 0.15s ease;
}
.step-row:hover { background: #f1f5f9; }
.step-row.correct { border-left: 4px solid #22c55e; }
.step-row.partial { border-left: 4px solid #eab308; }
.step-row.incorrect { border-left: 4px solid #ef4444; }

.q-eval-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px 18px;
    margin-bottom: 16px;
    transition: all 0.25s ease;
}
.q-eval-card:hover {
    border-color: #cbd5e1;
    box-shadow: 0 10px 25px -5px rgba(0,0,0,0.06);
}
.q-eval-card.needs-review {
    border-left: 4px solid #ef4444;
    background: #fffafa;
}

.spinner-eval {
    display: inline-block;
    width: 36px;
    height: 36px;
    border: 3px solid rgba(139, 92, 246, 0.2);
    border-radius: 50%;
    border-top-color: #8b5cf6;
    animation: spin 1s ease-in-out infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }
</style>

<div class="content-wrapper eval-studio-wrapper" style="min-height: 480px;">
    <section class="content-header">
        <h1><i class="fa fa-pencil-square-o" style="color: #8b5cf6;"></i> <?php echo $this->lang->line('ai_answer_evaluator'); ?></h1>
    </section>

    <section class="content">
        <!-- Modern KPI Stat Grid Cards (Just like Material Register) -->
        <div class="modern-stat-grid">
            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Total Evaluated Copies</div>
                    <div class="stat-value"><?php echo $total_evaluations; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(139, 92, 246, 0.12); color: #8b5cf6;">
                    <i class="fa fa-files-o"></i>
                </div>
            </div>

            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Verified & Published</div>
                    <div class="stat-value text-success" style="color: #059669;"><?php echo $verified_count; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
                    <i class="fa fa-check-circle"></i>
                </div>
            </div>

            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Draft / Pending Review</div>
                    <div class="stat-value text-warning" style="color: #d97706;"><?php echo $pending_count; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b;">
                    <i class="fa fa-hourglass-half"></i>
                </div>
            </div>

            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Evaluated Today</div>
                    <div class="stat-value" style="color: #0284c7;"><?php echo $today_count; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(14, 165, 233, 0.12); color: #0284c7;">
                    <i class="fa fa-calendar-check-o"></i>
                </div>
            </div>
        </div>

        <!-- Main Card with Header Button to Open Slide-in Drawer -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary" style="border-radius: 12px; overflow: hidden; border-top: 3px solid #8b5cf6;">
                    <div class="box-header ptbnull" style="padding: 14px 18px;">
                        <h3 class="box-title titlefix">
                            <i class="fa fa-list text-muted" style="margin-right: 6px;"></i> Student Answer Sheet Evaluations Register
                        </h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-primary btn-sm" id="btn-open-evaluator-drawer" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); border: none; font-weight: 700; padding: 6px 14px; border-radius: 6px; box-shadow: 0 2px 8px rgba(139, 92, 246, 0.3);">
                                <i class="fa fa-camera"></i> Evaluate New Student Copy
                            </button>
                        </div>
                    </div>

                    <div class="box-body" style="padding: 15px 18px;">
                        <div class="table-responsive" style="overflow-x: auto; overflow-y: visible; padding-bottom: 60px; margin-bottom: -60px;">
                            <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                    <tr style="background: #f8fafc;">
                                        <th style="width: 40px;">#</th>
                                        <th>Student Name</th>
                                        <th>Class & Section</th>
                                        <th>Question Paper</th>
                                        <th>Score Awarded</th>
                                        <th>AI Confidence</th>
                                        <th>Status</th>
                                        <th>Evaluation Date</th>
                                        <th class="text-right noExport" style="width: 100px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($recent_evaluations)) {
                                        $cnt = 1;
                                        foreach ($recent_evaluations as $rev) {
                                            $conf = isset($rev['average_confidence']) ? intval($rev['average_confidence']) : 85;
                                            $conf_class = $conf >= 85 ? 'conf-green' : ($conf >= 70 ? 'conf-yellow' : 'conf-red');
                                            $is_verified = (isset($rev['status']) && $rev['status'] === 'verified');
                                            ?>
                                            <tr id="row_eval_<?php echo $rev['id']; ?>">
                                                <td><?php echo $cnt++; ?></td>
                                                <td>
                                                    <strong style="color: #0f172a;"><?php echo htmlspecialchars($rev['firstname'] . ' ' . $rev['lastname']); ?></strong>
                                                    <?php if (!empty($rev['roll_no'])) { ?>
                                                        <br><small class="text-muted"><i class="fa fa-id-badge"></i> Roll #<?php echo $rev['roll_no']; ?></small>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <span class="badge bg-purple" style="font-size: 11px;"><?php echo $rev['class_name']; ?> (<?php echo $rev['section_name']; ?>)</span>
                                                </td>
                                                <td>
                                                    <div style="font-weight: 600; color: #1e293b;"><?php echo htmlspecialchars($rev['paper_title']); ?></div>
                                                    <small class="text-muted"><?php echo $rev['subject_name']; ?></small>
                                                </td>
                                                <td>
                                                    <strong style="font-size: 14px; color: #7c3aed;"><?php echo $rev['total_obtained_marks']; ?></strong> / <?php echo $rev['total_max_marks']; ?> M
                                                </td>
                                                <td>
                                                    <span class="eval-badge-confidence <?php echo $conf_class; ?>"><?php echo $conf; ?>%</span>
                                                </td>
                                                <td>
                                                    <?php if ($is_verified) { ?>
                                                        <span class="label label-success"><i class="fa fa-check"></i> Verified</span>
                                                    <?php } else { ?>
                                                        <span class="label label-warning"><i class="fa fa-clock-o"></i> Pending Review</span>
                                                    <?php } ?>
                                                </td>
                                                <td class="white-space-nowrap">
                                                    <i class="fa fa-calendar-o text-muted" style="margin-right: 4px;"></i> <?php echo date('d M Y, h:i A', strtotime($rev['created_at'])); ?>
                                                </td>
                                                <td class="text-right white-space-nowrap">
                                                    <div class="dropdown" style="display: inline-block;">
                                                        <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" style="border-radius: 6px; padding: 4px 8px; color: #475569; background: #f8fafc; border-color: #e2e8f0;">
                                                            <i class="fa fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-right" style="border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; min-width: 150px; padding: 4px 0; font-size: 13px;">
                                                            <li>
                                                                <a href="javascript:void(0);" onclick="openCockpitModal(<?php echo $rev['id']; ?>)">
                                                                    <i class="fa fa-eye text-info" style="width: 18px;"></i> Open Evaluation Cockpit
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0);" onclick="printEvaluationReport(<?php echo $rev['id']; ?>)">
                                                                    <i class="fa fa-print text-primary" style="width: 18px;"></i> Print Marksheet
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                    <?php } } else { ?>
                                        <tr>
                                            <td colspan="9" class="text-center text-muted" style="padding: 40px 10px;">
                                                <i class="fa fa-pencil-square-o" style="font-size: 32px; color: #cbd5e1; margin-bottom: 8px;"></i>
                                                <div style="font-size: 14px; font-weight: 600; color: #64748b;">No answer sheets evaluated yet.</div>
                                                <div style="font-size: 12px; color: #94a3b8; margin-top: 4px;">Click "Evaluate New Student Copy" above to upload and grade handwritten answer sheets.</div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Slide-in Right Side Drawer for Uploading & Evaluating (Material Register Style) -->
<div id="eval-drawer-overlay" class="modern-drawer-overlay"></div>
<div id="eval-drawer-panel" class="modern-drawer-panel">
    <div class="modern-drawer-header">
        <h4 class="modern-drawer-title"><i class="fa fa-camera"></i> Evaluate Student Copy</h4>
        <button type="button" class="modern-drawer-close" id="btn-close-eval-drawer">&times;</button>
    </div>

    <div class="modern-drawer-body">
        <form id="formEvaluateSubmission" enctype="multipart/form-data">
            <!-- Paper Source Mode Switcher: Saved System Paper OR Custom/External Paper -->
            <div class="form-group">
                <label style="font-weight: 700; color: #1e293b; margin-bottom: 6px;">1. Question Paper & Answer Key Source <small class="req">*</small></label>
                <div style="display: flex; gap: 8px;">
                    <button type="button" class="btn btn-default btn-sm active" id="btnModeSavedPaper" onclick="switchPaperMode('saved')" style="flex: 1; font-weight: 600; border-radius: 6px; padding: 7px;">
                        <i class="fa fa-database text-primary"></i> Saved System Paper
                    </button>
                    <button type="button" class="btn btn-default btn-sm" id="btnModeCustomPaper" onclick="switchPaperMode('custom')" style="flex: 1; font-weight: 600; border-radius: 6px; padding: 7px;">
                        <i class="fa fa-cloud-upload text-success"></i> Upload Custom / Physical Paper
                    </button>
                </div>
                <input type="hidden" id="eval_paper_mode" name="paper_mode" value="saved">
            </div>

            <!-- Mode A: Choose from Previously Generated Papers -->
            <div id="boxModeSavedPaper" class="form-group">
                <label>Select Saved CBSE Paper <small class="req">*</small></label>
                <select id="eval_paper_id" name="paper_id" class="form-control">
                    <option value="">-- Choose Generated CBSE Paper --</option>
                    <?php if (!empty($saved_papers)) {
                        foreach ($saved_papers as $sp) { ?>
                            <option value="<?php echo $sp['id']; ?>">
                                <?php echo htmlspecialchars($sp['paper_title']); ?> (<?php echo $sp['class_name']; ?> - <?php echo $sp['subject_name']; ?> | <?php echo $sp['total_marks']; ?>M)
                            </option>
                    <?php } } ?>
                </select>
                <small class="text-muted" style="font-size: 11px;">Automatically loads all question texts, sub-questions, and official step marking schemes.</small>
            </div>

            <!-- Mode B: Upload External / Physical Paper & Solution Scheme -->
            <div id="boxModeCustomPaper" style="display: none; background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 16px; margin-bottom: 18px; box-shadow: 0 2px 6px rgba(0,0,0,0.03);">
                <div class="form-group" style="margin-bottom: 12px;">
                    <label style="font-size: 12px; font-weight: 700; color: #1e293b;">Paper Title / Exam Name <small class="req">*</small></label>
                    <input type="text" id="eval_custom_paper_title" name="custom_paper_title" class="form-control input-sm" placeholder="e.g. Class 10 Pre-Board Physics Exam 2026">
                </div>

                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group" style="margin-bottom: 12px;">
                            <label style="font-size: 12px; font-weight: 700; color: #1e293b;">Select Subject <small class="req">*</small></label>
                            <select id="eval_custom_subject" name="custom_subject" class="form-control input-sm">
                                <option value="">-- Choose Subject --</option>
                                <?php if (!empty($subjectlist)) {
                                    foreach ($subjectlist as $sub) { ?>
                                        <option value="<?php echo htmlspecialchars($sub['name']); ?>"><?php echo htmlspecialchars($sub['name']); ?></option>
                                <?php } } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group" style="margin-bottom: 12px;">
                            <label style="font-size: 12px; font-weight: 700; color: #1e293b;">Max Marks <small class="req">*</small></label>
                            <input type="number" id="eval_custom_max_marks" name="custom_max_marks" class="form-control input-sm" placeholder="80" value="80">
                        </div>
                    </div>
                </div>

                <!-- Stylish Upload Box: Question Paper -->
                <div class="form-group" style="margin-bottom: 12px;">
                    <label style="font-size: 12px; font-weight: 700; color: #1e293b;"><i class="fa fa-file-text-o text-primary"></i> Upload Question Paper (Photo / Scan)</label>
                    <div style="border: 2px dashed #cbd5e1; border-radius: 8px; padding: 12px 16px; text-align: center; background: #f8fafc; cursor: pointer; transition: all 0.2s ease;" onclick="$('#eval_custom_paper_files').click()">
                        <i class="fa fa-cloud-upload" style="font-size: 24px; color: #6366f1; margin-bottom: 4px;"></i>
                        <div id="display_qp_files" style="font-weight: 600; color: #334155; font-size: 12px;">Click to select Question Paper scan / PDF</div>
                        <div style="font-size: 10px; color: #64748b;">Supports multi-page JPG, PNG, PDF</div>
                    </div>
                    <input type="file" id="eval_custom_paper_files" name="custom_paper_files[]" multiple accept="image/*,.pdf" style="display: none;" onchange="updateFileNameDisplay(this, 'display_qp_files', 'Question Paper selected')">
                </div>

                <!-- Stylish Upload Box: Solution Key -->
                <div class="form-group" style="margin-bottom: 0;">
                    <label style="font-size: 12px; font-weight: 700; color: #1e293b;"><i class="fa fa-check-circle text-success"></i> Upload Answer Key / Solution Scheme (Optional)</label>
                    <div style="border: 2px dashed #cbd5e1; border-radius: 8px; padding: 12px 16px; text-align: center; background: #f8fafc; cursor: pointer; transition: all 0.2s ease;" onclick="$('#eval_custom_solution_files').click()">
                        <i class="fa fa-file-text" style="font-size: 24px; color: #10b981; margin-bottom: 4px;"></i>
                        <div id="display_sol_files" style="font-weight: 600; color: #334155; font-size: 12px;">Click to select Solution Key scan / PDF</div>
                        <div style="font-size: 10px; color: #64748b;">Or type custom marking rules below</div>
                    </div>
                    <input type="file" id="eval_custom_solution_files" name="custom_solution_files[]" multiple accept="image/*,.pdf" style="display: none;" onchange="updateFileNameDisplay(this, 'display_sol_files', 'Solution Key selected')">
                </div>
            </div>

            <!-- 2. Class & Section -->
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Class <small class="req">*</small></label>
                        <select id="eval_class_id" name="class_id" class="form-control" onchange="onClassSelected(this.value)" required>
                            <option value="">-- Select Class --</option>
                            <?php if (!empty($classlist)) {
                                foreach ($classlist as $cls) { ?>
                                    <option value="<?php echo $cls['id']; ?>"><?php echo $cls['class']; ?></option>
                            <?php } } ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Section</label>
                        <select id="eval_section_id" name="section_id" class="form-control" onchange="loadStudentsList()">
                            <option value="">All Sections</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- 3. Select Student -->
            <div class="form-group">
                <label>2. Select Student <small class="req">*</small></label>
                <select id="eval_student_id" name="student_id" class="form-control" required>
                    <option value="">-- Choose Student --</option>
                </select>
            </div>

            <!-- 4. Upload Answer Sheet Photos / Scan -->
            <div class="form-group">
                <label>3. Upload Student's Handwritten Copy <small class="req">*</small></label>
                <div style="border: 2px dashed #cbd5e1; border-radius: 8px; padding: 18px; text-align: center; background: #f8fafc; cursor: pointer; transition: all 0.2s ease;" onclick="$('#eval_answer_sheets').click()">
                    <i class="fa fa-cloud-upload" style="font-size: 32px; color: #8b5cf6; margin-bottom: 6px;"></i>
                    <div style="font-weight: 700; color: #334155; font-size: 13px;">Click or Drag Photos of Student Copy</div>
                    <div style="font-size: 11px; color: #64748b; margin-top: 2px;">JPG, PNG images (Multi-page upload supported)</div>
                </div>
                <input type="file" id="eval_answer_sheets" name="answer_sheets[]" multiple accept="image/*" style="display: none;" onchange="previewUploadedPages(this)">
            </div>

            <!-- Uploaded Page Thumbnails Carousel -->
            <div id="pageThumbnailsBox" style="display: none; margin-bottom: 15px;">
                <label style="font-size: 11px; color: #475569; margin-bottom: 4px;"><i class="fa fa-file-image-o"></i> Uploaded Pages Preview:</label>
                <div id="pageThumbsContainer" style="display: flex; gap: 8px; overflow-x: auto; padding: 6px 0;"></div>
            </div>

            <!-- Optional: Custom Solution / Notes -->
            <div class="form-group">
                <label>Custom Teacher Instructions / Notes (Optional)</label>
                <textarea id="eval_custom_solution" name="custom_solution" class="form-control" rows="2" placeholder="e.g. Award full 3 marks for alternate method; accept 2.5 or 5/2."></textarea>
            </div>

            <input type="hidden" id="eval_api_key" name="api_key" value="">
        </form>
    </div>

    <div class="modern-drawer-footer">
        <button type="button" class="btn btn-default" id="btn-cancel-eval-drawer">Cancel</button>
        <button type="button" id="btnRunEvaluation" class="btn btn-primary" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); border: none; font-weight: 700; padding: 8px 18px;" onclick="startEvaluationProcess()">
            <i class="fa fa-bolt"></i> Run AI Vision Evaluation
        </button>
    </div>
</div>

<!-- Modal: Split-Screen Evaluation Cockpit (Full Interactivity) -->
<div class="modal fade" id="modalCockpit" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document" style="width: 95%; max-width: 1400px;">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
            <div class="modal-header" style="background: #ffffff; color: #0f172a; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; padding: 14px 20px;">
                <div>
                    <h4 class="modal-title" style="font-size: 16px; font-weight: 700; color: #0f172a; display: inline-block;">
                        <i class="fa fa-pencil-square-o text-primary"></i> <span id="disp_student_name">Student Evaluation Cockpit</span>
                    </h4>
                    <span id="disp_avg_conf" class="eval-badge-confidence conf-green" style="margin-left: 10px;">90% Confidence</span>
                </div>
                <div style="display: flex; align-items: center; gap: 16px;">
                    <div style="text-align: right;">
                        <span style="font-size: 11px; font-weight: 700; color: #64748b; letter-spacing: 0.5px;">TOTAL MARKS AWARDED</span><br>
                        <strong id="disp_total_score" style="font-size: 20px; color: #0f172a; font-weight: 800;">0.0 / 0</strong>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" style="color: #64748b; font-size: 24px; opacity: 0.8;">&times;</button>
                </div>
            </div>

            <div class="modal-body" style="padding: 18px; background: #f8fafc; max-height: 80vh; overflow-y: auto;">
                <div class="row">
                    <!-- Left Column: Interactive Red-Ink Annotation & Student Answer Sheet Viewer -->
                    <div class="col-md-6">
                        <div class="box box-solid" style="border-radius: 8px; border: 1px solid #e2e8f0; overflow: hidden; background: #f8fafc;">
                            <!-- Annotation & Zoom Toolbar -->
                            <div class="annotation-toolbar" style="background: #f1f5f9; border-bottom: 1px solid #e2e8f0;">
                                <div class="annotation-btn-group">
                                    <button type="button" class="annot-btn active" id="btnToolSelect" onclick="setAnnotationTool('select')" title="Pan / Select" style="background: #ffffff; color: #334155; border-color: #cbd5e1;"><i class="fa fa-mouse-pointer"></i> Select</button>
                                    <button type="button" class="annot-btn" id="btnToolPen" onclick="setAnnotationTool('pen')" title="Red Ink Freehand Pen" style="background: #ffffff; color: #334155; border-color: #cbd5e1;"><i class="fa fa-pencil" style="color: #ef4444;"></i> Red Pen</button>
                                    <button type="button" class="annot-btn" id="btnToolTick" onclick="stampRedTick()" title="Stamp Red Tick Mark" style="background: #ffffff; color: #334155; border-color: #cbd5e1;"><i class="fa fa-check" style="color: #22c55e;"></i> Tick (✓)</button>
                                    <button type="button" class="annot-btn" id="btnToolCross" onclick="stampRedCross()" title="Stamp Red Cross Mark" style="background: #ffffff; color: #334155; border-color: #cbd5e1;"><i class="fa fa-times" style="color: #ef4444;"></i> Cross (✗)</button>
                                    <button type="button" class="annot-btn" id="btnToolUnderline" onclick="setAnnotationTool('line')" title="Draw Red Underline" style="background: #ffffff; color: #334155; border-color: #cbd5e1;"><i class="fa fa-minus" style="color: #ef4444;"></i> Underline</button>
                                    <button type="button" class="annot-btn" id="btnToolNote" onclick="addStickyNote()" title="Add Sticky Note / Comment" style="background: #ffffff; color: #334155; border-color: #cbd5e1;"><i class="fa fa-sticky-note-o" style="color: #d97706;"></i> Note</button>
                                </div>
                                <div class="annotation-btn-group">
                                    <button type="button" class="annot-btn" onclick="deleteSelectedAnnotation()" title="Delete Selected Object" style="background: #ffffff; color: #ef4444; border-color: #cbd5e1;"><i class="fa fa-trash"></i></button>
                                    <button type="button" class="annot-btn" onclick="clearAllAnnotations()" title="Clear All Drawings" style="background: #ffffff; color: #334155; border-color: #cbd5e1;"><i class="fa fa-eraser"></i> Clear</button>
                                    <button type="button" class="annot-btn" onclick="zoomAnnotationCanvas(1.2)" title="Zoom In" style="background: #ffffff; color: #334155; border-color: #cbd5e1;"><i class="fa fa-search-plus"></i></button>
                                    <button type="button" class="annot-btn" onclick="zoomAnnotationCanvas(0.8)" title="Zoom Out" style="background: #ffffff; color: #334155; border-color: #cbd5e1;"><i class="fa fa-search-minus"></i></button>
                                    <button type="button" class="annot-btn" onclick="resetAnnotationCanvasZoom()" title="Fit Screen" style="background: #ffffff; color: #334155; border-color: #cbd5e1;"><i class="fa fa-arrows-alt"></i></button>
                                </div>
                            </div>

                            <!-- Canvas Container with Interactive Drawing -->
                            <div class="canvas-annotation-wrapper" id="canvasWrapper" style="background: #e2e8f0;">
                                <canvas id="annotCanvas"></canvas>
                            </div>

                            <!-- Page Thumbnails Strip -->
                            <div id="cockpitThumbnails" style="display: flex; gap: 6px; padding: 10px; background: #f8fafc; overflow-x: auto; border-top: 1px solid #e2e8f0;"></div>
                        </div>
                    </div>

                    <!-- Right Column: Step-by-Step AI Evaluation & Overrides -->
                    <div class="col-md-6">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                            <strong style="font-size: 13px; color: #1e293b;"><i class="fa fa-check-square-o text-primary"></i> Step-by-Step Marking Breakdown</strong>
                            <div style="display: flex; gap: 6px;">
                                <button type="button" class="btn btn-default btn-xs" onclick="saveTeacherVerifiedMarks('draft')">
                                    <i class="fa fa-save"></i> Save Draft
                                </button>
                                <button type="button" class="btn btn-success btn-xs" onclick="saveTeacherVerifiedMarks('verified')">
                                    <i class="fa fa-check-circle"></i> Verify & Publish Marks
                                </button>
                            </div>
                        </div>

                        <div id="questionsEvaluationList" style="max-height: 68vh; overflow-y: auto; padding-right: 4px;"></div>
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                <span class="text-muted" style="font-size: 12px;"><i class="fa fa-info-circle"></i> Teachers can freely adjust step marks before publishing final marks.</span>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
let currentEvaluationId = null;
let currentEvalData = null;
let uploadedImageUrls = [];
let currentZoom = 1.0;

$(document).ready(function() {
    $('#btn-open-evaluator-drawer').on('click', function() {
        openEvalDrawer();
    });
    $('#btn-close-eval-drawer, #btn-cancel-eval-drawer, #eval-drawer-overlay').on('click', function() {
        closeEvalDrawer();
    });
});

function openEvalDrawer() {
    $('#eval-drawer-overlay').addClass('is-active');
    $('#eval-drawer-panel').addClass('is-open');
    $('body').css('overflow', 'hidden');
}

function closeEvalDrawer() {
    $('#eval-drawer-panel').removeClass('is-open');
    $('#eval-drawer-overlay').removeClass('is-active');
    $('body').css('overflow', '');
}

function onClassSelected(classId) {
    if (!classId) return;
    $.ajax({
        url: '<?php echo base_url(); ?>admin/aiexamevaluator/get_sections_ajax',
        type: 'POST',
        dataType: 'json',
        data: { class_id: classId },
        success: function(res) {
            let html = '<option value="">All Sections</option>';
            if (res.status === 'success' && res.data) {
                res.data.forEach(sec => {
                    html += `<option value="${sec.section_id}">${sec.section}</option>`;
                });
            }
            $('#eval_section_id').html(html);
            loadStudentsList();
        }
    });
}

function loadStudentsList() {
    const classId = $('#eval_class_id').val();
    const sectionId = $('#eval_section_id').val();
    if (!classId) return;

    $.ajax({
        url: '<?php echo base_url(); ?>admin/aiexamevaluator/get_students_ajax',
        type: 'POST',
        dataType: 'json',
        data: { class_id: classId, section_id: sectionId },
        success: function(res) {
            let html = '<option value="">-- Choose Student --</option>';
            if (res.status === 'success' && res.data) {
                res.data.forEach(st => {
                    let roll = st.roll_no ? ` [Roll: ${st.roll_no}]` : '';
                    let adm = st.admission_no ? ` (Adm: ${st.admission_no})` : '';
                    html += `<option value="${st.id}">${st.firstname} ${st.lastname}${roll}${adm}</option>`;
                });
            }
            $('#eval_student_id').html(html);
        }
    });
}

function previewUploadedPages(input) {
    if (input.files && input.files.length > 0) {
        let html = '';
        for (let i = 0; i < input.files.length; i++) {
            let file = input.files[i];
            let url = URL.createObjectURL(file);
            html += `
                <div style="text-align: center; flex-shrink: 0;">
                    <img src="${url}" class="page-thumb-preview ${i === 0 ? 'active' : ''}" alt="Page ${i+1}">
                    <div style="font-size: 10px; color: #64748b; margin-top: 2px;">Page ${i+1}</div>
                </div>
            `;
        }
        $('#pageThumbsContainer').html(html);
        $('#pageThumbnailsBox').show();
    }
}

function updateFileNameDisplay(input, targetDisplayId, prefix) {
    if (input.files && input.files.length > 0) {
        let count = input.files.length;
        let names = Array.from(input.files).map(f => f.name).join(', ');
        if (count === 1) {
            $('#' + targetDisplayId).html('<strong style="color: #10b981;"><i class="fa fa-check"></i> ' + input.files[0].name + '</strong>');
        } else {
            $('#' + targetDisplayId).html('<strong style="color: #10b981;"><i class="fa fa-check"></i> ' + count + ' files selected</strong> (' + names.substring(0, 30) + '...)');
        }
    }
}

function switchPaperMode(mode) {
    $('#eval_paper_mode').val(mode);
    if (mode === 'saved') {
        $('#btnModeSavedPaper').addClass('active').css('background', '#f1f5f9');
        $('#btnModeCustomPaper').removeClass('active').css('background', '#ffffff');
        $('#boxModeSavedPaper').show();
        $('#boxModeCustomPaper').hide();
    } else {
        $('#btnModeCustomPaper').addClass('active').css('background', '#f1f5f9');
        $('#btnModeSavedPaper').removeClass('active').css('background', '#ffffff');
        $('#boxModeSavedPaper').hide();
        $('#boxModeCustomPaper').show();
    }
}

function startEvaluationProcess() {
    const form = document.getElementById('formEvaluateSubmission');
    const formData = new FormData(form);
    const paperMode = $('#eval_paper_mode').val();

    if (paperMode === 'saved' && !$('#eval_paper_id').val()) {
        alert('Please select a saved Question Paper.');
        return;
    }

    if (paperMode === 'custom' && !$('#eval_custom_paper_title').val()) {
        alert('Please enter a Paper Title / Exam Name for the uploaded question paper.');
        return;
    }

    if (!$('#eval_student_id').val()) {
        alert('Please select a Student.');
        return;
    }

    if ($('#eval_answer_sheets')[0].files.length === 0) {
        alert('Please upload at least 1 image of the student answer sheet.');
        return;
    }

    const btn = $('#btnRunEvaluation');
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Analyzing Copy with Vision AI...');

    $.ajax({
        url: '<?php echo base_url(); ?>admin/aiexamevaluator/evaluate_answer_sheets_ajax',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(res) {
            btn.prop('disabled', false).html('<i class="fa fa-bolt"></i> Run AI Vision Evaluation');

            if (res.status === 'success' && res.data) {
                closeEvalDrawer();
                currentEvaluationId = res.evaluation_id;
                currentEvalData = res.data;
                uploadedImageUrls = res.uploaded_urls || [];
                
                renderCockpit(res.data, res.student_info, uploadedImageUrls);
                $('#modalCockpit').modal('show');
                setTimeout(function() {
                    window.location.reload(true);
                }, 4000);
            } else {
                alert('Evaluation Error: ' + (res.message || 'Unknown error occurred.'));
            }
        },
        error: function(xhr, status, err) {
            btn.prop('disabled', false).html('<i class="fa fa-bolt"></i> Run AI Vision Evaluation');
            alert('Request Failed: ' + err);
        }
    });
}

function openCockpitModal(evalId) {
    $.ajax({
        url: '<?php echo base_url(); ?>admin/aiexamevaluator/get_evaluation_ajax',
        type: 'POST',
        dataType: 'json',
        data: { evaluation_id: evalId },
        success: function(res) {
            if (res.status === 'success' && res.data) {
                currentEvaluationId = evalId;
                currentEvalData = res.data;
                uploadedImageUrls = res.uploaded_urls || [];
                renderCockpit(res.data, res.student_info, uploadedImageUrls);
                $('#modalCockpit').modal('show');
            } else {
                alert('Error: ' + res.message);
            }
        }
    });
}

function renderCockpit(data, studentInfo, pages) {
    $('#disp_student_name').text(studentInfo.name || data.student_name || 'Student');
    $('#disp_total_score').text(`${data.total_obtained_marks} / ${data.total_max_marks} M`);
    $('#disp_avg_conf').text(`${data.average_confidence || 90}% Confidence`);

    // Setup Original Image Viewer
    if (pages && pages.length > 0) {
        let firstImgUrl = '<?php echo base_url(); ?>' + pages[0];
        $('#activeCopyImage').attr('src', firstImgUrl);
        resetZoom();

        let thumbHtml = '';
        pages.forEach((p, idx) => {
            let fullUrl = '<?php echo base_url(); ?>' + p;
            thumbHtml += `<img src="${fullUrl}" class="page-thumb-preview ${idx === 0 ? 'active' : ''}" style="width: 45px; height: 60px;" onclick="switchActiveImage('${fullUrl}', this)" title="Page ${idx+1}">`;
        });
        $('#cockpitThumbnails').html(thumbHtml);
    }

    // Render Evaluated Question Cards
    let qHtml = '';
    if (data.evaluated_questions && data.evaluated_questions.length > 0) {
        data.evaluated_questions.forEach((q, qIdx) => {
            let confClass = q.confidence_score >= 85 ? 'conf-green' : (q.confidence_score >= 70 ? 'conf-yellow' : 'conf-red');
            let isLowConf = q.confidence_score < 70;

            qHtml += `
                <div class="q-eval-card ${isLowConf ? 'needs-review' : ''}" id="q_card_${qIdx}">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <span style="font-weight: 700; color: #1e293b; font-size: 14px;">Q${q.q_no} (${q.section_name || ''})</span>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span class="eval-badge-confidence ${confClass}">${q.confidence_score}% Confidence</span>
                            <div class="input-group" style="width: 100px;">
                                <input type="number" step="0.5" min="0" max="${q.max_marks}" class="form-control input-sm text-center" style="font-weight: bold; color: #8b5cf6;" value="${q.obtained_marks}" onchange="updateQuestionMark(${qIdx}, this.value)">
                                <span class="input-group-addon" style="font-size: 11px; padding: 2px 6px;">/ ${q.max_marks}</span>
                            </div>
                        </div>
                    </div>

                    ${isLowConf ? `<div style="font-size: 11px; color: #dc2626; font-weight: 600; margin-bottom: 6px;"><i class="fa fa-exclamation-triangle"></i> Ambiguous handwriting / Please verify marks manually.</div>` : ''}

                    <div style="background: #f8fafc; border-radius: 4px; padding: 6px 10px; font-size: 12px; margin-bottom: 8px;">
                        <strong>Transcribed Student Answer:</strong>
                        <div style="font-style: italic; color: #334155; margin-top: 2px;">${q.student_answer_transcription || 'No text transcribed.'}</div>
                    </div>

                    <!-- Step-by-Step Marking Breakdown -->
                    <div style="margin-bottom: 6px;">
                        <div style="font-size: 11px; font-weight: 600; color: #64748b; margin-bottom: 4px;">Step Marking Allocation:</div>
            `;

            if (q.step_marking_breakdown && q.step_marking_breakdown.length > 0) {
                q.step_marking_breakdown.forEach(step => {
                    let stClass = step.step_status === 'correct' ? 'correct' : (step.step_status === 'partial' ? 'partial' : 'incorrect');
                    let icon = step.step_status === 'correct' ? 'fa-check text-green' : (step.step_status === 'partial' ? 'fa-adjust text-yellow' : 'fa-times text-red');
                    qHtml += `
                        <div class="step-row ${stClass}">
                            <span><i class="fa ${icon}"></i> ${step.step_description}</span>
                            <span style="font-weight: 600;">${step.marks_awarded} / ${step.marks_allocated} M</span>
                        </div>
                    `;
                });
            }

            qHtml += `
                    </div>
                    ${q.examiner_feedback ? `<div style="font-size: 11px; color: #0284c7; margin-top: 4px;"><i class="fa fa-lightbulb-o"></i> <em>Tip: ${q.examiner_feedback}</em></div>` : ''}
                </div>
            `;
        });
    }

    $('#questionsEvaluationList').html(qHtml);

    if (window.renderMathInElement) {
        renderMathInElement(document.getElementById('questionsEvaluationList'), {
            delimiters: [
                {left: '$$', right: '$$', display: true},
                {left: '$', right: '$', display: false}
            ],
            throwOnError: false
        });
    }
}

// --- Interactive Fabric.js Red-Ink Handwriting Annotation Engine ---
let fabricCanvas = null;
let currentTool = 'select';
let activePageUrl = null;
let annotationsCache = {}; // Cache annotations per image URL: { [url]: fabricJSON }

function initFabricCanvas(imageUrl) {
    if (!imageUrl) return;
    activePageUrl = imageUrl;

    if (fabricCanvas) {
        fabricCanvas.dispose();
        fabricCanvas = null;
    }

    const canvasEl = document.getElementById('annotCanvas');
    if (!canvasEl) return;

    fabricCanvas = new fabric.Canvas('annotCanvas', {
        isDrawingMode: false,
        selection: true
    });

    fabric.Image.fromURL(imageUrl, function(img) {
        const maxWidth = $('#canvasWrapper').width() ? ($('#canvasWrapper').width() - 30) : 600;
        const scaleFactor = Math.min(1.0, maxWidth / img.width);

        const canvasWidth = img.width * scaleFactor;
        const canvasHeight = img.height * scaleFactor;

        fabricCanvas.setWidth(canvasWidth);
        fabricCanvas.setHeight(canvasHeight);

        img.set({
            scaleX: scaleFactor,
            scaleY: scaleFactor,
            originX: 'left',
            originY: 'top',
            selectable: false,
            evented: false
        });

        fabricCanvas.setBackgroundImage(img, fabricCanvas.renderAll.bind(fabricCanvas));

        // Restore cached annotations for this specific page if any
        if (annotationsCache[imageUrl]) {
            fabricCanvas.loadFromDatalessJSON(annotationsCache[imageUrl], function() {
                fabricCanvas.setBackgroundImage(img, fabricCanvas.renderAll.bind(fabricCanvas));
                fabricCanvas.renderAll();
            });
        }

        setupPenBrush();
        setAnnotationTool(currentTool);
    }, { crossOrigin: 'anonymous' });

    // Auto-save changes to cache on modification
    fabricCanvas.on('object:added', saveCanvasToCache);
    fabricCanvas.on('object:modified', saveCanvasToCache);
    fabricCanvas.on('object:removed', saveCanvasToCache);
}

function saveCanvasToCache() {
    if (fabricCanvas && activePageUrl) {
        annotationsCache[activePageUrl] = fabricCanvas.toDatalessJSON();
    }
}

function setupPenBrush() {
    if (!fabricCanvas) return;
    fabricCanvas.freeDrawingBrush = new fabric.PencilBrush(fabricCanvas);
    fabricCanvas.freeDrawingBrush.color = '#ef4444'; // Red Ink
    fabricCanvas.freeDrawingBrush.width = 3;
}

function setAnnotationTool(tool) {
    currentTool = tool;
    $('.annot-btn').removeClass('active');

    if (!fabricCanvas) return;

    if (tool === 'select') {
        $('#btnToolSelect').addClass('active');
        fabricCanvas.isDrawingMode = false;
        fabricCanvas.selection = true;
    } else if (tool === 'pen') {
        $('#btnToolPen').addClass('active');
        fabricCanvas.isDrawingMode = true;
        setupPenBrush();
    } else if (tool === 'line') {
        $('#btnToolUnderline').addClass('active');
        fabricCanvas.isDrawingMode = false;
        drawRedUnderline();
    }
}

function stampRedTick() {
    if (!fabricCanvas) return;
    setAnnotationTool('select');
    const tick = new fabric.Text('✓', {
        left: fabricCanvas.width / 2 - 20,
        top: fabricCanvas.height / 2 - 20,
        fontSize: 38,
        fontWeight: 'bold',
        fill: '#16a34a', // Vivid Green Tick
        stroke: '#15803d',
        strokeWidth: 1,
        shadow: new fabric.Shadow({ color: 'rgba(22,163,74,0.3)', blur: 6 })
    });
    fabricCanvas.add(tick);
    fabricCanvas.setActiveObject(tick);
    fabricCanvas.renderAll();
    saveCanvasToCache();
}

function stampRedCross() {
    if (!fabricCanvas) return;
    setAnnotationTool('select');
    const cross = new fabric.Text('✗', {
        left: fabricCanvas.width / 2 - 20,
        top: fabricCanvas.height / 2 - 20,
        fontSize: 38,
        fontWeight: 'bold',
        fill: '#dc2626', // Vivid Red Cross
        stroke: '#b91c1c',
        strokeWidth: 1,
        shadow: new fabric.Shadow({ color: 'rgba(220,38,38,0.3)', blur: 6 })
    });
    fabricCanvas.add(cross);
    fabricCanvas.setActiveObject(cross);
    fabricCanvas.renderAll();
    saveCanvasToCache();
}

function drawRedUnderline() {
    if (!fabricCanvas) return;
    const line = new fabric.Line([50, 100, 200, 100], {
        stroke: '#dc2626', // Red Underline
        strokeWidth: 3,
        strokeLineCap: 'round',
        left: fabricCanvas.width / 2 - 75,
        top: fabricCanvas.height / 2
    });
    fabricCanvas.add(line);
    fabricCanvas.setActiveObject(line);
    fabricCanvas.renderAll();
    saveCanvasToCache();
}

function addStickyNote() {
    if (!fabricCanvas) return;
    setAnnotationTool('select');
    const noteText = prompt('Enter Teacher Remark / Sticky Note:', 'Review calculation step');
    if (!noteText) return;

    const rect = new fabric.Rect({
        width: 170,
        height: 55,
        fill: '#fef08a', // Yellow Sticky Note
        stroke: '#facc15',
        strokeWidth: 1.5,
        rx: 6,
        ry: 6,
        shadow: new fabric.Shadow({ color: 'rgba(0,0,0,0.15)', blur: 8, offsetY: 3 })
    });

    const text = new fabric.Textbox(noteText, {
        width: 155,
        fontSize: 12,
        fill: '#713f12',
        fontWeight: '600',
        originX: 'center',
        originY: 'center',
        top: 27,
        left: 85
    });

    const group = new fabric.Group([rect, text], {
        left: fabricCanvas.width / 2 - 85,
        top: fabricCanvas.height / 2 - 25
    });

    fabricCanvas.add(group);
    fabricCanvas.setActiveObject(group);
    fabricCanvas.renderAll();
    saveCanvasToCache();
}

function deleteSelectedAnnotation() {
    if (!fabricCanvas) return;
    const activeObjects = fabricCanvas.getActiveObjects();
    if (activeObjects && activeObjects.length > 0) {
        activeObjects.forEach(obj => fabricCanvas.remove(obj));
        fabricCanvas.discardActiveObject();
        fabricCanvas.renderAll();
        saveCanvasToCache();
    }
}

function clearAllAnnotations() {
    if (!fabricCanvas) return;
    if (!confirm('Clear all drawings and annotations on this page?')) return;
    const objects = fabricCanvas.getObjects();
    while (objects.length > 0) {
        fabricCanvas.remove(objects[0]);
    }
    fabricCanvas.renderAll();
    saveCanvasToCache();
}

let canvasZoomLevel = 1.0;
function zoomAnnotationCanvas(factor) {
    if (!fabricCanvas) return;
    canvasZoomLevel *= factor;
    fabricCanvas.setZoom(canvasZoomLevel);
    fabricCanvas.setWidth(fabricCanvas.getWidth() * factor);
    fabricCanvas.setHeight(fabricCanvas.getHeight() * factor);
    fabricCanvas.renderAll();
}

function resetAnnotationCanvasZoom() {
    if (!fabricCanvas || !activePageUrl) return;
    canvasZoomLevel = 1.0;
    initFabricCanvas(activePageUrl);
}

function switchActiveImage(url, elem) {
    saveCanvasToCache();
    $('#cockpitThumbnails img').removeClass('active');
    $(elem).addClass('active');
    initFabricCanvas(url);
}

function renderCockpit(data, studentInfo, pages) {
    $('#disp_student_name').text(studentInfo.name || data.student_name || 'Student');
    $('#disp_total_score').text(`${data.total_obtained_marks} / ${data.total_max_marks} M`);
    $('#disp_avg_conf').text(`${data.average_confidence || 90}% Confidence`);

    // Setup Original Image Viewer with Interactive Fabric Canvas
    if (pages && pages.length > 0) {
        let firstImgUrl = '<?php echo base_url(); ?>' + pages[0];
        
        let thumbHtml = '';
        pages.forEach((p, idx) => {
            let fullUrl = '<?php echo base_url(); ?>' + p;
            thumbHtml += `<img src="${fullUrl}" class="page-thumb-preview ${idx === 0 ? 'active' : ''}" style="width: 48px; height: 64px; flex-shrink: 0;" onclick="switchActiveImage('${fullUrl}', this)" title="Page ${idx+1}">`;
        });
        $('#cockpitThumbnails').html(thumbHtml);

        setTimeout(() => {
            initFabricCanvas(firstImgUrl);
        }, 300);
    }

    // Render Evaluated Question Cards
    let qHtml = '';
    if (data.evaluated_questions && data.evaluated_questions.length > 0) {
        data.evaluated_questions.forEach((q, qIdx) => {
            let confClass = q.confidence_score >= 85 ? 'conf-green' : (q.confidence_score >= 70 ? 'conf-yellow' : 'conf-red');
            let isLowConf = q.confidence_score < 70;

            qHtml += `
                <div class="q-eval-card ${isLowConf ? 'needs-review' : ''}" id="q_card_${qIdx}">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <span style="font-weight: 700; color: #1e293b; font-size: 14px;">Q${q.q_no} (${q.section_name || ''})</span>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <span class="eval-badge-confidence ${confClass}">${q.confidence_score}% Confidence</span>
                            <div class="input-group" style="width: 100px;">
                                <input type="number" step="0.5" min="0" max="${q.max_marks}" class="form-control input-sm text-center" style="font-weight: bold; color: #8b5cf6;" value="${q.obtained_marks}" onchange="updateQuestionMark(${qIdx}, this.value)">
                                <span class="input-group-addon" style="font-size: 11px; padding: 2px 6px;">/ ${q.max_marks}</span>
                            </div>
                        </div>
                    </div>

                    ${isLowConf ? `<div style="font-size: 11px; color: #dc2626; font-weight: 600; margin-bottom: 6px;"><i class="fa fa-exclamation-triangle"></i> Ambiguous handwriting / Please verify marks manually.</div>` : ''}

                    <div style="background: #f8fafc; border-radius: 4px; padding: 6px 10px; font-size: 12px; margin-bottom: 8px;">
                        <strong>Transcribed Student Answer:</strong>
                        <div style="font-style: italic; color: #334155; margin-top: 2px;">${q.student_answer_transcription || 'No text transcribed.'}</div>
                    </div>

                    <!-- Step-by-Step Marking Breakdown -->
                    <div style="margin-bottom: 6px;">
                        <div style="font-size: 11px; font-weight: 600; color: #64748b; margin-bottom: 4px;">Step Marking Allocation:</div>
            `;

            if (q.step_marking_breakdown && q.step_marking_breakdown.length > 0) {
                q.step_marking_breakdown.forEach(step => {
                    let stClass = step.step_status === 'correct' ? 'correct' : (step.step_status === 'partial' ? 'partial' : 'incorrect');
                    let icon = step.step_status === 'correct' ? 'fa-check text-green' : (step.step_status === 'partial' ? 'fa-adjust text-yellow' : 'fa-times text-red');
                    qHtml += `
                        <div class="step-row ${stClass}">
                            <span><i class="fa ${icon}"></i> ${step.step_description}</span>
                            <span style="font-weight: 600;">${step.marks_awarded} / ${step.marks_allocated} M</span>
                        </div>
                    `;
                });
            }

            qHtml += `
                    </div>
                    ${q.examiner_feedback ? `<div style="font-size: 11px; color: #0284c7; margin-top: 4px;"><i class="fa fa-lightbulb-o"></i> <em>Tip: ${q.examiner_feedback}</em></div>` : ''}
                </div>
            `;
        });
    }

    $('#questionsEvaluationList').html(qHtml);

    if (window.renderMathInElement) {
        renderMathInElement(document.getElementById('questionsEvaluationList'), {
            delimiters: [
                {left: '$$', right: '$$', display: true},
                {left: '$', right: '$', display: false}
            ],
            throwOnError: false
        });
    }
}

function updateQuestionMark(qIdx, newMarks) {
    if (currentEvalData && currentEvalData.evaluated_questions && currentEvalData.evaluated_questions[qIdx]) {
        currentEvalData.evaluated_questions[qIdx].obtained_marks = parseFloat(newMarks) || 0.0;
        
        let total = 0.0;
        currentEvalData.evaluated_questions.forEach(q => {
            total += parseFloat(q.obtained_marks) || 0.0;
        });
        currentEvalData.total_obtained_marks = total.toFixed(1);
        $('#disp_total_score').text(`${currentEvalData.total_obtained_marks} / ${currentEvalData.total_max_marks} M`);
    }
}

function saveTeacherVerifiedMarks(status) {
    if (!currentEvaluationId || !currentEvalData) {
        alert('No active evaluation found to save.');
        return;
    }

    saveCanvasToCache();

    $.ajax({
        url: '<?php echo base_url(); ?>admin/aiexamevaluator/save_verified_evaluation_ajax',
        type: 'POST',
        dataType: 'json',
        data: {
            evaluation_id: currentEvaluationId,
            evaluation_json: JSON.stringify(currentEvalData),
            total_obtained_marks: currentEvalData.total_obtained_marks,
            status: status
        },
        success: function(res) {
            if (res.status === 'success') {
                alert('Success: ' + res.message);
                window.location.reload(true);
            } else {
                alert('Error: ' + res.message);
            }
        }
    });
}

function printEvaluationReport(evalId) {
    openCockpitModal(evalId);
    setTimeout(() => {
        window.print();
    }, 600);
}
</script>
