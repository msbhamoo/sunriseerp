<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-pencil-square-o"></i> Marks Entry & Automatic Rank Generator</h1>
    </section>

    <section class="content">
        <?php echo $this->session->flashdata('msg'); ?>

        <div class="box box-primary" style="border-radius: 10px;">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-filter"></i> Select Exam & Class Filter</h3>
            </div>
            <div class="box-body">
                <form action="<?php echo site_url('admin/scholarshipexam/marks'); ?>" method="GET" class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Exam *</label>
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Class Filter (Optional)</label>
                            <select name="class_id" class="form-control" onchange="this.form.submit()">
                                <option value="">All Classes</option>
                                <?php if (!empty($classList)) {
                                    foreach ($classList as $c) { ?>
                                        <option value="<?php echo $c['id']; ?>" <?php echo ($selected_class_id == $c['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($c['class']); ?>
                                        </option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3" style="margin-top: 24px;">
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Load Candidate Sheet</button>
                    </div>
                </form>
            </div>
        </div>

        <?php if (!empty($candidates)) { ?>
            <div class="box box-success" style="border-radius: 10px;">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-table"></i> Evaluation Grid & Rank Calculation</h3>
                    <div class="box-tools pull-right">
                        <span class="label label-info">Saving triggers automated rank & percentile calculation</span>
                    </div>
                </div>

                <form action="<?php echo site_url('admin/scholarshipexam/save_marks'); ?>" method="POST">
                    <input type="hidden" name="exam_id" value="<?php echo $selected_exam_id; ?>">

                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr style="background: #f8fafc;">
                                    <th>Roll No</th>
                                    <th>Candidate Name</th>
                                    <th>Class & Section</th>
                                    <th width="140">Attendance</th>
                                    <th width="160">Marks Obtained</th>
                                    <th>Total / Pass Marks</th>
                                    <th>Percentile (%)</th>
                                    <th>Calculated Rank</th>
                                    <th>Result Status</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($candidates as $cand) { ?>
                                    <tr>
                                        <td><strong class="text-primary"><?php echo htmlspecialchars($cand['roll_no']); ?></strong></td>
                                        <td><strong><?php echo htmlspecialchars($cand['firstname'] . ' ' . $cand['lastname']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($cand['class'] . ' - ' . $cand['section']); ?></td>
                                        <td>
                                            <select name="marks[<?php echo $cand['id']; ?>][attendance_status]" class="form-control input-sm">
                                                <option value="present" <?php echo ($cand['attendance_status'] == 'present') ? 'selected' : ''; ?>>Present</option>
                                                <option value="absent" <?php echo ($cand['attendance_status'] == 'absent') ? 'selected' : ''; ?>>Absent</option>
                                                <option value="pending" <?php echo ($cand['attendance_status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" step="0.01" name="marks[<?php echo $cand['id']; ?>][marks_obtained]" class="form-control input-sm" value="<?php echo ($cand['marks_obtained'] !== null) ? $cand['marks_obtained'] : ''; ?>" placeholder="Enter marks">
                                        </td>
                                        <td><?php echo floatval($cand['total_marks'] ?: 100); ?> / <?php echo floatval($cand['passing_marks'] ?: 40); ?></td>
                                        <td>
                                            <?php echo ($cand['percentile'] !== null) ? '<strong>' . $cand['percentile'] . '%</strong>' : '-'; ?>
                                        </td>
                                        <td>
                                            <?php if ($cand['rank']) { ?>
                                                <?php if ($cand['rank'] == 1) { ?>
                                                    <span class="badge bg-gold" style="background:#f59e0b; font-size:13px;"><i class="fa fa-trophy"></i> Rank 1</span>
                                                <?php } elseif ($cand['rank'] == 2) { ?>
                                                    <span class="badge bg-silver" style="background:#94a3b8; font-size:13px;"><i class="fa fa-medal"></i> Rank 2</span>
                                                <?php } elseif ($cand['rank'] == 3) { ?>
                                                    <span class="badge bg-bronze" style="background:#b45309; font-size:13px;"><i class="fa fa-medal"></i> Rank 3</span>
                                                <?php } else { ?>
                                                    <span class="label label-default">Rank <?php echo $cand['rank']; ?></span>
                                                <?php } ?>
                                            <?php } else { ?>
                                                <span class="text-muted">-</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if ($cand['result_status'] == 'merit_holder') { ?>
                                                <span class="label label-success">MERIT HOLDER</span>
                                            <?php } elseif ($cand['result_status'] == 'passed') { ?>
                                                <span class="label label-info">PASSED</span>
                                            <?php } elseif ($cand['result_status'] == 'failed') { ?>
                                                <span class="label label-danger">FAILED</span>
                                            <?php } else { ?>
                                                <span class="label label-default">Pending</span>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <input type="text" name="marks[<?php echo $cand['id']; ?>][remarks]" class="form-control input-sm" value="<?php echo htmlspecialchars($cand['remarks']); ?>" placeholder="Remarks">
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="box-footer text-right">
                        <button type="submit" class="btn btn-success" style="border-radius: 6px;"><i class="fa fa-calculator"></i> Save Marks & Generate Ranks</button>
                    </div>
                </form>
            </div>
        <?php } else { ?>
            <div class="alert alert-info">No candidate records found for the selected exam. Please register candidates first.</div>
        <?php } ?>
    </section>
</div>
