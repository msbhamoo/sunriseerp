<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-building-o"></i> <?php echo $this->lang->line('hostel'); ?></h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo $this->lang->line('hostel_gate_pass'); ?> List</h3>
                        <?php if ($this->rbac->hasPrivilege('hostel_gate_pass', 'can_add')) { ?>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#addGatePassModal">
                                    <i class="fa fa-plus"></i> Add Gate Pass
                                </button>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) { ?>
                            <?php echo $this->session->flashdata('msg'); ?>
                        <?php } ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Admission No</th>
                                        <th>Going To</th>
                                        <th>Pass Type</th>
                                        <th>Out Date & Time</th>
                                        <th>Expected In</th>
                                        <th>Actual In</th>
                                        <th>Status</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($gate_passes)) {
                                        foreach ($gate_passes as $gp) { ?>
                                            <tr>
                                                <td><?php echo $gp['firstname'] . ' ' . $gp['lastname']; ?></td>
                                                <td><?php echo $gp['admission_no']; ?></td>
                                                <td><?php echo $gp['going_to']; ?></td>
                                                <td><?php echo isset($gp['pass_type']) ? $gp['pass_type'] : 'Day Pass'; ?></td>
                                                <td><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($gp['out_date'])) . ' ' . date("h:i A", strtotime($gp['out_time'])); ?></td>
                                                <td>
                                                    <?php 
                                                    $exp_str = '';
                                                    if (!empty($gp['expected_in_date'])) {
                                                        $exp_str .= date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($gp['expected_in_date'])) . ' ';
                                                    }
                                                    if (!empty($gp['expected_in_time'])) {
                                                        $exp_str .= date("h:i A", strtotime($gp['expected_in_time']));
                                                    }
                                                    echo $exp_str;
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                    $act_str = '';
                                                    if (!empty($gp['actual_in_date'])) {
                                                        $act_str .= date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($gp['actual_in_date'])) . ' ';
                                                    }
                                                    if (!empty($gp['actual_in_time'])) {
                                                        $act_str .= date("h:i A", strtotime($gp['actual_in_time']));
                                                    }
                                                    echo $act_str;
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php if ($gp['status'] == 'Out') { ?>
                                                        <span class="label label-warning">Out</span>
                                                    <?php } else { ?>
                                                        <span class="label label-success">Returned</span>
                                                    <?php } ?>
                                                </td>
                                                <td class="text-right">
                                                    <?php if ($this->rbac->hasPrivilege('hostel_gate_pass', 'can_view')) { ?>
                                                        <a href="javascript:void(0);" class="btn btn-default btn-xs" data-toggle="tooltip" title="Print Pass" onclick="printGatePass(<?php echo $gp['id']; ?>)">
                                                            <i class="fa fa-print"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if ($gp['status'] == 'Out' && $this->rbac->hasPrivilege('hostel_gate_pass', 'can_edit')) { ?>
                                                        <a href="<?php echo site_url('admin/hostelgatepass/mark_returned/'.$gp['id']); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="Mark Returned" onclick="return confirm('Are you sure you want to mark as returned?');">
                                                            <i class="fa fa-check"></i>
                                                        </a>
                                                    <?php } ?>
                                                    <?php if ($this->rbac->hasPrivilege('hostel_gate_pass', 'can_delete')) { ?>
                                                        <a href="<?php echo site_url('admin/hostelgatepass/delete/'.$gp['id']); ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="Delete" onclick="return confirm('Are you sure you want to delete this record?');">
                                                            <i class="fa fa-trash"></i>
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

<!-- Add Gate Pass Modal -->
<div class="modal fade" id="addGatePassModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Add Gate Pass</h4>
            </div>
            <form id="addGatePassForm" action="<?php echo site_url('admin/hostelgatepass/add') ?>" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Select Student <small class="req"> *</small></label>
                        <select class="form-control" name="student_session_id" id="student_session_id" style="width:100%;" required>
                            <!-- Select2 AJAX populated -->
                        </select>
                        <span class="text-danger" id="error_student_session_id"></span>
                    </div>
                    <div class="form-group">
                        <label>Pass Type <small class="req"> *</small></label>
                        <select class="form-control" name="pass_type" id="pass_type" required>
                            <option value="Day Pass">Day Pass</option>
                            <option value="Holiday Pass">Holiday Pass</option>
                        </select>
                        <span class="text-danger" id="error_pass_type"></span>
                    </div>
                    <div class="form-group">
                        <label>Going To <small class="req"> *</small></label>
                        <input type="text" class="form-control" name="going_to" required>
                        <span class="text-danger" id="error_going_to"></span>
                    </div>
                    <div class="form-group">
                        <label>Reason</label>
                        <textarea class="form-control" name="reason"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Out Date <small class="req"> *</small></label>
                                <input type="text" class="form-control date" name="out_date" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" required readonly>
                                <span class="text-danger" id="error_out_date"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Out Time <small class="req"> *</small></label>
                                <input type="time" class="form-control" name="out_time" required>
                                <span class="text-danger" id="error_out_time"></span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Exp. Return Date</label>
                                <input type="text" class="form-control date" name="expected_in_date" value="" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Exp. Return Time</label>
                                <input type="time" class="form-control" name="expected_in_time">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info pull-right" id="submitBtn">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Select2 CSS/JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#student_session_id').select2({
            dropdownParent: $('#addGatePassModal'),
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
            placeholder: 'Search by Name or Admission No',
            minimumInputLength: 2
        });

        $('#addGatePassForm').on('submit', function (e) {
            e.preventDefault();
            var form = $(this);
            var btn = $('#submitBtn');
            btn.button('loading');
            $('.text-danger').html('');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function (res) {
                    if (res.status == 'fail') {
                        $.each(res.error, function (key, value) {
                            $('#error_' + key).html(value);
                        });
                        btn.button('reset');
                    } else if (res.status == 'success') {
                        successMsg(res.message);
                        window.location.reload();
                    }
                }
            });
        });
    });

    function printGatePass(id) {
        $.ajax({
            url: '<?php echo site_url('admin/hostelgatepass/print_pass/'); ?>' + id,
            type: 'POST',
            success: function (data) {
                var newWin = window.open('', 'Print-Window');
                newWin.document.open();
                newWin.document.write('<html><head><title>Gate Pass</title>');
                newWin.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">');
                newWin.document.write('</head><body onload="window.print()">');
                newWin.document.write(data);
                newWin.document.write('</body></html>');
                newWin.document.close();
                setTimeout(function () { newWin.close(); }, 10);
            }
        });
    }
</script>
