<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-book"></i> Sample Papers & Study Material <small>Upload model papers and resources per class</small></h1>
    </section>

    <section class="content">
        <?php echo $this->session->flashdata('msg'); ?>

        <div class="row">
            <!-- Upload Material Form -->
            <div class="col-md-4">
                <div class="box box-primary" style="border-radius: 10px;">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-upload"></i> Upload Material</h3>
                    </div>
                    <form action="<?php echo site_url('admin/scholarshipexam/upload_material'); ?>" method="POST" enctype="multipart/form-data">
                        <div class="box-body">
                            <div class="form-group">
                                <label>Scholarship Exam <span class="text-danger">*</span></label>
                                <select name="scholarship_exam_id" class="form-control" required>
                                    <?php if (!empty($exams)) {
                                        foreach ($exams as $e) { ?>
                                            <option value="<?php echo $e['id']; ?>" <?php echo ($selected_exam_id == $e['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($e['title']); ?> (<?php echo htmlspecialchars($e['exam_code']); ?>)
                                            </option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Target Class <span class="text-danger">*</span></label>
                                <select name="class_id" class="form-control" required>
                                    <option value="">-- Select Class --</option>
                                    <?php if (!empty($classList)) {
                                        foreach ($classList as $cls) { ?>
                                            <option value="<?php echo $cls['id']; ?>" <?php echo ($selected_class_id == $cls['id']) ? 'selected' : ''; ?>>
                                                Class <?php echo htmlspecialchars($cls['class']); ?>
                                            </option>
                                        <?php }
                                    } ?>
                                </select>
                                <p class="help-block"><small>Registered candidates of this class will see this file in their portal.</small></p>
                            </div>

                            <div class="form-group">
                                <label>Material Type <span class="text-danger">*</span></label>
                                <select name="material_type" class="form-control" required>
                                    <option value="sample_paper" <?php echo ($selected_material_type == 'sample_paper') ? 'selected' : ''; ?>>Sample Paper (Model Test Paper)</option>
                                    <option value="syllabus" <?php echo ($selected_material_type == 'syllabus') ? 'selected' : ''; ?>>Syllabus Blueprint</option>
                                    <option value="notes" <?php echo ($selected_material_type == 'notes') ? 'selected' : ''; ?>>Subject Notes & Formula Sheet</option>
                                    <option value="other" <?php echo ($selected_material_type == 'other') ? 'selected' : ''; ?>>Other Study Material / Guide</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Title / Paper Name <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" placeholder="e.g. Class 9th Bilingual Sample Paper - SSAT 2026" required>
                            </div>

                            <div class="form-group">
                                <label>Brief Description / Instructions</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="e.g. 50 MCQs bilingual paper covering Maths, Science, SST, English, Hindi and Reasoning with marking scheme."></textarea>
                            </div>

                            <div class="form-group">
                                <label>Attach Document / PDF File <span class="text-danger">*</span></label>
                                <input type="file" name="material_file" class="form-control" accept=".pdf,.doc,.docx,.zip,.jpg,.jpeg,.png" required>
                                <p class="help-block"><small>Allowed formats: PDF, DOC, DOCX, ZIP, PNG, JPG (Max 25MB).</small></p>
                            </div>
                        </div>

                        <div class="box-footer text-right">
                            <button type="submit" class="btn btn-primary btn-block" style="border-radius: 6px;"><i class="fa fa-cloud-upload"></i> Upload & Publish to Portal</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Material Listing -->
            <div class="col-md-8">
                <div class="box box-primary" style="border-radius: 10px;">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-folder-open"></i> Uploaded Study Materials & Sample Papers</h3>
                    </div>

                    <div class="box-body">
                        <!-- Filters -->
                        <form method="GET" action="<?php echo site_url('admin/scholarshipexam/materials'); ?>" class="form-inline" style="margin-bottom: 20px;">
                            <div class="form-group">
                                <label>Exam:&nbsp;</label>
                                <select name="exam_id" class="form-control input-sm">
                                    <option value="">All Exams</option>
                                    <?php if (!empty($exams)) {
                                        foreach ($exams as $e) { ?>
                                            <option value="<?php echo $e['id']; ?>" <?php echo ($selected_exam_id == $e['id']) ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($e['title']); ?>
                                            </option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>

                            <div class="form-group" style="margin-left: 10px;">
                                <label>Class:&nbsp;</label>
                                <select name="class_id" class="form-control input-sm">
                                    <option value="">All Classes</option>
                                    <?php if (!empty($classList)) {
                                        foreach ($classList as $cls) { ?>
                                            <option value="<?php echo $cls['id']; ?>" <?php echo ($selected_class_id == $cls['id']) ? 'selected' : ''; ?>>
                                                Class <?php echo htmlspecialchars($cls['class']); ?>
                                            </option>
                                        <?php }
                                    } ?>
                                </select>
                            </div>

                            <div class="form-group" style="margin-left: 10px;">
                                <label>Type:&nbsp;</label>
                                <select name="material_type" class="form-control input-sm">
                                    <option value="">All Types</option>
                                    <option value="sample_paper" <?php echo ($selected_material_type == 'sample_paper') ? 'selected' : ''; ?>>Sample Paper</option>
                                    <option value="syllabus" <?php echo ($selected_material_type == 'syllabus') ? 'selected' : ''; ?>>Syllabus</option>
                                    <option value="notes" <?php echo ($selected_material_type == 'notes') ? 'selected' : ''; ?>>Notes</option>
                                    <option value="other" <?php echo ($selected_material_type == 'other') ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-sm btn-info" style="margin-left: 10px; border-radius: 4px;"><i class="fa fa-filter"></i> Filter</button>
                            <a href="<?php echo site_url('admin/scholarshipexam/materials'); ?>" class="btn btn-sm btn-default" style="margin-left: 5px; border-radius: 4px;">Reset</a>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr style="background: #f8fafc;">
                                        <th>Class</th>
                                        <th>Type</th>
                                        <th>Title & Details</th>
                                        <th>File Size</th>
                                        <th>Uploaded On</th>
                                        <th class="text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($materials)) {
                                        foreach ($materials as $m) {
                                            $type_badge = 'default';
                                            $type_label = ucfirst($m['material_type']);
                                            if ($m['material_type'] == 'sample_paper') {
                                                $type_badge = 'primary';
                                                $type_label = 'Sample Paper';
                                            } elseif ($m['material_type'] == 'syllabus') {
                                                $type_badge = 'warning';
                                                $type_label = 'Syllabus';
                                            } elseif ($m['material_type'] == 'notes') {
                                                $type_badge = 'success';
                                                $type_label = 'Subject Notes';
                                            }
                                            ?>
                                            <tr>
                                                <td><span class="label label-info" style="font-size: 12px;">Class <?php echo htmlspecialchars($m['class_name'] ?: 'All'); ?></span></td>
                                                <td><span class="label label-<?php echo $type_badge; ?>"><?php echo $type_label; ?></span></td>
                                                <td>
                                                    <strong><?php echo htmlspecialchars($m['title']); ?></strong>
                                                    <?php if (!empty($m['description'])) { ?>
                                                        <br><small class="text-muted"><?php echo htmlspecialchars($m['description']); ?></small>
                                                    <?php } ?>
                                                    <br><small class="text-primary"><i class="fa fa-file-text-o"></i> <?php echo htmlspecialchars($m['file_name']); ?></small>
                                                </td>
                                                <td><?php echo htmlspecialchars($m['file_size'] ?: 'N/A'); ?></td>
                                                <td><small><?php echo date('d M Y, h:i A', strtotime($m['created_at'])); ?></small></td>
                                                <td class="text-right">
                                                    <a href="<?php echo base_url($m['file_path']); ?>" target="_blank" class="btn btn-xs btn-primary" title="Download / Preview"><i class="fa fa-download"></i> View</a>
                                                    <a href="<?php echo site_url('admin/scholarshipexam/delete_material/' . $m['id']); ?>" onclick="return confirm('Are you sure you want to delete this study material?');" class="btn btn-xs btn-danger" title="Delete"><i class="fa fa-trash"></i></a>
                                                </td>
                                            </tr>
                                        <?php }
                                    } else { ?>
                                        <tr>
                                            <td colspan="6" class="text-center text-muted" style="padding: 30px;">
                                                <i class="fa fa-folder-open-o" style="font-size: 32px; color: #94a3b8; margin-bottom: 10px; display: block;"></i>
                                                No sample papers or study materials uploaded yet for the selected filter.
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
    </section>
</div>