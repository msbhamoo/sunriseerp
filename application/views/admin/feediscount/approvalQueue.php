<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> <?php echo $this->lang->line('fees_collection'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix">
                            <i class="fa fa-check-circle"></i> Dynamic Discount Approvals
                        </h3>
                    </div>
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) {
                            echo $this->session->flashdata('msg');
                        } ?>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Student</th>
                                        <th>Class (Section)</th>
                                        <th>Discount Type</th>
                                        <th>Amount / %</th>
                                        <th>Reason</th>
                                        <th>Requested By</th>
                                        <th>Status</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($requests)) { ?>
                                        <tr>
                                            <td colspan="9" class="text-center text-danger">No discount requests found</td>
                                        </tr>
                                    <?php } else {
                                        foreach ($requests as $request) { ?>
                                            <tr>
                                                <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($request['created_at'])); ?></td>
                                                <td><?php echo $request['firstname'] . " " . $request['lastname'] . " (" . $request['admission_no'] . ")"; ?></td>
                                                <td><?php echo $request['class'] . " (" . $request['section'] . ")"; ?></td>
                                                <td><?php echo ucfirst($request['discount_type']); ?></td>
                                                <td>
                                                    <?php if ($request['discount_type'] == 'fix') {
                                                        echo $currency_symbol . $request['amount'];
                                                    } else {
                                                        echo $request['percentage'] . "%";
                                                    } ?>
                                                </td>
                                                <td><?php echo $request['reason']; ?></td>
                                                <td><?php echo $request['staff_name'] . " " . $request['staff_surname'] . " (" . $request['staff_employee_id'] . ")"; ?></td>
                                                <td>
                                                    <?php
                                                    if ($request['status'] == 'pending') {
                                                        echo "<span class='label label-warning'>Pending</span>";
                                                    } elseif ($request['status'] == 'provisional') {
                                                        echo "<span class='label label-info'>Provisional</span>";
                                                    } elseif ($request['status'] == 'approved') {
                                                        echo "<span class='label label-success'>Approved by " . $request['admin_name'] . "</span>";
                                                    } else {
                                                        echo "<span class='label label-danger'>Rejected by " . $request['admin_name'] . "</span>";
                                                    }
                                                    ?>
                                                </td>
                                                <td class="text-right">
                                                    <?php if ($request['status'] == 'pending' || $request['status'] == 'provisional') { ?>
                                                        <?php if ($this->rbac->hasPrivilege('fee_discount_approval', 'can_edit')) { ?>
                                                            <a href="<?php echo base_url(); ?>admin/feediscount/approveRequest/<?php echo $request['id']; ?>" class="btn btn-default btn-xs" data-toggle="tooltip" title="Approve" onclick="return confirm('Are you sure you want to approve this discount?');">
                                                                <i class="fa fa-check text-success"></i> Approve
                                                            </a>
                                                            <a href="#" class="btn btn-default btn-xs reject_btn" data-toggle="tooltip" title="Reject" data-id="<?php echo $request['id']; ?>">
                                                                <i class="fa fa-times text-danger"></i> Reject
                                                            </a>
                                                        <?php } ?>
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

<div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Reject Discount Request</h4>
            </div>
            <form action="<?php echo site_url('admin/feediscount/rejectRequest'); ?>" method="POST" id="reject_form">
                <div class="modal-body">
                    <input type="hidden" name="request_id" id="reject_request_id" value="">
                    <div class="form-group">
                        <label>Reason for Rejection <small class="req"> *</small></label>
                        <textarea class="form-control" name="admin_remark" id="admin_remark" required></textarea>
                    </div>
                    <?php if ($this->rbac->hasPrivilege('fee_discount_approval', 'can_edit')) { ?>
                        <div class="alert alert-warning">
                            <i class="fa fa-warning"></i> If this is a provisional request, rejecting it will automatically adjust the student's due balance by removing the discount.
                        </div>
                    <?php } ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Request</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $('.reject_btn').click(function() {
            var request_id = $(this).data('id');
            $('#reject_request_id').val(request_id);
            $('#rejectModal').modal('show');
        });
    });
</script>
