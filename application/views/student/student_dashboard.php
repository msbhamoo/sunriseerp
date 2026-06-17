<style type="text/css">
    .dashboard2-wrapper {
        background-color: #f4f6f9;
        font-family: 'Inter', sans-serif;
    }
    .d2-card {
        background: #fff;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid #eaeaea;
        position: relative;
        height: calc(100% - 20px);
    }
    .d2-title {
        font-size: 14px;
        font-weight: 600;
        color: #555;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 15px;
    }
    .d2-stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #2c2c2c;
    }
    .d2-stat-sub {
        font-size: 12px;
        color: #8a8a8a;
        margin-top: 5px;
    }
    .d2-box {
        border-radius: 6px;
        padding: 15px;
        margin-bottom: 15px;
        border: 1px solid #f0f0f0;
        position: relative;
    }
    .d2-box-title {
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 5px;
    }
    .d2-box-val {
        font-size: 22px;
        font-weight: 700;
    }
    .d2-box-sub {
        font-size: 11px;
        color: #888;
    }
    .d2-box.students { background: #fdfaf6; border-color: #f7eedf; }
    .d2-box.students .d2-box-title { color: #d68940; }
    
    .d2-box.staff { background: #fdfaff; border-color: #f4e8fb; }
    .d2-box.staff .d2-box-title { color: #9d50ce; }

    .d2-box.attendance { background: #f6fffa; border-color: #dcf2e6; }
    .d2-box.attendance .d2-box-title { color: #3b9b65; }

    .d2-box.feerecovery { background: #fff5f8; border-color: #fbe0e8; }
    .d2-box.feerecovery .d2-box-title { color: #d8456a; }
    
    .d2-box.pending { background: #fffcf5; border-color: #fbedcf; text-align: center;}
    .d2-box.pending .d2-box-title { color: #d09435; }
    
    .d2-btn {
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
        border: 1px solid #ccc;
        background: #fff;
        color: #333;
        transition: all 0.2s;
        cursor: pointer;
    }
    .d2-btn:hover { background: #f5f5f5; }
    .d2-btn.primary { background: #007bff; color: #fff; border-color: #007bff; }
    .d2-btn.primary:hover { background: #0069d9; }
    .btn-view-metrics {
        position: absolute;
        top: 15px;
        right: 15px;
    }
    .d2-box .btn-view-metrics {
        top: 10px;
        right: 10px;
        padding: 2px 6px;
        font-size: 10px;
    }
    
    .chart-container {
        position: relative;
        height: 250px;
        width: 100%;
        max-width: 100%;
    }
</style>

<div class="content-wrapper dashboard2-wrapper">
    <section class="content-header">
        <div class="row" style="display:flex; align-items:center; justify-content:space-between; margin-bottom: 20px;">
            <div class="col-md-6">
                <h1 style="margin:0; font-size: 24px; font-weight:700;"><i class="fa fa-users"></i> <?php echo $this->lang->line('student_dashboard'); ?></h1>
            </div>
            <div class="col-md-6 text-right" style="display:flex; justify-content: flex-end; gap: 10px; align-items: center;">
                <label style="margin:0; font-size: 13px;">Date Filter:</label>
                <input type="text" class="form-control date" id="dashboard_date" value="<?php echo date($this->customlib->getSchoolDateFormat()); ?>" style="width: 130px; display:inline-block;" readonly>
                <button type="button" class="d2-btn primary" onclick="loadDashboardStats()">Filter</button>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="d2-card" style="height: auto;">
                    <div class="d2-title">Students Overview</div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="d2-box students">
                                <button class="d2-btn btn-view-metrics" onclick="viewMetrics('total_students')"><i class="fa fa-eye"></i></button>
                                <div class="d2-box-title">Total Students</div>
                                <div class="d2-box-val" id="metric_total_students">0</div>
                                <div class="d2-box-sub">Active Students Enrolled</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d2-box attendance">
                                <button class="d2-btn btn-view-metrics" onclick="viewMetrics('new_admissions')"><i class="fa fa-eye"></i></button>
                                <div class="d2-box-title"><?php echo $this->lang->line('new_admission'); ?></div>
                                <div class="d2-box-val" id="metric_new_admissions">0</div>
                                <div class="d2-box-sub">Admissions this Month</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d2-box pending">
                                <button class="d2-btn btn-view-metrics" onclick="viewMetrics('birthdays')"><i class="fa fa-eye"></i></button>
                                <div class="d2-box-title"><?php echo $this->lang->line('birthdays_today'); ?></div>
                                <div class="d2-box-val" id="metric_birthdays_today">0</div>
                                <div class="d2-box-sub">Students with birthdays</div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d2-box staff">
                                <button class="d2-btn btn-view-metrics" onclick="viewMetrics('rte')"><i class="fa fa-eye"></i></button>
                                <div class="d2-box-title"><?php echo $this->lang->line('rte_students'); ?></div>
                                <div class="d2-box-val" id="metric_rte_students">0</div>
                                <div class="d2-box-sub">RTE Active Students</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Class-wise Students Chart -->
            <div class="col-lg-6 col-md-12">
                <div class="d2-card">
                    <button class="d2-btn btn-view-metrics" onclick="viewMetrics('class_wise')">View Metrics</button>
                    <div class="d2-title"><?php echo $this->lang->line('class_wise_students'); ?></div>
                    <div class="chart-container">
                        <canvas id="classWiseChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Age Analysis Chart -->
            <div class="col-lg-6 col-md-12">
                <div class="d2-card">
                    <button class="d2-btn btn-view-metrics" onclick="viewMetrics('age_analysis')">View Metrics</button>
                    <div class="d2-title">Age Analysis</div>
                    <div class="chart-container">
                        <canvas id="ageAnalysisChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Gender Ratio -->
            <div class="col-lg-4 col-md-12">
                <div class="d2-card">
                    <button class="d2-btn btn-view-metrics" onclick="viewMetrics('gender')">View Metrics</button>
                    <div class="d2-title"><?php echo $this->lang->line('gender_ratio'); ?></div>
                    <div class="chart-container">
                        <canvas id="genderRatioChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Category Distribution -->
            <div class="col-lg-4 col-md-12">
                <div class="d2-card">
                    <button class="d2-btn btn-view-metrics" onclick="viewMetrics('category')">View Metrics</button>
                    <div class="d2-title"><?php echo $this->lang->line('category_distribution'); ?></div>
                    <div class="chart-container">
                        <canvas id="categoryDistributionChart"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Attendance Summary -->
            <div class="col-lg-4 col-md-12">
                <div class="d2-card">
                    <button class="d2-btn btn-view-metrics" onclick="viewMetrics('attendance')">View Metrics</button>
                    <div class="d2-title"><i class="fa fa-calendar-check-o"></i> <?php echo $this->lang->line('todays_attendance'); ?></div>
                    <div style="font-weight: 600; text-align: center; color: #3b9b65; font-size: 18px; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 10px;">
                        Total Present: <span id="metric_total_present">0</span>
                    </div>
                    <div class="row" id="attendance_grid">
                        <!-- Filled dynamically -->
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Generic Modal for viewing metrics -->
<div id="metricsModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" id="metricsModalTitle" style="font-weight: 600;">Metrics Data</h4>
            </div>
            <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-striped table-hover" id="metricsModalTable">
                    <thead>
                        <tr id="metricsModalTableHead"></tr>
                    </thead>
                    <tbody id="metricsModalTableBody"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script>
    var classWiseChartInstance = null;
    var genderRatioChartInstance = null;
    var categoryDistributionChartInstance = null;
    var ageAnalysisChartInstance = null;
    
    var dashboardGlobalData = null;

    function loadDashboardStats() {
        var date = $('#dashboard_date').val();
        $.ajax({
            url: base_url + 'student/get_dashboard_stats',
            type: 'POST',
            data: {date: date},
            dataType: 'json',
            success: function(response) {
                dashboardGlobalData = response;
                $('#metric_total_students').text(response.total_students);
                $('#metric_new_admissions').text(response.new_admissions);
                $('#metric_birthdays_today').text(response.birthdays_today);
                $('#metric_rte_students').text(response.rte_students);
                $('#metric_total_present').text(response.total_present);

                // Attendance Grid
                var attendanceHtml = '';
                var attColors = {'Present': '#3b9b65', 'Absent': '#d8456a', 'Late': '#d09435', 'Half Day': '#007bff'};
                $.each(response.attendance_today, function(type, count) {
                    var color = attColors[type] || '#607d8b';
                    attendanceHtml += '<div class="col-xs-6" style="margin-bottom: 15px; text-align: center;">' +
                                        '<div style="font-size: 11px; text-transform: uppercase; color: #888;">' + type + '</div>' +
                                        '<div style="font-size: 20px; font-weight: 700; color: ' + color + ';">' + count + '</div>' +
                                      '</div>';
                });
                $('#attendance_grid').html(attendanceHtml);

                // Charts
                renderClassWiseChart(response.class_wise);
                renderGenderChart(response.gender_ratio);
                renderCategoryChart(response.category_distribution);
                renderAgeChart(response.age_stats);
            }
        });
    }

    function viewMetrics(type) {
        if(!dashboardGlobalData) return;
        
        var title = "Metrics Data";
        var headHtml = "";
        var bodyHtml = "";
        
        if(type === 'class_wise') {
            title = "Class-wise Students";
            headHtml = "<th>Class</th><th>Student Count</th>";
            $.each(dashboardGlobalData.class_wise, function(k, v) {
                bodyHtml += "<tr><td>" + k + "</td><td><span class='label label-primary'>" + v + "</span></td></tr>";
            });
        } else if(type === 'age_analysis') {
            title = "Age Analysis";
            headHtml = "<th>Age Group</th><th>Student Count</th>";
            $.each(dashboardGlobalData.age_stats, function(k, v) {
                bodyHtml += "<tr><td>" + k + "</td><td><span class='label label-success'>" + v + "</span></td></tr>";
            });
        } else if(type === 'gender') {
            title = "Gender Ratio";
            headHtml = "<th>Gender</th><th>Count</th>";
            $.each(dashboardGlobalData.gender_ratio, function(k, v) {
                bodyHtml += "<tr><td>" + k + "</td><td><span class='label label-info'>" + v + "</span></td></tr>";
            });
        } else if(type === 'category') {
            title = "Category Distribution";
            headHtml = "<th>Category</th><th>Count</th>";
            $.each(dashboardGlobalData.category_distribution, function(k, v) {
                bodyHtml += "<tr><td>" + k + "</td><td><span class='label label-warning'>" + v + "</span></td></tr>";
            });
        } else if(type === 'attendance') {
            title = "Today's Attendance Breakdown";
            headHtml = "<th>Type</th><th>Count</th>";
            $.each(dashboardGlobalData.attendance_today, function(k, v) {
                bodyHtml += "<tr><td>" + k + "</td><td><span class='label label-default'>" + v + "</span></td></tr>";
            });
        } else if(type === 'birthdays') {
            title = "Birthdays Today";
            headHtml = "<th>Name</th><th>Class</th>";
            $.each(dashboardGlobalData.birthdays_list, function(i, student) {
                bodyHtml += "<tr><td>" + student.firstname + " " + student.lastname + "</td><td>" + student.class + " (" + student.section + ")</td></tr>";
            });
            if(dashboardGlobalData.birthdays_list.length === 0) {
                bodyHtml = "<tr><td colspan='2' class='text-center text-muted'>No birthdays today</td></tr>";
            }
        } else if(type === 'rte') {
            title = "RTE Students Summary";
            headHtml = "<th>Metric</th><th>Value</th>";
            bodyHtml += "<tr><td>Total RTE Students Enrolled</td><td><span class='label label-primary'>" + dashboardGlobalData.rte_students + "</span></td></tr>";
            bodyHtml += "<tr><td colspan='2' class='text-muted text-center' style='font-size:12px;'>Detailed RTE list can be viewed from Student List report.</td></tr>";
        } else if(type === 'total_students') {
            title = "Total Students Summary";
            headHtml = "<th>Metric</th><th>Value</th>";
            bodyHtml += "<tr><td>Total Active Students Enrolled</td><td><span class='label label-primary'>" + dashboardGlobalData.total_students + "</span></td></tr>";
            bodyHtml += "<tr><td colspan='2' class='text-muted text-center' style='font-size:12px;'>Please visit Student Details for a full list.</td></tr>";
        } else if(type === 'new_admissions') {
            title = "New Admissions Summary";
            headHtml = "<th>Metric</th><th>Value</th>";
            bodyHtml += "<tr><td>New Admissions this Month</td><td><span class='label label-success'>" + dashboardGlobalData.new_admissions + "</span></td></tr>";
            bodyHtml += "<tr><td colspan='2' class='text-muted text-center' style='font-size:12px;'>Check Admission Enquiries or Student List for full records.</td></tr>";
        }
        
        $('#metricsModalTitle').text(title);
        $('#metricsModalTableHead').html(headHtml);
        $('#metricsModalTableBody').html(bodyHtml);
        $('#metricsModal').modal('show');
    }

    function renderClassWiseChart(data) {
        var ctx = document.getElementById('classWiseChart');
        if(!ctx) return;
        ctx = ctx.getContext('2d');
        var labels = Object.keys(data);
        var values = Object.values(data);
        
        var baseColors = ['#4f46e5', '#ec4899', '#14b8a6', '#f59e0b', '#3b82f6', '#8b5cf6', '#ef4444', '#10b981', '#f97316', '#6366f1', '#d946ef', '#06b6d4'];
        var bgColors = [];
        for(var i=0; i<values.length; i++) {
            bgColors.push(baseColors[i % baseColors.length]);
        }

        if (classWiseChartInstance) {
            classWiseChartInstance.destroy();
        }

        classWiseChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Students',
                    data: values,
                    backgroundColor: bgColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: { display: false },
                scales: {
                    yAxes: [{
                        ticks: { beginAtZero: true }
                    }]
                }
            }
        });
    }

    function renderGenderChart(data) {
        var ctx = document.getElementById('genderRatioChart');
        if(!ctx) return;
        ctx = ctx.getContext('2d');
        var pieData = [];
        var pieLabels = [];
        var colors = {'Male': '#fbedcf', 'Female': '#f4e8fb'}; // Matches dashboard2 pills
        var borderColors = {'Male': '#d09435', 'Female': '#9d50ce'};
        var bgColors = [];
        var bColors = [];
        
        $.each(data, function(gender, count) {
            if (count > 0) {
                pieData.push(count);
                pieLabels.push(gender);
                bgColors.push(colors[gender] || '#e0e7ff');
                bColors.push(borderColors[gender] || '#4f46e5');
            }
        });

        if (genderRatioChartInstance) {
            genderRatioChartInstance.destroy();
        }

        genderRatioChartInstance = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: pieLabels,
                datasets: [{
                    data: pieData,
                    backgroundColor: bgColors,
                    borderColor: bColors,
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: { position: 'bottom' }
            }
        });
    }

    function renderCategoryChart(data) {
        var ctx = document.getElementById('categoryDistributionChart');
        if(!ctx) return;
        ctx = ctx.getContext('2d');
        var doughnutData = [];
        var dLabels = [];
        var bgColors = ['#eab308', '#0284c7', '#d8456a', '#3b9b65', '#9d50ce', '#d09435'];
        var i = 0;

        $.each(data, function(cat, count) {
            if (count > 0) {
                doughnutData.push(count);
                dLabels.push(cat);
                i++;
            }
        });

        if (categoryDistributionChartInstance) {
            categoryDistributionChartInstance.destroy();
        }

        categoryDistributionChartInstance = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: dLabels,
                datasets: [{
                    data: doughnutData,
                    backgroundColor: bgColors,
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutoutPercentage: 60,
                legend: { position: 'bottom' }
            }
        });
    }

    function renderAgeChart(data) {
        var ctx = document.getElementById('ageAnalysisChart');
        if(!ctx) return;
        ctx = ctx.getContext('2d');
        var labels = Object.keys(data);
        var values = Object.values(data);

        if (ageAnalysisChartInstance) {
            ageAnalysisChartInstance.destroy();
        }

        ageAnalysisChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Students',
                    data: values,
                    backgroundColor: '#14b8a6',
                    borderColor: '#0f766e',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: { display: false },
                scales: {
                    yAxes: [{
                        ticks: { beginAtZero: true }
                    }]
                }
            }
        });
    }

    $(document).ready(function() {
        if ($.fn.datepicker) {
            $('.date').datepicker({
                format: date_format,
                autoclose: true,
                todayHighlight: true
            });
        }
        loadDashboardStats();
    });
</script>
