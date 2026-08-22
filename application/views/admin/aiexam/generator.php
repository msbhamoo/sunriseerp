<?php
$total_papers = count($recent_papers);
$total_marks_sum = 0;
$board_term_count = 0;
$unit_test_count = 0;
foreach ($recent_papers as $rp) {
    $total_marks_sum += intval($rp['total_marks']);
    if (intval($rp['total_marks']) >= 70) {
        $board_term_count++;
    } else {
        $unit_test_count++;
    }
}
?>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.css">
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/contrib/auto-render.min.js"></script>
<!-- Include html-docx for direct MS Word download -->
<script defer src="https://cdn.jsdelivr.net/npm/html-docx-js@0.3.1/dist/html-docx.js"></script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

.ai-studio-wrapper {
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
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
    position: fixed !important;
    top: 0 !important;
    right: -680px !important;
    width: 620px !important;
    max-width: 95vw !important;
    height: 100vh !important;
    max-height: 100vh !important;
    background: #ffffff !important;
    z-index: 99999 !important;
    box-shadow: -10px 0 25px -5px rgba(0, 0, 0, 0.15) !important;
    display: flex !important;
    flex-direction: column !important;
    overflow: hidden !important;
    transition: right 0.35s cubic-bezier(0.4, 0, 0.2, 1) !important;
}
.modern-drawer-panel.is-open {
    right: 0 !important;
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

/* Blueprint Pills */
.blueprint-pill {
    cursor: pointer;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 10px 12px;
    transition: all 0.2s ease;
    background: #ffffff;
    position: relative;
}
.blueprint-pill:hover {
    border-color: #a78bfa;
    transform: translateY(-2px);
}
.blueprint-pill.active {
    border-color: #8b5cf6;
    background: linear-gradient(145deg, #f5f3ff 0%, #ede9fe 100%);
}
.blueprint-pill.active::after {
    content: '✓';
    position: absolute;
    top: 2px;
    right: 6px;
    font-size: 11px;
    font-weight: 900;
    color: #7c3aed;
}
.blueprint-pill h5 {
    margin: 0 0 2px 0;
    font-size: 14px;
    font-weight: 800;
    color: #0f172a;
}
.blueprint-pill p {
    margin: 0;
    font-size: 11px;
    font-weight: 600;
    color: #64748b;
}

.chapter-badge {
    display: inline-block;
    background: #f1f5f9;
    border: 1px solid #cbd5e1;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 11px;
    margin: 2px;
    cursor: pointer;
    transition: all 0.15s ease;
}
.chapter-badge.selected {
    background: #8b5cf6;
    color: #fff;
    border-color: #7c3aed;
}

/* Paper Preview Modal / Sheet Container */
.paper-preview-container {
    background: #ffffff;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 36px 44px;
    font-family: 'Times New Roman', Times, serif;
    color: #111827;
    position: relative;
}
.paper-watermark {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-30deg);
    font-size: 64px;
    font-weight: 900;
    color: rgba(0, 0, 0, 0.04);
    text-transform: uppercase;
    pointer-events: none;
    user-select: none;
    z-index: 0;
    white-space: nowrap;
    text-align: center;
}
.paper-header {
    text-align: center;
    border-bottom: 2px solid #000;
    padding-bottom: 16px;
    margin-bottom: 20px;
    position: relative;
    z-index: 1;
}
.paper-header-logo {
    max-height: 60px;
    margin-bottom: 6px;
    display: inline-block;
}
.paper-header h2 {
    margin: 0 0 4px 0;
    font-size: 22px;
    font-weight: bold;
    text-transform: uppercase;
}
.paper-meta-table {
    width: 100%;
    margin-top: 10px;
    font-size: 13px;
    font-weight: bold;
}
.paper-section-title {
    background: #f1f5f9;
    padding: 6px 12px;
    font-weight: bold;
    font-size: 14px;
    border-top: 1px solid #000;
    border-bottom: 1px solid #000;
    margin: 18px 0 12px 0;
    text-align: center;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.paper-preview-container table.cbse-exam-table {
    width: auto;
    max-width: 100%;
    margin: 10px 0;
    border-collapse: collapse;
    border: 1.5px solid #000;
}
.paper-preview-container table.cbse-exam-table th,
.paper-preview-container table.cbse-exam-table td {
    border: 1px solid #000;
    padding: 5px 12px;
    text-align: center;
    font-size: 13px;
}
.paper-preview-container table.cbse-exam-table th {
    background: #f1f5f9;
    font-weight: bold;
}
.q-card {
    margin-bottom: 14px;
    position: relative;
    padding-left: 36px;
    padding-right: 70px;
}
.q-card .q-num {
    position: absolute;
    left: 0;
    top: 0;
    font-weight: bold;
    font-size: 14px;
}
.q-card .q-marks {
    float: right;
    font-weight: bold;
    font-size: 13px;
}
.q-card .q-actions-bar {
    position: absolute;
    right: 0;
    top: 0;
    display: flex;
    gap: 4px;
}
.q-options {
    margin-top: 6px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 6px;
    font-size: 13px;
}
.solution-box {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 6px;
    padding: 8px 12px;
    margin-top: 6px;
    font-size: 12px;
    color: #166534;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}
.diagram-box {
    margin: 10px 0;
    text-align: center;
    background: #fff;
    padding: 8px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    display: inline-block;
}
.paper-signature-footer {
    margin-top: 36px;
    padding-top: 16px;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    font-size: 12px;
    font-weight: 600;
}
.sig-box {
    text-align: center;
    border-top: 1px solid #334155;
    padding-top: 4px;
    min-width: 130px;
}
.set-tab-btn {
    padding: 5px 12px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    background: #fff;
    cursor: pointer;
    font-weight: 600;
    font-size: 12px;
}
.set-tab-btn.active {
    background: #8b5cf6;
    color: #fff;
    border-color: #7c3aed;
}
@media print {
    @page { size: A4 portrait; margin: 12mm; }
    body * { visibility: hidden; }
    #printablePaperArea, #printablePaperArea * { visibility: visible; }
    #printablePaperArea {
        position: absolute !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    .no-print, .modal-header, .modal-footer, .q-actions-bar { display: none !important; }
}
</style>

<div class="content-wrapper ai-studio-wrapper" style="min-height: 480px;">
    <section class="content-header">
        <h1><i class="fa fa-magic" style="color: #8b5cf6;"></i> <?php echo $this->lang->line('ai_paper_generator'); ?></h1>
    </section>

    <section class="content">
        <!-- Modern KPI Stat Grid Cards (Just like Material Register) -->
        <div class="modern-stat-grid">
            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Total Generated Papers</div>
                    <div class="stat-value"><?php echo $total_papers; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(139, 92, 246, 0.12); color: #8b5cf6;">
                    <i class="fa fa-file-text-o"></i>
                </div>
            </div>

            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Board / Term Exams (70M+)</div>
                    <div class="stat-value text-success" style="color: #059669;"><?php echo $board_term_count; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
                    <i class="fa fa-graduation-cap"></i>
                </div>
            </div>

            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Unit / Periodic Tests</div>
                    <div class="stat-value text-warning" style="color: #d97706;"><?php echo $unit_test_count; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b;">
                    <i class="fa fa-clock-o"></i>
                </div>
            </div>

            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Academic Session</div>
                    <div class="stat-value" style="color: #0284c7; font-size: 18px;"><?php echo $current_session; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(14, 165, 233, 0.12); color: #0284c7;">
                    <i class="fa fa-calendar"></i>
                </div>
            </div>
        </div>

        <!-- Main Card with Header Button to Open Slide-in Drawer -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary" style="border-radius: 12px; overflow: hidden; border-top: 3px solid #8b5cf6;">
                    <div class="box-header ptbnull" style="padding: 14px 18px;">
                        <h3 class="box-title titlefix">
                            <i class="fa fa-list text-muted" style="margin-right: 6px;"></i> Generated CBSE Question Papers Archive
                        </h3>
                        <div class="box-tools pull-right" style="display: flex; gap: 8px;">
                            <button type="button" class="btn btn-default btn-sm" onclick="openBulkSyncModal()" style="font-weight: 600; color: #4f46e5; border-color: #cbd5e1; border-radius: 6px;" title="Pre-fetch and save curriculum chapters for all classes & subjects in background">
                                <i class="fa fa-cloud-download"></i> Sync All Syllabi via AI
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" id="btn-open-generator-drawer" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); border: none; font-weight: 700; padding: 6px 14px; border-radius: 6px; box-shadow: 0 2px 8px rgba(139, 92, 246, 0.3);">
                                <i class="fa fa-plus"></i> Generate New Question Paper
                            </button>
                        </div>
                    </div>
                    <div class="box-body" style="padding: 15px 18px;">
                        <div class="table-responsive" style="overflow-x: auto; overflow-y: visible; padding-bottom: 60px; margin-bottom: -60px;">
                            <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                    <tr style="background: #f8fafc;">
                                        <th style="width: 50px;">#</th>
                                        <th>Paper Title</th>
                                        <th>Class</th>
                                        <th>Subject</th>
                                        <th>Max Marks</th>
                                        <th>Created Date</th>
                                        <th class="text-right noExport" style="width: 100px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($recent_papers)) {
                                        $cnt = 1;
                                        foreach ($recent_papers as $p) { ?>
                                            <tr id="row_paper_<?php echo $p['id']; ?>">
                                                <td><?php echo $cnt++; ?></td>
                                                <td>
                                                    <strong style="color: #0f172a;"><?php echo htmlspecialchars($p['paper_title']); ?></strong>
                                                </td>
                                                <td><span class="badge bg-purple" style="font-size: 11px;"><?php echo $p['class_name']; ?></span></td>
                                                <td><span class="badge bg-blue" style="font-size: 11px;"><?php echo $p['subject_name']; ?></span></td>
                                                <td><strong><?php echo $p['total_marks']; ?> M</strong></td>
                                                <td class="white-space-nowrap"><i class="fa fa-calendar-o text-muted" style="margin-right: 4px;"></i> <?php echo date('d M Y, h:i A', strtotime($p['created_at'])); ?></td>
                                                <td class="text-right white-space-nowrap">
                                                    <div class="dropdown" style="display: inline-block;">
                                                        <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" style="border-radius: 6px; padding: 4px 8px; color: #475569; background: #f8fafc; border-color: #e2e8f0;">
                                                            <i class="fa fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-right" style="border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; min-width: 150px; padding: 4px 0; font-size: 13px;">
                                                            <li>
                                                                <a href="javascript:void(0);" onclick="openViewPaperModal(<?php echo $p['id']; ?>)">
                                                                    <i class="fa fa-eye text-info" style="width: 18px;"></i> View & Print Paper
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0);" onclick="openViewPaperModal(<?php echo $p['id']; ?>, true)">
                                                                    <i class="fa fa-check-square-o text-success" style="width: 18px;"></i> View Solutions Key
                                                                </a>
                                                            </li>
                                                            <li role="separator" class="divider" style="margin: 4px 0;"></li>
                                                            <li>
                                                                <a href="javascript:void(0);" onclick="deleteSavedPaperRow(<?php echo $p['id']; ?>)" style="color: #ef4444;">
                                                                    <i class="fa fa-trash text-danger" style="width: 18px;"></i> Delete
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                    <?php } } else { ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted" style="padding: 40px 10px;">
                                                <i class="fa fa-folder-open-o" style="font-size: 32px; color: #cbd5e1; margin-bottom: 8px;"></i>
                                                <div style="font-size: 14px; font-weight: 600; color: #64748b;">No question papers generated yet.</div>
                                                <div style="font-size: 12px; color: #94a3b8; margin-top: 4px;">Click "Generate New Question Paper" above to create your first CBSE paper.</div>
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

<!-- Slide-in Right Side Drawer for Generating AI Paper (Material Register Drawer Style) -->
<div id="ai-generator-drawer-overlay" class="modern-drawer-overlay"></div>
<form id="aiGeneratorForm">
<div id="ai-generator-drawer-panel" class="modern-drawer-panel">
    <div class="modern-drawer-header">
        <h4 class="modern-drawer-title"><i class="fa fa-magic text-primary"></i> AI Paper Generator Studio</h4>
        <button type="button" class="modern-drawer-close" id="btn-close-generator-drawer">&times;</button>
    </div>

    <div class="modern-drawer-body" style="flex: 1 1 auto !important; height: calc(100vh - 130px) !important; max-height: calc(100vh - 130px) !important; overflow-y: auto !important; overflow-x: hidden !important; padding: 22px 24px 40px 24px !important; background: #ffffff !important; -webkit-overflow-scrolling: touch !important;">
            <!-- 1. Class & Subject Row -->
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Select Class <small class="req">*</small></label>
                        <select id="gen_class_id" name="class_id" class="form-control" onchange="onClassOrSubjectChange()" required>
                            <option value="">-- Choose Class --</option>
                            <?php if (!empty($classlist)) {
                                foreach ($classlist as $cls) { ?>
                                    <option value="<?php echo $cls['id']; ?>" data-name="<?php echo htmlspecialchars($cls['class']); ?>"><?php echo $cls['class']; ?></option>
                            <?php } } ?>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Select Subject <small class="req">*</small></label>
                        <select id="gen_subject_id" name="subject_id" class="form-control" onchange="onClassOrSubjectChange()" required>
                            <option value="">-- Choose Subject --</option>
                            <?php if (!empty($subjectlist)) {
                                foreach ($subjectlist as $sub) { ?>
                                    <option value="<?php echo $sub['id']; ?>" data-name="<?php echo htmlspecialchars($sub['name']); ?>"><?php echo $sub['name']; ?></option>
                            <?php } } ?>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Pre-Primary / Kindergarten Mode Alert Banner (Dynamic) -->
            <div id="preprimaryModeBox" style="display: none; background: #fefce8; border: 1px solid #fef08a; border-radius: 8px; padding: 10px 12px; margin-bottom: 14px;">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <span style="font-size: 16px;">🎨</span>
                    <div>
                        <strong style="font-size: 12px; color: #854d0e;">Pre-Primary Worksheet Mode Activated</strong>
                        <div style="font-size: 11px; color: #a16207;">Auto-generating child-friendly pictorial activities: Coloring outlines, Matching columns, Fruit/Animal identification, and Visual counting.</div>
                    </div>
                </div>
            </div>

            <!-- 2. NCERT / Curriculum Chapters & Scope -->
            <div class="form-group">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                    <label style="margin: 0; font-weight: 700; color: #1e293b;"><i class="fa fa-book text-primary"></i> Curriculum Chapters & Scope</label>
                    <button type="button" class="btn btn-default btn-xs" id="btnFetchAiChapters" onclick="fetchChaptersForCurrentSelection(true)" style="font-size: 11px; font-weight: 600; color: #4f46e5; border-color: #cbd5e1;" title="Re-fetch updated chapter syllabus from AI">
                        <i class="fa fa-refresh"></i> Re-Fetch via AI
                    </button>
                </div>
                
                <div id="ncertChapterBrowserBox" style="display: none; margin-bottom: 8px; max-height: 140px; overflow-y: auto; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <span style="font-size: 11px; color: #64748b; font-weight: 600;">Click chapters to select scope:</span>
                        <span id="chapterSourceBadge" class="badge" style="font-size: 10px; background: #e2e8f0; color: #475569;">Saved in DB</span>
                    </div>
                    <div id="ncertChapterBadges" style="display: flex; flex-wrap: wrap; gap: 4px;"></div>
                </div>

                <div id="ncertLoadingBox" style="display: none; padding: 10px; background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 8px; text-align: center; margin-bottom: 8px;">
                    <i class="fa fa-spinner fa-spin text-primary"></i> <span style="font-size: 12px; font-weight: 600; color: #334155;">Fetching curriculum chapters using AI...</span>
                </div>

                <input type="text" id="gen_chapter" class="form-control" placeholder="e.g. Real Numbers, Polynomials, Light Reflection" value="Complete Syllabus">
                <small class="text-muted" style="font-size: 11px;">Select individual chapters above or type custom topic names.</small>
            </div>

            <!-- 3. Blueprint Total Marks Selection -->
            <div class="form-group">
                <label>Exam Blueprint / Total Marks</label>
                <div class="row" style="margin: 0 -5px;">
                    <div class="col-xs-4" style="padding: 0 5px;">
                        <div class="blueprint-pill active text-center" data-marks="80" onclick="selectBlueprint(this, 80)">
                            <h5>80 M</h5>
                            <p>Board / Term</p>
                        </div>
                    </div>
                    <div class="col-xs-4" style="padding: 0 5px;">
                        <div class="blueprint-pill text-center" data-marks="40" onclick="selectBlueprint(this, 40)">
                            <h5>40 M</h5>
                            <p>Periodic Test</p>
                        </div>
                    </div>
                    <div class="col-xs-4" style="padding: 0 5px;">
                        <div class="blueprint-pill text-center" data-marks="20" onclick="selectBlueprint(this, 20)">
                            <h5>20 M</h5>
                            <p>Unit / Quiz</p>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="gen_total_marks" value="80">
            </div>

            <!-- 4. Anti-Cheating Multi-Sets Feature -->
            <div class="form-group" style="background: #faf5ff; border: 1px dashed #c084fc; border-radius: 8px; padding: 12px; margin-bottom: 16px;">
                <label style="display: flex; justify-content: space-between; align-items: center; cursor: pointer; margin: 0;">
                    <span style="color: #6b21a8; font-weight: 700; font-size: 13px;">
                        <i class="fa fa-clone"></i> Anti-Cheating Multi-Sets (Set A, B, C)
                    </span>
                    <input type="checkbox" id="gen_multi_sets" value="1" style="width: 16px; height: 16px; cursor: pointer;">
                </label>
                <div style="font-size: 11px; color: #7e22ce; margin-top: 4px;">
                    Generates 3 shuffled variations with randomized options and modified numerical values.
                </div>
            </div>

            <!-- 5. Difficulty & Language Row -->
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Difficulty</label>
                        <select id="gen_difficulty" class="form-control">
                            <option value="Easy">Easy (Foundation / Basic)</option>
                            <option value="Medium" selected>Standard Balanced</option>
                            <option value="Hard">Challenging / Exemplar</option>
                            <option value="Competency Focused">Competency Focused (NEP 2020)</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Language</label>
                        <select id="gen_language" class="form-control">
                            <option value="English" selected>English</option>
                            <option value="Hindi">Hindi (हिंदी)</option>
                            <option value="Bilingual">Bilingual (English + Hindi)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- 6. Bloom's Taxonomy Weightage Accordion -->
            <div class="form-group">
                <div class="panel panel-default" style="border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 10px;">
                    <div class="panel-heading" style="background: #f8fafc; padding: 8px 12px; cursor: pointer;" data-toggle="collapse" data-target="#collapseBlooms">
                        <h4 class="panel-title" style="font-size: 12px; font-weight: 700; color: #334155; display: flex; justify-content: space-between; align-items: center;">
                            <span><i class="fa fa-graduation-cap text-primary"></i> Bloom's Taxonomy Weightage</span>
                            <span class="text-primary" style="font-size: 11px;"><i class="fa fa-sliders"></i> Adjust</span>
                        </h4>
                    </div>
                    <div id="collapseBlooms" class="panel-collapse collapse">
                        <div class="panel-body" style="padding: 10px 12px;">
                            <div class="row" style="margin: 0 -5px;">
                                <div class="col-xs-6" style="padding: 0 5px; margin-bottom: 6px;">
                                    <label style="font-size: 11px; margin-bottom: 2px;">Remembering (%)</label>
                                    <input type="number" id="bloom_remember" class="form-control input-sm" value="20" min="0" max="100">
                                </div>
                                <div class="col-xs-6" style="padding: 0 5px; margin-bottom: 6px;">
                                    <label style="font-size: 11px; margin-bottom: 2px;">Understanding (%)</label>
                                    <input type="number" id="bloom_understand" class="form-control input-sm" value="30" min="0" max="100">
                                </div>
                                <div class="col-xs-6" style="padding: 0 5px; margin-bottom: 6px;">
                                    <label style="font-size: 11px; margin-bottom: 2px;">Applying (%)</label>
                                    <input type="number" id="bloom_apply" class="form-control input-sm" value="25" min="0" max="100">
                                </div>
                                <div class="col-xs-6" style="padding: 0 5px; margin-bottom: 6px;">
                                    <label style="font-size: 11px; margin-bottom: 2px;">Analyzing / HOTS (%)</label>
                                    <input type="number" id="bloom_analyze" class="form-control input-sm" value="25" min="0" max="100">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 7. Section / Question Distribution Customizer -->
            <div class="form-group">
                <div class="panel panel-default" style="border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 10px;">
                    <div class="panel-heading" style="background: #f8fafc; padding: 8px 12px; cursor: pointer;" data-toggle="collapse" data-target="#collapseSections">
                        <h4 class="panel-title" style="font-size: 12px; font-weight: 700; color: #334155; display: flex; justify-content: space-between; align-items: center;">
                            <span><i class="fa fa-list-ol text-primary"></i> Question Types & Counts</span>
                            <span class="text-primary" style="font-size: 11px;">Customizer <i class="fa fa-chevron-down"></i></span>
                        </h4>
                    </div>
                    <div id="collapseSections" class="panel-collapse collapse">
                        <div class="panel-body" style="padding: 10px 12px;">
                            <div class="row" style="margin: 0 -5px;">
                                <div class="col-xs-6" style="padding: 0 5px; margin-bottom: 6px;">
                                    <label style="font-size: 11px; margin-bottom: 2px;">MCQ / Objective (1M)</label>
                                    <input type="number" id="dist_mcq" class="form-control input-sm" placeholder="Auto" min="0">
                                </div>
                                <div class="col-xs-6" style="padding: 0 5px; margin-bottom: 6px;">
                                    <label style="font-size: 11px; margin-bottom: 2px;">Short Answer I (2M)</label>
                                    <input type="number" id="dist_sa1" class="form-control input-sm" placeholder="Auto" min="0">
                                </div>
                                <div class="col-xs-6" style="padding: 0 5px; margin-bottom: 6px;">
                                    <label style="font-size: 11px; margin-bottom: 2px;">Short Answer II (3M)</label>
                                    <input type="number" id="dist_sa2" class="form-control input-sm" placeholder="Auto" min="0">
                                </div>
                                <div class="col-xs-6" style="padding: 0 5px; margin-bottom: 6px;">
                                    <label style="font-size: 11px; margin-bottom: 2px;">Long Answer (5M)</label>
                                    <input type="number" id="dist_la" class="form-control input-sm" placeholder="Auto" min="0">
                                </div>
                                <div class="col-xs-6" style="padding: 0 5px; margin-bottom: 6px;">
                                    <label style="font-size: 11px; margin-bottom: 2px;">Case Study (4M)</label>
                                    <input type="number" id="dist_case" class="form-control input-sm" placeholder="Auto" min="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 8. AI Generation Engine -->
            <div class="form-group">
                <label><i class="fa fa-microchip text-primary"></i> AI Generation Engine</label>
                <select id="gen_engine" class="form-control input-sm" style="font-weight: 600;">
                    <option value="openrouter_ox" <?php echo (!empty($sch_setting->ai_default_model) && $sch_setting->ai_default_model == 'openrouter_ox') ? 'selected' : ''; ?>>
                        🌟 OpenRouter: 01-ai / ox-alpha (Fable 5 Free Tier / Frontier Reasoning)
                    </option>
                    <option value="gemini" <?php echo (!empty($sch_setting->ai_default_model) && $sch_setting->ai_default_model == 'gemini') ? 'selected' : (!empty($sch_setting->ai_default_model) ? '' : 'selected'); ?>>
                        ⚡ Google Gemini 2.0 Flash (Fast & Precise)
                    </option>
                    <option value="groq" <?php echo (!empty($sch_setting->ai_default_model) && $sch_setting->ai_default_model == 'groq') ? 'selected' : ''; ?>>
                        🚀 Groq Cloud: LLaMA-3.3 70B (500 tok/sec)
                    </option>
                    <option value="openai" <?php echo (!empty($sch_setting->ai_default_model) && $sch_setting->ai_default_model == 'openai') ? 'selected' : ''; ?>>
                        🧠 OpenAI GPT-4o (Standard)
                    </option>
                </select>
                <small class="text-muted" style="font-size: 11px;">Powered by keys configured in <a href="<?php echo base_url(); ?>admin/aisetting" target="_blank" style="color: #6366f1;">AI Settings</a>.</small>
            </div>

            <!-- Hidden Defaults -->
            <input type="hidden" id="gen_api_key" value="">
    </div>

    <div class="modern-drawer-footer" style="flex-shrink: 0; background: #f8fafc; padding: 14px 24px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 10px; z-index: 10;">
        <button type="button" class="btn btn-default" id="btn-cancel-generator-drawer">Cancel</button>
        <button type="button" id="btnGeneratePaper" class="btn btn-primary" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); border: none; font-weight: 700; padding: 8px 18px;" onclick="startGeneration()">
            <i class="fa fa-bolt"></i> Generate & Save Paper
        </button>
    </div>
</div>
</form>

<!-- Modal: View / Print Generated Question Paper (Full View & Actions) -->
<div class="modal fade" id="modalViewPaper" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document" style="width: 90%; max-width: 1050px;">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
            <div class="modal-header" style="background: #ffffff; color: #0f172a; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; padding: 14px 20px;">
                <h4 class="modal-title" style="font-size: 16px; font-weight: 700; color: #0f172a;">
                    <i class="fa fa-file-text-o text-primary"></i> <span id="modalPaperTitle">CBSE Examination Question Paper</span>
                </h4>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <!-- Multi-Set Switcher Buttons -->
                    <div id="setSwitcherContainer" style="display: none; gap: 4px; margin-right: 10px;">
                        <button type="button" class="set-tab-btn active" onclick="switchActiveSet('Set A')">Set A</button>
                        <button type="button" class="set-tab-btn" onclick="switchActiveSet('Set B')">Set B</button>
                        <button type="button" class="set-tab-btn" onclick="switchActiveSet('Set C')">Set C</button>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" style="color: #64748b; font-size: 24px; opacity: 0.8;">&times;</button>
                </div>
            </div>

            <div class="modal-body" style="padding: 20px; background: #f8fafc; max-height: 78vh; overflow-y: auto;">
                <!-- Action Bar -->
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; flex-wrap: wrap; gap: 8px;" class="no-print">
                    <div style="display: flex; gap: 6px;">
                        <button type="button" class="btn btn-default btn-sm" onclick="$('#modalBrandingSettings').modal('show')">
                            <i class="fa fa-picture-o"></i> Branding & Watermark
                        </button>
                        <button type="button" class="btn btn-default btn-sm" onclick="toggleSolutions()">
                            <i class="fa fa-eye"></i> <span id="solBtnText">Show Solutions</span>
                        </button>
                        <button type="button" class="btn btn-info btn-sm" onclick="saveToQuestionBank()">
                            <i class="fa fa-database"></i> Save to Question Bank
                        </button>
                        <button type="button" class="btn btn-warning btn-sm" onclick="exportToWordDocx()" title="Download Microsoft Word .docx file">
                            <i class="fa fa-file-word-o"></i> Download Word (.docx)
                        </button>
                    </div>
                    <div style="display: flex; gap: 6px;">
                        <button type="button" class="btn btn-primary btn-sm" onclick="printCleanPaper(false)">
                            <i class="fa fa-print"></i> Print Paper
                        </button>
                        <button type="button" class="btn btn-success btn-sm" onclick="printCleanPaper(true)">
                            <i class="fa fa-check-square-o"></i> Print with Solutions
                        </button>
                    </div>
                </div>

                <!-- Printable Paper Container -->
                <div id="printablePaperArea">
                    <div class="paper-preview-container" id="paperContainer">
                        <!-- Content Injected Dynamically -->
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0;">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: In-Place Edit Question -->
<div class="modal fade" id="modalEditQuestion" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius: 10px; overflow: hidden;">
            <div class="modal-header" style="background: #1e293b; color: #fff;">
                <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
                <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> Edit Question Details</h4>
            </div>
            <div class="modal-body" style="padding: 20px;">
                <input type="hidden" id="edit_sec_idx">
                <input type="hidden" id="edit_q_idx">
                <div class="form-group">
                    <label>Question Text (Supports LaTeX $...$)</label>
                    <textarea id="edit_q_text" class="form-control" rows="3"></textarea>
                </div>
                <div class="row">
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Marks</label>
                            <input type="number" id="edit_q_marks" class="form-control" min="1" max="10">
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="form-group">
                            <label>Correct Answer / Key</label>
                            <input type="text" id="edit_q_correct" class="form-control" placeholder="e.g. A, True, or Final Value">
                        </div>
                    </div>
                </div>

                <!-- MCQ Options Section (Dynamic) -->
                <div id="edit_mcq_options_box" style="display: none; background: #f8fafc; border: 1px solid #e2e8f0; padding: 14px; border-radius: 8px; margin-bottom: 15px;">
                    <label style="font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 8px;"><i class="fa fa-list-ul"></i> MCQ Options (A, B, C, D)</label>
                    <div class="row">
                        <div class="col-xs-6" style="margin-bottom: 8px;">
                            <div class="input-group">
                                <span class="input-group-addon" style="font-weight: bold;">A</span>
                                <input type="text" id="edit_opt_A" class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-6" style="margin-bottom: 8px;">
                            <div class="input-group">
                                <span class="input-group-addon" style="font-weight: bold;">B</span>
                                <input type="text" id="edit_opt_B" class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="input-group">
                                <span class="input-group-addon" style="font-weight: bold;">C</span>
                                <input type="text" id="edit_opt_C" class="form-control">
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="input-group">
                                <span class="input-group-addon" style="font-weight: bold;">D</span>
                                <input type="text" id="edit_opt_D" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Answer Key / Step-by-Step Marking Scheme</label>
                    <textarea id="edit_q_explanation" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="saveEditedQuestion()"><i class="fa fa-save"></i> Save Changes</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Branding, Watermark & Signature Settings -->
<div class="modal fade" id="modalBrandingSettings" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius: 10px; overflow: hidden;">
            <div class="modal-header" style="background: #1e293b; color: #fff;">
                <button type="button" class="close" data-dismiss="modal" style="color:#fff;">&times;</button>
                <h4 class="modal-title"><i class="fa fa-picture-o"></i> Print Branding, Watermark & Signatures</h4>
            </div>
            <div class="modal-body" style="padding: 20px;">
                <div class="form-group">
                    <label style="display: flex; justify-content: space-between; align-items: center;">
                        <span><i class="fa fa-image"></i> <strong>Show School Crest / Logo in Header</strong></span>
                        <input type="checkbox" id="chk_show_logo" checked onchange="applyBrandingSettings()">
                    </label>
                    <div style="margin-top: 6px;">
                        <input type="file" id="upload_school_logo" accept="image/*" class="form-control" onchange="previewUploadedLogo(this)">
                        <small class="text-muted">Upload custom school logo/crest (PNG/JPG) or use default branding</small>
                    </div>
                </div>

                <hr style="margin: 15px 0;">

                <div class="form-group">
                    <label style="display: flex; justify-content: space-between; align-items: center;">
                        <span><i class="fa fa-shield"></i> <strong>Enable Background Watermark</strong></span>
                        <input type="checkbox" id="chk_show_watermark" checked onchange="applyBrandingSettings()">
                    </label>
                    <div class="row" style="margin-top: 8px;">
                        <div class="col-xs-8">
                            <input type="text" id="txt_watermark_text" class="form-control" value="<?php echo isset($sch_setting->name) ? htmlspecialchars($sch_setting->name) : 'SUNRISE INTERNATIONAL SCHOOL'; ?>" placeholder="Watermark Text" oninput="applyBrandingSettings()">
                        </div>
                        <div class="col-xs-4">
                            <select id="sel_watermark_opacity" class="form-control" onchange="applyBrandingSettings()">
                                <option value="0.04" selected>Light (4%)</option>
                                <option value="0.08">Medium (8%)</option>
                                <option value="0.12">Strong (12%)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <hr style="margin: 15px 0;">

                <div class="form-group">
                    <label style="display: flex; justify-content: space-between; align-items: center;">
                        <span><i class="fa fa-pencil"></i> <strong>Teacher & Examiner Signatures Footer</strong></span>
                        <input type="checkbox" id="chk_show_signatures" checked onchange="applyBrandingSettings()">
                    </label>
                    <div class="row" style="margin-top: 8px;">
                        <div class="col-xs-4">
                            <input type="text" id="sig_label_1" class="form-control input-sm" value="Subject Teacher" oninput="applyBrandingSettings()">
                        </div>
                        <div class="col-xs-4">
                            <input type="text" id="sig_label_2" class="form-control input-sm" value="HOD / Examiner" oninput="applyBrandingSettings()">
                        </div>
                        <div class="col-xs-4">
                            <input type="text" id="sig_label_3" class="form-control input-sm" value="Principal" oninput="applyBrandingSettings()">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-check"></i> Apply to Paper</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Bulk Sync All Syllabi Chapters in Background -->
<div class="modal fade" id="modalBulkSync" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="max-width: 600px;">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden;">
            <div class="modal-header" style="background: #ffffff; color: #0f172a; border-bottom: 1px solid #e2e8f0; padding: 16px 20px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="font-weight: 700; display: flex; align-items: center; gap: 8px;">
                    <i class="fa fa-cloud-download text-primary"></i> Sync All Class & Subject Syllabi via AI
                </h4>
            </div>
            <div class="modal-body" style="padding: 20px;">
                <p style="font-size: 13px; color: #475569; line-height: 1.5; margin-bottom: 15px;">
                    This will run through all school classes and subjects, query the AI model (Gemini / OpenRouter <code>ox-alpha</code> / Groq), and permanently cache standard chapter lists in your database.
                </p>

                <div class="progress" style="height: 20px; border-radius: 10px; margin-bottom: 12px; display: none;" id="bulkSyncProgressBox">
                    <div id="bulkSyncProgressBar" class="progress-bar progress-bar-striped active" role="progressbar" style="width: 0%; background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); font-weight: bold; font-size: 11px;">0%</div>
                </div>

                <div id="bulkSyncStatus" style="font-size: 12px; color: #64748b; margin-bottom: 12px;"></div>

                <div id="bulkSyncLogBox" style="max-height: 180px; overflow-y: auto; background: #0f172a; color: #38bdf8; font-family: monospace; font-size: 11px; padding: 10px; border-radius: 6px; display: none;"></div>
            </div>
            <div class="modal-footer" style="background: #f8fafc; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                <button type="button" class="btn btn-danger btn-sm" id="btnStopBulkSync" onclick="stopBulkSyncQueue()" style="display: none;">
                    <i class="fa fa-stop"></i> Stop / Cancel Sync
                </button>
                <div style="margin-left: auto; display: flex; gap: 8px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="btnStartBulkSync" onclick="startBulkSyncQueue()"><i class="fa fa-play"></i> Start Background Sync</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let currentPaperData = null;
let currentPaperClassId = null;
let currentPaperSubjectId = null;
let activeSetName = 'Set A';
let showSolutions = false;

$(document).ready(function() {
    $('#btn-open-generator-drawer').on('click', function() {
        openGeneratorDrawer();
    });
    $('#btn-close-generator-drawer, #btn-cancel-generator-drawer, #ai-generator-drawer-overlay').on('click', function() {
        closeGeneratorDrawer();
    });
});

function openGeneratorDrawer() {
    $('#ai-generator-drawer-overlay').addClass('is-active');
    $('#ai-generator-drawer-panel').addClass('is-open');
    $('body').css('overflow', 'hidden');
}

function closeGeneratorDrawer() {
    $('#ai-generator-drawer-panel').removeClass('is-open');
    $('#ai-generator-drawer-overlay').removeClass('is-active');
    $('body').css('overflow', '');
}

function onClassOrSubjectChange() {
    const className = ($('#gen_class_id option:selected').data('name') || '').toLowerCase();
    const isPreprimary = (
        className.indexOf('nursery') !== -1 ||
        className.indexOf('lkg') !== -1 ||
        className.indexOf('ukg') !== -1 ||
        className.indexOf('kg') !== -1 ||
        className.indexOf('prep') !== -1 ||
        className.indexOf('play') !== -1 ||
        className.indexOf('kindergarten') !== -1
    );

    if (isPreprimary) {
        $('#preprimaryModeBox').slideDown(200);
        // Adjust default marks if currently 80M
        if ($('#gen_total_marks').val() == '80') {
            selectBlueprint($('.blueprint-pill[data-marks="20"]')[0], 20);
        }
    } else {
        $('#preprimaryModeBox').slideUp(200);
    }

    fetchChaptersForCurrentSelection(false);
}

function fetchChaptersForCurrentSelection(forceReload) {
    const className = $('#gen_class_id option:selected').data('name') || '';
    const subjectName = $('#gen_subject_id option:selected').data('name') || '';
    const apiEngine = $('#gen_engine').val() || 'gemini';

    if (!className || !subjectName) {
        $('#ncertChapterBrowserBox').hide();
        $('#ncertLoadingBox').hide();
        return;
    }

    $('#ncertChapterBrowserBox').hide();
    $('#ncertLoadingBox').show();
    $('#btnFetchAiChapters').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Fetching...');

    $.ajax({
        url: '<?php echo base_url(); ?>admin/aiexamgenerator/get_or_fetch_chapters_ajax',
        type: 'POST',
        dataType: 'json',
        data: {
            class_name: className,
            subject_name: subjectName,
            api_engine: apiEngine,
            force_reload: forceReload ? 1 : 0
        },
        success: function(res) {
            $('#ncertLoadingBox').hide();
            $('#btnFetchAiChapters').prop('disabled', false).html('<i class="fa fa-refresh"></i> Re-Fetch via AI');

            if (res.status === 'success' && res.chapters && res.chapters.length > 0) {
                let badgesHtml = '';
                res.chapters.forEach(ch => {
                    badgesHtml += `<span class="chapter-badge" onclick="toggleChapterBadge(this, '${ch.replace(/'/g, "\\'")}')">${ch}</span>`;
                });
                $('#ncertChapterBadges').html(badgesHtml);

                if (res.source === 'database_cache') {
                    $('#chapterSourceBadge').text('⚡ Saved in Database').css({'background': '#dcfce7', 'color': '#15803d'});
                } else {
                    $('#chapterSourceBadge').text('✨ AI Fetched & Saved').css({'background': '#ede9fe', 'color': '#6d28d9'});
                }

                $('#ncertChapterBrowserBox').slideDown();
            } else {
                $('#ncertChapterBrowserBox').hide();
            }
        },
        error: function() {
            $('#ncertLoadingBox').hide();
            $('#btnFetchAiChapters').prop('disabled', false).html('<i class="fa fa-refresh"></i> Re-Fetch via AI');
            $('#ncertChapterBrowserBox').hide();
        }
    });
}

function toggleChapterBadge(elem, chName) {
    $(elem).toggleClass('selected');
    let selected = [];
    $('.chapter-badge.selected').each(function() {
        selected.push($(this).text());
    });
    if (selected.length > 0) {
        $('#gen_chapter').val(selected.join(', '));
    } else {
        $('#gen_chapter').val('Complete Syllabus');
    }
}

function selectBlueprint(element, marks) {
    $('.blueprint-pill').removeClass('active');
    $(element).addClass('active');
    $('#gen_total_marks').val(marks);
}

function startGeneration() {
    const classId = $('#gen_class_id').val();
    const className = $('#gen_class_id option:selected').data('name');
    const subjectId = $('#gen_subject_id').val();
    const subjectName = $('#gen_subject_id option:selected').data('name');
    const chapter = $('#gen_chapter').val();
    const totalMarks = $('#gen_total_marks').val();
    const difficulty = $('#gen_difficulty').val();
    const language = $('#gen_language').val();
    const apiEngine = $('#gen_engine').val();
    const isMultiSets = $('#gen_multi_sets').is(':checked') ? 'yes' : 'no';

    if (!classId || !subjectId) {
        alert('Please select both Class and Subject.');
        return;
    }

    currentPaperClassId = classId;
    currentPaperSubjectId = subjectId;

    const qDistribution = {
        mcq_count: $('#dist_mcq').val() || null,
        tf_count: $('#dist_tf').val() || null,
        fib_count: $('#dist_fib').val() || null,
        ar_count: $('#dist_ar').val() || null,
        sa1_count: $('#dist_sa1').val() || null,
        sa2_count: $('#dist_sa2').val() || null,
        la_count: $('#dist_la').val() || null,
        case_count: $('#dist_case').val() || null
    };

    const bloomsTaxonomy = {
        remembering: $('#slider_bloom_rem').val(),
        applying: $('#slider_bloom_app').val(),
        hots: $('#slider_bloom_hots').val()
    };

    $('#btnGeneratePaper').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Generating Question Paper...');

    $.ajax({
        url: '<?php echo base_url(); ?>admin/aiexamgenerator/generate_paper_ajax',
        type: 'POST',
        dataType: 'json',
        data: {
            class_id: classId,
            class_name: className,
            subject_id: subjectId,
            subject_name: subjectName,
            chapter: chapter,
            total_marks: totalMarks,
            difficulty: difficulty,
            language: language,
            generate_multi_sets: isMultiSets,
            blooms_taxonomy: bloomsTaxonomy,
            question_distribution: qDistribution,
            api_engine: apiEngine,
            api_key: ''
        },
        success: function(res) {
            $('#btnGeneratePaper').prop('disabled', false).html('<i class="fa fa-bolt"></i> Generate & Save Paper');

            if (res.status === 'success' && res.data) {
                closeGeneratorDrawer();
                currentPaperData = res.data;
                activeSetName = 'Set A';
                renderPaper(currentPaperData, activeSetName);
                $('#modalPaperTitle').text(currentPaperData.paper_title || 'CBSE Examination Paper');
                $('#modalViewPaper').modal('show');

                // If a new paper row was saved, prepend to table dynamically (no disruptive reload)
                if (res.saved_paper_id) {
                    const newId = res.saved_paper_id;
                    const pTitle = currentPaperData.paper_title || `${className} ${subjectName} Examination`;
                    const newRowHtml = `
                        <tr id="row_paper_${newId}" style="background: #f0fdf4;">
                            <td class="font-weight-600">${newId}</td>
                            <td>
                                <strong style="color: #0f172a;">${pTitle}</strong>
                                <br><small class="text-muted"><i class="fa fa-book"></i> ${chapter}</small>
                            </td>
                            <td><span class="badge" style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1;">${className}</span></td>
                            <td><span class="badge" style="background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe;">${subjectName}</span></td>
                            <td><strong>${totalMarks} M</strong></td>
                            <td class="text-muted" style="font-size: 11px;"><i class="fa fa-clock-o"></i> Just now</td>
                            <td class="text-right white-space-nowrap">
                                <button type="button" class="btn btn-default btn-xs" onclick="openViewPaperModal(${newId})" title="View Question Paper" style="border-radius: 4px; padding: 3px 8px; color: #4f46e5;"><i class="fa fa-eye"></i></button>
                                <button type="button" class="btn btn-default btn-xs" onclick="openViewPaperModal(${newId}, true)" title="View with Answer Key & Marking Scheme" style="border-radius: 4px; padding: 3px 8px; color: #16a34a;"><i class="fa fa-key"></i></button>
                                <button type="button" class="btn btn-default btn-xs" onclick="deleteSavedPaperRow(${newId})" title="Delete Paper" style="border-radius: 4px; padding: 3px 8px; color: #ef4444;"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
                    $('#tblGeneratedPapers tbody').prepend(newRowHtml);
                }
            } else {
                alert('Generation Error: ' + (res.message || 'Unknown error occurred.'));
            }
        },
        error: function(xhr, status, err) {
            $('#btnGeneratePaper').prop('disabled', false).html('<i class="fa fa-bolt"></i> Generate & Save Paper');
            let errMsg = err;
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errMsg = xhr.responseJSON.message;
            } else if (xhr.responseText) {
                try {
                    let parsed = JSON.parse(xhr.responseText);
                    if (parsed.message) errMsg = parsed.message;
                } catch(e) {}
            }
            alert('Request Failed: ' + errMsg);
        }
    });
}

function openViewPaperModal(paperId, openWithSolutions = false) {
    showSolutions = openWithSolutions;
    $('#solBtnText').text(showSolutions ? 'Hide Solutions' : 'Show Solutions');

    $.ajax({
        url: '<?php echo base_url(); ?>admin/aiexamgenerator/get_saved_paper_ajax',
        type: 'POST',
        dataType: 'json',
        data: { paper_id: paperId },
        success: function(res) {
            if (res.status === 'success' && res.data) {
                currentPaperData = res.data;
                currentPaperClassId = res.paper_info.class_id;
                currentPaperSubjectId = res.paper_info.subject_id;
                activeSetName = 'Set A';
                renderPaper(currentPaperData, activeSetName);
                $('#modalPaperTitle').text(currentPaperData.paper_title || 'CBSE Examination Paper');
                $('#modalViewPaper').modal('show');
            } else {
                alert('Error loading paper: ' + res.message);
            }
        }
    });
}

function deleteSavedPaperRow(paperId) {
    if (!confirm('Are you sure you want to delete this saved question paper?')) return;

    $.ajax({
        url: '<?php echo base_url(); ?>admin/aiexamgenerator/delete_saved_paper_ajax',
        type: 'POST',
        dataType: 'json',
        data: { paper_id: paperId },
        success: function(res) {
            if (res.status === 'success') {
                $(`#row_paper_${paperId}`).fadeOut(200, function() { $(this).remove(); });
            } else {
                alert('Error: ' + res.message);
            }
        }
    });
}

let customLogoSrc = '<?php echo base_url(); ?>uploads/school_content/logo/<?php echo !empty($sch_setting->image) ? $sch_setting->image : "app_logo.png"; ?>';
let brandingConfig = {
    showLogo: true,
    showWatermark: true,
    watermarkText: '<?php echo isset($sch_setting->name) ? addslashes($sch_setting->name) : "SUNRISE INTERNATIONAL SCHOOL"; ?>',
    watermarkOpacity: '0.04',
    showSignatures: true,
    sig1: 'Subject Teacher',
    sig2: 'HOD / Examiner',
    sig3: 'Principal'
};

function previewUploadedLogo(input) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = function(e) {
            customLogoSrc = e.target.result;
            if (currentPaperData) renderPaper(currentPaperData, activeSetName);
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function applyBrandingSettings() {
    brandingConfig.showLogo = $('#chk_show_logo').is(':checked');
    brandingConfig.showWatermark = $('#chk_show_watermark').is(':checked');
    brandingConfig.watermarkText = $('#txt_watermark_text').val();
    brandingConfig.watermarkOpacity = $('#sel_watermark_opacity').val();
    brandingConfig.showSignatures = $('#chk_show_signatures').is(':checked');
    brandingConfig.sig1 = $('#sig_label_1').val();
    brandingConfig.sig2 = $('#sig_label_2').val();
    brandingConfig.sig3 = $('#sig_label_3').val();

    if (currentPaperData) renderPaper(currentPaperData, activeSetName);
}

function renderPaper(data, setName = 'Set A') {
    let sessionName = data.academic_session || '<?php echo isset($current_session) ? htmlspecialchars($current_session) : "2026-2027"; ?>';
    
    if (data.sets && Object.keys(data.sets).length > 1) {
        $('#setSwitcherContainer').show();
        $('.set-tab-btn').removeClass('active');
        $(`.set-tab-btn:contains("${setName}")`).addClass('active');
    } else {
        $('#setSwitcherContainer').hide();
    }

    let activeSet = (data.sets && data.sets[setName]) ? data.sets[setName] : (data.sets ? data.sets['Set A'] : { sections: data.sections || [] });

    let html = `
        ${brandingConfig.showWatermark ? `
            <div class="paper-watermark" style="opacity: ${brandingConfig.watermarkOpacity};">
                ${brandingConfig.watermarkText}
            </div>
        ` : ''}

        <div class="paper-header">
            ${brandingConfig.showLogo ? `<img src="${customLogoSrc}" class="paper-header-logo" alt="School Crest" onerror="this.style.display='none';"><br>` : ''}
            <h2><?php echo isset($sch_setting->name) ? htmlspecialchars($sch_setting->name) : 'SUNRISE INTERNATIONAL SCHOOL'; ?></h2>
            <div style="font-size: 15px; font-weight: bold; margin-bottom: 3px;">
                ${data.paper_title || 'CBSE EXAMINATION'} ${data.sets && Object.keys(data.sets).length > 1 ? `(${setName})` : ''}
            </div>
            <div style="font-size: 13px; font-style: italic;">Academic Session ${sessionName}</div>
            
            <table class="paper-meta-table">
                <tr>
                    <td style="text-align: left;">Class: ${data.class || ''}</td>
                    <td style="text-align: center;">Subject: ${data.subject || ''}</td>
                    <td style="text-align: right;">Max Marks: ${data.max_marks || 80}</td>
                </tr>
                <tr>
                    <td style="text-align: left;">Time Allowed: ${data.time_allowed || '3 Hours'}</td>
                    <td style="text-align: center;">${data.sets && Object.keys(data.sets).length > 1 ? `<span class="badge bg-purple">${setName}</span>` : ''}</td>
                    <td style="text-align: right;">Roll No: [ &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ]</td>
                </tr>
            </table>
        </div>
    `;

    if (data.general_instructions && data.general_instructions.length > 0) {
        html += `
            <div style="margin-bottom: 16px; font-size: 12px; border: 1px dashed #64748b; padding: 8px 12px; border-radius: 4px;">
                <strong>General Instructions:</strong>
                <ol style="margin: 4px 0 0 18px; padding: 0;">
                    ${data.general_instructions.map(inst => `<li>${inst}</li>`).join('')}
                </ol>
            </div>
        `;
    }

    if (activeSet.sections && activeSet.sections.length > 0) {
        activeSet.sections.forEach((sec, secIdx) => {
            html += `
                <div class="paper-section-title">
                    <span>${sec.section_name} - ${sec.description || ''}</span>
                    <button type="button" class="btn btn-default btn-xs no-print" onclick="addQuestionToSection(${secIdx})">
                        <i class="fa fa-plus"></i> Add Question
                    </button>
                </div>
            `;

            if (sec.questions && sec.questions.length > 0) {
                sec.questions.forEach((q, qIdx) => {
                    html += `
                        <div class="q-card" id="q_card_${secIdx}_${qIdx}">
                            <span class="q-num">Q${q.q_no || (qIdx + 1)}.</span>
                            <span class="q-marks">[${q.marks} Mark${q.marks > 1 ? 's' : ''}]</span>
                            
                            <div class="q-actions-bar no-print">
                                <button type="button" class="btn btn-default btn-xs" onclick="openEditModal(${secIdx}, ${qIdx})" title="Edit Question"><i class="fa fa-pencil"></i></button>
                                <button type="button" class="btn btn-warning btn-xs" onclick="regenerateSingleQ(${secIdx}, ${qIdx})" title="Regenerate this question with AI"><i class="fa fa-refresh"></i></button>
                                <button type="button" class="btn btn-danger btn-xs" onclick="deleteQuestion(${secIdx}, ${qIdx})" title="Delete Question"><i class="fa fa-trash"></i></button>
                            </div>

                            <div class="q-text">
                                ${q.case_study_context ? `<div style="background: #f8fafc; padding: 6px 10px; border-left: 3px solid #3b82f6; margin-bottom: 6px; font-style: italic;">${formatQuestionText(q.case_study_context)}</div>` : ''}
                                ${formatQuestionText(q.question_text || '')}
                            </div>
                    `;

                    if (q.diagram_svg) {
                        html += `<div class="diagram-box">${q.diagram_svg}</div>`;
                    }

                    if (q.options) {
                        html += `<div class="q-options">`;
                        for (let optKey in q.options) {
                            html += `<div><strong>(${optKey})</strong> ${q.options[optKey]}</div>`;
                        }
                        html += `</div>`;
                    }

                    if (q.sub_questions && q.sub_questions.length > 0) {
                        html += `<div style="margin-top: 6px; padding-left: 12px;">`;
                        q.sub_questions.forEach(sub => {
                            html += `<div style="margin-bottom: 4px;"><strong>${sub.sub_q}</strong> <span style="float: right; font-weight: bold;">[${sub.marks}M]</span></div>`;
                        });
                        html += `</div>`;
                    }

                    if (q.or_question_text) {
                        html += `
                            <div style="text-align: center; font-weight: bold; margin: 6px 0;">-- OR --</div>
                            <div style="padding-left: 12px;">${q.or_question_text}</div>
                        `;
                    }

                    let solContent = q.explanation || q.answer_key || (q.correct_option ? `Correct Answer: ${q.correct_option}` : '');
                    if (solContent) {
                        html += `
                            <div class="solution-box solution-item" style="${showSolutions ? '' : 'display: none;'}">
                                <strong>Answer / Marking Scheme:</strong> ${solContent}
                            </div>
                        `;
                    }

                    html += `</div>`;
                });
            }
        });
    }

    if (brandingConfig.showSignatures) {
        html += `
            <div class="paper-signature-footer">
                <div class="sig-box">${brandingConfig.sig1}</div>
                <div class="sig-box">${brandingConfig.sig2}</div>
                <div class="sig-box">${brandingConfig.sig3}</div>
            </div>
        `;
    }

    $('#paperContainer').html(html);

    if (window.renderMathInElement) {
        renderMathInElement(document.getElementById('paperContainer'), {
            delimiters: [
                {left: '$$', right: '$$', display: true},
                {left: '$', right: '$', display: false}
            ],
            throwOnError: false
        });
    }
}

function formatQuestionText(text) {
    if (!text) return '';
    let processed = text;
    if (processed.indexOf('|') !== -1) {
        processed = processed.replace(/(?:^|\n)(\|[^\n]+\|\r?\n\|[\s\-:|]+\|\r?\n(?:\|[^\n]+\|\r?\n?)+)/g, function(match) {
            let rows = match.trim().split(/\r?\n/);
            let tableHtml = '<table class="cbse-exam-table">';
            rows.forEach((r, idx) => {
                if (idx === 1 && r.indexOf('---') !== -1) return;
                let cells = r.split('|').map(c => c.trim()).filter((c, i, arr) => i > 0 && i < arr.length - 1);
                tableHtml += '<tr>';
                cells.forEach(c => {
                    tableHtml += (idx === 0) ? `<th>${c}</th>` : `<td>${c}</td>`;
                });
                tableHtml += '</tr>';
            });
            tableHtml += '</table>';
            return tableHtml;
        });

        processed = processed.replace(/([a-zA-Z0-9_\-+]+)\s*\|\s*([^|\n]+(?:\|\s*[^|\n]+)+)/g, function(match, label, rest) {
            if (processed.indexOf('<table') !== -1 && match.indexOf('<table') !== -1) return match;
            let parts = match.split('|').map(p => p.trim());
            if (parts.length >= 3) {
                let tableHtml = '<table class="cbse-exam-table"><tr>';
                parts.forEach((p, idx) => {
                    if (p.indexOf('---') !== -1) return;
                    tableHtml += (idx === 0) ? `<th>${p}</th>` : `<td>${p}</td>`;
                });
                tableHtml += '</tr></table>';
                return tableHtml;
            }
            return match;
        });
    }
    return processed;
}

function switchActiveSet(setName) {
    activeSetName = setName;
    renderPaper(currentPaperData, activeSetName);
}

function openEditModal(secIdx, qIdx) {
    let setObj = (currentPaperData.sets && currentPaperData.sets[activeSetName]) ? currentPaperData.sets[activeSetName] : (currentPaperData.sets ? currentPaperData.sets['Set A'] : { sections: currentPaperData.sections });
    let q = setObj.sections[secIdx].questions[qIdx];

    $('#edit_sec_idx').val(secIdx);
    $('#edit_q_idx').val(qIdx);
    $('#edit_q_text').val(q.question_text || '');
    $('#edit_q_marks').val(q.marks || 1);
    $('#edit_q_correct').val(q.correct_option || '');
    $('#edit_q_explanation').val(q.explanation || q.answer_key || '');

    if (q.options && typeof q.options === 'object') {
        $('#edit_mcq_options_box').show();
        $('#edit_opt_A').val(q.options['A'] || '');
        $('#edit_opt_B').val(q.options['B'] || '');
        $('#edit_opt_C').val(q.options['C'] || '');
        $('#edit_opt_D').val(q.options['D'] || '');
    } else {
        $('#edit_mcq_options_box').hide();
        $('#edit_opt_A').val('');
        $('#edit_opt_B').val('');
        $('#edit_opt_C').val('');
        $('#edit_opt_D').val('');
    }

    $('#modalEditQuestion').modal('show');
}

function saveEditedQuestion() {
    let secIdx = $('#edit_sec_idx').val();
    let qIdx = $('#edit_q_idx').val();

    let setObj = (currentPaperData.sets && currentPaperData.sets[activeSetName]) ? currentPaperData.sets[activeSetName] : (currentPaperData.sets ? currentPaperData.sets['Set A'] : { sections: currentPaperData.sections });
    let q = setObj.sections[secIdx].questions[qIdx];

    q.question_text = $('#edit_q_text').val();
    q.marks = parseInt($('#edit_q_marks').val()) || 1;
    q.correct_option = $('#edit_q_correct').val();
    
    if (q.explanation !== undefined) {
        q.explanation = $('#edit_q_explanation').val();
    } else {
        q.answer_key = $('#edit_q_explanation').val();
    }

    if ($('#edit_mcq_options_box').is(':visible')) {
        if (!q.options) q.options = {};
        q.options['A'] = $('#edit_opt_A').val();
        q.options['B'] = $('#edit_opt_B').val();
        q.options['C'] = $('#edit_opt_C').val();
        q.options['D'] = $('#edit_opt_D').val();
    }

    $('#modalEditQuestion').modal('hide');
    renderPaper(currentPaperData, activeSetName);
}

function regenerateSingleQ(secIdx, qIdx) {
    let setObj = (currentPaperData.sets && currentPaperData.sets[activeSetName]) ? currentPaperData.sets[activeSetName] : (currentPaperData.sets ? currentPaperData.sets['Set A'] : { sections: currentPaperData.sections });
    let q = setObj.sections[secIdx].questions[qIdx];
    let sec = setObj.sections[secIdx];

    let card = $(`#q_card_${secIdx}_${qIdx}`);
    card.css('opacity', '0.4');

    $.ajax({
        url: '<?php echo base_url(); ?>admin/aiexamgenerator/regenerate_single_question_ajax',
        type: 'POST',
        dataType: 'json',
        data: {
            class_name: currentPaperData.class || 'Class 10',
            subject_name: currentPaperData.subject || 'Science',
            chapter: $('#gen_chapter').val() || 'General',
            section_name: sec.section_name,
            question_type: q.question_type || 'singlechoice',
            marks: q.marks || 1,
            difficulty: $('#gen_difficulty').val(),
            language: $('#gen_language').val(),
            api_engine: $('#gen_engine').val(),
            api_key: ''
        },
        success: function(res) {
            card.css('opacity', '1');
            if (res.status === 'success' && res.question) {
                res.question.q_no = q.q_no;
                setObj.sections[secIdx].questions[qIdx] = res.question;
                renderPaper(currentPaperData, activeSetName);
            } else {
                alert('Regenerate Error: ' + (res.message || 'Failed to replace question.'));
            }
        },
        error: function() {
            card.css('opacity', '1');
            alert('Failed to regenerate single question.');
        }
    });
}

function deleteQuestion(secIdx, qIdx) {
    if (!confirm('Are you sure you want to remove this question?')) return;
    let setObj = (currentPaperData.sets && currentPaperData.sets[activeSetName]) ? currentPaperData.sets[activeSetName] : (currentPaperData.sets ? currentPaperData.sets['Set A'] : { sections: currentPaperData.sections });
    setObj.sections[secIdx].questions.splice(qIdx, 1);
    renderPaper(currentPaperData, activeSetName);
}

function addQuestionToSection(secIdx) {
    let setObj = (currentPaperData.sets && currentPaperData.sets[activeSetName]) ? currentPaperData.sets[activeSetName] : (currentPaperData.sets ? currentPaperData.sets['Set A'] : { sections: currentPaperData.sections });
    let newQ = {
        q_no: setObj.sections[secIdx].questions.length + 1,
        question_type: 'descriptive',
        marks: 2,
        question_text: 'Type new question here (Click edit button to modify)...',
        answer_key: 'Answer key details...'
    };
    setObj.sections[secIdx].questions.push(newQ);
    renderPaper(currentPaperData, activeSetName);
}

function exportToWordDocx() {
    if (!currentPaperData) {
        alert('Please select a paper first.');
        return;
    }

    const contentHtml = `
        <!DOCTYPE html>
        <html>
        <head><meta charset="utf-8"><title>${currentPaperData.paper_title || 'CBSE Exam Paper'}</title></head>
        <body>
            ${$('#paperContainer').html()}
        </body>
        </html>
    `;

    if (window.htmlDocx) {
        const converted = htmlDocx.asBlob(contentHtml);
        const url = URL.createObjectURL(converted);
        const a = document.createElement('a');
        a.href = url;
        a.download = `${(currentPaperData.paper_title || 'CBSE_Exam_Paper').replace(/\s+/g, '_')}_${activeSetName}.docx`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    } else {
        alert('Word export engine loading. Please try again.');
    }
}

function toggleSolutions() {
    showSolutions = !showSolutions;
    if (showSolutions) {
        $('.solution-item').show();
        $('#solBtnText').text('Hide Solutions');
    } else {
        $('.solution-item').hide();
        $('#solBtnText').text('Show Solutions');
    }
}

function printCleanPaper(includeSolutions) {
    if (!currentPaperData) {
        alert('Please select a paper first.');
        return;
    }
    
    if (includeSolutions) {
        $('.solution-item').show();
    } else {
        $('.solution-item').hide();
    }
    
    setTimeout(() => {
        window.print();
    }, 150);
}

function saveToQuestionBank() {
    if (!currentPaperData) {
        alert('No questions found.');
        return;
    }

    const classId = currentPaperClassId || $('#gen_class_id').val();
    const subjectId = currentPaperSubjectId || $('#gen_subject_id').val();

    let flatQuestions = [];
    let setObj = (currentPaperData.sets && currentPaperData.sets[activeSetName]) ? currentPaperData.sets[activeSetName] : (currentPaperData.sets ? currentPaperData.sets['Set A'] : { sections: currentPaperData.sections });

    if (setObj.sections) {
        setObj.sections.forEach(sec => {
            if (sec.questions) {
                sec.questions.forEach(q => {
                    flatQuestions.push({
                        question_text: q.question_text || (q.case_study_context ? q.case_study_context : ''),
                        question_type: q.question_type || (q.options ? 'singlechoice' : 'descriptive'),
                        options: q.options || null,
                        correct_option: q.correct_option || null,
                        level: 'medium'
                    });
                });
            }
        });
    }

    if (flatQuestions.length === 0) {
        alert('No questions detected.');
        return;
    }

    if (!confirm(`Save ${flatQuestions.length} questions to the LMS Question Bank?`)) {
        return;
    }

    $.ajax({
        url: '<?php echo base_url(); ?>admin/aiexamgenerator/save_to_question_bank_ajax',
        type: 'POST',
        dataType: 'json',
        data: {
            class_id: classId,
            subject_id: subjectId,
            questions_payload: JSON.stringify(flatQuestions)
        },
        success: function(res) {
            alert(res.status === 'success' ? 'Success: ' + res.message : 'Error: ' + res.message);
        }
    });
}

// --- Bulk Sync All Classes & Subjects Chapters in Background ---
let bulkSyncPairsList = [];
let bulkSyncCurrentIndex = 0;
let isBulkSyncRunning = false;
let bulkSyncCurrentXHR = null;

function openBulkSyncModal() {
    $('#modalBulkSync').modal('show');
    $('#bulkSyncProgressBox').hide();
    $('#bulkSyncLogBox').hide().html('');
    $('#bulkSyncStatus').text('Ready to fetch curriculum chapters for all active school classes and subjects.');
    $('#btnStartBulkSync').prop('disabled', false).html('<i class="fa fa-play"></i> Start Background Sync');
    $('#btnStopBulkSync').hide();
}

function startBulkSyncQueue() {
    const btn = $('#btnStartBulkSync');
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Initializing...');
    $('#btnStopBulkSync').show().prop('disabled', false).html('<i class="fa fa-stop"></i> Stop Sync');
    $('#bulkSyncStatus').text('Retrieving class and subject roster...');
    $('#bulkSyncProgressBox').show();
    $('#bulkSyncProgressBar').css('width', '0%').text('0%').removeClass('progress-bar-success').addClass('progress-bar-striped active');
    $('#bulkSyncLogBox').show().html('<div style="color:#a78bfa;">Starting background curriculum pre-fetch...</div>');

    $.ajax({
        url: '<?php echo base_url(); ?>admin/aiexamgenerator/get_sync_pairs_ajax',
        type: 'POST',
        dataType: 'json',
        success: function(res) {
            if (res.status === 'success' && res.pairs && res.pairs.length > 0) {
                bulkSyncPairsList = res.pairs;
                bulkSyncCurrentIndex = 0;
                isBulkSyncRunning = true;
                processNextBulkSyncPair();
            } else {
                $('#bulkSyncStatus').text('No classes or subjects found to sync.');
                btn.prop('disabled', false).html('<i class="fa fa-play"></i> Start Background Sync');
                $('#btnStopBulkSync').hide();
            }
        },
        error: function() {
            $('#bulkSyncStatus').text('Failed to fetch class and subject roster.');
            btn.prop('disabled', false).html('<i class="fa fa-play"></i> Start Background Sync');
            $('#btnStopBulkSync').hide();
        }
    });
}

function stopBulkSyncQueue() {
    isBulkSyncRunning = false;

    // Immediately abort any running in-flight HTTP request
    if (bulkSyncCurrentXHR) {
        try {
            bulkSyncCurrentXHR.abort();
        } catch(e) {}
        bulkSyncCurrentXHR = null;
    }

    $('#btnStopBulkSync').hide();
    $('#bulkSyncStatus').html('<strong style="color: #dc2626;"><i class="fa fa-pause-circle"></i> Sync process stopped by user.</strong>');
    $('#bulkSyncProgressBar').removeClass('active');
    $('#bulkSyncLogBox').append('<div style="color: #f87171; margin-top: 6px;">⛔ Sync halted. Synced chapters up to this point remain saved in the database.</div>');
    $('#btnStartBulkSync').prop('disabled', false).html('<i class="fa fa-play"></i> Resume Sync');
}

function processNextBulkSyncPair() {
    if (!isBulkSyncRunning) {
        return;
    }

    if (bulkSyncCurrentIndex >= bulkSyncPairsList.length) {
        // Complete
        $('#bulkSyncProgressBar').css('width', '100%').text('100% Complete!').removeClass('active').addClass('progress-bar-success');
        $('#bulkSyncStatus').html('<strong style="color: #16a34a;"><i class="fa fa-check-circle"></i> All class & subject chapters synced and saved in database!</strong>');
        $('#btnStartBulkSync').html('<i class="fa fa-check"></i> Sync Complete').prop('disabled', false);
        $('#btnStopBulkSync').hide();
        $('#bulkSyncLogBox').append('<div style="color: #4ade80; margin-top: 6px;">✔ Completed! All chapter lists permanently cached in database.</div>');
        return;
    }

    const item = bulkSyncPairsList[bulkSyncCurrentIndex];
    const total = bulkSyncPairsList.length;
    const percent = Math.round(((bulkSyncCurrentIndex + 1) / total) * 100);

    $('#bulkSyncProgressBar').css('width', percent + '%').text(`${percent}% (${bulkSyncCurrentIndex + 1}/${total})`);
    $('#bulkSyncStatus').html(`Fetching [${bulkSyncCurrentIndex + 1}/${total}]: <strong>${item.class_name}</strong> - <strong>${item.subject_name}</strong>...`);

    // If already cached and not force reloaded, log and skip quickly
    if (item.is_cached) {
        $('#bulkSyncLogBox').append(`<div>[${bulkSyncCurrentIndex + 1}/${total}] ⚡ ${item.class_name} - ${item.subject_name}: Loaded from Cache</div>`);
        var logElem = document.getElementById('bulkSyncLogBox');
        logElem.scrollTop = logElem.scrollHeight;
        bulkSyncCurrentIndex++;
        setTimeout(processNextBulkSyncPair, 80);
        return;
    }

    const apiEngine = $('#gen_engine').val() || 'gemini';

    bulkSyncCurrentXHR = $.ajax({
        url: '<?php echo base_url(); ?>admin/aiexamgenerator/get_or_fetch_chapters_ajax',
        type: 'POST',
        dataType: 'json',
        data: {
            class_name: item.class_name,
            subject_name: item.subject_name,
            api_engine: apiEngine,
            force_reload: 0
        },
        success: function(res) {
            bulkSyncCurrentXHR = null;
            if (!isBulkSyncRunning) return;
            let count = (res.chapters && res.chapters.length) ? res.chapters.length : 0;
            let modelLabel = res.model_used ? ` [${res.model_used}]` : '';
            if (res.status === 'success') {
                $('#bulkSyncLogBox').append(`<div>[${bulkSyncCurrentIndex + 1}/${total}] ✨ <strong>${item.class_name} - ${item.subject_name}</strong>: Saved ${count} chapters <span style="color: #a78bfa;">${modelLabel}</span></div>`);
            } else {
                $('#bulkSyncLogBox').append(`<div style="color: #f87171;">[${bulkSyncCurrentIndex + 1}/${total}] ✖ ${item.class_name} - ${item.subject_name}: ${res.message || 'Error'}</div>`);
            }
            var logElem = document.getElementById('bulkSyncLogBox');
            logElem.scrollTop = logElem.scrollHeight;
            bulkSyncCurrentIndex++;
            setTimeout(processNextBulkSyncPair, 300); // 300ms pacing between AI queries
        },
        error: function(xhr, textStatus) {
            bulkSyncCurrentXHR = null;
            if (!isBulkSyncRunning || textStatus === 'abort') return;
            $('#bulkSyncLogBox').append(`<div style="color: #f87171;">[${bulkSyncCurrentIndex + 1}/${total}] ✖ ${item.class_name} - ${item.subject_name}: HTTP error, skipping...</div>`);
            bulkSyncCurrentIndex++;
            setTimeout(processNextBulkSyncPair, 300);
        }
    });
}
</script>
