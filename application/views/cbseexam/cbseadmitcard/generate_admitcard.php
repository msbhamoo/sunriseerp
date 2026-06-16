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

/* Action Bar Sticky */
.sticky-action-bar {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-top: 1px solid var(--border);
    padding: 16px 24px;
    box-shadow: 0 -4px 6px -1px rgb(0 0 0 / 0.05);
    z-index: 1000;
    display: flex;
    align-items: center;
    justify-content: space-between;
    transform: translateY(100%);
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.sticky-action-bar.visible {
    transform: translateY(0);
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

.content-wrapper { padding-bottom: 80px; } /* Space for sticky bar */
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

                <!-- Step 2: Student List & Actions -->
                <?php if (isset($student_list) && !empty($student_list)) { ?>
                    <div class="modern-card">
                        <div class="modern-header" style="display:flex; justify-content:space-between; align-items:center;">
                            <h3 class="modern-title"><i class="fa fa-users"></i> Step 2: Select & Generate</h3>
                            
                            <!-- Bulk Generate All Missing -->
                            <form action="<?php echo site_url('cbseexam/cbseadmitcardbulk/generate_missing'); ?>" method="post" class="form-inline" id="generateMissingForm">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
                                <div class="input-group">
                                    <input type="number" name="series" class="form-control input-sm" placeholder="Start Series (e.g. 1001)" required style="width: 160px; border-radius: 4px 0 0 4px;">
                                    <span class="input-group-btn">
                                        <button type="button" onclick="confirmGenerateMissing()" class="btn btn-success btn-sm" style="border-radius: 0 4px 4px 0;">
                                            <i class="fa fa-magic"></i> Auto-Generate All Missing
                                        </button>
                                    </span>
                                </div>
                            </form>
                        </div>
                        
                        <div class="modern-body p-0">
                            <form method="post" action="<?php echo base_url('cbseexam/cbseadmitcardbulk/save_and_download') ?>" id="generateCard">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <input type="hidden" name="admitcard_template" value="<?php echo isset($get_active_admitcard->id) ? $get_active_admitcard->id : 0; ?>">
                                <input type="hidden" name="exam_id" value="<?php echo $exam_id; ?>">
                                
                                <!-- Sticky Action Bar -->
                                <div class="sticky-action-bar" id="stickyActionBar">
                                    <div style="display:flex; align-items:center; gap: 20px;">
                                        <div style="font-weight:600; color:var(--primary);">
                                            <span id="selectedCount">0</span> Students Selected
                                        </div>
                                        <div style="display:flex; align-items:center; gap: 8px;">
                                            <label style="margin:0; font-weight:500; color:var(--text-muted);">Series:</label>
                                            <input type="number" name="series" class="form-control custom-input" style="width:120px; padding:6px 10px;" placeholder="Optional">
                                        </div>
                                        <div style="display:flex; align-items:center; gap: 8px; margin-left: 10px;">
                                            <span style="font-weight:500; color:var(--text-muted);">Show Timetable</span>
                                            <label class="switch" style="margin:0;">
                                                <input type="checkbox" name="show_timetable" value="1" checked>
                                                <span class="slider"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div>
                                        <button class="btn-premium" type="submit" id="bulkDownloadBtn" disabled>
                                            <i class="fa fa-file-pdf-o"></i> Generate & Download
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
        
        var actionBar = $('#stickyActionBar');
        var btn = $('#bulkDownloadBtn');

        if(checkedCount > 0) {
            actionBar.addClass('visible');
            btn.prop('disabled', false);
        } else {
            actionBar.removeClass('visible');
            btn.prop('disabled', true);
        }
    }

    function confirmGenerateMissing() {
        if($('#generateMissingForm input[name="series"]').val() === '') {
            alert('Please enter a starting series number.');
            return;
        }
        if(confirm('This will assign roll numbers to ALL students in this exam who do not have one yet. Proceed?')) {
            $('#generateMissingForm').submit();
        }
    }

    function downloadIndividual(cbse_exam_student_id) {
        $('#single_cbse_exam_student_id').val(cbse_exam_student_id);
        $('#individualDownloadForm').submit();
    }
</script>
