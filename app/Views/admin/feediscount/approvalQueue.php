<style type="text/css">
    /* Table & Status Badges */
    .approval-table-wrapper {
        margin-top: 10px;
    }
    .approval-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 8px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 11px;
        transition: all 0.15s ease;
    }
    .approval-action-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    }
    .reason-chip {
        display: inline-block;
        background: #f1f5f9;
        color: #475569;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    /* Right-Sidebar Drawer (Matching Standard System Drawer) */
    .modal-right-panel .modal-dialog {
        position: fixed;
        margin: 0;
        width: 560px;
        height: 100%;
        right: 0px;
        top: 0px;
        z-index: 1050;
    }
    @media (max-width: 768px) {
        .modal-right-panel .modal-dialog {
            width: 100%;
        }
    }
    .modal-right-panel .modal-content {
        height: 100%;
        overflow-y: auto;
        border-radius: 0;
        border: none;
        box-shadow: -10px 0 35px rgba(15, 23, 42, 0.18);
        display: flex;
        flex-direction: column;
        background: #ffffff;
    }
    .modal-right-panel .modal-header {
        background: #ffffff;
        color: #0f172a;
        border-radius: 0;
        padding: 18px 24px;
        flex-shrink: 0;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .modal-right-panel .modal-header .modal-title {
        font-size: 17px;
        font-weight: 700;
        color: #0f172a;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .modal-right-panel .modal-header .modal-title i {
        color: #114B5F;
        font-size: 18px;
    }
    .modal-right-panel .modal-header .close {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        width: 32px;
        height: 32px;
        font-size: 16px;
        color: #64748b;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        opacity: 1;
        transition: all 0.2s ease;
        margin: 0;
        padding: 0;
    }
    .modal-right-panel .modal-header .close:hover {
        background: #fee2e2;
        color: #ef4444;
        border-color: #fca5a5;
        opacity: 1;
    }
    .modal-right-panel.fade .modal-dialog {
        right: -600px;
        -webkit-transition: right 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        -moz-transition: right 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        transition: right 0.35s cubic-bezier(0.16, 1, 0.3, 1);
    }
    .modal-right-panel.fade.in .modal-dialog {
        right: 0;
    }
    .modal-right-panel .modal-body {
        padding: 24px 28px;
        flex: 1 1 auto;
        background: #ffffff;
        overflow-y: scroll !important;
        overflow-x: hidden !important;
        -webkit-overflow-scrolling: touch;
    }
    .modal-right-panel .modal-footer {
        padding: 16px 28px;
        border-top: 1px solid #e2e8f0;
        background: #ffffff;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        align-items: center;
        flex-shrink: 0;
    }
    .student-search-box {
        position: relative;
        margin-bottom: 18px;
    }
    .student-search-results {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: #ffffff;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        max-height: 280px;
        overflow-y: auto;
        z-index: 1100;
        display: none;
        margin-top: 6px;
        padding: 6px;
    }
    .student-search-item {
        display: flex;
        align-items: center;
        padding: 10px 12px;
        margin-bottom: 4px;
        border-radius: 8px;
        border: 1px solid #f1f5f9;
        background: #ffffff;
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none !important;
        color: #1e293b !important;
    }
    .student-search-item:hover {
        background: #114B5F !important;
        color: #ffffff !important;
        border-color: #114B5F !important;
    }
    .student-search-item:hover .st-subtext,
    .student-search-item:hover .st-badge,
    .student-search-item:hover i {
        color: #ffffff !important;
        opacity: 0.95;
    }
    .student-card-preview {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 18px;
    }
    .fee-stat-badge {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px;
        text-align: center;
    }
    .fee-stat-title {
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 700;
        color: #64748b;
        margin-bottom: 2px;
        letter-spacing: 0.5px;
    }
    .fee-stat-value {
        font-size: 16px;
        font-weight: 800;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-money"></i> <?php echo $this->lang->line('fees_collection'); ?>
        </h1>
    </section>

    <section class="content">
        <?php
        // Calculate Metrics
        $total_req = count($requests);
        $pending_count = 0;
        $approved_count = 0;
        $rejected_count = 0;

        foreach ($requests as $r) {
            if ($r['status'] == 'pending' || $r['status'] == 'provisional') {
                $pending_count++;
            } elseif ($r['status'] == 'approved') {
                $approved_count++;
            } else {
                $rejected_count++;
            }
        }
        ?>

        <!-- KPI Metrics Grid -->
        <div class="modern-stat-grid" style="margin-bottom: 16px;">
            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Total Requests</div>
                    <div class="stat-value"><?php echo $total_req; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(99, 102, 241, 0.12); color: #6366f1;">
                    <i class="fa fa-list"></i>
                </div>
            </div>

            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Pending / Provisional</div>
                    <div class="stat-value text-warning" style="color: #d97706;"><?php echo $pending_count; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(245, 158, 11, 0.12); color: #d97706;">
                    <i class="fa fa-clock-o"></i>
                </div>
            </div>

            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Approved Discounts</div>
                    <div class="stat-value text-success" style="color: #059669;"><?php echo $approved_count; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(16, 185, 129, 0.12); color: #059669;">
                    <i class="fa fa-check-circle"></i>
                </div>
            </div>

            <div class="modern-stat-card">
                <div class="modern-stat-info">
                    <div class="stat-label">Rejected Requests</div>
                    <div class="stat-value text-danger" style="color: #dc2626;"><?php echo $rejected_count; ?></div>
                </div>
                <div class="modern-stat-icon" style="background: rgba(239, 68, 68, 0.12); color: #ef4444;">
                    <i class="fa fa-times-circle"></i>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title titlefix">
                            <i class="fa fa-tags text-muted" style="margin-right: 6px;"></i> Dynamic Discount Approvals
                        </h3>
                        <div class="box-tools pull-right">
                            <?php if ($this->rbac->hasPrivilege('fee_discount_approval', 'can_edit')) { ?>
                            <button type="button" class="btn btn-sm btn-primary" id="openDirectDiscountBtn" style="border-radius: 8px; font-weight: 600;">
                                <i class="fa fa-plus"></i> Apply Student Discount
                            </button>
                            <?php } ?>
                        </div>
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
                                                <td>
                                                    <strong><?php echo $request['firstname'] . " " . $request['lastname']; ?></strong>
                                                    <div class="text-muted" style="font-size: 11px;">Adm: <?php echo $request['admission_no']; ?></div>
                                                </td>
                                                <td><?php echo $request['class'] . " (" . $request['section'] . ")"; ?></td>
                                                <td><span class="label label-default" style="text-transform: uppercase;"><?php echo ucfirst($request['discount_type']); ?></span></td>
                                                <td>
                                                    <strong style="color: #0f172a;">
                                                    <?php if ($request['discount_type'] == 'fix') {
                                                        echo $currency_symbol . $request['amount'];
                                                    } else {
                                                        echo $request['percentage'] . "%";
                                                    } ?>
                                                    </strong>
                                                </td>
                                                <td><span class="reason-chip"><?php echo $request['reason']; ?></span></td>
                                                <td>
                                                    <div style="font-weight: 600;"><?php echo $request['staff_name'] . " " . $request['staff_surname']; ?></div>
                                                    <div class="text-muted" style="font-size: 11px;"><?php echo $request['staff_employee_id']; ?></div>
                                                </td>
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
                                                            <a href="<?php echo base_url(); ?>admin/feediscount/approveRequest/<?php echo $request['id']; ?>" class="btn btn-default btn-xs approval-action-btn" data-toggle="tooltip" title="Approve" onclick="return confirm('Are you sure you want to approve this discount?');">
                                                                <i class="fa fa-check text-success"></i> Approve
                                                            </a>
                                                            <a href="#" class="btn btn-default btn-xs reject_btn approval-action-btn" data-toggle="tooltip" title="Reject" data-id="<?php echo $request['id']; ?>">
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

<!-- Right-Sidebar Modal for Direct Student Discount -->
<div class="modal fade modal-right-panel" id="directDiscountModal" tabindex="-1" role="dialog" aria-labelledby="directDiscountModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="directDiscountModalLabel">
                    <i class="fa fa-plus-circle" style="color: #114B5F; font-size: 18px;"></i> Apply Student Fee Discount
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <!-- Search Input -->
                <div class="student-search-box">
                    <label style="font-weight: 600; color: #334155; margin-bottom: 6px;">Search Student <small class="text-muted">(by Name, Father Name, or Admission No)</small></label>
                    <div class="input-group" style="width: 100%;">
                        <span class="input-group-addon" style="background: #fff; border-right: none; color: #94a3b8;"><i class="fa fa-search"></i></span>
                        <input type="text" id="discount_student_search" class="form-control" placeholder="Type at least 2 characters..." autocomplete="off" style="border-left: none; box-shadow: none;">
                    </div>
                    <div id="student_search_results_container" class="student-search-results"></div>
                </div>

                <!-- Loader Indicator -->
                <div id="discount_student_loader" style="display: none; text-align: center; padding: 25px 0;">
                    <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
                    <p style="margin-top: 8px; color: #64748b; font-size: 13px;">Loading student fee details...</p>
                </div>

                <!-- Student & Fee Profile Section -->
                <div id="student_fee_profile" style="display: none;">
                    <!-- Student Card -->
                    <div class="student-card-preview">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <img id="d_student_img" src="" alt="Student" style="width: 48px; height: 48px; min-width: 48px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0; background: #f8fafc;">
                            <div style="flex: 1; min-width: 0;">
                                <h4 id="d_student_name" style="margin: 0 0 3px 0; font-size: 15px; font-weight: 700; color: #1e293b; text-transform: uppercase; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;"></h4>
                                <div style="font-size: 12px; color: #64748b;">
                                    <span id="d_student_class_sec" style="font-weight: 600;"></span> &nbsp;|&nbsp; 
                                    Adm No: <span id="d_student_adm" style="font-weight: 600; color: #0f172a;"></span>
                                </div>
                                <div style="font-size: 11px; color: #64748b; margin-top: 2px;">
                                    <i class="fa fa-user"></i> Father: <span id="d_student_father" style="font-weight: 500;"></span>
                                </div>
                            </div>
                            <a id="d_student_addfee_link" href="#" target="_blank" class="btn btn-xs btn-default" style="flex-shrink: 0;" title="Open Fee Collection Page">
                                <i class="fa fa-external-link text-primary"></i> View Add Fee
                            </a>
                        </div>

                        <!-- 3 Stat Badges -->
                        <div class="row" style="margin-top: 12px;">
                            <div class="col-xs-4" style="padding-right: 4px;">
                                <div class="fee-stat-badge">
                                    <div class="fee-stat-title">Total Fee</div>
                                    <div class="fee-stat-value text-muted" id="d_total_fee">0.00</div>
                                </div>
                            </div>
                            <div class="col-xs-4" style="padding-left: 4px; padding-right: 4px;">
                                <div class="fee-stat-badge">
                                    <div class="fee-stat-title">Paid</div>
                                    <div class="fee-stat-value text-success" id="d_total_paid">0.00</div>
                                </div>
                            </div>
                            <div class="col-xs-4" style="padding-left: 4px;">
                                <div class="fee-stat-badge" style="background: #fef2f2; border-color: #fee2e2;">
                                    <div class="fee-stat-title" style="color: #991b1b;">Balance</div>
                                    <div class="fee-stat-value text-danger" id="d_total_balance">0.00</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Fee Breakdown Toggle -->
                    <div style="margin-bottom: 12px;">
                        <a href="#feeBreakdownCollapse" data-toggle="collapse" style="font-size: 12px; font-weight: 600; color: #3b82f6; text-decoration: none;">
                            <i class="fa fa-list"></i> View Fee Breakdown & Existing Discounts <i class="fa fa-chevron-down" style="font-size: 10px;"></i>
                        </a>
                        <div id="feeBreakdownCollapse" class="collapse" style="margin-top: 8px;">
                            <div class="table-responsive" style="max-height: 180px; overflow-y: auto; background: #fff; border: 1px solid #e2e8f0; border-radius: 6px;">
                                <table class="table table-condensed table-striped" style="font-size: 11px; margin-bottom: 0;">
                                    <thead>
                                        <tr style="background: #f1f5f9;">
                                            <th>Fee Head</th>
                                            <th class="text-right">Amount</th>
                                            <th class="text-right">Paid</th>
                                            <th class="text-right">Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody id="d_fee_breakdown_body"></tbody>
                                </table>
                            </div>
                            <div id="d_existing_discounts_container" style="margin-top: 6px; font-size: 11px; color: #64748b;"></div>
                        </div>
                    </div>

                    <!-- Discount Application Form -->
                    <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 18px;">
                        <h4 style="margin: 0 0 14px 0; font-size: 14px; font-weight: 700; color: #1e293b;">
                            <i class="fa fa-tag" style="color: #114B5F;"></i> Discount Details
                        </h4>
                        <form id="direct_discount_form">
                            <input type="hidden" name="student_session_id" id="form_student_session_id" value="">
                            
                            <div class="form-group" style="margin-bottom: 14px;">
                                <label style="font-size: 12.5px; font-weight: 600; color: #334155;">Discount Type <small class="text-danger">*</small></label>
                                <div style="display: flex; gap: 20px; align-items: center; margin-top: 4px;">
                                    <label class="radio-inline" style="font-size: 13px; font-weight: 500;">
                                        <input type="radio" name="discount_type" value="fix" checked class="discount_type_radio"> Fixed Amount (<?php echo $currency_symbol; ?>)
                                    </label>
                                    <label class="radio-inline" style="font-size: 13px; font-weight: 500;">
                                        <input type="radio" name="discount_type" value="percentage" class="discount_type_radio"> Percentage (%)
                                    </label>
                                </div>
                            </div>

                            <div class="form-group" id="discount_amount_group" style="margin-bottom: 14px;">
                                <label style="font-size: 12.5px; font-weight: 600; color: #334155;">Discount Amount (<?php echo $currency_symbol; ?>) <small class="text-danger">*</small></label>
                                <input type="number" step="0.01" min="0" class="form-control" name="amount" id="discount_amount_val" placeholder="e.g. 500" required>
                                <span class="text-danger" id="err_amount" style="font-size: 11px;"></span>
                            </div>

                            <div class="form-group" id="discount_percentage_group" style="display: none; margin-bottom: 14px;">
                                <label style="font-size: 12.5px; font-weight: 600; color: #334155;">Discount Percentage (%) <small class="text-danger">*</small></label>
                                <input type="number" step="0.01" min="0" max="100" class="form-control" name="percentage" id="discount_percentage_val" placeholder="e.g. 10">
                                <span class="text-danger" id="err_percentage" style="font-size: 11px;"></span>
                            </div>

                            <div class="form-group" style="margin-bottom: 14px;">
                                <label style="font-size: 12.5px; font-weight: 600; color: #334155;">Discount Reason / Type <small class="text-danger">*</small> <small class="text-muted">(Select or type to add new)</small></label>
                                <select class="form-control select2_discount_reason" name="reason" id="discount_reason_val" style="width: 100%;" required>
                                    <option value="">-- Select or Type New Reason --</option>
                                    <?php if (!empty($discount_categories)) {
                                        foreach ($discount_categories as $cat) { ?>
                                            <option value="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?></option>
                                        <?php }
                                    } ?>
                                </select>
                                <span class="text-danger" id="err_reason" style="font-size: 11px;"></span>
                            </div>

                            <!-- Live Calculated Balance Preview -->
                            <div id="new_balance_preview" style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px 14px; margin-bottom: 14px; display: none;">
                                <div style="display: flex; justify-content: space-between; font-size: 12px; color: #166534; font-weight: 600;">
                                    <span>Calculated Discount:</span>
                                    <span id="preview_discount_val">0.00</span>
                                </div>
                                <div style="display: flex; justify-content: space-between; font-size: 13px; color: #15803d; font-weight: 700; margin-top: 3px;">
                                    <span>New Estimated Due Balance:</span>
                                    <span id="preview_new_balance_val">0.00</span>
                                </div>
                            </div>

                            <div class="modal-footer" style="padding: 16px 0 0 0; margin-top: 18px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end; gap: 10px;">
                                <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius: 8px; font-weight: 600; padding: 6px 18px;">Cancel</button>
                                <button type="submit" class="btn btn-primary" id="submitDirectDiscountBtn" style="border-radius: 8px; font-weight: 600; padding: 6px 18px; background: #114B5F; border-color: #114B5F;">
                                    <i class="fa fa-check"></i> Save
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
        var base_url = '<?php echo base_url(); ?>';
        var currentStudentSessionId = null;
        var currentStudentBalance = 0;
        var currentCurrencySymbol = '<?php echo $currency_symbol; ?>';
        var searchTimeout = null;

        // Initialize Select2 with Tagging (Allows selecting existing or typing new reason)
        $('.select2_discount_reason').select2({
            tags: true,
            placeholder: '-- Select or Type New Reason --',
            allowClear: true,
            dropdownParent: $('#directDiscountModal')
        });

        // Open Drawer
        $('#openDirectDiscountBtn').click(function() {
            $('#direct_discount_form')[0].reset();
            $('.select2_discount_reason').val('').trigger('change');
            $('#student_fee_profile').hide();
            $('#student_search_results_container').empty().hide();
            $('#discount_student_search').val('');
            $('#new_balance_preview').hide();
            $('.text-danger[id^="err_"]').empty();
            $('#directDiscountModal').modal('show');
            setTimeout(function() {
                $('#discount_student_search').focus();
            }, 350);
        });

        // Search Input Handler (Debounced)
        $('#discount_student_search').on('keyup', function() {
            clearTimeout(searchTimeout);
            var query = $(this).val().trim();
            var resultsContainer = $('#student_search_results_container');

            if (query.length >= 2) {
                searchTimeout = setTimeout(function() {
                    $.ajax({
                        url: base_url + 'admin/feediscount/ajax_search_students',
                        type: 'POST',
                        data: { search_text: query },
                        dataType: 'json',
                        success: function(res) {
                            resultsContainer.empty().show();
                            if (res.status === 'success' && res.data && res.data.length > 0) {
                                res.data.forEach(function(st) {
                                    var itemHtml = `
                                        <div class="student-search-item" data-session-id="${st.student_session_id}">
                                            <img src="${st.image}" style="width: 36px; height: 36px; min-width: 36px; border-radius: 50%; object-fit: cover; margin-right: 10px; border: 1px solid #cbd5e1;">
                                            <div style="flex: 1; min-width: 0;">
                                                <div style="font-weight: 700; font-size: 13px; text-transform: uppercase;">${st.full_name}</div>
                                                <div class="st-subtext" style="font-size: 11px; opacity: 0.85;">
                                                    ${st.class} (${st.section}) &nbsp;|&nbsp; Adm: ${st.admission_no} &nbsp;|&nbsp; Father: ${st.father_name}
                                                </div>
                                            </div>
                                            <i class="fa fa-chevron-right" style="opacity: 0.5; font-size: 12px; margin-left: 6px;"></i>
                                        </div>
                                    `;
                                    resultsContainer.append(itemHtml);
                                });
                            } else {
                                resultsContainer.html('<div style="padding: 12px; text-align: center; color: #94a3b8; font-size: 12px;">No students found</div>');
                            }
                        }
                    });
                }, 300);
            } else {
                resultsContainer.empty().hide();
            }
        });

        // Click on student search result
        $(document).on('click', '.student-search-item', function() {
            var studentSessionId = $(this).data('session-id');
            $('#student_search_results_container').hide();
            loadStudentFeeDetails(studentSessionId);
        });

        // Close search dropdown on click outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.student-search-box').length) {
                $('#student_search_results_container').hide();
            }
        });

        // Load Student Fee Summary
        function loadStudentFeeDetails(studentSessionId) {
            currentStudentSessionId = studentSessionId;
            $('#discount_student_loader').show();
            $('#student_fee_profile').hide();
            $('#form_student_session_id').val(studentSessionId);

            $.ajax({
                url: base_url + 'admin/feediscount/get_student_fee_summary',
                type: 'POST',
                data: { student_session_id: studentSessionId },
                dataType: 'json',
                success: function(res) {
                    $('#discount_student_loader').hide();
                    if (res.status === 'success') {
                        var st = res.student;
                        var sm = res.summary;
                        currentCurrencySymbol = res.currency_symbol || '<?php echo $currency_symbol; ?>';
                        currentStudentBalance = parseFloat(sm.total_balance) || 0;

                        $('#d_student_img').attr('src', st.image);
                        $('#d_student_name').text(st.full_name);
                        $('#d_student_class_sec').text(st.class + ' (' + st.section + ')');
                        $('#d_student_adm').text(st.admission_no);
                        $('#d_student_father').text(st.father_name);
                        $('#d_student_addfee_link').attr('href', base_url + 'studentfee/addfee/' + st.student_session_id);

                        $('#d_total_fee').text(currentCurrencySymbol + parseFloat(sm.total_fee).toFixed(2));
                        $('#d_total_paid').text(currentCurrencySymbol + parseFloat(sm.total_paid).toFixed(2));
                        $('#d_total_balance').text(currentCurrencySymbol + currentStudentBalance.toFixed(2));

                        // Populate breakdown
                        var tbody = $('#d_fee_breakdown_body');
                        tbody.empty();
                        if (res.breakdown && res.breakdown.length > 0) {
                            res.breakdown.forEach(function(row) {
                                tbody.append(`
                                    <tr>
                                        <td><strong>${row.name}</strong> <span class="text-muted" style="font-size: 10px;">(Due: ${row.due_date})</span></td>
                                        <td class="text-right">${currentCurrencySymbol}${parseFloat(row.amount).toFixed(2)}</td>
                                        <td class="text-right text-success">${currentCurrencySymbol}${parseFloat(row.paid).toFixed(2)}</td>
                                        <td class="text-right text-danger" style="font-weight:600;">${currentCurrencySymbol}${parseFloat(row.balance).toFixed(2)}</td>
                                    </tr>
                                `);
                            });
                        } else {
                            tbody.append('<tr><td colspan="4" class="text-center text-muted">No fee records found for this student</td></tr>');
                        }

                        // Existing discounts
                        var disBox = $('#d_existing_discounts_container');
                        disBox.empty();
                        if (res.assigned_discounts && res.assigned_discounts.length > 0) {
                            var disTags = res.assigned_discounts.map(function(d) {
                                var val = d.type === 'fix' ? currentCurrencySymbol + parseFloat(d.amount).toFixed(2) : d.percentage + '%';
                                return `<span class="label label-info" style="margin-right: 4px;">${d.name} (${val}) - ${d.status}</span>`;
                            }).join(' ');
                            disBox.html('<strong>Allotted Discounts:</strong> ' + disTags);
                        }

                        $('#student_fee_profile').fadeIn(200);
                        calculatePreviewBalance();
                    } else {
                        errorMsg(res.message || 'Unable to fetch student details');
                    }
                },
                error: function() {
                    $('#discount_student_loader').hide();
                    errorMsg('Error connecting to server. Please try again.');
                }
            });
        }

        // Toggle Discount Type
        $('.discount_type_radio').change(function() {
            if ($(this).val() === 'percentage') {
                $('#discount_percentage_group').show();
                $('#discount_percentage_val').prop('required', true);
                $('#discount_amount_group').hide();
                $('#discount_amount_val').prop('required', false).val('');
            } else {
                $('#discount_amount_group').show();
                $('#discount_amount_val').prop('required', true);
                $('#discount_percentage_group').hide();
                $('#discount_percentage_val').prop('required', false).val('');
            }
            calculatePreviewBalance();
        });

        // Live calculation on amount / percentage change
        $('#discount_amount_val, #discount_percentage_val').on('input keyup change', function() {
            calculatePreviewBalance();
        });

        function calculatePreviewBalance() {
            var type = $('input[name="discount_type"]:checked').val();
            var calculatedDiscount = 0;

            if (type === 'fix') {
                var amt = parseFloat($('#discount_amount_val').val()) || 0;
                calculatedDiscount = amt;
            } else {
                var per = parseFloat($('#discount_percentage_val').val()) || 0;
                if (per > 0) {
                    calculatedDiscount = (currentStudentBalance * per) / 100;
                }
            }

            if (calculatedDiscount > 0) {
                var newBal = Math.max(0, currentStudentBalance - calculatedDiscount);
                $('#preview_discount_val').text(currentCurrencySymbol + calculatedDiscount.toFixed(2));
                $('#preview_new_balance_val').text(currentCurrencySymbol + newBal.toFixed(2));
                $('#new_balance_preview').slideDown(150);
            } else {
                $('#new_balance_preview').slideUp(150);
            }
        }

        // Submit Direct Discount Form
        $('#direct_discount_form').submit(function(e) {
            e.preventDefault();
            var form = $(this);
            var submitBtn = $('#submitDirectDiscountBtn');
            $('.text-danger[id^="err_"]').empty();

            submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Submitting...');

            $.ajax({
                url: base_url + 'admin/feediscount/apply_direct_discount',
                type: 'POST',
                data: form.serialize(),
                dataType: 'json',
                success: function(res) {
                    submitBtn.prop('disabled', false).html('<i class="fa fa-check"></i> Apply Discount');
                    if (res.status === 'success') {
                        $('#directDiscountModal').modal('hide');
                        successMsg(res.message);
                        setTimeout(function() {
                            location.reload(true);
                        }, 1200);
                    } else if (res.status === 'fail') {
                        if (res.error) {
                            $.each(res.error, function(key, val) {
                                $('#err_' + key).html(val);
                            });
                        } else if (res.message) {
                            errorMsg(res.message);
                        }
                    }
                },
                error: function() {
                    submitBtn.prop('disabled', false).html('<i class="fa fa-check"></i> Apply Discount');
                    errorMsg('An error occurred. Please try again.');
                }
            });
        });

        // Reject button logic
        $('.reject_btn').click(function() {
            var request_id = $(this).data('id');
            $('#reject_request_id').val(request_id);
            $('#rejectModal').modal('show');
        });
    });
</script>

