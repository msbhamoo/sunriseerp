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
                <th class="ms-tot" title="Present">P</th>
                <th class="ms-tot" title="Late">L</th>
                <th class="ms-tot" title="Absent">A</th>
                <th class="ms-tot" title="Leave/Half/Holiday">Oth</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($staff as $s) {
                $sid = $s['staff_id'];
                $tot = array('present' => 0, 'late' => 0, 'absent' => 0, 'oth' => 0);
            ?>
                <tr>
                    <td class="ms-name"><?php echo htmlspecialchars($s['name']); ?><br><span style="font-size:10px;color:#9aa6b4;"><?php echo htmlspecialchars($s['employee_id']); ?></span></td>
                    <td style="text-align:left;color:#6b7684;"><?php echo htmlspecialchars($s['role_name']); ?></td>
                    <?php for ($d = 1; $d <= $days_in_month; $d++) {
                        $ds = sprintf('%04d-%02d-%02d', $year, $month, $d);
                        $isHol = isset($holiday_dates[$ds]);
                        $type = isset($map[$sid][$ds]) ? $map[$sid][$ds] : null;
                        if ($type === 'present') { $tot['present']++; }
                        elseif ($type === 'late') { $tot['late']++; }
                        elseif ($type === 'absent') { $tot['absent']++; }
                        elseif ($type !== null) { $tot['oth']++; }
                    ?>
                        <td class="<?php echo $isHol ? 'ms-hol' : ''; ?>">
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
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<?php } ?>
