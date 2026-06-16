<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-calendar"></i> Class-wise Exam Timetable</h1>
    </section>
    
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Select Criteria</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Class</label><small class="req"> *</small>
                                    <select id="class_id" name="class_id[]" class="form-control select2" multiple="multiple">
                                        <?php foreach ($classlist as $class) { ?>
                                            <option value="<?php echo $class['id'] ?>"><?php echo $class['class'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Section</label><small class="req"> *</small>
                                    <select id="section_id" name="section_id[]" class="form-control select2" multiple="multiple">
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Exam</label><small class="req"> *</small>
                                    <select id="exam_id" name="exam_id[]" class="form-control select2" multiple="multiple">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button type="button" class="btn btn-primary btn-sm" id="btn-search"><i class="fa fa-search"></i> Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-info" id="timetable_result_box" style="display:none;">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-list"></i> Exam Timetable</h3>
                    </div>
                    <div class="box-body" id="timetable_result_body">
                        
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    $('.select2').select2();

    $('#class_id').change(function() {
        var class_ids = $(this).val();
        $('#section_id').html('');
        $('#exam_id').html('');
        if (class_ids && class_ids.length > 0) {
            $.ajax({
                url: base_url + 'cbseexam/marks/get_sections_multi',
                type: 'POST',
                data: {class_ids: class_ids},
                dataType: 'json',
                success: function(res) {
                    $.each(res, function(i, obj) {
                        $('#section_id').append('<option value="'+obj.section_id+'">'+obj.section+'</option>');
                    });
                }
            });
        }
    });

    $('#section_id').change(function() {
        var class_ids = $('#class_id').val();
        var section_ids = $(this).val();
        $('#exam_id').html('');
        if (class_ids && class_ids.length > 0 && section_ids && section_ids.length > 0) {
            $.ajax({
                url: base_url + 'cbseexam/marks/get_exams_multi',
                type: 'POST',
                data: {class_ids: class_ids, section_ids: section_ids},
                dataType: 'json',
                success: function(res) {
                    $.each(res, function(i, obj) {
                        $('#exam_id').append('<option value="'+obj.id+'">'+obj.name+'</option>');
                    });
                }
            });
        }
    });

    $('#btn-search').click(function() {
        var exam_ids = $('#exam_id').val();
        
        loadTimetable(exam_ids);
    });
    
    // Initial load
    loadTimetable([]);

    function loadTimetable(exam_ids) {
        $('#timetable_result_box').show();
        $('#timetable_result_body').html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x"></i></div>');
        
        $.ajax({
            url: base_url + 'cbseexam/timetable/get_timetable',
            type: 'POST',
            data: {exam_ids: exam_ids},
            dataType: 'json',
            success: function(res) {
                if (res.status == 1) {
                    $('#timetable_result_body').html(res.page);
                }
            }
        });
    }
});
</script>
