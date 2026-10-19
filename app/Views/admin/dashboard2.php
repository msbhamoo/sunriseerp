<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat();?>
<style type="text/css">
    .dashboard2-wrapper {
        background-color: #f4f6f9;
        font-family: 'Inter', sans-serif;
        padding-top: 60px;
    }
    .d2-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 24px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        border: 1px solid #f0f0f0;
        display: flex;
        flex-direction: column;
        height: 100%;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .d2-card:hover {
        box-shadow: 0 8px 25px rgba(0,0,0,0.06);
    }
    .d2-title {
        font-size: 13px;
        font-weight: 700;
        color: #666;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    /* Metrics Row */
    .d2-metric-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }
    .d2-metric-box {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #eaeaea;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        display: flex;
        align-items: center;
    }
    .d2-metric-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin-right: 16px;
    }
    .d2-metric-icon.students { background: #fff5eb; color: #f97316; }
    .d2-metric-icon.staff { background: #fdf4ff; color: #d946ef; }
    .d2-metric-icon.attendance { background: #ecfdf5; color: #10b981; }
    .d2-metric-icon.admissions { background: #f0f9ff; color: #0ea5e9; }
    
    .d2-metric-content { flex: 1; }
    .d2-metric-label { font-size: 12px; color: #888; font-weight: 600; text-transform: uppercase; margin-bottom: 4px; }
    .d2-metric-value { font-size: 26px; font-weight: 800; color: #1f2937; line-height: 1.2; }
    .d2-metric-sub { font-size: 12px; color: #10b981; font-weight: 600; margin-top: 4px; }
    
    /* Finances Row */
    .d2-finance-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
    }
    .d2-finance-card {
        padding: 16px;
        border-radius: 10px;
        border: 1px solid #f0f0f0;
    }
    .d2-finance-card.total { background: #f8fafc; border-color: #e2e8f0; }
    .d2-finance-card.outstanding { background: #fff1f2; border-color: #ffe4e6; }
    .d2-finance-card.month { background: #f0fdfa; border-color: #ccfbf1; }
    .d2-finance-card.today { background: #fefce8; border-color: #fef08a; }
    
    .d2-finance-lbl { font-size: 12px; text-transform: uppercase; font-weight: 700; margin-bottom: 8px; color: #64748b; }
    .d2-finance-val { font-size: 22px; font-weight: 800; color: #0f172a; }
    
    /* Demographics & Lists */
    .d2-scrollable-list {
        flex: 1;
        overflow-y: auto;
        max-height: 320px;
        padding-right: 5px;
    }
    .d2-scrollable-list::-webkit-scrollbar { width: 4px; }
    .d2-scrollable-list::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    
    .d2-list-item {
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .d2-list-item:last-child { border-bottom: none; }
    .d2-list-title { font-weight: 600; font-size: 14px; color: #334155; margin-bottom: 4px; }
    .d2-list-meta { font-size: 12px; color: #64748b; display: flex; align-items: center; gap: 8px; }
    
    .d2-badge {
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        background: #f1f5f9;
        color: #475569;
    }
    
    /* Header Actions */
    .d2-header-actions { display: flex; gap: 10px; flex-wrap: wrap; justify-content: flex-end; }
    .d2-btn {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #475569;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .d2-btn:hover { background: #f8fafc; border-color: #cbd5e1; color: #0f172a; text-decoration: none;}
    .d2-btn.primary { background: #3b82f6; color: #fff; border-color: #3b82f6; }
    .d2-btn.primary:hover { background: #2563eb; color: #fff; }
    
    /* Masonry style grid for bottom section */
    .d2-masonry {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
        gap: 24px;
        align-items: start;
    }
    
    /* Demographics Grid */
    .d2-demo-section { margin-bottom: 20px; }
    .d2-demo-heading { font-size: 12px; font-weight: 600; color: #94a3b8; margin-bottom: 10px; text-transform: uppercase; }
    .d2-demo-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; }
    .d2-demo-item { display: flex; justify-content: space-between; align-items: center; background: #f8fafc; padding: 8px 12px; border-radius: 6px; }
    .d2-demo-item span.lbl { font-size: 13px; font-weight: 500; color: #334155; }
    .d2-demo-item span.val { font-size: 13px; font-weight: 700; color: #0f172a; background: #fff; padding: 2px 8px; border-radius: 4px; border: 1px solid #e2e8f0; }

</style>

<div class="content-wrapper dashboard2-wrapper">
    <section class="content-header" style="padding: 20px 15px;">
        <div class="row" style="display:flex; align-items:center; justify-content:space-between; flex-wrap: wrap; gap: 15px;">
            <div class="col-md-5">
                <h1 style="margin:0; font-size: 26px; font-weight:800; color: #0f172a; letter-spacing: -0.5px;">School Dashboard</h1>
                <div style="color:#64748b; font-size: 13px; margin-top: 4px;">Session: <strong style="color:#3b82f6;"><?php echo $this->setting_model->getCurrentSessionName(); ?></strong></div>
            </div>
            <div class="col-md-7 text-right d2-header-actions">
                <?php if($this->rbac->hasPrivilege('collect_fees', 'can_view')): ?>
                <a href="<?php echo site_url('studentfee') ?>" class="d2-btn"><i class="fa fa-money" style="color:#10b981;"></i> Collect Fees</a>
                <?php endif; ?>
                <?php if($this->rbac->hasPrivilege('expense', 'can_view')): ?>
                <a href="<?php echo site_url('admin/expense') ?>" class="d2-btn"><i class="fa fa-credit-card" style="color:#ef4444;"></i> Add Expense</a>
                <?php endif; ?>
                <a href="<?php echo site_url('admin/admin/switch_dashboard/1.0') ?>" class="d2-btn primary"><i class="fa fa-exchange"></i> Switch to Old View</a>
            </div>
        </div>
    </section>

    <section class="content" style="padding: 0 15px;">
        
        <!-- ROW 1: Metrics -->
        <?php if($this->rbac->hasPrivilege('student', 'can_view') || $this->rbac->hasPrivilege('staff', 'can_view')): ?>
        <div class="d2-metric-grid">
            <div class="d2-metric-box">
                <div class="d2-metric-icon students"><i class="fa fa-graduation-cap"></i></div>
                <div class="d2-metric-content">
                    <div class="d2-metric-label">Active Students</div>
                    <div class="d2-metric-value"><?php echo $total_students; ?></div>
                </div>
            </div>
            <div class="d2-metric-box">
                <div class="d2-metric-icon staff"><i class="fa fa-users"></i></div>
                <div class="d2-metric-content">
                    <div class="d2-metric-label">Total Staff</div>
                    <div class="d2-metric-value"><?php echo $getTotalStaff_data; ?></div>
                </div>
            </div>
            <div class="d2-metric-box">
                <div class="d2-metric-icon attendance"><i class="fa fa-check-square-o"></i></div>
                <div class="d2-metric-content">
                    <div class="d2-metric-label">Today's Attendance</div>
                    <div class="d2-metric-value">
                        <?php echo ($total_students>0) ? round((($attendence_data['total_present']??0)/$total_students)*100, 1) : 0; ?>%
                    </div>
                    <div class="d2-metric-sub">Student avg</div>
                </div>
            </div>
            <div class="d2-metric-box">
                <div class="d2-metric-icon admissions"><i class="fa fa-user-plus"></i></div>
                <div class="d2-metric-content">
                    <div class="d2-metric-label">New Admissions</div>
                    <div class="d2-metric-value"><?php echo isset($new_admissions) ? $new_admissions : 0; ?></div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- ROW 2: Financial & Charts -->
        <div class="row">
            <div class="col-md-12" id="calendar_dashboard_summary"></div>
        </div>
        
        <div class="row">
            <?php if($this->rbac->hasPrivilege('fees_overview_widegts', 'can_view')): ?>
            <div class="col-md-12" style="margin-bottom: 24px;">
                <div class="d2-card">
                    <div class="d2-title">Financial Overview <span class="d2-badge" style="background:#ecfdf5; color:#10b981; border:1px solid #a7f3d0;">Live</span></div>
                    <div class="d2-finance-grid">
                        <div class="d2-finance-card total">
                            <div class="d2-finance-lbl">Total Collection</div>
                            <div class="d2-finance-val"><?php echo $currency_symbol . amountFormat(isset($total_coll) ? $total_coll : 0); ?></div>
                        </div>
                        <div class="d2-finance-card outstanding">
                            <div class="d2-finance-lbl" style="color:#e11d48;">Outstanding</div>
                            <div class="d2-finance-val" style="color:#e11d48;"><?php echo $currency_symbol . amountFormat(isset($outstanding) ? $outstanding : 0); ?></div>
                        </div>
                        <div class="d2-finance-card month">
                            <div class="d2-finance-lbl" style="color:#0f766e;">This Month</div>
                            <div class="d2-finance-val" style="color:#0f766e;"><?php echo $currency_symbol . amountFormat(isset($this_month_coll) ? $this_month_coll : 0); ?></div>
                        </div>
                        <div class="d2-finance-card today">
                            <div class="d2-finance-lbl" style="color:#a16207;">Today's Collection</div>
                            <div class="d2-finance-val" style="color:#a16207;"><?php echo $currency_symbol . amountFormat(isset($today_coll) ? $today_coll : 0); ?></div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- ROW 3: Charts & Demography -->
        <div class="row" style="display:flex; flex-wrap:wrap;">
            <?php if($this->rbac->hasPrivilege('student', 'can_view')): ?>
            <div class="col-md-4" style="display:flex; margin-bottom: 24px;">
                <div class="d2-card" style="width:100%;">
                    <div class="d2-title">Class-wise Strength</div>
                    <div style="height: 320px; position: relative; width: 100%;">
                        <canvas id="classStrengthChart"></canvas>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if($this->rbac->hasPrivilege('staff', 'can_view')): ?>
            <div class="col-md-4" style="display:flex; margin-bottom: 24px;">
                <div class="d2-card" style="width:100%;">
                    <div class="d2-title">Staff Roles</div>
                    <div style="height: 320px; position: relative; width: 100%;">
                        <canvas id="staffRoleChart"></canvas>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <?php if($this->rbac->hasPrivilege('student', 'can_view')): ?>
            <div class="col-md-4" style="display:flex; margin-bottom: 24px;">
                <div class="d2-card" style="width:100%;">
                    <div class="d2-title">Student Demographics</div>
                    <div class="d2-scrollable-list">
                        
                        <div class="d2-demo-section">
                            <div class="d2-demo-heading">Gender</div>
                            <div class="d2-demo-grid">
                                <?php if(isset($gender_stats)) foreach($gender_stats as $gs): ?>
                                <div class="d2-demo-item"><span class="lbl"><?php echo $gs['gender']; ?></span><span class="val"><?php echo $gs['total']; ?></span></div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="d2-demo-section">
                            <div class="d2-demo-heading">Category</div>
                            <div class="d2-demo-grid">
                                <?php if(isset($category_stats)) foreach($category_stats as $cs): $cat_name = empty($cs['category']) ? 'Not Set' : $cs['category']; ?>
                                <div class="d2-demo-item"><span class="lbl"><?php echo $cat_name; ?></span><span class="val"><?php echo $cs['total']; ?></span></div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="d2-demo-section">
                            <div class="d2-demo-heading">Religion</div>
                            <div class="d2-demo-grid">
                                <?php if(isset($religion_stats)) foreach($religion_stats as $rs): $rel_name = empty($rs['religion']) ? 'Not Set' : $rs['religion']; ?>
                                <div class="d2-demo-item"><span class="lbl"><?php echo $rel_name; ?></span><span class="val"><?php echo $rs['total']; ?></span></div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <?php if(isset($accommodation_stats)): ?>
                        <div class="d2-demo-section">
                            <div class="d2-demo-heading">Accommodation</div>
                            <div class="d2-demo-grid">
                                <div class="d2-demo-item"><span class="lbl">Day Scholar</span><span class="val"><?php echo $accommodation_stats['day_scholar'] ?? 0; ?></span></div>
                                <div class="d2-demo-item"><span class="lbl">Hostel</span><span class="val"><?php echo $accommodation_stats['hostel'] ?? 0; ?></span></div>
                                <div class="d2-demo-item"><span class="lbl">Transport</span><span class="val"><?php echo $accommodation_stats['transport'] ?? 0; ?></span></div>
                            </div>
                        </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- ROW 4: Activity & Lists (Masonry style grid) -->
        <div class="d2-masonry">
            
            <!-- Pending Call Follow-ups -->
            <?php if($this->rbac->hasPrivilege('student_call_log', 'can_view')): ?>
            <div class="d2-card">
                <div class="d2-title" style="color:#d946ef;">Pending Call Follow-ups</div>
                <div class="d2-scrollable-list">
                    <?php if(!empty($pending_fw)): ?>
                        <?php foreach($pending_fw as $fw): ?>
                        <div class="d2-list-item">
                            <div class="d2-list-title"><?php echo $fw['firstname'] . ' ' . $fw['lastname']; ?> <span style="font-weight:normal; color:#888;">(<?php echo $fw['admission_no']; ?>)</span></div>
                            <div class="d2-list-meta" style="color:#d946ef; font-weight:600;"><i class="fa fa-calendar"></i> Due: <?php echo date('d M Y', strtotime($fw['due_date'])); ?></div>
                            <div class="d2-list-meta" style="margin-top:4px;"><i class="fa fa-phone"></i> <?php echo $fw['phone_number']; ?> (<?php echo $fw['contact_person']; ?>)</div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="d2-list-meta">No pending follow-ups assigned to you.</div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Leaves & Attendance -->
            <?php if($this->rbac->hasPrivilege('approve_leave', 'can_view')): ?>
            <div class="d2-card">
                <div class="d2-title" style="color:#eab308;">Leave Activity</div>
                <div class="d2-scrollable-list">
                    
                    <div style="font-weight:700; font-size:12px; margin-bottom:10px; color:#eab308;">ON LEAVE TODAY</div>
                    <?php if(!empty($on_leave_today)): ?>
                        <?php foreach($on_leave_today as $leave): ?>
                        <div class="d2-list-item" style="padding-top:0;">
                            <div class="d2-list-title"><?php echo $leave['name'] . ' ' . $leave['surname']; ?> <span class="d2-badge"><?php echo $leave['employee_id']; ?></span></div>
                            <div class="d2-list-meta"><i class="fa fa-bed"></i> Away until <?php echo date('d M Y', strtotime($leave['leave_to'])); ?></div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="d2-list-meta" style="margin-bottom:15px;">All staff are present today.</div>
                    <?php endif; ?>

                    <div style="font-weight:700; font-size:12px; margin:15px 0 10px; color:#ef4444;">PENDING APPROVALS</div>
                    <?php if(!empty($pending_leaves)): ?>
                        <?php foreach($pending_leaves as $leave): ?>
                        <div class="d2-list-item" style="padding-top:0;">
                            <div class="d2-list-title"><?php echo $leave['name'] . ' ' . $leave['surname']; ?></div>
                            <div class="d2-list-meta"><i class="fa fa-calendar"></i> <?php echo date('d M', strtotime($leave['leave_from'])) . ' - ' . date('d M Y', strtotime($leave['leave_to'])); ?> (<?php echo $leave['leave_days']; ?> Days)</div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="d2-list-meta">No pending leave requests.</div>
                    <?php endif; ?>

                </div>
            </div>
            <?php endif; ?>

            <!-- Upcoming Events & Birthdays -->
            <div class="d2-card">
                <div class="d2-title" style="color:#3b82f6;">Upcoming & Highlights</div>
                <div class="d2-scrollable-list">
                    
                    <!-- Birthdays -->
                    <?php 
                        $has_today = false;
                        if(!empty($upcoming_birthdays)) {
                            foreach($upcoming_birthdays as $b) {
                                if(date('m-d', strtotime($b['dob'])) == date('m-d')) { $has_today = true; break; }
                            }
                        }
                    ?>
                    <div style="font-weight:700; font-size:12px; margin-bottom:10px; color:#10b981;">BIRTHDAYS</div>
                    <?php if(!empty($upcoming_birthdays)): ?>
                        <?php foreach($upcoming_birthdays as $b): 
                            $is_today = date('m-d', strtotime($b['dob'])) == date('m-d');
                        ?>
                        <div class="d2-list-item" style="padding-top:0;">
                            <div class="d2-list-title"><?php echo $b['firstname'] . ' ' . $b['lastname']; ?> <span class="d2-badge"><?php echo $b['user_type']; ?></span></div>
                            <div class="d2-list-meta" style="<?php if($is_today) echo 'color:#10b981; font-weight:600;'; ?>">
                                <i class="fa fa-birthday-cake"></i> <?php echo date('d M', strtotime($b['dob'])); ?> <?php if($is_today) echo "(Today!)"; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="d2-list-meta" style="margin-bottom:15px;">No upcoming birthdays.</div>
                    <?php endif; ?>

                    <!-- Events -->
                    <?php if($this->rbac->hasPrivilege('events', 'can_view')): ?>
                    <div style="font-weight:700; font-size:12px; margin:15px 0 10px; color:#f59e0b;">EVENTS</div>
                    <?php if(!empty($upcoming_events)): ?>
                        <?php foreach($upcoming_events as $ev): ?>
                        <div class="d2-list-item" style="padding-top:0;">
                            <div class="d2-list-title"><?php echo $ev['event_title']; ?></div>
                            <div class="d2-list-meta"><i class="fa fa-calendar"></i> <?php echo date('d M', strtotime($ev['start_date'])); ?></div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="d2-list-meta">No upcoming events.</div>
                    <?php endif; ?>
                    <?php endif; ?>

                </div>
            </div>

            <!-- CBSE Exams -->
            <?php if($this->rbac->hasPrivilege('cbse_exam', 'can_view')): ?>
            <div class="d2-card">
                <div class="d2-title" style="color:#6366f1;">Upcoming CBSE Exams</div>
                <div class="d2-scrollable-list">
                    <?php if(!empty($upcoming_cbse_exams)): ?>
                        <?php foreach($upcoming_cbse_exams as $ex): ?>
                        <div class="d2-list-item">
                            <div class="d2-list-title"><?php echo $ex['exam_title']; ?></div>
                            <div class="d2-list-meta"><i class="fa fa-calendar-check-o"></i> <?php echo date('d M Y', strtotime($ex['start_date'])); ?>
                            <?php if($ex['start_date'] != $ex['end_date'] && !empty($ex['end_date']) && $ex['end_date'] != '0000-00-00') echo " - " . date('d M Y', strtotime($ex['end_date'])); ?>
                            </div>
                            <?php if(!empty($ex['exam_description'])): ?>
                            <div class="d2-list-meta" style="margin-top:4px; font-size:11px;"><?php echo strip_tags($ex['exam_description']); ?></div>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="d2-list-meta">No upcoming exams.</div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Today's Visitors -->
            <?php if($this->rbac->hasPrivilege('visitor_book', 'can_view')): ?>
            <div class="d2-card">
                <div class="d2-title" style="color:#0ea5e9;">Today's Visitors</div>
                <div class="d2-scrollable-list">
                    <?php if(!empty($today_visitors)): ?>
                        <?php foreach($today_visitors as $v): ?>
                        <div class="d2-list-item">
                            <div class="d2-list-title" style="display:flex; justify-content:space-between;">
                                <span><?php echo $v['name']; ?></span>
                                <span style="font-size:11px; color:#0ea5e9;"><i class="fa fa-clock-o"></i> <?php echo $v['in_time']; ?></span>
                            </div>
                            <div class="d2-list-meta"><i class="fa fa-id-badge"></i> <?php echo $v['purpose']; ?></div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="d2-list-meta">No visitors logged today.</div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </section>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script>
    (function() {
        var classLabels = <?php echo json_encode(array_column($class_stats ?? [], 'class')); ?>;
        var classData = <?php echo json_encode(array_column($class_stats ?? [], 'total')); ?>;
        
        var baseColors = ['#4f46e5', '#ec4899', '#14b8a6', '#f59e0b', '#3b82f6', '#8b5cf6', '#ef4444', '#10b981', '#f97316', '#6366f1', '#d946ef', '#06b6d4'];
        var bgColors = [];
        for(var i=0; i<classData.length; i++) { bgColors.push(baseColors[i % baseColors.length]); }
        
        var ctx = document.getElementById('classStrengthChart');
        if(ctx) {
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: classLabels,
                    datasets: [{
                        data: classData,
                        backgroundColor: bgColors,
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutoutPercentage: 75,
                    legend: {
                        position: 'bottom',
                        labels: { usePointStyle: true, padding: 10, fontSize: 11, fontColor: '#64748b' }
                    }
                }
            });
        }

        var staffRoleLabels = <?php echo json_encode(array_column($staff_role_stats ?? [], 'role')); ?>;
        var staffRoleData = <?php echo json_encode(array_column($staff_role_stats ?? [], 'total')); ?>;
        var staffBgColors = [];
        var altColors = ['#c026d3', '#2563eb', '#16a34a', '#dc2626', '#eab308', '#9333ea', '#0284c7'];
        for(var i=0; i<staffRoleData.length; i++) { staffBgColors.push(altColors[i % altColors.length]); }
        
        var ctxStaff = document.getElementById('staffRoleChart');
        if(ctxStaff) {
            new Chart(ctxStaff, {
                type: 'doughnut',
                data: {
                    labels: staffRoleLabels,
                    datasets: [{
                        data: staffRoleData,
                        backgroundColor: staffBgColors,
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutoutPercentage: 75,
                    legend: {
                        position: 'bottom',
                        labels: { usePointStyle: true, padding: 10, fontSize: 11, fontColor: '#64748b' }
                    }
                }
            });
        }
    })();
</script>
<script>
.ready(function() {
    $.ajax({
        url: '<?php echo site_url("admin/holiday/get_dashboard_widget"); ?>',
        type: 'GET',
        success: function(response) {
            if(response.trim() != '') {
                #calendar_dashboard_summary.html(response).hide().fadeIn('fast');
            }
        },
        error: function(err) {
            console.error('Failed to load dashboard calendar summary', err);
        }
    });
});
</script>
<script>
$(document).ready(function() {
    $.ajax({
        url: '<?php echo site_url("admin/holiday/get_dashboard_widget"); ?>',
        type: 'GET',
        success: function(response) {
            if(response.trim() != '') {
                $('#calendar_dashboard_summary').html(response).hide().fadeIn('fast');
            }
        },
        error: function(err) {
            console.error('Failed to load dashboard calendar summary', err);
        }
    });
});
</script>
