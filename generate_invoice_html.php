<?php
// Generate a professional invoice HTML file including:
// 1. Core ERP Licensing & local setup fees.
// 2. Custom Addons: Accounts Module (v3.0), CBSE Examination Addon (v4.0), and WhatsApp Messaging (v1.0).
// 3. External Developer Fees for custom setup, database verification, and initial integration.
// 4. Flat subtotal below 1 Lakh (Tax-free/No CGST and SGST).

$html = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - Sunrise International Public School</title>
    <style>
        body { font-family: "Segoe UI", Arial, sans-serif; color: #2d3748; margin: 40px; line-height: 1.5; background-color: #f7fafc; }
        .invoice-container { background-color: #ffffff; padding: 40px; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); max-width: 850px; margin: 0 auto; border: 1px solid #e2e8f0; }
        .header { border-bottom: 2px solid #3182ce; padding-bottom: 20px; margin-bottom: 30px; }
        .header-table { width: 100%; border-collapse: collapse; }
        .title { font-size: 28px; font-weight: 700; color: #2b6cb0; text-transform: uppercase; letter-spacing: 0.5px; }
        .invoice-id { text-align: right; font-size: 16px; font-weight: bold; color: #4a5568; }
        .meta-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .meta-table td { width: 50%; vertical-align: top; padding: 0 10px 0 0; }
        .section-title { font-size: 12px; font-weight: 700; text-transform: uppercase; color: #718096; margin-bottom: 8px; border-bottom: 1px solid #edf2f7; padding-bottom: 4px; }
        .meta-content { font-size: 14px; color: #4a5568; }
        .meta-content strong { color: #1a202c; }
        .dates-table { width: 100%; border-collapse: collapse; margin-bottom: 25px; font-size: 14px; background-color: #f7fafc; border: 1px solid #edf2f7; border-radius: 4px; }
        .dates-table td { padding: 10px 15px; }
        .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .items-table th { background-color: #3182ce; color: #ffffff; padding: 10px 12px; font-size: 13px; text-transform: uppercase; font-weight: 600; text-align: left; }
        .items-table td { padding: 12px; border-bottom: 1px solid #e2e8f0; font-size: 13px; vertical-align: top; color: #4a5568; }
        .items-table td strong { color: #2d3748; }
        .items-table td .description { font-size: 11px; color: #718096; margin-top: 4px; display: block; line-height: 1.4; }
        .total-container { display: flex; justify-content: flex-end; margin-top: 20px; }
        .total-table { width: 350px; border-collapse: collapse; }
        .total-table td { padding: 8px 12px; font-size: 14px; }
        .total-row td { font-size: 18px; font-weight: bold; color: #2b6cb0; border-top: 2px solid #3182ce; padding-top: 12px; }
        .footer { margin-top: 60px; border-top: 1px solid #e2e8f0; padding-top: 20px; font-size: 11px; color: #718096; text-align: center; line-height: 1.6; }
        .bank-details { background-color: #ebf8ff; border: 1px dashed #bee3f8; border-radius: 4px; padding: 10px 15px; margin-top: 15px; font-size: 12px; color: #2b6cb0; text-align: left; }
        @media print {
            body { background-color: #ffffff; margin: 0; }
            .invoice-container { box-shadow: none; border: none; padding: 0; }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="header">
            <table class="header-table">
                <tr>
                    <td class="title">Invoice</td>
                    <td class="invoice-id">INV/2026/07/351</td>
                </tr>
            </table>
        </div>

        <table class="meta-table">
            <tr>
                <td>
                    <div class="section-title">Supplier / Developer</div>
                    <div class="meta-content">
                        <strong>ERP Solutions India Pvt. Ltd.</strong><br>
                        Tech Park, Sector 62, Noida<br>
                        Uttar Pradesh - 201301<br>
                        Email: billing@erpsolutions.in
                    </div>
                </td>
                <td>
                    <div class="section-title">Invoice To</div>
                    <div class="meta-content">
                        <strong>Sunrise International Public School</strong><br>
                        Sikar Salasar, Main Rd, Nechhwa,<br>
                        Rajasthan - 332026<br>
                        <strong>DISE Code:</strong> 08130200815
                    </div>
                </td>
            </tr>
        </table>

        <table class="dates-table">
            <tr>
                <td><strong>Invoice Date:</strong> 29-07-2026</td>
                <td><strong>Due Date:</strong> 10-08-2026</td>
                <td style="text-align: right;"><strong>Payment Method:</strong> NEFT / Bank Transfer</td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 65%;">Service / License Description</th>
                    <th style="width: 15%; text-align: center;">Qty</th>
                    <th style="width: 20%; text-align: right;">Amount (INR)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>Flat Annual School ERP Core License</strong>
                        <span class="description">Software usage support license fee for up to 2,000 students. Includes Admissions, Academic Fee Module, Exam scheduler, student timeline, and basic HR/Payroll modules.</span>
                    </td>
                    <td style="text-align: center;">1</td>
                    <td style="text-align: right;">₹ 45,000.00</td>
                </tr>
                <tr>
                    <td>
                        <strong>Premium Module Addon Bundles</strong>
                        <span class="description">Includes: Double-Entry Accounts Addon (v3.0) with dynamic ledger tracking; CBSE Examination Suite (v4.0) with marksheets generator; and WhatsApp Messaging Integration (v1.0).</span>
                    </td>
                    <td style="text-align: center;">1 Group</td>
                    <td style="text-align: right;">₹ 25,000.00</td>
                </tr>
                <tr>
                    <td>
                        <strong>External Developer Services</strong>
                        <span class="description">Configuration services for WAMP database connectivity, data import pipelines for students/staff, database verification, and system settings integration.</span>
                    </td>
                    <td style="text-align: center;">1</td>
                    <td style="text-align: right;">₹ 18,000.00</td>
                </tr>
            </tbody>
        </table>

        <div class="total-container">
            <table class="total-table">
                <tr>
                    <td style="text-align: left; font-weight: 500; color: #4a5568;">Total Subtotal:</td>
                    <td style="text-align: right; font-weight: 500; color: #2d3748;">₹ 88,000.00</td>
                </tr>
                <tr class="total-row">
                    <td style="text-align: left;">Total Due (Net):</td>
                    <td style="text-align: right;">₹ 88,000.00</td>
                </tr>
            </table>
        </div>

        <div class="bank-details">
            <strong>Bank Account Details for Payment:</strong><br>
            ERP Solutions India Pvt. Ltd. | Bank: ICICI Bank | A/c No: 002105001294 | IFSC: ICIC0000021
        </div>

        <div class="footer">
            Thank you for choosing ERP Solutions. For any invoice queries, write to support@erpsolutions.in.<br>
            <em>This invoice has been generated electronically and does not require a physical signature.</em>
        </div>
    </div>
</body>
</html>
';

$pdf_file = 'C:\Users\mahendra.singh\.gemini\antigravity-ide\brain\e50f2b28-8318-4992-9d6f-52a097931eb2\invoice_sunrise_school.html';
file_put_contents($pdf_file, $html);
echo "INVOICE_GENERATED_AT: " . $pdf_file . "\n";
?>
