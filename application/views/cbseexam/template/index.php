<script src="<?php echo base_url(); ?>backend/plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url(); ?>backend/js/ckeditor_config.js"></script>
<?php $this->load->view('layout/cbseexam_css.php'); ?>
<div class="content-wrapper">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('template_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('cbse_exam_template', 'can_add')) { ?>
                                <a href="<?php echo base_url(); ?>cbseexam/template/create" class="btn btn-sm btn-primary"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add') ?></a>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('template_list'); ?></div>
                        <div class="table-responsive mailbox-messages overflow-visible-lg">
                            <?php if ($this->session->flashdata('msgdelete')) { ?>
                                <?php echo $this->session->flashdata('msgdelete') ?>
                            <?php } ?>
                            <table class="table table-striped table-bordered table-hover example" data-export-title="<?php echo $this->lang->line('template_list'); ?>">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('template'); ?></th>
                                        <th><?php echo $this->lang->line('class_sections'); ?></th>
                                        <th width="60%"><?php echo $this->lang->line('template_description'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($result as $key => $value) {
                                    ?>
                                        <tr>
                                            <td><?php echo $value['name'] ?></td>
                                            <td><?php echo $value['class_sections'] ?></td>
                                            <td><?php echo $value['description'] ?></td>
                                            <td>                                    


                                                <?php if ($this->rbac->hasPrivilege('cbse_exam_template', 'can_view')) { ?>
                                                    <button type="button" class="btn btn-primary btn-xs view_template" id="load" data-toggle="tooltip" data-recordid="<?php echo $value['id']; ?>" data-temp_name="<?php echo $value['name']; ?>" title="<?php echo $this->lang->line('view'); ?>" data-loading-text="<i class='fa fa-spinner fa-spin'></i>"><i class="fa fa-reorder"></i>
                                                    </button>

                                                <?php }
                                                if ($this->rbac->hasPrivilege('cbse_exam_link_exam', 'can_view')) { ?>

                                                    <button type="button" class="btn btn-primary btn-xs linkexam" id="load" data-toggle="tooltip" data-recordid="<?php echo $value['id']; ?>" data-is_weightage="<?php echo $value['is_weightage']; ?>" data-marksheet_type="<?php echo $value['marksheet_type']; ?>" title="<?php echo $this->lang->line('link_exam'); ?>" data-loading-text="<i class='fa fa-spinner fa-spin'></i>"><i class="fa fa-newspaper-o"></i></button>

                                                <?php }
                                                if ($this->rbac->hasPrivilege('cbse_exam_template', 'can_edit')) { ?>

                                                    <a href="<?php echo base_url(); ?>cbseexam/template/edit_page/<?php echo $value['id']; ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>"><i class="fa fa-pencil"></i></a>

                                                <?php }  ?>
                                                
                                                <?php if ($this->rbac->hasPrivilege('cbse_exam_generate_rank', 'can_view')) { ?>
                                                    <a href="<?php echo site_url('cbseexam/template/templatewiserank/'.$value['id']);?>" class="btn btn-primary btn-xs"  data-toggle="tooltip"  title="<?php echo $this->lang->line('generate_rank'); ?>" data-loading-text="<i class='fa fa-spinner fa-spin'></i>"><i class="fa fa-list-alt"></i></a>                                                
                                                <?php } ?>
                                                <?php if ($this->rbac->hasPrivilege('cbse_exam_template', 'can_delete')) { ?>

                                                    <a data-id="<?php echo $value['id'] ?>" class="btn btn-primary btn-xs deletetemplate" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>"><i class="fa fa-remove"></i></a>

                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php
                                     
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>


<div id="viewTemplateModal" class="modal fade modalmark" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" autocomplete="off">×</button>
                <h4 class="modal-title"><?php echo $this->lang->line('template'); ?></h4>
            </div>
            <div class="modal-body minheight260">
                <div class="modal_loader_div" style="display: none;"></div>
                <div class="modal-body-inner">
                </div>
            </div>
        </div>
    </div>
</div>



<div id="linkexamModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('link_exam'); ?></h4>
            </div>
            <form role="form" id="formlink" method="post" enctype="multipart/form-data" action="">
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="hidden" name="template_id" id="template_id">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('marksheet_type') ?></label><small class="req"> *</small>
                                <select id="marksheet" name="marksheet" class="form-control">
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                    <?php
                                    foreach ($marksheet as $marksheet) {
                                    ?>
                                        <option value="<?php echo $marksheet['short_code'] ?>" <?php
                                                                                                if (set_value('marksheet') == $marksheet['short_code']) {
                                                                                                    echo "selected=selected";
                                                                                                }
                                                                                                ?>><?php echo $this->lang->line($marksheet['short_code']); ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <span class="text-danger"><?php echo form_error('marksheet'); ?></span>
                            </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="alert alert-info" style="margin-top:20px; font-size:13px;">
                                <i class="fa fa-info-circle"></i> <b>Understanding Weightage</b>
                                <ul style="margin-bottom:0; padding-left:15px; margin-top:5px;">
                                    <li><b>What is it?</b> Weightage allows you to assign a relative percentage of importance to different exams/terms in the final calculation.</li>
                                    <li><b>Why do we need it?</b> E.g., You may want "Term 1" to contribute only 40% to the final grade, and "Term 2" to contribute 60%.</li>
                                    <li><b>How to add it?</b> When linking multiple exams or terms below, enter the percentage value (e.g., 40, 60) in the "Weightage" column next to the corresponding exam. Ensure the total adds up to exactly 100%.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="row mt15">
                        <div class="col-md-12" id="examdata"></div>
                    </div>
                </div>
                <div class="modal-footer clearboth">
                    <?php if ($this->rbac->hasPrivilege('cbse_exam_link_exam', 'can_view')) { ?>
                        <button type="submit" class="btn btn-primary pull-right" data-loading-text="<?php echo $this->lang->line('submitting') ?>" value=""><?php echo $this->lang->line('save'); ?></button>
                    <?php } ?>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).on('click', '.view_template', function() {

        let recordid = $(this).data('recordid');
        let temp_name = $(this).data('temp_name');
        $.ajax({
            url: baseurl + 'cbseexam/template/viewtemplate',
            type: "POST",
            data: {
                "template_id": recordid
            },
            dataType: 'json',
            beforeSend: function() {
                $('#viewTemplateModal .modal-title').html(temp_name);
                $('#viewTemplateModal .modal-body .modal-body-inner').html("");
                $('#viewTemplateModal .modal-body .modal_loader_div').css("display", "block");
                $('#viewTemplateModal').modal('show');

            },
            success: function(data) {
                $('#viewTemplateModal .modal-body .modal-body-inner').html(data.page);
                $('#viewTemplateModal .modal-body .modal_loader_div').fadeOut(400);
            },
            error: function(xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");

            },
            complete: function() {

            }
        });
    });


    CKEDITOR.replace('question_textbox', {
        toolbar: 'Ques',
        allowedContent: true,
        enterMode: CKEDITOR.ENTER_BR,
        shiftEnterMode: CKEDITOR.ENTER_P,
        customConfig: baseurl + '/backend/js/ckeditor_config.js'
    });    
    


    function add() {
        $('#myModal').modal('show');
        $('#form1').trigger('reset');
        $('#modal-title').html('<?php echo $this->lang->line('add_template'); ?>');
        $('#sections').html('');
    }

    function getSectionByClass(class_id, section_id, select_control) {

        if (class_id != "") {
            var base_url = '<?php echo base_url() ?>';
            var div_data = '';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {
                    'class_id': class_id
                },
                dataType: "json",
                beforeSend: function() {
                    $('.custom-select-option-box').closest('div').find("input[name='select_all']").attr('checked', false);
                    $('.custom-select-option-box').children().not(':first').remove();
                },
                success: function(data) {

                    $.each(data, function(i, obj) {
                        var s = $('<div>', {
                            class: 'custom-select-option checkbox'
                        }).append($('<label>', {
                            class: 'vertical-middle line-h-18',

                        }).append($('<input />', {
                            class: 'custom-select-option-checkbox',
                            type: 'checkbox',
                            name: "section[]",
                            val: obj.id
                        })).append(obj.section));

                        $('.custom-select-option-box', $('#myModal .modal-body')).append(s);

                    });
                },
                complete: function() {

                }
            });
        }
    }

    function edit(template_id) {
        $('#editModal').modal('show');
        $.ajax({
            url: '<?php echo base_url(); ?>cbseexam/template/getdata',
            type: "POST",
            data: {
                template_id: template_id
            },
            dataType: 'json',
            beforeSend: function() {

            },
            success: function(res) {
                console.log(res.page);
                $('#templatedata').html(res.page);
                var elem = $('#editModal .modal-content').find('#ckeditor');
                $(elem).each(function(_, ckeditor) {

                    CKEDITOR.replace(ckeditor, {
                        toolbar: 'Ques',
                        allowedContent: true,
                        enterMode: CKEDITOR.ENTER_BR,
                        shiftEnterMode: CKEDITOR.ENTER_P,
                        customConfig: baseurl + '/backend/js/ckeditor_config.js'
                    });
                });

            },
            error: function(xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
            },
            complete: function() {

            }
        });
    }
</script>

<script type="text/javascript">
    (function($) {
        "use strict";

        $(document).on('click', '.linkexam', function() {
            var $this = $(this);
            $('#linkexamModal').modal('show');
            var recordid = $this.data('recordid');
            var is_weightage = $this.data('is_weightage');
            var marksheet_type = $this.data('marksheet_type');

            $('#template_id').val(recordid);
            $('#is_weightage').val(is_weightage).change();
            $('#marksheet').val(marksheet_type).change();
            if (marksheet_type == '') {

            } else {

            }
        });

        $("#form1").on('submit', (function(e) {
            e.preventDefault();

            for (var instanceName in CKEDITOR.instances) {
                CKEDITOR.instances[instanceName].updateElement();
            }

            var $this = $(this).find("button[type=submit]:focus");
            $.ajax({
                url: baseurl + "cbseexam/template/add",
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
                        window.location.reload(true);
                    }
                },
                error: function(xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                    $this.button('reset');
                },
                complete: function() {
                    $this.button('reset');
                }
            });
        }));

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
                        window.location.reload(true);
                    }
                },
                error: function(xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                    $this.button('reset');
                },
                complete: function() {
                    $this.button('reset');
                }
            });
        }));

        $('.deletetemplate').click(function() {
            var templateid = $(this).attr('data-id');
            if (confirm('<?php echo $this->lang->line('delete_confirm'); ?>')) {
                $.ajax({
                    url: '<?php echo base_url(); ?>cbseexam/template/remove',
                    type: "POST",
                    data: {
                        templateid: templateid
                    },
                    dataType: 'json',
                    success: function(res) {
                        successMsg(res.message);
                        window.location.reload(true);
                    }
                });
            }
        });

        $('#marksheet,#is_weightage').change(function() {
            var marksheet_type = $('#marksheet').val();
            var template_id = $('#template_id').val();
            var weightage = $('#is_weightage').val();
            $('#examdata').html('');
            $.ajax({
                type: 'POST',
                url: baseurl + 'cbseexam/template/get_examdata',
                data: {
                    marksheet_type: marksheet_type,
                    template_id: template_id,
                    weightage: weightage
                },
                dataType: 'json',
                beforeSend: function() {},
                success: function(data) {
                    $('#examdata').html(data.examdata);
                },
                error: function() {},
                complete: function() {}
            });
        });

        $("#formlink").on('submit', (function(e) {
            e.preventDefault();
            var $this = $(this).find("button[type=submit]:focus");
            var inps = document.getElementsByName('lessons[]');
            $.ajax({
                url: baseurl + "cbseexam/template/linkexams",
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
                        window.location.reload(true);
                    }
                },
                error: function(xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                    $this.button('reset');
                },
                complete: function() {
                    $this.button('reset');
                }
            });
        }));

        $(document).ready(function() {
            modal_click_disabled('myModal', 'linkexamModal', 'viewTemplateModal');
            $('.select2').select2();
        });

    })(jQuery);
</script>

<script>
    function getSectionByClassMultiple(class_id, section_id, select_control) {
        var class_ids = $('#searchclassid').val();
        if (class_ids == null || class_ids.length === 0) {
            $('#checkbox-dropdown-container .custom-select-option-box').html("");
            return;
        }
        $('#checkbox-dropdown-container .custom-select-option-box').html("");
        $.ajax({
            type: "POST",
            url: base_url + "cbseexam/template/get_sections_by_multiple_classes",
            data: { 'class_ids': class_ids },
            dataType: "json",
            beforeSend: function() {
                $('#checkbox-dropdown-container .custom-select-option-box').html("");
            },
            success: function(data) {
                var html = '<div class="custom-select-option checkbox"><label class="vertical-middle line-h-18"><input class="custom-select-option-checkbox select_all" type="checkbox" name="select_all" id="select_all" checked> <?php echo $this->lang->line("select_all"); ?></label></div>';
                $.each(data, function(i, obj) {
                    html += '<div class="custom-select-option checkbox"><label class="vertical-middle line-h-18"><input class="custom-select-option-checkbox" type="checkbox" name="section_id[]" value="' + obj.section_id + '" checked> ' + obj.section + '</label></div>';
                });
                $('#checkbox-dropdown-container .custom-select-option-box').append(html);
            }
        });
    }
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