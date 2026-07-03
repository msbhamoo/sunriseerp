<style>
    .bootstrap-timepicker-widget {
        z-index: 999999 !important;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-building-o"></i> <?php echo $this->lang->line('front_office'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary" id="route">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('gate_pass_list'); ?></h3>
                        <?php if ($this->rbac->hasPrivilege('front_office_gate_pass', 'can_add')) { ?>
                            <div class="box-tools pull-right">
                                <a data-toggle="modal" data-target="#myModal" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_gate_pass'); ?></a>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-messages table-responsive">
                            <div class="download_label"><?php echo $this->lang->line('gate_pass_list'); ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('gate_pass_no'); ?></th>
                                        <th><?php echo $this->lang->line('user_type'); ?></th>
                                        <th><?php echo $this->lang->line('user'); ?></th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th><?php echo $this->lang->line('out_time'); ?></th>
                                        <th>In Time (Exp.)</th>
                                        <th>Actual In-Time</th>
                                        <th><?php echo $this->lang->line('status'); ?></th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($gate_passes)) {
                                        foreach ($gate_passes as $gatepass) {
                                            ?>
                                            <tr>
                                                <td class="mailbox-name"><?php echo $gatepass['gate_pass_no']; ?></td>
                                                <td class="mailbox-name"><?php echo ucfirst($gatepass['user_type']); ?></td>
                                                <td class="mailbox-name"><?php echo $gatepass['user_details']; ?></td>
                                                <td class="mailbox-name">
                                                    <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($gatepass['date'])); ?>
                                                </td>
                                                <td class="mailbox-name"><?php echo $gatepass['out_time']; ?></td>
                                                <td class="mailbox-name"><?php echo $gatepass['in_time']; ?></td>
                                                <td class="mailbox-name"><?php echo $gatepass['actual_in_time'] ? $gatepass['actual_in_time'] : '-'; ?></td>
                                                <td class="mailbox-name">
                                                    <?php
                                                    if ($gatepass['status'] == 'Pending') {
                                                        echo "<span class='label label-warning'>Pending</span>";
                                                    } elseif ($gatepass['status'] == 'Approved') {
                                                        echo "<span class='label label-success'>Approved</span>";
                                                    } elseif ($gatepass['status'] == 'Rejected') {
                                                        echo "<span class='label label-danger'>Rejected</span>";
                                                    } elseif ($gatepass['status'] == 'Completed') {
                                                        echo "<span class='label label-info'>Completed</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td class="mailbox-date pull-right">
                                                    <?php if ($this->rbac->hasPrivilege('front_office_gate_pass', 'can_edit')) { ?>
                                                        <a onclick="updateStatus('<?php echo $gatepass['id']; ?>', '<?php echo $gatepass['status']; ?>', '<?php echo $gatepass['actual_in_time']; ?>')" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('update_status'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    <?php } ?>
                                                    
                                                    <?php if ($gatepass['status'] == 'Approved' || $gatepass['status'] == 'Completed') { ?>
                                                        <a onclick="printGatePass('<?php echo $gatepass['id']; ?>')" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('print'); ?>">
                                                            <i class="fa fa-print"></i>
                                                        </a>
                                                    <?php } ?>

                                                    <?php if ($this->rbac->hasPrivilege('front_office_gate_pass', 'can_delete')) { ?>
                                                        <a href="<?php echo base_url(); ?>admin/gatepass/delete/<?php echo $gatepass['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                            <i class="fa fa-remove"></i>
                                                        </a>
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
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

<!-- Add Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('add_gate_pass'); ?></h4>
            </div>
            <form id="formadd" action="<?php echo site_url('admin/gatepass/create') ?>" method="post" accept-charset="utf-8">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('user_type'); ?></label><small class="req"> *</small>
                                <select class="form-control" name="user_type" id="user_type" onchange="resetUserSelect()">
                                    <option value="student"><?php echo $this->lang->line('student'); ?></option>
                                    <option value="staff"><?php echo $this->lang->line('staff'); ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                                <select class="form-control select2" style="width:100%" name="user_id" id="user_id">
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('date'); ?></label><small class="req"> *</small>
                                <input type="text" name="date" class="form-control date" id="date" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Duration</label><small class="req"> *</small>
                                <select class="form-control" name="pass_type" id="pass_type" onchange="toggleOutTime()">
                                    <option value="Partial">Partial Time</option>
                                    <option value="Full Day">Full Day</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4" id="out_time_container">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('out_time'); ?></label><small class="req"> *</small>
                                <div class="input-group">
                                    <input type="text" name="out_time" class="form-control timepicker" id="out_time">
                                    <div class="input-group-addon" style="cursor: pointer;" onclick="$('#out_time').focus();">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4" id="in_time_container_add">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('in_time'); ?> (Expected)</label><small class="req"> *</small>
                                <div class="input-group">
                                    <input type="text" name="in_time" class="form-control timepicker" id="in_time_add">
                                    <div class="input-group-addon" style="cursor: pointer;" onclick="$('#in_time_add').focus();">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label><?php echo $this->lang->line('reason'); ?></label>
                                <textarea name="reason" class="form-control" id="reason"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info pull-right" id="submit" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><?php echo $this->lang->line('save') ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('update_status'); ?></h4>
            </div>
            <form id="statusform" action="<?php echo site_url('admin/gatepass/update_status') ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id" id="update_id">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('status'); ?></label>
                        <select name="status" id="update_status" class="form-control" onchange="checkCompletedStatus()">
                            <option value="Pending">Pending</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                            <option value="Completed">Completed</option>
                        </select>
                    </div>
                    <div class="form-group" id="in_time_div" style="display:none;">
                        <label>Actual In-Time</label>
                        <div class="input-group">
                            <input type="text" name="in_time" class="form-control timepicker" id="in_time">
                            <div class="input-group-addon" style="cursor: pointer;" onclick="$('#in_time').focus();">
                                <i class="fa fa-clock-o"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#user_id').select2({
            dropdownParent: $('#myModal'),
            ajax: {
                url: '<?php echo base_url(); ?>admin/gatepass/search_user',
                type: 'get',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        keyword: params.term,
                        user_type: $('#user_type').val()
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: false
            }
        });
        
        $('.timepicker').timepicker({
            showInputs: false,
            showMeridian: false
        });

        $('#formadd').on('submit', (function (e) {
            e.preventDefault();
            var $this = $(this).find("button[type=submit]:focus");
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                beforeSend: function () {
                    $this.button('loading');
                },
                success: function (res) {
                    if (res.status == "fail") {
                        $.each(res.error, function (index, value) {
                            var errorDiv = '#' + index + '_error';
                            if (value != '') {
                                $('#' + index).parent().append('<span class="text-danger">' + value + '</span>');
                            }
                        });
                    } else {
                        successMsg(res.message);
                        window.location.reload(true);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                },
                complete: function (data) {
                    $this.button('reset');
                }
            });
        }));
        
        $('#statusform').on('submit', (function (e) {
            e.preventDefault();
            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: $(this).serialize(),
                dataType: 'json',
                success: function (res) {
                    if (res.status == "success") {
                        successMsg(res.message);
                        window.location.reload(true);
                    }
                }
            });
        }));
    });

    function resetUserSelect() {
        $('#user_id').val(null).trigger('change');
    }

    function toggleOutTime() {
        if ($('#pass_type').val() == 'Full Day') {
            $('#in_time_container_add').hide();
            $('#in_time_add').val('');
        } else {
            $('#in_time_container_add').show();
        }
    }

    function updateStatus(id, current_status, actual_in_time) {
        $('#update_id').val(id);
        $('#update_status').val(current_status);
        if (actual_in_time) {
            $('#in_time').val(actual_in_time);
        } else {
            $('#in_time').val('');
        }
        checkCompletedStatus();
        $('#statusModal').modal('show');
    }

    function checkCompletedStatus() {
        if ($('#update_status').val() == 'Completed') {
            $('#in_time_div').show();
        } else {
            $('#in_time_div').hide();
        }
    }
    
    function printGatePass(id) {
        var url = '<?php echo base_url() ?>admin/gatepass/print_gatepass/' + id;
        $.ajax({
            url: url,
            type: "POST",
            success: function (data) {
                var popupWin = window.open('', '_blank', 'width=800,height=600');
                popupWin.document.open();
                popupWin.document.write('<html><head><title>Gate Pass</title></head><body onload="window.print()">' + data + '</body></html>');
                popupWin.document.close();
            }
        });
    }
</script>
