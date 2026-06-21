<?php
$signatures = [
    'sign_principal' => 'Principal',
    'sign_vice_principal' => 'Vice Principal',
    'sign_class_teacher' => 'Class Teacher',
    'sign_exam_incharge' => 'Examination In-charge',
    'sign_academic_coordinator' => 'Academic Coordinator',
    'sign_headmaster' => 'Headmaster / Headmistress',
    'sign_manager' => 'Manager',
    'sign_secretary' => 'Secretary',
    'sign_director' => 'Director',
    'sign_chairman' => 'Chairman',
    'sign_admin_officer' => 'Administrative Officer',
    'sign_accounts_officer' => 'Accounts Officer',
    'sign_admission_incharge' => 'Admission In-charge',
    'sign_cbse_coordinator' => 'CBSE Coordinator',
    'sign_school_seal' => 'School Seal / Stamp'
];
?>

<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <?php $this->load->view('setting/_settingmenu'); ?>
            
            <div class="col-lg-9 col-md-8 col-sm-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Upload Signatures & Seals</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <?php foreach($signatures as $field_name => $display_name): ?>
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <div class="card-body-logo" style="margin-bottom: 20px; min-height: 250px; display: flex; flex-direction: column; justify-content: space-between; padding: 15px; border: 1px solid #e1e1e1; border-radius: 4px; background: #fff;">
                                        <div>
                                            <h4 style="font-size: 14px; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; border-bottom: 1px solid #f4f4f4; padding-bottom: 10px; margin-top: 0; color: #333;" title="<?php echo $display_name; ?>"><?php echo $display_name; ?></h4> 
                                            
                                            <a href="#modal-upload_signature" class="upload_signature_btn signature-img-container" data-signature_type="<?php echo $field_name; ?>" data-title="<?php echo $display_name; ?>" style="position: relative; display: block; text-align: center; margin: 15px 0; cursor: pointer; text-decoration: none !important; color: inherit; padding: 10px; border-radius: 4px; border: 1px dashed transparent; transition: all 0.3s;" onmouseover="this.style.border='1px dashed #ccc'; this.style.background='#f9f9f9'; this.querySelector('.signature-overlay').style.opacity='1';" onmouseout="this.style.border='1px dashed transparent'; this.style.background='transparent'; this.querySelector('.signature-overlay').style.opacity='0';">
                                                <?php if (empty($result->{$field_name})) { ?>
                                                    <div class="card-body-logo-img" style="height: 80px; display: flex; align-items: center; justify-content: center;">
                                                        <img src="<?php echo $this->media_storage->getImageURL('uploads/school_content/logo/images.png') ?>" style="max-height: 80px; max-width: 100%; opacity: 0.15;" alt="">
                                                    </div>
                                                <?php } else { ?>
                                                    <div class="card-body-logo-img" style="height: 80px; display: flex; align-items: center; justify-content: center;">
                                                        <img src="<?php echo $this->media_storage->getImageURL('uploads/school_content/signatures/'.$result->{$field_name}); ?>" style="max-height: 80px; max-width: 100%;" alt="">
                                                    </div>
                                                <?php } ?>
                                                
                                                <div class="signature-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(255,255,255,0.8); display: flex; align-items: center; justify-content: center; opacity: 0; transition: opacity 0.3s; border-radius: 4px;">
                                                    <span style="color: #333; font-weight: 600; font-size: 13px;"><i class="fa fa-pencil" style="margin-right: 5px;"></i> Click to Update</span>
                                                </div>
                                                
                                                <p class="text-muted ptt10" style="font-size: 11px; margin-bottom: 0;">(Max Height: 100px)</p>
                                            </a>    
                                        </div>

                                        <div>
                                            <div class="form-group" style="margin-bottom: 0;">
                                                <label style="font-size: 12px; font-weight: normal; color: #666; display: block; text-align: left;">Map to Staff:</label>
                                                <select class="form-control signature_staff_mapping input-sm" data-signature_type="<?php echo $field_name; ?>_staff">
                                                    <option value="">Select Staff</option>
                                                    <?php foreach ($staffs as $staff) { 
                                                        $staff_name = $staff['name'] . ' ' . $staff['surname'] . ' (' . $staff['employee_id'] . ')';
                                                        $staff_col = $field_name . '_staff';
                                                        $selected = (isset($result->{$staff_col}) && $result->{$staff_col} == $staff['id']) ? 'selected' : '';
                                                    ?>
                                                        <option value="<?php echo $staff['id']; ?>" <?php echo $selected; ?>><?php echo $staff_name; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>    
                                </div> 
                            <?php endforeach; ?>
                        </div>
                    </div>    
                </div>    
            </div>
        </div>
    </section>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="modal-upload_signature" tabindex="-1" role="dialog" aria-labelledby="signatureModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="signatureModalLabel">Upload Signature</h4>
            </div>
            <div class="modal-body upload_logo_body">
                <form class="box_upload boxupload has-advanced-upload" method="post" action="<?php echo site_url('schsettings/ajax_edit_signature') ?>" enctype="multipart/form-data">
                    <input value="<?php echo $result->id; ?>" type="hidden" name="id" id="signature_setting_id"/>
                    <input type="hidden" name="signature_type" id="signature_type_field" value=""/>
                    <input type="file" name="file" id="file_signature" style="display:none;">
                    
                    <div class="box__input upload-signature_area" id="uploadfile_signature" style="cursor: pointer;">
                        <i class="fa fa-download box__icon"></i>
                        <label style="cursor: pointer;">
                            <strong><?php echo $this->lang->line('choose_a_file_or_drag_it_here'); ?></strong>
                        </label>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var base_url = '<?php echo base_url(); ?>';
    var current_signature_type = "";

    $('.upload_signature_btn').on('click', function (e) {
        e.preventDefault();
        var $this = $(this);
        current_signature_type = $this.data('signature_type');
        var modalTitle = "Upload " + $this.data('title');
        
        $('#signature_type_field').val(current_signature_type);
        $('#signatureModalLabel').text(modalTitle);

        $this.button('loading');
        $('#modal-upload_signature').modal({
            show: true,
            backdrop: 'static',
            keyboard: false
        });
    }); 

    $('#modal-upload_signature').on('shown.bs.modal', function () {
        $('.upload_signature_btn').button('reset');
    });

    $(function () {
        $('.signature_staff_mapping').on('change', function() {
            var signature_type = $(this).data('signature_type');
            var staff_id = $(this).val();

            $.ajax({
                url: '<?php echo site_url('schsettings/ajax_edit_signature_staff') ?>',
                type: 'post',
                data: {signature_type: signature_type, staff_id: staff_id},
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        successMsg(response.message);
                    } else {
                        errorMsg(response.error);
                    }
                },
                error: function (xhr) { 
                    errorMsg("An error occurred while saving staff mapping.");
                }
            });
        });

        $('.upload-signature_area').on('dragenter dragover', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $("h1").text("Drop");
        });

        $('.upload-signature_area').on('drop', function (e) {
            e.stopPropagation();
            e.preventDefault();
            $("h1").text("Upload");

            var file = e.originalEvent.dataTransfer.files;
            var fd = new FormData();
            fd.append('file', file[0]);
            fd.append("id", $('#signature_setting_id').val());
            fd.append("signature_type", current_signature_type);

            uploadSignatureData(fd);
        });

        $("#uploadfile_signature").click(function () {
            $("#file_signature").click();
        });

        $("#file_signature").change(function () {
            var files = $('#file_signature')[0].files[0];
            if(!files) return;

            var fd = new FormData();
            fd.append('file', files);
            fd.append("id", $('#signature_setting_id').val());
            fd.append("signature_type", current_signature_type);
            uploadSignatureData(fd);
        });
    });

    function uploadSignatureData(formdata) {
        $.ajax({
            url: '<?php echo site_url('schsettings/ajax_edit_signature') ?>',
            type: 'post',
            data: formdata,
            contentType: false,
            processData: false,
            dataType: 'json',
            cache: false,
            beforeSend: function () {
                $('#modal-upload_signature').addClass('modal_loading');
            },
            success: function (response) {
                if (response.success) {
                    successMsg(response.message);
                    window.location.reload(true);
                } else {
                    var message = "";
                    if(typeof response.error === 'object'){
                        $.each(response.error, function (index, value) {
                            message += value;
                        });
                    } else {
                        message = response.error;
                    }
                    errorMsg(message);
                }
            },
            error: function (xhr) { 
                errorMsg("An error occurred during upload.");
            },
            complete: function () {
                $('#modal-upload_signature').removeClass('modal_loading');
            }
        });
    }
</script>
