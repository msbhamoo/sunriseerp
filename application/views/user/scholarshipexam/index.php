<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-trophy"></i> Scholarship & Olympiad Exams</h1>
    </section>

    <section class="content">
        <?php echo $this->session->flashdata('msg'); ?>

        <div class="box box-primary" style="border-radius: 10px;">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-list"></i> Available Scholarship & Olympiad Competitions</h3>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th>Code</th>
                            <th>Exam Title</th>
                            <th>Category</th>
                            <th>Mode</th>
                            <th>Exam Date</th>
                            <th>Duration</th>
                            <th>Fee</th>
                            <th>Status / Registration</th>
                            <th class="text-right">Action / Admit Card</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($exams)) {
                            foreach ($exams as $e) { ?>
                                <tr>
                                    <td><span class="label label-default"><?php echo htmlspecialchars($e['exam_code']); ?></span></td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($e['title']); ?></strong>
                                        <br><small class="text-muted"><?php echo htmlspecialchars($e['description']); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($e['exam_category']); ?></td>
                                    <td>
                                        <?php echo ($e['exam_mode'] == 'online') ? '<span class="label label-info">Online CBT</span>' : '<span class="label label-warning">Offline OMR</span>'; ?>
                                    </td>
                                    <td><?php echo $e['exam_date'] ? date('d M Y h:i A', strtotime($e['exam_date'])) : 'TBA'; ?></td>
                                    <td><?php echo $e['duration_minutes']; ?> Mins</td>
                                    <td>
                                        <?php echo ($e['is_paid'] == 1) ? '<strong class="text-danger">$' . number_format($e['registration_fee'], 2) . '</strong>' : '<span class="text-success">FREE</span>'; ?>
                                    </td>
                                    <td>
                                        <?php if ($e['is_registered'] == 1) { ?>
                                            <span class="label label-success"><i class="fa fa-check-circle"></i> Registered</span>
                                            <br><small class="text-primary">Roll: <strong><?php echo htmlspecialchars($e['candidate_details']['roll_no']); ?></strong></small>
                                        <?php } else { ?>
                                            <span class="label label-default">Not Enrolled</span>
                                        <?php } ?>
                                    </td>
                                    <td class="text-right">
                                        <?php if ($e['is_registered'] == 1) { ?>
                                            <a href="<?php echo site_url('admin/scholarshipexam/admitcard/' . $e['candidate_details']['id']); ?>" target="_blank" class="btn btn-xs btn-primary"><i class="fa fa-print"></i> Admit Card</a>
                                        <?php } else { ?>
                                            <a href="<?php echo site_url('user/scholarshipexam/apply/' . $e['id'] . '/' . $e['schedule_id']); ?>" class="btn btn-xs btn-success" onclick="return confirm('Confirm registration for this scholarship exam?');"><i class="fa fa-pencil"></i> Apply Now</a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php }
                        } else { ?>
                            <tr>
                                <td colspan="9" class="text-center text-muted">No active scholarship/olympiad competitions for your class at this time.</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
