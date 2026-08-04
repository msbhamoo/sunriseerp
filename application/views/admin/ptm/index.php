<?php 
$logged_in_staff_id = $this->customlib->getStaffID();
$is_superadmin = ($this->customlib->getStaffRole() == '{"id":"7","name":"Super Admin"}') || ($this->rbac->hasPrivilege('superadmin', 'can_view'));

$can_show_form = false;
if (isset($ptm)) {
    if ($is_superadmin || $ptm['created_by'] == $logged_in_staff_id) {
        $can_show_form = $this->rbac->hasPrivilege('ptm_parent_teacher_meeting', 'can_edit');
    }
} else {
    $can_show_form = $this->rbac->hasPrivilege('ptm_parent_teacher_meeting', 'can_add');
}
?>
<style type="text/css">
    @media (max-width: 767px) {
        .mobile-card-table thead {
            display: none !important;
        }
        .mobile-card-table, 
        .mobile-card-table tbody, 
        .mobile-card-table tr, 
        .mobile-card-table td {
            display: block !important;
            width: 100% !important;
        }
        .mobile-card-table tr {
            background: #fff !important;
            border: 1px solid #e2e8f0 !important;
            border-radius: 12px !important;
            margin-bottom: 15px !important;
            padding: 12px 15px !important;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04) !important;
        }
        .mobile-card-table td {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            padding: 8px 0 !important;
            border: none !important;
            border-bottom: 1px dashed #edf2f7 !important;
            text-align: right !important;
            font-size: 13px !important;
        }
        .mobile-card-table td:last-child {
            border-bottom: none !important;
            padding-top: 12px !important;
        }
        .mobile-card-table td::before {
            content: attr(data-label);
            font-weight: 700;
            color: #64748b;
            text-align: left;
            padding-right: 10px;
            font-size: 12px;
            text-transform: uppercase;
        }
        .mobile-card-table td.pull-right,
        .mobile-card-table td[data-label="Action"] {
            justify-content: flex-end !important;
        }
        .mobile-card-table td[data-label="Action"] .btn {
            padding: 6px 12px !important;
            font-size: 13px !important;
            margin-left: 5px !important;
            border-radius: 6px !important;
        }
        .box-header .box-tools {
            position: relative !important;
            top: auto !important;
            right: auto !important;
            margin-top: 10px !important;
            float: none !important;
        }
        .box-header .box-tools .btn {
            width: 100% !important;
            display: block !important;
            margin-bottom: 5px !important;
        }
    }
</style>
<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1><i class="fa fa-calendar-check-o"></i> <?php echo $this->lang->line('parent_teacher_meeting'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <?php if ($can_show_form) { ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo isset($ptm) ? $this->lang->line('edit') . ' ' . $this->lang->line('ptm') : $this->lang->line('schedule_ptm'); ?></h3>
                        </div>
                        <form id="form1" action="<?php echo isset($ptm) ? site_url('admin/ptm/edit/'.$ptm['id']) : site_url('admin/ptm/index') ?>" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if (isset($ptm)) { ?>
                                    <input type="hidden" name="id" value="<?php echo $ptm['id']; ?>">
                                <?php } ?>
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg'); $this->session->unset_userdata('msg'); ?>
                                <?php } ?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                
                                <div class="form-group">
                                    <label for="title"><?php echo $this->lang->line('title'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="title" name="title" placeholder="" type="text" class="form-control" value="<?php echo set_value('title', isset($ptm) ? $ptm['title'] : ''); ?>" />
                                    <span class="text-danger"><?php echo form_error('title'); ?></span>
                                </div>
                                <div class="form-group">
                                    <label for="ptm_date"><?php echo $this->lang->line('date'); ?></label><small class="req"> *</small>
                                    <input id="ptm_date" name="ptm_date" type="text" class="form-control date" value="<?php echo set_value('ptm_date', isset($ptm) ? $this->customlib->dateformat($ptm['ptm_date']) : ''); ?>" readonly="readonly"/>
                                    <span class="text-danger"><?php echo form_error('ptm_date'); ?></span>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6 col-md-6">
                                        <div class="form-group">
                                            <label for="time_from"><?php echo $this->lang->line('time_from'); ?></label><small class="req"> *</small>
                                            <div class="input-group">
                                                <input id="time_from" name="time_from" type="text" class="form-control timepicker" value="<?php echo set_value('time_from', isset($ptm) ? $ptm['time_from'] : ''); ?>" />
                                                <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                                            </div>
                                            <span class="text-danger"><?php echo form_error('time_from'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-md-6">
                                        <div class="form-group">
                                            <label for="time_to"><?php echo $this->lang->line('time_to'); ?></label><small class="req"> *</small>
                                            <div class="input-group">
                                                <input id="time_to" name="time_to" type="text" class="form-control timepicker" value="<?php echo set_value('time_to', isset($ptm) ? $ptm['time_to'] : ''); ?>" />
                                                <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                                            </div>
                                            <span class="text-danger"><?php echo form_error('time_to'); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="venue"><?php echo $this->lang->line('venue'); ?></label><small class="req"> *</small>
                                    <?php 
                                    $school_setting = $this->setting_model->get();
                                    $default_venue = isset($school_setting[0]['name']) ? $school_setting[0]['name'] : '';
                                    ?>
                                    <input id="venue" name="venue" type="text" class="form-control" value="<?php echo set_value('venue', isset($ptm) ? $ptm['venue'] : $default_venue); ?>" />
                                    <span class="text-danger"><?php echo form_error('venue'); ?></span>
                                </div>
                                
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('target_audience'); ?></label>
                                    <select class="form-control" name="target_type" id="target_type" onchange="toggleTargets()">
                                        <option value="whole_school" <?php echo (isset($ptm) && $ptm['target_type'] == 'whole_school') ? 'selected' : ''; ?>><?php echo $this->lang->line('whole_school'); ?></option>
                                        <option value="class" <?php echo (isset($ptm) && $ptm['target_type'] == 'class') ? 'selected' : ''; ?>><?php echo $this->lang->line('specific_classes'); ?></option>
                                    </select>
                                </div>

                                <div class="form-group" id="class_section_div" style="<?php echo (isset($ptm) && $ptm['target_type'] == 'class') ? '' : 'display: none;'; ?>">
                                    <label><?php echo $this->lang->line('class'); ?> & <?php echo $this->lang->line('section'); ?></label>
                                    <div class="scroll-area" style="max-height: 200px; overflow-y: auto; border: 1px solid #ddd; padding: 10px; border-radius: 4px;">
                                        <?php foreach ($classlist as $class_key => $class_value) { ?>
                                            <div class="class-group" style="margin-bottom: 8px;">
                                                <div style="font-weight: bold; background: #f7f7f7; padding: 2px 5px; margin-bottom: 3px;">
                                                    <?php echo $class_value['class']; ?>
                                                </div>
                                                <div style="padding-left: 10px;">
                                                    <?php if (!empty($class_value['sections'])) {
                                                        foreach ($class_value['sections'] as $sec) {
                                                            $checked = '';
                                                            if (isset($ptm) && isset($ptm['targets'])) {
                                                                foreach ($ptm['targets'] as $t) {
                                                                    if ($t['class_id'] == $class_value['id'] && $t['section_id'] == $sec['section_id']) {
                                                                        $checked = 'checked';
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                            <label class="checkbox-inline" style="margin-left: 0; margin-right: 10px;">
                                                                <input type="checkbox" name="class_section_id[]" value="<?php echo $class_value['id'] . '-' . $sec['section_id']; ?>" <?php echo $checked; ?>>
                                                                <?php echo $sec['section']; ?>
                                                            </label>
                                                        <?php }
                                                    } ?>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label><?php echo $this->lang->line('description'); ?></label>
                                    <textarea name="description" class="form-control" rows="3"><?php echo set_value('description', isset($ptm) ? $ptm['description'] : ''); ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label><?php echo $this->lang->line('send_notification'); ?></label><br>
                                    <label class="checkbox-inline"><input type="checkbox" name="sms" value="1"> SMS</label>
                                    <label class="checkbox-inline"><input type="checkbox" name="mail" value="1"> Email</label>
                                    <label class="checkbox-inline"><input type="checkbox" name="mobile_app" value="1"> App</label>
                                    <label class="checkbox-inline"><input type="checkbox" name="whatsapp" value="1"> WhatsApp</label>
                                </div>

                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right btn-block-xs"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
            <div class="col-md-<?php echo ($can_show_form) ? '8' : '12'; ?>">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('ptm_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo base_url(); ?>admin/ptmreports" class="btn btn-sm btn-primary"><i class="fa fa-line-chart"></i> <?php echo $this->lang->line('reports'); ?></a>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages overflow-visible">
                            <table class="table table-striped table-bordered table-hover example mobile-card-table">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('title'); ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('time'); ?></th>
                                        <th><?php echo $this->lang->line('venue'); ?></th>
                                        <th><?php echo $this->lang->line('target') ? $this->lang->line('target') : 'Target'; ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($ptm_list)) {
                                        foreach ($ptm_list as $ptm_item) { ?>
                                        <tr>
                                            <td data-label="<?php echo $this->lang->line('title'); ?>"><?php echo $ptm_item['title']; ?></td>
                                            <td data-label="<?php echo $this->lang->line('date'); ?>"><?php echo $this->customlib->dateformat($ptm_item['ptm_date']); ?></td>
                                            <td data-label="<?php echo $this->lang->line('time'); ?>"><?php echo $ptm_item['time_from'] . ' - ' . $ptm_item['time_to']; ?></td>
                                            <td data-label="<?php echo $this->lang->line('venue'); ?>"><?php echo $ptm_item['venue']; ?></td>
                                            <td data-label="<?php echo $this->lang->line('target') ? $this->lang->line('target') : 'Target'; ?>"><?php 
                                                if($ptm_item['target_type'] == 'whole_school') echo $this->lang->line('whole_school');
                                                else {
                                                    $t_arr = [];
                                                    foreach($ptm_item['targets'] as $t) {
                                                        $t_arr[] = $t['class'] . " (" . $t['section'] . ")";
                                                    }
                                                    echo implode(", ", $t_arr);
                                                }
                                            ?></td>
                                            <td data-label="Action" class="pull-right">
                                                <?php if ($this->rbac->hasPrivilege('ptm_parent_teacher_meeting', 'can_view')) { ?>
                                                    <a href="<?php echo base_url(); ?>admin/ptm/attendance/<?php echo $ptm_item['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('mark_attendance'); ?>"><i class="fa fa-calendar-check-o"></i></a>
                                                <?php } ?>
                                                <?php if ($this->rbac->hasPrivilege('ptm_parent_teacher_meeting', 'can_edit') && ($is_superadmin || $ptm_item['created_by'] == $logged_in_staff_id)) { ?>
                                                    <a href="<?php echo base_url(); ?>admin/ptm/edit/<?php echo $ptm_item['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>"><i class="fa fa-pencil"></i></a>
                                                <?php } ?>
                                                <?php if ($this->rbac->hasPrivilege('ptm_parent_teacher_meeting', 'can_delete') && ($is_superadmin || $ptm_item['created_by'] == $logged_in_staff_id)) { ?>
                                                    <a href="<?php echo base_url(); ?>admin/ptm/delete/<?php echo $ptm_item['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm'); ?>');"><i class="fa fa-remove"></i></a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php } } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        // Initialize timepicker properly if not already done
        $('.timepicker').datetimepicker({
            format: 'LT'
        });

        // Make timepicker clickable via icon
        $('.input-group-addon').css('cursor', 'pointer').click(function(){
            var tp = $(this).siblings('.timepicker');
            if (tp.length > 0 && tp.data("DateTimePicker")) {
                tp.data("DateTimePicker").show();
            }
        });

        // Form validation before submit
        $('#form1').submit(function(e) {
            var timeFrom = $('#time_from').val();
            var timeTo = $('#time_to').val();
            
            if(timeFrom && timeTo) {
                var d1 = new Date("01/01/2000 " + timeFrom);
                var d2 = new Date("01/01/2000 " + timeTo);
                
                if (d1 >= d2) {
                    e.preventDefault();
                    errorMsg("Start time must be less than end time.");
                    return false;
                }
            }
        });
    });

    function toggleTargets() {
        if ($('#target_type').val() == 'whole_school') {
            $('#class_section_div').hide();
        } else {
            $('#class_section_div').show();
        }
    }
</script>
