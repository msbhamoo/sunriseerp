<style type="text/css">
    .tablists {
        list-style: none;
        padding: 0;
        margin: 0;
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
                        <li><a href="<?php echo site_url('admin/leavetypes?tab=leavetype') ?>">Leave Type</a></li>
                        <li><a href="<?php echo site_url('admin/leavetypes?tab=bulk') ?>">Bulk Leave Allocation</a></li>
                        <li><a href="<?php echo site_url('admin/department/department') ?>"><?php echo $this->lang->line('department'); ?></a></li>
                        <li><a href="<?php echo site_url('admin/designation/designation') ?>"><?php echo $this->lang->line('designation'); ?></a></li>
                        <li><a href="<?php echo site_url('admin/qualification/qualification') ?>">Qualification</a></li>
                        <li><a href="<?php echo site_url('admin/workexperience/workexperience') ?>" class="active">Work Experience</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add Work Experience</h3>
                    </div>
                    <form id="form1" action="<?php echo site_url('admin/workexperience/workexperience') ?>" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php echo $this->session->flashdata('msg'); $this->session->unset_userdata('msg'); ?>
                            <?php } ?>
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Work Experience Title</label><small class="req"> *</small>
                                <input autofocus="" id="type" name="type" placeholder="" type="text" class="form-control" value="<?php echo set_value('type'); ?>" />
                                <span class="text-danger"><?php echo form_error('type'); ?></span>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">Work Experience List</h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages overflow-visible">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($workexperience_list as $e) { ?>
                                        <tr>
                                            <td class="mailbox-name"><?php echo $e['work_experience'] ?></td>
                                            <td class="mailbox-date pull-right">
                                                <a href="<?php echo base_url(); ?>admin/workexperience/workexperienceedit/<?php echo $e['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                <a href="<?php echo base_url(); ?>admin/workexperience/workexperiencedelete/<?php echo $e['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                    <i class="fa fa-remove"></i>
                                                </a>
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
    </section>
</div>
