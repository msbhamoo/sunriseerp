<?php
$date_fmt = $this->customlib->getSchoolDateFormat();
$badge = array('pending' => '#d97706', 'approved' => '#16a34a', 'rejected' => '#dc2626', 'cancelled' => '#94a3b8');
$t12 = function ($t) { return ($t && $t !== '00:00:00') ? date('h:i A', strtotime($t)) : '--'; };
?>
<style>
    .cr-tabs a { display:inline-block; padding:6px 14px; border-radius:20px; border:1px solid #cbd5e1; margin:0 6px 6px 0; font-weight:600; font-size:12.5px; color:#334155; text-decoration:none; }
    .cr-tabs a.active { background:#0f766e; border-color:#0f766e; color:#fff; }
    .cr-table td { vertical-align:top !important; font-size:12.5px; }
    .cr-status { display:inline-block; color:#fff; font-size:11px; font-weight:700; padding:2px 8px; border-radius:10px; }
    .cr-cmp b { display:inline-block; min-width:56px; color:#64748b; font-weight:600; }
    .cr-new { color:#15803d; font-weight:700; }
</style>
<div class="content-wrapper">
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-pencil-square-o"></i> Attendance Correction Requests</h3>
                <div class="box-tools pull-right">
                    <a href="<?php echo site_url('admin/staffattendance'); ?>" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Staff Attendance</a>
                </div>
            </div>
            <div class="box-body">
                <p class="text-muted" style="font-size:12px;">
                    Staff raise these from <b>Mark My Attendance</b>. Approving writes the times to the attendance record and works out the status from the shift
                    timings (On Duty requests become On Duty). Approved days are marked as set by hand, so biometric sync will not change them. Every change appears in the Change Log.
                </p>
                <div class="cr-tabs">
                    <?php foreach (array('pending' => 'Pending (' . (int) $pending_count . ')', 'approved' => 'Approved', 'rejected' => 'Rejected', 'cancelled' => 'Withdrawn', 'all' => 'All') as $k => $v) { ?>
                        <a href="<?php echo site_url('admin/staffattendance/corrections?status=' . $k); ?>" class="<?php echo $status === $k ? 'active' : ''; ?>"><?php echo $v; ?></a>
                    <?php } ?>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered cr-table">
                        <thead>
                            <tr><th>Staff</th><th>Date</th><th>Request</th><th>Recorded now</th><th>Asked for</th><th>Reason</th><th>Status</th><th style="width:120px;"></th></tr>
                        </thead>
                        <tbody>
                        <?php if (empty($requests)) { ?>
                            <tr><td colspan="8" class="text-center text-muted">No requests here.</td></tr>
                        <?php } ?>
                        <?php foreach ($requests as $r) { ?>
                            <tr>
                                <td><b><?php echo html_escape(trim($r['name'] . ' ' . $r['surname'])); ?></b><br><span class="text-muted" style="font-size:11px;"><?php echo html_escape($r['employee_id'] . ($r['role_name'] ? ' · ' . $r['role_name'] : '')); ?></span></td>
                                <td style="white-space:nowrap;"><?php echo date($date_fmt, strtotime($r['date'])); ?><br><span class="text-muted" style="font-size:11px;"><?php echo date('l', strtotime($r['date'])); ?></span></td>
                                <td><?php echo html_escape(isset($types[$r['request_type']]) ? $types[$r['request_type']] : $r['request_type']); ?><br><span class="text-muted" style="font-size:11px;">sent <?php echo date($date_fmt . ' h:i A', strtotime($r['created_at'])); ?></span></td>
                                <td class="cr-cmp" style="white-space:nowrap;"><b>In</b><?php echo $t12($r['cur_in']); ?><br><b>Out</b><?php echo $t12($r['cur_out']); ?><br><b>Status</b><?php echo html_escape($r['cur_status'] ?: 'Not marked'); ?></td>
                                <td class="cr-cmp" style="white-space:nowrap;">
                                    <?php if ($r['status'] === 'approved') { ?>
                                        <b>In</b><span class="cr-new"><?php echo $t12($r['approved_in']); ?></span><br><b>Out</b><span class="cr-new"><?php echo $t12($r['approved_out']); ?></span>
                                    <?php } else { ?>
                                        <b>In</b><?php echo $r['requested_in'] ? '<span class="cr-new">' . $t12($r['requested_in']) . '</span>' : '--'; ?><br><b>Out</b><?php echo $r['requested_out'] ? '<span class="cr-new">' . $t12($r['requested_out']) . '</span>' : '--'; ?>
                                    <?php } ?>
                                </td>
                                <td style="max-width:280px;"><?php echo html_escape($r['reason']); ?>
                                    <?php if (!empty($r['admin_remark'])) { ?><div style="margin-top:4px; font-size:11.5px;"><b>Remark:</b> <?php echo html_escape($r['admin_remark']); ?></div><?php } ?>
                                </td>
                                <td><span class="cr-status" style="background:<?php echo $badge[$r['status']] ?? '#64748b'; ?>;"><?php echo $r['status'] === 'cancelled' ? 'Withdrawn' : ucfirst(html_escape($r['status'])); ?></span>
                                    <?php if (!empty($r['reviewed_at']) && $r['status'] !== 'cancelled') { ?><div class="text-muted" style="font-size:11px; margin-top:3px;"><?php echo html_escape(trim($r['rv_name'] . ' ' . $r['rv_surname'])); ?><br><?php echo date($date_fmt, strtotime($r['reviewed_at'])); ?></div><?php } ?>
                                </td>
                                <td class="text-right">
                                    <?php if ($r['status'] === 'pending') { ?>
                                        <button type="button" class="btn btn-success btn-xs cr-review" style="margin-bottom:4px;"
                                                data-id="<?php echo $r['id']; ?>" data-decision="approve"
                                                data-name="<?php echo html_escape(trim($r['name'] . ' ' . $r['surname'])); ?>" data-date="<?php echo date($date_fmt, strtotime($r['date'])); ?>"
                                                data-type="<?php echo $r['request_type']; ?>"
                                                data-in="<?php echo $r['requested_in'] ? substr($r['requested_in'], 0, 5) : ($r['cur_in'] && $r['cur_in'] !== '00:00:00' ? substr($r['cur_in'], 0, 5) : ''); ?>"
                                                data-out="<?php echo $r['requested_out'] ? substr($r['requested_out'], 0, 5) : ($r['cur_out'] && $r['cur_out'] !== '00:00:00' ? substr($r['cur_out'], 0, 5) : ''); ?>"><i class="fa fa-check"></i> Approve</button>
                                        <button type="button" class="btn btn-danger btn-xs cr-review" data-id="<?php echo $r['id']; ?>" data-decision="reject"
                                                data-name="<?php echo html_escape(trim($r['name'] . ' ' . $r['surname'])); ?>" data-date="<?php echo date($date_fmt, strtotime($r['date'])); ?>"><i class="fa fa-times"></i> Reject</button>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="crModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document" style="max-width:440px;">
        <div class="modal-content" style="border-radius:12px;">
            <form id="crForm" autocomplete="off">
                <input type="hidden" name="id"><input type="hidden" name="decision">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" style="font-weight:700;" id="crTitle"></h4>
                </div>
                <div class="modal-body">
                    <div class="row cr-times">
                        <div class="col-xs-6 form-group"><label>In time</label><input type="time" name="in_time" class="form-control"></div>
                        <div class="col-xs-6 form-group"><label>Out time</label><input type="time" name="out_time" class="form-control"></div>
                        <div class="col-xs-12 text-muted" style="font-size:11.5px; margin:-6px 0 10px;">Adjust if needed. Leave blank to keep what the day already has. The status (Present / Late / Half Day…) is worked out from the shift timings.</div>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label>Remark <span class="text-muted cr-remark-hint" style="font-weight:400;"></span></label>
                        <textarea name="remark" class="form-control" rows="2" maxlength="500"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn" id="crSubmit"></button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
$(function () {
    $('.cr-review').on('click', function () {
        var $b = $(this), approve = $b.data('decision') === 'approve', f = $('#crForm')[0];
        f.reset();
        f.id.value = $b.data('id'); f.decision.value = $b.data('decision');
        $('#crTitle').text((approve ? 'Approve' : 'Reject') + ' – ' + $b.data('name') + ', ' + $b.data('date'));
        $('.cr-times').toggle(approve);
        if (approve) { f.in_time.value = $b.data('in') || ''; f.out_time.value = $b.data('out') || ''; }
        $('.cr-remark-hint').text(approve ? '(optional)' : '(tell the staff member why)');
        $('#crSubmit').attr('class', 'btn ' + (approve ? 'btn-success' : 'btn-danger')).text(approve ? 'Approve & update attendance' : 'Reject request');
        $('#crModal').modal('show');
    });
    $('#crForm').on('submit', function (e) {
        e.preventDefault();
        if (this.decision.value === 'reject' && !$.trim(this.remark.value)) { errorMsg('Please add a remark explaining the rejection.'); return; }
        var $s = $('#crSubmit').prop('disabled', true);
        $.post('<?php echo site_url('admin/staffattendance/reviewcorrection'); ?>', $(this).serialize(), function (res) {
            if (res.status == 1) { $('#crModal').modal('hide'); successMsg(res.message); setTimeout(function () { location.reload(); }, 900); }
            else { errorMsg(res.message); }
        }, 'json').fail(function () { errorMsg('Could not save. Please try again.'); }).always(function () { $s.prop('disabled', false); });
    });
});
</script>
