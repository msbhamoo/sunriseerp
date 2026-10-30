<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.css">
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/katex.min.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.8/dist/contrib/auto-render.min.js"></script>
<!-- Include html-docx for direct MS Word download -->
<script src="https://cdn.jsdelivr.net/npm/html-docx-js@0.3.1/dist/html-docx.js"></script>
<!-- Include NCERT Syllabus Catalog -->
<script src="<?php echo base_url(); ?>application/views/cbseexam/ai_question_generator/ncert_curriculum.js"></script>

<style>
.cbse-ai-studio {
    background: #f8fafc;
    min-height: calc(100vh - 120px);
    padding: 20px 0;
}
.cbse-card {
    background: #fff;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    margin-bottom: 24px;
    overflow: hidden;
}
.cbse-card-header {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    color: #fff;
    padding: 16px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.cbse-card-header h4 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}
.cbse-badge-ai {
    background: linear-gradient(135deg, #ec4899 0%, #8b5cf6 100%);
    color: #fff;
    font-size: 11px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 20px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.blueprint-pill {
    cursor: pointer;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    padding: 12px 16px;
    transition: all 0.2s ease;
    background: #fff;
}
.blueprint-pill:hover {
    border-color: #94a3b8;
    transform: translateY(-2px);
}
.blueprint-pill.active {
    border-color: #8b5cf6;
    background: #f5f3ff;
}
.blueprint-pill h5 {
    margin: 0 0 4px 0;
    font-size: 14px;
    font-weight: 700;
    color: #1e293b;
}
.blueprint-pill p {
    margin: 0;
    font-size: 12px;
    color: #64748b;
}
.btn-ai-generate {
    background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%);
    color: #fff;
    border: none;
    font-weight: 600;
    padding: 12px 24px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    transition: all 0.2s ease;
    font-size: 15px;
}
.btn-ai-generate:hover {
    background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 100%);
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(139, 92, 246, 0.4);
}
.paper-preview-container {
    background: #fff;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    padding: 32px 40px;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
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
.paper-watermark.custom-image {
    width: 320px;
    height: 320px;
    opacity: 0.06;
    background-size: contain;
    background-repeat: no-repeat;
    background-position: center;
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
    max-height: 65px;
    margin-bottom: 8px;
    display: inline-block;
}
.paper-signature-footer {
    margin-top: 40px;
    padding-top: 20px;
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    font-size: 13px;
    font-weight: 600;
    page-break-inside: avoid !important;
}
.sig-box {
    text-align: center;
    border-top: 1px solid #334155;
    padding-top: 6px;
    min-width: 140px;
}
.paper-header h2 {
    margin: 0 0 6px 0;
    font-size: 24px;
    font-weight: bold;
    text-transform: uppercase;
}
.paper-meta-table {
    width: 100%;
    margin-top: 12px;
    font-size: 14px;
    font-weight: bold;
}
.paper-section-title {
    background: #f1f5f9;
    padding: 6px 12px;
    font-weight: bold;
    font-size: 15px;
    border-top: 1px solid #000;
    border-bottom: 1px solid #000;
    margin: 20px 0 14px 0;
    text-align: center;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.paper-preview-container table.cbse-exam-table {
    width: auto;
    max-width: 100%;
    margin: 12px 0;
    border-collapse: collapse;
    border: 1.5px solid #000;
}
.paper-preview-container table.cbse-exam-table th,
.paper-preview-container table.cbse-exam-table td {
    border: 1px solid #000;
    padding: 6px 14px;
    text-align: center;
    font-size: 14px;
}
.paper-preview-container table.cbse-exam-table th {
    background: #f1f5f9;
    font-weight: bold;
}
.q-card {
    margin-bottom: 16px;
    position: relative;
    padding-left: 36px;
    padding-right: 70px;
    transition: background 0.15s ease;
}
.q-card:hover {
    background: #f8fafc;
}
.q-card .q-num {
    position: absolute;
    left: 0;
    top: 0;
    font-weight: bold;
    font-size: 15px;
}
.q-card .q-marks {
    float: right;
    font-weight: bold;
    font-size: 14px;
}
.q-card .q-actions-bar {
    position: absolute;
    right: 0;
    top: 0;
    display: flex;
    gap: 4px;
}
.q-options {
    margin-top: 8px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 8px;
    font-size: 14px;
}
.solution-box {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 6px;
    padding: 10px 14px;
    margin-top: 8px;
    font-size: 13px;
    color: #166534;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
}
.diagram-box {
    margin: 12px 0;
    text-align: center;
    background: #fff;
    padding: 10px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    display: inline-block;
}
.history-item {
    padding: 10px 14px;
    border-bottom: 1px solid #e2e8f0;
    cursor: pointer;
    transition: background 0.15s ease;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.history-item:hover {
    background: #f1f5f9;
}
.history-item.active {
    background: #ede9fe;
    border-left: 3px solid #8b5cf6;
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
.set-tab-btn {
    padding: 6px 16px;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    background: #fff;
    cursor: pointer;
    font-weight: 600;
    font-size: 13px;
}
.set-tab-btn.active {
    background: #8b5cf6;
    color: #fff;
    border-color: #7c3aed;
}
.spinner-ai {
    display: inline-block;
    width: 20px;
    height: 20px;
    border: 3px solid rgba(255,255,255,.3);
    border-radius: 50%;
    border-top-color: #fff;
    animation: spin 1s ease-in-out infinite;
}
@keyframes spin {
    to { transform: rotate(360deg); }
}
@media print {
    @page {
        size: A4 portrait;
        margin: 15mm 15mm 15mm 15mm;
    }
    html, body {
        background: #fff !important;
        margin: 0 !important;
        padding: 0 !important;
        height: auto !important;
        min-height: auto !important;
    }
    .main-header, .main-sidebar, .content-header, .nav-tabs-custom, .breadcrumb, .no-print, footer, .control-sidebar {
        display: none !important;
    }
    .content-wrapper {
        margin: 0 !important;
        padding: 0 !important;
        background: #fff !important;
        border: none !important;
    }
    .cbse-ai-studio {
        padding: 0 !important;
        background: #fff !important;
    }
    .col-md-4 {
        display: none !important;
    }
    .col-md-8 {
        width: 100% !important;
        float: none !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    body * {
        visibility: hidden;
    }
    #paperPrintArea, #paperPrintArea * {
        visibility: visible;
    }
    #paperPrintArea {
        position: relative !important;
        left: auto !important;
        top: auto !important;
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
        box-shadow: none !important;
        border: none !important;
        display: block !important;
    }
    .paper-preview-container {
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
    }
    .paper-header {
        page-break-inside: avoid;
    }
    .paper-section-title {
        page-break-after: avoid;
        page-break-inside: avoid;
        background: #f8fafc !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .q-card {
        page-break-inside: avoid !important;
        break-inside: avoid !important;
        margin-bottom: 18px !important;
        padding-right: 0 !important;
    }
    .q-actions-bar {
        display: none !important;
    }
    .solution-box {
        page-break-inside: avoid !important;
        break-inside: avoid !important;
        border: 1px solid #d1d5db !important;
        background: #f9fafb !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
}
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-magic" style="color: #8b5cf6;"></i> <?php echo $this->lang->line('cbse_ai_question_generator'); ?>
            <small>Institutional CBSE Blueprint & NCERT AI Paper Studio</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>cbseexam/exam"><i class="fa fa-dashboard"></i> <?php echo $this->lang->line('cbse_exam'); ?></a></li>
            <li class="active"><?php echo $this->lang->line('cbse_ai_question_generator'); ?></li>
        </ul>
    </section>

    <section class="content cbse-ai-studio">
        <div class="row">
            <!-- Left Side: Config & Saved History Tabs -->
            <div class="col-md-4">
                <div class="nav-tabs-custom" style="border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    <ul class="nav nav-tabs pull-right" style="background: #f1f5f9;">
                        <li><a href="#tab_history" data-toggle="tab"><i class="fa fa-history"></i> Saved Papers (<?php echo count($recent_papers); ?>)</a></li>
                        <li class="active"><a href="#tab_generator" data-toggle="tab"><i class="fa fa-plus-circle"></i> Create New</a></li>
                        <li class="pull-left header" style="font-size: 16px; font-weight: 600; color: #1e293b;"><i class="fa fa-sliders"></i> CBSE Studio</li>
                    </ul>
                    <div class="tab-content" style="padding: 20px;">
                        <!-- Tab 1: Generator Form -->
                        <div class="tab-pane active" id="tab_generator">
                            <form id="aiGeneratorForm">
                                <!-- Class Selection -->
                                <div class="form-group">
                                    <label class="control-label">Select Class <span class="text-danger">*</span></label>
                                    <select id="gen_class_id" name="class_id" class="form-control" onchange="onClassOrSubjectChange()" required>
                                        <option value="">Select Class</option>
                                        <?php if (!empty($classlist)) {
                                            foreach ($classlist as $cls) { ?>
                                                <option value="<?php echo $cls['id']; ?>" data-name="<?php echo htmlspecialchars($cls['class']); ?>">
                                                    <?php echo $cls['class']; ?>
                                                </option>
                                        <?php } } ?>
                                    </select>
                                </div>

                                <!-- Subject Selection -->
                                <div class="form-group">
                                    <label class="control-label">Select Subject <span class="text-danger">*</span></label>
                                    <select id="gen_subject_id" name="subject_id" class="form-control" onchange="onClassOrSubjectChange()" required>
                                        <option value="">Select Subject</option>
                                        <?php if (!empty($subjectlist)) {
                                            foreach ($subjectlist as $sub) { ?>
                                                <option value="<?php echo $sub['id']; ?>" data-name="<?php echo htmlspecialchars($sub['name']); ?>">
                                                    <?php echo $sub['name']; ?> <?php if(!empty($sub['code'])) echo "(". $sub['code'] .")"; ?>
                                                </option>
                                        <?php } } ?>
                                    </select>
                                </div>

                                <!-- NCERT Chapter Browser / Topics Scope -->
                                <div class="form-group">
                                    <label class="control-label">NCERT Chapters / Scope</label>
                                    <div id="ncertChapterBrowserBox" style="display: none; margin-bottom: 8px; max-height: 140px; overflow-y: auto; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; padding: 6px;">
                                        <div style="font-size: 11px; color: #64748b; margin-bottom: 4px;">Click chapters to auto-fill scope:</div>
                                        <div id="ncertChapterBadges"></div>
                                    </div>
                                    <input type="text" id="gen_chapter" class="form-control" placeholder="e.g. Real Numbers, Polynomials, Light Reflection" value="Complete Syllabus">
                                </div>

                                <!-- Blueprint Presets -->
                                <div class="form-group">
                                    <label class="control-label">Exam Blueprint / Total Marks</label>
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

                                <!-- Multi-Set Generator Switch -->
                                <div class="form-group" style="background: #f5f3ff; border: 1px dashed #8b5cf6; padding: 10px 14px; border-radius: 8px;">
                                    <label style="margin: 0; cursor: pointer; display: flex; align-items: center; justify-content: space-between;">
                                        <span><i class="fa fa-clone" style="color: #8b5cf6;"></i> <strong>Anti-Cheating Multi-Sets (Set A, B, C)</strong></span>
                                        <input type="checkbox" id="gen_multi_sets" value="yes">
                                    </label>
                                    <small class="text-muted" style="display: block; margin-top: 4px;">Generates 3 shuffled variations with randomized options and modified numerical values.</small>
                                </div>

                                <!-- Difficulty & Language -->
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Difficulty</label>
                                            <select id="gen_difficulty" class="form-control">
                                                <option value="Standard CBSE (Balanced)" selected>Balanced</option>
                                                <option value="Moderate to Hard (HOTS Focused)">HOTS / Hard</option>
                                                <option value="Foundational / Easy">Easy</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="form-group">
                                            <label>Language</label>
                                            <select id="gen_language" class="form-control">
                                                <option value="English" selected>English</option>
                                                <option value="Hindi">Hindi (हिंदी)</option>
                                                <option value="Bilingual">Bilingual</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bloom's Taxonomy Cognitive Weightage -->
                                <div class="panel panel-default" style="border-radius: 8px; margin-bottom: 15px; border-color: #e2e8f0;">
                                    <div class="panel-heading" style="background: #f8fafc; cursor: pointer; padding: 8px 14px;" data-toggle="collapse" href="#collapseBlooms">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <strong style="font-size: 12px; color: #334155;"><i class="fa fa-graduation-cap"></i> Bloom's Taxonomy Distribution</strong>
                                            <span style="font-size: 11px; color: #8b5cf6;"><i class="fa fa-sliders"></i> Adjust</span>
                                        </div>
                                    </div>
                                    <div id="collapseBlooms" class="panel-collapse collapse">
                                        <div class="panel-body" style="padding: 12px 14px; background: #fff;">
                                            <div style="margin-bottom: 8px;">
                                                <div style="display: flex; justify-content: space-between; font-size: 11px;">
                                                    <span>Remembering & Recall</span>
                                                    <span id="txt_bloom_rem">30%</span>
                                                </div>
                                                <input type="range" id="slider_bloom_rem" min="10" max="60" value="30" oninput="$('#txt_bloom_rem').text(this.value + '%')">
                                            </div>
                                            <div style="margin-bottom: 8px;">
                                                <div style="display: flex; justify-content: space-between; font-size: 11px;">
                                                    <span>Applying & Solving</span>
                                                    <span id="txt_bloom_app">40%</span>
                                                </div>
                                                <input type="range" id="slider_bloom_app" min="10" max="60" value="40" oninput="$('#txt_bloom_app').text(this.value + '%')">
                                            </div>
                                            <div>
                                                <div style="display: flex; justify-content: space-between; font-size: 11px;">
                                                    <span>HOTS & Evaluating</span>
                                                    <span id="txt_bloom_hots">30%</span>
                                                </div>
                                                <input type="range" id="slider_bloom_hots" min="10" max="60" value="30" oninput="$('#txt_bloom_hots').text(this.value + '%')">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Custom Question Types & Count Builder -->
                                <div class="panel panel-default" style="border-radius: 8px; margin-bottom: 15px; border-color: #e2e8f0;">
                                    <div class="panel-heading" style="background: #f8fafc; cursor: pointer; padding: 10px 14px;" data-toggle="collapse" href="#collapseQTypes">
                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                            <strong style="font-size: 13px; color: #334155;"><i class="fa fa-list-ol"></i> Question Types & Counts</strong>
                                            <span style="font-size: 11px; color: #8b5cf6; font-weight: 600;">Customizer <i class="fa fa-chevron-down"></i></span>
                                        </div>
                                    </div>
                                    <div id="collapseQTypes" class="panel-collapse collapse">
                                        <div class="panel-body" style="padding: 12px 14px; background: #fff;">
                                            <div style="font-size: 11px; color: #64748b; margin-bottom: 10px;">Specify exact number of questions per type:</div>
                                            <div class="row" style="margin: 0 -5px;">
                                                <div class="col-xs-6" style="padding: 0 5px; margin-bottom: 8px;">
                                                    <label style="font-size: 11px; margin-bottom: 2px;">MCQs (1M)</label>
                                                    <input type="number" id="dist_mcq" class="form-control input-sm" placeholder="Auto" min="0">
                                                </div>
                                                <div class="col-xs-6" style="padding: 0 5px; margin-bottom: 8px;">
                                                    <label style="font-size: 11px; margin-bottom: 2px;">True / False (1M)</label>
                                                    <input type="number" id="dist_tf" class="form-control input-sm" placeholder="Auto" min="0">
                                                </div>
                                                <div class="col-xs-6" style="padding: 0 5px; margin-bottom: 8px;">
                                                    <label style="font-size: 11px; margin-bottom: 2px;">Fill in Blanks (1M)</label>
                                                    <input type="number" id="dist_fib" class="form-control input-sm" placeholder="Auto" min="0">
                                                </div>
                                                <div class="col-xs-6" style="padding: 0 5px; margin-bottom: 8px;">
                                                    <label style="font-size: 11px; margin-bottom: 2px;">Assertion-Reason (1M)</label>
                                                    <input type="number" id="dist_ar" class="form-control input-sm" placeholder="Auto" min="0">
                                                </div>
                                                <div class="col-xs-6" style="padding: 0 5px; margin-bottom: 8px;">
                                                    <label style="font-size: 11px; margin-bottom: 2px;">Short Answer I (2M)</label>
                                                    <input type="number" id="dist_sa1" class="form-control input-sm" placeholder="Auto" min="0">
                                                </div>
                                                <div class="col-xs-6" style="padding: 0 5px; margin-bottom: 8px;">
                                                    <label style="font-size: 11px; margin-bottom: 2px;">Short Answer II (3M)</label>
                                                    <input type="number" id="dist_sa2" class="form-control input-sm" placeholder="Auto" min="0">
                                                </div>
                                                <div class="col-xs-6" style="padding: 0 5px; margin-bottom: 8px;">
                                                    <label style="font-size: 11px; margin-bottom: 2px;">Long Answer (5M)</label>
                                                    <input type="number" id="dist_la" class="form-control input-sm" placeholder="Auto" min="0">
                                                </div>
                                                <div class="col-xs-6" style="padding: 0 5px; margin-bottom: 8px;">
                                                    <label style="font-size: 11px; margin-bottom: 2px;">Case Study (4M)</label>
                                                    <input type="number" id="dist_case" class="form-control input-sm" placeholder="Auto" min="0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- AI Engine Selection & Custom Key -->
                                <div class="form-group">
                                    <label>AI Engine</label>
                                    <select id="gen_engine" class="form-control">
                                        <option value="gemini" selected>Google Gemini (Auto Free Tier)</option>
                                        <option value="groq">Groq LLaMA-3.3 70B (Ultra Fast Free)</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>API Key (Optional / Direct override)</label>
                                    <input type="password" id="gen_api_key" class="form-control" placeholder="Enter Gemini/Groq API Key (AIzaSy...)">
                                </div>

                                <!-- Generate Button -->
                                <div style="margin-top: 20px;">
                                    <button type="button" id="btnGeneratePaper" class="btn btn-ai-generate btn-block" onclick="startGeneration()">
                                        <i class="fa fa-bolt"></i> Generate & Save Paper
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Tab 2: Saved Papers History -->
                        <div class="tab-pane" id="tab_history">
                            <div style="margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center;">
                                <span class="text-muted" style="font-size: 12px;">Auto-saved papers for later review & printing</span>
                                <button type="button" class="btn btn-default btn-xs" onclick="reloadHistoryList()"><i class="fa fa-refresh"></i> Refresh</button>
                            </div>
                            <div id="historyListContainer" style="max-height: 480px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 8px;">
                                <?php if (!empty($recent_papers)) { 
                                    foreach ($recent_papers as $p) { ?>
                                        <div class="history-item" id="paper_item_<?php echo $p['id']; ?>" onclick="loadSavedPaper(<?php echo $p['id']; ?>, this)">
                                            <div>
                                                <div style="font-weight: 600; font-size: 13px; color: #1e293b;"><?php echo htmlspecialchars($p['paper_title']); ?></div>
                                                <div style="font-size: 11px; color: #64748b;">
                                                    <span class="badge bg-purple" style="font-size: 10px;"><?php echo $p['class_name']; ?></span>
                                                    <span class="badge bg-blue" style="font-size: 10px;"><?php echo $p['subject_name']; ?></span>
                                                    <span>(<?php echo $p['total_marks']; ?> Marks)</span>
                                                </div>
                                                <div style="font-size: 10px; color: #94a3b8; margin-top: 2px;"><?php echo date('d M Y, h:i A', strtotime($p['created_at'])); ?></div>
                                            </div>
                                            <div>
                                                <button type="button" class="btn btn-danger btn-xs" onclick="deleteSavedPaper(<?php echo $p['id']; ?>, event)" title="Delete"><i class="fa fa-trash"></i></button>
                                            </div>
                                        </div>
                                <?php } } else { ?>
                                    <div class="text-center text-muted" style="padding: 30px 10px;">
                                        <i class="fa fa-folder-open-o" style="font-size: 24px; margin-bottom: 6px;"></i>
                                        <div>No saved papers yet. Generated papers will automatically appear here.</div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Paper Preview & Actions -->
            <div class="col-md-8">
                <!-- Action Bar -->
                <div class="cbse-card no-print" id="actionBarCard" style="display: none;">
                    <div class="cbse-card-body" style="padding: 12px 20px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <span class="badge bg-green" id="statusBadge" style="font-size: 13px; padding: 6px 12px;"><i class="fa fa-check"></i> Paper Loaded</span>
                            <!-- Multi-Set Switcher Buttons -->
                            <div id="setSwitcherContainer" style="display: none; gap: 4px;">
                                <button type="button" class="set-tab-btn active" onclick="switchActiveSet('Set A')">Set A</button>
                                <button type="button" class="set-tab-btn" onclick="switchActiveSet('Set B')">Set B</button>
                                <button type="button" class="set-tab-btn" onclick="switchActiveSet('Set C')">Set C</button>
                            </div>
                        </div>
                        <div style="display: flex; gap: 6px; flex-wrap: wrap;">
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
                                <i class="fa fa-file-word-o"></i> Download Word
                            </button>
                            <button type="button" class="btn btn-primary btn-sm" onclick="printCleanPaper(false)">
                                <i class="fa fa-print"></i> Print Paper
                            </button>
                            <button type="button" class="btn btn-success btn-sm" onclick="printCleanPaper(true)">
                                <i class="fa fa-check-square-o"></i> Print Solutions
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div class="cbse-card" id="emptyStateCard">
                    <div class="cbse-card-body text-center" style="padding: 60px 20px;">
                        <div style="width: 80px; height: 80px; margin: 0 auto 16px; background: #f3e8ff; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-file-text-o" style="font-size: 36px; color: #8b5cf6;"></i>
                        </div>
                        <h3 style="font-weight: 600; color: #1e293b;">CBSE AI Exam Studio Ready</h3>
                        <p style="color: #64748b; max-width: 460px; margin: 8px auto 20px;">
                            Select your Class, Subject, and NCERT Chapters from the left panel. Create custom question counts, Bloom's cognitive weightage, or 3 anti-cheating sets with 1 click.
                        </p>
                    </div>
                </div>

                <!-- Loading State -->
                <div class="cbse-card" id="loadingStateCard" style="display: none;">
                    <div class="cbse-card-body text-center" style="padding: 80px 20px;">
                        <div class="spinner-ai" style="border-top-color: #8b5cf6; width: 44px; height: 44px; border-width: 4px;"></div>
                        <h4 style="margin-top: 20px; font-weight: 600; color: #1e293b;">Generating CBSE Question Paper...</h4>
                        <p style="color: #64748b;">Synthesizing questions, embedding diagrams, structuring sections, and formatting marking keys...</p>
                    </div>
                </div>

                <!-- Generated Paper Container -->
                <div id="paperPrintArea" style="display: none;">
                    <div class="paper-preview-container" id="paperContainer">
                        <!-- Content Injected Dynamically -->
                    </div>
                </div>
            </div>
        </div>
    </section>
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
                <!-- School Logo Toggle & Upload -->
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

                <!-- Watermark Settings -->
                <div class="form-group">
                    <label style="display: flex; justify-content: space-between; align-items: center;">
                        <span><i class="fa fa-shield"></i> <strong>Enable Background Watermark</strong></span>
                        <input type="checkbox" id="chk_show_watermark" checked onchange="applyBrandingSettings()">
                    </label>
                    <div class="row" style="margin-top: 8px;">
                        <div class="col-xs-8">
                            <input type="text" id="txt_watermark_text" class="form-control" value="<?php echo isset($sch_setting->name) ? htmlspecialchars($sch_setting->name) : 'SUNRISE INTERNATIONAL SCHOOL'; ?>" placeholder="Watermark Text (e.g. SCHOOL NAME)" oninput="applyBrandingSettings()">
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

                <!-- Signatures Footer -->
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

<script>
let currentPaperData = null;
let currentPaperClassId = null;
let currentPaperSubjectId = null;
let activeSetName = 'Set A';
let showSolutions = false;

// Trigger NCERT chapter badge loading on Class / Subject select
function onClassOrSubjectChange() {
    const className = $('#gen_class_id option:selected').data('name') || '';
    const subjectName = $('#gen_subject_id option:selected').data('name') || '';

    if (window.NCERT_SYLLABUS_CATALOG && NCERT_SYLLABUS_CATALOG[className] && NCERT_SYLLABUS_CATALOG[className][subjectName]) {
        const chapters = NCERT_SYLLABUS_CATALOG[className][subjectName];
        let badgesHtml = '';
        chapters.forEach(ch => {
            badgesHtml += `<span class="chapter-badge" onclick="toggleChapterBadge(this, '${ch.replace(/'/g, "\\'")}')">${ch}</span>`;
        });
        $('#ncertChapterBadges').html(badgesHtml);
        $('#ncertChapterBrowserBox').show();
    } else {
        $('#ncertChapterBrowserBox').hide();
    }
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
    const apiKey = $('#gen_api_key').val();
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

    $('#emptyStateCard').hide();
    $('#paperPrintArea').hide();
    $('#actionBarCard').hide();
    $('#loadingStateCard').show();
    $('#btnGeneratePaper').prop('disabled', true).html('<span class="spinner-ai"></span> Generating...');

    $.ajax({
        url: '<?php echo base_url(); ?>cbseexam/aiquestiongenerator/generate_paper_ajax',
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
            api_key: apiKey
        },
        success: function(res) {
            $('#loadingStateCard').hide();
            $('#btnGeneratePaper').prop('disabled', false).html('<i class="fa fa-bolt"></i> Generate & Save Paper');

            if (res.status === 'success' && res.data) {
                currentPaperData = res.data;
                activeSetName = 'Set A';
                renderPaper(currentPaperData, activeSetName);
                $('#statusBadge').html('<i class="fa fa-check"></i> Paper Generated & Saved');
                $('#actionBarCard').show();
                $('#paperPrintArea').show();
                reloadHistoryList();
            } else {
                alert('Generation Error: ' + (res.message || 'Unknown error occurred.'));
                $('#emptyStateCard').show();
            }
        },
        error: function(xhr, status, err) {
            $('#loadingStateCard').hide();
            $('#emptyStateCard').show();
            $('#btnGeneratePaper').prop('disabled', false).html('<i class="fa fa-bolt"></i> Generate & Save Paper');
            alert('Request Failed: ' + err);
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
    
    // Check if multi-sets present
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
            <div style="font-size: 16px; font-weight: bold; margin-bottom: 4px;">
                ${data.paper_title || 'CBSE EXAMINATION'} ${data.sets && Object.keys(data.sets).length > 1 ? `(${setName})` : ''}
            </div>
            <div style="font-size: 14px; font-style: italic;">Academic Session ${sessionName}</div>
            
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
            <div style="margin-bottom: 20px; font-size: 13px; border: 1px dashed #64748b; padding: 10px 14px; border-radius: 4px;">
                <strong>General Instructions:</strong>
                <ol style="margin: 4px 0 0 20px; padding: 0;">
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
                                ${q.case_study_context ? `<div style="background: #f8fafc; padding: 8px 12px; border-left: 3px solid #3b82f6; margin-bottom: 8px; font-style: italic;">${formatQuestionText(q.case_study_context)}</div>` : ''}
                                ${formatQuestionText(q.question_text || '')}
                            </div>
                    `;

                    // SVG Diagram Rendering
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
                        html += `<div style="margin-top: 8px; padding-left: 14px;">`;
                        q.sub_questions.forEach(sub => {
                            html += `<div style="margin-bottom: 6px;"><strong>${sub.sub_q}</strong> <span style="float: right; font-weight: bold;">[${sub.marks}M]</span></div>`;
                        });
                        html += `</div>`;
                    }

                    if (q.or_question_text) {
                        html += `
                            <div style="text-align: center; font-weight: bold; margin: 8px 0;">-- OR --</div>
                            <div style="padding-left: 14px;">${q.or_question_text}</div>
                        `;
                    }

                    // Solution Box
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

    // Teacher & Examiner Signature Footer
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
    
    // Check if text contains pipe-delimited table (e.g. | m | 1 | 2 | or inline table)
    // Handle multiline or inline pipe tables
    let processed = text;

    // Convert pipe tables with header/separator lines: e.g. | A | B |\n|---|---|\n| 1 | 2 |
    if (processed.indexOf('|') !== -1) {
        // First normalize inline pipe lines that are run together
        // If line has multiple | and dashes like : m | 1 | 2 | 3 ... --- | ---
        processed = processed.replace(/(?:^|\n)(\|[^\n]+\|\r?\n\|[\s\-:|]+\|\r?\n(?:\|[^\n]+\|\r?\n?)+)/g, function(match) {
            let rows = match.trim().split(/\r?\n/);
            let tableHtml = '<table class="cbse-exam-table">';
            rows.forEach((r, idx) => {
                if (idx === 1 && r.indexOf('---') !== -1) return; // Skip separator line
                let cells = r.split('|').map(c => c.trim()).filter((c, i, arr) => i > 0 && i < arr.length - 1);
                tableHtml += '<tr>';
                cells.forEach(c => {
                    if (idx === 0) {
                        tableHtml += `<th>${c}</th>`;
                    } else {
                        tableHtml += `<td>${c}</td>`;
                    }
                });
                tableHtml += '</tr>';
            });
            tableHtml += '</table>';
            return tableHtml;
        });

        // Also handle single-line pseudo tables: e.g. m | 1 | 2 | 3 | 4 ... --- | --- | --- ... m+7 | ... | ...
        processed = processed.replace(/([a-zA-Z0-9_\-+]+)\s*\|\s*([^|\n]+(?:\|\s*[^|\n]+)+)/g, function(match, label, rest) {
            // If already inside <table>, don't re-wrap
            if (processed.indexOf('<table') !== -1 && match.indexOf('<table') !== -1) return match;
            
            let parts = match.split('|').map(p => p.trim());
            // If at least 3 parts, render as neat small exam table
            if (parts.length >= 3) {
                let tableHtml = '<table class="cbse-exam-table"><tr>';
                parts.forEach((p, idx) => {
                    if (p.indexOf('---') !== -1) return;
                    if (idx === 0) {
                        tableHtml += `<th>${p}</th>`;
                    } else {
                        tableHtml += `<td>${p}</td>`;
                    }
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

// In-Place Question Editing
function openEditModal(secIdx, qIdx) {
    let setObj = (currentPaperData.sets && currentPaperData.sets[activeSetName]) ? currentPaperData.sets[activeSetName] : (currentPaperData.sets ? currentPaperData.sets['Set A'] : { sections: currentPaperData.sections });
    let q = setObj.sections[secIdx].questions[qIdx];

    $('#edit_sec_idx').val(secIdx);
    $('#edit_q_idx').val(qIdx);
    $('#edit_q_text').val(q.question_text || '');
    $('#edit_q_marks').val(q.marks || 1);
    $('#edit_q_correct').val(q.correct_option || '');
    $('#edit_q_explanation').val(q.explanation || q.answer_key || '');

    // If question has options (MCQ / Assertion-Reason)
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

    // Save options if MCQ box was visible
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

// Single Question AI Regenerator
function regenerateSingleQ(secIdx, qIdx) {
    let setObj = (currentPaperData.sets && currentPaperData.sets[activeSetName]) ? currentPaperData.sets[activeSetName] : (currentPaperData.sets ? currentPaperData.sets['Set A'] : { sections: currentPaperData.sections });
    let q = setObj.sections[secIdx].questions[qIdx];
    let sec = setObj.sections[secIdx];

    let card = $(`#q_card_${secIdx}_${qIdx}`);
    card.css('opacity', '0.4');

    $.ajax({
        url: '<?php echo base_url(); ?>cbseexam/aiquestiongenerator/regenerate_single_question_ajax',
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
            api_key: $('#gen_api_key').val()
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

// 1-Click Export to MS Word (.docx)
function exportToWordDocx() {
    if (!currentPaperData) {
        alert('Please generate a paper first.');
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
        alert('Word export engine loading. Please try again in 2 seconds.');
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
        alert('Please generate or select a paper first.');
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
        alert('No generated questions found to save.');
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

    if (!confirm(`Are you sure you want to save ${flatQuestions.length} questions to the LMS Question Bank?`)) {
        return;
    }

    $.ajax({
        url: '<?php echo base_url(); ?>cbseexam/aiquestiongenerator/save_to_question_bank_ajax',
        type: 'POST',
        dataType: 'json',
        data: {
            class_id: classId,
            subject_id: subjectId,
            questions_payload: JSON.stringify(flatQuestions)
        },
        success: function(res) {
            if (res.status === 'success') {
                alert('Success: ' + res.message);
            } else {
                alert('Error: ' + res.message);
            }
        },
        error: function(xhr, status, err) {
            alert('Failed to save questions: ' + err);
        }
    });
}

function loadSavedPaper(paperId, elem) {
    $('.history-item').removeClass('active');
    if (elem) $(elem).addClass('active');

    $('#emptyStateCard').hide();
    $('#loadingStateCard').show();
    $('#paperPrintArea').hide();
    $('#actionBarCard').hide();

    $.ajax({
        url: '<?php echo base_url(); ?>cbseexam/aiquestiongenerator/get_saved_paper_ajax',
        type: 'POST',
        dataType: 'json',
        data: { paper_id: paperId },
        success: function(res) {
            $('#loadingStateCard').hide();
            if (res.status === 'success' && res.data) {
                currentPaperData = res.data;
                currentPaperClassId = res.paper_info.class_id;
                currentPaperSubjectId = res.paper_info.subject_id;
                activeSetName = 'Set A';
                renderPaper(currentPaperData, activeSetName);
                $('#statusBadge').html('<i class="fa fa-history"></i> Loaded from Archive');
                $('#actionBarCard').show();
                $('#paperPrintArea').show();
            } else {
                alert('Error loading paper: ' + res.message);
                $('#emptyStateCard').show();
            }
        },
        error: function() {
            $('#loadingStateCard').hide();
            $('#emptyStateCard').show();
            alert('Failed to load saved paper.');
        }
    });
}

function deleteSavedPaper(paperId, event) {
    event.stopPropagation();
    if (!confirm('Are you sure you want to delete this saved question paper?')) return;

    $.ajax({
        url: '<?php echo base_url(); ?>cbseexam/aiquestiongenerator/delete_saved_paper_ajax',
        type: 'POST',
        dataType: 'json',
        data: { paper_id: paperId },
        success: function(res) {
            if (res.status === 'success') {
                $(`#paper_item_${paperId}`).fadeOut(200, function() { $(this).remove(); });
            } else {
                alert('Error: ' + res.message);
            }
        }
    });
}

function reloadHistoryList() {
    $.ajax({
        url: '<?php echo base_url(); ?>cbseexam/aiquestiongenerator/get_saved_papers_list_ajax',
        type: 'GET',
        dataType: 'json',
        success: function(res) {
            if (res.status === 'success' && res.list) {
                let html = '';
                if (res.list.length > 0) {
                    res.list.forEach(p => {
                        html += `
                            <div class="history-item" id="paper_item_${p.id}" onclick="loadSavedPaper(${p.id}, this)">
                                <div>
                                    <div style="font-weight: 600; font-size: 13px; color: #1e293b;">${p.paper_title}</div>
                                    <div style="font-size: 11px; color: #64748b;">
                                        <span class="badge bg-purple" style="font-size: 10px;">${p.class_name}</span>
                                        <span class="badge bg-blue" style="font-size: 10px;">${p.subject_name}</span>
                                        <span>(${p.total_marks} Marks)</span>
                                    </div>
                                    <div style="font-size: 10px; color: #94a3b8; margin-top: 2px;">${p.created_at}</div>
                                </div>
                                <div>
                                    <button type="button" class="btn btn-danger btn-xs" onclick="deleteSavedPaper(${p.id}, event)" title="Delete"><i class="fa fa-trash"></i></button>
                                </div>
                            </div>
                        `;
                    });
                } else {
                    html = '<div class="text-center text-muted" style="padding: 30px 10px;"><i class="fa fa-folder-open-o" style="font-size: 24px; margin-bottom: 6px;"></i><div>No saved papers yet.</div></div>';
                }
                $('#historyListContainer').html(html);
            }
        }
    });
}
</script>
