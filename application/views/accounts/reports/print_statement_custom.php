<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Ledger Statement</title>
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

    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
    }

    @media print {
        .receipt-table th {
            background-color: <?php echo $theme_color; ?> !important;
            color: #fff !important;
        }
        .receipt-copy {
            border: 1px solid #000000 !important;
        }
        .receipt-table th, .receipt-table td {
            border: 1px solid #000000 !important;
        }
    }

    html, body {
        margin: 0;
        padding: 0;
        background: #fff;
        font-family: 'Helvetica', 'Arial', sans-serif;
        color: #1f2937;
    }
    .print-receipt-wrapper {
        width: 100%;
        max-width: 800px;
        margin: 0 auto;
    }

    .receipt-copy {
        width: 100%;
        border: 1px solid #e5e7eb;
        margin-bottom: 20px;
        position: relative;
    }

    .watermark-bg {
        position: absolute;
        top: 50%;
        left: 50%;
        width: 40%;
        opacity: 0.08;
        z-index: -1;
    }

    .receipt-inner {
        padding: 15px;
    }

    /* Layout Tables */
    .layout-table {
        width: 100%;
        border-collapse: collapse;
        border: none;
    }
    .layout-table td {
        border: none;
        vertical-align: top;
        padding: 0;
    }

    .header-top {
        font-size: 10px;
        color: #6b7280;
        margin-bottom: 10px;
    }
    .header-top td {
        width: 33.33%;
    }
    .header-top td.center { text-align: center; }
    .header-top td.right { text-align: right; }

    .receipt-header {
        margin-bottom: 15px;
    }
    .logo-container {
        width: 80px;
    }
    .logo-container img {
        width: 100%;
        height: auto;
    }
    .school-info {
        text-align: center;
        padding: 0 15px;
    }
    .school-name {
        font-size: 20px;
        font-weight: 700;
        color: <?php echo $theme_color; ?>;
        margin: 0 0 5px 0;
    }
    .school-address {
        font-size: 11px;
        color: #6b7280;
        margin: 0 auto 5px auto;
        border-bottom: 1px solid #e5e7eb;
        padding-bottom: 5px;
        width: 80%;
    }
    .school-contact {
        font-size: 10px;
        margin: 0;
        color: #6b7280;
    }

    .meta-grid {
        border-top: 1px solid #e5e7eb;
        padding-top: 10px;
        margin-bottom: 10px;
    }
    .meta-col {
        width: 50%;
    }
    
    .meta-table {
        width: 100%;
        border-collapse: collapse;
    }
    .meta-table td {
        font-size: 11px;
        padding: 2px 0;
    }
    .meta-label {
        width: 100px;
        color: #6b7280;
    }
    .meta-value {
        font-weight: 600;
    }
    .meta-table.right td {
        text-align: right;
    }
    .meta-table.right .meta-label {
        text-align: right;
        padding-right: 10px;
    }
    .meta-table.right .meta-value {
        width: 120px;
        text-align: left;
    }

    .fees-title {
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 5px;
        text-align: right;
    }

    .receipt-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
        font-size: 10px;
    }
    .receipt-table th {
        background-color: <?php echo $theme_color; ?>;
        color: #fff;
        text-align: left;
        padding: 6px 8px;
        font-weight: 600;
        border: 1px solid #e5e7eb;
    }
    .receipt-table td {
        padding: 6px 8px;
        border: 1px solid #e5e7eb;
        color: #1f2937;
    }
    .receipt-table th.text-right, .receipt-table td.text-right {
        text-align: right;
    }
    .text-center {
        text-align: center;
    }

    .footer-bottom {
        margin-top: 30px;
        font-size: 9px;
        color: #6b7280;
    }
    .notes {
        font-size: 8px;
        vertical-align: bottom;
    }
    .signature-box {
        text-align: center;
        width: 150px;
        vertical-align: bottom;
    }
    .sig-space {
        height: 40px;
    }
    .sig-label {
        border-top: 1px dashed #e5e7eb;
        padding-top: 5px;
        font-weight: 600;
        font-size: 10px;
        color: #1f2937;
    }

    @media print {
        @page {
            size: A4 portrait;
            margin: 10mm;
        }
        body {
            background: none;
        }
        .print-receipt-wrapper {
            max-width: 100%;
            margin: 0;
            padding: 0;
        }
    }
</style>
</head>
<body onload="window.print()">
<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>

<div class="print-receipt-wrapper">
    <div class="receipt-copy">
        <?php if (!empty($settinglist[0]['admin_small_logo'])) { ?>
        <!-- Optional watermark: disabled for mPDF compatibility, or implement via mPDF specific logic if needed -->
        <!-- <img src="<?php echo base_url('uploads/school_content/admin_small_logo/' . $settinglist[0]['admin_small_logo']); ?>" class="watermark-bg" alt="Watermark"> -->
        <?php } ?>
        
        <div class="receipt-inner">
            <table class="layout-table header-top">
                <tr>
                    <td>Reg No: <?php echo $settinglist[0]['dise_code']; ?></td>
                    <td class="center">Affiliation No.: <?php echo $settinglist[0]['dise_code']; ?></td>
                    <td class="right">U Dise Code: <?php echo $settinglist[0]['dise_code']; ?></td>
                </tr>
            </table>

            <table class="layout-table receipt-header">
                <tr>
                    <td class="logo-container">
                        <img src="<?php echo base_url('uploads/school_content/logo/' . $settinglist[0]['image']); ?>" alt="Logo">
                    </td>
                    <td class="school-info">
                        <h1 class="school-name"><?php echo $settinglist[0]['name']; ?></h1>
                        <div class="school-address"><?php echo $settinglist[0]['address']; ?></div>
                        <p class="school-contact">Email: <?php echo $settinglist[0]['email']; ?> Mobile No: <?php echo $settinglist[0]['phone']; ?></p>
                    </td>
                    <td style="width: 80px;"></td>
                </tr>
            </table>

            <table class="layout-table meta-grid">
                <tr>
                    <td class="meta-col">
                        <table class="meta-table">
                            <tr>
                                <td class="meta-label">Ledger Name</td>
                                <td class="meta-value">: <?php echo strtoupper($ledger_name); ?></td>
                            </tr>
                            <tr>
                                <td class="meta-label">Date From</td>
                                <td class="meta-value">: <?php echo $date_from; ?></td>
                            </tr>
                            <tr>
                                <td class="meta-label">Date To</td>
                                <td class="meta-value">: <?php echo $date_to; ?></td>
                            </tr>
                        </table>
                    </td>
                    <td class="meta-col">
                        <div class="fees-title">LEDGER STATEMENT</div>
                        <table class="meta-table right">
                            <tr>
                                <td class="meta-label">Printed On :</td>
                                <td class="meta-value"><?php echo date($this->customlib->getSchoolDateFormat()); ?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <table class="receipt-table">
                <thead>
                    <tr>
                        <th width="10%">DATE</th>
                        <th width="12%">VCH NO</th>
                        <th width="10%">TYPE</th>
                        <th width="38%">PARTICULARS</th>
                        <th width="10%" class="text-right">DEBIT</th>
                        <th width="10%" class="text-right">CREDIT</th>
                        <th width="10%" class="text-right">BALANCE</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($result)) { 
                        $balance = $result['opening_balance'];
                        $total_dr = ($balance > 0) ? $balance : 0;
                        $total_cr = ($balance < 0) ? abs($balance) : 0;
                    ?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><b>OPENING BALANCE</b></td>
                        <td class="text-right"><?php echo ($balance > 0) ? $currency_symbol . amountFormat(abs($balance)) : ''; ?></td>
                        <td class="text-right"><?php echo ($balance < 0) ? $currency_symbol . amountFormat(abs($balance)) : ''; ?></td>
                        <td class="text-right"><?php echo $currency_symbol . amountFormat(abs($balance)) . ' ' . ($balance >= 0 ? 'Dr' : 'Cr'); ?></td>
                    </tr>
                    
                    <?php if (!empty($result['transactions'])) {
                        foreach ($result['transactions'] as $row) {
                            $dr = $row['debit_amount'];
                            $cr = $row['credit_amount'];
                            $balance += ($dr - $cr);
                            $total_dr += $dr;
                            $total_cr += $cr;
                    ?>
                    <tr>
                        <td><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($row['voucher_date'])); ?></td>
                        <td><?php echo $row['voucher_no']; ?></td>
                        <td><?php echo ucfirst($row['voucher_type']); ?></td>
                        <td>
                            <b><?php echo strtoupper($row['opposite_ledger_name'] ? $row['opposite_ledger_name'] : 'SYSTEM'); ?></b>
                            <?php $final_narration = !empty($row['narration']) ? $row['narration'] : (isset($row['voucher_narration']) ? $row['voucher_narration'] : ''); ?>
                            <?php if (!empty($final_narration)) { ?>
                            <br><span style="font-size: 9px; color: #6b7280;"><?php echo $final_narration; ?></span>
                            <?php } ?>
                        </td>
                        <td class="text-right"><?php echo ($dr > 0) ? $currency_symbol . amountFormat($dr) : ''; ?></td>
                        <td class="text-right"><?php echo ($cr > 0) ? $currency_symbol . amountFormat($cr) : ''; ?></td>
                        <td class="text-right"><?php echo $currency_symbol . amountFormat(abs($balance)) . ' ' . ($balance >= 0 ? 'Dr' : 'Cr'); ?></td>
                    </tr>
                    <?php 
                        } 
                    } 
                    ?>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-right" style="background-color: transparent; color: #1f2937;">CLOSING BALANCE:</th>
                        <th class="text-right" style="background-color: transparent; color: #1f2937;"><?php echo isset($total_dr) ? $currency_symbol . amountFormat($total_dr) : ''; ?></th>
                        <th class="text-right" style="background-color: transparent; color: #1f2937;"><?php echo isset($total_cr) ? $currency_symbol . amountFormat($total_cr) : ''; ?></th>
                        <th class="text-right" style="background-color: transparent; color: #1f2937;"><?php echo isset($balance) ? $currency_symbol . amountFormat(abs($balance)) . ' ' . ($balance >= 0 ? 'Dr' : 'Cr') : ''; ?></th>
                    </tr>
                </tfoot>
            </table>

            <table class="layout-table footer-bottom">
                <tr>
                    <td class="notes">
                        Generated by System at <?php echo date('Y-m-d H:i:s'); ?>
                    </td>
                    <td class="right" style="width: 150px; text-align: right;">
                        <div class="signature-box" style="float: right;">
                            <div class="sig-space"></div>
                            <div class="sig-label">AUTHORISED SIGNATURE</div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>

</body>
</html>
