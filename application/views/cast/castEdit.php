<div class="content-wrapper">  
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
                        <li><a href="<?php echo site_url('religion') ?>"><?php echo $this->lang->line('religion') ? $this->lang->line('religion') : 'Religion'; ?></a></li>
                        <li><a href="<?php echo site_url('cast') ?>" class="active"><?php echo $this->lang->line('cast') ? $this->lang->line('cast') : 'Cast'; ?></a></li>
                    </ul>
                </div>
            </div>
            <?php
            if ($this->rbac->hasPrivilege('student_casts', 'can_add') || $this->rbac->hasPrivilege('student_casts', 'can_edit')) {
                ?>
                <div class="col-md-4">              
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $this->lang->line('edit_cast'); ?></h3>
                        </div>  
                        <form action="<?php echo site_url("cast/edit/" . $id) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                            <div class="box-body">   
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group">
                                    <label for="exampleInputEmail1"><?php echo $this->lang->line('cast'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="cast" name="cast" placeholder="" type="text" class="form-control"  value="<?php echo set_value('cast', $cast['cast']); ?>" />
                                    <span class="text-danger"><?php echo form_error('cast'); ?></span>
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
            if ($this->rbac->hasPrivilege('student_casts', 'can_add') || $this->rbac->hasPrivilege('student_casts', 'can_edit')) {
                echo "6";
            } else {
                echo "10";
            }
            ?>">               
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('cast_list'); ?></h3>                     
                    </div>
                    <div class="box-body">
                        <div class="download_label"><?php echo $this->lang->line('cast_list'); ?></div>
                        <div class="mailbox-messages table-responsive overflow-visible">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th align="left"><?php echo $this->lang->line('cast'); ?></th>
                                        <th><?php echo $this->lang->line('cast_id'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    foreach ($castlist as $cast) {
                                        ?>
                                        <tr>                                         
                                            <td class="mailbox-name"><?php echo $cast['cast'] ?></td>
                                            <td class="mailbox-name"><?php echo $cast['id'] ?></td>
                                            <td align="right" class="mailbox-date">
                                                <?php
                                                if ($this->rbac->hasPrivilege('student_casts', 'can_edit')) {
                                                    ?>
                                                    <a href="<?php echo base_url(); ?>cast/edit/<?php echo $cast['id'] ?>" class="btn btn-primary btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                <?php } ?>
                                                <?php
                                                if ($this->rbac->hasPrivilege('student_casts', 'can_delete')) {
                                                    ?>
                                                    <a href="<?php echo base_url(); ?>cast/delete/<?php echo $cast['id'] ?>"class="btn btn-primary btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                        $count++;
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
