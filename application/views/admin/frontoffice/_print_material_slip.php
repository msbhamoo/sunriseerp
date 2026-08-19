<?php
$theme_color = ($material['direction'] == 'inward') ? '#059669' : '#d97706';
$dir_label   = ($material['direction'] == 'inward') ? 'INWARD MATERIAL GATE PASS' : 'OUTWARD MATERIAL GATE PASS';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Material Gate Pass - <?php echo html_escape($material['gate_pass_no']); ?></title>
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
        max-width: 650px;
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
        max-width: 75px;
        max-height: 75px;
    }
    
    .school-info {
        flex-grow: 1;
        text-align: center;
        padding: 0 15px;
    }
    .school-name {
        font-size: 19px;
        font-weight: bold;
        margin: 0 0 4px 0;
    }
    .school-address {
        font-size: 12px;
        margin: 0 0 4px 0;
    }
    
    .cert-title {
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        margin: 10px 0 16px 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--primary-color);
    }

    .meta-table {
        width: 100%;
        margin-bottom: 15px;
        font-size: 12px;
        line-height: 1.5;
        border-collapse: collapse;
    }
    .meta-table td {
        padding: 3px 4px;
        vertical-align: top;
    }
    .meta-label {
        width: 130px;
        font-weight: bold;
        color: #4b5563;
    }

    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        margin-bottom: 15px;
        font-size: 12px;
    }
    .items-table th {
        background-color: #f8fafc;
        border: 1px solid #cbd5e1;
        padding: 6px 8px;
        text-align: left;
        font-weight: bold;
    }
    .items-table td {
        border: 1px solid #e2e8f0;
        padding: 6px 8px;
    }
    .items-table tfoot td {
        background-color: #f8fafc;
        font-weight: bold;
        border: 1px solid #cbd5e1;
    }

    .signatures {
        width: 100%;
        margin-top: 40px;
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
                <div>Gate Pass No: <strong><?php echo html_escape($material['gate_pass_no']); ?></strong></div>
                <div>Date: <strong><?php echo date($this->customlib->getSchoolDateFormat(), strtotime($material['date'])); ?></strong></div>
            </div>

            <div class="receipt-header">
                <div class="logo-container">
                    <?php if (!empty($sch_setting->admin_logo)) { ?>
                        <img src="<?php echo $this->customlib->getBaseUrl(); ?>uploads/school_content/admin_logo/<?php echo $sch_setting->admin_logo; ?>" alt="Logo">
                    <?php } ?>
                </div>
                <div class="school-info">
                    <div class="school-name"><?php echo $sch_setting->name; ?></div>
                    <div class="school-address"><?php echo $sch_setting->address; ?></div>
                    <div><?php echo $sch_setting->phone; ?></div>
                </div>
                <div class="logo-container" style="width: 75px;">
                    <!-- Right balance space -->
                </div>
            </div>

            <div class="cert-title"><?php echo $dir_label; ?></div>

            <table class="meta-table">
                <tr>
                    <td class="meta-label">Movement Type</td>
                    <td style="width: 35%;">: <strong><?php echo ucfirst($material['direction']); ?></strong></td>
                    <td class="meta-label">Time (In / Out)</td>
                    <td>: In: <?php echo $material['in_time'] ? html_escape($material['in_time']) : '-'; ?> | Out: <?php echo $material['out_time'] ? html_escape($material['out_time']) : '-'; ?></td>
                </tr>
                <tr>
                    <td class="meta-label">From / To (Party)</td>
                    <td>: <?php echo html_escape($material['party_name'] ? $material['party_name'] : '-'); ?></td>
                    <td class="meta-label">Carried By</td>
                    <td>: <?php echo html_escape($material['carried_by'] . (!empty($material['contact']) ? ' (' . $material['contact'] . ')' : '')); ?></td>
                </tr>
                <tr>
                    <td class="meta-label">Vehicle No.</td>
                    <td>: <?php echo html_escape($material['vehicle_no'] ? $material['vehicle_no'] : '-'); ?></td>
                    <td class="meta-label">Driver Name</td>
                    <td>: <?php echo html_escape($material['driver_name'] ? $material['driver_name'] : '-'); ?></td>
                </tr>
                <tr>
                    <td class="meta-label">Received / Issued By</td>
                    <td>: <?php echo html_escape(!empty($material['staff_name']) ? ($material['staff_name'] . ' ' . $material['staff_surname'] . ($material['staff_employee_id'] ? ' (' . $material['staff_employee_id'] . ')' : '')) : '-'); ?></td>
                    <td class="meta-label">Department</td>
                    <td>: <?php echo html_escape($material['department'] ? $material['department'] : '-'); ?></td>
                </tr>
                <tr>
                    <td class="meta-label">Approved By</td>
                    <td>: <?php echo html_escape($material['approved_by'] ? $material['approved_by'] : '-'); ?></td>
                    <td class="meta-label">Remarks</td>
                    <td>: <?php echo html_escape($material['remarks'] ? $material['remarks'] : '-'); ?></td>
                </tr>
            </table>

            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;">#</th>
                        <th>Material / Item Description</th>
                        <th style="width: 100px;">Quantity</th>
                        <th style="width: 90px;">Unit</th>
                        <th style="width: 110px; text-align: right;">Total Cost</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $items_list = !empty($material['items']) ? $material['items'] : array();
                    if (empty($items_list) && !empty($material['material_name'])) {
                        $items_list[] = array(
                            'material_name' => $material['material_name'],
                            'quantity'      => $material['quantity'],
                            'unit'          => $material['unit'],
                            'total_cost'    => $material['total_cost'],
                        );
                    }
                    $grand_cost = 0;
                    $has_cost = false;
                    if (!empty($items_list)) {
                        foreach ($items_list as $i => $it) {
                            $c_val = isset($it['total_cost']) && $it['total_cost'] !== '' ? (float)str_replace(',', '', $it['total_cost']) : null;
                            if ($c_val !== null) {
                                $grand_cost += $c_val;
                                $has_cost = true;
                            }
                            ?>
                            <tr>
                                <td style="text-align: center;"><?php echo ($i + 1); ?></td>
                                <td><strong><?php echo html_escape($it['material_name']); ?></strong></td>
                                <td><?php echo html_escape($it['quantity'] ? $it['quantity'] : '-'); ?></td>
                                <td><?php echo html_escape($it['unit'] ? $it['unit'] : '-'); ?></td>
                                <td style="text-align: right;"><?php echo ($c_val !== null) ? number_format($c_val, 2) : '-'; ?></td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo '<tr><td colspan="5" style="text-align: center;">No items recorded</td></tr>';
                    }
                    ?>
                </tbody>
                <?php if ($has_cost) { ?>
                    <tfoot>
                        <tr>
                            <td colspan="4" style="text-align: right;">Grand Total:</td>
                            <td style="text-align: right; color: var(--primary-color);"><?php echo number_format($grand_cost, 2); ?></td>
                        </tr>
                    </tfoot>
                <?php } ?>
            </table>

            <table class="signatures">
                <tr>
                    <td>
                        <div class="sign-line">Received / Carried By</div>
                    </td>
                    <td>
                        <div class="sign-line">Security / Gate Incharge</div>
                    </td>
                    <td>
                        <div class="sign-line">Authorized Signatory</div>
                    </td>
                </tr>
            </table>

        </div>
    </div>
</div>

</body>
</html>
