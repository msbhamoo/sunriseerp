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
                            
                            <div class="col-md-12 text-right" style="margin-top: 10px;">
                                <div class="rank-option-container">
                                    <label class="radio-inline"><input type="radio" name="rank_type" value="combined" checked> <strong>Combined</strong> (All Sections)</label>
                                    <label class="radio-inline"><input type="radio" name="rank_type" value="section"> <strong>Section-wise</strong></label>
                                    <button type="button" class="btn btn-sc-warning" id="btn_generate_rank"><i class="fa fa-line-chart"></i> Generate Rank</button>
                                </div>
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

<script type="text/javascript">
    $(document).ready(function() {
        
        $('.select2').select2();

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
                        // Assuming the API returns a list of exam_subjects
                        // Let's iterate if it has exam_subjects
                        if(data.exam_subjects) {
                            $.each(data.exam_subjects, function (i, obj) {
                                // obj.subject_id is the batch subject id
                                // obj.id is the timetable id
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
                    
                    var headerHtml = $('.marksEntryForm').find('#header_action_template').html();
                    if (headerHtml) {
                        $('#marks_header_actions').html(headerHtml).show();
                        if ($.fn.dropify) {
                            $('#marks_header_actions').find('.dropify').dropify();
                        }
                    }
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
