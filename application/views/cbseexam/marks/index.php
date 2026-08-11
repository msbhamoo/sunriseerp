<div class="content-wrapper dashboard2-wrapper" style="min-height: 946px;">
    <section class="content-header" style="padding: 15px 15px 5px 15px;">
        <h1 style="font-size: 20px; font-weight: 800; color: #0f172a; margin: 0; display: flex; align-items: center; gap: 8px;">
            <i class="fa fa-newspaper-o" style="color: #0284c7;"></i> <?php echo $this->lang->line('marks_register'); ?>
        </h1>
    </section>

    <style type="text/css">
        .dashboard2-wrapper {
            background-color: #f8fafc;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        /* Modern Card Box Enhancements */
        .sc-card {
            background: #ffffff;
            border-radius: 14px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 4px 18px rgba(0,0,0,0.02);
            margin-bottom: 20px;
            overflow: hidden;
        }
        .sc-card-header {
            background: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .sc-card-title {
            font-size: 16px;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sc-card label {
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .sc-card .form-control {
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            padding: 8px 12px;
            height: 38px;
            background-color: #f8fafc;
            font-size: 13px;
            color: #0f172a;
            transition: all 0.2s ease;
        }

        .sc-card .form-control:focus {
            border-color: #0284c7;
            background-color: #ffffff;
            box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.1);
        }

        .select2-container--default .select2-selection--multiple {
            border-radius: 8px !important;
            border: 1px solid #e2e8f0 !important;
            background-color: #f8fafc !important;
            min-height: 38px !important;
        }

        /* Buttons */
        .btn-sc-primary {
            background-color: #0284c7;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 13px;
            padding: 8px 20px;
            box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
            transition: all 0.2s ease;
        }
        .btn-sc-primary:hover, .btn-sc-primary:focus {
            background-color: #0369a1;
            color: #ffffff;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(2, 132, 199, 0.35);
        }

        .btn-sc-warning {
            background-color: #f59e0b;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 13px;
            padding: 8px 18px;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25);
            transition: all 0.2s ease;
        }
        .btn-sc-warning:hover, .btn-sc-warning:focus {
            background-color: #d97706;
            color: #ffffff;
            transform: translateY(-1px);
        }

        .rank-option-container {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 6px 14px;
            display: inline-flex;
            align-items: center;
            gap: 14px;
            margin-right: 15px;
        }
        .rank-option-container label {
            margin-bottom: 0 !important;
            font-size: 12px !important;
            text-transform: none !important;
            color: #334155 !important;
            cursor: pointer;
        }
    </style>

    <!-- Main content -->
    <section class="content" style="padding: 15px;">
        <div class="row">
            <div class="col-md-12">
                <div class="sc-card">
                    <div class="sc-card-header">
                        <h3 class="sc-card-title"><i class="fa fa-search" style="color:#0284c7;"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                        <div style="display:flex; gap:10px; flex-wrap:wrap;">
                            <button type="button" class="btn btn-default" id="btn_open_maxmarks_modal" style="border-radius: 8px; font-weight: 700; font-size: 13px; padding: 8px 18px; border:1px solid #cbd5e1; color:#0369a1; background:#f8fafc;"><i class="fa fa-sliders"></i> Subject Max Marks</button>
                            <button type="button" class="btn btn-success" id="btn_open_bulk_modal" style="border-radius: 8px; font-weight: 700; font-size: 13px; padding: 8px 18px; background-color: #10b981; border: none; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);"><i class="fa fa-file-excel-o"></i> Bulk Exam Marks Upload</button>
                        </div>
                    </div>
                    <div class="box-body" style="padding: 20px;">
                        <form role="form" id="filter_form" class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('class'); ?> <small class="req"> *</small></label>
                                    <select id="class_id" name="class_id[]" class="form-control select2" multiple="multiple">
                                        <?php foreach ($classes as $class) { ?>
                                            <option value="<?php echo $class['id'] ?>"><?php echo $class['class'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('section'); ?> <small class="req"> *</small></label>
                                    <select id="section_id" name="section_id[]" class="form-control select2" multiple="multiple">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('exam'); ?> <small class="req"> *</small></label>
                                    <select id="exam_id" name="exam_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('subject'); ?> <small class="req"> *</small></label>
                                    <select id="subject_id" name="subject_id" class="form-control">
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                    <input type="hidden" id="timetable_id" name="timetable_id" value="">
                                </div>
                            </div>
                            
                            <div class="col-md-12" style="margin-top: 10px; display: flex; align-items: center; justify-content: flex-end; flex-wrap: wrap; gap: 10px;">
                                <button type="button" class="btn btn-warning" id="btn_report_card" style="border-radius:8px;font-weight:700;font-size:13px;padding:8px 18px;background:#6d28d9;border:none;color:#fff;"><i class="fa fa-id-card-o"></i> CBSE Report Card</button>
                                <button type="button" class="btn btn-sc-primary" id="btn_search"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Marks Entry Grid Box -->
                <div class="sc-card" id="marks_entry_container" style="display:none;">
                    <div class="sc-card-header" style="display: flex; align-items: center; justify-content: space-between; gap: 15px; flex-wrap: wrap; padding: 10px 20px;">
                        <h3 class="sc-card-title" style="margin: 0;"><i class="fa fa-list" style="color:#0284c7;"></i> <?php echo $this->lang->line('marks_entry'); ?></h3>
                        <div id="marks_header_actions" style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-left: auto;"></div>
                    </div>
                    <div class="box-body marksEntryForm" style="padding: 20px;">
                        <!-- Premium grid will be loaded here via AJAX -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Bulk Multi-Subject Marks Upload Modal -->
<div class="modal fade" id="bulkUploadModal" tabindex="-1" role="dialog" aria-labelledby="bulkUploadModalLabel">
    <div class="modal-dialog modal-lg" role="document" style="width: 90%; max-width: 1200px;">
        <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background: #0284c7; color: #ffffff; border-top-left-radius: 14px; border-top-right-radius: 14px; padding: 16px 24px;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #ffffff; opacity: 0.8; font-size: 24px;"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="bulkUploadModalLabel" style="font-weight: 800; font-size: 18px; display: flex; align-items: center; gap: 10px;">
                    <i class="fa fa-file-excel-o"></i> Bulk Exam Marks Upload & Preview (All Subjects)
                </h4>
            </div>
            <div class="modal-body" style="padding: 24px;">
                <form id="bulk_upload_form" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label style="font-weight: 700; color: #334155;">1. Select CBSE Exam <small class="text-danger">*</small></label>
                                <select id="bulk_exam_id" name="exam_id" class="form-control" style="border-radius: 8px; height: 40px; font-weight: 600;">
                                    <option value="">-- Select Exam --</option>
                                    <?php
                                    if (isset($all_exams) && !empty($all_exams)) {
                                        foreach ($all_exams as $ex) {
                                            echo '<option value="' . $ex['id'] . '">' . htmlspecialchars($ex['name']) . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label style="font-weight: 700; color: #334155;">2. Select Class <small class="text-danger">*</small></label>
                                <select id="bulk_class_id" name="bulk_class_id" class="form-control" style="border-radius: 8px; height: 40px; font-weight: 600;" disabled>
                                    <option value="">-- Select Exam First --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4" style="display: flex; align-items: flex-end; padding-bottom: 15px;">
                            <button type="button" id="btn_export_bulk_template" class="btn btn-default" style="border-radius: 8px; font-weight: 700; height: 40px; border: 1px solid #cbd5e1; color: #0284c7; background: #f8fafc; display: flex; align-items: center; gap: 8px; width: 100%; justify-content: center;">
                                <i class="fa fa-download" style="font-size: 15px;"></i> Download Sample Template
                            </button>
                        </div>
                    </div>
                    <div class="row" style="margin-top: 10px;">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label style="font-weight: 700; color: #334155; margin-bottom: 8px;">3. Select Excel/CSV Mark File <small class="text-danger">*</small></label>
                                <div style="border: 2px dashed #0284c7; border-radius: 12px; background: #f0f9ff; padding: 25px; text-align: center; cursor: pointer; position: relative; transition: all 0.2s ease;" id="file_dropzone_box">
                                    <input type="file" id="bulk_file_input" name="file" accept=".csv" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; z-index: 10;">
                                    <div id="file_picker_display">
                                        <i class="fa fa-cloud-upload" style="font-size: 38px; color: #0284c7; margin-bottom: 8px; display: block;"></i>
                                        <h5 style="font-weight: 700; color: #0f172a; margin: 0; font-size: 15px;">Click here or drag & drop your Mark file</h5>
                                        <p id="selected_filename" style="margin: 6px 0 0 0; color: #64748b; font-size: 13px; font-weight: 600;">Format: .csv (Excel compatible)</p>
                                    </div>
                                </div>
                                <small class="text-muted" style="margin-top: 8px; display: block;">
                                    <i class="fa fa-info-circle"></i> File must identify students by <strong>SrNo / Admission No</strong>. Mark values of <strong>'A'</strong> or <strong>'ABS'</strong> will record the student as Absent.
                                </small>
                            </div>
                        </div>
                    </div>
                    <div class="text-right" style="margin-top: 15px;">
                        <button type="submit" id="btn_parse_bulk_file" class="btn btn-sc-primary" style="padding: 10px 28px; font-weight: 700; font-size: 14px; background-color: #0284c7; border: none; border-radius: 8px;">
                            <i class="fa fa-eye"></i> Parse & Preview Upload
                        </button>
                    </div>
                </form>

                <hr style="margin: 20px 0; border-color: #e2e8f0;">

                <!-- Dynamic Preview Grid Container -->
                <div id="bulk_preview_area" style="display: none;">
                    <div id="bulk_summary_alert" class="alert" style="border-radius: 10px; padding: 12px 18px; margin-bottom: 15px;"></div>

                    <div class="table-responsive" style="max-height: 450px; overflow-y: auto; border: 1px solid #e2e8f0; border-radius: 10px;">
                        <table class="table table-bordered table-striped" id="bulk_preview_table" style="margin-bottom: 0; font-size: 12px;">
                            <thead>
                                <tr id="bulk_preview_thead_tr" style="background: #f8fafc; color: #475569; font-weight: 700;">
                                </tr>
                            </thead>
                            <tbody id="bulk_preview_tbody">
                            </tbody>
                        </table>
                    </div>

                    <div style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center;">
                        <span id="bulk_records_info" style="font-weight: 600; color: #64748b; font-size: 13px;"></span>
                        <button type="button" id="btn_confirm_bulk_import" class="btn btn-success" style="border-radius: 8px; font-weight: 700; padding: 10px 28px; font-size: 14px; background-color: #10b981; border: none;">
                            <i class="fa fa-check-circle"></i> Confirm & Import Marks to Database
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Subject Max Marks Modal -->
<div class="modal fade" id="maxMarksModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document" style="width: 90%; max-width: 900px;">
        <div class="modal-content" style="border-radius: 14px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
            <div class="modal-header" style="background: #0369a1; color: #fff; border-top-left-radius: 14px; border-top-right-radius: 14px; padding: 16px 24px;">
                <button type="button" class="close" data-dismiss="modal" style="color:#fff; opacity:.85; font-size:24px;"><span>&times;</span></button>
                <h4 class="modal-title" style="font-weight: 800; font-size: 18px;"><i class="fa fa-sliders"></i> Set Subject Max Marks</h4>
            </div>
            <div class="modal-body" style="padding: 24px;">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label style="font-weight:700; color:#334155;">Exam <small class="text-danger">*</small></label>
                            <select id="mm_exam_id" class="form-control" style="border-radius:8px; height:40px; font-weight:600;">
                                <option value="">-- Select Exam --</option>
                                <?php if (isset($all_exams) && !empty($all_exams)) { foreach ($all_exams as $ex) { echo '<option value="' . $ex['id'] . '">' . htmlspecialchars($ex['name']) . '</option>'; } } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label style="font-weight:700; color:#334155;">Class <small class="text-danger">*</small></label>
                            <select id="mm_class_id" class="form-control" style="border-radius:8px; height:40px; font-weight:600;" disabled>
                                <option value="">-- Select Exam First --</option>
                            </select>
                        </div>
                    </div>
                </div>
                <p style="color:#64748b; font-size:13px; margin: 4px 0 12px;"><i class="fa fa-info-circle"></i> Leave a field blank to use the default max from the marks pattern. A value here overrides the max <strong>only for that subject</strong>.</p>
                <div id="mm_grid_area" style="display:none;">
                    <div class="table-responsive" style="max-height:420px; overflow-y:auto; border:1px solid #e2e8f0; border-radius:10px;">
                        <table class="table table-bordered" style="margin-bottom:0; font-size:13px;">
                            <thead><tr style="background:#f8fafc;"><th>Subject</th><th>Assessment</th><th style="width:120px;">Default Max</th><th style="width:150px;">Override Max</th></tr></thead>
                            <tbody id="mm_tbody"></tbody>
                        </table>
                    </div>
                    <div class="text-right" style="margin-top:16px;">
                        <button type="button" id="btn_save_maxmarks" class="btn btn-primary" style="border-radius:8px; font-weight:700; padding:10px 26px; background:#0369a1; border:none;"><i class="fa fa-check-circle"></i> Save Max Marks</button>
                    </div>
                </div>
                <div id="mm_empty" style="display:none; text-align:center; color:#64748b; padding:24px;">No subjects found for this class in the selected exam.</div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

        $('.select2').select2();

        var global_parsed_payload = null;

        function loadBulkClasses(exam_id, preselect_class) {
            var $cls = $('#bulk_class_id');
            $cls.prop('disabled', true).html('<option value="">Loading...</option>');
            if (!exam_id || exam_id === "") {
                $cls.html('<option value="">-- Select Exam First --</option>');
                return;
            }
            $.ajax({
                type: 'POST',
                url: baseurl + "cbseexam/marks/get_bulk_exam_classes",
                data: {exam_id: exam_id},
                dataType: 'json',
                success: function(data) {
                    var html = '<option value="">-- Select Class --</option>';
                    $.each(data, function(i, obj) {
                        html += '<option value="' + obj.id + '">' + obj.class + '</option>';
                    });
                    $cls.html(html).prop('disabled', false);
                    if (preselect_class) {
                        $cls.val(preselect_class);
                    }
                }
            });
        }

        // ---------- Subject Max Marks modal ----------
        function mmLoadClasses(exam_id) {
            var $cls = $('#mm_class_id');
            $('#mm_grid_area').hide(); $('#mm_empty').hide();
            $cls.prop('disabled', true).html('<option value="">Loading...</option>');
            if (!exam_id) { $cls.html('<option value="">-- Select Exam First --</option>'); return; }
            $.ajax({
                type: 'POST', url: baseurl + "cbseexam/marks/get_bulk_exam_classes",
                data: {exam_id: exam_id}, dataType: 'json',
                success: function(data) {
                    var html = '<option value="">-- Select Class --</option>';
                    $.each(data, function(i, o) { html += '<option value="' + o.id + '">' + o.class + '</option>'; });
                    $cls.html(html).prop('disabled', false);
                }
            });
        }

        function mmLoadGrid() {
            var exam_id = $('#mm_exam_id').val(), class_id = $('#mm_class_id').val();
            $('#mm_grid_area').hide(); $('#mm_empty').hide();
            if (!exam_id || !class_id) return;
            $.ajax({
                type: 'POST', url: baseurl + "cbseexam/marks/subject_max_marks",
                data: {exam_id: exam_id, class_id: class_id}, dataType: 'json',
                success: function(res) {
                    if (res.status !== 1 || !res.subjects || !res.subjects.length) { $('#mm_empty').show(); return; }
                    var html = '';
                    $.each(res.subjects, function(i, sub) {
                        if (!sub.assessment_types.length) return;
                        $.each(sub.assessment_types, function(j, at) {
                            html += '<tr>';
                            if (j === 0) html += '<td rowspan="' + sub.assessment_types.length + '" style="vertical-align:middle; font-weight:700;">' + sub.subject_name + (sub.subject_code ? ' <span class="text-muted">(' + sub.subject_code + ')</span>' : '') + '</td>';
                            html += '<td>' + (at.name || '-') + (at.code ? ' (' + at.code + ')' : '') + '</td>';
                            html += '<td class="text-muted">' + at.default_max + '</td>';
                            html += '<td><input type="number" min="0" step="any" class="form-control mm-input" data-tat="' + at.tat_id + '" value="' + (at.override_max === null ? '' : at.override_max) + '" placeholder="' + at.default_max + '" style="border-radius:6px;"></td>';
                            html += '</tr>';
                        });
                    });
                    $('#mm_tbody').html(html);
                    $('#mm_grid_area').show();
                }
            });
        }

        $('#btn_open_maxmarks_modal').click(function() {
            var selected = $('#exam_id').val();
            $('#mm_exam_id').val(selected && selected !== "" ? selected : "");
            $('#mm_grid_area').hide(); $('#mm_empty').hide();
            mmLoadClasses($('#mm_exam_id').val());
            $('#maxMarksModal').modal('show');
        });
        $('#mm_exam_id').on('change', function() { mmLoadClasses($(this).val()); });
        $('#mm_class_id').on('change', mmLoadGrid);

        $('#btn_save_maxmarks').click(function() {
            var marks = {};
            $('.mm-input').each(function() { marks[$(this).data('tat')] = $(this).val(); });
            var $btn = $(this); $btn.button('loading');
            $.ajax({
                type: 'POST', url: baseurl + "cbseexam/marks/save_subject_max_marks",
                data: {marks: marks}, dataType: 'json',
                success: function(res) {
                    $btn.button('reset');
                    if (res.status === 1) { successMsg(res.message); $('#maxMarksModal').modal('hide'); }
                    else { errorMsg(res.error || 'Failed to save.'); }
                },
                error: function() { $btn.button('reset'); errorMsg('Error saving max marks.'); }
            });
        });

        function syncBulkExams() {
            var selected_exam = $('#exam_id').val();
            if (selected_exam && selected_exam !== "") {
                $('#bulk_exam_id').val(selected_exam);
                // Preselect the class chosen in the main search (if any)
                var main_class = $('#class_id').val();
                var preselect = (main_class && main_class.length === 1) ? main_class[0] : null;
                loadBulkClasses(selected_exam, preselect);
            } else {
                loadBulkClasses('', null);
            }
        }

        // Reload class list when the exam is changed inside the modal
        $('#bulk_exam_id').on('change', function() {
            loadBulkClasses($(this).val(), null);
        });

        $('#btn_open_bulk_modal').click(function() {
            syncBulkExams();
            $('#bulk_preview_area').hide();
            $('#bulk_file_input').val('');
            $('#file_dropzone_box').css('background', '#f0f9ff').css('border-color', '#0284c7');
            $('#selected_filename').html('Format: .csv (Excel compatible)');
            global_parsed_payload = null;
            $('#bulkUploadModal').modal('show');
        });

        $('#bulk_file_input').on('change', function() {
            var file = this.files[0];
            if (file) {
                $('#file_dropzone_box').css('background', '#ecfdf5').css('border-color', '#10b981');
                $('#selected_filename').html('<span style="color: #059669; font-weight: 800;"><i class="fa fa-file-text-o"></i> Selected File: ' + file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)</span>');
            } else {
                $('#file_dropzone_box').css('background', '#f0f9ff').css('border-color', '#0284c7');
                $('#selected_filename').html('Format: .csv (Excel compatible)');
            }
        });


        // Download Bulk Template
        $('#btn_export_bulk_template').click(function() {
            var exam_id = $('#bulk_exam_id').val();
            var class_id = $('#bulk_class_id').val();
            if (!exam_id) {
                errorMsg("Please select a CBSE Exam first.");
                return false;
            }
            if (!class_id) {
                errorMsg("Please select a Class before downloading the sample template.");
                return false;
            }
            window.location.href = baseurl + "cbseexam/marks/export_bulk_template/" + exam_id + "/" + class_id;
        });

        // Parse File Submission
        $('#bulk_upload_form').on('submit', function(e) {
            e.preventDefault();
            var exam_id = $('#bulk_exam_id').val();
            var file_data = $('#bulk_file_input').prop('files')[0];

            if (!exam_id) {
                errorMsg("Please select an Exam.");
                return false;
            }
            if (!file_data) {
                errorMsg("Please select a CSV mark file.");
                return false;
            }

            var form_data = new FormData();
            form_data.append('exam_id', exam_id);
            form_data.append('file', file_data);

            var $btn = $('#btn_parse_bulk_file');
            $btn.button('loading');

            $.ajax({
                url: baseurl + "cbseexam/marks/parse_bulk_marks",
                type: 'POST',
                data: form_data,
                dataType: 'JSON',
                contentType: false,
                processData: false,
                success: function(res) {
                    $btn.button('reset');
                    if (res.status === 1) {
                        global_parsed_payload = res;
                        renderPreviewGrid(res);
                    } else {
                        errorMsg(res.error || "Failed to parse CSV file.");
                    }
                },
                error: function() {
                    $btn.button('reset');
                    errorMsg("Error processing file upload.");
                }
            });
        });

        function renderPreviewGrid(res) {
            var columns = res.columns;
            var rows = res.parsed_rows;
            var errors_found = res.errors_found;
            var total_students = res.total_students_found;

            // Header row
            var theadHtml = '<th>#</th><th>Admission No / SrNo</th><th>Roll No</th><th>Student Name</th><th>Class (Sec)</th>';
            $.each(columns, function(i, col) {
                theadHtml += '<th>' + col.subject_name + ' <small class="text-muted">(Max: ' + col.max_marks + ')</small></th>';
            });
            theadHtml += '<th>Status / Validation</th>';
            $('#bulk_preview_thead_tr').html(theadHtml);

            // Body rows
            var tbodyHtml = '';
            $.each(rows, function(i, r) {
                var rowClass = '';
                var statusBadge = '';

                if (r.status === 'invalid_student') {
                    rowClass = 'danger';
                    statusBadge = '<span class="label label-danger"><i class="fa fa-times"></i> Invalid SrNo</span>';
                } else if (r.status === 'error') {
                    rowClass = 'warning';
                    statusBadge = '<span class="label label-warning"><i class="fa fa-exclamation-triangle"></i> Check Marks</span>';
                } else {
                    statusBadge = '<span class="label label-success"><i class="fa fa-check"></i> Ready</span>';
                }

                tbodyHtml += '<tr class="' + rowClass + '">';
                tbodyHtml += '<td>' + (i + 1) + '</td>';
                tbodyHtml += '<td><strong>' + r.admission_no + '</strong></td>';

                if (r.student) {
                    tbodyHtml += '<td>' + (r.student.exam_roll_no || r.student.roll_no || '-') + '</td>';
                    tbodyHtml += '<td>' + (r.student.firstname + ' ' + (r.student.lastname || '')).trim() + '</td>';
                    tbodyHtml += '<td>' + r.student.class_name + ' (' + r.student.section_name + ')</td>';
                } else {
                    tbodyHtml += '<td colspan="3" class="text-danger"><em>Student not enrolled in this exam</em></td>';
                }

                // Mark columns
                $.each(r.marks, function(j, m) {
                    var cellHtml = '';
                    if (m.cell_status === 'not_applicable') {
                        cellHtml = '<span title="Not offered to this student\'s class - will be skipped" style="color:#dc2626; font-weight:700;"><i class="fa fa-times-circle"></i></span>';
                    } else if (m.cell_status === 'absent') {
                        cellHtml = '<span class="badge" style="background-color: #f59e0b;">ABSENT</span>';
                    } else if (m.cell_status === 'out_of_range') {
                        cellHtml = '<span class="text-danger font-bold" title="Exceeds max ' + m.max_marks + '">' + m.raw_value + ' ⚠️</span>';
                    } else if (m.cell_status === 'invalid_format') {
                        cellHtml = '<span class="text-danger font-bold">' + m.raw_value + ' ❌</span>';
                    } else if (m.raw_value === '') {
                        cellHtml = '<span class="text-muted">-</span>';
                    } else {
                        cellHtml = '<strong>' + m.raw_value + '</strong>';
                    }
                    tbodyHtml += '<td>' + cellHtml + '</td>';
                });

                var errText = r.errors.length > 0 ? '<br><small class="text-danger">' + r.errors.join('; ') + '</small>' : '';
                tbodyHtml += '<td>' + statusBadge + errText + '</td>';
                tbodyHtml += '</tr>';
            });

            $('#bulk_preview_tbody').html(tbodyHtml);

            // Summary Alert
            var alertClass = errors_found > 0 ? 'alert-warning' : 'alert-success';
            var alertMessage = '<strong>Parsed ' + rows.length + ' rows:</strong> Matched ' + total_students + ' registered students.';
            if (errors_found > 0) {
                alertMessage += ' Found <strong>' + errors_found + ' validation warnings/errors</strong>. Invalid rows will be skipped during import.';
            } else {
                alertMessage += ' All rows are valid and ready to import!';
            }

            $('#bulk_summary_alert').attr('class', 'alert ' + alertClass).html(alertMessage);
            $('#bulk_records_info').text('Total Matched Students: ' + total_students);

            $('#bulk_preview_area').slideDown();
        }

        // Confirm & Import Marks
        $('#btn_confirm_bulk_import').click(function() {
            if (!global_parsed_payload || !global_parsed_payload.parsed_rows) {
                errorMsg("No preview data available.");
                return false;
            }

            var exam_id = $('#bulk_exam_id').val();
            var $btn = $(this);
            $btn.button('loading');

            $.ajax({
                url: baseurl + "cbseexam/marks/import_bulk_marks",
                type: 'POST',
                data: {
                    exam_id: exam_id,
                    rows_data: JSON.stringify(global_parsed_payload.parsed_rows)
                },
                dataType: 'JSON',
                success: function(res) {
                    $btn.button('reset');
                    if (res.status === 1) {
                        successMsg(res.message);
                        $('#bulkUploadModal').modal('hide');
                        // Trigger search to refresh main grid if search filters were filled
                        if ($('#exam_id').val() !== "") {
                            $('#btn_search').trigger('click');
                        }
                    } else {
                        errorMsg(res.error || "Failed to import marks.");
                    }
                },
                error: function() {
                    $btn.button('reset');
                    errorMsg("Error importing marks into database.");
                }
            });
        });

        // Class change, get sections
        $('#class_id').change(function () {
            var class_ids = $(this).val();
            $('#section_id').html('');
            $('#exam_id').html('<option value=""><?php echo $this->lang->line('select'); ?></option>');
            $('#subject_id').html('<option value=""><?php echo $this->lang->line('select'); ?></option>');
            
            if (class_ids && class_ids.length > 0) {
                $.ajax({
                    type: "POST",
                    url: baseurl + "cbseexam/marks/get_sections_multi",
                    data: {'class_ids': class_ids},
                    dataType: "json",
                    success: function (data) {
                        $.each(data, function (i, obj) {
                            $('#section_id').append($('<option>').text(obj.section).attr('value', obj.section_id));
                        });
                    }
                });
            }
        });

        // Section change, get exams
        $('#section_id').change(function () {
            var class_ids = $('#class_id').val();
            var section_ids = $(this).val();
            $('#exam_id').html('<option value=""><?php echo $this->lang->line('select'); ?></option>');
            $('#subject_id').html('<option value=""><?php echo $this->lang->line('select'); ?></option>');
            
            if (class_ids && class_ids.length > 0 && section_ids && section_ids.length > 0) {
                $.ajax({
                    type: "POST",
                    url: baseurl + "cbseexam/marks/get_exams_multi",
                    data: {'class_ids': class_ids, 'section_ids': section_ids},
                    dataType: "json",
                    success: function (data) {
                        $.each(data, function (i, obj) {
                            $('#exam_id').append($('<option>').text(obj.name).attr('value', obj.id));
                        });
                    }
                });
            }
        });

        // Exam change, get subjects
        $('#exam_id').change(function () {
            var exam_id = $(this).val();
            var class_ids = $('#class_id').val();
            $('#subject_id').html('<option value=""><?php echo $this->lang->line('select'); ?></option>');
            
            if (exam_id !== "") {
                $.ajax({
                    type: "POST",
                    url: baseurl + "cbseexam/exam/getexamSubjects",
                    data: {'exam_id': exam_id, 'class_ids': class_ids},
                    dataType: "json",
                    success: function (data) {
                        if(data.exam_subjects) {
                            $.each(data.exam_subjects, function (i, obj) {
                                $('#subject_id').append($('<option>').text(obj.subject_name + " (" + obj.subject_code + ")").attr('value', obj.subject_id).attr('data-timetable_id', obj.id));
                            });
                        }
                    }
                });
            }
        });

        // Subject change, get timetable id
        $('#subject_id').change(function() {
            var timetable_id = $(this).find(':selected').data('timetable_id');
            $('#timetable_id').val(timetable_id);
        });

        // CBSE Report Card — open standard report cards for the selected class in a new tab
        $('#btn_report_card').click(function() {
            var exam_id = $('#exam_id').val();
            var class_id = $('#class_id').val();
            var section_id = $('#section_id').val();
            if (!class_id || class_id.length === 0 || exam_id === "") {
                errorMsg("Please select class and exam first.");
                return false;
            }
            var url = baseurl + 'cbseexam/marks/cbse_reportcard/' + encodeURIComponent(exam_id) + '/' + encodeURIComponent(class_id[0]);
            if (section_id && section_id.length) {
                url += '/' + encodeURIComponent(section_id[0]);
            }
            window.open(url, '_blank');
        });

        // Search Button Click
        $('#btn_search').click(function() {
            var subject_id = $('#subject_id').val();
            var timetable_id = $('#timetable_id').val();
            var exam_id = $('#exam_id').val();
            var class_id = $('#class_id').val();
            var section_id = $('#section_id').val();

            if (!class_id || class_id.length === 0 || !section_id || section_id.length === 0 || exam_id === "" || subject_id === "") {
                errorMsg("Please select all mandatory fields");
                return false;
            }

            var $btn = $(this);
            $btn.button('loading');

            $.ajax({ 
                type: 'POST',
                url: baseurl + "cbseexam/marks/subjectstudent_multi",
                data: {
                    exam_id: exam_id,
                    subject_id: subject_id,
                    timetable_id: timetable_id,
                    class_ids: class_id,
                    section_ids: section_id
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#marks_entry_container').show();
                    $('.marksEntryForm').html(data.page);

                    // Inline per-subject upload (drag & drop / Submit / Export Sample)
                    // is intentionally NOT shown here - use "Bulk Exam Marks Upload".
                    $('#marks_header_actions').empty().hide();
                    $btn.button('reset');
                },
                error: function() {
                    errorMsg("Error loading students");
                    $btn.button('reset');
                }
            });
        });

        $('#btn_generate_rank').click(function() {
            var exam_id = $('#exam_id').val();
            var rank_type = $('input[name="rank_type"]:checked').val();
            
            if (exam_id === "") {
                errorMsg("Please select an exam first");
                return false;
            }
            
            var $btn = $(this);
            $btn.button('loading');
            
            $.ajax({
                type: 'POST',
                url: baseurl + 'cbseexam/exam/updateExamRank/' + exam_id,
                data: {exam_id: exam_id, rank_type: rank_type},
                dataType: 'JSON',
                success: function (data) {
                    if (data.status == "1") {
                        successMsg(data.message);
                    } else {
                        errorMsg(data.error);
                    }
                    $btn.button('reset');
                },
                error: function() {
                    errorMsg("Error generating rank");
                    $btn.button('reset');
                }
            });
        });
    });
</script>

