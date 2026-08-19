<?php
$total_passes = count($gate_passes);
$pending_count = 0;
$approved_count = 0;
$completed_count = 0;
foreach ($gate_passes as $gp) {
    if (isset($gp['status'])) {
        if ($gp['status'] == 'Pending') { $pending_count++; }
        elseif ($gp['status'] == 'Approved') { $approved_count++; }
        elseif ($gp['status'] == 'Completed') { $completed_count++; }
    }
}
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-id-card-o"></i> <?php echo $this->lang->line('front_office'); ?></h1>
    </section>
    
    <section class="content">
        <!-- Modern KPI Stat Grid -->
        <div class="modern-stat-grid">
            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Total Passes</div>
                    <div class="stat-value"><?php echo $total_passes; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(99, 102, 241, 0.12); color: #6366f1;">
                    <i class="fa fa-id-badge"></i>
                </div>
            </div>
            
            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Pending Approval</div>
                    <div class="stat-value text-warning" style="color: #d97706;"><?php echo $pending_count; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b;">
                    <i class="fa fa-hourglass-half"></i>
                </div>
            </div>
            
            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Approved Passes</div>
                    <div class="stat-value text-success" style="color: #059669;"><?php echo $approved_count; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
                    <i class="fa fa-check-circle"></i>
                </div>
            </div>
            
            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Completed / Returned</div>
                    <div class="stat-value" style="color: #0284c7;"><?php echo $completed_count; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(14, 165, 233, 0.12); color: #0284c7;">
                    <i class="fa fa-flag-checkered"></i>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary" id="route">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-list text-muted" style="margin-right: 6px;"></i> <?php echo $this->lang->line('gate_pass_list'); ?></h3>
                        <?php if ($this->rbac->hasPrivilege('front_office_gate_pass', 'can_add')) { ?>
                            <div class="box-tools pull-right">
                                <button type="button" id="btn-open-gatepass-drawer" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> <?php echo $this->lang->line('add_gate_pass'); ?></button>
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
                                        <th>Father Name</th>
                                        <th><?php echo $this->lang->line('date'); ?></th>
                                        <th>Duration</th>
                                        <th><?php echo $this->lang->line('out_time'); ?></th>
                                        <th>In Time (Exp.)</th>
                                        <th>Actual In-Time</th>
                                        <th>Reason</th>
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
                                                <td class="mailbox-name"><code style="background: #f1f5f9; color: #4f46e5; padding: 2px 6px; border-radius: 4px; font-weight: 600;"><?php echo html_escape($gatepass['gate_pass_no']); ?></code></td>
                                                <td class="mailbox-name"><span class="badge" style="background: #f8fafc; color: #475569; border: 1px solid #cbd5e1;"><?php echo ucfirst($gatepass['user_type']); ?></span></td>
                                                <td class="mailbox-name"><strong style="color: #0f172a;"><?php echo html_escape($gatepass['user_details']); ?></strong></td>
                                                <td class="mailbox-name"><?php echo isset($gatepass['father_name']) ? html_escape($gatepass['father_name']) : '-'; ?></td>
                                                <td class="mailbox-name white-space-nowrap">
                                                    <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($gatepass['date'])); ?>
                                                </td>
                                                <td class="mailbox-name">
                                                    <?php 
                                                    $is_partial = (isset($gatepass['pass_type']) && $gatepass['pass_type'] !== '')
                                                        ? ($gatepass['pass_type'] !== 'Full Day')
                                                        : (!empty($gatepass['in_time']) && $gatepass['in_time'] != '00:00:00');

                                                    if ($is_partial) {
                                                        echo '<span class="badge" style="background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd;">Partial Time</span>';
                                                    } else {
                                                        echo '<span class="badge" style="background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0;">Full Day</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td class="mailbox-name"><?php echo html_escape($gatepass['out_time']); ?></td>
                                                <td class="mailbox-name"><?php echo (!empty($gatepass['in_time']) && $gatepass['in_time'] != '00:00:00') ? html_escape($gatepass['in_time']) : '-'; ?></td>
                                                <td class="mailbox-name white-space-nowrap">
                                                    <?php if (!empty($gatepass['actual_in_time']) && $gatepass['actual_in_time'] != '00:00:00') { ?>
                                                        <span style="font-weight: 600; color: #059669;"><?php echo html_escape($gatepass['actual_in_time']); ?></span>
                                                    <?php } elseif ($is_partial && $gatepass['status'] != 'Rejected') { ?>
                                                        <span class="text-muted" style="font-style: italic; font-size: 11px; margin-right: 6px;">Not Returned</span>
                                                        <?php if ($this->rbac->hasPrivilege('front_office_gate_pass', 'can_edit')) { ?>
                                                            <button type="button" class="btn btn-xs btn-quick-mark-in" onclick="quickMarkInTime('<?php echo $gatepass['id']; ?>', '<?php echo html_escape($gatepass['gate_pass_no']); ?>')" data-toggle="tooltip" title="Mark In-Time & Complete" style="display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 50%; background: #10b981; color: #fff; border: none; padding: 0; cursor: pointer; transition: all 0.2s; box-shadow: 0 1px 3px rgba(16,185,129,0.4);">
                                                                <i class="fa fa-plus" style="font-size: 11px; font-weight: bold;"></i>
                                                            </button>
                                                        <?php } ?>
                                                    <?php } else { ?>
                                                        <span class="text-muted">-</span>
                                                    <?php } ?>
                                                </td>
                                                <td class="mailbox-name"><?php echo html_escape($gatepass['reason']); ?></td>
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
                                                <td class="mailbox-date text-right white-space-nowrap">
                                                    <?php if ($this->rbac->hasPrivilege('front_office_gate_pass', 'can_edit')) { ?>
                                                        <a onclick="updateStatus('<?php echo $gatepass['id']; ?>', '<?php echo $gatepass['status']; ?>', '<?php echo $gatepass['actual_in_time']; ?>')" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('update_status'); ?>">
                                                            <i class="fa fa-pencil text-primary"></i>
                                                        </a>
                                                    <?php } ?>
                                                    
                                                    <?php if ($gatepass['status'] == 'Approved' || $gatepass['status'] == 'Completed') { ?>
                                                        <a onclick="printGatePass('<?php echo $gatepass['id']; ?>')" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('print'); ?>">
                                                            <i class="fa fa-print"></i>
                                                        </a>
                                                    <?php } ?>

                                                    <?php if ($this->rbac->hasPrivilege('front_office_gate_pass', 'can_delete')) { ?>
                                                        <a href="<?php echo base_url(); ?>admin/gatepass/delete/<?php echo $gatepass['id'] ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                            <i class="fa fa-trash text-danger"></i>
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

<!-- Slide-in Right Drawer for Adding Gate Pass -->
<div id="gatepass-drawer-overlay" class="modern-drawer-overlay"></div>
<div id="gatepass-drawer-panel" class="modern-drawer-panel">
    <form id="formadd" action="<?php echo site_url('admin/gatepass/create') ?>" method="post" accept-charset="utf-8">
        <div class="modern-drawer-header">
            <h4 class="modern-drawer-title"><i class="fa fa-id-badge" style="color: var(--primary-theme-color, #4f46e5);"></i> <?php echo $this->lang->line('add_gate_pass'); ?></h4>
            <button type="button" class="modern-drawer-close" id="btn-close-gatepass-drawer">&times;</button>
        </div>
        <div class="modern-drawer-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('user_type'); ?></label><small class="req"> *</small>
                        <select class="form-control" name="user_type" id="user_type" onchange="resetUserSelect()">
                            <option value="student"><?php echo $this->lang->line('student'); ?></option>
                            <option value="staff"><?php echo $this->lang->line('staff'); ?></option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                        <select class="form-control select2" style="width:100%" name="user_id" id="user_id">
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('date'); ?></label><small class="req"> *</small>
                        <input type="text" name="date" class="form-control date" id="date" readonly value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Duration</label><small class="req"> *</small>
                        <select class="form-control" name="pass_type" id="pass_type" onchange="toggleOutTime()">
                            <option value="Partial">Partial Time</option>
                            <option value="Full Day">Full Day</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6" id="out_time_container">
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
                <div class="col-sm-6" id="in_time_container_add">
                    <div class="form-group">
                        <label><?php echo $this->lang->line('in_time'); ?> (Expected)</label>
                        <div class="input-group">
                            <input type="text" name="in_time" class="form-control timepicker" id="in_time_add">
                            <div class="input-group-addon" style="cursor: pointer;" onclick="$('#in_time_add').focus();">
                                <i class="fa fa-clock-o"></i>
                            </div>
                        </div>
                        <span id="in_time_error"></span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label><?php echo $this->lang->line('reason'); ?></label>
                <textarea name="reason" class="form-control" id="reason" rows="3" placeholder="State purpose for leaving campus..."></textarea>
            </div>
        </div>
        <div class="modern-drawer-footer" style="display: flex; justify-content: flex-end; gap: 8px;">
            <button type="button" class="btn btn-default" id="btn-cancel-gatepass-drawer"><?php echo $this->lang->line('cancel'); ?></button>
            <button type="submit" class="btn btn-primary" id="btn-save-gatepass" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><i class="fa fa-check"></i> <?php echo $this->lang->line('save') ?></button>
            <button type="submit" class="btn btn-info" id="btn-save-print-gatepass" name="save_and_print" value="1" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><i class="fa fa-print"></i> Save & Print</button>
        </div>
    </form>
</div>

<!-- Status Modal -->
<div class="modal fade" id="statusModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><i class="fa fa-pencil text-primary" style="margin-right: 6px;"></i> <?php echo $this->lang->line('update_status'); ?></h4>
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
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                    <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('save'); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Custom Quick Gatepass In-Time Confirmation Modal -->
<div class="modal fade" id="quickGatepassTimeModal" tabindex="-1" role="dialog" aria-labelledby="quickGatepassTimeModalLabel" style="z-index: 1060;">
    <div class="modal-dialog modal-sm" role="document" style="max-width: 380px; margin-top: 15vh;">
        <div class="modal-content" style="border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); border: none;">
            <div class="modal-body" style="padding: 24px; text-align: center;">
                <div style="width: 52px; height: 52px; border-radius: 50%; background: #ecfdf5; color: #10b981; display: inline-flex; align-items: center; justify-content: center; font-size: 24px; margin-bottom: 16px;">
                    <i class="fa fa-check-circle"></i>
                </div>
                <h4 style="font-weight: 700; color: #0f172a; margin: 0 0 8px 0;">Mark Return In-Time</h4>
                <p style="color: #64748b; font-size: 13px; margin: 0 0 16px 0;" id="qgp-modal-desc"></p>
                <div style="margin-bottom: 20px; text-align: left;">
                    <label style="font-size: 12px; color: #475569; font-weight: 600; margin-bottom: 4px; display: block;">Return In-Time:</label>
                    <div class="input-group">
                        <input type="text" name="quick_in_time" class="form-control timepicker" id="qgp-edit-time" style="height: 38px; font-size: 15px; font-weight: 600; color: #0f172a; border-radius: 6px 0 0 6px; text-align: center;">
                        <div class="input-group-addon" style="background: #f8fafc; border-radius: 0 6px 6px 0; color: #64748b; cursor: pointer;" onclick="$('#qgp-edit-time').focus();">
                            <i class="fa fa-clock-o"></i>
                        </div>
                    </div>
                    <small class="text-muted" style="font-size: 11px; margin-top: 3px; display: block; text-align: center;">Auto-filled with current time. You can edit if needed.</small>
                </div>
                <input type="hidden" id="qgp-id" value="">
                <div style="display: flex; gap: 10px; justify-content: center;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 6px; padding: 8px 18px; font-weight: 600; color: #475569; min-width: 90px;"><?php echo $this->lang->line('cancel'); ?></button>
                    <button type="button" class="btn btn-primary" id="btn-confirm-quick-gp-time" style="border-radius: 6px; padding: 8px 20px; font-weight: 600; background: #10b981; border-color: #10b981; min-width: 100px;">Confirm</button>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
.bootstrap-timepicker-widget.dropdown-menu,
.bootstrap-timepicker-widget {
    z-index: 100000 !important;
}
</style>

<script>
    var lastClickedSubmitBtn = null;

    $(document).ready(function () {
        $('#user_id').select2({
            width: '100%',
            placeholder: 'Search name / roll no / ID',
            allowClear: true,
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

        $('#btn-open-gatepass-drawer').on('click', function() {
            $('#formadd').trigger("reset");
            $('#user_id').val(null).trigger('change');
            openGatepassDrawer();
        });
        $('#btn-close-gatepass-drawer, #btn-cancel-gatepass-drawer, #gatepass-drawer-overlay').on('click', function() {
            closeGatepassDrawer();
        });
        
        $('.timepicker').timepicker({
            showInputs: false,
            showMeridian: false
        });

        $('#formadd button[type=submit]').on('click', function() {
            lastClickedSubmitBtn = $(this);
        });

        $('#formadd').on('submit', (function (e) {
            e.preventDefault();
            var $this = lastClickedSubmitBtn || $(this).find("button[type=submit]:first");
            var isSaveAndPrint = ($this.attr('id') === 'btn-save-print-gatepass');

            $('#formadd').find('.text-danger').remove();

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
                            if (value != '') {
                                var $elem = $('#' + index);
                                if ($elem.length === 0) {
                                    $elem = $('[name="' + index + '"]');
                                }
                                if ($elem.length) {
                                    $elem.closest('.form-group').append('<span class="text-danger">' + value + '</span>');
                                }
                            }
                        });
                    } else {
                        successMsg(res.message);
                        if (isSaveAndPrint && res.id) {
                            printGatePass(res.id);
                            setTimeout(function() {
                                window.location.reload(true);
                            }, 1000);
                        } else {
                            window.location.reload(true);
                        }
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
            $('#in_time').val(getGatepassCurrentTimeString());
        }
        checkCompletedStatus();
        $('#statusModal').modal('show');
    }

    function checkCompletedStatus() {
        if ($('#update_status').val() == 'Completed') {
            $('#in_time_div').show();
            if (!$('#in_time').val()) {
                $('#in_time').val(getGatepassCurrentTimeString());
            }
        } else {
            $('#in_time_div').hide();
        }
    }
    
    function quickMarkInTime(id, passNo) {
        var currentTime = getGatepassCurrentTimeString();
        $('#qgp-id').val(id);
        $('#qgp-edit-time').val(currentTime);
        $('#qgp-modal-desc').text('Mark return in-time and complete Gate Pass ' + passNo + '.');
        $('#quickGatepassTimeModal').modal('show');
        $('#qgp-edit-time').timepicker({
            showInputs: false,
            showMeridian: false,
            defaultTime: currentTime
        });
    }

    $(document).on('click', '#btn-confirm-quick-gp-time', function () {
        var $btn = $(this);
        var id = $('#qgp-id').val();
        var currentTime = $('#qgp-edit-time').val() || getGatepassCurrentTimeString();

        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Saving...');

        $.ajax({
            url: '<?php echo site_url('admin/gatepass/update_status'); ?>',
            type: 'POST',
            data: {
                id: id,
                status: 'Completed',
                in_time: currentTime
            },
            dataType: 'json',
            success: function (res) {
                if (res.status === 'success') {
                    $('#quickGatepassTimeModal').modal('hide');
                    successMsg(res.message);
                    window.location.reload(true);
                } else {
                    errorMsg(res.message || 'Failed to update');
                    $btn.prop('disabled', false).text('Confirm');
                }
            },
            error: function () {
                errorMsg('Failed to update');
                $btn.prop('disabled', false).text('Confirm');
            }
        });
    });

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

    function getGatepassCurrentTimeString() {
        var now = new Date();
        var hours = now.getHours();
        var minutes = now.getMinutes();
        var formattedHours = hours < 10 ? '0' + hours : hours;
        var formattedMinutes = minutes < 10 ? '0' + minutes : minutes;
        return formattedHours + ':' + formattedMinutes;
    }

    function openGatepassDrawer() {
        var currentTime = getGatepassCurrentTimeString();
        if (!$('#out_time').val()) {
            $('#out_time').val(currentTime);
        }

        $('#gatepass-drawer-overlay').addClass('is-active');
        $('#gatepass-drawer-panel').addClass('is-open');
        $('body').addClass('drawer-open').css('overflow', 'hidden');
    }

    function closeGatepassDrawer() {
        $('#gatepass-drawer-panel').removeClass('is-open');
        $('#gatepass-drawer-overlay').removeClass('is-active');
        $('body').removeClass('drawer-open').css('overflow', '');
    }
</script>
