<style>
    .setting-section-title {
        font-size: 14px;
        font-weight: 800;
        color: #0f172a;
        margin: 20px 0 14px 0;
        padding-bottom: 8px;
        border-bottom: 2px solid #f1f5f9;
        display: flex;
        align-items: center;
        gap: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .setting-section-title i {
        color: var(--primary-theme-color, #4f46e5);
        font-size: 16px;
    }
    .form-group.row {
        margin-bottom: 16px;
        display: flex;
        align-items: center;
    }
    .form-group.row label {
        font-weight: 700;
        color: #334155;
        font-size: 13px;
        margin-bottom: 0;
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
                            
                <!-- general form elements -->
                <div class="box box-primary">                    
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix"><i class="fa fa-gear text-muted" style="margin-right: 6px;"></i> <?php echo $this->lang->line('general_setting'); ?></h3>
                    </div><!-- /.box-header -->
                    
                    <div class="box-body" style="padding-top: 15px;">
                        <div class="alert alert-info" style="border-radius: 8px; margin-bottom: 20px; font-weight: 500;">
                            <i class="fa fa-info-circle" style="margin-right: 6px;"></i>
                            <strong><?php echo $this->lang->line('note'); ?>:</strong> <?php echo $this->lang->line('after_saving_general_setting_please_once_logout_then_relogin_so_changes_will_be_come_in_effect'); ?>
                        </div>

                        <form role="form" id="schsetting_form" action="" method="post" enctype="multipart/form-data">
                            <div class="setting-section-title" style="margin-top: 0;">
                                <i class="fa fa-university"></i> School Information
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-4"><?php echo $this->lang->line('school_name'); ?><small class="req"> *</small> </label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="name" name="sch_name" value="<?php echo $result->name; ?>">
                                            <span class="text-danger"><?php echo form_error('name'); ?></span> <input type="hidden" name="sch_id" value="<?php echo $result->id; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-4 text-lg-end"><?php echo $this->lang->line('school_code'); ?></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="dise_code" name="sch_dise_code" value="<?php echo $result->dise_code; ?>">
                                            <span class="text-danger"><?php echo form_error('dise_code'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div><!--./row-->

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-sm-2"><?php echo $this->lang->line('address'); ?><small class="req"> *</small></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="address" name="sch_address" value="<?php echo $result->address; ?>"> <span class="text-danger"><?php echo form_error('address'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div><!--./row-->

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-4"><?php echo $this->lang->line('phone'); ?><small class="req"> *</small></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="phone" name="sch_phone" value="<?php echo $result->phone; ?>"><span class="text-danger"><?php echo form_error('phone'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-4 text-lg-end"><?php echo $this->lang->line('email'); ?><small class="req"> *</small></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control"  id="email" name="sch_email" value="<?php echo $result->email; ?>">
                                            <span class="text-danger"><?php echo form_error('email'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div><!--./row-->

                            <div class="setting-section-title">
                                <i class="fa fa-calendar"></i> <?php echo $this->lang->line('academic_session'); ?>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-4"><?php echo $this->lang->line('session'); ?><small class="req"> *</small> </label>
                                        <div class="col-sm-8">
                                            <select  id="session_id" name="sch_session_id" class="form-control" >
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php foreach ($sessionlist as $session) { ?>
                                                    <option value="<?php echo $session['id'] ?>" <?php
                                                    if ($session['id'] == $result->session_id) {
                                                        echo "selected";
                                                    }
                                                    ?>><?php echo $session['session'] ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('session_id'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-4 text-lg-end"><?php echo $this->lang->line('session_start_month'); ?><small class="req"> *</small></label>
                                        <div class="col-sm-8">
                                            <select  id="start_month" name="sch_start_month" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php foreach ($monthList as $key => $month) { ?>
                                                    <option value="<?php echo $key ?>" <?php
                                                    if ($key == $result->start_month) {
                                                        echo "selected";
                                                    }
                                                    ?> ><?php echo $month ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('start_month'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div><!--./row-->

                            <div class="setting-section-title">
                                <i class="fa fa-clock-o"></i> <?php echo $this->lang->line('date_time'); ?>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label class="col-sm-4"><?php echo $this->lang->line('date_format'); ?><small class="req"> *</small></label>
                                        <div class="col-sm-8">
                                            <select  id="date_format" name="sch_date_format" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php foreach ($dateFormatList as $key => $dateformat) { ?>
                                                    <option value="<?php echo $key ?>" <?php
                                                    if ($key == $result->date_format) {
                                                        echo "selected";
                                                    }
                                                    ?>><?php echo $dateformat; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('date_format'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label class="col-sm-4 text-lg-end"><?php echo $this->lang->line('timezone'); ?><small class="req"> *</small></label>
                                        <div class="col-sm-8"> 
                                            <select  id="language_id" name="sch_timezone" class="form-control" >
                                                <option value="">--<?php echo $this->lang->line('select') ?>--</option>
                                                <?php foreach ($timezoneList as $key => $timezone) { ?>
                                                    <option value="<?php echo $key ?>" <?php
                                                    if ($key == $result->timezone) {
                                                        echo "selected";
                                                    }
                                                    ?> ><?php echo $timezone ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('timezone'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label class="col-sm-5 text-lg-end"><?php echo $this->lang->line('start_day_of_week') ?><small class="req"> *</small></label>
                                        <div class="col-sm-7">
                                            <select  id="start_week" name="sch_start_week" class="form-control" >
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php foreach ($daysList as $day_key => $day_value) { ?>
                                                    <option value="<?php echo $day_key ?>" <?php
                                                    if ($day_key == $result->start_week) {
                                                        echo "selected";
                                                    }
                                                    ?> ><?php echo $day_value ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('sch_start_week'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div><!--./row-->

                            <div class="setting-section-title">
                                <i class="fa fa-money"></i> <?php echo $this->lang->line('currency') ?>
                            </div>

                            <div class="row">                                    
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-4"><?php echo $this->lang->line('currency_format'); ?><small class="req"> *</small></label>
                                        <div class="col-sm-8">
                                            <select  id="currency_format" name="currency_format" class="form-control" >
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php foreach ($currency_formats as $cur_format_key => $cur_format) { ?>
                                                    <option value="<?php echo $cur_format_key ?>" <?php
                                                    if ($cur_format_key == $result->currency_format) {
                                                        echo "selected";
                                                    }
                                                    ?> ><?php echo $cur_format; ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="text-danger"><?php echo form_error('currency_format'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div><!--./row-->

                            <div class="setting-section-title">
                                <i class="fa fa-envelope-o"></i> Email Digest Settings
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group row">
                                        <label class="col-sm-2">Recipients (comma separated)</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="email_digest_recipients" name="email_digest_recipients" value="<?php echo $result->email_digest_recipients; ?>" placeholder="admin@school.com, director@school.com">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label class="col-sm-5 text-lg-end">Daily Digest Time</label>
                                        <div class="col-sm-7">
                                            <input type="time" class="form-control" id="daily_digest_time" name="daily_digest_time" value="<?php echo $result->daily_digest_time; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label class="col-sm-5 text-lg-end">Weekly Digest Time (Sun)</label>
                                        <div class="col-sm-7">
                                            <input type="time" class="form-control" id="weekly_digest_time" name="weekly_digest_time" value="<?php echo $result->weekly_digest_time; ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group row">
                                        <label class="col-sm-5 text-lg-end">Monthly Digest Time (1st)</label>
                                        <div class="col-sm-7">
                                            <input type="time" class="form-control" id="monthly_digest_time" name="monthly_digest_time" value="<?php echo $result->monthly_digest_time; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div><!--./row-->

                            <div class="setting-section-title">
                                <i class="fa fa-folder-open-o"></i> <?php echo $this->lang->line('file_upload_path'); ?>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-4"><?php echo $this->lang->line('base_url'); ?><small class="req"> *</small></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="base_url" name="base_url" value="<?php echo $result->base_url; ?>">
                                            <span class="text-danger"><?php echo form_error('base_url'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group row">
                                        <label class="col-sm-4 text-lg-end"><?php echo $this->lang->line('file_upload_path'); ?><small class="req"> *</small></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control"  id="folder_path" name="folder_path" value="<?php echo $result->folder_path; ?>">
                                            <span class="text-danger"><?php echo form_error('folder_path'); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div><!--./row-->                               
                        </div><!-- /.box-body -->
                        <div class="box-footer" style="background: #f8fafc; border-top: 1px solid #f1f5f9; padding: 15px 20px;">
                            <?php
                            if ($this->rbac->hasPrivilege('general_setting', 'can_edit')) {
                                ?>
                                <button type="button" class="btn btn-primary submit_schsetting pull-right edit_setting" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo $this->lang->line('processing'); ?>" style="padding: 8px 24px; font-weight: 700;"> <i class="fa fa-check" style="margin-right: 4px;"></i> <?php echo $this->lang->line('save'); ?></button>
                                <?php
                            }
                            ?>
                        </div>
                    </form>
                </div>
            </div><!--/.col (left) -->
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

<script type="text/javascript">

    var base_url = '<?php echo base_url(); ?>';

    $(".edit_setting").on('click', function (e) {
        var $this = $(this);
        $this.button('loading');
        $.ajax({
            url: '<?php echo site_url("schsettings/generalsetting") ?>',
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
                }

                $this.button('reset');
            }
        });
    });
    function togglePasswordVisibility(inputId, btn) {
        var input = document.getElementById(inputId);
        var icon = btn.querySelector('i');
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>