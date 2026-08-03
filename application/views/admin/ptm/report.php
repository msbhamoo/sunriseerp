<div class="content-wrapper" style="min-height: 946px;">
    <section class="content-header">
        <h1><i class="fa fa-pie-chart"></i> <?php echo $this->lang->line('ptm_reports'); ?></h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form action="<?php echo site_url('admin/ptmreports') ?>" method="post" accept-charset="utf-8">
                        <div class="box-body">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Report Type</label>
                                        <select class="form-control" name="search_type" id="search_type" onchange="toggleSearchType()">
                                            <option value="ptm_report" <?php echo (isset($search_type) && $search_type == 'ptm_report') ? 'selected' : ''; ?>>PTM Attendance Report</option>
                                            <option value="followup_report" <?php echo (isset($search_type) && $search_type == 'followup_report') ? 'selected' : ''; ?>>Assigned PTM Follow-ups Report</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4" id="ptm_select_div" style="<?php echo (isset($search_type) && $search_type == 'followup_report') ? 'display:none;' : ''; ?>">
                                    <div class="form-group">
                                        <label><?php echo $this->lang->line('select_ptm'); ?></label>
                                        <select class="form-control" name="ptm_id">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($ptm_list as $ptm_option) { ?>
                                                <option value="<?php echo $ptm_option['id']; ?>" <?php echo set_select('ptm_id', $ptm_option['id'], (isset($selected_ptm) && $selected_ptm == $ptm_option['id'])); ?>><?php echo $ptm_option['title'] . ' (' . date('d-m-Y', strtotime($ptm_option['ptm_date'])) . ')'; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4" id="staff_select_div" style="<?php echo (isset($search_type) && $search_type == 'followup_report') ? '' : 'display:none;'; ?>">
                                    <div class="form-group">
                                        <label>Filter By Staff</label>
                                        <select class="form-control" name="staff_id">
                                            <option value="">All Assigned Staff</option>
                                            <?php if (!empty($staff_list)) { 
                                                foreach ($staff_list as $st) { ?>
                                                    <option value="<?php echo $st['id']; ?>" <?php echo set_select('staff_id', $st['id'], (isset($selected_staff) && $selected_staff == $st['id'])); ?>><?php echo $st['name'] . ' ' . $st['surname'] . ($st['employee_id'] ? ' (' . $st['employee_id'] . ')' : ''); ?></option>
                                            <?php } } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label style="display:block;">&nbsp;</label>
                                        <button type="submit" name="search" value="search_filter" class="btn btn-primary btn-sm checkbox-toggle pull-right"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php if (isset($search_type) && $search_type == 'followup_report' && isset($followup_list)): ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-tasks"></i> Assigned PTM Follow-up Tasks</h3>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>PTM Event</th>
                                            <th>Admission No</th>
                                            <th>Student Name</th>
                                            <th>Class & Section</th>
                                            <th>Arrival / Departure</th>
                                            <th>Discussion Points</th>
                                            <th>Parent Remarks</th>
                                            <th>Teacher Remarks</th>
                                            <th>Concerns</th>
                                            <th>Action Items</th>
                                            <th>Follow-up Date</th>
                                            <th>Assigned To</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($followup_list)) {
                                            foreach ($followup_list as $f) { 
                                                $concerns = [];
                                                if (!empty($f['concerns_academics'])) $concerns[] = "Academics";
                                                if (!empty($f['concerns_attendance'])) $concerns[] = "Attendance";
                                                if (!empty($f['concerns_behavior'])) $concerns[] = "Behavior";
                                                if (!empty($f['concerns_discipline'])) $concerns[] = "Discipline";
                                            ?>
                                                <tr>
                                                    <td><strong><?php echo $f['ptm_title']; ?></strong><br><small class="text-muted"><?php echo $this->customlib->dateformat($f['ptm_date']); ?></small></td>
                                                    <td><?php echo $f['admission_no']; ?></td>
                                                    <td><?php echo $f['firstname'] . ' ' . $f['lastname']; ?></td>
                                                    <td><?php echo $f['class'] . ' (' . $f['section'] . ')'; ?></td>
                                                    <td><?php echo (!empty($f['arrival_time']) ? $f['arrival_time'] : '--:--') . ' - ' . (!empty($f['departure_time']) ? $f['departure_time'] : '--:--'); ?></td>
                                                    <td><?php echo nl2br(htmlspecialchars($f['discussion_points'] ?: '-')); ?></td>
                                                    <td><?php echo nl2br(htmlspecialchars($f['parent_remarks'] ?: '-')); ?></td>
                                                    <td><?php echo nl2br(htmlspecialchars($f['teacher_remarks'] ?: '-')); ?></td>
                                                    <td><?php echo !empty($concerns) ? implode(', ', $concerns) : '-'; ?></td>
                                                    <td><?php echo nl2br(htmlspecialchars($f['action_items'] ?: '-')); ?></td>
                                                    <td><?php echo !empty($f['followup_date']) ? date('d-m-Y', strtotime($f['followup_date'])) : '-'; ?></td>
                                                    <td>
                                                        <?php if (!empty($f['assigned_staff_name'])): ?>
                                                            <span class="label label-info"><?php echo $f['assigned_staff_name'] . ' ' . $f['assigned_staff_surname'] . ($f['employee_id'] ? ' (' . $f['employee_id'] . ')' : ''); ?></span>
                                                        <?php else: ?>
                                                            <span class="text-muted">Unassigned</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                        <?php } } else { ?>
                                            <tr><td colspan="12" class="text-center text-muted">No assigned PTM follow-ups found.</td></tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <?php if ((!isset($search_type) || $search_type == 'ptm_report') && isset($students)) { 
            // Calculate Stats
            $total = count($students);
            $present = 0;
            foreach ($students as $student) {
                if (isset($attendances[$student['student_session_id']]) && $attendances[$student['student_session_id']]['status'] == 'present') {
                    $present++;
                }
            }
            $percentage = $total > 0 ? round(($present / $total) * 100, 2) : 0;
        ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"><i class="fa fa-users"></i> <?php echo $this->lang->line('ptm_attendance_report'); ?></h3>
                            <div class="pull-right">
                                <span class="label label-success"><?php echo $this->lang->line('total_present'); ?>: <?php echo $present; ?></span>
                                <span class="label label-danger"><?php echo $this->lang->line('total_absent'); ?>: <?php echo $total - $present; ?></span>
                                <span class="label label-info"><?php echo $this->lang->line('participation'); ?>: <?php echo $percentage; ?>%</span>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th><?php echo $this->lang->line('admission_no'); ?></th>
                                            <th><?php echo $this->lang->line('student_name'); ?></th>
                                            <th><?php echo $this->lang->line('class'); ?></th>
                                            <th><?php echo $this->lang->line('status'); ?></th>
                                            <th><?php echo $this->lang->line('attendee'); ?></th>
                                            <th>Arrival / Departure</th>
                                            <th><?php echo $this->lang->line('discussion_points'); ?></th>
                                            <th>Parent Remarks</th>
                                            <th>Teacher Remarks</th>
                                            <th>Concerns</th>
                                            <th><?php echo $this->lang->line('action_items'); ?></th>
                                            <th>Follow-up Required</th>
                                            <th>Assign To</th>
                                            <th>Follow-up Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($students as $student) { 
                                            $att = isset($attendances[$student['student_session_id']]) ? $attendances[$student['student_session_id']] : [];
                                            $concerns = [];
                                            if (!empty($att['concerns_academics'])) $concerns[] = "Academics";
                                            if (!empty($att['concerns_attendance'])) $concerns[] = "Attendance";
                                            if (!empty($att['concerns_behavior'])) $concerns[] = "Behavior";
                                            if (!empty($att['concerns_discipline'])) $concerns[] = "Discipline";
                                        ?>
                                            <tr>
                                                <td><?php echo $student['admission_no']; ?></td>
                                                <td><?php echo $student['firstname'] . ' ' . $student['lastname']; ?></td>
                                                <td><?php echo $student['class'] . ' (' . $student['section'] . ')'; ?></td>
                                                <td>
                                                    <?php 
                                                        if (isset($att['status']) && $att['status'] == 'present') {
                                                            echo "<span class='label label-success'>Present</span>";
                                                        } else {
                                                            echo "<span class='label label-danger'>Absent</span>";
                                                        }
                                                    ?>
                                                </td>
                                                <td><?php echo isset($att['attendee_type']) ? ucfirst($att['attendee_type']) : '-'; ?></td>
                                                <td><?php echo (!empty($att['arrival_time']) ? $att['arrival_time'] : '--:--') . ' - ' . (!empty($att['departure_time']) ? $att['departure_time'] : '--:--'); ?></td>
                                                <td><?php echo isset($att['discussion_points']) ? nl2br(htmlspecialchars($att['discussion_points'])) : '-'; ?></td>
                                                <td><?php echo isset($att['parent_remarks']) ? nl2br(htmlspecialchars($att['parent_remarks'])) : '-'; ?></td>
                                                <td><?php echo isset($att['teacher_remarks']) ? nl2br(htmlspecialchars($att['teacher_remarks'])) : '-'; ?></td>
                                                <td><?php echo !empty($concerns) ? implode(', ', $concerns) : '-'; ?></td>
                                                <td><?php echo isset($att['action_items']) ? nl2br(htmlspecialchars($att['action_items'])) : '-'; ?></td>
                                                <td>
                                                    <?php echo (isset($att['followup_required']) && $att['followup_required'] == 1) ? "<span class='label label-warning'>Yes</span>" : "<span class='text-muted'>No</span>"; ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($att['assigned_staff_name'])): ?>
                                                        <span class="label label-info"><?php echo $att['assigned_staff_name'] . ' ' . $att['assigned_staff_surname']; ?></span>
                                                    <?php else: ?>
                                                        <span class="text-muted">-</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php echo (!empty($att['followup_date'])) ? date('d-m-Y', strtotime($att['followup_date'])) : '-'; ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </section>
</div>

<script type="text/javascript">
function toggleSearchType() {
    if ($('#search_type').val() == 'followup_report') {
        $('#ptm_select_div').hide();
        $('#staff_select_div').show();
    } else {
        $('#ptm_select_div').show();
        $('#staff_select_div').hide();
    }
}
</script>
