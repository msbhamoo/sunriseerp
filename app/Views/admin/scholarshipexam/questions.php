<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-question-circle"></i> Question Bank & Paper Creation</h1>
    </section>

    <section class="content">
        <?php echo $this->session->flashdata('msg'); ?>

        <div class="box box-primary" style="border-radius: 10px;">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-filter"></i> Select Exam for Paper Customization</h3>
            </div>
            <div class="box-body">
                <form action="<?php echo site_url('admin/scholarshipexam/questions'); ?>" method="GET" class="row">
                    <div class="col-md-4">
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
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Subject Filter</label>
                            <select name="subject_id" class="form-control" onchange="this.form.submit()">
                                <option value="">All Subjects</option>
                                <?php if (!empty($subjects)) {
                                    foreach ($subjects as $sub) { ?>
                                        <option value="<?php echo $sub['id']; ?>" <?php echo ($selected_subject_id == $sub['id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($sub['name']); ?> (<?php echo htmlspecialchars($sub['code']); ?>)
                                        </option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Class Filter (Single / Multi-Class)</label>
                            <select name="class_id[]" class="form-control" onchange="this.form.submit()">
                                <option value="">All Classes (General Pool)</option>
                                <?php if (!empty($classList)) {
                                    foreach ($classList as $c) {
                                        $sel = (is_array($selected_class_ids) ? in_array($c['id'], $selected_class_ids) : ($selected_class_ids == $c['id'])) ? 'selected' : '';
                                        ?>
                                        <option value="<?php echo $c['id']; ?>" <?php echo $sel; ?>>Class <?php echo htmlspecialchars($c['class']); ?></option>
                                    <?php }
                                } ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2" style="margin-top: 24px;">
                        <?php if ($selected_exam_id) { ?>
                            <a href="<?php echo site_url('admin/scholarshipexam/print_paper/' . $selected_exam_id); ?>" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print Paper</a>
                        <?php } ?>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($selected_exam_id) { ?>
            <div class="row">
                <!-- Left: Question Paper Selected Questions -->
                <div class="col-md-6">
                    <div class="box box-success" style="border-radius: 10px;">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-file-text-o"></i> Exam Question Paper (<?php echo count($exam_questions); ?> Questions Attached)</h3>
                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr style="background: #f8fafc;">
                                        <th width="30">#</th>
                                        <th>Question</th>
                                        <th>Subject</th>
                                        <th>Marks</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($exam_questions)) {
                                        $cnt = 1;
                                        foreach ($exam_questions as $eq) { ?>
                                            <tr>
                                                <td><?php echo $cnt++; ?></td>
                                                <td>
                                                    <strong><?php echo strip_tags($eq['question']); ?></strong>
                                                    <br><small class="text-muted">Type: <?php echo ucfirst($eq['question_type']); ?> | Level: <?php echo ucfirst($eq['level']); ?></small>
                                                </td>
                                                <td><span class="label label-default"><?php echo htmlspecialchars($eq['subject_name'] ?: 'General'); ?></span></td>
                                                <td><span class="badge bg-green">+<?php echo floatval($eq['marks']); ?> / -<?php echo floatval($eq['neg_marks']); ?></span></td>
                                                <td>
                                                    <a href="<?php echo site_url('admin/scholarshipexam/remove_question?exam_id=' . $selected_exam_id . '&question_id=' . $eq['question_id']); ?>" class="btn btn-xs btn-danger" onclick="return confirm('Remove question from this paper?');"><i class="fa fa-times"></i></a>
                                                </td>
                                            </tr>
                                        <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">No questions attached to this exam paper yet. Add questions from the Question Bank on the right.</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Right: Question Bank Pool -->
                <div class="col-md-6">
                    <div class="box box-info" style="border-radius: 10px;">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-database"></i> Add From Question Bank Pool</h3>
                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr style="background: #f8fafc;">
                                        <th>Question</th>
                                        <th>Subject</th>
                                        <th>Add to Paper</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($available_questions)) {
                                        foreach ($available_questions as $aq) { ?>
                                            <tr>
                                                <td>
                                                    <strong><?php echo strip_tags($aq['question']); ?></strong>
                                                    <br><small class="text-muted">Options: A: <?php echo strip_tags($aq['opt_a']); ?> | B: <?php echo strip_tags($aq['opt_b']); ?></small>
                                                </td>
                                                <td><span class="label label-info"><?php echo htmlspecialchars($aq['subject_name'] ?: 'General'); ?></span></td>
                                                <td>
                                                    <form action="<?php echo site_url('admin/scholarshipexam/add_question'); ?>" method="POST" style="display:flex; gap:4px;">
                                                        <input type="hidden" name="exam_id" value="<?php echo $selected_exam_id; ?>">
                                                        <input type="hidden" name="question_id" value="<?php echo $aq['id']; ?>">
                                                        <input type="number" step="0.5" name="marks" value="1.00" class="form-control input-sm" style="width:60px;" title="Marks">
                                                        <button type="submit" class="btn btn-xs btn-success"><i class="fa fa-plus"></i> Add</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No question bank entries found for selected filters.</td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </section>
</div>
