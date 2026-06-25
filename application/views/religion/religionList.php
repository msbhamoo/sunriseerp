<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-user-plus"></i> <?php echo $this->lang->line('student_information'); ?> <small><?php echo $this->lang->line('class1'); ?></small></h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-2">
                <div class="box border0">
                                        <ul class="tablists">
                        <li><a href="<?php echo site_url('category') ?>"><?php echo $this->lang->line('student_categories') ? $this->lang->line('student_categories') : 'Student Categories'; ?></a></li>
                        <li><a href="<?php echo site_url('admin/schoolhouse') ?>"><?php echo $this->lang->line('student_house') ? $this->lang->line('student_house') : 'Student House'; ?></a></li>
                        <li><a href="<?php echo site_url('admin/disable_reason') ?>"><?php echo $this->lang->line('disable_reason') ? $this->lang->line('disable_reason') : 'Disable Reason'; ?></a></li>
                        <li><a href="<?php echo site_url('religion') ?>" class="active"><?php echo $this->lang->line('religion') ? $this->lang->line('religion') : 'Religion'; ?></a></li>
                        <li><a href="<?php echo site_url('cast') ?>"><?php echo $this->lang->line('cast') ? $this->lang->line('cast') : 'Cast'; ?></a></li>
                    </ul>
                </div>
            </div>
            <?php
            if ($this->rbac->hasPrivilege('student_religions', 'can_add')) {
                ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('create_religion'); ?></h3>
                        </div> 
                        <form id="form1" action="<?php echo site_url('religion/create') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <?php 
                                    if ($this->session->flashdata('msg')) { 
                                        echo $this->session->flashdata('msg');
                                        $this->session->unset_userdata('msg');
                                    } 
                                ?>    
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('religion'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="religion" name="religion" placeholder="" type="text" class="form-control"  value="<?php echo set_value('religion'); ?>" />
                                    <span class="text-danger"><?php echo form_error('religion'); ?></span>
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
            if ($this->rbac->hasPrivilege('student_religions', 'can_add')) {
                echo "6";
            } else {
                echo "10";
            }
            ?>">             
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('religion_list'); ?></h3>           
                    </div>
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('religion_list'); ?></div>
                        <div class="table-responsive mailbox-messages overflow-visible">

                            <?php 
                                if ($this->session->flashdata('msgdelete')) { 
                                    echo $this->session->flashdata('msgdelete');
                                    $this->session->unset_userdata('msgdelete');

                                } 
                            ?>

                            <table class="table table-striped table-bordered table-hover example"  data-export-title="<?php echo $this->lang->line('religion');?>">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('religion'); ?></th>
                                        <th><?php echo $this->lang->line('religion_id'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    foreach ($religionlist as $religion) {
                                        ?>
                                        <tr>
                                            <td class="mailbox-name"><?php echo $religion['religion'] ?></td>
                                            <td class="mailbox-name"><?php echo $religion['id'] ?></td>
                                            <td  class="mailbox-date pull-right">
                                                <?php
                                                if ($this->rbac->hasPrivilege('student_religions', 'can_edit')) {
                                                    ?>
                                                    <a href="<?php echo base_url(); ?>religion/edit/<?php echo $religion['id'] ?>" class="btn btn-primary btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                <?php } ?>
                                                <?php
                                                if ($this->rbac->hasPrivilege('student_religions', 'can_delete')) {
                                                    ?>
                                                    <a href="<?php echo base_url(); ?>religion/delete/<?php echo $religion['id'] ?>"class="btn btn-primary btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                <?php } ?>
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
