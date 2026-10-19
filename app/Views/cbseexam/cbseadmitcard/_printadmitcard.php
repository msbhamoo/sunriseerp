<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<style>
    * {
        box-sizing: border-box;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
        font-family: Arial, Helvetica, sans-serif;
    }

    html, body {
        margin: 0;
        padding: 0;
        background: #fff;
        color: #000;
    }

    .admit-card-wrapper {
        width: 100%;
        page-break-inside: avoid;
        margin: 0 auto;
        padding: 0;
    }

    .admit-card-box {
        width: 100%;
        border: 1px solid #777;
        padding: 4px 10px 4px 10px;
        position: relative;
        overflow: hidden;
        page-break-inside: avoid;
        box-sizing: border-box;
    }

    .cut-line-wrapper {
        width: 100%;
        text-align: center;
        margin: 2mm 0;
        padding: 0;
        line-height: 1;
    }

    .cut-line-text {
        font-size: 8px;
        color: #555;
        letter-spacing: 0.5px;
        font-weight: bold;
    }

    .badge-pill {
        background-color: #525b62;
        color: #ffffff;
        font-weight: bold;
        font-size: 10px;
        padding: 1.5px 22px;
        border-radius: 3px;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .meta-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 9.5px;
        line-height: 1.3;
    }
    .meta-label {
        font-weight: bold;
        color: #000;
        white-space: nowrap;
        padding: 1px 0;
    }
    .meta-val {
        color: #000;
        font-weight: normal;
        padding: 1px 0;
    }

    .photo-box {
        width: 72px;
        height: 84px;
        border: 1px solid #888;
        text-align: center;
        overflow: hidden;
        display: inline-block;
    }

    .schedule-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 10.5px;
        margin-top: 2px;
    }
    .schedule-table th {
        background-color: #525b62;
        color: #ffffff;
        border: 1px solid #888;
        padding: 2px 4px;
        text-align: center;
        font-weight: bold;
        font-size: 10px;
    }
    .schedule-table td {
        border: 1px solid #888;
        padding: 2px 4px;
        text-align: center;
        font-size: 10.5px;
        font-weight: bold;
    }

    @media print {
        .pagebreak {
            page-break-after: always;
        }
    }
</style>
</head>
<body>

<?php 
$CI =& get_instance();

if (!function_exists('resolve_admit_image_src')) {
    function resolve_admit_image_src($candidates, $fallback_url = '') {
        foreach ($candidates as $candidate) {
            if (empty($candidate)) continue;
            
            // Check direct file path
            if (file_exists($candidate) && is_file($candidate)) {
                $ext = strtolower(pathinfo($candidate, PATHINFO_EXTENSION));
                $mime = ($ext == 'png') ? 'image/png' : (($ext == 'svg') ? 'image/svg+xml' : 'image/jpeg');
                $data = @file_get_contents($candidate);
                if ($data !== false && strlen($data) > 0) {
                    return 'data:' . $mime . ';base64,' . base64_encode($data);
                }
            }

            // Check relative to FCPATH
            $fcpath_cand = FCPATH . ltrim($candidate, '/\\');
            if (file_exists($fcpath_cand) && is_file($fcpath_cand)) {
                $ext = strtolower(pathinfo($fcpath_cand, PATHINFO_EXTENSION));
                $mime = ($ext == 'png') ? 'image/png' : (($ext == 'svg') ? 'image/svg+xml' : 'image/jpeg');
                $data = @file_get_contents($fcpath_cand);
                if ($data !== false && strlen($data) > 0) {
                    return 'data:' . $mime . ';base64,' . base64_encode($data);
                }
            }
        }
        return $fallback_url;
    }
}

// Pre-fetch all subject-to-class mappings to avoid DB queries in loops
$CI->db->select('cbse_exam_timetable_id, class_id');
$mappings = $CI->db->get('cbse_exam_timetable_classes')->result_array();
$subject_class_map = [];
foreach ($mappings as $map) {
    $subject_class_map[$map['cbse_exam_timetable_id']][] = $map['class_id'];
}

// 1. Resolve School Logo (Priority: schsettings/logo -> app_logo -> template logo -> fallback logo)
$logo_candidates = [];
if (!empty($sch_setting->image)) {
    $logo_candidates[] = FCPATH . 'uploads/school_content/logo/' . $sch_setting->image;
    $logo_candidates[] = 'uploads/school_content/logo/' . $sch_setting->image;
}
if (!empty($sch_setting->app_logo)) {
    $logo_candidates[] = FCPATH . 'uploads/school_content/logo/app_logo/' . $sch_setting->app_logo;
    $logo_candidates[] = FCPATH . 'uploads/school_content/logo/' . $sch_setting->app_logo;
    $logo_candidates[] = 'uploads/school_content/logo/app_logo/' . $sch_setting->app_logo;
    $logo_candidates[] = 'uploads/school_content/logo/' . $sch_setting->app_logo;
}
if (!empty($admitcard->left_logo)) {
    $logo_candidates[] = FCPATH . 'uploads/cbseexam/admitcard/' . $admitcard->left_logo;
    $logo_candidates[] = 'uploads/cbseexam/admitcard/' . $admitcard->left_logo;
}
$logo_candidates[] = FCPATH . 'backend/images/sunrise_logo.jpg';
$logo_candidates[] = 'backend/images/sunrise_logo.jpg';
$logo_candidates[] = FCPATH . 'backend/images/s_logo.png';

$school_logo_src = resolve_admit_image_src(
    $logo_candidates,
    !empty($sch_setting->image) ? base_url('uploads/school_content/logo/' . $sch_setting->image) : base_url('backend/images/sunrise_logo.jpg')
);

// 2. Resolve Principal Signature (Priority: schsettings/signature -> customlib mapping -> template sign -> fallback sign)
$principal_sig_mapping = $CI->customlib->getSignatureMapping('sign_principal');

$sig_candidates = [];
if (!empty($sch_setting->sign_principal)) {
    $sig_candidates[] = FCPATH . 'uploads/school_content/signatures/' . $sch_setting->sign_principal;
    $sig_candidates[] = 'uploads/school_content/signatures/' . $sch_setting->sign_principal;
}
if (!empty($principal_sig_mapping['image_file'])) {
    $sig_candidates[] = FCPATH . 'uploads/school_content/signatures/' . $principal_sig_mapping['image_file'];
    $sig_candidates[] = 'uploads/school_content/signatures/' . $principal_sig_mapping['image_file'];
}
if (!empty($admitcard->sign)) {
    $sig_candidates[] = FCPATH . 'uploads/cbseexam/admitcard/' . $admitcard->sign;
    $sig_candidates[] = 'uploads/cbseexam/admitcard/' . $admitcard->sign;
}
$sig_candidates[] = FCPATH . 'backend/images/principal_sign_green.jpg';
$sig_candidates[] = 'backend/images/principal_sign_green.jpg';
$sig_candidates[] = FCPATH . 'backend/images/marksheet/sign-principal.png';
$sig_candidates[] = FCPATH . 'uploads/transfer_certificate/signature_of_principle.jpg';

$principal_sign_src = resolve_admit_image_src(
    $sig_candidates,
    !empty($principal_sig_mapping['image_url']) ? $principal_sig_mapping['image_url'] : base_url('backend/images/principal_sign_green.jpg')
);

// Default placeholder for missing photo
$photo_placeholder_src = resolve_admit_image_src(
    [FCPATH . 'backend/images/admit_img_1_Im7.png', 'backend/images/admit_img_1_Im7.png'],
    base_url('backend/images/admit_img_1_Im7.png')
);

$school_name_display = 'Sunrise International Public School';
if (!empty($admitcard->school_name) && $admitcard->school_name != 'Mount Carmel School') {
    $school_name_display = $admitcard->school_name;
} elseif (!empty($admitcard->heading)) {
    $school_name_display = $admitcard->heading;
} elseif (!empty($sch_setting->name) && strpos($sch_setting->name, 'Learn2care') === false) {
    $school_name_display = $sch_setting->name;
}

$school_address_display = 'Sikar Salasar road, Nechhwa, Sikar, 332026 Ph 9828512821';
if (!empty($sch_setting->address) && strpos($sch_setting->address, 'Salasar') !== false) {
    $school_address_display = 'Sikar Salasar road, Nechhwa, Sikar, 332026 Ph ' . (!empty($sch_setting->phone) ? $sch_setting->phone : '9828512821');
}

$print_session = !empty($session_name) ? $session_name : '2024-25';

// Determine max timetable rows across students to decide whether 3 or 2 admit cards fit per page
$max_sub_count = 0;
foreach ($student_details as $st) {
    $c = 0;
    if (!empty($exam_subjects)) {
        foreach ($exam_subjects as $subject) {
            if (isset($subject_class_map[$subject->timetable_id]) && !empty($subject_class_map[$subject->timetable_id])) {
                if (!in_array($st->class_id, $subject_class_map[$subject->timetable_id])) {
                    continue;
                }
            }
            $c++;
        }
    }
    if ($c > $max_sub_count) {
        $max_sub_count = $c;
    }
}
$max_rows = ceil($max_sub_count / 2);
// If timetable has 4 or fewer rows (i.e. up to 8 subjects in 2 columns), 3 cards fit on 1 page; otherwise 2 cards
$cards_per_page = ($max_rows <= 4) ? 3 : 2;

$print_count = 0;
$total_students = count($student_details);

foreach ($student_details as $student) { 
    $print_count++;

    // Filter subjects for student's class
    $student_subjects = [];
    if (!empty($exam_subjects)) {
        foreach ($exam_subjects as $subject) {
            if (isset($subject_class_map[$subject->timetable_id]) && !empty($subject_class_map[$subject->timetable_id])) {
                if (!in_array($student->class_id, $subject_class_map[$subject->timetable_id])) {
                    continue;
                }
            }
            $student_subjects[] = $subject;
        }
    }

    // Sort subjects in chronological order by date and time
    if (!empty($student_subjects)) {
        usort($student_subjects, function($a, $b) {
            $t1 = strtotime($a->date . ' ' . (!empty($a->time_from) ? $a->time_from : '00:00:00'));
            $t2 = strtotime($b->date . ' ' . (!empty($b->time_from) ? $b->time_from : '00:00:00'));
            if ($t1 == $t2) {
                return strcmp($a->name, $b->name);
            }
            return ($t1 < $t2) ? -1 : 1;
        });
    }

    $half = ceil(count($student_subjects) / 2);
    if ($half == 0) { $half = 1; }

    $roll_display = !empty($student->roll_no) ? $student->roll_no : (!empty($student->admit_roll_no) ? $student->admit_roll_no : (!empty($student->profile_roll_no) ? $student->profile_roll_no : ''));

    $class_display = $student->class;
    if (preg_match('/^(I|II|III|IV|V|VI|VII|VIII|IX|X|XI|XII|\d+)$/i', trim($class_display))) {
        $class_display .= 'th';
    }

    $faculty_display = !empty($student->faculty) ? $student->faculty : 'Senior';

    // Student Photo resolution
    $student_photo_src = '';
    if (!empty($student->image)) {
        $student_photo_src = resolve_admit_image_src(
            [FCPATH . $student->image, $student->image],
            base_url($student->image)
        );
    } elseif (!empty($student->admission_no) && $student->admission_no == '2263') {
        $student_photo_src = resolve_admit_image_src(
            [FCPATH . 'backend/images/admit_img_3_Im11.jpg'],
            base_url('backend/images/admit_img_3_Im11.jpg')
        );
    }
    if (empty($student_photo_src)) {
        $student_photo_src = $photo_placeholder_src;
    }
?>
    <div class="admit-card-wrapper">
        <div class="admit-card-box">
            <!-- Header Table -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 2px;">
                <tr>
                    <td width="70" valign="top" align="left">
                        <img src="<?php echo $school_logo_src; ?>" style="height: 46px; max-width: 70px;">
                    </td>
                    <td valign="top" align="center">
                        <div style="color: #a81c1c; font-size: 15px; font-weight: bold; font-family: Arial, sans-serif; letter-spacing: 0.2px;">
                            <?php echo $school_name_display; ?>
                        </div>
                        <div style="font-size: 9.5px; font-weight: bold; color: #000; margin-top: 2px;">
                            <?php echo $school_address_display; ?>
                        </div>
                        <div style="color: #a81c1c; font-size: 12px; font-weight: bold; text-decoration: underline; margin-top: 2px;">
                            Permission Letter cum Admission Card
                        </div>
                    </td>
                    <td width="70" valign="top"></td>
                </tr>
            </table>

            <!-- Session Row -->
            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 1px; margin-bottom: 2px;">
                <tr>
                    <td style="font-size: 9.5px; font-weight: bold; color: #000;">
                        Session :- <?php echo $print_session; ?>
                    </td>
                </tr>
            </table>

            <div style="border-top: 1px solid #888; margin: 0 0 4px 0;"></div>

            <!-- Exam Badge -->
            <div style="text-align: center; margin-bottom: 3px;">
                <span class="badge-pill"><?php echo !empty($exam->name) ? $exam->name : 'TERM 1'; ?></span>
            </div>

            <!-- Student Meta & Photo Grid -->
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <!-- Left Meta Column -->
                    <td width="42%" valign="top">
                        <table class="meta-table">
                            <tr>
                                <td class="meta-label" width="45%">Roll No.</td>
                                <td class="meta-val">: <?php echo $roll_display; ?></td>
                            </tr>
                            <tr>
                                <td class="meta-label">Student's Name</td>
                                <td class="meta-val" style="font-weight: bold;">: <?php echo strtoupper(trim($student->firstname . ' ' . $student->lastname)); ?></td>
                            </tr>
                            <tr>
                                <td class="meta-label">Father's Name(Mr.)</td>
                                <td class="meta-val">: <?php echo strtoupper($student->father_name); ?></td>
                            </tr>
                            <tr>
                                <td class="meta-label">Mother's Name(Mrs.)</td>
                                <td class="meta-val">: <?php echo strtoupper($student->mother_name); ?></td>
                            </tr>
                            <tr>
                                <td class="meta-label">DOB</td>
                                <td class="meta-val">: <?php echo !empty($student->dob) ? date('d/m/Y', strtotime($student->dob)) : ''; ?></td>
                            </tr>
                        </table>
                    </td>

                    <!-- Right Meta Column -->
                    <td width="38%" valign="top">
                        <table class="meta-table">
                            <tr>
                                <td class="meta-label" width="35%">SR No.</td>
                                <td class="meta-val">: <?php echo $student->admission_no; ?></td>
                            </tr>
                            <tr>
                                <td class="meta-label">Class</td>
                                <td class="meta-val">: <?php echo $class_display; ?></td>
                            </tr>
                            <tr>
                                <td class="meta-label">Faculty</td>
                                <td class="meta-val">: <?php echo $faculty_display; ?></td>
                            </tr>
                            <tr>
                                <td class="meta-label">Section</td>
                                <td class="meta-val">: <?php echo $student->section; ?></td>
                            </tr>
                        </table>
                    </td>

                    <!-- Photo Column -->
                    <td width="20%" valign="top" align="right">
                        <div class="photo-box">
                            <img src="<?php echo $student_photo_src; ?>" style="width: 72px; height: 84px; object-fit: cover;">
                        </div>
                    </td>
                </tr>
            </table>

            <!-- Exam Schedule Badge -->
            <div style="text-align: center; margin: 3px 0 2px 0;">
                <span class="badge-pill" style="font-size: 9px; padding: 1px 18px;">Exam Schedule</span>
            </div>

            <!-- Exam Schedule Table (2 columns split) -->
            <table class="schedule-table">
                <thead>
                    <tr>
                        <th width="26%">Subjects</th>
                        <th width="24%">Exam Date & Time</th>
                        <th width="26%">Subjects</th>
                        <th width="24%">Exam Date & Time</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($student_subjects)) { 
                        for ($i = 0; $i < $half; $i++) { 
                            $left_sub = isset($student_subjects[$i]) ? $student_subjects[$i] : null;
                            $right_sub = isset($student_subjects[$i + $half]) ? $student_subjects[$i + $half] : null;
                    ?>
                        <tr>
                            <td><?php echo $left_sub ? strtoupper($left_sub->name) : ''; ?></td>
                            <td><?php 
                                if ($left_sub) {
                                    $left_dt = date('d/m/Y', strtotime($left_sub->date));
                                    if (!empty($left_sub->time_from)) {
                                        $left_dt .= ' ' . $left_sub->time_from;
                                    }
                                    echo $left_dt;
                                }
                            ?></td>
                            <td><?php echo $right_sub ? strtoupper($right_sub->name) : ''; ?></td>
                            <td><?php 
                                if ($right_sub) {
                                    $right_dt = date('d/m/Y', strtotime($right_sub->date));
                                    if (!empty($right_sub->time_from)) {
                                        $right_dt .= ' ' . $right_sub->time_from;
                                    }
                                    echo $right_dt;
                                }
                            ?></td>
                        </tr>
                    <?php 
                        } 
                    } else { ?>
                        <tr>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <!-- Note & Signatures -->
            <div style="font-size: 8.5px; margin-top: 3px;">
                <strong>Note :-</strong> <?php echo !empty($admitcard->content_footer) ? strip_tags($admitcard->content_footer) : ''; ?>
            </div>

            <table width="100%" cellpadding="0" cellspacing="0" style="margin-top: 3px;">
                <tr>
                    <td width="50%" valign="bottom" align="left">
                        <img src="<?php echo $principal_sign_src; ?>" style="height: 22px; max-width: 90px; display: block;">
                        <div style="font-size: 8px; font-style: italic; color: #000; margin-top: 1px;">Principal Signature</div>
                    </td>
                    <td width="50%" valign="bottom" align="right">
                        <div style="font-size: 8px; font-style: italic; color: #000;">Exam Controller (Signature)</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

<?php 
    if ($print_count % $cards_per_page == 0 && $print_count < $total_students) {
        echo '<div class="pagebreak"></div><pagebreak />';
    } elseif ($print_count < $total_students) {
?>
        <div class="cut-line-wrapper">
            <div class="cut-line-text">&#9986; - - - - - - - - - - - - - - - - - - - - - - - - - - - - - Cut From Here - - - - - - - - - - - - - - - - - - - - - - - - - - - - - &#9986;</div>
        </div>
<?php
    }
} 
?>

</body>
</html>