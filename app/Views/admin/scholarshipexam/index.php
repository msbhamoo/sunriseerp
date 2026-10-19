<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-trophy"></i> <?php echo $this->lang->line('scholarship_exam') ?: 'Scholarship & Olympiad Exams'; ?> <small>Overview & Performance</small></h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-aqua" style="border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                    <div class="inner" style="padding: 20px;">
                        <h3><?php echo isset($stats['total_exams']) ? $stats['total_exams'] : 0; ?></h3>
                        <p style="font-weight: 500;">Total Olympiad & Scholarship Exams</p>
                    </div>
                    <div class="icon" style="top: 15px; right: 15px;"><i class="fa fa-graduation-cap"></i></div>
                    <a href="<?php echo site_url('admin/scholarshipexam/exams'); ?>" class="small-box-footer" style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">Manage Exams <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-green" style="border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                    <div class="inner" style="padding: 20px;">
                        <h3><?php echo isset($stats['active_exams']) ? $stats['active_exams'] : 0; ?></h3>
                        <p style="font-weight: 500;">Active / Upcoming Exams</p>
                    </div>
                    <div class="icon" style="top: 15px; right: 15px;"><i class="fa fa-calendar-check-o"></i></div>
                    <a href="<?php echo site_url('admin/scholarshipexam/exams'); ?>" class="small-box-footer" style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">View Schedules <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-yellow" style="border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                    <div class="inner" style="padding: 20px;">
                        <h3><?php echo isset($stats['total_candidates']) ? $stats['total_candidates'] : 0; ?></h3>
                        <p style="font-weight: 500;">Enrolled Candidates</p>
                    </div>
                    <div class="icon" style="top: 15px; right: 15px;"><i class="fa fa-users"></i></div>
                    <a href="<?php echo site_url('admin/scholarshipexam/candidates'); ?>" class="small-box-footer" style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">Candidate Roster <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">
                <div class="small-box bg-red" style="border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                    <div class="inner" style="padding: 20px;">
                        <h3><?php echo isset($stats['merit_holders']) ? $stats['merit_holders'] : 0; ?></h3>
                        <p style="font-weight: 500;">Merit Rank Holders</p>
                    </div>
                    <div class="icon" style="top: 15px; right: 15px;"><i class="fa fa-award"></i></div>
                    <a href="<?php echo site_url('admin/scholarshipexam/report'); ?>" class="small-box-footer" style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">Merit Leaderboard <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary" style="border-radius: 10px;">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-list-alt"></i> Active Scholarship & Olympiad Exams Overview</h3>
                        <div class="box-tools pull-right">
                            <a href="<?php echo site_url('admin/scholarshipexam/exams'); ?>" class="btn btn-sm btn-primary" style="border-radius: 6px;"><i class="fa fa-plus"></i> Create New Exam</a>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr style="background: #f8fafc;">
                                    <th>Code</th>
                                    <th>Exam Title</th>
                                    <th>Category</th>
                                    <th>Mode</th>
                                    <th>Roll Prefix</th>
                                    <th>Fee Structure</th>
                                    <th>Candidates</th>
                                    <th>Status</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($exams)) {
                                    foreach ($exams as $exam) { ?>
                                        <tr>
                                            <td><span class="label label-default"><?php echo htmlspecialchars($exam['exam_code']); ?></span></td>
                                            <td><strong><?php echo htmlspecialchars($exam['title']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($exam['exam_category']); ?></td>
                                            <td>
                                                <?php if ($exam['exam_mode'] == 'online') { ?>
                                                    <span class="label label-info"><i class="fa fa-laptop"></i> Online</span>
                                                <?php } else { ?>
                                                    <span class="label label-warning"><i class="fa fa-file-text-o"></i> Offline</span>
                                                <?php } ?>
                                            </td>
                                            <td><code><?php echo htmlspecialchars($exam['roll_no_prefix']); ?></code></td>
                                            <td>
                                                <?php if ($exam['is_paid'] == 1) { ?>
                                                    <span class="label label-danger">Paid ($<?php echo number_format($exam['registration_fee'], 2); ?>)</span>
                                                <?php } else { ?>
                                                    <span class="label label-success">Free</span>
                                                <?php } ?>
                                            </td>
                                            <td><span class="badge bg-purple"><?php echo $exam['candidate_count']; ?> Candidates</span></td>
                                            <td>
                                                <?php if ($exam['status'] == 1) { ?>
                                                    <span class="label label-success">Active</span>
                                                <?php } else { ?>
                                                    <span class="label label-danger">Inactive</span>
                                                <?php } ?>
                                            </td>
                                            <td class="text-right">
                                                <a href="<?php echo site_url('admin/scholarshipexam/candidates?exam_id=' . $exam['id']); ?>" class="btn btn-xs btn-default" title="Candidates"><i class="fa fa-users"></i></a>
                                                <a href="<?php echo site_url('admin/scholarshipexam/marks?exam_id=' . $exam['id']); ?>" class="btn btn-xs btn-default" title="Marks & Ranks"><i class="fa fa-pencil-square-o"></i></a>
                                                <a href="<?php echo site_url('admin/scholarshipexam/certificates?exam_id=' . $exam['id']); ?>" class="btn btn-xs btn-default" title="Certificates"><i class="fa fa-certificate"></i></a>
                                            </td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">No scholarship/olympiad exams setup yet. <a href="<?php echo site_url('admin/scholarshipexam/exams'); ?>">Create one now</a>.</td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
