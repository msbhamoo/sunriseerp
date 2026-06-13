<?php
$config = json_decode($cert['fields_config'] ?? '{}', true) ?? [];
$show_religion = $config['show_religion'] ?? 1;
$show_category = $config['show_category'] ?? 1;
$show_handicapped = $config['show_handicapped'] ?? 1;
$show_father_contact = $config['show_father_contact'] ?? 1;
$show_reason = $config['show_reason'] ?? 1;

$theme_color = !empty($sch_setting_detail->theme_color) ? $sch_setting_detail->theme_color : '#0c563e';

$name = strtoupper(($student_data['firstname'] ?? '') . ' ' . ($student_data['lastname'] ?? ''));
$gender = strtolower($student_data['gender'] ?? '');
$mr = ($gender == 'female') ? 'Miss.' : 'Mr.';
$son_of = ($gender == 'female') ? 'Daughter of' : 'Son of';
$he = ($gender == 'female') ? 'She' : 'He';
$his = ($gender == 'female') ? 'Her' : 'His';

// Convert DOB to words (Basic implementation, can be extended)
$dob = date('d-M-Y', strtotime($student_data['dob']));
$dob_words = date('F jS, Y', strtotime($student_data['dob']));

$img_url = empty($student_data['image']) ? base_url('uploads/student_images/no_image.png') : base_url($student_data['image']);
$print_logo = empty($sch_setting_detail->admin_logo) ? base_url('uploads/school_content/admin_logo/default.png') : base_url('uploads/school_content/admin_logo/'.$sch_setting_detail->admin_logo);
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $cert['certificate_name']; ?></title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            color: #222;
        }
        .cert-border {
            border: 5px solid <?php echo $theme_color; ?>;
            padding: 2px;
            height: 100%;
            box-sizing: border-box;
            position: relative;
        }
        .cert-inner {
            border: 2px solid <?php echo $theme_color; ?>;
            height: 100%;
            padding: 20px;
            box-sizing: border-box;
            position: relative;
            background-color: #fcfcfc;
        }
        .cert-title {
            color: <?php echo $theme_color; ?>;
            font-size: 26px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 2px;
            white-space: nowrap;
        }
        .cert-title-container {
            border-bottom: 2px solid <?php echo $theme_color; ?>;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .cert-meta {
            font-size: 16px;
            color: #444;
        }
        .student-photo {
            width: 100px;
            height: 125px;
            border: 1px solid #aaa;
            padding: 4px;
            background: #fff;
            position: absolute;
            right: 0;
            top: 0;
        }
        .content-text {
            font-size: 18px;
            line-height: 2.2;
            text-align: justify;
            margin-top: 40px;
        }
        .bold {
            font-weight: bold;
            color: #000;
        }
        .footer-table {
            position: absolute;
            bottom: 40px;
            left: 40px;
            width: 88%;
        }
        .signature-line {
            border-top: 1px solid #222;
            width: 85%;
            margin: 0 auto;
            padding-top: 15px;
            font-size: 16px;
            font-weight: bold;
            color: #222;
            text-align: center;
        }
        .info-table td {
            padding: 1px 0;
        }
    </style>
</head>
<body>

<div class="cert-border">
    <!-- Watermark -->
    <watermarkimage src="<?php echo $print_logo; ?>" alpha="0.08" size="100,100" />
    
    <div class="cert-inner">

        <?php if (!empty($general_purpose_header)) { ?>
            <div style="text-align: center; margin-bottom: 30px;">
                <img src="<?php echo base_url('uploads/print_headerfooter/general_purpose/'.$general_purpose_header); ?>" style="max-width: 100%; height: auto;">
            </div>
        <?php } ?>

        <div style="position: relative; margin-bottom: 20px; text-align: center;">
            <?php if (!empty($student_data['image'])) { ?>
                <img src="<?php echo base_url($student_data['image']); ?>" class="student-photo">
            <?php } ?>
            
            <div style="padding: 0 120px;">
                <div class="cert-title-container">
                    <span class="cert-title"><?php echo $cert['certificate_name']; ?></span>
                </div>
                <div class="cert-meta">
                    <strong>Certificate No:</strong> <?php echo $cert['certificate_number']; ?> &nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;
                    <strong>Issue Date:</strong> <?php echo date('d-M-Y', strtotime($cert['issue_date'])); ?>
                </div>
            </div>
        </div>

        <?php
        $master_miss = ($gender == 'female') ? 'Miss' : 'Master';
        $son_daughter = ($gender == 'female') ? 'Daughter' : 'Son';
        $he_she = ($gender == 'female') ? 'She' : 'He';
        $him_her = ($gender == 'female') ? 'her' : 'him';
        $his_her = ($gender == 'female') ? 'her' : 'his';
        
        $session_obj = $this->db->select('session')->from('sessions')->where('id', $sch_setting_detail->session_id)->get()->row();
        $session_name = $session_obj ? $session_obj->session : '';
        ?>
        <div class="content-text">
            <?php
            $cert_name_lower = strtolower($cert['certificate_name']);
            if (strpos($cert_name_lower, 'character') !== false || strpos($cert_name_lower, 'cc') !== false) {
            ?>
            <p style="text-align: justify; margin-bottom: 20px;">
                <i>This is to certify that</i> <?php echo $master_miss; ?> <span class="bold"><?php echo $name; ?></span>,
                <?php echo $son_daughter; ?> of Mr. <span class="bold"><?php echo strtoupper($student_data['father_name'] ?? ''); ?></span> and Mrs. <span class="bold"><?php echo strtoupper($student_data['mother_name'] ?? ''); ?></span>,
                bearing Admission No. <span class="bold"><?php echo $student_data['admission_no']; ?></span>, was a student of <span class="bold"><?php echo $sch_setting_detail->name; ?></span>.
            </p>

            <p style="margin-bottom: 20px;">
                During <?php echo $his_her; ?> stay in the school from <span class="bold"><?php echo date('d-M-Y', strtotime($student_data['admission_date'])); ?></span> to <span class="bold"><?php echo date('d-M-Y', strtotime($cert['issue_date'])); ?></span>, <?php echo $his_her; ?> conduct and character were found to be: <span class="bold">Good</span>.
            </p>

            <p style="margin-bottom: 20px;">
                To the best of our knowledge, the student has maintained good discipline and moral behavior throughout the period of study.
            </p>

            <p style="margin-bottom: 20px;">
                We wish <?php echo $him_her; ?> success in future academic and professional pursuits.
            </p>
            <?php
            } elseif (strpos($cert_name_lower, 'scholar') !== false || strpos($cert_name_lower, 'sc') !== false) {
            ?>
            <p style="text-align: justify; margin-bottom: 20px;">
                <i>This is to certify that</i> <?php echo $master_miss; ?> <span class="bold"><?php echo $name; ?></span>,
                <?php echo $son_daughter; ?> of Mr. <span class="bold"><?php echo strtoupper($student_data['father_name'] ?? ''); ?></span> and Mrs. <span class="bold"><?php echo strtoupper($student_data['mother_name'] ?? ''); ?></span>,
                bearing Admission No. <span class="bold"><?php echo $student_data['admission_no']; ?></span>, is/was a bonafide student of <span class="bold"><?php echo $sch_setting_detail->name; ?></span>.
            </p>

            <p style="margin-bottom: 15px;">As per school records, the student studied in:</p>

            <table width="100%" class="info-table" style="margin-left: 40px; margin-bottom: 30px; line-height: 1.8; font-size: 18px;">
                <tr><td width="30%">Class</td><td>: <span class="bold"><?php echo $student_data['class']; ?></span></td></tr>
                <tr><td>Section</td><td>: <span class="bold"><?php echo $student_data['section']; ?></span></td></tr>
                <tr><td>Academic Session</td><td>: <span class="bold"><?php echo $session_name; ?></span></td></tr>
            </table>

            <p style="margin-bottom: 20px;">
                The student attended the institution during the above-mentioned academic session and <?php echo $his_her; ?> attendance and academic records are maintained by the school.
            </p>

            <p style="margin-bottom: 20px;">
                This certificate is being issued upon the request of the student/parent for whatever purpose it may serve.
            </p>
            <?php
            } else {
            ?>
            <p style="text-align: justify; margin-bottom: 20px;">
                <i>This is to certify that</i> <?php echo $master_miss; ?> <span class="bold"><?php echo $name; ?></span>,
                <?php echo $son_daughter; ?> of Mr. <span class="bold"><?php echo strtoupper($student_data['father_name'] ?? ''); ?></span> and Mrs. <span class="bold"><?php echo strtoupper($student_data['mother_name'] ?? ''); ?></span>,
                bearing Admission No. <span class="bold"><?php echo $student_data['admission_no']; ?></span>, was a bonafide student of this school.
            </p>

            <p style="margin-bottom: 15px;">As per the school records:</p>

            <table width="100%" class="info-table" style="margin-left: 40px; margin-bottom: 30px; line-height: 1.8; font-size: 18px;">
                <tr><td width="30%">Admission Number</td><td>: <span class="bold"><?php echo $student_data['admission_no']; ?></span></td></tr>
                <tr><td>Date of Birth</td><td>: <span class="bold"><?php echo $dob; ?></span> (<?php echo $dob_words; ?>)</td></tr>
                <tr><td>Date of Admission</td><td>: <span class="bold"><?php echo date('d-M-Y', strtotime($student_data['admission_date'])); ?></span></td></tr>
                <tr><td>Class in which admitted</td><td>: <span class="bold"><?php echo $student_data['class'] . ' ' . $student_data['section']; ?></span></td></tr>
                <tr><td>Last Class Studied</td><td>: <span class="bold"><?php echo $student_data['class'] . ' ' . $student_data['section']; ?></span></td></tr>
                <tr><td>Session</td><td>: <span class="bold"><?php echo $session_name; ?></span></td></tr>
                <tr><td>Result</td><td>: <span class="bold">Passed</span></td></tr>
                <tr><td>Conduct</td><td>: <span class="bold">Good</span></td></tr>
            </table>

            <p style="margin-bottom: 20px;">
                The student has been studying in this institution up to <span class="bold"><?php echo date('d-M-Y', strtotime($cert['issue_date'])); ?></span>.
            </p>

            <p style="margin-bottom: 20px;">
                <?php echo $he_she; ?> has requested withdrawal from the school due to:<br>
                <span class="bold"><?php echo strtoupper($cert['remark'] ?? 'N/A'); ?></span>
            </p>

            <p style="margin-bottom: 20px;">
                We wish <?php echo $him_her; ?> success in future endeavors.
            </p>
            <?php } ?>
        </div>

        <table class="footer-table">
            <tr>
                <td width="35%" valign="bottom" align="left">
                    <div style="font-size: 16px; font-weight: bold; color: #222; text-align: left; padding-bottom: 10px;">
                        Date: <?php echo date('d-M-Y', strtotime($cert['issue_date'])); ?><br>
                        Place: <?php echo $sch_setting_detail->address; ?>
                    </div>
                </td>
                <td width="30%" valign="bottom" align="center">
                    <div class="signature-line">Official Seal</div>
                </td>
                <td width="35%" valign="bottom" align="center">
                    <div class="signature-line">Head of the Institution</div>
                </td>
            </tr>
        </table>

    </div>
</div>

</body>
</html>
