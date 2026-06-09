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
    .metric-icon.purple { background: #f3e5f5; color: #9c27b0; }
    
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

    .attendance-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 15px;
    }
    
    .attendance-box {
        background: #f8fcfd;
        border: 1px solid #e1f0f6;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
    }
    
    .attendance-box span {
        display: block;
        font-size: 12px;
        color: #607d8b;
        margin-bottom: 5px;
    }
    
    .attendance-box strong {
        font-size: 16px;
        color: #263238;
    }

    /* Responsiveness */
    @media (max-width: 1200px) {
        .attendance-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    @media (max-width: 991px) {
        .chart-container {
            margin-bottom: 20px;
        }
    }
    @media (max-width: 767px) {
        .top-metric {
            flex-direction: column;
            text-align: center;
        }
        .metric-icon {
            margin-right: 0;
            margin-bottom: 10px;
        }
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-users"></i> <?php echo $this->lang->line('student_dashboard'); ?></h1>
    </section>
    <section class="content">
        <div class="dashboard-wrapper">
            
            <div class="row">
                <div class="col-md-12">
                    <div class="glass-card" style="padding: 15px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
                        <h4 style="margin: 0;">Date Filter</h4>
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <input type="text" class="form-control date" id="dashboard_date" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" style="width: 150px;" readonly>
                            <button type="button" class="btn btn-primary" onclick="loadDashboardStats()">Filter</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top 4 Metrics -->
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="glass-card top-metric" style="border-top: 3px solid #2196f3;">
                        <div class="metric-icon blue"><i class="fa fa-users"></i></div>
                        <div class="metric-content">
                            <h4>Total Students</h4>
                            <h2 id="metric_total_students">0</h2>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="glass-card top-metric" style="border-top: 3px solid #4caf50;">
                        <div class="metric-icon green"><i class="fa fa-user-plus"></i></div>
                        <div class="metric-content">
                            <h4><?php echo $this->lang->line('new_admission'); ?></h4>
                            <h2 id="metric_new_admissions">0</h2>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="glass-card top-metric" style="border-top: 3px solid #ff9800;">
                        <div class="metric-icon orange"><i class="fa fa-birthday-cake"></i></div>
                        <div class="metric-content">
                            <h4><?php echo $this->lang->line('birthdays_today'); ?></h4>
                            <h2 id="metric_birthdays_today">0</h2>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
                    <div class="glass-card top-metric" style="border-top: 3px solid #9c27b0;">
                        <div class="metric-icon purple"><i class="fa fa-child"></i></div>
                        <div class="metric-content">
                            <h4><?php echo $this->lang->line('rte_students'); ?></h4>
                            <h2 id="metric_rte_students">0</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Class-wise Students Chart -->
                <div class="col-lg-7 col-md-12">
                    <div class="glass-card">
                        <h4 class="section-title"><?php echo $this->lang->line('class_wise_students'); ?></h4>
                        <div class="chart-container" style="height: 300px; position: relative; width: 100%;">
                            <canvas id="classWiseChart"></canvas>
                        </div>
                    </div>
                </div>
                <!-- Gender Ratio (Pie) -->
                <div class="col-lg-5 col-md-12">
                    <div class="glass-card">
                        <h4 class="section-title"><?php echo $this->lang->line('gender_ratio'); ?></h4>
                        <div class="chart-container" style="height: 300px; margin-top: 20px;">
                            <canvas id="genderRatioChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Category Distribution (Doughnut) -->
                <div class="col-lg-5 col-md-12">
                    <div class="glass-card">
                        <h4 class="section-title"><?php echo $this->lang->line('category_distribution'); ?></h4>
                        <div class="chart-container" style="height: 250px; margin-top: 20px;">
                            <canvas id="categoryDistributionChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Attendance Summary -->
                <div class="col-lg-7 col-md-12">
                    <div class="glass-card">
                        <h4 class="section-title"><i class="fa fa-calendar-check-o"></i> <?php echo $this->lang->line('todays_attendance'); ?></h4>
                        <div class="attendance-grid" id="attendance_grid">
                            <!-- Filled dynamically -->
                        </div>
                        <div style="margin-top: 20px;">
                            <h4 style="font-weight: 600; text-align: center; color: #4caf50;">Total Present: <span id="metric_total_present">0</span></h4>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
</div>

<script>
    var classWiseChartInstance = null;
    var genderRatioChartInstance = null;
    var categoryDistributionChartInstance = null;

    function loadDashboardStats() {
        var date = $('#dashboard_date').val();
        $.ajax({
            url: base_url + 'student/get_dashboard_stats',
            type: 'POST',
            data: {date: date},
            dataType: 'json',
            success: function(response) {
                $('#metric_total_students').text(response.total_students);
                $('#metric_new_admissions').text(response.new_admissions);
                $('#metric_birthdays_today').text(response.birthdays_today);
                $('#metric_rte_students').text(response.rte_students);
                $('#metric_total_present').text(response.total_present);

                // Attendance Grid
                var attendanceHtml = '';
                var attColors = {'Present': '#4caf50', 'Absent': '#f44336', 'Late': '#ff9800', 'Half Day': '#2196f3'};
                $.each(response.attendance_today, function(type, count) {
                    var color = attColors[type] || '#607d8b';
                    attendanceHtml += '<div class="attendance-box">' +
                                        '<span>' + type + '</span>' +
                                        '<strong style="color: ' + color + ';">' + count + '</strong>' +
                                      '</div>';
                });
                $('#attendance_grid').html(attendanceHtml);

                // Charts
                renderClassWiseChart(response.class_wise);
                renderGenderChart(response.gender_ratio);
                renderCategoryChart(response.category_distribution);
            }
        });
    }

    function renderClassWiseChart(data) {
        var ctx = document.getElementById('classWiseChart').getContext('2d');
        var labels = Object.keys(data);
        var values = Object.values(data);
        
        if (labels.length === 1) {
            labels.unshift(""); values.unshift(0);
            labels.push(""); values.push(0);
        }

        if (classWiseChartInstance) {
            classWiseChartInstance.destroy();
        }

        var barChartData = {
            labels: labels,
            datasets: [
                {
                    fillColor: "rgba(33, 150, 243, 0.7)",
                    strokeColor: "rgba(33, 150, 243, 1)",
                    data: values
                }
            ]
        };
        
        var barOptions = {
            responsive: true,
            maintainAspectRatio: false,
            scaleBeginAtZero: true,
            scaleShowGridLines: true,
            scaleGridLineColor: "rgba(0,0,0,.05)",
            barValueSpacing: 15,
            barDatasetSpacing: 1
        };

        classWiseChartInstance = new Chart(ctx).Bar(barChartData, barOptions);
    }

    function renderGenderChart(data) {
        var ctx = document.getElementById('genderRatioChart').getContext('2d');
        var pieData = [];
        var colors = {'Male': '#2196f3', 'Female': '#e91e63'};
        
        $.each(data, function(gender, count) {
            if (count > 0) {
                pieData.push({
                    value: count,
                    color: colors[gender] || '#9c27b0',
                    label: gender
                });
            }
        });

        if (genderRatioChartInstance) {
            genderRatioChartInstance.destroy();
        }

        genderRatioChartInstance = new Chart(ctx).Pie(pieData, {
            responsive: true,
            maintainAspectRatio: false
        });
    }

    function renderCategoryChart(data) {
        var ctx = document.getElementById('categoryDistributionChart').getContext('2d');
        var colors = ['#4caf50', '#ff9800', '#f44336', '#9c27b0', '#00bcd4', '#2196f3', '#e91e63'];
        var doughnutData = [];
        var i = 0;

        $.each(data, function(cat, count) {
            if (count > 0) {
                doughnutData.push({
                    value: count,
                    color: colors[i % colors.length],
                    label: cat
                });
                i++;
            }
        });

        if (categoryDistributionChartInstance) {
            categoryDistributionChartInstance.destroy();
        }

        categoryDistributionChartInstance = new Chart(ctx).Doughnut(doughnutData, {
            responsive: true,
            maintainAspectRatio: false,
            percentageInnerCutout: 60
        });
    }

    $(document).ready(function() {
        $('.date').datepicker({
            format: date_format,
            autoclose: true,
            todayHighlight: true
        });
        loadDashboardStats();
    });
</script>
