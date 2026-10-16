<?php
/**
 * Monthly Staff Attendance Register: printable (A4 landscape) or Excel (same table).
 * Data comes from Staffattendance::_monthSheetData(), the same source as the Monthly Sheet,
 * so the counts match it exactly.
 */
$excel     = ($format === 'excel');
$staff     = $sheet['staff'];
$map       = $sheet['map'];
$map_times = isset($sheet['map_times']) ? $sheet['map_times'] : array();
$monthName = date('F Y', mktime(0, 0, 0, $month, 1, $year));
$today     = date('Y-m-d');

// Status codes from Settings (same rules as the monthly sheet).
$codes = array(); // long name => [code, label]
foreach ((array) $type_list as $t) {
    $t    = (array) $t;
    $key  = strtolower(trim(strip_tags($t['key_value'])));
    $name = !empty($t['long_lang_name']) ? $t['long_lang_name'] : $key;
    $codes[$name] = array(strtoupper($key === 'on_duty' ? 'OD' : $key), !empty($t['type']) ? $t['type'] : ucwords(str_replace('_', ' ', $name)));
}
if (!isset($codes['on_duty'])) { $codes['on_duty'] = array('OD', 'On Duty'); }
$attend_weight = array('present' => 1, 'late' => 1, 'on_duty' => 1, 'half_day' => 0.5, 'half_day_second_shift' => 0.5);
$num = function ($n) { return rtrim(rtrim(number_format($n, 1), '0'), '.'); };
$lp_cols = !empty($late_policy_on) ? 1 : 0;

$logo = '';
if (!$excel && !empty($school->image)) {
    $logo = base_url('uploads/school_content/logo/' . rawurlencode($school->image));
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Staff Attendance Register - <?php echo html_escape($monthName); ?></title>
<?php if (!$excel) { ?>
<style>
    @page { size: A4 landscape; margin: 8mm 7mm 10mm 7mm; }
    * { box-sizing: border-box; }
    body { font-family: Arial, Helvetica, sans-serif; color: #111; margin: 0; background: #eef1f5; }
    .sheet { background: #fff; margin: 12px auto; padding: 10px 12px; max-width: 1400px; }
    .toolbar { text-align: right; max-width: 1400px; margin: 10px auto 0; }
    .toolbar button, .toolbar a { font: 600 13px Arial; padding: 7px 14px; border-radius: 6px; border: 1px solid #94a3b8; background: #fff; color: #0f172a; cursor: pointer; text-decoration: none; margin-left: 6px; }
    .toolbar .primary { background: #0f766e; border-color: #0f766e; color: #fff; }
    .head { display: flex; align-items: center; gap: 12px; border-bottom: 2px solid #111; padding-bottom: 6px; }
    .head img { height: 54px; }
    .head .school { flex: 1; text-align: center; }
    .head .school h1 { font-size: 18px; margin: 0; text-transform: uppercase; letter-spacing: .3px; }
    .head .school div { font-size: 10.5px; margin-top: 2px; }
    .title { display: flex; justify-content: space-between; align-items: flex-end; margin: 6px 0; font-size: 11px; }
    .title h2 { font-size: 14px; margin: 0; text-align: center; flex: 1; }
    table.reg { width: 100%; border-collapse: collapse; font-size: 8.5px; table-layout: fixed; }
    table.reg th, table.reg td { border: 1px solid #555; padding: 2px 1px; text-align: center; overflow: hidden; white-space: nowrap; }
    table.reg thead { display: table-header-group; }
    table.reg tr { page-break-inside: avoid; }
    table.reg th { background: #e5e7eb; font-weight: 700; }
    table.reg td.nm, table.reg th.nm { text-align: left; padding-left: 3px; }
    table.reg .off { background: #ede9fe; }
    table.reg .tot { background: #f3f4f6; font-weight: 700; }
    table.reg .A { color: #b91c1c; font-weight: 700; }
    table.reg .hw { outline: 1px solid #7c3aed; outline-offset: -2px; }
    .legend { font-size: 9px; margin-top: 6px; line-height: 1.5; }
    .legend b { display: inline-block; min-width: 18px; }
    .sign { display: flex; justify-content: space-between; margin-top: 34px; font-size: 11px; page-break-inside: avoid; }
    .sign div { width: 22%; text-align: center; border-top: 1px solid #111; padding-top: 4px; }
    .foot { font-size: 9px; color: #444; margin-top: 10px; display: flex; justify-content: space-between; }
    @media print {
        body { background: #fff; }
        .toolbar { display: none; }
        .sheet { margin: 0; padding: 0; max-width: none; }
        * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>
<?php } ?>
</head>
<body>
<?php if (!$excel) {
    $qs = http_build_query(array('role' => $role, 'month' => $month, 'year' => $year, 'format' => 'excel')); ?>
    <div class="toolbar">
        <a href="<?php echo site_url('admin/staffattendance/register') . '?' . $qs; ?>">Download Excel</a>
        <button type="button" class="primary" onclick="window.print()">Print / Save as PDF</button>
    </div>
<?php } ?>
<div class="sheet">
    <?php if ($excel) { ?>
        <table><tr><td colspan="<?php echo $days_in_month + 14 + $lp_cols; ?>" style="font-size:16px;font-weight:bold;text-align:center;"><?php echo html_escape($school->name); ?></td></tr>
        <tr><td colspan="<?php echo $days_in_month + 14 + $lp_cols; ?>" style="text-align:center;"><?php echo html_escape($school->address); ?><?php echo !empty($school->dise_code) ? ' | UDISE: ' . html_escape($school->dise_code) : ''; ?></td></tr>
        <tr><td colspan="<?php echo $days_in_month + 14 + $lp_cols; ?>" style="font-weight:bold;text-align:center;">Staff Attendance Register - <?php echo html_escape($monthName); ?> - <?php echo html_escape($role_label); ?></td></tr></table>
    <?php } else { ?>
        <div class="head">
            <?php if ($logo) { ?><img src="<?php echo html_escape($logo); ?>" alt="" onerror="this.style.display='none'"><?php } ?>
            <div class="school">
                <h1><?php echo html_escape($school->name); ?></h1>
                <div><?php echo html_escape($school->address); ?></div>
                <div>
                    <?php echo !empty($school->phone) ? 'Phone: ' . html_escape($school->phone) : ''; ?>
                    <?php echo !empty($school->email) ? ' &nbsp;|&nbsp; Email: ' . html_escape($school->email) : ''; ?>
                    <?php echo !empty($school->dise_code) ? ' &nbsp;|&nbsp; UDISE Code: ' . html_escape($school->dise_code) : ''; ?>
                </div>
            </div>
            <?php if ($logo) { ?><div style="width:54px;"></div><?php } ?>
        </div>
        <div class="title">
            <div>Staff: <b><?php echo html_escape($role_label); ?></b> (<?php echo count($staff); ?>)</div>
            <h2>STAFF ATTENDANCE REGISTER &mdash; <?php echo strtoupper(html_escape($monthName)); ?></h2>
            <div>Working days: <b><?php echo (int) $working_days; ?></b> &nbsp; Holidays: <b><?php echo (int) $holidays; ?></b></div>
        </div>
    <?php } ?>

    <table class="reg" border="1">
        <colgroup>
            <col style="width:2.2%"><col style="width:10.5%"><col style="width:5%"><col style="width:5.5%">
            <?php for ($d = 1; $d <= $days_in_month; $d++) { ?><col><?php } ?>
            <?php for ($i = 0; $i < 10 + $lp_cols; $i++) { ?><col style="width:2.6%"><?php } ?>
        </colgroup>
        <thead>
            <tr>
                <th rowspan="2">S.No</th>
                <th rowspan="2" class="nm">Name of Staff</th>
                <th rowspan="2">Emp. ID</th>
                <th rowspan="2">Designation</th>
                <?php for ($d = 1; $d <= $days_in_month; $d++) {
                    $ds = sprintf('%04d-%02d-%02d', $year, $month, $d); ?>
                    <th class="<?php echo isset($holiday_dates[$ds]) ? 'off' : ''; ?>" <?php echo $excel && isset($holiday_dates[$ds]) ? 'style="background:#ede9fe"' : ''; ?>><?php echo $d; ?></th>
                <?php } ?>
                <th colspan="<?php echo 10 + $lp_cols; ?>" class="tot">Totals</th>
            </tr>
            <tr>
                <?php for ($d = 1; $d <= $days_in_month; $d++) {
                    $ds = sprintf('%04d-%02d-%02d', $year, $month, $d); ?>
                    <th class="<?php echo isset($holiday_dates[$ds]) ? 'off' : ''; ?>" <?php echo $excel && isset($holiday_dates[$ds]) ? 'style="background:#ede9fe"' : ''; ?>><?php echo substr(date('D', strtotime($ds)), 0, 2); ?></th>
                <?php } ?>
                <th class="tot" title="Present">P</th>
                <th class="tot" title="Late">L</th>
                <th class="tot" title="Half Day (F + SH)">½</th>
                <th class="tot" title="Absent">A</th>
                <th class="tot" title="Leave">LV</th>
                <th class="tot" title="On Duty">OD</th>
                <th class="tot" title="Holiday">H</th>
                <th class="tot" title="Days attended: P/L/OD = 1, half day = 0.5">Att.</th>
                <th class="tot" title="Worked on a holiday">H.W</th>
                <th class="tot" title="Working days not marked">N.M</th>
                <?php if ($lp_cols) { ?><th class="tot" title="<?php echo html_escape($late_policy_text); ?>">Late Ded.</th><?php } ?>
            </tr>
        </thead>
        <tbody>
        <?php $sn = 0; foreach ($staff as $s) {
            $sid = $s['staff_id'];
            $cnt = array('present' => 0, 'late' => 0, 'half' => 0, 'absent' => 0, 'leave' => 0, 'on_duty' => 0, 'holiday' => 0);
            $attended = 0; $hol_work = 0; $not_marked = 0; ?>
            <tr>
                <td><?php echo ++$sn; ?></td>
                <td class="nm"><?php echo html_escape($s['name']); ?></td>
                <td><?php echo html_escape($s['employee_id']); ?></td>
                <td><?php echo html_escape($s['role_name']); ?></td>
                <?php for ($d = 1; $d <= $days_in_month; $d++) {
                    $ds    = sprintf('%04d-%02d-%02d', $year, $month, $d);
                    $isOff = isset($staff_off_map) ? isset($staff_off_map[$sid][$ds]) : isset($holiday_dates[$ds]);
                    $type  = isset($map[$sid][$ds]) ? $map[$sid][$ds] : null;
                    $code  = '';
                    $cls   = $isOff ? 'off' : '';
                    if ($type !== null) {
                        $code = isset($codes[$type]) ? $codes[$type][0] : strtoupper(substr($type, 0, 2));
                        if ($type === 'half_day' || $type === 'half_day_second_shift') { $cnt['half']++; }
                        elseif (isset($cnt[$type])) { $cnt[$type]++; }
                        $w = isset($attend_weight[$type]) ? $attend_weight[$type] : 0;
                        if ($isOff && $w > 0) { $hol_work += $w; $cls .= ' hw'; } else { $attended += $w; }
                        if ($type === 'absent') { $cls .= ' A'; }
                    } elseif ($isOff) {
                        $code = 'H';
                        $cnt['holiday']++;
                    } elseif ($ds <= $today) {
                        $not_marked++;
                    }
                    $tip = isset($map_times[$sid][$ds]) ? 'In ' . substr($map_times[$sid][$ds]['in'], 0, 5) . ' Out ' . substr($map_times[$sid][$ds]['out'], 0, 5) : '';
                    $xs  = $excel ? ($isOff ? ' style="background:#ede9fe"' : '') : '';
                ?>
                    <td class="<?php echo trim($cls); ?>"<?php echo $xs; ?><?php echo $tip && !$excel ? ' title="' . $tip . '"' : ''; ?>><?php echo html_escape($code); ?></td>
                <?php } ?>
                <td class="tot"><?php echo $cnt['present']; ?></td>
                <td class="tot"><?php echo $cnt['late']; ?></td>
                <td class="tot"><?php echo $cnt['half']; ?></td>
                <td class="tot"><?php echo $cnt['absent']; ?></td>
                <td class="tot"><?php echo $cnt['leave']; ?></td>
                <td class="tot"><?php echo $cnt['on_duty']; ?></td>
                <td class="tot"><?php echo $cnt['holiday']; ?></td>
                <td class="tot"><?php echo $num($attended); ?></td>
                <td class="tot"><?php echo $num($hol_work); ?></td>
                <td class="tot"><?php echo $not_marked; ?></td>
                <?php if ($lp_cols) { $ded = $late_deduct($cnt['late']); ?><td class="tot"><?php echo $ded > 0 ? '-' . $num($ded) : '0'; ?></td><?php } ?>
            </tr>
        <?php } ?>
        <?php if (empty($staff)) { ?>
            <tr><td colspan="<?php echo $days_in_month + 14 + $lp_cols; ?>">No active staff found.</td></tr>
        <?php } ?>
        </tbody>
    </table>

    <?php if ($excel) { ?>
        <table><tr><td colspan="<?php echo $days_in_month + 14 + $lp_cols; ?>">Codes: <?php foreach ($codes as $c) { echo html_escape($c[0] . ' = ' . $c[1]) . '; '; } ?>H = Holiday / weekly off. Att. = days attended (half day = 0.5). H.W = worked on a holiday. N.M = working days not marked.<?php echo $lp_cols ? ' Late Ded. = ' . html_escape($late_policy_text) : ''; ?></td></tr>
        <tr><td colspan="<?php echo $days_in_month + 14 + $lp_cols; ?>">Generated on <?php echo date('d M Y h:i A'); ?><?php echo $generated_by ? ' by ' . html_escape($generated_by) : ''; ?></td></tr></table>
    <?php } else { ?>
        <div class="legend">
            <?php foreach ($codes as $c) { ?><span><b><?php echo html_escape($c[0]); ?></b><?php echo html_escape($c[1]); ?></span> &nbsp; <?php } ?>
            <span><b>H</b>Holiday / weekly off (shaded)</span> &nbsp;
            <span>Boxed cell = worked on a holiday</span><br>
            <b>Att.</b> days attended (Present / Late / On Duty = 1, Half Day = 0.5) &nbsp;
            <b>H.W</b> days worked on a holiday &nbsp; <b>N.M</b> working days with no attendance marked<?php if ($lp_cols) { ?><br><b>Late Ded.</b> <?php echo html_escape($late_policy_text); ?><?php } ?>
        </div>
        <div class="sign">
            <div>Prepared by<br>(Office / Attendance In-charge)</div>
            <div>Checked by<br>(Administrative Officer)</div>
            <div>Principal<br>(Signature &amp; Seal)</div>
        </div>
        <div class="foot">
            <span>Generated on <?php echo date('d M Y h:i A'); ?><?php echo $generated_by ? ' by ' . html_escape($generated_by) : ''; ?> from the school ERP.</span>
            <span><?php echo html_escape($school->name); ?> &mdash; Staff Attendance Register, <?php echo html_escape($monthName); ?></span>
        </div>
    <?php } ?>
</div>
</body>
</html>
