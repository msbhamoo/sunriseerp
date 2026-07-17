<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-gears"></i> <?php echo $this->lang->line('system_settings'); ?>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php $this->load->view('setting/_settingmenu'); ?>
            <div class="col-lg-9 col-md-8 col-sm-8">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-bell"></i> System Notifications Setting</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-hover table-striped">
                                        <thead>
                                            <tr>
                                                <th>Event Name</th>
                                                <th class="text-right">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($settings as $setting) { ?>
                                                <tr>
                                                    <td><?php echo $setting['title']; ?></td>
                                                    <td class="text-right">
                                                        <label class="switch">
                                                            <input type="checkbox" class="setting_checkbox" data-type="<?php echo $setting['type']; ?>" <?php if ($setting['is_active'] == 'yes') { echo 'checked'; } ?>>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.setting_checkbox').change(function () {
            var type = $(this).data('type');
            var is_active = $(this).is(':checked') ? 1 : 0;
            
            $.ajax({
                type: "POST",
                url: baseurl + "admin/systemnotificationsetting/change_status",
                data: {'type': type, 'is_active': is_active},
                dataType: "json",
                success: function (data) {
                    if (data.status == "success") {
                        successMsg(data.msg);
                    }
                }
            });
        });
    });
</script>

<style>
/* Switch styles for toggles */
.switch {
  position: relative;
  display: inline-block;
  width: 40px;
  height: 24px;
}
.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}
.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}
.slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}
input:checked + .slider {
  background-color: #2196F3;
}
input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}
input:checked + .slider:before {
  -webkit-transform: translateX(16px);
  -ms-transform: translateX(16px);
  transform: translateX(16px);
}
.slider.round {
  border-radius: 34px;
}
.slider.round:before {
  border-radius: 50%;
}
</style>
