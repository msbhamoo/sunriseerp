<style>
    .logo-card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
    }
    .logo-upload-card {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 12px;
        padding: 16px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 2px 8px rgba(0,0,0,0.02);
        transition: all 0.2s ease;
    }
    .logo-upload-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 4px 16px rgba(0,0,0,0.06);
    }
    .logo-card-header {
        font-size: 14px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .logo-preview-box {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 8px;
        min-height: 120px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 12px;
        margin-bottom: 12px;
    }
    .logo-preview-box img {
        max-width: 100%;
        max-height: 90px;
        object-fit: contain;
    }
    .logo-dimension-badge {
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 12px;
        text-align: center;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-sliders"></i> <?php echo $this->lang->line('system_settings'); ?></h1>
    </section>

    <section class="content">
        <div class="row">
            <?php $this->load->view('setting/_settingmenu'); ?>
            
            <!-- left column -->
            <div class="col-lg-9 col-md-8 col-sm-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix"><i class="fa fa-picture-o text-muted" style="margin-right: 6px;"></i> <?php echo $this->lang->line('logo'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="logo-card-grid">
                            <!-- Print Logo -->
                            <div class="logo-upload-card">
                                <div>
                                    <div class="logo-card-header">
                                        <i class="fa fa-print text-primary"></i> <?php echo $this->lang->line('print_logo'); ?>
                                    </div>
                                    <div class="logo-preview-box">     
                                        <?php if ($result->image == "") { ?>
                                            <img src="<?php echo $this->media_storage->getImageURL('uploads/school_content/logo/images.png') ?>" alt="Print Logo">
                                        <?php } else { ?>
                                            <img src="<?php echo $this->media_storage->getImageURL('uploads/school_content/logo/'.$result->image); ?>" alt="Print Logo">
                                        <?php } ?>
                                    </div>
                                    <div class="logo-dimension-badge">(170px × 184px)</div>
                                </div>
                                <button type="button" class="btn btn-primary btn-block btn-sm upload_logo" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>">
                                    <i class="fa fa-upload"></i> <?php echo $this->lang->line('edit_print_logo'); ?>
                                </button>
                            </div> 

                            <!-- Admin Logo -->
                            <div class="logo-upload-card">
                                <div>
                                    <div class="logo-card-header">
                                        <i class="fa fa-desktop text-success"></i> <?php echo $this->lang->line('admin_logo'); ?>
                                    </div>
                                    <div class="logo-preview-box"> 
                                        <?php if ($result->admin_logo == "") { ?>
                                            <img src="<?php echo $this->media_storage->getImageURL('uploads/school_content/admin_logo/images.png'); ?>" alt="Admin Logo">
                                        <?php } else { ?>
                                            <img src="<?php echo $this->media_storage->getImageURL('uploads/school_content/admin_logo/'.$result->admin_logo); ?>" alt="Admin Logo">
                                        <?php } ?>
                                    </div> 
                                    <div class="logo-dimension-badge">(290px × 51px)</div>
                                </div>
                                <button type="button" class="btn btn-primary btn-block btn-sm upload_admin_logo" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>">
                                    <i class="fa fa-upload"></i> <?php echo $this->lang->line('edit_admin_logo'); ?>
                                </button>
                            </div>

                            <!-- Admin Small Logo -->
                            <div class="logo-upload-card">
                                <div>
                                    <div class="logo-card-header">
                                        <i class="fa fa-square-o text-warning"></i> <?php echo $this->lang->line('admin_small_logo'); ?>
                                    </div>
                                    <div class="logo-preview-box"> 
                                        <?php if ($result->admin_small_logo == "") { ?>
                                            <img src="<?php echo $this->media_storage->getImageURL('uploads/school_content/logo/images.png'); ?>" alt="Small Logo" style="width: 32px; height: 32px;">
                                        <?php } else { ?>
                                            <img src="<?php echo $this->media_storage->getImageURL('uploads/school_content/admin_small_logo/'.$result->admin_small_logo); ?>" alt="Small Logo" style="width: 32px; height: 32px;">
                                        <?php } ?>
                                    </div>    
                                    <div class="logo-dimension-badge">(32px × 32px)</div>
                                </div>
                                <button type="button" class="btn btn-primary btn-block btn-sm upload_admin_small_logo" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>">
                                    <i class="fa fa-upload"></i> <?php echo $this->lang->line('edit_admin_small_logo'); ?>
                                </button>
                            </div>

                            <!-- App Logo -->
                            <div class="logo-upload-card">
                                <div>
                                    <div class="logo-card-header">
                                        <i class="fa fa-mobile text-info" style="font-size: 16px;"></i> <?php echo $this->lang->line('app_logo'); ?>
                                    </div>
                                    <div class="logo-preview-box">    
                                        <?php if ($result->app_logo == "") { ?>
                                            <img src="<?php echo $this->media_storage->getImageURL('uploads/school_content/logo/images.png'); ?>" alt="App Logo">
                                        <?php } else { ?>
                                            <img src="<?php echo $this->media_storage->getImageURL('uploads/school_content/logo/app_logo/'.$result->app_logo); ?>" alt="App Logo">
                                        <?php } ?>
                                    </div>
                                    <div class="logo-dimension-badge">(290px × 51px)</div>
                                </div>
                                <button type="button" class="btn btn-primary btn-block btn-sm upload_app_logo" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>">
                                    <i class="fa fa-upload"></i> <?php echo $this->lang->line('edit_app_logo'); ?>
                                </button>
                            </div>
                        </div>
                    </div>    
                </div>    
            </div><!--/.col (left) -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<div class="modal fade" id="modal-upload_admin_logo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('edit_admin_logo'); ?></h4>
            </div>
            <div class="modal-body upload_logo_body">
               
                <form class="box_upload boxupload has-advanced-upload" method="post" action="<?php echo site_url('schsettings/ajax_editlogo') ?>" enctype="multipart/form-data">
                    <input value="<?php echo $result->id ?>" type="hidden" name="id" id="id_logo_admin"/>
                    <input type="file" name="file" id="file_admin">
                    <!-- Drag and Drop container-->
                    <div class="box__input upload-admin_area"  id="uploadfile_admin">
                        <i class="fa fa-download box__icon"></i>
                        <label>
                            <strong><?php echo $this->lang->line('choose_a_file_or_drag_it_here'); ?></strong>
                        </label>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-upload_app_logo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('edit_app_logo'); ?></h4>
            </div>
            <div class="modal-body upload_logo_body">
                 
                <form class="box_upload boxupload has-advanced-upload" method="post" action="<?php echo site_url('schsettings/ajax_editlogo') ?>" enctype="multipart/form-data">
                    <input value="<?php echo $result->id ?>" type="hidden" name="id" id="id_app_logo"/>
                    <input type="file" name="file" id="file_applogo">
                    <!-- Drag and Drop container-->
                    <div class="box__input upload-app_logo_area"  id="uploadapp_logo">
                        <i class="fa fa-download box__icon"></i>
                        <label>
                            <strong><?php echo $this->lang->line('choose_a_file_or_drag_it_here'); ?></strong>
                        </label>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-upload_admin_small_logo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('edit_admin_small_logo'); ?></h4>
            </div>
            <div class="modal-body upload_logo_body">
                <!-- ==== -->
                <form class="box_upload boxupload has-advanced-upload" method="post" action="<?php echo site_url('schsettings/ajax_editlogo') ?>" enctype="multipart/form-data">
                    <input value="<?php echo $result->id ?>" type="hidden" name="id" id="id_logo_small"/>
                    <input type="file" name="file" id="file_small">
                    <!-- Drag and Drop container-->
                    <div class="box__input upload-small_area"  id="uploadfile_small">
                        <i class="fa fa-download box__icon"></i>
                        <label><strong><?php echo $this->lang->line('choose_a_file_or_drag_it_here'); ?></strong></label>

                    </div>

                </form>
            </div>

        </div>
    </div>
</div>

<div class="modal fade" id="modal-uploadfile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('edit_logo'); ?></h4>
            </div>
            <div class="modal-body upload_logo_body">
                <!-- ==== -->
                <form class="box_upload boxupload has-advanced-upload" method="post" action="<?php echo site_url('schsettings/ajax_editlogo') ?>" enctype="multipart/form-data">
                    <input value="<?php echo $result->id ?>" type="hidden" name="id" id="id_logo"/>
                    <input type="file" name="file" id="file">
                    <!-- Drag and Drop container-->
                    <div class="box__input upload-area"  id="uploadfile">
                        <i class="fa fa-download box__icon"></i>
                        <label><strong><?php echo $this->lang->line('choose_a_file_or_drag_it_here'); ?></strong></label>

                    </div>

                </form>
            </div>

        </div>
    </div>
</div>


<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
    var logo_type = "logo";
    $('.upload_logo').on('click', function (e) {
        e.preventDefault();
        var $this = $(this);
        logo_type = $this.data('logo_type');

        $this.button('loading');
        $('#modal-uploadfile').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    }); 
// set focus when modal is opened
    $('#modal-uploadfile').on('shown.bs.modal', function () {
        $('.upload_logo').button('reset');
    });

    $('.upload_admin_logo').on('click', function (e) {
        e.preventDefault();
        var $this = $(this);
        logo_type = $this.data('logo_type');
        $this.button('loading');
        $('#modal-upload_admin_logo').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    });
// set focus when modal is opened
    $('#modal-upload_admin_logo').on('shown.bs.modal', function () {
        $('.upload_admin_logo').button('reset');
    });

    $('.upload_admin_small_logo').on('click', function (e) {
        e.preventDefault();
        var $this = $(this);
        logo_type = $this.data('logo_type');

        $this.button('loading');
        $('#modal-upload_admin_small_logo').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    });
// set focus when modal is opened
    $('#modal-upload_admin_small_logo').on('shown.bs.modal', function () {
        $('.upload_admin_small_logo').button('reset');
    });

    $(".edit_setting").on('click', function (e) {
        var $this = $(this);
        $this.button('loading');
        $.ajax({
            url: '<?php echo site_url("schsettings/ajax_schedit") ?>',
            type: 'POST',
            data: $('#schsetting_form').serialize(),
            dataType: 'json',

            success: function (data) {

                if (data.status == "fail") {
                    var message = "";
                    $.each(data.error, function (index, value) {

                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(data.message);
                    window.location.reload(true);
                }

                $this.button('reset');
            }
        });
    });
</script>
<script type="text/javascript">
    $(function () {

        // Drag enter
        $('.upload-area').on('dragenter', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $("h1").text("Drop");
        });

        // Drag over
        $('.upload-area').on('dragover', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $("h1").text("Drop");
        });

        // Drop
        $('.upload-area').on('drop', function (e) {
            e.stopPropagation();
            e.preventDefault();

            $("h1").text("Upload");

            var file = e.originalEvent.dataTransfer.files;
            var fd = new FormData();

            fd.append('file', file[0]);
            fd.append("id", $('#id_logo').val());
            fd.append("logo_type", logo_type);

            uploadData(fd);
        });

        // Open file selector on div click
        $("#uploadfile").click(function () {
            $("#file").click();
        });

        // file selected
        $("#file").change(function () {
            var fd = new FormData();

            var files = $('#file')[0].files[0];

            fd.append('file', files);
            fd.append("id", $('#id_logo').val());
            fd.append("logo_type", logo_type);
            uploadData(fd);
        });
    });

// Sending AJAX request and upload file
    function uploadData(formdata) {

        $.ajax({
            url: '<?php echo site_url('schsettings/ajax_editlogo') ?>',
            type: 'post',
            data: formdata,
            contentType: false,
            processData: false,
            dataType: 'json',
            cache: false,

            beforeSend: function () {
                $('#modal-uploadfile').addClass('modal_loading');
            },
            success: function (response) {
                if (response.success) {
                    successMsg(response.message);
                    window.location.reload(true);
                } else {
                    var message = "";
                    $.each(response.error, function (index, value) {
                        message += value;
                    });
                    errorMsg(message);
                }

            },
            error: function (xhr) { // if error occured

            },
            complete: function () {
                $('#modal-uploadfile').removeClass('modal_loading');

            }


        });
    }

    $(function () {

        // Drag enter
        $('.upload-small_area').on('dragenter', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $("h1").text("Drop");
        });

        // Drag over
        $('.upload-small_area').on('dragover', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $("h1").text("Drop");
        });

        // Drop
        $('.upload-small_area').on('drop', function (e) {
            e.stopPropagation();
            e.preventDefault();

            $("h1").text("Upload");

            var file = e.originalEvent.dataTransfer.files;
            var fd = new FormData();

            fd.append('file', file[0]);
            fd.append("id", $('#id_logo_small').val());
            fd.append("logo_type", logo_type);

            uploadSmallData(fd);
        });

        // Open file selector on div click
        $("#uploadfile_small").click(function () {
            $("#file_small").click();
        });

        // file selected
        $("#file_small").change(function () {
            var fd = new FormData();

            var files = $('#file_small')[0].files[0];

            fd.append('file', files);
            fd.append("id", $('#id_logo_small').val());
            fd.append("logo_type", logo_type);
            uploadSmallData(fd);
        });
    });

// Sending AJAX request and upload file
    function uploadSmallData(formdata) {

        $.ajax({
            url: '<?php echo site_url('schsettings/ajax_editadmin_smalllogo') ?>',
            type: 'post',
            data: formdata,
            contentType: false,
            processData: false,
            dataType: 'json',
            cache: false,

            beforeSend: function () {
                $('#modal-upload_admin_small_logo').addClass('modal_loading');
            },
            success: function (response) {

                if (response.success) {
                    successMsg(response.message);
                    window.location.reload(true);
                } else {
                    var message = "";
                    $.each(response.error, function (index, value) {
                        message += value;
                    });
                    errorMsg(message);
                }

            },
            error: function (xhr) { // if error occured

            },
            complete: function () {
                $('#modal-upload_admin_small_logo').removeClass('modal_loading');

            }


        });
    }

    $(function () {

        // Drag enter
        $('.upload-admin_area').on('dragenter', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $("h1").text("Drop");
        });

        // Drag over
        $('.upload-admin_area').on('dragover', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $("h1").text("Drop");
        });

        // Drop
        $('.upload-admin_area').on('drop', function (e) {
            e.stopPropagation();
            e.preventDefault();

            $("h1").text("Upload");

            var file = e.originalEvent.dataTransfer.files;
            var fd = new FormData();

            fd.append('file', file[0]);
            fd.append("id", $('#id_logo_small').val());
            fd.append("logo_type", logo_type);

            uploadadminlData(fd);
        });

        // Open file selector on div click
        $("#uploadfile_admin").click(function () {
            $("#file_admin").click();
        });

        // file selected
        $("#file_admin").change(function () {
            var fd = new FormData();

            var files = $('#file_admin')[0].files[0];

            fd.append('file', files);
            fd.append("id", $('#id_logo_small').val());
            fd.append("logo_type", logo_type);
            uploadadminData(fd);
        });
    });

// Sending AJAX request and upload file
    function uploadadminData(formdata) {

        $.ajax({
            url: '<?php echo site_url('schsettings/ajax_editadmin_adminlogo') ?>',
            type: 'post',
            data: formdata,
            contentType: false,
            processData: false,
            dataType: 'json',
            cache: false,

            beforeSend: function () {
                $('#modal-upload_admin_logo').addClass('modal_loading');
            },
            success: function (response) {

                if (response.success) {
                    successMsg(response.message);
                    window.location.reload(true);
                } else {
                    var message = "";
                    $.each(response.error, function (index, value) {
                        message += value;
                    });
                    errorMsg(message);
                }

            },
            error: function (xhr) { // if error occured

            },
            complete: function () {
                $('#modal-upload_admin_logo').removeClass('modal_loading');

            }


        });
    }

</script>


<script type="text/javascript">
    $('.upload_app_logo').on('click', function (e) {
        e.preventDefault();
        var $this = $(this);
        logo_type = $this.data('logo_type');

        $this.button('loading');
        $('#modal-upload_app_logo').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    });
// set focus when modal is opened
    $('#modal-upload_app_logo').on('shown.bs.modal', function () {
        $('.upload_app_logo').button('reset');
    });

    $(function () {

        // Drag enter
        $('.upload-app_logo_area').on('dragenter', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $("h1").text("Drop");
        });

        // Drag over
        $('.upload-app_logo_area').on('dragover', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $("h1").text("Drop");
        });

        // Drop
        $('.upload-app_logo_area').on('drop', function (e) {
            e.stopPropagation();
            e.preventDefault();

            $("h1").text("Upload");

            var file = e.originalEvent.dataTransfer.files;
            var fd = new FormData();

            fd.append('file', file[0]);
            fd.append("id", $('#id_app_logo').val());
            // fd.append("logo_type", logo_type);

            uploadSmallData(fd);
        });

        // Open file selector on div click
        $("#uploadapp_logo").click(function () {
            $("#file_applogo").click();
        });

        // file selected
        $("#file_applogo").change(function () {
            var fd = new FormData();

            var files = $('#file_applogo')[0].files[0];


            fd.append('file', files);
            fd.append("id", $('#id_app_logo').val());
            // fd.append("logo_type", logo_type);
            uploadAppData(fd);
        });
    });

// Sending AJAX request and upload file
    function uploadAppData(formdata) {

        $.ajax({
            url: '<?php echo site_url('schsettings/ajax_applogo') ?>',
            type: 'post',
            data: formdata,
            contentType: false,
            processData: false,
            dataType: 'json',
            cache: false,

            beforeSend: function () {
                $('#modal-upload_app_logo').addClass('modal_loading');
            },
            success: function (response) {

                if (response.success) {
                    successMsg(response.message);
                    window.location.reload(true);
                } else {
                    var message = "";
                    $.each(response.error, function (index, value) {
                        message += value;
                    });
                    errorMsg(message);
                }

            },
            error: function (xhr) { // if error occured

            },
            complete: function () {
                $('#modal-upload_app_logo').removeClass('modal_loading');

            }


        });
    }
</script>