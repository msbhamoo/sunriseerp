<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-line-chart"></i> Scholarship & Olympiad Exam Reports</h1>
    </section>

    <section class="content">
        <div class="box box-primary" style="border-radius: 10px;">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-filter"></i> Exam Performance & Merit Report Filters</h3>
            </div>
            <div class="box-body">
                <form action="<?php echo site_url('admin/scholarshipexam/report'); ?>" method="GET" class="row">
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
                            <label>Class Filter</label>
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
                        <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Generate Analytics</button>
                    </div>
                </form>
            </div>
        </div>

        <?php if (!empty($candidates)) { ?>
            <div class="box box-success" style="border-radius: 10px;">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-trophy"></i> Merit Leaderboard & Final Ranks</h3>
                    <div class="box-tools pull-right">
                        <button onclick="window.print()" class="btn btn-sm btn-default" style="border-radius: 6px;"><i class="fa fa-print"></i> Print Merit List</button>
                    </div>
                </div>

                <div class="box-body table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr style="background: #f8fafc;">
                                <th>Merit Rank</th>
                                <th>Roll Number</th>
                                <th>Student Name</th>
                                <th>Class / Section</th>
                                <th>Attendance</th>
                                <th>Marks Obtained</th>
                                <th>Percentile</th>
                                <th>Result</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($candidates as $cand) { ?>
                                <tr>
                                    <td>
                                        <?php if ($cand['rank'] == 1) { ?>
                                            <span class="badge bg-gold" style="background:#f59e0b; font-size:13px;"><i class="fa fa-trophy"></i> Rank 1</span>
                                        <?php } elseif ($cand['rank'] == 2) { ?>
                                            <span class="badge bg-silver" style="background:#94a3b8; font-size:13px;"><i class="fa fa-medal"></i> Rank 2</span>
                                        <?php } elseif ($cand['rank'] == 3) { ?>
                                            <span class="badge bg-bronze" style="background:#b45309; font-size:13px;"><i class="fa fa-medal"></i> Rank 3</span>
                                        <?php } elseif ($cand['rank']) { ?>
                                            <span class="label label-default">Rank <?php echo $cand['rank']; ?></span>
                                        <?php } else { ?>
                                            <span class="text-muted">-</span>
                                        <?php } ?>
                                    </td>
                                    <td><strong class="text-primary"><?php echo htmlspecialchars($cand['roll_no']); ?></strong></td>
                                    <td><strong><?php echo htmlspecialchars($cand['firstname'] . ' ' . $cand['lastname']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($cand['class'] . ' - ' . $cand['section']); ?></td>
                                    <td><?php echo ucfirst($cand['attendance_status']); ?></td>
                                    <td><strong><?php echo ($cand['marks_obtained'] !== null) ? $cand['marks_obtained'] : '-'; ?></strong></td>
                                    <td><?php echo ($cand['percentile'] !== null) ? $cand['percentile'] . '%' : '-'; ?></td>
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
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php } ?>
    </section>
</div>
