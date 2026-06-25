<script src="<?php echo base_url(); ?>backend/js/sstoast.js"></script>
<div class="content-wrapper" style="min-height: 946px;">
     
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-2">
                <div class="box border0">
                                        <ul class="tablists">
                        <li><a href="<?php echo site_url('category') ?>"><?php echo $this->lang->line('student_categories') ? $this->lang->line('student_categories') : 'Student Categories'; ?></a></li>
                        <li><a href="<?php echo site_url('admin/schoolhouse') ?>"><?php echo $this->lang->line('student_house') ? $this->lang->line('student_house') : 'Student House'; ?></a></li>
                        <li><a href="<?php echo site_url('admin/disable_reason') ?>" class="active"><?php echo $this->lang->line('disable_reason') ? $this->lang->line('disable_reason') : 'Disable Reason'; ?></a></li>
                        <li><a href="<?php echo site_url('religion') ?>"><?php echo $this->lang->line('religion') ? $this->lang->line('religion') : 'Religion'; ?></a></li>
                        <li><a href="<?php echo site_url('cast') ?>"><?php echo $this->lang->line('cast') ? $this->lang->line('cast') : 'Cast'; ?></a></li>
                    </ul>
                </div>
            </div>
            <?php
            if (($this->rbac->hasPrivilege('disable_reason', 'can_edit'))) {
                ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-users"></i> <?php echo $this->lang->line('edit_disable_reason'); ?></h3>
                        </div> 
                        <form id="form1" action="<?php echo base_url(); ?>admin/disable_reason/edit/<?php echo $id; ?>" id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php
                                if ($this->session->flashdata('msg')) {
                                    $msg = $this->session->flashdata('msg');
                                    ?>
                                    <?php 
                                        echo $this->session->flashdata('msg');
                                        $this->session->unset_userdata('msg'); 
                                    ?>
                                <?php } ?>    
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="row">
                                    <input type="hidden" id="reason_id"  name="reason_id">
                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <label for="pwd"><?php echo $this->lang->line('disable_reason'); ?></label><small class="req"> *</small>
                                            <input type="text" value="<?php echo set_value('name', $name); ?>" name="name" id="name" class="form-control "><span class="text-danger"><p><?php echo form_error('name') ?></p></span>
                                        </div>
                                    </div>
                                </div> 
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>  
                </div> 
            <?php } ?>
            <div class="col-md-<?php
            if (($this->rbac->hasPrivilege('disable_reason', 'can_edit'))) {
                echo "6";
            } else {
                echo "10";
            }
            ?>">             
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title"><i class="fa fa-users"></i> <?php echo $this->lang->line('disable_reason_list'); ?></h3>                
                    </div>
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('disable_reason_list'); ?></div>
                        <div class="mailbox-messages">
                            <table class="table table-hover table-striped table-bordered example" data-export-title="<?php echo $this->lang->line('disable_reason_list');?>">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('disable_reason') ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($results as $value) { ?>
                                        <tr>
                                            <td><?php echo $value['reason']; ?></td>
                                            <td class="text-right">
                                                <?php if ($this->rbac->hasPrivilege('disable_reason', 'can_edit')) { ?>
                                                <a class="btn btn-primary btn-xs" href="<?php echo base_url(); ?>admin/disable_reason/edit/<?php echo $value['id']; ?>" data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>"><i class="fa fa-pencil"></i></a>
                                                <?php } if ($this->rbac->hasPrivilege('disable_reason', 'can_delete')) { ?>
                                                <a onclick="return confirm('<?php echo $this->lang->line('delete_confirm'); ?>');" class="btn btn-primary btn-xs" href="<?php echo base_url() ?>admin/disable_reason/delete/<?php echo $value['id'] ?>" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>"><i class="fa fa-remove"></i></a></td>
                                                <?php } ?>
                                        </tr>
                                        <?php
                                    }
                                    ?>
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
        $("#btnreset").click(function () {
            $("#form1")[0].reset();
        });
    });
</script>