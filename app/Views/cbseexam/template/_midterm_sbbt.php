<?php
if (!empty($result)) {
    // Gather all exams from result terms into a flat list
    $all_exams = [];
    foreach ($result as $term_id => $term_info) {
        if (!empty($term_info['exam'])) {
            foreach ($term_info['exam'] as $ex) {
                $all_exams[$ex['id']] = [
                    'id' => $ex['id'],
                    'name' => $ex['name'],
                    'term_id' => $term_id,
                    'term_name' => $term_info['name']
                ];
            }
        }
    }

    // Decode saved sbbt config if exists in description or templatedata
    $saved_config = [];
    if (!empty($templatedata['description'])) {
        $meta_pos = strpos($templatedata['description'], '<!--SBBT_CONFIG:');
        if ($meta_pos !== false) {
            $json_str = substr($templatedata['description'], $meta_pos + 16);
            $end_pos = strpos($json_str, '-->');
            if ($end_pos !== false) {
                $saved_config = json_decode(substr($json_str, 0, $end_pos), true) ?: [];
            }
        }
    }

    $sbbt1_id = $saved_config['sbbt1_exam_id'] ?? '';
    $sbbt2_id = $saved_config['sbbt2_exam_id'] ?? '';
    $sbbt3_id = $saved_config['sbbt3_exam_id'] ?? '';
    $sbbt4_id = $saved_config['sbbt4_exam_id'] ?? '';
    $tee_id   = $saved_config['tee_exam_id'] ?? '';

    $pt1_max  = $saved_config['pt1_max'] ?? '40';
    $pt2_max  = $saved_config['pt2_max'] ?? '40';
    $tee_max  = $saved_config['tee_max'] ?? '120';

    $grade_exam  = $templatedata['gradeexam_id'] ?? '';
    $remark_exam = $templatedata['remarkexam_id'] ?? '';
?>

<style type="text/css">
    .sbbt-config-card {
        background: #ffffff;
        border: 1px solid #e1e8ed;
        border-radius: 6px;
        padding: 16px;
        margin-bottom: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .sbbt-config-title {
        font-size: 14px;
        font-weight: 700;
        color: #2c3e50;
        margin-top: 0;
        margin-bottom: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px dashed #e1e8ed;
        padding-bottom: 8px;
    }
    .sbbt-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 4px;
    }
    .sbbt-help-text {
        font-size: 12px;
        color: #7f8c8d;
        margin-top: 4px;
    }
    .sbbt-total-bar {
        background: #f8fafc;
        border: 1px solid #cbd5e1;
        border-radius: 6px;
        padding: 12px 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
    }
</style>

<div class="row">
    <!-- PT - I Section -->
    <div class="col-md-12">
        <div class="sbbt-config-card" style="border-left: 4px solid #3498db;">
            <div class="sbbt-config-title">
                <span><i class="fa fa-calculator text-primary"></i> PT - I : Best of Two SBBT (I &amp; II)</span>
                <div class="form-inline">
                    <label style="font-weight: normal; font-size: 12px; margin-right: 6px;">Max Marks:</label>
                    <input type="number" name="pt1_max" id="pt1_max" class="form-control input-sm max-marks-input" value="<?php echo htmlspecialchars($pt1_max); ?>" style="width: 75px; text-align: center; font-weight: bold;" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb10">
                        <label>SBBT - 1 Exam <span class="text-danger">*</span></label>
                        <select name="sbbt1_exam_id" id="sbbt1_exam_id" class="form-control select2 sbbt-select" style="width:100%;" required>
                            <option value="">-- Select Exam --</option>
                            <?php foreach ($all_exams as $eid => $ex): ?>
                                <option value="<?php echo $eid; ?>" <?php echo ($sbbt1_id == $eid) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($ex['name']); ?> (<?php echo htmlspecialchars($ex['term_name']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb10">
                        <label>SBBT - 2 Exam <span class="text-danger">*</span></label>
                        <select name="sbbt2_exam_id" id="sbbt2_exam_id" class="form-control select2 sbbt-select" style="width:100%;" required>
                            <option value="">-- Select Exam --</option>
                            <?php foreach ($all_exams as $eid => $ex): ?>
                                <option value="<?php echo $eid; ?>" <?php echo ($sbbt2_id == $eid) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($ex['name']); ?> (<?php echo htmlspecialchars($ex['term_name']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="sbbt-help-text">
                <i class="fa fa-info-circle"></i> The marksheet will evaluate both exams and print the <b>higher score (Best Of)</b> for PT-I.
            </div>
        </div>
    </div>

    <!-- PT - II Section -->
    <div class="col-md-12">
        <div class="sbbt-config-card" style="border-left: 4px solid #9b59b6;">
            <div class="sbbt-config-title">
                <span><i class="fa fa-calculator text-purple" style="color:#9b59b6;"></i> PT - II : Best of Two SBBT (III &amp; IV)</span>
                <div class="form-inline">
                    <label style="font-weight: normal; font-size: 12px; margin-right: 6px;">Max Marks:</label>
                    <input type="number" name="pt2_max" id="pt2_max" class="form-control input-sm max-marks-input" value="<?php echo htmlspecialchars($pt2_max); ?>" style="width: 75px; text-align: center; font-weight: bold;" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb10">
                        <label>SBBT - 3 Exam <span class="text-danger">*</span></label>
                        <select name="sbbt3_exam_id" id="sbbt3_exam_id" class="form-control select2 sbbt-select" style="width:100%;" required>
                            <option value="">-- Select Exam --</option>
                            <?php foreach ($all_exams as $eid => $ex): ?>
                                <option value="<?php echo $eid; ?>" <?php echo ($sbbt3_id == $eid) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($ex['name']); ?> (<?php echo htmlspecialchars($ex['term_name']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb10">
                        <label>SBBT - 4 Exam <span class="text-danger">*</span></label>
                        <select name="sbbt4_exam_id" id="sbbt4_exam_id" class="form-control select2 sbbt-select" style="width:100%;" required>
                            <option value="">-- Select Exam --</option>
                            <?php foreach ($all_exams as $eid => $ex): ?>
                                <option value="<?php echo $eid; ?>" <?php echo ($sbbt4_id == $eid) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($ex['name']); ?> (<?php echo htmlspecialchars($ex['term_name']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="sbbt-help-text">
                <i class="fa fa-info-circle"></i> The marksheet will evaluate both exams and print the <b>higher score (Best Of)</b> for PT-II.
            </div>
        </div>
    </div>

    <!-- TEE - I Section -->
    <div class="col-md-12">
        <div class="sbbt-config-card" style="border-left: 4px solid #e67e22;">
            <div class="sbbt-config-title">
                <span><i class="fa fa-book text-warning" style="color:#e67e22;"></i> TEE - I : Term End Exam</span>
                <div class="form-inline">
                    <label style="font-weight: normal; font-size: 12px; margin-right: 6px;">Max Marks:</label>
                    <input type="number" name="tee_max" id="tee_max" class="form-control input-sm max-marks-input" value="<?php echo htmlspecialchars($tee_max); ?>" style="width: 75px; text-align: center; font-weight: bold;" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group mb10">
                        <label>TEE - I Exam <span class="text-danger">*</span></label>
                        <select name="tee_exam_id" id="tee_exam_id" class="form-control select2 sbbt-select" style="width:100%;" required>
                            <option value="">-- Select Exam --</option>
                            <?php foreach ($all_exams as $eid => $ex): ?>
                                <option value="<?php echo $eid; ?>" <?php echo ($tee_id == $eid) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($ex['name']); ?> (<?php echo htmlspecialchars($ex['term_name']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="sbbt-help-text">
                <i class="fa fa-info-circle"></i> Directly prints the Term-End theory / component marks for this exam.
            </div>
        </div>
    </div>

    <!-- Attendance & Remarks Section -->
    <div class="col-md-12">
        <div class="sbbt-config-card" style="border-left: 4px solid #27ae60;">
            <div class="sbbt-config-title">
                <span><i class="fa fa-check-square-o text-success"></i> Grading, Teacher Remarks &amp; Attendance Source</span>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group mb10">
                        <label>Print Grade From Exam</label>
                        <select name="grading" id="grading" class="form-control select2" style="width:100%;">
                            <option value="">-- Auto (TEE-I) --</option>
                            <?php foreach ($all_exams as $eid => $ex): ?>
                                <option value="<?php echo $eid; ?>" <?php echo ($grade_exam == $eid) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($ex['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group mb10">
                        <label>Print Remarks &amp; Attendance From Exam</label>
                        <select name="teacher_remark" id="teacher_remark" class="form-control select2" style="width:100%;">
                            <option value="">-- Auto (TEE-I) --</option>
                            <?php foreach ($all_exams as $eid => $ex): ?>
                                <option value="<?php echo $eid; ?>" <?php echo ($remark_exam == $eid) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($ex['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Marks Summary -->
    <div class="col-md-12">
        <div class="sbbt-total-bar">
            <div>
                <span style="font-weight: bold; font-size: 14px; color: #334155;">
                    <i class="fa fa-pie-chart text-info"></i> Subject Total Marks:
                </span>
                <span class="text-muted" style="font-size: 13px; margin-left: 6px;">
                    (<span id="lbl_pt1">40</span> PT-I + <span id="lbl_pt2">40</span> PT-II + <span id="lbl_tee">120</span> TEE-I)
                </span>
            </div>
            <div>
                <span id="sbbt_grand_total_badge" class="badge" style="font-size: 15px; padding: 6px 14px; background-color: #0284c7;">
                    TOTAL: 200 MARKS
                </span>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
(function($) {
    "use strict";

    function updateSummary() {
        var pt1 = parseInt($('#pt1_max').val()) || 0;
        var pt2 = parseInt($('#pt2_max').val()) || 0;
        var tee = parseInt($('#tee_max').val()) || 0;
        var total = pt1 + pt2 + tee;

        $('#lbl_pt1').text(pt1);
        $('#lbl_pt2').text(pt2);
        $('#lbl_tee').text(tee);
        $('#sbbt_grand_total_badge').text('TOTAL: ' + total + ' MARKS');
    }

    $('.max-marks-input').on('input change', updateSummary);
    updateSummary();

    // Auto-sync grading and remarks if left blank
    $('#tee_exam_id').on('change', function() {
        var val = $(this).val();
        if (!$('#grading').val()) {
            $('#grading').val(val);
        }
        if (!$('#teacher_remark').val()) {
            $('#teacher_remark').val(val);
        }
    });
})(jQuery);
</script>

<?php } else { ?>
    <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
<?php } ?>
