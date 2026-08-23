<script src="<?php echo base_url(); ?>backend/plugins/ckeditor/ckeditor.js"></script>
<script src="<?php echo base_url(); ?>backend/js/ckeditor_config.js"></script>
<script src="<?php echo base_url(); ?>backend/plugins/ckeditor/adapters/jquery.js"></script>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-bus"></i> <?php //echo $this->lang->line('question'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary" id="route">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('select_criteria'); ?></h3>
                        <div class="box-tools box-tools-sm">
                            <?php if ($this->rbac->hasPrivilege('question_bank', 'can_add')) {?>
                                <button type="button" class="btn btn-primary" onclick="openAiQuestionModal()" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); border: none; font-weight: 700; box-shadow: 0 2px 6px rgba(99, 102, 241, 0.35);"><i class="fa fa-bolt"></i> AI Question Generator</button>
                                <button class="btn btn-primary question-btn" data-recordid="0" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_question'); ?></button>
                            <?php }?>
                            <?php if ($this->rbac->hasPrivilege('import_question', 'can_view')) {?>
                                <button class="btn btn-primary import-question" data-toggle="modal" data-target="#myQuesImportModal" ><i class="fa fa-plus"></i> <?php echo $this->lang->line('import'); ?></button>
                            <?php }?>
                            <?php if ($this->rbac->hasPrivilege('question_bank', 'can_delete')) {?>
                                <button class="btn btn-primary deleteSelected" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><i class="fa fa-trash"></i> <?php echo $this->lang->line('bulk_delete'); ?></button>
                            <?Php }?>
                        </div>
                    </div>
                    <div class="box-body">
                        <form role="form" action="<?php echo site_url('admin/question/questionsearchvalidation') ?>" method="post" class="" id="questionsearchform">
                            <div class="row">
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="col-sm-6 col-md-2">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('class'); ?></label>
                                        <select autofocus="" id="class_id" name="class" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
foreach ($classlist as $class) {
    ?>
                                                <option value="<?php echo $class['id'] ?>" <?php if (set_value('class_id') == $class['id']) {
        echo "selected=selected";
    }
    ?>><?php echo $class['class'] ?></option>
                                                <?php

}
?>
                                        </select>
                                         <span class="text-danger" id="error_class_id"></span>
                                    </div>
                                </div>
                                <div class="col-sm-6 col-md-2">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('section'); ?></label>
                                        <select  id="search_section_id" name="section" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-md-2">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('subject'); ?></label>
                                        <select class="form-control" name="subject">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        <?php
foreach ($subjectlist as $subject_key => $subject_value) {
    ?>
                                            <option value="<?php echo $subject_value['id']; ?>"><?php echo $subject_value['name']; ?> <?php if($subject_value['code']){ echo '('.$subject_value['code'].')'; } ?></option>
                                        <?php
}
?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-md-2">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('question_type'); ?></label>
                                        <select class="form-control" name="question_type">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
foreach ($question_type as $question_type_key => $question_type_value) {
    ?>
                                                    <option value="<?php echo $question_type_key; ?>"><?php echo $question_type_value; ?></option>
                                            <?php
}
?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-md-2">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('question_level'); ?></label>
                                        <select class="form-control" name="question_level" id="question_level">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
foreach ($question_level as $question_level_key => $question_level_value) {
    ?>
                                                    <option value="<?php echo $question_level_key; ?>"><?php echo $question_level_value; ?></option>
                                            <?php
}
?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-md-2">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('created_by'); ?></label>
                                        <select class="form-control" name="created_by" id="created_by">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
foreach ($staff_list as $staff_list_key => $staff_list_value) {
    ?>
                                                    <option value="<?php echo $staff_list_value->id; ?>"><?php echo $staff_list_value->name.' '.$staff_list_value->surname.' ('.$staff_list_value->employee_id.')'; ?></option>
                                            <?php
}
?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12">
                                        <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </div><!--./row-->
                        </form>
                    </div>
                    <div class="box-header ptbnull"></div>
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-users"></i><?php echo $this->lang->line('question_bank'); ?></h3>
                    </div>
                    <div class="box-body table-responsive">
                         <div id="import_msg"></div>
                        <table class="table table-striped table-bordered table-hover all-list" data-export-title="<?php echo $this->lang->line('question_bank'); ?>">
                            <thead>
                                <tr>
                                     <th><?php if ($this->rbac->hasPrivilege('question_bank', 'can_delete')) {?><input type="checkbox" id="masterCheck" value="checkUncheckAll"><?php }?></th>
                                    <th><?php echo $this->lang->line('q_id'); ?></th>
                                    <th><?php echo $this->lang->line('class'); ?></th>
                                    <th><?php echo $this->lang->line('subject') ?></th>
                                    <th><?php echo $this->lang->line('question_type') ?></th>
                                    <th><?php echo $this->lang->line('level'); ?></th>
                                    <th><?php echo $this->lang->line('question') ?></th>
                                    <th><?php echo $this->lang->line('created_by') ?></th>
                                    <th class="pull-right noExport" width="15%"><?php echo $this->lang->line('action'); ?></th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php
function findOption($questionOpt, $find)
{
    foreach ($questionOpt as $quet_opt_key => $quet_opt_value) {
        if ($quet_opt_key == $find) {
            return $quet_opt_value;
        }
    }
    return false;
}

?>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('question') ?></h4>
            </div>
            <form action="<?php echo site_url('admin/question/add'); ?>" method="POST" id="formsubject">
                <div class="modal-body add_question_body">
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('saving') ?>"><?php echo $this->lang->line('save') ?></button>
                </div>
        </div>
        </form>
    </div>
</div>

<!-- Modal -->
<div id="myimgModal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-xl">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title imgModal-title"><?php echo $this->lang->line('images'); ?> </h4>
      </div>
      <div class="modal-body imgModal-body pupscroll">
          <div class="form-group">
            <input type="text" name="search_box" id="search_box" class="form-control" placeholder="<?php echo $this->lang->line('search') ?>..." />
          </div>
          <div class="div_load">
          <div class="loading-overlay">
            <div class="overlay-content"> <?php echo $this->lang->line('loading'); ?> </div>
        </div>
             <label class="total displaynone"></label>
<div class="row" id="media_div">

</div>
<div id="pagination">

</div>
          </div>
      </div>
<div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?> </button>
                <button type="button" class="btn btn-primary add_media"><?php echo $this->lang->line('add'); ?></button>
            </div>
    </div>
  </div>
</div>

<div id="myQuesImportModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <a class="btn btn-primary pull-right btn-xs download_excel mt3" 
                data-toggle="tooltip" title='<?php echo $this->lang->line("download");?>' 
                href="<?php echo site_url('admin/question/exportformat'); ?>" target="_blank"><i class="fa fa-download"></i>
                </a>
                <h4 class="modal-title"> <?php echo $this->lang->line('import_question'); ?></h4>
            </div>
            <form action="<?php echo site_url('admin/question/uploadfile'); ?>" method="POST" id="formimportquestion">
                <div class="modal-body add_question_import_body">
                       <div class="form-group">
                            <label><?php echo $this->lang->line('subject'); ?></label><small class="req"> *</small>
                            <select autofocus="" id="subject_id" name="subject_id" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php
foreach ($subjectlist as $subject) {
    $sub_code = ($subject['code'] != "") ? " (" . $subject['code'] . ")" : "";
    ?>

                                    <option value="<?php echo $subject['id'] ?>" <?php
if (set_value('subject_id') == $subject['id']) {
        echo "selected=selected";
    }
    ?>><?php echo $subject['name'] . $sub_code; ?></option>
                                            <?php
}
?>
                            </select>
                            <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                        </div>
                 <div class="form-group">
                            <label><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                            <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php
foreach ($classlist as $class) {
    ?>
                                    <option value="<?php echo $class['id'] ?>" <?php
if (set_value('class_id') == $class['id']) {
        echo "selected=selected";
    }
    ?>><?php echo $class['class'] ?></option>
                                            <?php
}
?>
                            </select>
                            <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                        </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                <select  id="section_id" name="section_id" class="form-control" >
                                    <option value=""><?php echo $this->lang->line('select'); ?></option>
                                </select>
                                <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                            </div>
                <div class="form-group">
                <label for="exampleInputEmail1"> <?php echo $this->lang->line('attach_file'); ?></label><small class="req"> *</small>
                <input id="my-file-selector" name="file" placeholder="" type="file" class="filestyle form-control"  value="<?php echo set_value('file'); ?>" />
                <span class="text-danger"><?php echo form_error('file'); ?></span>
                </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="load" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('saving'); ?>"><?php echo $this->lang->line('upload') ?></button>
                </div>
        </div>
        </form>
    </div>
</div>

<script type="text/javascript">
    (function ($) {
        'use strict';
        $(document).ready(function () {
            initDatatable('all-list', 'admin/question/getDatatable', [],[], 50,
            [
                {
                    targets: [-1], // last column
                    orderable: false,
                    className: 'dt-body-right dt-head-right'
                },
                {
                    targets: [1], // last column
                    orderable: false,
                    className: 'dt-body-left dt-head-left'
                },
                 {
                    targets: [0], // last column
                    orderable: false,
                }
            ]
            );
        });
    }(jQuery))
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#myModal,#myQuesImportModal,#myimgModal').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        })

        $(document).on('click', '.question-btn', function () {
            var $this=$(this);
            var recordid = $(this).data('recordid');
            $('input[name=recordid]').val(recordid);
            $.ajax({
                type: 'POST',
                url: baseurl + "admin/question/addform",
                data: {'recordid': recordid},
                dataType: 'JSON',
                beforeSend: function () {
                    $this.button('loading');
                },
                success: function (data) {

                var ck= $('#myModal .add_question_body').html(data.page);
                var elem = $('#myModal .add_question_body').find('.ckeditor');
                var contentArray = [];
                var i = 0;
                $(elem).each(function(_, ckeditor) {
                CKEDITOR.env.isCompatible = true;
                CKEDITOR.replace(ckeditor, {
                  toolbar: 'Ques',
                  allowedContent : true,
                  extraPlugins: 'ckeditor_wiris',
                  enterMode : CKEDITOR.ENTER_BR,
                  shiftEnterMode: CKEDITOR.ENTER_P,
                   maxlength: '2',
                  customConfig: baseurl+'/backend/js/ckeditor_config.js',

                  });

                 });

                $('#myModal').modal('show');
                    $this.button('reset');
                },
                error: function (xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                    $this.button('reset');
                },
                complete: function () {
                    $this.button('reset');
                }
            });
        });

        $(document).on('click', '.question-btn-edit', function () {
            var $this = $(this);
            var recordid = $this.data('recordid');
            $('input[name=recordid]').val(recordid);
            $.ajax({
                type: 'POST',
                url: baseurl + "admin/question/editform",
                data: {'recordid': recordid},
                dataType: 'JSON',
                beforeSend: function () {
                    $this.button('loading');
                },
                success: function (data) {
console.log(data);
                if (data.status) {
                var ck= $('#myModal .add_question_body').html(data.page);
                var elem = $('#myModal .add_question_body').find('.ckeditor');
                   $(elem).each(function(_, ckeditor) {
 CKEDITOR.env.isCompatible = true;
                CKEDITOR.replace(ckeditor, {
                  toolbar: 'Ques',
                     allowedContent : true,
                  extraPlugins: 'ckeditor_wiris',
                  enterMode : CKEDITOR.ENTER_BR,
                  shiftEnterMode: CKEDITOR.ENTER_P,
                     customConfig: baseurl+'/backend/js/ckeditor_config.js'
                });
                 });
                $('#myModal').modal('show');
                    }
                    $this.button('reset');
                },
                error: function (xhr) { // if error occured
                   alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                    $this.button('reset');
                },
                complete: function () {
                    $this.button('reset');
                }
            });
        });
    });

    $("form#formimportquestion").submit(function (e) {
     //stop submit the form, we will post it manually.
            event.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var submit_button = form.find(':submit');
            var form_record = $('#formimportquestion')[0];
            var form_data = new FormData(form_record);
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                dataType: 'JSON',
                data: form_data,
                contentType: false,
                cache: false,
                processData:false,
                beforeSend: function () {

               },
                success: function (data) {

             if (data.status == "0") {
             var message = "";
             $.each(data.error, function (index, value) {
              message += value;
            });
            errorMsg(message);
             } else {
            $('#formimportquestion')[0].reset();

             $('#import_msg').html('<div class="alert alert-success text-center">'+data.message+'</div>');
             $('#myQuesImportModal').modal('hide');
            }
                },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
            },
            complete: function () {

            }
            });
    });

   $("form#formsubject").submit(function (e) {
        e.preventDefault(); // avoid to execute the actual submit of the form.
        var form = $(this);
        var url = form.attr('action');
        var submit_button = form.find(':submit');
        var post_params = form.serialize();
        for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
        $.ajax({
            type: "POST",
            url: url,
            data: form.serialize(), // serializes the form's elements.
            dataType: "JSON", // serializes the form's elements.
            beforeSend: function () {
                $("[class$='_error']").html("");
                submit_button.button('loading');
            },
            success: function (data)
            {

            if (!data.status) {
            var message = "";
            $.each(data.error, function (index, value) {
            message += value;

            });
         errorMsg(message);
                } else {
                    location.reload();
                }
            },
            error: function (xhr) { // if error occured
                submit_button.button('reset');
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
            },
            complete: function () {
                submit_button.button('reset');
            }
        });
    });
</script>

<script>
$(document).ready(function(){
    var target_textbox="";
    $(document).on('click','#question,#opt_a,#opt_b,#opt_c,#opt_d,#opt_e',function(){
     getImages(1);
    });
});

function getImages(page,query=""){
         $.ajax({
            type: "POST",
            url: baseurl+'admin/question/getimages',
           data:{page:page, query:query},
            dataType: "JSON", // serializes the form's elements.
            beforeSend: function () {
$('.loading-overlay').css("display", "block");
            },
            success: function (data)
            {

             $('label.total').html("").html("<?php echo $this->lang->line('total_record'); ?>: "+data.count).css("display", "block");

            $('.imgModal-body #media_div').html("").html(data.page);
            $('.imgModal-body #pagination').html("").html(data.pagination);
$('.loading-overlay').css("display", "none");
            },
            error: function (xhr) { // if error occured

                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
$('.loading-overlay').css("display", "none");
            },
            complete: function () {
$('.loading-overlay').css("display", "none");
            }
        });
}

        $(document).on('click', '.img_div_modal', function (event) {
            $('.img_div_modal div.fadeoverlay').removeClass('active');
            $(this).closest('.img_div_modal').find('.fadeoverlay').addClass('active');
        });

 $(document).on('click', '.add_media', function (event) {
            var content_html = $('div#media_div').find('.fadeoverlay.active').find('img').data('img');
            var is_image = $('div#media_div').find('.fadeoverlay.active').find('img').data('is_image');
            var content_name = $('div#media_div').find('.fadeoverlay.active').find('img').data('content_name');
            var content_type = $('div#media_div').find('.fadeoverlay.active').find('img').data('content_type');
            var vid_url = $('div#media_div').find('.fadeoverlay.active').find('img').data('vid_url');
            var content = "";
                if (typeof content_html !== "undefined") {
                    if (is_image === 1) {
                        content = '<img src="' + content_html + '">';
                    }
                    InsertHTML(content);
                    $('#myimgModal').modal('hide');
                }
        });

    function InsertHTML(content_html) {
        var aaa=target_textbox+"_textbox";
        // Get the editor instance that we want to interact with.
        var editor = CKEDITOR.instances[aaa];
        console.log(editor);
        // Check the active editing mode.
        if (editor.mode == 'wysiwyg')
        {
            editor.insertHtml(content_html);
        } else
            alert("<?php echo $this->lang->line('you_must_be_in_wysiwyg_mode'); ?>");
    }

$('#myimgModal').on('shown.bs.modal', function (event) {
      button = $(event.relatedTarget);
      target_textbox = button.data('location');
      console.log(target_textbox);
})

 $('.modal').on("hidden.bs.modal", function (e) { //fire on closing modal box

        if ($('.modal:visible').length) { // check whether parent modal is opend after child modal close
            $('body').addClass('modal-open'); // if open mean length is 1 then add a bootstrap css class to body of the page
        }
    });

 function CKupdate(){
    for ( instance in CKEDITOR.instances ){
    CKEDITOR.instances[instance].setData('');
    }
}

 $(document).on('keyup', '#search_box', function (event) {
         var query = $('#search_box').val();
         getImages(1, query);
        });

  $(document).on('click', '.page-link', function(){
      var page = $(this).data('page_number');
      var query = $('#search_box').val();
      getImages(page, query);
    });

    $(document).on('change', '#class_id', function (e) {
        $('#section_id').html("");
        $('#search_section_id').html("");
        var class_id = $(this).val();
        getSectionByClass(class_id, section_id);
    });

    function getSectionByClass(class_id, section_id) {

        if (class_id != "") {
            $('#section_id').html("");
            $('#search_section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';

            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                beforeSend: function () {
                    $('#section_id').addClass('dropdownloading');
                },
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                    $('#search_section_id').append(div_data);
                },
                complete: function () {
                    $('#section_id').removeClass('dropdownloading');
                }
            });
        }
    }

    $(document).on('change','#question_type',function(){
      if($(this).val() == "singlechoice"){
        $('.ans').show();
        $('.ans_true_false').hide();
        $('.ans_checkbox').hide();
        $('.option_list').show();

      }else if($(this).val() == "true_false"){
        $('.ans').hide();
        $('.ans_true_false').show();
        $('.ans_checkbox').hide();
        $('.option_list').hide();

      }else if($(this).val() == "multichoice"){
        $('.ans_true_false').hide();
        $('.ans_checkbox').show();
        $('.option_list').show();
        $('.ans').hide();

      }else if($(this).val() == "descriptive"){
        $('.ans_true_false').hide();
        $('.ans_checkbox').hide();
        $('.option_list').hide();
        $('.ans').hide();

      }else{
        $('.ans_true_false').hide();
        $('.ans_checkbox').hide();
        $('.option_list').hide();
        $('.ans').hide();

      }
    });
</script>

<script type="text/javascript">
    $(document).on('click','#masterCheck',function(){
     if ($(this).prop("checked")) {
       $("input:checkbox[name^='question_']").prop("checked", true);
     } else {
       $("input:checkbox[name^='question_']").prop("checked", false);
     }
    });

     $(document).on('click', '.deleteSelected', function () {
            var array_delete = [];
             var $this = $(this);
            $.each($("input[name^='question_']:checked"), function () {
                var question_id = $(this).data('questionId');

                array_delete.push(question_id);
            });
            if (array_delete.length === 0) {
                alert("<?php echo $this->lang->line('no_record_selected'); ?>");
            } else {
                if(confirm("<?php echo $this->lang->line('delete_confirm') ?>")) {
                $.ajax({
                type: 'POST',
                url: baseurl + "admin/question/bulkdelete",
                data: {'recordid': array_delete},
                dataType: 'JSON',
                beforeSend: function () {
                    $this.button('loading');
                },
                success: function (data) {
                    if(data.status){
                        successMsg(data.message);
                     table.ajax.reload( null, false );
                    }
                    $this.button('reset');
                },
                error: function (xhr) { // if error occured
                    alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                    $this.button('reset');
                },
                complete: function () {
                    $this.button('reset');
                }
            });
  }
            }
        });
</script>

<!-- Modal: AI Question Generator Direct Creator -->
<div class="modal fade" id="modalAiQuestionGen" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content" style="border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);">
            <div class="modal-header" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #ffffff; padding: 16px 20px;">
                <button type="button" class="close" data-dismiss="modal" style="color: #ffffff; opacity: 0.8;">&times;</button>
                <h4 class="modal-title" style="font-weight: 700; font-size: 16px; display: flex; align-items: center; gap: 8px;">
                    <i class="fa fa-bolt" style="color: #fbbf24;"></i> AI Instant Question Creator
                </h4>
            </div>
            <div class="modal-body" style="padding: 20px 24px; background: #f8fafc;">
                <form id="formAiQuestionGen">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label style="font-weight: 600; font-size: 13px;">1. Class <small class="text-danger">*</small></label>
                                <select id="ai_gen_class" class="form-control" onchange="onAiClassChange()" required>
                                    <option value="">-- Choose Class --</option>
                                    <?php if (!empty($classlist)) {
                                        foreach ($classlist as $cls) { ?>
                                            <option value="<?php echo $cls['id']; ?>" data-name="<?php echo htmlspecialchars($cls['class']); ?>"><?php echo $cls['class']; ?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label style="font-weight: 600; font-size: 13px;">2. Subject <small class="text-danger">*</small></label>
                                <select id="ai_gen_subject" class="form-control" onchange="onAiSubjectChange()" required>
                                    <option value="">-- Choose Class First --</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                            <label style="font-weight: 600; font-size: 13px; margin: 0;">3. Chapter / Syllabus Scope</label>
                            <span id="aiChapterLoadingStatus" style="font-size: 11px; color: #6366f1; display: none;"><i class="fa fa-spinner fa-spin"></i> Loading syllabus...</span>
                        </div>
                        <select id="ai_gen_topic_select" class="form-control" onchange="onTopicSelectChange()">
                            <option value="Complete Syllabus">-- Complete Subject Syllabus (All Chapters) --</option>
                        </select>
                        <input type="text" id="ai_gen_topic_custom" class="form-control" style="display: none; margin-top: 8px;" placeholder="Type custom topic name here...">
                        <small class="text-muted" style="margin-top: 4px; display: block;">Select a specific NCERT chapter from the curriculum or choose "Type Custom Topic".</small>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label style="font-weight: 600; font-size: 13px;">4. Question Type</label>
                                <select id="ai_gen_type" class="form-control">
                                    <option value="singlechoice">Multiple Choice (Single Choice - 1M)</option>
                                    <option value="multichoice">Multiple Choice (Multiple Correct)</option>
                                    <option value="true_false">True / False</option>
                                    <option value="descriptive">Descriptive / Subjective (2M - 5M)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label style="font-weight: 600; font-size: 13px;">5. Difficulty Level</label>
                                <select id="ai_gen_level" class="form-control">
                                    <option value="easy">Easy (Knowledge / Recall)</option>
                                    <option value="medium" selected>Medium (Standard Board Exam)</option>
                                    <option value="hard">Hard (Application / HOTS)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label style="font-weight: 600; font-size: 13px;">6. Number of Questions</label>
                                <input type="number" id="ai_gen_count" class="form-control" value="5" min="1" max="25" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label style="font-weight: 600; font-size: 13px;">7. AI Model Engine</label>
                                <select id="ai_gen_engine" class="form-control">
                                    <option value="gemini">Google Gemini 2.0 Flash (Recommended)</option>
                                    <option value="openrouter_ox">OpenRouter (01-ai/ox-alpha)</option>
                                    <option value="groq">Groq Cloud (LLaMA-3.3 70B)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div id="aiGenStatusAlert" style="display: none; margin-top: 10px;" class="alert"></div>
                </form>
            </div>
            <div class="modal-footer" style="background: #ffffff; border-top: 1px solid #e2e8f0; padding: 14px 20px;">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button type="button" id="btnRunAiQuestionGen" class="btn btn-primary" style="background: linear-gradient(135deg, #8b5cf6 0%, #6366f1 100%); border: none; font-weight: 700; padding: 8px 18px;" onclick="runAiQuestionGeneration()">
                    <i class="fa fa-bolt"></i> Generate & Add to Question Bank
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
function openAiQuestionModal() {
    $('#aiGenStatusAlert').hide();
    $('#modalAiQuestionGen').modal('show');
}

function onAiClassChange() {
    const classId = $('#ai_gen_class').val();
    const subSelect = $('#ai_gen_subject');
    
    subSelect.html('<option value="">-- Loading Mapped Subjects... --</option>').prop('disabled', true);

    if (!classId) {
        subSelect.html('<option value="">-- Choose Class First --</option>').prop('disabled', false);
        return;
    }

    $.ajax({
        url: '<?php echo base_url(); ?>admin/aiexamgenerator/get_subjects_by_class_ajax',
        type: 'POST',
        dataType: 'json',
        data: { class_id: classId },
        success: function(res) {
            subSelect.prop('disabled', false).html('<option value="">-- Choose Subject --</option>');
            if (res.status === 'success' && res.subjects && res.subjects.length > 0) {
                res.subjects.forEach(function(sub) {
                    subSelect.append(`<option value="${sub.id}" data-name="${sub.name}">${sub.name}${sub.code ? ' (' + sub.code + ')' : ''}</option>`);
                });
            } else {
                subSelect.append('<option value="">No subjects mapped</option>');
            }
        },
        error: function() {
            subSelect.prop('disabled', false).html('<option value="">-- Choose Subject --</option>');
        }
    });
}

function onAiSubjectChange() {
    const className = $('#ai_gen_class option:selected').data('name') || '';
    const subjectName = $('#ai_gen_subject option:selected').data('name') || '';
    const topicSelect = $('#ai_gen_topic_select');
    const loadingStatus = $('#aiChapterLoadingStatus');

    topicSelect.html('<option value="Complete Syllabus">-- Complete Subject Syllabus (All Chapters) --</option>');
    $('#ai_gen_topic_custom').hide().val('');

    if (!className || !subjectName) {
        return;
    }

    loadingStatus.show();

    $.ajax({
        url: '<?php echo base_url(); ?>admin/aiexamgenerator/get_or_fetch_chapters_ajax',
        type: 'POST',
        dataType: 'json',
        data: {
            class_name: className,
            subject_name: subjectName,
            api_engine: $('#ai_gen_engine').val() || 'gemini',
            force_reload: 0
        },
        success: function(res) {
            loadingStatus.hide();
            if (res.status === 'success' && res.chapters && res.chapters.length > 0) {
                topicSelect.empty();
                topicSelect.append('<option value="Complete Syllabus">-- Complete Subject Syllabus (All ' + res.chapters.length + ' Chapters) --</option>');
                res.chapters.forEach(function(ch, idx) {
                    topicSelect.append(`<option value="${ch}">Chapter ${idx + 1}: ${ch}</option>`);
                });
                topicSelect.append('<option value="__custom__">✍️ Type Custom Topic...</option>');
            } else {
                topicSelect.append('<option value="__custom__">✍️ Type Custom Topic...</option>');
            }
        },
        error: function() {
            loadingStatus.hide();
            topicSelect.append('<option value="__custom__">✍️ Type Custom Topic...</option>');
        }
    });
}

function onTopicSelectChange() {
    const val = $('#ai_gen_topic_select').val();
    if (val === '__custom__') {
        $('#ai_gen_topic_custom').show().focus();
    } else {
        $('#ai_gen_topic_custom').hide();
    }
}

function runAiQuestionGeneration() {
    const classId = $('#ai_gen_class').val();
    const className = $('#ai_gen_class option:selected').data('name');
    const subjectId = $('#ai_gen_subject').val();
    const subjectName = $('#ai_gen_subject option:selected').data('name');
    
    let topic = $('#ai_gen_topic_select').val();
    if (topic === '__custom__') {
        topic = $('#ai_gen_topic_custom').val().trim() || 'Complete Syllabus';
    }

    const qType = $('#ai_gen_type').val();
    const level = $('#ai_gen_level').val();
    const count = $('#ai_gen_count').val();
    const engine = $('#ai_gen_engine').val();

    if (!classId || !subjectId) {
        alert('Please select both Class and Subject.');
        return;
    }

    const btn = $('#btnRunAiQuestionGen');
    const alertBox = $('#aiGenStatusAlert');

    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Generating with AI...');
    alertBox.removeClass('alert-danger alert-success').addClass('alert-info').text('AI model is generating questions and validating answer keys...').show();

    $.ajax({
        url: '<?php echo base_url(); ?>admin/question/ai_generate_questions_ajax',
        type: 'POST',
        dataType: 'json',
        data: {
            class_id: classId,
            class_name: className,
            subject_id: subjectId,
            subject_name: subjectName,
            topic: topic,
            question_type: qType,
            level: level,
            count: count,
            api_engine: engine
        },
        success: function(res) {
            btn.prop('disabled', false).html('<i class="fa fa-bolt"></i> Generate & Add to Question Bank');
            if (res.status === 'success') {
                alertBox.removeClass('alert-info alert-danger').addClass('alert-success').html(`<strong><i class="fa fa-check-circle"></i> Success:</strong> ${res.message || 'Questions added successfully!'}`);
                
                setTimeout(function() {
                    $('#modalAiQuestionGen').modal('hide');
                    // Refresh question datatable if initialized
                    if ($('#questionsearchform').length) {
                        $('#questionsearchform').trigger('submit');
                    } else {
                        location.reload();
                    }
                }, 1200);
            } else {
                alertBox.removeClass('alert-info alert-success').addClass('alert-danger').text(res.message || 'Failed to generate questions.');
            }
        },
        error: function(xhr, status, err) {
            btn.prop('disabled', false).html('<i class="fa fa-bolt"></i> Generate & Add to Question Bank');
            alertBox.removeClass('alert-info alert-success').addClass('alert-danger').text('Server communication error. Please try again.');
        }
    });
}
</script>

<script type="text/javascript">
$(document).ready(function(){
$(document).on('submit','#questionsearchform',function(e){
    e.preventDefault(); // avoid to execute the actual submit of the form.
    var $this = $(this).find("button[type=submit]:focus");
    var form = $(this);
    var url = form.attr('action');
    var form_data = form.serializeArray();
    form_data.push({name: 'search_type', value: $this.attr('value')});

    $.ajax({
           url: url,
           type: "POST",
           dataType:'JSON',
           data: form_data, // serializes the form's elements.
              beforeSend: function () {
                $('[id^=error]').html("");
                $this.button('loading');

               },
              success: function(response) { // your success handler
                if(!response.status){
                    $.each(response.error, function(key, value) {
                    $('#error_' + key).html(value);

                    });
                }else{

                    initDatatable('all-list', 'admin/question/getDatatable',response.params,[],50,
                            [{
                                    targets: [0],
                                    orderable: true,
                                    className: 'dt-body-left dt-head-left'
                                },
                                {
                                    targets: [-1],
                                    orderable: false,
                                    className: 'dt-right dt-body-right'
                                }
                            ]
                   );

                }
              },
             error: function() { // your error handler
                 $this.button('reset');
             },
             complete: function() {
             $this.button('reset');
             }
         });
        });
    });
</script>