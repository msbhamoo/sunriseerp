<?php
$date_fmt = $this->customlib->getSchoolDateFormat();
$fmt_date = function ($ymd) use ($date_fmt) { return $ymd ? date($date_fmt, strtotime($ymd)) : ''; };

// Human-readable value for a tracked column.
$show_val = function ($field, $val) use ($type_names, $shift_names) {
    if ($val === null || $val === '') { return '<span class="text-muted">empty</span>'; }
    if ($field === 'staff_attendance_type_id' && isset($type_names[$val])) { return html_escape($type_names[$val]); }
    if ($field === 'shift_id' && isset($shift_names[$val])) { return html_escape($shift_names[$val]) . ' <span class="text-muted">#' . (int) $val . '</span>'; }
    if (is_array($val)) { return html_escape(json_encode($val)); }
    return html_escape((string) $val);
};
$field_label = array(
    'staff_attendance_type_id' => 'Status',
    'in_time'                  => 'In time',
    'out_time'                 => 'Out time',
    'remark'                   => 'Remark',
    'shift_id'                 => 'Shift',
    'attendance_source'        => 'Source',
    'biometric_attendence'     => 'Biometric',
    'qrcode_attendance'        => 'QR',
    'date'                     => 'Date',
    'staff_id'                 => 'Staff',
    'is_active'                => 'Active',
);
$label = function ($f) use ($field_label) { return isset($field_label[$f]) ? $field_label[$f] : ucwords(str_replace('_', ' ', $f)); };
$action_badge = array(
    'INSERT' => 'label-success',
    'UPDATE' => 'label-warning',
    'DELETE' => 'label-danger',
);
$qs = $_GET; unset($qs['page']);
$pages = max(1, (int) ceil($total / $per_page));
?>
<style>
    .al-change { margin:0; padding:0; list-style:none; font-size:12px; }
    .al-change li { padding:1px 0; }
    .al-change .f { font-weight:700; color:#334155; }
    .al-old { color:#b91c1c; text-decoration:line-through; }
    .al-new { color:#15803d; font-weight:600; }
    .al-table td { vertical-align:top !important; font-size:12.5px; }
</style>
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-history"></i> Staff Attendance Change Log</h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo site_url('admin/staffattendance'); ?>" class="btn btn-default btn-sm"><i class="fa fa-arrow-left"></i> Staff Attendance</a>
                        </div>
                    </div>
                    <div class="box-body">
                        <?php if (!$triggers_ok) { ?>
                            <div class="alert alert-danger">
                                <strong>Automatic logging is not active.</strong> The database user lacks permission to create triggers,
                                so attendance changes are not being recorded. Grant the <code>TRIGGER</code> privilege to the database user
                                (and on servers with binary logging, enable <code>log_bin_trust_function_creators</code>), then reload this page.
                            </div>
                        <?php } ?>

                        <form method="get" action="<?php echo site_url('admin/staffattendance/auditlog'); ?>" class="row" style="margin-bottom:12px;">
                            <div class="col-sm-2">
                                <label>Changed from</label>
                                <input type="text" name="from" class="form-control date" value="<?php echo $fmt_date($filters['from']); ?>">
                            </div>
                            <div class="col-sm-2">
                                <label>Changed to</label>
                                <input type="text" name="to" class="form-control date" value="<?php echo $fmt_date($filters['to']); ?>">
                            </div>
                            <div class="col-sm-2">
                                <label>Attendance date</label>
                                <input type="text" name="att_date" class="form-control date" value="<?php echo $fmt_date($filters['att_date']); ?>" placeholder="Any">
                            </div>
                            <div class="col-sm-2">
                                <label>Staff</label>
                                <select name="staff_id" class="form-control">
                                    <option value="">All staff</option>
                                    <?php foreach ($staff_list as $s) { ?>
                                        <option value="<?php echo $s['id']; ?>" <?php echo (int) $filters['staff_id'] === (int) $s['id'] ? 'selected' : ''; ?>>
                                            <?php echo html_escape(trim($s['name'] . ' ' . $s['surname']) . ' (' . $s['employee_id'] . ')'); ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-1">
                                <label>Action</label>
                                <select name="action" class="form-control">
                                    <option value="">All</option>
                                    <?php foreach (array('INSERT' => 'Created', 'UPDATE' => 'Changed', 'DELETE' => 'Deleted', 'SHIFT' => 'Shift settings', 'DAY' => 'Holiday / working day', 'POLICY' => 'Late policy') as $k => $v) { ?>
                                        <option value="<?php echo $k; ?>" <?php echo $filters['action'] === $k ? 'selected' : ''; ?>><?php echo $v; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-1">
                                <label>Source</label>
                                <select name="source" class="form-control">
                                    <option value="">All</option>
                                    <?php foreach ($sources as $src) { ?>
                                        <option value="<?php echo html_escape($src); ?>" <?php echo $filters['source'] === $src ? 'selected' : ''; ?>><?php echo html_escape(ucfirst($src)); ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-search"></i> Search</button>
                            </div>
                        </form>

                        <p class="text-muted" style="font-size:12px;"><?php echo (int) $total; ?> change(s) found.</p>

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover al-table">
                                <thead>
                                    <tr>
                                        <th style="width:140px;">When</th>
                                        <th>Changed by</th>
                                        <th>Source</th>
                                        <th>Staff</th>
                                        <th>Attendance date</th>
                                        <th>Action</th>
                                        <th>What changed</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if (empty($rows)) { ?>
                                    <tr><td colspan="7" class="text-center text-muted">No changes in this period.</td></tr>
                                <?php } ?>
                                <?php foreach ($rows as $r) {
                                    $old = $r['old_values'] ? json_decode($r['old_values'], true) : array();
                                    $new = $r['new_values'] ? json_decode($r['new_values'], true) : array();
                                    $is_day    = strpos($r['action'], 'DAY_') === 0;
                                    $is_policy = strpos($r['action'], 'POLICY_') === 0;
                                    $is_shift  = strpos($r['action'], 'SHIFT') === 0 || $is_day || $is_policy;
                                    $by = trim($r['by_name'] . ' ' . $r['by_surname']);
                                    if ($by === '') { $by = $r['changed_by_name']; }
                                ?>
                                    <tr>
                                        <td><?php echo date($date_fmt . ' h:i:s A', strtotime($r['created_at'])); ?></td>
                                        <td>
                                            <?php echo $by !== '' && $by !== null ? html_escape($by) : '<span class="text-muted">System / outside app</span>'; ?>
                                            <?php if (!empty($r['ip_address'])) { ?><br><span class="text-muted" style="font-size:11px;"><?php echo html_escape($r['ip_address']); ?></span><?php } ?>
                                        </td>
                                        <td>
                                            <?php echo html_escape(ucfirst((string) $r['source'])); ?>
                                            <?php if (!empty($r['url'])) { ?><br><span class="text-muted" style="font-size:11px;"><?php echo html_escape($r['url']); ?></span><?php } ?>
                                        </td>
                                        <td>
                                            <?php if ($is_shift) { ?>—<?php } else { ?>
                                                <?php echo html_escape(trim($r['staff_name'] . ' ' . $r['staff_surname'])); ?>
                                                <br><span class="text-muted" style="font-size:11px;"><?php echo html_escape($r['employee_id']); ?></span>
                                            <?php } ?>
                                        </td>
                                        <td><?php echo $fmt_date($r['att_date']); ?></td>
                                        <td>
                                            <?php if ($is_shift) { ?>
                                                <span class="label" style="background:#8b5cf6;"><?php echo html_escape(ucwords(strtolower(str_replace('_', ' ', $r['action'])))); ?></span>
                                            <?php } else { ?>
                                                <span class="label <?php echo isset($action_badge[$r['action']]) ? $action_badge[$r['action']] : 'label-default'; ?>">
                                                    <?php echo array('INSERT' => 'Created', 'UPDATE' => 'Changed', 'DELETE' => 'Deleted')[$r['action']] ?? html_escape($r['action']); ?>
                                                </span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if ($is_policy) {
                                                $labels = array('late_enabled' => 'Policy on', 'late_free' => 'Free lates / month', 'late_every' => 'Every N lates', 'late_penalty' => 'Days deducted');
                                            ?>
                                                <ul class="al-change">
                                                    <?php foreach ($labels as $f => $lbl) {
                                                        $ov = isset($old[$f]) ? $old[$f] : null; $nv = isset($new[$f]) ? $new[$f] : null;
                                                        if ((string) $ov === (string) $nv) { continue; } ?>
                                                        <li><span class="f"><?php echo $lbl; ?>:</span> <span class="al-old"><?php echo html_escape((string) $ov); ?></span> → <span class="al-new"><?php echo html_escape((string) $nv); ?></span></li>
                                                    <?php } ?>
                                                </ul>
                                            <?php } elseif ($is_day) {
                                                $o = $new && isset($new['day_type']) ? $new : (array) $old;
                                            ?>
                                                <ul class="al-change">
                                                    <li><span class="f"><?php echo ucfirst(html_escape(isset($o['day_type']) ? $o['day_type'] : '')); ?> day:</span>
                                                        <?php echo $fmt_date(isset($o['from_date']) ? $o['from_date'] : null); ?><?php if (!empty($o['to_date']) && $o['to_date'] !== $o['from_date']) { echo ' → ' . $fmt_date($o['to_date']); } ?></li>
                                                    <li><span class="f">Applies to:</span> <?php echo html_escape(isset($o['applies_to']) ? $o['applies_to'] : ''); ?><?php echo !empty($o['target_ids']) ? ' (ids ' . html_escape($o['target_ids']) . ')' : ''; ?></li>
                                                    <?php if (!empty($o['reason'])) { ?><li><span class="f">Reason:</span> <?php echo html_escape($o['reason']); ?></li><?php } ?>
                                                    <?php if (isset($new['records_updated'])) { ?><li><span class="f">Attendance records updated:</span> <?php echo (int) $new['records_updated']; ?></li><?php } ?>
                                                </ul>
                                            <?php } elseif ($is_shift) {
                                                $sh = isset($new['shift']) ? $new['shift'] : $old;
                                            ?>
                                                <ul class="al-change">
                                                    <li><span class="f">Shift:</span> <?php echo html_escape(isset($sh['name']) ? $sh['name'] : $r['changed_fields']); ?>
                                                        <?php if (!empty($sh['shift_type'])) { ?>(<?php echo html_escape($sh['shift_type']); ?><?php if ($sh['shift_type'] === 'temporary') { echo ', ' . $fmt_date($sh['effective_from']) . ' → ' . $fmt_date($sh['effective_to']); } ?>)<?php } ?>
                                                    </li>
                                                    <?php if (!empty($new['replaced'])) { ?>
                                                        <li><span class="f">Replaced active shift:</span>
                                                            <?php echo html_escape(implode(', ', array_map(function ($id) use ($shift_names) { return isset($shift_names[$id]) ? $shift_names[$id] : '#' . $id; }, $new['replaced']))); ?>
                                                        </li>
                                                    <?php } ?>
                                                    <?php if (isset($new['band_count'])) { ?><li><span class="f">Timing rows saved:</span> <?php echo (int) $new['band_count']; ?></li><?php } ?>
                                                </ul>
                                            <?php } elseif ($r['action'] === 'UPDATE') { ?>
                                                <ul class="al-change">
                                                    <?php foreach (array_filter(explode(',', (string) $r['changed_fields'])) as $f) { ?>
                                                        <li><span class="f"><?php echo html_escape($label($f)); ?>:</span>
                                                            <span class="al-old"><?php echo $show_val($f, isset($old[$f]) ? $old[$f] : null); ?></span>
                                                            → <span class="al-new"><?php echo $show_val($f, isset($new[$f]) ? $new[$f] : null); ?></span>
                                                        </li>
                                                    <?php } ?>
                                                </ul>
                                            <?php } else {
                                                $vals = $r['action'] === 'DELETE' ? $old : $new;
                                            ?>
                                                <ul class="al-change">
                                                    <?php foreach (array('staff_attendance_type_id', 'in_time', 'out_time', 'shift_id', 'attendance_source', 'remark') as $f) {
                                                        if (!array_key_exists($f, (array) $vals)) { continue; } ?>
                                                        <li><span class="f"><?php echo html_escape($label($f)); ?>:</span> <?php echo $show_val($f, $vals[$f]); ?></li>
                                                    <?php } ?>
                                                </ul>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>

                        <?php if ($pages > 1) { ?>
                            <ul class="pagination pagination-sm">
                                <?php for ($p = max(1, $page - 4); $p <= min($pages, $page + 4); $p++) { $qs['page'] = $p; ?>
                                    <li class="<?php echo $p === $page ? 'active' : ''; ?>"><a href="<?php echo site_url('admin/staffattendance/auditlog') . '?' . http_build_query($qs); ?>"><?php echo $p; ?></a></li>
                                <?php } ?>
                            </ul>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
