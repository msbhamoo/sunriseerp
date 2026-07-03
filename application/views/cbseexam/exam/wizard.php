<style>
    .dashboard2-wrapper {
        background-color: #f4f6f9;
        font-family: 'Inter', sans-serif;
    }
    .d2-card {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #eaeaea;
    }
    .d2-title {
        font-size: 14px;
        font-weight: 600;
        color: #8a8a8a;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 15px;
    }
    .d2-btn {
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 500;
        border: 1px solid #ccc;
        background: #fff;
        color: #333;
        transition: all 0.2s;
        cursor: pointer;
    }
    .d2-btn:hover { background: #f5f5f5; }
    .d2-btn.primary { background: #007bff; color: #fff; border-color: #007bff; }
    .d2-btn.primary:hover { background: #0069d9; }
    .d2-btn.success { background: #28a745; color: #fff; border-color: #28a745; }
    .d2-btn.success:hover { background: #218838; }
    
    .wizard-steps {
        display: flex;
        justify-content: space-between;
        margin-bottom: 30px;
        position: relative;
    }
    .wizard-steps::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 0;
        right: 0;
        height: 2px;
        background: #e0e0e0;
        z-index: 1;
    }
    .wizard-step {
        position: relative;
        z-index: 2;
        text-align: center;
        flex: 1;
    }
    .wizard-step-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #fff;
        border: 2px solid #e0e0e0;
        line-height: 36px;
        font-weight: 600;
        color: #888;
        margin: 0 auto 10px;
        transition: all 0.3s;
    }
    .wizard-step.active .wizard-step-circle {
        border-color: #007bff;
        background: #007bff;
        color: #fff;
    }
    .wizard-step.completed .wizard-step-circle {
        border-color: #28a745;
        background: #28a745;
        color: #fff;
    }
    .wizard-step-title {
        font-size: 13px;
        font-weight: 600;
        color: #555;
    }
    .wizard-content {
        display: none;
    }
    .wizard-content.active {
        display: block;
    }
    .wizard-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }
    
    /* Sleek form styling */
    .d2-form-control {
        border: 1px solid #dce1e7;
        border-radius: 4px;
        box-shadow: none;
        height: 38px;
        font-size: 13px;
        padding: 6px 12px;
        width: 100%;
        transition: border-color 0.15s ease-in-out;
    }
    .d2-form-control:focus {
        border-color: #80bdff;
        outline: 0;
    }
    .d2-label {
        font-size: 12px;
        font-weight: 600;
        color: #444;
        margin-bottom: 5px;
        display: block;
    }
    .d2-checkbox-inline {
        display: inline-block;
        margin-right: 15px;
        font-size: 13px;
        color: #333;
        font-weight: 500;
        cursor: pointer;
    }
</style>

<div class="content-wrapper dashboard2-wrapper">
    <section class="content-header">
        <div class="row" style="display:flex; align-items:center; justify-content:space-between; margin-bottom: 20px;">
            <div class="col-md-6">
                <h1 style="margin:0; font-size: 24px; font-weight:700;">CBSE Exam Wizard</h1>
                <small style="color:#888;">Dashboard / CBSE Examination / Create Exam</small>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="d2-card">
                    
                    <div class="wizard-steps">
                        <div class="wizard-step active" id="nav-step-1">
                            <div class="wizard-step-circle">1</div>
                            <div class="wizard-step-title">Exam Details</div>
                        </div>
                        <div class="wizard-step" id="nav-step-2">
                            <div class="wizard-step-circle">2</div>
                            <div class="wizard-step-title">Classes & Sections</div>
                        </div>
                        <div class="wizard-step" id="nav-step-3">
                            <div class="wizard-step-circle">3</div>
                            <div class="wizard-step-title">Subjects & Timetable</div>
                        </div>
                        <div class="wizard-step" id="nav-step-4">
                            <div class="wizard-step-circle">4</div>
                            <div class="wizard-step-title">Grading & Publish</div>
                        </div>
                    </div>

                    <form id="examWizardForm" method="post" action="<?php echo site_url('cbseexam/exam/save_wizard'); ?>">
                        <input type="hidden" name="exam_id" id="exam_id" value="<?php echo isset($exam_id) ? $exam_id : ''; ?>">
                        
                        <!-- Step 1: Exam Details -->
                        <div class="wizard-content active" id="step-1">
                            <div class="d2-title" style="color:#007bff;">Step 1: Define Exam Details</div>
                            <p style="color:#666; font-size:13px; margin-bottom:20px;">Set up the basic details for this examination. The exam will be automatically locked to the current academic session.</p>
                            
                            <div class="row">
                                <div class="col-md-6" style="margin-bottom:15px;">
                                    <label class="d2-label">Exam Name <span style="color:red;">*</span></label>
                                    <input type="text" class="d2-form-control" name="exam_name" id="exam_name" value="<?php echo isset($exam['name']) ? htmlspecialchars($exam['name']) : ''; ?>" required>
                                </div>
                                <div class="col-md-3" style="margin-bottom:15px;">
                                    <label class="d2-label">Term</label>
                                    <select class="d2-form-control" name="term_id" id="term_id">
                                        <option value="">Select Term</option>
                                        <?php foreach ($term_list as $term): ?>
                                            <option value="<?php echo $term->id; ?>" <?php echo (isset($exam['cbse_term_id']) && $exam['cbse_term_id'] == $term->id) ? 'selected' : ''; ?>><?php echo $term->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-3" style="margin-bottom:15px;">
                                    <label class="d2-label">Assessment Type <span style="color:red;">*</span></label>
                                    <select class="d2-form-control" name="assessment_id" id="assessment_id" <?php echo isset($exam_id) && $exam_id != '' ? 'disabled' : 'required'; ?>>
                                        <option value="">Select Assessment</option>
                                        <?php foreach ($assessment_result as $assessment): ?>
                                            <option value="<?php echo $assessment['id']; ?>" <?php echo (isset($exam['cbse_exam_assessment_id']) && $exam['cbse_exam_assessment_id'] == $assessment['id']) ? 'selected' : ''; ?>><?php echo $assessment['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if(isset($exam_id) && $exam_id != ''): ?>
                                        <input type="hidden" name="assessment_id" value="<?php echo $exam['cbse_exam_assessment_id']; ?>">
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12" style="margin-bottom:15px;">
                                    <label class="d2-label">Description</label>
                                    <textarea class="d2-form-control" name="description" id="description" rows="3" style="height:auto;"><?php echo isset($exam['description']) ? htmlspecialchars($exam['description']) : ''; ?></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div style="background:#fef08a; padding:10px 15px; border-radius:4px; font-size:12px; color:#ca8a04; font-weight:600;">
                                        <i class="fa fa-info-circle"></i> This exam will be created under the current session: <strong><?php echo $this->setting_model->getCurrentSessionName(); ?></strong>.
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 2: Classes & Sections -->
                        <div class="wizard-content" id="step-2">
                            <div class="d2-title" style="color:#d68940;">Step 2: Assign Classes and Sections</div>
                            <p style="color:#666; font-size:13px; margin-bottom:20px;">Select all the classes and sections that will participate in this exam.</p>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div style="display:flex; justify-content: space-between; align-items:center; margin-bottom: 15px;">
                                        <label class="d2-label" style="margin:0;">Select Classes <span style="color:red;">*</span></label>
                                        <label class="d2-checkbox-inline" style="font-size: 13px; font-weight: 700; color: #007bff; margin:0;">
                                            <input type="checkbox" id="select_all_classes"> Select All Classes
                                        </label>
                                    </div>
                                    <div class="row" id="class-checkboxes">
                                        <?php foreach ($classlist as $class): ?>
                                        <div class="col-md-3" style="margin-bottom: 20px; border-left: 2px solid #eaeaea; padding-left: 15px;">
                                            <label class="d2-checkbox-inline" style="font-size: 14px; font-weight: 700; color: #333; margin-bottom: 10px; display:block;">
                                                <input type="checkbox" class="class_check" name="classes[]" value="<?php echo $class['id']; ?>"> 
                                                <?php echo $class['class']; ?>
                                            </label>
                                            <div class="sections-container" id="sections_for_class_<?php echo $class['id']; ?>" style="display: none; background: #fafafa; padding: 10px; border-radius: 4px;"></div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Step 3: Subjects & Timetable -->
                        <div class="wizard-content" id="step-3">
                            <div class="d2-title" style="color:#9d50ce;">Step 3: Manage Subjects & Timetable</div>
                            <p style="color:#666; font-size:13px; margin-bottom:20px;">The grid below shows all applicable subjects for the selected classes. You can configure dates, times, and marks.</p>
                            
                            <div id="timetable-container" style="border: 1px solid #eaeaea; border-radius: 6px; padding: 15px; background: #fafafa;">
                                <div class="text-center" style="padding: 30px;">
                                    <i class="fa fa-spinner fa-spin fa-2x" style="color: #9d50ce;"></i>
                                    <p style="margin-top: 10px; font-size: 13px; color: #666;">Loading subjects...</p>
                                </div>
                            </div>
                        </div>

                        <!-- Step 4: Grading & Publish -->
                        <div class="wizard-content" id="step-4">
                            <div class="d2-title" style="color:#28a745;">Step 4: Configure Grading & Publish</div>
                            <p style="color:#666; font-size:13px; margin-bottom:20px;">Finalize the grading scale and choose whether to publish the exam immediately.</p>
                            
                            <div class="row">
                                <div class="col-md-6" style="margin-bottom:15px;">
                                    <label class="d2-label">Grade Scale <span style="color:red;">*</span></label>
                                    <select class="d2-form-control" name="grade_id" id="grade_id" required>
                                        <option value="">Select Grade Scale</option>
                                        <?php foreach ($grade_result as $grade): ?>
                                            <option value="<?php echo $grade['id']; ?>"><?php echo $grade['name']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6" style="margin-bottom:15px;">
                                    <label class="d2-label">&nbsp;</label>
                                    <label class="d2-checkbox-inline" style="background: #e6f4ea; padding: 8px 15px; border-radius: 4px; border: 1px solid #c3e6cb; color: #155724; display:block;">
                                        <input type="checkbox" name="is_active" id="is_active" value="1" checked> 
                                        Publish this exam immediately (Visible in active exams)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="wizard-actions">
                            <button type="button" class="d2-btn" id="btn-prev" style="display: none;">Back</button>
                            <div></div>
                            <button type="button" class="d2-btn primary" id="btn-next">Next Step <i class="fa fa-arrow-right" style="margin-left: 5px;"></i></button>
                            <button type="submit" class="d2-btn success" id="btn-submit" style="display: none;"><i class="fa fa-check" style="margin-right: 5px;"></i> Save Exam</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    var currentStep = 1;
    var totalSteps = 4;

    function updateWizard() {
        $('.wizard-content').removeClass('active');
        $('#step-' + currentStep).addClass('active');

        $('.wizard-step').removeClass('active completed');
        for (var i = 1; i < currentStep; i++) {
            $('#nav-step-' + i).addClass('completed');
        }
        $('#nav-step-' + currentStep).addClass('active');

        if (currentStep === 1) {
            $('#btn-prev').hide();
        } else {
            $('#btn-prev').show();
        }

        if (currentStep === totalSteps) {
            $('#btn-next').hide();
            $('#btn-submit').show();
        } else {
            $('#btn-next').show();
            $('#btn-submit').hide();
        }
        
        if (currentStep === 3) {
            loadSubjectsAndTimetable();
        }
    }

    $('#btn-next').click(function() {
        if (currentStep === 1) {
            if (!$('#exam_name').val()) {
                alert('Please enter an exam name.');
                return;
            }
            if (!$('#assessment_id').val()) {
                alert('Please select an assessment type.');
                return;
            }
        }
        
        if (currentStep === 2) {
            if ($('.section_check:checked').length === 0) {
                alert('Please select at least one class and section.');
                return;
            }
        }

        if (currentStep < totalSteps) {
            currentStep++;
            updateWizard();
        }
    });

    $('#btn-prev').click(function() {
        if (currentStep > 1) {
            currentStep--;
            updateWizard();
        }
    });

    function loadSectionsForClass(classId, sectionContainer, forceCheckAll) {
        if (sectionContainer.html().trim() === '' || sectionContainer.html().indexOf('fa-spinner') !== -1) {
            sectionContainer.html('<div class="text-center" style="padding:10px;"><i class="fa fa-spinner fa-spin" style="color:#d68940;"></i></div>');
            $.ajax({
                url: '<?php echo base_url(); ?>sections/getByClass',
                type: 'GET',
                data: {class_id: classId},
                dataType: 'json',
                success: function(res) {
                    var html = '';
                    $.each(res, function(i, obj) {
                        var checkedStr = forceCheckAll ? 'checked' : 'checked';
                        html += '<label class="d2-checkbox-inline" style="font-size: 12px; margin-bottom: 5px; display:block;">';
                        html += '<input type="checkbox" class="section_check" name="sections['+classId+'][]" value="'+obj.section_id+'" ' + checkedStr + '> ' + obj.section;
                        html += '</label>';
                    });
                    sectionContainer.html(html);
                },
                error: function() {
                    sectionContainer.html('<div style="color:red; font-size:11px;">Error loading sections. Please uncheck and check again.</div>');
                }
            });
        } else {
            if (forceCheckAll) {
                sectionContainer.find('.section_check').prop('checked', true);
            }
        }
    }

    $('.class_check').change(function() {
        var classId = $(this).val();
        var isChecked = $(this).is(':checked');
        var sectionContainer = $('#sections_for_class_' + classId);
        
        if (isChecked) {
            sectionContainer.show();
            loadSectionsForClass(classId, sectionContainer, true);
        } else {
            sectionContainer.hide();
            sectionContainer.find('.section_check').prop('checked', false);
        }
    });

    $('#select_all_classes').change(function() {
        var isChecked = $(this).is(':checked');
        $('.class_check').each(function() {
            if ($(this).is(':checked') !== isChecked) {
                $(this).prop('checked', isChecked).trigger('change');
            }
        });
    });
    
    function loadSubjectsAndTimetable() {
        var formData = $('#examWizardForm').serialize();
        $('#timetable-container').html('<div class="text-center" style="padding: 30px;"><i class="fa fa-spinner fa-spin fa-2x" style="color:#9d50ce;"></i><p style="margin-top: 10px; font-size: 13px; color: #666;">Loading subjects based on selected classes...</p></div>');
        
        $.ajax({
            url: '<?php echo site_url("cbseexam/exam/wizard_load_timetable"); ?>',
            type: 'POST',
            data: formData,
            success: function(res) {
                $('#timetable-container').html(res);
            },
            error: function() {
                $('#timetable-container').html('<div class="alert alert-danger" style="background:#fffcf5; color:#d09435; border-color:#fbedcf;">Error loading subjects. Please try again.</div>');
            }
        });
    }

    <?php if(isset($assigned_classes) && !empty($assigned_classes)): ?>
    var assigned_classes = <?php echo json_encode($assigned_classes); ?>;
    $.each(assigned_classes, function(class_id, sections) {
        $('.class_check[value="'+class_id+'"]').prop('checked', true);
        var sectionContainer = $('#sections_for_class_' + class_id);
        sectionContainer.show();
        sectionContainer.html('<div class="text-center" style="padding:10px;"><i class="fa fa-spinner fa-spin" style="color:#d68940;"></i></div>');
        
        $.ajax({
            url: '<?php echo base_url(); ?>sections/getByClass',
            type: 'GET',
            data: {class_id: class_id},
            dataType: 'json',
            success: function(res) {
                var html = '';
                $.each(res, function(i, obj) {
                    var isChecked = sections.indexOf(obj.section_id) !== -1 ? 'checked' : '';
                    html += '<label class="d2-checkbox-inline" style="font-size: 12px; margin-bottom: 5px; display:block;">';
                    html += '<input type="checkbox" class="section_check" name="sections['+class_id+'][]" value="'+obj.section_id+'" ' + isChecked + '> ' + obj.section;
                    html += '</label>';
                });
                sectionContainer.html(html);
            }
        });
    });
    <?php endif; ?>
});
</script>
