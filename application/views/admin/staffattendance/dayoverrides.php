<?php
$date_fmt = $this->customlib->getSchoolDateFormat();
$fmt = function ($ymd) use ($date_fmt) { return $ymd ? date($date_fmt, strtotime($ymd)) : ''; };
?>
<style>
    .do-card { background:#fff; border:1px solid #e2e8f0; border-radius:10px; padding:16px; margin-bottom:16px; }
    /* Wraps by the space the form actually has (the admin sidebar takes part of the screen). */
    .do-form-grid { display:flex; flex-wrap:wrap; gap:14px 16px; align-items:flex-end; }
    .do-form-grid > .do-field { flex:1 1 200px; min-width:0; }
    .do-form-grid > .do-span-3 { flex:3 1 320px; }
    .do-form-grid > .do-span-4 { flex:1 1 100%; }
    .do-form-grid > .do-field.do-btn { flex:0 1 180px; }
    .do-field label.do-l { display:block; font-weight:600; font-size:13px; color:#1e293b; margin-bottom:6px; }
    /* Working day / Holiday toggle */
    .do-seg { display:flex; border:1px solid #cbd5e1; border-radius:8px; overflow:hidden; height:40px; }
    .do-seg input { position:absolute; opacity:0; pointer-events:none; }
    .do-seg label { flex:1; margin:0; display:flex; align-items:center; justify-content:center; gap:6px; cursor:pointer; font-weight:600; font-size:13px; color:#475569; background:#fff; }
    .do-seg label.do-h { border-left:1px solid #cbd5e1; }
    .do-seg input:checked + label.do-w { background:#16a34a; color:#fff; }
    .do-seg input:checked + label.do-h { background:#7c3aed; color:#fff; }
    /* Role / staff checkbox pickers */
    .do-picker { border:1px solid #cbd5e1; border-radius:8px; background:#fff; }
    .do-picker-bar { display:flex; gap:8px; align-items:center; padding:8px; border-bottom:1px solid #e2e8f0; }
    .do-picker-bar input[type=text] { flex:1; height:32px; }
    .do-picker-bar .do-count { font-size:12px; color:#475569; white-space:nowrap; }
    .do-picker-list { max-height:220px; overflow-y:auto; padding:6px 8px; display:grid; grid-template-columns:repeat(auto-fill, minmax(240px, 1fr)); gap:2px 12px; }
    .do-picker-list label { display:flex; align-items:center; gap:8px; margin:0; padding:5px 4px; font-weight:500; font-size:12.5px; cursor:pointer; border-radius:4px; }
    .do-picker-list label:hover { background:#f1f5f9; }
    .do-picker-list label input { margin:0; flex:none; }
    .do-picker-list .do-sub { color:#94a3b8; font-size:11px; }
    .do-help { font-size:12px; color:#64748b; }
    .do-badge-working { background:#16a34a; } .do-badge-holiday { background:#7c3aed; }
    .do-past td { opacity:0.6; }
</style>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-calendar"></i> Holidays &amp; Working Days (Staff)</h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo site_url('admin/staffattendance'); ?>" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Staff Attendance</a>
                        </div>
                    </div>
                    <div class="box-body" style="background:#f8fafc;">
                        <p class="do-help">
                            By default Sundays and annual-calendar holidays are off days. Use this page to make a date a <b>working day</b>
                            (e.g. staff called in on a Sunday) or a <b>holiday</b> (e.g. a local holiday) for <b>all staff</b>, <b>some roles</b> or <b>some staff</b>.
                            If two entries cover the same person, the more specific one wins: staff, then role, then all staff.
                            On a holiday, staff with no punch are marked Holiday (not Absent); anyone who does work is counted under <b>Holiday Work</b> in the monthly sheet.
                        </p>

                        <?php if ($can_edit) { ?>
                        <div class="do-card">
                            <form id="do-form" autocomplete="off">
                                <div class="do-form-grid">
                                    <div class="do-field">
                                        <label class="do-l">From date</label>
                                        <input type="text" name="from_date" class="form-control date" value="<?php echo $fmt(date('Y-m-d')); ?>" required>
                                    </div>
                                    <div class="do-field">
                                        <label class="do-l">To date</label>
                                        <input type="text" name="to_date" class="form-control date" placeholder="Same day">
                                    </div>
                                    <div class="do-field">
                                        <label class="do-l">Make it a</label>
                                        <div class="do-seg">
                                            <input type="radio" name="day_type" value="working" id="do-t-w" checked>
                                            <label for="do-t-w" class="do-w"><i class="fa fa-briefcase"></i> Working day</label>
                                            <input type="radio" name="day_type" value="holiday" id="do-t-h">
                                            <label for="do-t-h" class="do-h"><i class="fa fa-tree"></i> Holiday</label>
                                        </div>
                                    </div>
                                    <div class="do-field">
                                        <label class="do-l">Applies to</label>
                                        <select name="applies_to" id="do-applies" class="form-control">
                                            <option value="all">All staff</option>
                                            <option value="roles">Selected roles</option>
                                            <option value="staff">Selected staff</option>
                                        </select>
                                    </div>

                                    <div class="do-field do-span-4" id="do-roles-box" style="display:none;">
                                        <label class="do-l">Roles</label>
                                        <div class="do-picker">
                                            <div class="do-picker-bar">
                                                <span class="do-count" data-count-for="role_ids[]">0 selected</span>
                                                <button type="button" class="btn btn-default btn-xs do-all">Select all</button>
                                                <button type="button" class="btn btn-default btn-xs do-none">Clear</button>
                                            </div>
                                            <div class="do-picker-list">
                                                <?php foreach ($roles as $r) { ?>
                                                    <label><input type="checkbox" name="role_ids[]" value="<?php echo $r['id']; ?>"> <?php echo html_escape($r['name']); ?></label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="do-field do-span-4" id="do-staff-box" style="display:none;">
                                        <label class="do-l">Staff</label>
                                        <div class="do-picker">
                                            <div class="do-picker-bar">
                                                <input type="text" id="do-staff-filter" class="form-control input-sm" placeholder="Search by name, staff ID or role">
                                                <span class="do-count" data-count-for="staff_ids[]">0 selected</span>
                                                <button type="button" class="btn btn-default btn-xs do-all" title="Select every staff member shown">Select shown</button>
                                                <button type="button" class="btn btn-default btn-xs do-none">Clear</button>
                                            </div>
                                            <div class="do-picker-list" id="do-staff-list">
                                                <?php foreach ($staff_list as $s) {
                                                    $nm = trim($s['name'] . ' ' . $s['surname']); ?>
                                                    <label data-search="<?php echo html_escape(strtolower($nm . ' ' . $s['employee_id'] . ' ' . $s['role_name'])); ?>">
                                                        <input type="checkbox" name="staff_ids[]" value="<?php echo $s['id']; ?>">
                                                        <span><?php echo html_escape($nm); ?> <span class="do-sub"><?php echo html_escape($s['employee_id'] . ($s['role_name'] ? ' · ' . $s['role_name'] : '')); ?></span></span>
                                                    </label>
                                                <?php } ?>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="do-field do-span-3">
                                        <label class="do-l">Reason</label>
                                        <input type="text" name="reason" class="form-control" maxlength="255" placeholder="e.g. Annual function rehearsal, Exam duty, Local holiday">
                                    </div>
                                    <div class="do-field do-btn">
                                        <button type="submit" class="btn btn-primary btn-block" style="height:40px;"><i class="fa fa-save"></i> Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <?php } ?>

                        <div class="do-card">
                            <h4 style="margin-top:0; font-size:15px; font-weight:700;">Entries (last 60 days and upcoming)</h4>
                            <div class="table-responsive">
                                <table class="table table-bordered table-condensed" style="font-size:12.5px;">
                                    <thead><tr><th>Dates</th><th>Type</th><th>Applies to</th><th>Reason</th><th>Added by</th><?php if ($can_edit) { ?><th></th><?php } ?></tr></thead>
                                    <tbody>
                                    <?php if (empty($overrides)) { ?>
                                        <tr><td colspan="6" class="text-center text-muted">No entries yet.</td></tr>
                                    <?php } ?>
                                    <?php foreach ($overrides as $o) { $past = $o['to_date'] < date('Y-m-d'); ?>
                                        <tr class="<?php echo $past ? 'do-past' : ''; ?>">
                                            <td style="white-space:nowrap;">
                                                <?php echo $fmt($o['from_date']) . ' (' . date('D', strtotime($o['from_date'])) . ')'; ?>
                                                <?php if ($o['to_date'] !== $o['from_date']) { echo ' → ' . $fmt($o['to_date']) . ' (' . date('D', strtotime($o['to_date'])) . ')'; } ?>
                                            </td>
                                            <td><span class="label do-badge-<?php echo $o['day_type']; ?>"><?php echo $o['day_type'] === 'working' ? 'Working day' : 'Holiday'; ?></span></td>
                                            <td><?php echo html_escape($o['label']); ?></td>
                                            <td><?php echo html_escape($o['reason']); ?></td>
                                            <td><?php echo html_escape(trim($o['by_name'] . ' ' . $o['by_surname'])); ?><br><span class="text-muted" style="font-size:11px;"><?php echo date($date_fmt . ' h:i A', strtotime($o['created_at'])); ?></span></td>
                                            <?php if ($can_edit) { ?>
                                                <td class="text-right"><button type="button" class="btn btn-xs btn-danger do-del" data-id="<?php echo $o['id']; ?>"><i class="fa fa-trash"></i></button></td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
$(function () {
    $('#do-applies').on('change', function () {
        $('#do-roles-box').toggle(this.value === 'roles');
        $('#do-staff-box').toggle(this.value === 'staff');
    });
    function refreshCounts() {
        $('.do-count').each(function () {
            var n = $('input[name="' + $(this).data('count-for') + '"]:checked').length;
            $(this).text(n + ' selected');
        });
    }
    $('#do-staff-filter').on('input', function () {
        var q = this.value.toLowerCase().trim();
        $('#do-staff-list label').each(function () {
            var hit = !q || $(this).data('search').indexOf(q) !== -1 || $(this).find('input').prop('checked');
            $(this).toggle(hit);
        });
    });
    $('.do-picker').on('change', 'input[type=checkbox]', refreshCounts);
    $('.do-picker').on('click', '.do-all', function () {
        $(this).closest('.do-picker').find('.do-picker-list label:visible input').prop('checked', true);
        refreshCounts();
    });
    $('.do-picker').on('click', '.do-none', function () {
        $(this).closest('.do-picker').find('.do-picker-list input').prop('checked', false);
        refreshCounts();
    });
    $('#do-form').on('submit', function (e) {
        e.preventDefault();
        var $b = $(this).find('button[type=submit]').prop('disabled', true);
        $.post('<?php echo site_url('admin/staffattendance/savedayoverride'); ?>', $(this).serialize(), function (res) {
            if (res.status == 1) { successMsg(res.message); setTimeout(function () { location.reload(); }, 800); }
            else { errorMsg(res.message); $b.prop('disabled', false); }
        }, 'json').fail(function () { errorMsg('Could not save. Please try again.'); $b.prop('disabled', false); });
    });
    $('.do-del').on('click', function () {
        if (!confirm('Remove this entry? Attendance records it changed (Absent/Holiday) will be switched back.')) return;
        $.post('<?php echo site_url('admin/staffattendance/deletedayoverride'); ?>', { id: $(this).data('id') }, function (res) {
            if (res.status == 1) { successMsg(res.message); setTimeout(function () { location.reload(); }, 800); }
            else { errorMsg(res.message); }
        }, 'json');
    });
});
</script>
