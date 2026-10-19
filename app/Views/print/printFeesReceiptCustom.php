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
        max-width: 800px; /* Approximately A4 width constraints */
        margin: 15px auto;
    }

    /* Receipt Copy Container */
    .receipt-copy {
        width: 100%;
        border: 1px solid var(--border-color);
        margin-bottom: 20px;
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
        padding: 15px;
    }

    /* Header Top Row */
    .header-top {
        display: flex;
        justify-content: space-between;
        font-size: 10px;
        color: var(--text-muted);
        margin-bottom: 10px;
    }

    /* Main Header */
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
    .session-info {
        font-weight: 600;
        font-size: 11px;
        margin-top: 3px;
        color: var(--text-main);
    }
    .copy-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--text-muted);
        width: 80px;
        text-align: right;
    }

    /* Meta Info Grid */
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
        width: 80px;
        text-align: right;
        margin-right: 10px;
    }
    .meta-col.right .value {
        width: 100px;
        text-align: left;
    }

    .fees-title {
        font-size: 12px;
        font-weight: 700;
        margin-bottom: 5px;
        text-align: right;
    }

    /* Table */
    .receipt-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
        font-size: 10px;
    }
    .receipt-table th {
        background-color: var(--primary-color);
        color: #fff;
        text-align: left;
        padding: 6px 8px;
        font-weight: 600;
        border: 1px solid var(--border-color);
    }
    .receipt-table th:last-child {
        text-align: right;
    }
    .receipt-table td {
        padding: 6px 8px;
        border: 1px solid var(--border-color);
        color: var(--text-main);
    }
    .receipt-table td:last-child {
        text-align: right;
        font-family: 'JetBrains Mono', monospace;
    }

    /* Ensure exactly 7 rows */
    .receipt-table tr.empty-row td {
        height: 25px;
        color: transparent;
    }

    /* Footer Info */
    .footer-info {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
        font-size: 11px;
    }
    .qr-placeholder {
        width: 50px;
        height: 50px;
        flex-shrink: 0;
    }
    .qr-placeholder img {
        width: 100%;
        height: 100%;
    }
    .payment-mode {
        font-weight: 600;
    }
    .amount-words {
        font-style: italic;
        color: var(--text-muted);
    }
    .total-deposit {
        font-weight: 700;
        font-size: 12px;
    }

    /* Footer Bottom */
    .footer-bottom {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-top: 10px;
        font-size: 9px;
        color: var(--text-muted);
    }
    .notes {
        max-width: 75%;
        font-size: 8px;
        white-space: nowrap;
    }
    .signature-box {
        text-align: center;
        width: 150px;
    }
    .sig-space {
        height: 20px;
    }
    .sig-label {
        border-top: 1px dashed var(--border-color);
        padding-top: 5px;
        font-weight: 600;
        font-size: 10px;
        color: var(--text-main);
    }

    @media print {
        @page {
            size: A4 portrait;
            margin: 0;
        }
        body {
            margin: 0;
            padding: 0;
            background: none;
        }
        /* Restrict the entire wrap to strict A4 height */
        .print-receipt-wrapper {
            max-width: 100%;
            padding: 0;
            display: flex;
            flex-direction: column;
            height: 290mm; /* Slightly less than A4 to prevent Firefox blank page */
            max-height: 290mm;
            overflow: hidden;
        }
        /* Each copy takes almost half the page */
        .receipt-copy {
            border-color: #000;
            flex: 1;
            max-height: 49.5%;
            margin-bottom: 10px;
            page-break-inside: avoid;
        }
    }
</style>

<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();

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

if (!$feeList) {
    echo "<div class='alert alert-danger'>Fee record not found.</div>";
    return;
}

// Find the specific deposit record
$fee_deposit_record = null;
$amount_detail = json_decode($feeList->amount_detail);
foreach ($amount_detail as $key => $value) {
    if ($key == $sub_invoice_id) {
        $fee_deposit_record = $value;
        break;
    }
}

$receipt_no = '';
// Fetch custom receipt
$custom_receipt = $this->db->order_by('id', 'desc')->get_where('custom_receipt_logs', array('student_fees_deposite_id' => $feeList->id, 'sub_invoice_id' => $sub_invoice_id))->row();
$receipt_no = $custom_receipt ? $custom_receipt->receipt_no : ($feeList->id . '/' . $sub_invoice_id);

$payment_mode = $fee_deposit_record->payment_mode ?? '';
$amount = $fee_deposit_record->amount ?? 0;
$amount_fine = $fee_deposit_record->amount_fine ?? 0;
$total_deposit = $amount + $amount_fine;

$copies = [
    'Office-Copy' => 'office-copy',
    'Candidate-Copy' => 'receiver-copy'
];

foreach ($copies as $copy_title => $copy_class) {
?>
<div class="receipt-copy <?php echo $copy_class; ?>">
    <!-- Background Watermark -->
    <img src="<?php echo base_url('uploads/school_content/admin_small_logo/' . $settinglist[0]['admin_small_logo']); ?>" class="watermark-bg" alt="Watermark">
    
    <div class="receipt-inner">
        <div class="header-top">
            <span>Reg No: <?php echo $settinglist[0]['dise_code']; ?></span>
            <span>Affiliation No.: <?php echo $settinglist[0]['dise_code']; /* Using dise_code as placeholder if no affiliation */ ?></span>
            <span>U Dise Code: <?php echo $settinglist[0]['dise_code']; ?></span>
        </div>

        <div class="receipt-header">
            <div class="logo-container">
                <img src="<?php echo base_url('uploads/school_content/logo/' . $settinglist[0]['image']); ?>" alt="Logo">
            </div>
            <div class="school-info">
                <h1 class="school-name"><?php echo $settinglist[0]['name']; ?></h1>
                <div class="school-address"><?php echo $settinglist[0]['address']; ?></div>
                <p class="school-contact">Email: <?php echo $settinglist[0]['email']; ?> Mobile No: <?php echo $settinglist[0]['phone']; ?> Website: <?php echo $settinglist[0]['phone']; /* placeholder for web */ ?></p>
                <div class="session-info">Session: <?php echo $student['session'] ?? ''; ?></div>
            </div>
            <div class="copy-label"><?php echo $copy_title; ?></div>
        </div>

        <div class="meta-grid">
            <div class="meta-col">
                <div class="meta-row"><span class="meta-label">SR No.</span><span class="value">: <?php echo $student['admission_no'] ?? ''; ?></span></div>
                <div class="meta-row"><span class="meta-label">Student Name</span><span class="value">: <?php echo strtoupper(($student['firstname'] ?? '') . ' ' . ($student['lastname'] ?? '')); ?></span></div>
                <div class="meta-row"><span class="meta-label">Father's Name</span><span class="value">: <?php echo strtoupper($student['father_name'] ?? ''); ?></span></div>
                <div class="meta-row"><span class="meta-label">Mother's Name</span><span class="value">: <?php echo strtoupper($student['mother_name'] ?? ''); ?></span></div>
                <div class="meta-row"><span class="meta-label">Class</span><span class="value">: <?php echo strtoupper(($student['class'] ?? '') . ' - ' . ($student['section'] ?? '')); ?></span></div>
            </div>
            <div class="meta-col right">
                <div class="fees-title">FEES RECEIPT</div>
                <div class="meta-row"><span class="meta-label">Receipt No :</span><span class="value"><?php echo $receipt_no; ?></span></div>
                <div class="meta-row"><span class="meta-label">Date :</span><span class="value"><?php echo date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($fee_deposit_record->date)); ?></span></div>
                <div class="meta-row"><span class="meta-label">User :</span><span class="value"><?php echo $fee_deposit_record->collected_by ?? 'Admin'; ?></span></div>
            </div>
        </div>

        <table class="receipt-table">
            <thead>
                <tr>
                    <th width="10%">S NO.</th>
                    <th width="70%">PARTICULAR</th>
                    <th width="20%">FEES DEPOSIT</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <?php
                    if(!isset($feeList->fee_category) || $feeList->fee_category == "fees"){
                        $particular = strtoupper(($feeList->is_system) ? $this->lang->line($feeList->type) : $feeList->type);
                    } elseif($feeList->fee_category == "transport") {
                        $particular = strtoupper("Bus Fee (" . $this->lang->line(strtolower($feeList->month)) . ")");
                    } elseif($feeList->fee_category == "transport_yearly") {
                        $particular = "BUS FEE";
                    } else {
                        $particular = strtoupper(isset($feeList->type) ? $feeList->type : 'FEE DEPOSIT');
                    }
                    ?>
                    <td><?php echo $particular; ?></td>
                    <td><?php echo $currency_symbol . amountFormat($total_deposit); ?></td>
                </tr>
                <?php for($i=2; $i<=5; $i++) { ?>
                <tr class="empty-row">
                    <td><?php echo $i; ?></td>
                    <td></td>
                    <td></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <div class="footer-info">
            <div class="qr-placeholder">
                <!-- Simple placeholder for QR Code, you can replace with actual QR generation logic if needed -->
                <?php
                $CI =& get_instance();
                $CI->load->library('QR_Code');
                $invoice_id = $feeList->id;
                $qr_url = base_url('welcome/download_receipt/' . $invoice_id . '/' . $sub_invoice_id . '/' . md5($invoice_id . $sub_invoice_id . 'receipt'));
                $qr_base64 = $CI->qr_code->generateBase64($qr_url);
                ?>
                <img src="<?php echo $qr_base64; ?>" alt="QR Code">
            </div>
            <div class="payment-mode-wrapper">
                <div class="payment-mode">Payment Mode: <?php echo strtoupper($payment_mode); ?></div>
                <?php if(!empty($fee_deposit_record->reference_no)): ?>
                <div style="font-size: 10px; margin-top: 2px; color: var(--text-muted);">Ref: <?php echo $fee_deposit_record->reference_no; ?></div>
                <?php endif; ?>
                <?php if(!empty($fee_deposit_record->description)): ?>
                <div style="font-size: 10px; margin-top: 2px; color: var(--text-muted);">Note: <?php echo $fee_deposit_record->description; ?></div>
                <?php endif; ?>
            </div>
            <div class="amount-words">Amount in Words: <?php echo convert_number_to_words($total_deposit); ?> Only</div>
            <div class="total-deposit">Total Deposit: <?php echo $currency_symbol . amountFormat($total_deposit); ?></div>
        </div>

        <div class="footer-bottom">
            <div class="notes">
                Note:- 1. Fee once deposited will not be refunded. 2. To avoid any inconvenience in future keep receipts safely.
            </div>
            <div class="signature-box">
                <div class="sig-space"></div>
                <div class="sig-label">AUTHORISED SIGNATURE</div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
