<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-newspaper-o"></i> <?php echo $this->lang->line('marks_register'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="box-body">
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
                                    <select id="exam_id" name="exam_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('subject'); ?> <small class="req"> *</small></label>
                                    <select id="subject_id" name="subject_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    </select>
                                    <input type="hidden" id="timetable_id" name="timetable_id" value="">
                                </div>
                            </div>
                            
                            <div class="col-md-12 text-right">
                                <div style="display:inline-block; margin-right: 20px; border-right: 1px solid #eee; padding-right: 20px;">
                                    <label class="radio-inline"><input type="radio" name="rank_type" value="combined" checked> Combined (All Sections)</label>
                                    <label class="radio-inline"><input type="radio" name="rank_type" value="section"> Section-wise</label>
                                    <button type="button" class="btn btn-warning btn-sm" id="btn_generate_rank"><i class="fa fa-line-chart"></i> Generate Rank</button>
                                </div>
                                <button type="button" class="btn btn-primary btn-sm" id="btn_search"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Marks Entry Grid Box -->
                <div class="box box-info" id="marks_entry_container" style="display:none;">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-list"></i> <?php echo $this->lang->line('marks_entry'); ?></h3>
                    </div>
                    <div class="box-body marksEntryForm">
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
            $('#subject_id').html('<option value=""><?php echo $this->lang->line('select'); ?></option>');
            
            if (exam_id !== "") {
                $.ajax({
                    type: "POST",
                    url: baseurl + "cbseexam/exam/getexamSubjects",
                    data: {'exam_id': exam_id},
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
