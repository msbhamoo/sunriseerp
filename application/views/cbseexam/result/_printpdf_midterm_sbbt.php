<style type="text/css">
    @media print {
        body { -webkit-print-color-adjust: exact; print-color-adjust: exact; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; }
    }
    .midterm-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 5px;
        font-size: 12px;
    }
    .midterm-table th, .midterm-table td {
        border: 1px solid #475569;
        padding: 5px 6px;
    }
    .midterm-table thead th {
        background-color: #fef08a !important; /* Soft yellow header like sample */
        color: #1e293b;
        font-weight: bold;
        text-align: center;
    }
    .midterm-header-title {
        font-size: 16px;
        font-weight: 800;
        text-align: center;
        letter-spacing: 0.5px;
        padding-bottom: 8px;
        text-decoration: underline;
    }
    .profile-card-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 11.5px;
        margin-bottom: 12px;
    }
    .profile-card-table th, .profile-card-table td {
        border: 1px solid #64748b;
        padding: 4px 8px;
    }
    .profile-card-header {
        background-color: #bae6fd !important; /* Light sky blue from sample */
        font-weight: 800;
        text-align: center;
        font-size: 13px;
        letter-spacing: 0.5px;
        color: #0369a1;
        padding: 5px !important;
    }
    .part-header {
        font-weight: 800;
        font-size: 13px;
        text-align: center;
        margin: 10px 0 6px 0;
        letter-spacing: 0.5px;
        color: #0f172a;
    }
    .highlight-red {
        color: #dc2626;
        font-weight: bold;
        font-size: 10.5px;
    }
    .text-center { text-align: center; }
    .text-left { text-align: left; }
    .text-right { text-align: right; }
    .font-bold { font-weight: bold; }
</style>

<?php
// Decode SBBT configuration from template description
$sbbt_config = [];
if (!empty($template['description'])) {
    $meta_pos = strpos($template['description'], '<!--SBBT_CONFIG:');
    if ($meta_pos !== false) {
        $json_str = substr($template['description'], $meta_pos + 16);
        $end_pos = strpos($json_str, '-->');
        if ($end_pos !== false) {
            $sbbt_config = json_decode(substr($json_str, 0, $end_pos), true) ?: [];
        }
    }
}

$sbbt1_exam_id = $sbbt_config['sbbt1_exam_id'] ?? 0;
$sbbt2_exam_id = $sbbt_config['sbbt2_exam_id'] ?? 0;
$sbbt3_exam_id = $sbbt_config['sbbt3_exam_id'] ?? 0;
$sbbt4_exam_id = $sbbt_config['sbbt4_exam_id'] ?? 0;
$tee_exam_id   = $sbbt_config['tee_exam_id'] ?? 0;

$pt1_max_config = !empty($sbbt_config['pt1_max']) ? (float)$sbbt_config['pt1_max'] : 40;
$pt2_max_config = !empty($sbbt_config['pt2_max']) ? (float)$sbbt_config['pt2_max'] : 40;
$tee_max_config = !empty($sbbt_config['tee_max']) ? (float)$sbbt_config['tee_max'] : 120;
$subject_max_total = $pt1_max_config + $pt2_max_config + $tee_max_config;

$student_count = count($result);
$student_idx = 0;

foreach ($result as $student_key => $student_value):
    $student_idx++;

    // Aggregate totals for the student
    $grand_total_obtained = 0;
    $grand_total_max = 0;
?>

<div style="<?php echo ($student_idx < $student_count) ? 'page-break-after: always;' : ''; ?> padding: 15px 20px;">
    
    <!-- Optional Header Image from Template -->
    <?php if (!empty($template['header_image'])): ?>
        <div style="text-align: center; margin-bottom: 10px;">
            <img src="<?php echo base_url('uploads/cbseexam/template/header_image/' . $template['header_image']); ?>" style="max-width: 100%; height: auto;">
        </div>
    <?php endif; ?>

    <!-- Report Card Title Header -->
    <div class="midterm-header-title">
        MID TERM REPORT CARD : SESSION <?php echo !empty($current_setting['session']) ? $current_setting['session'] : date('Y') . '-' . (date('y') + 1); ?>
    </div>

    <!-- STUDENT'S PROFILE TABLE -->
    <table class="profile-card-table">
        <thead>
            <tr>
                <th colspan="4" class="profile-card-header">STUDENT'S PROFILE</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th width="18%" class="text-left">ADMISSION NO.</th>
                <td width="32%"><?php echo !empty($student_value['admission_no']) ? $student_value['admission_no'] : '-'; ?></td>
                <th width="18%" class="text-left">ROLL NO.</th>
                <td width="32%"><?php echo !empty($student_value['roll_no']) ? $student_value['roll_no'] : '-'; ?></td>
            </tr>
            <tr>
                <th class="text-left">NAME</th>
                <td class="font-bold">
                    <?php 
                    $sch_middle = is_object($sch_setting) ? ($sch_setting->middlename ?? 0) : ($sch_setting['middlename'] ?? 0);
                    $sch_last   = is_object($sch_setting) ? ($sch_setting->lastname ?? 0) : ($sch_setting['lastname'] ?? 0);
                    echo strtoupper($this->customlib->getFullName($student_value['firstname'] ?? '', $student_value['middlename'] ?? '', $student_value['lastname'] ?? '', $sch_middle, $sch_last)); 
                    ?>
                </td>
                <th class="text-left">CLASS</th>
                <td><?php echo strtoupper($student_value['class'] ?? ''); ?><?php echo !empty($student_value['section']) ? ' (' . $student_value['section'] . ')' : ''; ?></td>
            </tr>
            <tr>
                <th class="text-left">FATHER'S NAME</th>
                <td><?php echo strtoupper($student_value['father_name'] ?? '-'); ?></td>
                <th class="text-left">MOTHER'S NAME</th>
                <td><?php echo strtoupper($student_value['mother_name'] ?? '-'); ?></td>
            </tr>
            <tr>
                <th class="text-left">DATE OF BIRTH</th>
                <td>
                    <?php 
                    if (!empty($student_value['dob']) && $student_value['dob'] != '0000-00-00') {
                        echo date('d/m/Y', strtotime($student_value['dob']));
                    } else {
                        echo '-';
                    }
                    ?>
                </td>
                <th class="text-left">ADDRESS</th>
                <td>
                    <?php
                    $addr = !empty($student_value['current_address']) ? $student_value['current_address'] : (!empty($student_value['permanent_address']) ? $student_value['permanent_address'] : '');
                    echo !empty($addr) ? strtoupper($addr) : '-';
                    ?>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- PART - A : SCHOLASTIC AREAS -->
    <div class="part-header">PART - A : SCHOLASTIC AREAS</div>

    <table class="midterm-table">
        <thead>
            <tr>
                <th rowspan="2" width="28%" style="vertical-align: middle; background-color: #ffffff !important; border-bottom: 2px solid #334155;">
                    SUBJECT
                </th>
                <th colspan="4" style="background-color: #fef08a !important; padding: 6px;">
                    TERM 1<br>(T1)
                </th>
            </tr>
            <tr>
                <th width="18%" style="background-color: #ffffff !important;">
                    PT - I<br>
                    <span class="highlight-red">BEST OF<br>TWO SBBT<br>(I &amp; II)</span>
                </th>
                <th width="18%" style="background-color: #ffffff !important;">
                    PT - II<br>
                    <span class="highlight-red">BEST OF<br>TWO SBBT<br>(III &amp; IV)</span>
                </th>
                <th width="18%" style="background-color: #ffffff !important;">
                    TEE - I
                </th>
                <th width="18%" style="background-color: #ffffff !important;">
                    TOTAL<br>(<?php echo (int)$subject_max_total; ?>)
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($subject_array as $sub_id => $sub_name):
                // 1. Get marks for SBBT 1 & 2
                $sbbt1_data = getExamSubjectMarks($student_value, $sbbt1_exam_id, $sub_id);
                $sbbt2_data = getExamSubjectMarks($student_value, $sbbt2_exam_id, $sub_id);

                // Best of Two for PT - I
                $pt1_score = null;
                if ($sbbt1_data['has_marks'] || $sbbt2_data['has_marks']) {
                    $m1 = $sbbt1_data['has_marks'] ? (float)$sbbt1_data['marks'] : 0;
                    $m2 = $sbbt2_data['has_marks'] ? (float)$sbbt2_data['marks'] : 0;
                    $pt1_score = max($m1, $m2);
                }

                // 2. Get marks for SBBT 3 & 4
                $sbbt3_data = getExamSubjectMarks($student_value, $sbbt3_exam_id, $sub_id);
                $sbbt4_data = getExamSubjectMarks($student_value, $sbbt4_exam_id, $sub_id);

                // Best of Two for PT - II
                $pt2_score = null;
                if ($sbbt3_data['has_marks'] || $sbbt4_data['has_marks']) {
                    $m3 = $sbbt3_data['has_marks'] ? (float)$sbbt3_data['marks'] : 0;
                    $m4 = $sbbt4_data['has_marks'] ? (float)$sbbt4_data['marks'] : 0;
                    $pt2_score = max($m3, $m4);
                }

                // 3. Get marks for TEE - I
                $tee_data = getExamSubjectMarks($student_value, $tee_exam_id, $sub_id);
                $tee_score = $tee_data['has_marks'] ? (float)$tee_data['marks'] : null;

                // 4. Calculate Subject Total
                $row_obtained = 0;
                $has_any_score = false;

                if ($pt1_score !== null) { $row_obtained += $pt1_score; $has_any_score = true; }
                if ($pt2_score !== null) { $row_obtained += $pt2_score; $has_any_score = true; }
                if ($tee_score !== null) { $row_obtained += $tee_score; $has_any_score = true; }

                if ($has_any_score) {
                    $grand_total_obtained += $row_obtained;
                    $grand_total_max += $subject_max_total;
                }
            ?>
            <tr>
                <td class="font-bold text-left" style="padding-left: 10px;">
                    <?php echo strtoupper($sub_name); ?>
                </td>
                <td class="text-center font-bold">
                    <?php echo ($pt1_score !== null) ? $pt1_score : '-'; ?>
                </td>
                <td class="text-center font-bold">
                    <?php echo ($pt2_score !== null) ? $pt2_score : '-'; ?>
                </td>
                <td class="text-center font-bold">
                    <?php echo ($tee_score !== null) ? $tee_score : '-'; ?>
                </td>
                <td class="text-center font-bold">
                    <?php echo $has_any_score ? $row_obtained : '-'; ?>
                </td>
            </tr>
            <?php endforeach; ?>

            <?php
            // Calculate overall percentage and result status
            $overall_percentage = ($grand_total_max > 0) ? round(($grand_total_obtained / $grand_total_max) * 100, 2) : 0;
            $result_status = ($overall_percentage >= 33) ? 'PASSED' : 'COMPARTMENT';
            ?>
            <!-- Grand Total / Summary Rows -->
            <tr style="background-color: #f8fafc; font-weight: bold;">
                <td class="text-left font-bold" style="padding-left: 10px;">GRAND TOTAL</td>
                <td colspan="4" class="text-center font-bold"><?php echo $grand_total_obtained; ?> / <?php echo $grand_total_max; ?></td>
            </tr>
            <tr style="background-color: #f8fafc; font-weight: bold;">
                <td class="text-left font-bold" style="padding-left: 10px;">PERCENTAGE &amp; RESULT</td>
                <td colspan="2" class="text-center font-bold"><?php echo $overall_percentage; ?>%</td>
                <td colspan="2" class="text-center font-bold" style="color: <?php echo ($result_status == 'PASSED') ? '#15803d' : '#b91c1c'; ?>;">
                    <?php echo $result_status; ?>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Signatures Section -->
    <div style="margin-top: 50px; width: 100%;">
        <table width="100%" style="border: none;">
            <tr>
                <td width="33%" class="text-center" style="border: none;">
                    <?php if (!empty($template['left_sign'])): ?>
                        <img src="<?php echo base_url('uploads/cbseexam/template/left_sign/' . $template['left_sign']); ?>" style="max-height: 45px;"><br>
                    <?php endif; ?>
                    <b style="border-top: 1px solid #334155; padding-top: 4px; display: inline-block; min-width: 140px;">Class Teacher</b>
                </td>
                <td width="33%" class="text-center" style="border: none;">
                    <?php if (!empty($template['middle_sign'])): ?>
                        <img src="<?php echo base_url('uploads/cbseexam/template/middle_sign/' . $template['middle_sign']); ?>" style="max-height: 45px;"><br>
                    <?php endif; ?>
                    <b style="border-top: 1px solid #334155; padding-top: 4px; display: inline-block; min-width: 140px;">Exam Incharge</b>
                </td>
                <td width="33%" class="text-center" style="border: none;">
                    <?php if (!empty($template['right_sign'])): ?>
                        <img src="<?php echo base_url('uploads/cbseexam/template/right_sign/' . $template['right_sign']); ?>" style="max-height: 45px;"><br>
                    <?php endif; ?>
                    <b style="border-top: 1px solid #334155; padding-top: 4px; display: inline-block; min-width: 140px;">Principal</b>
                </td>
            </tr>
        </table>
    </div>

</div>

<?php endforeach; ?>

<?php
/**
 * Helper to extract subject marks for a student given an exam ID and subject ID
 */
if (!function_exists('getExamSubjectMarks')) {
    function getExamSubjectMarks($student_value, $exam_id, $subject_id) {
        $result = ['has_marks' => false, 'marks' => 0, 'max' => 0, 'is_absent' => 0];
        if (empty($exam_id) || empty($student_value)) {
            return $result;
        }

        // 1. Search in student terms -> exams -> subjects structure
        if (!empty($student_value['terms'])) {
            foreach ($student_value['terms'] as $term) {
                if (!empty($term['exams'][$exam_id]['subjects'][$subject_id])) {
                    $sub = $term['exams'][$exam_id]['subjects'][$subject_id];
                    $total = 0;
                    $max_tot = 0;
                    $has = false;
                    if (!empty($sub['exam_assessments'])) {
                        foreach ($sub['exam_assessments'] as $assess) {
                            if (!$assess['is_absent'] && $assess['marks'] !== 'N/A' && $assess['marks'] !== null) {
                                $total += (float)$assess['marks'];
                                $has = true;
                            }
                            $max_tot += (float)$assess['maximum_marks'];
                        }
                    }
                    if ($has) {
                        return ['has_marks' => true, 'marks' => $total, 'max' => $max_tot, 'is_absent' => 0];
                    }
                }
            }
        }

        // 2. Search in student term -> exams structure (singular)
        if (!empty($student_value['term']['exams'][$exam_id]['subjects'][$subject_id])) {
            $sub = $student_value['term']['exams'][$exam_id]['subjects'][$subject_id];
            $total = 0;
            $max_tot = 0;
            $has = false;
            if (!empty($sub['exam_assessments'])) {
                foreach ($sub['exam_assessments'] as $assess) {
                    if (!$assess['is_absent'] && $assess['marks'] !== 'N/A' && $assess['marks'] !== null) {
                        $total += (float)$assess['marks'];
                        $has = true;
                    }
                    $max_tot += (float)$assess['maximum_marks'];
                }
            }
            if ($has) {
                return ['has_marks' => true, 'marks' => $total, 'max' => $max_tot, 'is_absent' => 0];
            }
        }

        // 3. Search direct exams list if present
        if (!empty($student_value['exams'][$exam_id]['subjects'][$subject_id])) {
            $sub = $student_value['exams'][$exam_id]['subjects'][$subject_id];
            $total = 0;
            $max_tot = 0;
            $has = false;
            if (!empty($sub['exam_assessments'])) {
                foreach ($sub['exam_assessments'] as $assess) {
                    if (!$assess['is_absent'] && $assess['marks'] !== 'N/A' && $assess['marks'] !== null) {
                        $total += (float)$assess['marks'];
                        $has = true;
                    }
                    $max_tot += (float)$assess['maximum_marks'];
                }
            }
            if ($has) {
                return ['has_marks' => true, 'marks' => $total, 'max' => $max_tot, 'is_absent' => 0];
            }
        }

        return $result;
    }
}
?>
