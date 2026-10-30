<?php
/**
 * Monthly Staff Compliance Sheet Partial View
 */
$monthName = date('F Y', mktime(0, 0, 0, $month, 1, $year));
$staff = isset($sheet['staff']) ? $sheet['staff'] : [];
$map   = isset($sheet['map']) ? $sheet['map'] : [];
$holiday_dates = isset($holiday_dates) ? $holiday_dates : [];

// Aggregate statistics across working days
$totalStaff = count($staff);
$holidaysCount = count($holiday_dates);
$workingDays = max(0, $days_in_month - $holidaysCount);

$totalRequiredChecks = 0;
$totalUniformYes = 0;
$totalIdCardYes = 0;
$totalLessonPlanYes = 0;
$totalPhoneYes = 0;
$totalFullCompliantDays = 0;

foreach ($staff as $s) {
    for ($d = 1; $d <= $days_in_month; $d++) {
        $c_date = sprintf('%04d-%02d-%02d', $year, $month, $d);
        if (isset($holiday_dates[$c_date])) {
            continue; // Skip off days/Sundays
        }
        $totalRequiredChecks++;
        if (isset($map[$s->staff_id][$c_date])) {
            $rec = $map[$s->staff_id][$c_date];
            if ($rec['uniform'] === 'yes') $totalUniformYes++;
            if ($rec['id_card'] === 'yes') $totalIdCardYes++;
            if ($rec['lesson_plan'] === 'yes') $totalLessonPlanYes++;
            if ($rec['phone_handover'] === 'yes') $totalPhoneYes++;
            if (!empty($rec['is_compliant'])) $totalFullCompliantDays++;
        }
    }
}

$overallPct = ($totalRequiredChecks > 0) ? round(($totalFullCompliantDays / $totalRequiredChecks) * 100, 1) : 0;
?>
<style>
    .mcs-stats { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 14px; }
    .mcs-tile { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px 16px; min-width: 130px; }
    .mcs-tile .v { font-size: 20px; font-weight: 800; color: #0f172a; line-height: 1.1; }
    .mcs-tile .l { font-size: 11px; font-weight: 600; color: #64748b; margin-top: 3px; text-transform: uppercase; letter-spacing: 0.4px; }
    .mcs-tile.success .v { color: #16a34a; }
    .mcs-tile.info .v { color: #0f766e; }
    .mcs-tile.purple .v { color: #7c3aed; }
    
    .mcs-wrap { width: 100%; max-width: 100%; max-height: 70vh; overflow: auto; border: 1px solid #e2e8f0; border-radius: 10px; background: #fff; box-shadow: 0 1px 3px rgba(0,0,0,0.03); }
    table.mcs-table { border-collapse: collapse; font-size: 12px; width: 100%; }
    table.mcs-table th, table.mcs-table td { border: 1px solid #eef2f6; padding: 5px 4px; text-align: center; white-space: nowrap; }
    table.mcs-table thead th { background: #f8fafc; color: #475569; position: sticky; top: 0; z-index: 2; font-weight: 700; font-size: 11.5px; }
    table.mcs-table th.mcs-name, table.mcs-table td.mcs-name { position: sticky; left: 0; background: #fff; text-align: left; min-width: 180px; z-index: 3; font-weight: 600; }
    table.mcs-table thead th.mcs-name { z-index: 4; background: #f8fafc; }
    table.mcs-table th.mcs-hol { background: #faf5ff; color: #7c3aed; }
    table.mcs-table td.mcs-hol { background: #faf5ff; }
    table.mcs-table td.mcs-tot { font-weight: 700; background: #f8fafc; color: #0f172a; }
    
    .mcs-pill { display: inline-flex; align-items: center; justify-content: center; width: 22px; height: 22px; border-radius: 5px; font-weight: 800; font-size: 11px; text-decoration: none; cursor: pointer; transition: transform 0.1s ease; }
    .mcs-pill:hover { transform: scale(1.15); }
    .mcs-pill-yes { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
    .mcs-pill-partial { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
    .mcs-pill-no { background: #fee2e2; color: #b91c1c; border: 1px solid #fecaca; }
    .mcs-pill-empty { color: #cbd5e1; font-weight: 400; }
    .mcs-pill-hol { color: #c084fc; font-weight: 600; font-size: 10px; }

    .mcs-popover-list { list-style: none; padding: 0; margin: 0; font-size: 12px; }
    .mcs-popover-list li { display: flex; justify-content: space-between; gap: 14px; padding: 4px 0; border-bottom: 1px solid #f1f5f9; }
    .mcs-popover-list li:last-child { border-bottom: none; }

    /* Popover Clean & High Z-Index Overrides */
    .popover { 
        background: #ffffff !important; 
        max-width: 320px !important; 
        border-radius: 8px !important; 
        box-shadow: 0 10px 25px -5px rgba(0,0,0,0.2), 0 8px 10px -6px rgba(0,0,0,0.1) !important; 
        border: 1px solid #e2e8f0 !important; 
        padding: 0 !important;
        z-index: 99999 !important;
    }
    .popover.top > .arrow:after {
        border-top-color: #ffffff !important;
    }
    .popover.bottom > .arrow:after {
        border-bottom-color: #f8fafc !important;
    }
    .popover-title { 
        background: #f8fafc !important; 
        font-weight: 700 !important; 
        font-size: 13px !important; 
        color: #0f172a !important; 
        border-bottom: 1px solid #e2e8f0 !important; 
        padding: 9px 14px !important; 
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

<!-- High-level Metric Tiles -->
<div class="mcs-stats">
    <div class="mcs-tile">
        <div class="v"><?php echo $totalStaff; ?></div>
        <div class="l">Staff Members</div>
    </div>
    <div class="mcs-tile purple">
        <div class="v"><?php echo $days_in_month; ?> <span style="font-size:13px; font-weight:500;">(<?php echo $workingDays; ?> Work)</span></div>
        <div class="l">Month Days (<?php echo $holidaysCount; ?> Off)</div>
    </div>
    <div class="mcs-tile success">
        <div class="v"><?php echo $overallPct; ?>%</div>
        <div class="l">Full Compliance Rate</div>
    </div>
    <div class="mcs-tile info">
        <div class="v"><?php echo $totalUniformYes; ?> <span style="font-size:12px; color:#64748b;">/ <?php echo $totalRequiredChecks; ?></span></div>
        <div class="l">Uniform Done</div>
    </div>
    <div class="mcs-tile info">
        <div class="v"><?php echo $totalIdCardYes; ?> <span style="font-size:12px; color:#64748b;">/ <?php echo $totalRequiredChecks; ?></span></div>
        <div class="l">ID Card Done</div>
    </div>
    <div class="mcs-tile info">
        <div class="v"><?php echo $totalLessonPlanYes; ?> <span style="font-size:12px; color:#64748b;">/ <?php echo $totalRequiredChecks; ?></span></div>
        <div class="l">Lesson Plan Done</div>
    </div>
    <div class="mcs-tile info">
        <div class="v"><?php echo $totalPhoneYes; ?> <span style="font-size:12px; color:#64748b;">/ <?php echo $totalRequiredChecks; ?></span></div>
        <div class="l">Phone Handover Done</div>
    </div>
</div>

<!-- Legend Bar -->
<div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; margin-bottom:10px;">
    <div style="display:flex; align-items:center; gap:14px; font-size:12px; color:#475569;">
        <span><span class="mcs-pill mcs-pill-yes" style="display:inline-flex; width:18px; height:18px; font-size:10px; vertical-align:middle; margin-right:4px;">&#10003;</span> All 4 Compliant (100%)</span>
        <span><span class="mcs-pill mcs-pill-partial" style="display:inline-flex; width:18px; height:18px; font-size:10px; vertical-align:middle; margin-right:4px;">!</span> Partially Compliant</span>
        <span><span class="mcs-pill mcs-pill-no" style="display:inline-flex; width:18px; height:18px; font-size:10px; vertical-align:middle; margin-right:4px;">&times;</span> Non-Compliant</span>
        <span><span class="mcs-pill-hol" style="display:inline-block; margin-right:4px; font-weight:700;">H</span> Holiday / Sunday</span>
    </div>
    <div style="font-size:12px; color:#64748b; font-weight:600;">
        <i class="fa fa-info-circle text-info"></i> Click or hover over any day's badge to inspect Uniform, ID Card, Lesson Plan & Phone status.
    </div>
</div>

<!-- Monthly Grid Table -->
<div class="mcs-wrap">
    <table class="mcs-table">
        <thead>
            <tr>
                <th class="mcs-name">Staff Member</th>
                <th style="min-width:90px;">Role</th>
                <?php for ($d = 1; $d <= $days_in_month; $d++) {
                    $day_date = sprintf('%04d-%02d-%02d', $year, $month, $d);
                    $is_off   = isset($holiday_dates[$day_date]);
                    $dow      = date('D', strtotime($day_date));
                ?>
                    <th class="<?php echo $is_off ? 'mcs-hol' : ''; ?>" title="<?php echo date('l, d M Y', strtotime($day_date)) . ($is_off ? ' (Holiday / Off)' : ''); ?>">
                        <div><?php echo $d; ?></div>
                        <div style="font-size:9.5px; opacity:0.8; font-weight:600;"><?php echo $dow; ?></div>
                    </th>
                <?php } ?>
                <th class="mcs-tot" title="Full Compliant Days / Working Days">Full Compliant</th>
                <th class="mcs-tot" title="Overall Compliance %">Score %</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($staff)) { ?>
                <tr>
                    <td colspan="<?php echo $days_in_month + 4; ?>" style="padding:28px; text-align:center; color:#94a3b8;">
                        <i class="fa fa-users" style="font-size:24px; margin-bottom:8px; display:block;"></i>
                        No staff members found matching the selected criteria.
                    </td>
                </tr>
            <?php } else {
                foreach ($staff as $s) {
                    $sFullDays = 0;
                    $sWorkDays = 0;
            ?>
                <tr>
                    <td class="mcs-name">
                        <div style="font-weight:700; color:#0f172a;"><?php echo html_escape($s->name . ' ' . $s->surname); ?></div>
                        <div style="font-size:10.5px; color:#64748b;"><?php echo html_escape($s->employee_id); ?></div>
                    </td>
                    <td style="color:#475569; font-size:11.5px;"><?php echo html_escape($s->role_name); ?></td>
                    <?php for ($d = 1; $d <= $days_in_month; $d++) {
                        $day_date = sprintf('%04d-%02d-%02d', $year, $month, $d);
                        $is_off   = isset($holiday_dates[$day_date]);

                        if (!$is_off) {
                            $sWorkDays++;
                        }

                        if ($is_off) {
                            echo '<td class="mcs-hol"><span class="mcs-pill-hol" title="' . date('l, d M Y', strtotime($day_date)) . ' - Off / Holiday">H</span></td>';
                            continue;
                        }

                        if (isset($map[$s->staff_id][$day_date])) {
                            $rec = $map[$s->staff_id][$day_date];
                            $u = ($rec['uniform'] === 'yes');
                            $i = ($rec['id_card'] === 'yes');
                            $l = ($rec['lesson_plan'] === 'yes');
                            $p = ($rec['phone_handover'] === 'yes');
                            $yesCount = ($u?1:0) + ($i?1:0) + ($l?1:0) + ($p?1:0);

                            if ($yesCount === 4) {
                                $sFullDays++;
                                $pillClass = 'mcs-pill-yes';
                                $pillIcon  = '&#10003;';
                                $pillTitle = '100% Compliant';
                            } elseif ($yesCount > 0) {
                                $pillClass = 'mcs-pill-partial';
                                $pillIcon  = $yesCount . '/4';
                                $pillTitle = "Partially Compliant ({$yesCount}/4)";
                            } else {
                                $pillClass = 'mcs-pill-no';
                                $pillIcon  = '&times;';
                                $pillTitle = 'Non-Compliant (0/4)';
                            }

                            // Tooltip / popover content
                            $popoverContent = '<ul class="mcs-popover-list">'
                                . '<li><span>Uniform</span><b style="color:' . ($u ? '#16a34a' : '#dc2626') . '">' . ($u ? 'Yes' : 'No') . '</b></li>'
                                . '<li><span>ID Card</span><b style="color:' . ($i ? '#16a34a' : '#dc2626') . '">' . ($i ? 'Yes' : 'No') . '</b></li>'
                                . '<li><span>Lesson Plan</span><b style="color:' . ($l ? '#16a34a' : '#dc2626') . '">' . ($l ? 'Yes' : 'No') . '</b></li>'
                                . '<li><span>Phone Handover</span><b style="color:' . ($p ? '#16a34a' : '#dc2626') . '">' . ($p ? 'Yes' : 'No') . '</b></li>';
                            if (!empty($rec['remark'])) {
                                $popoverContent .= '<li><span>Remark</span><i>' . html_escape($rec['remark']) . '</i></li>';
                            }
                            $popoverContent .= '</ul>';

                            echo '<td><span class="mcs-pill ' . $pillClass . '" data-toggle="popover" data-trigger="hover focus" data-placement="top" data-html="true" data-title="' . date('d M Y (D)', strtotime($day_date)) . '" data-content="' . html_escape($popoverContent) . '">' . $pillIcon . '</span></td>';
                        } else {
                            echo '<td><span class="mcs-pill-empty" title="' . date('d M Y', strtotime($day_date)) . ' - No record">&ndash;</span></td>';
                        }
                    } 
                    
                    $scorePct = ($sWorkDays > 0) ? round(($sFullDays / $sWorkDays) * 100, 1) : 0;
                    $scoreBadge = ($scorePct >= 80) ? '#16a34a' : (($scorePct >= 50) ? '#d97706' : '#dc2626');
                    ?>
                    <td class="mcs-tot"><?php echo $sFullDays; ?> / <?php echo $sWorkDays; ?></td>
                    <td class="mcs-tot" style="color:<?php echo $scoreBadge; ?>;"><?php echo $scorePct; ?>%</td>
                </tr>
            <?php } } ?>
        </tbody>
    </table>
</div>

<script>
    // Initialize popovers for the rendered sheet
    if (typeof $.fn.popover !== 'undefined') {
        $('[data-toggle="popover"]').popover({
            container: 'body'
        });
    }
</script>
