<style type="text/css">
    @media print
    {
        .no-print, .no-print *
        {
            display: none !important;
        }
    }
    .tablists {
        margin: 0;
        padding: 0;
        list-style: none;
    }
    .tablists li a {
        display: block;
        padding: 10px 15px;
        background: #fff;
        border: 1px solid #e7e7e7;
        margin-bottom: 5px;
        border-radius: 3px;
        color: #444;
        font-weight: 500;
    }
    .tablists li a.active, .tablists li a:hover {
        background: #0084B4;
        color: #fff;
        border-color: #0084B4;
    }
</style>

<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1><i class="fa fa-sitemap"></i> <?php echo $this->lang->line('human_resource'); ?></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-2">
                <div class="box border0">
                    <ul class="tablists">
                        <li><a href="<?php echo site_url('admin/leavetypes?tab=leavetype') ?>" class="<?php echo ($tab == 'leavetype') ? 'active' : ''; ?>">Leave Type</a></li>
                        <li><a href="<?php echo site_url('admin/leavetypes?tab=bulk') ?>" class="<?php echo ($tab == 'bulk') ? 'active' : ''; ?>">Bulk Leave Allocation</a></li>
                        <li><a href="<?php echo site_url('admin/department/department') ?>"><?php echo $this->lang->line('department'); ?></a></li>
                        <li><a href="<?php echo site_url('admin/designation/designation') ?>"><?php echo $this->lang->line('designation'); ?></a></li>
                        <li><a href="<?php echo site_url('admin/qualification/qualification') ?>">Qualification</a></li>
                        <li><a href="<?php echo site_url('admin/workexperience/workexperience') ?>">Work Experience</a></li>

                    </ul>
                </div>
            </div>

            <?php if ($tab == 'bulk') { ?>
                <div class="col-md-10">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-sliders"></i> Bulk Leave Allocation</h3>
                        </div>
                        <form id="bulk_leave_form" action="<?php echo site_url('admin/leavetypes/save_bulk_allocation') ?>" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg'); $this->session->unset_userdata('msg'); ?>
                                <?php } ?>
                                <?php echo $this->customlib->getCSRF(); ?>
                                
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Target Allocation Mode</label><small class="req"> *</small>
                                            <select id="target_type" name="target_type" class="form-control" onchange="toggleTargetType(this.value)">
                                                <option value="role">By Role</option>
                                                <option value="staff">Multi-Select Staff</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-8" id="role_select_box">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('role'); ?></label><small class="req"> *</small>
                                            <select name="role_id" class="form-control">
                                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                <?php foreach ($roles as $role) { ?>
                                                    <option value="<?php echo $role['id']; ?>"><?php echo $role['type']; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-8" id="staff_select_box" style="display: none;">
                                        <div class="form-group">
                                            <label><?php echo $this->lang->line('staff'); ?></label><small class="req"> *</small>
                                            <select name="staff_ids[]" class="form-control select2" multiple="multiple" style="width: 100%;">
                                                <?php foreach ($staff_list as $staff) { ?>
                                                    <option value="<?php echo $staff['id']; ?>">
                                                        <?php echo $staff['name'] . ' ' . $staff['surname'] . ' (' . $staff['employee_id'] . ')'; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <hr>
                                <h4><i class="fa fa-calendar-check-o"></i> Configure Leaves</h4>
                                <br>
                                <div class="row">
                                    <?php foreach ($leavetype as $leave) { ?>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label><?php echo $leave['type']; ?></label>
                                                <input type="number" step="0.01" min="0" name="alloted_leave[<?php echo $leave['id']; ?>]" class="form-control" placeholder="0.00" value="0.00">
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><i class="fa fa-save"></i> <?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } else { ?>
                <?php if (($this->rbac->hasPrivilege('leave_types', 'can_add'))) { ?>
                    <div class="col-md-4">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title"><?php echo $title; ?></h3>
                            </div>
                            <form id="form1" action="<?php echo site_url('admin/leavetypes/createleavetype') ?>" id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                                <div class="box-body">
                                    <?php if ($this->session->flashdata('msg')) { ?>
                                        <?php echo $this->session->flashdata('msg'); $this->session->unset_userdata('msg'); ?>
                                    <?php } ?>
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                                        <input autofocus="" id="type" name="type" placeholder="" type="text" class="form-control" value="<?php if (isset($result)) { echo $result["type"]; } ?>" />
                                        <span class="text-danger"><?php echo form_error('type'); ?></span>

                                        <input id="leavetypeid" name="leavetypeid" type="hidden" class="form-control" value="<?php if (isset($result)) { echo $result["id"]; } ?>" />
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php } ?>
                <div class="col-md-<?php if ($this->rbac->hasPrivilege('leave_types', 'can_add')) { echo "6"; } else { echo "10"; } ?>">
                    <div class="box box-primary" id="tachelist">
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><?php echo $this->lang->line('leave_type_list'); ?></h3>
                        </div>
                        <div class="box-body">
                            <div class="mailbox-controls"></div>
                            <div class="table-responsive mailbox-messages overflow-visible">
                                <div class="download_label"><?php echo $this->lang->line('leave_type_list'); ?></div>
                                <table class="table table-striped table-bordered table-hover example" data-export-title="<?php echo $this->lang->line('leave_type_list');?>">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('name'); ?></th>
                                            <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($leavetype as $value) { ?>
                                            <tr>
                                                <td class="mailbox-name"> <?php echo $value['type'] ?></td>
                                                <td class="mailbox-date pull-right no-print">
                                                    <?php if ($this->rbac->hasPrivilege('leave_types', 'can_edit')) { ?>
                                                        <a href="<?php echo base_url(); ?>admin/leavetypes/leaveedit/<?php echo $value['id'] ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    <?php } if ($this->rbac->hasPrivilege('leave_types', 'can_delete')) { ?>
                                                        <a href="<?php echo base_url(); ?>admin/leavetypes/leavedelete/<?php echo $value['id'] ?>" class="btn btn-primary btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>
</div>

<script type="text/javascript">
    function toggleTargetType(val) {
        if (val === 'role') {
            $('#role_select_box').show();
            $('#staff_select_box').hide();
        } else {
            $('#role_select_box').hide();
            $('#staff_select_box').show();
            if ($('.select2').length > 0) {
                $('.select2').select2();
            }
        }
    }
</script>
