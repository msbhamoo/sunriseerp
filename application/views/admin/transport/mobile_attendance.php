<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Bus Attendance - Mobile Mode</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="theme-color" content="#1e293b">
    
    <!-- Core Bootstrap & FontAwesome CSS -->
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/font-awesome/css/font-awesome.min.css">
    <link href="<?php echo base_url(); ?>backend/toast-alert/toastr.css" rel="stylesheet"/>
    <link rel="stylesheet" href="<?php echo base_url(); ?>backend/plugins/select2/select2.min.css">
    
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        * {
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f1f5f9;
            color: #0f172a;
            margin: 0;
            padding-bottom: 90px;
            user-select: none;
        }
        /* Mobile Top App Bar */
        .mobile-navbar {
            position: sticky;
            top: 0;
            z-index: 1030;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: #fff;
            padding: 12px 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .mobile-navbar h1 {
            font-size: 16px;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .btn-desktop-return {
            background: rgba(255,255,255,0.15);
            color: #fff;
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        .btn-desktop-return:hover, .btn-desktop-return:focus {
            background: rgba(255,255,255,0.25);
            color: #fff;
            text-decoration: none;
        }

        /* Filter Tray */
        .filter-card {
            background: #ffffff;
            margin: 12px;
            padding: 14px;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0,0,0,0.06);
            border: 1px solid #e2e8f0;
        }
        .shift-pill-group {
            display: flex;
            gap: 8px;
            margin-top: 10px;
        }
        .shift-pill {
            flex: 1;
            text-align: center;
            padding: 10px;
            font-size: 14px;
            font-weight: 700;
            border-radius: 8px;
            border: 1.5px solid #cbd5e1;
            background: #fff;
            color: #475569;
            cursor: pointer;
            transition: all 0.15s;
        }
        .shift-pill.active {
            background: #2563eb;
            color: #fff;
            border-color: #2563eb;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.35);
        }

        /* Live Stat Chips */
        .stat-chips-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
            margin: 0 12px 12px 12px;
        }
        .stat-chip {
            background: #fff;
            padding: 8px 6px;
            border-radius: 8px;
            text-align: center;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .stat-chip-num {
            font-size: 17px;
            font-weight: 800;
            line-height: 1.1;
        }
        .stat-chip-lbl {
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            color: #64748b;
            margin-top: 2px;
        }

        /* Stop Section Group */
        .stop-section {
            margin: 0 12px 14px 12px;
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0,0,0,0.05);
        }
        .stop-header {
            background: #f8fafc;
            padding: 10px 14px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 700;
            font-size: 13px;
            color: #1e293b;
        }
        .stop-badge {
            background: #e0e7ff;
            color: #4338ca;
            padding: 3px 8px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        /* Student Card */
        .student-item {
            padding: 12px 14px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            flex-direction: column;
            gap: 10px;
            transition: background 0.15s;
        }
        .student-item:last-child {
            border-bottom: none;
        }
        .student-meta {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .student-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #e2e8f0;
            flex-shrink: 0;
        }
        .student-name {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            line-height: 1.2;
        }
        .student-sub {
            font-size: 12px;
            color: #64748b;
            margin-top: 2px;
        }

        /* Large Touch Attendance Buttons */
        .touch-action-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
        }
        .btn-mark {
            border: 1.5px solid #cbd5e1;
            background: #f8fafc;
            color: #475569;
            padding: 9px 4px;
            font-size: 12px;
            font-weight: 700;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.1s ease-in-out;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 3px;
        }
        .btn-mark i {
            font-size: 14px;
        }
        .btn-mark.active-present {
            background: #16a34a !important;
            color: #ffffff !important;
            border-color: #16a34a !important;
            box-shadow: 0 2px 6px rgba(22, 163, 74, 0.4) !important;
        }
        .btn-mark.active-absent {
            background: #dc2626 !important;
            color: #ffffff !important;
            border-color: #dc2626 !important;
            box-shadow: 0 2px 6px rgba(220, 38, 38, 0.4) !important;
        }
        .btn-mark.active-hostel {
            background: #9333ea !important;
            color: #ffffff !important;
            border-color: #9333ea !important;
            box-shadow: 0 2px 6px rgba(147, 51, 234, 0.4) !important;
        }
        .btn-mark.active-gatepass {
            background: #d97706 !important;
            color: #ffffff !important;
            border-color: #d97706 !important;
            box-shadow: 0 2px 6px rgba(217, 119, 6, 0.4) !important;
        }

        /* Sticky Bottom Save Tray */
        .mobile-bottom-bar {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1040;
            background: #ffffff;
            padding: 10px 16px;
            border-top: 1px solid #cbd5e1;
            box-shadow: 0 -4px 16px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }
        .btn-save-mobile {
            flex: 1;
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: #fff;
            border: none;
            padding: 12px;
            font-size: 15px;
            font-weight: 800;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(22, 163, 74, 0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-save-mobile:active {
            transform: scale(0.98);
        }
    </style>
</head>
<body>

    <!-- Mobile Top Navigation Bar -->
    <header class="mobile-navbar">
        <h1>
            <i class="fa fa-bus text-warning"></i> Bus Attendance
        </h1>
        <a href="<?php echo site_url('admin/transportattendance'); ?>" class="btn-desktop-return">
            <i class="fa fa-desktop"></i> Full View
        </a>
    </header>

    <!-- Filter Control Card -->
    <div class="filter-card">
        <form id="mobile_filter_form" action="<?php echo site_url('admin/transportattendance/mobile'); ?>" method="get">
            <div class="row">
                <div class="col-xs-6" style="padding-right:4px;">
                    <label style="font-size:11px; color:#64748b; margin-bottom:2px; font-weight:700;">BUS NUMBER</label>
                    <select class="form-control input-sm" name="vehicle_id" id="mobile_vehicle_id" onchange="this.form.submit()" style="font-weight:700; border-radius:6px; height:36px;">
                        <option value="">Select Bus</option>
                        <?php foreach ($vehiclelist as $v) { 
                            $sel = ($vehicle_id == $v['id']) ? 'selected' : '';
                        ?>
                            <option value="<?php echo $v['id']; ?>" <?php echo $sel; ?>><?php echo $v['vehicle_no']; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-xs-6" style="padding-left:4px;">
                    <label style="font-size:11px; color:#64748b; margin-bottom:2px; font-weight:700;">DATE</label>
                    <input type="date" name="date" class="form-control input-sm" value="<?php echo $date; ?>" onchange="this.form.submit()" style="font-weight:700; border-radius:6px; height:36px;">
                </div>
            </div>

            <?php if (!empty($vehicle_routes) && count($vehicle_routes) > 1) { ?>
                <div style="margin-top:8px;">
                    <label style="font-size:11px; color:#64748b; margin-bottom:2px; font-weight:700;">ROUTE (TRIP FILTER)</label>
                    <select class="form-control input-sm" name="route_id" onchange="this.form.submit()" style="font-weight:700; border-radius:6px; height:36px;">
                        <option value="">All Routes (Entire Bus)</option>
                        <?php 
                        $seen = array();
                        foreach ($vehicle_routes as $vr) {
                            $r_id = isset($vr['route_id']) ? $vr['route_id'] : $vr['vehroute_id'];
                            if (!isset($seen[$r_id])) {
                                $seen[$r_id] = true;
                                $r_sel = ($route_id == $r_id) ? 'selected' : '';
                                echo '<option value="'.$r_id.'" '.$r_sel.'>'.$vr['route_title'].'</option>';
                            }
                        }
                        ?>
                    </select>
                </div>
            <?php } ?>

            <input type="hidden" name="attendance_type" id="hidden_attendance_type" value="<?php echo strtolower($attendance_type); ?>">

            <div class="shift-pill-group">
                <div class="shift-pill <?php echo (strtolower($attendance_type) == 'morning') ? 'active' : ''; ?>" onclick="setShift('morning')">
                    <i class="fa fa-sun-o"></i> Morning Pickup
                </div>
                <div class="shift-pill <?php echo (strtolower($attendance_type) == 'evening') ? 'active' : ''; ?>" onclick="setShift('evening')">
                    <i class="fa fa-moon-o"></i> Evening Drop
                </div>
            </div>
        </form>
    </div>

    <?php if (empty($vehicle_id)) { ?>
        <div style="text-align:center; padding:40px 20px;">
            <i class="fa fa-bus text-muted" style="font-size:48px; margin-bottom:12px;"></i>
            <h4 style="font-weight:700; color:#475569;">Please Select a Bus</h4>
            <p class="text-muted" style="font-size:13px;">Select your bus from the dropdown above to begin taking stop-wise attendance.</p>
        </div>
    <?php } elseif (empty($grouped_students)) { ?>
        <div style="text-align:center; padding:40px 20px;">
            <i class="fa fa-users text-muted" style="font-size:48px; margin-bottom:12px;"></i>
            <h4 style="font-weight:700; color:#475569;">No Students Found</h4>
            <p class="text-muted" style="font-size:13px;">There are no students assigned to this bus or route.</p>
        </div>
    <?php } else { ?>

        <!-- Live Touch Counters -->
        <div class="stat-chips-grid">
            <div class="stat-chip">
                <div class="stat-chip-num text-primary" id="mobile_count_total"><?php echo $students_count; ?></div>
                <div class="stat-chip-lbl">Total</div>
            </div>
            <div class="stat-chip">
                <div class="stat-chip-num text-success" id="mobile_count_present">0</div>
                <div class="stat-chip-lbl">Present</div>
            </div>
            <div class="stat-chip">
                <div class="stat-chip-num text-danger" id="mobile_count_absent">0</div>
                <div class="stat-chip-lbl">Absent</div>
            </div>
            <div class="stat-chip">
                <div class="stat-chip-num text-warning" id="mobile_count_other">0</div>
                <div class="stat-chip-lbl">Other</div>
            </div>
        </div>

        <!-- Sticky Student Quick Search -->
        <div style="padding: 0 12px 10px 12px;">
            <div class="input-group">
                <span class="input-group-addon" style="background:#fff; border-radius:8px 0 0 8px; border-right:none;"><i class="fa fa-search text-muted"></i></span>
                <input type="text" id="mobile_search" class="form-control" placeholder="Search student name, adm no, or stop..." style="border-radius:0 8px 8px 0; border-left:none; height:38px; font-size:13px;">
            </div>
        </div>

        <!-- Attendance Form Formatted by Stops -->
        <form id="mobile_attendance_save_form" action="<?php echo site_url('admin/transportattendance/save'); ?>" method="post">
            <?php echo $this->customlib->getCSRF(); ?>
            <input type="hidden" name="date" value="<?php echo $date; ?>">
            <input type="hidden" name="vehicle_id" value="<?php echo $vehicle_id; ?>">
            <input type="hidden" name="attendance_type" value="<?php echo strtolower($attendance_type); ?>">

            <?php foreach ($grouped_students as $stop_name => $stop_students) { ?>
                <div class="stop-section">
                    <div class="stop-header">
                        <div>
                            <i class="fa fa-map-marker text-danger"></i> <?php echo $stop_name; ?>
                        </div>
                        <span class="stop-badge"><?php echo count($stop_students); ?> Students</span>
                    </div>
                    <div class="stop-body">
                        <?php foreach ($stop_students as $student) { 
                            $st_id = $student['student_session_id'];
                            $st_status = $student['attendance_status'];
                            $img_src = !empty($student['image']) ? base_url($student['image']) : base_url('uploads/student_images/no_image.png');
                        ?>
                            <div class="student-item student-card-row" data-search="<?php echo strtolower($student['firstname'].' '.$student['lastname'].' '.$student['admission_no'].' '.$stop_name); ?>">
                                <div class="student-meta">
                                    <img src="<?php echo $img_src; ?>" class="student-avatar" onerror="this.src='<?php echo base_url('uploads/student_images/no_image.png'); ?>';">
                                    <div style="flex:1;">
                                        <div class="student-name"><?php echo $student['firstname'] . ' ' . $student['lastname']; ?></div>
                                        <div class="student-sub">
                                            <strong>Adm: <?php echo $student['admission_no']; ?></strong> &bull; Class: <?php echo $student['class'].' ('.$student['section'].')'; ?>
                                            <?php if (!empty($student['has_gatepass'])) { ?>
                                                <span class="label label-warning" style="font-size:9px;"><i class="fa fa-ticket"></i> Gatepass</span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="student_session[]" value="<?php echo $st_id; ?>">
                                <input type="hidden" name="attendencetype<?php echo $st_id; ?>" id="mobile_status_<?php echo $st_id; ?>" class="mobile-status-val" value="<?php echo $st_status; ?>">

                                <div class="touch-action-row" data-session-id="<?php echo $st_id; ?>">
                                    <button type="button" class="btn-mark <?php echo ($st_status == 'Present') ? 'active-present' : ''; ?>" data-val="Present">
                                        <i class="fa fa-check"></i> Present
                                    </button>
                                    <button type="button" class="btn-mark <?php echo ($st_status == 'Absent') ? 'active-absent' : ''; ?>" data-val="Absent">
                                        <i class="fa fa-times"></i> Absent
                                    </button>
                                    <button type="button" class="btn-mark <?php echo ($st_status == 'Hostel') ? 'active-hostel' : ''; ?>" data-val="Hostel">
                                        <i class="fa fa-building"></i> Hostel
                                    </button>
                                    <button type="button" class="btn-mark <?php echo ($st_status == 'Gatepass') ? 'active-gatepass' : ''; ?>" data-val="Gatepass">
                                        <i class="fa fa-ticket"></i> Gatepass
                                    </button>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>

            <!-- Sticky Bottom Save Bar -->
            <div class="mobile-bottom-bar">
                <button type="submit" class="btn-save-mobile">
                    <i class="fa fa-cloud-upload"></i> SAVE ATTENDANCE
                </button>
            </div>
        </form>
    <?php } ?>

    <!-- Core Scripts -->
    <script src="<?php echo base_url(); ?>backend/plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="<?php echo base_url(); ?>backend/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>backend/toast-alert/toastr.js"></script>

    <script type="text/javascript">
        function setShift(shift) {
            $('#hidden_attendance_type').val(shift);
            $('#mobile_filter_form').submit();
        }

        function updateMobileCounters() {
            var total = $('.student-card-row').length;
            var present = 0, absent = 0, other = 0;

            $('.mobile-status-val').each(function() {
                var v = $(this).val();
                if (v === 'Present' || v === 'Switched Bus') present++;
                else if (v === 'Absent') absent++;
                else other++;
            });

            $('#mobile_count_total').text(total);
            $('#mobile_count_present').text(present);
            $('#mobile_count_absent').text(absent);
            $('#mobile_count_other').text(other);
        }

        $(document).ready(function() {
            updateMobileCounters();

            // Handle Large Button Tap
            $(document).on('click', '.btn-mark', function(e) {
                e.preventDefault();
                var val = $(this).data('val');
                var row = $(this).closest('.touch-action-row');
                var sessionId = row.data('session-id');

                $('#mobile_status_' + sessionId).val(val);
                row.find('.btn-mark').removeClass('active-present active-absent active-hostel active-gatepass');

                if (val === 'Present') $(this).addClass('active-present');
                else if (val === 'Absent') $(this).addClass('active-absent');
                else if (val === 'Hostel') $(this).addClass('active-hostel');
                else if (val === 'Gatepass') $(this).addClass('active-gatepass');

                updateMobileCounters();
            });

            // Live Search
            $('#mobile_search').on('keyup search', function() {
                var query = $(this).val().toLowerCase();
                $('.student-card-row').each(function() {
                    var searchData = $(this).data('search');
                    if (searchData.indexOf(query) !== -1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
        });
    </script>
</body>
</html>
