<?php
$today_key = date('l');
$day_keys  = array_keys((array) $timetable);
if (!in_array($today_key, $day_keys, true)) {
    $today_key = $day_keys[0] ?? '';
}
$palette = ['#6366f1', '#10b981', '#f59e0b', '#ec4899', '#0ea5e9', '#8b5cf6', '#f97316', '#14b8a6', '#ef4444', '#84cc16'];
$subject_color = function ($name) use ($palette) {
    return $palette[abs(crc32((string) $name)) % count($palette)];
};
$is_break = function ($row) {
    return isset($row->period_type) && $row->period_type == 'break';
};
?>
<div class="content-wrapper">
    <section class="content tt-page">
        <div class="sp-page-head">
            <div>
                <h1 class="sp-page-title"><?php echo $this->lang->line('class_timetable'); ?></h1>
                <p class="sp-page-sub"><?php echo date('l, j M Y'); ?></p>
            </div>
            <?php if (!empty($timetable)) {?>
            <div class="tt-actions">
                <div class="tt-toggle" role="tablist">
                    <button type="button" class="tt-toggle-btn" data-view="day">Day</button>
                    <button type="button" class="tt-toggle-btn" data-view="week">Week</button>
                </div>
                <button type="button" title="<?php echo $this->lang->line('print'); ?>" class="sp-btn print_timetable hidden-xs" data-loading-text="<i class='fa fa-spinner fa-spin'></i>"><i class="icon-printer"></i> <?php echo $this->lang->line('print'); ?></button>
            </div>
            <?php }?>
        </div>

        <?php if (empty($timetable)) {?>
            <div class="sd-card tt-empty">
                <img src="https://smart-school.in/ssappresource/images/addnewitem.svg" width="150" class="center-block">
                <p><?php echo $this->lang->line('not_scheduled'); ?></p>
            </div>
        <?php } else {?>

        <!-- Day chips -->
        <div class="tt-days" role="tablist">
            <?php foreach ($timetable as $day => $rows) {
    $count = 0;
    foreach ((array) $rows as $r) {
        if (!$is_break($r)) {
            $count++;
        }
    }?>
            <button type="button" class="tt-day <?php echo $day === $today_key ? 'active' : ''; ?>" data-day="<?php echo $day; ?>">
                <span class="tt-day-name"><?php echo substr($this->lang->line(strtolower($day)) ?: $day, 0, 3); ?></span>
                <span class="tt-day-count"><?php echo $count; ?></span>
                <?php if ($day === date('l')) {?><span class="tt-day-today">Today</span><?php }?>
            </button>
            <?php }?>
        </div>

        <!-- Periods: one column per day (Week view shows them side by side) -->
        <div class="tt-grid">
            <?php foreach ($timetable as $day => $rows) {?>
            <div class="tt-col <?php echo $day === $today_key ? 'active' : ''; ?>" data-day="<?php echo $day; ?>">
                <div class="tt-col-head"><?php echo $this->lang->line(strtolower($day)) ?: $day; ?></div>
                <?php if (empty($rows)) {?>
                    <div class="tt-free"><i class="icon-coffee"></i> <?php echo $this->lang->line('not_scheduled'); ?></div>
                <?php } else {
        foreach ($rows as $row) {
            if ($is_break($row)) {?>
                    <div class="tt-break">
                        <i class="icon-coffee"></i> <?php echo $row->break_label; ?>
                        <span><?php echo $row->time_from; ?> - <?php echo $row->time_to; ?></span>
                    </div>
                <?php } else {
                $color = $subject_color($row->subject_name);?>
                    <div class="tt-period" style="--c: <?php echo $color; ?>;">
                        <div class="tt-time">
                            <strong><?php echo $row->time_from; ?></strong>
                            <span><?php echo $row->time_to; ?></span>
                        </div>
                        <div class="tt-body">
                            <div class="tt-subject">
                                <?php echo $row->subject_name != '' ? $row->subject_name : '<span class="text-danger">' . $this->lang->line('not_scheduled') . '</span>'; ?>
                                <?php if ($row->code != '') {?><span class="tt-code"><?php echo $row->code; ?></span><?php }?>
                            </div>
                            <div class="tt-meta">
                                <?php if ($row->name != '') {?><span><i class="icon-user-round"></i> <?php echo $row->name . ' ' . $row->surname; ?></span><?php }?>
                                <?php if ($row->room_no != '') {?><span><i class="icon-door-open"></i> <?php echo $this->lang->line('room_no'); ?> <?php echo $row->room_no; ?></span><?php }?>
                            </div>
                        </div>
                    </div>
                <?php }
        }
    }?>
            </div>
            <?php }?>
        </div>
        <?php }?>
    </section>
</div>

<style type="text/css">
.tt-page { padding: 20px 22px; }
.tt-actions { display: flex; align-items: center; gap: 10px; }
.tt-toggle { display: inline-flex; background: #eef2f7; border-radius: 10px; padding: 3px; }
.tt-toggle-btn { border: 0; background: transparent; padding: 6px 14px; border-radius: 8px; font-size: 13px; font-weight: 600; color: #64748b; }
.tt-toggle-btn.active { background: #fff; color: #0f172a; box-shadow: 0 1px 3px rgba(15, 23, 42, 0.1); }
.tt-empty { text-align: center; color: #64748b; }

.tt-days { display: flex; gap: 8px; overflow-x: auto; padding: 10px 2px 12px; margin-bottom: 6px; scrollbar-width: none; -webkit-overflow-scrolling: touch; }
.tt-days::-webkit-scrollbar { display: none; }
.tt-day {
    position: relative; flex: 0 0 auto; min-width: 64px;
    display: flex; flex-direction: column; align-items: center; gap: 2px;
    padding: 10px 12px 8px; border-radius: 14px;
    border: 1px solid #e2e8f0; background: #fff; color: #334155;
    transition: all .15s ease;
}
.tt-day-name { font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: .4px; }
.tt-day-count { font-size: 11px; color: #94a3b8; }
.tt-day-today { position: absolute; top: -8px; left: 50%; transform: translateX(-50%); font-size: 9px; font-weight: 700; text-transform: uppercase; background: #10b981; color: #fff; border-radius: 6px; padding: 1px 6px; }
.tt-day.active { background: var(--primary-theme-color, #6366f1); border-color: transparent; color: #fff; box-shadow: 0 6px 16px rgba(99, 102, 241, 0.25); }
.tt-day.active .tt-day-count { color: rgba(255, 255, 255, 0.8); }

.tt-grid { display: block; }
.tt-col { display: none; }
.tt-col.active { display: block; }
.tt-col-head { display: none; }

.tt-period {
    display: flex; gap: 14px; align-items: stretch;
    background: #fff; border: 1px solid #e2e8f0; border-left: 4px solid var(--c);
    border-radius: 14px; padding: 12px 14px; margin-bottom: 10px;
    box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
}
.tt-time { display: flex; flex-direction: column; justify-content: center; min-width: 70px; padding-right: 12px; border-right: 1px dashed #e2e8f0; }
.tt-time strong { font-size: 14px; color: #0f172a; }
.tt-time span { font-size: 12px; color: #94a3b8; }
.tt-body { flex: 1; min-width: 0; }
.tt-subject { font-size: 15px; font-weight: 600; color: #0f172a; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.tt-code { font-size: 11px; font-weight: 600; color: var(--c); background: color-mix(in srgb, var(--c) 12%, white); border-radius: 6px; padding: 2px 7px; }
.tt-meta { display: flex; flex-wrap: wrap; gap: 4px 14px; margin-top: 4px; font-size: 12.5px; color: #64748b; }
.tt-meta i { margin-right: 3px; }
.tt-break, .tt-free {
    display: flex; align-items: center; gap: 8px; justify-content: center;
    padding: 9px 12px; margin-bottom: 10px; border-radius: 12px;
    background: repeating-linear-gradient(-45deg, #f8fafc, #f8fafc 8px, #f1f5f9 8px, #f1f5f9 16px);
    color: #64748b; font-size: 13px; font-weight: 500;
}
.tt-break span { font-size: 12px; color: #94a3b8; }

/* Week view: all days side by side */
.tt-page.is-week .tt-days { display: none; }
.tt-page.is-week .tt-grid { display: grid; grid-template-columns: repeat(<?php echo max(1, count((array) $timetable)); ?>, minmax(170px, 1fr)); gap: 12px; overflow-x: auto; padding-bottom: 6px; }
.tt-page.is-week .tt-col { display: block; }
.tt-page.is-week .tt-col-head { display: block; font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: #475569; padding: 4px 4px 10px; }
.tt-page.is-week .tt-period { flex-direction: column; gap: 6px; padding: 10px 12px; }
.tt-page.is-week .tt-time { flex-direction: row; gap: 6px; align-items: baseline; border-right: 0; padding: 0; min-width: 0; }
.tt-page.is-week .tt-time span:before { content: "– "; }
.tt-page.is-week .tt-subject { font-size: 13.5px; }

@media (max-width: 767px) {
    .tt-page { padding: 12px !important; }
    .tt-toggle { display: none; }
}
</style>

<script>
    (function () {
        var $page = $('.tt-page');
        var storeKey = 'student_timetable_view';
        function setView(view) {
            if ($(window).width() < 768) {
                view = 'day';
            }
            $page.toggleClass('is-week', view === 'week');
            $('.tt-toggle-btn').removeClass('active').filter('[data-view="' + view + '"]').addClass('active');
            try { localStorage.setItem(storeKey, view); } catch (e) {}
        }
        var saved = null;
        try { saved = localStorage.getItem(storeKey); } catch (e) {}
        setView(saved || ($(window).width() >= 1200 ? 'week' : 'day'));

        $(document).on('click', '.tt-toggle-btn', function () {
            setView($(this).data('view'));
        });
        $(document).on('click', '.tt-day', function () {
            var day = $(this).data('day');
            $('.tt-day').removeClass('active');
            $(this).addClass('active');
            $('.tt-col').removeClass('active').filter('[data-day="' + day + '"]').addClass('active');
        });
        // Keep today's chip in view on phones
        var active = document.querySelector('.tt-day.active');
        if (active && active.scrollIntoView) {
            active.scrollIntoView({inline: 'center', block: 'nearest'});
        }
    })();

    $(document).on('click', '.print_timetable', function(e) {
        e.preventDefault();
        var $this = $(this);

        $.ajax({
            url: baseurl  + 'user/timetable/printclasstimetable',
            type: "POST",
            data: {},
            dataType: 'Json',
            beforeSend: function() {
                $this.button('loading');
            },
            success: function(data, textStatus, jqXHR) {
                Popup(data.page);
                $this.button('reset');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $this.button('reset');
            },
            complete: function() {
                $this.button('reset');
            }
        });
    });

    function Popup(data, winload = false) {
        var frameDoc = window.open('', 'Print-Window');
        frameDoc.document.open();
        //Create a new HTML document.
        frameDoc.document.write('<html>');
        frameDoc.document.write('<head>');
        frameDoc.document.write('<title></title>');
        frameDoc.document.write('<link rel="stylesheet" href="' + baseurl  + 'backend/bootstrap/css/bootstrap.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + baseurl  + 'backend/dist/css/font-awesome.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + baseurl  + 'backend/dist/css/ionicons.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + baseurl  + 'backend/dist/css/AdminLTE.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + baseurl  + 'backend/dist/css/skins/_all-skins.min.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + baseurl  + 'backend/plugins/iCheck/flat/blue.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + baseurl  + 'backend/plugins/morris/morris.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + baseurl  + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + baseurl  + 'backend/plugins/datepicker/datepicker3.css">');
        frameDoc.document.write('<link rel="stylesheet" href="' + baseurl  + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
        frameDoc.document.write('</head>');
        frameDoc.document.write('<body onload="window.print()">');
        frameDoc.document.write(data);
        frameDoc.document.write('</body>');
        frameDoc.document.write('</html>');
        frameDoc.document.close();
        setTimeout(function() {
            frameDoc.close();
            if (winload) {
                window.location.reload(true);
            }
        }, 5000);

        return true;
    }
</script>
