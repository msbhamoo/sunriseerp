<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-cogs"></i> Scholarship & Olympiad Exams Setup</h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary" style="border-radius: 10px;">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-list"></i> Managed Exams</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-primary btn-sm" onclick="openAddExamModal()" style="border-radius: 6px;"><i class="fa fa-plus"></i> Create New Exam</button>
                        </div>
                    </div>

                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr style="background: #f8fafc;">
                                    <th>Code</th>
                                    <th>Exam Title</th>
                                    <th>Mode</th>
                                    <th>Fee</th>
                                    <th>Class Schedules</th>
                                    <th>Quick Control Toggles</th>
                                    <th>Public Direct Form</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($exams)) {
                                    foreach ($exams as $e) { ?>
                                        <tr>
                                            <td><span class="label label-primary"><?php echo htmlspecialchars($e['exam_code']); ?></span></td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($e['title']); ?></strong>
                                                <br><small class="text-muted"><?php echo htmlspecialchars($e['exam_category']); ?></small>
                                            </td>
                                            <td>
                                                <?php echo ($e['exam_mode'] == 'online') ? '<span class="label label-info">Online CBT</span>' : '<span class="label label-warning">Offline OMR</span>'; ?>
                                            </td>
                                            <td>
                                                <?php echo ($e['is_paid'] == 1) ? '<strong class="text-danger">Paid ($' . number_format($e['registration_fee'], 2) . ')</strong>' : '<span class="text-success">Free</span>'; ?>
                                            </td>
                                            <td>
                                                <?php if (!empty($e['schedules'])) {
                                                    foreach ($e['schedules'] as $s) { ?>
                                                        <span class="label label-default" style="display:inline-block; margin-bottom: 4px; padding: 6px 10px; font-size: 12px; text-align: left; background: #e2e8f0; color: #1e293b; border: 1px solid #cbd5e1;">
                                                            <strong><?php echo htmlspecialchars($s['phase_name']); ?></strong>: Class (<?php echo htmlspecialchars($s['class_names_str']); ?>) | Exam: <?php echo $s['exam_date'] ? date('d M Y h:i A', strtotime($s['exam_date'])) : 'TBA'; ?>
                                                        </span><br>
                                                    <?php }
                                                } else { ?>
                                                    <span class="text-muted">No schedule attached</span>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <!-- Exam Status Toggle -->
                                                <?php if ($e['status'] == 1) { ?>
                                                    <a href="<?php echo site_url('admin/scholarshipexam/toggle_exam_status/' . $e['id']); ?>" class="btn btn-xs btn-success" title="Click to Deactivate"><i class="fa fa-toggle-on"></i> Active</a>
                                                <?php } else { ?>
                                                    <a href="<?php echo site_url('admin/scholarshipexam/toggle_exam_status/' . $e['id']); ?>" class="btn btn-xs btn-default" title="Click to Activate"><i class="fa fa-toggle-off"></i> Inactive</a>
                                                <?php } ?>

                                                <!-- Registration Open / Stop Toggle -->
                                                <?php if (isset($e['registration_status']) && $e['registration_status'] == 0) { ?>
                                                    <a href="<?php echo site_url('admin/scholarshipexam/toggle_registration_status/' . $e['id']); ?>" class="btn btn-xs btn-warning" title="Click to Re-open Registrations"><i class="fa fa-ban"></i> Reg Stopped <?php echo !empty($e['registration_stopped_at']) ? '(' . date('d M h:i A', strtotime($e['registration_stopped_at'])) . ')' : ''; ?></a>
                                                <?php } else { ?>
                                                    <a href="<?php echo site_url('admin/scholarshipexam/toggle_registration_status/' . $e['id']); ?>" class="btn btn-xs btn-primary" title="Click to Stop Registrations"><i class="fa fa-check-circle"></i> Reg Open</a>
                                                <?php } ?>

                                                <!-- Admit Card Release Toggle -->
                                                <?php if (isset($e['admit_card_status']) && $e['admit_card_status'] == 1) { ?>
                                                    <a href="<?php echo site_url('admin/scholarshipexam/toggle_admit_card_status/' . $e['id']); ?>" class="btn btn-xs btn-info" title="Click to Un-release Admit Cards"><i class="fa fa-id-card"></i> Admit Cards Released <?php echo !empty($e['admit_card_released_at']) ? '(' . date('d M h:i A', strtotime($e['admit_card_released_at'])) . ')' : ''; ?></a>
                                                <?php } else { ?>
                                                    <a href="<?php echo site_url('admin/scholarshipexam/toggle_admit_card_status/' . $e['id']); ?>" class="btn btn-xs btn-default" title="Click to Release Admit Cards"><i class="fa fa-id-card-o"></i> Release Admit Cards</a>
                                                <?php } ?>

                                                <!-- Result Release Toggle -->
                                                <?php if (isset($e['result_status_published']) && $e['result_status_published'] == 1) { ?>
                                                    <a href="<?php echo site_url('admin/scholarshipexam/toggle_result_status/' . $e['id']); ?>" class="btn btn-xs bg-purple" title="Click to Un-release Results"><i class="fa fa-award"></i> Results Released <?php echo !empty($e['result_released_at']) ? '(' . date('d M h:i A', strtotime($e['result_released_at'])) . ')' : ''; ?></a>
                                                <?php } else { ?>
                                                    <a href="<?php echo site_url('admin/scholarshipexam/toggle_result_status/' . $e['id']); ?>" class="btn btn-xs btn-default" title="Click to Release Results"><i class="fa fa-trophy"></i> Release Results</a>
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <?php $direct_url = site_url('scholarshipregister/apply/' . urlencode($e['exam_code'])); ?>
                                                <a href="<?php echo $direct_url; ?>" target="_blank" class="btn btn-xs btn-info" title="Open Direct Registration Form"><i class="fa fa-external-link"></i> Direct Public Form</a>
                                            </td>
                                            <td class="text-right">
                                                <button type="button" class="btn btn-xs btn-primary" onclick="editExam(<?php echo $e['id']; ?>)"><i class="fa fa-pencil"></i> Edit</button>
                                                <a href="<?php echo site_url('admin/scholarshipexam/delete_exam/' . $e['id']); ?>" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure you want to delete this exam? All candidate registrations will be removed.');"><i class="fa fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    <?php }
                                } else { ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">No exams configured yet. Click "Create New Exam" above.</td>
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

<!-- Modal Create / Edit Exam -->
<div class="modal fade" id="examModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <form id="examForm" action="<?php echo site_url('admin/scholarshipexam/save_exam'); ?>" method="POST">
            <div class="modal-content" style="border-radius: 12px;">
                <div class="modal-header" style="background: #3b82f6; color: #fff; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                    <button type="button" class="close" data-dismiss="modal" style="color: #fff;">&times;</button>
                    <h4 class="modal-title" id="modalTitle"><i class="fa fa-trophy"></i> Manage Scholarship / Olympiad Exam</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="exam_id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Exam Title *</label>
                                <input type="text" name="title" id="title" class="form-control" placeholder="e.g. National Math Olympiad 2026" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Exam Code *</label>
                                <input type="text" name="exam_code" id="exam_code" class="form-control" placeholder="e.g. NMO-2026" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Category</label>
                                <input type="text" name="exam_category" id="exam_category" class="form-control" placeholder="e.g. Science / Math / Merit">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Exam Mode *</label>
                                <select name="exam_mode" id="exam_mode" class="form-control">
                                    <option value="offline">Offline (OMR / Answer Sheet)</option>
                                    <option value="online">Online (Computer-Based Test)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Roll Number Series Prefix *</label>
                                <input type="text" name="roll_no_prefix" id="roll_no_prefix" class="form-control" value="OLY26-" placeholder="e.g. OLY26- or SCH-" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Fee Option</label>
                                <select name="is_paid" id="is_paid" class="form-control" onchange="toggleFeeInput(this.value)">
                                    <option value="0">Free</option>
                                    <option value="1">Paid</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2" id="fee_box" style="display: none;">
                            <div class="form-group">
                                <label>Fee Amount ($)</label>
                                <input type="number" step="0.01" name="registration_fee" id="registration_fee" class="form-control" placeholder="0.00">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Exam Center / Venue</label>
                                <input type="text" name="exam_center" id="exam_center" class="form-control" value="Main School Campus Auditorium">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Exam Instructions</label>
                                <input type="text" name="instructions" id="instructions" class="form-control" placeholder="e.g. Bring Admit Card and Blue Pen. No calculators allowed.">
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h4><i class="fa fa-calendar"></i> Phased Class Schedules & Multi-Phase Dates</h4>
                    <p class="text-muted small">Specify phase dates, registration start/close, admit card release, exam date, result, and award ceremony date per Class.</p>

                    <div id="scheduleContainer">
                        <div class="schedule-row panel panel-default" style="padding: 12px; margin-bottom: 10px;">
                            <div class="row">
                                <div class="col-md-3">
                                    <label>Phase Name</label>
                                    <input type="text" name="phase_name[]" class="form-control input-sm phase-name" value="Phase 1">
                                </div>
                                <div class="col-md-3">
                                    <label>Target Classes (Multi-Select) *</label>
                                    <select name="class_ids[0][]" class="form-control select2 class-select" multiple="multiple" style="width: 100%;" required>
                                        <?php foreach ($classList as $c) { ?>
                                            <option value="<?php echo $c['id']; ?>"><?php echo $c['class']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label>Registration Start</label>
                                    <input type="date" name="registration_start_date[]" class="form-control input-sm reg-start">
                                </div>
                                <div class="col-md-3">
                                    <label>Registration Close</label>
                                    <input type="date" name="registration_close_date[]" class="form-control input-sm reg-close">
                                </div>
                            </div>

                            <div class="row" style="margin-top: 8px;">
                                <div class="col-md-3">
                                    <label>Admit Card Release Date</label>
                                    <input type="date" name="admit_card_release_date[]" class="form-control input-sm admit-release">
                                </div>
                                <div class="col-md-3">
                                    <label>Exam Date & Time</label>
                                    <input type="datetime-local" name="exam_date[]" class="form-control input-sm exam-date">
                                </div>
                                <div class="col-md-2">
                                    <label>Duration (Mins)</label>
                                    <input type="number" name="duration_minutes[]" class="form-control input-sm duration-mins" value="60">
                                </div>
                                <div class="col-md-2">
                                    <label>Total Marks</label>
                                    <input type="number" step="0.01" name="total_marks[]" class="form-control input-sm total-marks" value="100">
                                </div>
                                <div class="col-md-2">
                                    <label>Pass Marks</label>
                                    <input type="number" step="0.01" name="passing_marks[]" class="form-control input-sm pass-marks" value="40">
                                </div>
                            </div>

                            <div class="row" style="margin-top: 8px;">
                                <div class="col-md-3">
                                    <label>Result Date</label>
                                    <input type="date" name="result_date[]" class="form-control input-sm result-date">
                                </div>
                                <div class="col-md-3">
                                    <label>Award Ceremony Date</label>
                                    <input type="date" name="award_ceremony_date[]" class="form-control input-sm award-date">
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-default btn-sm" onclick="addScheduleRow()"><i class="fa fa-plus-circle"></i> Add Another Class Phase Schedule</button>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn"><i class="fa fa-check"></i> Save Exam</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
var scheduleRowCounter = 1;

$(document).ready(function() {
    $('.select2').select2({
        placeholder: "Select Classes"
    });
});

function openAddExamModal() {
    $('#examForm')[0].reset();
    $('#exam_id').val('');
    $('#modalTitle').html('<i class="fa fa-trophy"></i> Create Scholarship / Olympiad Exam');
    $('.select2').val(null).trigger('change');
    $('#examModal').modal('show');
}

function editExam(id) {
    $.ajax({
        url: '<?php echo site_url("admin/scholarshipexam/get_exam_details/"); ?>' + id,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if (data) {
                $('#exam_id').val(data.id);
                $('#title').val(data.title);
                $('#exam_code').val(data.exam_code);
                $('#exam_category').val(data.exam_category);
                $('#exam_mode').val(data.exam_mode);
                $('#roll_no_prefix').val(data.roll_no_prefix);
                $('#is_paid').val(data.is_paid);
                toggleFeeInput(data.is_paid);
                $('#registration_fee').val(data.registration_fee);
                $('#exam_center').val(data.exam_center);
                $('#instructions').val(data.instructions);

                $('#modalTitle').html('<i class="fa fa-pencil"></i> Edit Scholarship / Olympiad Exam');
                $('#examModal').modal('show');
            }
        }
    });
}

function toggleFeeInput(val) {
    if (val == '1') {
        $('#fee_box').show();
    } else {
        $('#fee_box').hide();
    }
}

function addScheduleRow() {
    var newRow = $('#scheduleContainer .schedule-row:first').clone();
    newRow.find('input').val('');
    newRow.find('.select2-container').remove();
    var selectElem = newRow.find('select.class-select');
    selectElem.attr('name', 'class_ids[' + scheduleRowCounter + '][]');
    selectElem.val(null);
    $('#scheduleContainer').append(newRow);
    selectElem.select2({
        placeholder: "Select Classes"
    });
    scheduleRowCounter++;
}

$('#examForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function(res) {
            if (res.status == 'success') {
                alert(res.message);
                location.reload();
            } else {
                alert('Validation error. Please check form fields.');
            }
        }
    });
});
</script>
