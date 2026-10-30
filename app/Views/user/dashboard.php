<?php
// ---------- Derived figures for the overview tiles ----------
$empty_img = 'https://smart-school.in/ssappresource/images/addnewitem.svg';

$homework_pending = 0;
foreach ((array) $homeworklist as $hw) {
    if ($hw['status'] != 'submitted' && $hw['homework_evaluation_id'] == 0) {
        $homework_pending++;
    }
}

$subject_count    = !empty($subjects_data) ? count($subjects_data) : 0;
$syllabus_average = 0;
if ($subject_count) {
    $syllabus_average = round(array_sum(array_column($subjects_data, 'complete')) / $subject_count);
}

$today_classes = 0;
$today_key     = date('l');
if (!empty($timetable[$today_key])) {
    $today_classes = count($timetable[$today_key]);
}

$has_attendance  = $attendence_percentage != '-1';
$attendance_low  = $has_attendance && $low_attendance_limit != '0.00' && $attendence_percentage < $low_attendance_limit;
$attendance_ring = $has_attendance ? (float) $attendence_percentage : 0;

if (!empty($student_data["image"]) && is_file(FCPATH . $student_data["image"])) {
    $file = base_url() . $student_data["image"] . img_time();
} elseif (($student_data['gender'] ?? '') == 'Female') {
    $file = base_url() . "uploads/student_images/default_female.jpg" . img_time();
} else {
    $file = base_url() . "uploads/student_images/default_male.jpg" . img_time();
}

$staff_pic = function ($row) {
    if ($row->image != '') {
        return base_url('uploads/staff_images/' . $row->image) . img_time();
    }
    return base_url('uploads/staff_images/' . ($row->gender == 'Male' ? 'default_male.jpg' : 'default_female.jpg')) . img_time();
};
$date_fmt = function ($date) {
    return date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($date));
};
?>
<div class="content-wrapper">
    <section class="content pb0 sd-dashboard">
        <?php foreach ($unread_notifications as $notice_value) {?>
            <div class="dashalert alert alert-success alert-dismissible sd-alert" role="alert">
                <button type="button" class="alertclose close close_notice stualert" data-dismiss="alert" aria-label="Close" data-noticeid="<?php echo $notice_value->id; ?>"><span aria-hidden="true">&times;</span></button>
                <i class="icon-bell"></i> <a href="<?php echo site_url('user/notification') ?>"><?php echo $notice_value->title; ?></a>
            </div>
        <?php }?>

        <!-- ================= Overview ================= -->
        <div class="sd-card">
            <h3 class="sd-card-title"><?php echo $this->lang->line('dashboard'); ?> Overview</h3>
            <div class="sd-tiles">
                <a class="sd-tile sd-tile-orange" href="<?php echo site_url('user/attendence'); ?>">
                    <span class="sd-tile-label"><?php echo $this->lang->line('attendance'); ?></span>
                    <span class="sd-tile-value"><?php echo $has_attendance ? $attendence_percentage . '%' : '--'; ?></span>
                    <span class="sd-tile-sub"><?php echo $attendance_low ? $this->lang->line('need_improvement') : 'Current session'; ?></span>
                    <i class="icon-calendar-check sd-tile-icon"></i>
                </a>
                <a class="sd-tile sd-tile-green" href="<?php echo site_url('user/homework'); ?>">
                    <span class="sd-tile-label"><?php echo $this->lang->line('homework'); ?></span>
                    <span class="sd-tile-value"><?php echo $homework_pending; ?></span>
                    <span class="sd-tile-sub"><?php echo $this->lang->line('pending'); ?> &middot; <?php echo count((array) $homeworklist); ?> assigned</span>
                    <i class="icon-flask-conical sd-tile-icon"></i>
                </a>
                <a class="sd-tile sd-tile-amber" href="<?php echo site_url('user/timetable'); ?>">
                    <span class="sd-tile-label">Classes Today</span>
                    <span class="sd-tile-value"><?php echo $today_classes; ?></span>
                    <span class="sd-tile-sub"><?php echo $this->lang->line($today_key) ?: $today_key; ?></span>
                    <i class="icon-calendar-clock sd-tile-icon"></i>
                </a>
                <a class="sd-tile sd-tile-purple" href="<?php echo site_url('user/syllabus/status'); ?>">
                    <span class="sd-tile-label"><?php echo $this->lang->line('syllabus_status'); ?></span>
                    <span class="sd-tile-value"><?php echo $syllabus_average; ?>%</span>
                    <span class="sd-tile-sub">Average across <?php echo $subject_count; ?> subjects</span>
                    <i class="icon-list-checks sd-tile-icon"></i>
                </a>
            </div>
        </div>

        <div class="row sd-row sd-row-eq">
            <?php if ($this->customlib->get_user_dashboard_setting_status('welcome_student')) {?>
            <!-- ================= Profile hero ================= -->
            <div class="col-lg-6 col-md-12">
                <div class="sd-card sd-hero">
                    <img src="<?php echo $file; ?>" class="sd-hero-photo" alt="">
                    <div class="sd-hero-body">
                        <span class="sd-hero-greet"><?php echo $this->lang->line('welcome'); ?>,</span>
                        <h2 class="sd-hero-name"><?php echo $studentsession_username; ?></h2>
                        <?php if ($attendance_low) {?>
                            <p class="sd-hero-note text-danger"><?php echo $this->lang->line('your_current_attendance_is'); ?> <?php echo $attendence_percentage; ?>% <?php echo $this->lang->line('which_is_lower_than'); ?> <?php echo $low_attendance_limit; ?>% <?php echo $this->lang->line('of_minimum_attendance_mark'); ?>.</p>
                        <?php } elseif ($has_attendance && $low_attendance_limit != '0.00') {?>
                            <p class="sd-hero-note text-success"><?php echo $this->lang->line('keep_going'); ?>! <?php echo $this->lang->line('your_current_attendance_is'); ?> <?php echo $attendence_percentage; ?>%.</p>
                        <?php }?>
                        <div class="sd-hero-codes">
                            <a href="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/qrcode/' . $student_data['student_id'] . '.png'); ?>" target="_blank">
                                <img class="qrcodeimg" src="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/qrcode/' . $student_data['student_id'] . '.png'); ?>" alt="QR"></a>
                            <a href="<?php echo $this->media_storage->getImageURL('uploads/student_id_card/barcodes/' . $student_data['student_id'] . '.png'); ?>" target="_blank">
                                <img src="<?php echo base_url('uploads/student_id_card/barcodes/' . $student_data['student_id'] . '.png'); ?>" alt="Barcode"></a>
                        </div>
                    </div>
                    <div class="sd-ring" style="--pct: <?php echo $attendance_ring; ?>; --ring-color: <?php echo $attendance_low ? '#ef4444' : '#10b981'; ?>;">
                        <div class="sd-ring-inner">
                            <strong><?php echo $has_attendance ? round($attendance_ring) . '%' : '--'; ?></strong>
                            <span><?php echo $this->lang->line('attendance'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <?php }?>

            <?php if ($this->customlib->get_user_dashboard_setting_status('notice_board') && $this->module_lib->hasActive('communicate') && $this->studentmodule_lib->hasActive('notice_board')) {?>
            <!-- ================= Notice board ================= -->
            <div class="col-lg-6 col-md-12">
                <div class="sd-card">
                    <div class="sd-card-head">
                        <h3 class="sd-card-title"><?php echo $this->lang->line('notice_board'); ?></h3>
                        <a class="sd-btn" href="<?php echo site_url('user/notification'); ?>"><?php echo $this->lang->line('view_all'); ?></a>
                    </div>
                    <div class="sd-list sd-list-short">
                        <?php if (!empty($notificationlist)) {
    foreach (array_slice($notificationlist, 0, 4) as $notification) {?>
                            <a class="sd-list-item" href="<?php echo site_url('user/notification'); ?>">
                                <span class="sd-avatar sd-avatar-amber"><i class="icon-bell"></i></span>
                                <span class="sd-list-main">
                                    <span class="sd-list-title"><?php echo $notification['title']; ?>
                                        <?php if ($notification['notification_id'] != "read") {?><span class="sd-dot"></span><?php }?>
                                    </span>
                                    <span class="sd-list-sub"><i class="icon-calendar"></i> <?php echo $date_fmt($notification['date']); ?></span>
                                </span>
                            </a>
                        <?php }
} else {?>
                            <img src="<?php echo $empty_img; ?>" width="140" class="center-block mt20">
                        <?php }?>
                    </div>
                </div>
            </div>
            <?php }?>
        </div>

        <div class="row sd-row sd-row-eq">
            <?php if ($this->customlib->get_user_dashboard_setting_status('subject_progress') && $this->module_lib->hasActive('lesson_plan') && $this->studentmodule_lib->hasActive('syllabus_status')) {?>
            <!-- ================= Subject progress ================= -->
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="sd-card">
                    <div class="sd-card-head">
                        <h3 class="sd-card-title"><?php echo $this->lang->line('subject_progress'); ?></h3>
                        <a class="sd-btn" href="<?php echo site_url('user/syllabus/status'); ?>"><?php echo $this->lang->line('view_all'); ?></a>
                    </div>
                    <div class="sd-list">
                        <?php if (!empty($subjects_data)) {
    foreach ($subjects_data as $value) {?>
                            <div class="sd-progress-row">
                                <div class="sd-progress-top">
                                    <span><?php echo $value['lebel']; ?></span>
                                    <strong><?php echo $value['complete']; ?>%</strong>
                                </div>
                                <div class="sd-progress"><span style="width: <?php echo (int) $value['complete']; ?>%"></span></div>
                            </div>
                        <?php }
} else {?>
                            <img src="<?php echo $empty_img; ?>" width="140" class="center-block mt20">
                        <?php }?>
                    </div>
                </div>
            </div>
            <?php }?>

            <?php if ($this->customlib->get_user_dashboard_setting_status('upcomming_class') && $this->module_lib->hasActive('academics') && $this->studentmodule_lib->hasActive('class_timetable')) {?>
            <!-- ================= Upcoming classes ================= -->
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="sd-card">
                    <div class="sd-card-head">
                        <h3 class="sd-card-title"><?php echo $this->lang->line('upcomming_class'); ?></h3>
                        <a class="sd-btn" href="<?php echo site_url('user/timetable'); ?>"><?php echo $this->lang->line('view_all'); ?></a>
                    </div>
                    <div class="sd-list">
                        <?php
$shown = 0;
    foreach ((array) $timetable as $day_rows) {
        foreach (array_slice((array) $day_rows, 0, 5) as $row) {
            $shown++;?>
                            <div class="sd-list-item">
                                <img src="<?php echo $staff_pic($row); ?>" class="sd-avatar-img" alt="">
                                <span class="sd-list-main">
                                    <span class="sd-list-title"><?php echo $row->subject_name . ($row->code != '' ? ' (' . $row->code . ')' : ''); ?></span>
                                    <span class="sd-list-sub"><?php echo $row->name . ' ' . $row->surname; ?></span>
                                </span>
                                <span class="sd-list-meta">
                                    <span class="sd-chip"><?php echo $row->time_from; ?> - <?php echo $row->time_to; ?></span>
                                    <?php if ($row->room_no != '') {?><span class="sd-list-sub"><?php echo $this->lang->line('room_no'); ?> <?php echo $row->room_no; ?></span><?php }?>
                                </span>
                            </div>
                        <?php }
    }
    if ($shown == 0) {?>
                            <img src="<?php echo $empty_img; ?>" width="140" class="center-block mt20">
                        <?php }?>
                    </div>
                </div>
            </div>
            <?php }?>

            <?php if ($this->customlib->get_user_dashboard_setting_status('homework') && $this->module_lib->hasActive('homework') && $this->studentmodule_lib->hasActive('homework')) {?>
            <!-- ================= Homework ================= -->
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="sd-card">
                    <div class="sd-card-head">
                        <h3 class="sd-card-title"><?php echo $this->lang->line('homework'); ?></h3>
                        <a class="sd-btn" href="<?php echo site_url('user/homework'); ?>"><?php echo $this->lang->line('view_all'); ?></a>
                    </div>
                    <div class="sd-list">
                        <?php if (!empty($homeworklist)) {
    foreach (array_slice($homeworklist, 0, 5) as $homework) {
        if ($homework["homework_evaluation_id"] != 0) {
            $hw_class = 'sd-chip-green';
            $hw_label = $this->lang->line("evaluated");
        } elseif ($homework["status"] == 'submitted') {
            $hw_class = 'sd-chip-amber';
            $hw_label = $this->lang->line('submitted');
        } else {
            $hw_class = 'sd-chip-red';
            $hw_label = $this->lang->line("pending");
        }?>
                            <a class="sd-list-item" href="<?php echo site_url('user/homework'); ?>">
                                <span class="sd-avatar sd-avatar-rose"><i class="icon-notebook-pen"></i></span>
                                <span class="sd-list-main">
                                    <span class="sd-list-title"><?php echo $homework['subject_name']; ?> (<?php echo $homework['subject_code']; ?>)</span>
                                    <span class="sd-list-sub"><?php echo $this->lang->line('submission_date'); ?>: <?php echo $date_fmt($homework['submit_date']); ?></span>
                                </span>
                                <span class="sd-chip <?php echo $hw_class; ?>"><?php echo $hw_label; ?></span>
                            </a>
                        <?php }
} else {?>
                            <img src="<?php echo $empty_img; ?>" width="140" class="center-block mt20">
                        <?php }?>
                    </div>
                </div>
            </div>
            <?php }?>

            <?php if ($this->customlib->get_user_dashboard_setting_status('teacher_list') && $this->module_lib->hasActive('human_resource')) {?>
            <!-- ================= Teachers ================= -->
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="sd-card">
                    <div class="sd-card-head">
                        <h3 class="sd-card-title"><?php echo $this->lang->line('teacher_list'); ?></h3>
                        <?php if ($this->studentmodule_lib->hasActive('teachers_rating')) {?>
                        <a class="sd-btn" href="<?php echo site_url('user/teacher'); ?>"><?php echo $this->lang->line('view_all'); ?></a>
                        <?php }?>
                    </div>
                    <div class="sd-list">
                        <?php if (!empty($teacherlist)) {
    foreach ($teacherlist as $teacher) {?>
                            <div class="sd-list-item">
                                <img src="<?php echo $staff_pic($teacher[0]); ?>" class="sd-avatar-img" alt="">
                                <span class="sd-list-main">
                                    <span class="sd-list-title"><?php echo $teacher[0]->name . " " . $teacher[0]->surname; ?></span>
                                    <span class="sd-list-sub"><?php echo $teacher[0]->employee_id; ?></span>
                                </span>
                                <?php if ($teacher[0]->class_teacher == $teacher[0]->staff_id) {?>
                                    <span class="sd-chip sd-chip-green"><?php echo $this->lang->line('class_teacher'); ?></span>
                                <?php }?>
                            </div>
                        <?php }
} else {?>
                            <img src="<?php echo $empty_img; ?>" width="140" class="center-block mt20">
                        <?php }?>
                    </div>
                </div>
            </div>
            <?php }?>

            <?php if ($this->customlib->get_user_dashboard_setting_status('visitor_list') && $this->module_lib->hasActive('front_office') && $this->studentmodule_lib->hasActive('visitor_book')) {?>
            <!-- ================= Visitors ================= -->
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="sd-card">
                    <div class="sd-card-head">
                        <h3 class="sd-card-title"><?php echo $this->lang->line('visitor_list'); ?></h3>
                        <a class="sd-btn" href="<?php echo site_url('user/visitors'); ?>"><?php echo $this->lang->line('view_all'); ?></a>
                    </div>
                    <div class="sd-list">
                        <?php if (!empty($visitor_list)) {
    foreach ($visitor_list as $visitor_value) {?>
                            <div class="sd-list-item">
                                <span class="sd-avatar sd-avatar-slate"><i class="icon-door-open"></i></span>
                                <span class="sd-list-main">
                                    <span class="sd-list-title"><?php echo $visitor_value['name']; ?></span>
                                    <span class="sd-list-sub"><?php echo $this->lang->line('purpose'); ?>: <?php echo $visitor_value['purpose']; ?></span>
                                </span>
                                <span class="sd-list-sub"><?php echo $date_fmt($visitor_value['date']); ?></span>
                            </div>
                        <?php }
} else {?>
                            <img src="<?php echo $empty_img; ?>" width="140" class="center-block mt20">
                        <?php }?>
                    </div>
                </div>
            </div>
            <?php }?>

            <?php if ($this->customlib->get_user_dashboard_setting_status('library') && $this->module_lib->hasActive('library') && $this->studentmodule_lib->hasActive('library')) {?>
            <!-- ================= Library ================= -->
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="sd-card">
                    <div class="sd-card-head">
                        <h3 class="sd-card-title"><?php echo $this->lang->line('library_book_issue_list'); ?></h3>
                        <a class="sd-btn" href="<?php echo site_url('user/book/issue'); ?>"><?php echo $this->lang->line('view_all'); ?></a>
                    </div>
                    <div class="sd-list">
                        <?php
$issued = array_filter((array) $bookList, function ($b) {return $b['is_returned'] == 0;});
    if (!empty($issued)) {
        foreach ($issued as $value) {?>
                            <div class="sd-list-item">
                                <span class="sd-avatar sd-avatar-fuchsia"><i class="icon-library"></i></span>
                                <span class="sd-list-main">
                                    <span class="sd-list-title"><?php echo $value['book_title']; ?></span>
                                    <span class="sd-list-sub"><?php echo $value['author']; ?> &middot; #<?php echo $value['book_no']; ?></span>
                                </span>
                                <?php if ($value['duereturn_date'] != '') {?>
                                    <span class="sd-chip sd-chip-amber"><?php echo $this->lang->line('due_return'); ?> <?php echo $date_fmt($value['duereturn_date']); ?></span>
                                <?php }?>
                            </div>
                        <?php }
    } else {?>
                            <img src="<?php echo $empty_img; ?>" width="140" class="center-block mt20">
                        <?php }?>
                    </div>
                </div>
            </div>
            <?php }?>
        </div>
    </section>
</div>

<style type="text/css">
/* ===== Student dashboard – mirrors the admin dashboard card system ===== */
.sd-dashboard { padding: 20px 22px; }
.sd-row { margin-left: -10px; margin-right: -10px; }
.sd-row > [class*="col-"] { padding-left: 10px; padding-right: 10px; }
.sd-row-eq { display: flex; flex-wrap: wrap; }
.sd-row-eq:before, .sd-row-eq:after { display: none; }
.sd-row-eq > [class*="col-"] { display: flex; flex-direction: column; float: none; }
.sd-row-eq > [class*="col-"] > .sd-card { flex: 1; }

.sd-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(15, 23, 42, 0.04);
    padding: 22px 24px;
    margin-bottom: 20px;
}
.sd-card-head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
    gap: 10px;
}
.sd-card-title {
    margin: 0 0 18px 0;
    font-size: 15px;
    font-weight: 600;
    color: #1e293b;
    text-transform: uppercase;
    letter-spacing: 1.6px;
}
.sd-card-head .sd-card-title { margin: 0; }
.sd-btn {
    flex-shrink: 0;
    border: 1px solid #cbd5e1;
    border-radius: 6px;
    padding: 6px 14px;
    font-size: 13px;
    font-weight: 500;
    color: #1e293b !important;
    background: #fff;
    text-decoration: none !important;
    transition: all .15s ease;
}
.sd-btn:hover { background: #f1f5f9; border-color: #94a3b8; }

.sd-alert { border-radius: 12px; border: 1px solid #bbf7d0; background: #f0fdf4 !important; color: #166534 !important; }
.sd-alert a { color: #166534 !important; font-weight: 600; }

/* --- Overview tiles --- */
.sd-tiles {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 20px;
}
.sd-tile {
    position: relative;
    display: flex;
    flex-direction: column;
    padding: 18px 20px;
    border-radius: 6px;
    border: 1px solid;
    text-decoration: none !important;
    overflow: hidden;
    transition: transform .15s ease, box-shadow .15s ease;
}
.sd-tile:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(15, 23, 42, 0.06); }
.sd-tile-label { font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: .3px; }
.sd-tile-value { font-size: 28px; font-weight: 700; color: #0f172a; line-height: 1.4; margin-top: 4px; }
.sd-tile-sub { font-size: 13px; color: #64748b; }
.sd-tile-icon { position: absolute; top: 14px; right: 16px; font-size: 18px; opacity: .8; }
.sd-tile-orange { background: #fffaf3; border-color: #fde7c7; }
.sd-tile-orange .sd-tile-label, .sd-tile-orange .sd-tile-icon { color: #c2410c; }
.sd-tile-green { background: #f3fdf8; border-color: #c6f1dc; }
.sd-tile-green .sd-tile-label, .sd-tile-green .sd-tile-icon { color: #047857; }
.sd-tile-amber { background: #fffbef; border-color: #fbe6b3; }
.sd-tile-amber .sd-tile-label, .sd-tile-amber .sd-tile-icon { color: #b45309; }
.sd-tile-purple { background: #faf6ff; border-color: #eadcfd; }
.sd-tile-purple .sd-tile-label, .sd-tile-purple .sd-tile-icon { color: #7e22ce; }

/* --- Profile hero --- */
.sd-hero { display: flex; align-items: center; gap: 22px; min-height: 196px; }
.sd-hero-photo { width: 104px; height: 104px; border-radius: 16px; object-fit: cover; background: #f1f5f9; flex-shrink: 0; }
.sd-hero-body { flex: 1; min-width: 0; }
.sd-hero-greet { font-size: 13px; color: #64748b; }
.sd-hero-name { margin: 2px 0 6px 0; font-size: 22px; font-weight: 700; color: #0f172a; }
.sd-hero-note { font-size: 13px; margin: 0 0 8px 0; }
.sd-hero-codes { display: flex; align-items: center; gap: 12px; margin-top: 8px; }
.sd-hero-codes img { height: 46px; width: auto; border-radius: 4px; }
.sd-ring {
    width: 116px; height: 116px; border-radius: 50%; flex-shrink: 0;
    background: conic-gradient(var(--ring-color) calc(var(--pct) * 1%), #eef2f7 0);
    display: flex; align-items: center; justify-content: center;
}
.sd-ring-inner {
    width: 88px; height: 88px; border-radius: 50%; background: #fff;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
}
.sd-ring-inner strong { font-size: 20px; color: #0f172a; }
.sd-ring-inner span { font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: .4px; }

/* --- Lists --- */
.sd-list { height: 300px; overflow-y: auto; margin: 0 -6px; padding: 0 6px; scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent; }
.sd-list-short { height: 196px; }
.sd-list-item {
    display: flex; align-items: center; gap: 12px;
    padding: 10px 12px; border-radius: 10px; margin-bottom: 6px;
    background: #f8fafc; border: 1px solid #f1f5f9;
    text-decoration: none !important; color: inherit;
    transition: background .15s ease;
}
a.sd-list-item:hover { background: #f1f5f9; }
.sd-list-main { flex: 1; min-width: 0; display: flex; flex-direction: column; }
.sd-list-title { font-size: 13px; font-weight: 600; color: #1e293b; line-height: 1.35; word-break: break-word; }
.sd-list-sub { font-size: 12px; color: #64748b; }
.sd-list-meta { display: flex; flex-direction: column; align-items: flex-end; gap: 3px; flex-shrink: 0; }
.sd-avatar-img { width: 38px; height: 38px; border-radius: 50%; object-fit: cover; flex-shrink: 0; background: #e2e8f0; }
.sd-avatar { width: 38px; height: 38px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center; font-size: 17px; flex-shrink: 0; }
.sd-avatar-amber { background: rgba(245, 158, 11, .12); color: #f59e0b; }
.sd-avatar-rose { background: rgba(244, 63, 94, .12); color: #f43f5e; }
.sd-avatar-slate { background: rgba(100, 116, 139, .12); color: #64748b; }
.sd-avatar-fuchsia { background: rgba(217, 70, 239, .12); color: #d946ef; }
.sd-dot { display: inline-block; width: 7px; height: 7px; border-radius: 50%; background: #ef4444; margin-left: 6px; vertical-align: middle; }

.sd-chip { font-size: 11px; font-weight: 600; padding: 3px 9px; border-radius: 20px; background: #eef2ff; color: #4338ca; white-space: nowrap; flex-shrink: 0; }
.sd-chip-green { background: #dcfce7; color: #15803d; }
.sd-chip-amber { background: #fef3c7; color: #b45309; }
.sd-chip-red { background: #fee2e2; color: #b91c1c; }

.sd-progress-row { padding: 10px 4px; border-bottom: 1px solid #f1f5f9; }
.sd-progress-top { display: flex; justify-content: space-between; font-size: 13px; color: #334155; margin-bottom: 6px; gap: 10px; }
.sd-progress { height: 6px; border-radius: 6px; background: #eef2f7; overflow: hidden; }
.sd-progress span { display: block; height: 100%; border-radius: 6px; background: var(--primary-theme-color, #4f46e5); }

@media (max-width: 1199px) { .sd-tiles { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
@media (max-width: 767px) {
    .sd-dashboard { padding: 14px 12px; }
    .sd-tiles { grid-template-columns: 1fr 1fr; gap: 12px; }
    .sd-tile-value { font-size: 22px; }
    .sd-hero { flex-wrap: wrap; }
    .sd-ring { margin: 0 auto; }
}
</style>

<script type="text/javascript">
    $(document).ready(function() {
        $(document).on('click', '.close_notice', function() {
            var data = $(this).data();

            $.ajax({
                type: "POST",
                url: "<?php echo site_url("user/notification/read") ?>",
                data: {
                    'notice': data.noticeid
                },
                dataType: "json",
                success: function(data) {
                    if (data.status == "fail") {
                        errorMsg(data.msg);
                    } else {
                        successMsg(data.msg);
                    }
                }
            });
        });
    });
</script>
