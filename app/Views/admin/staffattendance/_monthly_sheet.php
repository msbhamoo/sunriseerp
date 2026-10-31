<?php
// One entry per attendance status configured in Settings (staff_attendance_type), keyed the same
// way as getMonthlySheet(): long_lang_name, or the lower-cased key when that is empty.
// Code = the status key (P, L, F, SH, ...); colour from the palette below, grey for new statuses.
$palette = array(
    'present'               => '#16a34a',
    'late'                  => '#f59e0b',
    'absent'                => '#dc2626',
    'half_day'              => '#2563eb',
    'holiday'               => '#7c3aed',
    'half_day_second_shift' => '#0d9488',
    'unplanned_leave'       => '#db2777',
    'on_duty'               => '#0891b2',
    'leave'                 => '#0369a1',
);
// Share of a working day a status counts as attended (for required-hours / shortfall).
$attend_weight = array('present' => 1, 'late' => 1, 'on_duty' => 1, 'half_day' => 0.5, 'half_day_second_shift' => 0.5);
$meta = array(); // name => [code, colour, label]
foreach ((array) $type_list as $t) {
    $t    = (array) $t;
    $key  = strtolower(trim(strip_tags($t['key_value'])));
    $name = !empty($t['long_lang_name']) ? $t['long_lang_name'] : $key;
    $code = strtoupper($key === 'on_duty' ? 'OD' : $key);
    $meta[$name] = array($code, isset($palette[$name]) ? $palette[$name] : '#64748b', !empty($t['type']) ? $t['type'] : ucwords(str_replace('_', ' ', $name)));
}
if (!isset($meta['on_duty'])) { // duty passes are shown as On Duty even if that status is inactive
    $meta['on_duty'] = array('OD', $palette['on_duty'], 'On Duty');
}

// Total badge that opens a day-wise list (hover or click; the list scrolls). $records: formatted, line, badge.
$ms_detail_btn = function ($title, $label, $sub, $records, $kind) {
    $h  = '<button type="button" class="ms-detail-btn ms-k-' . $kind . '" data-title="' . html_escape($title) . '">'
        . html_escape($label) . ' <span class="ms-sub">(' . html_escape($sub) . ' <i class="fa fa-info-circle"></i>)</span></button>';
    $h .= '<div class="ms-detail-src" hidden><ul class="ms-dl">';
    foreach ($records as $r) {
        $h .= '<li><div><b>' . html_escape($r['formatted']) . '</b><br><span>' . html_escape($r['line']) . '</span></div>'
            . '<span class="ms-dl-badge">' . html_escape($r['badge']) . '</span></li>';
    }
    if (empty($records)) {
        $h .= '<li><span>No punch details for these days.</span></li>';
    }
    return $h . '</ul></div>';
};
$monthName = date('F Y', mktime(0, 0, 0, $month, 1, $year));
$staff = $sheet['staff'];
$map   = $sheet['map'];
$map_times = isset($sheet['map_times']) ? $sheet['map_times'] : array();
$map_duty_passes = isset($sheet['map_duty_passes']) ? $sheet['map_duty_passes'] : array();
$role_req_map = isset($role_required_hours) ? $role_required_hours : array();
$map_shift = isset($sheet['map_shift']) ? $sheet['map_shift'] : array();
$shift_sched_map = isset($shift_schedules_map) ? $shift_schedules_map : array();
?>
<style>
    .ms-tiles { display:flex; flex-wrap:wrap; gap:12px; margin-bottom:14px; }
    .ms-tile { background:#f7f9fb; border-radius:8px; padding:10px 16px; min-width:120px; }
    .ms-tile .v { font-size:22px; font-weight:600; color:#1f2937; line-height:1.1; }
    .ms-tile .l { font-size:12px; color:#6b7684; margin-top:2px; }
    .ms-tile.hol .v { color:#7c3aed; }
    .ms-tile.work .v { color:#16a34a; }
    .ms-wrap { width:100%; max-width:100%; max-height:66vh; overflow:auto; border:1px solid #e6ebf1; border-radius:8px; }
    table.ms-table { border-collapse:collapse; font-size:12px; width:100%; }
    table.ms-table th, table.ms-table td { border:1px solid #eef2f6; padding:4px 3px; text-align:center; white-space:nowrap; }
    table.ms-table thead th { background:#f7f9fb; color:#55606d; position:sticky; top:0; }
    table.ms-table th.ms-name, table.ms-table td.ms-name { position:sticky; left:0; background:#fff; text-align:left; min-width:150px; z-index:2; font-weight:500; }
    table.ms-table thead th.ms-name { z-index:3; background:#f7f9fb; }
    table.ms-table th.ms-hol { background:#f3e8ff; color:#7c3aed; }
    table.ms-table td.ms-hol { background:#faf5ff; }
    .ms-cell { display:inline-block; min-width:18px; padding:1px 4px; border-radius:4px; color:#fff; font-weight:600; font-size:11px; }
    table.ms-table td.ms-tot { font-weight:600; background:#fafbfc; }
    .ms-legend { display:flex; flex-wrap:wrap; gap:10px; margin:10px 0; font-size:11.5px; color:#6b7684; }
    .ms-legend .li { display:inline-flex; align-items:center; gap:4px; }
    .ms-legend .sw { width:14px; height:14px; border-radius:3px; display:inline-block; }
    .ms-badge-ok { background:#dcfce7; color:#15803d; padding:2px 6px; border-radius:4px; font-weight:700; font-size:11px; }
    .ms-badge-short { background:#fee2e2; color:#b91c1c; padding:2px 6px; border-radius:4px; font-weight:700; font-size:11px; }
    .ms-early-cell { position:relative; }
    .ms-early-dot { position:absolute; bottom:1px; right:1px; width:5px; height:5px; background:#dc2626; border-radius:50%; }
    .ms-holwork-dot { position:absolute; top:1px; right:1px; width:6px; height:6px; border:1.5px solid #7c3aed; border-radius:50%; }
    td.ms-hol { position:relative; }
    .ms-detail-btn { border:none; border-radius:12px; padding:2px 8px; font-size:11px; font-weight:700; color:#fff; cursor:pointer; white-space:nowrap; }
    .ms-detail-btn .ms-sub { font-size:9.5px; opacity:0.9; }
    .ms-k-early { background:#dc2626; } .ms-k-ot { background:#16a34a; } .ms-k-hol { background:#7c3aed; }
    #ms-detail-panel { position:fixed; z-index:100000; width:330px; background:#fff; border:1px solid #e2e8f0; border-radius:8px;
        box-shadow:0 10px 25px -5px rgba(0,0,0,0.2); display:none; }
    #ms-detail-panel .ms-dp-head { padding:9px 12px; font-weight:700; font-size:13px; border-bottom:1px solid #e2e8f0; border-radius:8px 8px 0 0;
        display:flex; justify-content:space-between; align-items:center; gap:8px; }
    #ms-detail-panel .ms-dp-close { border:none; background:none; font-size:16px; line-height:1; color:#64748b; cursor:pointer; }
    #ms-detail-panel .ms-dp-body { max-height:300px; overflow-y:auto; overscroll-behavior:contain; padding:4px 12px; }
    .ms-dl { list-style:none; margin:0; padding:0; font-size:12px; color:#1e293b; text-align:left; }
    .ms-dl li { display:flex; justify-content:space-between; align-items:center; gap:8px; padding:6px 0; border-bottom:1px solid #f1f5f9; }
    .ms-dl li span { font-size:11px; color:#64748b; }
    .ms-dl .ms-dl-badge { font-weight:700; font-size:11px; padding:2px 7px; border-radius:10px; white-space:nowrap; }
    .ms-p-early .ms-dp-head { background:#fef2f2; color:#991b1b; } .ms-p-early .ms-dl-badge { background:#fee2e2; color:#b91c1c; }
    .ms-p-ot .ms-dp-head { background:#f0fdf4; color:#166534; } .ms-p-ot .ms-dl-badge { background:#dcfce7; color:#15803d; }
    .ms-p-hol .ms-dp-head { background:#faf5ff; color:#6b21a8; } .ms-p-hol .ms-dl-badge { background:#f3e8ff; color:#7c3aed; }
    
    /* Popover Clean Theme Overrides */
    .popover { 
        background: #ffffff !important; 
        max-width: 340px !important; 
        border-radius: 8px !important; 
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.2), 0 8px 10px -6px rgba(0,0,0,0.1) !important; 
        border: 1px solid #e2e8f0 !important; 
        padding: 0 !important;
        z-index: 99999 !important;
    }
    .popover.left > .arrow:after {
        border-left-color: #ffffff !important;
    }
    .popover.right > .arrow:after {
        border-right-color: #ffffff !important;
    }
    .popover.top > .arrow:after {
        border-top-color: #ffffff !important;
    }
    .popover.bottom > .arrow:after {
        border-bottom-color: #f8fafc !important;
    }
    .popover-title { 
        background: #fef2f2 !important; 
        font-weight: 700 !important; 
        font-size: 13px !important; 
        color: #991b1b !important; 
        border-bottom: 1px solid #fee2e2 !important; 
        padding: 10px 14px !important; 
        border-top-left-radius: 7px !important; 
        border-top-right-radius: 7px !important; 
    }
    .popover-content { 
        padding: 12px 14px !important; 
        background: #ffffff !important; 
        color: #1e293b !important;
        border-bottom-left-radius: 7px !important;
        border-bottom-right-radius: 7px !important;
    }
</style>

<div class="ms-tiles">
    <div class="ms-tile"><div class="v"><?php echo count($staff); ?></div><div class="l">Staff</div></div>
    <div class="ms-tile"><div class="v"><?php echo $month_days; ?></div><div class="l">Month Days</div></div>
    <div class="ms-tile work"><div class="v"><?php echo $working_days; ?></div><div class="l">Working Days</div></div>
    <div class="ms-tile hol"><div class="v"><?php echo $holidays; ?></div><div class="l">Holidays (Sun + calendar)</div></div>
    <div class="ms-tile" style="margin-left:auto;"><div class="v" style="font-size:16px;"><?php echo $monthName; ?></div><div class="l">Showing</div></div>
</div>

<div class="ms-legend">
    <?php foreach ($meta as $k => $mc) { ?>
        <span class="li"><span class="ms-cell" style="background:<?php echo $mc[1]; ?>;"><?php echo html_escape($mc[0]); ?></span> <?php echo html_escape($mc[2]); ?></span>
    <?php } ?>
    <span class="li"><span class="sw" style="background:#f3e8ff;border:1px solid #d8b4fe;"></span> Holiday column</span>
    <span class="li"><span style="display:inline-block;width:8px;height:8px;background:#dc2626;border-radius:50%;margin-right:2px;"></span> Red dot = Left Early</span>
    <span class="li"><span style="display:inline-block;width:8px;height:8px;border:1.5px solid #7c3aed;border-radius:50%;margin-right:2px;"></span> Purple ring = Worked on a holiday</span>
    <?php if (!empty($late_policy_on)) { ?><span class="li" style="color:#b45309;"><i class="fa fa-hourglass-end"></i> <?php echo html_escape($late_policy_text); ?></span><?php } ?>
    <span class="li" style="margin-left:auto;"><span class="ms-badge-short">-XXh Shortfall</span> = Under Required Work Hours</span>
</div>

<?php if (empty($staff)) { ?>
    <div class="alert alert-info">No active staff found for this role.</div>
<?php } else { ?>
<div class="ms-wrap">
    <table class="ms-table">
        <thead>
            <tr>
                <th class="ms-name">Staff</th>
                <th style="min-width:90px;">Role</th>
                <?php for ($d = 1; $d <= $days_in_month; $d++) {
                    $ds = sprintf('%04d-%02d-%02d', $year, $month, $d);
                    $isHol = isset($holiday_dates[$ds]);
                    $dow = date('D', strtotime($ds));
                ?>
                    <th class="<?php echo $isHol ? 'ms-hol' : ''; ?>" title="<?php echo $ds . ' (' . $dow . ')'; ?>">
                        <?php echo $d; ?><br><span style="font-size:9px;font-weight:400;"><?php echo substr($dow, 0, 2); ?></span>
                    </th>
                <?php } ?>
                <?php foreach ($meta as $k => $mc) { ?>
                    <th class="ms-tot" title="<?php echo html_escape($mc[2]); ?> days<?php echo $k === 'holiday' ? ' (incl. unmarked Sundays / calendar holidays)' : ''; ?>" style="color:<?php echo $mc[1]; ?>;"><?php echo html_escape($mc[0]); ?></th>
                <?php } ?>
                <th class="ms-tot" title="Working days with no attendance marked (not a Sunday or calendar holiday, up to today)" style="color:#64748b;">Not marked</th>
                <th class="ms-tot" title="Days counted as attended: Present / Late / On Duty = 1, Half Day = 0.5" style="color:#0f172a;">Attended</th>
                <th class="ms-tot" title="Staff compliance points followed (Uniform, ID Card, Lesson Plan, Phone Handover) out of 4 per day x days in the month" style="color:#0f766e; background:#f0fdfa; min-width:80px;">Compliance</th>
                <?php if (!empty($late_policy_on)) { ?>
                    <th class="ms-tot" title="<?php echo html_escape($late_policy_text); ?>" style="color:#b45309; background:#fffbeb;">Late Ded.</th>
                <?php } ?>
                <th class="ms-tot" title="Early Check-out Duration (Total hours and mins left before required school exit time)" style="color:#b91c1c; background:#fef2f2; min-width:85px;">Early Left</th>
                <th class="ms-tot" title="Overtime Done (Hours and days beyond scheduled institute hours)" style="color:#15803d; background:#f0fdf4; min-width:85px;">Overtime</th>
                <th class="ms-tot" title="Days worked on a Sunday or calendar holiday (for comp-off / extra pay). Not counted in Attended or Shortfall." style="color:#7c3aed; background:#faf5ff; min-width:85px;">Holiday Work</th>
                <th class="ms-tot" title="Total Actual Worked Hours (Sum of all In-Out punches)" style="min-width:75px;">Worked</th>
                <th class="ms-tot" title="Monthly Shortfall or Surplus based on Role Required Hours" style="min-width:95px;">Shortfall / +/-</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $role_sched_map = isset($role_schedules_map) ? $role_schedules_map : array();
            foreach ($staff as $s) {
                $sid = $s['staff_id'];
                $status_count = array_fill_keys(array_keys($meta), 0);
                $not_marked   = 0;
                $tot = array(
                    'early_out'      => 0,
                    'early_out_secs' => 0,
                    'overtime'       => 0
                );
                $total_worked_secs = 0;
                $total_overtime_secs = 0;
                $attended_days_count = 0;
                $early_out_records = array();
                $overtime_records  = array();
                $holiday_work_days = 0;
                $holiday_work_secs = 0;
                $holiday_work_records = array();

                $roleId = isset($s['role_id']) ? $s['role_id'] : 0;
                $reqDayStr = isset($role_req_map[$roleId]) ? $role_req_map[$roleId] : '08:00:00';
                $reqDayParts = explode(':', $reqDayStr);
                $reqDaySecs = ((int)$reqDayParts[0] * 3600) + ((isset($reqDayParts[1]) ? (int)$reqDayParts[1] : 0) * 60);
                if ($reqDaySecs <= 0) { $reqDaySecs = 8 * 3600; }

                $dayEndScheduled = (isset($role_sched_map[$roleId]['dayend_time']) && !empty($role_sched_map[$roleId]['dayend_time'])) ? $role_sched_map[$roleId]['dayend_time'] : null;
                $dayEndScheduledSecs = $dayEndScheduled ? strtotime("1970-01-01 " . $dayEndScheduled . " UTC") : null;
            ?>
                <tr>
                    <td class="ms-name"><?php echo htmlspecialchars($s['name']); ?><br><span style="font-size:10px;color:#9aa6b4;"><?php echo htmlspecialchars($s['employee_id']); ?></span></td>
                    <td style="text-align:left;color:#6b7684;"><?php echo htmlspecialchars($s['role_name']); ?></td>
                    <?php for ($d = 1; $d <= $days_in_month; $d++) {
                        $ds = sprintf('%04d-%02d-%02d', $year, $month, $d);
                        // Off day for this person (school-wide days adjusted by Holidays & Working Days entries).
                        $isHol = isset($staff_off_map) ? isset($staff_off_map[$sid][$ds]) : isset($holiday_dates[$ds]);
                        $type = isset($map[$sid][$ds]) ? $map[$sid][$ds] : null;

                        // Worked on a Sunday / holiday: counted apart (comp-off / extra pay), not as a normal working day.
                        $isHolidayWork = $isHol && $type !== null && !empty($attend_weight[$type]);
                        if ($type !== null && isset($status_count[$type])) {
                            $status_count[$type]++;
                            if ($isHolidayWork) {
                                $holiday_work_days += $attend_weight[$type];
                            } else {
                                $attended_days_count += isset($attend_weight[$type]) ? $attend_weight[$type] : 0;
                            }
                        } elseif ($type === null && $isHol && isset($status_count['holiday'])) {
                            $status_count['holiday']++; // unmarked Sunday / calendar holiday
                        } elseif ($type === null && !$isHol && $ds <= date('Y-m-d')) {
                            $not_marked++;
                        }

                        // Check punches for duration, early departure, and overtime
                        $day_hover = '';
                        $isEarlyLeft = false;
                        $isOvertime = false;

                        if (isset($map_duty_passes[$sid][$ds])) {
                            $dp = $map_duty_passes[$sid][$ds];
                            $day_hover = "On Duty: " . $dp['duty_title'] . (!empty($dp['venue']) ? " @ " . $dp['venue'] : "") . " (" . $dp['duty_pass_no'] . ")";
                        }
                        if (isset($map_times[$sid][$ds])) {
                            // Day end of the shift this day was recorded under; fall back to the current shift.
                            $dayShift = isset($map_shift[$sid][$ds]) ? $map_shift[$sid][$ds] : null;
                            if ($dayShift && !empty($shift_sched_map[$dayShift][$roleId]['dayend_time'])) {
                                $dayEndScheduledSecs = strtotime("1970-01-01 " . $shift_sched_map[$dayShift][$roleId]['dayend_time'] . " UTC");
                            } else {
                                $dayEndScheduledSecs = $dayEndScheduled ? strtotime("1970-01-01 " . $dayEndScheduled . " UTC") : null;
                            }
                            $inTimeStr = $map_times[$sid][$ds]['in'];
                            $outTimeStr = $map_times[$sid][$ds]['out'];
                            $inS = strtotime("1970-01-01 " . $inTimeStr . " UTC");
                            $outS = strtotime("1970-01-01 " . $outTimeStr . " UTC");
                            if ($outS > $inS && $isHolidayWork) {
                                // Holiday work: all hours are extra; no early-left / overtime checks.
                                $diff = $outS - $inS;
                                $holiday_work_secs += $diff;
                                $time_info = "Worked on holiday | In: " . date('h:i A', $inS) . " | Out: " . date('h:i A', $outS) . " (" . intdiv($diff, 3600) . "h " . intdiv($diff % 3600, 60) . "m)";
                                $holiday_work_records[] = array(
                                    'formatted' => date('d M (D)', strtotime($ds)),
                                    'line'      => 'In ' . date('h:i A', $inS) . ' – Out ' . date('h:i A', $outS),
                                    'badge'     => intdiv($diff, 3600) . 'h ' . intdiv($diff % 3600, 60) . 'm',
                                );
                                $day_hover = !empty($day_hover) ? ($day_hover . " | " . $time_info) : $time_info;
                            } elseif ($outS > $inS) {
                                $diff = $outS - $inS;
                                $total_worked_secs += $diff;
                                $dh = intdiv($diff, 3600);
                                $dm = intdiv($diff % 3600, 60);
                                $time_info = "In: " . date('h:i A', $inS) . " | Out: " . date('h:i A', $outS) . " (" . $dh . "h " . $dm . "m)";

                                // Early exit check against scheduled school end time (in hours & mins)
                                if ($dayEndScheduledSecs && $outS < $dayEndScheduledSecs && !in_array($type, ['half_day', 'half_day_second_shift'])) {
                                    $earlyGap = $dayEndScheduledSecs - $outS;
                                    $tot['early_out']++;
                                    $tot['early_out_secs'] += $earlyGap;
                                    $isEarlyLeft = true;
                                    $egH = intdiv($earlyGap, 3600);
                                    $egM = intdiv($earlyGap % 3600, 60);
                                    $gapStr = ($egH > 0 ? $egH . 'h ' : '') . $egM . 'm';
                                    $time_info .= " [Early Left: -" . $gapStr . "]";
                                    
                                    $early_out_records[] = array(
                                        'date'       => $ds,
                                        'formatted'  => date('d M (D)', strtotime($ds)),
                                        'out_time'   => date('h:i A', $outS),
                                        'scheduled'  => date('h:i A', $dayEndScheduledSecs),
                                        'gap'        => '-' . $gapStr,
                                        'in_time'    => date('h:i A', $inS)
                                    );
                                }

                                // Overtime check: worked more than required day hours (+15 min margin)
                                if ($diff > ($reqDaySecs + 900)) {
                                    $tot['overtime']++;
                                    $isOvertime = true;
                                    $otDiff = $diff - $reqDaySecs;
                                    $total_overtime_secs += $otDiff;
                                    $overtime_records[] = array(
                                        'formatted' => date('d M (D)', strtotime($ds)),
                                        'line'      => 'In ' . date('h:i A', $inS) . ' – Out ' . date('h:i A', $outS) . ' (worked ' . $dh . 'h ' . $dm . 'm, req ' . intdiv($reqDaySecs, 3600) . 'h ' . intdiv($reqDaySecs % 3600, 60) . 'm)',
                                        'badge'     => '+' . (intdiv($otDiff, 3600) > 0 ? intdiv($otDiff, 3600) . 'h ' : '') . intdiv($otDiff % 3600, 60) . 'm',
                                    );
                                    $time_info .= " [Overtime: +" . intdiv($otDiff, 3600) . "h " . intdiv($otDiff % 3600, 60) . "m]";
                                }

                                $day_hover = !empty($day_hover) ? ($day_hover . " | " . $time_info) : $time_info;
                            }
                        }
                    ?>
                        <td class="<?php echo $isHol ? 'ms-hol' : ''; ?> <?php echo $isEarlyLeft ? 'ms-early-cell' : ''; ?>" title="<?php $lbl = ($type && isset($meta[$type])) ? $meta[$type][2] : ($type ? ucfirst(str_replace('_', ' ', $type)) : ''); echo htmlspecialchars(trim($lbl . (!empty($day_hover) ? ' | ' . $day_hover : ''), ' |')); ?>">
                            <?php if ($type !== null && isset($meta[$type])) {
                                echo '<span class="ms-cell" style="background:' . $meta[$type][1] . ';">' . html_escape($meta[$type][0]) . '</span>';
                                if ($isEarlyLeft) {
                                    echo '<span class="ms-early-dot" title="Left early on this day"></span>';
                                }
                                if ($isHolidayWork) {
                                    echo '<span class="ms-holwork-dot" title="Worked on a holiday"></span>';
                                }
                            } elseif ($isHol) {
                                echo '<span style="color:#c084fc;">&bull;</span>';
                            } else {
                                echo '<span style="color:#cbd5e1;">&middot;</span>';
                            } ?>
                        </td>
                    <?php } ?>
                    <?php foreach ($meta as $k => $mc) { ?>
                        <td class="ms-tot" style="color:<?php echo $mc[1]; ?>; font-weight:700;"><?php echo $status_count[$k]; ?></td>
                    <?php } ?>
                    <td class="ms-tot" style="color:#64748b;"><?php echo $not_marked; ?></td>
                    <td class="ms-tot" style="font-weight:700;"><?php echo rtrim(rtrim(number_format($attended_days_count, 1), '0'), '.'); ?></td>
                    <?php $c_followed = isset($compliance_map[$sid]) ? $compliance_map[$sid] : 0; $c_total = isset($compliance_total) ? $compliance_total : (4 * $days_in_month); $c_pct = $c_total > 0 ? round($c_followed * 100 / $c_total) : 0; ?>
                    <td class="ms-tot" style="background:#f0fdfa; font-weight:700; color:<?php echo $c_pct >= 75 ? '#15803d' : ($c_pct >= 40 ? '#b45309' : '#b91c1c'); ?>;" title="<?php echo $c_followed; ?> of <?php echo $c_total; ?> compliance points followed (<?php echo $c_pct; ?>%)"><?php echo $c_followed; ?> / <?php echo $c_total; ?><br><span style="font-size:10px; font-weight:600;"><?php echo $c_pct; ?>%</span></td>
                    <?php if (!empty($late_policy_on)) {
                        $lates = isset($status_count['late']) ? $status_count['late'] : 0;
                        $ded   = $late_deduct($lates); ?>
                        <td class="ms-tot" style="background:#fffbeb; <?php echo $ded > 0 ? 'color:#b45309; font-weight:700;' : 'color:#94a3b8;'; ?>" title="<?php echo $lates; ?> late(s) this month → <?php echo $ded; ?> day(s) deducted. <?php echo html_escape($late_policy_text); ?>">
                            <?php echo $ded > 0 ? '-' . rtrim(rtrim(number_format($ded, 1), '0'), '.') : '0'; ?>
                        </td>
                    <?php } ?>
                    
                    <?php
                    $fmt_dur = function ($secs) { $h = intdiv($secs, 3600); $m = intdiv($secs % 3600, 60); return ($h > 0 ? $h . 'h ' : '') . $m . 'm'; };
                    $early_list = array();
                    foreach ($early_out_records as $rec) {
                        $early_list[] = array('formatted' => $rec['formatted'], 'line' => 'Out ' . $rec['out_time'] . ' (scheduled ' . $rec['scheduled'] . ')', 'badge' => $rec['gap']);
                    }
                    ?>
                    <!-- Early Left: total + day-wise list -->
                    <td class="ms-tot" style="background:#fef2f2;">
                        <?php if ($tot['early_out_secs'] > 0) {
                            echo $ms_detail_btn('Early Left: ' . $s['name'], '-' . $fmt_dur($tot['early_out_secs']), count($early_list) . 'd', $early_list, 'early');
                        } else { ?>
                            <span style="color:#94a3b8; font-size:11px;">0m</span>
                        <?php } ?>
                    </td>

                    <!-- Overtime: total + day-wise list -->
                    <td class="ms-tot" style="background:#f0fdf4;">
                        <?php if ($tot['overtime'] > 0) {
                            echo $ms_detail_btn('Overtime: ' . $s['name'], '+' . $fmt_dur($total_overtime_secs), $tot['overtime'] . 'd', $overtime_records, 'ot');
                        } else { ?>
                            <span style="color:#94a3b8; font-size:11px;">0m</span>
                        <?php } ?>
                    </td>

                    <!-- Holiday work: days worked on Sundays / holidays (comp-off or extra pay) -->
                    <td class="ms-tot" style="background:#faf5ff;">
                        <?php if (!empty($holiday_work_records) || $holiday_work_days > 0) {
                            echo $ms_detail_btn('Worked on holidays: ' . $s['name'], rtrim(rtrim(number_format($holiday_work_days, 1), '0'), '.') . 'd', $fmt_dur($holiday_work_secs), $holiday_work_records, 'hol');
                        } else { ?>
                            <span style="color:#94a3b8; font-size:11px;">0</span>
                        <?php } ?>
                    </td>

                    <?php
                    // Compute Actual Worked vs Required Hours
                    $wH = intdiv($total_worked_secs, 3600);
                    $wM = intdiv($total_worked_secs % 3600, 60);
                    $worked_text = ($wH > 0 ? $wH . 'h ' : '0h ') . ($wM > 0 ? $wM . 'm' : '');

                    // Expected required hours based on present days
                    $expected_req_secs = (int) ($attended_days_count * $reqDaySecs);
                    $shortfall_secs = $expected_req_secs - $total_worked_secs;
                    ?>
                    <td class="ms-tot" style="font-weight:700; color:#0f172a; font-size:11.5px;"><?php echo $worked_text; ?></td>
                    <td class="ms-tot" style="font-size:11px;">
                        <?php if ($total_worked_secs === 0 && $attended_days_count == 0) { ?>
                            <span style="color:#94a3b8;">-</span>
                        <?php } elseif ($shortfall_secs > 60) { 
                            $sH = intdiv($shortfall_secs, 3600);
                            $sM = intdiv($shortfall_secs % 3600, 60);
                        ?>
                            <span class="ms-badge-short" title="Expected: <?php echo intdiv($expected_req_secs, 3600); ?>h <?php echo intdiv($expected_req_secs % 3600, 60); ?>m based on role schedule">
                                <i class="fa fa-arrow-down"></i> -<?php echo ($sH > 0 ? $sH . 'h ' : '') . $sM . 'm'; ?>
                            </span>
                        <?php } else { 
                            $surplus_secs = abs($shortfall_secs);
                            $suH = intdiv($surplus_secs, 3600);
                            $suM = intdiv($surplus_secs % 3600, 60);
                        ?>
                            <span class="ms-badge-ok" title="Met or exceeded required role hours">
                                <i class="fa fa-check"></i> <?php echo ($suH > 0 ? '+' . $suH . 'h ' : 'OK'); ?>
                            </span>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<script>
    (function($) {
        if (!$) return;
        // Day-wise detail panel for Early Left / Overtime / Holiday Work. Opens on hover (and stays
        // while the mouse is over it, so the list can be scrolled) or on click (pinned until closed).
        $(function() {
            // The sheet is reloaded by AJAX; bind the document-level handlers only once.
            if (window.__msDetailInit) { $('#ms-detail-panel').hide(); return; }
            window.__msDetailInit = true;
            var $panel = $('#ms-detail-panel');
            if (!$panel.length) {
                $panel = $('<div id="ms-detail-panel"><div class="ms-dp-head"><span class="ms-dp-title"></span>' +
                    '<button type="button" class="ms-dp-close" aria-label="Close">&times;</button></div><div class="ms-dp-body"></div></div>').appendTo('body');
            }
            var hideTimer = null, pinned = false, $current = null;

            function show($btn) {
                clearTimeout(hideTimer);
                $current = $btn;
                var kind = ($btn.attr('class').match(/ms-k-(\w+)/) || [])[1] || '';
                $panel.attr('class', 'ms-p-' + kind);
                $panel.find('.ms-dp-title').text($btn.data('title'));
                $panel.find('.ms-dp-body').html($btn.next('.ms-detail-src').html()).scrollTop(0);
                var r = $btn[0].getBoundingClientRect(), w = 330;
                $panel.css({ display: 'block', visibility: 'hidden' });
                var h = $panel.outerHeight();
                var left = Math.max(8, r.left - w - 8);
                if (r.left - w - 8 < 8) { left = Math.min(window.innerWidth - w - 8, r.right + 8); }
                var top = Math.min(Math.max(8, r.top - 20), window.innerHeight - h - 8);
                $panel.css({ left: left, top: Math.max(8, top), visibility: 'visible' });
            }
            function hideSoon() {
                if (pinned) return;
                clearTimeout(hideTimer);
                hideTimer = setTimeout(function () { $panel.hide(); $current = null; }, 250);
            }

            $(document).on('mouseenter', '.ms-detail-btn', function () { if (!pinned) show($(this)); })
                       .on('mouseleave', '.ms-detail-btn', hideSoon)
                       .on('click', '.ms-detail-btn', function (e) {
                           e.stopPropagation();
                           pinned = true;
                           show($(this));
                       });
            $panel.on('mouseenter', function () { clearTimeout(hideTimer); })
                  .on('mouseleave', hideSoon)
                  .on('click', function (e) { e.stopPropagation(); });
            $panel.on('click', '.ms-dp-close', function () { pinned = false; $panel.hide(); });
            $(document).on('click', function () { if (pinned) { pinned = false; $panel.hide(); } });
            $(document).on('keydown', function (e) { if (e.key === 'Escape') { pinned = false; $panel.hide(); } });
            // The sheet scrolls inside its own box: close an unpinned panel when it moves.
            document.addEventListener('scroll', function (e) {
                if ($current && !pinned && $(e.target).is('.ms-wrap')) { $panel.hide(); }
            }, true);
        });
    })(window.jQuery);
</script>
<?php } ?>

