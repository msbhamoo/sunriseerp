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
<title>Hostel Gate Pass</title>
<style>
    :root {
        --primary-color: <?php echo $theme_color; ?>;
        --border-color: #e5e7eb;
        --text-main: #1f2937;
        --text-muted: #6b7280;
    }

    * {
        box-sizing: border-box;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
    }

    body {
        margin: 0;
        padding: 0;
        background: #fff;
        font-family: Arial, sans-serif;
        color: var(--text-main);
    }
    .print-receipt-wrapper {
        width: 100%;
        max-width: 600px;
        margin: 15px auto;
    }

    .receipt-copy {
        width: 100%;
        border: 2px solid var(--primary-color);
        border-radius: 8px;
        margin-bottom: 2vh;
        position: relative;
        overflow: hidden;
        page-break-inside: avoid;
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
        border-bottom: 2px solid var(--primary-color);
        padding-bottom: 10px;
    }
    
    .logo-container img {
        max-width: 80px;
        max-height: 80px;
    }
    
    .school-info {
        flex-grow: 1;
        text-align: center;
        padding: 0 15px;
    }
    .school-name {
        font-size: 20px;
        font-weight: bold;
        margin: 0 0 5px 0;
    }
    .school-address {
        font-size: 12px;
        margin: 0 0 5px 0;
    }
    
    .cert-title {
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        margin: 10px 0 20px 0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .meta-table {
        width: 100%;
        margin-bottom: 0;
        font-size: 13px;
        line-height: 1.6;
    }
    .meta-table td {
        padding: 4px 0;
        vertical-align: top;
    }
    .meta-label {
        width: 120px;
        font-weight: bold;
    }

    .content-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }
    .details-column {
        flex: 1;
        padding-right: 15px;
    }
    .barcode-column {
        width: 160px;
        flex-shrink: 0;
        text-align: center;
    }
    
    .barcode-container {
        text-align: center;
        margin: 0;
    }
    
    .barcode-container img {
        max-width: 150px;
    }

    .signatures {
        width: 100%;
        margin-top: 50px;
        font-size: 12px;
    }
    .signatures td {
        text-align: center;
        font-weight: bold;
        vertical-align: bottom;
    }
    .sign-line {
        border-top: 1px solid var(--text-main);
        width: 80%;
        margin: 0 auto;
        padding-top: 5px;
    }

    @media print {
        @page {
            size: A4 portrait;
            margin: 5mm;
        }
        .print-receipt-wrapper {
            max-height: 98vh;
            overflow: hidden;
            page-break-after: always;
        }
    }
</style>
</head>
<body>

<div class="print-receipt-wrapper">
    <?php
    $copies = array('Office Copy', 'Student Copy');
    foreach ($copies as $copy_type) {
    ?>
    <div class="receipt-copy">
        <div class="receipt-inner">
            <div class="header-top">
                <div>Gate Pass No: <strong>GP-<?php echo str_pad($gatepass['id'], 5, '0', STR_PAD_LEFT); ?></strong></div>
                <div>Copy: <strong><?php echo $copy_type; ?></strong></div>
            </div>

            <div class="receipt-header">
                <div class="logo-container">
                    <img src="<?php echo base_url('uploads/school_content/logo/' . $settinglist[0]['image']); ?>" alt="Logo">
                </div>
                <div class="school-info">
                    <div class="school-name"><?php echo $settinglist[0]['name']; ?></div>
                    <div class="school-address"><?php echo $settinglist[0]['address']; ?></div>
                    <div><?php echo $settinglist[0]['phone']; ?></div>
                </div>
                <div class="logo-container">
                    <!-- Right padding space for balance -->
                </div>
            </div>

            <div class="cert-title">HOSTEL GATE PASS</div>

            <div class="content-row">
                <div class="details-column">
                    <table class="meta-table">
                        <tr>
                            <td class="meta-label">Pass Type</td>
                            <td>: <strong><?php echo isset($gatepass['pass_type']) ? strtoupper($gatepass['pass_type']) : 'DAY PASS'; ?></strong></td>
                        </tr>
                        <tr>
                            <td class="meta-label">Student Name</td>
                            <td>: <?php echo strtoupper($gatepass['firstname'] . ' ' . $gatepass['lastname']); ?> (<?php echo $gatepass['admission_no']; ?>)</td>
                        </tr>
                        <tr>
                            <td class="meta-label">Class - Sec</td>
                            <td>: <?php echo strtoupper($gatepass['class_name'] . ' - ' . $gatepass['section_name']); ?></td>
                        </tr>
                        <tr>
                            <td class="meta-label">Going To</td>
                            <td>: <?php echo $gatepass['going_to']; ?></td>
                        </tr>
                        <tr>
                            <td class="meta-label">Reason</td>
                            <td>: <?php echo strip_tags($gatepass['reason']); ?></td>
                        </tr>
                        <tr>
                            <td class="meta-label">Out Date/Time</td>
                            <td>: <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($gatepass['out_date'])) . ' ' . date("h:i A", strtotime($gatepass['out_time'])); ?></td>
                        </tr>
                        
                        <?php if (!empty($gatepass['expected_in_date']) || !empty($gatepass['expected_in_time'])) { ?>
                        <tr>
                            <td class="meta-label">Expected Return</td>
                            <td>: <?php 
                                $exp_str = '';
                                if (!empty($gatepass['expected_in_date'])) {
                                    $exp_str .= date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($gatepass['expected_in_date'])) . ' ';
                                }
                                if (!empty($gatepass['expected_in_time'])) {
                                    $exp_str .= date("h:i A", strtotime($gatepass['expected_in_time']));
                                }
                                echo $exp_str;
                            ?></td>
                        </tr>
                        <?php } ?>

                        <?php if (!empty($gatepass['actual_in_date']) || !empty($gatepass['actual_in_time'])) { ?>
                        <tr>
                            <td class="meta-label">Actual Return</td>
                            <td>: <?php 
                                $act_str = '';
                                if (!empty($gatepass['actual_in_date'])) {
                                    $act_str .= date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($gatepass['actual_in_date'])) . ' ';
                                }
                                if (!empty($gatepass['actual_in_time'])) {
                                    $act_str .= date("h:i A", strtotime($gatepass['actual_in_time']));
                                }
                                echo $act_str;
                            ?></td>
                        </tr>
                        <?php } ?>

                        <tr>
                            <td class="meta-label">Status</td>
                            <td>: <strong><?php echo strtoupper($gatepass['status']); ?></strong></td>
                        </tr>
                    </table>
                </div>

                <div class="barcode-column">
                    <div class="barcode-container">
                        <!-- Barcode Generation -->
                        <img src="<?php echo base_url($this->customlib->generatebarcode('GP-'.str_pad($gatepass['id'], 5, '0', STR_PAD_LEFT), $gatepass['id'], 'barcode')); ?>" alt="Barcode">
                        <br><br>
                        <!-- QR Code Generation -->
                        <img src="<?php echo base_url($this->customlib->generatebarcode('GP-'.str_pad($gatepass['id'], 5, '0', STR_PAD_LEFT), $gatepass['id'], 'qrcode')); ?>" alt="QR Code">
                    </div>
                </div>
            </div>

            <table class="signatures">
                <tr>
                    <td>
                        <div class="sign-line">Warden Sign</div>
                    </td>
                    <td>
                        <div class="sign-line">Student Sign</div>
                    </td>
                    <td>
                        <div class="sign-line">Security Sign</div>
                    </td>
                </tr>
            </table>

        </div>
    </div>
    <?php } ?>
</div>

</body>
</html>