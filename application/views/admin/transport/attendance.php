<style>
/* Modern Transportation Attendance Styles */
.trans-stat-card {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 10px 14px;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 1px 3px rgba(0,0,0,0.04);
    transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.trans-stat-card:hover {
    box-shadow: 0 3px 6px rgba(0,0,0,0.07);
}
.trans-stat-card .stat-icon {
    width: 36px;
    height: 36px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
}
.trans-stat-card.stat-total { border-left: 4px solid #3b82f6; }
.trans-stat-card.stat-total .stat-icon { background: #eff6ff; color: #2563eb; }

.trans-stat-card.stat-present { border-left: 4px solid #10b981; }
.trans-stat-card.stat-present .stat-icon { background: #ecfdf5; color: #059669; }

.trans-stat-card.stat-absent { border-left: 4px solid #ef4444; }
.trans-stat-card.stat-absent .stat-icon { background: #fef2f2; color: #dc2626; }

.trans-stat-card.stat-other { border-left: 4px solid #f59e0b; }
.trans-stat-card.stat-other .stat-icon { background: #fffbeb; color: #d97706; }

.trans-stat-val {
    font-size: 18px;
    font-weight: 700;
    line-height: 1.2;
    color: #1e293b;
}
.trans-stat-lbl {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.4px;
    color: #64748b;
    margin: 0;
}

/* Unified Action & Filter Toolbar */
.trans-toolbar-box {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 12px 14px;
    margin-bottom: 15px;
}
.trans-toolbar-row {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
}
.trans-toolbar-row + .trans-toolbar-row {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px dashed #e2e8f0;
}

.btn-cta-action {
    font-size: 12px !important;
    font-weight: 600 !important;
    padding: 5px 12px !important;
    border-radius: 5px !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 5px !important;
    transition: all 0.15s ease-in-out !important;
    border: 1px solid transparent !important;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important;
}
.btn-cta-present {
    background-color: #10b981 !important;
    color: #ffffff !important;
}
.btn-cta-present:hover {
    background-color: #059669 !important;
    color: #ffffff !important;
}

.btn-cta-absent {
    background-color: #ef4444 !important;
    color: #ffffff !important;
}
.btn-cta-absent:hover {
    background-color: #dc2626 !important;
    color: #ffffff !important;
}

/* Filter Chips */
.filter-chip-group {
    display: inline-flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 5px;
}
.filter-chip {
    font-size: 11px;
    font-weight: 600;
    padding: 4px 9px;
    border-radius: 15px;
    background: #ffffff;
    border: 1px solid #cbd5e1;
    color: #475569;
    cursor: pointer;
    transition: all 0.15s ease;
    display: inline-flex;
    align-items: center;
    gap: 3px;
    user-select: none;
}
.filter-chip:hover {
    background: #f1f5f9;
    color: #1e293b;
    border-color: #94a3b8;
}
.filter-chip.active {
    background: #2563eb;
    color: #ffffff;
    border-color: #2563eb;
}
.filter-chip .badge-count {
    background: rgba(0,0,0,0.08);
    border-radius: 10px;
    padding: 1px 5px;
    font-size: 10px;
}
.filter-chip.active .badge-count {
    background: rgba(255,255,255,0.25);
    color: #ffffff;
}

/* Search Box Container */
.search-box-wrap {
    position: relative;
    width: 100%;
    max-width: 420px;
}
.search-box-wrap input {
    width: 100%;
    height: 32px;
    padding-left: 32px;
    padding-right: 28px;
    font-size: 12px;
    border-radius: 6px;
    border: 1px solid #cbd5e1;
    background: #ffffff;
    transition: border-color 0.15s ease;
}
.search-box-wrap input:focus {
    border-color: #3b82f6;
    outline: none;
    box-shadow: 0 0 0 2px rgba(59,130,246,0.15);
}
.search-box-wrap .search-icon {
    position: absolute;
    left: 10px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 12px;
    color: #94a3b8;
    pointer-events: none;
}
.search-box-wrap .clear-icon {
    position: absolute;
    right: 9px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 13px;
    color: #94a3b8;
    cursor: pointer;
    display: none;
}
.search-box-wrap .clear-icon:hover {
    color: #475569;
}

/* Inline Segmented Attendance Action Buttons */
.status-toggle-group {
    display: inline-flex;
    background: #f1f5f9;
    padding: 2px;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    gap: 2px;
    white-space: nowrap;
}

.btn-touch-status {
    font-weight: 600 !important;
    padding: 4px 9px !important;
    font-size: 11px !important;
    border-radius: 4px !important;
    margin: 0 !important;
    border: none !important;
    background: transparent !important;
    color: #64748b !important;
    transition: all 0.12s ease-in-out !important;
    outline: none !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 3px !important;
}
.btn-touch-status:hover {
    background-color: #e2e8f0 !important;
    color: #1e293b !important;
}

.btn-present-active {
    background-color: #10b981 !important;
    color: #ffffff !important;
    box-shadow: 0 1px 2px rgba(16, 185, 129, 0.35) !important;
}
.btn-present-active:hover {
    background-color: #059669 !important;
    color: #ffffff !important;
}

.btn-absent-active {
    background-color: #ef4444 !important;
    color: #ffffff !important;
    box-shadow: 0 1px 2px rgba(239, 68, 68, 0.35) !important;
}
.btn-absent-active:hover {
    background-color: #dc2626 !important;
    color: #ffffff !important;
}

.btn-hostel-active {
    background-color: #8b5cf6 !important;
    color: #ffffff !important;
    box-shadow: 0 1px 2px rgba(139, 92, 246, 0.35) !important;
}
.btn-hostel-active:hover {
    background-color: #7c3aed !important;
    color: #ffffff !important;
}

.btn-gatepass-active {
    background-color: #f59e0b !important;
    color: #ffffff !important;
    box-shadow: 0 1px 2px rgba(245, 158, 11, 0.35) !important;
}
.btn-gatepass-active:hover {
    background-color: #d97706 !important;
    color: #ffffff !important;
}

/* Sortable Headers */
.sortable-th {
    cursor: pointer;
    user-select: none;
    position: relative;
    transition: background-color 0.15s ease;
}
.sortable-th:hover {
    background-color: #f1f5f9 !important;
    color: #2563eb;
}
.sort-icon {
    font-size: 11px;
    margin-left: 5px;
    color: #94a3b8;
}
.sortable-th.sorted-asc .sort-icon {
    color: #2563eb;
}
.sortable-th.sorted-desc .sort-icon {
    color: #2563eb;
}

/* Table Enhancements */
.trans-table {
    border-collapse: separate;
    border-spacing: 0;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    overflow: hidden;
}
.trans-table thead th {
    background: #f8fafc;
    color: #475569;
    font-weight: 700;
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #cbd5e1 !important;
    padding: 10px 10px !important;
    vertical-align: middle !important;
}
.trans-table tbody tr:hover {
    background-color: #f8fafc !important;
}
.trans-table td {
    padding: 8px 10px !important;
    vertical-align: middle !important;
    border-top: 1px solid #edf2f7 !important;
}

/* Sticky Save Footer */
.sticky-save-bar {
    position: sticky;
    bottom: 0;
    background: rgba(255, 255, 255, 0.96);
    backdrop-filter: blur(8px);
    padding: 10px 18px;
    border-top: 2px solid #3b82f6;
    box-shadow: 0 -4px 12px rgba(0,0,0,0.08);
    z-index: 1020;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 0 0 6px 6px;
}

@media (max-width: 991px) {
    .trans-toolbar-row {
        flex-direction: column;
        align-items: stretch;
    }
    .search-box-wrap {
        max-width: 100%;
    }
}
@media (max-width: 768px) {
    .sticky-save-bar {
        flex-direction: column;
        gap: 8px;
        text-align: center;
    }
    .status-toggle-group {
        display: flex;
        width: 100%;
    }
    .btn-touch-status {
        flex: 1;
        justify-content: center;
        padding: 6px 4px !important;
        font-size: 10px !important;
    }
}
</style>

<div class="content-wrapper">
    <section class="content-header" style="display:flex; justify-content:space-between; align-items:center; padding: 12px 15px;">
        <h1 style="margin:0; font-size:20px; font-weight:700; color:#1e293b;">
            <i class="fa fa-bus text-primary" style="margin-right:6px;"></i> <?php echo $this->lang->line('transport'); ?> Attendance
        </h1>
        <div>
            <a href="<?php echo site_url('admin/transportattendance/mobile'); ?>" class="btn btn-sm btn-default" style="font-weight:600; border-radius:6px; border-color:#cbd5e1; box-shadow:0 1px 2px rgba(0,0,0,0.05); color:#334155;">
                <i class="fa fa-mobile-phone fa-lg text-primary"></i> Mobile Quick Mode
            </a>
        </div>
    </section>
    
    <section class="content" style="padding-top: 5px;">
        <div class="row">
            <div class="col-md-12">
                <!-- Search Criteria Box -->
                <div class="box box-primary" style="border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.05); margin-bottom:15px;">
                    <div class="box-header with-border" style="padding:10px 15px;">
                        <h3 class="box-title" style="font-size:14px; font-weight:700; color:#334155;"><i class="fa fa-sliders"></i> Select Criteria</h3>
                    </div>
                    <form id="attendance_form" action="<?php echo site_url('admin/transportattendance/index'); ?>" method="post">
                        <div class="box-body" style="padding:12px 15px 5px 15px;">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group" style="margin-bottom:10px;">
                                        <label for="date" style="font-size:12px; font-weight:600; color:#475569;"><?php echo $this->lang->line('date'); ?></label> <small class="req"> *</small>
                                        <input type="text" id="date" name="date" class="form-control date input-sm" value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat())); ?>" autocomplete="off" style="border-radius:4px;" />
                                        <span class="text-danger"><?php echo form_error('date'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group" style="margin-bottom:10px;">
                                        <label style="font-size:12px; font-weight:600; color:#475569;">Vehicle / Bus</label> <small class="req"> *</small>
                                        <select class="form-control select2 input-sm" name="vehicle_id" id="vehicle_id" style="width:100%;">
                                            <option value="">Select Bus</option>
                                            <?php 
                                             $auto_select_single = (isset($vehiclelist) && count($vehiclelist) == 1);
                                             foreach ($vehiclelist as $vehicle) { 
                                                 $selected = set_select('vehicle_id', $vehicle['id'], (isset($vehicle_id) && $vehicle_id == $vehicle['id']));
                                                 if (empty($selected) && $auto_select_single && !isset($_POST['vehicle_id'])) {
                                                     $selected = 'selected="selected"';
                                                 }
                                             ?>
                                                 <option value="<?php echo $vehicle['id'] ?>" <?php echo $selected; ?>><?php echo $vehicle['vehicle_no'] ?></option>
                                             <?php } ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('vehicle_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group" style="margin-bottom:10px;">
                                        <label style="font-size:12px; font-weight:600; color:#475569;">Route (Route-Wise Attendance)</label>
                                        <select class="form-control select2 input-sm" name="route_id" id="route_id" style="width:100%;">
                                            <option value="">All Routes (or Select Route)</option>
                                            <?php 
                                            if (isset($all_routes) && !empty($all_routes)) {
                                                foreach ($all_routes as $r) {
                                                    $r_selected = (isset($route_id) && $route_id == $r['id']) ? 'selected="selected"' : '';
                                                    echo '<option value="' . $r['id'] . '" ' . $r_selected . '>' . $r['route_title'] . '</option>';
                                                }
                                            } elseif (isset($vehicle_routes) && !empty($vehicle_routes)) {
                                                $seen_routes = array();
                                                foreach ($vehicle_routes as $vr) {
                                                    $curr_r_id = isset($vr['route_id']) ? $vr['route_id'] : $vr['vehroute_id'];
                                                    if (!isset($seen_routes[$curr_r_id])) {
                                                        $seen_routes[$curr_r_id] = true;
                                                        $r_selected = (isset($route_id) && $route_id == $curr_r_id) ? 'selected="selected"' : '';
                                                        echo '<option value="' . $curr_r_id . '" ' . $r_selected . '>' . $vr['route_title'] . '</option>';
                                                    }
                                                }
                                            }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('route_id'); ?></span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group" style="margin-bottom:10px;">
                                        <label style="font-size:12px; font-weight:600; color:#475569;">Attendance Shift</label> <small class="req"> *</small>
                                        <select class="form-control select2 input-sm" name="attendance_type" style="width:100%;">
                                            <option value="">Select Shift</option>
                                            <option value="morning" <?php echo set_select('attendance_type', 'morning', (isset($attendance_type) && $attendance_type == 'morning')); ?>>Morning Shift</option>
                                            <option value="evening" <?php echo set_select('attendance_type', 'evening', (isset($attendance_type) && $attendance_type == 'evening')); ?>>Evening Shift</option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('attendance_type'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer" style="background:#fafbfc; border-top:1px solid #f1f5f9; padding:8px 15px; border-radius:0 0 8px 8px;">
                            <button type="submit" name="search" value="search" class="btn btn-primary pull-right btn-sm" style="font-weight:600; padding:4px 14px; border-radius:4px;">
                                <i class="fa fa-search"></i> Search Students
                            </button>
                        </div>
                    </form>
                </div>
                
                <?php if (isset($resultlist)) { ?>
                    <div class="box box-solid" id="attendance" style="border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.05); border:1px solid #e2e8f0;">
                        <div class="box-header" style="background:#ffffff; border-bottom:1px solid #e2e8f0; padding:12px 16px; border-radius:8px 8px 0 0; display:flex; justify-content:space-between; align-items:center;">
                            <h3 class="box-title" style="font-size:15px; font-weight:700; color:#1e293b; margin:0;">
                                <i class="fa fa-users text-primary" style="margin-right:5px;"></i> Student List
                            </h3>
                            <div class="box-tools">
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#customRiderModal" style="font-weight:600; font-size:12px; border-radius:6px; padding:4px 12px; box-shadow:0 1px 2px rgba(37,99,235,0.2);">
                                    <i class="fa fa-user-plus"></i> Add Custom Rider
                                </button>
                            </div>
                        </div>
                        <div class="box-body" style="padding:15px;">
                            <?php if ($this->session->flashdata('msg')) { ?>
                                <?php echo $this->session->flashdata('msg') ?>
                            <?php } ?>
                            
                            <?php if (empty($resultlist)) { ?>
                                <div class="alert alert-info" style="border-radius:6px;"><i class="fa fa-info-circle"></i> No students found for the selected vehicle and criteria.</div>
                            <?php } else { ?>

                                <!-- Compact Metric Summary Cards -->
                                <div class="row" style="margin-bottom:6px;">
                                    <div class="col-xs-6 col-sm-3">
                                        <div class="trans-stat-card stat-total">
                                            <div>
                                                <div class="trans-stat-val" id="count_total">0</div>
                                                <p class="trans-stat-lbl">Total Students</p>
                                            </div>
                                            <div class="stat-icon"><i class="fa fa-users"></i></div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-3">
                                        <div class="trans-stat-card stat-present">
                                            <div>
                                                <div class="trans-stat-val" id="count_present" style="color:#059669;">0</div>
                                                <p class="trans-stat-lbl">Present</p>
                                            </div>
                                            <div class="stat-icon"><i class="fa fa-check"></i></div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-3">
                                        <div class="trans-stat-card stat-absent">
                                            <div>
                                                <div class="trans-stat-val" id="count_absent" style="color:#dc2626;">0</div>
                                                <p class="trans-stat-lbl">Absent</p>
                                            </div>
                                            <div class="stat-icon"><i class="fa fa-times"></i></div>
                                        </div>
                                    </div>
                                    <div class="col-xs-6 col-sm-3">
                                        <div class="trans-stat-card stat-other">
                                            <div>
                                                <div class="trans-stat-val" id="count_other" style="color:#d97706;">0</div>
                                                <p class="trans-stat-lbl">Gatepass / Other</p>
                                            </div>
                                            <div class="stat-icon"><i class="fa fa-ticket"></i></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Unified Action & Filter Toolbar -->
                                <div class="trans-toolbar-box">
                                    <!-- Row 1: Actions, Filters & Sorting -->
                                    <div class="trans-toolbar-row">
                                        <!-- Left: Bulk CTAs -->
                                        <div style="display:flex; align-items:center; gap:6px;">
                                            <button type="button" class="btn btn-cta-action btn-cta-present" onclick="markAllStatus('Present')" title="Mark all listed students as Present">
                                                <i class="fa fa-check-square-o"></i> Mark All Present
                                            </button>
                                            <button type="button" class="btn btn-cta-action btn-cta-absent" onclick="markAllStatus('Absent')" title="Mark all listed students as Absent">
                                                <i class="fa fa-minus-square-o"></i> Mark All Absent
                                            </button>
                                        </div>

                                        <!-- Center: Quick Status Filter Pills -->
                                        <div class="filter-chip-group">
                                            <span style="font-size:11px; font-weight:700; color:#64748b; margin-right:3px;">Filter:</span>
                                            <span class="filter-chip active" data-filter="all">All <span class="badge-count chip-count-all"><?php echo count($resultlist); ?></span></span>
                                            <span class="filter-chip" data-filter="Present">Present <span class="badge-count chip-count-present">0</span></span>
                                            <span class="filter-chip" data-filter="Absent">Absent <span class="badge-count chip-count-absent">0</span></span>
                                            <span class="filter-chip" data-filter="Other">Gatepass/Hostel <span class="badge-count chip-count-other">0</span></span>
                                        </div>

                                        <!-- Right: Quick Sort Dropdown -->
                                        <div style="display:flex; align-items:center; gap:6px;">
                                            <span style="font-size:11px; font-weight:700; color:#64748b;"><i class="fa fa-sort"></i> Sort:</span>
                                            <select id="quick_sort_select" class="form-control input-sm" style="width:170px; font-size:11px; height:30px; padding:3px 8px; border-radius:5px; border-color:#cbd5e1; font-weight:600;">
                                                <option value="name_asc" selected>Student Name (A &rarr; Z)</option>
                                                <option value="name_desc">Student Name (Z &rarr; A)</option>
                                                <option value="adm_asc">Adm No (Low - High)</option>
                                                <option value="adm_desc">Adm No (High - Low)</option>
                                                <option value="stop_asc">Bus Stop (A &rarr; Z)</option>
                                                <option value="class_asc">Class (A &rarr; Z)</option>
                                                <option value="status_present">Status (Present first)</option>
                                                <option value="status_absent">Status (Absent first)</option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- Row 2: Live Search & Counter info -->
                                    <div class="trans-toolbar-row" style="justify-content:space-between;">
                                        <div class="search-box-wrap">
                                            <i class="fa fa-search search-icon"></i>
                                            <input type="text" id="touch_student_search" placeholder="Search student name, adm no, route, or bus stop...">
                                            <i class="fa fa-times-circle clear-icon" id="clear_search_btn" title="Clear search"></i>
                                        </div>
                                        <div style="font-size:12px; font-weight:600; color:#64748b;">
                                            <span id="filtered_info">Showing all <?php echo count($resultlist); ?> students (Sorted Alphabetically)</span>
                                        </div>
                                    </div>
                                </div>

                                <form id="save_bus_attendance_form" action="<?php echo site_url('admin/transportattendance/save') ?>" method="post">
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <input type="hidden" name="date" value="<?php echo date('Y-m-d', strtotime($date)); ?>">
                                    <input type="hidden" name="vehicle_id" value="<?php echo $vehicle_id; ?>">
                                    <input type="hidden" name="attendance_type" value="<?php echo $attendance_type; ?>">
                                    
                                    <div class="table-responsive p-b-20" style="border:1px solid #e2e8f0; border-radius:6px;">
                                        <table class="table table-hover trans-table" id="attendance_table" style="margin-bottom:0;">
                                            <thead>
                                                <tr>
                                                    <th class="sortable-th" data-sort="adm" style="width:10%;">
                                                        Adm No <i class="fa fa-sort sort-icon"></i>
                                                    </th>
                                                    <th class="sortable-th sorted-asc" data-sort="name" style="width:30%;">
                                                        Student & Stop Info <i class="fa fa-sort-alpha-asc sort-icon"></i>
                                                    </th>
                                                    <th class="sortable-th" data-sort="class" style="width:12%;">
                                                        Class (Section) <i class="fa fa-sort sort-icon"></i>
                                                    </th>
                                                    <th class="sortable-th" data-sort="opp_status" style="width:16%;">
                                                        <?php echo isset($opposite_shift) ? $opposite_shift . ' Shift Status' : 'Morning Status'; ?> <i class="fa fa-sort sort-icon"></i>
                                                    </th>
                                                    <th class="sortable-th" data-sort="status" style="width:20%;">
                                                        Attendance Action <i class="fa fa-sort sort-icon"></i>
                                                    </th>
                                                    <th style="width:12%;">Remark</th>
                                                </tr>
                                            </thead>
                                            <tbody id="student_list_tbody">
                                                <?php foreach ($resultlist as $student) { 
                                                    $st_val = isset($student['attendance_status']) ? $student['attendance_status'] : 'Present';
                                                    $is_custom = (isset($student['status']) && $student['status'] == 'Switched Bus');
                                                    if ($is_custom) {
                                                        $st_val = 'Present';
                                                    }
                                                    $full_name = trim($student['firstname'] . ' ' . $student['lastname']);
                                                    $stop_title = !empty($student['pickup_point_name']) ? $student['pickup_point_name'] : (!empty($student['route_title']) ? $student['route_title'] : '');
                                                    $class_sec = $student['class'] . ' (' . $student['section'] . ')';
                                                    $opp_status = isset($student['opposite_shift_status']) ? $student['opposite_shift_status'] : 'Not Marked';
                                                ?>
                                                    <tr class="student-row" 
                                                        data-adm="<?php echo (int)preg_replace('/[^0-9]/', '', $student['admission_no']); ?>" 
                                                        data-adm-raw="<?php echo htmlspecialchars($student['admission_no']); ?>" 
                                                        data-name="<?php echo htmlspecialchars(strtolower($full_name)); ?>" 
                                                        data-stop="<?php echo htmlspecialchars(strtolower($stop_title)); ?>" 
                                                        data-class="<?php echo htmlspecialchars(strtolower($class_sec)); ?>" 
                                                        data-status="<?php echo htmlspecialchars($st_val); ?>"
                                                        data-opp-status="<?php echo htmlspecialchars(strtolower($opp_status)); ?>"
                                                        data-custom="<?php echo $is_custom ? 'yes' : 'no'; ?>">
                                                        
                                                        <td style="vertical-align:middle; font-weight:700; color:#1e293b;">
                                                            <?php echo $student['admission_no']; ?>
                                                            <input type="hidden" name="student_session[]" value="<?php echo $student['student_session_id']; ?>">
                                                            <?php if ($is_custom) { ?>
                                                                <input type="hidden" name="is_custom_rider<?php echo $student['student_session_id']; ?>" value="yes">
                                                            <?php } ?>
                                                        </td>

                                                        <td style="vertical-align:middle;">
                                                            <div style="display:flex; align-items:center; gap:9px;">
                                                                <?php 
                                                                $img_src = !empty($student['image']) ? base_url($student['image']) : base_url('uploads/student_images/no_image.png');
                                                                ?>
                                                                <img src="<?php echo $img_src; ?>" class="img-circle" style="width:34px; height:34px; object-fit:cover; border:1px solid #cbd5e1; flex-shrink:0;" onerror="this.src='<?php echo base_url('uploads/student_images/no_image.png'); ?>';">
                                                                <div style="min-width:0; flex-grow:1;">
                                                                    <div style="font-size:13px; font-weight:700; color:#1e293b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                                                        <?php echo $full_name; ?>
                                                                    </div>
                                                                    <?php if (!empty($student['route_title']) || !empty($student['pickup_point_name'])) { ?>
                                                                        <div style="font-size:11px; color:#64748b; margin-top:2px; display:flex; flex-wrap:wrap; gap:6px;">
                                                                            <?php if (!empty($student['route_title'])) { ?>
                                                                                <span style="color:#2563eb;"><i class="fa fa-road"></i> <strong><?php echo $student['route_title']; ?></strong></span>
                                                                            <?php } ?>
                                                                            <?php if (!empty($student['pickup_point_name'])) { ?>
                                                                                <span style="color:#475569;"><i class="fa fa-map-marker text-danger"></i> Stop: <strong><?php echo $student['pickup_point_name']; ?></strong></span>
                                                                            <?php } ?>
                                                                        </div>
                                                                    <?php } ?>
                                                                    <?php if (!empty($student['has_gatepass'])) { ?>
                                                                        <span class="label" style="background:#fffbeb; color:#d97706; border:1px solid #fde68a; font-size:10px; margin-top:2px; display:inline-block; padding:1px 5px;"><i class="fa fa-ticket"></i> Gatepass Issued Today</span>
                                                                    <?php } ?>
                                                                    <?php if ($is_custom) { 
                                                                        if (!empty($student['original_vehicle_no'])) {
                                                                            $rider_type = 'Custom Rider (' . $student['original_vehicle_no'] . ')';
                                                                        } elseif (!empty($student['hostel_room_id']) && $student['hostel_room_id'] > 0) {
                                                                            $rider_type = 'Custom Rider (Hosteler)';
                                                                        } else {
                                                                            $rider_type = 'Custom Rider (Day Scholar)';
                                                                        }
                                                                    ?>
                                                                        <span class="label" style="background:#eff6ff; color:#2563eb; border:1px solid #bfdbfe; font-size:10px; margin-top:2px; display:inline-block; padding:1px 5px;"><?php echo $rider_type; ?></span>
                                                                    <?php } ?>
                                                                </div>
                                                            </div>
                                                        </td>

                                                        <td style="vertical-align:middle; font-size:12px; color:#334155; font-weight:600;">
                                                            <?php echo $class_sec; ?>
                                                        </td>

                                                        <td style="vertical-align:middle;">
                                                            <?php
                                                            $opp_title = isset($opposite_shift) ? $opposite_shift : 'Morning';
                                                            if ($opp_status == 'Present') {
                                                                echo '<span class="label" style="background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; font-size:11px; padding:3px 7px;"><i class="fa fa-check"></i> ' . $opp_title . ': Present</span>';
                                                            } elseif ($opp_status == 'Absent') {
                                                                echo '<span class="label" style="background:#fef2f2; color:#dc2626; border:1px solid #fecaca; font-size:11px; padding:3px 7px;"><i class="fa fa-times"></i> ' . $opp_title . ': Absent</span>';
                                                            } elseif ($opp_status == 'Switched Bus') {
                                                                echo '<span class="label" style="background:#eff6ff; color:#2563eb; border:1px solid #bfdbfe; font-size:11px; padding:3px 7px;"><i class="fa fa-exchange"></i> ' . $opp_title . ': Switched</span>';
                                                            } elseif ($opp_status == 'Gatepass') {
                                                                echo '<span class="label" style="background:#fffbeb; color:#d97706; border:1px solid #fde68a; font-size:11px; padding:3px 7px;"><i class="fa fa-ticket"></i> ' . $opp_title . ': Gatepass</span>';
                                                            } elseif ($opp_status == 'Hostel') {
                                                                echo '<span class="label" style="background:#f5f3ff; color:#7c3aed; border:1px solid #ddd6fe; font-size:11px; padding:3px 7px;"><i class="fa fa-building"></i> ' . $opp_title . ': Hostel</span>';
                                                            } elseif (strpos($opp_status, 'Present') !== false) {
                                                                echo '<span class="label" style="background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; font-size:11px; padding:3px 7px;"><i class="fa fa-bus"></i> ' . $opp_title . ': ' . $opp_status . '</span>';
                                                            } else {
                                                                echo '<span class="label" style="background:#f1f5f9; color:#64748b; border:1px solid #e2e8f0; font-size:11px; padding:3px 7px;">' . $opp_title . ': Not Marked</span>';
                                                            }
                                                            ?>
                                                        </td>

                                                        <td style="vertical-align:middle;">
                                                            <?php if ($is_custom) { ?>
                                                                <input type="hidden" name="attendencetype<?php echo $student['student_session_id']; ?>" value="Switched Bus" class="attendencetype-field">
                                                                <input type="hidden" name="is_custom_rider<?php echo $student['student_session_id']; ?>" value="yes">
                                                                <span class="label" style="background:#ecfdf5; color:#059669; border:1px solid #a7f3d0; font-size:11px; padding:5px 8px; display:inline-block; font-weight:600;"><i class="fa fa-check"></i> Custom Rider (Present)</span>
                                                            <?php } else { ?>
                                                                <input type="hidden" name="attendencetype<?php echo $student['student_session_id']; ?>" id="attendencetype_<?php echo $student['student_session_id']; ?>" class="attendencetype-field" value="<?php echo $st_val; ?>">
                                                                <div class="status-toggle-group" data-session-id="<?php echo $student['student_session_id']; ?>">
                                                                    <button type="button" class="btn btn-touch-status <?php echo ($st_val == 'Present') ? 'btn-present-active' : ''; ?>" data-value="Present" title="Present">
                                                                        <i class="fa fa-check"></i> Present
                                                                    </button>
                                                                    <button type="button" class="btn btn-touch-status <?php echo ($st_val == 'Absent') ? 'btn-absent-active' : ''; ?>" data-value="Absent" title="Absent">
                                                                        <i class="fa fa-times"></i> Absent
                                                                    </button>
                                                                    <button type="button" class="btn btn-touch-status <?php echo ($st_val == 'Hostel') ? 'btn-hostel-active' : ''; ?>" data-value="Hostel" title="Hostel">
                                                                        Hostel
                                                                    </button>
                                                                    <button type="button" class="btn btn-touch-status <?php echo ($st_val == 'Gatepass') ? 'btn-gatepass-active' : ''; ?>" data-value="Gatepass" title="Gatepass">
                                                                        Gatepass
                                                                    </button>
                                                                </div>
                                                            <?php } ?>
                                                        </td>

                                                        <td style="vertical-align:middle;">
                                                            <div style="display: flex; align-items: center; gap: 4px;">
                                                                <input type="text" name="remark<?php echo $student['student_session_id']; ?>" class="form-control input-sm" value="<?php echo htmlspecialchars($student['remark']); ?>" placeholder="Remark..." style="font-size:11px; height:28px; border-radius:4px; border-color:#cbd5e1;">
                                                                <?php if ($is_custom) { ?>
                                                                    <button type="button" class="btn btn-default btn-xs" title="Remove custom rider" onclick="removeCustomRider(<?php echo $student['student_session_id']; ?>, this)" style="color:#ef4444; border-color:#fecaca; padding:4px 6px;">
                                                                        <i class="fa fa-trash"></i>
                                                                    </button>
                                                                <?php } ?>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <!-- Sticky Bottom Save Action Bar -->
                                    <div class="sticky-save-bar">
                                        <div style="display:flex; align-items:center; gap:8px;">
                                            <span style="font-size:13px; font-weight:700; color:#1e293b;"><i class="fa fa-bar-chart text-primary"></i> Attendance Summary:</span>
                                            <span id="sticky_summary" style="font-size:13px; font-weight:700; color:#059669;">0 Present, 0 Absent</span>
                                        </div>
                                        <button type="submit" class="btn btn-success" style="font-weight:700; padding:6px 22px; font-size:13px; border-radius:6px; background:#10b981; border-color:#059669; box-shadow:0 2px 4px rgba(16,185,129,0.3);">
                                            <i class="fa fa-save"></i> Save Attendance
                                        </button>
                                    </div>
                                </form>
                            <?php } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
</div>

<!-- Custom Rider Modal -->
<div id="customRiderModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius:8px; overflow:hidden;">
            <div class="modal-header" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; padding:12px 18px;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title" style="font-weight:700; font-size:16px; color:#1e293b;"><i class="fa fa-user-plus text-primary"></i> Add Custom Rider</h4>
            </div>
            <div class="modal-body" style="padding:18px;">
                <div class="form-group">
                    <label style="font-size:12px; font-weight:600; color:#475569;">Search Student (Name or Admission No)</label>
                    <input type="text" id="search_student_text" class="form-control input-sm" placeholder="Type name or adm no..." style="border-radius:4px;">
                </div>
                <div id="search_results" style="max-height: 250px; overflow-y: auto; border:1px solid #e2e8f0; border-radius:6px; display:none;">
                    
                </div>
            </div>
            <div class="modal-footer" style="background:#fafbfc; border-top:1px solid #f1f5f9; padding:10px 18px;">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" style="border-radius:4px; font-weight:600;">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
// Default sorting: Alphabetical by Student Name
var currentSortKey = 'name';
var currentSortOrder = 'asc';
var currentFilter = 'all';

function updateCounters() {
    var total = $('.student-row').length;
    var present = 0;
    var absent = 0;
    var other = 0;

    $('.attendencetype-field').each(function() {
        var val = $(this).val();
        if (val === 'Present' || val === 'Switched Bus') present++;
        else if (val === 'Absent') absent++;
        else other++;
    });

    $('#count_total').text(total);
    $('#count_present').text(present);
    $('#count_absent').text(absent);
    $('#count_other').text(other);

    $('.chip-count-all').text(total);
    $('.chip-count-present').text(present);
    $('.chip-count-absent').text(absent);
    $('.chip-count-other').text(other);

    $('#sticky_summary').text(present + ' Present, ' + absent + ' Absent' + (other > 0 ? ', ' + other + ' Other' : ''));
}

function markAllStatus(status) {
    $('.status-toggle-group').each(function() {
        var sessionId = $(this).data('session-id');
        var row = $(this).closest('.student-row');
        $('#attendencetype_' + sessionId).val(status);
        row.attr('data-status', status);
        
        $(this).find('.btn-touch-status').removeClass('btn-present-active btn-absent-active btn-hostel-active btn-gatepass-active');
        if (status === 'Present') {
            $(this).find('[data-value="Present"]').addClass('btn-present-active');
        } else if (status === 'Absent') {
            $(this).find('[data-value="Absent"]').addClass('btn-absent-active');
        }
    });
    updateCounters();
    applyFilterAndSearch();
}

function sortTable(key, order) {
    var $tbody = $('#student_list_tbody');
    var $rows = $tbody.find('tr.student-row').get();

    $rows.sort(function(a, b) {
        var valA, valB;
        if (key === 'name') {
            valA = ($(a).attr('data-name') || '').trim();
            valB = ($(b).attr('data-name') || '').trim();
            return order === 'asc' ? valA.localeCompare(valB) : valB.localeCompare(valA);
        } else if (key === 'adm') {
            valA = parseInt($(a).attr('data-adm')) || 0;
            valB = parseInt($(b).attr('data-adm')) || 0;
            return order === 'asc' ? valA - valB : valB - valA;
        } else if (key === 'stop') {
            valA = ($(a).attr('data-stop') || '').trim();
            valB = ($(b).attr('data-stop') || '').trim();
            return order === 'asc' ? valA.localeCompare(valB) : valB.localeCompare(valA);
        } else if (key === 'class') {
            valA = ($(a).attr('data-class') || '').trim();
            valB = ($(b).attr('data-class') || '').trim();
            return order === 'asc' ? valA.localeCompare(valB) : valB.localeCompare(valA);
        } else if (key === 'opp_status') {
            valA = ($(a).attr('data-opp-status') || '').trim();
            valB = ($(b).attr('data-opp-status') || '').trim();
            return order === 'asc' ? valA.localeCompare(valB) : valB.localeCompare(valA);
        } else if (key === 'status') {
            valA = ($(a).attr('data-status') || '').trim();
            valB = ($(b).attr('data-status') || '').trim();
            return order === 'asc' ? valA.localeCompare(valB) : valB.localeCompare(valA);
        }
        return 0;
    });

    $.each($rows, function(idx, row) {
        $tbody.append(row);
    });

    // Update Header Icons
    $('.sortable-th').removeClass('sorted-asc sorted-desc');
    $('.sortable-th .sort-icon').removeClass('fa-sort-asc fa-sort-desc fa-sort-numeric-asc fa-sort-numeric-desc fa-sort-alpha-asc fa-sort-alpha-desc').addClass('fa-sort');

    var $activeTh = $('.sortable-th[data-sort="' + key + '"]');
    $activeTh.addClass(order === 'asc' ? 'sorted-asc' : 'sorted-desc');
    var $icon = $activeTh.find('.sort-icon');
    $icon.removeClass('fa-sort');
    if (key === 'name') {
        $icon.addClass(order === 'asc' ? 'fa-sort-alpha-asc' : 'fa-sort-alpha-desc');
    } else if (key === 'adm') {
        $icon.addClass(order === 'asc' ? 'fa-sort-numeric-asc' : 'fa-sort-numeric-desc');
    } else {
        $icon.addClass(order === 'asc' ? 'fa-sort-asc' : 'fa-sort-desc');
    }

    currentSortKey = key;
    currentSortOrder = order;

    // Sync dropdown value if matched
    var matchingVal = key + '_' + order;
    if ($('#quick_sort_select option[value="' + matchingVal + '"]').length > 0) {
        $('#quick_sort_select').val(matchingVal);
    } else if (key === 'status') {
        $('#quick_sort_select').val(order === 'asc' ? 'status_present' : 'status_absent');
    }
}

function applyFilterAndSearch() {
    var query = $('#touch_student_search').val().toLowerCase().trim();
    var filter = currentFilter;
    var visibleCount = 0;
    var totalCount = $('.student-row').length;

    $('.student-row').each(function() {
        var $row = $(this);
        var status = $row.attr('data-status');
        var text = $row.text().toLowerCase();

        var matchesSearch = (query === '' || text.indexOf(query) !== -1);
        var matchesFilter = true;

        if (filter === 'Present') {
            matchesFilter = (status === 'Present' || status === 'Switched Bus');
        } else if (filter === 'Absent') {
            matchesFilter = (status === 'Absent');
        } else if (filter === 'Other') {
            matchesFilter = (status !== 'Present' && status !== 'Absent' && status !== 'Switched Bus');
        }

        if (matchesSearch && matchesFilter) {
            $row.show();
            visibleCount++;
        } else {
            $row.hide();
        }
    });

    var sortLabel = $('#quick_sort_select option:selected').text();
    if (query !== '' || filter !== 'all') {
        $('#filtered_info').text('Showing ' + visibleCount + ' of ' + totalCount + ' students (' + sortLabel + ')');
    } else {
        $('#filtered_info').text('Showing all ' + totalCount + ' students (' + sortLabel + ')');
    }

    $('#clear_search_btn').toggle(query.length > 0);
}

function removeCustomRider(student_session_id, btn) {
    if (confirm('Are you sure you want to remove this custom rider?')) {
        var vehicle_id = $('select[name="vehicle_id"]').val();
        var date = $('#date').val();
        var attendance_type = $('select[name="attendance_type"]').val();
        
        $.ajax({
            url: "<?php echo site_url('admin/transportattendance/remove_custom_rider') ?>",
            type: "POST",
            data: {
                student_session_id: student_session_id,
                vehicle_id: vehicle_id,
                date: date,
                attendance_type: attendance_type
            },
            dataType: 'json',
            success: function(res) {
                if (res.status == 1) {
                    successMsg(res.msg);
                    $(btn).closest('tr').fadeOut(300, function() {
                        $(this).remove();
                        updateCounters();
                        applyFilterAndSearch();
                    });
                } else {
                    errorMsg(res.msg);
                }
            }
        });
    }
}

$(document).ready(function() {
    $('.select2').select2();
    updateCounters();

    // Column Header Sorting
    $('.sortable-th').on('click', function() {
        var key = $(this).data('sort');
        var order = 'asc';
        if (currentSortKey === key && currentSortOrder === 'asc') {
            order = 'desc';
        }
        sortTable(key, order);
        applyFilterAndSearch();
    });

    // Quick Sort Dropdown Change
    $('#quick_sort_select').on('change', function() {
        var val = $(this).val();
        if (val === 'name_asc') sortTable('name', 'asc');
        else if (val === 'name_desc') sortTable('name', 'desc');
        else if (val === 'adm_asc') sortTable('adm', 'asc');
        else if (val === 'adm_desc') sortTable('adm', 'desc');
        else if (val === 'stop_asc') sortTable('stop', 'asc');
        else if (val === 'class_asc') sortTable('class', 'asc');
        else if (val === 'status_present') sortTable('status', 'asc');
        else if (val === 'status_absent') sortTable('status', 'desc');
        applyFilterAndSearch();
    });

    // Filter Chips
    $('.filter-chip').on('click', function() {
        $('.filter-chip').removeClass('active');
        $(this).addClass('active');
        currentFilter = $(this).data('filter');
        applyFilterAndSearch();
    });

    // Search Input & Clear
    $('#touch_student_search').on('keyup search input', function() {
        applyFilterAndSearch();
    });

    $('#clear_search_btn').on('click', function() {
        $('#touch_student_search').val('').focus();
        applyFilterAndSearch();
        $(this).hide();
    });

    // Status Toggle Button Click
    $(document).on('click', '.btn-touch-status', function(e) {
        e.preventDefault();
        var val = $(this).data('value');
        var group = $(this).closest('.status-toggle-group');
        var sessionId = group.data('session-id');
        var row = $(this).closest('.student-row');
        
        $('#attendencetype_' + sessionId).val(val);
        row.attr('data-status', val);
        
        group.find('.btn-touch-status').removeClass('btn-present-active btn-absent-active btn-hostel-active btn-gatepass-active');
        
        if (val === 'Present') $(this).addClass('btn-present-active');
        else if (val === 'Absent') $(this).addClass('btn-absent-active');
        else if (val === 'Hostel') $(this).addClass('btn-hostel-active');
        else if (val === 'Gatepass') $(this).addClass('btn-gatepass-active');
        
        updateCounters();
        if (currentFilter !== 'all') {
            applyFilterAndSearch();
        }
    });

    // Route & Vehicle 2-way sync
    var initial_route_id = '<?php echo isset($route_id) ? $route_id : ""; ?>';
    var syncingSelects = false;
    
    $('#route_id').on('change', function(e, triggeredByLoad) {
        if (triggeredByLoad || syncingSelects) return;
        var route_id = $(this).val();
        if (route_id) {
            syncingSelects = true;
            $.ajax({
                url: '<?php echo site_url("admin/transportattendance/get_route_vehicles"); ?>',
                type: 'POST',
                data: { route_id: route_id },
                dataType: 'json',
                success: function(vehicles) {
                    if (vehicles && vehicles.length > 0) {
                        var targetVehicleId = vehicles[0].id || vehicles[0].vehicle_id;
                        if (targetVehicleId && $('#vehicle_id').val() != targetVehicleId) {
                            $('#vehicle_id').val(targetVehicleId).trigger('change.select2');
                        }
                    }
                    syncingSelects = false;
                },
                error: function() {
                    syncingSelects = false;
                }
            });
        }
    });
    
    $('#vehicle_id').on('change', function(e, triggeredByLoad) {
        if (triggeredByLoad || syncingSelects) return;
        var vehicle_id = $(this).val();
        var route_select = $('#route_id');
        
        if (vehicle_id) {
            syncingSelects = true;
            $.ajax({
                url: '<?php echo site_url("admin/transportattendance/get_vehicle_routes"); ?>',
                type: 'POST',
                data: { vehicle_id: vehicle_id },
                dataType: 'json',
                success: function(routes) {
                    if (routes && routes.length > 0) {
                        var current_r = route_select.val();
                        route_select.html('<option value="">All Routes on this Bus</option>');
                        $.each(routes, function(idx, r) {
                            var sel = (current_r && current_r == r.route_id) || (initial_route_id && initial_route_id == r.route_id) ? ' selected="selected"' : '';
                            route_select.append('<option value="' + r.route_id + '"' + sel + '>' + r.route_title + '</option>');
                        });
                        route_select.trigger('change.select2');
                    }
                    syncingSelects = false;
                },
                error: function() {
                    syncingSelects = false;
                }
            });
        }
    });

    // Custom Rider Search
    $('#search_student_text').on('keyup', function() {
        var search = $(this).val();
        if (search.length >= 2) {
            $.ajax({
                url: '<?php echo site_url("admin/transportattendance/search_student") ?>',
                type: 'POST',
                data: {search: search},
                dataType: 'json',
                success: function(data) {
                    if (data && data.length > 0) {
                        var html = '<ul class="list-group" style="margin-bottom:0;">';
                        $.each(data, function(index, student) {
                            html += '<li class="list-group-item" style="display:flex; justify-content:space-between; align-items:center; padding:8px 12px;">';
                            html += '<div><strong>' + student.firstname + ' ' + student.lastname + '</strong> (' + student.admission_no + ') <small class="text-muted">' + student.class + ' (' + student.section + ')</small></div>';
                            html += '<button type="button" class="btn btn-xs btn-success add_custom_btn" data-studentid="'+student.id+'" style="border-radius:4px; font-weight:600;"><i class="fa fa-plus"></i> Add</button>';
                            html += '</li>';
                        });
                        html += '</ul>';
                        $('#search_results').html(html).show();
                    } else {
                        $('#search_results').html('<div style="padding:10px 14px; color:#94a3b8; font-size:12px;">No matching students found.</div>').show();
                    }
                }
            });
        } else {
            $('#search_results').hide().html('');
        }
    });

    $(document).on('click', '.add_custom_btn', function() {
        var student_id = $(this).data('studentid');
        var vehicle_id = $('select[name="vehicle_id"]').val();
        var date = $('#date').val();
        var attendance_type = $('select[name="attendance_type"]').val();
        
        if(!vehicle_id || !date || !attendance_type) {
            errorMsg('Please ensure you have selected a date, vehicle, and attendance shift on the search form.');
            return;
        }
        
        $.ajax({
            url: '<?php echo site_url("admin/transportattendance/add_custom_rider") ?>',
            type: 'POST',
            data: {
                student_id: student_id,
                vehicle_id: vehicle_id,
                date: date,
                attendance_type: attendance_type
            },
            dataType: 'json',
            success: function(res) {
                if (res.status == 1) {
                    successMsg(res.msg);
                    setTimeout(function(){ location.reload(); }, 800);
                } else {
                    errorMsg(res.msg);
                }
            },
            error: function(xhr) {
                errorMsg('An error occurred. Please try again.');
                console.error(xhr.responseText);
            }
        });
    });
});
</script>
