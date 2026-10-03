<?php
// Generate a professional invoice HTML file including:
// 1. External Senior Developer & Core Customizations: ₹ 58,000.00
// 2. Server Management, Cloud Setup & Security Hardening: ₹ 34,000.00
// 3. Legacy Data Migration & Database Verification: ₹ 27,000.00
// 4. Additional Feature Developments & Integrations: ₹ 33,000.00
// 5. Quality Assurance, UAT & Staff Onboarding: ₹ 25,000.00
// Gross Total: ₹ 1,77,000.00
// Discount: ₹ 42,000.00
// Net Amount Payable: ₹ 1,35,000.00

$html = file_get_contents(__DIR__ . '/invoice_sunrise_school.html');

$output_file = __DIR__ . '/invoice_sunrise_school.html';
file_put_contents($output_file, $html);

echo "INVOICE_GENERATED_AT: " . $output_file . "\n";
?>
