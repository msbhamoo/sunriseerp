<?php
$currency_symbol = $this->customlib->getSchoolCurrencyFormat();
?>
<style>
    /* Modern Dashboard Styling */
    .dashboard-wrapper {
        padding: 20px;
        background-color: #f4f6f9;
    }
    
    .glass-card {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.05);
        padding: 20px;
        margin-bottom: 20px;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .glass-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
    
    .top-metric {
        display: flex;
        align-items: center;
    }
    
    .metric-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-right: 15px;
        flex-shrink: 0;
    }
    
    .metric-icon.blue { background: #e3f2fd; color: #2196f3; }
    .metric-icon.green { background: #e8f5e9; color: #4caf50; }
    .metric-icon.orange { background: #fff3e0; color: #ff9800; }
    .metric-icon.red { background: #ffebee; color: #f44336; }
    
    .metric-content h4 {
        margin: 0;
        font-size: 13px;
        color: #757575;
        font-weight: 600;
        text-transform: uppercase;
    }
    
    .metric-content h2 {
        margin: 5px 0 0;
        font-size: 24px;
        font-weight: 700;
        color: #333;
    }
    
    .secondary-metric {
        text-align: center;
        padding: 15px;
    }
    
    .secondary-metric h5 {
        margin: 0 0 10px;
        font-size: 13px;
        color: #757575;
        font-weight: 500;
    }
    
    .secondary-metric h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
        color: #424242;
    }

    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
        max-width: 100%;
        padding: 0 10px;
        box-sizing: border-box;
    }
    
    .chart-container canvas {
        max-width: 100% !important;
    }
    
    .section-title {
        font-size: 15px;
        font-weight: 600;
        color: #333;
        margin-top: 0;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }

    .payment-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
    }
    
    .payment-box {
        background: #f8fcfd;
        border: 1px solid #e1f0f6;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
    }
    
    .payment-box span {
        display: block;
        font-size: 12px;
        color: #607d8b;
        margin-bottom: 5px;
    }
    
    .payment-box strong {
        font-size: 16px;
        color: #263238;
    }

    .breakdown-summary {
        margin-top: 20px;
        display: flex;
        justify-content: space-around;
        text-align: center;
    }
    
    .breakdown-item {
        border: 1px solid #eee;
        border-radius: 6px;
        padding: 10px 20px;
    }
    
    .breakdown-item.collected { border-color: #4caf50; color: #4caf50; }
    .breakdown-item.discount { border-color: #ff9800; color: #ff9800; }
    .breakdown-item.pending { border-color: #f44336; color: #f44336; }
    
    .breakdown-item span { display: block; font-size: 11px; margin-top: 5px; color: #757575; }
    .breakdown-item strong { font-size: 16px; }

    /* Responsiveness */
    @media (max-width: 1200px) {
        .payment-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    @media (max-width: 991px) {
        .payment-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .chart-container {
            margin-bottom: 20px;
        }
    }
    @media (max-width: 767px) {
        .payment-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .breakdown-summary {
            flex-direction: column;
            gap: 10px;
        }
        .breakdown-item {
            width: 100%;
        }
        .top-metric {
            flex-direction: column;
            text-align: center;
        }
        .metric-icon {
            margin-right: 0;
            margin-bottom: 10px;
        }
    }
    @media (max-width: 480px) {
        .payment-grid {
            grid-template-columns: 1fr;
        }
    }

</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-money"></i> <?php echo $this->lang->line('fees_dashboard'); ?></h1>
    </section>
    <section class="content">
        <div class="dashboard-wrapper">
            
            <!-- Top 4 Metrics -->
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="glass-card top-metric" style="border-top: 3px solid #2196f3;" title="Total assigned fees across all active students for this session.">
                        <div class="metric-icon blue"><i class="fa fa-inr"></i></div>
                        <div class="metric-content">
                            <h4>Total Fees</h4>
                            <h2><?php echo $currency_symbol . amountFormat($stats['total_fees']); ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="glass-card top-metric" style="border-top: 3px solid #4caf50;" title="Total fees successfully deposited by students.">
                        <div class="metric-icon green"><i class="fa fa-hand-holding-usd"></i></div>
                        <div class="metric-content">
                            <h4><?php echo $this->lang->line('total_collected'); ?></h4>
                            <h2><?php echo $currency_symbol . amountFormat($stats['total_collected']); ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="glass-card top-metric" style="border-top: 3px solid #ff9800;" title="Total discount amount granted to students.">
                        <div class="metric-icon orange"><i class="fa fa-tags"></i></div>
                        <div class="metric-content">
                            <h4><?php echo $this->lang->line('total_discount'); ?></h4>
                            <h2><?php echo $currency_symbol . amountFormat($stats['total_discount']); ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="glass-card top-metric" style="border-top: 3px solid #f44336;" title="Remaining assigned fees that have not been collected.">
                        <div class="metric-icon red"><i class="fa fa-calendar-times-o"></i></div>
                        <div class="metric-content">
                            <h4><?php echo $this->lang->line('due_pending'); ?></h4>
                            <h2><?php echo $currency_symbol . amountFormat($stats['due_pending']); ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Secondary Metrics -->
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="glass-card secondary-metric" title="Total fees collected specifically on today's date.">
                        <h5><?php echo $this->lang->line('todays_fees_collection'); ?></h5>
                        <h3><?php echo $currency_symbol . amountFormat($stats['todays_collection']); ?></h3>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="glass-card secondary-metric" title="Number of successful online payment gateway transactions.">
                        <h5><?php echo $this->lang->line('gateway_transactions_count'); ?></h5>
                        <h3><?php echo $stats['gateway_count']; ?></h3>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Monthly Collection Chart -->
                <div class="col-lg-7 col-md-12">
                    <div class="glass-card">
                        <h4 class="section-title"><?php echo $this->lang->line('monthly_collection'); ?></h4>
                        <div class="chart-container" style="height: 300px; position: relative; width: 100%;">
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Fees Breakdown -->
                <div class="col-lg-5 col-md-12">
                    <div class="glass-card">
                        <h4 class="section-title"><?php echo $this->lang->line('fees_breakdown'); ?></h4>
                        <div class="breakdown-summary">
                            <div class="breakdown-item collected">
                                <strong><?php echo $currency_symbol . amountFormat($stats['total_collected']); ?></strong>
                                <span>Collected</span>
                            </div>
                            <div class="breakdown-item discount">
                                <strong><?php echo $currency_symbol . amountFormat($stats['total_discount']); ?></strong>
                                <span>Discount</span>
                            </div>
                            <div class="breakdown-item pending">
                                <strong><?php echo $currency_symbol . amountFormat($stats['due_pending']); ?></strong>
                                <span>Pending</span>
                            </div>
                        </div>
                        <div class="chart-container" style="height: 200px; margin-top: 20px;">
                            <canvas id="breakdownChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Fees By Payment Mode -->
                <div class="col-lg-6 col-md-12">
                    <div class="glass-card">
                        <h4 class="section-title"><i class="fa fa-credit-card"></i> <?php echo $this->lang->line('fees_by_payment_mode'); ?></h4>
                        <div class="payment-grid">
                            <?php foreach($stats['payment_modes'] as $mode => $amount): ?>
                                <div class="payment-box">
                                    <span><?php echo $mode; ?></span>
                                    <strong><?php echo $currency_symbol . amountFormat($amount); ?></strong>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="chart-container" style="height: 250px; margin-top: 20px;">
                            <canvas id="paymentModeChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Class-wise Fees Collection -->
                <div class="col-lg-6 col-md-12">
                    <div class="glass-card">
                        <h4 class="section-title"><?php echo $this->lang->line('class_wise_fees_collection'); ?></h4>
                        <div class="chart-container" style="height: 250px; position: relative; width: 100%;">
                            <canvas id="classChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<script>
window.addEventListener("load", function() {
    
    // Monthly Collection Chart
    var ctxMonthly = document.getElementById('monthlyChart').getContext('2d');
    var monthlyData = <?php echo json_encode(array_values($stats['monthly_collection'])); ?>;
    var monthlyLabels = <?php echo json_encode(array_keys($stats['monthly_collection'])); ?>;
    
    // Prevent single bar from taking entire width
    if (monthlyLabels.length === 1) {
        monthlyLabels.unshift(""); monthlyData.unshift(0);
        monthlyLabels.push(""); monthlyData.push(0);
    }
    
    var barChartData = {
        labels: monthlyLabels,
        datasets: [
            {
                fillColor: "rgba(33, 150, 243, 0.7)",
                strokeColor: "rgba(33, 150, 243, 1)",
                data: monthlyData
            }
        ]
    };
    
    var barOptions = {
        responsive: true,
        maintainAspectRatio: false,
        scaleBeginAtZero: true,
        scaleShowGridLines: true,
        scaleGridLineColor: "rgba(0,0,0,.05)",
        barValueSpacing: 15, // Space between bars
        barDatasetSpacing: 1
    };
    
    new Chart(ctxMonthly).Bar(barChartData, barOptions);

    // Breakdown Chart (Doughnut)
    var ctxBreakdown = document.getElementById('breakdownChart').getContext('2d');
    
    var doughnutData = [
        {
            value: <?php echo $stats['total_collected']; ?>,
            color: "#4caf50",
            label: "Collected"
        },
        {
            value: <?php echo $stats['total_discount']; ?>,
            color: "#ff9800",
            label: "Discount"
        },
        {
            value: <?php echo $stats['due_pending']; ?>,
            color: "#f44336",
            label: "Pending"
        }
    ];
    
    var doughnutOptions = {
        responsive: true,
        maintainAspectRatio: false,
        percentageInnerCutout: 70
    };
    
    new Chart(ctxBreakdown).Doughnut(doughnutData, doughnutOptions);

    // Class-wise Fees Collection
    var ctxClass = document.getElementById('classChart').getContext('2d');
    var classData = <?php echo json_encode(array_values($stats['class_wise'])); ?>;
    var classLabels = <?php echo json_encode(array_keys($stats['class_wise'])); ?>;

    // Prevent single bar from taking entire width
    if (classLabels.length === 1) {
        classLabels.unshift(""); classData.unshift(0);
        classLabels.push(""); classData.push(0);
    }

    var classBarData = {
        labels: classLabels,
        datasets: [
            {
                fillColor: "rgba(33, 150, 243, 0.5)",
                strokeColor: "#2196f3",
                data: classData
            }
        ]
    };

    new Chart(ctxClass).Bar(classBarData, barOptions);

    // Payment Mode Chart (Pie)
    var ctxPaymentMode = document.getElementById('paymentModeChart').getContext('2d');
    var paymentModeLabels = <?php echo json_encode(array_keys($stats['payment_modes'])); ?>;
    var paymentModeDataRaw = <?php echo json_encode(array_values($stats['payment_modes'])); ?>;
    var paymentModeColors = ['#2196f3', '#4caf50', '#ff9800', '#f44336', '#9c27b0', '#00bcd4', '#e91e63', '#607d8b'];
    
    var paymentModeData = [];
    for (var i = 0; i < paymentModeLabels.length; i++) {
        // Only include if value > 0 for better chart appearance, optional but good practice
        if(paymentModeDataRaw[i] > 0) {
            paymentModeData.push({
                value: paymentModeDataRaw[i],
                color: paymentModeColors[i % paymentModeColors.length],
                label: paymentModeLabels[i]
            });
        }
    }

    new Chart(ctxPaymentMode).Pie(paymentModeData, {
        responsive: true,
        maintainAspectRatio: false
    });

});
</script>
