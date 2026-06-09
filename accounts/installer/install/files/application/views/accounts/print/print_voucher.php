<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title><?php echo $title . ' - ' . ($voucher_type == 'purchase' ? $purchase['invoice_no'] : $voucher['voucher_no']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;700&family=Great+Vibes&family=Caveat:wght@600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            color: #1f2937;
            margin: 0;
            padding: 20px;
            font-size: 13px;
            line-height: 1.5;
            background: #fff;
        }
        .print-wrap {
            max-width: 800px;
            margin: 0 auto;
        }
        .header-image-box {
            text-align: center;
            margin-bottom: 25px;
        }
        .header-image-box img {
            max-width: 100%;
            height: auto;
        }
        .voucher-title-box {
            border-bottom: 2px solid #1e3a8a;
            padding-bottom: 8px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .voucher-title {
            font-size: 22px;
            font-weight: 800;
            color: #1e3a8a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0;
        }
        .school-name {
            font-size: 14px;
            font-weight: 700;
            color: #4b5563;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 25px;
            border-collapse: collapse;
        }
        .meta-table td {
            padding: 6px 0;
            vertical-align: top;
        }
        .meta-label {
            font-weight: 700;
            color: #4b5563;
            width: 120px;
        }
        .meta-value {
            color: #111827;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background: #f3f4f6;
            color: #374151;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
        }
        .items-table td {
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
            vertical-align: middle;
        }
        .amount-col {
            font-family: 'JetBrains Mono', monospace;
            font-weight: 600;
            text-align: right;
            font-size: 13px;
        }
        .total-row {
            background: #f9fafb;
            font-weight: 700;
        }
        .total-row td {
            border-top: 2px solid #1e3a8a;
            border-bottom: 2px solid #1e3a8a;
        }
        .narration-box {
            background: #f9fafb;
            border-left: 4px solid #3b82f6;
            padding: 12px 16px;
            margin-bottom: 40px;
            border-radius: 0 8px 8px 0;
        }
        .narration-title {
            font-weight: 700;
            color: #4b5563;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        .narration-text {
            color: #1f2937;
            margin: 0;
        }
        .signatures-box {
            display: flex;
            justify-content: space-between;
            margin-top: 60px;
            margin-bottom: 40px;
            padding-top: 10px;
        }
        .sig-spot {
            text-align: center;
            width: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-end;
            min-height: 90px;
        }
        .sig-image-container {
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 5px;
            width: 100%;
        }
        .sig-image-container img {
            max-height: 48px;
            max-width: 100%;
            object-fit: contain;
        }
        .sig-handwritten {
            font-family: 'Great Vibes', cursive;
            font-size: 26px;
            color: #1e3a8a;
            height: 50px;
            line-height: 50px;
            margin-bottom: 5px;
            width: 100%;
            text-align: center;
            transform: rotate(-3deg);
            user-select: none;
            letter-spacing: 1px;
        }
        .sig-line {
            border-top: 1px dashed #9ca3af;
            margin-bottom: 6px;
        }
        .sig-label {
            font-weight: 700;
            color: #4b5563;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .footer-content-box {
            border-top: 1px solid #e5e7eb;
            padding-top: 15px;
            margin-top: 40px;
            color: #6b7280;
            font-size: 11px;
            text-align: center;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="print-wrap">
        
        <!-- Header from print settings -->
        <?php if (!empty($print_header)): ?>
            <div class="header-image-box">
                <img src="<?php echo base_url('uploads/print_headerfooter/general_purpose/' . $print_header); ?>" alt="Header">
            </div>
        <?php endif; ?>

        <!-- Voucher Header -->
        <div class="voucher-title-box">
            <h1 class="voucher-title"><?php echo $title; ?></h1>
            <span class="school-name"><?php echo htmlspecialchars($school_setting->name); ?></span>
        </div>

        <!-- Meta Table -->
        <table class="meta-table">
            <tr>
                <td style="width: 50%;">
                    <table style="width: 100%;">
                        <tr>
                            <td class="meta-label">Voucher No:</td>
                            <td class="meta-value"><strong><?php echo $voucher_type == 'purchase' ? htmlspecialchars($purchase['invoice_no']) : htmlspecialchars($voucher['voucher_no']); ?></strong></td>
                        </tr>
                        <tr>
                            <td class="meta-label">Voucher Date:</td>
                            <td class="meta-value"><?php echo date($school_setting->date_format, strtotime($voucher_type == 'purchase' ? $purchase['purchase_date'] : $voucher['voucher_date'])); ?></td>
                        </tr>
                    </table>
                </td>
                <td style="width: 50%;">
                    <table style="width: 100%;">
                        <?php if ($voucher_type == 'purchase'): ?>
                            <tr>
                                <td class="meta-label">Supplier Name:</td>
                                <td class="meta-value"><?php echo htmlspecialchars($supplier_name); ?></td>
                            </tr>
                        <?php else: ?>
                            <?php if (!empty($voucher['reference_id'])): ?>
                                <tr>
                                    <td class="meta-label">Reference No:</td>
                                    <td class="meta-value"><?php echo htmlspecialchars($voucher['reference_id']); ?></td>
                                </tr>
                            <?php endif; ?>
                            <?php if (!empty($voucher['payment_method'])): ?>
                                <tr>
                                    <td class="meta-label">Payment Mode:</td>
                                    <td class="meta-value"><?php echo htmlspecialchars($voucher['payment_method']); ?></td>
                                </tr>
                            <?php endif; ?>
                        <?php endif; ?>
                        <tr>
                            <td class="meta-label">Session ID:</td>
                            <td class="meta-value"><?php echo htmlspecialchars($school_setting->session); ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <?php if ($voucher_type == 'purchase'): ?>
                    <tr>
                        <th>Item Description</th>
                        <th>Expense Category</th>
                        <th style="text-align: center; width: 60px;">Qty</th>
                        <th style="text-align: right; width: 100px;">Rate</th>
                        <th style="text-align: right; width: 120px;">Amount</th>
                    </tr>
                <?php else: ?>
                    <tr>
                        <th>Account Name (Ledger)</th>
                        <th>Description / Category</th>
                        <th style="text-align: right; width: 130px;">Debit Amount</th>
                        <th style="text-align: right; width: 130px;">Credit Amount</th>
                    </tr>
                <?php endif; ?>
            </thead>
            <tbody>
                <?php if ($voucher_type == 'purchase'): ?>
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
                        <td class="amount-col" style="color: #166534; font-weight: 800; font-size: 15px; border-bottom: 2px double #166534;"><?php echo $currency_symbol . number_format($purchase['net_amount'], 2); ?></td>
                    </tr>
                <?php else: ?>
                    <?php 
                    $total_debit = 0;
                    $total_credit = 0;
                    foreach ($voucher['items'] as $item): 
                        $total_debit += $item['debit_amount'];
                        $total_credit += $item['credit_amount'];
                    ?>
                        <tr>
                            <td><strong><?php echo htmlspecialchars($item['ledger_name']); ?></strong></td>
                            <td><?php echo htmlspecialchars($item['expense_type_name'] ?: '—'); ?></td>
                            <td class="amount-col"><?php echo $item['debit_amount'] > 0 ? $currency_symbol . number_format($item['debit_amount'], 2) : '—'; ?></td>
                            <td class="amount-col"><?php echo $item['credit_amount'] > 0 ? $currency_symbol . number_format($item['credit_amount'], 2) : '—'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <tr class="total-row">
                        <td colspan="2" style="text-align: right;">Total Voucher Amount:</td>
                        <td class="amount-col"><?php echo $currency_symbol . number_format($total_debit, 2); ?></td>
                        <td class="amount-col"><?php echo $currency_symbol . number_format($total_credit, 2); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Narration Box -->
        <?php 
        $narration = $voucher_type == 'purchase' ? $purchase['narration'] : $voucher['narration'];
        if (!empty($narration)): 
        ?>
            <div class="narration-box">
                <div class="narration-title">Remarks / Narration:</div>
                <p class="narration-text"><?php echo htmlspecialchars($narration); ?></p>
            </div>
        <?php endif; ?>

        <!-- Signatures spot -->
        <div class="signatures-box">
            <!-- Signature 1 -->
            <div class="sig-spot">
                <div class="sig-image-container">
                    <?php if (!empty($settings['signature_1_photo']) && file_exists('./uploads/accounts/signatures/' . $settings['signature_1_photo'])): ?>
                        <img src="<?php echo base_url('uploads/accounts/signatures/' . $settings['signature_1_photo']); ?>" alt="Signature 1">
                    <?php elseif (!empty($settings['signature_1_name'])): ?>
                        <div class="sig-handwritten"><?php echo htmlspecialchars($settings['signature_1_name']); ?></div>
                    <?php endif; ?>
                </div>
                <div class="sig-line" style="width: 100%;"></div>
                <div class="sig-label"><?php echo htmlspecialchars($settings['signature_1_label'] ?? 'Prepared By'); ?></div>
                <?php if (!empty($settings['signature_1_name'])): ?>
                    <div style="font-size: 10px; color: #6b7280; font-weight: 600; margin-top: 2px;">(<?php echo htmlspecialchars($settings['signature_1_name']); ?>)</div>
                <?php endif; ?>
            </div>

            <!-- Signature 2 -->
            <div class="sig-spot">
                <div class="sig-image-container">
                    <?php if (!empty($settings['signature_2_photo']) && file_exists('./uploads/accounts/signatures/' . $settings['signature_2_photo'])): ?>
                        <img src="<?php echo base_url('uploads/accounts/signatures/' . $settings['signature_2_photo']); ?>" alt="Signature 2">
                    <?php elseif (!empty($settings['signature_2_name'])): ?>
                        <div class="sig-handwritten"><?php echo htmlspecialchars($settings['signature_2_name']); ?></div>
                    <?php endif; ?>
                </div>
                <div class="sig-line" style="width: 100%;"></div>
                <div class="sig-label"><?php echo htmlspecialchars($settings['signature_2_label'] ?? 'Checked By'); ?></div>
                <?php if (!empty($settings['signature_2_name'])): ?>
                    <div style="font-size: 10px; color: #6b7280; font-weight: 600; margin-top: 2px;">(<?php echo htmlspecialchars($settings['signature_2_name']); ?>)</div>
                <?php endif; ?>
            </div>

            <!-- Signature 3 -->
            <div class="sig-spot">
                <div class="sig-image-container">
                    <?php if (!empty($settings['signature_3_photo']) && file_exists('./uploads/accounts/signatures/' . $settings['signature_3_photo'])): ?>
                        <img src="<?php echo base_url('uploads/accounts/signatures/' . $settings['signature_3_photo']); ?>" alt="Signature 3">
                    <?php elseif (!empty($settings['signature_3_name'])): ?>
                        <div class="sig-handwritten"><?php echo htmlspecialchars($settings['signature_3_name']); ?></div>
                    <?php endif; ?>
                </div>
                <div class="sig-line" style="width: 100%;"></div>
                <div class="sig-label"><?php echo htmlspecialchars($settings['signature_3_label'] ?? 'Authorized Signatory'); ?></div>
                <?php if (!empty($settings['signature_3_name'])): ?>
                    <div style="font-size: 10px; color: #6b7280; font-weight: 600; margin-top: 2px;">(<?php echo htmlspecialchars($settings['signature_3_name']); ?>)</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Footer from print settings -->
        <?php if (!empty($print_footer)): ?>
            <div class="footer-content-box">
                <?php echo $print_footer; ?>
            </div>
        <?php endif; ?>

    </div>

    <!-- Auto print triggered -->
    <script type="text/javascript">
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
