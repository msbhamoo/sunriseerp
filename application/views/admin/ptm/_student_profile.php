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
            <img src="<?php echo base_url($image); ?>" alt="student" style="max-height:100px; max-width:100px; border-radius:50%; border:2px solid #ddd;">
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
            <!-- CBSE Exam Summary -->
            <div style="display: flex; align-items: center; justify-content: space-between; margin-top: 15px; margin-bottom: 8px;">
                <h5 style="font-weight: bold; margin: 0; color: #1e293b; display: flex; align-items: center; gap: 6px;">
                    <i class="fa fa-graduation-cap" style="color: #0284c7;"></i> CBSE Exam Performance & Marks
                </h5>
                <span style="font-size: 11px; color: #64748b;"><i class="fa fa-info-circle"></i> Click on any exam row to view subject-wise marks</span>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered mb0" style="font-size: 12px; border-collapse: separate; border-spacing: 0;">
                    <thead>
                        <tr style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%); color: #ffffff;">
                            <th style="width: 32px; text-align: center;"><i class="fa fa-angle-down"></i></th>
                            <th>EXAM NAME</th>
                            <th style="text-align: center;">SUBJECTS</th>
                            <th style="text-align: center;">TOTAL MARKS</th>
                            <th style="text-align: center;">OBTAINED MARKS</th>
                            <th>PERCENTAGE / GRADE</th>
                        </tr>
                    </thead>
                    <tbody>
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

                                foreach ($cbse_exams as $idx => $exam) {
                                    $pct = isset($exam['percentage']) ? $exam['percentage'] : 0;
                                    if ($pct >= 90) {
                                        $badge_bg = '#dcfce7'; $badge_color = '#16a34a'; $grade_label = 'A+';
                                    } elseif ($pct >= 75) {
                                        $badge_bg = '#e0f2fe'; $badge_color = '#0284c7'; $grade_label = 'A';
                                    } elseif ($pct >= 60) {
                                        $badge_bg = '#fef3c7'; $badge_color = '#d97706'; $grade_label = 'B';
                                    } elseif ($pct >= 45) {
                                        $badge_bg = '#ffedd5'; $badge_color = '#ea580c'; $grade_label = 'C';
                                    } else {
                                        $badge_bg = '#fee2e2'; $badge_color = '#dc2626'; $grade_label = 'D';
                                    }
                                    if (!empty($exam['grade']) && $exam['grade'] != '-') {
                                        $grade_label = $exam['grade'];
                                    }
                                    $has_subjects = !empty($exam['subjects']);
                                    $row_id = 'exam_detail_' . $idx . '_' . $exam['exam_id'];
                        ?>
                        <tr class="exam-summary-row" data-target="#<?php echo $row_id; ?>" style="cursor: pointer; background: #ffffff; transition: background 0.15s ease;" title="Click to view subject marks">
                            <td style="text-align: center; vertical-align: middle; color: #0284c7; font-weight: bold;">
                                <i class="fa fa-chevron-right exam-toggle-icon" id="icon_<?php echo $row_id; ?>"></i>
                            </td>
                            <td style="vertical-align: middle;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <strong style="color: #0f172a; font-size: 13px;"><?php echo htmlspecialchars($exam['exam_name']); ?></strong>
                                    <?php if (isset($exam['is_publish']) && $exam['is_publish'] == 1): ?>
                                        <span class="label label-success" style="font-size: 9px; padding: 2px 6px;"><i class="fa fa-check-circle"></i> Published</span>
                                    <?php else: ?>
                                        <span class="label label-default" style="font-size: 9px; padding: 2px 6px; background: #e2e8f0; color: #475569;">Draft</span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td style="text-align: center; vertical-align: middle; font-weight: 600; color: #334155;">
                                <?php echo isset($exam['subject_count']) ? $exam['subject_count'] : '-'; ?>
                            </td>
                            <td style="text-align: center; vertical-align: middle; font-weight: 700; color: #475569;">
                                <?php echo $exam['total_marks']; ?>
                            </td>
                            <td style="text-align: center; vertical-align: middle; font-weight: 800; color: #0284c7; font-size: 13px;">
                                <?php echo $exam['obtained_marks']; ?>
                            </td>
                            <td style="vertical-align: middle;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <div style="flex: 1; height: 8px; background: #e2e8f0; border-radius: 4px; min-width: 60px;">
                                        <div style="width: <?php echo min($pct, 100); ?>%; height: 100%; background-color: <?php echo $badge_color; ?>; border-radius: 4px;"></div>
                                    </div>
                                    <span class="badge" style="background: <?php echo $badge_bg; ?>; color: <?php echo $badge_color; ?>; font-weight: 700; font-size: 11px;">
                                        <?php echo $pct; ?>% (<?php echo $grade_label; ?>)
                                    </span>
                                </div>
                            </td>
                        </tr>

                        <!-- Expandable Subject Marks Breakdown Row -->
                        <tr id="<?php echo $row_id; ?>" class="exam-detail-row" style="display: none; background: #f8fafc;">
                            <td colspan="6" style="padding: 12px 16px; border-top: none; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">
                                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; padding: 10px 14px;">
                                    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px; padding-bottom: 6px; border-bottom: 1px solid #f1f5f9;">
                                        <span style="font-weight: 700; font-size: 12px; color: #334155;">
                                            <i class="fa fa-book text-info"></i> Subject Marks Breakdown &mdash; <?php echo htmlspecialchars($exam['exam_name']); ?>
                                        </span>
                                        <?php if (!empty($exam['rank'])): ?>
                                            <span class="label label-warning" style="font-size: 11px;"><i class="fa fa-trophy"></i> Rank: <?php echo $exam['rank']; ?></span>
                                        <?php endif; ?>
                                    </div>

                                    <?php if ($has_subjects): ?>
                                    <table class="table table-bordered table-condensed mb0" style="font-size: 11px; margin-bottom: 0;">
                                        <thead>
                                            <tr style="background: #f1f5f9; color: #475569; font-weight: 700;">
                                                <th style="width: 40%;">SUBJECT</th>
                                                <?php 
                                                if (!empty($exam['assessments_headers'])) {
                                                    foreach ($exam['assessments_headers'] as $hdr) {
                                                        $hdr_code = !empty($hdr->code) ? " (" . $hdr->code . ")" : "";
                                                        echo "<th class='text-center'>" . htmlspecialchars($hdr->name . $hdr_code) . "<br><small style='color:#64748b;'>(Max " . $hdr->maximum_marks . ")</small></th>";
                                                    }
                                                }
                                                ?>
                                                <th class="text-center" style="width: 15%; background: #e2e8f0;">TOTAL</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($exam['subjects'] as $sub): ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($sub['subject_name']); ?></strong>
                                                    <?php if (!empty($sub['subject_code'])): ?>
                                                        <span class="text-muted">(<?php echo htmlspecialchars($sub['subject_code']); ?>)</span>
                                                    <?php endif; ?>
                                                </td>
                                                <?php 
                                                if (!empty($exam['assessments_headers'])) {
                                                    foreach ($exam['assessments_headers'] as $hdr) {
                                                        $val_str = "N/A";
                                                        $cell_style = "color:#94a3b8;";
                                                        if (!empty($sub['assessments'])) {
                                                            foreach ($sub['assessments'] as $a_item) {
                                                                if ($a_item['assessment_type_id'] == $hdr->id) {
                                                                    if (!empty($a_item['is_absent'])) {
                                                                        $val_str = "<span class='text-danger font-weight-bold'>ABS</span>";
                                                                        $cell_style = "";
                                                                    } elseif ($a_item['marks'] !== "N/A") {
                                                                        $val_str = "<strong>" . $a_item['marks'] . "</strong>";
                                                                        $cell_style = "color:#0f172a;";
                                                                    }
                                                                    break;
                                                                }
                                                            }
                                                        }
                                                        echo "<td class='text-center' style='vertical-align:middle; " . $cell_style . "'>" . $val_str . "</td>";
                                                    }
                                                }
                                                ?>
                                                <td class="text-center" style="vertical-align: middle; background: #f8fafc; font-weight: 800; color: #0284c7;">
                                                    <?php echo $sub['subject_total']; ?>
                                                    <?php if ($sub['subject_max'] > 0): ?>
                                                        <small style="color: #64748b; font-weight: normal;"> / <?php echo $sub['subject_max']; ?></small>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                            <tr style="background: #f1f5f9; font-weight: 700;">
                                                <td colspan="<?php echo 1 + (!empty($exam['assessments_headers']) ? count($exam['assessments_headers']) : 0); ?>" class="text-right">
                                                    OVERALL TOTAL:
                                                </td>
                                                <td class="text-center" style="font-size: 12px; color: #0f172a; font-weight: 800; background: #e2e8f0;">
                                                    <?php echo $exam['obtained_marks'] . " / " . $exam['total_marks']; ?>
                                                    <div style="font-size: 10px; color: <?php echo $badge_color; ?>;"><?php echo $pct; ?>% (<?php echo $grade_label; ?>)</div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <?php else: ?>
                                        <p class="text-muted text-center" style="margin: 10px 0;"><i>No subject marks details available for this exam.</i></p>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php 
                                }
                            } else {
                                echo "<tr><td colspan='6' class='text-center text-muted' style='padding: 20px;'><i>No CBSE Exam records found for this student.</i></td></tr>";
                            }
                        ?>
                    </tbody>
                </table>
            </div>

            <script>
            $(document).off('click', '.exam-summary-row').on('click', '.exam-summary-row', function(e) {
                var targetId = $(this).data('target');
                var $detailRow = $(targetId);
                var $icon = $(this).find('.exam-toggle-icon');

                if ($detailRow.is(':visible')) {
                    $detailRow.slideUp(150);
                    $icon.removeClass('fa-chevron-down').addClass('fa-chevron-right');
                    $(this).css('background', '#ffffff');
                } else {
                    $('.exam-detail-row').not($detailRow).slideUp(150);
                    $('.exam-toggle-icon').not($icon).removeClass('fa-chevron-down').addClass('fa-chevron-right');
                    $('.exam-summary-row').not(this).css('background', '#ffffff');

                    $detailRow.slideDown(150);
                    $icon.removeClass('fa-chevron-right').addClass('fa-chevron-down');
                    $(this).css('background', '#f0f9ff');
                }
            });
            </script>
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
