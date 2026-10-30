<style type="text/css">
    @media print
    {
        .no-print, .no-print *
        {
            display: none !important;
        }
    }
    .tablists { margin: 0; padding: 0; list-style: none; }
    .tablists li a { display: block; padding: 10px 15px; background: #fff; border: 1px solid #e7e7e7; margin-bottom: 5px; border-radius: 3px; color: #444; font-weight: 500; }
    .tablists li a.active, .tablists li a:hover { background: #0084B4; color: #fff; border-color: #0084B4; }
</style>

<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-sitemap"></i> <?php //echo $this->lang->line('human_resource'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-2">
                <div class="box border0">
                    <ul class="tablists">
                        <li><a href="<?php echo site_url('admin/leavetypes?tab=leavetype') ?>">Leave Type</a></li>
                        <li><a href="<?php echo site_url('admin/leavetypes?tab=bulk') ?>">Bulk Leave Allocation</a></li>
                        <li><a href="<?php echo site_url('admin/department/department') ?>" class="active"><?php echo $this->lang->line('department'); ?></a></li>
                        <li><a href="<?php echo site_url('admin/designation/designation') ?>"><?php echo $this->lang->line('designation'); ?></a></li>
                        <li><a href="<?php echo site_url('admin/qualification/qualification') ?>">Qualification</a></li>
                        <li><a href="<?php echo site_url('admin/workexperience/workexperience') ?>">Work Experience</a></li>

                    </ul>
                </div>
            </div>
            <?php if (($this->rbac->hasPrivilege('department', 'can_add')) || ($this->rbac->hasPrivilege('department', 'can_edit'))) {
    ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $title; ?></h3>
                        </div>
                        <form id="form1" action="<?php echo site_url('admin/department/department') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) {
        ?>
                                    <?php echo $this->session->flashdata('msg');
        $this->session->unset_userdata('msg'); ?>
                                <?php }?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="type"  name="type" placeholder="" type="text" class="form-control"  value="<?php
if (isset($result)) {
        echo $result["department_name"];
    }
    ?>" />
                                    <span class="text-danger"><?php echo form_error('type'); ?></span>
                                    <input autofocus="" id="type"  name="departmenttypeid" placeholder="" type="hidden" class="form-control"  value="<?php
if (isset($result)) {
        echo $result["id"];
    }
    ?>" />
                                </div>

                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php }?>
            <div class="col-md-<?php
if (($this->rbac->hasPrivilege('department', 'can_add')) || ($this->rbac->hasPrivilege('department', 'can_edit'))) {
    echo "6";
} else {
    echo "10";
}
?>  ">

                <div class="box box-primary" id="tachelist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('department_list'); ?>s</h3>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">
                        </div>
                        <div class="table-responsive mailbox-messages overflow-visible">
                            <div class="download_label"><?php echo $this->lang->line('department_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover example" data-export-title="<?php echo $this->lang->line('department_list');?>">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
$count = 1;
foreach ($departmenttype as $value) {
    $status = "";

    if ($value["is_active"] == "yes") {
        $status = "Active";
    } else {
        $status = "Inactive";
    }
    ?>
                                        <tr>
                                            <td class="mailbox-name"> <?php echo $value['department_name'] ?></td>
                                            <td class="mailbox-date pull-right no-print">
                                                <?php if ($this->rbac->hasPrivilege('department', 'can_edit')) {
        ?>
                                                    <a href="<?php echo base_url(); ?>admin/department/departmentedit/<?php echo $value['id'] ?>" class="btn btn-primary btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                <?php }if ($this->rbac->hasPrivilege('department', 'can_delete')) {
        ?>
                                                    <a href="<?php echo base_url(); ?>admin/department/departmentdelete/<?php echo $value['id'] ?>" class="btn btn-primary btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>')";>
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                <?php }?>
                                            </td>
                                        </tr>
                                        <?php
}
$count++;
?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="">
                        <div class="mailbox-controls">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>