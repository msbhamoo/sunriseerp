<aside class="main-sidebar" id="alert2">
    <?php if ($this->rbac->hasPrivilege('student', 'can_view')) {?>
        <form class="navbar-form navbar-left search-form2" role="search"  action="<?php echo site_url('admin/admin/search'); ?>" method="POST">
            <?php echo $this->customlib->getCSRF(); ?>
            <div class="input-group ">
                <input type="text"  name="search_text" class="form-control search-form" placeholder="<?php echo $this->lang->line('search_by_student_name'); ?>">
                <span class="input-group-btn">
                    <button type="submit" name="search" id="search-btn" style="padding: 3px 12px !important;border-radius: 0px 30px 30px 0px;" class="btn btn-flat search-btn-sm"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>
    <?php }?>
    <section class="sidebar" id="sibe-box">
        <?php // $this->load->view('layout/top_sidemenu');?>

        <ul class="sidebar-menu verttop">
            
<!-- //==================sidebar dynamic======================= -->

<?php
$side_list = side_menu_list(1);

if (!empty($side_list)) {
    foreach ($side_list as $side_list_key => $side_list_value) {

        $module_permission = access_permission_sidebar_remove_pipe($side_list_value->access_permissions);
        $module_access     = false;
        if (!empty($module_permission)) {
            foreach ($module_permission as $m_permission_key => $m_permission_value) {
                $cat_permission = access_permission_remove_comma($m_permission_value);

                if ($this->rbac->hasPrivilege($cat_permission[0], isset($cat_permission[1]) ? $cat_permission[1] : '')) {
                    $module_access = true;
                    break;
                }
            }
        }
        if ($module_access) {
            if ($this->module_lib->hasModule($side_list_value->short_code) && $this->module_lib->hasActive($side_list_value->short_code)) {

                $modern_icons = [
                    'front_office' => ['icon' => 'icon-monitor', 'color' => '#3b82f6'], // Blue
                    'student_information' => ['icon' => 'icon-users', 'color' => '#6366f1'], // Indigo
                    'fees_collection' => ['icon' => 'icon-credit-card', 'color' => '#10b981'], // Emerald
                    'income' => ['icon' => 'icon-trending-up', 'color' => '#22c55e'], // Green
                    'expense' => ['icon' => 'icon-trending-down', 'color' => '#ef4444'], // Red
                    'examinations' => ['icon' => 'icon-book-open', 'color' => '#a855f7'], // Purple
                    'attendance' => ['icon' => 'icon-calendar-check', 'color' => '#14b8a6'], // Teal
                    'online_examinations' => ['icon' => 'icon-laptop', 'color' => '#8b5cf6'], // Violet
                    'lesson_plan' => ['icon' => 'icon-calendar', 'color' => '#f59e0b'], // Amber
                    'academics' => ['icon' => 'icon-graduation-cap', 'color' => '#f97316'], // Orange
                    'human_resource' => ['icon' => 'icon-briefcase', 'color' => '#ec4899'], // Pink
                    'communicate' => ['icon' => 'icon-message-square', 'color' => '#0ea5e9'], // Sky
                    'download_center' => ['icon' => 'icon-download', 'color' => '#64748b'], // Slate
                    'homework' => ['icon' => 'icon-book-open', 'color' => '#f43f5e'], // Rose
                    'library' => ['icon' => 'icon-book', 'color' => '#d946ef'], // Fuchsia
                    'inventory' => ['icon' => 'icon-box', 'color' => '#84cc16'], // Lime
                    'transport' => ['icon' => 'icon-truck', 'color' => '#eab308'], // Yellow
                    'hostel' => ['icon' => 'icon-building', 'color' => '#06b6d4'], // Cyan
                    'certificate' => ['icon' => 'icon-file-badge', 'color' => '#eab308'], // Yellow
                    'front_cms' => ['icon' => 'icon-globe', 'color' => '#3b82f6'], // Blue
                    'alumni' => ['icon' => 'icon-users', 'color' => '#6366f1'], // Indigo
                    'reports' => ['icon' => 'icon-chart-pie', 'color' => '#f43f5e'], // Rose
                    'system_settings' => ['icon' => 'icon-settings', 'color' => '#475569'], // Slate
                    'gmeet_live_classes' => ['icon' => 'icon-video', 'color' => '#10b981'], // Emerald
                    'zoom_live_classes' => ['icon' => 'icon-video', 'color' => '#3b82f6'], // Blue
                    'behaviour_records' => ['icon' => 'icon-activity', 'color' => '#f59e0b'], // Amber
                    'multi_branch' => ['icon' => 'icon-git-merge', 'color' => '#8b5cf6'], // Violet
                    'two_factor_authentication' => ['icon' => 'icon-shield', 'color' => '#14b8a6'], // Teal
                    'online_course' => ['icon' => 'icon-monitor-play', 'color' => '#ec4899'], // Pink
                    'cbse_exam' => ['icon' => 'icon-file-text', 'color' => '#f97316'], // Orange
                    'qr_code_attendance' => ['icon' => 'icon-maximize', 'color' => '#10b981'], // Emerald
                    'holiday' => ['icon' => 'icon-sun', 'color' => '#eab308'], // Yellow
                    'student_cv' => ['icon' => 'icon-file-text', 'color' => '#6366f1'], // Indigo
                    'accounts' => ['icon' => 'icon-calculator', 'color' => '#10b981'], // Emerald
                    'scholarship_exam' => ['icon' => 'icon-award', 'color' => '#f59e0b'] // Amber/Gold
                ];

                $icon_data = isset($modern_icons[$side_list_value->lang_key]) ? $modern_icons[$side_list_value->lang_key] : ['icon' => $side_list_value->icon, 'color' => '#6b7280'];
                $icon_class = $icon_data['icon'];
                $icon_color = $icon_data['color'];
                
                // Convert hex to rgb for background tint (e.g., #3b82f6 -> 59, 130, 246)
                list($r, $g, $b) = sscanf($icon_color, "#%02x%02x%02x");
                $bg_color = "rgba($r, $g, $b, 0.12)";

                ?>

                    <li class="treeview <?php echo activate_main_menu($side_list_value->activate_menu); ?>">

                        <a href="#">
                            <i class="<?php echo $icon_class; ?>" style="color: <?php echo $icon_color; ?>;"></i> <span><?php echo !empty($this->lang->line($side_list_value->lang_key)) ? $this->lang->line($side_list_value->lang_key) : $side_list_value->menu; ?></span> <i class="icon-chevron-left pull-right"></i>
                        </a>

                                                    <?php
if (!empty($side_list_value->submenus)) {
                    ?>
                        <ul class="treeview-menu custom-secondary-panel">
                            <li class="submenu-header">
                                <span class="submenu-title"><?php echo !empty($this->lang->line($side_list_value->lang_key)) ? $this->lang->line($side_list_value->lang_key) : $side_list_value->menu; ?></span>
                                <button type="button" class="close-submenu"><i class="fa fa-times"></i></button>
                            </li>
                            <?php
foreach ($side_list_value->submenus as $submenu_key => $submenu_value) {

                        $sidebar_permission = access_permission_sidebar_remove_pipe($submenu_value->access_permissions);
                        $sidebar_access     = false;

                        if (!empty($sidebar_permission)) {
                            foreach ($sidebar_permission as $sidebar_permission_key => $sidebar_permission_value) {
                                $sidebar_cat_permission = access_permission_remove_comma($sidebar_permission_value);

                                if ($submenu_value->addon_permission != "") {
                                    if ($this->rbac->hasPrivilege($sidebar_cat_permission[0], isset($sidebar_cat_permission[1]) ? $sidebar_cat_permission[1] : '')
                                        && $this->auth->addonchk($submenu_value->addon_permission, false)) {
                                        $sidebar_access = true;
                                        break;
                                    }
                                } else {
                                    if ($this->rbac->hasPrivilege($sidebar_cat_permission[0], isset($sidebar_cat_permission[1]) ? $sidebar_cat_permission[1] : '')) {
                                        $sidebar_access = true;
                                        break;
                                    }
                                }
                            }
                        }

                        if ($sidebar_access) {
                            if (!empty($submenu_value->permission_group_id)) {
                                if (!$this->module_lib->hasActive($submenu_value->short_code)) {
                                    continue;
                                }
                            }

                            ?>

                        <li class="<?php echo activate_submenu($submenu_value->activate_controller, explode(',', (string)$submenu_value->activate_methods)); ?>"><a href="<?php echo site_url($submenu_value->url); ?>"><i class="icon-circle-small"></i><?php echo !empty($this->lang->line($submenu_value->lang_key)) ? $this->lang->line($submenu_value->lang_key) : $submenu_value->menu; ?></a></li>

                          <?php
}

                    }

                    if ($side_list_value->lang_key == 'student_information') {
                        if ($this->rbac->hasPrivilege('student_dashboard', 'can_view')) {
                            ?>
                            <li class="<?php echo set_Submenu('studentreport/custom_report'); ?>"><a href="<?php echo site_url('studentreport/custom_report'); ?>"><i class="icon-circle-small"></i>Custom Report</a></li>
                            <?php
                        }
                        if ($this->rbac->hasPrivilege('student_call_log', 'can_view')) {
                            ?>
                            <li class="<?php echo set_Submenu('admin/studentcall'); ?>"><a href="<?php echo site_url('admin/studentcall'); ?>"><i class="icon-circle-small"></i>Student Call Log</a></li>
                            <?php
                        }
                    }

                    ?>
                        </ul>
                            <?php

                }
                ?>
                                </li>
                            <?php
}
        }
    }
}
?>
        <!-- //==================sidebar dynamic======================= -->

        </ul>
    </section>
</aside>

<style>
/* Sidebar Container Separation */
.main-sidebar {
    border-right: 1px solid #9ca3af !important; /* Much darker gray for high visibility */
}

/* Modern Elegant Scrollbar for Sidebar */
.main-sidebar, .sidebar, .sidebar-menu, .slimScrollDiv {
    scrollbar-width: thin !important;
    scrollbar-color: #d1d5db transparent !important;
}

.main-sidebar ::-webkit-scrollbar,
.sidebar-menu ::-webkit-scrollbar,
.slimScrollDiv ::-webkit-scrollbar {
    width: 4px !important;
}
.main-sidebar ::-webkit-scrollbar-track,
.sidebar-menu ::-webkit-scrollbar-track,
.slimScrollDiv ::-webkit-scrollbar-track {
    background: transparent !important;
}
.main-sidebar ::-webkit-scrollbar-thumb,
.sidebar-menu ::-webkit-scrollbar-thumb,
.slimScrollDiv ::-webkit-scrollbar-thumb {
    background: #d1d5db !important;
    border-radius: 4px !important;
}

/* Force SlimScroll plugin to be thin and gray */
div.slimScrollBar {
    background: #d1d5db !important;
    width: 4px !important;
    border-radius: 4px !important;
    opacity: 0.8 !important;
}
div.slimScrollRail {
    display: none !important;
}

/* Gap between Top Bar and Sidebar */
.sidebar-menu {
    margin-top: 15px !important;
}

/* Global SaaS Aesthetic Overrides */
.sidebar-menu > li > a {
    font-family: 'Inter', -apple-system, sans-serif !important;
    font-weight: 500 !important;
    color: #4b5563 !important;
    transition: all 0.2s ease !important;
    display: flex !important;
    align-items: center !important;
}

/* Modern Boxed Icons */
.sidebar-menu > li > a > i {
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
    width: 34px !important;
    height: 34px !important;
    border-radius: 8px !important;
    font-size: 18px !important;
    margin-right: 12px !important;
    transition: all 0.2s ease !important;
}

/* Hover State - Soft Background */
.sidebar-menu > li:hover > a > i {
    filter: brightness(0.9) !important;
}

/* Active State - Tinted Box (Glassmorphism/SaaS feel) */
.sidebar-menu > li.active > a > i {
    filter: brightness(0.85) saturate(1.5) !important;
    box-shadow: inset 0 0 0 1px rgba(0,0,0,0.05) !important;
}

/* Active Text */
.sidebar-menu > li.active > a {
    color: #111827 !important;
    font-weight: 600 !important;
}

/* Fix Dropdown Chevron Alignment in Flex */
.sidebar-menu > li > a > .pull-right,
.sidebar-menu > li > a > .icon-chevron-left {
    margin-left: auto !important;
    transition: transform 0.2s ease !important;
}

/* Force Text and Icon Perfect Centering */
.sidebar-menu > li > a > span {
    line-height: 1 !important;
    margin-top: 0 !important;
    margin-bottom: 0 !important;
    padding-top: 2px !important; /* Slight optical adjustment for standard fonts */
}

.sidebar-menu > li > a > i {
    margin-top: 0 !important;
    margin-bottom: 0 !important;
}

/* =========================================
   Submenu & Flyout Polish
   ========================================= */
.sidebar-menu .treeview-menu {
    border-radius: 12px !important;
    box-shadow: 0 4px 24px rgba(0,0,0,0.08), 0 1px 4px rgba(0,0,0,0.04) !important;
    border: 1px solid #e5e7eb !important;
    padding: 8px 0 !important;
    margin: 0 0 0 -1px !important; /* Close the gap and overlap the border slightly */
    background: #ffffff !important;
}

@media (min-width: 768px) {
    .sidebar-menu .treeview-menu {
        min-width: 240px !important; /* Ensure it has enough width on desktop so header doesn't wrap */
    }
}

/* Submenu Flyout Header */
.submenu-header {
    padding: 12px 16px !important;
    border-bottom: 1px solid #f3f4f6 !important;
    margin-bottom: 8px !important;
    display: flex !important;
    justify-content: space-between !important;
    align-items: center !important;
    background: #ffffff !important;
    margin-top: -8px !important;
    border-top-left-radius: 12px !important;
    border-top-right-radius: 12px !important;
}

.submenu-header .submenu-title {
    font-weight: 600 !important;
    font-size: 12px !important;
    color: #9ca3af !important;
    text-transform: uppercase !important;
    letter-spacing: 1px !important;
    margin: 0 !important;
    line-height: 1.4 !important;
}

.submenu-header .close-submenu {
    background: #f3f4f6 !important;
    border: none !important;
    color: #6b7280 !important;
    cursor: pointer !important;
    transition: all 0.2s !important;
    width: 26px !important;
    height: 26px !important;
    border-radius: 50% !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 0 !important;
}

.submenu-header .close-submenu:hover {
    background: #fee2e2 !important;
    color: #ef4444 !important;
}

/* Submenu Links */
.sidebar-menu .treeview-menu > li > a {
    color: #4b5563 !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    padding: 8px 16px !important;
    border-radius: 6px !important;
    margin: 2px 12px !important;
    transition: all 0.2s ease !important;
    display: block !important;
}

/* Submenu Hover */
.sidebar-menu .treeview-menu > li > a:hover {
    background-color: #f3f4f6 !important;
    color: #111827 !important;
}

/* Submenu Active State */
.sidebar-menu .treeview-menu > li.active > a {
    color: var(--primary-theme-color, #3b82f6) !important;
    font-weight: 600 !important;
    background-color: #f0f9ff !important;
}

/* Completely hide the old dots that cause overlapping */
.sidebar-menu .treeview-menu > li > a > .icon-circle-small,
.sidebar-menu .treeview-menu > li > a > i {
    display: none !important;
}
</style>
