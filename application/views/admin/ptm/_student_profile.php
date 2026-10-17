<div class="row">
    <div class="col-md-12">
        <button type="button" class="btn btn-success btn-sm print_report" onclick="printDiv('print_ptm_report')" style="margin-bottom: 15px;"><i class="fa fa-print"></i> Print</button>
    </div>
</div>

<div id="print_ptm_report" style="background:#fff; padding:20px; border:1px solid #ddd; border-radius:5px;">
    <!-- School Header -->
    <div class="row" style="border-bottom:1px solid #eee; padding-bottom:15px; margin-bottom:20px;">
        <div class="col-xs-3 col-sm-2 text-center">
            <img src="<?php echo base_url('uploads/school_content/logo/' . $settinglist[0]['image']); ?>" alt="logo" style="max-height:80px; max-width:100%;">
        </div>
        <div class="col-xs-9 col-sm-10 text-center">
            <h3 style="margin-top:0; margin-bottom:5px; font-weight:bold; color:#333;"><?php echo $settinglist[0]['name']; ?></h3>
            <p style="margin:0; font-size:12px; color:#555;">
                <?php echo $settinglist[0]['address']; ?><br>
                Email: <?php echo $settinglist[0]['email']; ?> | Mobile No: <?php echo $settinglist[0]['phone']; ?> | Website: <?php echo $settinglist[0]['dise_code']; /* Using as placeholder if no website */ ?><br>
                <strong>Session: <?php echo $settinglist[0]['session']; ?></strong>
            </p>
            <h4 style="color:#007bff; font-weight:bold; margin-top:10px;">PTM Report</h4>
        </div>
    </div>

    <!-- Student Details -->
    <div class="row" style="margin-bottom:20px;">
        <div class="col-sm-10 col-xs-9">
            <table style="width:100%; font-size:12px;">
                <tr>
                    <td width="20%"><strong>Student Name:</strong></td><td width="30%"><?php echo $student['firstname'] . ' ' . $student['lastname']; ?></td>
                    <td width="20%"><strong>Roll No.:</strong></td><td width="30%"><?php echo $student['roll_no']; ?></td>
                </tr>
                <tr>
                    <td><strong>Father's Name:</strong></td><td><?php echo $student['father_name']; ?></td>
                    <td><strong>Class & Section:</strong></td><td><?php echo $student['class'] . ' (' . $student['section'] . ')'; ?></td>
                </tr>
                <tr>
                    <td><strong>Mother's Name:</strong></td><td><?php echo $student['mother_name']; ?></td>
                    <td><strong>Date of Birth:</strong></td><td><?php echo !empty($student['dob']) ? date('M d, Y', strtotime($student['dob'])) : ''; ?></td>
                </tr>
                <tr>
                    <td><strong>Gender:</strong></td><td><?php echo $student['gender']; ?></td>
                    <td><strong>Address:</strong></td><td><?php echo $student['current_address']; ?></td>
                </tr>
                <tr>
                    <td><strong>Sr No.:</strong></td><td><?php echo $student['admission_no']; ?></td>
                    <td><strong>Telephone No.:</strong></td><td><?php echo $student['mobileno']; ?></td>
                </tr>
            </table>
        </div>
        <div class="col-sm-2 col-xs-3 text-right">
            <?php 
                $image = $student['image'] ? $student['image'] : 'uploads/student_images/no_image.png';
            ?>
            <img src="<?php echo base_url($image); ?>" alt="" onerror="this.onerror=null;this.src='<?php echo base_url('uploads/student_images/no_image.png'); ?>';" style="width:90px; height:90px; object-fit:cover; border-radius:50%; border:2px solid #e2e8f0; background:#f1f5f9;">
        </div>
    </div>

    <?php if ($this->rbac->hasPrivilege('collect_fees', 'can_view')) { ?>
    <div class="row">
        <div class="col-md-12">
            <!-- Fee Summary -->
            <h5 style="font-weight:bold; margin-top:20px;">Fee Summary (Total)</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" style="font-size:12px;">
                    <thead style="background-color:#4CAF50; color:white;">
                        <tr>
                            <th>TYPE</th>
                            <th>TOTAL FEES</th>
                            <th>COLLECTED</th>
                            <th>DUE FEE</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $total_fee = 0; $total_paid = 0;
                            if (!empty($fees)) {
                                foreach ($fees as $fee_group) {
                                    if (!empty($fee_group->fees)) {
                                        foreach ($fee_group->fees as $fee) {
                                            $total_fee += $fee->amount;
                                            $paid = 0;
                                            if (is_string($fee->amount_detail)) {
                                                $amount_detail = json_decode($fee->amount_detail);
                                                if (is_object($amount_detail) || is_array($amount_detail)) {
                                                    foreach ($amount_detail as $ad) {
                                                        $paid += $ad->amount;
                                                    }
                                                }
                                            }
                                            $total_paid += $paid;
                        ?>
                        <tr>
                            <td><?php echo $fee->name ?? 'Fee'; ?> (<?php echo $fee->type ?? ''; ?>)</td>
                            <td><?php echo $settinglist[0]['currency_symbol'] . $fee->amount; ?></td>
                            <td><?php echo $settinglist[0]['currency_symbol'] . $paid; ?></td>
                            <td><?php echo $settinglist[0]['currency_symbol'] . ($fee->amount - $paid); ?></td>
                        </tr>
                        <?php 
                                        }
                                    }
                                }
                            } else {
                                echo "<tr><td colspan='4' class='text-center'>No fee data available.</td></tr>";
                            }
                        ?>
                        <tr style="font-weight:bold; background-color:#f9f9f9;">
                            <td>TOTAL</td>
                            <td><?php echo $settinglist[0]['currency_symbol'] . number_format($total_fee, 2); ?></td>
                            <td><?php echo $settinglist[0]['currency_symbol'] . number_format($total_paid, 2); ?></td>
                            <td><?php echo $settinglist[0]['currency_symbol'] . number_format($total_fee - $total_paid, 2); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php } ?>
    
    <div class="row">
        <div class="col-md-12">
            <!-- CBSE Exam Summary: consolidated subject x exam matrix -->
            <?php
            if (!empty($cbse_exams)) {
                usort($cbse_exams, function($a, $b) {
                    $extractOrder = function($name) {
                        $romanMap = ['VIII' => 8, 'VII' => 7, 'VI' => 6, 'IV' => 4, 'V' => 5, 'III' => 3, 'II' => 2, 'I' => 1];
                        $prefixPriority = 50;
                        if (stripos($name, 'SBBT') !== false || stripos($name, 'UNIT') !== false || stripos($name, 'PERIODIC') !== false || stripos($name, 'PT') !== false) {
                            $prefixPriority = 10;
                        } elseif (stripos($name, 'TERM') !== false || stripos($name, 'MID') !== false || stripos($name, 'HALF') !== false) {
                            $prefixPriority = 20;
                        } elseif (stripos($name, 'PRE') !== false || stripos($name, 'MOCK') !== false) {
                            $prefixPriority = 30;
                        } elseif (stripos($name, 'ANNUAL') !== false || stripos($name, 'FINAL') !== false) {
                            $prefixPriority = 40;
                        }
                        $num = 99;
                        foreach ($romanMap as $r => $val) {
                            if (preg_match('/\b' . $r . '\b/i', $name)) {
                                $num = $val;
                                break;
                            }
                        }
                        if ($num === 99 && preg_match('/\b(\d+)\b/', $name, $matches)) {
                            $num = (int)$matches[1];
                        }
                        return ($prefixPriority * 100) + $num;
                    };
                    $orderA = $extractOrder($a['exam_name']);
                    $orderB = $extractOrder($b['exam_name']);
                    if ($orderA === $orderB) {
                        return (int)$a['exam_id'] - (int)$b['exam_id'];
                    }
                    return $orderA - $orderB;
                });
            }

            // Build subject rows and per-exam cells; only marks actually entered count toward totals
            $mx_subjects = [];
            $mx_cells = [];
            $mx_totals = [];
            if (!empty($cbse_exams)) {
                foreach ($cbse_exams as $ei => $exam) {
                    $mx_totals[$ei] = ['obt' => 0, 'max' => 0];
                    if (empty($exam['subjects'])) continue;
                    foreach ($exam['subjects'] as $sub) {
                        $key = strtoupper(trim($sub['subject_name'])) . '|' . strtoupper(trim(isset($sub['subject_code']) ? $sub['subject_code'] : ''));
                        if (!isset($mx_subjects[$key])) {
                            $mx_subjects[$key] = ['name' => $sub['subject_name'], 'code' => isset($sub['subject_code']) ? $sub['subject_code'] : ''];
                        }
                        $entered = false;
                        $absent = false;
                        if (!empty($sub['assessments'])) {
                            foreach ($sub['assessments'] as $a_item) {
                                if (!empty($a_item['is_absent'])) { $absent = true; }
                                elseif (isset($a_item['marks']) && $a_item['marks'] !== 'N/A' && $a_item['marks'] !== '' && $a_item['marks'] !== null) { $entered = true; }
                            }
                        }
                        if ($entered || $absent) {
                            $mx_cells[$key][$ei] = ['obt' => $sub['subject_total'], 'max' => $sub['subject_max'], 'abs' => ($absent && !$entered)];
                            $mx_totals[$ei]['obt'] += (float)$sub['subject_total'];
                            $mx_totals[$ei]['max'] += (float)$sub['subject_max'];
                        }
                    }
                }
            }
            $fmt = function($n) { return (floor($n) == $n) ? (int)$n : round($n, 2); };
            ?>
            <style>
                .cx-head { display:flex; align-items:center; gap:12px; flex-wrap:wrap; margin:22px 0 12px; }
                .cx-head h5 { margin:0; font-weight:800; font-size:15px; letter-spacing:.4px; text-transform:uppercase; color:#0f172a; }
                .cx-head .cx-sub { font-size:11px; letter-spacing:.6px; text-transform:uppercase; color:#64748b; margin-top:2px; }
                .cx-pill { border:1px solid #e2e8f0; background:#f8fafc; border-radius:7px; padding:3px 10px; font-size:11px; font-weight:700; color:#475569; letter-spacing:.4px; text-transform:uppercase; }
                .cx-wrap { border:1px solid #e2e8f0; border-radius:12px; overflow-x:auto; }
                .cx-table { width:100%; border-collapse:collapse; font-size:13px; min-width:640px; }
                .cx-table th { background:#f8fafc; color:#0f172a; font-weight:800; font-size:11px; letter-spacing:.5px; text-transform:uppercase; text-align:center; padding:14px 10px; border-bottom:1px solid #e2e8f0; vertical-align:middle; }
                .cx-table th.cx-subj { text-align:left; color:#64748b; padding-left:16px; position:sticky; left:0; z-index:2; }
                .cx-table td { padding:14px 10px; text-align:center; border-bottom:1px solid #f1f5f9; color:#0f172a; vertical-align:middle; }
                .cx-table td.cx-subj { text-align:left; padding-left:16px; font-weight:800; text-transform:uppercase; background:#fff; position:sticky; left:0; z-index:1; min-width:170px; }
                .cx-table td.cx-subj small { font-weight:500; color:#64748b; }
                .cx-dash { color:#cbd5e1; }
                .cx-max { color:#64748b; font-size:11px; }
                .cx-draft { display:inline-block; margin-top:6px; background:#e2e8f0; color:#475569; border-radius:6px; padding:2px 8px; font-size:10px; }
                .cx-pub { display:inline-block; margin-top:6px; background:#dcfce7; color:#16a34a; border-radius:6px; padding:2px 8px; font-size:10px; }
                .cx-table tr.cx-total td { background:#f8fafc; font-weight:800; border-top:1px solid #e2e8f0; }
                .cx-table tr.cx-total td.cx-subj, .cx-table tr.cx-pct td.cx-subj { background:#f8fafc; }
                .cx-table tr.cx-pct td { background:#f8fafc; font-weight:800; }
            </style>
            <div class="cx-head">
                <i class="fa fa-graduation-cap" style="font-size:20px; color:#0f3d3e;"></i>
                <div>
                    <h5>Academic Performance &mdash; Consolidated Exams</h5>
                    <div class="cx-sub">Consolidated subject-wise marks across all evaluated examinations</div>
                </div>
                <span class="cx-pill"><?php echo count((array)$cbse_exams); ?> Exams Evaluated</span>
            </div>

            <?php if (empty($cbse_exams)) { ?>
                <p class="text-muted text-center" style="padding:20px;"><i>No CBSE Exam records found for this student.</i></p>
            <?php } else { ?>
            <div class="cx-wrap">
                <table class="cx-table">
                    <thead>
                        <tr>
                            <th class="cx-subj">Subject</th>
                            <?php foreach ($cbse_exams as $exam) { ?>
                                <th>
                                    <?php echo htmlspecialchars($exam['exam_name']); ?><br>
                                    <?php if (!empty($exam['is_publish'])) { ?><span class="cx-pub">Published</span><?php } else { ?><span class="cx-draft">Draft</span><?php } ?>
                                </th>
                            <?php } ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($mx_subjects)) { ?>
                            <tr><td colspan="<?php echo 1 + count($cbse_exams); ?>" class="text-muted"><i>No subject marks available.</i></td></tr>
                        <?php } ?>
                        <?php foreach ($mx_subjects as $key => $s) { ?>
                            <tr>
                                <td class="cx-subj"><?php echo htmlspecialchars($s['name']); ?><?php if ($s['code'] !== '') { ?> <small>(<?php echo htmlspecialchars($s['code']); ?>)</small><?php } ?></td>
                                <?php foreach ($cbse_exams as $ei => $exam) {
                                    $c = isset($mx_cells[$key][$ei]) ? $mx_cells[$key][$ei] : null; ?>
                                    <td>
                                        <?php if (!$c) { ?>
                                            <span class="cx-dash">&mdash;</span>
                                        <?php } elseif ($c['abs']) { ?>
                                            <span class="text-danger" style="font-weight:700;">ABS</span>
                                        <?php } else { ?>
                                            <strong><?php echo $fmt((float)$c['obt']); ?></strong> <span class="cx-max">/ <?php echo $fmt((float)$c['max']); ?></span>
                                        <?php } ?>
                                    </td>
                                <?php } ?>
                            </tr>
                        <?php } ?>
                        <tr class="cx-total">
                            <td class="cx-subj"><i class="fa fa-calculator"></i> Total Marks</td>
                            <?php foreach ($cbse_exams as $ei => $exam) { ?>
                                <td><?php echo $fmt($mx_totals[$ei]['obt']); ?> <span class="cx-max">/ <?php echo $fmt($mx_totals[$ei]['max']); ?></span></td>
                            <?php } ?>
                        </tr>
                        <tr class="cx-pct">
                            <td class="cx-subj">% Percentage (%)</td>
                            <?php foreach ($cbse_exams as $ei => $exam) {
                                $p = $mx_totals[$ei]['max'] > 0 ? round($mx_totals[$ei]['obt'] / $mx_totals[$ei]['max'] * 100, 2) : null;
                                $pc = $p === null ? '#94a3b8' : ($p >= 75 ? '#16a34a' : ($p >= 50 ? '#0284c7' : ($p >= 33 ? '#d97706' : '#dc2626'))); ?>
                                <td style="color:<?php echo $pc; ?>;"><?php echo $p === null ? '&mdash;' : $p . '%'; ?></td>
                            <?php } ?>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php } ?>
        </div>
    </div>

    
    <div class="row">
        <div class="col-md-12">
            <h5 style="font-weight:bold; margin-top:20px;">Current PTM Discussion & Remarks</h5>
            <table class="table table-bordered" style="font-size:12px;">
                <tbody>
                    <?php if ($ptm_remarks): ?>
                    <tr>
                        <th width="20%">Status</th>
                        <td><?php echo ucfirst($ptm_remarks['status']); ?></td>
                        <th width="20%">Attendee</th>
                        <td><?php echo ucfirst($ptm_remarks['attendee_type']); ?></td>
                    </tr>
                    <tr>
                        <th>Discussion Points</th>
                        <td colspan="3"><?php echo nl2br(htmlspecialchars($ptm_remarks['discussion_points'])); ?></td>
                    </tr>
                    <tr>
                        <th>Parent Remarks</th>
                        <td><?php echo nl2br(htmlspecialchars($ptm_remarks['parent_remarks'])); ?></td>
                        <th>Teacher Remarks</th>
                        <td><?php echo nl2br(htmlspecialchars($ptm_remarks['teacher_remarks'])); ?></td>
                    </tr>
                    <tr>
                        <th>Concerns</th>
                        <td colspan="3">
                            <?php 
                                $concerns = [];
                                if (!empty($ptm_remarks['concerns_academics'])) $concerns[] = "Academics";
                                if (!empty($ptm_remarks['concerns_attendance'])) $concerns[] = "Attendance";
                                if (!empty($ptm_remarks['concerns_behavior'])) $concerns[] = "Behavior/Discipline";
                                echo !empty($concerns) ? implode(', ', $concerns) : '-';
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Action Items</th>
                        <td colspan="3"><?php echo nl2br(htmlspecialchars(isset($ptm_remarks['action_items']) ? $ptm_remarks['action_items'] : (isset($ptm_remarks['agreed_action_items']) ? $ptm_remarks['agreed_action_items'] : ''))); ?></td>
                    </tr>
                    <tr>
                        <th>Follow-up Status</th>
                        <td colspan="3">
                            <?php if (!empty($ptm_remarks['followup_required']) && $ptm_remarks['followup_required'] == 1): ?>
                                <span class="label label-warning" style="font-size: 11px;">Follow-up Required</span>
                                <?php if (!empty($ptm_remarks['assigned_staff_name'])): ?>
                                    &nbsp;|&nbsp; <strong>Assigned Staff:</strong> <?php echo $ptm_remarks['assigned_staff_name'] . ' ' . $ptm_remarks['assigned_staff_surname'] . ($ptm_remarks['employee_id'] ? ' (' . $ptm_remarks['employee_id'] . ')' : ''); ?>
                                <?php endif; ?>
                                <?php if (!empty($ptm_remarks['followup_date'])): ?>
                                    &nbsp;|&nbsp; <strong>Target Date:</strong> <?php echo date('d-m-Y', strtotime($ptm_remarks['followup_date'])); ?>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted">No follow-up required</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php else: ?>
                    <tr><td colspan="4" class="text-center text-muted">No remarks entered for this PTM yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <?php if (!empty($previous_ptm_remarks)): ?>
    <div class="row">
        <div class="col-md-12">
            <h5 style="font-weight:bold; margin-top:10px; color:#666;">Previous PTM History</h5>
            <?php foreach ($previous_ptm_remarks as $prev): ?>
            <div style="background:#f9f9f9; padding:10px; border:1px solid #eee; margin-bottom:10px; font-size:12px;">
                <strong><?php echo $prev['title']; ?> (<?php echo date('M d, Y', strtotime($prev['ptm_date'])); ?>)</strong><br>
                <strong>Discussion:</strong> <?php echo htmlspecialchars($prev['discussion_points'] ?: '-'); ?><br>
                <strong>Action Items:</strong> <?php echo htmlspecialchars($prev['agreed_action_items'] ?: '-'); ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
function printDiv(divId) {
    var content = document.getElementById(divId).innerHTML;
    var printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Print PTM Report</title>');
    printWindow.document.write('<link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">');
    printWindow.document.write('<style>body { padding: 20px; font-family: Arial, sans-serif; }</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(content);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    
    // Allow styles to load before printing
    setTimeout(function() {
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }, 500);
}
</script>
