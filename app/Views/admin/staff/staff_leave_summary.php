<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-sitemap"></i> <?php echo $this->lang->line('human_resource'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('leave'); ?> <?php echo $this->lang->line('summary'); ?></h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th><?php echo $this->lang->line('staff'); ?></th>
                                    <th><?php echo $this->lang->line('leave_type'); ?></th>
                                    <th><?php echo $this->lang->line('leave'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($resultdata)) { foreach ($resultdata as $row) { ?>
                                    <tr>
                                        <td><?php echo $row['name'] ?? ''; ?></td>
                                        <td><?php echo $row['type'] ?? ''; ?></td>
                                        <td><?php echo $row['leaves'] ?? ''; ?></td>
                                    </tr>
                                <?php } } else { ?>
                                    <tr><td colspan="3" class="text-center"><?php echo $this->lang->line('no_record_found'); ?></td></tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
