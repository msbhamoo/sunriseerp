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
if (!function_exists('numberToWord')) {
    function numberToWord($num) {
        $ones = array(
            1 => "one", 2 => "two", 3 => "three", 4 => "four", 5 => "five", 6 => "six",
            7 => "seven", 8 => "eight", 9 => "nine", 10 => "ten", 11 => "eleven",
            12 => "twelve", 13 => "thirteen", 14 => "fourteen", 15 => "fifteen",
            16 => "sixteen", 17 => "seventeen", 18 => "eighteen", 19 => "nineteen",
            20 => "twenty", 30 => "thirty", 40 => "forty", 50 => "fifty",
            60 => "sixty", 70 => "seventy", 80 => "eighty", 90 => "ninety"
        );
        if($num == 0) return "";
        if($num < 20) return $ones[(int)$num];
        if($num < 100) {
            $tens = floor($num / 10) * 10;
            $units = $num % 10;
            return $ones[$tens] . ($units ? " " . $ones[$units] : "");
        }
        return "";
    }
}

if (!function_exists('dateToWords')) {
    function dateToWords($date) {
        $time = strtotime($date);
        $d = date('d', $time);
        $m = strtolower(date('F', $time));
        $y = date('Y', $time);

        $dWord = numberToWord($d);
        
        $yWord = "";
        if ($y >= 2000) {
            $yWord = "two thousand";
            $rem = $y - 2000;
            if ($rem > 0) {
                $yWord .= " " . numberToWord($rem);
            }
        } else {
            $yWord = "nineteen";
            $rem = $y - 1900;
            if ($rem > 0) {
                $yWord .= " " . numberToWord($rem); 
            }
        }
        return $dWord . " " . $m . " " . $yWord;
    }
}

$dob = date('d-M-Y', strtotime($student_data['dob']));
$dob_words = dateToWords($student_data['dob']);

$img_url = empty($student_data['image']) ? base_url('uploads/student_images/no_image.png') : base_url($student_data['image']);
$print_logo = empty($sch_setting_detail->admin_logo) ? base_url('uploads/school_content/admin_logo/default.png') : base_url('uploads/school_content/admin_logo/'.$sch_setting_detail->admin_logo);
?>
<!DOCTYPE html>
<html>
<head>
    <title><?php echo $cert['certificate_name']; ?></title>
    <style>
        * {
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        html, body {
            width: 100%;
            margin: 0;
            padding: 0;
            background: white;
        }
        
        body {
            font-family: 'Times New Roman', serif;
            color: #222;
            margin: 0;
            padding: 5px;
        }
        
        .cert-border {
            border: 5px solid <?php echo $theme_color; ?>;
            padding: 2px;
            box-sizing: border-box;
            margin: 0;
            width: 100%;
            page-break-after: avoid;
            max-width: 100%;
            overflow: hidden;
        }
        
        .cert-inner {
            border: 2px solid <?php echo $theme_color; ?>;
            padding: 15px 12px 90px 12px;
            box-sizing: border-box;
            position: relative;
            background-color: #fcfcfc;
            min-height: auto;
            width: 100%;
            overflow-x: hidden;
        }
        
        .cert-title {
            color: <?php echo $theme_color; ?>;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
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
            right: 20px;
            top: 20px;
        }
        
        .content-text {
            font-size: 16px;
            line-height: 1.8;
            text-align: justify;
            margin-top: 15px;
            margin-bottom: 15px;
        }
        
        .bold {
            font-weight: bold;
            color: #000;
        }
        
        .footer-table {
            position: absolute;
            bottom: 20px;
            left: 40px;
            width: 85%;
            margin-top: 0;
            padding-top: 15px;
        }
        
        .signature-line {
            border-top: 1px solid #222;
            width: 85%;
            margin: 0 auto;
            padding-top: 15px;
            font-size: 16px;
            font-weight: bold;
        }
        
        .info-table td {
            padding: 0 0;
            font-size: 14px;
        }
        
        .tc-list {
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            font-size: 13px;
            line-height: 1.4;
        }
        
        .tc-list td {
            word-wrap: break-word;
            overflow-wrap: break-word;
            padding: 1px 2px;
        }
        
        .tc-num {
            width: 3%;
            vertical-align: top;
        }
        
        .tc-label {
            width: 40%;
            vertical-align: top;
            font-weight: 600;
        }
        
        .tc-val {
            width: 57%;
            vertical-align: top;
        }
        
        .cert-meta-tc {
            table-layout: auto;
            width: 100%;
        }
        
        .cert-meta-tc td {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        
        @media print {
            html, body {
                margin: 0;
                padding: 5px;
                width: 100%;
            }
            
            .cert-border {
                margin: 0;
                padding: 2px;
                width: 100%;
                border: 5px solid <?php echo $theme_color; ?> !important;
                page-break-after: avoid;
                max-width: 100%;
                overflow: hidden;
            }
            
            .cert-inner {
                margin: 0;
                padding: 15px 12px 90px 12px;
                background-color: #fcfcfc !important;
                border: 2px solid <?php echo $theme_color; ?> !important;
                width: 100%;
                overflow-x: hidden;
            }
        }
    </style>
</head>
<body>

<div class="cert-border">
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
            } elseif (strpos($cert_name_lower, 'transfer') !== false || strpos($cert_name_lower, 'tc') !== false) {
                $dt_adm = date('d/m/Y', strtotime($student_data['admission_date']));
                $dt_dob = date('d/m/Y', strtotime($student_data['dob']));
                $dt_issue = date('d/m/Y', strtotime($cert['issue_date']));
                $academic_hist = $academic_history[0] ?? [];
            ?>
            <style>
.footer-table{
    width:100%;
    margin-top:25px;
    padding-top:15px;
    border-collapse:collapse;
    page-break-inside: avoid;
}

.footer-table td{
    vertical-align:bottom;
    page-break-inside: avoid;
}

.signature-box{
    text-align:center;
}

.signature-space{
    height:75px;
}

.signature-line{
    border-top:1px solid #333;
    width:80%;
    margin:0 auto;
}

.signature-title{
    margin-top:5px;
    font-size:11px;
    font-weight:bold;
    text-transform:uppercase;
}

.issue-date{
    font-size:11px;
    font-weight:bold;
}
            </style>
            
            <table class="cert-meta-tc" width="100%" style="margin-bottom: 15px; table-layout: auto;">
                <tr>
                    <td>Admission No. <strong><?php echo $student_data['admission_no']; ?></strong></td>
                    <td>TC No. <strong><?php echo $cert['certificate_number']; ?></strong></td>
                    <td>Apaar ID <strong><?php echo !empty($custom_data['apaar_id']) ? strtoupper($custom_data['apaar_id']) : '..................'; ?></strong></td>
                    <td>PEN No. <strong><?php echo !empty($custom_data['pen_no']) ? strtoupper($custom_data['pen_no']) : '..................'; ?></strong></td>
                </tr>
            </table>

            <table class="tc-list" style="table-layout: fixed; width: 100%;">
                <tr><td class="tc-num">1.</td><td class="tc-label">Name Of Pupil :</td><td class="tc-val"><?php echo !empty($name) ? $name : '........................'; ?></td></tr>
                <tr><td class="tc-num">2.</td><td class="tc-label">Mother's Name :</td><td class="tc-val"><?php echo !empty($student_data['mother_name']) ? strtoupper($student_data['mother_name']) : '........................'; ?></td></tr>
                <tr><td class="tc-num">3.</td><td class="tc-label">Father's Name :</td><td class="tc-val"><?php echo !empty($student_data['father_name']) ? strtoupper($student_data['father_name']) : '........................'; ?></td></tr>
                <tr><td class="tc-num">4.</td><td class="tc-label">Nationality :</td><td class="tc-val">INDIAN</td></tr>
                <tr><td class="tc-num">5.</td><td class="tc-label">Whether the candidate belongs to Schedule Caste or Schedule Tribe :</td><td class="tc-val"><?php echo !empty($student_data['category']) ? strtoupper($student_data['category']) : '........................'; ?></td></tr>
                <tr><td class="tc-num">6.</td><td class="tc-label">Date of first admission in the school :</td><td class="tc-val"><?php echo !empty($dt_adm) ? $dt_adm : '........................'; ?></td></tr>
                <tr><td class="tc-num">7.</td><td class="tc-label">Date of Birth as per Admission Register (in Figures) :</td><td class="tc-val"><?php echo $dt_dob; ?></td></tr>
                <tr><td class="tc-num">8.</td><td class="tc-label">Date of Birth as per Admission Register (In Words) :</td><td class="tc-val"><?php echo ucwords(dateToWords($student_data['dob'])); ?></td></tr>
                <tr><td class="tc-num">9.</td><td class="tc-label">Class In Which Pupil Last Studied :</td><td class="tc-val"><?php echo !empty($student_data['class']) ? strtoupper($student_data['class'] . ' - ' . $student_data['section']) : '........................'; ?></td></tr>
                <tr><td class="tc-num">10.</td><td class="tc-label">School/Board Annual Examination Last taken with result :</td><td class="tc-val"><?php echo !empty($academic_hist['result']) ? strtoupper($academic_hist['result']) : '........................'; ?></td></tr>
                <tr><td class="tc-num">11.</td><td class="tc-label">Promoted to Class :</td><td class="tc-val"><?php echo !empty($custom_data['promoted_to']) ? strtoupper($custom_data['promoted_to']) : '........................'; ?></td></tr>
                <tr><td class="tc-num">12.</td><td class="tc-label">Whether failed. If so once/twice in the same class :</td><td class="tc-val"><?php echo !empty($custom_data['pass_fail']) ? strtoupper($custom_data['pass_fail']) : '........................'; ?></td></tr>
                <tr><td class="tc-num">13.</td><td class="tc-label">Any fee concession availed of, if so, the nature of such concession :</td><td class="tc-val"><?php echo !empty($custom_data['fee_concession']) ? strtoupper($custom_data['fee_concession']) : '........................'; ?></td></tr>
                <tr><td class="tc-num">14.</td><td class="tc-label">Subjects studied :</td><td class="tc-val"><?php echo !empty($custom_data['subjects_studied']) ? strtoupper($custom_data['subjects_studied']) : '........................'; ?></td></tr>
                <tr><td class="tc-num">15.</td><td class="tc-label">Total number of working days :</td><td class="tc-val"><?php echo !empty($academic_hist['working_days']) ? $academic_hist['working_days'] : '........................'; ?></td></tr>
                <tr><td class="tc-num">16.</td><td class="tc-label">Total number of working days present :</td><td class="tc-val"><?php echo !empty($academic_hist['present_days']) ? $academic_hist['present_days'] : '........................'; ?></td></tr>
                <tr><td class="tc-num">17.</td><td class="tc-label">Whether NCC cadet/ Boy Scout/ Girl Guide (Detail is may be given) :</td><td class="tc-val"><?php echo !empty($custom_data['ncc_cadet']) ? strtoupper($custom_data['ncc_cadet']) : '........................'; ?></td></tr>
                <tr><td class="tc-num">18.</td><td class="tc-label">General Conduct :</td><td class="tc-val"><?php echo !empty($academic_hist['conduct']) ? strtoupper($academic_hist['conduct']) : 'GOOD'; ?></td></tr>
                <tr><td class="tc-num">19.</td><td class="tc-label">Games played/ Extracurricular activities in which the pupil usually took part :</td><td class="tc-val"><?php echo !empty($custom_data['games_played']) ? strtoupper($custom_data['games_played']) : '........................'; ?></td></tr>
                <tr><td class="tc-num">20.</td><td class="tc-label">Date Of School Leaving :</td><td class="tc-val"><?php echo !empty($dt_issue) ? $dt_issue : '........................'; ?></td></tr>
                <tr><td class="tc-num">21.</td><td class="tc-label">Date of issue of certificate :</td><td class="tc-val"><?php echo !empty($dt_issue) ? $dt_issue : '........................'; ?></td></tr>
                <tr><td class="tc-num">22.</td><td class="tc-label">Reason for Leaving the school :</td><td class="tc-val"><?php echo !empty($cert['remark']) ? strtoupper($cert['remark']) : '........................'; ?></td></tr>
                <tr><td class="tc-num">23.</td><td class="tc-label">Any Other remarks :</td><td class="tc-val">................................................</td></tr>
            </table>
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
        <td width="30%" align="left">
            <div class="issue-date">
                <br><br><br>
                Date: <?php echo date('d-M-Y', strtotime($cert['issue_date'])); ?>
            </div>
        </td>

        <td width="35%">
            <div class="signature-box">
                <div class="signature-space"><br><br><br></div>
                <div class="signature-line"></div>
                <div class="signature-title">
                    Issued By
                </div>
            </div>
        </td>

        <td width="35%">
            <div class="signature-box">
                <div class="signature-space"><br><br><br></div>
                <div class="signature-line"></div>
                <div class="signature-title">
                    Verified By
                </div>
            </div>
        </td>

        <td width="35%">
            <div class="signature-box">
                <div class="signature-space"><br><br><br></div>
                <div class="signature-line"></div>
                <div class="signature-title">
                    Principal
                </div>
            </div>
        </td>
    </tr>
</table>

    </div>
</div>

</body>
</html>
