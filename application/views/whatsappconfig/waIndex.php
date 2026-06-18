<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-gears"></i> <?php echo $this->lang->line('system_settings'); ?>
        </h1>
    </section> 
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="nav-tabs-custom theme-shadow">
                    <div class="box-header with-border">
                       <h3 class="box-title titlefix"></i> WhatsApp Setting</h3>
                    </div>
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#tab_1" data-toggle="tab">EasyApp Gateway</a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <form role="form" id="easyapp" action="<?php echo site_url('admin/whatsappconfig/easyapp') ?>" class="form-horizontal" method="post">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="minheight170">
                                            <div class="col-md-7">
                                                <?php
                                                $easyapp_result = null;
                                                if(!empty($whatsapplist)){
                                                    foreach($whatsapplist as $wa) {
                                                        if($wa->api_engine == 'easyapp') {
                                                            $easyapp_result = $wa;
                                                        }
                                                    }
                                                }
                                                ?>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label">Auth Key<small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <input autofocus="" type="text" class="form-control" name="easyapp_auth_key" value="<?php echo $easyapp_result ? $easyapp_result->api_key : ''; ?>">
                                                        <span class=" text text-danger easyapp_auth_key_error"></span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-5 control-label"><?php echo $this->lang->line('status'); ?><small class="req"> *</small></label>
                                                    <div class="col-sm-7">
                                                        <select class="form-control" name="easyapp_status">
                                                            <?php
                                                            foreach ($statuslist as $s_key => $s_value) {
                                                                ?>
                                                                <option 
                                                                    value="<?php echo $s_key; ?>"
                                                                    <?php
                                                                    if ($easyapp_result && $easyapp_result->is_active == $s_key) {
                                                                        echo "selected=selected";
                                                                    }
                                                                    ?>
                                                                    ><?php echo $s_value; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                        </select>
                                                        <span class=" text text-danger easyapp_status_error"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-5 text text-center disblock">
                                                <p>API Integration powered by EasyApp</p>
                                                <button type="button" class="btn btn-info btn-sm" onclick="checkApiStatus()">Check API Status</button>
                                                <div id="api_status_msg" style="margin-top:10px;"></div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <hr>
                                    <h4>Test Message</h4>
                                    <div class="row">
                                        <div class="col-md-7">
                                            <div class="form-group">
                                                <label class="col-sm-5 control-label">Mobile Number<small class="req"> *</small></label>
                                                <div class="col-sm-7">
                                                    <input type="text" class="form-control" id="test_mobile" name="test_mobile" placeholder="e.g. 919876543210">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-7 col-sm-offset-5">
                                                    <button type="button" class="btn btn-primary btn-sm" onclick="sendTestMessage()">Send Test Message</button>
                                                    <span id="test_msg_loader"></span>
                                                    <div id="test_msg_response" style="margin-top:10px;"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <!-- /.box-body -->

                                <div class="box-footer">
                                        <div class="col-md-offset-3">
                                            <?php if ($this->rbac->hasPrivilege('sms_setting', 'can_edit')) {
                                                ?>
                                                <button type="submit" class="btn btn-primary btnleftinfo"><?php echo $this->lang->line('save'); ?></button>&nbsp;&nbsp;<span class="easyapp_loader"></span>
                                            <?php } ?>
                                        </div>       
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
$(document).ready(function () {
    $('#easyapp').on('submit', (function (e) {
        e.preventDefault();
        var $this = $(this).find("button[type=submit]:focus");
        $.ajax({
            url: "<?php echo site_url('admin/whatsappconfig/easyapp') ?>",
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function () {
                $this.button('loading');
            },
            success: function (res)
            {
                if (res.st == 1) {
                    var error = res.msg;
                    for (var i in error) {
                        $('.' + i + '_error').html(error[i]);
                    }
                } else {
                    successMsg(res.msg);
                }
            },
            error: function (xhr) { // if error occured
                $this.button('reset');
            },
            complete: function () {
                $this.button('reset');
            }
        });
    }));
});

function checkApiStatus() {
    $('#api_status_msg').html('<i class="fa fa-spinner fa-spin"></i> Checking...');
    $.ajax({
        url: '<?php echo site_url("admin/whatsappconfig/check_status"); ?>',
        type: 'POST',
        dataType: 'json',
        success: function(res) {
            if(res.status == 'success') {
                $('#api_status_msg').html('<span class="text-success">' + res.msg + '</span>');
            } else {
                $('#api_status_msg').html('<span class="text-danger">' + res.msg + '</span>');
            }
        },
        error: function() {
            $('#api_status_msg').html('<span class="text-danger">Failed to check status.</span>');
        }
    });
}

function sendTestMessage() {
    var mobile = $('#test_mobile').val();
    if(mobile == '') {
        alert('Please enter a mobile number');
        return;
    }
    $('#test_msg_loader').html('<i class="fa fa-spinner fa-spin"></i>');
    $('#test_msg_response').html('');
    
    $.ajax({
        url: '<?php echo site_url("admin/whatsappconfig/test_message"); ?>',
        type: 'POST',
        data: {mobile: mobile},
        dataType: 'json',
        success: function(res) {
            $('#test_msg_loader').html('');
            if(res.status == 'success') {
                $('#test_msg_response').html('<div class="alert alert-success">' + res.msg + '</div>');
            } else {
                $('#test_msg_response').html('<div class="alert alert-danger">' + res.msg + '</div>');
            }
        },
        error: function() {
            $('#test_msg_loader').html('');
            $('#test_msg_response').html('<div class="alert alert-danger">Error sending test message.</div>');
        }
    });
}
</script>
