<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-cogs"></i> Scholarship & Olympiad Exams Setup</h1>
    </section>

    <section class="content">
        <style>
            .sx-top { display: flex; justify-content: space-between; align-items: center; gap: 12px; flex-wrap: wrap; margin-bottom: 18px; }
            .sx-top p { margin: 0; color: #64748b; }
            .sx-btn { border: 0; border-radius: 8px; padding: 9px 16px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 7px; text-decoration: none !important; font-size: 13.5px; }
            .sx-btn-primary { background: #1b4f9c; color: #fff; } .sx-btn-primary:hover { background: #12356f; color: #fff; }
            .sx-btn-light { background: #eef3fa; color: #12356f; } .sx-btn-light:hover { background: #dfe9f6; color: #12356f; }
            .sx-btn-danger { background: #fff; color: #c11a20; border: 1px solid #f3c4c6; } .sx-btn-danger:hover { background: #fdecec; color: #c11a20; }
            .sx-card { background: #fff; border: 1px solid #e2e8f0; border-radius: 14px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(15,23,42,.05); overflow: hidden; }
            .sx-head { padding: 18px 22px; display: flex; justify-content: space-between; gap: 14px; flex-wrap: wrap; align-items: flex-start; border-bottom: 1px solid #eef2f7; }
            .sx-title { margin: 0 0 6px; font-size: 20px; font-weight: 700; color: #0f172a; }
            .sx-meta { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
            .sx-chip { display: inline-block; padding: 3px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; background: #eef3fa; color: #12356f; }
            .sx-chip.warn { background: #fff4e0; color: #9a5b00; } .sx-chip.info { background: #e3f4fb; color: #0b6a8a; }
            .sx-chip.ok { background: #e2f7e9; color: #12713a; } .sx-chip.bad { background: #fde8e8; color: #b42318; } .sx-chip.off { background: #eceff3; color: #5b6676; }
            .sx-stats { display: flex; gap: 22px; }
            .sx-stat { text-align: center; } .sx-stat b { display: block; font-size: 22px; color: #12356f; line-height: 1.1; } .sx-stat span { font-size: 11px; text-transform: uppercase; letter-spacing: .5px; color: #64748b; }
            .sx-body { display: grid; grid-template-columns: 1.55fr 1fr; gap: 0; }
            .sx-col { padding: 18px 22px; } .sx-col + .sx-col { border-left: 1px solid #eef2f7; background: #fafcfe; }
            .sx-h { font-size: 12px; text-transform: uppercase; letter-spacing: .7px; color: #64748b; font-weight: 700; margin: 0 0 10px; }
            .sx-link { display: flex; gap: 8px; margin-bottom: 18px; }
            .sx-link input { flex: 1; min-width: 0; border: 1px solid #d5deea; border-radius: 8px; padding: 8px 12px; background: #f6f9fd; font-size: 13px; color: #12356f; }
            table.sx-t { width: 100%; border-collapse: collapse; font-size: 13px; }
            table.sx-t th { text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: .5px; color: #64748b; padding: 6px 8px; border-bottom: 1px solid #e2e8f0; white-space: nowrap; }
            table.sx-t td { padding: 9px 8px; border-bottom: 1px solid #f0f3f8; vertical-align: top; }
            table.sx-t tr:last-child td { border-bottom: 0; }
            .sx-sw { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 11px 0; border-bottom: 1px solid #edf1f6; }
            .sx-sw:last-of-type { border-bottom: 0; }
            .sx-sw b { display: block; font-size: 14px; color: #1e293b; } .sx-sw small { color: #64748b; font-size: 12px; }
            .sx-tg { flex: none; width: 46px; height: 26px; border-radius: 999px; background: #cbd5e1; position: relative; transition: background .2s; display: block; }
            .sx-tg::after { content: ""; position: absolute; top: 3px; left: 3px; width: 20px; height: 20px; border-radius: 50%; background: #fff; transition: left .2s; box-shadow: 0 1px 3px rgba(0,0,0,.3); }
            .sx-tg.on { background: #16a34a; } .sx-tg.on::after { left: 23px; }
            .sx-foot { padding: 14px 22px; border-top: 1px solid #eef2f7; display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
            .sx-foot .sp { flex: 1; }
            .sx-empty { text-align: center; padding: 50px 20px; color: #64748b; background: #fff; border: 2px dashed #d5deea; border-radius: 14px; }
            @media (max-width: 991px) { .sx-body { grid-template-columns: 1fr; } .sx-col + .sx-col { border-left: 0; border-top: 1px solid #eef2f7; } }
        </style>

        <div class="sx-top">
            <p><i class="fa fa-info-circle"></i> Create exams, set phase dates, and control what students can do (register, admit card, result).</p>
            <button type="button" class="sx-btn sx-btn-primary" onclick="openAddExamModal()"><i class="fa fa-plus"></i> Create New Exam</button>
        </div>

        <?php if (!empty($exams)) {
            foreach ($exams as $e) {
                $direct_url = site_url('scholarshipregister/apply/' . urlencode($e['exam_code']));
                $is_active = ($e['status'] == 1);
                $reg_open = !(isset($e['registration_status']) && $e['registration_status'] == 0);
                $admit_on = (isset($e['admit_card_status']) && $e['admit_card_status'] == 1);
                $result_on = (isset($e['result_status_published']) && $e['result_status_published'] == 1);
                $stamp = function ($v) { return !empty($v) ? date('d M, h:i A', strtotime($v)) : ''; };
                $dt = function ($v, $time = false) { return !empty($v) ? date($time ? 'd M Y, h:i A' : 'd M Y', strtotime($v)) : '—'; };
                if (!$is_active) { $live = '<span class="sx-chip off">Inactive – page hidden</span>'; }
                elseif (!$reg_open) { $live = '<span class="sx-chip bad">Registration closed</span>'; }
                else { $live = '<span class="sx-chip ok"><i class="fa fa-circle"></i> Live – accepting registrations</span>'; }
                ?>
                <div class="sx-card">
                    <div class="sx-head">
                        <div>
                            <h3 class="sx-title"><?php echo htmlspecialchars($e['title']); ?></h3>
                            <div class="sx-meta">
                                <span class="sx-chip"><?php echo htmlspecialchars($e['exam_code']); ?></span>
                                <?php echo ($e['exam_mode'] == 'online') ? '<span class="sx-chip info">Online CBT</span>' : '<span class="sx-chip warn">Offline OMR</span>'; ?>
                                <?php echo ($e['is_paid'] == 1) ? '<span class="sx-chip bad">Paid ₹' . number_format($e['registration_fee'], 2) . '</span>' : '<span class="sx-chip ok">Free</span>'; ?>
                                <?php echo $live; ?>
                            </div>
                        </div>
                        <div class="sx-stats">
                            <div class="sx-stat"><b><?php echo (int) $e['candidate_count']; ?></b><span>Candidates</span></div>
                            <div class="sx-stat"><b><?php echo (int) $e['question_count']; ?></b><span>Questions</span></div>
                            <div class="sx-stat"><b><?php echo count($e['schedules']); ?></b><span>Phases</span></div>
                        </div>
                    </div>

                    <div class="sx-body">
                        <div class="sx-col">
                            <p class="sx-h">Public registration link</p>
                            <div class="sx-link">
                                <input type="text" readonly value="<?php echo htmlspecialchars($direct_url); ?>" onclick="this.select()">
                                <button type="button" class="sx-btn sx-btn-light" onclick="copyLink(this, '<?php echo htmlspecialchars($direct_url, ENT_QUOTES); ?>')"><i class="fa fa-clipboard"></i> Copy</button>
                                <a href="<?php echo $direct_url; ?>" target="_blank" class="sx-btn sx-btn-light" title="Open public page"><i class="fa fa-external-link"></i></a>
                                <a href="<?php echo site_url('admin/scholarshipexam/setting'); ?>" class="sx-btn sx-btn-light" title="Permanent link &amp; QR code"><i class="fa fa-qrcode"></i> QR</a>
                            </div>

                            <p class="sx-h">Phases &amp; dates</p>
                            <?php if (!empty($e['schedules'])) { ?>
                                <div class="table-responsive">
                                    <table class="sx-t">
                                        <thead><tr><th>Phase</th><th>Classes</th><th>Exam</th><th>Reg. closes</th><th>Admit card</th><th>Result</th></tr></thead>
                                        <tbody>
                                        <?php foreach ($e['schedules'] as $sc) { ?>
                                            <tr>
                                                <td><b><?php echo htmlspecialchars($sc['phase_name']); ?></b></td>
                                                <td><?php echo htmlspecialchars($sc['class_names_str']); ?></td>
                                                <td><b><?php echo $dt($sc['exam_date'], true); ?></b></td>
                                                <td><?php echo $dt($sc['registration_close_date']); ?></td>
                                                <td><?php echo $dt($sc['admit_card_release_date']); ?></td>
                                                <td><?php echo $dt($sc['result_date']); ?></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php } else { ?>
                                <span class="text-muted">No schedule yet — click Edit to add phases.</span>
                            <?php } ?>
                        </div>

                        <div class="sx-col">
                            <p class="sx-h">Controls</p>
                            <div class="sx-sw">
                                <div><b>Exam active</b><small><?php echo $is_active ? 'Public page is visible' : 'Public page is hidden'; ?></small></div>
                                <a href="<?php echo site_url('admin/scholarshipexam/toggle_exam_status/' . $e['id']); ?>" title="Toggle exam active"><span class="sx-tg <?php echo $is_active ? 'on' : ''; ?>"></span></a>
                            </div>
                            <div class="sx-sw">
                                <div><b>Registration open</b><small><?php echo $reg_open ? 'Students can register' : 'Stopped' . ($stamp($e['registration_stopped_at'] ?? '') ? ' on ' . $stamp($e['registration_stopped_at']) : ''); ?></small></div>
                                <a href="<?php echo site_url('admin/scholarshipexam/toggle_registration_status/' . $e['id']); ?>" title="Toggle registration"><span class="sx-tg <?php echo $reg_open ? 'on' : ''; ?>"></span></a>
                            </div>
                            <div class="sx-sw">
                                <div><b>Admit cards released</b><small><?php echo $admit_on ? 'Released' . ($stamp($e['admit_card_released_at'] ?? '') ? ' ' . $stamp($e['admit_card_released_at']) : '') : 'Students cannot download yet'; ?></small></div>
                                <a href="<?php echo site_url('admin/scholarshipexam/toggle_admit_card_status/' . $e['id']); ?>" title="Toggle admit cards"><span class="sx-tg <?php echo $admit_on ? 'on' : ''; ?>"></span></a>
                            </div>
                            <div class="sx-sw">
                                <div><b>Results published</b><small><?php echo $result_on ? 'Published' . ($stamp($e['result_released_at'] ?? '') ? ' ' . $stamp($e['result_released_at']) : '') : 'Hidden from students'; ?></small></div>
                                <a href="<?php echo site_url('admin/scholarshipexam/toggle_result_status/' . $e['id']); ?>" title="Toggle results"><span class="sx-tg <?php echo $result_on ? 'on' : ''; ?>"></span></a>
                            </div>
                        </div>
                    </div>

                    <div class="sx-foot">
                        <button type="button" class="sx-btn sx-btn-primary" onclick="editExam(<?php echo (int) $e['id']; ?>)"><i class="fa fa-pencil"></i> Edit exam &amp; dates</button>
                        <a href="<?php echo site_url('admin/scholarshipexam/candidates?exam_id=' . $e['id']); ?>" class="sx-btn sx-btn-light"><i class="fa fa-users"></i> Candidates</a>
                        <a href="<?php echo site_url('admin/scholarshipexam/brochure/' . $e['id']); ?>" target="_blank" class="sx-btn sx-btn-light"><i class="fa fa-file-text-o"></i> Brochure</a>
                        <span class="sp"></span>
                        <a href="<?php echo site_url('admin/scholarshipexam/delete_exam/' . $e['id']); ?>" class="sx-btn sx-btn-danger" onclick="return confirm('Delete this exam? All candidate registrations will be removed.');"><i class="fa fa-trash"></i> Delete</a>
                    </div>
                </div>
            <?php }
        } else { ?>
            <div class="sx-empty">
                <i class="fa fa-trophy" style="font-size:42px;color:#c5d3e6;"></i>
                <h4>No exams yet</h4>
                <p>Create your first scholarship exam to get a public registration link and QR code.</p>
                <button type="button" class="sx-btn sx-btn-primary" onclick="openAddExamModal()"><i class="fa fa-plus"></i> Create New Exam</button>
            </div>
        <?php } ?>
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
                                <label>Fee Amount (₹)</label>
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

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Eligibility & Awards / Full Description</label>
                                <textarea name="description" id="description" class="form-control" rows="3" placeholder="Eligibility, syllabus breakdown, awards, WhatsApp contact, etc."></textarea>
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
// Phase-row template captured before any plugin touches it; rows are built fresh from it.
var scheduleRowCounter = 0;
var scheduleRowTemplate = $('#scheduleContainer .schedule-row:first').prop('outerHTML');
$('#scheduleContainer').empty();

function buildScheduleRow(s) {
    s = s || {};
    var d = function (v) { return v ? String(v).substring(0, 10) : ''; };
    var row = $(scheduleRowTemplate);
    row.find('.phase-name').val(s.phase_name || ('Phase ' + (scheduleRowCounter + 1)));
    row.find('.reg-start').val(d(s.registration_start_date));
    row.find('.reg-close').val(d(s.registration_close_date));
    row.find('.admit-release').val(d(s.admit_card_release_date));
    row.find('.exam-date').val(s.exam_date ? String(s.exam_date).replace(' ', 'T').substring(0, 16) : '');
    row.find('.duration-mins').val(s.duration_minutes || 60);
    row.find('.total-marks').val(s.total_marks || 100);
    row.find('.pass-marks').val(s.passing_marks || 40);
    row.find('.result-date').val(d(s.result_date));
    row.find('.award-date').val(d(s.award_ceremony_date));

    var sel = row.find('select.class-select');
    sel.attr('name', 'class_ids[' + scheduleRowCounter + '][]');
    var chosen = $.map(s.class_id_array || [], String);
    // Mark the saved classes as selected in the markup itself, then let select2 render them
    sel.find('option').each(function () {
        $(this).prop('selected', $.inArray(String($(this).val()), chosen) !== -1);
    });
    $('#scheduleContainer').append(row);
    sel.select2({ placeholder: 'Select Classes', width: '100%', dropdownParent: $('#examModal') });
    scheduleRowCounter++;
    return row;
}

function resetScheduleRows(list) {
    $('#scheduleContainer').empty();
    scheduleRowCounter = 0;
    if (list && list.length) {
        for (var i = 0; i < list.length; i++) { buildScheduleRow(list[i]); }
    } else {
        buildScheduleRow();
    }
}

// Re-render the class tags once the modal is visible (select2 measures widths while hidden)
$('#examModal').on('shown.bs.modal', function () {
    $('#scheduleContainer select.class-select').trigger('change.select2');
});

function addScheduleRow() {
    buildScheduleRow();
}

function openAddExamModal() {
    $('#examForm')[0].reset();
    $('#exam_id').val('');
    $('#modalTitle').html('<i class="fa fa-trophy"></i> Create Scholarship / Olympiad Exam');
    resetScheduleRows();
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
                $('#description').val(data.description);

                resetScheduleRows(data.schedules || []);

                $('#modalTitle').html('<i class="fa fa-pencil"></i> Edit Scholarship / Olympiad Exam');
                $('#examModal').modal('show');
            }
        }
    });
}

function copyLink(btn, url) {
    var done = function () { var h = btn.innerHTML; btn.innerHTML = '<i class="fa fa-check"></i> Copied'; setTimeout(function () { btn.innerHTML = h; }, 1500); };
    if (navigator.clipboard) { navigator.clipboard.writeText(url).then(done); }
    else { var i = document.createElement('input'); i.value = url; document.body.appendChild(i); i.select(); document.execCommand('copy'); document.body.removeChild(i); done(); }
}

function toggleFeeInput(val) {
    if (val == '1') {
        $('#fee_box').show();
    } else {
        $('#fee_box').hide();
    }
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
