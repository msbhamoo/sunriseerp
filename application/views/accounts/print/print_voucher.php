<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); 

if (!function_exists('get_amount_in_words')) {
    function get_amount_in_words($number) {
        $no = floor($number);
        $point = round($number - $no, 2) * 100;
        $hundred = null;
        $digits_1 = strlen($no);
        $i = 0;
        $str = array();
        $words = array('0' => '', '1' => 'One', '2' => 'Two',
        '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
        '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
        '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
        '13' => 'Thirteen', '14' => 'Fourteen',
        '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
        '18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
        '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
        '60' => 'Sixty', '70' => 'Seventy',
        '80' => 'Eighty', '90' => 'Ninety');
        $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
        while ($i < $digits_1) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += ($divider == 10) ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? '' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number] .
                    " " . $digits[$counter] . $plural . " " . $hundred
                    :
                    $words[floor($number / 10) * 10]
                    . " " . $words[$number % 10] . " "
                    . $digits[$counter] . $plural . " " . $hundred;
            } else $str[] = null;
        }
        $str = array_reverse($str);
        $result = implode('', $str);
        $points = ($point) ?
        " and " . $words[$point / 10] . " " . 
              $words[$point = $point % 10] . " Paise" : '';
        $final_str = $result . $points;
        if(empty(trim($final_str))) return "Zero Only";
        return $final_str . " Only";
    }
}

$total_amount = 0;
if ($voucher_type == 'purchase') {
    $total_amount = $purchase['net_amount'];
} else {
    foreach ($voucher['items'] as $item) {
        $total_amount += $item['debit_amount'];
    }
}
$amount_in_words = get_amount_in_words($total_amount);

ob_start();
?>
<div class="voucher-container" id="{{COPY_ID}}">
    <!-- Header from print settings -->
    <?php if (!empty($print_header)): ?>
        <div class="header-image-box">
            <img src="<?php echo base_url('uploads/print_headerfooter/general_purpose/' . $print_header); ?>" alt="Header">
        </div>
    <?php endif; ?>

    <div class="voucher-title-box">
        <h1 class="voucher-title"><?php echo $title; ?> <span class="copy-text">{{COPY_TYPE}}</span></h1>
        <span class="school-name" style="font-size: 14px;">Voucher No: #<?php echo $voucher_type == 'purchase' ? htmlspecialchars($purchase['invoice_no']) : htmlspecialchars($voucher['voucher_no']); ?></span>
    </div>

    <!-- Meta Information -->
    <div class="meta-section">
        <div class="meta-col">
            <div class="meta-row">
                <span class="meta-label">Voucher No:</span>
                <span class="meta-value" style="font-weight: 800; color: var(--primary-color);">#<?php echo $voucher_type == 'purchase' ? htmlspecialchars($purchase['invoice_no']) : htmlspecialchars($voucher['voucher_no']); ?></span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Voucher Date:</span>
                <span class="meta-value"><?php echo date($school_setting->date_format, strtotime($voucher_type == 'purchase' ? $purchase['purchase_date'] : $voucher['voucher_date'])); ?></span>
            </div>
        </div>
        <div class="meta-col">
            <?php if ($voucher_type == 'purchase'): ?>
                <div class="meta-row">
                    <span class="meta-label">Supplier Name:</span>
                    <span class="meta-value font-bold"><?php echo htmlspecialchars($supplier_name); ?></span>
                </div>
            <?php else: ?>
                <?php if (!empty($voucher['reference_id'])): ?>
                    <div class="meta-row">
                        <span class="meta-label">Reference No:</span>
                        <span class="meta-value"><?php echo htmlspecialchars($voucher['reference_id']); ?></span>
                    </div>
                <?php endif; ?>
                <?php if (!empty($voucher['payment_method'])): ?>
                    <div class="meta-row">
                        <span class="meta-label">Payment Mode:</span>
                        <span class="meta-value"><?php echo htmlspecialchars($voucher['payment_method']); ?></span>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
            <div class="meta-row">
                <span class="meta-label">Session ID:</span>
                <span class="meta-value"><?php echo htmlspecialchars($school_setting->session); ?></span>
            </div>
        </div>
    </div>

    <?php if ($voucher_type == 'purchase'): ?>
        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="text-align: left;">Item Description</th>
                    <th style="text-align: left;">Expense Category</th>
                    <th style="text-align: center; width: 60px;">Qty</th>
                    <th style="text-align: right; width: 100px;">Rate</th>
                    <th style="text-align: right; width: 120px;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($purchase['items'] as $item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['item_description']); ?></td>
                        <td><?php echo htmlspecialchars($item['expense_type_name'] ?: '—'); ?></td>
                        <td style="text-align: center; font-family: 'JetBrains Mono', monospace;"><?php echo number_format($item['qty'], 2); ?></td>
                        <td class="amount-col"><?php echo $currency_symbol . number_format($item['rate'], 2); ?></td>
                        <td class="amount-col"><?php echo $currency_symbol . number_format($item['amount'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr class="total-row">
                    <td colspan="4" style="text-align: right;">Total Amount:</td>
                    <td class="amount-col"><?php echo $currency_symbol . number_format($purchase['total_amount'], 2); ?></td>
                </tr>
                <?php if (floatval($purchase['discount']) > 0): ?>
                    <tr>
                        <td colspan="4" style="text-align: right; font-weight: 600;">Discount:</td>
                        <td class="amount-col" style="color: #dc2626;">- <?php echo $currency_symbol . number_format($purchase['discount'], 2); ?></td>
                    </tr>
                <?php endif; ?>
                <?php if (floatval($purchase['gst_amount']) > 0): ?>
                    <tr>
                        <td colspan="4" style="text-align: right; font-weight: 600;">GST Amount:</td>
                        <td class="amount-col" style="color: #2563eb;">+ <?php echo $currency_symbol . number_format($purchase['gst_amount'], 2); ?></td>
                    </tr>
                <?php endif; ?>
                <tr class="total-row" style="background: #f0fdf4;">
                    <td colspan="4" style="text-align: right; color: #166534; font-weight: 800;">Net Amount Paid:</td>
                    <td class="amount-col" style="color: #166534; font-weight: 800; font-size: 14px; border-bottom: 2px double #166534;"><?php echo $currency_symbol . number_format($purchase['net_amount'], 2); ?></td>
                </tr>
            </tbody>
        </table>
    <?php else: ?>
        <?php 
        $total_debit = 0;
        $total_credit = 0;
        $debits = [];
        $credits = [];
        $debit_cats = [];
        $credit_cats = [];
        
        foreach ($voucher['items'] as $item) {
            $total_debit += $item['debit_amount'];
            $total_credit += $item['credit_amount'];
            
            if ($item['debit_amount'] > 0) {
                $debits[] = $item['ledger_name'];
                if ($item['expense_type_name']) $debit_cats[] = $item['expense_type_name'];
            }
            if ($item['credit_amount'] > 0) {
                $credits[] = $item['ledger_name'];
                if ($item['expense_type_name']) $credit_cats[] = $item['expense_type_name'];
            }
        }
        
        $debit_ledgers = implode(', ', array_filter(array_unique($debits)));
        $credit_ledgers = implode(', ', array_filter(array_unique($credits)));
        $debit_categories = implode(', ', array_filter(array_unique($debit_cats)));
        $credit_categories = implode(', ', array_filter(array_unique($credit_cats)));
        
        $is_payment = stripos($title, 'payment') !== false;
        $is_receipt = stripos($title, 'receipt') !== false;
        ?>
        <table class="ledger-summary-table">
            <?php if ($is_payment): ?>
                <tr>
                    <td class="ledger-label">Against Pay:</td>
                    <td class="ledger-value"><?php echo htmlspecialchars($debit_categories ?: $debit_ledgers); ?></td>
                </tr>
                <tr>
                    <td class="ledger-label">Payment To Mr./M/s:</td>
                    <td class="ledger-value"><?php echo htmlspecialchars($debit_ledgers); ?></td>
                </tr>
            <?php elseif ($is_receipt): ?>
                <tr>
                    <td class="ledger-label">Received From:</td>
                    <td class="ledger-value"><?php echo htmlspecialchars($credit_ledgers); ?></td>
                </tr>
                <tr>
                    <td class="ledger-label">Towards:</td>
                    <td class="ledger-value"><?php echo htmlspecialchars($credit_categories ?: $credit_ledgers); ?></td>
                </tr>
            <?php else: ?>
                <tr>
                    <td class="ledger-label">From (Giver):</td>
                    <td class="ledger-value"><?php echo htmlspecialchars($credit_ledgers . ($credit_categories ? ' (' . $credit_categories . ')' : '')); ?></td>
                </tr>
                <tr>
                    <td class="ledger-label">To (Receiver):</td>
                    <td class="ledger-value"><?php echo htmlspecialchars($debit_ledgers . ($debit_categories ? ' (' . $debit_categories . ')' : '')); ?></td>
                </tr>
            <?php endif; ?>
            <tr>
                <td class="ledger-label">Total Amount:</td>
                <td class="ledger-value" style="font-family: 'JetBrains Mono', monospace; font-size: 13px; color: #166534; padding-top: 6px;"><?php echo $currency_symbol . number_format($total_debit, 2); ?></td>
            </tr>
        </table>
    <?php endif; ?>

    <div class="amount-words">
        <span class="label">Amount in words:</span> 
        <span class="value"><?php echo $currency_symbol . " " . ucwords(strtolower($amount_in_words)); ?></span>
    </div>

    <!-- Narration Box -->
    <?php 
    $narration = $voucher_type == 'purchase' ? $purchase['narration'] : $voucher['narration'];
    if (!empty($narration)): 
    ?>
        <div class="narration-box">
            <span class="narration-title">Remarks / Narration:</span>
            <span class="narration-text"><?php echo htmlspecialchars($narration); ?></span>
        </div>
    <?php endif; ?>

    <div class="flex-grow"></div>

    <!-- Signatures spot -->
    <div class="signatures-box">
        <div class="sig-spot" style="width: 200px;">
            <div class="sig-image-container"></div>
            <div class="sig-label" style="text-transform: none; font-size: 11px;">Receiver's Signature & Stamp</div>
        </div>

        <div class="sig-spot" style="width: 200px;">
            <div class="sig-image-container">
                <?php if (!empty($settings['signature_3_photo']) && file_exists('./uploads/accounts/signatures/' . $settings['signature_3_photo'])): ?>
                    <img src="<?php echo base_url('uploads/accounts/signatures/' . $settings['signature_3_photo']); ?>" alt="Authorized Signatory">
                <?php elseif (!empty($settings['signature_3_name'])): ?>
                    <div class="sig-handwritten"><?php echo htmlspecialchars($settings['signature_3_name']); ?></div>
                <?php endif; ?>
            </div>
            <div class="sig-label" style="text-transform: none; font-size: 11px;">Authorized Signatory</div>
        </div>
    </div>

    <!-- Footer from print settings -->
    <?php if (!empty($print_footer)): ?>
        <div class="footer-content-box">
            <?php echo $print_footer; ?>
        </div>
    <?php endif; ?>
</div>
<?php 
$voucher_html = ob_get_clean(); 
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $title . ' - ' . ($voucher_type == 'purchase' ? $purchase['invoice_no'] : $voucher['voucher_no']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&family=Great+Vibes&display=swap" rel="stylesheet">
    <style>
        <?php
        $theme_color = '#2eab66'; // Default smart school green
        $theme_settings = $this->customlib->getCurrentThemeSetting();
        if (isset($theme_settings->theme_color) && !empty($theme_settings->theme_color)) {
            $theme_color = $theme_settings->theme_color;
        } else {
            $theme = $this->customlib->getCurrentTheme();
            if ($theme == 'red') { $theme_color = '#e74c3c'; } 
            elseif ($theme == 'blue') { $theme_color = '#1e3a8a'; } 
            elseif ($theme == 'gray') { $theme_color = '#737373'; } 
            elseif ($theme == 'white') { $theme_color = '#222222'; }
            elseif ($theme == 'default') { $theme_color = '#2eab66'; }
        }
        ?>
        :root {
            --primary-color: <?php echo $theme_color; ?>;
        }
        body {
            font-family: 'Inter', sans-serif;
            color: #1f2937;
            margin: 0;
            padding: 0;
            font-size: 11px;
            line-height: 1.4;
            background: #fff;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .print-wrap {
            max-width: 800px;
            margin: 0 auto;
        }
        .voucher-container {
            height: 133mm; /* Adjusted to allow a taller full-width header */
            padding: 5px 20px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            page-break-inside: avoid;
        }
        .header-image-box {
            text-align: center;
            margin-bottom: 8px;
            width: 100%;
        }
        .header-image-box img {
            width: 100%;
            height: auto;
            max-height: 110px; /* Increased to allow natural 100% width scaling */
            object-fit: contain;
        }
        .voucher-title-box {
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 5px;
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .voucher-title {
            font-size: 14px;
            font-weight: 800;
            color: var(--primary-color);
            text-transform: capitalize;
            letter-spacing: 0.5px;
            margin: 0;
            display: flex;
            align-items: center;
        }
        .copy-text {
            font-size: 10px;
            color: #6b7280;
            font-weight: 700;
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 4px;
            margin-left: 10px;
            text-transform: none;
            letter-spacing: 0;
        }
        .school-name {
            font-size: 12px;
            font-weight: 700;
            color: #4b5563;
        }
        .meta-section {
            display: flex;
            justify-content: space-between;
            background: #f9fafb;
            padding: 6px 10px;
            border-radius: 6px;
            margin-bottom: 8px;
            border: 1px solid #f3f4f6;
        }
        .meta-col {
            width: 48%;
        }
        .meta-col:last-child .meta-row {
            justify-content: flex-end;
        }
        .meta-col:last-child .meta-label {
            width: auto;
            margin-right: 15px;
        }
        .meta-row {
            display: flex;
            margin-bottom: 4px;
        }
        .meta-row:last-child {
            margin-bottom: 0;
        }
        .meta-label {
            font-weight: 600;
            color: #6b7280;
            width: 110px;
            flex-shrink: 0;
        }
        .meta-value {
            color: #111827;
            font-weight: 500;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .items-table th {
            background: #f3f4f6;
            color: #374151;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
            padding: 6px 8px;
            border: 1px solid #e5e7eb;
        }
        .items-table td {
            padding: 6px 8px;
            border: 1px solid #e5e7eb;
            vertical-align: middle;
        }
        .amount-col {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 600;
            text-align: right;
            font-size: 11px;
        }
        .total-row {
            background: #f9fafb;
            font-weight: 700;
        }
        .amount-words {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 6px 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        .amount-words .label {
            color: #15803d;
            margin-right: 5px;
        }
        .narration-box {
            background: #fff;
            border-left: 3px solid #3b82f6;
            padding: 6px 12px;
            margin-bottom: 10px;
            font-size: 11px;
            background: #eff6ff;
        }
        .narration-title {
            font-weight: 700;
            color: var(--primary-color);
            margin-right: 5px;
        }
        .narration-text {
            color: #111827;
        }
        .ledger-summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            font-size: 13px;
        }
        .ledger-summary-table td {
            padding: 6px 0;
        }
        .ledger-label {
            font-weight: 600;
            color: #4b5563;
            width: 200px;
        }
        .ledger-value {
            color: #111827;
            font-weight: 700;
        }
        .flex-grow {
            height: 5px; /* Greatly reduced space */
        }
        .signatures-box {
            display: flex;
            justify-content: space-between;
            margin-top: 5px; /* Reduced from 20px */
            padding-top: 5px;
        }
        .sig-spot {
            text-align: center;
            width: 150px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
        }
        .sig-image-container {
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 4px;
            width: 100%;
        }
        .sig-image-container img {
            max-height: 38px;
            max-width: 100%;
            object-fit: contain;
        }
        .sig-handwritten {
            font-family: 'Great Vibes', cursive;
            font-size: 20px;
            color: var(--primary-color);
            height: 40px;
            line-height: 40px;
            width: 100%;
            text-align: center;
            transform: rotate(-3deg);
        }
        .sig-line {
            border-top: 1px dashed #9ca3af;
            width: 100%;
            margin-bottom: 4px;
        }
        .sig-label {
            font-weight: 700;
            color: #4b5563;
            font-size: 10px;
            text-transform: uppercase;
        }
        .sig-name {
            font-size: 9px;
            color: #6b7280;
            font-weight: 600;
            margin-top: 2px;
        }
        .footer-content-box {
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
            margin-top: 15px;
            color: #9ca3af;
            font-size: 10px;
            text-align: center;
        }
        .cut-line-wrapper {
            position: relative;
            text-align: center;
            margin: 10px 0;
            display: flex;
            align-items: center;
        }
        .cut-line {
            width: 100%;
            border-top: 1px dashed #6b7280;
        }
        .scissor {
            position: absolute;
            left: 30px;
            background: #fff;
            padding: 0 10px;
            color: #4b5563;
            font-size: 16px;
            line-height: 1;
        }
        @media print {
            @page {
                size: A4;
                margin: 0mm;
            }
            html, body {
                margin: 0 !important;
                padding: 0 !important;
                background: #fff;
                height: 100%;
            }
            .print-wrap {
                height: 296mm; /* Strict A4 height */
                max-height: 296mm;
                overflow: hidden; /* Forcibly prevent a second page */
                display: flex;
                flex-direction: column;
            }
            .voucher-container {
                flex: 1;
                height: auto;
                max-height: 145mm;
            }
            .cut-line-wrapper {
                print-color-adjust: exact;
                margin: 0;
                padding: 5px 0;
            }
            .no-print {
                display: none !important;
            }
        }
        .print-controls {
            text-align: center;
            padding: 15px;
            background: #f3f4f6;
            margin-bottom: 20px;
            border-bottom: 1px solid #e5e7eb;
        }
        .print-controls button {
            padding: 8px 16px;
            margin: 0 5px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            font-size: 12px;
            transition: opacity 0.2s;
        }
        .print-controls button:hover {
            opacity: 0.9;
        }
        .no-print {
            /* visible on screen but hidden in print */
        }
    </style>
</head>
<body>
    <div class="print-controls no-print">
        <button onclick="printVoucher('both')">🖨️ Print Both Copies</button>
        <button onclick="printVoucher('office')">🖨️ Print Office Copy Only</button>
        <button onclick="printVoucher('receiver')">🖨️ Print Receiver Copy Only</button>
    </div>

    <div class="print-wrap">
        
        <?php 
        $office_copy = str_replace('{{COPY_TYPE}}', 'Office Copy', $voucher_html); 
        echo str_replace('{{COPY_ID}}', 'copy-office', $office_copy);
        ?>
        
        <div class="cut-line-wrapper" id="cut-line">
            <div class="cut-line"></div>
            <span class="scissor">✂</span>
        </div>

        <?php 
        $receiver_copy = str_replace('{{COPY_TYPE}}', 'Receiver Copy', $voucher_html); 
        echo str_replace('{{COPY_ID}}', 'copy-receiver', $receiver_copy);
        ?>

    </div>

    <!-- Auto print triggered -->
    <script type="text/javascript">
        function printVoucher(type) {
            document.getElementById('copy-office').style.display = (type === 'both' || type === 'office') ? 'flex' : 'none';
            document.getElementById('copy-receiver').style.display = (type === 'both' || type === 'receiver') ? 'flex' : 'none';
            document.getElementById('cut-line').style.display = (type === 'both') ? 'flex' : 'none';
            window.print();
        }

        window.onload = function() {
            // Auto-print Office Copy by default on page load
            setTimeout(function() { printVoucher('office'); }, 500);
        }

        // Signal to the parent tab to refresh the list after printing is done
        // afterprint fires when the user closes/completes the print dialog
        window.addEventListener('afterprint', function() {
            try { localStorage.setItem('pv_print_done', '1'); } catch(e) {}
        });
        // pagehide fires when the tab is closed or navigated away
        window.addEventListener('pagehide', function() {
            try { localStorage.setItem('pv_print_done', '1'); } catch(e) {}
        });

    </script>
</body>
</html>
