<style>
/* Modern UX Styling for Generate Admit Card */
:root {
    --primary: #4f46e5;
    --primary-hover: #4338ca;
    --surface: #ffffff;
    --background: #f8fafc;
    --border: #e2e8f0;
    --text-main: #0f172a;
    --text-muted: #64748b;
    --success: #10b981;
    --danger: #ef4444;
}

.modern-card {
    background: var(--surface);
    border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
    border: 1px solid var(--border);
    margin-bottom: 24px;
    overflow: hidden;
}

.modern-header {
    padding: 16px 24px;
    border-bottom: 1px solid var(--border);
    background: #fdfdfd;
}

.modern-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--text-main);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}

.modern-body {
    padding: 24px;
}

.custom-input {
    border-radius: 8px;
    border: 1px solid var(--border);
    padding: 10px 14px;
    transition: all 0.2s ease;
    box-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
}

.custom-input:focus {
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

/* Toggle Switch */
.switch {
  position: relative;
  display: inline-block;
  width: 44px;
  height: 24px;
}
.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}
.slider {
  position: absolute;
  cursor: pointer;
  top: 0; left: 0; right: 0; bottom: 0;
  background-color: #cbd5e1;
  transition: .3s;
  border-radius: 24px;
}
.slider:before {
  position: absolute;
  content: "";
  height: 18px;
  width: 18px;
  left: 3px;
  bottom: 3px;
  background-color: white;
  transition: .3s;
  border-radius: 50%;
  box-shadow: 0 1px 3px rgba(0,0,0,0.2);
}
input:checked + .slider {
  background-color: var(--primary);
}
input:checked + .slider:before {
  transform: translateX(20px);
}

/* Action Bar Top */
.action-bar-top {
    background: var(--surface);
    border-bottom: 1px solid var(--border);
    padding: 16px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 15px;
}

.btn-premium {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-hover) 100%);
    color: white;
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 500;
    box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.3);
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-premium:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 8px -1px rgba(79, 70, 229, 0.4);
    color: white;
}
.btn-premium:disabled {
    background: #94a3b8;
    transform: none;
    box-shadow: none;
    cursor: not-allowed;
}

/* Table styling */
.modern-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}
.modern-table th {
    background: #f8fafc;
    color: var(--text-muted);
    font-weight: 600;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 12px 16px;
    border-bottom: 1px solid var(--border);
}
.modern-table td {
    padding: 14px 16px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}
.modern-table tbody tr {
    transition: background-color 0.2s ease;
    cursor: pointer;
}
.modern-table tbody tr:hover {
    background-color: #f8fafc;
}

.badge-soft {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}
.badge-blue { background: #e0f2fe; color: #0369a1; }
.badge-pink { background: #fce7f3; color: #be185d; }
.badge-green { background: #dcfce7; color: #15803d; }
.badge-gray { background: #f1f5f9; color: #475569; }

.empty-state {
    text-align: center;
    padding: 60px 20px;
}
.empty-state i {
    font-size: 48px;
    color: #cbd5e1;
    margin-bottom: 16px;
}
.empty-state h3 {
    margin: 0 0 8px;
    color: var(--text-main);
    font-size: 18px;
}
.empty-state p {
    color: var(--text-muted);
    margin: 0;
}

/* Hide default checkbox and use custom */
.custom-cb {
    width: 18px;
    height: 18px;
    border-radius: 4px;
    border: 2px solid #cbd5e1;
    appearance: none;
    outline: none;
    cursor: pointer;
    position: relative;
    transition: all 0.2s;
}
.custom-cb:checked {
    background-color: var(--primary);
    border-color: var(--primary);
}
.custom-cb:checked::after {
    content: '\2714';
    font-size: 12px;
    color: white;
    position: absolute;
    top: -1px;
    left: 2px;
}

.content-wrapper { padding-bottom: 30px; }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-id-card-o"></i> Generate Admit Card</h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <!-- Step 1: Filters -->
                <div class="modern-card">
                    <div class="modern-header">
                        <h3 class="modern-title"><i class="fa fa-filter"></i> Step 1: Filter Students</h3>
                    </div>
                    <div class="modern-body" style="padding-bottom: 0;">
                        <form role="form" action="<?php echo site_url('cbseexam/cbseadmitcardbulk/generate') ?>" method="post">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Exam <small class="req"> *</small></label>
                                        <select id="exam_id" name="exam_id" class="form-control custom-input" required>
                                            <option value="">Select Exam</option>
                                            <?php foreach ($getexamlist as $exam) { ?>
                                                <option value="<?php echo $exam->id ?>" <?php echo (set_value('exam_id', $exam_id) == $exam->id) ? "selected" : ""; ?>>
                                                    <?php echo $exam->name ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('class'); ?></label>
                                        <select id="class_id" name="class_id" class="form-control custom-input">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($classlist as $class) { ?>
                                                <option value="<?php echo $class['id'] ?>" <?php echo (set_value('class_id', $class_id) == $class['id']) ? "selected" : ""; ?>>
                                                    <?php echo $class['class'] ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('section'); ?></label>
                                        <select id="section_id" name="section_id" class="form-control custom-input">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group" style="margin-top: 24px;">
                                        <button type="submit" class="btn btn-premium w-100" style="width:100%; justify-content:center;">
                                            <i class="fa fa-search"></i> Search
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Missing Active Template Warning -->
                <?php if (empty($get_active_admitcard)) { ?>
                    <div class="alert alert-danger modern-card p-4">
                        <i class="fa fa-exclamation-triangle"></i> 
                        <strong>Warning:</strong> No active Admit Card template found. Please go to CBSE Examination -> Design Admit Card and activate a template before generating.
                    </div>
                <?php } ?>

                <!-- Summary Dashboard -->
                <?php if (isset($summary_data) && !empty($summary_data)) { 
                    $total_students_all = 0;
                    $total_generated_all = 0;
                    $total_missing_all = 0;
                    foreach ($summary_data as $summary) {
                        $total_students_all += $summary['total_students'];
                        $total_generated_all += $summary['generated_count'];
                        $total_missing_all += $summary['missing_count'];
                    }
                    $overall_progress = ($total_students_all > 0) ? round(($total_generated_all / $total_students_all) * 100) : 0;
                ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="info-box" style="background:#fff; border:1px solid var(--border); border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
                                <span class="info-box-icon" style="background:#e0f2fe; color:#0369a1; border-radius:8px 0 0 8px;"><i class="fa fa-users"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text text-muted" style="text-transform:uppercase; font-size:12px; font-weight:600;">Total Students</span>
                                    <span class="info-box-number" style="font-size:24px; color:var(--text-main);"><?php echo $total_students_all; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box" style="background:#fff; border:1px solid var(--border); border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
                                <span class="info-box-icon" style="background:#dcfce7; color:#15803d; border-radius:8px 0 0 8px;"><i class="fa fa-check-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text text-muted" style="text-transform:uppercase; font-size:12px; font-weight:600;">Generated</span>
                                    <span class="info-box-number" style="font-size:24px; color:var(--text-main);"><?php echo $total_generated_all; ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box" style="background:#fff; border:1px solid var(--border); border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
                                <span class="info-box-icon" style="background:#fce7f3; color:#be185d; border-radius:8px 0 0 8px;"><i class="fa fa-exclamation-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text text-muted" style="text-transform:uppercase; font-size:12px; font-weight:600;">Pending</span>
                                    <span class="info-box-number" style="font-size:24px; color:var(--text-main);"><?php echo $total_missing_all; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modern-card">
                        <div class="modern-header" style="display:flex; justify-content:space-between; align-items:center;">
                            <h3 class="modern-title"><i class="fa fa-pie-chart"></i> Class & Section Summary (<?php echo $overall_progress; ?>% Complete)</h3>
                            
                            <!-- Global Actions -->
                            <div style="display:flex; gap:10px;">
                                <form action="<?php echo site_url('cbseexam/cbseadmitcardbulk/download_all'); ?>" method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to download all generated admit cards? This may take some time depending on the size.');">
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
                                    <!-- <button type="submit" class="btn btn-primary btn-sm" style="border-radius: 4px;">
                                        <i class="fa fa-download"></i> Download All
                                    </button> -->
                                </form>

                                <?php if ($total_generated_all > 0) { ?>
                                <form action="<?php echo site_url('cbseexam/cbseadmitcardbulk/reset_generated'); ?>" method="post" style="display:inline;" onsubmit="return confirm('DANGER: Are you sure you want to reset all generated admit cards for this exam? This will erase all assigned roll numbers.');">
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" style="border-radius: 4px;">
                                        <i class="fa fa-trash"></i> Reset All
                                    </button>
                                </form>
                                <?php } ?>

                                <!-- Bulk Generate All Missing (Entire Exam) -->
                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#generateAllModal" style="border-radius: 4px;">
                                    <i class="fa fa-magic"></i> Auto-Generate All Missing
                                </button>
                            </div>
                        </div>
                        <div class="modern-body p-0">
                            <div class="table-responsive">
                                <table class="modern-table">
                                    <thead>
                                        <tr>
                                            <th>Class (Section)</th>
                                            <th style="text-align:center;">Total</th>
                                            <th style="text-align:center;">Generated</th>
                                            <th style="text-align:center;">Pending</th>
                                            <th style="width:30%;">Progress</th>
                                            <th style="text-align:right;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($summary_data as $row) { 
                                            $progress = ($row['total_students'] > 0) ? round(($row['generated_count'] / $row['total_students']) * 100) : 0;
                                            $progress_color = $progress == 100 ? '#10b981' : ($progress > 50 ? '#3b82f6' : '#f59e0b');
                                        ?>
                                            <tr>
                                                <td style="font-weight:600; color:var(--text-main);"><?php echo $row['class'] . ' (' . $row['section'] . ')'; ?></td>
                                                <td style="text-align:center;"><span class="badge-soft badge-gray"><?php echo $row['total_students']; ?></span></td>
                                                <td style="text-align:center;"><span class="badge-soft badge-green"><?php echo $row['generated_count']; ?></span></td>
                                                <td style="text-align:center;">
                                                    <?php if ($row['missing_count'] > 0) { ?>
                                                        <span class="badge-soft badge-pink"><?php echo $row['missing_count']; ?></span>
                                                    <?php } else { ?>
                                                        <span class="badge-soft badge-gray">0</span>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <div style="display:flex; align-items:center; gap:10px;">
                                                        <div style="flex:1; height:8px; background:#e2e8f0; border-radius:4px; overflow:hidden;">
                                                            <div style="height:100%; width:<?php echo $progress; ?>%; background:<?php echo $progress_color; ?>; transition:width 0.5s ease;"></div>
                                                        </div>
                                                        <span style="font-size:12px; font-weight:600; color:var(--text-muted); width:35px;"><?php echo $progress; ?>%</span>
                                                    </div>
                                                </td>
                                                <td style="text-align:right; display:flex; gap:5px; justify-content:flex-end;">
                                                    <!-- View Students -->
                                                    <button type="button" class="btn btn-default btn-xs" onclick="viewClassSection(<?php echo $row['class_id']; ?>, <?php echo $row['section_id']; ?>)" title="View Students">
                                                        <i class="fa fa-eye text-primary"></i> View
                                                    </button>
                                                    
                                                    <!-- Generate for this section modal trigger -->
                                                    <?php if ($row['missing_count'] > 0) { ?>
                                                        <button type="button" class="btn btn-success btn-xs" style="height:22px; padding:2px 8px;" onclick="openSectionGenerateModal(<?php echo $row['class_id']; ?>, <?php echo $row['section_id']; ?>, '<?php echo htmlspecialchars($row['class'] . ' (' . $row['section'] . ')'); ?>')">
                                                            <i class="fa fa-magic"></i> Generate
                                                        </button>
                                                    <?php } else { ?>
                                                        <span class="label label-success" style="padding:4px 6px;"><i class="fa fa-check"></i> Complete</span>
                                                    <?php } ?>
                                                    
                                                    <!-- Section Level Reset & Download -->
                                                    <?php if ($row['generated_count'] > 0) { ?>
                                                        <form action="<?php echo site_url('cbseexam/cbseadmitcardbulk/download_all'); ?>" method="post" style="display:inline;" onsubmit="return confirm('Download admit cards for this section?');">
                                                            <?php echo $this->customlib->getCSRF(); ?>
                                                            <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
                                                            <input type="hidden" name="class_id" value="<?php echo $row['class_id']; ?>">
                                                            <input type="hidden" name="section_id" value="<?php echo $row['section_id']; ?>">
                                                            <button type="submit" class="btn btn-primary btn-xs" style="height:22px; padding:2px 5px;" title="Download Generated">
                                                                <i class="fa fa-download"></i>
                                                            </button>
                                                        </form>
                                                        <form action="<?php echo site_url('cbseexam/cbseadmitcardbulk/reset_generated'); ?>" method="post" style="display:inline;" onsubmit="return confirm('DANGER: Reset all generated admit cards for this section?');">
                                                            <?php echo $this->customlib->getCSRF(); ?>
                                                            <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
                                                            <input type="hidden" name="class_id" value="<?php echo $row['class_id']; ?>">
                                                            <input type="hidden" name="section_id" value="<?php echo $row['section_id']; ?>">
                                                            <button type="submit" class="btn btn-danger btn-xs" style="height:22px; padding:2px 5px;" title="Reset Generated">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    
                    <script>
                        function viewClassSection(classId, sectionId) {
                            $('#class_id').val(classId).trigger('change');
                            setTimeout(function() {
                                $('#section_id').val(sectionId);
                                $('#class_id').closest('form').submit();
                            }, 500);
                        }
                    </script>
                <?php } ?>

                <!-- Step 2: Student List & Actions -->
                <?php if (isset($student_list) && !empty($student_list)) { ?>
                    <div class="modern-card">
                        <div class="modern-header" style="display:flex; justify-content:space-between; align-items:center;">
                            <h3 class="modern-title"><i class="fa fa-users"></i> Step 2: Select & Generate</h3>
                            
                            <!-- Bulk Generate All Missing Modal Trigger -->
                            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#generateAllModal">
                                <i class="fa fa-magic"></i> Auto-Generate All Missing
                            </button>
                        </div>
                        
                        <div class="modern-body p-0">
                            <form method="post" action="<?php echo base_url('cbseexam/cbseadmitcardbulk/save_and_download') ?>" id="generateCard">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <input type="hidden" name="admitcard_template" value="<?php echo isset($get_active_admitcard->id) ? $get_active_admitcard->id : 0; ?>">
                                <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
                                
                                <!-- Top Action Bar -->
                                <div class="action-bar-top" id="topActionBar">
                                    <div style="display:flex; align-items:center; gap: 16px; flex-wrap: wrap;">
                                        <div style="font-weight:600; color:var(--primary);">
                                            <span id="selectedCount">0</span> Students Selected
                                        </div>
                                        <div style="display:flex; align-items:center; gap: 6px;">
                                            <label style="margin:0; font-weight:600; color:var(--text-main); font-size:12.5px;">Roll No Source:</label>
                                            <select name="roll_type" id="top_bar_roll_type" class="form-control custom-input" style="width:190px; height:34px; padding:4px 8px; font-size:12.5px;" onchange="toggleTopBarSeriesInput(this.value)">
                                                <option value="class_roll">Use Class Roll No</option>
                                                <option value="series">Custom Series</option>
                                            </select>
                                        </div>
                                        <div id="top_bar_series_wrapper" style="display:none; align-items:center; gap: 6px;">
                                            <label style="margin:0; font-weight:500; color:var(--text-muted); font-size:12px;">Start Series:</label>
                                            <input type="number" name="series" id="top_bar_series_input" class="form-control custom-input" style="width:110px; height:34px; padding:4px 8px;" placeholder="e.g. 1001">
                                        </div>
                                        <div style="display:flex; align-items:center; gap: 8px; margin-left: 6px;">
                                            <span style="font-weight:500; color:var(--text-muted); font-size:12.5px;">Timetable</span>
                                            <label class="switch" style="margin:0;">
                                                <input type="checkbox" name="show_timetable" value="1" checked>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div style="display:flex; gap: 10px;">
                                        <button class="btn btn-default" type="button" id="bulkGenerateBtn" onclick="submitGenerate()" disabled style="border-radius:8px; padding:8px 18px; font-weight:600;">
                                            <i class="fa fa-cogs"></i> Generate / Regenerate
                                        </button>
                                        <button class="btn-premium" type="button" id="bulkDownloadBtn" onclick="submitDownload()" disabled>
                                            <i class="fa fa-download"></i> Download All Generated
                                        </button>
                                    </div>
                                </div>

                                <div class="table-responsive">
                                    <table class="modern-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 40px; text-align:center;">
                                                    <input type="checkbox" id="select_all" class="custom-cb">
                                                </th>
                                                <th>Class Roll No</th>
                                                <th>Admit Card Roll No</th>
                                                <th>Student Name</th>
                                                <th>Class (Sec)</th>
                                                <th>Gender</th>
                                                <th>Category</th>
                                                <th style="text-align:right;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($student_list as $student) { 
                                                $has_roll = !empty($student['admit_roll_no']);
                                            ?>
                                                <tr class="student-row">
                                                    <td style="text-align:center;">
                                                        <input type="checkbox" class="custom-cb student-cb" name="cbse_exam_student_id[]" value="<?php echo $student['cbse_exam_student_id']; ?>">
                                                    </td>
                                                    <td>
                                                        <?php if(!empty($student['roll_no'])) { ?>
                                                            <span class="badge-soft badge-blue" style="font-family:monospace; font-size:12.5px; font-weight:700;"><?php echo $student['roll_no']; ?></span>
                                                        <?php } else { ?>
                                                            <span class="text-muted" style="font-size:11.5px;">-</span>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <?php if($has_roll) { ?>
                                                            <span class="badge-soft badge-green"><i class="fa fa-check"></i> <?php echo $student['admit_roll_no']; ?></span>
                                                        <?php } else { ?>
                                                            <span class="badge-soft badge-gray">Not Generated</span>
                                                        <?php } ?>
                                                    </td>
                                                    <td>
                                                        <div style="font-weight:600; color:var(--text-main);">
                                                            <?php echo $this->customlib->getFullName($student['firstname'], $student['middlename'], $student['lastname'], $sch_setting->middlename, $sch_setting->lastname); ?>
                                                        </div>
                                                        <div style="font-size:12px; color:var(--text-muted);">Adm No: <?php echo $student['admission_no']; ?></div>
                                                    </td>
                                                    <td><?php echo $student['class'] . ' (' . $student['section'] . ')'; ?></td>
                                                    <td>
                                                        <?php if(strtolower($student['gender']) == 'male') { ?>
                                                            <span class="badge-soft badge-blue">Male</span>
                                                        <?php } else { ?>
                                                            <span class="badge-soft badge-pink"><?php echo $student['gender']; ?></span>
                                                        <?php } ?>
                                                    </td>
                                                    <td><?php echo $student['category']; ?></td>
                                                    <td style="text-align:right;">
                                                        <?php if($has_roll) { ?>
                                                            <button type="button" class="btn btn-default btn-xs" onclick="viewIndividual(<?php echo $student['cbse_exam_student_id']; ?>)" title="View Admit Card">
                                                                <i class="fa fa-eye text-primary"></i>
                                                            </button>
                                                            <!-- Trigger specific download via JS -->
                                                            <button type="button" class="btn btn-default btn-xs" onclick="downloadIndividual(<?php echo $student['cbse_exam_student_id']; ?>)" title="Download">
                                                                <i class="fa fa-download text-primary"></i>
                                                            </button>
                                                        <?php } else { ?>
                                                            <span class="text-muted" style="font-size:12px;">Needs Roll No</span>
                                                        <?php } ?>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php } else if($this->input->server('REQUEST_METHOD') === 'POST') { ?>
                    <div class="modern-card empty-state">
                        <i class="fa fa-user-times"></i>
                        <h3>No Students Found</h3>
                        <p>No students are registered for the selected exam criteria.</p>
                    </div>
                <?php } else { ?>
                    <div class="modern-card empty-state">
                        <i class="fa fa-search"></i>
                        <h3>Ready to Generate</h3>
                        <p>Select an exam above to load students and generate admit cards.</p>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</div>

<!-- Hidden form for individual download -->
<form id="individualDownloadForm" method="post" action="<?php echo base_url('cbseexam/cbseadmitcardbulk/save_and_download') ?>" style="display:none;">
    <?php echo $this->customlib->getCSRF(); ?>
    <input type="hidden" name="admitcard_template" value="<?php echo isset($get_active_admitcard->id) ? $get_active_admitcard->id : 0; ?>">
    <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
    <input type="hidden" name="show_timetable" value="1">
    <input type="hidden" name="cbse_exam_student_id[]" id="single_cbse_exam_student_id" value="">
</form>

<!-- Admit Card Modal -->
<div class="modal fade" id="admitCardModal" tabindex="-1" role="dialog" aria-labelledby="admitCardModalLabel">
  <div class="modal-dialog modal-lg" role="document" style="width: 80%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="admitCardModalLabel">View Admit Card</h4>
      </div>
      <div class="modal-body" id="admitCardModalBody" style="padding:0;">
          <iframe id="admitCardIframe" style="width: 100%; height: 700px; border: none;"></iframe>
      </div>
    </div>
  </div>
</div>

<script>
    var class_id = '<?php echo set_value('class_id', $class_id) ?>';
    var section_id = '<?php echo set_value('section_id', $section_id) ?>';

    getSectionByClass(class_id, section_id);

    $(document).on('change', '#class_id', function (e) {
        $('#section_id').html("");
        var class_id = $(this).val();
        getSectionByClass(class_id, 0);
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
                success: function (data) {
                    $.each(data, function (i, obj) {
                        var sel = (section_id == obj.section_id) ? "selected" : "";
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        }
    }

    // UX: Row click selects checkbox (Fitts's Law)
    $('.student-row').on('click', function(e) {
        if(e.target.type !== 'checkbox' && e.target.tagName !== 'BUTTON' && e.target.tagName !== 'I') {
            var cb = $(this).find('.student-cb');
            cb.prop('checked', !cb.prop('checked'));
            updateStickyBar();
        }
    });

    $('.student-cb').on('change', updateStickyBar);

    $('#select_all').on('click', function() {
        $('.student-cb').prop('checked', this.checked);
        updateStickyBar();
    });

    function updateStickyBar() {
        var checkedCount = $('.student-cb:checked').length;
        $('#selectedCount').text(checkedCount);
        
        var btnGen = $('#bulkGenerateBtn');
        var btnDown = $('#bulkDownloadBtn');

        if(checkedCount > 0) {
            btnGen.prop('disabled', false);
            btnDown.prop('disabled', false);
        } else {
            btnGen.prop('disabled', true);
            btnDown.prop('disabled', true);
        }
    }

    function toggleTopBarSeriesInput(val) {
        if(val === 'series') {
            $('#top_bar_series_wrapper').css('display', 'inline-flex');
            $('#top_bar_series_input').prop('required', true);
        } else {
            $('#top_bar_series_wrapper').hide();
            $('#top_bar_series_input').prop('required', false);
        }
    }

    function toggleModalSeriesInput(selectElem, wrapperId, inputId) {
        if($(selectElem).val() === 'series') {
            $('#' + wrapperId).show();
            $('#' + inputId).prop('required', true);
        } else {
            $('#' + wrapperId).hide();
            $('#' + inputId).prop('required', false);
        }
    }

    function openSectionGenerateModal(classId, sectionId, classSectionName) {
        $('#section_modal_class_id').val(classId);
        $('#section_modal_section_id').val(sectionId);
        $('#section_modal_target_name').text(classSectionName);
        $('#sectionGenerateModal').modal('show');
    }

    function submitGenerate() {
        var rollType = $('#top_bar_roll_type').val();
        if(rollType === 'series' && $('#top_bar_series_input').val() === '') {
            alert('Please enter a starting Series number.');
            $('#top_bar_series_input').focus();
            return;
        }
        $('#generateCard').attr('action', '<?php echo base_url('cbseexam/cbseadmitcardbulk/generate_admitcards') ?>');
        $('#generateCard').submit();
    }

    function submitDownload() {
        $('#generateCard').attr('action', '<?php echo base_url('cbseexam/cbseadmitcardbulk/save_and_download') ?>');
        $('#generateCard').submit();
    }

    function downloadIndividual(cbse_exam_student_id) {
        $('#single_cbse_exam_student_id').val(cbse_exam_student_id);
        $('#individualDownloadForm').submit();
    }

    function viewIndividual(cbse_exam_student_id) {
        var admitcard_template = $('input[name="admitcard_template"]').val();
        var exam_id = $('input[name="exam_id"]').val();
        var show_timetable = $('input[name="show_timetable"]').is(':checked') ? 1 : 0;
        var base_url = '<?php echo base_url() ?>';

        $.ajax({
            url: base_url + "cbseexam/cbseadmitcardbulk/view_admitcard_html",
            type: "POST",
            data: {
                cbse_exam_student_id: cbse_exam_student_id,
                admitcard_template: admitcard_template,
                exam_id: exam_id,
                show_timetable: show_timetable
            },
            success: function(response) {
                var iframe = document.getElementById('admitCardIframe');
                iframe.srcdoc = response;
                $('#admitCardModal').modal('show');
            },
            error: function() {
                alert('Error fetching admit card preview');
            }
        });
    }
</script>

<!-- Modal: Generate All Missing for entire Exam -->
<div class="modal fade" id="generateAllModal" tabindex="-1" role="dialog" aria-labelledby="generateAllModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:12px; overflow:hidden;">
            <div class="modal-header" style="background:#114B5F; color:#ffffff; padding:16px 20px;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#ffffff; opacity:0.8;"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="generateAllModalLabel" style="font-weight:700;"><i class="fa fa-magic"></i> Auto-Generate Roll Numbers (All Missing)</h4>
            </div>
            <form action="<?php echo site_url('cbseexam/cbseadmitcardbulk/generate_missing'); ?>" method="post">
                <?php echo $this->customlib->getCSRF(); ?>
                <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
                
                <div class="modal-body" style="padding:24px;">
                    <div class="form-group">
                        <label style="font-weight:700; color:#1e293b; margin-bottom:8px;">Choose Roll Number Source:</label>
                        <select name="roll_type" class="form-control" style="border-radius:8px; height:40px;" onchange="toggleModalSeriesInput(this, 'all_modal_series_box', 'all_modal_series_input')">
                            <option value="class_roll">Use Class Roll No (Copy from Student Profile)</option>
                            <option value="series">Generate New Custom Series Number</option>
                        </select>
                        <span class="help-block" style="font-size:12px; color:#64748b; margin-top:6px;">
                            <strong>Class Roll No:</strong> Copies each student's current classroom roll number.<br>
                            <strong>Custom Series:</strong> Generates sequential numbers starting from your input (e.g. 100001).
                        </span>
                    </div>

                    <div class="form-group" id="all_modal_series_box" style="display:none; margin-top:14px;">
                        <label style="font-weight:700; color:#1e293b; margin-bottom:6px;">Starting Series Number: <small class="text-danger">*</small></label>
                        <input type="number" name="series" id="all_modal_series_input" class="form-control" placeholder="e.g. 100001" style="border-radius:8px; height:40px;">
                    </div>
                </div>
                <div class="modal-footer" style="background:#f8fafc; border-top:1px solid #e2e8f0; padding:14px 20px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius:6px;">Cancel</button>
                    <button type="submit" class="btn btn-success" style="border-radius:6px; font-weight:600;"><i class="fa fa-check"></i> Generate Roll Numbers</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Generate for Section -->
<div class="modal fade" id="sectionGenerateModal" tabindex="-1" role="dialog" aria-labelledby="sectionGenerateModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="border-radius:12px; overflow:hidden;">
            <div class="modal-header" style="background:#114B5F; color:#ffffff; padding:16px 20px;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color:#ffffff; opacity:0.8;"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="sectionGenerateModalLabel" style="font-weight:700;">
                    <i class="fa fa-magic"></i> Generate Roll Numbers - <span id="section_modal_target_name"></span>
                </h4>
            </div>
            <form action="<?php echo site_url('cbseexam/cbseadmitcardbulk/generate_missing_by_section'); ?>" method="post">
                <?php echo $this->customlib->getCSRF(); ?>
                <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
                <input type="hidden" name="class_id" id="section_modal_class_id" value="">
                <input type="hidden" name="section_id" id="section_modal_section_id" value="">
                
                <div class="modal-body" style="padding:24px;">
                    <div class="form-group">
                        <label style="font-weight:700; color:#1e293b; margin-bottom:8px;">Choose Roll Number Source:</label>
                        <select name="roll_type" class="form-control" style="border-radius:8px; height:40px;" onchange="toggleModalSeriesInput(this, 'sec_modal_series_box', 'sec_modal_series_input')">
                            <option value="class_roll">Use Class Roll No (Copy from Student Profile)</option>
                            <option value="series">Generate New Custom Series Number</option>
                        </select>
                        <span class="help-block" style="font-size:12px; color:#64748b; margin-top:6px;">
                            <strong>Class Roll No:</strong> Copies each student's current classroom roll number.<br>
                            <strong>Custom Series:</strong> Generates sequential numbers starting from your input.
                        </span>
                    </div>

                    <div class="form-group" id="sec_modal_series_box" style="display:none; margin-top:14px;">
                        <label style="font-weight:700; color:#1e293b; margin-bottom:6px;">Starting Series Number: <small class="text-danger">*</small></label>
                        <input type="number" name="series" id="sec_modal_series_input" class="form-control" placeholder="e.g. 101" style="border-radius:8px; height:40px;">
                    </div>
                </div>
                <div class="modal-footer" style="background:#f8fafc; border-top:1px solid #e2e8f0; padding:14px 20px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius:6px;">Cancel</button>
                    <button type="submit" class="btn btn-success" style="border-radius:6px; font-weight:600;"><i class="fa fa-check"></i> Generate Section Roll Numbers</button>
                </div>
            </form>
        </div>
    </div>
</div>
