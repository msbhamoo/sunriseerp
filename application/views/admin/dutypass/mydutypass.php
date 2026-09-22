<style type="text/css">
    .mydutypass-page {
        font-family: inherit;
        color: #0f172a;
    }

    .mdp-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        padding: 20px;
        margin-bottom: 20px;
    }

    .mdp-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .mdp-table th {
        background: #f8fafc;
        color: #475569;
        font-weight: 700;
        font-size: 11.5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 10px 12px;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
    }

    .mdp-table td {
        padding: 10px 12px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .mdp-table tbody tr:hover {
        background-color: #f8fafc;
    }

    .mdp-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        height: 36px;
        padding: 0 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        border: 1px solid transparent;
        cursor: pointer;
        transition: all 0.15s ease;
        text-decoration: none !important;
    }

    .mdp-btn-primary {
        background: #0d9488;
        color: #ffffff;
    }

    .mdp-btn-primary:hover {
        background: #0f766e;
        color: #ffffff;
    }

    .pill-approved { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; padding:3px 9px; border-radius:20px; font-size:11.5px; font-weight:700; }
    .pill-pending { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; padding:3px 9px; border-radius:20px; font-size:11.5px; font-weight:700; }
    .pill-completed { background: #e0e7ff; color: #4338ca; border: 1px solid #c7d2fe; padding:3px 9px; border-radius:20px; font-size:11.5px; font-weight:700; }
    .pill-rejected { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; padding:3px 9px; border-radius:20px; font-size:11.5px; font-weight:700; }
</style>

<div class="content-wrapper mydutypass-page">
    <section class="content-header">
        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
            <h1 style="margin:0; font-size:22px; font-weight:800; color:#0f172a;">
                <i class="fa fa-id-badge text-teal" style="color:#0d9488;"></i> My Field Duty Passes (On Duty)
            </h1>
            <div>
                <?php if ($this->rbac->hasPrivilege('my_duty_pass', 'can_add')) { ?>
                    <button type="button" class="mdp-btn mdp-btn-primary" onclick="openApplyDutyPassModal()">
                        <i class="fa fa-paper-plane"></i> Apply for Field Duty
                    </button>
                <?php } ?>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="mdp-card">
            <div class="table-responsive">
                <table class="mdp-table">
                    <thead>
                        <tr>
                            <th width="40">#</th>
                            <th width="130">Pass No</th>
                            <th>Duty / Event Name</th>
                            <th>Category</th>
                            <th>Venue / Destination</th>
                            <th width="160">Duty Period</th>
                            <th>Colleagues / Team</th>
                            <th width="100">Status</th>
                            <th width="90" class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (!empty($duty_passes)) {
                            $cnt = 1;
                            foreach ($duty_passes as $pass) {
                                $from_ts = strtotime($pass['from_date']);
                                $to_ts = strtotime($pass['to_date']);
                                $days_diff = round(($to_ts - $from_ts) / 86400) + 1;
                                $is_multiday = ($days_diff > 1);
                                $status_class = 'pill-' . strtolower($pass['status']);
                        ?>
                            <tr>
                                <td><?php echo $cnt++; ?></td>
                                <td>
                                    <code style="background:#f0fdfa; color:#0f766e; padding:3px 6px; border-radius:5px; font-weight:700; font-size:12px; border:1px solid #99f6e4;">
                                        <?php echo html_escape($pass['duty_pass_no']); ?>
                                    </code>
                                </td>
                                <td>
                                    <div style="font-weight:700; color:#0f172a;"><?php echo html_escape($pass['title']); ?></div>
                                    <?php if (!empty($pass['transport_details'])) { ?>
                                        <div style="font-size:11px; color:#64748b; margin-top:2px;">
                                            <i class="fa fa-bus text-info"></i> <?php echo html_escape($pass['transport_details']); ?>
                                        </div>
                                    <?php } ?>
                                </td>
                                <td>
                                    <span style="background:#f1f5f9; color:#334155; padding:2px 7px; border-radius:4px; font-size:11px; font-weight:700;">
                                        <?php echo html_escape($pass['category']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div style="font-weight:600; color:#334155;"><i class="fa fa-map-marker text-danger"></i> <?php echo html_escape($pass['venue']); ?></div>
                                </td>
                                <td>
                                    <div style="font-weight:700; color:#0f172a; font-size:12px;">
                                        <?php echo date($this->customlib->getSchoolDateFormat(), $from_ts); ?>
                                        <?php if ($is_multiday) { ?>
                                            → <?php echo date($this->customlib->getSchoolDateFormat(), $to_ts); ?>
                                        <?php } ?>
                                    </div>
                                    <div style="font-size:10.5px; color:#64748b; margin-top:2px;">
                                        <?php echo $is_multiday ? ($days_diff . ' Days Trip') : '1 Day Duty'; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                    $members = array();
                                    foreach ($pass['staff_members'] as $m) {
                                        $members[] = $m['name'] . ' ' . $m['surname'];
                                    }
                                    echo html_escape(implode(', ', $members));
                                    ?>
                                </td>
                                <td>
                                    <span class="<?php echo $status_class; ?>">
                                        <?php echo html_escape($pass['status']); ?>
                                    </span>
                                </td>
                                <td class="text-right">
                                    <a href="<?php echo base_url('admin/dutypass/print_dutypass/' . $pass['id']); ?>" target="_blank" class="btn btn-default btn-xs" style="border-radius:6px; font-weight:700; color:#0d9488;" title="Print Duty Pass Slip">
                                        <i class="fa fa-print"></i> Print
                                    </a>
                                </td>
                            </tr>
                        <?php 
                            }
                        } else {
                        ?>
                            <tr>
                                <td colspan="9" class="text-center" style="padding:36px; color:#94a3b8;">
                                    <i class="fa fa-id-badge" style="font-size:36px; margin-bottom:8px; display:block;"></i>
                                    <strong>No Field Duty Passes Found</strong>
                                    <div style="font-size:12px; margin-top:4px;">You have no field duty assignments or pending requests.</div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<!-- Modal: Apply for Field Duty -->
<div class="modal fade" id="applyDutyPassModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="border-radius:12px; overflow:hidden;">
            <div class="modal-header" style="background:linear-gradient(135deg, #0d9488 0%, #0f766e 100%); color:#fff; padding:14px 20px;">
                <button type="button" class="close" data-dismiss="modal" style="color:#fff; opacity:0.8;">&times;</button>
                <h4 class="modal-title" style="font-weight:800; font-size:16px;">
                    <i class="fa fa-paper-plane"></i> Apply for Field Duty Assignment
                </h4>
            </div>
            <form id="applyDutyPassForm" method="post" enctype="multipart/form-data">
                <div class="modal-body" style="padding:20px; max-height:calc(100vh - 200px); overflow-y:auto;">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label style="font-weight:700; font-size:12.5px;">Duty Title / Event Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="title" placeholder="e.g. District Sports Meet / Board Exam Duty" required style="border-radius:8px;">
                                <span class="text-danger error-title"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label style="font-weight:700; font-size:12.5px;">Category / Purpose <span class="text-danger">*</span></label>
                                <select class="form-control" name="category" style="border-radius:8px;" required>
                                    <option value="Sports">Sports Tournament / Meet</option>
                                    <option value="Competition">Inter-School Competition</option>
                                    <option value="Exam Duty">Board / External Exam Duty</option>
                                    <option value="Workshop">Seminar / Workshop / Training</option>
                                    <option value="Educational Tour">Educational Tour / Excursion</option>
                                    <option value="Official Duty">Official / Administrative Duty</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label style="font-weight:700; font-size:12.5px;">Venue / Destination Location <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="venue" placeholder="e.g. SMS Stadium, Jaipur" required style="border-radius:8px;">
                        <span class="text-danger error-venue"></span>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="font-weight:700; font-size:12.5px;">From Date <span class="text-danger">*</span></label>
                                <input type="text" class="form-control date" name="from_date" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" readonly style="background:#fff; cursor:pointer; border-radius:8px;" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="font-weight:700; font-size:12.5px;">To Date <span class="text-danger">*</span></label>
                                <input type="text" class="form-control date" name="to_date" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" readonly style="background:#fff; cursor:pointer; border-radius:8px;" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="font-weight:700; font-size:12.5px;">Departure Time (Optional)</label>
                                <input type="text" class="form-control time" name="departure_time" placeholder="HH:MM AM/PM" style="border-radius:8px;">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="font-weight:700; font-size:12.5px;">Expected Return Time (Optional)</label>
                                <input type="text" class="form-control time" name="return_time" placeholder="HH:MM AM/PM" style="border-radius:8px;">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label style="font-weight:700; font-size:12.5px;">Transport Details (Optional)</label>
                        <input type="text" class="form-control" name="transport_details" placeholder="e.g. School Bus No 2 / Self" style="border-radius:8px;">
                    </div>

                    <div class="form-group">
                        <label style="font-weight:700; font-size:12.5px;">Accompanying Students / Team Info (Optional)</label>
                        <textarea class="form-control" name="student_details" rows="2" placeholder="e.g. 10 students of Class 9 & 10" style="border-radius:8px;"></textarea>
                    </div>

                    <div class="form-group">
                        <label style="font-weight:700; font-size:12.5px;">Purpose Description / Remarks</label>
                        <textarea class="form-control" name="description" rows="2" placeholder="Details regarding the event or assignment..." style="border-radius:8px;"></textarea>
                    </div>

                    <div class="form-group">
                        <label style="font-weight:700; font-size:12.5px;">Duty Circular / Invitation Document (Optional)</label>
                        <input type="file" name="document" class="form-control" style="border-radius:8px;">
                    </div>
                </div>
                <div class="modal-footer" style="background:#f8fafc; border-top:1px solid #e2e8f0; padding:12px 20px;">
                    <button type="button" class="btn btn-default" data-dismiss="modal" style="border-radius:8px; font-weight:600;">Cancel</button>
                    <button type="submit" id="apply_dp_btn" class="mdp-btn mdp-btn-primary">
                        <i class="fa fa-paper-plane"></i> Submit Request
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
function openApplyDutyPassModal() {
    $('#applyDutyPassForm')[0].reset();
    $('.text-danger[class*="error-"]').text('');
    $('#applyDutyPassModal').modal('show');
}

$('#applyDutyPassForm').on('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);

    $('#apply_dp_btn').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Submitting...');
    $('.text-danger[class*="error-"]').text('');

    $.ajax({
        url: '<?php echo site_url("admin/mydutypass/apply"); ?>',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            $('#apply_dp_btn').prop('disabled', false).html('<i class="fa fa-paper-plane"></i> Submit Request');
            if (response.status === 'success') {
                $('#applyDutyPassModal').modal('hide');
                successMsg(response.message);
                setTimeout(function() {
                    location.reload();
                }, 800);
            } else {
                if (response.error) {
                    $.each(response.error, function(key, val) {
                        $('.error-' + key).text(val);
                    });
                }
                if (response.message) {
                    errorMsg(response.message);
                }
            }
        },
        error: function() {
            $('#apply_dp_btn').prop('disabled', false).html('<i class="fa fa-paper-plane"></i> Submit Request');
            errorMsg('Something went wrong. Please try again.');
        }
    });
});
</script>
