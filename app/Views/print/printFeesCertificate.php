<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
<style>
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
    :root {
        --primary-color: <?php echo $theme_color; ?>;
        --border-color: #e5e7eb;
        --text-main: #1f2937;
        --text-muted: #6b7280;
    }

    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
    }

    /* Print Base */
    html, body {
        margin: 0;
        padding: 0;
        background: #fff;
        font-family: 'Inter', sans-serif;
        color: var(--text-main);
    }
    .print-receipt-wrapper {
        width: 96%;
        max-width: 800px;
        margin: 15px auto;
    }

    .receipt-copy {
        width: 100%;
        border: 2px solid var(--primary-color);
        border-radius: 8px;
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
        page-break-inside: avoid;
    }

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
        padding: 20px;
    }

    .header-top {
        display: flex;
        justify-content: space-between;
        font-size: 11px;
        color: var(--text-muted);
        margin-bottom: 10px;
    }

    .receipt-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 15px;
    }
    .logo-container {
        width: 140px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .logo-container img {
        max-width: 100%;
        max-height: 80px;
        object-fit: contain;
    }
    .school-info {
        flex-grow: 1;
        text-align: center;
        padding: 0 15px;
    }
    .school-name {
        font-size: 20px;
        font-weight: 700;
        color: var(--text-main);
        margin: 0 0 5px 0;
    }
    .school-address {
        font-size: 12px;
        color: var(--text-muted);
        margin: 0 0 5px 0;
        border-bottom: 1px solid var(--text-main);
        padding-bottom: 8px;
        display: inline-block;
        width: 90%;
    }
    .school-contact {
        font-size: 11px;
        margin: 0;
        color: var(--text-main);
        margin-top: 5px;
    }
    .session-info {
        font-weight: 700;
        font-size: 12px;
        margin-top: 5px;
        color: var(--text-main);
    }
    .cert-label {
        font-size: 12px;
        font-weight: 700;
        color: var(--text-main);
        margin-top: 5px;
    }

    .cert-title {
        text-align: center;
        font-size: 20px;
        font-weight: 700;
        color: var(--text-main);
        margin: 20px 0 30px 0;
    }

    .cert-body {
        font-size: 15px;
        color: var(--text-main);
        padding: 0 20px;
    }
    
    .cert-line {
        display: flex;
        align-items: flex-end;
        margin-bottom: 25px;
    }

    .input-line {
        border-bottom: 1px dashed var(--text-main);
        color: var(--text-main);
        font-weight: 700;
        text-align: center;
        padding: 0 10px;
    }

    .footer-bottom {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-top: 60px;
        font-size: 11px;
        color: var(--text-muted);
    }
    .notes {
        max-width: 65%;
        font-size: 12px;
        line-height: 1.5;
    }
    .signature-box {
        text-align: center;
        width: 200px;
    }
    .sig-space {
        height: 40px;
    }
    .sig-label {
        border-top: 1px solid var(--text-main);
        padding-top: 5px;
        font-weight: 600;
        font-size: 12px;
        color: var(--text-main);
    }

    @media print {
        @page {
            size: A4 landscape;
            margin: 15mm;
        }
        body {
            margin: 0;
            padding: 0;
            background: none;
        }
        .receipt-copy {
            border: 2px solid var(--text-main) !important;
            margin: 0;
            width: auto;
            height: auto;
            min-height: 85vh;
            box-sizing: border-box;
        }
    }
</style>

<?php
if (!function_exists('convert_number_to_words')) {
    function convert_number_to_words($number) {
        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = array(
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'fourty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            1000000             => 'million',
            1000000000          => 'billion',
            1000000000000       => 'trillion',
            1000000000000000    => 'quadrillion',
            1000000000000000000 => 'quintillion'
        );

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            trigger_error('convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING);
            return false;
        }

        if ($number < 0) {
            return $negative . convert_number_to_words(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = (int) ($number / 100);
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . convert_number_to_words($remainder);
                }
                break;
            default:
                $baseUnit = pow(1000, floor(log($number, 1000)));
                $numBaseUnits = (int) ($number / $baseUnit);
                $remainder = $number % $baseUnit;
                $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
                if ($remainder) {
                    $string .= $remainder < 100 ? $conjunction : $separator;
                    $string .= convert_number_to_words($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = array();
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return ucwords($string);
    }
}
?>

<div class="receipt-copy">
    <!-- Background Watermark -->
    <img src="<?php echo base_url('uploads/school_content/admin_small_logo/' . $settinglist[0]['admin_small_logo']); ?>" class="watermark-bg" alt="Watermark">
    
    <div class="receipt-inner">
        <div class="header-top">
            <span>Reg No: <?php echo $settinglist[0]['dise_code']; ?></span>
            <span>Affiliation No.: <?php echo $settinglist[0]['dise_code']; ?></span>
            <span>U Dise Code: <?php echo $settinglist[0]['dise_code']; ?></span>
        </div>

        <div class="receipt-header">
            <div class="logo-container">
                <img src="<?php echo base_url('uploads/school_content/logo/' . $settinglist[0]['image']); ?>" alt="Logo">
            </div>
            <div class="school-info">
                <h1 class="school-name"><?php echo $settinglist[0]['name']; ?></h1>
                <div class="school-address"><?php echo $settinglist[0]['address']; ?></div>
                <p class="school-contact">Email: <?php echo $settinglist[0]['email']; ?> &nbsp; Mobile No: <?php echo $settinglist[0]['phone']; ?> &nbsp; Website: <?php echo $settinglist[0]['phone']; ?></p>
                <div class="session-info">Session: <?php echo !empty($student['session']) ? $student['session'] : ($current_session['session'] ?? ''); ?></div>
                <div class="cert-label">Fees Certificate</div>
            </div>
            <div class="logo-container" style="visibility: hidden;">
                <!-- Balancer for flex -->
                <img src="<?php echo base_url('uploads/school_content/logo/' . $settinglist[0]['image']); ?>" alt="Logo">
            </div>
        </div>

        <div class="cert-title">Certificate of Tuition Fees</div>

        <div class="cert-body">
            <div class="cert-line">
                <span style="white-space: nowrap; margin-right: 15px;">This is to certify that</span>
                <span class="input-line" style="flex-grow: 1;"><?php echo strtoupper(($student['firstname'] ?? '') . ' ' . ($student['lastname'] ?? '')); ?></span>
                <span style="white-space: nowrap; margin-left: 15px;">is a student of our institution.</span>
            </div>
            
            <div class="cert-line">
                <span style="white-space: nowrap; margin-right: 15px;">Son/Daughter of</span>
                <span class="input-line" style="flex-grow: 1;"><?php echo strtoupper($student['father_name'] ?? ''); ?></span>
                <span style="white-space: nowrap; margin: 0 15px;">SR No.</span>
                <span class="input-line" style="width: 250px;"><?php echo $student['admission_no'] ?? ''; ?></span>
            </div>

            <div class="cert-line">
                <span style="white-space: nowrap; margin-right: 15px;">of Class</span>
                <span class="input-line" style="width: 350px;"><?php echo strtoupper(($student['class'] ?? '') . ' - ' . ($student['section'] ?? '')); ?></span>
                <span style="white-space: nowrap; margin: 0 15px;">Total Fees (Rs)</span>
                <span class="input-line" style="flex-grow: 1;"><?php echo $total_paid; ?></span>
            </div>

            <div class="cert-line">
                <span style="white-space: nowrap; margin-right: 15px;">(in words:</span>
                <span class="input-line" style="flex-grow: 1; font-style: italic; font-weight: 500;"><?php echo convert_number_to_words($total_paid); ?> Only</span>
                <span style="white-space: nowrap; margin-left: 15px;">)</span>
            </div>

            <div class="cert-line">
                <span style="white-space: nowrap; margin-right: 15px;">towards Academic Fees for academic session</span>
                <span class="input-line" style="flex-grow: 1;"><?php echo !empty($student['session']) ? $student['session'] : ($current_session['session'] ?? ''); ?></span>
                <span style="white-space: nowrap; margin-left: 15px;">.</span>
            </div>
        </div>

        <div class="footer-bottom">
            <div class="notes">
                This certificate is issued on the request of the above-named student to enable him/her to claim Income Tax Deduction under <strong>Section 80C</strong> of Income Tax Act.
            </div>
            <div class="signature-box">
                <div class="sig-space"></div>
                <div class="sig-label">Authorised Signatory</div>
            </div>
        </div>
    </div>
</div>
