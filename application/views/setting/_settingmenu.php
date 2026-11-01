<style>
    /* ---------- Settings menu ---------- */
    .settings-nav-card {
        background: #ffffff;
        border-radius: 14px;
        border: 1px solid #e9eef5;
        box-shadow: 0 2px 12px rgba(15,23,42,0.03);
        overflow: hidden;
        margin-bottom: 20px;
    }
    @media (min-width: 768px) {
        .settings-nav-card { position: sticky; top: 60px; max-height: calc(100vh - 80px); display: flex; flex-direction: column; }
        .settings-nav-scroll { overflow-y: auto; }
    }
    .settings-nav-header {
        padding: 14px 16px 12px;
        border-bottom: 1px solid #f1f5f9;
    }
    .settings-nav-title {
        font-weight: 800; font-size: 14px; color: #0f172a;
        display: flex; align-items: center; gap: 8px; margin-bottom: 10px;
    }
    .settings-nav-title i { color: var(--primary-theme-color, #4f46e5); }
    .settings-nav-search { position: relative; }
    .settings-nav-search i { position: absolute; left: 11px; top: 10px; color: #94a3b8; font-size: 12px; }
    .settings-nav-search input {
        width: 100%; height: 34px; border-radius: 8px; border: 1px solid #e2e8f0; background: #f8fafc;
        padding: 0 10px 0 30px; font-size: 12.5px; outline: none; transition: all .15s;
    }
    .settings-nav-search input:focus { background: #fff; border-color: var(--primary-theme-color, #4f46e5); box-shadow: 0 0 0 3px rgba(79,70,229,.1); }
    .settings-nav-group {
        padding: 12px 22px 4px; font-size: 10.5px; font-weight: 800; letter-spacing: .08em;
        text-transform: uppercase; color: #94a3b8;
    }
    .settings-nav-list {
        list-style: none;
        padding: 0 8px 4px;
        margin: 0;
    }
    .settings-nav-list li { margin-bottom: 2px; }
    .settings-nav-list li a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        border-radius: 8px;
        color: #475569;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.15s ease-in-out;
    }
    .settings-nav-list li a:hover { background: #f1f5f9; color: #0f172a; }
    .settings-nav-list li.active a,
    .settings-nav-list li a.active {
        background: var(--primary-theme-color, #4f46e5) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.25);
    }
    .settings-nav-list li a i {
        font-size: 13px; width: 26px; height: 26px; flex: 0 0 26px; border-radius: 7px;
        display: inline-flex; align-items: center; justify-content: center;
        background: #f1f5f9; color: #64748b;
    }
    .settings-nav-list li.active a i,
    .settings-nav-list li a.active i { background: rgba(255,255,255,.2); color: #fff; }
    .settings-nav-empty { display: none; padding: 16px; text-align: center; font-size: 12.5px; color: #94a3b8; }

    /* ---------- Shared theme for every settings page ---------- */
    body.settings-ui .content-header h1 { font-weight: 800; color: #0f172a; }
    body.settings-ui .content > .row > [class*="col-lg-9"] > .box,
    body.settings-ui .content > .row > [class*="col-lg-9"] .box.box-primary {
        border: 1px solid #e9eef5; border-top: 1px solid #e9eef5; border-radius: 14px;
        box-shadow: 0 2px 12px rgba(15,23,42,.03); overflow: visible; margin-bottom: 16px;
    }
    body.settings-ui .box > .box-header {
        padding: 16px 20px !important; border-bottom: 1px solid #e9eef5; border-radius: 14px 14px 0 0;
        background: #fff; display: flex; align-items: center; justify-content: space-between; gap: 10px; flex-wrap: wrap;
    }
    body.settings-ui .box > .box-header::before, body.settings-ui .box > .box-header::after { display: none; }
    body.settings-ui .box > .box-header .box-title {
        font-size: 16px !important; font-weight: 800; color: #0f172a; display: flex; align-items: center; gap: 10px; float: none;
    }
    body.settings-ui .box > .box-header .box-title > i:first-child {
        width: 34px; height: 34px; border-radius: 10px; display: inline-flex; align-items: center; justify-content: center;
        background: rgba(79,70,229,.08); color: var(--primary-theme-color, #4f46e5); font-size: 15px;
    }
    body.settings-ui .box > .box-header .box-tools { position: static; float: none; }
    body.settings-ui .box-body { padding: 18px 20px; }

    /* Each setting row reads as a list item: label on the left, control on the right */
    body.settings-ui .box-body .form-group.row {
        display: flex; align-items: center; flex-wrap: wrap;
        margin-left: 0; margin-right: 0; margin-bottom: 0; padding: 14px 0; border-bottom: 1px solid #f1f5f9;
    }
    body.settings-ui .box-body .form-group.row > label { font-weight: 700; color: #334155; font-size: 13px; margin-bottom: 0; padding-left: 0; }
    body.settings-ui .box-body [class*="col-"]:last-child > .form-group.row:last-child { border-bottom: 0; }
    body.settings-ui .box-body .form-group > label:not(.radio-inline):not(.checkbox-inline):not(.label-info-success) { font-weight: 700; color: #334155; font-size: 13px; }

    body.settings-ui .box-body .form-control {
        border-radius: 9px; border: 1px solid #dbe2ea; box-shadow: none; font-size: 13.5px;
        transition: border-color .15s, box-shadow .15s;
    }
    body.settings-ui .box-body input.form-control:not(.input-sm), body.settings-ui .box-body select.form-control:not(.input-sm) { min-height: 38px; }
    body.settings-ui .box-body .form-control:focus,
    body.settings-ui .modal .form-control:focus { border-color: var(--primary-theme-color, #4f46e5); box-shadow: 0 0 0 3px rgba(79,70,229,.12); }
    body.settings-ui .box-body .input-group .form-control:not(:last-child) { border-radius: 9px 0 0 9px; }
    body.settings-ui .box-body .input-group .form-control:last-child { border-radius: 0 9px 9px 0; }
    body.settings-ui .box-body .input-group-addon { border-color: #dbe2ea; background: #f8fafc; color: #64748b; }
    body.settings-ui .box-body .input-group-addon:first-child { border-radius: 9px 0 0 9px; }
    body.settings-ui .box-body .input-group-addon:last-child { border-radius: 0 9px 9px 0; }

    body.settings-ui .radio-inline, body.settings-ui .checkbox-inline {
        padding: 7px 12px 7px 30px; margin: 3px 6px 3px 0; border: 1px solid #e2e8f0; border-radius: 8px;
        background: #f8fafc; font-weight: 600; font-size: 12.5px; color: #334155; transition: all .15s;
    }
    body.settings-ui .radio-inline:hover, body.settings-ui .checkbox-inline:hover { border-color: #cbd5e1; background: #fff; }
    body.settings-ui .radio-inline input, body.settings-ui .checkbox-inline input { margin-left: -20px; accent-color: var(--primary-theme-color, #4f46e5); }
    body.settings-ui .radio-inline + .radio-inline, body.settings-ui .checkbox-inline + .checkbox-inline { margin-left: 0; }

    body.settings-ui .settinghr, body.settings-ui .session-head {
        font-size: 12px; font-weight: 800; color: #0f172a; text-transform: uppercase; letter-spacing: .06em;
        border: 0; padding: 18px 0 8px; margin: 0 0 4px; background: none;
    }
    body.settings-ui .settinghr { border-top: 1px solid #e9eef5; height: 0; padding: 0; margin: 14px 0; }

    body.settings-ui .box-footer {
        position: sticky; bottom: 0; z-index: 15; background: rgba(255,255,255,.96); backdrop-filter: blur(6px);
        border-top: 1px solid #e9eef5; border-radius: 0 0 14px 14px; padding: 12px 20px;
    }
    body.settings-ui .box-footer::after { content: ""; display: table; clear: both; }
    body.settings-ui .box-footer .btn, body.settings-ui .box-header .btn { border-radius: 9px; font-weight: 700; }
    body.settings-ui .box-footer .btn-primary { padding: 8px 24px; }

    body.settings-ui .alert { border-radius: 10px; }
    body.settings-ui .table > thead > tr > th { background: #f8fafc; font-size: 12px; text-transform: uppercase; letter-spacing: .04em; color: #64748b; border-bottom: 1px solid #e9eef5; }
    body.settings-ui .modal-content { border-radius: 14px; border: 0; overflow: hidden; box-shadow: 0 20px 50px rgba(15,23,42,.2); }
    body.settings-ui .modal-header { border-bottom: 1px solid #e9eef5; }
    body.settings-ui .modal-title { font-weight: 800; }

    @media (max-width: 767px) {
        body.settings-ui .box-body .form-group.row { display: block; }
        body.settings-ui .box-body .form-group.row > label { display: block; margin-bottom: 8px; }
    }
</style>
<script>document.body.classList.add('settings-ui');</script>

<div class="col-lg-3 col-md-4 col-sm-4">
    <div class="settings-nav-card">
        <div class="settings-nav-header">
            <div class="settings-nav-title"><i class="fa fa-cogs"></i> System Settings</div>
            <div class="settings-nav-search">
                <i class="fa fa-search"></i>
                <input type="text" id="settings_nav_filter" placeholder="Find a setting..." autocomplete="off">
            </div>
        </div>
        <div class="settings-nav-scroll">
            <div class="settings-nav-group">School</div>
            <ul class="settings-nav-list">
                <li class="<?php echo set_SubSubmenu('schsettings/index'); ?>">
                    <a class="<?php echo set_SubSubmenu('schsettings/index'); ?>" href="<?php echo site_url('schsettings') ?>"><i class="fa fa-sliders"></i> <?php echo $this->lang->line('general_setting'); ?></a>
                </li>
                <li class="<?php echo set_SubSubmenu('schsettings/logo'); ?>">
                    <a class="<?php echo set_SubSubmenu('schsettings/logo'); ?>" href="<?php echo site_url('schsettings/logo') ?>"><i class="fa fa-picture-o"></i> <?php echo $this->lang->line('logo'); ?></a>
                </li>
                <li class="<?php echo set_SubSubmenu('schsettings/signature'); ?>">
                    <a class="<?php echo set_SubSubmenu('schsettings/signature'); ?>" href="<?php echo site_url('schsettings/signature') ?>"><i class="fa fa-pencil-square-o"></i> Signatures</a>
                </li>
                <li class="<?php echo set_SubSubmenu('schsettings/login_page_background'); ?>">
                    <a class="<?php echo set_SubSubmenu('schsettings/login_page_background'); ?>" href="<?php echo site_url('schsettings/login_page_background') ?>"><i class="fa fa-desktop"></i> <?php echo $this->lang->line('login_page_background'); ?></a>
                </li>
                <li class="<?php echo set_SubSubmenu('schsettings/backendtheme'); ?>">
                    <a class="<?php echo set_SubSubmenu('schsettings/backendtheme'); ?>" href="<?php echo site_url('schsettings/backendtheme') ?>"><i class="fa fa-paint-brush"></i> <?php echo $this->lang->line('backend_theme'); ?></a>
                </li>
            </ul>

            <div class="settings-nav-group">Operations</div>
            <ul class="settings-nav-list">
                <li class="<?php echo set_SubSubmenu('schsettings/studentguardianpanel'); ?>">
                    <a class="<?php echo set_SubSubmenu('schsettings/studentguardianpanel'); ?>" href="<?php echo site_url('schsettings/studentguardianpanel') ?>"><i class="fa fa-users"></i> <?php echo $this->lang->line('student_guardian_panel'); ?></a>
                </li>
                <li class="<?php echo set_SubSubmenu('schsettings/fees'); ?>">
                    <a class="<?php echo set_SubSubmenu('schsettings/fees'); ?>" href="<?php echo site_url('schsettings/fees') ?>"><i class="fa fa-money"></i> <?php echo $this->lang->line('fees'); ?></a>
                </li>
                <li class="<?php echo set_SubSubmenu('schsettings/idautogeneration'); ?>">
                    <a class="<?php echo set_SubSubmenu('schsettings/idautogeneration'); ?>" href="<?php echo site_url('schsettings/idautogeneration') ?>"><i class="fa fa-id-card-o"></i> <?php echo $this->lang->line('id_auto_generation'); ?></a>
                </li>
                <li class="<?php echo set_SubSubmenu('schsettings/attendancetype'); ?>">
                    <a class="<?php echo set_SubSubmenu('schsettings/attendancetype'); ?>" href="<?php echo site_url('schsettings/attendancetype') ?>"><i class="fa fa-calendar-check-o"></i> <?php echo $this->lang->line('attendance_type'); ?></a>
                </li>
                <li class="<?php echo set_SubSubmenu('schsettings/maintenance'); ?>">
                    <a class="<?php echo set_SubSubmenu('schsettings/maintenance'); ?>" href="<?php echo site_url('schsettings/maintenance') ?>"><i class="fa fa-wrench"></i> <?php echo $this->lang->line('maintenance'); ?></a>
                </li>
                <li class="<?php echo set_SubSubmenu('schsettings/miscellaneous'); ?>">
                    <a class="<?php echo set_SubSubmenu('schsettings/miscellaneous'); ?>" href="<?php echo site_url('schsettings/miscellaneous') ?>"><i class="fa fa-th-list"></i> <?php echo $this->lang->line('miscellaneous'); ?></a>
                </li>
                <li class="<?php echo set_SubSubmenu('schsettings/cbse_disclosure'); ?>">
                    <a class="<?php echo set_SubSubmenu('schsettings/cbse_disclosure'); ?>" href="<?php echo site_url('schsettings/cbse_disclosure') ?>"><i class="fa fa-file-text-o"></i> CBSE Mandatory Disclosure</a>
                </li>
            </ul>

            <div class="settings-nav-group">Apps &amp; Integrations</div>
            <ul class="settings-nav-list">
                <li class="<?php echo set_SubSubmenu('schsettings/mobileapp'); ?>">
                    <a class="<?php echo set_SubSubmenu('schsettings/mobileapp'); ?>" href="<?php echo site_url('schsettings/mobileapp') ?>"><i class="fa fa-mobile"></i> <?php echo $this->lang->line('mobile_app'); ?></a>
                </li>
                <li class="<?php echo set_SubSubmenu('schsettings/googledrivesetting'); ?>">
                    <a class="<?php echo set_SubSubmenu('schsettings/googledrivesetting'); ?>" href="<?php echo site_url('schsettings/googledrivesetting') ?>"><i class="fa fa-cloud"></i> <?php echo $this->lang->line('google_drive_setting'); ?></a>
                </li>
                <li class="<?php echo set_SubSubmenu('schsettings/whatsappsettings'); ?>">
                    <a class="<?php echo set_SubSubmenu('schsettings/whatsappsettings'); ?>" href="<?php echo site_url('schsettings/whatsappsettings') ?>"><i class="fa fa-whatsapp"></i> <?php echo $this->lang->line('whatsapp_settings'); ?></a>
                </li>
                <?php if ($this->module_lib->hasActive('chat')) { ?>
                <li class="<?php echo set_SubSubmenu('schsettings/chatsetting'); ?>">
                    <a class="<?php echo set_SubSubmenu('schsettings/chatsetting'); ?>" href="<?php echo site_url('schsettings/chatsetting') ?>"><i class="fa fa-commenting-o"></i> <?php echo $this->lang->line('chat'); ?></a>
                </li>
                <?php } ?>
                <li class="<?php echo set_SubSubmenu('admin/systemnotificationsetting'); ?>">
                    <a class="<?php echo set_SubSubmenu('admin/systemnotificationsetting'); ?>" href="<?php echo site_url('admin/systemnotificationsetting') ?>"><i class="fa fa-bell-o"></i> System Notifications</a>
                </li>
                <li class="<?php echo set_SubSubmenu('admin/aisetting'); ?>">
                    <a class="<?php echo set_SubSubmenu('admin/aisetting'); ?>" href="<?php echo site_url('admin/aisetting') ?>"><i class="fa fa-magic"></i> AI API Keys & Engines</a>
                </li>
            </ul>
            <div class="settings-nav-empty" id="settings_nav_empty">No matching settings</div>
        </div>
    </div>
</div><!--./col-md-3-->
<script>
    (function () {
        var input = document.getElementById('settings_nav_filter');
        if (!input) return;
        input.addEventListener('input', function () {
            var q = this.value.trim().toLowerCase(), any = false;
            document.querySelectorAll('.settings-nav-list').forEach(function (ul) {
                var shown = 0;
                ul.querySelectorAll('li').forEach(function (li) {
                    var hit = li.textContent.toLowerCase().indexOf(q) !== -1;
                    li.style.display = hit ? '' : 'none';
                    if (hit) shown++;
                });
                ul.style.display = shown ? '' : 'none';
                ul.previousElementSibling.style.display = shown ? '' : 'none';
                if (shown) any = true;
            });
            document.getElementById('settings_nav_empty').style.display = any ? 'none' : 'block';
        });
        input.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                var first = document.querySelector('.settings-nav-list li:not([style*="none"]) a');
                if (first) window.location = first.href;
            }
        });
    })();
</script>
