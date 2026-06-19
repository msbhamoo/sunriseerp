<div class="content-wrapper" style="min-height: 348px;">
    <section class="content-header">
        <h1>
            <i class="fa fa-phone"></i> <?php echo ($this->lang->line('student_call_log') ? $this->lang->line('student_call_log') : 'Student Call Log'); ?>
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <?php if ($this->rbac->hasPrivilege('call_purpose_setup', 'can_add')) { ?>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo ($this->lang->line('add_purpose') ? $this->lang->line('add_purpose') : 'Add Call Purpose'); ?></h3>
                        </div>
                        <form id="form1" action="<?php echo site_url('admin/callpurpose/add') ?>" method="post" accept-charset="utf-8">
                            <div class="box-body">
                                <input type="hidden" name="id" id="purpose_id" value="">
                                <div class="form-group">
                                    <label for="purpose"><?php echo ($this->lang->line('purpose') ? $this->lang->line('purpose') : 'Purpose'); ?></label> <small class="req"> *</small>
                                    <input class="form-control" id="purpose" name="purpose" value="" />
                                    <span class="text-danger" id="purpose_error"></span>
                                </div>
                            </div>
                            <div class="box-footer">
                                <button type="submit" class="btn btn-info pull-right" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('save'); ?>"><?php echo $this->lang->line('save'); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php } ?>
            <div class="col-md-<?php echo ($this->rbac->hasPrivilege('call_purpose_setup', 'can_add')) ? "8" : "12"; ?>">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo ($this->lang->line('purpose_list') ? $this->lang->line('purpose_list') : 'Purpose List'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="table-responsive mailbox-messages overflow-visible">
                            <table class="table table-hover table-striped table-bordered example">
                                <thead>
                                    <tr>
                                        <th><?php echo ($this->lang->line('purpose') ? $this->lang->line('purpose') : 'Purpose'); ?></th>
                                        <th class="text-right noExport"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($purpose_list)) {
                                        foreach ($purpose_list as $key => $value) { ?>
                                            <tr id="row_<?php echo $value['id']; ?>">
                                                <td class="mailbox-name"><?php echo $value['purpose']; ?></td>
                                                <td class="mailbox-date pull-right">
                                                    <?php if ($this->rbac->hasPrivilege('call_purpose_setup', 'can_edit')) { ?>
                                                        <a href="#" class="btn btn-primary btn-xs edit_record" data-id="<?php echo $value['id']; ?>" data-toggle="tooltip" title="<?php echo $this->lang->line('edit') ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    <?php } if ($this->rbac->hasPrivilege('call_purpose_setup', 'can_delete')) { ?>
                                                        <a href="#" class="btn btn-primary btn-xs delete_record" data-id="<?php echo $value['id']; ?>" data-toggle="tooltip" title="<?php echo $this->lang->line('delete') ?>">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
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
    </section>
</div>

<script>
    $(document).ready(function () {
        $('#form1').on('submit', function (e) {
            e.preventDefault();
            var $this = $(this).find("button[type=submit]:focus");
            var id = $('#purpose_id').val();
            var url = id ? '<?php echo site_url("admin/callpurpose/update") ?>' : '<?php echo site_url("admin/callpurpose/add") ?>';
            
            $.ajax({
                url: url,
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (res) {
                    if (res.status == "fail") {
                        $('#purpose_error').html(res.error.purpose);
                    } else {
                        successMsg(res.message);
                        window.location.reload();
                    }
                }
            });
        });

        $('.edit_record').on('click', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            $.ajax({
                url: '<?php echo site_url("admin/callpurpose/edit/") ?>' + id,
                type: "GET",
                dataType: 'json',
                success: function (res) {
                    $('#purpose_id').val(res.id);
                    $('#purpose').val(res.purpose);
                    $('.box-title').html('<?php echo ($this->lang->line("edit_purpose") ? $this->lang->line("edit_purpose") : "Edit Purpose"); ?>');
                }
            });
        });

        $('.delete_record').on('click', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            if (confirm('<?php echo $this->lang->line("delete_confirm") ?>')) {
                $.ajax({
                    url: '<?php echo site_url("admin/callpurpose/delete/") ?>' + id,
                    type: "GET",
                    dataType: 'json',
                    success: function (res) {
                        successMsg(res.message);
                        window.location.reload();
                    }
                });
            }
        });
    });
</script>
