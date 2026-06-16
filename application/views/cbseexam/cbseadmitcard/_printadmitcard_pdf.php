<?php
$theme_color = '#2eab66';
$theme_settings = $this->customlib->getCurrentThemeSetting();
if (isset($theme_settings->theme_color) && !empty($theme_settings->theme_color)) {
    $theme_color = $theme_settings->theme_color;
} else {
    $legacy_theme = $this->customlib->getCurrentTheme();
    $theme_map = [
        'default' => '#424242', 'red' => '#f44336', 'blue' => '#1e3a8a',
        'gray' => '#607d8b', 'material' => '#3f51b5', 'darkgray' => '#343a40'
    ];
    if (isset($theme_map[$legacy_theme])) {
        $theme_color = $theme_map[$legacy_theme];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<style>
    :root {
        --primary-color: <?php echo $theme_color; ?>;
        --border-color: #000000;
        --text-main: #1f2937;
        --text-muted: #4b5563;
    }

    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
    }

    body {
        margin: 0;
        padding: 0;
        background: #fff;
        font-family: 'Helvetica', 'Arial', sans-serif;
        color: var(--text-main);
    }

    .print-receipt-wrapper {
        width: 100%;
        margin: 0 auto;
        padding-bottom: 20px;
    }

    /* Receipt Copy Container */
    .receipt-copy {
        width: 100%;
        border: 1px solid var(--border-color);
        margin-bottom: 20px; /* Space between copies */
        position: relative;
        overflow: hidden;
        page-break-inside: avoid;
    }

    /* Watermark */
    .watermark-bg {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 40%;
        opacity: 0.08;
        pointer-events: none;
        z-index: 0;
    }

    .receipt-inner {
        position: relative;
        z-index: 1;
        padding: 10px 15px;
    }

    /* Header Top Row */
    .header-top {
        display: flex;
        justify-content: space-between;
        font-size: 10px;
        color: var(--text-muted);
        margin-bottom: 5px;
    }

    /* Main Header */
    .receipt-header {
        display: table;
        width: 100%;
        margin-bottom: 10px;
    }
    
    .header-col {
        display: table-cell;
        vertical-align: middle;
    }
    
    .logo-container {
        width: 60px;
    }
    .logo-container img {
        width: 100%;
        max-height: 60px;
        height: auto;
    }
    
    .school-info {
        text-align: center;
        padding: 0 10px;
    }
    .school-name {
        font-size: 18px;
        font-weight: bold;
        color: var(--primary-color);
        margin: 0 0 3px 0;
    }
    .school-address {
        font-size: 11px;
        color: var(--text-muted);
        margin: 0 0 3px 0;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 3px;
        display: inline-block;
        width: 80%;
    }
    .session-info {
        font-weight: bold;
        font-size: 12px;
        margin-top: 3px;
        color: #000;
        text-transform: uppercase;
    }
    
    .copy-label {
        font-size: 12px;
        font-weight: bold;
        color: var(--text-main);
        width: 100px;
        text-align: right;
    }

    /* Meta Info Grid */
    .meta-table {
        width: 100%;
        border-top: 1px solid var(--border-color);
        padding-top: 8px;
        margin-bottom: 8px;
        font-size: 11px;
    }
    .meta-table td {
        padding: 2px 0;
        vertical-align: top;
    }
    .meta-label {
        width: 90px;
        font-weight: bold;
        color: var(--text-main);
    }
    .meta-value {
        font-weight: normal;
    }

    .student-photo-cell {
        width: 80px;
        text-align: right;
    }
    .student-photo {
        width: 60px;
        height: 60px;
        border: 1px solid var(--border-color);
    }

    /* Table */
    .receipt-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
    }
    .receipt-table th, .receipt-table td {
        border: 1px solid var(--border-color);
        padding: 5px 8px;
        font-size: 11px;
        text-align: left;
    }
    .receipt-table th {
        background-color: #f3f4f6;
        font-weight: bold;
        text-transform: uppercase;
        font-size: 10px;
        color: #000;
    }

    .instructions {
        font-size: 10px;
        color: var(--text-muted);
        margin-bottom: 20px;
    }

    .signatures {
        width: 100%;
        margin-top: 25px;
        font-size: 10px;
    }
    .signatures td {
        text-align: center;
        font-weight: bold;
        vertical-align: bottom;
    }
</style>
</head>
<body>

<?php foreach ($student_details as $student) { ?>
    <div class="print-receipt-wrapper">
        <div class="receipt-copy">
            <!-- Watermark -->
            <?php if (!empty($admitcard->background_img)) { ?>
                <img class="watermark-bg" src="<?php echo base_url("uploads/cbseexam/admitcard/" . $admitcard->background_img); ?>" alt="">
            <?php } ?>

            <div class="receipt-inner">
                <!-- Header Top Row -->
                <div class="header-top">
                    <span><strong>Roll No:</strong> <?php echo $student->roll_no; ?></span>
                    <span><strong>Center:</strong> <?php echo $admitcard->exam_center; ?></span>
                </div>

                <!-- Main Header -->
                <div class="receipt-header">
                    <div class="header-col logo-container">
                        <?php if (!empty($admitcard->left_logo)) { ?>
                            <img src="<?php echo base_url('uploads/cbseexam/admitcard/' . $admitcard->left_logo); ?>">
                        <?php } elseif (!empty($sch_setting->image)) { ?>
                            <img src="<?php echo base_url('uploads/school_content/logo/' . $sch_setting->image); ?>" alt="School Logo">
                        <?php } ?>
                    </div>
                    
                    <div class="header-col school-info">
                        <div class="school-name"><?php echo (!empty($admitcard->school_name)) ? $admitcard->school_name : $sch_setting->name; ?></div>
                        <div class="school-address"><?php echo $sch_setting->address; ?></div>
                        <div class="session-info"><?php echo $admitcard->title; ?> <?php echo (!empty($admitcard->exam_name)) ? '- ' . $admitcard->exam_name : '- ' . $exam->name; ?></div>
                    </div>
                    
                    <div class="header-col copy-label" style="width: 60px;">
                        <!-- Removed Candidate/Office Copy label -->
                    </div>
                </div>

                <!-- Student Meta Data -->
                <table class="meta-table">
                    <tr>
                        <td class="meta-label">Student Name</td>
                        <td class="meta-value">: <?php echo $student->firstname . ' ' . $student->lastname; ?></td>
                        
                        <td class="meta-label" style="padding-left:15px;">Father's Name</td>
                        <td class="meta-value">: <?php echo $student->father_name; ?></td>
                        
                        <td rowspan="4" class="student-photo-cell">
                            <?php if (!empty($student->image)) { ?>
                                <img src="<?php echo base_url($student->image); ?>" class="student-photo">
                            <?php } else { ?>
                                <div class="student-photo" style="display:inline-block; line-height:60px; text-align:center; color:#ccc;">Photo</div>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="meta-label">Class</td>
                        <td class="meta-value">: <?php echo $student->class . ' (' . $student->section . ')'; ?></td>
                        
                        <td class="meta-label" style="padding-left:15px;">Mother's Name</td>
                        <td class="meta-value">: <?php echo $student->mother_name; ?></td>
                    </tr>
                    <tr>
                        <td class="meta-label">DOB</td>
                        <td class="meta-value">: <?php echo date($this->customlib->getSchoolDateFormat(), strtotime($student->dob)); ?></td>
                        
                        <td class="meta-label" style="padding-left:15px;">Adm No</td>
                        <td class="meta-value">: <?php echo $student->admission_no; ?></td>
                    </tr>
                </table>

                <!-- Timetable -->
                <?php if ($show_timetable && !empty($exam_subjects)) { ?>
                    <table class="receipt-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Subject</th>
                                <th>Room No</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($exam_subjects as $subject) { ?>
                                <tr>
                                    <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($subject->date)); ?></td>
                                    <td><?php echo $subject->time_from . ' - ' . $subject->time_to; ?></td>
                                    <td><?php echo $subject->name . ' (' . $subject->code . ')'; ?></td>
                                    <td><?php echo $subject->room_no; ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>

                <!-- Instructions -->
                <div class="instructions">
                    <strong>Note:</strong> <?php echo strip_tags($admitcard->content_footer); ?>
                </div>

                <!-- Signatures -->
                <table class="signatures">
                    <tr>
                        <td style="text-align:left; width:33%;">
                            <?php if (!empty($admitcard->left_sign)) { ?>
                                <img src="<?php echo base_url('uploads/cbseexam/admitcard/' . $admitcard->left_sign); ?>" style="max-height: 25px;"><br>
                            <?php } ?>
                            Candidate's Sign
                        </td>
                        <td style="text-align:center; width:33%;">
                            <?php if (!empty($admitcard->middle_sign)) { ?>
                                <img src="<?php echo base_url('uploads/cbseexam/admitcard/' . $admitcard->middle_sign); ?>" style="max-height: 25px;"><br>
                            <?php } ?>
                            Class Teacher
                        </td>
                        <td style="text-align:right; width:33%;">
                            <?php if (!empty($admitcard->right_sign)) { ?>
                                <img src="<?php echo base_url('uploads/cbseexam/admitcard/' . $admitcard->right_sign); ?>" style="max-height: 25px;"><br>
                            <?php } ?>
                            Principal's Sign
                        </td>
                    </tr>
                </table>

            </div>
        </div>

    </div>
<?php } // end student loop ?>

</body>
</html>
