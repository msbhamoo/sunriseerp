<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Sunrise ERP Digest</title>
<style>
    body {
        margin: 0;
        padding: 0;
        background-color: #f4f7f6;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif, Arial;
        color: #333333;
        -webkit-font-smoothing: antialiased;
    }
    table {
        border-collapse: collapse;
        width: 100%;
    }
    .wrapper {
        width: 100%;
        background-color: #f4f7f6;
        padding: 20px 0;
    }
    .container {
        max-width: 650px;
        margin: 0 auto;
        background-color: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .header {
        background-color: #0f2039;
        color: #ffffff;
        padding: 30px 40px;
        text-align: center;
        border-bottom: 4px solid #f39c12;
    }
    .header h1 {
        margin: 0;
        font-size: 24px;
        font-weight: 600;
        letter-spacing: 1px;
    }
    .header p {
        margin: 5px 0 0 0;
        font-size: 14px;
        color: #bdc3c7;
    }
    .content {
        padding: 40px;
    }
    .greeting {
        font-size: 20px;
        font-weight: 600;
        color: #0f2039;
        margin-bottom: 25px;
    }
    .greeting span {
        color: #f39c12;
    }
    .section-title {
        font-size: 16px;
        font-weight: bold;
        color: #7f8c8d;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin: 30px 0 15px 0;
        border-bottom: 2px solid #ecf0f1;
        padding-bottom: 8px;
    }
    
    /* Stats Grid */
    .stats-grid {
        width: 100%;
        margin-bottom: 20px;
    }
    .stats-grid td {
        padding: 15px;
        width: 50%;
        vertical-align: top;
    }
    .stat-card {
        background-color: #f8faf9;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
    }
    .stat-card.highlight {
        background-color: #fffaf0;
        border-color: #fde6bd;
    }
    .stat-label {
        font-size: 13px;
        color: #718096;
        text-transform: uppercase;
        margin-bottom: 8px;
        font-weight: 600;
    }
    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: #2d3748;
    }
    .stat-value.green { color: #27ae60; }
    .stat-value.red { color: #e74c3c; }
    .stat-value.blue { color: #2980b9; }
    .stat-value.orange { color: #e67e22; }

    /* Data Table */
    .data-table {
        width: 100%;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }
    .data-table th {
        background-color: #f1f5f9;
        color: #4a5568;
        font-size: 13px;
        font-weight: 600;
        text-transform: uppercase;
        text-align: left;
        padding: 12px 15px;
        border-bottom: 1px solid #e2e8f0;
    }
    .data-table td {
        padding: 12px 15px;
        font-size: 14px;
        color: #2d3748;
        border-bottom: 1px solid #edf2f7;
    }
    .data-table tr:last-child td {
        border-bottom: none;
    }
    .data-table tr:nth-child(even) td {
        background-color: #fafbfc;
    }
    
    .footer {
        background-color: #f8faf9;
        padding: 25px;
        text-align: center;
        border-top: 1px solid #e2e8f0;
        font-size: 12px;
        color: #a0aec0;
    }
    .empty-state {
        color: #a0aec0;
        font-style: italic;
        text-align: center;
        padding: 20px;
    }
</style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            
            <!-- Header -->
            <div class="header">
                <h1>Sunrise ERP</h1>
                <p><?php echo $period_name; ?> Digest Report</p>
            </div>
            
            <!-- Content -->
            <div class="content">
                
                <div class="greeting">
                    Hello <span><?php echo isset($recipient_name) ? $recipient_name : 'Administrator'; ?></span>,
                </div>
                
                <p style="font-size: 15px; color: #4a5568; line-height: 1.6; margin-bottom: 30px;">
                    Here is your automated <?php echo strtolower($period_name); ?> summary report from the Sunrise ERP system. Below you'll find the latest insights on accounting, fee collections, and attendance.
                </p>

                <!-- Core Financials -->
                <div class="section-title">Income & Fees</div>
                <table class="stats-grid">
                    <tr>
                        <td>
                            <div class="stat-card">
                                <div class="stat-label">Total Income</div>
                                <div class="stat-value green">₹<?php echo number_format($stats['income'], 2); ?></div>
                            </div>
                        </td>
                        <td>
                            <div class="stat-card">
                                <div class="stat-label">Total Expenses</div>
                                <div class="stat-value red">₹<?php echo number_format($stats['expense'], 2); ?></div>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding-top: 0;">
                            <div class="stat-card highlight">
                                <div class="stat-label">Fee Collection</div>
                                <div class="stat-value orange">₹<?php echo number_format($stats['fee_collection'], 2); ?></div>
                            </div>
                        </td>
                    </tr>
                </table>

                <!-- Advanced Accounting Data -->
                <?php if(isset($stats['acc_voucher_total'])): ?>
                <div class="section-title">Accounting & Vouchers</div>
                <table class="stats-grid">
                    <tr>
                        <td>
                            <div class="stat-card">
                                <div class="stat-label">Voucher Volume</div>
                                <div class="stat-value blue">₹<?php echo number_format($stats['acc_voucher_total'], 2); ?></div>
                            </div>
                        </td>
                        <td>
                            <div class="stat-card">
                                <div class="stat-label">Highest Paid</div>
                                <div class="stat-value">₹<?php echo number_format($stats['acc_highest_voucher'], 2); ?></div>
                            </div>
                        </td>
                    </tr>
                </table>

                <div class="section-title" style="margin-top: 10px;">Active Ledgers (Debits)</div>
                <table class="data-table">
                    <tr>
                        <th>Ledger Name</th>
                        <th style="text-align: right;">Total Debit</th>
                        <th style="text-align: right;">Total Credit</th>
                    </tr>
                    <?php if(!empty($stats['acc_active_ledgers'])): ?>
                        <?php foreach($stats['acc_active_ledgers'] as $ledger): ?>
                        <tr>
                            <td><strong><?php echo $ledger['ledger_name']; ?></strong></td>
                            <td style="text-align: right; color: #27ae60;">₹<?php echo number_format($ledger['total_debit'], 2); ?></td>
                            <td style="text-align: right; color: #e74c3c;">₹<?php echo number_format($ledger['total_credit'], 2); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="empty-state">No ledger activity recorded for this period.</td>
                        </tr>
                    <?php endif; ?>
                </table>
                <?php endif; ?>

                <!-- Attendance Metrics -->
                <div class="section-title">Attendance Overview</div>
                <table class="data-table">
                    <tr>
                        <th>Group</th>
                        <th style="text-align: center; color: #27ae60;">Present</th>
                        <th style="text-align: center; color: #e74c3c;">Absent</th>
                    </tr>
                    <tr>
                        <td><strong>Staff</strong></td>
                        <td style="text-align: center;"><?php echo $stats['staff_present']; ?></td>
                        <td style="text-align: center;"><?php echo $stats['staff_absent']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Students</strong></td>
                        <td style="text-align: center;"><?php echo $stats['student_present']; ?></td>
                        <td style="text-align: center;"><?php echo $stats['student_absent']; ?></td>
                    </tr>
                </table>
                
            </div>
            
            <!-- Footer -->
            <div class="footer">
                <p style="margin: 0;">This is an automated digest generated by Sunrise ERP.</p>
                <p style="margin: 5px 0 0 0;">Please do not reply directly to this email.</p>
            </div>
            
        </div>
    </div>
</body>
</html>
