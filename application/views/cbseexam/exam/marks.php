<style>
.marks-page-wrapper .box.box-primary {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 18px rgba(0,0,0,0.03);
    margin-bottom: 20px;
    overflow: hidden;
}
.marks-page-wrapper .box-header {
    padding: 16px 20px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
}
.marks-page-wrapper .box-header .box-title {
    font-size: 17px;
    font-weight: 700;
    color: #0f172a;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.marks-page-wrapper .box-header .box-tools {
    position: static;
    float: none !important;
}
.marks-page-wrapper .btn-back {
    border-radius: 8px;
    padding: 6px 14px;
    font-weight: 600;
    font-size: 13px;
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none !important;
}
.marks-page-wrapper .btn-back:hover {
    background: #e2e8f0;
    color: #0f172a;
}
.marks-page-wrapper .btn-search-marks {
    background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-weight: 700;
    padding: 8px 18px;
    box-shadow: 0 4px 12px rgba(2, 132, 199, 0.25);
    transition: all 0.2s;
}
.marks-page-wrapper .btn-search-marks:hover {
    background: linear-gradient(135deg, #0369a1 0%, #075985 100%);
    color: #fff;
    transform: translateY(-1px);
}
@media (max-width: 768px) {
    .marks-page-wrapper .box-header {
        flex-direction: column;
        align-items: stretch;
    }
    .marks-page-wrapper .box-header .box-tools {
        width: 100%;
    }
    .marks-page-wrapper .btn-back {
        width: 100%;
        justify-content: center;
        padding: 8px;
    }
    .marks-page-wrapper .btn-search-marks {
        width: 100%;
        padding: 10px;
    }
    .marks-page-wrapper .form-group label.display-block {
        display: none !important;
    }
}
</style>

<div class="content-wrapper marks-page-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-newspaper-o" style="color:#0284c7;"></i> <?php echo $this->lang->line('enter_marks'); ?> - <?php echo $exam['name']; ?></h3>
                        <div class="box-tools">
                            <a href="<?php echo site_url('cbseexam/exam') ?>" class="btn-back"><i class="fa fa-arrow-left"></i> <?php echo $this->lang->line('back'); ?></a>
                        </div>
                    </div>
                    <div class="box-body">
                        <form role="form" id="filter_form" class="row">
                            <input type="hidden" name="exam_id" id="exam_id" value="<?php echo $exam['id']; ?>">
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('subject'); ?> <small class="req"> *</small></label>
                                    <select autofocus="" id="subject_id" name="subject_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
                                        foreach ($subjects as $subject) {
                                            $subject = (array)$subject;
                                            ?>
                                            <option value="<?php echo $subject['subject_id'] ?>" data-timetable_id="<?php echo $subject['id']; ?>"><?php echo $subject['subject_name'] . " (" . $subject['subject_code'] . ")" ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                    <input type="hidden" id="timetable_id" name="timetable_id" value="">
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('class'); ?></label>
                                    <select  id="class_id" name="class_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('all'); ?></option>
                                        <?php
                                        foreach ($classes as $class) {
                                            ?>
                                            <option value="<?php echo $class['id'] ?>"><?php echo $class['class'] ?></option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('section'); ?></label>
                                    <select  id="section_id" name="section_id" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('all'); ?></option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="display-block">&nbsp;</label>
                                    <button type="button" class="btn btn-primary btn-sm" id="btn_search"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
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
        
        // Subject change, get timetable id
        $('#subject_id').change(function() {
            var timetable_id = $(this).find(':selected').data('timetable_id');
            $('#timetable_id').val(timetable_id);
        });

        // Class change, get sections
        $('#class_id').change(function () {
            var class_id = $(this).val();
            $('#section_id').html('<option value=""><?php echo $this->lang->line('all'); ?></option>');
            if (class_id !== "") {
                $.ajax({
                    type: "GET",
                    url: baseurl + "sections/getByClass",
                    data: {'class_id': class_id},
                    dataType: "json",
                    success: function (data) {
                        $.each(data, function (i, obj) {
                            $('#section_id').append($('<option>').text(obj.section).attr('value', obj.section_id));
                        });
                    }
                });
            }
        });

        // Search Button Click
        $('#btn_search').click(function() {
            var subject_id = $('#subject_id').val();
            var timetable_id = $('#timetable_id').val();
            var exam_id = $('#exam_id').val();
            var class_id = $('#class_id').val();
            var section_id = $('#section_id').val();

            if (subject_id === "") {
                errorMsg("Please select subject");
                return false;
            }

            var $btn = $(this);
            $btn.button('loading');

            $.ajax({ 
                type: 'POST',
                url: baseurl + "cbseexam/exam/subjectstudent",
                data: {
                    exam_id: exam_id,
                    subject_id: subject_id,
                    timetable_id: timetable_id,
                    class_id: class_id,
                    section_id: section_id
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
    });
</script>
