<?php
$theme_color = '#0d9488'; // Deep Teal for Official Duty Pass
$scan_type = !empty($sch_setting->scan_code_type) ? $sch_setting->scan_code_type : 'barcode';
$sch_name = !empty($sch_setting->name) ? $sch_setting->name : 'Sunrise School';
$sch_address = !empty($sch_setting->address) ? $sch_setting->address : '';
$sch_phone = !empty($sch_setting->phone) ? $sch_setting->phone : '';
$sch_email = !empty($sch_setting->email) ? $sch_setting->email : '';

$logo_img = !empty($sch_setting->admin_logo) ? $sch_setting->admin_logo : (!empty($sch_setting->image) ? $sch_setting->image : '');
$sch_logo = !empty($logo_img) ? (file_exists(FCPATH . 'uploads/school_content/admin_logo/' . $logo_img) ? base_url('uploads/school_content/admin_logo/' . $logo_img) : base_url('uploads/school_content/logo/' . $logo_img)) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Field Duty Pass - <?php echo html_escape($duty_pass['duty_pass_no']); ?></title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    :root {
        --primary-color: <?php echo $theme_color; ?>;
        --border-color: #cbd5e1;
        --text-main: #0f172a;
        --text-muted: #475569;
    }

    * {
        box-sizing: border-box;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
    }

    body {
        margin: 0;
        padding: 20px;
        background: #f8fafc;
        font-family: 'Segoe UI', Arial, sans-serif;
        color: var(--text-main);
    }

    .print-receipt-wrapper {
        width: 100%;
        max-width: 780px;
        margin: 0 auto;
        background: #ffffff;
        border: 2px solid var(--primary-color);
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        position: relative;
        overflow: hidden;
    }

    .watermark {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-30deg);
        font-size: 80px;
        font-weight: 900;
        color: rgba(13, 148, 136, 0.05);
        text-transform: uppercase;
        letter-spacing: 12px;
        pointer-events: none;
        z-index: 0;
        white-space: nowrap;
    }

    .receipt-inner {
        position: relative;
        z-index: 1;
        padding: 24px 30px;
    }

    .receipt-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 2px solid var(--primary-color);
        padding-bottom: 14px;
        margin-bottom: 16px;
    }

    .logo-container img {
        max-width: 85px;
        max-height: 85px;
        object-fit: contain;
    }

    .school-info {
        flex-grow: 1;
        text-align: center;
        padding: 0 15px;
    }

    .school-name {
        font-size: 22px;
        font-weight: 800;
        margin: 0 0 4px 0;
        color: #0f172a;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .school-address {
        font-size: 12px;
        color: var(--text-muted);
        margin: 0 0 4px 0;
    }

    .school-contact {
        font-size: 11.5px;
        color: var(--text-muted);
    }

    .pass-title-banner {
        background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
        color: #ffffff;
        text-align: center;
        padding: 8px 16px;
        border-radius: 8px;
        margin-bottom: 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .pass-title-banner h2 {
        margin: 0;
        font-size: 16px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .pass-title-banner .pass-no {
        font-size: 14px;
        font-weight: 700;
        background: rgba(255,255,255,0.2);
        padding: 3px 10px;
        border-radius: 6px;
        letter-spacing: 0.5px;
    }

    .duty-details-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: #ffffff;
        border: 1.5px solid #cbd5e1;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 20px;
        font-size: 12.5px;
    }

    .duty-details-table td {
        padding: 9px 14px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
        line-height: 1.5;
    }

    .duty-details-table tr:last-child td {
        border-bottom: none;
    }

    .dt-label {
        width: 160px;
        font-weight: 700;
        color: #334155;
        background: #f8fafc;
        border-right: 1px solid #e2e8f0;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        white-space: nowrap;
    }

    .dt-label i {
        margin-right: 4px;
        width: 14px;
        text-align: center;
    }

    .dt-value {
        color: #0f172a;
        background: #ffffff;
    }

    .dt-value.half-col {
        border-right: 1px solid #e2e8f0;
        width: 34%;
    }

    .duty-badge {
        display: inline-block;
        background: #ccfbf1;
        color: #0f766e;
        border: 1px solid #99f6e4;
        padding: 2px 10px;
        border-radius: 4px;
        font-size: 11.5px;
        font-weight: 700;
    }

    .section-heading {
        font-size: 13px;
        font-weight: 800;
        color: #0f172a;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 16px 0 8px 0;
        display: flex;
        align-items: center;
        gap: 6px;
        border-bottom: 1.5px solid #e2e8f0;
        padding-bottom: 4px;
    }

    .members-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 16px;
        font-size: 12.5px;
    }

    .members-table th {
        background: #f1f5f9;
        color: #334155;
        font-weight: 700;
        text-align: left;
        padding: 7px 10px;
        border: 1px solid #cbd5e1;
    }

    .members-table td {
        padding: 7px 10px;
        border: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .signature-section {
        display: flex;
        justify-content: space-between;
        margin-top: 36px;
        padding-top: 10px;
    }

    .sig-box {
        text-align: center;
        width: 180px;
    }

    .sig-line {
        border-top: 1.5px dashed #64748b;
        margin-top: 45px;
        margin-bottom: 5px;
    }

    .sig-text {
        font-size: 11.5px;
        font-weight: 700;
        color: #334155;
    }

    .print-actions {
        text-align: center;
        margin: 18px 0;
    }

    .btn-print {
        background: #0d9488;
        color: #fff;
        border: none;
        padding: 10px 24px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.15);
    }

    .btn-print:hover {
        background: #0f766e;
    }

    @media print {
        body {
            background: #fff;
            padding: 0;
        }
        .print-actions {
            display: none;
        }
        .print-receipt-wrapper {
            box-shadow: none;
            border-width: 1.5px;
            max-width: 100%;
        }
    }
</style>
</head>
<body>

<div class="print-actions">
    <button class="btn-print" onclick="window.print();"><i class="fa fa-print"></i> Print Official Duty Pass</button>
    <button class="btn-print" style="background:#475569;" onclick="window.close();"><i class="fa fa-times"></i> Close Window</button>
</div>

<div class="print-receipt-wrapper">
    <div class="watermark">ON DUTY</div>
    <div class="receipt-inner">
        <!-- Header -->
        <div class="receipt-header">
            <div class="logo-container">
                <?php if (!empty($sch_logo)) { ?>
                    <img src="<?php echo $sch_logo; ?>" alt="Logo">
                <?php } ?>
            </div>
            <div class="school-info">
                <h1 class="school-name"><?php echo html_escape($sch_name); ?></h1>
                <div class="school-address"><?php echo html_escape($sch_address); ?></div>
                <div class="school-contact">
                    <?php if (!empty($sch_phone)) { ?><span><i class="fa fa-phone"></i> <?php echo html_escape($sch_phone); ?></span><?php } ?>
                    <?php if (!empty($sch_email)) { ?> | <span><i class="fa fa-envelope"></i> <?php echo html_escape($sch_email); ?></span><?php } ?>
                </div>
            </div>
            <div style="text-align:right;">
                <!-- QR code verification placeholder / barcode -->
                <div style="font-size:11px; font-weight:700; color:#0d9488; margin-bottom:4px;">OFFICIAL CLEARANCE</div>
                <div style="border:1px solid #cbd5e1; border-radius:6px; padding:6px; background:#fff; text-align:center;">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=<?php echo urlencode(base_url('admin/dutypass/print_dutypass/' . $duty_pass['id'])); ?>" alt="QR" style="width:55px; height:55px;">
                </div>
            </div>
        </div>

        <!-- Title Banner -->
        <div class="pass-title-banner">
            <h2><i class="fa fa-certificate"></i> Official Staff Field Duty Pass</h2>
            <div class="pass-no"><?php echo html_escape($duty_pass['duty_pass_no']); ?></div>
        </div>

        <!-- Duty Assignment Details Table -->
        <table class="duty-details-table">
            <tr>
                <td class="dt-label"><i class="fa fa-flag text-teal" style="color:#0d9488;"></i> Duty / Event</td>
                <td class="dt-value" colspan="3"><strong style="font-size:14px; color:#0f766e;"><?php echo html_escape($duty_pass['title']); ?></strong></td>
            </tr>
            <tr>
                <td class="dt-label"><i class="fa fa-tag text-teal" style="color:#0d9488;"></i> Category</td>
                <td class="dt-value half-col"><span class="duty-badge"><?php echo html_escape($duty_pass['category']); ?></span></td>
                <td class="dt-label"><i class="fa fa-map-marker text-danger"></i> Destination</td>
                <td class="dt-value"><strong><?php echo html_escape($duty_pass['venue']); ?></strong></td>
            </tr>
            <tr>
                <td class="dt-label"><i class="fa fa-calendar text-teal" style="color:#0d9488;"></i> Duty Period</td>
                <td class="dt-value half-col">
                    <?php 
                    $from_f = date($this->customlib->getSchoolDateFormat(), strtotime($duty_pass['from_date']));
                    $to_f = date($this->customlib->getSchoolDateFormat(), strtotime($duty_pass['to_date']));
                    if ($from_f === $to_f) {
                        echo "<strong>" . $from_f . "</strong> (1 Day)";
                    } else {
                        $days = ((strtotime($duty_pass['to_date']) - strtotime($duty_pass['from_date'])) / 86400) + 1;
                        echo "<strong>" . $from_f . "</strong> to <strong>" . $to_f . "</strong> (" . $days . " Days)";
                    }
                    ?>
                </td>
                <td class="dt-label"><i class="fa fa-clock-o text-teal" style="color:#0d9488;"></i> Timings</td>
                <td class="dt-value">
                    <?php 
                    $dep = !empty($duty_pass['departure_time']) ? date('h:i A', strtotime($duty_pass['departure_time'])) : 'School Hours';
                    $ret = !empty($duty_pass['return_time']) ? date('h:i A', strtotime($duty_pass['return_time'])) : 'After Event';
                    echo $dep . " &rarr; " . $ret;
                    ?>
                </td>
            </tr>
            <?php if (!empty($duty_pass['transport_details'])) { ?>
            <tr>
                <td class="dt-label"><i class="fa fa-bus text-info"></i> Transport</td>
                <td class="dt-value" colspan="3"><i class="fa fa-automobile text-muted"></i> <?php echo html_escape($duty_pass['transport_details']); ?></td>
            </tr>
            <?php } ?>
            <?php if (!empty($duty_pass['student_details'])) { ?>
            <tr>
                <td class="dt-label"><i class="fa fa-graduation-cap text-teal" style="color:#0d9488;"></i> Students / Team</td>
                <td class="dt-value" colspan="3"><?php echo nl2br(html_escape($duty_pass['student_details'])); ?></td>
            </tr>
            <?php } ?>
            <?php if (!empty($duty_pass['description'])) { ?>
            <tr>
                <td class="dt-label"><i class="fa fa-info-circle text-muted"></i> Remarks / Notes</td>
                <td class="dt-value" colspan="3" style="color:#334155;"><?php echo nl2br(html_escape($duty_pass['description'])); ?></td>
            </tr>
            <?php } ?>
        </table>

        <!-- Assigned Staff Members -->
        <div class="section-heading"><i class="fa fa-users text-teal"></i> Deputed Staff Member(s)</div>
        <table class="members-table">
            <thead>
                <tr>
                    <th width="40">#</th>
                    <th width="100">Staff ID</th>
                    <th>Staff Name</th>
                    <th>Designation / Role</th>
                    <th>Contact No</th>
                    <th>Role in Duty</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $m_count = 1;
                foreach ($duty_pass['staff_members'] as $member) { 
                ?>
                    <tr>
                        <td><?php echo $m_count++; ?></td>
                        <td><code><?php echo html_escape($member['employee_id']); ?></code></td>
                        <td><strong><?php echo html_escape($member['name'] . ' ' . $member['surname']); ?></strong></td>
                        <td><?php echo html_escape(!empty($member['designation']) ? $member['designation'] : $member['role_name']); ?></td>
                        <td><?php echo html_escape($member['contact_no']); ?></td>
                        <td><span style="background:#f1f5f9; padding:2px 6px; border-radius:4px; font-weight:600;"><?php echo html_escape($member['role_in_duty']); ?></span></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        <div style="font-size:11px; color:#64748b; margin-top:8px; line-height:1.4;">
            * <strong>Note:</strong> The above deputed staff member(s) are on official school field duty. Attendance is officially recorded as <strong>On Duty (OD)</strong> in the school records.
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="sig-box">
                <div class="sig-line"></div>
                <div class="sig-text">Staff Incharge Signature</div>
            </div>
            <div class="sig-box">
                <div class="sig-line"></div>
                <div class="sig-text">Admin / HR Officer</div>
            </div>
            <div class="sig-box">
                <div class="sig-line"></div>
                <div class="sig-text">Principal / Director</div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
