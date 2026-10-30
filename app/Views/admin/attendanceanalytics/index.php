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
    }
    .d2-title {
        font-size: 14px;
        font-weight: 600;
        color: #8a8a8a;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 15px;
    }
    .d2-box {
        border-radius: 6px;
        padding: 15px;
        margin-bottom: 15px;
        border: 1px solid #f0f0f0;
    }
    .d2-box-title {
        font-size: 11px;
        text-transform: uppercase;
        font-weight: 600;
        margin-bottom: 5px;
    }
    .d2-box-val {
        font-size: 26px;
        font-weight: 700;
    }
    .d2-box-sub {
        font-size: 11px;
        color: #888;
    }
    .d2-box.present { background: #f6fffa; border-color: #dcf2e6; }
    .d2-box.present .d2-box-title { color: #3b9b65; }
    
    .d2-box.absent { background: #fff5f8; border-color: #fbe0e8; }
    .d2-box.absent .d2-box-title { color: #d8456a; }

    .d2-box.late { background: #fffcf5; border-color: #fbedcf; }
    .d2-box.late .d2-box-title { color: #d09435; }

    .d2-box.halfday { background: #fdfaff; border-color: #f4e8fb; }
    .d2-box.halfday .d2-box-title { color: #9d50ce; }
    
    .d2-pill {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .status-contacted { background: #f6fffa; color: #3b9b65; border: 1px solid #dcf2e6;}
    .status-notcontacted { background: #fff5f8; color: #d8456a; border: 1px solid #fbe0e8;}
    .status-pending { background: #f4f6f9; color: #666; border: 1px solid #eaeaea;}
</style>

<div class="content-wrapper dashboard2-wrapper">
    <section class="content-header">
        <div class="row" style="display:flex; align-items:center; justify-content:space-between; margin-bottom: 20px;">
            <div class="col-md-6">
                <h1 style="margin:0; font-size: 24px; font-weight:700;">Live Attendance Analytics</h1>
                <small style="color:#888;">Attendance / Analytics Dashboard</small>
            </div>
            <div class="col-md-6 text-right">
                <form action="<?php echo site_url('admin/attendanceanalytics') ?>" method="post" class="form-inline" style="justify-content: flex-end; display: flex;">
                    <?php echo $this->customlib->getCSRF(); ?>
                    <div class="form-group" style="margin-right: 10px;">
                        <input id="date" name="date" type="text" class="form-control date" value="<?php echo set_value('date', $date); ?>" readonly="readonly" style="width:150px; text-align:center; border-radius:4px; font-weight:bold;"/>
                    </div>
                    <button type="submit" class="btn btn-primary" style="border-radius:4px;"><i class="fa fa-refresh"></i> Update</button>
                </form>
            </div>
        </div>
    </section>

    <section class="content">
        <!-- Overview Row -->
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="d2-card d2-box present">
                    <div class="d2-box-title">Total Present</div>
                    <div class="d2-box-val" style="color: #3b9b65;"><?php echo isset($overview['total_present']) ? $overview['total_present'] : 0; ?></div>
                    <div class="d2-box-sub">Students physically present</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="d2-card d2-box absent">
                    <div class="d2-box-title">Total Absent</div>
                    <div class="d2-box-val" style="color: #d8456a;"><?php echo isset($overview['total_absent']) ? $overview['total_absent'] : 0; ?></div>
                    <div class="d2-box-sub">Students absent today</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="d2-card d2-box late">
                    <div class="d2-box-title">Total Late</div>
                    <div class="d2-box-val" style="color: #d09435;"><?php echo isset($overview['total_late']) ? $overview['total_late'] : 0; ?></div>
                    <div class="d2-box-sub">Arrived after start time</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="d2-card d2-box halfday">
                    <div class="d2-box-title">Half Day / Leave</div>
                    <div class="d2-box-val" style="color: #9d50ce;"><?php echo isset($overview['total_half_day']) ? $overview['total_half_day'] : 0; ?></div>
                    <div class="d2-box-sub">Excused absence</div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Charts Row -->
            <div class="col-md-6">
                <div class="d2-card" style="height: 400px;">
                    <div class="d2-title">Class-wise Absenteeism (Top 10)</div>
                    <div style="height: 300px; width: 100%;">
                        <canvas id="classAbsentChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d2-card" style="height: 400px;">
                    <div class="d2-title">7-Day Attendance Trend</div>
                    <div style="height: 300px; width: 100%;">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="d2-card">
                    <div class="d2-title">Absent Students & Follow-up Status</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover example">
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Class - Section</th>
                                    <th>Mobile No</th>
                                    <th>Guardian Phone</th>
                                    <th>Follow-up Status</th>
                                    <th>Remark</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($absent_students)) {
                                    foreach($absent_students as $student) { 
                                        $f_status = $student['followup_status'];
                                        $badge_class = 'status-pending';
                                        if($f_status == 'Contacted') $badge_class = 'status-contacted';
                                        elseif($f_status == 'Not Contacted') $badge_class = 'status-notcontacted';
                                        elseif(!empty($f_status)) $badge_class = 'status-pending';
                                        ?>
                                        <tr>
                                            <td style="font-weight:600;"><?php echo $student['firstname'] . ' ' . $student['lastname']; ?></td>
                                            <td><?php echo $student['class'] . ' - ' . $student['section']; ?></td>
                                            <td><?php echo $student['mobileno']; ?></td>
                                            <td><?php echo $student['guardian_phone']; ?></td>
                                            <td>
                                                <?php if(!empty($f_status)) { ?>
                                                    <span class="d2-pill <?php echo $badge_class; ?>"><?php echo $f_status; ?></span>
                                                <?php } else { ?>
                                                    <span class="d2-pill status-pending">Pending Follow-up</span>
                                                <?php } ?>
                                            </td>
                                            <td style="color:#666; font-size:12px; max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="<?php echo $student['followup_remark']; ?>">
                                                <?php echo $student['followup_remark']; ?>
                                            </td>
                                        </tr>
                                    <?php }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="d2-card">
                    <div class="d2-title" style="color: #d8456a;">Non-Compliant Teachers (No Attendance Submitted)</div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover example">
                            <thead>
                                <tr>
                                    <th>Class - Section</th>
                                    <th>Class Teacher</th>
                                    <th>Contact No</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($non_compliant_teachers)) {
                                    foreach($non_compliant_teachers as $teacher) { ?>
                                        <tr>
                                            <td style="font-weight:600;"><?php echo $teacher['class'] . ' - ' . $teacher['section']; ?></td>
                                            <td><?php echo (!empty($teacher['staff_name'])) ? $teacher['staff_name'] . ' ' . $teacher['staff_surname'] : '<span class="text-muted"><i>Not Assigned</i></span>'; ?></td>
                                            <td><?php echo (!empty($teacher['contact_no'])) ? $teacher['contact_no'] : '-'; ?></td>
                                            <td>
                                                <span class="d2-pill" style="background: rgba(216, 69, 106, 0.1); color: #d8456a;">Missing Submission</span>
                                            </td>
                                        </tr>
                                    <?php }
                                } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy']) ?>';
        $('.date').datepicker({
            format: date_format,
            autoclose: true,
            endDate: '+0d',
            todayHighlight: true
        });

        // Class Absent Chart
        var absentLabels = <?php echo json_encode(array_column($class_absenteeism ?? [], 'class')); ?>;
        var absentData = <?php echo json_encode(array_column($class_absenteeism ?? [], 'total_absent')); ?>;
        
        if (absentLabels.length === 0) {
            absentLabels = ['No Data'];
            absentData = [0];
        }

        var ctxAbsent = document.getElementById('classAbsentChart');
        if(ctxAbsent) {
            var ctx2dAbsent = ctxAbsent.getContext('2d');
            var barChartData = {
                labels: absentLabels,
                datasets: [
                    {
                        fillColor: "rgba(216, 69, 106, 0.8)",
                        strokeColor: "#d8456a",
                        data: absentData
                    }
                ]
            };
            new Chart(ctx2dAbsent).Bar(barChartData, {
                responsive: true,
                maintainAspectRatio: false
            });
        }

        // Trend Chart
        var trendRaw = <?php echo json_encode($trend ?? []); ?>;
        var trendLabels = [];
        var presentData = [];
        var absentTrendData = [];

        if (trendRaw.length === 0) {
            trendLabels = ['No Data'];
            presentData = [0];
            absentTrendData = [0];
        } else {
            trendLabels = trendRaw.map(function(item) { 
                var d = new Date(item.date);
                return d.getDate() + '/' + (d.getMonth()+1);
            });
            presentData = trendRaw.map(function(item) { return item.total_present; });
            absentTrendData = trendRaw.map(function(item) { return item.total_absent; });
        }

        var ctxTrend = document.getElementById('trendChart');
        if(ctxTrend) {
            var ctx2dTrend = ctxTrend.getContext('2d');
            var lineChartData = {
                labels: trendLabels,
                datasets: [
                    {
                        label: "Present Students",
                        fillColor: "rgba(102, 170, 24, 0.2)",
                        strokeColor: "#66aa18",
                        pointColor: "#66aa18",
                        pointStrokeColor: "#fff",
                        data: presentData
                    },
                    {
                        label: "Absent Students",
                        fillColor: "rgba(216, 69, 106, 0.2)",
                        strokeColor: "#d8456a",
                        pointColor: "#d8456a",
                        pointStrokeColor: "#fff",
                        data: absentTrendData
                    }
                ]
            };
            new Chart(ctx2dTrend).Line(lineChartData, {
                responsive: true,
                maintainAspectRatio: false
            });
        }
    });
</script>
