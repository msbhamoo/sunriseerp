<?php
$theme_color = '#3b82f6'; // Default color for Gate Pass
$scan_type = $sch_setting->scan_code_type; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Gate Pass</title>
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
            max-height: 48vh;
            overflow: hidden;
            page-break-after: always;
        }
    }
</style>
</head>
<body>

<div class="print-receipt-wrapper">
    <div class="receipt-copy">
        <div class="receipt-inner">
            <div class="header-top">
                <div>Gate Pass No: <strong><?php echo $gatepass['gate_pass_no']; ?></strong></div>
                <div>Date: <strong><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($gatepass['date'])); ?></strong></div>
            </div>

            <div class="receipt-header">
                <div class="logo-container">
                    <img src="<?php echo $this->customlib->getBaseUrl(); ?>uploads/school_content/admin_logo/<?php echo $sch_setting->admin_logo; ?>" alt="Logo">
                </div>
                <div class="school-info">
                    <div class="school-name"><?php echo $sch_setting->name; ?></div>
                    <div class="school-address"><?php echo $sch_setting->address; ?></div>
                    <div><?php echo $sch_setting->phone; ?></div>
                </div>
                <div class="logo-container">
                    <!-- Right padding space -->
                </div>
            </div>

            <div class="cert-title">GATE PASS</div>

            <div class="content-row">
                <div class="details-column">
                    <table class="meta-table">
                        <tr>
                            <td class="meta-label">User Type</td>
                            <td>: <?php echo ucfirst($gatepass['user_type']); ?></td>
                        </tr>
                        <tr>
                            <td class="meta-label">Name & Details</td>
                            <td>: <?php echo $gatepass['user_details']; ?></td>
                        </tr>
                        <?php if (!empty($gatepass['user_info']['vehicle_no']) || !empty($gatepass['user_info']['route_title'])) { ?>
                        <tr>
                            <td class="meta-label">Bus / Route</td>
                            <td>: <?php 
                                $trans_details = array();
                                if (!empty($gatepass['user_info']['vehicle_no'])) $trans_details[] = 'Bus: ' . $gatepass['user_info']['vehicle_no'];
                                if (!empty($gatepass['user_info']['route_title'])) $trans_details[] = 'Route: ' . $gatepass['user_info']['route_title'];
                                if (!empty($gatepass['user_info']['pickup_point_name'])) $trans_details[] = 'Stop: ' . $gatepass['user_info']['pickup_point_name'];
                                echo implode(' | ', $trans_details);
                            ?></td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td class="meta-label">Out Time</td>
                            <td>: <?php echo $gatepass['out_time']; ?></td>
                        </tr>
                        <?php if ($gatepass['status'] == 'Completed') { ?>
                        <tr>
                            <td class="meta-label">In Time</td>
                            <td>: <?php echo $gatepass['in_time']; ?></td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td class="meta-label">Reason</td>
                            <td>: <?php echo $gatepass['reason']; ?></td>
                        </tr>
                        <tr>
                            <td class="meta-label">Status</td>
                            <td>: <strong><?php echo $gatepass['status']; ?></strong></td>
                        </tr>
                    </table>
                </div>

                <div class="barcode-column">
                    <div class="barcode-container">
                        <!-- Barcode Generation -->
                        <img src="<?php echo base_url($this->customlib->generatebarcode($gatepass['gate_pass_no'], $gatepass['id'], 'barcode')); ?>" alt="Barcode">
                        <br><br>
                        <!-- QR Code Generation -->
                        <img src="<?php echo base_url($this->customlib->generatebarcode($gatepass['gate_pass_no'], $gatepass['id'], 'qrcode')); ?>" alt="QR Code">
                    </div>
                </div>
            </div>

            <table class="signatures">
                <tr>
                    <td>
                        <div class="sign-line">Class Teacher (For Students)</div>
                    </td>
                    <td>
                        <div class="sign-line">Applicant Sign</div>
                    </td>
                    <td>
                        <div class="sign-line">Authorized Sign</div>
                    </td>
                </tr>
            </table>

        </div>
    </div>
</div>

</body>
</html>
