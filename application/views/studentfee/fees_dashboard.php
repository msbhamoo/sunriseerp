<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat(); ?>
<style type="text/css">
    .dashboard2-wrapper { background-color: #f4f6f9; font-family: 'Inter', sans-serif; }
    .d2-card { background: #fff; border-radius: 8px; padding: 20px; margin-bottom: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); border: 1px solid #eaeaea; }
    .d2-title { font-size: 14px; font-weight: 600; color: #8a8a8a; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 15px; }
    .d2-stat-value { font-size: 28px; font-weight: 700; color: #2c2c2c; }
    .d2-stat-sub { font-size: 12px; color: #8a8a8a; margin-top: 5px; }
    .d2-box { border-radius: 6px; padding: 15px; margin-bottom: 15px; border: 1px solid #f0f0f0; }
    .d2-box-title { font-size: 11px; text-transform: uppercase; font-weight: 600; margin-bottom: 5px; }
    .d2-box-val { font-size: 16px; font-weight: 700; word-break: break-word; line-height: 1.2; }
    .d2-box-sub { font-size: 11px; color: #888; }
    .d2-box.total { background: #e0e7ff; border-color: #c7d2fe; }
    .d2-box.total .d2-box-title { color: #4f46e5; }
    .d2-box.collected { background: #dcfce7; border-color: #bbf7d0; }
    .d2-box.collected .d2-box-title { color: #16a34a; }
    .d2-box.discount { background: #fef08a; border-color: #fde047; }
    .d2-box.discount .d2-box-title { color: #ca8a04; }
    .d2-box.pending { background: #fee2e2; border-color: #fecaca; }
    .d2-box.pending .d2-box-title { color: #dc2626; }
    .d2-fee-summary-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #f0f0f0; }
    .d2-fee-summary-row:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
    .d2-fee-lbl { font-size: 13px; font-weight: 600; color: #555; }
    .d2-fee-val { font-size: 16px; font-weight: 700; color: #222; }
    .d2-pill { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; display: inline-block; }
    .payment-box { background: #f8fcfd; border: 1px solid #e1f0f6; border-radius: 6px; padding: 10px; margin-bottom: 10px; }
    .payment-box span { display: block; font-size: 11px; color: #607d8b; margin-bottom: 3px; }
    .payment-box strong { font-size: 14px; color: #263238; }
    .chart-container { position: relative; width: 100%; max-width: 100%; padding: 0; box-sizing: border-box; }
    .d2-header-actions { display: flex; gap: 10px; }
    .d2-btn { padding: 6px 12px; border-radius: 4px; font-size: 13px; font-weight: 500; border: 1px solid #ccc; background: #fff; color: #333; transition: all 0.2s; }
    .d2-btn:hover { background: #f5f5f5; }
    .d2-btn.primary { background: #007bff; color: #fff; border-color: #007bff; }
    .d2-btn.primary:hover { background: #0069d9; }
</style>

<div class="content-wrapper dashboard2-wrapper">
    <section class="content-header">
        <div class="row" style="display:flex; align-items:center; justify-content:space-between; margin-bottom: 20px;">
            <div class="col-md-6">
                <h1 style="margin:0; font-size: 24px; font-weight:700;"><i class="fa fa-money"></i> <?php echo $this->lang->line('fees_dashboard'); ?></h1>
                <small style="color:#888;">Fees / Dashboard</small>
            </div>
            <div class="col-md-6 text-right d2-header-actions" style="justify-content: flex-end;">
                <a href="<?php echo site_url('studentfee') ?>" class="d2-btn"><i class="fa fa-bolt" style="color:#28a745;"></i> Collect Fees</a>
                <a href="<?php echo site_url('financereports/collection_list') ?>" class="d2-btn"><i class="fa fa-list"></i> Collection List</a>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-8">
                <div class="d2-card">
                    <div class="d2-title">Fees Overview</div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="d2-box total">
                                <div class="d2-box-title">Total Fees</div>
                                <div class="d2-box-val"><?php echo $currency_symbol . amountFormat($stats['total_fees']); ?></div>
                                <div class="d2-box-sub">Assigned this session</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d2-box collected">
                                <div class="d2-box-title">Collected</div>
                                <div class="d2-box-val"><?php echo $currency_symbol . amountFormat($stats['total_collected']); ?></div>
                                <div class="d2-box-sub">Total received</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d2-box discount">
                                <div class="d2-box-title">Discount</div>
                                <div class="d2-box-val"><?php echo $currency_symbol . amountFormat($stats['total_discount']); ?></div>
                                <div class="d2-box-sub">Granted this session</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d2-box pending">
                                <div class="d2-box-title">Pending</div>
                                <div class="d2-box-val"><?php echo $currency_symbol . amountFormat($stats['due_pending']); ?></div>
                                <div class="d2-box-sub">To be collected</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d2-title" style="margin-top: 10px;">Monthly Collection</div>
                    <div class="chart-container" style="height: 280px;">
                        <canvas id="monthlyChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="d2-card" style="height: 100%;">
                    <div class="d2-title">Fees Breakdown</div>
                    <div class="chart-container" style="height: 220px; margin-bottom: 20px;">
                        <canvas id="breakdownChart"></canvas>
                    </div>

                    <div class="d2-fee-summary-row" style="background:#f6fffa; padding:15px; border-radius:6px; border:1px solid #dcf2e6; margin-bottom:15px;">
                        <div>
                            <div class="d2-fee-lbl" style="color:#3b9b65; font-size:11px; text-transform:uppercase;">Today's Collection</div>
                            <div class="d2-fee-val" style="color:#3b9b65;"><?php echo $currency_symbol . amountFormat($stats['todays_collection']); ?></div>
                        </div>
                    </div>

                    <div class="d2-fee-summary-row" style="background:#fdf4ff; padding:15px; border-radius:6px; border:1px solid #f4e8fb;">
                        <div>
                            <div class="d2-fee-lbl" style="color:#9d50ce; font-size:11px; text-transform:uppercase;">Gateway Transactions</div>
                            <div class="d2-fee-val" style="color:#9d50ce;"><?php echo $stats['gateway_count']; ?> Transactions</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-5">
                <div class="d2-card" style="height: 100%;">
                    <div class="d2-title">Payment Modes</div>
                    <div class="row" style="margin-bottom: 15px;">
                        <?php foreach($stats['payment_modes'] as $mode => $amount): ?>
                            <div class="col-xs-6">
                                <div class="payment-box">
                                    <span><?php echo $mode; ?></span>
                                    <strong><?php echo $currency_symbol . amountFormat($amount); ?></strong>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="chart-container" style="height: 220px;">
                        <canvas id="paymentModeChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="d2-card" style="height: 100%;">
                    <div class="d2-title">Class-wise Fees Collection</div>
                    <div class="chart-container" style="height: 320px;">
                        <canvas id="classChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script>
(function() {
    if (typeof Chart === 'undefined') { console.error('Chart.js not found'); return; }
    
    var monthlyLabels = <?php echo json_encode(array_keys($stats['monthly_collection'])); ?>;
    var monthlyData = <?php echo json_encode(array_values($stats['monthly_collection'])); ?>;
    
    if(monthlyLabels.length === 1) {
        monthlyLabels.unshift(""); monthlyData.unshift(0);
        monthlyLabels.push(""); monthlyData.push(0);
    }
    
    var ctxMonthly = document.getElementById('monthlyChart');
    if(ctxMonthly) {
        new Chart(ctxMonthly, {
            type: 'bar',
            data: {
                labels: monthlyLabels,
                datasets: [{
                    label: 'Collection',
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    data: monthlyData
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, legend: { display: false },
                scales: { yAxes: [{ ticks: { beginAtZero: true } }] }
            }
        });
    }

    var ctxBreakdown = document.getElementById('breakdownChart');
    if(ctxBreakdown) {
        new Chart(ctxBreakdown, {
            type: 'doughnut',
            data: {
                labels: ['Collected', 'Discount', 'Pending'],
                datasets: [{
                    data: [
                        <?php echo $stats['total_collected'] ? $stats['total_collected'] : 0; ?>,
                        <?php echo $stats['total_discount'] ? $stats['total_discount'] : 0; ?>,
                        <?php echo $stats['due_pending'] ? $stats['due_pending'] : 0; ?>
                    ],
                    backgroundColor: ['#22c55e', '#eab308', '#ef4444'],
                    borderWidth: 2, borderColor: '#fff'
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, cutoutPercentage: 70, legend: { position: 'bottom' }
            }
        });
    }

    var ctxClass = document.getElementById('classChart');
    var classLabels = <?php echo json_encode(array_keys($stats['class_wise'])); ?>;
    var classData = <?php echo json_encode(array_values($stats['class_wise'])); ?>;
    if(classLabels.length === 1) {
        classLabels.unshift(""); classData.unshift(0);
        classLabels.push(""); classData.push(0);
    }
    
    if(ctxClass) {
        new Chart(ctxClass, {
            type: 'bar',
            data: {
                labels: classLabels,
                datasets: [{
                    label: 'Collection',
                    backgroundColor: 'rgba(99, 102, 241, 0.6)',
                    borderColor: 'rgba(99, 102, 241, 1)',
                    borderWidth: 1, data: classData
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, legend: { display: false },
                scales: { yAxes: [{ ticks: { beginAtZero: true } }] }
            }
        });
    }

    var ctxPaymentMode = document.getElementById('paymentModeChart');
    var pmLabelsRaw = <?php echo json_encode(array_keys($stats['payment_modes'])); ?>;
    var pmDataRaw = <?php echo json_encode(array_values($stats['payment_modes'])); ?>;
    var pmColors = ['#3b82f6', '#22c55e', '#f59e0b', '#ef4444', '#a855f7', '#06b6d4', '#ec4899', '#64748b'];
    
    var pmLabels = []; var pmData = []; var pmBg = [];
    for(var i=0; i<pmLabelsRaw.length; i++){
        if(pmDataRaw[i] > 0){
            pmLabels.push(pmLabelsRaw[i]);
            pmData.push(pmDataRaw[i]);
            pmBg.push(pmColors[i % pmColors.length]);
        }
    }
    
    if(ctxPaymentMode) {
        new Chart(ctxPaymentMode, {
            type: 'pie',
            data: {
                labels: pmLabels,
                datasets: [{
                    data: pmData,
                    backgroundColor: pmBg,
                    borderWidth: 2, borderColor: '#fff'
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false, legend: { position: 'right' }
            }
        });
    }
})();
</script>
