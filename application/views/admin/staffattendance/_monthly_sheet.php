<?php
// short code + colour per attendance type (by long_lang_name)
$meta = array(
    'present'                => array('P',  '#16a34a'),
    'late'                   => array('L',  '#f59e0b'),
    'absent'                 => array('A',  '#dc2626'),
    'half_day'               => array('½', '#2563eb'),
    'holiday'                => array('H',  '#7c3aed'),
    'half_day_second_shift'  => array('½', '#0d9488'),
    'unplanned_leave'        => array('UL', '#db2777'),
);
$monthName = date('F Y', mktime(0, 0, 0, $month, 1, $year));
$staff = $sheet['staff'];
$map   = $sheet['map'];
$map_times = isset($sheet['map_times']) ? $sheet['map_times'] : array();
$role_req_map = isset($role_required_hours) ? $role_required_hours : array();
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
        <span class="li"><span class="sw" style="background:<?php echo $mc[1]; ?>;"></span> <?php echo $this->lang->line($k) ? $this->lang->line($k) : $k; ?></span>
    <?php } ?>
    <span class="li"><span class="sw" style="background:#f3e8ff;border:1px solid #d8b4fe;"></span> Holiday column</span>
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
                <th class="ms-tot" title="Present Days">P</th>
                <th class="ms-tot" title="Late Days">L</th>
                <th class="ms-tot" title="Absent Days">A</th>
                <th class="ms-tot" title="Leave / Half / Holiday">Oth</th>
                <th class="ms-tot" title="Total Actual Worked Hours (Sum of all In-Out punches)" style="min-width:75px;">Worked</th>
                <th class="ms-tot" title="Monthly Shortfall based on Role Required Hours" style="min-width:95px;">Shortfall / +/-</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($staff as $s) {
                $sid = $s['staff_id'];
                $tot = array('present' => 0, 'late' => 0, 'absent' => 0, 'oth' => 0);
                $total_worked_secs = 0;
                $attended_days_count = 0;
            ?>
                <tr>
                    <td class="ms-name"><?php echo htmlspecialchars($s['name']); ?><br><span style="font-size:10px;color:#9aa6b4;"><?php echo htmlspecialchars($s['employee_id']); ?></span></td>
                    <td style="text-align:left;color:#6b7684;"><?php echo htmlspecialchars($s['role_name']); ?></td>
                    <?php for ($d = 1; $d <= $days_in_month; $d++) {
                        $ds = sprintf('%04d-%02d-%02d', $year, $month, $d);
                        $isHol = isset($holiday_dates[$ds]);
                        $type = isset($map[$sid][$ds]) ? $map[$sid][$ds] : null;
                        if ($type === 'present') { $tot['present']++; $attended_days_count++; }
                        elseif ($type === 'late') { $tot['late']++; $attended_days_count++; }
                        elseif ($type === 'absent') { $tot['absent']++; }
                        elseif ($type === 'half_day' || $type === 'half_day_second_shift') { $tot['oth']++; $attended_days_count += 0.5; }
                        elseif ($type !== null) { $tot['oth']++; }

                        // Calculate day duration if punch exists
                        $day_hover = '';
                        if (isset($map_times[$sid][$ds])) {
                            $inS = strtotime("1970-01-01 " . $map_times[$sid][$ds]['in'] . " UTC");
                            $outS = strtotime("1970-01-01 " . $map_times[$sid][$ds]['out'] . " UTC");
                            if ($outS > $inS) {
                                $diff = $outS - $inS;
                                $total_worked_secs += $diff;
                                $dh = intdiv($diff, 3600);
                                $dm = intdiv($diff % 3600, 60);
                                $day_hover = "In: " . date('h:i A', $inS) . " | Out: " . date('h:i A', $outS) . " (" . $dh . "h " . $dm . "m)";
                            }
                        }
                    ?>
                        <td class="<?php echo $isHol ? 'ms-hol' : ''; ?>" title="<?php echo !empty($day_hover) ? $day_hover : ($type ? ucfirst(str_replace('_', ' ', $type)) : ''); ?>">
                            <?php if ($type !== null && isset($meta[$type])) {
                                echo '<span class="ms-cell" style="background:' . $meta[$type][1] . ';">' . $meta[$type][0] . '</span>';
                            } elseif ($isHol) {
                                echo '<span style="color:#c084fc;">&bull;</span>';
                            } else {
                                echo '<span style="color:#cbd5e1;">&middot;</span>';
                            } ?>
                        </td>
                    <?php } ?>
                    <td class="ms-tot" style="color:#16a34a;"><?php echo $tot['present']; ?></td>
                    <td class="ms-tot" style="color:#f59e0b;"><?php echo $tot['late']; ?></td>
                    <td class="ms-tot" style="color:#dc2626;"><?php echo $tot['absent']; ?></td>
                    <td class="ms-tot" style="color:#6b7684;"><?php echo $tot['oth']; ?></td>
                    
                    <?php
                    // Compute Actual Worked vs Required Hours
                    $wH = intdiv($total_worked_secs, 3600);
                    $wM = intdiv($total_worked_secs % 3600, 60);
                    $worked_text = ($wH > 0 ? $wH . 'h ' : '0h ') . ($wM > 0 ? $wM . 'm' : '');

                    // Required per day
                    $roleId = isset($s['role_id']) ? $s['role_id'] : 0;
                    $reqDayStr = isset($role_req_map[$roleId]) ? $role_req_map[$roleId] : '08:00:00';
                    $reqDayParts = explode(':', $reqDayStr);
                    $reqDaySecs = ((int)$reqDayParts[0] * 3600) + ((isset($reqDayParts[1]) ? (int)$reqDayParts[1] : 0) * 60);
                    if ($reqDaySecs <= 0) { $reqDaySecs = 8 * 3600; } // default 8 hours

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
<?php } ?>

