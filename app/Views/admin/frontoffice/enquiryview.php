<?php
$total_enquiries = count($enquiry_list);
$active_count    = 0;
$won_count       = 0;
$lost_count      = 0;
foreach ($enquiry_list as $e) {
    if (isset($e['status'])) {
        if ($e['status'] == 'active') { $active_count++; }
        elseif ($e['status'] == 'won') { $won_count++; }
        elseif ($e['status'] == 'lost' || $e['status'] == 'dead') { $lost_count++; }
    }
}
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-ioxhost"></i> <?php echo $this->lang->line('front_office'); ?>
        </h1>
    </section>
    
    <section class="content">
        <!-- Modern KPI Stat Grid -->
        <div class="modern-stat-grid">
            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label"><?php echo $this->lang->line('admission_enquiry') ? $this->lang->line('admission_enquiry') : 'Total Enquiries'; ?></div>
                    <div class="stat-value"><?php echo $total_enquiries; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(99, 102, 241, 0.12); color: #6366f1;">
                    <i class="fa fa-user-plus"></i>
                </div>
            </div>
            
            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label"><?php echo $this->lang->line('active'); ?></div>
                    <div class="stat-value" style="color: #0284c7;"><?php echo $active_count; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(14, 165, 233, 0.12); color: #0284c7;">
                    <i class="fa fa-clock-o"></i>
                </div>
            </div>
            
            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Admitted / Won</div>
                    <div class="stat-value text-success" style="color: #059669;"><?php echo $won_count; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
                    <i class="fa fa-check-circle"></i>
                </div>
            </div>
            
            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Lost / Inactive</div>
                    <div class="stat-value text-danger" style="color: #dc2626;"><?php echo $lost_count; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(239, 68, 68, 0.12); color: #ef4444;">
                    <i class="fa fa-times-circle"></i>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-filter text-muted" style="margin-right: 6px;"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <div class="col-md-12">
                        <?php echo $this->session->flashdata('msg');
                        $this->session->unset_userdata('msg'); ?>
                    </div>
                    <form role="form" action="<?php echo site_url('admin/enquiry') ?>" method="post" class="">
                        <div class="box-body row">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="col-sm-6 col-md-2 col-lg-2">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('class'); ?></label>
                                    <select id="class" name="class" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                        <?php foreach ($class_list as $key => $value) { ?>
                                            <option <?php if ($value["id"] == $selected_class) { echo "selected"; } ?> value="<?php echo $value["id"] ?>"><?php echo $value["class"] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6 col-md-2 col-lg-2">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('source'); ?></label>
                                    <select id="source" name="source" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                        <?php foreach ($sourcelist as $key => $value) { ?>
                                            <option <?php if ($value["source"] == $source_select) { echo "selected"; } ?> value="<?php echo $value["source"] ?>"><?php echo $value["source"] ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('source'); ?></span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-md-2 col-lg-2">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('enquiry_from_date'); ?></label>
                                    <input type="text" autocomplete="off" name="from_date" class="form-control date" value="<?php echo set_value('from_date') ?>">
                                    <span class="text-danger"><?php echo form_error('from_date'); ?></span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-md-2 col-lg-2">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('enquiry_to_date'); ?></label>
                                    <input type="text" autocomplete="off" name="to_date" class="form-control date" value="<?php echo set_value('to_date') ?>">
                                    <span class="text-danger"><?php echo form_error('to_date'); ?></span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-md-2 col-lg-2">
                                <div class="form-group">
                                    <label><?php echo $this->lang->line('status'); ?></label>
                                    <select id="status" name="status" class="form-control" >
                                        <option value=""><?php echo $this->lang->line('select') ?></option>
                                        <option value="all" <?php if ($status == "all") { echo "selected"; } ?>><?php echo $this->lang->line('all') ?></option>
                                        <?php foreach ($enquiry_status as $enkey => $envalue) { ?>
                                            <option <?php if ($enkey == $status) { echo "selected"; } ?> value="<?php echo $enkey ?>"><?php echo $envalue ?></option>
                                        <?php } ?>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('status'); ?></span>
                                </div>
                            </div>
                            <div class="col-sm-3 col-md-2 col-lg-2">
                                <div class="form-group pl10">
                                    <label class="displayblock opacity d-sm-none">&nbsp;</label>
                                    <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm pull-right" style="margin-top: 24px;"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                        </div>
                    </form>
                    
                    <div class="ptt10">
                        <div class="bordertop">
                            <div class="box-header with-border">
                                <h3 class="box-title titlefix"><i class="fa fa-list text-muted" style="margin-right: 6px;"></i> <?php echo $this->lang->line('admission_enquiry'); ?></h3>
                                <div class="box-tools pull-right">
                                    <?php if ($this->rbac->hasPrivilege('admission_enquiry', 'can_add')) { ?>
                                        <button type="button" class="btn btn-sm btn-primary openmodal"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add'); ?></button>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="download_label"><?php echo $this->lang->line('admission_enquiry_list'); ?></div>
                                <div class="mailbox-messages">
                                    <div class="table-responsive overflow-visible-lg">
                                        <table class="table table-hover table-striped table-bordered example" id="example" data-export-title="<?php echo $this->lang->line('admission_enquiry_list');?>">
                                            <thead>
                                                <tr>
                                                    <th><?php echo $this->lang->line('name'); ?></th>
                                                    <th>Father's Name</th>
                                                    <th><?php echo $this->lang->line('phone'); ?></th>
                                                    <th><?php echo $this->lang->line('source'); ?></th>
                                                    <th><?php echo $this->lang->line('enquiry_date'); ?></th>
                                                    <th><?php echo $this->lang->line('last_follow_up_date'); ?></th>
                                                    <th><?php echo $this->lang->line('next_follow_up_date'); ?></th>
                                                    <th><?php echo $this->lang->line('status'); ?></th>
                                                    <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (!empty($enquiry_list)) {
                                                    foreach ($enquiry_list as $key => $value) {
                                                        $current_date = date("Y-m-d");
                                                        $next_date    = $value["next_date"];
                                                        if (empty($next_date)) {
                                                            $next_date = $value["follow_up_date"];
                                                        }

                                                        if ($next_date < $current_date && !empty($next_date)) {
                                                            $class = "class='danger'";
                                                        } else {
                                                            $class = "";
                                                        }
                                                        ?>
                                                        <tr <?php echo $class ?>>
                                                            <td class="mailbox-name"><strong style="color: #0f172a;"><?php echo html_escape($value['name']); ?></strong></td>
                                                            <td class="mailbox-name"><?php echo html_escape($value['father_name']); ?></td>
                                                            <td class="mailbox-name"><i class="fa fa-phone text-muted" style="margin-right: 3px;"></i> <?php echo html_escape($value['contact']); ?></td>
                                                            <td class="mailbox-name"><span class="badge" style="background: #f8fafc; color: #475569; border: 1px solid #cbd5e1;"><?php echo html_escape($value['source']); ?></span></td>
                                                            <td class="mailbox-name white-space-nowrap">
                                                                <?php if (!empty($value["date"])) {
                                                                    echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['date']));
                                                                } ?>
                                                            </td>
                                                            <td class="mailbox-name white-space-nowrap">
                                                                <?php if (!empty($value["followupdate"])) {
                                                                    echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['followupdate']));
                                                                } ?>
                                                            </td>
                                                            <td class="mailbox-name white-space-nowrap">
                                                                <?php if (!empty($next_date) && $next_date != '0000-00-00') {
                                                                    echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($next_date));
                                                                } ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                $st = $value["status"];
                                                                if ($st == 'won') {
                                                                    echo "<span class='label label-success'>Won</span>";
                                                                } elseif ($st == 'active') {
                                                                    echo "<span class='label label-info'>Active</span>";
                                                                } elseif ($st == 'lost' || $st == 'dead') {
                                                                    echo "<span class='label label-danger'>" . ucfirst($st) . "</span>";
                                                                } else {
                                                                    echo "<span class='label label-warning'>" . (isset($enquiry_status[$st]) ? $enquiry_status[$st] : ucfirst($st)) . "</span>";
                                                                }
                                                                ?>
                                                            </td>
                                                            <td class="mailbox-date text-right white-space-nowrap">
                                                                <?php if ($value['status'] == 'won') { ?>
                                                                    <?php if (!empty($value['student_id'])) { ?>
                                                                        <a href="<?php echo site_url('student/view/' . $value['student_id']); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="View Profile">
                                                                            <i class="fa fa-user text-primary"></i>
                                                                        </a>
                                                                    <?php } ?>
                                                                <?php } else { ?>
                                                                    <?php if ($this->rbac->hasPrivilege('follow_up_admission_enquiry', 'can_view')) { ?>
                                                                        <a class="btn btn-default btn-xs" onclick="follow_up('<?php echo $value['id']; ?>', '<?php echo $value['status']; ?>', '<?php echo $value['created_by']; ?>');" data-target="#follow_up" data-toggle="modal" title="<?php echo $this->lang->line('follow_up_admission_enquiry'); ?>">
                                                                            <i class="fa fa-phone text-info"></i>
                                                                        </a>
                                                                    <?php } ?>
                                                                    <?php if ($this->rbac->hasPrivilege('admission_enquiry', 'can_edit')) { ?>
                                                                        <a onclick="getRecord('<?php echo $value['id']; ?>', '<?php echo $value['status']; ?>')" class="btn btn-default btn-xs" data-target="#myModaledit" data-toggle="modal" title="<?php echo $this->lang->line('edit'); ?>"><i class="fa fa-pencil text-primary"></i></a>
                                                                        <a href="<?php echo site_url('student/create?enquiry_id=' . $value['id']); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="Convert to Admission">
                                                                            <i class="fa fa-user-plus text-success"></i>
                                                                        </a>
                                                                    <?php } ?>
                                                                    <?php if ($this->rbac->hasPrivilege('admission_enquiry', 'can_delete')) { ?>
                                                                        <a href="#" class="btn btn-default btn-xs" data-toggle="tooltip" title="" onclick="delete_enquiry('<?php echo $value["id"] ?>')" data-original-title="<?php echo $this->lang->line('delete'); ?>">
                                                                            <i class="fa fa-trash text-danger"></i>
                                                                        </a>
                                                                    <?php } ?>
                                                                <?php } ?>
                                                            </td>
                                                        </tr>
                                                    <?php }
                                                } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Slide-in Right Drawer for Adding Admission Enquiry -->
    <div id="enquiry-drawer-overlay" class="modern-drawer-overlay"></div>
    <div id="enquiry-drawer-panel" class="modern-drawer-panel">
        <form id="formadd" method="post">
            <div class="modern-drawer-header">
                <h4 class="modern-drawer-title"><i class="fa fa-user-plus" style="color: var(--primary-theme-color, #4f46e5);"></i> <?php echo $this->lang->line('admission_enquiry'); ?></h4>
                <button type="button" class="modern-drawer-close" id="btn-close-enquiry-drawer">&times;</button>
            </div>
            <div class="modern-drawer-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                            <input type="text" id="name_add" autocomplete="off" class="form-control" value="<?php echo set_value('name'); ?>" name="name">
                            <span id="name_add_error" class="text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('phone'); ?></label><small class="req"> *</small>
                            <input id="number" autocomplete="off" name="contact" placeholder="" type="number" class="form-control" value="<?php echo set_value('contact'); ?>" />
                            <span id="phone_error_message" class="text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label>Father Name</label>
                            <input type="text" autocomplete="off" class="form-control" value="<?php echo set_value('father_name'); ?>" name="father_name">
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('email'); ?></label>
                            <input type="text" value="<?php echo set_value('email'); ?>" name="email" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('address'); ?></label>
                            <textarea name="address" class="form-control"><?php echo set_value('address'); ?></textarea>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('description'); ?></label>
                            <textarea name="description" class="form-control"><?php echo set_value('description'); ?></textarea>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('note'); ?></label>
                            <textarea name="note" class="form-control"><?php echo set_value('note'); ?></textarea>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('date'); ?></label><small class="req"> *</small>
                            <input type="text" id="date" name="date" class="form-control date" readonly value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat())); ?>">
                            <span id="date_error" class="text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('next_follow_up_date'); ?></label><small class="req"> *</small>
                            <input type="text" id="follow_up_date" name="follow_up_date" class="form-control date" readonly value="<?php echo set_value('follow_up_date', date($this->customlib->getSchoolDateFormat())); ?>">
                            <span id="follow_up_date_error" class="text-danger"></span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('assigned'); ?></label>
                            <select name="assigned" class="form-control">
                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                <?php foreach ($stff_list as $key => $stff_list_value) { ?>
                                    <option value="<?php echo $stff_list_value['id']; ?>"><?php echo $stff_list_value['name'] . ' ' . $stff_list_value['surname'] . ' (' . $stff_list_value['employee_id'] . ')'; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('reference'); ?></label>
                            <select name="reference" class="form-control">
                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                <?php foreach ($Reference as $key => $value) { ?>
                                    <option value="<?php echo $value['reference']; ?>" <?php if (set_value('reference') == $value['reference']) { ?>selected=""<?php } ?>><?php echo $value['reference']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('source'); ?></label><small class="req"> *</small>
                            <select name="source" class="form-control">
                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                <?php foreach ($sourcelist as $key => $value) { ?>
                                    <option value="<?php echo $value['source']; ?>"><?php echo $value['source']; ?></option>
                                <?php } ?>
                            </select>
                            <span id="source_error" class="text-danger"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('class'); ?></label>
                            <select name="class" class="form-control">
                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                <?php foreach ($class_list as $key => $value) { ?>
                                    <option value="<?php echo $value['id'] ?>" <?php if (set_value('class') == $value['id']) { ?>selected=""<?php } ?>><?php echo $value['class'] ?></option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label><?php echo $this->lang->line('number_of_child'); ?></label>
                            <input type="number" class="form-control" min="1" value="<?php echo set_value('no_of_child', 1); ?>" name="no_of_child">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modern-drawer-footer">
                <button type="button" class="btn btn-default" id="btn-cancel-enquiry-drawer"><?php echo $this->lang->line('cancel'); ?></button>
                <button type="submit" class="btn btn-primary" id="submitadd" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('saving') ?>"><i class="fa fa-check"></i> <?php echo $this->lang->line('save'); ?></button>
            </div>
        </form>
    </div>

                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Mother Name</label>
                                            <input type="text" autocomplete="off" class="form-control" value="<?php echo set_value('mother_name'); ?>" name="mother_name">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label>Date Of Birth</label>
                                            <input type="text" autocomplete="off" class="form-control date" value="<?php echo set_value('dob'); ?>" name="dob" readonly="">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="email"><?php echo $this->lang->line('address'); ?></label>
                                            <textarea name="address" class="form-control" ><?php echo set_value('address'); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="email"><?php echo $this->lang->line('description'); ?></label>
                                            <textarea name="description" class="form-control" ><?php echo set_value('description'); ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="pwd"><?php echo $this->lang->line('note'); ?></label>
                                            <textarea name="note" class="form-control" ><?php echo set_value('note'); ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="pwd"><?php echo $this->lang->line('date'); ?><small class="req"> *</small></label>
                                            <input type="text" id="date" name="date" class="form-control date" value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat())); ?>" readonly="">
                                            <span id="date_add_error" class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label for="pwd"><?php echo $this->lang->line('next_follow_up_date'); ?><small class="req"> *</small></label>
                                            <input type="text" id="date_of_call" name="follow_up_date"class="form-control date" value="<?php echo set_value('follow_up_date', date($this->customlib->getSchoolDateFormat())); ?>" readonly="">
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('assigned'); ?></label>
                                            <select name="assigned" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                                <?php foreach ($stff_list as $key => $stff_list_value) {?>
                                                    <option value="<?php echo $stff_list_value['id']; ?>" ><?php echo $this->customlib->getStaffFullName($stff_list_value['name'], $stff_list_value['surname'],  $stff_list_value['employee_id']); ?></option>
                                                <?php }
?>
                                            </select>
                                        </div><!--./form-group-->
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="pwd"><?php echo $this->lang->line('reference'); ?></label>
                                            <select name="reference" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                                <?php foreach ($Reference as $key => $value) {?>
                                                    <option value="<?php echo $value['reference']; ?>" <?php if (set_value('reference') == $value['reference']) {?>selected=""<?php }?>><?php echo $value['reference']; ?></option>
                                                <?php }
?>
                                            </select>
                                        </div><!--./form-group-->
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="pwd"><?php echo $this->lang->line('source'); ?></label> <small class="req"> *</small>
                                            <select name="source" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                                <?php foreach ($sourcelist as $key => $value) {?>
                                                    <option value="<?php echo $value['source']; ?>"><?php echo $value['source']; ?></option>
                                                <?php }
?>
                                            </select>
                                        </div><!--./form-group-->
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="pwd"><?php echo $this->lang->line('class'); ?></label>
                                            <select name="class" class="form-control"  >
                                                <option value=""><?php echo $this->lang->line('select') ?></option>
                                                <?php
foreach ($class_list as $key => $value) {
    ?>
                                                    <option value="<?php echo $value['id'] ?>" <?php if (set_value('class') == $value['id']) {?> selected="" <?php }?>><?php echo $value['class'] ?></option>
                                                    <?php
}
?>
                                            </select>
                                        </div><!--./form-group-->
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="form-group">
                                            <label for="pwd"><?php echo $this->lang->line('number_of_child'); ?></label>
                                            <input type="number" class="form-control" min="1" value="<?php echo set_value('no_of_child'); ?>" name="no_of_child">
                                        </div><!--./form-group-->
                                    </div>
                                </div><!--./row-->
                        </div><!--./col-md-12-->
                    </div><!--./row-->
                    <div class="row">
                        <div class="box-footer col-md-12">
                            <button type="submit" class="btn btn-info pull-right" id="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('save') ?></button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="myModaledit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content modal-media-content">
                <div class="modal-header modal-media-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="box-title"><?php echo $this->lang->line('edit_admission_enquiry'); ?></h4>
                </div>
                <div class="modal-body pt0 pb0" id="getdetails">
                    <div id="alert_message">
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="follow_up" tabindex="-1" role="dialog" aria-labelledby="follow_up">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content modal-media-content">
                <div class="modal-header modal-media-header">
                    <button type="button" class="close" onclick="update()" data-dismiss="modal">&times;</button>
                    <h4 class="box-title"><?php echo $this->lang->line('follow_up_admission_enquiry'); ?></h4>
                </div>
                <div class="modal-body pt0 pb0" id="getdetails_follow_up">
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function (e) {
        $('#follow_up,#myModaledit').modal({
            backdrop: 'static',
            keyboard: false,
            show: false
        });

        // Drawer open / close handlers
        $(".openmodal").on('click', function() {
            $('#formadd').trigger("reset");
            openEnquiryDrawer();
        });
        $("#btn-close-enquiry-drawer, #btn-cancel-enquiry-drawer, #enquiry-drawer-overlay").on('click', function() {
            closeEnquiryDrawer();
        });
    });

    function openEnquiryDrawer() {
        $('#enquiry-drawer-overlay').addClass('is-active');
        $('#enquiry-drawer-panel').addClass('is-open');
        $('body').addClass('drawer-open').css('overflow', 'hidden');
    }

    function closeEnquiryDrawer() {
        $('#enquiry-drawer-panel').removeClass('is-open');
        $('#enquiry-drawer-overlay').removeClass('is-active');
        $('body').removeClass('drawer-open').css('overflow', '');
    }
</script>
<script>
    $(document).ready(function () {
      moment.lang('en', {
          week: { dow: start_week }
        });
     $('#enquiry_date').daterangepicker(
        {
            locale: {
                    format: calendar_date_time_format
                }
        });
    });

    function getRecord(id, status) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/enquiry/details/' + id + '/' + status,
            success: function (result) {
                $('#getdetails').html(result);
            }
        });
    }

    function postRecord(id) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/enquiry/editpost/' + id,
            type: 'POST',
            data: $("#myForm1").serialize(),
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
            },
            error: function () {
                alert("<?php echo $this->lang->line('fail'); ?>");
            }
        });
    }

    $("#formadd").on('submit', (function (e) {
        e.preventDefault();
        var $this = $(this).find("button[type=submit]:focus");
        $.ajax({
            url: "<?php echo site_url("admin/enquiry/add/") ?>",
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
                if (res.status == "fail") {
                    var message = "";
                    $.each(res.error, function (index, value) {
                        message += value;
                    });
                    errorMsg(message);
                } else {
                    successMsg(res.message);
                    window.location.reload(true);
                }
            },
            error: function (xhr) { // if error occured
                alert("<?php echo $this->lang->line('error_occurred_please_try_again'); ?>");
                $this.button('reset');
            },
            complete: function () {
                $this.button('reset');
            }
        });
    }));

    function delete_enquiry(id) {
        if (confirm('<?php echo $this->lang->line('delete_confirm') ?>')) {
            $.ajax({
                url: '<?php echo base_url(); ?>admin/enquiry/delete/' + id,
                type: 'POST',
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
                }
            })
        }
    }

    function follow_up(id, status, created_by) {
        $.ajax({
            url: '<?php echo base_url(); ?>admin/enquiry/follow_up/' + id + '/' + status+ '/' + created_by,
            success: function (data) {
                $('#getdetails_follow_up').html(data);
                $.ajax({
                    url: '<?php echo base_url(); ?>admin/enquiry/follow_up_list/' + id,
                    success: function (data) {
                        $('#timeline').html(data);
                    },
                    error: function () {
                        alert("<?php echo $this->lang->line('fail'); ?>");
                    }
                });
            },
            error: function () {
                alert("<?php echo $this->lang->line('fail'); ?>");
            }
        });
    }

    function update() {
        window.location.reload(true);
    }
</script>
<script type="text/javascript">

    $('#number').blur(function(){
        $('#phone_error_message').html('');
        $.ajax({
                url: '<?php echo base_url(); ?>admin/enquiry/check_number',
                type: 'POST',
                data: {phone_number:$('#number').val()},
                dataType: 'json',
                success: function (data) {
                    if (data.status == "success") {
                       $('#phone_error_message').html('('+data.message+')');
                    }
                }
        })
    })
</script>