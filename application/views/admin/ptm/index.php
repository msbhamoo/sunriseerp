<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1><i class="fa fa-calendar-check-o"></i> <?php echo $this->lang->line('parent_teacher_meeting'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('ptm_parent_teacher_meeting', 'can_add')) { ?>
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
                                    <input id="ptm_date" name="ptm_date" type="text" class="form-control date" value="<?php echo set_value('ptm_date', isset($ptm) ? date('d-m-Y', strtotime($ptm['ptm_date'])) : ''); ?>" readonly="readonly"/>
                                    <span class="text-danger"><?php echo form_error('ptm_date'); ?></span>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="time_from"><?php echo $this->lang->line('time_from'); ?></label><small class="req"> *</small>
                                            <div class="input-group">
                                                <input id="time_from" name="time_from" type="text" class="form-control timepicker" value="<?php echo set_value('time_from', isset($ptm) ? $ptm['time_from'] : ''); ?>" />
                                                <div class="input-group-addon"><i class="fa fa-clock-o"></i></div>
                                            </div>
                                            <span class="text-danger"><?php echo form_error('time_from'); ?></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
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
                                    $default_venue = $school_setting[0]['name'];
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
                                    <div class="scroll-area">
                                        <?php foreach ($classlist as $class_key => $class_value) { ?>
                                            <div class="checkbox">
                                                <label>
                                                    <?php 
                                                    $checked = '';
                                                    if (isset($ptm) && isset($ptm['targets'])) {
                                                        foreach($ptm['targets'] as $t) {
                                                            if ($t['class_id'] == $class_value['id']) $checked = 'checked';
                                                        }
                                                    }
                                                    ?>
                                                    <input type="checkbox" name="class_section_id[]" value="<?php echo $class_value['id'].'-0'; ?>" class="chk_class" data-class="<?php echo $class_value['id']; ?>" <?php echo $checked; ?>>
                                                    <b><?php echo $class_value['class']; ?></b>
                                                </label>
                                            </div>
                                            <!-- Dynamically load sections via ajax or print all if available in scope. 
                                            For brevity, assuming typical class-section checkboxes. -->
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
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
            <div class="col-md-<?php echo ($this->rbac->hasPrivilege('ptm_parent_teacher_meeting', 'can_add')) ? '8' : '12'; ?>">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('ptm_list'); ?></h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo base_url(); ?>admin/ptmreports" class="btn btn-sm btn-primary"><i class="fa fa-line-chart"></i> <?php echo $this->lang->line('reports'); ?></a>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages overflow-visible">
                            <table class="table table-striped table-bordered table-hover example">
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
                                        foreach ($ptm_list as $ptm) { ?>
                                        <tr>
                                            <td><?php echo $ptm['title']; ?></td>
                                            <td><?php echo date('d-m-Y', strtotime($ptm['ptm_date'])); ?></td>
                                            <td><?php echo $ptm['time_from'] . ' - ' . $ptm['time_to']; ?></td>
                                            <td><?php echo $ptm['venue']; ?></td>
                                            <td><?php 
                                                if($ptm['target_type'] == 'whole_school') echo $this->lang->line('whole_school');
                                                else {
                                                    $t_arr = [];
                                                    foreach($ptm['targets'] as $t) {
                                                        $t_arr[] = $t['class'] . " (" . $t['section'] . ")";
                                                    }
                                                    echo implode(", ", $t_arr);
                                                }
                                            ?></td>
                                            <td class="pull-right">
                                                <?php if ($this->rbac->hasPrivilege('ptm_parent_teacher_meeting', 'can_add')) { ?>
                                                    <a href="<?php echo base_url(); ?>admin/ptm/attendance/<?php echo $ptm['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('mark_attendance'); ?>"><i class="fa fa-calendar-check-o"></i></a>
                                                <?php } ?>
                                                <?php if ($this->rbac->hasPrivilege('ptm_parent_teacher_meeting', 'can_edit')) { ?>
                                                    <a href="<?php echo base_url(); ?>admin/ptm/edit/<?php echo $ptm['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>"><i class="fa fa-pencil"></i></a>
                                                <?php } ?>
                                                <?php if ($this->rbac->hasPrivilege('ptm_parent_teacher_meeting', 'can_delete')) { ?>
                                                    <a href="<?php echo base_url(); ?>admin/ptm/delete/<?php echo $ptm['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm'); ?>');"><i class="fa fa-remove"></i></a>
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
        // Restrict datepicker to today and future dates
        setTimeout(function(){
            $('#ptm_date').datepicker('setStartDate', new Date());
        }, 500);

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
