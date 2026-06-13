<?php $currency_symbol = $this->customlib->getSchoolCurrencyFormat();?>
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
        font-size: 12px;
        font-weight: 600;
        color: #8a8a8a;
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
    
    .d2-fee-summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f0f0f0;
    }
    .d2-fee-summary-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .d2-fee-lbl {
        font-size: 13px;
        font-weight: 600;
        color: #555;
    }
    .d2-fee-val {
        font-size: 16px;
        font-weight: 700;
        color: #222;
    }
    .d2-demo-box {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .d2-pill {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
    .d2-pill.male { background: #fbedcf; color: #d09435; }
    .d2-pill.female { background: #f4e8fb; color: #9d50ce; }
    .d2-pill.general { background: #e0e7ff; color: #4f46e5; }
    .d2-pill.notset { background: #f3f4f6; color: #4b5563; }
    .d2-pill.hindu { background: #fef08a; color: #ca8a04; }
    
    .d2-header-actions {
        display: flex;
        gap: 10px;
    }
    .d2-btn {
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 500;
        border: 1px solid #ccc;
        background: #fff;
        color: #333;
        transition: all 0.2s;
    }
    .d2-btn:hover { background: #f5f5f5; }
    .d2-btn.primary { background: #007bff; color: #fff; border-color: #007bff; }
    .d2-btn.primary:hover { background: #0069d9; }
    
</style>
<div class="content-wrapper dashboard2-wrapper">
    <section class="content-header">
        <div class="row" style="display:flex; align-items:center; justify-content:space-between; margin-bottom: 20px;">
            <div class="col-md-6">
                <h1 style="margin:0; font-size: 24px; font-weight:700;">School Admin Dashboard</h1>
                <small style="color:#888;">Dashboard / School Admin Dashboard</small>
            </div>
            <div class="col-md-6 text-right d2-header-actions" style="justify-content: flex-end;">
                <a href="<?php echo site_url('studentfee') ?>" class="d2-btn"><i class="fa fa-bolt" style="color:#28a745;"></i> Collect</a>
                <a href="<?php echo site_url('admin/expense') ?>" class="d2-btn"><i class="fa fa-plus" style="color:#dc3545;"></i> Expense</a>
                <a href="#" class="d2-btn"><i class="fa fa-external-link"></i> Public Form</a>
                <a href="<?php echo site_url('admin/admin/switch_dashboard/1.0') ?>" class="d2-btn primary"><i class="fa fa-arrow-left"></i> Old Dashboard</a>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row">
            <!-- Session Overview -->
            <div class="col-md-8">
                <div class="d2-card">
                    <div class="d2-title">Session Overview</div>
                    <div class="row">
                        <div class="col-md-9">
                            <div class="d2-stat-value"><?php echo $this->setting_model->getCurrentSessionName(); ?></div>
                            <div class="d2-stat-sub">Current Academic Session</div>
                            
                            <div class="row" style="margin-top:20px;">
                                <div class="col-md-4">
                                    <div class="d2-box students">
                                        <div class="d2-box-title">Students</div>
                                        <div class="d2-box-val"><?php echo $total_students; ?></div>
                                        <div class="d2-box-sub">Active Students</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d2-box staff">
                                        <div class="d2-box-title">Staff</div>
                                        <div class="d2-box-val"><?php echo $getTotalStaff_data; ?></div>
                                        <div class="d2-box-sub">Teaching and office</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="d2-box attendance">
                                        <div class="d2-box-title">Attendance Today</div>
                                        <div style="font-size:13px; font-weight:600; margin-top:5px;">
                                            Stu: <span style="color:#3b9b65;"><?php echo ($total_students>0) ? round((($attendence_data['total_present']??0)/$total_students)*100, 1) : 0; ?>%</span>
                                        </div>
                                        <div style="font-size:13px; font-weight:600; margin-top:2px;">
                                            Stf: <span style="color:#3b9b65;"><?php echo ($getTotalStaff_data>0) ? round((($Staffattendence_data??0)/$getTotalStaff_data)*100, 1) : 0; ?>%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="d2-box pending" style="height: 100%; display: flex; flex-direction: column; justify-content: center;">
                                <div class="d2-box-title">Pending Admissions</div>
                                <div class="d2-box-val" style="font-size: 36px;"><?php echo $pending_admissions; ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fee Summary -->
            <div class="col-md-4">
                <div class="d2-card" style="height: calc(100% - 20px);">
                    <?php 
                        $CI =& get_instance();
                        $CI->load->model('studentfeemaster_model');
                        $CI->load->model('module_model');
                        
                        // Total Collection
                        $total_coll = 0;
                        $all_collections = $CI->studentfeemaster_model->getFeeCollectionReport('1970-01-01', '2099-12-31', null, null, null, null, null);
                        if(!empty($all_collections)){
                            foreach($all_collections as $col){
                                $total_coll += (float)$col['amount'] + (float)$col['amount_fine'];
                            }
                        }

                        // Outstanding Calculation
                        $outstanding = 0;
                        $all_due = $CI->studentfeemaster_model->getFeesAwaiting('1970-01-01', '2099-12-31');
                        if(!empty($all_due)){
                            foreach($all_due as $f){
                                $amt = isset($f->is_system) && $f->is_system ? $f->amount : $f->fee_amount;
                                $paid = 0;
                                $discount = 0;
                                if(!empty($f->amount_detail)){
                                    $details = json_decode($f->amount_detail, true);
                                    if(is_array($details)){
                                        foreach($details as $d){
                                            $paid += (float)$d['amount'];
                                            $discount += (float)$d['amount_discount'];
                                        }
                                    }
                                }
                                $bal = (float)$amt - ($paid + $discount);
                                if($bal > 0) $outstanding += $bal;
                            }
                        }
                        if ($CI->module_model->hasModule('transport')) {
                            $all_transport = $CI->studentfeemaster_model->getTransportFeesByDueDate('1970-01-01', '2099-12-31');
                            if(!empty($all_transport)){
                                foreach($all_transport as $f){
                                    $amt = isset($f->fees) ? $f->fees : (isset($f->fee_amount) ? $f->fee_amount : 0);
                                    $paid = 0;
                                    $discount = 0;
                                    if(!empty($f->amount_detail)){
                                        $details = json_decode($f->amount_detail, true);
                                        if(is_array($details)){
                                            foreach($details as $d){
                                                $paid += (float)$d['amount'];
                                                $discount += (float)$d['amount_discount'];
                                            }
                                        }
                                    }
                                    $bal = (float)$amt - ($paid + $discount);
                                    if($bal > 0) $outstanding += $bal;
                                }
                            }
                        }

                        // This Month Collection
                        $this_month_coll = 0;
                        $month_collections = $CI->studentfeemaster_model->getFeeCollectionReport(date('Y-m-01'), date('Y-m-t'), null, null, null, null, null);
                        if(!empty($month_collections)){
                            foreach($month_collections as $col){
                                $this_month_coll += (float)$col['amount'] + (float)$col['amount_fine'];
                            }
                        }

                        // Today Collection
                        $today_coll = 0;
                        $today_collections = $CI->studentfeemaster_model->getFeeCollectionReport(date('Y-m-d'), date('Y-m-d'), null, null, null, null, null);
                        if(!empty($today_collections)){
                            foreach($today_collections as $col){
                                $today_coll += (float)$col['amount'] + (float)$col['amount_fine'];
                            }
                        }
                    ?>
                    <div class="d2-title">Fee Summary</div>
                    <div class="d2-fee-summary-row" style="background:#f4f6f9; padding:15px; border-radius:6px; border:1px solid #e9ecef; margin-bottom:15px;">
                        <div>
                            <div class="d2-fee-lbl" style="color:#333; font-size:11px; text-transform:uppercase;">Total Collection</div>
                            <div class="d2-fee-val" style="color:#333; font-size:20px;"><?php echo $currency_symbol . amountFormat($total_coll); ?></div>
                        </div>
                    </div>
                    <div class="d2-fee-summary-row" style="background:#fff5f8; padding:15px; border-radius:6px; border:1px solid #fbe0e8; margin-bottom:15px;">
                        <div>
                            <div class="d2-fee-lbl" style="color:#d8456a; font-size:11px; text-transform:uppercase;">Outstanding</div>
                            <div class="d2-fee-val" style="color:#d8456a; font-size:20px;"><?php echo $currency_symbol . amountFormat($outstanding); ?></div>
                        </div>
                    </div>
                    <div class="d2-fee-summary-row" style="margin-bottom:15px;">
                        <div>
                            <div class="d2-fee-lbl" style="font-size:11px; text-transform:uppercase;">This month</div>
                        </div>
                        <div class="d2-fee-val"><?php echo $currency_symbol . amountFormat($this_month_coll); ?></div>
                    </div>
                    <div class="d2-fee-summary-row" style="background:#f6fffa; padding:15px; border-radius:6px; border:1px solid #dcf2e6;">
                        <div>
                            <div class="d2-fee-lbl" style="color:#3b9b65; font-size:11px; text-transform:uppercase;">Today</div>
                            <div class="d2-fee-val" style="color:#3b9b65;"><?php echo $currency_symbol . amountFormat($today_coll); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Top Lists -->
            <div class="col-md-4">
                <div class="d2-card" style="height:100%;">
                    <?php 
                        $has_today = false;
                        if(!empty($upcoming_birthdays)) {
                            foreach($upcoming_birthdays as $b) {
                                if(date('m-d', strtotime($b['dob'])) == date('m-d')) {
                                    $has_today = true; break;
                                }
                            }
                        }
                    ?>
                    <div class="d2-title" style="color:#28a745;"><?php echo $has_today ? "Today's Birthdays" : "Upcoming Birthdays"; ?></div>
                    <?php if(!empty($upcoming_birthdays)): ?>
                        <ul style="list-style:none; padding:0; margin:0;">
                        <?php foreach($upcoming_birthdays as $b): 
                            $is_today = date('m-d', strtotime($b['dob'])) == date('m-d');
                        ?>
                            <li style="margin-bottom:15px; border-bottom:1px solid #dcf2e6; padding-bottom:10px;">
                                <div style="font-weight:600; font-size:14px; color:#222;">
                                    <?php echo $b['firstname'] . ' ' . $b['lastname']; ?>
                                    <span style="font-size:11px; padding:2px 6px; border-radius:4px; background:#f4f6f9; color:#666; margin-left:5px; font-weight:500;"><?php echo $b['user_type']; ?></span>
                                </div>
                                <div style="font-size:12px; color:<?php echo $is_today ? '#28a745' : '#888'; ?>; margin-top:3px; <?php if($is_today) echo 'font-weight:600;'; ?>">
                                    <i class="fa fa-birthday-cake"></i> <?php echo date('d M Y', strtotime($b['dob'])); ?>
                                    <?php if($is_today) echo " (Today!)"; ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div style="font-size:13px; color:#888;">No upcoming birthdays</div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="d2-card" style="height:100%;">
                    <div class="d2-title" style="color:#007bff;">Upcoming Exams (CBSE)</div>
                    <?php 
                        $CI =& get_instance();
                        $session_id = $CI->setting_model->getCurrentSession();
                        $today = date('Y-m-d');
                        $cbse_query = $CI->db->query("
                            SELECT e.name as exam_title, e.description as exam_description,
                                   MIN(t.date) as start_date, MAX(t.date) as end_date
                            FROM cbse_exams e
                            JOIN cbse_exam_timetable t ON t.cbse_exam_id = e.id
                            WHERE e.session_id = $session_id
                            GROUP BY e.id
                            HAVING MAX(t.date) >= '$today'
                            ORDER BY MIN(t.date) ASC
                            LIMIT 5
                        ");
                        $upcoming_cbse_exams = $cbse_query->result_array();
                    ?>
                    <?php if(!empty($upcoming_cbse_exams)): ?>
                        <ul style="list-style:none; padding:0; margin:0;">
                        <?php foreach($upcoming_cbse_exams as $ex): ?>
                            <li style="margin-bottom:15px; border-bottom:1px solid #e0e7ff; padding-bottom:10px;">
                                <div style="font-weight:600; font-size:14px; color:#222;"><?php echo $ex['exam_title']; ?></div>
                                <div style="font-size:12px; color:#888; margin-top:3px;">
                                    <i class="fa fa-calendar-check-o"></i> <?php echo date('d M Y', strtotime($ex['start_date'])); ?>
                                    <?php if($ex['start_date'] != $ex['end_date'] && !empty($ex['end_date']) && $ex['end_date'] != '0000-00-00') echo " - " . date('d M Y', strtotime($ex['end_date'])); ?>
                                </div>
                                <?php if(!empty($ex['exam_description'])): ?>
                                <div style="font-size:12px; color:#666; margin-top:5px; line-height:1.4;">
                                    <?php echo strip_tags($ex['exam_description']); ?>
                                </div>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div style="font-size:13px; color:#888;">No upcoming exams</div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-4">
                <div class="d2-card" style="height:100%;">
                    <div class="d2-title" style="color:#d09435;">Upcoming Events</div>
                    <?php if(!empty($upcoming_events)): ?>
                        <ul style="list-style:none; padding:0; margin:0;">
                        <?php foreach($upcoming_events as $ev): ?>
                            <li style="margin-bottom:15px; border-bottom:1px solid #fbedcf; padding-bottom:10px;">
                                <div style="font-weight:600; font-size:14px; color:#222;"><?php echo $ev['event_title']; ?></div>
                                <div style="font-size:12px; color:#888; margin-top:3px;">
                                    <i class="fa fa-calendar"></i> <?php echo date('d M Y', strtotime($ev['start_date'])); ?>
                                    <?php if($ev['start_date'] != $ev['end_date'] && !empty($ev['end_date'])) echo " - " . date('d M Y', strtotime($ev['end_date'])); ?>
                                </div>
                                <?php if(!empty($ev['event_description'])): ?>
                                <div style="font-size:12px; color:#666; margin-top:5px; line-height:1.4;">
                                    <?php echo strip_tags($ev['event_description']); ?>
                                </div>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div style="font-size:13px; color:#888;">No upcoming events</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="row" style="margin-bottom: 20px;">
            <!-- Pending Leave Requests -->
            <div class="col-md-4">
                <div class="d2-card" style="height:100%;">
                    <div class="d2-title" style="color:#d8456a;">Pending Leave Requests</div>
                    <?php 
                        $CI =& get_instance();
                        $today = date('Y-m-d');
                        $pending_leaves = $CI->db->query("
                            SELECT s.name, s.surname, s.employee_id, lr.leave_from, lr.leave_to, lr.leave_days 
                            FROM staff_leave_request lr 
                            JOIN staff s ON lr.staff_id = s.id 
                            WHERE lr.status = 'pending' 
                            ORDER BY lr.id DESC 
                            LIMIT 5
                        ")->result_array();
                    ?>
                    <?php if(!empty($pending_leaves)): ?>
                        <ul style="list-style:none; padding:0; margin:0;">
                        <?php foreach($pending_leaves as $leave): ?>
                            <li style="margin-bottom:15px; border-bottom:1px solid #fbe0e8; padding-bottom:10px;">
                                <div style="font-weight:600; font-size:14px; color:#222;">
                                    <?php echo $leave['name'] . ' ' . $leave['surname']; ?>
                                    <span style="font-size:11px; padding:2px 6px; border-radius:4px; background:#f4f6f9; color:#666; margin-left:5px; font-weight:500;"><?php echo $leave['employee_id']; ?></span>
                                </div>
                                <div style="font-size:12px; color:#d8456a; margin-top:3px; font-weight:600;">
                                    <i class="fa fa-calendar"></i> <?php echo date('d M', strtotime($leave['leave_from'])) . ' - ' . date('d M Y', strtotime($leave['leave_to'])); ?> 
                                    (<?php echo $leave['leave_days']; ?> Days)
                                </div>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div style="font-size:13px; color:#888;">No pending requests</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Staff on Leave Today -->
            <div class="col-md-4">
                <div class="d2-card" style="height:100%;">
                    <div class="d2-title" style="color:#eab308;">Staff on Leave Today</div>
                    <?php 
                        $on_leave_today = $CI->db->query("
                            SELECT s.name, s.surname, s.employee_id, lr.leave_from, lr.leave_to, lr.leave_days 
                            FROM staff_leave_request lr 
                            JOIN staff s ON lr.staff_id = s.id 
                            WHERE lr.status = 'approve' AND '$today' >= lr.leave_from AND '$today' <= lr.leave_to 
                            ORDER BY lr.id DESC
                        ")->result_array();
                    ?>
                    <?php if(!empty($on_leave_today)): ?>
                        <ul style="list-style:none; padding:0; margin:0;">
                        <?php foreach($on_leave_today as $leave): ?>
                            <li style="margin-bottom:15px; border-bottom:1px solid #fef08a; padding-bottom:10px;">
                                <div style="font-weight:600; font-size:14px; color:#222;">
                                    <?php echo $leave['name'] . ' ' . $leave['surname']; ?>
                                    <span style="font-size:11px; padding:2px 6px; border-radius:4px; background:#f4f6f9; color:#666; margin-left:5px; font-weight:500;"><?php echo $leave['employee_id']; ?></span>
                                </div>
                                <div style="font-size:12px; color:#ca8a04; margin-top:3px;">
                                    <i class="fa fa-bed"></i> Away until <?php echo date('d M Y', strtotime($leave['leave_to'])); ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div style="font-size:13px; color:#888;">All staff are present today</div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Today's Visitors -->
            <div class="col-md-4">
                <div class="d2-card" style="height:100%;">
                    <div class="d2-title" style="color:#0284c7;">Today's Visitors</div>
                    <?php 
                        $today_visitors = $CI->db->query("
                            SELECT name, purpose, contact, in_time 
                            FROM visitors_book 
                            WHERE date = '$today' 
                            ORDER BY id DESC 
                            LIMIT 5
                        ")->result_array();
                    ?>
                    <?php if(!empty($today_visitors)): ?>
                        <ul style="list-style:none; padding:0; margin:0;">
                        <?php foreach($today_visitors as $v): ?>
                            <li style="margin-bottom:15px; border-bottom:1px solid #bae6fd; padding-bottom:10px;">
                                <div style="font-weight:600; font-size:14px; color:#222;">
                                    <?php echo $v['name']; ?> 
                                    <span style="font-size:11px; color:#0284c7; float:right;"><i class="fa fa-clock-o"></i> <?php echo $v['in_time']; ?></span>
                                </div>
                                <div style="font-size:12px; color:#666; margin-top:3px;">
                                    <i class="fa fa-id-badge"></i> <?php echo $v['purpose']; ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <div style="font-size:13px; color:#888;">No visitors logged today</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <!-- Class Distribution -->
                    <div class="col-md-6">
                        <div class="d2-card" style="height: 100%;">
                            <div class="d2-title">Student Distribution</div>
                            <div style="font-weight:600; margin-bottom:15px; font-size:14px; color:#555;">Class-wise strength</div>
                            <div style="height: 280px; width: 100%; margin-bottom: 20px;">
                                <canvas id="classStrengthChart"></canvas>
                            </div>
                            <?php if(!empty($class_stats)): ?>
                            <div style="background: linear-gradient(135deg, #f0f4ff 0%, #e0e7ff 100%); padding:15px; border-radius:8px; border-left: 4px solid #4f46e5;">
                                <div style="font-size:11px; color:#666; text-transform:uppercase; font-weight:600; letter-spacing:0.5px;">Largest Class</div>
                                <div style="display:flex; justify-content:space-between; align-items:center; margin-top:5px;">
                                    <div style="font-size:18px; font-weight:700; color:#1e1e2f;"><?php echo $class_stats[0]['class']; ?></div>
                                    <div style="font-size:24px; font-weight:800; color:#4f46e5;"><?php echo $class_stats[0]['total']; ?></div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Staff Distribution -->
                    <div class="col-md-6">
                        <div class="d2-card" style="height: 100%;">
                            <div class="d2-title" style="color:#9d50ce;">Staff Distribution</div>
                            <div style="font-weight:600; margin-bottom:15px; font-size:14px; color:#555;">Role-wise strength snapshot</div>
                            <div style="height: 280px; width: 100%; margin-bottom: 20px;">
                                <canvas id="staffRoleChart"></canvas>
                            </div>
                            <?php if(!empty($staff_role_stats)): 
                                $top_role = $staff_role_stats[0];
                            ?>
                            <div style="background: linear-gradient(135deg, #fdf4ff 0%, #fae8ff 100%); padding:15px; border-radius:8px; border-left: 4px solid #c026d3;">
                                <div style="font-size:11px; color:#666; text-transform:uppercase; font-weight:600; letter-spacing:0.5px;">Largest Role</div>
                                <div style="display:flex; justify-content:space-between; align-items:center; margin-top:5px;">
                                    <div style="font-size:18px; font-weight:700; color:#1e1e2f;"><?php echo $top_role['role']; ?></div>
                                    <div style="font-size:24px; font-weight:800; color:#c026d3;"><?php echo $top_role['total']; ?></div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
                
            <div class="col-md-4">
                <div class="d2-card" style="margin-bottom:20px;">
                    <div class="d2-title" style="color:#d09435;">Student Demography</div>
                    <div style="font-weight:600; margin-bottom:15px;">Gender-wise Students</div>
                    <div class="row">
                        <?php foreach($gender_stats as $gs): ?>
                        <div class="col-xs-6" style="margin-bottom:10px;">
                            <span class="d2-fee-lbl"><?php echo $gs['gender']; ?></span>
                            <span class="d2-pill <?php echo strtolower($gs['gender']); ?>"><?php echo $gs['total']; ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="d2-card" style="margin-bottom:20px;">
                    <div class="d2-title" style="color:#9d50ce;">Student Demography</div>
                    <div style="font-weight:600; margin-bottom:15px;">Category-wise Students</div>
                    <div class="row">
                        <?php foreach($category_stats as $cs): 
                            $cat_name = empty($cs['category']) ? 'Not Set' : $cs['category'];
                            $pill_class = empty($cs['category']) ? 'notset' : 'general';
                        ?>
                        <div class="col-xs-6" style="margin-bottom:10px;">
                            <span class="d2-fee-lbl"><?php echo $cat_name; ?></span>
                            <span class="d2-pill <?php echo $pill_class; ?>"><?php echo $cs['total']; ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="d2-card" style="margin-bottom:20px;">
                    <div class="d2-title" style="color:#ca8a04;">Student Demography</div>
                    <div style="font-weight:600; margin-bottom:15px;">Religion-wise Students</div>
                    <div class="row">
                        <?php foreach($religion_stats as $rs): 
                            $rel_name = empty($rs['religion']) ? 'Not Set' : $rs['religion'];
                            $pill_class = empty($rs['religion']) ? 'notset' : 'hindu';
                        ?>
                        <div class="col-xs-6" style="margin-bottom:10px;">
                            <span class="d2-fee-lbl"><?php echo $rel_name; ?></span>
                            <span class="d2-pill <?php echo $pill_class; ?>"><?php echo $rs['total']; ?></span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="d2-card">
                    <div class="d2-title" style="color:#0ea5e9;">Student Demography</div>
                    <div style="font-weight:600; margin-bottom:15px;">Accommodation-wise Students</div>
                    <div class="row">
                        <?php if(isset($accommodation_stats)): ?>
                        <div class="col-xs-6" style="margin-bottom:10px;">
                            <span class="d2-fee-lbl">Day Scholar</span>
                            <span class="d2-pill general"><?php echo $accommodation_stats['day_scholar'] ?? 0; ?></span>
                        </div>
                        <div class="col-xs-6" style="margin-bottom:10px;">
                            <span class="d2-fee-lbl">Hostel</span>
                            <span class="d2-pill hindu"><?php echo $accommodation_stats['hostel'] ?? 0; ?></span>
                        </div>
                        <div class="col-xs-6" style="margin-bottom:10px;">
                            <span class="d2-fee-lbl">Transport</span>
                            <span class="d2-pill female"><?php echo $accommodation_stats['transport'] ?? 0; ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.min.js"></script>
<script>
    (function() {
        var classLabels = <?php echo json_encode(array_column($class_stats ?? [], 'class')); ?>;
        var classData = <?php echo json_encode(array_column($class_stats ?? [], 'total')); ?>;
        
        // Vibrant sleek colors for student chart
        var baseColors = ['#4f46e5', '#ec4899', '#14b8a6', '#f59e0b', '#3b82f6', '#8b5cf6', '#ef4444', '#10b981', '#f97316', '#6366f1', '#d946ef', '#06b6d4'];
        var bgColors = [];
        for(var i=0; i<classData.length; i++) {
            bgColors.push(baseColors[i % baseColors.length]);
        }
        
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
                    cutoutPercentage: 70,
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 10,
                            fontSize: 11
                        }
                    }
                }
            });
        }

        // Staff Role Chart
        var staffRoleLabels = <?php echo json_encode(array_column($staff_role_stats ?? [], 'role')); ?>;
        var staffRoleData = <?php echo json_encode(array_column($staff_role_stats ?? [], 'total')); ?>;
        var staffBgColors = [];
        // Distinct vibrant gradient-like solid tones
        var altColors = ['#c026d3', '#2563eb', '#16a34a', '#dc2626', '#eab308', '#9333ea', '#0284c7'];
        for(var i=0; i<staffRoleData.length; i++) {
            staffBgColors.push(altColors[i % altColors.length]);
        }
        
        var ctxStaff = document.getElementById('staffRoleChart');
        if(ctxStaff) {
            new Chart(ctxStaff, {
                type: 'pie',
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
                    legend: {
                        position: 'right',
                        labels: {
                            usePointStyle: true,
                            padding: 15
                        }
                    }
                }
            });
        }
    })();
</script>
