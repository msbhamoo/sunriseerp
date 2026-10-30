<?php
$signatures = [
    'sign_principal' => ['title' => 'Principal', 'icon' => 'fa-graduation-cap text-primary'],
    'sign_vice_principal' => ['title' => 'Vice Principal', 'icon' => 'fa-graduation-cap text-info'],
    'sign_class_teacher' => ['title' => 'Class Teacher', 'icon' => 'fa-user text-success'],
    'sign_exam_incharge' => ['title' => 'Examination In-charge', 'icon' => 'fa-file-text-o text-warning'],
    'sign_academic_coordinator' => ['title' => 'Academic Coordinator', 'icon' => 'fa-book text-primary'],
    'sign_headmaster' => ['title' => 'Headmaster / Headmistress', 'icon' => 'fa-university text-danger'],
    'sign_manager' => ['title' => 'Manager', 'icon' => 'fa-briefcase text-info'],
    'sign_secretary' => ['title' => 'Secretary', 'icon' => 'fa-id-card-o text-success'],
    'sign_director' => ['title' => 'Director', 'icon' => 'fa-star text-warning'],
    'sign_chairman' => ['title' => 'Chairman', 'icon' => 'fa-trophy text-primary'],
    'sign_admin_officer' => ['title' => 'Administrative Officer', 'icon' => 'fa-shield text-danger'],
    'sign_accounts_officer' => ['title' => 'Accounts Officer', 'icon' => 'fa-money text-success'],
    'sign_admission_incharge' => ['title' => 'Admission In-charge', 'icon' => 'fa-user-plus text-info'],
    'sign_cbse_coordinator' => ['title' => 'CBSE Coordinator', 'icon' => 'fa-certificate text-warning'],
    'sign_school_seal' => ['title' => 'School Seal / Stamp', 'icon' => 'fa-stamp text-danger']
];
?>

<style>
    .logo-card-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 16px;
    }
    .logo-upload-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.03);
        transition: all 0.2s ease;
    }
    .logo-upload-card:hover {
        border-color: #cbd5e1;
        box-shadow: 0 6px 18px rgba(15, 23, 42, 0.08);
        transform: translateY(-2px);
    }
    .logo-card-header {
        font-size: 13.5px;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .logo-card-title {
        display: flex;
        align-items: center;
        gap: 8px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .logo-preview-box {
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 8px;
        min-height: 100px;
        max-height: 100px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px;
        margin-bottom: 10px;
        position: relative;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .logo-preview-box:hover {
        border-color: #114B5F;
        background: #f0fdfa;
    }
    .logo-preview-box img {
        max-width: 100%;
        max-height: 80px;
        object-fit: contain;
    }
    .logo-dimension-badge {
        font-size: 11px;
        font-weight: 600;
        color: #64748b;
        margin-bottom: 10px;
        text-align: center;
    }
    .signature-staff-wrapper {
        margin-top: 10px;
        padding-top: 10px;
        border-top: 1px dashed #e2e8f0;
    }
    .signature-staff-label {
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        margin-bottom: 4px;
        display: block;
    }
    .signature-staff-select {
        height: 32px;
        font-size: 12px;
        border-radius: 6px;
        border: 1px solid #cbd5e1;
        color: #1e293b;
        background: #ffffff;
    }
    .btn-signature-upload {
        background-color: #114B5F !important;
        border-color: #114B5F !important;
        color: #ffffff !important;
        font-weight: 600 !important;
        border-radius: 6px !important;
        font-size: 12.5px !important;
        padding: 6px 12px !important;
        transition: all 0.2s ease !important;
    }
    .btn-signature-upload:hover {
        background-color: #0c3847 !important;
        border-color: #0c3847 !important;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-sliders"></i> <?php echo $this->lang->line('system_settings'); ?></h1>
    </section>

    <section class="content">
        <div class="row">
            <?php $this->load->view('setting/_settingmenu'); ?>
            
            <div class="col-lg-9 col-md-8 col-sm-8">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix"><i class="fa fa-pencil-square-o text-muted" style="margin-right: 6px;"></i> Upload Signatures & Seals</h3>
                    </div>
                    <div class="box-body">
                        <div class="logo-card-grid">
                            <?php foreach($signatures as $field_name => $info): 
                                $display_name = is_array($info) ? $info['title'] : $info;
                                $icon_class = is_array($info) ? $info['icon'] : 'fa-pencil text-primary';
                            ?>
                                <div class="logo-upload-card">
                                    <div>
                                        <div class="logo-card-header">
                                            <span class="logo-card-title" title="<?php echo $display_name; ?>">
                                                <i class="fa <?php echo $icon_class; ?>"></i> <?php echo $display_name; ?>
                                            </span>
                                            <?php if (!empty($result->{$field_name})) { ?>
                                                <a href="javascript:void(0);" class="text-danger remove_signature_btn" data-signature_type="<?php echo $field_name; ?>" title="Remove Signature" style="font-size: 12px;">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            <?php } ?>
                                        </div>
                                        
                                        <div class="logo-preview-box upload_signature_btn" data-signature_type="<?php echo $field_name; ?>" data-title="<?php echo $display_name; ?>" title="Click to upload/change">     
                                            <?php if (empty($result->{$field_name})) { ?>
                                                <img src="<?php echo $this->media_storage->getImageURL('uploads/school_content/logo/images.png') ?>" style="opacity: 0.25;" alt="Placeholder">
                                            <?php } else { ?>
                                                <img src="<?php echo $this->media_storage->getImageURL('uploads/school_content/signatures/'.$result->{$field_name}); ?>" alt="<?php echo $display_name; ?>">
                                            <?php } ?>
                                        </div>
                                        <div class="logo-dimension-badge">(Max Height: 100px)</div>
                                    </div>

                                    <div>
                                        <button type="button" class="btn btn-block btn-signature-upload upload_signature_btn" data-signature_type="<?php echo $field_name; ?>" data-title="<?php echo $display_name; ?>">
                                            <i class="fa fa-upload"></i> <?php echo !empty($result->{$field_name}) ? 'Change Signature' : 'Upload Signature'; ?>
                                        </button>

                                        <div class="signature-staff-wrapper">
                                            <label class="signature-staff-label">Map to Staff</label>
                                            <select class="form-control signature_staff_mapping signature-staff-select" data-signature_type="<?php echo $field_name; ?>_staff">
                                                <option value="">-- Unassigned --</option>
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
        <div class="modal-content" style="border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; box-shadow: 0 20px 40px rgba(15,23,42,0.15);">
            <div class="modal-header" style="background: #ffffff; border-bottom: 1px solid #e2e8f0; padding: 16px 20px;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="border-radius: 8px; width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center; background: #f8fafc; border: 1px solid #e2e8f0;"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="signatureModalLabel" style="font-weight: 700; color: #0f172a; font-size: 16px;"><i class="fa fa-upload" style="color: #114B5F; margin-right: 6px;"></i> Upload Signature</h4>
            </div>
            <div class="modal-body upload_logo_body" style="padding: 24px;">
                <form class="box_upload boxupload has-advanced-upload" method="post" action="<?php echo site_url('schsettings/ajax_edit_signature') ?>" enctype="multipart/form-data">
                    <input value="<?php echo $result->id; ?>" type="hidden" name="id" id="signature_setting_id"/>
                    <input type="hidden" name="signature_type" id="signature_type_field" value=""/>
                    <input type="file" name="file" id="file_signature" style="display:none;">
                    
                    <div class="box__input upload-signature_area" id="uploadfile_signature" style="cursor: pointer; border: 2px dashed #cbd5e1; border-radius: 10px; background: #f8fafc; padding: 30px 20px; text-align: center; transition: all 0.2s;">
                        <i class="fa fa-cloud-upload box__icon" style="font-size: 36px; color: #114B5F; margin-bottom: 10px; display: block;"></i>
                        <label style="cursor: pointer; color: #334155; font-size: 14px;">
                            <strong><?php echo $this->lang->line('choose_a_file_or_drag_it_here'); ?></strong>
                        </label>
                        <p style="color: #94a3b8; font-size: 12px; margin-top: 6px; margin-bottom: 0;">Supported formats: PNG, JPG, JPEG (Max Height: 100px)</p>
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

        $('.remove_signature_btn').on('click', function (e) {
            e.stopPropagation();
            var signature_type = $(this).data('signature_type');
            if (confirm("Are you sure you want to remove this signature?")) {
                $.ajax({
                    url: '<?php echo site_url('schsettings/ajax_remove_signature') ?>',
                    type: 'post',
                    data: { signature_type: signature_type },
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            successMsg(response.message);
                            window.location.reload(true);
                        } else {
                            errorMsg(response.error);
                        }
                    },
                    error: function (xhr) { 
                        errorMsg("An error occurred while removing the signature.");
                    }
                });
            }
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
