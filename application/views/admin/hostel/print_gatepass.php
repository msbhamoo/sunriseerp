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
        --primary-light: <?php echo $theme_color; ?>15;
        --primary-dark: <?php echo $theme_color; ?>cc;
        --border-color: #e5e7eb;
        --text-main: #1f2937;
        --text-muted: #6b7280;
        --bg-light: #f9fafb;
        --danger: #dc2626;
        --danger-light: #fef2f2;
        --danger-border: #fecaca;
    }

    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        color-adjust: exact !important;
    }

    @media print {
        :root {
            --border-color: #000000;
        }
        .receipt-table th {
            background-color: var(--primary-color) !important;
            color: #fff !important;
        }
        .receipt-copy {
            border: 1px solid #000000 !important;
        }
        .receipt-table th, .receipt-table td {
            border: 1px solid #000000 !important;
        }
        .emergency-box {
            border-color: #000000 !important;
        }
        .disclaimer-box {
            border-color: #000000 !important;
        }
        .no-print {
            display: none !important;
        }
        @page {
            margin: 10mm;
        }
    }

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
        border: 1px solid var(--border-color);
        margin-bottom: 20px;
        position: relative;
        overflow: hidden;
        page-break-inside: avoid;
    }

    .receipt-inner {
        position: relative;
        z-index: 1;
        padding: 15px;
    }

    .header-top {
        display: flex;
        justify-content: space-between;
        font-size: 10px;
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
        width: 80px;
        flex-shrink: 0;
    }
    .logo-container img {
        width: 100%;
        height: auto;
    }
    .school-info {
        flex-grow: 1;
        text-align: center;
        padding: 0 15px;
    }
    .school-name {
        font-size: 20px;
        font-weight: 700;
        color: var(--primary-color);
        margin: 0 0 5px 0;
    }
    .school-address {
        font-size: 11px;
        color: var(--text-muted);
        margin: 0 0 5px 0;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 5px;
        display: inline-block;
        width: 80%;
    }
    .school-contact {
        font-size: 10px;
        margin: 0;
        color: var(--text-muted);
    }
    .copy-label {
        font-size: 14px;
        font-weight: 700;
        color: var(--primary-color);
        width: 100px;
        text-align: right;
    }

    .meta-grid {
        display: flex;
        justify-content: space-between;
        border-top: 1px solid var(--border-color);
        padding-top: 10px;
        margin-bottom: 10px;
    }
    .meta-col {
        width: 48%;
    }
    .meta-row {
        display: flex;
        margin-bottom: 4px;
        font-size: 11px;
    }
    .meta-row .meta-label {
        width: 100px;
        color: var(--text-muted);
    }
    .meta-row .value {
        font-weight: 600;
    }
    .meta-col.right {
        text-align: right;
    }
    .meta-col.right .meta-row {
        justify-content: flex-end;
    }
    .meta-col.right .meta-label {
        width: 120px;
        text-align: right;
        margin-right: 10px;
    }
    .meta-col.right .value {
        width: 120px;
        text-align: left;
    }

    .reason-box {
        border: 1px solid var(--border-color);
        padding: 10px;
        font-size: 12px;
        margin-bottom: 15px;
        min-height: 60px;
        background: var(--bg-light);
    }

    .reason-title {
        font-weight: 700;
        margin-bottom: 5px;
        color: var(--primary-color);
    }

    /* Emergency Contact Box */
    .emergency-box {
        border: 2px solid var(--danger);
        border-radius: 6px;
        padding: 12px 14px;
        margin-bottom: 15px;
        background: var(--danger-light);
        position: relative;
    }

    .emergency-box::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 5px;
        background: var(--danger);
        border-radius: 6px 0 0 6px;
    }

    .emergency-title {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 800;
        color: var(--danger);
        margin: 0 0 8px 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .emergency-title svg {
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }

    .emergency-grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 8px;
    }

    .emergency-item {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .emergency-item .em-label {
        font-size: 9px;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }

    .emergency-item .em-value {
        font-size: 12px;
        font-weight: 700;
        color: var(--text-main);
        font-family: 'JetBrains Mono', monospace;
    }

    .emergency-note {
        margin-top: 8px;
        padding-top: 8px;
        border-top: 1px solid var(--danger-border);
        font-size: 10px;
        color: var(--danger);
        font-weight: 600;
        font-style: italic;
    }

    /* Disclaimer Box */
    .disclaimer-box {
        border: 1px solid var(--border-color);
        border-left: 4px solid var(--primary-color);
        padding: 10px 14px;
        margin-bottom: 15px;
        background: var(--bg-light);
    }

    .disclaimer-title {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--primary-color);
        margin: 0 0 6px 0;
    }

    .disclaimer-text {
        font-size: 9px;
        color: var(--text-muted);
        line-height: 1.6;
        margin: 0;
    }

    .disclaimer-text ul {
        margin: 4px 0 0 0;
        padding-left: 14px;
    }

    .disclaimer-text li {
        margin-bottom: 2px;
    }

    /* Footer Section */
    .footer-info {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        padding: 15px 0 10px 0;
        font-size: 11px;
        border-top: 1px solid var(--border-color);
    }

    .signature-box {
        text-align: center;
        width: 120px;
    }
    .signature-line {
        border-bottom: 1px solid #000;
        margin-bottom: 5px;
        height: 30px;
    }

    /* Bottom Footer Bar */
    .receipt-bottom-footer {
        border-top: 2px solid var(--primary-color);
        padding: 10px 0 0 0;
        margin-top: 5px;
    }

    .footer-main-text {
        text-align: center;
        font-size: 10px;
        color: var(--text-muted);
        line-height: 1.5;
        margin: 0 0 5px 0;
    }

    .footer-main-text strong {
        color: var(--text-main);
    }

    .footer-divider {
        height: 1px;
        background: var(--border-color);
        margin: 6px 0;
    }

    .footer-meta-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 8px;
        color: var(--text-muted);
    }

    .footer-meta-row .footer-barcode-text {
        font-family: 'JetBrains Mono', monospace;
        font-size: 9px;
        font-weight: 500;
        color: var(--text-main);
        letter-spacing: 1.5px;
    }

    .footer-printed-on {
        font-style: italic;
    }
</style>

<div class="print-receipt-wrapper">
    <div class="receipt-copy">
        <div class="receipt-inner">

            <!-- Header Top -->
            <div class="header-top">
                <span>Reg No: <?php echo $settinglist[0]['dise_code']; ?></span>
                <span>U Dise Code: <?php echo $settinglist[0]['dise_code']; ?></span>
            </div>

            <!-- Receipt Header -->
            <div class="receipt-header">
                <div class="logo-container">
                    <img src="<?php echo base_url('uploads/school_content/logo/' . $settinglist[0]['image']); ?>" alt="Logo">
                </div>
                <div class="school-info">
                    <h1 class="school-name"><?php echo $settinglist[0]['name']; ?></h1>
                    <div class="school-address"><?php echo $settinglist[0]['address']; ?></div>
                    <p class="school-contact">Email: <?php echo $settinglist[0]['email']; ?> | Mobile: <?php echo $settinglist[0]['phone']; ?></p>
                </div>
                <div class="copy-label">GATE PASS</div>
            </div>

            <!-- Meta Grid -->
            <div class="meta-grid">
                <div class="meta-col">
                    <div class="meta-row"><span class="meta-label">Gate Pass No</span><span class="value">: GP-<?php echo str_pad($gatepass['id'], 5, '0', STR_PAD_LEFT); ?></span></div>
                    <div class="meta-row"><span class="meta-label">Student Name</span><span class="value">: <?php echo strtoupper($gatepass['firstname'] . ' ' . $gatepass['lastname']); ?></span></div>
                    <div class="meta-row"><span class="meta-label">Admission No</span><span class="value">: <?php echo $gatepass['admission_no']; ?></span></div>
                    <div class="meta-row"><span class="meta-label">Father's Name</span><span class="value">: <?php echo strtoupper($gatepass['father_name']); ?></span></div>
                    <div class="meta-row"><span class="meta-label">Mother's Name</span><span class="value">: <?php echo strtoupper($gatepass['mother_name']); ?></span></div>
                    <div class="meta-row"><span class="meta-label">Class</span><span class="value">: <?php echo strtoupper($gatepass['class_name'] . ' - ' . $gatepass['section_name']); ?></span></div>
                </div>
                <div class="meta-col right">
                    <div class="meta-row"><span class="meta-label">Status</span><span class="value">: <?php echo strtoupper($gatepass['status']); ?></span></div>
                    <div class="meta-row"><span class="meta-label">Going To</span><span class="value">: <?php echo $gatepass['going_to']; ?></span></div>
                    <div class="meta-row"><span class="meta-label">Out Date</span><span class="value">: <?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($gatepass['out_date'])); ?></span></div>
                    <div class="meta-row"><span class="meta-label">Out Time</span><span class="value">: <?php echo date("h:i A", strtotime($gatepass['out_time'])); ?></span></div>
                    <?php if (!empty($gatepass['expected_in_time'])) { ?>
                    <div class="meta-row"><span class="meta-label">Expected Return</span><span class="value">: <?php echo date("h:i A", strtotime($gatepass['expected_in_time'])); ?></span></div>
                    <?php } ?>
                    <?php if (!empty($gatepass['actual_in_time'])) { ?>
                    <div class="meta-row"><span class="meta-label">Actual Return</span><span class="value">: <?php echo date("h:i A", strtotime($gatepass['actual_in_time'])); ?></span></div>
                    <?php } ?>
                </div>
            </div>

            <!-- Reason Box -->
            <div class="reason-box">
                <div class="reason-title">Reason for Leaving:</div>
                <?php echo nl2br(strip_tags($gatepass['reason'])); ?>
            </div>

            <!-- Emergency Contact Box -->
            <div class="emergency-box">
                <div class="emergency-title">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                    Emergency Contact Information
                </div>
                <div class="emergency-grid">
                    <div class="emergency-item">
                        <span class="em-label">School Office</span>
                        <span class="em-value"><?php echo $settinglist[0]['phone']; ?></span>
                    </div>
                    <div class="emergency-item">
                        <span class="em-label">Principal / Head</span>
                        <span class="em-value"><?php echo !empty($settinglist[0]['principal_phone']) ? $settinglist[0]['principal_phone'] : $settinglist[0]['phone']; ?></span>
                    </div>
                    <div class="emergency-item">
                        <span class="em-label">Guardian Contact</span>
                        <span class="em-value"><?php echo !empty($gatepass['guardian_phone']) ? $gatepass['guardian_phone'] : $gatepass['father_phone']; ?></span>
                    </div>
                    <div class="emergency-item">
                        <span class="em-label">Warden / Hostel</span>
                        <span class="em-value"><?php echo !empty($settinglist[0]['warden_phone']) ? $settinglist[0]['warden_phone'] : 'N/A'; ?></span>
                    </div>
                    <div class="emergency-item">
                        <span class="em-label">Security Gate</span>
                        <span class="em-value"><?php echo !empty($settinglist[0]['security_phone']) ? $settinglist[0]['security_phone'] : 'N/A'; ?></span>
                    </div>
                    <div class="emergency-item">
                        <span class="em-label">Local Police</span>
                        <span class="em-value">100</span>
                    </div>
                </div>
                <div class="emergency-note">
                    &#9888; In case of any emergency during transit, contact the school office immediately. This gate pass is valid only for the stated date, time, and purpose.
                </div>
            </div>

            <!-- Disclaimer Box -->
            <div class="disclaimer-box">
                <div class="disclaimer-title">&#9888; Disclaimer &amp; Terms</div>
                <div class="disclaimer-text">
                    <ul>
                        <li>This gate pass is issued solely for the purpose mentioned above and is <strong>non-transferable</strong>. It remains valid only for the specified date and time.</li>
                        <li>The school shall <strong>not be held responsible</strong> for any incident, injury, loss, or damage that occurs to the student while outside the school premises.</li>
                        <li>The student must be <strong>accompanied by an authorized guardian</strong> (whose name and ID are recorded at the gate) during departure and return.</li>
                        <li>Failure to return within the <strong>stipulated time</strong> may result in disciplinary action as per school rules.</li>
                        <li>This document is a <strong>single-use permit</strong>. Any duplication, alteration, or misuse is strictly prohibited and may attract legal action.</li>
                        <li>The school reserves the right to <strong>revoke this pass</strong> at any time without prior notice if circumstances warrant such action.</li>
                        <li>By accepting this gate pass, the parent/guardian acknowledges and agrees to the above terms and conditions.</li>
                    </ul>
                </div>
            </div>

            <!-- Signature Section -->
            <div class="footer-info">
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div>Student / Guardian Sign</div>
                </div>
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div>Class Teacher Sign</div>
                </div>
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div>Warden / HOD Sign</div>
                </div>
                <div class="signature-box">
                    <div class="signature-line"></div>
                    <div>Principal Sign</div>
                </div>
            </div>

            <!-- Bottom Footer -->
            <div class="receipt-bottom-footer">
                <p class="footer-main-text">
                    <strong><?php echo $settinglist[0]['name']; ?></strong> &mdash; 
                    <?php echo $settinglist[0]['address']; ?> | 
                    Ph: <?php echo $settinglist[0]['phone']; ?> | 
                    Email: <?php echo $settinglist[0]['email']; ?>
                </p>
                <div class="footer-divider"></div>
                <div class="footer-meta-row">
                    <span class="footer-printed-on">Printed on: <?php echo date('d M Y, h:i A'); ?></span>
                    <span class="footer-barcode-text">GP-<?php echo str_pad($gatepass['id'], 5, '0', STR_PAD_LEFT); ?></span>
                    <span>This is a computer-generated document.</span>
                </div>
            </div>

        </div>
    </div>
</div>