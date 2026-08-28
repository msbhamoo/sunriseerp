<?php
$total_passes = count($gate_passes);
$out_count = 0;
$returned_count = 0;
$day_pass_count = 0;
$holiday_pass_count = 0;

foreach ($gate_passes as $gp) {
    if (isset($gp['status'])) {
        if ($gp['status'] == 'Out') { 
            $out_count++; 
        } elseif ($gp['status'] == 'Returned') { 
            $returned_count++; 
        }
    }
    if (isset($gp['pass_type'])) {
        if ($gp['pass_type'] == 'Holiday Pass') {
            $holiday_pass_count++;
        } else {
            $day_pass_count++;
        }
    }
}
?>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-building-o"></i> <?php echo $this->lang->line('hostel'); ?></h1>
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
                    <div class="stat-label">Currently Out</div>
                    <div class="stat-value text-warning" style="color: #d97706;"><?php echo $out_count; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b;">
                    <i class="fa fa-sign-out"></i>
                </div>
            </div>
            
            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Returned / Completed</div>
                    <div class="stat-value text-success" style="color: #059669;"><?php echo $returned_count; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
                    <i class="fa fa-check-circle"></i>
                </div>
            </div>
            
            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Holiday Passes</div>
                    <div class="stat-value" style="color: #0284c7;"><?php echo $holiday_pass_count; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(14, 165, 233, 0.12); color: #0284c7;">
                    <i class="fa fa-plane"></i>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary" id="route">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><i class="fa fa-list text-muted" style="margin-right: 6px;"></i> <?php echo $this->lang->line('hostel_gate_pass'); ?> List</h3>
                        <?php if ($this->rbac->hasPrivilege('hostel_gate_pass', 'can_add')) { ?>
                            <div class="box-tools pull-right">
                                <button type="button" id="btn-open-hostelgatepass-drawer" class="btn btn-primary btn-sm">
                                    <i class="fa fa-plus"></i> Add Gate Pass
                                </button>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) { ?>
                            <?php echo $this->session->flashdata('msg'); ?>
                        <?php } ?>
                        <div class="mailbox-messages table-responsive">
                            <div class="download_label"><?php echo $this->lang->line('hostel_gate_pass'); ?> List</div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Admission No</th>
                                        <th>Class</th>
                                        <th>Going To</th>
                                        <th>Pass Type</th>
                                        <th>Out Date & Time</th>
                                        <th>Expected In</th>
                                        <th>Actual In</th>
                                        <th>Reason</th>
                                        <th>Status</th>
                                        <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($gate_passes)) {
                                        foreach ($gate_passes as $gp) {
                                            ?>
                                            <tr>
                                                <td class="mailbox-name"><strong style="color: #0f172a;"><?php echo html_escape($gp['firstname'] . ' ' . $gp['lastname']); ?></strong></td>
                                                <td class="mailbox-name"><code style="background: #f1f5f9; color: #4f46e5; padding: 2px 6px; border-radius: 4px; font-weight: 600;"><?php echo html_escape($gp['admission_no']); ?></code></td>
                                                <td class="mailbox-name">
                                                    <?php echo !empty($gp['class_name']) ? html_escape($gp['class_name'] . ' (' . $gp['section_name'] . ')') : '-'; ?>
                                                </td>
                                                <td class="mailbox-name"><?php echo html_escape($gp['going_to']); ?></td>
                                                <td class="mailbox-name">
                                                    <?php 
                                                    $ptype = isset($gp['pass_type']) ? $gp['pass_type'] : 'Day Pass';
                                                    if ($ptype == 'Holiday Pass') {
                                                        echo '<span class="badge" style="background: #fdf4ff; color: #9333ea; border: 1px solid #f5d0fe;">Holiday Pass</span>';
                                                    } else {
                                                        echo '<span class="badge" style="background: #e0f2fe; color: #0284c7; border: 1px solid #bae6fd;">Day Pass</span>';
                                                    }
                                                    ?>
                                                </td>
                                                <td class="mailbox-name white-space-nowrap">
                                                    <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($gp['out_date'])) . ' ' . (!empty($gp['out_time']) ? date("h:i A", strtotime($gp['out_time'])) : ''); ?>
                                                </td>
                                                <td class="mailbox-name white-space-nowrap">
                                                    <?php 
                                                    $exp_str = '';
                                                    if (!empty($gp['expected_in_date'])) {
                                                        $exp_str .= date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($gp['expected_in_date'])) . ' ';
                                                    }
                                                    if (!empty($gp['expected_in_time'])) {
                                                        $exp_str .= date("h:i A", strtotime($gp['expected_in_time']));
                                                    }
                                                    echo !empty($exp_str) ? $exp_str : '-';
                                                    ?>
                                                </td>
                                                <td class="mailbox-name white-space-nowrap">
                                                    <?php if ($gp['status'] == 'Returned') { ?>
                                                        <span style="font-weight: 600; color: #059669;">
                                                            <?php 
                                                            $act_str = '';
                                                            if (!empty($gp['actual_in_date'])) {
                                                                $act_str .= date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($gp['actual_in_date'])) . ' ';
                                                            }
                                                            if (!empty($gp['actual_in_time'])) {
                                                                $act_str .= date("h:i A", strtotime($gp['actual_in_time']));
                                                            }
                                                            echo !empty($act_str) ? $act_str : 'Returned';
                                                            ?>
                                                        </span>
                                                    <?php } else { ?>
                                                        <span class="text-muted" style="font-style: italic; font-size: 11px; margin-right: 6px;">Out Campus</span>
                                                        <?php if ($this->rbac->hasPrivilege('hostel_gate_pass', 'can_edit')) { ?>
                                                            <button type="button" class="btn btn-xs" onclick="quickMarkHostelReturned('<?php echo $gp['id']; ?>', '<?php echo html_escape($gp['firstname'] . ' ' . $gp['lastname']); ?>')" data-toggle="tooltip" title="Mark Returned Now" style="display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 50%; background: #10b981; color: #fff; border: none; padding: 0; cursor: pointer; transition: all 0.2s; box-shadow: 0 1px 3px rgba(16,185,129,0.4);">
                                                                <i class="fa fa-check" style="font-size: 11px; font-weight: bold;"></i>
                                                            </button>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </td>
                                                <td class="mailbox-name"><?php echo !empty($gp['reason']) ? html_escape($gp['reason']) : '-'; ?></td>
                                                <td class="mailbox-name">
                                                    <?php
                                                    if ($gp['status'] == 'Out') {
                                                        echo "<span class='label label-warning'>Out</span>";
                                                    } else {
                                                        echo "<span class='label label-success'>Returned</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td class="mailbox-date text-right white-space-nowrap">
                                                    <div class="dropdown" style="display: inline-block;">
                                                        <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius: 6px; padding: 4px 8px; color: #475569; background: #f8fafc; border-color: #e2e8f0;">
                                                            <i class="fa fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-right" style="border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; min-width: 150px; padding: 4px 0; font-size: 13px; z-index: 1050;">
                                                            <?php if ($this->rbac->hasPrivilege('hostel_gate_pass', 'can_view')) { ?>
                                                                <li>
                                                                    <a href="javascript:void(0);" onclick="printGatePass('<?php echo $gp['id']; ?>')">
                                                                        <i class="fa fa-print text-info" style="width: 18px;"></i> <?php echo $this->lang->line('print'); ?>
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                            
                                                            <?php if ($gp['status'] == 'Out' && $this->rbac->hasPrivilege('hostel_gate_pass', 'can_edit')) { ?>
                                                                <li>
                                                                    <a href="<?php echo site_url('admin/hostelgatepass/mark_returned/'.$gp['id']); ?>" onclick="return confirm('Are you sure you want to mark as returned?');">
                                                                        <i class="fa fa-check text-success" style="width: 18px;"></i> Mark Returned
                                                                    </a>
                                                                </li>
                                                            <?php } ?>

                                                            <?php if ($this->rbac->hasPrivilege('hostel_gate_pass', 'can_delete')) { ?>
                                                                <li role="separator" class="divider" style="margin: 4px 0;"></li>
                                                                <li>
                                                                    <a href="<?php echo site_url('admin/hostelgatepass/delete/'.$gp['id']); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');" style="color: #ef4444;">
                                                                        <i class="fa fa-trash text-danger" style="width: 18px;"></i> <?php echo $this->lang->line('delete'); ?>
                                                                    </a>
                                                                </li>
                                                            <?php } ?>
                                                        </ul>
                                                    </div>
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

<!-- Slide-in Right Drawer for Adding Hostel Gate Pass -->
<div id="hostelgatepass-drawer-overlay" class="modern-drawer-overlay"></div>
<div id="hostelgatepass-drawer-panel" class="modern-drawer-panel">
    <form id="addHostelGatePassForm" action="<?php echo site_url('admin/hostelgatepass/add') ?>" method="post" accept-charset="utf-8">
        <div class="modern-drawer-header">
            <h4 class="modern-drawer-title"><i class="fa fa-id-badge" style="color: var(--primary-theme-color, #4f46e5);"></i> Add Hostel Gate Pass</h4>
            <button type="button" class="modern-drawer-close" id="btn-close-hostelgatepass-drawer">&times;</button>
        </div>
        <div class="modern-drawer-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label>Select Hostel Student <small class="req"> *</small></label>
                        <select class="form-control" name="student_session_id" id="student_session_id" style="width:100%;" required>
                        </select>
                        <span class="text-danger" id="error_student_session_id"></span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Pass Type <small class="req"> *</small></label>
                        <select class="form-control" name="pass_type" id="pass_type" required>
                            <option value="Day Pass">Day Pass</option>
                            <option value="Holiday Pass">Holiday Pass</option>
                        </select>
                        <span class="text-danger" id="error_pass_type"></span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Going To / Destination <small class="req"> *</small></label>
                        <input type="text" class="form-control" name="going_to" id="going_to" placeholder="e.g. Market / Home" required>
                        <span class="text-danger" id="error_going_to"></span>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Out Date <small class="req"> *</small></label>
                        <input type="text" name="out_date" class="form-control date" id="out_date" readonly value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" required>
                        <span class="text-danger" id="error_out_date"></span>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Out Time <small class="req"> *</small></label>
                        <div class="input-group">
                            <input type="text" name="out_time" class="form-control timepicker" id="out_time" required>
                            <div class="input-group-addon" style="cursor: pointer;" onclick="$('#out_time').focus();">
                                <i class="fa fa-clock-o"></i>
                            </div>
                        </div>
                        <span class="text-danger" id="error_out_time"></span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Exp. Return Date</label>
                        <input type="text" name="expected_in_date" class="form-control date" id="expected_in_date" readonly value="">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label>Exp. Return Time</label>
                        <div class="input-group">
                            <input type="text" name="expected_in_time" class="form-control timepicker" id="expected_in_time">
                            <div class="input-group-addon" style="cursor: pointer;" onclick="$('#expected_in_time').focus();">
                                <i class="fa fa-clock-o"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Reason / Note</label>
                <textarea name="reason" class="form-control" id="reason" rows="3" placeholder="State purpose for leaving hostel..."></textarea>
            </div>
        </div>
        <div class="modern-drawer-footer" style="display: flex; justify-content: flex-end; gap: 8px;">
            <button type="button" class="btn btn-default" id="btn-cancel-hostelgatepass-drawer"><?php echo $this->lang->line('cancel'); ?></button>
            <button type="submit" class="btn btn-primary" id="btn-save-hostelgatepass" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><i class="fa fa-check"></i> <?php echo $this->lang->line('save') ?></button>
            <button type="submit" class="btn btn-info" id="btn-save-print-hostelgatepass" name="save_and_print" value="1" data-loading-text="<i class='fa fa-spinner fa-spin '></i> <?php echo $this->lang->line('please_wait'); ?>"><i class="fa fa-print"></i> Save & Print</button>
        </div>
    </form>
</div>

<!-- Select2 CSS/JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<style>
/* Modern KPI Stats Styles */
.modern-stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
    margin-bottom: 20px;
}

.modern-stat-card {
    background: #ffffff;
    border-radius: 10px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.07), 0 1px 2px 0 rgba(0, 0, 0, 0.04);
    border: 1px solid #e2e8f0;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.modern-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.08), 0 2px 4px -1px rgba(0, 0, 0, 0.04);
}

.stat-label {
    font-size: 13px;
    font-weight: 500;
    color: #64748b;
    margin-bottom: 4px;
    text-transform: capitalize;
}

.stat-value {
    font-size: 22px;
    font-weight: 700;
    color: #0f172a;
    line-height: 1.2;
}

.modern-stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    flex-shrink: 0;
}

/* Modern Right Drawer CSS */
.modern-drawer-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(15, 23, 42, 0.4);
    backdrop-filter: blur(2px);
    z-index: 1040;
    opacity: 0;
    visibility: hidden;
    transition: opacity 0.3s ease, visibility 0.3s ease;
}

.modern-drawer-overlay.is-active {
    opacity: 1;
    visibility: visible;
}

.modern-drawer-panel {
    position: fixed;
    top: 0;
    right: -520px;
    width: 480px;
    max-width: 92vw;
    height: 100vh;
    background: #ffffff;
    z-index: 1050;
    box-shadow: -8px 0 24px rgba(0, 0, 0, 0.12);
    display: flex;
    flex-direction: column;
    transition: right 0.35s cubic-bezier(0.16, 1, 0.3, 1);
}

.modern-drawer-panel.is-open {
    right: 0;
}

.modern-drawer-panel form {
    display: flex;
    flex-direction: column;
    height: 100%;
    margin-bottom: 0;
}

.modern-drawer-header {
    padding: 16px 20px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #f8fafc;
}

.modern-drawer-title {
    margin: 0;
    font-size: 16px;
    font-weight: 700;
    color: #0f172a;
    display: flex;
    align-items: center;
    gap: 8px;
}

.modern-drawer-close {
    background: transparent;
    border: none;
    font-size: 24px;
    line-height: 1;
    color: #94a3b8;
    cursor: pointer;
    padding: 0;
    transition: color 0.15s ease;
}

.modern-drawer-close:hover {
    color: #0f172a;
}

.modern-drawer-body {
    padding: 20px;
    overflow-y: auto;
    flex-grow: 1;
}

.modern-drawer-footer {
    padding: 14px 20px;
    border-top: 1px solid #e2e8f0;
    background: #f8fafc;
    flex-shrink: 0;
}

/* Form Styles within Drawer */
.modern-drawer-body .form-group {
    margin-bottom: 16px;
}

.modern-drawer-body label {
    font-size: 12.5px;
    font-weight: 600;
    color: #334155;
    margin-bottom: 6px;
}

.modern-drawer-body .form-control {
    border-radius: 6px;
    border: 1px solid #cbd5e1;
    box-shadow: none;
    height: 38px;
    padding: 6px 12px;
    font-size: 13px;
    color: #0f172a;
    transition: border-color 0.15s ease, box-shadow 0.15s ease;
}

.modern-drawer-body textarea.form-control {
    height: auto;
}

.modern-drawer-body .form-control:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.select2-container--default .select2-selection--single {
    border-radius: 6px !important;
    border: 1px solid #cbd5e1 !important;
    height: 38px !important;
    padding: 4px 8px !important;
    font-size: 13px !important;
    position: relative !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 28px !important;
    color: #0f172a !important;
}

.bootstrap-timepicker-widget.dropdown-menu,
.bootstrap-timepicker-widget {
    z-index: 100000 !important;
}

.table-responsive {
    overflow-x: auto !important;
    overflow-y: visible !important;
    padding-bottom: 60px;
    margin-bottom: -60px;
}
</style>

<script>
    var lastClickedHostelSubmitBtn = null;

    $(document).ready(function () {
        $('#student_session_id').select2({
            dropdownParent: $('#hostelgatepass-drawer-panel'),
            width: '100%',
            ajax: {
                url: '<?php echo site_url('admin/hostelgatepass/search_student'); ?>',
                type: 'post',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        keyword: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                },
                cache: true
            },
            placeholder: 'Search by Student Name or Admission No',
            minimumInputLength: 1
        });

        $('#btn-open-hostelgatepass-drawer').on('click', function() {
            $('#addHostelGatePassForm').trigger("reset");
            $('#student_session_id').val(null).trigger('change');
            openHostelGatepassDrawer();
        });

        $('#btn-close-hostelgatepass-drawer, #btn-cancel-hostelgatepass-drawer, #hostelgatepass-drawer-overlay').on('click', function() {
            closeHostelGatepassDrawer();
        });
        
        $('.timepicker').timepicker({
            showInputs: false,
            showMeridian: false
        });

        $('#addHostelGatePassForm button[type=submit]').on('click', function() {
            lastClickedHostelSubmitBtn = $(this);
        });

        $('#addHostelGatePassForm').on('submit', (function (e) {
            e.preventDefault();
            var $this = lastClickedHostelSubmitBtn || $(this).find("button[type=submit]:first");
            var isSaveAndPrint = ($this.attr('id') === 'btn-save-print-hostelgatepass');

            $('#addHostelGatePassForm').find('.text-danger').remove();
            $this.button('loading');

            $.ajax({
                url: $(this).attr('action'),
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (res) {
                    $this.button('reset');
                    if (res.status == "fail") {
                        var message = "";
                        $.each(res.error, function (index, value) {
                            message += value;
                            $('#' + index).parents('.form-group').append('<span class="text-danger">' + value + '</span>');
                        });
                        errorMsg(message);
                    } else {
                        successMsg(res.message);
                        closeHostelGatepassDrawer();
                        if (isSaveAndPrint && res.insert_id) {
                            printGatePass(res.insert_id);
                        }
                        setTimeout(function() {
                            window.location.reload(true);
                        }, 500);
                    }
                },
                error: function () {
                    $this.button('reset');
                    errorMsg('An error occurred during submission.');
                }
            });
        }));
    });

    function quickMarkHostelReturned(id, studentName) {
        if (!confirm('Mark gate pass as returned for ' + studentName + '?')) {
            return;
        }
        $.ajax({
            url: '<?php echo site_url('admin/hostelgatepass/mark_returned/'); ?>' + id,
            type: 'POST',
            dataType: 'json',
            success: function (res) {
                if (res.status == 'success') {
                    successMsg(res.message);
                    window.location.reload(true);
                } else {
                    errorMsg('Failed to update status.');
                }
            },
            error: function() {
                window.location.href = '<?php echo site_url('admin/hostelgatepass/mark_returned/'); ?>' + id;
            }
        });
    }

    function printGatePass(id) {
        $.ajax({
            url: '<?php echo site_url('admin/hostelgatepass/print_pass/'); ?>' + id,
            type: 'POST',
            success: function (data) {
                var newWin = window.open('', 'Print-Window', 'width=800,height=600');
                newWin.document.open();
                newWin.document.write('<html><head><title>Hostel Gate Pass</title>');
                newWin.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">');
                newWin.document.write('</head><body onload="window.print()">');
                newWin.document.write(data);
                newWin.document.write('</body></html>');
                newWin.document.close();
            }
        });
    }

    function getHostelCurrentTimeString() {
        var now = new Date();
        var hours = now.getHours();
        var minutes = now.getMinutes();
        var formattedHours = hours < 10 ? '0' + hours : hours;
        var formattedMinutes = minutes < 10 ? '0' + minutes : minutes;
        return formattedHours + ':' + formattedMinutes;
    }

    function openHostelGatepassDrawer() {
        var currentTime = getHostelCurrentTimeString();
        if (!$('#out_time').val()) {
            $('#out_time').val(currentTime);
        }

        $('#hostelgatepass-drawer-overlay').addClass('is-active');
        $('#hostelgatepass-drawer-panel').addClass('is-open');
        $('body').addClass('drawer-open').css('overflow', 'hidden');
    }

    function closeHostelGatepassDrawer() {
        $('#hostelgatepass-drawer-panel').removeClass('is-open');
        $('#hostelgatepass-drawer-overlay').removeClass('is-active');
        $('body').removeClass('drawer-open').css('overflow', '');
    }
</script>
