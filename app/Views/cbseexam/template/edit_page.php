<script src="<?php echo base_url(); ?>backend/plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url(); ?>backend/js/ckeditor_config.js"></script>
<?php $this->load->view('layout/cbseexam_css.php'); ?>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-map-o"></i> <?php echo $this->lang->line('edit_template'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-edit"></i> <?php echo $this->lang->line('edit_template'); ?></h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo base_url(); ?>cbseexam/template" class="btn btn-sm btn-primary"><i class="fa fa-list"></i> <?php echo $this->lang->line('template_list'); ?></a>
                        </div>
                    </div>
                    <form role="form" id="edit_form" method="post" enctype="multipart/form-data" action="">
                        <input type="hidden" name="templateid" value="<?php echo $result['id']; ?>">
                        <div class="box-body">
                            
                            <!-- BASIC INFO SECTION -->
                            <h4 class="page-header mt0"><?php echo $this->lang->line('basic_details'); ?></h4>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('template'); ?> Name</label><small class="req"> *</small>
                                        <input type="text" id="name" name="name" class="form-control" value="<?php echo $result['name']; ?>" autofocus>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                        <select id="editclassid" name="class_id[]" onchange="getSectionByClasseditMultiple()" class="form-control select2" multiple="multiple" style="width:100%">
                                            <?php foreach ($classlist as $class) {
                                                $selected = in_array($class['id'], $selected_class_id) ? 'selected' : '';
                                            ?>
                                                <option value="<?php echo $class['id'] ?>" <?php echo $selected; ?>><?php echo $class['class'] ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="text-danger" id="error_class_id"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group relative z-index-6">
                                        <label><?php echo $this->lang->line('section'); ?></label>
                                        <small class="req"> *</small>
                                        <div id="checkbox-dropdown-container">
                                            <div class="">
                                                <div class="custom-select" id="custom-select"><?php echo $this->lang->line('select'); ?></div>
                                                <div class="custom-select-option-box displaynone" id="custom-select-option-box">
                                                    <div class="custom-select-option checkbox">
                                                        <label class="vertical-middle line-h-18">
                                                            <input class="custom-select-option-checkbox select_all" type="checkbox" name="select_all" id="select_all"> <?php echo $this->lang->line('select_all'); ?>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <span class="text-danger" id="error_class_id"></span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="input-type"><?php echo $this->lang->line('marksheet_type'); ?></label>
                                        <div id="input-type" class="row">
                                            <div class="col-sm-6">
                                                <label class="radio-inline">
                                                    <input name="orientation" class="orientation" id="input-type-landscape" value="L" type="radio" <?php echo ($result['orientation'] == "L") ? "checked='checked'" : ""; ?>><?php echo $this->lang->line('landscape'); ?> </label>
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="radio-inline">
                                                    <input name="orientation" class="orientation" id="input-type-portrait" value="P" type="radio" <?php echo ($result['orientation'] == "P") ? "checked='checked'" : ""; ?>><?php echo $this->lang->line('portrait'); ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('template_description'); ?></label>
                                        <textarea type="text" name="description" rows="2" class="form-control"><?php echo $result['description']; ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- PRINTING DETAILS SECTION -->
                            <h4 class="page-header"><?php echo $this->lang->line('printing_details'); ?></h4>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('school_name') ?></label>
                                        <input id="line" value="<?php echo set_value('school_name', $result['school_name']); ?>" name="school_name" placeholder="" type="text" class="form-control" />
                                        <span class="text-danger"><?php echo form_error('line'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('exam_center'); ?></label>
                                        <input id="exam_center" value="<?php echo set_value('exam_center', $result['exam_center']); ?>" name="exam_center" placeholder="" type="text" class="form-control" />
                                        <span class="text-danger"><?php echo form_error('exam_center'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('printing_date'); ?></label>
                                        <input id="date" name="date" value="<?php echo set_value('date', $this->customlib->dateformat($result['date'])); ?>" placeholder="" type="text" class="form-control date" />
                                        <span class="text-danger"><?php echo form_error('date'); ?></span>
                                    </div>
                                </div>
                            </div>

                            <!-- STUDENT ATTRIBUTES SECTION -->
                            <h4 class="page-header"><?php echo $this->lang->line('student_details'); ?> (On/Off)</h4>
                            <div class="row">
                                <?php 
                                $toggles = [
                                    ['id' => 'stu_name', 'name' => 'stu_name', 'label' => 'student_name', 'checked' => $result['is_name']],
                                    ['id' => 'father_name', 'name' => 'father_name', 'label' => 'father_name', 'checked' => $result['is_father_name']],
                                    ['id' => 'mother_name', 'name' => 'mother_name', 'label' => 'mother_name', 'checked' => $result['is_mother_name']],
                                    ['id' => 'examsession', 'name' => 'examsession', 'label' => 'template_academic_session', 'checked' => $result['exam_session']],
                                    ['id' => 'admission_no', 'name' => 'admission_no', 'label' => 'admission_no', 'checked' => $result['is_admission_no']],
                                    ['id' => 'roll_no', 'name' => 'roll_no', 'label' => 'roll_no', 'checked' => $result['is_roll_no']],
                                    ['id' => 'photo', 'name' => 'photo', 'label' => 'photo', 'checked' => $result['is_photo']],
                                    ['id' => 'class', 'name' => 'class', 'label' => 'class', 'checked' => $result['is_class']],
                                    ['id' => 'section', 'name' => 'is_section', 'label' => 'section', 'checked' => $result['is_section']],
                                    ['id' => 'is_dob', 'name' => 'is_dob', 'label' => 'date_of_birth', 'checked' => $result['is_dob']],
                                    ['id' => 'remark', 'name' => 'remark', 'label' => 'teacher_remark', 'checked' => $result['is_remark']],
                                    ['id' => 'subject_note', 'name' => 'subject_note', 'label' => 'subject_note', 'checked' => $result['is_subject_note']]
                                ];

                                foreach($toggles as $toggle) { ?>
                                    <div class="col-md-3 col-sm-6">
                                        <div class="form-group" style="display:flex; justify-content:space-between; align-items:center; padding: 10px; background: #f9f9f9; border: 1px solid #f1f1f1; border-radius: 4px;">
                                            <label style="margin:0; font-weight:normal;"><?php echo $this->lang->line($toggle['label']); ?></label>
                                            <div class="material-switch switchcheck pt0" style="margin:0;">
                                                <input id="<?php echo $toggle['id']; ?>" name="<?php echo $toggle['name']; ?>" type="checkbox" class="chk" value="1" <?php if ($toggle['checked'] == 1) echo "checked"; ?>>
                                                <label for="<?php echo $toggle['id']; ?>" class="label-info-success"></label>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>

                            <!-- MEDIA & SIGNATURES SECTION -->
                            <h4 class="page-header"><?php echo $this->lang->line('signatures_formatting'); ?></h4>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('header_image'); ?> (965px X 150px)</label>
                                        <input id="header_image" name="header_image" placeholder="" type="file" class="filestyle form-control" data-height="40" />
                                        <span class="text-danger"><?php echo form_error('header_image'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('background_image'); ?></label>
                                        <input id="background_img" name="background_img" placeholder="" type="file" class="filestyle form-control" data-height="40" />
                                        <span class="text-danger"><?php echo form_error('background_img'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('left_sign'); ?> (100px X 50px)</label>
                                        <input id="left_sign" name="left_sign" placeholder="" type="file" class="filestyle form-control" data-height="40" />
                                        <span class="text-danger"><?php echo form_error('left_sign'); ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('middle_sign'); ?> (100px X 50px)</label>
                                        <input id="middle_sign" name="middle_sign" placeholder="" type="file" class="filestyle form-control" data-height="40" />
                                        <span class="text-danger"><?php echo form_error('middle_sign'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('right_sign'); ?> (100px X 50px)</label>
                                        <input id="right_sign" name="right_sign" placeholder="" type="file" class="filestyle form-control" data-height="40" />
                                        <span class="text-danger"><?php echo form_error('right_sign'); ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label><?php echo $this->lang->line('footer_text'); ?></label>
                                <textarea class="form-control" id="question_textbox" name="content_footer"><?php echo $result['content_footer']; ?></textarea>
                                <span class="text-danger"><?php echo form_error('content_footer'); ?></span>
                            </div>

                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right" data-loading-text="<i class='fa fa-spinner fa-spin'></i> <?php echo $this->lang->line('submitting') ?>"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    function getSectionByClasseditMultiple() {
        var class_ids = $('#editclassid').val();
        var sections_id = '<?php echo $selected_section_id; ?>';
        
        if (class_ids != null && class_ids.length > 0) {
            $.ajax({
                type: "POST",
                url: baseurl + "cbseexam/template/get_sections_by_multiple_classes",
                data: {'class_ids': class_ids},
                dataType: "json",
                beforeSend: function () {
                    $('#checkbox-dropdown-container .custom-select-option-box').closest('div').find("input[name='select_all']").attr('checked', false);
                    $('#checkbox-dropdown-container .custom-select-option-box').children().not(':first').remove();
                },
                success: function (data) {
                    $.each(data, function (i, obj) {
                        var checked = false;
                        $.each(JSON.parse(sections_id), function (index, val) {
                            if(obj.section_id == val.section_id){
                                checked = true;
                            }
                        });

                        var html = '<div class="custom-select-option checkbox"><label class="vertical-middle line-h-18"><input class="custom-select-option-checkbox" type="checkbox" name="section_id[]" value="' + obj.section_id + '" '+(checked?'checked':'')+'> ' + obj.section + '</label></div>';
                        $('#checkbox-dropdown-container .custom-select-option-box').append(html);
                    });
                }
            });
        }else{
            $('#checkbox-dropdown-container .custom-select-option-box').children().not(':first').remove();
        }
    }

    (function($) {
        "use strict";

        $(document).ready(function() {
            $('#editclassid').select2();
            getSectionByClasseditMultiple();
            
            $('.date').datepicker({
                format: date_format,
                autoclose: true
            });
            
            CKEDITOR.replace('question_textbox', {
                toolbar: 'Ques',
                allowedContent: true,
                enterMode: CKEDITOR.ENTER_BR,
                shiftEnterMode: CKEDITOR.ENTER_P,
                customConfig: baseurl + '/backend/js/ckeditor_config.js'
            });
        });

        $("#edit_form").on('submit', (function(e) {
            e.preventDefault();
            for (var instanceName in CKEDITOR.instances) {
                CKEDITOR.instances[instanceName].updateElement();
            }
            var $this = $(this).find("button[type=submit]:focus");
            $.ajax({
                url: baseurl + "cbseexam/template/edit",
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function() {
                    $this.button('loading');
                },
                success: function(res) {
                    if (res.status == "fail") {
                        var message = "";
                        $.each(res.error, function(index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    } else {
                        successMsg(res.message);
                        window.location.href = baseurl + "cbseexam/template";
                    }
                },
                error: function(xhr) {
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                    $this.button('reset');
                },
                complete: function() {
                    $this.button('reset');
                }
            });
        }));

    })(jQuery);
</script>

<script>
    $(document).on('click', ".custom-select", function() {
        $(".custom-select-option-box").toggle();
    });

    $(".custom-select-option").on("click", function(e) {
        var checkboxObj = $(this).children("input");
        if ($(e.target).attr("class") != "custom-select-option-checkbox") {
            if ($(checkboxObj).prop('checked') == true) {
                $(checkboxObj).prop('checked', false)
            } else {
                $(checkboxObj).prop("checked", true);
            }
        }
    });

    $(document).on('click', function(event) {
        if (event.target.className != "custom-select" && !$(event.target).closest('div').hasClass("custom-select-option")) {
            $(".custom-select-option-box").hide();
        }
    });

    $(document).on('change', '.select_all', function() {
        $(this).closest('#checkbox-dropdown-container').find('input:checkbox').not(this).prop('checked', this.checked);
    });
</script>
