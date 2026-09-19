<?php 
$total_students = count($students);
$present_count = 0;
$absent_count = 0;
$followup_count = 0;

$class_stats = [];

foreach ($students as $stu) {
    $sess_id = $stu['student_session_id'];
    $att = isset($attendances[$sess_id]) ? $attendances[$sess_id] : null;
    $is_present = ($att && isset($att['status']) && $att['status'] == 'present');
    
    if ($is_present) {
        $present_count++;
    } else {
        $absent_count++;
    }

    if ($att && !empty($att['followup_required']) && $att['followup_required'] == 1) {
        $followup_count++;
    }

    $c_name = $stu['class'] . ' (' . $stu['section'] . ')';
    if (!isset($class_stats[$c_name])) {
        $class_stats[$c_name] = ['total' => 0, 'present' => 0, 'absent' => 0];
    }
    $class_stats[$c_name]['total']++;
    if ($is_present) {
        $class_stats[$c_name]['present']++;
    } else {
        $class_stats[$c_name]['absent']++;
    }
}

$turnout_rate = $total_students > 0 ? round(($present_count / $total_students) * 100, 1) : 0;
?>

<style type="text/css">
    .kpi-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 16px 18px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 16px;
    }
    .kpi-icon-box {
        width: 46px;
        height: 46px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .kpi-title {
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .kpi-value {
        font-size: 20px;
        font-weight: 800;
        color: #0f172a;
        margin-top: 2px;
    }
    .mobile-call-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px !important;
        font-size: 12px !important;
        border-radius: 6px !important;
        font-weight: 600;
        text-decoration: none !important;
    }
    @media (max-width: 767px) {
        .kpi-card {
            padding: 10px 12px;
            gap: 10px;
            margin-bottom: 10px;
        }
        .kpi-icon-box {
            width: 36px;
            height: 36px;
            font-size: 16px;
            border-radius: 8px;
        }
        .kpi-title {
            font-size: 10px;
        }
        .kpi-value {
            font-size: 16px;
        }
    }
</style>

<!-- KPI Metrics Summary Row -->
<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-6">
        <div class="kpi-card">
            <div class="kpi-icon-box" style="background: #e0f2fe; color: #0284c7;">
                <i class="fa fa-users"></i>
            </div>
            <div>
                <div class="kpi-title">Total Roster</div>
                <div class="kpi-value"><?php echo $total_students; ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-6">
        <div class="kpi-card">
            <div class="kpi-icon-box" style="background: #dcfce7; color: #16a34a;">
                <i class="fa fa-check-circle"></i>
            </div>
            <div>
                <div class="kpi-title">Present</div>
                <div class="kpi-value"><?php echo $present_count; ?> <small style="font-size: 12px; font-weight: 600; color: #16a34a;">(<?php echo $turnout_rate; ?>%)</small></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-6">
        <div class="kpi-card">
            <div class="kpi-icon-box" style="background: #fee2e2; color: #dc2626;">
                <i class="fa fa-times-circle"></i>
            </div>
            <div>
                <div class="kpi-title">Absent</div>
                <div class="kpi-value"><?php echo $absent_count; ?></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 col-xs-6">
        <div class="kpi-card">
            <div class="kpi-icon-box" style="background: #fef3c7; color: #d97706;">
                <i class="fa fa-flag"></i>
            </div>
            <div>
                <div class="kpi-title">Follow-ups</div>
                <div class="kpi-value"><?php echo $followup_count; ?></div>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Report Content Section -->
<div class="sc-card">
    <div class="sc-card-header">
        <h3 class="sc-card-title">
            <?php 
            if ($report_type == 'followup_report') {
                echo '<i class="fa fa-tasks text-success"></i> Assigned Follow-ups & Action Items';
            } else if ($report_type == 'remarks_report') {
                echo '<i class="fa fa-comments-o text-info"></i> Discussion & Remarks Summary';
            } else if ($report_type == 'analytics_report') {
                echo '<i class="fa fa-bar-chart text-warning"></i> Class-wise Turnout & Analytics';
            } else if ($report_type == 'absentee_report') {
                echo '<i class="fa fa-user-times text-danger"></i> Absentee & Non-Attendee List';
            } else {
                echo '<i class="fa fa-file-text-o text-primary"></i> PTM Attendance & Participation Report';
            }
            ?>
        </h3>
        <span class="text-muted" style="font-size: 13px; font-weight: 600;">
            Meeting: <strong><?php echo htmlspecialchars($ptm['title']); ?></strong> (<?php echo date('d-M-Y', strtotime($ptm['ptm_date'])); ?>)
        </span>
    </div>
    <div class="box-body" style="padding: 20px;">
        <?php if ($report_type == 'analytics_report') { ?>
            <!-- Class-wise Turnout Breakdown -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th>Class & Section</th>
                            <th>Total Students</th>
                            <th>Present Attendees</th>
                            <th>Absent</th>
                            <th style="width: 35%;">Turnout Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($class_stats as $c_name => $c_stat) { 
                            $pct = $c_stat['total'] > 0 ? round(($c_stat['present'] / $c_stat['total']) * 100, 1) : 0;
                            $bar_color = $pct >= 75 ? '#10b981' : ($pct >= 50 ? '#0284c7' : '#f59e0b');
                        ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($c_name); ?></strong></td>
                                <td><?php echo $c_stat['total']; ?></td>
                                <td class="text-success font-weight-bold"><?php echo $c_stat['present']; ?></td>
                                <td class="text-danger"><?php echo $c_stat['absent']; ?></td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div class="progress" style="flex: 1; height: 10px; margin-bottom: 0; background: #e2e8f0; border-radius: 5px;">
                                            <div class="progress-bar" style="width: <?php echo $pct; ?>%; background-color: <?php echo $bar_color; ?>;"></div>
                                        </div>
                                        <span style="font-weight: 700; font-size: 12px; min-width: 45px; color: #1e293b;"><?php echo $pct; ?>%</span>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        <?php } else if ($report_type == 'followup_report') { ?>
            <!-- Follow-up Report -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th>Adm No</th>
                            <th>Student</th>
                            <th>Class</th>
                            <th>Concerns</th>
                            <th>Action Items & Discussion</th>
                            <th>Assigned Staff</th>
                            <th>Follow-up Due</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $has_followups = false;
                        foreach ($students as $stu) {
                            $sess_id = $stu['student_session_id'];
                            $att = isset($attendances[$sess_id]) ? $attendances[$sess_id] : null;
                            if ($att && !empty($att['followup_required']) && $att['followup_required'] == 1) {
                                $has_followups = true;
                                $concerns = [];
                                if (!empty($att['concerns_academics'])) $concerns[] = "Academics";
                                if (!empty($att['concerns_attendance'])) $concerns[] = "Attendance";
                                if (!empty($att['concerns_behavior'])) $concerns[] = "Behavior";
                                if (!empty($att['concerns_discipline'])) $concerns[] = "Discipline";
                            ?>
                                <tr>
                                    <td><?php echo $stu['admission_no']; ?></td>
                                    <td>
                                        <strong><?php echo $stu['firstname'] . ' ' . $stu['lastname']; ?></strong>
                                        <a href="#" class="btn btn-xs btn-info view-snapshot" data-student="<?php echo $stu['id']; ?>" data-session="<?php echo $stu['student_session_id']; ?>" style="margin-left: 4px;"><i class="fa fa-line-chart"></i></a>
                                    </td>
                                    <td><?php echo $stu['class'] . ' (' . $stu['section'] . ')'; ?></td>
                                    <td>
                                        <?php if (!empty($concerns)) { 
                                            foreach($concerns as $c) { ?>
                                                <span class="badge" style="background:#fef3c7;color:#92400e;font-size:11px;"><?php echo $c; ?></span>
                                            <?php } 
                                        } else { echo '-'; } ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($att['action_items'])) { ?>
                                            <div><strong>Action:</strong> <?php echo htmlspecialchars($att['action_items']); ?></div>
                                        <?php } ?>
                                        <?php if (!empty($att['discussion_points'])) { ?>
                                            <div style="font-size: 12px; color: #64748b;"><strong>Notes:</strong> <?php echo htmlspecialchars($att['discussion_points']); ?></div>
                                        <?php } ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($att['assigned_staff_name'])) { ?>
                                            <span class="badge" style="background: #e0f2fe; color: #0284c7;"><?php echo $att['assigned_staff_name'] . ' ' . $att['assigned_staff_surname']; ?></span>
                                        <?php } else { ?>
                                            <span class="text-muted">Unassigned</span>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo !empty($att['followup_date']) ? date('d-M-Y', strtotime($att['followup_date'])) : '-'; ?></td>
                                </tr>
                            <?php }
                        }
                        if (!$has_followups) { ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted" style="padding: 24px;">No follow-up action items recorded for this selection.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        <?php } else if ($report_type == 'remarks_report') { ?>
            <!-- Remarks & Discussions Report -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th>Adm No</th>
                            <th>Student</th>
                            <th>Class</th>
                            <th>Attendee</th>
                            <th>Discussion Points</th>
                            <th>Parent Remarks</th>
                            <th>Teacher Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        foreach ($students as $stu) {
                            $sess_id = $stu['student_session_id'];
                            $att = isset($attendances[$sess_id]) ? $attendances[$sess_id] : null;
                        ?>
                            <tr>
                                <td><?php echo $stu['admission_no']; ?></td>
                                <td>
                                    <strong><?php echo $stu['firstname'] . ' ' . $stu['lastname']; ?></strong>
                                    <a href="#" class="btn btn-xs btn-info view-snapshot" data-student="<?php echo $stu['id']; ?>" data-session="<?php echo $stu['student_session_id']; ?>" style="margin-left: 4px;"><i class="fa fa-line-chart"></i></a>
                                </td>
                                <td><?php echo $stu['class'] . ' (' . $stu['section'] . ')'; ?></td>
                                <td><?php echo ($att && !empty($att['attendee_type'])) ? ucfirst($att['attendee_type']) : '-'; ?></td>
                                <td><?php echo ($att && !empty($att['discussion_points'])) ? nl2br(htmlspecialchars($att['discussion_points'])) : '-'; ?></td>
                                <td><?php echo ($att && !empty($att['parent_remarks'])) ? nl2br(htmlspecialchars($att['parent_remarks'])) : '-'; ?></td>
                                <td><?php echo ($att && !empty($att['teacher_remarks'])) ? nl2br(htmlspecialchars($att['teacher_remarks'])) : '-'; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        <?php } else if ($report_type == 'absentee_report') { ?>
            <!-- Absentee List -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th>Adm No</th>
                            <th>Student Name</th>
                            <th>Class</th>
                            <th>Father Name</th>
                            <th>Father Mobile</th>
                            <th>Mother / Guardian Phone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $absent_found = false;
                        foreach ($students as $stu) {
                            $sess_id = $stu['student_session_id'];
                            $att = isset($attendances[$sess_id]) ? $attendances[$sess_id] : null;
                            $is_present = ($att && isset($att['status']) && $att['status'] == 'present');
                            if (!$is_present) {
                                $absent_found = true;
                            ?>
                                <tr>
                                    <td><?php echo $stu['admission_no']; ?></td>
                                    <td>
                                        <strong><?php echo $stu['firstname'] . ' ' . $stu['lastname']; ?></strong>
                                        <a href="#" class="btn btn-xs btn-info view-snapshot" data-student="<?php echo $stu['id']; ?>" data-session="<?php echo $stu['student_session_id']; ?>" style="margin-left: 4px;"><i class="fa fa-line-chart"></i></a>
                                    </td>
                                    <td><?php echo $stu['class'] . ' (' . $stu['section'] . ')'; ?></td>
                                    <td><?php echo !empty($stu['father_name']) ? htmlspecialchars($stu['father_name']) : '-'; ?></td>
                                    <td>
                                        <?php if (!empty($stu['father_phone'])) { ?>
                                            <a href="tel:<?php echo $stu['father_phone']; ?>" class="btn btn-xs btn-default mobile-call-btn" style="border: 1px solid #bbf7d0; background: #f0fdf4; color: #166534;"><i class="fa fa-phone" style="color: #16a34a;"></i> <?php echo $stu['father_phone']; ?></a>
                                        <?php } else { echo '-'; } ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($stu['guardian_phone'])) { ?>
                                            <a href="tel:<?php echo $stu['guardian_phone']; ?>" class="btn btn-xs btn-default mobile-call-btn" style="border: 1px solid #bae6fd; background: #f0f9ff; color: #0369a1;"><i class="fa fa-phone" style="color: #0284c7;"></i> <?php echo $stu['guardian_phone']; ?></a>
                                        <?php } else if (!empty($stu['mobileno'])) { ?>
                                            <a href="tel:<?php echo $stu['mobileno']; ?>" class="btn btn-xs btn-default mobile-call-btn" style="border: 1px solid #bae6fd; background: #f0f9ff; color: #0369a1;"><i class="fa fa-phone" style="color: #0284c7;"></i> <?php echo $stu['mobileno']; ?></a>
                                        <?php } else { echo '-'; } ?>
                                    </td>
                                    <td>
                                        <span class="badge" style="background:#fee2e2;color:#dc2626;"><i class="fa fa-times"></i> Absent</span>
                                    </td>
                                </tr>
                            <?php }
                        } 
                        if (!$absent_found) { ?>
                            <tr>
                                <td colspan="7" class="text-center text-success" style="padding: 24px;">
                                    <i class="fa fa-check-circle" style="font-size: 24px;"></i><br>
                                    All parents attended this PTM meeting!
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

        <?php } else { ?>
            <!-- Standard Attendance & Participation Report -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th>Adm No</th>
                            <th>Student Name</th>
                            <th>Class & Section</th>
                            <th>Status</th>
                            <th>Attendee</th>
                            <th>Timing</th>
                            <th>Snapshot</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $stu) { 
                            $sess_id = $stu['student_session_id'];
                            $att = isset($attendances[$sess_id]) ? $attendances[$sess_id] : null;
                            $is_present = ($att && isset($att['status']) && $att['status'] == 'present');
                        ?>
                            <tr>
                                <td><?php echo $stu['admission_no']; ?></td>
                                <td>
                                    <strong><?php echo $stu['firstname'] . ' ' . $stu['lastname']; ?></strong>
                                </td>
                                <td><?php echo $stu['class'] . ' (' . $stu['section'] . ')'; ?></td>
                                <td>
                                    <?php if ($is_present) { ?>
                                        <span class="badge" style="background:#dcfce7;color:#16a34a;"><i class="fa fa-check"></i> Present</span>
                                    <?php } else { ?>
                                        <span class="badge" style="background:#fee2e2;color:#dc2626;"><i class="fa fa-times"></i> Absent</span>
                                    <?php } ?>
                                </td>
                                <td><?php echo ($att && !empty($att['attendee_type'])) ? ucfirst($att['attendee_type']) : '-'; ?></td>
                                <td>
                                    <?php 
                                    if ($att && (!empty($att['arrival_time']) || !empty($att['departure_time']))) {
                                        echo ($att['arrival_time'] ?: '--:--') . ' - ' . ($att['departure_time'] ?: '--:--');
                                    } else {
                                        echo '-';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-xs btn-info view-snapshot" data-student="<?php echo $stu['id']; ?>" data-session="<?php echo $stu['student_session_id']; ?>"><i class="fa fa-line-chart"></i> Performance</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } ?>
    </div>
</div>
