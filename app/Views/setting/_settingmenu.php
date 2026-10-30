<style>
    .settings-nav-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 2px 12px rgba(0,0,0,0.02);
        overflow: hidden;
        margin-bottom: 20px;
    }
    .settings-nav-header {
        padding: 14px 16px;
        background: #f8fafc;
        border-bottom: 1px solid #f1f5f9;
        font-weight: 800;
        font-size: 13px;
        color: #0f172a;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .settings-nav-list {
        list-style: none;
        padding: 6px 8px;
        margin: 0;
    }
    .settings-nav-list li {
        margin-bottom: 2px;
    }
    .settings-nav-list li a {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 9px 14px;
        border-radius: 8px;
        color: #475569;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.15s ease-in-out;
    }
    .settings-nav-list li a:hover {
        background: #f1f5f9;
        color: #0f172a;
    }
    .settings-nav-list li.active a,
    .settings-nav-list li a.active {
        background: var(--primary-theme-color, #4f46e5) !important;
        color: #ffffff !important;
        box-shadow: 0 4px 10px rgba(79, 70, 229, 0.25);
    }
    .settings-nav-list li a i {
        font-size: 14px;
        width: 18px;
        text-align: center;
        opacity: 0.85;
    }
    .settings-nav-list li.active a i,
    .settings-nav-list li a.active i {
        opacity: 1;
    }
</style>

<div class="col-lg-3 col-md-4 col-sm-4"> 
    <div class="settings-nav-card">
        <div class="settings-nav-header">
            <i class="fa fa-cogs text-primary"></i> System Settings
        </div>
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
            <li class="<?php echo set_SubSubmenu('schsettings/mobileapp'); ?>">
                <a class="<?php echo set_SubSubmenu('schsettings/mobileapp'); ?>" href="<?php echo site_url('schsettings/mobileapp') ?>"><i class="fa fa-mobile" style="font-size:18px;"></i> <?php echo $this->lang->line('mobile_app'); ?></a>
            </li>
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
            <li class="<?php echo set_SubSubmenu('schsettings/maintenance'); ?>">
                <a class="<?php echo set_SubSubmenu('schsettings/maintenance'); ?>" href="<?php echo site_url('schsettings/maintenance') ?>"><i class="fa fa-wrench"></i> <?php echo $this->lang->line('maintenance'); ?></a>  
            </li>
            <li class="<?php echo set_SubSubmenu('schsettings/miscellaneous'); ?>">
                <a class="<?php echo set_SubSubmenu('schsettings/miscellaneous'); ?>" href="<?php echo site_url('schsettings/miscellaneous') ?>"><i class="fa fa-th-list"></i> <?php echo $this->lang->line('miscellaneous'); ?></a>
            </li>
            <li class="<?php echo set_SubSubmenu('schsettings/cbse_disclosure'); ?>">
                <a class="<?php echo set_SubSubmenu('schsettings/cbse_disclosure'); ?>" href="<?php echo site_url('schsettings/cbse_disclosure') ?>"><i class="fa fa-file-text-o"></i> CBSE Mandatory Disclosure</a>
            </li>
            <li class="<?php echo set_SubSubmenu('admin/systemnotificationsetting'); ?>">
                <a class="<?php echo set_SubSubmenu('admin/systemnotificationsetting'); ?>" href="<?php echo site_url('admin/systemnotificationsetting') ?>"><i class="fa fa-bell-o"></i> System Notifications</a>
            </li>
            <li class="<?php echo set_SubSubmenu('admin/aisetting'); ?>">
                <a class="<?php echo set_SubSubmenu('admin/aisetting'); ?>" href="<?php echo site_url('admin/aisetting') ?>"><i class="fa fa-magic" style="color: #8b5cf6;"></i> AI API Keys & Engines</a>
            </li>
        </ul>
    </div>
</div><!--./col-md-3--> 