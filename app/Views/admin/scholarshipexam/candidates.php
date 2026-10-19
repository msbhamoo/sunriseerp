<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-users"></i> Candidate Registration & Roll Number Assignment</h1>
    </section>

    <section class="content">
        <?php echo $this->session->flashdata('msg'); ?>

        <div class="box box-primary" style="border-radius: 10px;">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-filter"></i> Select Exam & Registered Roster</h3>
            </div>
            <div class="box-body">
                <form action="<?php echo site_url('admin/scholarshipexam/candidates'); ?>" method="GET" class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Select Scholarship Exam *</label>
                            <select name="exam_id" class="form-control" onchange="this.form.submit()">
                                <?php if (!empty($exams)) {
                                    foreach ($exams as $e) { ?>
                                        <option value="<?php echo $e['id']; ?>" <?php echo ($selected_exam_id == $e['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($e['title']); ?> (<?php echo htmlspecialchars($e['exam_code']); ?>)
                                        </option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Candidate Type Filter</label>
                            <select name="candidate_type" class="form-control" onchange="this.form.submit()">
                                <option value="">All Candidates (Internal & External)</option>
                                <option value="internal" <?php echo (isset($selected_candidate_type) && $selected_candidate_type == 'internal') ? 'selected' : ''; ?>>Internal Students Only</option>
                                <option value="external" <?php echo (isset($selected_candidate_type) && $selected_candidate_type == 'external') ? 'selected' : ''; ?>>External Candidates Only</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Filter Class (Internal Enrollment)</label>
                            <select name="class_id" class="form-control" onchange="this.form.submit()">
                                <option value="">All Classes</option>
                                <?php if (!empty($classList)) {
                                    foreach ($classList as $c) { ?>
                                        <option value="<?php echo $c['id']; ?>" <?php echo ($selected_class_id == $c['id']) ? 'selected' : ''; ?>>
                                            Class <?php echo htmlspecialchars($c['class']); ?>
                                        </option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2" style="margin-top: 24px;">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Search</button>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($selected_exam_id && $selected_class_id && !empty($eligible_students)) { ?>
            <div class="box box-warning" style="border-radius: 10px;">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-user-plus"></i> Eligible Students for Candidate Registration</h3>
                </div>
                <form action="<?php echo site_url('admin/scholarshipexam/register_candidates'); ?>" method="POST">
                    <input type="hidden" name="exam_id" value="<?php echo $selected_exam_id; ?>">
                    <input type="hidden" name="schedule_id" value="<?php echo isset($schedules[0]['id']) ? $schedules[0]['id'] : ''; ?>">

                    <div class="box-body table-responsive">
                        <div class="row" style="margin-bottom: 12px;">
                            <div class="col-md-4">
                                <label>Roll Number Prefix Series</label>
                                <input type="text" name="roll_prefix" class="form-control input-sm" value="OLY26-" placeholder="e.g. OLY26-">
                            </div>
                            <div class="col-md-8 text-right" style="margin-top: 22px;">
                                <button type="button" class="btn btn-xs btn-default" onclick="selectAllStudents(true)">Select All</button>
                                <button type="button" class="btn btn-xs btn-default" onclick="selectAllStudents(false)">Deselect All</button>
                            </div>
                        </div>

                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th width="40">Select</th>
                                    <th>Admission No</th>
                                    <th>Student Name</th>
                                    <th>Class & Section</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($eligible_students as $st) { ?>
                                    <tr>
                                        <td>
                                            <?php if ($st['is_registered'] == 0) { ?>
                                                <input type="checkbox" name="student_session_ids[]" value="<?php echo $st['student_session_id']; ?>" class="std-check">
                                            <?php } else { ?>
                                                <i class="fa fa-check-circle text-success"></i>
                                            <?php } ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($st['admission_no']); ?></td>
                                        <td><strong><?php echo htmlspecialchars($st['firstname'] . ' ' . $st['lastname']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($st['class'] . ' - ' . $st['section']); ?></td>
                                        <td>
                                            <?php if ($st['is_registered'] == 1) { ?>
                                                <span class="label label-success">Registered (Roll: <?php echo htmlspecialchars($st['candidate_details']['roll_no']); ?>)</span>
                                            <?php } else { ?>
                                                <span class="label label-default">Not Enrolled</span>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer text-right">
                        <button type="submit" class="btn btn-success" style="border-radius: 6px;"><i class="fa fa-user-plus"></i> Register Selected Candidates & Auto-Generate Roll Series</button>
                    </div>
                </form>
            </div>
        <?php } ?>

        <div class="box box-info" style="border-radius: 10px;">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-id-card-o"></i> Registered Candidates Roster & Admit Cards</h3>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th>Roll Number</th>
                            <th>Admit Card No</th>
                            <th>Student Name & Type</th>
                            <th>Class / Section / School</th>
                            <th>Registration Date</th>
                            <th>Payment Status</th>
                            <th>Admit Card</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($registered_candidates)) {
                            foreach ($registered_candidates as $cand) { ?>
                                <tr>
                                    <td><strong class="text-primary"><?php echo htmlspecialchars($cand['roll_no']); ?></strong></td>
                                    <td><code><?php echo htmlspecialchars($cand['admit_card_no']); ?></code></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($cand['firstname'] . ' ' . $cand['lastname']); ?></strong>
                                        <?php if (isset($cand['is_external']) && $cand['is_external'] == 1) { ?>
                                            <span class="label label-warning" style="font-size: 10px; margin-left: 4px;">EXTERNAL</span>
                                        <?php } else { ?>
                                            <span class="label label-info" style="font-size: 10px; margin-left: 4px;">INTERNAL</span>
                                        <?php } ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($cand['class'] . ' - ' . $cand['section']); ?></td>
                                    <td><?php echo date('d M Y', strtotime($cand['registration_date'])); ?></td>
                                    <td>
                                        <?php echo ($cand['payment_status'] == 'paid') ? '<span class="label label-success">Paid</span>' : '<span class="label label-default">Free</span>'; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo site_url('admin/scholarshipexam/admitcard/' . $cand['id']); ?>" target="_blank" class="btn btn-xs btn-info" style="border-radius: 4px;"><i class="fa fa-print"></i> Print Admit Card</a>
                                    </td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="7" class="text-center text-muted">No candidate records found for selected filters.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>

<script>
function selectAllStudents(flag) {
    $('.std-check').prop('checked', flag);
}
</script>
